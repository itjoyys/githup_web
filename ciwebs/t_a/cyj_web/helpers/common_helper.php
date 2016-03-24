<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

//常用函数
function p($arr){
	print_r('<pre>');
	print_r($arr);
	print_r('<pre>');
}



// 单选框选中
function radio_check($val_a, $val_b)
{
	if ( isset($val_a) &&  isset($val_b)) {
		if ($val_a == $val_b) {
			echo "checked=\"checked\"";
		}
	}
}

//获得生肖组合数组
function func_get_shenxiao($type_id,$nianfen=2016){
	 if(func_nowtime('Y-m-d H:i:s','now') > '2016-02-08 00:00:00'){
		$nianfen=2016;
	  }else{
		$nianfen=2015;
	  }
    $array = array();
    if($type_id == 230 || $type_id == 229 || $type_id == 232 || $type_id == 227){
        $arr = func_shenxiao_2014(49);
    }elseif($type_id == 231){
        $arr = func_shenxiao_2014();
    }
    $shuxiang=func_get_shuxiang();
    $count=count($shuxiang);
    $xu = intval($nianfen)-2014;//变化参数
    for($i=0;$i<$count;$i++){
        $xuhao =$i+$xu;
        if($xuhao  >= 12){
            $xuhao = $xuhao% 12 ;
        }
        $array[$shuxiang[$xuhao]] = $arr[$i];

    }
    return $array;
}
//获得六合彩尾数数组
function func_get_weishu(){
    $array = array();
    $array[] = array(10,20,30,40);
    $array[] = array('01',11,21,31,41);
    $array[] = array('02',12,22,32,42);
    $array[] = array('03',13,23,33,43);
    $array[] = array('04',14,24,34,44);
    $array[] = array('05',15,25,35,45);
    $array[] = array('06',16,26,36,46);
    $array[] = array('07',17,27,37,47);
    $array[] = array('08',18,28,38,48);
    $array[] = array('09',19,29,39,49);
    return $array;
}

function banbo_arr($key){
    $array = array();
    $array['红单']=array('01','07',13,19,23,29,35,45);//红单
    $array['红双']=array('02','08',12,18,24,30,34,40,46);//红双
    $array['红大']=array(29,30,34,35,40,45,46);//红大
    $array['红小']=array('01','02','07','08',12,13,18,19,23,24);//红小
    $array['绿单']=array('05',11,17,21,27,33,39,43);//绿单
    $array['绿双']=array('06',16,22,28,32,38,44);//绿双
    $array['绿大']=array(27,28,32,33,38,39,43,44);//绿大
    $array['绿小']=array('05','06',11,16,17,21,22);//绿小
    $array['蓝单']=array('03','09',15,25,31,37,41,47);//蓝单
    $array['蓝双']=array('04',10,14,20,26,36,42,48);//蓝双
    $array['蓝大']=array(25,26,31,36,37,41,42,47,48);//蓝大
    $array['蓝小']=array('03','04','09',10,14,15,20);//蓝小
    $array['红合单']=array('01','07',23,29,45,12,18,30,34);//红合单
    $array['红合双']=array(13,19,35,'02','08',24,40,46);//红合双
    $array['绿合单']=array('05',16,21,27,32,38,43);//绿合单
    $array['绿合双']=array('06',11,17,22,28,33,39,44);//绿合双
    $array['蓝合单']=array('03','09',10,14,25,36,41,47);//蓝合双
    $array['蓝合双']=array('04',15,20,26,31,37,42,48);//蓝合双


    return $array[$key];
}


function yanzheng($name){
    $array['五不中'] = 5;
    $array['六不中'] = 6;
    $array['七不中'] = 7;
    $array['八不中'] = 8;
    $array['九不中'] = 9;
    $array['十不中'] = 10;
    $array['十一不中'] = 11;
    $array['十二不中'] = 12;

    $array['二肖'] = 2;
    $array['三肖'] = 3;
    $array['四肖'] = 4;
    $array['五肖'] = 5;
    $array['六肖'] = 6;
    $array['七肖'] = 7;
    $array['八肖'] = 8;
    $array['九肖'] = 9;
    $array['十肖'] = 10;
    $array['十一肖'] = 11;

    $array['二肖连中'] = 2;
    $array['三肖连中'] = 3;
    $array['四肖连中'] = 4;
    $array['五肖连中'] = 5;

    $array['二肖连不中'] = 2;
    $array['三肖连不中'] = 3;
    $array['四肖连不中'] = 4;

    $array['二尾连中'] = 2;
    $array['三尾连中'] = 3;
    $array['四尾连中'] = 4;
    $array['二尾连不中'] = 2;
    $array['三尾连不中'] = 3;
    $array['四尾连不中'] = 4;
    return $array[$name];
}


function func_zoushi($result, $ball_1, $daxiao,$type,$liangmian=false)
{

    // 1,查询结果 3,第几球字段名 4,玩法
    $zhong = array(01,02,03,04,05,06,07);
    $fa = array('08','09',10,11,12,13,14);
    $bai = array(15,16,17,18,19,20);
    $fagnwei_dong = array(01,05,'09',13,17);
    $fagnwei_nan = array(02,06,10,14,18);
    $fagnwei_xi = array(03,07,11,15,19);
    $fagnwei_bei = array(04,'08',12,16,20);
    $data = array();
    foreach ($result as $k => $v) {

        if ($daxiao == '大小') {
          if(isset($v['ball_10'])){
            if ($v['ball_'.$ball_1] < 6) {
                $data[] = '小';
            } else {
                $data[] = '大';
            }
          }elseif(isset($v['ball_8'])){
            if ($v['ball_'.$ball_1] < 11) {
                $data[] = '小';
            } else {
                $data[] = '大';
            }
          }else{
            if ($v['ball_'.$ball_1] < 5) {
                $data[] = '小';
            } else {
                $data[] = '大';
            }
          }

        } elseif ($daxiao == '单双') {
            if ($v['ball_'.$ball_1] % 2 == 1) {
                $data[] = '单';
            } else {
                $data[] = '双';
            }
        } elseif ($daxiao == '冠亚和') {
          $data[] = $v['ball_1'] + $v['ball_2'];

        }  elseif ($daxiao == '冠亚和大小') {
          $num = $v['ball_1'] + $v['ball_2'];
          if ($num <= 11) {
                $data[] = '小';
            } else {
                $data[] = '大';
            }
        }  elseif ($daxiao == '冠亚和单双') {
          $num = $v['ball_1'] + $v['ball_2'];
          if ($num % 2 == 1) {
                $data[] = '单';
            } else {
                $data[] = '双';
            }
        } elseif ($daxiao == '合数单双') {
          $num = str_split($v['ball_'.$ball_1]);
          $num_he = 0;
          foreach ($num as $k => $v) {
            $num_he += $v;
          }
            if ($num_he % 2 == 1) {
                $data[] = '合单';
            } else {
                $data[] = '合双';
            }
        } elseif ($daxiao == '尾大小') {
          $num = str_split($v['ball_'.$ball_1]);
          $wei = $num[(count($num)-1)];
            if ($wei < 5) {
                $data[] = ' 小';
            } else {
                $data[] = ' 大';
            }
        } elseif ($daxiao == '总和尾大小') {
          $he = $v['ball_1'] + $v['ball_2'] + $v['ball_3'] + $v['ball_4'] + $v['ball_5'] + $v['ball_6'] + $v['ball_7'] + $v['ball_8'] + $v['ball_9'] + $v['ball_10'] + $v['ball_11'] + $v['ball_12'] + $v['ball_13'] + $v['ball_14'] + $v['ball_15'] + $v['ball_16'] + $v['ball_17'] + $v['ball_18'] + $v['ball_19'] + $v['ball_20'];
          $num = str_split($he);
          $wei = $num[(count($num)-1)];
          if ($wei < 5) {
              $data[] = '小';
          } else {
              $data[] = '大';
          }
        } elseif ($daxiao == '方位') {
          $num = $v['ball_'.$ball_1];
          if(in_array($num, $fagnwei_dong)){
            $data[] = '东';
          }elseif(in_array($num, $fagnwei_nan)){
            $data[] = '南';
          }elseif(in_array($num, $fagnwei_xi)){
            $data[] = '西';
          }elseif(in_array($num, $fagnwei_bei)){
            $data[] = '北';
          }
        } elseif ($daxiao == '中发白') {
          $num = $v['ball_'.$ball_1];
          if(in_array($num, $zhong)){
            $data[] = '中';
          }elseif(in_array($num, $fa)){
            $data[] = '发';
          }elseif(in_array($num, $bai)){
            $data[] = '白';
          }
        } elseif ($daxiao == '总和单双') {
          if(isset($v['ball_20'])){
            if (($v['ball_1'] + $v['ball_2'] + $v['ball_3'] + $v['ball_4'] + $v['ball_5'] + $v['ball_6'] + $v['ball_7'] + $v['ball_8'] + $v['ball_9'] + $v['ball_10'] + $v['ball_11'] + $v['ball_12'] + $v['ball_13'] + $v['ball_14'] + $v['ball_15'] + $v['ball_16'] + $v['ball_17'] + $v['ball_18'] + $v['ball_19'] + $v['ball_20']) % 2 == 1) {
                    $data[] = '单';
                } else {
                    $data[] = '双';
                }
          }elseif(isset($v['ball_4'])){
                if (($v['ball_1'] + $v['ball_2'] + $v['ball_3'] + $v['ball_4'] + $v['ball_5']) % 2 == 1) {
                    $data[] = '单';
                } else {
                    $data[] = '双';
                }
            }else{
                if (($v['ball_1'] + $v['ball_2'] + $v['ball_3'] ) % 2 == 1) {
                    $data[] = '单';
                } else {
                    $data[] = '双';
                }
            }

        } elseif ($daxiao == '总和大小') {
          if(isset($v['ball_8'])){
            $num_limit = 84;
            $num = $v['ball_1'] + $v['ball_2'] + $v['ball_3'] + $v['ball_4'] + $v['ball_5'] + $v['ball_6'] + $v['ball_7'] + $v['ball_8'] + $v['ball_9'] + $v['ball_10'] + $v['ball_11'] + $v['ball_12'] + $v['ball_13'] + $v['ball_14'] + $v['ball_15'] + $v['ball_16'] + $v['ball_17'] + $v['ball_18'] + $v['ball_19'] + $v['ball_20'];
            if ($num < $num_limit) {
                    $data[] = '小';
            } elseif($num == $num_limit) {
                $data[] = '和';
            } else{
                $data[] = '大';
            }
          }elseif(isset($v['ball_4'])){
                $num_limit=23;
                if (($v['ball_1'] + $v['ball_2'] + $v['ball_3'] + $v['ball_4'] + $v['ball_5']) < $num_limit) {
                    $data[] = '小';
                } else {
                    $data[] = '大';
                }
            }else{
                $num_limit=14;
                if (($v['ball_1'] + $v['ball_2'] + $v['ball_3'] ) < $num_limit) {
                    $data[] = '小';
                } else {
                    $data[] = '大';
                }
            }


        }elseif($daxiao=="龙虎"){
          if($ball_1 == 0){
            if(isset($v['ball_4'])){
                //时时彩
                if ($v['ball_1']>$v['ball_5']) {
                    $data[] = '龙';
                }elseif($v['ball_1']==$v['ball_5']){
                    $data[] = '和';
                }else {
                    $data[] = '虎';
                }
            }else{
                //福彩，PL3
                if ($v['ball_1']>$v['ball_3']) {
                    $data[] = '龙';
                }elseif($v['ball_1']==$v['ball_3']){
                    $data[] = '合';
                }else {
                    $data[] = '虎';
                }
            }
          }elseif($ball_1 == 1){
            if(isset($v['ball_10'])){
               if ($v['ball_1']>$v['ball_10']) {
                    $data[] = '龙';
                }elseif($v['ball_1']==$v['ball_10']){
                    $data[] = '合';
                }else {
                    $data[] = '虎';
              }
            }else{
              if ($v['ball_1']>$v['ball_8']) {
                    $data[] = '龙';
                }elseif($v['ball_1']==$v['ball_8']){
                    $data[] = '合';
                }else {
                    $data[] = '虎';
             }
            }

          }elseif($ball_1 == 2){
             if(isset($v['ball_10'])){
               if ($v['ball_2']>$v['ball_9']) {
                    $data[] = '龙';
                }elseif($v['ball_2']==$v['ball_9']){
                    $data[] = '合';
                }else {
                    $data[] = '虎';
              }
            }else{
              if ($v['ball_2']>$v['ball_7']) {
                      $data[] = '龙';
                  }elseif($v['ball_2']==$v['ball_7']){
                      $data[] = '合';
                  }else {
                      $data[] = '虎';
              }
            }
          }elseif($ball_1 == 3){
             if(isset($v['ball_10'])){
               if ($v['ball_3']>$v['ball_8']) {
                    $data[] = '龙';
                }elseif($v['ball_3']==$v['ball_8']){
                    $data[] = '合';
                }else {
                    $data[] = '虎';
              }
            }else{
              if ($v['ball_3']>$v['ball_6']) {
                      $data[] = '龙';
                  }elseif($v['ball_3']==$v['ball_6']){
                      $data[] = '合';
                  }else {
                      $data[] = '虎';
              }
           }
          }elseif($ball_1 == 4){
            if(isset($v['ball_10'])){
               if ($v['ball_4']>$v['ball_7']) {
                    $data[] = '龙';
                }elseif($v['ball_4']==$v['ball_7']){
                    $data[] = '合';
                }else {
                    $data[] = '虎';
              }
            }else{
              if ($v['ball_4']>$v['ball_5']) {
                      $data[] = '龙';
                  }elseif($v['ball_4']==$v['ball_5']){
                      $data[] = '合';
                  }else {
                      $data[] = '虎';
              }
            }
          }elseif($ball_1 == 5){
            if(isset($v['ball_10'])){
               if ($v['ball_5']>$v['ball_6']) {
                    $data[] = '龙';
                }elseif($v['ball_5']==$v['ball_6']){
                    $data[] = '合';
                }else {
                    $data[] = '虎';
              }
            }
          }
        }elseif($daxiao == '号码'){
          $data[] = $v['ball_'.$ball_1];
        }
    }
    // p($data);
    if($liangmian){
      return hebing_arr($data,$ball_1,$daxiao,true,$result[0]['ball_10']);
    }else{
      return hebing_arr($data,$ball_1,$daxiao,false,$result[0]['ball_10']);
    }

}
// array("c"=>"7:1,3:1,1:1,5:1,0:1,6:1,8:1,3:1,5:1,6:1,0:1,7:1,3 :1,1:1,4:2,9:1,7:2,8:1,5:1,9:1,5:1,0:1,7:1,6:1,0:1,4:1,0:1,6:1","n" => "第一球" , "p" => 1),
// "双:1,单:1,双:1,单:4,双:2,单:2,双:2,单:1,双:1,单:2,双:1,单:2,双:1,单:1,双:2,单:3,双:1,单:1,双:1"
function hebing_arr($arr,$ball_1,$daxiao,$liangmian=false,$ball_10=''){
  // p($arr);
  $n = '';
  $p = 0;
  $m = '总和';
  if($ball_1 == 1 && !$ball_10){
    $m = '第一球';
  }elseif($ball_1 == 2 && !$ball_10){
    $m = '第二球';
  }elseif($ball_1 == 3 && !$ball_10){
    $m = '第三球';
  }elseif($ball_1 == 4 && !$ball_10){
    $m = '第四球';
  }elseif($ball_1 == 5 && !$ball_10){
    $m = '第五球';
  }elseif($ball_1 == 6 && !$ball_10){
    $m = '第六球';
  }elseif($ball_1 == 7 && !$ball_10){
    $m = '第七球';
  }elseif($ball_1 == 8 && !$ball_10){
    $m = '第八球';
  }elseif($ball_1 == 1 && $ball_10){
    $m = '冠军';
  }elseif($ball_1 == 2 && $ball_10){
    $m = '亚军';
  }elseif($ball_1 == 3 && $ball_10){
    $m = '第三名';
  }elseif($ball_1 == 4 && $ball_10){
    $m = '第四名';
  }elseif($ball_1 == 5 && $ball_10){
    $m = '第五名';
  }elseif($ball_1 == 6 && $ball_10){
    $m = '第六名';
  }elseif($ball_1 == 7 && $ball_10){
    $m = '第七名';
  }elseif($ball_1 == 8 && $ball_10){
    $m = '第八名';
  }elseif($ball_1 == 9 && $ball_10){
    $m = '第九名';
  }elseif($ball_1 == 10 && $ball_10){
    $m = '第十名';
  }elseif($daxiao == '龙虎'){
    $m = '龙虎';
  }elseif($daxiao == '总和尾大小'){
    $m = '总和尾';
  }

  if($daxiao=='号码' && $ball_1 == 1){
    $n = '第一球';
    $p = 1;
  }elseif($daxiao=='号码' && $ball_1 == 2){
    $n = '第二球';
    $p = 2;
  }elseif($daxiao=='号码' && $ball_1 == 3){
    $n = '第三球';
    $p = 3;
  }elseif($daxiao=='号码' && $ball_1 == 4){
    $n = '第四球';
    $p = 4;
  }elseif($daxiao=='号码' && $ball_1 == 5){
    $n = '第五球';
    $p = 5;
  }elseif($daxiao=='号码' && $ball_1 == 6){
    $n = '第六球';
    $p = 6;
  }elseif($daxiao=='号码' && $ball_1 == 7){
    $n = '第七球';
    $p = 7;
  }elseif($daxiao=='号码' && $ball_1 == 8){
    $n = '第八球';
    $p = 8;
  }elseif($daxiao=='单双' || $daxiao=='大小' || $daxiao=='总和大小' || $daxiao=='总和单双' || $daxiao=='龙虎' || $daxiao=='尾大小' || $daxiao=='合数单双' || $daxiao=='方位' || $daxiao=='中发白' || $daxiao=='总和尾大小' ){
    $n = $daxiao;
    $p = $ball_1;
  }elseif($daxiao=='冠亚和' || $daxiao=='冠亚和大小' || $daxiao=='冠亚和单双'){
    $n = $daxiao;
    $p = 1;
  }


  $arr_1 = array();
  foreach ($arr as $k => $v) {
    if(empty($arr_1)){
      $arr_1[] = $v.':1';
    }else{
      $aa = $arr_1[count($arr_1)-1];
      $bb = explode(':', $aa);
      if($bb[0] == $v){
        $arr_1[count($arr_1)-1] = $v.':'.($bb[1]+1);
      }else{
        $arr_1[] = $v.':1';
      }
    }
  }
  $str = '';
  foreach ($arr_1 as $k => $v) {
    $str .= $v.',';
  }
  $c = rtrim($str,',');
  // echo $c;echo "<br/>";
  if($liangmian){
    if(!empty($c)){
      $c_arr = explode(',', $c);
      $c_arr_1 = explode(':', $c_arr[0]);
      if($c_arr_1[1] > 1){
        // ["第一球-单","4"]
// var_dump($c_arr_1);
        if($c_arr_1[0] != trim($c_arr_1[0])){
          $c_arr_1[0] = '尾'.trim($c_arr_1[0]);
        }
        $res = $m.'-'.$c_arr_1[0];
        $res_arr = array(0=>$res , 1=>$c_arr_1[1]);

        return $res_arr;
      }else{
        return false;
      }
    }
  }else{
    $temp = array('c'=>$c , 'n'=>$n , 'p'=> $p);
    return $temp;
  }


}

// 多选框选中
function check_box($val_a, $val_b)
{
	if ( isset($val_a) &&  isset($val_b)) {
		if ($val_a == $val_b) {
			echo "checked=\"true\"";
		}
	}
}

// 多选框选中
function check_box2($val_a, $val_b)
{
	$if = 0;
	if ( isset($val_a) &&  isset($val_b)) {
		foreach (explode(',', $val_a) as $s) {
			if ($s == $val_b) {
				$if = 1;
			}
		}
		if ($if == 1) {
			echo "checked=\"true\"";
		}
	}
}

function select_check($field, $val)
{

	if (isset($val)) {
		if ($field == $val) {
			echo "selected=\"selected\"";
		}
	}
}



function message($value,$url=""){ //默认返回上一页
	header("Content-type: text/html; charset=utf-8");
	$js  = "<script type=\"text/javascript\" language=\"javascript\">\r\n";
	$js .= "alert(\"".$value."\");\r\n";
	if($url) $js .= "window.location.href=\"$url\";\r\n";
	else $js .= "window.history.go(-1);\r\n";
	$js .= "</script>\r\n";
	echo $js;
	exit;
}

//获取组合，返回所有组合的数组
function func_get_zuhe($arr,$m){
    $result = array();
      if ($m ==1){
      return $arr;
    }
    if ($m == count($arr)){
    $result[] = implode(',' , $arr);
    return $result;
    }
    $aaa = $arr[0]; //a
    unset($arr[0]);
    $arr = array_values($arr);
    $temp_list1 = func_get_zuhe($arr, ($m-1));
    foreach ($temp_list1 as $s){
      $s = $aaa.','.$s;
      $result[] = $s;
    }
    unset($temp_list1);
    $temp_list2 = func_get_zuhe($arr, $m);
    foreach ($temp_list2 as $s){
      $result[] = $s;
    }
    unset($temp_list2);
    return $result;
}

//网站资讯系统 文案类别
function case_type($type){
	switch ($type) {
		case '1':
			return '线上入款';
			break;
		case '2':
			return '公司入款';
			break;
		case '3':
			return '关于我们';
			break;
		case '4':
			return '联系我们';
			break;
		case '5':
			return '代理联盟';
			break;
		case '6':
			return '存款帮助';
			break;
		case '7':
			return '取款帮助';
			break;
		case '8':
			return '常见问题';
			break;
		case '9':
			return '会员注册';
			break;
		case '10':
			return '代理注册';
			break;
		case '11':
			return '网站LOGO';
			break;
		case '12':
			return '会员中心LOGO';
			break;
		case '13':
			return '首页轮播图';
			break;
		case '14':
			return '优惠活动';
			break;
		case '15':
			return '首页游戏图';
			break;
		case '16':
			return '左边浮动';
			break;
		case '17':
			return '右边浮动';
			break;
		case '18':
		    return '开户协议';
		    break;
	}
}

//编辑器图片上传处理
function Uedit(){
	header("Content-Type: text/html; charset=utf-8");
	$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("config.json")), true);
	$action = $_GET['action'];
	switch ($action) {
	    case 'config':
	        $result =  json_encode($CONFIG);
	        break;

	    /* 上传图片 */
	    case 'uploadimage':
	    /* 上传涂鸦 */
	    case 'uploadscrawl':
	    /* 上传视频 */
	    case 'uploadvideo':
	    /* 上传文件 */
	    case 'uploadfile':
	        $result = include("action_upload.php");
	        break;
	    /* 列出图片 */
	    case 'listimage':
	        $result = include("action_list.php");
	        break;
	    /* 列出文件 */
	    case 'listfile':
	        $result = include("action_list.php");
	        break;

	    /* 抓取远程文件 */
	    case 'catchimage':
	        $result = include("action_crawler.php");
	        break;
	    default:
	        $result = json_encode(array(
	            'state'=> '请求地址出错'
	        ));
	        break;
	}
	/* 输出结果 */
	if (isset($_GET["callback"])) {
	    if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
	        echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
	    } else {
	        echo json_encode(array(
	            'state'=> 'callback参数不合法'
	        ));
	    }
	} else {
	    echo $result;
	}
}

//体育交易记录
function return_status($status){
	if($status==0)  $status='未结算';
	else if($status==1)  $status='<span style="color:#FF0000;">赢</span>';
	else if($status==2)  $status='<span style="color:#00CC00;">输</span>';
	else if($status==8)  $status='和局';
	else if($status==3)  $status='注单无效';
	else if($status==4)  $status='<span style="color:#FF0000;">赢一半</span>';
	else if($status==5)  $status='<span style="color:#00CC00;">输一半</span>';
	else if($status==6)  $status='进球无效';
	else if($status==7)  $status='红卡取消';
	return $status;
}

function return_color($ball_sort){
	if ($ball_sort=="足球滚球"){return "#0066FF";}else{return "#336600";}
}

function return_typec_cp($type,$mingxi){
	if($type=='六合彩' || $type =='北京赛车PK拾'){
		return ':'.$mingxi;
	}
}

function return_result_cp($v){
	if($v['js'] == 0){
		if($v['status'] == 0){
			$result='未结算'; //没有结算的·结果为0
		}elseif($v['status'] == 4){
			$result='注单取消';
		}

	}else if($v['js'] == 1){
		if($v['win']==0){
			$result='未中奖';
		}else{
			$result=$v['money']*$v['odds'];
		}
	}else if($v['js'] == 3){
		$result='和局';
	}
	return $result;
}

function get_cp_list(){
	return $array = array(
		'1' => '重庆时时彩',
		'2' => '重庆快乐十分',
		'3' => '广东快乐十分',
		'4' => '北京赛车PK拾',
		'5' => '福彩3D',
		'6' => '排列三',
		'7' => '北京快乐8',
		'8' => '六合彩',
		'9' => '江苏快3',
		'10' => '吉林快3',
		'11' => '新疆时时彩',
		'12' => '天津时时彩',
		// '13' => '江西时时彩',
	);
}

function get_correspondence_list(){
	return $array = array(
			'1' => '額度轉換',
			'2' => '体育下注',
			'15' => '体育派彩',
			'3' => '彩票下注',
			'14' => '彩票派彩',
			'10' => '线上入款',
			'11' => '公司入款',
			'19' => '线上取款',
			'9' => '优惠退水',
      '33' => '自助返水',
			'1-12-6' => '优惠活动',
			'1-12-3' => '人工存入',
			'2-12-4' => '人工取出',
			'23' => '系统取消出款',
			'7' => '系统拒绝出款',
			'12' => '人工存款與取款',
			'in' => '入款明细',
			'out' => '出款明细',
	);
}

function cash_type_r($cash_type){
	switch ($cash_type) {
		case '1':
			return '額度轉換';
			break;
		case '2':
			return '体育下注';
			break;
		case '3':
			return '彩票下注';
			break;
		case '4':
			return '视讯下注';
			break;
		case '5':
			return '彩票派彩';
			break;
		case '6':
			return '活动优惠';
			break;
		case '7':
			return '系统拒绝出款';
			break;
		case '8':
			return '系统取消出款';
			break;
		case '9':
			return '优惠退水';
			break;
		case '10':
			return '在线存款';
			break;
		case '11':
			return '公司入款';
			break;
		case '12':
			return '存入取出';
			break;
		case '13':
			return '优惠冲销';
			break;
		case '14':
			return '彩票派彩';
			break;
		case '15':
			return '体育派彩';
			break;
		case '19':
			return '线上取款';
			break;
		case '20':
			return '和局返本金';
		case '22':
			return '体育无效注单';
		case '23':
			return '系统取消出款';
		case '24':
			return '系统拒绝出款';
		case '25':
			return '彩票无效注单';
		case '26':
			return '彩票无效注单(扣本金)';
		case '27':
			return '注单取消(彩票)';
		case '28':
			return '注单取消(体育)';
    case '33':
      return '自助返水';
    case '34':
        return 'EG电子下注';
    case '35':
        return 'EG电子派彩';
    case '36':
        return '注单取消(EG电子)';
	}
}

//返回交易类别
function cash_do_type_r($do_type){
	switch ($do_type) {
		case '1':
			return '存入';
			break;
		case '2':
			return '取出';
			break;
		case '3':
			return '人工存入';
			break;
		case '4':
			return '人工取出';
			break;
		case '5':
			return '扣除派彩';
			break;
		case '6':
			return '返回本金';
			break;
	}
}

function str_cut($str){
	$arr = array();
	if(strstr($str, ',操作者') || strstr($str, ',返水操作者')){
		if(strstr($str, ',操作者')){
			$arr = explode(',操作者', $str);
			return $arr[0];
		}elseif(strstr($str, ',返水操作者')){
			$arr = explode(',返水操作者', $str);
			return $arr[0];
		}

	}else{
		return $str;
	}
}

//判断是否为空
function ifempty($data){
	if(empty($data)){
		return 0;
	}else{
		return $data;
	}
}

//选择样式
function select_hover($data,$val){
	if($data == $val)return "hover";
}

function set_stype($url){
	$server =$_SERVER['REQUEST_URI'];
	if(!empty($url)){
		if($server==$url){
			return "style='color:#ffffff;background:#bc5a83;padding:2px'";
		}
	}
}

//ag 玩法
function ag_type($gt,$type){
	if($gt == "ag"){
		switch ($type) {
			case 'BAC':
				return '百家乐';
				break;
			case 'CBAC':
				return '包桌百家乐';
				break;
			case 'LINK':
				return '连环百家乐';
				break;
			case 'DT':
				return '龙虎';
				break;
			case 'SHB':
				return '骰宝';
				break;
			case 'ROU':
				return '轮盘';
				break;
			case 'FT':
				return '番攤';
				break;
			case 'SL1':
				return '巴西世界杯';
				break;
			case 'PK_J':
				return '视频扑克(杰克高手)';
				break;
			case 'SL2':
				return '疯狂水果店';
				break;
			case 'SL3':
				return '3D 水族馆';
				break;
			case 'SL4':
				return '极速赛车';
				break;
			case 'PKBJ':
				return '新视频扑克(杰克高手)';
				break;
			case 'FRU':
				return '水果拉霸';
				break;
		}
	}elseif ($gt == "ct"){
		switch ($type) {
	        case '1':
	            return '百家乐';
	            break;
	        case '2':
	            return '轮盘';
	            break;
	        case '3':
	            return '骰宝';
	            break;
	        case '4':
	            return '龙虎';
	            break;
	        case '5':
	            return '番摊/骰宝翻摊';
	            break;
	        case '7':
	            return '保险百家乐';
	            break;
	        case '9':
	            return '色碟';
	            break;
	    }
	}elseif ($gt == "og"){
		switch (type) {
			case '11':
				return '百家乐';
				break;
			case '12':
				return '龙虎';
				break;
			case '13':
				return '轮盘';
				break;
			case '14':
				return '骰宝';
				break;
			case '15':
				return '德州扑克';
				break;
			case '16':
				return '番摊';
				break;
		}
	}else{
		return $type;
	}

}


//重庆时时彩开奖函数
//$type 1总和 2和大小 3和单双 4龙虎和 5前三 6中三 7后三 8斗牛 9梭哈
function ssc_auto($num , $type){
    $zh = $num[0]+$num[1]+$num[2]+$num[3]+$num[4];
    if($type==1){
        return $zh;
    }
    if($type==2){
        if($zh>=23){
            return '大';
        }
        if($zh<=23){
            return '小';
        }
    }
    if($type==3){
        if($zh%2==0){
            return '双';
        }else{
            return '单';
        }
    }
    if($type==4){
        if($num[0]>$num[4]){
            return '龙';
        }
        if($num[0]<$num[4]){
            return '虎';
        }
        if($num[0]==$num[4]){
            return '和';
        }
    }
    if($type==5){

        $n1=$num[0];
        $n2=$num[1];
        $n3=$num[2];
        if(($n1==0 || $n2==0 || $n3==0) && ($n1==9 || $n2==9 || $n3==9)){
            if($n1==0){
                $n1=10;
            }
            if($n2==0){
                $n2=10;
            }
            if($n3==0){
                $n3=10;
            }
        }

        if($n1==$n2 && $n2==$n3){
            return "豹子";
        }elseif(($n1==$n2) || ($n1==$n3) || ($n2==$n3)){
            return "对子";
        }elseif(($n1==10 || $n2==10 || $n3==10) && ($n1==9 || $n2==9 || $n3==9) && ($n1==1 || $n2==1 || $n3==1)){
            return "顺子";
        }elseif( ( (abs($n1-$n2)==1) && (abs($n2-$n3)==1) ) || ((abs($n1-$n2)==2) && (abs($n1-$n3)==1) && (abs($n2-$n3)==1)) ||((abs($n1-$n2)==1) && (abs($n1-$n3)==1)) ){
            return "顺子";
        }elseif((abs($n1-$n2)==1) || (abs($n1-$n3)==1) || (abs($n2-$n3)==1)){
            return "半顺";
        }else{
            return "杂六";
        }
    }
    if($type==6){
        $n1=$num[1];
        $n2=$num[2];
        $n3=$num[3];
        if(($n1==0 || $n2==0 || $n3==0) && ($n1==9 || $n2==9 || $n3==9)){
            if($n1==0){
                $n1=10;
            }
            if($n2==0){
                $n2=10;
            }
            if($n3==0){
                $n3=10;
            }
        }

        if($n1==$n2 && $n2==$n3){
            return "豹子";
        }elseif(($n1==$n2) || ($n1==$n3) || ($n2==$n3)){
            return "对子";
        }elseif(($n1==10 || $n2==10 || $n3==10) && ($n1==9 || $n2==9 || $n3==9) && ($n1==1 || $n2==1 || $n3==1)){
            return "顺子";
        }elseif( ( (abs($n1-$n2)==1) && (abs($n2-$n3)==1) ) || ((abs($n1-$n2)==2) && (abs($n1-$n3)==1) && (abs($n2-$n3)==1)) ||((abs($n1-$n2)==1) && (abs($n1-$n3)==1)) ){
            return "顺子";
        }elseif((abs($n1-$n2)==1) || (abs($n1-$n3)==1) || (abs($n2-$n3)==1)){
            return "半顺";
        }else{
            return "杂六";
        }
    }
    if($type==7){
        $n1=$num[2];
        $n2=$num[3];
        $n3=$num[4];
        if(($n1==0 || $n2==0 || $n3==0) && ($n1==9 || $n2==9 || $n3==9)){
            if($n1==0){
                $n1=10;
            }
            if($n2==0){
                $n2=10;
            }
            if($n3==0){
                $n3=10;
            }
        }

        if($n1==$n2 && $n2==$n3){
            return "豹子";
        }elseif(($n1==$n2) || ($n1==$n3) || ($n2==$n3)){
            return "对子";
        }elseif(($n1==10 || $n2==10 || $n3==10) && ($n1==9 || $n2==9 || $n3==9) && ($n1==1 || $n2==1 || $n3==1)){
            return "顺子";
        }elseif( ( (abs($n1-$n2)==1) && (abs($n2-$n3)==1) ) || ((abs($n1-$n2)==2) && (abs($n1-$n3)==1) && (abs($n2-$n3)==1)) ||((abs($n1-$n2)==1) && (abs($n1-$n3)==1)) ){
            return "顺子";
        }elseif((abs($n1-$n2)==1) || (abs($n1-$n3)==1) || (abs($n2-$n3)==1)){
            return "半顺";
        }else{
            return "杂六";
        }
    }


    if($type==8){//斗牛
        $n1=$num[0];
        $n2=$num[1];
        $n3=$num[2];
        $n4=$num[3];
        $n5=$num[4];
        $array=$num;
        $zh=0;
        //$zh1=$n1+$n2+$n3+$n4+$n5;
        $zh2=0;
        for($i=0;$i<5;$i++){

            $zh=-1;
            $j=$i+1;
            for($ii=$j;$ii<5;$ii++){

                $jj=$ii+1;
                $zh=-1;
                for($iii=$jj;$iii<5;$iii++){
                    $zh=$array[$i]+$array[$ii]+$array[$iii];


                    if($zh==0 || $zh%10==0) {

                        foreach ($array as $key => $value) {
                            if($key==$i)unset($array[$key]);
                            if($key==$ii)unset($array[$key]);
                            if($key==$iii)unset($array[$key]);
                        }

                        foreach ($array as $key => $value) {
                            $zh2+=$value;
                        }

                        //echo $zh."|".$zh2."<br>";
                        break;
                    }
                }
                if($zh==0 || $zh%10==0) break;
            }
            if($zh==0 || $zh%10==0) break;
        }//echo "--".$zh."|".$zh2."<br>";
        if($zh==0 || $zh%10==0){
            if($zh2>10){
                return "牛".($zh2-10);
            }elseif(($zh+$zh2)==0 || $zh2==10){
                return "牛牛";
            }
            else{
                return "牛".$zh2;
            }
        }else
        {
            return "没牛";
        }

    }

    if($type==9){//梭哈
        $n1=$num[0];
        $n2=$num[1];
        $n3=$num[2];
        $n4=$num[3];
        $n5=$num[4];
        if($n1==$n2 && $n2==$n3 && $n3==$n4 && $n4==$n5){
            return "五条";
        }

        $array=$num;
        for($i=0;$i<5;$i++){
            $j=$i+1;
            for($ii=$j;$ii<5;$ii++){
                $jj=$ii+1;
                for($iii=$jj;$iii<5;$iii++){
                    $jjj=$iii+1;
                    for($iiii=$jjj;$iiii<5;$iiii++){
                        if($array[$i]==$array[$ii] && $array[$ii]==$array[$iii] && $array[$iii]==$array[$iiii]) return "四条";
                    }
                }
            }
        }

        for($i=0;$i<5;$i++){
            $j=$i+1;
            for($ii=$j;$ii<5;$ii++){
                $jj=$ii+1;
                for($iii=$jj;$iii<5;$iii++){
                    if($array[$i]==$array[$ii] && $array[$ii]==$array[$iii] )
                    {
                        foreach ($array as $key => $value) {
                            if($key==$i)unset($array[$key]);
                            if($key==$ii)unset($array[$key]);
                            if($key==$iii)unset($array[$key]);
                        }
                        $arr;
                        foreach ($array as $key => $value) {
                            $arr[]=$value;
                        }
                        if($arr[0]==$arr[1]) return "葫芦";
                    }
                }
            }
        }

        $n1=$num[0];$n1==10?0:$n1;
        $n2=$n1+1;$n2==10?0:$n2;
        $n3=$n2+1;$n3==10?0:$n3;
        $n4=$n3+1;$n4==10?0:$n4;
        $n5=$n4+1;$n5==10?0:$n5;
        if($n1==$num[0] && $n2==$num[1] && $n3==$num[2] && $n4==$num[3] && $n5==$num[4] )
        {
            return "顺子";
        }


        for($i=0;$i<5;$i++){
            $j=$i+1;
            for($ii=$j;$ii<5;$ii++){
                $jj=$ii+1;
                for($iii=$jj;$iii<5;$iii++){
                    if($array[$i]==$array[$ii] && $array[$ii]==$array[$iii] ) return "三条";
                }
            }
        }

        $array=$num;
        for($i=0;$i<5;$i++){
            $j=$i+1;
            for($ii=$j;$ii<5;$ii++){
                if($array[$i]==$array[$ii]){
                    foreach ($array as $key => $value) {
                        if($key==$i)unset($array[$key]);
                        if($key==$ii)unset($array[$key]);
                    }
                    $arr;
                    foreach ($array as $key => $value) {
                        $arr[]=$value;
                    }
                    //print_r($arr);break;
                    for($vi=0;$vi<3;$vi++){
                        $vj=$vi+1;
                        for($vii=$vj;$vii<3;$vii++){
                            if($arr[$vi]==$arr[$vii]) return "两对";
                        }
                    }
                }
            }
        }

        $array=$num;
        for($i=0;$i<5;$i++){
            $j=$i+1;
            for($ii=$j;$ii<5;$ii++){
                if($array[$i]==$array[$ii]){
                    return "一对";
                }
            }
        }

        return "散号";

    }//梭哈

}



/**
 * ************************************************************
 *
 *
 *
 * 使用特定function对数组中所有元素做处理
 *
 * @param
 *            string &$array 要处理的字符串
 *
 * @param string $function
 *            要执行的函数
 *
 * @return boolean $apply_to_keys_also 是否也应用到key上
 *
 * @access public
 *
 *
 *         ***********************************************************
 */
function arrayRecursive(&$array, $function, $apply_to_keys_also = false)

{
    static $recursive_counter = 0;

    if (++ $recursive_counter > 1000) {

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

    $recursive_counter --;
}

/**
 * ************************************************************
 *
 *
 *
 * 将数组转换为JSON字符串（兼容中文）
 *
 * @param array $array
 *            要转换的数组
 *
 * @return string 转换得到的json字符串
 *
 * @access public
 *
 *
 *
 *         ***********************************************************
 */
function JSON($array)
{
    arrayRecursive($array, 'urlencode', true);

    $json = json_encode($array);

    return urldecode($json);
}


//获得当前时间
function func_nowtime($type='',$date = "+12 hours"){
    if(empty($type)){
        return date("H:i:s",strtotime($date));
    }else{
        return date($type,strtotime($date));
    }
}


function checkDatetime($str, $format="Y-m-d H:i:s"){
    date_default_timezone_set('Asia/Shanghai');//'Asia/Shanghai'   亚洲/上海
    $unixTime=strtotime($str);
    $checkDate= date($format, $unixTime);
    return  $checkDate;
}


/*
 * 数字补0函数，当数字小于10的时候在前面自动补0
 */
function func_BuLing($num)
{
    if ($num < 10) {
        $num = '0' . $num;
    }
    return $num;
}

/*
 * 数字补0函数2，当数字小于10的时候在前面自动补00，当数字大于10小于100的时候在前面自动补0
 */
function func_BuLings($num)
{
  if(strlen($num) == 3){
    return $num;
  }
    if ($num < 10) {
        $num = '00' . $num;
    }
    if ($num > 9 && $num < 100) {
        $num = '0' . $num;
    }
    return $num;
}


function func_getdid(){
    return  date("YmdHis") . mt_rand("100000", "999999");
}


// 计算bj_pk10初始数据和bj_kl8的期数
function func_com_qishu($fc_type, $now='')
{

    if(date("m")>=4 && date("m")<11){
      $now = strtotime("+12 hours"); // 当前时间戳
    }else{
      $now = strtotime("+12 hours"); // 当前时间戳
    }

    if ($fc_type == 'bj_10') {
        // bj_pk10初始数据
        $old_qishu = 489193;
        $old_lizi = strtotime("2015-05-12 23:57:00"); // 固定不要动
        $left = 9 * 60 + 2; // 每天第一期开盘时间(分钟)
        $now_time = ceil(($now - $old_lizi - 3 * 60) / 60 % (60 * 24) + 0.1); // 已过去的当天分钟总数
    } elseif ($fc_type == 'bj_8') {
        // bj_kl8初始数据
        $old_qishu = 694622;
        $old_lizi = strtotime("2015-05-12 23:55:00"); // 固定不要动
        $left = 9 * 60; // 每天第一期开盘时间(分钟)
        $now_time = ceil(($now - $old_lizi - 5 * 60) / 60 % (60 * 24) + 0.1); // 已过去的当天分钟总数
    }

    $time = $now - $old_lizi; // 秒数
    $day = floor(($time / (60 * 60 * 24))-(7)); // 天数
    // 判断期数
    if ($time > 0) {
        if ($now_time >= $left) {
            $old_qishu += ($day * 179 + ceil(($now_time - $left) / 5));
            return $old_qishu;
        } else {
            $old_qishu += ($day * 179); // 当天第一期
            return $old_qishu;
        }
    } else {
        return false;
    }

}

// 计算福彩3D和排列3的期数
function func_fc_qishu()
{
    $yeah = func_nowtime('Y');
    $month = func_nowtime('m');
    $day = func_nowtime('d');
    // 由于过年放假7天 要减去7天
    $guonian_fangjia = 7;

    // 该年的过年日期(初一)
    if ($yeah == 2015) {
        $guonian = '02.28';
    } elseif ($yeah == 2016) {
        $guonian = '02.08';
    } elseif ($yeah == 2017) {
        $guonian = '01.28';
    } elseif ($yeah == 2018) {
        $guonian = '02.16';
    }

    if ($yeah == 2016) {
        $reyue = 29;
    } else {
        $reyue = 28;
    }

    if ($month == 1) {
        $qishu = $day;
    } elseif ($month == 2) {
        $qishu = 31 + $day;
    } elseif ($month == 3) {
        $qishu = 31 + $day + $reyue;
    } elseif ($month == 4) {
        $qishu = 31 + $day + $reyue + 31;
    } elseif ($month == 5) {
        $qishu = 31 + $day + $reyue + 31 + 30;
    } elseif ($month == 6) {
        $qishu = 31 + $day + $reyue + 31 + 30 + 31;
    } elseif ($month == 7) {
        $qishu = 31 + $day + $reyue + 31 + 30 + 31 + 30;
    } elseif ($month == 8) {
        $qishu = 31 + $day + $reyue + 31 + 30 + 31 + 30 + 31;
    } elseif ($month == 9) {
        $qishu = 31 + $day + $reyue + 31 + 30 + 31 + 30 + 31 + 31;
    } elseif ($month == 10) {
        $qishu = 31 + $day + $reyue + 31 + 30 + 31 + 30 + 31 + 31 + 30;
    } elseif ($month == 11) {
        $qishu = 31 + $day + $reyue + 31 + 30 + 31 + 30 + 31 + 31 + 30 + 31;
    } elseif ($month == 12) {
        $qishu = 31 + $day + $reyue + 31 + 30 + 31 + 30 + 31 + 31 + 30 + 31 + 30;
    }
    // 判断是否到年后了
    $guonian1 = explode('.', $guonian);
    if ($guonian1[0] < $month || ($guonian1[0] == $month & $guonian1[1] < $day)) {
        $qishu = $qishu - $guonian_fangjia;
    }

    return $qishu;
}

//重庆时时彩
function pl_odds_ssc5($arr){
	foreach ($arr as $k=>$v){
		//第一球到第五球
		switch ($v['fc_type']){
			case '第一球' : $array[0][0][] = $v;break;
			case '第二球' : $array[1][0][] = $v;break;
			case '第三球' : $array[2][0][] = $v;break;
			case '第四球' : $array[3][0][] = $v;break;
			case '第五球' : $array[4][0][] = $v;break;
		}

		//前三球、中三球、后三球、总和、龙虎
		switch ($v['fc_type']){
			case '前三球' : $array1[0][0][] = $v;break;
			case '中三球' : $array1[1][0][] = $v;break;
			case '后三球' : $array1[2][0][] = $v;break;
			case '總和,龍虎' :
				if(strlen($v['input_name'])>3){
					$array1[3][0][] = $v;
				}else{
					$array1[4][0][] = $v;
				}
				break;
		}

		//斗牛、梭哈
		switch ($v['fc_type']){
			case '斗牛' : $array2[0][0][] = $v;break;
			case '梭哈' : $array2[1][0][] = $v;break;
		}
	}
	$data[0] = $array;
	$data[1][0] = $array1[0];
	$data[1][1] = $array1[1];
	$data[1][2] = $array1[2];
	$data[1][3] = $array1[3];
	$data[1][4] = $array1[4];

	$row1 = fzpx($array2[0][0]);
	$row2 = fzpx($array2[1][0]);
	$data[2][0][0] = $row1[0];
	$data[2][1][0] = $row1[1];
	$data[2][2][0] = $row1[2];
	$data[2][3][0] = $row2[0];
	$data[2][4][0] = $row2[1];
	return $data;
}

//斗牛梭哈排序
function fzpx($arr){
	$num = count($arr)%5 == 0 ?  intval( count($arr)/5 ) : intval( count($arr)/5 )+1;
	for($i=0;$i<$num;$i++){
		$row[$i] = array_slice($arr,$i*5,5);
	}
	return $row;
}

//重庆时时彩显示页赔率排序(整合)
function pailie_odds_ssc_5($arr){
    $arr1 = array();
    foreach ($arr as $k => $v) {
        $arr1[] = $v['fc_type'];
    }
    $arr1 = array_flip(array_flip($arr1));
    $arr2 = array();
    $i = 0;
    foreach ($arr1 as $k1 => $v1) {
        foreach ($arr as $k2 => $v2) {
            if($v2['fc_type'] == $v1){
                $arr2[$i][] = $v2;
            }
        }
        $i++;
    }
    $arr3 = array();
    // p($arr2);
    //前台格式后面的玩法5个一组
    foreach ($arr2 as $k => $v) {
        $i = count($arr3);
        if($k >= 5){
            if(count($v) > 5){
                foreach ($v as $k1 => $v1) {
                    $arr3[$i][] = $v1;
                    if(($k1+1)%5 == 0){
                        $i++;
                    }
                }
            }else{
                $arr3[] = $v;
            }
        }else{
            $arr3[] = $v;
        }
    }

    return $arr3;
}


//重庆时时彩显示页赔率排序(整合)
function pailie_odds_5($arr,$arr1){
    $arr2 = array();
    $i = 0;
    foreach ($arr1 as $k1 => $v1) {
        foreach ($arr as $k2 => $v2) {
            if($v2['fc_type'] == $v1){
                $arr2[$i][] = $v2;
            }
        }
        $i++;
    }
    $arr3 = array();
    // p($arr2);
    //前台格式后面的玩法5个一组
    foreach ($arr2 as $k => $v) {
        $i = count($arr3);
        if($k >= 5){
            if(count($v) > 5){
                foreach ($v as $k1 => $v1) {
                    $arr3[$i][] = $v1;
                    if(($k1+1)%5 == 0){
                        $i++;
                    }
                }
            }else{
                $arr3[] = $v;
            }
        }else{
            $arr3[] = $v;
        }
    }

    return $arr3;
}

function pailie_odds_k3_5($arr,$arr1,$count=4){
    $arr2 = array();
    $i = 0;
    foreach ($arr1 as $k1 => $v1) {
        foreach ($arr as $k2 => $v2) {
            if($v2['fc_type'] == $v1){
                $arr2[$i][] = $v2;
            }
        }
        $i++;
    }
   //  p($arr2);
    $arr3 = array();
    // p($arr2);
    //前台格式后面的玩法5个一组
    foreach ($arr2 as $k => $v) {
        $i = count($arr3);

            if(count($v) > $count){
                foreach ($v as $k1 => $v1) {
                    $arr3[$i][] = $v1;
                    if(($k1+1)%$count == 0){
                        $i++;
                    }
                }
            }else{
                $arr3[] = $v;
            }
    }

    return $arr3;
}

function tamaodds($odds){
  if(!empty($odds)){
    $arr1 = $arr2 = array();
    foreach ($odds as $k=>$v) {
        if(is_numeric($v['input_name'])){
          $arr1[] = $v;

        }else{
          $arr2[] = $v;

        }
    }
     $odds1['suzi'] = $arr1;
     $odds1['hanzi'] = $arr2;
    return $odds1;
  }
}


function zhengma($odds){
  // p($odds);
    foreach ($odds as $k=>$v){
      if($v['odds_value'] != 0){
        $odds1[$v['input_name']][] = $v;
      }
    }
    return $odds1;
}

function lianma($odds){
  // p($odds);
  $arr = array();
  $str_odd = '';
  if(count($odds) > 1 ){
    foreach ($odds as $k => $v) {
      $str_odd .= '/'.$v['odds_value'];
    }
    $aa = trim($str_odd,'/');
    $arr = $odds[1];
  }else{
    $arr = $odds[0];
  }
  if(!empty($aa)){
    $arr['odds_value'] = $aa;
  }
  $data = array();
  for($i=1;$i<=49;$i++){
    $arr['input_name'] = $i;
    $data[$i] = $arr;

  }
  return $data;
}



function guoguan($odds){
    foreach ($odds as $k=>$v){
        $odds1[$v['type2']][] = $v;
    }
    return $odds1;
}
//特码样式
function func_set_style($str){

    $arr1=array(01,02,07,8,12,13,18,19,23,24,29,30,34,35,40,45,46);
    $arr2=array(03,04,9,10,14,15,20,25,26,31,36,37,41,42,47,48);
    $arr3=array(05,06,11,16,17,21,22,27,28,32,33,38,39,43,44,49);
    if($str){

        if(in_array($str,$arr1)){
            return "red";
        }else if(in_array($str,$arr2)){
            return "blue";
        }else if(in_array($str,$arr3)){
            return "green";
        }else if($str=="红波"){
            return "ball_reb";
        }else if($str=="蓝波"){
            return "ball_blue";
        }else if($str=="绿波"){
            return "ball_gree";
        }
    }
}

//重庆时时彩显示页赔率排序(2面)
function pailie_odds_ssc_4($arr){
    $arr1 = array();
    foreach ($arr as $k => $v) {
        $arr1[] = $v['fc_type'];
    }
    $arr1 = array_flip(array_flip($arr1));
    $arr2 = array();
    $i = 0;
    foreach ($arr1 as $k1 => $v1) {
        foreach ($arr as $k2 => $v2) {
            if($v2['fc_type'] == $v1){
                $arr2[$i][] = $v2;
            }
        }
        $i++;
    }
    $arr3 = array();
    // p($arr2);
    //前台格式后面的玩法4个一组
    foreach ($arr2 as $k => $v) {
        $i = count($arr3);
        if($k > 4){
            if(count($v) > 4){
                foreach ($v as $k1 => $v1) {
                    $arr3[$i][] = $v1;
                    if(($k1+1)%4 == 0){
                        $i++;
                    }
                }
            }else{
                $arr3[] = $v;
            }
        }else{
            $arr3[] = $v;
        }
    }

    return $arr3;
}


//幸运28显示页赔率排序
function pailie_odds_xy_28($arr){
    $arr1 = array();
    foreach ($arr as $k => $v) {
      $key = intval(($k)/7);
        $arr1[$key][] = $v;
    }

    return $arr1;
}


function num2char($num){
  $char = array('零','一','二','三','四','五','六','七','八','九','十','十一','十二');
  //$char = array('零','壹','贰','叁','肆','伍','陆','柒','捌','玖);
  $out = $char[$num];
  return $out;
}

//快乐十分显示页赔率排序(2面)
function pailie_odds_ten($arr){
    $arr1 = array();
    foreach ($arr as $k => $v) {
        $arr1[] = $v['fc_type'];
    }
    // p($arr);
    if(!empty($arr1)){
      $arr1 = array_flip(array_flip($arr1));
    }

    $arr2 = array();
    $i = 0;
    foreach ($arr1 as $k1 => $v1) {
        foreach ($arr as $k2 => $v2) {
            if($v2['fc_type'] == $v1){
                $arr2[$i][] = $v2;
            }
        }
        $i++;
    }
    return $arr2;
}

//快乐十分显示页赔率排序(2面总和)
function pailie_odds_ten_zonghe($arr){
    $arr2 = array();

    foreach ($arr as $k2 => $v2) {
        if($v2['fc_type'] == '總和,龍虎'){
            $arr2[] = $v2;
        }
    }
    $arr3 = array();
    $arr_1 = $arr_2 = $arr_3 = $arr_4 = array();
    foreach ($arr2 as $k1 => $v1) {

        $num = ceil(($k1+0.1)/2);
        switch ($num) {
          case 1:
            $arr_1[] = $v1;
            break;
          case 2:
            $arr_2[] = $v1;
            break;
          case 3:
            $arr_3[] = $v1;
            break;
          case 4:
            $arr_4[] = $v1;
            break;
      }

      $arr3[1]=$arr_1;
      $arr3[2]=$arr_2;
      $arr3[3]=$arr_3;
      $arr3[4]=$arr_4;
    }
    return $arr3;
}
 function selected_one($odds,$count=80){
    for($i=1;$i<=$count;$i++){
        foreach ($odds as $k=>$v){
        $lists[$i-1]['id'] = $odds['id'];//."_".$i;
        $lists[$i-1]['input_name'] = $odds['input_name'];
        $lists[$i-1]['odds_value'] = $odds['odds_value'];
        $lists[$i-1]['fc_type'] = $odds['fc_type'];
        $lists[$i-1]['type_id'] = $odds['type_id'];
        $lists[$i-1]['count_arr'] = $i;
        }
    }
    return $lists;
}

function putong_odds($arr){
    $arr1 = array();

    foreach ($arr as $k => $v) {
        $arr1[$v['type_id']] = $v['fc_type'];
    }
    $arr1 = array_flip(array_flip($arr1));
    $arr2 = array();
    $i = 0;
 // p($arr);
    foreach ($arr1 as $k1 => $v1) {
        foreach ($arr as $k2 => $v2) {
            if($v2['fc_type'] == $v1){
                $arr2[$k1][] = $v2;
            }
        }
        $i++;
    }
    $arr3 = array();
    //p($arr2);
    foreach ($arr2 as $k1 => $v1) {
        $arr_1 = $arr_2 = $arr_3 = $arr_0 = array();
       // p($v1);
        foreach ($v1 as $k2 => $v2) {
            $num = ($k2+4)%4;
            switch ($num) {
                case 1:
                    $arr_1[] = $v2;
                    break;
                case 2:
                    $arr_2[] = $v2;
                    break;
                case 3:
                    $arr_3[] = $v2;
                    break;
                case 0:
                    $arr_0[] = $v2;
                    break;
            }
        }
        // p($arr_3);exit;
        $arr3[$k1][0]=$arr_0;
        $arr3[$k1][1]=$arr_1;
        $arr3[$k1][2]=$arr_2;
        $arr3[$k1][3]=$arr_3;
    }
    // p($arr3);
    return $arr3;
}
//快乐十分显示页赔率排序(第几球)
function pailie_odds_ten_dijiqiu($arr){
    $arr1 = array();
    foreach ($arr as $k => $v) {
        $arr1[] = $v['fc_type'];
    }
    if(!empty($arr1)){
   $arr1 = array_flip(array_flip($arr1));
    }

    $arr2 = array();
    $i = 0;
    foreach ($arr1 as $k1 => $v1) {
        foreach ($arr as $k2 => $v2) {
            if($v2['fc_type'] == $v1){
                $arr2[$i][] = $v2;
            }
        }
        $i++;
    }

    $arr3 = array();
    foreach ($arr2 as $k1 => $v1) {
      $arr_1 = $arr_2 = $arr_3 = $arr_0 = array();
      foreach ($v1 as $k2 => $v2) {
        $num = ($k2+4)%4;
        switch ($num) {
          case 1:
            $arr_1[] = $v2;
            break;
          case 2:
            $arr_2[] = $v2;
            break;
          case 3:
            $arr_3[] = $v2;
            break;
          case 0:
            $arr_0[] = $v2;
            break;
        }
      }
      // p($arr_3);exit;
      $arr3[$k1][0]=$arr_0;
      $arr3[$k1][1]=$arr_1;
      $arr3[$k1][2]=$arr_2;
      $arr3[$k1][3]=$arr_3;
    }
    // p($arr3);
    return $arr3;
}

//北京PK赛车10
function pl_odds_ten($arr){
	foreach ($arr as $k=>$v){
		//双面盘  数组：$array
		if(!is_numeric($v['input_name'])){
			//if($v['input_name'] != '龙' && $v['input_name'] != '虎'){
				switch ($v['fc_type']){
					case '冠、亚军和' : $array[0][] = $v;break;
					case '冠军' : $array[1][1][1][] = $v;break;
					case '亚军' : $array[1][1][2][] = $v;break;
					case '第三名' : $array[1][1][3][] = $v;break;
					case '第四名' : $array[1][1][4][] = $v;break;
					case '第五名' : $array[1][1][5][] = $v;break;
					case '第六名' : $array[1][2][1][] = $v;break;
					case '第七名' : $array[1][2][2][] = $v;break;
					case '第八名' : $array[1][2][3][] = $v;break;
					case '第九名' : $array[1][2][4][] = $v;break;
					case '第十名' : $array[1][2][5][] = $v;break;
				}
			/*}elseif($v['fc_type'] != '龍虎'){
				switch ($v['fc_type']){
					case '冠军' : $v['fc_types']='1V10'; $array[1][0][1][] = $v;break;
					case '亚军' : $v['fc_types']='2V9'; $array[1][0][2][] = $v;break;
					case '第三名' : $v['fc_types']='3V8'; $array[1][0][3][] = $v;break;
					case '第四名' : $v['fc_types']='4V7'; $array[1][0][4][] = $v;break;
					case '第五名' : $v['fc_types']='5V6'; $array[1][0][5][] = $v;break;
				}
			}*/
		}

		//冠、亚军 组合 数组：$gyjh
		if($v['fc_type'] == "冠、亚军和"){
			if($k<17)$gyjh[$k] = $v;
			if($k==17){
				$gyjh[$k] = array();
				$gyjh[$k+1] = array();
				$gyjh[$k+2] = array();
			}
			if($k==17)$gyjh[$k+3] = $v;
			if($k==18)$gyjh[$k+3] = $v;
			if($k==19)$gyjh[$k+3] = $v;
			if($k==20)$gyjh[$k+3] = $v;
		}

		//1-5 | 6-10  数组：$result
		if($v['input_name'] != '龙' && $v['input_name'] != '虎'){
			switch ($v['fc_type']){
				case '冠军' : $result[0][0][] = $v;break;
				case '亚军' : $result[0][1][] = $v;break;
				case '第三名' : $result[0][2][] = $v;break;
				case '第四名' : $result[0][3][] = $v;break;
				case '第五名' : $result[0][4][] = $v;break;
				case '第六名' : $result[1][0][] = $v;break;
				case '第七名' : $result[1][1][] = $v;break;
				case '第八名' : $result[1][2][] = $v;break;
				case '第九名' : $result[1][3][] = $v;break;
				case '第十名' : $result[1][4][] = $v;break;
			}
		}
	}

	$data[0] = $array;
	$data[1][0] = pl_gyjh($gyjh);
	$data[2][0] = $result[0];
	$data[3][0] = $result[1];
	return $data;
}


function pl_odds_k3($arr){
	foreach ($arr as $k=>$v){
		switch ($v['fc_type']){
			case '和值' : $array[0][] = $v;break;
			case '两连' : $array[1][] = $v;break;
			case '独胆' : $array[2][1][] = $v;break;
			case '豹子' : $array[2][2][] = $v;break;
			case '对子' : $array[2][3][] = $v;break;
		}
	}
  $array[1][] = array();
  $array[2][1][] = array();
  $array[2][3][] = array();
	$data[0][0] = pl_gyjh($array[0]);
	$data[1][0] = pl_gyjh($array[1]);
	$data[2][0] = $array[2];
	return $data;
}

function pl_gyjh($arr){
	$arr_1 = $arr_2 = $arr_3 = $arr_4 = array();
    foreach ($arr as $k1 => $v1) {
        $num = ($k1+5)%4;
        switch ($num) {
          case 1:
            $arr_1[] = $v1;
            break;
          case 2:
            $arr_2[] = $v1;
            break;
          case 3:
            $arr_3[] = $v1;
            break;
          default:
            $arr_4[] = $v1;
            break;
      }
   }
   $data[0] = $arr_1;
   $data[1] = $arr_2;
   $data[2] = $arr_3;
   $data[3] = $arr_4;
   return $data;
}

function pdlh($v){
	if(is_numeric($v)){
		return 1;
	}else{
		return 0;
	}
}

//JS需要的JSON排列
function pailie_odds_json($arr){
    $arr1 = array();
    foreach ($arr as $k => $v) {
        $arr1['j'.$v['id']] = rtrim(',',$v['odds_value']);
    }
    return $arr1;
}



function func_toweek($w){
    switch($w){
        case "1":
            return "星期一";
            break;
        case "2":
            return "星期二";
            break;
        case "3":
            return "星期三";
            break;
        case "4":
            return "星期四";
            break;
        case "5":
            return "星期五";
            break;
        case "6":
            return "星期六";
            break;
        default:
            return "星期日";
            break;
    }
}

//北京赛车PK拾开奖函数
function Pk10_Auto($num , $type){
    $zh = $num[0]+$num[1];
    if($type==1){
        return $zh;
    }
    if($type==2){
        if($zh>11){
            return '大';
        }else{
            return '小';
        }
    }
    if($type==3){
        if($zh%2==0){
            return '双';
        }else{
            return '单';
        }
    }
    if($type==4){
        if($num[0]>$num[9]){
            return '龙';
        }else{
            return '虎';
        }
    }
    if($type==5){
        if($num[1]>$num[8]){
            return '龙';
        }else{
            return '虎';
        }
    }
    if($type==6){
        if($num[2]>$num[7]){
            return '龙';
        }else{
            return '虎';
        }
    }
    if($type==7){
        if($num[3]>$num[6]){
            return '龙';
        }else{
            return '虎';
        }
    }
    if($type==8){
        if($num[4]>$num[5]){
            return '龙';
        }else{
            return '虎';
        }
    }
}

//广东快乐十分开奖函数
function G10_Auto($num , $type){
    $zh = $num[0]+$num[1]+$num[2]+$num[3]+$num[4]+$num[5]+$num[6]+$num[7];
    if($type==1){
        return $zh;
    }
    if($type==2){
        if($zh>=85 && $zh<=132){
            return '大';
        }
        if($zh>=36 && $zh<=83){
            return '小';
        }
        if($zh==84){
            return '和';
        }
    }
    if($type==3){
        if($zh%2==0){
            return '双';
        }else{
            return '单';
        }
    }
    if($type==4){
        $zhws = substr($zh,strlen($zh)-1);
        if($zhws>=5){
            return '尾大';
        }else{
            return '尾小';
        }
    }
    if($type==5){
        if($num[0]>$num[7]){
            return '龙';
        }else{
            return '虎';
        }
    }
    if($type==6){
        if($num[1]>$num[6]){
            return '龙';
        }else{
            return '虎';
        }
    }
    if($type==7){
        if($num[2]>$num[5]){
            return '龙';
        }else{
            return '虎';
        }
    }
    if($type==8){
        if($num[3]>$num[4]){
            return '龙';
        }else{
            return '虎';
        }
    }
}



function FC3D_Auto($num , $type){
    $zh = $num[0]+$num[1]+$num[2];
    if($type==0){
        return $zh;
    }

    if($type==1 || $type==2 || $type==3){//第一~三球大小

        if($type==1)$qnum = $num[0];
        if($type==2)$qnum = $num[1];
        if($type==3)$qnum = $num[2];

        if($qnum>=5){
            return '大';
        }else{
            return '小';
        }
    }

    if($type==4 || $type==5 || $type==6){
        if($type==4)$qnum = $num[0];
        if($type==5)$qnum = $num[1];
        if($type==6)$qnum = $num[2];

        if($qnum%2==0){
            return '双';
        }else{
            return '单';
        }
    }

    if($type==7){//总和大小
        if($zh>=14){
            return '总和大';
        }else{
            return '总和小';
        }
    }

    if($type==8){//总和双单
        if($zh%2==0){
            return '总和双';
        }else{
            return '总和单';
        }
    }

    if($type==9){
        if($num[0]>$num[2]){
            return '龙';
        }
        if($num[0]<$num[2]){
            return '虎';
        }
        if($num[0]==$num[2]){
            return '和';
        }
    }

    if($type==10){
        $n1=$num[0];
        $n2=$num[1];
        $n3=$num[2];
        if(($n1==0 || $n2==0 || $n3==0) && ($n1==9 || $n2==9 || $n3==9)){
            if($n1==0){
                $n1=10;
            }
            if($n2==0){
                $n2=10;
            }
            if($n3==0){
                $n3=10;
            }
        }

        if($n1==$n2 && $n2==$n3){
            return "豹子";
        }elseif(($n1==$n2) || ($n1==$n3) || ($n2==$n3)){
            return "对子";
        }elseif(($n1==10 || $n2==10 || $n3==10) && ($n1==9 || $n2==9 || $n3==9) && ($n1==1 || $n2==1 || $n3==1)){
            return "顺子";
        }elseif( ( (abs($n1-$n2)==1) && (abs($n2-$n3)==1) ) || ((abs($n1-$n2)==2) && (abs($n1-$n3)==1) && (abs($n2-$n3)==1)) ||((abs($n1-$n2)==1) && (abs($n1-$n3)==1)) ){
            return "顺子";
        }elseif((abs($n1-$n2)==1) || (abs($n1-$n3)==1) || (abs($n2-$n3)==1)){
            return "半顺";
        }else{
            return "杂六";
        }
    }

    if($type==11){
        return max(abs($num[0]-$num[1]),abs($num[0]-$num[2]),abs($num[1]-$num[2]));
    }
}
function k3_Auto($num , $type){
    $zh = $num[0]+$num[1]+$num[2];
    if($type==0){
        return $zh;
    }

    if($type==7){//总和大小
        if($zh>=11){
            return '总和大';
        }else{
            return '总和小';
        }
    }

    if($type==8){//总和双单
        if($zh%2==0){
            return '总和双';
        }else{
            return '总和单';
        }
    }

}


function PL3_Auto($num , $type){
    $zh = $num[0]+$num[1]+$num[2];
    if($type==0){
        return $zh;
    }

    if($type==1 || $type==2 || $type==3){//第一~三球大小

        if($type==1)$qnum = $num[0];
        if($type==2)$qnum = $num[1];
        if($type==3)$qnum = $num[2];

        if($qnum>=5){
            return '大';
        }else{
            return '小';
        }
    }

    if($type==4 || $type==5 || $type==6){
        if($type==4)$qnum = $num[0];
        if($type==5)$qnum = $num[1];
        if($type==6)$qnum = $num[2];

        if($qnum%2==0){
            return '双';
        }else{
            return '单';
        }
    }

    if($type==7){//总和大小
        if($zh>=14){
            return '总和大';
        }else{
            return '总和小';
        }
    }

    if($type==8){//总和双单
        if($zh%2==0){
            return '总和双';
        }else{
            return '总和单';
        }
    }

    if($type==9){
        if($num[0]>$num[2]){
            return '龙';
        }
        if($num[0]<$num[2]){
            return '虎';
        }
        if($num[0]==$num[2]){
            return '和';
        }
    }

    if($type==10){
        $n1=$num[0];
        $n2=$num[1];
        $n3=$num[2];
        if(($n1==0 || $n2==0 || $n3==0) && ($n1==9 || $n2==9 || $n3==9)){
            if($n1==0){
                $n1=10;
            }
            if($n2==0){
                $n2=10;
            }
            if($n3==0){
                $n3=10;
            }
        }

        if($n1==$n2 && $n2==$n3){
            return "豹子";
        }elseif(($n1==$n2) || ($n1==$n3) || ($n2==$n3)){
            return "对子";
        }elseif(($n1==10 || $n2==10 || $n3==10) && ($n1==9 || $n2==9 || $n3==9) && ($n1==1 || $n2==1 || $n3==1)){
            return "顺子";
        }elseif( ( (abs($n1-$n2)==1) && (abs($n2-$n3)==1) ) || ((abs($n1-$n2)==2) && (abs($n1-$n3)==1) && (abs($n2-$n3)==1)) ||((abs($n1-$n2)==1) && (abs($n1-$n3)==1)) ){
            return "顺子";
        }elseif((abs($n1-$n2)==1) || (abs($n1-$n3)==1) || (abs($n2-$n3)==1)){
            return "半顺";
        }else{
            return "杂六";
        }
    }

    if($type==11){
        return max(abs($num[0]-$num[1]),abs($num[0]-$num[2]),abs($num[1]-$num[2]));
    }


}
//北京快乐8
function Kl8_Auto($num , $type){
    $zh = $num[0]+$num[1]+$num[2]+$num[3]+$num[4]+$num[5]+$num[6]+$num[7]+$num[8]+$num[9]+$num[10]+$num[11]+$num[12]+$num[13]+$num[14]+$num[15]+$num[16]+$num[17]+$num[18]+$num[19];
    if($type==0){
        return $zh;
    }

    if($type==1){//总和大小
        if($zh>810){
            return '总和大';
        }elseif($zh<810){
            return '总和小';
        }elseif($zh==810){
            return '总和810';
        }
    }

    if($type==2){//总和双单
        if($zh%2==0){
            return '总和双';
        }else{
            return '总和单';
        }
    }

    if($type==3){//上中下盘
        $compare =($num[0]>40?1:-1)+($num[1]>40?1:-1)+($num[2]>40?1:-1)+($num[3]>40?1:-1)+($num[4]>40?1:-1)+($num[5]>40?1:-1)+($num[6]>40?1:-1)+($num[7]>40?1:-1)+($num[8]>40?1:-1)+($num[9]>40?1:-1)+($num[10]>40?1:-1)+($num[11]>40?1:-1)+($num[12]>40?1:-1)+($num[13]>40?1:-1)+($num[14]>40?1:-1)+($num[15]>40?1:-1)+($num[16]>40?1:-1)+($num[17]>40?1:-1)+($num[18]>40?1:-1)+($num[19]>40?1:-1);

        if($compare>0){
            return '下盘';
        }elseif($compare<0){
            return '上盘';
        }elseif($compare==0){
            return '中盘';
        }
    }

    if($type==4){//奇偶和盘
        $compare =($num[0]%2==0?1:-1)+($num[1]%2==0?1:-1)+($num[2]%2==0?1:-1)+($num[3]%2==0?1:-1)+($num[4]%2==0?1:-1)+($num[5]%2==0?1:-1)+($num[6]%2==0?1:-1)+($num[7]%2==0?1:-1)+($num[8]%2==0?1:-1)+($num[9]%2==0?1:-1)+($num[10]%2==0?1:-1)+($num[11]%2==0?1:-1)+($num[12]%2==0?1:-1)+($num[13]%2==0?1:-1)+($num[14]%2==0?1:-1)+($num[15]%2==0?1:-1)+($num[16]%2==0?1:-1)+($num[17]%2==0?1:-1)+($num[18]%2==0?1:-1)+($num[19]%2==0?1:-1);

        if($compare>0){
            return '偶盘';
        }elseif($compare<0){
            return '奇盘';
        }elseif($compare==0){
            return '和盘';
        }
    }


}

function pailie_odds_5_j($arr){
  $arr1 = array();
  $arr1[0] = $arr[0];
  $arr1[1] = $arr[1];
  $arr1[2] = $arr[3];
  $arr1[3] = $arr[2];
  return $arr1;
}

function func_get_shuxiang(){
    $shuxiang=array("鼠","牛","虎","兔","龙","蛇","马","羊","猴","鸡","狗","猪");
    return $shuxiang;
}
//判断生肖 第二参数为当前年数(2015)
function func_shenxiao($num,$nianfen=2016){
    $arr = func_shenxiao_2014(49);
    $shuxiang=func_get_shuxiang();
    $count=count($shuxiang);
    $xu = intval($nianfen)-2014;//变化参数
    for($i=0;$i<$count;$i++){
        $xuhao =$i+$xu;
        if($xuhao  >= 12){
            $xuhao = $xuhao% 12 ;
        }
        if(in_array($num,$arr[$i])){
            return $shuxiang[$xuhao];
        }
    }
}

//特码单双
function func_tm_danshuang($str){

    if($str%2 == 0){
        echo "<font color='blue';>双</font>";
    }else{
        echo "<font color='#f00;';>单</font>";
    }
}

//特码大小
function func_tm_daxiao($str){

    if($str >= 25){
        echo "<font color='blue';>大</font>";
    }else{
        echo "<font color='#f00;';>小</font>";
    }
}

//合数单双
function func_heshu_danshuang($str){
    if(strlen($str) == 1){
        $str = "0".$str;
    }
    $num = substr($str,0,1);
    $num1 = substr($str,1,1);
    $data = intval($num) + intval($num1);
    if($data%2 == 0){
        echo "<font color='blue';>双</font>";
    }else{
        echo "<font color='#f00;';>单</font>";
    }
}

//六合彩总分大小
function func_zongfen_daxiao($str){
    $max = 49 * 6 / 2;
    if($str >= $max){
        echo "<font color='blue';>大</font>";
    }else{
        echo "<font color='#f00;';>小</font>";
    }
}
//特码色波
function func_tm_sebo($str){

    $arr1=array(01,02,07,8,12,13,18,19,23,24,29,30,34,35,40,45,46);
    $arr2=array(03,04,9,10,14,15,20,25,26,31,36,37,41,42,47,48);
    $arr3=array(05,06,11,16,17,21,22,27,28,32,33,38,39,43,44,49);
    if($str != 0){

        if(in_array($str,$arr1)){
            echo "<font color='#f00;';>红波</font>";
        }else if(in_array($str,$arr2)){
            echo "<font color='blue';>蓝波</font>";
        }else if(in_array($str,$arr3)){
            echo "<font color='green';>绿波</font>";
        }
    }

}

function func_shenxiao_2014($is_he=0){
    $shu = array('07',19,31,43);
    $niu = array('06',18,30,42);
    $hu = array('05',17,29,41);
    $tu = array('04',16,28,40);
    $long = array('03',15,27,39);
    $she = array('02',14,26,38);
    if($is_he==49){
        $ma = array('01',13,25,37,49);
    }elseif($is_he==0){
        $ma = array('01',13,25,37);
    }
    $yang = array(12,24,36,48);
    $hou = array(11,23,35,47);
    $ji = array(10,22,34,46);
    $gou = array('09',21,33,45);
    $zhu = array('08',20,32,44);
    $arr=array($shu,$niu,$hu,$tu,$long,$she,$ma,$yang,$hou,$ji,$gou,$zhu);
    return $arr;
}

//处理数组 添加元素 用于开奖结果
function func_get_arr_row_all($arr,$type){
    $data=array();
    foreach ($arr as $k => $v) {
        foreach ($v as $kk => $vv) {
            $ks = explode('ball_', $kk);
            if($ks[0] ==''){
                $v['hm'][] = $vv;
            }
        }
        $v['time'] = func_toweek(date("w",strtotime($v['kaijiang']?$v['kaijiang']:$v['datetime']))).' '.date("m/d",strtotime($v['kaijiang']?$v['kaijiang']:$v['datetime'])).' '. date("H:i",(strtotime($v['kaijiang']?$v['kaijiang']:$v['datetime'])));
        $data[]  = $v;

    }

    return $data;
}


function eng2chn($type){
  switch ($type) {
    case 'fc_3d':
      return '福彩3D';
      break;
    case 'pl_3':
      return '排列三';
      break;
    case 'liuhecai':
      return '六合彩';
      break;
    case 'bj_8':
      return '北京快乐8';
      break;
    case 'bj_10':
      return '北京赛车PK拾';
      break;
    case 'cq_ssc':
      return '重庆时时彩';
      break;
    case 'tj_ssc':
      return '天津时时彩';
      break;
    case 'xj_ssc':
      return '新疆时时彩';
      break;
    case 'cq_ten':
      return '重庆快乐十分';
      break;
    case 'gd_ten':
      return '广东快乐十分';
      break;
    case 'js_k3':
      return '江苏快3';
      break;
    case 'jl_k3':
      return '吉林快3';
      break;
    case 'xy_28':
      return 'PK幸运28';
      break;


  }
}
