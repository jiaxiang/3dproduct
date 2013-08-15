<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Classic pagination style
 * 
 * @preview < First  < 1 2 3 >  Last >
 */
?> 
<div class="b_r_pager">
<?php if ($first_page): ?>
	<a title="上一页" class="sysiconBtnNoIcon" href="<?php echo str_replace('{page}', 1, $url) ?>">首页</a>
<?php endif ?>
<?php if ($previous_page): ?>
	<a title="上一页" class="sysiconBtnNoIcon" href="<?php echo str_replace('{page}', $previous_page, $url) ?>">上一页</a>
<?php endif ?>

<?php
for($i = $current_page-3;$i<$current_page+3;$i++):
//for ($i = $begin; $i <= $end; $i++): 
?>
	<?php 
		if($i<=0):
			$i = 0;
			continue;
	?>
	<?php 
		elseif($i > $total_pages):
			break;
	?>
    <?php //elseif ($i == $current_page): ?>
    <!-- <a class="bordercurrent" href="javascript:void(0);"><?php echo $i ?></a> -->
    <?php else: ?>
    <a href="<?php echo str_replace('{page}', $i, $url) ?>" class="<?php if ($i == $current_page) : ?>bordercurrent<?php else : ?>sysiconBtnNoIcon borderup<?php endif; ?>"><?php echo $i ?></a>
    <?php endif ?>
<?php 
	endfor
?>

<?php if ($next_page): ?>
	<a title="下一页" class="sysiconBtnNoIcon" href="<?php echo str_replace('{page}', $next_page, $url) ?>">下一页</a>
<?php endif ?>

<?php if ($last_page): ?>
	<a title="尾页" class="sysiconBtnNoIcon" href="<?php echo str_replace('{page}', $last_page, $url) ?>">尾页</a>
<?php endif ?>
</div>
<div class="totalview">共<?php echo $total_items ?>条</div>
