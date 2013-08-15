<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑地区</li>
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
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current(true);?>">
                <div class="edit_area">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th>上级地区：</th>
                                    <td><b><?php echo !empty($pdata['local_name'])?$pdata['local_name']:'中国';?></b></td>
                                </tr>
                                <tr>
                                    <th width="25%">地区中文名称：</th>
                                    <td>
                                        <input size="30" name="local_name" class="text required" value="<?php echo isset($data['local_name'])?$data['local_name']:'';?>"><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>地区英文名称：</th>
                                    <td>
                                        <input size="30" name="en_name" class="text" value="<?php echo isset($data['en_name'])?$data['en_name']:'';?>"> 
                                    </td>
                                </tr>
                                <tr>
                                    <th>排序：</th>
                                    <td>
                                        <input size="10" name="position" class="text" value="<?php echo isset($data['position'])?$data['position']:0;?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>状态：</th>
                                    <td>
                                        <input name="disabled" type="radio" value="false" <?php echo (!isset($data['disabled']) || $data['disabled'] == 'false')?"checked":"";?>>
                                        可用
                                        <input name="disabled" type="radio" value="true" <?php echo (isset($data['disabled']) && $data['disabled'] == 'true')?"checked":"";?>>
                                        不可用
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <input type="submit" class="ui-button" value=" 保存 ">
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
        $("#add_form").validate({
        	errorPlacement:function(error, element){
	            if(element.attr("name") == "meno"){
	                //alert(error);
	                error.appendTo( element.parent());
	            }else{
	                error.insertAfter(element)
	            }
        	}
        });
    });
</script>