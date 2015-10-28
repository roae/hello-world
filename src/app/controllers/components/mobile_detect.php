<?php
	/**
	 * Class MobileDetectComponent
	 *
	 * Componente para indentificar dispositivos mobiles usando la clase Mobile_Detect
	 * https://github.com/serbanghita/Mobile-Detect
	 * PHP version5
	 *
	 * @author Efrain Rochin Aramburo
	 * @date 23 Oct 2015
	 */
	class MobileDetectComponent extends Object {
		/**
		 * @var string
		 */
		var $name = "MobileDetect";

		/**
		* Estado de la clase Mobile_Detect
		* @var bool
		*/
		var $loaded = false;

		/**
		* Objeto MobileDetect
		* @var object
		*/
		var $MobileDetect = null;

		/**
		 * Carga la clase Mobile_Detect, ejecuta el metodo de la clase.
		 * Se usa el metoso 'isMobile' si no se especifica uno.
		 *
		 * @param string $method El metodo que se ejecutara
		 * @param string $args Argumentos opcionales para el metodo especificado.
		 * @return mixed
		 * @throws CakeException
		 */

		function detect($method = "isMobile", $args = null){
			if (!class_exists('Mobile_Detect')) {
				// Se carga el Vendor Mobile_Detect.php si aún no se carga
				$loaded = App::import('Vendor', 'MobileDetect', array( 'file' => 'Mobile_Detect.php')
				);
				// Aborta si el vendor no se puede cargar o no se encontró.
				if (!$loaded) {
					throw new CakeException('Mobile_Detect is missing or could not be loaded.');
				}
			}
			// Se instancia el objeto solo una vez en la llamada
			if (!($this->MobileDetect instanceof Mobile_Detect)) {
				$this->MobileDetect = new Mobile_Detect();
			}
			return $this->MobileDetect->{$method}($args);
		}

	}
?>