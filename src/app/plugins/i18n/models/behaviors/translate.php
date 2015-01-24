<?php

/**
 * 	TranslateBehavior class.
 *
 * 	Este archivo contiene la definicion de la clase TranslateBehavior.
 */

/**
 * 	Esta clase maneja los campos que deben estar en varios idiomas
 *
 * 	@package		app
 * 	@subpackage		app.plugin.i18n.model.behavior
 */
class TranslateBehavior extends ModelBehavior {

	/**
	 * Nombre de este behavior
	 * @var String
	 */
	var $name = "Translate";
	/**
	 * Guarda los idiomas en que el sitio esta disponibles se obtienen de Configure::read("I18n.Langs");
	 * @var Array
	 */
	var $locale = array( );
	/**
	 * Su valor es true si en la configuracion resivida del modelo vienen campos traducibles
	 * @var Boolean
	 */
	var $enable = false;
	/**
	 * Guarda los campos traducibles que le llegan en el arreglo $query['fields'] en el callback beforeFind
	 * @var Array
	 */
	var $traducibles = array( );
	/**
	 * Indica si se pidio traducir a mas idiomas los resultados
	 * @var Boolean
	 */
	var $changeLocale = false;
	/**
	 * Guarda toda la estructura de containy los datos datos que se ponen en los modelos cuando se hace inidica una relacion
	 * @var Array
	 */
	var $contains = array( );
	/**
	 * Modelo que contiene las traducciones de todos los modelos
	 * @var Model
	 */
	var $I18n;
	/**
	 * Referencia al controller donde se esta utilizando el modelo que se comporta como este behavior
	 * @var unknown_type
	 */
	var $controller;
	/**
	 * Referencia al modelo que actua como este Behavior
	 * @var Model
	 */
	var $Model;
	/**
	 * Alias del modelo que esta ejecutando este behavior
	 * @var String
	 */
	var $alias;
	/**
	 * Copia del arreglo $query que llega como parametro al callback beforeFind, en este se hacen las modificaciones necesarias para
	 * obtener las traducciones de los campos
	 * @var Array
	 */
	var $query;
	/**
	 * Modelos a los que no se les permite utilizar el behavior, este arreglo se llena desde la configuracion del behavior
	 * $config['notAllow']
	 * @var Array
	 */
	var $notAllow;

	/**
	 * Regresa true si el modelo es 'Aro','Aco','Grupo','Usuario','Idioma','Traduccion','IdiomaTraduccion','Pagina'
	 * a estos modelos no se les permite utilizar los metodos del behavior
	 * @param $Model Model que actua como este behavior
	 * @return Boolean
	 */
	function __notAllow ( &$Model ) {
		//debug($Model->name);
		return in_array($Model->name, $this->notAllow);
	}

	/**
	 * 	Callback: se ejecuta al crear el behavior
	 *
	 * 	$config Configuracion para el behavior Translate
	 * 	Se indica aqui los campos que son traducibles para el modelo
	 * 	array('field_one','field_two','field_three')
	 *
	 * 	@param Model $Model modelo que actua como este Behavior.
	 * 	@param array $config
	 * 	@return mixed
	 * 	@access public
	 */
	function setup ( &$Model, $config = array( ) ) {
		if ( isset($config['notAllow']) ) {
			$this->notAllow = array_unique($config['notAllow']);
			unset($config['notAllow']);
		}
		if ( $this->__notAllow($Model) ) {
			return false;
		}
		$this->enable[$Model->alias]=false;
		$this->I18n = ClassRegistry::init('I18n.Field');
		if ( !empty($config) ) {
			$this->settings[$Model->alias] = $config;
			$this->enable[$Model->alias] = true;
			$currentLocale = Configure::read("I18n.Locale");
			$idiomas = array_keys(Configure::read("I18n.Langs"));

			#esto se hace para poder saber en todo el sitio los campos traducibles para cada modelo
			Configure::write("I18n.Fields.$Model->alias", $this->settings[$Model->alias]);

			foreach ( $Model->validate as $field => $rules ) {
				if ( in_array($field, $this->settings[$Model->alias]) ) {
					foreach ( $rules as $rule => $options ) {
						$locale = $idiomas;
						if ( is_array($options) ) {
							if ( isset($options['langs']) ) {
								if ( is_string($options['langs']) ) {
									$locale = array( $options['langs'] );
									if ( $options['langs'] == "*" ) {
										$locale = $idiomas;
									}
								} else {
									$locale = $options['langs'];
								}
								unset($options['langs']);
							} else {
								$locale = (array)$currentLocale;
							}
							foreach ( $locale as $_locale ) {
								$Model->validate[$field . "_" . $_locale][$rule] = $options;
							}
						} else {
							trigger_error("Las reglas de valiciones no tienen la estructura correcta para el campo " . $Model->alias . '.' . $field, E_USER_NOTICE);
						}
					}
					unset($Model->validate[$field]);
				}
			}
		}
	}

	/**
	 * 	TranslateBehavior::breforeValidate
	 * 	callback
	 *
	 * 	@param Model $Model modelo que actua como este Behavior.
	 * 	@param Boolean $created indica si hizo insert o update.
	 * 	@return void
	 * 	@access public
	 */
	function beforeValidate ( &$Model ) {
		if ( isset($this->enable[$Model->alias]) && $this->enable[$Model->alias] ) {
			$currentLocale = Configure::read("I18n.Locale");
			$idiomas = array_keys(Configure::read("I18n.Langs"));
			foreach ( $Model->validate as $fieldName => $rules ) {
				if ( preg_match('/(.+)(_([a-z]{2}))$/', $fieldName, $matches) ) {
					$field = $matches[1];
					$locale = $matches[3];
					if ( in_array($field, $this->settings[$Model->alias]) ) {
						foreach ( $rules as $rule => $options ) {
							if ( $options['rule'] == 'isUnique' ) {
								$Model->validate[$fieldName][$rule]['rule'] = array( 'isUniqueTraduction' );
								unset($Model->validate[$field][$rule]);
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Metodo que simula la validacion isUnique para un campo traducible
	 *
	 * @param $Model Model que actua como este behavior
	 * @param $value array que contiene el valor del campo (ej: array('titulo'=>'titulo de ejemplo'))
	 * @return unknown_type
	 */
	function isUniqueTraduction ( $Model, $value ) {
		/*
		 * @TODO evitar que invalide el el campo si el que encuentra el query es el mismo que se edita
		 */
		//pr($Model->data);
		foreach ( $value as $fieldName => $_value ) {
			if ( preg_match('/(.+)(_([a-z]{2}))$/', $fieldName, $matches) ) {
				$this->I18n->Behaviors->disable("Translate");
				isset($Model->data[$Model->alias]['id']) ? $id = $Model->data[$Model->alias]['id'] : $id = 0;
				return!$this->I18n->find('count', array( 'conditions' => array( 'model' => $Model->name, 'field' => $matches[1], 'locale' => $matches[3], 'content' => $_value, 'foreign_key <>' => $id ) ));
			}
		}
	}

	/**
	 * 	TranslateBehavior::afterSave
	 * 	callback
	 *
	 * 	@param Model $Model modelo que actua como este Behavior.
	 * 	@param Boolean $created indica si hizo insert o update.
	 * 	@return void
	 * 	@access public
	 */
	function afterSave ( &$Model, $created ) {
		#$dbo = $Model->getDatasource();
		#$this->log(current(end($dbo->_queriesLog)),"Translate");
		//$this->log($this->settings,"Translate");
		if ( isset($this->settings[$Model->alias]) ) {
			$idiomas = Configure::read("I18n.Langs");
			$locale = Configure::read("I18n.Locale");//Clave del idioma
			$data = array( );
			$tableName = $this->I18n->tablePrefix . $this->I18n->table;
			$query = "REPLACE INTO $tableName (locale,model,foreign_key,field,content) values ";
			$tradujo = false;
			App::import('Core', 'Sanitize');
			foreach ( $this->settings[$Model->alias] as $field ) {
				if ( isset($Model->data[$Model->alias][$field . '_' . $locale]) || isset($Model->data[$Model->alias][$field]) ) {

					$content = isset($Model->data[$Model->alias][$field]) ? $Model->data[$Model->alias][$field] : $Model->data[$Model->alias][$field . '_' . $locale];
					$query.="('" . $locale . "','" . $Model->name . "'," . $Model->id . ",'" . $field . "','" . Sanitize::escape($content) . "'),";

					foreach ( $idiomas as $_locale => $idioma ) {
						if ( isset($Model->data[$Model->alias][$field . '_' . $_locale]) ) {
							$query.="('" . $_locale . "','" . $Model->name . "'," . $Model->id . ",'" . $field . "','" . Sanitize::escape($Model->data[$Model->alias][$field . '_' . $_locale]) . "'),";
						}
					}
					$tradujo = true;
				}

			}
			if ( $tradujo ) {
				$query = substr($query, 0, -1);
				$this->I18n->query($query);
			}
		}
		#$this->log(current(end($dbo->_queriesLog)),"Translate");
	}

	/**
	 * Obtiene el arreglo contain que se el envia al behavior Containable para despues mesclarlo con el arreglo $query['contains']
	 * @return Array
	 */
	function __getContainableParams () {
		if ( $this->Model->Behaviors->enabled("Containable") && isset($this->Model->Behaviors->Containable->runtime[$this->Model->alias]['contain']) ) {
			$contain = $this->Model->Behaviors->Containable->runtime[$this->Model->alias]['contain'];
			$this->Model->Behaviors->Containable->runtime[$this->Model->alias]['contain'] = array( );
			return $contain;
		}
		return array( );
	}

	/**
	 * 	TranslateBehavior::beforeFind
	 * 	callback
	 *
	 * 	@param Model $Model modelo que actua como este Behavior.
	 * 	@param array $query
	 * 	@return array Modified query
	 * 	@access public
	 */
	function beforeFind ( &$Model, $query ) {
		/*
		 * @TODO checar por que trae todos los modelos relacionados aun cuando no se le indica ningun contain
		 * y solo cuando se hace desde un controller que no es el propio al modelo
		 */

		#pr($this->settings);

		if ( $this->__notAllow($Model) ) {
			return $query;
		}

		$this->Model = $Model;
		$this->alias=$Model->alias;
		$this->query[$this->alias] = $query;
		$this->contains[$this->alias] = array( );
		$this->traducibles[$this->alias] = array( );


		if ( empty($this->locale) ) {
			$this->locale = array( Configure::read("I18n.Locale") );
		}
		$locale = $this->locale;

		$db = & ConnectionManager::getDataSource($Model->useDbConfig);
		$tablePrefix = $this->I18n->tablePrefix;

		(!empty($this->query[$this->alias]['contain'])) ? $this->query[$this->alias]['contain'] = am($this->query[$this->alias]['contain'], $this->__getContainableParams()) : $this->query[$this->alias]['contain'] = $this->__getContainableParams();

		# se pasan los joins a una variable auxiliar para poner hasta al final estos joins
		if ( is_string($this->query[$this->alias]['joins']) ) {
			$joins = array( $this->query[$this->alias]['joins'] );
		} elseif ( is_array($this->query[$this->alias]['joins']) ) {
			$joins = $this->query[$this->alias]['joins'];
			$this->query[$this->alias]['joins'] = array( );
		}

		#esto se hace cuando se usa el paginate, ya que este primero hace un count de los elementos
		#pr($this->query);
		if ( is_string($this->query[$this->alias]['fields']) && 'COUNT(*) AS ' . $db->name('count') == $this->query[$this->alias]['fields'] ) {
			$this->query[$this->alias]['fields'] = 'COUNT(DISTINCT(' . $db->name($Model->alias . '.' . $Model->primaryKey) . ')) ' . $db->alias . 'count';

			if ( is_string($this->query[$this->alias]['joins']) ) {
				$this->query[$this->alias]['joins'] = array( $this->query[$this->alias]['joins'] );
			}

			$this->query[$this->alias]['joins'][] = array(
				'type' => 'LEFT',
				'alias' => $this->I18n->alias,
				'table' => $db->name($tablePrefix . $this->I18n->useTable),
				'conditions' => array(
					$Model->alias . '.' . $Model->primaryKey => $db->identifier($this->I18n->alias . '.foreign_key'),
					$this->I18n->alias . '.model' => $Model->name,
					$this->I18n->alias . '.locale' => $locale
				)
			);
			#pr($this->query);

			#se ponen los joins que venian originalmente en $query['joins'] al final del arreglo
			if ( !empty($joins) ) {
				foreach ( $joins as $join ) {
					$this->query[$this->alias]['joins'][] = $join;
				}
			}

			if ( !empty($this->query[$this->alias]['contain']) ) {
				$this->__changeContain($this->query[$this->alias]['contain'], $this->contains[$this->alias], $this->Model);
			}

			if ( !empty($this->contains[$this->alias]) ) {
				$this->__containsToJoins($this->contains[$this->alias]);
			}

			$this->__changeFields();

			if ( !empty($this->query[$this->alias]['conditions']) ) {
				$this->__changeConditions($this->query[$this->alias]['conditions']);
			}

			if ( !empty($this->query[$this->alias]['order']) ) {
				$this->query[$this->alias]['order'] = $this->__changeOrder($this->query[$this->alias]['order']);
			}



			//Debug::dump($this->query,'fin count');
			return $this->query[$this->alias];
		}
		#pr($this->query['fields']);
		#si no se especifica ningun campo se traen todos los campos del modelo
		if ( empty($this->query[$this->alias]['fields']) ) {
			$traducibles = array( );
			if ( $this->isTranslate($this->Model) ) {
				$traducibles = $this->Model->Behaviors->Translate->settings[$this->Model->alias];
			}
			#pr($traducibles);
			foreach ( am(array_keys($this->Model->schema()), $traducibles) as $tableField ) {
				$this->query[$this->alias]['fields'][] = sprintf("%s.%s", $this->Model->alias, $tableField);
			}
			#$this->query['fields'][]=$Model->alias.".*";
		}
		#pr($this->query['fields']);

		#se ponen los joins que venian originalmente en $query['joins'] al final del arreglo
		if ( !empty($joins) ) {
			foreach ( $joins as $join ) {
				$this->query[$this->alias]['joins'][] = $join;
			}
		}

		if ( !empty($this->query[$this->alias]['contain']) ) {
			$this->__changeContain($this->query[$this->alias]['contain'], $this->contains[$this->alias], $this->Model);
		}

		if ( !empty($this->contains[$this->alias]) ) {
			$this->__containsToJoins($this->contains[$this->alias]);
		}

		//Debug::dump($this->query['fields']);
		#Se quitan los campos traducibles para los modelos afectados
		$this->__changeFields();
		#debug($this->traducibles);

		#Esto es parte del translate behavior y no se para que es
		if ( is_array($this->query[$this->alias]['fields']) ) {
			$this->query[$this->alias]['fields'] = array_merge($this->query[$this->alias]['fields']);
		}

		if ( !empty($this->query[$this->alias]['conditions']) ) {
			$this->__changeConditions($this->query[$this->alias]['conditions']);
		}

		if ( !empty($this->query[$this->alias]['order']) ) {
			$this->query[$this->alias]['order'] = $this->__changeOrder($this->query[$this->alias]['order']);
		}


		#pr($this->query);
		#pr($this->contains);

		return $this->query[$this->alias];
	}

	/**
	 * 	Cambia los campos que son traducibles es decir modifica el arreglo $query['fields']
	 * 	@access private
	 */
	/* @var $Model Model */

	function __changeFields () {

		$locale = $this->locale;
		$db = & ConnectionManager::getDataSource($this->Model->useDbConfig);
		$tablePrefix = $this->I18n->tablePrefix;

		if ( is_array($this->query[$this->alias]['fields']) ) {
			$traducibles = array( );
			foreach ( $this->query[$this->alias]['fields'] as $key => $field ) {
				$pieces = explode(".", $field);
				#pr($field);
				preg_match_all('/([A-Z]{1}[A-Za-z0-9-_]+)\.([A-Za-z0-9-_]+)/',$field,$matches,PREG_SET_ORDER);
				#pr($matches);

				if(empty($matches)){
					$matches[]=array("",$this->Model->alias,$field);
				}
				#pr($pieces);
				foreach( $matches as $match ){
					$_model=$match[1];
					$_field=$match[2];
					#pr($_model.".".$_field);

					if ( $_model != $this->Model->alias ) {
						$Model = &ClassRegistry::getObject($_model);
						if ( $this->isTranslate($this->Model, $Model) ) {
							if ( isset($Model->Behaviors->Translate->settings[$_model]) && in_array($_field, $Model->Behaviors->Translate->settings[$_model]) ) {
								$traducibles[$_model][] = $_field;
							} elseif ( $_field == "*" ) {
								$traducibles[$_model] = $Model->Behaviors->Translate->settings[$_model];
							}
						}
					} else {
						if ( $this->isTranslate($this->Model) ) {
							if ( in_array($_field, $this->settings[$_model]) ) {
								$traducibles[$_model][] = $_field;
							} else if ( $_field == "*" ) {
								$traducibles[$_model] = $this->settings[$_model];
							}
						}
					}
				}

				#pr($traducibles);

				if ( isset($traducibles[$_model]) && ($_field == "*" || in_array($_field, $traducibles[$_model])) ) {

					#  @TODO Respetar el orden en que se envian los campos

					#pr($traducibles);
					$currentLocale = Configure::read("I18n.Locale");
					if ( is_array($locale) ) {

						foreach ( $traducibles[$_model] as $fieldTranslate ) {
							foreach ( $locale as $_locale ) {
								if(count($matches) > 1){
									$replacement = $this->__replaceFields($this->query[$this->alias]['fields'][$key]);

									if ( $replacement != $this->query[$this->alias]['fields'][$key] && !empty($replacement) ) {
										$this->query[$this->alias]['fields'][$key] = $replacement;
									}
								}else{
									if ( $currentLocale == $_locale && $_field != "*" ) {
										$this->query[$this->alias]['fields'][$key] = $_model . '__' . $fieldTranslate . '__' . $_locale . '.content';
									} else {
										$this->query[$this->alias]['fields'][] = $_model . '__' . $fieldTranslate . '__' . $_locale . '.content';
									}
								}

								$Model = ClassRegistry::getObject($_model);
								//pr($Model->alias);
								$this->__addTranslateJoin($Model, $fieldTranslate, $_locale);
							}
						}
						//unset($this->query['fields'][$key]);
					} else {
						trigger_error("No se han especificados los idiomas disponibles para este sitio en el core Configure::write('I18n.Locale')", E_USER_ERROR);
					}
				}
			}
			#debug($this->Model->alias);
			//pr($traducibles);
			$this->traducibles[$this->alias] = $traducibles;
		}
	}/**/
	/**
	function __changeFields () {

		$locale = $this->locale;
		#$db = & ConnectionManager::getDataSource($this->Model->useDbConfig);
		#$tablePrefix = $this->I18n->tablePrefix;

		if ( is_array($this->query[$this->alias]['fields']) ) {
			$traducibles = array( );
			foreach ( $this->query[$this->alias]['fields'] as $key => $field ) {
				$replacement = $this->__replaceFields($field);
				if ( $replacement != $field && !empty($replacement) ) {
					$this->query[$this->alias]['fields'][$key] = $replacement;
				}
			}
			#debug($this->Model->alias);
			$this->traducibles[$this->alias] = $traducibles;
		}
	}
	/**/



	/**
	 * 	Cambia los campos que son traducibles en los conditions
	 * 	ejemplo: si se envia como parametro en el conditions=>array(Model.nombre=>'value') y es un campo tradocuble lo cambiara a
	 * 	Model__nombre__{locale}
	 *
	 * 	Nota 1: Esta funcion solo esta considerando el caso en que se envian los conditions como un array
	 * 	y se use la notacion Modelo.campo
	 * 	ejemplo:$query['conditions']=array('Model.campo'=>{valor},'Modelo.otro_campo'=>{valor})
	 *
	 * 	Nota 2: Si el campo que se esta usando como condicional es del modelo actual entonces
	 * 	agregara el campo a $query['fields'], si este es array lo agrega como un elemento mas del array
	 * 	si es String lo concatena a $query['fields'], de lo contrario se espera que en el metodo unsetContain
	 * 	se agreguen a $query['fields'] esots campos traducibles
	 *
	 * 	@param Model $model modelo que actua como este Behavior.
	 * 	@param Model $order arreglo order del los contain o find.
	 * 	@return array $order modificado
	 * 	@access private
	 */
	function __changeConditions ( &$conditions ) {
		$_locale = Configure::read("I18n.Locale");
		if ( is_array($conditions) ) {
			foreach ( $conditions as $key => $value ) {
				if ( empty($value) && $value !== 0 && $value !== "0" ) {
					unset($conditions[$key]);
				} else {
					if ( is_numeric($key) && is_array($value) ) {
						$this->__changeConditions($conditions[$key]);
					} else if ( is_numeric($key) && is_string($value) ) {
						$replacement = $this->__replaceFields($value);
						if ( $replacement != $value && !empty($replacement) ) {
							$conditions[$key] = $replacement;
						}
					} else if ( in_array(up($key), array( 'NOT', 'AND', 'XOR', 'OR' )) ) {
						$this->__changeConditions($conditions[$key]);
					} else if ( is_string($key) ) {
						$replacement = $this->__replaceFields($key);
						if ( $replacement != $key && !empty($replacement) ) {
							$conditions[$replacement] = $value;
							unset($conditions[$key]);
						}
					}
				}
			}
		} else if ( is_string($conditions) ) {
			$replacement = $this->__replaceFields($conditions);
			if ( $replacement != $conditions && !empty($replacement) ) {
				$conditions = $replacement;
			}
		}
	}

	/**
	 * Busca los campos traducibles en un cadena y regresa la cadena con los cambios necesarios para q los campos se traduscan
	 * (ej. "Modelo.campo asc" retornara Modelo__campo__{idioma}.content asc)
	 * @param $toReplace
	 * @return unknown_type
	 */
	function __replaceFields ( $toReplace ) {
		return preg_replace_callback('/([A-Z]{1}[A-Za-z0-9-_]+)\.([A-Za-z0-9-_]+)/', array( $this, '__replaceMatches' ), $toReplace);
	}

	/**
	 * Callback que se llama en la funcion preg_replace_callback que busca Modelo.campo
	 * se encarga de remplazar las cambiar las coincidencias para que sean traducibles
	 * @param $matches
	 * @return $match string
	 */
	function __replaceMatches ( $matches ) {
		#pr($matches);
		$_locale = Configure::read("I18n.Locale");
		list($match, $_model, $_field) = $matches;

		$traducible = $_model . "__" . $_field . "__" . $_locale . ".content";

		$tradujo = false;
		$Model = null;
		if ( $_model != $this->Model->alias ) {
			$Model = &ClassRegistry::getObject($_model);
		}

		if ( is_object($Model) && $this->isTranslate($this->Model, $Model) && in_array($_field, $Model->Behaviors->Translate->settings[$Model->alias]) ) {
			$this->__addTranslateJoin($Model, $_field, $_locale);
			return $traducible;
		} elseif ( $this->isTranslate($this->Model) && isset($this->settings[$_model]) && in_array($_field, $this->settings[$_model]) ) {

			if ( is_array($this->query[$this->alias]['fields']) && !in_array($traducible, $this->query[$this->alias]['fields']) ) {
				#pr("rochin");
				#$this->query[$this->alias]['fields'][] = $traducible;
				#$this->__addTranslateJoin(ClassRegistry::getObject($_model), $_field, $_locale);
				$this->__addTranslateJoin(ClassRegistry::getObject($_model), $_field, $_locale);
			} else if ( is_string($this->query[$this->alias]['fields']) && !stripos($this->query[$this->alias]['fields'], $traducible) ) {
				$this->query[$this->alias]['fields'].="," . $traducible;

				#se agrega aqui el left join para el campo traducible en caso de que el $this->query['fields'] sea cadena por que no lo agrega el unsetFields cuando es String
				$this->__addTranslateJoin(ClassRegistry::getObject($_model), $_field, $_locale);
			}
			return $traducible;
		}
		return $match;
	}

	/**
	 * TraduvibleBehavior::__changeContain
	 * Funcion recursiva que se encarga de obtener todos los modelos en los diferentes niveles del arreglo $query['contain']
	 * para despues convertirlos en joins como si estuviesen en el primer nivel
	 *
	 * @param Model $parent nombre del modelo padre
	 * @param int $level nivel actual del arreglo
	 * @return void
	 */
	function __changeContain ( &$contain, &$buffer, $parent=null, $level=0 ) {
		#Debug::dump($contain);
		foreach ( $contain as $key => $value ) {
			if ( is_numeric($key) ) {
				$model = $value;
				foreach ( array( 'belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany' ) as $relation ) {
					foreach ( $parent->{$relation} as $_model => $options ) {
						if ( is_numeric($_model) ) {
							$_model = $options;
							$options = array( );
						}
						if ( $model == $_model ) {
							if ( in_array($relation, array( 'belongsTo', 'hasOne' )) ) {
								$buffer[$_model] = array(
									'options' => $options,
									'padre' => $parent->alias,
									'level' => $level,
									'relation' => $relation
								);
								unset($contain[$key]);
							}
							if ( in_array($relation, array( 'hasMany', 'hasAndBelongsToMany' )) ) {
								$buffer[$_model] = array(
									'options' => $options,
									'padre' => $parent->alias,
									'level' => $level,
									'relation' => $relation
								);
								unset($contain[$key]);
							}
						}
					}
				}
			} else {
				$model = $key;
				foreach ( array( 'belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany' ) as $relation ) {
					foreach ( $parent->{$relation} as $_model => $options ) {
						if ( is_numeric($_model) ) {
							$_model = $options;
							$options = array( );
						}
						if ( $model == $_model ) {
							if ( in_array($relation, array( 'hasMany', 'hasAndBelongsToMany' )) ) {
								$innerContain = $this->__getModelsNames($contain[$model]);
								$buffer[$model] = array(
									'options' => am($options, $contain[$model], array( 'contain' => $innerContain )),
									'padre' => $parent->alias,
									'level' => $level,
									'relation' => $relation
								);
								unset($contain[$key]);
							} else if ( in_array($relation, array( 'belongsTo', 'hasOne' )) ) {
								$options = am($options, $contain[$key]);
								$innerContain = $this->__getModelsNames($contain[$_model]);
								$buffer[$model] = array(
									'options' => am($options, $contain[$model]),
									'padre' => $parent->alias,
									'level' => $level,
									'relation' => $relation
								);
								$Model = &ClassRegistry::getObject($model);
								if ( is_object($Model) && $this->isTranslate($this->Model, $Model) ) {
									$this->traducibles[$this->alias][$Model->alias] = $Model->Behaviors->Translate->settings[$Model->alias];
								}
								$this->__changeContain($innerContain, $buffer[$model], $parent->{$_model}, $level + 1);
								unset($contain[$key]);
							}
						}
					}
				}
			}
		}
	}

	/**
	 * 	Quita los modelos que viene en el arreglo $query['contain'] para tratarlos en este behavior y evitar que los procese el behavior
	 * 	Containable.
	 * 	en el caso de los modelos con relacion belongsTo y hasOne se hace un left Join si es traducible se hancen los left joins correspondientes
	 * 	a cada campo traducible esto es en todos los niveles del contain
	 *
	 * 	en caso de ser modelos con relacion hasMany o hasAndBelongsToMany, si estos tienen campos trducibles se quitan del arreglo y se guardan
	 * 	en $this->contain para tratarlos posteriormente en el callback afterFind, de lo contrario, es decir si no tiene campos traducibles,
	 * 	no se removeran del arreglo siempre y cuando esten en el primer niverl del arreglo.
	 *
	 * 	@return void
	 * 	@access private
	 */
	/* @var $Model Model */
	function __containsToJoins ( $contains ) {
		//Debug::dump($contains,$this->Model->alias.' contains');
		foreach ( $contains as $model => $value ) {
			$Model = &ClassRegistry::getObject($model);
			$db = & ConnectionManager::getDataSource($Model->useDbConfig);
			//$tablePrefix = $db->config['prefix'];
			if ( in_array($value['relation'], array( 'hasOne', 'belongsTo' )) ) {
				/*
				 * @TODO pensar la forma de que sea mas generico la manera en que se maneja el foreing key de los modelos
				 * 	ahorita se considera que cumple con la convencion q cake plantea
				 */

				if ( $value['relation'] == 'hasOne' ) {
					$foreignKey = $value['options']['foreignKey'];
					$conditions = am(array( $model . '.' . $foreignKey . ' = ' . $value['padre'] . '.id' ), $value['options']['conditions']);
				} else {
					$foreignKey = $value['options']['foreignKey'];
					$conditions = array( $model . '.id = ' . $value['padre'] . '.' . $foreignKey );
				}

				$this->query[$this->alias]['joins'][] = array(
					'type' => 'LEFT',
					'alias' => $model,
					'table' => $db->fullTableName($Model),
					'conditions' => $conditions
				);
				$queryFields = array( );
				if ( !empty($value['options']['fields']) ) {
					if ( is_string($value['options']['fields']) ) {
						$queryFields = am($queryFields['fields'], $value['options']['fields']);
						if ( !in_array($model . '.id', $this->query[$this->alias]['fields']) ) {
							$queryFields[] = $model . '.id';
						}
					} elseif ( !is_string($this->query[$this->alias]['fields']) ) {
						foreach ( $value['options']['fields'] as $fieldName ) {
							//pr($fieldName);
							$queryFields[] = $fieldName;
							if ( !in_array($model . '.id', $this->query[$this->alias]['fields']) ) {
								$queryFields[] = $model . '.id';
							}
						}
					}
					$added = false;
					if ( !empty($queryFields) ) {
						foreach ( $queryFields as $_queryField ) {
							if ( !in_array($_queryField, $this->query[$this->alias]['fields']) ) {
								$this->query[$this->alias]['fields'][] = $_queryField;
								if ( preg_match('/(.+)\.(.+)/', $_queryField, $matches) ) {
									if ( $matches[1] == $model ) {
										$added = true;
									}
								}
							}
						}
					}
				} elseif ( is_array($this->query[$this->alias]['fields']) ) {
					$added = false;
					foreach ( $this->query[$this->alias] as $_queryField ) {
						if ( in_array($_queryField, $this->query[$this->alias]['fields']) ) {
							if ( preg_match('/(.+)\.(.+)/', $_queryField, $matches) ) {
								if ( $matches[1] == $model ) {
									$added = true;
								}
							}
						}
					}
					if ( !$added ) {
						$traducibles = array( );
						if ( $this->isTranslate($this->Model, $Model) ) {
							$traducibles = $Model->Behaviors->Translate->settings[$model];
						}
						foreach ( am(array_keys($Model->schema()), $traducibles) as $tableField ) {
							$this->query[$this->alias]['fields'][] = sprintf("%s.%s", $Model->alias, $tableField);
						}
						#$this->query['fields'][]=$model.".*";
					}
				}

				if ( !empty($value['options']['order']) ) {
					$this->query[$this->alias]['order'] = am($this->query[$this->alias]['order'], $value['options']['order']);
				}

				/*
				 * @TODO separar los conditions que se ponen en la definicion de la relacion de los modelos con los conditions de los
				 */

				if ( !empty($value['options']['conditions']) ) {
					#$this->query['conditions']=am($this->query['conditions'],$value['options']['conditions']);
				}

				/* if(!isset($this->traducibles[$model]) && $this->isTranslate($Model,$Model)){
				  $traducibles[$model]=$Model->Behaviors->Translate->settings[$model];
				  foreach($traducibles[$model] as $_field){
				  foreach ($locale as $_locale) {
				  if(is_array($this->query['fields'])){
				  $this->query['fields'][] =$model.'__'.$_field.'__'.$_locale.'.content';
				  }elseif(is_array($this->query['fields'])){
				  $this->query['fields'].=",".$model.'__'.$_field.'__'.$_locale.'.content';
				  }
				  $this->__addTranslateJoin($model,$_field,$_locale);
				  }
				  }
				  } */
				$innerContains = $this->__getModelsNames($contains[$model]);
				if ( !empty($innerContains) ) {
					$this->__containsToJoins($innerContains);
				}
			}
		}
	}

	/**
	 * Obtiene un arreglo con todo los modelos que se encuentren en el arreglo enviado como parametro
	 * @param $options array
	 * @return $contain array nombres de los modelos que se encuentren en el arreglo
	 */
	function __getModelsNames ( &$options ) {
		$contain = array( );
		$options = (array)$options;
		foreach ( $options as $key => $value ) {
			if ( preg_match("/([A-Z]{1}[A-Za-z0-9\-_]+)/", $key) ) {
				$contain[$key] = $value;
				unset($options[$key]);
			} else if ( is_numeric($key) ) {
				$contain[] = $value;
				unset($options[$key]);
			}
		}
		return $contain;
	}

	/**
	 * 	Cambia los campos que son traducibles en las ordenacion
	 * 	ejemplo: si se envia como parametro en el find Model.nombre y es un campo tradocuble lo cambiara a
	 * 	Model__nombre__{locale}
	 *
	 * 	@param Model $model modelo que actua como este Behavior.
	 * 	@param Model $order arreglo order del los contain o find.
	 * 	@return array $order modificado
	 * 	@access private
	 */
	function __changeOrder ( $order ) {
		if ( is_string($order) ) {
			$_order = $order;
			$order = array( );
			$order[] = $_order;
		}
		if ( !empty($order) ) {
			$currentLocale = Configure::read("I18n.Locale");
			foreach ( $order as $key => $_order ) {
				if ( is_array($_order) && !empty($_order) ) {
					foreach ( $_order as $campo => $direccion ) {
						if ( preg_match('/(.+)\.(.+)/', $campo, $pieces) ) {
							#Aqui se puede poner un or pero lo separe para que sea mas leible xD
							if ( $pieces[1] == $this->Model->alias && isset($this->settings[$this->Model->alias]) && in_array($pieces[2], $this->settings[$this->Model->alias]) ) {
								$order[$key][$pieces[1] . '__' . $pieces[2] . '__' . $currentLocale . '.content'] = $direccion;
								$this->__addTranslateJoin(ClassRegistry::getObject($pieces[1]), $pieces[2], $currentLocale);
								unset($order[$key][$campo]);
							} else {
								$object = ClassRegistry::getObject($pieces[1]);
								if ( is_object($object) && $object->Behaviors->enabled("Translate") && isset($object->Behaviors->Translate->settings[$pieces[1]]) && in_array($pieces[2], $object->Behaviors->Translate->settings[$pieces[1]]) ) {
									$order[$key][$pieces[1] . '__' . $pieces[2] . '__' . $currentLocale . '.content'] = $direccion;
									$this->__addTranslateJoin(ClassRegistry::getObject($pieces[1]), $pieces[2], $currentLocale);
									unset($order[$key][$campo]);
								}
							}
						}
					}
				} elseif ( is_numeric($key) && is_string($_order) ) {
					if ( preg_match('/(.+)\.(.+)\s+(.+)/', $_order, $pieces) ) {
						if ( count($pieces) == 4 ) {
							if ( $pieces[1] == $this->Model->alias && isset($this->settings[$this->Model->alias]) && in_array($pieces[2], $this->settings[$this->Model->alias]) ) {
								unset($order[$key]);
								$order[$key][$pieces[1] . '__' . $pieces[2] . '__' . $currentLocale . '.content'] = $pieces[3];
								$this->__addTranslateJoin(ClassRegistry::getObject($pieces[1]), $pieces[2], $currentLocale);
							} else if ( isset($this->Model->{$pieces[1]}) && $this->Model->{$pieces[1]}->Behaviors->enabled("Translate") && !empty($this->Model->{$pieces[1]}->Behaviors->Translate->settings[$pieces[1]]) && in_array($pieces[2], $this->Model->{$pieces[1]}->Behaviors->Translate->settings[$pieces[1]]) ) {
								unset($order[$key]);
								$order[$key][$pieces[1] . '__' . $pieces[2] . '__' . $currentLocale . '.content'] = $pieces[3];
								$this->__addTranslateJoin(ClassRegistry::getObject($pieces[1]), $pieces[2], $currentLocale);
							}
						} else if ( count($pieces) == 3 ) {
							unset($order[$key]);
							$order[$key][$this->Model->alias . '__' . $pieces[1] . '__' . $currentLocale . '.content'] = $pieces[2];
							$this->__addTranslateJoin(ClassRegistry::getObject($pieces[1]), $pieces[2], $currentLocale);
						}
					}
				} elseif ( is_string($key) ) {
					if ( preg_match('/(.+)\.(.+)/', $key) ) {
						$replacement = $this->__replaceFields($key);
						if ( !empty($replacement) && $replacement != $key ) {
							$order[$replacement] = $_order;
							unset($order[$key]);
						}
					}
				}
			}
		}
		return $order;
	}

	/**
	 * 	Agrega un join para un campo traducible
	 * 	checa si ya se agrego con anterioridad
	 *
	 *
	 * 	@param Model $Model modelo que actua como este Behavior.
	 * 	@param string $_field nombre del campo.
	 * 	@param string $_locale clave del idioma.
	 * 	@return array representa el join
	 * 	@access private
	 */
	function __addTranslateJoin ( &$Model, $_field, $_locale ) {
		$db = & ConnectionManager::getDataSource($Model->useDbConfig);
		$tablePrefix = $this->I18n->tablePrefix;
		$alias = $Model->alias . '__' . $_field . '__' . $_locale;
		$found = false;
		foreach ( $this->query[$this->alias]['joins'] as $join ) {
			if ( is_array($join) && in_array($alias, $join) ) {
				$found = true;
				break;
			}
		}

		if ( !$found ) {

			$this->query[$this->alias]['joins'][] = array(
				'type' => 'LEFT',
				'alias' => $Model->alias . '__' . $_field . '__' . $_locale,
				'table' => $db->name($tablePrefix . $this->I18n->useTable),
				'conditions' => array(
					$Model->alias . '.' . $this->Model->primaryKey => $db->identifier($Model->alias . "__{$_field}__{$_locale}.foreign_key"),
					$Model->alias . '__' . $_field . '__' . $_locale . '.model' => $Model->name,
					$Model->alias . '__' . $_field . '__' . $_locale . '.' . $this->I18n->displayField => $_field,
					$Model->alias . '__' . $_field . '__' . $_locale . '.locale' => $_locale
				)
			);
			//pr($this->query);
		}
	}

	/**
	 * 	Verifica si el behavior es traducible
	 * 	es decir cheka si el behavior esta activo en el modelo y si este tiene campos traducibles
	 *
	 * 	@param Model $model modelo que actua como este Behavior.
	 * 	@param mixed String or Model $_model modelo interno a $model puede ser una cadena con el nombre del modelo o el la instancia del modelo.
	 * 	@return boolean
	 * 	@access public
	 */
	function isTranslate ( &$Model, $_model=null ) {
		if ( is_string($_model) ) {
			if ( isset($Model->{$_model}) && $Model->{$_model}->Behaviors->enabled("Translate") && isset($Model->{$_model}->Behaviors->Translate->settings[$Model->{$_model}->alias]) ) {
				return true;
			}
		} else if ( !empty($_model) && $_model->Behaviors->enabled("Translate") && isset($_model->Behaviors->Translate->settings[$_model->alias]) ) {
			return true;
		} else if ( !empty($this->settings[$Model->alias]) && !is_object($_model) ) {
			return true;
		}
		return false;
	}

	/**
	 * 	afterFind Callback
	 *
	 * 	@param array $results
	 * 	@param boolean $primary
	 * 	@return array Modified results
	 * 	@access public
	 */
	function afterFind ( &$Model, $results, $primary ) {

		//Debug::dump($results);

		if ( empty($results) || $this->__notAllow($Model) ) {
			return $results;
		}
		#debug($results);
		if(!isset($results[0][0])){
		#debug($this->traducibles);
		$locale = $this->locale;
		$this->alias=$Model->alias;
		$currentLocale = Configure::read("I18n.Locale");

		foreach ( $results as $key => $row ) {
			foreach ( $this->traducibles[$Model->alias] as $model => $fields ) {
				foreach ( $fields as $field ) {
					foreach ( $locale as $_locale ) {
						if ( $currentLocale == $_locale && (!isset($results[$key][$model][$field]) && !empty($results[$key][$model . '__' . $field . '__' . $_locale]['content'])) ) {
							$results[$key][$model][$field] = $results[$key][$model . '__' . $field . '__' . $_locale]['content'];
						}

						if ( $this->changeLocale ) {
							$results[$key][$model][$field . "_" . $_locale] = isset($results[$key][$model . '__' . $field . '__' . $_locale]['content']) ? $results[$key][$model . '__' . $field . '__' . $_locale]['content'] : '';
						}
						unset($results[$key][$model . '__' . $field . '__' . $_locale]);
					}

					if ( !isset($results[$key][$model][$field]) ) {
						$results[$key][$model][$field] = '';
					}
				}
			}
		}
		if ( !empty($this->contains[$Model->alias]) ) {
			$contains=$this->contains[$Model->alias];
			foreach ( $results as $key => $result ) {
				$this->result[$this->alias] = $result;
				#pr($result);
				$this->__normalizeResults($result, $contains);
				$results[$key] = $result;
			}
		}

		//Debug::dump($results);
		}
		return $results;
	}

	/**
	 * Forma el array $results en la forma normal del cake sin modificar los cambios en la estructura hechos
	 * por el behavior
	 *
	 * @param $results
	 * @return unknown_type
	 */
	function __normalizeResults ( &$result, $contains ) {
		foreach ( $contains as $model => $options ) {
			if ( !is_numeric($model) ) {
				if ( $options['level'] > 0 ) {
					if ( in_array($options['relation'], array( 'belongsTo', 'hasOne' )) ) {
						$result[$model] = &$this->result[$this->alias][$model];
						$innerContains = $this->__getModelsNames($contains[$model]);
						if ( !empty($innerContains) ) {
							$this->__normalizeResults($result[$model], $innerContains);
						}
					} else if ( $options['relation'] == 'hasMany' ) {
						#pr($result);pr($model);
						$result[$model] = $this->__getHasManyResults($model, $options['options'], $options['options']['foreignKey'], $result['id']);
					} else if ( $options['relation'] == 'hasAndBelongsToMany' ) {
						$result[$model] = $this->__getHABTMResults($model, $options['options'], $options['options']['foreignKey'], $result['id']);
					}
				} else {
					if ( in_array($options['relation'], array( 'belongsTo', 'hasOne' )) ) {
						$innerContains = $this->__getModelsNames($contains[$model]);
						if ( !empty($innerContains) ) {
							$this->__normalizeResults($result[$model], $innerContains);
						}
					} else if ( $options['relation'] == 'hasMany' ) {
						if ( isset($result[$options['padre']]) ) {
							$result[$model] = $this->__getHasManyResults($model, $options['options'], $options['options']['foreignKey'], $result[$options['padre']]['id']);
						}
					} else if ( $options['relation'] == 'hasAndBelongsToMany' ) {
						if ( isset($result[$options['padre']]) ) {
							$result[$model] = $this->__getHABTMResults($model, $options['options'], $options['options']['foreignKey'], $result[$options['padre']]['id']);
						}
					}
				}
			}
		}
	}

	/**
	 * Esta funcion obtiene todos los registros de los modelos relacionados con hasMany para incluirlos despues en los resultados
	 *
	 * @param $model string nombre del modelo por el que se buscaran los registros
	 * @param $query array	opciones del find
	 * @param $id id del registro que tiene estos datos
	 * @return $data resultados del find
	 */
	function __getHasManyResults ( $model, $query, $foreignKey, $id ) {
		# Se quitan todos los elementos del arreglo que no se necesitan
		foreach ( $query as $key => $value ) {
			if ( in_array($key, array( 'className', 'foreignKey', 'dependent', 'exclusive', 'finderQuery', 'counterQuery' )) ) {
				unset($query[$key]);
			}
		}
		if ( empty($query['conditions']) ) {
			$query['conditions'] = array( );
		}
		$query['conditions'] = am($query['conditions'], array( $foreignKey => $id ));

		if ( !array_key_exists('contain', $query) ) {
			$query['contain'] = array( );
		}

		$Model = ClassRegistry::getObject($model);
		$records = $Model->find('all', $query);
		$data = array( );
		foreach ( $records as $key => $record ) {
			$data[$key] = array( );
			foreach ( $record as $alias => $_data ) {
				if ( $alias == $Model->alias ) {
					$data[$key] = am($data[$key], $_data);
				} else {
					$data[$key][$alias] = $_data;
				}
			}
		}
		return $data;
	}

	/**
	 * Esta funcion obtiene todos los registros de los modelos relacionados con hasAndBelongsToMany para incluirlos despues en los resultados
	 *
	 * @param $model string nombre del modelo por el que se buscaran los registros
	 * @param $query array	opciones del find
	 * @param $id id del registro que tiene estos datos
	 * @return $data resultados del find
	 */
	function __getHABTMResults ( $model, $query, $foreignKey, $id ) {
		# Se quitan todos los elementos del arreglo que no se necesitan
		$associationForeignKey = $query['associationForeignKey'];
		$with = $query['with'];
		$joinTable = $query['joinTable'];
		foreach ( $query as $key => $value ) {
			if ( in_array($key, array( 'className', 'foreignKey', 'dependent', 'exclusive', 'finderQuery', 'counterQuery', 'insertQuery', 'deleteQuery', 'unique', 'associationForeignKey', 'with', 'joinTable' )) ) {
				unset($query[$key]);
			}
		}

		if ( !array_key_exists('contain', $query) ) {
			$query['contain'] = array( );
		}

		$Model = ClassRegistry::getObject($model);
		$ds = $Model->getDataSource();
		$ids = $ds->buildStatement(array(
					'fields' => array( $associationForeignKey ),
					'table' => $joinTable,
					'alias' => $with,
					'conditions' => array( $foreignKey => $id ),
					'order'=>null,'limit'=>null,'group'=>null
						), $Model);
		$query['conditions'] = am((array)$query['conditions'], array( $ds->expression($Model->alias . '.' . $Model->primaryKey . ' IN (' . $ids . ')') ));

		$records = $Model->find('all', $query);
		$data = array( );
		foreach ( $records as $key => $record ) {
			$data[$key] = array( );
			foreach ( $record as $alias => $_data ) {
				if ( $alias == $Model->alias ) {
					$data[$key] = am($data[$key], $_data);
				} else {
					$data[$key][$alias] = $_data;
				}
			}
		}
		return $data;
	}

	/**
	 * afterFind Callback
	 * @param Model $Model
	 * @see cake/libs/model/ModelBehavior#afterDelete($model)
	 */
	function afterDelete ( &$Model ) {
		if ( $this->__notAllow($Model) ) {
			return true;
		}
		$this->I18n->deleteAll(array( 'model' => $Model->name, 'foreign_key' => $Model->id ));
	}

	/**
	 * Guarda la referencia del behavior cuando se usa paginate
	 *
	 * @param $Model Model modelo que actua como este behavior
	 * @param $controller Controller referencia del controller desde donde se hizo paginate
	 * @return void
	 */
	function controller ( &$Model, &$controller ) {
		$this->controller = $controller;
	}

	/**
	 * Hace la paginacion automatica de los modelos traducibles
	 *
	 * @param mixed $Model Model to paginate (Referencia del modelo)
	 * @param mixed $scope Conditions to use while paginating
	 * @param array $whitelist List of allowed options for paging
	 * @return array Model query results
	 * @access public
	 */
	/* @var $object Model */
	function paginate ( &$Model, $scope = array( ), $whitelist = array( ) ) {

		$options = array_merge($this->controller->params, $this->controller->params['url'], $this->controller->passedArgs);

		if ( isset($this->controller->paginate[$Model->alias]) ) {
			$defaults = $this->controller->paginate[$Model->alias];
		} else {
			$defaults = $this->controller->paginate;
		}

		if ( isset($options['show']) ) {
			$options['limit'] = $options['show'];
		}

		if ( isset($options['sort']) ) {
			$direction = null;
			if ( isset($options['direction']) ) {
				$direction = strtolower($options['direction']);
			}
			if ( $direction != 'asc' && $direction != 'desc' ) {
				$direction = 'asc';
			}
			$options['order'] = array( $options['sort'] => $direction );
		}

		if ( !empty($options['order']) && is_array($options['order']) ) {
			$alias = $Model->alias;
			$key = $field = key($options['order']);

			if ( strpos($key, '.') !== false ) {
				list($alias, $field) = explode('.', $key);
				$object = &ClassRegistry::getObject($alias);
			}
			$value = $options['order'][$key];
			unset($options['order'][$key]);

			if ( isset($Model->{$alias}) && $Model->{$alias}->hasField($field) ) {
				$options['order'][$alias . '.' . $field] = $value;
			} elseif ( $Model->hasField($field) ) {
				$options['order'][$alias . '.' . $field] = $value;
			} elseif ( is_object($object) && $this->isTranslateField($object, $field) ) {
				$options['order'][$alias . '.' . $field] = $value;
			} elseif ( is_object($object) && $object->hasField($field) ) {
				$options['order'][$alias . '.' . $field] = $value;
			}
		}
		$vars = array( 'fields', 'order', 'limit', 'page', 'recursive' );
		$keys = array_keys($options);
		$count = count($keys);

		for ( $i = 0; $i < $count; $i++ ) {
			if ( !in_array($keys[$i], $vars, true) ) {
				unset($options[$keys[$i]]);
			}
			if ( empty($whitelist) && ($keys[$i] === 'fields' || $keys[$i] === 'recursive') ) {
				unset($options[$keys[$i]]);
			} elseif ( !empty($whitelist) && !in_array($keys[$i], $whitelist) ) {
				unset($options[$keys[$i]]);
			}
		}
		$conditions = $fields = $order = $limit = $page = $recursive = null;

		if ( !isset($defaults['conditions']) ) {
			$defaults['conditions'] = array( );
		}
		extract($options = array_merge(array( 'page' => 1, 'limit' => 20 ), $defaults, $options));

		if ( is_array($scope) && !empty($scope) ) {
			$conditions = array_merge($conditions, $scope);
		} elseif ( is_string($scope) ) {
			$conditions = array( $conditions, $scope );
		}
		if ( $recursive === null ) {
			$recursive = $Model->recursive;
		}
		$type = 'all';

		if ( isset($defaults[0]) ) {
			$type = array_shift($defaults);
		}
		$extra = array_diff_key($defaults, compact(
								'conditions', 'fields', 'order', 'limit', 'page', 'recursive'
				));
		if ( $type !== 'all' ) {
			$extra['type'] = $type;
		}

		if ( method_exists($Model, 'paginateCount') ) {
			$count = $Model->paginateCount($conditions, $recursive, $extra);
		} else {
			$parameters = compact('conditions');
			if ( $recursive != $Model->recursive ) {
				$parameters['recursive'] = $recursive;
			}
			$count = $Model->find('count', array_merge($parameters, $extra));
		}
		$pageCount = intval(ceil($count / $limit));

		if ( $page === 'last' || $page >= $pageCount ) {
			$options['page'] = $page = $pageCount;
		} elseif ( intval($page) < 1 ) {
			$options['page'] = $page = 1;
		}

		$parameters = compact('conditions', 'fields', 'order', 'limit', 'page');
		if ( $recursive != $Model->recursive ) {
			$parameters['recursive'] = $recursive;
		}
		$results = $Model->find($type, array_merge($parameters, $extra));

		$paging = array(
			'page' => $page,
			'current' => count($results),
			'count' => $count,
			'prevPage' => ($page > 1),
			'nextPage' => ($count > ($page * $limit)),
			'pageCount' => $pageCount,
			'defaults' => array_merge(array( 'limit' => 20, 'step' => 1 ), $defaults),
			'options' => $options
		);
		$this->controller->params['paging'][$Model->alias] = $paging;

		if ( !in_array('Paginator', $this->controller->helpers) && !array_key_exists('Paginator', $this->controller->helpers) ) {
			$this->controller->helpers[] = 'Paginator';
		}
		return $results;
	}

	/**
	 * Regresa true si el campo es traducible.
	 *
	 * @param Model $Model model que actua como este behavior
	 * @param mixed $name nombre del campo que desea chekar si es traducible
	 * @return mixed If $name is a string, returns a boolean indicating whether the field exists.
	 *               If $name is an array of field names, returns the first field that exists,
	 *               or false if none exist.
	 * @access public
	 */
	function isTranslateField ( &$Model, $name ) {
		return isset($Model->Behaviors->Translate->settings[$Model->alias]) && in_array($name, $Model->Behaviors->Translate->settings[$Model->alias]);
	}

	/**
	 * 	TranslateBehavior::traducir
	 * 	Se le indica al behavior que idiomas traera en las consulas
	 *
	 * 	@param Model $Model modelo que actua como este Behavior.
	 * 	@param mixed String or array $idiomas indica los idiomas.
	 * 	se puede mandar el idioma directamente por ejemplo: "es",
	 * 	se pueden enviar un "*" como parametro y traducira a todos los idiomas disponibles
	 * 	o se puede enviar un arreglo en donde se indican los idiomas ejemplo array('es','en','fr')
	 *
	 * 	@return void
	 * 	@access public
	 */
	function translate ( &$Model, $idiomas ) {
		if ( is_array($idiomas) ) {
			$this->locale = $idiomas;
		} else if ( is_string($idiomas) ) {
			if ( $idiomas == "*" ) {
				$this->locale = array_keys(Configure::read("I18n.Langs"));
			} else {
				$this->locale = array( $idiomas );
			}
		}
		$this->changeLocale = true;
	}

	/**
	 * 	TranslateBehavior::langs
	 * 	funcion para hacer compatible con la version anterior
	 */
	function langs ( &$Model, $idiomas ) {
		$this->traducir($Model, $idiomas);
	}

}

?>