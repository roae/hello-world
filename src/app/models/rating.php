<?php
class Rating extends AppModel {

    var $name = 'Rating';
	var $useTable = "ratings";

    var $actsAs = array('Polymorphic');


}
?>