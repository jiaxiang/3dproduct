<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑下级用户</li>
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
                                    <th>代理：</th>
                                    <td><label><?php echo $agent['lastname']; echo '<'.$agent['id'].'>';?></label>
                                    </td>
								</tr>
                                <tr>
                                    <th>下级用户：</th>
                                    <td><?php echo $client['lastname'];?></td>
                                </tr>
								<tr>
									<th>用户邮箱：</th>
									<td><?php echo $client['email'];?></td>
                                </tr>                               
								<tr>
                                    <th>是否返利：</th>
                                    <td>
                                    <?php if ($relation['client_type'] == 0 || $relation['client_type'] == 1) {?>
                                    	<select name="client_type">
                                    		<option value="1" <?php if($relation['client_type'] == 1){echo 'selected';}?> >返利用户</option>
                                    		<option value="0" <?php if($relation['client_type'] == 0){echo 'selected';}?> >普通用户</option>
                                    	</select>
                                    	<span class="brief-input-state notice_inline">一旦设置成"普通用户"，则以下返点率则不生效</span>
                                    <?php } else if ($relation['client_type'] == 2) {
											echo '特殊二级代理';
                                    	  } else if ($relation['client_type'] == 12) {
											echo '二级代理';
                                    	  } 
                                    ?>
                                    </td>
                                </tr>
								<tr>
                                    <th>普通返点率：</th>
                                    <td>
                                    	<input type="text" size="60" name="client_rate" class="text" value="<?php echo $relation['client_rate'];?>">
                                    	<span class="brief-input-state notice_inline">返点率不能为负数，也不能超过代理的即时返点率</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">北单返点率：</th>
                                    <td>
                                    	<input type="text" size="60" name="client_rate_beidan" class="text" value="<?php echo $relation['client_rate_beidan'];?>">
                                    	<span class="brief-input-state notice_inline">返点率不能为负数，也不能超过代理的即时返点率</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">下级加入时间：</th>
                                    <td><?php echo $relation['date_add'];?></td>
                                </tr>
                                <tr>
                                	<th width="15%">状态</th>
                                	<td><?php echo ($relation['flag'] == 2) ? '有效' : '关闭'; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="list_save">
                    	<input type="button" class="ui-button" value="返回" onclick="javascript:window.history.back(-1);">
                        <input type="submit" class="ui-button" value=" 确认修改 " name="submit" />
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