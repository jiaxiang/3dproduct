<?php defined('SYSPATH') OR die('No direct access allowed.');?>

<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <div class="public_crumb">
                <p><a href="/">后台首页</a> 》<a href="/site/seo">编辑块配置</a></p>
            </div>
            <!--**productlist edit start**-->

            <div class="edit_area">

                <form id="add_form" name="add_form" method="post" action="<?php echo url::current();?>">
                    <div class="division">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>

                                <tr>
                                    <th>title:</th>
                                    <td>
                                        <input type="text" size="10" name="title" id="title" class="text t400  _x_ipt" value=" "/>
                                    </td>
                                </tr>

                                <tr>
                                    <th>description:</th>
                                    <td>
                                        <textarea name="description" id="description" cols="75" rows="20" class="text _x_ipt t400" type="textarea" maxth="255" ></textarea>
                                    </td>
                                </tr>

                                <tr>
                                    <th>keywords:</th>
                                    <td>
                                        <input type="text" size="10" name="keywords" id="keywords" class="text t400  _x_ipt" value=""/>
                                    </td>
                                </tr>

                                <tr>
                                    <th>index_title:</th>
                                    <td>
                                        <input type="text" size="10" name="index_title" id="index_title" class="text t400  _x_ipt" value=""/>
                                    </td>
                                </tr>

                                <tr>
                                    <th>index_description:</th>
                                    <td>
                                        <textarea name="index_description" id="index_description" cols="75" rows="20" class="text _x_ipt t400" type="textarea" maxth="255" ></textarea>
                                    </td>
                                </tr>

                                <tr>
                                    <th>index_keywords:</th>
                                    <td>
                                        <input type="text" size="10" name="index_keywords" id="index_keywords" class="text t400  _x_ipt" value=""/>
                                    </td>
                                </tr>

                                <tr>
                                    <th>seowords:</th>
                                    <td>
                                        <input type="text" size="10" name="seowords" id="seowords" class="text t400  _x_ipt" value=""/>
                                    </td>
                                </tr>

                                <tr>
                                    <th>stat_code:</th>
                                    <td>
                                        <textarea name="stat_code" id="stat_code" cols="75" rows="20" class="text _x_ipt t400" type="textarea" maxth="255" ></textarea>
                                    </td>
                                </tr>

                                <tr>
                                    <th>pay_code:</th>
                                    <td>
                                        <textarea name="pay_code" id="pay_code" cols="75" rows="20" class="text _x_ipt t400" type="textarea" maxth="255" ></textarea>
                                    </td>
                                </tr>

                                <tr>
                                    <th>站点:</th>
                                    <td>
                                        <a href="http://www.google.com">www.google.com</a>
                                    </td>
                                </tr>

                                <tr>
                                    <th>添加时间:</th>
                                    <td>2008-10-12</td>
                                </tr>

                                <tr>
                                    <th>更新时间: </th>
                                    <td>2008-10-12</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="btn_eidt">
                        <table width="445" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <th width="152"></th>
                                <td width="293">
                                    <input name="Input" type="submit" class="ui-button" value="保存">
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>

            </div>
            <!--**productlist edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<!--**footer start**-->
<div id="footer">
</div>
<!--**footer end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate();
    });
</script>