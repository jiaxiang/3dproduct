<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 该部分主要是单日统计报告，例如今日统计、明日统计
 *
 */
class Onedaystat_Controller extends Template_Controller {
	
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
        	
        	//要发送的请求时间
			$time_request = strtotime(date('Y-m-d',time()) . $this->time_offset);
			
			//获取模板数据
			$data = $this->get_request_data($time_request);
			
			
			//定义模板
			$content = new View($this->package_name.'/'.$this->class_name.'/'.'oneday');
			$this->template->content = $content;
			//模板数据定义
			$this->template->content->data = $data;
			$this->template->content->istoday = 1;
			
			//模板左部功能导航
			$this->template->content->sitestat_left = new View($this->package_name.'/sitestat_left');
			//模板左部功能导航功能选中
			$this->template->content->sitestat_left->onedaystat = 1;
				
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
        	
			//要发送的请求时间
			$time_request = strtotime(date('Y-m-d',time()-86400) . $this->time_offset);
			
			//获取模板数据
			$data = $this->get_request_data($time_request);
			
			
			//定义模板
			$content = new View($this->package_name.'/'.$this->class_name.'/'.'oneday');
			$this->template->content = $content;
			//模板数据定义
			$this->template->content->data = $data;
			$this->template->content->isyesterday = 1;
			
			//模板左部功能导航
			$this->template->content->sitestat_left = new View($this->package_name.'/sitestat_left');
			//模板左部功能导航功能选中
			$this->template->content->sitestat_left->onedaystat_yesterday = 1;
				
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
	
	public function oneday($date, $isnext=0){
		$return_struct = array (
            'status' => 0,
            'code' => 501,
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try {
        	$request_data = $this->input->get();
        	
			//要发送的请求时间
			$time_request = strtotime($date . $this->time_offset);
			
			//获取模板数据
			$data = $this->get_request_data($time_request);
			
			
			//定义模板
			$content = new View($this->package_name.'/'.$this->class_name.'/'.'oneday');
			$this->template->content = $content;
			//模板数据定义
			$this->template->content->data = $data;
			$this->template->content->isnext = $isnext;
			
			//模板左部功能导航
			$this->template->content->sitestat_left = new View($this->package_name.'/sitestat_left');
			//模板左部功能导航功能选中
			$this->template->content->sitestat_left->onedaystat = 1;
				
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
	
	private function get_request_data( $time_request ){
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
		
		//生成要发送的密钥
		$phprpc_statking_key = Kohana::config('phprpc.remote.statking.api_key');
        $args = array($statking_id, $time_request);
        $sign = md5(json_encode($args).$phprpc_statking_key);
		
        //发送请求获取原始数据
		$data = $client->get_data_pv_ip_by_time($statking_id, $time_request, $sign );
		
		//原始数据处理
		$data = $this->manage_data($data);
		$data['date'] = date('Y-m-d', $time_request);
		$data['site_name'] = $site_name;
		return $data;
	}
	
	private function getpicsrc($data){
		$ps1 = $ps2 = $ct = $chart_data = '';
		$pv_max = $ip_min = 0;
		for ($i=0; $i<count($data); $i++){
			$ct == '' ? $ct.=$i : $ct.=','.$i;
			$ps1 == '' ? $ps1.=$data[$i]['pv'] : $ps1.=','.$data[$i]['pv'];
			$ps2 == '' ? $ps2.=$data[$i]['ip'] : $ps2.=','.$data[$i]['ip'];
			if ($data[$i]['pv'] > $pv_max) {
				$pv_max = $data[$i]['pv'];
			}
			$i == 0 ? $ip_min = $data[$i]['ip'] : '';
			if ($data[$i]['ip'] < $ip_min) {
				$ip_min = $data[$i]['ip'];
			}
			$chart_data .= "{$i};{$data[$i]['pv']};{$data[$i]['ip']}\n";
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
	
	private function manage_data($data){
		$array = array();
		$array['date_pv']     = isset($data['day']['pv']) ? intval( $data['day']['pv'] ) : 0;
		$array['date_ip']     = isset($data['day']['ip_count']) ? intval( $data['day']['ip_count'] ) : 0;
		$array['date_ip_new'] = isset($data['day']['ip_new']) ? intval( $data['day']['ip_new'] ) : 0;
		$array['date_pv_ip']  = $array['date_ip'] == 0 ? '0' : substr( $array['date_pv']/$array['date_ip'], 0, 5);
		
		$hours = array();
		for ($i = 0; $i<24; $i++){
			if ( isset($data['hours'][$i]) ) {
				$pv = isset( $data['hours'][$i]['pv'] ) ? $data['hours'][$i]['pv'] : 0;
				$ip = isset( $data['hours'][$i]['ip_count'] ) ? $data['hours'][$i]['ip_count'] : 0;
			}else {
				$pv = 0;
				$ip = 0;
			}
			$pv_ip = $ip == 0 ? 0 : substr( $pv/$ip, 0, 5 );
			$pv_rate = $array['date_pv']==0 ? '0%' : substr( $pv/$array['date_pv']*100, 0, 5) . '%';
			$pv_length = $array['date_pv']==0 ? 0 : $pv/$array['date_pv']*200;
			$hours[] = array(
				'pv' => $pv,
				'ip' => $ip,
				'pv_ip' => $pv_ip,
				'pv_rate' => $pv_rate,
				'pv_length' => $pv_length,
			);
		}
		$array['hours'] = $hours;
		
		$src_arr = $this->getpicsrc($hours);
		$array['date_src1'] = $src_arr['src1'];
		$array['date_src2'] = $src_arr['src2'];
		$array['flash1']    = $src_arr['flash1'];
		$array['flash2']    = $src_arr['flash2'];
		
		return $array;
	}
}