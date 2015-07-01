<?php
/**
 * Class Service
 * @property $Location Location
 */
class Service extends AppModel {

	var $name = "Service";

	var $belongsTo = array( );
	var $hasOne = array( );
	var $hasAndBelongsToMany = array(
		"Location"
	);
	var $hasMany = array(  );
	var $displayField="name";

	var $actsAs = array(
		'Media.Uploader' => array(
			'Icon' => array(
				/*'copies' => array(
					'snipped' => array('width' => 100,'height'=>100,'image_ratio_crop' => true),
					'mini' => array('width' => 75,'height'=>75,'image_ratio_crop' => true),
					'medium' => array('width' => 280,'height'=>135,'image_ratio_crop' => true),
					'big' => array('width' => 775,'image_ratio_crop' => false,'image_ratio_y'=>true),
				),*/
				'limit' => 1,
				'required' => false,
				'width'=>42,
				'height'=>42,
				#'image_ratio_crop' => true,
				'allowed' => array('images'),
				'max_file_size'=>2,// MB
			),
			'Gallery'=>array(
				'copies' => array(
					'mini' => array('width' => 75,'height'=>75,'image_ratio_crop' => true),
					'big' => array('width' => 775,'image_ratio_crop' => false,'image_ratio_y'=>true),
				),
				'limit' => 10,
				'required' => false,
				#'image_ratio_crop' => true,
				'allowed' => array('images'),
				'max_file_size'=>2,// MB
			)
		)
	);

	var $validate = array(
		'name'=>array(
			'unico'=>array('rule'=>array('isUnique','name'),'message'=>'[:service_already_existe:]'),
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
		)
	);

}
?>