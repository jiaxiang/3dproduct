<?php defined('SYSPATH') or die('No direct access allowed.');
//$session = Session::instance();
//$sessionErrorData = $session->get('sessionErrorData');
//if(isset($sessionErrorData) && !empty($sessionErrorData)) extract($sessionErrorData,EXTR_OVERWRITE);
?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">选择促销规则</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
  <div class="grid_c2">
    <div class="col_main">
      <!--**category add start**-->
      <div class="edit_area">
        <form id="add_form" name="add_form" method="get" action="<?php echo url::base();?>promotion/promotion/add_next" enctype="multipart/form-data">
          <div class="division">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
              <tbody>
                <?php foreach ( $promotion_schemes as $key=>$scheme ): ?>
                <tr> 
                  <th width="2%"><input type="radio" name="pmts_id" value="<?php echo $scheme['id'];?>" <?php if((isset($pmts_id) && $pmts_id == $scheme['id']) || $key == 0){?>checked="true"<?php }?>/></th><td><?php echo ($key+1).' . '.$scheme['pmts_memo'] ?></td>
                </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
          <div class="btn_eidt a_center">
            <input type="hidden" name="pmta_id" value="<?php echo $pmta_id ?>" />
            <input type="button" class="ui-button" value=" 取 消 " onclick="javascript:window.location='<?php echo url::base();?>promotion/promotion_activity';" />
            <input type="submit" class="ui-button" value="下一步" />
          </div>
        </form>
      </div>
      <!--**category add end**-->
    </div>
  </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate();
    });
</script>
