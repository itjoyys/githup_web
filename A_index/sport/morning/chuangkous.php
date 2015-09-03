<?php
include('../../include/filter.php');
$bool	=	false;//print_r($_GET['lsm']);exit;
// if($_GET['lsm']!='undefined'){
	// $arr	=	explode('|',urldecode($_GET['lsm']));
// }else{
	// $arr=array();
// }
$arr=array();
$arrs=array();
include_once("../../include/public_config.php");
if($_GET['lsm']=='ft_danshi'){//足球单式
	$sql		=	"select match_name from bet_match WHERE Match_Type=0 AND Match_CoverDate>now() AND Match_Date!='".date("m-d")."' and Match_HalfId is not null order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	while($row	=	$query->fetch_array()){
		$arr[]=$row['match_name'];
	}
}elseif($_GET['lsm']=='ft_shangbanchang'){//足球上半场
	$sql		=	"select match_name from bet_match where Match_Type=1 and match_date='".date("m-d",strtotime("-12 hours"))."' AND Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) and (Match_BHo+Match_BAo<>0 or Match_Bdpl+Match_Bxpl<>0) and Match_IsShowb=1  order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	while($row	=	$query->fetch_array()){
		$arr[]=$row['match_name'];
	}
}elseif($_GET['lsm']=='ft_bodan'){//足球波胆
	$sql		=	"select match_name from bet_match where Match_Type=1 and Match_IsShowbd=1 and Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) and Match_Bd21>0 order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	while($row	=	$query->fetch_array()){
		$arr[]=$row['match_name'];
	}
}elseif($_GET['lsm']=='ft_shangbanbodan'){//足球上半波胆
	$sql		=	"select match_name from bet_match where match_date='".date("m-d",strtotime("-12 hours"))."' and Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) and Match_Hr_Bd10>0 order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	while($row	=	$query->fetch_array()){
		$arr[]=$row['match_name'];
	}
}elseif($_GET['lsm']=='ft_ruqiushu'){//足球总入球
	$sql		=	"select match_name from bet_match where Match_Type=1 and Match_IsShowt=1 and Match_Total01Pl>0 AND Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	while($row	=	$query->fetch_array()){
		$arr[]=$row['match_name'];
	}
}elseif($_GET['lsm']=='ft_banquanchang'){//足球半全场
	$sql		=	"select match_name from bet_match WHERE Match_Date='".date("m-d",strtotime("-12 hours"))."' and Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) and Match_BqMM>0 order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	while($row	=	$query->fetch_array()){
		$arr[]=$row['match_name'];
	}
}elseif($_GET['lsm']=='bk_danshi'){//篮球单式
	$sql		=	"select match_name from lq_match WHERE Match_Type!=3 AND Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) AND Match_Date='".date("m-d",strtotime("-12 hours"))."'  order by Match_CoverDate,iPage,iSn,Match_ID,match_name,Match_Master";//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	while($row	=	$query->fetch_array()){
		$arr[]=$row['match_name'];
	}
}elseif($_GET['lsm']=='tennis_danshi'){//网球单式
	$sql		=	"select Match_Name from tennis_Match WHERE Match_Type=1 and Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) AND Match_Date='".date("m-d",strtotime("-12 hours"))."' order by Match_CoverDate,Match_Name";//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	while($row	=	$query->fetch_array()){
		$arr[]=$row['match_name'];
	}
}elseif($_GET['lsm']=='tennis_bodan'){//网球赛盘投注
	$sql		=	"select Match_Name from tennis_Match WHERE Match_Type=10 and Match_Bd21>0 and Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) order by Match_CoverDate,Match_Name";//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	while($row	=	$query->fetch_array()){
		$arr[]=$row['match_name'];
	}
}elseif($_GET['lsm']=='volleyball_danshi'){//排球单式
	$sql		=	"select match_name from volleyball_match WHERE Match_Type=1 AND Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) AND Match_Date='".date("m-d",strtotime("-12 hours"))."' order by Match_CoverDate,match_name";//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	while($row	=	$query->fetch_array()){
		$arr[]=$row['match_name'];
	}
}elseif($_GET['lsm']=='volleyball_bodan'){//排球塞盘投注
	$sql		=	"select match_name from volleyball_match WHERE Match_Type=10 AND Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) order by Match_CoverDate,match_name";//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	while($row	=	$query->fetch_array()){
		$arr[]=$row['match_name'];
	}
}elseif($_GET['lsm']=='baseball_danshi'){//棒球单式
	$sql		=	"select match_name from baseball_match WHERE Match_Type=1 and Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) and Match_Date='".date("m-d",strtotime("-12 hours"))."' order by Match_CoverDate,match_name";//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	while($row	=	$query->fetch_array()){
		$arr[]=$row['match_name'];
	}
}elseif($_GET['lsm']=='guanjun'){//冠军
	$sql		=	"select match_name from t_guanjun where match_type=1 and match_coverdate>DATE_ADD(now(),INTERVAL -12 HOUR) and x_result is null order by  match_coverdate,match_name,x_id";//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	while($row	=	$query->fetch_array()){
		$arr[]=$row['match_name'];
	}
}elseif($_GET['lsm']=='ftz_danshi'){//足球早餐单式
	 $sql		=	"select match_name from bet_match WHERE Match_Type=0 AND Match_CoverDate>now() and match_date!='".date("m-d")."' order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";//print_r($sql);exit;

    $query		=	$mysqli->query($sql);
	while($row	=	$query->fetch_array()){
       // echo $row['match_name'];
		$arr[]=$row['match_name'];
	}

}elseif($_GET['lsm']=='ftz_shangbanchang'){//足球早餐上半场
	$sql		=	"select match_name from bet_match where Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) and match_date!='".date("m-d",strtotime("-12 hours"))."' and (Match_BHo+Match_BAo<>0 or Match_Bdpl+Match_Bxpl<>0) and Match_IsShowb=1 order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	while($row	=	$query->fetch_array()){
		$arr[]=$row['match_name'];
	}
}elseif($_GET['lsm']=='ftz_banquanchang'){//足球早餐半全场
	$sql		=	"select match_name from bet_match WHERE Match_Date<>'".date("m-d",strtotime("-12 hours"))."' and Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) and Match_BqMM>0 order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	while($row	=	$query->fetch_array()){
		$arr[]=$row['match_name'];
	}
}elseif($_GET['lsm']=='ftz_ruqiushu'){//足球早餐入球数
	$sql		=	"select match_name from bet_match where Match_Type=0 and Match_IsShowt=1 AND Match_CoverDate>now() and Match_Total01Pl>0 order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	while($row	=	$query->fetch_array()){
		$arr[]=$row['match_name'];
	}
}elseif($_GET['lsm']=='ftz_bodan'){//足球早餐波胆
	$sql		=	"select match_name from bet_match where Match_Type=0 and Match_IsShowbd=1 and Match_CoverDate>now() and Match_Bd21>0 order by Match_CoverDate,iPage,match_name,Match_Master,Match_ID,iSn";//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	while($row	=	$query->fetch_array()){
		$arr[]=$row['match_name'];
	}
}elseif($_GET['lsm']=='bkz_danshi'){//篮球早餐单式
	$sql		=	"select match_name from lq_match WHERE Match_Type!=3 AND Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) AND Match_Date<>'".date("m-d",strtotime("-12 hours"))."' order by Match_CoverDate,iPage,iSn,Match_ID,match_name,Match_Master";//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	while($row	=	$query->fetch_array()){
		$arr[]=$row['match_name'];
	}
}elseif($_GET['lsm']=='bk_gunqiu'){//篮球滚球
	
}elseif($_GET['lsm']=='ft_gunqiu'){//足球滚球
	
}
$arr=array_unique($arr);
//print_r($arr);exit;

if($_GET['lsm'] == 'zqzcds'){ //足球早餐单式
	$bool	=	true;
	$sql	=	"select match_name from bet_match WHERE Match_Type=0 AND Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) group by match_name";
	$query	=	$mysqli->query($sql);
}elseif($_GET['lsm'] == 'zqds'){ //足球单式
	$bool	=	true;
	$sql	=	"select match_name from bet_match WHERE Match_Type=1 AND Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) AND Match_Date='".date("m-d",strtotime("-12 hours"))."' and Match_HalfId is not null group by match_name";
	$query	=	$mysqli->query($sql);
}elseif($_GET['lsm'] == 'lqds'){ //篮球单式
	$bool	=	true;
	$sql	=	"select match_name from lq_match WHERE Match_Type!=3 AND Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) AND Match_Date='".date("m-d",strtotime("-12 hours"))."' group by match_name";
	$query	=	$mysqli->query($sql);
}elseif($_GET['lsm'] == 'zqsbc'){ //足球上半场
	$bool	=	true;
	$sql	=	"select match_name from bet_match where Match_Type=1 and match_date='".date("m-d",strtotime("-12 hours"))."' AND Match_CoverDate>DATE_ADD(now(),INTERVAL -12 HOUR) and (Match_BHo+Match_BAo<>0 or Match_Bdpl+Match_Bxpl<>0) and Match_IsShowb=1 group by match_name";
	$query	=	$mysqli->query($sql);
}elseif($_GET['lsm'] == 'gj'){ //冠军
	$bool	=	true;
	$sql	=	"select x_title as match_name from t_guanjun where match_type=1 and match_coverdate>DATE_ADD(now(),INTERVAL -12 HOUR) and x_result is null group by x_title";
	$query	=	$mysqli->query($sql);
}
if($bool){
	$arrs='';
	while($rows = $query->fetch_array()){
		$arrs=$rows['match_name'];
	}
}else{
	$arrs=array();
	if(is_array($arr)){
		foreach($arr as $kv){
				$arrs[]=$kv;
		}
	}
}
?>

<script>
var sel_gtype = parent.sel_gtype;
function onLoad() {
    if ("" + eval("parent.parent.parent." + sel_gtype + "_lid_ary") == "undefined") eval("parent.parent.parent." + sel_gtype + "_lid_ary='ALL'");
    var len = lid_form.elements.length;
    parent.setleghi(document.body.scrollHeight);
    if (eval("parent.parent.parent." + sel_gtype + "_lid_ary") == 'ALL') {
        lid_form.sall.checked = 'true';
        for (var i = 1; i < len; i++) {
            var e = lid_form.elements[i];
            if (e.id.substr(0, 3) == "LID") e.checked = 'true';
        }
    } else {
        for (var i = 1; i < len; i++) {
            var e = lid_form.elements[i];
            if (e.id.substr(0, 3) == "LID" && e.type == 'checkbox') {
                if (eval("parent.parent.parent." + sel_gtype + "_lid_ary").indexOf(e.id.substr(3, e.id.length) + "|", 0) != -1) {
                    e.checked = 'true';
                }
            }
        }
    }

}
function fx(){ //反选
	var checkboxs=document.getElementsByName("liangsai");
	for(var i=0;i<checkboxs.length;i++) {
		checkboxs[i].checked = !checkboxs[i].checked;
	}
}

function back() {
    parent.parent.leg_flag = "Y";
    parent.g_date = "ALL";
    //self.location.href=links;
    parent.LegBack();
}

function chk_league(){
	var alllsm='';
	var checkboxs=document.getElementsByName("liangsai");
	for(var i=0;i<checkboxs.length;i++) {
		if(checkboxs[i].checked){
			alllsm+=checkboxs[i].value+'$';
		}
	}
	parent.loaded(alllsm);
	parent.$('#league').val(alllsm);
	//alert(alllsm);
	back();
}

</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html style="overflow:hidden">
<head>
<title>选择联盟</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../public/css/mem_body_ft.css" type="text/css">
</head>
<body id="LEG" onload="onLoad();" onselectstart="self.event.returnValue=true" oncontextmenu="self.event.returnValue=true">
<form name="lid_form" action="football.php">
	
<table border="0" cellpadding="0" cellspacing="0" id="box">

  <tr>
    <td class="leg_top">
        <table width="704px;" border="0" cellspacing="0" cellpadding="0" >
          <tbody><tr>
            <td width="30%"> <input type="checkbox" value="all" id="sall" onclick="fx();"><label class="all_sel">反选</label> </td>
            <td class="btn_td">
            <input type="submit" name="button" id="button" value="取消" class="enter_btn" onclick="back();">&nbsp;
            <input type="button" name="button" id="button" value="提交" class="enter_btn" onclick="chk_league();">
            </td>
            <td class="close_td"><span class="close_box" onclick="back();">关闭</span></td>
          </tr>
        </tbody></table>
  	  
	</td>
  </tr>
  <tr>
    <td>
    <div class="leg_mem">
      <table border="0" cellspacing="1" cellpadding="0" class="leg_game">
      

	

		
		
		<?php
          	for ($i=0; $i < (count($arrs)/3); $i++) { 
          	  $j=0;	 
          	?>
          	<tr>
          	   <?php 
          	   foreach ($arrs as $key => $val) {
          	   	  $j++;	
          	   	  $k = $key+$i*3;
          	   	  
          	   ?>
			       <td class="league"><?if(@$arrs[$k]){?><div><input type=checkbox value="<?=@$arrs[$k]?>" name="liangsai" class="myinput"><?=@$arrs[$k]?></div><?}?></td>
           <?php 
           
             if ($j == 3) { 
             	break 1;//跳出第一层循环
             }
             }
             ?>
           </tr>
           <?php }?>
		

        

      </table> 
      </div>
	</td>
  </tr>
</tbody></table>
<div class="btn_box">
  <input type="submit" name="button" id="button" value="取消" class="enter_btn" onclick="back();">&nbsp;
  <input type="submit" name="button" id="button" value="提交" class="enter_btn" onclick="chk_league();">
</div>

</form>
</body>
</html>

