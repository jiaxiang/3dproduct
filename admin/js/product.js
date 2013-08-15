/**
 * jQuery 扩展（或者array或object的元素数量）
 */
jQuery.extend({
    count: function(v) {
        if (typeof v == 'object') {
            var c = 0;
            for (var k in v) {
                c ++;
            }
            return c;
        } else if (typeof v == 'array') {
            return v.length;
        } else {
            return -1;
        }
    }
});

/**
 * 显示消息提示
 */
function showMessage(title, content, height, width) {
    var message = $('#message');
	message.dialog({
    	title: title,
    	modal: true,
    	autoOpen: false,
    	height: height?height:200,
    	width: width?width:300,
    	buttons: {
    	    '确定': function(){
		                message.dialog('close');
    				}
    	}
    });
    $('#message_content').html(content);
    message.dialog('open');
}

/**
 * tinyMCE 初始化回调函数
 */
function initialiseInstance(editor) {
	$('input[name=commit]').mouseover(function(e) {
		$('#' + editor.editorId).val(editor.getContent());
	});
}

function check_product_id(msg) {
    var product_id = $('#product_id').val();
    if(!product_id || product_id<=0){
        showMessage('操作失败', '<font color="#990000">'+msg+'</font>');
        return false;
    }
    return true;
}

/**
 * 弹出窗口初始化
 */
$(document).ready(function(){
	// 提示消息窗口
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
    
    // 图片上传窗口
    $("#pdt_picupload_ifm").dialog({
        title: "图片上传",
        modal: true,
        autoOpen: false,
        height: 600,
        width: 460
    });
    
    // 图片关联窗口
    $("#pdt_picrelation_ifm").dialog({
        title: "图片",
        modal: true,
        autoOpen: false,
        height: 600,
        width: 600
    });
});
	

/**
 * 商品图片插件
 * 
 * TODO 需重新设计，提供一些回调函数供其他插件调用，如商品规格插件
 */
var pictures = function(){
    var items = [];
    var current = 0;
	var delete_handlers = [];    
    var t = {
    	add_delete_handler: function(handler) {
    		if (typeof handler == 'function') {
    			delete_handlers.push(handler);
    		}
    	},
        show_upload_form: function() {
            if(!check_product_id('请保存当前最新编辑的商品信息，然后才能上传图片。'))return false;
            $('#pdt_picupload_ifm').find('iframe').attr('src', url_base + 'product/product/pic_upform?product_id=' + $('#product_id').val());
            $('#pdt_picupload_ifm').dialog('open');
        },
        load: function(pics) {
            for (var idx = 0; idx < pics.length; idx++) {
                var pic = pics[idx];
                if (pic['is_default'] == 1) {
                    current = idx;
                }
                t.add(pic);
            }
            t.show(current);
        },
        add: function(pic, show) {
            if (typeof show == 'undefined') {
                show = false;
            }
			
            var idx = items.push(pic) - 1;
            var h = '';
            h += '<li id="pdtpic_' + idx + '">';
            h += '<span class="small_pic">';
            h += '<a href="javascript:void(0)"><img style="cursor:pointer;" title="' + pic['title'] + '" src="' + pic['picurl_t'].replace('120x120', '40x40') + '" ></a>';
            h += '</span>';
            h += '<span class="small_btn">';
            h += '<a href="#" class="del"></a>';
            h += '</span>';
            h += '</li>';

            h = $(h);
            h.find('img').bind('click', function(){
                t.show(idx);
            });
            h.find('.picurl').bind('click', function(){
                t.url(idx);
                return false;
            });
            h.find('.del').bind('click', function(){
                t.remove(idx);
                return false;
            });
            $('#pdtpic_list').append(h);

            if (show) {
                t.show(idx);
            }

            return idx;
        },
        show: function(idx) {
            var l = $('#pdtpic_larger');
            if (typeof items[idx] != 'undefined') {
                var pic = items[idx];
                $('.pdtpic-clicked').removeClass('pdtpic-clicked');
                $('#pdtpic_' + idx).find('img').addClass('pdtpic-clicked');
                l.attr('src', pic['picurl_t'].replace('120x120', '300x300'));
                l.attr('title', pic['title']);
                l.parent().attr('href', pic['picurl_o']);
                current = idx;
            } else {
                l.attr('src', url_base + 'att/0.jpg');
                l.attr('title', '');
                l.parent().attr('href', url_base + 'att/0.jpg');
            }
        },
        remove: function(idx, ajax) {
            if (typeof items[idx] != 'undefined') {
                var picture_id = items[idx]['id'];
                if (delete_handlers.length > 0) {
                	for (var i = 0; i < delete_handlers.length; i ++) {
                		if (!handler(delete_handlers[i](picture_id))) {
                			return false;
                		}
                	}
                }
                
                if (typeof ajax == 'undefined') {
                	ajax = true;
                }
                
                if (ajax == true) {
	                if (!confirm('确定要删除吗？')) {
	                    return false;
	                }
	                var url = url_base + 'product/product/pic_delete?product_id=' + $('#product_id').val();
                        url += '&picture_id=' + picture_id;
					ajax_block.open();
	                $.ajax({
	                    url: url ,
	                    type: 'GET',
	                    dataType: 'json',
	                    success: function(retdat, status) {
	                    	ajax_block.close();
	                        if (retdat['status'] == 1 && retdat['code'] == 200) {
	                            delete items[idx];
	                            if (idx == current) {
	                                for (var k = 0; k < items.length; k ++) {
	                                    if (typeof items[k] != 'undefined') {
	                                        current = k;
	                                        break;
	                                    }
	                                }
	                            }
	                            t.show(current);
	                            $('#pdtpic_' + idx).remove();
	                        } else {
	                            showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
	                        }
	                    },
	                    error: function () {
	                        ajax_block.close();
	                        showMessage('请求错误', '<font color="#990000">请稍后重新尝试！</font>');
	                    }
	                });
                } else {
                	delete items[idx];
                    if (idx == current) {
                        for (var k = 0; k < items.length; k ++) {
                            if (typeof items[k] != 'undefined') {
                                current = k;
                                break;
                            }
                        }
                    }
                    t.show(current);
                    $('#pdtpic_' + idx).remove();
                }
            }
        },
        url: function(idx) {

        },
        set_default: function() {
            if (typeof items[current] != 'undefined') {
                if (items[current]['is_default'] == 1) {
                    showMessage('操作成功', '设置商品默认图片成功！');
                } else {
                    var url = url_base + 'product/product/pic_set_default?product_id=' + $('#product_id').val();
                        url +=  '&picture_id=' + items[current]['id'];
					ajax_block.open();
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(retdat, status) {
                        	ajax_block.close();
                            if (retdat['status'] == 1 && retdat['code'] == 200) {
                            	for (var k in items) {
                            		items[k]['is_default'] = 0;
                            	}
                            	items[current]['is_default'] = 1;
                                showMessage('操作成功', '设置商品默认图片成功！');
                            } else {
                                showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
                            }
                        },
                        error: function() {
                            ajax_block.close();
                        	showMessage('操作失败', '<font color="#990000">请稍候重新尝试！</font>');
                        }
                    });
                }
            }
        },
        get_current: function() {
        	return current;
        }
    };
    return t;
}();

// 商品图片事件绑定
$(document).ready(function(){
	$('#pdtpic_upload').click(function() {
        pictures.show_upload_form();
        return false;
    });
    $('#pdtpic_set_default').click(function() {
        pictures.set_default();
        return false;
    });
    $('#pdtpic_larger').parent().click(function() {
        if ($(this).attr('href') == '') {
            return false;
        }
    });
});

/**
 * 商品图片关联对象
 */
var picrelation = function() {
	var picres = {};
	var t = {
		show: function(save_handler, pids) {
			switch (typeof pids) {
				case 'undefined':
					pids = '';
					break;
				case 'string':
					break;
				case 'object':
					pids = pids.join(',');
			}
			
			var url = url_base + 'product/product/picrelation?product_id=' + $('#product_id').val() + '&save_handler=' + save_handler + '&pids=' + pids;
			
			$('#pdt_picrelation_ifm').find('iframe').attr('src', url);
			$('#pdt_picrelation_ifm').dialog('open');
		},
		hide: function() {
			$('#pdt_picrelation_ifm').dialog('close');
		}
	}
	return t;
}();

/**
 * 关联商品插件 JS 对象
 */
var relation = function() {
	var selectors = {
		dialog:    '#pdt_relation_ifm',
		container: '#pdt_relation_container',
		add:       '#pdt_relation_add',
		remove:    '#pdt_relation_remove',
		check:     'input[name="pdt_relation_check"]',
		check_all: '#pdt_relation_check_all'
	};

	var key = function(id) {
		return '_' + id;
	}

	var ancients  = {};
	var relations = {};
	var is_change = false;
	
	
	var t = {
		dialog: function(type, url) {
			if (type) {
				if (typeof url != 'undefined') {
					$(selectors.dialog).find('iframe').attr('src', url);
				}
				$(selectors.dialog).dialog('open');
			} else {
				$(selectors.dialog).dialog('close');
			}
		},
		count: function() {
			var count = 0;
			for (var id in relations) {
				if (typeof relations[id] != 'undefined') {
					count ++;
				}
			}
			return count;
		},
		add: function(r) {
			var id = key(r.id);
            var name_manage = r.name_manage?r.name_manage:'&nbsp;';
			if (typeof relations[id] == 'undefined') {
				var row = $('<tr></tr>');
				var td = $('<td></td>');
				td.append('<input name="pdt_relation_ids[]" type="hidden" value="' + r.id + '"/>');
				td.append('<input name="pdt_relation_check" type="checkbox" value="' + r.id + '"/>');
				row.append(td);
				var td = $('<td></td>');
				td.append('<a target="_blank" href="' + url_base + 'product/product/edit?id=' + r.id + '">' + r.sku + '</a>');
				row.append(td);
				row.append($('<td></td>').html(r.title));
				row.append($('<td></td>').html(name_manage));
				row.append($('<td></td>').append('<img width="12" height="12" border="0" src="' + url_base + 'images/icon/remove.gif" style="cursor: pointer;"/>'));
				$(selectors.container).append(row);
				relations[id] = row;
				is_change = true;
			}
		},
		remove: function(id) {
			if (t.is(id)) {
				var id = key(id);
				relations[id].remove();
				delete relations[id];
				is_change = true;
			}
		},
		is: function(id) {
			return typeof relations[key(id)] != 'undefined';
		},
		load: function(rs) {
			for (var i = 0; i < rs.length; i ++) {
				t.add(rs[i]);
			}
			t.update(true);
		},
		empty: function() {
			for (var id in relations) {
				t.remove(id.slice(1));
			}
		},
		update: function(type) {
			is_change = false;
			if (type) {
				ancients  = relations;
			} else {
				t.empty();
				for (var id in ancients) {
					$(selectors.container).append(ancients[id]);
				}
			}
		},
		is_change: function() {
			return is_change;
		}
	};
	
	$(document).ready(function(){

		// 配置关联商品窗口
		$(selectors.dialog).dialog({
            title:       '相关商品',
            modal:       true,
            autoOpen:    false,
            height:      450,
            width:       '88%',
            beforeclose: function() {
				if (relation.is_change()) {
					if (!confirm('所有修改将会丢失，确定关闭吗？')) {
						return false;
					}
					relation.update(false);
				}
			}
        });

		// 全选关联商品
        $(selectors.check_all).unbind().bind('click', function(){
        	var checks = $(selectors.container).find(selectors.check);
        	if (checks.length > 0) {
	        	var checked = $(this).attr('checked');
				for (var i = 0; i < checks.length; i ++) {
					$(checks[i]).attr('checked', checked);
				}
        	}
	    });

        // 显示添加关联商品窗口
		$(selectors.add).unbind().bind('click', function(){
            if(!check_product_id('请保存当前最新编辑的商品基本信息，然后才能添加关联商品。'))return false;
			t.dialog(true, url_base + 'product/relation/index?product_id=' + $('#product_id').val());
		});

		// 删除关联商品
		$(selectors.remove).unbind().bind('click', function(){
			var checks = $(selectors.container).find(selectors.check);
			if (checks.length > 0) {
				$(selectors.check_all).attr('checked', false);
				var rs = [];
				for (var i = 0; i < checks.length; i ++) {
					var o = $(checks[i]);
					if (o.attr('checked')) {
						rs.push(o.val());
					}
				}
				if (rs.length == 0) {
					showMessage('操作失败', '<font color="#990000">请选择所要删除的关联商品！</font>');
				} else {
					if (!confirm('确定要删除吗？')) {
						return false;
					}
					for (var i = 0; i < rs.length; i ++) {
						t.remove(rs[i]);
					}
				}
			}
		});

		$(selectors.container).unbind().bind('click', function(e){
			if (e.target.nodeName.toUpperCase() == 'IMG') {
				var r = $(e.target).parent().parent();
				var c = r.find(selectors.check);
				if (c.length == 1) {
					if (confirm('确定要删除吗？')) {
						t.remove(c.val());
					} else {
						return false;
					}
				}
			}
		});
	});
	
	return t;
}();

/**
 * 商品扩展分类处理
 */
$(document).ready(function(){
	var checks = $('#pdt_category_additional_box').find('input[name="pdt_category_additional_id[]"]');

	$('#pdt_category_additional_check_all').unbind().bind('click', function(){
		var checked = $(this).attr('checked');
		if (checks.length > 0) {
			for (var i = 0; i < checks.length; i ++) {
				var o = $(checks[i]);
				if (o.attr('disabled') != true) {
					o.attr('checked', checked);
				}
			}
		}
	});
	
	$('#default_category_id').bind('change', function(){
		var category_id = $(this).val();
		checks.attr('disabled', false);
		if (category_id > 0) {
			for (var i = 0; i < checks.length; i ++) {
				var o = $(checks[i]);
				if (o.val() == category_id) {
					o.attr('disabled', true);
				}
			}
		}
	});
});