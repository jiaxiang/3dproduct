<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#logo_upload_form").validate();
    });
</script>
<form name="logo_upload_form" id="logo_upload_form" action="/site/config/logo_upload" method="POST" enctype="multipart/form-data">
    <div class="dialog_box">
        <div class="body dialogContent">
            <!-- tips of pdtattrset_set_tips  -->
            <div id="gEditor-sepc-panel">
                <div class="division">
                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tbody>
                            <tr>
                                <th width="20%">选择图片：</th>
                                <td>
                                    <input type="file" name="img_val" size="20">
                                </td>
                            </tr>
                            <tr>
                                <th width="20%">Logo描述：</th>
                                <td>
                                    <input type="text" name="logo_desc" class="text" value="<?php echo (isset($data['logo_desc']) || !empty($data['logo_desc']))?$data['logo_desc']:$data['site_title'];?>" size="30">
                                    <div>默认是站点的标题。</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="list_save">
         <input name="create_all_goods" type="submit" class="ui-button" value=" 上传 "/>
         <input name="cancel" id="cancel_btn" type="button" class="ui-button" value=" 取消 " onclick='$("#upload_content").dialog("close");'/>
    </div>
</form>
<link type="text/css" href="<?php echo url::base();?>css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo url::base();?>js/jquery-ui-1.8.2.custom.min.js"></script>
<script language ="javascript">
	$(document).ready(function(){
		/* 按钮风格 */
		$(".ui-button-small,.ui-button").button();
	});
</script>