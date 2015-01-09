<?php
class Lang extends I18nAppModel {
	var $name="Lang";
	var $useTable="lags";
	var $validate=array(
		'locale'=>array('rule'=>array('locale'=>'/[a-z]{2}_[A-Z]{2}$/','required'=>true,'allowEmpty'=>false,'message'=>'[:write_a_correct_code:]')),
		'description'=>array('rule'=>'notEmpty','message'=>'[:required_field:]')
	);
	var $hasMany=array('I18n.KeyMeaning');
}
?>