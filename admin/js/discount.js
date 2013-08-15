checkedData = new Array();

//记录选择与不选择的状态
function check_record_unrecord(thing,check)
{
	//record
	if(checkedData[thing] == undefined){
		checkedData[thing] = new Array();
	}
	$(':checkbox').unbind().live('click',function(){
		if($(this).attr('name') == check){
		    if($(this).attr('checked')){
		        $(":checkbox[name="+thing+"]").attr('checked',true);
		    }else{
		        $(":checkbox[name="+thing+"]").attr('checked',false);
		    }
		}
		if($(this).attr('checked') == true){
			if($(this).attr('name') == thing){
				checkedData[thing][String($(this).val())] = $(this).parent().parent();
			}
			if($(this).attr('name') == check){
				var che = $(":checkbox[name="+thing+"]");
				for(var i=0;i<che.length;i++){
					checkedData[thing][String(che.eq(i).val())] = che.eq(i).parent().parent();
				}
			}
		}else{
			if($(this).attr('name') == thing){
				if(checkedData[thing][String($(this).val())] != undefined){
					checkedData[thing][String($(this).val())] = undefined;
				}
			}
			if($(this).attr('name') == check){
				var che = $(":checkbox[name="+thing+"]");
				for(var i=0;i<che.length;i++){
					if(checkedData[thing][String(che.eq(i).val())] != undefined){
						checkedData[thing][String(che.eq(i).val())] = undefined;
					}
				}
			}
		}
	});
	
	//unrecord
	$('a[name="deleted'+thing+'"]').unbind().live('click',function(){
		//when delete checkbox delete checkedData
		var delval = $(this).next().val();
		$(this).parent().parent().remove();
		
		if(checkedData[thing][String(delval)] != undefined){
			checkedData[thing][String(delval)] = undefined;
		}
		
		//uncheck the checkbox in local page
		$(":checkbox[name="+thing+"][value="+delval+"]").attr('checked',false);
		return false;
	});
}

//清除checkbox框的状态
function uncheckbox(thing)
{
	 $(":checkbox[name="+thing+"]").attr('checked',false);
}

//根据返回的数据生成对应的表格
function generate_table(request_data,thing,check,field)
{
	var options = '<tr ><th style="text-align:left;width:20px;padding-left:5px"><input type="checkbox" name="'+check+'" id="'+check+'" /></th>';
	var lengthzd=0;
	for(var key in field)
	{
		lengthzd++;
	}
	for(var key in field)
	{
		options +='<th style="text-align:left;width:'+(380/lengthzd)+'px;">'+key+'</th>';
	}
	options +='</tr>';
	var optionsContent = '';
	var page = request_data.page;
	var count = request_data.count;
	request_data = request_data.content;
	for (var i = 0; i < request_data.length; i++) {
		var ische = '';
		//默认已经选择过的，任然保持选择状态   现在不用
      	if(checkedData[thing][String(request_data[i].id)] != undefined){
       		ische = 'checked = "true"';
        }
		optionsContent += '<tr style="text-align:left;"> <td style="width:20px;"><input name="'+thing+'" type="checkbox" value="'+request_data[i].id+'" '+ische+'/></td>';
		for(var key in field){
			if(request_data[i][field[key]] == null || request_data[i][field[key]] == ''){
				request_data[i][field[key]] = '&nbsp;';
			}
			optionsContent += '<td style="width:'+(580/lengthzd)+'px;">'+request_data[i][field[key]]+'</td>';
		}
		optionsContent += '</tr>';
	}
	if(optionsContent.length == 0)
	{
		optionsContent = '<tr><td colspan="'+(lengthzd*1+1)+'">没有找到相关信息</td></tr>';
	}
	options += optionsContent;
	var back = page*1-1;
	var forward = page*1+1;
	if(page==1){
		back = page;
	}
	if(page>=count){
		forward = count;
		page = count;
	}
	options += '<tr style="text-align:right;text-decoration:none;"><td colspan="'+(lengthzd*1+1)+'">'+page*1+'/'+count+' <a name="'+thing+'_page" href="1" rev="1">首页</a> <a name="'+thing+'_page" rev="'+back+'" href="'+back+'">上一页</a>';
	for(var i = 0;i<5;i++){
		if(page*1-2>0){
			
			var cla = 'sysiconBtnNoIcon borderup';
			var rev = page*1+i-2;
			if(rev>count) break;
			if(i == 2) cla = "bordercurrent";
			options +=' &nbsp;<a name="'+thing+'_page" class="'+cla+'" rev="'+rev+'" href="'+rev+'"> '+rev+' </a> &nbsp;';
			
		}else if(page*1-1>0){
			var cla = 'sysiconBtnNoIcon borderup';
			var rev = page*1+i-1;
			if(rev>count) break;
			if(i == 1) cla = "bordercurrent";
			options +=' &nbsp;<a name="'+thing+'_page" class="'+cla+'" rev="'+rev+'" href="'+rev+'"> '+rev+' </a> &nbsp;';
		}else{
			var cla = 'sysiconBtnNoIcon borderup';
			var rev = page*1+i;
			if(rev>count) break;
			if(i == 0) cla = "bordercurrent";
			options +=' &nbsp;<a name="'+thing+'_page" class="'+cla+'" rev="'+rev+'" href="'+rev+'"> '+rev+' </a> &nbsp;';
		}
		
	}
	options += '<a name="'+thing+'_page" rev="'+forward+'" href="'+forward+'">下一页</a> <a name="'+thing+'_page" href="'+count+'" rev="'+count+'">尾页</a></td></tr>';
	return options;
}

//前台后台传输显示数据
function tranData(url ,type,keyword ,check,thing,field,table)
{
    //if(keyword) document.write(url + "?type=" + type + "&keyword=" + keyword);
	check_record_unrecord(thing,check);
    $.getJSON(url, {type: type, keyword: keyword}, function(request_data){
		var options = generate_table(request_data,thing,check,field);
        $("#"+table).html(options);
        $('a[name="'+thing+'_page"]').css('text-decoration','none');
    });
}

//开启dialog
function opendialog(dialog ,thing,toField ,related_ids)
{
	$("#"+dialog).dialog({
	    autoOpen: false,
	    height: 420,
	    width: 800,
	    modal: true,
	    stack: true,
	    draggable: true,
	    resizable: true,
	    buttons: {
	        '取消': function() {
	            $(this).dialog('close');
	        },
	        '确定': function() {
	        		var num = 0;
	        		var tmp = Array();
	        		for(k in checkedData[thing]){
	        			if(parseInt(k) && checkedData[thing][k]!= undefined){
	        				tmp[num] = k;
		        			num++;
	        			}
	        		}
	        		
		            var i = 0;
		            var tr = '';
		            while(i<num)
		            {
		                i++;
		                var haveChecked = $('#'+toField+' :hidden');
		                var j = 0;
		                var isHave = false;
		                var chevalue = checkedData[thing][tmp[i-1]].find(':checkbox').val();
		                while(haveChecked.eq(j).val() != undefined)
		                {
		                    j++;
		                    if(haveChecked.eq(j-1).val() == chevalue){
		                        isHave = true;
		                        continue;
		                    }
		                }
		                if(isHave){
		                    continue;
		                }
		                var children = checkedData[thing][tmp[i-1]].children();
		                tr += '<tr><td><a href="deleted'+thing+'" name="deleted'+thing+'">X</a><input type="hidden" name="'+related_ids+'[]" value="'+chevalue/*selected.eq(i-1).val()*/+'"/></td>';
		                for(var j=1;j<children.length;j++){
		                    tr += '<td>'+children.eq(j).html()+'</td>';
		                }
		                tr += '</tr>';
		            }
		        $('#'+toField).append(tr);
				//uncheckbox(thing);
				$(this).dialog('close');
                //$("#productSearchbtn").click();
	        }
	    }
	});
}

/**
 * fold and unfold the children of a categories.
 */
function fold(obj,dialogform)
{
	var categoryId = obj.parent().prev().children().eq(0).val();
	var children = category_children(categoryId,dialogform);
	if(obj.attr('src') == '/images/icon_dot2.gif'){
		for(var i = 0;i<children.length;i++){
			children[i].parent().parent().hide();
		}
		obj.attr('src','/images/icon_dot1.gif');
	}else{
		
		for(var i = 0;i<children.length;i++){
			children[i].parent().next().children().eq(0).attr('src','/images/icon_dot2.gif');
			children[i].parent().parent().show();
		}
		obj.attr('src','/images/icon_dot2.gif');
	}
}
/**
 * select  all the children of a category 
 */
function category_children(categoryId,dialogform)
{
	var pids = $('#'+dialogform+' :hidden[name=parentId]');
	var length = pids.length;
	var children = new Array();
	var num = 0;
	for(var i = 0;i<length;i++){
		if(pids.eq(i).val() == categoryId){
			children[num++] = pids.eq(i);
		}
	}
	var childrenChi = new Array();
	for(var i = 0;i<children.length;i++){
		 childrenChi[i] = category_children(children[i].prev().val(),dialogform);
		
	}
	for(var i = 0;i<childrenChi.length;i++){
		if(childrenChi[i].length == 0){
			continue;
		}
		for(var j = 0;j<childrenChi[i].length;j++){
			children[num++] = childrenChi[i][j];
		}
	}
	return children;
}
/**
 * when check a parent category, children categories will be checked.
 */
function checked(categories,checkAll,dialogform)
{
	$('#'+dialogform+' :checkbox').unbind().bind('click',function(){
		var checked = $(this).attr('checked');
		if($(this).attr('name') == categories){
			var children = category_children($(this).val(),dialogform);
		    for(var i=0;i<children.length;i++){
		        children[i].prev().attr('checked',checked);
		    }
		}
		if($(this).attr('name') == checkAll){
			var checkbox = $(':checkbox[name='+categories+']');
			checkbox.attr('checked',checked);
		}
	});	
}