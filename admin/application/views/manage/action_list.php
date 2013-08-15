<?php defined('SYSPATH') OR die('No direct access allowed.');
$list_columns = array(
	array('name'=>'ID','column'=>'id','class_num'=>'2', 'width'=>'40'),
	array('name'=>'名称','column'=>'name','class_num'=>'10', 'width'=>'250'),
	array('name'=>'标记','column'=>'resource','class_num'=>'6', 'width'=>'150'),
);
?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'manage/action/';?>'>操作资源列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">            	
            <?php echo role::view_check('<li><a href="/manage/action/add"><span class="add_pro">添加权限资源</span></a></li>', 'action_edit');?>
            </ul>
        </div>
        <?php if (is_array($actions) && count($actions)) {?>
        <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current();?>">
        <table  cellspacing="0">
                <thead>
                    <tr class="headings">
                        <!-- <th width="20"><input type="checkbox" id="check_all"></th> -->
                        <th width="100">操作</th>
                        <?php
                            foreach ($list_columns as $key=>$value):
                                echo '<th title="' . $value['name'] . '" width="' . $value['width'] . '">' . $value['name'] . '</th>';
                            endforeach;
                        ?>
                        <th></th> 
                    </tr>
                </thead>
                
                <tbody id="list-line">
                     <?php foreach ($actions as $value) : ?>
                    <tr id="<?php echo $value['level_depth'];?>" name="<?php echo $value['id'];?>" class="row">
                        <!-- <td><input class="sel" name="menu_ids[]" value="<?php echo $value['id'];?>" type="checkbox" /></td> -->
                        <td><?php echo role::view_check('<a href="' . url::base() . 'manage/action/edit/' . $value['id'] . '" >编辑</a>', 'action_edit');?>
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
                        endforeach;
                        ?>
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