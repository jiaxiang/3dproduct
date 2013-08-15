<?php defined('SYSPATH') OR die('No direct access allowed.');

class Css_Controller extends Template_Controller {

	public $site_id;

	public function __construct()
	{
		parent::__construct();
		$this->site_id = site::id();
		role::check('site_css',$this->site_id);
	}

	public function index()
	{
		if($_POST)
		{
		
		}

		$css = "";

        $this->template->content = new View("site/css_edit");
        $this->template->content->data = $css;
	}

}
