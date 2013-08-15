<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑国家</li>
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
                <form id="edit_form" name="edit_form" method="post" action="<?php echo url::base().url::current();?>">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="15%">站点： </th>
                                    <td><input type="hidden" name="site_id" id="site_id" value="<?php echo $data['site_id'];?>" />
                                        <?php echo $site_info['name'];?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>英文名：</th>
                                    <td><?php echo $data['name'];?></td>
                                </tr>
                                <tr>
                                    <th>两位简码：</th>
                                    <td><?php echo $data['iso_code'];?></td>
                                </tr>
                                <tr>
                                    <th>是否可用：</th>
                                    <td>
                                        <input type="radio" name="active" value="1" <?php if($data['active']==1)echo "checked";?>>
                                        可用
                                        <input type="radio" name="active" value="0" <?php if($data['active']==0)echo "checked";?>>
                                        不可用
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="list_save">
                        <input type="submit" class="ui-button" name="button" value="编辑"  />
                    </div>
                </form>
            </div>
            <!--** edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#edit_form").validate();
    });
</script>
