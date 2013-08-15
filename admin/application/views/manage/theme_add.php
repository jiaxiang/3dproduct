<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加主题</li>
            </ul>
            <span class="fright">
                <?php if(site::id()>0):?>
                当前站点：<a href="http://<?php echo site::domain();?>" target="_blank" title="点击访问"><?php echo site::domain();?></a> [ <a href="<?php echo url::base();?>manage/site/out">返回</a> ]
                <?php endif;?>
            </span>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**productlist edit start**-->
            <div class="edit_area">
                <form id="add_form" name="add_form" method="post" action="/<?php echo url::current(TRUE);?>">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="25%">
                                        主题ID：
                                    </th>
                                    <td>
                                        <input size="10" name="id" class="text required"/><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        主题名称：
                                    </th>
                                    <td>
                                        <input size="30" name="name" class="text required"/><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        主题等级：
                                    </th>
                                    <td>
                                        <select name="grade" class="text required">
                                            <option value="1"> 等级1 </option>
                                            <option value="2"> 等级2 </option>
                                            <option value="3"> 等级3 </option>
                                            <option value="4"> 等级4 </option>
                                            <option value="5"> 等级5 </option>
                                            <option value="6"> 等级6 </option>
                                            <option value="7"> 等级7 </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>说明:</th>
                                    <td class="d_line">
                                        <textarea name="brief" cols="75" rows="5" class="text" type="textarea"  maxlength="255"></textarea>
                                        <span class="brief-input-state notice_inline">简短的菜单功能介绍，请不要超过255字节。</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="list_save">
                        <input name="submit" type="submit" class="ui-button" value=" 确认添加 ">
                    </div>
                </form>
            </div>
            <!--**productlist edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
    	$("#add_form").validate({
            errorPlacement:function(error, element){
                if(element.attr("name") == "brief"){
                    //alert(error);
                    error.appendTo( element.parent());
                }else{
                    error.insertAfter(element)
                }
            }
        });
    });
</script>
