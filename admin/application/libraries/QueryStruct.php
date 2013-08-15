<?php defined('SYSPATH') OR die('No direct access allowd.');

class QueryStruct_Core {
	
	const QUERY   = 0;
	const REQUEST = 1;
	
	protected $data      = NULL;
	protected $has_error = FALSE;
	protected $pfilters  = array('trim');
	protected $sfilters  = array();
	protected $qstruct   = array(
		'where'   => array(),
		'limit'   => array(),
		'orderby' => array(),
	);
	protected $rstruct = array();
	protected $issets  = array();
	protected $orders  = array(
		0 => array('id' => 'DESC'),
		1 => array('id' => 'ASC'),
		2 => array('update_timestamp' => 'DESC'),
		3 => array('update_timestamp' => 'ASC'),
		4 => array('create_timestamp' => 'DESC'),
		5 => array('create_timestamp' => 'ASC'),
	);
	protected $default_order = 0;
	protected $index_type    = 'type';
	protected $index_value   = 'keyword';
	
	public static function factory($request)
	{
		return new QueryStruct($request);
	}
	
	protected function __construct($request)
	{
		$this->data = $this->strip($request);
	}
	
	public function get($type)
	{
		if (!empty($this->data[$this->index_type]))
		{
			if (!isset($this->issets[$this->data[$this->index_type]]))
			{
				$this->set($this->data[$this->index_type], NULL);
			}
			$this->rstruct[$this->index_type] = $this->data[$this->index_type];
			if (isset($this->data[$this->index_value]))
			{
				$this->rstruct[$this->index_value] = $this->data[$this->index_value];
			}
		}
		$this->orderby();
		$this->limit();
		
		return $type == self::QUERY ? $this->qstruct : $this->rstruct;
	}
	
	public function get_qstruct()
	{
		return $this->get(self::QUERY);
	}
	
	public function get_rstruct()
	{
		return $this->get(self::REQUEST);
	}
	
	public function set($key, $default)
	{
		$fkey = '';
		for ($i = 0; isset($key{$i}); $i++)
		{
			$ord = ord($key{$i});
			if ((80 <= $ord AND $ord <= 89) OR (65 <= $ord AND $ord <= 90) OR (97 <= $ord AND $ord <= 122) OR $ord == 95)
			{
				$fkey .= $key{$i};
			} else {
				break;
			}
		}
		
		if (array_key_exists($fkey, $this->data))
		{
			$value = $this->strip($fkey, $this->data[$fkey]);
		} elseif (!empty($this->data[$this->index_type]) AND $this->data[$this->index_type] === $fkey) {
			$value = isset($this->data[$this->index_value]) ? $this->strip($this->data[$this->index_type], $this->data[$this->index_value]) : '';
		} else {
			$value = '';
		}
		
		if ($value !== '')
		{
			//if (func_num_args() > 2)
			//{
			//	$args = func_get_args();
			//	$args = array_slice($args, 2);
			//}
			// TODO 与 Kohana 的 Validation 组件结合，可以进行数据验证
			$this->issets[$fkey]          = TRUE;
			$this->qstruct['where'][$key] = $value;
			$this->rstruct[$fkey]         = $value;
		} elseif (!is_null($default)) {
			$this->issets[$fkey]          = TRUE;
			$this->qstruct['where'][$key] = $default;
			$this->rstruct[$fkey]         = $default;
		}
		
		return $this;
	}
	
	public function func($key, $func)
	{
		$fkey = '';
		for ($i = 0; isset($key{$i}); $i++)
		{
			$ord = ord($key{$i});
			if ((80 <= $ord AND $ord <= 89) OR (65 <= $ord AND $ord <= 90) OR (97 <= $ord AND $ord <= 122) OR $ord == 95)
			{
				$fkey .= $key{$i};
			} else {
				break;
			}
		}
		
		if (array_key_exists($fkey, $this->data))
		{
			$value = $this->strip($fkey, $this->data[$fkey]);
		} elseif (isset($this->data[$this->index_type]) AND $this->data[$this->index_type] === $fkey) {
			$value = isset($this->data[$this->index_value]) ? $this->strip($this->data[$this->index_type], $this->data[$this->index_value]) : '';
		} else {
			$value = NULL;
		}
		
		$this->rstruct[$fkey] = $value;
		$value = call_user_func_array($func, array($value, $this->rstruct, $this->data));
		if ($value === FALSE)
		{
			$this->has_error = TRUE;
		} elseif (!is_null($value)) {
			$this->issets[$fkey] = TRUE;
			$this->qstruct['where'][$key] = $value;
		}
		
		return $this;
	}
	
	public function add_order($orders)
	{
		foreach ($orders as $index => $order)
		{
			$this->orders[$index] = $order;
		}
		
		return $this;
	}
	
	public function set_default_order($order)
	{
		if (isset($this->orders[$order]))
		{
			$this->default_order = $order;
		}
		
		return $this;
	}
	
	public function add_filter($filter)
	{
		if (!in_array($filter, $this->pfilters))
		{
			$this->pfilters[] = $filter;
		}
		
		return $this;
	}
	
	public function orderby($order = NULL, $sort = NULL)
	{
		if (!is_null($order))
		{
			if (preg_match('/^\d+$/', $order))
			{
				if (!isset($this->orders[$order]))
				{
					$order = $this->default_order;
				}
				$this->rstruct['orderby'] = $order;
				foreach ($this->orders[$order] as $order => $sort)
				{
					$this->qstruct['orderby'][$order] = $sort;
				}
			} else {
				if (is_null($sort))
				{
					$sort = 'DESC';
				}
				$this->qstruct['orderby'][$order] = $sort;
			}
		} else {
			if (isset($this->data['orderby']))
			{
				$this->orderby($this->strip('orderby', $this->data['orderby']));
			} else {
				$this->orderby($this->default_order);
			}
		}
		
		return $this;
	}
	
	public function limit($page = NULL, $pagesize = NULL)
	{
		if (!is_null($page))
		{
			$this->qstruct['limit']['page'] = $page;
			$this->rstruct['page'] = $page;
		} elseif (!isset($this->qstruct['limit']['page'])) {
			if (isset($this->data['page']))
			{
				$page = $this->strip('page', $this->data['page']);
			}
			$this->qstruct['limit']['page'] = !empty($page) ? $page : 1;
			$this->rstruct['page'] = $this->qstruct['limit']['page'];
		}
		if (!is_null($pagesize))
		{
			$this->qstruct['limit']['per_page'] = $pagesize;
			$this->rstruct['per_page'] = $pagesize;
		} elseif (!isset($this->qstruct['limit']['per_page'])) {
			if (isset($this->data['per_page']))
			{
				$pagesize = $this->strip('per_page', $this->data['per_page']);
			}
			$this->qstruct['limit']['per_page'] = !empty($pagesize) ? $pagesize : Kohana::config('my.items_per_page');
			$this->rstruct['per_page'] = $this->qstruct['limit']['per_page'];
		}
		
		return $this;
	}
	
	public function has_error()
	{
		return $this->has_error;
	}
	
	protected function strip($var, $value = NULL)
	{
		// TODO 添加单体过滤器支持，即针对单个字段设置的过滤器
		if (!is_null($value))
		{
			return $value;
		}
		if (is_array($var)) {
			return array_map(array($this, 'strip'), $var);
		} else {
			foreach ($this->pfilters as $filter)
			{
				$var = call_user_func($filter, $var);
			}
			return $var;
		}
	}
}