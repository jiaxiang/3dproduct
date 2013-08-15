<?php defined('SYSPATH') OR die('No direct access allowed!');

/**
 * Memcached 缓存驱动
 *
 * @author 王浩
 * @version 1.0
 */
class Cache_Mem implements Cache_Interface {
    
	/**
	 * Memcached 主机名
	 * 
	 * @var string
	 */
	protected $host = NULL;
	
	/**
	 * Memcached 端口号
	 * 
	 * @var int
	 */
	protected $port = 11211;
	
	/**
	 * Memcached 存取标记
	 * 
	 * @var int
	 */
	protected $flag = MEMCACHE_COMPRESSED;
	
	/**
	 * 默认缓存生存周期
	 * 
	 * @var int
	 */
	protected $expire = 3600;
	
	/**
	 * Memcache 对象
	 * 
	 * @var object
	 */
	protected $mem = NULL;
	
	/**
	 * 连接服务器是否成功
	 *
	 * @var boolean
	 */
	protected $isConnected = FALSE;
	
	/**
	 * 初始化
	 * 
	 * @param array $option  配置数组
	 */
	public function __construct($option)
	{
		empty($option['host']) OR  $this->host = $option['host'];
		empty($option['port']) OR  $this->port = intval($option['port']);
		
		is_null($option['flag'])   OR $this->flag   = $option['flag'];
		is_null($option['expire']) OR $this->expire = intval($option['expire']);
		
		$this->mem = new Memcache();
		$this->mem->connect($this->host, $this->port) AND $this->isConnected = TRUE;
	}
	
	/**
	 * 析构函数
	 */
	public function __destruct()
	{
	    if ($this->isConnected)
	    {
	        $this->mem->close();
	    }
	    
	    $this->mem = NULL;
	}
	
	/**
	 * 读取缓存
	 * 
	 * @param string $key  缓存ID
	 * @return mixed
	 */
	public function get($key)
	{
		return $this->isConnected ? $this->mem->get($key) : FALSE;
	}
	
	/**
	 * 写入缓存
	 * 
	 * @param string $key       缓存ID
	 * @param mixed  $value     缓存数据
	 * @param int    $expire  缓存生存周期
	 * @return bool
	 */
	public function set($key, $value, $expire = NULL)
	{
		return $this->isConnected ? $this->mem->set($key, $value, $this->flag, $expire) : FALSE;
	}
	
	/**
	 * 移除缓存
	 * 
	 * @param string $key
	 * @return bool
	 */
	public function remove($key)
	{
		return $this->isConnected ? $this->mem->delete($key, 0) : FALSE;
	}
	
	/**
	 * 清空缓存
	 * 
	 * @return bool
	 */
	public function flush()
	{
		return $this->isConnected ? $this->mem->flush() : FALSE;
	}
}