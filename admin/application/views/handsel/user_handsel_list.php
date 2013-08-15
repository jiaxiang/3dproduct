<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'handsel/handsel/user_handsel_list';?>'>已领取彩金用户</a></li>
            </ul>
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'handsel/handsel/user_handsel_list/3';?>'>申请彩金待审核用户</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
            </ul>
        </div>
        <table  cellspacing="0" >
        <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th style="width:60px;">ID</th>
                        <th style="width:150px;">用户名</th>
                        <th style="width:150px;">真实名</th>
                        <th style="width:150px;">身份证</th>
                        <th style="width:200px;">Email</th>
                        <th style="width:150px;">手机号</th>
                        <th style="width:150px;">操作</th>
						<th></th>
                    </tr>
                </thead>
                <tbody>
                     <?php if (is_array($user_list) && count($user_list)) :?>
                     <?php foreach ($user_list as $item) : ?>
                    <tr>
                        <td><?php echo $item['id'];?>&nbsp;</td>
                        <td><?php echo $item['lastname'];?>&nbsp;</td>
                        <td><?php echo $item['real_name'];?>&nbsp;</td>
                        <td><?php echo $item['identity_card'];?>&nbsp;</td>
                        <td><?php echo $item['email'];?>&nbsp;</td>
                        <td><?php echo $item['mobile'];?>&nbsp;</td>
                        <td><a href='<?php echo url::base() . 'handsel/handsel/check_status/'.$item['id'];?>'><?php if($item['check_status'] == 3)echo '通过';?></a>&nbsp;
                        &nbsp;<a href='<?php echo url::base() . 'handsel/handsel/check_status_fail/'.$item['id'];?>'><?php if($item['check_status'] == 3)echo '不通过';?></a>&nbsp;</td>
						<td></td>
                    </tr>
                     <?php endforeach;?>
                     <?php endif; ?>
                </tbody>
                </form>
        </table>
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
            var url = '<?php echo url::base();?>handsel/handsel/set_order';
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
        //删除友情链接
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
                alert('请选择要删除的友情链接！');
                return false;
            }
            if(!confirm('确认删除选中的友情链接？')){
                return false;
            }
            $('#list_form').attr('action','/handsel/handsel/batch_delete/');
            $('#list_form').submit();
            return false;
        });
    });
</script>