<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加国家</li>
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
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current();?>">
                <div class="edit_area">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th>英文名：</th>
                                    <td><input size="60" name="name" class="text  required" value=""><span class="required"> *</span></td>
                                </tr>
                                <tr>
                                    <th>两位简码：</th>
                                    <td><input size="10" name="iso_code" class="text required" value=""><span class="required"> *</span>
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

