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
class Crond_Mysql_Driver extends Crond_Driver {
    private $db;
    public function __construct(){
        $this->db = Database::instance();    
    }
    /**
     * 邮件消息加入队列(即时邮件)
     * 
     * @param mixed $to
     * @param mixed $subject
     * @param mixed $message
     * @param mixed $from
     * @param mixed $smtp
     * @return void
     */
    public function add_mail_task($to,$subject,$message,$from){
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

	/**
     * 邮件消息加入队列(定时邮件)
     * 
     * @param mixed $to
     * @param mixed $subject
     * @param mixed $message
     * @param mixed $from
     * @param mixed $smtp
     * @param mixed $interval_time
     * @param mixed $exec_time
     * @return void
     */
    public function add_mail_crond($to,$subject,$message,$from,$interval_time,$exec_time){
        /* 得到smtp的配置信息 */
        $crond = new Crond();
        $smtp = $crond->get_smtp();
        
        $email_task = $this->db->from('email_task')
                        ->set(array(
                            'username'  => $smtp['username'],
                            'password'  => $smtp['password'],
                            'host'      => $smtp['host'],
                            'port'      => $smtp['port'],
                            'from'      => $from,
                            'recipient' => $to,
                            'title'     => $subject,
                            'message'   => $message,
                            'add_time'  => gmdate('Y-m-d H:i:s')
                        ))
                        ->insert();
        $insert_id = $email_task->insert_id();
        if($insert_id <= 0){
            Kohana::log('error','MAIL CROND CAN NOT INSERT. SMPT:' . print_r($smtp,true));
            return 0;
        } else {
			$crond_queue = $this->db->from('crond_queue')
                                ->set(array(
                                	'task_id'       => $insert_id,
                                	'category'      => 0,
                                	'interval_time' => $interval_time,
                                	'exec_time'     => $exec_time,
                                	'add_time'      => gmdate('Y-m-d H:i:s')
                                ))
                                ->insert();
            return $insert_id;
        }
    }

    /**
     * url触发加入队列(即时触发)
     * 
     * @param mixed $url
     * @param mixed $smtp
     * @return void
     */
    public function add_url_task($url){
        $url_task = $this->db->from('fetch_task')
                        ->set(array(
                            'url'   => $url,
                            'add_time'  => gmdate('Y-m-d H:i:s')
                        ))
                        ->insert();
        $insert_id = $url_task->insert_id();
        if($insert_id <= 0){
            Kohana::log('error','URL TASK CAN NOT INSERT.');
            return 0;
        } else {
			$task_queue = $this->db->from('task_queue')
                            ->set(array(
                            	'task_id'  => $insert_id,
                            	'category' => 1,
                            	'add_time' => gmdate('Y-m-d H:i:s')
                            ))
                            ->insert();
            return $insert_id;
        }
    }

	/**
     * url触发加入队列(定时触发)
     * 
     * @param mixed $url
     * @param mixed $interval_time
     * @param mixed $exec_time
     * @param mixed $smtp
     * @return void
     */
    public function add_url_crond($url,$interval_time,$exec_time){
        $url_task = $this->db->from('fetch_task')
                        ->set(array(
                            'url'   => $url,
                            'add_time'  => gmdate('Y-m-d H:i:s')
                        ))
                        ->insert();
        $insert_id = $url_task->insert_id();
        if($insert_id <= 0){
            Kohana::log('error','URL CROND CAN NOT INSERT');
            return 0;
        } else {
			$crond_queue = $this->db->from('crond_queue')
                            ->set(array(
                            	'task_id'       => $insert_id,
                            	'category'      => 1,
                            	'interval_time' => $interval_time,
                            	'exec_time'     => $exec_time,
                            	'add_time'      => gmdate('Y-m-d H:i:s')
                            ))
                            ->insert();
            return $insert_id;
        }
    }
}