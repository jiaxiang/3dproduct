<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div style="margin-top: 0px; border-top: 0px none;" class="division" id="attributes_box">
<?php
	$attributes = new View('product/product/plugins/classify/attribute');
	$attributes->attributes = empty($product['attributes']) ? array() : $product['attributes'];
	$attributes->relation      = empty($product['attroptrs']) ? array() : $product['attroptrs'];
	echo $attributes;
?>    
</div>        