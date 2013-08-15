<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑合约</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--** edit start**-->
            <div class="edit_area">
                <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current();?>">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width=20%>代理用户名(ID)</th>
                                    <td><?php echo $user['lastname'].'('.$user['id'].')'; ?></td>
                                </tr>
                                <tr>
                                    <th>类型</th>
                                    <td><select name="type">
                                    		<option value="0" <?php if($contract['type'] == 0) echo 'selected'; ?>>普通</option>
                                    		<option value="7" <?php if($contract['type'] == 7) echo 'selected'; ?>>北单</option>
                                    	</select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>实时返点率</th>
                                    <td><input type="text" size="30" name="rate" class="text" value="<?php echo $contract['rate'];?>"><span class="required"> *</span></td>
                                </tr>
                                
                                <tr>
                                    <th>合约创建时间</th>
                                    <td><?php echo $contract['createtime'];?></td>
                                </tr>
                                <tr>
                                    <th>合约生效时间</th>
                                    <td>
                                    	<input type="text" name="starttime" class="text" value="<?php echo $contract['starttime'];?>" id="starttime">
                                    </td>
                                </tr>
                                <tr>
                                    <th>最后一次结算</th>
                                    <td><?php echo $contract['lastsettletime'];?></td>
                                </tr>
                                <tr>
                                    <th>返利税率</th>
                                    <td><input type="text" size="30" name="taxrate" class="text" value="<?php echo $contract['taxrate'];?>"><span class="required"> *</span></td>
                                </tr>
                                <tr>
                                    <th>状态</th>
                                    <td><?php if ($contract['flag'] == 2) {echo '生效'; }
                                    		else {echo '关闭'; } ?>
                                    </td>
                                </tr>
                                <tr>
                                	<th>备注：</th>
                                	<td>
                                		<textarea maxlength="255" type="textarea" class="text valid" rows="5" cols="56" name="note"><?php echo $contract['note']; ?></textarea>
                                		<span class="brief-input-state notice_inline">用户备注，请不要超过250字节。</span>
                                	</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="list_save">
                    	<input type="button" class="ui-button" value=" 返回 " onclick="javascript:window.history.back(-1);">
                        <input type="submit" name="submit" class="ui-button" value=" 确认修改 " >
                    </div>
                </form>
            </div>
            <!--** edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->

<div id='example' style="display:none;"></div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
    	$('#starttime').datepicker({dateFormat:"yy-mm-dd"});
    });

</script>