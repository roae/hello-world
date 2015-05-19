<?php
class SocialAuth extends AppModel{
	var $name = "SocialAuth";
	var $useTable = "social_auths";
	var $belongsTo = array(
		'User',
	);

}
?>