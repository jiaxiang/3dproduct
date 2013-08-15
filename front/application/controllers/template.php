<?php defined('SYSPATH') OR die('No direct access allowed.');

abstract class Template_Controller extends Controller {

    // Default to do auto-rendering
    public $auto_render = FALSE;

    public $ajax_request = FALSE;

    public $_user = NULL;
    public $_agent = NULL;
    public $_site_config = NULL;
	public $obj_session, $obj_user_lib;
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
    public function __construct() {
		parent::__construct();
		$this->obj_session = Session::instance();
		$this->obj_user_lib = User::instance();
        // checke request is ajax
        $this->ajax_request = request::is_ajax();

        if ($this->auto_render == TRUE)
        {
            Event::add('system.post_controller', array($this, '_render'));
        }

        //$session = Session::instance();
        $user = array();
        $user = $this->obj_session->get('USER');
        //var_dump($_SESSION);
        if (!empty($user)) {
            $this->_user = $this->obj_user_lib->get_user_by_uid($user['id']);
        }
        unset($user);
        $data = array();
        $data['site_config'] = Kohana::config('site_config.site');
        $host = $_SERVER['HTTP_HOST'];
        $dis_site_config = Kohana::config('distribution_site_config');
        if (array_key_exists($host, $dis_site_config) == true && isset($dis_site_config[$host])) {
        	$data['site_config']['site_title'] = $dis_site_config[$host]['site_name'];
        	$data['site_config']['keywords'] = $dis_site_config[$host]['keywords'];
        	$data['site_config']['description'] = $dis_site_config[$host]['description'];
        }
        $this->_site_config = $data;
    }

    /**
     * Render the loaded template.
     */
    public function _render(){
        if ($this->auto_render == TRUE && isset($this->template))
        {
			// Render the template when the class is destroyed
            $this->template->render(TRUE);
        }
    }

    protected function _ex(&$ex, $return_struct=array(), $request_data=array(), $view = 'info'){
        $return_struct['code'] = $ex->getCode();
        $return_struct['msg'] = $ex->getMessage();
        $return_struct['status'] = $return_struct['code']==200?1:0;

        $this->template = new View($view);

        //TODO 异常处理
        if($this->is_ajax_request()){
            $this->template->content = $return_struct;
        }else{
            $this->template->return_struct = $return_struct;
            $this->template->request_data = $request_data;
        }
    }

    public function return_array() {
    	return array('code' => 0, 'msg' => '');
    }

} // End Template_Controller
