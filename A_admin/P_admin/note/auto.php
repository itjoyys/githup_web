<?php
ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(1);                    //打印出所有的 错误信息
include_once("../../include/config.php");
include_once("../common/login_check.php");

function get_HK_ior_($H_ratio,$C_ratio){
    $H_ratio=$H_ratio*1000;
    $C_ratio=$C_ratio*1000;
    $out_ior=array();
    $nowType="";
    if ($H_ratio <= 1000 && $C_ratio <= 1000){
        $out_ior[0]=$H_ratio;
        $out_ior[1]=$C_ratio;
    }else{
        $line=2000 - ( $H_ratio + $C_ratio );
        if ($H_ratio > $C_ratio){
            $lowRatio=$C_ratio;
            $nowType = "C";
        }else{
            $lowRatio = $H_ratio;
            $nowType = "H";
        }

        if (((2000 - $line) - $lowRatio) > 1000){
            //對盤馬來盤
            $nowRatio = ($lowRatio + $line) * (-1);
        }else{
            //對盤香港盤
            $nowRatio=(2000 - $line) - $lowRatio;
        }

        if ($nowRatio < 0){
            $highRatio = floor(abs(1000 / $nowRatio) * 1000);
        }else{
            $highRatio = (2000 - $line - $nowRatio);
        }
        if ($nowType == "H"){
            $out_ior[0]=$lowRatio;
            $out_ior[1]=$highRatio;
        }else{
            $out_ior[0]=$highRatio;
            $out_ior[1]=$lowRatio;
        }
    }
    $out_ior[0]=$out_ior[0]/1000;
    $out_ior[1]=$out_ior[1]/1000;
    return $out_ior;
}
$msg_gq='';
$redis = new Redis();
$redis->connect('localhost',6379);
$d=$redis->get('ZQGQ_JSON');
//print_r($d);
$d=json_decode($d);
//print_r($d);

if($d[1]){
    $zqgq		=	array();
    $lasttime	=	$d[0][0];
    $count      =   count($d[1]);
    foreach($d[1] as $k =>$r){
        $dx	=	array();
        $dx	=	get_HK_ior_($r[9],$r[10]);
        $r[9]	=	$dx[0];
        $r[10]	=	$dx[1];
        $dx	=	get_HK_ior_($r[13],$r[14]);
        $r[13]	=	$dx[0];
        $r[14]	=	$dx[1];
        $zqgq[$k]['Match_ID']=$r[0];
        $zqgq[$k]['Match_Master']=$r[5];
        $zqgq[$k]['Match_Guest']=$r[6];
        $zqgq[$k]['Match_Name']=$r[2];
        $zqgq[$k]['Match_Time']=$r[1];
        $zqgq[$k]['Match_Ho']=$r[9];
        $zqgq[$k]['Match_DxDpl']=$r[14];
        $zqgq[$k]['Match_BHo']=$r[23];
        $zqgq[$k]['Match_Bdpl']=$r[28];
        $zqgq[$k]['Match_Ao']=$r[10];
        $zqgq[$k]['Match_DxXpl']=$r[13];
        $zqgq[$k]['Match_BAo']=$r[24];
        $zqgq[$k]['Match_Bxpl']=$r[27];
        $zqgq[$k]['Match_RGG']=$r[8];
        $zqgq[$k]['Match_BRpk']=$r[22];
        $zqgq[$k]['Match_ShowType']=$r[7];
        $zqgq[$k]['Match_Hr_ShowType']=$r[7];
        $zqgq[$k]['Match_DxGG']=$r[11];
        $zqgq[$k]['Match_Bdxpk']=$r[25];
        $zqgq[$k]['Match_HRedCard']=$r[29];
        $zqgq[$k]['Match_GRedCard']=$r[30];
        $zqgq[$k]['Match_NowScore']=$r[18].":".$r[19];
        $zqgq[$k]['Match_BzM']=$r[33];
        $zqgq[$k]['Match_BzG']=$r[34];
        $zqgq[$k]['Match_BzH']=$r[35];
        $zqgq[$k]['Match_Bmdy']=$r[36];
        $zqgq[$k]['Match_Bgdy']=$r[37];
        $zqgq[$k]['Match_Bhdy']=$r[38];
        $zqgq[$k]['Match_CoverDate']=date('Y-m-d H:i:s',$lasttime);
        $zqgq[$k]['Match_Date']='';
        $zqgq[$k]['Match_Type']=2;
    }

}

function cancelBet($status,$bid,$uid,$msg_title,$msg_info,$why=''){ //注单状态值，注单编号
    global $mysqlt;
    $InsertTime=date('Y-m-d H:i:s',time());
    $sql	=	"update k_bet,k_user set k_bet.lose_ok=1,k_bet.status=$status,k_user.money=k_user.money+k_bet.bet_money,k_bet.win=k_bet.bet_money,k_bet.update_time='$InsertTime',k_bet.match_endtime='$InsertTime',k_bet.sys_about='$why' where k_user.uid=k_bet.uid and k_bet.bid=$bid and k_bet.status=0";
    $mysqlt->autocommit(FALSE);
    $mysqlt->query("BEGIN"); //事务开始

    try{
        $mysqlt->query($sql);
        $q1		=	$mysqlt->affected_rows;
        $sql="select a.bet_money,a.balance,a.number,b.money,b.username from k_bet a,k_user b where b.uid = $uid and  a.bid = $bid limit 0,1";
        $betquery=$mysqlt->query($sql);
        $betorder=$betquery->fetch_array();
       // print_r($betorder);
        $bet_money=$betorder['bet_money'];
        $balance=$betorder['money'];
        $number=$betorder['number'];
        $username=$betorder['username'];
        if ($status==6) $msgtype='滚球危险球无效';
        elseif ($status==7) $msgtype='滚球红卡取消';
        else $msgtype='';
        $sql_cash	=	"insert into k_user_cash_record(site_id,uid,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark,source_id,username) values('".SITEID."','".$uid."','22','1','".$bet_money."','".$balance."','$InsertTime','体育注单：".$number." $msgtype',$bid,$username)";
        $mysqlt->query($sql_cash);
        $q2		=	$mysqlt->affected_rows;
        if($q1 == 2 && $q2){
            $mysqlt->commit(); //事务提交
            include_once("../../class/user.php");
            //user::msg_add($uid,$web_site['web_name'],$msg_title,$msg_info);
            return true;
        }else{
            $mysqlt->rollback(); //数据回滚
            return false;
        }
    }catch(Exception $e){
        $mysqlt->rollback(); //数据回滚
        return false;
    }
}

function setOK($bid){ //审核通过

    global $mysqlt;
    $sql 	= "update k_bet set lose_ok=1,match_endtime=now() where bid=$bid and lose_ok=0";
    $mysqlt->autocommit(FALSE);
    $mysqlt->query("BEGIN"); //事务开始
    try{
        $mysqlt->query($sql);
        $q1		=	$mysqlt->affected_rows;
        if($q1 == 1){
            $mysqlt->commit(); //事务提交
            return true;
        }else{
            $mysqlt->rollback(); //数据回滚
            return false;
        }
    }catch(Exception $e){
        $mysqlt->rollback(); //数据回滚
        return false;
    }
}
$sec=round(time()-$lasttime);
$msg_gq = '距离上次采集 '.($sec>10?'<span style="color:#ff0000">'.$sec.'</span>':$sec).' 秒 - '.date('Y-m-d H:i:s',$lasttime).'<br>';
if(time()-$lasttime > 10){ //超时
    include_once("../../cache/zqgq.php");
    if(time()-$lasttime > 5){
        include_once("../../include/function_cj.php");
        zqgq_cj();
        include("../../cache/zqgq.php"); //重新载入
        $msg_gq.=$t= "PHP->滚球采集 [ $count ] 条！";

    }
}else{
    $msg_gq.=$t= "Redis->滚球采集 [ $count ] 条！";
}
$sql	= "select match_nowscore,match_id,bid,uid,master_guest,bet_info,Match_GRedCard,Match_HRedCard,bet_money from k_bet where lose_ok=0 and bet_time<=DATE_SUB(now(),INTERVAL 60 SECOND) and bet_time>=DATE_SUB(now(),INTERVAL 120 SECOND)  order by bid  desc ";
// $sql	= "select match_nowscore,match_id,bid,uid,master_guest,bet_info,Match_GRedCard,Match_HRedCard,bet_money from k_bet where lose_ok=0 and bet_time<='2015-04-27 05:41:15' and bet_time>='2015-04-27 03:41:15'  order by bid  desc ";
$query	=	$mysqlt->query($sql);
$rows	=	$query->fetch_array();
$msg 	=	date("Y-m-d H:i:s")."<p />"; //本次处理信息结果
//print_r($rows);
if(!$rows){
    $msg.=	"本次无滚球注单判断";
}else{
    include_once("../../cache/zqgq.php"); //加载足球滚球缓存文件

    $bet		=	array();
    $arr		=	array();
    $match_id	=	"";
    $num		=	0;
    do{
        $bet[$rows["bid"]]["uid"] 				=	$rows["uid"];
        $bet[$rows["bid"]]["match_id"] 			=	$rows["match_id"];
        $bet[$rows["bid"]]["bet_info"] 			=	$rows["bet_info"];
        $bet[$rows["bid"]]["master_guest"] 		=	$rows["master_guest"];
        $bet[$rows["bid"]]["Match_HRedCard"] 	=	$rows["Match_HRedCard"];
        $bet[$rows["bid"]]["Match_GRedCard"] 	=	$rows["Match_GRedCard"];
        $bet[$rows["bid"]]["match_nowscore"] 	=	$rows["match_nowscore"];
        $bet[$rows["bid"]]["bet_money"] 	    =	$rows["bet_money"];

        $bool = true; //默认赛事已结束
        for($i=0; $i<$count; $i++){
            if($zqgq[$i]['Match_ID'] == $rows["match_id"]){
                $arr[$num]['i']		= $i; //zqgq 数组的 i 下标
                $arr[$num]['bid']	= $rows["bid"]; //bet 数组的 bid 下标
                $bool				= false; //赛事未结束
                $num++;
                break;
            }
        }
        if($bool) $match_id .= $rows["match_id"].",";

    }while($rows = $query->fetch_array());
    echo 'NUM:'.$num;
    for($i=0; $i<$num; $i++){ //判断缓存文件的赛事
        $bool = $msg_t	=	'';

        if($bet[$arr[$i]['bid']]["match_nowscore"] == $zqgq[$arr[$i]['i']]['Match_NowScore']){ //比分未改变

            if($bet[$arr[$i]['bid']]["Match_HRedCard"]!=$zqgq[$arr[$i]['i']]['Match_HRedCard'] || $bet[$arr[$i]['bid']]["Match_GRedCard"]!=$zqgq[$arr[$i]['i']]['Match_GRedCard']){ //主队或客队红牌，红牌无效
                $msg_t 	=	'滚球注单红卡无效';
                $bool  	=	cancelBet(7,$arr[$i]['bid'],$bet[$arr[$i]['bid']]["uid"],$bet[$arr[$i]['bid']]["master_guest"]."_注单已取消",$bet[$arr[$i]['bid']]["master_guest"].'<br/>'.$bet[$arr[$i]['bid']]["bet_info"].'<br /><font style="color:#F00"/>因红卡无效，该注单取消,已返还本金。</font>','红卡无效');
            }else{ //注单有效
                $msg_t 	=	'滚球注单有效';
                $bool	=	setOK($arr[$i]['bid']);
            }
        }else{ //比分已改变，进球无效
            $msg_t		=	'滚球注单进球无效';
            $bool 		=	cancelBet(6,$arr[$i]['bid'],$bet[$arr[$i]['bid']]["uid"],$bet[$arr[$i]['bid']]["master_guest"]."_注单已取消",$bet[$arr[$i]['bid']]["master_guest"].'<br/>'.$bet[$arr[$i]['bid']]["bet_info"].'<br /><font style="color:#F00"/>因进球无效，该注单取消,已返还本金。</font>','进球无效');
        }
        if($bool){
            /*写入日志文件*/
            $d 			=	date('Y-m-d');
            $filename 	=	'../../cache/logList/'.$d.'.txt';
            $somecontent=	"[".date('Y-m-d H:i:s')."]   管理员".$_SESSION["login_name"]."审核了编号为".$arr[$i]['bid']."的".$msg_t."  投注金额[".$bet[$arr[$i]['bid']]["bet_money"]."]\r\n";
            $handle = fopen($filename, 'a');
            if (fwrite($handle, $somecontent) === FALSE) {
                exit;
            }
            fclose($handle);
            $msg .= "<font color='#0000FF'>审核了编号为".$arr[$i]['bid']."的".$msg_t."</font><br />";;
        }
    }


    if($match_id){ //有赛事已结束，需要从数据库中读取
        include_once("../../include/public_config.php");
        $match_id 	=	rtrim($match_id,",");
        $sql		=	"select Match_HRedCard,Match_GRedCard,Match_NowScore,Match_ID,Match_LstTime from bet_match where Match_Type=2 and Match_ID in($match_id)";
        $query		=	$mysqli->query($sql);
        while($rows =	$query->fetch_array()){
            foreach($bet as $k=>$v){
                $money = $v["bet_money"];
                if($v["match_id"] == $rows["Match_ID"]){ //有注单用户下了这场赛事
                    $bool = $msg_t	=	'';
                    if($v["match_nowscore"] == $rows["Match_NowScore"]){ //比分未改变
                        if($rows["Match_HRedCard"]!=$v["Match_HRedCard"] || $rows["Match_GRedCard"]!=$v["Match_GRedCard"]){ //主队或客队红牌，红牌无效
                            $msg_t 	=	'滚球注单红卡无效';
                            $bool  	=	cancelBet(7,$k,$v["uid"],$v["master_guest"]."_注单已取消",$v["master_guest"].'<br/>'.$v["bet_info"].'<br /><font style="color:#F00"/>因红卡无效，该注单取消,已返还本金。</font>','红卡无效');
                        }else{ //注单有效
                            $msg_t 	=	'滚球注单有效';
                            $bool	=	setOK($k);
                        }
                    }else{ //比分已改变，进球无效
                        $msg_t		=	'滚球注单进球无效';
                        $bool		=	cancelBet(6,$k,$v["uid"],$v["master_guest"]."_注单已取消",$v["master_guest"].'<br/>'.$v["bet_info"].'<br /><font style="color:#F00"/>因进球无效，该注单取消,已返还本金。</font>','进球无效');
                    }

                    if($bool){
                        /*写入日志文件*/
                        $d			=	date('Y-m-d');
                        $filename	=	'../../cache/logList/'.$d.'.txt';
                        $somecontent=	"[".date('Y-m-d H:i:s')."]   管理员".$_SESSION["login_name"]."审核了编号为".$k."的".$msg_t."  投注金额[".$money."]\r\n";
                        $handle = fopen($filename, 'a');
                        if (fwrite($handle, $somecontent) === FALSE) {
                            exit;
                        }
                        fclose($handle);

                        $msg .= "<font color='#0000FF'>审核了编号为".$k."的".$msg_t."</font><br />";
                        unset($bet[$k]);
                    }
                }
            }
        }
    }
}

?>

<html>
<head>
    <meta http-equiv="refresh" content="3">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=$t?>滚球自动审核</title>
</head>
<style type="text/css">

    body {
        FONT-SIZE: 12px;
        margin-left: 4px;
        margin-top: 0px;
        margin-right: 0px;
        PADDING-RIGHT: 0px; PADDING-LEFT: 0px;
        SCROLLBAR-FACE-COLOR: #59D6FF; PADDING-BOTTOM: 0px;
        SCROLLBAR-HIGHLIGHT-COLOR: #ffffff;
        SCROLLBAR-SHADOW-COLOR: #ffffff;
        SCROLLBAR-3DLIGHT-COLOR: #007BC6;
        SCROLLBAR-DARKSHADOW-COLOR: #007BC6;
        SCROLLBAR-ARROW-COLOR: #007BC6; PADDING-TOP: 0px;
        SCROLLBAR-TRACK-COLOR: #009ED2;
    }

</STYLE>
<script>
    window.parent.is_open = 1;
</script>
<body >
<div align="center">
    <div align="center" style="width:500px; height:200px; border:1px solid #CCC; font-size:13px;">
        <div align="left" style="padding:5px; background-color:#CCC">滚球自动审核</div>
        <div style="padding-top:50px;"><?=$msg_gq?><br><?=$msg?></div>

    </div></div>
</body>
</html>