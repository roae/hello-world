<?php
class LocationsController extends AppController{
	var $name = "Locations";
	var $components = array('SmartConnector');
	var $helpers = array();

	var $paginate = array(
		'limit'=>30,
		'conditions'=>array(
			'Location.trash'=>0
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
		#$this->SmartConnector->change_pass("CD3aeD5iju");
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

		$this->set("cities",$this->Location->City->find("list",array('conditions'=>array('City.trash'=>0))));
		$this->set("services",$this->Location->Service->find("list",array('conditions'=>array('Service.trash'=>0))));
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
				$this->Location->contain(array(
					"Gallery",
					"Service"
				));
				$this->data = $this->Location->read(null, $id);
			}
			$this->set("cities",$this->Location->City->find("list",array('conditions'=>array('City.trash'=>0))));
			$this->set("services",$this->Location->Service->find("list",array('conditions'=>array('Service.trash'=>0))));
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Location_id:]"));
		}
	}

	function admin_view($id){
		if(!empty($id)){
			$this->Location->contain(array(
				"Gallery",
				"Service"=>array(
					'Icon',
				)
			));
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
				$this->redirect($this->referer());
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
			$this->redirect($this->referer());
		}
	}

	function admin_venta_online($state=null, $id=null){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect($this->referer());
			}
			if(!empty($state) || $state == 0){
				if($this->Location->updateAll(array('Location.venta_online' => $state), array('Location.id' => $id))){
					$this->Notifier->success($this->Interpreter->process(($state) ? "[:Location_venta_online_active_successfully:]" : "[:Location_venta_online_inactive_successfully:]"));
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
			if($this->Location->updateAll(array('Location.trash' => 1), array('Location.id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Location_deleted_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Location_id:]"));
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
				$this->redirect($this->referer());
			}
			if($this->Location->updateAll(array('Location.trash' => 0), array('Location.id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Location_restored_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
			$this->redirect(Router::reverse(Router::parse($this->referer())));
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Location_id:]"));
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
			if($this->Location->deleteAll(array('id' => $id))){
				$this->Notifier->success($this->Interpreter->process("[:Location_deleted_successfully:]"));
			}else{
				$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
			}
			$this->redirect(Router::reverse(Router::parse($this->referer())));
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_Location_id_add:]"));
		}
		if(!$this->Xpagin->isExecuter){
			$referer = Router::parse($this->referer());
			if($referer['action'] == 'view'){
				$this->redirect(array('action'=>'trash'));
			}
			$this->redirect($this->referer());
		}
	}

	function admin_smart_config($id = null){
		if($id){
			$this->Location->id= $id;
			$record = $this->Location->read();
			$this->set("record",$record);
			if(!empty($this->data)){
				/*if(isset($this->data['Location']['smart_newpasswd']) ){
					if($this->data['Location']['smart_newpasswd'] != $record['Location']['smart_passwd']){

					}
				}*/
				$response = false;
				$this->data['Location']['id'] = $id;
				$this->Location->set($this->data);
				if($this->data['Location']['smart_passwd'] != $record['Location']['smart_passwd'] && $this->Location->validates()){
					#Cambiar contraseña
					$this->SmartConnector->settings = array(
						'hosts'=>Configure::read("AppConfig.smart_url"),
						'clientID'=>Configure::read("AppConfig.smart_clientID"),
						'serialPOS'=>$record['Location']['smart_serialpos'],
						'user'=>$record['Location']['smart_user'],
						'passwd'=>$record['Location']['smart_passwd'],
						'location_id'=>$record['Location']['id'],
						'randomKey'=>Configure::read("Smart.randomkey"),
					);
					$response = $this->SmartConnector->change_pass($this->data['Location']['smart_passwd']);

					if($response == "APROBADA"){
						if($this->Location->save($this->data,false)){
							$this->Notifier->success("[:smart-config-successfully-change:]");
							$this->redirect(array('action'=>'index'));
						}else{
							$this->Notifier->error("Ocurri&oacute; un error al guardar la contraseña contacte al administrador");
						}
					}else{
						$message  = " ";
						if(is_string($response)){
							$message = $response;
						}else if(is_array($response) && isset($response['message'])){
							$message = $response['message'];
						}
						$this->Notifier->error($message);
					}

				}


			}else{
				$this->data = $record;
			}

		}
	}

	function get(){
		return $this->Location->find($this->params['type'], $this->params['query']);
	}

	/**
	 * Guarda el id del complejo en una Cookie llamada Location que caduca en 1 año
	 * @param null $id Location
	 */
	function set_location($id = null){
		if(isset($this->data['Location']['id']) || !empty($id)){
			$id = isset($this->data['Location']['id'])? $this->data['Location']['id'] : $id;
			$this->Location->id = $id;
			$data=$this->Location->read(array('id','name'));
			if(!empty($data)){
				$this->Cookie->write("Location",$data['Location'],false,mktime(0,0,0,date("m"),date("d"),date("Y")+1));
			}else{
				$this->Notifier->error("[:location-id-no-existe:]");
			}
		}else{
			$this->Notifier->error("[:no-indico-location-id:]");
		}
		$this->redirect(($this->referer()));

	}

	function index(){
		$this->Location->contain(array('Gallery','Service' => 'Icon', 'City'));
		$locations = $this->Location->find("all",array('conditions'=>array('Location.trash'=>0,'Location.status'=>1),'fields'=>$this->Location->publicFields));
		$this->set(compact("locations"));
		$this->set("cities", $this->Location->City->find("list",array('conditions'=>array('City.trash' => 0,"City.status"=>1 ))));
	}

	function view($id){
		$this->Location->contain(array('Gallery','Service' => 'Icon', 'City'));
		$record = $this->Location->find("first",array('conditions'=>array('Location.trash'=>0,'Location.status'=>1,'Location.id'=>$id)));
		$this->set(compact("record"));
		$this->pageTitle = isset($record['Location']['name'])? $record['Location']['name']." | Citicinemas" : "Citicinemas";
	}

	function map($street_view=0){
		$this->render($street_view ? "street_view" : "map",'map');
	}

}
?>