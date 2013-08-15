<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 简单商品类型BLL
 *
 */
class BLL_Product_Type_Simple {
	
	/**
	 * 加载基本商品类型相关数据
	 *
	 * @param array $product
	 * @return boolean
	 */
	static public function load(& $product)
	{        
        $product['classify_id'] && $product['attributes'] = BLL_Product_Attribute::get_clsattrrs($product['classify_id']);
        
        //处理简单商品是否属于可配置商品或者组合商品的一项        
        $p_configurable = Product_assemblyService::get_instance()->query_assoc(array(
			'where'   => array(
                            'product_id' => $product['id'], 
                            'assembly_type' => ProductService::PRODUCT_TYPE_CONFIGURABLE
                        ),
			'orderby' => array('id' => 'asc'),
            'limit'   => array('per_page'=>1),
		));
        $configurable_id = (isset($p_configurable[0]['assembly_id']) && $p_configurable[0]['assembly_id']>0)?$p_configurable[0]['assembly_id']:0;
        if($configurable_id>0)
        {
            $product['configurable_id'] = $configurable_id;
            !$product['descsections'] && $product['descsections']   = BLL_Product_Detail::get($configurable_id);
            !$product['fetuoptrs'] && $product['fetuoptrs']         = BLL_Product_Feature::get_fetuoptrs($configurable_id);
        	!$product['relations'] && $product['relations']         = BLL_Product_Relation::get($configurable_id); 
            !$product['wholesales'] && $product['wholesales']       = BLL_Product_Wholesale::get($configurable_id);
            
            //可配置商品的关联图片更新检查
            if(!empty($product['goods_productpic_relation_struct']))
            {
                $configurable_pictures = BLL_Product_Picture::get($configurable_id);
                $productpicService = ProductpicService::get_instance();
                $product = coding::decode_attribute($product, 'goods_productpic_relation_struct');
                foreach($product['goods_productpic_relation_struct']['items'] as $pic_id)
                {
                    if(isset($configurable_pictures[$pic_id]))
                    {
                        $query_struct = array('where'=>
                            array(
                                'product_id' => $product['id'],
                                'image_id'   => $configurable_pictures[$pic_id]['image_id']                            
                            )
                        );
                        if(!$productpicService->count($query_struct)>0)
                        {
                            $productpic_data = array(
                                'product_id' => $product['id'],
                                'is_default' => ProductpicService::PRODUCTPIC_IS_DEFAULT_FALSE,
                                'title'      => $configurable_pictures[$pic_id]['title'],
                                'image_id'   => $configurable_pictures[$pic_id]['image_id']
                            );
                            $productpic_row_id = $productpicService->add($productpic_data);
                            $product['pictures'][$pic_id] = $configurable_pictures[$pic_id];
                        }
                    }
                }
            }
        }
    
		//获取商品规格
		$attroptrs  = array();
        if($product['attribute_struct_default'])
        {
    		$attroptrs  = json_decode($product['attribute_struct_default'], TRUE);
            $attroptrs  = $attroptrs['items'];
            if(is_array($attroptrs))
            {
                foreach($attroptrs as $aid=>$oid)
                {
                    $attroptrs[$aid] = $oid[0];
                }
            }
    		unset($product['attribute_struct_default']);
		}else{
    		$attroptrs = self::get_pdt_attroptrs($product['id']);
		}
        

		if(!empty($attroptrs))
		{
    		$product['attroptrs'] = $attroptrs;		
    		$product['attrrs'] = array_keys($attroptrs);
		}
		return TRUE;
	}
	
	/**
	 * 保存基本商品类型相关数据
	 *
	 * @param array $product
	 * @return boolean
	 */
	static public function save(& $product)
	{
        //是新商品，先创建商品数据ID
        if(empty($product['id']))
        {
            $pdata = array(
					'create_time'   => time()
				);
		    $product['id'] = ProductService::get_instance()->add($pdata);
        }
        else
        {
            //删除旧的属性关联数据，建立新的属性关联数据
    		ORM::factory('product_attributeoption_relation')
                ->where('product_id', $product['id'])
                ->delete_all();
        }
        
        if(isset($product['attribute_spec']) && is_array($product['attribute_spec']))
        {
            $attroptrs = array();
    		foreach ($product['attribute_spec'] as $aid => $vid)
    		{
    		    $aid = (string)$aid;
    		    $vid = (string)$vid;
    		    
    		    $attroptrs[$aid] = array($vid);
                
    			Product_attributeoption_relationService::get_instance()->add(array(
    				'apply'              => AttributeService::ATTRIBUTE_SPEC,
    				'product_id'         => $product['id'],
    				'attribute_id'       => $aid,
    				'attributeoption_id' => $vid,
    			));
    		}            
    		$product['attribute_struct'] = array(
    			'items' => array_keys($attroptrs),
    		);
    		$product['attribute_struct_default'] = array(
    			'items' => $attroptrs,
    		);
        }
            
        isset($product['front_visible']) || $product['front_visible'] = 1;
        $product['pdt_category_additional_id'] = isset($product['pdt_category_additional_id'])?implode(',', $product['pdt_category_additional_id']):'';
        
        //更新商品数据
        $data = array(
            'id'                 => $product['id'],
            'front_visible'      => $product['front_visible'], //1可见，0不可见
            'default_goods'      => 1, //1默认
			'type'               => ProductService::PRODUCT_TYPE_GOODS,
			'classify_id'        => $product['classify_id'],
			'brand_id'           => $product['brand_id'],
			'category_id'        => $product['category_id'],
			'category_ids'       => $product['pdt_category_additional_id'],
			'title'              => $product['title'],
			'name_manage'        => $product['name_manage'],
            'uri_name'           => $product['uri_name'],
			'sku'                => $product['sku'],
			'unit'               => $product['unit'],
			'price'              => $product['price'],
			'market_price'       => $product['market_price'],
			'cost'               => $product['cost'],
			'weight'             => $product['weight'],
			'store'              => $product['store'],
			'on_sale'            => $product['on_sale'],
			'is_wholesale'       => $product['is_wholesale'],
			'meta_title'         => $product['meta_title'],
			'meta_keywords'      => $product['meta_keywords'],
			'meta_description'   => $product['meta_description'],
			'brief'              => $product['brief'],
            'attribute_struct'   => isset($product['attribute_struct'])?json_encode($product['attribute_struct']):'',
            'attribute_struct_default' => isset($product['attribute_struct_default'])?json_encode($product['attribute_struct_default']):'',
			'made_date'          => $product['made_date'],
			'quality_date'       => $product['quality_date'],
			'quality_percent'    => $product['quality_percent'],
		);
        ProductService::get_instance()->update($data);
	}
	
	/**
	 * 删除基本商品类型相关数据
	 *
	 * @param array $product
	 * @return boolean
	 */
	static public function delete($product_id)
	{
		$binds = Product_assemblyService::get_instance()->index(array('where'=> array('product_id'=>$product_id)));
        if(!empty($binds))
        {
            foreach($binds as $bind)
            {
        		if (isset($bind['assembly_id']) && $bind['assembly_id']>0) {
                    $assembly_id = $bind['assembly_id'];
                    $pd = ProductService::get_instance()->get($assembly_id);
                    $pd_name = $pd['title'].($pd['name_manage']?'('.$pd['name_manage'].')':'');
        			throw new MyRuntimeException('该简单商品被捆绑在商品ID'.$assembly_id.'：'.$pd_name.'。请清除绑定后再进行删除！', 403);
        		}
            }
            ORM::factory('product_assembly')->where('product_id', $product_id)->delete_all();
        }
		ORM::factory('product_attributeoption_relation')->where('product_id', $product_id)->delete_all();
		
		return TRUE;
	}
	
	/**
	 * 通过商品ID设置货品的上下架状态
	 *
	 * @param integer $product_id
	 * @param integer $on_sale
	 * @return boolean
	 */
	static public function set_on_sale($product_id, $on_sale)
	{
		$goods = ProductService::get_instance()->index(array('where' => array(
			'product_id' => $product_id,
		)));
		
		if (!empty($goods))
		{
			foreach ($goods as $good)
			{
				$good['on_sale'] = $on_sale;
				ProductService::get_instance()->set($good['id'], $good);
			}
		}
		
		return TRUE;
	}
	
	/**
	 * 通过商品ID获取商品与规格项的关联
	 *
	 * @param integer $product_id
	 * @return array
	 */
	static protected function get_pdt_attroptrs($product_id)
	{
		$attroptrs = array();
		
		$records = Product_attributeoption_relationService::get_instance()->index(array(
			'where' => array('product_id' => $product_id, 'apply' => AttributeService::ATTRIBUTE_SPEC),
		));
        
		if (!empty($records))
		{
			foreach ($records as $record)
			{
                $attroptrs[$record['attribute_id']] = $record['attributeoption_id'];
			}
		}
		
		return $attroptrs;
	}
	
	
}