<?php


$now_shengxiao = '羊';//2015年

function type_action($action){
  if($action == 'k_tm'){
      return '特码';
  }elseif($action == 'k_zm'){
     return '特码';
  }elseif($action == 'k_zt'){
     return '正特';
  }elseif($action == 'k_zm6'){
     return '正1-6';
  }elseif($action == 'k_gg'){
     return '过关';
  }elseif($action == 'k_lm'){
     return '连码';
  }elseif($action == 'k_bb'){
     return '半波';
  }elseif($action == 'k_sxp'){
     return '生肖';
  }elseif($action == 'k_sx'){
     return '生肖';
  }elseif($action == 'k_sx6'){
     return '生肖';
  }elseif($action == 'k_sxt2'){
     return '生肖连';
  }elseif($action == 'k_wsl'){
     return '尾数连';
  }elseif($action == 'k_wbz'){
     return '全不中';
  }
}

function randStr($len=12){
global $mysqli;
$chars='0123456789'; // 字符，以建立密码

mt_srand((double)microtime()*1000000*getmypid()); // 随机数发生器 (必须做)

$password='';

while(strlen($password)<$len)

$password.=substr($chars,(mt_rand()%strlen($chars)),1);

return $password;

}



function ka_config($i){
}



   //赔率

function ka_bl($i,$b){
global $mysqli;
   $result=$mysqli->query("Select * From c_odds_7 where id='".$i."' Order By id Desc");
$ka_config5=$result->fetch_array();
return $ka_config5[$b];
}








function ka_memds($i,$b){


}


function ka_guanuser($i){
global $mysqli;
   $result=$mysqli->query("select * from ka_guan where  kauser='".$_SESSION['kauser']."' order by id desc");

$ka_guanuser1=$result->fetch_array();

return $ka_guanuser1[$i];

   }

 function ka_guansds($i,$b){
global $mysqli;
   $guanss=ka_guanuser("guan");

$result=$mysqli->query("Select ds,yg,xx,xxx,ygb,ygc,ygd from ka_quota where username='".$guanss."' order by id");

$drop_guansds = array();

$y=0;

while($image = $result->fetch_array()){

$y++;

array_push($drop_guansds,$image);

}

return $drop_guansds[$i][$b];

}



function ka_zongds($i,$b){
global $mysqli;
   $guanss=ka_guanuser("zong");

$result=$mysqli->query("Select ds,yg,xx,xxx,ygb,ygc,ygd from ka_quota where username='".$guanss."' order by id");

$drop_zongds = array();

$y=0;

while($image = $result->fetch_array()){

$y++;

array_push($drop_zongds,$image);

}

return $drop_zongds[$i][$b];

}





function ka_memuser($i){

   }


//返回用户单一个字段值
// username 用户名称
//字段名
function Get_user_One($username,$field){
    global $mysqlt;
    $sql    =   "select ".$field." from k_user where username='".$username."' and site_id='".SITEID."' limit 1";
    $query  =   $mysqlt->query($sql);
    $user   =   $query->fetch_array();
    return $user[$field];
}




 ///开奖生肖



function Get_sx_Color($rrr){
global $mysqli;
$result=$mysqli->query("Select id,m_number,sx From ka_sxnumber where  m_number LIKE '%$rrr%'  and id<=12  Order By id LIMIT 1");

$ka_Color1=$result->fetch_array();

return $ka_Color1['sx'];

}



 ///波色



function Get_bs_Color($i){
global $mysqli;
   $result=$mysqli->query("Select id,color From ka_color where id=".$i." Order By id");

$ka_configg=$result->fetch_array();



return $ka_configg['color'];

   }



function ka_Color_s($i){
  global $mysqli;
  $result=$mysqli->query("Select id,color From ka_color where id=".$i." Order By id");

  $ka_configg=@$result->fetch_array();

    if ($ka_configg['color']=="r"){$bscolor="红波";}

    if ($ka_configg['color']=="b"){$bscolor="蓝波";}

    if ($ka_configg['color']=="g"){$bscolor="绿波";}

  return $bscolor;

}

// 当前期数，$type_y 表示 类别 列：'六合彩'
function dq_qishu($type_y, $db_config)
{
    if ($type_y == 0)
        $type_y = 7;
    $now_time = date("H:i:s", strtotime("+12 hours"));
    $dangqian = M('c_opentime_' . $type_y, $db_config);
    $data_time = $dangqian->field("*")
        ->where("ok ='0' and fengpan > '" . $now_time . "'")
        ->order("kaijiang ASC")
        ->find();

    $date_Y = date("Y", strtotime("+12 hours"));
    $date_y = date("y", strtotime("+12 hours"));
    $date_ymd = date("ymd", strtotime("+12 hours"));
    $date_Ymd = date("Ymd", strtotime("+12 hours"));
    // 判断是否是当天的最后一期,如果是显示明天第一期
    if (empty($data_time['qishu'])) {
        $data_time['qishu'] = 1;
        $date_Y = date("Y", strtotime("+24 hours"));
        $date_y = date("y", strtotime("+24 hours"));
        $date_ymd = date("ymd", strtotime("+24 hours"));
        $date_Ymd = date("Ymd", strtotime("+24 hours"));
    }

    if ($type_y == 7) {
        // 六合彩单独查期数
        $now_time = date("Y-m-d H:i:s", strtotime("+12 hours"));
        $data_time = $dangqian->field("*")
            ->where("ok ='0' and fengpan > '" . $now_time . "'")
            ->order("kaijiang ASC")
            ->find();
        return BuLings($data_time['qishu']);
    } elseif ($type_y == 5) {
        // 福彩3D每天一期
        $data_time['qishu'] = BuLings(fc_qishu());
        return $date_Y . BuLings($data_time['qishu']);
    } elseif ($type_y == 6) {
        // 排列3每天一期
        $data_time['qishu'] = BuLings(fc_qishu());
        return $date_y . BuLings($data_time['qishu']);
    } elseif ($type_y == 8) {
        // 北京快乐8
        return com_qishu('bj_kl8');
    } elseif ($type_y == 3) {
        // 北京PK10
        return com_qishu('bj_pk10');
    } elseif ($type_y == 4) {
        // 重庆快乐10分
        return $date_ymd . BuLings($data_time['qishu']);
    } elseif ($type_y == 1) {
        // 广东快乐十分
        return $date_Ymd . BuLing($data_time['qishu']);
    } else {
        return $date_Ymd . BuLings($data_time['qishu']);
    }
}

/*
 * 数字补0函数，当数字小于10的时候在前面自动补0
 */
function BuLing($num)
{
    if ($num < 10) {
        $num = '0' . $num;
    }
    return $num;
}

/*
 * 数字补0函数2，当数字小于10的时候在前面自动补00，当数字大于10小于100的时候在前面自动补0
 */
function BuLings($num)
{
    if ($num < 10) {
        $num = '00' . $num;
    }
    if ($num > 9 && $num < 100) {
        $num = '0' . $num;
    }
    return $num;
}

// 查看当前是否是封盘时间
function get_fengpan($type_id, $db_config)
{
    $now_time = date("H:i:s", strtotime("+12 hours"));
    $dangqian = M('c_opentime_' . $type_id, $db_config);
    $data_time = $dangqian->field("*")
        ->where("ok ='0' and kaijiang >= '" . $now_time . "' and fengpan <= '" . $now_time . "'")
        ->order("kaijiang ASC")
        ->find();
    // p($data_time);exit;
    if ($data_time['id'] != "") {
        return true;
    } else {
        return false;
    }
}

function set_arraypan($type_id, $db_config, $adddate = '+12 hours')
{
    $now_time = date("H:i:s", strtotime("+12 hours"));
    switch ($type_id) {
        case 5:
            $where = "ok ='0'";
            break;
        case 6:
            $where = "ok ='0'";
            break;
        case 7:
            $now_time = date("Y-m-d H:i:s", strtotime("+12 hours"));
             $where = "ok ='0' and fengpan > '" . $now_time . "'";
            break;
        default:
            $where = "ok ='0' and fengpan > '" . $now_time . "'";
            break;
    }
    // 查询是否开盘
    $data_time = M('c_opentime_' . $type_id, $db_config)->field("*")
        ->where($where)
        ->order("kaijiang ASC")
        ->find();

    if(empty($data_time)){
        $data_time = M('c_opentime_' . $type_id, $db_config)->field("*")
        ->order("kaipan ASC")
        ->find();
    }

    if ($type_id != 7) {
        $f_t = date("Y-m-d", strtotime("+12 hours")) . ' ' . $data_time['fengpan'];
        $o_t = date("Y-m-d", strtotime($adddate)) . ' ' . $data_time['kaipan'];

        $array['f_t_stro'] = strtotime($f_t) - strtotime($now_time); // 距离封盘的时间
        $array['o_t_stro'] = strtotime($o_t) - strtotime($now_time); // 距离开盘的时间
        $array['f_state'] = date("Y-m-d", strtotime("+12 hours")) . ' ' . $data_time['fengpan']; // 封盘状态判断时间
        $array['o_state'] = date("Y-m-d", strtotime("+12 hours")) . ' ' . $data_time['kaipan']; // 开盘状态判断时间
        $array['c_time'] = date('Y-m-d', strtotime("+12 hours")) . ' ' . $now_time;

    } else {
        $now_time_day = date("d", strtotime("+12 hours"));
        $f_t = $data_time['fengpan'];
        $o_t = $data_time['kaipan'];
        $f_t_day = explode('-', $o_t);
        $f_t_day = $f_t_day[2];
        $left_hours = ($f_t_day - $now_time_day) * 24 * 60 * 60; // 距离下次开盘的天数换成秒

        $array['f_t_stro'] = strtotime($f_t) - strtotime($now_time); // 距离封盘的时间
        $array['o_t_stro'] = (strtotime($o_t) - strtotime($now_time)) + $left_hours; // 距离开盘的时间

        $array['f_state'] = $data_time['fengpan']; // 封盘状态判断时间
        $array['o_state'] = $data_time['kaipan']; // 开盘状态判断时间
    }
    return $array;
}

// 计算bj_pk10初始数据和bj_kl8的期数
function com_qishu($fc_type, $now)
{
    $now = strtotime("+12 hours"); // 当前时间戳
    if ($fc_type == 'bj_pk10') {
        // bj_pk10初始数据
        $old_qishu = 489193;
        $old_lizi = strtotime("2015-05-12 23:57:00"); // 固定不要动
        $left = 9 * 60 + 2; // 每天第一期开盘时间(分钟)
        $now_time = ceil(($now - $old_lizi - 3 * 60) / 60 % (60 * 24) + 0.1); // 已过去的当天分钟总数
    } elseif ($fc_type == 'bj_kl8') {
        // bj_kl8初始数据
        $old_qishu = 694622;
        $old_lizi = strtotime("2015-05-12 23:55:00"); // 固定不要动
        $left = 9 * 60; // 每天第一期开盘时间(分钟)
        $now_time = ceil(($now - $old_lizi - 5 * 60) / 60 % (60 * 24) + 0.1); // 已过去的当天分钟总数
    }

    $time = $now - $old_lizi; // 秒数
    $day = floor($time / (60 * 60 * 24)); // 天数

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
function fc_qishu()
{
    $yeah = date("Y", strtotime("+12 hours"));
    $month = date("m", strtotime("+12 hours"));
    $day = date("d", strtotime("+12 hours"));
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

function isPrime($n)
{
    if($n == 0){
         echo "<font color='red';>合</font>";
         return false;
    }
    if ($n <= 3) {
        echo "<font color='blue';>质</font>";
        return false;
    } else
        if ($n % 2 === 0 || $n % 3 === 0) {
            echo "<font color='red';>合</font>";
            return false;
        } else {
            for ($i = 5; $i * $i <= $n; $i += 6) {
                if ($n % $i === 0 || $n % ($i + 2) === 0) {
                    echo "<font color='red';>合</font>";
                }
            }
            echo "<font color='blue';>质</font>";
        }
}

// 查询当期玩法已下注的金额
function beted_limit($qishu_type, $title_3d, $db_config)
{
    $db_config['dbname'] = 'cyj_public';
    $qishu = dq_qishu($qishu_type, $db_config);

    $db_config['dbname'] = 'cyj_private';
    $data = M('c_bet', $db_config)->field("sum(money)")
        ->where("qishu = '" . $qishu . "' and mingxi_1 = '" . $title_3d . "' and username = '".$_SESSION['username']."'")
        ->find();
    $data["sum(money)"] = $data["sum(money)"] != "" ? $data["sum(money)"] : 0;
    return $data;
}



// 转换main_r里的参数
function type_y($type_y)
{
    switch ($type_y) {
        case '1':
            return 5;
            break;
        case '2':
            return 6;
            break;
        case '3':
            return 2;
            break;
        case '4':
            return 8;
            break;
        case '5':
            return 3;
            break;
        case '6':
            return 7;
            break;
        case '7':
            return 1;
            break;
        case '8':
            return 4;
            break;
        case '9':
            return 9;
            break;
        case '10':
            return 10;
            break;
        case '11':
            return 11;
            break;
        case '12':
            return 12;
            break;
        case '13':
            return 13;
            break;
        case '14':
            return 14;
            break;
    }
}
/**
 * 下注的方法
 * @param array $datas 所有post参数
 * @param array $names 获取清空后的POST键名
 * @param string $qishu 当前期数
 * @param string $gtype 彩票名称
 * @param int $type_y
 * @param class $mysqlt 数据库对象
 * @param array $ball_name_s
 * @param array $ball_name
 * @param array $ball_name_zh
 * @param array $ball_name_h
 * @param array $ball_name_f
 * @param array $ball_name_g
 * @param array $qiu   北京快乐8专用
 * @param array $names_s  北京快乐8专用
 * **/
function addlottery_bet($datas, $names, $qishu, $gtype, $type_y, $db_config,$ball_name_s, $ball_name, $ball_name_zh,$ball_name_h,$ball_name_f,$ball_name_g,$qiu,$names_s)
{
    $uid = $_SESSION["uid"];
    $username = $_SESSION["username"];
    $agent_id = $_SESSION["agent_id"];
    if(empty($uid) || empty($username) || empty($agent_id)){
        echo '<script type="text/javascript">alert("您已离线！请重新登录！");top.location.href="/"</script>';
        exit();
    }
    $betmodel = M('c_bet', $db_config);
    $betmodel->begin(); // 事务开始
    try {
        $arr;
        $balance_money; // k_user_cash_record表的balance;
        $all_money; // k_user_cash_record表的总金额cash_num;
        for ($i = 0; $i < count($datas) - 1; $i ++) {
            // 分割键名，取ball_后的数字，来判断属于第几球
            $qiu = explode("_", $names[$i]);
            if ($qiu[0] != 'ball') {
                continue;
            }
            $money = $datas['' . $names[$i] . ''];
            if($money=='on'){
                //continue;
            }
            $qiuhao = $ball_name['qiu_' . $qiu[1]];
            // 随机生成订单号
            $did = date("YmdHis") . mt_rand("100000", "999999");
            if($_GET['type']==3){
                //龙虎转换玩法字段
                if( $_POST['longhu'] == 'longhu' ){
                    $qiuhao = '龍虎';
                    $qiuhaoall = array('1'=>'1V10 龍虎','2'=>'2V9 龍虎','3'=>'3V8 龍虎','4'=>'4V7 龍虎','5'=>'5V6 龍虎');
                    foreach ($qiuhaoall as $k =>$s){
                        $qiuhao3 = $qiu[1]==$k?$s:'';
                        if($qiuhao3){
                            break;
                        }
                    }
                }
            }

            if($_GET['type']==8){
                if($qiu[1] == 1){//选一
                    $odds   = lottery_odds($_GET['type'],'ball_'.$qiu[1],1);
                }elseif($_GET['type']==8 && ($qiu[1]>1 && $qiu[1]<6)){
                    //获取赔率
                    $odds   = lottery_odds($_GET['type'],'ball_'.$qiu[1],$qiu[2]);
                    $odds4 = lottery_odds($_GET['type'],'ball_'.$qiu[1],2,1);
                    $odds3 = lottery_odds($_GET['type'],'ball_'.$qiu[1],3,1);


                    switch ($qiu[1]) {
                        case 5:
                            $qiuhao3 = "五中五:".$odds.",五中四:".$odds4.",五中三:".$odds3;
                            break;
                        case 4:
                            $qiuhao3 = "四中四:".$odds.",四中三:".$odds4.",四中二:".$odds3;
                            break;
                        case 3:
                            $qiuhao3 = "三中三:".$odds.",三中二:".$odds4;
                            break;
                        case 2:
                            $qiuhao3 = "二中二:".$odds;
                            break;
                        case 1:
                            $qiuhao3 = "一中一:".$odds;
                            break;
                        default:
                            ;
                            break;
                    }
                }else{
                    // 获取赔率
                    $odds = lottery_odds($_GET['type'], 'ball_' . $qiu[1], $qiu[2]);
                }
            }else{
                // 获取赔率
                $odds = lottery_odds($_GET['type'], 'ball_' . $qiu[1], $qiu[2]);
            }

            //玩法获取
            $wanfa = getwanfa($_GET['type'],$names[$i], $qiu, $ball_name_s, $ball_name, $ball_name_zh, $ball_name_h, $ball_name_f, $ball_name_g);

            $u_money = Get_user_money($username);
            $where = "username='".$username."' and site_id='".SITEID."'" ;
            $edu = $betmodel->setTable('k_user')->field('money')->where($where)->find();
            $dataU = array();
            $dataU['money'] = array('-',$money);
            $log_1 = $betmodel ->setTable('k_user')
                    ->where("uid = '".$uid."'")
                    ->update($dataU);//更新会员余额
            $balance_money = M('k_user',$db_config)
                           ->where("uid = '".$uid."'")
                           ->getField("money");
            // 写投注表
            $datereg = date("Y-m-d H:i:s", time());
            $all_money += $money; // 交易总金额
           // $balance_money = $u_money - $money; // 用户余额
            $odds_amount = $money * $odds ;

            $c_betdata['did'] = $did;
            $c_betdata['uid'] = $uid;
            $c_betdata['agent_id'] = $agent_id;
            $c_betdata['username'] = $username;
            $c_betdata['addtime'] = $datereg;
            $c_betdata['type'] =$gtype;
            $c_betdata['qishu']=$qishu;
            $c_betdata['mingxi_1'] = $qiuhao;
            $c_betdata['mingxi_2'] = $wanfa;
            $c_betdata['mingxi_3'] = $qiuhao3;
            $c_betdata['odds'] = $odds;
            $c_betdata['money'] = $money;
            $c_betdata['win'] = $odds_amount;
            $c_betdata['assets'] = $u_money;//下注之前的余额
            $c_betdata['balance'] = $balance_money;
            $c_betdata['fs'] = 0;
            $c_betdata['site_id'] = SITEID;

            $source_id =$betmodel->setTable('c_bet')->add($c_betdata);
            $arr[$i] = $source_id;

            // 写现金流表 cash_type=3：彩票下注 cash_do_type=2：取出
            $remark = "彩票注单：" . $did . " , 類型:" . $gtype;
            $record = array();
            $record['source_id'] = $source_id;
            $record['source_type'] = 7;//彩票下注类型
            $record['site_id'] = SITEID;
            $record['uid'] = $uid;
            $record['agent_id'] = $agent_id;
            $record['username'] = $username;
            $record['cash_type'] = 3;
            $record['cash_do_type'] = 2;
            $record['cash_num'] =$money;
            $record['cash_balance'] = $balance_money;
            $record['cash_date'] = $datereg;
            $record['remark'] =$remark;

            $q_1 =$betmodel->setTable('k_user_cash_record')->add($record);
            $arr_1[$i] = $q_1;
            $wanfaget.=$wanfa."_";
            $oddsget.=$odds."_";
            $moneyget.=$money."_";
        }

        $j = 0;
        $ok = 0;

        for ($j; $j < count($arr) - 1; $j ++) {

            if ($arr[$j + 1] >0 && $arr_1[$j + 1] >0 && $balance_money!=-1) {
                $ok += 1;
            } else {
                $betmodel->rollback(); // 数据回滚
                echo '<script type="text/javascript">alert("由于网络堵塞，本次下注失败。\\n请您稍候再试，或联系在线客服。！");</script>';
                exit();
            }
        }

        if ($ok == count($arr) - 1) {
            $betmodel->commit(); // 事务提交
        }
        $wanfaget = substr($wanfaget,0,strlen($wanfaget)-1);
        $oddsget = substr($oddsget,0,strlen($oddsget)-1);
        echo '<script type="text/javascript">window.location.href="Order.php?action_h=ok&type_h='.$_GET['type'].'&wanfa_h=' . $qiuhao . '&money_h=' . $all_money . '&money_y=' . $money . '&bet_h=' . $odds . '&type_y=' . $type_y . '&wanfa='.$wanfaget.'&odds='.$oddsget.'&money='.$moneyget.'"</script>';
    } catch (Exception $e) {
        $betmodel->rollback(); // 数据回滚
        echo '<script type="text/javascript">tip("由于网络堵塞，本次下注失败。\\n请您稍候再试，或联系在线客服。！");</script>';
        exit();
    }
}

/**
 * 获得玩法
 * @param int $type 彩票类别
 * @param array $names_s 配置文件的对应玩法的数组名
 * @param array $qiu 获取用户选择下注的 input id号
 * @param  $ball_name, $ball_name_zh,$ball_name_h,$ball_name_f,$ball_name_g  配置文件的对应玩法的数组
 * @return string 返回玩法
 *
 * */

function getwanfa($type,$names,$qiu,$ball_name_s, $ball_name, $ball_name_zh,$ball_name_h,$ball_name_f,$ball_name_g){
    if ($type==1 ||$type==4) {  //广东快乐和重庆快乐
        if( $qiu[1] == 9 ){
            $wanfa  = $ball_name_zh['ball_'.$qiu[2].''];
        }else{
            $wanfa  = $ball_name['ball_'.$qiu[2].''];
        }
    }else if ($type==3) {  //北京pk10
        if( $qiu[1] == 11 ){
            $wanfa  = $ball_name_h['ball_'.$qiu[2].''];
        }else{
            $wanfa  = $ball_name['ball_'.$qiu[2].''];
        }
    }elseif($type==5||$type==6){ //福彩3D和排列3

        if( $qiu[1] == 4 ){
            $wanfa  = $ball_name_zh['ball_'.$qiu[2].''];
        }else if( $qiu[1] == 5 ){
            $wanfa  = $ball_name_s['ball_'.$qiu[2].''];
        }else{
            $wanfa  = $ball_name['ball_'.$qiu[2].''];
        }
    }else if ( $type==13 || $type==14){  //快3
        if( $qiu[1] == 2 ){
            $wanfa  = $ball_name_s['ball_'.$qiu[2].''];
        }else if( $qiu[1] == 3 ){
            $wanfa  = $ball_name_f['ball_'.$qiu[2].''];
        }else if( $qiu[1] == 4 ){
            $wanfa  = $ball_name_h['ball_'.$qiu[2].''];
        }else if( $qiu[1] == 5 ){
            $wanfa  = $ball_name_g['ball_'.$qiu[2].''];
        }else{
            $wanfa  = $ball_name['ball_'.$qiu[2].''];
        }

    }else if($type==8){
        if( $qiu[1] == 2 ||$qiu[1] == 3 ||$qiu[1] == 4||$qiu[1] == 5 ){
               $name= explode('_', $names);
                for($i=2;$i<count($name);$i++){
                    $wanfa.=$ball_name['ball_'.$name[$i]].",";
                }


            $wanfa = substr($wanfa,0,strlen($wanfa)-1);
        }elseif( $qiu[1] == 6 ){
            $wanfa  = $ball_name_zh['ball_'.$qiu[2].''];
        }else if( $qiu[1] == 7 ){
            $wanfa  = $ball_name_s['ball_'.$qiu[2].''];
        }else if( $qiu[1] == 8 ){
            $wanfa  = $ball_name_f['ball_'.$qiu[2].''];
        }else{
            $wanfa  = $ball_name['ball_'.$qiu[2].''];
        }
    }else{                    //时时彩

        if( $qiu[1] == 6 ){
            $wanfa  = $ball_name_zh['ball_'.$qiu[2].''];
        }else if( $qiu[1] == 7 ||$qiu[1] == 8 || $qiu[1] == 9 ){
            $wanfa  = $ball_name_s['ball_'.$qiu[2].''];
        }else if( $qiu[1] == 10){
            $wanfa  = $ball_name_s['nball_'.$qiu[2].''];
        }else if( $qiu[1] == 11){
            $wanfa  = $ball_name_s['shball_'.$qiu[2].''];
        }else{
            $wanfa  = $ball_name['ball_'.$qiu[2].''];
        }
    }
    return $wanfa;
}








