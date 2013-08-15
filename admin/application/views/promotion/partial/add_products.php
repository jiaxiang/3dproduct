<?php defined('SYSPATH') or die('No direct access allowed.');
$session = Session::instance();
$sessionErrorData = $session->get('sessionErrorData');
if(isset($sessionErrorData) && !empty($sessionErrorData)) extract($sessionErrorData,EXTR_OVERWRITE);	
?>
<table style="border:1px solid #C5DBEC" id="relatedProducts">
  <tbody id="relatedProducts">
    <tr>
        <td><input type="button" class="ui-button" name="addProduct" id="addProduct" value="添加" /> </td>
        <?php if(isset($product_field)){?>
        <?php foreach($product_field as $field){?>
        <td><?php echo $field;?></td>
        <?php }}?>        
    </tr>
<?php if(isset($related_ids) && isset($all_ids)){?>
<?php foreach ( $all_ids as $all_id ) : ?>
<?php if(in_array($all_id['id'],$related_ids)){?>
<tr>
    <td><a href="deleted" name="deletedproducts">X</a></td>
            <?php if(isset($product_field)){?>
            <?php foreach($product_field as $key=>$field){?>
            <td><?php echo isset($all_id[$key])?$all_id[$key]:'&nbsp;';?></td>
            <?php }}?>
    <input type="hidden" name="related_ids[]" value="<?php echo $all_id['id'];?>"/>
</tr>
<?php }?>
<?php endforeach ?>
<?php }?>
</tbody>
</table>