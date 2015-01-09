<?php

/**
 *	En esta clase se agregar?n propiedades y metodos que serán utilizados por
 *	los models para administrar archivos adjuntos automagicamente.
 *
 *	@package		cms.plugins.media
 *	@subpackage		cms.plugins.media.models.behaviors
 */
	class UploaderBehavior extends ModelBehavior{
		/**
		 *	Nombre de este behavior.
		 *
		 *	@var string
		 *	@access public
		 */
		var $name = 'Uploader';

		/**
		 *	Parámetros de configuración por omisión.
		 *
		 *	@var string
		 *	@access public
		 */
		var $__default = array(
			'allowed' => null,
			'limit'	=> null,
			'required' => null,
			'max_file_size' => null,
			'max_upload_size' => null,
			'override' => 'check',
			'resize' => true,
			'width' => 1024,
			'height' => 768,
			'copies' => array(),
			'min_dimension' => false,
			'max_dimension' => false,
			'flags' => array()
		);

		/**
		 *	Almacena los parámetros de configuración, además dependiendo de
		 *	estos se modifican las relaciones y las reglas de validación del
		 * 	model.
		 *
		 *	@param Model &$model Model que actua como este Behavior.
		 * 	@param array $config Opciones de configuración.
		 *	@return void
		 *	@access public
		 */
		function setup(&$model,$config){
			if(empty($config)){
				$config['Picture'] = array('limit' => 5,'allowed' => array('image/gif','image/jpeg','image/png'));
			}
			$hasOne = array();
			$hasMany = array();
			$has = compact('hasOne','hasMany');

			//$config[$model->alias]=normalizeAllowedConfig($config);

			foreach($config as $alias => $settings){
				$settings=normalizeAllowedConfig($settings);
				if(is_numeric($alias)){
					$alias = $settings;
					$settings = array();
				}
				$settings = am($this->__default,$settings);
				$flags = array();
				if(!empty($settings['flags'])){
					$settings['flags'] = $this->__flags($model,$settings['flags']);
				}
				$this->settings[$model->name][$alias] = $settings;
				if(!empty($settings['copies'])){
					foreach($settings['copies'] as $name => $copy){
						$settings['copies'][$name] = am(array('width' => null,'height' => null),$copy);
					}
				}
				#pr('Medium.' . $model->name . '.' . $alias);
				#pr($settings);
				Configure::write('Medium.' . $model->name . '.' . $alias,$settings);
				$has[$settings['limit'] == 1 ? 'hasOne' : 'hasMany'][$alias] = array(
					'className' => 'Media.Medium',
					'conditions' => array($alias . '.model' => $model->name,$alias . '.alias' => $alias),
					'foreignKey' => 'foreign_key',
					'limit' => $settings['limit'],
					'order' => $settings['limit'] == 1 ? null : array($alias . '.order' => 'ASC'),
					'dependent' => true
				);
				if($settings['required']){
					$model->validate[$alias]['required'] = array('rule' => array('atLeastFiles',$alias,$settings['required']),'required' => true,'alowEmpty' => false,'message' => '[:error-required-files:]');
				}
				if($settings['limit']){
					$model->validate[$alias]['limit'] = array('rule' => array('atMostFiles',$alias,$settings['limit']),'message' => '[:error-limit-files-allowed:]');
				}
				$this->settings[$model->name][$alias] = $settings;
			}
			$model->bindModel($has,false);
			foreach($config as $alias => $settings){
				$model->{$alias}->model = $model->name;
			}
		}

		/**
		 *	Calcula la cantidad de archivos eliminados.
		 *
		 *	@param Model &$model Model que actua como este Behavior.
		 * 	@param string $alias Alias de los datos a contar.
		 *	@return int Cantidad de archivos eliminados.
		 *	@access public
		 */
		function deleted(&$model,$alias){
			$data = Set::extract('/'. $alias .'[id=/^\\-/]',$model->data);
			return count($data);
		}

		/**
		 *	Verifica que se cumpla con el mínimo de archivos requeridos.
		 *
		 *	@param Model &$model Model que actua como este Behavior.
		 * 	@param array $data Datos del "campo" a validar.
		 * 	@param string $alias Alias a validar.
		 *	@return bool Indica si se pasa la verificación.
		 *	@access public
		 */
		function atLeastFiles(&$model,$data,$alias){
			$count = (empty($model->data[$alias]) ? 0 : count($model->data[$alias])) - $this->deleted($model,$alias);
			return $count >= $this->settings[$model->name][$alias]['required'];
		}

		/**
		 *	Verifica que se cumpla con el máximo de archivos requeridos.
		 *
		 *	@param Model &$model Model que actua como este Behavior.
		 * 	@param array $data Datos del "campo" a validar.
		 * 	@param string $alias Alias a validar.
		 *	@return bool Indica si se pasa la verificación.
		 *	@access public
		 */
		function atMostFiles(&$model,$data,$alias){
			unset($model->data[$model->alias][$alias]);
			$count = (empty($model->data[$alias]) ? 0 : count($model->data[$alias])) - $this->deleted($model,$alias);
			return $count <= $this->settings[$model->name][$alias]['limit'];
		}

		/**
		 *	Estandariza las banderas de la configuración.
		 *
		 *	@param Model &$model Model que actua como este Behavior.
		 * 	@param array $flags Banderas.
		 *	@return array Banderas estandarizadas en la forma
		 *  	array('min' = $min,'max' => $max).
		 *	@access private
		 */
		 function __flags(&$model,$flags){
			if(!is_array($flags)){
				$flags = explode(',',$flags);
			}
			foreach($flags as $flag => $type){
				if(is_numeric($flag)){
					$flags[$type] = array('min' => 0,'max' => 0);
					unset($flags[$flag]);
					continue;
				}
				if(is_numeric($type)){
					$flags[$flag] = array('min' => $type,'max' => $type);
					continue;
				}
				if(is_array($type)){
					switch(count($type)){
						case 0:
							$flags[$flag] = array('min' => 0,'max' => 0);
						break;
						case 1:
							list($type) = $type;
							$flags[$flag] = array('min' => $type,'max' => $type);
						break;
						default:
							list($min,$max) = $type;
							if($min > $max){
								$aux = $max;
								$max = $min;
								$min = $aux;
							}
							$flags[$flag] = compact('min','max');
					}
				}
			}
			return $flags;
		}

		/**
		 *	Se ejecuta antes de validar los datos del model.
		 *
		 *	@param Model &$model Model que actua como este Behavior.
		 * 	@param array $options Opciones de validación.
		 *	@return bool Indica si se debe continuar o no con las siguientes
		 *		validaciones.
		 *	@access public
		 */
		function beforeValidate(&$model,$options = array()){
			foreach($this->settings[$model->name] as $alias => $config){
				if($config['required']){
					$model->data[$model->alias][$alias] = 1;
				}
			}
			return true;
		}

		/**
		 *	Se ejecuta despues de guardar los datos.
		 *
		 *	@param Model &$model Model que actua como este Behavior.
		 * 	@param bool $created Indica si se realizó un INSERT o un UPDATE.
		 *	@return void
		 *	@access public
		 */
		function afterSave(&$model,$created){
			foreach($this->settings[$model->name] as $alias => $settings){
				if($model->$alias){
					if(empty($model->data[$alias])){
						continue;
					}
					$order = 0;
					foreach($model->data[$alias] as $key => $attachment){
						if(!is_array($attachment)){
							continue;
						}
						if(empty($attachment['id'])){
							$model->{$alias}->create();
						}elseif(!$created && $attachment['id'] > 0){
							$model->{$alias}->id = $attachment['id'];
						}elseif(!$created){
							$model->{$alias}->delete(abs($attachment['id']));
							continue;
						}
						$attachment['order'] = ++$order;
						$attachment['model'] = $model->name;
						$attachment['alias'] = $alias;
						$attachment['foreign_key'] = $model->id;
						$success = $model->{$alias}->save(array($alias => $attachment),false);
						$model->data[$alias][$key] = $success[$alias];
					}
				}
			}
		}

		/**
		 *	Se ejecuta despues de eliminar un registro.
		 *
		 *	@param Model &$model Model que actua como este Behavior.
		 *	@return void
		 *	@access public
		 */
		function afterDelete(&$model){
			$folder = &new Folder(WWW_ROOT . 'files' . DS . $model->name . DS . $model->id);
			@$folder->delete();
		}
	}
?>