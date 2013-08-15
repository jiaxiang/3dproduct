/**
相关商品处理

fromid:源list的id.
toid:目标list的id.

isAll参数(true或者false):是否全部移动或添加
isTwoWay参数(true或者false):是否双向关联
*/ 
jQuery.relativelist = function(fromid,toid,isAll,isTwoWay) { 
        if(isAll == true) { //全部移动 
            $("#"+fromid+" option").each(function() { 
                //将源list中的option添加到目标list,当目标list中已有该option时不做任何操作. 
                if(!($("#"+toid+":not(:has(option[value="+$(this).val()+"]))").val()=='undefined')){
                	if(isTwoWay){
                		$(this).text($(this).text()+' [双向关联]')
                			.attr('temp',1) 
                			.appendTo($("#"+toid+":not(:has(option[value="+$(this).val()+"]))")); 		
                	}else{
                		$(this).text($(this).text().replace(' [双向关联]','')) 
                			.attr('temp',0) 
                			.appendTo($("#"+toid+":not(:has(option[value="+$(this).val()+"]))")); 
                	}
                }
            }); 
            $("#"+fromid).empty();  //清空源list 
        } 
        else if(isAll == false) { 
            $("#"+fromid+" option:selected").each(function() { 
                //将源list中的option添加到目标list,当目标list中已有该option时不做任何操作. 
                if(!($("#"+toid+":not(:has(option[value="+$(this).val()+"]))").val()=='undefined')){
                	if(isTwoWay){
                		$(this).text($(this).text()+' [双向关联]')
                			.attr('temp',1) 
                			.appendTo($("#"+toid+":not(:has(option[value="+$(this).val()+"]))")); 		
                	}else{
                		$(this).text($(this).text().replace(' [双向关联]','')) 
                			.attr('temp',0) 
                			.appendTo($("#"+toid+":not(:has(option[value="+$(this).val()+"]))")); 
                	}
                }
                //目标list中已经存在的option并没有移动,仍旧在源list中,将其清空. 
                if($("#"+fromid+" option[value="+$(this).val()+"]").length > 0) { 
                    $("#"+fromid).get(0) 
                    .removeChild($("#"+fromid+" option[value="+$(this).val()+"]").get(0)); 
                } 
            }); 
        } 
}; 


/**
配件商品处理

fromid:源list的id.
toid:目标list的id.

isAll参数(true或者false):是否全部移动或添加
isTwoWay参数(true或者false):是否双向关联
*/ 
jQuery.accessorylist = function(fromid,toid,isAll,isAccessory,discount_type,discount_value) { 
        if(isAll == true) { //全部移动 
            $("#"+fromid+" option").each(function() { 
                //将源list中的option添加到目标list,当目标list中已有该option时不做任何操作. 
                if(!($("#"+toid+":not(:has(option[value="+$(this).val()+"]))").val()=='undefined')){
                	if(isAccessory){
                		if(discount_type==1&&discount_value!=''&&discount_value!='5 : 5%,$5'){
                			temp	= ' [打折：'+parseInt(discount_value)+'%]';
                		}else if(discount_type==2&&discount_value!=''&&discount_value!='5 : 5%,$5'){
                			temp	= ' [打折：$'+parseInt(discount_value)+']';
                		}else {
                			temp	= '';
                		}
                		$(this).attr('real_name',$(this).text()) 
                			.text($(this).text()+temp)
                			.attr('discount_type',discount_type) 
                			.attr('discount_value',discount_value)
                			.appendTo($("#"+toid+":not(:has(option[value="+$(this).val()+"]))")); 		
                	}else{
                		real_name		= $(this).attr('real_name');
                		if(real_name==undefined){
                			$(this).appendTo($("#"+toid+":not(:has(option[value="+$(this).val()+"]))")); 
                		}else{
		            		$(this).text(real_name)
		            			.appendTo($("#"+toid+":not(:has(option[value="+$(this).val()+"]))")); 
                		}
                	}
                }
            }); 
            $("#"+fromid).empty();  //清空源list 
        } 
        else if(isAll == false) { 
            $("#"+fromid+" option:selected").each(function() { 
                //将源list中的option添加到目标list,当目标list中已有该option时不做任何操作. 
                if(!($("#"+toid+":not(:has(option[value="+$(this).val()+"]))").val()=='undefined')){
                	if(isAccessory){
                		if(discount_type==1&&discount_value!=''&&discount_value!='5 : 5%,$5'){
                			temp	= ' [打折：'+parseInt(discount_value)+'%]';
                		}else if(discount_type==2&&discount_value!=''&&discount_value!='5 : 5%,$5'){
                			temp	= ' [打折：$'+parseInt(discount_value)+']';
                		}else {
                			temp	= '';
                		}
                		$(this).attr('real_name',$(this).text()) 
                			.text($(this).text()+temp)
                			.attr('discount_type',discount_type) 
                			.attr('discount_value',discount_value)
                			.appendTo($("#"+toid+":not(:has(option[value="+$(this).val()+"]))")); 		
                	}else{
                		real_name		= $(this).attr('real_name');
                		if(real_name==undefined){
                			$(this).appendTo($("#"+toid+":not(:has(option[value="+$(this).val()+"]))")); 
                		}else{
		            		$(this).text(real_name)
		            			.appendTo($("#"+toid+":not(:has(option[value="+$(this).val()+"]))")); 
                		}
                	}
                }
                //目标list中已经存在的option并没有移动,仍旧在源list中,将其清空. 
                if($("#"+fromid+" option[value="+$(this).val()+"]").length > 0) { 
                    $("#"+fromid).get(0) 
                    .removeChild($("#"+fromid+" option[value="+$(this).val()+"]").get(0)); 
                } 
            }); 
        } 
}; 







//关联商品处理
$(document).ready(function() { 
    $("#select_relative_1").dblclick(function() {$.relativelist("select_relative_1","select_relative_2",false,false);return false;}); 
    $("#select_relative_2").dblclick(function() {$.relativelist("select_relative_2","select_relative_1",false,false);return false;}); 
    $("#moveright").click(function() {
    	if($("#is_single_1").attr("checked")){
    		$.relativelist("select_relative_1","select_relative_2",false,false);
    	}else{
    		$.relativelist("select_relative_1","select_relative_2",false,true);
    	}
    	return false;
    }); 
    $("#moverightall").click(function() {
    	if($("#is_single_1").attr("checked")){
    		$.relativelist("select_relative_1","select_relative_2",true,false);
    	}else{
    		$.relativelist("select_relative_1","select_relative_2",true,true);
    	}
    	return false;
    }); 
    $("#moveleft").click(function() {$.relativelist("select_relative_2","select_relative_1",false,false);return false;}); 
    $("#moveleftall").click(function() {$.relativelist("select_relative_2","select_relative_1",true,false);return false;}); 
    $("#edit_form_relative").submit(function() {
    	relative_array	= [];
    	key=0;
	    $("#select_relative_2 option").each(function() { 
	    	
	    	relative_array[key]				= {};
	    	//alert($(this).attr('temp'));
	    	if($(this).attr('temp')==undefined){
	    		relative_array[key]['temp']		= 0;
	    	}else{
	    		relative_array[key]['temp']		= 1;
	    	}
	    	
	    	relative_array[key]['value']	= $(this).val();

	    	key++;
	    }); 
	    //console.log(relative_array);
	    $("#relative_products").val($.json.encode(relative_array));
    	return true;
    }); 
    
}); 

//商品配件处理
$(document).ready(function() { 
    
    $("#select_accessory_1").dblclick(function() {
    	discount_type	= $("#accessory_discount_type").val();
    	discount_value	= $("#accessory_discount_value").val();
    	$.accessorylist("select_accessory_1","select_accessory_2",false,true,discount_type,discount_value);
    	return false;
    }); 
    $("#select_accessory_2").dblclick(function() {
    	discount_type	= $("#accessory_discount_type").val();
    	discount_value	= $("#accessory_discount_value").val();
    	$.accessorylist("select_accessory_2","select_accessory_1",false,false,discount_type,discount_value);
    	return false;
    }); 
    $("#accessory_moveright").click(function() {
    	discount_type	= $("#accessory_discount_type").val();
    	discount_value	= $("#accessory_discount_value").val();
    	$.accessorylist("select_accessory_1","select_accessory_2",false,true,discount_type,discount_value);
    	return false;
    }); 
    $("#accessory_moverightall").click(function() {
    	discount_type	= $("#accessory_discount_type").val();
    	discount_value	= $("#accessory_discount_value").val();
    	$.accessorylist("select_accessory_1","select_accessory_2",true,true,discount_type,discount_value);
    	return false;
    }); 
    $("#accessory_moveleft").click(function() {
    	discount_type	= $("#accessory_discount_type").val();
    	discount_value	= $("#accessory_discount_value").val();
    	$.accessorylist("select_accessory_2","select_accessory_1",false,false,discount_type,discount_value);
    }); 
    $("#accessory_moveleftall").click(function() {
    	discount_type	= $("#accessory_discount_type").val();
    	discount_value	= $("#accessory_discount_value").val();
    	$.accessorylist("select_accessory_2","select_accessory_1",true,false,discount_type,discount_value);
    }); 
    $("#edit_form_accessory").submit(function() {
    	accessory_array	= [];
    	key=0;
	    $("#select_accessory_2 option").each(function() { 
	    	accessory_array[key]				= {};
	    	if($(this).attr('discount_type')==undefined){
	    		accessory_array[key]['discount_type']		= 0;
	    		accessory_array[key]['discount_value']		= 0;
	    	}else{
	    		accessory_array[key]['discount_type']		= $(this).attr('discount_type');
	    		accessory_array[key]['discount_value']		= $(this).attr('discount_value');
	    	}
	    	accessory_array[key]['value']		= $(this).val();
	    	key++;
	    }); 
	    //console.log(accessory_array);
	    $("#accessory_products").val($.json.encode(accessory_array));
    	return true;
    }); 
    
}); 


