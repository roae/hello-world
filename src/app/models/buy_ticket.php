<?php
class BuyTicket extends AppModel{
	var $name = "BuyTicket";

	var $useTable = "buy_tickets";

	var $belongsTo = array('Buy');
}
?>