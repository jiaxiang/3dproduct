<?php defined('SYSPATH') OR die("No direct access allowed.");
  
class Mycpn_promotion_Core extends My
{
	protected $object_name = "cpn_promotion";
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
    private  function get_orm_instance()
    {
        if(is_null($this->orm_instance)){
            $this->orm_instance = ORM::factory($this->object_name);
        }
        return $this->orm_instance;
    }
     */
    
    /**
     * Get the promotion of a coupon
     * 
     * @param integer $coupon_id
     */
    public function get_by_couponid($coupon_id) {
        $cpn_promotion = ORM::factory($this->object_name)
    	    			->where('cpn_id', $coupon_id)
    	    			->find();
    	return $cpn_promotion->as_array();
    }
    
    /**
     * Delete the promotion of a coupon
     * 
     * @param integer $coupon_id
     */
    public function delete_by_couponid($coupon_id) {
    	if ( ORM::factory($this->object_name)
    	    ->where('cpn_id', $coupon_id)
    	    ->delete_all() ) {
    	   	return true;
    	}
    	return false;
    }
    
    /**
     * Enclose IDs with enclosers (comma(,) by default)
     * 
     * @param array $ids
     * @param char $encloser
     */
    public static function enclose_ids($ids, $encloser = ',') {
    	$enclosed_ids = '';
    	foreach ( $ids as $id ) {
        	$enclosed_ids .= $encloser . $id;
    	}
        $enclosed_ids .= $encloser;
        return $enclosed_ids;
    }
    
}
