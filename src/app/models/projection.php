<?php
/**
 * Class Projection
 * @property $Movie Movie
 */
class Projection extends AppModel {

	var $name = "Projection";

	var $belongsTo = array(
		"Movie",
	);
	var $hasOne = array( );
	var $hasAndBelongsToMany = array( );
	var $hasMany = array(  );
	var $displayField = "vista_code";
	var $actsAs = array( );

	var $validate = array(
		'vista_code'=>array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
		),
		'lang'=>array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
		),
		'format'=>array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
		),
	);

}
?>