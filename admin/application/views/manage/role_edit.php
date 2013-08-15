<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑权限模板</li>
            </ul>
            <span class="fright">
                <?php if(site::id()>0):?>
                当前站点：<a href="http://<?php echo site::domain();?>" target="_blank" title="点击访问"><?php echo site::domain();?></a> [ <a href="<?php echo url::base();?>manage/site/out">返回</a> ]
                <?php endif;?>
            </span>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**productlist edit start**-->
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base().url::current();?>">
                <div class="edit_area">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="20%"> 名称： </th>
                                    <td width="80%">
                                        <input size="30" maxlength="20" name="name" class="text required" value="<?php echo $data['name'];?>"><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th> 上级分组： </th>
                                    <td>
                                        <select name="parent_id" id="parent_id" class="text">
                                            <option value=""> ---- </option>
                                            <?php foreach($roles as $key=>$value) {?>
                                            <option value="<?php echo $value['id'];?>" <?php echo ($data['parent_id'] == $value['id'])?'selected':'';?>>
                                                    <?php for($i=1;$i<$value['level_depth'];$i++) {?>
                                                &#166;&nbsp;
                                                        <?php } ?>
                                                    <?php echo $value['name'];?></option>
                                                <?php } ?>
                                        </select>
                                        [不选则表示使作为顶级分类]
                                    </td>
                                </tr>
                                <?php 
								/**
								?>
                                <tr>
                                    <th> 类型： </th>
                                    <td>
                                        <input name="type" type="radio" value="1" <?php echo ($data['type'] == 1)?"checked":"";?>>
                                        管理员
                                        <input name="type" type="radio" value="0" <?php echo ($data['type'] == 0)?"checked":"";?>>
                                        商家

                                    </td>
                                </tr>
								<?php
								**/
								?>
								
								
                                <tr>
                                    <th>状态：</th>
                                    <td>
                                        <input name="active" type="radio" value="1" <?php echo ($data['active'] == 1)?"checked":"";?>>
                                        可用
                                        <input type="radio" name="active" value="0" <?php echo ($data['active'] == 0)?"checked":"";?>>
                                        不可用
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <input name="submit" type="submit" class="ui-button" value=" 保存 ">
                </div>
            </form>
            <!--**productlist edit end**-->
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