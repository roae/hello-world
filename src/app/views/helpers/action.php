<?php
class ActionHelper extends AppHelper {

	var $helpers=array('Form','Html');
	var $defaultUrl;

	function setdefaultUrl($url){
		$this->defaultUrl=$url;
	}

	function getDefaultUrl(){
		return $this->Form->hidden("_Action.url",array('value'=>$this->defaultUrl));
	}

}
?>