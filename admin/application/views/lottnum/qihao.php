     	 <!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="<?php echo $status=='dlt'?"on":"off";?>"><a href='/lottnum/qihao/index/dlt'>大乐透</a></li>
				 <li class="<?php echo $status=='plw'?"on":"off";?>"><a href='/lottnum/qihao/index/plw'>排列五</a></li>
				 <li class="<?php echo $status=='qxc'?"on":"off";?>"><a href='/lottnum/qihao/index/qxc'>七星彩</a></li>
				 <li class="<?php echo $status=='pls'?"on":"off";?>"><a href='/lottnum/qihao/index/pls'>排列三</a></li>

            </ul>
        </div>																																													
        <div class="newgrid_top">

            <ul class="pro_oper">
                <li>
                    <a href="/lottnum/qihao/add/<?php echo $status;?>"><span class="add_pro">添加期号</span></a>
                </li>
                <li><a href="javascript:void(0);"><span class="del_pro" id="batch_delete">批量删除</span></a></li>
				 </ul>
            
        </div>
        <?php if (is_array($list) && count($list)) {?>
                <table  cellspacing="0">
        <form id="list_form" name="list_form" method="post" action="">
                <thead>
                    <tr class="headings">
                        <th width="15px"><input type="checkbox" id="check_all"></th>
                        <th width="100px">操作</th>
                       
                        <th width="170px">期号</th>

						 <th width="100px">彩种</th>
						 <th width="50px" class="txc">当前期标识</th>
						 <th width="50px" class="txc">是否可售</th>
						 <th width="50px" class="txc">是否清算</th>
						 <th width="50px" class="txc">是否结算</th>
						 <th width="110px" class="txc">官方截止时间</th>
						 <th width="110px" class="txc">单式截止时间</th>
						 <th width="110px" class="txc">复式截止时间</th>
                    </tr>
                </thead>
                <tbody>
                
                <?php 
                foreach ($list as $val){
                ?>
                                         <tr>
                        <td><input class="sel" name="qids[]" value="<?php echo $val['id']?>" type="checkbox" /></td>

                        <td><a href="/lottnum/qihao/add/<?php echo $status;?>/<?php echo $val['id']?>">编辑</a>&nbsp;
							<a href="/lottnum/qihao/del/<?php echo $status;?>/<?php echo $val['id']?>" onclick="javascript:return confirm('确定删除？')"> 删除</a>
                        </td>
                        
                        <td><a href="javascript:void(0)" target="_blank"><?php echo $val['qihao']?></a>&nbsp;</td>
						<td><?php echo $lottconfig[$val['lotyid']]?></td>

						<td  class="txc"><?php echo $val['isnow']==1?"<img src='/../images/1.png'>":"<img src=\"/../images/0.png\">"; ?></td>
						<td  class="txc"> <?php echo $val['buystat']==1?"<img src='/../images/1.png'>":"<img src=\"/../images/0.png\">"; ?></td>
						<td class="txc"><?php echo $val['qsstat']==1?"<img src='/../images/1.png'>":"<img src=\"/../images/0.png\">"; ?></td>
						<td class="txc"><?php echo $val['pjstat']==1?"<img src='/../images/1.png'>":"<img src=\"/../images/0.png\">"; ?></td>
						<td  class="txc"> <?php echo $val['endtime']?></td>
						<td  class="txc"><?php echo $val['dendtime']?></td>
						<td  class="txc"><?php echo $val['fendtime']?></td>
						
						 
                      
                    </tr>
                    <?php }?>
                                        
                                      
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
                alert('请选择要删除的期号！');
                return false;
            }
            if(!confirm('确认删除选中的期号？')){
                return false;
            }
            $('#list_form').attr('action','/lottnum/qihao/batch_delete/<?php echo $status;?>');
            $('#list_form').submit();
            return false;
        });
    });

</script>
