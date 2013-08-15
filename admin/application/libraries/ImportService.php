<?php defined('SYSPATH') OR die('No direct access allowed.');

class ImportService_Core {
	
	protected $columns = array(
		'分类'       => 'category',
		'管理名称'   => 'name_manage',
		'商品名称'   => 'name',
		'商品SKU'    => 'sku',
		'货品SKU'    => 'good_sku',
		'所属品牌'   => 'brand',
		'上下架'     => 'on_sale',
		'价格'       => 'price',
		'市场价格'   => 'market_price',
		'成本价格'   => 'cost',
		'商品图片'   => 'pictures',
		'简介'       => 'brief',
		'详细描述'   => 'description',
		'库存'       => 'store',
		'重量'       => 'weight',
		'META标题'   => 'meta_title',
		'META关键字' => 'meta_keywords',
		'META描述'   => 'meta_description',
	);
	
	protected $titlebar = array();
	
	protected $picdir      = NULL;
	protected $site_name   = NULL;
	protected $classify_id = NULL;
	protected $classify_name = NULL;
	protected $attributes  = array();
	protected $features    = array();
	protected $arguments   = array();
	
	protected $lc          = 0;
	protected $errors      = array();
	
	protected $attribute_ids        = array();
	protected $attribute_option_ids = array();
	protected $feature_ids          = array();
	protected $feature_option_ids   = array();
	protected $category_names       = array();
	protected $classify_names       = array();
	protected $brand_names          = array();
    
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
	
	public function run($csv, $dir = NULL)
	{
		if (!is_null($dir))
		{
			$this->picdir = $dir;
		}
		
		$products  = array();
		$good_ids  = array();
		$uri_names = array();
        $csvArr = csv::decode($csv);
		foreach ($csvArr as $lc => $l)
		{
			$this->lc = $lc;
			
			if ($this->is_empty($l))
				continue;
			
			if ($lc === 0 OR trim($l[0]) !== '' OR trim($l[1]) !== '')  // 解析表头
			{
				$this->parse_titlebar($l);
			} else {
				if ($this->is_product($l))
				{
					$product = $this->parse_product($l);
					$index   = count($products);
					$products[$index] = $product;
					
					$attributes = $this->parse_attributes($l, $product);
				} else {
					if (!isset($product))
					{
						$this->set_error(new MyRuntimeException('该货品未属于任何商品'));
					} else {
						$this->parse_good($l, $products[$index], $attributes);
					}
				}
			}
		}
        
		return array(
			'products' => $products,
			'errors'   => $this->errors,
		);
	}
	
	public function parse_csv($csv, $dir = NULL){
		if (!is_null($dir))
		{
			$this->picdir = $dir;
		}
		
		$products  = array();
        $csvArr = csv::decode($csv);
		foreach ($csvArr as $lc => $l){
			if($this->is_empty($l))continue;
            
			//表头
			if($lc===0)continue;
			$product = $this->set_product($l);
		}
        
		return array(
			'errors'   => $this->errors,
		);
	}    
	protected function set_product($l){
        $product_id = 0;
        $str_bad = array(" ", '（', '）', '(', ')');
        $sku   = str_replace($str_bad, '', $l['2']);
        $title = trim($l['5']);
		$datas = array(
			'status'             => 0,
			'front_visible'      => 1,
			'classify_id'        => (int)$l['0'],
			'category_id'        => (int)$l['1'],
			'sku'                => $sku,
			'name_manage'        => $l['4'],
			'title'              => $title,
			'brand_id'           => (int)$l['6'],
			'on_sale'            => (int)$l['7'],
			'price'              => $l['8'],
			'market_price'       => $l['9'],
			'cost'               => $l['14'],
			'brief'              => $l['12'],
			'store'              => (int)$l['15'],
			'weight'             => (int)$l['16'],
			'meta_title'         => $l['17'],
			'meta_keywords'      => $l['18'],
			'meta_description'   => $l['19'],
            'create_time'        => time(),
			'update_timestamp'   => time(),
		);		
        
        //save data
		$query_struct = array(
        	'orderby' => array(
        		'id' => 'ASC',
        	),
        	'limit' => array(
        		'page'     => 1,
        		'per_page' => 1,
        	),
        );
        if(!empty($sku)){  
        	$query_struct['where'] = array('sku' => $sku);
        }else{        
        	$query_struct['where'] = array('title' => $title);
        }
        $product = ProductService::get_instance()->query_assoc($query_struct);
        if (!empty($product)) {
        	$product_id = $product[0]['id'];
            $datas['id'] = $product_id;
        }     
        if($product_id>0){
            ProductService::get_instance()->update($datas);
        }else{
            $product_id = ProductService::get_instance()->create($datas);
        }
        
        //save pic
        $pictures = str_replace('，', ',', $l['10']);
        $pictures = trim($pictures, ',');
        if($product_id>0 && !empty($pictures)){    
            $i = 0;
            $pictures = explode(',', $pictures);
		    foreach($pictures as $pic_name){      
                $str_bad = array(" ", ",", "\t", "\r", "\n", "\r\n");
                $pic_name = str_replace($str_bad, '', $pic_name);
                if(empty($pic_name))continue;
                $img_file = ATTPATH.'upimg/'.iconv('UTF-8', 'GBK//IGNORE', $pic_name);
                if(!file_exists($img_file)){
                    $this->set_error(new MyRuntimeException("图片不存在：".$pic_name));
                    continue;
                }
                $str_bad = array('（', '）', '(', ')');
                $picname = str_replace($str_bad, '', $pic_name);
                $default_img_id = strtolower(substr($picname, 0, strrpos($picname, '.')));
                if(file_exists(ATTPATH.'product/'.$default_img_id.'/default.jpg')){
                    $img_id = $default_img_id;
                }else{
                    $att = AttService::get_instance("product");
                    $img_id = $att->save_default_img($img_file, $default_img_id);
                }
                if(empty($img_id)){
                    $this->set_error(new MyRuntimeException(Kohana::lang('o_product.phprpc_pic_save_failed').$pic_name));
                    continue;
                }                
        		$query_struct = array(
                	'where' => array(
                		'product_id' => $product_id,
                		'image_id' => $img_id,
                	),
                	'orderby' => array(
                		'id' => 'ASC',
                	),
                	'limit' => array(
                		'page'     => 1,
                		'per_page' => 1,
                	),
                );
                $pic_data = ProductpicService::get_instance()->query_assoc($query_struct);
                if(empty($pic_data)){
                    $productpic_data = array(
                        'product_id' => $product_id,
                        'is_default' => ProductpicService::PRODUCTPIC_IS_DEFAULT_FALSE,
                        'image_id' => $img_id
                    );
                    $productpic_id = ProductpicService::get_instance()->add($productpic_data);
    		    	if($i == 0 && $productpic_id>0){
    		    		ProductpicService::get_instance()->set_default_pic_by_productpic_id($productpic_id, $product_id);
    		    	}
                }
		    	$i++;
		    }
        }
	}
    
	protected function parse_product($l)
	{
		static $uri_names = array();
        
		$product = array(
			'status'           => 1,
			'name_manage'      => $this->parse_name_manage($l),
			'title'            => $this->parse_name($l),
			'brand_id'         => $this->parse_brand($l),
			'category_id'      => $this->parse_category($l),
			'classify_id'      => $this->classify_id,
			'on_sale'          => $this->parse_on_sale($l),
			'goods_price'        => $this->parse_price($l),
			'goods_market_price' => $this->parse_market_price($l),
			'goods_cost'         => $this->parse_cost($l),
			'brief'            => $this->parse_brief($l),
			'store'            => $this->parse_store($l),
			'weight'           => $this->parse_weight($l),
			'meta_title'       => $this->parse_meta_title($l),
			'meta_keywords'    => $this->parse_meta_keywords($l),
			'meta_description' => $this->parse_meta_description($l),
			'update_timestamp' => time(),
		);
		
		$product['sku'] = $this->parse_sku($l, $product);
		
		if (!isset($product['id']))
		{
			$product['create_timestamp'] = $product['update_timestamp'];
			$product['uri_name'] = product::create_uri_name($product['title'], $product['site_id'], 0, $uri_names, $product['sku']);
			$uri_names[] = $product['uri_name'];
		}
		
		$product['pictures']    = $this->parse_pictures($l, $product);
		$product['description'] = $this->parse_description($l, $product);
		
		$product['product_featureoption_relation_struct'] = array(
			'items' => $this->parse_features($l),
		);
		$product['product_feature_relation_struct'] = array(
			'items' => array_keys($product['product_featureoption_relation_struct']['items']),
		);
		
		$product['arguments'] = $this->parse_arguments($l);
		
		return $product;
	}
	
	protected function parse_good($l, & $product, $attributes)
	{
		$good = array(
			'on_sale'      => $this->parse_on_sale($l),
			'price'        => $this->parse_price($l),
			'market_price' => $this->parse_market_price($l),
			'cost'         => $this->parse_cost($l),
			'weight'       => $this->parse_weight($l),
			'store'        => $this->parse_store($l),
			'update_timestamp' => time(),
		);
		
		$good['sku'] = $this->parse_good_sku($l, $good, $product);
		
		$attributeoptions = $this->parse_attributeoptions($l, $attributes);
		if (isset($product['goods']))
		{
			foreach ($product['goods'] as $item)
			{
				if (!empty($item['goods_attributeoption_relation_struct']['items']) AND $item['goods_attributeoption_relation_struct']['items'] == $attributeoptions)
				{
					$this->set_error(new MyRuntimeException('货品规格值设置重复'));
				}
			}
		}
		$good['goods_attributeoption_relation_struct'] = array(
			'items' => $attributeoptions,
		);
		
		$good['title']    = $this->parse_good_title($l, $good, $product);
		$good['pictures'] = $this->parse_goodpics($l, $good, $product);
		
		if (!isset($good['id']))
		{
			$good['create_timestamp'] = $good['update_timestamp'];
		}
		
		if (!isset($product['goods']))
		{
			$product['goods'] = array();
			
			$product['store']              = '-1';
			$product['goods_price']        = $good['price'];
			$product['goods_market_price'] = $good['market_price'];
			$product['goods_cost']         = $good['cost'];
			$product['weight']             = $good['weight'];
			
			$good['is_default'] = 1;
		} else {
			$good['is_default'] = 0;
		}
		
		if ($good['store'] >= '0')
		{
			$product['store'] == '-1' AND $product['store'] = '0';
			$product['store'] += $good['store'];
		}
		
		if (!empty($good['goods_attributeoption_relation_struct']['items']))
		{
			if (!isset($product['product_attribute_relation_struct']['items']))
			{
				$product['product_attribute_relation_struct'] = array(
					'items' => array(),
				);
			}
			
			if (!isset($product['goods_attributeoption_relation_struct_default']['items']))
			{
				$product['goods_attributeoption_relation_struct_default'] = array(
					'items' => array(),
				);
			}
			
			foreach ($good['goods_attributeoption_relation_struct']['items'] as $aid => $oid)
			{
				if (!in_array($aid, $product['product_attribute_relation_struct']['items']))
				{
					$product['product_attribute_relation_struct']['items'][] = $aid;
				}
				
				if (!isset($product['goods_attributeoption_relation_struct_default']['items'][$aid]))
				{
					$product['goods_attributeoption_relation_struct_default']['items'][$aid] = array();
				}
				
				if (!in_array($oid, $product['goods_attributeoption_relation_struct_default']['items'][$aid]))
				{
					$product['goods_attributeoption_relation_struct_default']['items'][$aid][] = $oid;
				}
			}
		}
		
		$product['goods'][] = $good;
	}
	
	protected function parse_titlebar($l)
	{
		$this->titlebar = array();
		
		foreach ($l as $i => $item)
		{
			$item = trim($item);
			$position = strpos($item, ':');
			if ($position !== FALSE)
			{
				$type  = trim(substr($item, 0, $position));
				$value = trim(substr($item, $position + 1));
				switch ($type)
				{
					case '类型':
							if (empty($value))
							{
								$this->set_error(new MyRuntimeException('商品类型名称不可为空'));
							}
							
							if ($value == ClassifyService::DEFAULT_CLASSIFY_NAME)
							{
								$this->classify_id   = 0;
								$this->classify_name = ClassifyService::DEFAULT_CLASSIFY_NAME;
								
								$this->attributes  = array();
								$this->features    = array();
								$this->arguments   = array();
							} else {
								$classify = ClassifyService::get_instance()->query_row(array('where'=>array(
									'name'    => $value,
								)));
                                
								if (empty($classify))
								{
									$this->set_error(new MyRuntimeException(sprintf('未找到名称为 "%s" 的商品类型', $value)));
								}
								
								$this->classify_id   = $classify['id'];
								$this->classify_name = $classify['name'];
								
								if (!empty($classify['argument_relation_struct']))
								{
									$this->arguments = json_decode($classify['argument_relation_struct'], TRUE);
								}
								
								try{
									$this->attributes = array();
									foreach ((array)ClassifyService::get_instance()->get_attribute_options_by_classify_id($classify['id'],1) as $item)
									{
										$this->attributes[] = $item;
									}
								} catch (MyRuntimeException $ex) {
									$this->set_error($ex);
								}
								
								try{
									$this->features = ClassifyService::get_instance()->get_attribute_options_by_classify_id($classify['id'],0);
								} catch (MyRuntimeException $ex) {
									$this->set_error($ex);
								}
							}
						break;
					case '字段':
						$value = strtoupper($value);
						if (isset($this->columns[$value]))
						{
							$this->titlebar[$this->columns[$value]] = $i;
						} else {
							$this->set_error(new MyRuntimeException(sprintf('未找到名称为 "%s" 的字段', $value)));
						}
						break;
					case '规格':
						$this->titlebar['attributes'] = $i;
						break;
					case '特性':
						if ($this->classify_id > 0)
						{
							if (!isset($this->titlebar['features']))
							{
								$this->titlebar['features'] = array();
							}
							
							$fsearch = FALSE;
							foreach ($this->features as $feature)
							{
								if (strtoupper($feature['name_manage']) === strtoupper($value))
								{
									$this->titlebar['features'][$feature['id']] = $i;
									$fsearch = TRUE;
									break;
								}
							}
							
							if ($fsearch === FALSE)
							{
								$this->set_error(new MyRuntimeException(sprintf('商品类型 "%s" 下未关联名称为 "%s" 的特性', $this->classify_name, $value)));
							}
						} else {
							$this->set_error(new MyRuntimeException(sprintf('"%s" 下不进行任何特性关联，却意外的找到了名称为 "%s" 的特性', ClassifyService::DEFAULT_CLASSIFY_NAME, $value)));
						}
						break;
					case '参数':
						if ($this->classify_id > 0)
						{
							if (!isset($this->titlebar['arguments']))
							{
								$this->titlebar['arguments'] = array();
							}
							if (strpos($value, '->'))
							{
								$value = explode('->', $value);
								$value[0] = trim($value[0]);
								$value[1] = trim($value[1]);
								$gsearch = FALSE;
								foreach ($this->arguments as $argument_group)
								{
									if ($argument_group['name'] === $value[0])
									{
										$gsearch = TRUE;
										break;
									}
								}
								if ($gsearch === TRUE)
								{
									$asearch = FALSE;
									foreach ($argument_group['items'] as $argument)
									{
										if ($argument['name'] === $value[1])
										{
											$this->titlebar['arguments'][$argument_group['name'].'->'.$argument['name']] = $i;
											$asearch = TRUE;
											break;
										}
									}
									if ($asearch === FALSE)
									{
										$this->set_error(new MyRuntimeException(sprintf('参数组 "%s" 下未找到名称为 "%s" 的参数', $argument_group['name_manage'], $value[1])));
									}
								} else {
									$this->set_error(new MyRuntimeException(sprintf('商品类型 "%s" 下未找到名称为 "%s" 的特性组', $this->classify_name, $value[0])));
								}
							} else {
								$this->set_error(new MyRuntimeException(sprintf('错误的参数关联格式："%s"，参数组名称与参数名称之间使用 "->" 符号分割', $value)));
							}
						} else {
							$this->set_error(new MyRuntimeException(sprintf('商品分类 "%s" 下不进行任何参数关联，却意外的找到了名称为 "%s" 的参数', ClassifyService::DEFAULT_CLASSIFY_NAME, $value)));
						}
						break;
				}
			} elseif ($item !== '') {
				$this->set_error(new MyRuntimeException(sprintf('标题解析失败："%s"', $item)));
			}
		}
	}
	
	protected function parse_cost($l)
	{
		$price = $this->get($l, 'cost');
		
		if ($price !== '')
		{
			if (is_numeric($price))
			{
				if ($price >= 0 AND $price <= 9999999.99)
				{
					return $price;
				} else {
					$this->set_error(new MyRuntimeException('成本价格必须大于 0 小于 9999999.99'));
				}
			} else {
				$this->set_error(new MyRuntimeException('成本价格必须为数字'));
			}
		}
		
		return '0';
	}
	
	protected function parse_store($l)
	{
		$store = $this->get($l, 'store');
		
		if ($store !== '')
		{
			if (preg_match('/^\-?\d+$/', $store) AND $store >= -1)
			{
				return $store;
			} else {
				$this->set_error(new MyRuntimeException('库存必须为大于 -1 的整数'));
			}
		}
		
		return '-1';
	}
	
	protected function parse_weight($l)
	{
		$weight = $this->get($l, 'weight');
		
		if ($weight !== '')
		{
			if (preg_match('/^\d+$/', $weight))
			{
				return $weight;
			} else {
				$this->set_error(new MyRuntimeException('重量必须为正整数'));
			}
		}
		
		return '0';
	}
	
	protected function parse_category($l)
	{
		static $names = array();
		static $last_category_id = 0;
		
		$categorys = $this->get($l, 'category');
		if ($categorys !== '')
		{
			$cs_upper = strtoupper($categorys);
			
			if (isset($names[0][$cs_upper]))
			{
				return $names[0][$cs_upper];
			}
			
			$cnames      = explode('->', $categorys);
			$parent_id   = 0;
			$parent_name = '根分类';
			
			foreach ($cnames as $item)
			{
				$category = CategoryService::get_instance()->query_row(array('where' => array(
					'pid'          => $parent_id,
					'title_manage' => trim($item),
				)));
				if (!empty($category))
				{
					$parent_id   = $category['id'];
					$parent_name = $category['title_manage'];
				} else {
					$this->set_error(new MyRuntimeException(sprintf('分类 "%s" 下未找到名称为 "%s" 的子分类', $parent_name, $item)));
					$is_error = TRUE;
					break;
				}
			}
			
			if (!isset($is_error) OR $is_error === FALSE)
			{
				$names[][$cs_upper] = $category['id'];
				$last_category_id = $category['id'];
				return $category['id'];
			}
		}
		
		if ($last_category_id == 0)
		{
			$this->set_error(new MyRuntimeException('商品所属分类解析失败'));
		}
		
		return $last_category_id;
	}
	
	protected function parse_brief($l)
	{
		return $this->get($l, 'brief');
	}
	
	protected function parse_description($l, & $product)
	{
		$description = $this->get($l, 'description');
		
		if ($description !== '')
		{
			if (isset($product['id']))
			{
				$record = ProductdescsectionService::get_instance()->query_row(array(
					'where' => array(
						'product_id' => $product['id'],
					),
					'orderby' => array(
						'position' => 'ASC',
					),
				));
				if (!empty($record))
				{
					$record['content']  = $description;
					$record['position'] = '0';
					return $record;
				}
			}
		} else {
			$this->set_error(new MyRuntimeException('详细描述不可为空'));
		}
		
		return array(
			'position' => 0,
			'title'    => 'Product Detail',
			'content'  => $description,
		);
	}
	
	protected function parse_name($l)
	{
		$name = $this->get($l, 'name');
		
		if ($name === '')
		{
			$this->set_error(new MyRuntimeException('商品名称不可为空'));
		}
		
		return $name;
	}
	
	protected function parse_name_manage($l)
	{
		$name_manage = $this->get($l, 'name_manage');
		
		if ($name_manage === '')
		{
			$this->set_error(new MyRuntimeException('商品管理名称不可为空'));
		}
		
		return $name_manage;
	}
	
	protected function parse_on_sale($l)
	{
		$on_sale = $this->get($l, 'on_sale');
		if (strtoupper($on_sale) == 'N')
		{
			return '0';
		} else {
			return '1';
		}
	}
	
	protected function parse_price($l)
	{
		$price = $this->get($l, 'price');
		
		if ($price !== '')
		{
			if (is_numeric($price))
			{
				if ($price >= 0 AND $price <= 9999999.99)
				{
					return $price;
				} else {
					$this->set_error(new MyRuntimeException('价格必须大于 0 小于 9999999.99'));
				}
			} else {
				$this->set_error(new MyRuntimeException('价格必须为数字'));
			}
		}
		
		return '0';
	}
	
	protected function parse_market_price($l)
	{
		$price = $this->get($l, 'market_price');
		
		if ($price !== '')
		{
			if (is_numeric($price))
			{
				if ($price >= 0 AND $price <= 9999999.99)
				{
					return $price;
				} else {
					$this->set_error(new MyRuntimeException('市场价格必须大于 0 小于 9999999.99'));
				}
			} else {
				$this->set_error(new MyRuntimeException('市场价格必须为数字'));
			}
		}
		
		return '0';
	}
	
	protected function parse_sku($l, & $product)
	{
		static $skus = array();
		
		$sku = $this->get($l, 'sku');
		if ($sku !== '')
		{
			if (strlen($sku) <= 32)
			{
				$record = ProductService::get_instance()->query_row(array('where' => array(
					'sku'     => $sku,
				)));
				
				if (empty($record))
				{
					if (!isset($skus[$sku]))
					{
						$skus[$sku] = TRUE;
						return $sku;
					} else {
						$this->set_error(new MyRuntimeException('商品 SKU 不可重复'));
					}
				} else {
					$product['id'] = $record['id'];
					return $sku;
				}
			} else {
				$this->set_error(new MyRuntimeException('商品 SKU 长度不可超过 32 字节'));
			}
		} else {
			$this->set_error(new MyRuntimeException('商品 SKU 不可为空'));
		}
		
		return '';
	}
	
	protected function parse_good_sku($l, & $good, & $product)
	{
		static $good_skus = array();
		
		$good_sku = $this->get($l, 'good_sku');
		if ($good_sku === '')
		{
			if (!empty($product['sku']))
			{
				return product::create_good_sku($product['sku']);
			} else {
				throw new MyRuntimeException('所属商品 SKU 为空，无法依据商品 SKU 自动生成货品 SKU');
			}
		} else {
			if (strlen($good_sku) <= 32)
			{
				$record = GoodService::get_instance()->query_row(array('where' => array(
					'sku'     => $good_sku,
				)));
				if (!empty($record))
				{
					if (isset($product['id']) AND $record['product_id'] == $product['id'])
					{
						$good['id'] = $record['id'];
						return $good_sku;
					} else {
						$this->set_error(new MyRuntimeException('货品 SKU 不可重复'));
					}
				} else {
					if (!isset($good_skus[$good_sku]))
					{
						$good_skus[$good_sku] = TRUE;
						return $good_sku;
					} else {
						$this->set_error(new MyRuntimeException('货品 SKU 不可重复'));
					}
				}
			} else {
				$this->set_error(new MyRuntimeException('货品 SKU 不可超过 32 字节'));
			}
		}
		
		return '';
	}
	
	protected function parse_good_title($l, & $good, & $product)
	{
		$good_title = $this->get($l, 'name');
		if ($good_title === '')
		{
			return product::create_good_title($product['title'], $good['goods_attributeoption_relation_struct']['items']);
		} else {
			return $good_title;
		}
	}
	
	protected function parse_pictures($l, & $product)
	{
		if (empty($this->picdir))
		{
			return array();
		}
		
		$dir = $this->get($l, 'pictures');
		
		if ($dir === '')
		{
			$dir = $product['sku'];
		}
		
		if ($dir !== '')
		{
			$picdir = $this->picdir.'/'.$dir;
			if (is_dir($picdir))
			{
				$pictures = array();
				
				$attach_setup = Kohana::config('attach.productPicAttach');
				foreach ($attach_setup['allowTypes'] as $idx => $allow_type)
				{
					$attach_setup['allowTypes'][$idx] = strtolower($allow_type);
				}
				
				$no_error = TRUE;
				
				$handler = opendir($picdir);
				while ($picture = readdir($handler))
				{
					if ($picture !== '.' AND $picture !== '..')
					{
						if (strpos($picture, '.'))
						{
							$postfix = strtolower(substr($picture, strrpos($picture, '.') + 1));
							if (in_array($postfix, $attach_setup['allowTypes']))
							{
								$filename = $picdir.'/'.$picture;
								if ($attach_setup['fileSizePreLimit'] > 0 AND filesize($filename) > $attach_setup['fileSizePreLimit'])
								{
									$this->set_error(new MyRuntimeException(sprintf('图片 "%s" 大小超出限制，最大允许上传的图片体积为 "%s M"', $picture, number_format($attach_setup['fileSizePreLimit']/1024/1024, 2))));
								} else {
									$pictures[$picture] = $filename;
								}
							} else {
								$this->set_error(new MyRuntimeException(sprintf('图片 "%s" 格式非法，只允许上传扩展名为 "%s" 的图片', $picture, implode(',', $attach_setup['allowTypes']))));
							}
						} else {
							$this->set_error(new MyRuntimeException(sprintf('图片 "%s" 扩展名获取失败', $picture)));
						}
					}
				}
				closedir($handler);
				
				return $pictures;
			}
		} else {
			$this->set_error(new MyRuntimeException('商品 SKU 解析错误，无法判断该商品是否具有图片'));
		}
		
		return array();
	}
	
	protected function parse_goodpics($l, & $good, & $product)
	{
		$goodpics = $this->get($l, 'pictures');
		
		if ($goodpics !== '')
		{
			if (empty($product['pictures']))
			{
				$this->set_error(new MyRuntimeException('商品图片解析错误，无法建立货品与图片的关联关系'));
			} else {
				$return_array = array();
				foreach (explode('#', $goodpics) as $item)
				{
					$goodpic = trim($item);
					if (isset($product['pictures'][$goodpic]))
					{
						$return_array[] = $goodpic;
					} else {
						$this->set_error(new MyRuntimeException(sprintf('货品关联图片 "%s" 未找到', $item)));
					}
				}
				return $return_array;
			}
		} else {
			return array();
		}
	}
	
	protected function parse_brand($l)
	{
		static $names = array();
		
		$name = $this->get($l, 'brand');
		if ($name !== '')
		{
			$upper = strtoupper($name);
			
			if (isset($names[0][$upper]))
			{
				return $names[0][$upper];
			}
			
			$brand = BrandService::get_instance()->query_row(array('where' => array(
				'name'    => $name,
			)));
			if (!empty($brand))
			{
				$names[][$upper] = $brand['id'];
				return $brand['id'];
			} else {
				$this->set_error(new MyRuntimeException(sprintf('未找到名称为 "%s" 的品牌', $name)));
			}
		}
		
		return '0';
	}
	
	protected function parse_meta_title($l)
	{
		return $this->get($l, 'meta_title');
	}
	
	protected function parse_meta_keywords($l)
	{
		return $this->get($l, 'meta_keywords');
	}
	
	protected function parse_meta_description($l)
	{
		return $this->get($l, 'meta_description');
	}
	
	protected function parse_attributes($l, & $product)
	{
		$attributes = $this->get($l, 'attributes');
		
		if ($attributes !== '')
		{
			$return_assoc = array();
			
			$sign = '规格:';
			
			if ($sign === substr($attributes, 0, strlen($sign)))
			{
				$attributes = explode('|', str_replace('/\|+/', '|', substr($attributes, strlen($sign))));
				foreach ($attributes as $index => $item)
				{
					$attributes[$index] = trim($item);
				}
				
				$records = AttributeService::get_instance()->get_attribute_options(array('where'=>array(
					'name_manage' => $attributes,
				)));
				
				foreach ($attributes as $item)
				{
					$upper  = strtoupper($item);
					$search = FALSE;
					foreach ($records as $record)
					{
						if (strtoupper($record['name_manage']) === $upper)
						{
							$return_assoc[] = $record;
							$search = TRUE;
							break;
						}
					}
					if ($search === FALSE)
					{
						$this->set_error(new MyRuntimeException(sprintf('站点 "%s" 下未找到名称为 "%s" 的规格', $this->site_name, $item)));
					}
				}
			} else {
				$num = 1;
				$attributes = explode('|', $attributes);
				$relations  = array();
				foreach ($attributes as $index => $item)
				{
					$item = explode('/', trim($item));
					if (empty($item))
					{
						$this->set_error(new MyRuntimeException(sprintf('规格 "%s" 的值不可为空', $this->attributes[$index]['name_manage'])));
					} else {
						if (isset($this->attributes[$index]))
						{
							foreach ($item as $option_name)
							{
								$uppercase = strtoupper(trim($option_name));
								foreach ($this->attributes[$index]['options'] as $option)
								{
									if ($uppercase === strtoupper($option['name_manage']))
									{
										if (!isset($relations[$this->attributes[$index]['id']]))
										{
											$relations[$this->attributes[$index]['id']] = array();
										}
										$relations[$this->attributes[$index]['id']][] = $option['id'];
									}
								}
							}
							
							if (isset($relations[$this->attributes[$index]['id']]) AND count($relations[$this->attributes[$index]['id']] === count($item)))
							{
								$num *= count($item);
							} else {
								$this->set_error(new MyRuntimeException(sprintf('规格 "%s" 的值填写错误："%s"', $this->attributes[$index]['name_manage'], $item)));
							}
						}
					}
				}
				
				if (!empty($relations))
				{
					if (!function_exists('array_assembly'))
					{
						function array_assembly($arrays)
						{
							$result = array();
							$array  = array_shift($arrays);
							if (empty($arrays)) {
								foreach ($array as $key => $val) {
									$array[$key] = array($val);
								}
								return $array;
							} else {
								foreach ($array as $val) {
									foreach (array_assembly($arrays) as $item) {
										array_unshift($item, $val);
										$result[] = $item;
									}
								}
								return $result;
							}
						}
					}
					
					$struct = array();
					$index  = 0;
					foreach ($relations as $aid => $oids)
					{
						if (!isset($struct[$index]))
						{
							$struct[$index] = array();
						}
						foreach ($oids as $oid)
						{
							$struct[$index][] = array($aid, $oid);
						}
						$index ++;
					}
					$struct = array_assembly($struct);
					foreach ($struct as $item)
					{
						$good = array(
							'sku'          => product::create_good_sku($product['sku']),
							'store'        => '-1',
							'on_sale'      => $product['on_sale'],
							'price'        => $product['goods_price'],
							'market_price' => $product['goods_market_price'],
							'cost'         => $product['goods_cost'],
							'weight'       => $product['weight'],
							'update_timestamp' => time(),
							'create_timestamp' => time(),
						);
						
						if (!isset($product['goods']))
						{
							$product['store']   = '-1';
							$product['goods']   = array();
							
							$good['is_default'] = '1';
						} else {
							$good['is_default'] = '0';
						}
						
						$good['goods_attributeoption_relation_struct'] = array(
							'items' => array(),
						);
						foreach ($item as $aoid)
						{
							$good['goods_attributeoption_relation_struct']['items'][$aoid[0]] = $aoid[1];
						}
						
						$good['title'] = product::create_good_title($product['title'], $good['goods_attributeoption_relation_struct']['items']);
						
						$product['goods'][] = $good;
					}
				}
			}
			
			return $return_assoc;
		} else {
			return array();
		}
	}
	
	protected function parse_attributeoptions($l, $attributes)
	{
		if (empty($attributes))
		{
			$attributes = $this->attributes;
		}
		
		$options = $this->get($l, 'attributes');
		if ($options !== '')
		{
			$return_assoc = array();
			
			$options = explode('|', str_replace('/\|+/', '|', $options));
			
			$index = 0;
			foreach ($attributes as $attribute)
			{
				if (isset($options[$index]))
				{
					$upper  = strtoupper(trim($options[$index]));
					$search = FALSE;
					foreach ($attribute['options'] as $option)
					{
						if (strtoupper($option['name_manage']) === $upper)
						{
							$return_assoc[$attribute['id']] = (string)$option['id'];
							$search = TRUE;
							break;
						}
					}
					if ($search === FALSE)
					{
						$this->set_error(new MyRuntimeException(sprintf('规格 "%s" 下不包含名称为 "%s" 的规格值', $attribute['name_manage'], $options[$index])));
					}
				} else {
					$this->set_error(new MyRuntimeException(sprintf('未找到规格 "%s" 的规格值', $attribute['name_manage'])));
				}
				$index ++;
			}
			
			return $return_assoc;
		} else {
			$this->set_error(new MyRuntimeException('规格值不可为空'));
		}
		
		return array();
	}
	
	protected function parse_features($l)
	{
		if (empty($this->features))
		{
			return array();
		} else {
			$return_assoc = array();
			foreach ($this->get($l, 'features') as $feature_id => $option_name)
			{
				if ($option_name !== '')
				{
					$upper_name = strtoupper($option_name);
					foreach ($this->features[$feature_id]['options'] as $option)
					{
						if ($upper_name === strtoupper($option['name_manage']))
						{
							$return_assoc[$feature_id] = $option['id'];
						}
					}
					if (!isset($return_assoc[$feature_id]))
					{
						$this->set_error(new MyRuntimeException(sprintf('特性 "%s" 下未找到名称为 "%s" 的特性值', $this->features[$feature_id]['name_manage'], $option_name)));
					}
				}
			}
			return $return_assoc;
		}
	}
	
	protected function parse_arguments($l)
	{
		if (empty($this->arguments))
		{
			return array();
		} else {
			$return_assoc = array();
			foreach ($this->get($l, 'arguments') as $key => $value)
			{
				if ($value !== '')
				{
					$key = explode('->', $key);
					if (!isset($return_assoc[$key[0]]))
					{
						$return_assoc[$key[0]] = array();
					}
					$return_assoc[$key[0]][$key[1]] = $value;
				}
			}
			return $return_assoc;
		}
	}
	
	protected function get($l, $key)
	{
		if (isset($this->titlebar[$key]))
		{
			if (is_array($this->titlebar[$key]))
			{
				$return_array = array();
				foreach ($this->titlebar[$key] as $k => $v)
				{
					$return_array[$k] = isset($l[$v]) ? trim($l[$v]) : '';
				}
				return $return_array;
			} else {
				return isset($l[$this->titlebar[$key]]) ? trim($l[$this->titlebar[$key]]) : '';
			}
		} else {
			return FALSE;
		}
	}
	
	protected function is_product($l)
	{
		return $this->get($l, 'sku') !== '';
	}
	
	protected function is_empty($l)
	{
		foreach ($l as $item)
		{
			if (trim($item) !== '')
			{
				return FALSE;
			}
		}
		
		return TRUE;
	}
	
	protected function set_error($error)
	{
		if (!isset($this->errors[$this->lc]))
		{
			$this->errors[$this->lc] = array();
		}
		
		$this->errors[$this->lc][] = $error;
	}
}