<?php
//获取赔率
function lottery_odds($type,$ball,$h,$if=0){
	global $mysqli;$db_config;
	$sql	=	"select * from c_odds_".$type." where type='".$ball."' limit 1";

	$query	=	$mysqli->query($sql);
	$t		=	$query->fetch_array();
	if($type==8){
		$ball1 = explode("_",$ball);  
		if($if==1){
		    return $t["h".$h.""];
		}else{
		if($ball1[1]>1 && $ball1[1]<6){
			return $t["h1"];
		}else{
		    return $t["h".$h.""];
		}
		}
	}
	return $t["h".$h.""];
}
//获取彩票期数
function lottery_qishu($type){
	
	global $mysqli;$db_config;
	$lottery_time = time()+strtotime("+12 hours");
	$sql	=	"select qishu from c_opentime_".$type." where kaipan<='".date("H:i:s",$lottery_time)."' and fengpan>='".date("H:i:s",$lottery_time)."' limit 1";
	$query	=	$mysqli->query($sql);
	$qs		=	$query->fetch_array();
	if($qs){
		if($type==1 || $type==4){
			return date("Ymd",$lottery_time).BuLing($qs['qishu']);
		}
		if($type==2){
			return date("Ymd",$lottery_time).BuLings($qs['qishu']);
		}
		if($type==3){
			$l_date=date("Y-m-d",$lottery_time);
			$pk10_date = '2014-03-31';
			$pk10_qi = 417593;
			$pk10_t = (strtotime($l_date)-strtotime($pk10_date))/86400;
			return ($pk10_t-1)*179+$qs['qishu']+$pk10_qi;
		}
		if($type==5){//3D
			$l_date=date("Y-m-d",$lottery_time);
			$pk10_date = '2014-03-19';
			$pk10_qi = 70;
			$holidays =array("4月5日","4月6日","4月7日","5月1日","5月2日","5月3日","5月31日","6月1日","6月2日","9月6日","9月7日","9月8日");
			$pk10_t = (strtotime($l_date)-strtotime($pk10_date))/86400;
			$pk10_t = $pk10_t+$pk10_qi;
			$pk10_t = $pk10_t > 100?$pk10_t:"0".$pk10_t;
			return date("Y",$lottery_time).$pk10_t;
		}
		if($type==6){//排列3
			$l_date=date("Y-m-d",$lottery_time);
			$pk10_date = '2014-03-19';
			$pk10_qi = 70;
			$holidays =array("4月5日","4月6日","4月7日","5月1日","5月2日","5月3日","5月31日","6月1日","6月2日","9月6日","9月7日","9月8日");
			$pk10_t = (strtotime($l_date)-strtotime($pk10_date))/86400;
			$pk10_t = $pk10_t+$pk10_qi;
			$pk10_t = $pk10_t > 100?$pk10_t:"0".$pk10_t;
			return "14".$pk10_t;
		}
		if($type==8){//北京快乐8
			$l_date=date("Y-m-d",$lottery_time);
			$pk10_date = '2014-03-31';
			$pk10_qi = 623023;
			$pk10_t = (strtotime($l_date)-strtotime($pk10_date))/86400;
			return ($pk10_t-1)*179+$qs['qishu']+$pk10_qi;
		}
	}else{
		return -1;
	}
	
	
}



//判断会员额度是否大于投注总额
function user_money($username,$money,$type){
	global $mysqlt;$db_config;
	$sql	=	"select money from k_user where username='".$username."' and site_id='".SITEID."' limit 1";
	
	$query	=	$mysqlt->query($sql);
	$user	=	$query->fetch_array();
	if($money<=0)
	{
		return -1;
	}
	if($user['money']-$money>=0){
	    return $user['money']-$money;	
	}else{
		return -1;
	}
	
}
/*
数字补0函数，当数字小于10的时候在前面自动补0
// */
// function BuLing ( $num ) {
// 	if ( $num<10 ) {
// 		$num = '0'.$num;
// 	}
// 	return $num;
// }
// /*
// 数字补0函数2，当数字小于10的时候在前面自动补00，当数字大于10小于100的时候在前面自动补0
// */
// function BuLings ( $num ) {
// 	if ( $num<10 ) {
// 		$num = '00'.$num;
// 	}
// 	if ( $num>9 && $num<100 ) {
// 		$num = '0'.$num;
// 	}
// 	return $num;
// }

//返回用户帐户金额
function Get_user_money($username){
	global $mysqlt;$db_config;
	$sql	=	"select money from k_user where username='".$username."' and site_id='".SITEID."' limit 1";
	$query	=	$mysqlt->query($sql);
	$user	=	$query->fetch_array();
	return $user['money'];
}
?>