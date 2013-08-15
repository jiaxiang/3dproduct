<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate();
        $('#theme_edit_area').dialog({
            autoOpen: false,
            bgiframe: true,
            width: 760,
            maxHeight: 500,
            modal: true,
            position: ['center',50]
        });
        $('.edit_btn').each(function(){
            var current_key = $(this).attr('id');
            $(this).click(function(){
                var reqUrl = '/site/config/theme_single_config/?key='+current_key;
                $("#theme_edit_area").html('loading...');
                $.ajax({
            		url: reqUrl,
                    type: 'GET',
                    dataType: 'json',
                    error: function() {
                        window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
                    },
                    success: function(retdat, status) {
        				ajax_block.close();
        				if (retdat['code'] == 200 && retdat['status'] == 1) {
        					$("#theme_edit_area").html(retdat['content']);
        				} else {
        					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
        				}
        			}
            	});
                $("#theme_edit_area").dialog('open');
            });
        });
    });
</script>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">模板配置信息</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**edit start**-->
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current();?>" enctype="multipart/form-data">
                <div class="edit_area">
                    <div class="division">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <?php if(count($vals) > 0):?>
                                <?php foreach($vals as $key=>$value):?>
                                <tr>
                                    <th width="20%">
                                        [<a title="<?php echo $names[$key];?>" class="edit_btn" href="javascript:void(0);" id="<?php echo $key;?>">编辑</a>]
                                            <?php echo $names[$key];?>：
                                    </th>
                                    <td>
                                            <?php if ($types[$key] == 2) {?>
                                                <?php if(isset($site_theme_configs['val'][$key])) {?>
                                        <img src="/att/theme/theme<?php echo $id;?><?php echo strtr($key, array('_'=>''));?>_120_90.jpg" alt="<?php echo $names[$key];?>" maxheight="100"/>
                                        <br/>图片链接：<?php echo (isset($descriptions[$key]['url']))?$descriptions[$key]['url']:'';?>
                                        <br/>图片ALT：<?php echo (isset($descriptions[$key]['alt']))?$descriptions[$key]['alt']:'';?>
                                        <input type="hidden" name="key" value="<?php echo $key; ?>" />
                                                    <?php }else { ?>
                                        <img src="/img/theme_image/<?php echo $id;?>/<?php echo $key;?>_<?php echo $value;?>" alt="<?php echo $names[$key];?>" maxheight="100"/>
                                        <br/>图片链接：<?php echo (isset($descriptions[$key]['url']))?$descriptions[$key]['url']:'';?>
                                        <br/>图片ALT：<?php echo (isset($descriptions[$key]['alt']))?$descriptions[$key]['alt']:'';?>
                                        <input type="hidden" name="key" value="<?php echo $key; ?>" />
                                                    <?php } ?>
                                        <!--<input type="file" name="img" size="60"/>-->
                                                <?php } else { ?>
                                        <?php echo $value;?>
                                                <?php }?>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                                <?php else:?>
                                <tr><td colspan="2">当前模板无配置标签。</td></tr>
                                <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
            <!--**edit end**-->
        </div>
    </div>
</div>
<div id="theme_edit_area" title="配置站点信息">test</div>
<!--**content end**-->