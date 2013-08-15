<?php defined('SYSPATH') OR die('No direct access allowed.');
class CarrierDataTransport_Core extends DefaultDataTransport_Service {
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
     * @param Int $id
     */
    public function __construct($site_id)
    {
        $this->db = Database::instance('old');
        $sql    = "SELECT `carriers`.* FROM (`carriers`) WHERE `site_id` = $site_id ORDER BY `carriers`.`id` ASC"; 
        $carriers = $this->db->query($sql); 
        foreach($carriers as $keyc=>$_carrier)
        {
            $carrier_temp                      = array();
            $carrier_temp['id']                = $_carrier->carrier_id;
            $carrier_temp['site_id']           = $_carrier->site_id;
            $carrier_temp['name']              = $_carrier->name;
            $carrier_temp['url']               = $_carrier->url;
            $carrier_temp['delay']             = $_carrier->delay;
            $carrier_temp['type']              = 0;
            $carrier_temp['price']             = 0;
            $carrier_temp['country_relative']  = 1;
            $carrier_temp['position']          = $_carrier->position;
            $carrier_temp['active']            = 1;
            $carrier_temp['carrier_country']   = array();
            $sql    = "SELECT `carrier_countries`.* FROM (`carrier_countries`) WHERE `site_id` = $site_id AND `carrier_id` = $_carrier->carrier_id ORDER BY `carrier_countries`.`id` ASC";
            $countries = $this->db->query($sql);
            foreach($countries as $key_c=>$_country)
            {
                $carrier_temp['carrier_country'][$key_c]['id']              = $_country->id;
                $carrier_temp['carrier_country'][$key_c]['country_id']      = $_country->country_id;
                $carrier_temp['carrier_country'][$key_c]['shipping_add']    = $_country->shipping_add;
                $carrier_temp['carrier_country'][$key_c]['position']        = 0;

            }

            $this->data[$keyc]     = $carrier_temp;
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
