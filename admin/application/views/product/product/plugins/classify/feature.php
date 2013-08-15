<?php defined('SYSPATH') OR die('No direct access allowed.');
if($features) : 
?>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<?php foreach ($features as $feature) : ?>
	<tr>
		<th width="15%"><?php echo html::specialchars($feature['name']); ?></th>
		<td width="85%">
        <?php if (isset($feature['type']) && $feature['type']==1){ ?>
            <input type="text" class="text" name="pdt_fetuoptrs[<?php echo $feature['id']; ?>]" value="<?php echo isset($relation[$feature['id']]['attribute_value'])?$relation[$feature['id']]['attribute_value']:''; ?>" size="50">
        <?php 
              }else{
                  $attribute_value = '';
                  if( isset($relation) 
                      && isset($relation[$feature['id']]) 
                      && $relation[$feature['id']]['attributeoption_id']>0
                      && isset($feature['options'][$relation[$feature['id']]['attributeoption_id']]) )
                  {
                    $attribute_value = $feature['options'][$relation[$feature['id']]['attributeoption_id']]['name']; 
                  } 
        ?>                
            <input type="hidden" name="pdt_fetuoptrs[<?php echo $feature['id']; ?>]" value="<?php echo $attribute_value; ?>">
			<select name="pdt_fetuoptrs_check_<?php echo $feature['id']; ?>">
				<option value="0">----</option>
				<?php foreach ($feature['options'] as $option) : ?>
				<option value="<?php echo $option['id']; ?>"<?php if (!empty($relation[$feature['id']]) && $relation[$feature['id']]['attributeoption_id'] == $option['id']) : ?> selected<?php endif; ?>><?php echo htmlspecialchars($option['name']); ?></option>
				<?php endforeach; ?>
			</select>    
        <?php } ?>    
		</td>
	</tr>
	<?php endforeach;?>
</table>
<script type="text/javascript">
	var features = <?php echo json_encode($features); ?>;
	for (var k in features) {
		var feature = features[k];
		$('select[name="pdt_fetuoptrs_check_' + feature['id'] + '"]').unbind('change').bind('change', function(){
			var feature_id = $(this).attr('name').split('_')[3];
			var option_id  = $(this).val();
			if (option_id > 0) {
				var name = features[feature_id]['options'][option_id]['name'];
			} else {
				var name = '';
			}
			$('#features_box').find('input[name="pdt_fetuoptrs[' + feature_id + ']"]').val(name)
		});
	}
	
	$('#features_box').unbind().bind('click', function(e){
		if (e.target.nodeName.toUpperCase() == 'IMG') {
			var o = $(e.target);
			var p = o.prev();
			if (o.attr('src') == url_base + 'images/icon_dot1.gif') {
				p.show();
				o.attr('src', url_base + 'images/icon_dot2.gif');
			} else {
				p.hide();
				o.attr('src', url_base + 'images/icon_dot1.gif');
			}
		}
	});
</script>
<?php endif; ?>