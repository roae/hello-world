<?php
class RoomsController extends AppController{
	var $name = "Rooms";
	var $components = array();
	var $helpers = array();

	var $paginate = array(
		'limit'=>30,
		'conditions'=>array(
			'Room.trash'=>0
		)
	);

	function admin_index(){
		$conditions=array();
		if(isset($this->data['Xpagin']['search'])){
			if(is_numeric($this->data['Xpagin']['search'])){
				$conditions=array('Room.id'=>$this->data['Xpagin']['search']);
			}else{
				$conditions=array("Room.name like"=>"%{$this->data['Xpagin']['search']}%");
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
				$this->redirect(array('action'=>'index'));
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
			$this->redirect(array('action'=>'index'));
		}
	}

	function admin_delete($id){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect(Router::parse($this->referer()));
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
			$this->redirect(Router::parse($this->referer()));
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
				$this->redirect(array('action'=>'index'));
			}
			if($this->Room->updateAll(array('Room.trash' => 0), array('Room.id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Room_restored_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
			$this->redirect(Router::parse($this->referer()));
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Room_id:]"));
		}
		if(!$this->Xpagin->isExecuter){
			$this->redirect(array('action'=>'index'));
		}
	}

	function admin_destroy($id){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect(array('action'=>'index'));
			}
			if($this->Room->deleteAll(array('id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Room_deleted_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Room_id_add:]"));
		}
		$this->redirect(array('action'=>'index'));
	}

}
?>