<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head></head>
<p>
首页
</p>
<p>
<?php
if ($user) {
	echo '<p>welcome,'.$user['username'].'
		<a href="'.url::base().'user/logout">logout</a>
		<a href="'.url::base().'service/print3d">print</a> </p>';
}
?>
<a href="<?php echo url::base();?>user/register">reg</a>
</p>
<p>
<a href="<?php echo url::base();?>user/login">login</a>
</p>
</html>