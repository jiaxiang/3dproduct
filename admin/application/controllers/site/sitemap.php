<?php
defined('SYSPATH') or die('No direct access allowed.');
class Sitemap_Controller extends Template_Controller {
    // Disable this controller when Kohana is set to production mode.
    const ALLOW_PRODUCTION = TRUE;
    
    public $site_id = 0;
    // Set the name of the template to use
    public $template_ = 'layout/common_html';
    
    /**
     * 构造方法
     */
    public function __construct()
    {
        parent::__construct();
        if($this->is_ajax_request()){
            $this->template = new View('layout/default_json');
        }
        $this->site_id = site::id();
    }
    
    public function index()
    {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            
            //$profiler = new Profiler;
            //* 初始化返回数据 */
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            //* 实现功能后屏蔽此异常抛出 */
            //throw new MyRuntimeException('Not Implemented',501);
            

            //权限验证
            if($this->site_id == 0){
                throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            role::check('default', $this->site_id, 0);
            
            // 执行业务逻辑
            $category_service = CategoryService::get_instance();
            $str = '<option value={$id} {$selected}>{$spacer}{$title}</option>';
            $category_list = $category_service->get_tree($str);
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '';
            $return_struct['content'] = $return_data;
            
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                // html 输出
                //* 模板输出 */
                $content = new View('site/sitemap');
                //* 变量绑定 */
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->site_id = $this->site_id;
                $this->template->content->category_list = $category_list;
            
            } // end of request type determine
        

        }catch(MyRuntimeException $ex){
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
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
    
    public function build()
    {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            
            //$profiler = new Profiler;
            //* 初始化返回数据 */
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->post();
            //* 实现功能后屏蔽此异常抛出 */
            //throw new MyRuntimeException('Not Implemented',501);
            //权限验证
            if($this->site_id == 0){
                throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            role::check('default', $this->site_id, 0);
            
            // 调用底层服务
            $sitemap_service = SitemapService::get_instance();
            //业务逻辑
            $xmlContent = '';
            $xmlContent .= '<?xml version="1.0" encoding="UTF-8"?>';
            $xmlContent .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            //添加首页
            if(!empty($request_data['index']) && is_numeric($request_data['index'])){
                $priority = number_format($request_data['index'], 1);
            }else{
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            $site_domain = Mysite::instance($this->site_id)->get('domain');
            $xmlContent .= sitemap::Render('http://'.$site_domain, 0, 'always', $priority);
            //添加分类页面
            $categories = $sitemap_service->get_category_page_by_site_id($this->site_id);
            if(!empty($request_data['category']) && is_numeric($request_data['category'])){
                $priority = number_format($request_data['category'], 1);
            }else{
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            if(!empty($categories)){
                foreach($categories as $category){
                    if(empty($request_data['exclude_category']) || (!empty($request_data['exclude_category']) && !in_array($category['id'], $request_data['exclude_category']))){
                        $xmlContent .= sitemap::Render(category::permalink($category['id']), 0, 'weekly', $priority);
                    }
                }
            }
            //添加商品页面
            if(!empty($request_data['product']) && is_numeric($request_data['product'])){
                $priority = number_format($request_data['product'], 1);
            }else{
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            
            if(isset($request_data['on_sale'])){
                $on_sale = intval($request_data['on_sale']);
            }else{
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            $products = $sitemap_service->get_product_page_by_site_id($this->site_id,$on_sale);
            if(!empty($request_data['exclude_product'])){
                if(preg_match('/^([a-zA-Z0-9_]+,)*[a-zA-Z0-9_]+$/i', $request_data['exclude_product'])){
                    $request_data['exclude_product'] = explode(',',$request_data['exclude_product']);
                }else{
                    throw new MyRuntimeException(Kohana::lang('o_site.product_id_format_check'), 404);
                }
            }
            if(!empty($products)){
                foreach($products as $product){
                    if(empty($request_data['exclude_product'])){
                        $xmlContent .= sitemap::Render(product::permalink($product['id']), $product['update_timestamp'], 'weekly', $priority);
                    }elseif(!empty($request_data['exclude_product']) && !in_array($product['sku'],$request_data['exclude_product'])){
                        $xmlContent .= sitemap::Render(product::permalink($product['id']), $product['update_timestamp'], 'weekly', $priority);
                    }
                }
            }
            //添加促销页
            if(!empty($request_data['promotion']) && is_numeric($request_data['promotion'])){
                $priority = number_format($request_data['promotion'], 1);
            }else{
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            $promotions = $sitemap_service->get_promotion_page_by_site_id($this->site_id);
            if(!empty($promotions)){
                $route = Myroute::instance()->get();
                $action = $route['promotion'];
                foreach($promotions as $promotion){
                    $xmlContent .= sitemap::Render('http://'.$site_domain.'/'.$action.'/'.$promotion['id'], time(), 'weekly', $priority);
                }
            }
            //添加文案页
            if(!empty($request_data['doc']) && is_numeric($request_data['doc'])){
                $priority = number_format($request_data['doc'], 1);
            }else{
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            $docs = $sitemap_service->get_doc_page_by_site_id($this->site_id);
            if(!empty($docs)){
                foreach($docs as $doc){
                    $lastmod = strtotime($doc['updated']);
                    $xmlContent .= sitemap::Render('http://'.$site_domain.'/'.$doc['permalink'], $lastmod, 'weekly', $priority);
                }
            }
            
            $xmlContent .= '</urlset>';
            $data['sitemap'] = $xmlContent;
            if(!Mysite_detail::instance()->update_by_site_id($this->site_id,$data)){
                throw new MyRuntimeException(Kohana::lang('o_site.sitemap_error_handle'), 500);
            }
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '操作成功';
            $return_struct['content'] = $return_data;
            $return_struct['action'] = array (
                'type' => 'location', 
                'url' => url::base() . 'site/sitemap' 
            );
            
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                // html 输出
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            
            } // end of request type determine
        

        }catch(MyRuntimeException $ex){
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
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
?>
