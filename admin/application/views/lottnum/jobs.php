     	 <!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="<?php echo $status=='dlt'?"on":"off";?>"><a href='/lottnum/jobs/index/dlt'>大乐透</a></li>
				<li class="<?php echo $status=='plw'?"on":"off";?>"><a href='/lottnum/jobs/index/plw'>排列五</a></li>
				<li class="<?php echo $status=='qxc'?"on":"off";?>"><a href='/lottnum/jobs/index/qxc'>七星彩</a></li>
				<li class="<?php echo $status=='pls'?"on":"off";?>"><a href='/lottnum/jobs/index/pls'>排列三</a></li>
            </ul>
            </ul>
        </div>																																											
        <div class="newgrid_top">

            <ul class="pro_oper">
           <form action="/lottnum/jobs/add/<?php echo $status; ?>" method="post">
             
                <li>所属期号 <select name="issue">
                <?php foreach ($issues as $issue): ?>
                <option value="<?php echo $issue['qihao']?>" <?php echo $issue['isnow']==1?"selected":"";?>><?php echo $issue['qihao']?></option>
                <?php endforeach;?>
                </select></li>
                <li>任务类型 <select name="jtype">
                <option value="1">清算任务</option>
                <option value="2">算奖任务</option>
                <option value="3">派奖任务</option>
                </select></li>
                <li><input name="submit" type="submit" value=" 下达 " /></li>
                <li>记录数：<?php echo $count; ?></li>
                </form>
                
				 </ul>
            
        </div>
        <?php if (is_array($list) && count($list)) {?>
                <table  cellspacing="0">
        <form id="list_form" name="list_form" method="post" action="">
                <thead>
                    <tr class="headings">
                        <th width="40px">ID</th>
                        <th width="70px">期号</th>

						 <th width="100px">彩种</th>
						 <th width="50px" class="txc">任务类型</th>
						 <th width="100px" class="txc">开始时间</th>
						 <th width="100px" class="txc">结束时间</th>
						 <th width="110px" class="txc">状态</th>
						 <th width="110px" class="txc">下达管理员</th>
						 <th width="110px" class="txc">下达时间</th>
						 <th width="110px" class="txc">说明</th>
                    </tr>
                </thead>
                <tbody>
                
                <?php 
                foreach ($list as $val){
                ?>
                                         <tr>    

                        
                        <td><?php echo $val['id']?>&nbsp;</td>
                        <td><?php echo $val['qihao']?>&nbsp;</td>
						<td><?php echo $lottconfig[$val['lottyid']]?></td>
                        <td  class="txc"><?php echo $jobtype[$val['tasktype']]?></td>
						<td  class="txc"><?php echo $val['stime']?$val['stime']:"未开始"; ?></td>
						<td  class="txc"> <?php echo $val['etime']?$val['etime']:"未结束"; ?></td>
						
						<td  class="txc"> <?php echo $jobstatconfig[$val['stat']]?></td>
						
						<td  class="txc"><?php echo $val['manager']?></td>
						<td  class="txc"><?php echo $val['ctime']?></td>
						
						<td  class="txc"><?php echo $val['note']?></td>
						 
                      
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
