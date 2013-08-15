<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
      <div class="newgrid_tab fixfloat">
        <ul>
          <li class="on">特性转换</li>
        </ul>
        <span class="fright">
	        <?php if(site::id()>0):?>
	                    当前站点：<a href="http://<?php echo site::domain();?>" target="_blank" title="点击访问"><?php echo site::domain();?></a> [ <a href="<?php echo url::base();?>manage/site/out">返回</a> ]
            <?php endif;?>
		</span> </div>
    </div>
</div>
<div class="outbox">
    <div class="new_order_con fixfloat ">
    <form id="fm_transport" action="post" action="<?php echo url::base(); ?>product/classify/transport_post">
      <table cellspacing="0" class="table_overflow">
        <col width="150">
        <col />
          <tr>
            <td class="a_right a_title">选择商品类型：</td>
            <td>
            	<select id="classify_id" name="classify_id">
	                <?php foreach ($classifies as $classify) : ?>
	                <option value="<?php echo $classify['id']; ?>"><?php echo html::specialchars($classify['name']); ?></option>
	                <?php endforeach; ?>
              	</select>
           	</td>
          </tr>
          <tr>
            <td class="a_right a_title">关联特性：</td>
            <td>
		      <table cellspacing="0" class="table_overflow" id="features_box"></table>
            </td>
          </tr>
          <tr>
            <td class="a_right a_title"></td>
            <td>
            	<input id="transport" type="button" class="ui-button" value="  确定  ">
            </td>
          </tr>
      </table>
      </form>
    </div>
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

	var arguments = [];
	var features  = [];
	var argument_setting = {};
	
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

		$('#classify_id').change(function(){
			var classify_id = $(this).val();
			if (classify_id > 0) {
				ajax_block.open();
				$.ajax({
					url: url_base + 'product/classify/transport_relation?classify_id=' + classify_id,
					type: 'GET',
					dataType: 'json',
					success: function(retdat, status) {
						ajax_block.close();
						var b = $('#features_box').empty();
						var b_message = function(message) {
							b.html('<tr><td style="color:#990000">' + message + '</td></tr>');
						};
						if (retdat['code'] == 200 && retdat['status'] == 1) {
							argments = retdat['arguments'];
							features = retdat['features'];
							if (retdat['arguments'].length == 0) {
								b_message('该类型尚未设置关联参数，请首先设置参数关联！');
							} else {
								if (typeof retdat['features'].length == 'undefined') {
									var s = $('<select></select>');
									s.append('<option value="">----</option>');
									for (var i = 0; i < argments.length; i ++) {
										s.append($('<option></option>').attr('value', argments[i]['name']).html(argments[i]['name_manage']));
									}
									s.change(function() {
										var v = $(this).val();
										var c = $(this).next().empty();
										var a = null;
										for (var i = 0; i < argments.length; i ++) {
											if (v == argments[i]['name']) {
												a = argments[i];
												break;
											}
										}
										c.append('<option value="">----</option>');
										if (a != null) {
											for (var i = 0; i < a['items'].length; i ++) {
												c.append($('<option></option>').attr('value', a['items'][i]['name']).html(a['items'][i]['name_manage']));
											}
										}
									});
									
									var a = $('<select></select>');
									a.append('<option value="">----</option>');
									a.css('margin-left', '10px');
									
									b.append('<tr><td style="background-color:#ededed;"><b>特性名称</b></td><td style="background-color:#ededed;"><b>转换参数</b></td></tr>');
							        
									for (var i in features) {
										var r = $('<tr></tr>');
										r.append($('<td></td>').html(features[i]['name_manage']));
										r.append($('<td></td>').append(s.clone(true).attr('name', 'argument_group[' + features[i]['id'] + ']')).append(a.clone(true).attr('name', 'argument[' + features[i]['id'] + ']')));
										r.appendTo(b);
									}
								} else {
									b_message('该类型下未关联任何商品特性！');
								}
							}
						} else {
							showMessage('请求错误', '<font color="#990000">' + retdat['msg'] + '</font>');
						}
					},
					error: function() {
						ajax_block.close();
						showMessage('请求错误', '<font color="#990000">请稍后重新尝试！</font>');
					}
				});
			}
		}).change();
		
		$('#transport').click(function(){
			var fm = $('#fm_transport');
			
			var isset = false;
			for (var k in features) {
				var fid   = features[k]['id'];
				var gname = $('select[name="argument_group[' + fid + ']"]').val();
				var aname = $('select[name="argument[' + fid + ']"]').val();
				if (gname != '' && aname != '') {
					isset = true;
					var k = gname + aname;
					if (typeof argument_setting[k] == 'undefined') {
						argument_setting[k] = true;
					} else {
						argument_setting = {};
						showMessage('操作失败', '<font color="#990000">参数设置不可重复！</font>');
						return false;
					}
				}
			}
			argument_setting = {};
			
			if (isset == false) {
				showMessage('操作失败', '<font color="#990000">请设置转换结果参数！</font>');
				return false;
			}
			
			ajax_block.open();
			$.ajax({
				url: url_base + 'product/classify/transport_post',
				type: 'POST',
				dataType: 'json',
				data: fm.serialize(),
				timeout: 1000 * 3600,
				success: function(retdat, status) {
					if (retdat['code'] == '200' && retdat['status'] == '1') {
						ajax_block.close();
						$('#classify_id').change();
					} else {
						ajax_block.close();
						showMessage('请求错误', '<font color="#990000">' + retdat['msg'] + '</font>');
					}
				},
				error: function() {
					ajax_block.close();
					showMessage('请求错误', '<font color="#990000">请稍后重新尝试！</font>');
				}
			});
		});
	});
</script>