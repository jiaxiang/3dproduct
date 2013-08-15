<?php defined('SYSPATH') or die('No direct script access.');

class User_Core extends My {
    protected $object_name = 'user';

	private static $instances;

	public $obj_db;

	public function __construct() {
		$this->obj_db = Database::instance();
	}

	public static function & instance($id = 0) {
		if (!isset(self::$instances[$id])) {
			$class = __CLASS__;
			self::$instances[$id] = new $class($id);
		}
		return self::$instances[$id];
	}

	public function get_user_by_email($email) {
		$where = array();
		$where['email'] = $email;
		$result_obj = ORM::factory($this->object_name)
		->where($where)
		->find();
		$return = $result_obj->as_array();
		if ($return['id'] > 0) {
			return $return;
		}
		return FALSE;
	}

	public function get_user_by_username($username) {
		$where = array();
		$where['username'] = $username;
		$result_obj = ORM::factory($this->object_name)
		->where($where)
		->find();
		$return = $result_obj->as_array();
		if ($return['id'] > 0) {
			return $return;
		}
		return FALSE;
	}

	public function get_user_by_uid($uid) {
		$where = array();
		$where['id'] = $uid;
		$result_obj = ORM::factory($this->object_name)
		->where($where)
		->find();
		$return = $result_obj->as_array();
		if ($return['id'] > 0) {
			return $return;
		}
		return FALSE;
	}

	public function add_user($data) {
		//d($data);
		$result_obj = ORM::factory($this->object_name);
		//d($result_obj->validate($data, FALSE));
		if($result_obj->validate($data, FALSE)) {
			$result_obj->save();
			return $result_obj->id;
		}
		else {
			return FALSE;
		}
	}

	public function update_user_status($uid, $status) {
		$result_obj = ORM::factory($this->object_name, $uid);
		if ($result_obj->loaded) {
			$result_obj->status = $status;
			$result_obj->save();
			if ($result_obj->saved == TRUE) {
				return TRUE;
			}
		}
		return FALSE;
	}

	public function update_user_lastlogin($uid, $time) {
		$result_obj = ORM::factory($this->object_name, $uid);
		if ($result_obj->loaded) {
			$result_obj->lastlogin_time = $time;
			$result_obj->save();
			if ($result_obj->saved == TRUE) {
				return TRUE;
			}
		}
		return FALSE;
	}

	public function update_user_mobile($uid, $mobile) {
		$result_obj = ORM::factory($this->object_name, $uid);
		if ($result_obj->loaded) {
			$result_obj->mobile = $mobile;
			$result_obj->save();
			if ($result_obj->saved == TRUE) {
				return TRUE;
			}
		}
		return FALSE;
	}

	public function update_user_name($uid, $name) {
		$result_obj = ORM::factory($this->object_name, $uid);
		if ($result_obj->loaded) {
			$result_obj->name = $name;
			$result_obj->save();
			if ($result_obj->saved == TRUE) {
				return TRUE;
			}
		}
		return FALSE;
	}
}