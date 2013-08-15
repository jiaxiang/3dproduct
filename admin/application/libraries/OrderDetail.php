<?php defined('SYSPATH') or die('No direct script access.');

class OrderDetail_Core extends My {
    protected $object_name = 'order_detail';

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

	public function get_orders_by_orderid($order_id) {
		$obj = ORM::factory($this->object_name)->where('order_id', $order_id);
        $results = $obj->find_all();
        $return = array();
        foreach ($results as $result) {
            $return[] = $result->as_array();
        }
        return $return;
	}

	public function get_order_by_id($id) {
		$where = array();
		$where['id'] = $id;
		$result_obj = ORM::factory($this->object_name)
		->where($where)
		->find();
		$return = $result_obj->as_array();
		if ($return['id'] > 0) {
			return $return;
		}
		return FALSE;
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

	public function update_status_by_id($id, $status) {
		$result_obj = ORM::factory($this->object_name, intval($id));
		if ($result_obj->loaded) {
			$result_obj->status = $status;
			$result_obj->save();
			if ($result_obj->saved == TRUE) {
				return TRUE;
			}
		}
		return FALSE;
	}
}