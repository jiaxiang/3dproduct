<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'handsel/handsel/';?>'>彩金管理</a></li>
            </ul>
        </div>

        <table  cellspacing="0">
        <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <!--<th width="20px"><input type="checkbox" id="check_all"></th>-->
                        <th width="100px">操作</th>
                        <th>彩金赠送活动名称</th>
                        <th>活动开始时间</th>
                        <th>活动结束时间</th>
                        <th>赠送彩金额度</th>
                        <th>彩金状态</th>
                        <th>人工审核</th>
                    </tr>
                </thead>
                <tbody>
                     <?php if (is_array($data) && count($data)) :?>
                     <?php foreach ($data as $item) : ?>
                    <tr>
                        <!--<td><input class="sel" name="link_ids[]" value="<?php echo $item['id'];?>" type="checkbox" /></td>-->
                        <td><a href="/handsel/handsel/edit/<?php echo $item['id']; ?>">编辑</a>&nbsp;
                        </td>
                        <td><?php echo $item['title'];?>&nbsp;</td>
                        <td><?php echo $item['start_time'];?></a>&nbsp;</td>
                        <td><?php echo $item['end_time'];?>&nbsp;</td>
                        <td><?php echo $item['total'];?>&nbsp;</td>
                        <td><?php if($item['status'] == 0){echo "未开启";}else{ echo "已开启";}?>
                        <td><?php if($item['check'] == 0){echo "未开启";}else{ echo "已开启";}?>
                        </td>
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
    </div>

    <div class="Turnpage_rightper">
        <div class="b_r_pager">
            <div class="totalview">共<?php echo $total;?>条</div>
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