<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#theme_single_config_form").validate();
    });
</script>
<form name="theme_single_config_form" id="theme_single_config_form" action="/site/config/theme_single_config/?key=<?php echo $key;?>" method="POST" enctype="multipart/form-data">
    <div class="dialog_box">
        <div class="headContent">
            <table cellspacing="0" cellpadding="0" border="0" width="100%" class="actionBar mainHead">
                <tbody>
                    <tr>
                        <td><span class="notice_inline">1 选择规格选项 » 2 添加需要的规格值 » 3保存</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="body dialogContent">
            <!-- tips of pdtattrset_set_tips  -->
            <div id="gEditor-sepc-panel">
                <div class="out_box">
                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tbody>
                            <tr>
                                <th width="20%">标题：</th>
                                <td>
                                    <?php echo $data['name'];?>
                                    <input type="hidden" value="<?php echo $data['name'];?>" name="config_name" size="30" class="text">
                                </td>
                            </tr>
                            <tr>
                                <th width="20%">标记：</th>
                                <td><?php echo $key;?></td>
                            </tr>
                            <tr>
                                <th width="20%">类型：</th>
                                <td>
                                    <input type="hidden" value="<?php echo $data['type'];?>" name="config_type_value">
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
                                </td>
                            </tr>
                            <tr>
                                <th width="20%">内容：</th>
                                <td>
                                    <?php if($data['type'] == 2):?>
                                    <input type="file" name="img_val" size="20"><span style="display:block;color:#666666">图片尺寸：小于 1MB;可用扩展名：gif,png,jpg.</span>
                                    <?php else:?>
                                    <input type="text" value="<?php echo $data['val'];?>" name="config_val" size="50" maxlength="255" class="text required">
                                    <span class="required">*</span>
                                    <?php endif;?>
                                </td>
                            </tr>
                            <?php if($data['type'] == 2):?>
                            <tr>
                                <th width="20%">图片链接：</th>
                                <td><input type="text" value="<?php echo isset($data['description']['url'])?$data['description']['url']:'';?>" name="img_url" size="30" class="text valid" maxlength="255"><span class="notice_inline">填写站点内链接，不需要带域名。</span></td>
                            </tr>
                            <tr>
                                <th width="20%">图片Alt标签：</th>
                                <td><input type="text" value="<?php echo isset($data['description']['alt'])?$data['description']['alt']:'';?>" name="img_alt" size="30" class="text valid" maxlength="255"></td>
                            </tr>
                            <?php endif;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="list_save">
        <input name="create_all_goods" type="submit" class="ui-button" value=" 保存信息 "/>
        <input name="cancel" id="cancel_btn" type="button" class="ui-button" value=" 取消 " onclick='$("#theme_edit_area").dialog("close");'/>
        <input type="hidden" name="submit_target" id="submit_target" value="0" />
    </div>
</form>
<link type="text/css" href="<?php echo url::base();?>css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo url::base();?>js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
    $(function(){
        $('.attribute_single_img').each(function(){
            $(this).fancybox();
        });
        /* 按钮风格 */
        $(".ui-button-small,.ui-button").button();
    });
</script>