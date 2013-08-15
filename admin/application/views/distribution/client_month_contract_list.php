<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
	<div class="newgrid">
	
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on">
					<a href='<?php echo url::base().'distribution/client_month_contract/index?relationId='.$relation['id']; ?>'>
						下级用户&nbsp;<?php echo $client['lastname'];?>&nbsp;的月结合约</a>
				</li>
			</ul>
		</div>
		<div class="newgrid_top">
			<ul class="pro_oper">
				<li>
					<a href="/distribution/client_month_contract/add/<?php echo $relation['id']; ?>">
						<span class="add_pro">添加该下级用户的月结合约</span></a>
				</li>
			</ul>
        </div>
        
        <?php if (is_array($contractList) && count($contractList)) {?>
		<form id="list_form" name="list_form" method="post" action="<?php echo url::base().url::current();?>">
			<table  cellspacing="0" >
				<thead>
					<tr class="headings">
						<th width="50px">操作</th>
						<th width="30px">上级代理UserId</th>
						<th width="30px">下级用户UserId</th>
						<th width="30px">合约类型(合约号)</th>
						<th width="30px">彩种分类</th>
						<th width="60px">合约创建日期</th>
						<th width="60px">合约生效日期</th>
						<th width="60px">最后一次结算</th>
						<th width="30px">备注</th>
						<th width="20px" class="txc">状态</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($contractList as $item) { ?>
					<tr>
						<td>
							<a href="<?php echo url::base().'distribution/client_month_contract/detail/'.$item['id']; ?>" target="_blank">查看</a>&nbsp;
							<?php if($item['flag']==0) {?>
							<a href="<?php echo url::base().'distribution/client_month_contract/open/'.$item['id']; ?>">生效</a>&nbsp;
							<?php } else {?>
							<a href="<?php echo url::base().'distribution/client_month_contract/close/'.$item['id']; ?>">关闭</a>&nbsp;
							<?php } ?>
							<a href="<?php echo url::base().'distribution/client_month_contract/delete/'.$item['id']; ?>" 
								 onclick="javascript:return confirm('确定删除？')">删除</a>&nbsp;
						</td>
						<td><?php echo $agent['lastname'].'('.$item['agent_id'].')' ;?></td>
						<td><?php echo $client['lastname'].'('.$item['user_id'].')' ;?></td>
						<td>
							<?php 
								if ($item['contract_type'] == 0) {
									echo '普通返利';
								}else if($item['contract_type'] == 1) {
									echo '下线返利';
								}else if($item['contract_type'] == 2) {
									echo '二级代理返点';
								}
								echo '('.$item['id'].')';
							?>
						</td>
						<td><?php echo ($item['type'] == 7) ? '北单' : '普通'; ?></td>
						<td><?php echo $item['createtime'];?></td>
						<td><?php echo $item['starttime'];?></td>
						<td><?php echo $item['lastsettletime'];?></td>
						<td><?php echo $item['note'];?></td>
						<td class="txc"><?php echo ($item['flag'] == 2) ? '生效':'关闭';?></td>
					</tr>
					<?php }?>
                </tbody>
	        </table>
		</form>
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
        var default_rate = '0';
        $('input[name=rate]').focus(function(){
            $('.new_float').hide();
            default_rate = $(this).val();
            $(this).next().show();
            $(this).next().children('input[name=client_rate]').focus();
        });
        $('input[name=cancel_order_form]').click(function(){
            $(this).parent().hide();
        });
        $('input[name=submit_order_form]').click(function(){
            var url = '<?php echo url::base();?>distribution/agent_client/set_client_rate';
            var obj = $(this).parent();
            var relationId = $(this).next().val();
            var rate = $(this).prev().val();
            $(this).parent().hide();
            if (rate == default_rate){
                return false;
            }
            obj.prev().attr('disabled','disabled');
            $.ajax({
                type:'GET',
                dataType:'json',
                url:url,
                data:'relationId=' + relationId + '&client_rate=' + rate,
                error:function(){},
                success:
                    function(retdat,status){
                    obj.prev().removeAttr('disabled');
                    if(retdat['status'] == 1 && retdat['code'] == 200)
                    {
                        obj.prev().attr('value',(retdat['content']['client_rate']));
                    }else{
                        alert(retdat['msg']);
                    }
                }
            });
        });
</script>

<script type="text/javascript">
        var default_rate = '0';
        $('input[name=rate]').focus(function(){
            $('.new_float').hide();
            default_rate = $(this).val();
            $(this).next().show();
            $(this).next().children('input[name=client_rate]').focus();
        });
        $('input[name=cancel_order_form]').click(function(){
            $(this).parent().hide();
        });
        $('input[name=submit_order_form]').click(function(){
            var url = '<?php echo url::base();?>distribution/agent_client/set_client_rate';
            var obj = $(this).parent();
            var relationId = $(this).next().val();
            var rate = $(this).prev().val();
            $(this).parent().hide();
            if (rate == default_rate){
                return false;
            }
            obj.prev().attr('disabled','disabled');
            $.ajax({
                type:'GET',
                dataType:'json',
                url:url,
                data:'relationId=' + relationId + '&client_rate=' + rate,
                error:function(){},
                success:
                    function(retdat,status){
                    obj.prev().removeAttr('disabled');
                    if(retdat['status'] == 1 && retdat['code'] == 200)
                    {
                        obj.prev().attr('value',(retdat['content']['client_rate']));
                    }else{
                        alert(retdat['msg']);
                    }
                }
            });
        });
</script>