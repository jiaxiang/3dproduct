<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>上海竞搏</title>
<head>
<link href="/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/jquery-1.3.2.min.js"></script>
</head>
<body>
	<div id="append"></div><div class="container">
	<h3><img src="/img/task.gif" width="16" height="16"> 服务器环境</h3>
	<ul class="memlist fixwidth">
		<li><em><span class="tdbg">操作系统</span>:</em><?php echo php_uname();?></li>
		<li><em><span class="tdbg">WEB服务器</span>:</em><?php echo $_SERVER['SERVER_SOFTWARE'];?></li>
		<li><em><span class="tdbg">PHP版本</span>:</em><?php echo phpversion();?></li>
		<li><em><span class="tdbg">接口类型</span>:</em><?php echo php_sapi_name();?></li>
		
		<li><em><span class="tdbg">MySQL客户端版本</span>:</em><?php echo mysql_get_client_info();?></li>
		
		<li><em><span class="tdbg">服务器时间</span>:</em><?php echo date('Y-m-d H:i:s');?></li>
		<li><em><span class="tdbg">服务器IP</span>:</em><?php echo $_SERVER['SERVER_ADDR'];?></li>
		<li><em><span class="tdbg">服务器端口</span>:</em><?php echo $_SERVER['SERVER_PORT'];?></li>
	</ul>
    </div>
</body>
</html>