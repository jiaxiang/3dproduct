<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate();   
    });
</script>
<!-- header_content -->
<div class="new_sub_menu_con">
    <div class="newgrid_tab fixfloat">
        <ul>
            <li class="on">模板配置信息</li>
        </ul>
    </div>
    <div class="newgrid_top">
        <ul class="pro_oper">
            <li>
                <a href="/manage/theme/config/<?php echo $id;?>" title="添加"><span class="add_pro">查看配置</span></a>
            </li>
        </ul>
    </div>  
</div>
<!-- header_content(end) -->

<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**edit start**-->
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current(TRUE);?>" enctype="multipart/form-data">
                <div class="edit_area">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th>标题：</th>
                                    <td>
                                        <input type="text" class="text required" size="50" name="config_name" value="<?php echo $data['name'];?>">
                                        <span class="required">*</span>
                                        <span class="notice_inline">配置标题</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>标记：</th>
                                    <td>
                                        <?php echo $data['key'];?>
                                        <input type="hidden" value="<?php echo $data['key'];?>" name="config_flag">
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">类型：</th>
                                    <td>
                                    <?php
                                    switch ($data['type']) {
                                        case 1:
                                            echo "文本";
                                            break;
                                        case 2:
                                            echo "图片";
                                            break;
                                        case 3:
                                            echo "链接";
                                            break;
                                    }
                                    ?>
                                        <input type="hidden" name="config_type_value" value="<?php echo $data['type'];?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                        <span id="config_title">内容：</span>
                                    </th>
                                    <td>
                                        <?php if($data['type'] == 2):?>
                                        <img src="/att/theme/theme<?php echo $id;?><?php echo strtr($data['key'], array('_'=>''));?>_120_90.jpg" alt="<?php echo $data['name'];?>"/>
                                        <br/>
                                        <input type="file" class="text val" size="60" name="img_val" id="val_2"><span class="notice_inline">图片尺寸：小于 1MB;可用扩展名：gif,png,jpg.</span>
                                        <?php else:?>
                                        <input type="text" class="text val" size="60" name="text_val" id="val_1" value="<?php echo $data['val'];?>">
                                        <span class="required">*</span>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <?php if($data['type'] == 2):?>
                                <tr class="image_url_row">
                                    <th width="20%">
                                        <span id="config_title">图片链接：</span>
                                    </th>
                                    <td>
                                        <input type="text" class="text" size="60" name="img_url" value="<?php echo (isset($data['description']['url']))?$data['description']['url']:'';?>"><span class="notice_inline">填写站点内链接，不需要带域名。</span>
                                    </td>
                                </tr>
                                <tr class="image_url_row">
                                    <th width="20%">
                                        <span id="config_title">图片Alt标签：</span>
                                    </th>
                                    <td>
                                        <input type="text" class="text" size="50" name="img_alt" value="<?php echo (isset($data['description']['alt']))?$data['description']['alt']:'';?>">
                                    </td>
                                </tr>
                                <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                     <input name="submit" type="submit" class="ui-button" value=" 确认修改 ">
                </div>
            </form>
            <!--**edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->