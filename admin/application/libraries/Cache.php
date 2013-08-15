<?php defined('SYSPATH') OR die('No direct access allowed!');

/**
 * 缓存组件
 *
 * @author 王浩
 * @version 1.0
 */
class Cache_Core {
    
    /**
     * 配置信息
     *
     * @var array
     */
    static protected $config = array();
    
    /**
     * 缓存驱动对象实例
     *
     * @var array
     */
    static protected $instances = array();
    
    /**
     * 数据缓冲
     *
     * @var array
     */
    static protected $buffer = array();
    
    /**
     * 指示对象是否已初始化
     *
     * @var boolean
     */
    static protected $initilize = FALSE;
    
    /**
     * 获取缓存驱动对象
     *
     * @param string $id
     * @return object
     */
    static public function factory($id = NULL)
    {
        self::initialize();
        
        empty($id) AND $id = 'default';
        
        if (!isset(self::$instances[$id]))
        {
            if (self::isExists($id))
            {
                $driver = sprintf(self::$config['driver'],  self::$config['sources'][$id]['driver']);
                //if (System::loadClass($driver))
                //{
                self::$instances[$id] = new $driver(self::$config['sources'][$id]['option']);
                //} else {
                //   throw new MyRuntimeException(sprintf('缓存驱动 "%s" 未找到！', $driver));
                //}
            } else {
                throw new MyRuntimeException(sprintf('缓存服务器 "%s" 未找到！', $id));
            }
        }
        
        return self::$instances[$id];
    }
    
    /**
     * 写入缓存
     *
     * @param string $key
     * @param mixed $value
     * @param integer $expire
     * @return boolean
     */
    static public function set($key, $value, $expire = NULL)
    {
        self::initialize();
        
        $key  = trim($key);
        $type = ($position = strpos($key, '.')) > 0
              ? substr($key, 0, $position)
              : $key;
        
        if (!self::isDenied($type))
        {
            $source = self::factory(self::router($type));
            unset(self::$buffer[$key]);
            return $source->set($key, $value, $expire);
        }
        
        return FALSE;
    }
    
    /**
     * 读取缓存
     *
     * @param string $key
     * @return mixed
     */
    static public function get($key)
    {
        self::initialize();
        
        $key  = trim($key);
        
        if (array_key_exists($key, self::$buffer))
        {
            return self::$buffer[$key];
        }
        
        $type = ($position = strpos($key, '.')) > 0
              ? substr($key, 0, $position)
              : $key;
        
        if (!self::isDenied($type))
        {
            $source = self::factory(self::router($type));
            $cache  = $source->get($key);
            
            if (isset(self::$config['buffer'][$type]) AND self::$config['buffer'][$type])
            {
                self::$buffer[$key] = $cache;
            }
            
            return $cache;
        }
        
        return FALSE;
    }
    
    /**
     * 移除缓存
     *
     * @param string $key
     * @return boolean
     */
    static public function remove($key)
    {
        self::initialize();
        
        $key  = trim($key);
        $type = ($position = strpos($key, '.')) > 0
              ? substr($key, 0, $position)
              : $key;
        
        $source = self::factory(self::router($type));
        unset(self::$buffer[$key]);
        return $source->remove($key);
    }
    
    /**
     * 检查缓存服务器是否存在
     *
     * @param string $id
     * @return boolean
     */
    static public function isExists($id)
    {
        self::initialize();
        
        return !empty(self::$config['sources'][$id]);
    }
    
    /**
     * 检查缓存类型是否被禁用
     *
     * @param string $type
     * @return boolean
     */
    static public function isDenied($type)
    {
        self::initialize();
        
        return isset(self::$config['denied'][$type]) AND self::$config['denied'][$type];
    }
    
    /**
     * 通过缓存类型获取其所存放的缓存服务器的ID
     *
     * @param string $type
     * @return string
     */
    static public function router($type)
    {
        self::initialize();
        
        if (isset(self::$config['routes'][$type]) AND self::isExists(self::$config['routes'][$type]))
        {
            $id = self::$config['routes'][$type];
            if (!isset(self::$config['sources'][$id]['enabled']) OR self::$config['sources'][$id]['enabled'])
            {
                return $id;
            }
        }
        
        return 'default';
    }
    
    /**
     * 组件初始化
     */
    static protected function initialize()
    {
        if (self::$initilize === FALSE)
        {
            self::$config    = Kohana::config('cache');
            self::$initilize = TRUE;
        }
    }
}