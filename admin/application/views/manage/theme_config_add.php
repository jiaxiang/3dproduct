<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate();
        $("#config_type").val(<?php echo $type;?>);
        show_val();
    });
    function show_val(){
        var type_val = $("#config_type").val();
        if(type_val == '2'){
            $(".image_url_row").css('display','');
        }else{
            $(".image_url_row").css('display','none');
        }
        $(".val").each(function(){
            if($(this).attr('id') == 'val_'+type_val){
                $(this).addClass('required');
                $(this).css('display','');
            }else{
                $(this).removeClass('required');
                $(this).css('display','none');
            }
        });  
        
        $("label[class='error']").hide();
        $(":input").removeClass('error');     
    }
</script>
<!-- header_content -->
<div class="new_sub_menu_con">
    <div class="newgrid_tab fixfloat">
        <ul>
            <li class="on">模板配置信息</li>
        </ul>
    </div>
    <div class="newgrid_top">
        <ul class="pro_oper">
            <li>
                <a href="/manage/theme/config/<?php echo $id;?>" ><span class="add_pro">查看配置</span></a>
            </li>
        </ul>
    </div>  
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**edit start**-->
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current();?>" enctype="multipart/form-data">
                <div class="edit_area">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th>标题：</th>
                                    <td>
                                        <input type="text" class="text required" size="50" name="config_name">
                                        <span class="required">*</span>
                                        <span class="notice_inline">配置标题</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>标记：</th>
                                    <td>
                                        <input type="text" class="text required" size="40" name="config_flag">
                                        <span class="required">*</span>
                                        <span class="notice_inline">惟一标识，一个主题不要出现重复，请用英文、数字。</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">类型：</th>
                                    <td>
                                        <select name="config_type" id="config_type" onchange="show_val();" class="text">
                                            <option value="1">文本</option>
                                            <option value="2">图片</option>
                                            <option value="3">链接</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                        <span id="config_title">内容：</span>
                                    </th>
                                    <td>
                                        <input type="text" class="text val" size="60" name="text_val" id="val_1">
                                        <input type="file" class="text val" size="60" name="img_val" id="val_2" style="display:none;">
                                        <input type="text" class="text val" size="60" name="link_val" id="val_3" style="display:none;">
                                        <span class="required">*</span><span class="notice_inline image_url_row">图片尺寸：小于 1MB;可用扩展名：gif,png,jpg.</span>
                                    </td>
                                </tr>
                                <tr class="image_url_row" style="display: none;">
                                    <th width="20%">
                                        <span id="config_title">图片链接：</span>
                                    </th>
                                    <td>
                                        <input type="text" class="text" size="60" name="img_url"><span class="notice_inline">填写站点内链接，不需要带域名。</span>
                                    </td>
                                </tr>
                                <tr class="image_url_row" style="display: none;">
                                    <th width="20%">
                                        <span id="config_title">图片Alt标签：</span>
                                    </th>
                                    <td>
                                        <input type="text" class="text" size="50" name="img_alt">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                     <input name="submit" type="submit" class="ui-button" value=" 确认添加 ">
                </div>
            </form>
            <!--**edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->