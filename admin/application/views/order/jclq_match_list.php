<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
            <li class="on"><a href="/order/jczq_match_list/match_list">竞彩篮球</a></li>
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
        <a href="http://info.sporttery.cn/basketball/match_result.php" target="_blank">竞彩赛果开奖</a>&nbsp;&nbsp;格式：主胜|让分主胜-3.5/让分主胜-2.5/让分主胜-1.5|主胜1-5|大147.5/大148.5|(79:84)，赛事取消填cancel
        </div>
          <?php if (count($match_list) > 0) {?>      
            <table  cellspacing="0">
             <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="50"><input type="checkbox" id="check_all">id号</th>
                        <th width="40">赛事编号</th>
                        <th width="40">赛事名称</th>
                        <th width="40">联赛名称</th>
                        <th width="70">主队 VS 客队</th>
                        <th width="40">赛果</th>
                        <th width="40">比赛时间</th>
                    </tr>
                </thead>
                <tbody id="list-line">
                        <?php foreach ($match_list as $key=>$rs) { ?>
                        <tr id="<?php echo $rs['id'];?>" class="row">
                        <td><input class="sel" name="order_ids[]" value="<?php echo $rs['id'];?>" type="checkbox"><?php echo $rs['id'];?></td>
                        <td><?php echo $rs['index_id'];?></td>
                        <td><?php echo $rs['match_info'];?></td>                        
                         <td><?php echo $rs['league'];?></td> 
						<td><?php echo $rs['host_name'];?> VS <?php echo $rs['guest_name'];?>&nbsp;<a href="http://info.sporttery.cn/basketball/pool_result.php?id=<?=$rs['index_id']?>" target="_blank">赛果</a></td>            
                         <td>
	                        <div class="new_float_parent">
                                <input type="text" class="text" size="4" name="position" value="" />
                                <div class="new_float" style="z-index:9999">
                                    <input type="text" class="text" size="20" name="order" value="" />
                                    <input type="button" class="ui-button-small" value="保存" name="submit_order_form" />
                                    <input type="hidden" name="id" value="<?php echo $rs['id']; ?>" />
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
            var url = '/order/jczq_match_list/set_result';
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