<?php

App::import('Folder');

/**
 * 	Esta clase contiene la definición de métodos para manipular los archivos
 * 	cargados apoyandose del vendor Upload.
 *
 * 	@package		cms.plugins.media
 * 	@subpackage		cms.plugins.media.models
 */
class Upload extends MediaAppModel{

	var $actsAs = array('Tree');
	/**
	 * 	Nombre para este model.
	 *
	 * 	@var string
	 * 	@access public
	 */
	var $name = 'Upload';
	/**
	 * 	Nombre de la tabla para este model.
	 *
	 * 	@var string
	 * 	@access public
	 */
	var $useTable = 'uploads';
	var $tablePrefix = "media_";
	/**
	 * 	Ruta del directorio temporal de archivos.
	 *
	 * 	@var string
	 * 	@access public
	 */
	var $tmpDir = null;
	/**
	 * 	Instancia de la clase Upload.
	 *
	 * 	@var Upload
	 * 	@access public
	 */
	var $Uploader = null;
	/**
	 * 	Lista de de iconos para dependiendo del tipo de archivo.
	 *
	 * 	@var array
	 * 	@access public
	 */
	var $icons = array(
		'doc.png' => array('doc', 'dot', 'rtf'),
		'docx.png' => array('docx'),
		'xls.png' => array('xls'),
		'xlsx.png' => array('xlsx'),
		'ppt.png' => array('ppt'),
		'pptx.png' => array('pptx'),
		'zip.png' => array('zip', 'rar'),
		'swf.png' => array('swf'),
		'pdf.png' => array('pdf'),
		'txt.png' => array('txt'),
		'image.png' => array('jpg', 'gif', 'bmp', 'png'),
	);
	/**
	 * 	Lista de reglas de validación.
	 *
	 * 	@var array
	 * 	@access public
	 */
	var $validate = array(
		'mime' => array(
			'is-allowed' => array('rule' => array('allowed'), 'message' => '[:error-invalid-file-type:]')
		),
		'size' => array(
			'max_file_size' => array('rule' => array('comparison', '<=', 'dimension'), 'message' => '[:error-file-is-biggest:]'),
		//'min-dimension' => array('rule' => array('minDimension'),'message' => '[:error-min-dimension:]'),
		//'max-dimension' => array('rule' => array('maxDimension'),'message' => '[:error-max-dimension:]')
		)
	);

	/**
	 * 	Elimina un archivo físicamente así como sus copias relacionadas.
	 *
	 * 	@param array $filename Nombre del archivo.
	 * 	@return bool Indica si la operación se realizó correctamente.
	 * 	@access public
	 */
	function drop($filename = null){
		$this->tmpDir = Configure::read("Media.tmpDir");
		if(!$filename){
			if(empty($this->data)){
				return false;
			}
			$filename = $this->data[$this->alias]['temp'] . '.' . $this->data[$this->alias]['extension'];
		}
		@unlink($this->tmpDir . DS . $filename);
		@unlink($this->tmpDir . DS . '~' . $filename);
		return true;
	}

	/**
	 * 	Genera un nombre de archivo temporal único en una ruta y con una
	 * 	extensión.
	 *
	 * 	@param array $path Ruta del archivo.
	 * 	@param array $ext Extensión del archivo.
	 * 	@return string Nombre del archivo temporal único.
	 * 	@access public
	 */
	function temp($path, $ext){
		do{
			$name = intval(mt_rand());
			$file = $path . DS . "$name.$ext";
		}while(file_exists($file));
		return $name;
	}

	/**
	 * 	Marca un campo como invalido y elimina el archivo temporal asociado con
	 * 	los datos. Opcionalmente asigna el nombre de la regla de validación que
	 * 	no se cumple.
	 *
	 * 	@param string $field El nombre del campo a invalidar.
	 * 	@param mixed $value Nombre de la regla de validación que no se cumple.
	 * 		El valor por omisión es true.
	 * 	@return void
	 * 	@access public
	 */
	function invalidate($field, $value = true){
		parent::invalidate($field, $value);
		/**
		 * 	Nota: Si se desea validar datos antes de guardar el registro
		 * 	relacionado por medio de (model,alias,foreign_key) es necesario
		 * 	tener especial cuidado con la siguiente instrucción.
		 */
		//$this->drop();
	}

	/**
	 * 	Copia un archivo de imagen y lo redimensiona de ser necesario.
	 *
	 * 	@param array $dimensions Un array con las opciones de redimensión.
	 * 	@return bool Indica si la operación fue exitosa.
	 * 	@access public
	 */
	function copy($settings, $path, $filename){
		if($this->isImage()){
			list($width, $height) = getimagesize($this->Uploader->file_src_pathname);
			if($width > $settings['width'] || $height > $settings['height']){
				$this->Uploader->image_resize = !isset($settings['resize']) || $settings['resize'];
				$this->Uploader->image_x = $settings['width'];
				$this->Uploader->image_y = $settings['height'];
			}
			$this->Uploader->image_ratio = true;
			$this->Uploader->jpeg_quality = $settings['jpeg_quality'];
			#$this->Uploader->image_ratio_crop= isset($settings['image_ratio_crop'])?$settings['image_ratio_crop']:false ;
			#$this->Uploader->image_ratio_no_zoom_out=isset($settings['image_ratio_no_zoom_out'])?$settings['image_ratio_no_zoom_out']: false ;
			#$this->Uploader->image_ratio_no_zoom_out=isset($settings['image_ratio_no_zoom_in'])?$settings['image_ratio_no_zoom_in']: false ;
		}
		$this->Uploader->file_safe_name = true;
		$this->Uploader->file_new_name_body = $filename;
		$this->Uploader->file_overwrite = false;
		$this->Uploader->file_auto_rename = true;
		$this->Uploader->mime_check = false;
		foreach($settings as $varname => $value){
			if(isset($this->Uploader->{$varname})){
				$this->Uploader->{$varname} = $value;
			}
		}
		$this->Uploader->process($path);
		$this->Uploader->file_safe_name = false;
		$this->Uploader->image_ratio = true;
		$this->Uploader->file_overwrite = true;
		$this->Uploader->file_auto_rename = true;
		$this->Uploader->image_resize = false;
		$this->Uploader->mime_check = true;
		return $this->Uploader->processed;
	}

	/**
	 *	Se ejecuta antes de cargar un archivo y regresa un valor boolean que
	 *	determina si se debe cargar el archivo o no.
	 *
	 *	@return bool Determina si se debe realizar la carga del archivo.
	 *	@access public
	 */
		function loadUploader(){
			$this->tmpDir = Configure::read("Media.tmpDir");
			if(!App::import('Vendor','Media.Uploader')){
				$this->invalidate('name','[:library-not-load:]');
				return false;
			}
			return true;
		}

	function isFolder(){
		return isset($this->data['Upload']['mime']) && $this->data['Upload']['mime']=='folder';
	}

	function beforeValidate(){
		if(!$this->isFolder()){
			if(!$this->loadUploader()){
				return false;
			}
			$this->Uploader = &new Uploader($this->data['Upload']);
			if(!$this->Uploader->uploaded){
				$this->invalidate('name',$this->Uploader->error);
				return false;
			}
			#$this->log(Configure::read("loggedUser"), 'debug');
			$this->set(array(
				#'temp' => $this->temp($this->tmpDir,$this->Uploader->file_src_name_ext),
				'name' => $this->Uploader->file_src_name_body,
				'extension' => $this->Uploader->file_src_name_ext,
				'size' => $this->Uploader->file_src_size,
				'mime' => $this->Uploader->file_src_mime,
				'path' => str_replace(Configure::read("Media.Upload.dir")."/","",$_REQUEST['folder']),
				'width' => $this->Uploader->image_src_x,
				'height' => $this->Uploader->image_src_y,
				'created_by' => Configure::read("loggedUser.User.id"),
				'modified_by' => Configure::read("loggedUser.User.id"),
			));

			$settings = normalizeAllowedConfig(Configure::read('Media.Upload.config'));
			#$this->log($settings,'debug');
			$this->validate['mime']['is-allowed']['rule'][1] = $settings['allowed'];
			$this->validate['size']['max_file_size']['rule'][2] = $settings['max_file_size']*1048576;

			return true;
		}else{
			unset($this->validate['mime']);
		}
		//$this->log($this->data, 'debug');
		return true;
	}

	function beforeSave(){
		if(!$this->isFolder()){
			# se guarda el archivo original
			$path=WWW_ROOT . Configure::read("Media.Upload.dir").$this->data['Upload']['path'];
			//$this->data['Upload']['name']=$this->Uploader->file_dst_name_body;
			//$this->Uploader->file_dst_name_body=Inflector::slug($this->Uploader->file_src_name_body);
			$this->Uploader->process($path);
			$num=str_replace(str_replace(' ','_',$this->data['Upload']['name']),"",$this->Uploader->file_dst_name_body);
			if($num){
				$this->data['Upload']['name'].="$num";
			}
			if(!$this->isFolder() && $this->isImage()){
				# se crea la miniatura
				$this->copy(array('width' => 100, 'height' => 100), $path, 'thumb_'.$this->data['Upload']['name']);
				# se guarda la foto grande
				if($this->data['Upload']['width'] >= 1200){
					$width=1024;
					$height=round($width/$this->data['Upload']['width']*$this->data['Upload']['height']);
					$this->copy(array('width' => $width,'height'=>$height),$path,$width."x".$height."_".$this->data['Upload']['name']);
				}
				# se guarda la foto mediana
				if($this->data['Upload']['width'] >= 400){
					$width=300;
					$height=round($width/$this->data['Upload']['width']*$this->data['Upload']['height']);
					$this->copy(array('width' => $width,'height'=>$height),$path,$width."x".$height."_".$this->data['Upload']['name']);
				}
			}
		}
		return true;
	}

	/**
	 * 	Se ejecuta despues de guardar un registro para realizar operaciones con
	 * 	los archivos físicos (copiar,renombrar,redimensionar, etcétera) de
	 * 	acuerdo con los datos guardados.
	 *
	 * 	@param bool $created Indica si se realizó una operación INSERT.
	 * 	@return void
	 * 	@access public
	 */
	function afterSave($created){
		if($created){
			if($this->data['Upload']['mime']=="folder"){
				# se crea el folder fisicamente
				$folder = &new Folder(WWW_ROOT . Configure::read("Media.Upload.dir").$this->data['Upload']['path'], true, 0777);
			}else{

			}
		}
	}

	/**
	 * 	Se ejecuta despues de realizar una búsqueda para organizar los datos.
	 *
	 * 	@param array $results Un array con los resultados de búsqueda.
	 * 	@param bool $primary Indica si se realizó la búsqueda directamente en
	 * 		este modelo o de lo contrario por medio de una relación. El valor
	 * 		por omisión es false.
	 * 	@return array Resultados de búsqueda reorganizados.
	 * 	@access public
	 */
	function afterFind($results, $primary = false){
		if(isset($results[0]['mime'])){
			foreach($results as $key => $record){
				$this->afterRecord($results[$key]);
			}
		}elseif(isset($results[0][$this->alias]['mime'])){
			foreach($results as $key => $record){
				$this->afterRecord($results[$key][$this->alias]);
			}
		}
		return $results;
	}
	/**
	 * determina el height de la imagen segun el width que se envie
	 * @param <type> $measures
	 * @param <type> $width
	 */
	function __getHeight($measures,$width){
		return round($width/$measures['width']*$measures['height']);
	}

	/**
	 * 	Se ejecuta por cada reorganizar los datos de un registro
	 * 	resultado de una búsqueda.
	 *
	 * 	@param array &$record Una referencia a un arreglo con los datos del registro.
	 * 	@param bool $primary Indica si se realizó la búsqueda directamente en
	 * 		este modelo o de lo contrario por medio de una relación. El valor
	 * 		por omisión es false.
	 * 	@return array Registro reorganizado.
	 * 	@access public
	 */
	function afterRecord(&$record, $primary = false){
		if($record['mime']=="folder"){
			$record['thumb']="/".Configure::read("Media.dir")."/img/gtk-directory.png";
		}else{
			$path="/".Configure::read('Media.Upload.dir').$record['path'];
			$filename=str_replace(' ','_',$record['name']).'.'.$record['extension'];
			$record['thumb']=$path."thumb_".$filename;

		}
	}

	/**
	 * 	Se ejecuta antes de eliminar un registro para obtener los datos del
	 * 	registro a eliminar.
	 *
	 * 	@param bool $cascade Indica si se realizará una eliminación en cascada.
	 * 	@return bool Indica si se debe continuar con el proceso de eliminación.
	 * 	@access public
	 */
	function beforeDelete($cascade = true){
		$this->contain();
		$this->data = $this->read();
		return!empty($this->data);
	}

	/**
	 * 	Se ejecuta después de eliminar un registro de la base de datos para
	 * 	eliminar el archivo físico relacionado.
	 *
	 * 	@return void
	 * 	@access public
	 */
	function afterDelete(){
		extract($this->data[$this->alias]);
		$name = Inflector::slug($name);
		$base = WWW_ROOT . Configure::read("Media.dir") . DS . $model . '_' . $foreign_key . DS . $this->alias . DS;
		@unlink("$base$name-$id.$extension");
		if($this->isImage($mime)){
			$settings = Configure::read("{$this->name}.{$model}.{$this->alias}");
			if(!empty($settings['copies'])){
				foreach($settings['copies'] as $folder => $copy){
					@unlink($base . $folder . DS . "$name-$id.$extension");
				}
			}
			@unlink("$base~$name-$id.$extension");
		}
	}

	/**
	 * Regresa el id de la carpeta que va en la ruta
	 * @param array $path arreglo con la ruta
	 */

	function getFolderID($path){
		$folder=$this->find('first',array('conditions'=>array('path'=>"/".implode('/',$path),'mime'=>'folder'),'fields'=>array('id'),'contains'=>array()));
		return (isset($folder['Upload']['id']))? $folder['Upload']['id'] : null;
	}

}

?>