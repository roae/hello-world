<?php
class BuysController extends AppController{
	var $name = "Buys";
	var $uses = array(
		"Buy"
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
			$route = Router::parse($this->referer());
			$this->set("showMessage",$route['controller'] == "shows");
			$this->set("referer",$this->referer());
			$this->set("record",$record);

			$this->Email->reset();
			$this->Email->to = $record['Buy']['email'];
			$this->Email->from = "erochin@h1webstudio.com";
			$this->Email->subject = "Confirmación de compra";
			$this->Email->sendAs = 'html';
			//$this->set("data",$this->errors);
			$this->Email->template = "buy_confirm";

			/* Opciones SMTP*/
			$this->Email->smtpOptions = array(
				'port'=>'25',
				'timeout'=>'30',
				'host' => 'mail.h1webstudio.com',
				'username'=>'erochin@h1webstudio.com',
				'password'=>'Rochin12!-');

			$this->Email->delivery = 'smtp';
			/**/
			$this->Email->send();

			//pr(print_r($this->Email->smtpError));



		}else{
			$this->cakeError('error404');
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