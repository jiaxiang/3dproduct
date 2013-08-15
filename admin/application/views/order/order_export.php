<script type="text/javascript" src="<?php echo url::base();?>js/core.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#left").click(function(){
            $('#left_select').moveOptions('#right_select', {check_required: true, message: '以下字段是必须的：'});
        });
        $("#right").click(function(){
            $('#right_select').moveOptions('#left_select');
        });

        $("#left_select").dblclick(function(){
            $('#left_select').moveOptions('#right_select', {check_required: true, message: '以下字段是必须的：'});
        });
        $("#right_select").dblclick(function(){
            $('#right_select').moveOptions('#left_select');
        });
        //全到左边
        $("#leftAll").click(function(){
            $('#left_select').moveOptions('#right_select', {move_all: true});
        });
        //全到右边
        $("#rightAll").click(function(){
            $('#right_select').moveOptions('#left_select', {move_all: true,check_required: true, message: '以下字段是必须的：'});
        });

        //排序
        $("#up").click(function(){
            $('#left_select').swapOptions('up');
        });
        $("#down").click(function(){
            $('#left_select').swapOptions('down');
        });
    });
    //提交
    function add_form_submit(){
        if($("#export_name").val() == ''){
            alert('请为你的导出设置取名.');
            $("#export_name").focus();
            return false;
        }
        $("#left_select option").each(function(){
            $(this).attr('selected','selected');
        });
        $("#add_form").submit();
        return false;
    }
</script>
<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">自定义订单导出</li>
            </ul>
            <span class="fright">
                <?php if(site::id()>0):?>
                当前站点：<a href="http://<?php echo site::domain();?>" target="_blank" title="点击访问"><?php echo site::domain();?></a> [ <a href="<?php echo url::base();?>manage/site/out">返回</a> ]
                <?php endif;?>
            </span>
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
                    <form id="add_form" name="add_form" method="post" action="<?php echo url::base().'order/order_export/do_edit/'.$export_id;?>">
                        <TABLE id="linkgoods-table" width="100%" align="center">
                            <TBODY>
                                <!-- 导出列表 -->
                                <TR>
                                    <TH style="text-align:left;font-weight:bold;" class="111">排序</TH>
                                    <TH style="text-align:left;font-weight:bold;">需要导出的字段</TH>
                                    <TH style="text-align:center;font-weight:bold;">操作</TH>
                                    <TH style="text-align:left; font-weight:bold;">不需要导出的字段</TH>
                                </TR>
                                <TR>
                                    <TD style="text-align:center; vertical-align:middle;" width="2%">
                                        <a title="向上" id="up">
                                            <img border="0" alt="up" src="/images/icon_up.gif" style="cursor:pointer;"/>
                                        </a>
                                        <a title="向下" id="down">
                                            <img border="0" alt="down" src="/images/icon_down.gif" style="cursor:pointer;"/>
                                        </a>
                                    </TD>
                                    <TD width="42%">
                                        <select name="export_select[]" id="left_select" size="20" multiple style="width:100%">
                                            <?php
                                            foreach($export_select_list as $key=>$value):
                                                ?>
                                            <option value="<?php echo $value['id'];?>" <?php if($value['id'] == 1) {?>class="cm-required"<?php }?>><?php echo $value['show'];?></option>
                                            <?php
                                            endforeach;
                                            ?>
                                        </select>
                                    </TD>
                                    <TD style="text-align:center;"><p>
                                            <INPUT name="button" id="leftAll" type="button" onclick="" value=">>" class="button_arrow" style="cursor:pointer;">
                                        </p>
                                        <p>
                                            <INPUT name="button" id="left" type="button" onclick="" value=">" class="button_arrow" style="cursor:pointer;">
                                        </p>
                                        <p>
                                            <INPUT name="button" id="right" type="button"  onclick="" value="<" class="button_arrow" style="cursor:pointer;">
                                        </p>
                                        <p>
                                            <INPUT name="button" id="rightAll" type="button"  onclick="" value="<<" class="button_arrow" style="cursor:pointer;">
                                        </p></TD>
                                    <TD width="42%">
                                        <SELECT multiple size="20" name="export_spare[]" id="right_select" style="width:100%">
                                            <?php
                                            foreach($export_spare_list as $key=>$value):
                                                ?>
                                            <option value="<?php echo $value['id'];?>"><?php echo $value['show'];?></option>
                                            <?php
                                            endforeach;
                                            ?>
                                        </SELECT>
                                    </TD>
                                </TR>
                                <TR>
                                    <TD colspan="5"><span>自定义导出设置取名</span>
                                        <INPUT class="text" size="40" maxlength="100" id="export_name" name="export_name" type="text" value="<?php echo $export_name;?>" >
                                    </TD>
                                </TR>
                            </TBODY>
                        </TABLE>
                        <div class="list_save">
                            <input type="button" class="ui-button" name="button" value="保存添加信息" onclick="add_form_submit();">
                        </div>
                    </form>
                </div>
                <!--**productlist edit end**-->
            </div>
        </div>
    </div>
</div>
<!--**content end**-->
