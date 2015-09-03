
  <meta charset="UTF-8">

<? 
// if(!defined('PHPYOU')) {
// 	exit('非法进入');
// }
$_GET['leixing']='正码';
$_GET['leixing_bet']='正码';

if ($_GET['ids']!=""){$ids=$_GET['ids'];}else{$ids="正A";}




if ($ids=="正A"){
$xc=6;
$z2color="000000";
$z1color="ff0000";
}else{
$xc=7;
$z1color="000000";
$z2color="ff0000";
}

$XF=15;
function ka_kk1($i){

   }



?>


<SCRIPT language=JAVASCRIPT>
if(self == top) {location = '/';} 
if(window.location.host!=top.location.host){top.location=window.location;} 
</SCRIPT>



 <SCRIPT language=JAVASCRIPT>
<!--
var count_win=false;
//window.setTimeout("self.location='quickinput2.php'", 178000);
function CheckKey(){
	if(event.keyCode == 13) return true;
	if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode > 95 || event.keyCode < 106)){alert("下注金额仅能输入数字!!"); return false;}
}

function ChkSubmit(){
    //判断投注是否超上限
    var ball_limit_num = <?=$ball_limit_num ?>;
    var allgold = $("#allgold").text(); 
    var single_field_max = $("#single_field_max").text();
    if(parseInt(single_field_max) < (parseInt(allgold)+parseInt(ball_limit_num))){
      alert("投注金额超过单项限额！");
      self.location.reload();
      return false;
    }
	if (eval(document.all.allgold.innerHTML)<=0 )
	{
		alert("请选择投注类型！");
		return false;
	}
		document.all.gold_all.value=eval(document.all.allgold.innerHTML)
        document.lt_form.submit();
         self.location.reload();
   //     document.all.allgold.innerHTML=0;
			// document.all.Num_1.value='';
			// document.all.Num_2.value='';
			// document.all.Num_3.value='';
			// document.all.Num_4.value='';
			// document.all.Num_5.value='';
			// document.all.Num_6.value='';
			// document.all.Num_7.value='';
			// document.all.Num_8.value='';
			// document.all.Num_9.value='';
			// document.all.Num_10.value='';
			// document.all.Num_11.value='';
			// document.all.Num_12.value='';
			// document.all.Num_13.value='';
			// document.all.Num_14.value='';
			// document.all.Num_15.value='';
			// document.all.Num_16.value='';
			// document.all.Num_17.value='';
			// document.all.Num_18.value='';
			// document.all.Num_19.value='';
			// document.all.Num_20.value='';
			// document.all.Num_21.value='';
			// document.all.Num_22.value='';
			// document.all.Num_23.value='';
			// document.all.Num_24.value='';
			// document.all.Num_25.value='';
			// document.all.Num_26.value='';
			// document.all.Num_27.value='';
			// document.all.Num_28.value='';
			// document.all.Num_29.value='';
			// document.all.Num_30.value='';
			// document.all.Num_31.value='';
			// document.all.Num_32.value='';
			// document.all.Num_33.value='';
			// document.all.Num_34.value='';
			// document.all.Num_35.value='';
			// document.all.Num_36.value='';
			// document.all.Num_37.value='';
			// document.all.Num_38.value='';
			// document.all.Num_39.value='';
			// document.all.Num_40.value='';
			// document.all.Num_41.value='';
			// document.all.Num_42.value='';
			// document.all.Num_43.value='';
			// document.all.Num_44.value='';
			// document.all.Num_45.value='';
			// document.all.Num_46.value='';
			// document.all.Num_47.value='';
			// document.all.Num_48.value='';
			// document.all.Num_49.value='';
			// document.all.Num_50.value='';
			// document.all.Num_51.value='';
			// document.all.Num_52.value='';
			// document.all.Num_53.value='';

}



function CountGold(gold,type,rtype,bb,ffb){

}
//-->
</SCRIPT>

 <style type="text/css">
.STYLE1 {color: #333}
 </style>

<TABLE  border="0" cellpadding="2" cellspacing="1" bordercolordark="#f9f9f9" bgcolor="#CCCCCC" width="780" >
  <TBODY>
  <!-- <TR class="tbtitle"> -->
    <!-- <TD > -->
<?php include("common_qishu.php"); ?>

<form  name="lt_form"  method="get" action="main_left.php"  style="height:580px;" target="k_meml" onsubmit="return ChkSubmit();">
<input type="hidden" name="title_3d" value="正码" id="title_3d">
 <input type="hidden" name="fc_type" value="9" id="fc_type">
 <input type="hidden" name="ids" value="<?=$ids ?>" id="ids">
 <input type="hidden" name="action" value="n1" id="action"> 
 <input type="hidden" name="class2" value="<?=$ids?>" id="class2">
<TABLE cellSpacing=1 cellPadding=0 style="width:780px" border=0 class="game_table all_body"  style="display:none;" >
   <tr class="tbtitle2">
    <th colspan="15"><div  style="float:left;line-height:30px;text-align:center;width:100%"><?=$_GET['leixing'] ?></div>
	</th>
  </tr> 

<tr class="tbtitle2">
    <td>号码</th>
    <td>赔率</th>
    <td>金额</th>
    <td>号码</th>
    <td>赔率</th>
    <td>金额</th>
    <td>号码</th>
    <td>赔率</th>
    <td>金额</th>
    <td>号码</th>
    <td>赔率</th>
    <td>金额</th>
    <td>号码</th>
    <td>赔率</th>
    <td>金额</th>
  </tr>
    <tr class="tbtitle">
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm01">01</td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id="bl00"> <?=$data_bet['0']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(1)?>','1');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_1" ID="Num_01"  />
          <input name="class3_1" value="1" type="hidden" >
        <!--   <input name="gb1" type="hidden"  value="0">
          <input name="xr_0" type="hidden" id="xr_0" value="0" >
          <input name="xrr_1" type="hidden"  value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm11">11</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl10> <?=$data_bet['10']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(11)?>','11');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;"     style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_11" id="Num_11"/>
          <input name="class3_11" value="11" type="hidden" >
        <!--   <input name="gb11" type="hidden"  value="0">
		    <input name="xr_10" type="hidden" id="xr_10" value="0" >
          <input name="xrr_11" type="hidden"  value="0" > -->		  </td>
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm21">21</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl20><?=$data_bet['20']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(21)?>','21');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_21"  id="Num_21"/>
          <!-- <input name="gb21" type="hidden"  value="0"> -->
          <input name="class3_21" value="21" type="hidden" >
		   <!-- <input name="xr_20" type="hidden" id="xr_20" value="0" >
          <input name="xrr_21" type="hidden" id="xrr_21" value="0">	 -->	  </td>
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm31">31</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl30> <?=$data_bet['30']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(31)?>','31');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_31" id="Num_31"/>
          <input name="class3_31" value="31" type="hidden" >
       <!--    <input name="gb31" type="hidden"  value="0">
		   <input name="xr_30" type="hidden" id="xr_30" value="0" >
          <input name="xrr_31" type="hidden" id="xrr_31" value="0">	 -->	  </td>
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm41">41</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl40> <?=$data_bet['40']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(41)?>','41');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_41"  id="Num_41" />
          <input name="class3_41" value="41" type="hidden" >
         <!--  <input name="gb41" type="hidden"  value="0">
		   <input name="xr_40" type="hidden" id="xr_40" value="0" >
          <input name="xrr_41" type="hidden" id="xrr_41" value="0"> --></td>
    </tr>
    <tr class="tbtitle">
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm02">02</td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl1> <?=$data_bet['1']['rate'] ?></span><span rate="true1"> </span></b></td>
       <td height="25" class="ball_ff"><input onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(2)?>','2');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_2" id="Num_02"/>
          <input name="class3_2" value="2" type="hidden" >
        <!--   <input name="gb2" type="hidden"  value="0">
		  
		   <input name="xr_1" type="hidden" id="xr_1" value="0" >
          <input name="xrr_2" type="hidden" id="xrr_2" value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm12">12</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl11> <?=$data_bet['11']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(12)?>','12');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;"     style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_12"  id="Num_12"/>
          <input name="class3_12" value="12" type="hidden" >
         <!--  <input name="gb12" type="hidden"  value="0">
		   <input name="xr_11" type="hidden" id="xr_11" value="0" >
          <input name="xrr_12" type="hidden" id="xrr_12" value="0" > -->		  </td>
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm22">22</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl21> <?=$data_bet['21']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(22)?>','22');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_22" id="Num_22"/>
          <!-- <input name="gb22" type="hidden"  value="0"> -->
          <input name="class3_22" value="22" type="hidden" >
	<!-- 	   <input name="xr_21" type="hidden" id="xr_21" value="0" >
          <input name="xrr_22" type="hidden" id="xrr_22" value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm32">32</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl31> <?=$data_bet['31']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(32)?>','32');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_32" id="Num_32"/>
          <input name="class3_32" value="32" type="hidden" >
          <!-- <input name="gb32" type="hidden"  value="0">
		  
		   <input name="xr_31" type="hidden" id="xr_31" value="0" >
          <input name="xrr_32" type="hidden" id="xrr_32" value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm42">42</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl41> <?=$data_bet['41']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(42)?>','42');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_42" id="Num_42" />
          <input name="class3_42" value="42" type="hidden" >
         <!--  <input name="gb42" type="hidden"  value="0">
		   <input name="xr_41" type="hidden" id="xr_41" value="0" >
          <input name="xrr_42" type="hidden" id="xrr_42" value="0" > --></td>
    </tr>
    <tr class="tbtitle">
      <td align="center" bgcolor="#FFFFFF" class="ball_b" id="hm03"><span rate="true1" class="ball_r">03</span></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl2> 
	  <?=$data_bet['2']['rate'] ?></span><span rate="true1"> </span></b></td>
       <td height="25" class="ball_ff"><input onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(3)?>','3');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_3" id="Num_03" />
          <input name="class3_3" value="3" type="hidden" >
        <!--   <input name="gb3" type="hidden"  value="0">
		   <input name="xr_2" type="hidden" id="xr_2" value="0" >
          <input name="xrr_3" type="hidden" id="xrr_3" value="0" > -->		  </td>
      <td align="center" bgcolor="#FFFFFF" class="ball_b" id="hm13"><span rate="true1" class="ball_r">13</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl12>  <?=$data_bet['12']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(13)?>','13');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;"     style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_13"  id="Num_13"/>
          <input name="class3_13" value="13" type="hidden" >
          <!-- <input name="gb13" type="hidden"  value="0">
		  
		   <input name="xr_12" type="hidden" id="xr_12" value="0" >
          <input name="xrr_13" type="hidden" id="xrr_13" value="0" > -->		  </td>
      <td align="center" bgcolor="#FFFFFF" class="ball_b" id="hm23"><span rate="true1" class="ball_r">23</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl22><?=$data_bet['22']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(23)?>','23');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_23" id="Num_23"/>
          <!-- <input name="gb23" type="hidden"  value="0"> -->
          <input name="class3_23" value="23" type="hidden" >
	<!-- 	   <input name="xr_22" type="hidden" id="xr_22" value="0" >
          <input name="xrr_23" type="hidden" id="xrr_23" value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_b" id="hm33"><span rate="true1" class="ball_r">33</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl32><?=$data_bet['32']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(33)?>','33');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_33" id="Num_33" />
          <input name="class3_33" value="33" type="hidden" >
         <!--  <input name="gb33" type="hidden"  value="0">
		   <input name="xr_32" type="hidden" id="xr_32" value="0" >
          <input name="xrr_33" type="hidden" id="xrr_33" value="0" > -->		  </td>
      <td align="center" bgcolor="#FFFFFF" class="ball_b" id="hm43"><span rate="true1" class="ball_r">43</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl42><?=$data_bet['42']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(43)?>','43');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_43" id="Num_43" />
          <input name="class3_43" value="43" type="hidden" >
          <!-- <input name="gb43" type="hidden"  value="0">
		  
		   <input name="xr_42" type="hidden" id="xr_42" value="0" >
          <input name="xrr_43" type="hidden" id="xrr_43" value="0" > --></td>
    </tr>
    <tr class="tbtitle">
      <td align="center" bgcolor="#FFFFFF" class="ball_b" id="hm04"><span rate="true1" class="ball_r">04</span></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl3><?=$data_bet['3']['rate'] ?></span><span rate="true1"> </span></b></td>
       <td height="25" class="ball_ff"><input onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(4)?>','4');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_4" id="Num_04"/>
          <input name="class3_4" value="4" type="hidden" >
          <!-- <input name="gb4" type="hidden"  value="0">
		  
		   <input name="xr_3" type="hidden" id="xr_3" value="0" >
          <input name="xrr_4" type="hidden" id="xrr_4" value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_b" id="hm14"><span rate="true1" class="ball_r">14</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl13><?=$data_bet['13']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(14)?>','14');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;"     style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_14"  id="Num_14"/>
          <input name="class3_14" value="14" type="hidden" >
          <!-- <input name="gb14" type="hidden"  value="0">
		  
		  <input name="xr_13" type="hidden" id="xr_13" value="0" >
          <input name="xrr_14" type="hidden" id="xrr_14" value="0" > -->		  </td>
      <td align="center" bgcolor="#FFFFFF" class="ball_b" id="hm24"><span rate="true1" class="ball_r">24</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl23><?=$data_bet['23']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onkeypress="return CheckKey();" 
                        onblur="return CountGold(this,'blur','SP','<?=ka_kk1(24)?>','24');" 
                        onkeyup="return CountGold(this,'keyup');" 
                        onfocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_24"  id="Num_24" />
      <!-- <input name="gb24" type="hidden"  value="0"> -->
          <input name="class3_24" value="24" type="hidden" >
		 <!--  <input name="xr_23" type="hidden" id="xr_23" value="0" >
          <input name="xrr_24" type="hidden" id="xrr_24" value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_b" id="hm34"><span rate="true1" class="ball_r">34</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl33><?=$data_bet['33']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(34)?>','34');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_34" id="Num_34" />
          <input name="class3_34" value="34" type="hidden" >
         <!--  <input name="gb34" type="hidden"  value="0">
		  
		  <input name="xr_33" type="hidden" id="xr_33" value="0" >
          <input name="xrr_34" type="hidden" id="xrr_34" value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_b" id="hm44"><span rate="true1" class="ball_r">44</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl43><?=$data_bet['43']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(44)?>','44');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_44" id="Num_44" />
          <input name="class3_44" value="44" type="hidden" >
          <!-- <input name="gb44" type="hidden"  value="0">
		  
		  <input name="xr_43" type="hidden" id="xr_43" value="0" >
          <input name="xrr_44" type="hidden" id="xrr_44" value="0" > --></td>
    </tr>
    <tr class="tbtitle">
      <td align="center" bgcolor="#FFFFFF" class="ball_g" id="hm05"><span rate="true1" class="ball_r">05</span></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl4><?=$data_bet['4']['rate'] ?></span><span rate="true1"> </span></b></td>
       <td height="25" class="ball_ff"><input onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(5)?>','5');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_5" id="Num_05"/>
          <input name="class3_5" value="5" type="hidden" >
         <!--  <input name="gb5" type="hidden"  value="0">
		  <input name="xr_4" type="hidden"  value="0" >
          <input name="xrr_5" type="hidden"  value="0" > -->		  </td>
      <td align="center" bgcolor="#FFFFFF" class="ball_g" id="hm15"><span rate="true1" class="ball_r">15</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl14><?=$data_bet['14']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(15)?>','15');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;"     style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_15"  id="Num_15" />
          <input name="class3_15" value="15" type="hidden" >
         <!--  <input name="gb15" type="hidden"  value="0">
		  
		    <input name="xr_14" type="hidden"  value="0" >
          <input name="xrr_15" type="hidden"  value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_g" id="hm25"><span rate="true1" class="ball_r">25</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl24><?=$data_bet['24']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(25)?>','25');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_25" id="Num_25" />
          <!-- <input name="gb25" type="hidden"  value="0"> -->
          <input name="class3_25" value="25" type="hidden" >
		   <!--  <input name="xr_24" type="hidden"  value="0" >
          <input name="xrr_25" type="hidden"  value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_g" id="hm35"><span rate="true1" class="ball_r">35</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl34><?=$data_bet['34']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(35)?>','35');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_35" id="Num_35"  />
          <input name="class3_35" value="35" type="hidden" >
         <!--  <input name="gb35" type="hidden"  value="0">
		    <input name="xr_34" type="hidden"  value="0" >
          <input name="xrr_35" type="hidden"  value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_g" id="hm45"><span rate="true1" class="ball_r">45</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl44><?=$data_bet['44']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(45)?>','45');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_45"  id="Num_45" />
          <input name="class3_45" value="45" type="hidden" >
         <!--  <input name="gb45" type="hidden"  value="0">
		  
		    <input name="xr_44" type="hidden"  value="0" >
          <input name="xrr_45" type="hidden"  value="0" > --></td>
    </tr>
    <tr class="tbtitle">
      <td align="center" bgcolor="#FFFFFF" class="ball_g" id="hm06"><span rate="true1" class="ball_r">06</span></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl5><?=$data_bet['5']['rate'] ?></span><span rate="true1"> </span></b></td>
       <td height="25" class="ball_ff"><input onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(6)?>','6');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_6"  id="Num_06"/>
          <input name="class3_6" value="6" type="hidden" >
         <!--  <input name="gb6" type="hidden"  value="0">
          <input name="xr_5" type="hidden"  value="0" >
          <input name="xrr_6" type="hidden"  value="0" > -->     </td>
      <td align="center" bgcolor="#FFFFFF" class="ball_g" id="hm16"><span rate="true1" class="ball_r">16</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl15> <?=$data_bet['15']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(16)?>','16');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;"     style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_16" id="Num_16"/>
          <input name="class3_16" value="16" type="hidden" >
          <!-- <input name="gb16" type="hidden"  value="0">
		  
		    <input name="xr_15" type="hidden"  value="0" >
          <input name="xrr_16" type="hidden"  value="0" > --> </td>
      <td align="center" bgcolor="#FFFFFF" class="ball_g" id="hm26"><span rate="true1" class="ball_r">26</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl25><?=$data_bet['25']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(26)?>','26');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_26" id="Num_26" />
          <!-- <input name="gb26" type="hidden"  value="0"> -->
          <input name="class3_26" value="26" type="hidden" >
		  
		   <!--  <input name="xr_25" type="hidden"  value="0" >
          <input name="xrr_26" type="hidden"  value="0" > --> </td>
      <td align="center" bgcolor="#FFFFFF" class="ball_g" id="hm36"><span rate="true1" class="ball_r">36</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl35><?=$data_bet['35']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(36)?>','36');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_36" id="Num_36" />
          <input name="class3_36" value="36" type="hidden" >
          <!-- <input name="gb36" type="hidden"  value="0">
		  
		    <input name="xr_35" type="hidden"  value="0" >
          <input name="xrr_36" type="hidden"  value="0" > --> </td>
      <td align="center" bgcolor="#FFFFFF" class="ball_g" id="hm46"><span rate="true1" class="ball_r">46</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl45><?=$data_bet['45']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(46)?>','46');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_46" id="Num_46" />
          <input name="class3_46" value="46" type="hidden" >
          <!-- <input name="gb46" type="hidden"  value="0">
		  
		    <input name="xr_45" type="hidden"  value="0" >
          <input name="xrr_46" type="hidden"  value="0" > --> </td>
    </tr>
    <tr class="tbtitle">
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm07">07</td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl6><?=$data_bet['6']['rate'] ?></span><span rate="true1"> </span></b></td>
       <td height="25" class="ball_ff"><input onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(7)?>','7');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_7"  id="Num_07" />
          <input name="class3_7" value="7" type="hidden" >
         <!--  <input name="gb7" type="hidden"  value="0">
		  
		    <input name="xr_6" type="hidden"  value="0" >
          <input name="xrr_7" type="hidden"  value="0" > --> </td>
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm17">17</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl16><?=$data_bet['16']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(17)?>','17');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;"     style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_17"  id="Num_17"/>
          <input name="class3_17" value="17" type="hidden" >
        <!--   <input name="gb17" type="hidden"  value="0">
		  
		   <input name="xr_16" type="hidden"  value="0" >
          <input name="xrr_17" type="hidden"  value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm27">27</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl26><?=$data_bet['26']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(27)?>','27');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_27" id="Num_27"/>
          <!-- <input name="gb27" type="hidden"  value="0"> -->
          <input name="class3_27" value="27" type="hidden" >
          <!-- <input name="xr_26" type="hidden"  value="0" >
          <input name="xrr_27" type="hidden"  value="0" > -->    </td>
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm37">37</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl36><?=$data_bet['36']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(37)?>','37');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_37"  id="Num_37"  />
          <input name="class3_37" value="37" type="hidden" >
         <!--  <input name="gb37" type="hidden"  value="0">
		  
		   <input name="xr_36" type="hidden"  value="0" >
          <input name="xrr_37" type="hidden"  value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm47">47</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl46><?=$data_bet['46']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(47)?>','47');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_47" id="Num_47"  />
          <input name="class3_47" value="47" type="hidden" >
          <!-- <input name="gb47" type="hidden"  value="0">
		   <input name="xr_46" type="hidden"  value="0" >
          <input name="xrr_47" type="hidden"  value="0" > --></td>
    </tr>
    <tr class="tbtitle">
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm08">08</td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl7><?=$data_bet['7']['rate'] ?></span><span rate="true1"> </span></b></td>
       <td height="25" class="ball_ff"><input onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(8)?>','8');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_8"  id="Num_08"/>
          <input name="class3_8" value="8" type="hidden" >
       <!--    <input name="gb8" type="hidden"  value="0">
		  
		   <input name="xr_7" type="hidden"  value="0" >
          <input name="xrr_8" type="hidden"  value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm18">18</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl17><?=$data_bet['17']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(18)?>','18');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;"     style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_18"  id="Num_18" />
          <input name="class3_18" value="18" type="hidden" >
          <!-- <input name="gb18" type="hidden"  value="0">
		  
		    <input name="xr_17" type="hidden"  value="0" >
          <input name="xrr_18" type="hidden"  value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm28">28</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl27><?=$data_bet['27']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(28)?>','28');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_28"  id="Num_28" />
          <!-- <input name="gb28" type="hidden"  value="0"> -->
          <input name="class3_28" value="28" type="hidden" >
		  
		<!--     <input name="xr_27" type="hidden"  value="0" >
          <input name="xrr_28" type="hidden"  value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm38">38</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl37><?=$data_bet['37']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(38)?>','38');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_38" id="Num_38" />
          <input name="class3_38" value="38" type="hidden" >
        <!--   <input name="gb38" type="hidden"  value="0">
		    <input name="xr_37" type="hidden"  value="0" >
          <input name="xrr_38" type="hidden"  value="0" > --></td>

          
      <td align="center" bgcolor="#FFFFFF" class="ball_r" id="hm48">48</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id="bl47"><?=$data_bet['47']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(48)?>','48');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_48" id="Num_48"  />
          <input name="class3_48" value="48" type="hidden" >
        <!--   <input name="gb48" type="hidden"  value="0">
		  
		    <input name="xr_47" type="hidden"  value="0" >
          <input name="xrr_48" type="hidden"  value="0" > --></td>
    </tr>
    <tr class="tbtitle">
      <td align="center" bgcolor="#FFFFFF" class="ball_b" id="hm09"><span rate="true1" class="ball_r">09</span></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl8><?=$data_bet['8']['rate'] ?></span><span rate="true1"> </span></b></td>
       <td height="25" class="ball_ff"><input onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(9)?>','9');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_9" id="Num_09" />
          <input name="class3_9" value="9" type="hidden" >
     <!--      <input name="gb9" type="hidden"  value="0">
          <input name="xr_8" type="hidden"  value="0" >
          <input name="xrr_9" type="hidden"  value="0" > -->		       </td>
      <td align="center" bgcolor="#FFFFFF" class="ball_b" id="hm19"><span rate="true1" class="ball_r">19</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl18><?=$data_bet['18']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(19)?>','19');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;"     style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_19"  id="Num_19"/>
          <input name="class3_19" value="19" type="hidden" >
       <!--    <input name="gb19" type="hidden"  value="0">
		  
		   <input name="xr_18" type="hidden"  value="0" >
          <input name="xrr_19" type="hidden"  value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_b" id="hm29"><span rate="true1" class="ball_r">29</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl28><?=$data_bet['28']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(29)?>','29');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_29" id="Num_29"/>
          <!-- <input name="gb29" type="hidden"  value="0"> -->
          <input name="class3_29" value="29" type="hidden" >
		  
		<!--    <input name="xr_28" type="hidden"  value="0" >
          <input name="xrr_29" type="hidden"  value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_b" id="hm39"><span rate="true1" class="ball_r">39</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl38><?=$data_bet['38']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(39)?>','39');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_39" id="Num_39" />
          <input name="class3_39" value="39" type="hidden" >
        <!--   <input name="gb39" type="hidden"  value="0">
		  
		   <input name="xr_38" type="hidden"  value="0" >
          <input name="xrr_39" type="hidden"  value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_b" id="hm49"><span rate="true1" class="ball_r">49</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl48><?=$data_bet['48']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(49)?>','49');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_49" id="Num_49" />
          <input name="class3_49" value="49" type="hidden" >
      <!--     <input name="gb49" type="hidden"  value="0">
		  
		   <input name="xr_48" type="hidden"  value="0" >
          <input name="xrr_49" type="hidden"  value="0" > --></td>
    </tr>
    <tr class="tbtitle">
      <td height="25" align="center" class="ball_b" bgcolor="#FFFFFF" id="hm10"><span rate="true1" class="ball_r">10</span></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl9><?=$data_bet['9']['rate'] ?></span><span rate="true1"> </span></b></td>
       <td height="25" class="ball_ff"><input onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(10)?>','10');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_10" id="Num_10" />
          <input name="class3_10" value="10" type="hidden" >
          <!-- <input name="gb10" type="hidden"  value="0">
		  
		  
		   <input name="xr_9" type="hidden"  value="0" >
          <input name="xrr_10" type="hidden"  value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_b" id="hm20"><span rate="true1" class="ball_r">20</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl19><?=$data_bet['19']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(20)?>','20');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;"     style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_20" id="Num_20" />
          <input name="class3_20" value="20" type="hidden" >
        <!--   <input name="gb20" type="hidden"  value="0">
		  
		  <input name="xr_19" type="hidden"  value="0" >
          <input name="xrr_20" type="hidden"  value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_b" id="hm30"><span rate="true1" class="ball_r">30</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl29> <?=$data_bet['29']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(30)?>','30');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_30" id="Num_30"/>
          <!-- <input name="gb30" type="hidden"  value="0"> -->
          <input name="class3_30" value="30" type="hidden" >
		  <!-- input name="xr_29" type="hidden"  value="0" >
          <input name="xrr_30" type="hidden"  value="0" > --></td>
      <td align="center" bgcolor="#FFFFFF" class="ball_b" id="hm40"><span rate="true1" class="ball_r">40</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff"><b><span rate="true1" id=bl39><?=$data_bet['39']['rate'] ?></span></b></td>
       <td height="25" class="ball_ff"><input 
			  onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1(40)?>','40');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_40"  id="Num_40" />
          <input name="class3_40" value="40" type="hidden" >
        <!--   <input name="gb40" type="hidden"  value="0">
		  <input name="xr_39" type="hidden"  value="0" >
          <input name="xrr_40" type="hidden"  value="0" > --></td>

	  <td class="ball_ff" rowspan="2" colspan="3">&nbsp;</td>
    </tr>

       <!-- ------------------------------------------ -->
    <tr class="tbtitle">
   <TD  class="tbtitle" height="26" width="0">总单</TD>
          <TD  width="0" class="ball_ff"><b><span rate="true1" id=bl49><?=$data_bet['49']['rate'] ?></span></b></TD>
          <TD  width=78 class="ball_ff"><input 
        onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','dx','<?=ka_kk1("单")?>');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_50" id="Num_50" />
          <input name="gb50" type="hidden"  value="0">
          <input name="class3_50" value="总单" type="hidden" ></TD>


      <TD  class="tbtitle" height="26" width="0">总双</TD>
          <TD width="0" class="ball_ff" ><b><span rate="true1" id="bl50"><?=$data_bet['50']['rate'] ?></span></b></TD>
          <TD  width=78 class="ball_ff"><input 
        onkeypress="return CheckKey();" 
                        onblur="return CountGold(this,'blur','dx','<?=ka_kk1("双")?>');" 
                        onkeyup="return CountGold(this,'keyup');" 
                        onfocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_51"  id="Num_51"/>
          <input name="gb51" type="hidden"  value="0" />
          <input name="class3_51" value="总双" type="hidden"></TD>
  <TD  class="tbtitle" height="26" width="0">总大</TD>
          <TD width="0" class="ball_ff"><b><span rate="true1" id=bl51><?=$data_bet['51']['rate'] ?></span></b></TD>
          <TD  width=78 class="ball_ff"><input 
        onkeypress="return CheckKey();" 
                        onblur="return CountGold(this,'blur','ds','<?=ka_kk1("大")?>');" 
                        onkeyup="return CountGold(this,'keyup');" 
                        onfocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_52" id="Num_52" />
          <input name="gb52" type="hidden"  value="0" />
          <input name="class3_52" value="总大" type="hidden">
          </TD>
     <TD  class="tbtitle" height="26" width="0">总小</TD>
          <TD  width="0" class="ball_ff"><b><span rate="true1" id=bl52><?=$data_bet['52']['rate'] ?></span></b></TD>
          <TD  width=78 class="ball_ff"><input 
        onkeypress="return CheckKey();" 
                        onblur="return CountGold(this,'blur','ds','<?=ka_kk1("小")?>');" 
                        onkeyup="return CountGold(this,'keyup');" 
                        onfocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="HEIGHT: 18px"  class="" size="3" type='text' js='js' name="Num_53" id="Num_53"  />
          <input name="gb53" type="hidden"  value="0" />
          <input name="class3_53" value="总小" type="hidden"></TD>
    </tr>
    <tr class="hset2">
       <td id="" align="center" class="tbtitle" colspan="15">
      <input name="btnSubmit"  type="submit"  class="button_a" id="btnSubmit" value="投注" />&nbsp;
  <!-- onclick="return ChkSubmit();" -->
        &nbsp;  <input  name='reset' onclick="javascript:document.all.allgold.innerHTML =0;"  class='button_a' type='reset' value='重 设' /></td>
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
			
           
            if(result==""){
           
                result = "Access failure ";
           
            }
           
		   var arrResult = result.split("###");	
		   for(var i=0;i<53;i++)
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

function beginrefresh(){
	 makeRequest('liuhecai.php?action=server&class1=正码&class2=<?=$ids?>');
}
</script>

<SCRIPT language=javascript>
 makeRequest('liuhecai.php?action=server&class1=正码&class2=<?=$ids?>')
 </script>
 <?php include(dirname(__FILE__)."/fast.php") ?>