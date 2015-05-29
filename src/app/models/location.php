<?php
/**
 * Class Location
 * @property $Service Service
 * @property $City City
 */
class Location extends AppModel {

	var $name = "Location";

	var $belongsTo = array(
		'City',
	);
	var $hasOne = array( );
	var $hasAndBelongsToMany = array(
		"Service",
		#'Movie',
	);
	var $hasMany = array(
		'Show',
		'Buy',
	);
	var $displayField="name";

	var $actsAs = array(
		'Media.Uploader' => array(
			'Gallery' => array(
				#'resize' => true,
				'copies' => array(
					'snipped' => array('width' => 100,'height'=>100,'image_ratio_crop' => true),
					'mini' => array('width' => 75,'height'=>75,'image_ratio_crop' => true),
					'medium' => array('width' => 280,'height'=>135,'image_ratio_crop' => true),
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
			'unico'=>array('rule'=>array('isUnique','name'),'message'=>'[:location_already_existe:]'),
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
		),
		'phone_numbers'=>array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
		),
		'city_id'=>array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
		),
		'vista_code'=>array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
		),
		'vista_service_url'=>array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
		),
		'street'=>array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
		),
		'outside'=>array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
		),
		'state'=>array(
			'requerido' => array('rule' =>'notEmpty','required' => true,'allowEmpty' => false,'message' => '[:required_field:]'),
		),
	);

	var $publicFields = array(
		'id',
		'name',
		'city_id',
		'phone_numbers',
		'state',
		'zip',
		'street',
		'neighborhood',
		'interior',
		'outside',
		'mark_lat',
		'mark_lng',
		'map_lat',
		'map_lng',
		'mark_lat',
		'map_zoom',
		'mark_lat',
		'sv_lat',
		'sv_lng',
		'description',
	);

}
?>