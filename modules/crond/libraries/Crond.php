<?php defined ( 'SYSPATH' ) or die ( 'No direct access allowed.' );
/**
 * Crond_Core
 * 
 * @package   
 * @author B2C_BASE
 * @copyright qinbin
 * @version 2010-11-12
 * @access public
 */
class Crond_Core{
    private static $instance = NULL;
    /**
     * 获取模块的实例
     * 
     * @param String $dirver_name
     * @return
     */
    public function get_instance($dirver_name = NULL)
    {
        if (!isset(self::$instance[$dirver_name])) {
            //driver启动
            $driver = 'Crond_' . ucfirst($dirver_name) . '_Driver';
            
            // Load the driver
            if (!Kohana::auto_load($driver))
                throw new Kohana_Exception('crond.driver_not_found', $dirver_name, get_class($this));
                
            // Initialize the driver
            self::$instance[$dirver_name] = new $driver();
            // Validate the driver
            if (!(self::$instance[$dirver_name] instanceof Crond_Driver))
                throw new Kohana_Exception('crond.driver_implements', $dirver_name, get_class($this), 'Crond_Driver');
            
            Kohana::log('debug', 'Crond Library initialized');
        }
        
        return self::$instance[$dirver_name];
    }
    
    
    /**
     * 得到SMTP信息
     * 
     * @return array()
     */
    public function get_smtp(){
        $smtp = array(
            'host' => 'smtp.jingbo365.com',
            'port' => 25,
            'username' => 'webmaster@jingbo365.com',
            'password' => '365jingbo500wan'
        );
        
        $smtp_queue = ORM::factory('smtp_queue')->orderby('sendnum','ASC')->find();
        if($smtp_queue->loaded){
            $smtp['host'] = $smtp_queue->host;
            $smtp['port'] = $smtp_queue->port;
            $smtp['username'] = $smtp_queue->username;
            $smtp['password'] = $smtp_queue->password;
            $smtp_queue->sendnum = $smtp_queue->sendnum + 1;
            $smtp_queue->save();
        }
        return $smtp;
    }
}

/**
 * CrondException
 * 
 * @package B2C_BASE
 * @author qinbin
 * @copyright 2010
 * @version $Id$
 * @access public
 */
class CrondException extends Exception {
    /**
     * 重定义构造器使 message 变为必须被指定的属性
     * 
     * @param mixed $message
     * @param integer $code
     * @return
     */
    public function __construct($message, $code = 0) {
        // 自定义的代码
        // 确保所有变量都被正确赋值
        parent::__construct ( $message, $code );
    }
    
    /**
     * 自定义字符串输出的样式
     * 
     * @return
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}