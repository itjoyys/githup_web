<?php
header('Content-Type:text/html; charset=utf-8');
include_once("../../include/config.php");
include ("../../include/mysqli.php");
include ("js_class.php");
$qi 		= $_REQUEST['qi'];
//获取开奖号码
$sql		= "select * from c_auto_5 where qishu=".$qi." order by id desc limit 1";
$query		= $mysqli->query($sql);
$rs			= $query->fetch_array();
$hm 		= array();
$hm[]		= $rs['ball_1'];
$hm[]		= $rs['ball_2'];
$hm[]		= $rs['ball_3'];
//根据期数读取未结算的注单
$sql		= "select * from c_bet where type='福彩3D' and js=0 and qishu=".$qi." order by addtime asc";
$query		= $mysqli->query($sql);
$sum		= $mysqli->affected_rows;
while($rows = $query->fetch_array()){
	//开始结算第一球
	if($rows['mingxi_1']=='第一球'){
		$ds = FC3D_Auto($rs['ball_1'],4);//单双
		$dx = FC3D_Auto($rs['ball_1'],1);//大小
		if($rows['mingxi_2']==$rs['ball_1'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx){
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
			//注单未中奖，给会员账户增加反水
			$msql="update k_user set money=money+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}
	}
	//开始结算第二球
	if($rows['mingxi_1']=='第二球'){
		$ds = FC3D_Auto($rs['ball_2'],5);//单双
		$dx = FC3D_Auto($rs['ball_2'],2);//大小
		if($rows['mingxi_2']==$rs['ball_2'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx){
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
			//注单未中奖，给会员账户增加反水
			$msql="update k_user set money=money+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}
	}
	//开始结算第三球
	if($rows['mingxi_1']=='第三球'){
		$ds = FC3D_Auto($rs['ball_3'],6);//单双
		$dx = FC3D_Auto($rs['ball_3'],3);//大小
		if($rows['mingxi_2']==$rs['ball_3'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx){
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
			//注单未中奖，给会员账户增加反水
			$msql="update k_user set money=money+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}
	}
	//开始结算独胆
	if($rows['mingxi_1']=='独胆'){
		if($rows['mingxi_2']==$rs['ball_1'] || $rows['mingxi_2']==$rs['ball_2'] || $rows['mingxi_2']==$rs['ball_3']){
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
			//注单未中奖，给会员账户增加反水
			$msql="update k_user set money=money+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}
	}
	
	//开始结算独胆
	if($rows['mingxi_1']=='跨度'){
		$numSpan=max(abs($rs['ball_1']-$rs['ball_2']),abs($rs['ball_1']-$rs['ball_3']),abs($rs['ball_2']-$rs['ball_3']));
		if($rows['mingxi_2']==$numSpan){
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
			//注单未中奖，给会员账户增加反水
			$msql="update k_user set money=money+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}
	}
	
	//开始结算总和大小
	if($rows['mingxi_2']=='总和大' || $rows['mingxi_2']=='总和小'){
		$zonghe = FC3D_Auto($hm,7);
		if($rows['mingxi_2']==$zonghe){
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
			//注单未中奖，给会员账户增加反水
			$msql="update k_user set money=money+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}
	}
	//开始结算总和单双
	if($rows['mingxi_2']=='总和单' || $rows['mingxi_2']=='总和双'){
		$zonghe = FC3D_Auto($hm,8);
		if($rows['mingxi_2']==$zonghe){
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
			//注单未中奖，给会员账户增加反水
			$msql="update k_user set money=money+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}
	}
	//开始结算龙虎和
	if($rows['mingxi_2']=='龙' || $rows['mingxi_2']=='虎' || $rows['mingxi_2']=='和'){
		$longhu = FC3D_Auto($hm,9);
		if($rows['mingxi_2']==$longhu){
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
			//注单未中奖，给会员账户增加反水
			$msql="update k_user set money=money+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}
	}
	//开始结算3连
	if($rows['mingxi_1']=='3连'){
		$qiansan = FC3D_Auto($hm,10);
		if($rows['mingxi_2']==$qiansan){
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
			//注单未中奖，给会员账户增加反水
			$msql="update k_user set money=money+".$rows['fs']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}
	}
	
}
$msql="update c_auto_5 set ok=1 where qishu=".$qi."";
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
