<?php

/**
 * Key class
 * Modelo de la tabla keys que guarda las claves de edición
 *
 */
class Key extends I18nAppModel {
	/**
	 * Nombre de este modelo
	 * @var string
	 */
	var $name="Key";
	/**
	 * nombre dela tabla que usa este modelo sin el prefijo
	 * @var string
	 */
	var $useTable="keys";

	/**
	 * Guarda los modelos con los que este modelo tiene relacion hasMany
	 * @var array
	 */
	var $hasMany=array('KeyMeaning'=>array('className'=>'I18n.KeyMeaning'));

	/**
	 * Regla de validaciones para los campos del modelo
	 * @var array
	 */
	var $validates = array(
		'key' => array('rule' => array('key', '/[a-z0-9]$/i'),'required' => true,'allowEmpty' => false)
	);

	/**
	 * Esta funcion se encarga de interpretar cadenas, es decir, cambiar las claves que existan en la cadena pasada como parametro ($output) por el contenido de estas
	 * @param string $output contenido que se interpretara, este contiene las claves de edicion las cuales se remplazaran por su contenido
	 * @param string $locale id del idioma
	 * @access public
	 * @return string $output
	 */
	function interpret( $output , $locale=null ) {
		if(!$locale){
			$locale = Configure::read("I18n.Locale");
		}
		preg_match_all('/\[:([0-9a-zA-Z_\-]+):\]/',$output,$keys);#se obtienen todas las claves de edicion que van en la salida

		$keys=array_unique($keys[1]);#se quitan las claves repetidas
		
		$contentKeys=$this->find("all",array(
			'fields'=>array('Key.id','Key.key','KeyMeaning.content'),
			'joins'=>array(
				array(
					'table'=>$this->KeyMeaning->tablePrefix.$this->KeyMeaning->table,
					'alias'=>$this->KeyMeaning->alias,
					'conditions'=> 'Key.id = '.$this->KeyMeaning->alias.'.key_id'
				)
			),
			'conditions'=>array('KeyMeaning.lang_id'=>$locale,'BINARY Key.key IN ("'.implode('", "',$keys).'")')
		));

		$foundedKeys=$this->__replaceKeys($output,$contentKeys);# se remplazan las clavez de edicion por el contenido encontrado en query

		return $output;
	}

	/**
	 * Cambia las claves de la respuesta por el contenido de las mismas, obtenido en el find
	 * @param String $output referencia a la salida
	 * @param array $keys arreglo con el contenido de las clavez encontradas en el find
	 */

	function __replaceKeys( &$output, $keys ) {
		$foundedKeys=array();
		foreach($keys as $key){
			$content=preg_replace('/\$([0-9])/','\\\$$1',$key['KeyMeaning']['content']); # se limpia el contenido de la clave
			$output=preg_replace('/\[:'.$key['Key']['key'].':\]/',$content,$output);
		}
		return $foundedKeys;
	}

	/**
	 * Trae las clavez de urls y el contenido
	 * @return array arraglo con las clavez de url y el contenido
	 */
	function getUrls(){
		
		$keys=$this->find("all",array(
			'fields'=>array('Key.key','KeyMeaning.content','KeyMeaning.lang_id'),
			'joins'=>array(
				array(
					'table'=>$this->KeyMeaning->tablePrefix.$this->KeyMeaning->table,
					'alias'=>$this->KeyMeaning->alias,
					'conditions'=>array(
						'Key.id = KeyMeaning.key_id'
					)
				)
			),
			'conditions'=>array(
				'Key.key like'=>'%_url',
				#'KeyMeaning.lang_id'=>Configure::read('I18n.Locale')
			)
		));
		
		$urls=array();
		foreach($keys as $key){
			$urls[$key['KeyMeaning']['lang_id']][$key['Key']['key']]=$key['KeyMeaning']['content'];
		}
		Configure::write("I18n.urls",$urls);

		return $urls;

	}
	

}

?>