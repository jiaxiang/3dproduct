<section class="topbar">
	<div class="col-width clearfix">
	<nav id="top-menu" class="ddsmoothmenu">
	<ul id="menu-menu-en" class="drop-menu" style="position: relative;">
	<li class="" style="position: absolute; display: block; margin: 0px; padding: 0px; left: 64px; top: 0px; width: 80px; height: 30px; overflow: hidden;">
	<div class="leftLava"></div>
	<div class="bottomLava"></div>
	<div class="cornerLava"></div>
	</li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-382">
<?php
if ($user) {
	echo '<a href="#">Welcome!  '.$user['username'].'</a>';
}
?>
</li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-395"><a href="http://lifewithart.taobao.com/" target="_blank">淘宝店</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-382"><a href="<?php echo url::base();?>service/print3d">我要打印</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-383"><a href="http://lifewithart.taobao.com/p/service.htm" target="_blank">3D打印服务说明</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-382"><a href="http://lifewithart.taobao.com/p/buyerguide.htm" target="_blank">线材购买指南</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-382"><a href="<?php echo url::base();?>forums" target="_blank">在线论坛</a></li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-382">
<?php
if ($user) {
	echo '<a href="'.url::base().'user/logout">登出</a>';
}
?>
</li>
</ul></nav>
</div>
</section>
<div id="hd">
<div class="layout grid-m J_TLayout" >
    <div class="col-main">
        <div class="main-wrap J_TRegion" >
            <div class="J_TModule" >
<div class="skin-box tb-module tshop-pbsm tshop-pbsm-shop-custom-banner" style="position: relative;">
    <s class="skin-box-tp"><b></b></s>
    <div class="skin-box-bd">
<style>.tshop-pbsm-shop-custom-banner .banner-box {
            background : url(<?php echo url::base();?>media/images/tb/headpic.jpg) repeat 0 0 !important;
            height : 120px !important;
            }

            .tshop-pbsm-shop-custom-banner {
            height : 120px !important;
            }</style>
        <div>
<div class="banner-box"></div>
</div>
</div>
<s class="skin-box-bt"><b></b></s>
</div>
</div>
<div class="J_TModule">
<div class="skin-box tb-module tshop-pbsm tshop-pbsm-shop-nav-ch" style="display: block; visibility: visible;">
<div class="skin-box-bd"></div>
</div></div></div></div></div>
</div>