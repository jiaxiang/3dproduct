<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Mail_Core
 * File encode UTF-8
 * $Id: Mail_Core.php 1 2010-12-15 00:48:56 zhu $
 *
 * @package    mail
 * @author     Ketai Team
 * @copyright  (c) 2007-2008 Ketai Team
 * @license    http://ketai.com/license.html
 */
// Ensure Zend/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(dirname(__FILE__)),
    get_include_path()
)));
require 'Zend/ZendMail.php';

class Mail_z_Core extends Zend_Mail{

    private static $instances = array();
	protected $config;
	protected $driver;

    /**
     * 多实例方法
     *
     * @param $id
     */
    public static function & instance($id = 0, $charset = "UTF-8")
    {
        if (!isset(self::$instances[$id]))
        {
            $class = __CLASS__;
            self::$instances[$id] = new $class($charset);
        }
        return self::$instances[$id];
    }

    /**
     * Create an instance of Ip.
     *
     * @return  object
     */
    public function __construct($charset = NULL) 
    {
        parent::__construct($charset);
        $this->config = Kohana::config('mail');
        Zend_Loader::loadClass("Zend_Mail_Transport_Smtp");
        $this->driver = new Zend_Mail_Transport_Smtp($this->config['host'], $this->config);
    }
	
	public function send()
	{
        return parent::send($this->driver);
    }

	/**	
	 * @param   string  $mailto
	 * @param   string  $subject
	 * @param   string  $body
	 * @param   string  $encode
	 * @return  $this
	 */
	public function smtp_send_mail($mailto, $subject = '', $body = '', $encode = Zend_Mime::ENCODING_BASE64){
        $messageID = date('Ymdhis').microtime(true).substr($this->config['username'], strrpos($this->config['username'], "@"));
        $this->setFrom($this->config['username'], substr($this->config['username'], 0, strrpos($this->config['username'], "@")));
        $this->addTo($mailto, substr($mailto, 0, strrpos($mailto, "@")));
        $this->setSubject($subject);
        $this->setBodyText(strip_tags($body), $this->_charset, $encode);
        $this->setBodyHtml($body, $this->_charset, $encode);
        $this->setMessageId($messageID);
        $this->_storeHeader('X-mailer', 'Microsoft Office Outlook 12.0');
        $this->send();
        return $this;
	}
	

} // End Mail_Core