<?php defined('SYSPATH') OR die('No direct access allowed.');

class Product_Core {
    /**
     * 获得商品前台链接
     *
     * @param 	int 	商品id
     * @param 	String 	商品链接
     */
    public static function permalink($product)
    {
    	static $routes    = array();
    	static $categorys = array();
        
    	if (!is_array($product))
    	{
    		$product = ProductService::get_instance()->get($product);
    	}
        
    	$routes = Myroute::instance()->get();
    	$domain = Mysite::instance()->get('domain');

    	$route_type     = $routes['type'];
    	$product_suffix = $routes['product_suffix'];
    	$product_route  = $routes['product'];
    	
    	if ($route_type == 2 OR $route_type == 3)
    	{
    		if (!isset($categorys[$product['category_id']]))
    		{
    			$categorys[$product['category_id']] = CategoryService::get_instance()->get($product['category_id']);
    		}
    	}
    	
    	$permalink = '';
        $product['uri_name'] = isset($product['uri_name'])?$product['uri_name']:$product['id'];
    	switch ($route_type)
    	{
    		case 0:
    			//$permalink = $product_route.'/'.$product['id'].$product_suffix;
    			$permalink = $product_route.$product['id'];
    			break;
    		case 1:
    			$permalink = $product_route.'/'.urlencode($product['uri_name']).$product_suffix;
    			break;
    		case 2:
    			$permalink = urlencode($categorys[$product['category_id']]['uri_name']).'/'.urlencode($product['uri_name']).$product_suffix;
    			break;
    		case 3:
    			$permalink = urlencode($categorys[$product['category_id']]['uri_name']).'/'.urlencode($product['uri_name']).$product_suffix;
    			break;
    		case 4:
    			$permalink = $product_route.'/'.urlencode($product['uri_name']).$product_suffix;
    			break;
    	}
        
    	return Kohana::config('config.front_protocol').$domain.'/'.$permalink;
    }
	
	/**
	 * 通过商品SKU创建货品SKU
	 * 
	 * @param string $product_sku
	 * @return string
	 */
	public static function create_good_sku($product_sku)
	{
		$good_service = ProductService::get_instance();
		
		do {
			$good_sku = strtoupper($product_sku.'-'.substr(uniqid(), -4));
			if (strlen($good_sku) > 32) {
				$good_sku = substr($good_sku, -32);
			}
		} while ($good_service->query_count(array('where'=>array('sku'=>$good_sku))) > 0);
		
		return $good_sku;
	}
	
	public static function create_good_title($product_title, $options)
	{
		static $attributes = array();
		
		$attribute_ids = array();
		foreach ($options as $attribute_id => $option_id) {
			if (!isset($attributes[$attribute_id])) {
				$attribute_ids[] = $attribute_id;
			}
		}
		if (!empty($attribute_ids)) {
			$query_struct = array('where' => array(
				'id' => $attribute_ids,
			));
			foreach (AttributeService::get_instance()->get_attribute_options($query_struct) as $attribute) {
				$attributes[$attribute['id']] = $attribute;
			}
		}
		
		$title = '';
		foreach ($options as $attribute_id => $option_id) {
			if (isset($attributes[$attribute_id]['options'][$option_id])) {
				if ($title != '') {
					$title .= ',';
				}
				$title .= $attributes[$attribute_id]['options'][$option_id]['name'];
			}
		}
		return $product_title.' - '.$title;
	}
	
	/**
	 * 清楚规格项重复的货品
	 * 
	 * @param array $goods
	 * @return array
	 */
	public static function clear_goods($goods)
	{
		$keys = array_keys($goods);
		for ($x = 0; isset($keys[$x]); $x++) {
			for ($y = $x + 1; isset($keys[$y]); $y++) {
				if (!isset($goods[$keys[$x]]))
				{
					continue;
				}
				
				if ($goods[$keys[$x]]['goods_attributeoption_relation_struct']['items'] == $goods[$keys[$y]]['goods_attributeoption_relation_struct']['items']) {
					if ($goods[$keys[$x]]['is_default'] == GoodService::GOOD_IS_DEFAULT) {
						$goods[$keys[$y]]['is_default'] = GoodService::GOOD_IS_DEFAULT;
					}
					unset($goods[$keys[$x]]);
				}
			}
		}
		return $goods;
	}
	
	public static function get_struct($request_data)
	{
		$qs_obj = QueryStruct::factory($request_data);
		$qs_obj->add_order(array(
			0  => array('id'          => 'DESC'),
			1  => array('id'          => 'ASC'),
			2  => array('sku'         => 'ASC'),
			3  => array('sku'         => 'DESC'),
			4  => array('id'          => 'ASC'),
			5  => array('id'          => 'DESC'),
			6  => array('category_id' => 'ASC'),
			7  => array('category_id' => 'DESC'),
			8  => array('weight'      => 'ASC'),
			9  => array('weight'      => 'DESC'),
			10 => array('title'       => 'ASC'),
			11 => array('title'       => 'DESC'),
			12 => array('brand_id'    => 'ASC'),
			13 => array('brand_id'    => 'DESC'),
			14 => array('on_sale'     => 'ASC'),
			15 => array('on_sale'     => 'DESC'),
			16 => array('goods_price' => 'ASC'),
			17 => array('goods_price' => 'DESC'),
			18 => array('front_visable' => 'ASC'),
			19 => array('front_visable' => 'DESC'),
			20 => array('update_time' => 'DESC'),
			21 => array('update_time' => 'ASC'),
			22 => array('type' => 'ASC'),
			23 => array('type' => 'DESC'),
		));
        
        $status = ProductService::PRODUCT_STATUS_PUBLISH;
        if(isset($request_data['status']))
        {
            switch($request_data['status'])
            {
                case ProductService::PRODUCT_STATUS_DELETE:
                    $status = ProductService::PRODUCT_STATUS_DELETE;
                    break;
                case ProductService::PRODUCT_STATUS_DRAFT:
                    $status = ProductService::PRODUCT_STATUS_DRAFT;
                    break;
                case ProductService::PRODUCT_STATUS_PUBLISH:
                    $status = ProductService::PRODUCT_STATUS_PUBLISH;
                    break;
                default:
                    throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
                    break;                  
            }
        }
		$qs_obj->set_default_order(0);
		$qs_obj->set('status', $status);
		$qs_obj->func('on_sale',     'product_get_struct_on_sale');
		$qs_obj->func('brand_id',    'product_get_struct_brand_id');
		$qs_obj->func('category_id', 'product_get_struct_category_id');
		
		if (!$qs_obj->has_error())
		{
			$struct = array(
				'query'   => $qs_obj->get_qstruct(),
				'request' => $qs_obj->get_rstruct(),
			);
			
			if (isset($struct['query']['where']['category_id']))
			{
				$category_ids = $struct['query']['where']['category_id'];
				if (is_array($category_ids))
				{
					unset($struct['query']['where']['category_id']);
					$sqlp = '(category_id IN('.implode(',', $category_ids).')';
					if (isset($category_ids[0]))
					{
						$sqlp .= " OR category_ids LIKE '%{$category_ids[0]}%'";
					}
					$sqlp .= ')';
					$struct['query']['where'][] = $sqlp;
				}
			}
			
			//模糊搜索
			$query_struct_current = & $struct['query'];
			
			$query_struct_current['like'] = array();
			
			if (!empty($query_struct_current['where']['name_manage']))
            {
            	$query_struct_current['like']['name_manage'] = $query_struct_current['where']['name_manage'];
            	unset($query_struct_current['where']['name_manage']);
            }
            
            if (!empty($query_struct_current['where']['sku']))
            {
            	$query_struct_current['like']['sku'] = $query_struct_current['where']['sku'];
            	unset($query_struct_current['where']['sku']);
            }
            
			if (!empty($query_struct_current['where']['title']))
            {
            	$query_struct_current['like']['title'] = $query_struct_current['where']['title'];
            	unset($query_struct_current['where']['title']);
            }
            
			return $struct;
		} else {
			throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
		}
	}
	
	/*
	public function add_product(& $return_data, $request_struct)
	{
		if (isset($request_struct['sku']))
		{
			$query_struct = array('where' => array(
				'site_id' => $request_struct['site_id'],
				'sku'     => $request_struct['sku'],
			));
			$site_id = $request_struct['site_id'];
			if (isset($request_struct['on_sale']) AND in_array($request_struct['on_sale'], array('0', '1')))
			{
				$query_struct['where']['on_sale'] = $request_struct['on_sale'];
			}
			
			$goods = GoodService::get_instance()->query_assoc($query_struct);
			if (!empty($goods))
			{
				$product_ids = array();
				foreach ((array)$return_data['assoc'] as $item)
				{
					$product_ids[$item['id']] = TRUE;
				}
				$add_ids = array();
				foreach ($goods as $item)
				{
					if (!isset($product_ids[$item['product_id']]))
					{
						$add_ids[] = $item['product_id'];
						$product_ids[$item['product_id']] = TRUE;
					}
				}
				if (!empty($add_ids))
				{
					$products = ProductService::get_instance()->query_assoc(array('where' => array(
						'id'      => $add_ids,
						'site_id' => $site_id,
					)));
					if (!empty($products))
					{
						foreach ($products as $item)
						{
							$return_data['assoc'][] = $item;
							$return_data['count']++;
						}
					}
				}
			}
		}
	}*/
}


function product_get_struct_category_id($cname, $rstruct)
{
	if (empty($cname))
	{
		return NULL;
	}
	/*
	$query_struct = array('where' => array(
		'site_id'      => $rstruct['site_id'],
		'title_manage' => $cname,
	));*/
	
	//模糊搜索
	$query_struct = array(
		'where' => array(
			
		),
		'like'  => array(
			'title_manage' => $cname
		)
	);

	$categorys = CategoryService::get_instance()->query_assoc($query_struct);
	if (empty($categorys))
	{
		return '0';
	} else {
		$category_ids = array();
		foreach ($categorys as $category)
		{
			$category_ids[$category['id']] = TRUE;
			if (!empty($category['sub_ids']))
			{
				foreach (explode(',', $category['sub_ids']) as $item)
				{
					$category_ids[trim($item)] = TRUE;
				}
			}
		}
		return array_keys($category_ids);
	}
}

function product_get_struct_brand_id($bname, $rstruct)
{
	if (empty($bname))
	{
		return NULL;
	}
	if ($bname == '无')
	{
		return '0';
	}
	/*
	$query_struct = array('where' => array(
		'site_id' => $rstruct['site_id'],
		'name'    => $bname,
	));*/
	//模糊搜索
	$query_struct = array(
		'where' => array(
			
		),
		'like'  => array(
			'name'    => $bname
		)
	);
	$brands = BrandService::get_instance()->query_assoc($query_struct);
	if (empty($brands))
	{
		return '-1';
	} else {
		$brand_ids = array();
		foreach ($brands as $brand)
		{
			$brand_ids[] = $brand['id'];
		}
		return $brand_ids;
	}
}

function product_get_struct_on_sale($on_sale)
{
	if ($on_sale != '0' AND $on_sale != 1)
	{
		return NULL;
	} else {
		return $on_sale;
	}
}