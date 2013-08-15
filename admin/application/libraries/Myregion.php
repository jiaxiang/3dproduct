<?php defined('SYSPATH') OR die('No direct access allowed.');

class Myregion_Core {
    private $data = array();
    private $errors = array();

    private static $instances;
    public static function & instance($id = 0)
    {
        if (!isset(self::$instances[$id]))
        {
            $class = __CLASS__;
            self::$instances[$id] = new $class($id);
        }
        return self::$instances[$id];
    }

    /**
     * Construct load site data
     *
     * @param Int $id
     */
    public function __construct($id)
    {
        $this->_load($id);
    }

    /**
     * load site data
     *
     * @param Int $id
     */
    private function _load($id){

        $this->data = ORM::factory('region', intval($id))->as_array();
    }

    /**
     * 遍历操作
     *
     * @param <Int> $id ID
     * @return Array
     */
    public function areas($id = 0, $orderby=array('position'=>'DESC'))
    {
        $result = array();
        $region = ORM::factory('region');

        $list = $region->where('p_region_id', $id)->orderby($orderby)->find_all();

        foreach($list as $item){
            $temp = $item->as_array();
            $temp['childs'] = $region->where('p_region_id', $temp['id'])->count_all();
            $result[] = $temp;
        }
        return $result;
    }

    /**
     * get region data
     *
     * @return Array
     */
    public function get($key=NULL)
    {
        if(empty($key))
        {
            return $this->data;
        }
        else
        {
            return isset($this->data[$key])?$this->data[$key]:'';
        }
    }
     
    /**
     * get sub region by region id
     *
     * @param Int $id
     * @return Array
     */
    public function sub_areas($id)
    {
        $list = array();

        $sub_regions = ORM::factory('region')
            ->where(array('p_region_id'=>$id, 'disabled'=>'false'))
            ->orderby(array('position'=>'DESC'))
            ->find_all();
        foreach($sub_regions as $item){
            $list[] = $item->as_array();
        }
        return $list;
    }

    /**
     * get data by local_name, en_name
     *
     * @param String local_name, en_name
     * @return Array
     */
    public function check_name($field, $name, $pid=0, $id=0){
        $region = ORM::factory('region')->where(array($field => $name))->where(array('p_region_id' => $pid));
        $id>0 && $region->where(array('id!=' => $id));
        return $region->count_all()>0?TRUE:FALSE;
    }

    /**
     * add a item
     *
     * @param Array $data
     * @return Array
     */
    public function add($data){
        $errors = '';
        $region_path = '';
        $region_grade = 1;
        $parent_id = $data['p_region_id'];
        if($parent_id > 0){
            $parent = ORM::factory('region', $parent_id);
            if($parent->loaded){
                $region_grade = $parent->region_grade+1;
                $region_path = $parent->region_path;
            }
        }
        $data['region_grade'] = $region_grade;

        //ADD
        $region = ORM::factory('region');
        if($region->validate($data, TRUE, $errors)){
            $data = $region->as_array();
            $region->id = $data['id'];
            $region->region_path = ($region_path?$region_path:',').$data['id'].',';
            $region->save();
            $this->data = $region->as_array();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * edit a item
     *
     * @param Array $data
     * @param Int $id
     * @return Array
     */
    public function update($id, $data)
    {
        $id = intval($id);
        $region = ORM::factory('region', $id);
        if(!$region->loaded){
            return FALSE;
        }
        
        $errors = '';
        if($region->validate($data, TRUE, $errors)){
            $subs = $region->like(array('region_path' => ",{$id},"))->find_all();            
            foreach($subs as $sub){
                $sub->disabled = $data['disabled'];
                $sub->save();
            }
            $this->data = $region->as_array();
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * edit a item
     *
     * @param Array $data
     * @param Int $id
     * @return Array
     */
    public function edit($data)
    {
        $id = $data['id']?$data['id']:$this->data['id'];d($id);
        return $this->update($id,$data);
    }
    
    /**
     * set active
     */
    function set_active($id, $flag = 1)
    {
        if($id<=0)
        {
            return false;
        }
        $region = ORM::factory('region', $id);
        if($region->loaded)
        {
            $region->disabled = ($flag==1?'true':'false');
            return $region->save();
        }
        else
        {
            return false;
        }
    }
    
    public function set_order($id, $position){
        $obj = ORM::factory('region')->where(array('id'=>$id))->find();
        if($obj->loaded){
            $obj->position = $position;
            return $obj->save();
        }
        return false;
    }

    /**
     * delete a item
     *
     * @param Int $id
     * @return Boolean
     */
    function delete($id){
        $id = intval($id);
        if(!$id){
            return FALSE;
        }
        $region = ORM::factory('region');
        $region->like(array('region_path' => ','.$id.','))->delete_all();
        return TRUE;
    }

    /**
     * get api error
     *
     * @return Array
     */
    public function errors(){
        $result = '';
        if(count($this->errors))
        {
            $result     = '<br />';
            foreach($this->errors as $key=>$value)
            {
                $result .= ($key+1).' . '.$value.'<br />';
            }
        }
        return $result;
    }
}
