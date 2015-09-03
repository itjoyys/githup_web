<?php
//print_r($_POST);exit;
/*ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(-1);*/
if(!isset($_SESSION["uid"]) || !isset($_SESSION["username"])){
    echo "<script type=\"text/javascript\" language=\"javascript\">window.location.href='select.php';</script>";
    exit();
}
$uid     = $_SESSION['uid'];
include_once dirname(__FILE__) . '/../include/site_config.php';
include_once(dirname(__FILE__)."/../wh/site_state.php");
GetSiteStatus($SiteStatus,1,'sport',1);

include(dirname(__FILE__).'/../include/filter.php');
include(dirname(__FILE__).'/../include/login_check.php');

include_once("../include/public_config.php");
include_once("common/logintu.php");
include_once("common/function.php");
include(dirname(__FILE__)."/../include/redis_config.php");
//$redis = new Redis();
//$redis->connect($RedisConefi['Host'],$RedisConefi['Port']); 
$mysqlit=$mysqlt;
if($_SESSION["check_action"] !== 'true'){ //用户用软件打水

    go("<font color=\"#000\">非法操作！<br/>如有疑问，请联系在线客服！</font>");
}
function str_leng($str){ //取字符串长度
    mb_internal_encoding("UTF-8");
    return mb_strlen($str)*12;
}
function check_point($ballsort,$column,$match_id,$point,$rgg,$dxgg,$tid=0,$index=0,$match_showtype,$islose){
    global $redis;

    $pk = array("Match_Ho","Match_Ao","Match_DxDpl","Match_DxXpl","Match_BHo","Match_BAo","Match_Bdpl","Match_Bxpl"); //让球大小盘口
    $pk_dy=array("match_bzm","match_bzg","match_bzh","match_bmdy","match_bgdy","match_bhdy");
    $t  = array(array("cn"=>"足球波胆","db_table"=>"bet_match"),
        array("cn"=>"足球单式","db_table"=>"bet_match"),
        array("cn"=>"足球上半场","db_table"=>"bet_match"),
        array("cn"=>"足球早餐","db_table"=>"bet_match"),
        array("cn"=>"足球滚球","db_table"=>"zqgq_match"),
        array("cn"=>"篮球单式","db_table"=>"lq_match"),
        array("cn"=>"篮球单节","db_table"=>"lq_match"),
        array("cn"=>"篮球滚球","db_table"=>"lqgq_match"),
        array("cn"=>"篮球早餐","db_table"=>"lq_match"),
        array("cn"=>"排球单式","db_table"=>"volleyball_match"),
        array("cn"=>"网球单式","db_table"=>"tennis_match"),
        array("cn"=>"棒球单式","db_table"=>"baseball_match"),
        array("cn"=>"冠军","db_table"=>"t_guanjun_team"),
        array("cn"=>"金融","db_table"=>"t_guanjun_team"));
    foreach ($t as $m){
        if($m['cn']==$ballsort){
            $db_table=$m['db_table'];
        }
    }
    //把水位和让球与大小盘口设为字符串形式，以便下面绝对判断
    $rgg		=	"".$rgg;
    $dxgg		=	"".$dxgg;
    $point		=	"".$point;


    if($db_table=="zqgq_match" || $db_table=="lqgq_match"){ //足球滚球、篮球滚球不验证数据库，直接验证缓存文件
        if($db_table == "zqgq_match"){
            include(dirname(__FILE__) ."/include/function_cj.php");
            include(dirname(__FILE__) ."/include/function.php");
            $d=RedisZQGQ();

            $zqgq=$d['zqgq'];

            for($i=0; $i<count($zqgq);$i++){
                if(@$zqgq[$i]['Match_ID'] == $match_id) break;
            }
            if(strpos($ballsort,'上半场')>0){
                if($match_showtype!=$zqgq[$i]['Match_Hr_ShowType'])  go("上半场单式数据异常,请重新投注！");//让球单式检测
            }
            elseif($zqgq[$i]['Match_ShowType']!=$match_showtype) {
                go("滚球数据异常,请重新投注！");
            }//让球单式检测
            // if($zqgq[$i]['Match_ShowType']!=$match_showtype) go("数据异常,请重新投注！");//让球单式检测
            if($islose!=1) go("滚球数据异常,请重新投注！");//让球单式检测
            if($zqgq[$i][$column] < 0.01){
                go("盘口已关闭,交易失败");
            }
            //echo '<br><br><br><br><br><br><br><br><br>'.sprintf("%.2f",$zqgq[$i][$column]).'<br><br><br><br><br><br><br><br><br>';
            if(sprintf("%.2f",$zqgq[$i][$column])== $point){
                if(in_array($column,$pk)){ //盘口
                    if(($column=="Match_Ho" || $column=="Match_Ao") && $zqgq[$i]["Match_RGG"] !== $rgg){ //全场让球盘口改已变
                        if($zqgq[$i]["Match_RGG"] == ''){
                            go("盘口已关闭,交易失败");
                        }else{
                            confirm('盘口',$zqgq[$i]["Match_RGG"],$point,$zqgq[$i]["Match_RGG"],$zqgq[$i]["Match_DxGG"]);
                        }
                    }elseif(($column=="Match_BHo" || $column=="Match_BAo") && $zqgq[$i]["Match_BRpk"] !== $rgg){ //上半场让球盘口改已变
                        if($zqgq[$i]["Match_BRpk"] == ''){
                            go("盘口已关闭,交易失败");
                        }else{
                            confirm('盘口',$zqgq[$i]["Match_BRpk"],$point,$zqgq[$i]["Match_BRpk"],$zqgq[$i]["Match_Bdxpk"]);
                        }
                    }elseif(($column=="Match_DxDpl" || $column=="Match_DxXpl") && $zqgq[$i]["Match_DxGG"] !== $dxgg){ //全场大小盘口改已变
                        if($zqgq[$i]["Match_DxGG"] == ''){
                            go("盘口已关闭,交易失败");
                        }else{
                            confirm('盘口',$zqgq[$i]["Match_DxGG"],$point,$zqgq[$i]["Match_RGG"],$zqgq[$i]["Match_DxGG"]);
                        }
                    }elseif(($column=="Match_Bdpl" || $column=="Match_Bxpl") && $zqgq[$i]["Match_Bdxpk"] !== $dxgg){ //上半场大小盘口改已变
                        if($zqgq[$i]["Match_Bdxpk"] == ''){
                            go("盘口已关闭,交易失败");
                        }else{
                            confirm('盘口',$zqgq[$i]["Match_Bdxpk"],$point,$zqgq[$i]["Match_BRpk"],$zqgq[$i]["Match_Bdxpk"]);
                        }
                    }
                }
                return  true;
            }else{//水位变动
                confirm('水位',sprintf("%.2f",$zqgq[$i][$column]),sprintf("%.2f",$zqgq[$i][$column]),$rgg,$dxgg);
            }
        }else{

            include(dirname(__FILE__) ."/include/function_cj.php");
            $d=RedisLQGQ();
            $lqgq=$d['lqgq'];

            for($i=0; $i<count($lqgq);$i++){
                if(@$lqgq[$i]['Match_ID'] == $match_id) break;
            }
            if($lqgq[$i][$column] < 0.01){
                go("盘口已关闭,交易失败");
            }
            if($lqgq[$i][$column] == $point){
                if(in_array($column,$pk)){ //盘口
                    if(($column=="Match_Ho" || $column=="Match_Ao") && $lqgq[$i]["Match_RGG"] !== $rgg){ //全场让球盘口改已变
                        if($lqgq[$i]["Match_RGG"] == '' || $lqgq[$i]["Match_RGG"] == 0){
                            go("盘口已关闭,交易失败");
                        }else{
                            confirm('盘口',$lqgq[$i]["Match_RGG"],$point,$lqgq[$i]["Match_RGG"],$lqgq[$i]["Match_DxGG"]);
                        }
                    }elseif(($column=="Match_DxDpl" || $column=="Match_DxXpl") && $lqgq[$i]["Match_DxGG"] !== $dxgg){ //全场大小盘口改已变
                        if($lqgq[$i]["Match_DxGG"] == '' || $lqgq[$i]["Match_DxGG"] == 0){
                            go("盘口已关闭,交易失败");
                        }else{
                            confirm('盘口',$lqgq[$i]["Match_DxGG"],$point,$lqgq[$i]["Match_RGG"],$lqgq[$i]["Match_DxGG"]);
                        }
                    }
                }
                return  true;
            }else{//水位变动
                confirm('水位',$lqgq[$i][$column],$lqgq[$i][$column],$rgg,$dxgg);
            }
        }
    }else{
        global $mysqli;
        if($db_table	==	"t_guanjun_team"){
            if($tid){
                $sql		=	"select t.point from t_guanjun_team t,t_guanjun g where t.tid=$tid and t.xid=g.x_id and g.Match_CoverDate>now() limit 1"; //赛事未结束
                $query		=	$mysqli->query($sql);
                $rs			=	$query->fetch_array();
                $newpoint	=	"".sprintf("%.2f",$rs["point"]);
                if($newpoint===$point){
                    return  true;
                }else{   //水位变动
                    if($newpoint == 0){
                        go("盘口已关闭,交易失败");
                    }else{
                        confirm('水位',$newpoint,$newpoint);
                    }
                }
            }
        }else{//今日/早餐
            global	$touzhutype;
            $other		=	"";
            if($db_table == "bet_match") $other = ",Match_BRpk,Match_Bdxpk,Match_ShowType,Match_Hr_ShowType,Match_Type";
            if($db_table == "lq_match") $other = ",Match_Type,Match_ShowType";
            $sql		=	"select Match_RGG,Match_DxGG,$column $other from $db_table where match_id=$match_id and Match_CoverDate>now() limit 1"; //赛事未结束
            $query		=	$mysqli->query($sql);
            $rs			=	$query->fetch_array();
            $newpoint	=	"".sprintf("%.2f",$rs["$column"]);
            if(strpos($ballsort,'上半场')>0){

                if($match_showtype!=$rs['Match_Hr_ShowType'] && in_array(strtolower($column),$pk_dy)!=true)  go($column."上半场单式数据异常,请重新投注！");//让球单式检测
            }
            elseif($rs['Match_ShowType']!=$match_showtype) {
                go($rs['Match_ShowType']."单式数据异常,请重新投注！".$match_showtype.'-'.$match_id);
            }//让球单式检测
            if($rs['Match_Type']==2) go("滚球数据异常,请重新投注！");//让球单式检测

            if($newpoint===$point){
                if(in_array($column,$pk)){ //盘口
                    if(($column=="Match_Ho" || $column=="Match_Ao") && $rs["Match_RGG"] !== $rgg){ //全场让球盘口改已变
                        if($touzhutype == 1){ //串关
                            confirm_cg('盘口',$rs["Match_RGG"],$point,$rs["Match_RGG"],$rs["Match_DxGG"],$index);
                        }else{ //单式
                            confirm('盘口',$rs["Match_RGG"],$point,$rs["Match_RGG"],$rs["Match_DxGG"]);
                        }
                    }elseif(($column=="Match_DxDpl" || $column=="Match_DxXpl") && $rs["Match_DxGG"] !== $dxgg){ //全场大小盘口改已变
                        if($touzhutype == 1){ //串关
                            confirm_cg('盘口',$rs["Match_DxGG"],$point,$rs["Match_RGG"],$rs["Match_DxGG"],$index);
                        }else{ //单式
                            confirm('盘口',$rs["Match_DxGG"],$point,$rs["Match_RGG"],$rs["Match_DxGG"]);
                        }
                    }elseif(($column=="Match_BHo" || $column=="Match_BAo") && $rs["Match_BRpk"] !== $rgg){ //上半场让球盘口改已变
                        if($touzhutype == 1){ //串关
                            confirm_cg('盘口',$rs["Match_BRpk"],$point,$rs["Match_BRpk"],$rs["Match_Bdxpk"],$index);
                        }else{ //单式
                            confirm('盘口',$rs["Match_BRpk"],$point,$rs["Match_BRpk"],$rs["Match_Bdxpk"]);
                        }
                    }elseif(($column=="Match_Bdpl" || $column=="Match_Bxpl") && $rs["Match_Bdxpk"] !== $dxgg){ //上半场大小盘口改已变
                        if($touzhutype == 1){ //串关
                            confirm_cg('盘口',$rs["Match_Bdxpk"],$point,$rs["Match_BRpk"],$rs["Match_Bdxpk"],$index);
                        }else{ //单式
                            confirm('盘口',$rs["Match_Bdxpk"],$point,$rs["Match_BRpk"],$rs["Match_Bdxpk"]);
                        }
                    }
                }

                return  true;
            }else{   //水位变动
                if($newpoint == 0){
                    go("盘口已关闭,交易失败");
                }else{
                    if($touzhutype == 1){ //串关
                        confirm_cg('水位',$newpoint,$newpoint,$rgg,$dxgg,$index);
                    }else{ //单式
                        confirm('水位',$newpoint,$newpoint,$rgg,$dxgg);
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>投注状态</title>
        <style>
            body{ background-color:#493721;}
        </style>
    </head>
<body leftmargin="4px" rightmargin="0" topmargin="0">
<?php
$uid			=	$_SESSION["uid"];
$touzhutype		=	trim($_POST["touzhutype"]);
$bet_point		=	$_POST["bet_point"][0]*1;
$bet_money		=	trim($_POST["bet_money"]);
$point_column	=	$_POST["point_column"][0];
$arr_add		=	array('Match_Ho','Match_Ao','Match_DxDpl','Match_DxXpl','Match_BHo','Match_BAo','Match_Bdpl','Match_Bxpl');
$bet_win		=	$bet_money*$bet_point; //可赢金额=交易金额*当前水位
if(in_array($point_column,$arr_add)){ //让球，大小，半场让球，半场大小，可赢金额要加上本金
    $ben_add    = 0;
    $bet_win	+=	$bet_money;
}

echo '<font style="display:none">'.investSZ($uid).'</font>';

if(is_numeric($bet_money) && is_int($bet_money*1)){
    include_once("set.php");
    $bet_money	=	$bet_money*1;
    //会员余额
    $balance	=	0;
    $assets		=	0;
    $sql		= 	"SELECT money FROM k_user where uid=$uid and site_id='".SITEID."' limit 1";

    $query 		=	$mysqlt->query($sql);
    $rs			=	$query->fetch_array();
    if($rs['money']){
        $assets	=	round($rs['money'],2);
        $balance=	$assets-$bet_money;
    }

    if($balance<0){ //投注后，用户余额不能小于0
        go("账户余额不足<br>交易失败");
    }
    /*
    if(investSZ($uid)>=1){
        go("操作频繁<br>交易失败");
    }*/
    $InsertTime=date('Y-m-d H:i:s',time());

    if($touzhutype		==	0){ //单式
        //print_r($rs_bet);exit;
        $ball_sort		=	$_POST["ball_sort"][0];
        $column			=	$_POST["point_column"][0];
        $match_name		=	$_POST["match_name"][0];
        $master_guest	=	$_POST["master_guest"][0];
        $match_id		=	$_POST["match_id"][0];
        $tid			=	@intval($_POST["tid"][0]);
        $bet_info		=	$_POST["bet_info"][0];
        $touzhuxiang	=	$_POST['touzhuxiang'][0];
        $match_showtype	=	$_POST["match_showtype"][0];
        $match_rgg		=	$_POST["match_rgg"][0];
        $match_dxgg		=	$_POST["match_dxgg"][0];
        $match_nowscore	=	$_POST["match_nowscore"][0];
        $bet_point		=	$_POST["bet_point"][0];
        $match_type		=	$_POST["match_type"][0];
        $ben_add		=	$_POST["ben_add"][0];
        $match_time		=	$_POST["match_time"][0];
        $match_endtime	=	$_POST["match_endtime"][0];
        $Match_HRedCard	=	$Match_GRedCard	=	0;
        if(in_array($point_column,$arr_add)){ //让球，大小，半场让球，半场大小，可赢金额要加上本金
            $ben_add    = 1;
        }

        //限额判断
        if($ball_sort == "冠军" || $ball_sort == "金融"){
            $dz=@$dz_db["$ball_sort"];
            $dc=@$dc_db["$ball_sort"];
            $dm=@$dm_db["$ball_sort"];
        }else{
            $dz=@$dz_db["$ball_sort"]["$touzhuxiang"];
            $dc=@$dc_db["$ball_sort"]["$touzhuxiang"];
            $dm=@$dm_db["$ball_sort"]["$touzhuxiang"];
        }
        if(!$dz || $dz=="") $dz=$dz_db['未定义'];
        if(!$dc || $dc=="") $dc=$dc_db['未定义'];
        if(!$dm || $dm=="") $dm=$dm_db['未定义'];

        if($bet_money<$dm){
            go("交易金额不能少于 ".$dm." RMB!");
        }

        if($bet_money>$dz){ //判断单场限额，判断原因：用软件来投注，才会有此问题
            go("交易金额多于系统限额");
        }
        //判断当天限额，判断原因：用软件来投注，才会有此问题
        $s_t	=	strftime("%Y-%m-%d",time())." 00:00:00";
        $e_t	=	strftime("%Y-%m-%d",time())." 23:59:59";
        $sql	=	"select sum(bet_money) as s from `k_bet` where match_id=$match_id and uid=$uid and bet_time>='$s_t' and bet_time<='$e_t' and `status` not in(3,8) limit 1"; //无效跟平手不当成投注
        $query 	=	$mysqlt->query($sql);
        $rs		=	$query->fetch_array(); //取出单场总下注金额
        if(!$rs['s'] || $rs['s']=="null") $rs['s']=0;
        if(($rs['s']+$bet_money)>$dc){
            go("交易金额多于系统限额");
        }
        if(time()>strtotime($match_endtime) && !strpos($ball_sort,"滚球")){ //不是滚球，赛事已结束，无法投注
            go("赛事已结束<br>交易失败");
        }elseif(strpos($master_guest,'先開球') && time()+300>strtotime($match_endtime)){ //先開球提前 5 分钟关盘
            go("盘口已关闭<br>交易失败");
        }
        check_point($ball_sort,$column,$match_id,$bet_point,$match_rgg,$match_dxgg,$tid,0,$match_showtype,intval(@$_POST["is_lose"])); //验证水位是否变动

        //echo 2222;exit;
        $ksTime = $_POST["match_endtime"][0]; //赛事开赛时间
        if(@$_POST["is_lose"]==1){ //走地需要确认
            $lose_ok=0;
            if($ball_sort == "足球滚球"){ //足球滚球要记录红牌（赛事自动审核需要）
                $Match_HRedCard = $_POST["Match_HRedCard"][0];
                $Match_GRedCard = $_POST["Match_GRedCard"][0];
            }
        }else{ //不是滚球不需要确认
            $lose_ok=1;
        }
        if(!$match_type || $match_type=="") $match_type='1'; //为空统一为单式;(1：单式、2：滚球)
        $bet_info	=	write_bet_info($ball_sort,$column,$master_guest,$bet_point,$match_showtype,$match_rgg,$match_dxgg,$match_nowscore,$tid);
        include_once("class/bet_ds.php");

            if($id=bet_ds::dx_add($uid,$ball_sort,strtolower($column),$match_name,$master_guest,$match_id,$bet_info,$bet_money,$bet_point,$ben_add,$bet_win,$match_time,$match_endtime,$lose_ok,$match_showtype,$match_rgg,$match_dxgg,$match_nowscore,$match_type,$balance,$assets,$Match_HRedCard,$Match_GRedCard,$ksTime,$InsertTime)){

                if(@$_POST["is_lose"]==1){
                    go("交易确认中");
                }else{
                    echo '<script>alert("交易成功！");window.location.href="select.php";</script>';exit;
                }
            }else{
                go("交易失败");
            }
    }else{
        //限额判断
        if(count($_POST["match_name"])<3)
        {
            go("串关最少投注3场");
        }
        $dz		=	$dz_db["串关"];
        $dc		=	$dc_db["串关"];
        $dm		=	$dm_db["串关"];
        if(!$dz || $dz=="") $dz	=	$dz_db['未定义'];
        if(!$dc || $dc=="") $dc	=	$dc_db['未定义'];
        if(!$dm || $dc=="") $dm	=	$dm_db['未定义'];
        if($bet_money<$dm){
            go("交易金额不能少于 ".$dm." RMB!");
        }
        if($bet_money>$dz){ //目前只判断单场限额，判断原因：用软件来投注，才会有此问题
            go("交易金额多于系统限额");
        }
        //判断当天限额，判断原因：用软件来投注，才会有此问题
        $s_t	=	strftime("%Y-%m-%d",time())." 00:00:00";
        $e_t	=	strftime("%Y-%m-%d",time())." 23:59:59";
        $sql	=	"select sum(bet_money) as s from `k_bet_cg_group` where uid=".$_SESSION["uid"]." and bet_time>='$s_t' and bet_time<='$e_t' and `status`!=3"; //无效跟平手不当成投注
        $query 	=	$mysqlt->query($sql);
        $rs		=	$query->fetch_array(); //取出串关当天总下注金额
        if(!$rs['s'] || $rs['s']=="null") $rs['s'] = 0;
        if(($rs['s']+$bet_money)>$dc){
            go("交易金额多于系统限额");
        }
        $width		=	0; //宽
        $name1		=	''; //保存联赛名称
        $guest1		=	''; //保存队伍名称
        $info1		=	''; //保存交易信息
        $bet_win	=	0; //可赢金额默认为0
        $point		=	1; //水位默认为1
        $ksTime		=	$_POST["match_endtime"][0]; //赛事开赛时间,默认取第一个的日期时间
        $matchid='';
        for($i=0;$i<count($_POST["match_id"]);$i++){
            if(in_array($_POST["match_id"][$i],$matchid)){
                go("同场赛事不可参与过关!"); //串关同场赛事判断
                exit();
            }
            $matchid[]=$_POST["match_id"][$i];
            check_point($_POST["ball_sort"][$i],$_POST["point_column"][$i],$_POST["match_id"][$i],$_POST["bet_point"][$i],$_POST["match_rgg"][$i],$_POST["match_dxgg"][$i],0,$i,$_POST["match_showtype"][$i],0);
            $bet_point		=	$_POST["bet_point"][$i]*1;
            $point_column	=	$_POST["point_column"][$i];
            if(in_array($point_column,$arr_add)){ //让球，大小，半场让球，半场大小，可赢金额要加上本金
                $bet_point+=1;
            }

            $point *= $bet_point; //串关水位为相乘
        }


        $cg_count	=	count($_POST["match_name"]); //串关条数
        $bet_win	=	$point*$bet_money; //可赢金额=交易金额*水位

        $mysqlt->autocommit(FALSE);
        $mysqlt->query("BEGIN"); //事务开始
        try{
            include("cache/conf.php");
            $sql	=	"insert into k_bet_cg_group(uid,cg_count,bet_money,bet_win,balance,assets,www,match_coverdate,site_id,bet_time,agent_id,username) values('$uid','$cg_count','$bet_money','$bet_win',$balance,$assets,'$conf_www','$ksTime','".SITEID."','$InsertTime','".$_SESSION["agent_id"]."','".$_SESSION['username']."')"; //添加投注
            $mysqlt->query($sql);
            $cgid 	=	$mysqlt->insert_id;
            $q1		=	$mysqlt->affected_rows;
            $gid 	=	$mysqlt->insert_id;
            $number =	'C'.date("YmdHis").$cgid;
            $mysqlt->query("update  k_bet_cg_group set `number`='$number' where gid=$cgid");
            $sql	=	"insert into k_bet_cg(uid,gid,ball_sort,point_column,match_name,master_guest,match_id,bet_info,bet_money,bet_point,ben_add,match_endtime,match_showtype,match_rgg,match_dxgg,match_nowscore,site_id) values";
            $sql_cash	=	"insert into k_user_cash_record(site_id,uid,cash_type,cash_do_type,cash_num,cash_balance,cash_date,remark,source_id,username,source_type,agent_id) values";
            for($i=0;$i<$cg_count;$i++){
                $ball_sort		=	$_POST["ball_sort"][$i];
                $column			=	$_POST["point_column"][$i];
                $match_name		=	$_POST["match_name"][$i];
                $master_guest	=	$_POST["master_guest"][$i];
                $match_id		=	$_POST["match_id"][$i];
                $bet_info		=	$_POST["bet_info"][$i];
                $bet_money		=	$_POST["bet_money"];
                $bet_point		=	$_POST["bet_point"][$i];
                $ben_add		=	$_POST["ben_add"][$i];
                $match_showtype	=	$_POST["match_showtype"][$i];
                $match_rgg		=	$_POST["match_rgg"][$i];
                $match_dxgg		=	$_POST["match_dxgg"][$i];
                $match_nowscore	=	$_POST["match_nowscore"][$i];
                $match_endtime	=	$_POST["match_endtime"][$i];
                if(in_array($column,$arr_add)){ //让球，大小，半场让球，半场大小，可赢金额要加上本金
                    $ben_add=1;
                }
                $bet_info		=	write_bet_info($ball_sort,$column,$master_guest,$bet_point,$match_showtype,$match_rgg,$match_dxgg,$match_nowscore,@$tid);
                $sql		   .=	"('$uid','$gid','$ball_sort','".strtolower($column)."','$match_name','$master_guest','$match_id','$bet_info','$bet_money','$bet_point','$ben_add','$match_endtime','$match_showtype','$match_rgg','$match_dxgg','$match_nowscore','".SITEID."'),";

            }
            $sql_cash.="('".SITEID."','".$uid."','2','2','".$bet_money."','".$balance."','".$InsertTime."','体育过关 $cg_count 串 1 注单:$number',$cgid,'".$_SESSION['username']."',9,'".$_SESSION['agent_id']."')";
            $sql				=	rtrim($sql,",");
            $mysqlt->query($sql);

            $q2					=	$mysqlt->affected_rows;
            //echo $sql_cash;
            $mysqlt->query($sql_cash);
            $q_cash					=	$mysqlt->affected_rows;
            $sql				=	"update k_user set money=money-$bet_money where uid=$uid and money>=$bet_money and $balance>=0"; //扣钱
            $mysqlt->query($sql);
            $q3					=	$mysqlt->affected_rows;


            if($q1==1 && $q2==$i && $q3==1  && $q_cash==1){
                $mysqlt->commit(); //事务提交

                echo '<script>alert("交易成功！");window.location.href="select.php";</script>';exit;
            }else{
                $mysqlt->rollback(); //数据回滚
                go("交易失败");
            }
        }catch(Exception $e){
            $mysqlt->rollback(); //数据回滚
            go("交易失败");
        }
    }
}else{
    go("交易金额有误<br>交易失败");
}

function go($msg){
    $_SESSION["check_action"]=''; //检测用户是否用软件打水标识
    ?>
    <script language="javascript">
        parent.document.body.scrollTop="0px";
    </script>
    <table width="100%" style="margin:0px;" cellspacing="0" border="0">
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td style="color:#FFFFFF; height:30px;" align="center"><font style="color:#FFFFFF; font-weight:bold; font-size: 12px;"><?=$msg?></font></td>
        </tr>
        <tr>
            <td height="24" align="center"><a style="text-decoration:none; color:#ff0300; font-size:12px;" href="select.php">5秒后自动返回交易页</a></td>
        </tr>
    </table>
    <meta content="5;url=select.php?<?=rand()?>" http-equiv="refresh" />
    <script language="javascript">
        <!--
        if(self==top){
            top.location='/index.php';
        }
        -->
    </script>
    <script type="text/javascript" language="javascript" src="/js/left_mouse.js"></script>
    </body>
    </html>
    <?php
    exit();
}

function confirm($msg,$type,$pl,$rgg=0,$dxgg=0){
    ?>
    <div style="  width:225px; position:absolute; left: 6px; top: 5px; color:#ffffff; background: #e3cfaa; border: #977F62 solid 1px;">
        <form id="form1" name="form1" method="post" action="bet.php" style="margin:0 0 0 0;" onsubmit="javascript:document.getElementById('submit').disabled=true;">
            <table width="223" border="0" align="center" cellspacing="0">
                <tr>
                    <td colspan="2" align="left" valign="middle" style="color:#3a3a3a;padding-left:10px; padding-top:10px; font-size:13px;">当前<?=$msg?>已改变</td>
                </tr>
                <tr>
                    <td colspan="2" align="left" valign="middle" style="color:#3a3a3a;padding-left:10px; font-size:13px">最新<?=$msg?>：<span style="color:#FF0000; font-size:14px;"><?=$type?></span></td>
                </tr>
                <tr>
                    <td colspan="2" align="left" valign="middle" style="color:#3a3a3a;padding-left:10px; font-size:13px">是否继续交易？</td>
                </tr>
                <tr>
                    <td height="47" align="center" valign="middle">
                        <input type="button" name="Submit2"   value="取消交易" onclick="goleft();" /></td>
                    <td height="47" align="center" valign="middle"><input type="submit" name="submit" id="submit"   value="继续交易" /></td>
                </tr>
            </table>
            <input type="hidden" name="bet_money" value="<?=$_POST["bet_money"]?>" />
            <input type="hidden" name="touzhutype" value="0" />
            <input type="hidden" name="ball_sort[]" value="<?=$_POST["ball_sort"][0]?>" />
            <input type="hidden" name="point_column[]" value="<?=$_POST["point_column"][0]?>" />
            <input type="hidden" name="match_id[]" value="<?=$_POST["match_id"][0]?>" />
            <input type="hidden" name="match_name[]" value="<?=$_POST["match_name"][0]?>"  />
            <input type="hidden" name="match_showtype[]" value="<?=$_POST["match_showtype"][0]?>"  />
            <input type="hidden" name="match_rgg[]" value="<?=$rgg?>" />
            <input type="hidden" name="match_dxgg[]" value="<?=$dxgg?>" />
            <input type="hidden" name="match_nowscore[]"  value="<?=$_POST["match_nowscore"][0]?>"  />
            <input type="hidden" name="match_type[]"  value="<?=$_POST["match_type"][0]?>"  />
            <input type="hidden" name="touzhuxiang[]" value="<?=$_POST["touzhuxiang"][0]?>" />
            <input type="hidden" name="master_guest[]"  value="<?=$_POST["master_guest"][0]?>"/>
            <input type="hidden" name="bet_info[]" value="<?=$_POST["bet_info"][0]?>"/>
            <input type="hidden" name="bet_point[]" value="<?=$pl?>"/>
            <input type="hidden" name="match_time[]"  value="<?=$_POST["match_time"][0]?>"/>
            <input type="hidden" name="ben_add[]" value="<?=$_POST["ben_add"][0]?>"/>
            <input type="hidden" name="match_endtime[]"  value="<?=$_POST["match_endtime"][0]?>"/>
            <input type="hidden" name="Match_HRedCard[]"  value="<?=$_POST["Match_HRedCard"][0]?>"/>
            <input type="hidden" name="Match_GRedCard[]"  value="<?=$_POST["Match_GRedCard"][0]?>"/>
            <input type="hidden" name="is_lose"  value="<?=$_POST["is_lose"][0]?>"/>
            <input type="hidden" name="tid"  value="<?=$_POST["tid"][0]?>"/>
        </form>
    </div>
    <script language="javascript">
        <!--
        if(self==top){
            top.location='/index.php';
        }

        function goleft(){
            window.location.href="select.php";
        }

        window.setInterval("goleft()",5000); //5秒未点击，自动退到left.php页面
        -->
    </script>
    <script type="text/javascript" language="javascript" src="/js/left_mouse.js"></script>
    </body>
    </html>
    <?php
    exit();
}

function confirm_cg($msg,$type,$pl,$rgg=0,$dxgg=0,$index){
    $g_arr	=	explode('VS.',$_POST["master_guest"][$index]);
    ?>
    <div style="  width:225px; position:absolute; left: 6px; top: 5px; color:#ffffff; background: #e3cfaa; border: #977F62 solid 1px;">
        <form id="form1" name="form1" method="post" action="bet.php" style="margin:0 0 0 0;" onsubmit="javascript:document.getElementById('submit').disabled=true;">
            <table width="223" border="0" align="center" cellspacing="0"  style="color:#ffffff">
                <tr>
                    <td colspan="2" align="center" valign="middle" style=" color:#ffffff;padding-left:5px; padding-top:10px; font-size:12px; color:#813a0d; font-weight:bold;"><?=$_POST["match_name"][$index]?></td>
                </tr>
                <tr>
                    <td colspan="2" align="center" valign="middle" style="color:#ffffff;padding-left:5px; padding-top:10px; font-size:12px; color:#a32a55;"><?=$g_arr[0]?> <span style="color:#FF0000;">VS.</span> <span style="color:#890209;"><?=$g_arr[1]?></span></td>
                </tr>
                <tr>
                    <td colspan="2" align="left" valign="middle" style="color:#3a3a3a;padding-left:10px; padding-top:10px; font-size:12px;">当前<?=$msg?>已改变</td>
                </tr>
                <tr>
                    <td colspan="2" align="left" valign="middle" style="color:#3a3a3a;padding-left:10px; font-size:12px">最新<?=$msg?>：<span style="color:#FF0000; font-size:12px;"><?=$type?></span></td>
                </tr>
                <tr>
                    <td colspan="2" align="left" valign="middle" style="color:#3a3a3a;padding-left:10px; font-size:12px">是否继续交易？</td>
                </tr>
                <tr>
                    <td height="47" align="center" valign="middle">
                        <input type="button" name="Submit2"   value="取消交易" onclick="goleft();" /></td>
                    <td height="47" align="center" valign="middle"><input type="submit" name="submit" id="submit"   value="继续交易" /></td>
                </tr>
            </table>
            <input type="hidden" name="bet_money" value="<?=$_POST["bet_money"]?>" />
            <input type="hidden" name="touzhutype" value="1" />
            <?php
            $sum	=	count($_POST["match_id"]);
            for($i=0;$i<$sum;$i++){
                if($i == $index){
                    $_POST["match_rgg"][$i]		=	$rgg;
                    $_POST["match_dxgg"][$i]	=	$dxgg;
                    $_POST["bet_point"][$i]		=	$pl;
                }
                ?>
                <input type="hidden" name="ball_sort[]" value="<?=$_POST["ball_sort"][$i]?>" />
                <input type="hidden" name="point_column[]" value="<?=$_POST["point_column"][$i]?>" />
                <input type="hidden" name="match_id[]" value="<?=$_POST["match_id"][$i]?>" />
                <input type="hidden" name="match_name[]" value="<?=$_POST["match_name"][$i]?>"  />
                <input type="hidden" name="match_showtype[]" value="<?=$_POST["match_showtype"][$i]?>"  />
                <input type="hidden" name="match_rgg[]" value="<?=$_POST["match_rgg"][$i]?>" />
                <input type="hidden" name="match_dxgg[]" value="<?=$_POST["match_dxgg"][$i]?>" />
                <input type="hidden" name="match_nowscore[]"  value="<?=$_POST["match_nowscore"][$i]?>"  />
                <input type="hidden" name="match_type[]"  value="<?=$_POST["match_type"][$i]?>"  />
                <input type="hidden" name="master_guest[]"  value="<?=$_POST["master_guest"][$i]?>"/>
                <input type="hidden" name="bet_info[]" value="<?=$_POST["bet_info"][$i]?>"/>
                <input type="hidden" name="bet_point[]" value="<?=$_POST["bet_point"][$i]?>"/>
                <input type="hidden" name="match_time[]"  value="<?=$_POST["match_time"][$i]?>"/>
                <input type="hidden" name="ben_add[]" value="<?=$_POST["ben_add"][$i]?>"/>
                <input type="hidden" name="match_endtime[]"  value="<?=$_POST["match_endtime"][$i]?>"/>
                <input type="hidden" name="is_lose"  value="<?=$_POST["is_lose"][$i]?>"/>
            <?php
            }
            ?>
        </form>
    </div>
    <script language="javascript">
        <!--
        if(self==top){
            top.location='/index.php';
        }

        function goleft(){
            window.location.href="select.php";
        }

        window.setInterval("goleft()",5000); //5秒未点击，自动退到left.php页面
        -->
    </script>
    <script type="text/javascript" language="javascript" src="/js/left_mouse.js"></script>
    </body>
    </html>
    <?php
    exit();
}
?>