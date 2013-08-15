<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加物流区间</li>
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
            <!--** edit start**-->
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current(TRUE);?>">
                <div class="edit_area">
                    <div class="division">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="15%">站点： </th>
                                    <td>
                                        <?php echo $site['domain'] . '[' . $site['name'] . ']';?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>物流名称：</th>
                                    <td><?php echo $data['name'];?></td>
                                </tr>
                                <tr>
                                    <th>物流区间：</th>
                                    <td>
                                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="finderInform">
                                            <thead>
                                                <tr>
                                                    <th width="20%" align="center">开始区间</th>
                                                    <th width="20%" align="center">结束区间</th>
                                                    <th width="20%" align="center">物流费用($)</th>
                                                    <th width="20%" align="center">删除</th>
                                                    <th width="20%" align="center">增加一列</th>
                                                </tr>
                                            </thead>
                                            <tbody id="option_content">
                                                <?php foreach($carrier_ranges as $key=>$value):?>
                                                <tr>
                                                    <td align="center"><input type="text" class="text" size="10" name="begin[<?php echo $value['id'];?>]" value="<?php echo $value['parameter_from'];?>"></td>
                                                    <td align="center"><input type="text" class="text" size="10" name="end[<?php echo $value['id'];?>]" value="<?php echo $value['parameter_to'];?>"></td>
                                                    <td align="center">$<input type="text" class="text" size="5" name="shipping[<?php echo $value['id'];?>]" value="<?php echo $value['shipping'];?>"></td>
                                                    <td align="center"><img alt="no" src="/images/icon/cancel.png" style="cursor: pointer;" onclick="option_delete(this);"></td>
                                                    <td align="center"><img alt="add" src="/images/icon/add.png" style="cursor: pointer;" onclick="option_add();"></td>
                                                </tr>
                                                <?php endforeach;?>
                                                <tr id="option_row">
                                                    <td align="center">
                                                        <input type="text" class="text" size="10" name="begin[]" id="begin">
                                                    </td>
                                                    <td align="center">
                                                        <input type="text" class="text" size="10" name="end[]" id="end">
                                                    </td>
                                                    <td align="center">
                                                        $<input type="text" class="text" size="5" name="shipping[]" id="shipping">
                                                    </td>
                                                    <td align="center">
                                                        <img alt="no" src="/images/icon/cancel.png" style="cursor: pointer;" onclick="option_delete(this);">
                                                    </td>
                                                    <td align="center">
                                                        <img alt="add" src="/images/icon/add.png" style="cursor: pointer;" onclick="option_add();">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <?php if($data['country_relative'] > 0):?>
                    <input type="button" name="button" class="ui-button" value="下一步"  onclick="submit_form(2);"/>
                    <?php else:?>
                    <input type="button" name="button" class="ui-button" value="确认保存"  onclick="submit_form(1);"/>
                    <?php endif;?>
                    <input type="hidden" name="submit_target" id="submit_target" value="0" />
                    <input type="hidden" name="hidden_data" value="yes" />
                </div>
            </form>
            <!--**productlist edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    function option_add(){
        var option_row = $('#option_row').html();
        $('#option_content').append('<tr>' + option_row + '</tr>');
        var option_content_tr = $('#option_content tr');
        $('#option_content tr').eq(option_content_tr.length-1).find('input').val('');
    }
    function option_delete(Obj){
        var current_row = $(Obj).parent().parent();
        if(current_row.attr('id') == 'option_row'){
            current_row.hide();
            return false;
        }
        if($('#option_content tr').length < 2){
            return false;
        }
        current_row.remove();
    }
</script>
