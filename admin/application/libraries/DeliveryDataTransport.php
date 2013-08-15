<?php defined('SYSPATH') OR die('No direct access allowed.');
class DeliveryDataTransport_Core extends DefaultDataTransport_Service {
    /**
     * 当前操作的记录ID
     */
    protected $current_id = -1;

    /**
     * 记录结束ID
     */
    protected $end = 0;

    /**
     * 记录集数据
     */
    protected $data     = array();

    /**
     * 实例化对像 
     */
    private static $instances = NULL;

    // 获取单态实例
    public static function & instance($site_id){
        if(self::$instances[$site_id] === null){
            $classname = __CLASS__;
            self::$instances[$site_id] = new $classname($site_id);
        }
        return self::$instances[$site_id];
    }


    /**
     * Construct load data
     *
     * @param Int $site_id
     */
    public function __construct($site_id)
    {
        $this->db = Database::instance('default');
        $sql    = "SELECT `carriers`.* FROM (`carriers`) WHERE `site_id` = $site_id ORDER BY `carriers`.`id` ASC"; 
        $carriers = $this->db->query($sql); 
        foreach($carriers as $keyc=>$_carrier)
        {
            $delivery_temp                      = array();
            $delivery_temp['id']                = $_carrier->id;
            $delivery_temp['site_id']           = $_carrier->site_id;
            $delivery_temp['name']              = $_carrier->name;
            $delivery_temp['url']               = $_carrier->url;
            $delivery_temp['type']              = $_carrier->country_relative;           
            $delivery_temp['first_unit']        = 1000000;
            $delivery_temp['continue_unit']     = 1000;
            $delivery_temp['is_default']        = 1;            
            $delivery_temp['continue_price']    = 0;  
            $delivery_temp['position']          = $_carrier->position;         
            $delivery_temp['delay']             = $_carrier->delay;
            $delivery_temp['active']            = $_carrier->active;
            
            if($_carrier->type == 0)
            {
            	$delivery_temp['use_exp']           = 0;
            	$delivery_temp['first_price']       = $_carrier->price;
            	$delivery_temp['expression']        = delivery::create_exp(1000000, 1000, $_carrier->price, 0);
            	
	            $delivery_temp['delivery_country']   = array();
	            $sql    = "SELECT `carrier_countries`.* FROM (`carrier_countries`) WHERE `site_id` = $site_id AND `carrier_id` = $_carrier->id ORDER BY `carrier_countries`.`id` ASC";
	            $countries = $this->db->query($sql);
	            $j = 0;
	            $price_j = array();
	            foreach($countries as $key_c=>$_country)
	            {
	            	if($_country->shipping_add != 0)
	            	{
	            		if(!isset($price_j[$_country->shipping_add]))
	            		{
	            			$delivery_temp['delivery_country'][$key_c]['position']        = $j;
	            			$price_j[$_country->shipping_add] = $j;
	            			$j++;
	            		}
	            		else
	            		{
	            			$delivery_temp['delivery_country'][$key_c]['position']        = $price_j[$_country->shipping_add];
	            		}
	            		$delivery_temp['delivery_country'][$key_c]['id']              = $_country->id;
		                $delivery_temp['delivery_country'][$key_c]['site_id']         = $_country->site_id;
		                $delivery_temp['delivery_country'][$key_c]['country_id']      = $_country->country_id;
		                $delivery_temp['delivery_country'][$key_c]['delivery_id']     = $_country->carrier_id;
		                $delivery_temp['delivery_country'][$key_c]['first_price']     = $_country->shipping_add + $delivery_temp['first_price'];
		                $delivery_temp['delivery_country'][$key_c]['continue_price']  = 0;
		                $first_price = $_country->shipping_add + $delivery_temp['first_price'];  
		                $delivery_temp['delivery_country'][$key_c]['use_exp']         = 0;
						$delivery_temp['delivery_country'][$key_c]['expression']      = delivery::create_exp(1000000, 1000, $first_price, 0);				
	            	}	                
	            }
            	if($j == 0)
	            {
	            	$delivery_temp['type']              = 0; 
	            }
            }
            else
            {
            	$delivery_temp['use_exp']           = 1;
	            $delivery_temp['first_price']       = 0;
	            $sql_range    = "SELECT `carrier_ranges`.* FROM (`carrier_ranges`) WHERE `site_id` = $site_id AND `carrier_id` = $_carrier->id ORDER BY `carrier_ranges`.`id` ASC";
	            $ranges = $this->db->query($sql_range)->as_array();
	            $str = '';
	            foreach($ranges as $key_c=>$_range)
	            {
	            	if($_range == end($ranges))
	            	{
		            	if($_range->parameter_from == 0)
		            	{
		            		$str .= "{{".$_range->parameter_to."-p}-0.6}*".$_range->shipping;
		            	}
		            	else
		            	{
		            		$str .= "{{p-".$_range->parameter_from."}-0.1}*{{".$_range->parameter_to."-p}-0.6}*".$_range->shipping;
		            	}
	            	}
	            	else
	            	{
		            	if($_range->parameter_from == 0)
			            {
			            	$str .= "{{".$_range->parameter_to."-p}-0.6}*".$_range->shipping.'+';
			            }
			            else
			            {
			            	$str .= "{{p-".$_range->parameter_from."}-0.1}*{{".$_range->parameter_to."-p}-0.6}*".$_range->shipping.'+';
			            }
	            	}   
	            }
	            $delivery_temp['expression']        = $str;	 

                $delivery_temp['delivery_country']   = array();
	            $sql    = "SELECT `carrier_countries`.* FROM (`carrier_countries`) WHERE `site_id` = $site_id AND `carrier_id` = $_carrier->id ORDER BY `carrier_countries`.`id` ASC";
	            $countries = $this->db->query($sql);
	            $i = 0;
	            $price = array();
	            foreach($countries as $key_c=>$_country)
	            {
	            	if($_country->shipping_add != 0)
	            	{
	            		if(!isset($price[$_country->shipping_add]))
	            		{
	            			$delivery_temp['delivery_country'][$key_c]['position']        = $i;
	            			$price[$_country->shipping_add] = $i;
	            			$i++;
	            		}
	            		else
	            		{
	            			$delivery_temp['delivery_country'][$key_c]['position']        = $price[$_country->shipping_add];
	            		}
	            		$str_country = '';
	            		$delivery_temp['delivery_country'][$key_c]['id']              = $_country->id;
		                $delivery_temp['delivery_country'][$key_c]['site_id']         = $_country->site_id;
		                $delivery_temp['delivery_country'][$key_c]['country_id']      = $_country->country_id;
		                $delivery_temp['delivery_country'][$key_c]['delivery_id']     = $_country->carrier_id;
		                $delivery_temp['delivery_country'][$key_c]['first_price']     = 0;   
		                $delivery_temp['delivery_country'][$key_c]['continue_price']  = 0;
		                
		                $delivery_temp['delivery_country'][$key_c]['use_exp']         = 1;
		                
			            foreach($ranges as $k_c=>$_r)
			            {			            	
			            	if($_r == end($ranges))
			            	{
				            	if($_r->parameter_from == 0)
				            	{
				            		$str_country .= "{{".$_r->parameter_to."-p}-0.6}*".($_r->shipping + $_country->shipping_add);
				            	}
				            	else
				            	{
				            		$str_country .= "{{p-".$_r->parameter_from."}-0.1}*{{".$_r->parameter_to."-p}-0.6}*".($_r->shipping + $_country->shipping_add);
				            	}
			            	}
			            	else
			            	{
				            	if($_r->parameter_from == 0)
				            	{
				            		$str_country .= "{{".$_r->parameter_to."-p}-0.6}*".($_r->shipping + $_country->shipping_add).'+';
				            	}
				            	else
				            	{
				            		$str_country .= "{{p-".$_r->parameter_from."}-0.1}*{{".$_r->parameter_to."-p}-0.6}*".($_r->shipping + $_country->shipping_add).'+';
				            	}
			            	}		            	
			            }
						$delivery_temp['delivery_country'][$key_c]['expression']      = $str_country;				
	            	}	            	
	            }
	            if($i == 0)
	            {
	            	$delivery_temp['type']              = 0; 
	            }
            }            
            
            $this->data[$keyc]     = $delivery_temp;
        }
        $this->end    = count($this->data);
    }


    /**
     * 获取下一条记录的ID
     * 
     * @return int,bool  当不具备下一条记录时，返回 false;
     */
    public function next_id()
    {
        $this->current_id ++;
        if($this->current_id<$this->end)
        {
            return $this->current_id; 
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * 通过ID获取数组
     * 
     * @param int $id
     * @return array
     */
    public function get($id)
    {
        if(isset($this->data[$id]))
        {
            return $this->data[$id];
        }
        else
        {
            return array();
        }
    }
}

