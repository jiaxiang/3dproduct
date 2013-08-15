<?php defined('SYSPATH') OR die('No direct access allowed.');

class Phpinfo_Controller extends Template_Controller {
	public function index() {
		echo phpinfo();
	}
}
?>