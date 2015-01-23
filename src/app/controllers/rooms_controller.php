<?php
class RoomsController extends AppController{
	var $name = "Rooms";
	var $components = array();
	var $helpers = array();

	var $paginate = array(
		'limit'=>30,
		'conditions'=>array(
			'Room.trash'=>0
		),
		'contain'=>array(
			'Location',
		)
	);

	function beforeFilter(){
		parent::beforeFilter();

		# Se obtienen las acciones a las que el usuario tiene permiso de este controller
		$this->access=array(
			'trash'=>$this->__checkAccessUrl(array('controller'=>'rooms','action'=>'trash','admin'=>true,'plugin'=>false)),
			'restore'=>$this->__checkAccessUrl(array('controller'=>'rooms','action'=>'restore','admin'=>true,'plugin'=>false)),
			'destroy'=>$this->__checkAccessUrl(array('controller'=>'rooms','action'=>'destroy','admin'=>true,'plugin'=>false)),
			'delete'=>$this->__checkAccessUrl(array('controller'=>'rooms','action'=>'destroy','admin'=>true,'plugin'=>false)),
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
				$conditions=array('Room.id'=>$this->data['Xpagin']['search']);
			}else{
				$conditions=array("Room.description like"=>"%{$this->data['Xpagin']['search']}%");
			}
		}
		$this->set("recordset",$this->paginate("Room",$conditions));
	}

	function admin_add(){
		if(!empty($this->data)){
			$this->Room->set($this->data);
			if($this->Room->validates()){
				if($this->Room->save()){
					$this->Notifier->success("[:Room_saved_successfully:]");
					$this->redirect(array('action'=>'index'));
				}else{
					$this->Notifier->error("[:an_error_ocurred_on_the_server:]");
				}
			}else{
				$this->Notifier->error("[:some_fields_invalid:]");
			}
		}
		$this->set("locations",$this->Room->Location->find("list",array('conditions'=>array('Location.status'=>1,'Location.trash'=>0))));
	}

	function admin_edit($id){
		if(!empty($id)){
			if(!empty($this->data)){
				$this->Room->set($this->data);
				if($this->Room->validates()){
					if($this->Room->save($this->data, false)){
						$this->Notifier->success("[:Room_updated_successfully:]");
						$this->redirect(array('action'=>'index'));
					}else{
						$this->Room->rollback();
						$this->Notifier->error("[:an_error_ocurred_on_the_server:]");
					}
				}else{
					$this->Notifier->error("[:some_fields_invalid:]");
				}
			}else{
				$this->data = $this->Room->read(null, $id);
				$this->set("locations",$this->Room->Location->find("list",array('conditions'=>array('Location.status'=>1,'Location.trash'=>0))));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Room_id:]"));
		}
	}

	function admin_view($id){
		if(!empty($id)){
			$this->Room->contain(array(
				"Location",
			));
			$this->set("record",$this->Room->read(null, $id));
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Room_id:]"));
		}
	}

	function admin_status($state=null, $id=null){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect($this->referer());
			}
			if(!empty($state) || $state == 0){
				if($this->Room->updateAll(array('Room.status' => $state), array('Room.id' => $id))){
					$this->Notifier->success($this->Interpreter->process(($state) ? "[:Room_publish_successfully:]" : "[:Room_unpublish_successfully:]"));
				}else{
					$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
				}
			}else{
				$this->Notifier->error($this->Interpreter->process("[:specify_a_state:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Room_id:]"));
		}
		if(!$this->Xpagin->isExecuter){
			$this->redirect($this->referer());
		}
	}

	function admin_delete($id){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect($this->referer());
			}
			if($this->Room->updateAll(array('Room.trash' => 1), array('Room.id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Room_deleted_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Room_id:]"));
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
		$conditions=array('Room.trash'=>1);
		if(isset($this->data['Xpagin']['search'])){
			if(is_numeric($this->data['Xpagin']['search'])){
				$conditions=am($conditions,array('Room.id'=>$this->data['Xpagin']['search']));
			}else{
				$conditions=am($conditions,array("Room.name like"=>"%{$this->data['Xpagin']['search']}%"));
			}
		}
		$this->set("recordset",$this->paginate("Room",$conditions));
	}

	function admin_restore($id){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect($this->referer());
			}
			if($this->Room->updateAll(array('Room.trash' => 0), array('Room.id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Room_restored_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Room_id:]"));
		}
		if(!$this->Xpagin->isExecuter){
			$this->redirect($this->referer());
		}
	}

	function admin_destroy($id){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect($this->referer());
			}
			if($this->Room->deleteAll(array('id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Room_deleted_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Room_id_add:]"));
		}
		$referer = Router::parse($this->referer());
		if($referer['action'] == 'view'){
			$this->redirect(array('action'=>'index'));
		}
		$this->redirect($this->referer());
	}

}
?>