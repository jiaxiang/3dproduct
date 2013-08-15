<?php defined('SYSPATH') OR die('No direct access allowed.');

class Menu_tree_Controller extends Template_Controller{

    public function __construct(){
        //权限验证
        role::check('default');
		parent::__construct();
    }
    
    function top_menu(){
        $nodes = array();
        $action_ids = role::get_action_ids();
        $orderby = array('order'=>'DESC');
		$menus = ORM::factory('menu')
			->where('parent_id',0)
			->where('active',1)
			->in('action_id',$action_ids)
			->orderby($orderby)
			->find_all();
		foreach($menus as $item){
			$nodes[] = $item->as_array();
		}
        
        $this->template = new View("top_menu", array('nodes'=>$nodes, 'manager'=>$this->manager));
    }
    
    function left_menu(){
        $nodes = array();
        $action_ids = role::get_action_ids();
        $orderby = array('order'=>'DESC');
		$menus = ORM::factory('menu')
			->where('parent_id',0)
			->where('active',1)
			->in('action_id',$action_ids)
			->orderby($orderby)
			->find_all();		
		foreach($menus as $item){
			$value = $item->as_array();
			$value['submenu'] = $this->sub_menus($value['id'], $action_ids);
		    $nodes[] = $value;
		} 
        $this->template = new View("left_menu", array('nodes'=>$nodes));
    }
    
	function sub_menus($id, $action_ids=array()){
		$list = array();
        $menu = ORM::factory('menu');
        if(!empty($action_ids)){
            $menu->in('action_id',$action_ids);
        }
		$sub_menus = $menu->where(array('parent_id'=>$id,'active'=>1))
			->orderby(array('order'=>'DESC'))
			->find_all();
		foreach($sub_menus as $item){
			$list[] = $item->as_array();
		}
		return $list;
	}
    
	function index(){ 
        $nodes = array();
        $empty_menu = array('#','javascript:void(0);','javascript:void(0)');
        $action_ids = role::get_action_ids();
        $orderby = array('order'=>'DESC');
		$menus = ORM::factory('menu')
			->where('parent_id',0)
			->where('active',1)
			->in('action_id',$action_ids)
			->orderby($orderby)
			->find_all();		
		foreach($menus as $item){
			$value = $item->as_array();
            $value['name'] = '<b>'.$value['name'].'</b>';
			$sub_menus = $this->sub_menus($value['id'], $action_ids);
            if(empty($sub_menus))continue;
			foreach ($sub_menus as $sub_menu_key=>$sub_menu_value){
				$sub_menus[$sub_menu_key]['children'] = $this->sub_menus($sub_menu_value['id'], $action_ids);
			}
			$value['children'] = $sub_menus;
			$value = self::to_node($value);
		    $nodes[] = $value;
		}        
		die(json_encode($nodes));
        //echo 'Docs.classData =[];';
	} 
    
	/* 获得管理模组树节点信息 */
	private static function to_node($row){
        $url = menu::get_url($row["url"]);
		$data = array(
		    'id'   => $row["id"]
		   ,'text' => $row["name"]
		   ,'leaf' => empty($row["children"])
		);
		if($data["leaf"]==true){
           $data['iconCls'] = 'icon-cls';
           $data['url'] = $url;
           $data['qtip'] = $url;
		}else{
		   $data['iconCls'] = 'icon-pkg';
		   $data["singleClickExpand"] = true;
           foreach($row["children"] as $k=>$v){
               $row["children"][$k] = self::to_node($v);
           }
           $data['children'] = $row["children"];
        }
		return $data;
	}
}

