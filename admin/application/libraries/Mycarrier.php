<?php
defined('SYSPATH') or die('No direct access allowed.');

class Mycarrier_Core extends My{
	//表名
	protected $object_name = 'carrier';
	//数据成员记录单体数据
	protected $data = array();
	//记录Service中的错误信息
	protected $error = array();
	
	private static $instances;
	public static function &instance($id = 0)
	{
		if(!isset(self::$instances[$id]))
		{
			$class = __CLASS__;
			self::$instances[$id] = new $class($id);
		}
		return self::$instances[$id];
	}

	/**
	 * get carrier data
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	private function _data($where = NULL,$in = NULL,$orderby = NULL,$limit = 0,$offset = 100)
	{
		$list = array();
		
		$orm = ORM::factory('carrier');
		if(!empty($where))
		{
			$orm->where($where);
		}
		
		if(!empty($in))
		{
			$orm->in('site_id',$in);
		}
		if(!empty($orderby))
		{
			$orm->orderby($orderby);
		}
		
		$orm_list = $orm->find_all($limit,$offset);
		
		$list = array();
		foreach($orm_list as $key=>$rs)
		{
			$list[$key] = $rs->as_array();
			$list[$key]['site'] = Mysite::instance($rs->site_id)->get();
			$where = array('carrier_id'=>$rs->id,'site_id'=>$rs->site_id);
			$orderby = array('parameter_from'=>'ASC');
			$list[$key]['carrier_ranges'] = Mycarrier_range::instance()->carrier_ranges($where,NULL,$orderby);
		}
		
		return $list;
	}

	/**
	 * get the total number
	 *
	 * @param Array $where
	 * @param Array $in
	 * @return Int
	 */
	function count($where = NULL,$in = NULL)
	{
		$orm = ORM::factory('carrier');
		if(!empty($where))
		{
			$orm->where($where);
		}
		if(!empty($in))
		{
			$orm->in('site_id',$in);
		}
		$count = $orm->count_all();
		return $count;
	}

	/**
	 * list carrier
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	public function carriers($where = NULL,$in = NULL,$orderby = NULL,$limit = 100,$offset = 0)
	{
		$list = $this->_data($where,$in,$orderby,$limit,$offset);
		return $list;
	}

	/**
	 * get carrier data
	 *
	 * @return Array
	 */
	public function get_by_name($site_id,$name)
	{
		$carrier = ORM::factory('carrier')->where(array('name'=>$name,'site_id'=>$site_id))->find()->as_array();
		return $carrier;
	}

	/**
	 * add a carrier
	 *
	 * @param Int $id
	 * @param Array $data
	 * @return Array
	 */
	public function add($data)
	{
		$orm = ORM::factory('carrier');
		if($orm->validate($data,TRUE,$errors = ''))
		{
			$this->data = $orm->as_array();
			
			//添加默认国家物流关联
			$country_list = ORM::factory('country')->where('site_id',$orm->site_id)->find_all();
			foreach($country_list as $key=>$rs)
			{
				$country_carrier = ORM::factory('carrier_country');
				$country_carrier->site_id = $orm->site_id;
				$country_carrier->carrier_id = $orm->id;
				$country_carrier->country_id = $rs->id;
				$country_carrier->shipping_add = 0;
				$country_carrier->position = 1000;
				$country_carrier->save();
			}
			//添加物流价格区间
			$carrier_range = ORM::factory('carrier_range');
			$carrier_range->site_id = $orm->site_id;
			$carrier_range->carrier_id = $orm->id;
			$carrier_range->parameter_from = 0;
			$carrier_range->parameter_to = 1000;
			$carrier_range->shipping = 0;
			$carrier_range->position = 1000;
			$carrier_range->save();
			return $orm->id;
		}
		else
		{
			$this->errors = $errors;
			return FALSE;
		}
	}

	/**
	 * edit a carrier
	 *
	 * @param Int $id
	 * @param Array $data
	 * @return Array
	 */
	public function edit($data)
	{
		$id = $this->data['id'];
		//EDIT
		$orm = ORM::factory('carrier',$id);
		if(!$orm->loaded)
		{
			return FALSE;
		}
		if($orm->validate($data,TRUE,$errors = ''))
		{
			$this->data = $orm->as_array();
			return TRUE;
		}
		else
		{
			$this->errors = $errors;
			return FALSE;
		}
	}

	/**
	 * get carrier select list
	 *
	 * @param array $in
	 * @return array
	 */
	public function select_list($in = NULL)
	{
		$list = array();
		
		$orm = ORM::factory('carrier');
		
		if(!empty($in))
		{
			$orm->in('site_id',$in);
		}
		$orm->where('active',1);
		$list = $orm->select_list('id','name');
		
		return $list;
	}

	/**
	 * 是否已经存在
	 * @param <array> $args
	 * @return <boolean>
	 */
	public function exist($data)
	{
		$where = array();
		$where['site_id'] = $data['site_id'];
		$where['name'] = $data['name'];
		$count = $this->count($where);
		if($count>0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * list order status
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	public function carrier_ids($where = NULL,$orderby = NULL,$limit = 100,$offset = 0)
	{
		$list = $this->_data($where,$orderby,$limit,$offset);
		$res = array();
		foreach($list as $key=>$rs)
		{
			if(isset($rs['id']))
			{
				$res[] = $rs['id'];
			}
		}
		return $res;
	}

	/**
	 * get errors
	 */
	public function errors()
	{
		return $this->errors;
	}

	/**
	 * 删除物流
	 */
	public function delete()
	{
		$id = $this->data['id'];
		$orm = ORM::factory('carrier',$id);
		if(!$orm->loaded)
		{
			return FALSE;
		}
		//国家物流关系删除
		ORM::factory('carrier_country')->where('site_id',$orm->site_id)->where('carrier_id',$orm->id)->delete_all();
		//物流区间删除
		ORM::factory('carrier_range')->where('site_id',$orm->site_id)->where('carrier_id',$orm->id)->delete_all();
		$orm->delete();
		return TRUE;
	}
	
	/**
	 * 删除站点所有的物流信息
	 * 
	 * @param int $site_id
	 * @return boolean
	 */
	public function delete_by_site($site_id)
	{
		//删除物流信息
		$carrier = ORM::factory('carrier')->where('site_id',$site_id);
		$carrier->delete_all();
		//删除物流国家关联信息
		$carrier_country = ORM::factory('carrier_country')->where('site_id',$site_id);
		$carrier_country->delete_all();
		//删除物流区间信息
		$carrier_range = ORM::factory('carrier_range')->where('site_id',$site_id);
		$carrier_range->delete_all();
		
		return true;
	}
	
	/**
	 * 排序
	 *
	 * @param int       $id             分类ID
	 * @param string    $position       排序方向
	 * @return boolean
	 */
	public function position($id,$position)
	{
		$orm = ORM::factory('carrier')->where('id',$id)->find();
		if($orm->loaded)
		{
			if($position=='up')
			{
				$orm->position = $orm->position-3;
			}
			else
			{
				$orm->position = $orm->position+3;
			}
			$orm->save();
			
			$orm_list = ORM::factory('carrier')->where(array('site_id'=>$orm->site_id))->orderby(array('position'=>'ASC'))->find_all();
			foreach($orm_list as $key=>$rs)
			{
				if($rs->position!=$key*2+1)
				{
					$rs->position = $key*2+1;
					$rs->save();
				}
			}
			return TRUE;
		}
		else
		{
			$error = 'ID '.$id.' 数据不存在';
			Mylog::instance()->error($error,__FILE__,__LINE__);
			return FALSE;
		}
	}
    public function set_order($id ,$order)
    {
        $where = array('id'=>$id);
        $obj = ORM::factory('carrier')->where($where)->find();
        if($obj->loaded){
            $obj->position = $order;
            if($obj->save()){
                return true;
            }
            return false;
        }
        return false;
    }
    

    
    
}
