<?php
defined('SYSPATH') or die('No direct access allowed.');
/**
 * 数据服务
 * @package feedback
 * @author nickfan<nickfan81@gmail.com>
 * @link http://feedback.ketai-cluster.com
 * @version $Id: MyAttachmentService.php 227 2010-04-14 12:55:01Z zhubin $
 */
abstract class DefaultService_Core {
    //对象名称(表名)
    protected $object_name = '';
    
    //**  以下为共用方法和变量，请勿修改除非您知道自己在做什么，业务逻辑请加到类最后处理 **//
    protected $orm_instance = NULL;
    protected static $instances = array ();
    protected $test_mode = FALSE;     //调试模式开关
    
    
    // FIXME 兼容旧版5.2写法 用 protected 理论上 5.3 应该用 private
    /**
     * 单实例方法
     * @param $id
     */
    public function __construct()
    {
        $this->object_name = strtolower(substr(get_class($this), 0, -12)); // 0 -7 : Service
        return $this;
    }
    
    /**
     * 获取公共实例
     */
    private function get_orm_instance()
    {
        if(is_null($this->orm_instance)){
            $this->orm_instance = ORM::factory($this->object_name);
        }
        return $this->orm_instance;
    }
    
    /**
     * 查询数据列表
     * @param array $query_struct
     * @return array
     * @throws MyRuntimeException
     */
    public function query_assoc($query_struct)
    {        
        try
        {
            $orm_instance = ORM::factory($this->object_name);

            if(isset($query_struct['and_where']) && is_array($query_struct['and_where']))
            {
                $orm_instance->open_paren();
                
                foreach($query_struct['and_where'] as $key => $condition)
                {
                    if (is_array($condition))
                    {
                        $orm_instance->in($key, $condition);
                    }
                    else
                    {
                    	if (is_numeric($key))
                        {
                        	$orm_instance->where($condition);
                        } else {
                        	$orm_instance->where($key, $condition);
                        }
                    }
                }
                $orm_instance->close_paren();
            }

            if(isset($query_struct['or_where']) && is_array($query_struct['or_where']))
            {
                $orm_instance->open_paren();
                foreach($query_struct['or_where'] as $key => $condition)
                {
                    if (is_array($condition))
                    {
                        $orm_instance->in($key, $condition);
                    }
                    else
                    {
                    	if (is_numeric($key))
                        {
                        	$orm_instance->orwhere($condition);
                        } else {
                        	$orm_instance->orwhere($key, $condition);
                        }
                    }
                }
                $orm_instance->close_paren();
            }           
            
            if(isset($query_struct['where']) && is_array($query_struct['where']))
            {
                foreach($query_struct['where'] as $key => $condition)
                {
                    if (is_array($condition))
                    {
                        $orm_instance->in($key, $condition);
                    }
                    else
                    {
                    	if (is_numeric($key))
                        {
                        	$orm_instance->where($condition);
                        } else {
                        	$orm_instance->where($key, $condition);
                        }
                    }
                }
            }
            
            if(isset($query_struct['orwhere']) && is_array($query_struct['orwhere']))
            {
                foreach($query_struct['orwhere'] as $key => $condition)
                {
                    if(is_array($condition))
                    {
                        $orm_instance->in($key, $condition);
                    }
                    else
                    {
                    	if (is_numeric($key))
                        {
                        	$orm_instance->orwhere($condition);
                        } else {
                        	$orm_instance->orwhere($key, $condition);
                        }
                    }
                }
            }
            
            if(isset($query_struct['notin']) && is_array($query_struct['notin']))
            {
                foreach($query_struct['notin'] as $key => $condition)
                {
                    if(is_array($condition))
                    {
                        $orm_instance->notin($key, $condition);
                    }
                    else
                    {
                    	if (is_numeric($key))
                        {
                        	$orm_instance->orwhere($condition);
                        } else {
                        	$orm_instance->orwhere($key, $condition);
                        }
                    }
                }
            }

            //* 处理 like 模块
            if(isset($query_struct['like']) && is_array($query_struct['like']) && !empty($query_struct['like']))
            {
                foreach($query_struct['like'] as $field => $regex)
                {
                    $field = trim($field);
                    $position = strpos($field, ' ');
                    $sign = '';
                    if($position > 0){
                        $sign = trim(substr($field, $position + 1));
                        $field = substr($field, 0, $position);
                    }
                    $regexs = is_array($regex) ? $regex : array (
                        $regex 
                    );
                    foreach($regexs as $val)
                    {
                        switch(strtoupper($sign))
                        {
                            case 'OR':
                                $orm_instance->orlike($field, $val);
                                break;
                            case 'NOT':
                                $orm_instance->notlike($field, $val);
                                break;
                            case 'ORNOT':
                                $orm_instance->ornotlike($field, $val);
                                break;
                            default:
                                $orm_instance->like($field, $val);
                        }
                    }
                }
            }
            
            // 处理 regex 模块
            if(isset($query_struct['regex']) && is_array($query_struct['regex']) && !empty($query_struct['regex']))
            {
                foreach($query_struct['regex'] as $field => $regex)
                {
                    $field = trim($field);
                    $position = strpos($field, ' ');
                    $sign = '';
                    if($position > 0)
                    {
                        $sign = trim(substr($field, $position + 1));
                        $field = substr($field, 0, $position);
                    }
                    $regexs = is_array($regex) ? $regex : array (
                        $regex 
                    );
                    foreach($regexs as $val)
                    {
                        switch(strtoupper($sign)){
                            case 'OR':
                                $orm_instance->orregex($field, $val);
                                break;
                            case 'NOT':
                                $orm_instance->notregex($field, $val);
                                break;
                            case 'ORNOT':
                                $orm_instance->ornotregex($field, $val);
                                break;
                            default:
                                $orm_instance->regex($field, $val);
                        }
                    }
                }
            }
            
            //* 处理 orderby 模块
            if(isset($query_struct['orderby']) && is_array($query_struct['orderby']) && !empty($query_struct['orderby']))
            {
                $orm_instance->orderby($query_struct['orderby']);
            }
            //* 处理 limit 模块
            if(isset($query_struct['limit']) && isset($query_struct['limit']['per_page']))
            {
                $limit = $query_struct['limit']['per_page'];
                $offset = intval(((isset($query_struct['limit']['page']) ? $query_struct['limit']['page'] : 1) - 1) * $limit);
                $tmp = $orm_instance->find_all($limit, $offset)->as_array();
                if ($this->test_mode)
                    d($orm_instance->last_query(), FALSE);
                return array_map(create_function('$item', 'return $item->as_array();'), $tmp);
            }
            else
            {
                return array_map(create_function('$item', 'return $item->as_array();'), $orm_instance->find_all()->as_array());
            }
        }
        catch(MyRuntimeException $ex)
        {
            //TODO 自定义逻辑
            throw $ex;
        }
    }
    
    /**
     * 查询数据统计结果
     * @param array $query_struct
     * @return int
     * @throws MyRuntimeException
     */
    public function query_count($query_struct)
    {
        try
        {
            $orm_instance = $this->get_orm_instance();
            
            if(isset($query_struct['and_where']) && is_array($query_struct['and_where']))
            {
                $orm_instance->open_paren();
                
                foreach($query_struct['and_where'] as $key => $condition)
                {
                    if (is_array($condition))
                    {
                        $orm_instance->in($key, $condition);
                    }
                    else
                    {
                    	if (is_numeric($key))
                        {
                        	$orm_instance->where($condition);
                        } else {
                        	$orm_instance->where($key, $condition);
                        }
                    }
                }
                $orm_instance->close_paren();
            }

            if(isset($query_struct['or_where']) && is_array($query_struct['or_where']))
            {
                $orm_instance->open_paren();
                foreach($query_struct['or_where'] as $key => $condition)
                {
                    if (is_array($condition))
                    {
                        $orm_instance->in($key, $condition);
                    }
                    else
                    {
                    	if (is_numeric($key))
                        {
                        	$orm_instance->orwhere($condition);
                        } else {
                        	$orm_instance->orwhere($key, $condition);
                        }
                    }
                }
                $orm_instance->close_paren();
            }           
            
            if(isset($query_struct['where']) && is_array($query_struct['where']))
            {
                foreach($query_struct['where'] as $key => $condition)
                {
                    if (is_array($condition))
                    {
                        $orm_instance->in($key, $condition);
                    }
                    else
                    {
                    	if (is_numeric($key))
                        {
                        	$orm_instance->where($condition);
                        } else {
                        	$orm_instance->where($key, $condition);
                        }
                    }
                }
            }
            
            if(isset($query_struct['orwhere']) && is_array($query_struct['orwhere']))
            {
                foreach($query_struct['orwhere'] as $key => $condition)
                {
                    if(is_array($condition))
                    {
                        $orm_instance->in($key, $condition);
                    }
                    else
                    {
                    	if (is_numeric($key))
                        {
                        	$orm_instance->orwhere($condition);
                        } else {
                        	$orm_instance->orwhere($key, $condition);
                        }
                    }
                }
            }
            
            if(isset($query_struct['notin']) && is_array($query_struct['notin']))
            {
                foreach($query_struct['notin'] as $key => $condition)
                {
                    if(is_array($condition))
                    {
                        $orm_instance->notin($key, $condition);
                    }
                    else
                    {
                    	if (is_numeric($key))
                        {
                        	$orm_instance->orwhere($condition);
                        } else {
                        	$orm_instance->orwhere($key, $condition);
                        }
                    }
                }
            }

            //* 处理 like 模块
            if(isset($query_struct['like']) && is_array($query_struct['like']) && !empty($query_struct['like']))
            {
                foreach($query_struct['like'] as $field => $regex)
                {
                    $field = trim($field);
                    $position = strpos($field, ' ');
                    $sign = '';
                    if($position > 0){
                        $sign = trim(substr($field, $position + 1));
                        $field = substr($field, 0, $position);
                    }
                    $regexs = is_array($regex) ? $regex : array (
                        $regex 
                    );
                    foreach($regexs as $val)
                    {
                        switch(strtoupper($sign))
                        {
                            case 'OR':
                                $orm_instance->orlike($field, $val);
                                break;
                            case 'NOT':
                                $orm_instance->notlike($field, $val);
                                break;
                            case 'ORNOT':
                                $orm_instance->ornotlike($field, $val);
                                break;
                            default:
                                $orm_instance->like($field, $val);
                        }
                    }
                }
            }
            
            // 处理 regex 模块
            if(isset($query_struct['regex']) && is_array($query_struct['regex']) && !empty($query_struct['regex']))
            {
                foreach($query_struct['regex'] as $field => $regex)
                {
                    $field = trim($field);
                    $position = strpos($field, ' ');
                    $sign = '';
                    if($position > 0)
                    {
                        $sign = trim(substr($field, $position + 1));
                        $field = substr($field, 0, $position);
                    }
                    $regexs = is_array($regex) ? $regex : array (
                        $regex 
                    );
                    foreach($regexs as $val)
                    {
                        switch(strtoupper($sign)){
                            case 'OR':
                                $orm_instance->orregex($field, $val);
                                break;
                            case 'NOT':
                                $orm_instance->notregex($field, $val);
                                break;
                            case 'ORNOT':
                                $orm_instance->ornotregex($field, $val);
                                break;
                            default:
                                $orm_instance->regex($field, $val);
                        }
                    }
                }
            }
            
            $countall = $orm_instance->count_all();
            
            if ($this->test_mode)
                d($orm_instance->last_query(), FALSE);            
            
            return $countall;
        }
        catch(MyRuntimeException $ex)
        {
            //TODO 自定义逻辑
            throw $ex;
        }
    }
    
    
    /**
     * 查询数据统计结果
     * @param array $query_struct
     * @return int
     * @throws MyRuntimeException
     */
    public function query_sum($query_struct, $field)
    {
        if (empty($field))
            return FALSE;
        
        if (isset($query_struct['limit']))
            unset($query_struct['limit']);
            
        $results = $this->query_assoc($query_struct);
           
        $sums = array();
            
        if (!empty($results))
        {
            foreach ($results as $row)
            {
                foreach ($field as $rowfield)
                {
                    @$sums[$rowfield] += $row[$rowfield];
                }
            }
        }
        return $sums;
    }    
    
    
    /**
     * 查询数据列表
     * @param array $query_struct
     * @return array
     * @throws MyRuntimeException
     */
    public function query_row($query_struct)
    {
        try{
            $orm_instance = $this->get_orm_instance();
            //* 处理输入条件
            $where = array ();
            $in = array ();
            if(isset($query_struct['where']) && is_array($query_struct['where'])){
                foreach($query_struct['where'] as $key => $condition){
                    if(is_array($condition)){
                        $in[$key] = $condition;
                    }else{
                        $where[$key] = $condition;
                    }
                }
            }
            //* 处理 where 模块
            if(!empty($where)){
                $orm_instance->where($where);
            }
            if(!empty($in)){
                foreach($in as $in_key => $in_val){
                    $orm_instance->in($in_key, $in_val);
                }
            }
            
            
            if(isset($query_struct['orwhere']) && is_array($query_struct['orwhere'])){
                foreach($query_struct['orwhere'] as $key => $condition){
                    if(is_array($condition)){
                        //$in[$key] = $condition;
                        $orm_instance->in($key, $condition);
                    }else{
                        //$where[$key] = $condition;
                    	if (is_numeric($key))
                        {
                        	$orm_instance->orwhere($condition);
                        } else {
                        	$orm_instance->orwhere($key, $condition);
                        }
                    }
                }
            }
            
            if(isset($query_struct['notin']) && is_array($query_struct['notin'])){
                foreach($query_struct['notin'] as $key => $condition){
                    if(is_array($condition)){
                        //$in[$key] = $condition;
                        $orm_instance->notin($key, $condition);
                    }else{
                        //$where[$key] = $condition;
                    	if (is_numeric($key))
                        {
                        	$orm_instance->orwhere($condition);
                        } else {
                        	$orm_instance->orwhere($key, $condition);
                        }
                    }
                }
            }             
            
            
            //* 处理 like 模块
            if(isset($query_struct['like']) && is_array($query_struct['like']) && !empty($query_struct['like'])){
                $orm_instance->like($query_struct['like']);
            }
            $return_object = $orm_instance->find();
            if(empty($return_object) || empty($return_object->id)){
                return NULL;
            }else{
                return $return_object->as_array();
            }
        }catch(MyRuntimeException $ex){
            //TODO 自定义逻辑
            throw $ex;
        }
    }
    
    /**
     * 创建数据
     * @param array $request_data
     * @return int
     * @throws MyRuntimeException
     */
    public function create($request_data)
    {
        try{
            $orm_instance = ORM::factory($this->object_name);
            $data = $orm_instance->as_array();
            foreach($request_data as $key => $val){
                array_key_exists($key, $data) && $orm_instance->$key = $val;
            }
            $orm_instance->save();
            if($orm_instance->saved !== TRUE){
                throw new MyRuntimeException('internal error', 500);
            }
            //TODO 逻辑与数据分离：状态与数据分离
            return $orm_instance->id;
        }catch(MyRuntimeException $ex){
            //TODO 自定义逻辑
            throw $ex;
        }
    }
    
    /**
     * 读取数据
     * @param array $request_data
     * @return array
     * @throws MyRuntimeException
     */
    public function read($request_data)
    {
        try{
            //if(!empty($this->object_pool) && isset($this->object_pool[$request_data['id']])){
            //	return $this->object_pool[$request_data['id']];
            //}
            $orm_instance = ORM::factory($this->object_name, $request_data['id']);
            if($orm_instance->loaded == FALSE){
                throw new MyRuntimeException('object not found', 404);
            }
            $object_array = $orm_instance->as_array();
            //if(!empty($object_array)){
            //    $this->object_pool[$object_array['id']] = $object_array;
            //}
            return $object_array;
        }catch(MyRuntimeException $ex){
            //TODO 自定义逻辑
            throw $ex;
        }
    }
    /**
     * 更新数据
     * @param array $request_data
     * @return void
     * @throws MyRuntimeException
     */
    public function update($request_data)
    {
        try{
            $orm_instance = ORM::factory($this->object_name, $request_data['id']);
            if($orm_instance->loaded == FALSE){
                throw new MyRuntimeException('object not found', 404);
            }
            $data = $orm_instance->as_array();
            foreach($request_data as $key => $val){
                array_key_exists($key, $data) && $orm_instance->$key = $val;
            }
            $orm_instance->save();
            if($orm_instance->saved !== TRUE){
                throw new MyRuntimeException('internal error', 500);
            }
            //if(!empty($this->object_pool) && isset($this->object_pool[$request_data['id']])){
        //	$object_array_pool = $this->object_pool[$request_data['id']];
        //    foreach ($request_data as $key=>$val){
        //        array_key_exists($key,$object_array_pool) && $object_array_pool[$key] = $val;
        //    }
        //    $this->object_pool[$request_data['id']] = $object_array_pool;
        //}
        //return $orm_instance->saved;
        }catch(MyRuntimeException $ex){
            //TODO 自定义逻辑
            throw $ex;
        }
    }
    
    /**
     * 删除数据
     * @param array $request_data
     * @return void
     * @throws MyRuntimeException
     */
    public function delete($request_data)
    {
        try{
            $orm_instance = ORM::factory($this->object_name, $request_data['id']);
            if($orm_instance->loaded == FALSE){
                throw new MyRuntimeException('object not found', 404);
            }
            $orm_instance->delete();
            //if(!empty($this->object_pool) && isset($this->object_pool[$request_data['id']])){
        //	unset($this->object_pool[$request_data['id']]);
        //}
        }catch(MyRuntimeException $ex){
            //TODO 自定义逻辑
            throw $ex;
        }
    }
    
    //** 业务逻辑代码请写在此行之后　**//
    

    //FIXME 根据本类属性对这部分应用函数做一定业务逻辑上的调整
    public function get($id)
    {
        // Custom 
        return $this->read(array (
            'id' => $id 
        ));
    }
    public function set($id, $data)
    {
        // Custom 
        $request_data = $data;
        $request_data['id'] = $id;
        return $this->update($request_data);
    }
    public function add($data)
    {
        // Custom 
        return $this->create($data);
    }
    public function remove($id)
    {
        // Custom 
        return $this->delete(array (
            'id' => $id 
        ));
    }
    public function index($query_struct)
    {
        // Custom 
        return $this->query_assoc($query_struct);
    }
    public function count($query_struct)
    {
        // Custom 
        return $this->query_count($query_struct);
    }
    
//:: 本类定制的业务逻辑 :://
//TODO 根据业务逻辑需求提供对应的函数调用


}