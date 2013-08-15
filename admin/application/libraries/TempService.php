<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 数据服务
 * @package feedback
 * @author nickfan<nickfan81@gmail.com>
 * @link http://feedback.ketai-cluster.com
 * @version $Id: MyAttachmentService.php 227 2010-04-14 12:55:01Z zhubin $
 */
class TempService_Core  extends DefaultService_Core{
    /* 兼容php5.2环境 Start */
    private static $instance = NULL;
    // 获取单态实例
    public static function get_instance(){
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
    /* 兼容php5.2环境 End */
    
//** 业务逻辑代码请写在此行之后　**//

//    //FIXME 根据本类属性对这部分应用函数做一定业务逻辑上的调整
//    public function get($id){
//        // Custom 
//        return $this->read(array('id'=>$id));
//    }
//    public function set($id,$data){
//        // Custom 
//        $request_data = $data;
//        $request_data['id'] = $id;
//        return $this->update($request_data);
//    }
//    public function add($data){
//        // Custom 
//        return $this->create($data);
//    }
//    public function remove($id){
//        // Custom 
//        return $this->delete(array('id'=>$id));
//    }
//    public function index($query_struct){
//        // Custom 
//        return $this->query_assoc($query_struct);
//    }
//    public function count($query_struct){
//        // Custom 
//        return $this->query_count($query_struct);
//    }

    //:: 本类定制的业务逻辑 :://
    //TODO 根据业务逻辑需求提供对应的函数调用
}