<?php defined('SYSPATH') OR die('No direct access allowed.');

class coding_Core {
	
	/**
	 * 编码 feature 记录
	 * 
	 * @param array $feature
	 * @return array
	 */
	public static function encode_feature($feature)
	{
		if (!empty($feature['meta_struct'])) {
			if (is_array($feature['meta_struct'])) {
				$feature['meta_struct'] = json_encode($feature['meta_struct']);
			}
		} elseif (isset($feature['meta_struct'])) {
			$feature['meta_struct'] = '';
		}
		
		return $feature;
	}
	
	/**
	 * 解码 feature 记录
	 * 
	 * @param array $feature
	 * @return array
	 */
	public static function decode_feature($feature)
	{
		if (!empty($feature['meta_struct'])) {
			if (is_string($feature['meta_struct'])) {
				$feature['meta_struct'] = json_decode($feature['meta_struct'], TRUE);
			}
		} elseif (isset($feature['meta_struct'])) {
			$feature['meta_struct'] = array();
		}
		
		return $feature;
	}
	
	/**
	 * 编码 featureoption 记录
	 * 
	 * @param array $featureoption
	 * @return array
	 */
	public static function encode_featureoption($featureoption)
	{
		if (!empty($featureoption['meta_struct'])) {
			if (is_array($featureoption['meta_struct'])) {
				$featureoption['meta_struct'] = json_encode($featureoption['meta_struct']);
			}
		} elseif (isset($featureoption['meta_struct'])) {
			$featureoption['meta_struct'] = '';
		}
		
		return $featureoption;
	}
	
	/**
	 * 解码 featureoption 记录
	 * 
	 * @param array $featureoption
	 * @return array
	 */
	public static function decode_featureoption($featureoption)
	{
		if (!empty($featureoption['meta_struct'])) {
			if (is_string($featureoption['meta_struct'])) {
				$featureoption['meta_struct'] = json_decode($featureoption['meta_struct'], TRUE);
			}
		} elseif (isset($featureoption['meta_struct'])) {
			$featureoption['meta_struct'] = array();
		}
		
		return $featureoption;
	}
	
	/**
	 * 编码 store 记录
	 * 
	 * @param array $store
	 * @return array
	 */
	public static function encode_store($store)
	{
		if (!empty($store['store_meta'])) {
			if (is_array($store['store_meta'])) {
				$store['store_meta'] = json_encode($store['store_meta']);
			}
		} elseif (isset($store['store_meta'])) {
			$store['store_meta'] = '';
		}
		
		return $store;
	}
	
	/**
	 * 解码 store 记录
	 * 
	 * @param array $store
	 * @return array
	 */
	public static function decode_store($store)
	{
		if (!empty($store['store_meta'])) {
			if (is_string($store['store_meta'])) {
				$store['store_meta'] = json_decode($store['store_meta'], TRUE);
			}
		} elseif (isset($store['store_meta'])) {
			$store['store_meta'] = array();
		}
		
		return $store;
	}
	
	/**
	 * 编码 product 记录
	 * 
	 * @param array $product
	 * @return array
	 */
	public static function encode_product($product)
	{
		if (!empty($product['category_ids'])) {
			if (is_array($product['category_ids'])) {
				$product['category_ids'] = ','.implode(',', $product['category_ids']).',';
			}
		} elseif (isset($product['category_ids'])) {
			$product['category_ids'] = '';
		}
		
		if (!empty($product['product_tag_ids'])) {
			if (is_array($product['product_tag_ids'])) {
				$product['product_tag_ids'] = implode(',', $product['product_tag_ids']);
			}
		} elseif (isset($product['product_tag_ids'])) {
			$product['product_tag_ids'] = '';
		}
		
		if (!empty($product['goods_attributeoption_relation_struct_default'])) {
			if (is_array($product['goods_attributeoption_relation_struct_default'])) {
				$product['goods_attributeoption_relation_struct_default'] = json_encode($product['goods_attributeoption_relation_struct_default']);
			}
		} elseif (isset($product['goods_attributeoption_relation_struct_default'])) {
			$product['goods_attributeoption_relation_struct_default'] = '';
		}
		
		if (!empty($product['product_featureoption_relation_struct'])) {
			if (is_array($product['product_featureoption_relation_struct'])) {
				$product['product_featureoption_relation_struct'] = json_encode($product['product_featureoption_relation_struct']);
			}
		} elseif (isset($product['product_featureoption_relation_struct'])) {
			$product['product_featureoption_relation_struct'] = '';
		}
		
		if (!empty($product['product_attribute_relation_struct'])) {
			if (is_array($product['product_attribute_relation_struct'])) {
				if (!empty($product['product_attribute_relation_struct']['items']) AND is_array($product['product_attribute_relation_struct']['items'])) {
					$product['product_attribute_relation_struct']['items'] = ':'.implode(':', $product['product_attribute_relation_struct']['items']).':';
				}
				$product['product_attribute_relation_struct'] = json_encode($product['product_attribute_relation_struct']);
			}
		} elseif (isset($product['product_attribute_relation_struct'])) {
			$product['product_attribute_relation_struct'] = '';
		}
		
		if (!empty($product['product_feature_relation_struct'])) {
			if (is_array($product['product_feature_relation_struct'])) {
				if (!empty($product['product_feature_relation_struct']['items']) AND is_array($product['product_feature_relation_struct']['items'])) {
					$product['product_feature_relation_struct']['items'] = ':'.implode(':', $product['product_feature_relation_struct']['items']).':';
				}
				$product['product_feature_relation_struct'] = json_encode($product['product_feature_relation_struct']);
			}
		} elseif (isset($product['product_feature_relation_struct'])) {
			$product['product_feature_relation_struct'] = '';
		}
		
		if (!empty($product['product_tag_entities'])) {
			if (is_array($product['product_tag_entities'])) {
				$product['product_tag_entities'] = json_encode($product['product_tag_entities']);
			}
		} elseif (isset($product['product_tag_entities'])) {
			$product['product_tag_entities'] = '';
		}
		
		return $product;
	}
	
	/**
	 * 解码  product 记录
	 * 
	 * @param array $product
	 * @return array
	 */
	public static function decode_product(& $product)
	{
		if (!empty($product['category_ids'])) {
			if (is_string($product['category_ids'])) {
				$product['category_ids'] = explode(',', $product['category_ids']);
			}
		} elseif (isset($product['category_ids'])) {
			$product['category_ids'] = array();
		}
		
		if (!empty($product['product_tag_ids'])) {
			if (is_string($product['product_tag_ids'])) {
				$product['product_tag_ids'] = explode(',', $product['product_tag_ids']);
			}
		} elseif (isset($product['product_tag_ids'])) {
			$product['product_tag_ids'] = array();
		}
		
		if (!empty($product['attribute_struct'])) {
			if (is_string($product['attribute_struct'])) {
				$product['attribute_struct'] = json_decode($product['attribute_struct'], TRUE);
			}
		} elseif (isset($product['attribute_struct'])) {
			$product['attribute_struct'] = array();
		}
		
		if (!empty($product['product_featureoption_relation_struct'])) {
			if (is_string($product['product_featureoption_relation_struct'])) {
				$product['product_featureoption_relation_struct'] = json_decode($product['product_featureoption_relation_struct'], TRUE);
			}
		} elseif (isset($product['product_featureoption_relation_struct'])) {
			$product['product_featureoption_relation_struct'] = array();
		}
		
		if (!empty($product['product_attribute_relation_struct'])) {
			if (is_string($product['product_attribute_relation_struct'])) {
				$product['product_attribute_relation_struct'] = json_decode($product['product_attribute_relation_struct'], TRUE);
				if (!empty($product['product_attribute_relation_struct']['items']) AND is_string($product['product_attribute_relation_struct']['items'])) {
					if ($product['product_attribute_relation_struct']['items']{0} === ':') {
						$product['product_attribute_relation_struct']['items'] = substr($product['product_attribute_relation_struct']['items'], 1);
					}
					if ($product['product_attribute_relation_struct']['items']{strlen($product['product_attribute_relation_struct']['items'])-1} === ':') {
						$product['product_attribute_relation_struct']['items'] = substr($product['product_attribute_relation_struct']['items'], 0, -1);
					}
					$product['product_attribute_relation_struct']['items'] = explode(':', $product['product_attribute_relation_struct']['items']);
				}
			}
		} elseif (isset($product['product_attribute_relation_struct'])) {
			$product['product_attribute_relation_struct'] = array();
		}
		
		if (!empty($product['product_feature_relation_struct'])) {
			if (is_string($product['product_feature_relation_struct'])) {
				$product['product_feature_relation_struct'] = json_decode($product['product_feature_relation_struct'], TRUE);
				if (!empty($product['product_feature_relation_struct']['items']) AND is_string($product['product_feature_relation_struct']['items'])) {
					if ($product['product_feature_relation_struct']['items']{0} === ':') {
						$product['product_feature_relation_struct']['items'] = substr($product['product_feature_relation_struct']['items'], 1);
					}
					if ($product['product_feature_relation_struct']['items']{strlen($product['product_feature_relation_struct']['items'])-1} === ':') {
						$product['product_feature_relation_struct']['items'] = substr($product['product_feature_relation_struct']['items'], 0, -1);
					}
					$product['product_feature_relation_struct']['items'] = explode(':', $product['product_feature_relation_struct']['items']);
				}
			}
		} elseif (isset($product['product_feature_relation_struct'])) {
			$product['product_feature_relation_struct'] = array();
		}
		
		if (!empty($product['product_tag_entities'])) {
			if (is_string($product['product_tag_entities'])) {
				$product['product_tag_entities'] = json_decode($product['product_tag_entities'], TRUE);
			}
		} elseif (isset($product['product_tag_entities'])) {
			$product['product_tag_entities'] = array();
		}
		
		return $product;
	}
	
	/**
	 * 编码 productpic
	 * 
	 * @param array $productpic
	 * @return array
	 */
	public static function encode_productpic($productpic)
	{
		if (!empty($productpic['meta_struct'])) {
			if (is_array($productpic['meta_struct'])) {
				$productpic['meta_struct'] = json_encode($productpic['meta_struct']);
			}
		} elseif (isset($productpic['meta_struct'])) {
			$productpic['meta_struct'] = '';
		}
		
		return $productpic;
	}
	
	/**
	 * 解码 productpic
	 * 
	 * @param array $productpic
	 * @return array
	 */
	public static function decode_productpic($productpic)
	{
		if (!empty($productpic['meta_struct'])) {
			if (is_string($productpic['meta_struct'])) {
				$productpic['meta_struct'] = json_decode($productpic['meta_struct'], TRUE);
			}
		} elseif (isset($productpic['meta_struct'])) {
			$productpic['meta_struct'] = array();
		}
		
		return $productpic;
	}
	
	/**
	 * 编码 good
	 * 
	 * @param array $good
	 * @return array
	 */
	public static function encode_good($good)
	{
		if (!empty($good['attribute_struct'])) {
			if (is_array($good['attribute_struct'])) {
				$good['attribute_struct'] = json_encode($good['attribute_struct']);
			}
		} elseif (isset($good['attribute_struct'])) {
			$good['attribute_struct'] = '';
		}
        
		if (!empty($good['attribute_struct_default'])) {
			if (is_array($good['attribute_struct_default'])) {
				$good['attribute_struct_default'] = json_encode($good['attribute_struct_default']);
			}
		} elseif (isset($good['attribute_struct_default'])) {
			$good['attribute_struct'] = '';
		}
		
		if (!empty($good['goods_productpic_relation_struct'])) {
			if (is_array($good['goods_productpic_relation_struct'])) {
				$good['goods_productpic_relation_struct'] = json_encode($good['goods_productpic_relation_struct']);
			}
		} elseif (isset($good['goods_productpic_relation_struct'])) {
			$good['goods_productpic_relation_struct'] = '';
		}
		
		return $good;
	}
	
	/**
	 * 解码 good
	 * 
	 * @param array $good
	 * @return array
	 */
	public static function decode_good($good)
	{
		if (!empty($good['attribute_struct'])) {
			if (is_string($good['attribute_struct'])) {
				$good['attribute_struct'] = json_decode($good['attribute_struct'], TRUE);
			}
		} elseif (isset($good['attribute_struct'])) {
			$good['attribute_struct'] = array();
		}
        
		if (!empty($good['attribute_struct_default'])) {
			if (is_string($good['attribute_struct_default'])) {
				$good['attribute_struct_default'] = json_decode($good['attribute_struct_default'], TRUE);
			}
		} elseif (isset($good['attribute_struct_default'])) {
			$good['attribute_struct_default'] = array();
		}
		
		if (!empty($good['goods_productpic_relation_struct'])) {
			if (is_string($good['goods_productpic_relation_struct'])) {
				$good['goods_productpic_relation_struct'] = json_decode($good['goods_productpic_relation_struct'], TRUE);
			}
		} elseif (isset($good['goods_productpic_relation_struct'])) {
			$good['goods_productpic_relation_struct'] = array();
		}
		
		return $good;
	}
	
    /**
     * 编码 attributeoption 记录
     * 
     * @param array $attributeoption
     * @return array
     */
    public static function encode_attributeoption($attributeoption)
    {
    	if (!empty($attributeoption['meta_struct'])) {
    		if (is_array($attributeoption['meta_struct'])) {
    			$attributeoption['meta_struct'] = json_encode($attributeoption['meta_struct']);
    		}
    	} elseif (isset($attributeoption['meta_struct'])) {
    		$attributeoption['meta_struct'] = '';
    	}
    	
    	return $attributeoption;
    }
    
    /**
     * 解码  attributeoption 记录
     * 
     * @param array $attributeoption
     * @return array
     */
    public static function decode_attributeoption($attributeoption)
    {
    	if (!empty($attributeoption['image'])) {
    	    $attributeoption['image'] = explode("|", $attributeoption['image']);
    	}
        
    	if (!empty($attributeoption['meta_struct'])) {
    		if (is_string($attributeoption['meta_struct'])) {
    			$attributeoption['meta_struct'] = json_decode($attributeoption['meta_struct'], TRUE);
    		}
    	} elseif (isset($attributeoption['meta_struct'])) {
    		$attributeoption['meta_struct'] = array();
    	}
    	
    	return $attributeoption;
    }
	
	/**
     * 编码 attribute
     * 
     * @param array $attribute
     * @return array
     */
    public static function encode_attribute($attribute)
    {
    	if (!empty($attribute['meta_struct'])) {
    		if (is_array($attribute['meta_struct'])) {
    			$attribute['meta_struct'] = json_encode($attribute['meta_struct']);
    		}
    	} elseif (isset($attribute['meta_struct'])) {
    		$attribute['meta_struct'] = '';
    	}
    	
    	return $attribute;
    }
    
    /**
     * 解码 attribute 记录
     * 
     * @param array $attribute
     * @return array
     */
    public static function decode_attribute($attribute, $k = 'meta_struct')
    {
    	if (!empty($attribute[$k])) {
    		if (is_string($attribute[$k])) {
    			$attribute[$k] = json_decode($attribute[$k], TRUE);
    		}
    	} elseif (isset($attribute[$k])) {
    		$attribute[$k] = array();
    	}
    	
    	return $attribute;
    }
	
    /**
     * 编码 attachment 记录
     * 
     * @param array $attachment
     * @return array
     */
    public static function encode_attachment($attachment)
    {
    	if (!empty($attachment['attach_meta'])) {
    		if (is_array($attachment['attach_meta'])) {
    			$attachment['attach_meta'] = json_encode($attachment['attach_meta']);
    		}
    	} elseif (isset($attachment['attach_meta'])) {
    		$attachment['attach_meta'] = '';
    	}
    	
    	if (!empty($attachment['ref_array'])) {
    		if (is_array($attachment['ref_array'])) {
    			$attachment['ref_array'] = json_encode($attachment['ref_array']);
    		}
    	} elseif (isset($attachment['ref_array'])) {
    		$attachment['ref_array'] = '';
    	}
    	
    	return $attachment;
    }
    
	/**
     * 解码 attachment 记录
     * 
     * @param array $attachment
     * @return array
     */
	public static function decode_attachment($attachment)
    {
    	if (!empty($attachment['attach_meta'])) {
    		if (is_string($attachment['attach_meta'])) {
    			$attachment['attach_meta'] = json_decode($attachment['attach_meta'], TRUE);
    		}
    	} elseif (isset($attachment['attach_meta'])) {
    		$attachment['attach_meta'] = array();
    	}
    	
    	if (!empty($attachment['ref_array'])) {
    		if (is_string($attachment['ref_array'])) {
    			$attachment['ref_array'] = json_decode($attachment['ref_array'], TRUE);
    		}
    	} elseif (isset($attachment['ref_array'])) {
    		$attachment['ref_array'] = array();
    	}
    	
    	return $attachment;
    }
}