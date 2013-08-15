<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 商品SEO功能BLL
 *
 */
class BLL_Product_SEO {
	
	/**
	 * 设置商品的SEO信息
	 *
	 * @param array $product
	 * @return boolean
	 */
	static public function set(& $product)
	{
		$category = CategoryService::get_instance()->get($product['category_id']);
    	$seo_inf = Seo_manageService::get_instance()->get_product_seo_struct(array(
    		'category_id'   => $product['category_id'],
    		'product_name'  => $product['title'],
    		'category_name' => $category['title'],
    		//'site_domain'   => Mysite::instance($product['site_id'])->get('domain'),
    		'goods_price'   => $product['goods_price'],
    	));
    	
    	empty($product['meta_title'])       AND $product['meta_title']       = $seo_inf['meta_title'];
    	empty($product['meta_keywords'])    AND $product['meta_keywords']    = $seo_inf['meta_keywords'];
    	empty($product['meta_description']) AND $product['meta_description'] = $seo_inf['meta_description'];
    	
    	return TRUE;
	}
}