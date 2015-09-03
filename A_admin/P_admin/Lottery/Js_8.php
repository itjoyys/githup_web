<?php
header('Content-Type:text/html; charset=utf-8');
include_once("../../include/config.php");
include ("../../include/mysqli.php");
include ("js_class.php");
$qi 		= $_REQUEST['qi'];
//获取开奖号码
$sql		= "select * from c_auto_8 where qishu=".$qi." order by id desc limit 1";
$query		= $mysqli->query($sql);
$rs			= $query->fetch_array();
$hm 		= array();
$hm[]		= $rs['ball_1'];
$hm[]		= $rs['ball_2'];
$hm[]		= $rs['ball_3'];
$hm[]		= $rs['ball_4'];
$hm[]		= $rs['ball_5'];
$hm[]		= $rs['ball_6'];
$hm[]		= $rs['ball_7'];
$hm[]		= $rs['ball_8'];
$hm[]		= $rs['ball_9'];
$hm[]		= $rs['ball_10'];
$hm[]		= $rs['ball_11'];
$hm[]		= $rs['ball_12'];
$hm[]		= $rs['ball_13'];
$hm[]		= $rs['ball_14'];
$hm[]		= $rs['ball_15'];
$hm[]		= $rs['ball_16'];
$hm[]		= $rs['ball_17'];
$hm[]		= $rs['ball_18'];
$hm[]		= $rs['ball_19'];
$hm[]		= $rs['ball_20'];
//根据期数读取未结算的注单
$sql		= "select * from c_bet where type='北京快乐8' and js=0 and qishu=".$qi." order by addtime asc";
$query		= $mysqli->query($sql);
$sum		= $mysqli->affected_rows;
while($rows = $query->fetch_array()){
	//开始结算选一
	if($rows['mingxi_1']=='选一'){		
		if(in_array($rows['mingxi_2'],$hm)){
			//如果投注内容等于第一球开奖号码，则视为中奖
			$msql="update c_bet set js=1 where id='".$rows['id']."'";
			$mysqli->query($msql) or die ("注单修改失败!!!".$rows['id']);
			//注单中奖，给会员账户增加奖金
			$msql="update k_user set money=money+".$rows['win']."+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}else{
			//注单未中奖，修改注单内容
			$msql="update c_bet set win=0,js=1 where id=".$rows['id']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
			//未中奖，反水
			$msql="update k_user set money=money+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}
	}
	//开始结算选二
	if($rows['mingxi_1']=='选二'){	
		$tz =  explode(",",$rows['mingxi_2'])	;
		if(in_array($tz[0],$hm) && in_array($tz[1],$hm)){
			//如果投注内容等于第二球开奖号码，则视为中奖
			$msql="update c_bet set js=1 where id='".$rows['id']."'";
			$mysqli->query($msql) or die ("注单修改失败!!!".$rows['id']);
			//注单中奖，给会员账户增加奖金
			$msql="update k_user set money=money+".$rows['win']."+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}else{
			//注单未中奖，修改注单内容
			$msql="update c_bet set win=0,js=1 where id=".$rows['id']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
			//未中奖，反水
			$msql="update k_user set money=money+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}
	}
	//开始结算选三
	if($rows['mingxi_1']=='选三'){	
		$tz =  explode(",",$rows['mingxi_2']);
		if(in_array($tz[0],$hm) && in_array($tz[1],$hm) && in_array($tz[2],$hm)){
			//如果投注内容等于第三球开奖号码，则视为中奖
			$msql="update c_bet set js=1 where id='".$rows['id']."'";
			$mysqli->query($msql) or die ("注单修改失败!!!".$rows['id']);
			//注单中奖，给会员账户增加奖金
			$msql="update k_user set money=money+".$rows['win']."+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}else{
			//注单未中奖，修改注单内容
			$msql="update c_bet set win=0,js=1 where id=".$rows['id']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
			//未中奖，反水
			$msql="update k_user set money=money+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}
	}
	//开始结算选四
	if($rows['mingxi_1']=='选四'){	
		$tz =  explode(",",$rows['mingxi_2']);	
		if(in_array($tz[0],$hm) && in_array($tz[1],$hm) && in_array($tz[2],$hm) && in_array($tz[3],$hm)){
			//如果投注内容等于第四球开奖号码，则视为中奖
			$msql="update c_bet set js=1 where id='".$rows['id']."'";
			$mysqli->query($msql) or die ("注单修改失败!!!".$rows['id']);
			//注单中奖，给会员账户增加奖金
			$msql="update k_user set money=money+".$rows['win']."+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}else{
			//注单未中奖，修改注单内容
			$msql="update c_bet set win=0,js=1 where id=".$rows['id']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
			//未中奖，反水
			$msql="update k_user set money=money+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}
	}
	//开始结算选五
	if($rows['mingxi_1']=='选五'){	
		$tz =  explode(",",$rows['mingxi_2'])	;
		if(in_array($tz[0],$hm) && in_array($tz[1],$hm) && in_array($tz[2],$hm) && in_array($tz[3],$hm) && in_array($tz[4],$hm)){
			//如果投注内容等于第五球开奖号码，则视为中奖
			$msql="update c_bet set js=1 where id='".$rows['id']."'";
			$mysqli->query($msql) or die ("注单修改失败!!!".$rows['id']);
			//注单中奖，给会员账户增加奖金
			$msql="update k_user set money=money+".$rows['win']."+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}else{
			//注单未中奖，修改注单内容
			$msql="update c_bet set win=0,js=1 where id=".$rows['id']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
			//未中奖，反水
			$msql="update k_user set money=money+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}
	}
	//开始结算和值
	if($rows['mingxi_1']=='和值'){
		$ds		= Kl8_Auto($hm , 1);
		$dx		= Kl8_Auto($hm , 2);
		if($rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx){
			//如果投注内容等于第六球开奖号码，则视为中奖
			$msql="update c_bet set js=1 where id='".$rows['id']."'";
			$mysqli->query($msql) or die ("注单修改失败!!!".$rows['id']);
			//注单中奖，给会员账户增加奖金
			$msql="update k_user set money=money+".$rows['win']."+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}else{
			//注单未中奖，修改注单内容
			$msql="update c_bet set win=0,js=1 where id=".$rows['id']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
			//未中奖，反水
			$msql="update k_user set money=money+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}
	}
	//开始结算上中下
	if($rows['mingxi_1']=='上中下'){ini_set("display_errors","on");error_reporting(E_ALL);
		$szx		= Kl8_Auto($hm , 3);
		if($rows['mingxi_2']==$szx){
			//如果投注内容等于第七球开奖号码，则视为中奖
			$msql="update c_bet set js=1 where id='".$rows['id']."'";
			$mysqli->query($msql) or die ("注单修改失败!!!".$rows['id']);
			//注单中奖，给会员账户增加奖金
			$msql="update k_user set money=money+".$rows['win']."+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}else{
			//注单未中奖，修改注单内容
			$msql="update c_bet set win=0,js=1 where id=".$rows['id']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
			//未中奖，反水
			$msql="update k_user set money=money+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}
	}
	//开始结算奇和偶
	if($rows['mingxi_1']=='奇和偶'){
		$qho		= Kl8_Auto($hm , 3);
		if($rows['mingxi_2']==$qho){
			//如果投注内容等于第八球开奖号码，则视为中奖
			$msql="update c_bet set js=1 where id='".$rows['id']."'";
			$mysqli->query($msql) or die ("注单修改失败!!!".$rows['id']);
			//注单中奖，给会员账户增加奖金
			$msql="update k_user set money=money+".$rows['win']."+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}else{
			//注单未中奖，修改注单内容
			$msql="update c_bet set win=0,js=1 where id=".$rows['id']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
			//未中奖，反水
			$msql="update k_user set money=money+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}
	}
	
}
$msql="update c_auto_8 set ok=1 where qishu=".$qi."";
$mysqli->query($msql) or die ("期数修改失败!!!");
?>

<style type="text/css">
body,td,th {
	font-size: 12px;
}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?=$qi?>期 结算完毕！</td>
  </tr>
</table>
