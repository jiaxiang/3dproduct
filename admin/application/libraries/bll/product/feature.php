<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 商品特性BLL
 *
 * @author 王浩
 */
class BLL_Product_Feature {
	
	/**
	 * 根据 WHERE 条件获取商品特性列表
	 *
	 * @param array $where
	 * @return array
	 */
	static public function index($where)
	{
		$features = array();
        $feature_arr = AttributeService::get_instance()->index(array('where' => $where, 'orderby' => 'order'));
		foreach ($feature_arr as $feature)
		{
			$feature = coding::decode_feature($feature);
			
			$feature['options'] = array();
			
			$features[$feature['id']] = $feature;
		}
		
                
		if (!empty($features))
		{
            $options = Attribute_valueService::get_instance()->index(array(
				'where'   => array('attribute_id' => array_keys($features)),
				'orderby' => 'order'
			));
            
			foreach ($options as $option)
			{
				//$option = coding::decode_featureoption($option);
				$featid = $option['attribute_id'];
                
				unset($option['attribute_id']);
				unset($option['order']);
				
				$features[$featid]['options'][$option['id']] = $option;
			}
		}
		
		return $features;
	}
	
	/**
	 * 根据特性ID获取商品特性
	 *
	 * @param integer $feature_id
	 * @return array
	 */
	static public function get($feature_id)
	{
		$feature = FeatureService::get_instance()->get($feature_id);
		$feature = coding::decode_feature($feature);
		
		$options   = FeatureoptionService::get_instance()->index(array(
			'where'   => array('feature_id' => $feature['id']),
			'orderby' => 'order',
		));
		
		$feature['options'] = array();
		
		foreach ($options as $option)
		{
			unset($option['feature_id']);
			unset($option['order']);
			
			$feature['options'][$option['id']] = $option;
		}
		
		return $feature;
	}
	
	/**
	 * 通过商品ID获取商品与特性的关联
	 *
	 * @param integer $product_id
	 * @return array
	 */
	static public function get_fetuoptrs($product_id)
	{
		$fetuoptrs = array();
        $records = Product_attributeoption_relationService::get_instance()->index(array(
			'where' => array('product_id' => $product_id, 
                             'apply'=>AttributeService::ATTRIBUTE_FEATURE,
                ),
		));
        
		if (!empty($records))
		{
			foreach ($records as $record)
			{
				$fetuoptrs[$record['attribute_id']] = $record;
			}
		}
		return $fetuoptrs;
	}
	
	/**
	 * 设置商品特性关联
	 *
	 * @param array $product
	 * @return boolean
	 */
	static public function set_fetuoptrs(& $product)
	{        
		$fetuoptrs = isset($product['pdt_fetuoptrs'])?$product['pdt_fetuoptrs']:array();

		if ($product['classify_id'] > 0 && $fetuoptrs)
		{
            $product['product_featureoption_relation_struct'] = array();
			$features = self::get_clsfeturs($product['classify_id']);
			if ($features)
			{
				$rs = array();
				foreach ($fetuoptrs as $fetu_id => $opti_name)
				{
					if (isset($features[$fetu_id]))
					{
						$opti_name = trim($opti_name);
						if (!empty($opti_name))
						{
                            //输入项目
                            if($features[$fetu_id]['type']==1)
                            {
                                //组合商品属性保存
                    		    Product_attributeoption_relationService::get_instance()->add(array(
                    		    	'apply'              => AttributeService::ATTRIBUTE_FEATURE,
                    		    	'product_id'         => $product['id'],
                    		    	'attribute_id'       => $fetu_id,
                    		    	'attributeoption_id' => '0',
                                    'attribute_value'    => $opti_name
                    		    ));
                                
                                $rs[$fetu_id] = '0';
                            }
                            else
                            {
                                //选择项目
    							foreach ($features[$fetu_id]['options'] as $option)
    							{
    								if (strtolower(trim($option['name'])) === strtolower($opti_name))
    								{
                    					Product_attributeoption_relationService::get_instance()->add(array(
                    						'apply'              => AttributeService::ATTRIBUTE_FEATURE,
                    						'product_id'         => $product['id'],
                    						'attribute_id'       => $fetu_id,
                    						'attributeoption_id' => $option['id'],
                                            'attribute_value'    => $opti_name
                    					));
    									$rs[$fetu_id] = (string)$option['id'];
    									break;
    								}
    							}
                            }                            
						}
					}
				}
				if (!empty($rs))
				{
                    $product_feature_relation_struct = json_encode(array(
						'items' => array_keys($rs),
					));
                    $product_featureoption_relation_struct = json_encode(array(
						'items' => $rs,
					));
                    
                    //更新商品数据
                    $data = array(
                        'id' => $product['id'],
                        'product_feature_relation_struct' => $product_feature_relation_struct,
                        'product_featureoption_relation_struct' => $product_featureoption_relation_struct,
            			'update_time' => time()
            		);
                    ProductService::get_instance()->update($data);
                    
                    //更新货品数据
                    if(!empty($product['pdt_goods']))
                    {
            			foreach ($product['pdt_goods'] as $index => $good)
            			{
                            $data = array(
                                'id' => $good['id'],
                                'product_feature_relation_struct' => $product_feature_relation_struct,
                                'product_featureoption_relation_struct' => $product_featureoption_relation_struct,
                    			'update_time' => time()
                    		);
                            ProductService::get_instance()->update($data);
                        }
                    }
				}
			}
		}
		
		return TRUE;
	}
	
	/**
	 * 根据商品类型ID获取商品类型所关联的商品特性
	 *
	 * @param integer $classify_id
	 * @return array
	 */
	static public function get_clsfeturs($classify_id)
	{
		if ($classify_id > 0)
		{
			$clsfeturids = array();
            $records = Classify_attribute_relationService::get_instance()->index(array(
				'where' => array('classify_id' => $classify_id, 'apply' => AttributeService::ATTRIBUTE_FEATURE),
			));
			if (!empty($records))
			{
				foreach ($records as $record)
				{
					$clsfeturids[] = $record['attribute_id'];
				}
			}
			if (!empty($clsfeturids))
			{
				return self::index(array(
					'id' => $clsfeturids,
				));
			}
		}
		
		return array();
	}
}