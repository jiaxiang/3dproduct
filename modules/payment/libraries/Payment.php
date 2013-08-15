<?php
defined('SYSPATH') or die('No direct access allowed.');
class Payment_Core {
    private static $instance = NULL;
    //获取支付模块的支付实例
    public function get_instance($dirver_name = NULL)
    {
        if (!isset(self::$instance[$dirver_name])) {
            //driver启动
            $driver = 'Payment_' . ucfirst($dirver_name) . '_Driver';
            
            // Load the driver
            if (!Kohana::auto_load($driver))
                throw new Kohana_Exception('payment.driver_not_found', $dirver_name, get_class($this));
                
            // Initialize the driver
            self::$instance[$dirver_name] = new $driver();
            // Validate the driver
            if (!(self::$instance[$dirver_name] instanceof Payment_Driver))
                throw new Kohana_Exception('core.driver_implements', $dirver_name, get_class($this), 'Payment_Driver');
            
            Kohana::log('debug', 'Payment Library initialized');
        }
        
        return self::$instance[$dirver_name];
    }
    
    /*可以添加一些支付模块的通用方法*/
}
