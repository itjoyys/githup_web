<?php
    
    function p($array){

    	dump($array, 1, '<pre>' , 0);
    }
  
//JSON输出中文

  function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
    static $recursive_counter = 0;
    if (++$recursive_counter > 1000) {
        die('possible deep recursion attack');
    }
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            arrayRecursive($array[$key], $function, $apply_to_keys_also);
        } else {
            $array[$key] = $function($value);
        }
        if ($apply_to_keys_also && is_string($key)) {
            $new_key = $function($key);
            if ($new_key != $key) {
                $array[$new_key] = $array[$key];
                unset($array[$key]);
            }
        }
    }
    $recursive_counter--;
}

  function JSON($array) {
        arrayRecursive($array, 'urlencode', true);
        $json = json_encode($array);
        return urldecode($json);
    }

//字符串截取函数
   function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)  
{  
    if(function_exists("mb_substr")){   
    if($suffix)   
      return mb_substr($str, $start, $length, $charset)."...";  
      else  
      return mb_substr($str, $start, $length, $charset);   
       }  
    elseif(function_exists('iconv_substr')) {  
        if($suffix)   
       return iconv_substr($str,$start,$length,$charset)."...";  
       else  
       return iconv_substr($str,$start,$length,$charset);  
    }  
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";  
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";  
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";  
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";  
    preg_match_all($re[$charset], $str, $match);  
    $slice = join("",array_slice($match[0], $start, $length));  
    if($suffix) return $slice."…";  
    return $slice;  
}

//获取当前星期
function week($date){
    $datearr = explode("-",$date);     //将传来的时间使用“-”分割成数组
    $year = $datearr[0];       //获取年份
    $month = sprintf('%02d',$datearr[1]);  //获取月份
    $day = sprintf('%02d',$datearr[2]);      //获取日期
    $hour = $minute = $second = 0;   //默认时分秒均为0
    $dayofweek = mktime($hour,$minute,$second,$month,$day,$year);    //将时间转换成时间戳
    $shuchu = date("w",$dayofweek);      //获取星期值
    $weekarray=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
    return $weekarray[$shuchu];
}

    //截取utf8字符串支持中文
    function utf8Substr($str, $from, $len){
      return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.

                       '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',

                       '$1',$str);

      }

?>