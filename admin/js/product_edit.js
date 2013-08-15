
//<![CDATA[
/* render product description */
function renderPdtDescLayoutRow(id){
    retstr = '';
    retstr += '    <table cellspacing="0" cellpadding="0" border="0" class="finderInform">';
    retstr += '      <thead>';
    retstr += '        <tr>';
    retstr += '          <th width="11%" style=" text-align:right" >标题：</th>';
    retstr += '          <th width="84%"  style=" text-align:left" ><input name="product_description_id[]" type="hidden" value="0" class="text"/><input name="group[]" type="text" size="50"></th>';
    retstr += '          <th width="5%" style=" text-align:right" >排序：<input name="position[]" type="text" class="text" size="4"></th>';
    retstr += '        </tr>';
    retstr += '      </thead>';
    retstr += '      <tbody>';
    retstr += '        <tr>';
    retstr += '          <td style=" text-align:right;font-weight:bold;" >内容：</td>';
    retstr += '          <td style=" text-align:left" colspan="2"><textarea name="content[]" id="pdt_desc_content_'+id+'" cols="75" rows="10" class="tinymce text" ></textarea></td>';
    retstr += '        </tr>';
    retstr += '      </tbody>';
    retstr += '    </table>';
    return retstr;
}
/* render product supplier new row */
function renderPdtSupRow(data){
    retstr = '';
    retstr += '  <tr id="pdtsup_row_'+data['id']+'">';
    retstr += '    <td>'+data['id']+'</td>';
    retstr += '    <td>'+data['name']+'</td>';
    retstr += '    <td>'+data['price']+'</td>';
    retstr += '    <td>'+data['position']+'</td>';
    retstr += '    <td>';
    retstr += '    	<a id="pdtsup_edit_'+data['id']+'" href="#pdtsup_edit_'+data['id']+'" class="pdtsup_edit">编辑</a>';
    retstr += '    	<a id="pdtsup_del_'+data['id']+'" href="#pdtsup_del_'+data['id']+'" class="pdtsup_del">删除</a>';
    retstr += '    	</td>';
    retstr += '  </tr>';
    return retstr;
}
/* render product attr item new row */
function renderPdtAttrRow(data){
    retstr ='';
    retstr +='<tr id="pdtattrset_row_'+data['id']+'">';
    retstr +='<td>'+data['id']+'</td>';
    retstr +='<td> ';/* add a space for nodata cond.*/
    if(typeof(data['attribute'])!='undefined' && data['attribute']!=null && data['attribute'].length>0){
        for(i=0,j=data['attribute'].length;i<j;i++){
            retstr +='<div class="spec_selected">'+ data['attribute'][i]['group_name']+'：'+data['attribute'][i]['name']+'</div>';
        }
    }
    retstr +='</td>';
    retstr +='<td><div  class="spec_selected_img">';
    if(typeof(data['attribute_image'])!='undefined' && data['attribute_image']!=null && data['attribute_image'].length>0){
        /* limit to 4 or data['attribute_image'].length */
        for(i=0,j=data['attribute_image'].length>4?4:data['attribute_image'].length;i<j;i++){
            retstr +='<span><img id="pdt_attrset_spec_img_'+data['attribute_image'][i]['id']+'" src="'+url_base+'product/tt/product_images/'+data['attribute_image'][i]['site_id']+'/'+data['attribute_image'][i]['image']+'/0.jpg" width="44" height="44"></span>';
        }
    }
    retstr +='</div></td>';
    retstr +='<td>'+data['price']+'</td>';
    retstr +='<td>'+data['weight']+'</td>';
    retstr +='<td>'+data['stock']+'</td>';
    retstr +='<td>'+data['on_sale']+'</td>';
    retstr +='<td>'+data['default_on']+'</td>';
    retstr +='<td> ';
    retstr +='<a id="pdtattset_edit_'+data['id']+'" href="#pdtattset_edit_'+data['id']+'" class="pdtattset_edit">编辑</a>';
    retstr +='<a id="pdtattset_del_'+data['id']+'" href="#pdtattset_del_'+data['id']+'" class="pdtattset_del">删除</a>';
    retstr +='</td> ';
    retstr +='</tr>';
    return retstr;
}
/* render product addision images new row*/
function renderPdtAddImgRow(data){
    retstr ='';
    retstr +='<tr id="add_img_row_'+data['id']+'">';
    retstr +='    <td><div class="pic_alt"  style="width:700px;height:auto;overflow:hidden;"> <img src="'+url_base+'product/tt/product_images/'+data['site_id']+'/'+data['image']+'/0.jpg" /> </div></td>';
    retstr +='    <td width="10%">';
    retstr +='    	<a id="add_img_del_'+data['id']+'" href="#add_img_del_'+data['id']+'" class="op_add_img_del">删除</a>';
    retstr +='		</td>';
    retstr +='  </tr>';
    return retstr;
}
/* render product images new row */
function renderPdtImgRow(data){
    retstr ='';
    retstr +='<tr id="pdt_img_row_'+data['id']+'">';
    retstr +='<td><img src="'+url_base+'product/tt/product_images/'+data['site_id']+'/'+data['image']+'/0.jpg" width="40" height="40" /></td>';
    //retstr +='<td><img src="'+url_base+'product/tt/product_images/'+data['site_id']+'/'+data['image_big']+'/0.jpg" width="40" height="40"></td>';
    retstr +='<td><input name="description['+data['id']+']" type="text" class="text" value="'+(data['description']=='null'?'':data['description'])+'" size="35" >';
    retstr +='</td>';
    retstr +='<td><input name="position['+data['id']+']" type="text" class="text" value="'+data['position']+'" size="5" ></td>';
    retstr +='<td><input name="cover['+data['id']+']" type="radio" value="1" '+(data['cover']==1?'checked="checked"':'')+'>';
    retstr +='有';
    retstr +='<input name="cover['+data['id']+']" type="radio" value="0" '+(data['cover']==0?'checked="checked"':'')+'>';
    retstr +='否</td>';
    retstr +='<td><img src="'+url_base+'images/icon_del.gif" alt="删除" class="operater op_pdtimgrow_del" id="op_pdtimg_del_'+data['id']+'" style="cursor:pointer;"> </td>';
    retstr +='</tr>';
    return retstr;
}
/* render product attribute set images row */
function renderPdtAttrImgSetRow(data){
    retstr ='';
    retstr +='    <label class="imgs">';
    retstr +='    <input type="checkbox" name="product_image[]" class="act_product_image" value="'+data['id']+'" >';
    retstr +='    <img src="'+url_base+'product/tt/product_images/'+data['site_id']+'/'+data['image']+'/0.jpg" width="40" height="40" class="d_img">';
    retstr +='    </label>';
    return retstr;
}
/* render current virtual category */
function renderCurVirCateCompTpl(catearr){
    retstr = '';
    retstr += '<select name="vircate[]" class="qavircate">';
    arrlen = catearr.length;
    if(arrlen>0){
        for(i=0;i<arrlen;i++){
            retstr += '<option value="'+catearr[i]['id']+'" >'+catearr[i]['name']+'</option>';
        }
    }
    retstr += '<option value="">-[remove]-</option>';
    retstr += '</select>';
    return retstr;
}
/* render select options */
function renderOption(data){
    retstr = '';
    datalen = data.length;
    if(datalen>0){
        for(i=0;i<datalen;i++){
            retstr += '<option value="'+data[i]['id']+'" >'+data[i]['name']+'</option>';
        }
    }
    return retstr;
}
/* render attribute item rows */
function renderAttrItemRow(data){
    retstr = '';
    retstr +='<tr>';
    retstr +='<th style="width: 15%;">'+data['grp_name']+'</th>';
    retstr +='<td>'+data['itm_name']+'</td>';
    retstr +='<td><input type="hidden" class="op_pdt_attr_item" name="pdt_attr_item[]" value="'+(data['grp_val']+'-'+data['itm_val'])+'" /><img src="/images/icon_del.gif" alt="删除" width="13" height="12" class="op_pdt_attr_item_del operater" style="cursor: pointer;"></td>';
    retstr +='</tr>';
    return retstr;
}
/* render batch attribute group rows */
function renderBatchAttrGroup(data,itemhtml){
    retstr = '';
    retstr +='';
    retstr +='<div class="division" id="op_bat_grp_attr_layout_'+data['grp_val']+'">';
    retstr +='<table cellspacing="0" cellpadding="0" border="0" width="100%" class="finderInform">';
    retstr +='  <thead>';
    retstr +='    <tr>';
    retstr +='      <th>商品规格组</th>';
    retstr +='      <th>商品规格值</th>';
    retstr +='      <th>价格增长</th>';
    retstr +='      <th>重量增长</th>';
    retstr +='      <th>操作</th>';
    retstr +='    </tr>';
    retstr +='  </thead>';
    retstr +='  <tbody class="op_bat_attr_container" id="op_bat_attr_row_container_'+data['grp_val']+'">';
    retstr +='  <thead>';
    retstr +=''+itemhtml;
    retstr +='  </thead>';
    retstr +='</table>';
    retstr +='</div>';
    return retstr;
}
/* render batch attribute group item rows */
function renderBatchAttrGroupItemRow(data){
    idstr = data['grp_val']+'-'+data['itm_val'];
    retstr = '';
    retstr +='    <tr id="bat_pdt_grp_itm_row_'+idstr+'">';
    retstr +='      <td>'+data['grp_name']+'</td>';
    retstr +='      <td>'+data['itm_name']+'</td>';
    retstr +='      <td><input name="batch_price['+data['itm_val']+']" type="text" class="text" size="5" value="0"></td>';
    retstr +='      <td><input name="batch_weight['+data['itm_val']+']" type="text" class="text" size="5" value="0"></td>';
    retstr +='      <td><input type="hidden" class="op_batch_pdt_attr_item" name="batch_pdt_attr_item[]" value="'+idstr+'" />';
    retstr +='      <img src="/images/icon_del.gif" id="op_bat_pdt_grp_itm_del_'+idstr+'" alt="删除" width="13" height="12" class="op_batch_pdt_attr_item_del operater" style="cursor: pointer;"></td>';
    retstr +='    </tr>';
    return retstr;
}

/* render product feature rows */
function renderPdtFetRow(data){
    retstr = '';
    datalen = data.length;
    if(datalen>0){
        for(i=0;i<datalen;i++){
            retstr +='<tr>';
            retstr +='<th width="20%">'+data[i]['fet_grp_name']+'</th>';
            retstr +='<td>&nbsp;';
            if(typeof(data[i]['fet_grp_assoc'])!='undefined' && data[i]['fet_grp_assoc'].length>0){
                retstr +='<select name="feature['+data[i]['fet_grp_id']+']" class="text">';
                retstr +='<option value="" selected="selected">请选择附加属性值</option>';

                assoc_curr = data[i]['fet_grp_assoc'];
                for(k=0,j=assoc_curr.length;k<j;k++){
                    retstr +='<option value="'+assoc_curr[k]['id']+'"';
                    if(assoc_curr[k]['selected'])
                    {
                        retstr +='selected="selected"';
                    }
                    retstr +='>'+assoc_curr[k]['name']+'</option>';
                }
                retstr +='</select>';
            }
            retstr +='</td>';
            retstr +='</tr>';
        }
    }
    return retstr;
}
/* render accessory/relative search options */
function renderSearchOption(data){
    retstr = '';
    if(data!=null && data!=[] && data!={}){
        for(k in data){
            retstr +='<option value="'+k+'">'+data[k]+'</option>';
        }
    }
    return retstr;
}
//]]>
