<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 默认View
 * @package feedback
 * @author nickfan<nickfan81@gmail.com>
 * @link http://feedback.ketai-cluster.com
 * @version $Id: default_html.php 191 2010-04-14 01:31:56Z fzx $
 */
if(isset($resourceUpdateTimestamp) && !empty($resourceUpdateTimestamp)){
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s' , $resourceUpdateTimestamp) . ' GMT');
    if((isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $resourceUpdateTimestamp) || (isset($_SERVER['HTTP_IF_UNMODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_UNMODIFIED_SINCE']) < $resourceUpdateTimestamp)){
        header('HTTP/1.0 304 Not Modified');
        exit;
    }
}
if(isset($resourceEtag) && !empty($resourceEtag)){
    header('Etag: ' . $resourceEtag);
    if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $resourceEtag == $_SERVER['HTTP_IF_NONE_MATCH']){
        header('HTTP/1.0 304 Not Modified');
        exit;
    }
}

if(isset($resourceCacheTimeInterval)){
    if($resourceCacheTimeInterval==-1){
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
    }else{
        if($resourceCacheTimeInterval>0){
            header('Cache-control: max-age='.$resourceCacheTimeInterval);
        }
        if(isset($resourceExpiresTimestamp) && !empty($resourceExpiresTimestamp)){
            header('Expires: ' . gmdate('D, d M Y H:i:s', $resourceExpiresTimestamp) . ' GMT');
        }else{
            header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$resourceCacheTimeInterval) . ' GMT');
        }
    }
}

header('Content-Type: text/html; charset=UTF-8');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<link rel="stylesheet" href="<?php echo url::base();?>css/reset.source.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo url::base();?>css/grids.source.css" type="text/css" media="screen" />
<link type="text/css" href="<?php echo url::base();?>css/custom-theme/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<?php isset($addon_css_link_context) && print($addon_css_link_context);?>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.blockUI.src.js"></script>
<?php isset($addon_js_link_context) && print($addon_js_link_context);?>
<title><?php isset($title) && print($title);?></title>
<?php if(isset($addon_css_content_ontext)){
?>
<style type="text/css">
<!--
<?php echo $addon_css_content_ontext;?>
-->
</style>
<?php }//end of $addon_css_content_ontext?>
<?php if(isset($addon_js_content_context)){
?>
<script type="text/javascript">
//<![CDATA[
<?php echo $addon_js_content_context;?>
//]]>
</script>
<?php }//end of $addon_js_content_context?>
<script type="text/javascript">
	var url_base = '<?php echo url::base(); ?>';
	var ajax_block = function() {
		var t = {
			open: function() {
				$.blockUI({
					css: {
						width: '34px',
						height: '34px',
						border: '1px solid #000',
						textAlign: 'center',
						backgroundColor: '#fff',
				        cursor: 'wait',
				        left: '49%',
				        top: '49%'
					},
					overlayCSS: { 
						backgroundColor: '#000',
						opacity: 0.1,
						cursor: 'default'
				    },
					message: $('<img border="0" src="' + url_base + 'images/loading.gif">')
				});
			},
			close: function() {
				$.unblockUI();
			}
		};
		return t;
	}();
</script>
</head>
<body style="height:100%">
<!--**error start**-->
<?php echo remind::get();?>
<!--**error end**-->
<?php echo (!empty($content))?$content:'';?>
</body>
</html>
