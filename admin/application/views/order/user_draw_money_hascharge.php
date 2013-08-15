<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">设为打款成功</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="newgrid">
            <!--** edit start**-->
            <div class="out_box">
                <form id="add_form" name="add_form" method="post"  action="<?php echo url::base() . url::current();?>">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                           	 <tr>
                             <th>姓名：</th>
                                    <td>
                                    <label><?php echo $truename;?></label>
                                    </td>
                              </tr>
                                <tr>
                                    <th>银行帐号：</th>
                                    <td>
                                    <label><?php echo $account;?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>提款银行：</th>
                                    <td>
                                    <label><?php echo $bank_name;?>     </label>
                                    </td>
                                </tr>
                                                                
                                 <tr>
                                    <th>省份/城市/支行名称：</th>
                                    <td>
                                    	<label><?php echo $bank_name;?>/<?php echo $province;?>/<?php echo $city;?> </label>
                                    </td>
                                </tr>   
                                
                                 <tr>
                                    <th>提款金额：</th>
                                    <td>
                                    	<label><?php echo $money;?></label>
                                    </td>
                                </tr>
                                
                                  <tr>
                                    <th>备注：</th>
                                    <td>
                                    	<label><?php echo nl2br($memo);?></label>
                                    </td>
                                </tr>                                                        

                                 <tr>
                                    <th>流水号：</th>
                                    <td>
                                    <input type="text" size="20" maxlength="30" id="serialnumber" name="serialnumber" class="text required" value=""><span class="required"> *</span>
                                    </td>
                                </tr>                                
                                                        
                                  <tr>
                                    <th>更多信息：</th>
                                    <td><textarea name="memo" cols="30" rows="5" class="text required" id="memo"></textarea>                                        <span class="required"> *</span>
                                    </td>
                                </tr>                                   
                                
                            </tbody>
                        </table>
                    </div>
                    <div class="list_save">
                        <input name="submit" type="submit" class="ui-button" value=" 提交 ">
                    </div>
                    <div class="clear">&nbsp;</div>
                </form>
            </div>
            <!--** edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<div id='example' style="display:none;"></div>
<div id='address' style="display:none;"></div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate({});
    });
</script>