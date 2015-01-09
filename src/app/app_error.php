<?php
	class AppError extends ErrorHandler{

		var $components=array('Interpreter');

		function _outputMessage($template){
			$this->controller->set(array('home' => false));
			return parent::_outputMessage($template);
		}
	}
?>