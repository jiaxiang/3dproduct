<?php defined('SYSPATH') OR die('No direct access allowed.');
/* 返回的主体数据 */
$return_data = $return_struct['content'];
$total = $return_data['total'];
$list = $return_data['list'];
?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href="<?php echo url::base();?>product/aliasfilter">虚拟分类列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li>
                    <a href="javascript:void(0);" id="delete_all"><span class="del_pro">批量删除</span></a>
                </li>
                <li>
                    <a href="<?php echo url::base();?>product/aliasfilter/add" title="添加虚拟分类"><span class="add_pro">添加虚拟分类</span></a>
                </li>
            </ul>
        </div>
        <?php if (is_array($list) && count($list)){?>
        <table  cellspacing="0">
             <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="20"> <input type="checkbox" id="check_all"></th>
                        <th width="40">操作</th>
                        <?php echo view_tool::sort('虚拟分类名称',2, 100);?>
                        <?php echo view_tool::sort('前台链接',4, 100);?>
                        <?php echo view_tool::sort('排序', 8, 100);?>
                        <?php echo view_tool::sort('更新时间',6, 100);?>
                    </tr>
                </thead>
                <tbody>
                 <?php
                                $str = '<tr id=\"top_div_{$id}\" pid=\"{$pid}\" >
                                <td>
                                        <input class=\"sel\" name=\"id[]\" value=\"{$id}\" type=\"checkbox\" pid=\"{$pid}\">
                                    </td>
                                    <td>
                                        <a href=\"/product/aliasfilter/edit?id={$id}\">编辑</a>
                                        <a class=\"act_dodelete\" href=\"/product/aliasfilter/delete?id={$id}\"> 删除</a>
                                    </td>
                                    <td>&nbsp; {$spacer} <img src=\"/images/icon_dot2.gif\" class=\"icon_dot\" onclick=\"fold({$id})\" /> {$title}&nbsp;</td>
                                    <td>&nbsp; {$uri_name}&nbsp;</td>
								    <td class=\"over\">
									<div class=\"new_float_parent\">
										<input type=\"text\" class=\"text\" size=\"4\" name=\"position\" value=\"{$order}\" />
										<div class=\"new_float\">
											<input type=\"text\" class=\"text\" size=\"4\" name=\"order\" value=\"{$order}\" />
											<input type=\"button\" class=\"ui-button-small\" value=\"保存\" name=\"submit_order_form\" />
											<input type=\"hidden\" name=\"order_id\" value=\"{$id}\"/>
											<input type=\"button\" class=\"ui-button-small\" value=\"取消\" name=\"cancel_order_form\"/>
										</div>
									</div>
								   </td>
                                   <td>{$update_timestamp}&nbsp;</td>
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
            共<?php echo $total;?>条
    </div>

    <div class="Turnpage_rightper">
        <div class="b_r_pager">
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
	var url = '<?php echo url::base();?>product/aliasfilter/set_order';
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
         		    list_form.attr('action','<?php echo url::base();?>product/aliasfilter/delete_all');
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

        $("input.sel").unbind().bind('click',function(){
        	var id = $(this).val();
       	    if($(this).attr('checked')){
               checkChild(id,true); 
            }else{
               checkChild(id,false); 
            }        
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
                foldchild($(this).find('input.sel').val(),type);
            });
        }
    }
</script>
