<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 组合商品类型BLL
 *
 */
class BLL_Product_Type_Assembly {
	
	/**
	 * 加载组合商品类型相关数据
	 *
	 * @param array $product
	 * @return boolean
	 */
	static public function load(& $product)
	{
		/**
		 * 获取商品规格与货品
		 */         
		$attroptrs  = array();
		//$attroptrs  = self::get_pdt_attroptrs($product['id']);
        if($product['attribute_struct_default'])
        {
    		$attroptrs  = json_decode($product['attribute_struct_default']);
            $attroptrs  = (array)@$attroptrs->items;
    		$product['attroptrs'] = $attroptrs;		
    		$product['attrrs'] = array_keys($attroptrs);
    		unset($product['attribute_struct_default']);
        }
        
		if (!empty($attroptrs))
		{
			$goods = ProductService::get_instance()->index(array(
				'where' => array('product_id' => $product['id'])
			));
			
			$product['store'] = -1;
			
			foreach ($goods as $index => $good)
			{
				$good = coding::decode_good($good);
				if (isset($good['attribute_struct_default']['items'])) {
					$good['attroptrs'] = $good['attribute_struct_default']['items'];
				}else {
					$good['attroptrs'] = array();
				}
				
				$good['picrels'] = array();
				if (!empty($good['goods_productpic_relation_struct']['items']))
				{
					$good['picrels'] = $good['goods_productpic_relation_struct']['items'];
				}
				
				// 重新计算商品库存，以货品库存为准
				if ($good['store'] > -1)
				{
					if ($product['store'] == -1)
					{
						$product['store'] = 0;
					}
					$product['store'] += $good['store'];
				}
				
				unset($good['attribute_struct_default']);
				unset($good['goods_productpic_relation_struct']);
				unset($good['product_id']);
				
				$goods[$index] = $good;
			}
		} else {
			// 重新计算商品库存，以货品库存为准
			$good = ProductService::get_instance()->query_row(array(
				'where' => array('product_id' => $product['id']),
			));
			if (!empty($good) AND isset($good['store']))
			{
				$product['store'] = $good['store'];
			}
		}
		
		if (empty($goods))
		{
			$product['goods']      = array();
			$product['attributes'] = array();
		} else {
			$product['goods']      = $goods;
			$product['attributes'] = BLL_Product_Attribute::index(array(
				'id' => array_keys($attroptrs),
			));
		}		
		//$product['attroptpicrs'] = BLL_Product_Picture::get_pdt_attroptpicrs($product['id']);
		return TRUE;
	}
	
	/**
	 * 保存组合商品类型相关数据
	 *
	 * @param array $product
	 * @return boolean
	 */
	static public function save(& $product)
	{
        //先创建商品数据ID
        if(empty($product['id']))
        {
            $pdata = array(
					'create_time'   => time()
				);
		    $product['id'] = ProductService::get_instance()->add($pdata);
        }
        $product['pdt_category_additional_id'] = isset($product['pdt_category_additional_id'])?implode(',', $product['pdt_category_additional_id']):'';
        
		// 将商品类型设置为 0 简单商品处理
		if(!empty($product['pdt_goods']))
		{
			$attroptrs = array();
            
			$product['store']         = 0;
			$product['on_sale']       = 0;
			$product['default_goods'] = 0;
            
			foreach ($product['pdt_goods'] as $index => $good)
			{
				$good['type'] = ProductService::PRODUCT_TYPE_GOODS;
				$good['product_id'] = $product['id'];
                $good['classify_id'] = $product['classify_id'];
				$good['brand_id'] = $product['brand_id'];
				$good['category_id'] = $product['category_id'];
                $good['is_wholesale'] = $product['is_wholesale'];
                $good['category_ids'] = $product['pdt_category_additional_id'];
				$good['unit'] = $product['unit'];
    			$good['name_manage'] = $product['name_manage'];
                $good['uri_name'] = $product['uri_name'].$index;
    			$good['meta_title'] = $product['meta_title'];
    			$good['meta_keywords'] = $product['meta_keywords'];
    			$good['meta_description'] = $product['meta_description'];
    			$good['brief'] = $product['brief'];
                $good['made_date'] = $product['made_date'];
			    $good['quality_date'] = $product['quality_date'];
			    $good['quality_percent'] = $product['quality_percent'];

				isset($good['default_goods']) ||  $good['default_goods'] = 0;
				isset($good['front_visible']) ||  $good['front_visible'] = 0;
				isset($good['on_sale'])    ||  $good['on_sale']    = 0;
				empty($good['sku'])        AND $good['sku']        = product::create_good_sku($product['sku']);
				empty($good['title'])      AND $good['title']      = product::create_good_title($product['title'], $good['attroptrs']);
				
				$good['attribute_struct'] = array(
					'items' => array_keys($good['attroptrs']),
				);
                $good['attribute_struct_default'] = array(
					'items' => $good['attroptrs'],
				);
				
				// 获取商品与规格值的关联
				foreach ($good['attroptrs'] as $aid => $oid)
				{
					$aid = (string)$aid;
					$oid = (string)$oid;
					
					isset($attroptrs[$aid])          OR $attroptrs[$aid]   = array();
					in_array($oid, $attroptrs[$aid]) OR $attroptrs[$aid][] = $oid;
				}
				
				// 商品价格等信息从默认货品获取
				if ($good['default_goods'] == 1)
				{
					$product['price']        = $good['price'];
					$product['market_price'] = $good['market_price'];
					$product['cost']         = $good['cost'];
					$product['weight']       = $good['weight'];
				}
                
				// 判断商品的上下架
				if ($product['on_sale'] == 0 && $good['on_sale'] == 1)
				{
					$product['on_sale'] = 1;
				}
				
				// 计算商品的库存
				if ($good['store'] > 0)
				{
					$product['store'] += $good['store'];
				}
				
				// 处理货品的关联商品图片
				if (!empty($good['picrels']))
				{
					is_array($good['picrels']) OR $good['picrels'] = explode(',', $good['picrels']);

					/*foreach ($good['picrels'] as $i => $picrel)
					{
						if (!isset($product['pictures'][$picrel]))
						{
							unset($good['picrels'][$i]);
						}
					}*/
					if (!empty($good['picrels']))
					{
						$good['goods_productpic_relation_struct'] = array(
							'items' => $good['picrels'],
						);
					}
				} else {
					$good['goods_productpic_relation_struct'] = array();
				}
				
				$product['pdt_goods'][$index] = $good;
			}
            
			// 商品价格等信息从默认货品获取
			if (!isset($product['price']) || $product['price'] == 0)
			{
				$product['price']        = $product['pdt_goods'][$index]['price'];
				$product['market_price'] = $product['pdt_goods'][$index]['market_price'];
				$product['cost']         = $product['pdt_goods'][$index]['cost'];
				$product['weight']       = $product['pdt_goods'][$index]['weight'];
			}
            //dump($product['pdt_goods']);
            
			//删除组合商品、所有被关联的简单商品旧属性，然后保存新的属性
			ORM::factory('product_attributeoption_relation')
                ->where('product_id', $product['id'])
                ->delete_all();
			ORM::factory('product_attributeoption_relation')
                ->where('assembly_id', $product['id'])
                ->delete_all();
			foreach ($attroptrs as $aid => $oids)
			{
				foreach ($oids as $oid)
				{
					Product_attributeoption_relationService::get_instance()->add(array(
						'apply'              => AttributeService::ATTRIBUTE_SPEC,
						'product_id'         => $product['id'],
						'attribute_id'       => $aid,
						'attributeoption_id' => $oid,
					));
				}
			}
			$product['attribute_struct'] = array(
				'items' => array_keys($attroptrs),
			);
			$product['attribute_struct_default'] = array(
				'items' => $attroptrs,
			);
            
			//获取原有货品ID
			$old_gids = array();
			$old_goods = ProductService::get_instance()->index(array(
				'where' => array('product_id' => $product['id']),
			));
			foreach ($old_goods as $good)
			{
				$old_gids[$good['id']] = TRUE;
			}
			
			// 保存货品、货品与规格值关联、货品与商品图片关联
			foreach ($product['pdt_goods'] as $index => $good)
			{
				$good = coding::encode_good($good);
				if (isset($good['id']))
				{
					unset($old_gids[$good['id']]);
                    $good['update_time'] = time();
					ProductService::get_instance()->update($good);
				} else {
                    $good['create_time'] = time();
					$good['id'] = ProductService::get_instance()->add($good);
				}
                
                //记录简单商品的所有规格信息
				foreach ($good['attroptrs'] as $aid => $oid)
				{
					Product_attributeoption_relationService::get_instance()->add(array(
						'apply'              => AttributeService::ATTRIBUTE_SPEC,
                        'assembly_id'        => $product['id'],
						'product_id'         => $good['id'],
						'attribute_id'       => $aid,
						'attributeoption_id' => $oid,
					));
				}
                $product['pdt_goods'][$index] = $good;
			}
			
			if (!empty($old_gids))
			{
				ORM::factory('product')->in('id', array_keys($old_gids))->delete_all();
			}            
		}
        else
        {
			//处理默认货品
			//ORM::factory('product_attributeoption_productpic_relation')->where('product_id', $product['id'])->delete_all();
			ORM::factory('product_attributeoption_relation')
                ->where('product_id', $product['id'])
                ->delete_all();
            ORM::factory('product_attributeoption_relation')
                ->where('assembly_id', $product['id'])
                ->delete_all();
            ORM::factory('product')->where('product_id', $product['id'])->delete_all();
            $product['default_goods'] = 1;
		}
        
        //更新商品数据
        $data = array(
            'front_visible'      => 1, //1可见，0不可见
            'id'                 => $product['id'],
			'type'               => ProductService::PRODUCT_TYPE_ASSEMBLY,
            'default_goods'      => $product['default_goods'],
			'classify_id'        => $product['classify_id'],
			'is_wholesale'       => $product['is_wholesale'],
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
	 * 删除组合商品类型相关数据
	 *
	 * @param array $product
	 * @return boolean
	 */
	static public function delete($product_id)
	{
		$goods = ProductService::get_instance()->index(array('where'=> array('product_id'=>$product_id)));
		if (!empty($goods)){
			$goods_ids = array();
			for ($i=0; $i<count($goods); $i++){
				$goods_ids[] = $goods[$i]['id'];
			}
			$binds = Product_bind_goodService::get_instance()->index(array('where'=> array('goods_id'=>$goods_ids)));
			if (!empty($binds)) {
				throw new MyRuntimeException('该商品有货品被捆绑在其他货品，请清除绑定后再进行删除！', 403);
			}
		}
		//ORM::factory('product_attributeoption_productpic_relation')->where('product_id', $product_id)->delete_all();
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
				$data['on_sale'] = $on_sale;
				ProductService::get_instance()->set($good['id'], $data);
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
			'where' => array('product_id' => $product_id),
		));
        
		if (!empty($records))
		{
			foreach ($records as $record)
			{
				$attr_id = $record['attribute_id'];
				$opti_id = $record['attributeoption_id'];
				
				if (!isset($attroptrs[$attr_id]))
				{
					$attroptrs[$attr_id] = array();
				}
				
				if (!in_array($opti_id, $attroptrs[$attr_id]))
				{
					$attroptrs[$attr_id][] = $opti_id;
				}
			}
		}
		
		return $attroptrs;
	}
	
	/**
	 * 检查货品SKU是否重复
	 *
	 * @param integer $site_id
	 * @param string $sku
	 * @param integer $good_id
	 * @return boolean
	 */
	static public function good_sku_exists($sku, $good_id = 0)
	{
		$query_struct = array('where' => array(
			'sku'     => trim($sku),
		));
		if ($good_id > 0)
		{
			$query_struct['where']['id !='] = $good_id;
		}
		
		return ProductService::get_instance()->count($query_struct) > 0 ? TRUE : FALSE;
	}
    
	/**
	 * 检查商品规格是否重复
	 *
	 * @param integer $assembly_id
	 * @param array $attribute_spec：key作为attribute_id，value作为attributeoption_id
	 * @param integer $product_id
	 * @return boolean
	 */
	static public function attribute_spec_exists($assembly_id, $attribute_spec, $product_id=0)
	{
        $products = $rs = array();
        $item_data = create_function('$item', 'return $item->as_array();');
        $product_attribute_orm = ORM::factory('product_attributeoption_relation');
        foreach($attribute_spec as $aid => $vid)
        {
            //输入项目不检测
            if($vid==0)continue;
            
            $product_attribute_orm->where('assembly_id', $assembly_id);
    		if($product_id > 0)
    		{
                $product_attribute_orm->where('product_id !=', $product_id);
    		}
            $product_attribute_orm->where('attribute_id', $aid);
            $product_attribute_orm->where('attributeoption_id', $vid);
            $products = array_map($item_data, $product_attribute_orm->find_all()->as_array());
            foreach($products as $product)
            {
                $rs[] = $product['product_id'];
            }            
        }
        
        //通过计算是否有重复的product_id，判断是否有相同规格信息的产品数据
		return count($rs) > count(array_unique($rs)) ? TRUE : FALSE;
	}
    
}