<?php
defined('SYSPATH') or die('No direct access allowed.');

class Update_Controller extends Template_Controller
{
	public $site_id;

	public function __construct()
	{
        parent::__construct();
		$this->site_id = site::id();
		if($this->site_id<=0)
		{
			die(Kohana::lang('o_global.access_denied'));
		}
	}
	/**
	 * 更新商品中的默认规格商品价格
	 */
	public function update_product_default_attribute_price()
	{
		$site = Mysite::instance($this->site_id)->get();
		if($_POST)
		{
			$domain = $this->input->post('domain');
			if(empty($domain)||$domain!=$site['domain'])
			{
				remind::set(Kohana::lang('o_global.input_error'),'site/update/update_product_seo');
			}
			$query_struct = array(
				'where'=>array('site_id'=>$this->site_id),
				'like'=>array(),
				'orderby'=>array('id'=>'DESC'),
				'limit'=>array('per_page'=>20,'offset'=>0)
			);
			$product_count = Myproduct::instance()->count($query_struct);
			//1000个商品以下直接读取所有的商品
			if($product_count < 1000)
			{
				$query_struct['limit']['per_page'] = $product_count;
				$products = Myproduct::instance()->lists($query_struct);
				$product_skus = array();
				foreach($products as $key=>$value)
				{
					$query_struct = array(
						'where'=>array('site_id'=>$this->site_id,'product_id'=>$value['id'],'default_on'=>1),
						'like'=>array(),
						'orderby'=>array('id'=>'DESC'),
						'limit'=>array('per_page'=>20,'offset'=>0)
					);
					$default_product_attributes = Myproduct_attribute::instance()->lists($query_struct);
					$data = array();
					if(count($default_product_attributes) > 0)
					{
						$default_product_attribute = array_shift($default_product_attributes);
						
						if(isset($default_product_attribute['price']) && ($default_product_attribute['price']>0))
						{
							$data['default_attr_price'] = $value['price'] + $default_product_attribute['price'];
						}
						else
						{
							$data['default_attr_price'] = $value['price'];
						}
					}
					else
					{
						$data['default_attr_price'] = $value['price'];
					}
					if(!Myproduct::instance($value['id'])->edit($data))
					{
						$product_skus[] = $value['SKU'];
					}
				}
			}
			else
			{
				remind::set(Kohana::lang('o_site.product_request_check'),'site/update/update_product_name_url');
			}
			if(count($product_skus) > 0)
			{
				$product_sku_str = '';
				foreach($product_skus as $key=>$value)
				{
					$product_sku_str = $value . '|';
				}
				remind::set(Kohana::lang('o_site.sku_update_error').$product_sku_str,'site/update/update_product_name_url');
			}
			else
			{
				remind::set(Kohana::lang('o_site.sku_update_success'),'site/update/update_product_name_url','success');
			}
		}
		$this->template->content = new View("site/update_product_default_attribute_price");
		$this->template->content->site = $site;
	}
	
	/**
	 * 批量更新商品的name_url
	 */
	public function update_product_name_url()
	{
		$site = Mysite::instance($this->site_id)->get();
		if($_POST)
		{
			$domain = $this->input->post('domain');
			if(empty($domain)||$domain!=$site['domain'])
			{
				remind::set(Kohana::lang('o_global.input_error'),'site/update/update_product_name_url');
			}
            $query_struct = array(
                'where'=>array('site_id' => $this->site_id),
                'like'=>array(),
                'orderby'   => array(),
                'limit'     => array(
	                'per_page'  => 20,
	                'page'      => 1,
                ),
            );
			$product_count = ProductService::get_instance()->count($query_struct);
			//1000个商品以下直接读取所有的商品
			if($product_count < 1000)
			{
				$query_struct['limit']['per_page'] = $product_count;

				$products = ProductService::get_instance()->index($query_struct);
				foreach($products as $key=>$value)
				{
					$product_name = $value['title'];
					if(!empty($product_name))
					{
						//判断是否为中文的商品
						if(preg_match('/[\x7f-\xff]/',$product_name))
						{
							continue;
						}
						//过滤重复的NAME URL
						$product_name_url = product::create_uri_name($product_name,$value['site_id'],$value['id']);
						echo $product_name_url . "<br/>";
						$data = array();
						$data['uri_name'] = $product_name_url;
						$return_sturct = ProductService::get_instance()->set($value['id'],$data);
					}
				}
			}
			else
			{
				remind::set(Kohana::lang('o_site.product_request_check'),'site/update/update_product_name_url');
			}
			remind::set(Kohana::lang('o_site.sku_update_success'),'site/update/update_product_name_url','success');
		}

		$this->template->content = new View("site/update_product_name_url");
		$this->template->content->site = $site;
	}

	/**
	 * 批量更新分类的name_url
	 */
	public function update_category_name_url()
	{
		$site = Mysite::instance($this->site_id)->get();
		if($_POST)
		{
			$domain = $this->input->post('domain');
			if(empty($domain)||$domain!=$site['domain'])
			{
				remind::set(Kohana::lang('o_global.input_error'),'site/update/update_category_name_url');
			}
			$query_struct = array(
				'where'=>array('site_id'=>$this->site_id),
				'like'=>array(),
				'orderby'=>array('id'=>'DESC'),
				'limit'=>array('per_page'=>20,'offset'=>0)
			);
			$category_count = Mycategory::instance()->count($query_struct);
			$category_names = array();
			//1000个商品以下直接读取所有的商品
			if($category_count < 1000)
			{
				$query_struct['limit']['per_page'] = $category_count;

				$categorys = Mycategory::instance()->lists($query_struct);
				$category_name = array();
				foreach($categorys as $key=>$value)
				{
					$category_name = $value['name'];
					//判断是否为中文的商品
					if(preg_match('/[\x7f-\xff]/',$category_name))
					{
						continue;
					}
					$category_name_url = strtolower(product::generate_name_url($category_name));
					//过滤重复的NAME URL
					$name_url_exist = Mycategory::instance()->check_name_url($category_name_url,$this->site_id,$value['id']);
					$perfix = 1;
					while($name_url_exist)
					{
						$category_name_url = $category_name_url . '-' . $perfix;
						$name_url_exist = Mycategory::instance()->check_name_url($category_name_url,$this->site_id,$value['id']);
						$perfix++;
					}
					$data = array();
					$data['name_url'] = $category_name_url;
					if(!Mycategory::instance($value['id'])->edit($data))
					{
						$category_names[] = $value['name'];
					}
				}
			}
			else
			{
				remind::set(Kohana::lang('o_site.product_request_check'),'site/update/update_category_name_url');
			}
			if(count($category_names) > 0)
			{
				$category_name_str = '';
				foreach($category_names as $key=>$value)
				{
					$category_name_str = $value . '|';
				}
				remind::set(Kohana::lang('o_site.name_update_error').$category_name_str,'site/update/update_category_name_url');
			}
			else
			{
				remind::set(Kohana::lang('o_site.name_update_success'),'site/update/update_category_name_url','success');
			}
		}

		$this->template->content = new View("site/update_category_name_url");
		$this->template->content->site = $site;
	}
}
