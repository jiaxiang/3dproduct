<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 商品详细描述BLL
 * 
 * @author 王浩
 */
class BLL_Product_Detail {
	
	/**
	 * 缓存键名
	 *
	 * @var string
	 */
	static protected $cache_key = 'product_detail.';
	
	/**
	 * 通过商品ID获取商品的详细描述
	 * 
	 * @param integer $product_id
	 * @return array
	 */
	static public function get($product_id)
	{
		//$descsections = Cache::get(self::$cache_key.$product_id);
		
		//if (!$descsections AND !is_array($descsections))
		//{
			$descsections = array();
			
			$query_struct = array(
				'where'   => array('product_id' => $product_id),
				'orderby' => 'position',
			);
			
			foreach (Product_detailService::get_instance()->index($query_struct) as $descsection)
			{
                if(empty($descsection['content']))continue;
				unset($descsection['product_id']);
				$descsections[] = $descsection;
			}
			
			//Cache::set(self::$cache_key.$product_id, $descsections);
		//}
		
		return $descsections;
	}
	
	/**
	 * 设置商品的详细描述
	 * 
	 * @param array $product
	 * @param array $descsections
	 * @return boolean
	 */
	static public function set(& $product)
	{
		$descids = array();
        $descsections = $product['descsections'];
		foreach (self::get($product['id']) as $descsection)
		{
			$descids[$descsection['id']] = TRUE;
		}
        
		if($descsections)
        {
    		foreach ($descsections as $descsection)
    		{    			
    			if ( isset($descsection['id']) && isset($descids[$descsection['id']]) )
    			{
    				unset($descids[$descsection['id']]);
    				Product_detailService::get_instance()->update($descsection);
    			} else {
                    unset($descsection['id']);
                    $descsection['product_id'] = $product['id'];
    				Product_detailService::get_instance()->create($descsection);
    			}
    		}
        }
        
		if (!empty($descids))
		{
			ORM::factory('product_detail')->in('id', array_keys($descids))->delete_all();
		}
		
		//Cache::remove(self::$cache_key.$product['id']);
		
		return TRUE;
	}
	
	/**
	 * 通过商品ID删除商品的详细描述
	 * 
	 * @param integer $product_id
	 * @return boolean
	 */
	static public function delete($product_id)
	{
		ORM::factory('product_detail')->where('product_id', $product_id)->delete_all();
		//Cache::remove(self::$cache_key.$product_id);
		
		return TRUE;
	}
}