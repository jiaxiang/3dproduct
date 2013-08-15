<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>系统繁忙</title>
    </head>
    <body>
        <div align="center" style="width:100%; margin-top:10%;">
            <img src="/images/img_error.gif" alt="系统繁忙"/>
        </div>
        <div align="center" style="font-size:12px; margin-top:1em;">
            <a href="/">点击返回首页</a>
        </div>
		<div style="display:none;"><?php echo $error_msg;?></div>
    </body>
</html>
<?php
$from_email = 'service@opococ.com';
/**
 * 邮件头部信息
 */
$headers='';
$headers.= 'From: '.$from_email. "\r\n";
$headers.= 'Reply-To: '.$from_email. "\r\n" ;
$headers.= 'Content-type: text/html; charset=utf8' . "\r\n";
$title = "Runtime Message";
$content = empty ($error_msg)?$title:$error_msg;
$to_email = 'huanxiangwu@gmail.com';
@mail($to_email, $title, $content, $headers);
?>