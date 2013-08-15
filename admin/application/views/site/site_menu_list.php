<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<script type="text/javascript">
    $(function() {
        /* 添加导航栏目 */
        $(".pro_oper .down").hover(function(){
            $(this).addClass("on");
            $(this).children("ul").show();
        }, function(){
            $(this).removeClass("on");
            $(this).children("ul").hide();
        });
    });
</script>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'site/menu/';?>'>站点导航菜单列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li class="down" id="menu_add"><span class="batch_pro left">添加导航</span><span class="down_arrow left"></span>
                    <ul class="level_2">
                        <li><a id="address_link" href="/site/menu/address_menu_add">链接导航</a></li>
                        <li><a id="category_link" href="/site/menu/category_menu_add">分类导航</a></li>
                        <li><a id="doc_link" href="/site/menu/doc_menu_add">文案导航</a></li>
                    </ul>
                </li>
                <li><a href="javascript:void(0);"><span class="del_pro" id="batch_delete">批量删除</span></a></li>
            </ul>
        </div>
        <?php if (is_array($data) && count($data)) {?>
        <table  cellspacing="0">
        <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="20px"><input type="checkbox" id="check_all"></th>
                        <th width="100px">操作</th>
                        <th width="120px">名称</th>
                        <th width="120px">标签</th>
                        <th width="180px">URL</th>
                        <?php echo view_tool::sort('上级导航名称',4,120);?>
                        <?php echo view_tool::sort('导航类型', 6, 80);?>
                        <?php echo view_tool::sort('排序', 2, 60);?>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                     <?php foreach ($data as $item) : ?>
                    <tr>
                        <td><input class="sel" name="menu_ids[]" value="<?php echo $item['id'];?>" type="checkbox" /></td>
                        <td><?php 
                        		switch($item['memo'])
       						 	{
                        			case 'category':echo '<a href="/site/menu/category_menu_edit/'.$item['id'];break;
                        			case 'doc':echo '<a href="/site/menu/doc_menu_edit/'.$item['id'];break;
                        			default:echo '<a href="/site/menu/address_menu_edit/'.$item['id'];break;                        			
                        		}
                        	?>">编辑</a>&nbsp;
                            <a href="/site/menu/delete/<?php echo $item['id']; ?>" onclick="javascript:return confirm('确定删除？',function(){}"> 删除</a>
                        </td>
                        <td><?php echo $item['name'];?>&nbsp;</td>
                        <td><?php echo $item['title'];?>&nbsp;</td>
                        <td><?php echo $item['url'];?>&nbsp;</td>
                        <td><?php echo $item['parent_name'];?>&nbsp;</td>
                        <td><?php 
                        		switch($item['memo'])
                        		{
                        			case 'category':echo '分类';break;
                        			case 'doc':echo '文案';break;
                        			default:echo '链接';break;                        			
                        		}
                        	?>
                        </td>
                        <td>
	                        <div class="new_float_parent">
	                        <input type="text" class="text" size="4" name="position" value="<?php echo $item['order'];?>" />
	                        <div class="new_float">
	                        <input type="text" class="text" size="4" name="order" value="<?php echo $item['order'];?>"/>
	                        <input type="button" class="ui-button-small" value="保存" name="submit_order_form" />
	                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>"/>
	                        <input type="button" class="ui-button-small" value="取消" name="cancel_order_form" />
                            </div>
	                        </div>
                        </td>
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
    </div>

    <div class="Turnpage_rightper">
        <div class="b_r_pager">
            <div class="totalview">共<?php echo $total;?>条</div>
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
            var url = '<?php echo url::base();?>site/menu/set_order';
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
        //删除导航
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
                showMessage('操作失败','请选择要删除的导航!');
            }
            else
            {
                confirm("确认要删除选中的导航?",function(){
            		$('#list_form').attr('action','<?php echo url::base();?>site/menu/batch_delete/');
            		ajax_block.open();
            		$('#list_form').submit();
                });
            }
            return false;
        });
    });
</script>