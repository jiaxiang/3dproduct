tinyMCE.init({
    file_browser_callback: 'openKCFinder',
        //mode : "textareas",
        language : 'zh',
        theme : "advanced",
        plugins : "safari,layer,table,inlinepopups,preview,media,contextmenu,fullscreen,pagebreak,advimage,searchreplace,directionality",
        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect,|,forecolor,backcolor,pagebreak,|,fullscreen,preview,replace,|,ltr,rtl",
        theme_advanced_buttons2 : "tablecontrols,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,image,cleanup,code,|,charmap,iespell,media",
        theme_advanced_buttons3 : '',
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
		//convert_urls: false,
		relative_urls: false,
        init_instance_callback : "initialiseInstance"
    });

function openKCFinder(field_name, url, type, win) {
	// 得到父窗口中的变量
	var global_site_id = win.top.global_site_id;
    tinyMCE.activeEditor.windowManager.open({
        file: '/kc_browser?opener=tinymce&type=' + type + '&site_id=' + global_site_id,
        title: '图片管理器',
        width: 700,
        height: 500,
        resizable: "yes",
        inline: true,
        close_previous: "no",
        popup_css: false
    }, {
        window: win,
        input: field_name
    });
    return false;
}



