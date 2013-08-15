<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'user/email_sign_up/';?>'>Email_sign_up列表</a></li>
            </ul>
            <span class="fright">
                <?php if(site::id()>0):?>
                当前站点：<a href="http://<?php echo site::domain();?>" target="_blank" title="点击访问"><?php echo site::domain();?></a> [ <a href="<?php echo url::base();?>manage/site/out">返回</a> ]
                <?php endif;?>
            </span>
        </div>
        <div class="newgrid_top">
            <form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div>搜索：
                    <select name="search_type" class="text">
                        <option value="email" <?php if ($where['search_type'] == 'email')echo "SELECTED";?>>Email</option>
                        <option value="firstname" <?php if ($where['search_type'] == 'firstname')echo "SELECTED";?>>姓</option>
                        <option value="lastname" <?php if ($where['search_type'] == 'lastname')echo "SELECTED";?>>名</option>
                        <option value="ip" <?php if ($where['search_type'] == 'ip')echo "SELECTED";?>>IP</option>
                    </select>
                    <input type="text" name="search_value" class="text" value="">
                    <input type="submit" name="Submit2" value="搜索" class="ui-button-small">
                </div>
            </form>

        </div>
        <?php if (is_array($email_sign_up_list) && count($email_sign_up_list)) {?>
        <table  cellspacing="0">
            <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                            <?php echo view_tool::sort('站点',2, 200);?>
                            <?php echo view_tool::sort('Email',4, 200);?>
                            <?php echo view_tool::sort('姓',6, 80);?>
                            <?php echo view_tool::sort('名',8, 80);?>
                            <?php echo view_tool::sort('注册时间',10, 150);?>
                            <?php echo view_tool::sort('IP/地址', 12, 100);?>
                            <?php echo view_tool::sort('状态', 14, 30);?>
                    </tr>
                </thead>
                <tbody>
                        <?php foreach ($email_sign_up_list as $rs) : ?>
                    <tr>
                        <td><?php echo $rs['site']['name'];?>&nbsp;</td>
                        <td><?php echo $rs['email'];?>&nbsp;</td>
                        <td><?php echo $rs['firstname'];?>&nbsp;</td>
                        <td><?php echo $rs['lastname'];?>&nbsp;</td>
                        <td><?php echo $rs['date_add'];?>&nbsp;</td>
                        <td><?php echo long2ip($rs['ip']);?>&nbsp;</td>
                        <td><?php echo view_tool::get_active_img($rs['active']);?></td>
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