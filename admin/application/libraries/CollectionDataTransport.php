<?php defined('SYSPATH') OR die('No direct access allowed.');
class CollectionDataTransport_Core extends DefaultDataTransport_Service {
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
        /*
        $categorys = ORM::factory('category')
            ->where('site_id',$site_id)
            ->where('virtual',1)
            ->find_all();
         */
        $sql    = "SELECT `categories`.* FROM (`categories`) WHERE `site_id` = $site_id AND `virtual` = 1 ORDER BY `categories`.`id` ASC";
        $categories = $this->db->query($sql); 
        foreach($categories as $keyc=>$_category)
        {
            $category_temp                      = array();
            $category_temp['id']                = $_category->category_id;
            $category_temp['site_id']           = $_category->site_id;
            $category_temp['title']             = $_category->name;
            $category_temp['label']             = 1;
            $category_temp['description']       = $_category->description;
            $category_temp['meta_title']        = $_category->meta_title;
            $category_temp['meta_keywords']     = $_category->meta_keywords;
            $category_temp['meta_description']  = $_category->meta_description;
            $category_temp['memo']              = $_category->name;
            $category_temp['create_timestamp']  = time();
            $category_temp['update_timestamp']  = time();

            //分类商品
            $product_id_list    = array();
            /*
            $product_id_list = ORM::factory('category_product')
                ->where('site_id',$site_id)
                ->where('category_id',$_category->id)
                ->select_list('product_id','product_id');
             */
            $sql    = "SELECT `product_id`, `product_id` FROM (`category_products`) WHERE `site_id` = $site_id AND `category_id` = $_category->category_id ORDER BY `category_products`.`id` ASC";
            $query = $this->db->query($sql); 
            foreach($query as $key=>$_query)
            {
                $product_id_list[$_query->product_id]   = $_query->product_id;
            }
            if(count($product_id_list))
            {
                /*
                $product_id_list = ORM::factory('product')
                    ->where('site_id',$site_id)
                    ->in('id',$product_id_list)
                    ->select_list('id','id');
                 */
                $sql    = "SELECT `id`, `product_id` FROM (`products`) WHERE `site_id` = $site_id AND `product_id` IN (".join($product_id_list,',').") ORDER BY `products`.`id` ASC";
                $query = $this->db->query($sql); 
                $product_id_list = array(); 
                foreach($query as $key=>$_query)
                {
                    $product_id_list[$_query->product_id]   = $_query->product_id;
                }
            }
            $category_temp['product']  = $product_id_list;

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
}
