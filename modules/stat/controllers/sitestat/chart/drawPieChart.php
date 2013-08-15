<?php
//图片宽和高
$w = isset($_GET['w']) ? intval($_GET['w']) : 400;
$h = isset($_GET['h']) ? intval($_GET['h']) : 200;
$title = isset( $_GET['t'] ) ? $_GET['t'] : '';
//颜色库
$color_arr = array(
	array(0,0,255),
	array(30,30,255),
	array(60,60,255),
	array(90,90,255),
	array(120,120,255),
	array(150,150,255),
	array(180,180,255),
	array(210,210,255),
	
	array(0,255,0),
	array(120,255,120),
	array(150,255,150),
	array(180,255,180),
	array(210,255,210),
	
	array(255,0,0),
	array(255,30,30),
	array(255,60,60),
	array(255,90,90),
	array(255,120,120),
	array(255,150,150),
	array(255,180,180),
	array(255,210,210),
	
	array(255,255,0),
	
	array(255,0,255),
	array(255,60,255),
	array(255,90,255),
	array(255,120,255),
	array(255,150,255),
	array(255,180,255),
	array(255,210,255),
	
	array(0,255,255),
	array(90,255,255),
	array(120,255,255),
	array(150,255,255),
	array(180,255,255),
	array(210,255,255),
	
	array(50,50,50),
	array(70,70,70),
	array(90,90,90),
	array(110,110,110),
	array(130,130,130),
	array(150,150,150),
	array(170,170,170),
	array(190,190,190),
	);
//数值
$points = isset($_GET['ps']) ? explode(',', $_GET['ps']) : array(1,1,1);
//数值对应名称
$pts = isset($_GET['pts']) ? explode(',', $_GET['pts']) : array('1','2','3');
$cnt = count($points);
//总值
$total = 0;
$tlen = 0;
for ($i=0; $i<$cnt; $i++){
	$total += $points[$i];
	if (isset($pts[$i])) {
		$tlen = $tlen>strlen($pts[$i]) ? $tlen : strlen($pts[$i]);
	}
}
//最短边
$min_wh = ($w-2*($tlen+8)*ImageFontWidth(2))>($h-10) ? $h : ($w-2*($tlen+8)*ImageFontWidth(2));

//创建图片
$im = imagecreate($w,$h);
//图片背景色为白色
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
ImageFill($im,0,0,$white);
//圆心坐标
$cx = $w/2;
$cy = $h/2+20;
//圆外接矩形的宽和高
$cw = $min_wh-65;
$ch = $min_wh-65;

$start = 0;
$end = 0;
for ($i=0; $i<$cnt; $i++){
	//扇形的颜色
	$color = $i>=43 ? imagecolorallocate($im, rand(0,255), rand(0,255), rand(0,255)) : imagecolorallocate($im, $color_arr[$i][0], $color_arr[$i][1], $color_arr[$i][2]);
	$color = imagecolorallocate($im, $color_arr[$i][0], $color_arr[$i][1], $color_arr[$i][2]);
	//起始角度和结束角度
	$start = $end;
	$end = $start + $points[$i]/$total*360;
	imagefilledarc($im, $cx, $cy, $cw, $ch, $start, $end, $color, IMG_ARC_PIE);
	$notel1x = $cx+cos(deg2rad( $start/2+$end/2 ))*$cw/2;
	$notel1y = $cy+sin(deg2rad( $start/2+$end/2 ))*$cw/2;
	$notel2x = $cx+cos(deg2rad( $start/2+$end/2 ))*($cw/2+10);
	$notel2y = $cy+sin(deg2rad( $start/2+$end/2 ))*($cw/2+10);
	imageline($im,$notel1x,$notel1y,$notel2x,$notel2y,$black);
	$str = isset( $pts[$i] ) ? $pts[$i]."(".(substr($points[$i]/$total*100,0,5))."%)" : ($i+1)."(".(substr($points[$i]/$total*100,0,5))."%)";
	if ($notel2x > $notel1x ) {
		$notel3x = $notel2x+10;
		$notestrx = $notel2x+10;
	}else {
		$notel3x = $notel2x-10;
		$notestrx = $notel2x-10-ImageFontWidth(2)*strlen($str);
	}
	$notel3y = $notel2y;
	$notestry = $notel2y-10;
	imageline($im,$notel2x,$notel2y,$notel3x,$notel3y,$black);
	imagestring($im,2,$notestrx,$notestry,$str,$black);
}

$wf5 = ImageFontWidth(5);
$t_len = $wf5 * strlen($title);
//echo $cw.' '.$ch;die();
imagestring($im, 5, $w/2-$t_len/2, 5, $title, $black);
// flush image
header('Content-type: image/gif');
imagegif($im);
imagedestroy($im);
?>