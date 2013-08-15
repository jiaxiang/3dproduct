<?php defined('SYSPATH') OR die('No direct access allowed.');

class Scan_Controller extends Template_Controller {	
	public $site_id;

        //判断菜单权限
	public function __construct()
	{
		parent::__construct();
		$this->site_id = site::id();
		role::check('site_scan',$this->site_id);
	}

        /*
         * 列表
         */
        public function index()
        {
            $query_struct = array();

            $list_columns = array(                    
                    array('name'=>'域名/IP','column'=>'name','class_num'=>'6'),                    
                    array('name'=>'添加扫描时间','column'=>'add_time','class_num'=>'4')
            );

            //调用分类
            $per_page    = controller_tool::per_page();
            $this->pagination = new Pagination(
                    array(
                            'total_items'    => Myscan::instance()->count($query_struct),
                            'items_per_page' => $per_page,
                    )
            );

            $scan = Myscan::instance()->scans($query_struct,NULL,$per_page,$this->pagination->sql_offset);

            $this->template->content = new View("site/scan_list");
            $this->template->content->list_columns = $list_columns;
            $this->template->content->scan = $scan;
        }

        /*
         * 添加站点
         */
        public function add()
        {
            if($_POST) {
                $name = $this->input->post('name'); 
                $data = Myscan::instance()->scans();                
                foreach($data as $key => $value) {
                    if ($name == $value['name'])
                    {
                        remind::set(Kohana::lang('o_site.scan_has_exist'),'site/scan');
                    }
                }
                $_POST['rep_id'] = Nessus_system::instance()->queue_add($name);                

                if(Myscan::instance()->add($_POST)) {
                    remind::set(Kohana::lang('o_site.scanning'),'site/scan','success');
                }else {
                    remind::set(Kohana::lang('o_global.add_error'),'site/scan/add');
                }
            }

            $this->template->content = new View("site/scan_add");
        }

        /*
         * 修改站点         
        public function edit($id)
        {
            $scan = Myscan::instance($id)->get();
            if(!$scan['id']) {
                remind::set('站点不存在.','site/scan');
            }

            if($_POST) {
                $name = $this->input->post('name');
                //判读站点是否存在
                $data = Myscan::instance()->scans();
                foreach($data as $key => $value) {
                    if ($name == $value['name'])
                    {
                        remind::set(Kohana::lang('o_site.scan_site_has_exist'),'site/scan');
                    }
                }
                //先删除原先站点显示的报告
                $scan = Myscan::instance($id)->get();
                Nessus_system::instance()->queue_delete($scan['name']);
                //重新根据新的站点生成新的报告
                $_POST['rep_id'] = Nessus_system::instance()->queue_add($name);
                $_POST['manager_id'] = $this->manager_id;
                if(Myscan::instance($id)->edit($_POST)) {
                    remind::set(Kohana::lang('o_global.update_success'),'site/scan','success');
                }else {
                    remind::set(Kohana::lang('o_global.update_success'),'site/scan/edit', 'success');
                }
            }

            $this->template->content = new View("site/scan_edit");
            $this->template->content->data = $scan;
        }
         */

        /*
         * 删除站点
         */
        public function delete($id)
        {
            $scan = Myscan::instance($id)->get();
            if(!$scan['id']) {
                remind::set(Kohana::lang('o_site.scan_site_not_exist'),'site/scan');
            }
            //删除站点所产生的报告            
            Nessus_system::instance()->queue_delete($scan['name']);
            //删除数据库数据
            if(Myscan::instance($id)->delete()) {
                remind::set(Kohana::lang('o_global.delete_success'),'site/scan','success');
            }else {
                $error = Myscan::instance($id)->error();
                remind::set(Kohana::lang('o_global.delete_error') . $error,'site/scan');
            }            
        }

        /*
         * 显示报告
         */
        public function view($rep_id,$id)
        {
            //查看数据库中是否有报告
            $report = Myscan::instance($id)->get();            
            if ($report['value']) {                
                $this->template->content = new View("site/scan_view");
                $this->template->content->data = $report['value'];
            } else {
                try
                {
                    $html_string = Nessus_system::instance()->report_get($rep_id);
                    if(!$html_string){
                        throw new Exception(Kohana::lang('o_site.scanning'));
                    }
                } catch(Exception $e)
                {
                    remind::set($e->getMessage(),'site/scan');
                }
                $html_string = str_replace('http://www.nessus.org/plugins/index.php?view=single&id=',"http://".$_SERVER['SERVER_NAME']."/site/scan/single/",$html_string);
                $html_string = str_replace('Nessus','Ketai',str_replace('nessus','ketai',str_replace("<hr>\n<i>This file was generated by <a href=\"http://www.nessus.org\">Nessus</a>, <i>the</i> security scanner.</i>","",$html_string)));
                $html_string = str_replace('Ketai ID : <a',"Report ID : <a class='contentsmall'",$html_string);
                $html_string = str_replace("\n",'',$html_string);                
                preg_match("/<table bgcolor=\"#a1a1a1\" border=0 cellpadding=0 cellspacing=0 width=\"95%\">(.*)<\/tr><\/td><\/tr><\/tbody><\/table><\/td><\/tr><\/tbody><\/table>/",$html_string,$content);                
                //$html_string = str_replace('width="95%"','width="80%"',str_replace('width="60%"','width="80%"',str_replace('width="75%"','width="80%"',$content[0])));
                //清楚相关的链接
                $html_string = str_replace('http://cgi.ketai.org/cve.php3?cve=','http://web.nvd.nist.gov/view/vuln/detail?vulnId=',str_replace('http://cgi.ketai.org/bid.php3?bid=','http://www.securityfocus.com/bid/',$html_string));
                $html_string = preg_replace("/http:\/\/www.ketai.org\/u\?[a-z0-9]{8}/",'',$html_string);
                $html_string = str_replace('<a href=""></a><br>','',$html_string);
                $html_string = str_replace('<div align="left"><font size=-2><a href="#toc">[ return to top ]</a></font></div>','',$html_string);
                $html_string = str_replace('See also :<br><br><a href="http://blog.tenablesecurity.com/web-app-auditing/">http://blog.tenablesecurity.com/web-app-auditing/</a><br><br>','',$html_string);
                //die($html_string);
                //将生成的报告插入到数据库中
                $data = array();
                $data['value'] = $html_string;
                Myscan::instance($id)->edit($data);
                $this->template->content = new View("site/scan_view");
                $this->template->content->data = $html_string;
            }
        }

        public function single($id) {
            $scan_single = @Myscan_single::instance($id)->get();
            if ($scan_single['value']) {
//                $this->template->content = new View("site/scan_single_view");
//                $this->template->content->data = $scan_single['value'];
                  return $scan_single['value'];
            } else {
                $url = "http://www.nessus.org/plugins/index.php?view=single&id=$id";               
                //设置页面响应时间
                set_time_limit(0);
                $content = $this->url($url);
                $content = str_replace("\n",'',str_replace("\t\t","",str_replace('Nessus','Ketai',$content)));
                //$content = '';//进行相关调试
                try
                {
                    preg_match("/<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"10\">	<tr>	  <td valign=\"top\"> <\/td>	<\/tr>	<tr>	  <td valign=\"top\">(.*)<\/td>	<\/tr>      <\/table>    <\/td>   <\/tr><\/table>	       <\/td>/",$content,$report);
                    if(!@$report[1]){
                        throw new Exception(Kohana::lang('o_global.access_denied'));
                    }
                }catch(Exception $e)
                {
                    return $e->getMessage();
                }
                    $single = str_replace('<tr><td colspan=2><img src="../images/hdr_dash.gif" width="400" height="3" /></td></tr>','',$report[1]);
                    //去除相关的链接
                    $single = preg_replace("/http:\/\/www.nessus.org\/u\?[a-z0-9]{8}/",'',$single);
                    $single = str_replace('<a href="" target="_blank"></a><br />','',$single);                    
                    //插入到数据库
                    $data = array();
                    $data['value'] = $single;
                    $data['id'] = $id;
                    Myscan_single::instance($id)->add($data);
//                    $this->template->content = new View("site/scan_single_view");
//                    $this->template->content->data = $single;
                    return $single;
            }
            
        }

        public function url($url) {
            // 始化个 cURL 象
            $curl = curl_init();
            // 设置需抓URL
            curl_setopt($curl, CURLOPT_URL, $url);
            // 设置header
            curl_setopt($curl, CURLOPT_HEADER, 1);
            // 设置cURL 参数，求结果保存字符串还输出屏幕。
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            // 运行cURL，求网页
            $content = curl_exec($curl);
            // 关闭URL求
            curl_close($curl);
            // 返回内容
            return $content;
        }

	/**
	 * ajax get notice content
	 */
	public function ajax_content()
	{
		$return_struct = array(
			'status' => 0,
			'code'	 => 501,
			'msg'	 => 'Not Implemented'
		);
		if(request::is_ajax())
		{
			$id = intval($this->input->get('id'));                       
            $value = $this->single($id);
            
			$return_template = $this->template = new View('template_blank');
			$this->template->content = $value;
			$return_str = $return_template->render();
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = 'Success';
			$return_struct['content'] = $return_str;
			exit(json_encode($return_struct));
		}
	}
}
?>
