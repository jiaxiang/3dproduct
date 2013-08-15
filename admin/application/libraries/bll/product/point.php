<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 商品点击数BLL
 *
 * @author 王浩
 */
class BLL_Product_Point {
	
	/**
	 * 通过商品ID获取商品点击数
	 *
	 * @param integer $product_id
	 * @return integer
	 */
	static public function get($product_id)
	{
		$point = Product_pointService::get_instance()->query_row(array('where' => array(
			'product_id' => $product_id,
		)));
		
		if (empty($point))
		{
			$point = 0;
			Product_pointService::get_instance()->add(array(
				'product_id' => $product_id,
				'point'      => $point,
			));
		} else {
			$point = $point['point'];
		}
		
		return $point;
	}
	
	/**
	 * 设置商品的点击数
	 *
	 * @param array $product
	 * @param integer $point
	 * @return boolean
	 */
	static public function set(& $product, $point)
	{
		$record = Product_pointService::get_instance()->query_row(array('where' => array(
			'product_id' => $product['id'],
		)));
		
		if (!empty($record))
		{
			Product_pointService::get_instance()->add(array(
				'product_id' => $product['id'],
				'point'      => $point,
			));
		} else {
			Product_pointService::get_instance()->set($record['id'], array(
				'product_id' => $product['id'],
				'point'      => $point,
			));
		}
		
		return TRUE;
	}
	
	/**
	 * 删除商品的点击数
	 *
	 * @param integer $product_id
	 * @return boolean
	 */
	static public function delete($product_id)
	{
		ORM::factory('product_point')->where('product_id', $product_id)->delete_all();
		
		return TRUE;
	}
}