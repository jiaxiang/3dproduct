<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">分配商户可管理站点</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**productlist edit start**-->
            <div class="edit_area">
                <div class="division">
                    <form id="add_form" name="add_form" method="post" action="<?php echo url::base().url::current();?>">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="actionBar mainHead" align="center">
                            <tbody>
                                <tr>
                                    <td align="left" style="font-weight:bold;">搜索:
                                        <select name="site_type" id="site_type">
                                            <option value="">所有类型</option>
                                            <?php foreach($site_types as $key=>$value): ?>
                                            <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <INPUT name="keyword12" class="text">
                                        <INPUT name="button2" type="button" class="btn_text" onclick="searchGoods(sz1, 'cat_id1','brand_id1','keyword1')" value=" 搜索 "></td>
                                </tr>
                            </tbody>
                        </table>
                        <TABLE id="linkgoods-table" width="100%" align="center">
                            <!-- 商品搜索 -->
                            <TBODY>
                                <!-- 商品列表 -->
                                <TR>
                                    <TH style="text-align:left;font-weight:bold;">可选站点</TH>
                                    <TH style="text-align:center;font-weight:bold;">操作</TH>
                                    <TH style="text-align:left; font-weight:bold;">已经分配的站点</TH>
                                </TR>
                                <TR>
                                    <TD width="42%">
                                        <select name="source_select[]" id="source_select" size="20" multiple style="width:100%">
                                            <?php
                                            foreach($sites as $key=>$value):
                                                ?>
                                            <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
                                            <?php
                                            endforeach;
                                            ?>
                                        </select>
                                    </TD>
                                    <TD style="text-align:center;"><p>
                                            <INPUT name="button" id="addAll" type="button" onclick="" value=">>" class="button_arrow">
                                        </p>
                                        <p>
                                            <INPUT name="button" id="add" type="button" onclick="" value=">"class="button_arrow">
                                        </p>
                                        <p>
                                            <INPUT name="button" id="delete" type="button" onclick="" value="<" class="button_arrow">
                                        </p>
                                        <p>
                                            <INPUT name="button" id="deleteAll" type="button" onclick="" value="<<" class="button_arrow">
                                        </p></TD>
                                    <TD width="42%">
                                        <SELECT multiple size="20" name="target_select[]" id="target_select" style="width:100%" class="require">
                                        </SELECT>
                                    </TD>
                                </TR>
                            </TBODY>
                        </TABLE>
                        <div class="footContent" style="">
                            <div style="margin: 0pt auto; width: 200px; height: 40px;" class="mainFoot">
                                <table style="margin: 0pt auto; width: auto;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="button" class="ui-button" name="button" value="保存添加信息" onclick="check_target_select();">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
                <!--**productlist edit end**-->
            </div>
        </div>
    </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate();
    });
    //给添加和添加所有按钮添加onclick事件
    $(function(){
        $(":button[id^=add]").click(function(){
            var toAdds;
            if($(this).attr("id") == "addAll"){ //添加全部
                toAdds = $("#source_select option");
            }else{ //添加选中
                toAdds = $("#source_select option:selected");
                if(toAdds.length == 0){
                    alert("请先选择！");
                    return;
                }
            }
            toAdds.each(function(){
                $("#target_select").append("<option value='"+$(this).val()+"' ondblclick='delete_target_select();'>"+$(this).text()+"</option>");
                $(this).remove();
            });
        });
        $("#source_select option").dblclick(function(){
            var toAdds = $("#source_select option:selected");
            toAdds.each(function(){
                $("#target_select").append("<option value='"+$(this).val()+"' ondblclick='delete_target_select();'>"+$(this).text()+"</option>");
                $(this).remove();
            });
        });
    });

    //给删除和删除所有按钮添加onclick事件
    $(function(){
        $(":button[id^=delete]").click(function(){
            var todeletes;
            if($(this).attr("id") == "deleteAll"){ //删除全部
                todeletes = $("#target_select option");
            }else{ //删除选中
                todeletes = $("#target_select option:selected");
                if(todeletes.length == 0){
                    alert("请先选择！");
                    return;
                }
            }
            todeletes.each(function(){
                $("#source_select").append("<option value='"+$(this).val()+"' ondblclick='add_target_select();'>"+$(this).text()+"</option>");
                $(this).remove();
            });
        });
        $("#target_select option").dblclick(function(){
            var toAdds = $("#target_select option:selected");
            toAdds.each(function(){
                $("#source_select").append("<option value='"+$(this).val()+"' ondblclick='delete_target_select();'>"+$(this).text()+"</option>");
                $(this).remove();
            });
        });
    });
    function add_target_select(){
        var toAdds = $("#source_select option:selected");
        toAdds.each(function(){
            $("#target_select").append("<option value='"+$(this).val()+"' ondblclick='delete_target_select();'>"+$(this).text()+"</option>");
            $(this).remove();
        });
    }
    function delete_target_select(){
        console.log("click");
        todeletes = $("#target_select option:selected");
        todeletes.each(function(){
            $("#source_select").append("<option value='"+$(this).val()+"' ondblclick='add_target_select();'>"+$(this).text()+"</option>");
            $(this).remove();
        });
    }
    function check_target_select(){
        $("#target_select option").each(function(){
            $(this).attr('selected','selected');
        });
        $("#add_form").submit();
    }
</script>
