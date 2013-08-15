<?php
$width  = isset( $_GET['w'] ) ? intval($_GET['w'] ) : 400;        //图片宽度
$height = isset( $_GET['h'] ) ? intval($_GET['h'] ) : 300;        //图片的高度
$title  = isset( $_GET['t'] ) ? $_GET['t'] : '';                  //图片标题

$rows = isset( $_GET['r'] ) ? intval($_GET['r']) : 5;             //行数
$rma  = $_GET['ma'];                  //最大值
$rmi  = $_GET['mi'];                  //最小值

if ( isset($_GET['sp']) ){
	$space = intval($_GET['sp'])>20 ? intval($_GET['sp']) : 20;  //图形距离图片的边距
}else {
	$space = 20;
}

if (isset($_GET['g'])) {
	$g = intval($_GET['g']) ? intval($_GET['g']) : 1;     //数据的组数
}else {
	$g = 1;
}


$lts = explode(',', $_GET['ct']);     //横坐标标值
$cols = count($lts);

$im = imagecreate($width,$height);
$black = ImageColorAllocate($im, 0,0,0);
$white = ImageColorAllocate($im, 255,255,255);
$skyblue = ImageColorAllocate($im,136,193,255);
$gray = imagecolorallocate($im, 240,240,240);
ImageFill($im,0,0,$white);

$wf2 = ImageFontWidth(2);
$rt_len = 0;
for($i=0; $i<=$rows; $i++){
	$len = strlen( $rmi+($rma-$rmi)/$rows*$i );
	$len > $rt_len ? $rt_len = $len : '';
}
$left_space = $wf2*$rt_len+5;
//最左侧的直线
imageline($im, $left_space, $space, $left_space, $height-$space, $black);

//最下侧的直线
imageline($im, $left_space, $height-$space, $width-$space, $height-$space, $black);


for($i=0; $i<=$rows; $i++){
	$y = intval( $height-$space-($height-2*$space-5)/$rows*$i );
	if ($i>0){
		//画横线
		imageline($im, $left_space+1, $y, $width-$space-5, $y, $gray);
	}
	// 横线对应的值
	imagestring($im, 2, 5, $y-10, $rmi+($rma-$rmi)/$rows*$i , $black);
}

for($i=0; $i<$cols; $i++){
	$x1 = intval( $left_space+($width-$left_space-$space-5)/($cols-1)*$i );
	if ($i>0) {
		//画竖线
		imageline($im, $x1, $height-$space-1, $x1, $space+5, $gray);
	}
	//横坐标标值
	imagestring($im, 2, $x1-5, $height-$space+5, $lts[$i], $black);
}

for ($n=0; $n<$g; $n++){
	$ps = $_GET['ps'.($n+1)];                 //点坐标
	$pa = explode(',',$ps);                   //点坐标数组
	$cnt = count($pa);
	$clr = isset( $_GET['clr'.($n+1)] ) ? $_GET['clr'.($n+1)] : '';
	if ($clr != '') {
		$ca = explode(',',$clr);
	}else {
		$ca = array(0,0,0);
	}
	
	$color = imagecolorallocate($im, $ca[0],$ca[1],$ca[2]);
	
	for($i=0; $i<$cnt; $i++){
		//点坐标（x,y）
		$x1 = intval( $left_space+($width-$left_space-$space-5)/($cols-1)*$i );
		$y1 = intval( $height-$space-( ($pa[$i]-$rmi)/($rma-$rmi)*($height-2*$space-5 ) ) );
		if ($i>0) {
			$x2 = intval( $left_space+($width-$left_space-$space-5)/($cols-1)*($i-1) );
			$y2 = intval( $height-$space-( ($pa[$i-1]-$rmi)/($rma-$rmi)*($height-2*$space-5 ) ) );
			//画折线
			imageline($im, $x1,$y1,$x2,$y2, $color);
		}
		imagefilledrectangle($im, $x1-2,$y1-2,$x1+2,$y1+2, $color);
	}
}
$wf5 = ImageFontWidth(5);
$t_len = $wf5 * strlen($title);
//echo $cw.' '.$ch;die();
imagestring($im, 5, $width/2-$t_len/2, 5, $title, $black);
Header("Content-type: image/gif");
ImageGif($im);
ImageDestroy($im);