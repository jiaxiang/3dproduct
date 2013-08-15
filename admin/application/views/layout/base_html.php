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
<?php isset($addon_css_link_context) && print($addon_css_link_context);?>
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
</head>
<body>
<?php echo (!empty($content))?$content:'';?>
</body>
</html>
