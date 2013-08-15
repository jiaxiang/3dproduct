<?php 
defined('SYSPATH') OR die('No direct access allowed.');

class Mydomain_Core extends My{
	protected $object_name = 'domain';
    protected $data = array();
    protected $error = array();
    
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
    private function _load($id)
    {
        $id = intval($id);
        
        $domain = ORM::factory('domain', $id)->as_array();
        $this->data = $domain;
    }
    
    /**
     * get domain data
     *
     * @param Array $query_struct
     * @param Array $orderby
     * @param Int $limit
     * @param Int $offset
     * @return Array
     */
    private function _data($query_struct = array(), $orderby = NULL, $limit = 1000, $offset = 0)
    {
        $list = array();
        $where = array();
        $like = array();
        $in = array();
        
        $domain = ORM::factory('domain');
        //WHERE
        if (count($query_struct) > 0)
        {
            if (isset($query_struct['where']))
            {
                foreach ($query_struct['where'] as $key=>$value)
                {
                    $where[$key] = $value;
                }
            }
        }
        //LIKE
        if (count($query_struct) > 0)
        {
            if (isset($query_struct['like']))
            {
                foreach ($query_struct['like'] as $key=>$value)
                {
                    $like[$key] = $value;
                }
            }
        }
        //IN
        if (count($query_struct) > 0)
        {
            if (isset($query_struct['in']))
            {
                foreach ($query_struct['in'] as $key=>$value)
                {
                    $in[$key] = $value;
                }
            }
        }
        //WHERE
        if (count($where) > 0)
        {
            $domain->where($where);
        }
        //LIKE
        if (count($like) > 0)
        {
            $domain->like($like);
        }
        //IN
        if (count($in) > 0)
        {
            foreach ($in as $key=>$value)
            {
                $domain->in($key, $value);
            }
        }
        
        if (! empty($orderby))
        {
            $domain->orderby($orderby);
        }
        
        $orm_list = $domain->find_all($limit, $offset);
        
        foreach ($orm_list as $item)
        {
        	$merge_arr = array();
			$merge_arr['domain_api_name'] = $item->domain_api->name;
			$merge_arr['manager_name'] = $item->manager->name;
			$merge_arr['site_name'] = $item->site->name;
            $list[] = array_merge($item->as_array(), $merge_arr);
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
        $domain = ORM::factory('domain');
        
        $where = array();
        $like = array();
        $in = array();
        
        $domain = ORM::factory('domain');
        //WHERE
        if (count($query_struct) > 0)
        {
            if (isset($query_struct['where']))
            {
                foreach ($query_struct['where'] as $key=>$value)
                {
                    $where[$key] = $value;
                }
            }
        }
        //LIKE
        if (count($query_struct) > 0)
        {
            if (isset($query_struct['like']))
            {
                foreach ($query_struct['like'] as $key=>$value)
                {
                    $like[$key] = $value;
                }
            }
        }
        //IN
        if (count($query_struct) > 0)
        {
            if (isset($query_struct['in']))
            {
                foreach ($query_struct['in'] as $key=>$value)
                {
                    $in[$key] = $value;
                }
            }
        }
        //WHERE
        if (count($where) > 0)
        {
            $domain->where($where);
        }
        //LIKE
        if (count($like) > 0)
        {
            $domain->like($like);
        }
        //IN
        if (count($in) > 0)
        {
            foreach ($in as $key=>$value)
            {
                $domain->in($key, $value);
            }
        }
        
        $count = $domain->count_all();
        return $count;
    }
    
    /**
     * list domain
     *
     * @param Array $where
     * @param Array $orderby
     * @param Int $limit
     * @param Int $offset
     * @return Array
     */
    public function domains($query_struct = array(), $orderby = NULL, $limit = 100, $offset = 0)
    {
        $list = $this->_data($query_struct, $orderby, $limit, $offset);
        return $list;
    }
    
    /**
     * get domain data
     *
     * @return Array
     */
    public function get($key = NULL)
    {
        if ( empty($key))
        {
            return $this->data;
        }
        else
        {
            if (isset($this->data[$key]))
            {
                return $this->data[$key];
            }
            else
            {
                return NULL;
            }
        }
    }
    
    /**
     * get domain by domain
     *
     * @param <String> $domain
     * @return <Array>
     */
    public function get_by_domain($domain)
    {
        $where = array();
        $where['domain'] = $domain;
        
        $domain = ORM::factory('domain')->where($where)->find();
        
        return $domain->as_array();
    }
    
    /**
     * add a domain
     *
     * @param Array $data
     * @return Array
     */
    public function add($data)
    {
        //ADD
        $domain = ORM::factory('domain');
        $domain->reg_time = date('Y-m-d H:i:s');
        $domain->domain = 'www.'.$data['sld'].'.'.$data['tld'];
        $errors = '';
        if ($domain->validate($data, TRUE, $errors))
        {
            $this->data = $domain->as_array();
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    /**
     * get domain site
     *
     * @return Array
     */
    public function site()
    {
        $id = $this->data['id'];
        
        $site = ORM::factory('domain', $id)->site;
        return $site->as_array();
    }
    
    /**
     * set domain site
     *
     * @param <Int> $site_id
     * @return Boolean
     */
    public function set_site($site_id)
    {
        $id = $this->data['id'];
        $domain = ORM::factory('domain', $id);
        if ($domain->loaded)
        {
            $domain->site_id = $site_id;
            $domain->save();
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * purchase a domain
     * @param string $sld
     * @param string $tld
     * @param int $api_id [optional]
     * @return boolean
     */
    public function purchase($sld, $tld, $api_id = NULL)
    {
        if (is_null($api_id))
        {
            //默认使用第一个域名API
            $apis = Mydomain_api::instance()->domain_apis();
            $api = $apis[0];
        }
        else
        {
            $api = Mydomain_api::instance($api_id)->get();
            if (!$api['id'])
            {
                $this->error[] = "域名API无效.";
                return false;
            }
        }
        
        $domain_interface_obj = Mydomaininterface::instance($api['name']);
        $domain_interface_obj->account($api['api_username'], $api['api_password']);
        if ($domain_interface_obj->purchase($sld, $tld))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    /**
     * check domain avaliable
     */
    public function check($sld, $tld, $api_id = NULL)
    {
        if (is_null($api_id))
        {
            //默认使用第一个域名API
            $apis = Mydomain_api::instance()->domain_apis();
            $api = $apis[0];
        }
        else
        {
            $api = Mydomain_api::instance($api_id)->get();
            if (!$api['id'])
            {
                $this->error[] = "域名API无效.";
                return false;
            }
        }
        
        $domain_interface_obj = Mydomaininterface::instance($api['name']);
        $domain_interface_obj->account($api['api_username'], $api['api_password']);
        if ($domain_interface_obj->check($sld, $tld))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
	/**
	 * 删除站点则把域名中对应的站点清空
	 * 
	 * @param int $site_id
	 * @return boolean
	 */
	public function delete_by_site($site_id)
	{
		//更新信息
		$domain = ORM::factory('domain')->where('site_id',$site_id)->find_all();
		foreach($domain as $item)
		{
			$item->site_id = 0;
			$item->save();
		}
		
		return true;
	}
    /**
     * get api error
     *
     * @return Array
     */
    public function error()
    {
        $result = '';
        if (count($this->error))
        {
            $result = '<br />';
            foreach ($this->error as $key=>$value)
            {
                $result .= ($key + 1).' . '.$value.'<br />';
            }
        }
        return $result;
    }
}
