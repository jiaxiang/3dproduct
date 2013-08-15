<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'site/carrier/';?>'>物流列表</a></li>
            </ul>
            <span class="fright">
                <?php if(site::id()>0):?>
                当前站点：<a href="http://<?php echo site::domain();?>" target="_blank" title="点击访问"><?php echo site::domain();?></a> [ <a href="<?php echo url::base();?>manage/site/out">返回</a> ]
                <?php endif;?>
            </span>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <?php echo role::view_check('<li><span class="add_pro"><a href="'.url::base().'site/carrier/add" >添加物流</a></span></li>', 'site_carrier_add');?>
                <li><a href="javascript:void(0);"><span class="del_pro" id="batch_delete">批量删除</span></a></li>
            </ul>
        </div>
     
        <?php if (is_array($carrier_list) && count($carrier_list)) {?>
        <table  cellspacing="0">
        <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
            <thead>
                <tr class="headings">
                    <th width="20"><input type="checkbox" id="check_all"></th>
                    <th width="100">操作</th>
                        <?php echo view_tool::sort('衡量类型', 10, 200);?>
                    <th width="200">地区费用类型</th>
                        <?php echo view_tool::sort('站点', 2, 150);?>
                        <?php echo view_tool::sort('名称', 4, 150);?>
                        <?php echo view_tool::sort('排序', 12,100);?>
                        <?php echo view_tool::sort('链接', 6, 300);?>
                        <?php echo view_tool::sort('说明', 8, 150);?>
                        <?php echo view_tool::sort('可用', 14, 50);?>
                </tr>
            </thead>
            <tbody>
                    <?php foreach ($carrier_list as $rs) : ?>
                <tr>
                    <td><input class="sel" name="carrierids[]" value="<?php echo $rs['id'];?>" type="checkbox" /></td>
                    <td><a href="<?php echo url::base();?>site/carrier/edit/<?php echo $rs['id'];?>">编辑</a>
                                <?php echo role::view_check('<a href="'.url::base().'site/carrier/do_delete/'.$rs['id'].'" onclick="javascript:return confirm(\'确定删除？\')" >删除</a>', 'site_carrier_delete');?>
                    </td>
                    <td onclick="javascript:$('#group_<?php echo $rs['id'];?>').toggle();">
                                <?php echo kohana::config('product.carrier_type.'.$rs['type']);?>&nbsp;
                                <?php if($rs['type']) { ?>
                        <a href="javascript:void(0);" class="carrier_range_add" carrier_id="<?php echo $rs['id'];?>" site_id="<?php echo $rs['site']['id'];?>">添加区间</a>
                                    <?php }else {
                                    echo '$'.$rs['price'];
                                }//end if?>&nbsp;</td>
                    <td onclick="javascript:$('#group_<?php echo $rs['id'];?>').toggle();">
                                <?php if($rs['country_relative']) { ?>指定配送国家
                        <a href="<?php echo url::base();?>site/carrier_country/index/<?php echo $rs['id'];?>"> 查看</a>
                                    <?php }else {
                                    echo '统一配置';
                                }//end if?>&nbsp;</td>
                    <td onclick="javascript:$('#group_<?php echo $rs['id'];?>').toggle();"><?php echo $rs['site']['name'];?>&nbsp;</td>
                    <td onclick="javascript:$('#group_<?php echo $rs['id'];?>').toggle();"><?php echo $rs['name'];?>&nbsp;</td>
                    <td>
                        <div class="new_float_parent">
                            <input type="text" class="text" size="4" name="position" value="<?php echo $rs['position'];?>"/>
                            <div class="new_float">
                                <input type="text" class="text" size="4" name="order" value="<?php echo $rs['position'];?>"/>
                                <input type="button" class="ui-button-small" value="保存" name="submit_order_form"/>
                                <input type="hidden" name="id" value="<?php echo $rs['id']; ?>"/>
                                <input type="button" class="ui-button-small" value="取消" name="cancel_order_form"/>
                            </div>
                        </div>
                    </td>
                    <td onclick="javascript:$('#group_<?php echo $rs['id'];?>').toggle();"><?php echo $rs['url'];?>&nbsp;</td>
                    <td onclick="javascript:$('#group_<?php echo $rs['id'];?>').toggle();"><?php echo $rs['delay'];?>&nbsp;</td>
                    <td onclick="javascript:$('#group_<?php echo $rs['id'];?>').toggle();"><?php echo view_tool::get_active_img($rs['active']);?>&nbsp;</td>
                </tr>
                        <?php if($rs['type']) { ?>
                <tr style="display:none;" id="group_<?php echo $rs['id'];?>">
                    <td colspan="10">
                        <div class="new_in_table">
                            <table>
                                <thead>
                                    <tr class="headings">
                                        <th width="20%">区间开始&nbsp;</th>
                                        <th width="20%">区间结束&nbsp;</th>
                                        <th width="25%">费用&nbsp;</th>
                                        <th width="25%">操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                                <?php foreach($rs['carrier_ranges'] as $k=>$carrier_range) { ?>
                                    <tr>
                                        <td><?php echo $carrier_range['parameter_from'];?></td>
                                        <td><?php echo $carrier_range['parameter_to'];?></td>
                                        <td>$ <?php echo $carrier_range['shipping'];?></td>
                                        <td><a href="javascript:void(0);" class="carrier_range_edit" id="<?php echo $carrier_range['id'];?>">编辑</a>
                                            <a href="<?php echo url::base();?>site/carrier_range/do_delete/<?php echo $carrier_range['id'];?>"> 删除</a></td>
                                    </tr>
                                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                            <?php }?>
                    <?php endforeach;?>
            </tbody>
            </form>
        </table>
            <?php }else {?>
            <?php echo remind::no_rows();?>
            <?php }?>

    </div>
</div>
<!--**content end**-->
<!--FOOTER-->
<div class="new_bottom fixfloat">
    <div class="b_r_view new_bot_l">
        <?php echo view_tool::per_page(); ?>
    </div>

    <div class="Turnpage_rightper">
        <div class="b_r_pager">
            <?PHP echo $this->pagination->render('opococ'); ?>
        </div>
    </div>
</div>
<!--END FOOTER-->
<div id='carrier_range' style="display:none;"></div>
<script type="text/javascript">
    $(document).ready(function(){
        var default_order = '0';
        $('input[name=position]').focus(function(){
            $('.new_float').hide();
            default_order = $(this).val();
            $(this).next().show();
            $(this).next().children('input[name=order]').focus();
        });
        $('input[name=cancel_order_form]').click(function(){
            $(this).parent().hide();
        });
        $('input[name=submit_order_form]').click(function(){
            var url = '<?php echo url::base();?>site/carrier/set_order';
            var obj = $(this).parent();
            var id = $(this).next().val();
            var order = $(this).prev().val();
            $(this).parent().hide();
            if(order == default_order){
                return false;
            }
            obj.prev().attr('disabled','disabled');
            $.ajax({
                type:'GET',
                dataType:'json',
                url:url,
                data:'id='+id+'&order='+order,
                error:function(){},
                success:
                    function(retdat,status){
                    obj.prev().removeAttr('disabled');
                    if(retdat['status'] == 1 && retdat['code'] == 200)
                    {
                        obj.prev().attr('value',(retdat['content']['order']));
                    }else{
                        alert(retdat['msg']);
                    }
                }
            });
        });
        //查看邮件模板
        var dialogOpts_add = {
            title: "添加物流费用区间",
            modal: true,
            autoOpen: false,
            maxHeight: 500,
            width: 800,
            position: ['center',50]
        };

        var dialogOpts_edit = {
            title: "编辑物流费用区间",
            modal: true,
            autoOpen: false,
            maxHeight: 500,
            width: 800,
            position: ['center',50]
        };
        $(".carrier_range_edit").click(function(){
            $("#carrier_range").dialog(dialogOpts_edit);
            var id = $(this).attr('id');
            $("#carrier_range").html("loading...");
            $.ajax({
        		url: '<?php echo url::base();?>site/carrier_range/ajax_edit' + '?id=' + id,
                type: 'GET',
                dataType: 'json',
                error: function() {
                    window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
                },
                success: function(retdat, status) {
    				ajax_block.close();
    				if (retdat['code'] == 200 && retdat['status'] == 1) {
    					$("#carrier_range").html(retdat['content']);
    				} else {
    					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
    				}
    			}
        	});
            $("#carrier_range").dialog("open");
        });
        $(".carrier_range_add").click(function(){
            $("#carrier_range").dialog(dialogOpts_add);
            var site_id = $(this).attr('site_id');
            var carrier_id = $(this).attr('carrier_id');
            $("#carrier_range").html("loading...");
            $.ajax({
        		url: '<?php echo url::base();?>site/carrier_range/ajax_add' + '?site_id=' + site_id + '&carrier_id' + carrier_id,
                type: 'GET',
                dataType: 'json',
                error: function() {
                    window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
                },
                success: function(retdat, status) {
    				ajax_block.close();
    				if (retdat['code'] == 200 && retdat['status'] == 1) {
    					$("#carrier_range").html(retdat['content']);
    				} else {
    					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
    				}
    			}
        	});
            $("#carrier_range").dialog("open");
        });
				    
        //删除
        $("#batch_delete").click(function(){
            var i = false;
            $('.sel').each(function(){
                if(i == false){
                    if($(this).attr("checked")==true){
                        i = true;
                    }
                }
            });
            if(i == false){
                alert('请选择要删除的物流！');
                return false;
            }
            if(!confirm('确认删除选中的物流？')){
                return false;
            }
            $('#list_form').attr('action','/site/carrier/batch_delete/');
            $('#list_form').submit();
            return false;
        });
    
    });
</script>
