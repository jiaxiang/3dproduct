<?php
for ($i = 0; $i < count($data); $i++) {
	echo $data[$i]['order_num'].'<br />';
	$child_orders = $data[$i]['child_orders'];
	for ($j = 0; $j < count($child_orders); $j++) {
		echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$child_orders[$j]['id'].'<br />';
	}
}
?>
<div class="list_page"><?php echo $this->pagination->render('page_html'); ?></div>