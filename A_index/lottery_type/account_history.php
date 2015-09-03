<?php
include_once("../include/config.php");


function dates($num){
	if($num=='0'){
		return '星期日';
	}elseif($num=='1'){
		return '星期一';
	}elseif($num=='2'){
		return '星期二';
	}elseif($num=='3'){
		return '星期三';
	}elseif($num=='4'){
		return '星期四';
	}elseif($num=='5'){
		return '星期五';
	}elseif($num=='6'){
		return '星期六';
	}

}
 if ($_GET['type']) {
	 $map['type']=$_GET['type'];
 }

 $map['site_id']=SITEID;
 $map['uid']=$_SESSION['uid'];
 $record=M('c_bet',$db_config)->where($map)->order("addtime desc")->select();

 $record_data=array();
 if (!empty($record)) {
 	foreach($record as $v){
	$date=date("Y-m-d",strtotime($v['addtime']));
	$dates=strtotime($date);
	$record_data[$date]['date']=$date.dates(date('w',$dates));
	$record_data[$date]['money']+=$v['money'];
	if($v['js']!=0){
		if($v['win']==0){
			$record_data[$date]['result']-=$v['money'];
		}else{
			$record_data[$date]['result']+=$v['money']*($v['odds']-1);
		}
	}else{
	    if(isset($record_data[$date]['result'])){
	        $record_data[$date]['result']=$record_data[$date]['result'];
	    }else{
	        $record_data[$date]['result']=0; //没有结算的·结果为0
	    }
	}
}
 }
$c_bet=array();
if($_GET['startdate']){
	$j=0;
	$date=date("Y-m-d",strtotime($_GET['enddate']));
	while($date>$_GET['startdate']){
		$date=date("Y-m-d",strtotime($_GET['enddate'])-$j*3600*24);
		$dates=strtotime($date);
		if($record_data[$date]){
			$c_bet[$date]=$record_data[$date];
		}else{
			$c_bet[$date]['date']=$date.dates(date('w',$dates));
			$c_bet[$date]['money']=0;
			$c_bet[$date]['result']=0;
		}
		$j++;
	}
}else{
	for($i=0;$i<7;$i++){
		$date=date("Y-m-d",time()-$i*3600*24);
		$dates=strtotime($date);

		if($record_data[$date]){
			$c_bet[$date]=$record_data[$date];

		}else{
			$c_bet[$date]['date']=$date.dates(date('w',$dates));
			$c_bet[$date]['money']=0;
			$c_bet[$date]['result']=0;
		}
	}
}


?>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>webcom</title>
<link rel="stylesheet" href="./public/css/reset.css" type="text/css">
<link rel="stylesheet" href="./public/css/xp.css" type="text/css">
</head>
<body marginwidth="3" marginheight="3" id="HOP" ondragstart="window.event.returnValue=false" oncontextmenu="window.event.returnValue=false" onselectstart="event.returnValue=false">
<link rel="stylesheet" href="./public/css/mem_body_ft_ssc.css?=" type="text/css">
<script language="javascript" type="text/javascript" src="./public/My97DatePicker/WdatePicker.js"></script>
<script language="javascript" type="text/javascript" src="./public/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
var url="account_history.php.php?";

function CheckForm()
{	var start=document.getElementById("startdate").value;
	var end=document.getElementById("enddate").value;
	if(start!=''||end!=''){
		if(start=="")
		{
			alert("请选择开始日期！");
			return false;
		}
		if(end=="")
		{
			alert("请选择结束日期！");
			return false;
		}


		if(getDays(start,end)>7)
		{
			alert("范围不能超过7天！");
			return false;
		}
	}

}

function getDays(strDateStart,strDateEnd){

   var strSeparator = "-";
   var oDate1;
   var oDate2;
   var iDays;
   oDate1= strDateStart.split(strSeparator);//alert(oDate1);
   oDate2= strDateEnd.split(strSeparator);
   var strDateS = new Date(oDate1[0], oDate1[1]-1, oDate1[2]);
  var strDateE = new Date(oDate2[0], oDate2[1]-1, oDate2[2]);
   iDays = parseInt(Math.abs(strDateS - strDateE ) / 1000 / 60 / 60 /24)
   return iDays ;
}

</script>
<table border="0" cellpadding="0" cellspacing="0" id="box">
	<tbody><tr>
    <td class="mem">
    <h2><form name="myFORM" method="get" id="myFORM"  action="#" onsubmit="return CheckForm();">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" id="fav_bar">
			<tbody><tr>
				<td id="page_no"><b>帐户历史摘要</b></td>
               <td align="left">
					日期：
					<input id="startdate" class="za_text Wdate" type="text" style="height:20px" onfocus="WdatePicker()" value="<?=$_GET['startdate']?>" name="startdate">
					~
					<input id="enddate" class="za_text Wdate" type="text" style="height:20px" onfocus="WdatePicker()" value="<?=$_GET['enddate']?>" name="enddate">
					<input class="inputSub" type="submit" value="搜索">
				</td>
				<td>
					<select name="type" id="type" onchange="document.getElementById('myFORM').submit()" style="float:right;height:20px">
						<option value="">全部</option>
							<option value="福彩3D" <?php if($_GET['type']=="福彩3D"){echo 'selected="selected"';}?>>福彩3D</option>
							<option value="排列三" <?php if($_GET['type']=="排列三"){echo 'selected="selected"';}?>>排列三</option>
							<option value="重庆时时彩" <?php if($_GET['type']=="重庆时时彩"){echo 'selected="selected"';}?>>重庆时时彩</option>
							<option value="天津时时彩"<?php if($_GET['type']=="天津时时彩"){echo 'selected="selected"';}?>>天津时时彩</option>
							<option value="江西时时彩"<?php if($_GET['type']=="江西时时彩"){echo 'selected="selected"';}?>>江西时时彩</option>
							<option value="新疆时时彩"<?php if($_GET['type']=="新疆时时彩"){echo 'selected="selected"';}?>>新疆时时彩</option>
							<option value="北京快乐8" <?php if($_GET['type']=="北京快乐8"){echo 'selected="selected"';}?>>北京快乐8</option>
							<option value="北京赛车pk拾" <?php if($_GET['type']=="北京赛车pk拾"){echo 'selected="selected"';}?>>北京赛车pk拾</option>
							<option value="广东快乐十分" <?php if($_GET['type']=="广东快乐十分"){echo 'selected="selected"';}?>>广东快乐十分</option>
							<option value="重庆快乐十分" <?php if($_GET['type']=="重庆快乐十分"){echo 'selected="selected"';}?>>重庆快乐十分</option>
							<option value="江苏快3"<?php if($_GET['type']=="江苏快3"){echo 'selected="selected"';}?>>江苏快3</option>
							<option value="吉林快3"<?php if($_GET['type']=="吉林快3"){echo 'selected="selected"';}?>>吉林快3</option>
							<option value="六合彩" <?php if($_GET['type']=="六合彩"){echo 'selected="selected"';}?>>六合彩</option>

					</select>

				</td>
			</tr>
		</tbody></table></form>
    </h2>
	<table border="0" cellspacing="0" cellpadding="0" class="game">
      <tbody><tr class="center">
			<th class="his_wag">序号</th>
			<th class="his_time">日期</th>
			<th class="his_wag">金额</th>
			<th class="his_wag">佣金</th>
			<th class="his_wag">结果</th>
			</tr>
			<?
			$money=0;$result=0;
			$i=1;
// 			p($c_bet);
			foreach($c_bet as $val){
			$money+=$val['money'];
			$result+=$val['result'];
			?>

							<tr class="items center" onmouseover="javascript:this.bgColor='EDFF6C'" onmouseout="javascript:this.bgColor='f2f2f2'" bgcolor="f2f2f2">
				<td height="25"><?=$i++ ?></td>
				<td>
					<a href="note_list.php?action=list&amp;lx=0&amp;kithe=<?=$val['date']?>&amp;ids=&amp;uid=8b3c45471918822708f53&amp;langx=zh-cn" style="color:003366"><b><?=$val['date']?></b></a>
				</td>
				<td class="his_total right" nowrap="nowrap"><font style="color:#EECA0B">
					<?=$val['money']?></font></td>
				<td class="his_total right" nowrap="nowrap"><font style="color:#05940A">0</font></td>
				<td class="his_total right" nowrap="nowrap"><font style="color:#ff0000"><?=$val['result']?></font></td>
			</tr>
		<?}?>

		<tr class="sum_bar right">
			<td colspan="2" class="center his_total"> 小计</td>
			<td class="his_total"><span class="STYLE4"><font style="color:#EE8A0B"><?=$money?></font></span></td>
			<td class="his_total"><span class="STYLE4"><b><font style="color:#9F0">0</font></b></span></td>
			<td class="his_total"><span class="STYLE4"><b><font style="color:#EECA0B"><?=$result?></font></b></span></td>
		</tr>
			</tbody></table>
</td></tr>
</tbody></table>
</body></html>