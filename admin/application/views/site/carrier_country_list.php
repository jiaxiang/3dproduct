<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">物流国家关联列表 : [<?php echo $data['name'];?>]</li>
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
                    <a href="/site/carrier/carrier_country_add/<?php echo $data['id'];?>" class="carrier_country_add" site_id="<?php echo $data['site_id'];?>"><span class="add_pro">编辑物流国家关系</span></a>
                </li>
            </ul>
        </div>
        <?php if (is_array($carrier_country_list) && count($carrier_country_list)) {?>
        <table  cellspacing="0">
                <thead>
                    <tr class="headings">
                        <th width="100px">国家名称</th>
                        <?php echo view_tool::sort('国家代码', 2, 120);?>
                        <th width="120px">物流名称</th>
                        <th width="150px">附加运费</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                     <?php foreach ($carrier_country_list as $rs) : ?>
                    <tr>
                        <td><?php echo $rs['country']['name'];?></td>
                        <td><?php echo $rs['country']['iso_code'];?></td>
                        <td><?php echo $rs['carrier']['name'];?></td>
                        <td><?php echo $rs['shipping_add'];?></td>
                        <td></td>
                    </tr>
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