<?php defined('SYSPATH') OR die('No direct access allowed.');

class Relation_Controller extends Template_Controller 
{
	public $profiler = NULL;
    private $package_name = '';
    private $class_name = '';

    public $template_ = 'layout/common_html';    //Set the name of the template to use

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
            /* 初始化返回数据 */
            $return_data = array(
                'assoc'         => NULL,
                'count'         => 0,
            );
            /* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this -> input -> get();
            
            $struct = product::get_struct($request_data);
            $query_struct_current   = $struct['query'];
            $request_struct_current = $struct['request'];
            
            /* 初始化默认查询结构体 ==根据业务逻辑定制== */
            $request_struct_default = array(
                'type' => NULL,
                'keyword' => NULL,
            );
            /* 初始化请求结构体 */
            $request_struct_current = array();
            /* 设置合并默认请求结构数据到当前请求结构体 */
            $request_struct_current = array_merge($request_struct_current,$request_struct_default);
            /* 初始化默认查询结构体 ==根据业务逻辑定制== */
            $query_struct_default = array(
                'where'=>array(
                    'type' => ProductService::PRODUCT_TYPE_GOODS,
            		'on_sale' => 1,
                    'store >' => 0
                ),
                'like'=>array(
                    ),
                    'orderby' => array(
                        'id' => 'DESC',
                    ),
                    'limit' => array(
                        'per_page' => Kohana::config('my.items_per_page'),//TODO 测试期参数，正式发布建议使用10
                        'page'     => 1,
                    ),
                );
            /* 初始化当前查询结构体 */
            $query_struct_current = array();
            /* 设置合并默认查询条件到当前查询结构体 */
            $query_struct_current = array_merge($query_struct_current,$query_struct_default);
            
            /* 当前支持的查询业务逻辑 ==根据业务逻辑定制== */
            if(isset($request_data['type']) && isset($request_data['keyword']) && !empty($request_data['keyword']))
            {
                switch ($request_data['type']) {
                    case 'sku':
                    	$query_struct_current['like'][$request_data['type']]  = trim($request_data['keyword']);
                        $request_struct_current['keyword'] = $query_struct_current['like'][$request_data['type']];
                    	/*
                        $query_struct_current['where'][$request_data['type']]  = trim($request_data['keyword']);
                        $request_struct_current['keyword'] = $query_struct_current['where'][$request_data['type']];
                        */
                        break;
                    case 'title':
                        $query_struct_current['like'][$request_data['type']]  = trim($request_data['keyword']);
                        $request_struct_current['keyword'] = $query_struct_current['like'][$request_data['type']];
                    	/*
                        $query_struct_current['where'][$request_data['type']]  = trim($request_data['keyword']);
                        $request_struct_current['keyword'] = $query_struct_current['where'][$request_data['type']];
                        */
                        break;
                }
                $request_struct_current['type'] = $request_data['type'];
            }
            /* 当前支持的排序业务逻辑 */
            if(isset($request_data['order']) && !empty($request_data['order'])){
                /* 如果查询请求中包含了排序条件则覆盖当前的默认排序请求*/
                $query_struct_current['orderby'] = array();
                //FIXME 目前排序请求采用的是key和value合在一起减少请求参数，以后如果有必要可以分开
                if(is_array($request_data['order'])){
                    foreach ($request_data['order'] as $order_string){
                        $order_field            = substr($order_string, 0, -1);
                        /*预设0为ASC,1为DESC*/
                        $order_sort             = intval(substr($order_string, -1, 1));
                        $query_struct_current['orderby'][$order_field]= $order_sort==0 ? 'ASC':'DESC';
                    }
                }else{
                    $order_string               = trim($request_data['order']);
                    $order_field                = substr($order_string, 0, -1);
                    /* 预设0为ASC,1为DESC*/
                    $order_sort = intval(substr($order_string, -1, 1));
                    $query_struct_current['orderby'][$order_field] = $order_sort==0 ? 'ASC':'DESC';
                }
            }
            /*
             * 回写$request_struct_current 的 order状态 用于页面调用时判断显示状态,
             * 回写的原因是因为有些默认初始的orderby请求并非在第一次请求时就由_GET得到的,而是在内部设置的。
             */
            if(isset($query_struct_current['orderby']) && !empty($query_struct_current['orderby'])){
                $request_struct_current['order'] = array();
                if(count($query_struct_current['orderby'])==1){// 单条
                    foreach($query_struct_current['orderby'] as $ord_field=>$ord_sort){
                        $request_struct_current['order'] = $ord_field.($ord_sort=='ASC'?0:1);
                    }
                }else{// 多条
                    foreach($query_struct_current['orderby'] as $ord_field => $ord_sort){
                        $request_struct_current['order'][] = $ord_field.($ord_sort == 'ASC' ? 0 : 1);
                    }
                }
            }
            
            //设定默认分页
            $preset_perpages = Kohana::config('my.items_per_pages');
            if(isset($request_data['per_page']) && !empty($request_data['per_page']))
            {
                $query_struct_current['limit']['per_page']    = $request_data['per_page'];
            }
            $request_struct_current['per_page']= $query_struct_current['limit']['per_page'];
            if(isset($request_data['page']) && !empty($request_data['page']) && is_numeric($request_data['page']) && $request_data['page']>0){
                $query_struct_current['limit']['page']        = $request_data['page'];
            }
            $request_struct_current['page']= $query_struct_current['limit']['page'];
            
            try{
              	/* 调用后端服务获取数据 */
                $good_service = ProductService::get_instance();
                $return_data['assoc'] = $good_service->index($query_struct_current);
                $return_data['count'] = $good_service->count($query_struct_current);
                             
                /* 模板输出 分页*/
		        $this->pagination = new Pagination(array(
		            'total_items'    => $return_data['count'],
		            'items_per_page' => $query_struct_current['limit']['per_page'],
		        ));
            }catch(MyRuntimeException $ex) {
                /* ==根据业务逻辑定制== */
                //FIXME 根据service层的异常做一些对应处理并抛出用户友好的异常Message
                throw $ex;
            }
            /* 如果是ajax请求缩减返回的字段 ==根据业务逻辑定制== */
            if($this->is_ajax_request()){
                $requestkeys = array('id', 'title', 'uri_name', 'store', 'on_sale', 'goods_price', 'sku');
                array_walk($return_data['assoc'], 'util::simplify_return_array', $requestkeys);
            }
            /* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '';
            $return_struct['content']= $return_data;

            /* 请求类型 */
            if($this -> is_ajax_request()){
                // ajax 请求
                // json 输出
                $this -> template -> content = $return_struct;
            }else{
                /* html 输出 ==根据业务逻辑定制== */
                $this -> template = new View('layout/commonfix_html');
                /* 模板输出 */
                $this -> template -> return_struct = $return_struct;

                $content = new View($this -> package_name.'/'.$this->class_name.'/'.__FUNCTION__);
                /* 变量绑定 */
                $this -> template -> title = Kohana::config('site.name');
                $this -> template -> content = $content;
                /* 请求结构数据绑定 */
                $this -> template -> content -> request_data   = $request_data;
                $this -> template -> content -> request_struct = $request_struct_current;
                /* 返回结构体绑定 */
                $this -> template -> content -> return_struct  = $return_struct;
                // 当前应用专用数据
                $this -> template -> content -> title          = Kohana::config('site.name');
                
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $return_struct['status'] = 0;
            $return_struct['code']   = $ex -> getCode();
            $return_struct['msg']    = $ex -> getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this -> is_ajax_request()){
            	$this -> template -> content = $return_struct;
            }else{
                $this -> template -> return_struct = $return_struct;
                $content = new  View('info');
                $this -> template -> content = $content;
                /* 请求结构数据绑定 */
                $this -> template -> content -> request_data = $request_data;
                /* 返回结构体绑定 */
                $this -> template -> content -> return_struct = $return_struct;
            }
        }
    }
    
    public function put()
    {
    	$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
        );
       try {
            /* 初始化返回数据 */
            $return_data = array();
            /* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            if (!isset($request_data['relation_ids'])) {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            //* 权限验证 ==根据业务逻辑定制== */
            $good_service = ProductService::get_instance();
            
            $query_relation_ids = explode('-', $request_data['relation_ids']);
            $relation_ids       = array();
            $goods              = array();
            
            $query_struct = array('where' => array(
            	'id' => $query_relation_ids,
            ));
            foreach ($good_service->query_assoc($query_struct) as $relation) {
            	$relation_ids[$relation['id']] = true;
            	$goods[] = $relation;
            }           
            
            $list = new View($this -> package_name.'/'.$this->class_name.'/list');
            $list -> goods  = $goods;
            
            $return_data['relation_ids'] = $relation_ids;
            $return_data['list'] = (string)$list;
            
            /* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '添加成功！';
            $return_struct['content']= $return_data;

            /* 请求类型 */
            if($this -> is_ajax_request()){
                // ajax 请求
                // json 输出
                $this -> template -> content = $return_struct;
            }

        }catch(MyRuntimeException $ex) {
            $return_struct['status'] = 0;
            $return_struct['code']   = $ex->getCode();
            $return_struct['msg']    = $ex->getMessage();

            if($this -> is_ajax_request()){
                $this -> template -> content = $return_struct;
            }else{
                //$return_struct['action']['type']= 'close';  // 当前应用为弹出窗口所以定义失败后续动作为关闭窗口
                $this -> template = new View('layout/default_html');
                $this -> template -> return_struct = $return_struct;
                $content = new  View('info');
                $this -> template -> content = $content;
                /* 请求结构数据绑定 */
                $this -> template -> content -> request_data  = $request_data;
                /* 返回结构体绑定 */
                $this -> template -> content -> return_struct = $return_struct;
            }
        }
    }
}