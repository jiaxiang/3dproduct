<?php defined('SYSPATH') OR die('No direct access allowed.');

class Forums_Controller extends Template_Controller {
	public function index() {
		$view = new View('forums');
		$view->render(TRUE);
	}
}