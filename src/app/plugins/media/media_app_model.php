<?php
/**
 *	En esta clase se agregarán propiedades y metodos que serán heredados por
 *	todos los models del plugin Media.
 *
 *	@package		cms.plugins.Media
 */
	class MediaAppModel extends AppModel{

		/**
		 *	Instancia de la clase Upload.
		 *
		 *	@var Upload
		 *	@access public
		 */
			var $Uploader = null;

		/**
		 *	Lista de de iconos para dependiendo del tipo de archivo.
		 *
		 *	@var array
		 *	@access public
		 */
			var $icons = array(
				'doc.png' => array('doc','dot','rtf'),
				'docx.png' => array('docx'),
				'xls.png' => array('xls'),
				'xlsx.png' => array('xlsx'),
				'ppt.png' => array('ppt'),
				'pptx.png' => array('pptx'),
				'zip.png' => array('zip','rar'),
				'swf.png' => array('swf'),
				'pdf.png' => array('pdf'),
				'txt.png' => array('txt'),
				'image.png' => array('jpg','gif','bmp','png'),
			);

		

		/**
		 * Nombre de la carpeta donde se guardan los archivos
		 * @var string
		 * @access private;
		 */
			var $__folder;

		/**
		 *	Determina si un archivo es una imagen de acuerdo a su tipo mime.
		 *
		 *	@param string $mime Tipo mime del archivo, si es omitido se
		 *		toma de $this->data.
		 *	@return bool Resultado de la prueba.
		 *	@access public
		 */
			function isImage($mime = null){
				if(!$mime){
					if(empty($this->data)){
						return false;
					}
					$mime = $this->data[$this->alias]['mime'];
				}
				return strpos(strtolower($mime),'image') === 0;
			}

		/**
		 *	Determina si un archivo es de un tipo mime válido.
		 *
		 *	@param array $data Datos a validar.
		 *	@param array $allowed Tipos permitidos.
		 *	@return bool Resultado de la prueba.
		 *	@access public
		 */
			function allowed($data,$allowed = null){
				if(empty($allowed)){
					$this->Uploader->mime_check = false;
					return true;
				}
				$this->Uploader->mime_check = true;
				$this->Uploader->allowed = $allowed;
				return in_array($data['mime'],$allowed);
			}

		/**
		 *	Determina si un archivo tiene un tamaño válido.
		 *
		 *	@param array $data Datos a validar.
		 *	@param int $maxFileSize Máximo tamaño permitido.
		 *	@return bool Resultado de la prueba.
		 *	@access public
		 */
			function maxFileSize($data,$maxFileSize = null){
				return empty($maxFileSize) || $data['size'] <= $maxFileSize;
			}

		/**
		 *	Determina si un archivo de imagen cumple con las dimensiones mínimas.
		 *
		 *	@param array $data Datos a validar.
		 *	@param array $minDimension Dimensiones mínimas permitidas.
		 *	@return bool Resultado de la prueba.
		 *	@access public
		 */
			function minDimension($data,$minDimension = null){
				if(empty($minDimension) || !$this->isImage()){
					return true;
				}
				list($width,$height) = getimagesize($this->Uploader->file_src_pathname);
				return $width >= $minDimension['width'] && $height >= $minDimension['height'];
			}

		/**
		 *	Determina si un archivo de imagen cumple con las dimensiones máximas.
		 *
		 *	@param array $data Datos a validar.
		 *	@param array $maxDimension Dimensiones máximas permitidas.
		 *	@return bool Resultado de la prueba.
		 *	@access public
		 */
			function maxDimension($data,$maxDimension = null){
				if(empty($maxDimension) || !$this->isImage()){
					return true;
				}
				list($width,$height) = getimagesize($this->Uploader->file_src_pathname);
				return $width <= $maxDimension['width'] && $height <= $maxDimension['height'];
			}

		/**
		 *	Se ejecuta antes de cargar un archivo y regresa un valor boolean que
		 *	determina si se debe cargar el archivo o no.
		 *
		 *	@return bool Determina si se debe realizar la carga del archivo.
		 *	@access public
		 */
			function beforeUpload(){
				$this->tmpDir = Configure::read("Media.tmpDir");
				if(!App::import('Vendor','Media.Upload')){
					$this->invalidate('name','[:library-not-load:]');
					return false;
				}
				return true;
			}
		/**
		 *	Determina el archivo de icono que se debe utilizar de acuerdo a la
		 *	extensión del archivo.
		 *
		 *	@param array $extension	Extensión del archivo.
		 *	@return string Ruta del archivo de icono.
		 *	@access public
		 */
			function icon($extension){
				$result = 'unknown.png';
				foreach($this->icons as $image => $extensions){
					if(in_array($extension,$extensions)){
						$result = $image;
						break;
					}
				}
				return "/media/img/$result";
			}
	}
?>