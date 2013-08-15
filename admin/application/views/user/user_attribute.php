<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_content">
    <form method="post" name="add_form" action="/user/user_attribute/set_order">
        <div class="newgrid">

            <div class="newgrid_tab fixfloat">
                <ul>
                    <li class="on">会员注册项</li>
                </ul>
            </div>
            <div class="newgrid_top">
                <ul class="pro_oper">
                    <li>
                        <a href="/user/user_attribute/add"><span class="add_pro">添加注册项</span></a>
                    </li>
                </ul>
            </div>
            <table cellspacing="0" class="table_mover">
                <thead >
                    <tr class="headings">
                        <th width="100">操作</th>
                        <th width="300">注册项名称</th>
                        <th width="100">注册项类型</th>
                        <th width="50">显示</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $class = array(
                    'text'=>'text',
                    'select'=>'text',
                    'radio'=>'',
                    'checkbox'=>'sel',
                
                );
                
                ?>
                    <?php foreach($user_attributes as $user_attribute):?>
                    <tr>
                        <td width="100">
                            <?php if(!$user_attribute['attribute_default']):?><a href="/user/user_attribute/edit?attribute_id=<?php echo $user_attribute['id']?>"> 编辑 </a> <?php endif;?>&nbsp;
                            <?php if(!$user_attribute['attribute_default']):?><a href="/user/user_attribute/delete?attribute_id=<?php echo $user_attribute['id']?>" onclick="javascript:return confirm('若删除了本选项，则无法恢复。会员所填写的本注册项的信息也将丢失!')"> 删除</a>  <?php endif;?>
                        </td>
                    
                        <td width="300">
                            <input name="setorder[]" value="<?php echo $user_attribute['id'];?>" type="hidden" />
                            <span style="width:100px; display:inline-block;"> <?php echo $user_attribute['attribute_name'];?></span> <?php //echo user_attribute::show_view($user_attribute,$class); ?>
                        </td>
                        <td width="100">
                                <?php if($user_attribute['attribute_default']) echo '系统默认项';
                                else echo kohana::config('user_attribute_type.attribute.'.$user_attribute['attribute_type'].'.name');
                                ?>
                        </td>

                        <td width="50">
                            <img <?php if($user_attribute['attribute_show']) {?>src="/images/icon/accept.png"<?php } else {?> src="/images/icon/cancel.png"<?php }?> class="active_img" style="cursor:pointer;"/>
                            <input type="hidden" name="attribute_id"  value="<?php echo $user_attribute['id']?>" />
                        </td>
                        <td></td>
                    </tr>
                    <?php endforeach;?>
                </tbody>

            </table>

        </div>
        <div style="text-align:center;"><input type="submit" name="set_order" id="set_order" value="保存" class="ui-button"/></div>
    </form>
</div>
<div id="order_alarm" name="order_alarm" style="display:none; position:absolute; z-index:999; padding:5px; color:#264409; background:#E6EFC2; border:2px solid #C6D880;">
    您可以拖拽注册项到相应的位置，点击【保存】后生效
</div>
<!-- header_content(end) -->
<script type="text/javascript">
    $(document).ready(function(){
        $("#order_alarm").bgiframe();
        $('.active_img').live('click',function(){
            var obj = $(this);
            var attribute_id = $(this).parent().children("input[name='attribute_id']").val();
            obj.attr('disabled',true);
            var url='/user/user_attribute/show_toggle';
            $.ajax({
                type:'GET',
                dataType:'json',
                url:url,
                data:'attribute_id='+attribute_id,
                error:function(){},
                success:
                    function(retdat,status){
                    $('#attribute_show').removeAttr('disabled');
                    if(retdat['status'] == 1 && retdat['code'] == 200)
                    {
                        if(retdat['content']['attribute_show']){
                            obj.attr('src', '/images/icon/accept.png');
                        }else{
                            obj.attr('src', '/images/icon/cancel.png');
                        }
                    }else{
                        alert(retdat['msg']);
                    }
                }
            });
        });
        $('tbody tr').each(function(){
            $(this).children().eq(1).bind('mouseover',function(){
                //alert(window.event.pageX);
                $(this).css('cursor','move');
                $(this).mousemove(function(e){
                    left = e.pageX+20;
                    top = e.pageY-20;
                });
                a = window.setInterval('move()',10);
                $("#order_alarm").show();
            });

            $(this).children().eq(1).bind('mouseout',function(){
                $("#order_alarm").hide();
                if(a != undefined ) window.clearInterval(a);
            });
        });
        $("#order_alarm").mouseover(function(e){
            left = e.clientX+20;
            top = e.clientY-20;
            $("#order_alarm").css('left',left);
            $("#order_alarm").css('top',top);
        });
        $("tbody").sortable();
        $("tbody").disableSelection();
    });
    var left = 0;
    var top = 0;
    function move()
    {
        $("#order_alarm").css('left',left);
        $("#order_alarm").css('top',top);
    }
</script>