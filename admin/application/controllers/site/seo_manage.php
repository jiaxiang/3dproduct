<?php
defined('SYSPATH') or die('No direct access allowed.');

class Seo_manage_Controller extends Template_Controller
{
	public $site_id;
    public $template_ = 'layout/common_html';

    public function __construct()
    {
        role::check('site_seo');
        parent::__construct();
        if($this->is_ajax_request()==TRUE)
        {
            $this->template = new View('layout/default_json');
        }
    }

	/**
	 * 更新商品SEO信息
	 */
	public function index()
	{
		$site = Mysite::instance()->get();

		if($_POST)
		{
			// 收集请求数据
			$domain = $this->input->post('domain');
			$category_id = $this->input->post('category_id');
			$is_children = $this->input->post('is_children');
			$meta_title = $this->input->post('meta_title');
			$meta_keywords = $this->input->post('meta_keywords');
			$meta_description = $this->input->post('meta_description');
			if(empty($domain)||$domain!=$site['domain'])
			{
				remind::set(Kohana::lang('o_site.seo_domain_cannot_null'),'site/seo_manage', 'error');
			}
			//分类ID数组
			$category_ids = array();
			//分类ID和名称的对应关系
			$category_names = array();
			//分类id不为空
			if($category_id>0){
				$category = CategoryService::get_instance()->get($category_id);
				//先处理选择分类的信息
				$result = Seo_manageService::get_instance()->get_by_category_id($category_id);

				$set_data = array(
					'category_id'          =>  $category_id,
					'is_children'          =>  $is_children,
					'meta_title'           =>  $meta_title,
					'meta_keywords'        =>  $meta_keywords,
					'meta_description'     =>  $meta_description
				);
				if(isset($result) && $result['id'] > 0){
					$set_data['update_timestamp'] = date('Y-m-d H:i:s',time());
					Seo_manageService::get_instance()->set($result['id'], $set_data);
				}else{
					Seo_manageService::get_instance()->add($set_data);
				}

				//包含子分类			
				if($is_children == Seo_manageService::SEO_CATEGORY_IS_CHILDREN)
				{					
					//得到分类下的子分类
					$childrens = CategoryService::get_instance()->get_childrens_by_category_id($category_id);
					
					foreach($childrens as $key=>$value)
					{
						//记录更新过的分类ID
						$category_ids[] = $value;
						$category_child = CategoryService::get_instance()->get($value);
						$category_names[$value] = $category_child['title'];
						//查看分类信息已经存在的SEO信息
						$result_child = Seo_manageService::get_instance()->get_by_category_id($value);
						$set_data = array(
							'category_id'          =>  $value,
							'is_children'          =>  Seo_manageService::SEO_CATEGORY_IS_CHILDREN,
							'meta_title'           =>  $meta_title,
							'meta_keywords'        =>  $meta_keywords,
							'meta_description'     =>  $meta_description
						);
						if(isset($result_child) && $result_child['id'] > 0){
							$set_data['update_timestamp'] = date('Y-m-d H:i:s',time());
							Seo_manageService::get_instance()->set($result_child['id'], $set_data);
						}else{
							Seo_manageService::get_instance()->add($set_data);
						}
					}

					$category_ids[] = $category_id;					
					$category_names[$category_id] = $category['title'];
					// 初始化默认查询条件[商品表]
					$reqeust_query_struct = array(
						'where' => array(
							'category_id'	=> $category_ids
						));	
				}
				else
				{
					$category_names[$category_id] = $category['title'];
					$reqeust_query_struct = array(
						'where' => array(
							'category_id'	=> $category_id
						));						
				}					
			}else{
				//没有选择分类，默认更新全部分类
				$result = Seo_manageService::get_instance()
					->get_by_category_id(Seo_manageService::SEO_CATEGORY_IS_NULL);

				$set_data = array(
					'is_children'          =>  Seo_manageService::SEO_CATEGORY_IS_CHILDREN,
					'meta_title'           =>  $meta_title,
					'meta_keywords'        =>  $meta_keywords,
					'meta_description'     =>  $meta_description
				);
				if(isset($result['id']) && $result['id'] > 0){
					$set_data['update_timestamp'] = date('Y-m-d H:i:s',time());
					Seo_manageService::get_instance()->set($result['id'], $set_data);			
				}else{
					Seo_manageService::get_instance()->add($set_data);
				}

				//得到站点所有分类
				$categories = CategoryService::get_instance()->get_categories();
				foreach($categories as $cate_key=>$cate_value)
				{
					if($cate_value['id']<=0) continue;
					$category_ids[] = $cate_value['id'];
					$category_names[$cate_value['id']] = $cate_value['title'];

					$result = Seo_manageService::get_instance()
						->get_by_category_id($cate_value['id']);
					$set_data = array(
						'category_id'          =>  $cate_value['id'],
						'is_children'          =>  Seo_manageService::SEO_CATEGORY_IS_CHILDREN,
						'meta_title'           =>  $meta_title,
						'meta_keywords'        =>  $meta_keywords,
						'meta_description'     =>  $meta_description,
						'update_timestamp'     =>  date('Y-m-d H:i:s',time()),
					);
					if(isset($result['id']) && $result['id'] > 0){
						Seo_manageService::get_instance()->set($result['id'], $set_data);			
					}else{
						$set_data['create_timestamp'] = date('Y-m-d H:i:s',time());
						Seo_manageService::get_instance()->add($set_data);
					}
				}

				$reqeust_query_struct = array(
					'where' => array(                        
						'category_id'	=> $category_ids,
					));					
			}
            
			//获得总数
			$count = ProductService::get_instance()->count($reqeust_query_struct);			

			if($count < 10000)
			{				
				$products = ProductService::get_instance()->query_assoc($reqeust_query_struct);
                
				foreach($products as $key=>$value)
				{
					$product_name = $value['title'];
					$category_name = isset($category_names[$value['category_id']])?$category_names[$value['category_id']]:'';
					$site_domain = $site['domain'];
					$goods_price = isset($value['goods_price'])?$value['goods_price']:0;

					$title = str_replace('{product_name}',$product_name,$meta_title);
					$keywords = str_replace('{product_name}',$product_name,$meta_keywords);
					$description = str_replace('{product_name}',$product_name,$meta_description);

					$title = str_replace('{category_name}',$category_name,$title);
					$keywords = str_replace('{category_name}',$category_name,$keywords);
					$description = str_replace('{category_name}',$category_name,$description);

					$title = str_replace('{site_domain}',$site_domain,$title);
					$keywords = str_replace('{site_domain}',$site_domain,$keywords);
					$description = str_replace('{site_domain}',$site_domain,$description);
					
					$title = str_replace('{price}',$goods_price,$title);
					$keywords = str_replace('{price}',$goods_price,$keywords);
					$description = str_replace('{price}',$goods_price,$description);
					
					$data = array(
						'meta_title'        => $title,
						'meta_keywords'     => $keywords,
						'meta_description'  => $description,
					);
                    Product_detailService::get_instance()->set_by_product_id($value['id'], $data);
				}
				remind::set(Kohana::lang('o_global.update_success'),'site/seo_manage','success');
			}
			else
			{
				remind::set(Kohana::lang('o_site.product_request_check'),'site/seo_manage');
			}
		}

		//没有选择分类，默认更新全部分类
		$data = Seo_manageService::get_instance()
			->get_by_category_id(Seo_manageService::SEO_CATEGORY_IS_NULL);
		// 分类列表默认关联第一个
		$categorys_tree = CategoryService::get_instance()
			->get_tree('<option value={$id} {$selected}>{$spacer}{$title}</option>');

		$this->template->content = new View("site/update_product_seo");
		$this->template->content->data = $data;
		$this->template->content->site = $site;	
		$this->template->content->categorys_tree = $categorys_tree;
	}

	/**
	 * 得到分类已经的SEO信息
	 */
	public function get_category_product_seo()
	{
    	$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
        );
		// 初始化返回数据
		$return_data = array();
		//请求结构体
		$request_data = array();
		try {
			/* 分类 */
			$category_id = $this->input->get('category_id');
            
	        // 初始化默认查询条件
	        $query_struct = array(
	            'where'     =>array(
                    'category_id' => $category_id,
	        	),
	            'like'      =>array(),
	            'orderby'   => array(
	                'id'    =>'DESC',
	            ),
	            'limit'     => array(
	                'per_page'  =>100,
	                'offset'    =>0,
	            ),
	        );

			if($category_id > 0) {				
				//判断分类下面是否有子分类
				$childrens = CategoryService::get_instance()->get_childrens_by_category_id($category_id);
				if(isset($childrens) && !empty($childrens)){
					$is_contain_child = 1;
				}else{
					$is_contain_child = 0;
				}
			}else{
				$query_struct['where']['category_id'] = Seo_manageService::SEO_CATEGORY_IS_NULL;
				$is_contain_child = 0;
			}
			$seo_manage = Seo_manageService::get_instance()->query_assoc($query_struct);

			if(is_array($seo_manage) && count($seo_manage) > 0){
				$return_struct['status']           = 1;
				$return_struct['code']             = 200;
				$return_data['data']               = $seo_manage;
				
				$return_struct['content']          = $return_data;
				$return_struct['is_contain_child'] = $is_contain_child;
			}
            
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
            	die('No direct access allowed.');
            }// end of request type determine
		} catch (MyRuntimeException $ex) {
			$return_struct['status'] = 0;
			$return_struct['code']   = $ex->getCode();
			$return_struct['msg']    = $ex->getMessage();
			
			if($this->is_ajax_request()) {
				$this->template = new View('layout/empty_html');
				$this->template->content = $return_struct['msg'];
			} else {
				$this->template->return_struct = $return_struct;

				$content = new View('info');
				$this->template->content = $content;
				/* 请求结构数据绑定 */
				$this->template->content->request_data = $request_data;
				/* 返回结构体绑定 */
				$this->template->content->return_struct = $return_struct;
			}
		}
	}
}
