<?php defined('SYSPATH') OR die('No direct access allowed.');
/*
 * 后台需要自动运行的程序
 */
class Auto_Controller extends Template_Controller {
    public function __construct()
    {
        parent::__construct();       
    }
    
    public function index()
    {
        $arrurl[] = '/autorun/auto/update_order';
        
        $view = new View("autorun/auto_index");
        $view->urls = $arrurl;
        $view->render(TRUE);
    }
    
    /*
     * 更新方案
     */
    public function update_order()
    {
        plan::get_instance()->expired_plan();    //过期自动返还
        
        //北单自动兑奖
        $bjdc_obj = Plans_bjdcService::get_instance();
        $results = $bjdc_obj->get_unpaijiang_id();

        if(empty($results))
            return ;

        foreach ($results as $row)
        {
           $bjdc_obj->bonus_plan($row);
        }
    }
    
}