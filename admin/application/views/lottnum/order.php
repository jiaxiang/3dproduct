     	 <!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="<?php echo $status=='dlt'?"on":"off";?>"><a href='/lottnum/order/index/dlt'>大乐透</a></li>
				<li class="<?php echo $status=='plw'?"on":"off";?>"><a href='/lottnum/order/index/plw'>排列五</a></li>
				<li class="<?php echo $status=='qxc'?"on":"off";?>"><a href='/lottnum/order/index/qxc'>七星彩</a></li>
				<li class="<?php echo $status=='pls'?"on":"off";?>"><a href='/lottnum/order/index/pls'>排列三</a></li>
            </ul>
        </div>																																													
        <div class="newgrid_top">
			<div style="float:left;">
            <ul class="pro_oper">
           
                 <li>
                    <a href="javascript:void(0);"><span class="add_word" id="yescpstat">设为已出票</span></a>
                </li>
                
                 <li>
                    <a href="javascript:void(0);"><span class="add_word" id="nocpstat">设为未出票</span></a>
                </li>               
                         
				 </ul>
				
            
        </div>

         <div class="fa_number" style="float:right;">
				 <ul class="pro_oper">
				 <form action="/lottnum/order/index/<?php echo $status; ?>" method="get">
                <li>方案编号：<input type="text" name="bid" value=""> </li>
                <li>期号 <select name="issue">
                <option value="-1" <?php echo empty($theissue)||$theissue==-1?"selected":"";?>>全部</option>
                <?php foreach ($issues as $issue): ?>
                <option value="<?php echo $issue['qihao']?>" <?php echo $theissue==$issue['qihao']?"selected":"";?>><?php echo $issue['qihao']?></option>
                <?php endforeach;?>
                </select></li>
                
                
				<?php
				if($status=='pls'){
				?>
				<li>玩法 <select name="wtype">
                <option value="0" <?php echo isset($_GET['wtype']) && (strlen($_GET['wtype'])==0)?"selected":"";?>>全部</option>
             
                <option value="1" <?php echo isset($_GET['wtype']) && $_GET['wtype']==1?"selected":"";?>>直选复式</option>

                <option value="3" <?php echo isset($_GET['wtype']) && $_GET['wtype']==3?"selected":"";?>>直选单式</option>

                <option value="4" <?php echo isset($_GET['wtype']) && $_GET['wtype']==4?"selected":"";?>>直选和值</option>


				 <option value="5" <?php echo isset($_GET['wtype']) && $_GET['wtype']==5?"selected":"";?>>组六复式</option>
				  <option value="6" <?php echo isset($_GET['wtype']) && $_GET['wtype']==6?"selected":"";?>>组六单式</option>

				   <option value="9" <?php echo isset($_GET['wtype']) && $_GET['wtype']==9?"selected":"";?>>组三复式</option>

				    <option value="10" <?php echo isset($_GET['wtype']) && $_GET['wtype']==10?"selected":"";?>>组三单式</option>

                </select></li>


				<?php
				}
				?>
                <li>方案状态 <select name="stat">
                <option value="-1" <?php echo isset($_GET['stat']) && (strlen($_GET['stat'])==0||$_GET['stat']==-1)?"selected":"";?>>全部</option>
                <option value="0" <?php echo isset($_GET['stat']) && $_GET['stat']==0?"selected":"";?>>未满员</option>
                <option value="2" <?php echo isset($_GET['stat']) && $_GET['stat']==2?"selected":"";?>>已满员</option>
                <option value="1" <?php echo isset($_GET['stat']) && $_GET['stat']==1?"selected":"";?>>已撤单</option>
                <option value="3" <?php echo isset($_GET['stat']) && $_GET['stat']==3?"selected":"";?>>已出票</option>
                </select></li>
                <li><input name="submit" type="submit" value=" 搜索 " /></li>
                <li>记录数：<?php echo $count; ?></li>
                </form> 
                </ul>
                </div>
             </div>
        <?php if (is_array($list) && count($list)) {?>
                <table  cellspacing="0">
        <form id="list_form" name="list_form" method="post" action="">
                <thead>
                    <tr class="headings">
                       <th width="15px"><input type="checkbox" id="check_all"></th>
                        <th width="100px">操作</th>
                        <th width="40px">ID</th>
                        <th width="100px">期号</th>

						 <th width="100px">彩种</th>
						 <th width="100px" class="txc">发起人</th>
						 <th width="50px" class="txc">总金额</th>
						 <th width="50px" class="txc">总份数</th>
						 <th width="50px" class="txc">倍数</th>
						 <th width="50px" class="txc">进度</th>
						 <th width="60px" class="txc">税后奖金</th>
						 <th width="80px" class="txc">满员状态</th>
						 <th width="80px" class="txc">出票状态</th>
						 <th width="80px" class="txc">撤单状态</th>
                    </tr>
                </thead>
                <tbody>
                
                <?php 
                foreach ($list as $val){
                ?>
                                         <tr>    
                       <td><input class="sel" name="pids[]" value="<?php echo $val['id']?>" type="checkbox" /></td>
                        <td>
                        <?php if($val['restat']==0){ ?>
                        <a href="/lottnum/order/rev/<?php echo $status;?>/<?php echo $val['id']?>?<?php echo http_build_query($_GET)?>" onclick="javascript:return confirm('确定对此方案做撤单处理吗？')">撤单</a>&nbsp;
                          <?php } ?>
                          <a href="http://<?php echo $site_config['name'];?>/<?php echo $status;?>/view/<?php echo $val['id']?>" target="_blank">查看</a> &nbsp;
                            <?php if($val['baodi']==1){ ?>
							<a href="/lottnum/order/clear/<?php echo $status;?>/<?php echo $val['id']?>?<?php echo http_build_query($_GET)?>" onclick="javascript:return confirm('确定对方案清保吗？')"> 清保</a>
						   <?php }?>
						   
                        </td>
                        <td><?php echo $val['basic_id']?>&nbsp;</td>
                        <td><a href="javascript:void(0)" target="_blank"><?php echo $val['qihao']?></a>&nbsp;</td>
						<td><?php echo $lottconfig[$val['lotyid']]?></td>

						<td  class="txc"><?php echo $val['uname']; ?></td>
						<td  class="txc"> <?php echo $val['allmoney']; ?></td>
						
						<td  class="txc"> <?php echo $val['nums']?></td>
						<td  class="txc"><?php echo $val['lotmulti']?></td>
						<td  class="txc"><?php echo $val['renqi']?></td>
						<td  class="txc"><?php echo $val['afterbonus']?></td>
						<td  class="txc"><?php echo $isfullinfo[$val['isfull']]?></td>
						<td  class="txc"><?php echo $cpstatinfo[$val['cpstat']]?></td>
						<td  class="txc"><?php echo $restatinfo[$val['restat']]?></td>
						
						 
                      
                    </tr>
                    <?php }?>
                    <!-- 已出票汇总 -->
                    <?php if(isset($_GET['stat']) && $_GET['stat']==3){?>
                          <tr>  
                          <td colspan="14"><font color="red" style="margin-left:120px">方案汇总: </font>&nbsp;&nbsp;总购买金额(<b><?php echo $sum['allmoney'] ?></b>),&nbsp;&nbsp;总中奖金额(<b><?php echo $sum['afterbonus']; ?></b>)</td>         
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
        $("#yescpstat").click(function(){
            var i = false;
            $('.sel').each(function(){
                if(i == false){
                    if($(this).attr("checked")==true){
                        i = true;
                    }
                }
            });
            if(i == false){
                alert('请选择要手工设置为已出票的方案！');
                return false;
            }
            if(!confirm('确认对选中的方案做出票处理？')){
                return false;
            }
            $('#list_form').attr('action','/lottnum/order/cpstat/<?php echo $status;?>/2');
            $('#list_form').submit();
            return false;
        });
        $("#nocpstat").click(function(){
            var i = false;
            $('.sel').each(function(){
                if(i == false){
                    if($(this).attr("checked")==true){
                        i = true;
                    }
                }
            });
            if(i == false){
                alert('请选择要手工设置为已出票的方案！');
                return false;
            }
            if(!confirm('确认对选中的方案做出票处理？')){
                return false;
            }
            $('#list_form').attr('action','/lottnum/order/cpstat/<?php echo $status;?>/0');
            $('#list_form').submit();
            return false;
        });
    });

</script>
