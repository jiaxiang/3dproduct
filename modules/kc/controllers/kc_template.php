<?php 
defined('SYSPATH') OR die('No direct access allowed.');

abstract class Kc_template_Controller extends Controller {

    // Template view name
    public $template = 'layout/common_html';
    public $site_id = 0;
    public $manager = array();
    
    // Default to do auto-rendering
    public $auto_render = TRUE;
    
    public $ajax_request = FALSE;
    public function is_ajax_request()
    {
        return $this->ajax_request;
    }
    public function set_ajax_request($bool)
    {
        $this->ajax_request = $bool == TRUE;
    }
    /**
     * Template loading and setup routine.
     */
    public function __construct()
    {
		parent::__construct();

        // checke request is ajax
        $this->ajax_request = request::is_ajax();
        // Load the template
        $this->template = new View($this->template);
        if ($this->auto_render == TRUE)
        {
            Event::add('system.post_controller', array($this, '_render'));
        }
        /**
         * 判断用户登录情况
		 */
		if (isset($_REQUEST['session_id'])) 
		{
			$session = Session::instance($_REQUEST['session_id']);
			$manager = role::get_manager($_REQUEST['session_id']);
		}
		else
		{
			$session = Session::instance();
		    $manager = role::get_manager();
        }
		/* 当前请求的URL */
		$current_url = urlencode(url::current(TRUE));
        //当前用户管理的站点的ID
		$this->site_id = site::id();
    }
    
    /**
     * Render the loaded template.
     */
    public function _render()
    {
        if ($this->auto_render == TRUE)
        {
			// Render the template when the class is destroyed
			$this->profiler = new Profiler;
            $this->template->render(TRUE);
        }
    }
} // End Template_Controller
