<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">批量更新产中的默认货品的价格</li>
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
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current(TRUE);?>">
                <div class="division">
                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tbody>
                            <tr>
                                <td colspan="2">
                                    <span class="require-field">*输入站点域名，防止恶意操作或者操作失误.</span>
                                </td>
                            </tr>
                            <tr>
                                <th width="15%">站点： </th>
                                <td><?php echo $site['name'] . '[' . $site['domain'] . ']';?></td>
                            </tr>
                            <tr>
                                <th>站点域名：</th>
                                <td>
                                    <input size="40" name="domain" class="text required">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="list_save">
                     <input type="button" name="button" class="ui-button" value="确认更新"  onclick="submit_form(0);"/>
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

