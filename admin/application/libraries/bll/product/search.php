<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 商品检索BLL
 *
 * @author 王浩
 */
class BLL_Product_Search {
	
	/**
	 * 设置商品检索信息
	 *
	 * @param array $product
	 * @return boolean
	 */
	static public function set(& $product)
	{
        $data = array(
        	'product_id'  => $product['id'],
        	'category_id' => $product['category_id'],
        	'brand_id'    => $product['brand_id'],
        	'title'       => $product['title'],
        	'brief'       => $product['brief'],
        	'description' => empty($product['descsections'][0])?'':$product['descsections'][0]['content'],
        	//'attributes'  => empty($product['goods_attributeoption_relation_struct_default']['items']) ? array() : $product['goods_attributeoption_relation_struct_default']['items'],
        	//'features'    => empty($product['product_featureoption_relation_struct']['items'])         ? array() : $product['product_featureoption_relation_struct']['items'],
        );
		ProductsearchService::get_instance()->set_single($data);
        
        return TRUE;
	}
	
	/**
	 * 通过商品ID删除商品检索信息
	 *
	 * @param integer $product_id
	 * @return boolean
	 */
	static public function delete($product_id)
	{
		ORM::factory('productsearch')->where('product_id', $product_id)->delete_all();
		
		return TRUE;
	}
}