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
                <form id="add_form" name="edit_form" method="post" action="<?php echo url::base()?>manage/country_manage/do_edit">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="20%">英文名称：</th>
                                    <td><input size="60" name="name" class="text required" value="<?php echo $data['name'];?>" maxlength="255"><span class="required"> *</span></td>
                                </tr>
                                <tr>
                                    <th>中文名称：</th>
                                    <td><input size="60" name="name_manage" class="text required" value="<?php echo $data['name_manage'];?>" maxlength="255"><span class="required"> *</span></td>
                                </tr>
                                <tr>
                                    <th>两位简码：</th>
                                    <td><input id="iso_code" size="10" name="iso_code" class="text required" value="<?php echo $data['iso_code'];?>" maxlength="2"><span class="required"> *</span></td>
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
                    	<input type="hidden" name="id" value="<?php echo $data['id'];?>">
                        <input type="submit" name="button" class="ui-button" value="保存返回列表"/>
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
        $("#add_form").validate();
        $('#iso_code').live('keyup', function(e){
        	var val = $(this).val();
			var n = '';
			var r = /[A-Za-z]/;
			val = val.toUpperCase();
			for (var i = 0; i < val.length; i++) {
	            var c = val.slice(i, i + 1);
	            if (r.test(c)) {
	                b = false;
	                n += c;
	            }
	        }
			$(this).val(n);
        });
    });
</script>
