<?php
defined('SYSPATH') or die('No direct access allowed.');
class Kc_browser_stat_Controller extends Template_Controller {
    
    private $class_name = 'kc_browser_stat';
    
    /**
     * 构造方法
     */
    public function __construct()
    {
        //$this->class_name = strtolower(substr(__CLASS__, 0, strpos(__CLASS__, '_')));
        parent::__construct();
        if($this->is_ajax_request())
        {
            $this->template = new View('layout/default_json');
        }
    }
    
    /**
     * 数据列表
     */
    public function index()
    {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );

        $return_data = array ();

        $request_data = $this->input->get();

        /* 初始化默认查询结构体 */
        $query_struct_default = array (
            'like' => array (), 
            'orderby' => array (

                'id' => 'DESC' 
            ), 
            'limit' => array (
                'per_page' => 20,
                'page' => 1
            ) 
        );

        /* 初始化当前查询结构体 */
        $query_struct_current = array ();

        //* 设置合并默认查询条件到当前查询结构体 */
        $query_struct_current = array_merge($query_struct_current, $query_struct_default);

        //列表排序
        $orderby_arr = array (
            0 => array (
                'id' => 'DESC' 
            ), 
            1 => array (
                'id' => 'ASC' 
            ), 
            2 => array (
                'type' => 'ASC' 
            ), 
            3 => array (
                'type' => 'DESC' 
            ), 
            4 => array (
                'version' => 'ASC' 
            ), 
            5 => array (
                'version' => 'DESC' 
            ),
            6 => array (
                'agent_detail' => 'ASC' 
            ), 
            7 => array (
                'agent_detail' => 'DESC' 
            ),
            8 => array (
                'ip' => 'ASC' 
            ), 
            9 => array (
                'ip' => 'DESC' 
            ),
            10 => array (
                'date_add' => 'ASC' 
            ), 
            11 => array (
                'date_add' => 'DESC' 
            ),
            12 => array (
                'date_upd' => 'ASC' 
            ), 
            13 => array (
                'date_upd' => 'DESC' 
            ),
            14 => array (
                'quantity' => 'ASC' 
            ), 
            15 => array (
                'quantity' => 'DESC' 
            ),
        );
        $orderby = controller_tool::orderby($orderby_arr);
        // 排序处理 
        if(isset($request_data['orderby']) && is_numeric($request_data['orderby']))
        {
            $query_struct_current['orderby'] = $orderby;
        }
        // 每页条目数
        controller_tool::request_per_page($query_struct_current,$request_data);

        //调用服务执行查询
        $kc_browser_stat = Mykc_browser_stat::instance();
        $count = $kc_browser_stat->count($query_struct_current);

        // 模板输出 分页
        $this->pagination = new Pagination(array(
            'total_items' => $count,
            'items_per_page' => $query_struct_current['limit']['per_page'],
        ));
        $query_struct_current['limit']['page'] = $this->pagination->current_page;
        $return_data['list'] = Mykc_browser_stat::instance()->lists($query_struct_current);

        $return_struct['content'] = $return_data;

        $content = new View($this->class_name . '_' . __FUNCTION__);
        /* 变量绑定 */
        $this->template->title = '浏览器详细信息';
        $this->template->content = $content;
        $this->template->content->request_data = $request_data;
        /* 返回结构体绑定 */
        $this->template->content->return_struct = $return_struct;
    }

}
