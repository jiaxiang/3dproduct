<?php defined('SYSPATH') OR die('No direct access allowed.');
if($attributes) : 
?>
<link rel="stylesheet" type="text/css" href="/js/msdropdown/dd.css" />
<script type="text/javascript" src="/js/msdropdown/jquery.dd.js"></script>           
<table id='attr_spec' width="100%" cellspacing="0" cellpadding="0" border="0">
    <?php foreach ($attributes as $attribute) : ?>
    <tr><th width="15%">* 
        <?php 
            echo html::specialchars($attribute['name']);
            if(isset($attribute['alias']) && $attribute['name']!=$attribute['alias'])
            {
                echo "(".$attribute['alias'].")";
            } 
       ?>
    </th><td width="85%">    
    <?php
        if($attribute['type']==1)
        {
            echo '用户填写<input class="attribute_spec" type="hidden" name="attribute_spec['.$attribute['id'].']" value="0">';
        }
        else
        {
            if($attribute['display']=='image')
            {
                echo '<select class="attribute_spec" style="display:none;width:220px;" id="attribute_spec_'.$attribute['id'].'" name="attribute_spec['.$attribute['id'].']">';
                foreach($attribute['options'] as $option)
                {
                    $select = '';
                    if(isset($relation[$attribute['id']]) && $relation[$attribute['id']]==$option['id'])
                    {
                        $select = 'selected';
                    }
                    $option_name = $option['name'].($option['alias']?'--('.$option['alias'].')':'');
                    $option_img = isset($option['image'][2])?$option['image'][2]:'/att/no.gif';
                    echo '<option value="'.$option['id'].'" title="'.$option_img.'" '.$select.'>'.$option_name.'</option>';
                }
                echo '</select>';   
                echo '<script language="javascript" type="text/javascript">
                    $(document).ready(function(){
                        try{
                            oHandler = $("#attribute_spec_'.$attribute['id'].'").msDropDown().data("dd");
                            oHandler.visible(true);
                        } catch(e) {
                            alert("Error: "+e.message);
                        }
                    });	
                    </script>';
            }
            else
            {
                echo '<select class="attribute_spec" name="attribute_spec['.$attribute['id'].']">';
                foreach($attribute['options'] as $option)
                {
                    $select = '';
                    if(isset($relation[$attribute['id']]) && $relation[$attribute['id']]==$option['id'])
                    {
                        $select = 'selected';
                    }
                    $option_name = $option['name'].($option['alias']?'--('.$option['alias'].')':'');
                    echo '<option value="'.$option['id'].'" '.$select.'>'.$option_name.'</option>';
                }
                echo '</select>';   
            }
        }
    ?>
    </td></tr>
    <?php endforeach; ?>
</table>
<script type="text/javascript">
(function(){ 
    /**
     * 表单提交时，首先验证货品的规格等信息是否正确
     */
    submitHandlers.push(function(){
        var sign = false;
        var success = true;
        var product_id = $('#product_id').val();
        var configurable_id = $('#configurable_id').val();
        var url = url_base + 'product/configurable/validate_spec';
        if(configurable_id > 0){
            url += '?product_id=' + product_id + '&configurable_id=' + configurable_id;
            $('.attribute_spec').each(function(idx, item){
                sign = true
                var name = $(item).attr('name');
                url += '&' + name + '=' + $(item).val();            
    		});
        	if (sign == true) {
                //document.write(url);
        		ajax_block.open();
        		$.ajax({
        			url: url,
        			type: 'GET',
        			async: false,
        			dataType: 'json',
        			success: function(retdat, status) {
        				ajax_block.close();
        				if (retdat['status'] != 1 || retdat['code'] != 200) { 
                            success = false;       					
                            var msg = retdat['msg'];
        					var ths = $('#attr_spec').find('th');
                            ths.each(function(idx, item){
                                msg += '<br>' + item.innerHTML;          
                    		});
                            msg += '<br>请修改其中的一个规格选项值后再试试。'
        					showMessage('操作失败', '<font color="#990000">' + msg + '</font>', 260, 400);
        				}
        			},
        			error: function() {
        				ajax_block.close();
                        success = false;
        				showMessage('操作失败', '<font color="#990000">验证货品规格信息失败，请稍后重新尝试！</font>');
        			}
        		});
        	}
        }
        return success;		
    });
})();
</script>
<?php endif; ?>