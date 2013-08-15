<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 商品关联商品BLL
 *
 * @author 王浩
 */
class BLL_Product_Relation {
	
	/**
	 * 通过商品ID获取该商品的关联商品ID序列
	 * 
	 * @param integer $product_id
	 * @return array
	 */
	static public function get($product_id)
	{
		$relations = Product_relationService::get_instance()->index(array('where' => array(
			'product_id' => $product_id,
		)));
		
		$relation_product_ids = array();
		
		if (!empty($relations))
		{
			foreach ($relations as $relation)
			{
				$relation_product_ids[] = $relation['relation_product_id'];
			}
		}
		
		if (!empty($relation_product_ids))
		{            
			/*$relations = ProductService::get_instance()->index(array('where' => array(
				'id'      => $relation_product_ids,
			)));*/
			$relations = BLL_Product::index(array('where' => array(
				'id' => $relation_product_ids,
			)));
			return $relations['assoc'];
		} else {
			return array();
		}
	}
	
	/**
	 * 设置商品的关联商品
	 * 
	 * @param array $product
	 * @param array $relation_ids
	 * @return boolean
	 */
	static public function set(& $product)
	{
		ORM::factory('product_relation')->where('product_id', $product['id'])->delete_all();
		
		if (isset($product['pdt_relation_ids']) && count($product['pdt_relation_ids'])>0)
		{
			$relations = ProductService::get_instance()->index(array('where' => array(
				'id'      => $product['pdt_relation_ids'],
			)));
			
			foreach ($relations as $relation)
			{
				if ($relation['id'] != $product['id']){
					Product_relationService::get_instance()->add(array(
						'product_id'          => $product['id'],
						'relation_product_id' => $relation['id'],
					));
				}
			}
		}
		
		return TRUE;
	}
	
	/**
	 * 通过商品ID删除商品的关联商品
	 * 
	 * @param integer $product_id
	 * @return boolean
	 */
	static public function delete($product_id)
	{
		ORM::factory('product_relation')->where('product_id', $product_id)->delete_all();
		ORM::factory('product_relation')->where('relation_product_id', $product_id)->delete_all();
		
		return TRUE;
	}
}