<?php defined('SYSPATH') OR die('No direct access allowed.');

class Myseo_manage_Core extends My{
    //对象名称(表名)
    protected $object_name = 'seo_manage';

    protected static $instances;
    protected $data = array();
	/**
     * 单实例方法
     * @param $id
     */
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
	private function _load($id)
	{
		$id = intval($id);

		$site = ORM::factory('seo_manage',$id)->as_array();
		$this->data = $site;
	}

	/**
	 * get site data
	 *
	 * @param Array $query_struct
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @return Array
	 */
	private function _data($query_struct=array(),$orderby=NULL,$limit=1000,$offset=0)
	{
		$list = array();
		$where = array();
		$like = array();
		$in = array();

		$site = ORM::factory('seo_manage');
		//WHERE
		if(count($query_struct) > 0)
		{
			if(isset($query_struct['where']))
			{
				foreach($query_struct['where'] as $key=>$value)
				{
					$where[$key] = $value;
				}
			}
		}
		//LIKE
		if(count($query_struct) > 0)
		{
			if(isset($query_struct['like']))
			{
				foreach($query_struct['like'] as $key=>$value)
				{
					$like[$key] = $value;
				}
			}
		}
		//IN
		if(count($query_struct) > 0)
		{
			if(isset($query_struct['in']))
			{
				foreach($query_struct['in'] as $key=>$value)
				{
					$in[$key] = $value;
				}
			}
		}
		//WHERE
		if(count($where) > 0)
		{
			$site->where($where);
		}
		//LIKE
		if(count($like) > 0)
		{
			$site->like($like);
		}
		//IN
		if(count($in) > 0)
		{
			foreach($in as $key=>$value)
			{
				$site->in($key,$value);
			}
		}

		if(!empty($orderby))
		{
			$site->orderby($orderby);
		}

		$orm_list = $site->find_all($limit,$offset);

		foreach($orm_list as $item)
		{
			$list[] = $item->as_array();
		}

		return $list;
	}

	/**
	 * get the total number
	 *
	 * @param Array $query_struct
	 * @return Int
	 */
	function count($query_struct = array())
	{
		$site = ORM::factory('seo_manage');

		$where = array();
		$like = array();
		$in = array();

		$site = ORM::factory('seo_manage');
		//WHERE
		if(count($query_struct) > 0)
		{
			if(isset($query_struct['where']))
			{
				foreach($query_struct['where'] as $key=>$value)
				{
					$where[$key] = $value;
				}
			}
		}
		//LIKE
		if(count($query_struct) > 0)
		{
			if(isset($query_struct['like']))
			{
				foreach($query_struct['like'] as $key=>$value)
				{
					$like[$key] = $value;
				}
			}
		}
		//IN
		if(count($query_struct) > 0)
		{
			if(isset($query_struct['in']))
			{
				foreach($query_struct['in'] as $key=>$value)
				{
					$in[$key] = $value;
				}
			}
		}
		//WHERE
		if(count($where) > 0)
		{
			$site->where($where);
		}
		//LIKE
		if(count($like) > 0)
		{
			$site->like($like);
		}
		//IN
		if(count($in) > 0)
		{
			foreach($in as $key=>$value)
			{
				$site->in($key,$value);
			}
		}

		$count = $site->count_all();
		return $count;
	}

	/**
	 * list site
	 *
	 * @param Array $query_struct
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	public function seo_manages($query_struct = array(),$orderby=NULL,$limit=1000,$offset=0)
	{
		$list = array();
		$this->union_query = true;

		$list = $this->_data($query_struct,$orderby,$limit,$offset);
		return $list;
	}
    
    public function add($data)
    {
        return $this->create($data);
    }
    
    public function create($request_data){
        try {
            $orm_instance = ORM::factory($this->object_name);
            $data = $orm_instance->as_array();
            foreach ($request_data as $key=>$val) {
                array_key_exists($key,$data) && $orm_instance->$key = $val;
            }
            $orm_instance->save();
            if($orm_instance->saved !== TRUE){
                throw new MyRuntimeException('internal error',500);
            }
            //TODO 逻辑与数据分离：状态与数据分离
            return $orm_instance->id;
        }catch(MyRuntimeException $ex){
            //TODO 自定义逻辑
            throw $ex;
        }
    }
    
	public function update($data)
	{
		$id = intval($this->data['id']);

		return $this->_update($id,$data);
	}
	
	/**
	 * update manager by id
	 *
	 * @param <Int> $id
	 * @param <Array> $data
	 *
	 * @return <Boolean>
	 */
	public function _update($id,$data = array())
	{
		$manager = ORM::factory('seo_manage',$id);

		if(count($data) > 0)
		{
			foreach($data as $key=>$value)
			{
				$manager->$key = $value;
			}
		}
		$manager->save();
		return $manager->as_array();
	}
    
	public function update_seo_manage_by_site_id($site_id){
		$seo_manages = self::get_seo_manage_by_site_id($site_id);
		foreach($seo_manages as $key => $seo_manage){
			$request_struct = array(
	            'where'		=> array( 
	                'site_id'	=> $site_id,
					'id'        => $key,
	            ),
	            'like'		=> array(
	            ),
	            'orderby'   => array(
	                'update_timestamp'		=>'ASC',
	            ),
	            'limit'     => array(
	            ),
        	);
        	$result = $this->lists($request_struct);
        	foreach($result as $result){
        		$request_data = array(
		            'date_upd'             => $result['update_timestamp'],
					'meta_title'           => $result['meta_title'],
					'meta_keywords'        => $result['meta_keywords'],
					'meta_description'     => $result['meta_description'],
        		);
        	}       	
			foreach($seo_manage['id'] as $id){
				$query_struct['where']['category_id'] = $id;
				$category_products = Mycategory_product::instance()->lists($query_struct);
				foreach($category_products as $key=>$value)
				{
					$products[] = Myproduct::instance($value['product_id'])->get();
				}

				foreach($products as $key=>$val){
					Myproduct::instance($val['id'])->edit($request_data);
				}
			}
		}
	}
	
    public function get_seo_manage_by_site_id($site_id){
    	$seo_manages = self::get_category_seo_by_site_id($site_id);
    	$arrs = array();
    	foreach ($seo_manages as $key => $seo_manage) {
    		foreach ($seo_manage['id'] as $id) {
    			if (isset($arrs[$id])) {
    				if ($seo_manage['update_timestamp'] > $seo_manages[$arrs[$id]]['update_timestamp']) {
    					unset($seo_manages[$arrs[$id]]['id'][$id]);
    					$arrs[$id] = $key;
    				} else {
    					unset($seo_manages[$key]['id'][$id]);
    				}
    			} else {
    				$arrs[$id] = $key;
    			}
    		}
    	}
		return $seo_manages;
    }
    

    
    public function get_category_seo_by_site_id($site_id){
    	$request_struct = array(
            'where'		=> array( 
                'site_id'	=> $site_id,
            ),
            'like'		=> array(
            ),
            'orderby'   => array(
                'update_timestamp'		=>'ASC',
            ),
            'limit'     => array(
            ),
        );
        
    	try{
            $result = $this->lists($request_struct);
            if(!empty($result)){
                foreach($result as $val){
                	$return_array[$val['id']]['id'][$val['parent_id']] = $val['parent_id'];
                	$return_array[$val['id']]['update_timestamp'] = $val['update_timestamp'];
                	if(!empty($val['is_contain_child'])){               		
                		$child_ids = Mycategory::instance()->site_subcategories($val['site_id'], $val['parent_id']);
                		foreach($child_ids as $value){
                			$return_array[$val['id']]['id'][$value['id']] = $value['id'];
                		}              		
                	}                 
                }
            }
            return $return_array;
        }catch(MyRuntimeException $ex){
            throw $ex;
        }
    }
}
