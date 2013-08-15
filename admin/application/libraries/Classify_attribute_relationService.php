<?php
defined('SYSPATH') or die('No direct access allowed.');

class Classify_attribute_relationService_Core extends DefaultService_Core {
	/* 兼容php5.2环境 Start */
    private static $instance = NULL;
    // 获取单态实例
    public static function get_instance(){
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
    /* 兼容php5.2环境 End */
    
	/**
	 * 根据条件where删除类型-规格关系
	 * @param $where array
	 */
	public function delete_relations($where = array()) {
		try {
			$orm_instance = ORM::factory($this->object_name);
			$orm_instance->where($where)->delete_all();
		} catch (MyRuntimeException $ex) {
			throw $ex;
		}
	}
	
    /**
     * 维护类型-规格关系
     * @param where array
     */
    public function update_relations($where = array())
    {
    	//TODO
    }
}
