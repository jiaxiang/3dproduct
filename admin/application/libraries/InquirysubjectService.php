<?php defined('SYSPATH') OR die('No direct access allowed.');

class InquirysubjectService_Core extends DefaultService_Core {
	CONST DEFAULT_POSITION = 0;
	
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
    /* 兼容php5.2环境 End */
    
    /**
     * 批量 删除主题
     * @param $ids  array 主题id数组
     * @return void
     * @throws MyRuntimeException
     */
    public function delete_subjects($ids)
    {
        if(!empty($ids))
        {
            foreach($ids as $val)
            {
                $this->remove($val);
            }
        }
    }
}