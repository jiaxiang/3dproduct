<?php defined('SYSPATH') OR die('No direct access allowed.');
class CurrencyDataTransport_Core extends DefaultDataTransport_Service {
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
        $sql    = "SELECT `currencies`.* FROM (`currencies`) WHERE `site_id` = $site_id ORDER BY `currencies`.`id` ASC"; 
        $currencies = $this->db->query($sql); 
        foreach($currencies as $keyc=>$_currency)
        {
            $currency_temp                      = array();
            $currency_temp['id']                = $_currency->currency_id;
            $currency_temp['site_id']           = $_currency->site_id;
            $currency_temp['name']              = $_currency->name;
            $currency_temp['code']              = $_currency->name;
            $currency_temp['sign']              = $_currency->sign;
            $currency_temp['format']            = $_currency->format;
            $currency_temp['decimals']          = $_currency->decimals;
            $currency_temp['conversion_rate']   = $_currency->conversion_rate;
            $currency_temp['default']           = $_currency->default;
            $currency_temp['active']            = 1;
            $this->data[$keyc]     = $currency_temp;
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
