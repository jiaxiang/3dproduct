<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 可配置商品类型BLL
 */
class BLL_Product_Type_Configurable {
	
	/**
	 * 加载可配置商品类型相关数据
	 *
	 * @param array $product
	 * @return boolean
	 */
	static public function load(& $product)
	{
		/**
		 * 获取商品规格、简单商品
		 */
        $goods = array();         
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
			$product['store'] = 0;
            $good_ids = Product_assemblyService::get_instance()->index(array(
				'where' => array('assembly_id' => $product['id'])
			));
            
            //依次获得简单商品数据
			foreach($good_ids as $good_id)
            {
                $good = ProductService::get_instance()->get($good_id['product_id']);
				$good = coding::decode_good($good);
                $good['default_goods'] = $good_id['is_default']?$good_id['is_default']:0;
                
                $good['attroptrs'] = array();
				if (isset($good['attribute_struct_default']['items'])) {
                    foreach($good['attribute_struct_default']['items'] as $aid=>$oid)
                    {
                        $good['attroptrs'][$aid] = $oid[0];
                    }
				}
                
				$good['picrels'] = array();
				if (!empty($good['goods_productpic_relation_struct']['items']))
				{
					$good['picrels'] = $good['goods_productpic_relation_struct']['items'];
				}
				
				// 重新计算商品库存，以货品库存为准
				if ($good['store'] > 0)
				{
					$product['store'] += $good['store'];
				}
				
				unset($good['attribute_struct_default']);
				unset($good['goods_productpic_relation_struct']);
                
    			$goods[] = $good;
                unset($good);
			}
		}
        /* 
        else 
        {
			// 重新计算商品库存，以货品库存为准
			$good = ProductService::get_instance()->query_row(array(
				'where' => array('product_id' => $product['id']),
			));
			if (!empty($good) AND isset($good['store']))
			{
				$product['store'] = $good['store'];
			}
		}*/
		
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
	 * 保存可配置商品类型相关数据
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
					'create_time' => time()
				);
		    $product['id'] = ProductService::get_instance()->add($pdata);
        }
        $product['pdt_category_additional_id'] = isset($product['pdt_category_additional_id'])?implode(',', $product['pdt_category_additional_id']):'';
        
		//简单商品处理
		if(!empty($product['pdt_goods']))
		{
			$attroptrs = array();
            $is_default = false;
            $product['price']         = 0;
			$product['store']         = 0;
			$product['on_sale']       = 0;
			$product['default_goods'] = 0;
            
			foreach ($product['pdt_goods'] as $index => $good)
			{
                //简单商品基本信息初始从可配置商品中获得
                if(empty($good['id']))
                {
    				$good['type'] = ProductService::PRODUCT_TYPE_GOODS;
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
                }
                else if($good['id']>0)
                {
                    //更新简单商品一部分基本信息
                    $good_org = ProductService::get_instance()->get($good['id']);
                    $good_org['is_wholesale']==0 && $good['is_wholesale'] = $product['is_wholesale'];
                    empty($good_org['category_ids']) && $good['category_ids'] = $product['pdt_category_additional_id'];
        			empty($good_org['meta_title']) && $good['meta_title'] = $product['meta_title'];
        			empty($good_org['meta_keywords']) && $good['meta_keywords'] = $product['meta_keywords'];
        			empty($good_org['meta_description']) && $good['meta_description'] = $product['meta_description'];
        			empty($good_org['brief']) && $good['brief'] = $product['brief'];
                    unset($good_org);
                }
                
				isset($good['default_goods']) ||  $good['default_goods'] = 0;
				isset($good['front_visible']) ||  $good['front_visible'] = 0;
				isset($good['on_sale'])    ||  $good['on_sale']          = 0;
				empty($good['sku'])        && $good['sku']               = product::create_good_sku($product['sku']);
				empty($good['title'])      && $good['title']             = product::create_good_title($product['title'], $good['attroptrs']);
				
				$g_attroptrs = array();
				foreach($good['attroptrs'] as $aid => $avid)
                {
                    $g_attroptrs[$aid] = array($avid);
                }
				$good['attribute_struct'] = array(
					'items' => array_keys($good['attroptrs']),
				);
                $good['attribute_struct_default'] = array(
					'items' => $g_attroptrs,
				);
				unset($g_attroptrs);
                
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
                    $is_default = true;
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
            
			//没有设置默认货品，商品价格等信息从最后的货品获取
			if ($is_default == false || $product['price'] == 0)
			{
                //$product['pdt_goods'][$index]['default_goods'] = 1;
				$product['price']                              = $product['pdt_goods'][$index]['price'];
				$product['market_price']                       = $product['pdt_goods'][$index]['market_price'];
				$product['cost']                               = $product['pdt_goods'][$index]['cost'];
				$product['weight']                             = $product['pdt_goods'][$index]['weight'];
			}
            
			//删除可配置商品、所有被关联的简单商品旧属性，然后保存新的属性
			ORM::factory('product_attributeoption_relation')
                ->where('product_id', $product['id'])
                ->delete_all();
			ORM::factory('product_attributeoption_relation')
                ->where('configurable_id', $product['id'])
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
            
            //删除可配置商品与简单商品的关联关系
            ORM::factory('product_assembly')->where('assembly_id', $product['id'])->delete_all();
            
			//保存简单商品、简单商品与规格值关联、简单商品与可配置商品关联
			foreach ($product['pdt_goods'] as $index => $good)
			{
				$good = coding::encode_good($good);
				if (isset($good['id']))
				{
                    $good['update_time'] = time();
					ProductService::get_instance()->update($good);
				} 
                else 
                {
                    $good['create_time'] = time();
					$good['id'] = ProductService::get_instance()->add($good);
				}
                
                //记录简单商品的所有规格信息
				foreach ($good['attroptrs'] as $aid => $oid)
				{
					Product_attributeoption_relationService::get_instance()->add(array(
						'apply'              => AttributeService::ATTRIBUTE_SPEC,
                        'configurable_id'    => $product['id'],
						'product_id'         => $good['id'],
						'attribute_id'       => $aid,
						'attributeoption_id' => $oid,
					));
				}
                
                //建立可配置商品与货品的关联关系
                Product_assemblyService::get_instance()->add(array(
                    'is_default'    => $good['default_goods'],
                    'assembly_type' => ProductService::PRODUCT_TYPE_CONFIGURABLE,
                    'assembly_id'   => $product['id'],
			        'product_id'    => $good['id'],                        
                ));
                $product['pdt_goods'][$index] = $good;
			}   
		}
        else
        {
			//处理默认货品
			//ORM::factory('product_attributeoption_productpic_relation')->where('product_id', $product['id'])->delete_all();
			ORM::factory('product_attributeoption_relation')->where('product_id', $product['id'])->delete_all();
            //ORM::factory('product_attributeoption_relation')->where('configurable_id', $product['id'])->delete_all();
            //ORM::factory('product')->where('product_id', $product['id'])->delete_all();
            $product['default_goods'] = 1;
		}
        
        //更新商品数据
        $data = array(
            'front_visible'      => 1, //1可见，0不可见
            'id'                 => $product['id'],
			'type'               => ProductService::PRODUCT_TYPE_CONFIGURABLE,
            'default_goods'      => $product['default_goods'],
			'category_id'        => $product['category_id'],
			'classify_id'        => $product['classify_id'],
			'brand_id'           => $product['brand_id'],
			'is_wholesale'       => $product['is_wholesale'],
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
			'update_time'        => time()
		);
        ProductService::get_instance()->update($data);
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
		$goods = Product_assemblyService::get_instance()->index(array('where' => array(
			'assembly_id' => $product_id,
		)));
		f($goods,1);
		if (!empty($goods))
		{
			foreach ($goods as $good)
			{
				$data['on_sale'] = $on_sale;
                $data['update_time'] = time();
				ProductService::get_instance()->set($good['product_id'], $data);
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
	 * @param integer $configurable_id
	 * @param array $attribute_spec：key作为attribute_id，value作为attributeoption_id
	 * @param integer $product_id
	 * @return boolean
	 */
	static public function attribute_spec_exists($configurable_id, $attribute_spec, $product_id=0)
	{
        $products = $rs = array();
        $item_data = create_function('$item', 'return $item->as_array();');
        $product_attribute_orm = ORM::factory('product_attributeoption_relation');
        foreach($attribute_spec as $aid => $vid)
        {
            //输入项目不检测
            if($vid==0)continue;
            
            $product_attribute_orm->where('configurable_id', $configurable_id);
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
		return count($rs)==1 || count($rs) > count(array_unique($rs)) ? TRUE : FALSE;
	}
    
}