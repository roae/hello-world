<?php
App::import('Folder');
/**
 *	Esta clase contiene la definición de métodos para manipular los archivos
 *	cargados apoyandose del vendor Upload.
 *
 *	@package		cms.plugins.media
 *	@subpackage		cms.plugins.media.models
 */
	class Medium extends MediaAppModel{
	/**
	 *	Nombre para este model.
	 *
	 *	@var string
	 *	@access public
	 */
		var $name = 'Medium';

	/**
	 *	Nombre de la tabla para este model.
	 *
	 *	@var string
	 *	@access public
	 */
		var $useTable = 'mediums';

		var $tablePrefix="media_";

	/**
	 *	Ruta del directorio temporal de archivos.
	 *
	 *	@var string
	 *	@access public
	 */
		var $tmpDir = null;



	/**
	 *	Nombre del modelo relacionado.
	 *
	 *	@var string
	 *	@access public
	 */
		var $model = null;

	/**
	 *	Lista de reglas de validación.
	 *
	 *	@var array
	 *	@access public
	 */
		var $validate = array(
			'mime' => array(
				'is-allowed' => array('rule' => array('allowed'),'message' => '[:error-invalid-file-type:]')
			),
			'size' => array(
				'max_file_size' => array('rule' => array('comparison','<=','dimension'),'message' => '[:error-file-is-biggest:]'),
				'min-dimension' => array('rule' => array('minDimension'),'message' => '[:error-min-dimension:]'),
				'max-dimension' => array('rule' => array('maxDimension'),'message' => '[:error-max-dimension:]')
			)
		);
	/**
	 * Nombre de la carpeta donde se guardan los archivos
	 * @var string
	 * @access private;
	 */
		var $__folder;

	/**
	 *	Elimina un archivo físicamente así como sus copias relacionadas.
	 *
	 *	@param array $filename Nombre del archivo.
	 *	@return bool Indica si la operación se realizó correctamente.
	 *	@access public
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
	 *	Genera un nombre de archivo temporal único en una ruta y con una
	 *	extensión.
	 *
	 *	@param array $path Ruta del archivo.
	 *	@param array $ext Extensión del archivo.
	 *	@return string Nombre del archivo temporal único.
	 *	@access public
	 */
		function temp($path,$ext){
			do{
				$name = intval(mt_rand());
				$file = $path . DS . "$name.$ext";
			}while(file_exists($file));
			return $name;
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

	/**
	 *	Carga un archivo y genera una copia en miniatura del mismo en caso de
	 *	ser una imagen.
	 *
	 *	@param array $data Datos del archivo recividos por POST.
	 *	@return bool Indica si la operación fue exitosa.
	 *	@access public
	 */
		function upload($data = null){

			if(empty($data) || !$this->loadUploader()){
				return false;
			}

			foreach($data as $model => $attachments){
				if(empty($attachments)){
					return false;
				}

				foreach($attachments as $alias => $file){

					$this->Uploader = &new Uploader($file);

					if(!$this->Uploader->uploaded){
						$this->invalidate('name',$this->Uploader->error);
						return false;
					}
					$this->set(array(
						'temp' => $this->temp($this->tmpDir,$this->Uploader->file_src_name_ext),
						'name' => $this->Uploader->file_src_name_body,
						'extension' => $this->Uploader->file_src_name_ext,
						'size' => $this->Uploader->file_src_size,
						'mime' => $this->Uploader->file_src_mime,
						'thumb' => $this->icon($this->Uploader->file_src_name_ext)
					));
					#$this->log($this->data,'debug');
					$settings = Configure::read('Medium.' . $this->model . '.' . $this->alias);
					#pr($settings);
					$this->validate['mime']['is-allowed']['rule'][1] = $settings['allowed'];
					$this->validate['size']['max_file_size']['rule'][2] = $settings['max_file_size']*1048576;
					$this->validate['size']['min-dimension']['rule'][1] = $settings['min_dimension'];
					$this->validate['size']['max-dimension']['rule'][1] = $settings['max_dimension'];
					if(!$this->validates()){
						#$this->log($this->data,'debug');
						return false;
					}
					$file = &$this->data[$this->alias];
					if($isImage = $this->isImage()){
						$thumb = '~' . $file['temp'];

						if($this->copy(array('width' => 100,'height' => 100),$this->tmpDir,$thumb,$this->Uploader->file_src_name_ext)){
							$file['thumb'] = $this->tmpDir.'/'. $thumb . '.' . $file['extension'];
						}else{

							$this->invalidate('name',$this->Uploader->error);
							return false;
						}
					}
					if(!$this->copy($settings,$this->tmpDir,$file['temp'],$this->Uploader->file_src_name_ext)){
						$this->invalidate('name',$this->Uploader->error);
						#$this->log($this->data,'debug');
						return false;
					}
				}
			}
			return true;
		}

	/**
	 *	Marca un campo como invalido y elimina el archivo temporal asociado con
	 *	los datos. Opcionalmente asigna el nombre de la regla de validación que
	 *	no se cumple.
	 *
	 *	@param string $field El nombre del campo a invalidar.
	 *	@param mixed $value Nombre de la regla de validación que no se cumple.
	 *		El valor por omisión es true.
	 *	@return void
	 *	@access public
	 */
 		function invalidate($field,$value = true){
			parent::invalidate($field,$value);
			/**
			 *	Nota: Si se desea validar datos antes de guardar el registro
			 *	relacionado por medio de (model,alias,foreign_key) es necesario
			 *	tener especial cuidado con la siguiente instrucción.
			 */
			$this->drop();
		}

	/**
	 *	Copia un archivo de imagen y lo redimensiona de ser necesario.
	 *
	 *	@param array $dimensions Un array con las opciones de redimensión.
	 *	@return bool Indica si la operación fue exitosa.
	 *	@access public
	 */
		function copy($settings,$path,$filename,$extension){
			if($this->isImage()){
				#$this->log($this->Uploader->file_src_pathname,"debug");
				#pr($this->Uploader->file_src_pathname);
				list($width,$height) = getimagesize($this->Uploader->file_src_pathname);
				#if($width > $settings['width'] || $height > $settings['height']){
					$this->Uploader->image_resize = !isset($settings['resize']) || $settings['resize'];
					$this->Uploader->image_x = $settings['width'];
					$this->Uploader->image_y = $settings['height'];
				#}
				$this->Uploader->image_ratio = isset($settings['image_ratio_crop'])?$settings['image_ratio_crop']:true ;
				$this->Uploader->image_ratio_crop= isset($settings['image_ratio_crop'])?$settings['image_ratio_crop']:false ;
				$this->Uploader->image_ratio_no_zoom_out=isset($settings['image_ratio_no_zoom_out'])?$settings['image_ratio_no_zoom_out']: false ;
				$this->Uploader->image_ratio_no_zoom_in=isset($settings['image_ratio_no_zoom_in'])?$settings['image_ratio_no_zoom_in']: false ;
				$this->Uploader->image_ratio_x= isset($settings['image_ratio_x'])?$settings['image_ratio_x']:false ;
				$this->Uploader->image_ratio_y= isset($settings['image_ratio_y'])?$settings['image_ratio_y']:false ;
			}
			$this->Uploader->file_safe_name = false;
			$this->Uploader->file_new_name_body = $filename;
			$this->Uploader->file_overwrite = true;
			$this->Uploader->file_auto_rename = false;
			$this->Uploader->mime_check = false;
			foreach($settings as $varname => $value){
				if(isset($this->Uploader->{$varname})){
					$this->Uploader->{$varname} = $value;
				}
			}

			$this->Uploader->process($path);

			chmod(sprintf("%s".DS."%s.%s",$path,$filename,$extension),0777);

			$this->Uploader->file_safe_name = false;
			$this->Uploader->image_ratio = true;
			$this->Uploader->file_overwrite = true;
			$this->Uploader->file_auto_rename = true;
			$this->Uploader->image_resize = false;
			$this->Uploader->mime_check = true;

			return $this->Uploader->processed;
		}

	/**
	 *	Se ejecuta despues de guardar un registro para realizar operaciones con
	 *	los archivos físicos (copiar,renombrar,redimensionar, etcétera) de
	 *	acuerdo con los datos guardados.
	 *
	 *	@param bool $created Indica si se realizó una operación INSERT.
	 *	@return void
	 *	@access public
	 */
		function afterSave($created){
			if(!$this->loadUploader()){
				return false;
			}
			if(isset($this->data[$this->alias]['model'])){
				extract($this->data[$this->alias],EXTR_SKIP);
				$base = &new Folder(WWW_ROOT . Configure::read("Media.dir") . DS . $model . '_' . $foreign_key . DS . $this->alias,true,0777);
				$settings = Configure::read("{$this->name}.{$model}.{$this->alias}");
				$filename = Inflector::slug($name,'-') . '-' . $this->id;
				$tmpDir = $created ? $this->tmpDir : $base->path;
				$_dir=Configure::read("Media.settings.folder");
				#$this->log($tmpDir . DS . $temp . '.' . $extension,"uploader");

				$this->Uploader = &new Uploader($tmpDir . DS . $temp . '.' . $extension);
				#$this->log($tmpDir . DS . $temp . '.' . $extension,"uploader");
				#$this->log($this->Uploader,"uploader");
				if($this->isImage()){
					if(!empty($settings['copies'])){
						foreach($settings['copies'] as $folder => $copy){
							$basename = $folder;
							$folder = &new Folder($base->path . DS . $folder,true,0777);
							if($this->copy($copy,$folder->path,$filename,$extension)){
								$this->data[$this->alias][$basename.'$'] = $folder->path . DS . $filename . '.' . $extension;
								$this->data[$this->alias][$basename] = sprintf('/%s/%s_%s/%s/%s/%s.%s',$_dir,$model,$foreign_key,$alias,$basename,$filename,$extension);
							}
							if(!$created && $filename !== $temp){
								@unlink($folder->path . DS . $temp . '.' . $extension);
							}
						}
					}
					@rename($tmpDir . DS . '~' . $temp . '.' . $extension,$base->path . DS . '~' . $filename . '.' . $extension);
					$this->data[$this->alias]['thumb$'] = $base->path . DS . '~' . $filename . '.' . $extension;
					$this->data[$this->alias]['thumb'] = sprintf('/%s/%s_%s/%s/~%s.%s',$_dir,$model,$foreign_key,$alias,$filename,$extension);
				}
				if($this->copy($settings,$base->path,$filename,$extension)){
					$this->data[$this->alias]['$'] = $base->path . DS . $filename . '.' .$extension;
					$this->data[$this->alias]['url'] = sprintf('/%s/%s_%s/%s/%s.%s',$_dir,$model,$foreign_key,$alias,$filename,$extension);
				}
				if($created || $filename !== $temp){
					@unlink($tmpDir . DS . $temp . '.' . $extension);
				}
			}
		}

	/**
	 *	Se ejecuta despues de realizar una búsqueda para organizar los datos.
	 *
	 *	@param array $results Un array con los resultados de búsqueda.
	 *	@param bool $primary Indica si se realizó la búsqueda directamente en
	 *		este modelo o de lo contrario por medio de una relación. El valor
	 *		por omisión es false.
	 *	@return array Resultados de búsqueda reorganizados.
	 *	@access public
	 */
		function afterFind($results,$primary = false){
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
	 *	Se ejecuta por cada reorganizar los datos de un registro
	 *	resultado de una búsqueda.
	 *
	 *	@param array &$record Una referencia a un arreglo con los datos del registro.
	 *	@param bool $primary Indica si se realizó la búsqueda directamente en
	 *		este modelo o de lo contrario por medio de una relación. El valor
	 *		por omisión es false.
	 *	@return array Registro reorganizado.
	 *	@access public
	 */
		function afterRecord(&$record,$primary = false){
			if(empty($record['mime'])){
				return;
			}
			$path = sprintf('/%s/%s_%s/%s',Configure::read("Media.dir"),$record['model'],$record['foreign_key'],$record['alias']);
			$filename = sprintf('%s-%s.%s',Inflector::slug($record['name'],'-'),$record['id'],$record['extension']);
			$record['url'] = sprintf('%s/%s',$path,$filename);
			$record['temp'] = Inflector::slug($record['name'],'-') .  '-' . $record['id'];
			if($image = $this->isImage($record['mime'])){
				$record['thumb'] = sprintf('%s/~%s',$path,$filename);
				$settings = Configure::read("{$this->name}.{$record['model']}.{$record['alias']}");
				if(!empty($settings['copies'])){
					foreach($settings['copies'] as $folder => $copy){
						$record[$folder] = sprintf('%s/%s/%s',$path,$folder,$filename);
						if($fullpath = fileExistsInPath($record[$folder])){
							$file = &new File($fullpath);
							$record[$folder . '$'] = $file->path;
						}else{
							$record[$folder] = sprintf('/img/no-pic-%s-%s.jpg',$copy['width'],$copy['height']);
						}
					}
				}
				if($fullpath = fileExistsInPath($record['thumb'])){
					$file = &new File($fullpath);
					$record['thumb$'] = $file->path;
				}else{
					$record['thumb'] = '/img/no-pic-100-100.jpg';
				}
			}else{
				$record['thumb'] = $this->icon($record['extension']);
				$record['temp'] = Inflector::slug($record['name'],'-') . '-' . $record['id'];
			}
			if($fullpath = fileExistsInPath($record['url'])){
				$file = &new File($fullpath);
				$record['$'] = $file->path;
			}elseif($image){
				$record['url'] = $settings['resize'] ? sprintf('/img/no-pic-%s-%s.jpg',$settings['width'],$settings['height']) : '/img/no-pic.jpg';
			}
		}


	/**
	 *	Se ejecuta antes de eliminar un registro para obtener los datos del
	 *	registro a eliminar.
	 *
	 *	@param bool $cascade Indica si se realizará una eliminación en cascada.
	 *	@return bool Indica si se debe continuar con el proceso de eliminación.
	 *	@access public
	 */
		function beforeDelete($cascade = true){
			$this->contain();
			$this->data = $this->read();
			return !empty($this->data);
		}

	/**
	 *	Se ejecuta después de eliminar un registro de la base de datos para
	 *	eliminar el archivo físico relacionado.
	 *
	 *	@return void
	 *	@access public
	 */
		function afterDelete(){
			extract($this->data[$this->alias]);
			$name = Inflector::slug($name,'-');
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
	}
?>