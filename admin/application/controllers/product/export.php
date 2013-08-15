<?php defined('SYSPATH') OR die('No direct access allowed.');

set_time_limit(0);

class Export_Controller extends Template_Controller {
	// Disable this controller when Kohana is set to production mode.
    const ALLOW_PRODUCTION = TRUE;
    
    private $class_name = '';
    private $package = '';
    // Set the name of the template to use
    public $template_ = 'layout/common_html';
    
	/**
     * 构造方法
     */
    public function __construct()
    {
        $this->package = 'product';
        $this->class_name = strtolower(substr(__CLASS__, 0, strpos(__CLASS__, '_')));
        parent::__construct();
        if($this->is_ajax_request()){
            $this->template = new View('layout/default_json');
        }
    }
    
	public function index()
    {
    	$return_struct = array(
            'status' => 0,
            'code' => 501,
            'msg' => 'Not Implemented',
            'content' => array()
        );
        try{
            //$profiler = new Profiler;
            //* 初始化返回数据 */
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            $query_struct = array(
            	'where' => array(
            		'status'  => ProductService::PRODUCT_STATUS_PUBLISH,
            	),
            	'orderby' => array(),
            	'limit'   => array(),
            );
            
            switch (trim($request_data['type']))
            {
            	case 'category':
            		$query_struct['orderby']['id'] = 'ASC';
            		if (!isset($request_data['id']) OR !preg_match('/^\d+$/', $request_data['id']))
            		{
            			throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            		}                    
            		$category = CategoryService::get_instance()->get($request_data['id']);
            		$category['sub_ids'] = trim($category['sub_ids']);
            		if (empty($category['sub_ids']))
            		{
            			$query_struct['where']['category_id'] = $category['id'];
            		} else {
            			$sub_ids = explode(',', $category['sub_ids']);
	            		array_push($sub_ids, $category['id']);
	            		$query_struct['where']['category_id'] = $sub_ids;
            		}
            		break;
            	case 'classify':
            		$query_struct['orderby']['id'] = 'ASC';
            		if (!isset($request_data['id']) OR !preg_match('/^\d+$/', $request_data['id']))
            		{
            			throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            		}
            		$categorys = CategoryService::get_instance()->query_assoc(array('where'=>array(
            			'classify_id' => $request_data['id'],
            		)));
            		if (empty($categorys))
            		{
            			throw new MyRuntimeException(Kohana::lang('o_product.export_pdt_not_found'));
            		}
            		$query_struct['where']['category_id'] = array();
            		foreach ($categorys as $category)
            		{
            			$query_struct['where']['category_id'][] = $category['id'];
            		}            		
            		break;
            	default:		            
		            if (!empty($request_data['product_id']))
		            {
		            	$request_data['product_id'] = explode('-', $request_data['product_id']);
		            	foreach ($request_data['product_id'] as $item)
		            	{
		            		if (!preg_match('/^\d+$/', $item))
		            		{
		            			throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
		            		}
		            	}
		            	$query_struct['where']['id'] = $request_data['product_id'];
		            }
		            
		            if (isset($request_data['category_id']))
		            {
		            	if (!preg_match('/^\d+$/', $request_data['category_id']))
		            	{
		            		throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
		            	}
		            	$query_struct['where']['category_id'] = $request_data['category_id'];
		            }
		            
		            if (isset($request_data['brand_id']))
		            {
		            	if (!preg_match('/^\d+$/', $request_data['brand_id']))
		            	{
		            		throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
		            	}
		            	$query_struct['where']['brand_id'] = $request_data['brand_id'];
		            }
		            
		            if (!empty($request_data['title']))
		            {
		            	$query_struct['where']['title'] = trim($request_data['title']);
		            }
		            
		            if (!empty($request_data['name_manage']))
		            {
		            	$query_struct['where']['name_manage'] = trim($request_data['name_manage']);
		            }
		            
		            if (!empty($request_data['sku']))
		            {
		            	$query_struct['where']['sku'] = trim($request_data['sku']);
		            }
		            
		            if (isset($request_data['orderby']))
		            {
		            	if (!is_array($request_data['orderby']))
		            	{
		            		throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
		            	}
		            	foreach ($request_data['orderby'] as $item)
		            	{
		            		$item = explode('-', $item);
		            		if (count($item) != 2)
		            		{
		            			throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
		            		}
		            		$query_struct['orderby'][$item[0]] = $item[1] === '0' ? 'ASC' : 'DESC';
		            	}
		            }
		            
		            if (isset($request_data['page']))
		            {
		            	if (!preg_match('/^\d+$/', $request_data['page']))
		            	{
		            		throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
		            	}
		            	$query_struct['limit']['page'] = $request_data['page'];
		            	if (!isset($request_data['per_page']) OR !preg_match('/^\d+$/', $request_data['per_page']))
		            	{
		            		throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
		            	}
		            	$query_struct['limit']['per_page'] = $request_data['per_page'];
		            }
            }
            
            $products = ProductService::get_instance()->query_assoc($query_struct);
            if (empty($products))
            {
            	throw new MyRuntimeException(Kohana::lang('o_product.export_pdt_not_found'));
            }
            $csv = ExportService::get_instance()->run($products);
            $csv = csv::encode($csv);
            $csv = iconv('UTF-8', 'GBK//IGNORE', $csv);
            
            if ($this->is_ajax_request())
            {
	            $fid = uniqid();	            
            	$dir = Kohana::config('product.export_tmp_dir');
            	$dir = rtrim(trim($dir), '/');
            	if (!is_dir($dir) && !@mkdir($dir, 0777, TRUE))
            	{
            		throw new MyRuntimeException(Kohana::lang('o_product.export_cte_tmpdir_failed'));
            	}
	            
	            $filename = $dir.'/'.$fid.'.csv';
	            if (!@file_put_contents($filename, $csv))
	            {
	            	throw new MyRuntimeException(Kohana::lang('o_product.export_wte_tmp_failed'));
	            }
            } 
            else 
            {
	            @header('Cache-control: private');
	            @header('Content-Disposition: attachment; filename='.'export-'.date('Ymd', time()).'.csv');
	            @header('Content-type: text/csv; charset=GBK');
	            echo $csv;
	            exit;
            }
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '';
            $return_struct['content'] = url::base().'product/export/download?fid='.$fid;
            
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                //exit('Not Implemented');
                $this->template->content = $return_struct;
            }else{
                // html 输出
                //* 模板输出 */
                $content = new View($this->package . '/' . $this->class_name . '/' . __FUNCTION__);
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->site_id = $site_id;
                $this->template->content->sites   = $sites;
            	$this->template->content->classifies_html = $html;
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
    
    public function download()
    {
    	$return_struct = array(
            'status' => 0,
            'code' => 501,
            'msg' => 'Not Implemented',
            'content' => array()
        );
        try{
            //$profiler = new Profiler;
            //* 初始化返回数据 */
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            if (empty($request_data['fid']))
            {
            	throw new MyRuntimeException('下载导出的商品 CSV 文件失败！', 400);
            }
            
            $dir = Kohana::config('product.export_tmp_dir');
            $dir = rtrim(trim($dir), '/');
            $filename = $dir.'/'.$request_data['fid'].'.csv';
            if (!file_exists($filename) AND is_readable($filename))
            {
            	throw new MyRuntimeException('下载导出的商品 CSV 文件失败！', 400);
            }
            
            $fp = @fopen($filename, 'rb');
            if ($fp)
            {
	            @header('Cache-control: private');
	            @header('Content-Disposition: attachment; filename='.'export-'.date('YmdHis', time()).'.csv');
	            @header('Content-type: text/csv; charset=GBK');
            	while (!feof($fp))
            	{
            		echo fread($fp, 8192);
            	}
            } else {
            	throw new MyRuntimeException('下载导出的商品 CSV 文件失败！', 400);
            }
            @unlink($filename);            
            exit;
        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
}