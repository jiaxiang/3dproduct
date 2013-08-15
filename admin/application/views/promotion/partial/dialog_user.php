<!-- dialog form start -->
<?php if(isset($userDiaStruct)) extract($userDiaStruct,EXTR_OVERWRITE);?>
<div title="添加用户" id="<?php echo $dialog_form;?>" style="text-align:center;display:none;">
    <div style="text-align:left;padding:0 0 0 20px;margin:0 0 2px 0;">
	    <label>
	        <select id="<?php echo $userSearchType;?>" name="<?php echo $userSearchType;?>" class="text">
	            <option value="email">登录邮箱</option>
	        </select>
	    </label>
	    <label>
	        <input class="text" type="text" name="<?php echo $userKeyword;?>" id="<?php echo $userKeyword;?>" />
	    </label>
	    <label>
	        <input type="button" class="ui-button" id="<?php echo $userSearchbtn;?>" name="<?php echo $userSearchbtn;?>" value="搜索">
	    </label>
    </div>
    <div class="division" style="margin-top:0;width:98%">
    <table id="<?php echo $userTable;?>" style="width:100%;border:1px solid #ccc;" class="table_overflow">
        
    </table>
    </div>
</div>
<!-- dialog form end -->