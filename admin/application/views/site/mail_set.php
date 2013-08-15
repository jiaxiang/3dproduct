<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<script type="text/javascript">
    function preview(Obj){
        var id = $(Obj).val();
        $(Obj).next().css('display','').attr('href',"<?php echo url::base();?>site/mail/perview/template/" + id);
    }
</script>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">邮件模板</li>
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
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current();?>">
                <div class="edit_area">
                    <div class="division">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <?php foreach ($site_categories as $key=>$value):?>
                                <tr>
                                    <th width="20%"><?php echo $value['name'];?>：</th>
                                    <td>
                                        <input type="hidden" value="<?php echo $value['id'];?>" name="category_id[]"/>
                                        <select name="mail_id[]" onchange="preview(this);" class="text">
                                            <option value="0"> <?php echo $value['mail_template'];?> </option>
                                            <OPTGROUP LABEL="可用邮件模板" style='color:#03F;'>
                                                    <?php if ($value['mail_template_list']) foreach ($value['mail_template_list'] as $mail_list_key=>$mail_list_value):?>
                                                <option value="<?php echo $mail_list_value['id'];?>">
                                                                <?php echo $mail_list_value['name'];?>
                                                            <?php endforeach;?>
                                                </option>
                                            </OPTGROUP>
                                        </select>
                                            <?php if ($value['mail_template_perview_link'] == "#") {?>
                                        <a href="<?php echo $value['mail_template_perview_link'];?>" target="_blank" class="contentsmall" style="display:none;">预览</a>
                                                <?php } else { ?>
                                        <a href="<?php echo $value['mail_template_perview_link'];?>" target="_blank" class="contentsmall">预览</a>
                                                <?php }?>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                     <input type="button" name="button" class="ui-button" value="保存返回列表"  onclick="submit_form();"/>
                     <input type="hidden" name="submit_target" id="submit_target" value="0" />
                </div>
            </form>
            <!--**productlist edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<div id='example' style="display:none;"></div>
