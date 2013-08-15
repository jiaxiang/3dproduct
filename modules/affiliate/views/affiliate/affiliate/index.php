<div class="new_content">
	<div class="newgrid">
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on">平台支持的网站联盟列表</li>
			</ul>
			<span class="fright">
				当前站点：<a title="点击访问" target="_blank" href="http://<?php echo $site_name ?>"><?php echo $site_name ?></a> [ <a href="/manage/site/out">返回</a> ]
			</span>
		</div>
		<div class="newgrid_top">
            <ul class="pro_oper">
                <li class=""><a href="/affiliate/affiliate"><span class="add_pro">网站联盟列表</span></a></li>
                <li class=""><a href="/affiliate/affiliate/select"><span class="add_pro">网站联盟订单查看</span></a></li>
            </ul>
        </div>
		<div>
			<table cellspacing="0" class="table_overflow">
				<tr class="headings even">
					<th width="100px">操作</th>
					<th width="200px">联盟名称</th>
					<th width="450px">参数格式</th>
					<th></th>
					</tr>
				<?php foreach ($affiliates as $af) : ?>
                <tr pid="0" id="top_div_164" class="row odd">
                    <td align="right">
                    	<?php if (isset($af['install'])&& $af['install']==1) : ?>
                    	已经安装 | <a href="/affiliate/affiliate/uninstall/<?php echo $af['id'] ?>" onclick="return confirm('确认要卸载该联盟推广应用吗？')">卸载</a>
                    	<?php else : ?>
                    	<a href="/affiliate/affiliate/install/<?php echo $af['id'] ?>">安装联盟推广</a>
                    	<?php endif; ?>
                    </td>
                    <td><?php if (isset($af['install'])&& $af['install']==1) : ?>
                    		<a href="/affiliate/affiliate/edit/<?php echo $af['id'] ?>"><?php echo $af['name'] ?></a>
                    	<?php else : ?>
                    		<?php echo $af['name'] ?>
                    	<?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars( $af['format'] ) ?></td>
                    <td></td>
                </tr>
                <?php endforeach; ?>
            </table>
		</div>
	</div>
</div>