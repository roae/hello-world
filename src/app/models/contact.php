<?php
class Contact extends AppModel{

	var $name = "Contact";
	var $useTable = "contacts";
	var $actsAs = array();
	var $belongsTo = array();
	var $hasOne = array();
	var $hasAndBelongsToMay = array();
	var $hasMany = array();
	var $validate = array(
		'name'=>array(
			'requerido'=>array('rule'=>'notEmpty','required'=>true,'allowEmpty'=>false,'message'=>'[:name_required:]')
		),
		'email'=>array(
			'requerido'=>array('rule'=>'notEmpty','required'=>true,'allowEmpty'=>false,'message'=>'[:email_required:]'),
			'valid_mail'=>array('rule'=>'email','message'=>'[:invalid_email:]')
		),
		'message'=>array(
			'requerido'=>array( 'rule'=>'notEmpty','required'=>true,'allowEmpty'=>false,'message'=>'[:message_required:]' )
		)
	);

}
?>