<?php
class ServicesController extends AppController{
	var $name = "Services";
	var $components = array();
	var $helpers = array();

	var $paginate = array(
		'limit'=>30,
		'conditions'=>array(
			'Service.trash'=>0
		),
		'contain'=>array(
			'Icon',
		)
	);

	function beforeFilter(){
		parent::beforeFilter();

		# Se obtienen las acciones a las que el usuario tiene permiso de este controller
		$this->access=array(
			'trash'=>$this->__checkAccessUrl(array('controller'=>'services','action'=>'trash','admin'=>true,'plugin'=>false)),
			'restore'=>$this->__checkAccessUrl(array('controller'=>'services','action'=>'restore','admin'=>true,'plugin'=>false)),
			'destroy'=>$this->__checkAccessUrl(array('controller'=>'services','action'=>'destroy','admin'=>true,'plugin'=>false)),
			'delete'=>$this->__checkAccessUrl(array('controller'=>'services','action'=>'destroy','admin'=>true,'plugin'=>false)),
		);
		$this->set("trashAccess",$this->access['trash']);
		$this->set("restoreAccess",$this->access['restore']);
		$this->set("destroyAccess",$this->access['destroy']);
		$this->set("deleteAccess",$this->access['delete']);

	}

	function admin_index(){
		$conditions=array();
		if(isset($this->data['Xpagin']['search'])){
			if(is_numeric($this->data['Xpagin']['search'])){
				$conditions=array('Service.id'=>$this->data['Xpagin']['search']);
			}else{
				$conditions=array("Service.name like"=>"%{$this->data['Xpagin']['search']}%");
			}
		}
		$this->set("recordset",$this->paginate("Service",$conditions));
	}

	function admin_add(){
		if(!empty($this->data)){
			$this->Service->set($this->data);
			if($this->Service->validates()){
				if($this->Service->save()){
					$this->Notifier->success("[:Service_saved_successfully:]");
					$this->redirect(array('action'=>'index'));
				}else{
					$this->Notifier->error("[:an_error_ocurred_on_the_server:]");
				}
			}else{
				$this->Notifier->error("[:some_fields_invalid:]");
			}
		}
	}

	function admin_edit($id = null){
		if(!empty($id)){
			if(!empty($this->data)){
				$this->Service->set($this->data);
				if($this->Service->validates()){
					if($this->Service->save($this->data, false)){
						$this->Notifier->success("[:Service_updated_successfully:]");
						$this->redirect(array('action'=>'index'));
					}else{
						$this->Service->rollback();
						$this->Notifier->error("[:an_error_ocurred_on_the_server:]");
					}
				}else{
					$this->Notifier->error("[:some_fields_invalid:]");
				}
			}else{
				$this->Service->contain(array(
					"Icon",
					"Gallery",
				));
				$this->data = $this->Service->read(null, $id);
				if(empty($this->data) || ($this->data['Service']['trash'] && !$this->access['trash'])){
					$this->Notifier->error("[:Service_not_found:]");
					$this->redirect(array('action'=>'index'));
				}
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Service_id:]"));
		}
	}

	function admin_view($id = null){
		if(!empty($id)){
			$this->Service->contain(array(
				"Icon",
				"Gallery",
			));
			$record = $this->Service->read(null, $id);
			$this->set("record",$record);
			if(empty($record) || ($record['Service']['trash'] && !$this->access['trash'])){
				$this->Notifier->error("[:Service_not_found:]");
				$this->redirect(array('action'=>'index'));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Service_id:]"));
		}
	}

	function admin_status($state=null, $id=null){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect(array('action'=>'index'));
			}
			if(!empty($state) || $state == 0){
				if($this->Service->updateAll(array('Service.status' => $state), array('Service.id' => $id))){
					$this->Notifier->success($this->Interpreter->process(($state) ? "[:Service_publish_successfully:]" : "[:Service_unpublish_successfully:]"));
				}else{
					$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
				}
			}else{
				$this->Notifier->error($this->Interpreter->process("[:specify_a_state:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Service_id:]"));
		}
		if(!$this->Xpagin->isExecuter){
			$this->redirect(array('action'=>'index'));
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
			if($this->Service->updateAll(array('Service.trash' => 1), array('Service.id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Service_deleted_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Service_id:]"));
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
		$conditions=array('Service.trash'=>1);
		if(isset($this->data['Xpagin']['search'])){
			if(is_numeric($this->data['Xpagin']['search'])){
				$conditions=am($conditions,array('Service.id'=>$this->data['Xpagin']['search']));
			}else{
				$conditions=am($conditions,array("Service.name like"=>"%{$this->data['Xpagin']['search']}%"));
			}
		}
		$this->set("recordset",$this->paginate("Service",$conditions));
	}

	function admin_restore($id= null){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect($this->referer());
			}
			if($this->Service->updateAll(array('Service.trash' => 0), array('Service.id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Service_restored_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Service_id:]"));
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
			if($this->Service->deleteAll(array('id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Service_deleted_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Service_id_add:]"));
		}
		$referer = Router::parse($this->referer());
		if($referer['action'] == 'view'){
			$this->redirect(array('action'=>'trash'));
		}
		$this->redirect($this->referer());
	}

	function index() {
		$services = $this->Service->find('all',array(
			'conditions'=>array('Service.trash'=>0),
			'contain'=>array('Icon','Gallery'),
		));
		$this->set("recordset", $services);
	}

}
?>