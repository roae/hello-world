<?php
class User extends AppModel {

	var $name = "User";
	var $useTable = "users";
	var $actsAs = array('Acl.Aclx'=>array('Aro','Aco'));
	#var $actsAs=array('Acl'=>array('type'=>'requester'));
	var $belongsTo = array( 'Acl.Group' );
	var $hasOne = array(
		'Profile'
	);
	var $hasAndBelongsToMany = array();
	var $hasMany = array(
		'SocialAuth',
		'Buy'=>array(
			'className'=>'Buy',
			'foreignKey'=>'buyer',
		)
	);

	var $_data = array();

	var $validate = array(
		'nombre'=>array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
		),
		'paterno'=>array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
		),
		'username'   => array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
			"unique"=>array("rule"=>array("isUnique",'username'),"message"=>"[:user_exist:]")),
		'password'   => array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
			'length' => array('rule' => array('minLength', 6),'message' => '[:valid_lenght_password:]')
		),
		'email'=>array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
			'mail' => array('rule' => 'email','message' => '[:valid_email:]'),
		),
		'group_id'	=> array(
			'requerido'=>  array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]' ),
		),
		'password_confirm' => array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
			'confirm' => array('rule' => 'confirm','required' => true,'allowEmpty' => false,'message' => '[:error-confirm-password-not-match:]')
		)
	);

	function confirm(){
		return $this->data[$this->alias]['password'] == Security::hash($this->data[$this->alias]['password_confirm'],'sha1',true);
	}

	function parentNode($type) {
		if ($type == 'Aro') {
			if (!$this->id) { return null; }
			$data = $this->read();
			return (!$data['User']['group_id'])? null : array('model' => 'Group', 'foreign_key' => $data[$this->alias]['group_id']);
		}
		return false;
	}
}
?>