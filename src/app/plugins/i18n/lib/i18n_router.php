<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

App::import('Model','I18n.Key');

class I18nRouter{

	/**
	 * Guarda las clavez de urls
	 * @var Array
	 */
	var $__urlsKey;

	/**
	 * Construcctor de I18nRouter
	 * llena $__routes y crea la instancia del modelo que se encarga de interpretar las rutas
	 * @access public
	 * @return void
	 */
	function I18nRouter(){
		$this->__setLocale();
		$this->__resortLangs();
		$key =new Key();
		$this->__urlsKey=$key->getUrls();
	}

	/**
	 * Obtiene una referencia ala instancia del objeto I18nRouter
	 *
	 * @return I18nRouter Instancia de I18nRouter.
	 * @access public
	 * @static
	 */
	function &getInstance() {
		static $instance = array();

		if (!$instance) {
			$instance[0] =& new I18nRouter();
		}
		return $instance[0];
	}

	/**
	 * Asigna el idioma en que se mostrara la página para
	 */
	function __setLocale(){
		$domains=Configure::read("I18n.Domains");
		if(($key=array_search(env('HTTP_HOST'),$domains))!==false){
			Configure::write("I18n.Locale",$key);
		}
	}

	/**
	 *
	 */
	function __resortLangs(){
		$langs=Configure::read('I18n.Langs');
		$locale=Configure::read("I18n.Locale");
		$ordered[$locale]=$langs[$locale];
		Configure::write("I18n.Langs",am($ordered,array_diff($langs,$ordered)));
	}

	/**
	 * Conecta una nueva ruta interpretada al enrutador
	 *
	 *
	 * @param string $route
	 * @param array $defaults
	 * @param array $options
	 */

	function connect($route, $defaults=array(), $options=array()){
		$self= I18nRouter::getInstance();
		$route=$self->interpretUrl($route);
		//pr($route);
		Router::connect($route,$defaults,$options);
	}

	function interpretUrl($url,$locale=null){
		$self= I18nRouter::getInstance();
		#pr($url);
		preg_match_all('/\[([0-9a-zA-Z_\-]+)\]/',$url,$keys);#se obtienen todas las claves de edicion que van en la ruta
		$keys=array_unique($keys[1]);#se quitan las claves repetidas
		//pr($url);
		if(empty($locale)){
			$locale=Configure::read('I18n.Locale');
		}
		
		foreach($keys as $key){
			if(isset($self->__urlsKey[$locale][$key])){
				$url=preg_replace('/\['.$key.'\]/',$self->__urlsKey[$locale][$key],$url);
			}
		}
		//pr($url);
		return $url;
	}
	
	/**
	 * Retorna la url con las clavez de edicion con la que se forma
	 * return string url con clavez
	 */
	
	function getUninterpreterUrl($url,$locale=false){
		$self= I18nRouter::getInstance();
		
		if(!$locale){
			$locale=Configure::read('I18n.Locale');
		}
		$pieces=explode('/',$url);
		$url=array();
		foreach($pieces as $piece){
			if(isset($self->__urlsKey[$locale]) &&  $key=array_search($piece, $self->__urlsKey[$locale])){
				$url[]="[:$key:]";
			}else{
				$url[]=$piece;
			}
		}
		return implode('/',$url);
	}
	
	/**
	 * Cambia el locale de la url
	 * @param string $url
	 * @param string $locale 
	 * @return string retorna el nuevo locale
	 */
	
	function chgLocaleUrl($url,$locale){
		$self=I18nRouter::getInstance();
		return $self->interpretUrl($self->getUninterpreterUrl($url),$locale);
	}
	
}
?>