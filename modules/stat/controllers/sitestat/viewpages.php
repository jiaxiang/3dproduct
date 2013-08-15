<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 受访页面分析
 * 
 */
class Viewpages_Controller extends Template_Controller {
	
	private $package_name = '';
    private $class_name = '';
	public $template = 'layout/common_html';
	public $phprpc_server = '';
	public $perpage = 10;
	public $time_offset = ' 00:00:00';
	public $site_ids;
	public $site_id;
	
	/**
     * 构造方法
     */
    public function __construct()
    {
        $package_name = substr(dirname(__FILE__),strlen(APPPATH.'controllers/'));
        empty($package_name) && $package_name = 'default';
        $this->package_name = $package_name;
        $this->class_name = strtolower(substr(__CLASS__,0,strpos(__CLASS__,'_')));
        $this->phprpc_server = Kohana::config('phprpc.remote.statking.host');
        $this->site_ids = role::get_site_ids();
        $this->site_id = site::id();
        parent::__construct();
        if($this->is_ajax_request()==TRUE){
            $this->template = new View('layout/default_json');
        }
    }
    
	public function index(){
		$return_struct = array (
            'status' => 0,
            'code' => 501,
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try {
        	$request_data = $this->input->get();
        	
			$date_from = $date_to = date('Y-m-d');
        
	        $data = $this->get_request_data($date_from, $date_to, 0);
	        
			$content = new View($this->package_name.'/'.$this->class_name.'/'.'viewpages');
			$this->template->content = $content;
			
			$this->template->content->data = $data;
			$this->template->content->istoday = 1;
			
			$this->template->content->sitestat_left = new View($this->package_name.'/sitestat_left');
			$this->template->content->sitestat_left->viewpages = 1;
				
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
	
	public function yesterday(){
		$return_struct = array (
            'status' => 0,
            'code' => 501,
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try {
        	$request_data = $this->input->get();
        	
			$date_from = $date_to = date('Y-m-d',time()-86400);
        
	        $data = $this->get_request_data($date_from, $date_to, 0);
	        
			$content = new View($this->package_name.'/'.$this->class_name.'/'.'viewpages');
			$this->template->content = $content;
			
			$this->template->content->data = $data;
			$this->template->content->isyesterday = 1; //页面tab选中
			
			$this->template->content->sitestat_left = new View($this->package_name.'/sitestat_left');
			$this->template->content->sitestat_left->viewpages = 1;
				
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
	
	public function thismonth(){
		$return_struct = array (
            'status' => 0,
            'code' => 501,
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try {
        	$request_data = $this->input->get();
        	
			$date_from = date('Y-m-').'01';
			$date_to = date('Y-m-d');
	        
	        $data = $this->get_request_data($date_from, $date_to, 0);
	        
			
			$content = new View($this->package_name.'/'.$this->class_name.'/'.'viewpages');
			$this->template->content = $content;
			
			$this->template->content->data = $data;
			$this->template->content->isthismonth = 1; //页面tab选中
			
			$this->template->content->sitestat_left = new View($this->package_name.'/sitestat_left');
			$this->template->content->sitestat_left->viewpages = 1;
				
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
	
	public function recent30days(){
		$return_struct = array (
            'status' => 0,
            'code' => 501,
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try {
        	$request_data = $this->input->get();
        	
			$date_from = date('Y-m-d',time()-86400*30);
			$date_to = date('Y-m-d');
	        
	        $data = $this->get_request_data($date_from, $date_to, 0);
	        
			
			$content = new View($this->package_name.'/'.$this->class_name.'/'.'viewpages');
			$this->template->content = $content;
			
			$this->template->content->data = $data;
			$this->template->content->isrecent30days = 1; //页面tab选中
			
			$this->template->content->sitestat_left = new View($this->package_name.'/sitestat_left');
			$this->template->content->sitestat_left->viewpages = 1;
				
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
	
	public function oneday($date){
		$return_struct = array (
            'status' => 0,
            'code' => 501,
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try {
        	$request_data = $this->input->get();
        	
			$date_from = $date_to = $date;
        
	        $data = $this->get_request_data($date_from, $date_to, 0);
	        
			
			$content = new View($this->package_name.'/'.$this->class_name.'/'.'viewpages');
			$this->template->content = $content;
			
			$this->template->content->data = $data;
			
			$this->template->content->sitestat_left = new View($this->package_name.'/sitestat_left');
			$this->template->content->sitestat_left->viewpages = 1;
				
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
	
	public function fewdays(){
		$return_struct = array (
            'status' => 0,
            'code' => 501,
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try {
        	$request_data = $this->input->get();
        	
			$d_f = isset($_POST['time_from']) ? $_POST['time_from'] : date('Y-m-d');
			$d_t = isset($_POST['time_to']) ? $_POST['time_to'] : date('Y-m-d');
			
			if ( $d_f<=$d_t ){
				$date_from = $d_f;
				$date_to   = $d_t;
			}else {
				$date_from = $d_t;
				$date_to   = $d_f;
			}
	        
	        $data = $this->get_request_data($date_from, $date_to, 0);
	        
			
			$content = new View($this->package_name.'/'.$this->class_name.'/'.'viewpages');
			$this->template->content = $content;
			
			$this->template->content->data = $data;
			
			$this->template->content->sitestat_left = new View($this->package_name.'/sitestat_left');
			$this->template->content->sitestat_left->viewpages = 1;
				
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
	
	private function get_request_data( $date_from, $date_to, $order_by=0 ){
		if (empty($this->site_ids)) {
            throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
        }
        if ( $this->site_id <= 0 && !in_array($this->site_id, $this->site_ids)) {
        	throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
        }
        
        //获取站点的统计ID
        $statking_site_name = $this->get_statking_id_site_name();
		$statking_id = $statking_site_name['statking_id'];
		$site_name = $statking_site_name['site_name'];
        
		//PHPRPC客户端
		require_once(Kohana::find_file('vendor', 'phprpc/phprpc_client',TRUE));
		$client = new PHPRPC_Client($this->phprpc_server);
		
		$time_from = strtotime($date_from . $this->time_offset);
		$time_to = strtotime($date_to . $this->time_offset);
		
		//生成要发送的密钥
		$phprpc_statking_key = Kohana::config('phprpc.remote.statking.api_key');
        $args = array($statking_id, $time_from, $time_to);
        $sign = md5(json_encode($args).$phprpc_statking_key);
        
        //页码
        $page = $this->getpage();
        //发送请求获取原始数据
		$data_all = $client->get_data_page_by_time_range($statking_id, $time_from, $time_to, $order_by, $page, $this->perpage, $sign);
		
		//原始数据处理
		$data = $this->manage_data($data_all, $date_from, $date_to);
		$data['site_name'] = $site_name;
		return $data;
	}
	
	private function  getpage(){
		$page_now = isset($_POST['page_now']) ? intval($_POST['page_now']) : 1;
        $page_total = isset($_POST['page_total']) ? intval($_POST['page_total']) : 1;
        
		$page;
        if ( isset($_POST['first_page']) ) {
        	$page = 1;
        }elseif ( isset($_POST['previous_page']) ){
        	$page = $page_now - 1;
        }elseif ( isset($_POST['next_page']) ){
        	$page = $page_now + 1;
        }elseif ( isset($_POST['last_page']) ){
        	$page = $page_total;
        }else {
        	$page = 1;
        }
        $page = $page < 1 ? 1 : $page;
        return $page;
	}
	
	private function getpicsrc($data){
		if (empty($data)) {
			return 'none';
		}
		$ps = $pts = '';
		$chart_data = "[title];[value]\n";
		for ($i=0; $i<count($data); $i++){
			$ps .= ($ps == '') ? $data[$i]['pv'] : ','.$data[$i]['pv'];
			$pts .= ($pts == '') ? $data[$i]['url'] : ','.$data[$i]['url'];
			$chart_data .= "{$data[$i]['url']};{$data[$i]['pv']}\n";
		}
		$chart_data = urlencode($chart_data);
		$src1 = "/sitestat/chart?type=pc&w=1000&h=250&ps=$ps&pts=$pts";
		$flash1 = "<embed width=\"1200\" height=\"600\" flashvars=\"path=/amline/&settings_file=/amline/chart_settings/pie.xml&chart_data=$chart_data\" quality=\"high\" bgcolor=\"#FFFFFF\" name=\"amline\" id=\"amline\" style=\"\" src=\"/amline/ampie.swf\" type=\"application/x-shockwave-flash\">";
		return array('src1'=>$src1, 'flash1'=>$flash1);
	}
	
	private function get_statking_id_site_name(){
		$site_detail = Mysite::instance($this->site_id)->detail();
		$statking_id = $site_detail['statking_id'];
		$site_name = Mysite::instance($this->site_id)->get('domain');
		//$statking_id = 100097;
		
		return array('statking_id' => $statking_id, 'site_name'=>$site_name);
	}
	
	private function manage_data($data, $date_from, $date_to){
		$array = array();
		$array['date_from'] = $date_from;
		$array['date_to'] = $date_to;
		
		$src = $this->getpicsrc($data['data']);
		if ($src == 'none') {
			$array['src1'] = $src;
			$array['flash1'] = $src;
		}else {
			$array['src1'] = $src['src1'];
			$array['flash1'] = $src['flash1'];
		}
		$array['page'] = $this->getpage();
		$count = $data['count'];
		$array['pages'] = $count%$this->perpage==0 ? intval($count/$this->perpage) : intval($count/$this->perpage+1);
		$array['perpage'] = $this->perpage;
		
		$view_pages = $data['data'];
		for ($i=0; $i<count($view_pages); $i++){
			$view_pages[$i]['ip']        = $view_pages[$i]['ip_count'];
			$view_pages[$i]['pv_ip']     = $view_pages[$i]['ip']==0 ? 0 : substr($view_pages[$i]['pv']/$view_pages[$i]['ip'],0,5);
			$view_pages[$i]['jump_rate'] = substr( $view_pages[$i]['jump_count']/$view_pages[$i]['pv']*100, 0, 5).'%';
		}
		$array['view_pages'] = $view_pages;
		
		return $array;
	}
}