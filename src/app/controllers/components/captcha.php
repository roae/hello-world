<?php
	/**
	 * Class CaptchaComponent
	 *
	 * Control de captcha
	 * @author Efrain Rochin Aramburo
	 * @date 10 Marzo 2011
	 */
	class CaptchaComponent extends Object {
		/**
		 * @var string
		 */
		var $name="Captcha";

		/**
		 * @var array
		 */
		var $components=array('Session');

		/**
		 * @var string
		 */
		var $sessionName='captcha';

		/**
		 * @param $session
		 */
		function image($session){
			App::import('Vendor','Captcha',array('file'=>'captcha'.DS.'captcha.php'));
			$captcha=new Captcha();
			$captcha->session_var=$this->sessionName.$session;
			$captcha->CreateImage();
		}

		/**
		 * @param $val
		 * @param $session
		 *
		 * @return bool
		 */
		function check($val,$session){
			return $this->Session->read($this->sessionName.$session)==$val;
		}
	}
?>