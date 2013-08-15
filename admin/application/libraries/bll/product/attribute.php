<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 商品规格BLL
 *
 * @author 王浩
 */
class BLL_Product_Attribute {
	
	/**
	 * 通过 WHERE 条件获取商品规格列表
	 *
	 * @param array $where
	 * @return array
	 */
	static public function index($where)
	{
		$attributes = array();
		foreach (AttributeService::get_instance()->index(array('where' => $where, 'orderby' => 'order')) as $attribute)
		{
			$attribute = coding::decode_attribute($attribute);
			
			$attribute['options'] = array();
			
			$attributes[$attribute['id']] = $attribute;
		}
		
		if (!empty($attributes))
		{
			$options = Attribute_valueService::get_instance()->index(array(
				'where'   => array('attribute_id' => array_keys($attributes)),
				'orderby' => 'order'
			));
			foreach ($options as $option)
			{
				//$option = coding::decode_attributeoption($option);
				$option['image'] = explode('|',$option['image']);
				$attrid = $option['attribute_id'];
				
				unset($option['attribute_id']);
				unset($option['order']);
				
				$attributes[$attrid]['options'][$option['id']] = $option;
			}
		}
		
		return $attributes;
	}
	
	/**
	 * 根据规格ID获取商品规格
	 *
	 * @param integer $attribute_id
	 * @return array
	 */
	static public function get($attribute_id)
	{
		$attribute = AttributeService::get_instance()->get($attribute_id);
		$attribute = coding::decode_attribute($attribute);
		
		$options   = Attribute_valueService::get_instance()->index(array(
			'where'   => array('attribute_id' => $attribute['id']),
			'orderby' => 'order',
		));
		
		$attribute['options'] = array();
		
		foreach ($options as $option)
		{
			unset($option['attribute_id']);
			unset($option['order']);
			
			$attribute['options'][$option['id']] = $option;
		}
		
		return $attribute;
	}
	
	/**
	 * 获取规格与规格值
	 *
	 * @param array $attroptrs
	 * @return array
	 */
	static public function get_attropts($attroptrs)
	{
		$attropts = array();
		
		if (!empty($attroptrs))
		{
			$attributes = self::index(array(
				'id' => array_keys($attroptrs),
			));
			
			foreach ($attributes as $attrid => $attribute)
			{
				foreach ($attribute['options'] as $optid => $option)
				{
					if (!in_array($optid, $attroptrs[$attrid]))
					{
						unset($attribute['options'][$optid]);
					}
				}
				
				$attributes[$attrid] = $attribute;
			}
		}
		
		return $attropts;
	}
	
	static public function get_clsattrrs($classify_id, $type=AttributeService::ATTRIBUTE_SPEC)
	{
		if ($classify_id > 0)
		{
			$attroptrids = array();
			$records = Classify_attribute_relationService::get_instance()->index(array(
				'where' => array('classify_id' => $classify_id, 'apply'=>$type),
			));
            //d($records);
			if (!empty($records))
			{
				foreach ($records as $record)
				{
					$attroptrids[] = $record['attribute_id'];
				}
			}
			if (!empty($attroptrids))
			{
				return self::index(array(
					'id' => $attroptrids,
				));
			}
		}		
		return array();
	}
	
	static public function get_pdtattrrs($product_id, $type='')
	{
		$pdtattrs = array();
        $qs = array(
			'where' => array('product_id' => $product_id),
		);
        if($type)
        {
            $qs['where']['apply'] = $type;
        }
		$records = Product_attributeoption_relationService::get_instance()->index($qs);
		if (!empty($records))
		{
			foreach ($records as $record)
			{
				if (!isset($pdtattrs[$record['attribute_id']]))
				{
					$pdtattrs[$record['attribute_id']] = array();
				}
				$pdtattrs[$record['attribute_id']][] = $record['attributeoption_id'];
			}
            return $pdtattrs;
		}
	}
	
}