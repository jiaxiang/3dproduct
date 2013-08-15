<?php defined('SYSPATH') or die('No direct script access.');

class Action_Core{
	private static $instance;
	public static function & instance()
	{
		if (!isset(self::$instance))
		{
			$class = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}

	/**
	 * 构建用户操作资源的多维数组
	 *
	 * @return Array
	 */
	public static function get()
	{
		$actions = array();
		$cache = Mycache::instance('tt');
		$tag = "admin/action";

		if(!($data = $cache->get($tag)))
		{
			$model = self::models();
			foreach($model as $model_key=>$model_value)
			{
				$actions[$model_value['flag']] = self::actions($model_value['id'],TRUE);
			}
		}
		else
		{
			$actions = $data;
		}

		return $actions;
	}

	/**
	 * 递归得到模块和操作下面的子操作
	 *
	 * @param Int $id
	 * @return Array
	 */
	public static function get_actions_by_level($level = 1,$parent_id = 0)
	{
		$where['level_depth'] = $level;
		$where['parent_id'] = $parent_id;
		$actions = Myaction::instance()->get_actions($where);
		$count = count($actions)-1;
		foreach($actions as $key=>$value)
		{
			if($key == $count)
			{
				$actions[$key]['class_flag'] = 'last';
				$actions[$key]['last_flag'] = 'end_none';
			}
			else
			{
				$actions[$key]['class_flag'] = '';
				$actions[$key]['last_flag'] = '';
			}
		}
		return $actions;
	}

	/**
	 * 得到模块列表
	 *
	 * @return $menu
	 */
	public static function models()
	{
		$where = array('active'=>1);
		$orderby = array('order'=>'desc');
		$model = Mymodel::instance()->models($where,$orderby);		
		return $model;
	}
}
