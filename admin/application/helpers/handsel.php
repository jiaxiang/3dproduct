<?php defined('SYSPATH') or die('No direct script access.');


class handsel_Core {
	
	private static $instance = NULL;

    // 获取单态实例
    public static function get_instance(){
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
	
	/*
	 * 获取彩金信息
	 * 
	 *  @param  	Int 	 用户id
	 *  @return 	array() 用户信息
	 */
	
	public function get($handsel_id)
	{
		$handsel = ORM::factory('handsel', $handsel_id);
		if ($handsel->loaded)
		{
		    return $handsel->as_array();
		}
		return FALSE;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	}