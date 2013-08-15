<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加页面分类</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--** content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**productlist edit start**-->
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current(true);?>">
                <div class="edit_area">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="15%">分类名称：</th>
                                    <td>
                                        <input type="type" size="50" maxlength="250" name="category_name" class="text required" value="<?php echo $form_data['category_name'];?>" /><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>上级分类：</th>
                                    <td>
                                        <select name="parent_id" class="required">
                                            <option value="0"> 顶级分类 </option>
                                            <?php foreach($news_categories as $key=>$value):?>
                                            <option value="<?php echo $value['id'];?>" <?php echo ($form_data['parent_id'] == $value['id'])?'selected':'';?>>
                                                    <?php for ($i = 1;$i < $value['level_depth'];$i++):?>
                                                &#166;&nbsp;
                                                    <?php endfor; ?>
                                                    <?php echo $value['category_name'];?>
                                            </option>
                                            <?php endforeach;?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>排序：</th>
                                    <td>
                                        <input type="type" size="5" name="title" class="text required number" value="<?php echo $form_data['p_order'];?>" maxlength="5"/>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <input type="button" name="button" class="ui-button" value="保存返回列表"  onclick="submit_form();"/>
                    <!-- <input type="button" name="button" class="ui-button" value="保存继续添加"  onclick="submit_form(1);"/> -->   
                    <input type="hidden" name="submit_target" id="submit_target" value="0" />
                </div>
            </form>
            <!--**productlist edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/jq/plugins/tinymce/tiny_mce.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate();
    });
</script>