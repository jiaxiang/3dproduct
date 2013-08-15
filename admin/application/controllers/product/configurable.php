<?php defined('SYSPATH') OR die('No direct access allowed.');

class Configurable_Controller extends Template_Controller {
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


    public function validate_spec()
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
            
            if (empty($request_data['configurable_id']) 
                || empty($request_data['attribute_spec']) 
                || !is_array($request_data['attribute_spec']))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }
            
            $product_id = (int)$request_data['product_id'];
            $configurable_id = (int)$request_data['configurable_id'];
            $attribute_spec = $request_data['attribute_spec'];
            
            if(BLL_Product_Type_Configurable::attribute_spec_exists($configurable_id, $attribute_spec, $product_id))
            {
            	throw new MyRuntimeException(Kohana::lang('o_product.spec_has_exists_in_configurable'), 500);
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
                throw new MyRuntimeException(Kohana::lang('o_product.spec_no_exists'), 200);
            }
        }catch(MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
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
            
            if (empty($request_data['product_id']))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }
            
            if (empty($request_data['goods']) OR !is_array($request_data['goods']))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }
            
            try
            {
            	$product = BLL_Product::get($request_data['product_id']);
            } catch (MyRuntimeException $ex) {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }
            
            foreach ($request_data['goods'] as $index => $good)
            {
            	if (isset($good['sku']))
            	{
            		empty($good['id']) AND $good['id'] = 0;
            		
            		if (BLL_Product_Type_Assembly::good_sku_exists($good['sku'], $good['id']))
            		{
            			throw new MyRuntimeException(sprintf(Kohana::lang('o_product.good_sku_has_exists'), $index), 403);
            		}
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
                
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct; 
            }// end of request type determine

        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
}