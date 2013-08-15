<?php defined('SYSPATH') OR die('No direct access allowed.');

class Currency_Controller extends Template_Controller {
	protected $current_flow = 'currency';

	public function __construct()
	{
		parent::__construct();
   		/* 权限验证 国家管理 */
        role::check('site_currency');
	}
	
	/**
	 * 币种列表
	 */
	public function index()
	{
		/* 初始化结构体*/
		$query_struct = array(
			'where'=>array(
			),
			'orderby' => array (
                'id' => 'DESC' 
            ),
		);
		$this->template->content = new View("site/currency_list");

        //每页显示条数
        $per_page    = controller_tool::per_page();
        $query_struct['limit']['per_page'] = $per_page;
        //调用分页
        $this->pagination = new Pagination(array(
            'total_items'    => Mycurrency::instance()->query_count($query_struct),
            'items_per_page' => $per_page,
        ));

        $query_struct['limit']['offset'] = $this->pagination->sql_offset;
        //调用列表
        $this->template->content->currency_list	= Mycurrency::instance()->query_assoc($query_struct);
	}

    /**
     * 修改用户信息
     */
    function edit($id = 0) {
        if(!$id)
		{
            remind::set(Kohana::lang('o_global.bad_request'),'site/currency','error');;
        }

        if($_POST) {
            //标签过滤
            tool::filter_strip_tags($_POST);
        	
			if(Mycurrency::instance($id)->edit($_POST))
            {
                //更新默认币种显示
                Mycurrency::instance($id)->update_currencies_default();

                if($_POST['active']==0&&!Mycurrency::instance($id)->check_currencies_active())
                {
                    remind::set(Kohana::lang('o_site.current_not_exist'),'site/currency','error');
                }
                else
                {
                    remind::set(Kohana::lang('o_global.update_success'),'site/currency','success');
                }
            }
            else
            {
                remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
            }
        }

        $this->template->content = new View("site/currency_edit");
        $this->template->content->data = Mycurrency::instance($id)->get();
    }

    /**
     * 添加新币种
     */
    function add() {        
        if($_POST) {
            //标签过滤
            tool::filter_strip_tags($_POST);
        	
            $data = $_POST;
            $currency = Mycurrency::instance();
            if($currency->exist($data))
            {
                remind::set(Kohana::lang('o_site.current_has_exist'),null,'error');
            }
            else
            {
                unset($data['id']);
                $data['active']     = 1;
                if($id = $currency->add($data))
                {
                    //更新默认币种显示
                    Mycurrency::instance($id)->update_currencies_default();
                    
                    $site_next_flow = site::site_next_flow($this->current_flow);
                    $submit_target = intval($this->input->post('submit_target'));
                    //判断添加成功去向
                    switch($submit_target)
                    {
                    case 1:
                        remind::set(Kohana::lang('o_global.add_success'),'site/currency/add','success');
                    case 2:
                        remind::set(Kohana::lang('o_global.add_success'),$site_next_flow['url'],'success');
                    default:
                        remind::set(Kohana::lang('o_global.add_success'),'site/currency','success');
                    }
                }
                else
                {
                    $errors = $currency->errors() ;
                    remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
                }
            }
        }
        $currency_code = kohana::config('currency.code');
        $currency_sign = kohana::config('currency.sign');
        $currency_name = kohana::config('currency.name');
        $currency_rate = kohana::config('currency.rate');
        $currency_format = kohana::config('currency.format');

        $currency_data = json_encode(
	        array(
	        	'code'=>$currency_code,
		        'sign'=>$currency_sign,
		        'name'=>$currency_name,
		        'rate'=>$currency_rate
	        )
        );
        
        $this->template->content = new View("site/currency_add");
        $this->template->content->currency_data = $currency_data;
        $this->template->content->currency_name = $currency_name;
        $this->template->content->currency_format = $currency_format;
    }

    /**
     * 删除
     */
    function do_delete($id) {
        if(!$id)
        {
            remind::set(Kohana::lang('o_global.bad_request'),request::referrer(),'error');
        }
        $currency = Mycurrency::instance($id)->get();
        if($currency['default'] == 1)
        {
            remind::set(Kohana::lang('o_site.default_current_cannot_delete'),request::referrer(),'error');
        }

        if(Mycurrency::instance($id)->delete())
        {
            remind::set(Kohana::lang('o_global.delete_success'),request::referrer(),'success');
        }
        else
        {
            remind::set(Kohana::lang('o_global.delete_error'),request::referrer(),'error');
        }
    }
    
    /**
     * 批量删除币种
     */
    public function batch_delete()
    {        
        //初始化返回数据
        $return_data = array();
        //请求结构体
        $request_data = array();

        try {
            $currency_ids = $this->input->post('currency_ids');
            
            if(is_array($currency_ids) && count($currency_ids) > 0)
            {
                /* 初始化默认查询条件 */
                $query_struct = array(
                    'where'=>array(
                        'id'   => $currency_ids,
                    ),
                    'like'=>array(),
                    'limit'     => array(
                    ),
                );
                $currencies = Mycurrency::instance()->query_assoc($query_struct);
                
                /* 删除失败的用户 */
                $failed_currency_names = '';
                /* 执行操作 */
                foreach($currencies as $key=>$currency)
                {
                    if($currency['default'] == 1)
			        {
			            remind::set(Kohana::lang('o_site.default_current_cannot_delete'),request::referrer(),'error');
			        }
                    if(!Mycurrency::instance($currency['id'])->delete())
                    {
                        $failed_currency_names = ' | ' . $currency['name'];
                    }

                }
                if(empty($failed_currency_names))
                {
                    throw new MyRuntimeException(Kohana::lang('o_site.delete_currency_success'),200);
                }
                else
                {
                    /* 中转提示页面的停留时间 */
                    $return_struct['action']['time'] = 10;
                    $failed_carrier_names = trim($failed_currency_names,'|');
                    throw new MyRuntimeException(Kohana::lang('o_site.delete_currency_error',$failed_currency_names),200);
                }
            }
            else
            {
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'),403);
            }
        } catch (MyRuntimeException $ex) {
            $return_struct['status'] = 0;
            $return_struct['code']   = $ex->getCode();
            $return_struct['msg']    = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
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
