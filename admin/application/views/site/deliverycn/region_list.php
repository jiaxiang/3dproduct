<div class="col_main">
     <div class="list_save">
         <input id="save_sel_country" type="button" class="ui-button" value="  确定   "/>
         <input id="check_all" type="button" class="ui-button" value="  全选   ">
         <input id="cancel_check_all" type="button" class="ui-button" value="  重置   ">
         <input id="cancel_sel_country" type="button" class="ui-button" value="  取消   "/>
     </div>
     <div class="out_box">
		<table width="100%" cellspacing="0" class="table_overflow">
	    <tbody>
	    <?php foreach($regions as $region):?>	    
	        <tr id="region_<?php echo $region['id'] ?>" class="row" myid="<?php echo $region['id'] ?>" pid="<?php echo $region['p_region_id'] ?>" ajax_request="0"><td><img src="/images/icon_dot<?php $icon_dot_num=$region['childs']>0?1:2; echo $icon_dot_num; ?>.gif" class="icon_dot" onclick="fold(<?php echo $region['id']?>)" /> <input type="checkbox" id="region_checkbox_<?php echo $region['id'];?>" name="region_id" myid="<?php echo $region['id'] ?>" pid="<?php echo $region['p_region_id'] ?>" onclick="selectregions(<?php echo $region['id'];?>)" value="<?php echo $region['id'];?>"> <?php echo $region['local_name'];?></td></tr>
	    <?php endforeach?>
	    </tbody>                
	    </table>
		<!--ul class="tree">
	    <?php foreach($regions as $region):?>	    
	        <li id="region_<?php echo $region['id'] ?>" pid="<?php echo $region['id'] ?>"><img src="/images/icon_dot<?php $icon_dot_num=$region['childs']>0?1:2; echo $icon_dot_num; ?>.gif" class="icon_dot" onclick="fold(<?php echo $region['id']?>)" /> <input type="checkbox" name="region_id" value="<?php echo $region['id'];?>"> <?php echo $region['local_name'];?></li>
	    <?php endforeach?>
	    </ul-->
	</div>
</div>
<link type="text/css" href="<?php echo url::base(); ?>css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo url::base(); ?>js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
	// 展开子节点
	function fold(id) {
		var img = $('#region_'+id).find('img.icon_dot');
		if('1'==$('#region_'+id).attr('ajax_request')) { //已ajax请求过，直接显示/收起子节点
			if(img.attr('src') == '/images/icon_dot2.gif') { //收起
	        	if($('tr[pid="'+id+'"]').length > 0) { //如果有子节点
	            	img.attr('src','/images/icon_dot1.gif');
	            	foldchild(id,1);
	        	}
        	} else { //展开
        		if($('tr[pid="'+id+'"]').length > 0) { //如果有子节点
	            	img.attr('src','/images/icon_dot2.gif');
	            	foldchild(id,2);
	        	}
        	}
		} else { //ajax展开
			//
			param = '';
			if ($('#region_checkbox_'+id).attr('checked')) param = '&checked=checked';
			//alert('<?php echo url::base();?>site/deliverycn/sel_region?pid=' + id + param);
			$.ajax({
		    		url: '<?php echo url::base();?>site/deliverycn/sel_region?pid=' + id + param,
		            type: 'GET',
		            dataType: 'json',
		            error: function() {
		                //window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
		                alert('Error!');
		            },
		            success: function(retdat, status) {
						//ajax_block.close();
						if (retdat['code'] == 200 && retdat['status'] == 1) {
							//alert(retdat['content']);
							$('#region_'+id).after(retdat['content']);
							img.attr('src','/images/icon_dot2.gif');
							$('#region_'+id).attr('ajax_request', "1");
						} else {
							showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
						}
						//alert(retdat);
					}
		    	});
		}
	}
	function foldchild(id,type) {
        if($('tr[pid="'+id+'"]').length > 0) {
            $('tr[pid="'+id+'"]').each(function(){
            	current_id = $(this).attr('myid');
                if(type == 1){
                	/*//根据是否存在子节点设置图标
                	if($('tr[pid="'+current_id+'"]').length > 0)
                    	$(this).find('img.icon_dot').attr('src','/images/icon_dot1.gif');*/
                    foldchild(current_id,type);
                    $(this).hide();
                }else if(type == 2){
                    $(this).show();
                    if ($(this).find('img.icon_dot').attr('src')=='/images/icon_dot2.gif') { //展开子节点
                    	foldchild(current_id,type);
                    }
                } 
            });
        }
    }
    function selectregions(id) { //选中/取消所有父节点和子节点
    	//alert($("#region_checkbox_"+id).attr('checked'));
    	flag = $("#region_checkbox_"+id).attr('checked');
    	selectregions_child(id,flag);
    	selectregions_parent(id,flag)
    }
    function selectregions_child(id,flag) {
    	//子节点操作
    	$('input[name="region_id"][pid="'+id+'"]').each(function(){
    		$(this).attr('checked',flag);
    		selectregions_child($(this).attr('myid'),flag);
    	});
    }
    function selectregions_parent(id,flag) {
    	//父节点操作
    	pid = $("#region_checkbox_"+id).attr('pid');
    	f = false;
    	if (flag==false) {
    		$('input[name="region_id"][pid="'+pid+'"]').each(function(){
    			if ($(this).attr('checked')) f = true;
    		});
    		if (f) return;
    	}
    	$("#region_checkbox_"+pid).attr('checked',flag);
    	selectregions_parent(pid,flag);
    }
	
	$(document).ready(function(){

        var cs = parent.region_area_id;

        $('#check_all').click(function(){
			var ps = parent.selected_region_ids[cs];
			$('input[name="region_id"]').each(function(i, item){
				$(item).attr('checked', true);
				var region_id = $(item).val();
				if (typeof ps[region_id] == 'undefined') {
					ps[region_id] = true;
				}
			});
			parent.selected_region_ids[cs] = ps;
		});
		
		$('input[name="region_id"]').click(function(){
			var region_id  = $(this).val();
			var ps = parent.selected_region_ids[cs];
			if ($(this).attr('checked') == true) {
				if (typeof ps[region_id] == 'undefined') {
					ps[region_id] = true;
				}
			} else {
				if (typeof ps[region_id] != 'undefined') {
					delete ps[region_id];
				}
			}
			parent.selected_region_ids[cs] = ps;
		});
		
		$('#save_sel_country').click(function(){
			parent.$('#region_sel_ifm').dialog('close');
			var ps = parent.selected_region_ids[cs];
			parent.renderSelRegions(cs);
		});
		
		$('#cancel_check_all').click(function(){
			var ps = parent.selected_region_ids[cs];
			$('input[name="region_id"]').each(function(idx, item){
				var o = $(item);
				if (o.attr('checked') == true) {
					o.attr('checked', false);
					var v = o.val();
					if (typeof ps[v] != 'undefined') {
						delete ps[v];
					}
				}
			});
			parent.selected_region_ids[cs] = ps;
		});
		
		$('#cancel_sel_country').click(function(){
			parent.$('#region_sel_ifm').dialog('close');
		});
		
		/* 按钮风格 */
        $(".ui-button,.ui-button-small").button();

        var ids = parent.$("#region_ids_"+cs).attr('value');
        var pss = ids.split('-');
        for(var data in pss){
        	$('input[name="region_id"][value="'+pss[data]+'"]').attr('checked', true);
        }
       
	});
</script>