<div class="col_main">
     <div class="public_right public">
	     <form id="search_form" name="search_form" method="GET" action="<?php echo url::base() . url::current();?>">
	     		<span style="float:left">
	     		<label>国家名称:</label>
                <input class="text" type="text" name="search_value" value="<?php isset($where_view['search_value']) && !empty($where_view['search_value']) &&print($where_view['search_value']); ?>" />
                <input class="text" type="hidden" name="search_type" value="name" />
                <input class="text" type="hidden" name="country_ids" value="<?php isset($country_ids) && !empty($country_ids) && print($country_ids)?>" />
                <input type="submit" class="ui-button-small" name="searchbtn" value="搜索" class="btn_text"></span>
                <span style="color:#035383;display:inline;float:right">一共<?php echo $whole_count ?>条记录，被选择了<?php echo $left_count;?>条记录。</span>
		 </form>
		 
     </div>
     <div class="out_box">
		<table width="100%" cellspacing="0" class="table_overflow">
		<thead>
	    <tr class="headings">
	        <th width="40"></th>
	        <th width="65">国家代码</th>
	        <th>国家名称</th>
	        <th width="40"></th>
	        <th width="65">国家代码</th>
	        <th>国家名称</th>
	    </tr>
		</thead>
	    <tbody>
	    <tr>
	    <?php $i = 0;?>
	    <?php foreach($countries as $key=>$value):?>	    
	        <td class="a_center"><input type="checkbox" name="country_id" value="<?php echo $value['id'];?>"></td>
	        <td><?php echo $value['iso_code'];?></td>
	        <td><?php echo $value['name'];?></td>
	        <?php if($i%2 == 1) :?>
	        </tr><tr>
			<?php endif;?>
	    <?php $i=$i+1;?>
	    <?php endforeach?>
	    </tr>
	    <!--     
	    <tr>
	        <td colspan="3" id="pager">
	        <div class="Turnpage_rightper"> <?php //echo view_tool::per_page(); ?>
				 <div class="b_r_pager"> <?PHP //echo $this->pagination->render('opococ'); ?> </div>
			</div>
	        </td>
	    </tr>
	    --> 
	    </tbody>                
	    </table>
	</div>
	<div class="list_save">
         <input id="save_sel_country" type="button" class="ui-button" value="  确定   "/>
         <input id="check_all" type="button" class="ui-button" value="  全选   "/>
         <input id="cancel_sel_country" type="button" class="ui-button" value="  清空   "/>
    </div>	
</div>
<link type="text/css" href="<?php echo url::base(); ?>css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo url::base(); ?>js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){

        var cs = parent.country_area_id; 

        $('#check_all').click(function(){
			var ps = parent.selected_country_ids[cs];
			$('input[name="country_id"]').each(function(i, item){
				$(item).attr('checked', true);
				var country_id = $(item).val();
				if (typeof ps[country_id] == 'undefined') {
					ps[country_id] = true;
				}
			});
			parent.selected_country_ids[cs] = ps;
		});      
		
		$('input[name="country_id"]').click(function(){
			var country_id  = $(this).val();
			var ps = parent.selected_country_ids[cs];
			if ($(this).attr('checked') == true) {
				if (typeof ps[country_id] == 'undefined') {
					ps[country_id] = true;
				}
			} else {
				if (typeof ps[country_id] != 'undefined') {
					delete ps[country_id];
				}
			}
			parent.selected_country_ids[cs] = ps;
		});
		
		$('#save_sel_country').click(function(){
			parent.$('#country_sel_ifm').dialog('close');
			var ps = parent.selected_country_ids[cs];
			parent.renderSelCountrys(cs);
		});
		
		$('#cancel_sel_country').click(function(){
			var ps = parent.selected_country_ids[cs];
			$('input[name="country_id"]').each(function(idx, item){
				var o = $(item);
				if (o.attr('checked') == true) {
					o.attr('checked', false);
					var v = o.val();
					if (typeof ps[v] != 'undefined') {
						delete ps[v];
					}
				}
			});
			parent.selected_country_ids[cs] = ps;
		});
		
		/* 按钮风格 */
        $(".ui-button,.ui-button-small").button();

        var ids = parent.$("#country_ids_"+cs).attr('value');
        var pss = ids.split('-');
        for(var data in pss){
        	$('input[name="country_id"][value="'+pss[data]+'"]').attr('checked', true);
        }
       
	});
</script>