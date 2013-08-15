<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 商品BLL
 */
class BLL_Product {
	
	/**
	 * 缓存键名
	 *
	 * @var string
	 */
	static protected $cache_key = 'product.';
	
	/**
	 * 获取商品列表
	 *
	 * @param array $query_struct
	 * @return array
	 */
	static public function index($query_struct)
	{
		$products = ProductService::get_instance()->index($query_struct);
		
		if (isset($query_struct['where']['sku']))
		{
			$add_struct = array(
				'where' => array(
                ),
			);
            
			$add_struct['where']['sku']     = $query_struct['where']['sku'];
			
			if (isset($query_struct['where']['on_sale']))
			{
				$add_struct['where']['on_sale'] = $query_struct['where']['on_sale'];
			}
			
			if (!empty($add_struct['where']))
			{
				$goods = ProductService::get_instance()->index($add_struct);
				if (!empty($goods))
				{
					$add_ids = array();
					$pdt_ids = array();
					
					foreach ($products as $product)
					{
						$pdt_ids[$product['id']] = TRUE;
					}
					
					foreach ($goods as $good)
					{
						if (!isset($pdt_ids[$good['product_id']]))
						{
							$add_ids[] = $good['product_id'];
							$pdt_ids[$good['product_id']] = TRUE;
						}
					}
					
					if (!empty($add_ids))
					{
						$add_pdts = ProductService::get_instance()->index(array('where' => array(
							'id' => $add_ids,
						)));
						foreach ($add_pdts as $product)
						{
							$products[] = $product;
						}
					}
				}
			}
		}
		
		
		$category_ids = array();
		$classify_ids = array();
		$brand_ids = array();
		foreach ($products as $product)
		{
			$product['category_id'] > 0 AND $category_ids[$product['category_id']] = TRUE;
			$product['classify_id'] > 0 AND $classify_ids[$product['classify_id']] = TRUE;
			$product['brand_id'] > 0 AND $brand_ids[$product['brand_id']] = TRUE;
		}
		$category_ids = array_keys($category_ids);
		$classify_ids = array_keys($classify_ids);
		$brand_ids = array_keys($brand_ids);
		
		$categorys = array();
		if (!empty($category_ids))
		{
			foreach (CategoryService::get_instance()->index(array('where' => array('id' => $category_ids))) as $category)
			{
				$categorys[$category['id']] = $category;
			}
		}
		
		$classifys = array();
		if (!empty($classify_ids))
		{
			foreach (ClassifyService::get_instance()->index(array('where' => array('id' => $classify_ids))) as $classify)
			{
				$classifys[$classify['id']] = $classify;
			}
		}
		
		$brands = array();
		if (!empty($brand_ids))
		{
			foreach (BrandService::get_instance()->index(array('where' => array('id' => $brand_ids))) as $brand)
			{
				$brands[$brand['id']] = $brand;
			}
		}
		
		foreach ($products as $index => $product)
		{
			$product = coding::decode_product($product);
			
			isset($categorys[$product['category_id']]) AND $product['category'] = $categorys[$product['category_id']];
			isset($classifys[$product['classify_id']]) AND $product['classify'] = $classifys[$product['classify_id']];
			isset($brands[$product['brand_id']]) AND $product['brand'] = $brands[$product['brand_id']];
         	$product['name_manage'] || $product['name_manage']=$product['title'];
         	$product['category_name'] = isset($product['category']['title_manage'])?$product['category']['title_manage']:'';
			
			$products[$index] = $product;
		}
        
		return array(
			'assoc' => $products,
			'count' => ProductService::get_instance()->count($query_struct),
		);
	}
	
	/**
	 * 通过商品ID获取商品
	 *
	 * @param integer $product_id
	 * @return array
	 */
	static public function get($product_id, $page = 1, $per_page = 10)
	{
		$product = ProductService::get_instance()->get($product_id);
		$product = coding::decode_product($product);
        
		$product['descsections'] = BLL_Product_Detail::get($product_id);
		$product['fetuoptrs']    = BLL_Product_Feature::get_fetuoptrs($product_id);
		$product['pictures']     = BLL_Product_Picture::get($product_id);
		$product['relations']    = BLL_Product_Relation::get($product_id); 
        $product['wholesales']   = BLL_Product_Wholesale::get($product_id);
		//$product['point']      = BLL_Product_Point::get($product_id);

		switch($product['type'])
        {
            case ProductService::PRODUCT_TYPE_ASSEMBLY:
                BLL_Product_Type_Assembly::load($product);
                break;
            case ProductService::PRODUCT_TYPE_CONFIGURABLE:
                BLL_Product_Type_Configurable::load($product);
                break;
            case ProductService::PRODUCT_TYPE_GOODS:
            default:
                BLL_Product_Type_Simple::load($product);
                break;
        }
		return $product;
	}
	
	/**
	 * 编辑商品
	 *
	 * @param array $product
	 * @return boolean
	 */
	static public function set($product)
	{
		//isset($product['point'])        AND BLL_Product_Point::set($product, $product['point']);
		if (empty($product['uri_name']))
		{
		    $product['uri_name'] = self::crt_uri_name($product);
		}
        
		switch($product['type'])
        {
            case ProductService::PRODUCT_TYPE_ASSEMBLY:
                BLL_Product_Type_Assembly::save($product);
                break;
            case ProductService::PRODUCT_TYPE_CONFIGURABLE:
                BLL_Product_Type_Configurable::save($product);
                break;
            case ProductService::PRODUCT_TYPE_GOODS:
            default:
                BLL_Product_Type_Simple::save($product);
                break;
        }
 		BLL_Product_Feature::set_fetuoptrs($product);
		BLL_Product_Detail::set($product);
        BLL_Product_Relation::set($product);
        BLL_Product_Wholesale::set($product);
		BLL_Product_Search::set($product);
		return TRUE;
	}
	
	/**
     * zhu
	 * 通过商品ID修改商品状态
	 * @param integer $product_id
	 * @param integer $status
	 * @return boolean
	 */
	static public function modify_status($product_id, $status)
	{
        $pd = ProductService::get_instance()->get($product_id);
        if($pd['id']>0)
        {
        	 $product['id'] = $pd['id'];
        	 $product['status'] = $status;
             $product['update_time'] = time();
        	 ProductService::get_instance()->update($product);
             return true;
        }
        return false;
    }
    
	/**
	 * 通过商品ID删除商品
	 *
	 * @param integer $product_id
	 * @return boolean
	 */
	static public function delete($product_id)
	{
        $product = ProductService::get_instance()->get($product_id);
        if(!$product['id']>0)
        {
			throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
		}
        
		switch($product['type'])
        {
            case ProductService::PRODUCT_TYPE_ASSEMBLY:
            case ProductService::PRODUCT_TYPE_CONFIGURABLE:
                self::delete_goods($product_id);
                break;
            case ProductService::PRODUCT_TYPE_GOODS:
			    BLL_Product_Type_Simple::delete($product_id);
                break;
		}
		
		BLL_Product_Picture::delete($product_id);
		BLL_Product_Wholesale::delete($product_id);
		BLL_Product_Relation::delete($product_id);
		BLL_Product_Detail::delete($product_id);
		//BLL_Product_Point::delete($product_id);
		BLL_Product_Search::delete($product_id);
		
		//BLL_Product_Argument::rmv_arguments($product_id);
		
		ORM::factory('productcomment')->where('product_id', $product_id)->delete_all();
		ORM::factory('productinquiry')->where('product_id', $product_id)->delete_all();
		
		ProductService::get_instance()->remove($product_id);
        
		//Cache::remove(self::$cache_key.$product_id);
		//Cache::remove('product_inquiries.'.$product_id);
		//Cache::remove('product_comments.'.$product_id);
		
		return TRUE;
	}
	
	/**
	 * 检查SKU是否已存在
	 *
	 * @param string $sku
	 * @param integer $product_id
	 * @return boolean
	 */
	static public function sku_exists($sku, $product_id = 0)
	{
		$query_struct = array('where' => array(
			'sku'     => $sku,
		));
		
		if ($product_id > 0)
		{
			$query_struct['where']['id !='] = $product_id;
		}
		
		return ProductService::get_instance()->count($query_struct) > 0 ? TRUE : FALSE;
	}
	
	/**
	 * 检查 URI NAME 是否已存在
	 *
	 * @param integer $site_id
	 * @param string $uri_name
	 * @param integer $product_id
	 * @return boolean
	 */
	static public function uri_name_exists($uri_name, $product_id = 0)
	{
		$query_struct = array('where' => array(
			'uri_name' => $uri_name,
		));
		
		if ($product_id > 0)
		{
			$query_struct['where']['id !='] = $product_id;
		}
		
		return ProductService::get_instance()->count($query_struct) > 0 ? TRUE : FALSE;
	}
	
	/**
	 * 创建商品 URI NAME
	 *
	 * @param array $product
	 * @param array $eliminate
	 * @return string
	 */
	static public function crt_uri_name($product, $eliminate = array())
	{
		if (!is_array($eliminate))
		{
			$eliminate = array();
		} 
        else 
        {
			$eliminate = array_flip($eliminate);
		}
		
    	$uri_name = strtolower(tool::create_uri_name($product['sku']));
    	
    	if (!array_key_exists($uri_name, $eliminate) AND !self::uri_name_exists($uri_name, empty($product['id']) ? 0 : $product['id']))
    	{
    		return $uri_name;
    	} else {
    		if (!empty($product['sku']))
    		{
    			return $uri_name.'-'.strtolower(tool::create_uri_name($product['sku']));
    		} else {
    			return $uri_name.'-'.strtolower(tool::create_uri_name(uniqid()));
    		}
    	}
	}
	
	/**
	 * 设置商品上下架
	 *
	 * @param integer $product_id
	 * @param integer $on_sale
	 * @return boolean
	 */
	static public function set_on_sale($product_id, $on_sale)
	{
		$product = self::get($product_id);
		if(!$product['id']>0)return false;
		ProductService::get_instance()->set($product_id, array(
			'on_sale'          => $on_sale,
			'update_time' => time(),
		));
		
		if (isset($product['type']) && $product['type'] != ProductService::PRODUCT_TYPE_GOODS)
		{            
    		$goods = Product_assemblyService::get_instance()->index(array('where' => array(
    			'assembly_id' => $product_id,
    		)));
    		if (!empty($goods))
    		{
    			foreach ($goods as $good)
    			{
    				$data['on_sale'] = $on_sale;
                    $data['update_time'] = time();
    				ProductService::get_instance()->set($good['product_id'], $data);
    			}
    		}
		
		}
		
		return TRUE;
	}
    
	/**
	 * 设置商品前台可见
	 *
	 * @param integer $product_id
	 * @param integer $front_visible
	 * @return boolean
	 */
	static public function set_front_visible($product_id, $front_visible)
	{
		$product = self::get($product_id);
		if(!$product['id']>0)return false;
		ProductService::get_instance()->set($product_id, array(
			'front_visible'          => $front_visible,
			'update_time' => time(),
		));
		
		return TRUE;
	}
    
	/**
	 * 删除可配置商品类型相关数据
	 *
	 * @param int $product_id
	 * @return boolean
	 */
	static private function delete_goods($product_id)
	{
		/*$goods = Product_assemblyService::get_instance()->index(array('where'=> array('assembly_id'=>$product_id)));
		if(!empty($goods))
        {
			$goods_ids = array();
			for($i=0; $i<count($goods); $i++)
            {
                $good_id = $goods[$i]['product_id'];
                $qs = array('where' => array(
                            'product_id' => $good_id,
                            'assembly_id !=' => $product_id
                        )
                    );              
    			$bind = Product_assemblyService::get_instance()->index($qs);
    			if(!empty($bind))
                {
    				throw new MyRuntimeException('该商品的简单商品ID:'.$good_id.'被捆绑在其他商品，请清除绑定后再进行删除！', 403);
    			}
			}
		}*/
		ORM::factory('product_assembly')->where('assembly_id', $product_id)->delete_all();
		ORM::factory('product_attributeoption_relation')->where('product_id', $product_id)->delete_all();		
		return TRUE;
	}
	
}