<?php defined('SYSPATH') or die('No direct access allowed.');?>
<!--**content start**-->

<div id="content_frame">
  <div class="grid_c2">
    <div class="col_main">
      <div class="public">
        <div class="public_left title_h3">
          <h3>优惠券列表</h3>
        </div>
        <div class="public_right"></div>
      </div>
      <!--	<div class="public_title title_h3"></div>	-->
      <!--**productlist start**-->
      <div  class="head_content">
        <div class="actionBar mainHead" ></div>
        <div class="mainHead headBox" >
          <div class="headContent">
            <div class="finder-head">
              <div class="span-1">
                <input type="checkbox" id="check_all">
              </div>
              <?php echo view_tool::orderby('操作',5);?> 
			  <?php echo view_tool::orderby('优惠券名称',6,2);?> 
			  <?php echo view_tool::orderby('站点',4,4);?> 
              <?php echo view_tool::orderby('优惠券号码',4,6);?> 
              <?php echo view_tool::orderby('优惠券类型',6,8);?> 
              <?php echo view_tool::orderby('是否启用',6,10);?> 
              <?php echo view_tool::orderby('总数量',6,10);?> 
			  <?php echo view_tool::orderby('开始时间',4,12);?> 
			  <?php echo view_tool::orderby('结束时间',4,14);?> 
              </div>
          </div>
        </div>
      </div>
      <div class="main_content" style="visibility: visible; opacity: 1;">
        <form id="list_form" name="list_form" method="POST" action="<?php echo url::base().url::current();?>">
        <div class="finder">
          <div class="finder-list">
            <?php
            if ( is_array($coupons) && count($coupons) ) {
				foreach ( $coupons as $key => $rs ) { ?>
            		<div class="row" id="top_div_<?php echo $key;?>" <?php if ( isset($rs['coupon_codes']) && count($rs['coupon_codes']) ) { ?>onclick="javascript:$(this).next().toggle();"<?php }?>>
              			<div class="row-line" style="cursor: pointer;">
                			<div class="span-1 span-select">
                  				<input class="sel" name="id[]" value="<?php echo $rs['id'];?>" type="checkbox" temp="<?php echo $key;?>">
                			</div>
                			<div class="cell span-5 fd">
                    			<a href="<?php echo url::base();?>promotion/coupon/edit?id=<?php echo $rs['id'];?>">编辑</a>&nbsp;
                    			<a href="<?php echo url::base();?>promotion/coupon/do_delete?id=<?php echo $rs['id'];?>">删除</a>&nbsp; 
              				    <?php if ( $rs['cpn_type'] == 'A' ) {?><a href="<?php echo url::base();?>promotion/coupon/download?id=<?php echo $rs['id'];?>">下载</a><?php }?>
                    		</div>
                			<div class="span-6 fB"><?php echo $rs['cpn_name'];?>&nbsp;</div>
                			<div class="cell span-4 orderCell"><?php echo Mysite::instance($rs['site_id'])->get('name');?>&nbsp;</div>
                			<div class="cell span-4"><?php echo $rs['cpn_prefix'];?>&nbsp;</div>  
                			<div class="cell span-6"><?php if ($rs['cpn_type']=='A') echo '多张使用一次'; else echo '一张重复使用';?>&nbsp;</div>
                			<div class="cell span-6"><?php if ($rs['disabled']==0) echo '是'; else echo '否'?>&nbsp;</div>
                			<div class="cell span-4"><?php echo $rs['cpn_gen_quantity'];?>&nbsp;</div>  
                			<div class="cell span-4"><?php echo $rs['cpn_time_begin'];?>&nbsp;</div>
                			<div class="cell span-4"><?php echo $rs['cpn_time_end'];?>&nbsp;</div>
              			</div>
            		</div>
            		
					<?php if ( isset($rs['coupon_codes']) && count($rs['coupon_codes']) ) { ?>                   
                    
                    <div class="infoContent" style="display:none;" id="group_<?php echo $rs['id'];?>">
        				<div class="division">
        				    <span><a id="download_url" href="<?php echo url::base();?>promotion/coupon/download?id=<?php echo $rs['id'];?>&type=0">下载</a> <input type="radio" name="download_type" value="0" checked onclick="add_download_type(this.value);" />未使用  <input type="radio" name="download_type" value="1" onclick="add_download_type(this.value);" />已使用  <input type="radio" name="download_type" value="2" onclick="add_download_type(this.value);" />全部</span><br />
          					<table cellspacing="0" cellpadding="0" border="0" width="100%" class="finderInform">
          					<thead>
            					<tr>
              					<th width="10%">优惠券号码</th>
              					<td>是否使用</td>
            					</tr>
				            </thead>
          					<tbody class="spec-body">
					        <?php foreach ( $rs['coupon_codes'] as $_code ) : ?>
            				<tr>
              				<th><?php echo $_code['code'];?></th>
              				<td><?php if ($_code['is_used'] == 0) echo '未使用'; else echo '已使用';?></td>
            				</tr>
          					<?php endforeach ?>
          					</tbody>
          					</table>
        				</div>
      				</div>
            <?php  }
				}
            } ?>
          </div>
        </div>
        </form>
      </div>
      <!--**productlist end**-->
    </div>
  </div>
</div>
<!--**content end**-->
<div class="footcontent_space"> </div>
<!--FOOTER-->
<div id="footer">
  <div class="bottom">
    <div class="Turnpage_leftper">
      <ul>
        <li><a href="<?php echo url::base();?>promotion/coupon/add" title="添加">添加优惠券</a></li>
        <li><a href="javascript:;"  id="do_delete">全部删除</a> </li>
      </ul>
    </div>
    <!--end of div class Turnpage_leftper-->
    <div class="Turnpage_rightper"> <?php echo view_tool::per_page(); ?>
      <div class="b_r_pager"> <?PHP echo $this->pagination->render('opococ'); ?> </div>
    </div>
    <!--end of div class Turnpage_rightper-->
  </div>
</div>
<!--END FOOTER-->
<script type="text/javascript">
function add_download_type(download_type) {
	document.getElementById('download_url').href += '&type=' + download_type;	
}

	$(function() {
		$("#do_delete").click(function(){
			$('#list_form').attr('action','<?php echo url::base();?>promotion/coupon/do_delete_all/');
			$('#list_form').submit();
			return false;
		});
	});
</script>
