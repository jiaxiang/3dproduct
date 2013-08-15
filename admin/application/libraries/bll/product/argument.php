<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 商品参数BLL
 *
 * @author 王浩
 */
class BLL_Product_Argument {
	
	/**
	 * 缓存键名
	 *
	 * @var string
	 */
	static protected $cache_key = 'product_arguments.';
	
	/**
	 * 通过商品ID获取商品参数
	 *
	 * @param integer $product_id
	 * @return array
	 */
	static public function get_argumrs($product_id)
	{
		$arguments = null;//Cache::get(self::$cache_key.$product_id);
		
		if (!$arguments AND !is_array($arguments))
		{
			$arguments = array();
			
			$record = Product_argumentService::get_instance()->query_row(array(
				'where' => array('product_id' => $product_id),
			));
			
			if (!empty($record))
			{
				$arguments = json_decode($record['arguments'], TRUE);
				$arguments OR $arguments = array();
			}
			
			//Cache::set(self::$cache_key.$product_id, $arguments);
		}
		return $arguments;
	}
	
	/**
	 * 设置商品规格
	 *
	 * @param array $product
	 * @param array $arguments
	 * @return boolean
	 */
	static public function set_argumrs(& $product)
	{
		self::rmv_arguments($product['id']);
		$arguments = isset($product['pdt_argumrs'])?$product['pdt_argumrs']:array();
        
		if ($product['classify_id'] > 0 AND $arguments)
		{
			$relation = array();
			foreach (self::get_clsargurs($product['classify_id']) as $group)
			{
				isset($relation[$group['name']]) OR $relation[$group['name']] = array();
				
				foreach ($group['items'] as $argument)
				{
					$relation[$group['name']][$argument['name']] = TRUE;
				}
			}
			
			if (!empty($relation))
			{
				foreach ($arguments as $group => $argument)
				{
					if (isset($relation[$group]))
					{
						foreach ($argument as $key => $value)
						{
							$value = trim($value);
							if (!isset($relation[$group][$key]) OR empty($value))
							{
								unset($arguments[$group][$key]);
							}
						}
					} else {
						unset($arguments[$group]);
					}
				}
			}
			
			if (!empty($arguments))
			{
				Product_argumentService::get_instance()->add(array(
					'product_id' => $product['id'],
					'arguments'  => json_encode($arguments),
				));
			}
		}
		
		//Cache::remove(self::$cache_key.$product['id']);
		
		return TRUE;
	}
	
	/**
	 * 移除商品参数
	 *
	 * @param integer $product_id
	 * @return boolean
	 */
	static public function rmv_arguments($product_id)
	{
		ORM::factory('product_argument')->where('product_id', $product_id)->delete_all();
		
		//Cache::remove(self::$cache_key.$product_id);
		
		return TRUE;
	}
	
	/**
	 * 通过商品类型ID获取商品类型所关联的商品参数
	 *
	 * @param integer $classify_id
	 * @return array
	 */
	static public function get_clsargurs($classify_id)
	{
		$arguments = array();
		
		if ($classify_id > 0)
		{
			try
			{
				$classify  = ClassifyService::get_instance()->get($classify_id);
				$arguments = json_decode($classify['argument_relation_struct'], TRUE);
				$arguments OR $arguments = array();
			} catch (MyRuntimeException $ex) {
				return $arguments;
			}
		}
		
		return $arguments;
	}
}