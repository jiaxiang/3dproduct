<?php defined('SYSPATH') or die('No direct script access.');

class userfunc_Core {
    private static $instance = NULL;

    const USERNAME_LEN_MIN = 3;
    const USERNAME_LEN_MAX = 20;
    const PASSWD_LEN_MIN = 6;
    const PASSWD_LEN_MAX = 20;
    // 获取单态实例
    public static function get_instance() {
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }

    public function check_username_rule($username) {
    	if (mb_strlen($username) > self::USERNAME_LEN_MAX
    	|| mb_strlen($username) < self::USERNAME_LEN_MIN) {
    		return FALSE;
    	}
    	return TRUE;
    }

    public function check_email_rule($email) {
    	if (!preg_match("/^([a-zA-Z0-9\._-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/", $email)) {
    		return FALSE;
    	}
    	return TRUE;
    }

    public function check_passwd_rule($passwd) {
    	if (strlen($passwd) > self::PASSWD_LEN_MAX
    	|| strlen($passwd) < self::PASSWD_LEN_MIN) {
    		return FALSE;
    	}
    	return TRUE;
    }

    public function encrypt_passwd($passwd) {
    	$secret = Kohana::config('site_config.site.secret_pwd');
    	return Mytool::hash(Mytool::hash($passwd).$secret);
    }

    public function encrypt_regmail_key($uid, $email) {
    	$mail_check_pwd = Kohana::config('site_config.site.register_mail_check_pwd');
    	$key = Mytool::hash(Mytool::hash(Mytool::hash($mail_check_pwd).$email).$uid);
    	return $key;
    }

    public function create_token($email) {
    	$secret = Kohana::config('site_config.site.secret_tk');
    	return md5(md5($email).md5($secret).time());
    }

    public function send_reg_mail($uid, $username, $email) {
    	$key = $this->encrypt_regmail_key($uid, $email);
    	mail::check_register($uid, $email, $username, $key);
    }

    public function check_reg_mail($uid, $email, $key) {
    	if ($this->encrypt_regmail_key($uid, $email) == $key) {
    		return TRUE;
    	}
    	return FALSE;
    }

	public function is_login() {
		$session = Session::instance();
		$user = $session->get('USER');
		if ($user) {
			return $user;
		}
		else {
        	return FALSE;
		}
    }
}