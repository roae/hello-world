<?php
Class Ad extends AppModel{
	var $name = "Ad";
	var $uses = "ads";
	var $belongsTo = array(
		'AdsGroup',
	);
	var $actsAs = array(
		'Media.Uploader' => array(
			'Horizontal' => array(
				/*'copies' => array(
					'mini' => array('width' => 95,'height'=>136,'image_ratio_crop' => true),
					'medium' => array('width' => 190,'height'=>272,'image_ratio_crop' => true),
					'big' => array('width' => 280,'height'=>544,'image_ratio_crop' => true,),
				),*/
				'limit' => 1,
				'required' => false,
				'width'=>960,
				'height'=>220,
				'image_ratio_crop' => true,
				'allowed' => array('images'),
				'max_file_size'=>2,// MB
			),
			'Vertical'=>array(
				/*'copies' => array(
					'mini' => array('width' => 75,'height'=>75,'image_ratio_crop' => true),
					'big' => array('width' => 775,'image_ratio_crop' => false,'image_ratio_y'=>true),
				),*/
				'limit' => 1,
				'height'=>620,
				'width'=>270,
				'required' => false,
				'image_ratio_crop' => true,
				'allowed' => array('images'),
				'max_file_size'=>2,// MB
			),
			'VerticalMini'=>array(
				/*'copies' => array(
					'mini' => array('width' => 75,'height'=>75,'image_ratio_crop' => true),
					'big' => array('width' => 775,'image_ratio_crop' => false,'image_ratio_y'=>true),
				),*/
				'limit' => 1,
				'height'=>380,
				'width'=>270,
				'required' => false,
				'image_ratio_crop' => true,
				'allowed' => array('images'),
				'max_file_size'=>2,// MB
			),
			'Cuadro'=>array(
				/*'copies' => array(
					'mini' => array('width' => 75,'height'=>75,'image_ratio_crop' => true),
					'big' => array('width' => 775,'image_ratio_crop' => false,'image_ratio_y'=>true),
				),*/
				'height'=>270,
				'width'=>270,
				'limit' => 1,
				'required' => false,
				'image_ratio_crop' => true,
				'allowed' => array('images'),
				'max_file_size'=>2,// MB
			)
		)
	);

}
?>