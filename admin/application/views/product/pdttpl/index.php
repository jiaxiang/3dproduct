<div class="new_content">
	<div class="out_box pro_ie6">
		<div class="new_sub_menu_title fixfloat">
			<span class="title2">设置商品模板</span>
			<span class="fright">
                <?php if(site::id()>0):?>
                当前站点：<a href="http://<?php echo site::domain();?>" target="_blank" title="点击访问"><?php echo site::domain();?></a> [ <a href="<?php echo url::base();?>manage/site/out">返回</a> ]
                <?php endif;?>
            </span>
		</div>
		<input type="button" value="选择要作为模板的商品" onclick="show_goods_nb_container()">
		
		<script type="text/javascript">
		function show_template(product_id){
			document.getElementById("template_iframe").src = "/product/pdttpl/product_as_template?product_id=" + product_id;
		}
		</script>
		<div id="template_container" style="border:1px solid #CCCCCC;margin-top:10px;min-width:900px;">
			<?php if(isset($products[0]['id'])) : ?>
			
			<script type="text/javascript">  
			function SetCwinHeight(){  
				var bobo=document.getElementById("template_iframe"); //iframe id
				if (document.getElementById){  
					if (bobo && !window.opera){
						if (bobo.contentDocument && bobo.contentDocument.body.offsetHeight){
							bobo.height = bobo.contentDocument.body.offsetHeight;
						}else if(bobo.Document && bobo.Document.body.scrollHeight){
							bobo.height = bobo.Document.body.scrollHeight;
						}
					}
				}
			}
			</script>  
			<iframe id="template_iframe" name="template_iframe" src="/product/pdttpl/product_as_template?product_id=<?php echo $products[0]['id'] ?>" scrolling="No" frameborder="0" style="border: 0px none; width: 100%;" onload="SetCwinHeight()"></iframe>
			<?php else : ?>
			<center><font color="Red">您的站点还没有任何商品，所以不能进行该操作！</font></center>
			<?php endif; ?>
		</div>
		
		<div id="pdt_template_ifm" class="ui-dialog-content ui-widget-content" style="display:none;">
		    <iframe style="border:0px;width:100%;height:95%;" frameborder="0" src="/product/pdttpl/search_products" scrolling="auto"></iframe>
		</div>
		<script>
		$(document).ready(function(){
		    // 图片上传窗口
		    $("#pdt_template_ifm").dialog({
		        title: "添加模板商品",
		        modal: true,
		        autoOpen: false,
		        height: 500,
		        width: 800
		    });
		});
		function show_goods_nb_container(){
			$('#pdt_template_ifm').dialog('open');
		}
		function hide_goods_nb_container(){
			$('#pdt_template_ifm').dialog('close');
		}
		</script>
	</div>
</div>