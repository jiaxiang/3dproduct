<?php
defined('SYSPATH') or die('No direct access allowed.');

class Mycurrency_Core extends My{
	//表名
	protected $object_name = 'currency';
	protected $data = array();
	protected $errors = NULL;
	
	private static $instances;

	public static function &instance($id = 0)
	{
		if(!isset(self::$instances[$id])){
			$class = __CLASS__;
			self::$instances[$id] = new $class($id);
		}
		return self::$instances[$id];
	}

	/**
	 * get currency data
	 *
	 * @return Array
	 */
	public function get_by_code($code)
	{
		$currency = ORM::factory('currency')->where(array(
			'code' => $code
		))
			->find()
			->as_array();
		return $currency;
	}

	/**
	 * 更新默认币种显示
	 *
	 * @return Array
	 */
	public function update_currencies_default()
	{
		if(!$this->data['id']){
			return FALSE;
		}
		//当前币种为默认币种
		if($this->data['default'] == 1 && $this->data['active']){
			$orm_list = ORM::factory('currency')->where('default',1)
				->where('id !=',$this->data['id'])
				->find_all();
			foreach($orm_list as $key=>$rs){
				$rs->default = 0;
				$rs->save();
			}
		} else{
			//当前币种不为默认币种
			$orm_list = ORM::factory('currency')
				->orderby(array(
				'active' => 'DESC'
			))
				->orderby(array(
				'default' => 'DESC',
				'id'      => 'ASC'
			))
				->find_all();
			foreach($orm_list as $key=>$rs){
				if($key){
					if($rs->default){
						$rs->default = 0;
						$rs->save();
					}
				} else{
					if(!$rs->default){
						$rs->default = 1;
						$rs->save();
					}
				}
			}
		}
		return TRUE;
	}

	/**
	 * get currency select list
	 *
	 * @param array $in
	 * @return array
	 */
	public function select_list()
	{
		$list = array();
		
		$orm = ORM::factory('currency');		
		
		$orm->where('active',1);
		$list = $orm->select_list('code','code');
		
		return $list;
	}

	/**
	 * 是否已经存在
	 * @param <array> $args
	 * @return <boolean>
	 */
	public function exist($data)
	{
		$where = array();
		$where['code'] = $data['code'];
		$count = ORM::factory('currency')->where($where)
			->count_all();
		if($count > 0){
			return TRUE;
		} else{
			return FALSE;
		}
	}

	/**
	 * 删除站点对应的币种信息
	 * 
	 * @param int $site_id
	 * @return boolean
	 */
	public function delete_by_site($site_id)
	{
		$carrier_range = ORM::factory('currency');
		$carrier_range->delete_all();
		return false;
	}

	/**
	 * 检查并更新站点可用币种，保证站点有一个可用币种
	 *
	 * @return boolean
	 */
	public function check_currencies_active()
	{
		$count = ORM::factory('currency')
			->where('active',1)
			->count_all();
		if($count){
			return TRUE;
		} else{
			$currency = ORM::factory('currency',$this->data['id']);
			$currency->active = 1;
			$currency->save();
			return FALSE;
		}
	}
}
