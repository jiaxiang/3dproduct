function showMessage(title, content) {
		// 消息提示框
	    var message = $('<div><p id="message_content"></p></div>');
		message.dialog({
    		title: '',
    		modal: true,
    		autoOpen: false,
    		height: 160,
    		width: 300,
    		buttons: {
    		    '确定': function(){
			                message.dialog('close');
    					}
    		}
        });
		message.find('p#message_content').html(content);
		message.dialog('option', 'title', title);
		message.dialog('open');
	}
	function confirm(content, callback) {
		// 确认框
		var message = $('<div><p id="message_content"></p></div>');
		message.dialog({
    		title: '确认框',
    		modal: true,
    		autoOpen: false,
    		height: 160,
    		width: 300,
    		buttons: {
		        '取消': function(){
			        message.dialog('close');
	            },
	            '确定': function(){
                    callback();
                    message.dialog('close');
				}
    		}
        });
		message.find('p#message_content').html(content);
		message.dialog('open');
	}