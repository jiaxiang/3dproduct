<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加文案链接导航</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**address_menu edit start**-->
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current();?>">
                <div class="edit_area">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                	<th>上级导航：</th>
                                	<td>
                                		<select name="parent_id">
                                			<option value="0">----</option>
                                            <?php foreach($site_menus as $key=>$value):?>
                                            <option value="<?php echo $value['id'];?>" >
                                            	<?php for($i=1;$i<$value['level_depth'];$i++):?>
                                                	&#166;&nbsp;
                                            	<?php endfor;?>
                                                <?php echo $value['name'];?>
                                                </option>
                                            <?php endforeach;?>
                                		</select>
                                	</td>
                                </tr>
                                <tr>
                                    <th width="15%">标题：</th>
                                    <td>
                                        <input type="text" size="50" id="name" name="name" class="text required" value="" /><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>标签：</th>
                                    <td>
                                        <input type="text" size="40" id="title" name="title" class="text required" /><span class="required"> *</span>
                                    </td>
                                </tr>
								<tr>
									<th>选择文案：</th>
									<td>
										<select name="doc_id" id="doc_id" class="required">
                                            <?php foreach($site_docs as $doc):?>
                                            <option value="<?php echo $doc['id'];?>">
                                            	<?php echo $doc['title'];?>
                                            </option>
                                            <?php endforeach;?>
                                        </select>
									</td>
								</tr>
                                <tr>
                                    <th>打开方式：</th>
                                    <td>
                                        <input type="radio" checked="" value="1" name="target">本页面打开
                                        <input type="radio" value="0" name="target">新页面打开
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <input type="button" name="button" class="ui-button" value="保存返回列表"  onclick="submit_form();"/>
                    <input type="button" name="button" class="ui-button" value="保存继续添加"  onclick="submit_form(1);"/>
                    <input type="hidden" name="submit_target" id="submit_target" value="0" />
                </div>
            </form>
            <!--**address_menu edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate();
    });
</script>

