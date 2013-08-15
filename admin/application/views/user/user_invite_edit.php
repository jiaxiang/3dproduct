<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">邀请奖励</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="newgrid">
            <!--** edit start**-->
            <div class="out_box">
                <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current();?>">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th>被邀请人Email：</th>
                                    <td>
                                    	<?php echo $data['email'];?>
                                    </td>
                                </tr>
                                 <tr>
                                    <th>被邀请人昵称：</th>
                                    <td>
                                    	<?php echo $data['lastname'];?>
                                    </td>
                                </tr>
                              	<tr>
                                    <th width="15%">被邀请人注册时间：</th>
                                    <td><?php echo $data['date_add'];?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>被邀请人注册IP：</th>
                                    <td><?php echo long2ip($data['ip']);?></td>
                                </tr>
                                
                                <tr>
                                    <th>邀请人Email：</th>
                                    <td>
                                    	<?php echo $data['invite']['email'];?>
                                    </td>
                                </tr>                                 
                                <tr>
                                    <th>邀请人昵称：</th>
                                    <td>
                                    	<?php echo $data['invite']['lastname'];?>
                                    </td>
                                </tr>   
                                                     
                                <tr>
                                    <th>奖励邀请人：</th>
                                    <td>
                                    
                                    <?php if (empty($data['reward'])) {?>
                                    
                                    	<input type="text" size="10" maxlength="5" id="reward_money" name="reward_money" class="text required" value=""><span class="required"> *</span>
                                    	<input name="invite_user_id" type="hidden" value="<?php echo $data['invite']['id'];?>" />
                                    	<input name="user_id" type="hidden" value="<?php echo $data['id'];?>" />
                                    <?php }else{?>
                                    	 已成功奖励了 <em><?php echo $data['invite']['lastname'];?></em> ，<a href="<?php echo url::base()?>user/user_invite">>>点击此处返回列表页<<</a>
                                    <?php }?>
                                    
                                    </td>
                                </tr> 

                            </tbody>
                        </table>
                    </div>
                    <div class="list_save">
                    <?php if (empty($data['reward'])) {?>
                        <input name="submit" type="submit" class="ui-button" value=" 确认奖励 ">
                    <?php }?>
                    </div>
                    <div class="clear">&nbsp;</div>
                </form>

            <!--** edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<div id='example' style="display:none;"></div>
<div id='address' style="display:none;"></div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate({
			rules:{
        		reward_money: {
					required:true,
					maxlength: 5,
					digits:true 
				},
			},
    		messages:{
				reward_money: {
					required: '奖励邀请人不能为空',
					maxlength: '长度不能超过5个字符',
					number: '必须输入正确的数字'
				},
			}
        });
    });
</script>