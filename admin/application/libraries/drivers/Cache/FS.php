<?php defined('SYSPATH') OR die('Access denied!');

/**
 * 本地文件缓存
 *
 * @author 王浩
 * @version 1.0
 */
class Cache_FS_Driver implements Cache_Interface_Driver {
	/**
	 * 缓存目录
	 * 
	 * @var string
	 */
	protected $direct = NULL;
	/**
	 * 子目录层次
	 * 
	 * @var int
	 */
	protected $layer = 3;
	/**
	 * 默认缓存过期时间
	 * 
	 * @var int
	 */
	protected $expire = 3600;
	/**
	 * 缓存文件后缀
	 * 
	 * @var string
	 */
	protected $suffix = '.c';
	
	/**
	 * 初始化
	 * 
	 * @param array $option  配置数组
	 */
	public function __construct($option)
	{
		empty($option['direct']) OR  $this->direct = rtrim(trim($option['direct']), '/');
		empty($option['layer'])  OR  $this->layer  = intval($option['layer']);
		empty($option['suffix']) OR  $this->suffix = $option['suffix'];
		
		is_null($option['expire']) OR $this->expire = intval($option['expire']);
		
		$this->expire < 0  AND $this->expire = 0;
		$this->layer  > 16 AND $this->layer  = 16;
		$this->layer  < 1  AND $this->layer  = 1;
	}
	
	/**
	 * 析构函数
	 */
	public function __destruct()
	{
	    
	}
	
	/**
	 * 读取缓存
	 * 
	 * @param string $key  缓存ID
	 * @return mixed
	 */
	public function get($key)
	{
		$filename = $this->getPath($key);
		
		if (file_exists($filename))
		{
			$content = @file_get_contents($filename);
			if ($content !== FALSE)
			{
				$position = strpos($content, '>');
				if ($position !== FALSE)
				{
					$verify = substr($content, 0, $position);
					$verify = explode(',', $verify);
					if (count($verify) === 2 AND ($verify[0] === '0' OR $verify[0] > time()))
					{
						$content = substr($content, $position + 1);
						if (md5($content) === $verify[1])
						{
							return unserialize($content);
						}
					}
				}
			}
		}
		
		return FALSE;
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
		$filename = $this->getPath($key);
		
		$expire = is_null($expire) ? $this->expire : intval($expire);
		
		$content = serialize($value);
		$content = ($expire > 0 ? time() + $expire : 0).','.md5($content).'>'.$content;
		
		$dirname = dirname($filename);
		if (is_dir($dirname) OR @mkdir($dirname, 0777, TRUE))
		{
			return @file_put_contents($filename, $content) ? TRUE : FALSE;
		} else {
			return FALSE;
		}
	}
	
	/**
	 * 移除缓存
	 * 
	 * @param string $key
	 * @return bool
	 */
	public function remove($key)
	{
		return file_exists($filename = $this->getPath($key)) ? @unlink($filename) : TRUE;
	}
	
	/**
	 * 清空缓存
	 * 
	 * @return bool
	 */
	public function flush()
	{
		return TRUE;
	}
	
	/**
	 * 通过缓存 ID 获取该缓存存储路径
	 * 
	 * @param string $key  缓存ID
	 * @return string
	 */
	protected function getPath($key)
	{
		static $filenames = array();
		
		if (!isset($filenames[$key]))
		{
			$md5 = md5($key);
			$dir = '';
			for ($i = 0; $i < $this->layer; $i++)
			{
				if ($dir !== '')
				{
					$dir .= '/';
				}
				$dir .= substr($md5, $i * 2, 2);
			}
			$filenames[$key] = $this->direct.'/'.$dir.'/'.$md5.$this->suffix;
		}
		
		return $filenames[$key];
	}
}