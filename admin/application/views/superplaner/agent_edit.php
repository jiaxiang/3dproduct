<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑代理</li>
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
                                    <th>用户名：</th>
                                    <td><label><?php echo $agent['lastname']; echo '<'.$agent['id'].'>';?></label>
                                    </td>
								</tr>
                                <tr>
                                    <th>姓名：</th>
                                    <td><input type="text" size="60" name="realname" class="text" value="<?php echo $agent['realname'];?>">
                                    	<span class="brief-input-state notice_inline">请不要超过50字节。</span>	
                                    </td>
                                </tr>
								<tr>
									<th>手机号码：</th>
									<td>
										<input type="text" size="60" name="mobile" class="text" value="<?php echo $agent['mobile'];?>">
                                    	<span class="brief-input-state notice_inline">请不要超过50字节。</span>	
									</td>
                                </tr>                               
								<tr>
                                    <th>固定电话：</th>
                                    <td><input type="text" size="60" name="tel" class="text" value="<?php echo $agent['tel'];?>">
                                    	<span class="brief-input-state notice_inline">请不要超过50字节。</span>	
                                    </td>
                                </tr>
								<tr>
                                    <th>QQ号码：</th>
                                    <td><input type="text" size="60" name="qq" class="text" value="<?php echo $agent['qq'];?>">
                                    	<span class="brief-input-state notice_inline">请不要超过50字节。</span>	
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">代理创建时间：</th>
                                    <td><?php echo $agent['createtime'];?>
                                    </td>
                                </tr>
                                <!-- 
                                <tr>
                                    <th width="15%">代理生效日期：</th>
                                    <td><input type="text" size="60" name="starttime" id="starttime" class="text" value="<?php echo $agent['starttime'];?>">
                                    </td>
                                </tr>
                                 -->
                                <tr>
                                	<th width="15%">状态</th>
                                	<td><?php if ($agent['flag'] == 2) {echo '有效';} ?>
                                		<?php if ($agent['flag'] == 0) {echo '关闭';} ?>
                                	</td>
                                </tr>
                                <tr>
                                	<th width="15%">代理类型</th>
                                	<td>
                                		<select name="agent_type">
                                			<option <?php if ($agent['agent_type'] == 0) {echo 'selected';} ?> value="0">普通</option>
                                			<option <?php if ($agent['agent_type'] == 1) {echo 'selected';} ?> value="1">超级代理</option>
                                			<option <?php if ($agent['agent_type'] == 2) {echo 'selected';} ?> value="2">二级代理</option>
                                		</select>
                                	</td>
                                </tr>
                                <tr>
                                    <th>上级代理ID：</th>
                                    <td><input type="text" size="60" name="up_agent_id" class="text" value="<?php echo $agent['up_agent_id'];?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>邀请码：</th>
                                    <td><input type="text" size="60" name="invite_code" class="text" value="<?php echo $agent['invite_code'];?>">
                                    </td>
                                </tr>
                                <tr>
                                	<th>用户备注：</th>
                                	<td>
                                		<textarea maxlength="255" type="textarea" class="text valid" rows="5" cols="56" name="note"><?php echo $agent['note'];?></textarea>
                                		<span class="brief-input-state notice_inline">用户备注，请不要超过250字节。</span>
                                	</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="list_save">
                    	<input type="button" class="ui-button" value="返回" onclick="javascript:window.history.back(-1);">
                        <input name="submit" type="submit" class="ui-button" value=" 确认修改 ">
                    </div>
                    <div class="clear">&nbsp;</div>
                </form>
                
            </div>
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
	    $('#starttime').datepicker({dateFormat:"yy-mm-dd"});

        $("#add_form").validate({
			rules:{
        		mail: {
					maxlength: 255
				},
				firstname: {
					maxlength: 50
				},
				lastname: {
					maxlength: 50
				}
			},
    		messages:{
				mail: {
					required: '邮箱不可为空',
					maxlength: '邮箱长度不可超过 255 个字符'
				},
				firstname: {
					required: '名不可为空',
					maxlength: '名长度不可超过 50 个字符'
				},
				lastname: {
					required: '姓不可为空',
					maxlength: '姓长度不可超过 50 个字符'
				}
			}
        });
        
    });
</script>