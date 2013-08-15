<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mysite_Core {
	protected $object_name = 'site';
	protected $data = array();
	protected $error = array();
	private $union_query = true;
	protected $serv_route_instance = NULL;
	private static $instances;
	const SITE_BIZ_TYPE_B2B = 1;
	const SITE_BIZ_TYPE_B2C = 0;
    //const SITE_CONFIG_FILE = 'site_config';
    public $site_config = 'site_config';
    
	public static function & instance()
	{
		if (!isset(self::$instances))
		{
			$class = __CLASS__;
			self::$instances = new $class();
		}
		return self::$instances;
	}
	
	/**
     * 获取路由实例管理实例
     */
    private function get_serv_route_instance()
    {
        if($this->serv_route_instance === NULL){
            $this->serv_route_instance = ServRouteInstance::getInstance(ServRouteConfig::getInstance());
        }
        return $this->serv_route_instance;
    }

	/**
	 * Construct load site data
	 *
	 * @param Int $id
	 */
	public function __construct()
	{
		$this->_load();
	}

	/**
	 * load site data
	 *
	 * @param Int $id
	 */
	private function _load()
	{
        //$this->data = Kohana::config($this->site_config.'.site');
		$this->data = ORM::factory('site',1)->find()->as_array();
	}

	function update_site_config($site_data)
	{
        return $this->edit($site_data);
        /*$str='<?php defined(\'SYSPATH\') OR die(\'No direct access allowed.\');'."\r\n".'$config[\'site\']=' . var_export($site_data, true) . ';'."\r\n".'?>';
        return @file_put_contents(SYSPATH.'/config/'.$this->site_config.'.php', $str);*/
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

		$site = ORM::factory('site');
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

		if($this->union_query)
		{
			foreach($orm_list as $item)
			{
				$merge_arr = array(
					'site_type_name' => Mysite_type::instance($item->site_type_id)->get('name'),//$item->site_type->name,
					'theme_name' => Mytheme::instance($item->theme_id)->get('name')//$item->theme->name
				);
				$list[] = array_merge($item->as_array(),$merge_arr);
			}
		}
		else
		{
			foreach($orm_list as $item)
			{
				$list[] = $item->as_array();
			}
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
		$site = ORM::factory('site');

		$where = array();
		$like = array();
		$in = array();

		$site = ORM::factory('site');
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
	public function sites($query_struct = array(),$orderby=NULL,$limit=1000,$offset=0)
	{
		$list = array();
		$this->union_query = true;

		$list = $this->_data($query_struct,$orderby,$limit,$offset);
		return $list;
	}

	/**
	 * 单独得到站点列表
	 * 
	 * @param Array $query_struct
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	public function get_sites($query_struct = array(),$orderby=NULL,$limit=1000,$offset=0)
	{
		$list = array();
		$this->union_query = false;

		$list = $this->_data($query_struct,$orderby,$limit,$offset);
		return $list;
	}

	/**
	 * get site data
	 *
	 * @return Array
	 */
	public function get($key = NULL)
	{
        $this->_load();
		if(empty($key))
		{
			return $this->data;
		}
		else
		{
			if(isset($this->data[$key]))
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
	 * get for domain
	 *
	 * @param String $domain
	 * @return Array
	 */
	public function get_by_domain($domain)
	{
		$site = ORM::factory('site')->where(array('domain'=>$domain))->find();
		return $site->as_array();
	}

	/**
	 * add a site
	 *
	 * @param Array $data
	 * @return Array
	 */
	public function add($data)
	{
		//ADD
		$site = ORM::factory('site');
		$errors = '';
		if($site->validate($data ,TRUE ,$errors))
		{
			$this->data = $site->as_array();
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * edit a manager item
	 *
	 * @param Array $data
	 * @param Int $id
	 * @return Array
	 */
	private function _edit($id,$data)
	{
		$id = intval($id);
		//EDIT
		$site = ORM::factory('site',$id);
		if(!$site->loaded)
		{
			return FALSE;
		}
		//TODO
		$errors = '';
		if($site->validate($data ,TRUE ,$errors))
		{
			$this->data = $site->as_array();
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
		$id = $this->data['id'];
		return $this->_edit($id,$data);
	}

	/**
	 * edit item by id
	 *
	 * @param Array $data
	 * @param Int $id
	 * @return Array
	 */
	public function edit_by_id($id,$data)
	{
		$id = intval($id);
		return $this->_edit($id,$data);
	}

	/*
	 * update item by id
	 *
	 * @param Int $id site id
	 * @param Array $data update data array
	 * @return Boolean
	 */
	public function _update($id,$data=array())
	{
		$site = ORM::factory('site',$id);
		if(count($data) > 0)
		{
			foreach($data as $key=>$value)
			{
				$site->$key = $value;
			}
		}

		$site->save();
		$this->data = $site->as_array();

		if($site->saved)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * update item
	 *
	 * @param Array $data update data array
	 * @return Boolean
	 */
	public function update($data = array())
	{
		$id = intval($this->data['id']);

		return $this->_update($id,$data);
	}

	/**
	 * update item by id
	 *
	 * @param Array $id
	 * @param Array $data update data array
	 * @return Boolean
	 */
	public function update_by_id($id,$data=array())
	{
		return $this->_update($id,$data);
	}

	/**
	 * add site's manager
	 *
	 * @param int $manager_id
	 * @return
	 */
	public function add_managers($manager_id = array())
	{
		$id = $this->data['id'];
		foreach($manager_id as $key=>$value)
		{
			$site = ORM::factory('site',$id);
			$site->add(ORM::factory('manager',$value));
			$site->save();
		}
	}
	/**
	 * get site theme
	 *
	 * @return Array
	 */
	public function theme()
	{
		$theme = ORM::factory('site',$this->data['id'])->theme;
		return $theme->as_array();
	}

	/**
	 * get site domain
	 *
	 * @return array
	 */
	public function default_domain()
	{
        return $this->data['domain'];
	}

	/**
	 * get site payment_api
	 *
	 * @return array
	 */
	public function payments()
	{
		$list = array();
		$payments = ORM::factory('payment')->orderby(array('position'=>'ASC'))->find_all();
		foreach($payments as $key=>$rs)
		{
			$list[$key] = $rs->as_array();
			$list[$key]['payment_type'] = Mypayment_type::instance($rs->payment_type_id)->get();
		}
		return $list;
	}

	/**
	 * get site's managers
	 * 
	 * @return array
	 */
	public function managers()
	{
		$list = array();
		$id = $this->data['id'];

		$site = ORM::factory('site',$id);
		$manager = $site->managers;

		foreach($manager as $item)
		{
			$list[] = $item->as_array();
		}
		return $list;
	}

	/**
	 * get site detail
	 *
	 * @return array
	 */
	public function detail()
	{
		$id = $this->data['id'];
		$site = ORM::factory('site',$id);
		$site_detail = $site->site_detail;
        //return ORM::factory('site_detail',$id)->find()->as_array();
		return $site_detail->as_array();
	}

	/**
	 * get site payment_api
	 *
	 * @return Array
	 */
	public function add_payment($id,$payment_id)
	{
		$site = ORM::factory('site',$id);
		if($site->loaded)
		{
			$site->add(ORM::factory('payment',$payment_id ));
			$site->save();
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * delete site payment_api
	 *
	 * @return Array
	 */
	public function delete_payment($payment_id)
	{
		$payment = ORM::factory('payment',$payment_id);
		if($payment->loaded)
		{
			$payment->delete();
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * get site select list
	 *
	 * @param array $in_array
	 * @return array
	 */
	public function select_list($in_array = NULL)
	{
		$list = array();
		$site   = ORM::factory('site');
		if(!empty($in_array))
		{
			$site   = $site->in('id',$in_array);
		}
		return $site->select_list('id','name');
	}

	/**
	 * delete a item
	 *
	 * @param Int $id
	 * @return Boolean
	 */
	public function delete($id = 0)
	{
		$id = intval($id);
		if(!$id){
			$id = $this->data['id'];
		}
		
		$site = ORM::factory('site',$id);
		if($site->loaded){
			$site->delete();
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * get for pay_id
	 *
	 * @param String $domain
	 * @return Array
	 */
	public function get_by_pay_id($pay_id)
	{
		$site = ORM::factory('site')->where(array('pay_id'=>$pay_id))->find()->as_array();
		return $site;
	}
	
	public function get_site_id_by_name($name)
	{
		$site = ORM::factory('site')->where(array('name'=>$name))->find()->as_array();
		return $site['id'];
	}

	/**
	 * get api error
	 *
	 * @return String
	 */
	public function error()
	{
		$result = '';
		if(count($this->error))
		{
			$result     = '<br />';
			foreach($this->error as $key=>$value)
			{
				$result .= ($key+1).' . '.$value.'<br />';
			}
		}
		return $result;
	}
}
