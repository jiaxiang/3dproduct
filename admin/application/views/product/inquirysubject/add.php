<?php defined('SYSPATH') OR die('No direct access allowed.');
/* 返回的主体数据 */
$return_data = $return_struct['content'];
?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加咨询主题</li>
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
            <form id="add_form" name="add_form" method="POST" action="<?php echo url::base();?>product/inquirysubject/do_add">
                        <div class="out_box">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tbody>
                                     <tr>
                                        <th width="10%">所属站点：</th>
                                        <td>
                                          <?php echo $site_name;?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>* 主题名称： </th>
                                        <td><input id="name" name="name" type="input" class="text required"  value="" size="50" maxlength="255"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                 <div class="list_save">
                 	<input name="dosubmit" type="submit" class="ui-button" value="添加" />
                 </div>
            </div>
            </form>
        </div>
        <!--**productlist edit end**-->
    </div>
</div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
    	var validator = $("#add_form").validate({
        	rules: {
			    name : {
				    remote: '/product/inquirysubject/check_name'
			    }
		    },
	        messages:{
		    	name : {
		    	    remote: '咨询主题名称已经存在'
		        }
		    }
        });
    });
</script>