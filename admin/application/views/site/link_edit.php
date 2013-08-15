<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑站点友情链接</li>
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
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base().url::current();?>">
                <div class="edit_area">
                    <div class="division">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>

                                <tr>
                                    <th width="15%">网站名称:</th>
                                    <td>
                                        <input type="text" size="10" name="name" class="text t400  _x_ipt required" value="<?php isset($data) && print($data['name']); ?>"/><span class="required"> *</span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>URL:</th>
                                    <td>
                                        <input type="text" size="10" name="url" class="text t400  _x_ipt url required" value="<?php isset($data) && print($data['url']); ?>"/><span class="required"> *</span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>EMAIL:</th>
                                    <td>
                                        <input type="text" size="10" name="email" class="text t400  _x_ipt email" value="<?php isset($data) && print($data['email']); ?>"/> 
                                    </td>
                                </tr>
                                <tr>
                                    <th>LOGO:</th>
                                    <td>
                                        <input type="text" size="10" name="logo" class="text t400  _x_ipt" value="<?php isset($data) && print($data['logo']); ?>"/> 
                                    </td>
                                </tr>
                                <tr>
                                    <th>标签:</th>
                                    <td>
                                        <input type="text" size="10" name="title" class="text t400  _x_ipt" value="<?php isset($data) && print($data['title']); ?>" />
                                    </td>
                                </tr>

                                <tr>
                                    <th>打开方式:</th>
                                    <td>
                                        <input type="radio" <?php if(isset($data) && $data['target']) echo 'checked'; ?> value="1" name="target">本页面打开 &nbsp;&nbsp;
                                        <input type="radio" <?php if(!isset($data) || !$data['target']) echo 'checked'; ?> value="0" name="target">新页面打开
                                    </td>
                                </tr>

                                <tr>
                                    <th>前台显示:</th>
                                    <td>
                                        <input type="radio" <?php if(isset($data) && $data['status']) echo 'checked'; ?> value="1" name="status">显示 &nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" <?php if(!isset($data) || !$data['status']) echo 'checked'; ?> value="0" name="status">不显示 
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <input name="submit" type="submit" class="ui-button" value=" 保存 " />
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
