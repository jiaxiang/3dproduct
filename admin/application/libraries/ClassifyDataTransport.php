<?php defined('SYSPATH') OR die('No direct access allowed.');
class ClassifyDataTransport_Core extends DefaultDataTransport_Service {
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
        $sql    = "SELECT `categories`.* FROM (`categories`) WHERE `site_id` = $site_id AND `virtual` = 0 AND `level_depth` != 1 ORDER BY `categories`.`id` ASC"; 
        $categorys = $this->db->query($sql); 
        /*
        $categorys = ORM::factory('category')
            ->where('site_id',$site_id)
            ->where('virtual',0)
            ->find_all();
        echo $this->db->last_query();
        exit;
         */
        foreach($categorys as $keyc=>$_category)
        {
            $category_temp                          = array();
            $category_temp['id']                    = $_category->category_id;
            $category_temp['site_id']               = $_category->site_id;
            $category_temp['name']                  = $_category->name;
            $category_temp['create_timestamp']      = time();
            $category_temp['update_timestamp']      = time();

            $category_temp['attribute_ids']         = $this->classify_attribute($site_id,$_category->category_id);
            $category_temp['feature_ids']           = $this->classify_feature($site_id,$_category->category_id);
            $category_temp['brand_ids']             = $this->classify_brand($site_id,$_category->category_id);

            $this->data[$keyc]     = $category_temp;
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

    /**
     * 通过分类ID得到所有的下级cata
     * 
     * @param int $site_id
     * @param int $category_id
     * @return array
     */
    public function cata_list($site_id,$category_id){
        $result=array();
        $sql =  "SELECT `id`, `category_id` FROM (`categories`) WHERE `site_id` = $site_id AND `parent_id` = '$category_id' ORDER BY `categories`.`id` ASC";
        $query = $this->db->query($sql);
        foreach($query as $_query) {
            $result[$_query->category_id] = $_query->category_id;
            $temp=$this->cata_list($site_id,$_query->category_id);
            if(is_array($temp)&&count($temp)) {			
                $result = array_merge($result, $temp);
            }
        }
        return $result;
    }


    /**
     * 通过分类ID得到与类型关联的规格组
     * 
     * @param int $site_id
     * @param int $category_id
     * @return array
     */
    private function classify_attribute($site_id,$category_id)
    {        
        //$this->db = Database::instance();
        //把这个分类下的所有子分类列出
        $category_id_list   = array();
        $category_id_list[$category_id] = $category_id;
        if(count($this->cata_list($site_id,$category_id)))
        {
            $category_id_list = array_merge($category_id_list,$this->cata_list($site_id,$category_id));
        }
        //找到所有的商品
        $sql    =  "SELECT `product_id`,`product_id` FROM (`products`) WHERE `site_id` = $site_id AND `category_default_id` IN (";
        $sql    .= join($category_id_list,',').") GROUP BY `product_id` ORDER BY `product_id` ASC";
        $query = $this->db->query($sql); 

        $product_id_list    = array();
        foreach($query as $_query)
        {
            $product_id_list[$_query->product_id]   = $_query->product_id; 
        }
        /*
        $product_id_list  = ORM::factory('product')
            ->where('site_id',$site_id)
            ->where('default_category_id',$category_id)
            ->select_list('id','id');
        echo $this->db->last_query();
        exit;
         */
        //无分类商品处理
        if(!count($product_id_list))
        {
            return array();
        }
        $attribute_id_list       = array();
        $sql    = "SELECT `id`, `id` FROM (`product_attributes`) WHERE `site_id` = $site_id AND `product_id` IN (";
        $sql    .= join($product_id_list,',').") ORDER BY `product_attributes`.`id` ASC";
        $query = $this->db->query($sql);
        $product_attribute_id_list = array();
        foreach($query as $_query)
        {
            $product_attribute_id_list[$_query->id] = $_query->id;
        } 
        $sql    = "SELECT `attribute_id`, `attribute_id` FROM (`product_attribute_combinations`) WHERE `site_id` = $site_id AND `attribute_id` != 1 AND `product_attribute_id` IN (";
        $sql    .= join($product_attribute_id_list,',').") GROUP BY `attribute_id` ORDER BY `product_attribute_combinations`.`id` ASC";
        $query      = $this->db->query($sql); 
        foreach($query as $_query)
        {
            $attribute_id_list[$_query->attribute_id]   = $_query->attribute_id; 
        }
        /*
        $attribute_id_list=ORM::factory('product_attribute_combination')
            ->where('site_id',$site_id)
            ->in('product_id',$product_id_list)
            ->groupby('attribute_id')
            ->select_list('attribute_id','attribute_id');
         */
        //无商品规格处理
        if(!count($attribute_id_list))
        {
            return array();
        }
        /*
        $attribute_group_id_list = ORM::factory('attribute')
            ->where('site_id',$site_id)
            ->in('id',$attribute_id_list)
            ->groupby('attribute_group_id')
            ->select_list('attribute_group_id','attribute_group_id');
         */
        $sql = "SELECT `attribute_group_id`, `attribute_group_id` FROM (`attributes`) WHERE `site_id` = $site_id AND `attribute_group_id` != 1 AND `attribute_id` IN (".join($attribute_id_list,',').") GROUP BY `attribute_group_id` ORDER BY `attributes`.`id` ASC";
        $query      = $this->db->query($sql); 
        $attribute_group_id_list    = array();
        foreach($query as $_query)
        {
            $attribute_group_id_list[$_query->attribute_group_id]   = $_query->attribute_group_id; 
        }
        if(!count($attribute_group_id_list))
        {
            return array();
        }
        //规格组真实情验证
        $return     = array();
        $sql = "SELECT `id`, `attribute_group_id` FROM (`attribute_groups`) WHERE `site_id` = $site_id AND `attribute_group_id` IN (".join($attribute_group_id_list,',').") ORDER BY `attribute_groups`.`id` ASC";

        $query      = $this->db->query($sql); 
        foreach($query as $_query)
        {
            $return[$_query->attribute_group_id]   = $_query->attribute_group_id; 
        }
        return $return;
        /*
         ORM::factory('attribute_group')
            ->where('site_id',$site_id)
            ->in('id',$attribute_group_id_list)
            ->select_list('id','id');
        echo $this->db->last_query();
        exit;
         */
    }

    /**
     * 通过分类ID得到与类型关联的属性组
     * 
     * @param int $site_id
     * @param int $category_id
     * @return array
     */
    private function classify_feature($site_id,$category_id)
    {     
        //把这个分类下的所有子分类列出
        $category_id_list   = array();
        $category_id_list[$category_id] = $category_id;
        $category_id_list = array_merge($category_id_list,$this->cata_list($site_id,$category_id));
        //找到所有的商品
        $sql    =  "SELECT `product_id`,`product_id` FROM (`products`) WHERE `site_id` = $site_id AND `category_default_id` IN (";
        $sql    .= join($category_id_list,',').") GROUP BY `product_id` ORDER BY `product_id` ASC";
        $query = $this->db->query($sql); 


        $product_id_list = array();
        /*    
        $product_id_list  = ORM::factory('product')
            ->where('site_id',$site_id)
            ->where('default_category_id',$category_id)
            ->select_list('id','id');
         */
        foreach($query as $_query)
        {
            $product_id_list[$_query->product_id]   = $_query->product_id; 
        }

        //无分类商品处理
        if(!count($product_id_list))
        {
            return array();
        }
        /*
        $feature_id_list=ORM::factory('product_feature')
            ->where('site_id',$site_id)
            ->in('product_id',$product_id_list)
            ->groupby('feature_id')
            ->select_list('feature_id','feature_id');
         */
        $feature_id_list  = array();
        $sql = "SELECT `feature_id`, `feature_id` FROM (`product_features`) WHERE `site_id` = $site_id AND `product_id` IN (".join($product_id_list,',').") GROUP BY `feature_id` ORDER BY `product_features`.`id` ASC";
        $query      = $this->db->query($sql); 
        foreach($query as $_query)
        {
            $feature_id_list[$_query->feature_id]   = $_query->feature_id; 
        }
        //无商品属性处理
        if(!count($feature_id_list))
        {
            return array();
        }

        $feature_group_id_list   = array();
        /*
        $feature_group_id_list   = ORM::factory('feature')
            ->where('site_id',$site_id)
            ->in('id',$feature_id_list)
            ->groupby('feature_group_id')
            ->select_list('feature_group_id','feature_group_id');
         */
        $sql    = "SELECT `feature_group_id`, `feature_group_id` FROM (`features`) WHERE `site_id` = $site_id AND `feature_group_id` NOT IN (4,2) AND `feature_id` IN (".join($feature_id_list,',').") GROUP BY `feature_group_id` ORDER BY `features`.`id` ASC"; 
        $query      = $this->db->query($sql); 
        foreach($query as $_query)
        {
            $feature_group_id_list[$_query->feature_group_id]   = $_query->feature_group_id; 
        }
        if(!count($feature_group_id_list))
        {
            return array();
        }
        //属性组真实情验证
        $return     = array();
        /*
        ORM::factory('feature_group')
            ->where('site_id',$site_id)
            ->in('id',$feature_group_id_list)
            ->select_list('id','id');
         */
        $sql = "SELECT `id`, `feature_group_id` FROM (`feature_groups`) WHERE `site_id` = $site_id AND `feature_group_id` IN (".join($feature_group_id_list,',').") ORDER BY `feature_groups`.`id` ASC";
        $query      = $this->db->query($sql); 
        foreach($query as $_query)
        {
            $return[$_query->feature_group_id]   = $_query->feature_group_id; 
        }
        return $return;
    }
    /**
     * 通过分类ID得到与类型关联的品牌
     * 
     * @param int $site_id
     * @param int $category_id
     * @return array
     */
    private function classify_brand($site_id,$category_id)
    {     
        //把这个分类下的所有子分类列出
        $category_id_list   = array();
        $category_id_list[$category_id] = $category_id;
        $category_id_list = array_merge($category_id_list,$this->cata_list($site_id,$category_id));
        //找到所有的商品
        $sql    =  "SELECT `product_id`,`product_id` FROM (`products`) WHERE `site_id` = $site_id AND `category_default_id` IN (";
        $sql    .= join($category_id_list,',').") GROUP BY `product_id` ORDER BY `product_id` ASC";
        $query = $this->db->query($sql); 


        $product_id_list = array();
        /*    
        $product_id_list  = ORM::factory('product')
            ->where('site_id',$site_id)
            ->where('default_category_id',$category_id)
            ->select_list('id','id');
         */
        foreach($query as $_query)
        {
            $product_id_list[$_query->product_id]   = $_query->product_id; 
        }


        //无分类商品处理
        if(!count($product_id_list))
        {
            return array();
        }
        /*
        $feature_id_list=ORM::factory('product_feature')
            ->where('site_id',$site_id)
            ->in('product_id',$product_id_list)
            ->groupby('feature_id')
            ->select_list('feature_id','feature_id');
         */
        $feature_id_list  = array();
        $sql = "SELECT `feature_id`, `feature_id` FROM (`product_features`) WHERE `site_id` = $site_id AND `product_id` IN (".join($product_id_list,',').") GROUP BY `feature_id` ORDER BY `product_features`.`id` ASC";
        $query      = $this->db->query($sql);
        foreach($query as $_query)
        {
            $feature_id_list[$_query->feature_id]   = $_query->feature_id; 
        }
        

        //无商品属性处理
        if(!count($feature_id_list))
        {
            return array();
        }

        $brand_id_list   = array();
        /*
        $feature_group_id_list   = ORM::factory('feature')
            ->where('site_id',$site_id)
            ->in('id',$feature_id_list)
            ->groupby('feature_group_id')
            ->select_list('feature_group_id','feature_group_id');
         */
        $sql    = "SELECT `feature_id`, `feature_id` FROM (`features`) WHERE `site_id` = $site_id AND `feature_group_id` IN (4,2) AND `feature_id` IN (".join($feature_id_list,',').") GROUP BY `feature_id` ORDER BY `features`.`id` ASC"; 
        $query      = $this->db->query($sql); 
        foreach($query as $_query)
        {
            $brand_id_list[$_query->feature_id]   = $_query->feature_id; 
        }
        if(!count($brand_id_list))
        {
            return array();
        }
        //属性组真实情验证
        //$return     = array();
        /*
        ORM::factory('feature_group')
            ->where('site_id',$site_id)
            ->in('id',$feature_group_id_list)
            ->select_list('id','id');
         */
        /*$sql = "SELECT `id`, `id` FROM (`feature_groups`) WHERE `site_id` = $site_id AND `id` IN (".join($feature_group_id_list,',').") ORDER BY `feature_groups`.`id` ASC";
        $query      = $this->db->query($sql); 
        foreach($query as $_query)
        {
            $return[$_query->id]   = $_query->id; 
        }
         */
        return $brand_id_list;
    }
}
