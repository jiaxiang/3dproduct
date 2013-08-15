<?php defined('SYSPATH') OR die('No direct access allowed.');
$list_columns = array(
    array('name'=>'名称','column'=>'local_name','class_num'=>'250')
);
?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'site/region/';?>'>地区列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li>
                    <a href="<?php echo url::base();?>site/region/add"><span class="add_pro">添加顶级地区</span></a>
                </li>
            </ul>
        </div>
        <?php if (is_array($areas) && count($areas)) {?>
        <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current();?>">
            <table  cellspacing="0">
                <thead>
                    <tr class="headings">
                        <th width="360">地区名称</th>
                        <th width="100">排序</th>
                        <th width="50">状态</th>
                        <th colspan=3>操作</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="list-line">
                    <?php foreach ($areas as $value){ ?>
                    <tr id="<?php echo $value['region_grade'];?>" name="<?php echo $value['id'];?>" class="row">
                        <td>
                            <?php if($value['childs']>0){ ?>
                            <img id="<?php echo $value['id'];?>" onclick="clickTree(this);" src="/images/collapsed.gif" 
                                style="cursor:hand;margin-left:<?php echo $value['region_grade'];?>em;"> 
                           <?php 
                                }else{
                                    echo "<img src='/images/expanded.gif' style='margin-left:".$value['region_grade']."em;'> ";
                                }
                                echo $value['local_name'];
                           ?> 
                        </td>
                        <td>
                            <div class="new_float_parent">
                                <input type="text" class="text" size="4" name="position" value="<?php echo $value['position'];?>" />
                                <div class="new_float" style="z-index:9999">
                                    <input type="text" class="text" size="4" name="order" value="<?php echo $value['position'];?>"/>
                                    <input type="button" class="ui-button-small" value="保存" name="submit_order_form" />
                                    <input type="hidden" name="id" value="<?php echo $value['id']; ?>"/>
                                    <input type="button" class="ui-button-small" value="取消" name="cancel_order_form" />
                                </div>
                            </div>
                        </td>
                        <td><img src=<?php echo $value['disabled']=='false'?'"/images/icon/accept.png" title="有效"':'"/images/icon/Disabled.png" title="无效"';?>></td>
                        <td width="100"><a href="<?php echo url::base();?>site/region/add?pid=<?php echo $value['id'];?>">添加子地区</a></td>
                        <td width="50"><a href="<?php echo url::base();?>site/region/edit?id=<?php echo $value['id'];?>">编辑</a></td>
                        <td width="50"><a onclick='delete_area("<?php echo $value['id'];?>")' href="javascript:;">删除</a></td>
                    </tr>
                    <?php } ?>
                </tbody>                
            </table>
        </form>
            <?php }else {?>
            <?php echo remind::no_rows();?>
            <?php }?>
    </div>
</div>
<img id='imgTreeC' style="display:none" src="/images/collapsed.gif" />
<img id='imgTreeO' style="display:none" src="/images/expanded.gif" />
<!--**content end**-->

<script type="text/javascript">
var default_order = '0';    
function delete_area(id){
    if(!id)return;
    var url = "<?php echo url::base();?>site/region/delete?id="+id;
    if(confirm("数据删除后无法恢复，确定要删除吗？"))top.location.href = url;
}

function setorder(){
    $('input[name=submit_order_form]').click(function(){
        var url = '<?php echo url::base();?>site/region/set_order';
        var obj = $(this).parent();
        var id = $(this).next().val();
        var order = $(this).prev().val();
        $(this).parent().hide();
        if(order == default_order){
            return false;
        }
        obj.prev().attr('disabled','disabled');
        $.ajax({
            type:'GET',
            dataType:'json',
            url:url,
            data:'id='+id+'&order='+order,
            error:function(){},
            success:
                function(retdat,status){
                obj.prev().removeAttr('disabled');
                if(retdat['status'] == 1 && retdat['code'] == 200){
                    obj.prev().attr('value',(retdat['content']['order']));
                }else{
                    alert(retdat['msg']);
                }
            }
        });
    });
    
    $('input[name=position]').focus(function(){
        $('.new_float').hide();
        default_order = $(this).val();
        $(this).next().show();
        $(this).next().children('input[name=order]').focus();
    });
    
    $('input[name=cancel_order_form]').click(function(){
        $(this).parent().hide();
    });    
}

setorder();
            
function clickTree(ipt){
  var obj = $(ipt).parent().parent();
  var pid = $(ipt).attr('id');
  var url = '/site/region?pid=' + pid;
  var obj_id = obj.attr('id')?obj.attr('id'):'';
  var org_src = $(ipt).attr('src');
  
  if(!$('.child_'+pid).attr('id')){
        $(ipt).attr('src', '/images/count_l_dot.png');
        $(ipt).attr('disabled', true);
        $.ajax({
            url:url,
            type:'GET',
            dataType:'json',
            //dataType:'html',
            //data:'',
            error:function(){},
            success:function(retdat, status){
                //document.write(retdat);
                if(retdat['status'] == 1 && retdat['code'] == 200){
                    if(retdat['content'].length>0){
                        for(i=retdat['content'].length-1; i>=0; i--){
                              var area = retdat['content'][i];
                              if(!area.local_name)continue;
                              var disable = area.disabled && area.disabled=='true'?true:false;
                              row = '<tr class="row child_'+pid+'" name="'+area.id+'" id="' + area.region_grade + '">';
                              if(area.childs && area.childs>0){
                                row += '<td>&nbsp;&nbsp;&nbsp;&nbsp;<img id="'+area.id+'" onclick="clickTree(this);" style="cursor:hand;margin-left:'+area.region_grade+'em;" src="/images/collapsed.gif">&nbsp;'+area.local_name+'</td>';
                              }else{
                                row += '<td>&nbsp;&nbsp;&nbsp;&nbsp;<span style="margin-left:'+area.region_grade+'em;">&nbsp;&nbsp;&nbsp;&nbsp;</span>'+area.local_name+'</td>';  
                              }
                              row += '<td><div class="new_float_parent">';
                              row += '<input type="text" class="text" size="4" name="position" value="'+area.position+'" />';
                              row += '<div class="new_float" style="z-index:9999">';
                              row += '<input type="text" class="text" size="4" name="order" value="'+area.position+'"/>';
                              row += '<input type="button" class="ui-button-small" value="保存" name="submit_order_form" />';
                              row += '<input type="hidden" name="id" value="'+area.id+'"/>';
                              row += '<input type="button" class="ui-button-small" value="取消" name="cancel_order_form" />';
                              row += '</div>';                       
                              row += '</div>';
                              row += '</td>';
                              row += '<td><img src="/'+(!disable?'images/icon/accept.png" title="有效"':'images/icon/Disabled.png" title="无效"')+'></td>';
                              row += '<td width="100"><a href="<?php echo url::base();?>site/region/add?pid='+area.id+'">添加子地区</a></td>';
                              row += '<td width="50"><a href="<?php echo url::base();?>site/region/edit?id='+area.id+'">编辑</a></td>';
                              row += '<td width="50"><a onclick="delete_area(\''+area.id+'\');" href="javascript:;">删除</a></td>';
                              row += '</tr>';
                              obj.after(row);
                        }
                    }
                    setorder();
                }else{
                    alert(retdat['msg']);
                }
                
                $(ipt).attr('disabled', false);
                if(org_src == $('#imgTreeC').attr('src')){
                  //obj.getNext().style.display = '';
                  $(ipt).attr('src', $('#imgTreeO').attr('src'));
                }else{
                  //obj.getNext().style.display = 'none';
                  $(ipt).attr('src', $('#imgTreeC').attr('src'));
                }
            }
        });
  }else{
      rowExpand(ipt);
  }
}
   
/**
 * 折叠分类列表
 */
function rowExpand(Obj){
    var line_obj = $(Obj).parent().parent();
    var img_src = $(Obj).attr('src');
    
    var tbl = $("#list-line")
    var line_obj_id = parseInt(line_obj.attr("id"));
    var fnd = false;
    var up_parent_id = 0;
    
    $("#list-line").find('.row').each(function(){
        var row = $(this);
        if(line_obj.attr("name") == row.attr("name")){
            if(img_src == $('#imgTreeC').attr('src')){
                line_obj.css('close','');
                $(Obj).attr('src', $('#imgTreeO').attr('src'));
            }else{
                line_obj.css('close','1');
                $(Obj).attr('src', $('#imgTreeC').attr('src'));
            }
            fnd = true;
        }else{
            if(fnd == true){
                var row_id = parseInt(row.attr("id"));
                if(row_id > line_obj_id){
                    if(row.css('display') == "none" && !line_obj.css('close')){
                        if(up_parent_id>0 && row_id>up_parent_id){
                            row.css('display',"none");
                        }else{
                            row.css('display',"");
                            up_parent_id = 0;
                        }
                        if(row.css('close')){
                            up_parent_id = row_id;
                        }
                    }else{
                        row.css('display',"none");
                    }
                }else{
                    fnd = false;
                    return true;
                }
            }
        }
    });
}
</script>