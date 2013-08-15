<?php defined('SYSPATH') or die('No direct script access.');

class OrderBasic_Core extends My {
    protected $object_name = 'order_basic';

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

	public function get_orders_by_ordernum($order_num) {
		$obj = ORM::factory($this->object_name)->where('order_num', $order_num);
        $results = $obj->find_all();
        $return = array();
        foreach ($results as $result) {
            $return[] = $result->as_array();
        }
        return $return;
	}

	public function get_orders_by_uid($uid) {
		$obj = ORM::factory($this->object_name)->where('uid', $uid);
		$results = $obj->find_all();
		$return = array();
		foreach ($results as $result) {
			$return[] = $result->as_array();
		}
		return $return;
	}

	public function add_order($data) {
		$result_obj = ORM::factory($this->object_name);
		if($result_obj->validate($data, FALSE)) {
			$result_obj->save();
			return $result_obj->id;
		}
		else {
			return FALSE;
		}
	}

	public function get_user_order_count($uid) {
		return ORM::factory($this->object_name)->where('uid', $uid)->count_all();
	}
}