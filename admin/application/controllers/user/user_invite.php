<?php defined('SYSPATH') OR die('No direct access allowed.');

class User_invite_Controller extends Template_Controller {
    
    /* 用户列表 */
    public function index(){
        role::check('user_list');
        /* 初始化默认查询条件 */
        $user_query_struct = array(
            'where'=>array(
                'invite_user_id > ' =>0,
            ),
            'like'=>array(),
            'orderby'   => array(),
            'limit'     => array(
                'per_page'  => 20,
                'offset'    => 0,
            ),
        );        
        
        /* 用户列表模板 */
        $this->template->content = new View("user/user_invite");
        
        /* 搜索功能 */
        $search_arr      = array('id','email','lastname','ip');
        $search_value    = $this->input->get('search_value');
        $search_type     = $this->input->get('search_type');
        $where_view      = array();
        if($search_arr){
            foreach($search_arr as $value){
                if($search_type == $value && strlen($search_value) > 0){
                    $user_query_struct['like'][$value] = $search_value;
                    //$user_query_struct['where'][$value] = $search_value;
                    if($value == 'ip'){
                        $user_query_struct['like'][$value] = tool::myip2long($search_value);
                        //$user_query_struct['where'][$value] = tool::myip2long($search_value);                     
                    }
                }
            }
            $where_view['search_type']    = $search_type;
            $where_view['search_value']   = $search_value;
        }      

        
        /* 列表排序 */
        $orderby_arr= array(
                0   => array('id'=>'DESC'),
                1   => array('id'=>'ASC'),
                4   => array('email'=>'ASC'),
                5   => array('email'=>'DESC'),
                8   => array('lastname'=>'ASC'),
                9   => array('lastname'=>'DESC'),
                10  => array('date_add'=>'ASC'),
                11  => array('date_add'=>'DESC'),
                12  => array('ip'=>'ASC'),
                13  => array('ip'=>'DESC'),
                14  => array('active'=>'ASC'),
                15  => array('active'=>'DESC'),
                16  => array('register_mail_active'=>'ASC'),
                17  => array('register_mail_active'=>'DESC')
            );

        $orderby    = controller_tool::orderby($orderby_arr);
        $user_query_struct['orderby'] = $orderby;
        
        /* 每页显示条数 */
        $per_page    = controller_tool::per_page();
        $user_query_struct['limit']['per_page'] = $per_page;
        
        /* 调用分页 */
        $this->pagination = new Pagination(array(
            'total_items'    => Myuser::instance()->query_count($user_query_struct),
            'items_per_page' => $per_page,
        ));
        $user_query_struct['limit']['offset'] = $this->pagination->sql_offset;
        
        $users = Myuser::instance()->query_assoc($user_query_struct);
        
        if(!empty($users)) {
            $invite_ids  = array();
            $u_ids  = array();
            foreach ($users as $row) {
                $invite_ids[$row['invite_user_id']] = $row['invite_user_id'];
                $u_ids[$row['id']] = $row['id'];
            }
            
            $user_invite_query_struct = array(
                'where'=>array(
                    'id' => array_keys($invite_ids),
                ),

            );            
            $user_invite = Myuser::instance()->query_assoc($user_invite_query_struct);
        }
        
        $invites = array();
        
        if(!empty($user_invite)) {
            foreach($user_invite as $row) {
                $invites[$row['id']] = $row;
            }
        }
        
        $reward = array();
        if(!empty($u_ids)) {
            $sql = " SELECT guest_user_id,reward_money FROM user_reward  WHERE guest_user_id in(".implode(',', $u_ids).") AND user_id > 0 AND reward_type = 'invite' " ;
            $reward_list = User_inviteService::get_instance()->select_list($sql);

            if(!empty($reward_list)) {
                foreach($reward_list as $row) {
                    $reward[$row['guest_user_id']] = $row['guest_user_id'];
                }
            }
        }
        
        /* 调用列表 */
        $this->template->content->user_list     = $users;
        $this->template->content->invite_list   = $invites;
        $this->template->content->reward_list   = $reward;
        $this->template->content->where         = $where_view;
    }

    /**
     * 修改用户信息
     */
    function edit($id) {
        //权限检查 得到所有可管理站点ID列表
        role::check('user_edit');
        
        if($_POST) {
            //标签过滤
            tool::filter_strip_tags($_POST);

            if(Myuser::instance($id)->set_money($_POST['invite_user_id'],$_POST['reward_money']))
            {
                $arr['guest_user_id'] = intval($_POST['user_id']);
                $arr['user_id'] = intval($_POST['invite_user_id']);
                $arr['reward_money'] = intval($_POST['reward_money']);
                $arr['memo'] = '邀请用户奖励';
                $arr['reward_type'] = 'invite';
                $arr['time_stamp'] = date("Y-m-d H:i:s",time());

                User_inviteService::get_instance()->insert($arr);
                
                $logodata = array();
                $logodata['manager_id'] = $this->manager_id;
                $logodata['ip'] = tool::get_str_ip();
                $logodata['user_log_type'] = 28;
                $logodata['method'] = __CLASS__.'::'.__METHOD__.'()';
                $logodata['memo'] = "邀请奖励审核通过, 奖励:".$_POST['reward_money'];
                ulog::add($logodata);
                
                remind::set(Kohana::lang('o_global.update_success'),request::referrer(),'success');
            }
            else
            {
                remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
            }
        }
        
        $this->template->content = new View("user/user_invite_edit");
        
        $id = intval($id);
        $data = Myuser::instance($id)->get();
        $data['invite'] = Myuser::instance($data['invite_user_id'])->get();
        
        $sql = " SELECT COUNT(id) AS id FROM user_reward  WHERE guest_user_id = ".$data['id']." AND user_id = ".$data['invite']['id']."  AND reward_type = 'invite' " ;
        $data['reward'] = User_inviteService::get_instance()->get_one($sql);
        $data['reward'] = $data['reward']['id'];
        $this->template->content->data = $data;
        
    }

}
