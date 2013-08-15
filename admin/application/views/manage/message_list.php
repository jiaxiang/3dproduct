<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'manage/message/';?>'>留言列表</a></li>
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
                    <a href="<?php echo url::base();?>manage/message/add"><span class="add_pro">添加留言</span></a>
                </li>
            </ul>
        </div>
       <?php if (is_array($messages) && count($messages)){?>
        <table cellspacing="0" class="table_overflow">
                <thead>
                    <tr class="headings">
                        <th width="20px"><input type="checkbox" id="check_all"></th>
                        <th width="100px">操作</th>
                        <?php echo view_tool::sort('优先级', 4, 60);?>
                        <?php echo view_tool::sort('留言标题', 6, 120);?>
                        <?php echo view_tool::sort('邮箱', 8,150);?>
                        <?php echo view_tool::sort('留言时间', 10 ,150);?>
                        <?php echo view_tool::sort('回复状态', 14, 70);?>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                     <?php foreach ($messages as $rs) : ?>
                    <tr>
                        <td><input class="sel" name="message_id[]" value="<?php echo $rs['id'];?>" type="checkbox" /></td>
                        <?php if(isset($rs['is_reply']) && !empty($rs['is_reply'])):?>
                        <td onclick="javascript:$('#message_<?php echo $rs['id'];?>').toggle();" style="cursor:pointer;">查看
                        <?php else:?>
                        <td>
                        <a href="<?php echo url::base();?>manage/message/edit?id=<?php echo $rs['id'];?>">编辑</a> 
                        <?php endif;?>
                        </td>
						<td><?php echo $rs['status'];?></td>
						<td><?php echo $rs['title'];?></td>
						<td><?php if(isset($rs['email']) && !empty($rs['email'])) echo $rs['email']; else echo '无';?></td>
						<td><?php echo $rs['create_timestamp'];?></td>
						<td><?php echo $rs['reply_status'];?></td>
                        <td></td>
                    </tr>
                    <?php if($rs['id']) : ?>
                    <tr style="display:none;" id="message_<?php echo $rs['id'];?>">
	            	<td colspan="8">
		                <div class="new_in_table fixfloat">
			            <table>
			            	<thead>
			                	<tr class="headings">
			                    	<th colspan="2">留言信息</th>
			                    </tr>
			                </thead>
			                <tbody>
			                	<tr>
                                <td width="120px">标题：</td><td><?php echo $rs['title']?></td>
                                </tr>
                                <tr>
                                <td width="120px">优先级：</td><td><?php echo $rs['status']?></td>
                                </tr>
                                <tr>
                                <td width="120px">邮箱：</td><td><?php if(isset($rs['email']) && !empty($rs['email'])) echo $rs['email']; else echo '无';?></td>
                                </tr>
                                <tr>
                                <td width="120px">内容：</td><td><?php echo $rs['content']?></td>
                                </tr>			                   
			                </tbody>			               
			            </table>
			            <?php if(!empty($rs['is_reply'])) :?>
			            <table>
			            	<thead>
			                	<tr class="headings">
			                    	<th colspan="2">回复信息</th>
			                    </tr>
			                </thead>
			         		<?php foreach($rs['replies'] as $val) {?>
                            <tbody>
                                 <tr>
                                 <td width="220px"><?php echo $val['manager_name']?>于<?php echo $val['update_timestamp']?>回复：</td>
                                 <td><div align='left'><?php echo $val['content']?></div></td>
                                 </tr>
                            </tbody>
                            <?php  } ?>			               
			            </table>
			            <?php endif;?>
			        </div>
	                </td>
	          		</tr>
	          		 <?php endif;?>
                     <?php endforeach;?>
                </tbody>
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