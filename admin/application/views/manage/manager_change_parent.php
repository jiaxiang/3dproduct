<!-- order_message dialog start -->
<form id="message_add_form" name="message_add_form" method="post" action="<?php echo url::base().'manage/manager/change_parent/';?>">
<div class="division">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody>
            <tr>
                <th width="20%">用户:</th>
                <td>
                <select name="parent_id" class="text">
                	<option value="0"> -不做修改- </option>
                    <?php foreach($managers as $key=>$value):?>
                    	<option value="<?php echo $value['id'];?>"> <?php echo $value['name'];?> </option>
                    <?php endforeach;?>
                </select>
                </td>
            </tr>
            <tr>
            	<td colspan="2">*转移用户到指定用户下，请谨慎操作。</td>
            </tr>
        </tbody>
    </table>
</div>
<div class="footContent" style="">
  <div style="margin: 0pt auto; width: 200px; height: 40px;" class="mainFoot">
    <table style="margin: 10px auto; width: auto;">
      <tbody>
        <tr>
          <td>
            <input type="submit" class="ui-button" name="submit" value="保存"/>
            <input type="hidden" value="<?php echo $id_str;?>" name="id_str" />
            </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
</form>
<!-- order_message dialog end -->
<script type="text/javascript">
	/* 按钮风格 */
	$(".ui-button-small,.ui-button").button();
</script>
