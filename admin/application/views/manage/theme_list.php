
<script type="text/javascript" src="/js/jquery.qtip-1.0.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.theme_image').each(function(){
            var info = $(this).attr("alt");
            //alert(info);
            $(this).qtip({
                content: info,
                show: 'mouseover',
                hide: 'mouseout',
                style: {
                    border: {
                        width: 1,
                        radius: 2,
                        color: 'green'
                    },
                    tip: true,
                    padding: 5,
                    textAlign: 'left',
                    name: 'light' // Style it according to the preset 'cream' style
                },
                position:{
                    adjust:{screen:true},
                    corner: {
                        target: 'topMiddle',
                        tooltip: 'bottomMiddle'
                    }
                }
            });
        });
    });
</script>
<div class="new_content">
    <div class="newgrid">
            <div class="newgrid_tab fixfloat">
                <ul>
                    <li class="on"><a href="/promotion/promotion_activity">模板列表</a></li>
                </ul>
            </div>
            <div class="newgrid_top">
                <ul class="pro_oper">
                    <li>
                        <a href="/manage/theme/add" title="添加"><span class="add_pro">添加新模板</span></a>
                    </li>
                </ul>
            </div>
     
            <!--**productlist edit start**-->
            <div class="tableform clearfix ">
                <div id="all-themes">
                    <div class="templatelist">
                        <?php foreach($data as $item){ ?>
                        <div class="item">
                            <table cellspacing="0" cellpadding="0" border="0">
                                <tbody>
                                    <tr>
                                        <td valign="top" align="center">
                                            <div class="templateimg">
                                                <img class="theme_image" alt="<?php echo $item['name'].'<br>'.$item['brief'];?>" height="180" width="180" src="/att/theme/theme<?php echo $item['id']; ?>_180_180.jpg">
                                                <h5 style="margin: 0pt; padding: 0pt; height: 20px;"><?php echo $item['name']; ?></h5>
                                                <div class="tempatebtn">
                                                    <a class="sysiconBtnNoIcon" href="/manage/theme/set/<?php echo $item['id']; ?>">使用</a>
                                                    <?php echo role::view_check('<a class="sysiconBtnNoIcon" href="/manage/theme/edit/' . $item['id'] . '">编辑</a>', 'theme_config');?>
                                                    <?php echo role::view_check('<a class="sysiconBtnNoIcon" href="/manage/theme/config/' . $item['id'] . '">配置</a>', 'theme_config');?>
                                                    <a target="_blank" class="sysiconBtnNoIcon" href="/manage/theme/view_theme_img/<?php echo $item['id']; ?>">查看</a>
                                                    <?php echo role::view_check('<a class="sysiconBtnNoIcon" onclick="return confirm(\'确认模板？\');" href="/manage/theme/delete/' . $item['id'] . '">删除</a>', 'theme_delete');?>
                                                </div>
                                            </div>
                                            <span style="color:#ccc; font-size: 11px; font-weight: normal;"> 更新时间：<?php echo $item['add_time'];?></span> </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div> 
            <!--**productlist edit end**-->
    </div>
</div>