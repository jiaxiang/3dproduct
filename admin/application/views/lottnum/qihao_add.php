 <?php defined('SYSPATH') OR die('No direct access allowed.');?>
 <!-- header_content -->
<?php
echo html::script(array
(
	'js/calendar/cal'
), FALSE);
echo html::stylesheet(array
(
 	'js/calendar/calendar-win2k-cold-1'
), FALSE);
?>
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><?php echo isset($id)?"编辑":"添加"?><?php echo $lottconfig[$lotyid]?>期号</li>
            </ul>
        </div>

    </div>
</div>

<style type="text/css">
	.time_out{background:url('/../images/set.png') no-repeat;border:none;width:20px;height:20px; cursor: pointer;}
</style>
<!-- header_content(end) -->
<!--** content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">

            <!--**productlist edit start**-->
            <form id="add_form" name="add_form" method="post" action="/lottnum/qihao/addok/<?php echo $type;?>/<?php echo isset($id)?$id:"";?>">
                <div class="edit_area">

                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
								<tr> 
                                    <td  colspan=4>
                                   <b> 基础信息</b>
                                    </td>

                                </tr>
                                <tr>
                                    <th width="15%">期号：<span class="required"> *</span>：</th>
                                    <td colspan=3>
                                        <input type="text" size="10" name="qihao" id="qihao" class="text t200  _x_ipt required" value="<?php echo isset($data['qihao'])?$data['qihao']:"";?>" />
                                        <input type="hidden" name="lotyid" value="<?php echo $lotyid?>">
                                    </td>

                                </tr>
								<tr>
                                    <th width="15%">是否是当前期<span class="required"> *</span>：</th>
                                    <td colspan=3>
                                        <input name="isnow" type="checkbox" id="isnow" value="1" <?php echo isset($data['isnow'])&&$data['isnow']==1?"checked":"";?> />
                                    </td>
                                </tr>

								<tr>
                                    <th width="15%">是否可售<span class="required"> *</span>：</th>
                                    <td colspan=3>
                                        <input name="buystat" type="checkbox" id="buystat" value="1" <?php echo isset($data['buystat'])&&$data['buystat']==1?"checked":"";?> />
                                    </td>
                                </tr>
								<tr>

                                    <th width="15%">官方截止时间：<span class="required"> *</span>：</th>
                                    <td colspan=3><input type="text" size="10" name="endtime" id="endtime" class="text t200  _x_ipt required" value="<?php echo isset($data['endtime'])?$data['endtime']:"";?>" /><input type="button" class="time_out"  id="endt"> <font color="red">注：2011-12-05 21:00:00</font>
                                    </td>
                                </tr>
                                <tr>

                                    <th width="15%">开奖时间：<span class="required"> *</span>：</th>
                                    <td colspan=3><input type="text" size="10" name="ktime" id="ktime" class="text t200  _x_ipt required" value="<?php echo isset($data['ktime'])?$data['ktime']:"";?>" /><input class="time_out" type="button" id="kendt">
                                    </td>
                                </tr>
								<tr>
                                    <th width="15%">单式截止时间：<span class="required"> *</span>：</th>

                                  <td colspan=3>
                                      <input type="text" size="10" name="dendtime" id="dendtime" class="text t200  _x_ipt required" value="<?php echo isset($data['dendtime'])?$data['dendtime']:"";?>" /><input class="time_out" type="button" id="dendt">									  
                                    </td>
                                </tr>
								<tr>
                                    <th width="15%">复式截止时间：<span class="required"> *</span>：</th>

                                    <td colspan=3><input type="text" size="10" name="fendtime" id="fendtime" class="text t200  _x_ipt required" value="<?php echo isset($data['fendtime'])?$data['fendtime']:"";?>" /><input class="time_out" type="button" id="fendt">
                                    </td>
                                </tr>
								<tr>
                                    <th width="15%">是否充许出票<span class="required"> *</span>：</th>
                                    <td colspan=3><input name="cpstat" type="checkbox" id="cpstat" value="1" <?php echo isset($data['cpstat'])&&$data['cpstat']==1?"checked":"";?> />
                                    </td>

                                </tr>
                                <?php if($type=='pls'){
                                		
                                ?>
								<tr>
                                    <th width="15%">限号列表<span class="required"> *</span>：</th>
                                    <td colspan=3><textarea name="limitcode" id="limitcode" rows="10" cols="37" class="text t200  _x_ipt"><?php echo isset($data['ext']['limitcode'])?$data['ext']['limitcode']:"";?></textarea><font color="red">注：每组号码用英文逗号分开  如：123,456,119,332</font>
                                    </td>

                                </tr>
								<?php 

								}
                            if(!isset($id))
                                		echo '<table style="display:none">';
                                ?>
                                
                                
                                <tr> 
                                    <td  colspan=4>
                                    <b>扩展信息</b><input name="getext"  style="margin-left:500px" id="getkjinfo" type="button" class="ui-button" value="  智能获取开奖信息 " />
                                    </td>
                                    
                                    <?php
                                    $bonusinfo = array();
                                    $salesacc = array();
                                    $ext = array();
                                    if(isset($data['ext'])){
                                      	$ext = $data['ext'];
                                      	if($ext['bonusinfo']){
                                      		$bonusinfo = explode(';',$ext['bonusinfo']);
                                      	}
                                      	if($ext['salesacc']){
                                      		$salesacc = explode('|',$ext['salesacc']);
                                      	}
                                    }
                                    ?>

                                </tr>
                                <?php

								switch($lotyid)
								{
									

									case '8':								
								?>
								<tr>
                                    <th width="15%">开奖号码<span class="required"> </span>：</th>
                                    <td colspan=3><input type="text" size="10" name="awardnum" id="awardnum" class="text t300  _x_ipt" value="<?php echo isset($ext['awardnum'])?$ext['awardnum']:"";?>" />  <font color="red">注：01,02,03,04,05|01,02 </font>         
                                    </td>
                                </tr>
                                <?php
								break;
								case '9':								
								?>

								<tr>
                                    <th width="15%">开奖号码<span class="required"> </span>：</th>
                                    <td colspan=3><input type="text" size="10" name="awardnum" id="awardnum" class="text t300  _x_ipt" value="<?php echo isset($ext['awardnum'])?$ext['awardnum']:"";?>" />  <font color="red">注：1,2,3,4,5 </font>         
                                    </td>
                                </tr>

								<?php
								break;
								case '10':								
								?>

								<tr>
                                    <th width="15%">开奖号码<span class="required"> </span>：</th>
                                    <td colspan=3><input type="text" size="10" name="awardnum" id="awardnum" class="text t300  _x_ipt" value="<?php echo isset($ext['awardnum'])?$ext['awardnum']:"";?>" />  <font color="red">注：1,2,3,4,5,6,7 </font>         
                                    </td>
                                </tr>

								<?php
								break;


								case '11':								
								?>

								<tr>
                                    <th width="15%">开奖号码<span class="required"> </span>：</th>
                                    <td colspan=3><input type="text" size="10" name="awardnum" id="awardnum" class="text t300  _x_ipt" value="<?php echo isset($ext['awardnum'])?$ext['awardnum']:"";?>" />  <font color="red">注：1,2,3,4,5,6,7 </font>         
                                    </td>
                                </tr>

								<?php
								break;
																
								}
						

                                
                                foreach ($bonusconfig as $key=>$val): 
                                    $thebonus = array();
	                                if(isset($bonusinfo[$key-1])){
	                                	$thebonus = explode(',',$bonusinfo[$key-1]);
	                                }
                                ?>
                                <tr>
                                    <th width="15%"><?php echo $val['name'];?>奖金<span class="required"> </span>：</th>

                                    <td><input type="text" size="10" id="zd<?php echo $key;?>" name="zd<?php echo $key;?>" class="text t200  _x_ipt" value="<?php echo isset($thebonus[2])?$thebonus[2]:$val['default'];?>" />
                                    </td>
                                    <th width="15%"><?php echo $val['name'];?>中奖注数<span class="required"> </span>：</th>

                                    <td><input type="text" size="10" id="zn<?php echo $key;?>" name="zn<?php echo $key;?>" class="text t200  _x_ipt" value="<?php echo isset($thebonus[1])?$thebonus[1]:"";?>" />
                                    </td>
                                </tr>
                                <?php endforeach;?>
                                <tr>

                                    <th width="20%">
                                        当期销量
                                    </th>
                                    <td colspan="3">
                                   		 <input type="text" name="sales" id="sales" class="text" value="<?php echo isset($salesacc[0])?$salesacc[0]:"";?>" size="25">                                     
                                    </td>
                                </tr>
                                <tr>

                                    <th width="20%">
                                        滚存
                                    </th>
                                    <td colspan="3">
                                   		 <input type="text" name="acc" id="acc" class="text" value="<?php echo isset($salesacc[1])?$salesacc[1]:"";?>" size="25">                                     
                                    </td>
                                </tr> 
                                  <?php if(!isset($id))
                                		echo '</table>';
                                ?>                								
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="list_save">
                    <input name="submit" type="submit" class="ui-button" value=" 保存 " />
                </div>
            </form>
            <!--**productlist edit end**-->
        </div>
    </div>
<script>

Calendar.setup ({inputField : "endtime", 
	             ifFormat : "%Y-%m-%d 20:00:00", 
	             showsTime : false, 
	             button : "endt", 
	             singleClick : true, step : 1}
);
Calendar.setup ({inputField : "fendtime", 
    ifFormat : "%Y-%m-%d 19:30:00", 
    showsTime : false, 
    button : "fendt", 
    singleClick : true, step : 1}
);
Calendar.setup ({inputField : "dendtime", 
    ifFormat : "%Y-%m-%d 19:30:00", 
    showsTime : false, 
    button : "dendt", 
    singleClick : true, step : 1}
);
Calendar.setup ({inputField : "ktime", 
    ifFormat : "%Y-%m-%d 21:00:00", 
    showsTime : false, 
    button : "kendt", 
    singleClick : true, step : 1}
);
</script>
</div>
<!--**content end**-->
<script type="text/javascript" src="/js/jquery.validate.js"></script>
<script type="text/javascript" src="/js/jq/plugins/tinymce/tiny_mce.js"></script>
<script type="text/javascript" src="/js/init_tiny_mce.js"></script>
<div id='upload_content' style="display:none;"></div>

<script type="text/javascript">
	$(function() {
        //删除
        $("#getkjinfo").click(function(){
            var issue = $('#qihao').val();
            if(issue==''||issue==null){
                alert("期号为空");
                return false;
            }

        	$.ajax({
        		type: "get",
        		dataType:"json",
        		url: "/lottnum/qihao/getkjinfo/<?php echo $type;?>/"+issue,
        		success: function(data){
        		    if(data.acc!=null){
		        		$('#acc').val(data.acc);
		        		$('#sales').val(data.sale);
		        		$('#awardnum').val(data.opencode);
		        		var winname = data.names;
		        		var wincount = data.counts;
		        		var winbouns = data.bouns;
		        		jQuery.each(winbouns, function(i, val) {
	                        var key = i+1;
							$('#zd'+key).val(val);
							$('#zn'+key).val(wincount[i]);
		        	    });
        		    }

        		}
        	});
            return false;
        });

        
    });

</script>

     
             </div>            
        </div>


