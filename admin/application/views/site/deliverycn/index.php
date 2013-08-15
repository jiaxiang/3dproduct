<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**--> 
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'site/deliverycn/';?>'>国内物流列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <?php echo role::view_check('<li><a href="'.url::base().'site/deliverycn/add" ><span class="add_pro">添加物流</span></a></li>', 'site_carrier_add');?>
                <li><a href="javascript:void(0);" id="batch_delete"><span class="del_pro">批量删除</span></a></li>
            </ul>
        </div>
     
        <?php if (is_array($deliveries) && count($deliveries)) :?>
        <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
        <table class="table_overflow" cellspacing="0">
            <thead>
                <tr class="headings">
                    <th width="20"><input type="checkbox" id="check_all"></th>
                    <th width="100">操作</th>
                    <th width="120">配送方式</th>
                    <th width="150">费用类型</th>
                    <th width="120">是否启用默认费用</th>
                    <th width="200">链接地址</th>
                    <?php echo view_tool::sort('排序', 6,80);?>
                    <th>说明</th>
                    <th width="60">状态</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($deliveries as $rs) : ?>
                <tr>
                    <td><input type="checkbox" class="sel" name="deliverycn_ids[]" value="<?php echo $rs['id'];?>" /></td>
                    <td><a href="<?php echo url::base();?>site/deliverycn/edit/<?php echo $rs['id'];?>">编辑</a>
                     <?php echo role::view_check('<a href="'.url::base().'site/deliverycn/do_delete/'.$rs['id'].'" onclick="javascript:return confirm(\'确定删除？\')" >删除</a>', 'site_carrier_delete');?>
                    </td>
                    <td><?php echo $rs['name']?></td>
                    <td><?php if($rs['type'] == 1) : ?>指定配送国家和费用
                        <a href="<?php echo url::base();?>site/deliverycn_region/index/<?php echo $rs['id'];?>"> 查看</a>
                        <?php else : ?> 统一配置<?php endif;?>
                    </td>
                    <td><?php echo view_tool::get_active_img($rs['is_default']);?></td>
                    <td><a href="<?php echo $rs['url']?>" target="_blank"><?php echo tool::my_substr($rs['url'], 28)?></a></td>
                    <td class="over">
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
                    <td><?php echo $rs['delay'];?></td>
                    <td><?php echo view_tool::get_active_img($rs['active']);?>&nbsp;</td>
                </tr> 
                <?php endforeach;?>
            </tbody>
        </table>
        </form>
        <?php else :?>
        <?php echo remind::no_rows();?>
        <?php endif;?>
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
<div id="message" class="ui-dialog-content ui-widget-content" style="height:160px;min-height:100px;width:auto;">
    <p id="message_content"></p>
</div>
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
        $('input[name=order]').bind('keyup',function(){
			if($(this).val()>999999999)
			{
				$(this).val(999999999);
			}
        });
        $('input[name=submit_order_form]').click(function(){
            var url = '<?php echo url::base();?>site/deliverycn/set_order';
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
                        showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
                    }
                }
            });
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
            $('#list_form').attr('action','/site/deliverycn/batch_delete/');
            $('#list_form').submit();
            return false;
        });

        //提示窗口
        $('#message').dialog({
            title: '',
            modal: true,
            autoOpen: false,
            height: 160,
            width: 300,
            buttons: {
                '确定': function(){
                    $('#message').dialog('close');
                }
            }
        });   
    });

  	//提示
    function showMessage(title, content) {
        var message = $('#message');
        $('#message_content').html(content);
        message.dialog('option', 'title', title);
        message.dialog('open');
    }
</script>
