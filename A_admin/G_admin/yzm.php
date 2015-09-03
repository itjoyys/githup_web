<?php 

/*
  高级版本已经弃用，直接用$_SESSION
*/
//session_register("randcode");   
$type = 'png';
$width= 60;
$height= 20;
header("Content-type: image/".$type);
srand((double)microtime()*1000000);
$randval = randStr(4,"");
if($type!='png' && function_exists('imagecreatetruecolor')){
$im = @imagecreatetruecolor($width,$height);
}else{
$im = @imagecreate($width,$height);
}
$r = Array(210,50,120);
$g = Array(240,225,235);
$b = Array(250,225,10);

$rr = Array(255,240,0);
$gg = Array(100,0,0);
$bb = Array(0,0,205);

$key = rand(0,2);
$stringColor = ImageColorAllocate($im,255,255,255); //字体颜色
//$backColor = ImageColorAllocate($im,223,238,245);//背景色（随机）
$backColor = ImageColorAllocate($im,255,20,30);//背景色（随机）
//$borderColor = ImageColorAllocate($im, 73, 185, 219);//边框色
//$pointColor = ImageColorAllocate($im, 18,121,226);//点颜色

@imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);//背景位置
@imagerectangle($im, 0, 0, $width-1, $height-1, $borderColor); //边框位置

/*for($i=0;$i<=200;$i++){
$pointX = rand(2,$width-2);
$pointY = rand(2,$height-2);
@imagesetpixel($im, $pointX, $pointY, $pointColor);//绘模糊作用的点
}*/

@imagestring($im,15,12,2, $randval, $stringColor); //调整字型位置
$ImageFun='Image'.$type;
$ImageFun($im);
@ImageDestroy($im);
$_SESSION['randcode'] = $randval;
//产生随机字符串
function randStr($len=6,$format='ALL') {
switch($format) {
/*case 'ALL':
$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; break;
case 'CHAR':
$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; break;
case 'NUMBER':
$chars='0123456789'; break;*/
default :
$chars='0123456789';
break;
}
$string="";
while(strlen($string)<$len)
$string.=substr($chars,(mt_rand()%strlen($chars)),1);
return $string;
}
?>