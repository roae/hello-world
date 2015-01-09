<?php
/**
 *	Este controller contiene actions para cargar por POST, eliminar archivos
 *	temporales y visualizar archivos guardados.
 *
 *	@package		cms.plugins.Media
 *	@subpackage		cms.plugins.Media.controllers
 */
	class FilesController extends MediaAppController{
	/**
	 *	Nombre de este controller.
	 *
	 *	@var string
	 *	@access public
	 */
		var $name = 'Files';

	/**
	 *	Lista de modelos utilizados por este controller.
	 *
	 *	@var array
	 *	@access public
	 */
		var $uses = array(
			'Media.Medium',
			'Media.Upload',
			'Media.TinyImage'
		);
	/**
	 *	Lista de helpers disponibles para las vistas de las acciones de este controller.
	 *
	 *	@var array
	 *	@access public
	 */
		var $helpers=array(
			'Js',
			'Html',
			'Javascript',
			'Media.Uploader',
			'Number'
		);

	/*var $ribbonBar = array(
		'title' => '{image:img} [:admin_media_plugin_title:]',
		'options' => array(
			'image' => array( 'src' => '/media/img/media_folder.png', 'alt' => 'Media' )
		),
		'links' => array()
	);*/

	/**
	 *	Se ejecuta antes de la lógica de cada action.
	 *
	 *	@return void
	 *	@access public
	 */
		function beforeFilter(){
			parent::beforeFilter();
			#Configure::write('debug',0);

			if(isset($this->params['named']['model'])){
				$this->Auth->allow('*');
				extract($this->params['named']);
				extract($this->params,EXTR_OVERWRITE);
				App::import('Model',$model);
				$this->$model = &new $model();
				$this->Medium->model = $model;
				$this->Medium->alias = $alias;
				if(isset($this->History)){
					$this->History->enabled = false;
				}
				$this->helpers = array('Html','Javascript');
			}

			if($this->params['action']=='admin_add_files'){
				$this->Auth->allow('*');
			}
		}

	/**
	 *	Determina a que actions es posible acceder.
	 *
	 *	@return bool Resultado de la prueba.
	 *	@access public
	 */
		function isAuthorizer(){
			return true;
		}

	/**
	 *	Carga un archivo recibido por medio de POST.
	 *
	 *	@return void
	 *	@access public
	 */
		function add(){
			if(empty($this->data)){
				#$this->cakeError('error404');
			}
			$this->layout = 'ajax';
			#$this->log($this->data,'debug');
			if($this->Medium->upload($this->data)){
				$this->set('data',$this->Medium->data[$this->Medium->alias]);
				#$this->log($this->Medium->data[$this->Medium->alias],'debug');
			}else{
				$this->set('errors',$this->Medium->validationErrors);
				#$this->log($this->Medium->validationErrors,'debug');
			}
			$this->data = null;
		}

		function admin_add_files(){
			if(!empty($this->data)){
				$this->layout = 'ajax';
				if($this->Upload->save($this->data['Media'])){
					$this->set('data',$this->Upload->data['Upload']);
				}else{
					$this->set("errors",$this->Upload->validationErrors);
				}
			}
		}

		function admin_add_folder(){
			if(!empty($this->data)){
				$path=$this->Session->read("MediaPath");
				$this->data['Upload']['parent_id']=$this->Upload->getFolderID($path);
				$this->data['Upload']['mime']='folder';
				if(count($path)==0){
					$this->data['Upload']['path']="/".Inflector::slug($this->data['Upload']['name'],'-');
				}else{
					$this->data['Upload']['path']="/".implode("/",$path)."/".Inflector::slug($this->data['Upload']['name'],'-');
				}
				$this->Upload->Save($this->data);
				//$this->log($this->Upload->validationErrors,'debug');
				$this->Notifier->success("[:media_folder_created_successfully:]");
			}
			header('MediaPath: /'.implode("/",$path));
			$this->redirect("/admin/media/".implode("/",$path));
		}

	/**
	 *	Elimina un archivo temporal que fue cargado previamente por POST.
	 *
	 *	@param $filename Nombre del archivo temporal a eliminar.
	 *	@return void
	 *	@access public
	 */
		function delete($filename = null){
			if(!($this->RequestHandler->isAjax() && $filename)){
				$this->cakeError('error404');
			}
			$this->layout = 'ajax';
			$this->Medium->drop($filename);
		}

	/**
	 *	Realiza una redirección tipo 301 en caso de encontrar un archivo que
	 *	coincida con los parámetros recibidos por URL. Para hacer uso de esta
	 *	funcionalidad se debe agregar lo siguiente al archivo routes.php en la
	 *	carpeta de configuración del proyecto:
	 *
	 *	Router::connect(
	 * 		'/files/:model/:foreign_key/:alias/*',
	 * 		array(
	 * 			'plugin' => 'Media',
	 * 			'controller' => 'Files',
	 * 			'action' => 'view'
	 * 		)
	 *	);
	 *
	 *	@param $filename Nombre del archivo.
	 *	@return void
	 *	@access public
	 */
		function view($filename = null,$copy = null){
			if($filename){
				if($copy){
					$aux = $copy;
					$copy = $filename;
					$filename = $aux;
				}else{
					$copy = 'url';
				}
				if(preg_match('/^(.*\-)?([0-9]+)(\..*)?$/',$filename,$matches)){
					$data = $this->File->find('first',array(
						'conditions' => array(
							'id' => $matches[2],
							'model' => $this->params['model'],
							'alias' => $this->params['alias'],
							'foreign_key' => $this->params['foreign_key']
						)
					));
					if(!empty($data[$this->params['alias']][$copy])){
						$this->redirect($data[$this->params['alias']][$copy],301);
					}
				}
			}
			$this->cakeError('error404');
		}

		function admin_delete_file(){
			$path=$this->Session->read("MediaPath");
			if(!empty($this->data)){
				$this->Upload->id=$this->Upload->getFolderID($this->data['Upload']['url']);
			}
			header('MediaPath: /'.implode("/",$path));
			$this->redirect("/admin/media/".implode("/",$path));
		}

		function admin_index(){
			if(isset($this->params['named']['frame'])){
				$this->layout="frame";
			}
			$path=func_get_args();
			//$this->data['Folder']['path']=implode('/',$path);
			$this->set("path",implode('/',$path));
			$this->Session->write('MediaPath',$path);
			if(!empty($path)){
				$this->set("detailPath",$this->Upload->getPath($this->Upload->getFolderID($path),array('id','name','path')));
				$this->set("files",$this->Upload->find('all',array('conditions'=>array('parent_id'=>$this->Upload->getFolderID($path)))));
			}else{
				$this->set("detailPath",false);
				$this->set("files",$this->Upload->find('all',array('conditions'=>array('parent_id IS NULL'))));
			}
			$this->set("folders",$this->Upload->find('threaded',array('conditions'=>array('mime'=>'folder'))));
		}

		function admin_tiny_images(){
			$this->layout="frame";
			if(!empty($this->data)){
				$this->TinyImage->upload($this->data);
			}
	        $this->set('images',$this->TinyImage->readFolder(APP.WEBROOT_DIR.DS.'media'.DS.'uploads'));
		}
	}
?>