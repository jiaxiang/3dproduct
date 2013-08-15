<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑晒宝信息</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<form id="edit_form" name="edit_form" method="post" action="/user/user_show/edit?id=<?php echo isset($data)?$data['id']:'';?>">
    <div class="tableform" id="tabs-1">
        <div class="division">
            <input name="id" id="id" type="hidden" value="<?php echo isset($data)?$data['id']:'';?>">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tbody>
                    <tr>
                        <th>允许前台显示：</th>
                        <td>
                            <input name="status" type="radio" value="1" <?php echo (isset($data) && $data['status']=='1')?'checked':'';?>>是 &nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="status" type="radio" value="0" <?php echo (empty($data['status']) || $data['status']==0)?'checked':'';?>>否
                        </td>
                    </tr>
                    <tr>
                        <th>奖励拍点： </th>
                        <td>
                            <?php if(isset($data) && $data['get_money']>0){ ?>
                                <?php echo $data['get_money'];?>
                            <?php }else{ ?>
                                <input name="get_money" type="input" class="text"  value="0" size="10">
                            <?php } ?>
                        </td>
                    </tr>  
                    <tr>
                        <th width=260>成交价： </th>
                        <td><?php echo isset($data)?$data['price_deal']:'';?></td>
                    </tr>  
                    <tr>
                        <th width=260>成交价时间： </th>
                        <td><?php echo isset($data)?$data['deal_time']:'';?></td>
                    </tr>  
                    <tr>
                        <th width=260>用户： </th>
                        <td><?php echo isset($data['user'])?$data['user']['lastname']:'';?></td>
                    </tr>  
                    <tr>
                        <th width=260>商品名称： </th>
                        <td><?php echo isset($data)?$data['title']:'';?></td>
                    </tr>
                    <tr>
                        <th width=260>用户感言： </th>
                        <td><?php echo isset($data)?$data['memo']:'';?></td>
                    </tr>
                    <tr>
                        <th width=260>晒宝图片： </th>
                        <td>
                            <?php 
                              if(isset($data['images']) && count($data['images'])){ 
                                foreach($data['images'] as $image){  
                            ?>
                                <div style='float:left;margin:2px;' id="show_img<?php echo $image['id'];?>">
                                    <p><img src="<?php echo $front_domain.'/'.$image['image_path'];?>" width=100 height=80>&nbsp;</p>
                                    <p><a href='javascript:;' onclick="delete_show_img('<?php echo $image['id'];?>');"> 删 除 </a></p>
                                </div>
                            <?php
                                } 
                              } 
                            ?>
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
            
<script type="text/javascript">
function delete_show_img(id){ 
    var url = "/user/user_show/delete_show_img?id="+id;
    $.ajax({
    	url: url,
    	type: 'GET',
    	dataType: 'json',
    	success: function(data, status){
            if(data.code<0){
                alert(data.msg);
            }else{
                $('#show_img'+id).hide();
            }
    	},
    	error: function(){
            alert('err');
    	}
    });
}
</script>