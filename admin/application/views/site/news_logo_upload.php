<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#logo_upload_form").validate();
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#upload_submit').click(function (){
        $("#upload_content").html("正在上传，请稍候...");
            $.ajax({
        		url: '<?php echo url::base();?>site/news/logo_upload',
                type: 'POST',
                dataType: 'json',
				
                error: function() {
					//alert("sadfsadf");					
                    //window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
                },
                success: function(retdat, status) {
    				ajax_block.close();
    				if (retdat['code'] == 200 && retdat['status'] == 1) {
    					$("#upload_content").html(retdat['content']);
    				} else {
    					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
    				}
    			}
        	});
            $("#upload_content").dialog("open");
            return false;
        });
    });
</script>


<form name="logo_upload_form" id="logo_upload_form" action="/site/news/logo_upload" method="POST" enctype="multipart/form-data">
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="list_save">
         <input name="create_all_goods" id='upload_submit' type="submit" class="ui-button" value=" 上传 "/>
         <input name="cancel" id="cancel_btn" type="button" class="ui-button" value=" 取消 " onclick='window.parent.$("#upload_content").dialog("close");'/>
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