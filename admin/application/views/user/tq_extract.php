<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'user/tq_extract/';?>'>提取金额列表</a></li>
            </ul>
        </div>`																																														
        <div class="newgrid_top">
            <ul class="pro_oper">
				<li><a href="javascript:void(0);"><span class="del_pro" id="batch_delete">批量作废</span></a></li>
                 <li><a href="/user/tq_extract/index/3"><span class="add_word">已提交</span></a></li>
				 <li><a href="/user/tq_extract/index/2"><span class="add_word">已取消</span></a></li>
				 <li><a href="/user/tq_extract/index/1"><span class="add_word">已打款</span></a></li>
				 <li><a href="/user/tq_extract/"><span class="add_word">全部</span></a></li>
				 
				<li><a href="/user/tq_extract/export"><span class="batch_down" id="export_all">导出所有提款</span></a></li>
				<li><a href="/user/tq_extract/export"><span class="batch_down" id="export" >导出指定提款</span></a></li>
            </ul>
        </div>
        <?php
		 if(is_array($data) && count($data)) {?>
        <table  cellspacing="0">
        <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
              <thead>
                    <tr class="headings">
                        <th width="15px"><input type="checkbox" id="check_all"></th>
                        <th width="60px">操作</th>
                        <th width="100px">订单号</th>
                        <th width="60px">姓名</th>
						<th width="100px" class="txc">会员名</th>
						<th width="70px">剩余金额</th>
						 <th width="70px">提取金额</th>
						 <th width="60px" class="txc">手续费</th>
						 <th width="60px" class="txc">状态</th>
						 <th width="60px" class="txc">扣除提款状态</th>
						 <th width="260px" class="txc">备注</th>
					    
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                     <?php foreach ($data as $item) : ?>
                    <tr>
                        <td><input class="sel" name="ids[]" value="<?php echo $item['id'];?>" type="checkbox" /></td>
                        <td><a href="/user/tq_extract/edit/<?php echo $item['id']; ?>">操作</a></td>
                        <td><?php echo $item['order_num'];?></td>
                        <td><?php echo $item['tq_name'];?></td>
						<td class="txc"><?php echo $item['user']['lastname']?></td>
						<td class="txc"><?php echo $item['user']['user_money']?></td>
						<td><?php echo $item['money'];?></td>
						<td class="txc"><?php echo $item['poundage'];?></td>
						<td class="txc"><?php  echo $bankinfo[0][$item['type']];?></td>
						<td class="txc"><img src="/../images/<?php echo $item['deductible'];?>.png" /></td>
						<td class="txc"><?php  echo $item['content'];?></td>
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
            var url = '<?php echo url::base();?>site/news/set_order';
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
		
		$(function(){
       
		//会员的导出
        $("#export").click(function(){
            var arr = $("input[name='ids[]']");
            var str = 'export_point_user=1&';
            for(var i=0;i<arr.length;i++)
            {
                if(arr.eq(i).attr('checked'))
                {
                    str += 'ids[]='+arr.eq(i).val()+'&';
                }
            }
            str = '/user/tq_extract/export?'+str;
            location.href=str;
            return false;
        });
		
		
		
		 //删除新闻
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
                alert('请选择要作废的订单！');
                return false;
            }
            if(!confirm('确认作废的选中的订单？')){
                return false;
            }
            $('#list_form').attr('action','/user/tq_extract/batch_delete/');
            $('#list_form').submit();
            return false;
        });
    });
</script>