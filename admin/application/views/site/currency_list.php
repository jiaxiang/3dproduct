<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'site/currency/';?>'>币种列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <?php echo role::view_check('<li><a href="' . url::base() . 'site/currency/add" ><span class="add_pro">添加币种</span></a></li>', 'site_currency_add');?>
                <?php echo role::view_check('<li><a href="javascript:void(0);"><span class="del_pro" id="batch_delete">批量删除</span></a></li>', 'site_currency_delete');?>
            </ul>
        </div>
        <?php if (is_array($currency_list) && count($currency_list)) {?>
        <table  cellspacing="0">
            <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="20"><input type="checkbox" id="check_all"></th>
                        <th width="100">操作</th>
                        <th width="100">代码</th>
                        <th width="200">名称</th>
                        <th width="50">符号</th>
                        <th width="100">汇率</th>
                        <th width="60">默认显示</th>
                        <th width="30">状态</th>
                        <th></th> 
                    </tr>
                </thead>
                <tbody>
                        <?php foreach ($currency_list as $rs) : ?>
                    <tr>
                        <td><input class="sel" name="currency_ids[]" value="<?php echo $rs['id'];?>" type="checkbox" /></td>
                        <td>
                            <?php echo role::view_check('<a href="' . url::base() . 'site/currency/edit/' . $rs['id'] . '">编辑</a>', 'site_currency');?>
                            <?php echo role::view_check('<a href="' . url::base() . 'site/currency/do_delete/' . $rs['id'] . '" onclick="return confirm(\'确认删除?\');">删除</a>', 'site_currency_delete');?>
                        </td>
                        <td><?php echo $rs['code'];?></td>
                        <td><?php echo $rs['name'];?></td>
                        <td><?php echo $rs['sign'];?></td>
                        <td><?php echo $rs['conversion_rate'];?></td>
                        <td><?php echo view_tool::get_active_img($rs['default']);?></td>
                        <td><?php echo view_tool::get_active_img($rs['active']);?></td>
                        <td></td>
                    </tr>
                        <?php endforeach;?>
                </tbody>
            </form>
        </table>
            <?php }else {?>
            <?php echo remind::no_rows();?>
            <?php }?>
    </div>
</div>
<!--**content end**-->
<!--FOOTER-->
<div class="new_bottom fixfloat">
    <div class="b_r_view new_bot_l">
        <?php echo view_tool::per_page(); ?>
    </div>

    <div class="Turnpage_rightper">
        <div class="b_r_pager">
            <?PHP echo $this->pagination->render('opococ'); ?>
        </div>
    </div>
</div>
<!--END FOOTER-->
<script type="text/javascript">
    $(function() {
        //删除
        $("#batch_delete").click(function(){
            var i = false;
            $('.sel').each(function(){
                if(i == false){
                    if($(this).attr("checked")==true){
                        i = true;
                    }
                }
            });
            if(i == false){
                alert('请选择要删除的币种！');
                return false;
            }
            if(!confirm('确认删除选中的币种？')){
                return false;
            }
            $('#list_form').attr('action','/site/currency/batch_delete/');
            $('#list_form').submit();
            return false;
        });
    });
</script>