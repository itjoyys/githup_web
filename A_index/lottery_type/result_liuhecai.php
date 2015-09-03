<?
include_once("../include/config.php");



//$cdate=time();
$cdate=time();
$where='';
if($_POST['nn']){
	$where="nn='".$_POST['nn']."'";
}

?>

<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>福彩3D</title>
<link rel="stylesheet" href="public/css/reset.css" type="text/css">
<link rel="stylesheet" href="public/css/xp.css" type="text/css">
<script src="./public/js/jquery-1.8.3.min.js"></script>

</head>
<body id="HOP" ondragstart="window.event.returnValue=false" oncontextmenu="window.event.returnValue=false" onselectstart="event.returnValue=false">

<script src="js/orderFunc.js" type="text/javascript"></script>
<div style="width:960px;margin:0 auto">
  <table border="0" cellpadding="0" cellspacing="1">
    <tbody>
      <tr>
        <td width="10%"><span class="title" style="display:block;width:630px;">福彩3D-开奖管理</span></td>
        <td align="left">
		<table>
            <form action="" method="post" name="regstep1" id="regstep1">



           <input type="hidden" name="type" value="pk10">
            <tbody>
              <tr>
                <td colspan="2" align="center" nowrap="nowrap"><p class="STYLE2" align="right">期数：</p></td>
                <td colspan="6" align="center"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tbody>
                      <tr>
                        <td><input name="nn" class="input1" id="qishu" size="10" type="text"></td>
                        <td align="center" width="80"><input value=" 确定搜索 " name="B1" class="button_a" type="submit" style="height:20px;line-height:12px;"></td>
                        <td>&nbsp;</td>
                      </tr>
                    </tbody>  </form>
                  </table></td>
              </tr>
            </tbody>

          </table>

		  </td>
        <td width="37%"><div align="right">
            <select name="lx" id="lx" onchange="document.location=this.value">
               <option value="result.php?type=3D" <?if($_GET['type']=='3D'){echo 'selected="selected"';}?>>福彩3D</option>
              <option value="result.php?type=pl3" <?if($_GET['type']=='pl3'){echo 'selected="selected"';}?>>排列三</option>
              <option value="result.php?type=Cqss" <?if($_GET['type']=='Cqss'){echo 'selected="selected"';}?>>重庆时时彩</option>
              <option value="result.php?type=pk10" <?if($_GET['type']=='pk10'){echo 'selected="selected"';}?>>北京赛车PK拾</option>
              <option value="result.php?type=Cqsf" <?if($_GET['type']=='Cqsf'){echo 'selected="selected"';}?>>重庆十分</option>
              <option value="result.php?type=kl8" <?if($_GET['type']=='kl8'){echo 'selected="selected"';}?>>北京快乐8</option>
              <option value="result.php?type=Gdsf" <?if($_GET['type']=='Gdsf'){echo 'selected="selected"';}?>>广东快乐十分</option>
             <option value="result_liuhecai.php?type=lhc" selected="selected">六合彩</option>
            </select>
          </div></td>
      </tr>
    </tbody>
  </table>

  	 <table class="game_table" border="0" cellpadding="0" cellspacing="1">


    <tbody>

      <tr class="tbtitle3">
        <td><div align="center" width="10%">期数</div></td>
        <td align="center" width="20%">开奖时间</td>
        <td align="center" width="30%">开奖球号</td>
		<td align="center" width="30%"><div align="center">生肖</div></td>
      </tr>
<?php
$u = M("c_auto_7",$db_config);
$count=$u->field("id")->where($where)->count();//获得记录总数

//分页
$perNumber=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($page)?$_GET['page']:1;
$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;
$data = $u->field("*")->where($where)->limit($limit)->select();

$page = $u->showPage($totalPage,$page);
if(!empty($data)){
	foreach ($data as $k => $v) {


 ?>

      <tr>
        <td class="ball_bg2"><?=$v['nn'] ?></td>
        <td class="ball_bg2"><?=$v['nd'] ?></td>
        <td class="ball_bg2" align="center">

			<span class="ball_r" style="float:left;width:24px;height:24px;line_height:24px;display:block;"><?=$v['na'] ?></span>
			<span style="float:left;width:24px;height:24px;line_height:24px;display:block;"></span>
			<span class="ball_r" style="float:left;width:24px;height:24px;line_height:24px;display:block;"><?=$v['n1'] ?></span>
			<span class="ball_r" style="float:left;width:24px;height:24px;line_height:24px;display:block;"><?=$v['n2'] ?></span>
			<span class="ball_r" style="float:left;width:24px;height:24px;line_height:24px;display:block;"><?=$v['n3'] ?></span>
			<span class="ball_r" style="float:left;width:24px;height:24px;line_height:24px;display:block;"><?=$v['n4'] ?></span>
			<span class="ball_r" style="float:left;width:24px;height:24px;line_height:24px;display:block;"><?=$v['n5'] ?></span>
			<span class="ball_r" style="float:left;width:24px;height:24px;line_height:24px;display:block;"><?=$v['n6'] ?></span>

		</td>
			<td class="ball_bg2">
				<span class="" style="float:left;width:24px;height:24px;line_height:24px;display:block;"><?=$v['sx'] ?></span>
			<span style="float:left;width:24px;height:24px;line_height:24px;display:block;"></span>
			<span class="" style="float:left;width:24px;height:24px;line_height:24px;display:block;"><?=$v['x1'] ?></span>
			<span class="" style="float:left;width:24px;height:24px;line_height:24px;display:block;"><?=$v['x2'] ?></span>
			<span class="" style="float:left;width:24px;height:24px;line_height:24px;display:block;"><?=$v['x3'] ?></span>
			<span class="" style="float:left;width:24px;height:24px;line_height:24px;display:block;"><?=$v['x4'] ?></span>
			<span class="" style="float:left;width:24px;height:24px;line_height:24px;display:block;"><?=$v['x5'] ?></span>
			<span class="" style="float:left;width:24px;height:24px;line_height:24px;display:block;"><?=$v['x6'] ?></span>
			</td>

      </tr>
	<?php 	}
} ?>

	      <tr>
        <td colspan="17" style="padding:0" height="25"><table align="center" border="0" cellpadding="1" cellspacing="0" width="100%">
            <tbody>
              <tr class="tbtitle">
                <td nowrap="nowrap" height="26" width="180"><div align="left">
                    <button onclick="javascript:location.reload();" class="button_a" style="width: 60; height: 22" ;=""><img src="public/images/icon_21x21_info.gif" align="absmiddle">刷新</button>
                  </div></td>
                <td height="26">
				<div align="center">
				 				</div></td>
                <td height="26" width="60"><div align="center">

                  </div></td>
              </tr>
            </tbody>
          </table></td>
      </tr>
    </tbody>
  </table>


</div>



<script>
function url(u){
	window.location.href=u;
}
</script>
<SCRIPT language=JAVASCRIPT>
//更改球号的波色类
  $(function(){

    var ball_r = $("span[class='ball_r']").length;
    var ball_b = $("span[class='ball_b']").length;
    var ball_g = $("span[class='ball_g']").length;
    // var ball_all = eval(ball_r) + eval(ball_g) +eval(ball_b);

  // $("td[class='ball_r']").removeClass("ball_r").
   var arr1=[01,02,07,8,12,13,18,19,23,24,29,30,34,35,40,45,46];//红

  var arr2=[03,04,9,10,14,15,20,25,26,31,36,37,41,42,47,48];//蓝

  var arr3=[05,06,11,16,17,21,22,27,28,32,33,38,39,43,44,49];//绿

//1
  $("span[class='ball_r']").each(function(){

      if($.inArray(eval($(this).text()),arr1) != -1){

      $(this).removeClass("ball_g").removeClass("ball_b").addClass("ball_r");

    }else if($.inArray(eval($(this).text()),arr2) != -1){

      $(this).removeClass("ball_r").removeClass("ball_g").addClass("ball_b");

    }else if($.inArray(eval($(this).text()),arr3) != -1){

       $(this).removeClass("ball_r").removeClass("ball_b").addClass("ball_g");
    }
  })
//2
  $("span[class='ball_b']").each(function(){

      if($.inArray(eval($(this).text()),arr1) != -1){

      $(this).removeClass("ball_g").removeClass("ball_b").addClass("ball_r");

    }else if($.inArray(eval($(this).text()),arr2) != -1){

      $(this).removeClass("ball_r").removeClass("ball_g").addClass("ball_b");

    }else if($.inArray(eval($(this).text()),arr3) != -1){

       $(this).removeClass("ball_r").removeClass("ball_b").addClass("ball_g");
    }
  })
  //3
  $("span[class='ball_g']").each(function(){

      if($.inArray(eval($(this).text()),arr1) != -1){

      $(this).removeClass("ball_g").removeClass("ball_b").addClass("ball_r");

    }else if($.inArray(eval($(this).text()),arr2) != -1){

      $(this).removeClass("ball_r").removeClass("ball_g").addClass("ball_b");

    }else if($.inArray(eval($(this).text()),arr3) != -1){

       $(this).removeClass("ball_r").removeClass("ball_b").addClass("ball_g");
    }
  })
  })
</script>

</body></html>