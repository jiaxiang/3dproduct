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
                <a href="/manage/theme/config_add/<?php echo $id;?>" title="添加"><span class="add_pro">增加配置</span></a>
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
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current(TRUE);?>">
                <div class="edit_area">
                    <div class="division">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <?php if(count($vals) > 0):?>
                                    <?php foreach ($vals as $key=>$value):?>
                                <tr>
                                    <th width="20%">

                                        [<a href="/manage/theme/config_edit/<?php echo $id;?>?key=<?php echo $key;?>">编辑</a> |
                                        <a href="/manage/theme/config_delete/<?php echo $id;?>?key=<?php echo $key;?>" onclick="return confirm('确认删除？');">删除</a>]
                                                <?php echo $names[$key];?>
                                    </th>
                                    <td>
                                                <?php if ($types[$key] == 2) {?>
                                        <a href="javascript:;" onclick="javascript:window.open('/att/theme/theme<?php echo $id;?><?php echo strtr($key, array('_'=>''));?>.jpg','_blank','resizable=yes,scroll=yes');">    
                                        <img src="/att/theme/theme<?php echo $id;?><?php echo strtr($key, array('_'=>''));?>.jpg" width=160 alt="<?php echo $names[$key];?>"/></a>
                                        <br/>图片链接：<?php echo (isset($descriptions[$key]['url']))?$descriptions[$key]['url']:'';?>
                                        <br/>图片ALT：<?php echo (isset($descriptions[$key]['alt']))?$descriptions[$key]['alt']:'';?>
                                                    <?php } else { ?>
                                                    <?php echo $value;?>
                                                    <?php }?>
                                    </td>
                                </tr>
                                    <?php endforeach;?>
                                <?php else:?>
                                <tr><td colspan="2">当前模板无配置标签。<a href="/manage/theme/config_add/<?php echo $id;?>">添加配置</a></td></tr>
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
<!--**content end**-->