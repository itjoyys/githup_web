<?php
/*ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(-1);*/
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once dirname(__FILE__) . "/../../lib/video/Games.class.php";
include_once dirname(__FILE__) . "/../../lib/video/GameType.php";

$Company = trim(@$_GET["Company"]);

$g_type_arr = array("og", "ag", "mg", "ct", "bbin","lebo");

if (!in_array($Company, $g_type_arr)) {
    $data['Error']='未知的游戏';
	exit(json_encode($data));
}
$loginname = @$_REQUEST['UserName'];
$orderId = @$_REQUEST['OrderId'];
$videotype = @$_REQUEST['VideoType'];
$gametype = @$_REQUEST['GameType'];
$s_time = @$_REQUEST['S_Time'].' 00:00:00';
$e_time = @$_REQUEST['E_Time'].' 23:59:59';
$agentid = @$_REQUEST['agentid'];
$Page=intval(@$_REQUEST['Page']);
$Page_Num=intval(@$_REQUEST['Page_Num']);

if ($Page<=0 )$Page=1;
if ($Page_Num<=0)$Page_Num=20;

$games = new Games();
$agentid='';
//$agentid=$_SESSION['agent_id']; //代理后台
$data = $games->GetBetRecord($Company, $loginname, $orderId, $videotype,$gametype, $s_time, $e_time,$agentid, $Page, $Page_Num);
$data=json_decode($data,true);
$list=$data['data']['data'];
foreach($list as $k=>$r){
    $BetType = $r['BetType'];
    $GameResult = $r['GameResult'];
    $BetType_ = GetGameType($BetType,$t=1,$CompanyType=$Company,$GameType='game');
    if(!$BetType_[$BetType]['Name']) {
        $BetType_ = GetGameType($BetType,$t=1,$CompanyType=$Company,$GameType='video');
    }
    if(!$BetType_[$BetType]['Name']) $BetType_[$BetType]['Name']='';
    $data['data']['data'][$k]['BetType']=$BetType_[$BetType]['Name'];
    if($Company=='ct' && $data['data']['data'][$k]['BetType']=='龙虎'){
        $Result=explode('^',$r['GameResult']);
        $Result=explode('#',$Result[1]);
        $data['data']['data'][$k]['GameResult']=poker($Result[0]).poker($Result[1]);
    }elseif($Company=='ct' && $data['data']['data'][$k]['BetType']=='百家乐'){
        $Result=explode('^',$r['GameResult']);
        $Result=explode('#',$Result[1]);
        $data['data']['data'][$k]['GameResult']='庄'.poker($Result[0]).':'.poker($Result[1]).':'.poker($Result[2]).'<br>闲'.poker($Result[3]).':'.poker($Result[4]).':'.poker($Result[5]);
    }elseif($Company=='og'){
        $data['data']['data'][$k]['GameResult']=ogwin($r['GameResult']);
    }elseif($Company=='lebo' && ($data['data']['data'][$k]['BetType']=='百家乐')){
        $Result=explode('^',$r['GameResult']);
        $Result=explode(';',$Result[1]);
        $z=explode(',',$Result[1]);
        $x=explode(',',$Result[0]);
        $data['data']['data'][$k]['GameResult']='庄'.poker_lb($x[0]).':'.poker_lb($x[1]).':'.poker_lb($x[2]).'<br>闲'.poker_lb($z[0]).':'.poker_lb($z[1]).':'.poker_lb($z[2]);
    }elseif($Company=='lebo' && ($data['data']['data'][$k]['BetType']=='龙虎')){
        $Result=explode('^',$r['GameResult']);
        $Result=explode(';',$Result[0]);
        $data['data']['data'][$k]['GameResult']='庄'.$Result[1].' 闲'.$Result[0];
    }
}
echo json_encode($data);