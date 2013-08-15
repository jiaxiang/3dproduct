<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">批量更新商品的SEO信息</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--** seo edit start**-->
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current(TRUE);?>">
                <div class="out_box">
                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tbody>
                            <tr>
                                <th width=200>分类：</th>
                                <td>
                                    <select name="category_id" id="category_id" class="required text">
                                        <option value="0"> -所有分类- </option>
                                        <?php echo $categorys_tree;?>
                                    </select><span id="category_change_tips" class="valierror"></span>
                                    <span class="require-field">*选择所有分类则更新所有分类商品的SEO信息。</span>
                                </td>
                            </tr>
                            <tr id="is_children_tr" style="display:none">
                                <th>是否包含子分类：</th>
                                <td><input id="is_children" type="checkbox" name="is_children" value="1"></td>
                            </tr>
                            <tr>
                                <th>站点域名：</th>
                                <td>
                                    <input id="domain" size="40" name="domain" class="text required" autocomplete="off"><span class="required"> *</span>
                                    <span class="require-field">输入站点域名：<b><font color=blue><?php echo $site['domain'];?></b></font>，防止恶意操作或者操作失误。</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Meta Title:</th>
                                <td>
                                    <input id="meta_title" type="input" size="80" name="meta_title" class="text required" maxlength="255" value="<?php if(isset($data['meta_title'])) echo $data['meta_title']?>" maxlength="255"><span class="required"> *</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Mata Keywords: </th>
                                <td>
                                    <input id="meta_keywords" type="input" size="80" name="meta_keywords" class="text required" maxlength="255" value="<?php if(isset($data['meta_keywords'])) echo $data['meta_keywords']?>" maxlength="255"><span class="required"> *</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Mata Descrptions:</th>
                                <td class="d_line">
                                    <textarea id="meta_description" name="meta_description" cols="100" rows="5" class="text required" maxlength="1000" type="textarea" class="required"><?php if(isset($data['meta_description'])) echo $data['meta_description']?></textarea><span class="required"> *</span>
                                </td>
                            </tr>
                            <tr>
                                <th>可替换变量：</th>
                                <td>
                                    {product_name} 商品名称<br/>
                                    {category_name} 商品对应的分类名称<br/>
                                    {site_domain} 站点域名<br/>
                                    {price} 商品价格
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="list_save">
                    <input type="button" name="button" class="ui-button" value="确认更新"  onclick="submit_form(0);"/>
                </div>
            </form>
            <!--**seo edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    url_base = '<?php echo url::base(); ?>';
    $(document).ready(function(){
        $("#add_form").validate({
            errorPlacement:function(error, element){
                if(element.attr("name") == "meta_description"){
                    //alert(error);
                    error.appendTo( element.parent());
                }else{
                    error.insertAfter(element)
                }
            }
        });
    });

    $('#category_id').bind('change keyup', function(){
        cur_disstat = $(this).attr('disabled');
        if(!cur_disstat){
            var category_id = $(this).val();
            if(category_id == 0){
                $('#is_children_tr').css('display', 'none');
            }else{
                $('#is_children_tr').css('display', '');
            }
            $('#category_change_tips').html('loading...');
            $(this).attr('disabled',true);
            //window.open(url_base + 'site/seo_manage/get_category_product_seo?category_id=' + category_id);return;
            $.ajax({
                url: url_base + 'site/seo_manage/get_category_product_seo?category_id=' + category_id,
                type:'GET',
                dataType: 'json',
                success: function (retdat, status) {
                    if(retdat['is_contain_child'] == 1){
                        $('#is_children_tr').css('display', '');
                    }else{
                        $('#is_children_tr').css('display', 'none');
                        $("#is_children").attr('checked', '');
                    }
                    if (retdat['status'] == 1 && retdat['code'] == 200) {
                        $('#category_change_tips').empty();
                        $('#category_id').removeAttr('disabled');
                        $("#meta_description").val(retdat['content']['data'][0]['meta_description']);
                        $("#meta_title").val(retdat['content']['data'][0]['meta_title']);
                        $("#meta_keywords").val(retdat['content']['data'][0]['meta_keywords']);
                        if(retdat['content']['data'][0]['is_children'] == 1){
                            $("#is_children").attr('checked', 'true');
                        }else{
                            $("#is_children").attr('checked', '');
                        }
                    } else {
                        $('#category_id').removeAttr('disabled');
                        $('#category_change_tips').css('display', 'none');
                        $("#meta_description").val('');
                        $("#meta_title").val('');
                        $("#domain").val('');
                        $("#meta_keywords").val('');
                        $("#is_children").attr('checked', '');
                    }
                },
                error:function(){
                    /* reset layout */
                    $('#category_id').removeAttr('disabled');
                    $("#category_change_tips").html('request http error, please try again later');
                    window.setTimeout(function(){
                        /* clear tips */
                        $("#category_change_tips").empty();
                    },2000);
                }
            });
        }
    });

</script>