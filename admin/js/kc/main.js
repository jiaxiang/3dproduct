// Object.js
var browser = {
    opener: {},
    support: {},
    files: [],
    clipboard: [],
    labels: {
    	'select' : '选择原图',
    	'select_thumbnail' : '选择缩略图',
    	'view' : '查看原图',
    	'download' : '下载',
    	'rename' : '重命名',
    	'delete' : '删除',
    	'refresh' : '刷新',
    	'files'  : '个文件',
    	'ok' : '确定',
    	'cancel' : '取消',
    	'image_not_found' : '图片已经被删除，请刷新目录',
    	
    	'selectedFiles' : '选中的文件',
    	'the_selected_files_are_not_removable' : '选中的文件无法删除',
    	'delete_all_files_confirm' : '确定删除所有选中的文件吗？',
    	'unknown_error' : '未知错误',
    	'loading_image' : '图片加载中...',
    	'delete_file_confirm' : '你确定要删除这个文件吗？',
    	'loading_folders' : '目录加载中...',
    	'loading_files' : '文件加载中...',
    	
    	'new_subfolder' : '新建目录',
    	'delete_folder_confirm' : '确定要删除这个目录和其中的内容吗？',
    	'uploading_file' : '文件上传中',
    	'cannot_write_to_upload_folder' : '文件无法写入目录',
    	
    	'new_file_name' : '新文件名',
    	'file_name_empty_err' : '请输入新文件名',
    	'file_name_slash_err' : '文件名中存在非法字符',
    	'file_name_dot_err' : '文件名不能以\'.\'开头',
    	
    	'new_folder_name' : '目录名',
    	'folder_name_empty_err' : '请输入目录名',
    	'folder_name_slash_err' : '目录名中存在非法字符',
    	'folder_name_dot_err' : '目录名不能以\'.\'开头',
		'kcfinder_name' : '图片管理器'
	},
    shows: [],
    orders: []
};

var root_forder = '/att/kc/';
//files.js
browser.initFiles = function() {
    $(document).unbind('keypress');
    $(document).keypress(function(e) {
        if ((e.which == 65) || (e.which == 97))
            browser.selectAll();
    });
    $('#files').unbind();
    $('#files').scroll(function() {
        browser.hideDialog();
    });
    $('.file').unbind();
    $('.file').click(function(e) {
        _.unselect();
        browser.selectFile($(this), e);
    });
    $('.file').rightClick(function(e) {
        _.unselect();
        browser.menuFile($(this), e);
    });
    $('.file').dblclick(function() {
        _.unselect();
        browser.returnFile($(this));
    });
    $('.file').mouseup(function() {
        _.unselect();
    });
    $('.file').mouseout(function() {
        _.unselect();
    });
    $.each(this.shows, function(i, val) {
        var display = (_.kuki.get('show' + val) == 'off')
            ? 'none' : 'block';
        $('#files .file div.' + val).css('display', display);
    });
    this.statusDir();
};

browser.loadFiles = function(files) {
    this.files = [];
    $.each(files, function(i, file) {
        browser.files[i] = {
            name: browser.xmlData(file.getElementsByTagName('name')[0].childNodes),
            fileId: file.getAttribute('fileId'),
            attachId: file.getAttribute('attachId'),
            size: file.getAttribute('size'),
            mtime: file.getAttribute('mtime'),
            date: file.getAttribute('date'),
            readable: file.getAttribute('readable') == 'yes',
            writable: file.getAttribute('writable') == 'yes',
            bigIcon: file.getAttribute('bigIcon') == 'yes',
            smallIcon: file.getAttribute('smallIcon') == 'yes',
            thumb: file.getAttribute('thumb') == 'yes',
            smallThumb: file.getAttribute('smallThumb') == 'yes'
        };
    });
};

browser.showFiles = function(callBack, selected) {
    this.fadeFiles();
    setTimeout(function() {
        var html = '';
        $.each(browser.files, function(i, file) {
            if (_.kuki.get('view') == 'list') {
                if (!i) html += '<table summary="list">';
                var icon = _.getFileExtension(file.name);
                if (file.thumb)
                    icon = ".image";
                else if (!icon.length || !file.smallIcon)
                    icon = ".";
                icon = '/images/kc/themes/' + browser.theme + '/img/files/small/' + icon + '.png';
                html += '<tr class="file">' +
                    '<td class="name" style="background-image:url(' + icon + ')">' + _.htmlData(file.name) + '</td>' +
                    '<td class="time">' + file.date + '</td>' +
                    '<td class="size">' + browser.humanSize(file.size) + '</td>' +
                '</tr>';
                if (i == browser.files.length - 1) html += '</table>';
            } else {
                var icon = root_forder + file.attachId + '_100x100.jpg';
                html += '<div class="file">' +
                    '<div class="thumb" style="background-image:url(\'' + icon + '\')" ></div>' +
                    '<div class="name">' + _.htmlData(file.name) + '</div>' +
                    '<div class="time">' + file.date + '</div>' +
                    '<div class="size">' + browser.humanSize(file.size) + '</div>' +
                '</div>';
            }
        });
        $('#files').html('<div>' + html + '<div>');
        $.each(browser.files, function(i, file) {
            var item = $('#files .file').get(i);
            $(item).data(file);
            if (file.name == selected)
            $(item).addClass('selected');
        });
        $('#files > div').css({opacity:'', filter:''});
        if (callBack) callBack();
        browser.initFiles();
    }, 200);
};

browser.selectFile = function(file, e) {
    if (e.ctrlKey) {
        if (file.hasClass('selected'))
            file.removeClass('selected');
        else
            file.addClass('selected');
        var files = $('.file.selected').get();
        var size = 0;
        if (!files.length)
            this.statusDir();
        else {
            $.each(files, function(i, cfile) {
                size += parseInt($(cfile).data('size'));
            });
            size = this.humanSize(size);
            if (files.length > 1)
                $('#fileinfo').html(files.length + ' ' + this.label("selected_files") + ' (' + size + ')');
            else {
                var data = $(files[0]).data();
                $('#fileinfo').html(this.get_short_name(data.name, 30) + ' (' + this.humanSize(data.size) + ', ' + data.date + ')');
            }
        }
    } else {
        var data = file.data();
        $('.file').removeClass('selected');
        file.addClass('selected');
        $('#fileinfo').html(this.get_short_name(data.name, 30) + ' (' + this.humanSize(data.size) + ', ' + data.date + ')');
    }
};

browser.selectAll = function() {
    var files = $('.file').get();
    if (files.length) {
        var size = 0;
        $.each(files, function(i, file) {
            if (!$(file).hasClass('selected'))
                $(file).addClass('selected');
            size += parseInt($(file).data('size'));
        });
        size = this.humanSize(size);
        $('#fileinfo').html(files.length + ' ' + this.label("selected_files") + ' (' + size + ')');
    }
};

browser.returnFile = function(file) {
    var fileURL = file.substr
      ? file : root_forder + file.data('attachId') + '.jpg';
    fileURL = _.escapeDirs(fileURL);

    if (this.opener.CKEditor) {
        this.opener.CKEditor.object.tools.callFunction(this.opener.CKEditor.funcNum, fileURL, '');
        window.close();
    } else if (this.opener.FCKeditor) {
        window.opener.SetUrl(fileURL) ;
        window.close() ;

    } else if (this.opener.TinyMCE) {
        var win = tinyMCEPopup.getWindowArg('window');
        win.document.getElementById(tinyMCEPopup.getWindowArg('input')).value = fileURL;
        if (win.getImageData) win.getImageData();
        if (typeof(win.ImageDialog) != "undefined") {
            if (win.ImageDialog.getImageData)
                win.ImageDialog.getImageData();
            if (win.ImageDialog.showPreviewImage)
                win.ImageDialog.showPreviewImage(fileURL);
        }
        tinyMCEPopup.close();

    } else if (this.opener.callBack) {
        if (window.opener && window.opener.KCFinder) {
            this.opener.callBack(fileURL);
            window.close();
        }

        if (window.parent && window.parent.KCFinder) {
            var button = $('#toolbar a[href="kcact:maximize"]');
            if (button.hasClass('selected'))
                this.maximize(button);
            this.opener.callBack(fileURL);
        }

    } else if (this.opener.callBackMultiple) {
        if (window.opener && window.opener.KCFinder) {
            this.opener.callBackMultiple([fileURL]);
            window.close();
        }

        if (window.parent && window.parent.KCFinder) {
            var button = $('#toolbar a[href="kcact:maximize"]');
            if (button.hasClass('selected'))
                this.maximize(button);
            this.opener.callBackMultiple([fileURL]);
        }
    }
};

// 返回文件信息给TinyMCE
browser.returnFiles = function(files) {
    if (this.opener.callBackMultiple && files.length) {
        var rfiles = [];
        $.each(files, function(i, file) {
            rfiles[i] = browser.uploadURL + '/' + browser.dir + '/' + $(file).data('name');
            rfiles[i] = _.escapeDirs(rfiles[i]);
        });
        this.opener.callBackMultiple(rfiles);
        if (window.opener) window.close()
    }
};

browser.returnThumbnails = function(files) {
    if (this.opener.callBackMultiple) {
        var rfiles = [];
        var j = 0;
        $.each(files, function(i, file) {
            if ($(file).data('thumb')) {
                rfiles[j] = browser.thumbsURL + '/' + browser.dir + '/' + $(file).data('name');
                rfiles[j] = _.escapeDirs(rfiles[j++]);
            }
        });
        this.opener.callBackMultiple(rfiles);
        if (window.opener) window.close()
    }
};

// 文件操作的右键菜单 
browser.menuFile = function(file, e) {
    var data = file.data();
    var path = this.dir + '/' + data.name;
    var files = $('.file.selected').get();
    var html = '';

    if (file.hasClass('selected') && files.length && (files.length > 1)) {
        var thumb = false;
        var notWritable = 0;
        var cdata;
        $.each(files, function(i, cfile) {
            cdata = $(cfile).data();
            if (cdata.thumb) thumb = true;
            if (!data.writable) notWritable++;
        });
        if (this.opener.callBackMultiple) {
            html += '<a href="kcact:pick">' + this.label("select") + '</a>';
            if (thumb) html +=
                '<a href="kcact:pick_thumb">' + this.label("select_thumbnail") + '</a>';
            html += '<div class="delimiter"></div>';
        }
        if (this.support.zip) html+=
            '<a href="kcact:download">' + this.label("download") + '</a>';

        if (!this.readonly) html +=
            '<a href="kcact:rm"' + ((notWritable == files.length) ? ' class="denied"' : '') + '>' + this.label("delete") + '</a>';

        if (html.length) {
            html = '<div class="menu">' + html + '</div>';
            $('#dialog').html(html);
            this.showMenu(e);
        } else
            return;

        $('.menu a[href="kcact:pick"]').click(function() {
            browser.returnFiles(files);
            browser.hideDialog();
            return false;
        });

        $('.menu a[href="kcact:pick_thumb"]').click(function() {
            browser.returnThumbnails(files);
            browser.hideDialog();
            return false;
        });

        $('.menu a[href="kcact:download"]').click(function() {
            browser.hideDialog();
            var pfiles = [];
            $.each(files, function(i, cfile) {
                pfiles[i] = $(cfile).data('name');
            });
            browser.post(browser.baseGetData('download_selected'), {dir:browser.dir, files:pfiles});
            return false;
        });

        $('.menu a[href="kcact:rm"]').click(function() {
            if ($(this).hasClass('denied')) return false;
            browser.hideDialog();
            var failed = 0;
            var dfiles = [];
            $.each(files, function(i, cfile) {
                var cdata = $(cfile).data();
                if (!cdata.writable)
                    failed++;
                else
                   dfiles[dfiles.length] = cdata.fileId;
            });
            if (failed == files.length) {
                alert(browser.label("The_selected_files_are_not_removable."))
                return false;
            }
            if (failed) {
                if (!confirm(browser.label("{count} selected files are not removable. Do you want to delete the rest?", {count:failed})))
                    return false;
            } else if (!confirm(browser.label("delete_all_files_confirm")))
                return false;

            browser.fadeFiles();
            $.ajax({
                type: 'POST',
                url: browser.baseGetData('rm_cbd'),
                data: {file_ids:dfiles},
                async: false,
                success: function(xml) {
                    browser.errors(xml);
                    var sizeElement = xml.getElementsByTagName('size')[0];
			        var nowSize = sizeElement.getAttribute('now');
			        var maxSize = sizeElement.getAttribute('max');
			        browser.initImgTotal(nowSize, maxSize);
                    browser.refresh();
                },
                error: function(request, error) {
                    $('#files > div').css('opacity', '');
                    $('#files > div').css('filter', '');
                    alert(browser.label("unknown_error"));
                }
            });
            return false;
        });

    } else {
        html += '<div class="menu">';
        $('.file').removeClass('selected');
        file.addClass('selected');
        $('#fileinfo').html(this.get_short_name(data.name, 30) + ' (' + this.humanSize(data.size) + ', ' + data.date + ')');
        if (this.opener.callBack || this.opener.callBackMultiple) {
            html += '<a href="kcact:pick">' + this.label("select") + '</a>';
            if (data.thumb) html +=
                '<a href="kcact:pick_thumb">' + this.label("select_thumbnail") + '</a>';
            html += '<div class="delimiter"></div>';
        }

        if (data.thumb)
            html +='<a href="kcact:view">' + this.label("view") + '</a>';

        html +=
            '<a href="kcact:download">' + this.label("download") + '</a>';

        if (!this.readonly) html +=
            '<a href="kcact:mv"' + (!data.writable ? ' class="denied"' : '') + '>' + this.label("rename") + '</a>' +
            '<a href="kcact:rm"' + (!data.writable ? ' class="denied"' : '') + '>' + this.label("delete") + '</a>';
        html += '</div>';

        $('#dialog').html(html);
        this.showMenu(e);

        $('.menu a[href="kcact:pick"]').click(function() {
            browser.returnFile(file);
            browser.hideDialog();
            return false;
        });

        $('.menu a[href="kcact:pick_thumb"]').click(function() {
            var path = root_forder + data.attachId + '_100x100.jpg'
            browser.returnFile(path);
            browser.hideDialog();
            return false;
        });

        $('.menu a[href="kcact:view"]').click(function() {
        	
            browser.hideDialog();
            $('#loading').html(browser.label("loading_image"));
            $('#loading').css('display', 'inline');
            var img = new Image();
            //img.src = root_forder + file.data('attachId') + '.jpg?' + Math.round(Math.random()*10000);
            var url = _.escapeDirs(root_forder + file.data('attachId') + '.jpg?' + Math.round(Math.random()*10000));
            img.src = url;
            
            img.onload = function() {
            	//alert(url + 'x');
                $('#loading').css('display', 'none');
                $('#dialog').html('<img />');
                $('#dialog img').attr('src', url);
                var o_w = $('#dialog').outerWidth();
                var o_h = $('#dialog').outerHeight();
                var f_w = $(window).width() - 30;
                var f_h = $(window).height() - 30;
                if ((o_w > f_w) || (o_h > f_h)) {
                    if ((f_w / f_h) > (o_w / o_h))
                        f_w = parseInt((o_w * f_h) / o_h);
                    else if ((f_w / f_h) < (o_w / o_h))
                        f_h = parseInt((o_h * f_w) / o_w);
                    $('#dialog img').attr('width', f_w);
                    $('#dialog img').attr('height', f_h);
                }
                $('#dialog').click(function() {
                    browser.hideDialog();
                });
                browser.showDialog();           
		    };
  
            
            
            
          /* $.ajax({
                type: 'GET',
                url: root_forder + file.data('attachId') + '.jpg?' + Math.round(Math.random()*10000),
                async: true,
                success: function(data) {
                	var res = eval('(' + data + ')');
                	if (res.code == 404) {
                		
                		alert('test123');
                		
                		alert(browser.label('image_not_found'));
                		$('#loading').css('display', 'none');
                	} else {
                		var img = new Image();
			            var url = _.escapeDirs(root_forder + file.data('attachId') + '.jpg?' + Math.round(Math.random()*10000));
			            
			            alert('test321');
			            
			            
			            img.src = url;
			            img.onload = function() {
			            	//alert(url + 'x');
			                $('#loading').css('display', 'none');
			                $('#dialog').html('<img />');
			                $('#dialog img').attr('src', url);
			                var o_w = $('#dialog').outerWidth();
			                var o_h = $('#dialog').outerHeight();
			                var f_w = $(window).width() - 30;
			                var f_h = $(window).height() - 30;
			                if ((o_w > f_w) || (o_h > f_h)) {
			                    if ((f_w / f_h) > (o_w / o_h))
			                        f_w = parseInt((o_w * f_h) / o_h);
			                    else if ((f_w / f_h) < (o_w / o_h))
			                        f_h = parseInt((o_h * f_w) / o_w);
			                    $('#dialog img').attr('width', f_w);
			                    $('#dialog img').attr('height', f_h);
			                }
			                $('#dialog').click(function() {
			                    browser.hideDialog();
			                });
			                browser.showDialog();
			            };
                	}
                },
                error: function(request, error) {
                	alert('test111111111');
                }
            });
                
            return false;*/
        });

        $('.menu a[href="kcact:download"]').click(function() {
            var html = '<form id="downloadForm" method="post" action="' + browser.baseGetData('download') + '">' +
                '<input type="hidden" name="dir" />' +
                '<input type="hidden" name="file" />' +
                '<input type="hidden" name="attach_id" />' +
            '</form>';
            $('#dialog').html(html);
            $('#downloadForm input').get(0).value = browser.dir;
            $('#downloadForm input').get(1).value = data.name;
            $('#downloadForm input').get(2).value = data.attachId;
            $('#downloadForm').submit();
            //browser.refresh();
            return false;
        });

        $('.menu a[href="kcact:mv"]').click(function(e) {
            if (!data.writable) return false;
            browser.fileNameDialog(
                e, {dir: browser.dir, file: data.name, file_id:data.fileId, dir_id:browser.dirId},
                'new_name', data.name, browser.baseGetData('rename'), {
                    title: browser.label("new_file_name"),
                    errEmpty: browser.label("file_name_empty_err"),
                    errSlash: browser.label("file_name_slash_err"),
                    errDot: browser.label("file_name_dot_err")
                },
                function() {
                    browser.refresh();
                }
            );
            return false;
        });

        $('.menu a[href="kcact:rm"]').click(function() {
            if (!data.writable) return false;
            browser.hideDialog();
            if (confirm(browser.label(
            	"delete_file_confirm"
            )))
                $.ajax({
                    type: 'POST',
                    url: browser.baseGetData('delete'),
                    data: {file_id:data.fileId},
                    async: false,
                    success: function(xml) {
                        if (browser.errors(xml)) return;
                        var sizeElement = xml.getElementsByTagName('size')[0];
			            var nowSize = sizeElement.getAttribute('now');
			            var maxSize = sizeElement.getAttribute('max');
			            browser.initImgTotal(nowSize, maxSize);
                        browser.refresh();
                    },
                    error: function(request, error) {
                        alert(browser.label("unknown_error"));
                    }
                });
            return false;
        });
    }
};

// folders.js
browser.initFolders = function() {
    $('#folders').scroll(function() {
        browser.hideDialog();
    });
    $('div.folder > a').unbind();
    $('div.folder > a').bind('click', function() {
        browser.hideDialog();
        return false;
    });
    $('div.folder > a > span.brace').unbind();
    $('div.folder > a > span.brace').click(function() {
        if ($(this).hasClass('opened') || $(this).hasClass('closed'))
            browser.expandDir($(this).parent());
    });
    $('div.folder > a > span.folder').unbind();
    $('div.folder > a > span.folder').click(function() {
        browser.changeDir($(this).parent());
    });
    $('div.folder > a > span.folder').rightClick(function(e) {
        browser.menuDir($(this).parent(), e);
    });

    if ($.browser.msie && $.browser.version &&
        (parseInt($.browser.version.substr(0, 1)) < 8)
    ) {
        var fls = $('div.folder').get();
        var body = $('body').get(0);
        var div;
        $.each(fls, function(i, folder) {
            div = document.createElement('div');
            div.style.display = 'inline';
            div.style.margin = div.style.border = div.style.padding = '0';
            div.innerHTML='<table style="border-collapse:collapse;border:0;margin:0;width:0"><tr><td nowrap="nowrap" style="white-space:nowrap;padding:0;border:0">' + $(folder).html() + "</td></tr></table>";
            body.appendChild(div);
            $(folder).css('width', $(div).innerWidth() + 'px');
            body.removeChild(div);
        });
    }
};

browser.setTreeData = function(xml, path) {
    if (!path)
        path = "";
    else if (path.length && (path.substr(path.length - 1, 1) != '/'))
        path += '/';
    var data = {
        name: browser.xmlData(xml.getElementsByTagName('name')[0].childNodes),
        readable: xml.getAttribute('readable') == 'yes',
        writable: xml.getAttribute('writable') == 'yes',
        removable: xml.getAttribute('removable') == 'yes',
        hasDirs: xml.getAttribute('hasDirs') == 'yes',
        current: xml.getAttribute('current') ? true : false,
        dirId: xml.getAttribute('dirId')
    };
    path += data.name;
    var selector = '#folders a[href="kcdir:/' + _.escapeDirs(path) + '"]';
    $(selector).data({
        name: data.name,
        path: path,
        readable: data.readable,
        writable: data.writable,
        removable: data.removable,
        hasDirs: data.hasDirs,
        dirId: data.dirId
    });
    $(selector + ' span.folder').addClass(data.current ? 'current' : 'regular');
    if (xml.getElementsByTagName('dirs').length) {
        $(selector + ' span.brace').addClass('opened');
        var dirs = xml.getElementsByTagName('dirs')[0];
        $.each(dirs.childNodes, function(i, cdir) {
            browser.setTreeData(cdir, path + '/');
        });
    } else if (data.hasDirs)
        $(selector + ' span.brace').addClass('closed');
};

browser.buildTree = function(xml, path) {
    if (!path) path = "";
    var name = this.xmlData(xml.getElementsByTagName('name')[0].childNodes);
    var hasDirs = xml.getAttribute('hasDirs') == 'yes';
    path += name;
    var html = '<div class="folder"><a href="kcdir:/' + _.escapeDirs(path) + '"><span class="brace">&nbsp;</span><span class="folder">' + _.htmlData(name) + '</span></a>';
    if (xml.getElementsByTagName('dirs').length) {
        var dirs = xml.getElementsByTagName('dirs')[0];
        html += '<div class="folders">';
        $.each(dirs.childNodes, function(i, cdir) {
            html += browser.buildTree(cdir, path + '/');
        });
        html += '</div>';
    }
    html += '</div>';
    return html;
};

browser.expandDir = function(dir, callBack) {
    var path = dir.data('path');
    var dirId = dir.data('dirId');
    if (dir.children('.brace').hasClass('opened')) {
        dir.parent().children('.folders').hide(500, function() {
            if (path == browser.dir.substr(0, path.length))
                browser.changeDir(dir);
        });
        dir.children('.brace').removeClass('opened');
        dir.children('.brace').addClass('closed');
        if (callBack) callBack();
    } else {
        if (dir.parent().children('.folders').get(0)) {
            dir.parent().children('.folders').show(500);
            dir.children('.brace').removeClass('closed');
            dir.children('.brace').addClass('opened');
            if (callBack) callBack();
        } else if (!$('#loadingDirs').get(0)) {
            dir.parent().append('<div id="loadingDirs">' + this.label("loading_folders") + '</div>');
            $('#loadingDirs').css('display', 'none');
            $('#loadingDirs').show(200, function() {
                $.ajax({
                    type: 'POST',
                    url: browser.baseGetData('expand'),
                    data: {dir:path,dir_id:dirId},
                    async: false,
                    success: function(xml) {
                        $('#loadingDirs').hide(200, function() {
                            $('#loadingDirs').detach();
                        });
                        if (browser.errors(xml)) return;
                        var dirs = xml.getElementsByTagName('dir');
                        var html = '';
                        var pth, name, hadDirs;
                        $.each(dirs, function(i, cdir) {
                            name = browser.xmlData(cdir.getElementsByTagName('name')[0].childNodes);
                            hasDirs = cdir.getAttribute('hasDirs') == 'yes';
                            pth = path + '/' + name;
                            html += '<div class="folder"><a href="kcdir:/' + _.escapeDirs(pth) + '"><span class="brace">&nbsp;</span><span class="folder">' + _.htmlData(name) + '</span></a></div>';
                        });
                        if (html.length) {
                            dir.parent().append('<div class="folders">' + html + '</div>');
                            var folders = $(dir.parent().children('.folders').first());
                            folders.css('display', 'none');
                            $(folders).show(500);
                            $.each(dirs, function(i, cdir) {
                                browser.setTreeData(cdir, path, true);
                            });
                        }
                        if (dirs.length) {
                            dir.children('.brace').removeClass('closed');
                            dir.children('.brace').addClass('opened');
                        } else {
                            dir.children('.brace').removeClass('opened');
                            dir.children('.brace').removeClass('closed');
                        }

                        browser.initFolders();
                        if (callBack) callBack(xml);
                    },
                    error: function(request, error) {
                        $('#loadingDirs').detach();
                        alert(browser.label("unknown_error."));
                    }
                });
            });
        }
    }
};

// 切换目录
browser.changeDir = function(dir) {
    if (dir.children('span.folder').hasClass('regular')) {
        $('div.folder > a > span.folder').removeClass('current');
        $('div.folder > a > span.folder').removeClass('regular');
        $('div.folder > a > span.folder').addClass('regular');
        dir.children('span.folder').removeClass('regular');
        dir.children('span.folder').addClass('current');
        $('#files').html(browser.label("loading_files"));
        $.ajax({
            type: 'POST',
            url: browser.baseGetData('ch_dir'),
            data: {dir:dir.data('path'),dir_id:dir.data('dirId')},
            async: false,
            success: function(xml) {
                if (browser.errors(xml))
                {
                	$('#files').html('');
                	return ;
                }
                var files = xml.getElementsByTagName('file');
                browser.loadFiles(files);
                browser.orderFiles();
                browser.dir = dir.data('path');
                browser.dirId = dir.data('dirId');
                browser.filesCount = xml.getElementsByTagName('filescount')[0].firstChild.nodeValue;
                browser.filesSize = xml.getElementsByTagName('filessize')[0].firstChild.nodeValue;
                var dirWritable =
                    xml.getElementsByTagName('files')[0].getAttribute('dirWritable');
                browser.dirWritable = (dirWritable == 'yes');
                var title = browser.label("kcfinder_name") + browser.dir;
                //document.title = title;
                //if (browser.opener.TinyMCE)
                  //  tinyMCEPopup.editor.windowManager.setTitle(window, title);
                browser.statusDir();
            },
            error: function(request, error) {
                $('#files').html(browser.label("unknown_error"));
            }
        });
    }
};

browser.statusDir = function() {
    /*for (var i = 0, size = 0; i < this.files.length; i++)
        size += parseInt(this.files[i].size);
    size = this.humanSize(size);*/
    var size = this.humanSize(browser.filesSize);
    $('#fileinfo').html(browser.filesCount + ' ' + this.label("files") + ' (' + size + ')');
};

browser.menuDir = function(dir, e) {           
    var data = dir.data();
    var html = '<div class="menu">';
    html +=
        '<a href="kcact:refresh">' + this.label("refresh") + '</a>';
    if (this.support.zip) html+=
        '<div class="delimiter"></div>' +
        '<a href="kcact:download">' + this.label("download") + '</a>';
    if (!this.readonly) html +=
        '<div class="delimiter"></div>' +
        '<a href="kcact:mkdir"' + (!data.writable ? ' class="denied"' : '') + '>' + this.label("new_subfolder") + '</a>' +
        '<a href="kcact:mvdir"' + (!data.removable ? ' class="denied"' : '') + '>' + this.label("rename") + '</a>' +
        '<a href="kcact:rmdir"' + (!data.removable ? ' class="denied"' : '') + '>' + this.label("delete") + '</a>';
    html += '</div>';

    $('#dialog').html(html);
    this.showMenu(e);
    $('div.folder > a > span.folder').removeClass('context');
    if (dir.children('span.folder').hasClass('regular'))
        dir.children('span.folder').addClass('context');

    if (this.clipboard && this.clipboard.length && data.writable) {

        $('.menu a[href="kcact:cpcbd"]').click(function() {
            browser.hideDialog();
            browser.copyClipboard(data.path);
            return false;
        });

        $('.menu a[href="kcact:mvcbd"]').click(function() {
            browser.hideDialog();
            browser.moveClipboard(data.path);
            return false;
        });
    }

    $('.menu a[href="kcact:refresh"]').click(function() {
        browser.hideDialog();
        browser.refreshDir(dir);
        return false;
    });

    $('.menu a[href="kcact:download"]').click(function() {
        browser.hideDialog();
        browser.post(browser.baseGetData('downloadDir'), {dir:data.path});
        return false;
    });

    $('.menu a[href="kcact:mkdir"]').click(function(e) {
        if (!data.writable) return false;
        browser.hideDialog();
        browser.fileNameDialog(
            e, {dir: data.path, dir_id: data.dirId},
            'new_dir', '', browser.baseGetData('new_dir'), {
                title: browser.label("new_folder_name"),
                errEmpty: browser.label("folder_name_empty_err"),
                errSlash: browser.label("folder_name_slash_err"),
                errDot: browser.label("folder_name_dot_err")
            }, function(xml) {
                browser.refreshDir(dir);
                if (!data.hasDirs) {
                    dir.data('hasDirs', true);
                    dir.children('span.brace').addClass('closed');
                }
            }
        );
        return false;
    });

    $('.menu a[href="kcact:mvdir"]').click(function(e) {
        if (!data.removable) return false;
        browser.hideDialog();
        browser.fileNameDialog(
            e, {dir: data.path, dir_id: data.dirId, old_name: data.name},
            'new_name', data.name, browser.baseGetData('rename_dir'), {
                title: browser.label("new_folder_name"),
                errEmpty: browser.label("folder_name_empty_err"),
                errSlash: browser.label("folder_name_slash_err"),
                errDot: browser.label("folder_name_dot_err")
            }, function(xml) {
                if (!xml.getElementsByTagName('name').length) {
                    alert(browser.label("unknown_error."));
                    return;
                }
                var name = browser.xmlData(xml.getElementsByTagName('name')[0].childNodes);
                dir.children('span.folder').html(_.htmlData(name));
                dir.data('name', name);
                dir.data('path', _.dirname(data.path) + '/' + name);
                if (data.path == browser.dir)
                {
                	browser.dir   = dir.data('path');
                	browser.dirId = dir.data('dirId');
                }
                    
            }
        );
        return false;
    });

    $('.menu a[href="kcact:rmdir"]').click(function() {
        if (!data.removable) return false;
        browser.hideDialog();
        if (confirm(browser.label(
        	"delete_folder_confirm"
        ))) {
            $.ajax({
                type: 'POST',
                url: browser.baseGetData('delete_dir'),
                data: {dir:data.path, dir_id:data.dirId},
                async: false,
                success: function(xml) {
                    if (browser.errors(xml)) return;
                    var sizeElement = xml.getElementsByTagName('size')[0];
			        var nowSize = sizeElement.getAttribute('now');
			        var maxSize = sizeElement.getAttribute('max');
			        browser.initImgTotal(nowSize, maxSize);
                    dir.parent().hide(500, function() {
                        var folders = dir.parent().parent();
                        var pDir = folders.parent().children('a').first();
                        dir.parent().detach();
                        if (!folders.children('div.folder').get(0)) {
                            pDir.children('span.brace').first().removeClass('opened');
                            pDir.children('span.brace').first().removeClass('closed');
                            pDir.parent().children('.folders').detach();
                            pDir.data('hasDirs', false);
                        }
                        if (pDir.data('path') == browser.dir.substr(0, pDir.data('path').length))
                            browser.changeDir(pDir);
                    });
                },
                error: function(request, error) {
                    alert(browser.label("unknown_error."));
                }
            });
        }
        return false;
    });
};

browser.refreshDir = function(dir) {
	//alert('path=' + dir.data('path') + 'dirId=' +dir.data('dirId'));
    var path = dir.data('path');
    if (dir.children('.brace').hasClass('opened') || dir.children('.brace').hasClass('closed')) {
        dir.children('.brace').removeClass('opened');
        dir.children('.brace').addClass('closed');
    }
    dir.parent().children('.folders').first().detach();
    if (path == browser.dir.substr(0, path.length))
        browser.changeDir(dir);
    browser.expandDir(dir);
    return true;
};

//init.js
browser.init = function() {
    if (!this.checkAgent()) return;

    $('body').click(function() {
        browser.hideDialog();
    });
    $('#shadow').click(function() {
        return false;
    });
    $('#dialog').unbind();
    $('#dialog').click(function() {
        return false;
    });
    this.initOpeners();
    this.initSettings();
    this.initContent();
    this.initToolbar();
    this.initResizer();
};

browser.checkAgent = function() {
    if (!$.browser.version ||
        ($.browser.msie && (parseInt($.browser.version) < 7) && !this.support.chromeFrame) ||
        ($.browser.opera && (parseInt($.browser.version) < 10)) ||
        ($.browser.mozilla && (parseFloat($.browser.version.replace(/^(\d+(\.\d+)?)([^\d].*)?$/, "$1")) < 1.8))
    ) {
        var html = '<div style="padding:10px">你的浏览器不支持此编辑器。 请升级你的浏览器或者安装以下任意浏览器： <a href="http://www.mozilla.com/firefox/" target="_blank">Mozilla Firefox</a>, <a href="http://www.apple.com/safari" target="_blank">Apple Safari</a>, <a href="http://www.google.com/chrome" target="_blank">Google Chrome</a>, <a href="http://www.opera.com/browser" target="_blank">Opera</a>。';
        if ($.browser.msie)
            html += ' 你也可以安装<a href="http://www.google.com/chromeframe" target="_blank">Google Chrome Frame ActiveX plugin</a>插件来使Internet Explorer 6支持此编辑器。';
        html += '</div>';
        $('body').html(html);
        return false;
    }
    return true;
};

browser.initOpeners = function() {
    if (this.opener.TinyMCE && (typeof(tinyMCEPopup) == 'undefined'))
        this.opener.TinyMCE = null;

    if (this.opener.TinyMCE)
        this.opener.callBack = true;

    if ((!this.opener.name || (this.opener.name == 'fckeditor')) &&
        window.opener && window.opener.SetUrl
    ) {
        this.opener.FCKeditor = true;
        this.opener.callBack = true;
    }

    if (this.opener.CKEditor) {
        if (window.parent && window.parent.CKEDITOR)
            this.opener.CKEditor.object = window.parent.CKEDITOR;
        else if (window.opener && window.opener.CKEDITOR) {
            this.opener.CKEditor.object = window.opener.CKEDITOR;
            this.opener.callBack = true;
        } else
            this.opener.CKEditor = null;
    }

    if (!this.opener.CKFinder && !this.opener.FCKEditor && !this.TinyMCE) {
        if ((window.opener && window.opener.KCFinder && window.opener.KCFinder.callBack) ||
            (window.parent && window.parent.KCFinder && window.parent.KCFinder.callBack)
        )
            this.opener.callBack = window.opener
                ? window.opener.KCFinder.callBack
                : window.parent.KCFinder.callBack;

        if ((
                window.opener &&
                window.opener.KCFinder &&
                window.opener.KCFinder.callBackMultiple
            ) || (
                window.parent &&
                window.parent.KCFinder &&
                window.parent.KCFinder.callBackMultiple
            )
        )
            this.opener.callBackMultiple = window.opener
                ? window.opener.KCFinder.callBackMultiple
                : window.parent.KCFinder.callBackMultiple;
    }
};

// 初始化窗口
browser.initContent = function() {
    $('div#folders').html(this.label("loading_folders"));
    $('div#files').html(this.label("loading_files"));
    $.ajax({
        type: 'GET',
        url: browser.baseGetData('init'),
        async: false,
        success: function(xml) {
            if (browser.errors(xml)) return;
            browser.dirId = xml.getElementsByTagName('tree')[0].getElementsByTagName('dir')[0].getAttribute('dirId');
            var dirWritable = xml.getElementsByTagName('files')[0].getAttribute('dirWritable');
            browser.dirWritable = (dirWritable == 'yes');
            var tree = xml.getElementsByTagName('tree')[0].getElementsByTagName('dir')[0];
            $('#folders').html(browser.buildTree(tree));
            
            var tmpDir = $(this);
            tmpDir.data('dirId', browser.dirId);
            tmpDir.data('path', 'ddd');
            tmpDir.data('hasDirs', 'dd');
            browser.rootDir = tmpDir;
            
            // 容量显示
            var sizeElement = xml.getElementsByTagName('size')[0];
            var nowSize = sizeElement.getAttribute('now');
            var maxSize = sizeElement.getAttribute('max');
            browser.initImgTotal(nowSize, maxSize);
            
            // 状态栏
            browser.filesCount = xml.getElementsByTagName('filescount')[0].firstChild.nodeValue;
            browser.filesSize = xml.getElementsByTagName('filessize')[0].firstChild.nodeValue;
            browser.statusDir();
            
            browser.setTreeData(tree);
            browser.initFolders();
            var files = xml.getElementsByTagName('files')[0].getElementsByTagName('file');
            browser.loadFiles(files);
            browser.orderFiles();
        },
        error: function(request, error) {
            $('div#folders').html(browser.label("unknown_error."));
            $('div#files').html(browser.label("unknown_error."));
        }
    });
};

browser.initResizer = function() {
    var cursor = ($.browser.opera) ? 'move' : 'col-resize';
    $('#resizer').css('cursor', cursor);
    $('#resizer').drag('start', function() {
        $(this).css({opacity:'0.4', filter:'alpha(opacity:40)'});
        $('#all').css('cursor', cursor);
    });
    $('#resizer').drag(function(e) {
        var left = e.pageX - parseInt(_.nopx($(this).css('width')) / 2);
        left = (left >= 0) ? left : 0;
        left = (left + _.nopx($(this).css('width')) < $(window).width())
            ? left : $(window).width() - _.nopx($(this).css('width'));
		$(this).css('left', left);
	});
	var end = function() {
        $(this).css({opacity:'0', filter:'alpha(opacity:0)'});
        $('#all').css('cursor', '');
        var left = _.nopx($(this).css('left')) + _.nopx($(this).css('width'));
        var right = $(window).width() - left;
        $('#left').css('width', left + 'px');
        $('#right').css('width', right + 'px');
        _('files').style.width = $('#right').innerWidth() - _.outerHSpace('#files') + 'px';
        _('resizer').style.left = $('#left').outerWidth() - _.outerRightSpace('#folders', 'm') + 'px';
        _('resizer').style.width = _.outerRightSpace('#folders', 'm') + _.outerLeftSpace('#files', 'm') + 'px';
        browser.fixFilesHeight();
    };
    $('#resizer').drag('end', end);
    $('#resizer').mouseup(end);
};

browser.resize = function() {
    _('left').style.width = '25%';
    _('right').style.width = '75%';
    _('toolbar').style.height = $('#toolbar a').outerHeight() + "px";
    _('shadow').style.width = $(window).width() + 'px';
    _('shadow').style.height = _('resizer').style.height = $(window).height() + 'px';
    _('left').style.height = _('right').style.height =
        $(window).height() - $('#status').outerHeight() + 'px';
    _('folders').style.height =
        $('#left').outerHeight() - _.outerVSpace('#folders') + 'px';
    browser.fixFilesHeight();
    var width = $('#left').outerWidth() + $('#right').outerWidth();
    _('status').style.width = width + 'px';
    while ($('#status').outerWidth() > width)
        _('status').style.width = _.nopx(_('status').style.width) - 1 + 'px';
    while ($('#status').outerWidth() < width)
        _('status').style.width = _.nopx(_('status').style.width) + 1 + 'px';
    if ($.browser.msie && ($.browser.version.substr(0, 1) < 8))
        _('right').style.width = $(window).width() - $('#left').outerWidth() + 'px';
    _('files').style.width = $('#right').innerWidth() - _.outerHSpace('#files') + 'px';
    _('resizer').style.left = $('#left').outerWidth() - _.outerRightSpace('#folders', 'm') + 'px';
    _('resizer').style.width = _.outerRightSpace('#folders', 'm') + _.outerLeftSpace('#files', 'm') + 'px';
};

browser.fixFilesHeight = function() {
    _('files').style.height =
        $('#left').outerHeight() - $('#toolbar').outerHeight() - _.outerVSpace('#files') -
        (($('#settings').css('display') != "none") ? $('#settings').outerHeight() : 0) + 'px';
};

//misc.js
browser.showDialog = function(e) {
    this.shadow();
    if (e) {
        var left = e.pageX - parseInt($('#dialog').outerWidth() / 2);
        var top = e.pageY - parseInt($('#dialog').outerHeight() / 2);
        if (left < 15) left = 15;
        if (top < 15) top = 15;
        if (($('#dialog').outerWidth() + left) > $(window).width() - 30)
            left = $(window).width() - $('#dialog').outerWidth() - 15;
        if (($('#dialog').outerHeight() + top) > $(window).height() - 30)
            top = $(window).height() - $('#dialog').outerHeight() - 15;
        $('#dialog').css('left', left + "px");
        $('#dialog').css('top', top + "px");
    } else {
        $('#dialog').css('left', parseInt(($(window).width() - $('#dialog').outerWidth()) / 2) + 'px');
        $('#dialog').css('top', parseInt(($(window).height() - $('#dialog').outerHeight()) / 2) + 'px');
        $('#dialog').css('display', 'block');
    }

};

browser.hideDialog = function() {
    this.unshadow();
    if ($('#clipboard').hasClass('selected'))
        $('#clipboard').removeClass('selected');
    $('#dialog').css('display', 'none');
    $('div.folder > a > span.folder').removeClass('context');
    $('#dialog').html('');
};

browser.shadow = function() {
    $('#shadow').css('display', 'block');
};

browser.unshadow = function() {
    $('#shadow').css('display', 'none');
};

browser.showMenu = function(e) {
    var left = e.pageX;
    var top = e.pageY;
    if (($('#dialog').outerWidth() + left) > $(window).width())
        left = $(window).width() - $('#dialog').outerWidth();
    if (($('#dialog').outerHeight() + top) > $(window).height())
        top = $(window).height() - $('#dialog').outerHeight();
    $('#dialog').css('left', left + "px");
    $('#dialog').css('top', top + "px");
    $('#dialog').css('display', 'none');
    $('#dialog').fadeIn();
};

browser.fileNameDialog = function(e, post, inputName, inputValue, url, labels, callBack) {
    var html = '<form method="post" action="javascript:;">' +
        '<div class="box"><b>' + this.label(labels.title) + '</b><br />' +
        '<input name="' + inputName + '" value="' + _.htmlValue(inputValue) + '" type="text" autocomplete="off"/><br />' +
        '<div style="text-align:right">' +
        '<input type="submit" value="' + _.htmlValue(this.label('ok')) + '" />' +
        '<input type="button" value="' + _.htmlValue(this.label('cancel')) + '" onclick="browser.hideDialog(); return false" />' +
    '</div></div></form>';
    $('#dialog').html(html);
    $('#dialog').unbind();
    $('#dialog').click(function() {
        return false;
    });
    // 新建目录
    $('#dialog form').submit(function() {
        var name = this.elements[0];
        name.value = $.trim(name.value);
        if (name.value == '') {
            alert(browser.label(labels.errEmpty));
            name.focus();
            return;
        } else if (/[\/\\]/g.test(name.value)) {
            alert(browser.label(labels.errSlash))
            name.focus();
            return;
        } else if (name.value.substr(0, 1) == ".") {
            alert(browser.label(labels.errDot))
            name.focus();
            return;
        }
        eval('post.' + inputName + ' = name.value;');
        $.ajax({
            type: 'POST',
            url: url,
            data: post,
            async: false,
            success: function(xml) {
                if (browser.errors(xml)) return;
                if (callBack) callBack(xml);
                browser.hideDialog();
            },
            error: function(request, error) {
                alert(browser.label("unknown_error."));
            }
        });
        return false;
    });
    browser.showDialog(e);
    $('#dialog').css('display', 'block');
    $('#dialog input[type="submit"]').click(function() {
        return $('#dialog form').submit();
    });
    $('#dialog input[type="text"]').get(0).focus();
    $('#dialog input[type="text"]').get(0).select();
    $('#dialog input[type="text"]').keypress(function(e) {
        if (e.keyCode == 27) browser.hideDialog();
    });
};

browser.orderFiles = function(callBack, selected) {
    var order = _.kuki.get('order');
    var desc = (_.kuki.get('orderDesc') == 'on');

    browser.files = browser.files.sort(function(a, b) {
        var a1, b1, arr;
        if (!order) order = 'name';

        if (order == 'date') {
            a1 = a.mtime;
            b1 = b.mtime;
        } else if (order == 'type') {
            a1 = _.getFileExtension(a.name);
            b1 = _.getFileExtension(b.name);
        } else
            eval('a1 = a.' + order + '.toLowerCase(); b1 = b.' + order + '.toLowerCase();');

        if ((order == 'size') || (order == 'date')) {
            a1 = parseInt(a1 ? a1 : '');
            b1 = parseInt(b1 ? b1 : '');
            if (a1 < b1) return desc ? 1 : -1;
            if (a1 > b1) return desc ? -1 : 1;
        }

        if (a1 == b1) {
            a1 = a.name.toLowerCase();
            b1 = b.name.toLowerCase();
            arr = [a1, b1];
            arr = arr.sort();
            return (arr[0] == a1) ? -1 : 1;
        }

        arr = [a1, b1];
        arr = arr.sort();
        if (arr[0] == a1) return desc ? 1 : -1;
        return desc ? -1 : 1;
    });

    browser.showFiles(callBack, selected);
    browser.initFiles();
};

browser.humanSize = function(size) {
    if (size < 1024) {
        size = size.toString() + ' B';
    } else if (size < 1048576) {
        size /= 1024;
		size = parseInt(size*100)/100;
        size = size.toString() + ' KB';
    } else if (size < 1073741824) {
        size /= 1048576;
		size = parseInt(size*100)/100;
        size = size.toString() + ' MB';
    } else if (size < 1099511627776) {
        size /= 1073741824;
		size = parseInt(size*100)/100;
        size = size.toString() + ' GB';
    } else {
        size /= 1099511627776;
		size = parseInt(size*100)/100;
        size = size.toString() + ' TB';
    }
    return size;
};

browser.baseGetData = function(act) {
    var data = '/kc_browser?type=' + encodeURIComponent(this.type) + '&lng=' + this.lang + '&site_id=' + this.siteId;
    if (act)
        data += "&act=" + act
    return data;
};

browser.label = function(index, data) {
    var label = this.labels[index] ? this.labels[index] : index;
    if (data)
        $.each(data, function(key, val) {
            label = label.replace('{' + key + '}', val);
        });
    return label;
};

browser.errors = function(xml) {
    if (!xml.getElementsByTagName("error").length)
      return false;
    var alertMsg = '';
    $.each(xml.getElementsByTagName('error'), function(i, error) {
        alertMsg += browser.xmlData(error.childNodes) + "\n";
    });
    alertMsg = alertMsg.substr(0, alertMsg.length - 1);
    alert(alertMsg);
    return true;
};

browser.post = function(url, data) {
    var html = '<form id="postForm" method="POST" action="' + url + '">';
    $.each(data, function(key, val) {
        if ($.isArray(val))
            $.each(val, function(i, aval) {
                html += '<input type="hidden" name="' + _.htmlValue(key) + '[]" value="' + _.htmlValue(aval) + '" />';
            });
        else
            html += '<input type="hidden" name="' + _.htmlValue(key) + '" value="' + _.htmlValue(val) + '" />';
    });
    html += '</form>';
    $('#dialog').html(html);
    $('#dialog').css('display', 'block');
    $('#postForm').get(0).submit();
};

browser.fadeFiles = function() {
    $('#files > div').css('opacity', '0.4');
    $('#files > div').css('filter', 'alpha(opacity:40)');
};

browser.xmlData = function(nodes) {
    var data = '';
    $.each(nodes, function(i) {
        data += nodes[i].nodeValue;
    });
    return data;
};

//settings.js
browser.initSettings = function() {

    if (!this.shows.length) {
        var showInputs = $('#show input[type="checkbox"]').toArray();
        $.each(showInputs, function (i, input) {
            browser.shows[i] = input.name;
        });
    }

    var shows = this.shows;

    if (!_.kuki.isSet('showname')) {
        _.kuki.set('showname', 'on');
        $.each(shows, function (i, val) {
            if (val != "name") _.kuki.set('show' + val, 'off');
        });
    }

    $('#show input[type="checkbox"]').click(function() {
        var kuki = $(this).get(0).checked ? 'on' : 'off';
        _.kuki.set('show' + $(this).get(0).name, kuki)
        if ($(this).get(0).checked)
            $('#files .file div.' + $(this).get(0).name).css('display', 'block');
        else
            $('#files .file div.' + $(this).get(0).name).css('display', 'none');
    });

    $.each(shows, function(i, val) {
        var checked = (_.kuki.get('show' + val) == 'on') ? 'checked' : '';
        $('#show input[name="' + val + '"]').attr('checked', checked);
    });

    if (!this.orders.length) {
        var orderInputs = $('#order input[type="radio"]').toArray();
        $.each(orderInputs, function (i, input) {
            browser.orders[i] = input.value;
        });
    }

    var orders = this.orders;

    if (!_.kuki.isSet('order'))
        _.kuki.set('order', 'name');

    if (!_.kuki.isSet('orderDesc')){
        _.kuki.set('orderDesc', 'off');
	}

    $('#order input[value="' + _.kuki.get('order') + '"]').attr('checked', 'checked');
    $('#order_method input[name="desc"]').attr('checked',
        (_.kuki.get('orderDesc') == 'on') ? 'checked' : ''
    );

    $('#order input[type="radio"]').click(function() {
        _.kuki.set('order', $(this).get(0).value);
        browser.orderFiles();
    });

    $('#order_method input[name="desc"]').click(function() {
        _.kuki.set('orderDesc', $(this).get(0).checked ? "on" : "off");
        browser.orderFiles();
    });

    if (!_.kuki.isSet('view'))
        _.kuki.set('view', 'thumbs');

    if (_.kuki.get('view') == "list") {
        $('#show input').attr('checked', 'checked');
        $('#show input').attr('disabled', 'disabled');
    }

    $('#view input[value="' + _.kuki.get('view') + '"]').attr('checked', 'checked');

    $('#view input').click(function() {
        var view = $(this).attr('value');
        if (_.kuki.get('view') != view) {
            _.kuki.set('view', view);
            if (view == 'list') {
                $('#show input').attr('checked', 'checked');
                $('#show input').attr('disabled', 'disabled');
            } else {
                $.each(browser.shows, function(i, val) {
                    if (_.kuki.get('show' + val) != "on")
                        $('#show input[name="' + val + '"]').attr('checked', '');
                });
                $('#show input').attr('disabled', '');
            }
        }
        browser.refresh();
    });
};

//toolbar.js
browser.initToolbar = function() {
    $('#toolbar a').click(function() {
        browser.hideDialog();
    });

    if (!_.kuki.isSet('displaySettings'))
        _.kuki.set('displaySettings', 'off');

    if (_.kuki.get('displaySettings') == 'on') {
        $('#toolbar a[href="kcact:settings"]').addClass('selected');
        $('#settings').css('display', 'block');
        browser.resize();
    }

    $('#toolbar a[href="kcact:settings"]').click(function () {
        if ($('#settings').css('display') == 'none') {
            $(this).addClass('selected');
            _.kuki.set('displaySettings', 'on');
            $('#settings').css('display', 'block');
            browser.fixFilesHeight();
        } else {
            $(this).removeClass('selected');
            _.kuki.set('displaySettings', 'off');
            $('#settings').css('display', 'none');
            browser.fixFilesHeight();
        }
        return false;
    });

    $('#toolbar a[href="kcact:refresh"]').click(function() {
        browser.refresh();
        return false;
    });

    if (window.opener || this.opener.TinyMCE || $('iframe', window.parent.document).get(0))
        $('#toolbar a[href="kcact:maximize"]').click(function() {
            browser.maximize(this);
            return false;
        });
    else
        $('#toolbar a[href="kcact:maximize"]').css('display', 'none');

    this.initUploadButton();
};

browser.initUploadButton = function() {
    var btn = $('#toolbar a[href="kcact:upload"]');
    if (this.readonly) {
        btn.css('display', 'none');
        return;
    }
    var top = btn.get(0).offsetTop;
    var width = btn.outerWidth();
    var height = btn.outerHeight();
    $('#toolbar').prepend('<div id="upload" style="top:' + top + 'px;width:' + width + 'px;height:' + height + 'px">' +
        '<form enctype="multipart/form-data" method="post" target="uploadResponse" action="' + browser.baseGetData('upload') + '">' +
            '<input type="file" name="upload" onchange="browser.uploadFile(this.form)" style="height:' + height + 'px" />' +
            '<input type="hidden" name="dir" value="" />' + '<input type="hidden" name="dir_id" value="" />' + 
        '</form>' +
    '</div>');
    $('#upload input').css('margin-left', "-" + ($('#upload input').outerWidth() - width) + "px");
    $('#upload').mouseover(function() {
        $('#toolbar a[href="kcact:upload"]').addClass('hover');
    });
    $('#upload').mouseout(function() {
        $('#toolbar a[href="kcact:upload"]').removeClass('hover');
    });
};

browser.uploadFile = function(form) {
    if (!this.dirWritable) {
        alert(this.label("cannot_write_to_upload_folder"));
        $('#upload').detach();
        browser.initUploadButton();
        return;
    }
    var type="";
	if(form.elements[0].value!='')
	{
		type = form.elements[0].value.match(/^(.*)(\.)(.{1,8})$/)[3];
	  	type = type.toUpperCase();
	}
  
    if(type!="JPEG" && type!="PNG" && type!="JPG" && type!="GIF") 
    {
      alert("请上传gif, png, jpg, jpeg等类型的图片");
      return;
    } 
    
    form.elements[1].value = browser.dir;
    form.elements[2].value = browser.dirId;
    $('<iframe id="uploadResponse" name="uploadResponse" src="javascript:;"></iframe>').prependTo(document.body);
    $('#loading').html(this.label("uploading_file"));
    $('#loading').css('display', 'inline');
    form.submit();
    $('#uploadResponse').load(function() {
        var response = $(this).contents().find('body').html();
        $('#loading').css('display', 'none');
        var length = response.length;
        if (length && response.substr(0, 1) != '/') 
        {
        	alert(response);
        } else {
        	// 刷新目录
        	response = response.substr(1, length-1);
        	var sizes = response.split('|');
            browser.refresh(response.substr(1, response.length - 1));        
            // 初始化图片容量
            browser.initImgTotal(sizes[0], sizes[1]);
        }
            
        // 移除
        $('#upload').detach();
        setTimeout(function() {
            $('#uploadResponse').detach();
        }, 1);
        browser.initUploadButton();
    });
};

browser.maximize = function(button) {
    if (window.opener) {
        window.moveTo(0, 0);
        width = screen.availWidth;
        height = screen.availHeight;
        if ($.browser.opera)
            height -= 50;
        window.resizeTo(width, height);

    } else if (browser.opener.TinyMCE) {
        var win, ifr, id;

        $('iframe', window.parent.document).each(function() {
            if (/^mce_\d+_ifr$/.test($(this).attr('id'))) {
                id = parseInt($(this).attr('id').replace(/^mce_(\d+)_ifr$/, "$1"));
                win = $('#mce_' + id, window.parent.document);
                ifr = $('#mce_' + id + '_ifr', window.parent.document);
            }
        });

        if ($(button).hasClass('selected')) {
            $(button).removeClass('selected');
            win.css('left', browser.maximizeMCE.left + 'px');
            win.css('top', browser.maximizeMCE.top + 'px');
            win.css('width', browser.maximizeMCE.width + 'px');
            win.css('height', browser.maximizeMCE.height + 'px');
            ifr.css('width', browser.maximizeMCE.width - browser.maximizeMCE.Hspace + 'px');
            ifr.css('height', browser.maximizeMCE.height - browser.maximizeMCE.Vspace + 'px');

        } else {
            $(button).addClass('selected')
            browser.maximizeMCE = {
                width: _.nopx(win.css('width')),
                height: _.nopx(win.css('height')),
                left: win.position().left,
                top: win.position().top,
                Hspace: _.nopx(win.css('width')) - _.nopx(ifr.css('width')),
                Vspace: _.nopx(win.css('height')) - _.nopx(ifr.css('height'))
            };
            var width = $(window.parent).width();
            var height = $(window.parent).height();
            win.css('left', $(window.parent).scrollLeft() + 'px');
            win.css('top', $(window.parent).scrollTop() + 'px');
            win.css('width', width + 'px');
            win.css('height', height + 'px');
            ifr.css('width', width - browser.maximizeMCE.Hspace + 'px');
            ifr.css('height', height - browser.maximizeMCE.Vspace + 'px');
        }

    } else if ($('iframe', window.parent.document).get(0)) {
        var ifrm = $('iframe[name="' + window.name + '"]', window.parent.document);
        var parent = ifrm.parent();
        var width, height;
        if ($(button).hasClass('selected')) {
            $(button).removeClass('selected');
            if (browser.maximizeThread) {
                clearInterval(browser.maximizeThread);
                browser.maximizeThread = null;
            }
            if (browser.maximizeW) browser.maximizeW = null;
            if (browser.maximizeH) browser.maximizeH = null;
            $.each($('*', window.parent.document).get(), function(i, e) {
                e.style.display = browser.maximizeDisplay[i];
            });
            ifrm.css('display', browser.maximizeCSS.display);
            ifrm.css('position', browser.maximizeCSS.position);
            ifrm.css('left', browser.maximizeCSS.left);
            ifrm.css('top', browser.maximizeCSS.top);
            ifrm.css('width', browser.maximizeCSS.width);
            ifrm.css('height', browser.maximizeCSS.height);
            $(window.parent).scrollLeft(browser.maximizeLest);
            $(window.parent).scrollTop(browser.maximizeTop);

        } else {
            $(button).addClass('selected');
            browser.maximizeCSS = {
                display: ifrm.css('display'),
                position: ifrm.css('position'),
                left: ifrm.css('left'),
                top: ifrm.css('top'),
                width: ifrm.outerWidth() + 'px',
                height: ifrm.outerHeight() + 'px'
            };
            browser.maximizeTop = $(window.parent).scrollTop();
            browser.maximizeLeft = $(window.parent).scrollLeft();
            browser.maximizeDisplay = [];
            $.each($('*', window.parent.document).get(), function(i, e) {
                browser.maximizeDisplay[i] = $(e).css('display');
                $(e).css('display', 'none');
            });

            ifrm.css('display', 'block');
            ifrm.parents().css('display', 'block');
            var resize = function() {
                width = $(window.parent).width();
                height = $(window.parent).height();
                if (!browser.maximizeW || (browser.maximizeW != width) ||
                    !browser.maximizeH || (browser.maximizeH != height)
                ) {
                    browser.maximizeW = width;
                    browser.maximizeH = height;
                    ifrm.css('width', width + 'px');
                    ifrm.css('height', height + 'px');
                    browser.resize();
                }
            }
            ifrm.css('position', 'absolute');
            if ((ifrm.offset().left == ifrm.position().left) &&
                (ifrm.offset().top == ifrm.position().top)
            ) {
                ifrm.css('left', '0');
                ifrm.css('top', '0');
            } else {
                ifrm.css('left', - ifrm.offset().left +'px');
                ifrm.css('top', - ifrm.offset().top + 'px');
            }
            resize();
            browser.maximizeThread = setInterval(resize, 250);
        }
    }
};

browser.refresh = function(selected) {
    this.fadeFiles();
    $.ajax({
        type: 'POST',
        url: browser.baseGetData('ch_dir'),
        data: {dir:browser.dir, dir_id:browser.dirId},
        async: false,
        success: function(xml) {
            if (browser.errors(xml)) return;
			var sizeElement = xml.getElementsByTagName('size')[0];
			var nowSize = sizeElement.getAttribute('now');
			var maxSize = sizeElement.getAttribute('max');
			browser.initImgTotal(nowSize, maxSize);
            var files = xml.getElementsByTagName('file');
            var dirWritable =
                xml.getElementsByTagName('files')[0].getAttribute('dirWritable');
            browser.dirWritable = (dirWritable == 'yes');
            browser.loadFiles(files);
            browser.orderFiles(null, selected);
            browser.filesCount = xml.getElementsByTagName('filescount')[0].firstChild.nodeValue;
            browser.filesSize = xml.getElementsByTagName('filessize')[0].firstChild.nodeValue;
            browser.statusDir();
            
            // 右下角的容量显示刷新
            var sizeElement = xml.getElementsByTagName('size')[0];
	        var nowSize = sizeElement.getAttribute('now');
	        var maxSize = sizeElement.getAttribute('max');
			browser.initImgTotal(nowSize, maxSize);
        },
        error: function(request, error) {
            $('#files > div').css({opacity:'', filter:''});
            $('#files').html(browser.label("unknown_error"));
        }
    });
};

browser.initImgTotal = function(now, max) {
    var max = this.humanSize(max);
    var now = this.humanSize(now);
    $('#img_total').html(now + '/' + max);
    var resize = function() {
        $('#img_total').css('left', $(window).width() - $('#img_total').outerWidth()- 15 + 'px');
        $('#img_total').css('top', $(window).height() - $('#img_total').outerHeight() + 'px');
    };
    resize();
    $('#img_total').css('display', 'block');
    $(window).unbind();
    $(window).resize(function() {
        browser.resize();
        resize();
    });
};
browser.get_short_name = function(str,len)
{
	var str_length = 0;
	var str_len = 0;
	str_cut = new String();
	str_len = str.length;
	if(str_len <= len)
	{
		return str;
	}
	for(var i = 0;i<str_len;i++)
	{
		a = str.charAt(i);
		str_length++;
		if(escape(a).length > 4)
		{
			//中文字符的长度经编码之后大于4
			str_length++;
		}
		str_cut = str_cut.concat(a);
		if(str_length>=len-3)
		{
			str_cut = str_cut.concat("...");
			return str_cut;
		}
	}
	//如果给定字符串小于指定长度，则返回源字符串
	return  str;
	
}