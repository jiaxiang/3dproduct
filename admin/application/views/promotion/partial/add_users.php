<?php defined('SYSPATH') or die('No direct access allowed.');
$session = Session::instance();
$sessionErrorData = $session->get('sessionErrorData');
if(isset($sessionErrorData) && !empty($sessionErrorData)) extract($sessionErrorData,EXTR_OVERWRITE);
isset($related_ids) && $all_ids = Myuser::instance()->lists(array('where' => array('id'=>$$related_idsu)));
?>
<table id="<?php echo $relatedUsers;?>" style="border:1px solid #C5DBEC;">
    <tr>
        <td><input type="button" class="ui-button" name="<?php echo $addUser; ?>" id="<?php echo $addUser; ?>" value="添加" /> </td>
      <?php if(isset($user_field)){?>
                <?php foreach($user_field as $field){?>
                <td><?php echo $field;?></td>
                <?php }}?>
    </tr>
    <?php if(isset($$related_idsu) && isset($all_ids)){?>
    <?php foreach ( $all_ids as $all_id ) : ?>
    <?php if(in_array($all_id['id'],$$related_idsu)){?>
    <tr>
        <td><a href="deleted" name="deletedusers">X</a></td>
                <?php if(isset($user_field)){?>
                <?php foreach($user_field as $key=>$field){?>
                <td><?php echo $all_id[$key];?></td>
                <?php }}?>
        <input type="hidden" name="<?php echo $related_idsu;?>[]" value="<?php echo $all_id['id'];?>"/>
    </tr>
    <?php }?>
    <?php endforeach ?>
    <?php }?>
</table>