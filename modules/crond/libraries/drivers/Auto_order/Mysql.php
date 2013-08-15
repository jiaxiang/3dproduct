<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Crond_Mysql_Driver
 * 
 * @package   
 * @author Bin
 * @copyright qinbin
 * @version 2010
 * @access public
 */
class Auto_order_Mysql_Driver extends Auto_order_Driver {
    private $db;
    public function __construct(){
        $this->db = Database::instance();    
    }
    public function check($to,$subject,$message,$from){
        /* 得到smtp的配置信息 */
        //$crond = new Crond();
        //$smtp = $crond->get_smtp();
        $smtp = Kohana::config('mail');
        $email_task = $this->db->from('email_task')
                        ->set(array(
                            'username'  => $smtp['username'],
                            'password'  => $smtp['password'],
                            'host'      => $smtp['host'],
                            'port'      => $smtp['port'],
                            'auth'      => $smtp['port'],
                            'ssl'       => $smtp['port'],
                            'from'      => $from,
                            'recipient' => $to,
                            'title'     => $subject,
                            'message'   => $message,
                            'add_time'  => gmdate('Y-m-d H:i:s')
                        ))
                        ->insert();
                        
        $insert_id = $email_task->insert_id();
        if($insert_id <= 0){
            Kohana::log('error','MAIL TASK CAN NOT INSERT. SMPT:' . print_r($smtp,true));
            return 0;
        } else {
			/*$task_queue = $this->db->from('task_queue')
                            ->set(array(
                            	'task_id'  => $insert_id,
                            	'category' => 0,
                            	'add_time' => gmdate('Y-m-d H:i:s')
                            ))
                            ->insert();*/
            return $insert_id;
        }
    }

}