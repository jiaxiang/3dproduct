<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">录入奖金</li>
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
                             <th>订单号：</th>
                                    <td>
                                    <label><?php echo $order_num;?></label>
                                    </td>
                              </tr>
                                <tr>
                                    <th>用户：</th>
                                    <td>
                                    <label><?php echo $user['lastname'];?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>代码：</th>
                                    <td>
                                    <label><?php echo $codes;?></label>
                                    </td>
                                </tr>
                                                                
                                 <tr>
                                    <th>倍数：</th>
                                    <td>
                                    	<label><?php echo $rate;?></label>
                                    </td>
                                </tr>   
                                
                                 <tr>
                                    <th>金额：</th>
                                    <td>
                                    	<label><?php echo $money;?></label>
                                    </td>
                                </tr> 
                                 <tr>
                                    <th>最后操作人员：</th>
                                    <td>
                                    	<label><?php echo $manager;?></label>
                                    </td>
                                </tr>  
                                 <tr>
                                    <th>打印时间：</th>
                                    <td>
                                    	<label><?php echo $time_print;?></label>
                                    </td>
                                </tr>                                                      

                                
                                 <tr>
                                    <th>彩票号码：</th>
                                    <td>
                                    <input type="text" size="20" maxlength="30" id="num" name="num" class="text required" value=""><span class="required"> *</span>
                                    </td>
                                </tr>                                
                                
                                 <tr>
                                    <th>彩票密码：</th>
                                    <td>
                                    <input type="text" size="5" maxlength="10" id="password" name="password" class="text required" value=""><span class="required"> *</span>
                                    </td>
                                </tr>   
                                
                                
                                 <tr>
                                    <th>奖金：</th>
                                    <td>
                                    <input type="text" size="5" maxlength="8" id="money" name="money" class="text required"
                                    onkeyup="this.value=this.value.replace(/^\D*(\d*(?:\.\d{0,2})?).*$/g, '$1');" onfocus="this.value='';" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/^\D*(\d*(?:\.\d{0,2})?).*$/g, '$1'));"
                                     value=""><span class="required"> *</span>
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