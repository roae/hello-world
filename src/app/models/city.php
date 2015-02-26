<?php
/**
 * Class City
 * @property $Location Location
 */
class City extends AppModel {

	var $name = "City";
	var $useTable = "cities";

	var $belongsTo = array( );
	var $hasOne = array( );
	var $hasAndBelongsToMay = array( );
	var $hasMany = array( 'Location' );
	var $displayField="name";

	var $validate = array(
		'name'=>array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
		)
	);

	function beforeSave() {
		$this->data['City']['slug'] = Inflector::slug(low($this->data['City']['name']), '-');

		return true;
	}

}
?>