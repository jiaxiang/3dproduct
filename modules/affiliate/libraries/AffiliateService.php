<?php defined('SYSPATH') OR die('No direct access allowed.');

class AffiliateService_Core extends DefaultService_Core{
	/* 兼容php5.2环境 Start */ 
    private static $instance = NULL; 
    // 获取单态实例 
    public static function get_instance() 
    {
        if(self::$instance === null){ 
            $classname = __CLASS__; 
            self::$instance = new $classname(); 
        }
        return self::$instance; 
    }
	
    /**
     * 获取平台所有的联盟以及站点中个联盟的状态
     */
	public function get_all_affiliates($site_id){
		//所有支持的联盟
		$affiliates = $this->index( array('where'=> array('mark'=> 1,),) );
		//站点中各个联盟的状态
		$site_affiliates = Site_affiliateService::get_instance()->get_affiliates($site_id);
		if (!empty($site_affiliates)) {
			$aids = array();
			foreach ($site_affiliates as $s_a){
				$aids[] = $s_a['affiliate_id'];
			}
			for ($i=0;$i<count($affiliates);$i++){
				if (in_array( $affiliates[$i]['id'], $aids) ) {
					$affiliates[$i]['install'] = 1;
				}
			}
		}
		return $affiliates;
	}
	
	/**
	 * 获取要安装的联盟的信息
	 *
	 * @param int $affiliate_id
	 * @param int $site_id
	 * @return unknown
	 */
	public function get_affiliate_install($affiliate_id, $site_id){
		$site_affiliate = Site_affiliateService::get_instance()->index( array('where'=>array('affiliate_id'=>$affiliate_id, 'site_id'=>$site_id, 'mark'=>1)) );
		if (empty($site_affiliate)) {
			$affiliate = $this->query_row( array('where'=>array('id'=>$affiliate_id,'mark'=>1)) );
			if (empty($affiliate)) {
				throw new MyRuntimeException('', 403);
			}else {
				$affiliate['form_string'] = $this->get_form_string($affiliate['name']);
				return $affiliate;
			}
		}else {
			throw new MyRuntimeException('该联盟已经安装，不能重复安装！', 403);
		}
	}
	
	/**
	 * 获取联盟的参数格式
	 * @param string $affiliate_name
	 * @return string
	 */
	public function get_form_string($affiliate_name){
		if (file_exists(dirname(__FILE__).'/driver/'.$affiliate_name.'.php')) {
			require_once( dirname(__FILE__).'/driver/'.$affiliate_name.'.php' );
		}else {
			throw new MyRuntimeException('文件 '.dirname(__FILE__).'/driver/'.$affiliate_name.'.php 不存在！', 403);
		}
		$class_name = strtoupper( substr($affiliate_name,0,1) ) . substr($affiliate_name,1);
		if (class_exists($class_name)) {
			$class = new $class_name();
			$string = $class->get_form_string();
		}else {
			throw new MyRuntimeException('class '.$class_name.' 不存在！', 403);
		}
		return $string;
	}
}