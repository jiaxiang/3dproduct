<?php defined('SYSPATH') or die('No direct access allowed.');

class Productsearch_Controller extends Template_Controller {
    // Set the name of the template to use
    public $template_ = 'layout/common_html';
    
    public function __construct()
    {
        parent::__construct();
        if($this->is_ajax_request() == TRUE){
            $this->template = new View('layout/default_json');
        }
    }
    
    /**
     * 首页
     */
    public function index()
    {
        // 初始化返回数据
        $return_data = array ();
        //请求结构体
        $request_data = array ();
        try{
            /* 清空商品搜索表中的脏数据 */
            /* 初始化默认查询结构体 */
            $query_struct = array (
                'where' => array (), 
                'like' => array (), 
                'orderby' => array (), 
                'limit' => array (
                    'per_page' => 100, 
                    'page' => 1 
                ) 
            );
            
            /* 清理已经删除的产品 */
            do{
                $productsearches = ProductsearchService::get_instance()->query_assoc($query_struct);
                if(is_array($productsearches) && count($productsearches) > 0){
                    foreach($productsearches as $value){
                    	try{
	                        $product = ProductService::get_instance()->get($value['product_id']);
	                        if(!is_array($product) || $product['id'] < 1){
	                            ProductsearchService::get_instance()->delete_by_productsearch_id($value['id']);
	                        }
                    	}catch(MyRuntimeException $ex){
				            Kohana::log('error', 'Productsearch error:' . $value['product_id'] . ' product not found.');
				        }
                    }
                    $query_struct['limit']['page']++;
                }else{
                    break;
                }
            }while(1);
            
            //更新产品内容到产品搜索快照  获取产品描述
            $query_struct = array (
                'where' => array (
                ) 
            );
            $productdescsections = array ();
            $descsections = Product_detailService::get_instance()->query_assoc($query_struct);
            if(!empty($descsections))
            {
                foreach($descsections as $val){
                    if(!empty($productdescsections[$val['product_id']])){
                        $productdescsections[$val['product_id']] .= ' ' . $val['content'];
                    }else{
                        $productdescsections[$val['product_id']] = $val['content'];
                    }
                }
            }
            
            /* 初始化默认查询结构体 */
            $query_struct = array (
                'where' => array (
                ), 
                'like' => array (), 
                'orderby' => array (), 
                'limit' => array (
                    'per_page' => 100, 
                    'page' => 1 
                ) 
            );
            do{
                $products = ProductService::get_instance()->query_assoc($query_struct);
                if(is_array($products) && count($products) > 0){
                    foreach($products as $key => $value){
                        $productsearch = ProductsearchService::get_instance()->get_by_product_id($value['id']);
                        
                        $productsearch_data = array ();
                        $productsearch_data['product_id'] = $value['id'];
                        $productsearch_data['category_id'] = $value['category_id'];
                        $productsearch_data['brand_id'] = $value['brand_id'];
                        $productsearch_data['title'] = $value['title'];
                        $productsearch_data['brief'] = $value['brief'];
                        if(!empty($productdescsections[$value['id']])){
                            $productsearch_data['description'] = $productdescsections[$value['id']];
                        }
                        if($productsearch['id'] > 0){
                            ProductsearchService::get_instance()->set($productsearch['id'], $productsearch_data);
                        }else{
                            ProductsearchService::get_instance()->add($productsearch_data);
                        }
                    }
                    $query_struct['limit']['page']++;
                }else{
                    break;
                }
            }while(1);
            
            remind::set(Kohana::lang('o_global.set_success'), '/index/desktop', 'success');
        }catch(MyRuntimeException $ex){
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
}
