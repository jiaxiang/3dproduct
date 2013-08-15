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
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base();?>site/country/do_save">
                <div class="edit_area">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                            <tr>
                                    <th width="200px">英文名称：</th>
                                    <td><select id="country_manage_id" name="country_manage_id" class="text required" style="width:150px">
                                    	<option value="">------------请选择------------</option>
                                    <?php foreach ($country_manages as $rs) :?>
                                    	<option value="<?php echo $rs['id'];?>" <?php if($rs['id'] == $country['country_manage_id']) echo "selected"?>><?php echo $rs['name'];?></option>
                                    <?php endforeach;?>
                                    </select><span id="loading_data" class="valierror"></span></td>
                                </tr>
                                <tr>
                                    <th>中文名称：</th>
                                    <td><input size="60" id="name_manage" name="name_manage" class="text required" value="<?php echo $country_manage['name_manage']?>" maxlength="255" readonly="true"><span class="required"> *</span></td>
                                </tr>
                                <tr>
                                    <th>两位简码：</th>
                                    <td><input id="iso_code" size="10" name="iso_code" class="text required" value="<?php echo $country_manage['iso_code'];?>" maxlength="2" readonly="true"><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr id="is_change" style="display:<?php if($is_change == '0') echo 'none';?>">
                                    <th>备注：</th>
                                    <td>系统管理中对应的国家信息已经被改变，直接点击保存按钮，更新与系统管理中的此国家信息一致。<br/>
                                   此国家的信息：<br/>
                                    英文名称：<?php echo $country['name']?><br/>
                                    中文名称：<?php echo $country['name_manage']?><br/>  
                                    两位简码：<?php echo $country['iso_code']?><br/>                     
                                    </td>
                                </tr>
                                <tr id="is_delete" style="display:<?php if($is_delete == '0') echo 'none';?>">
                                    <th>备注：</th>
                                    <td>系统管理中对应的国家信息已经不存在，请重新选择。<br/>
                                   此国家的信息：<br/>
                                    英文名称：<?php echo $country['name']?><br/>
                                    中文名称：<?php echo $country['name_manage']?><br/>  
                                    两位简码：<?php echo $country['iso_code']?><br/>                     
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                	<input type="hidden" id="id" name="id" value="<?php echo $country['id'];?>" />
                    <input type="button" name="button" class="ui-button" value="返回列表" onclick="javascript:window.location.href='<?php echo url::base();?>site/country/'"/>
                    <input type="submit" name="button" class="ui-button" value="保存返回列表"/>
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
    	var validator = $("#add_form").validate({
    	    messages:{              
    		country_manage_id:"请选择一个有效的国家英文名称"
    		}
	    });
    	$('#country_manage_id').bind('change keyup', function(){
            cur_disstat = $(this).attr('disabled');
            if(!cur_disstat){
                var country_manage_id = $(this).val();
                if(country_manage_id != <?php echo $country['country_manage_id'];?>){
                	$('#is_change').css('display', 'none');
                	//$('#is_delete').css('display', 'none');
                }else{
                	<?php if($is_change == '1') :?>
                		$('#is_change').css('display', '');
                	<?php endif;?>
                }                   
                $('#loading_data').html('  loading...');
                $(this).attr('disabled',true);
                $.ajax({
                    url: url_base + 'site/country/get_country_data?country_manage_id=' + country_manage_id,
                    type:'GET',
                    dataType: 'json',
                    success: function (retdat, status) {
                        if (retdat['status'] == 1 && retdat['code'] == 200) {
                            $('#loading_data').empty();
                            $('#country_manage_id').removeAttr('disabled');
                            $("#name_manage").val(retdat['data']['name_manage']);
                            $("#iso_code").val(retdat['data']['iso_code']);
                            $("#name").val(retdat['data']['name']);
                        } else {
                            if(country_manage_id == ''){
                            	$('#loading_data').empty();
                                $('#country_manage_id').removeAttr('disabled');
                                $("#name_manage").val('');
                                $("#iso_code").val('');
                                $("#name").val('');
                            }
                        }
                        $('label.error').hide();
                        $(':input').removeClass('error');
                    },
                    error:function(){
                        $('#country_manage_id').removeAttr('disabled');
                        $("#loading_data").html('request http error, please try again later');
                        window.setTimeout(function(){
                            /* clear tips */
                            $("#loading_data").empty();
                        },2000);
                    }
                });
            }
        });
    });
</script>