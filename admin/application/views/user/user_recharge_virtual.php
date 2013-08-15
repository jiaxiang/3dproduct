<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li><a href="/user/user/recharge/<?php echo $data['id'];?>">用户充值</a></li>
                <li class="on"><a href="/user/user/recharge_virtual_money/<?php echo $data['id'];?>">竞波币充值</a></li>
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
                             <th>用户名：</th>
                                    <td>
                                    <label><?php echo $data['lastname'];?></label>
                                    </td>
                              </tr>
                                <tr>
                                    <th>邮箱：</th>
                                    <td>
                                    <label><?php echo $data['email'];?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>姓名：</th>
                                    <td>
                                    <label><?php echo $data['lastname'];?></label>
                                    </td>
                                </tr>
                                                                
                                 <tr>
                                    <th>充值金额：</th>
                                    <td>
                                    	<input type="text" size="5" maxlength="10" id="money" name="money" class="text required integer" value=""><span class="required"> *</span>
                                    </td>
                                </tr>          
                                                      
                                 <tr>
                                    <th>备注：</th>
                                    <td>
                                    <input type="text" size="60" id="memo" name="memo" class="text required" value=""><span class="required"> *</span>
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