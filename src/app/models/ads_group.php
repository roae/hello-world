<?php
/**
 * Class AdsGroup
 * @property $Ads Ads
 */
class AdsGroup extends AppModel {

	var $name = "AdsGroup";
	var $useTable = "ads_groups";

	var $belongsTo = array( );
	var $hasOne = array( );
	var $hasAndBelongsToMay = array( );
	var $hasMany = array( 'Ads' );
	var $displayField = "name";

	var $validate = array(
		'name'=>array(
			'unico'=>array('rule'=>array('isUnique','name'),'message'=>'[:ads_group_already_existe:]'),
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
		)
	);

}
?>