<? if(!defined('PHPYOU')) {
	exit('非法进入');
}
$_GET['leixing']='正码1-6';
$_GET['leixing_bet']='正1-6';

$XF=13;
if ($_GET['ids']!=""){$ids=$_GET['ids'];}else{$ids="正码1";}

$xc=5;
function ka_kk1($i,$bbs){
//   global $mysqli;
//    $result=$mysqli->query("select sum(sum_m) as sum_mm from c_odds_7 where kithe='".$Current_Kithe_Num."' and  username='".$_SESSION['kauser']."' and class1='正码' and class2='".$bbs."' and class3='".$i."' order by id desc");
// $ka_guanuserkk1=$result->fetch_array();
// return $ka_guanuserkk1[0];
   }
?>

<?

// if ($Current_KitheTable[29]==0 or $Current_KitheTable[$XF]==0) {
?>

<?
// }



$result=$mysqli->query("Select class3,rate,class2  from c_odds_7 where class1='正1-6'   Order By class2,ID");
$drop_table = array();
$y=0;
while($image = $result->fetch_array()){
$y++;
//echo $image['class3'];
array_push($drop_table,$image);
$KA_BL[$image['class2']][$image['class3']]=$image['rate'];
}

?>







<SCRIPT language=JAVASCRIPT>
if(self == top) {location = '/';}
if(window.location.host!=top.location.host){top.location=window.location;}
</SCRIPT>



 <SCRIPT language=JAVASCRIPT>
<!--
var count_win=false;
//window.setTimeout("self.location='quickinput2.php'", 180000);
function CheckKey(){
	if(event.keyCode == 13) return true;
	if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode > 95 || event.keyCode < 106)){alert("下注金额仅能输入数字!!"); return false;}
}

function ChkSubmit(){
    //设定『确定』键为反白
	document.all.btnSubmit.disabled = true;

	if (eval(document.all.allgold.innerHTML)<=0 )
	{
		alert("请输入下注金额!!");
	    document.all.btnSubmit.disabled = false;
		return false;

	}

    //    if (!confirm("是否确定下注")){
	   // document.all.btnSubmit.disabled = false;
    //    return false;
    //    }
		document.all.gold_all.value=eval(document.all.allgold.innerHTML)
        document.lt_form.submit();
         self.location.reload();
}



function CountGold(gold,type,rtype,bb,ffb){

}
//-->
</SCRIPT>


<style type="text/css">

body {background-color:#f1f1f1}
td.ball_ff{height:31px;}

.game_table td {
  border-right:1px solid #bbafaf;
  border-bottom:1px solid #bbafaf;
}
#mytab  td{
  border-left:1px solid #bbafaf;
}

</style>
<body >

<TABLE  border="0" cellpadding="2" cellspacing="1" bordercolordark="#f9f9f9" bgcolor="#CCCCCC"width=780 >
  <TBODY>
<!--   <TR class="tbtitle">
    <TD > -->
  <?php include("common_qishu.php"); ?>
<?php
$dat = M("c_odds_7",$db_config);
$data_bet = $dat->field("rate,id,class2,class3")->where("class1 = '".$_GET['leixing_bet']."' and class2 = '正码1'")->select();
 // p($data_bet);
 ?>
<form  name="lt_form"  method="post" action="main_left.php?title_3d=正1-6&fc_type=9&ids=正1-6&class2=正1-6&action=n1" target="k_meml" onsubmit="return ChkSubmit();">

 <!--<input type="hidden" name="fc_type" value="9" id="fc_type">
 <input type="hidden" name="ids" value="正1-6" id="ids">
 <input type="hidden" name="action" value="n1" id="action">
 <input type="hidden" name="class2" value="正1-6" id="class2">-->
 <input type="hidden" name="title_3d" value="正1-6" id="title_3d">
 <input type="hidden" name="fc_type" value="9" id="fc_type">
 <input type="hidden" name="ids" value="正1-6" id="ids">

<table  border="0" style="width:780px;" cellspacing="0" cellpadding="0" class="all_body">
   <tr class="tbtitle2">
    <th colspan="13"><div  style="float:left;line-height:30px;text-align:center;width:778px;border:1px solid #bbafaf"><?=$_GET['leixing'] ?></div>
	</th>
  </tr>
  <tr>
    <td>
  <table width="40" border="0" cellpadding="0" cellspacing="0" class="game_table all_body">

    <tr class="hset"><td width="40" class="tbtitle"><?=$_GET['leixing'] ?></td></tr>
    <tr class="tbtitle2">
        <td>类型</td>
      </tr>
      <tr class="Ball_tr_H">
        <td class="ball_bg">大</td>
      </tr>
      <tr class="Ball_tr_H">
        <td class="ball_bg">小</td>
      </tr>
      <tr class="Ball_tr_H">
        <td class="ball_bg">单</td>
      </tr>
      <tr class="Ball_tr_H">
        <td class="ball_bg">双</td>
      </tr>
     <tr class="Ball_tr_H">
        <td class="ball_bg">红波</td>
      </tr>
      <tr class="Ball_tr_H">
        <td class="ball_bg">绿波</td>
      </tr>
      <tr class="Ball_tr_H">
        <td class="ball_bg">蓝波</td>
      </tr>
<!--      <tr class="Ball_tr_H">
        <td class="ball_bg">合大</td>
      </tr>
      <tr class="Ball_tr_H">
        <td class="ball_bg">合小</td>
      </tr>
     <tr class="Ball_tr_H">
        <td class="ball_bg">合单</td>
      </tr>
      <tr class="Ball_tr_H">
        <td class="ball_bg">合双</td>
      </tr>
     <tr class="Ball_tr_H">
        <td class="ball_bg">尾大</td>
      </tr>
      <tr class="Ball_tr_H">
        <td class="ball_bg">尾小</td>
      </tr> -->
    </table>
    </td>
    <td>
<table width="0" border="0" cellpadding="0" cellspacing="0" class="game_table all_body">
      <tr class="hset"><td colspan="2" class="tbtitle">正码一</td></tr>
      <tr class="tbtitle2">
        <td width="48">赔率</td>
        <td width="62">金额</td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_12_231" class="ball_ff"><span rate="true2" id="bl00"><?=$data_bet['0']['rate'] ?></span><!-- <input name="class2_1" value="正码1" type="hidden" > --></td>
        <td ID="jeu_m_12_231" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','ds','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_1" />
<input name="class3_1" value="大" type="hidden" ></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_12_237" class="ball_ff"><span rate="true2" id="bl1"><?=$data_bet['1']['rate'] ?></span></td>
        <td ID="jeu_m_12_237" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','ds','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_2" />
<input name="class3_2" value="小" type="hidden" ><!-- <input name="class2_2" value="正码1" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_13_243"class="ball_ff" border="1"><span rate="true2" id="bl2"><?=$data_bet['2']['rate'] ?></span></td>
        <td ID="jeu_m_13_243" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','dx','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_3" />
<input name="class3_3" value="单" type="hidden" ><!-- <input name="class2_3" value="正码1" type="hidden" > --></td>
      </tr>
     <tr class="Ball_tr_H">
        <td ID="jeu_p_13_249" class="ball_ff"><span rate="true2" id="bl3"><?=$data_bet['3']['rate'] ?></span></td>
        <td ID="jeu_m_13_249" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','dx','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_4" />
<input name="class3_4" value="双" type="hidden" ><!-- <input name="class2_4" value="正码1" type="hidden" > --></td>
      </tr>
     <tr class="Ball_tr_H">
        <td ID="jeu_p_14_255" class="ball_ff"><span rate="true2" id=bl4><?=$data_bet['4']['rate'] ?></span></td>
        <td ID="jeu_m_14_255" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','bs','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_5" />
<input name="class3_5" value="红波" type="hidden" ><!-- <input name="class2_5" value="正码1" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_14_267"class="ball_ff"><span rate="true2" id=bl5><?=$data_bet['5']['rate'] ?></span></td>
        <td ID="jeu_m_14_267" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','bs','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_6" />
<input name="class3_6" value="绿波" type="hidden" ><!-- <input name="class2_6" value="正码1" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_14_261" class="ball_ff"><span rate="true2" id=bl6><?=$data_bet['6']['rate'] ?></span></td>
        <td ID="jeu_m_14_261" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','bs','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_7" />
<input name="class3_7" value="蓝波" type="hidden" ><!-- <input name="class2_7" value="正码1" type="hidden" > --></td>
      </tr>

 <!--     <tr class="Ball_tr_H">
        <td ID="jeu_p_15_273"class="ball_ff"><span rate="true2" id=bl7><?=$data_bet['7']['rate'] ?></span></td>
        <td ID="jeu_m_15_273" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_8" />
<input name="class3_8" value="合大" type="hidden" > <input name="class2_8" value="正码1" type="hidden" ></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_15_279"class="ball_ff"><span rate="true2" id=bl8><?=$data_bet['8']['rate'] ?></span></td>
        <td ID="jeu_m_15_279" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_9" />
<input name="class3_9" value="合小" type="hidden" > <input name="class2_9" value="正码1" type="hidden" ></td>
      </tr>
     <tr class="Ball_tr_H">
        <td ID="jeu_p_15_273"class="ball_ff"><span rate="true2" id=bl9><?=$data_bet['9']['rate'] ?></span></td>
        <td ID="jeu_m_15_273" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_10" />
<input name="class3_10" value="合单" type="hidden" > <input name="class2_10" value="正码1" type="hidden" > </td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_15_279"class="ball_ff"><span rate="true2" id=bl10><?=$data_bet['10']['rate'] ?></span></td>
        <td ID="jeu_m_15_279" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_11" />
<input name="class3_11" value="合双" type="hidden" > <input name="class2_11" value="正码1" type="hidden" > </td>
      </tr>
     <tr class="Ball_tr_H">
        <td ID="jeu_p_15_273"class="ball_ff"><span rate="true2" id=bl11><?=$data_bet['11']['rate'] ?></span></td>
        <td ID="jeu_m_15_273" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_12" />
<input name="class3_12" value="尾大" type="hidden" > <input name="class2_12" value="正码1" type="hidden" > </td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_15_279"class="ball_ff"><span rate="true2" id=bl12><?=$data_bet['12']['rate'] ?></span></td>
        <td ID="jeu_m_15_279" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_13" />
<input name="class3_13" value="尾小" type="hidden" > <input name="class2_13" value="正码1" type="hidden" > </td>
      </tr>     -->
    </table>
    <td>
    <?php

$data_bet2 = $dat->field("rate,id,class2,class3")->where("class1 = '".$_GET['leixing_bet']."' and class2 = '正码2'")->select();
 // p($data_bet2);
 ?>
	<table width="0" border="0" cellpadding="0" cellspacing="0" class="game_table all_body">
      <tr class="hset">
        <td colspan="2" class="tbtitle">正码二</td>
        </tr>
      <tr class="tbtitle2">
        <td width="48">赔率</td>
        <td width="62">金额</td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_12_232"class="ball_ff"><span rate="true2" id=bl13><?=$data_bet2['0']['rate'] ?></span></td>
        <td ID="jeu_m_12_232" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','ds','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_14" />
<input name="class3_14" value="大" type="hidden" ><!-- <input name="class2_14" value="正码2" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_12_238"class="ball_ff"><span rate="true2" id=bl14><?=$data_bet2['1']['rate'] ?></span></td>
        <td ID="jeu_m_12_238" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','ds','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_15" />
<input name="class3_15" value="小" type="hidden" ><!-- <input name="class2_15" value="正码2" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_13_244"class="ball_ff"><span rate="true2" id=bl15><?=$data_bet2['2']['rate'] ?></span></td>
        <td ID="jeu_m_13_244" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','dx','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_16" />
<input name="class3_16" value="单" type="hidden" ><!-- <input name="class2_16" value="正码2" type="hidden" > --></td>
      </tr>
     <tr class="Ball_tr_H">
        <td ID="jeu_p_13_250"class="ball_ff"><span rate="true2" id=bl16><?=$data_bet2['3']['rate'] ?></span></td>
        <td ID="jeu_m_13_250" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','dx','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_17" />
<input name="class3_17" value="双" type="hidden" ><!-- <input name="class2_17" value="正码2" type="hidden" > --></td>
      </tr>
     <tr class="Ball_tr_H">
        <td ID="jeu_p_14_256"class="ball_ff"><span rate="true2" id=bl17><?=$data_bet2['4']['rate'] ?></span></td>
        <td ID="jeu_m_14_256" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','bs','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_18" />
<input name="class3_18" value="红波" type="hidden" ><!-- <input name="class2_18" value="正码2" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_14_268"class="ball_ff"><span rate="true2" id=bl18><?=$data_bet2['5']['rate'] ?></span></td>
        <td ID="jeu_m_14_268" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','bs','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_19" />
<input name="class3_19" value="绿波" type="hidden" ><!-- <input name="class2_19" value="正码2" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_14_262" class="ball_ff"><span rate="true2" id=bl19><?=$data_bet2['6']['rate'] ?></span></td>
        <td ID="jeu_m_14_262" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','bs','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_20" />
<input name="class3_20" value="蓝波" type="hidden" ><!-- <input name="class2_20" value="正码2" type="hidden" > --></td>
      </tr>
<!--      <tr class="Ball_tr_H">
        <td ID="jeu_p_15_274"class="ball_ff"><span rate="true2" id=bl20><?=$data_bet2['7']['rate'] ?></span></td>
        <td ID="jeu_m_15_274" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_21" />
<input name="class3_21" value="合大" type="hidden" ><input name="class2_21" value="正码2" type="hidden" ></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_15_280"class="ball_ff"><span rate="true2" id=bl21><?=$data_bet2['8']['rate'] ?></span></td>
        <td ID="jeu_m_15_280" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_22" />
<input name="class3_22" value="合小" type="hidden" ><input name="class2_22" value="正码2" type="hidden" ></td>
      </tr>
     <tr class="Ball_tr_H">
        <td ID="jeu_p_15_274"class="ball_ff"><span rate="true2" id=bl22><?=$data_bet2['9']['rate'] ?></span></td>
        <td ID="jeu_m_15_274" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_23" />
<input name="class3_23" value="合单" type="hidden" ><input name="class2_23" value="正码2" type="hidden" ></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_15_280"class="ball_ff"><span rate="true2" id=bl23><?=$data_bet2['10']['rate'] ?></span></td>
        <td ID="jeu_m_15_280" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_24" />
<input name="class3_24" value="合双" type="hidden" ><input name="class2_24" value="正码2" type="hidden" ></td>
      </tr>
     <tr class="Ball_tr_H">
        <td ID="jeu_p_15_274"class="ball_ff"><span rate="true2" id=bl24><?=$data_bet2['11']['rate'] ?></span></td>
        <td ID="jeu_m_15_274" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_25" />
<input name="class3_25" value="尾大" type="hidden" ><input name="class2_25" value="正码2" type="hidden" ></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_15_280"class="ball_ff"><span rate="true2" id=bl25><?=$data_bet2['12']['rate'] ?></span></td>
        <td ID="jeu_m_15_280" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_26" />
<input name="class3_26" value="尾小" type="hidden" ><input name="class2_26" value="正码2" type="hidden" ></td>
      </tr>     -->
    </table>
	</td>
    <td>
    <?php

$data_bet3 = $dat->field("rate,id,class2,class3")->where("class1 = '".$_GET['leixing_bet']."' and class2 = '正码3'")->select();
 // p($data_bet3);
     ?>
	<table width="0" border="0" cellpadding="0" cellspacing="0" class="game_table all_body">
      <tr class="hset">
        <td colspan="2" class="tbtitle">正码三</td>
        </tr>
      <tr class="tbtitle2">
        <td width="48">赔率</td>
        <td width="62">金额</td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_12_233"class="ball_ff"><span rate="true2" id=bl26><?=$data_bet3['0']['rate'] ?></span></td>
        <td ID="jeu_m_12_233" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','ds','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_27" />
<input name="class3_27" value="大" type="hidden" ><!-- <input name="class2_27" value="正码3" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_12_239"class="ball_ff"><span rate="true2" id=bl27><?=$data_bet3['1']['rate'] ?></span></td>
        <td ID="jeu_m_12_239" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','ds','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_28" />
<input name="class3_28" value="小" type="hidden" ><!-- <input name="class2_28" value="正码3" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_13_245"class="ball_ff"><span rate="true2" id=bl28><?=$data_bet3['2']['rate'] ?></span></td>
        <td ID="jeu_m_13_245" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','dx','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_29" />
<input name="class3_29" value="单" type="hidden" ><!-- <input name="class2_29" value="正码3" type="hidden" > --></td>
      </tr>
     <tr class="Ball_tr_H">
        <td ID="jeu_p_13_251"class="ball_ff"><span rate="true2" id=bl29><?=$data_bet3['3']['rate'] ?></span></td>
        <td ID="jeu_m_13_251" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','dx','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_30" />
<input name="class3_30" value="双" type="hidden" ><!-- <input name="class2_30" value="正码3" type="hidden" > --></td>
      </tr>

     <tr class="Ball_tr_H">
        <td ID="jeu_p_14_257"class="ball_ff"><span rate="true2" id=bl30><?=$data_bet3['4']['rate'] ?></span></td>
        <td ID="jeu_m_14_257" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','bs','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_31" />
<input name="class3_31" value="红波" type="hidden" ><!-- <input name="class2_31" value="正码3" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_14_269"class="ball_ff"><span rate="true2" id=bl31><?=$data_bet3['5']['rate'] ?></span></td>
        <td ID="jeu_m_14_269" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','bs','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_32" />
<input name="class3_32" value="绿波" type="hidden" ><!-- <input name="class2_32" value="正码3" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_14_263"class="ball_ff"><span rate="true2" id=bl32><?=$data_bet3['6']['rate'] ?></span></td>
        <td ID="jeu_m_14_263" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','bs','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_33" />
<input name="class3_33" value="蓝波" type="hidden" ><!-- <input name="class2_33" value="正码3" type="hidden" > --></td>
      </tr>
 <!--     <tr class="Ball_tr_H">
        <td ID="jeu_p_15_275"class="ball_ff"><span rate="true2" id=bl33><?=$data_bet3['7']['rate'] ?></span></td>
        <td ID="jeu_m_15_275" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_34" />
<input name="class3_34" value="合大" type="hidden" ><input name="class2_34" value="正码3" type="hidden" ></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_15_281"class="ball_ff"><span rate="true2" id=bl34><?=$data_bet3['8']['rate'] ?></span></td>
        <td ID="jeu_m_15_281" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_35" />
<input name="class3_35" value="合小" type="hidden" ><input name="class2_35" value="正码3" type="hidden" ></td>
      </tr>
     <tr class="Ball_tr_H">
        <td ID="jeu_p_15_275"class="ball_ff"><span rate="true2" id=bl35><?=$data_bet3['9']['rate'] ?></span></td>
        <td ID="jeu_m_15_275" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_36" />
<input name="class3_36" value="合单" type="hidden" ><input name="class2_36" value="正码3" type="hidden" ></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_15_281"class="ball_ff"><span rate="true2" id=bl36><?=$data_bet3['10']['rate'] ?></span></td>
        <td ID="jeu_m_15_281" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_37" />
<input name="class3_37" value="合双" type="hidden" ><input name="class2_37" value="正码3" type="hidden" ></td>
      </tr>
     <tr class="Ball_tr_H">
        <td ID="jeu_p_15_275"class="ball_ff"><span rate="true2" id=bl37><?=$data_bet3['11']['rate'] ?></span></td>
        <td ID="jeu_m_15_275" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_38" />
<input name="class3_38" value="尾大" type="hidden" ><input name="class2_38" value="正码3" type="hidden" ></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_15_281"class="ball_ff"><span rate="true2" id=bl38><?=$data_bet3['12']['rate'] ?></span></td>
        <td ID="jeu_m_15_281" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_39" />
<input name="class3_39" value="尾小" type="hidden" ><input name="class2_39" value="正码3" type="hidden" ></td>
      </tr>  -->
    </table>
	</td>
    <td>
    <?php

$data_bet4 = $dat->field("rate,id,class2,class3")->where("class1 = '".$_GET['leixing_bet']."' and class2 = '正码4'")->select();
 // p($data_bet4);
 ?>
	<table width="0" border="0" cellpadding="0" cellspacing="0" class="game_table all_body">
      <tr class="hset">
        <td colspan="2" class="tbtitle">正码四</td>
        </tr>
      <tr class="tbtitle2">
        <td width="48" >赔率</td>
        <td width="62" >金额</td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_12_234"class="ball_ff"><span rate="true2" id=bl39><?=$data_bet4['0']['rate'] ?></span></td>
        <td ID="jeu_m_12_234" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','ds','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_40" />
<input name="class3_40" value="大" type="hidden" ><!-- <input name="class2_40" value="正码4" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_12_240"class="ball_ff"><span rate="true2" id=bl40><?=$data_bet4['1']['rate'] ?></span></td>
        <td ID="jeu_m_12_240" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','ds','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_41" />
<input name="class3_41" value="小" type="hidden" ><!-- <input name="class2_41" value="正码4" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_13_246"class="ball_ff"><span rate="true2" id=bl41><?=$data_bet4['2']['rate'] ?></span></td>
        <td ID="jeu_m_13_246" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','dx','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_42" />
<input name="class3_42" value="单" type="hidden" ><!-- <input name="class2_42" value="正码4" type="hidden" > --></td>
      </tr>
     <tr class="Ball_tr_H">
        <td ID="jeu_p_13_252"class="ball_ff"><span rate="true2" id=bl42><?=$data_bet4['3']['rate'] ?></span></td>
        <td ID="jeu_m_13_252" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','dx','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_43" />
<input name="class3_43" value="双" type="hidden" ><!-- <input name="class2_43" value="正码4" type="hidden" > --></td>
      </tr>

     <tr class="Ball_tr_H">
        <td ID="jeu_p_14_258"class="ball_ff"><span rate="true2" id=bl43><?=$data_bet4['4']['rate'] ?></span></td>
        <td ID="jeu_m_14_258" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','bs','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_44" />
<input name="class3_44" value="红波" type="hidden" ><!-- <input name="class2_44" value="正码4" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_14_270"class="ball_ff"><span rate="true2" id=bl44><?=$data_bet4['5']['rate'] ?></span></td>
        <td ID="jeu_m_14_270" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','bs','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_45" />
<input name="class3_45" value="绿波" type="hidden" ><!-- <input name="class2_45" value="正码4" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_14_264"class="ball_ff"><span rate="true2" id=bl45><?=$data_bet4['6']['rate'] ?></span></td>
        <td ID="jeu_m_14_264" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','bs','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_46" />
<input name="class3_46" value="蓝波" type="hidden" ><!-- <input name="class2_46" value="正码4" type="hidden" > --></td>
      </tr>

    </table>
	</td>
    <td>
    <?php

$data_bet5 = $dat->field("rate,id,class2,class3")->where("class1 = '".$_GET['leixing_bet']."' and class2 = '正码5'")->select();
 // p($data_bet5);
 ?>
	<table width="0" border="0" cellpadding="0" cellspacing="0" class="game_table all_body">
      <tr class="hset">
        <td colspan="2" class="tbtitle">正码五</td>
        </tr>
      <tr class="tbtitle2">
        <td width="48" >赔率</td>
        <td width="62" >金额</td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_12_235"class="ball_ff"><span rate="true2" id=bl52><?=$data_bet5['0']['rate'] ?></span></td>
        <td ID="jeu_m_12_235" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','ds','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_53" />
<input name="class3_53" value="大" type="hidden" ><!-- <input name="class2_53" value="正码5" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_12_241"class="ball_ff"><span rate="true2" id=bl53><?=$data_bet5['1']['rate'] ?></span></td>
        <td ID="jeu_m_12_241" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','ds','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_54" />
<input name="class3_54" value="小" type="hidden" ><!-- <input name="class2_54" value="正码5" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_13_247"class="ball_ff"><span rate="true2" id=bl54><?=$data_bet5['2']['rate'] ?></span></td>
        <td ID="jeu_m_13_247" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','dx','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_55" />
<input name="class3_55" value="单" type="hidden" ><!-- <input name="class2_55" value="正码5" type="hidden" > --></td>
      </tr>
     <tr class="Ball_tr_H">
        <td ID="jeu_p_13_253"class="ball_ff"><span rate="true2" id=bl55><?=$data_bet5['3']['rate'] ?></span></td>
        <td ID="jeu_m_13_253" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','dx','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_56" />
<input name="class3_56" value="双" type="hidden" ><!-- <input name="class2_56" value="正码5" type="hidden" > --></td>
      </tr>

     <tr class="Ball_tr_H">
        <td ID="jeu_p_14_259"class="ball_ff"><span rate="true2" id=bl56><?=$data_bet5['4']['rate'] ?></span></td>
        <td ID="jeu_m_14_259" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','bs','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_57" />
<input name="class3_57" value="红波" type="hidden" ><!-- <input name="class2_57" value="正码5" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_14_271"class="ball_ff"><span rate="true2" id=bl57><?=$data_bet5['5']['rate'] ?></span></td>
        <td ID="jeu_m_14_271" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','bs','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_58" />
<input name="class3_58" value="绿波" type="hidden" ><!-- <input name="class2_58" value="正码5" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_14_265"class="ball_ff"><span rate="true2" id=bl58><?=$data_bet5['6']['rate'] ?></span></td>
        <td ID="jeu_m_14_265" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','bs','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_59" />
<input name="class3_59" value="蓝波" type="hidden" ><!-- <input name="class2_59" value="正码5" type="hidden" > --></td>
      </tr>


    </table>
	</td>
    <td>
    <?php

$data_bet6 = $dat->field("rate,id,class2,class3")->where("class1 = '".$_GET['leixing_bet']."' and class2 = '正码6'")->select();
 // p($data_bet6);
 ?>
	<table width="0" border="0" cellpadding="0" cellspacing="0" class="game_table all_body">
      <tr class="hset">
        <td colspan="2" class="tbtitle">正码六</td>
        </tr>
      <tr class="tbtitle2">
        <td width="48" >赔率</td>
        <td width="62" >金额</td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_12_236"class="ball_ff"><span rate="true2" id=bl65><?=$data_bet6['0']['rate'] ?></span></td>
        <td ID="jeu_m_12_236" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','ds','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_66" />
<input name="class3_66" value="大" type="hidden" ><!-- <input name="class2_66" value="正码6" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_12_242"class="ball_ff"><span rate="true2" id=bl66><?=$data_bet6['1']['rate'] ?></span></td>
        <td ID="jeu_m_12_242" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','ds','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_67" />
<input name="class3_67" value="小" type="hidden" ><!-- <input name="class2_67" value="正码6" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_13_248"class="ball_ff"><span rate="true2" id=bl67><?=$data_bet6['2']['rate'] ?></span></td>
        <td ID="jeu_m_13_248" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','dx','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_68" />
<input name="class3_68" value="单" type="hidden" ><!-- <input name="class2_68" value="正码6" type="hidden" > --></td>
      </tr>
     <tr class="Ball_tr_H">
        <td ID="jeu_p_13_254"class="ball_ff"><span rate="true2" id=bl68><?=$data_bet6['3']['rate'] ?></span></td>
        <td ID="jeu_m_13_254" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','dx','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_69" />
<input name="class3_69" value="双" type="hidden" ><!-- <input name="class2_69" value="正码6" type="hidden" > --></td>
      </tr>

     <tr class="Ball_tr_H">
        <td ID="jeu_p_14_260"class="ball_ff"><span rate="true2" id=bl69><?=$data_bet6['4']['rate'] ?></span></td>
        <td ID="jeu_m_14_260" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','bs','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_70" />
<input name="class3_70" value="红波" type="hidden" ><!-- <input name="class2_70" value="正码6" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_14_272"class="ball_ff"><span rate="true2" id=bl70><?=$data_bet6['5']['rate'] ?></span></td>
        <td ID="jeu_m_14_272" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','bs','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_71" />
<input name="class3_71" value="绿波" type="hidden" ><!-- <input name="class2_71" value="正码6" type="hidden" > --></td>
      </tr>
      <tr class="Ball_tr_H">
        <td ID="jeu_p_14_266"class="ball_ff"><span rate="true2" id=bl71><?=$data_bet6['6']['rate'] ?></span></td>
        <td ID="jeu_m_14_266" bgcolor="#F7F0FB" class="ball_ff" width="72"><input
onKeyPress="return CheckKey();"
onBlur="this.className='inp1';return CountGold(this,'blur','bs','');"
onKeyUp="return CountGold(this,'keyup');"
onFocus="this.className='inp1m';CountGold(this,'focus');;"
style="HEIGHT: 18px"  class="" maxlength="6" type='text' js='js' size="4" name="Num_72" />
<input name="class3_72" value="蓝波" type="hidden" ><!-- <input name="class2_72" value="正码6" type="hidden" > --></td>
      </tr>


    </table>
	</td>
  </tr>
</table>
<table border="0" class="game_table all_body" style="width:780px;" cellpadding="0" cellspacing="0" >
    <tr class="hset2">
    <!-- onclick="return ChkSubmit();" -->
        <td id="M_ConfirmClew" align="center" class="tbtitle">
        <input name="btnSubmit"   type="submit"  class="button_a" id="btnSubmit" value="投注"  />&nbsp;
        &nbsp;<input class='button_a' name='reset' onClick="javascript:document.all.allgold.innerHTML =0;" type='reset' value='重 设' /></td>
    </tr>
</table>

	<INPUT type="hidden"  value=0 name=gold_all>
  </form>
  <INPUT  type="hidden" value=0 name=total_gold id="total_gold">



<script>
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_changeProp(objName,x,theProp,theValue) { //v6.0
  var obj = MM_findObj(objName);
  if (obj && (theProp.indexOf("style.")==-1 || obj.style)){
    if (theValue == true || theValue == false)
      eval("obj."+theProp+"="+theValue);
    else eval("obj."+theProp+"='"+theValue+"'");
  }
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}

function makeRequest(url) {

    http_request = false;

    if (window.XMLHttpRequest) {

        http_request = new XMLHttpRequest();

        if (http_request.overrideMimeType){

            http_request.overrideMimeType('text/xml');

        }

    } else if (window.ActiveXObject) {

        try{

            http_request = new ActiveXObject("Msxml2.XMLHTTP");

        } catch (e) {

            try {

                http_request = new ActiveXObject("Microsoft.XMLHTTP");

            } catch (e) {

            }

        }

     }
     if (!http_request) {

        alert("Your browser nonsupport operates at present, please use IE 5.0 above editions!");

        return false;

     }


//method init,no init();
 http_request.onreadystatechange = init;

 http_request.open('GET', url, true);

//Forbid IE to buffer memory
 // http_request.setRequestHeader("If-Modified-Since","0");

//send count
 http_request.send(null);

//Updated every two seconds a page
 setTimeout("makeRequest('"+url+"')", <?=$ftime?>);

}


function init() {

    if (http_request.readyState == 4) {

        if (http_request.status == 0 || http_request.status == 200) {

            var result = http_request.responseText;
			//alert(result);

            if(result==""){

                result = "Access failure ";

            }

		   var arrResult = result.split("###");
		   for(var i=0;i<78;i++)
{
		   arrTmp = arrResult[i].split("@@@");



num1 = arrTmp[0]; //字段num1的值
num2 = parseFloat(arrTmp[1]).toFixed(2); //字段num2的值
num3 = parseFloat(arrTmp[2]).toFixed(2); //字段num1的值
num4 = arrTmp[3]; //字段num2的值
num5 = arrTmp[4]; //字段num2的值
num6 = arrTmp[5]; //字段num2的值


//if (i<49){
//document.all["xr_"+i].value = num4;
var sb=i+1
//document.all["xrr_"+sb].value = num5;
//}

if (num6==1){
MM_changeProp('num_'+sb,'','disabled','1','INPUT/text')}

var bl;
bl="bl"+i;
if (num6==1){
document.all[bl].innerHTML= "停";
}else{

}



function sendCommand(commandName,pageURL,strPara)
{
	//功能：向pageURL页面发送数据，参数为strPara
	//并回传服务器返回的数据
	var oBao = new ActiveXObject("Microsoft.XMLHTTP");
	//特殊字符：+,%,&,=,?等的传输解决办法.字符串先用escape编码的.
	oBao.open("GET",pageURL+"?commandName="+commandName+"&"+strPara,false);
	oBao.send();
	//服务器端处理返回的是经过escape编码的字符串.
	var strResult = unescape(oBao.responseText);
	return strResult;
}
function beginrefresh(){
	 makeRequest('liuhecai.php?action=server&class1=正码1-6&class2=<?=$ids?>');
}

</script>

<SCRIPT language=javascript>
 makeRequest('liuhecai.php?action=server&class1=正码1-6')
 </script>