<?php defined('SYSPATH') OR die('No direct access allowed.');

require 'PHPMailer/class.phpmailer.php';

class Phpmail_Core{

    private static $instances = array();
	protected $config;
    public    $charset;

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
        $this->_charset = $charset;
        $this->config = Kohana::config('mail');
    }

	/**	
	 * @param   string  $mailto
	 * @param   string  $subject
	 * @param   string  $body
	 * @param   string  $encode
	 * @return  $this
	 */
	public function smtp_send_mail($mailto, $subject = '', $body = '', $encode = 'base64'){
        $messageID = date('Ymdhis').microtime(true).substr($this->config['username'], strrpos($this->config['username'], "@"));
        //$this->setFrom($this->config['username'], substr($this->config['username'], 0, strrpos($this->config['username'], "@")));
        //$this->addTo($mailto, substr($mailto, 0, strrpos($mailto, "@")));
        //$this->setSubject($subject);
        //$this->setBodyText(strip_tags($body), $this->_charset, $encode);
        //$this->setBodyHtml($body, $this->_charset, $encode);
        //$this->setMessageId($messageID);
        //$this->_storeHeader('X-mailer', 'Microsoft Office Outlook 12.0');
        
        try {
        	$mail = new PHPMailer(true);

        	$body             = preg_replace('/\\\\/','', $body);
            $mail->CharSet    = $this->_charset;
            $mail->Encoding   = $encode;
        	$mail->IsSMTP();                           // tell the class to use SMTP
        	$mail->SMTPAuth   = true;                  // enable SMTP authentication
        	$mail->Port       = $this->config['port'];                    // set the SMTP server port
        	$mail->Host       = $this->config['host']; // SMTP server
        	$mail->Username   = $this->config['username'];     // SMTP server username
        	$mail->Password   = $this->config['password'];            // SMTP server password
        	$mail->From       = $this->config['username'];
        	$mail->FromName   = substr($this->config['username'], 0, strrpos($this->config['username'], "@"));
        	$mail->AddReplyTo($mail->From, $mail->FromName);
            
        	$mail->AddAddress($mailto);
        	$mail->Subject  = $subject;

        	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        	$mail->WordWrap   = 80;  

        	$mail->MsgHTML($body);

        	$mail->IsHTML(true); 

        	$mail->Send();
        } catch (phpmailerException $e) {
        	return $e->errorMessage();
        }
        return $mail;
	}
	

}