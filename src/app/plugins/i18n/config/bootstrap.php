<?php
/**
 * Configuracion del plugin I18n
 */
$i18nDefaultConfig=array(
		'Langs'=>array(
			'en_us'=>'english',
			'es_mx'=>'spanish',
		),
		'Domains'=>array(
			'en_us'=>'www.domain.com',
			'es_mx'=>'es.domain.com',
		),
		'Locale'=>'es_mx', # Idioma default
		'humanize'=>false,
		'sessionName'=>'L10nActivated',
		'Interpreter'=>array(
			'active'=>true,
		),
		'L10n'=>array(
			'allow'=>false, # Indica si el usuario puede editar el contenido
			'active'=>false, # Indica que esta activa la edicion del contenido
		)
);
Configure::write('I18n', am($i18nDefaultConfig,Configure::read("I18n")));