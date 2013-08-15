<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<script type="text/javascript" src="<?php echo url::base(); ?>js/SWFUpload/swfupload.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var uploading = false;
	var uploader = new SWFUpload({
		flash_url: '<?php echo url::base(); ?>js/SWFUpload/Flash/swfupload.swf',
		upload_url: '<?php echo url::base(); ?>product/import/import?session_id=<?php echo urlencode(Session::instance()->id()); ?>',
		file_size_limit: '500 MB',
		file_types: '*.tsv;*.csv;*.zip',
		file_types_description: 'TSV文件;CSV文件;ZIP压缩文件',
		file_upload_limit: 100,
		custom_settings: {
			progressTarget: "fsUploadProgress",
			cancelButtonId: "btnCancel"
		},
		debug: false,

		button_image_url: '<?php echo url::base(); ?>images/uppdtcsv.png',
		button_width: "120",
		button_height: "29",
		button_placeholder_id: "spanButtonPlaceHolder",
		button_text: '<span class="ui-button" style="cursor:pointer;">选择上传文件</span>',
		button_text_style: ".theFont { font-size: 16; }",
		button_text_left_padding: 25,
		button_text_top_padding: 3,
		button_cursor: SWFUpload.CURSOR.HAND, 

		file_dialog_complete_handler: function(num_selected, num_queued) {
			if (uploading == true) {
				showMessage('操作失败', '<font color="#990000">文件上传尚未完成，不可再次上传文件！</font>');
				return false;
			}
			if (num_selected > 1)
			{
				showMessage('操作失败', '<font color="#990000">每次最多仅可选取一个文件！</font>');
				return false;
			}
			this.startUpload();
		},
		upload_start_handler: function() {
			uploading  = true;
			ajax_block.open();
		},
		upload_success_handler: function(file, ser_data) {
			ajax_block.close();
			try{
				var retdat = eval('(' + ser_data + ')');
				if (retdat['code'] == 200 && retdat['status'] == 1){
					showMessage('上传成功', '商品导入成功！');
					$('#error_messages').empty();
					$('#up_pdt_csv').get(0).reset();
				} else {
					$('#error_messages').html(retdat['msg']);
                    //w = window.open('', '_blank');
                    //w.document.write(retdat['msg']);
				}
			} catch (ex) {
				$('#error_messages').empty();
				showMessage('上传失败', '<font color="#990000">文件上传失败，请稍后重新尝试！</font>');
			}
		},
		upload_error_handler: function(file, errcode, message) {
			$('#error_messages').empty();
			showMessage('上传失败', '<font color="#990000">文件上传失败，请稍后重新尝试！</font>');
		},
		upload_complete_handler: function() {
			ajax_block.close();
			uploading = false;
		},
		upload_progress_handler: function(file, loaded, total) {
			var message = '';
			var container = $('#error_messages');
			
			var number_format = function(number, decimals) {
				number = String(number);
				if (number.indexOf('.') > -1) {
					number = number.slice(0, number.indexOf('.') + 1 + decimals);
				}
				return Number(number);
			}
			
			if (total > 1024 * 1024) {
				message += '总体积：' + number_format(total / 1024 / 1024, 2) + ' MB；';
			} else {
				message += '总体积：' + number_format(total / 1024, 2) + ' KB；';
			}

			if (loaded > 1024 * 1024) {
				message += '已上传体积：' + number_format(loaded / 1024 / 1024, 2) + ' MB；';
			} else {
				message += '已上传体积：' + number_format(loaded / 1024, 2) + ' KB；';
			}

			var progress = number_format(loaded / total * 100, 2);
			if (progress == 100) {
				message = '文件已成功上传，正在导入商品，请稍后！';
			} else {
				message += '上传进度：' + progress + '%。';
			}
			
			container.html(message);
		}
	});
});
</script>
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
      <div class="newgrid_tab fixfloat">
        <ul>
          <li class="on">商品上传</li>
        </ul>
      </div>
    </div>
</div>
<div class="outbox">
    <div class="new_order_con fixfloat ">
      <h2>第一步：下载商品CSV文件</h2>
      <table cellspacing="0" class="table_overflow">
        <col width="150">
        <col />
          <tr>
            <td class="a_right a_title">请选择商品类型：</td>
            <td>
            	<select id="classify_id" name="classify_id">
	                <?php echo $classifies_html; ?>
              	</select>
            </td>
          </tr>
          <tr>
            <td class="a_right a_title"></td>
            <td>
            	<input id="download" type="button" class="ui-button" value="  确定  ">
            </td>
          </tr>
      </table>
    </div>
    
    <div class="new_order_con fixfloat ">
      <h2>第二步：填写CSV文件</h2>
      <table cellspacing="0" class="table_overflow">
        <tfoot>
          <tr>
            <td class="a_left">打开CSV文件，在里面对应写入上传商品的内容。</td>
          </tr>
        </tfoot>
      </table>
    </div>
    
    <div class="new_order_con fixfloat ">
      <h2>第三步：上传填写好的CSV文件</h2>
      <form id="up_pdt_csv" action="<?php echo url::base(); ?>product/import/import" target="get_pdt_csv" method="post" enctype=multipart/form-data>
      <table cellspacing="0" class="table_overflow">
        <col width="150">
        <col />
          <tr>
            <td class="a_right a_title">上传CSV文件：</td>
            <td id="fsUploadProgress"><input type="button" id="spanButtonPlaceHolder" class="ui-button" value="  上传  "></td><!-- <input id="pdt_csv" name="pdt_csv" type="file"/> -->
          </tr>
          <!-- 
          <tr>
            <td class="a_right a_title"></td>
            <td><input id="upload" type="button" class="ui-button" value="  确定  "></td>
          </tr>
           -->
          <tr>
          	<td id="error_messages" colspan="2" style="color:#990000;"></td>
          </tr>
      </table>
      </form>
    </div>
</div>
<iframe id="get_pdt_csv" name="get_pdt_csv" src="" style="display:none;"></iframe>
<div id="message" class="ui-dialog-content ui-widget-content" style="height:160px;min-height:100px;width:auto;">
    <p id="message_content"></p>
</div>
<script type="text/javascript">
var url_base = '<?php echo url::base(); ?>';
var trees    = {};

function showMessage(title, content) {
    var message = $('#message');
    $('#message_content').html(content);
    message.dialog('option', 'title', title);
    message.dialog('open');
}

$(document).ready(function(){
	
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
	
	$('#site_id').change(function(){
		var site_id = $(this).val();
		if (typeof trees[site_id] == 'undefined') {
			$('#classify_id').empty();
			$.ajax({
				url: url_base + 'product/import/classifies?site_id=' + site_id,
				type: 'GET',
				dataType: 'json',
				success: function(retdat, status) {
					if (retdat['code'] == 200 && retdat['status'] == 1) {
						trees[site_id] = retdat['content'];
						$('#classify_id').html(retdat['content']);
					} else {
						showMessage('请求失败', '<font color="#990000">' + retdat['msg'] + '</font>');
					}
				},
				error: function() {
					showMessage('请求失败', '<font color="#990000">请稍后重新尝试！</font>');
				}
			});
		} else {
			$('#category_id').html(trees[site_id]);
		}
	});
	
	$('#download').click(function(){
		var classify_id = $('#classify_id').val();
		if (classify_id != null) {
			location.href = url_base + 'product/import/export?classify_id=' + classify_id;
		}
	});

	$('#upload').click(function(){
		var csv = $('#pdt_csv').val().replace(/\s/g, '');
		if (csv == '' || csv.slice(csv.lastIndexOf('.') + 1).toUpperCase() != 'CSV') {
			showMessage('上传失败', '<font color="#990000">请选择所要上传的商品 CSV 文件！</font>');
			return false;
		}
		$('#error_messages').empty();
		$('#up_pdt_csv').submit();
	});
});
</script>