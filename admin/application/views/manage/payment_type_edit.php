<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑支付类型</li>
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
                                <th width="25%">名称： </th>
                                <td><input size="60" name="name" class="text t400  required" value="<?php echo $data['name'];?>" maxlength="200"><span class="required"> *</span></td>
                            </tr>
                            <tr>
                                <th>图片地址：</th>
                                <td><input size="60" name="image_url" class="text t400  required" value="<?php echo $data['image_url'];?>" maxlength="200"><span class="required"> *</span></td>
                            </tr>
                            <tr>
                                <th>驱动：</th>
                                <td><input size="60" name="driver" class="text t400  required" value="<?php echo $data['driver'];?>" maxlength="200"><span class="required"> *</span>
                                </td>
                            </tr>
                            <tr>
                                <th>提交地址：</th>
                                <td><input size="60" name="submit_url" class="text t400  required" value="<?php echo $data['submit_url'];?>" maxlength="200"><span class="required"> *</span></td>
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