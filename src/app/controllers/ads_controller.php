<?php
class AdsController extends AppController{
	var $name = "Ads";
	var $uses = array(
		'Ad',
	);

	var $paginate = array(
		'limit'=>50,
		'contain'=>array(
			'AdsGroup',
		),
		'conditions'=>array(
			'Ad.status'=>1,
			'Ad.trash'=>0,
		)
	);

	function beforeFilter(){
		parent::beforeFilter();

		# Se obtienen las acciones a las que el usuario tiene permiso de este controller
		$this->access=array(
			'trash'=>$this->__checkAccessUrl(array('controller'=>'ads','action'=>'trash','admin'=>true,'plugin'=>false)),
			'restore'=>$this->__checkAccessUrl(array('controller'=>'ads','action'=>'restore','admin'=>true,'plugin'=>false)),
			'destroy'=>$this->__checkAccessUrl(array('controller'=>'ads','action'=>'destroy','admin'=>true,'plugin'=>false)),
			'delete'=>$this->__checkAccessUrl(array('controller'=>'ads','action'=>'destroy','admin'=>true,'plugin'=>false)),
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
				$conditions=array('Ad.id'=>$this->data['Xpagin']['search']);
			}else{
				$conditions=array("Ad.name like"=>"%{$this->data['Xpagin']['search']}%");
			}
		}
		$this->set("recordset",$this->paginate("Ad",$conditions));
	}

	function admin_add(){
		if(!empty($this->data)){
			$this->Ad->set($this->data);
			if($this->Ad->validates()){
				if($this->Ad->save()){
					$this->Notifier->success("[:Ad_saved_successfully:]");
					$this->redirect(array('action'=>'index'));
				}else{
					$this->Notifier->error("[:an_error_ocurred_on_the_server:]");
				}
			}else{
				$this->Notifier->error("[:some_fields_invalid:]");
			}
		}
		$this->set("ads_groups",$this->Ad->AdsGroup->find("list",array('conditions'=>array('AdsGroup.status'=>1,'AdsGroup.trash'=>0))));
	}

	function admin_edit($id){
		if(!empty($id)){
			if(!empty($this->data)){
				$this->Ad->set($this->data);
				if($this->Ad->validates()){
					if($this->Ad->save($this->data, false)){
						$this->Notifier->success("[:Ad_updated_successfully:]");
						$this->redirect(array('action'=>'index'));
					}else{
						$this->Ad->rollback();
						$this->Notifier->error("[:an_error_ocurred_on_the_server:]");
					}
				}else{
					$this->Notifier->error("[:some_fields_invalid:]");
				}
			}else{
				$this->Ad->contain(array(
					"AdsGroup",
					'Vertical',
					'Horizontal',
					'VerticalMini',
					'Cuadro'
				));
				$this->data = $this->Ad->read(null, $id);
				if(empty($this->data) || ($this->data['Ad']['trash'] && !$this->access['trash'])){
					$this->Notifier->error("[:Ad_not_found:]");
					$this->redirect(array('action'=>'index'));
				}
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Ad_id:]"));
		}
		$this->set("ads_groups",$this->Ad->AdsGroup->find("list",array('conditions'=>array('AdsGroup.status'=>1,'AdsGroup.trash'=>0))));
	}

	function admin_view($id){
		if(!empty($id)){
			$this->Ad->contain(array(
				"AdsGroup",
				'Vertical',
				'Horizontal',
				'VerticalMini',
				'Cuadro'
			));
			$record = $this->Ad->read(null, $id);
			if(empty($record) || ($record['Ad']['trash'] && !$this->access['trash'])){
				$this->Notifier->error("[:Ad_not_found:]");
				$this->redirect(array('action'=>'index'));
			}
			$this->set("record",$record);
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Ad_id:]"));
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
				if($this->Ad->updateAll(array('Ad.status' => $state), array('Ad.id' => $id))){
					$this->Notifier->success($this->Interpreter->process(($state) ? "[:Ad_publish_successfully:]" : "[:Ad_unpublish_successfully:]"));
				}else{
					$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
				}
			}else{
				$this->Notifier->error($this->Interpreter->process("[:specify_a_state:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Ad_id:]"));
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
			if($this->Ad->updateAll(array('Ad.trash' => 1), array('Ad.id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Ad_deleted_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Ad_id:]"));
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
		$conditions=array('Ad.trash'=>1);
		if(isset($this->data['Xpagin']['search'])){
			if(is_numeric($this->data['Xpagin']['search'])){
				$conditions=am($conditions,array('Ad.id'=>$this->data['Xpagin']['search']));
			}else{
				$conditions=am($conditions,array("Ad.name like"=>"%{$this->data['Xpagin']['search']}%"));
			}
		}
		$this->set("recordset",$this->paginate("Ad",$conditions));
	}

	function admin_restore($id){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect(array('action'=>'index'));
			}
			if($this->Ad->updateAll(array('Ad.trash' => 0), array('Ad.id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Ad_restored_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Ad_id:]"));
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
				$this->redirect(array('action'=>'index'));
			}
			if($this->Ad->deleteAll(array('id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Ad_deleted_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Ad_id_add:]"));
		}
		$referer = Router::parse($this->referer());
		if($referer['action'] == 'view'){
			$this->redirect(array('action'=>'trash'));
		}
		$this->redirect($this->referer());
	}


	function get(){
		return $this->Ad->find($this->params['type'], $this->params['query']);
	}


}
?>