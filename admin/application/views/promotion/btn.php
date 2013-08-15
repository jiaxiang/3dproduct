<div class="btn_eidt a_center">
  <input name="pmts_id" type="hidden" value="<?php echo $pmts_id ?>" />
  <input name="applied_on" type="hidden" value="category" />
  <input name="pmta_id" type="hidden" value="<?php echo $pmta_id ?>" />       
  <input name="dosubmit" type="button" class="ui-button" value="上一步" onclick="javascript:window.location='<?php echo url::base();?>promotion/promotion/add?id=<?php echo $pmta_id ?>&pmts_id=<?php echo $pmts_id ?>';" />
  <input name="dosubmit" type="submit" class="ui-button" value="保存" />
</div>