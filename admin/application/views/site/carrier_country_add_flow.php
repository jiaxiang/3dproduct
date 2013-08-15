<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加物流的配送国家区间</li>
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
            <!--** edit start**-->
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current(TRUE);?>">
                <div class="edit_area">
                    <div class="division">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="15%">站点： </th>
                                    <td>
                                        <?php echo $site['domain'] . '[' . $site['name'] . ']';?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>物流名称：</th>
                                    <td><?php echo $data['name'];?></td>
                                </tr>
                                <tr>
                                    <th>国家列表：</th>
                                    <td>
                                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="finderInform">
                                            <thead>
                                                <tr>
                                                    <th width="10%" align="center"><input type="checkbox" class="text" onclick="all_checked(this);" title="点击全选"></th>
                                                    <th width="20%" align="center">国家代码</th>
                                                    <th width="40%" align="center">国家名称</th>
                                                    <th width="20%" align="center">附加费用</th>
                                                </tr>
                                            </thead>
                                            <tbody id="option_content">
                                                <?php foreach($countries as $key=>$value):?>
                                                <tr id="option_row">
                                                    <td align="center"><input type="checkbox" value="<?php echo $value['id'];?>" class="carrier_country_checkbox" name="carrier_country_check[<?php echo $value['id'];?>]" <?php echo (in_array($value['id'],$carrier_country_ids))?'checked="checked"':'';?>></td>
                                                    <td align="center"><?php echo $value['iso_code'];?></td>
                                                    <td align="center"><?php echo $value['name'];?></td>
                                                    <td align="center">$ <input type="text" class="text" size="5" name="price[<?php echo $value['id'];?>]" value="<?php echo $value['price'];?>"></td>
                                                </tr>
                                                <?php endforeach;?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <input type="button" name="button" class="ui-button" value="确认保存"  onclick="submit_form(0);"/>
                    <input type="hidden" name="submit_target" id="submit_target" value="0" />
                </div>
            </form>
            <!--**productlist edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    function all_checked(obj){
        if ($(obj).attr("checked") == true) {
            $('.carrier_country_checkbox').attr("checked", true);
        }else{
            $('.carrier_country_checkbox').attr("checked", false);
        }
    }

    //判断是否选择国家
    $("#add_form").validate({
    	submitHandler:function(form){
	    	var i = false;
	        $('.carrier_country_checkbox').each(function(){
	            if(i == false){
	                if($(this).attr("checked")==true){
	                    i = true;
	                }
	            }
	        });
	        if(i == false){
	            alert('请选择国家信息！');
	            return false;
	        }else{
	        	form.submit();
	        }        
    	}	
    });
</script>
