<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑模板</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**productlist edit start**-->
            <div class="edit_area">
                <form id="add_form" name="add_form" method="post" action="/manage/theme/post_save" enctype="multipart/form-data">
                    <input type='hidden' name='id' value="<?php echo isset($data['id'])?$data['id']:0; ?>">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th>
                                        主题名称：
                                    </th>
                                    <td>
                                        <input size="30" name="name" class="text required" value="<?php echo isset($data['name'])?$data['name']:''; ?>" /><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        主题等级：
                                    </th>
                                    <td>
                                        <select name="grade" class="text required">
                                            <option value="1" <?php if (isset($data['grade']) && $data['grade'] == 1) echo 'selected'; ?>> 等级1 </option>
                                            <option value="2" <?php if (isset($data['grade']) && $data['grade'] == 2) echo 'selected'; ?>> 等级2 </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>说明:</th>
                                    <td class="d_line">
                                        <textarea name="brief" cols="75" rows="5" class="text" type="textarea" maxlength="255"><?php echo isset($data['brief'])?$data['brief']:''; ?></textarea>
                                        <span class="brief-input-state notice_inline">简短的菜单功能介绍，请不要超过255字节。</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="25%">
                                        主题图片：
                                    </th>
                                    <td>
                                        <div class="templateimg">
                                            <img width="180" src="/att/theme/theme<?php echo isset($data['id'])?$data['id']:''; ?>_180_200.jpg" alt="<?php echo isset($data['name'])?$data['name']:'';?>"/>
                                        </div>
                                        <input type="file" name="theme_image" size='100' class="text">
                                    </td>
                                </tr>
                                <!-- -->
                                <tr>
                                <th>主题包: </th>
                                <td>
                                <input type="file" name="theme_file" />
                                </td>
                                </tr>
                                <tr>
                                <th>状态: </th>
                                <td>
                                <input name="active" type="radio" value="1" checked>
                                可用
                                <input type="radio" name="active" value="0">
                                不可用
                                </td>
                                </tr>
                                <!-- -->
                            </tbody>
                        </table>
                    </div>
                    <div class="list_save">
                        <input type="button" name="button" class="ui-button" value="保存"  onclick="submit_form(1);"/>
                    </div>
                </form>
            </div>
            <!--**productlist edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
    	$("#add_form").validate({
            errorPlacement:function(error, element){
                if(element.attr("name") == "brief"){
                    //alert(error);
                    error.appendTo( element.parent());
                }else{
                    error.insertAfter(element)
                }
            }
        });
    });
</script>