<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>图片管理器</title>
<link href="/css/kc/main.css" rel="stylesheet" type="text/css" />
<link href="/images/kc/themes/<?php echo $theme ?>/style.css" rel="stylesheet" type="text/css" />
<script src="/js/kc/jquery.js" type="text/javascript"></script>
<script src="/js/kc/jquery.rightClick.js" type="text/javascript"></script>
<script src="/js/kc/jquery.drag.js" type="text/javascript"></script>
<script src="/js/kc/helper.js" type="text/javascript"></script>
<script src="/js/kc/main.js" type="text/javascript"></script>
<script src="/js/tiny_mce/tiny_mce_popup.js" type="text/javascript"></script>
<script src="/images/kc/themes/<?php echo $theme ?>/init.js" type="text/javascript"></script>
<script type="text/javascript">
browser.support.chromeFrame = false;
browser.support.zip = false;
browser.lang = "<?php echo kc_text::js_value(Kohana::config('kc.lang')) ?>";
browser.type = "<?php echo kc_text::js_value(Kohana::config('kc.type')) ?>";
browser.theme = "<?php echo kc_text::js_value(Kohana::config('kc.theme')) ?>";
browser.readonly = <?php echo kc_text::js_value(Kohana::config('kc.readonly')) ? 'true' : 'false' ?>;
browser.dir = "/<?php echo kc_text::js_value(Kohana::config('kc.type')) ?>";
browser.opener.name = "tinymce";
browser.opener.TinyMCE = true;
browser.siteId = "<?php echo $site_id ?>";
_.kuki.domain = document.domain;
_.kuki.path = "<?php echo kc_text::js_value(Kohana::config('kc.cookiePath')) ?>";
_.kuki.prefix = "<?php echo kc_text::js_value(Kohana::config('kc.cookiePrefix')) ?>";
$(document).ready(function() {
    browser.resize();
    browser.init();
    $('#all').css('visibility', 'visible');
});
$(window).resize(browser.resize);
</script>
</head>
<body>
<script type="text/javascript">
$('body').noContext();
</script>
<div id="resizer"></div>
<div id="shadow"></div>
<div id="dialog"></div>
<div id="clipboard"></div>
<div id="img_total"></div>
<div id="all">
<div id="left">
    <div id="folders"></div>
</div>
<div id="right">
    <div id="toolbar">
        <div>
        <a href="kcact:upload"><?php echo Kohana::lang('o_kc.upload') ?></a>
        <a href="kcact:refresh"><?php echo Kohana::lang('o_kc.refresh') ?></a>
        <a href="kcact:settings"><?php echo Kohana::lang('o_kc.settings') ?></a>
        <a href="kcact:maximize"><?php echo Kohana::lang('o_kc.maximize') ?></a>
        <div id="loading"></div>
        </div>
    </div>
    <div id="settings">

    <div>
    <fieldset>
    <legend><?php echo Kohana::lang('o_kc.view') ?>:</legend>
        <table summary="view" id="view"><tr>
        <th><input id="viewThumbs" type="radio" name="view" value="thumbs" /></th>
        <td><label for="viewThumbs">&nbsp;<?php echo Kohana::lang('o_kc.thumbnails') ?></label> &nbsp;</td>
        <th><input id="viewList" type="radio" name="view" value="list" /></th>
        <td><label for="viewList">&nbsp;<?php echo Kohana::lang('o_kc.list') ?></label></td>
        </tr></table>
    </fieldset>
    </div>

    <div>
    <fieldset>
    <legend><?php echo Kohana::lang('o_kc.show') ?>:</legend>
        <table summary="show" id="show"><tr>
        <th><input id="showName" type="checkbox" name="name" /></th>
        <td><label for="showName">&nbsp;<?php echo Kohana::lang('o_kc.name') ?></label> &nbsp;</td>
        <th><input id="showSize" type="checkbox" name="size" /></th>
        <td><label for="showSize">&nbsp;<?php echo Kohana::lang('o_kc.size') ?></label> &nbsp;</td>
        <th><input id="showTime" type="checkbox" name="time" /></th>
        <td><label for="showTime">&nbsp;<?php echo Kohana::lang('o_kc.date') ?></label></td>
        </tr></table>
    </fieldset>
    </div>

    <div>
    <fieldset>
    <legend><?php echo Kohana::lang('o_kc.order_by') ?>:</legend>
        <table summary="order" id="order"><tr>
        <th><input id="sortName" type="radio" name="sort" value="name" /></th>
        <td><label for="sortName">&nbsp;<?php echo Kohana::lang('o_kc.name') ?></label> &nbsp;</td>
        <th><input id="sortType" type="radio" name="sort" value="type" /></th>
        <td><label for="sortType">&nbsp;<?php echo Kohana::lang('o_kc.type') ?></label> &nbsp;</td>
        <th><input id="sortSize" type="radio" name="sort" value="size" /></th>
        <td><label for="sortSize">&nbsp;<?php echo Kohana::lang('o_kc.size') ?></label> &nbsp;</td>
        <th><input id="sortTime" type="radio" name="sort" value="date" /></th>
        <td><label for="sortTime">&nbsp;<?php echo Kohana::lang('o_kc.date') ?></label> &nbsp;</td>
        </tr></table>
    </fieldset>
    </div>

    <div>
    <fieldset>
    <legend><?php echo Kohana::lang('o_kc.order_method') ?>:</legend>
        <table summary="order" id="order_method"><tr>
        <th><input id="sortOrder" type="checkbox" name="desc" /></th>
        <td><label for="sortOrder">&nbsp;<?php echo Kohana::lang('o_kc.descending') ?></label></td>
        </tr></table>
    </fieldset>
    </div>

    </div>
    <div id="files">
        <div id="content"></div>
    </div>
</div>
<div id="status"><span id="fileinfo">&nbsp;</span></div>
</div>
</body>
</html>
