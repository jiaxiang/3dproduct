<?php defined('SYSPATH') OR die('No direct access allowed.');?>

<div class="out_box">
    <h3 class="title1_h3">添加物流费用区间 ： 根据订单金额决定物流费用 （单位：美元 USD）</h3>
    <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . 'site/carrier_range/do_add/' . $site_id . '/' . $carrier_id;?>">
        <table width="90%" border="0" cellpadding="0" cellspacing="0" >
            <thead >
                <tr>
                    <th width="15%">起始费用：</th>
                    <td>
                        <input size="20" name="parameter_from" class="text required" title="起始费用不能为空！" value="0"><span class="required"> *</span>
                        <br/>例如：100，表示从100美元开始,即订单金额>=100美元
                    </td>
                </tr>
            </thead>
            <thead >
                <tr>
                    <th>结束费用：</th>
                    <td>
                        <input size="20" name="parameter_to" class="text required" title="结束费用不能为空！"><span class="required"> *</span>
                        <br/>例如：200，表示到200美元结束,即订单金额<200美元
                    </td>
                </tr>
            </thead>
            <thead >
                <tr>
                    <th>物流费用：</th>
                    <td>
                        <input size="20" name="shipping" class="text required" title="物流费用不能为空！"><span class="required"> *</span>
                        <br/>例如：10，表示此区间的订单物流费用为10美元
                    </td>
                </tr>
            </thead>
            <thead >
                <tr>
                    <td colspan="2">
                        <div class="list_save">
                             <input name="submit" type="submit" class="ui-button" value=" 保存 " />
                        </div>
                    </td>
                </tr>
            </thead>
        </table>
    </form>
    <h3 class="title1_h3">已添加区间:</h3>
    <table width="90%" border="0" cellpadding="0" cellspacing="0" >
        <tbody>
            <tr>
                <th width="40%" align="left">开始</th>
                <th width="40%" align="left">结束</th>
                <th align="left">费用</th>
            </tr>
            <?php foreach ($carrier_ranges as $key=>$carrier_range):?>
            <tr>
                <td><?php echo $carrier_range['parameter_from'];?></td>
                <td><?php echo $carrier_range['parameter_to'];?></td>
                <td>$<?php echo $carrier_range['shipping'];?></td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <h3 class="title1_h3">特别说明，如果订单金额超出所有物流费用区间，则会按现有物流费用区间的最高物流费用计算</h3>
</div>
<link type="text/css" href="<?php echo url::base();?>css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo url::base();?>js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate();
        /* 按钮风格 */
    	$(".ui-button-small,.ui-button").button();
    });
</script>
