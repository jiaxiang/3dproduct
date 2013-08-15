<?php defined('SYSPATH') or die('No direct script access.');

class jsbf_Core {
    private static $instance = NULL;
   	static public $status_name = array(
    	'0' => '未开',
    	'1' => '上半场',
    	'2' => '中场',
    	'3' => '下半场',
   		'-10' => '取消',
    	'-11' => '待定',
    	'-12' => '腰斩',
    	'-13' => '中断',
    	'-14' => '推迟',
    	'-1' => '完场'
    );
    static public $status_color = array(
    	'0' => '#808080',
    	'1' => '#FF0000',
    	'2' => '#0000FF',
    	'3' => '#FF0000',
    	'-10' => '#808080',
    	'-11' => '#808080',
    	'-12' => '#808080',
    	'-13' => '#808080',
    	'-14' => '#808080',
    	'-1' => '#000000'
    );
    // 获取单态实例
    public static function get_instance(){
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
	
    public function getBFByDate($date, $type, $status) {
    	$obj = ORM::factory('jsbf_data');
    	$obj->select('*');
    	if ($date == NULL) {
    		$date = date('Y-m-d');
    		$obj->where('match_date>=', $date);
    	}
    	else {
    		$obj->where('match_date', $date);
    	}
        if ($status != NULL) {
        	$obj->where('match_status', $status);
        }
        switch ($type) {
        	case 'zc': $obj->where('is_zc', 1); break;
        	case 'jc': $obj->where('is_jc', 1); break;
        	case 'bd': $obj->where('is_bd', 1); break;
        	default: break;
        }
        
        //$obj->orderby('match_time', 'ASC');
        $obj->orderby('match_open_time', 'ASC');
        
        
        $results = $obj->find_all();
       /* $a = $obj->last_query();
        var_dump($a);
        die();*/
        $jsbf_info = array();
        foreach ($results as $result) {
        	$t = $result->as_array();
        	$jsbf_info[] = $t;
        }
        return $jsbf_info;
    }
	
    public function getJSBFByAjax($date, $type, $status) {
    	$obj = ORM::factory('jsbf_data');
    	$obj->select('id,match_open_time,match_status,home_score,away_score,home_first_half_score,away_first_half_score,home_red_card
    	,away_red_card,home_yellow_card,away_yellow_card');
   		if ($date == NULL) {
    		$date = date('Y-m-d');
    		$obj->where('match_date>=', $date);
    	}
    	else {
    		$obj->where('match_date', $date);
    	}
        switch ($type) {
        	case 'zc': $obj->where('is_zc', 1); break;
        	case 'jc': $obj->where('is_jc', 1); break;
        	case 'bd': $obj->where('is_bd', 1); break;
        	default: break;
        }
        $obj->orderby('match_time', 'ASC');
        $results = $obj->find_all();
        $jsbf_info = array();
        foreach ($results as $result) {
        	$t = $result->as_array();
        	$jsbf_info[] = $t;
        }
        return $jsbf_info;
    }
    
    static public function getTimeStatus($start_time, $status) {
    	$return = '';
    	$ing_time = intval((time()-strtotime($start_time))/60);
    	switch ($status) {
    		case '1': 
    			if ($ing_time > 45) $return = '45+\'';
    			else $return = $ing_time.'\'';
    			break;
    		case '3':
    			if ($ing_time+45 > 90) $return = '90+\'';
    			else $return = ($ing_time+45).'\'';
    			break;
    		default: break;
    	}
    	return $return;
    }
	
}
?>