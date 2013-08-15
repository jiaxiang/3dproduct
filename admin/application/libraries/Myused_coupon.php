<?php defined('SYSPATH') OR die("No direct access allowed.");
  
class Myused_coupon_Core extends My
{
	protected $object_name = "used_coupon";
	protected static $instances;
	//protected $orm_instance = NULL;
	//protected $data = array();
	
	public static function & instance($id=0)
	{
		if(!isset(self::$instances[$id]))
		{
			$class=__CLASS__;
			self::$instances[$id]=new $class($id);
		}
		return self::$instances[$id];
	}
	
    /*	
	public function __construct($id)
	{
		$id 		= intval($id);
		$this->data = ORM::factory($this->object_name, $id)->as_array();
    }
     */
	
	/**
     * 获取公共实例
     */
    /*
    public  function get_orm_instance(){
        if(is_null($this->orm_instance)){
            $this->orm_instance = ORM::factory($this->object_name);
        }
        return $this->orm_instance;
    }
     */
    
    public function get_used_coupon_codes($site_id){
    	$coupons = $this->lists(array('where'=>array('site_id'=>$site_id)));

    	$coupon_codes = array();
    	
    	foreach ( $coupons as $coupon ) {
    		$coupon_codes[] = $coupon['coupon_code'];
    	}
        return $coupon_codes;
    }  
}
