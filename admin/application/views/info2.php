<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 消息交互
 * @package feedback
 * @author nickfan<nickfan81@gmail.com>
 * @link http://feedback.ketai-cluster.com
 * @version $Id: info.php 59 2010-03-31 04:21:25Z fzx $
 */

$render_struct = array(
        'status'=>0,
        'code'=>501,
        'msg'=>'',
        'action'=>array(
                'url'=>request::referrer('about:blank'),
                'time'=>3,
                'type'=>'back',//header/back/close/page/location/stand
                'frame'=>'self',//self/blank/top/parent/[string]
                'script'=>'',
        ),

);
isset($return_struct['status']) && $render_struct['status'] = $return_struct['status'];
isset($return_struct['code']) && $render_struct['code'] = $return_struct['code'];
isset($return_struct['msg']) && $render_struct['msg'] = $return_struct['msg'];
if(isset($return_struct['action'])) {
    isset($return_struct['action']['url']) && $render_struct['action']['url'] = $return_struct['action']['url'];
    //empty($render_struct['action']['url']) && $render_struct['action']['url'] = request::referrer('about:blank');
    isset($return_struct['action']['time']) && $render_struct['action']['time'] = $return_struct['action']['time'];
    isset($return_struct['action']['type']) && $render_struct['action']['type'] = $return_struct['action']['type'];
    isset($return_struct['action']['frame']) && $render_struct['action']['frame'] = $return_struct['action']['frame'];
    isset($return_struct['action']['script']) && $render_struct['action']['script'] = $return_struct['action']['script'];
}
$render_struct['action']['target'] = in_array($render_struct['action']['frame'], array('blank', 'top', 'self', 'parent')) ? "_".$render_struct['action']['frame'] : $render_struct['action']['frame'];

//exit("<div id=\"do_debug\" style=\"clear:both;display:;\"><pre>\n".var_export($render_struct,TRUE)."\n</pre></div>");

$action_linktext = 'proceed';
$action_linkcontext = '';
$action_actioncontext = '';

if($render_struct['action']['type']=='header') {
    header("Location:" . $render_struct['action']['url']);
    exit();
}

//elseif(in_array($render_struct['action']['type'],array('location','close')))
switch($render_struct['action']['type']) {
    case 'location':
    case 'close':
        if($render_struct['action']['frame']!='self') {
            if($render_struct['action']['type']=='location') {
                $action_context_current = $render_struct['action']['script'].' '
                        .'top.window[\''.$render_struct['action']['frame'].'\'].location.href=\''.$render_struct['action']['url'].'\';';
            }elseif($render_struct['action']['type']=='close') {
                $action_context_current = $render_struct['action']['script'].' '
                        .'top.window[\''.$render_struct['action']['frame'].'\'].close();';
            }
        }else {
            if($render_struct['action']['type']=='location') {
                $action_context_current = $render_struct['action']['script'].' '
                        .'self.location.href=\''.$render_struct['action']['url'].'\';';
            }elseif($render_struct['action']['type']=='close') {
                $action_context_current = $render_struct['action']['script'].' '
                        .'self.close();';
            }
        }
        $action_linkcontext = '<a href="javascript:'.$action_context_current.'" '
                .'name="action_current" id="action_current" '
                .'target="'.$render_struct['action']['target'].'"'
                .'class="action_current ui-button ui-state-default ui-corner-all" '
                .'>'.$action_linktext.'</a>'
                .'<script type="text/javascript">document.getElementById(\'action_current\').focus();</script>';
        $action_actioncontext = '<script type="text/javascript">action_trigger = window.setTimeout(function(){ '.$action_context_current.' },1000*'.$render_struct['action']['time'].');</script>';
        break;
    case 'page':
        $action_context_current = $render_struct['action']['script'].' '
                .'var pageredirect=function(){ if (top.location !== self.location){top.location=self.location;} location.href = \''.$render_struct['action']['url'].'\'; return ; }; pageredirect(); ';
        $action_linkcontext = '<a href="javascript:'.$action_context_current.'" '
                .'name="action_current" id="action_current" '
                .'target="'.$render_struct['action']['target'].'"'
                .'class="action_current ui-button ui-state-default ui-corner-all" '
                .'>'.$action_linktext.'</a>'
                .'<script type="text/javascript">document.getElementById(\'action_current\').focus();</script>';
        $action_actioncontext = '<script type="text/javascript">action_trigger = window.setTimeout(function(){ '.$action_context_current.' },1000*'.$render_struct['action']['time'].');</script>';
        break;
    case 'stand':
        $action_context_current = $render_struct['action']['script'].' ';
        if(!empty($render_struct['action']['url'])) {
            if($render_struct['action']['frame']!='self') {
                $action_context_current .= 'top.window[\''.$render_struct['action']['frame'].'\'].location.href=\''.$render_struct['action']['url'].'\';';
            }else {
                $action_context_current .= 'self.location.href=\''.$render_struct['action']['url'].'\';';
            }
        }
        $action_context_current .= ';';
        $action_linkcontext = '<a href="javascript:'.$action_context_current.'" '
                .'name="action_current" id="action_current" '
                .'target="'.$render_struct['action']['target'].'"'
                .'class="action_current ui-button ui-state-default ui-corner-all" '
                .'>'.$action_linktext.'</a>'
                .'<script type="text/javascript">document.getElementById(\'action_current\').focus();</script>';
        $action_actioncontext = '';
        break;
    case 'back':
    default:
        $action_context_current = $render_struct['action']['script'].' '
                .'history.back();';
        $action_linkcontext = '<a href="javascript:'.$action_context_current.'" '
                .'name="action_current" id="action_current" '
                .'target="'.$render_struct['action']['target'].'"'
                .'class="action_current ui-button ui-state-default ui-corner-all" '
                .'>'.$action_linktext.'</a>'
                .'<script type="text/javascript">document.getElementById(\'action_current\').focus();</script>';
        $action_actioncontext = '<script type="text/javascript">action_trigger = window.setTimeout(function(){ '.$action_context_current.' },1000*'.$render_struct['action']['time'].');</script>';
        break;
}

?>
<div class="new_content">
    <div class="newgrid">
    	<?php if ($render_struct['status'] == 1) : ?>
        <div class="new_skip_title">
       		管理中心-系统提示
        </div>
		<div class="new_skip_info_short">
        	<p class="skip_suc"><?php echo html::specialchars($render_struct['msg']); ?></p>
			<p>3秒之后页面将自动跳转。</p>
        </div>       
       <?php else : ?>
        <div class="new_skip_title">
       		管理中心-系统提示
        </div>
		<div class="new_skip_info_short">
        	<p class="skip_erro"><?php echo html::specialchars($render_struct['msg']); ?></p>
			<p>3秒之后页面将自动跳转。</p>
        </div>
       <?php endif; ?>
    </div>
</div>
<?php echo $action_actioncontext; ?>
<script type="text/javascript">
    //<![CDATA[
    /* response ui data */
    var uiData = {
        'status': <?php echo $render_struct['status'];?>,
        'message': '<?php echo $render_struct['msg'];?>',
        'trigger_tips': null,
        'style_tips':'<?php if($render_struct['status']==1) { ?>ui-state-highlight<?php }else {?>ui-state-error<?php }?>'
    };

    /* document dom ready */
    $(function() {
        /* tips effect */
        if(uiData['message']!=''){
            $("#respTips").effect("highlight",{},2000);
        }
        /* back button */
        $("button[name='goback'],input[name='goback']").click(function(e){
            history.go(-1);
            if(e){ e.preventDefault(); }
            return false;
        });
        /* ui effect */
        $('.ui-state-default').hover(
        function(){
            $(this).addClass("ui-state-hover");
        },
        function(){
            $(this).removeClass("ui-state-hover");
        }
    ).mousedown(function(){
            $(this).addClass("ui-state-active");
        }).mouseup(function(){
            $(this).removeClass("ui-state-active");
        });
    });
    //]]>
</script>