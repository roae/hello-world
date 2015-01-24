<?php
/**
 * Class MoviesController
 *
 * @property $Movie Movie
 */
class MoviesController extends AppController{
	var $name = "Movies";
	var $components = array();
	var $helpers = array();

	var $paginate = array(
		'limit'=>30,
		'conditions'=>array(
			'Movie.trash'=>0
		),
		'contain'=>array(
			'Poster',
		)
	);

	function beforeFilter(){
		parent::beforeFilter();

		# Se obtienen las acciones a las que el usuario tiene permiso de este controller
		$this->access=array(
			'trash'=>$this->__checkAccessUrl(array('controller'=>'movies','action'=>'trash','admin'=>true,'plugin'=>false)),
			'restore'=>$this->__checkAccessUrl(array('controller'=>'movies','action'=>'restore','admin'=>true,'plugin'=>false)),
			'destroy'=>$this->__checkAccessUrl(array('controller'=>'movies','action'=>'destroy','admin'=>true,'plugin'=>false)),
			'delete'=>$this->__checkAccessUrl(array('controller'=>'movies','action'=>'destroy','admin'=>true,'plugin'=>false)),
		);
		$this->set("trashAccess",$this->access['trash']);
		$this->set("restoreAccess",$this->access['restore']);
		$this->set("destroyAccess",$this->access['destroy']);
		$this->set("deleteAccess",$this->access['delete']);

	}

	function admin_index(){
		$conditions=array();
		if(isset($this->data['Xpagin']['search']) && !empty($this->data['Xpagin']['search'])){
			if(is_numeric($this->data['Xpagin']['search'])){
				$conditions=array('Movie.id'=>$this->data['Xpagin']['search']);
			}else{
				$conditions=array("Movie.title like"=>"%{$this->data['Xpagin']['search']}%");
			}
		}
		$this->set("recordset",$this->paginate("Movie",$conditions));
	}

	function admin_add(){
		if(!empty($this->data)){
			$this->Movie->set($this->data);
			$transaction = $this->Movie->save();
			if ( $transaction == 1 ) {
				$this->Notifier->success ( "[:Movie_saved_successfully:]" );
				$this->redirect ( array( 'action' => 'index' ) );
			} else if ( $transaction == -1 ) {
				$this->Notifier->error ( "[:an_error_ocurred_on_the_server:]" );
			} else {
				$this->Notifier->error ( "[:some_fields_invalid:]" );
			}
		}
		$this->set("locations",$this->Movie->MovieLocation->Location->find("list",array('Location.status'=>1,'Location.trash'=>0)));
	}

	function admin_edit($id = null){
		if(!empty($id)){
			if(!empty($this->data)){
				$this->Movie->set($this->data);
				$transaction = $this->Movie->save();
				if ( $transaction == 1 ) {
					$this->Notifier->success ( "[:Movie_saved_successfully:]" );
					$this->redirect ( array( 'action' => 'index' ) );
				} else if ( $transaction == -1 ) {
					$this->Notifier->error ( "[:an_error_ocurred_on_the_server:]" );
				} else {
					$this->Notifier->error ( "[:some_fields_invalid:]" );
				}
			}else{
				$this->Movie->contain(array(
					"Poster",
					"Gallery",
					"Projection",
					'MovieLocation',
				));
				$this->data = $this->Movie->read(null, $id);
				if(empty($this->data) || ($this->data['Movie']['trash'] && !$this->access['trash'])){
					$this->Notifier->error("[:Movie_not_found:]");
					$this->redirect(array('action'=>'index'));
				}
			}
			$this->set("locations",$this->Movie->MovieLocation->Location->find("list",array('Location.status'=>1,'Location.trash'=>0)));
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Movie_id:]"));
		}
	}

	function admin_view($id = null){
		if(!empty($id)){
			$this->Movie->contain(array(
				"Poster",
				"Gallery",
				"Projection",
				'MovieLocation'=>array(
					'Location'
				)
			));
			$record = $this->Movie->read(null, $id);
			$this->set("record",$record);
			if(empty($record) || ($record['Movie']['trash'] && !$this->access['trash'])){
				$this->Notifier->error("[:Movie_not_found:]");
				$this->redirect(array('action'=>'index'));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Movie_id:]"));
		}
	}

	function admin_status($state=null, $id=null){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect($this-referer());
			}
			if(!empty($state) || $state == 0){
				if($this->Movie->updateAll(array('Movie.status' => $state), array('Movie.id' => $id))){
					$this->Notifier->success($this->Interpreter->process(($state) ? "[:Movie_publish_successfully:]" : "[:Movie_unpublish_successfully:]"));
				}else{
					$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
				}
			}else{
				$this->Notifier->error($this->Interpreter->process("[:specify_a_state:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Movie_id:]"));
		}
		if(!$this->Xpagin->isExecuter){
			$this->redirect($this-referer());
		}
	}

	function admin_delete($id = null){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect($this->referer());
			}
			if($this->Movie->updateAll(array('Movie.trash' => 1), array('Movie.id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Movie_deleted_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Movie_id:]"));
		}
		if(!$this->Xpagin->isExecuter){
			$referer = Router::parse($this->referer());
			if($referer['action'] == 'edit'){
				$this->redirect(array('action'=>'index'));
			}
			$this->redirect($this->referer());
		}
	}

	function admin_trash(){
		$conditions=array('Movie.trash'=>1);
		if(isset($this->data['Xpagin']['search'])){
			if(is_numeric($this->data['Xpagin']['search'])){
				$conditions=am($conditions,array('Movie.id'=>$this->data['Xpagin']['search']));
			}else{
				$conditions=am($conditions,array("Movie.name like"=>"%{$this->data['Xpagin']['search']}%"));
			}
		}
		$this->set("recordset",$this->paginate("Movie",$conditions));
	}

	function admin_restore($id= null){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect($this->referer());
			}
			if($this->Movie->updateAll(array('Movie.trash' => 0), array('Movie.id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Movie_restored_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Movie_id:]"));
		}
		if(!$this->Xpagin->isExecuter){
			$this->redirect($this->referer());
		}
	}

	function admin_destroy($id= null){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect($this->referer());
			}
			if($this->Movie->deleteAll(array('id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Movie_deleted_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Movie_id_add:]"));
		}
		$referer = Router::parse($this->referer());
		if($referer['action'] == 'view'){
			$this->redirect(array('action'=>'trash'));
		}
		$this->redirect($this->referer());
	}

}
?>