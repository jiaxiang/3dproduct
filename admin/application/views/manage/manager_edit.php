<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑帐号信息</li>
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
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current();?>">
                <div class="edit_area">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="25%">用户名：</th>
                                    <td><input size="30" name="username" class="text required" maxlength="100"  value="<?php echo $data['username'];?>" /><span class="required"> *</span></td>
                                </tr>
                                <tr>
                                    <th>邮箱：</th>
                                    <td>
                                        <input size="40" name="email" class="text required email" maxlength="320" value="<?php echo $data['email'];?>"><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>密码：</th>
                                    <td>
                                        <input size="30" id="password1" name="password1" type="password" class="text"  maxlength="50" minlength="6" value="">
                                        留空不修改密码
                                    </td>
                                </tr>
                                <tr>
                                    <th>重复密码：</th>
                                    <td>
                                        <input size="30" id="password2" name="password2" type="password" class="text" maxlength="50" minlength="6" value="">
                                    </td>
                                </tr>
                                <tr>
                                    <th>公司名：</th>
                                    <td>
                                        <input size="50" name="name" class="text required" maxlength="250" value="<?php echo $data['name'];?>"><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>选择帐户权限模板: </th>
                                    <td>
                                        <select name="role_id" class="text">
                                            <option value=""> 单独分配权限 </option>
                                            <?php
                                            foreach ($roles as $key=>$value):
                                                echo '<option value="' . $value['id'] . '" ' . $value['selected'] . '> ' . $value['name'] . ' </option>';
                                            endforeach;
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>可管理站点数量：</th>
                                    <td>
                                        <select name="site_num" class="text">
                                            <?php for ($i = 1;$i < 100;$i++):?>
                                            <option value="<?php echo $i;?>" <?php echo ($data['site_num'] == $i)?"selected":"";?>><?php echo $i;?></option>
                                            <?php endfor;?>
                                        </select>
                                        个
                                    </td>
                                </tr>
                                <tr>
                                    <th>联系人姓名：</th>
                                    <td>
                                        <input size="40" name="contact_name" class="text" maxlength="320" value="<?php echo $data['contact_name'];?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>电话：</th>
                                    <td>
                                        <input size="40" name="phone" class="text" maxlength="320" value="<?php echo $data['phone'];?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>传真：</th>
                                    <td>
                                        <input size="40" name="fax" class="text" maxlength="320" value="<?php echo $data['fax'];?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>国家/省/市/区：</th>
                                    <td>
                                        <select name="country" onchange="change_country(this);" class="required text">
                                            <option value="" selected>Select Country...</option>
                                            <option value="US">United   States</option>
                                            <option value="GB">United Kingdom</option>
                                            <option value="DE">Germany</option>
                                            <option value="FR">France</option>
                                            <option value="AU">Australia</option>
                                            <option value="AD">Andorra</option>
                                            <option value="AQ">Antarctica</option>
                                            <option value="AG">Antigua And Barbuda</option>
                                            <option value="AR">Argentina</option>
                                            <option value="AU">Australia</option>
                                            <option value="AT">Austria</option>
                                            <option value="BH">Bahrain</option>
                                            <option value="BY">Belarus</option>
                                            <option value="BE">Belgium</option>
                                            <option value="BM">Bermuda</option>
                                            <option value="BO">Bolivia</option>
                                            <option value="BV">Bouvet Island</option>
                                            <option value="BR">Brazil</option>
                                            <option value="BG">Bulgaria</option>
                                            <option value="CA">Canada</option>
                                            <option value="CL">Chile</option>
                                            <option value="CN" selected>China</option>
                                            <option value="CC">Cocos (Keeling) Islands</option>
                                            <option value="CK">Cook Islands</option>
                                            <option value="CR">Costa Rica</option>
                                            <option value="CY">Cyprus</option>
                                            <option value="CZ">Czech Republic</option>
                                            <option value="DK">Denmark</option>
                                            <option value="FJ">Fiji</option>
                                            <option value="FI">Finland</option>
                                            <option value="FR">France</option>
                                            <option value="GE">Georgia</option>
                                            <option value="DE">Germany</option>
                                            <option value="GR">Greece</option>
                                            <option value="GU">Guam</option>
                                            <option value="GT">Guatemala</option>
                                            <option value="HK">Hong Kong</option>
                                            <option value="HU">Hungary</option>
                                            <option value="IS">Iceland</option>
                                            <option value="IN">India</option>
                                            <option value="IE">Ireland</option>
                                            <option value="IL">Israel</option>
                                            <option value="IT">Italy</option>
                                            <option value="CI">Ivory Coast</option>
                                            <option value="JP">Japan</option>
                                            <option value="KW">Kuwait</option>
                                            <option value="KZ">Kazakhstan</option>
                                            <option value="LA">Lao</option>
                                            <option value="LV">Latvia</option>
                                            <option value="LY">Libyan Arab Jamahiriya</option>
                                            <option value="LI">Liechtenstein</option>
                                            <option value="LT">Lithuania</option>
                                            <option value="LU">Luxembourg</option>
                                            <option value="MO">Macau</option>
                                            <option value="MK">Macedonia</option>
                                            <option value="YT">Mayotte</option>
                                            <option value="MX">Mexico</option>
                                            <option value="MC">Monaco</option>
                                            <option value="MA">Morocco</option>
                                            <option value="NL">Netherlands</option>
                                            <option value="AN">Netherlands Antilles</option>
                                            <option value="NZ">New Zealand</option>
                                            <option value="NO">Norway</option>
                                            <option value="PA">Panama</option>
                                            <option value="PG">Papua New Guinea</option>
                                            <option value="PE">Peru</option>
                                            <option value="PN">Pitcairn</option>
                                            <option value="PL">Poland</option>
                                            <option value="PT">Portugal</option>
                                            <option value="RE">Reunion</option>
                                            <option value="RO">Romania</option>
                                            <option value="RU">Russian Federation</option>
                                            <option value="KN">Saint Kitts And Nevis</option>
                                            <option value="LC">Saint Lucia</option>
                                            <option value="PM">Saint Pierre and Miquelon</option>
                                            <option value="SM">San Marino</option>
                                            <option value="SA">Saudi Arabia</option>
                                            <option value="CS">Serbia And Montenegro</option>
                                            <option value="SG">Singapore</option>
                                            <option value="SI">Slovenia</option>
                                            <option value="ZA">South Africa</option>
                                            <option value="GS">South Georgia</option>
                                            <option value="KR">South Korea</option>
                                            <option value="ES">Spain</option>
                                            <option value="SE">Sweden</option>
                                            <option value="CH">Switzerland</option>
                                            <option value="TW">Taiwan</option>
                                            <option value="TT">Trinidad And Tobago</option>
                                            <option value="TR">Turkey</option>
                                            <option value="TM">Turkmenistan</option>
                                            <option value="TC">Turks And Caicos Islands</option>
                                            <option value="UA">Ukraine</option>
                                            <option value="AE">United Arab Emirates</option>
                                            <option value="GB">United Kingdom</option>
                                            <option value="US">United States</option>
                                            <option value="UZ">Uzbekistan</option>
                                            <option value="VA">Vatican City State</option>
                                            <option value="VE">Venezuela</option>
                                        </select>
                                        <span id="ChinaArea">
                                        </span>
                                        <input size="50" name="state" id="state" class="text" maxlength="255" style="display:none;" value="<?php echo $data['province'];?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>地址：</th>
                                    <td>
                                        <input size="60" name="address required" class="text" maxlength="320" value="<?php echo $data['address'];?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>移动电话：</th>
                                    <td>
                                        <input size="40" name="mobile_phone" class="text" maxlength="320" value="<?php echo $data['mobile_phone'];?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>邮编：</th>
                                    <td>
                                        <input size="20" name="postcode" class="text" maxlength="20" value="<?php echo $data['postcode'];?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>备注:</th>
                                    <td class="d_line">
                                        <textarea name="remark" cols="75" rows="5" class="text" type="textarea" maxlength="255"></textarea>
                                        <span class="brief-input-state notice_inline">备注信息，请不要超过255字节。</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>状态: </th>
                                    <td>
                                        <input name="active" type="radio" value="1" <?php echo ($data['active'] == 1)?"checked":"";?>>
                                        可用
                                        <input type="radio" name="active" value="0" <?php echo ($data['active'] == 0)?"checked":"";?>>
                                        不可用
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <input name="submit" type="submit" class="ui-button" value=" 保存 ">
                    <input type="hidden" name="permission" class="ui-button" value="单独分配权限" onclick='$("#submit_target").val("1");'/>
                    <input type="hidden" name="submit_target" id="submit_target" value="0" />
                </div>
            </form>
            <!--**productlist edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script src="<?php echo url::base();?>js/jquery.provincesCity.js" type="text/javascript"></script>
<script src="<?php echo url::base();?>js/provincesdata.js" type="text/javascript"></script>
<script type="text/javascript">
//zhu add
var obj_role = $("select[name='role_id']");
var obj_permission = $("input[name='permission']");
function aclchk(){
	if(!obj_role.val()){
		obj_permission.attr("disabled",false);
	}else{
		obj_permission.attr("disabled",true);
	}
}
    $(document).ready(function(){
    	aclchk();
		obj_role.change(function(){
			aclchk();
		});
    	$("#add_form").validate({
        	errorPlacement:function(error, element){
	            if(element.attr("name") == "meno"){
	                //alert(error);
	                error.appendTo( element.parent());
	            }else{
	                error.insertAfter(element)
	            }
        	}
        });
        $("#ChinaArea").ProvinceCity();
        $("#country").val(<?php echo '"' . $data['country'] . '"';?>);
        $("#township").val(<?php echo '"' . $data['township'] . '"';?>);
        $("#city").val(<?php echo '"' . $data['city'] . '"';?>);
        $("#province").val(<?php echo '"' . $data['province'] . '"';?>);
    });
    function change_country(Obj){
        var country = $(Obj).val();
        if(country == 'CN'){
            $("#state").hide();
            $("#township").show();
            $("#city").show();
            $("#province").show();
        }else{
            $("#state").show();
            $("#township").hide();
            $("#city").hide();
            $("#province").hide();
        }
    }
</script>