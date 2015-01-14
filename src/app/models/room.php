<?php
/**
 * Class Room
 * @property $Location Location
 */
class Room extends AppModel {

	var $name = "Room";

	var $belongsTo = array(
		"Location"
	);
	var $hasOne = array( );
	var $hasAndBelongsToMay = array( );
	var $hasMany = array(  );
	var $displayField="description";

	var $validate = array(
		'description'=>array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
		)
	);

}
?>