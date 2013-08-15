<?php defined('SYSPATH') or die('No direct access allowed.');
$session = Session::instance();
$sessionErrorData = $session->get('sessionErrorData');
if(isset($sessionErrorData) && !empty($sessionErrorData)) extract($sessionErrorData,EXTR_OVERWRITE);
?>
<table id="relatedGoods" style="border:1px solid #C5DBEC">
    <tr>
        <td><input type="button" class="ui-button" name="addGoods" id="addGoods" value="添加" /> </td>
        <?php if(isset($good_field)){?>
        <?php foreach($good_field as $field){?>
        <td><?php echo $field;?></td>
        <?php }}?>
    </tr>
<?php if(isset($gift_related_ids)){?>
        <?php foreach ( $all_ids as $all_id ) : ?>
        <?php if(in_array($all_id['id'],$gift_related_ids)){?>
        <tr>
            <td><a href="deleted" name="deletedgoods">X</a></td>
            <?php if(isset($good_field)){?>
            <?php foreach($good_field as $key=>$field){?>
            <td><?php echo $all_id[$key];?></td>
            <?php }}?>
            <input type="hidden" name="gift_related_ids[]" value="<?php echo $all_id['id'];?>"/>
        </tr>
        <?php }?>
        <?php endforeach ?>
<?php }?>
</table>
