<?php

class ExportService_Core {
	protected $site_names     = array();
	protected $brand_names    = array();
	protected $classify_names = array();
	protected $category_names = array();
	
	protected $attributes = array();
	protected $features   = array();
	protected $arguments  = array();
	/* 兼容php5.2环境 Start */
    private static $instance = NULL;
    // 获取单态实例
    public static function get_instance()
    { 
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
	
	public function run($products)
	{
		$csv_array = array();
		if ($products)
		{
			$result = $this->format($products);
			foreach ($result as $classify_id => $products)
			{
				foreach ($products as $product)
				{
					$csv_array[] = $this->get_titlebar($classify_id);
                    //dump($csv_array);
					//foreach ($products as $product){
						$product = coding::decode_product($product);
                        $desc  = Product_detailService::get_instance()->get_by_product_id($product['id']);
						$goods = ProductService::get_instance()->query_assoc(array(
							'where' => array(
								'product_id' => $product['id'],
							),
							'orderby' => array(
								'default_goods' => 'DESC',
							),
						));
						
						if (!empty($this->arguments))
						{
							$arguments = Product_argumentService::get_instance()->query_row(array('where' => array(
								'product_id' => $product['id'],
							)));
							$arguments = empty($arguments) ? array() : json_decode($arguments['arguments'], TRUE);
						}
                        
						$has_goods    = FALSE;
						$default_good = array();
						foreach ($goods as $good)
						{
							if (!empty($good['attribute_struct']['items']))
							{
								$has_goods = TRUE;
							}
							if ($good['default_goods'] == 1)
							{
								$default_good = $good;
								break;
							}
						}
                        
						/*if ($default_good AND $goods)
						{
							$default_good = $goods[key($goods)];
						}*/
						$p_row = array();
						$p_row[] = '';
						$p_row[] = '';
						$p_row[] = $this->get_category_names($product['category_id']);
						$p_row[] = $product['sku'];
						$p_row[] = '';
						$p_row[] = $product['name_manage'];
						$p_row[] = $product['title'];
						$p_row[] = $product['brand_id'] > 0 ? $this->get_brand_name($product['brand_id']) : '';
						
						if (!$has_goods)
						{
							$p_row[] = $product['on_sale'] == 1 ? 'Y' : 'N';
							$p_row[] = $product['price'];
							$p_row[] = $product['market_price'];
						} else {
							$p_row[] = '';
							$p_row[] = '';
							$p_row[] = '';
						}
						
						$p_row[] = '';  // 商品图片						
                       
						/**
						 * 处理商品规格
						 */
						if ($has_goods)
						{
							/*$attribute_option_ids = $product['argumrs_struct_default']['items'];
							$same_attributes = TRUE;
							if (count($this->attributes) == count($attribute_option_ids))
							{
								foreach ($this->attributes as $attribute)
								{
									if (!isset($attribute_option_ids[$attribute['id']]))
									{
										$same_attributes = FALSE;
										break;
									}
								}
							} else {
								$same_attributes = FALSE;
							}
							if ($same_attributes == TRUE)
							{
								$attributes = $this->attributes;
								$p_row[] = '';
							} else {
								$attributes = AttributeService::get_instance()->get_attribute_options(array(
									'where' => array(
										'id' => array_keys($attribute_option_ids),
									),
									'orderby' => array(
										'id' => 'ASC',
									),
								));*/
								$attribute_names = '';
								foreach ($this->attributes as $attribute)
								{
									if (!empty($attribute_names))
									{
										$attribute_names .= '|';
									}
									$attribute_names .= $attribute['name'];
								}
								$p_row[] = '规格:'.$attribute_names;
							//}
						} else {
							$p_row[] = '';
						}
						
						$p_row[] = $product['brief'];
						
						/**
						 * 处理商品的详细描述
						 */
						if ($desc)
						{
							$p_row[] = $desc['description'];
						} else {
							$p_row[] = '';
						}
                        
						/**
						 * 处理商品的成本、库存、重量，当商品有规格时，在商品行不显示此三项信息
						 */
						if (!$has_goods)
						{
							$p_row[] = $product['cost'];
							$p_row[] = $product['store'];
							$p_row[] = empty($default_good) ? '0' : $default_good['weight'];
						} else {
							$p_row[] = '';
							$p_row[] = '';
							$p_row[] = '';
						}
						
						$p_row[] = $desc['meta_title'];
						$p_row[] = $desc['meta_keywords'];
						$p_row[] = $desc['meta_description'];
                        
						/**
						 * 处理货品与特性的关联
						 */
						$feature_option_ids = empty($product['product_featureoption_relation_struct']['items'])
											? array()
											: $product['product_featureoption_relation_struct']['items'];
						
						foreach ($this->features as $feature)
						{
							if (isset($feature_option_ids[$feature['id']]))
							{
								$p_row[] = $feature['options'][$feature_option_ids[$feature['id']]]['name'];
							} else {
								$p_row[] = '';
							}
						}
						
						foreach ($this->arguments as $argument_group)
						{
							foreach ($argument_group['items'] as $argument)
							{
								if (isset($arguments[$argument_group['name']]) AND isset($arguments[$argument_group['name']][$argument['name']]))
								{
									$p_row[] = $arguments[$argument_group['name']][$argument['name']];
								} else {
									$p_row[] = '';
								}
							}
						}
						$csv_array[] = $p_row;
						
						/**
						 * 处理货品
						 */
						if ($has_goods)
						{
							foreach ($goods as $good)
							{
								$is_err = FALSE;
								$good   = coding::decode_good($good);
								$g_row  = array();
								$g_row[] = '';
								$g_row[] = '';
								$g_row[] = '';
								$g_row[] = '';
								$g_row[] = $good['sku'];
								$g_row[] = '';
								$g_row[] = $good['title'];
								$g_row[] = '';
								$g_row[] = $good['on_sale'] == 1 ? 'Y' : 'N';
								$g_row[] = $good['price'];
								$g_row[] = $good['market_price'];
								$g_row[] = '';
								
								$option_name = '';
								foreach ($this->attributes as $attribute)
								{
									if ($option_name != '')
									{
										$option_name .= '|';
									}
									if (!isset($good['argumrs_struct']['items'][$attribute['id']]))
									{
										$is_err = TRUE;
										log::write('product_import_data_error', print_r($product, true), __FILE__, __LINE__);
									} else {
										$option_id = $good['argumrs_struct']['items'][$attribute['id']];
										$option_name .= $attribute['options'][$option_id]['name'];
									}
								}
								$g_row[] = $option_name;
								if ($is_err == TRUE)
								{
									break;
								}
								
								$g_row[] = '';
								$g_row[] = '';
								
								$g_row[] = $good['cost'];
								$g_row[] = $good['store'];
								$g_row[] = $good['weight'];
								
								$g_row[] = '';
								$g_row[] = '';
								$g_row[] = '';
								
								foreach ($this->features as $feature)
								{
									$g_row[] = '';
								}
								
								foreach ($this->arguments as $argument_group) {
									foreach ($argument_group['items'] as $argument)
									{
										$g_row[] = '';
									}
								}
								
								$csv_array[] = $g_row;
							}
						}
					//}
				}
			}
		}
		
		return $csv_array;
	}
	
	public function get_titlebar($classify_id)
	{
		try
		{			
			if ($classify_id == 0)
			{
				$classify_name = ClassifyService::DEFAULT_CLASSIFY_NAME;
				$attributes    = array();
				$features      = array();
			} else {
				$classify = ClassifyService::get_instance()->get($classify_id);
				$classify_name = $classify['name'];
                
				$attributes    = ClassifyService::get_instance()->get_attribute_options_by_classify_id($classify_id, AttributeService::ATTRIBUTE_SPEC);
				$features      = ClassifyService::get_instance()->get_attribute_options_by_classify_id($classify_id, AttributeService::ATTRIBUTE_FEATURE);
			}
            
			$titlebar = array();
			$titlebar[] = '类型:'.$classify_name;
			$titlebar[] = '字段:分类';
            $titlebar[] = '字段:商品SKU';
            $titlebar[] = '字段:货品SKU';
            $titlebar[] = '字段:管理名称';
            $titlebar[] = '字段:商品名称';
            $titlebar[] = '字段:所属品牌';
            $titlebar[] = '字段:上下架';
            $titlebar[] = '字段:价格';
            $titlebar[] = '字段:市场价格';
            $titlebar[] = '字段:商品图片';
            
            if ($attributes)
            {
            	$titlebar[] = '规格:';
            } else {
            	$attribute_names = '';
            	foreach ($attributes as $attribute)
            	{
            		if (!empty($attribute_names))
            		{
            			$attribute_names .= '|';
            		}
            		$attribute_names .= $attribute['name'];
            	}
            	$titlebar[] = '规格:'.$attribute_names;
            }
            
            $titlebar[] = '字段:简介';
            $titlebar[] = '字段:详细描述';
            $titlebar[] = '字段:成本价格';
            $titlebar[] = '字段:库存';
            $titlebar[] = '字段:重量';
            $titlebar[] = '字段:META标题';
            $titlebar[] = '字段:META关键字';
            $titlebar[] = '字段:META描述';
            
            foreach ($features as $feature)
            {
            	$titlebar[] = '特性:'.$feature['name'];
            }
            
            if (isset($classify) AND !empty($classify['argument_relation_struct']))
            {
            	$classify['argument_relation_struct'] = json_decode($classify['argument_relation_struct'], TRUE);
            	foreach ($classify['argument_relation_struct'] as $argument_group)
            	{
            		$argument_name = '参数:'.$argument_group['name'];
            		foreach ((array)$argument_group['items'] as $argument)
            		{
            			$titlebar[] = $argument_name.'->'.$argument['name'];
            		}
            	}
            	$this->arguments = $classify['argument_relation_struct'];
            }
            
            $this->attributes = $attributes;
            $this->features   = $features;
            
            return $titlebar;
		} catch (MyRuntimeException $ex) {
			throw new MyRuntimeException(sprintf('创建标题栏失败：%s', $ex->getMessage()));
		}
	}
	
	protected function get_brand_name($brand_id)
	{
		if (!isset($this->brand_names[$brand_id]))
		{
			$brand = BrandService::get_instance()->get($brand_id);
			$this->brand_names[$brand_id] = $brand['name'];
		}
		return $this->brand_names[$brand_id];
	}
	
	protected function get_site_name($site_id)
	{
		if (!isset($this->site_names[$site_id]))
		{
			$site_name = Mysite::instance($site_id)->get('name');
			if ($site_name === NULL)
			{
				throw new MyRuntimeException(sprintf('未找到 ID 为 "%s" 的站点', $site_id));
			}
			$this->site_names[$site_id] = $site_name;
		}
		return $this->site_names[$site_id];
	}
	
	protected function get_category_names($category_id)
	{
		if (!isset($this->category_names[$category_id]))
		{
			$category_names = '';
			$category = CategoryService::get_instance()->get($category_id);
			$category_names = $category['title_manage'];
			while ($category['pid'] != 0)
			{
				$category = CategoryService::get_instance()->get($category['pid']);
				if (!empty($category_names))
				{
					$category_names = '->'.$category_names;
				}
				$category_names = $category['title_manage'].$category_names;
			}
			$this->category_names[$category_id] = $category_names;
		}
		return $this->category_names[$category_id];
	}
	
	public function format($products)
	{
		$result = array();
		
		if (!empty($products))
		{
			foreach ($products as $product)
			{
				$classify_id = $product['classify_id'];
				
				if (!isset($result))
				{
					$result = array();
				}
				if (!isset($result[$classify_id]))
				{
					$result[$classify_id] = array();
				}
				
				$result[$classify_id][] = $product;
			}
			
			ksort($result);
			foreach ($result as $k => $v)
			{
				ksort($result[$k]);
			}
		}
		
		return $result;
	}
}