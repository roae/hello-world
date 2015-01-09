<?php
class I18nHelper extends FormHelper {
	/**
	 * Arreglo de helpers q ayudan en el funcionamiento de este helper
	 * @var array
	 */
	var $helpers=array('Html','Ajax');

	/**
	 *	Referencia al component Interpreter
	 *
	 *	@var InterpreterComponent
	 *	@access public
	 */
	var $Interpreter = null;
	/**
	 * Guara las clavez de edicion que no son alcanzables de ninguna manera en la paginá
	 * @var array
	 */
	var $__missingKeys=array();

	/**
	 *	Constructor.
	 *
	 *	@param TraductorComponent Es una referencia al component que se encarga de interpretar la salida.
	 *	@return void
	 *	@access public
	 */
	function __construct(&$component){
		$this->Interpreter = $component;
	}

	/**
	 * Agrega la clave y la descripcion al arreglo missingKey para despues ser agregadas al tab missing de la barra de edición.
	 * @param string $key clave de edición valida
	 * @param string $desc descripción de la clave
	 */
	function addMissing($key,$desc,$tab="extras",$js=false){
		if(is_array($desc)){
			$options=am(array('tab'=>'extras','js'=>false),$desc);
			$missings=Configure::read("I18n.Interpreter.Missing");
			$missings[$options['tab']][$key]=$options['desc'];
			Configure::write("I18n.Interpreter.Missing",$missings);
			if($options['js']){
				$missings=Cache::read("I18n.Interpreter.MissingJs");
				$missings[$options['tab']][$key]=$options['desc'];
				Cache::write("I18n.Interpreter.MissingJs",$missings);
			}
			return;
		}
		$missings=Configure::read("I18n.Interpreter.Missing");
		$missings[$tab][$key]=$desc;
		Configure::write("I18n.Interpreter.Missing",$missings);

		if($js){
			$missings=Cache::read("I18n.Interpreter.MissingJs");
			$missings[$tab][$key]=$desc;
			Cache::write("I18n.Interpreter.MissingJs",$missings);
		}
	}

	/**
	 * Oculta los tabs de la barra de edicion especificados
	 * @param mixed $tabs lista de tabs a ocultar
	 */
	function addHiddeTabs($tabs=null){
		$hiddenTabs=Configure::read("I18n.Interpreter.hiddenTabs");
		if(is_string($tabs)){
			$hiddenTabs=am($hiddenTabs,array($tabs));
		}else{
			$hiddenTabs=am($hiddenTabs,$tabs);
		}
		Configure::write("I18n.Interpreter.hiddenTabs",$hiddenTabs);
	}

	/**
	 * Se ejecuta despues del view
	 * @return void
	 * @access public
	 */
	function afterRender() {
		/*if(Configure::read("I18n.L10n.allow")){
			$this->Html->script('/i18n/js/jquery.flydom',false);
			$this->Html->script('/i18n/js/l10nbar',false);
			$this->Html->css("/i18n/css/l10ntools",'stylesheet',array('inline'=>false));
		}
		$this->Html->script('/js/l10n',false);
		$this->Html->script('/i18n/js/interpreter',false);

		$this->Html->scriptBlock("WEB_ROOT='".$this->webroot."';",array('inline'=>false));*/
	}

	/**
	 * Abre un buffer para interpretar por separado este contenido en los casos especiales
	 * en que las peticiones no ejecutan el InterpreterComponent
	 * @access public
	 */
	function start(){
		if($this->params['isAjax']){
			ob_start();
		}
	}
	/**
	 * Cierra el buffer e interpreta el contenido
	 * @access public
	 */

	function end($return=false){
		if($this->params['isAjax']){
			if(!$this->Interpreter){
				echo $this->requestAction(array(
					'plugin'=>'i18n',
					'controller'=>'l10n',
					'action'=>'interpret',
				));
			}else{
				if(!$return){
					echo Configure::read('I18n.Interpreter.active') ? $this->Interpreter->process(ob_get_clean()) : ob_get_clean();
				}else{
					return Configure::read('I18n.Interpreter.active') ? $this->Interpreter->process(ob_get_clean()) : ob_get_clean();
				}
			}
		}

	}

	/**
	 * Interpreta las clavez de edicion en una cadena
	 * @param sring $val cadena cn clavez
	 * @param string $locale idioma en que regresara la salida, si no se especifica un idioma se usa el idioma actual del sitio
	 * @return string cadea con clavez interpretadas en el idioma especificado
	 */

	function process($val,$locale=null){
		return $this->Interpreter->process($val,$locale);
	}

	/**
	 * Pone un input en los idiomas que se indiquen si no se indica un idioma pone uno de cada idioma del sitio
	 * @param mixed $fieldName string que contiene el nombre del campo o arreglo de campos que desea mostrar cn una sola llamada de la función
	 * @param array $options opciones de configuración del input
	 */
	function input($fieldName,$options=array()){
		$this->setEntity($fieldName);
		$fields=(array)Configure::read('I18n.Fields.'.$this->model());
		$inline=true;
		$langs=array("");
		if(!empty($options['langs'])){
			$langs=(array)$options['langs'];
			if($options['langs']===true || $options['langs']=="*"){
				$langs=array_keys(Configure::read("I18n.Langs"));
			}
		}else{
			$langs=array_keys(Configure::read("I18n.Langs"));
		}

		if(!empty($fields)){
			if(in_array($this->field(),$fields)){
				if(empty($options['langs'])){
					$langs=array_keys(Configure::read('I18n.Langs'));
				}
			}else{
				$langs=array("");
			}
		}else{
			$langs=array("");
		}
		if(isset($options['tab'])){
			$inline=false;
		}
		$inputs=array();
		unset($options['langs'],$options['inline']);
		$field=$fieldName;

		foreach($langs as $lang){
			if(!empty($lang)){
				$field=$fieldName."_".$lang;
			}
			$inputs[$lang][]=parent::input($field,$options);
		}

		if($inline){
			$buffer="";
			foreach($inputs as $lang){
				foreach($lang as $input){
					$buffer.=$input;
				}
			}
			return $buffer;
		}else{
			foreach($inputs as $lang=>$value){
				$this->inputs[$lang][]=$value;
			}
		}
	}

	/**
 * Returns a formatted LABEL element for HTML FORMs. Will automatically generate
 * a for attribute if one is not provided.
 *
 * @param string $fieldName This should be "Modelname.fieldname"
 * @param string $text Text that will appear in the label field.
 * @param mixed $options An array of HTML attributes, or a string, to be used as a class name.
 * @return string The formatted LABEL element
 * @link http://book.cakephp.org/view/1427/label
 */
	function label($fieldName = null, $text = null, $options = array()) {
		if (empty($fieldName)) {
			$view = ClassRegistry::getObject('view');
			$fieldName = implode('.', $view->entity());
		}else{
			$this->setEntity($fieldName);
			$fieldName=$this->model().".".$this->field();
		}
		if ($text === null) {
			$text = $fieldName;
			if (substr($text, -3) == '_id') {
				$text = substr($text, 0, strlen($text) - 3);
			}
			$text = "[:".Inflector::slug($text)."_input:]";
		}

		if (is_string($options)) {
			$options = array('class' => $options);
		}

		if (isset($options['for'])) {
			$labelFor = $options['for'];
			unset($options['for']);
		} else {
			$labelFor = $this->domId($fieldName);
		}

		return sprintf(
			$this->Html->tags['label'],
			$labelFor,
			$this->_parseAttributes($options), $text
		);
	}


	/**
	 * Estos aun no funcionan
	 */
	function div($id){
		echo $this->Ajax->div($id);
		$this->start();
	}

	function divEnd($id){
		echo $this->end().$this->Ajax->divEnd($id);
	}

}

?>