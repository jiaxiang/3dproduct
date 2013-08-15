<?php defined('SYSPATH') OR die('No direct access allowed.');
class AddressDataTransport_Core extends DefaultDataTransport_Service {
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
        $sql    = "SELECT `addresses`.* FROM (`addresses`) WHERE `site_id` = $site_id ORDER BY `addresses`.`id` ASC"; 
        $addresses = $this->db->query($sql);
        foreach($addresses as $keyc=>$_address)
        {
            $address_temp = array();
            $address_temp['id']                 = $_address->id;
            $address_temp['site_id']            = $_address->site_id;
            $address_temp['user_id']            = $_address->user_id;
            $address_temp['firstname']          = $_address->firstname;
            $address_temp['lastname']           = $_address->lastname;
            $address_temp['country']            = $_address->country;
            $address_temp['state']              = $_address->state;
            $address_temp['city']               = $_address->city;
            $address_temp['address']            = $_address->address;
            $address_temp['zip']                = $_address->zip;
            $address_temp['phone']              = $_address->phone;
            $address_temp['phone_mobile']       = $_address->phone_mobile;
            $address_temp['other']              = $_address->other;
            $address_temp['date_add']           = date('Y-m-d H:m:s',$_address->date_add);
            $address_temp['date_upd']           = date('Y-m-d H:m:s',$_address->date_upd);
            $address_temp['ip']                 = $_address->ip_add;
            $this->data[$keyc]              = $address_temp;
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
