<?php defined('SYSPATH') OR die('No direct access allowed.');

class Payment_type_Controller extends Template_Controller {

	public function index()
	{
        //权限验证
        role::check('manage_payment_type');

		$this->template->content = new View("manage/payment_type_list");

        //搜索功能
        $search_arr     = array('id');
        $where          = array();
		$where_view     = array();

        //列表排序
        $orderby_arr= array
            (
                0   => array('id'=>'DESC'),
                1   => array('id'=>'ASC'),
                2   => array('name'=>'ASC'),
                3   => array('name'=>'DESC'),
                4   => array('image_url'=>'ASC'),
                5   => array('image_url'=>'DESC'),
                6   => array('driver'=>'ASC'),
                7   => array('driver'=>'DESC'),
                8   => array('submit_url'=>'ASC'),
                9   => array('submit_url'=>'DESC'),
            );

        $orderby    = controller_tool::orderby($orderby_arr);
        //每页显示条数
        $per_page   = 100;

        //调用分页
        $this->pagination = new Pagination(array(
            'total_items'    => 100,
            'items_per_page' => $per_page,
        ));

        //调用列表
        $this->template->content->payment_type_list		= Mypayment_type::instance()->payment_types($where,$orderby,$per_page,$this->pagination->sql_offset);

	}

    /**
     * 修改支付类型
     */
    function edit() {
        $id = intval($this->uri->segment('id'));
        if(!$id)
		{
            remind::set(Kohana::lang('o_global.access_denied'),'manage/payment_type');
        }
        //权限验证
        $site_id_list = role::check('manage_payment_type');
        if($_POST) {
            //标签过滤
            tool::filter_strip_tags($_POST);
        	
			if(Mypayment_type::instance($id)->edit($_POST))
			{
				remind::set(Kohana::lang('o_global.update_success'),'manage/payment_type','success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),'manage/payment_type/edit/id/'.$id,'error');
			}
        }

        $this->template->content = new View("manage/payment_type_edit");

        $data = Mypayment_type::instance($id)->get();

        $this->template->content->data = $data;
    }
    
    /**
     * 添加新支付类型
     */
    function add() {        
        //权限验证
        role::check('manage_payment_type');
        if($_POST) {
            //标签过滤
            tool::filter_strip_tags($_POST);
        	
			$payment_type = Mypayment_type::instance();
			if($payment_type->add($_POST))
			{
				remind::set(Kohana::lang('o_global.add_success'),'manage/payment_type','success');
			}
			else
			{
				$errors = $payment_type->errors() ;
				remind::set(Kohana::lang('o_global.add_error'),'manage/payment_type/add','error');
			}
        }
        $this->template->content = new View("manage/payment_type_add");
    }
    
    
    /**
     * 批量删除支付方式
     */
    public function batch_delete()
    {
        //初始化返回数据
        $return_data = array();
        //请求结构体
        $request_data = array();
        /* 可管理的站点ID列表 */
        role::check('manage_payment_type'); 
        try { 
            $payment_type_ids = $this->input->post('payment_type_id');
            
            if(is_array($payment_type_ids) && count($payment_type_ids) > 0)
            {
                /* 初始化默认查询条件 */
                $query_struct = array(
                    'where'=>array(
                        'id'   => $payment_type_ids,
                    ),
                    'like'=>array(),
                    'limit'     => array(
                        'per_page'  =>300,
                        'offset'    =>0
                    ),
                );
                $payment_types = Mypayment_type::instance()->query_assoc($query_struct);
                
                /* 删除失败的 */
                $failed_payment_type_names = '';
                /* 执行操作 */
                foreach($payment_types as $key=>$payment_type)
                {
                    if(!Mypayment_type::instance($payment_type['id'])->delete())
                    {
                        $failed_payment_type_names .= ' | ' . $payment_type['name'];
                    }
                }
                if(empty($failed_payment_type_names))
                {
                    throw new MyRuntimeException(Kohana::lang('o_manage.delete_payment_type_success'),403);
                }
                else
                {
                    /* 中转提示页面的停留时间 */
                    $return_struct['action']['time'] = 10;
                    $failed_payment_type_names = trim($failed_payment_type_names,' | ');
                    throw new MyRuntimeException(Kohana::lang('o_manage.delete_payment_type_error',$failed_payment_type_names),403);
                }
            }
            else
            {
                throw new MyRuntimeException(Kohana::lang('o_global.data_load_error'),403);
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
