<?php
/**
 * Definicion de InterpreterComponent
 *
 * Se encarga de remplazar las clavez de edición
 *
 * @author Efrain Rochin Aramburo
 * 30 enero 2011
 */
class InterpreterComponent extends Object{
	/**
	 * Definicion de los components que ayudan en el funcionamiento de este component.
	 * @var array
	 * @access public
	 */
	var $components=array('Session','Acl','Auth');

	/**
	 * Etiqueta que se usara para la edición.
	 * @var string
	 * @access public
	 */
	var $tag="skey";

	/**
	 * Arreglo de opciones
	 * @var array
	 */
	var $settings=array();

	/**
	 * Guarda la referencia del controller que instanció este component
	 * @var Controller
	 */
	var $controller;

	/**
	 * Modelo que se encarga de guardar y obtener el contenido de las claves
	 * @var Model
	 */
	var $I18n;

	/**
	 * Modelo grupo, para poner restricciones en las claves de edicion
	 * @var Model
	 */
	var $Group;

	/**
	 * Nombre de la sesion que se usa chekar si puedo o no editar
	 * @var string
	 */
	var $sessionName;

	/**
	 * Contenido de la respuesta
	 * @var string
	 */
	var $output;

	/**
	 * Arreglo de scripts que cargara en caso de que se activo la edicion
	 * @var array
	 */
	var $__scripts=array(
		'<script type="text/javascript">WEB_ROOT="/"</script>',
		'<script type="text/javascript" src="/js/l10n.js"></script>',
		'<script type="text/javascript" src="/i18n/js/interpreter.js"></script>',
		/*
		'<script type="text/javascript" src="/i18n/js/jquery.l10n.js"></script>',
		'<script type="text/javascript" src="/i18n/js/jquery.ui.js"></script>',
		'<script type="text/javascript" src="/i18n/js/l10ntools.js"></script>',
		'<script type="text/javascript" src="/i18n/js/jquery.flydom.js"></script>',
		'<script type="text/javascript" src="/i18n/js/jquery.l10n.js"></script>',
		//'<script type="text/javascript" src="/i18n/js/jquery.hotkeys-0.7.9.min.js"></script>',
		'<script type="text/javascript">window.onbeforeunload=function(){ return __("onunload_text",true);}</script>',*/
	);
	/**
	 * Arreglo de archivos css que se cargaran en la edicion
	 * @var array
	 */
	var $__css=array(
		'<link href="/i18n/css/jquery-ui/mactheme/mactheme.css" type="text/css" rel="stylesheet"/>',
		'<link href="/i18n/css/l10ntools.css" type="text/css" rel="stylesheet"/>'
	);
	/**
	 * Guarda las traducciones que estan en los meta datos de la respuesta
	 * @var array
	 * @example array(array('type'=>'description','key'=>'[:meta_description]:'))
	 */
	var $__metas=array();

	/**
	 * Guarda las clavez de edicion que estan en elementos que no se pueden editar como buttons selects options inputs
	 * atributos de tags como title value rel etc
	 * @var array
	 * @example la estructura del arreglo es como la siguiente
	 * array(
	 *		select=>array(
	 *				0=>array(
	 *					description=>descricion del elemento,
	 *					options=>array(
	 *						0=>[:key:]
	 *						1=>[:key:]
	 *					)
	 *				)
	 *		)
	 *		button=>array(
	 *					0=>array(
	 *						description=>descricion del elemento,
	 *						key=>[:key:]
	 *					)
	 *				)
	 *		text=>array(
	 *					0=>array(
	 *						description=>descricion del elemento,
	 *						key=>[:key:]
	 *					)
	 *				)
	 *		textarea=>array(
	 *					0=>array(
	 *						description=>descricion del elemento,
	 *						key=>[:key:]
	 *					)
	 *				)
	 * )
	 *
	 */
	var $__uneditablesKeys=array();

	/**
	 * Guarda las clavez y la descripcion que se encuentran en el archivo js l10n
	 * @var array
	 */
	var $__jsKeys=array();

	/**
	 * Guarda las clavez que no se pueden editar desde el contenido de la pagina para ponerlas en un tab en la barra de edicion
	 * esta atributo se iguala al del controller
	 * @var array
	 */
	var $missingKeys=array();

	/**
	 * variable auxiliar
	 * @var mixed
	 */
	var $aux;

	/**
	 * Indica si tiene o no permiso de editar el usuario logeado
	 * @var Boolean
	 */
	var $allow;

	/*
	 * Guarda el contenido de todas las clavez de traduccion encontradas
	 * @var array
	 */
	var $contentKeys;

	/*
	 * Idioma elegido
	 * @var String
	 */
	var $locale;

	/*
	 * Claves enconatradas en la ejecucion
	 * @var array
	 */
	var $keys=array();


	/**
	 * Es llamado antes del metodo beforeFilter del controller
	 * @param Controller $controller Referencia
	 * @param array $settings arreglo de opcione
	 */
	function initialize( &$controller, $settings=array() ) {
		$this->sessionName=Configure::read('I18n.sessionName');
		if(isset($controller->missingKeys)){
			$this->missingKeys=$controller->missingKeys;
		}
		if(!in_array('I18n.I18n',$controller->helpers) && !isset($controller->helpers['I18n.I18n'])){
			$controller->helpers['I18n.I18n'] = &$this;
		}
	}

	/**
	 * Se ejecuta despues del beforeFilter del controller pero antes de la acción
	 * @param Controller $controller Referencia
	 */
	function startup( &$controller ) {
		# se carga el modelo que se encarga de obtener el contenido de las claves
		$this->__loadModel();
		# se ejecuta el metodo controller del behavior Translate en todos los modelos cargados
		foreach ($controller->uses as $model){
			$parts=pluginSplit($model);
			$model=$parts[1];
			if(isset($controller->{$model}->Behaviors)){
				if($controller->{$model}->Behaviors->enabled("Translate")){
					$controller->{$model}->controller($controller);
				}
			}
		}
		$auth=$this->Session->read($this->Auth->sessionKey);
		if(Set::check($auth,"I18n.L10n.allow")){
			$this->allow=Set::classicExtract($auth,"I18n.L10n.allow");
		}else{
			$this->allow=$this->Acl->check($this->Auth->user(),'i18n/l10n/admin_edit') && !isset($_GET['L10n']);
			$this->Session->write($this->Auth->sessionKey.".I18n.L10n.allow",$this->allow);
		}

		Configure::write("I18n.L10n.allow",$this->allow);
		Configure::write("I18n.L10n.active",$this->Session->check($this->sessionName) && $this->allow);
	}

	/**
	 * Carga el modelo Key que se utiliza para obtener el contenido de las clavez
	 * @return void
	 */
	function __loadModel(){
		if(empty($this->I18n)){
			$this->I18n = ClassRegistry::init('I18n.Key');
		}
		if(empty($this->Group)){
			$this->Group = ClassRegistry::init('Group');
		}
	}

	/**
	 * Se ejecuta despues de la accion del controller pero antes de que se renderize la pagina
	 * @param Controller $controller Referencia
	 */
	function beforeRender( &$controller ) {

	}

	/**
	 * Callback llamado despues de la accion y antes de que la salida sea enviada al cliente
	 * En este component es ulizado para procesar la salida que se enviara al cliente
	 * y cambiar todas las claves de edicion por el contenido que le corresponde a cada una de ellas
	 * @param Controller $controller Referencia
	 */
	function shutdown( &$controller ) {
		if(empty($controller->params['requested'])){
			if(Configure::read('I18n.Interpreter.active')){
				Configure::write("aux",1);
				$controller->output=$this->process($controller->output,null,true);
			}
			//if($this->allow){
			# se cargan los archivos necesarios para crear la barra de edición
			$langs=Configure::read("I18n.Langs");
			foreach($langs as $locale=>$lang){
				$items[]="$locale:'$lang'";
			}
			$json="I18nLocale={".implode(',',$items)."};";
			$controller->output=preg_replace('/(<!--I18nScripts-->)/i','<script type="text/javascript">'.$json.' </script>'.implode(' ',$this->__css).implode(' ',$this->__scripts).' $1',$controller->output);
			//}
		}
	}


	/**
	 * Este metodo es llamado cuando se llama el metodo redirect del controller justo antes de cualquier otra accion
	 * si regresa false no continua con la redireccion el controller debe regresar la $url a la que se redirigira si
	 * @param Controller $controller Referencia
	 * @param mixed $url
	 * @param Tnteger $status
	 * @param Boolean $exit
	 */
	function beforeRedirect( &$controller, $url, $status=null, $exit=true ) {

	}

	/**
	 * Cambia la salida remplazando las clavez de edicion por el contenido guardado en la base de datos
	 * @param string $output referencia del contenido de la respuesta
	 * @return void
	 */

	function process($output,$locale=null,$layout=false){
		if(empty($locale)){
			$locale=Configure::read("I18n.Locale");
		}
		$this->locale=$locale;
		if(Configure::read("I18n.L10n.active")){ #si es posible editar
			Configure::write("I18n.L10n.active",true);
			$this->__processMetas($output);
			//$this->__processJsKeys($output);
			$this->__generateTabMissingKeys($output);
			$this->__hideEventsAndScripts($output);
			$this->__processInvisiblesKeys($output);
			//$this->__hideVisibleCode($output);
		}
		$output=preg_replace('/desc=(\'|").+?(\'|")/','',$output);# se quita el atributo desc no valido que tiene la descripcion de los elementos html no editables

		preg_match_all('/\[:([0-9a-zA-Z_\-.]+):\]/',$output,$keys);#se obtienen todas las claves de edicion que van en la salida

		$keys=$_keys=array_unique($keys[1]);#se quitan las claves repetidas
		$done=false;

		if(!$layout){ # si ya se extrajeron las clavez se quitan las repetidas
			$keys=array_diff($keys,$this->keys);
			$this->keys=am($this->keys,$keys);
			$done=true;
		}else{
			$this->keys=$keys;

		}

		if(!empty($keys)){

			$this->__loadModel();

			$contentKeys=$this->I18n->find("all",array(
				'fields'=>array($this->I18n->alias.'.id',$this->I18n->alias.'.key',$this->I18n->KeyMeaning->alias.'.content'),
				'joins'=>array(
					array(
						'table'=>$this->I18n->KeyMeaning->tablePrefix.$this->I18n->KeyMeaning->table,
						'alias'=>$this->I18n->KeyMeaning->alias,
						'conditions'=> 'Key.id = '.$this->I18n->KeyMeaning->alias.'.key_id and '.$this->I18n->KeyMeaning->alias.'.lang_id="'.$this->locale.'"'
					)
				),
				'conditions'=>array('BINARY '.$this->I18n->alias.'.key IN ("'.implode('", "',$keys).'")'),
				'contain'=>array()
			));
			#$dbo = $this->I18n->getDatasource();
			#pr(current(end($dbo->_queriesLog)));

			foreach($contentKeys as $record){
				$this->contentKeys[$record['Key']['key']]=array(
					'id'=>$record['Key']['id'],
					'content'=>$record['KeyMeaning']['content']
				);
			}
			#pr($this->contentKeys);
		}

		# se remplazan las clavez de edicion que estan en elementos que muestran el codigo html en el contenido encontrado en query
		$output=preg_replace_callback('/<(title|textarea)[^>]*>(.*?)(<\/\1>|\/1>)|<input[^>]*\/>|title=(\'|")[^(\'|")]*?\[:[0-9a-zA-Z_\-]+:\].*?(\'|")|value=(\'|")[^(\'|")]*?\[:[0-9a-zA-Z_\-]+:\].*?(\'|")/',array($this,'__replaceVisibleCodeKeys'),$output);

		# Se quitan las clavez de edicion no interpretadas en el atributo href de los links
		$output=preg_replace_callback('/href=(\'|").*?(\'|")/',array($this,'__replaceVisibleCodeKeys'),$output);

		$prefixes=$this->__getGroupPrefix();
		# \[:((Registered|Administrator|System)\.[a-zA-Z-0-9_\-]+|[a-zA-Z-0-9_\-]+):\]
		$this->pattern='('.implode("|",$prefixes).')\.[a-zA-Z0-9_\-]+|[a-zA-Z0-9_\-]+';

		$foundedKeys=$this->__replaceKeys($output,$keys);# se remplazan las clavez de edicion por el contenido encontrado en query


		if(!empty($foundedKeys)){
			$keys=array_diff($keys,$foundedKeys);
		}
		#pr($this->pattern);

		if(Configure::read("I18n.L10n.active") && $layout){ #si es posible editar se ponen las clavez dentro del tag de edición
			$replace='<'.$this->tag.' title =\'$1\' rel=\'$1\'> [$1] </'.$this->tag.'>';
			$output=preg_replace('/\[:('.$this->pattern.'):\]/',$replace,$output);

			$this->__scripts=am($this->__scripts,array(
				'<script type="text/javascript" src="/i18n/js/jquery.js"></script>',
				'<script type="text/javascript" src="/i18n/js/jquery.ui.js"></script>',
				'<script type="text/javascript" src="/i18n/js/jquery.flydom.js"></script>',
				'<script type="text/javascript" src="/i18n/js/l10ntools.js"></script>',
				'<script type="text/javascript" src="/i18n/js/jquery.l10n.js"></script>',
				'<script type="text/javascript">window.onbeforeunload=function(){ return __("onunload_text",true);}</script>',
				'<script type="text/javascript">$(function(){$("skey").L10n();});</script>',
			));
		}else if($layout){
			$this->__css=array();
			if($this->allow){
				$this->__scripts=am($this->__scripts,array(
					'<script type="text/javascript" src="/i18n/js/jquery.js"></script>',
					'<script type="text/javascript" src="/i18n/js/jquery.flydom.js"></script>',
					'<script type="text/javascript" src="/i18n/js/l10nbar.js"></script>',
				));
				#pr(h($this->__scripts));
				$this->__css=array(
					'<link href="/i18n/css/l10ntools.css" type="text/css" rel="stylesheet"/>'
				);
			}

			if(Configure::read("debug")<=0 || Configure::read("I18n.humanize") ){
				$output=preg_replace_callback('/\[:([a-zA-Z0-9_\-\.]+):\]/',array($this,'__humanizeKey'),$output);
			}
		}

		$output=preg_replace('/\[\-:([A-Za-z0-9\-_\.]+):\-\]/', '[:$1:]', $output);
		$output=preg_replace('/interpreter=(\'|").+?(\'|")/','',$output);# se quita el atributo interpreter no valido que indica si se traducira el contenido del elemento
		#$this->log($output,"interpreter");
		return $output;
	}

	/**
	 * Obtiene los grupos hijos del grupo al que el usuario loggeado pertenece, para obtener los prefijos a los que este usuario tiene permiso de edición.
	 * @return Array
	 * @TODO hacer que obtenga los hijos de la tabla de grupo como arbol
	 */
	function __getGroupPrefix(){
		//$groups=array();
		$parent=array(Configure::read("loggedUser.User.group_id"));
		$group=$this->Group->findById(Configure::read("loggedUser.User.group_id"),array('name'));

		$groups=Set::extract("/Group/name",$group);
		//pr($groups);
		while(!empty($parent)){
			$recordset=$this->Group->find("all",array('conditions'=>array('Group.parent_id'=>$parent),'fields'=>array('Group.id',"Group.name")));
			$childrenName=Set::extract("/Group/name",$recordset);
			if(!empty($childrenName)){
				$groups=am($groups,$childrenName);
			}
			$parent=Set::extract("/Group/id",$recordset);
		}#while(!empty($parent));
		#pr($groups);
		return $groups;
	}

	/**
	 * Transforma las clavez de edicion a una version mas legible para los humanos
	 * @param Array $matches
	 * @return String
	 */
	function __humanizeKey( $matches ) {
		return sprintf('%s',r('-',' ',Inflector::humanize($matches[1],'-')));
	}

	/**
	 * Remplaza las clavez encontrada en la etiqueta title, textarea, atributos title, href y value por el contenido de la clavez o en el caso de que no se le aya puesto contenido a la clavez aun se elimina
	 * @param array $matches
	 * @return string
	 */

	function __replaceVisibleCodeKeys($matches){
		preg_match_all('/\[:([0-9a-zA-Z_\-]+):\]/',$matches[0],$keys); #se obtienen todas las claves de edicion que van en la salida
		$keys=array_unique($keys[1]);#se quitan las claves repetidas
		foreach((array)$keys as $key){
			if(isset($this->contentKeys[$key])){
				if(!preg_match('/interpreter=(\'|")no(\'|")/',$matches[0])){
					$matches[0]=preg_replace('/\[:'.$key.':\]/',$this->contentKeys[$key]['content'],$matches[0]);
				}else{
					$matches[0]=preg_replace('/\[:([0-9a-zA-Z_\-]+):\]/', '[-:$1:-]', $matches[0]);
				}
			}else{
				$matches[0]=preg_replace('/\[:('.$key.'):\]/','[$1]',$matches[0]);
			}

		}
		return $matches[0];
	}

	/**
	 * busca si la clavez esta en el arreglo que contiene el contenido de las clavez ($this->contentKeys)
	 * si la encuentra regresa la posicion en caso contrario regresa false
	 * @param <type> $k
	 * @return <type> mixed
	 */
	function __keySearch($k){
		foreach($this->contentKeys as $index=>$contentKey){
			if($contentKey['Key']['key'] == $k){
				return $index;
			}
		}
		return false;
	}

	/**
	 * Cambia las claves de la respuesta por el contenido de las mismas, obtenido en el find
	 * @return array arreglo de claves que se encontraron
	 */

	function __replaceKeys( &$output , $keys ) {
		$foundedKeys=array();
		foreach($keys as $key){
			$foundedKeys[]=$key;
			if(isset($this->contentKeys[$key])){
				if($this->Session->check($this->sessionName)){
					#pr('/'.$this->pattern.'/');
					#pr($key);

					$content=preg_replace('/\[:([0-9a-zA-Z_\-]+):\]/','[-:$1:-]',$this->contentKeys[$key]['content']);#se transforman las claves de edicion del contenido obtenido de la BD
					#pr(preg_match_all('/'.$this->pattern.'/',$key,$_matches));
					if(preg_match_all('/'.$this->pattern.'/',$key,$_matches) === 1){
						#pr(h($content));
						$content="<".$this->tag." rel='".$key."' title='".$this->contentKeys[$key]['id']." - [".$key."]'>";
						$content.=preg_replace('/\$([0-9])/','\\\$$1',$this->contentKeys[$key]['content']);
						$content.="</".$this->tag.">";
					}
				}else{
					$content=$this->contentKeys[$key]['content'];

					if(preg_match('/\[:([0-9a-zA-Z_\-]+):\]/',$content)){
						$content=$this->process($content,$this->locale);
					}else{
						Configure::write("aux",1);
					}
					$content=preg_replace('/\$([0-9])/','\\\$$1',$this->__setStrongsTags($content)); # se limpia el contenido de las clavez y se remplazan los tags b por strong

				}

				$content=preg_replace('/\[:([0-9a-zA-Z_\-]+):\]/','[-:$1:-]',$content);#se transforman las claves de edicion del contenido obtenido de la BD

				$output=preg_replace('/\[:'.$key.':\]/',$content,$output);
			}
		}
		return $foundedKeys;
	}
	/**
	 * Remplaza las etiquetas B por STRONG
	 * @param string $content
	 * @return string $content modificado
	 */

	function __setStrongsTags($content){
		$content=preg_replace('/<b>/', '<strong>', $content);
		$content=preg_replace('/<\/b>/', '</strong>', $content);
		return $content;
	}

	/**
	 * Agrega una clave a la pestaña missing de la barra de edición
	 * @param String $key clave
	 * @param String $desc descripción de la clave
	 */
	function addMissing($key,$desc=null,$tab='extras'){
		if(is_string($key)){
			$this->missingKeys[$tab][$key]=$desc;
		}else{
			$this->missingKeys=am($this->missingKeys,$key);
		}
	}

	/**
	 * busca las clavez de edicion que estan en elementos html que no se pueden editar como botones inputs selects y textarea
	 * y genera la salida necesaria para que estos se puedan editar
	 * @param string $output referencia a la vairable de la salida
	 */

	function __processInvisiblesKeys(&$output){
		# se obtienen las clavez que estan dentro de las etiquetas select
		$output=preg_replace_callback('/<(?<tag>select) [^>]*>(.*?)<\/select>/is',array($this,'__getUneditablesElementsKeys'),$output);
		# se obteienen las clavez que estan dentro de las etiquetas buttons
		$output=preg_replace_callback('/<(?<tag>button) ([^>]*)>(?<keys>.*?)<\/button>/is',array($this,'__getUneditablesElementsKeys'),$output);
		# inputs
		$output=preg_replace_callback('/<(?<tag>input) ([^>]*)\/>/is',array($this,'__getUneditablesElementsKeys'),$output);
		# imagenes
		$output=preg_replace_callback('/<(?<tag>img) ([^>]*)\/>/is',array($this,'__getUneditablesElementsKeys'),$output);
		# links
		$output=preg_replace_callback('/<(?<tag>a) ([^>]*)>/is',array($this,'__getUneditablesElementsKeys'),$output);
		# clavez de edicion que estan en textareas
		$output=preg_replace_callback('/<(?<tag>textarea) [^>]*>(?<keys>.*?)<\/textarea>/is',array($this,'__getUneditablesElementsKeys'),$output);
		# pr($this->__uneditablesKeys);
		$out="";
		if(!empty($this->__uneditablesKeys)){
			$this->__uneditablesKeys=Set::diff($this->__uneditablesKeys);
			$out.=$this->__uneditableElementsOutput();
		}
		$output = preg_replace('/(<\/body>)/iU',$out.'$1 ',$output);

	}

	function __getUneditablesElementsKeys($matches){
		preg_match('/desc=(\'|")(?<desc>.+?)(\'|")/is',$matches[0],$desc);
		preg_match_all('/(?<attr>[a-zA-Z]+)=(\'|")(?<value>[^(\'|")]*?\[:[0-9a-zA-Z_\-]+:\].*?)(\'|")/',$matches[0],$attrs);
		preg_match('/type=(\'|")(?<type>.+?)(\'|")/is',$matches[0],$type);
		$this->optionsKeys=array();
		preg_replace_callback('/<option\b[^>]*>(\[:[a-zA-Z0-9_\-]+:\]?)<\/option>/is',array($this,'__getOptionsKey'),$matches[0]);

		if($matches['tag']=='input' && isset($type['type']) && ($type['type']=="text" || $type['type']=='submit')){
			$type=($type['type']=="text") ? 'text':'button';
		}else if($matches['tag']!='input'){
			$type=($matches['tag']=="textarea")? "text" : $matches['tag'];
		}else{
			$type=false;
		}

		if(isset($attrs['attr']) && (!empty($attrs['attr']) || isset($matches['keys'])) && $type){
			$a=array();
			foreach($attrs['attr'] as $i=>$attr){
				$a[$attr]=$attrs['value'][$i];
			}
			if(empty($this->__uneditablesKeys[$type])){
				$this->__uneditablesKeys[$type]=array();
			}
			$a['desc']=isset($desc['desc']) ? $desc['desc'] : '';
			$a['options']=$this->optionsKeys;
			$a['text']=(isset($matches['keys']) && $matches['keys']) ? $matches['keys'] : false;
			array_push($this->__uneditablesKeys[$type],$a);
			// se agrega el parametro data-l10nid para saber que div donde estan las clabes de este elemento
			$matches[0]=preg_replace('/^<([a-zA-z]+) /','<$1 data-l10nid="L10n'.$type.count($this->__uneditablesKeys[$type]).'"',$matches[0]);
		}
		return $matches[0];
	}

	function __getOptionsKey($matches){
		$count=count($this->optionsKeys);
		$this->optionsKeys[$count]['label']=$matches[1];
		preg_match('/value=(\'|")(?<value>\[:[a-zA-Z-0-9_\-]+:\]?)(\'|")/is', $matches[0],$value);
		if(isset($value['value'])){
			$this->optionsKeys[$count]['value']=$value['value'];
		}
		return $matches[0];
	}

	function __uneditableElementsOutput(){
		$out="";
		#pr($this->__uneditablesKeys);
		foreach($this->__uneditablesKeys as $type=>$elements){
			if(!empty($elements)){
				//$out.="<div class='fieldset'><span class='fieldsetTitle fieldsetElementTitle'>[:$type-elements:]</span>";
				foreach($elements as $key=>$element){
					$done=true;
					$desc=(empty($element['desc']))? "[:".$type."-elemenent:]" : $element['desc'];
					$el="<div class='L10nUneditable' id='L10n".$type.($key+1)."' title='".$desc."'>".
						/*"<div class='L10nUneditableTitle'>".
							$desc.
							"<span class='L10nWindowClose'><span>".
						"</div>".
						"<div class='L10nUneditableContent'>".*/
						"<div class='keys'>";
					unset($element['desc']);
					if($type=="select"){
						$el.="<div class='attr_options'>[:select_options:]</div><div class='select_options'>";
						foreach((array)$element['options'] as $option){
							$el.="<div class='attr_name'>[:option_label:]</div>".
								"<div class='attr_value'>".$option['label']."</div>";
							if(isset($option['value'])){
								$el.="<div class='attr_name'>[:option_value:]</div>".
									"<div class='attr_value'>".$option['value']."</div>";
							}
							$done=false;
						}
						$el.="</div>";
						unset($element['options']);
					}
					foreach($element as $k=>$v){
						if($v && ($type!="select"  || ($type=="select" && $k!="value"))){
							$el.="<div class='attr_name'>[:{$k}_attr:]</div>";
							$el.="<div class='attr_value'>{$v}</div>";
							$done=false;
						}
					}
					$el.="</div>".
						"</div>";
					if(!$done){
						$out.=$el;
					}
				}
				//$out.="</div>";
			}
		}
		return $out;
	}

	function __generateTabMissingKeys(&$output){
		$missing=Configure::read("I18n.Interpreter.Missing");
		$missing=am((array)$missing,$this->missingKeys);
		$hiddenTabs=Configure::read("I18n.Interpreter.hiddenTabs");
		//$this->log($missing,'debug');
		$tabKeys="";$li="<ul id='I18nTabNames'>";
		foreach($missing as $tabName=>$tab){
			if(empty($hiddenTabs) || !in_array($tabName,$hiddenTabs)){
				$tabKeys.="<div class='L10nTab' id='L10n$tabName'>";
				if(!empty($tab)){
					//$this->log($tab,'debug');
					foreach($tab as $key=>$desc){
						$key = (preg_match('/\[:.*?:\]/',$key)) ? $key : "[:$key:]";
						$tabKeys.="<div class='fieldset'><span class='fieldsetTitle'>$desc</span><div class='key'>$key</div></div>";
					}
				}
				$tabKeys.="</div>";
				$li.='<li><a href="L10n'.$tabName.'">[:System.'.$tabName.':]</a></li>';
			}
		}
		$li.="</ul>";
		$output = preg_replace('/(<\/body>)/iU',$tabKeys.$li.'$1 ',$output);
	}

	/**
	 * Obtiene las clavez de edicion que estan en el archivo js L10n
	 * @param string $output referencia a la cadena que contiene la salida de la pagina
	 * @return void
	 */
	function __processJsKeys(&$output) {
		$js=file_get_contents(APP.'plugins'.DS.'i18n'.DS.'views'.DS.'js'.DS.'l10n.ctp');
		preg_replace_callback('/"(?<key>\[:[a-zA-Z0-9_\-]+:\])",(.*?)("|\'|})/is',array($this,'__getJsKeys'),$js);
		$out="";
		//$this->log($this->__jsKeys,'debug');
		if(!empty($this->__jsKeys)){
			$out="<div class='L10nTab' id='L10nJs'>";
			foreach($this->__jsKeys as $key=>$desc){
				$out.="<div class='fieldset'><span class='fieldsetTitle'>$desc</span><div class='key'>$key</div></div>";
			}
			$out.="</div>";
		}
		$output = preg_replace('/(<\/body>)/iU',$out.'$1 ',$output);
	}

	function __getJsKeys($matches){
		preg_match('/\/\/desc:(?<desc>.*?)\/\//',$matches[0],$regs);
		$this->__jsKeys[$matches['key']]=isset($regs['desc'])? $regs['desc'] : '';
	}


	/**
	 * Obtiene las claves de edicion que estan en los metadatos de la respuesta
	 * y crea la estructura de elementos html para la edicion
	 * @param &$output string referencia a la salida de la página
	 * @return void
	 */
	function __processMetas(&$output) {
		# etiqueta title
		$output = preg_replace_callback('/<(?<type>title)>(?<key>.*)<\/title>/i',array($this,'__metaReplaceCallback'),$output);
		# meta description y keywords
		$output = preg_replace_callback('/<meta[^>]*\"(?<type>[a-zA-Z]+)\".*(?<key>\[:[0-9a-z_\-]+:\])[^>]*>/iU',array($this,'__metaReplaceCallback'),$output);
		if(!empty($this->__metas)){
			$this->__metas=$this->__arrayUnique($this->__metas);
			if(!empty($this->__metas)){
				//$this->log($this->__metas,'debug');
				$metasOut="<div id='L10nMetas' class='L10nTab'>";
				foreach($this->__metas as $meta){
					$metasOut.="<div class='fieldset'><span class='fieldsetTitle'>[:System.".$meta['type'].":]</span><div class='key'>".$meta['key']."</div></div>";
				}
				$metasOut.="</div>";
				$output = preg_replace('/(<\/body>)/iU',$metasOut.'$1 ',$output);
			}
		}
	}

	/**
	 * callback de preg_replace_callback
	 * Pone la traduccion encontrada en el arreglo de metas
	 * @param array $matches
	 * @return string
	 */
	function __metaReplaceCallback($matches){
		$this->__metas[]=$matches;
		return $matches[0];
	}

	/**
	 * Quita los scripts y los eventos de los elementos html
	 * @access private
	 * @return void
	 */

	function __hideEventsAndScripts(&$output) {
		#pr(h($output));
		#$output=preg_replace('/(<[^>]*)\[:([0-9a-zA-Z_\-]+):\]/iUs','$1',$output);
		#$output=preg_replace('/(<[^>]*)\[:([0-9a-zA-Z_\-]+):\]/iUs','$1',$output);
		$output=preg_replace('/(<a [^>]+)on[a-z] *=(\'|").+\2/iUs','$1 ',$output);
		#$dom=new DOMDocument();
		preg_match('/\/\/<!.*(WEB_ROOT.*;).*\]/iUs',$output, $matches);
		if(!empty($matches)){
			$this->__scripts=am(array('<script type="text/javascript">'.$matches[0].'</script>'),$this->__scripts,array('<script type="text/javascript">$(function(){$("'.$this->tag.'").L10n();})</script>'));
		}
		$output=preg_replace('/(<input [^>]+)on[a-z]*=(\'|").+\2/iUs','$1 ',$output); # Quita eventos
		$output=preg_replace('/(<button [^>]+)on[a-z]*=(\'|").+\2/iUs','$1 ',$output);# Quita eventos

		$output=preg_replace('/<script.*<\/script>/iUs','',$output); # quita los scripts

		$output=preg_replace('/<a ([^>]*href=(\'|"))/iUs','<a onclick=$2return false;$2 $1 ',$output);# pone return false en el evento onclick de las a
		$output=preg_replace('/<button /iUs','<button onclick="return false;" ',$output);# pone return false en el evento onclick de los buttons
		$output=preg_replace('/<input /iUs','<input onclick="return false;"  ',$output);# pone return false en el evento onclick de los inputs
		$output=preg_replace('/<select/iUs','<select onclick="return false;" ',$output);# pone return false en el evento onclick de los selects
		$output=preg_replace('/for=(\'|").+?\1/m','',$output);# quita el atributo for de los label
		#pr(h($output));
	}

	function __arrayUnique( $array ) {
		$result = array_map("unserialize", array_unique(array_map("serialize", $array)));
		foreach ($result as $key => $value){
			if ( is_array($value) ){
				$result[$key] = $this->__arrayUnique($value);
			}
		}
		return $result;
	}

}
?>