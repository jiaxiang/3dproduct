<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加物流方式</li>
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
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current();?>">
                <div class="edit_area">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="15%">站点： </th>
                                    <td>
                                        <select tabindex="3" name="site_id" class="text">
                                            <?php foreach ($site_list as $key=>$value) {?>
                                            <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                                <?php }?>
                                        </select>
                                    </td>
                                    <td> &nbsp;</td>
                                </tr>
                                <tr>
                                    <th>名称：</th>
                                    <td><input size="60" name="name" class="text required" value="" title="名称不能为空!"><span class="required"> *</span> </td>
                                    <td> &nbsp;</td>
                                </tr>
                                <tr>
                                    <th>链接：</th>
                                    <td><input size="60" name="url" class="text required" value="" title="链接不能为空!"><span class="required"> *</span> </td>
                                    <td> 前台显示指向该物流方式的链接,例如  http://www.ems.com.cn/english-main.jsp </td>
                                </tr>
                                <tr>
                                    <th>物流说明：</th>
                                    <td><input size="60" name="delay" class="text required" value="" title="物流说明不能为空!"><span class="required"> *</span></td>
                                    <td> 前台显示的物流方式简单说明,如:3-5 days </td>
                                </tr>
                                <tr>
                                    <th>计算方法：</th>
                                    <td>
                                        <input type="radio" name="type" value="0" checked >
                                        统一定价
                                        <input type="radio" name="type" value="1">
                                        基于价格衡量
                                        <!--
                                        <input type="radio"  disabled="disabled" name="type" value="2">
                                        基于重量衡量
                                        <input type="radio"  disabled="disabled" name="type" value="3">
                                        基于数量衡量
                                        -->
                                    </td>
                                    <td> 基于价格衡量，则前台将以订单总金额<br />在何金额区间来决定实际的物流费用,请于列表页添加衡量区间</td>
                                </tr>
                                <tr id="price">
                                    <th>统一定价运费金额：</th>
                                    <td><input size="30" name="price" class="text" value=""> <span class="required"> *</span>单位：美元 USD</td>
                                    <td> &nbsp;</td>
                                </tr>
                                <tr>
                                    <th>地区费用类型：</th>
                                    <td>
                                        <input type="radio" name="country_relative" value="0" checked>
                                        统一设置
                                        <input type="radio" name="country_relative" value="1">
                                        指定配送国家和费用
                                    </td>
                                    <td> 统一设置 则当前物流方式和所有可用国家关联.<br />指定配送国家和费用 则要在列表页添加关联国家及增加的费用</td>
                                </tr>
                                <tr>
                                    <th>是否可用：</th>
                                    <td>
                                        <input type="radio" name="active" value="1" checked>
                                        可用
                                        <input type="radio" name="active" value="0">
                                        不可用
                                    </td>
                                    <td> &nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <span id="single">
                        <input type="button" name="button" class="ui-button" value="保存返回列表"  onclick="submit_form();"/>
                        <input type="button" name="button" class="ui-button" value="保存当前"  onclick="submit_form(1);"/>
                    </span>
                    <span id="next_step" style="display:none;">
                        <input type="button" name="button" class="ui-button" value="下一步"  onclick="submit_form(2);"/>
                    </span>
                    <span id="next_step_country" style="display:none;">
                        <input type="button" name="button" class="ui-button" value="下一步"  onclick="submit_form(3);"/>
                    </span>
                    <input type="hidden" name="submit_target" id="submit_target" value="0" />
                </div>
            </form>
            <!--**productlist edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate({
            rules: {
                price: {
                    required: function(){ return ($("input[type=radio][name='type']:checked").val() == 0)}
                }
            }
        });
        $("input[type=radio][name=type]").click(function(){
            $("input[type=radio][name='type']:checked").each(function(){
                //由于复选框一般选中的是多个,所以可以循环输出
                if($(this).val()==0){
                    if($("input[type=radio][name='country_relative']:checked").val() == 1){
                        $("#next_step_country").show();
                        $("#next_step").hide();
                        $("#single").hide();
                    }else{
                        $("#next_step_country").hide();
                        $("#next_step").hide();
                        $("#single").show();
                    }
                    $('#price').show();
                }else{
                    $('#price').hide();
                    $("#next_step").show();
                    $("#single").hide();
                    $("#next_step_country").hide();
                }
            });
        });
        //国家
        $("input[type=radio][name=country_relative]").click(function(){
            $("input[type=radio][name='country_relative']:checked").each(function(){
                //由于复选框一般选中的是多个,所以可以循环输出
                if($(this).val()==0){
                    if($('#next_step_country').css('display') != 'none'){
                        $("#next_step_country").hide();
                        $("#single").show();
                    }
                }else{
                    if($('#next_step').css('display') == 'none'){
                        $("#next_step_country").show();
                        $("#single").hide();
                    }
                }
            });
        });
    });
</script>
