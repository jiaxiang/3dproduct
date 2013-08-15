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
                <li class="on"><a href="<?php echo url::base();?>product/classify">类型列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li>
                    <a href="javascript:void(0);" id="delete_all"><span class="del_pro">批量删除</span></a>
                </li>
                <li>
                    <a href="<?php echo url::base();?>product/classify/add" title="添加新类型"><span class="add_pro">添加新类型</span></a>
                </li>
                <li>
                    <a class="act_doexport" href="<?php echo url::base();?>product/export/index?type=classify&id=0" title="导出默认类型商品"><span class="add_pro">导出默认类型商品</span></a>
                </li>
            </ul>
        </div>
        <?php if (is_array($list) && count($list) > 0) : ?>
        <table  cellspacing="0">
             <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="20"><input type="checkbox" id="check_all"></th>
                        <th width="150">操作</th>
                        <?php echo view_tool::sort('类型名称',2, 0);?>
                        <?php echo view_tool::sort('更新时间',4, 0);?>
                    </tr>
                </thead>
                <tbody>
                      <?php foreach($list as $val) : ?>
	                  <tr id="top_div_<?php echo $val['id'];?>">
	                  <td><input class="sel" name="id[]" value="<?php echo $val['id'];?>" type="checkbox" temp="<?php echo $val['id'];?>"></td>
	                  <td> 
	                   <a href="/product/classify/edit?id=<?php echo $val['id'];?>">编辑</a>
	                   <a class="act_dodelete" href="/product/classify/delete?id=<?php echo $val['id'];?>"> 删除</a>
	                   <a class="act_doexport" href="/product/export/index?type=classify&id=<?php echo $val['id'];?>"> 导出商品</a></td>
	                  <td>&nbsp; <?php echo $val['name'];?>&nbsp;</td>
	                  <td>&nbsp; <?php echo date("Y-m-d H:i:s",$val['update_timestamp']);?>&nbsp;</td>
	                  </tr>
                  	 <?php endforeach; ?>
                </tbody>
            </form>
        </table>
        <?php else : ?>
            <?php echo remind::no_rows(); ?>
        <?php endif; ?>
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

	var url_base = '<?php echo url::base(); ?>';
	
    $(function() {
        $("#delete_all").click(function(){
        	var list_form   = $('#list_form');
            var ids_checked = $('input[class="sel"]:checked').length;
            if (ids_checked > 0) { 
         	    confirm("确定删除所有被选中的项吗?",function(){
         		    list_form.attr('action','<?php echo url::base();?>product/classify/delete_all');
					ajax_block.open();
         		    list_form.submit();
         	    });
            }else{
            	showMessage('操作失败','请选择要删除的项!');
            }
            return false;
        });
    });

    $(function() {

    	$('.act_doexport').bind('click', function(){
        	var url = $(this).attr('href');
        	ajax_download(url);
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
