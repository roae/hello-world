<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
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
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */

App::import( "Lib", "I18n.I18nRouter", true, array(), 'i18n_router.php' );

#App::import('Lib', 'routes/SubDomainPrefixRoute');
#Router::connect('*',array(),array('routeClass'=>'SubDomainPrefixRoute'));

/*if(preg_match('/^answers/', env("HTTP_HOST"))){
	Router::connect('/', array('controller' => 'questions', 'action' => 'index'));
}else{*/
Router::connect( '/', array( 'controller' => 'pages', 'action' => 'display', 'home' ) );
Router::connect( '/error404', array( 'controller' => 'pages', 'action' => 'display', 'error', '404' ) );


#Router::connectNamed(array('c','hotel','page','sort','direction','limit','group','order'));

I18nRouter::connect( "/[m_about_url]", array( 'controller' => 'pages', 'action' => 'display', 'about' ) );

I18nRouter::connect( '/[m_articles_url]/:id-:slug/*',
	array( 'controller' => 'articles', 'action' => 'view', 'restricted' => false ),
	array( 'pass' => array( 'id', 'slug' ), 'id' => '[0-9]+' )
);
I18nRouter::connect( '/[m_articles_url]/[tag_url]/:tag_slug/*',
	array( 'controller' => 'articles', 'action' => 'index', 'restricted' => false ),
	array( 'pass' => array( 'tag_slug' ), 'tag_slug' => '[0-9a-zA-Z_\-]+' )
);
I18nRouter::connect( '/[m_articles_url]/[category_url]/:category_slug/*',
	array( 'controller' => 'articles', 'action' => 'index', 'restricted' => false ),
	array( 'pass' => array( 'category_slug' ), 'category_slug' => '[0-9a-zA-Z_\-]+' )
);
I18nRouter::connect( "/[m_articles_url]/*", array( 'controller' => 'articles',
	'action' => 'index',
	'restricted' => false
) );

I18nRouter::connect('/[movies_url]/:slug/*',
	array( 'controller' => 'movies', 'action' => 'view', 'restricted' => false ),
	array( 'pass' => array( 'slug' ) )
);

I18nRouter::connect('/[shows_buy_url]/:movie_slug/:show_id/*',
	array( 'controller' => 'shows', 'action' => 'buy', 'restricted' => false ),
	array( 'pass' => array( 'movie_slug','show_id' ), 'session_id' => '[0-9]+' )
);

Router::connect( "/admin/terms/:class/:action/*",
	array( 'controller' => 'terms', 'admin' => true ),
	array( 'pass' => array( 'class' ), 'class' => '[0-9a-zA-Z_\-]+' )
);

Router::connect( "/admin/terms/:class/*",
	array( 'controller' => 'terms', 'action' => 'index', 'admin' => true ),
	array( 'pass' => array( 'class' ), 'class' => '[0-9a-zA-Z_\-]+' )
);

I18nRouter::connect( "/[billboard_url]-:slug/*",
	array( 'controller' => 'shows', 'action' => 'index' ),
	array( 'pass' => array( 'slug' ), 'slug' => '[0-9a-zA-Z_\-]+' )
);

I18nRouter::connect( "/[m_contacts_url]", array( 'controller' => 'contacts',
	'action' => 'add',
	'restricted' => false
) );

I18nRouter::connect( "/[thanks_url]", array( 'controller' => 'pages', 'action' => 'display', 'thanks' ) );

I18nRouter::connect( "/[search_url]", array( 'controller' => 'pages', 'action' => 'display', 'buscador' ) );

I18nRouter::connect( "/[privacy_policies_url]", array( 'controller' => 'pages',
	'action' => 'display',
	'privacy_policies'
) );

I18nRouter::connect( "/[sitemap_url]", array( 'controller' => 'pages', 'action' => 'display', 'sitemap' ) );
I18nRouter::connect( "/[m_about_url]", array( 'controller' => 'pages', 'action' => 'display', 'about' ) );
I18nRouter::connect( "/[m_locations_url]", array( 'controller' => 'locations', 'action' => 'index') );
I18nRouter::connect( "/[m_services_url]", array( 'controller' => 'services', 'action' => 'index' ) );
I18nRouter::connect( "/[m_register_url]", array( 'controller' => 'pages', 'action' => 'display', 'register' ) );

/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
Router::connect( '/pages/*', array( 'controller' => 'pages', 'action' => 'display', 'restricted' => false ) );
Router::connect( '/admin', array( 'controller' => 'pages', 'action' => 'display', 'admin_home', 'admin' => true ) );

Router::connect( "/contacts.captcha", array( 'controller' => 'contacts', 'action' => 'captcha' ) );

Router::mapResources( 'locations' );
Router::mapResources( 'shows' );
Router::mapResources( 'movies' );
Router::mapResources( 'users' );
Router::mapResources( 'cities' );

Router::parseExtensions( 'json','xml' );

Router::connect( '/billboard/*', array( 'controller' => 'shows', 'action' => 'rest') );
Router::connect( '/billboard-full/*', array( 'controller' => 'shows', 'action' => 'rest_schedules') );

/*
 * Carga todos los archivos routes de los plugins
 */

$f = new Folder( APP.'plugins' );
$files = $f->read();
foreach( $files[0] as $file ){
	if( file_exists( APP.'plugins'.DS.$file.DS.'config'.DS.'routes.php' ) ){
		require_once APP.'plugins'.DS.$file.DS.'config'.DS.'routes.php';
	}
}
#}