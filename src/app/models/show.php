<?php
/**
 * Class Show
 *
 * @property $Location Location
 * @property $Projection Projection
 * @property $Movie Movie
 */
class Show extends AppModel{
	var $name = "Show";
	var $belongsTo = array(
		'Projection',
		'Location',
		'Movie'
	);

	var $hasMany = array(
		'TicketPrice'=>array(
			'dependent'=>true
		)
	);

	var $virtualFields = array(
		'date'=>'Date(Show.schedule)'
	);

	var $validate = array(
		'location_id' => array(
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]'),
		),
		'movie_id' => array(
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]'),
		),
		'projection_id' => array(
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]'),
		),
		'schedule' => array(
			'requerido' => array( 'rule' => 'notEmpty', 'required' => true, 'allowEmpty' => false, 'message' => '[:required_field:]'),
			'Fecha' => array( 'rule' => '/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}$/i', 'required' => true, 'allowEmpty' => false, 'message' => '[:invalid_date:]'),
		),
	);
}
?>