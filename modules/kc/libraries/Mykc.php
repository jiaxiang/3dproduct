<?php defined('SYSPATH') or die('No direct access allowed.');

class Mykc_Core
{
    //表名
    protected $object_name = NULL;

    /**
     * 单实例方法
     */
    public function __construct()
    {
        $this->object_name = strtolower(substr(get_class($this), 2,-5)); 
    }

    /**
     * 创建数据(C)
     *
     * @param array $request_data
     * @return Int|Boolean
     */
    public function create($request_data)
    {
        $orm_instance = ORM::factory($this->object_name);

        $errors = '';
        if($orm_instance->validate($request_data,TRUE,$errors))
        {
            return $orm_instance->id;
        }
        else
        {
            log::write('alert',$errors,__FILE__,__LINE__);

            return FALSE;
        }

    }

    /**
     * 更新数据(U)
     *
     * @param Array $request_data
     * @return Boolean
     */
    //  样例
    //  update($data);
    //  update($id,$data);
    public function update($request_data)
    {
		if (func_num_args() > 1)
		{
			$request_data   = func_get_args();
            $id             = $request_data[0];
            $data           = $request_data[1];
		}else{
            $id             = $request_data['id'];
            $data           = $request_data;
		}


        $orm_instance = ORM::factory($this->object_name,$id);

        if(!$orm_instance->loaded)
        {
            $error = 'Data load failed , object : '.$this->object_name.' , id : '.$id;
            log::write('alert',$error,__FILE__,__LINE__);

            return FALSE;
        }
        $errors = '';
        if($orm_instance->validate($data,TRUE,$errors))
        {
            //echo $orm_instance->last_query();
            return TRUE;
        }else{
            log::write('alert',$errors,__FILE__,__LINE__);

            return FALSE;
        }
    }

    /**
     * 读取数据(R)
     *
     * @param Int|Array $request_data
     * @return Array
     */
    //  样例
    //  read(1);
    //  read(array('id'=>1));
    public function read($request_data)
    {
        $id     = is_array($request_data)?$request_data['id']:$request_data;
        $orm_instance = ORM::factory($this->object_name)
            ->where('id',$id)
            //->where('disabled','false')
            ->find();
        if($orm_instance->loaded == FALSE)
        {
            $error = 'Data load failed , object : '.$this->object_name.' , id : '.$id;
            log::write('alert',$error,__FILE__,__LINE__);
        }

        $object_array = $orm_instance->as_array();
        return $object_array;
    }

    /**
     * 删除数据(D)
     * 
     * @param Int|Array $request_data
     * @return Boolean 
     */
    //  样例
    //  delete(1);
    //  delete(array('id'=>1));
    public function delete($request_data)
    {
        $id     = is_array($request_data)?$request_data['id']:$request_data;
        $orm_instance = ORM::factory($this->object_name, $id);

        //if($orm_instance->loaded && $orm_instance->disabled)
        if($orm_instance->loaded )
        {
            //$orm_instance->disabled     = true;
            //$orm_instance->save();
            $orm_instance->delete();

            return TRUE;
        }else{
            $error = 'Data load failed , object : '.$this->object_name.' , id : '.$id;
            log::write('alert',$error,__FILE__,__LINE__);

            return FALSE;
        }
    }


    /**
     * 得到总条目数
     *
     * @param Array
     * $query_struct= array(
         'where'     => array(
             'site_id'   => 1,
             'category_id'   => array(1,2,3),
         ),
         'like'      => array(
             'SKU'       => 'SKU'
         ),
         'regex'     => array(
             'name' => 'name|sample',
         ),
     );
    *
        * @return Int
     */
    public function count($query_struct = array())
    {
        $query_struct_default = array(
            'where'     => array(
               // 'disabled'   => 'false',
            ),
        );
        $query_struct   = array_merge_recursive($query_struct,$query_struct_default);
        
        $orm_instance   = ORM::factory($this->object_name);

        // 处理 where,in 模块
        if(isset($query_struct['where']) && is_array($query_struct['where']))
        {
            foreach($query_struct['where'] as $key => $condition)
            {
                if(is_array($condition)){
                    $orm_instance->in($key, $condition);
                }else{
                    $orm_instance->where($key, $condition);
                }
            }
        }

        // 处理 like 模块
        if(isset($query_struct['like']) && is_array($query_struct['like']) )
        {
            foreach($query_struct['like'] as $field => $regex)
            {
                $field      = trim($field);
                $position   = strpos($field, ' ');
                $sign       = '';
                if($position > 0)
                {
                    $sign       = trim(substr($field, $position + 1));
                    $field      = substr($field, 0, $position);
                }
                $regexs = is_array($regex) ? $regex : array($regex);
                foreach($regexs as $val)
                {
                    switch(strtoupper($sign)){
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
        if(isset($query_struct['regex']) && is_array($query_struct['regex']) )
        {
            foreach($query_struct['regex'] as $field => $regex)
            {
                $field      = trim($field);
                $position   = strpos($field, ' ');
                $sign       = '';
                if($position > 0)
                {
                    $sign       = trim(substr($field, $position + 1));
                    $field      = substr($field, 0, $position);
                }
                $regexs = is_array($regex) ? $regex : array($regex);
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

        return $orm_instance->count_all();
    }

    /**
     * 得到多条数据
     *
     * @param Array $query_struct
     * $query_struct= array(
         'where'     => array(
             'site_id'   => 1,
             'category_id'   => array(1,2,3),
         ),
         'like'      => array(
             'SKU'       => 'SKU'
         ),
         'regex'     => array(
             'name' => 'name|sample',
         ),
         'orderby'   => array(
             'site_id'   => 'ASC',
         ),
         'limit'     => array(
             'per_page'  => 20,
             'offset'    => 30,
         ),
     );
    * 
        * @return Array
     */
    public function lists($query_struct = array())
    {
        $list = array();
        $query_struct_default = array(
            'where'     => array(
             //   'disabled'   => 'false',
            ),
        );
       
        $query_struct   = array_merge_recursive($query_struct,$query_struct_default);
        
        $orm_instance = ORM::factory($this->object_name);

        // 处理 where,in 模块
        if(isset($query_struct['where']) && is_array($query_struct['where']))
        {
            foreach($query_struct['where'] as $key => $condition)
            {
                if(is_array($condition))
                {
                    $orm_instance->in($key, $condition);
                }else{
                    $orm_instance->where($key, $condition);
                }
            }
        }

        // 处理 like 模块
        if(isset($query_struct['like']) && is_array($query_struct['like']) )
        {
            foreach($query_struct['like'] as $field => $regex)
            {
                $field      = trim($field);
                $position   = strpos($field, ' ');
                $sign       = '';
                if($position > 0)
                {
                    $sign       = trim(substr($field, $position + 1));
                    $field      = substr($field, 0, $position);
                }
                $regexs = is_array($regex) ? $regex : array ($regex);
                foreach($regexs as $val){
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
        if(isset($query_struct['regex']) && is_array($query_struct['regex']) )
        {
            foreach($query_struct['regex'] as $field => $regex)
            {
                $field      = trim($field);
                $position   = strpos($field, ' ');
                $sign       = '';
                if($position > 0){
                    $sign       = trim(substr($field, $position + 1));
                    $field      = substr($field, 0, $position);
                }
                $regexs = is_array($regex) ? $regex : array($regex);
                foreach($regexs as $val)
                {
                    switch(strtoupper($sign))
                    {
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

        // 处理 orderby 模块
        if(isset($query_struct['orderby']) && is_array($query_struct['orderby']) && !empty($query_struct['orderby']))
        {
            $orm_instance->orderby($query_struct['orderby']);
        }

        // 处理 limit 模块 ,无条件最多查询1000条数据
        $limit  = isset($query_struct['limit']['per_page']) ? $query_struct['limit']['per_page'] : 1000;
        $offset = isset($query_struct['limit']['offset']) ? $query_struct['limit']['offset'] : 0;
        $orm_list = $orm_instance->find_all($limit,$offset);
        foreach($orm_list as $item)
        {
            $list[] = $item->as_array();
        }

        return $list;
    }


    /**
     * 删除全部 
     *
     * @param Array
     * $query_struct= array(
         'where'     => array(
             'site_id'   => 1,
             'category_id'   => array(1,2,3),
         ),
         'like'      => array(
             'SKU'       => 'SKU'
         ),
     );
    *
        * @return Int 
     */
    public function delete_all($query_struct = array())
    {
        $delete_count   = 0;
        $query_struct_default = array(
            'where'     => array(
              //  'disabled'   => 'false',
            ),
        );

        $query_struct   = array_merge_recursive($query_struct,$query_struct_default);
        
        $orm_instance = ORM::factory($this->object_name);

        // 处理 where,in 模块
        if(isset($query_struct['where']) && is_array($query_struct['where']))
        {
            foreach($query_struct['where'] as $key => $condition)
            {
                if(is_array($condition)){
                    $orm_instance->in($key, $condition);
                }else{
                    $orm_instance->where($key, $condition);
                }
            }
        }

        // 处理 like 模块
        if(isset($query_struct['like']) && is_array($query_struct['like']) )
        {
            foreach($query_struct['like'] as $field => $regex)
            {
                $field      = trim($field);
                $position   = strpos($field, ' ');
                $sign       = '';
                if($position > 0)
                {
                    $sign       = trim(substr($field, $position + 1));
                    $field      = substr($field, 0, $position);
                }
                $regexs = is_array($regex) ? $regex : array($regex);
                foreach($regexs as $val)
                {
                    switch(strtoupper($sign)){
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

        
        $orm_list = $orm_instance->find_all();
        foreach($orm_list as $orm)
        {
            //$orm->disabled  = true;
            //$orm->save();
            $orm->delete();

            $delete_count ++;
        }

        return $delete_count;
    }





    # 业务逻辑代码请写在此行之后　

    /**
     * 得到单条信息
     *
     * @param Int $id
     * @return Array
     */
    public function get($id)
    {
        return $this->read($id);
    }

}
