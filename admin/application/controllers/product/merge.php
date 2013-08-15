<?php defined('SYSPATH') OR die('No direct access allowed.');

class Merge_Controller extends Template_Controller {
	private $package_name = '';
    private $class_name = '';

    // Set the name of the template to use
    public $template_ = 'layout/common_html';

    public function __construct()
    {
        $package_name = substr(dirname(__FILE__),strlen(APPPATH.'controllers/'));
        empty($package_name) && $package_name = 'default';
        $this->package_name = $package_name;
        $this->class_name = strtolower(substr(__CLASS__,0,strpos(__CLASS__,'_')));
        parent::__construct();
        if($this->is_ajax_request()==TRUE){
            $this->template = new View('layout/default_json');
        }
    }
    
    public function index()
    {
    	$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
        );
       try {
            //* 初始化返回数据 */
            $return_data = array(
                'assoc'         => NULL,
                'count'         => 0,
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            $site_ids = role::get_site_ids();
            if (empty($site_ids))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
       		if (empty($request_data['classify_id']) OR !isset($request_data['site_id']) OR !in_array($request_data['site_id'], $site_ids))
       		{
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'),400);
            }
            
            $struct = product::get_struct($request_data, $request_data['site_id']);
            $query_struct_current   = $struct['query'];
            $request_struct_current = $struct['request'];
            $query_struct_current['where']['status'] = 1;
            $query_struct_current['where']['type'] = 0;
            $query_struct_current['where']['classify_id'] = $request_data['classify_id'];
            $query_struct_current['where']['goods_attributeoption_relation_struct_default'] = array('', '{"items":[]}');
            
            // 每页条目数
            controller_tool::request_per_page($query_struct_current, $request_data);
            try{
                $return_data = BLL_Product::index($query_struct_current);
                
               	foreach ($return_data['assoc'] as $index => $product)
               	{
               		$product['pictures'] = BLL_Product_Picture::get($product['id']);
               		$product['picrels']  = array_keys($product['pictures']);
               		$return_data['assoc'][$index] = $product;
               	}
                
                // 模板输出 分页
		        $this->pagination = new Pagination(array(
		            'total_items'    => $return_data['count'],
		            'items_per_page' => $query_struct_current['limit']['per_page'],
		        ));
                $query_struct_current['limit']['page'] = $this->pagination->current_page;
            }catch(MyRuntimeException $ex) {
                //* ==根据业务逻辑定制== */
                //FIXME 根据service层的异常做一些对应处理并抛出用户友好的异常Message
                throw $ex;
            }
            //* 如果是ajax请求缩减返回的字段 ==根据业务逻辑定制== */
            if($this->is_ajax_request()){
                $requestkeys = array('id', 'site_id', 'category_id', 'title', 'uri_name', 'store', 'on_sale', 'goods_price', 'sku');
                array_walk($return_data['assoc'], 'util::simplify_return_array', $requestkeys);
            }
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '';
            $return_struct['content']= $return_data;
			
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                //* html 输出 ==根据业务逻辑定制== */
                $this->template = new View('layout/commonfix_html');
                //* 模板输出 */
                $this->template->return_struct = $return_struct;

                $content = new View($this->package_name.'/product/merge/list');
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data   = $request_data;
                $this->template->content->request_struct = $request_struct_current;
                $this->template->content->query_struct   = $query_struct_current;
                $this->template->content->site_id        = $request_data['site_id'];
                $this->template->content->classify_id    = $request_data['classify_id'];
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->title      = Kohana::config('site.name');
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $return_struct['status'] = 0;
            $return_struct['code']   = $ex->getCode();
            $return_struct['msg']    = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new  View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
    
    public function validate()
    {
    	$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
        );
       try {
            //* 初始化返回数据 */
            $return_data = array(
                'assoc'         => NULL,
                'count'         => 0,
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            $site_ids = role::get_site_ids();
            if (empty($site_ids))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            if (empty($request_data['classify_id']))
            {
            	throw new MyRuntimeException('请首先选择商品类型', 403);
            }
            
            if (empty($request_data['merges']))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }
            
            $classify = ClassifyService::get_instance()->get($request_data['classify_id']);
            $features = BLL_Product_Feature::get_clsfeturs($classify['id']);
            if (empty($features) OR empty($request_data['mfids']) OR !is_array($request_data['mfids']))
            {
            	throw new MyRuntimeException('未找到任何可供合并的特性', 403);
            }
            
            foreach ($request_data['mfids'] as $mfid)
            {
            	if (!isset($features[$mfid]))
            	{
            		throw new MyRuntimeException('所设置的合并特性未找到', 403);
            	}
            }
            
            $merges    = array();
            $fetuoptrs = array();
            foreach ($request_data['merges'] as $index => $merge)
            {
            	if (isset($merge['id']) AND isset($merge['sku']))
            	{
            		try
            		{
            			$merges[$index] = BLL_Product::get($merge['id']);
            		} catch (MyRuntimeException $ex) {
            			throw new MyRuntimeException(sprintf('参与合并的商品 #%s 未找到', $index), 403);
            		}
            		
            		if ($merges[$index]['classify_id'] != $classify['id'])
            		{
            			throw new MyRuntimeException(sprintf('参与合并的商品 #%s 不属于商品类型 “%s”', $index, $classify['name']));
            		}
            		
            		// 验证合并商品的SKU
            		if (BLL_Product::sku_exists($classify['site_id'], $merge['sku'], $merges[$index]['id']))
            		{
            			throw new MyRuntimeException(sprintf('参与合并的商品 #%s 与其他商品的SKU重复', $index), 403);
            		}
            		
            		// 验证是否包含要合并的特性值
            		if (empty($merges[$index]['fetuoptrs']))
            		{
            			$merges[$index]['fetuoptrs'] = array();
            		}
            		
            		$fetuoptr = array();
            		
            		foreach ($request_data['mfids'] as $mfid)
            		{
            			if (!isset($merges[$index]['fetuoptrs'][$mfid]))
            			{
            				throw new MyRuntimeException(sprintf('参与合并的商品 #%s 未设置特性 "%s" 的值', $index, $features[$mfid]['name_manage']));
            			}
            			
            			if (!isset($fetuoptr))
            			{
            				$fetuoptrs[$index] = array();
            			}
            			
            			$fetuoptr[$mfid] = $merges[$index]['fetuoptrs'][$mfid];
            		}
            		
            		foreach ($fetuoptrs as $k => $item)
            		{
            			if ($item == $fetuoptr)
            			{
            				throw new MyRuntimeException(sprintf('参与合并的商品 #%s 特性设置与商品 #%s 相同', $index, $k), 403);
            			}
            		}
            		
            		$fetuoptrs[$index] = $fetuoptr;
            	} else {
            		throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            	}
            }
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '';
            $return_struct['content']= $return_data;
			
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                //* html 输出 ==根据业务逻辑定制== */
                $this->template = new View('layout/commonfix_html');
                //* 模板输出 */
                $this->template->return_struct = $return_struct;

                $content = new View($this->package_name.'/product/merge/list');
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data   = $request_data;
                $this->template->content->request_struct = $request_struct_current;
                
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->title      = Kohana::config('site.name');
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $return_struct['status'] = 0;
            $return_struct['code']   = $ex->getCode();
            $return_struct['msg']    = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new  View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
}