<?
$_GET ['leixing'] = '生肖连';
$_GET ['leixing_bet'] = '生肖连';

$ids = $_GET ['ids'];
if ($ids == "")
	$ids = "二肖连中";

if ($ids == "二肖连中") {
	$xc = 48;
	$XF = 23;
}
if ($ids == "三肖连中") {
	$xc = 49;
	$XF = 23;
}
if ($ids == "四肖连中") {
	$xc = 50;
	$XF = 23;
}
if ($ids == "五肖连中") {
	$xc = 51;
	$XF = 23;
}
if ($ids == "二肖连不中") {
	$xc = 52;
	$XF = 23;
}
if ($ids == "三肖连不中") {
	$xc = 53;
	$XF = 23;
}
if ($ids == "四肖连不中") {
	$xc = 54;
	$XF = 23;
}
function ka_kk1($i) {
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

$result = $mysqli->query ( "Select class3,rate from c_odds_7 where class2='" . $ids . "' order by ID" );
$drop_table = array ();
$y = 0;
while ( $image = $result->fetch_array () ) {
	$y ++;
	// echo $image['class3'];
	array_push ( $drop_table, $image );
}

?>



<SCRIPT language=JAVASCRIPT>
var ids='<?=$_GET['ids'] ?>';

if (ids=="二肖连中") { 
type_min = 2;
type_nums = 2;  

}else if (ids=="三肖连中"){ 
	type_min = 3;
	type_nums = 3;  
	}
else if (ids=="四肖连中"){ 
type_min = 4;
type_nums = 4;  
}else if (ids=="五肖连中"){ 
	type_min = 5;
	type_nums = 5;  
}else if (ids=="二肖连不中"){ 
	type_min = 2;
	type_nums = 2;  
}else if (ids=="三肖连不中"){ 
	type_min = 3;
	type_nums = 3;  
}else if (ids=="四肖连不中"){ 
	type_min = 4;
	type_nums = 4;  
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

//判断勾选 至少需要2个
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
    if(count <type_min){  
        alert("温馨提示:至少选择"+type_min+"个"); 
        return false;  
    }
}
</SCRIPT>

<script>
  $(function(){   
    $("span").css('cursor','default');
  })
</script>
<style type="text/css">
<!--
body {
	/*	margin-left: 10px;
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
						<!-- <td height=25><SPAN id=Lottery_Type_Name>当前期数: </SPAN>【第<?=$Current_Kithe_Num?>期】 <span id=allgold style="display:none">0</span></TD> -->
						<TD align=right colSpan=3 class="tbtitle4" style="height: 35px;">

							<div align="center">
 <?if ($ids=="二肖连中"){?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_sxt2&ids=二肖连中';"
									class="button_a" onMouseOut="this.className='but_c1'"
									onMouseOver="this.className='but_c1M'"
									style="height: 25; background: #f00;";>
									<SPAN id=rtm1 STYLE='color: #fff;'>二肖连中</span>
								</button>&nbsp;
<?} else{?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_sxt2&ids=二肖连中';"
									class="button_a" style="height: 25";>
									<SPAN id=rtm1 STYLE='color: #fff;'>二肖连中</span>
								</button>&nbsp;
<?}?>
 <?if ($ids=="三肖连中"){?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_sxt2&ids=三肖连中';"
									class="button_a" onMouseOut="this.className='but_c1'"
									onMouseOver="this.className='but_c1M'"
									style="height: 25; background: #f00;";>
									<SPAN id=rtm1 STYLE='color: #fff;'>三肖连中</span>
								</button>&nbsp;
<?} else{?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_sxt2&ids=三肖连中';"
									class="button_a" style="height: 25";>
									<SPAN id=rtm1 STYLE='color: #fff;'>三肖连中</span>
								</button>&nbsp;
<?}?>
 <?if ($ids=="四肖连中"){?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_sxt2&ids=四肖连中';"
									class="button_a" onMouseOut="this.className='but_c1'"
									onMouseOver="this.className='but_c1M'"
									style="height: 25; background: #f00;";>
									<SPAN id=rtm1 STYLE='color: #fff;'>四肖连中</span>
								</button>&nbsp;
<?} else{?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_sxt2&ids=四肖连中';"
									class="button_a" style="height: 25";>
									<SPAN id=rtm1 STYLE='color: #fff;'>四肖连中</span>
								</button>&nbsp;
<?}?>
 <?if ($ids=="五肖连中"){?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_sxt2&ids=五肖连中';"
									class="button_a" onMouseOut="this.className='but_c1'"
									onMouseOver="this.className='but_c1M'"
									style="height: 25; background: #f00;";>
									<SPAN id=rtm1 STYLE='color: #fff;'>五肖连中</span>
								</button>&nbsp;
<?} else{?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_sxt2&ids=五肖连中';"
									class="button_a" style="height: 25";>
									<SPAN id=rtm1 STYLE='color: #fff;'>五肖连中</span>
								</button>&nbsp;
<?}?> <!-- <br><hr style="height:1px;" /> -->
 <?if ($ids=="二肖连不中"){?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_sxt2&ids=二肖连不中';"
									class="button_a" onMouseOut="this.className='but_c1'"
									onMouseOver="this.className='but_c1M'"
									style="height: 25; background: #f00;";>
									<SPAN id=rtm1 STYLE='color: #fff;'>二肖连不中</span>
								</button>&nbsp;
<?} else{?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_sxt2&ids=二肖连不中';"
									class="button_a" style="height: 25";>
									<SPAN id=rtm1 STYLE='color: #fff;'>二肖连不中</span>
								</button>&nbsp;
<?}?>
 <?if ($ids=="三肖连不中"){?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_sxt2&ids=三肖连不中';"
									class="button_a" onMouseOut="this.className='but_c1'"
									onMouseOver="this.className='but_c1M'"
									style="height: 25; background: #f00;";>
									<SPAN id=rtm1 STYLE='color: #fff;'>三肖连不中</span>
								</button>&nbsp;
<?} else{?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_sxt2&ids=三肖连不中';"
									class="button_a" style="height: 25";>
									<SPAN id=rtm1 STYLE='color: #fff;'>三肖连不中</span>
								</button>&nbsp;
<?}?>
 <?if ($ids=="四肖连不中"){?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_sxt2&ids=四肖连不中';"
									class="button_a" onMouseOut="this.className='but_c1'"
									onMouseOver="this.className='but_c1M'"
									style="height: 25; background: #f00;";>
									<SPAN id=rtm1 STYLE='color: #fff;'>四肖连不中</span>
								</button>&nbsp;
<?} else{?>
<button
									onClick="javascript:location.href='liuhecai.php?action=k_sxt2&ids=四肖连不中';"
									class="button_a" style="height: 25";>
									<SPAN id=rtm1 STYLE='color: #fff;'>四肖连不中</span>
								</button>&nbsp;
<?}?>

           </div>
						</TD>
					</TR>
					</TBODY>
				</TABLE></td>
		</tr>

</table>

<!-- <form  name="lt_form"  method="post" target="k_meml_h" action="liuhecai.php?action=k_sxt2save&class2=<?=$ids?>" style="height:555px;"> -->
<form name="lt_form" id="lt_form" method="get" action="main_left.php"
	style="height: 640px;" target="k_meml" onsubmit="return ChkSubmit();">
	<input type="hidden" name="title_3d" value="生肖连" id="title_3d"> <input
		type="hidden" name="leixing" value="生肖连" id="leixing"> <input
		type="hidden" name="fc_type" value="9" id="fc_type"> <input
		type="hidden" name="ids" value="<?=$ids ?>" id="ids"> <input
		type="hidden" name="action" value="k_sxt2save" id="action"> <input
		type="hidden" name="class2" value="<?=$ids?>" id="class2">


	<TABLE cellSpacing=1 cellPadding=0 style="width: 780" border=0
		class="game_table all_body" style="display:none;">

		<tr class="tbtitle">
			<td width="41" class=td_caption_1 height="28" align="center"
				nowrap="nowrap"><span class="STYLE54 STYLE1 STYLE1"> 肖</span></td>
			<td width="50" class=td_caption_1 align="center" nowrap="nowrap"><span
				class="STYLE54 STYLE1 STYLE1">赔率</span></td>
			<td width="55" class=td_caption_1 align="center" nowrap="nowrap"><span
				class="STYLE54 STYLE1 STYLE1">勾选</span></td>
			<td height="28" class=td_caption_1 align="center" nowrap="nowrap"><span
				class="STYLE1">号码</span></td>
			<td width="41" class=td_caption_1 height="28" align="center"
				nowrap="nowrap"><span class="STYLE54 STYLE1 STYLE1">肖</span></td>
			<td width="50" class=td_caption_1 align="center" nowrap="nowrap"><span
				class="STYLE54 STYLE1 STYLE1">赔率</span></td>
			<td width="55" class=td_caption_1 align="center" nowrap="nowrap"><span
				class="STYLE54 STYLE1 STYLE1">勾选</span></td>
			<td height="28" class=td_caption_1 align="center" nowrap="nowrap"><span
				class="STYLE1">号码</span></td>
		</tr>
    <?
				
				for($I = 0; $I <= 5; $I = $I + 1) {
					
					?>
	<tr class="Ball_tr_H">
			<td width="41" height="35" align="center" class="ball_bg"><span
				class="STYLE4"><?=$drop_table[$I][0]?></span></td>
			<td width="50" height="25" align="center" valign="middle"
				class="ball_ff_1"><b> <span
					id="bl<?
					if ($I == 0) {
						echo '00';
					} else {
						echo $I;
					}
					
					?>  ">  <?=$data_bet[$I]['rate'] ?></span><span> </span></b></td>
			<td width="55" height="25" align="center" bgcolor="#FFFFFF"
				class="ball_ff_1"><input type="checkbox" js="js" name="num<?=$I+1?>"
				value="<?=$drop_table[$I][0]?>" id="num<?=$I+1?>"
				onclick="checkInputBox(this)"></td>
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
				class="STYLE4"><?=$drop_table[$I+6][0]?></span></td>
			<td width="50" height="25" align="center" valign="middle"
				class="ball_ff_1"><b><span id=bl <?=$I+6?>> <?=$data_bet[$I+6]['rate'] ?></span><span>
				</span></b></td>
			<td width="55" height="25" align="center" class="ball_ff_1"
				bgcolor="#FFFFFF"><input type="checkbox" name="num<?=$I+7?>"
				value="<?=$drop_table[$I+6][0]?>" id="num<?=$I+7?>" js="js"
				onclick="checkInputBox(this)"></td>
			<td height="25" bgcolor="f1f1f1" class="ball_ff_1"><table align=left>
					<tr>
						<?
					
					$result = $mysqli->query ( "Select m_number from ka_sxnumber where sx='" . $drop_table [$I + 6] [0] . "' order by id" );
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
						<!--  <td width="44" align="left">
		      <input  onkeypress="return CheckKey();" 
                        onblur="return CountGold(this,'blur','SP');" 
                        onkeyup="return CountGold(this,'keyup');" 
                        onfocus="CountGold(this,'focus');;"     style="HEIGHT: 22px" name="Num_1" type="text" id="Num_1" size="6"></td> -->
						<td width="188" align="left">&nbsp;&nbsp;&nbsp; <input
							name="btnSubmit" type="submit" class="button_a" id="btnSubmit"
							value="投注" /> &nbsp;&nbsp;<input type="reset" class="button_a"
							name="Submit3" value="重设" /></td>
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
		   for(var i=0;i<12;i++)
{	   
		   arrTmp = arrResult[i].split("@@@");
		   


num1 = arrTmp[0]; //字段num1的值
num2 = arrTmp[1]; //字段num2的值
num3 = arrTmp[2]; //字段num1的值
num4 = arrTmp[3]; //字段num2的值
num5 = arrTmp[4]; //字段num2的值
num6 = arrTmp[5]; //字段num2的值



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
 makeRequest('liuhecai.php?action=server&class1=生肖连&class2=<?=$ids?>')
 </script>
e
