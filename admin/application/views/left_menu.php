 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>菜单</title>
<link href="/css/menu.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base target="mcMainFrame" />
</head>
<script language="javascript">
<!--
var menus = new Array();
 
function refreshMainFrame(url)
{
    parent.mcMainFrame.document.location = url;
}
function show_menu(id){
    var ispush = true;
    for(i=0; i<menus.length; i++){
        sid = menus[i];
        document.getElementById(sid).style.display = 'none';
        parent.mcTopFrame.document.getElementById(sid).className = 'nohover';  
        if(sid == id)ispush = false;
    }    
    document.getElementById(id).style.display = 'block';
    parent.mcTopFrame.document.getElementById(id).className = 'onhover';  
    if(ispush == true)menus.push(id);  
}
-->
</script>
<base target="mcMainFrame">
<body><div style="margin-top:14px; ">
</div>
<div class="menu">
<dl>
    <?php foreach($nodes as $row){ if(empty($first_menu)){$first_menu=$row['target'];} ?>
	<div id="<?php echo $row['target'];?>" style="display:none;">
        <dt><a href="javascript:;" target="_self"><?php echo $row['name'];?></a></dt>
        <dd>
            <ul>
            <?php if(!empty($row['submenu']))foreach($row['submenu'] as $srow){ ?>
            <li><a href='/<?php echo $srow["url"];?>' target="mcMainFrame"><?php echo $srow['name'];?></a></li>
            <?php } ?>
            </ul>
        </dd>
    </div>    
    <?php } ?>        
<dl>
</div>
<script language="javascript">
	show_menu("<?php echo $first_menu;?>");
</script>
</body>
</html>