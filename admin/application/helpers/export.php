<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * 导出类
 * 用于自定义导出
 * 原始配置文件在\application\config\export.php
 * 使用方法
 *
		$order_ids = array(6712,6713);
		$output_field_ids = array(37,3,2,4,5);//导出这5个字段
		$xls = export::instance();
		//$xls->debug(true);//开测试模式
		//$xls->set_prefix("'");//设置格前缀
		$xls->set_output_field_ids($output_field_ids);

		$result = array();
		foreach($order_ids as $order_id){
			$order		 = Rpc::instance()->order('get_order',$order_id);
			$xls->set_order_line($order);
		}
		$xls->output();
 */

class Export_Core{
	//SEPARATOR  分隔符
	protected $separator = ",";
	//换行符
	protected $newline = "\r\n";
	//第一行文字	
	protected $header_line = "";
	//每格前缀
	protected $prefix = '';
	//导出的文件名
	protected $file_name = "";
	//config导出原始设置
	protected $config = array();
	//测试模式 默认关闭
	protected $debug = false;
	
	private static $instance;

	public static function instance()
	{
		if(!isset(self::$instance)){
			$class = __CLASS__;
			self::$instance = new $class();
		}
		
		return self::$instance;
	}

	public function __construct()
	{
		$config = Kohana::config('order_export.default');
		foreach($config as $key=>$rs)
		{
			$this->config[$key] = $rs;
		}
		$this->file_name = "Order" . date("Ymd",time());
	}

	public function output()
	{
		if($this->debug == false){
			$this->header_for_xls();
		}
		$this->set_header_line();
		
		echo $this->header_line . $this->order_line;
	}

	/*
	 * 输出一个xls头
	 */
	public function header_for_xls()
	{
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		;
		header("Content-Disposition: attachment;filename=" . $this->file_name . ".csv");
		header("Content-Transfer-Encoding: binary ");
		header("Charset=UTF-8");
	
	}

	/*
	 * 设置测试模式
	 */
	public function debug($boolean)
	{
		$this->debug = $boolean;
	}

	/*
	 * 设置导出字段值
	 */
	public function set_output_field_ids($output_field_ids)
	{
		$this->output_field_ids = $output_field_ids;
	}

	/*
	 * 设置第一行
	 */
	public function set_header_line()
	{
		foreach($this->output_field_ids as $id)
		{
			$value = @iconv('UTF-8','gb2312',$this->config[$id]['show']);
			$this->header_line .= $value . $this->separator;
		}
		$this->header_line .= $this->newline;
	}

	/*
	 * 设置一个订单
	 */
	public function set_order_line($order)
	{
		foreach($this->output_field_ids as $id){
			if($this->config[$id]['parent'])
			{
				if($this->config[$id]['parent'] == 'product')
				{
					if(count($order['product']) > 0)
					{
						$this->order_line .= $this->_field_to_value($this->config[$id]['field'],$order['product'][0]);
					} else{
						$this->order_line .= $this->_field_to_value();
					}
				} else{
					$this->order_line .= $this->_field_to_value($this->config[$id]['field'],$order[$this->config[$id]['parent']]);
				}
			} else{
				$this->order_line .= $this->_field_to_value($this->config[$id]['field'],$order);
			}
		}
		$this->order_line .= $this->newline;
		foreach($order['product'] as $key=>$value)
		{
			if($key>0)
			{
				foreach($this->output_field_ids as $id)
				{
					if($this->config[$id]['parent'] == 'product')
					{
						$this->order_line .= $this->_field_to_value($this->config[$id]['field'],$value);
					} else {
						$this->order_line .= $this->_field_to_value();
					}
				}
				$this->order_line .= $this->newline;
			}
		}
	}

	/*
	 * 包装配置文件中的字段名 成数组中的键值
	 */
	public function _field_to_value($field = NULL, $order = array())
	{
		$return = '';
		$fields = explode("|",$field);
		//print_r($fields);
		foreach($fields as $field){
			$key = $field;
			$value = @iconv('UTF-8','gb2312',$order[$key]);
			$value = str_replace(',','~',$value);
			$return .= $value . " ";
		}
		if(trim($return) == ''){
			return $return . $this->separator;
		} else{
			return $this->prefix . $return . $this->separator;
		}
	
	}

	/*
	 * 返回config配置
	 */
	public function config()
	{
		return $this->config;
	}

	/*
	 * 设置格前缀
	 */
	public function set_prefix($prefix)
	{
		$this->prefix = $prefix;
	}
}
