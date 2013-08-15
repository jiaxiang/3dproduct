<script language="javascript">
setInterval("refresh()", 120000);
function refresh(){
  window.location.reload();
}
</script>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
            <li <?php if ($status == 'all') echo 'class="on"';?>><a href="/order/user_draw_money">会员提现管理</a></li>
            <li <?php if ($status == 'review') echo 'class="on"';?>><a href="/order/user_draw_money/index/review">审核中</a></li>
            <li <?php if ($status == 'hasreview') echo 'class="on"';?>><a href="/order/user_draw_money/index/hasreview">审核通过</a></li>
            <li <?php if ($status == 'reviewfail') echo 'class="on"';?>><a href="/order/user_draw_money/index/reviewfail">审核失败</a></li>
            <li <?php if ($status == 'hascharge') echo 'class="on"';?>><a href="/order/user_draw_money/index/hascharge">已打款</a></li>
            <li <?php if ($status == 'chargefail') echo 'class="on"';?>><a href="/order/user_draw_money/index/chargefail">打款失败</a></li>
            <li <?php if ($status == 'chargewin') echo 'class="on"';?>><a href="/order/user_draw_money/index/chargewin">提现成功</a></li>            
            </ul>
        </div>
        <div class="newgrid_top">
        <ul class="pro_oper">
<?php
switch ($status)
{              
	case 'review':
		echo '<li><a href="javascript:void(0);"><span class="rec_pro" id="hasreview">设为审核成功</span></a></li>';
		echo '<li><a href="javascript:void(0);"><span class="del_pro" id="reviewfail">设为审核失败</span></a></li>';
		break;
	case 'hasreview':
		//echo '<li><a href="javascript:void(0);"><span class="del_pro" id="chargefail">设为打款失败</span></a></li>';
		echo '<li><a href="javascript:void(0);"><span class="batch_down" id="exportinfo">导出信息</span></a></li>';
		break;	
	case 'hascharge':
		echo '<li><a href="javascript:void(0);"><span class="rec_pro" id="chargewin">设为提现成功</span></a></li>';
		break;
	default:
}            
?>
            </ul>
            
            <form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div>搜索：
                   <select name="search_type" class="text">
                        <option value="id" <?php if ($where['search_type'] == 'id')echo "SELECTED";?>>ID</option>
                        <option value="money" <?php if ($where['search_type'] == 'money')echo "SELECTED";?>>提取金额</option>
                        <option value="account" <?php if ($where['search_type'] == 'account')echo "SELECTED";?>>银行帐号</option>
                        <option value="truename" <?php if ($where['search_type'] == 'truename')echo "SELECTED";?>>姓名</option>
                        <option value="bank_name" <?php if ($where['search_type'] == 'bank_name')echo "SELECTED";?>>提款银行</option>
                        <option value="province" <?php if ($where['search_type'] == 'province')echo "SELECTED";?>>省份</option>
                        <option value="city" <?php if ($where['search_type'] == 'city')echo "SELECTED";?>>城市</option>
                        <option value="bank_found" <?php if ($where['search_type'] == 'bank_found')echo "SELECTED";?>>支行名称</option>
                    </select>
                    <input type="text" name="search_value" class="text" value="<?php echo $where['search_value'];?>">
                    <input type="submit" name="Submit2" value="搜索" class="ui-button-small">
                </div>
            </form>

        </div>
        <?php if (is_array($list) && count($list)) {?>
        <table cellspacing="0" class="table_overflow">
            <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="20"><input type="checkbox" id="check_all"></th>
                        <!-- <th width="30">ID</th> -->
                        <th width="180">用户信息</th>
                        <th width="250">银行信息</th>
                        <th width="60">提款金额</th>
                        <th width="50">状态</th>
                        <th width="50">操作</th>
                        <th>备注</th>
                        <th width="60">操作人</th>
                    </tr>
                </thead>
                <tbody>
                        <?php foreach ($list as $key=>$rs) { ?>
                    	<tr>
                        <td><input class="sel" name="order_ids[]" value="<?php echo $rs['id'];?>" type="checkbox"></td>
                        <!-- td><?php echo $rs['id'];?></td-->
                        
                        <td>
                        	用户名:<a href="/user/user/account/<?php echo $rs['user_id'];?>" target="_blank" ><?php echo $users[$rs['user_id']]['lastname'];?></a>
                        <br />
                        	姓名:<a href="/user/user/detail/<?php echo $rs['user_id'];?>" target="_blank" ><?php echo $rs['truename'];?></a>
                        <br />
                        	手机:<?php echo $users[$rs['user_id']]['mobile'];?>
                        <br />
                        	身份证:<?php echo $users[$rs['user_id']]['identity_card'];?>
                        </td>
                        
                        <td style="white-space:inherit;word-wrap:break-word;word-break:normal;">
                        	卡号:<?php echo $rs['account'];?>
                        <br />
                       		 银行:<?php echo $rs['bank_name'];?>
                        <br />
                        	省份:<?php echo $rs['province'];?>
                        <br />
                        	城市:<?php echo $rs['city'];?>
                        <br />
                        	支行:<?php echo $rs['bank_found'];?>
                        </td>
                        
                        <td style="white-space:inherit;word-wrap:break-word;word-break:normal;"><?php echo $rs['money'];?>
                        <?php
						$fee_moneys = 0;
                        if (!empty($rs['other']->fee_moneys))
						{
							$fee_moneys = $rs['other']->fee_moneys->USER_MONEY + $rs['other']->fee_moneys->BONUS_MONEY + $rs['other']->fee_moneys->FREE_MONEY;
						}
						
						if ($fee_moneys > 0)
						{
							echo '<br /><font color=red>手续费:'.$fee_moneys.'</font>';
						}
						?>
                        
                        </td>
                        <td style="white-space:inherit;word-wrap:break-word;word-break:normal;"><?php
						switch ($rs['status'])
						{
							case 0:
								echo '<font color=#aaa>审核中</font>';
								break;
						    case 1:
								echo '<font color="green">审核通过</font>';
								break;
						    case 2:
								echo '<font color="red"><s>审核失败<s></font>';
								break;
						    case 3:
								echo '<font color="blue">已打款</font>';
								break;
						    case 4:
								echo '<font color="red"><u><s>打款失败<s></u></font>';
								break;
						    case 5:
								echo '<font color="blue"><u>提现成功</u></font>';
								break;								
							default:
								break;
						}
						?></td>

                        <td style="white-space:inherit;word-wrap:break-word;word-break:normal;">
						<?php
						if ($status == 'hasreview')
							if ($rs['status'] == 1)
							{
								echo '<a href="/order/user_draw_money/set_hascharge/'.$rs['id'].'/'.$_GET['page'].'">财务录入</a>';
								echo '<br />';
								echo '<a href="/order/user_draw_money/set_chargefail/'.$rs['id'].'/'.$_GET['page'].'">打款失败</a>';	
							}							
						?>
                        </td>
                        
                        <td style="white-space:inherit;word-wrap:break-word;word-break:normal;">
                        <?php echo nl2br($rs['memo']);?>
                        </td>
                        
                       <td><?php if(!empty($rs['manager_id'])) echo $managers[$rs['manager_id']]['username'];?></td>                  
                    </tr>
               
			   <?php }?>
               
                </tbody>
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
        <?php echo view_tool::per_page(); ?>
    </div>

    <div class="Turnpage_rightper">
        <div class="b_r_pager">
            <?PHP echo $this->pagination->render('opococ'); ?>
        </div>
    </div>
</div>
<!--END FOOTER-->

<!-- 批量废除订单 -->
<div id="order_cancel_content" title="设为已打票" style="display:none;">
    <div class="dialog_box">
        <div class="out_box">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tbody>
                    <tr>
                        <td colspan="2">
                            <h3 class="title1_h3">确定要打印选中的彩票?</h3>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="list_save">
        <input name="order_cancel_btn" onclick="order_cancel();" type="button" value=" 确认 " class="ui-button"/>
        <input name="cancel" id="cancel_btn" type="button" value=" 取消 " class="ui-button" onclick='$("#order_cancel_content").dialog("close");'/>
    </div>
</div>

<!-- 批量配货 -->
<div id="shipping_processing_dialog" title="批量配货" style="display:none;">
    <div class="dialog_box">
        <div class="out_box">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tbody>
                    <tr>
                        <td colspan="2">
                            <h3 class="title1_h3">确认选择的订单是要处理为配货中！</h3>
                        </td>
                    </tr>
                    <tr>
                        <th>备注(管理员)：</th>
                        <td>
                            <textarea type="textarea" class="text" rows="3" cols="60" name="content_admin" id="content_admin"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>备注(会员)：</th>
                        <td>
                            <textarea type="textarea" class="text" rows="3" cols="60" name="content_user" id="content_user"></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="list_save">
        <input name="order_cancel_btn" onclick="shipping_processing();" type="button" value=" 确认 " class="ui-button"/>
        <input name="cancel" id="cancel_btn" type="button" value=" 取消 " class="ui-button" onclick='$("#shipping_processing_dialog").dialog("close");'/>
    </div>
</div>
<script type="text/javascript">        
		//设为通过审核
		$("#hasreview").click(function(){
		var i = false;
		var j = 0;
		$('.sel').each(function(){
			if(i == false){
				if($(this).attr("checked")==true){
					i = true;
				}
			}
		});
		if(i == false){
			alert('请选择要设为审核通过的款项！');
			return false;
		}
		$('.sel').each(function(){
			if($(this).attr("checked")==true){
				j++;
			}
		});
		if(j > 1){
			alert('每次只能选择1条款项！');
			return false;
		}
		if(!confirm('设为设为审核通过的款项将会进入财务打款列表，确认要进行此操作吗？')){
			return false;
		}
		$('#list_form').attr('action','/order/user_draw_money/set_hasreview');
		$('#list_form').submit();
		return false;
		});	
		
		
		//设为审核失败
		$("#reviewfail").click(function(){
		var i = false;
		var j = 0;
		$('.sel').each(function(){
			if(i == false){
				if($(this).attr("checked")==true){
					i = true;
				}
			}
		});
		if(i == false){
			alert('请选择要设为审核失败的款项！');
			return false;
		}
		$('.sel').each(function(){
			if($(this).attr("checked")==true){
				j++;
			}
		});
		if(j > 1){
			alert('每次只能选择1条款项！');
			return false;
		}
		if(!confirm('设为设为审核失败的款项将会返还冻结金额，确认要进行此操作吗？')){
			return false;
		}
		$('#list_form').attr('action','/order/user_draw_money/set_reviewfail');
		$('#list_form').submit();
		return false;
		});	


		//设为打款失败
		$("#chargefail").click(function(){
		var i = false;
		var j = 0;
		$('.sel').each(function(){
			if(i == false){
				if($(this).attr("checked")==true){
					i = true;
				}
			}
		});
		if(i == false){
			alert('请选择要设为打款失败的款项！');
			return false;
		}
		$('.sel').each(function(){
			if($(this).attr("checked")==true){
				j++;
			}
		});
		if(j > 1){
			alert('每次只能选择1条款项！');
			return false;
		}
		if(!confirm('设为设为打款失败的款项将会返还冻结金额，确认要进行此操作吗？')){
			return false;
		}
		$('#list_form').attr('action','/order/user_draw_money/set_chargefail');
		$('#list_form').submit();
		return false;
		});	


		//设为提现成功
		$("#chargewin").click(function(){
		var i = false;
		var j = 0;
		$('.sel').each(function(){
			if(i == false){
				if($(this).attr("checked")==true){
					i = true;
				}
			}
		});
		if(i == false){
			alert('请选择要设为提现成功的款项！');
			return false;
		}
		$('.sel').each(function(){
			if($(this).attr("checked")==true){
				j++;
			}
		});
		if(j > 1){
			alert('每次只能选择1条款项！');
			return false;
		}
		if(!confirm('设为设为提现成功款项将会永久扣除冻结的金额，确认要进行此操作吗？')){
			return false;
		}
		$('#list_form').attr('action','/order/user_draw_money/set_chargewin');
		$('#list_form').submit();
		return false;
		});	
		
		//导出所选彩票
		$("#exportinfo").click(function(){
		var i = false;
		$('.sel').each(function(){
			if(i == false){
				if($(this).attr("checked")==true){
					i = true;
				}
			}
		});
		if(i == false){
			alert('请选择要导出的款项！');
			return false;
		}
		if(!confirm('确认要导出所选的款项吗？')){
			return false;
		}
		$('#list_form').attr('action','/order/user_draw_money/exportinfo');
		$('#list_form').submit();
		return false;
		});		
</script>


