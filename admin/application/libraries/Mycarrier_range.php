<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mycarrier_range_Core {
	private $data = array();
	private $errors = NULL;

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
	 * Construct load carrier_range data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}
	/**
	 * load carrier_range data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$id = intval($id);

		$carrier_range = ORM::factory('carrier_range',$id)->as_array();
		$this->data = $carrier_range;
	}
	/**
	 * get carrier_range data
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	private function _data($where = NULL,$in=NULL,$orderby = NULL,$limit = 0,$offset = 100)
	{
		$list = array();

		$orm = ORM::factory('carrier_range');
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
	function count($where=NULL,$in=NULL)
	{
		$orm = ORM::factory('carrier_range');
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
	 * list carrier_range
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	public function carrier_ranges($where=NULL,$in=NULL,$orderby=NULL,$limit=100,$offset=0)
	{
		$list = $this->_data($where,$in,$orderby,$limit,$offset);
		return $list;
	}

	/**
	 * get carrier_range data
	 *
	 * @return Array
	 */
	public function get($key = NULL)
	{
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
	 * add a carrier_range
	 *
	 * @param Int $id
	 * @param Array $data
	 * @return Array
	 */
	public function add($data)
	{
		//ADD
		$orm = ORM::factory('carrier_range');
		//TODO
		if($orm->validate($data ,TRUE ,$errors = ''))
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
	 * edit a carrier_range
	 *
	 * @param Int $id
	 * @param Array $data
	 * @return Array
	 */
	public function edit($data)
	{
		$id = $this->data['id'];
		//EDIT
		$orm = ORM::factory('carrier_range',$id);
		if(!$orm->loaded)
		{
			return FALSE;
		}
		//TODO
		if($orm->validate($data ,TRUE ,$errors = ''))
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
	 * get carrier_range select list
	 *
	 * @param array $in
	 * @return array
	 */
	public function select_list($in = NULL)
	{
		$list = array();

		$orm = ORM::factory('carrier_range');

		if(!empty($in))
		{
			$orm->in('site_id',$in);
		}

		$list = $orm->select_list('id','name');

		return $list;
	}

    /**
     * 检查开始区间
     * @param <array>   $data
     * @param <int>     $carrier_range_id 物流范围ID(如果带入物流区间的ID则不对本条记录做验证)
     * @return <boolean>
     */
    public function check_range_from($data,$carrier_range_id=0)
    {
		$where = array();
        $where['site_id']		    = $data['site_id'];
		$where['carrier_id']	    = $data['carrier_id'];
		$where['parameter_from <='] = $data['parameter_from'];
		$where['parameter_to >']    = $data['parameter_from'];

        $where['id <>']	            = $carrier_range_id;

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
     * 检查结束区间
     * @param <array>   $data
     * @param <int>     $carrier_range_id 物流范围ID(如果带入物流区间的ID则不对本条记录做验证)
     * @return <boolean>
     */
    public function check_range_to($data,$carrier_range_id=0)
    {
        $where = array();
        $where['site_id']		    = $data['site_id'];
        $where['carrier_id']	    = $data['carrier_id'];
        $where['parameter_from <']  = $data['parameter_to'];
        $where['parameter_to >=']   = $data['parameter_to'];
        
        $where['id <>']	            = $carrier_range_id;

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
    public function carrier_range_ids($where=NULL,$orderby=NULL,$limit=100,$offset=0)
    {
        $list = $this->_data($where,$orderby,$limit,$offset);
        $res  = array();
        foreach($list as $key=>$rs){
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
	 * 删除物流区间
	 */
    public function delete()
    {
        $id = $this->data['id'];

        $orm = ORM::factory('carrier_range',$id);
        if(!$orm->loaded)
        {
            return FALSE;
        }
        $orm->delete();
        return TRUE;
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
        $orm=ORM::factory('carrier_range')
            ->where('id',$id)
            ->find();
        if($orm->loaded){
            if($position=='up'){
                $orm->position = $orm->position-3;
            }else{
                $orm->position = $orm->position+3;
            }
            $orm->save();

            $orm_list= ORM::factory('carrier_range')
                ->where(array('site_id'=>$orm->site_id))
                ->orderby(array('position'=>'ASC'))
                ->find_all() ;
            foreach( $orm_list as $key=>$rs){
                if($rs->position<>$key*2+1){
                    $rs->position = $key*2+1;
                    $rs->save();
                }
            }
            return TRUE;
        }else{
            $error          = 'ID '.$id.' 数据不存在';
            Mylog::instance()->error($error,__FILE__,__LINE__);
            return FALSE;
        }
    }
}
