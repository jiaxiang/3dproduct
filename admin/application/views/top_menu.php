 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>导航</title>
<link href="/css/top.css" rel="stylesheet" type="text/css" />
</head>
<body>    
<div class="topnav">
  <div class="sitenav">
    <div class="welcome">您好, <span class="username"><?php if(!empty($manager['name'])){ ?><?php echo $manager['name']; }?></span> 欢迎进入后台管理系统</div>
    <div class="sitelink">  
    	<a href="/manage/manager/change_password" target="mcMainFrame">修改密码</a> | 
    	<a href="/login/logout" target="_parent">安全退出</a>     
        <!-- a target="mcMainFrame" title="点击刷新视图窗口" href="javascript:location.reload()" class="reload">刷新内容</a>
     	<a href="http://<?php echo Mysite::instance()->get('domain');?>" target="_blank">网站前台</a-->
    </div>
  </div>
  <div class="leftnav">
		<ul>
			<li class="navleft"></li>
        <?php foreach($nodes as $row){ ?>
            <eq><li><A id="<?php echo $row['target'];?>" href="javascript:parent.mcMenuFrame.show_menu('<?php echo $row['target'];?>')" 
                        target="mcMenuFrame"><?php echo $row['name'];?></A></li></eq>
        <?php } ?>
            <li class="navright"></li>
		</ul>
    </div>
</div>
<?php
/**
?>
<iframe src="/autorun/auto" width = "100%" frameborder="0" scrolling="no" style="display:none"></iframe>
<?php
**/
?>
</body>
</html>