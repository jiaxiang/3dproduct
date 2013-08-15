<?php
defined('SYSPATH') or die('No direct access allowed.');

abstract class My_Core
{
	//表名
	protected $object_name = NULL;
	//数据成员记录单体数据
	protected $data = array();
	//记录Service中的错误信息
	protected $error = array();
	protected $orm ;

	/**
	 * Construct load data
	 *
	 * @param Int $id
	 */
	protected function __construct($id)
	{
		$this->_load($id);
	}

	/**
	 * load data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
        $this->orm || $this->orm = ORM::factory($this->object_name,$id);
		$this->data = $this->orm->as_array();
	}

	/**
	 * get the total number
	 *
	 * @param Array $query_struct
	 * @return Int
	 */
	public function query_count($query_struct = array())
	{
		$orm_instance = ORM::factory($this->object_name);
		// 处理输入条件
		$where = array();
		$in = array();
		if(isset($query_struct['where'])&&is_array($query_struct['where']))
		{
			foreach($query_struct['where'] as $key=>$condition)
			{
				if(is_array($condition))
				{
					$in[$key] = $condition;
				}
				else
				{
					$where[$key] = $condition;
				}
			}
		}
		// 处理 where 模块
		if(!empty($where))
		{
			$orm_instance->where($where);
		}
		// 处理 not_in 模块
		if(!empty($query_struct['not_in']) && is_array($query_struct['not_in']) && count($query_struct['not_in']) > 0)
		{
			foreach($query_struct['not_in'] as $notin_key=>$notin_val)
			{
				$orm_instance->notin($notin_key,$notin_val);
			}
		}
		//处理IN模块
		if(!empty($in))
		{
			foreach($in as $in_key=>$in_val)
			{
				$orm_instance->in($in_key,$in_val);
			}
		}
		
		// 处理 like 模块
		if(isset($query_struct['like'])&&is_array($query_struct['like'])&&count($query_struct['like']))
		{
			$orm_instance->like($query_struct['like']);
		}
		// 处理 orlike 模块
		if(isset($query_struct['orlike'])&&is_array($query_struct['orlike'])&&count($query_struct['orlike']))
		{
			$orm_instance->orlike($query_struct['orlike']);
		}
		return $orm_instance->count_all();
	}

	/**
	 * 多条数据结构体
	 *
	 * @param Array $query_struct
	 * @return Array
	 */
	public function query_assoc($query_struct = array())
	{
		$list = array();
		$orm_instance = ORM::factory($this->object_name);
		// 处理输入条件
		$where = array();
		$in = array();
		if(isset($query_struct['where'])&&is_array($query_struct['where']))
		{
			foreach($query_struct['where'] as $key=>$condition)
			{
				if(is_array($condition))
				{
					$in[$key] = $condition;
				}
				else
				{
					$where[$key] = $condition;
				}
			}
		}
		// 处理 where 模块
		if(!empty($where))
		{
			$orm_instance->where($where);
		}
		//处理IN条件
		if(!empty($in))
		{
			foreach($in as $in_key=>$in_val)
			{
				$orm_instance->in($in_key,$in_val);
			}
		}
		// 处理 not_in 模块
		if(!empty($query_struct['not_in']) && is_array($query_struct['not_in']) && count($query_struct['not_in']) > 0)
		{
			foreach($query_struct['not_in'] as $notin_key=>$notin_val)
			{
				$orm_instance->notin($notin_key,$notin_val);
			}
		}
		//处理传入in条件
		if(isset($query_struct['in'])&&is_array($query_struct['in']))
		{
			foreach($query_struct['in'] as $key=>$value)
			{
				$orm_instance->in($key,$value);
			}
		}
		// 处理 like 模块
		if(isset($query_struct['like'])&&is_array($query_struct['like'])&&count($query_struct['like']))
		{
			$orm_instance->like($query_struct['like']);
		}
		// 处理 orlike 模块
		if(isset($query_struct['orlike'])&&is_array($query_struct['orlike'])&&count($query_struct['orlike']))
		{
			$orm_instance->orlike($query_struct['orlike']);
		}
		// 处理 orderby 模块
		if(isset($query_struct['orderby'])&&is_array($query_struct['orderby'])&&count($query_struct['orderby']))
		{
			$orm_instance->orderby($query_struct['orderby']);
		}
		//处理limit条件，无条件最多查询1000条数据
		$limit = isset($query_struct['limit']['per_page']) ? $query_struct['limit']['per_page'] : 1000;
		$offset = isset($query_struct['limit']['offset']) ? $query_struct['limit']['offset'] : 0;
		$orm_list = $orm_instance->find_all($limit,$offset);
		//得到返回结构体
		foreach($orm_list as $item)
		{
			$list[] = $item->as_array();
		}
		return $list;
	}

	/**
	 * select list
	 *
	 * @param Array $query_struct
	 * @param String $key_select
	 * @param String $val_select
	 * @return Array
	 */
	public function select_list($query_struct,$key_select,$val_select)
	{
		$list = array();
		$orm_instance = ORM::factory($this->object_name);
		// 处理输入条件
		$where = array();
		$in = array();
		if(isset($query_struct['where'])&&is_array($query_struct['where']))
		{
			foreach($query_struct['where'] as $key=>$condition)
			{
				if(is_array($condition))
				{
					$in[$key] = $condition;
				}
				else
				{
					$where[$key] = $condition;
				}
			}
		}
		// 处理 where 模块
		if(!empty($where))
		{
			$orm_instance->where($where);
		}
		if(!empty($in))
		{
			foreach($in as $in_key=>$in_val)
			{
				$orm_instance->in($in_key,$in_val);
			}
		}
		
		// 处理 like 模块
		if(isset($query_struct['like'])&&is_array($query_struct['like'])&&!empty($query_struct['like']))
		{
			$orm_instance->like($query_struct['like']);
		}
		// 处理 orderby 模块
		if(isset($query_struct['orderby'])&&is_array($query_struct['orderby'])&&!empty($query_struct['orderby']))
		{
			$orm_instance->orderby($query_struct['orderby']);
		}
		// 处理 select_list 模块
		return $orm_instance->select_list($key_select,$val_select);
	}

	/**
	 * get data
	 *
	 * @return Array
	 */
	public function get($key = NULL)
	{
		if(empty($key))
		{
			return $this->data;
		}
		else
		{
			return isset($this->data[$key]) ? $this->data[$key] : '';
		}
	}

	/**
	 * add a site
	 *
	 * @param Array $data
	 * @return Array
	 */
	public function add($data)
	{	
		$orm_instance = ORM::factory($this->object_name);
		$errors = '';
		if($orm_instance->validate($data,TRUE,$errors))
		{
			$this->data = $orm_instance->as_array();
			return $orm_instance->id;
		}
		else
		{
			$this->error[] = $errors;
			return FALSE;
		}
	}

	/**
	 * edit a item
	 *
	 * @param Array $data
	 * @param Int $id
	 * @return Array
	 */
	public function edit($data)
	{
		$id = isset($data['id'])&&intval($data['id']) ? $data['id'] : $this->data['id'];
		$orm_instance = ORM::factory($this->object_name,$id);
		if(!$orm_instance->loaded)
		{
			$this->error[] = 'Data load failed , object : '.$this->object_name.' , id : '.$id;
			return FALSE;
		}
		
		$errors = '';
		if($orm_instance->validate($data,TRUE,$errors))
		{
			$this->data = $orm_instance->as_array();
			return TRUE;
		}
		else
		{
			$this->error[] = $errors;
			return FALSE;
		}
	}

	/**
	 * delete a item
	 *
	 * @param Int $id
	 * @return Boolean
	 */
	public function delete($id = 0)
	{
		$id = $id ? $id : $this->data['id'];
		
		$orm_instance = ORM::factory($this->object_name,$id);
		
		if(!$orm_instance->loaded)
		{
			$this->error[] = 'Data load failed , object : '.$this->object_name.' , id : '.$id;
			return FALSE;
		}
		else
		{
			$orm_instance->delete();
			return TRUE;
		}
	}

	/**
	 * 删除站点所有的信息
	 * 
	 * @param int $site_id
	 * @return boolean
	 */
	public function delete_by_site($site_id)
	{
		//删除物流信息
		$obj = ORM::factory($this->object_name);
		$obj->delete_all();
		
		return true;
	}
	
	/**
	 * 列表
	 * @param unknown_type $id
	 */
    public function lists($query_struct = array()){
        return $this->query_assoc($query_struct);
    }
    
	/**
	 * 查询条件的记录数量
	 * @param array $query_struct
	 */
    public function count($query_struct = array()){
        return $this->query_count($query_struct);
    }
    
	/**
	 * get api error
	 *
	 * @return Array
	 */
	public function error()
	{
		$result = '';
		if(count($this->error))
		{
			$result = '<br />';
			foreach($this->error as $key=>$value)
			{
				if(is_array($value))
				{
					
					foreach($value as $key1=>$value1)
					{
						$result .= $key1.' . '.$value1.'<br />';
					}
				}
				else
				{
					$result .= ($key+1).' . '.$value.'<br />';
				}
			}
		}
		return $result;
	}
	

	/**
	 * Batch insert object to DB.
	 * @param array $items
	 * $itmes format
	 * array
	 * (
	 * 		array($fieldName=>$fieldValue,
	 * 			  $fieldName=>$fieldValue, ...)
	 * )
	 */
	public function batch_insert($items=array())
	{
		if ($items == null || count($items) == 0) {
			return false;
		}
		$db = Database::instance('default');
		$fieldList = $db->list_fields($this->object_name.'s');
		if (!isset($fieldList) || count($fieldList) == 0) {
			return false;
		}
		//		d($field_array, true);
	
		// constructed the field string
		$fieldString = ' (';
		foreach ($fieldList as $fieldName => $fieldAttr)
		{
			$fieldString = $fieldString.$fieldName.',';
		}
		$fieldString = substr($fieldString, 0, strlen($fieldString)-1 );
		$fieldString = $fieldString.') ';
	
		// constructed the value string for each item
	
		$itemValueList = array();
		foreach ($items as $aItem)
		{
			$aItemValue = '(';
			foreach ($fieldList as $fieldName => $fieldAttr)
			{
				if ($fieldName == 'id'){
					$aItemValue = $aItemValue.'0,';
					continue;
				}
	
				if ($fieldAttr['type'] == 'string') {
					if ($aItem[$fieldName] == null){
						$aItemValue = $aItemValue.'null,';
					}else {
						$aItemValue = $aItemValue.'\''.$aItem[$fieldName].'\',';
					}
					//$aItemValue = $aItemValue.'\''.$aItem[$fieldName].'\',';
				}else if ($fieldAttr['type'] == 'int') {
					$aItemValue = $aItemValue.$aItem[$fieldName].',';
				}else {
					$aItemValue = $aItemValue.$aItem[$fieldName].',';
				}
			}
			$aItemValue = substr($aItemValue, 0, strlen($aItemValue)-1 );
			$aItemValue = $aItemValue.')';
				
			$itemValueList[] = $aItemValue;
		}
	
		$valueString = '';
		$count = 0;
		foreach ($itemValueList as $aItemValue)
		{
			// append a item value
			$valueString = $valueString.$aItemValue.',';
			$count ++;
			// commit per 1000 items
			if ($count % 1000 == 0)
			{
				$valueString = substr($valueString, 0, strlen($valueString)-1 );
				$sql = 'INSERT INTO '.$this->object_name.'s '
				.$fieldString.' VALUES '.$valueString;
				//d($sql);
				$result = $db->query($sql);
				$valueString = '';	//clear the buff.
			}
		}
		if (strlen($valueString) > 1)
		{
			$valueString = substr($valueString, 0, strlen($valueString)-1 );
			$sql = 'INSERT INTO '.$this->object_name.'s '
			.$fieldString.' VALUES '.$valueString;
			//d($sql);
			$result = $db->query($sql);
		}
	
		return true;
	}
	
	/**
	 * Batch delete from DB by where condition array
	 * @param array $where
	 * $where = array(
	 * 		'flag' => 2,
	 * 		'name' => 'amazon',
	 * 		'id'   => array(1, 2, 3, 4, ... ),
	 * 		...
	 * );
	 */
	public function batch_delete($where=array())
	{
		if ($where === null || count($where) == 0) {
			return false;
		}
		$db = Database::instance('default');
	
		$whereString = ' 1=1 ';
		foreach($where as $key=>$condition)
		{
			// it is a key=value condition
			if(!is_array($condition))
			{
				if (strpos($key, '>') === false &&
						strpos($key, '<') === false)
				{
					$whereString = $whereString.' AND '.$key.'='.$condition;
				}
				else {
					$whereString = $whereString.' AND '.$key.$condition;
				}
				continue;
			}
				
			// it is a IN condition
			$inCondition = '(';
			foreach($condition as $aInItem)
			{
				$inCondition = $inCondition.$aInItem.',';
			}
			$inCondition = substr($inCondition, 0, strlen($inCondition)-1 );
			$inCondition = $inCondition.')';
			$whereString = $whereString.' AND '.$key.' IN '.$inCondition;
		}
	
		$sql = 'DELETE FROM '.$this->object_name.'s WHERE '.$whereString;
		//		d($sql, true);
		$result = $db->query($sql);
		return true;
	}
	
	/**
	 * Batch update object by where array
	 * @param array() $newValues
	 * $newValues = array(
	 * 		'flag' => 4,
	 * 		'name' => 'newName',
	 * 		...
	 * );
	 * @param array() $where
	 * $where = array(
	 * 		'flag' => 2,
	 * 		'name' => 'amazon',
	 * 		'id'   => array(1, 2, 3, 4, ... ),
	 * 		...
	 * );
	 */
	public function batch_update($newValues=array(), $where=array())
	{
		if ($where == null || count($where) == 0) {
			return false;
		}
		if ($newValues == null || count($newValues) == 0) {
			return false;
		}
		$db = Database::instance('default');
		$fieldList = $db->list_fields($this->object_name.'s');
		if (!isset($fieldList) || count($fieldList) == 0) {
			return false;
		}
	
		// set the update string
		$updateString = '';
		foreach($newValues as $key=>$value)
		{
			if (!isset($fieldList[$key])) {
				return false;
			}
			$fieldAttr = $fieldList[$key];
			if ($fieldAttr['type'] == 'string') {
				$updateString = $updateString.$key.'=\''.$value.'\',';
			}else if ($fieldAttr['type'] == 'int') {
				$updateString = $updateString.$key.'='.$value.',';
			}else {
				$updateString = $updateString.$key.'='.$value.',';
			}
		}
		$updateString = substr($updateString, 0, strlen($updateString)-1 );
	
		// set the where string
		$whereString = ' 1=1 ';
		foreach($where as $key=>$condition)
		{
			// it is a key=value condition
			if(!is_array($condition))
			{
				if (strpos($key, '>') === false &&
						strpos($key, '<') === false)
				{
					$whereString = $whereString.' AND '.$key.'='.$condition;
				}
				else {
					$whereString = $whereString.' AND '.$key.$condition;
				}
				continue;
			}
				
			// it is a IN condition
			$inCondition = '(';
			foreach($condition as $aInItem)
			{
				$inCondition = $inCondition.$aInItem.',';
			}
			$inCondition = substr($inCondition, 0, strlen($inCondition)-1 );
			$inCondition = $inCondition.')';
			$whereString = $whereString.' AND '.$key.' IN '.$inCondition;
		}
	
		$sql = 'UPDATE '.$this->object_name.'s SET '.$updateString.' WHERE '.$whereString;
		//		d($sql, true);
		$result = $db->query($sql);
		return true;
	}
	
}
