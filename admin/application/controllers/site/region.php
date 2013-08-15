<?php defined('SYSPATH') OR die('No direct access allowed.');

class Region_Controller extends Template_Controller {
    public $site_ids;

    public function __construct()
    {
        parent::__construct();
        role::check('site_region');
    }
    
    /**
     * 菜单列表
     */
    public function index() {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            $pid = $this->input->get('pid',0);
            $areas = Myregion::instance()->areas($pid);            
            $return_struct = array (
                'status' => 1, 
                'code' => 200, 
                'msg' => 'ok', 
                'content' => $areas 
            );
            if($this->is_ajax_request()){
                exit(json_encode($return_struct));
            }else{
                $this->template->content = new View("site/region_list", array('areas' => $areas));
            }
        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $return_struct);
        }
    }

    public function add(){
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            $pid = $this->input->get('pid',0);            
            $post = $this->input->post();
            if($post){
                tool::filter_strip_tags($post);
                                
                if(empty($post['local_name'])){
                    throw new MyRuntimeException(Kohana::lang('region.local_name_null'), 500);
                }
                
                if(Myregion::instance()->check_name('local_name', $post['local_name'], $pid)){
                    throw new MyRuntimeException(Kohana::lang('region.local_name_exists'), 500);
                }
                
                if(!empty($post['en_name']) && Myregion::instance()->check_name('en_name', $post['en_name'], $pid)){
                    throw new MyRuntimeException(Kohana::lang('region.en_name_exists'), 500);
                }
                
                $post['p_region_id'] = (int)$pid;
                $post['package']     = 'mainland';
                if(Myregion::instance()->add($post)) {
                    remind::set(Kohana::lang('o_global.add_success'),'site/region','success');
                }else {
                    remind::set(Kohana::lang('o_global.add_error'),'site/region/add');
                }
            }

            $pdata = Myregion::instance($pid)->get();
            if($pid != $pdata['id']){
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 404);
            }
            $this->template->content = new View("site/region_edit",array(
                        'pdata' => $pdata,
                    ));
        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $return_struct);
        }
    }
  
    public function edit(){
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            $id = $this->input->get('id');
            if(empty($id)){
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
            }
            
            $data = Myregion::instance($id)->get();
            if(empty($data['id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
            }
            
            $pid = $data['p_region_id'];
            $post = $this->input->post();
            if($post){
                tool::filter_strip_tags($post);
                
                if(empty($post['local_name'])){
                    throw new MyRuntimeException(Kohana::lang('region.local_name_null'), 500);
                }
                
                if(Myregion::instance()->check_name('local_name', $post['local_name'], $pid, $id)){
                    throw new MyRuntimeException(Kohana::lang('region.local_name_exists'), 500);
                }
                
                if(!empty($post['en_name']) && Myregion::instance()->check_name('en_name', $post['en_name'], $pid, $id)){
                    throw new MyRuntimeException(Kohana::lang('region.en_name_exists'), 500);
                }
                
                if(Myregion::instance()->update($id, $post)){
                    remind::set(Kohana::lang('o_global.update_success'),'site/region','success');
                }else {
                    remind::set(Kohana::lang('o_global.update_error'),'site/region/eidt?id='.$id);
                }
            }
            
            if($pid>0){
                $pdata = Myregion::instance($pid)->get();
            }else{
                $pdata = array();
            }
            $this->template->content = new View("site/region_edit",array(
                        'data' => $data,
                        'pdata' => $pdata,
                    ));
        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $return_struct);
        }
    }
 
    public function delete(){
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            $id = $this->input->get('id');
            if(Myregion::instance()->delete($id)){
                throw new MyRuntimeException(Kohana::lang('o_global.delete_success'), 200);
            }else{
                throw new MyRuntimeException(Kohana::lang('o_global.delete_error'), 500);
            }
        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $return_struct);
        }
    }

    /**
     * set active
     */
    public function set_active()
    {
        $id = $this->input->get('id');
        if(!$id)
        {
            remind::set(Kohana::lang('o_global.set_error'),'site/region');
        }
        $menu = Myregion::instance($id)->get();
        $active = 0;
        if($menu['active'] == 0)
        {
            $active = 1;
        }
        if(Myregion::instance()->set_active($id,$active))
        {
            remind::set(Kohana::lang('o_global.set_success'),'site/region','success');
        }
        else
        {
            remind::set(Kohana::lang('o_global.set_error'),'site/region');
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
       if(empty($id) || (empty($order) && $order != 0))
       {
           $return_struct['msg'] = Kohana::lang('o_global.bad_request');
           exit(json_encode($return_struct));
       }
       if(!is_numeric($order) || $order<0){
           $return_struct['msg'] = Kohana::lang('o_global.position_rule');
           exit(json_encode($return_struct));
       }
       if(Myregion::instance()->set_order($id, $order)){
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
}
