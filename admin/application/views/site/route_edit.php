<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">更新站点固定链接</li>
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
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current(TRUE);?>">
                <div class="edit_area">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="15%">商品及分类链接类型:</th>
                                    <td>
                                        <input type="radio" name="type" value="0" <?php if ($data['type'] == 0) echo 'checked="checked"'; ?> <?php if($is_modify<>1) echo 'disabled="disabled"';?>/>类型1(通过商品ID)<br/>
                                        <span class="notice_inline">
                                            商品页链接:http://www.example.com/商品标识/商品ID+商品页后缀<br/>
                                        </span>
                                        <span class="notice_inline">
                                            分类页面链接:http://www.example.com/分类标识/分类ID+分类页后缀<br/>
                                        </span>
                                        <input type="radio" name="type" value="1" <?php if ($data['type'] == 1) echo 'checked="checked"'; ?> <?php if($is_modify<>1) echo 'disabled="disabled"';?>/>类型2(通过商品URL)<br/>
                                        <span class="notice_inline">
                                            商品页链接:http://www.example.com/商品标识/商品URL+商品页后缀<br/>
                                        </span>
                                        <span class="notice_inline">
                                            分类页面链接:http://www.example.com/分类标识/分类URL+分类后缀<br/>
                                        </span>
                                        <input type="radio" name="type" value="2" <?php if ($data['type'] == 2) echo 'checked="checked"'; ?> <?php if($is_modify<>1) echo 'disabled="disabled"';?>/>类型3(通过分类URL和商品URL)<br/>
                                        <span class="notice_inline">
                                            商品页链接:http://www.example.com/分类URL/商品URL+商品页后缀<br/>
                                        </span>
                                        <span class="notice_inline">
                                            分类页面链接:http://www.example.com/分类URL+分类页后缀<br/>
                                        </span>
                                        <input type="radio" name="type" value="3" <?php if ($data['type'] == 3) echo 'checked="checked"'; ?> <?php if($is_modify<>1) echo 'disabled="disabled"';?>/>类型4(通过多级分类URL和商品URL)<br/>
                                        <span class="notice_inline">
                                            商品页链接:http://www.example.com/一级分类URL/二级分类URL/.../商品URL+商品页后缀<br/>
                                        </span>
                                        <span class="notice_inline">
                                            分类页面链接:http://www.example.com/一级分类URL/二级分类URL/.../分类URL<br/>
                                        </span>
                                        <input type="radio" name="type" value="4" <?php if ($data['type'] == 4) echo 'checked="checked"'; ?> <?php if($is_modify<>1) echo 'disabled="disabled"';?>/>类型5(通过商品URL)<br/>
                                        <span class="notice_inline">
                                            商品页链接:http://www.example.com/商品标识/商品URL+商品页后缀<br/>
                                        </span>
                                        <span class="notice_inline">
                                            分类页面链接:http://www.example.com/分类URL+分类后缀<br/>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <h3 class="title1_h3">标识只能是字母数字和下划线，不能有空格和特殊字符，不同页面标识不要相同否则页面不能显示！</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <th>会员登录<span class="required"> *</span>：</th>
                                    <td>
                                        名称：<input type="text" size="30" name="login_name" class="text required" value="<?php echo (empty($data['login_name']))?'login':$data['login_name'];?>"/>
                                        - 标识：<input type="text" size="20" name="login" class="text required" value="<?php echo (empty($data['login']))?'Login':$data['login'];?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>会员退出<span class="required"> *</span>：</th>
                                    <td>
                                        名称：<input type="text" size="30" name="logout_name" class="text required" value="<?php echo (empty($data['logout_name']))?'Logout':$data['logout_name'];?>"/>
                                        - 标识：<input type="text" size="20" name="logout" class="text required" value="<?php echo (empty($data['logout']))?'logout':$data['logout'];?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>会员注册<span class="required"> *</span>：</th>
                                    <td>
                                        名称：<input type="text" size="30" name="register_name" class="text required" value="<?php echo (empty($data['register_name']))?'Register':$data['register_name'];?>"/>
                                        - 标识：<input type="text" size="20" name="register" class="text required" value="<?php echo (empty($data['register']))?'register':$data['register'];?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>购物车<span class="required"> *</span>：</th>
                                    <td>
                                        名称：<input type="text" size="30" name="cart_name" class="text required" value="<?php echo (empty($data['cart_name']))?'Cart':$data['cart_name'];?>"/>
                                        - 标识：<input type="text" size="20" name="cart" class="text required" value="<?php echo (empty($data['cart']))?'cart':$data['cart'];?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>找回密码<span class="required"> *</span>：</th>
                                    <td>
                                        名称：<input type="text" size="30" name="find_password_name" class="text required" value="<?php echo (empty($data['find_password_name']))?'Forget your password':$data['find_password_name'];?>"/>
                                        - 标识：<input type="text" size="20" name="find_password" class="text required" value="<?php echo (empty($data['find_password']))?'find_password':$data['find_password'];?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>找回密码邮件中修改密码<span class="required"> *</span>：</th>
                                    <td>
                                        名称：<input type="text" size="30" name="get_password_name" class="text required" value="<?php echo (empty($data['get_password_name']))?'Get Password':$data['get_password_name'];?>"/>
                                        - 标识：<input type="text" size="20" name="get_password" class="text required" value="<?php echo (empty($data['get_password']))?'get_password':$data['get_password'];?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>会员基本信息修改<span class="required"> *</span>：</th>
                                    <td>
                                        名称：<input type="text" size="30" name="profile_name" class="text required" value="<?php echo (empty($data['profile_name']))?'Profile':$data['profile_name'];?>"/>
                                        - 标识：<input type="text" size="20" name="profile" class="text required" value="<?php echo (empty($data['profile']))?'profile':$data['profile'];?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Wishlists<span class="required"> *</span>:</th>
                                    <td>
                                        名称：<input type="text" size="30" name="wishlists_name" class="text required" value="<?php echo (empty($data['wishlists_name']))?'Wishlists':$data['wishlists_name'];?>"/>
                                        - 标识：<input type="text" size="20" name="wishlists" class="text required" value="<?php echo (empty($data['wishlists']))?'wishlists':$data['wishlists'];?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>会员地址信息<span class="required"> *</span>：</th>
                                    <td>
                                        名称：<input type="text" size="30" name="addresses_name" class="text required" value="<?php echo (empty($data['addresses_name']))?'Address':$data['addresses_name'];?>"/>
                                        - 标识：<input type="text" size="20" name="addresses" class="text required" value="<?php echo (empty($data['addresses']))?'addresses':$data['addresses'];?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>会员更改密码<span class="required"> *</span>：</th>
                                    <td>
                                        名称：<input type="text" size="30" name="password_name" class="text required" value="<?php echo (empty($data['password_name']))?'Password':$data['password_name'];?>"/>
                                        - 标识：<input type="text" size="20" name="password" class="text required" value="<?php echo (empty($data['password']))?'password':$data['password'];?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>订单<span class="required"> *</span>：</th>
                                    <td>
                                        名称：<input type="text" size="30" name="orders_name" class="text required" value="<?php echo (empty($data['orders_name']))?'Orders':$data['orders_name'];?>"/>
                                        - 标识：<input type="text" size="20" name="orders" class="text required" value="<?php echo (empty($data['orders']))?'orders':$data['orders'];?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>分类<span class="required"> *</span>：</th>
                                    <td>
                                        名称：<input type="text" size="30" name="category_name" class="text required" value="<?php echo (empty($data['category_name']))?'Category':$data['category_name'];?>"/>
                                        - 标识：<input type="text" size="20" name="category" class="text required" value="<?php echo (empty($data['category']))?'category':$data['category'];?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>产品<span class="required"> *</span>：</th>
                                    <td>
                                        名称：<input type="text" size="30" name="product_name" class="text required" value="<?php echo (empty($data['product_name']))?'Product':$data['product_name'];?>"/>
                                        - 标识：<input type="text" size="20" name="product" class="text required" value="<?php echo (empty($data['product']))?'product':$data['product'];?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>促销<span class="required"> *</span>：</th>
                                    <td>
                                        名称：<input type="text" size="30" name="promotion_name" class="text required" value="<?php echo (empty($data['promotion_name']))?'Promotion':$data['promotion_name'];?>"/>
                                        - 标识：<input type="text" size="20" name="promotion" class="text" value="<?php echo (empty($data['promotion']))?'promotion':$data['promotion'];?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>FAQ<span class="required"> *</span>：</th>
                                    <td>
                                        名称：<input type="text" size="30" name="faq_name" class="text required" value="<?php echo (empty($data['faq_name']))?'FAQ':$data['faq_name'];?>"/>
                                        - 标识：<input type="text" size="20" name="faq" class="text required" value="<?php echo (empty($data['faq']))?'faq':$data['faq'];?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>联系我们<span class="required"> *</span>：</th>
                                    <td>
                                        名称：<input type="text" size="30" name="contact_us_name" class="text required" value="<?php echo (empty($data['contact_us_name']))?'Contact US':$data['contact_us_name'];?>"/>
                                        - 标识：<input type="text" size="20" name="contact_us" class="text required" value="<?php echo (empty($data['contact_us']))?'contact_us':$data['contact_us'];?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>会员中心<span class="required"> *</span>：</th>
                                    <td>
                                        标识：<input type="text" size="50" name="user" class="text required" value="<?php echo (empty($data['user']))?'user':$data['user'];?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>分类后缀：</th>
                                    <td>
                                        <input type="text" size="50" name="category_suffix" class="text" value="<?php echo $data['category_suffix']; ?>"/>
                                        如：.html,.php等
                                    </td>
                                </tr>
                                <tr>
                                    <th>产品后缀：</th>
                                    <td>
                                        <input type="text" size="50" name="product_suffix" class="text" value="<?php echo $data['product_suffix']; ?>"/>
                                        如：.html,.php等
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <input type="button" name="button" class="ui-button" value="保存"  onclick="submit_form(1);"/>
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
        $("#add_form").validate();
    });
</script>
