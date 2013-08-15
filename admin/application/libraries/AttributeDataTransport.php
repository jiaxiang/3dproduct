<?php defined('SYSPATH') OR die('No direct access allowed.');
class AttributeDataTransport_Core extends DefaultDataTransport_Service {
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
        $sql    = "SELECT `attribute_groups`.* FROM (`attribute_groups`) WHERE `site_id` = $site_id ORDER BY `attribute_groups`.`id` ASC"; 
        $attribute_groups = $this->db->query($sql); 
        /*
        $attribute_groups = ORM::factory('attribute_group')
            ->where('site_id',$site_id)
            ->find_all();
        */
        //echo $this->db->last_query();
        //exit;
        foreach($attribute_groups as $keyag=>$_attribute_group)
        {
            
            $attribute_group_temp                   = array();
            $attribute_group_temp['id']             = $_attribute_group->attribute_group_id;
            $attribute_group_temp['site_id']        = $_attribute_group->site_id;
            $attribute_group_temp['name']           = $_attribute_group->name;
            //??
            $attribute_group_temp['name_manage']    = $_attribute_group->name;
            $attribute_group_temp['position']    	= 0;
            $attribute_group_temp['meta_struct']    = '';
            $attribute_group_temp['options']        = array();

            /*
            $attributes=ORM::factory('attribute')
                ->where('site_id',$site_id)
                ->where('attribute_group_id',$_attribute_group->id)
                ->orderby('position','ASC')
                ->find_all();
            */
            $sql    = "SELECT `attributes`.* FROM (`attributes`) WHERE `site_id` = $site_id AND `attribute_group_id` = '$_attribute_group->attribute_group_id' AND `name` != 'Default' ORDER BY `position` ASC"; 
            $attributes = $this->db->query($sql); 
            //echo $this->db->last_query();
            //exit;
            foreach($attributes as $keya => $_attribute)
            {
                $attribute_group_temp['options'][$keya]['id']           = $_attribute->attribute_id;  
                $attribute_group_temp['options'][$keya]['site_id']      = $_attribute->site_id;  
                $attribute_group_temp['options'][$keya]['name']         = $_attribute->name;  
                $attribute_group_temp['options'][$keya]['name_manage']  = $_attribute->name;  
                $attribute_group_temp['options'][$keya]['position']    	= $_attribute->position;
                $attribute_group_temp['options'][$keya]['meta_struct']  = '';  
            }
            if(!empty($attribute_group_temp['options']))
            {
                $this->data[]     = $attribute_group_temp; 
            }
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
