<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'site/link/';?>'>站点友情链接列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li><a href="javascript:void(0);"><span class="del_pro" id="batch_delete">批量删除</span></a></li>
                <li><a href="/site/link/add"><span class="add_pro">添加友情链接</span></a></li>
            </ul>
        </div>
        <table  cellspacing="0">
        <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="20px"><input type="checkbox" id="check_all"></th>
                        <th width="100px">操作</th>
                        <th width="40px">ID</th>
                        <th>名称</th>
                        <th>URL</th>
                        <th>EMAIL</th>
                        <th>标签</th>
                        <?php echo view_tool::sort('排序', 2, 100);?>
                        <th width="40px">显示</th>
                    </tr>
                </thead>
                <tbody>
                     <?php if (is_array($data) && count($data)) :?>
                     <?php foreach ($data as $item) : ?>
                    <tr>
                        <td><input class="sel" name="link_ids[]" value="<?php echo $item['id'];?>" type="checkbox" /></td>
                        <td><a href="/site/link/edit/<?php echo $item['id']; ?>">编辑</a>&nbsp;
                            <a href="/site/link/delete/<?php echo $item['id']; ?>" onclick="javascript:return confirm('确定删除？')"> 删除</a>
                        </td>
                        <td><?php echo $item['id'];?>&nbsp;</td>
                        <td><?php echo $item['name'];?>&nbsp;</td>
                        <td><a href="<?php echo $item['url'];?>" target="_blank"><?php echo $item['url'];?></a>&nbsp;</td>
                        <td><?php echo $item['email'];?>&nbsp;</td>
                        <td><?php echo $item['title'];?>&nbsp;</td>
                        <td>
	                        <div class="new_float_parent">
                                <input type="text" class="text" size="4" name="position" value="<?php echo $item['order'];?>" />
                                <div class="new_float" style="z-index:9999">
                                    <input type="text" class="text" size="4" name="order" value="<?php echo $item['order'];?>"/>
                                    <input type="button" class="ui-button-small" value="保存" name="submit_order_form" />
                                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>"/>
                                    <input type="button" class="ui-button-small" value="取消" name="cancel_order_form" />
                            	</div>                       
							</div>	
                        </td>
                        <td>
                            <a href="<?php echo url::base();?>site/link/do_active/<?php echo $item['id'];?>">
                            <?php echo view_tool::get_active_img($item['status'],true);?>
                            </a>
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
            var url = '<?php echo url::base();?>site/link/set_order';
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
            $('#list_form').attr('action','/site/link/batch_delete/');
            $('#list_form').submit();
            return false;
        });
    });
</script>