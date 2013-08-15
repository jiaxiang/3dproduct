<?php defined('SYSPATH') OR die('No direct access allowed.');
class FeatureDataTransport_Core extends DefaultDataTransport_Service {
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
        $sql    = "SELECT `feature_groups`.* FROM (`feature_groups`) WHERE `site_id` =$site_id AND `name` != 'BRAND' ORDER BY `feature_groups`.`id` ASC"; 
        $feature_groups = $this->db->query($sql); 
        /*
        $feature_groups = ORM::factory('feature_group')
            ->where('site_id',$site_id)
            ->find_all();
        echo $this->db->last_query();
        exit;
        */
        foreach($feature_groups as $keyfg=>$_feature_group)
        {
            $feature_group_temp           = array();
            $feature_group_temp['id']     = $_feature_group->feature_group_id;
            $feature_group_temp['site_id']= $_feature_group->site_id;
            $feature_group_temp['name']   = substr(strip_tags($_feature_group->name), 0, 100);
            $feature_group_temp['name_manage']    = strip_tags($_feature_group->name);
            $feature_group_temp['meta_struct']    = '';
            $feature_group_temp['position']    	  = 0;
            $feature_group_temp['options']        = array();

            $sql    = "SELECT `features`.* FROM (`features`) WHERE `site_id` = $site_id AND `feature_group_id` = '$_feature_group->feature_group_id' ORDER BY `position` ASC";
            $features = $this->db->query($sql);
            /*
            $features=ORM::factory('feature')
                ->where('site_id',$site_id)
                ->where('feature_group_id',$_feature_group->id)
                ->orderby('position','ASC')
                ->find_all();
             */
            foreach($features as $keya => $_feature)
            {
                $feature_group_temp['options'][$keya]['id']           = $_feature->feature_id;  
                $feature_group_temp['options'][$keya]['site_id']      = $_feature->site_id;  
                $feature_group_temp['options'][$keya]['name']         = substr(strip_tags($_feature->name), 0, 100);  
                $feature_group_temp['options'][$keya]['name_manage']  = substr(strip_tags($_feature->name), 0, 100);  
            	$feature_group_temp['options'][$keya]['position']     = $_feature->position;
                $feature_group_temp['options'][$keya]['meta_struct']  = '';  
            }
            $this->data[$keyfg]     = $feature_group_temp; 
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
