<?php defined('SYSPATH') OR die('No direct access allowed.');

class Site_Controller extends Template_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		/* $view = new View('index');
		$view->set('user', $this->_user);
		$view->render(TRUE); */

		$view = new View('user/login');
		$view->set('user', $this->_user);
		$view->render(TRUE);
    }


}
