<?php defined('SYSPATH') or die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">第二步 后台生成订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**order add start**-->
            <div class="tableform">
                <form id="add_form" name="add_form" method="post" action="<?php echo url::base()?>order/order_add/add_again">
                    <input type="hidden" id="user_id" name="user_id" value="<?php echo $data['id'];?>">
                    <input type="hidden" id="email" name="email" value="<?php echo $data['email'];?>">
                    <div class="out_box">
                        <table id="good_box" cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <th class="cell span-4" style="text-align:left"><b>货品SKU</b></th>
                                <th class="cell span-5" style="text-align:left"><b>货品名称</b></th>
                                <th class="cell span-3" style="text-align:left"><b>价格</b></th>
                                <th class="cell span-3" style="text-align:left"><b>数量</b></th>
                                <th class="cell span-3" style="text-align:left"><b>现有库存量</b></th>
                                <th class="cell span-3" style="text-align:left"><b>操作</b></th>
                            </tr>
                            <?php foreach ($good_info as $good) : ?>
                            <tr class = "session_str">
                            <input name="good_id" type="hidden" value="<?php echo $good['id']?>">
                            <input id="good_store_<?php echo $good['id']?>" class="option_store" name="good_store" type="hidden" value="<?php echo ($good['store']==-1)?999:$good['store']?>">
                            <td width="20%"><?php echo $good['sku']; ?></td>
                            <td width="30%"><?php echo $good['title']; ?></td>
                            <td width="10%"><?php echo $good['price']; ?></td>
                            <td width="15%"><input id="amount_<?php echo html::specialchars($good['id'])?>" name="amount[<?php echo $good['id']?>]" type="text" class="text required digits min quantityNumlimit" size="10" value="<?php echo $good['cart_num'];?>">
                            </td>
                            <td width="15%"><?php echo ($good['store']==-1)?999:$good['store']; ?></td>
                            <td width="10%"><img style="cursor:pointer;" src="<?php echo url::base(); ?>images/icon/remove.gif" width="12" height="12" border="0"/></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>

                    <div class="list_save nore">
                        <input class="ui-button" id="add_good" type="button" value=" 添加货品 "/>
                        <input class="ui-button" id="next_step" type="button" value=" 下一步 "/>
                    </div>
                </form>
            </div>
            <!--**order add end**-->
        </div>
    </div>
</div>
<div id="good_relation_ifm" class="ui-dialog-content ui-widget-content" style="width:auto;">
    <iframe style="border:0px;width:100%;height:95%;" frameborder="0" src="" scrolling="auto"></iframe>
</div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/message.js"></script>
<script type="text/javascript">
    /* document base */
    url_base = '<?php echo url::base(); ?>';

	jQuery.validator.addMethod("quantityNumlimit", function (value, element){
	    var id = $(element).attr('id').split('_')[1];
	    var tt = $("#good_store_"+id).val();
	    var lable = true;	
	    if((value - tt) > 0){
	        lable = false;
	  	  	return false;
	    }
    	return this.optional(element) || lable;       
    } ,  "购买数量不能超过现有库存量" );

    function renderRelationGoods() {
        var relation_ids = '';
        for (var relation_id in relation_good_ids) {
            if (relation_ids != '') {
                relation_ids += '-';
            }
            relation_ids += relation_id;
        }
        
        $.ajax({
            url: url_base + 'order/relation/put?relation_ids=' + relation_ids,
            type: 'GET',
            dataType: 'json',
            success: function(retdat, status) {
                if (retdat['code'] == 200 && retdat['status'] == 1) {
                    relation_good_ids = retdat['content']['relation_ids'];
                    var trs = $('#good_box').find('tr');
                    if (trs.length > 1) {
                        for (var i = 1; i < trs.length; i ++) {
                            $(trs[i]).remove();
                        }
                    }
                    $('#good_box').append(retdat['content']['list']);
					//alert(retdat['content']['list']);
                    var t = $('#good_box').find("input.text");
                    t.each(function(){
                        $(this).rules('add', {
                        required: true,
                        digits: true,
                        quantityNumlimit:true,
                        min: 1,
                        messages: {
                            required: '产品数量不可为空',
                            digits: '产品数量必须为正整数',
                            min: '产品数量最小为1',
                            quantityNumlimit:'购买数量不能超过现有库存量'
                        }
                    	});
                    });
                } else {
                    showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
                }
            },
            error: function() {
                showMessage('请求错误', '<font color="#990000">请稍后重新尝试！</font>');
            }
        });
    }


    $(document).ready(function(){
    	relation_good_ids = {};
    	good_info = <?php echo json_encode($good_info);?>;
    	for(var data in good_info){
        	relation_good_ids[good_info[data]['id']] = true;
    	}
    	
        $('#good_relation_ifm').dialog({
            title: '添加商品',
            modal: true,
            autoOpen: false,
            height: 400,
            width: 800
        });

        $('#add_good').click(function(){
            var ifm = $('#good_relation_ifm');
            ifm.find('iframe').attr('src', url_base + 'order/relation/index');
            ifm.dialog('open');
        });

        $('#good_box').click(function(e){
            var o = $(e.target);

            if (typeof o.attr('name') != 'undefined' && o.attr('name').toUpperCase() == 'RELATION_PRODUCT_ID') {
                return true;
            }

            if (e.target.nodeName.toUpperCase() == 'IMG') {
                var p = o.parent().parent();
                var relation_id = p.find('input[name="good_id"]').val();
                if (typeof relation_good_ids[relation_id] != 'undefined') {
                    delete relation_good_ids[relation_id];
                }
                p.remove();
                return true;
            }

            return false;
        });

        $('#next_step').click(function(){
            var option = $('#good_box').find("tr.element_str");
            var opt = $('#good_box').find("tr.session_str");
            if(!option.attr('class') && !opt.attr('class')){
                showMessage('操作失败','请添加商品');
                return false;
            }
            $('#add_form').submit();
        });
        $("#add_form").validate();
    })
</script>