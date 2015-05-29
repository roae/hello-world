<?php
class BuySeat extends AppModel{
	var $name = "BuySeat";

	var $useTable = "buy_seats";

	var $belongsTo = array('Buy');
}
?>