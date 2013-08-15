<?php defined('SYSPATH') OR die('No direct access allowed.');
/* 返回的主体数据 */
$return_data = $return_struct['content'];
$list = $return_data['list'];
?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href="<?php echo url::base();?>kc_browser_stat">客户端浏览器信息统计</a></li>
            </ul>
            <span class="fright">
                <?php if(site::id()>0):?>
                当前站点：<a href="http://<?php echo site::domain();?>" target="_blank" title="点击访问"><?php echo site::domain();?></a> [ <a href="<?php echo url::base();?>manage/site/out">返回</a> ]
                <?php endif;?>
            </span>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
            </ul>
        </div>
        <?php if (is_array($list) && count($list)){?>
        <table  cellspacing="0" class="table_overflow">
             <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <?php echo view_tool::sort('内核类型(百分比)',2, 60);?>
                        <?php echo view_tool::sort('内核版本(百分比)',4, 60);?>
                        <th width="30">主版本号</th>
                        <th width="30">副版本号</th>
                        <?php echo view_tool::sort('详细信息(百分比)', 6, 350);?>
                        <?php echo view_tool::sort('ip', 8, 60);?>
                        <?php echo view_tool::sort('最近访问时间', 12, 85);?>
                        <?php echo view_tool::sort('访问次数', 14, 40);?>
                    </tr>
                </thead>
                <tbody>
                  
                  <?php foreach($list as $val){?>
                  <tr >
                  <td>&nbsp; <?php echo $val['type'];?>&nbsp;(<?php echo $val['type_percentage'];?>%)</td>
                  <td>&nbsp; <?php echo $val['version'];?>&nbsp;(<?php echo $val['version_percentage'];?>%)</td>
                  <td>&nbsp; <?php echo $val['major_version'];?>&nbsp;</td>
                  <td>&nbsp; <?php echo $val['minor_version'];?>&nbsp;</td>
                  <td title=" <?php echo $val['agent_detail'];?>">&nbsp; <?php echo $val['agent_detail'];?>&nbsp;(<?php echo $val['agent_detail_percentage'];?>%)</td>
                  <td>&nbsp; <?php echo long2ip($val['ip']);?>&nbsp;</td>
                  <td>&nbsp; <?php echo $val['date_upd'];?>&nbsp;</td>
                  <td>&nbsp; <?php echo $val['quantity'];?>&nbsp;</td>
                  </tr>
                  <?php 
                  }?>
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
