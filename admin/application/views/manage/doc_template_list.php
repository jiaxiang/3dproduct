	<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div id="content_frame">
  <div class="grid_c2">
    <div class="col_main">
      <div class="public">
        <div class="public_left title_h3">
           <p><a href="<?php echo url::base();?>">后台首页</a> 》<a href="<?php echo url::base();?>product/category/">分类列表</a> </p>
        </div>
        <div class="public_right">
        </div>
      </div>
      <!--	<div class="public_title title_h3"></div>	-->

      <!--**productlist start**-->
      <div  class="head_content">
        <div class="actionBar mainHead" ></div>
        <div class="mainHead headBox" >
          <div class="headContent">
            <div class="finder-head">
              <div class="span-1">
                <input  type="checkbox" id="check_all">
              </div>
			  <?php echo view_tool::orderby('操作',4);?>
			  <?php echo view_tool::orderby('ID',3,0);?>
			  <?php echo view_tool::orderby('名称',5,2);?>
			  <?php echo view_tool::orderby('前台URL',6,6);?>
			  <?php echo view_tool::orderby('排序',3,10);?>
			  <?php echo view_tool::orderby('添加时间',4,12);?>
			  <?php echo view_tool::orderby('更新时间',4,14);?>
            </div>
          </div>
        </div>
      </div>
      <div class="main_content" style="visibility: visible; opacity: 1;">
        <div class="finder">
<?php
	foreach($data as $item)
	{
?>
<div class="finder-list" >
<div class="row" id="top_div_<?php echo $item['id']; ?>">
<div class="row-line" style="cursor: pointer;">
<div class="span-1 span-select">
<input class="sel" name="product_id[]" value="" type="checkbox" temp="">
</div>
<div class="cell span-4 fd">
<a href="/site/doc/edit/<?php echo $item['id']; ?>">编辑</a>&nbsp;
<a href="/site/doc/delete/<?php echo $item['id']; ?>"> 删除</a>&nbsp;
</div>
<div class="span-3 fB">
<?php echo $item['id']; ?>&nbsp;
</div>
	<div class="cell span-5 orderCell"><?php echo $item['title']; ?>&nbsp;</div>
	<div class="cell span-6"><a href="<?php echo site::default_domain($item['site_id']).'/'.$item['permalink']; ?>" target="_black"><?php echo $item['permalink']; ?></a>&nbsp;</div>
<div class="cell span-3">
<a href="/product/category/doedit_position_up/"><img src="/images/arrow-up.gif" alt="up" border="0"></a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="/product/category/doedit_position_down/"><img src="/images/arrow-down.gif" alt="down" border="0"></a>
</div>
<div class="cell span-4"><?php echo $item['created']; ?>&nbsp;</div>
<div class="cell span-4"><?php echo $item['updated']?>&nbsp;</div>
</div>
</div>
</div>
<?php
	}		
?>

<!---------------------------------------->
</div>
</div>
      <!--**productlist end**-->
</div>
</div>
</div>
<!--**content end**-->
<!--**footer start**-->
<div id="footer">
<div class="bottom">
<div class="Turnpage_40per">
<p> 
<span><a href="/site/doc/add">添加文案</a></span> 
<span>打印样式</span> 
<span> 刷新</span>  
<span> 删除 </span> 
<span> 回收站</span> 
<span>导出</span> 
<span>导入</span> 
<span>批量操作</span> 
<span>留言处理</span> 
</p>
</div>
<div class="Turnpage_50per">
<?php
	echo $this->pagination->render('opococ');
?>
</div>
<div class="Turnpage_10per">
<p>
<span class="Turnpage_btn120">
<a href="#">查看所有站点列表</a>
</span>
</p>
</div>
</div>
</div>
<!--**footer end**-->

