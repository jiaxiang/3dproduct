<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加支付类型</li>
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
            <!--** edit start**-->
            <div class="edit_area">
                <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current();?>">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="25%">名称： </th>
                                    <td><input size="60" name="name" class="text t400   required" value="" maxlength="200"><span class="required"> *</span></td>
                                </tr>
                                <tr>
                                    <th>图片地址：</th>
                                    <td><input size="60" name="image_url" class="text t400   required" value="" maxlength="200"><span class="required"> *</span></td>
                                </tr>
                                <tr>
                                    <th>驱动：</th>
                                    <td><input size="60" name="driver" class="text t400   required" value="" maxlength="200"><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>提交地址：</th>
                                    <td><input size="60" name="submit_url" class="text t400   required" value="" maxlength="200"><span class="required"> *</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="btn_eidt">
                        <table width="445" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <th width="152"></th>
                                <td width="293"><input name="submit" type="submit" class="ui-button" value="添加"></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
            <!--** edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<!--FOOTER-->
<div id="footer">
    <div class="bottom">
        <div class="Turnpage_leftper">
        </div>
        <!--end of div class Turnpage_leftper-->
        <div class="Turnpage_rightper">
        </div>
        <!--end of div class Turnpage_rightper-->
    </div>
</div>
<!--END FOOTER-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate();
    });
</script>
