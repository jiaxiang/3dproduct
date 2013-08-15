<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 商品模板BLL
 *
 * @author 葛海峰
 */
class BLL_Pdttpl {
	
	public function set_product_template(&$request_data,&$product){
		$p_t = isset( $request_data['product'] ) ? $request_data['product'] : array();
		if (empty($p_t)) {
			throw new MyRuntimeException('服务器没有接收到模板设置的信息！', 400);
		}
		
		$template = array();
		//print_r($product);die();
		foreach ($p_t as $key => $value){
			if ($value == 'on'){
				if ($key == 'product_featureoption_relation_struct') {
					if(!empty($product['fetuoptrs'])){
						$template['product_featureoption_relation_struct'] = '{"items":'.json_encode($product['fetuoptrs']).'}';
						$str = ':';
						foreach ($product['fetuoptrs'] as $key => $value){
							$str .= "$key:";
						}
						$template['product_feature_relation_struct'] = '{"items":"'.$str.'"}';
					}else {
						$template['product_featureoption_relation_struct'] = '';
						$template['product_feature_relation_struct'] = '';
					}
				}
				if (isset($product[$key])) {
					$template[$key] = $product[$key];
				}
			}
		}
		
		if (isset($template['descsections'])) {
			$template['descsections'] = isset( $product['descsections'][0]['content'] ) ? $product['descsections'][0]['content'] : '' ;
		}
		if (!isset($template['on_sale'])) {
			$template['on_sale'] = 1;
		}
		if (empty($template)) {
			throw new MyRuntimeException('没有勾选任何模板信息，模板更新失败！', 400);
		}
		$template_id = Product_templateService::get_instance()->is_template_exist($product['site_id']);
		if ( $template_id > 0 ) {
			Product_templateService::get_instance()->set( $template_id, $template );
			return 1;
		}else {
			$template['site_id'] = $product['site_id'];
			return Product_templateService::get_instance()->add( $template );
		}
	}
	
}