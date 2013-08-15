<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 商品模板BLL
 * 
 * @author 王浩
 */
class BLL_Product_Template {
	
	/**
	 * 使用商品模板创建一个新的商品
	 * 
	 * @return array
	 */
	static public function get($site_id, $type=0)
	{
		if( Product_templateService::get_instance()->is_template_exist($site_id) ){
			$template = Product_templateService::get_instance()->get_template_by_site($site_id);
            unset($template['id']);
            unset($template['create_timestamp']);
            unset($template['update_timestamp']);
            unset($template['descriptions']);
            
            $product = $template;
            $product['type'] = $type;
            $product['sku'] = strtoupper(uniqid());
		}else {
			$product = array(
				'site_id'     => $site_id,
				'status'      => 0,
				'sku'         => strtoupper(uniqid()),
				'category_id' => 0,
				'on_sale'     => 1,
				'type'        => $type,
			);
		}
		
		
		$product_id = ProductService::get_instance()->add($product);
		
		return BLL_Product::get($product_id);
	}
}