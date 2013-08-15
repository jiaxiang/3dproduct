<?php defined('SYSPATH') OR die('No direct access allowed.');

class Deliverycn_Controller extends Template_Controller{

    public function __construct()
    {
        parent::__construct();
        role::check('site_carrier');    
    }

    public function index()
    {      
        $request_data = $this->input->get();
        $deliverycn_service = DeliverycnService::get_instance(); 
        
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
        
        $count = $deliverycn_service->query_count($query_struct);

        // 模板输出 分页
        $this->pagination       = new Pagination(array(
            'total_items'    => $count,
            'items_per_page' => $query_struct['limit']['per_page'],
        ));
        
        $query_struct['limit']['offset']      = $this->pagination->sql_offset;
        $query_struct['limit']['page'] = $this->pagination->current_page;

        //调用列表
        $deliveries = $deliverycn_service->query_assoc($query_struct);
        $this->template->content = new View("site/deliverycn/index");
        $this->template->content->deliveries = $deliveries;
    }

    /**
     * 添加新的物流方式
     */
    function add() 
    {    
        $this->template->content = new View("site/deliverycn/add");
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
            $deliverycn_service = DeliverycnService::get_instance();
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
            if($deliverycn_service->name_is_exist($request_data['name']))
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
            
            if($deliverycn['id'] = $deliverycn_service->add($set_data)) 
            {                
                //处理支持的配送地区
                if($request_data['type'] == 1)
                {                                                                                                                                                                                                                                                                                                            }
                    if (!empty($request_data['des_indexs']) AND is_array($request_data['des_indexs']))
                    {
                        $deliverycn_region_service = Deliverycn_regionService::get_instance();
                        foreach ($request_data['des_indexs'] as $i => $index) 
                        {
                            // 验证国家数据
                            $validation = Validation::factory($request_data)
                                ->pre_filter('trim')
                                ->add_rules('region_names_'.$index,    'required')
                                ->add_rules('regions_use_exp_'.$index,'required', 'digit')
                                ->add_rules('region_ids_'.$index,      'required');
        
                            // 生成 支配国家数据
                            $region_data = array(
                                'deliverycn_id'      => $deliverycn['id'],
                                'first_price'        => $request_data['first_price_'.$index],
                                'continue_price'     => $request_data['continue_price_'.$index],
                                'use_exp'            => $request_data['regions_use_exp_'.$index],
                                'expression'         => $request_data['expression_'.$index]
                            );
                            
                            $region_ids = explode('-', $request_data['region_ids_'.$index]);
                            $region_names = explode(',', $request_data['region_names_'.$index]);
                            //判断提交的国家id个数是否于国家名称的个数一致
                            if(empty($region_ids))
                            {
                                remind::set(Kohana::lang('o_global.bad_request'),request::referrer(), 'error');
                                break;
                            }
                            //使用公式
                            if ($request_data['regions_use_exp_'.$index] == 1) 
                            {
                                $validation->add_rules('expression_'.$index,      'required');
                            }
                            else
                            {
                                $validation->add_rules('first_price_'.$index,      'required',   'numeric');
                                $validation->add_rules('continue_price_'.$index,   'required',   'numeric');
                                $country_data['expression'] = delivery::create_exp($request_data['first_unit'], $request_data['continue_unit'], $request_data['first_price_'.$index], $request_data['continue_price_'.$index]);
                            }
                        
                            $config['region_ids'] = $request_data['region_ids_'.$index];
                            $region_data['position'] = $index - 1;
                            foreach($region_ids as $i)
                            {
                                $region_data['region_id'] = $i;
                                if(!$deliverycn_region_service->add($region_data))
                                {
                                    remind::set(Kohana::lang('o_global.add_error'),'site/deliverycn/add');
                                    break;
                                }
                            }
                        }
                    }
                
                //判断走向
                switch($submit_target)
                {
                    case 1:
                        remind::set(Kohana::lang('o_global.add_success'),'site/deliverycn/add','success');
                    default:
                        remind::set(Kohana::lang('o_global.add_success'),'site/deliverycn/','success');
                }
            }
            else
            {
                remind::set(Kohana::lang('o_global.add_error'),'site/deliverycn/add');
            }      
        }
        else
        {
            remind::set(Kohana::lang('o_global.add_error'),'site/deliverycn/add');
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
        $deliverycn_service = DeliverycnService::get_instance();
        //验证此条物流
        $data = $deliverycn_service->get($id);
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
        $region_areas = array(); 
        $deliverycn_region_service = Deliverycn_regionService::get_instance();
        if($data['type'] == 1)
        {
            $deliverycn_regions = $deliverycn_region_service->get_delivery_regions_by_id($data['id']);

            foreach($deliverycn_regions as $key=>$item)
            {
                if(!is_array($item) || count($item) < 0)
                {
                    remind::set(Kohana::lang('o_global.access_denied'), request::referrer(), 'error');
                    break;
                }
                $ids = '';
                $names = '';
                $region_ids = '';
                $disable_names = '';
                $edit_id = array();
                $region_id = array();
                $region_names = array();
                $region_disable = array();

                foreach($item as $k=>$v)
                {
                    if(Myregion::instance($v['region_id'])->get('disabled') == false)
                    {
                        $region_disable[] = Myregion::instance($v['region_id'])->get('local_name');
                    }
                    $region_names[$v['region_id']] = Myregion::instance($v['region_id'])->get('local_name');
                    $first_price = $v['first_price'];
                    $continue_price = $v['continue_price'];
                    $expression = $v['expression'];
                    $use_exp = $v['use_exp'];                    
                    $region_id[] = $v['region_id'];
                    $edit_id[] = $v['id'];
                }
                ksort($region_names);                               
                $names = implode(',', $region_names);
                $disable_names = implode(',', $region_disable);
                $region_ids = implode('-', $region_id);
                $ids = implode('-', $edit_id);
                $region_areas[$key]['first_price'] = $first_price;
                $region_areas[$key]['continue_price'] = $continue_price;
                $region_areas[$key]['expression'] = $expression;
                $region_areas[$key]['use_exp'] = $use_exp;
                $region_areas[$key]['region_names'] = $names;
                $region_areas[$key]['ids'] = $ids;
                $region_areas[$key]['region_ids'] = $region_ids;
                $region_areas[$key]['disable_names'] = $disable_names;
            }    
        }

        $this->template->content = new View("site/deliverycn/edit");
        $this->template->content->data        = $data;
        $this->template->content->region_area    = $region_areas;
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
            $deliverycn_service = DeliverycnService::get_instance();
            $deliverycn_region_service = Deliverycn_regionService::get_instance();
            //数据验证
            $validation = Validation::factory($request_data)
                ->pre_filter('trim')
                ->add_rules('deliverycn_id',      'required',   'digit')
                ->add_rules('name',               'required',   'length[0,100]')
                ->add_rules('url',                'required',   'length[0,200]')
                ->add_rules('first_unit',         'required',   'numeric')
                ->add_rules('continue_unit',      'required',   'numeric')
                ->add_rules('use_exp',            'required',   'digit')
                ->add_rules('type',               'required',   'digit')
                ->add_rules('position',           'required',   'numeric')
                ->add_rules('active',             'required',   'digit');

            //验证物流表的数据
            $deliverycn = $deliverycn_service->get($request_data['deliverycn_id']);
            
            if(empty($deliverycn) || !isset($deliverycn))
            {
                remind::set(Kohana::lang('o_global.bad_request'),request::referrer(),'error');
            }
                        
            //验证物流地区表的数据
            if(!empty($deliverycn['type']) && ($deliverycn['type'] == 1))
            {
                $deliverycn_regions = $deliverycn_region_service->get_delivery_regions_by_id($deliverycn['id']);

                if(empty($deliverycn_regions) || !isset($deliverycn_regions)){
                    remind::set(Kohana::lang('o_global.bad_request'),request::referrer(),'error');
                }
                //验证物流地区表里面的数据是否与物流表的数据统一
                $old_region_ids = array();
                foreach($deliverycn_regions as $item)
                {
                    if(!is_array($item) || count($item) < 0)
                    {
                        remind::set(Kohana::lang('o_global.access_denied'), request::referrer(), 'error');
                        break;
                    }
                    foreach($item as $k=>$v)
                    {
                        if($v['deliverycn_id'] != $deliverycn['id'])
                        {
                            remind::set(Kohana::lang('o_global.bad_request'),request::referrer(),'error');
                        }
                        
                        //保存已存在的物流地区的信息
                        $old_region_ids[$v['region_id']] = $v['id'];
                    }
                }
            }
            
            //检测物流名称是否存在
            if($deliverycn_service->name_is_exist($request_data['name'],$request_data['deliverycn_id']))
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
            $deliverycn_service->set($deliverycn['id'], $set_data);
            
            //处理地区费用类型改变的情况
            if($deliverycn['type'] == 1) 
            {                
                //处理支持的配送国家的情况
                if($request_data['type'] == 1)
                {
                    if (!empty($request_data['des_indexs']) AND is_array($request_data['des_indexs']))
                    {
                        $whole_region_ids = array();
                        foreach ($request_data['des_indexs'] as $i => $index) 
                        {
                            // 验证国家数据
                            $validation = Validation::factory($request_data)
                                ->pre_filter('trim')
                                ->add_rules('region_names_'.$index,    'required')
                                ->add_rules('regions_use_exp_'.$index,'required', 'digit')
                                ->add_rules('region_ids_'.$index,      'required');
        
                            // 生成 支配的地区数据
                            $region_data = array(
                                'deliverycn_id'      => $deliverycn['id'],
                                'first_price'        => $request_data['first_price_'.$index],
                                'continue_price'     => $request_data['continue_price_'.$index],
                                'use_exp'            => $request_data['regions_use_exp_'.$index],
                                'expression'         => $request_data['expression_'.$index]
                            );
                            
                            $region_ids = explode('-', $request_data['region_ids_'.$index]);
                            $region_names = explode(',', $request_data['region_names_'.$index]);
                        
                            //判断提交的地区id个数是否于地区名称的个数一致
                            if(empty($region_ids))
                            {
                                remind::set(Kohana::lang('o_global.bad_request'),request::referrer(), 'error');
                            }
                            //使用公式
                            if ($request_data['regions_use_exp_'.$index] == 1) 
                            {
                                $validation->add_rules('expression_'.$index,      'required');
                            }
                            else
                            {
                                $validation->add_rules('first_price_'.$index,      'required',   'numeric');
                                $validation->add_rules('continue_price_'.$index,   'required',   'numeric');
                                $region_data['expression'] = delivery::create_exp($request_data['first_unit'], $request_data['continue_unit'], $request_data['first_price_'.$index], $request_data['continue_price_'.$index]);
                            }
                                        
                            $region_data['position'] = $index - 1;                        
                            
                            $ids_index = 'ids_'.$index;
                            $de_regions[$index] = $region_data;
                            $de_regions[$index]['ids'] = array();
                            if (!empty($request_data[$ids_index]))
                            {
                                $de_regions[$index]['ids'] = $request_data[$ids_index];
                            }                        
                            
                            $regions_ids[$index] = $request_data['region_ids_'.$index];
                            $whole_region_ids = array_merge($region_ids, $whole_region_ids);
                        }
                        
                        foreach ($regions_ids as $i=>$v) 
                        {
                            $region_ids = explode('-', $v);
                            foreach($region_ids as $value)
                            {
                                $de_regions[$i]['region_id'] = $value;
                                if(!isset($old_region_ids[$value]))
                                {    
                                    $deliverycn_region_service->add($de_regions[$i]);
                                }
                                else
                                {
                                    $deliverycn_region_service->set($old_region_ids[$value], $de_regions[$i]);
                                }
                            }
                        }
                        
                        foreach($old_region_ids as $key=>$id)
                        {
                            if(!in_array($key, $whole_region_ids))
                            {
                                $deliverycn_region_service->remove($id);
                            }
                        }
                    }                
                }
                else
                {
                    $deliverycn_region_service->delete_delivery_regions_by_deliverycn_id($deliverycn['id']);
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
                                ->add_rules('region_names_'.$index,    'required', 'length[0,512]')
                                ->add_rules('regions_use_exp_'.$index,'required', 'digit')
                                ->add_rules('region_ids_'.$index,      'required', 'length[0,256]');
        
                            // 生成 支配国家数据
                            $region_data = array(
                                'deliverycn_id'      => $deliverycn['id'],
                                'first_price'        => $request_data['first_price_'.$index],
                                'continue_price'     => $request_data['continue_price_'.$index],
                                'use_exp'            => $request_data['regions_use_exp_'.$index],
                                'expression'         => $request_data['expression_'.$index]
                            );
                            
                            $region_ids = explode('-', $request_data['region_ids_'.$index]);
                            $region_names = explode(',', $request_data['region_names_'.$index]);
                        
                            //判断提交的国家id个数是否于国家名称的个数一致
                            if(empty($region_ids))
                            {
                                remind::set(Kohana::lang('o_global.bad_request'),request::referrer(), 'error');
                            }
                            //使用公式
                            if ($request_data['regions_use_exp_'.$index] == 1) 
                            {
                                $validation->add_rules('expression_'.$index,      'required');
                            }
                            else
                            {
                                $validation->add_rules('first_price_'.$index,      'required',   'numeric');
                                $validation->add_rules('continue_price_'.$index,   'required',   'numeric');
                                $region_data['expression'] = delivery::create_exp($request_data['first_unit'], $request_data['continue_unit'], $request_data['first_price_'.$index], $request_data['continue_price_'.$index]);
                            }

                            $region_data['position'] = $index - 1;
                            foreach($region_ids as $i)
                            {
                                $region_data['region_id'] = $i;
                                if(!$deliverycn_region_service->add($region_data))
                                {
                                    remind::set(Kohana::lang('o_global.add_error'),'site/deliverycn/add');
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
                    remind::set(Kohana::lang('o_global.update_success'),'site/deliverycn/edit/'.$deliverycn['id'],'success');
                default:
                    remind::set(Kohana::lang('o_global.update_success'),'site/deliverycn/','success');
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
        $deliverycn_service = DeliverycnService::get_instance();
        if($deliverycn_service->delete_by_id($id))
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
        $deliverycn_ids = $this->input->post('deliverycn_ids');
            
        if(is_array($deliverycn_ids) && count($deliverycn_ids) > 0)
        {
            $deliverycn_service = DeliverycnService::get_instance();
                
            /* 删除失败的物流 */
            $failed_delivery_names = '';
            
            /* 执行操作 */
            foreach($deliverycn_ids as $deliverycn_id)
            {
                if(!$deliverycn_service->delete_by_id($deliverycn_id))
                {
                    $failed_delivery_names .= ' | ' . $deliverycn_id;
                }
            }
            if(empty($failed_delivery_names))
            {
                remind::set(Kohana::lang('o_site.delete_carrier_success'), 'site/deliverycn', 'success');
            }
            else
            {
                $failed_delivery_names = trim($failed_delivery_names,' | ');
                remind::set(Kohana::lang('o_site.delete_carrier_error').$failed_delivery_names, 'site/deliverycn');
            }
        }
        else
        {
             remind::set(Kohana::lang('o_site.delete_carrier_error'), 'site/deliverycn');
        }
    }
    
     /**
     * 选择地区
     */    
    function sel_region()
    {
    	$return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
	        //$request_data = $this->input->get();
	        $pid = $this->input->get('pid',0);
	        $checked = $this->input->get('checked','');
	        'checked'==$checked && $checked = 'checked="checked"';
	        
	        $where_view      = array();
	        //初始化请求结构体
	        $query_struct = array (
	            'where'   => array (
	                //'active'    => 1
	            ), 
	            'like'    => array (), 
	            'orderby' => array (
	                'id'        => 'ASC' 
	            ), 
	        );
	
	        /*$whole_count = Myregion::instance()->query_count($query_struct);
	        if(isset($request_data['region_ids']) && !empty($request_data['region_ids']))
	        {
	            $query_region_ids = explode('-', $request_data['region_ids']);
	            $region_ids = implode(',', $query_region_ids);
	            $query_struct['not_in']['id'] = $region_ids;
	        }    
	        $count = Myregion::instance()->query_count($query_struct);
	        $left_count = $whole_count - $count;*/
	        
	        $regions = Myregion::instance()->areas($pid);

            if($this->is_ajax_request()){
            	$this->template = new View("site/deliverycn/ajax_region_list");
            	$this->template->checked = $checked;
            	$this->template->regions = $regions;
            	$content = $this->template->render();
            	$return_struct = array (
	                'status' => 1, 
	                'code' => 200, 
	                'msg' => 'ok', 
	                'content' => $content
	            );
                exit(json_encode($return_struct));
            }else{
                //调用列表
                $this->template = new View('layout/commonfix_html');
		        $this->template->content = new View("site/deliverycn/region_list");
		        /*$this->template->content->regions = Myregion::instance()->query_assoc($query_struct);
		        $this->template->content->whole_count = $whole_count;
		        $this->template->content->left_count = $left_count;*/
		        $this->template->content->regions = $regions;//helper::dump($regions);exit();
		        /*if(isset($request_data['region_ids']) && !empty($request_data['region_ids']))
		        {
		            $this->template->content->region_ids = $request_data['region_ids'];
		        }*/
            }        	
        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $return_struct);
        }
    }
    
    /*
     * 获取地区的名称
     */
    function get_region()
    {
        $return_struct = array();
        $regions = array();
        // 修改返回状态数据
        $return_struct['status']    = 1;
        $return_struct['code']      = 200;
        $return_struct['msg']       = 'ok';
        $request_data = $this->input->get();
        $query_region_ids = explode('-', $request_data['region_ids']);
        if(!empty($query_region_ids))
        {
            foreach($query_region_ids as $id)
            {
                $regions[] = Myregion::instance($id)->get('local_name');
            }
        }
        if(empty($regions))
        {
            $return_struct['status']    = 0;
            $return_struct['code']      = 501;
            $return_struct['msg']       = 'error';
        }
        $regions_str = implode(',', $regions);
        $return_struct['content'] = $regions_str;
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
        $deliverycn_service = DeliveryService::get_instance();
        $deliverycn_region_service = Deliverycn_regionService::get_instance();
        if (empty($request_data['deliverycn_id']) OR empty($request_data['ids'])) 
        {
            remind::set(Kohana::lang('o_global.access_denied'), request::referrer(), 'error');
        }
        $delivery = $deliverycn_service->get($request_data['deliverycn_id']);
        if($delivery['site_id'] != $this->site_id)
        {
            remind::set(Kohana::lang('o_global.access_denied'), request::referrer(), 'error');
        }
        
        $query_ids = explode('-', $request_data['ids']);
        foreach($query_ids as $ids)
        {
            $delivery_countries = $deliverycn_region_service->get($ids);
            if($delivery_countries['deliverycn_id'] != $deliverycn['id'])
            {
                remind::set(Kohana::lang('o_global.access_denied'), request::referrer(), 'error');
                break;
            }
            if(!$deliverycn_region_service->remove($ids))
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
