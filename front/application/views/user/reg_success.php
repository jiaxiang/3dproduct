<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head></head>
<?php
if ($error == '') {
	echo '认证成功！立即返回<a href="'.url::base().'user/login">登陆</a>！';
}
else {
	echo $error;
}
?>
</html>