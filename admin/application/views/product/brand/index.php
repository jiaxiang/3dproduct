<?php defined('SYSPATH') OR die('No direct access allowed.');
/* 返回的主体数据 */
$return_data = $return_struct['content'];
$list = $return_data['list'];
?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href="<?php echo url::base();?>product/brand">品牌列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li>
                   <a href="javascript:void(0);" id="delete_all"> <span class="del_pro">批量删除</span></a>
                </li>
                <li>
                    <a href="<?php echo url::base();?>product/brand/add" title="添加品牌"><span class="add_pro">添加品牌</span></a>
                </li>
            </ul>
        </div>
        <?php if (is_array($list) && count($list)){?>
        <table  cellspacing="0" border=0>
             <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="20">
                        <input type="checkbox" id="check_all">
                        </th>
                        <th width="300">操作</th>
                        <?php echo view_tool::sort('品牌ID', 0, 100);?>
                        <?php echo view_tool::sort('品牌名称', 2, 200);?>
                        <th width="250">品牌别名</th>
                        <th width="100">品牌站点</th>
                        <th width="100">排序</th>
                    </tr>
                </thead>
                <tbody>
                  
                  <?php foreach($list as $val){?>
                  <tr id="top_div_<?php echo $val['id'];?>">
                  <td><input class="sel" name="id[]" value="<?php echo $val['id'];?>" type="checkbox" temp="<?php echo $val['id'];?>"></td>
                  <td> 
                    <a href="/product/brand/edit?id=<?php echo $val['id'];?>">编辑</a>
                    <a class="act_dodelete" href="/product/brand/delete?id=<?php echo $val['id'];?>"> 删除</a>
                  </td>
                  <td>&nbsp; <?php echo $val['id'];?>&nbsp;</td>
                  <td>&nbsp; <?php echo $val['name'];?>&nbsp;</td>
                  <td>&nbsp; <?php echo $val['alias'];?>&nbsp;</td>
                  <td>&nbsp; <a href="<?php echo $val['url'];?>" target='_blank'><?php echo $val['url'];?></a>&nbsp;</td>
                  <td>
                    <div class="new_float_parent">
                        <input type="text" class="text" size="4" name="position" value="<?php echo $val['order'];?>" />
                        <div class="new_float">
                            <input type="text" class="text" size="4" name="order" value="<?php echo $val['order'];?>" />
                            <input type="button" class="ui-button-small" value="保存" name="submit_order_form" />
                            <input type="hidden" name="order_id" value="<?php echo $val['id']; ?>"/>
                            <input type="button" class="ui-button-small" value="取消" name="cancel_order_form"/>
                        </div>
                    </div>
                  
                  </td>
                  </tr>
                  <?php 
                  }
                  ?>
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
<script type="text/javascript" src="<?php echo url::base();?>js/message.js"></script>
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
    var url = '<?php echo url::base();?>product/brand/set_order';
    var obj = $(this).parent();
    var id = $(this).next().val();
    var order = $(this).prev().val();
    $(this).parent().hide();
    if(order == default_order){
        return false;
    }
    obj.prev().attr('disabled','disabled');
    ajax_block.open();
    $.ajax({
        type:'GET',
        dataType:'json',
        url:url,
        data:'id='+id+'&order='+order,
        error:function(){ajax_block.close();},
        success: function(retdat,status) {
            ajax_block.close();
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
        $("#delete_all").click(function(){
        	var list_form   = $('#list_form');
            var ids_checked = $('input[class="sel"]:checked').length;
            if (ids_checked > 0) { 
         	    confirm("确定删除所有被选中的项吗?",function(){
         		    list_form.attr('action','<?php echo url::base();?>product/brand/delete_all');
					ajax_block.open();
         		    list_form.submit();
         	    });
            }else{
            	showMessage('操作失败','请选择要删除的项!');
            }
            return false;
        });
        
        $("a.act_dodelete").unbind().bind('click keyup',function(e){
        	obj = $(this);
            confirm('请确认要删除此项?',function(){
            	ajax_block.open();
                location.href = obj.attr('href');
            });
            return false;
        });
    });
</script>


