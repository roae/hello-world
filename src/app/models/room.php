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

	/**
	 * Pone en el formato correcto el campo room_type
	 * @return bool|void
	 */
	function beforeSave(){
		if(isset($this->data['Room']['room_type']) && !empty($this->data['Room']['room_type'])){
			$this->data['Room']['room_type'] = implode("|",$this->data['Room']['room_type']);
		}
		return true;
	}

	/**
	 * Modifica el valor de room_type en la tabla shows
	 * @return bool|void
	 */
	function afterSave(){
		#pr($this->data);
		$this->Location->Show->updateAll(array(
			'Show.room_type'=>"'".$this->data['Room']['room_type']."'",
		),array(
			'Show.location_id'=>$this->data['Room']['location_id'],
			'Show.screen_name'=>$this->data['Room']['description'],
		));
		return true;
	}

}
?>