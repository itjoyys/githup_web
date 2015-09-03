<? if(!defined('PHPYOU')) {
	exit('非法进入');
}

$_GET['leixing']='特码生肖';
$_GET['leixing_bet']='生肖';


$ids="特肖";

$xc=18;

$XF=23;
function ka_kk1($i){  

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
	if (eval(document.all.allgold.innerHTML)<=0 )
	{
		alert("请输入下注金额!!");
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
}



function CountGold(gold,type,rtype,bb,ffb){

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
  <TBODY>
    <?php include("common_qishu.php"); ?>

<form  name="lt_form"  method="get" action="main_left.php" target="k_meml" style="height:580px;background-color:#f1f1f1" onsubmit="return ChkSubmit();">
<input type="hidden" name="title_3d" value="生肖" id="title_3d">
<input type="hidden" name="leixing" value="特码生肖" id="leixing">
 <input type="hidden" name="fc_type" value="9" id="fc_type">
 <input type="hidden" name="ids" value="<?=$ids ?>" id="ids">
 <input type="hidden" name="action" value="n1" id="action"> 
 <input type="hidden" name="class2" value="<?=$ids?>" id="class2">

<TABLE cellSpacing='1' style="width:780px" cellPadding='0'  border='0' class="game_table all_body" style="display:none;"  >
<tr class="tbtitle2">
    <th colspan="8"><div  style="float:left;line-height:30px;text-align:center;width:100%"><?=$_GET['leixing'] ?></div>

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
	<tr class="Ball_tr_H">
      <td width="41" height="35" align="center"   class="ball_bg"><span rate="true3" class="STYLE4"><?=$drop_table[$I][0]?></span></td>
      <td width="50" height="25" align="center" valign="middle" class="ball_ff"><b><span rate="true3" id="bl<? 
        if($I==0){
          echo '00';
        }else{
          echo $I;
        }
        
      ?>  ">  <?php echo $data_bet[$I]['rate']; ?></span><span rate="true3"> </span></b></td>

      <td width="55" height="25" align="center" class="ball_ff" bgcolor="#FFFFFF"><input type="text" onKeyPress="return CheckKey();" 
                        onBlur="this.className='inp1';return CountGold(this,'blur','SP','<?=ka_kk1($drop_table[$I][0])?>','1');" 
                        onKeyUp="return CountGold(this,'keyup');" 
                        onFocus="this.className='inp1m';CountGold(this,'focus');;" 
      style="height:18px"  class="" size="4" type='text' js='js' name="Num_<?=$I+1?>"  id="Num_<?=$I+1?>" />
          <input name="class3_<?=$I+1?>" value="<?=$drop_table[$I][0]?>" type="hidden" >
          <input name="gb<?=$I+1?>" type="hidden"  value="0">
          <input name="xr_<?=$I?>" type="hidden" id="xr_<?=$I?>" value="0" >
          <input name="xrr_<?=$I+1?>" type="hidden"  value="0" ></td>
      <td height="25" bgcolor="f1f1f1"  ><table align=center><tr>
						<?
						
						
$result=$mysqli->query("Select m_number from ka_sxnumber where sx='".$drop_table[$I][0]."' order by id");
$image =$result->fetch_array();
						
		$xxm=explode(",",$image['m_number']);	
		$ssc=count($xxm);
		for ($j=0; $j<$ssc; $j=$j+1){			
				
					
					?>
    						<td align='middle'   height="32" width="32" class="ball_<?=Get_bs_Color(intval($xxm[$j]))?>"><?=$xxm[$j]?></td>
    					<? } ?>
	</tr></table>	</td>
	
	
	
	
	<td width="41" height="35" align="center"  class="ball_bg"><span rate="true3" class="STYLE4"><?=$drop_table[$I+6][0]?></span></td>
      <td width="50" height="25" align="center" valign="middle" class="ball_ff"><b><span rate="true3" id=bl<?=$I+6?>> <?=$data_bet[$I+6]['rate'] ?></span><span rate="true3"> </span></b></td>
      <td width="55" height="25" align="center" class="ball_ff" bgcolor="#FFFFFF"><input type="text" onKeyPress="return CheckKey();" 
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
<TABLE cellSpacing='0' class="game_tablex all_body" style="display:none;" cellPadding='0' style="width:780px" border='0'  >
        <tr class="hset2"> 
         <!-- onclick="return chkForm();" -->
          <td class="tbtitle" align="center" style="border:1px solid #bbafaf;border-top:none"><input name="btnSubmit"  type="submit"  class="button_a"  id="btnSubmit" value="投注" />&nbsp;&nbsp;
          <input type="reset" onclick="javascript:document.all.allgold.innerHTML =0;" class="button_a"  name="Submit3" value="重设" /></td>
        </tr>
      </table>
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
		   for(var i=0;i<12;i++)
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
