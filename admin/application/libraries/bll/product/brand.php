<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 商品品牌BLL
 *
 * @author 王浩
 */
class BLL_Product_Brand {
	
	/**
	 * 通过商品类型ID获取商品类型所关联的商品品牌
	 *
	 * @param integer $classify_id
	 * @return array
	 */
	static public function get_clsbrdrs($classify_id = 0)
	{
		$brands = $query_struct = array();
		
		if ($classify_id > 0)
		{
            $query_struct_relation = array(
				'where' => array('classify_id' => $classify_id)
			);
    		$brand_ids = array();
    		$records = Classify_brand_relationService::get_instance()->index($query_struct_relation);
            
    		foreach ($records as $record)
    		{
    			if (!in_array($record['brand_id'], $brand_ids))
    			{
    				$brand_ids[] = $record['brand_id'];
    			}
    		}
    		if (!empty($brand_ids))
    		{
                $query_struct = array(
    				'where'   => array('id' => $brand_ids),
    				'orderby' => array('name' => 'ASC'),
			    );
    		}
        }
		$brands = BrandService_Core::get_instance()->index($query_struct);
		return $brands;
	}
    
}