<?php
print_r($_GET);exit;
$bool	=	false;
$arr	=	explode('|',urldecode($_GET['lsm']));
if($_GET['lsm'] == 'zqzcds'){ //足球早餐单式
	include_once("../include/public_config.php");
	$bool	=	true;
	$sql	=	"select match_name from bet_match WHERE Match_Type=0 AND Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) group by match_name";
	$query	=	$mysqls->query($sql);
}elseif($_GET['lsm'] == 'zqds'){ //足球单式
	include_once("../include/mysqls.php");
	$bool	=	true;
	$sql	=	"select match_name from bet_match WHERE Match_Type=1 AND Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) AND Match_Date='".date("m-d",strtotime("-12 hours"))."' and Match_HalfId is not null group by match_name";
	$query	=	$mysqls->query($sql);
}elseif($_GET['lsm'] == 'lqds'){ //篮球单式
	include_once("../include/mysqls.php");
	$bool	=	true;
	$sql	=	"select match_name from lq_match WHERE Match_Type!=3 AND Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) AND Match_Date='".date("m-d",strtotime("-12 hours"))."' group by match_name";
	$query	=	$mysqls->query($sql);
}elseif($_GET['lsm'] == 'zqsbc'){ //足球上半场
	include_once("../include/mysqls.php");
	$bool	=	true;
	$sql	=	"select match_name from bet_match where Match_Type=1 and match_date='".date("m-d",strtotime("-12 hours"))."' AND Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) and (Match_BHo+Match_BAo<>0 or Match_Bdpl+Match_Bxpl<>0) and Match_IsShowb=1 group by match_name";
	$query	=	$mysqls->query($sql);
}elseif($_GET['lsm'] == 'gj'){ //冠军
	include_once("../include/mysqls.php");
	$bool	=	true;
	$sql	=	"select x_title as match_name from t_guanjun where match_type=1 and match_coverdate>DATE_ADD(now(),INTERVAL -12 HOUR) and x_result is null group by x_title";
	$query	=	$mysqls->query($sql);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>选择联赛</title>
<style type="text/css">
<!--
body{font-size:12px;margin:0px;padding:0px; }
.cb_div{width:290px;height:30px;padding-left:10px;float:left; color:#000}
-->
</style>
</head>

<body >
<script type="text/javascript">
<!--
//注：每个嵌入页必须定义该方法，供父窗口调用，并且返回true或false来告之父窗口是否关闭
function Ok(){
	var lsm='';
	var checkboxs=document.getElementsByName("liangsai");
	for(var i=0;i<checkboxs.length;i++) {
		if(checkboxs[i].checked){
			lsm += checkboxs[i].value+"$";
		}
	}
	if(lsm == '') return false;
	
	window.parent.document.getElementById("league").value	=	lsm;
	parent.loaded(lsm);
	return true;//返回true模态窗口关闭；返回false模态窗口不关闭；
}

function fx(){ //反选
	var checkboxs=document.getElementsByName("liangsai");
	for(var i=0;i<checkboxs.length;i++) {
		checkboxs[i].checked = !checkboxs[i].checked;
	}
}

function cx(){ //重选
	var checkboxs=document.getElementsByName("liangsai");
	for(var i=0;i<checkboxs.length;i++) {
		checkboxs[i].checked = false;
	}
}
//-->
</script>
<table width="600" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="5" align="left" valign="middle" bgcolor="FFFFFF"></td>
  </tr>
  <tr>
    <td align="left" valign="middle" bgcolor="FFFFFF">
<?php
if($bool){
	while($rows = $query->fetch_array()){ 
?>
	<div class="cb_div"><input type="checkbox" name="liangsai" id="liangsai" value="<?=$rows['match_name']?>" /><?=$rows['match_name']?></div>
<?php
	}
}else{
	foreach($arr as $k=>$v){
?>
	<div class="cb_div"><input type="checkbox" name="liangsai" id="liangsai" value="<?=$v?>" /><?=$v?></div>
<?php
	}
}
?>
	</td>
  </tr>
</table>
<script language="javascript" src="style/js/mouse.js"></script>
</body>
</html>