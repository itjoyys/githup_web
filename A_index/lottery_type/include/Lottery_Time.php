<?php
/*********************************彩票時間*********************************************/
//$lottery_time = time()+1*12*3600;
// date_default_timezone_set('PRC');
$lottery_time = time()+strtotime("+12 hours");
$l_time=date("Y-m-d H:i:s",$lottery_time);
$l_date=date("Y-m-d",$lottery_time);
/*********************************時時彩時間*********************************************/
$lottery_ssc_time = time()+1*12*3600;
$ssc_time=date("H:i:s",$lottery_ssc_time);
/*********************************北京快乐8期数设置*********************************************/
$kl8_date = '2012-08-20';
$kl8_qi = 520455;
$kl8_t = (strtotime($l_date)-strtotime($kl8_date))/86400;
/*********************************彩票美东時間*********************************************/
function mdtime($md_time){
	$meidong_time = strtotime($md_time)-1*12*3600;
	$md_times=date("Y-m-d H:i:s",$meidong_time);
	return $md_times;
	}
function mdssc($md_ssc){
	$meidong_ssc = strtotime($md_ssc)-1*12*3600;
	$md_sscs=date("Y-m-d",time()+1*12*3600)." ".date("H:i:s",$meidong_ssc);
	return $md_sscs;
	}
function bjssc($bj_ssc){
	$beijing_ssc = strtotime($bj_ssc);
	$bj_sscs=date("Y-m-d",time()+1*12*3600)." ".date("H:i:s",$beijing_ssc);
	return $bj_sscs;
	}
?>