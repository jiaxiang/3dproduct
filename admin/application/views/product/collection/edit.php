<?php defined('SYSPATH') OR die('No direct access allowed.');
/* 返回的主体数据 */
$return_data = $return_struct['content'];
$data = $return_data['data'];
?>
<script type="text/javascript" src="<?php echo url::base();?>js/jq/plugins/tinymce/tiny_mce.js"></script>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">修改商品专题</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <form id="edit_form" name="edit_form" method="POST" action="<?php echo url::base();?>product/collection/post">
            <div class="out_box" id="tabs">
                <div class="tableform" id="tabs-1">
                        <div class="division">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th>* 名称： </th>
                                        <td>
                                        <input name="id" id="id" type="hidden" value="<?php echo $data['id'];?>">
                                        <input id="title" name="title" type="input" class="text required"  value="<?php echo $data['title'];?>" size="50">
                                        </td>
                                    </tr>
                                     <tr>
                                        <th>标识： </th>
                                        <td><input id="label" name="label" type="input" class="text"  value="<?php echo $data['label'];?>" size="50"></td>
                                    </tr>
                                    <tr>
                                    <th>Meta Title(页面标题): </th>
                                    <td><input type="text" style="" size="10" name="meta_title" class="text t400  _x_ipt" value="<?php echo $data['meta_title'];?>"/></td>
                                </tr>
                                <tr>
                                    <th>Meta Keywords(页面关键字): </th>
                                    <td><input type="text" style="" size="10" name="meta_keywords" class="text t400  _x_ipt" value="<?php echo $data['meta_keywords'];?>"/></td>
                                </tr>
                                <tr>
                                    <th>Meta Descriptions(页面描述):</th>
                                    <td><textarea name="meta_description" cols="75" rows="5" class="text _x_ipt t400" type="textarea" maxlength="255" ><?php echo $data['meta_description'];?></textarea>
                                        <span class="brief-input-state notice_inline">简短的商品介绍，请不要超过255字节。</span></td>
                                </tr>
                                <tr>
                                    <th>前台说明：</th>
                                    <td><textarea name="description" cols="75" rows="5" class="text _x_ipt t400" type="textarea" maxlength="1024" ><?php echo $data['description'];?></textarea>
                                        <span class="brief-input-state notice_inline">简短的商品介绍，请不要超过1024字节。</span></td>
                                </tr>
                                <tr>
                                    <th>后台说明：</th>
                                    <td><textarea name="memo" cols="75" rows="5" class="text _x_ipt t400" type="textarea" maxlength="255" ><?php echo $data['memo'];?></textarea>
                                        <span class="brief-input-state notice_inline">简短的商品介绍，请不要超过255字节。</span></td>
                                </tr>
                                </tbody>
                            </table>
                        </div> 
                </div>
                 <div style="text-align:center;">
                 	<input name="dosubmit" type="submit" class="ui-button" value=" 编辑 " />
                 </div>
            </div>
            </form>
        </div>
        <!--**productlist edit end**-->
    </div>
</div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#edit_form").validate();
    });
</script>