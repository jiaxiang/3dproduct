<?php defined('SYSPATH') OR die('No direct access allowed.');

class Overview_Controller extends Template_Controller {
	
	private $package_name = '';
    private $class_name = '';
	public $template = 'layout/common_html';
	public $phprpc_server = '';
	public $time_offset = ' 00:00:00';
	
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
        	
			$site_ids = role::get_site_ids();
	        if (empty($site_ids)) {
	            throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
	        }
	        $site_id = site::id();
			
	        if ( $site_id <= 0 && !in_array($site_id, $site_ids)) {
	        	throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
	        }
	        
	        //获取站点的统计ID
	        $site_detail = Mysite::instance($site_id)->detail();
			$statking_id = $site_detail['statking_id'];
			$site_name = Mysite::instance($site_id)->get('domain');
			//$statking_id = 100097;
			
			require_once(Kohana::find_file('vendor', 'phprpc/phprpc_client',TRUE));
			$client = new PHPRPC_Client($this->phprpc_server);
			
			$date_today = date('Y-m-d');
			
			//今日数据
			$phprpc_statking_key = Kohana::config('phprpc.remote.statking.api_key');
	        $args = array($statking_id, strtotime($date_today . $this->time_offset));
	        $sign = md5(json_encode($args).$phprpc_statking_key);
	        
			$today = $client->get_data_pv_ip_by_time($statking_id,strtotime($date_today . $this->time_offset), $sign );
			
			//昨日数据
			$phprpc_statking_key = Kohana::config('phprpc.remote.statking.api_key');
	        $args = array($statking_id, strtotime( date('Y-m-d', time()-86400) . $this->time_offset) );
	        $sign = md5(json_encode($args).$phprpc_statking_key);
			
			$yesterday = $client->get_data_pv_ip_by_time($statking_id,strtotime(date('Y-m-d', time()-86400) . $this->time_offset), $sign );
			
			//24小时流量
			$now_hour = intval(date('H'));
			$hours = array();
			$ps1 = $ps2 = $ct = $chart_data = '';
			$pv_max = $ip_min = 0;
			for ($i=$now_hour+1; $i<=23; $i++){
				$hours[]['h'] = $i;
				$hours[]['v'] = $yesterday['hours'][$i];
				$ct == '' ? $ct.=$i : $ct.=','.$i;
				$ps1 == '' ? $ps1.=$yesterday['hours'][$i]['pv'] : $ps1.=','.$yesterday['hours'][$i]['pv'];
				$ps2 == '' ? $ps2.=$yesterday['hours'][$i]['ip_count'] : $ps2.=','.$yesterday['hours'][$i]['ip_count'];
				$chart_data .= "{$i};{$yesterday['hours'][$i]['pv']};{$yesterday['hours'][$i]['ip_count']}\n";
				if ($yesterday['hours'][$i]['pv'] > $pv_max) {
					$pv_max = $yesterday['hours'][$i]['pv'];
				}
				$ip_min == 0 ? $ip_min = $yesterday['hours'][$i]['ip_count'] : '';
				if ($yesterday['hours'][$i]['ip_count'] < $ip_min) {
					$ip_min = $yesterday['hours'][$i]['ip_count'];
				}
			}
			for ($i=0; $i<= $now_hour; $i++){
				$hours[]['h'] = $i;
				$hours[]['v'] = $today['hours'][$i];
				$ct == '' ? $ct.=$i : $ct.=','.$i;
				$ps1 == '' ? $ps1.=$today['hours'][$i]['pv'] : $ps1.=','.$today['hours'][$i]['pv'];
				$ps2 == '' ? $ps2.=$today['hours'][$i]['ip_count'] : $ps2.=','.$today['hours'][$i]['ip_count'];
				$chart_data .= "{$i};{$today['hours'][$i]['pv']};{$today['hours'][$i]['ip_count']}\n";
				if ($today['hours'][$i]['pv'] > $pv_max) {
					$pv_max = $today['hours'][$i]['pv'];
				}
				$ip_min == 0 ? $ip_min = $today['hours'][$i]['ip_count'] : '';
				if ($today['hours'][$i]['ip_count'] < $ip_min) {
					$ip_min = $today['hours'][$i]['ip_count'];
				}
			}
			if ($pv_max == $ip_min) {
				$pv_max = $ip_min+10;
			}
			$src1 = "/sitestat/chart?type=lc&w=800&h=300&ma=$pv_max&mi=$ip_min&r=10&t=pv-ip&ct=$ct&sp=30&g=2&ps1=$ps1&ps2=$ps2&clr1=255,0,0&clr2=0,255,0";
			$src2 = "/sitestat/chart?type=bg&w=800&h=300&ma=$pv_max&mi=$ip_min&r=10&t=pv-ip&ct=$ct&sp=30&g=2&ps1=$ps1&ps2=$ps2&clr1=255,0,0&clr2=0,255,0";
			$flash1 = "<embed width=\"800\" height=\"400\" flashvars=\"path=/amline/&settings_file=/amline/chart_settings/pv_ip.xml&chart_data=$chart_data\" wmode=\"transparent\" quality=\"high\" bgcolor=\"#FFFFFF\" name=\"img_src1\" id=\"img_src1\" style=\"\" src=\"/amline/amline.swf\" type=\"application/x-shockwave-flash\">";
			$flash2 = "<embed width=\"800\" height=\"400\" flashvars=\"path=/amline/&settings_file=/amline/chart_settings/pv_ip.xml&chart_data=$chart_data&preloader_color=#999999\" quality=\"high\" bgcolor=\"#FFFFFF\" name=\"img_src2\" id=\"img_src2\" style=\"display:none\" src=\"/amline/amcolumn.swf\" type=\"application/x-shockwave-flash\">";
			
			//站点概况
			$phprpc_statking_key = Kohana::config('phprpc.remote.statking.api_key');
	        $args = array($statking_id);
	        $sign = md5(json_encode($args).$phprpc_statking_key);
	        
			$overview = $client->get_data_pv_ip_by_one_site($statking_id, $sign);
			
			//来路域名
			$phprpc_statking_key = Kohana::config('phprpc.remote.statking.api_key');
	        $args = array( $statking_id, strtotime($date_today . $this->time_offset), strtotime($date_today . $this->time_offset) );
	        $sign = md5(json_encode($args).$phprpc_statking_key);
	        
			$domains_all = $client->get_data_domain_by_time_range($statking_id, strtotime($date_today . $this->time_offset), strtotime($date_today . $this->time_offset), 0, 1, 10, $sign);
			$domains = $domains_all['data'];
			
			//受访页面
			$phprpc_statking_key = Kohana::config('phprpc.remote.statking.api_key');
	        $args = array( $statking_id, strtotime($date_today . $this->time_offset), strtotime($date_today . $this->time_offset) );
	        $sign = md5(json_encode($args).$phprpc_statking_key);
	        
			$pages_all = $client->get_data_page_by_time_range($statking_id, strtotime($date_today . $this->time_offset), strtotime($date_today . $this->time_offset), 0, 1, 10, $sign);
			$pages = $pages_all['data'];
			
			//地区分布
			$phprpc_statking_key = Kohana::config('phprpc.remote.statking.api_key');
	        $args = array( $statking_id, strtotime($date_today . $this->time_offset), strtotime($date_today . $this->time_offset) );
	        $sign = md5(json_encode($args).$phprpc_statking_key);
	        
			$areas_all = $client->get_data_country_by_time_range($statking_id, strtotime($date_today . $this->time_offset), strtotime($date_today . $this->time_offset), 0, 1, 10, $sign);
			$areas = $areas_all['data'];
			
			$ps = $pts = '';
			$chart_data = "[title];[value]\n";
			for ($i=1; $i<count($areas); $i++){
				$ps .= ($ps == '') ? $areas[$i]['pv'] : ','.$areas[$i]['pv'];
				$pts .= ($pts == '') ? $areas[$i]['name'] : ','.$areas[$i]['name'];
				$chart_data .= "{$areas[$i]['name']};{$areas[$i]['pv']}\n";
			}
			$chart_data = urlencode($chart_data);
			$src3 = "/sitestat/chart?type=pc&w=400&h=200&ps=$ps&pts=$pts";
			$flash3 = "<embed width=\"800\" height=\"400\" flashvars=\"path=/amline/&settings_file=/amline/chart_settings/pie.xml&chart_data=$chart_data\" quality=\"high\" bgcolor=\"#FFFFFF\" name=\"amline\" id=\"amline\" style=\"\" src=\"/amline/ampie.swf\" type=\"application/x-shockwave-flash\">";
			
			$content = new View($this->package_name.'/'.$this->class_name.'/'.'index');
			$this->template->content = $content;
			$this->template->content->site_name = $site_name;
			$this->template->content->sitestat_left   = new View($this->package_name.'/sitestat_left');
			$this->template->content->sitestat_left->overview = 1;
			$this->template->content->today_pv_ip     = $today['day'];
			$this->template->content->yesterday_pv_ip = $yesterday['day'];
			$this->template->content->average         = $overview['average'];
			$this->template->content->highest         = $overview['highest'];
			$this->template->content->total           = $overview['total'];
			$this->template->content->src1            = $src1;
			$this->template->content->src2            = $src2;
			$this->template->content->src3            = $src3;
			$this->template->content->flash1          = $flash1;
			$this->template->content->flash2          = $flash2;
			$this->template->content->flash3          = $flash3;
			$this->template->content->domains         = $domains;
			$this->template->content->pages           = $pages;
				
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