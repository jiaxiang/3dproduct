<?php defined('SYSPATH') OR die('No direct access allowed.');
/* 返回的主体数据 */
$return_data = $return_struct['content'];
$count = $return_data['count'];
$list = $return_data['list'];
?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href="<?php echo url::base();?>product/category">分类列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li>
                    <a href="<?php echo url::base();?>product/category/add" title="添加新分类"><span class="add_pro">添加新分类</span></a>
                </li>
            </ul>
        </div>
        <?php if (is_array($list) && count($list)) {?>
        <table  cellspacing="0" class="table_overflow">
            <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="160">操作</th>
                            <?php echo view_tool::sort('id', 1, 50);?>
                            <?php echo view_tool::sort('URI', 1, 100);?>
                            <?php echo view_tool::sort('名称',4, 0);?>
                            <?php echo view_tool::sort('别名',2, 130);?>
                            <?php echo view_tool::sort('类型',6, 100);?>
                            <?php echo view_tool::sort('排序',8, 100);?>
                            <?php echo view_tool::sort('更新时间',10, 150);?>
                            <?php echo view_tool::sort('前台显示',12, 80);?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $str = '<tr class=\"row\" id=\"top_div_{$id}\" pid=\"{$pid}\" >
                                    <td>
                                        <a href=\"/product/category/edit?id={$id}\">编辑</a>
                                        <a href=\"/product/product/?category_id=".urlencode($title_manage)."\">分类商品</a>
                                        <a class=\"act_dodelete\" href=\"/product/category/delete?id={$id}\">删除</a>
                                        <a class=\"act_doexport\" href=\"/product/export/index?type=category&id={$id}\">导出商品</a>
                                    </td>
                                    <td>{$id}&nbsp;</td>
                                    <td>{$uri_name}&nbsp;</td>
                                    <td>&nbsp; {$spacer} <img src=\"/images/icon_dot2.gif\" class=\"icon_dot\" onclick=\"fold({$id})\" /> {$title}&nbsp;</td>
                                    <td>&nbsp; {$title_manage}&nbsp;</td>
                                    <td>{$classify_name}&nbsp;</td>
                                    <td class=\"over\">
										<div class=\"new_float_parent\">
											<input type=\"text\" class=\"text\" size=\"4\" name=\"position\" value=\"{$position}\" />
											<div class=\"new_float\" style=\"z-index:9999\">
												<input type=\"text\" class=\"text\" size=\"4\" name=\"order\" value=\"{$position}\" />
												<input type=\"button\" class=\"ui-button-small\" value=\"保存\" name=\"submit_order_form\" />
												<input type=\"hidden\" name=\"id\" value=\"{$id}\"/>
												<input type=\"button\" class=\"ui-button-small\" value=\"取消\" name=\"cancel_order_form\" />
											</div>                       
										</div>	
                                    </td>
                                    <td>{$update_timestamp}&nbsp;</td>
                                    <td>{$is_show}&nbsp;</td>
                                </tr>';
                        $tree = tree::get_tree($list, $str, 0, 0, '&nbsp;&nbsp;&nbsp;');
                        echo $tree;
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
        共<?php echo $count?>条
    </div>

    <div class="Turnpage_rightper">
        <div class="b_r_pager">
        </div>
    </div>
</div>
<!--END FOOTER-->
<script type="text/javascript" src="<?php echo url::base();?>js/message.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#list_form").validate({
            errorClass:'error1',
            errorPlacement: function(error, element) {
                alert(error.html());
            }
        });
    });
</script>
<script type="text/javascript">
    $(function() {
        $("#delete_all").click(function(){
            var list_form   = $('#list_form');
            var ids_checked = $('input[class="sel"]:checked').length;
            if (ids_checked > 0) { 
                confirm("确定删除所有被选中的项吗?",function(){
                    list_form.attr('action','<?php echo url::base();?>product/category/delete_all');
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

		$('.act_doexport').bind('click', function(){
			var url = $(this).attr('href');
			ajax_download(url);
			return false;
		});
        
        $("input.sel").unbind().bind('click',function(){
            var id = $(this).val();
       	    if($(this).attr('checked')){
                checkChild(id,true);
            }else{
                checkChild(id,false);
            }        
        });

        $("input.position").unbind().bind('focus',function(){
            var id = $(this).attr('tmp');
            var position = $(this).val();
            var str = "<div style=\"display:block\" class=\"new_float edit_position\"><input name=\"position_"+id+"\" class=\"position text required digits\" value=\""+position+"\" type=\"text\" size=\"5\"><input type=\"button\" class=\"submit_pos ui-button-small\" value=\"修改\" tmp=\""+id+"\"/><input type=\"button\" class=\"close_pos ui-button-small\"  value=\"取消\" onclick=\"$(this).parent(\'div.edit_position\').remove();\"/></div>";
            var obj = $(this).after(str);
            /* 按钮风格 */
            $(".ui-button,.ui-button-small").button();
            $("input.submit_pos").click(function(){
                var id = $(this).attr('tmp');
                var position = $(this).prev('.position').val();
                $('#list_form').attr('action','<?php echo url::base();?>product/category/edit_pos?id='+id+'&position='+position);
                $('#list_form').submit();
                return false;
            });
        });
    });

    function checkChild(id,checked){
        if($('input[pid="'+id+'"]').length > 0){
            $('input[pid="'+id+'"]').each(function(){
                if(checked){
                    $(this).attr('checked',true);
                }else{
                    $(this).attr('checked',false);
                } 
                checkChild($(this).val(),checked); 
            });
        }
    }

    function fold(id){
        var obj = $('#top_div_'+id);
        var img = obj.find('img.icon_dot');
        if(img.attr('src') == '/images/icon_dot2.gif'){
            img.attr('src','/images/icon_dot1.gif');
            foldchild(id,1);
        }else if(img.attr('src') == '/images/icon_dot1.gif'){
            img.attr('src','/images/icon_dot2.gif');
            foldchild(id,2);
        }
    }

    function foldchild(id,type){
        if($('tr[pid="'+id+'"]').length > 0){
            $('tr[pid="'+id+'"]').each(function(){
                if(type == 1){
                    $(this).find('img.icon_dot').attr('src','/images/icon_dot1.gif');
                    $(this).hide();
                }else if(type == 2){
                    $(this).find('img.icon_dot').attr('src','/images/icon_dot2.gif');
                    $(this).show();
                } 
                foldchild($(this).find('input[name="id"]').val(),type);
            });
        }
    }

    function pos(id,type){
        var urlbase = "";
        var obj = $('#top_div_'+id);
        var pid = obj.attr('pid');
        if(type == 1){
            urlbase = '/product/category/position/up?id='+id;
        }else if(type == 2){
            urlbase = '/product/category/position/down?id='+id;
        }
        ajax_block.open();
        $.ajax({
            url: urlbase,
            dataType: 'json',
            success: function(retdat,status) {
            	ajax_block.close();
                if (retdat['status'] == 1 && retdat['code'] == 200) {
                    //移动
                    id = retdat['content']['categorys'][0]['id'];
                    repalce_obj = $('#top_div_'+retdat['content']['categorys'][1]['id']);
                    move(id,repalce_obj);
                    changePosition(retdat['content']['categorys']);
                } else {
                    showMessage('操作失败',retdat['msg']);
                }
            },
            error: function() {
                ajax_block.close();
                showMessage('操作失败','请求错误，请稍候再试');
            }
        });
    }

    /**
     * 位置修改
     */
    function changePosition(categorys) {
        for (var k in categorys) {
            $('#top_div_'+categorys[k]['id']).find('input.position').val(categorys[k]['position']);
        }
    }

    function move(id,repalce_obj){
        var obj = $('#top_div_'+id);
        obj.clone(true).insertBefore(repalce_obj);
        obj.remove();
        if($('div[pid="'+id+'"]').length > 0){
            $('div[pid="'+id+'"]').each(function(){
                $(this).clone(true).insertBefore(repalce_obj);
                $(this).remove();
                move($(this).find('input.sel').val(),repalce_obj);
            });
        }
    }   
</script>
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
            var url = '<?php echo url::base();?>product/category/set_order';
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
                error:function(){},
                success:
                    function(retdat,status){
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
</script>