<?php defined('SYSPATH') OR die('No direct access allowed.');

class Theme_Controller extends Template_Controller {

	public $site_id;

	public function __construct()
	{
		parent::__construct();
		$this->site_id = site::id();
	}

	public function index()
	{
		$site_id = 1;
		$theme_id = 2;

		$server = Storage_server::instance();
		$filename = $this->input->get('filename');

		$theme_views = $server->get_site_themes($site_id,$theme_id,'views');                
		$theme_js = $server->get_site_themes($site_id,$theme_id,'js');
		$theme_css = $server->get_site_themes($site_id,$theme_id,'css');                
		if(in_array($filename,$theme_views))
		{
			$type = 'views';
		}
		else if(in_array($filename,$theme_js))
		{
			$type = 'js';
		}
		else if(in_array($filename,$theme_css))
		{
			$type = 'css';
		}else{
			$type = 'views';
			$filename = 'index.php';
		}

		if($_POST)
		{
			$file = $_POST['file'];
			$server->cache_site_theme($site_id,$theme_id,$type,$filename,$file);
			remind::set('add '.$_POST['file'],url::current(TRUE));
		}

		$code = $server->get_site_theme($site_id,$theme_id,$type,$filename);

                $this->template->content = new View("site/theme_edit");
		$this->template->content->theme_files = array_merge($theme_views,$theme_js,$theme_css);
		$this->template->content->data = $code;
		$this->template->content->filename = $filename;
	}

}
