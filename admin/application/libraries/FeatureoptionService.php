<?php
defined('SYSPATH') or die('No direct access allowed.');

class FeatureoptionService_Core extends DefaultService_Core {
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
    
    /**
     * 根据 featureoption_id 获取 feature
     * 
     * @param $featureoption_id  int
     * @return array
     */
    public function get_feature_by_featureoption_id($featureoption_id)
    {
        try{
            $featureoption = self::get_instance()->get($featureoption_id);
            $feature_id = $featureoption['feature_id'];
            return FeatureService::get_instance()->get($feature_id);
        }catch(MyRuntimeException $ex){
            throw $ex;
        }
    }
    
    /**
     * 根据 featureoption_id 获取该 featureoption 的兄弟记录
     * 
     * @param $featureoption_id  int
     * @param $clear_self  bool
     * @return array
     */
    public function get_brothers_by_featureoption_id($featureoption_id, $clear_self = FALSE)
    {
        try{
            $featureoption = self::get_instance()->get($featureoption_id);
            $feature_id = $featureoption['feature_id'];
            $featureoptions = FeatureService::get_instance()->get_featureoptions_by_feature_id($feature_id);
            
            if($clear_self == TRUE){
                foreach($featureoptions as $index => $featureoption){
                    if($featureoption['id'] == $featureoptions_id){
                        unset($featureoptions[$index]);
                    }
                }
            }
            
            return $featureoptions;
        }catch(MyRuntimeException $ex){
            throw $ex;
        }
    }
    
    /**
     * 通过featureoption_id 判断该 featureoption 当前是否正在被关联
     * @param $featureoption_ids  int || array
     * @return bool
     * @throws MyRuntimeException
     */
    public function is_relation_by_featureoption_id($featureoption_ids)
    {
        // 初始化默认查询条件
        $request_struct = array (
            'where' => array (
                'featureoption_id' => $featureoption_ids 
            )
        );
        try{
            // 判断与  product_featureoption_relations 表的关联
            if(Product_featureoption_relationService::get_instance()->count($request_struct)){
                return TRUE;
            }
        }catch(MyRuntimeException $ex){
            throw $ex;
        }
        
        return FALSE;
    }
    
    /**
     * 通过 featureoption_id 删除 featureoption
     * @param  int || array $featureoption_ids 规格项ID或ID数组
     * @return void
     * @throws MyRuntimeException
     */
    public function delete_by_featureoption_id($featureoption_ids)
    {
        try{
            if($this->is_relation_by_featureoption_id($featureoption_ids)){
                throw new MyRuntimeException('Hava relative data, delete is denied.', 500);
            }
            if(is_array($featureoption_ids) && !empty($featureoption_ids)){
                ORM::factory('featureoption')->in('id', $featureoption_ids)->delete_all();
            }else{
                $this->remove($featureoption_ids);
            }
        }catch(MyRuntimeException $ex){
            throw $ex;
        }
    }
}