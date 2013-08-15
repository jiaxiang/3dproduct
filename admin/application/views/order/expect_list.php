
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
            <li <?php if($expect_data['expect_type']==14) echo 'class="on"';?>><a href="/order/expect_list/sfc_14c">14场胜负彩与9场任选</a></li>
            <li <?php if($expect_data['expect_type']==6) echo 'class="on"';?>><a href="/order/expect_list/sfc_6c">6场半全场</a></li>
            <li <?php if($expect_data['expect_type']==4) echo 'class="on"';?>><a href="/order/expect_list/sfc_4c">4场进球</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
        
        
			 <script type="text/JavaScript">
            <!--
            function MM_jumpMenu(selObj,restore){ //v3.0
			document.URL=selObj.options[selObj.selectedIndex].value;
              if (restore) selObj.selectedIndex=0;
            }
            //-->
            </script>
      
        
            <form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div>搜索：
                    <select name="search_type" onchange="MM_jumpMenu(this,0)" class="text">
                    <?php rsort($expect_data['expects']);
					 foreach($expect_data['expects'] as $rs){?>
                        <option value="/order/expect_list/sfc_<?php echo $expect_data['expect_type'];?>c/<?php echo $rs;?>" <?php if ($rs == $cur_expect)echo "SELECTED";?> style="color:#ff0000"><?php echo $rs;?></option>
                     <?php }?>  
					<?php for($i=1;$i<=7;$i++) { ?>
                             <option value="/order/expect_list/sfc_<?php echo $expect_data['expect_type'];?>c/<?php echo ($expect_data['expect_num']-$i);?>" <?php if (($expect_data['expect_num']-$i) == $cur_expect)echo "SELECTED";?> style="color:#888888"><?php echo ($expect_data['expect_num']-$i);?></option>
                        <?php } ?>                       
                     
                      
                    </select>
                    <?php /*?>
                    <input type="text" name="search_value" class="text" value="<?php echo $expect_data['expect_num'];?>">
                    <input type="submit" name="Submit2" value="搜索" class="ui-button-small">
					*/?>
                </div>
            </form>            
        </div>
        
          <?php if ($expect_list) {?>      
            <table  cellspacing="0">
             <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="50"><input type="checkbox" id="check_all"></th>
                        <th width="10">场次</th>
                        <th width="40">期次</th>
                        <th width="70">赛事</th>
                        <th width="40">比赛时间</th>
                        <th width="70">主队</th>
                        <th width="40">客队</th>
                        <th width="20">赛果</th>
                        <th width="20">彩果</th>
                         <th width="80">开售时间</th>
                        <th width="80">停售时间</th>
                        <th width="80">开奖时间</th> 
                        <th width="20">详情</th>
                    </tr>
                </thead>
                <tbody id="list-line">
                
                        <?php foreach ($expect_list as $key=>$rs) { ?>
                        <tr id="2" name="<?php echo $rs['id'];?>" class="row">
                        <td><input class="sel" name="order_ids[]" value="<?php echo $rs['id'];?>" type="checkbox"><?php echo $rs['id'];?></td>
                        <td><?php echo $rs['changci'];?></td>
                        <td><?php echo $rs['expect_num'];?></td>                        
                         <td><?php echo $rs['game_event'];?></td>                       
                         <td><?php echo date("Y-m-d",strtotime($rs['game_time']));?></td>                
                        <td><?php echo $rs['vs1'];?></td>                        
                         <td><?php echo $rs['vs2'];?></td>                       
                         <td><?php echo $rs['game_result'];?></td>                        
                         <td><?php if($rs['cai_result']==="" or $rs['game_result']===""){?>
                         
	                        <div class="new_float_parent">
                                <input type="text" class="text" size="4" name="position" value="<?php echo $rs['cai_result'];?>" />
                                <div class="new_float" style="z-index:9999">
                                    <input type="text" class="text" size="4" name="order" value="<?php echo $rs['cai_result'];?>"/>
                                    <input type="button" class="ui-button-small" value="保存" name="submit_order_form" />
                                    <input type="hidden" name="id" value="<?php echo $rs['id']; ?>"/>
                                    <input type="button" class="ui-button-small" value="取消" name="cancel_order_form" />
                            	</div>                       
							</div>	

						 <?php }else{echo $rs['cai_result'];}?></td>   
                         <td><?php echo $rs['start_time'];?></td> 
                         <td><?php echo $rs['end_time'];?></td>                        
                         <td><?php echo $rs['open_time'];?></td>                
                         <td><?php if($rs['index_id']>0){?><a href="http://info.sporttery.cn/football/info/fb_match_info.php?m=<?php echo $rs['index_id'];?>" target="_blank">查看</a><?php }else{echo "---";}?></td> 
                    </tr>
                    <?php }?>
	 			</tbody>                
                <input name="backurl" type="hidden" value="2" />
                <input type="hidden" value="" id="content_user_batch" name="content_user_batch">
                <input type="hidden" value="" id="content_admin_batch" name="content_admin_batch">
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
    </div>
 
    <div class="Turnpage_rightper">
        <div class="b_r_pager">
        </div>
    </div>
</div>
<!--END FOOTER-->
<script type="text/javascript"> 
        var default_order = '0';
        $('input[name=position]').focus(function(){
            $('.new_float').hide();
            default_order = $(this).val();
            $(this).next().show();
            $(this).next().children('input[name=order]').focus();
        });
        $('input[name=cancel_order_form]').click(function(){
            $(this).parent().hide();
        });
        $('input[name=submit_order_form]').click(function(){
            var url = '/order/expect_list/set_cai_result';
            var obj = $(this).parent();
            var id = $(this).next().val();
            var order = encodeURIComponent($(this).prev().val());
            $(this).parent().hide();
			//alert(order);
            if(order == default_order){
                return false;
            }
            obj.prev().attr('disabled','disabled');
            $.ajax({
                type:'GET',
                dataType:'json',
                url:url,
                data:'id='+id+'&order='+order,
                error:function(){
					alert("网址错误");
				},
                success:function(retdat,status){
					//alert(order);
                    obj.prev().removeAttr('disabled');
					
                    if(retdat['status'] == 1 && retdat['code'] == 200)
                    {
                        obj.prev().attr('value',(retdat['content']['order']));
                    }else{
                        alert(retdat['msg']);
                    }
                }
            });
        });
</script>     
             </div>            
        </div>
</body>
</html>

