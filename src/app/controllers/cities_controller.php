<?php
class CitiesController extends AppController{
	var $name = "Cities";
	var $components = array();
	var $helpers = array();

	var $paginate = array(
		'limit'=>30,
		'conditions'=>array(
			'City.trash'=>0
		)
	);

	function admin_index(){
		$conditions=array();
		if(isset($this->data['Xpagin']['search'])){
			if(is_numeric($this->data['Xpagin']['search'])){
				$conditions=array('City.id'=>$this->data['Xpagin']['search']);
			}else{
				$conditions=array("City.name like"=>"%{$this->data['Xpagin']['search']}%");
			}
		}
		$this->set("recordset",$this->paginate("City",$conditions));
	}

	function admin_add(){
		if(!empty($this->data)){
			$this->City->set($this->data);
			if($this->City->validates()){
				if($this->City->save()){
					$this->Notifier->success("[:City_saved_successfully:]");
					$this->redirect(array('action'=>'index'));
				}else{
					$this->Notifier->error("[:an_error_ocurred_on_the_server:]");
				}
			}else{
				$this->Notifier->error("[:some_fields_invalid:]");
			}
		}
	}

	function admin_edit($id){
		if(!empty($id)){
			if(!empty($this->data)){
				$this->City->set($this->data);
				if($this->City->validates()){
					if($this->City->save($this->data, false)){
						$this->Notifier->success("[:City_updated_successfully:]");
						$this->redirect(array('action'=>'index'));
					}else{
						$this->City->rollback();
						$this->Notifier->error("[:an_error_ocurred_on_the_server:]");
					}
				}else{
					$this->Notifier->error("[:some_fields_invalid:]");
				}
			}else{
				$this->data = $this->City->read(null, $id);
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_City_id:]"));
		}
	}

	function admin_view($id){
		if(!empty($id)){
			$this->set("record",$this->City->read(null, $id));
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_City_id:]"));
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
				if($this->City->updateAll(array('City.status' => $state), array('City.id' => $id))){
					$this->Notifier->success($this->Interpreter->process(($state) ? "[:City_publish_successfully:]" : "[:City_unpublish_successfully:]"));
				}else{
					$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
				}
			}else{
				$this->Notifier->error($this->Interpreter->process("[:specify_a_state:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_City_id:]"));
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
			if($this->City->updateAll(array('City.trash' => 1), array('City.id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:City_deleted_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_City_id:]"));
		}
		if(!$this->Xpagin->isExecuter){
			$this->redirect($this->referer());
		}
	}

	function admin_trash(){
		$conditions=array('City.trash'=>1);
		if(isset($this->data['Xpagin']['search'])){
			if(is_numeric($this->data['Xpagin']['search'])){
				$conditions=am($conditions,array('City.id'=>$this->data['Xpagin']['search']));
			}else{
				$conditions=am($conditions,array("City.name like"=>"%{$this->data['Xpagin']['search']}%"));
			}
		}
		$this->set("recordset",$this->paginate("City",$conditions));
	}

	function admin_restore($id){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect(array('action'=>'index'));
			}
			if($this->City->updateAll(array('City.trash' => 0), array('City.id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:City_restored_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
			$this->redirect($this->referer());
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_City_id:]"));
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
			if($this->City->deleteAll(array('id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:City_deleted_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_City_id_add:]"));
		}
		$this->redirect(array('action'=>'index'));
	}

}
?>