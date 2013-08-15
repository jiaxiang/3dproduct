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
<script type="text/javascript" src="<?php echo url::base();?>js/jquery-1.4.2.min.js"></script>
<?php isset($addon_js_link_context) && print($addon_js_link_context);?>
  <title><?php isset($title) && print($title);?></title>
<style type="text/css">
body {font:12px/1.5 Tahoma,Helvetica,Arial,'宋体',sans-serif; }
html, legend {color:#404040; background: #fff; }
body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,button,textarea,p,blockquote,th,td {margin:0;padding: 0;}
table {border-collapse: collapse;border-spacing: 0;}
fieldset,img {border: none;}
address,caption,cite,code,dfn,em,strong,th,var {font-style: normal;font-weight: normal;}
li { list-style: none; }
a {color: #36c;text-decoration: none;}
a:hover {color: #f60;text-decoration: underline;}
.new_skip_title{background:#F0F7FC; border:1px solid #9FDAEA;margin-top:5px; padding:10px; font-size:16px; font-weight:bold;}
.new_skip_info_short{background:url(/images/new_message_bg.gif) bottom left repeat-x; border:1px solid #ccc; padding:10px 0 10px 40px; margin-top:20px; }
.new_skip_info_short .skip_suc{background:url(/images/new_skip_suc.gif) no-repeat; padding-left:50px; font-size:14px; line-height:50px; font-weight:bold; }
.new_skip_info_short .skip_erro{background:url(/images/new_skip_erro.gif) no-repeat; padding-left:50px; font-size:14px; line-height:50px; font-weight:bold; }
.new_skip_info_short p{ padding-left:50px; line-height:30px;}
.new_skip_info_short ul{padding-left:50px;}
.new_skip_info_short ul li{line-height:25px; background:url(/images/new_skip_li.gif) 5px no-repeat; padding-left:20px;}
</style>
<?php if(isset($addon_css_content_ontext)){
?>
<style type="text/css">
<?php echo $addon_css_content_ontext;?>
body {font:12px/1.5 Tahoma,Helvetica,Arial,'宋体',sans-serif; }
html, legend {color:#404040; background: #fff; }
body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,button,textarea,p,blockquote,th,td {margin:0;padding: 0;}
table {border-collapse: collapse;border-spacing: 0;}
fieldset,img {border: none;}
address,caption,cite,code,dfn,em,strong,th,var {font-style: normal;font-weight: normal;}
li { list-style: none; }
a {color: #36c;text-decoration: none;}
a:hover {color: #f60;text-decoration: underline;}
.new_skip_title{background:#F0F7FC; border:1px solid #9FDAEA;margin-top:20px; padding:10px; font-size:16px; font-weight:bold;}
.new_skip_info_short{background:url(images/new_message_bg.gif) bottom left repeat-x; border:1px solid #ccc; padding:10px 0 10px 40px; margin-top:20px; }
.new_skip_info_short .skip_suc{background:url(images/new_skip_suc.gif) no-repeat; padding-left:50px; font-size:14px; line-height:50px; font-weight:bold; }
.new_skip_info_short .skip_erro{background:url(images/new_skip_erro.gif) no-repeat; padding-left:50px; font-size:14px; line-height:50px; font-weight:bold; }
.new_skip_info_short p{ padding-left:50px; line-height:30px;}
.new_skip_info_short ul{padding-left:50px;}
.new_skip_info_short ul li{line-height:25px; background:url(images/new_skip_li.gif) 5px no-repeat; padding-left:20px;}
</style>
<?php } ?>
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
