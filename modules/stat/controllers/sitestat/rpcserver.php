<?php defined('SYSPATH') OR die('No direct access allowed.');

class Rpcserver_Controller extends Controller{
	
	
	public function index(){
		require_once(Kohana::find_file('vendor', 'phprpc/phprpc_server',TRUE));
		$server = new PHPRPC_Server();  
		$server->add(array('get_data_pv_ip_by_time', 'get_data_pv_ip_by_time_range', 'get_data_domain_by_time_range', 'get_data_page_by_time_range' ,'get_data_country_by_time_range', 'get_data_pv_ip_by_one_site', 'get_data_source_by_time_range'),new Rpcserver_Controller());  
		$server->start();
	}
	
	public function get_data_pv_ip_by_time($site, $time, $sign_server){
		$phprpc_statking_key = Kohana::config('phprpc.remote.statking.api_key');
        $args = array($site, $time);
        $sign = md5(json_encode($args).$phprpc_statking_key);
        if ($sign_server != $sign) {
        	return 'server refused';
        }
        
		$array = array(
			'day' => array('pv'=>240, 'ip_count'=>120, 'ip_new' => 10),
			'hours' => array(
				'0' => array('pv'=>10, 'ip_count'=>5),
				'1' => array('pv'=>10, 'ip_count'=>5),
				'2' => array('pv'=>10, 'ip_count'=>5),
				'3' => array('pv'=>10, 'ip_count'=>5),
				'4' => array('pv'=>10, 'ip_count'=>5),
				'5' => array('pv'=>10, 'ip_count'=>5),
				'6' => array('pv'=>10, 'ip_count'=>5),
				'7' => array('pv'=>10, 'ip_count'=>5),
				'8' => array('pv'=>10, 'ip_count'=>5),
				'9' => array('pv'=>10, 'ip_count'=>5),
				'10' => array('pv'=>10, 'ip_count'=>5),
				'11' => array('pv'=>10, 'ip_count'=>5),
				'12' => array('pv'=>10, 'ip_count'=>5),
				'13' => array('pv'=>10, 'ip_count'=>5),
				'14' => array('pv'=>10, 'ip_count'=>5),
				'15' => array('pv'=>10, 'ip_count'=>5),
				'16' => array('pv'=>10, 'ip_count'=>5),
				'17' => array('pv'=>10, 'ip_count'=>5),
				'18' => array('pv'=>10, 'ip_count'=>5),
				'19' => array('pv'=>10, 'ip_count'=>5),
				'20' => array('pv'=>10, 'ip_count'=>5),
				'21' => array('pv'=>10, 'ip_count'=>5),
				'22' => array('pv'=>10, 'ip_count'=>5),
				'23' => array('pv'=>10, 'ip_count'=>5),
				)
		);
		return $array;
	}
	
	public function get_data_pv_ip_by_time_range($site, $time_from, $time_to, $sign_server){
		$phprpc_statking_key = Kohana::config('phprpc.remote.statking.api_key');
        $args = array($site, $time_from, $time_to);
        $sign = md5(json_encode($args).$phprpc_statking_key);
        if ($sign_server != $sign) {
        	return 'server refused';
        }
		$array = array(
			'0' => array('date'=>strtotime('2010-09-01 08:00'), 'pv'=>20, 'ip_count'=>5),
			'1' => array('date'=>strtotime('2010-09-02 08:00'), 'pv'=>20, 'ip_count'=>5),
			'2' => array('date'=>strtotime('2010-09-03 08:00'), 'pv'=>20, 'ip_count'=>5),
			'3' => array('date'=>strtotime('2010-09-04 08:00'), 'pv'=>20, 'ip_count'=>5),
			'4' => array('date'=>strtotime('2010-09-05 08:00'), 'pv'=>20, 'ip_count'=>5),
			'5' => array('date'=>strtotime('2010-09-06 08:00'), 'pv'=>20, 'ip_count'=>5),
			'6' => array('date'=>strtotime('2010-09-07 08:00'), 'pv'=>20, 'ip_count'=>5),
			'7' => array('date'=>strtotime('2010-09-08 08:00'), 'pv'=>20, 'ip_count'=>5),
			'8' => array('date'=>strtotime('2010-09-09 08:00'), 'pv'=>20, 'ip_count'=>5),
			'9' => array('date'=>strtotime('2010-09-10 08:00'), 'pv'=>20, 'ip_count'=>5),
			'10' => array('date'=>strtotime('2010-09-11 08:00'), 'pv'=>20, 'ip_count'=>5),
			'11' => array('date'=>strtotime('2010-09-12 08:00'), 'pv'=>20, 'ip_count'=>5),
			'12' => array('date'=>strtotime('2010-09-13 08:00'), 'pv'=>20, 'ip_count'=>5),
			'13' => array('date'=>strtotime('2010-09-14 08:00'), 'pv'=>20, 'ip_count'=>5),
			'14' => array('date'=>strtotime('2010-09-15 08:00'), 'pv'=>20, 'ip_count'=>5),
			'15' => array('date'=>strtotime('2010-09-16 08:00'), 'pv'=>20, 'ip_count'=>5),
			'16' => array('date'=>strtotime('2010-09-17 08:00'), 'pv'=>20, 'ip_count'=>5),
			'17' => array('date'=>strtotime('2010-09-18 08:00'), 'pv'=>20, 'ip_count'=>5),
			'18' => array('date'=>strtotime('2010-09-19 08:00'), 'pv'=>20, 'ip_count'=>5),
			'19' => array('date'=>strtotime('2010-09-20 08:00'), 'pv'=>20, 'ip_count'=>5),
			'20' => array('date'=>strtotime('2010-09-21 08:00'), 'pv'=>20, 'ip_count'=>5),
			'21' => array('date'=>strtotime('2010-09-22 08:00'), 'pv'=>20, 'ip_count'=>5),
			'22' => array('date'=>strtotime('2010-09-23 08:00'), 'pv'=>20, 'ip_count'=>5),
			'23' => array('date'=>strtotime('2010-09-24 08:00'), 'pv'=>20, 'ip_count'=>5),
			'24' => array('date'=>strtotime('2010-09-25 08:00'), 'pv'=>20, 'ip_count'=>5),
			'25' => array('date'=>strtotime('2010-09-26 08:00'), 'pv'=>20, 'ip_count'=>5),
			'26' => array('date'=>strtotime('2010-09-27 08:00'), 'pv'=>20, 'ip_count'=>5),
			'27' => array('date'=>strtotime('2010-09-28 08:00'), 'pv'=>20, 'ip_count'=>5),
			'28' => array('date'=>strtotime('2010-09-29 08:00'), 'pv'=>20, 'ip_count'=>5),
			'29' => array('date'=>strtotime('2010-09-30 08:00'), 'pv'=>20, 'ip_count'=>5),
			'30' => array('date'=>strtotime('2010-09-31 08:00'), 'pv'=>20, 'ip_count'=>5),
		);
		return $array;
	}
	
	public function get_data_domain_by_time_range($site, $time_from, $time_to, $page=1, $limit=10, $order_by=0, $sign_server){
		$phprpc_statking_key = Kohana::config('phprpc.remote.statking.api_key');
        $args = array($site, $time_from, $time_to);
        $sign = md5(json_encode($args).$phprpc_statking_key);
        if ($sign_server != $sign) {
        	return 'server refused';
        }
        
		$array = array(
			'count' => 100,
			'data' => array(
				'0' => array('site'=>'www.aaa.com', 'pv'=>300, 'ip_count'=>200, 'ip_new'=>20,'jump_count'=>30),
				'1' => array('site'=>'www.bbb.com', 'pv'=>300, 'ip_count'=>200, 'ip_new'=>20,'jump_count'=>30),
				'2' => array('site'=>'www.ccc.com', 'pv'=>300, 'ip_count'=>200, 'ip_new'=>20,'jump_count'=>30),
				'3' => array('site'=>'www.ddd.com', 'pv'=>300, 'ip_count'=>200, 'ip_new'=>20,'jump_count'=>30),
				'4' => array('site'=>'www.eee.com', 'pv'=>300, 'ip_count'=>200, 'ip_new'=>20,'jump_count'=>30),
				'5' => array('site'=>'www.fff.com', 'pv'=>300, 'ip_count'=>200, 'ip_new'=>20,'jump_count'=>30),
				'6' => array('site'=>'www.ggg.com', 'pv'=>300, 'ip_count'=>200, 'ip_new'=>20,'jump_count'=>30),
				'7' => array('site'=>'www.hhh.com', 'pv'=>300, 'ip_count'=>200, 'ip_new'=>20,'jump_count'=>30),
				'8' => array('site'=>'www.iii.com', 'pv'=>300, 'ip_count'=>200, 'ip_new'=>20,'jump_count'=>30),
				'9' => array('site'=>'www.jjj.com', 'pv'=>300, 'ip_count'=>200, 'ip_new'=>20,'jump_count'=>30),
			),
		);
		return $array;
	}
	
	public function get_data_page_by_time_range($site, $time_from, $time_to, $page=1, $limit=10, $order_by=0, $sign_server){
		$phprpc_statking_key = Kohana::config('phprpc.remote.statking.api_key');
        $args = array($site, $time_from, $time_to);
        $sign = md5(json_encode($args).$phprpc_statking_key);
        if ($sign_server != $sign) {
        	return 'server refused';
        }
		
		$array = array(
			'count' => 100,
			'data' => array(
				'0' => array('url'=>'www.aaa.com', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
				'1' => array('url'=>'www.aaa.com/aaa', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
				'2' => array('url'=>'www.aaa.com/bbb', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
				'3' => array('url'=>'www.aaa.com/ccc', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
				'4' => array('url'=>'www.aaa.com/ddd', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
				'5' => array('url'=>'www.aaa.com/eee', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
				'6' => array('url'=>'www.aaa.com/fff', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
				'7' => array('url'=>'www.aaa.com/ggg', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
				'8' => array('url'=>'www.aaa.com/hhh', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
				'9' => array('url'=>'www.aaa.com/iii', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
			),
		);
		return $array;
	}
	
	public function get_data_country_by_time_range($site, $time_from, $time_to, $page=1, $limit=10, $order_by=0, $sign_server){
		$phprpc_statking_key = Kohana::config('phprpc.remote.statking.api_key');
        $args = array($site, $time_from, $time_to);
        $sign = md5(json_encode($args).$phprpc_statking_key);
        if ($sign_server != $sign) {
        	return 'server refused';
        }
		$array = array(
			'count' => 100,
			'total' => array('name'=>'总计', 'pv'=>3000, 'ip_count'=>2000, 'viewtime'=>200, 'jump_count'=>300),
			'data' => array(
				'0' => array('name'=>'中国', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
				'1' => array('name'=>'holand', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
				'2' => array('name'=>'england', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
				'3' => array('name'=>'france', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
				'4' => array('name'=>'Germany', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
				'5' => array('name'=>'japan', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
				'6' => array('name'=>'holand', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
				'7' => array('name'=>'Korea', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
				'8' => array('name'=>'Korea', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
				'9' => array('name'=>'Switzer', 'pv'=>300, 'ip_count'=>200, 'viewtime'=>20, 'jump_count'=>30),
			),
		);
		return $array;
	}
	
	public function get_data_pv_ip_by_one_site($site, $sign_server){
		$phprpc_statking_key = Kohana::config('phprpc.remote.statking.api_key');
        $args = array($site);
        $sign = md5(json_encode($args).$phprpc_statking_key);
        if ($sign_server != $sign) {
        	return 'server refused';
        }
		$array = array(
			'average' => array('pv'=>300, 'ip_count' => 150),
			'highest' => array('pv'=>500, 'ip_count' => 250),
			'total'   => array('pv'=>100000, 'ip_count' => 50000),
		);
		return $array;
	}
	
	public function get_data_source_by_time_range($site, $time_from, $time_to, $sign){
		/*$phprpc_statking_key = Kohana::config('phprpc.remote.statking.api_key');
        $args = array($site, $time_from, $time_to);
        $sign = md5(json_encode($args).$phprpc_statking_key);
        if ($sign_server != $sign) {
        	return 'server refused';
        }*/
		$array = array(
			'0' => array(
						'source' => 'SNS',
						'pv'          => 100,
						'ip_count'    => 40,
						'dates'       => array(
										'0'=> array('time'=>strtotime( '2010-10-01' ), 'pv'=> 20, 'ip_count'=> 8,),
										'1'=> array('time'=>strtotime( '2010-10-02' ), 'pv'=> 20, 'ip_count'=> 8,),
										'2'=> array('time'=>strtotime( '2010-10-03' ), 'pv'=> 20, 'ip_count'=> 8,),
										'3'=> array('time'=>strtotime( '2010-10-04' ), 'pv'=> 20, 'ip_count'=> 8,),
										),
						),
			'1' => array(
						'source' => '推广',
						'pv'          => 100,
						'ip_count'    => 40,
						'dates'       => array(
										'0'=> array('time'=>strtotime( '2010-10-01' ), 'pv'=> 20, 'ip_count'=> 8,),
										'1'=> array('time'=>strtotime( '2010-10-02' ), 'pv'=> 20, 'ip_count'=> 8,),
										'2'=> array('time'=>strtotime( '2010-10-03' ), 'pv'=> 20, 'ip_count'=> 8,),
										'3'=> array('time'=>strtotime( '2010-10-04' ), 'pv'=> 20, 'ip_count'=> 8,),
										),
						),
			'2' => array(
						'source' => '联盟',
						'pv'          => 100,
						'ip_count'    => 40,
						'dates'       => array(
										'0'=> array('time'=>strtotime( '2010-10-01' ), 'pv'=> 20, 'ip_count'=> 8,),
										'1'=> array('time'=>strtotime( '2010-10-02' ), 'pv'=> 20, 'ip_count'=> 8,),
										'2'=> array('time'=>strtotime( '2010-10-03' ), 'pv'=> 20, 'ip_count'=> 8,),
										'3'=> array('time'=>strtotime( '2010-10-04' ), 'pv'=> 20, 'ip_count'=> 8,),
										),
						),
			'3' => array(
						'source' => 'EDM',
						'pv'          => 100,
						'ip_count'    => 40,
						'dates'       => array(
										'0'=> array('time'=>strtotime( '2010-10-01' ), 'pv'=> 20, 'ip_count'=> 8,),
										'1'=> array('time'=>strtotime( '2010-10-02' ), 'pv'=> 20, 'ip_count'=> 8,),
										'2'=> array('time'=>strtotime( '2010-10-03' ), 'pv'=> 20, 'ip_count'=> 8,),
										'3'=> array('time'=>strtotime( '2010-10-04' ), 'pv'=> 20, 'ip_count'=> 8,),
										),
						),
		);
		return $array;
	}
}