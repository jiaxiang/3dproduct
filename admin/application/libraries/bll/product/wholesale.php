<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 商品批发BLL
 * 
 * @author 王浩
 */
class BLL_Product_Wholesale {
	
	/**
	 * 缓存键名
	 *
	 * @var string
	 */
	static protected $cache_key = 'product_wholesales.';
	
	/**
	 * 通过商品ID获取商品批发信息
	 *
	 * @param integer $product_id
	 * @return array
	 */
	static public function get($product_id)
	{
		$wholesales = array();
		
		$records = Product_wholesaleService::get_instance()->index(array(
			'where' => array(
				'product_id' => $product_id,
			),
			'order' => 'num_begin',
		));
		
		if (!empty($records))
		{
			foreach ($records as $record)
			{
				$wholesales['type'] = $record['type'];
				
				unset($record['product_id']);
				unset($record['type']);
				
				$wholesales['items'][] = $record;
			}
		}
		
		return $wholesales;
	}
	
	/**
	 * 设置商品批发
	 *
	 * @param array $product
	 * @param array $wholesales
	 * @return boolean
	 */
	static public function set(& $product)
	{
		self::delete($product['id']);
		$wholesales = isset($product['wholesales'])?$product['wholesales']:array();
		if ($product['is_wholesale'] > 0 AND isset($wholesales['items']))
    	{
			foreach ($wholesales['items'] as $index => $wholesale)
			{
				$wholesale['product_id']     = $product['id'];
				$wholesale['type']           = $wholesales['type'];
				$wholesales['items'][$index] = $wholesale;
			}
            
			while (TRUE)
			{
				$order = FALSE;
				foreach ($wholesales['items'] as $index => $wholesale)
				{
					$next = $index + 1;
					if (isset($wholesales['items'][$next]))
					{
						if ($wholesale['num_begin'] > $wholesales['items'][$next]['num_begin'])
						{
							$wholesales['items'][$index] = $wholesales['items'][$next];
							$wholesales['items'][$next]  = $wholesale;
							$order = TRUE;
						} elseif ($wholesale['num_begin'] == $wholesales['items'][$next]['num_begin']) {
							unset($wholesales['items'][$index]);
						}
					}
				}
				if ($order == FALSE)
				{
					break;
				}
			}
            
			foreach ($wholesales['items'] as $index => $wholesale)
			{
				$prev = $index - 1;
				if (isset($wholesales['items'][$prev]))
				{
					$wholesales['items'][$prev]['num_end'] = $wholesale['num_begin'] - 1;
				}
			}
			
			isset($wholesales['items'][0]['num_begin']) AND $product['lowest_wholesale_num'] = $wholesales['items'][0]['num_begin'];
			foreach ($wholesales['items'] as $wholesale)
			{
				Product_wholesaleService::get_instance()->add($wholesale);
			}
    	} else {
    		$product['is_wholesale']         = 0;
			$product['lowest_wholesale_num'] = 0;
    	}
        
        //更新商品数据
        $data = array(
            'id'                   => $product['id'],
            'is_wholesale'         => $product['is_wholesale'],
            'lowest_wholesale_num' => $product['lowest_wholesale_num'],
        	'update_time'          => time()
        );
        ProductService::get_instance()->update($data);
        
		//更新货品数据
        if(isset($product['pdt_goods']) && is_array($product['pdt_goods']))
        {
    		foreach ($product['pdt_goods'] as $good)
    		{
    			if (isset($good['id']))
    			{
                    $data['id'] = $good['id'];
    				ProductService::get_instance()->update($data);
    			}
    		}  
        }  	
    	return TRUE;
	}
	
	/**
	 * 通过商品ID删除商品批发信息
	 *
	 * @param integer $product_id
	 * @return boolean
	 */
	static public function delete($product_id)
	{
		ORM::factory('product_wholesale')->where('product_id', $product_id)->delete_all();
		
		//Cache::remove(self::$cache_key.$product_id);
		
		return TRUE;
	}
}