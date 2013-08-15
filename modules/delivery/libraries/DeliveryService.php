<?php defined('SYSPATH') OR die('No direct access allowed.');

class DeliveryService_Core extends DefaultService_Core {
	protected $serv_route_instance = NULL;
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
     * 根据物流类型查找站点支持的物流
     *
     * @param unknown_type $site_id
     * @param unknown_type $category_id
     * @return unknown
     */
    public function get_deliveries_by_category($category_id, $weight){
    	$deliveries = array();
    	if ($category_id == 0) {
    		$where = array( 'active'               => 1,
							//"(max_weight >= $weight OR max_weight = 0)",
    	              );
    	}else {
    		$where = array(//'delivery_category_id' => $category_id,
							'active'               => 1,
							//"(max_weight >= $weight OR max_weight = 0)",
    	              );
    	}
    	
    	$orderby = array(
                		'position'=>'DESC',
                		'id'=>'DESC',
            			);
    	$query_struct = array('where' => $where,'orderby'=>$orderby);
    	$deliveries = $this->index($query_struct);
    	return $deliveries;
    }
    
    public function get_delivery_by_id($id){
    	$delivery = array();
    	$where = array('id' => $id,
    	              );
    	$query_struct = array('where' => $where);
    	$delivery = $this->query_row($query_struct);
    	return $delivery;
    }
    
    /**
     * 根据国家和订单的价格或重量得到所有的物流方式以及计算的费用
     *
     * @param 	String $iso_code     国家简码
     * @param 	float  $total        订单价格
     * @param 	int    $weight       订单重量
     * @return 	array  $deliveries
     */
    public function get_cart_deliveries_by_country($iso_code, $total, $weight = 0)
    {
    	$whole_country_ids = array();
    	$results = array();
    	$country = Mycountry::instance()->get_by_iso($iso_code);
    	if(empty($country['id']) || !isset($country))
    	{
    		return array();
    	}
    	$country_id = $country['id'];
               
    	if(!is_array($country) || count($country) == 0)
	    {
	        die('error');
	    }
	        
    	$deliveries = $this->get_deliveries($site_id);
    	  	
    	foreach($deliveries as $key=>$rs)
        {        	
            //指定配送国家和费用 
            $result = $this->get_delivery_price($rs['id'], $country_id, $total, $weight); 
	        if(isset($result) && !empty($result))
	        {
	            $results[$result['id']] = $result;
	            
	        }        			
        }
        $results = array_merge($results);
        return $results;
    }
    
    /**
     * 根据物流id以及国家id和订单的价格或重量得到费用
     *
     * @param 	$int   $id           物流id
     * @param 	$int   $country_id   国家id
     * @param 	float  $total        订单价格
     * @param 	int    $weight       订单重量
     * @return 	array  $result
     */
    public function get_delivery_price($delivery_id, $country_id, $total, $weight = 0)
    {
    	$result = $this->get($delivery_id);
    	//$result = ORM::factory('delivery')->where('id',$delivery_id)->find()->as_array();
    	
		if ( $result['type'] == 0 ) {
			$result['use'] = true;
		}else {
			$delivery_country = Delivery_countryService::get_instance()->get_delivery_country($delivery_id, $country_id);
			if (empty($delivery_country)) {
				if ( $result['is_default'] == 1 ) {
					$result['use'] = true;
				}
			}else {
				$result['use'] = true;
				$result['first_price']    = $delivery_country['first_price'];
				$result['continue_price'] = $delivery_country['continue_price'];
				$result['expression']     = $delivery_country['expression'];
			}
		}
    	$result['shipping_discount'] = $this->do_carrier_price($result['expression'], $total, $weight);
        /*if($result['type'] == 1)
        {
            foreach($result['option'] as $k=>$v)
            {
            	if($result['id'] != $v['delivery_id'])
            	{
            		die('error');
            	} 
 
            	$result['countries'][$k] = $k;   		           		   			      			           		
            }

            if(isset($result['countries'][$country_id]) && !empty($result['countries'][$country_id]))
            {
            	$key_id = $result['countries'][$country_id];            		
            	$result['use'] = true;
            	$result['use_expression'] = $result['option'][$key_id]['expression'];
            	$result['shipping_discount'] = $this->do_carrier_price($result['use_expression'], $total, $weight);
            }
            else
            {
            	if($result['is_default'] == 1)
            	{
            		$result['use'] = true;
            		$result['shipping_discount'] = $this->do_carrier_price($result['expression'], $total, $weight);
            	}
            }
       }
       else
       {
            $result['use'] = true;
            $result['shipping_discount'] = $this->do_carrier_price($result['expression'], $total, $weight);
       }*/
            
       if(!isset($result['use']) || empty($result['use']))
       {
           unset($result);
           return array();
       }          
            
        return $result;     
    }
    
     /**
     * 通过物流id借助memcache获取物流相关数据
     *
     * @param 	int $id  物流id
     * @return 	array $cacheObject
     */
    function get($id)
    {
        $cacheObject = Cache::get('delivery.'.$id);
    	if(empty($cacheObject))
    	{	
    		$cacheObject = $this->read(array (
                'id' => $id 
            ));
    		if(!empty($cacheObject)){
    			if($cacheObject['type'] == 1)
        		{
        			$cacheObject['option'] = $this->get_option_by_delivery_id($id);
        		}else{
        			$cacheObject['option'] = array();
        		}
    		}
            Cache::set('delivery.'.$id, $cacheObject);
        }
        return $cacheObject;
    }
    
    /**
	 * 根据 site_id 获取所有物流的信息
	 *
	 * @param int $site_id 站点id
	 * @return array
	 */
    public function get_deliveries()
    {
    	$request_struct = array ( 
            'where' => array ( 
    			'active'  => 1
            ),
            'orderby' => array (
            	'position'=>'DESC',
            	'id'=>'DESC'
            )
        ); 
        
        $deliveries = $this->query_assoc($request_struct);
        return $deliveries;
    }
    /**
     * 根据site_id delivery_category_id 以及current_weight获取物流信息
     * @author 陈连生
     * @param int $site_id 站点id
     * @param int $delivery_category_id 物流类型id
     * @param int $current_weight 当前物流类型货品重量
     * @return array
     */
    public function get_deliveries_by_options($delivery_category_id,$current_weight)
    {
        $request_struct = array(
            'where'=>array(
                //'delivery_category_id='.$delivery_category_id,
                '(max_weight>'.$current_weight.' or max_weight=0)',
                'active=1',
            ),
            'orderby' => array(
                'position'=>'DESC',
                'id'=>'DESC',
            ),
        );
        return $this->query_assoc($request_struct);
    }

    
	/**
	 * 根据delivery_id得到此物流涉及的相关国家信息
	 *
	 * @param int $delivery_id 物流id
	 * @return array
	 */
	public function get_option_by_delivery_id($delivery_id)
	{
		$result = array();
		$results = array();
		$delivery_countries = ORM::factory('delivery_country')
			->where('delivery_id',$delivery_id)
			->find_all();
			
		
		foreach($delivery_countries as $value)
		{
			$result[] = $value->as_array();
		}

		foreach($result as $val)
		{
			$results[$val['country_id']] = $val;
		}
		return $results;
	}
	
	/**
     * 根据物流id以及国家iso和订单的价格或重量得到费用
     * @param   int    $delivery_id  物流id
     * @param 	String $iso_code     国家简码
     * @param 	float  $total        订单价格
     * @param 	int    $weight       订单重量
     * @return 	array  $deliveries
     */
	public function get_final_delivery_price($delivery_id, $iso_code, $total, $weight = 0)
	{
		$delivery = array();
		$country = Mycountry::instance()->get_by_iso($iso_code);
	    if(empty($country['id']) || !isset($country))
    	{
    		return array();
    	}
		$country_id = $country['id'];
		$result = $this->get($delivery_id);
		$delivery = $this->get_delivery_price($delivery_id, $country_id, $total, $weight);
		return $delivery;
	}
	
    /**
     * 得到并计算物流费用
     *
     * @param 	string $expresion    公式
     * @param 	float  $total        订单价格
     * @param 	int    $weight       订单重量
     * @return 	Float  $price
     */
    private function do_carrier_price($expression, $total, $weight = 0)
    {
    	if(!$expression)
    	{
    		return 0;
    	}
    	
    	$price = delivery::cal_fee($expression, $weight, $total);
        return $price;
    }
    
    public static function get_delivery_name($delivery_id)
	{
		$delivery = ORM::factory('delivery')->where('id',$delivery_id)->find()->as_array();
		$name = isset($delivery) && !empty($delivery['id']) ? $delivery['name'] : '未知';
		return $name;
	}
}