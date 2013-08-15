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
                    <a href="/site/delivery/edit/<?php echo $data['id'];?>"><span class="add_pro">编辑物流</span></a>
                </li>
            </ul>
        </div>
        
        <table  cellspacing="0">
                <thead>
                    <tr class="headings">
                        <th width="100px">国家名称</th>
                        <th width="100px">国家代码</th>
                        <th width="120px">物流名称</th>
                        <th width="300px">信息</th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                	 <?php if (is_array($delivery_countries) && count($delivery_countries)) :?>
                     <?php foreach ($delivery_countries as $rs) : ?>
                    <tr>
                        <td><?php echo $countries[$rs['country_id']]['name'];?></td>
                        <td><?php echo $countries[$rs['country_id']]['iso_code'];?></td>
                        <td><?php echo $data['name']?></td>
                        <td><?php if ($rs['use_exp'] == 0) : ?> 
                        	首重费用：$<?php echo $rs['first_price'];?>;续重费用：$<?php echo $rs['continue_price'];?>
                        	<?php else : ?>
                        	公式：<?php echo $rs['expression']; ?>
                        	<?php endif;?>
                        </td>
                        <td><span class="required"><?php isset($rs['disable']) && print($countries[$rs['country_id']]['name']."已被管理员禁止使用，将不会调用此物流信息。");?></span></td>
                    </tr>
                     <?php endforeach;?>
                     <?php endif;?>
                     <?php if($data['is_default'] == 1):?>
                     <tr><td>其他</td><td></td><td><?php echo $data['name']?></td>
                     <td><span style="color:#ff0000"><?php if ($data['use_exp'] == 0): ?> 
                        	此物流启用默认费用，首重费用：$<?php echo $data['first_price'];?>;续重费用：$<?php echo $data['continue_price'];?>
                        	<?php else : ?>
                        	此物流启用默认费用，使用公式：<?php echo $data['expression']; ?>
                        	<?php endif;?></span>
                     </td><td></td></tr>
                     <?php endif;?>
                </tbody>
        </table>
        
    </div>
</div>
<!--**content end**-->
<!--FOOTER-->
<!--END FOOTER-->