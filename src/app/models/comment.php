<?php
class Comment extends AppModel {

    var $name = 'Comment';
	var $useTable = "comments";

    var $actsAs = array('Polymorphic','Tree');
	var $validate=array(
		'nombre'=>array(
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