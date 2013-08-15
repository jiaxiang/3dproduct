<?php defined('SYSPATH') OR die('No direct access allowed.');

abstract class Template_Controller extends Controller {

    // Template view name
    //public $template = 'layout/common_html';
    public $template = 'layout/surlink_html';
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
        
        //zhu
        if (isset($manager['id']))
        {
            $active_time = $session->get('Opococ_manager_active_time');//用户最后操作时间
            $session->set('Opococ_manager_active_time', time());//用户最后操作时间
            $login_ip = $session->get('Opococ_manager_login_ip');//用户登录的IP

            //操作超时
            if ((time() - $active_time) > Kohana::config('login.time_out'))
            {
                $session->delete('Opococ_manager');
                $session->delete('Opococ_manager_active_time');
                $session->delete('Opococ_manager_login_ip');
                
                remind::set(Kohana::lang('o_global.first_login'), 'login?request_url=' . $current_url);
            }
            //用户IP(登录状态更换IP需要重新登录)
            $ip = tool::get_long_ip();
            if ($ip <> $login_ip)
            {
                remind::set(Kohana::lang('o_global.login_again'), 'login?request_url=' . $current_url);
            }
            $this->manager = $manager;
            $this->manager_id = $manager['id'];
            $this->manager_name = $manager['name'];
            $this->manager_is_admin = role::is_root($manager['name'])?1:$manager['is_admin'];
            $this->template->manager_data = $manager;
        }
        else
        {
            remind::set(Kohana::lang('o_global.first_login'), 'login?request_url=' . $current_url);
        }
    }
    
    /**
     * Render the loaded template.
     */
    public function _render()
    {
        if ($this->auto_render == TRUE)
        {
			// Render the template when the class is destroyed
			//$this->profiler = new Profiler;
            $this->template->render(TRUE);
        }
    }
    
    protected function _ex(&$ex, $return_struct=array(), $request_data=array(), $view = 'info', $tpl = 'layout/common_html'){
        $return_struct['code'] = $ex->getCode();
        $return_struct['msg'] = $ex->getMessage();
        $return_struct['status'] = $return_struct['code']==200?1:0;
        !is_object($this->template) && $this->template = new View($tpl);
        
        //TODO 异常处理
        if($this->is_ajax_request()){
            $this->template->content = $return_struct;
        }else{
            $this->template->content = new View($view);
            $this->template->content->request_data = $request_data;
            $this->template->content->return_struct = $return_struct;
            $this->template->return_struct = $return_struct;
        }
    }
    
} // End Template_Controller
