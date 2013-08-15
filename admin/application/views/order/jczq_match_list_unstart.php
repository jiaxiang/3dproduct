<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
            <li class="on"><a href="/order/jczq_match_list/match_list/1">赛果更新</a></li>
            <li class="on"><a href="/order/jczq_match_list/match_list_unstart/1">赛事取消</a></li>
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
        <a href="http://info.sporttery.cn/roll/listn.php" target="_blank">官方公告</a>&nbsp;&nbsp;
        格式：标识0为可投注比赛，标识1为取消投注比赛
        </div>
          <?php if (count($match_list) > 0) {?>      
            <table  cellspacing="0">
             <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="60"><input type="checkbox" id="check_all">id号</th>
                        <th width="20">赛事编号</th>
                        <th width="20">赛事名称</th>
                        <th width="120">联赛名称</th>
                        <th>主队 VS 客队</th>
                        <th width="20">标识</th>
                        <th width="120">比赛时间</th>
                    </tr>
                </thead>
                <tbody id="list-line">
                        <?php 
                        foreach ($match_list as $key=>$rs) { 
                        	if ($rs['pool_id'] == 0) {
                        		$pool = '<font color="green">可投注</font>';
                        	}
                        	else {
                        		$pool = '<font color="red">已取消</font>';
                        	}
                        ?>
                        <tr id="<?php echo $rs['id'];?>" class="row">
                        <td><input class="sel" name="order_ids[]" value="<?php echo $rs['id'];?>" type="checkbox"><?php echo $rs['id'];?></td>
                        <td><?php echo $rs['index_id'];?></td>
                        <td><?php echo $rs['match_info'];?></td>                        
                         <td><?php echo $rs['league'];?></td> 
						<td><?php echo $rs['host_name'];?> VS <?php echo $rs['guest_name'];?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $pool;?></td>            
                         <td>
	                        <div class="new_float_parent">
                                <input type="text" class="text" size="4" name="position" value="" />
                                <div class="new_float" style="z-index:9999">
                                    <input type="text" class="text" size="20" name="order" value="" />
                                    <input type="button" class="ui-button-small" value="保存" name="submit_order_form" />
                                    <input type="hidden" name="id" value="<?php echo $rs['index_id']; ?>" />
                                    <input type="button" class="ui-button-small" value="取消" name="cancel_order_form" />
                            	</div>                       
							</div>	
						</td>  
						<td><?php echo $rs['time'];?></td>              
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
            var url = '/order/jczq_match_list/set_match_cancel';
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