<?php
header('Content-Type:text/html; charset=utf-8');
include_once("../../include/config.php");
include ("../../include/mysqli.php");
include ("js_class.php");
$qi 		= $_REQUEST['qi'];
//获取开奖号码
$sql		= "select * from c_auto_1 where qishu=".$qi." order by id desc limit 1";
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
//根据期数读取未结算的注单
$sql		= "select * from c_bet where type='广东快乐十分' and js=0 and qishu=".$qi." order by addtime asc";
$query		= $mysqli->query($sql);
$sum		= $mysqli->affected_rows;
while($rows = $query->fetch_array()){
	//开始结算第一球
	if($rows['mingxi_1']=='第一球'){
		$ds		= G10_Ds($rs['ball_1']);
		$dx		= G10_Dx($rs['ball_1']);
		$wsdx	= G10_WsDx($rs['ball_1']);
		$hsds	= G10_HsDs($rs['ball_1']);
		$fw		= G10_Fw($rs['ball_1']);
		$zfb	= G10_Zfb($rs['ball_1']);
		if($rows['mingxi_2']==$rs['ball_1'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx || $rows['mingxi_2']==$wsdx || $rows['mingxi_2']==$hsds || $rows['mingxi_2']==$fw || $rows['mingxi_2']==$zfb){
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
	if($rows['mingxi_1']=='第二球'){
		$ds		= G10_Ds($rs['ball_2']);
		$dx		= G10_Dx($rs['ball_2']);
		$wsdx	= G10_WsDx($rs['ball_2']);
		$hsds	= G10_HsDs($rs['ball_2']);
		$fw		= G10_Fw($rs['ball_2']);
		$zfb	= G10_Zfb($rs['ball_2']);
		if($rows['mingxi_2']==$rs['ball_2'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx || $rows['mingxi_2']==$wsdx || $rows['mingxi_2']==$hsds || $rows['mingxi_2']==$fw || $rows['mingxi_2']==$zfb){
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
	if($rows['mingxi_1']=='第三球'){
		$ds		= G10_Ds($rs['ball_3']);
		$dx		= G10_Dx($rs['ball_3']);
		$wsdx	= G10_WsDx($rs['ball_3']);
		$hsds	= G10_HsDs($rs['ball_3']);
		$fw		= G10_Fw($rs['ball_3']);
		$zfb	= G10_Zfb($rs['ball_3']);
		if($rows['mingxi_2']==$rs['ball_3'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx || $rows['mingxi_2']==$wsdx || $rows['mingxi_2']==$hsds || $rows['mingxi_2']==$fw || $rows['mingxi_2']==$zfb){
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
	if($rows['mingxi_1']=='第四球'){
		$ds		= G10_Ds($rs['ball_4']);
		$dx		= G10_Dx($rs['ball_4']);
		$wsdx	= G10_WsDx($rs['ball_4']);
		$hsds	= G10_HsDs($rs['ball_4']);
		$fw		= G10_Fw($rs['ball_4']);
		$zfb	= G10_Zfb($rs['ball_4']);
		if($rows['mingxi_2']==$rs['ball_4'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx || $rows['mingxi_2']==$wsdx || $rows['mingxi_2']==$hsds || $rows['mingxi_2']==$fw || $rows['mingxi_2']==$zfb){
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
	if($rows['mingxi_1']=='第五球'){
		$ds		= G10_Ds($rs['ball_5']);
		$dx		= G10_Dx($rs['ball_5']);
		$wsdx	= G10_WsDx($rs['ball_5']);
		$hsds	= G10_HsDs($rs['ball_5']);
		$fw		= G10_Fw($rs['ball_5']);
		$zfb	= G10_Zfb($rs['ball_5']);
		if($rows['mingxi_2']==$rs['ball_5'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx || $rows['mingxi_2']==$wsdx || $rows['mingxi_2']==$hsds || $rows['mingxi_2']==$fw || $rows['mingxi_2']==$zfb){
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
	if($rows['mingxi_1']=='第六球'){
		$ds		= G10_Ds($rs['ball_6']);
		$dx		= G10_Dx($rs['ball_6']);
		$wsdx	= G10_WsDx($rs['ball_6']);
		$hsds	= G10_HsDs($rs['ball_6']);
		$fw		= G10_Fw($rs['ball_6']);
		$zfb	= G10_Zfb($rs['ball_6']);
		if($rows['mingxi_2']==$rs['ball_6'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx || $rows['mingxi_2']==$wsdx || $rows['mingxi_2']==$hsds || $rows['mingxi_2']==$fw || $rows['mingxi_2']==$zfb){
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
	if($rows['mingxi_1']=='第七球'){
		$ds		= G10_Ds($rs['ball_7']);
		$dx		= G10_Dx($rs['ball_7']);
		$wsdx	= G10_WsDx($rs['ball_7']);
		$hsds	= G10_HsDs($rs['ball_7']);
		$fw		= G10_Fw($rs['ball_7']);
		$zfb	= G10_Zfb($rs['ball_7']);
		if($rows['mingxi_2']==$rs['ball_7'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx || $rows['mingxi_2']==$wsdx || $rows['mingxi_2']==$hsds || $rows['mingxi_2']==$fw || $rows['mingxi_2']==$zfb){
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
	if($rows['mingxi_1']=='第八球'){
		$ds		= G10_Ds($rs['ball_8']);
		$dx		= G10_Dx($rs['ball_8']);
		$wsdx	= G10_WsDx($rs['ball_8']);
		$hsds	= G10_HsDs($rs['ball_8']);
		$fw		= G10_Fw($rs['ball_8']);
		$zfb	= G10_Zfb($rs['ball_8']);
		if($rows['mingxi_2']==$rs['ball_8'] || $rows['mingxi_2']==$ds || $rows['mingxi_2']==$dx || $rows['mingxi_2']==$wsdx || $rows['mingxi_2']==$hsds || $rows['mingxi_2']==$fw || $rows['mingxi_2']==$zfb){
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
	//开始结算总和大小
	if($rows['mingxi_2']=='总和大' || $rows['mingxi_2']=='总和小'){
		$zhdx = G10_Auto($hm,2);
		if($zhdx=='和'){
			//如果投注内容等于第一球开奖号码，则视为中奖
			$msql="update c_bet set win=0,js=1 where id='".$rows['id']."'";
			$mysqli->query($msql) or die ("注单修改失败!!!".$rows['id']);
			//注单中奖，给会员账户增加奖金
			$msql="update k_user set money=money+".$rows['money']." where uid=".$rows['uid']."";
			$mysqli->query($msql) or die ("会员修改失败!!!".$rows['id']);
		}
		if($rows['mingxi_2']==$zhdx){
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
	//开始结算总和单双
	if($rows['mingxi_2']=='总和单' || $rows['mingxi_2']=='总和双'){
		$zhds = G10_Auto($hm,3);
		if($rows['mingxi_2']==$zhds){
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
	//开始结算总和尾数大小
	if($rows['mingxi_2']=='总和尾大' || $rows['mingxi_2']=='总和尾小'){
		$zhwsdx = G10_Auto($hm,4);
		if($rows['mingxi_2']==$zhwsdx){
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
	//开始结算龙虎
	if($rows['mingxi_2']=='龙' || $rows['mingxi_2']=='虎'){
		$lh = G10_Auto($hm,5);
		if($rows['mingxi_2']==$lh){
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
}
$msql="update c_auto_1 set ok=1 where qishu=".$qi."";
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
