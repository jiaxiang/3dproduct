<?php defined('SYSPATH') OR die('No direct access allowed.');

class Lotty_jobService_Core extends DefaultService_Core 
{
	/* 兼容php5.2环境 Start */
    private static $instance = NULL;
    private static $pdb = null;
	   
    // 获取单态实例
    public static function get_instance()
    {
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
    
    
    public function query_data_list($query_struct){
        if (empty($query_struct))
            return FALSE;
        try
        {  
        	$data_list = $this->query_assoc($query_struct);
			return $data_list;
        }
        catch (MyRuntimeException $ex) 
        {
            return FALSE;
            //throw new MyRuntimeException('', 404);
        }
    }
    
    public function add($data){
    	 $this->loaddb();
    	 if(!count($data)) return -2;
    	 //select 
    	 $row = self::$pdb->query("select * from lotty_jobs where qihao='".$data['qihao']."' and lottyid='".$data['lottyid']."' and tasktype='".$data['tasktype']."'");
         //if($row->count()>0) return -1;
    	 $query = self::$pdb->insert('lotty_jobs', $data);
    	 $insertid = $query->insert_id();
    	 return $insertid;
    }
    
    public function loaddb(){
		if(!self::$pdb){
		 	self::$pdb = Database::instance();
		}
	}
    
}