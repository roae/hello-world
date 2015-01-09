<?php
class I18nAppHelper extends AppHelper {

	/**
	 *	An instance of View that is using this helper.
	 *
	 *	@var View
	 */
		var $view;
	/**
	 *	Constructor for AppHelper.
	 *
	 *	@access public
	 *	@return AppHelper
	 */
	function __construct(){
		parent::__construct();
		$this->view = &ClassRegistry::getObject('view');
	}

}
?>