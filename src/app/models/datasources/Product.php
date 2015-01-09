<?php

class Category extends AppModel {

	var $name = "Product";
	var $useTable = "ProductTypes";
	var $actsAs = array( 'Tree' );
	var $belongsTo = array( );
	var $hasOne = array( );
	var $hasAndBelongsToMany = array( );
	var $hasMany = array( "Question" );
	var $validate = array(
		'name_en_us' => array(
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:requiered_field:]' )
		),
		'name_es_mx' => array(
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:requiered_field:]' )
		)
	);

}

?>