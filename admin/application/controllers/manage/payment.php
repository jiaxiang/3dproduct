<?php defined('SYSPATH') OR die('No direct access allowed.');

class Payment_Controller extends Template_Controller {

    public function index(){
        //权限验证
        role::check('manage_payment');

        $this->template->content = new View("manage/payment_list");

        //搜索功能
        $search_arr     = array('id');
        $where          = array();
        $where_view     = array();

        $where['manager_id'] = role::root_manager_id();
        //列表排序
        $orderby_arr = array(
                0   => array('id'=>'DESC'),
                1   => array('id'=>'ASC'),
                2   => array('payment_type_id'=>'ASC'),
                3   => array('payment_type_id'=>'DESC'),
                4   => array('account'=>'ASC'),
                5   => array('account'=>'DESC'),
                6   => array('position'=>'ASC'),
                7   => array('position'=>'DESC'),
                8   => array('active'=>'ASC'),
                9   => array('active'=>'DESC'),
            );

        $orderby    = controller_tool::orderby($orderby_arr);

        //每页显示条数
        $per_page    = controller_tool::per_page();

        //调用分页
        $this->pagination = new Pagination(array(
            'total_items'    => Mypayment::instance()->count($where),
            'items_per_page' => $per_page,
        ));

        //调用列表
        $this->template->content->payment_list        = Mypayment::instance()->payments($where,$orderby,$per_page,$this->pagination->sql_offset);
    }

    /**
     * 修改支付
     */
    function edit() {
        $id = intval($this->uri->segment('id'));
        if(!$id)
        {
            remind::set(Kohana::lang('o_global.bad_request'),'manage/payment');
        }  

        $data = Mypayment::instance($id)->get();
        $data['payment_type'] = Mypayment_type::instance($data['payment_type_id'])->get();
        $data['args'] = unserialize($data['args']);

        if($data['payment_type']['driver'] == 'paypalec')
        {
            $data['paypalec'] = $data['args'];
        }
        else
        {
            $keys = array('passwd','signature','version');
            $data['paypalec'] = arr::init_arr($keys);
        }

        if($data['payment_type']['driver'] == 'motopay')
        {
            $data['motopay'] = $data['args'];
        }
        else
        {
            $keys = array('terminalid','key');
            $data['motopay'] = arr::init_arr($keys);
        }
        
        $this->template->content = new View("manage/payment_edit", array(
                'data'              => $data,
                'payment_types'     => Mypayment_type::instance()->payment_types(),    
                'payment_type_list' => Mypayment_type::instance()->select_list(),        
            ));
    }

    /**
     * 修改支付
     */
    function do_edit($payment_type_id, $id) {
        if(!$id){
            remind::set(Kohana::lang('o_global.bad_request'),'manage/payment');
        }
        
        $post = new Validation($_POST);
        //权限验证
        //$this->profiler = new Profiler;
        $site_id_list = role::check('manage_payment');
        $data = array();
        $data['active']             = $post['active'];
        $data['manager_id']         = role::root_manager_id();
        $data['payment_type_id']    = $payment_type_id;
        $data['account']            = $this->input->post('account')?$this->input->post('account'):'';

        $payment = Mypayment::instance($id);
        switch($payment_type_id){
            case '1':
            case '2':
            case '3':
            case '4':
                if($payment->edit($data))
                {
                    remind::set(Kohana::lang('o_global.update_success'),request::referrer(),'success');
                }
                else
                {
                    remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
                }
                break;
            case '5':
            case '6':
                $post->pre_filter('trim');
                $post->add_rules('jump_url','required','length[1,200]');
                $post->add_rules('passwd','required','standard_text');
                $post->add_rules('signature','required','standard_text');
                $post->add_rules('version','required','standard_text');
                if (!($post->validate()))
                {
                    $errors = $post->errors();
                    log::write(Kohana::lang('o_manage.form_error'),$errors,__FILE__,__LINE__);
                }
                $data['args'] = serialize($post->as_array());
                if($payment->edit($data))
                {
                    remind::set(Kohana::lang('o_global.update_success'),'manage/payment','success');
                }
                else
                {
                    remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
                }
                break;
            case '8':
            case '9':
                $post->pre_filter('trim');
                $post->add_rules('terminalid','required');
                $post->add_rules('key','required');
                if (!($post->validate()))
                {
                    $errors = $post->errors();
                    log::write(Kohana::lang('o_manage.form_error'),$errors,__FILE__,__LINE__);
                }
                $data['args'] = serialize($post->as_array());
                if($payment->edit($data))
                {
                    remind::set(Kohana::lang('o_global.update_success'),'manage/payment','success');
                }
                else
                {
                    remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
                }
                break;
            case '10':
                $post->pre_filter('trim');
                $post->add_rules('partner','required');
                $post->add_rules('key','required');
                $post->add_rules('seller_email','required');
                if (!($post->validate()))
                {
                    $errors = $post->errors();
                    log::write('form_error',$errors,__FILE__,__LINE__);
                }
                $data['args'] = serialize($post->as_array());
                if($payment->edit($data))
                {
                    remind::set(Kohana::lang('o_global.update_success'),'manage/payment','success');
                }
                else
                {
                    remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
                }
                break;   
        }
    }

    /**
     * 添加新支付
     */
    function add() {
        //权限验证
        role::check('manage_payment_add');

        $this->template->content = new View("manage/payment_add");
        //支付类型列表
        $this->template->content->payment_types            = Mypayment_type::instance()->payment_types();
        $this->template->content->payment_type_list        = Mypayment_type::instance()->select_list();
    }

    /**
     * 添加新支付
     */
    function do_add($payment_type_id) {
        role::check('manage_payment');
        
        $data = array();
        $data['manager_id']            = role::root_manager_id();
        $data['payment_type_id']    = $payment_type_id;
        $data['account']            = $this->input->post('account')?$this->input->post('account'):'';

        $payment = Mypayment::instance();
        if($payment->payment_exist($data))
        {
            remind::set(Kohana::lang('o_manage.you_have_this_payment'),'manage/payment/add','error');
        }
        switch($payment_type_id)
        {
            case '1':
            case '2':
            case '3':
            case '4':
                if($payment->add($data))
                {
                    remind::set(Kohana::lang('o_global.add_success'),'manage/payment','success');
                }
                else
                {
                    remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
                }
                break;
            case '5':
            case '6':
                $post = new Validation($_POST);
                $post->pre_filter('trim');
                $post->add_rules('jump_url','required','length[1,200]');
                $post->add_rules('passwd','required','standard_text');
                $post->add_rules('signature','required','standard_text');
                $post->add_rules('version','required','standard_text');
                if (!($post->validate()))
                {
                    $errors = $post->errors();
                    log::write('form_error',$errors,__FILE__,__LINE__);
                }
                $data['args'] = serialize($post->as_array());
                if($payment->add($data))
                {
                    remind::set(Kohana::lang('o_global.add_success'),'manage/payment','success');
                }
                else
                {
                    remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
                }
                break;
            case '8':
            case '9':
                $post = new Validation($_POST);
                $post->pre_filter('trim');
                $post->add_rules('terminalid','required');
                $post->add_rules('key','required');
                if (!($post->validate()))
                {
                    $errors = $post->errors();
                    log::write('form_error',$errors,__FILE__,__LINE__);
                }
                $data['args'] = serialize($post->as_array());
                if($payment->add($data))
                {
                    remind::set(Kohana::lang('o_global.add_success'),'manage/payment','success');
                }
                else
                {
                    remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
                }
                break;
            case '10':
                $post = new Validation($_POST);
                $post->pre_filter('trim');
                $post->add_rules('partner','required');
                $post->add_rules('key','required');
                $post->add_rules('seller_email','required');
                if (!($post->validate()))
                {
                    $errors = $post->errors();
                    log::write('form_error',$errors,__FILE__,__LINE__);
                }
                $data['args'] = serialize($post->as_array());
                if($payment->add($data))
                {
                    remind::set(Kohana::lang('o_global.add_success'),'manage/payment','success');
                }
                else
                {
                    remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
                }
                break;                
            default :
                if($payment->add($data))
                {
                    remind::set(Kohana::lang('o_global.add_success'),'manage/payment','success');
                }
                else
                {
                    remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
                }
                break;
        }
    }

    /**
     * 建站流程中的支付添加
     */
    function flow_add() {
        //权限验证
        $site_id_list = role::check('manage_payment');

        $this->template->content = new View("manage/payment_flow_add");
        //支付类型列表
        $this->template->content->payment_types            = Mypayment_type::instance()->payment_types();
        $this->template->content->payment_type_list        = Mypayment_type::instance()->select_list();
    }

    /**
     * 建站流程中的添加新支付
     */
    function do_flow_add() {
        //权限验证
        //$this->profiler = new Profiler;
        $site_id_list = role::check('manage_payment');

        $payment_type_ids            = $_POST['payment_type_id'];

        foreach((array)$payment_type_ids as $key=>$payment_type_id)
        {
            $data = array();
            $data['manager_id']            = role::root_manager_id();
            $data['payment_type_id']    = $payment_type_id;
            $data['account']            = $this->input->post('account')?$this->input->post('account'):'';

            switch($payment_type_id)
            {
                case '1':
                case '2':
                    $data['account'] = '';
                    $payment = Mypayment::instance();
                    if($payment->payment_exist($data))
                    {
                        remind::set(Kohana::lang('o_global.you_have_this_payment'),'','error');
                        break;
                    }
                    if($payment->add($data))
                    {

                        remind::set(Kohana::lang('o_global.add_success'),'','success');
                    }
                    else
                    {
                        remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
                    }
                    break;
                case '3':
                case '4':
                    $payment = Mypayment::instance();
                    if($payment->payment_exist($data))
                    {
                        remind::set(Kohana::lang('o_global.you_have_this_payment'),'','error');
                        break;
                    }
                    if($payment->add($data))
                    {

                        remind::set(Kohana::lang('o_global.add_success'),'','success');
                    }
                    else
                    {
                        remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
                    }
                    break;
            }
        }
        url::redirect('manage/site_payment');
    }
    
    /**
     * 设定菜单的排序
     */
   public function set_order()
   {
        //初始化返回数组
        $return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
        );
       $request_data = $this->input->get();
       $id = isset($request_data['id']) ?  $request_data['id'] : '';
       $order = isset($request_data['order']) ?  $request_data['order'] : '';

       if(empty($id) || (empty($order) && $order != 0))
       {
           $return_struct['msg'] = Kohana::lang('o_global.bad_request');
           exit(json_encode($return_struct));
       }
       if(!is_numeric($order) || $order<0){
           $return_struct['msg'] = Kohana::lang('o_global.position_rule');
           exit(json_encode($return_struct));
       }
       if(Mypayment::instance()->set_order($id,$order)){
            $return_struct = array(
                'status'        => 1,
                'code'          => 200,
                'msg'           => Kohana::lang('o_global.position_success'),
                'content'       => array('order'=>$order),
            );
       } else {
            $return_struct['msg'] = Kohana::lang('o_global.position_error');
       }
       exit(json_encode($return_struct));
    }
    
    /**
     * 批量删除支付
     */
    public function batch_delete()
    {
        role::check('manage_payment');
        //初始化返回数据
        $return_data = array();
        //请求结构体
        $request_data = array();
        
        try {
            $payment_ids = $this->input->post('payment_ids');
            
            if(is_array($payment_ids) && count($payment_ids) > 0)
            { 
                /* 删除失败的 */
                $failed_payment_names = '';
                /* 执行操作 */
                foreach($payment_ids as $payment_id)
                {
                    if(!Mypayment::instance($payment_id)->delete())
                    {
                        $failed_payment_names .= ' | ' . $payment_id;
                    }
                }
                if(empty($failed_payment_names))
                {
                    throw new MyRuntimeException(Kohana::lang('o_manage.delete_payment_success'),403);
                }
                else
                {
                    /* 中转提示页面的停留时间 */
                    $return_struct['action']['time'] = 10;
                    $failed_payment_names = trim($failed_payment_names,' | ');
                    throw new MyRuntimeException(Kohana::lang('o_manage.delete_payment_error',$failed_payment_names),403);
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
