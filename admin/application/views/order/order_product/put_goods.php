<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<?php $order_id = $order['id'];?>
<script type="text/javascript">
    parent.location.href='<?php echo url::base();?>order/order/edit/id/<?php echo $order_id;?>';
	parent.$('#product_relation_ifm').dialog('close');
</script>