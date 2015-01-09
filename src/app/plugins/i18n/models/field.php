<?php
/**
 * Definicion del modelo Field
 * maneja la tabla que tiene el contenido de los campos que estan en varios idiomas
 */
class Field extends I18nAppModel {
	/**
	 * Nombre de este modelo
	 * @var string
	 */
	var $name = 'Field';
	/**
	 * nombre dela tabla que usa este modelo sin el prefijo
	 * @var string
	 */
	var $useTable = 'fields';
	
	var $displayField = 'field';
}
?>