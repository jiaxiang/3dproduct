<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'site/news/';?>'>站点新闻列表</a></li>
            </ul>
        </div>`																																														
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li>
                    <a href="/site/news/add"><span class="add_pro">添加新闻</span></a>
                </li>
                <li><a href="javascript:void(0);"><span class="del_pro" id="batch_delete">批量删除</span></a></li>
				 <?php echo role::view_check('<li><a href="/site/news_category/"><span class="add_word">新闻分类管理</span></a></li>', 'doc_category');?>
            </ul>
            
        </div>
        <?php if (is_array($data) && count($data)) {?>
        <table  cellspacing="0">
        <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="15px"><input type="checkbox" id="check_all"></th>
                        <th width="60px">操作</th>
                        <th width="40px">ID</th>
                        <th width="240px">标题</th>
						 <th width="70px">来原</th>
						 <th width="60px" class="txc">点击数</th>
						 <th width="160px" class="txc">分类</th>
						 <th width="70px" class="txc">最新推荐</th>
						 <th width="70px" class="txc">首页推荐</th>
						 <th width="70px" class="txc">新闻推荐</th>
						 <th width="50px" class="txc">置顶</th>
                        <?php echo view_tool::sort('排序', 2, 60);?>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                     <?php foreach ($data as $item) : ?>
                    <tr>
                        <td><input class="sel" name="new_ids[]" value="<?php echo $item['id'];?>" type="checkbox" /></td>
                        <td><a href="/site/news/edit/<?php echo $item['id']; ?>">编辑</a>&nbsp;
							<a href="/site/news/delete/<?php echo $item['id']; ?>" onclick="javascript:return confirm('确定删除？')"> 删除</a>
                        </td>
                        <td><?php echo $item['id'];?>&nbsp;</td>
                        <td><a href="<?php echo site::default_domain()."/news/infor_list/".$item['id']; ?>" target="_blank"><?php echo $item['title'];?></a>&nbsp;</td>
						<td><?php echo $item['comefrom'];?></td>
						<td  class="txc"><?php echo $item['click'];?></td>
						<td  class="txc"> <?php  if(!empty($categorys[$item['classid']])){echo $categorys[$item['classid']];}?></td>
						<td  class="txc"> <img src="../images/<?php echo $item['zxtj'];?>.png" /></td>
						<td  class="txc"><img src="../images/<?php if($item['indextj']>0){echo '1';}else{echo '0';}?>.png" /></td>
						<td  class="txc"><img src="../images/<?php echo $item['newstj'];?>.png" /></td>
						<td class="txc"><img src="../images/<?php echo $item['zd'];?>.png" /></td>
						 
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
		
		$(function() {
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
                alert('请选择要删除的新闻！');
                return false;
            }
            if(!confirm('确认删除选中的新闻？')){
                return false;
            }
            $('#list_form').attr('action','/site/news/batch_delete/');
            $('#list_form').submit();
            return false;
        });
    });
</script>