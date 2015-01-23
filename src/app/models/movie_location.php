<?php
/**
 * Class MovieLocation
 * @property $Location Location
 * @property $Movie Movie
 */
class MovieLocation extends AppModel {

	var $name = "MovieLocation";

	var $belongsTo = array('Movie','Location');
	var $hasOne = array( );
	var $hasAndBelongsToMany = array();
	var $hasMany = array();

	var $actsAs = array();

	var $validate = array(
		'location_id'=>array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
		),
		'premiere_end'=>array(
			#'requerido'=>array('rule'=>'notEmpty','required'=>true,'allowEmpty'=>false,'message'=>'[:required_field:]'),
			'fecha'=>array('rule'=>array('date','ymd'),'message'=>'[:invalid_date:]')
		),
		'presale_start'=>array(
			#'fecha'=>array('rule'=>array('date','ymd'),'message'=>'[:invalid_date:]'),
			'requerido'=>array('rule'=>'presaleDate'),
		),
		'presale_end'=>array(
			#'fecha'=>array('rule'=>array('date','ymd'),'message'=>'[:invalid_date:]'),
			'requerido'=>array('rule'=>'presaleDate'),
		)

	);

	function presaleDate(&$check){
		$passed = true;
		$field = array_keys($check);
		$field = $field[0];
		#pr($this->data);
		if($this->data['MovieLocation']['presale']){
			if(empty($this->data['MovieLocation'][$field])){
				$passed = "[:required_field:]";
			}
		}

		return $passed;
	}

}
?>