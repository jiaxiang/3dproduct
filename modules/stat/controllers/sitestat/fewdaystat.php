<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 该部分主要是多日统计报告，例如本月统计、最近30天统计
 * 该部分还作为从xxxx-xx-xx到xxxx-xx-xx的统计
 * 
 */
class Fewdaystat_Controller extends Template_Controller {
	
	private $package_name = '';
    private $class_name = '';
	public $template = 'layout/common_html';
	public $phprpc_server = '';
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
        	
        	//本月第一天
			$date_from = date('Y-m-').'01';
			//当天
			$date_to   = date('Y-m-d');
			
			if ($date_from == $date_to) {
				header("Location: /sitestat/onedaystat/oneday/$date_from");
				die();
			}
			//获取模板数据
		    $data = $this->get_request_data($date_from, $date_to);
			//print_r($data);die();
			//定义模板
			$content = new View($this->package_name.'/'.$this->class_name.'/'.'fewday');
			$this->template->content = $content;
			//模板数据定义
			$this->template->content->data = $data;
			$this->template->content->isthismonth = 1; 
			
			//模板左部功能导航
			$this->template->content->sitestat_left = new View($this->package_name.'/sitestat_left');
			//模板左部功能导航功能选中
			$this->template->content->sitestat_left->fewdaystat = 1;
			
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
			$date_to   = date('Y-m-d');
			//获取模板数据
	        $data = $this->get_request_data($date_from, $date_to);
			
			//定义模板
			$content = new View($this->package_name.'/'.$this->class_name.'/'.'fewday');
			$this->template->content = $content;
			//模板数据定义
			$this->template->content->data = $data;
			$this->template->content->isrecent30days = 1;
			
			//模板左部功能导航
			$this->template->content->sitestat_left = new View($this->package_name.'/sitestat_left');
			//模板左部功能导航功能选中
			$this->template->content->sitestat_left->fewdaystat_recent30days = 1;
			
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
			
			if ($date_from == $date_to) {
				header("Location: /sitestat/onedaystat/oneday/$date_from");
				die();
			}
			//获取模板数据
	        $data = $this->get_request_data($date_from, $date_to);
			
			//定义模板
			$content = new View($this->package_name.'/'.$this->class_name.'/'.'fewday');
			$this->template->content = $content;
			//模板数据定义
			$this->template->content->data = $data;
			
			//模板左部功能导航
			$this->template->content->sitestat_left = new View($this->package_name.'/sitestat_left');
				
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
		$data = $client->get_data_pv_ip_by_time_range($statking_id, $time_from, $time_to, $sign );
		
		//原始数据处理
		$data = $this->manage_data($data, $date_from, $date_to);
		$data['site_name'] = $site_name;
		return $data;
	}
	
	private function getpicsrc($data){
		$ps1 = $ps2 = $ct = $chart_data = '';
		$pv_max = $ip_min = 0;
		for ($i=0; $i<count($data); $i++){
			$ct == '' ? $ct.=substr($data[$i]['date'],5,5) : $ct.=','.substr($data[$i]['date'],5,5);
			$ps1 == '' ? $ps1.=$data[$i]['pv'] : $ps1.=','.$data[$i]['pv'];
			$ps2 == '' ? $ps2.=$data[$i]['ip'] : $ps2.=','.$data[$i]['ip'];
			
			if ($data[$i]['pv'] > $pv_max) {
				$pv_max = $data[$i]['pv'];
			}
			$i == 0 ? $ip_min = $data[$i]['ip'] : '';
			if ($data[$i]['ip'] < $ip_min) {
				$ip_min = $data[$i]['ip'];
			}
			$day = substr($data[$i]['date'],5,5);
			$chart_data .= "{$day};{$data[$i]['pv']};{$data[$i]['ip']}\n";
		}
		if ($pv_max == $ip_min) {
			$pv_max = $ip_min+10;
		}
		$chart_data = urlencode($chart_data);
		$src1 = "/sitestat/chart?type=lc&w=800&h=300&ma=$pv_max&mi=$ip_min&r=10&t=pv-ip&ct=$ct&sp=30&g=2&ps1=$ps1&ps2=$ps2&clr1=255,0,0&clr2=0,255,0";
		$src2 = "/sitestat/chart?type=bg&w=800&h=300&ma=$pv_max&mi=$ip_min&r=10&t=pv-ip&ct=$ct&sp=30&g=2&ps1=$ps1&ps2=$ps2&clr1=255,0,0&clr2=0,255,0";
		$flash1 = "<embed width=\"800\" height=\"400\" flashvars=\"path=/amline/&settings_file=/amline/chart_settings/pv_ip.xml&chart_data=$chart_data\" wmode=\"transparent\" quality=\"high\" bgcolor=\"#FFFFFF\" name=\"img_src1\" id=\"img_src1\" style=\"\" src=\"/amline/amline.swf\" type=\"application/x-shockwave-flash\">";
		$flash2 = "<embed width=\"800\" height=\"400\" flashvars=\"path=/amline/&settings_file=/amline/chart_settings/pv_ip.xml&chart_data=$chart_data&preloader_color=#999999\" quality=\"high\" bgcolor=\"#FFFFFF\" name=\"img_src2\" id=\"img_src2\" style=\"display:none\" src=\"/amline/amcolumn.swf\" type=\"application/x-shockwave-flash\">";
		
		return array('src1' => $src1, 'src2' => $src2, 'flash1' => $flash1, 'flash2' => $flash2);
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
		$time_f = strtotime($date_from); 
		$time_t = strtotime($date_to);
		
		$dates = $date_arr = array();
		for ($time=$time_f; $time<=$time_t; $time+=86400){
			$date_arr[] = date('Y-m-d',$time);
		}
		
		for ($time=$time_f; $time<=$time_t; $time+=86400){
			$dates[] = array(
				'date' => date('Y-m-d',$time),
				'pv'   => 0,
				'ip'   => 0,
				'pv_ip'=> 0,
				'pv_rate' => '0%',
				'pv_length' => 0,
			);
		}
		
		if(!empty($data)){
			$total = 0;
			for ($i=0; $i<count($data); $i++){
				$total += intval( $data[$i]['pv'] );
			}
			for ($i=0; $i<count($data); $i++){
				if ( isset( $data[$i]['date'] ) && in_array( date('Y-m-d',$data[$i]['date']),$date_arr) ) {
					$place = array_search( date('Y-m-d',$data[$i]['date']), $date_arr);
					$dates[$place]['pv'] = intval( $data[$i]['pv'] );
					$dates[$place]['ip'] = intval( $data[$i]['ip_count'] );
					$dates[$place]['pv_ip'] = $dates[$place]['ip'] == 0 ? 0 : substr( $dates[$place]['pv']/$dates[$place]['ip'], 0, 5);
					$dates[$place]['pv_rate'] = $total==0 ? '0%' : substr( $dates[$place]['pv']/$total*100, 0, 5).'%';
					$dates[$place]['pv_length'] = $total==0 ? 0 : $dates[$place]['pv']/$total*200;
				}
			}
		}
		
		$array['dates'] = $dates;
		
		$src_arr = $this->getpicsrc($dates);
		$array['src1'] = $src_arr['src1'];
		$array['src2'] = $src_arr['src2'];
		$array['flash1']    = $src_arr['flash1'];
		$array['flash2']    = $src_arr['flash2'];
		
		$array['date_from'] = $date_from;
		$array['date_to'] = $date_to;
		return $array;
	}
}