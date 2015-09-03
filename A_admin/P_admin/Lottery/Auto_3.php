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
	$qishu		=	$_POST["qishu"];
	$datetime	=	$_POST["datetime"];
	$ball_1		=	$_POST["ball_1"];
	$ball_2		=	$_POST["ball_2"];
	$ball_3		=	$_POST["ball_3"];
	$ball_4		=	$_POST["ball_4"];
	$ball_5		=	$_POST["ball_5"];
	$ball_6		=	$_POST["ball_6"];
	$ball_7		=	$_POST["ball_7"];
	$ball_8		=	$_POST["ball_8"];
	$ball_9		=	$_POST["ball_9"];
	$ball_10	=	$_POST["ball_10"];
	$sql		=	"insert into c_auto_3(qishu,datetime,ball_1,ball_2,ball_3,ball_4,ball_5,ball_6,ball_7,ball_8,ball_9,ball_10) values (".$qishu.",'".$datetime."',".$ball_1.",".$ball_2.",".$ball_3.",".$ball_4.",".$ball_5.",".$ball_6.",".$ball_7.",".$ball_8.",".$ball_9.",".$ball_10.")";
	$mysqli->query($sql);
}elseif($_GET["action"]=="edit" && $id>0){
	$qishu		=	$_POST["qishu"];
	$datetime	=	$_POST["datetime"];
	$ball_1		=	$_POST["ball_1"];
	$ball_2		=	$_POST["ball_2"];
	$ball_3		=	$_POST["ball_3"];
	$ball_4		=	$_POST["ball_4"];
	$ball_5		=	$_POST["ball_5"];
	$ball_6		=	$_POST["ball_6"];
	$ball_7		=	$_POST["ball_7"];
	$ball_8		=	$_POST["ball_8"];
	$ball_9		=	$_POST["ball_9"];
	$ball_10	=	$_POST["ball_10"];
	$sql		=	"update c_auto_3 set qishu=".$qishu.",datetime='".$datetime."',ball_1=".$ball_1.",ball_2=".$ball_2.",ball_3=".$ball_3.",ball_4=".$ball_4.",ball_5=".$ball_5.",ball_6=".$ball_6.",ball_7=".$ball_7.",ball_8=".$ball_8.",ball_9=".$ball_9.",ball_10=".$ball_10." where id=".$id."";
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
		alert("请选择冠军车号");
		$("#ball_1").focus();
		return false;
	}
	if($("#ball_2").val()==""){
		alert("请选择亚军车号");
		$("#ball_2").focus();
		return false;
	}
	if($("#ball_3").val()==""){
		alert("请选择第三名车号");
		$("#ball_3").focus();
		return false;
	}
	if($("#ball_4").val()==""){
		alert("请选择第四名车号");
		$("#ball_4").focus();
		return false;
	}
	if($("#ball_5").val()==""){
		alert("请选择第五名车号");
		$("#ball_5").focus();
		return false;
	}
	if($("#ball_6").val()==""){
		alert("请选择第六名车号");
		$("#ball_6").focus();
		return false;
	}
	if($("#ball_7").val()==""){
		alert("请选择第七名车号");
		$("#ball_7").focus();
		return false;
	}
	if($("#ball_8").val()==""){
		alert("请选择第八名车号");
		$("#ball_8").focus();
		return false;
	}
	if($("#ball_9").val()==""){
		alert("请选择第九名车号");
		$("#ball_9").focus();
		return false;
	}
	if($("#ball_10").val()==""){
		alert("请选择第十名车号");
		$("#ball_10").focus();
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
          <td><span class="lans"><a href="auto_1.php" style="color:#FFF" title="广东快乐十分开奖管理">广东快乐十分开奖</a></span><span class="lans"><a href="auto_2.php" style="color:#FFF" title="重庆时时彩开奖管理">重庆时时彩开奖</a></span><span class="lans"><a href="auto_4.php" style="color:#FFF" title="重庆快乐十分开奖管理">重庆快乐十分开奖</a></span><span class="hongs"><a href="auto_3.php" style="color:#FFF" title="北京PK拾开奖管理">北京PK拾开奖</a></span><span class="lans"><a href="auto_5.php" style="color:#FFF" title="福彩3D">福彩3D</a></span><span class="lans"><a href="auto_6.php" style="color:#FFF" title="排列三">排列三</a></span><span class="lans"><a href="auto_8.php" style="color:#FFF" title="北京快乐8">北京快乐8</a></span></td>
        </tr>
      </table>
        <form name="form1" onSubmit="return check_submit();" method="post" action="?id=<?=$id?>&action=<?=$id>0 ? 'edit' : 'add'?>&page=<?=$_REQUEST['page']?>">
<?php
if($id>0 && !isset($_GET['action'])){
	$sql	=	"select * from c_auto_3 where id=$id limit 1";
	$query	=	$mysqli->query($sql);
	$rs		=	$query->fetch_array();
}
?>
    <table width="100%" border="0" cellpadding="5" cellspacing="1" class="font12" style="margin-top:5px;" bgcolor="#798EB9">
  <tr>
    <td  align="left" bgcolor="#3C4D82" style="color:#FFF">彩票类别：</td>
    <td  align="left" bgcolor="#3C4D82" style="color:#FFF"><strong>北京PK拾</strong></td>
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
    <td align="left" bgcolor="#F0FFFF">开奖号码：</td>
    <td align="left" bgcolor="#FFFFFF"><select name="ball_1" id="ball_1">
        <option value="1" <?=$rs['ball_1']==1 ? 'selected' : ''?>>1</option>
        <option value="2" <?=$rs['ball_1']==2 ? 'selected' : ''?>>2</option>
        <option value="3" <?=$rs['ball_1']==3 ? 'selected' : ''?>>3</option>
        <option value="4" <?=$rs['ball_1']==4 ? 'selected' : ''?>>4</option>
        <option value="5" <?=$rs['ball_1']==5 ? 'selected' : ''?>>5</option>
        <option value="6" <?=$rs['ball_1']==6 ? 'selected' : ''?>>6</option>
        <option value="7" <?=$rs['ball_1']==7 ? 'selected' : ''?>>7</option>
        <option value="8" <?=$rs['ball_1']==8 ? 'selected' : ''?>>8</option>
        <option value="9" <?=$rs['ball_1']==9 ? 'selected' : ''?>>9</option>
        <option value="10" <?=$rs['ball_1']==10 ? 'selected' : ''?>>10</option>
        <option value="" <?=$rs['ball_1']=='' ? 'selected' : ''?>>冠军</option>
      </select>
      <select name="ball_2" id="ball_2">
        <option value="1" <?=$rs['ball_2']==1 ? 'selected' : ''?>>1</option>
        <option value="2" <?=$rs['ball_2']==2 ? 'selected' : ''?>>2</option>
        <option value="3" <?=$rs['ball_2']==3 ? 'selected' : ''?>>3</option>
        <option value="4" <?=$rs['ball_2']==4 ? 'selected' : ''?>>4</option>
        <option value="5" <?=$rs['ball_2']==5 ? 'selected' : ''?>>5</option>
        <option value="6" <?=$rs['ball_2']==6 ? 'selected' : ''?>>6</option>
        <option value="7" <?=$rs['ball_2']==7 ? 'selected' : ''?>>7</option>
        <option value="8" <?=$rs['ball_2']==8 ? 'selected' : ''?>>8</option>
        <option value="9" <?=$rs['ball_2']==9 ? 'selected' : ''?>>9</option>
        <option value="10" <?=$rs['ball_2']==10 ? 'selected' : ''?>>10</option>
        <option value="" <?=$rs['ball_2']=='' ? 'selected' : ''?>>亚军</option>
      </select>
      <select name="ball_3" id="ball_3">
        <option value="1" <?=$rs['ball_3']==1 ? 'selected' : ''?>>1</option>
        <option value="2" <?=$rs['ball_3']==2 ? 'selected' : ''?>>2</option>
        <option value="3" <?=$rs['ball_3']==3 ? 'selected' : ''?>>3</option>
        <option value="4" <?=$rs['ball_3']==4 ? 'selected' : ''?>>4</option>
        <option value="5" <?=$rs['ball_3']==5 ? 'selected' : ''?>>5</option>
        <option value="6" <?=$rs['ball_3']==6 ? 'selected' : ''?>>6</option>
        <option value="7" <?=$rs['ball_3']==7 ? 'selected' : ''?>>7</option>
        <option value="8" <?=$rs['ball_3']==8 ? 'selected' : ''?>>8</option>
        <option value="9" <?=$rs['ball_3']==9 ? 'selected' : ''?>>9</option>
        <option value="10" <?=$rs['ball_3']==10 ? 'selected' : ''?>>10</option>
        <option value="" <?=$rs['ball_3']=='' ? 'selected' : ''?>>第三名</option>
      </select>
      <select name="ball_4" id="ball_4">
        <option value="1" <?=$rs['ball_4']==1 ? 'selected' : ''?>>1</option>
        <option value="2" <?=$rs['ball_4']==2 ? 'selected' : ''?>>2</option>
        <option value="3" <?=$rs['ball_4']==3 ? 'selected' : ''?>>3</option>
        <option value="4" <?=$rs['ball_4']==4 ? 'selected' : ''?>>4</option>
        <option value="5" <?=$rs['ball_4']==5 ? 'selected' : ''?>>5</option>
        <option value="6" <?=$rs['ball_4']==6 ? 'selected' : ''?>>6</option>
        <option value="7" <?=$rs['ball_4']==7 ? 'selected' : ''?>>7</option>
        <option value="8" <?=$rs['ball_4']==8 ? 'selected' : ''?>>8</option>
        <option value="9" <?=$rs['ball_4']==9 ? 'selected' : ''?>>9</option>
        <option value="10" <?=$rs['ball_4']==10 ? 'selected' : ''?>>10</option>
        <option value="" <?=$rs['ball_4']=='' ? 'selected' : ''?>>第四名</option>
      </select>
      <select name="ball_5" id="ball_5">
        <option value="1" <?=$rs['ball_5']==1 ? 'selected' : ''?>>1</option>
        <option value="2" <?=$rs['ball_5']==2 ? 'selected' : ''?>>2</option>
        <option value="3" <?=$rs['ball_5']==3 ? 'selected' : ''?>>3</option>
        <option value="4" <?=$rs['ball_5']==4 ? 'selected' : ''?>>4</option>
        <option value="5" <?=$rs['ball_5']==5 ? 'selected' : ''?>>5</option>
        <option value="6" <?=$rs['ball_5']==6 ? 'selected' : ''?>>6</option>
        <option value="7" <?=$rs['ball_5']==7 ? 'selected' : ''?>>7</option>
        <option value="8" <?=$rs['ball_5']==8 ? 'selected' : ''?>>8</option>
        <option value="9" <?=$rs['ball_5']==9 ? 'selected' : ''?>>9</option>
        <option value="10" <?=$rs['ball_5']==10 ? 'selected' : ''?>>10</option>
        <option value="" <?=$rs['ball_5']=='' ? 'selected' : ''?>>第五名</option>
      </select>
      <select name="ball_6" id="ball_6">
        <option value="1" <?=$rs['ball_6']==1 ? 'selected' : ''?>>1</option>
        <option value="2" <?=$rs['ball_6']==2 ? 'selected' : ''?>>2</option>
        <option value="3" <?=$rs['ball_6']==3 ? 'selected' : ''?>>3</option>
        <option value="4" <?=$rs['ball_6']==4 ? 'selected' : ''?>>4</option>
        <option value="5" <?=$rs['ball_6']==5 ? 'selected' : ''?>>5</option>
        <option value="6" <?=$rs['ball_6']==6 ? 'selected' : ''?>>6</option>
        <option value="7" <?=$rs['ball_6']==7 ? 'selected' : ''?>>7</option>
        <option value="8" <?=$rs['ball_6']==8 ? 'selected' : ''?>>8</option>
        <option value="9" <?=$rs['ball_6']==9 ? 'selected' : ''?>>9</option>
        <option value="10" <?=$rs['ball_6']==10 ? 'selected' : ''?>>10</option>
        <option value="" <?=$rs['ball_6']=='' ? 'selected' : ''?>>第六名</option>
      </select>
      <select name="ball_7" id="ball_7">
        <option value="1" <?=$rs['ball_7']==1 ? 'selected' : ''?>>1</option>
        <option value="2" <?=$rs['ball_7']==2 ? 'selected' : ''?>>2</option>
        <option value="3" <?=$rs['ball_7']==3 ? 'selected' : ''?>>3</option>
        <option value="4" <?=$rs['ball_7']==4 ? 'selected' : ''?>>4</option>
        <option value="5" <?=$rs['ball_7']==5 ? 'selected' : ''?>>5</option>
        <option value="6" <?=$rs['ball_7']==6 ? 'selected' : ''?>>6</option>
        <option value="7" <?=$rs['ball_7']==7 ? 'selected' : ''?>>7</option>
        <option value="8" <?=$rs['ball_7']==8 ? 'selected' : ''?>>8</option>
        <option value="9" <?=$rs['ball_7']==9 ? 'selected' : ''?>>9</option>
        <option value="10" <?=$rs['ball_7']==10 ? 'selected' : ''?>>10</option>
        <option value="" <?=$rs['ball_7']=='' ? 'selected' : ''?>>第七名</option>
      </select>
      <select name="ball_8" id="ball_8">
        <option value="1" <?=$rs['ball_8']==1 ? 'selected' : ''?>>1</option>
        <option value="2" <?=$rs['ball_8']==2 ? 'selected' : ''?>>2</option>
        <option value="3" <?=$rs['ball_8']==3 ? 'selected' : ''?>>3</option>
        <option value="4" <?=$rs['ball_8']==4 ? 'selected' : ''?>>4</option>
        <option value="5" <?=$rs['ball_8']==5 ? 'selected' : ''?>>5</option>
        <option value="6" <?=$rs['ball_8']==6 ? 'selected' : ''?>>6</option>
        <option value="7" <?=$rs['ball_8']==7 ? 'selected' : ''?>>7</option>
        <option value="8" <?=$rs['ball_8']==8 ? 'selected' : ''?>>8</option>
        <option value="9" <?=$rs['ball_8']==9 ? 'selected' : ''?>>9</option>
        <option value="10" <?=$rs['ball_8']==10 ? 'selected' : ''?>>10</option>
        <option value="" <?=$rs['ball_8']=='' ? 'selected' : ''?>>第八名</option>
      </select>
      <select name="ball_9" id="ball_9">
        <option value="1" <?=$rs['ball_9']==1 ? 'selected' : ''?>>1</option>
        <option value="2" <?=$rs['ball_9']==2 ? 'selected' : ''?>>2</option>
        <option value="3" <?=$rs['ball_9']==3 ? 'selected' : ''?>>3</option>
        <option value="4" <?=$rs['ball_9']==4 ? 'selected' : ''?>>4</option>
        <option value="5" <?=$rs['ball_9']==5 ? 'selected' : ''?>>5</option>
        <option value="6" <?=$rs['ball_9']==6 ? 'selected' : ''?>>6</option>
        <option value="7" <?=$rs['ball_9']==7 ? 'selected' : ''?>>7</option>
        <option value="8" <?=$rs['ball_9']==8 ? 'selected' : ''?>>8</option>
        <option value="9" <?=$rs['ball_9']==9 ? 'selected' : ''?>>9</option>
        <option value="10" <?=$rs['ball_9']==10 ? 'selected' : ''?>>10</option>
        <option value="" <?=$rs['ball_9']=='' ? 'selected' : ''?>>第九名</option>
      </select>
      <select name="ball_10" id="ball_10">
        <option value="1" <?=$rs['ball_10']==1 ? 'selected' : ''?>>1</option>
        <option value="2" <?=$rs['ball_10']==2 ? 'selected' : ''?>>2</option>
        <option value="3" <?=$rs['ball_10']==3 ? 'selected' : ''?>>3</option>
        <option value="4" <?=$rs['ball_10']==4 ? 'selected' : ''?>>4</option>
        <option value="5" <?=$rs['ball_10']==5 ? 'selected' : ''?>>5</option>
        <option value="6" <?=$rs['ball_10']==6 ? 'selected' : ''?>>6</option>
        <option value="7" <?=$rs['ball_10']==7 ? 'selected' : ''?>>7</option>
        <option value="8" <?=$rs['ball_10']==8 ? 'selected' : ''?>>8</option>
        <option value="9" <?=$rs['ball_10']==9 ? 'selected' : ''?>>9</option>
        <option value="10" <?=$rs['ball_10']==10 ? 'selected' : ''?>>10</option>
        <option value="" <?=$rs['ball_10']=='' ? 'selected' : ''?>>第十名</option>
      </select></td>
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
              <td align="center"><strong>冠军</strong></td>
              <td align="center"><strong>亚军</strong></td>
              <td align="center"><strong>第三名</strong></td>
              <td align="center"><strong>第四名</strong></td>
        <td height="25" align="center"><strong>第五名</strong></td>
        <td align="center"><strong>第六名</strong></td>
        <td align="center"><strong>第七名</strong></td>
        <td align="center"><strong>第八名</strong></td>
        <td align="center"><strong>第九名</strong></td>
        <td align="center"><strong>第十名</strong></td>
        <td align="center">结算</td>
        <td align="center"><strong>操作</strong></td>
          </tr>
<?php
$sql		=	"select id from c_auto_3 order by qishu desc";
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
	$sql	=	"select * from c_auto_3 where id in($id) order by qishu desc";
	$query	=	$mysqli->query($sql);
	$time	=	time();
	while($rows = $query->fetch_array()){
		$color = "#FFFFFF";
	  	$over	 = "#EBEBEB";
	 	$out	 = "#ffffff";
		if($rows['ok']==1){
			$ok = '<a href="js_3.php?qi='.$rows['qishu'].'"><font color="#FF0000">已结算</font></a>';
		}else{
			$ok = '<a href="js_3.php?qi='.$rows['qishu'].'"><font color="#0000FF">未结算</font></a>';
		}
?>
      <tr align="center" onMouseOver="this.style.backgroundColor='<?=$over?>'" onMouseOut="this.style.backgroundColor='<?=$out?>'" style="background-color:<?=$color?>; line-height:20px;">
        <td height="25" align="center" valign="middle">北京PK拾</td>
        <td align="center" valign="middle"><?=$rows['qishu']?></td>
        <td align="center" valign="middle"><?=$rows['datetime']?></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_3/<?=$rows['ball_1']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_3/<?=$rows['ball_2']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_3/<?=$rows['ball_3']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_3/<?=$rows['ball_4']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_3/<?=$rows['ball_5']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_3/<?=$rows['ball_6']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_3/<?=$rows['ball_7']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_3/<?=$rows['ball_8']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_3/<?=$rows['ball_9']?>.png"></td>
        <td align="center" valign="middle"><img src="/Lottery/Images/Ball_3/<?=$rows['ball_10']?>.png"></td>
        <td><?=$ok?></td>
        <td><a href="?id=<?=$rows["id"]?>&page=<?=$_REQUEST['page']?>">编辑</a></td>
        </tr>
<?php
	}
}
?>
	<tr style="background-color:#FFFFFF;">
        <td colspan="15" align="center" valign="middle"><?php echo $pageStr;?></td>
        </tr>
    </table></td>
    </tr>
  </table>
</div>
</body>
</html>