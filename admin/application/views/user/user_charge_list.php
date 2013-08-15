<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li <?php if(!isset($status)){print('class="on"');} ?>><a href='<?php echo url::base() . 'user/user_charge/';?>'>用户充值列表</a></li>
                <li <?php if(isset($status) && $status=='0'){print('class="on"');} ?>><a href='<?php echo url::base() . 'user/user_charge?status=0';?>'>未确认充值</a></li>
                <li <?php if(isset($status) && $status==1){print('class="on"');} ?>><a href='<?php echo url::base() . 'user/user_charge?status=1';?>'>已确认充值</a></li>
            </ul>
        </div>
        <!-- div class="newgrid_top">
            <ul class="pro_oper">
                 <li><a href="javascript:void(0);"><span class="del_pro" id="batch_delete">批量删除</span></a></li>
            </ul>
            <form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div>搜索:
                	 <select name="search_type">
                     </select>
                     <input type="submit" name="Submit2" value="搜索" class="ui-button-small">
                </div>
            </form>

        </div -->
        <?php if(is_array($lists) && count($lists)) { ?>
        <table  cellspacing="0">
        <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="200">用户名称</td>
                        <th>订单号</td>
                        <th width="100">拍点数量</td>
                        <th width="100"> 金额(元)</td>
                        <th>支付方式</td>
                        <th width="150">日期</td>
                        <th width="100">IP</td>
                        <th width="80">到账确认</td>
                    </tr>
                </thead>
                <tbody>
                     <?php foreach ($lists as $row) : ?>
                    <tr>
                        <td><?php isset($users[$row['user_id']]) && print($users[$row['user_id']]);?>&nbsp;</td>
                        <td><?php echo $row['order_num'];?>&nbsp;</td>
                        <td><?php echo $row['price'];?>&nbsp;</td>
                        <td><?php echo $row['money'];?>&nbsp;</td>
                        <td><?php echo tool::my_substr($row['pay_name'], 40);?>&nbsp;</td>
                        <td><?php echo $row['add_time'];?>&nbsp;</td>
                        <td><?php echo $row['ip'];?>&nbsp;</td>
                        <td>
                            <?php if($row['status']!=1){ ?>
                            <a href="javascript:;" onclick="javascript:if(confirm('确认已收到此项付款了吗？'))window.location.href='<?php echo url::base();?>user/user_charge/do_active/<?php echo $row['id'];?>';">
                            <?php echo view_tool::get_active_img($row['status'],true);?>
                            </a>
                            <?php }else{ ?>
                                <?php echo view_tool::get_active_img($row['status'],true);?>            
                            <?php } ?>
                        </td>
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
    $(function(){
        //批量删除留言
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
                alert('请选择要删除的留言！');
                return false;
            }
            if(!confirm('确认删除选中的留言？')){
                return false;
            }
            $('#list_form').attr('action','/user/user_charge/batch_delete/');
            $('#list_form').submit();
            return false;
        });
    });
</script>