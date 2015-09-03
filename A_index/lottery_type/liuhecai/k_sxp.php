<? 
// if(!defined('PHPYOU')) {
// 	exit('非法进入');
// }

$_GET['leixing']='一肖/尾数';
$_GET['leixing_bet']='生肖';


$ids="一肖";

$xc=22;

$XF=23;
function ka_kk1($i){   

   }

function ka_kk2($i){ 
//    global $mysqli;  
//    $result=$mysqli->query("select sum(sum_m) as sum_mm from c_odds_7 where kithe='".$Current_Kithe_Num."' and  username='".$_SESSION['kauser']."' and class1='正特尾数' and class2='正特尾数' and class3='".$i."' order by id desc"); 
// $ka_guanuserkk1=$result->fetch_array();
// return $ka_guanuserkk1[0];
   }



?>
<?
$result=$mysqli->query("Select class3,rate from c_odds_7 where class2='".$ids."' order by ID");
$drop_table = array();
$y=0;
while($image = $result->fetch_array()){
$y++;
//echo $image['class3'];
array_push($drop_table,$image);

}



?>

﻿ ﻿ 



<SCRIPT language=JAVASCRIPT>
if(self == top) {location = '/';} 
if(window.location.host!=top.location.host){top.location=window.location;} 
</SCRIPT>



 <SCRIPT language=JAVASCRIPT>
<!--
var count_win=false;
//window.setTimeout("self.location='quickinput2.php'", 178000);
function CheckKey(){
	// if(event.keyCode == 13) return true;
	// if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode > 95 || event.keyCode < 106)){alert("下注金额仅能输入数字!!"); return false;}
}

function ChkSubmit(){
    //设定『确定』键为反白 
//	document.all.btnSubmit.disabled = true;

	if (eval(document.all.allgold.innerHTML)<=0 )
	{
		alert("请输入下注金额!!");
	    document.all.btnSubmit.disabled = false;
		return false;

	}else{
     
        document.lt_form.submit();
         self.location.reload();
  }

   
}

function ChkSubmit_z(){
    //设定『确定』键为反白 
	document.all.btnSubmit_z.disabled = true;

	if (eval(document.all.allgold.innerHTML)<=0 )
	{
		alert("请输入下注金额!!");
	    document.all.btnSubmit_z.disabled = false;
		return false;

	}else{

    document.all.gold_all.value=eval(document.all.allgold_z.innerHTML);
        document.lt_form_z.submit();
         self.location.reload();
  }

   
    
}



function CountGold(gold,type,rtype,bb,ffb){

}

function CountGold_z(gold,type,rtype,bb,ffb){
 
}
//-->
</SCRIPT>


 <style type="text/css">
<!--
body {
/*	margin-left: 10px;
	margin-top: 10px;*/
}
.STYLE1 {color: #333}
.STYLE4 {color: #333333; font-weight: bold; }
-->
body {background-color:#f1f1f1}
 </style>
<noscript>
<iframe scr=″*.htm″></iframe>
</noscript>

<TABLE  border="0" cellpadding="2" cellspacing="1" bordercolordark="#f9f9f9" bgcolor="#CCCCCC" width=780 >
<!--   <TBODY>
  <TR class="tbtitle">
    <TD ><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr> <td height=25><span rate="true3" id=Lottery_Type_Name>当前期数: </SPAN>【第<?=$Current_Kithe_Num?>期】 <span rate="true3" id=allgold style="display:none"><?=$data_bet['1']['rate'] ?></span></TD> -->
   <!--  <TD align=right colSpan=3>
    </TD></TR>
  <TR vAlign=bottom class="tbtitle">
    <TD width="25%" height=17><B class=font_B>生肖尾数</B></TD>
    <TD align=middle width="25%">开奖时间：<?=date("H:i:s",strtotime($Current_KitheTable['nd'])) ?></TD>
    <TD align=middle width="35%">距离封盘时间：
    
      <span rate="true3" id="span_dt_dt"></span>
      <SCRIPT language=javascript> 
	  tnowtoday=new Date();
      function show_student163_time(){ 
      window.setTimeout("show_student163_time()", 1000); 
      BirthDay=new Date("<?=date("m-d-Y H:i:s",strtotime($Current_KitheTable[12]))?>");
	  //BirthDay=new Date("09-09-2013 12:12:12");
      today=new Date("<?=date('m-d-Y H:i:s',time())?>");
	  nowtoday=new Date(); 
	  seep=(tnowtoday.getTime()-today.getTime()); 
      timeold=(BirthDay.getTime()-nowtoday.getTime()+seep); 
      sectimeold=timeold/1000 
      secondsold=Math.floor(sectimeold); 
      msPerDay=24*60*60*1000 
      e_daysold=timeold/msPerDay 
      daysold=Math.floor(e_daysold); 
      e_hrsold=(e_daysold-daysold)*24; 
      hrsold=Math.floor(e_hrsold); 
      e_minsold=(e_hrsold-hrsold)*60; 
      minsold=Math.floor((e_hrsold-hrsold)*60); 
      seconds=Math.floor((e_minsold-minsold)*60); 
      if(daysold<0) 
      { 
      daysold=0; 
      hrsold=0; 
      minsold=0; 
      seconds=0; 
      } 
      if (daysold>0){
      span_dt_dt.innerHTML=daysold+"天"+hrsold+":"+minsold+":"+seconds ; 
      }else if(hrsold>0){
      span_dt_dt.innerHTML=hrsold+":"+minsold+":"+seconds ; 
      }else if(minsold>0){
      span_dt_dt.innerHTML=minsold+":"+seconds ;  
      }else{
      span_dt_dt.innerHTML=seconds+"秒" ; 
      
      }
      // if (daysold<=0 && hrsold<=0  && minsold<=0 && seconds<=0)
      // window.setTimeout("self.location='liuhecai.php?action=kq'", 1);
      // } 
      // show_student163_time(); 
      </SCRIPT>
    </TD>
    <TD align=right width="25%"><span rate="true3" class=Font_B 
      id=Update_Time></SPAN></TD></TR></TBODY></TABLE></td>
  </tr>
      </table> -->

  <?php include("common_qishu.php"); ?>
  
<form  name="lt_form"  method="get" action="main_left.php" target="k_meml" style="height:300px;" >
<input type="hidden" name="title_3d" value="生肖" id="title_3d">
<input type="hidden" name="leixing" value="一肖/尾数" id="leixing">
 <input type="hidden" name="fc_type" value="9" id="fc_type">
 <input type="hidden" name="ids" value="<?=$ids ?>" id="ids">
 <input type="hidden" name="action" value="n1" id="action"> 
 <input type="hidden" name="class2" value="<?=$ids?>" id="class2">

<TABLE cellSpacing='1' cellPadding='0' style="width:780px" border=0 class="game_table all_body" style="display:none;" >
  <tr class="tbtitle2">
    <th colspan="8"><div  style="float:left;line-height:30px;text-align:center;width:778px;"><?=$_GET['leixing'] ?></div>
    </th>
  </tr>

     <tr class="tbtitle2">
		<th>号码</th>
		<th>赔率</th>
		<th>金额</th>
		<th>号码</th>
		<th>号码</th>
		<th>赔率</th>
		<th>金额</th>
		<th>号码</th>
    </tr>
    <?

for ($I=0; $I<=5; $I=$I+1)
{

	
	?>
	<tr  class="Ball_tr_H">
      <td width="41" height="35" align="center"   class="ball_bg"><span rate="true3" class="STYLE4"><?=$drop_table[$I][0]?></span></td>
      <td width="50" height="25" align="center" valign="middle" class="ball_ff"><b>  <span rate="true3" id="bl<? 
        if($I==0){
          echo '00';
        }else{
          echo $I;
        }
        
      ?>  ">  <?=$data_bet[$I]['rate'] ?></span><span rate="true3"> </span></b></td>
      <td width="55" height="25" align="center" class="ball_ff" bgcolor="#FFFFFF"><input onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1($drop_table[$I][0])?>','1');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="4" type='text' js='js' name="Num_<?=$I+1?>" id="Num_<?=$I+1?>"/>
          <input name="class3_<?=$I+1?>" value="<?=$drop_table[$I][0]?>" type="hidden" >
          <input name="gb<?=$I+1?>" type="hidden"  value="0">
          <input name="xr_<?=$I?>" type="hidden" id="xr_<?=$I?>" value="0" >
          <input name="xrr_<?=$I+1?>" type="hidden"  value="0" ></td>
      <td height="25" bgcolor="f1f1f1"  ><table align=center><tr>
						<?
						
						
$result=$mysqli->query("Select m_number from ka_sxnumber where sx='".$drop_table[$I][0]."' order by id");
$image = $result->fetch_array();
						
		$xxm=explode(",",$image['m_number']);	
		$ssc=count($xxm);
		for ($j=0; $j<$ssc; $j=$j+1){			
				
					
					?>
					
    						<td align=middle   height="32" width="32" class="ball_<?=Get_bs_Color(intval($xxm[$j]))?>"><?=$xxm[$j]?></td>
    					<? } ?>
	</tr></table>	</td>
	
	
	
	
	<td width="41" height="35" align="center"  class="ball_bg"><span rate="true3" class="STYLE4"><?=$drop_table[$I+6][0]?></span></td>
      <td width="50" height="25" align="center" valign="middle" class="ball_ff"><b><span rate="true3" id="bl<? 
        if($I==0){
          echo '00';
        }else{
          echo $I;
        }
        
      ?>  ">  <?=$data_bet[$I+6]['rate'] ?> </span><span rate="true3"> </span></b></td>
      <td width="55" height="25" align="center" class="ball_ff" bgcolor="#FFFFFF"><input onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1($drop_table[$I+6][0])?>','1');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="4" type='text' js='js' name="Num_<?=$I+1+6?>" id="Num_<?=$I+1+6?>" />
          <input name="class3_<?=$I+1+6?>" value="<?=$drop_table[$I+6][0]?>" type="hidden" >
          <input name="gb<?=$I+1+6?>" type="hidden"  value="0">
          <input name="xr_<?=$I+6?>" type="hidden" id="xr_<?=$I+6?>" value="0" >
          <input name="xrr_<?=$I+1+6?>" type="hidden"  value="0" ></td>
      <td height="25" bgcolor="f1f1f1"  ><table align=center><tr>
						<?
						
						
$result=$mysqli->query("Select m_number from ka_sxnumber where sx='".$drop_table[$I+6][0]."' order by id");
$image = $result->fetch_array();
						
		$xxm=explode(",",$image['m_number']);	
		$ssc=count($xxm);
		for ($j=0; $j<$ssc; $j=$j+1){			
				
					
					?>
    						<td align=middle   height="32" width="32" class="ball_<?=Get_bs_Color(intval($xxm[$j]))?>"><?=$xxm[$j]?></td>
    					<? } ?>
	</tr></table>	</td>
	
	
	
    </tr>
	
	
	

	
	<?
	

	 }?>
<INPUT type='hidden' value=0 name='gold_all'>
</table>
<table border="0" cellpadding="0" class="game_tablex all_body" style="display:none;" cellspacing="0" style="width:780px">
        <tr class="hset2">
        <!-- onclick="return ChkSubmit();" -->
          <td  class="tbtitle" align="center" style="border:1px solid #bbafaf;border-top:none; width:778px;">
            <input name="btnSubmit"    type="submit" class="button_a"  id="btnSubmit" value="投注"  onclick="return ChkSubmit();"/>&nbsp;&nbsp;
          <input type="reset" onclick="javascript:document.all.allgold.innerHTML =0;" class="button_a"  name="Submit3" value="重设" /></td>
        </tr>
      </table>
  </form>
  <INPUT  type="hidden" value=0 name='total_gold' id="total_gold">
<br />
<!-- 正特尾数 -->
 <form  name="lt_form_z" id="lt_form" method="get" action="main_left.php" style="height:640px;" target="k_meml" onsubmit="return ChkSubmit_z();">
 <input type="hidden" name="title_3d" value="尾数" id="title_3d">
 <input type="hidden" name="fc_type" value="9" id="fc_type">
 <input type="hidden" name="ids" value="尾数" id="ids">
 <input type="hidden" name="action" value="n1" id="action"> 
 <input type="hidden" name="class2" value="尾数" id="class2">
<!-- <form name="lt_form_z"  method="post" action="liuhecai.php?action=n1&class2=正特尾数" > -->
<TABLE cellSpacing='1' cellPadding='0' style="width:780px" border='0' class="game_table all_body" style="display:none;">
  <?php 
$dat = M("c_odds_7",$db_config);

$data_bet = $dat->field("rate,id,class3")->where("class1 = '尾数' and class2 = '尾数'")->select();

   ?>
        <tr class="tbtitle2">
		<th>号码</th>
		<th>赔率</th>
		<th>金额</th>
		<th>号码</th>
		<th>赔率</th>
		<th>金额</th>
		<th>号码</th>
		<th>赔率</th>
		<th>金额</th>
		<th>号码</th>
		<th>赔率</th>
		<th>金额</th>
		<th>号码</th>
		<th>赔率</th>
		<th>金额</th>
		

    </tr>
    <tr>
      <td width="44" height="35" align="center" class="ball_bg" ><strong>0尾</strong></td>
      <td width="53" height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true3" id="bl12"> <?=$data_bet['0']['rate'] ?></span><span rate="true3"> </span></b></td>
      <td width="58" height="25" class="ball_ff" align="center" bgcolor="#ffffff"><input onkeypress="return CheckKey();" 
                        onblur="this.className='inp1';return CountGold_z(this,'blur','SP','<?=ka_kk2(0)?>','1');" 
                        onkeyup="return CountGold_z(this,'keyup');" 
                        onfocus="this.className='inp1m';CountGold_z(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="4" type='text' js='js' name="Num_1" id="Num_1" />
          <input name="class3_1" value="0" type="hidden" />
          <input name="gb1" type="hidden"  value="0" />
          <input name="xr_0" type="hidden" id="xr_0" value="0" />
          <input name="xrr_1" type="hidden"  value="0" /></td>

      <td width="44" height="35" align="center"  class="ball_bg" ><strong>1尾</strong></td>
      <td width="53" height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true3" id="bl13"> <?=$data_bet['1']['rate'] ?></span><span rate="true3"> </span></b></td>
      <td width="58" height="25" class="ball_ff" align="center" bgcolor="#ffffff"><input onkeypress="return CheckKey();" 
                        onblur="this.className='inp1';return CountGold_z(this,'blur','SP','<?=ka_kk2(1)?>','2');" 
                        onkeyup="return CountGold_z(this,'keyup');" 
                        onfocus="this.className='inp1m';CountGold_z(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="4" type='text' js='js' name="Num_2" id="Num_2" />
          <input name="class3_2" value="1" type="hidden" />
          <input name="gb2" type="hidden"  value="0" />
          <input name="xr_1" type="hidden" id="xr_1" value="0" />
          <input name="xrr_2" type="hidden" id="xrr_2" value="0" /></td>
      <td width="44" height="35" align="center" class="ball_bg" ><strong>2尾</strong></td>
      <td width="53" height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true3" id="bl14"> <?=$data_bet['2']['rate'] ?></span><span rate="true3"> </span></b></td>
      <td width="58" height="25" class="ball_ff" align="center" bgcolor="#ffffff"><input onkeypress="return CheckKey();" 
                        onblur="this.className='inp1';return CountGold_z(this,'blur','SP','<?=ka_kk2(2)?>','3');" 
                        onkeyup="return CountGold_z(this,'keyup');" 
                        onfocus="this.className='inp1m';CountGold_z(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="4" type='text' js='js' name="Num_3"  id="Num_3" />
          <input name="class3_3" value="2" type="hidden" />
          <input name="gb3" type="hidden"  value="0" />
          <input name="xr_2" type="hidden" id="xr_2" value="0" />
          <input name="xrr_3" type="hidden" id="xrr_3" value="0" />      </td>
      <td width="44" height="35" align="center" class="ball_bg" ><strong>3尾</strong></td>
      <td width="53" height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true3" id="bl15"><?=$data_bet['3']['rate'] ?></span><span rate="true3"> </span></b></td>
      <td width="58" height="25" class="ball_ff" align="center" bgcolor="#ffffff"><input onkeypress="return CheckKey();" 
                        onblur="this.className='inp1';return CountGold_z(this,'blur','SP','<?=ka_kk2(3)?>','4');" 
                        onkeyup="return CountGold_z(this,'keyup');" 
                        onfocus="this.className='inp1m';CountGold_z(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="4" type='text' js='js' name="Num_4" id="Num_4" />
          <input name="class3_4" value="3" type="hidden" />
          <input name="gb4" type="hidden"  value="0" />
          <input name="xr_3" type="hidden" id="xr_3" value="0" />
          <input name="xrr_4" type="hidden" id="xrr_4" value="0" /></td>
      <td width="44" height="35" align="center" class="ball_bg" ><strong>4尾</strong></td>
      <td width="53" height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true3" id="bl16"><?=$data_bet['4']['rate'] ?></span><span rate="true3"> </span></b></td>
      <td width="58" height="25" class="ball_ff" align="center" bgcolor="#ffffff"><input onkeypress="return CheckKey();" 
                        onblur="this.className='inp1';return CountGold_z(this,'blur','SP','<?=ka_kk2(4)?>','5');" 
                        onkeyup="return CountGold_z(this,'keyup');" 
                        onfocus="this.className='inp1m';CountGold_z(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="4" type='text' js='js' name="Num_5" id="Num_5" />
          <input name="class3_5" value="4" type="hidden" />
          <input name="gb5" type="hidden"  value="0" />
          <input name="xr_4" type="hidden"  value="0" />
          <input name="xrr_5" type="hidden"  value="0" />      </td>
    </tr>
    <tr>
      <td width="41" height="35" align="center" class="ball_bg" ><strong>5尾</strong></td>
      <td width="50" height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true3" id="bl17"><?=$data_bet['5']['rate'] ?></span><span rate="true3"> </span></b></td>
      <td width="55" height="25" class="ball_ff" align="center" bgcolor="#ffffff"><input onkeypress="return CheckKey();" 
                        onblur="this.className='inp1';return CountGold_z(this,'blur','SP','<?=ka_kk2(5)?>','6');" 
                        onkeyup="return CountGold_z(this,'keyup');" 
                        onfocus="this.className='inp1m';CountGold_z(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="4" type='text' js='js' name="Num_6" id="Num_6"  />
          <input name="class3_6" value="5" type="hidden" />
          <input name="gb6" type="hidden"  value="0" />
          <input name="xr_5" type="hidden"  value="0" />
          <input name="xrr_6" type="hidden"  value="0" />      </td>
      <td height="35" align="center" class="ball_bg" ><strong>6尾</strong></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true3" id="bl18"><?=$data_bet['6']['rate'] ?></span><span rate="true3"> </span></b></td>
      <td height="25" class="ball_ff" align="center" bgcolor="#ffffff"><input onkeypress="return CheckKey();" 
                        onblur="this.className='inp1';return CountGold_z(this,'blur','SP','<?=ka_kk2(6)?>','7');" 
                        onkeyup="return CountGold_z(this,'keyup');" 
                        onfocus="this.className='inp1m';CountGold_z(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="4" type='text' js='js' name="Num_7"  id="Num_7"/>
          <input name="class3_7" value="6" type="hidden" />
          <input name="gb7" type="hidden"  value="0" />
          <input name="xr_6" type="hidden"  value="0" />
          <input name="xrr_7" type="hidden"  value="0" />      </td>
      <td height="35" align="center" class="ball_bg" ><strong>7尾</strong></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true3" id="bl19"><?=$data_bet['7']['rate'] ?></span><span rate="true3"> </span></b></td>
      <td height="25" align="center" class="ball_ff" bgcolor="#ffffff"><input onkeypress="return CheckKey();" 
                        onblur="this.className='inp1';return CountGold_z(this,'blur','SP','<?=ka_kk2(7)?>','8');" 
                        onkeyup="return CountGold_z(this,'keyup');" 
                        onfocus="this.className='inp1m';CountGold_z(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="4" type='text' js='js' name="Num_8" id="Num_8" />
          <input name="class3_8" value="7" type="hidden" />
          <input name="gb8" type="hidden"  value="0" />
          <input name="xr_7" type="hidden"  value="0" />
          <input name="xrr_8" type="hidden"  value="0" /></td>
      <td height="35" align="center" class="ball_bg" ><strong>8尾</strong></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true3" id="bl20"><?=$data_bet['8']['rate'] ?></span><span rate="true3"> </span></b></td>
      <td height="25" align="center" class="ball_ff" bgcolor="#ffffff"><input onkeypress="return CheckKey();" 
                        onblur="this.className='inp1';return CountGold_z(this,'blur','SP','<?=ka_kk2(8)?>','9');" 
                        onkeyup="return CountGold_z(this,'keyup');" 
                        onfocus="this.className='inp1m';CountGold_z(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="4" type='text' js='js' name="Num_9"  id="Num_9" />
          <input name="class3_9" value="8" type="hidden" />
          <input name="gb9" type="hidden"  value="0" />
          <input name="xr_8" type="hidden"  value="0" />
          <input name="xrr_9" type="hidden"  value="0" />      </td>
      <td height="35" align="center" class="ball_bg" ><strong>9尾</strong></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true3" id="bl21"><?=$data_bet['9']['rate'] ?></span><span rate="true3"> </span></b></td>
      <td height="25" align="center" class="ball_ff" bgcolor="#ffffff"><input onkeypress="return CheckKey();" 
                        onblur="this.className='inp1';return CountGold_z(this,'blur','SP','<?=ka_kk2(9)?>','9');" 
                        onkeyup="return CountGold_z(this,'keyup');" 
                        onfocus="this.className='inp1m';CountGold_z(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="4" type='text' js='js' name="Num_10" id="Num_10" />
          <input name="class3_10" value="9" type="hidden" />
          <input name="gb10" type="hidden"  value="0" />
          <input name="xr_9" type="hidden"  value="0" />
          <input name="xrr_10" type="hidden"  value="0" /></td>
    </tr>
	

</table>

<TABLE cellSpacing='0'  class="game_tablex all_body" style="display:none;" cellPadding='0' style="width:780px" border='0'>
        <tr class="hset2">
         <!-- onclick="return ChkSubmit_z();" -->
          <td  class="tbtitle" align="center" style="border:1px solid #bbafaf;border-top:none;width:778px;">
            <input name="btnSubmit_z"   type="submit"  class="button_a"  id="btnSubmit_z" value="投注" />&nbsp;&nbsp;
            <input type="reset" onclick="javascript:document.all.allgold_z.innerHTML =0;" class="button_a"  name="Submit3" value="重设" /></td>
          </tr>
      </table>
      <span rate="true3" id=allgold_z style="display:none"><?=$data_bet['1']['rate'] ?></span>
      <INPUT type=hidden value=0 name=gold_all_z id="gold_all_z">
  </form>
  <INPUT  type="hidden" value=0 name=total_gold_z id="total_gold_z">






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
			
           
            if(result==""){
           
                result = "Access failure ";
           
            }
           
		   var arrResult = result.split("###");	
		   for(var i=0;i<22;i++)
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
//var sb=i+1
//document.all["xrr_"+sb].value = num5;
//}

var sbbn=i+1
if (num6==1){
MM_changeProp('num_'+sbbn,'','disabled','1','INPUT/text')}


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


</script>

<SCRIPT language=javascript>
 makeRequest('liuhecai.php?action=server&class1=生肖&class2=<?=$ids?>')
 </script>
