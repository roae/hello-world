<?php

class TemplateHelper extends AppHelper{

	var $values;

	function make($template, $values){
		$this->values=$values;
		return preg_replace_callback('/\[(?<key>[a-zA-Z0-9_\-]+)\]/',array($this,'__replace_callback'),$template);
	}

	function __replace_callback($matches){
		return isset($this->values[$matches['key']]) ? $this->values[$matches['key']] : $matches[0];
	}

}

?>