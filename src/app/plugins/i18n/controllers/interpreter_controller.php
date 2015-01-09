<?php

class InterpreterController extends I18nAppController{

	var $name = "Interpreter";
	var $uses = array();
	var $components = array('I18n.Interpreter', 'Session');

	function start(){
		if(!isset($this->params['requested'])){
			$this->cakeError('error404');
		}
		ob_start();
	}

	function end(){
		if(!isset($this->params['requested'])){
			$this->cakeError('error404');
		}
		return $this->Interpreter->process(ob_get_clean());
	}

}

?>
