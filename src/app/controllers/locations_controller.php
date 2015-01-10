<?php
class LocationsController extends AppController{
	var $name = "Locations";
	var $components = array();
	var $helpers = array();

	var $paginate = array(
		'limit'=>30,
		'conditions'=>array(
			'Location.trash'=>0
		)
	);

	function admin_index(){
		$conditions=array();
		if(isset($this->data['Xpagin']['search'])){
			if(is_numeric($this->data['Xpagin']['search'])){
				$conditions=array('Location.id'=>$this->data['Xpagin']['search']);
			}else{
				$conditions=array("Location.name like"=>"%{$this->data['Xpagin']['search']}%");
			}
		}
		$this->set("recordset",$this->paginate("Location",$conditions));
	}

	function admin_add(){
		if(!empty($this->data)){
			$this->Location->set($this->data);
			if($this->Location->validates()){
				if($this->Location->save()){
					$this->Notifier->success("[:Location_saved_successfully:]");
					$this->redirect(array('action'=>'index'));
				}else{
					$this->Notifier->error("[:an_error_ocurred_on_the_server:]");
				}
			}else{
				$this->Notifier->error("[:some_fields_invalid:]");
			}
		}

		$this->set("cities",$this->Location->City->find("list",array('conditions'=>array('City.status'=>1,'City.trash'=>0))));
	}

	function admin_edit($id){
		if(!empty($id)){
			if(!empty($this->data)){
				$this->Location->set($this->data);
				if($this->Location->validates()){
					if($this->Location->save($this->data, false)){
						$this->Notifier->success("[:Location_updated_successfully:]");
						$this->redirect(array('action'=>'index'));
					}else{
						$this->Location->rollback();
						$this->Notifier->error("[:an_error_ocurred_on_the_server:]");
					}
				}else{
					$this->Notifier->error("[:some_fields_invalid:]");
				}
			}else{
				$this->Location->contain(array("Gallery"));
				$this->data = $this->Location->read(null, $id);
			}
			$this->set("cities",$this->Location->City->find("list",array('conditions'=>array('City.status'=>1,'City.trash'=>0))));
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Location_id:]"));
		}
	}

	function admin_view($id){
		if(!empty($id)){
			$this->set("record",$this->Location->read(null, $id));
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Location_id:]"));
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
				if($this->Location->updateAll(array('Location.status' => $state), array('Location.id' => $id))){
					$this->Notifier->success($this->Interpreter->process(($state) ? "[:Location_publish_successfully:]" : "[:Location_unpublish_successfully:]"));
				}else{
					$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
				}
			}else{
				$this->Notifier->error($this->Interpreter->process("[:specify_a_state:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Location_id:]"));
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
				$this->redirect($this->referer());
			}
			if($this->Location->updateAll(array('Location.trash' => 1), array('Location.id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Location_deleted_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Location_id:]"));
		}
		if(!$this->Xpagin->isExecuter){
			$this->redirect($this->referer());
		}
	}

	function admin_trash(){
		$conditions=array('Location.trash'=>1);
		if(isset($this->data['Xpagin']['search'])){
			if(is_numeric($this->data['Xpagin']['search'])){
				$conditions=am($conditions,array('Location.id'=>$this->data['Xpagin']['search']));
			}else{
				$conditions=am($conditions,array("Location.name like"=>"%{$this->data['Xpagin']['search']}%"));
			}
		}
		$this->set("recordset",$this->paginate("Location",$conditions));
	}

	function admin_restore($id){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect(array('action'=>'index'));
			}
			if($this->Location->updateAll(array('Location.trash' => 0), array('Location.id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Location_restored_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
			$this->redirect($this->referer());
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Location_id:]"));
		}
		if(!$this->Xpagin->isExecuter){
			$this->redirect(array('action'=>'index'));
		}
	}

	function admin_destroy(){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect(array('action'=>'index'));
			}
			if($this->Location->deleteAll(array('id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Location_deleted_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Location_id_add:]"));
		}
		$this->redirect(array('action'=>'index'));
	}

}
?>