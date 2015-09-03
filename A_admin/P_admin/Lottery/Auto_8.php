<?php
include_once("../../include/config.php");
include("../common/login_check.php");
check_quanxian("lottery");
include("../../include/mysqli.php");
include("../../include/pager.class.php");
include("auto_class.php");
$id	=	0;
if($_GET['id'] > 0){
	$id	=	$_GET['id'];
}
if($_REQUEST['page']==''){
	$_REQUEST['page']=1;
}
if($_GET["action"]=="add" && $id==0){ 
	$qishu		=	trim($_POST["qishu"]);
	$datetime	=	trim($_POST["datetime"]);
	$ball_1		=	trim($_POST["ball_1"]);
	$ball_2		=	trim($_POST["ball_2"]);
	$ball_3		=	trim($_POST["ball_3"]);
	$ball_4		=	trim($_POST["ball_4"]);
	$ball_5		=	trim($_POST["ball_5"]);
	$ball_6		=	trim($_POST["ball_6"]);
	$ball_7		=	trim($_POST["ball_7"]);
	$ball_8		=	trim($_POST["ball_8"]);
	$ball_9		=	trim($_POST["ball_9"]);
	$ball_10	=	trim($_POST["ball_10"]);
	$ball_11		=	trim($_POST["ball_11"]);
	$ball_12		=	trim($_POST["ball_12"]);
	$ball_13		=	trim($_POST["ball_13"]);
	$ball_14		=	trim($_POST["ball_14"]);
	$ball_15		=	trim($_POST["ball_15"]);
	$ball_16		=	trim($_POST["ball_16"]);
	$ball_17		=	trim($_POST["ball_17"]);
	$ball_18		=	trim($_POST["ball_18"]);
	$ball_19		=	trim($_POST["ball_19"]);
	$ball_20	=	trim($_POST["ball_20"]);
	$sql		=	"insert into c_auto_8(qishu,datetime,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8,ball_9,ball_10,ball_11,ball_12,ball_13,ball_14,ball_15,ball_16,ball_17,ball_18,ball_19,ball_20) values (".$qishu.",'".$datetime."',".$ball_1.",".$ball_2.",".$ball_3.",".$ball_4.",".$ball_5.",".$ball_6.",".$ball_7.",".$ball_8.",".$ball_9.",".$ball_10.$ball_11.",".$ball_12.",".$ball_13.",".$ball_14.",".$ball_15.",".$ball_16.",".$ball_17.",".$ball_18.",".$ball_19.",".$ball_20.")";
	$mysqli->query($sql);
}elseif($_GET["action"]=="edit" && $id>0){
	$qishu		=	trim($_POST["qishu"]);
	$datetime	=	trim($_POST["datetime"]);
	$ball_1		=	trim($_POST["ball_1"]);
	$ball_2		=	trim($_POST["ball_2"]);
	$ball_3		=	trim($_POST["ball_3"]);
	$ball_4		=	trim($_POST["ball_4"]);
	$ball_5		=	trim($_POST["ball_5"]);
	$ball_6		=	trim($_POST["ball_6"]);
	$ball_7		=	trim($_POST["ball_7"]);
	$ball_8		=	trim($_POST["ball_8"]);
	$ball_9		=	trim($_POST["ball_9"]);
	$ball_10	=	trim($_POST["ball_10"]);
	$ball_11		=	trim($_POST["ball_11"]);
	$ball_12		=	trim($_POST["ball_12"]);
	$ball_13		=	trim($_POST["ball_13"]);
	$ball_14		=	trim($_POST["ball_14"]);
	$ball_15		=	trim($_POST["ball_15"]);
	$ball_16		=	trim($_POST["ball_16"]);
	$ball_17		=	trim($_POST["ball_17"]);
	$ball_18		=	trim($_POST["ball_18"]);
	$ball_19		=	trim($_POST["ball_19"]);
	$ball_20	=	trim($_POST["ball_20"]);
	$sql		=	"update c_auto_8 set qishu=".$qishu.",datetime='".$datetime."',ball_1=".$ball_1.",ball_2=".$ball_2.",ball_3=".$ball_3.",ball_4=".$ball_4.",ball_5=".$ball_5.",ball_6=".$ball_6.",ball_7=".$ball_7.",ball_8=".$ball_8.",ball_9=".$ball_9.",ball_10=".$ball_10."',ball_11=".$ball_11.",ball_12=".$ball_12.",ball_13=".$ball_13.",ball_14=".$ball_14.",ball_15=".$ball_15.",ball_16=".$ball_16.",ball_17=".$ball_17.",ball_18=".$ball_18.",ball_19=".$ball_19.",ball_20=".$ball_20." where id=".$id."";
	$mysqli->query($sql);
}
?><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome</title>
<link rel="stylesheet" href="../images/css/admin_style_1.css" type="text/css" media="all" />
<script language="javascript" src="/js/jquery.js"></script>
<script language="javascript">
function check_submit(){
	if($("#qishu").val()==""){
		alert("请填写开奖期数");
		$("#qishu").focus();
		return false;
	}
	if($("#datetime").val()==""){
		alert("请填写开奖时间");
		$("#datetime").focus();
		return false;
	}
	if($("#ball_1").val()==""){
		alert("请选择第1球开奖号码");
		$("#ball_1").focus();
		return false;
	}
	if($("#ball_2").val()==""){
		alert("请选择第2球开奖号码");
		$("#ball_2").focus();
		return false;
	}
	if($("#ball_3").val()==""){
		alert("请选择第3球开奖号码");
		$("#ball_3").focus();
		return false;
	}
	if($("#ball_4").val()==""){
		alert("请选择第4球开奖号码");
		$("#ball_4").focus();
		return false;
	}
	if($("#ball_5").val()==""){
		alert("请选择第5球开奖号码");
		$("#ball_5").focus();
		return false;
	}
	if($("#ball_6").val()==""){
		alert("请选择第6球开奖号码");
		$("#ball_6").focus();
		return false;
	}
	if($("#ball_7").val()==""){
		alert("请选择第7球开奖号码");
		$("#ball_7").focus();
		return false;
	}
	if($("#ball_8").val()==""){
		alert("请选择第8球开奖号码");
		$("#ball_8").focus();
		return false;
	}
	if($("#ball_9").val()==""){
		alert("请选择第9球开奖号码");
		$("#ball_9").focus();
		return false;
	}
	if($("#ball_10").val()==""){
		alert("请选择第10球开奖号码");
		$("#ball_10").focus();
		return false;
	}
	if($("#ball_11").val()==""){
		alert("请选择第11球开奖号码");
		$("#ball_11").focus();
		return false;
	}
	if($("#ball_12").val()==""){
		alert("请选择第12球开奖号码");
		$("#ball_12").focus();
		return false;
	}
	if($("#ball_13").val()==""){
		alert("请选择第13球开奖号码");
		$("#ball_13").focus();
		return false;
	}
	if($("#ball_14").val()==""){
		alert("请选择第14球开奖号码");
		$("#ball_14").focus();
		return false;
	}
	if($("#ball_15").val()==""){
		alert("请选择第15球开奖号码");
		$("#ball_15").focus();
		return false;
	}
	if($("#ball_16").val()==""){
		alert("请选择第16球开奖号码");
		$("#ball_16").focus();
		return false;
	}
	if($("#ball_17").val()==""){
		alert("请选择第17球开奖号码");
		$("#ball_17").focus();
		return false;
	}
	if($("#ball_18").val()==""){
		alert("请选择第18球开奖号码");
		$("#ball_18").focus();
		return false;
	}
	if($("#ball_19").val()==""){
		alert("请选择第19球开奖号码");
		$("#ball_19").focus();
		return false;
	}
	if($("#ball_20").val()==""){
		alert("请选择第20球开奖号码");
		$("#ball_20").focus();
		return false;
	}
	return true;
}
</script>
</head>
<body>
<div id="pageMain">
  <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="font12">
        <tr>
          <td><span class="lans"><a href="auto_1.php" style="color:#FFF" title="广东快乐十分开奖管理">广东快乐十分开奖</a></span><span class="lans"><a href="auto_2.php" style="color:#FFF" title="重庆时时彩开奖管理">重庆时时彩开奖</a></span><span class="lans"><a href="auto_4.php" style="color:#FFF" title="重庆快乐十分开奖管理">重庆快乐十分开奖</a></span><span class="lans"><a href="auto_3.php" style="color:#FFF" title="北京PK拾开奖管理">北京PK拾开奖</a></span><span class="lans"><a href="auto_5.php" style="color:#FFF" title="福彩3D">福彩3D</a></span><span class="lans"><a href="auto_6.php" style="color:#FFF" title="排列三">排列三</a></span><span class="hongs"><a href="auto_8.php" style="color:#FFF" title="北京快乐8">北京快乐8</a></span></td>
        </tr>
      </table>
        <form name="form1" onSubmit="return check_submit();" method="post" action="?id=<?=$id?>&action=<?=$id>0 ? 'edit' : 'add'?>&page=<?=$_REQUEST['page']?>">
<?php
if($id>0 && !isset($_GET['action'])){
	$sql	=	"select * from c_auto_8 where id=$id limit 1";
	$query	=	$mysqli->query($sql);
	$rs		=	$query->fetch_array();
}
?>
    <table width="100%" border="0" cellpadding="5" cellspacing="1" class="font12" style="margin-top:5px;" bgcolor="#798EB9">
  <tr>
    <td  align="left" bgcolor="#3C4D82" style="color:#FFF">彩票类别：</td>
    <td  align="left" bgcolor="#3C4D82" style="color:#FFF"><strong>北京快乐8</strong></td>
  </tr>
  <tr>
    <td width="60"  align="left" bgcolor="#F0FFFF">开奖期号：</td>
    <td  align="left" bgcolor="#FFFFFF"><input name="qishu" type="text" id="qishu" value="<?=$rs['qishu']?>" size="20" maxlength="11"/></td>
  </tr>
  <tr>
    <td align="left" bgcolor="#F0FFFF">开奖时间：</td>
    <td align="left" bgcolor="#FFFFFF"><input name="datetime" type="text" id="datetime" value="<?=$rs['datetime']?>" size="20" maxlength="19"/></td>
    </tr>
  <tr>
    <td rowspan="2" align="left" bgcolor="#F0FFFF">开奖号码：</td>
    <td align="left" bgcolor="#FFFFFF">
	1球<input type="text" name="ball_1" id="ball_1" size="5" value="<?=$rs['ball_1']?>"/>&nbsp;
	2球<input type="text" name="ball_2" id="ball_2"  size="5" value="<?=$rs['ball_2']?>"/>&nbsp;
	3球<input type="text" name="ball_3" id="ball_3" size="5"  value="<?=$rs['ball_3']?>"/>&nbsp;
	4球<input type="text" name="ball_4" id="ball_4" size="5" value="<?=$rs['ball_4']?>"/>&nbsp;
	5球<input type="text" name="ball_5" id="ball_5" size="5" value="<?=$rs['ball_5']?>"/>&nbsp;
	6球<input type="text" name="ball_6" id="ball_6" size="5" value="<?=$rs['ball_6']?>"/>&nbsp;
	7球<input type="text" name="ball_7" id="ball_7" size="5" value="<?=$rs['ball_7']?>"/>&nbsp;
	8球<input type="text" name="ball_8" id="ball_8" size="5" value="<?=$rs['ball_8']?>"/>&nbsp;
	9球<input type="text" name="ball_9"  id="ball_9"size="5" value="<?=$rs['ball_9']?>"/>&nbsp;
	10球<input type="text" name="ball_10" id="ball_10" size="5" value="<?=$rs['ball_10']?>"/>&nbsp;
	</td>
  </tr>
  <tr>
    <td align="left" bgcolor="#FFFFFF">
	11球<input type="text" name="ball_11" id="ball_11" size="5" value="<?=$rs['ball_11']?>"/>&nbsp;
	12球<input type="text" name="ball_12" id="ball_12"size="5" value="<?=$rs['ball_12']?>"/>&nbsp;
	13球<input type="text" name="ball_13" id="ball_13"size="5" value="<?=$rs['ball_13']?>"/>&nbsp;
	14球<input type="text" name="ball_14" id="ball_14"size="5" value="<?=$rs['ball_14']?>"/>&nbsp;
	15球<input type="text" name="ball_15" id="ball_15"size="5" value="<?=$rs['ball_15']?>"/>&nbsp;
	16球<input type="text" name="ball_16" id="ball_16"size="5" value="<?=$rs['ball_16']?>"/>&nbsp;
	17球<input type="text" name="ball_17" id="ball_17"size="5" value="<?=$rs['ball_17']?>"/>&nbsp;
	18球<input type="text" name="ball_18" id="ball_17"size="5" value="<?=$rs['ball_18']?>"/>&nbsp;
	19球<input type="text" name="ball_19" id="ball_18"size="5" value="<?=$rs['ball_19']?>"/>&nbsp;
	20球<input type="text" name="ball_20" id="ball_20"size="5" value="<?=$rs['ball_20']?>"/>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="left" bgcolor="#FFFFFF"><input name="submit" type="submit" class="submit80" value="确认发布"/></td>
  </tr>
</table>  
    </form>
    <table width="100%" border="0" cellpadding="5" cellspacing="1" class="font12" style="margin-top:5px;" bgcolor="#798EB9">   
            <tr style="background-color:#3C4D82; color:#FFF">
              <td align="center"><strong>彩票类别</strong></td>
              <td align="center"><strong>彩票期号</strong></td>
              <td align="center"><strong>开奖时间</strong></td>
              <td align="center"><strong>1球</strong></td>
              <td align="center"><strong>2球</strong></td>
              <td align="center"><strong>3球</strong></td>
              <td align="center"><strong>4球</strong></td>
        <td height="25" align="center"><strong>5球</strong></td>
        <td align="center"><strong>6球</strong></td>
        <td align="center"><strong>7球</strong></td>
        <td align="center"><strong>8球</strong></td>
        <td align="center"><strong>9球</strong></td>
        <td align="center"><strong>10球</strong></td>
		<td align="center"><strong>11球</strong></td>
              <td align="center"><strong>12球</strong></td>
              <td align="center"><strong>13球</strong></td>
              <td align="center"><strong>14球</strong></td>
        <td height="25" align="center"><strong>15球</strong></td>
        <td align="center"><strong>16球</strong></td>
        <td align="center"><strong>17球</strong></td>
        <td align="center"><strong>18球</strong></td>
        <td align="center"><strong>19球</strong></td>
        <td align="center"><strong>20球</strong></td>
        <td align="center">结算</td>
        <td align="center"><strong>操作</strong></td>
          </tr>
<?php
$sql		=	"select id from c_auto_8 order by qishu desc";
$query		=	$mysqli->query($sql);
$sum		=	$mysqli->affected_rows; //总页数
$thisPage	=	1;
$pagenum	=	50;
if($_GET['page']){
	$thisPage	=	$_GET['page'];
}
$CurrentPage=isset($_GET['page'])?$_GET['page']:1;
$myPage=new pager($sum,intval($CurrentPage),$pagenum);
$pageStr= $myPage->GetPagerContent();

$id		=	'';
$i			=	1; //记录 uid 数
$start	=	($CurrentPage-1)*$pagenum+1;
$end	=	$CurrentPage*$pagenum;
while($row = $query->fetch_array()){
  if($i >= $start && $i <= $end){
	$id .=	$row['id'].',';
  }
  if($i > $end) break;
  $i++;
}
if($id){
	$id	=	rtrim($id,',');
	$sql	=	"select * from c_auto_8 where id in($id) order by qishu desc";
	$query	=	$mysqli->query($sql);
	$time	=	time();
	while($rows = $query->fetch_array()){
		$color = "#FFFFFF";
	  	$over	 = "#EBEBEB";
	 	$out	 = "#ffffff";
		if($rows['ok']==1){
			$ok = '<a href="js_8.php?qi='.$rows['qishu'].'"><font color="#FF0000">已结算</font></a>';
		}else{
			$ok = '<a href="js_8.php?qi='.$rows['qishu'].'"><font color="#0000FF">未结算</font></a>';
		}
?>
      <tr align="center" onMouseOver="this.style.backgroundColor='<?=$over?>'" onMouseOut="this.style.backgroundColor='<?=$out?>'" style="background-color:<?=$color?>; line-height:20px;">
        <td height="25" align="center" valign="middle">北京快乐8</td>
        <td align="center" valign="middle"><?=$rows['qishu']?></td>
        <td align="center" valign="middle"><?=$rows['datetime']?></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_1']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_2']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_3']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_4']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_5']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_6']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_7']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_8']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_9']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_10']?>.png"></td>
		 <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_11']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_12']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_13']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_14']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_15']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_16']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_17']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_18']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_19']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_4/<?=$rows['ball_20']?>.png"></td>
        <td><?=$ok?></td>
        <td><a href="?id=<?=$rows["id"]?>&page=<?=$_REQUEST['page']?>">编辑</a></td>
        </tr>
<?php
	}
}
?>
	<tr style="background-color:#FFFFFF;">
        <td colspan="25" align="center" valign="middle"><?php echo $pageStr;?></td>
        </tr>
    </table></td>
    </tr>
  </table>
</div>
</body>
</html>