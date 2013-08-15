<?php defined('SYSPATH') OR die('No direct access allowed.');

class User_charge_collect_Controller extends Template_Controller {
    // Set the name of the template to use
    public $template_ = 'layout/common_html';
    var  $result;

    public function __construct()
    {
        parent::__construct();
        if($this->is_ajax_request()==TRUE)
        {
            $this->template = new View('layout/default_json');
        }
    }
	

	//用户充值记录
    public function index(){
    	role::check('user_charge_collect');
    	$db = Database::instance(); 
    	$search_value = $this->input->get('search_value');
    	$search_name = $this->input->get('search_name');
        if(strlen($search_value)<6&&strlen($search_value)>3){
		$date = explode("-", $search_value);
		$search_value1 = "and (year(add_time)='20".$date[0]."' and week(add_time)='".$date[1]."')";
    	$search_value2 = "and (year(date_add)='20".$date[0]."' and week(date_add)='".$date[1]."')";
		}
    	else if(strlen($search_value)==10){
    	$search_value1 = "and date(add_time)='".$search_value."'";
    	$search_value2 = "and date(date_add)='".$search_value."'";
    	}
		else if(strlen($search_value)==6){
		$search_value1 = "and EXTRACT(YEAR_MONTH FROM add_time ) = $search_value";
		$search_value2 = "and EXTRACT(YEAR_MONTH FROM date_add ) = $search_value";
		}
		else{
		$search_value1 = null;
		$search_value2 = null;
		}
		if(!empty($search_name)){
		$search_name="where lastname='".$search_name."'";
		}
		$user_query_struct = array(
            'limit'     => array(
                'per_page'  => 50,
                'offset'    => 0,
            ),
        );  
		$per_page    = controller_tool::per_page();
		$user_query_struct['limit']['per_page'] = $per_page;
		
		/* 调用分页 */
		$sql="SELECT count(id) as count FROM users $search_name";//计算总条数
        $result = $db->query($sql);
		$this->pagination = new Pagination(array(
			'total_items'    => $result[0]->count,
			'items_per_page' => $per_page,
		));
		$page = $this->input->get('page');
		$per_page = $this->input->get('per_page');
		$stpage=0;
		$per_page = $this->input->get('per_page');
    	if(empty($per_page)){
			 $per_page=$this->pagination->items_per_page;
		}
		$set_per_page=$this->pagination->items_per_page;
		if(!empty($page)){
			 $stpage = ($page-1)*$per_page;//起始页
		}
    	$get=$this->input->get('order');
		if(!empty($get)){
			$order="order by `".$get;
//			$a=explode("`",$get);
//			$order=$$a[0];
//			$sc=$a[1];
		}
		else{
			$order="order by `id`asc";
//			$order=$id;
//			$sc='ASC';
		}
        $query="SELECT id,lastname, y.user_money+y.bonus_money+y.free_money as user_money, (
		SELECT sum( price ) 
		FROM `account_logs` AS z
		WHERE z.user_id = y.id $search_value1
		AND is_in =0 
		GROUP BY user_id) AS user_in,
		(SELECT sum( price ) 
		FROM `account_logs` AS x
		WHERE x.user_id = y.id $search_value1
		AND is_in =1 
		GROUP BY user_id) AS user_is,
		(SELECT count( 
		STATUS )
		FROM `plans_basics` 
		WHERE y.id=plans_basics.user_id and STATUS >0 $search_value2
		GROUP BY user_id) AS Betting_count,
		(SELECT count( 
		STATUS )
		FROM `plans_basics` 
		WHERE y.id=plans_basics.user_id and (STATUS IN ( 4,5 )  or (STATUS=0 and bonus>0)) $search_value2
		GROUP BY user_id) AS win_count,
		(SELECT sum( 
		bonus)
		FROM `plans_basics` 
		WHERE y.id=plans_basics.user_id $search_value2
		GROUP BY user_id) AS win_num,
		(SELECT sum( 
		money)
		FROM `user_charge_orders` 
		WHERE y.id=user_charge_orders.user_id and status=1 $search_value1
		GROUP BY user_id) AS user_charge
		FROM users AS y $search_name $order limit $stpage,$per_page";
        $result = $db->query($query);
        $arr1=array();
        $arr=array();
    	foreach ($result as $rs) {
    	//if($rs->user_money<>0.00 || $rs->user_in!=null ||$rs->user_is!=null ||$rs->Betting_count!=null||$rs->win_count!=null||$rs->win_num!=null||$rs->user_charge!=null ){
    	$arr['id']=$rs->id;
    	$arr['lastname']=$rs->lastname;
    	$arr['user_money']=$rs->user_money;
    	$arr['user_in']=$rs->user_in==null?0.00:$rs->user_in;
    	$arr['user_is']=$rs->user_is==null?0.00:$rs->user_is;
    	$arr['Betting_count']=$rs->Betting_count==null?0:$rs->Betting_count;
    	$arr['win_count']=$rs->win_count==null?0:$rs->win_count;
    	$arr['win_num']=$rs->win_num==null?0.00:$rs->win_num;
    	$arr['user_charge']=$rs->user_charge==null?0.00:$rs->user_charge;
    	$arr1[]=$arr;
    	$this->result=$arr1;
    	}
		//}
		
//		// 取得列的列表
//		if(!empty($arr1)){
//		foreach ($arr1 as $key=>$row) {
//		    $id[$key] = $row['id'];
//		    $lastname[$key] = $row['lastname'];
//		    $user_money[$key] = $row['user_money'];
//		    $user_in[$key] = $row['user_in'];
//		    $user_is[$key] = $row['user_is'];
//		    $Betting_count[$key] = $row['Betting_count'];
//		    $win_count[$key] = $row['win_count'];
//		    $win_num[$key] = $row['win_num'];
//		    $user_charge[$key] = $row['user_charge'];
//		    
//		}
//		}

//		$get=$this->input->get('order');
//		if(!empty($get)){
//			$order="order by `".$get;
//			$a=explode("`",$get);
//			$order=$$a[0];
//			$sc=$a[1];
//		}
//		else{
//			$order="order by `id`desc";
//						$order=$id;
//			$sc='ASC';
//		}
			

		
		// 将数据根据 volume 降序排列，根据 edition 升序排列
		// 把 $data 作为最后一个参数，以通用键排序
//		if($sc=="ASC"){
//			array_multisort($order,SORT_ASC,$arr1);
//		}
//		if($sc=="DESC"){
//			array_multisort($order,SORT_DESC,$arr1);
//		}
//		$this->result=$arr1;
//		}
		
		
        /* 初始化默认查询条件 */
//        $user_query_struct = array(
//            'where'=>array(),
//            'like'=>array(),
//            'orderby'   => array(
//                'id'  => "DESC",			
//			),
//            'limit'     => array(
//                'per_page'  => 20,
//                'offset'    => 0,
//            ),
//        );        

		/* 用户列表模板 */
		$this->template->content = new View("user/user_charge_collect");
		
		/* 搜索功能 */
//		$search_arr      = array('order_num');
//		$search_value    = $this->input->get('search_value');
		$where_view      = array();
//
//		$user_query_struct['like']['order_num'] = $search_value;
		//$where_view['search_value'] = $search_value;			
//		      
		/* 每页显示条数 */
	//d($this->pagination->sql_offset);
//		$user_query_struct['limit']['offset'] = $this->pagination->sql_offset;
//		$users = User_chargeService::get_instance()->lists($user_query_struct);
//			
//		$userobj = user::get_instance();
  
//       foreach($users as $key=>$rowuser)
//       {
//           $users[$key]['userinfo'] = $userobj->get($rowuser['user_id']);
//       }

		/* 调用列表 */
		$this->template->content->user_list	= $this->result;
		$this->template->content->where	= $where_view;
		$this->template->content->set('set_per_page',$set_per_page);
	}
	
	public function virtual(){
		role::check('user_charge_collect');
		$db = Database::instance();
		$search_value = $this->input->get('search_value');
		$search_name = $this->input->get('search_name');
		if(strlen($search_value)<6&&strlen($search_value)>3){
			$date = explode("-", $search_value);
			$search_value1 = "and (year(add_time)='20".$date[0]."' and week(add_time)='".$date[1]."')";
			$search_value2 = "and (year(date_add)='20".$date[0]."' and week(date_add)='".$date[1]."')";
		}
		else if(strlen($search_value)==10){
			$search_value1 = "and date(add_time)='".$search_value."'";
			$search_value2 = "and date(date_add)='".$search_value."'";
		}
		else if(strlen($search_value)==6){
			$search_value1 = "and EXTRACT(YEAR_MONTH FROM add_time ) = $search_value";
			$search_value2 = "and EXTRACT(YEAR_MONTH FROM date_add ) = $search_value";
		}
		else{
			$search_value1 = null;
			$search_value2 = null;
		}
		if(!empty($search_name)){
			$search_name="where lastname='".$search_name."'";
		}
		$user_query_struct = array(
				'limit'     => array(
						'per_page'  => 50,
						'offset'    => 0,
				),
		);
		$per_page    = controller_tool::per_page();
		$user_query_struct['limit']['per_page'] = $per_page;
	
		/* 调用分页 */
		$sql="SELECT count(id) as count FROM users $search_name";//计算总条数
		$result = $db->query($sql);
		$this->pagination = new Pagination(array(
				'total_items'    => $result[0]->count,
				'items_per_page' => $per_page,
		));
		$page = $this->input->get('page');
		$per_page = $this->input->get('per_page');
		$stpage=0;
		$per_page = $this->input->get('per_page');
		if(empty($per_page)){
			$per_page=$this->pagination->items_per_page;
		}
		$set_per_page=$this->pagination->items_per_page;
		if(!empty($page)){
			$stpage = ($page-1)*$per_page;//起始页
		}
		$get=$this->input->get('order');
		if(!empty($get)){
			$order="order by `".$get;
			//			$a=explode("`",$get);
			//			$order=$$a[0];
			//			$sc=$a[1];
		}
		else{
			$order="order by `id`asc";
			//			$order=$id;
			//			$sc='ASC';
		}
		$query="SELECT id,lastname, y.virtual_money as user_money, (
		SELECT sum( price )
		FROM `account_virtual_logs` AS z
		WHERE z.user_id = y.id $search_value1
		AND is_in =0
		GROUP BY user_id) AS user_in,
		(SELECT sum( price )
		FROM `account_virtual_logs` AS x
		WHERE x.user_id = y.id $search_value1
		AND is_in =1
		GROUP BY user_id) AS user_is,
		(SELECT count(
		STATUS )
		FROM `plans_basics`
		WHERE y.id=plans_basics.user_id and STATUS >0 $search_value2
		GROUP BY user_id) AS Betting_count,
		(SELECT count(
		STATUS )
		FROM `plans_basics`
		WHERE y.id=plans_basics.user_id and (STATUS IN ( 4,5 )  or (STATUS=0 and bonus>0)) $search_value2
		GROUP BY user_id) AS win_count,
		(SELECT sum(
		bonus)
		FROM `plans_basics`
		WHERE y.id=plans_basics.user_id $search_value2
		GROUP BY user_id) AS win_num,
		(SELECT sum(
		money)
		FROM `user_charge_orders`
		WHERE y.id=user_charge_orders.user_id and status=1 $search_value1
		GROUP BY user_id) AS user_charge
		FROM users AS y $search_name $order limit $stpage,$per_page";
		$result = $db->query($query);
		$arr1=array();
		$arr=array();
		foreach ($result as $rs) {
			//if($rs->user_money<>0.00 || $rs->user_in!=null ||$rs->user_is!=null ||$rs->Betting_count!=null||$rs->win_count!=null||$rs->win_num!=null||$rs->user_charge!=null ){
			$arr['id']=$rs->id;
			$arr['lastname']=$rs->lastname;
			$arr['user_money']=$rs->user_money;
			$arr['user_in']=$rs->user_in==null?0.00:$rs->user_in;
			$arr['user_is']=$rs->user_is==null?0.00:$rs->user_is;
			$arr['Betting_count']=$rs->Betting_count==null?0:$rs->Betting_count;
			$arr['win_count']=$rs->win_count==null?0:$rs->win_count;
			$arr['win_num']=$rs->win_num==null?0.00:$rs->win_num;
			$arr['user_charge']=$rs->user_charge==null?0.00:$rs->user_charge;
			$arr1[]=$arr;
			$this->result=$arr1;
		}
		
	
		/* 用户列表模板 */
		$this->template->content = new View("user/user_charge_virtual_collect");
	
		$where_view      = array();
		
	
		/* 调用列表 */
		$this->template->content->user_list	= $this->result;
		$this->template->content->where	= $where_view;
		$this->template->content->set('set_per_page',$set_per_page);
	}
	
}
