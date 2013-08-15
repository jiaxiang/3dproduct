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
            <?php if (is_array($country_manages) && count($country_manages)) {?>
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base();?>site/country/save">
                <div class="edit_area">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="200px">英文名称：</th>
                                    <td><select id="country_manage_id" name="country_manage_id" class="text required" style="width:150px">
                                    	<option value="">------------请选择------------</option>
                                    <?php foreach ($country_manages as $rs) :?>
                                    	<option value="<?php echo $rs['id'];?>"><?php echo $rs['name'];?></option>
                                    <?php endforeach;?>
                                    </select><span id="loading_data" class="valierror"></span></td>
                                </tr>
                                <tr>
                                    <th>中文名称：</th>
                                    <td><input size="60" id="name_manage" name="name_manage" class="text required" value="" maxlength="255" readonly="true"><span class="required"> *</span></td>
                                </tr>
                                <tr>
                                    <th>两位简码：</th>
                                    <td><input id="iso_code" size="10" name="iso_code" class="text required" value="" maxlength="2" readonly="true"><span class="required"> *</span>
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
            <?php }else {?>
            <?php echo remind::no_rows();?>
            <?php }?>
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
                        } else {
                            if(country_manage_id == ''){
                            	$('#loading_data').empty();
                                $('#country_manage_id').removeAttr('disabled');
                                $("#name_manage").val('');
                                $("#iso_code").val('');
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