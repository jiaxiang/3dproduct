<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">报告列表</li>
            </ul>
            <span class="fright">
                <?php if(site::id()>0):?>
                当前站点：<a href="http://<?php echo site::domain();?>" target="_blank" title="点击访问"><?php echo site::domain();?></a> [ <a href="<?php echo url::base();?>manage/site/out">返回</a> ]
                <?php endif;?>
            </span>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**productlist start**-->
            <div  class="head_content">
                <div class="actionBar mainHead" ></div>
                <div class="mainHead headBox" >
                    <div class="headContent">
                        <div class="finder-head">
                            <div class="span-1">
                                <input  type="checkbox" id="check_all">
                            </div>
                            <div title="操作" class="span-4">操作</div>
                            <?php
                            foreach ($list_columns as $key=>$value):
                                echo '<div title="' . $value['name'] . '" class="span-' . $value['class_num'] . '">' . $value['name'] . '</div>';
                            endforeach;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main_content" style="visibility: visible; opacity: 1;">
                <div class="finder">
                    <div class="finder-list" >
                        <?php
                        foreach ($scan as $key=>$value):
                            ?>
                        <div class="row">
                            <div class="row-line">
                                <div class="span-1 span-select">
                                    <input tags="null" class="sel" name="goods_id" value="19" type="checkbox">
                                </div>
                                <div class="cell span-4 fd">
                                    <a href="<?php echo url::base();?>site/scan/view/<?php echo $value['rep_id'];?>/<?php echo $value['id'];?>">查看报告</a>
                                    <a href="<?php echo url::base();?>site/scan/delete/<?php echo $value['id'];?>" onclick="return confirm('确认删除?');">删除报告</a>
                                </div>
                                    <?php
                                    foreach ($list_columns as $column_key=>$column_value):
                                        if ($column_value['column'] == 'content_small')
                                            echo '<div class="cell span-' . $column_value['class_num'] . '"><a href="javascript:void(0);" class="contentsmall" id="' . $value['id'] . '">' . $value[$column_value['column']] . '</a></div>';
                                        else
                                            echo '<div class="cell span-' . $column_value['class_num'] . '">' . $value[$column_value['column']] . '</div>';
                                    endforeach;
                                    ?>
                            </div>
                        </div>
                        <?php
                        endforeach;
                        ?>
                    </div>
                </div>
            </div>
            <!--**productlist end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<!--FOOTER-->
<div id="footer">
    <div class="bottom">
        <div class="Turnpage_leftper">
            <ul>
                <li class="b_icon_16"><img src="<?php echo url::base();?>images/icon/plus.png"></li>
                <li><a href="<?php echo url::base();?>site/scan/add">站点扫描</a></li>
            </ul>
        </div><!--end of div class Turnpage_leftper-->

        <div class="Turnpage_rightper">
            <?php echo view_tool::per_page();?>
            <div class="b_r_pager">
                <?PHP echo $this->pagination->render('opococ');?>
            </div>
        </div>
        <!--end of div class Turnpage_rightper-->
    </div>
</div>
<!--END FOOTER-->
<div id='example'></div>
