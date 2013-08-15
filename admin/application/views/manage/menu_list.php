<?php defined('SYSPATH') OR die('No direct access allowed.');
$list_columns = array(
	array('name'=>'ID','column'=>'id','class_num'=>'40', 'width'=>'40'),
	array('name'=>'名称','column'=>'name','class_num'=>'250'),
	array('name'=>'对应权限','column'=>'action_name','class_num'=>'250')
);
?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'manage/menu/';?>'>菜单列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li>
                    <a href="<?php echo url::base();?>manage/menu/add"><span class="add_pro">添加新菜单</span></a>
                </li>
            </ul>
        </div>
        <?php if (is_array($menus) && count($menus)) {?>
        <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current();?>">
            <table  cellspacing="0">
                <thead>
                    <tr class="headings">
                        <!-- <th width="20"><input type="checkbox" id="check_all"></th> -->
                        <th width="100">操作</th>
                            <?php
                            foreach ($list_columns as $key=>$value):
                                echo '<th title="' . $value['name'] . '" width="' . $value['class_num'] . '">' . $value['name'] . '</th>';
                            endforeach;
                            ?>
                        <th width="100">排序</th>
                        <th width="100">状态</th>
                        <th></th> 
                    </tr>
                </thead>
                <tbody id="list-line">
                        <?php foreach ($menus as $value) : ?>
                    <tr id="<?php echo $value['level_depth'];?>" name="<?php echo $value['id'];?>" class="row">
                        <td><a href="<?php echo url::base();?>manage/menu/edit/<?php echo $value['id'];?>">编辑</a>
                        </td>
                                <?php
                                foreach ($list_columns as $column_key=>$column_value):
                                    if ($column_value['column'] == 'name') {
                                        ?>
                        <td><img onclick="rowClicked(this);" style="margin-left:<?php echo $value['level_depth'];?>em;" src="<?php echo url::base();?>images/icon_dot2.gif">
                            <?php echo $value[$column_value['column']];?></td>
                                <?php
                                    } else {
                                        echo '<td>' . $value[$column_value['column']] . '</td>';
                                    }
                                    ?>
                                <?php
                                endforeach;
                                ?>
                        <td>
	                        <div class="new_float_parent">
                                <input type="text" class="text" size="4" name="position" value="<?php echo $value['order'];?>" />
                                <div class="new_float" style="z-index:9999">
                                    <input type="text" class="text" size="4" name="order" value="<?php echo $value['order'];?>"/>
                                    <input type="button" class="ui-button-small" value="保存" name="submit_order_form" />
                                    <input type="hidden" name="id" value="<?php echo $value['id']; ?>"/>
                                    <input type="button" class="ui-button-small" value="取消" name="cancel_order_form" />
                            	</div>                       
							</div>	
                        </td>
                        <td><?php echo view_tool::get_active_img($value['active']);?></td>
                        <td></td>
                    </tr>
                        <?php endforeach;?>
                </tbody>                
            </table>
        </form>
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
        </div>
    </div>
</div>
<!--END FOOTER-->
<script type="text/javascript">
    /**
     * 折叠分类列表
     */
    function rowClicked(Obj)
    {
        var line_obj = $(Obj).parent().parent();
        var img_src = $(Obj).attr('src');

        var tbl = $("#list-line")
        var lvl = parseInt(line_obj.attr("id"));
        var fnd = false;

        $("#list-line").find('.row').each(function(){
            var row = $(this);
            if(line_obj.attr("name") == row.attr("name")){
                //console.log(line_obj.attr("name") == row.attr("name"));

                if(img_src == '<?php echo url::base();?>images/icon_dot1.gif')
                $(Obj).attr("src",'<?php echo url::base();?>images/icon_dot2.gif');
                else
                    $(Obj).attr("src",'<?php echo url::base();?>images/icon_dot1.gif');
                fnd = true;
            }else{
                if(fnd == true){
                    var cur = parseInt(row.attr("id"));
                    //console.log(cur);
                    //console.log(lvl);
                    if(cur > lvl){
                        if(row.css('display') == "none")
                            row.css('display',"");
                        else
                            row.css('display',"none");
                    }else{
                        fnd = false;
                        return true;
                    }
                }
        }
    });
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
            var url = '<?php echo url::base();?>manage/menu/set_order';
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
</script>