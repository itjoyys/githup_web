<?php
/**
 +------------------------------------------------------------------------------
 * 后台管理 图片文件上传综合处理功能类
 +------------------------------------------------------------------------------
 * @category 
 * @package  
 * @author   Revised by pengzl<pzl7758@163.com>
 * @version  $Id: 2009-02-17 update by pengzl$
 +------------------------------------------------------------------------------
 */
class Image
{
    private $image;
    private $imageUrl;
    private $imagepath;
    private $imageSize;
    private $imageWidth;
    private $imageHeight;
    private $image_mime;
    private $saveName;
    private $savePath;
    private $waterPath; //水印路径
    private $rootPath;
    //水印处理
    private $font_angle = 0;//倾斜角度
    private $font_text	= "文字水印";
    private $font_color	= array("233","14","91");
    private $font_size 	= 20;
    private $font_ttf 	= "";
    private $font_x 		= 20;
    private $font_y 		= 20;
    private $img_width;
    private $img_height;
    private $img_url;
    private $img_water;
    private $img_water_x;
    private $img_water_y;
    private $img_water_Transparency = 20;//水印透明度
    private $img_water_PosType;//水印位置
    //缩略图设置
    private $shrink_width 	= 0;
    private $shrink_height	= 0;
    private $shrink_type 		= "gif";
    //上传设置
    private $upfile_size 		= 0;//为0则大小不限制
    private $upfile_type 		= array(
    													   'image/jpg', 
    													   'image/jpeg', 
    													   'image/png', 
    													   'image/pjpeg', 
    													   'image/gif', 
    													   'image/bmp', 
    													   'image/x-png'
    													   );
    private $erro_msg 			= "";

    public function Image()
    {
        $this->font_size 	= 12;
        $this->font_angle = 0;
    }
    
    //上传
    public function upfile($fname, $savepath="")
    {
        clearstatcache();
        $file     = $_FILES[$fname];
        $fileInfo = pathinfo($file['name']);
    
        if (!empty($savepath)) {
            $this->savePath = $savepath;
        } else {
            $this->savePath = "./".date("Ymd")."/";
        }

        if (empty($this->saveName)) {
            $this->saveName = time().rand(0,999999).".".$fileInfo['extension'];		
        }
        
        self::is_write($this->savePath);
        
        $savection = $this->savePath.$this->saveName;
    
        if  ($this -> upfile_size)  {
            if  ($file['size'] > $this->upfile_size)  {
                $this->erro_msg .= "上传文件过大"; 
                return false;
            }
        }
        if (!in_array($file['type'], $this->upfile_type)) {

            $this->erro_msg .= "出错代码:{$file['error']}类型不符!"; 
            return false;
        }
        $file['tmp_name'] = str_replace('\\\\', '\\', $file['tmp_name']);
        if (!@move_uploaded_file ($file['tmp_name'], $savection)) 
        { 
            $this->erro_msg .= "出错代码:{$file['error']}移动文件失败!";
            return false;
        } else {
            chmod ($savection,0775);//给文件赋权限
        }
    
        $temp   				    = getimagesize($savection);
        $this->image 				= self::readImage($fileInfo['extension'], $savection);
        $this->image_mime 	= $temp['mime'];
        $this->imageHeight 	= $temp[0];
        $this->imageWidth 	= $temp[1];
        $this->imagepath  	= $savection;
        return $savection;
    }

    public function delImg($fname){
        
    }
    //加图片水印
    public function createImg($fname="",$savepath="")
    {
        if (empty($fname))
        {
            $fname = $this->imagepath;
        }
    
        if ($fileInfo = self::is_File_True($fname)) {
            $this->image = self::readImage($fileInfo['extension'],$fname);//取背景图片信息
        }
    
        if (empty($this->saveName)) {
            $this->saveName = $fileInfo['basename'];
        }
    
        if (empty($savepath))
        {
            empty($this->savePath) ? $savepath = $fname :  $savepath = $this->savePath;
        } else {
            self::is_write($savepath);
        }
        
        $savection = $savepath.$this->saveName;
        
        if (empty($this->img_water))
        {
            $this->erro_msg .= "水印文件未读取"; 
            return false;
        }
    
        $bg_w = imagesx($this->image);
        $bg_h = imagesy($this->image);
    
        if($bg_w < ($this->img_width+ceil($this->img_width/2)) || $bg_h < ($this->img_Height+ceil($this->img_Height/2)))
        {
        //height=h/(w/fixwidth);
        //width=w/(h/fixheight);
        $this->erro_msg .= "文件太小未加水印!"; 
        return false;
        }
        
        /*
        if ($bg_w < ($this->img_width+10) || $bg_h < ($this->img_Height+10))
        {
        //height=h/(w/fixwidth);
        //width=w/(h/fixheight);
            $this->erro_msg .= "文件太小未加水印!"; 
            return false;
        }
        */
        
        imagealphablending($this->image,true);
        imagealphablending($this->img_water,true);
        
        if (empty($this-> img_water_PosType)) {
            $this-> img_water_PosType = 4;//右下角
        }
        
        self::WaterPos($this-> img_water_PosType);
        
        imagecopymerge(
        $this->image,
        $this->img_water,
        $this->img_water_x,
        $this->img_water_y,
        0,0,
        $this->img_width,
        $this->img_Height,
        $this->img_water_Transparency);
        
        /*
        imagecopyresampled(
                $this->image,
                $this->img_water,
                $this->img_water_x,$this->img_water_y,
                0,0,
                $this->img_width,$this->img_Height,
                $this->img_width,$this->img_Height
                );
        */

        
        $_rtn = self::saveImage($this->image, $fileInfo['extension'], $savepath,substr($this->saveName,0,-4));
        imagedestroy($this->image); 
        imagedestroy($this->img_water);
        
        return $_rtn;
    }

    //读取水印文件(必须操作)
    function read_waterImg($Cimgu="")
    {
        if (empty($Cimgu))
        {
            $Cimgu = "./water.jpg";
        }
        
        $this->waterPath = $Cimgu;
        
        if ($Cimginfo = self::is_File_True($this->waterPath))
        {
            $this->img_water = self::readImage($Cimginfo['extension'],$this->waterPath);//取水印图片信息
        }
        
        $this->img_width 		= imagesx($this->img_water);
        $this->img_Height 	= imagesy($this->img_water);
        
        return $this;
    }
	
    //建立文字水印
    function createTextImg($fname="", $savepath="")
    {
        if (empty($fname))
        {
            $fname = $this->imagepath;
        }
        
        if ($fileInfo = self::is_File_True($fname)) {
            $this->image = self::readImage($fileInfo['extension'],$fname);//取背景图片信息
        }
        
        if (empty($this->saveName)){
            $this->saveName = $fileInfo['basename'];
        }
        
        if (empty($savepath))
        {
            empty($this->savePath) ? $savepath = $fname :  $savepath = $this->savePath;
        } else {
            self::is_write($savepath);
        }
        $savection = $savepath.$this->saveName;			
        
        
        if (empty($this->font_text)) {
            $this->erro_msg .= "水印文字为空"; 
            return false;
        }
        
        if (!function_exists("iconv")) {
            $this->erro_msg .= "字体转换模块iconv未加载"; 
            return false;
        } else {
            $this->font_text	= iconv('GB2312','UTF-8//IGNORE',$this->font_text);
        }
        
        if (empty($this->font_color)) {
            $this->font_color = "#000000";
        }
        
        //将16进制转为数组array(r,g,b)
        if (strlen($this->font_color) == 7)
        {
            $r = hexdec(substr($this->font_color, 1, 2));
            $g = hexdec(substr($this->font_color, 3, 2));
            $b = hexdec(substr($this->font_color, 5));
            $this->font_color = array($r, $g, $b);
        }
        
        $fontColor = $this->font_color;		
        $fontColor = imagecolorallocate($this->image, $fontColor[0], $fontColor[1], $fontColor[2]);
        
        //设定图片混色模式
        imagealphablending($this->image, true);
        
        if (empty($this->font_ttf) || !is_file($this->font_ttf)) {//如果不加载字体，则使用默认字体
            $f = @imagestring(
                $this->image,
                $this->font_size,
                $this->img_water_x,
                $this->img_water_y,
                $this->font_text,
                $fontColor
            );
        } else {//如果加载字体则使用改字体显示
            $f = @imagettftext(
                $this->image,
                $this->font_size,
                $this->font_angle,
                $this->img_water_x,
                $this->img_water_y,
                $fontColor,
                $this->font_ttf,
                $this->font_text
            );
        }
        
        $_rtn = self::saveImage($this->image, $fileInfo['extension'], $savepath, substr($this->saveName,0,-4));
        imagedestroy($this->image); 
        
        return $_rtn;
    }
	
    //图片一般缩略图方法
    function shrinkImage($fname="", $savepath="")
    {
    		if (empty($fname))
    		{
    			$fname = $this->imagepath;
    		}
    
    		if($fileInfo = self::is_File_True($fname)) {
            $this->image = self::readImage($fileInfo['extension'], $fname);//取原图片信息
    		}
    		
    		if (empty($this->shrink_type) && $fileInfo['extension'])
    		{
    		  $this->shrink_type = $fileInfo['extension'];
    		}		
    		
    		if(empty($this->saveName)){
    				$this->saveName = "shrink_{$fileInfo['basename']}";
    		}
    		
    		if (empty($savepath))
    		{
    				empty($this->savePath) ? $savepath = $fname :  $savepath = $this->savePath;
    		} else {
    				self::is_write($savepath);
    		}
    		
    		$savection    = $savepath.$this->saveName;			
    		$image_width 	= imagesx($this->image);
    		$image_height = imagesy($this->image);
    		//$image_shot   = self::shotImg($this->image,350);
    		//self::saveImage($image_shot, $this->shrink_type, $savepath, 'bbbb');
    		
    		if ($this->shrink_width < 0) {
            $this->shrink_width = ceil($image_width / abs($this->shrink_width));
    		} elseif ($this->shrink_width==0) {
            $this->shrink_width=$image_width;
    		}
    		
    		if ($this->shrink_height < 0) {
            $this->shrink_height = ceil($image_height / abs($this->shrink_height));
    		} elseif ($this->shrink_height == 0) {
            $this->shrink_height = $image_height;
    		}
    		
    		$rinkImage = imagecreatetruecolor($this->shrink_width,$this->shrink_height);
    		imagecopyresized($rinkImage,$this->image,0,0,0,0,$this->shrink_width,$this->shrink_height,$image_width,$image_height);

       	$_rtn = self::saveImage($rinkImage, $this->shrink_type, $savepath, substr($this->saveName,0,-4));
    		imagedestroy($this->image); 
    		imagedestroy($rinkImage); 
    		return $_rtn;
    }
	
    public static function createFolder($path, $mode=0777)
    {
        if (empty($path)) {
            $_rtn = false;
        } else {
            if (!is_dir($path))
            {
                $_rtn = mkdir($path,$mode,true);
                if (!chmod($path,$mode)) {
                    echo "<script>alert('给目录:{$path}赋权限失败!');</script>";
                }
            } else {
                $_rtn = false;
            }
        }
        return $_rtn;
    }
    
    // 图片优化缩略方法
    public function resizeImage($src_file,$rootPath,$savePath) 
    {
        if ($this->shrink_width < 1 || $this->shrink_height < 1) 
        {
            $this->erro_msg .= "缩略长度或宽度过小"; 
            return false;
        }
        
        // 图像类型
        $type         = exif_imagetype($src_file);
        $support_type = array('1'=>'gif', '2'=>'jpg', '3'=>'png', '6'=>'bmp');
        
    		if($fileInfo = self::is_File_True($src_file)) {
            $this->image = self::readImage($support_type[$type], $src_file);//取原图片信息
    		}
    		
    		if (empty($this->shrink_type))
    		{
            $this->shrink_type = $support_type[$type]; 
    		}
        
        $w        = imagesx($this->image);
        $h        = imagesy($this->image);
        $ratio_w  = 1.0 * $this->shrink_width / $w;
        $ratio_h  = 1.0 * $this->shrink_height / $h;
        $ratio    = 1.0;
        
        if (($ratio_w < 1 && $ratio_h < 1) || ($ratio_w > 1 && $ratio_h > 1)) 
        {
            if ($ratio_w < $ratio_h) {
              $ratio = $ratio_h ; 
            } else {
              $ratio = $ratio_w ;
            }
            
            $inter_w    = (int)($this->shrink_width / $ratio);
            $inter_h    = (int)($this->shrink_height / $ratio);
            $inter_img  = imagecreatetruecolor($inter_w, $inter_h);
            imagecopy($inter_img, $this->image, 0, 0, 0, 0, $inter_w, $inter_h);
            
            $new_img  = imagecreatetruecolor($this->shrink_width, $this->shrink_height);
            imagecopyresampled($new_img, $inter_img, 0, 0, 0, 0, $this->shrink_width, $this->shrink_height, $inter_w, $inter_h);
            
            $_rtn = self::saveImage($new_img, $this->shrink_type, $rootPath,$savePath, substr($this->saveName,0,-4));

        } else {
            $ratio    = $ratio_h > $ratio_w ? $ratio_h : $ratio_w;
            $inter_w  = (int)($w * $ratio);
            $inter_h  = (int) ($h * $ratio);
            $inter_img= imagecreatetruecolor($inter_w, $inter_h);
            
            //将原图缩放比例后裁剪
            imagecopyresampled($inter_img, $this->image, 0, 0, 0, 0, $inter_w, $inter_h, $w, $h);
            
            // 定义一个新的图像
            $new_img  = imagecreatetruecolor($this->shrink_width,$this->shrink_height);
            imagecopy($new_img, $inter_img, 0, 0, 0, 0, $this->shrink_width, $this->shrink_height);
            
            $_rtn = self::saveImage($new_img, $this->shrink_type,$rootPath, $savePath, substr($this->saveName,0,-4));
        }
        return $_rtn;
    }
    
    //获得MAX尺寸的最大图像  
    public static function shotImg($image,$maxs=null)
    {
        $width_orig 	= imagesx($image); 
        $height_orig  = imagesy($image); 
      
        empty($maxs) ? $maxs = max($width_orig,$height_orig)*2/3 : ''; 
        $MAXwidth  = $maxs; 
        $MAXheight = $maxs; 
        
        if ( $MAXwidth  && ( $width_orig < $height_orig )) { 
            $MAXwidth  = ( $MAXheight / $height_orig ) * $width_orig; 
        } else { 
            $MAXheight  = ( $MAXwidth / $width_orig ) * $height_orig; 
        } 
        
        $image_p  =  imagecreatetruecolor ( $MAXwidth, $MAXheight ); 
        imagecopyresampled ( $image_p, $image, 0, 0, 0, 0, $MAXwidth, $MAXheight, $width_orig, $height_orig ); 
        return $image_p;      
    }
	
    private function is_write($path)
    {
        if (!is_dir($path) && !is_file($path))
        {
            if (!self::createFolder($path)) {
                $this->erro_msg .= "创建文件夹{$path}失败!";
                return false;
            }
        }
        	   
        /* 检查目录是否可写 */
        if (!@is_writable($path))
        {
            $this->erro_msg .= $path.'文件夹不可写!';
            return false;
        }
        return true;
    }
	
    private function is_File_True($fname)
    {
        if (!is_file($fname)) {
            $this->erro_msg .= "文件不存在";
            return false;
        }
        $fileInfo = pathinfo($fname);
        return $fileInfo;
    }
	
    function setsaveName($t)
    {
        $this->saveName = $t;
        return $this;
    }
    
    function setsavePath($t)
    {
        $this->savePath = $t;
        return $this;
    }
    
    function setupFileSize($t)
    {
        $this->upfile_size = $t;
        return $this;
    }	
    
    function setShrinkType($t)
    {
        $this->shrink_type = $t;
        return $this;
    }
    
    function setShrinkWidth($t)
    {
        $this->shrink_width = $t;
        return $this;
    }
    
    function setShrinkHeight($t)
    {
        $this->shrink_height = $t;
        return $this;
    }
    
    function setFontTtf($t)
    {
        $this->font_ttf = $t;
        return $this;
    }
    
    function setFontText($t)
    {
        $this->font_text = $t;
        return $this;
    }
    
    function setFontColor($t)
    {
        $this->font_color = $t;
        return $this;
    }
    
    function setFontAngle($t)
    {
        $this->font_angle = $t;
        return $this;
    }
    
    function setFontSize($t)
    {
        $this->font_size = $t;
        return $this;
    }
    
    function setImgWaterPosType($t)
    {
        $this->img_water_PosType = $t;
        return $this;
    }
	
    private function WaterPos($Type)
    {
        switch($Type)
        {
            case 0://左上角
                $this->img_water_x = 10;
                $this->img_water_y = 10;
            break;
            case 1://右上角
                $this->img_water_x = imagesx($this->image) - $this->img_width - 10;
                $this->img_water_y = 10;
            break;
            case 2://居中
                $this->img_water_x = ceil((imagesx($this->image) - $this->img_width) / 2);
                $this->img_water_y = ceil((imagesy($this->image) - $this->img_Height) / 2);
            break;
            case 3://左下角
                $this->img_water_x = 10;
                $this->img_water_y = imagesy($this->image) - $this->img_Height - 10;
            break;
            case 4://右下角
                $this->img_water_x = imagesx($this->image) - $this->img_width - 10;
                $this->img_water_y = imagesy($this->image) - $this->img_Height - 10;
            break;
        }
        return $this;
    }	
	
    public static function readImage($imgType,$imgPath)
    {
        $img = '';
        //var_dump($imgType);
        switch(strtolower($imgType))
        {
            case "gif":
                $img = @imagecreatefromgif($imgPath);
            break;
            case "jpg":
                $img = @imagecreatefromjpeg($imgPath);
            break;
            case "png":
                $img = @imagecreatefrompng($imgPath);
            break;
            case "bmp": 
                $img = @imagecreatefrombmp($imgPath);//自定义函数
            break;
            default:
                $img = @imagecreatefromjpeg($imgPath);
        }
        return $img;
    }
	
    public static function saveImage($im,$imgType,$rootPath,$savePath,$saveName)
    {
        //文件全路径
        $img  = "";
        $path = $savePath.$saveName.".".strtolower($imgType);
        
        switch(strtolower($imgType))
        {
            case "gif":
                $img = @imagegif($im,$rootPath.$path,100);
            break;
            case "jpeg":
                $img = @imagejpeg($im,$rootPath.$path,100);
            break;
            case "png":
                imagesavealpha ($im, true);
                $img = @imagepng($im,$rootPath.$path,100);
            break;
            case "jpg":
                $img = @imagejpeg($im,$rootPath.$path,100);
            break;
            default:
                $img = @imagejpeg($im,$rootPath.$path,100);
        }
        
        if (!$img)
        {
            return false;
        }
        return $path;
    }
	
    function showErrmsg()
    {
        if ($this->erro_msg <> "") {				
            return $this->erro_msg;
        }
        
        return true;
    }	

}

/*
常用调用方式如下:
$filename_info 	= pathinfo($_FILES['file']['name']);
$saveFileName 	= time().rand(0,999999999).".".$filename_info['extension'];		
$savePath 	= ROOT . "/photo/con_img/";           //上传图片目录
$img 				= new Image();
$file 			= $img->setsaveName($saveFileName)->upfile("file", $savePath); //将图片上传至$savePath
												
$smallpath	=	ROOT . "/photo/shrink/";            //缩略图目录
$file_shrink 					= $img->setShrinkWidth(175) //缩略图宽度
														->setShrinkHeight(135)//高度
														->setShrinkType("jpg")//缩略图类型
														->setsaveName("small_".$saveFileName) //缩略图名字
														->resizeImage($file,$smallpath);      //生成缩略图方法

$waterlogo 						= ROOT . "/xyimg/xylogo.gif";
$waterpath						=	ROOT . "/photo/picWater/";         //加有水印图_图片目录
$file_water 					= $img->setsaveName($saveFileName)   
														->setImgWaterPosType(4)        //加水印位置 4 为右下角
														->read_waterImg($waterlogo)    //读取水印LOGO
														->createImg($file,$waterpath); //加水印方法
														
$img -> showErrmsg();  //出错提示
*/
?>
 
