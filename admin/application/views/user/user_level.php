<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_content">
    <form method="post" name="add_form" action="/user/user_attribute/set_order">
        <div class="newgrid">

            <div class="newgrid_tab fixfloat">
                <ul>
                    <li class="on">会员等级</li>
                </ul>
                <span class="fright">
                    <?php if(site::id()>0):?>
                    当前站点：<a href="http://<?php echo site::domain();?>" target="_blank" title="点击访问"><?php echo site::domain();?></a> [ <a href="<?php echo url::base();?>manage/site/out">返回</a> ]
                    <?php endif;?>
                </span>
            </div>
            <div class="newgrid_top">
                <ul class="pro_oper">
                    <li>
                        <a href="/user/user_level/add"><span class="add_pro">添加会员等级</span></a>
                    </li>
                    <!-- 
                    <li>
                        <a href="/user/user_level/formula"><span class="add_word">设置积分计算公式</span></a>
                    </li>
                     -->
                </ul>
            </div>
            <table cellspacing="0" class="table_mover">
                <thead >
                    <tr class="headings">
                        <th width="100">操作</th>
                        <th width="100">管理名称</th>
                        <th width="100">等级名称</th>
                        <th width="100">所需积分</th>
                        <th  width="100">默认等级</th>
                        <th  width="100">特殊等级</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($user_levels as $user_level):?>
                    <tr>
                        <td width="100">
                            <a href="/user/user_level/edit?level_id=<?php echo $user_level['id']?>"> 编辑 </a> &nbsp;
                            <a href="/user/user_level/delete?level_id=<?php echo $user_level['id']?>" onclick="javascript:return confirm('等级删除以后，该等级的会员的等级会自动降到默认等级，请确认要删除此项?')"> 删除</a>
                        </td>
                    
                        <td width="100">
                            <?php echo $user_level['name_manage']; ?>
                        </td>
                        <td width="100">
                            <?php echo $user_level['name']; ?>
                        </td>
                        <td width="100">
                            <?php echo $user_level['score']; ?>
                        </td>
                        <td width="100">
                        	<img <?php if($user_level['is_default']) {?>src="/images/icon/accept.png"<?php } else {?> src="/images/icon/cancel.png"<?php }?> class="active_img" />
                        </td>
                        <td width="100">
                        	<img <?php if($user_level['is_special']) {?>src="/images/icon/accept.png"<?php } else {?> src="/images/icon/cancel.png"<?php }?> class="active_img" />
                        </td>
                        <td></td>
                    </tr>
                    <?php endforeach;?>
                </tbody>

            </table>

        </div>
    </form>
</div>
<!-- header_content(end) -->
<script type="text/javascript">

</script>