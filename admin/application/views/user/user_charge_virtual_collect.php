<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li><a href='<?php echo url::base() . 'user/user_charge_collect/';?>'>会员资金汇总</a></li>
                <li class="on"><a href='<?php echo url::base() . 'user/user_charge_collect/virtual';?>'>会员竞波币汇总</a></li>
            </ul>
        </div>
        <?php 
         if(!isset($_GET['search_value'])) {
 			$date="";
         }
         else{
              $date = $_GET['search_value'];
        }
        ?>
        <div class="newgrid_top">

            <form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div>按账号搜索：                    <input type="text" name="search_name" class="text" value="">
                    <input type="submit" name="Submit2" value="搜索" class="ui-button-small">
                    <select name="search_value"  onchange="location.href=this.value">
					<?php
					$now_time = strtotime(date("Y-m-d"))+24*60*60; //当前时间
					$url=url::base() . url::current()."?search_value=";
					 echo "<option >选择日期</option>";
					for($i = 1; $i <= 365; $i++) {
					 $i_time = date("Y-m-d", $now_time - $i*24*60*60);
					 $sel=null;
					if($date==$i_time){$sel="selected='selected'" ;}
					 echo "<option value=$url$i_time $sel>" . $i_time . "</option>";
					}
					?>
					</select>
					<select name="search_value" onchange="location.href=this.value">
					<?php
					$now_month = (int)date("m"); 
					$now_year = date("Y");
					echo "<option>选择月份</option>";
					for($i =$now_month; $i >0; $i--) {
					 if($now_month<10){
					 	$year_month=$now_year.'0'.$i;
					 	$sel=null;
						if($date==$year_month){$sel="selected='selected'" ;}
					 	echo"<option value=$url$year_month $sel>".$year_month. "</option>";
					 }
					 else{
					 	$year_month=$now_year.$i;
					  	$sel=null;
						if($date==$year_month){$sel="selected='selected'" ;}
					 	echo"<option value=$url$year_month $sel>". $year_month."</option>";
					}
					}
					for($i =12; $i >0; $i--) {
						$last_year=$now_year-1;
					 if($i<10){
					 	$year_month=$now_year.'0'.$i;
					 	$sel=null;
						if($date==$year_month){$sel="selected='selected'" ;}
					 	echo"<option value=$url$year_month $sel>".$year_month. "</option>";
					 }else{
					 	$year_month=$now_year.$i;
					 	$sel=null;
						if($date==$year_month){$sel="selected='selected'" ;}
					 	echo"<option value=$url$year_month $sel>". $year_month."</option>";
					}
					}
					?>
					</select>
					<select name="search_value" onchange="location.href=this.value">
					<option>选择周数</option>
					<?php   
					$now_week=date("W");
					$now_year=date("y");
					$last_year=date("y")-1;
					for($i=$now_week;$i>0;$i--){
					$sel=null;
					if($date==$now_year."-".$i){$sel="selected='selected'" ;}
					echo"<option value=".$url.$now_year."-".$i." $sel>".$now_year."年".$i."周</option>";	
					}
					for($i=52;$i>0;$i--){
					$sel=null;
					if($date==$last_year."-".$i){$sel="selected='selected'" ;}
					echo"<option value=".$url.$last_year."-".$i." $sel>".$last_year."年".$i."周</option>";	
					}
					?>
					</select>
					<a href="<?php echo url::base() . url::current();?>">全部数据</a>
                </div>
            </form>

        </div>
        <?php if (is_array($user_list) && count($user_list)) {?>
        <table  cellspacing="0" class="table_overflow">
            <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                    <?php  if(!isset($_GET['order'])) {
 								$order="";
                    		}
                    		else{
                    			$order = $_GET['order'];
                    		}
        					if(!isset($_GET['page'])) {
 								$page="";
                    		}
                    		else{
                    			$page = $_GET['page'];
                    		}
                			if(!isset($_GET['per_page'])) {
 								$per_page=$set_per_page;
                    		}
                    		else{
                    			$per_page = $_GET['per_page'];
                    		}

                    		?>             
                           <th width="20px"><a href="<?php echo url::base().url::current();?>?order=id`<?php if($order=="id`DESC"){echo 'ASC';}else{echo 'DESC';}?>&search_value=<?php echo $date?>&search_value=<?php echo $date?>&page=<?php echo $page?>&per_page=<?php echo $per_page?>">id</a></th>
                           <th width="70px"><a href="<?php echo url::base().url::current();?>?order=lastname`<?php if($order=="lastname`DESC"){echo 'ASC';}else{echo 'DESC';}?>&search_value=<?php echo $date?>&page=<?php echo $page?>&per_page=<?php echo $per_page?>">账号</a></th>                      
                           <th width="100px"><a href="<?php echo url::base().url::current();?>?order=user_money`<?php if($order=="user_money`DESC"){echo 'ASC';}else{echo 'DESC';}?>&search_value=<?php echo $date?>&page=<?php echo $page?>&per_page=<?php echo $per_page?>">用户余额(当前)</a></th>                              
							<th width="60px"><a href="<?php echo url::base().url::current();?>?order=user_in`<?php if($order=="user_in`DESC"){echo 'ASC';}else{echo 'DESC';}?>&search_value=<?php echo $date?>&page=<?php echo $page?>&per_page=<?php echo $per_page?>">收入</th>                    
                           <th width="50px"><a href="<?php echo url::base().url::current();?>?order=user_is`<?php if($order=="user_is`DESC"){echo 'ASC';}else{echo 'DESC';}?>&search_value=<?php echo $date?>&page=<?php echo $page?>&per_page=<?php echo $per_page?>">支出</th> 
                                         
                           
                           
                           
                    </tr>
                </thead>
                <tbody>
                        <?php foreach ($user_list as $rs) : ?>
                    <tr>
                        
                        <td><?php echo $rs['id'];?>&nbsp;</td>
                        <td><?php echo $rs['lastname'];?>&nbsp;</td>
                        <td><?php echo $rs['user_money'];?>&nbsp;</td>
                        <td><?php echo $rs['user_in'];?>&nbsp;</td>       
                        <td><?php echo $rs['user_is'];?>&nbsp;</td> 
                        
                                               
                        
                        
                    </tr>
                        <?php endforeach;?>
                </tbody>
            </form>
        </table>
            <?php }else {?>
            <?php echo remind::no_rows();?>
            <?php }?>
    </div>
</div>
<!--**content end**-->
<!--FOOTER-->
<div class="new_bottom fixfloat">
    <div class="b_r_view new_bot_l">
       <?php echo view_tool::per_page(); ?>
    </div>
    <div class="Turnpage_rightper">
        <div class="b_r_pager">
            <?PHP echo $this->pagination->render('opococ'); ?>
        </div>
    </div>
</div>
<!--END FOOTER-->
<div id="site_msg" style='display:none;'>    
    <form id="msg_form" name="msg_form" class="new_search" method="post" action="<?php echo url::base();?>user/user/site_msg">
        <input type="hidden" name="uid" id="uid">
        <p>消息内容：</p>
        <textarea name='msg' cols='50' rows='6' class="text required"></textarea> <label><font color='red'>*</font></label>
        <br><br><center><input type="submit" value=" 发 送 " class="ui-button-small"></center>
    </form>
</div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(function() {
        var dialogOpts = {
            title: "群发站内消息",
            modal: true,
            autoOpen: false,
            height: 220,
            width: 400
        };
        $("#site_msg").dialog(dialogOpts);
        $("#batch_site_msg").click(function (){
                var i = false, uid = '';
                $('.sel').each(function(){
                    if($(this).attr("checked")==true){
                        i = true;
                        uid += $(this).attr("value") + ',';
                    }
                });
                if(i == false){
                    alert('请选择用户！');
                    return false;
                }
                $("#uid").val(uid);
                $("#site_msg").dialog("open");
                $("#msg_form").validate();
                return false;
            }
        );
        
        //会员的导出
        $("#export").click(function(){
            var arr = $("input[name='userids[]']");
            var str = 'export_point_user=1&';
            for(var i=0;i<arr.length;i++)
            {
                if(arr.eq(i).attr('checked'))
                {
                    str += 'userids[]='+arr.eq(i).val()+'&';
                }
            }
            str = '/user/user/export?'+str;
            location.href=str;
            return false;
        });
        //批量删除用户
        $("#batch_delete").click(function(){
            var i = false;
            $('.sel').each(function(){
                if(i == false){
                    if($(this).attr("checked")==true){
                        i = true;
                    }
                }
            });
            if(i == false){
                alert('请选择要停用的账号！');
                return false;
            }
            if(!confirm('停用的用户不能再登录而且不能用同样的邮箱注册，确认停用吗？')){
                return false;
            }
            $('#list_form').attr('action','/user/user/batch/delete');
            $('#list_form').submit();
            return false;
        });
        //批量恢复用户
        $("#batch_recover").click(function(){
            var i = false;
            $('.sel').each(function(){
                if(i == false){
                    if($(this).attr("checked")==true){
                        i = true;
                    }
                }
            });
            if(i == false){
                alert('请选择要恢复的账号！');
                return false;
            }
            if(!confirm('确认恢复吗？')){
                return false;
            }
            $('#list_form').attr('action','/user/user/batch/recover');
            $('#list_form').submit();
            return false;
        });
        
        $('#register_mail_active img').click(function(){
			var obj = $(this);
            if(obj.next().val() == 0)
            {
				if(confirm("点击确定将激活该账号,是否要激活该账号?"))
				{

					var user_id = obj.attr('rev');
					$.ajax({
	            		url: url_base + 'user/user/active_user',
	            		type: 'POST',
	            		data: 'user_id=' + user_id ,
	            		dataType: 'json',
	            		success: function(retdat, status){	
	            			if (retdat['code'] == 200 && retdat['status'] == 1) {
	            				obj.attr('src','/images/icon/accept.png');
	            				obj.next().val(1);
	            			} else {
	            				alert(retdat['msg']);
	            			}
	            		},
	            		error: function(){
	            			alert('Request error, please try again later!');
	            		}
	            	});
				}
            }
        	
        });
		
        
    });
</script>