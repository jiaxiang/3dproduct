<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑权限资源</li>
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
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="25%"> 资源名称： </th>
                                    <td>
                                        <input size="30" name="name" class="text required" value="<?php echo $data['name'];?>" /><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th> 资源标记： </th>
                                    <td>
                                        <input size="50" name="resource" class="text required" value="<?php echo $data['resource'];?>"><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>上级：</th>
                                    <td>
                                        <select name="parent_id" class="text">
                                            <option value="0"> - - </option>
                                            <?php
                                            foreach($actions as $key=>$value):
                                                ?>
                                            <option value="<?php echo $value['id'];?>" <?php echo ($data['parent_id'] == $value['id'])?'selected':'';?>>
                                                    <?php for($i=1;$i<$value['level_depth'];$i++) {?>
                                                &#166;&nbsp;
                                                        <?php } ?>
                                                    <?php echo $value['name'];?></option>
                                            <?php
                                            endforeach;
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th> 排序： </th>
                                    <td>
                                        <input size="10" name="order" class="text" value="<?php echo $data['order'];?>">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <input name="submit" type="submit" class="ui-button" value=" 保存 ">
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