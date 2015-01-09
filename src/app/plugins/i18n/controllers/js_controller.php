<?php
class JsController extends I18nAppController{
/**
 * Nombre del controller
 *
 * @var string
 * @access public
 */
	var $name = 'Js';
/**
 * Este controller no usa ningun modelo
 * @var array
 */
	var $uses=array();

	var $layout = 'ajax';

	function beforeFilter(){
		$this->Auth->allow("*");
		parent::beforeFilter();
	}

	
/**
 * Displays a view
 * @access public
 */
	function display() {
		$this->layout = 'ajax';
		if (!func_num_args()) {
			$this->redirect('/');
		}
		$path = func_get_args();
		$path[0] = preg_replace('/\.js$/','',$path[0]);

		if (!count($path)) {
			$this->redirect('/');
		}
		$count = count($path);
		$page = null;
		$subpage = null;
		$title = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title = Inflector::humanize($path[$count - 1]);
		}
		$this->set('page', $page);
		$this->set('subpage', $subpage);
		$this->set('title', $title);
		header("Content-Type: application/x-javascript; charset=UTF-8");
		$this->render(join('/', $path));
	}
	/**
	 *
	 */
	function content(){
		$this->render("l10n");
	}
}
?>