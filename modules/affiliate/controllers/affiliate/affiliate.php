<?php defined('SYSPATH') OR die('No direct access allowed.');

class Affiliate_Controller extends Template_Controller {
	private $package_name = '';
    private $class_name = '';
	public $template = 'layout/common_html';
	
	public $site_ids;
	public $site_id;
	
	/**
     * 构造方法
     */
    public function __construct()
    {
    	$package_name = substr(dirname(__FILE__),strlen(dirname( dirname( dirname(__FILE__) ) ).'controllers/')+1);
        empty($package_name) && $package_name = 'default';
        $this->package_name = $package_name;
        $this->class_name = strtolower(substr(__CLASS__,0,strpos(__CLASS__,'_')));
        $this->site_ids = role::get_site_ids();
        $this->site_id = site::id();
        parent::__construct();
        if($this->is_ajax_request()==TRUE){
            $this->template = new View('layout/default_json');
        }
    }
    /**
     * 网站联盟推广首页
     *
     */
    public function index(){
    	$site_id_list = role::check('affiliate');
    	
		$return_struct = array (
            'status' => 0,
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try {
        	//* 初始化返回数据 */
            $return_data = array(
                'assoc'         => NULL,
                'count'         => 0,
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            $site_ids = role::get_site_ids();
            if (empty($site_ids))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            $in_site_id = site::id();
            
            if (isset($request_data['site_id']) AND $request_data['site_id'] === '0')
            {
            	unset($request_data['site_id']);
            }
            
       		if (isset($request_data['site_id']) AND !in_array($request_data['site_id'], $site_ids))
       		{
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'),403);
            }
            if ($in_site_id > 0){
            	$query_site_id = $in_site_id;
            } else {
            	throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            //获取平台支持的联盟
            $affiliates = AffiliateService::get_instance()->get_all_affiliates($query_site_id);
            $site_name = Mysite::instance($this->site_id)->get('domain');
            //print_r($affiliates);die();
            
			$content = new View($this->package_name.'/'.$this->class_name.'/'.__FUNCTION__);
			$this->template->content = $content;
			$this->template->content->site_name  = $site_name;
			$this->template->content->affiliates = $affiliates;
			
        }catch(MyRuntimeException $ex){
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
	}
	
	/**
	 * 网站联盟推广安装
	 *
	 * @param int $affiliate_id 网站联盟的ID
	 */
	public function install($affiliate_id){
		$site_id_list = role::check('affiliate');
		
		$return_struct = array (
            'status' => 0,
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try {
        	//* 初始化返回数据 */
            $return_data = array(
                'assoc'         => NULL,
                'count'         => 0,
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            $site_ids = role::get_site_ids();
            if (empty($site_ids))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            $in_site_id = site::id();
            
            if (isset($request_data['site_id']) AND $request_data['site_id'] === '0')
            {
            	unset($request_data['site_id']);
            }
            
       		if (isset($request_data['site_id']) AND !in_array($request_data['site_id'], $site_ids))
       		{
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'),403);
            }
            if ($in_site_id > 0){
            	$query_site_id = $in_site_id;
            } else {
            	throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            
            //获取平台支持的联盟
            $site_name = Mysite::instance($this->site_id)->get('domain');
            $affiliate = AffiliateService::get_instance()->get_affiliate_install($affiliate_id, $query_site_id);
            $currencies = CurrencyService::get_instance()->index( array('where'=>array('site_id'=>$query_site_id, 'active'=>1)) );
            
			$content = new View($this->package_name.'/'.$this->class_name.'/'.__FUNCTION__);
			$this->template->content = $content;
			$this->template->content->site_name  = $site_name;
			$this->template->content->affiliate  = $affiliate;
			$this->template->content->currencies = $currencies;
			
        }catch(MyRuntimeException $ex){
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
	}
	
	/**
	 * 卸载单个网站联盟
	 *
	 * @param int $affiliate_id 网站联盟的ID
	 */
	public function uninstall($affiliate_id){
		$site_id_list = role::check('affiliate');
		
		$return_struct = array (
            'status' => 0,
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try {
        	//* 初始化返回数据 */
            $return_data = array(
                'assoc'         => NULL,
                'count'         => 0,
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            $site_ids = role::get_site_ids();
            if (empty($site_ids))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            $in_site_id = site::id();
            
            if (isset($request_data['site_id']) AND $request_data['site_id'] === '0')
            {
            	unset($request_data['site_id']);
            }
            
       		if (isset($request_data['site_id']) AND !in_array($request_data['site_id'], $site_ids))
       		{
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'),403);
            }
            if ($in_site_id > 0){
            	$query_site_id = $in_site_id;
            } else {
            	throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            
            $affiliate = Site_affiliateService::get_instance()->get_affiliate_edit($affiliate_id, $query_site_id);
            Site_affiliateService::get_instance()->uninstall_site_affiliate($affiliate['id']);
			//* 补充&修改返回结构体 */
            //* 补充&修改返回结构体 ==根据业务逻辑定制== */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = "联盟 {$affiliate['affiliate_name']} 卸载成功！";
            $return_struct['content']= $return_data;
    		
            $return_struct['action'] = array(
                'type'=>'location',
                'url'=>!empty($request_data['listurl']) ? url::base().$request_data['listurl'] : url::base().$this->package_name.'/'.$this->class_name.'/'.'index'
            );
            
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            } else {
                // html 输出
                //* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->title = Kohana::config('site.name');
                
            }// end of request type determine
			
        }catch(MyRuntimeException $ex){
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
	}
	
	/**
	 * 编辑网站联盟的信息
	 *
	 * @param int $affiliate_id
	 */
	public function edit($affiliate_id){
		$site_id_list = role::check('affiliate');
		
		$return_struct = array (
            'status' => 0,
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try {
        	//* 初始化返回数据 */
            $return_data = array(
                'assoc'         => NULL,
                'count'         => 0,
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            $site_ids = role::get_site_ids();
            if (empty($site_ids))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            $in_site_id = site::id();
            
            if (isset($request_data['site_id']) AND $request_data['site_id'] === '0')
            {
            	unset($request_data['site_id']);
            }
            
       		if (isset($request_data['site_id']) AND !in_array($request_data['site_id'], $site_ids))
       		{
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'),403);
            }
            if ($in_site_id > 0){
            	$query_site_id = $in_site_id;
            } else {
            	throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            
            //获取平台支持的联盟
            $site_name = Mysite::instance($this->site_id)->get('domain');
            $affiliate = Site_affiliateService::get_instance()->get_affiliate_edit($affiliate_id, $query_site_id);
            $currencies = CurrencyService::get_instance()->index( array('where'=>array('site_id'=>$query_site_id, 'active'=>1)) );
            
			$content = new View($this->package_name.'/'.$this->class_name.'/'.__FUNCTION__);
			$this->template->content = $content;
			$this->template->content->site_name  = $site_name;
			$this->template->content->affiliate  = $affiliate;
			$this->template->content->currencies  = $currencies;
			
        }catch(MyRuntimeException $ex){
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
	}
	
	/**
	 * 对编辑网站联盟和安装网站联盟信息的处理
	 *
	 */
	public function post(){
		$site_id_list = role::check('affiliate');
		
		$return_struct = array (
            'status' => 0,
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try {
        	//* 初始化返回数据 */
            $return_data = array(
                'assoc'         => NULL,
                'count'         => 0,
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            $site_ids = role::get_site_ids();
            if (empty($site_ids))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            $in_site_id = site::id();
            
            if (isset($request_data['site_id']) AND $request_data['site_id'] === '0')
            {
            	unset($request_data['site_id']);
            }
            
       		if (isset($request_data['site_id']) AND !in_array($request_data['site_id'], $site_ids))
       		{
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'),403);
            }
            if ($in_site_id > 0){
            	$query_site_id = $in_site_id;
            } else {
            	throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            
            if (!isset($_POST['install_affiliate'])) {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'),403);
            }
            
            $affiliate_id = intval( $_POST['affiliate_id'] );
            $pdata = isset( $_POST['pdata'] ) ? $_POST['pdata'] : array();
            $send_type = intval( $_POST['send_type'] );
            $cookie_day = intval( $_POST['cookie_day'] );
            $currency = $_POST['currency_use']=='default' ? 'default' : $_POST['currency'];
            Site_affiliateService::get_instance()->update_site_affiliate($query_site_id, $affiliate_id, $pdata, $send_type, $cookie_day, $currency);
            
			//* 补充&修改返回结构体 */
            //* 补充&修改返回结构体 ==根据业务逻辑定制== */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '网站联盟推广操作成功！';
            $return_struct['content']= $return_data;
    		
            $return_struct['action'] = array(
                'type'=>'location',
                'url'=>!empty($request_data['listurl']) ? url::base().$request_data['listurl'] : url::base().$this->package_name.'/'.$this->class_name.'/'.'index'
            );
            
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            } else {
                // html 输出
                //* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->title = Kohana::config('site.name');
                
            }// end of request type determine
			
        }catch(MyRuntimeException $ex){
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
	}
	
	/**
	 * 对网站联盟订单的查询处理
	 *
	 */
	public function select(){
		$site_id_list = role::check('affiliate');
		
		$return_struct = array (
            'status' => 0,
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try {
        	//* 初始化返回数据 */
            $return_data = array(
                'assoc'         => NULL,
                'count'         => 0,
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            $site_ids = role::get_site_ids();
            if (empty($site_ids))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            $in_site_id = site::id();
            
            if (isset($request_data['site_id']) AND $request_data['site_id'] === '0')
            {
            	unset($request_data['site_id']);
            }
            
       		if (isset($request_data['site_id']) AND !in_array($request_data['site_id'], $site_ids))
       		{
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'),403);
            }
            if ($in_site_id > 0){
            	$query_site_id = $in_site_id;
            } else {
            	$query_site_id = $site_ids;
            	//throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            
            $site_name = Mysite::instance($this->site_id)->get('domain');
            //页数
            $page = isset($request_data['page']) && intval($request_data['page']) >= 1 ? intval($request_data['page']) : 1;
            //联盟id
            $affiliate_id = isset($request_data['affiliate_id']) && intval($request_data['affiliate_id']) >= 1 ? intval($request_data['affiliate_id']) : 0;
            //起始时间
            $time_f = isset($request_data['time_f']) ? $request_data['time_f'] : date('Y-m-d',time()-86400*365*5);
            $time_f = $time_f == '' ? date('Y-m-d',time()-86400*365*5) : $time_f;
            //结束时间
            $time_t = isset($request_data['time_t']) ? $request_data['time_t'] : date('Y-m-d');
            $time_t = $time_t == '' ? date('Y-m-d') : $time_t;
            
            //获取联盟的订单
            $where = array('site_id'=>$query_site_id,
            			   'order_time >' => date( 'Y-m-d H:i:s',strtotime($time_f.' 00:00:00') ),
            			   'order_time <' => date( 'Y-m-d H:i:s',strtotime($time_t.' 23:59:59') ),
            			   );
            if ($affiliate_id > 0) {
            	$where['affiliate_id'] = $affiliate_id;
            }
            $query_struct = array('where' =>$where,
            					  'limit' =>array('page'=>$page, 'per_page'=>20),
            					);
            $orders = Affiliate_orderService::get_instance()->index($query_struct);
            $orders_count = Affiliate_orderService::get_instance()->count($query_struct);
            
            $this->pagination = new Pagination(array(
				'total_items'    => $orders_count,
				'items_per_page' => 20,
			));
            
			$affiliates = AffiliateService::get_instance()->index(array('where'=>array('mark'=>1)));
			for ($i=0;$i<count($affiliates);$i++){
				if ($affiliates[$i]['id'] == $affiliate_id) {
					$affiliates[$i]['selected'] = 1;
				}else {
					$affiliates[$i]['selected'] = 0;
				}
			}
			
			$content = new View($this->package_name.'/'.$this->class_name.'/'.__FUNCTION__);
			$this->template->content = $content;
			$this->template->content->site_name  = $site_name;
			$this->template->content->orders     = $orders;
			$this->template->content->pagination = $this->pagination;
			$this->template->content->time_f     = $time_f;
			$this->template->content->time_t     = $time_t;
			$this->template->content->affiliates = $affiliates;
			
        }catch(MyRuntimeException $ex){
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
	}
}