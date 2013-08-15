<?php defined('SYSPATH') OR die('No direct access allowed.');
class BrandDataTransport_Core extends DefaultDataTransport_Service {
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
        if(!isset(self::$instances[$site_id])){
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
        $sql    = "SELECT `feature_groups`.`feature_group_id` FROM (`feature_groups`) WHERE `site_id` =$site_id AND `name` = 'BRAND' ORDER BY `feature_groups`.`id` ASC"; 
        $query   = $this->db->query($sql);
        $brand_gro_list = array();
        foreach($query as $_query)
        {
            $brand_gro_list[$_query->feature_group_id] = $_query->feature_group_id;
        }
        $sql    =  "SELECT `features`.* FROM (`features`) WHERE `site_id` = $site_id AND `feature_group_id` IN (";
        $sql    .= join($brand_gro_list,',').") GROUP BY `feature_id` ORDER BY `feature_id` ASC";
        $brands = $this->db->query($sql);
        foreach($brands as $keyb=>$_brand)
        {
            $brand_temp         = array();
            $brand_temp['id']   = $_brand->feature_id;
            $brand_temp['site_id']  = $site_id;
            $brand_temp['name']     = substr(strip_tags($_brand->name), 0, 100);
            $brand_temp['create_timestamp']  = time();
            $brand_temp['update_timestamp']  = time();
            $this->data[$keyb]     = $brand_temp;
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
