<?php defined('SYSPATH') OR die('No direct access allowed.');

class Site_affiliateService_Core extends DefaultService_Core{
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
     * 获取站点所有的联盟
     * 
     * @param int site_id
     */
    public function get_affiliates($site_id){
    	$site_affiliates = $this->index( array('where'=> array('site_id'=>$site_id,'mark'=> 1,),) );
    	return $site_affiliates;
    }
    
    /**
     * 获得站点需要编辑的联盟的信息
     *
     * @param int $affiliate_id
     * @param int $site_id
     * @return unknown
     */
    public function get_affiliate_edit($affiliate_id, $site_id){
		$site_affiliate = $this->query_row( array('where'=>array('affiliate_id'=>$affiliate_id, 'site_id'=>$site_id, 'mark' => 1)) );
		if (!empty($site_affiliate)) {
			$prm_value = json_decode($site_affiliate['prm_value']);
			$send_type = $site_affiliate['send_type'];
			$affiliate_name = $site_affiliate['affiliate_name'];
			
			$site_affiliate['form_string'] = $this->get_edit_string($affiliate_name,$prm_value,$send_type);
			return $site_affiliate;
		}else {
			throw new MyRuntimeException('没有安装该网站联盟的推广！', 403);
		}
	}
    
	/**
	 * 更新联盟信息
	 *
	 * @param int $site_id
	 * @param int $affiliate_id
	 * @param array $pdata
	 * @param int $send_type
	 * @param int $cookie_day
	 * @param string $currency
	 */
    public function update_site_affiliate($site_id, $affiliate_id, $pdata, $send_type, $cookie_day=30, $currency='default'){
    	$affiliate = AffiliateService::get_instance()->query_row( array('where'=>array('id'=>$affiliate_id,'mark'=>1)) );
    	$site_affiliate = $this->query_row( array('where'=>array('affiliate_id'=>$affiliate_id,'site_id'=>$site_id)) );
    	$cookie_day = $cookie_day < 1 ? 30 : $cookie_day;
    	if (empty($site_affiliate)) {
    		$add_arr = array('site_id'      => $site_id,
    						 'affiliate_id' => $affiliate_id,
    						 'affiliate_name'=> $affiliate['name'],
    						 'prm_value'    => json_encode($pdata),
    						 'send_type'    => $send_type,
    						 'cookie_day'   => $cookie_day,
    						 'currency'     => $currency,);
    		$this->add($add_arr);
    	}else {
    		$update_arr = array('id'           => $site_affiliate['id'],
    							'site_id'      => $site_id,
    							'affiliate_id' => $affiliate_id,
    							'affiliate_name'=> $affiliate['name'],
    							'prm_value'    => json_encode($pdata),
    							'send_type'    => $send_type,
    							'cookie_day'   => $cookie_day,
    							'currency'     => $currency,
    							'mark'         =>1,);
    		$this->update($update_arr);
    	}
	}
	
	/**
	 * 卸载已经安装的联盟
	 *
	 * @param int $id
	 */
	public function uninstall_site_affiliate($id){
		$update_arr = array('id'           => $id,
							'mark'         =>0,);
		$this->update($update_arr);
	}
	
	/**
	 * 获取编辑联盟需要标签
	 *
	 * @param string $affiliate_name
	 * @param string $prm_value
	 * @param int $send_type
	 * @return string
	 */
	public function get_edit_string($affiliate_name,$prm_value,$send_type){
		if (file_exists(dirname(__FILE__).'/driver/'.$affiliate_name.'.php')) {
			require_once( dirname(__FILE__).'/driver/'.$affiliate_name.'.php' );
		}else {
			throw new MyRuntimeException('文件 '.dirname(__FILE__).'/driver/'.$affiliate_name.'.php 不存在！', 403);
		}
		$class_name = strtoupper( substr($affiliate_name,0,1) ) . substr($affiliate_name,1);
		if (class_exists($class_name)) {
			$class = new $class_name();
			$string = $class->get_edit_string($prm_value,$send_type);
		}else {
			throw new MyRuntimeException('class '.$class_name.' 不存在！', 403);
		}
		return $string;
	}
	
	/**
	 * 获取联盟需要加载到支付成功页的代码
	 *
	 * @param array $order
	 * @return string
	 */
	public function get_code($order){
		/* 联盟过来的COOKIE数据 */
		$affiliateSource = cookie::get ( 'affiliateSource' );
		$affiliate_source_shareasale = cookie::get ( 'affiliate_source_shareasale' );
		if (! empty ( $affiliateSource )) {
			if (file_exists(dirname(__FILE__).'/driver/'.$affiliateSource.'.php')) {
				require_once( dirname(__FILE__).'/driver/'.$affiliateSource.'.php' );
			}else {
				return '<!-- 文件 '.dirname(__FILE__).'/driver/'.$affiliateSource.'.php 不存在! -->';
			}
			$class_name = strtoupper( substr($affiliateSource,0,1) ) . substr($affiliateSource,1);
			if (class_exists($class_name)) {
				$class = new $class_name();
				$string = $class->get_code($order,$affiliateSource);
			}else {
				return '<!-- class '.$class_name.' 不存在! -->';
			}
			return $string;
		}
		return '<!-- 订单不是由联盟产生的 -->';
	}
	
	/**
	 * 设置cookie
	 *
	 * @param int $site_id
	 * @param int $log
	 * @return string
	 */
	public function set_cookie($site_id, $log=1){
		if(preg_match("/(source|SSAID|adnetwork)=([^&]*)/",$_SERVER['REQUEST_URI'],$aryAffi))
		{
			if ($aryAffi[1] == 'SSAID') {
				$affiliate_name = 'shareasale';
			}elseif ($aryAffi[1] == 'adnetwork' && $aryAffi[2] == "af"){
				$affiliate_name = 'affiliatefuture';
			}elseif ($aryAffi[1] == "source"){
				if ($aryAffi[2] == 'CJ') {
					$affiliate_name = 'commissionjunction';
				}elseif ($aryAffi[2] == 'earnmoneyonfb'){
					$affiliate_name = 'earnmoney';
				}else {
					$affiliate_name = $aryAffi[2];
				}
			}else {
				return '<!-- 参数格式不正确！-->';
			}
			
			if (file_exists(dirname(__FILE__).'/driver/'.$affiliate_name.'.php')) {
				require_once( dirname(__FILE__).'/driver/'.$affiliate_name.'.php' );
			}else {
				return '<!-- 文件 '.dirname(__FILE__).'/driver/'.$affiliate_name.'.php 不存在！-->';
			}
			$class_name = strtoupper( substr($affiliate_name,0,1) ) . substr($affiliate_name,1);
			if (class_exists($class_name)) {
				$class = new $class_name();
				$string = $class->set_cookie($site_id,$affiliate_name,$log);
				return $string;
			}else {
				return '<!-- class '.$class_name.' 不存在！-->';
			}
			return '<!-- 成功设置cookie -->';
		}
		return '<!-- 不是来自联盟 -->';
	}
}