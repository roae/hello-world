<?php
/*
 * Definicio de XpaginComponent
 *
 * Se encarga de ejecutar las acciones a multiples registros de los listados
 * @author Efrain Rochin Aramburo
 * 23 febrero 2011
 */

class XpaginComponent extends Object {
	var $name="Xpagin";

	/**
	 * Guarda la referencia del controller que instanció este component
	 * @var Controller
	 */
	var $Controller;

	var $passedParams=array();

	var $data=null;

	var $isExecuter=false;

	/**
	 * Definicion de los components que ayudan en el funcionamiento de este component.
	 * @var array
	 * @access public
	 */
	var $components=array('Auth','Acl','Notifier','I18n.Interpreter','Session');

	/**
	 *	Bandera que indica si se debe regresar a la página anterior.
	 *
	 *	@var boolean
	 *	@access public
	 */
	var $return = true;

	/**
	 * Es llamado antes del metodo beforeFilter del controller
	 * @param Controller $controller Referencia
	 * @param array $settings arreglo de opcione
	 */
	function initialize( &$controller, $settings=array() ) {
		$this->Controller=&$controller;
	}

	/**
	 * Se ejecuta despues del beforeFilter del controller pero antes de la acción
	 * @param Controller $controller Referencia
	 */
	function startup(&$controller) {
			$this->data = $controller->data;
			if(isset($controller->data['Xpagin']['record'])) {
				$controller->data['Xpagin']['record'] = $this->clear($controller->data['Xpagin']['record']);
			}
			if(isset($this->data['Xpagin']) && !empty($this->data['Xpagin'])) {
				$route = $this->__parseUrl($this->data['Xpagin']['url']);
				$url = (!empty($route['plugin']) ? $route['plugin'] . '/'
						: '') . Inflector::camelize($route['controller']) . ($route['action'] ? '/' . $route['action'] : '');
				if($this->Acl->check($this->Auth->user(), $this->Auth->action())) {
					$this->isExecuter = true;
					call_user_func_array(array($controller, $route['action']), $route['pass']);
					unset($controller->data['Xpagin']['all']);
				} else {
					$this->Notifier->error($this->Interpreter->process("[:you-dont-have-permission-to-perform-this-action:]"));
				}
			}
		}

	function clear($val){
		$val=array_unique($val);
		foreach($val as $index=>$value){
			if(!$value){
				unset($val[$index]);
			}
		}
		return am($val);
	}

	function __checkAccessUrl($url){
		$user=$this->Auth->user();
		if(empty($user)){
			return $this->Acl->check($this->Auth->user('grupo_id'),$url);
		}else{
			return $this->Acl->check($this->Auth->user(),$url);
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
	 *	Agrega los parametros nombrados que vayan en la url a la url que se reedirige.
	 *
	 *	@param Controller &$controller Controller que realiza la redirección.
	 *	@param mixed $url Dirección URL a la que se redirige.
	 *	@param mixed $status Status HTTP.
	 *	@param boolean $exit Una bandera que indica si debe terminar el script.
	 *	@access public
	 *	@return boolean Indica si se debe continuar la redirección.
	 */
	function beforeRedirect(&$controller,$url,$status = null,$exit = true){
		if(isset($controller->params['requested']) || $this->Session->check('Auth.redirect')){
			$this->enabled = false;
			return $url;
		}
		$this->enabled = false;
		if(is_array($url)){
			$url = Router::url(am($controller->params['named'],$url,array("/")))."/";
			$this->log($url,'debug');

		}else{
			$named = '';
			#$this->log($url,'debug');
			if(!empty($controller->params['named'])){
				foreach($controller->params['named'] as $name => $value){
					$named .= '/' . $name . ':' . $value;
				}
			}
			$url .= $named."/";
			$url = (preg_match('/\/\/$/', $url))? preg_replace('/\/$/','',$url) : $url;
		}
		return (!preg_match('/#.+/', $url))? $url : preg_replace('/#/','/#',$url);
	}
}
?>