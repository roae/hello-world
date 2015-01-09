<?php
class KeyMeaning extends I18nAppModel {

	var $name="KeyMeaning";
	var $useTable="key_meanings";
	var $belongsTo=array("Key"=>array('className'=>"I18n.Key"));

}
?>