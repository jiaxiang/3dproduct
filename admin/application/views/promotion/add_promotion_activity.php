<?php defined('SYSPATH') or die('No direct access allowed.');
$session = Session::instance();
$sessionErrorData = $session->get('sessionErrorData');
if(isset($sessionErrorData) && !empty($sessionErrorData)) extract($sessionErrorData,EXTR_OVERWRITE);
//var_dump($sessionErrorData);exit;
?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加促销活动</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
  <div class="grid_c2">
    <div class="col_main">
      <!--**category add start**-->
      <div class="edit_area">
        <form id="add_form" name="add_form" method="POST" action="<?php echo url::base();?>promotion/promotion_activity/do_add" enctype="multipart/form-data">
          <div class="out_box">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
              <tbody>
                <tr>
                  <th>活动名称<span style="color:#F00">*</span>：</th>
                  <td><input type="text" style=""  size="60" id="pmta_name" name="pmta_name" class="text required" value="<?php isset($pmta_name) && print($pmta_name);?>"/></td>
                </tr>
                <tr>
                  <th>头部标题<span style="color:#F00">*</span>：</th>
                  <td><input type="text" style=""  size="60" name="meta_title" id="meta_title" class="text required" value="<?php isset($meta_title) && print($meta_title);?>"/></td>
                </tr>
                <tr>
                  <th>前台描述：</th>
                  <td><textarea name="frontend_description" id="frontend_description" cols="75" rows="5" class="text _x_ipt t400" type="textarea" maxth="255" ><?php isset($frontend_description) && print($frontend_description);?></textarea>
                    <span class="brief-input-state notice_inline">简短的活动介绍，促销页面用。</span></td>
                </tr>
                <tr>
                  <th>Banner 图片：</th>
                  <td><label>
                    <img name="pai" id="pai" width="263" src="<?php isset($banner)&&print($banner);?>" <?php if(!isset($banner) || empty($banner)){?> style="display:none;" <?php }?>height="60" title="暂无图片"/><br />
                    <input type="hidden" name="banner" id="banner" value="<?php isset($banner)&&print($banner);?>" class="text t400  _x_ipt">
                    <input type="button" class="ui-button" name="imagefile" value="选择图片" >
                    <input type="button" class="ui-button" name="deleteImage" value="删除图片" >
                    </label></td>
                </tr>
                <tr>
                  <th>开始时间：</th>
                  <td><input type="text"  name="pmta_time_begin" id="time_begin"  value="<?php (isset($pmta_time_begin)&&print($pmta_time_begin))||print(date('Y-m-d'));?>" size="10" class="text required" style="background-color:#f1f1f1" readonly="true"/></td>
                </tr>
                <tr>
                  <th>结束时间：</th>
                  <td><input type="text"  name="pmta_time_end" id="time_end" value="<?php (isset($pmta_time_end)&&print($pmta_time_end))||print(date('Y-m-d',time()+7*24*3600));?>" size="10" class="text required" style="background-color:#f1f1f1" readonly="true"/></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="btn_eidt">
            <table width="445" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <th width="152"></th>
                <td width="293"><input name="dosubmit" type="submit" class="ui-button" value="添加" /></td>
              </tr>
            </table>
          </div>
        </form>
      </div>
      <!--**category add end**-->
    </div>
  </div>
</div>
<!--**content end**-->
<div id="dialog" style="display:none">
    <iframe frameborder=0 style="border:0px;width:100%;height:98%;" src="" scrolling="auto" id="ifr"></iframe>
</div>
<div id="message" class="ui-dialog-content ui-widget-content" style="height:160px;min-height:100px;width:auto;">
    <p id="message_content"></p>
</div>
<script type="text/javascript">
function showMessage(title, content) {
    var message = $('#message');
    $('#message_content').html(content);
    message.dialog('option', 'title', title);
    message.dialog('open');
}
$(function(){

    $('#message').dialog({
        title: '',
        modal: true,
        autoOpen: false,
        height: 160,
        width: 300,
        buttons: {
            '确定': function(){
                        $('#message').dialog('close');
                    }
        }
    });
	$('#time_begin').datepicker({dateFormat:"yy-mm-dd"});
	$('#time_end').datepicker({dateFormat:"yy-mm-dd"});

	var dialogOpts = {
		    title: "图片上传",
		    modal: true,
		    autoOpen: false,
		    height: 260,
		    width: 500
		};
	$(':button[name=deleteImage]').unbind().bind('click',function(){
	    $('#banner').attr('value','');
	    $('#pai').attr('src',' ');
	    $('#pai').css('display','none');
	});
		//上传图片
		$('#dialog').dialog(dialogOpts);
		$('input[name=imagefile]').bind('click',function(){
		    //option_id = $(this).parent('td').parent('tr').find('.option_id').val();
		    $('#dialog').find('iframe').attr('src', '/promotion/promotion_activity/uploadform');
		    $('#dialog').dialog("open");
		});
});

//上传图片dialog设置
</script>

<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate({
                rules:{
		            pmta_name:{
		            "required":true,
		            "maxlength":255
            },
            meta_title:{
                "required":true,
                "maxlength":255
        },
        frontend_description:{
            "maxlength":65355
    }
              },
        messages:{
            	  pmta_name:{
	            	  "required":"活动名称不能为空",
	                  "maxlength":"活动名称长度不能超过255个"
	                	    },
	              meta_title:{
	                            "required":"活动标题不能为空",
	                            "maxlength":"活动名称长度不能超过255个"
	                    },
	                    frontend_description:{
	                        "maxlength":"活动描述长度不能超过65355个"
	                }
                  }
        });
    });
</script>
