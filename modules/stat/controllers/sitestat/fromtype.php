<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 来源站点分析
 * 
 */
class Fromtype_Controller extends Template_Controller {
	
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
		//die('gehaifeng');
		$return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try {
        	$request_data = $this->input->get();
        	
        	$date_from = $date_to = date('Y-m-d');
        
	        $data = $this->get_request_data($date_from, $date_to);
			
			$content = new View($this->package_name.'/'.$this->class_name.'/'.'fromtype');
			$this->template->content = $content;
			
			$this->template->content->data = $data;
			$this->template->content->istoday = 1;//页面tab选中
			
			$this->template->content->sitestat_left = new View($this->package_name.'/sitestat_left');
			$this->template->content->sitestat_left->fromtype = 1;
				
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
        	
        	$date_from = $date_to = date('Y-m-d', time()-86400);
        
	        $data = $this->get_request_data($date_from, $date_to, 0);
			
			$content = new View($this->package_name.'/'.$this->class_name.'/'.'fromtype');
			$this->template->content = $content;
			
			$this->template->content->data = $data;
			$this->template->content->isyesterday = 1; //页面tab选中
			
			$this->template->content->sitestat_left = new View($this->package_name.'/sitestat_left');
			$this->template->content->sitestat_left->fromtype = 1;
				
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
			
			$content = new View($this->package_name.'/'.$this->class_name.'/'.'fromtype');
			$this->template->content = $content;
			
			$this->template->content->data = $data;
			$this->template->content->isthismonth = 1; //页面tab选中
			
			$this->template->content->sitestat_left = new View($this->package_name.'/sitestat_left');
			$this->template->content->sitestat_left->fromtype = 1;
				
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
			
			$content = new View($this->package_name.'/'.$this->class_name.'/'.'fromtype');
			$this->template->content = $content;
			
			$this->template->content->data = $data;
			$this->template->content->isrecent30days = 1; //页面tab选中
			
			$this->template->content->sitestat_left = new View($this->package_name.'/sitestat_left');
			$this->template->content->sitestat_left->fromtype = 1;
				
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
			
			$content = new View($this->package_name.'/'.$this->class_name.'/'.'fromtype');
			$this->template->content = $content;
			
			$this->template->content->data = $data;
			
			$this->template->content->sitestat_left = new View($this->package_name.'/sitestat_left');
			$this->template->content->sitestat_left->fromtype = 1;
				
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
			
			$content = new View($this->package_name.'/'.$this->class_name.'/'.'fromtype');
			$this->template->content = $content;
			
			$this->template->content->data = $data;
			
			$this->template->content->sitestat_left = new View($this->package_name.'/sitestat_left');
			$this->template->content->sitestat_left->fromtype = 1;
				
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
	
	private function get_request_data( $date_from, $date_to ){
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
        
        //发送请求获取原始数据
		$data_all = $client->get_data_source_by_time_range($statking_id, $time_from, $time_to, $sign);
		
		//原始数据处理
		$data = $this->manage_data($data_all, $date_from, $date_to);
		$data['site_name'] = $site_name;
		return $data;
	}
	
	private function getpicsrc($data,$type){
		if (empty($data)) {
			return 'none';
		}
		
		if ($type == 'pie') {
			$chart_data = "[title];[value]\n";
			for ($i=0; $i<count($data); $i++){
				$chart_data .= "{$data[$i]['source']};{$data[$i]['pv']}\n";
			}
			$chart_data = urlencode($chart_data);
			$flash1 = "<embed width=\"800\" height=\"400\" flashvars=\"path=/amline/&settings_file=/amline/chart_settings/pie.xml&chart_data=$chart_data\" quality=\"high\" bgcolor=\"#FFFFFF\" name=\"amline\" id=\"amline\" style=\"\" src=\"/amline/ampie.swf\" type=\"application/x-shockwave-flash\">";
		}elseif ($type == 'line') {
			$chart_data = '';
			for ($i=0; $i<count($data[0]);$i++){
				$chart_data .= $data[0][$i]['date'];
				for ($j=0; $j<count($data); $j++){
					$chart_data.= ";{$data[$j][$i]['pv']}";
				}
				$chart_data .= "\n";
			}
			$chart_data = urlencode($chart_data);
			$flash1 = "<embed width=\"800\" height=\"400\" flashvars=\"path=/amline/&settings_file=/amline/chart_settings/statfromtype.xml&chart_data=$chart_data\" wmode=\"transparent\" quality=\"high\" bgcolor=\"#FFFFFF\" name=\"img_src1\" id=\"img_src1\" style=\"\" src=\"/amline/amline.swf\" type=\"application/x-shockwave-flash\">";
		}
		return array('flash1'=>$flash1);
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
		
		$time_f = strtotime($date_from); 
		$time_t = strtotime($date_to);
		
		$array_1 = $array_2 = $array_a = $date_arr = array();
		
		for ($time=$time_f; $time<=$time_t; $time+=86400){
			$array_a[] = array(
				'date' => date('Y-m-d',$time),
				'pv'   => 0,
				'ip'   => 0,
			);
			$date_arr[] = date('Y-m-d',$time);
		}
		$total = 0;
		for ($i=0; $i<count($data); $i++){
			$array_1[] = $array_a;
			$total += intval( $data[$i]['pv'] );
		}
		
		for ($i=0; $i<count($data); $i++){
			for ($j=0;$j<count($date_arr);$j++){
				if ( isset( $data[$i]['dates'][$j]['time'] ) && in_array( date('Y-m-d',$data[$i]['dates'][$j]['time']),$date_arr) ) {
					$place = array_search( date('Y-m-d',$data[$i]['dates'][$j]['time']), $date_arr);
					$array_1[$i][$place]['pv'] = isset($data[$i]['dates'][$j]['pv']) ? intval( $data[$i]['dates'][$j]['pv'] ) : 0;
					$array_1[$i][$place]['ip'] = isset($data[$i]['dates'][$j]['ip_count']) ? intval( $data[$i]['dates'][$j]['ip_count'] ) : 0;
				}
			}
			
			$array_2[] = array(
				'source' => $data[$i]['source'],
				'pv'          => $data[$i]['pv'],
				'ip'          => $data[$i]['ip_count'],
				'pv_rate'     => $total==0 ? '0%' : substr( $data[$i]['pv']/$total*100, 0, 5).'%',
				'pv_length'   => $total==0 ? 0 : $data[$i]['pv']/$total*200,
				);
		}
		
		if ($date_from == $date_to) {
			$src = $this->getpicsrc($array_2,'pie');
		}else {
			$src = $this->getpicsrc($array_1,'line');
		}
		
		if ($src == 'none') {
			$array['flash1'] = $src;
		}else {
			$array['flash1'] = $src['flash1'];
		}
		
		$array['types'] = $array_2;
		
		return $array;
	}
}