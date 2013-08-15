<?php defined('SYSPATH') or die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">选择优惠券规则</li>
            </ul>
            <span class="fright">
                <?php if(site::id()>0):?>
                当前站点：<a href="http://<?php echo site::domain();?>" target="_blank" title="点击访问"><?php echo site::domain();?></a> [ <a href="<?php echo url::base();?>manage/site/out">返回</a> ]
                <?php endif;?>
            </span>
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
        <form id="add_form" name="add_form" method="get" action="<?php echo url::base();?>promotion/cpn_promotion/add_next" enctype="multipart/form-data">
          <div class="division">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
              <tbody>
                <?php foreach ( $promotion_schemes['data'] as $scheme ): ?>
                <tr> 
                  <th width="2%"><input type="radio" name="cpns_id" value="<?php echo $scheme['id']?>" class="text t4 _x_ipt required" /></th><td><?php echo $scheme['cpns_memo'] ?></td>
                </tr>
                <?php endforeach ?>                
              </tbody>
            </table>
          </div>
          <div class="btn_eidt">
            <table width="445" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <th width="152"><input type="hidden" name="site_id" value="<?php echo $site_id ?>" /></th>
                <th width="152"><input type="hidden" name="coupon_id" value="<?php echo $coupon_id ?>" /></th>
                <td width="293">
                  <input type="submit" class="ui-button" value="下一步" />
                  <input type="button" class="ui-button" value="取消" onclick="javascript:history.back();" /></td>
              </tr>
            </table>
          </div>
        </form>
      </div>
      <!--**category add end**-->
    </div>
  </div>
</div>
<!--**content end**-->
<script type="text/javascript">
$(function(){
	$('#time_begin').datepicker({dateFormat:"yy-mm-dd"});
	$('#time_end').datepicker({dateFormat:"yy-mm-dd"});
});
</script>

<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate();
    });
</script>
<script type="text/javascript">
//<![CDATA[
url_base = '<?php echo url::base();?>';
//分类下拉树处理
function renderCategoryTree(catearr){
	retstr = '';
	retstr += '<option value="0">----</option>';
	arrlen = catearr.length;
	if(arrlen>0){
		for(i=0;i<arrlen;i++){	
			retstr += '<option value="'+catearr[i]['id']+'" >';
			depth = catearr[i]['level_depth'];
			for(j=1;j<depth;j++){
				retstr += '&#166;&nbsp;';
			}
			retstr += catearr[i]['name']+'</option>';
		}
	}
	return retstr;
}
$(function() {
	$("#site_id").unbind().bind('change keyup',function(e){
		/* get current event stat */
		cur_disstat = $(this).attr('disabled');
		if(!cur_disstat){
			/* disable controls */
			$("#site_change_tips").text('loading...');
			$(this).attr('disabled',true);

			/* prepare arguments */
			reqid = $(":selected",this).attr('value');
			urlbase = url_base+'b2b_product/b2b_category/get_site_categories?site_id='+reqid;

			/* ajax load data */
			xhrobj = $.ajax({url:urlbase,
					//cache:false,
				    dataType:'json',
				    error:function(xhr,status,err){
	    			//console.log(status);
	    			//console.log(err);
			    	  /* reset layout */
				      $("#site_id").attr('disabled',false);
				      $("#site_change_tips").html('request http error, please try again later');
				      window.setTimeout(function(){
					      /* clear tips */
				    	  $("#site_change_tips").empty();
					  },2000);
			    	},
			    	//timeout:1000,
					success:function(retdat,status){
						/* app logic ok */
						if(retdat['status']=='1' && retdat['code']=='200'){
	        				/* reset layout */
				    		$("#site_change_tips").empty();
	        				/* render layout */
	        				$("select#parent_id").html(renderCategoryTree(retdat['content']));
				    		$("#site_id").attr('disabled',false);
	        				/* rebind event */
						}else{
							/* render layout */
							$("#site_change_tips").html('request error with message:'+retdat['msg']);
							$("#site_id").attr('disabled',false);
						}
					}
				});
		}else{
			/* deal with the exception */
			$("#site_change_tips").html('request failed, please try to <a href="javascript:document.location.reload();">reload</a> the page.');			
		}
		
	});
});
//]]>
</script>
