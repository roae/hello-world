<?php
class BuysController extends AppController{
	var $name = "Buys";
	var $uses = array(
		"Buy"
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
				'BuyTicket'
			));
			$this->Buy->id = $id;
			$record = $this->Buy->read();
			$this->set("record",$record);
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