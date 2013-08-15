<?php defined('SYSPATH') OR die('No direct access allowed.');

class Delivery_Controller extends Template_Controller{

    public function __construct()
    {
        parent::__construct();
        role::check('site_carrier');    
    }

    public function index()
    {      
        $request_data = $this->input->get();
        $delivery_service = DeliveryService::get_instance(); 
        
        //初始化请求结构体
        $query_struct = array (
            'where' => array (), 
            'like' => array (), 
            'orderby' => array (
                'id'         => 'DESC' 
            ), 
            'limit' => array (
                'per_page'   =>20,
                'offset'     =>0
            )
        );

        //列表排序
        $orderby_arr= array
        (
            0   => array('id'=>'DESC'),
            1   => array('id'=>'ASC'),
            2   => array('url'=>'ASC'),
            3   => array('url'=>'DESC'),
            4   => array('name'=>'ASC'),
            5   => array('name'=>'DESC'),
            6   => array('position'=>'ASC'),
            7   => array('position'=>'DESC'),
            8   => array('delay'=>'ASC'),
            9   => array('delay'=>'DESC'),
            10  => array('active'=>'ASC'),
            11  => array('active'=>'DESC'),
            12  => array('type'=>'ASC'),
            13  => array('type'=>'DESC'),
            14  => array('is_default'=>'ASC'),
            15  => array('is_default'=>'DESC')
        );

        $orderby    = controller_tool::orderby($orderby_arr);
        if(isset($orderby) && !empty($orderby)){
            $query_struct['orderby'] = $orderby;
        }
        
        // 每页条目数
        controller_tool::request_per_page($query_struct,$request_data);
        
        $count = $delivery_service->query_count($query_struct);

        // 模板输出 分页
        $this->pagination       = new Pagination(array(
            'total_items'    => $count,
            'items_per_page' => $query_struct['limit']['per_page'],
        ));
        
        $query_struct['limit']['offset']      = $this->pagination->sql_offset;
        $query_struct['limit']['page'] = $this->pagination->current_page;

        //调用列表
        $deliveries = $delivery_service->query_assoc($query_struct);
        $this->template->content = new View("site/delivery_list");
        $this->template->content->deliveries = $deliveries;
    }

    /**
     * 添加新的物流方式
     */
    function add() 
    {    
        $this->template->content = new View("site/delivery_add");
        $this->template->content->unit_list        = Kohana::config('delivery.unit');
    }
    
    /**
     * 提交物流
     */
    function do_add()
    {        
        $request_data = $this->input->post();
        //流程
        $submit_target = intval($this->input->post('submit_target'));

        if($_POST)
        {                        
            //数据验证
            $delivery_service = DeliveryService::get_instance();
            $validation = Validation::factory($request_data)
                ->pre_filter('trim')
                ->add_rules('name',               'required',   'length[0,100]')
                ->add_rules('url',                'required',   'length[0,200]')
                ->add_rules('first_unit',         'required',   'numeric')
                ->add_rules('continue_unit',      'required',   'numeric')
                ->add_rules('type',               'required',   'digit')
                ->add_rules('use_exp',            'required',   'digit')
                ->add_rules('position',           'required',   'numeric')
                ->add_rules('active',             'required',   'digit');
                
            //检测物流名称是否存在
            if($delivery_service->name_is_exist($request_data['name']))
            {
                remind::set(Kohana::lang('o_site.carrier_has_exist'),request::referrer(),'error');
            }

            $is_default = isset($request_data['is_default']) ? $request_data['is_default'] : 0;
            $set_data = array(
                'name'                 => $request_data['name'],
                'url'                  => $request_data['url'],
                'first_unit'           => $request_data['first_unit'],
                'continue_unit'        => $request_data['continue_unit'],
                'type'                 => $request_data['type'],
                'is_default'           => $is_default,
                'use_exp'              => $request_data['use_exp'],
                'expression'           => $request_data['expression'],
                'position'             => $request_data['position'],
                'delay'                => $request_data['delay'],
                'active'               => $request_data['active']
            );
            //处理统一设置或启用默认费用
            if($request_data['use_exp'] == 1)
            {
                $validation->add_rules('expression',      'required');
            }
            else
            {
                if(isset($request_data['is_default']) && $request_data['is_default'] == 1)
                {
                    $validation->add_rules('first_price',      'required',   'numeric');
                    $validation->add_rules('continue_price',   'required',   'numeric');    
                    $set_data['first_price'] = $request_data['first_price'];
                    $set_data['continue_price'] = $request_data['continue_price'];
                    $set_data['expression'] = delivery::create_exp($request_data['first_unit'], $request_data['continue_unit'], $request_data['first_price'], $request_data['continue_price']);                
                }
                else
                {
                    $set_data['expression'] = delivery::create_exp($request_data['first_unit'], $request_data['continue_unit']);
                }
            }
            if (!$validation->validate())
            {
                remind::set(Kohana::lang('o_global.input_error'),request::referrer(), 'error');
            }
            
            if($delivery['id'] = $delivery_service->add($set_data)) 
            {                
                //处理支持的配送国家
                if($request_data['type'] == 1)
                {                                                                                                                                                                                                                                                                                                            }
                    if (!empty($request_data['des_indexs']) AND is_array($request_data['des_indexs']))
                    {
                        $delivery_country_service = Delivery_countryService::get_instance();
                        foreach ($request_data['des_indexs'] as $i => $index) 
                        {
                            // 验证国家数据
                            $validation = Validation::factory($request_data)
                                ->pre_filter('trim')
                                ->add_rules('country_names_'.$index,    'required')
                                ->add_rules('countries_use_exp_'.$index,'required', 'digit')
                                ->add_rules('country_ids_'.$index,      'required');
        
                            // 生成 支配国家数据
                            $country_data = array(
                                'delivery_id'        => $delivery['id'],
                                'first_price'        => $request_data['first_price_'.$index],
                                'continue_price'     => $request_data['continue_price_'.$index],
                                'use_exp'            => $request_data['countries_use_exp_'.$index],
                                'expression'         => $request_data['expression_'.$index]
                            );
                            
                            $country_ids = explode('-', $request_data['country_ids_'.$index]);
                            $country_names = explode(',', $request_data['country_names_'.$index]);
                            //判断提交的国家id个数是否于国家名称的个数一致
                            if(empty($country_ids))
                            {
                                remind::set(Kohana::lang('o_global.bad_request'),request::referrer(), 'error');
                                break;
                            }
                            //使用公式
                            if ($request_data['countries_use_exp_'.$index] == 1) 
                            {
                                $validation->add_rules('expression_'.$index,      'required');
                            }
                            else
                            {
                                $validation->add_rules('first_price_'.$index,      'required',   'numeric');
                                $validation->add_rules('continue_price_'.$index,   'required',   'numeric');
                                $country_data['expression'] = delivery::create_exp($request_data['first_unit'], $request_data['continue_unit'], $request_data['first_price_'.$index], $request_data['continue_price_'.$index]);
                            }
                        
                            $config['country_ids'] = $request_data['country_ids_'.$index];
                            $country_data['position'] = $index - 1;
                            foreach($country_ids as $i)
                            {
                                $country_data['country_id'] = $i;
                                if(!$delivery_country_service->add($country_data))
                                {
                                    remind::set(Kohana::lang('o_global.add_error'),'site/delivery/add');
                                    break;
                                }
                            }
                        }
                    }
                
                //判断走向
                switch($submit_target)
                {
                    case 1:
                        remind::set(Kohana::lang('o_global.add_success'),'site/delivery/add','success');
                    default:
                        remind::set(Kohana::lang('o_global.add_success'),'site/delivery/','success');
                }
            }
            else
            {
                remind::set(Kohana::lang('o_global.add_error'),'site/delivery/add');
            }      
        }
        else
        {
            remind::set(Kohana::lang('o_global.add_error'),'site/delivery/add');
        }
    }
    
    /**
     * 修改物流信息
     */
    function edit($id) 
    { 
        if(!$id)
        {
            remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
        }
        $delivery_service = DeliveryService::get_instance();
        //验证此条物流
        $data = $delivery_service->get($id);
        if(!$data['id'])
        {
            remind::set(Kohana::lang('o_global.access_denied'), request::referrer(), 'error');
        }        
        if($data['use_exp'] == 1)
        {
            $data['first_price'] = $data['first_price'] == 0 ? '' : $data['first_price'];
            $data['continue_price'] = $data['continue_price'] == 0 ? '' : $data['continue_price'];
        }
               
        //检索指定的国家
        $country_areas = array(); 
        $delivery_country_service = Delivery_countryService::get_instance();
        if($data['type'] == 1)
        {
            $delivery_countrys = $delivery_country_service->get_delivery_countries_by_id($data['id']);

            foreach($delivery_countrys as $key=>$item)
            {
                if(!is_array($item) || count($item) < 0)
                {
                    remind::set(Kohana::lang('o_global.access_denied'), request::referrer(), 'error');
                    break;
                }
                $ids = '';
                $names = '';
                $country_ids = '';
                $disable_names = '';
                $edit_id = array();
                $country_id = array();
                $country_names = array();
                $country_disable = array();

                foreach($item as $k=>$v)
                {
                    if(Mycountry::instance($v['country_id'])->get('active') == 0)
                    {
                        $country_disable[] = Mycountry::instance($v['country_id'])->get('name');
                    }
                    $country_names[$v['country_id']] = Mycountry::instance($v['country_id'])->get('name');
                    $first_price = $v['first_price'];
                    $continue_price = $v['continue_price'];
                    $expression = $v['expression'];
                    $use_exp = $v['use_exp'];                    
                    $country_id[] = $v['country_id'];
                    $edit_id[] = $v['id'];
                }
                ksort($country_names);                               
                $names = implode(',', $country_names);
                $disable_names = implode(',', $country_disable);
                $country_ids = implode('-', $country_id);
                $ids = implode('-', $edit_id);
                $country_areas[$key]['first_price'] = $first_price;
                $country_areas[$key]['continue_price'] = $continue_price;
                $country_areas[$key]['expression'] = $expression;
                $country_areas[$key]['use_exp'] = $use_exp;
                $country_areas[$key]['country_names'] = $names;
                $country_areas[$key]['ids'] = $ids;
                $country_areas[$key]['country_ids'] = $country_ids;
                $country_areas[$key]['disable_names'] = $disable_names;
            }    
        }

        $this->template->content = new View("site/delivery_edit");
        $this->template->content->data        = $data;
        $this->template->content->country_area    = $country_areas;
        $this->template->content->unit_list        = Kohana::config('delivery.unit');    
    }
    
    /**
     * 提交编辑的物流
     */
    function do_edit()
    {        
        $request_data = $this->input->post();
        //流程
        $submit_target = intval($this->input->post('submit_target'));
        if($_POST)
        {                        
            $delivery_service = DeliveryService::get_instance();
            $delivery_country_service = Delivery_countryService::get_instance();
            //数据验证
            $validation = Validation::factory($request_data)
                ->pre_filter('trim')
                ->add_rules('delivery_id',        'required',   'digit')
                ->add_rules('name',               'required',   'length[0,100]')
                ->add_rules('url',                'required',   'length[0,200]')
                ->add_rules('first_unit',         'required',   'numeric')
                ->add_rules('continue_unit',      'required',   'numeric')
                ->add_rules('use_exp',            'required',   'digit')
                ->add_rules('type',               'required',   'digit')
                ->add_rules('position',           'required',   'numeric')
                ->add_rules('active',             'required',   'digit');

            //验证物流表的数据
            $delivery = $delivery_service->get($request_data['delivery_id']);
            
            if(empty($delivery) || !isset($delivery))
            {
                remind::set(Kohana::lang('o_global.bad_request'),request::referrer(),'error');
            }
                        
            //验证物流国家表的数据
            if(!empty($delivery['type']) && ($delivery['type'] == 1))
            {
                $delivery_countries = $delivery_country_service->get_delivery_countries_by_id($delivery['id']);

                if(empty($delivery_countries) || !isset($delivery_countries)){
                    remind::set(Kohana::lang('o_global.bad_request'),request::referrer(),'error');
                }
                //验证物流国家表里面的数据是否与物流表的数据统一
                $old_country_ids = array();
                foreach($delivery_countries as $item)
                {
                    if(!is_array($item) || count($item) < 0)
                    {
                        remind::set(Kohana::lang('o_global.access_denied'), request::referrer(), 'error');
                        break;
                    }
                    foreach($item as $k=>$v)
                    {
                        if($v['delivery_id'] != $delivery['id'])
                        {
                            remind::set(Kohana::lang('o_global.bad_request'),request::referrer(),'error');
                        }
                        
                        //保存已存在的物流国家的信息
                        $old_country_ids[$v['country_id']] = $v['id'];
                    }
                }
            }
            
            //检测物流名称是否存在
            if($delivery_service->name_is_exist($request_data['name'],$request_data['delivery_id']))
            {
                remind::set(Kohana::lang('o_site.carrier_has_exist'),request::referrer(),'error');
            }
            $is_default = isset($request_data['is_default']) ? $request_data['is_default'] : 0;
            $set_data = array(
                'name'                 => $request_data['name'],
                'url'                  => $request_data['url'],
                'first_unit'           => $request_data['first_unit'],
                'continue_unit'        => $request_data['continue_unit'],
                'type'                 => $request_data['type'],
                'is_default'           => $is_default,
                'use_exp'              => $request_data['use_exp'],
                'expression'           => $request_data['expression'],
                'position'             => $request_data['position'],
                'delay'                => $request_data['delay'],
                'active'               => $request_data['active']
            );
            
            //处理统一设置或启用默认费用
            if($request_data['use_exp'] == 1)
            {
                $validation->add_rules('expression',      'required');
            }
            else
            {
                if(isset($request_data['is_default']) && $request_data['is_default'] == 1)
                {
                    $validation->add_rules('first_price',      'required',   'numeric');
                    $validation->add_rules('continue_price',   'required',   'numeric');    
                    $set_data['first_price'] = $request_data['first_price'];
                    $set_data['continue_price'] = $request_data['continue_price'];
                    $set_data['expression'] = delivery::create_exp($request_data['first_unit'], $request_data['continue_unit'], $request_data['first_price'], $request_data['continue_price']);                
                }
                else
                {
                    $set_data['expression'] = delivery::create_exp($request_data['first_unit'], $request_data['continue_unit']);
                }                
            }
            if(!$validation->validate())
            {
                remind::set(Kohana::lang('o_global.input_error'),request::referrer(), 'error');
            }
            
            //更新物流表的信息
            $delivery_service->set($delivery['id'], $set_data);
            
            //处理地区费用类型改变的情况
            if($delivery['type'] == 1) 
            {                
                //处理支持的配送国家的情况
                if($request_data['type'] == 1)
                {
                    if (!empty($request_data['des_indexs']) AND is_array($request_data['des_indexs']))
                    {
                        $whole_country_ids = array();
                        foreach ($request_data['des_indexs'] as $i => $index) 
                        {
                            // 验证国家数据
                            $validation = Validation::factory($request_data)
                                ->pre_filter('trim')
                                ->add_rules('country_names_'.$index,    'required')
                                ->add_rules('countries_use_exp_'.$index,'required', 'digit')
                                ->add_rules('country_ids_'.$index,      'required');
        
                            // 生成 支配的国家数据
                            $country_data = array(
                                'delivery_id'        => $delivery['id'],
                                'first_price'        => $request_data['first_price_'.$index],
                                'continue_price'     => $request_data['continue_price_'.$index],
                                'use_exp'            => $request_data['countries_use_exp_'.$index],
                                'expression'         => $request_data['expression_'.$index]
                            );
                            
                            $country_ids = explode('-', $request_data['country_ids_'.$index]);
                            $country_names = explode(',', $request_data['country_names_'.$index]);
                        
                            //判断提交的国家id个数是否于国家名称的个数一致
                            if(empty($country_ids))
                            {
                                remind::set(Kohana::lang('o_global.bad_request'),request::referrer(), 'error');
                            }
                            //使用公式
                            if ($request_data['countries_use_exp_'.$index] == 1) 
                            {
                                $validation->add_rules('expression_'.$index,      'required');
                            }
                            else
                            {
                                $validation->add_rules('first_price_'.$index,      'required',   'numeric');
                                $validation->add_rules('continue_price_'.$index,   'required',   'numeric');
                                $country_data['expression'] = delivery::create_exp($request_data['first_unit'], $request_data['continue_unit'], $request_data['first_price_'.$index], $request_data['continue_price_'.$index]);
                            }
                                        
                            $country_data['position'] = $index - 1;                        
                            
                            $ids_index = 'ids_'.$index;
                            $de_countries[$index] = $country_data;
                            $de_countries[$index]['ids'] = array();
                            if (!empty($request_data[$ids_index])) 
                            {
                                $de_countries[$index]['ids'] = $request_data[$ids_index];
                            }                        
                            
                            $countries_ids[$index] = $request_data['country_ids_'.$index];
                            $whole_country_ids = array_merge($country_ids, $whole_country_ids);
                        }
                        
                        foreach ($countries_ids as $i=>$v) 
                        {
                            $country_ids = explode('-', $v);
                            foreach($country_ids as $value)
                            {
                                $de_countries[$i]['country_id'] = $value;
                                if(!isset($old_country_ids[$value]))
                                {    
                                    $delivery_country_service->add($de_countries[$i]);
                                }
                                else
                                {
                                    $delivery_country_service->set($old_country_ids[$value], $de_countries[$i]);
                                }
                            }
                        }
                        
                        foreach($old_country_ids as $key=>$id)
                        {
                            if(!in_array($key, $whole_country_ids))
                            {
                                $delivery_country_service->remove($id);
                            }
                        }
                    }                
                }
                else
                {
                    $delivery_country_service->delete_delivery_countries_by_delivery_id($delivery['id']);
                }
            }
            else
            {
                //处理支持的配送国家
                if($request_data['type'] == 1)
                {
                    if (!empty($request_data['des_indexs']) AND is_array($request_data['des_indexs']))
                    {
                        foreach ($request_data['des_indexs'] as $i => $index) 
                        {
                            // 验证国家数据
                            $validation = Validation::factory($request_data)
                                ->pre_filter('trim')
                                ->add_rules('country_names_'.$index,    'required', 'length[0,512]')
                                ->add_rules('countries_use_exp_'.$index,'required', 'digit')
                                ->add_rules('country_ids_'.$index,      'required', 'length[0,256]');
        
                            // 生成 支配国家数据
                            $country_data = array(
                                'delivery_id'        => $delivery['id'],
                                'first_price'        => $request_data['first_price_'.$index],
                                'continue_price'     => $request_data['continue_price_'.$index],
                                'use_exp'            => $request_data['countries_use_exp_'.$index],
                                'expression'         => $request_data['expression_'.$index]
                            );
                            
                            $country_ids = explode('-', $request_data['country_ids_'.$index]);
                            $country_names = explode(',', $request_data['country_names_'.$index]);
                        
                            //判断提交的国家id个数是否于国家名称的个数一致
                            if(empty($country_ids))
                            {
                                remind::set(Kohana::lang('o_global.bad_request'),request::referrer(), 'error');
                            }
                            //使用公式
                            if ($request_data['countries_use_exp_'.$index] == 1) 
                            {
                                $validation->add_rules('expression_'.$index,      'required');
                            }
                            else
                            {
                                $validation->add_rules('first_price_'.$index,      'required',   'numeric');
                                $validation->add_rules('continue_price_'.$index,   'required',   'numeric');
                                $country_data['expression'] = delivery::create_exp($request_data['first_unit'], $request_data['continue_unit'], $request_data['first_price_'.$index], $request_data['continue_price_'.$index]);
                            }

                            $country_data['position'] = $index - 1;
                            foreach($country_ids as $i)
                            {
                                $country_data['country_id'] = $i;
                                if(!$delivery_country_service->add($country_data))
                                {
                                    remind::set(Kohana::lang('o_global.add_error'),'site/delivery/add');
                                    break;
                                }
                            }
                        }
                    }
                }
            }
            //判断走向
            switch($submit_target)
            {
                case 1:
                    remind::set(Kohana::lang('o_global.update_success'),'site/delivery/edit/'.$delivery['id'],'success');
                default:
                    remind::set(Kohana::lang('o_global.update_success'),'site/delivery/','success');
            }      
        }
        else
        {
            remind::set(Kohana::lang('o_global.update_error'),'site/delivery');
        }
    }

    /**
     * 删除物流信息
     */
    function do_delete($id) 
    {
        if(!$id)
        {
            remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
        }
        $delivery_service = DeliveryService::get_instance();
        if($delivery_service->delete_by_id($id))
        {
            remind::set(Kohana::lang('o_global.delete_success'),request::referrer(),'success');
        }
        else
        {
            remind::set(Kohana::lang('o_global.delete_error'),request::referrer(),'error');
        }
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

       $validation = Validation::factory($request_data)
                ->pre_filter('trim')
                ->add_rules('order',            'required',   'digit');
       if (!$validation->validate())
       {
            $return_struct['msg'] = Kohana::lang('o_global.position_rule');
            exit(json_encode($return_struct));
       }
       if(empty($id) || (empty($order) && $order != 0))
       {
           $return_struct['msg'] = Kohana::lang('o_global.bad_request');
           exit(json_encode($return_struct));
       }
       if(!is_numeric($order) || $order < 0)
       {
           $return_struct['msg'] = Kohana::lang('o_global.position_rule');
           exit(json_encode($return_struct));
       }
       if(DeliveryService::get_instance()->set_order($id,$order))
       {
            $return_struct = array(
                'status'        => 1,
                'code'          => 200,
                'msg'           => Kohana::lang('o_global.position_success'),
                'content'       => array('order'=>$order),
            );
       } 
       else 
       {
            $return_struct['msg'] = Kohana::lang('o_global.position_error');
       }
       exit(json_encode($return_struct));
    }
    
    /**
     * 批量删除物流
     */
    public function batch_delete()
    {        
        $delivery_ids = $this->input->post('delivery_ids');
            
        if(is_array($delivery_ids) && count($delivery_ids) > 0)
        {
            $delivery_service = DeliveryService::get_instance();
                
            /* 删除失败的物流 */
            $failed_delivery_names = '';
            
            /* 执行操作 */
            foreach($delivery_ids as $delivery_id)
            {
                if(!$delivery_service->delete_by_id($delivery_id))
                {
                    $failed_delivery_names .= ' | ' . $delivery_id;
                }
            }
            if(empty($failed_delivery_names))
            {
                remind::set(Kohana::lang('o_site.delete_carrier_success'), 'site/delivery', 'success');
            }
            else
            {
                $failed_delivery_names = trim($failed_delivery_names,' | ');
                remind::set(Kohana::lang('o_site.delete_carrier_error').$failed_delivery_names, 'site/delivery');
            }
        }
        else
        {
             remind::set(Kohana::lang('o_site.delete_carrier_error'), 'site/delivery');
        }
    }
    
     /**
     * 选择国家
     */    
    function sel_country()
    {
        $request_data = $this->input->get();
        
        $where_view      = array();
        //初始化请求结构体
        $query_struct = array (
            'where'   => array (
                'active'    => 1
            ), 
            'like'    => array (), 
            'orderby' => array (
                'id'        => 'DESC' 
            ), 
            'limit'   => array (
                'per_page'  =>20000,
                'offset'    =>0,
            )
        );

        // 搜索功能 
        $search_arr      = array('name');
        $search_value    = $this->input->get('search_value');
        $search_type     = $this->input->get('search_type');
        
        if($search_arr)
        {
            foreach($search_arr as $value)
            {
                if($search_type == $value && strlen($search_value) > 0)
                {
                    $query_struct['like'][$value] = $search_value;
                    //$query_struct['where'][$value] = $search_value;
                }
            }
            $where_view['search_type']      = $search_type;
            $where_view['search_value']   = $search_value;
        }        

        $whole_count = Mycountry::instance()->query_count($query_struct);
        if(isset($request_data['country_ids']) && !empty($request_data['country_ids']))
        {
            $query_country_ids = explode('-', $request_data['country_ids']);
            $country_ids = implode(',', $query_country_ids);
            $query_struct['not_in']['id'] = $country_ids;
        }    
        $count = Mycountry::instance()->query_count($query_struct);
        $left_count = $whole_count - $count;

        // 模板输出 分页
        $this->pagination       = new Pagination(array(
            'total_items'    => $count,
            'items_per_page' => $query_struct['limit']['per_page'],
        ));
        
        $query_struct['limit']['offset']      = $this->pagination->sql_offset;
        $query_struct['limit']['page'] = $this->pagination->current_page;

        //调用列表
        $this->template = new View('layout/commonfix_html');
        $this->template->content = new View("site/delivery_country_list");
        $this->template->content->countries    = Mycountry::instance()->query_assoc($query_struct);
        $this->template->content->where_view = $where_view;
        $this->template->content->whole_count = $whole_count;
        $this->template->content->left_count = $left_count;
        if(isset($request_data['country_ids']) && !empty($request_data['country_ids']))
        {
            $this->template->content->country_ids = $request_data['country_ids'];
        }       
    }
    
    /*
     * 获取国家的名称
     */
    function get_country()
    {
        $return_struct = array();
        $countrys = array();
        // 修改返回状态数据
        $return_struct['status']    = 1;
        $return_struct['code']      = 200;
        $return_struct['msg']       = 'ok';
        $request_data = $this->input->get();
        $query_country_ids = explode('-', $request_data['country_ids']);
        if(!empty($query_country_ids))
        {
            foreach($query_country_ids as $id)
            {
                $countrys[] = Mycountry::instance($id)->get('name');
            }
        }
        if(empty($countrys))
        {
            $return_struct['status']    = 0;
            $return_struct['code']      = 501;
            $return_struct['msg']       = 'error';
        }
        $countrys_str = implode(',', $countrys);
        $return_struct['content'] = $countrys_str;
        header('Content-Type: text/javascript; charset=UTF-8');
        exit(json_encode($return_struct));
    }
    
    /*
     * 手动删除国家的物流信息
     */
    function del_country()
    {
        $return_struct = array();
        $request_data = $this->input->get();
        // 修改返回状态数据
        $return_struct['status']    = 1;
        $return_struct['code']      = 200;
        $return_struct['msg']       = 'ok';        
        $delivery_service = DeliveryService::get_instance();
        $delivery_country_service = Delivery_countryService::get_instance();
        if (empty($request_data['delivery_id']) OR empty($request_data['ids'])) 
        {
            remind::set(Kohana::lang('o_global.access_denied'), request::referrer(), 'error');
        }
        $delivery = $delivery_service->get($request_data['delivery_id']);
        if($delivery['site_id'] != $this->site_id)
        {
            remind::set(Kohana::lang('o_global.access_denied'), request::referrer(), 'error');
        }
        
        $query_ids = explode('-', $request_data['ids']);
        foreach($query_ids as $ids)
        {
            $delivery_countries = $delivery_country_service->get($ids);
            if($delivery_countries['delivery_id'] != $delivery['id'])
            {
                remind::set(Kohana::lang('o_global.access_denied'), request::referrer(), 'error');
                break;
            }
            if(!$delivery_country_service->remove($ids))
            {
                $return_struct['status']    = 0;
                $return_struct['code']      = 501;
                break;
            }
        }
        header('Content-Type: text/javascript; charset=UTF-8');
        exit(json_encode($return_struct));
    }
}
