<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">Sitemap生成</li>
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
<!--** content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**productlist edit start**-->
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current().'/build';?>">
                <div class="edit_area">
                    <div class="division">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="20%"><b>优先权:</b></th>
                                    <td>
                                    <div style="padding-bottom:5px;"><select name="index">
                                        <option>1.0</option>
                                        <option>0.9</option>
                                        <option>0.8</option>
                                        <option>0.7</option>
                                        <option>0.6</option>
                                        <option>0.5</option>
                                        <option>0.4</option>
                                        <option>0.3</option>
                                        <option>0.2</option>
                                        <option>0.1</option>
                                        <option>0.0</option>
                                        </select> 首页</div> 
                                   <div style="padding-bottom:5px;"><select name="category">
                                        <option>1.0</option>
                                        <option>0.9</option>
                                        <option>0.8</option>
                                        <option>0.7</option>
                                        <option>0.6</option>
                                        <option>0.5</option>
                                        <option>0.4</option>
                                        <option>0.3</option>
                                        <option>0.2</option>
                                        <option>0.1</option>
                                        <option>0.0</option>
                                        </select> 分类页</div> 
                                   <div style="padding-bottom:5px;"><select name="product">
                                        <option>1.0</option>
                                        <option>0.9</option>
                                        <option>0.8</option>
                                        <option>0.7</option>
                                        <option>0.6</option>
                                        <option>0.5</option>
                                        <option>0.4</option>
                                        <option>0.3</option>
                                        <option>0.2</option>
                                        <option>0.1</option>
                                        <option>0.0</option>
                                        </select> 商品页</div> 
                                  <div style="padding-bottom:5px;"><select name="promotion">
                                        <option>1.0</option>
                                        <option>0.9</option>
                                        <option>0.8</option>
                                        <option>0.7</option>
                                        <option>0.6</option>
                                        <option>0.5</option>
                                        <option>0.4</option>
                                        <option>0.3</option>
                                        <option>0.2</option>
                                        <option>0.1</option>
                                        <option>0.0</option>
                                        </select> 促销页</div> 
                                 <div style="padding-bottom:5px;"><select name="doc">
                                        <option>1.0</option>
                                        <option>0.9</option>
                                        <option>0.8</option>
                                        <option>0.7</option>
                                        <option>0.6</option>
                                        <option>0.5</option>
                                        <option>0.4</option>
                                        <option>0.3</option>
                                        <option>0.2</option>
                                        <option>0.1</option>
                                        <option>0.0</option>
                                        </select> 文案页</div>
                                    </td>
                                </tr>
                                 <tr>
                                    <th><b>包含的项目:</b></th>
                                    <td>
                                    <div><b>包含的商品</b></div>
                                    <div style="padding-top:5px;">
                                    <select name="on_sale"><option value="0">所有商品</option><option value="1">上架商品</option></select>
                                                                                                选择需要生成sitemap的商品
                                    </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th><b>不包含的项目:</b></th>
                                    <td>
                                    <div><b>不包含的分类</b></div>
                                    <div><select id="exclude_category" name="exclude_category[]" class="text" multiple="multiple" size="10">
                                    <?php echo $category_list;?>
                                    </select> 按住ctrl+鼠标左键选择不包含的分类页面</div>
                                    <div><b>不包含的商品</b></div>
                                    <div><textarea rows="4" cols="40" name="exclude_product"></textarea> 不包括的商品页面： 商品sku列表，用英文逗号隔开，末尾不要加逗号</div>
                                    </td>
                                </tr>
                            <input type="hidden" name="site_id" value="<?php echo $site_id; ?>"/>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                     <input type="submit" class="ui-button" name="button" value="重建sitemap"/>
                </div>
            </form>
            <!--**productlist edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
