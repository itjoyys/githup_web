<?

if (! defined ( 'PHPYOU' )) {
	exit ( '非法进入' );
}
$_GET ['leixing'] = '尾数连';
$_GET ['leixing_bet'] = '尾数连';
$ids = $_GET ['ids'];
$type_min;
if ($ids == "")
	$ids = "二尾连中";

if ($ids == "二尾连中") {
	$xc = 56;
	$XF = 23;
	$type_min = 2;
}
if ($ids == "三尾连中") {
	$xc = 57;
	$XF = 23;
	$type_min = 3;
}
if ($ids == "四尾连中") {
	$xc = 58;
	$XF = 23;
	$type_min = 4;
}
if ($ids == "二尾连不中") {
	$xc = 59;
	$XF = 23;
	$type_min = 2;
}
if ($ids == "三尾连不中") {
	$xc = 60;
	$XF = 23;
	$type_min = 3;
}
if ($ids == "四尾连不中") {
	$xc = 61;
	$XF = 23;
	$type_min = 4;
}
function ka_kk1($i) {
}

?>

<link rel="stylesheet" href="../public/css/reset.css" type="text/css">
<link rel="stylesheet" href="../public/css/xp.css" type="text/css">
<script src="../public/js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="../public/js/bet.js" type="text/javascript"></script>
<SCRIPT type="text/javascript" src="imgs/activeX_Embed.js"></SCRIPT>

<?

// if ($Current_KitheTable[29]==0 or $Current_KitheTable[$XF]==0) {
?>
<script language="javascript">
// Make_FlashPlay('imgs/T0.swf','T','780','500');
</script>
<?
// exit;
// }

$result = $mysqli->query ( "Select class3,rate from c_odds_7 where class2='" . $ids . "' order by class3" );
$drop_table = array ();
$y = 0;
while ( $image = $result->fetch_array () ) {
	$y ++;
	// echo $image['class3'];
	array_push ( $drop_table, $image );
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

function checkInputBox(object){    
    var count = 0;   
    $("input[js='js']").each(function(){               
        if($(this).attr("checked")){   
            count++;   
        }  
    })   
    if(count >6){  
        alert("温馨提示:只能选择2-6个");  
        $(object).attr("checked",false)  
    } 
}

function ChkSubmit(){
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
    if(count < <?=$type_min ?>){  
        alert("温馨提示:至少选择"+<?=$type_min ?>+"个"); 
        return false;  
    }
        document.lt_form.submit();


}



function CountGold(gold,type,rtype,bb,ffb){

}
//-->
</SCRIPT>
<script>
  $(function(){   
    $("span").css('cursor','default');
  })
</script>

<style type="text/css">
<!--
body {
	/*margin-left: 10px;
	margin-top: 10px;*/
	
}

.STYLE1 {
	color: #333
}

.STYLE4 {
	color: #333333;
	font-weight: bold;
}
-->
</style>

<TABLE border="0" cellpadding="2" cellspacing="1"
	bordercolordark="#f9f9f9" bgcolor="#CCCCCC" width=780>
	<TBODY> <?php include("common_qishu.php"); ?>
  <TR class="tbtitle">
			<TD class="tbtitle4"><table width="100%" border="0" cellspacing="0"
					cellpadding="0">
					<tr>
						<TD align=right colSpan=3 style="height: 35px;">
							<div align="center">
 <?if ($ids=="二尾连中"){?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_wsl&ids=二尾连中';"
									class="button_a" onMouseOut="this.className='but_c1'"
									onMouseOver="this.className='but_c1M'"
									style="height: 25; background: #f00;";>
									<SPAN id=rtm1 STYLE='color: #fff;'>二尾连中</span>
								</button>&nbsp;
<?} else{?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_wsl&ids=二尾连中';"
									class="button_a" style="height: 25";>
									<SPAN id=rtm1 STYLE='color: #fff;'>二尾连中</span>
								</button>&nbsp;
<?}?>
 <?if ($ids=="三尾连中"){?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_wsl&ids=三尾连中';"
									class="button_a" onMouseOut="this.className='but_c1'"
									onMouseOver="this.className='but_c1M'"
									style="height: 25; background: #f00;";>
									<SPAN id=rtm1 STYLE='color: #fff;'>三尾连中</span>
								</button>&nbsp;
<?} else{?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_wsl&ids=三尾连中';"
									class="button_a" style="height: 25";>
									<SPAN id=rtm1 STYLE='color: #fff;'>三尾连中</span>
								</button>&nbsp;
<?}?>
 <?if ($ids=="四尾连中"){?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_wsl&ids=四尾连中';"
									class="button_a" onMouseOut="this.className='but_c1'"
									onMouseOver="this.className='but_c1M'"
									style="height: 25; background: #f00;";>
									<SPAN id=rtm1 STYLE='color: #fff;'>四尾连中</span>
								</button>&nbsp;
<?} else{?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_wsl&ids=四尾连中';"
									class="button_a" style="height: 25";>
									<SPAN id=rtm1 STYLE='color: #fff;'>四尾连中</span>
								</button>&nbsp;
<?}?>
 <!-- <hr  style="height:1px;"/> -->
 <?if ($ids=="二尾连不中"){?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_wsl&ids=二尾连不中';"
									class="button_a" onMouseOut="this.className='but_c1'"
									onMouseOver="this.className='but_c1M'"
									style="height: 25; background: #f00;";>
									<SPAN id=rtm1 STYLE='color: #fff;'>二尾连不中</span>
								</button>&nbsp;
<?} else{?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_wsl&ids=二尾连不中';"
									class="button_a" style="height: 25";>
									<SPAN id=rtm1 STYLE='color: #fff;'>二尾连不中</span>
								</button>&nbsp;
<?}?>
 <?if ($ids=="三尾连不中"){?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_wsl&ids=三尾连不中';"
									class="button_a" onMouseOut="this.className='but_c1'"
									onMouseOver="this.className='but_c1M'"
									style="height: 25; background: #f00;";>
									<SPAN id=rtm1 STYLE='color: #fff;'>三尾连不中</span>
								</button>&nbsp;
<?} else{?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_wsl&ids=三尾连不中';"
									class="button_a" style="height: 25";>
									<SPAN id=rtm1 STYLE='color: #fff;'>三尾连不中</span>
								</button>&nbsp;
<?}?>
 <?if ($ids=="四尾连不中"){?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_wsl&ids=四尾连不中';"
									class="button_a" onMouseOut="this.className='but_c1'"
									onMouseOver="this.className='but_c1M'"
									style="height: 25; background: #f00;";>
									<SPAN id=rtm1 STYLE='color: #fff;'>四尾连不中</span>
								</button>&nbsp;
<?} else{?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_wsl&ids=四尾连不中';"
									class="button_a" style="height: 25">
									<SPAN id=rtm1 STYLE='color: #fff;'>四尾连不中</span>
								</button>&nbsp;
<?}?>

           </div>
						</TD>
					</TR>
					<!-- <TR vAlign=bottom class="tbtitle">
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
    <TD align=right width="25%"><SPAN class=Font_B 
      id=Update_Time></SPAN></TD></TR> -->
	
	</TBODY>
</TABLE>
</td>
</tr>
</table>

<!-- <form  name="lt_form" target="k_meml_h" method="post" action="liuhecai.php?action=k_wslsave&class2=<?=$ids?>" style="height:555px;"> -->
<form name="lt_form" id="lt_form" method="get" action="main_left.php"
	style="height: 640px;" target="k_meml" onsubmit="return ChkSubmit();">
	<input type="hidden" name="title_3d" value="尾数连" id="title_3d"> <input
		type="hidden" name="leixing" value="尾数连" id="leixing"> <input
		type="hidden" name="fc_type" value="9" id="fc_type"> <input
		type="hidden" name="ids" value="<?=$ids ?>" id="ids"> <input
		type="hidden" name="action" value="k_wslsave" id="action"> <input
		type="hidden" name="class2" value="<?=$ids?>" id="class2">




	<TABLE cellSpacing=1 cellPadding=0 style="width: 780" border=0
		class="game_table all_body" style="display:none;">
		<tr class="tbtitle2">
			<td width="41" class="td_caption_1" height="28" align="center"
				nowrap="nowrap"><span class="STYLE54 STYLE1 STYLE1"> 尾</span></td>
			<td width="50" class="td_caption_1" align="center" nowrap="nowrap"><span
				class="STYLE54 STYLE1 STYLE1">赔率</span></td>
			<td width="55" class="td_caption_1" align="center" nowrap="nowrap"><span
				class="STYLE54 STYLE1 STYLE1">勾选</span></td>
			<td height="28" class="td_caption_1" align="center" nowrap="nowrap"><span
				class="STYLE1">号码</span></td>
			<td width="41" class="td_caption_1" height="28" align="center"
				nowrap="nowrap"><span class="STYLE54 STYLE1 STYLE1"> 尾</span></td>
			<td width="50" class="td_caption_1" align="center" nowrap="nowrap"><span
				class="STYLE54 STYLE1 STYLE1">赔率</span></td>
			<td width="55" class="td_caption_1" align="center" nowrap="nowrap"><span
				class="STYLE54 STYLE1 STYLE1">勾选</span></td>
			<td height="28" class="td_caption_1" align="center" nowrap="nowrap"><span
				class="STYLE1">号码</span></td>
		</tr>
    <?
				
				for($I = 0; $I < 5; $I = $I + 1) {
					?>
	<tr class="Ball_tr_H">
			<td width="41" height="35" align="center" class="ball_bg"><span
				class="STYLE4"><?=$drop_table[$I][0]?>尾</span></td>
			<td width="50" height="25" align="center" valign="middle"
				class="ball_ff_1"><b> <span
					id="bl<?
					if ($I == 0) {
						echo '00';
					} else {
						echo $I;
					}
					
					?>  "> 

      <?=$drop_table[$I]['rate'] ?></span><span> </span></b></td>
			<td width="55" height="25" align="center" bgcolor="#FFFFFF"
				class="ball_ff_1"><input type="checkbox" onclick="checkInputBox(this)" js="js" name="num<?=$I+1?>"
				value="<?=$drop_table[$I][0]?>" id="num<?=$I+1?>"></td>
			<td height="25" bgcolor="f1f1f1" class="ball_ff_1"><table align=left>
					<tr>
						<?
					
					$result = $mysqli->query ( "Select m_number from ka_sxnumber where sx='" . $drop_table [$I] [0] . "' order by id" );
					$image = $result->fetch_array ();
					
					$xxm = explode ( ",", $image ['m_number'] );
					$ssc = count ( $xxm );
					for($j = 0; $j < $ssc; $j = $j + 1) {
						
						?>
    						<td align=middle height="32" width="32"
							class="ball_<?=Get_bs_Color(intval($xxm[$j]))?>"><?=$xxm[$j]?></td>
    					<? } ?>
	</tr>
				</table></td>




			<td width="41" height="35" align="center" class="ball_bg"><span
				class="STYLE4"><?=$drop_table[$I+5][0]?>尾</span></td>
			<td width="50" height="25" align="center" valign="middle"
				class="ball_ff_1"><b><span id=bl <?=$I+5?>> <?=$drop_table[$I+5]['rate'] ?></span><span>
				</span></b></td>
			<td width="55" height="25" align="center" bgcolor="#FFFFFF"
				class="ball_ff_1"><input onclick="checkInputBox(this)" js="js" type="checkbox" name="num<?=$I+6?>"
				value="<?=$drop_table[$I+5][0]?>" id="num<?=$I+6?>"></td>
			<td height="25" bgcolor="f1f1f1" class="ball_ff_1"><table align=left>
					<tr>
			<?
					$result = $mysqli->query ( "Select m_number from ka_sxnumber where sx='" . $drop_table [$I + 5] [0] . "' order by id" );
					$image = $result->fetch_array ();
					
					$xxm = explode ( ",", $image ['m_number'] );
					$ssc = count ( $xxm );
					for($j = 0; $j < $ssc; $j = $j + 1) {
						
						?>
    						<td align=middle height="32" width="32"
							class="ball_<?=Get_bs_Color(intval($xxm[$j]))?>"><?=$xxm[$j]?></td>
    					<? } ?>
	</tr>
				</table></td>



		</tr>
	
	
	

	
	<?
				}
				?>
	 	<tr>
			<td height="35" colspan="8" align="center" bgcolor="#FFFFFF"><table
					border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td align="center" colspan="2">
							<!-- 下注金额： -->
						</td>
						<!-- <td width="44" align="left">
		      <input  onkeypress="return CheckKey();" 
                        onblur="return CountGold(this,'blur','SP');" 
                        onkeyup="return CountGold(this,'keyup');" 
                        onfocus="CountGold(this,'focus');;" style="height:22px"  name="Num_1" type="text" id="Num_1" size="6"></td> -->
						<td width="188" align="left">&nbsp;&nbsp;&nbsp;<input
							name="btnSubmit" onclick="return ChkSubmit();" type="submit"
							class="button_a" id="btnSubmit" value="投注" />&nbsp;&nbsp;
							&nbsp;&nbsp;<input type="reset" class="button_a"
							onclick="javascript:location.reload();" name="Submit3" value="重设" /></td>
					</tr>
				</table></td>
		</tr>
		<INPUT type=hidden value=0 name=gold_all>
	</table>
</form>
<INPUT type="hidden" value=0 name=total_gold id="total_gold">






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
		   for(var i=0;i<10;i++)
{	   
		   arrTmp = arrResult[i].split("@@@");
		   


num1 = arrTmp[0]; //字段num1的值
num2 = arrTmp[1]; //字段num2的值
num3 = arrTmp[2]; //字段num1的值
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
 makeRequest('liuhecai.php?action=server&class1=尾数连&class2=<?=$ids?>')
 </script>

