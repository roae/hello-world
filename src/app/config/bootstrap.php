<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php
 *
 * This is an application wide file to load any function that is not used within a class
 * define. You can also use this to include or require any files in your application.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * App::build(array(
 *     'plugins' => array('/full/path/to/plugins/', '/next/full/path/to/plugins/'),
 *     'models' =>  array('/full/path/to/models/', '/next/full/path/to/models/'),
 *     'views' => array('/full/path/to/views/', '/next/full/path/to/views/'),
 *     'controllers' => array('/full/path/to/controllers/', '/next/full/path/to/controllers/'),
 *     'datasources' => array('/full/path/to/datasources/', '/next/full/path/to/datasources/'),
 *     'behaviors' => array('/full/path/to/behaviors/', '/next/full/path/to/behaviors/'),
 *     'components' => array('/full/path/to/components/', '/next/full/path/to/components/'),
 *     'helpers' => array('/full/path/to/helpers/', '/next/full/path/to/helpers/'),
 *     'vendors' => array('/full/path/to/vendors/', '/next/full/path/to/vendors/'),
 *     'shells' => array('/full/path/to/shells/', '/next/full/path/to/shells/'),
 *     'locales' => array('/full/path/to/locale/', '/next/full/path/to/locale/')
 * ));
 *
 */

/**
 * As of 1.3, additional rules for the inflector are added below
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */
if (!class_exists('Folder')) {
	require_once LIBS . 'folder.php';
}

ini_set('session.cookie_domain', env('HTTP_HOST'));

/*
 * Carga todos los archivos bootstrap de los plugins
 */

$f = new Folder(APP . 'plugins');
$files = $f->read();
foreach($files[0] as $file)
{
	if(file_exists(APP . 'plugins' . DS . $file . DS . 'config' . DS . 'bootstrap.php'))
	{
		require_once APP . 'plugins' . DS . $file . DS . 'config' . DS . 'bootstrap.php';
	}
}

function setTimezoneByOffset($offset){

	$offset = (idate("I")? $offset+1 : $offset);# Si es horario de verano se le aumenta una hora

	$testTimestamp = time();
	date_default_timezone_set('UTC');
	$testLocaltime = localtime($testTimestamp,true);
	$testHour = $testLocaltime['tm_hour'];

	$abbrarray = timezone_abbreviations_list();
	foreach ($abbrarray as $abbr){
		//echo $abbr."<br>";
		foreach ($abbr as $city){
			#pr(strlen($city['timezone_id']));
			if(strlen($city['timezone_id'])){
				date_default_timezone_set($city['timezone_id']);
				$testLocaltime     = localtime($testTimestamp,true);
				$hour                     = $testLocaltime['tm_hour'];
				$testOffset =  $hour - $testHour;
				if($testOffset == $offset){
					return true;
				}
			}

		}
	}
	return false;
}

setTimezoneByOffset(-7);
//pr(timezone_abbreviations_list());

?>