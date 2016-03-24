<?php
error_reporting(-1);
ini_set('display_errors',0);
@session_start();
$y_w=intval($_REQUEST['w']);
$y_h=intval($_REQUEST['h']);
if($y_w==0 || $y_w>200)$y_w=50;
if($y_h==0 || $y_h>100)$y_h=20;
$y_w_=intval($y_w/5);
$y_h_=intval($y_h/1.3);
$y_f_=intval($y_h/1.8);
$point=$y_w/10;
 //$y_f_=18;
function verify_rand($length){
    $result = "";
    $string = "0123456789";
    for ($i = 0 ; $i < $length ; $i++){
        if($i==0) $result .=$string[mt_rand(0 , strlen($string) - 1)];
        else $result .=$string[mt_rand(0 , strlen($string) - 1)];
    }
    return $result;
}
$zs=4;
$randcode=verify_rand($zs);
$_SESSION['code']=strtolower($randcode);
header ("content-type: image/png");
$image_x = $y_w;
$image_y = $y_h;
$image = imagecreate($image_x , $image_y);
$background_color = imagecolorallocate($image,mt_rand(200,255),mt_rand(0,255),mt_rand(0,255));
$font_color = imagecolorallocate($image,1,1,1);
$gray_color  = imagecolorallocate($image,0,0,0);
//fonts
$fonts=array('micross.ttf' );

for($i=0;$i<$zs;$i++){
    $array = array(-1,0,1);
    $p = array_rand($array);
    $an = $array[$p]*mt_rand(-15,20);
    imagettftext($image,$y_f_, $an, $i*$y_w_+$y_w_/2,$y_h_, $font_color, "fonts/".$fonts[mt_rand(0,count($fonts)-1)],substr($randcode,$i,1) );
}

imagerectangle($image,0,0,$image_x-1, $image_y-1,$gray_color);


for($i=0;$i<$point;$i++){
    imagesetpixel($image,mt_rand(0,$image_x),mt_rand(0,$image_y),$gray_color);
}

imagepng($image);
imagedestroy($image);
?>