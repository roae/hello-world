<?php
/**
 *	En esta clase se agregarán propiedades y metodos que se utilizan en los
 *	views para facilitar el manejo de archivos adjuntos.
 *
 *	@package		cms.plugins.xata
 *	@subpackage		cms.plugins.xata.views.helpers
 */
	class UploaderHelper extends AppHelper{
	/**
	 *	Nombre de este helper.
	 *
	 *	@var string
	 *	@access public
	 */
		var $name = 'Uploader';
	/**
	 *	Indica si es necesario agregar los scripts.
	 *
	 *	@var bool
	 *	@access private
	 */
		var $__scripts = false;

	/**
	 *	Nombre de la clase intermediaria para formularios.
	 *
	 *	@var string
	 *	@access public
	 */
		var $form = 'Form';

	/**
	 *	Helpers usados en esta clase.
	 *
	 *	@var array
	 *	@access public
	 */
		var $helpers = array(
			'Html',
			'Js'
		);
	/**
	 * Guarda el nombre del modelo
	 * @var string
	 * @access private
	 */
		var $__modelName;

	/**
	 * Guarda el nombre del campo
	 * @var string
	 * @access private
	 */
		var $__fieldName;

	/**
	 * Guarda el arreglo de configuracion pasado al behavior
	 * @var string
	 * @access private
	 */
		var $__settings;

	/**
	 *	Constructor - determina el helper intermediario para los formularios.
	 *
	 *	@param array $settings Settings array contains name of engine helper.
	 *	@return void
	 *	@access public
	 */
		function __construct($settings = array()) {
			$className = 'Form';
			if(is_array($settings) && isset($settings[0])) {
				$className = $settings[0];
			}elseif(is_string($settings)){
				$className = $settings;
			}
			$engineName = $className;
			list($plugin,$className) = pluginSplit($className);

			$this->form = $className;
			$engineClass = $engineName;
			$this->helpers[] = $engineClass;
			parent::__construct();
		}

	/**
	 *	Crea los tags necesarios para una entrada de archivo avanzada.
	 *
	 *	@param string $fieldName
	 * 	@param array $options
	 *	@return string HTML válido de una entrada de archivo avanzada.
	 *	@access public
	 */
		function input($fieldName,$options = array()){
			$this->setEntity($fieldName);
			$this->__modelName=$this->model();
			$this->__fieldName=$this->field();
			#list($model,$field,$domId) = array($this->model(),$this->field(),$this->domId());
			$this->setEntity($this->__modelName.'.');
			$this->__settings = Configure::read("Medium.$this->__modelName.$this->__fieldName");
			#pr(Configure::read("Medium.$this->__modelName.$this->__fieldName"));
			$config = $this->__settings;
			if($this->__settings['limit']>1){
				$class="multiUploads";
				$options=am($options,array('multiple'=>true));
				$config=am(array('sortable'=>true),$config);
			}else{
				$class="singleUpload";
			}
			$config['skin']=(isset($options['skin'])) ? $options['skin']: 'UploadDefaultSkin';

			#$data=((isset($this->data[$this->__fieldName]['id']) && $this->data[$this->__fieldName]['id']) || (!empty($this->data[$this->__fieldName]) && $this->data[$this->__fieldName]['id']))? $this->data[$this->__fieldName] : array();
			$data=( !isset($this->data[$this->__fieldName][0]) && empty($this->data[$this->__fieldName]['id']) )? array() : $this->data[$this->__fieldName];

			//$options = am(array('preview' => array()),$options);
			$options = $this->addClass($options,addslashes($this->Js->object($config)),'data-config');
			$options = $this->addClass($options,addslashes($this->Js->object($data)),'data-loaded');
			$options = $this->addClass($options,$this->Html->tag('noscript','[:enable-javascript-please:]',array('escape' => false)),'after');
			#$options = $this->addClass($options,$this->preview($fieldName,$options['preview']),'after');
			#unset($options['preview']);



			$this->__scripts = true;
			return $this->{$this->form}->input($fieldName,am($options,array('type' => 'file','value' => '','class'=>'UploadInput','div'=>array('id'=>$this->__modelName.$this->__fieldName.'Media','class'=>'MediaUploader '.$class))));
		}

		/**
		 *	Se ejecuta después del view.
		 *
		 *	@return void
		 *	@access public
		 */
		function afterRender(){
			if($this->__scripts){
				#$this->Html->script('/media/js/jquery.uploadinput',false);
				#$this->Html->scriptBlock('$(document).ready(function(){$(\'.UploadInput\').UploadInput();});',array('inline' => false));
				#$this->Html->script(array('/media/js/jquery.uploadify','/media/js/swfobject','/media/js/jquery.gritter.min'),false);
				$this->Html->script(array('/media/js/jquery.uploadfiles'),false);
				$this->Html->script(array('/media/js/jquery.uploadi'),false);
				$this->Html->scriptBlock('$(function(){$(\'.MediaUploader\').UploadI();});',array('inline' => false));
				#$this->Html->script(array('/media/js/input-controls'),false);
			}
		}

		/**
		 * Genera la ruta hacia el archivo segun los datos devueltos por el query se usa cuando no se ejecutan los callbacks del modelo y no trae las diferentese medidas de la imagen o archivo
		 * @param Array $data
		 * @param String $folder
		 * @return String
		 */
		function generatePath($data,$folder){
			$path = sprintf('/%s/%s_%s/%s',Configure::read("Media.dir"),$data['model'],$data['foreign_key'],$data['alias']);
			$filename = sprintf('%s-%s.%s',Inflector::slug($data['name'],"-"),$data['id'],$data['extension']);
			return sprintf('%s/%s/%s',$path,$folder,$filename);
		}
	}
?>