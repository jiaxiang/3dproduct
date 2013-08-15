<?php defined('SYSPATH') OR die('No direct access allowed.');

class Country_manageService_Core extends DefaultService_Core {
	
	/* 兼容php5.2环境 Start */
    private static $instance = NULL;
    // 获取单态实例
    public static function get_instance()
    {
        if(self::$instance === null)
        {
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
    
    /**
     * 根据name[名称]检查是否重复
     * @param $name string
     * @return bool
     */
    public function check_exist_name($name)
    {
        $query_struct = array (
            'where' => array (
                'name' => $name 
            ) 
        );
        if($this->count($query_struct))
        return true;
    }
    
    /**
     * 根据name_manage[中文名称]检查是否重复
     * @param $name_manage string
     * @return bool
     */
    public function check_exist_name_manage($name_manage)
    {
        $query_struct = array (
            'where' => array (
                'name_manage' => $name_manage 
            ) 
        );
        if($this->count($query_struct))
        return true;
    }
    
    /**
     * 根据iso_code[简码]检查是否重复
     * @param $iso_code string
     * @return bool
     */
    public function check_exist_code($iso_code)
    {
        $query_struct = array (
            'where' => array (
                'iso_code' => $iso_code 
            ) 
        );
        if($this->count($query_struct))
        return true;
    }
    
    
	/**
	 * get country data
	 *
	 * @param int $name
	 * @return Array
	 */
	public function get_by_name($name)
	{
		$country = array();
		$country = ORM::factory('country_manage')->where(array('name'=>$name))->find()->as_array();
		return $country;
	}
}    
    
?>