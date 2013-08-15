<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">修改竞拍商品信息</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<form id="edit_form" name="edit_form" method="post" action="/product/product_auction/edit?id=<?php echo isset($data)?$data['id']:'';?>">
    <div class="tableform" id="tabs-1">
        <div class="division">
            <input name="id" id="id" type="hidden" value="<?php echo isset($data)?$data['id']:'';?>">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tbody>
                    <tr>
                        <th>允许上架竞拍： </th>
                        <td>
                            <input name="status" type="radio" value="1" <?php echo (isset($data) && $data['status']=='1')?'checked':'';?>>是 &nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="status" type="radio" value="0" <?php echo (empty($data['status']) || $data['status']==0)?'checked':'';?>>否
                        </td>
                    </tr>
                    <tr>
                        <th width=260>* 竞拍商品名称： </th>
                        <td><input name="name" type="input" class="text required"  value="<?php echo isset($data)?$data['name']:'';?>" size="50"></td>
                    </tr>
                    <tr>
                        <th>* 上架竞拍数量： </th>
                        <td><input name="qty" type="input" class="text required"  value="<?php echo isset($data)?$data['qty']:'';?>" size="50"></td>
                    </tr>
                    <!-- tr>
                        <th>允许自由竞拍： </th>
                        <td>
                            <input name="auto" type="radio" value="1" <?php echo (isset($data) && $data['auto']=='1')?'checked':'';?>>是 &nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="auto" type="radio" value="0" <?php echo (empty($data['auto']) || $data['auto']==0)?'checked':'';?>>否
                        </td>
                    </tr>
                    <tr>
                        <th>自由竞拍点数： </th>
                        <td>
                           <input name="auto_price" type="input" class="text"  value="<?php echo isset($data)?$data['auto_price']:'';?>" size="50">
                           （设定竞拍点到多少时开始进入自由竞拍模式）
                        </td>
                    </tr -->
                    <tr>
                        <th>竞拍成功奖励点数： </th>
                        <td>
                           <input name="reward_money" type="input" class="text"  value="<?php echo isset($data)?$data['reward_money']:'1';?>" size="50">
                        </td>
                    </tr>
                    <tr>
                        <th>每次竞拍扣除点数： </th>
                        <td>
                           <input name="use_money" type="input" class="text"  value="<?php echo isset($data)?$data['use_money']:'1';?>" size="50">
                        </td>
                    </tr>
                    <tr>
                        <th>竞拍起始价格： </th>
                        <td>
                           <input name="price_start" type="input" class="text"  value="<?php echo isset($data)?$data['price_start']:'0.00';?>" size="50">
                        </td>
                    </tr>
                    <tr>
                        <th>竞拍递增价格： </th>
                        <td>
                           <input name="price_increase" type="input" class="text"  value="<?php echo isset($data)?$data['price_increase']:'0.01';?>" size="50">
                           （设定每次投标竞拍价格递增多少）
                        </td>
                    </tr>
                    <tr>
                        <th>竞拍倒计时秒数： </th>
                        <td>
                           <input name="time_end" type="input" class="text"  value="<?php echo isset($data)?$data['time_end']:(12*3600);?>" size="50">
                           <input id='calculator' type='button' class='' value=' 计算器 '>（竞拍的倒计时秒数计算公式，如：倒计时12小时，秒数=12*60*60）
                        </td>
                    </tr>
                    <tr>
                        <th>竞拍倒计时重置秒数： </th>
                        <td>
                           <input name="time_reset" type="input" class="text"  value="<?php echo isset($data)?$data['time_reset']:10;?>" size="50">
                           （竞拍的倒计时结束前1秒，如果有人再次竞拍，再次重新多少秒开始倒计时）
                        </td>
                    </tr>                                    
                </tbody>
            </table>
        </div> 
    </div>                         
    
    <div style="text-align:center;">
    	<input type="submit" class="ui-button" value=" 保 存 " />
    </div>        
</form>
          
<div id="tool_calculator" style="display:nones;">
	<iframe style="border:0px;width:100%;height:98%;" frameborder="0" src="/index/calculator" scrolling="auto"></iframe>
</div>
              
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#edit_form").validate();
        
    	// 相关窗口
        $('#tool_calculator').dialog({
    		title: '计算器',
    		modal: true,
    		autoOpen: false,
    		height: 450,
    		width: 820
        });
        $('#calculator').click(function(){
    		var ifm = $('#tool_calculator');
    		ifm.dialog('open');
    	});
    });
</script>