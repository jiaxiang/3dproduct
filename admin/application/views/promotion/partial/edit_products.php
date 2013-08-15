<?php defined('SYSPATH') or die('No direct access allowed.');
$session = Session::instance();
$sessionErrorData = $session->get('sessionErrorData');
if(isset($sessionErrorData) && !empty($sessionErrorData)) extract($sessionErrorData,EXTR_OVERWRITE);
?>
<table id="relatedProducts" style="border:1px solid #C5DBEC">
    <tr>
        <td><input type="button" class="ui-button" name="addProduct" id="addProduct" value="添加" /> </td>
        <?php if(isset($product_field)){?>
        <?php foreach($product_field as $field){?>
        <td><?php echo $field;?></td>
        <?php }}?>
    </tr>
    <?php if (!empty($all_ids) AND is_array($all_ids)) : ?>
    <?php foreach ($all_ids as $all_id) : ?>
    <tr>
        <td><a href="deleted" name="deleted<?php echo $thing;?>">X</a><input type="hidden" name="related_ids[]" value="<?php echo $all_id['id'];?>"/></td>
        <?php if(isset($product_field)){?>
        <?php foreach($product_field as $key=>$field){?>
        <td><?php echo $all_id[$key];?></td>
        <?php }}?>
        
    </tr>
    <?php endforeach ?>
    <?php endif; ?>
</table>
