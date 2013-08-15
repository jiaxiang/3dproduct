<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<?php $good_list = $return_struct['content']['assoc']; ?>
<!--**content start**-->
<div class="col_main">
    <div class="public_right public">
        <form id="search_form" name="search_form" method="GET" action="<?php echo url::base() . url::current();?>">
            <input type="hidden" name="adv_bar" id="adv_bar_nor" value="0" />
            <p> 搜索:
                <label>
                    <select name="type" id="select_type" class="text">
                        <option value="sku" <?php if (isset($request_data['type']) && $request_data['type'] == 'sku') {?>selected<?php }?>>货品SKU</option>
                        <option value="title" <?php if (isset($request_data['type']) && $request_data['type'] == 'title') {?>selected<?php }?>>货品名称</option>
                    </select>
                </label>
                <label>
                    <input class="text" type="text" name="keyword" id="keyword2" value="<?php isset($request_data['keyword']) && !empty($request_data['keyword']) && print($request_data['keyword']);?>" />
                </label>
                <label>
                    <input type="submit" class="ui-button-small" name="searchbtn" value="搜索" class="btn_text">
                </label>
        </form>
    </div>
    <!--	<div class="public_title title_h3"></div>	-->
    <form id="product_relations" action="/order/relation/put" method="post">
        <div class="division" style="width:96%">
            <table id="product_relation_box" cellspacing="0" cellpadding="0" border="0" width="100%" style="border:1px solid #efefef;">
                <tr>
                    <th style="text-align:left"><input type="checkbox" id="check_all"></th>
                    <th style="text-align:left"><b>货品SKU</b></th>
                    <th style="text-align:left"><b>货品名称</b></th>
                    <th style="text-align:left"><b>价格</b></th>
                    <th style="text-align:left"><b>重量</b></th>
                </tr>
                <?php foreach ($good_list as $good) { ?>
                <tr>
                    <td><input name="relation_id[]" type="checkbox" value="<?php echo $good['id']; ?>" ></td>
                    <td><?php echo html::specialchars($good['sku']); ?></td>
                    <td><?php echo html::specialchars($good['title']); ?></td>
                    <td><?php echo html::specialchars($good['price']); ?></td>
                    <td><?php echo html::specialchars($good['weight']); ?></td>
                </tr>
                    <?php } ?>
                <tr>
                    <td colspan="6">
                        <div class="Turnpage_rightper"> <!--<?php echo view_tool::per_page(); ?>-->
                            <div class="b_r_pager"> <?PHP echo $this->pagination->render('opococ'); ?> </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="list_save">
            <input id="add_good" class="ui-button" type="button" value="添加"/>
            <input id="clear_good" class="ui-button" type="button" value="清空"/>
        </div>
    </form>
</div>
<!--**content end**-->
<script type="text/javascript">
    $(document).ready(function(){
        /* 按钮风格 */
        $(".ui-button-small,.ui-button").button();
        $('#check_all').click(function(){
            var rs = $(this).attr('checked');
            var ps = parent.relation_good_ids;
            $('input[name="relation_id[]"]').each(function(i, item){
                $(item).attr('checked', rs);
                if(rs == true)	{
                    var relation_id = $(item).val();
                    if(typeof ps[relation_id] == 'undefined'){
                        ps[relation_id] = true;
                    }
                }
                if(rs == false)
                {
                	var relation_id = $(item).val();
                    delete ps[relation_id];
                }
            });
            parent.relation_good_ids = ps;
        });

        $('input[name="relation_id[]"]').click(function(){
            var relation_id  = $(this).val();
            var ps = parent.relation_good_ids;
            if ($(this).attr('checked') == true) {
                if (typeof ps[relation_id] == 'undefined') {
                    ps[relation_id] = true;
                }
            } else {
                if (typeof ps[relation_id] != 'undefined') {
                    delete ps[relation_id];
                }
            }
            parent.relation_good_ids = ps;
        });

		$('#clear_good').click(function(){
			var ps = parent.relation_good_ids;
			$('input[name="relation_id[]"]').each(function(idx, item){
				var o = $(item);
				if (o.attr('checked') == true) {
					o.attr('checked', false);
					var v = o.val();
					if (typeof ps[v] != 'undefined') {
						delete ps[v];
					}
				}
			});
			parent.relation_good_ids = ps;
		});

        $('#add_good').click(function(){
            parent.$('#good_relation_ifm').dialog('close');
            parent.renderRelationGoods();
        });

        var ps = parent.relation_good_ids;
        $('input[name="relation_id[]"]').each(function(idx, item){
            var o = $(item);
            var v = o.val();
            if (typeof ps[v] != 'undefined') {
                o.attr('checked', true);
            } else {
                o.attr('checked', false);
            }
        });
    });
</script>