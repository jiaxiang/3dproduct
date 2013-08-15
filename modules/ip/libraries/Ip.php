<?php
defined ( 'SYSPATH' ) or die ( 'No direct access allowed.' );
class Ip_Core {
    protected $config;
    /**
     * Create an instance of Ip.
     *
     * @return  object
     */
    public function __construct() {
        $this->config = Kohana::config ( 'ip' );
        define('IPDATA_MINI', $this->config['IPDATA_MINI']);
        define('IPDATA_FULL', $this->config['IPDATA_FULL']);
        define('CHARSET', $this->config['CHARSET']);
        $driver = 'Ip_Ip2area_Driver';
        if (! Kohana::auto_load ( $driver ))
        throw new Kohana_Exception ( 'core.driver_not_found', $this->config ['driver'], get_class ( $this ) );
        $this->driver = new $driver ( );
    }
    public function get($ip){
        return $this->driver->get($ip);
    }
}
?>