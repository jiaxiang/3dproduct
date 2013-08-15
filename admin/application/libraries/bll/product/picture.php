<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 商品图片BLL
 *
 * @author 王浩
 */
class BLL_Product_Picture {
	
	/**
	 * 通过商品ID获取商品图片列表
	 *
	 * @param integer $product_id
	 * @return array
	 */
	static public function get($product_id)
	{
		$pictures = array();		
		$records = ProductpicService::get_instance()->get_stand_pic_list_by_product_id($product_id, ProductpicService::PRODUCTPIC_STANDARDS_COMMON);
		foreach ($records as $index => $picture)
		{
			unset($picture['product_id']);			
			$pictures[$picture['id']] = $picture;
		}
		return $pictures;
	}
	
	/**
	 * 通过商品ID删除商品图片
	 *
	 * @param integer $product_id
	 * @return boolean
	 */
	static public function delete($product_id)
	{
        $ProductpicService = ProductpicService::get_instance();
        $pictures = $ProductpicService->index(array('where'=> array('product_id'=>$product_id)));
        if(empty($pictures))return FALSE;
		foreach ($pictures as $picture)
		{
            if(empty($picture['image_id']))continue;
            $pic_count = $ProductpicService->count(array('where'=> array('image_id'=>$picture['image_id'])));
            if($pic_count<=1)$ProductpicService->delete_productpic_attachment($picture);
		}
        ORM::factory('productpic')->where('product_id', $product_id)->delete_all();
        return TRUE;
	}
	
	/**
	 * 根据商品ID获取该商品的规格值与商品图片关联
	 *
	 * @param integer $product_id
	 * @return array
	 */
	static public function get_pdt_attroptpicrs($product_id)
	{
		$attroptpicrs = array();
		
		$records = Product_attributeoption_productpic_relationService::get_instance()->index(array(
			'where' => array('product_id' => $product_id),
		));
		if (!empty($records))
		{
			foreach ($records as $record)
			{
				isset($attroptpicrs[$record['attribute_id']])                                OR $attroptpicrs[$record['attribute_id']]                                = array();
				isset($attroptpicrs[$record['attribute_id']][$record['attributeoption_id']]) OR $attroptpicrs[$record['attribute_id']][$record['attributeoption_id']] = array();
				
				if (!in_array($record['productpic_id'], $attroptpicrs[$record['attribute_id']][$record['attributeoption_id']]))
				{
					$attroptpicrs[$record['attribute_id']][$record['attributeoption_id']][] = $record['productpic_id'];
				}
			}
		}
		
		return $attroptpicrs;
	}
	
	/**
	 * 删除商品图片与规格值的关联
	 *
	 * @param integer $product_id
	 * @return boolean
	 */
	static public function rmv_pdt_attroptpicrs($product_id)
	{
		ORM::factory('product_attributeoption_productpic_relation')->where('product_id', $product_id)->delete_all();
		
		return TRUE;
	}
}