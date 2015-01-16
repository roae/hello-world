<?php
/**
 * Class UsersController
 * @property $User USer
 */
class UsersController extends AppController{
	var $name = 'Users';
	var $uses=array(
		'User'
	);

	var $ribbonBar=array(
		'title'=>'{image:img} [:admin_security_title:]',
		'options'=>array(
			'image'=>array('src'=>'admin/ribbon_security.png','alt'=>'Securirty')
		),
		'links'=>array()
	);

	var $paginate=array('fields'=>array('id','username','created','modified','status','Group.name'),'limit'=>10);

	function beforeFilter(){
		$this->Auth->allow('logout');
		parent::beforeFilter();
	}

	function admin_login(){
		$this->login();
	}

	function admin_logout() {
		$this->logout();
	}

	function admin_index(){
		$this->set("recordset",$this->paginate("User"));
	}

	function admin_add(){
		if(!empty($this->data)){
			#pr($this->data['User']['password']);
			#$this->data['User']['password_confirm'] = $this->Auth->password($this->data['User']['password_confirm']);
			$this->User->set($this->data['User']);
			if($this->User->validates()){
				if($this->_permit($this->data['User']['group_id'])){
					$this->data['User']['status'] = 1;
					$this->User->save($this->data,false);
					$this->Acl->allow($this->User,$this->User);
					$this->Notifier->success('[:admin_user_save_success:]');
					$this->redirect(array('action' => 'index'));
				}else{
					$this->User->invalidate('grupo_id','[:admin_no_tiene_permisos_de_agregar_a_este_grupo:]');
				}
			}
			#pr($this->User->invalidFields());
			unset($this->data['User']['password'],$this->data['User']['password_confirm']);
			$this->Session->setFlash('[:admin_user_add_error:]','default',array('class' => 'error'));
		}
		$this->set('groups',$this->User->Group->find('list',array('fields'=>array('id','name'),'order' => array('Group.name' => 'ASC'))));
	}

	function admin_edit($id){
		if(!empty($this->data)){
			#$this->data['User']['password'] = $this->data['User']['password_confirm'] = "";
			$this->User->set($this->data['User']);
			unset($this->User->validate['password'], $this->User->validate['password_confirm']);
			if($this->User->validates()){
				#if($this->_permit($this->data['User']['group_id'])){
					//$this->data['User']['status'] = 1;
					$this->User->save($this->data,false);
					#$this->Acl->allow($this->User,$this->User);
					$this->Notifier->success('[:admin_user_update_success:]');
					$this->redirect(array('action' => 'index'));
				#}else{
					//$this->User->invalidate('grupo_id','[:admin_no_tiene_permisos_de_agregar_a_este_grupo:]');
				#}
			}
			#pr($this->User->invalidFields());
			//unset($this->data['User']['password'],$this->data['User']['password_confirm']);
			$this->Notifier->error('[:admin_user_edit_error:]');
		}
		$this->set('groups',$this->User->Group->find('list',array('fields'=>array('id','name'),'order' => array('Group.name' => 'ASC'))));
		$this->User->id=$id;
		$this->data=$this->User->read();
	}

	function admin_password($id = null){
		if(!empty($this->data)){
			unset(
				$this->User->validate['nombre'],
				$this->User->validate['paterno'],
				$this->User->validate['materno'],
				$this->User->validate['group_id'],
				$this->User->validate['username']
			);
			$this->data['User']['password']=$this->Auth->password($this->data['User']['password']);
			$this->data['User']['id'] = $id;
			$this->User->set($this->data);
			if($this->User->validates(array('fieldList'=>array('password','password_confirm'))) && $this->User->save($this->data,false)){
				$this->Notifier->success("[:admin_user_password_change_success:]");
				$this->redirect(array('action'=>'index'));
			}else{
				$this->Notifier->error("[:validation_errors:]");
			}
		}
	}

	function admin_status($state=null, $id=null){
		if(!empty($id) || $this->Xpagin->isExecuter){
			if(empty($id) && !empty($this->data['Xpagin']['record'])){
				$id = $this->data['Xpagin']['record'];
			}else if(empty($id)){
				$this->Notifier->error($this->Interpreter->process("[:no_items_selected:]"));
				$this->redirect("/admin/users/index/");
			}
			if(!empty($state) || $state == 0){
				if($this->User->updateAll(array('User.status' => $state), array('User.id' => $id))){
					$this->Notifier->success($this->Interpreter->process(($state) ? "[:User_publish_successfully:]" : "[:User_unpublish_successfully:]"));
				}else{
					$this->Notifier->success($this->Interpreter->process("[:an_error_ocurred_on_the_server:]"));
				}
			}else{
				$this->Notifier->error($this->Interpreter->process("[:specify_a_state:]"));
			}
		}else{
			$this->Notifier->error($this->Interpreter->process("[:specify_a_User_id:]"));
		}
		if(!$this->Xpagin->isExecuter){
			$this->redirect("/admin/users/index");
		}
	}

	function admin_dashboard() {

	}

	function login(){
		$this->layout="login";
		if(!empty($this->data)){
			if($this->Auth->login()){
				$this->redirect($this->Auth->loginRedirect);
			}else{
				$this->Notifier->error('[:username_or_password_incorrect:]');
				$this->User->invalidate('username'," ");
				$this->User->invalidate('password'," ");
				unset($this->data['User']['password']);
			}
		}
	}

	function logout(){
		$this->Session->setFlash('Good-Bye');
		$this->redirect($this->Auth->logout());
	}

	function _permit($group_id,$action = "update"){
		return !Configure::read('Acl.active') || $this->Acl->check($this->Auth->user(),array('model' => 'Group','foreign_key' => $group_id),$action);
	}
}
?>