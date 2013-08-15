<?php defined('SYSPATH') OR die('No direct access allowed.');

class Crond_test_Controller extends Controller{
    public function index(){
        /**
         * 邮件消息加入队列(即时邮件)
         * 
         * @param mixed $to
         * @param mixed $subject
         * @param mixed $message
         * @param mixed $from
         * @return void
         */
        $add_mail_task = Crond::get_instance('mysql')->add_mail_task('qinbin@live.com','test mail task','test mail message','jsrgqinbin@gmail.com');
    	var_dump($add_mail_task);
        echo "<hr/>";
        exit;
        /**
         * 邮件消息加入队列(定时邮件)
         * 
         * @param mixed $to
         * @param mixed $subject
         * @param mixed $message
         * @param mixed $from
         * @param mixed $interval_time
         * @param mixed $exec_time
         * @return void
         */
        $add_mail_crond = Crond::get_instance('mysql')->add_mail_crond('qinbin@live.com','test mail task','test mail message','jsrgqinbin@gmail.com',60,'2010-11-30 12:00:12');
        var_dump($add_mail_crond);
        echo "<hr/>";
        /**
         * url触发加入队列(即时触发)
         * 
         * @param mixed $url
         * @param mixed $smtp
         * @return void
         */
        $add_url_task = Crond::get_instance('mysql')->add_url_task('http://74.207.242.41:80/put/test');
        var_dump($add_url_task);
        echo "<hr/>";
    	/**
         * url触发加入队列(定时触发)
         * 
         * @param mixed $url
         * @param mixed $interval_time
         * @param mixed $exec_time
         * @param mixed $smtp
         * @return void
         */
        $add_url_crond = Crond::get_instance('mysql')->add_url_crond('http://74.207.242.41:80/put/test',60,'2010-11-30 12:00:12');
        var_dump($add_url_crond);
        exit('1');
    }
}