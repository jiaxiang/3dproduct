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
class Aotu_order_Core{
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
            
            Kohana::log('debug', 'Aotu_order Library initialized');
        }
        
        return self::$instance[$dirver_name];
    }

}

