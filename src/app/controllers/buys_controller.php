<?php
class BuysController extends AppController{
	var $name = "Buys";
	var $uses = array(
		"Buy"
	);

	var $helpers = array(
		'Number',
	);

	var $components = array(
		'Email'
	);

	function view($id = null){
		if($id){
			$this->Buy->contain(array(
				'Movie'=>array(
					'Poster',
					/*'Gallery'=>array(
						'limit'=>1
					)*/
				),
				'Location'=>array(
					'City'
				),
				'Projection',
				'BuySeat',
				'BuyTicket',
				'Buyer'=>array(
					'Profile'
				)
			));
			$this->Buy->id = $id;
			$record = $this->Buy->read();
			if(empty($record)){
				$this->cakeError("error404");
			}
			$route = Router::parse($this->referer());
			$this->set("showMessage",$route['controller'] == "shows");
			$this->set("referer",$this->referer());
			$this->set("record",$record);

			if(isset($this->params['named']['print'])){
				$this->layout = 'email/html/default';
				return $this->render('/elements/email/html/buy_confirm');
			}

			if($route['controller'] == "shows"){
				$this->__sendBuyConfirmation($record);
			}
		}else{
			$this->cakeError('error404');
		}
	}

	function __sendBuyConfirmation($record){
		$this->log("Enviando Confirmacion BuysContoller","SmartConnector");
		$this->Email->reset();
		$this->Email->to = $record['Buy']['email'];
		$this->Email->from = "Citicinemas Móvil<noreply@citicinemas.com>";
		$this->Email->bcc = explode(",",Configure::read("AppConfig.buy_bcc_confirmation"));
		$this->Email->subject = "Confirmación de compra";
		$this->Email->sendAs = 'html';
		#$this->set("record",$record);
		$this->Email->template = "buy_confirm";
		#pr(explode(",",Configure::read("AppConfig.buy_bcc_confirmation")));
		/* Opciones SMTP *
		$this->Email->smtpOptions = array(
			'port'=>'25',
			'timeout'=>'30',
			'host' => 'mail.h1webstudio.com',
			'username'=>'erochin@h1webstudio.com',
			'password'=>'Rochin12!-');

		$this->Email->delivery = 'smtp';
		/**/
		if($this->Email->send()){
			$this->log("Email de confirmación enviada con exito al email ".$this->data['Buy']['email'],"SmartConnector");
		}else{
			$this->log("Error al enviar el correo de confirmación: ".$this->Email->smtpError,"SmartConnector");
		}
	}

	function get(){

	}

	function barcode($text){
		#App::import('Vendor','BarcodeFont',array('file'=>'barcode'.DS.'BCGFontFile.php'));
		#App::import('Vendor','BarcodeColor',array('file'=>'barcode'.DS.'BCGColor.php'));
		App::import('Vendor','BarcodeDrawing',array('file'=>'barcode'.DS.'BCGDrawing.php'));
		App::import('Vendor','Barcode128',array('file'=>'barcode'.DS.'BCGcode128.barcode.php'));
		#pr(VENDORS.'barcode'.DS.'Arial.ttf');

		#$font = new BCGFontFile(VENDORS.'barcode'.DS.'Arial.ttf', 18);
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255);
		$drawException = null;
		try {
			$code = new BCGcode128();
			$code->setScale(1); // Resolution
			$code->setThickness(30); // Thickness
			$code->setForegroundColor($color_black); // Color of bars
			$code->setBackgroundColor($color_white); // Color of spaces
			#$code->setFont($font); // Font (or 0)
			$code->parse($text); // Text
		} catch(Exception $exception) {
			$drawException = $exception;
		}

		/* Here is the list of the arguments
		1 - Filename (empty : display on screen)
		2 - Background color */
		$drawing = new BCGDrawing('', $color_white);
		if($drawException) {
			$drawing->drawException($drawException);
		} else {
			$drawing->setBarcode($code);
			$drawing->draw();
		}

		// Header that says it is an image (remove it if you save the barcode to a file)
		header('Content-Type: image/png');
		header('Content-Disposition: inline; filename="barcode.png"');

		// Draw (or save) the image into PNG format.
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
	}

}
?>