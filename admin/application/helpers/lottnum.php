<?php defined('SYSPATH') or die('No direct script access.');

class lottnum_Core {
    private static $instance = NULL;
   	
    
// 获取单态实例
    public static function get_instance(){
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
    
    public static function getlottid($type){
		switch ($type){
			case 'dlt':
			$lottid = 8;
			break;
			case 'plw':
			$lottid = 9;	
			break;
			case 'qxc':
			$lottid = 10;	
			break;
			case 'pls':
			$lottid = 11;	
			break;
			default:
				$lottid = 8;
			break;
		}
		return $lottid;
	}
	
   public static function getissue($type='dlt'){
		
		$lotyid = self::getlottid($type);
		$where = array('lotyid'=>$lotyid);
		$query_struct_default = array (
            'orderby' => array (
                'id' => 'DESC' 
            ), 
            'limit' => array (
                'per_page' => 20,
                'page' => 1
            ),
            'where' => $where,
        );
        
        $qhobj  = Qihaoservice::get_instance();
        $issues =$qhobj->query_assoc($query_struct_default);
        return $issues;
	}
}