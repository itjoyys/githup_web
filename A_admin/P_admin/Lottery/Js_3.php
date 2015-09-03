<?php
header('Content-Type:text/html; charset=utf-8');
include_once("../../include/config.php");
include ("../../include/mysqli.php");
include ("js_class.php");
$qi 		= $_REQUEST['qi'];
//获取开奖号码
$sql		= "select * from c_auto_3 where qishu=".$qi." order by id desc limit 1";
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
//根据期数读取未结算的注单
$sql		= "select * from c_bet where type='北京PK拾' and js=0 and qishu=".$qi." order by addtime asc";
$query		= $mysqli->query($sql);
$sum		= $mysqli->affected_rows;
while($rows = $query->fetch_array()){
	//开始结算第一球
	if($rows['mingxi_1']=='冠军'){
		$ds		= Pk10_Auto($hm , 10 , $rs['ball_1']);
		$dx		= Pk10_Auto($hm , 9 , $rs['ball_1']);
		$lh		= Pk10_Auto($hm , 4 , 0);
		if($rows['mingxi_2']==$rs['ball_1'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx || $rows['mingxi_2']==$lh){
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
	//开始结算第二球
	if($rows['mingxi_1']=='亚军'){
		$ds		= Pk10_Auto($hm , 10 , $rs['ball_2']);
		$dx		= Pk10_Auto($hm , 9 , $rs['ball_2']);
		$lh		= Pk10_Auto($hm , 5 , 0);
		if($rows['mingxi_2']==$rs['ball_2'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx || $rows['mingxi_2']==$lh){
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
	//开始结算第三球
	if($rows['mingxi_1']=='第三名'){
		$ds		= Pk10_Auto($hm , 10 , $rs['ball_3']);
		$dx		= Pk10_Auto($hm , 9 , $rs['ball_3']);
		$lh		= Pk10_Auto($hm , 6 , 0);
		if($rows['mingxi_2']==$rs['ball_3'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx || $rows['mingxi_2']==$lh){
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
	//开始结算第四球
	if($rows['mingxi_1']=='第四名'){
		$ds		= Pk10_Auto($hm , 10 , $rs['ball_4']);
		$dx		= Pk10_Auto($hm , 9 , $rs['ball_4']);
		$lh		= Pk10_Auto($hm , 7 , 0);
		if($rows['mingxi_2']==$rs['ball_4'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx || $rows['mingxi_2']==$lh){
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
	//开始结算第五球
	if($rows['mingxi_1']=='第五名'){
		$ds		= Pk10_Auto($hm , 10 , $rs['ball_5']);
		$dx		= Pk10_Auto($hm , 9 , $rs['ball_5']);
		$lh		= Pk10_Auto($hm , 8 , 0);
		if($rows['mingxi_2']==$rs['ball_5'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx || $rows['mingxi_2']==$lh){
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
	//开始结算第六球
	if($rows['mingxi_1']=='第六名'){
		$ds		= Pk10_Auto($hm , 10 , $rs['ball_6']);
		$dx		= Pk10_Auto($hm , 9 , $rs['ball_6']);
		if($rows['mingxi_2']==$rs['ball_6'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx){
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
	//开始结算第七球
	if($rows['mingxi_1']=='第七名'){
		$ds		= Pk10_Auto($hm , 10 , $rs['ball_7']);
		$dx		= Pk10_Auto($hm , 9 , $rs['ball_7']);
		if($rows['mingxi_2']==$rs['ball_7'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx){
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
	//开始结算第八球
	if($rows['mingxi_1']=='第八名'){
		$ds		= Pk10_Auto($hm , 10 , $rs['ball_8']);
		$dx		= Pk10_Auto($hm , 9 , $rs['ball_8']);
		if($rows['mingxi_2']==$rs['ball_8'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx){
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
	//开始结算第九球
	if($rows['mingxi_1']=='第九名'){
		$ds		= Pk10_Auto($hm , 10 , $rs['ball_9']);
		$dx		= Pk10_Auto($hm , 9 , $rs['ball_9']);
		if($rows['mingxi_2']==$rs['ball_9'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx){
			//如果投注内容等于第九球开奖号码，则视为中奖
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
	//开始结算第十球
	if($rows['mingxi_1']=='第十名'){
		$ds		= Pk10_Auto($hm , 10 , $rs['ball_10']);
		$dx		= Pk10_Auto($hm , 9 , $rs['ball_10']);
		if($rows['mingxi_2']==$rs['ball_10'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx){
			//如果投注内容等于第十球开奖号码，则视为中奖
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
	//开始结算冠亚军和
	if($rows['mingxi_1']=='冠亚军和'){
		$zh		= Pk10_Auto($hm , 1 , 0);
		$dx		= Pk10_Auto($hm , 2 , 0);
		$ds		= Pk10_Auto($hm , 3 , 0);
		if($rows['mingxi_2']==$zh || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx){
			//如果投注内容等于第十球开奖号码，则视为中奖
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
$msql="update c_auto_3 set ok=1 where qishu=".$qi."";
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
