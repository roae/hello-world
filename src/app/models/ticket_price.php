<?php
/**
 * Class TicketPrice
 *
 * @property $Show Show
 */
class TicketPrice extends AppModel{
	var $name = "TicketPrice";
	var $belongsTo = array(
		'Show',
	);

	var $validate = array(

	);
}
?>