<?php
/**
 * CakePHP AppController
 * @author Efrain Rochin
 *
 *
 * ==============================================
 * Components
 * ==============================================
 * @property AuthComponent $Auth
 * @property AclComponent $Acl
 * @property CookieComponent $Cookie
 * @property EmailComponent $Email
 * @property $RequestHandler RequestHandlerComponent
 * @property SecurityComponent $Security
 * @property SessionComponent $Session
 * @property NotifierComponent $Notifier
 * @property InterpreterComponent $Interpreter
 * @property XpaginComponent $Xpagin
 *
 * ==============================================
 * Models
 * ==============================================
 * @property User $User
 * @property Group $Group
 * @property Contact $Contact
 */
class AppController extends Controller{
	var $helpers =array(
		'Html',
		'Xhtml',
		'Form',
		'Js'=>array('Jquery'),
		'Paginator',
		'Session',
		'Time',
		'Text',
		'Navigation',
		'Ajax',
		'Template',
		"Media.Uploader",
	);

	var $uses=array(
		'User',
		'Group',
		'Contact'
	);

	var $components = array(
		'I18n.Interpreter',
		'DebugKit.Toolbar'=>array('autoRun'=>true),
		'Cookie'=>array(
			'name'=>'ctic',
			'time'=>'1 year',
			'path'=>'/',
			'key'=>'kn&dfjnsf*!!kam99'
		),
		'Session',
		'Auth'=>array(
			'sessionKey'=>'Auth',
			'authorize' => 'controller',
			'actionPath' => 'controllers/',
			'loginAction' => '/admin/users/login',
			'loginRedirect' => '/admin/',
			'logoutAction' =>"/",
			'loginError'=>'[:username_or_password_are_incorrect:]',
			'authError'=>'[:you_dont_have_permision_to_access_this_location:]',
			'autoRedirect' => false
		),
		'Acl',
		'RequestHandler',
		'Xpagin',
		'Notifier'
	);

    /**
     * Datos del usuario logeado
     *
     */
	public $loggedUser = null;

    /**
	 * Determina si el usuario puede usar remember me en el login
	 */
	public $allowCookie = FALSE;

	/**
	 * Determina el tiempo de vida de la sesion.
	 *
	 * Si allowCookie.
	 */
	public $cookieTerm = '+4 weeks';

	/**
	 * Nombre de la cookie para los valores del usuario loggeado
	 */
	public $cookieName = 'Usr';

	/**
	 * Titulo de la pagina
	 * @var String
	 */
	var $pageTitle;

	/*
	 * Menus del sitio
	 */

	var $menus=array(
		'admin'=>array(
			'menu'=>array(
				/*'[:madmin_inicio:]'=>array(
					'url'=>'/admin/','isCurrentWhen'=>array('url'),
				),*/
				#'[:madmin_media:]'=>array('url'=>'/admin/media/','isCurrentWhen'=>array('plugin')),
				'[:madmin_cities:]'=>array(
					'url'=>array('controller'=>'cities','action'=>'index','admin'=>true,'plugin'=>false),
					'isCurrentWhen'=>array('child'),
					'menu'=>array(
						'[:madmin_cities_list:]'=>array(
							'url'=>array('controller'=>'cities','action'=>'index','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('action'),
						),
						'[:madmin_cities_add:]'=>array(
							'url'=>array('controller'=>'cities','action'=>'add','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('action'),
						),
						'|',
						'<i class="icon-trash"></i> [:madmin_cities_trash:]'=>array(
							'url'=>array('controller'=>'cities','action'=>'trash','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('action'),
						),
					)
				),
				'[:madmin_locations:]'=>array(
					'url'=>array('controller'=>'locations','action'=>'index','admin'=>true,'plugin'=>false),
					'isCurrentWhen'=>array('child'),
					'menu'=>array(
						'[:madmin_locations_list:]'=>array(
							'url'=>array('controller'=>'locations','action'=>'index','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('action'),
						),
						'[:madmin_locations_add:]'=>array(
							'url'=>array('controller'=>'locations','action'=>'add','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('action'),
						),
						'|',
						'<i class="icon-trash"></i> [:madmin_locations_trash:]'=>array(
							'url'=>array('controller'=>'locations','action'=>'trash','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('action'),
						),
					)
				),
				'[:madmin_rooms:]'=>array(
					'url'=>array('controller'=>'rooms','action'=>'index','admin'=>true,'plugin'=>false),
					'isCurrentWhen'=>array('child'),
					'menu'=>array(
						'[:madmin_rooms_list:]'=>array(
							'url'=>array('controller'=>'rooms','action'=>'index','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('action'),
						),
						'[:madmin_rooms_add:]'=>array(
							'url'=>array('controller'=>'rooms','action'=>'add','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('action'),
						),
						'|',
						'<i class="icon-trash"></i> [:madmin_rooms_trash:]'=>array(
							'url'=>array('controller'=>'rooms','action'=>'trash','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('action'),
						),
					)
				),
				'[:madmin_services:]'=>array(
					'url'=>array('controller'=>'services','action'=>'index','admin'=>true,'plugin'=>false),
					'isCurrentWhen'=>array('child'),
					'menu'=>array(
						'[:madmin_services_list:]'=>array(
							'url'=>array('controller'=>'services','action'=>'index','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('action'),
						),
						'[:madmin_services_add:]'=>array(
							'url'=>array('controller'=>'services','action'=>'add','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('action'),
						),
						'|',
						'<i class="icon-trash"></i> [:madmin_services_trash:]'=>array(
							'url'=>array('controller'=>'services','action'=>'trash','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('action'),
						),
					)
				),
				'|',
				'[:madmin_movies:]'=>array(
					'url'=>array('controller'=>'movies','action'=>'index','admin'=>true,'plugin'=>false),
					'isCurrentWhen'=>array('child'),
					'menu'=>array(
						'[:madmin_movies_list:]'=>array(
							'url'=>array('controller'=>'movies','action'=>'index','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('action'),
						),
						'[:madmin_movies_add:]'=>array(
							'url'=>array('controller'=>'movies','action'=>'add','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('action'),
						),
						'|',
						'<i class="icon-trash"></i> [:madmin_movies_trash:]'=>array(
							'url'=>array('controller'=>'movies','action'=>'trash','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('action'),
						),
					)
				),
				'|',
				'[:madmin_articles:]'=>array(
					'url'=>array('controller'=>'articles','action'=>'index','admin'=>true,'plugin'=>false),
					'isCurrentWhen'=>array('child'),
					'menu'=>array(
						'[:madmin_articles_list:]'=>array(
							'url'=>array('controller'=>'articles','action'=>'index','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('action'),
						),
						'[:madmin_articles_add:]'=>array(
							'url'=>array('controller'=>'articles','action'=>'add','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('action'),
						),
						'|',
						'<i class="icon-trash"></i> [:madmin_articles_trash:]'=>array(
							'url'=>array('controller'=>'articles','action'=>'trash','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('action'),
						),
						'|',
						'[:madmin_tags:]'=>array(
							'url'=>array('controller'=>'terms','class'=>'Tag','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('url'),
						),
						'[:madmin_category:]'=>array(
							'url'=>array('controller'=>'terms','class'=>'Category','admin'=>true,'plugin'=>false),
							'isCurrentWhen'=>array('url'),
						),
					),
				),
				//'[:madmin_tags:]'=>array('url'=>array('controller'=>'tags','action'=>'index','admin'=>true,'plugin'=>false)),
				'[:madmin_comments:]'=>array('url'=>'/admin/comments/'),
				'[:madmin_contacts:]'=>array('url'=>'/admin/contacts/'),
			),
			'config'=>array(
				'[:security:]'=>array('url'=>array('plugin'=>'acl','controller'=>'acos','action'=>'index','admin'=>true)),
				'|',
				'[:syncstatus:]'=>array('url'=>array('plugin'=>false,'controller'=>'shows','action'=>'syncstatus','admin'=>true)),
				'|',
				'[:settings:]'=>array('url'=>array('plugin'=>false,'controller'=>'settings','action'=>'index','admin'=>true)),
				'|',
				'[:users_list:]'=>array('url'=>array('controller'=>'users','action'=>'index','admin'=>true,'plugin'=>false)),
				'[:user_add:]'=>array('url'=>array('controller'=>'users','action'=>'add','admin'=>true,'plugin'=>false)),
				'|',
				'[:visit_site:]'=>array('url'=>'/','target'=>'_blank'),
				'|',
				'[:logout:]'=>array('url'=>array('controller'=>'users','action'=>'logout','plugin'=>false)),

			)
		),
		'default'=>array(
			'menu'=>array(
				'[:m_home:]'=>array('url'=>array('controller'=>'pages','action'=>'display','home'),'restrincted'=>false,'isCurrentWhen'=>array('url')),
				'[:m_about:]'=>array('url'=>array('controller'=>'pages','action'=>'display','about'),'restricted'=>false,'title'=>'[:m_about_title:]','isCurrentWhen'=>array('url'),'desc'=>'Link nosotros menu'),
				'[:m_articles:]'=>array('url'=>array('controller'=>'articles','action'=>'index'),'restricted'=>false,'title'=>'[:m_articles_title:]','desc'=>'link blog menu'),
				'[:m_contact:]'=>array('url'=>array('controller'=>'contacts','action'=>'add'),'restricted'=>false,'title'=>'[:m_contact_title:]','desc'=>'contacto menu','class'=>'contact'),
			)
		)
	);


	var $layout="";

	/**
	 * Meta description de la página
	 */
	var $pageDescription;

	/**
	 * Meta keywords de la página
	 */
	var $pageKeywords="[:meta_keywords_default:]";

	function beforeFilter() {
        $cookie = null;
		$this->params['isAjax']=$this->RequestHandler->isAjax();

		if((isset($this->params['restricted']) && $this->params['restricted']==false) || ($this->name=="Pages" && (isset($this->params['prefix']) && $this->params['prefix']!="admin"))){
			$this->Auth->allow("*");
		}

		$this->__userManagement();

		if($this->params['isAjax'] && Configure::read("debug")>0){
			Configure::write('debug', 1);
		}

		if(isset($this->params['named']['page']) && $this->params['named']['page']==1 && !isset($this->params['admin'])){
			$url=Router::parse("/".$this->params['url']['url']);
			unset($url['named']['page'],$this->params['named']['page']);
			$url=am($url,$url['named']);
			unset($url['named'],$url['pass'],$url['plugin']);
			#pr(Router::url($url));

			$this->redirect($url,301);
		}

		# se pone el titulo a la pagina
		$pass="";
		if($this->name=="Pages"){
			if(!empty($this->params['pass'])){
				$pass=implode("_",$this->params['pass']);
			}
		}
		if(empty($this->pageTitle)){
			$this->pageTitle="[:".$this->params['action']."_".$this->params['controller']."_".$pass."_page_title:]";
		}
		if(empty($this->pageDescription)){
			$this->pageDescription="[:".$this->params['action']."_".$this->params['controller']."_".$pass."_page_description:]";
		}

		#pr($this->Cookie->read("CitySelected"));

		Configure::write("CitySelected",$this->Cookie->read("CitySelected"));
		$this->set("CitySelected",$this->Cookie->read("CitySelected"));

		$LocationsSelected = stripslashes($this->Cookie->read("LocationsSelected"));

		$first = substr($LocationsSelected, 0, 1);
		if ($first !== false && ($first === '{' || $first === '[') && function_exists('json_decode')) {

			$LocationsSelected = json_decode($LocationsSelected, true);
		}
		Configure::write("LocationsSelected",$LocationsSelected);
		$this->set("LocationsSelected",$LocationsSelected);


		if  ($this->RequestHandler->isXml()) { // Allow a json request to specify XML formatting
			$this->RequestHandler->respondAs('xml'); // for setting headers
			$this->RequestHandler->renderAs($this, 'xml'); // for specifying templates for rendering
		} elseif ($this->RequestHandler->ext == 'json'){ // 'action' ajax requests and all 'action.json' requests receive JSON
			$this->RequestHandler->respondAs('json');
			$this->RequestHandler->renderAs($this, 'json');
		}

	}

	function __userManagement(){
		if( !$this->__isLogged()){
			if($this->loggedUser['User']['group_id'] != Configure::read('Group.Anonymous')){
				$this->Session->write($this->Auth->sessionKey,Configure::read("AnonyUser"));
				$this->loggedUser[$this->Auth->userModel] = $this->Session->read($this->Auth->sessionKey);
			}
		}
		Configure::write('loggedUser',$this->loggedUser);
		$this->set('loggedUser', $this->loggedUser);
	}

	function isAuthorized(){ #return true;
		if(isset($this->params['requested'])){
			return true;
		}
		if (!isset($this->params['url']['url'])) {
			$url = '';
		} else {
			$url = $this->params['url']['url'];
		}
		if($this->Acl->check($this->Auth->user(), $this->Auth->action())){
			return true;
		}
		$this->Notifier->error($this->Auth->authError);

		if(!isset($this->params['requested'])){
			$this->Session->write('Auth.redirect', $url);
		}
		$this->redirect($this->Auth->loginAction, null, true);
		return false;
	}

	function beforeRender(){

		#se agregan las urls del menu al tab de traducciones

		if(!$this->params['isAjax'] && $this->params['controller']!="js" && !isset($this->params['requested'])){
			# Asigna el layout dependiendo del prefijo si viene vacio se pone el layout default

			if(!$this->params['isAjax'] && empty($this->layout)){
				$this->layout = empty($this->params['prefix']) ? 'default' : $this->params['prefix'];
			}
			# Asignar bandera que señala cuando esta en el home o en una pagina interna.
			$this->set('home',$this->params['controller'] == 'pages' && $this->params['pass'][0] == 'home');
			#pr($this->layout);
			$this->__buildMenu($this->layout);
			//$this->set("ribbonBar",$this->ribbonBar);

			if(isset($this->menus[$this->layout])){
				$this->set($this->layout."Menu",$this->menus[$this->layout]);
			}else {
				$this->set("defaultMenu",$this->menus['default']);
			}
		}
		if($this->params['controller']=="js"){
			$this->layout="ajax";
		}

		$this->set("pageTitle",$this->pageTitle);
		$this->set("pageDescription",$this->pageDescription);
		$this->set("pageKeywords",$this->pageKeywords);

		#$this->__breadCrumb();
		#pr($this->layout);
		if($this->name=='CakeError'){
			$this->params['isAjax']=$this->RequestHandler->isAjax();
			$this->__userManagement();
			$this->set("home",false);
			$this->set('requestError','true');
			$this->layout="error";
		}
		#pr($this->layout);
	}

	function __breadCrumb(){
		if($this->name!='CakeError'){
			$breadCrumb=$this->Session->read("BreadCrumb");
			$breadCrumb[]=array(
				'url'=>$this->params['url']['url'],
				'ip'=>$_SERVER['REMOTE_ADDR'],
				'created'=>date("Y-m-d h:i:s"),
				'referer'=>$this->referer()
			);
		$this->Session->write("BreadCrumb",$breadCrumb);
		}
		#pr($breadCrumb);
	}

    /**
     * Establece un valor para el usuario actual que se loggea
     * @returns boolean TRUE if there is a logged user FALSE if no user is logged in.
     */
    function __isLogged(){

		$this->loggedUser = $this->Auth->user();
		if(isset($this->loggedUser['User']['group_id'])){
			return ($this->loggedUser['User']['group_id'] != Configure::read('Group.Anonymous'));
		}

		return false;
	}

    /**
     * Construye el menu agregando los links a los que tiene acceso el usuario.
     * @access private
     * @returns null
     */
	function __buildMenu($name){
		if(isset($this->menus[$name])){
			#Si es ajax o un RequestAction no se construye el menu
			if($this->params['isAjax'] || isset ($this->params['requested'])){
				return;
			}

			#Se remplaza la arroba porque session no permite guardar datos con @
			$login = r('@','.',$this->Auth->user('username'));
			$user=$this->Auth->user();
			if($user['User']['group_id'] == Configure::read("Group.Anonymous")){
				#$menu = Cache::read("Menu.$name.$login");
			}else{
				#$menu = $this->Session->read("Menu.$name.$login");
			}

			if(!empty($menu)){
				$this->menus[$name] = $menu;
			}else{
				foreach($this->menus[$name] as $key=>$menu){
					$this->__hideNotAllowMenuItems($this->menus[$name][$key]);
				}
				if($user['User']['group_id'] == Configure::read("Group.Anonymous")){
					Cache::write("Menu.$name.$login",$this->menus[$name]);
				}else{
					$this->Session->write("Menu.$name.$login",$this->menus[$name]);
				}
			}
		}
	}

	function __hideNotAllowMenuItems(&$menus=null){
		foreach($menus as $key => $menu){
			if($key=="_templates"){
				continue;
			}
			if(is_array($menu)){
				if(!empty($menu['url']) && (!isset($menu['restricted']) || $menu['restricted'])){

					if(!$this->__checkAccessUrl($menu['url'])){
						#Si tiene submenu se borra solo el enlace, en caso contrario se borra todo
						if(!empty($menus[$key]['menu'])){
							unset($menus[$key]['url']);
						}else{
							unset($menus[$key]);
						}
					}
				}
				#Si tiene submenus revisar sus permisos
				if(!empty($menus[$key]['menu'])){
					$this->__hideNotAllowMenuItems($menus[$key]['menu']);
				}
			}else{
				continue;
			}
			if(!empty($menus[$key]['menu'])){
				$hayElemento = false;
				foreach($menus[$key]['menu'] as $i=>$menu){
					if(is_array($menu)){
						$hayElemento = true;
					}
				}
				if(!$hayElemento){
					$menus[$key]['menu'] = array();
				}
			}

			#Eliminar menus que se quedaron sin submenus y sin link
			if(empty($menus[$key]['menu'])&&empty($menus[$key]['url'])){
				unset($menus[$key]);
			}
		}

		if (empty($menus)){
			$menus=array();
		}
	}

	function __checkAccessUrl($url){
		if(is_string($url)){
			$url=I18nRouter::interpretUrl($url);
		}
		$params = $this->__parseUrl($url);
		$aco = "controllers/".(!empty($params['plugin']) ? $params['plugin'] . '/' : '') . Inflector::camelize($params['controller']) . ($params['action'] ? '/' . $params['action'] : '');
		#pr($aco);
		$user=$this->Auth->user();
		if(empty($user)){
			return $this->Acl->check($this->Auth->user('grupo_id'),$aco);
		}else{
			return $this->Acl->check($this->Auth->user(),$aco);
		}
	}

	function __parseUrl($url){
		$paths = Configure::getInstance();
		$params = Router::parse(Router::url($url));
		if($paths->Routing['prefixes']){
			if(isset($params[$paths->Routing['prefixes'][0]])){
				if(empty($params['action'])){
					$params['action'] = $paths->Routing['prefixes'][0].'_'.'index';
				}else{
					$params['action'] = $paths->Routing['prefixes'][0].'_'.$params['action'];
				}
			}elseif(strpos($params['action'],$paths->Routing['prefixes'][0]) === 0){
				$privateAction = true;
			}
		}
		return $params;
	}

	/**
	 * Se sobreescribio este metodo por que en las llamadas ajax no se ejecuta el callback beforeRedirect el component Xpagin+
	 * @param string $url
	 * @param int $status
	 */

	function redirect($url,$status=null){
		parent::redirect($this->Xpagin->beforeRedirect($this,$url),$status);
	}

	function afterFilter() {
		$actions=array('admin_add','admin_edit','admin_delete','admin_status','admin_trash');
		if(!empty($this->data) && in_array($this->params['action'], $actions)){
			clearCache();
		}
	}

}
?>