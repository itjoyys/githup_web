<?php
ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(1);                    //打印出所有的 错误信息
session_start();
include(dirname(__FILE__).'../include/filter.php');
include(dirname(__FILE__).'../include/login_check.php');
include_once("../include/public_config.php");
include_once("../include/private_config.php");
$gtype=$_REQUEST['gtype'];
$uid=$_SESSION['uid'];
$date_s__=$date_s_=$_REQUEST['date_s'];
$date_e__=$date_e_=$_REQUEST['date_e'];
$daynum_s=strtotime($date_s__);
$daynum_e=strtotime($date_e__);
$daynum=floor(abs($daynum_e-$daynum_s)/86400)+1;
$daynum>8 ?$daynum=8:"";
if($date_e_<$date_s_){
    $date_s_=$date_e_;
    $date_e_=$date_s__;

}


$where='';
if($gtype=='ALL'){

}
elseif($gtype=='FT'){//足球//
    $where=" and ball_sort like('足球%')";//
}
elseif($gtype=='BK'){//足球
    $where=" and ball_sort like('篮球%')";//
}
elseif($gtype=='TN'){//足球
    $where=" and ball_sort like('网球%')";//
}
elseif($gtype=='VB'){//足球
    $where=" and ball_sort like('排球%')";//
}
elseif($gtype=='BS'){//足球
    $where=" and ball_sort like('棒球%')";//
}
/*elseif($gtype=='CG'){//足球
    $where=" and ball_sort like('串关%')";
}*/
elseif($gtype=='OP'){//足球

}else{

}
$weekarray=array("日","一","二","三","四","五","六");
for($i=0;$i<$daynum;$i++){
    $time=strtotime(date("$date_s_"));
    $date_s=date("Y-m-d 00:00:00",$time+$i*86400);
    $date_e=date("Y-m-d 23:59:59",$time+$i*86400);
    $time=strtotime($date_s);
    $date=date('Y-m-d',$time);
    $week=$weekarray[date('w',$time)];
    //echo "$date 星期".$week;
    $sql="select sum(`bet_money`) from `k_bet` where status >0 and uid=$uid and site_id='".SITEID."'  and match_coverdate between '$date_s' and '$date_e' $where";
    $query	=	$mysqlt->query($sql);
    $rows	=	$query->fetch_row();
     $AllMoney=$rows[0];

    $sql="select sum(`bet_money`) from `k_bet` where status in (1,2,3,4) and  uid=$uid and site_id='".SITEID."' and match_coverdate between  '$date_s' and '$date_e' $where";
    $query	=	$mysqlt->query($sql);
    $rows	=	$query->fetch_row();
     $EffecTiveMoney=$rows[0];

    $sql="select sum(`win`) from `k_bet` where win>0 and  uid=$uid and site_id='".SITEID."' and match_coverdate between  '$date_s' and '$date_e' $where";
    $query	=	$mysqlt->query($sql);
    $rows	=	$query->fetch_row();
     $WinMoney=$rows[0];
    $d[$i]['date']=$date;
    $d[$i]['week']=$week;
    $d[$i]['AllMoney']=number_format($AllMoney, 2, '.', '');
    $d[$i]['EffecTiveMoney']=number_format($EffecTiveMoney, 2, '.', '');
    $d[$i]['WinMoney']=number_format($WinMoney, 2, '.', '');
}
exit(json_encode($d));



