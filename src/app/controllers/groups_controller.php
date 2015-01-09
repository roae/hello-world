<?php
class GroupsController extends AppController {

	var $name = "Groups";

	var $ribbonBar=array(
		'title'=>'{image:img} [:admin_security_title:]',
		'options'=>array(
			'image'=>array('src'=>'admin/ribbon_security.png','alt'=>'Securirty')
		),
		'links'=>array()
	);

	function admin_index () {
		$this->set("groups",$this->Group->find("all"));
	}

	function admin_add(){
		if(!empty($this->data)){
			if($this->Group->save($this->data)){
				$this->Acl->deny($this->Group,$this->Group);
				$this->Notifier->success('[:admin-grupos-add-success:]');
				$this->redirect(array('action' => 'index'));
			}else{
				$this->Notifier->error('[:admin-grupos-add-error:]');
			}
		}
		$this->set('parents',$this->Group->find('list',array('order' => array('Group.name' => 'ASC'))));
	}


	function admin_edit ( $id ) {

	}


	function admin_delete ( $id ) {
			}


	function index () {

	}


}
?>