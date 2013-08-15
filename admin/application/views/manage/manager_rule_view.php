<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">用户权限列表</li>
            </ul>
        </div>
        <?php if (is_array($actions) && count($actions)) {?>
        <table  cellspacing="0">
                <thead>
                    <tr class="headings">
                        <?php
							$list_columns = array(
								array('name'=>'ID','column'=>'id','class_num'=>'2', 'width'=>'40'),
								array('name'=>'名称','column'=>'name','class_num'=>'10', 'width'=>'220'),
								array('name'=>'权限资源','column'=>'resource','class_num'=>'5', 'width'=>'180'),
							);
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

    /**
     * 处理复选间的关系
     */
    function check_bind(Obj){
        var cur = parseInt($(Obj).attr("class"));
        if(cur == 1){
            $(Obj).parent().parent().nextAll().each(function(){
                var lvl = parseInt($(this).attr("id"));
                if(lvl > cur){
                    //console.log(lvl);
                    //console.log($(this).find('input').val());
                    if($(Obj).attr("checked") == true){
                        $(this).find('input').attr("checked",true);
                    }else{
                        //console.log("FALSE");
                        $(this).find('input').attr("checked",false);
                    }
                }else{
                    return false;
                }
            });
        }else if(cur == 2){
            $(Obj).parent().parent().nextAll().each(function(){
                var lvl = parseInt($(this).attr("id"));
                if(lvl > cur){
                    //console.log(lvl);
                    //console.log($(this).find('input').val());
                    if($(Obj).attr("checked") == true){
                        $(this).find('input').attr("checked",true);
                    }else{
                        //console.log("FALSE");
                        $(this).find('input').attr("checked",false);
                    }
                }else{
                    return false;
                }
            });
        }else if(cur == 3){
            $(Obj).parent().parent().nextAll().each(function(){
                var lvl = parseInt($(this).attr("id"));
                if(lvl > cur){
                    //console.log(lvl);
                    //console.log($(this).find('input').val());
                    if($(Obj).attr("checked") == true){
                        $(this).find('input').attr("checked",true);
                    }else{
                        //console.log("FALSE");
                        $(this).find('input').attr("checked",false);
                    }
                }else{
                    return false;
                }
            });
        }
    }
</script>