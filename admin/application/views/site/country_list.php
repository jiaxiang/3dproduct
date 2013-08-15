<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'site/country/';?>'>国家</a></li>
            </ul>
        </div>

        <div class="newgrid_top">
            <ul class="pro_oper">
                <?php echo role::view_check('<li><a href="/site/country/add"><span class="add_pro">添加国家</span></a></li>', 'site_country_add');?>
                <?php echo role::view_check('<li><a href="javascript:void(0);"><span class="del_pro" id="batch_delete">批量删除</span></a></li>', 'site_country_delete');?>
            </ul>
        </div>
        <?php if (is_array($country_list) && count($country_list)) {?>
        <table  cellspacing="0">
            <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="20"><input type="checkbox" id="check_all"></th>
                        <th width="100">操作</th>
                        <th width="100">国家代码</th>
                        <th width="200">名称</th>
                        <?php echo view_tool::sort('排序', 2, 80);?>
                        <th width="30">状态</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                        <?php foreach ($country_list as $rs) : ?>
                    <tr>
                        <td><input class="sel" name="country_ids[]" value="<?php echo $rs['id'];?>" type="checkbox" /></td>
                        <td>
                                    <?php echo role::view_check('<a href="'.url::base().'site/country/edit/'.$rs['id'].'">编辑</a>', 'site_country');?>
                                    <?php echo role::view_check('<a href="'.url::base().'site/country/do_delete/'.$rs['id'].'" onclick="return confirm(\'删除会导致国家对应的物流信息也被删除，确认删除?\');">删除</a>', 'site_country_delete');?>
                        </td>
                        <td><?php echo $rs['iso_code'];?></td>
                        <td><?php echo $rs['name'];?></td>
                        <td>
                            <div class="new_float_parent">
                                <input type="text" class="text" size="4" name="position" value="<?php echo $rs['position'];?>" />
                                <div class="new_float">
                                    <input type="text" class="text" size="4" name="order" value="<?php echo $rs['position'];?>" />
                                    <input type="button" class="ui-button-small" value="保存" name="submit_order_form" />
                                    <input type="hidden" name="id" value="<?php echo $rs['id']; ?>"/>
                                    <input type="button" class="ui-button-small" value="取消" name="cancel_order_form"/></div>
                            </div>
                        </td>
                        <td><?php echo view_tool::get_active_img($rs['active']);?></td>
                        <td></td>
                    </tr>
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
<?php if (is_array($country_list) && count($country_list)) :?>
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
<?php endif;?>
<script type="text/javascript">
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
        var url = '<?php echo url::base();?>site/country/set_order';
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
    $(function() {
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
                alert('请选择要删除的国家！');
                return false;
            }
            if(!confirm('删除会导致国家对应的物流信息也被删除，确认删除选中的国家？')){
                return false;
            }
            $('#list_form').attr('action','/site/country/batch_delete/');
            $('#list_form').submit();
            return false;
        });
    });
</script>