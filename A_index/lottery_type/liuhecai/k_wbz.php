<? if(!defined('PHPYOU')) {
	exit('非法进入');
}

$_GET['leixing']='全不中';
$_GET['leixing_bet']='全不中';
$xc=37;
$XF=21;
$ids=$_GET['ids'];
if ($ids=="") $ids="五不中";


function Get_sx_nx($rrr){   
  global $mysqli;  
$result=$mysqli->query("Select id,m_number,sx From ka_sxnumber where  id='".$rrr."' Order By ID LIMIT 1"); 
$ka_Color1=$result->fetch_array();
$xxmnx=explode(",",$ka_Color1['m_number']);
return intval($xxmnx[0]);
}
 
?>



<?

// if ($Current_KitheTable[29]==0 or $Current_KitheTable[$XF]==0) {       
?>
<script language="javascript">
// Make_FlashPlay('imgs/T0.swf','T','780','500');
</script>
<?
// exit;
// }

$result=$mysqli->query("Select class3,rate from c_odds_7 where class2='".$ids."' order by ID");
$drop_table = array();
$y=0;
while($image = $result->fetch_array()){
$y++;
//echo $image['class3'];
array_push($drop_table,$image);

}

?>
<? if ($ids=="五不中"){ ?>
<SCRIPT language=javascript>
  var type_nums=8;  
  var type_min =5;
  var mess2 =  '最多选择8个数字';
</script>
<? }?>
<? if ($ids=="六不中"){ ?>
<SCRIPT language=javascript>
  var type_nums=8;  
  var type_min =6;
  var mess2 =  '最多选择8个数字';
</script>
<? }?>
<? if ($ids=="七不中"){ ?>
<SCRIPT language=javascript>
  var type_nums=9;  
  var type_min =7;
  var mess2 =  '最多选择9个数字';
</script>
<? }?>
<? if ($ids=="八不中"){ ?>
<SCRIPT language=javascript>
  var type_nums=10;  
  var type_min =8;
  var mess2 =  '最多选择10个数字';
</script>
<? }?>
<? if ($ids=="九不中"){ ?>
<SCRIPT language=javascript>
  var type_nums=12;  
  var type_min =9;
  var mess2 =  '最多选择11个数字';
</script>
<? }?>
<? if ($ids=="十不中"){ ?>
<SCRIPT language=javascript>
  var type_nums=11;  
  var type_min =10;
  var mess2 =  '最多选择11个数字';
</script>
<? }?>
<? if ($ids=="十一不中"){ ?>
<SCRIPT language=javascript>
  var type_nums=13;  
  var type_min =11;
  var mess2 =  '最多选择13个数字';
</script>
<? }?>
<? if ($ids=="十二不中"){ ?>
<SCRIPT language=javascript>
  var type_nums=14;  
  var type_min =12;
  var mess2 =  '最多选择14个数字';
</script>
<? }?>


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
    //设定『确定』键为反白 
	document.all.btnSubmit.disabled = true;

	if (eval(document.all.allgold.innerHTML)<=0 )
	{
		alert("请输入下注金额!!");
	    document.all.btnSubmit.disabled = false;
		return false;

	}

       if (!confirm("是否确定下注")){
	   document.all.btnSubmit.disabled = false;
       return false;
       }        
		document.all.gold_all.value=eval(document.all.allgold.innerHTML)
        document.lt_form.submit();
}



function CountGold(gold,type,rtype,bb,ffb){
 
}
//-->
</SCRIPT>

 <style type="text/css">

.STYLE2 {color: #333}

 </style>
 <body 
>
<noscript>
<!-- <iframe scr=″*.htm″></iframe> -->
</noscript>


<SCRIPT language=javascript>
<!--
if(self == top) location = '/';

var cb_num = 1;
var mess1 =  '最少选择';
var mess11 = '个数字';
var mess = mess2;


function ra_select(str1,zz){

       
        document.all[str1].value = zz;

}



function SubChk(){

  var count = 0;
   for ( var i=0; i<document.lt_form.elements.length; i++ )
   {
    var e = document.lt_form.elements[i];
    if ( (e.type=='checkbox')&&(!e.disabled) )
    {
     if ( e.checked )
     {
      count++ ;
     }
    }
   }  
    if(count < type_min){  
        alert("温馨提示:至少选择"+type_min +"个"); 
        return false;  
    }
        document.lt_form.submit();


}

function SubChkBox(obj,bmbm) {


	if(obj.checked == false)
	{
		cb_num--;
		//obj.checked = false;
	}


	//alert (cb_num);


	if(obj.checked == true)
	{
		if ( cb_num > type_nums ) 
		{
			alert(mess);
			cb_num--;
			obj.checked = false;
		}
		cb_num++;
	}


var str1="pabc";
var str2="rrtype";
var str3="dm1";
var str4="dm2";

if(document.all[str1].value ==2)

{
if(document.all[str2].value ==1  )

{

if (document.all[str3].value ==""){
MM_changeProp('num'+bmbm,'','disabled','disabled','INPUT/CHECKBOX')
///MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
document.all[str3].value = bmbm;
MM_changeProp('dm1','','disabled','0','INPUT/text')
}else
{
if (document.all[str4].value ==""){
MM_changeProp('num'+bmbm,'','disabled','disabled','INPUT/CHECKBOX')
MM_changeProp('dm2','','disabled','0','INPUT/text')
///MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
document.all[str4].value = bmbm;
}
}
}else
{
if (document.all[str3].value ==""){
MM_changeProp('num'+bmbm,'','disabled','disabled','INPUT/CHECKBOX')
MM_changeProp('dm1','','disabled','0','INPUT/text')
///MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
document.all[str3].value = bmbm;
}
}
}
}


//-->
</SCRIPT>
<script>
  $(function(){   
    $("span").css('cursor','default');
  })
</script>
<TABLE  border="0" class="tab_001" cellpadding="2" cellspacing="1" bordercolordark="#f9f9f9" bgcolor="#CCCCCC" width="780" >
  <TBODY>

  <?php include("common_qishu.php"); ?>
  <TR class="tbtitle">
    <TD class="tbtitle4"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
    <TD align=center colSpan="" height="30">
<?if ($ids=="五不中"){?>
<input name="type0" type="radio" checked="checked" value="5" onClick="javascript:location.href='liuhecai.php?action=k_wbz&ids=五不中';" ><SPAN id=rtm1 STYLE='color:ff0000;'>五不中</span>
<?} else{?>
<input name="type0" type="radio" value="5" onClick="javascript:location.href='liuhecai.php?action=k_wbz&ids=五不中';"><SPAN id=rtm1 STYLE='color:000000;'>五不中</span>
<?}?>
<?if ($ids=="六不中"){?>
<input name="type0" type="radio" checked="checked" value="6" onClick="javascript:location.href='liuhecai.php?action=k_wbz&ids=六不中';" ><SPAN id=rtm1 STYLE='color:ff0000;'>六不中</span>
<?} else{?>
<input name="type0" type="radio" value="6" onClick="javascript:location.href='liuhecai.php?action=k_wbz&ids=六不中';"><SPAN id=rtm1 STYLE='color:000000;'>六不中</span>
<?}?>
<?if ($ids=="七不中"){?>
<input name="type0" type="radio" checked="checked" value="7" onClick="javascript:location.href='liuhecai.php?action=k_wbz&ids=七不中';" ><SPAN id=rtm1 STYLE='color:ff0000;'>七不中</span>
<?} else{?>
<input name="type0" type="radio" value="7" onClick="javascript:location.href='liuhecai.php?action=k_wbz&ids=七不中';"><SPAN id=rtm1 STYLE='color:000000;'>七不中</span>
<?}?>
<?if ($ids=="八不中"){?>
<input name="type0" type="radio" checked="checked" value="8" onClick="javascript:location.href='liuhecai.php?action=k_wbz&ids=八不中';" ><SPAN id=rtm1 STYLE='color:ff0000;'>八不中</span>
<?} else{?>
<input name="type0" type="radio" value="8" onClick="javascript:location.href='liuhecai.php?action=k_wbz&ids=八不中';"><SPAN id=rtm1 STYLE='color:000000;'>八不中</span>
<?}?>
<?if ($ids=="九不中"){?>
<input name="type0" type="radio" checked="checked" value="9" onClick="javascript:location.href='liuhecai.php?action=k_wbz&ids=九不中';" ><SPAN id=rtm1 STYLE='color:ff0000;'>九不中</span>
<?} else{?>
<input name="type0" type="radio" value="9" onClick="javascript:location.href='liuhecai.php?action=k_wbz&ids=九不中';"><SPAN id=rtm1 STYLE='color:000000;'>九不中</span>
<?}?>
<?if ($ids=="十不中"){?>
<input name="type0" type="radio" checked="checked" value="10" onClick="javascript:location.href='liuhecai.php?action=k_wbz&ids=十不中';" ><SPAN id=rtm1 STYLE='color:ff0000;'>十不中</span>
<?} else{?>
<input name="type0" type="radio" value="10" onClick="javascript:location.href='liuhecai.php?action=k_wbz&ids=十不中';"><SPAN id=rtm1 STYLE='color:000000;'>十不中</span>
<?}?>
<?if ($ids=="十一不中"){?>
<input name="type0" type="radio" checked="checked" value="10" onClick="javascript:location.href='liuhecai.php?action=k_wbz&ids=十一不中';" ><SPAN id=rtm1 STYLE='color:ff0000;'>十一不中</span>
<?} else{?>
<input name="type0" type="radio" value="10" onClick="javascript:location.href='liuhecai.php?action=k_wbz&ids=十一不中';"><SPAN id=rtm1 STYLE='color:000000;'>十一不中</span>
<?}?>
<?if ($ids=="十二不中"){?>
<input name="type0" type="radio" checked="checked" value="10" onClick="javascript:location.href='liuhecai.php?action=k_wbz&ids=十二不中';" ><SPAN id=rtm1 STYLE='color:ff0000;'>十二不中</span>
<?} else{?>
<input name="type0" type="radio" value="10" onClick="javascript:location.href='liuhecai.php?action=k_wbz&ids=十二不中';"><SPAN id=rtm1 STYLE='color:000000;'>十二不中</span>
<?}?>
    
    
    </TD></TR>
 <!--  <TR vAlign=bottom class="tbtitle">
    <TD width="25%" height=17><B class=font_B>生肖尾数</B></TD>
    <TD align=middle width="25%">开奖时间：<?=date("H:i:s",strtotime($Current_KitheTable['nd'])) ?></TD>
    <TD align=middle width="35%">距离封盘时间：
    
      <span id="span_dt_dt"></span>
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
    <TD align=right width="25%"><SPAN class="Font_B" 
      id="Update_Time"></SPAN></TD></TR> --></TBODY></TABLE></td>
  </tr>
      </table>

<!-- <form  method="post" target="k_meml_h" onSubmit="return SubChk();"  action="liuhecai.php?action=k_wbzsave" name="lt_form"  style="height:580px;" > -->
 <form  name="lt_form" id="lt_form" method="get" action="main_left.php" style="height:640px;" target="k_meml" onsubmit="return SubChk();">
 <input type="hidden" name="title_3d" value="全不中" id="title_3d">
 <input type="hidden" name="leixing" value="全不中" id="leixing">
 <input type="hidden" name="fc_type" value="9" id="fc_type">
 <input type="hidden" name="ids" value="<?=$ids ?>" id="ids">
 <input type="hidden" name="action" value="k_wbzsave" id="action"> 
 <input type="hidden" name="class2" value="<?=$ids?>" id="class2">

 
<TABLE cellSpacing=1 cellPadding=0 style="width:780" border=0 class="game_table all_body" style="display:none;">
                              <tr class="tbtitle2">
    <th>号码</th>
    <th>赔率</th>
    <th>勾选</th>
    <th>号码</th>
    <th>赔率</th>
    <th>勾选</th>
    <th>号码</th>
    <th>赔率</th>
    <th>勾选</th>
    <th>号码</th>
    <th>赔率</th>
    <th>勾选</th>
    <th>号码</th>
    <th>赔率</th>
    <th>勾选</th>
  </tr>
                                <TR>
                              
                                  <td align="center" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">01</span></td>
      <td height="25" align="center" valign="middle" class="ball_ff_1"><b><span id="bl00"> <?=$data_bet['0']['rate'] ?></span><span> </span></b></td>
                                 <TD align="center" class="ball_ff_1"><INPUT onclick=SubChkBox(this,1) 
                        type=checkbox value=01 name=num1 id=num1></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b">11</td>
      <td height="25" align="center" class="ball_ff_1"><b><span id=bl10> <?=$data_bet['10']['rate'] ?></span></b></td>
                                 <TD align="center" class="ball_ff_1"><INPUT onclick=SubChkBox(this,11) 
                        type=checkbox value=11 name=num11></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b">21</td>
      <td height="25" align="center" class="ball_ff_1"><b><span id=bl20><?=$data_bet['20']['rate'] ?></span></b></td>
                                  <TD align="center" class="ball_ff_1"><INPUT onclick=SubChkBox(this,21) 
                        type=checkbox value=21 name=num21></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b">31</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl30> <?=$data_bet['30']['rate'] ?></span></b></td>
                                 <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,31) 
                        type=checkbox value=31 name=num31></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b">41</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl40> <?=$data_bet['40']['rate'] ?></span></b></td>
                                 <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,41) 
                        type=checkbox value=41 name=num41></TD>
                                </TR>
                                <TR>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b">02</td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl1> <?=$data_bet['1']['rate'] ?></span><span> </span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT name=num2 
                        type=checkbox onclick=SubChkBox(this,2) value=02 ></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b">12</td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl11> <?=$data_bet['11']['rate'] ?></span><span> </span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,12) 
                        type=checkbox value=12 name=num12></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b">22</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1" class="ball_ff_1"><b><span id=bl21><?=$data_bet['21']['rate'] ?></span></b></td>
                                 <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,22) 
                        type=checkbox value=22 name=num22></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b">32</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl31><?=$data_bet['31']['rate'] ?></span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,32) 
                        type=checkbox value=32 name=num32></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b">42</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl41><?=$data_bet['41']['rate'] ?></span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,42) 
                        type=checkbox value=42 name=num42></TD>
                                </TR>
                                <TR>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">03</span></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl2> <?=$data_bet['2']['rate'] ?></span><span> </span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,3) 
                        type=checkbox value=03 name=num3></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">13</span></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl12> <?=$data_bet['12']['rate'] ?></span><span> </span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,13) 
                        type=checkbox value=13 name=num13></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">23</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl22><?=$data_bet['22']['rate'] ?></span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,23) 
                        type=checkbox value=23 name=num23></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">33</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl32><?=$data_bet['32']['rate'] ?></span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,33) 
                        type=checkbox value=33 name=num33></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">43</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl42><?=$data_bet['42']['rate'] ?></span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,43) 
                        type=checkbox value=43 name=num43></TD>
                                </TR>
                                <TR>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">04</span></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl3> <?=$data_bet['3']['rate'] ?></span><span> </span></b></td>
                                 <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,4) 
                        type=checkbox value=04 name=num4></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">14</span></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl13> <?=$data_bet['13']['rate'] ?></span><span> </span></b></td>
                                 <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,14) 
                        type=checkbox value=14 name=num14></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">24</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl23><?=$data_bet['23']['rate'] ?></span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,24) 
                        type=checkbox value=24 name=num24></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">34</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl33><?=$data_bet['33']['rate'] ?></span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,34) 
                        type=checkbox value=34 name=num34></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">44</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl43><?=$data_bet['43']['rate'] ?></span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,44) 
                        type=checkbox value=44 name=num44></TD>
                                </TR>
                                <TR>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">05</span></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl4> <?=$data_bet['4']['rate'] ?></span><span> </span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,5) 
                        type=checkbox value=05 name=num5></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">15</span></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl14> <?=$data_bet['14']['rate'] ?></span><span> </span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,15) 
                        type=checkbox value=15 name=num15></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">25</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl24><?=$data_bet['24']['rate'] ?></span></b></td>
                                 <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,25) 
                        type=checkbox value=25 name=num25></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">35</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl34><?=$data_bet['34']['rate'] ?></span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,35) 
                        type=checkbox value=35 name=num35></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">45</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl44><?=$data_bet['44']['rate'] ?></span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,45) 
                        type=checkbox value=45 name=num45></TD>
                                </TR>
                                <TR>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">06</span></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl5> <?=$data_bet['5']['rate'] ?></span><span> </span></b></td>
                                <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,6) 
                        type=checkbox value=06 name=num6></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">16</span></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl15> <?=$data_bet['15']['rate'] ?></span><span> </span></b></td>
                                 <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,16) 
                        type=checkbox value=16 name=num16></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">26</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl25><?=$data_bet['25']['rate'] ?></span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,26) 
                        type=checkbox value=26 name=num26></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">36</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl35><?=$data_bet['35']['rate'] ?></span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,36) 
                        type=checkbox value=36 name=num36></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">46</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl45><?=$data_bet['45']['rate'] ?></span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><input onClick=SubChkBox(this,46) 
                        type=checkbox value=46 name=num46></TD>
                                </TR>
                                <TR>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b">07</td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl6> <?=$data_bet['6']['rate'] ?></span><span> </span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,7) 
                        type=checkbox value=07 name=num7></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b">17</td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl16> <?=$data_bet['16']['rate'] ?></span><span> </span></b></td>
                                 <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,17) 
                        type=checkbox value=17 name=num17></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b">27</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl26><?=$data_bet['26']['rate'] ?></span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,27) 
                        type=checkbox value=27 name=num27></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b">37</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl36><?=$data_bet['36']['rate'] ?></span></b></td>
                                 <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,37) 
                        type=checkbox value=37 name=num37></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b">47</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl46><?=$data_bet['46']['rate'] ?></span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,47) 
                        type=checkbox value=47 name=num47></TD>
                                </TR>
                                <TR>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b">08</td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl7> <?=$data_bet['7']['rate'] ?></span><span> </span></b></td>
                                 <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,8) 
                        type=checkbox value=08 name=num8></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b">18</td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl17> <?=$data_bet['17']['rate'] ?></span><span> </span></b></td>
                                 <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,18) 
                        type=checkbox value=18 name=num18></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b">28</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl27><?=$data_bet['27']['rate'] ?></span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,28) 
                        type=checkbox value=28 name=num28></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b">38</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl37><?=$data_bet['37']['rate'] ?></span></b></td>
                                 <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,38) 
                        type=checkbox value=38 name=num38></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b">48</td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl47><?=$data_bet['47']['rate'] ?></span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,48) 
                        type=checkbox value=48 name=num48></TD>
                                </TR>
                                <TR>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">09</span></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl8> <?=$data_bet['8']['rate'] ?></span><span> </span></b></td>
                                 <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,9) 
                        type=checkbox value=09 name=num9></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">19</span></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl18> <?=$data_bet['18']['rate'] ?></span><span> </span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,19) 
                        type=checkbox value=19 name=num19></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">29</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl28><?=$data_bet['28']['rate'] ?></span></b></td>
                                 <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,29) 
                        type=checkbox value=29 name=num29></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">39</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl38><?=$data_bet['38']['rate'] ?></span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,39) 
                        type=checkbox value=39 name=num39></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">49</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl48><?=$data_bet['48']['rate'] ?></span></b></td>
                                 <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,49) 
                        type=checkbox value=49 name=num49></TD>
                                </TR>
                                <TR>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">10</span></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl9> <?=$data_bet['9']['rate'] ?></span><span> </span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,10) 
                        type=checkbox value=10 name=num10></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">20</span></td>
      <td height="25" align="center" valign="middle" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl19> <?=$data_bet['19']['rate'] ?></span><span> </span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,20) 
                        type=checkbox value=20 name=num20></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">30</span></td>
      <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl29><?=$data_bet['29']['rate'] ?></span></b></td>
                                 <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT onclick=SubChkBox(this,30) 
                        type=checkbox value=30 name=num30></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_b">40</span></td>
         <td height="25" align="center" bgcolor="#ffffff" class="ball_ff_1"><b><span id=bl39><?=$data_bet['39']['rate'] ?></span></b></td>
                                  <TD align="center" bgcolor="ffffff" class="ball_ff_1"><INPUT  onclick=SubChkBox(this,40) 
                        type=checkbox value=40 name=num40></TD><input name="rtype" type="hidden" id="rtype" value="<?=$ids?>">
								  <input name="rrtype" type="hidden" id="rrtype" value="6"><input name="pabc" type="hidden" id="pabc" value="1">
                                  <TD colSpan=3 align=middle bgcolor="#FFFFFF" class="ball_bg">                         </TD>
                                </TR>
<tr>
<td colspan="15" align="center" bgcolor="#FFFFFF" height="35" >
<!-- 金额: -->
<!-- <input  onKeyPress="return CheckKey();" 
                        onBlur="return CountGold(this,'blur','SP');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="CountGold(this,'focus');;"     style="HEIGHT: 22px" name="jq" type="text" id="jq" size="5"> -->
								  
                                      <INPUT type="submit" value="投注"  class="button_a" name="btnSubmit" >
									  &nbsp;&nbsp;
                                      <input type="reset"  onclick="javascript:location.reload();" class="button_a" name="Submit3" value="重设" />         </td></tr>								
                              </TBODY>
                          </TABLE>
                             
   </TD>
                        </TR>
                      </TBODY>
                    </TABLE>
                </TD>
              </TR>
            
            </TBODY>
            
</TABLE>
    </td>
  </tr>
</table>
      <INPUT type=hidden value=0 name=gold_all>
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
		   for(var i=0;i<49;i++)
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
 makeRequest('liuhecai.php?action=server&class1=全不中&class2=<?=$ids?>')
 </script>
u