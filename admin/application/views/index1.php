<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理</title>
<link rel="stylesheet" type="text/css" href="/ext/resources/css/ext-all.css" />
<link rel="stylesheet" type="text/css" href="/ext/style/docs.css" />
<link rel="stylesheet" type="text/css" href="/ext/style/style.css" />
<SCRIPT type=text/javascript>
//iframe包含
if (top.location != location) {
	top.location.href = location.href;
}
//window.moveTo(0,0);
//window.resizeTo(screen.availWidth,screen.availHeight);
</SCRIPT>    
</head>
<body scroll="no" id="docs">
  <div id="loading-mask"></div>
  <div id="loading">
    <div class="loading-indicator"><img src="/ext/images/extanim32.gif" width="32" height="32" style="margin-right:8px;" align="absmiddle"/>Loading...</div>
  </div>

  <div id="header">
	<a href="#" style="float:right;margin-right:10px;display:none;"><img src="/ext/images/extjs.gif" onclick="top.location.href='/login/logout';" alt="注销" title="注销" style="width:83px;height:24px;margin-top:1px;"/></a>
	<div class="api-title" style="float:left;"><h1>后台管理</h1></div>
    <div class="topm">
      <ul style="color:#fff;">
      <li><?php if(!empty($manager['name'])){ ?>您好, <?php echo $manager['name']; }?> [ <a href="<?php echo url::base();?>login/logout" class="yellow">注销</a> ]</li>
      <li class='pointer'>[ <a href="http://<?php echo Mysite::instance()->get('domain');?>" target="_blank">浏览网店</a> ]</li>
      </ul>
    </div>  
  </div>    
    <!-- include everything after the loading indicator -->
    <script type="text/javascript" src="/ext/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="/ext/ext-all.js"></script>
    <script type="text/javascript" src="/ext/src/locale/ext-lang-zh_CN.js"></script>  
     
    <!-- 系统js加载 -->
    <script type="text/javascript" src="/ext/TabCloseMenu.js"></script>
    <script type="text/javascript" src="/ext/docs.js"></script>

</body>
</html>