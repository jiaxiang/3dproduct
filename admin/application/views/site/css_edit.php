<?php defined('SYSPATH') OR die('No direct access allowed.');?>

<!--**content start**-->
<div id="content_frame">
<div class="grid_c2">
<div class="col_main">
<div class="public_crumb">
<p><a href="/">后台首页</a> 》<a href="/site/seo">seo配置</a></p>
</div>
<!--**productlist edit start**-->

<div class="edit_area">

<form id="add_form" name="add_form" method="post" action="<?php echo url::current();?>">
<div class="division">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tbody>

<tr>
<th>Css:</th>
<td>
<textarea name="css" id="css" cols="75" rows="20" class="text _x_ipt t400" type="textarea" maxth="255" ><?php echo $css; ?></textarea>
</td>
</tr>

<tr>
<th>站点:</th>
<td>
<a href="<?php echo site::default_domain($site_id); ?>"><?php echo site::default_domain($site_id); ?></a>
</td>
</tr>

</tbody>
</table>
</div>

<div class="btn_eidt">
<table width="445" border="0" cellpadding="0" cellspacing="0">
<tr>
<th width="152"></th>
<td width="293">
<input name="Input" type="submit" class="ui-button" value="保存">
</td>
</tr>
</table>
</div>
</form>

</div>
<!--**productlist edit end**-->
</div>
</div>
</div>
<!--**content end**-->
<!--**footer start**-->
<div id="footer">
</div>
<!--**footer end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate();
    });
</script>
