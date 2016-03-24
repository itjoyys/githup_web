<?php

class VerifCode{
    private $chars="98765432100123456789";  //随机因子
    private $code;  //验证码
    private $codelen=4; //验证码长度
    private $width=50; //宽度
    private $height=20; //高度
    private $fontSize=12;   //字体大小
    private $fontColor;

    public function ccode(){
    	header ("content-type: image/png");
    	$y_w_ = intval($this->width/5);
		$y_h_ = intval($this->height/1.3);
		$y_f_ = intval($this->height/1.8);
		$point = $this->width/10;
		$image = imagecreate($this->width,$this->height);
		$background_color = imagecolorallocate($image,mt_rand(200,255),mt_rand(0,255),mt_rand(0,255));
		$font_color = imagecolorallocate($image,1,1,1);
		$gray_color = imagecolorallocate($image,0,0,0);

        $randcode = $this->verify_rand($this->codelen);

        $_SESSION['code'] = $randcode;
		for($i=0;$i< $this->codelen;$i++){
		    $array = array(-1,0,1);
		    $p = array_rand($array);
		    $an = $array[$p]*mt_rand(-15,20);
		    @imagettftext($image,$this->fontSize, $an, $i*$y_w_+$y_w_/2,$y_h_, $font_color, "micross.ttf",substr($randcode,$i,1) );
		}

		imagerectangle($image,0,0,$this->width-1, $this->height-1,$gray_color);

		for($i=0;$i<$point;$i++){
		    imagesetpixel($image,mt_rand(0,$this->width),mt_rand(0,$this->height),$gray_color);
		}

		imagepng($image);
		imagedestroy($image);
    }

	public function verify_rand(){
	    $result = "";
	    $string = '98765432100123456789';
	    for ($i = 0 ; $i < $this->codelen ; $i++){
	        if($i==0) $result .=$string[mt_rand(0 , strlen($string) - 1)];
	        else $result .=$string[mt_rand(0 , strlen($string) - 1)];
	    }
	    return $result;
	}

	public function get_code(){
		return $this->code;
	}

}
?>