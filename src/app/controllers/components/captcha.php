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
		 * Shared secret for the site.
		 * @var type string
		 */
		public $secret;

		/**
		 * Method used to communicate  with service. Defaults to POST request.
		 * @var RequestMethod
		 */
		private $requestMethod;


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

		function reCaptcha($response,$remoteIp = null){
			$peer_key = version_compare(PHP_VERSION, '5.6.0', '<') ? 'CN_name' : 'peer_name';
			$params = array(
				'secret'=>$this->secret,
				'response'=>$response,
				'remoteIp'=>$remoteIp,
				'version'=>'php_1.1.1'
			);

			$options = array(
				'http' => array(
					'header' => "Content-type: application/x-www-form-urlencoded\r\n",
					'method' => 'POST',
					'content' => http_build_query($params),
					// Force the peer to validate (not needed in 5.6.0+, but still works
					'verify_peer' => true,
					// Force the peer validation to use www.google.com
					$peer_key => 'www.google.com',
				),
			);
			$context = stream_context_create($options);
			$responseData = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context));
			return $responseData->success;
		}

	}
?>