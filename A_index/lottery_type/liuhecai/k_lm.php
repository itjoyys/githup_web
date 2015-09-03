<? if(!defined('PHPYOU')) {
	exit('非法进入');
}


$_GET['leixing']='连码';
$_GET['leixing_bet']='连码';

$xc=20;

$XF=21;

$ids="连码";


function Get_sx_nx($rrr){
global $mysqli;
$result=$mysqli->query("Select id,m_number,sx From ka_sxnumber where  id='".$rrr."' Order By ID LIMIT 1");
$ka_Color1=$result->fetch_array();
$xxmnx=explode(",",$ka_Color1['m_number']);
return intval($xxmnx[0]);
}

?>




<script language="javascript">

function SubChkBox(obj,bmbm) {
	if(obj.checked == false)
	{
		cb_num--;
	}
	if(obj.checked == true)
	{
		if ( cb_num > type_nums )
		{
			alert("最多只能选择"+type_nums+"个号码");
			cb_num--;
			obj.checked = false;
		}
		cb_num++;
	}
	if($("input[name='pabc']").val()=="2")
	{
		if($("input[name='rrtype']").val()=="1")
		{
			if($("input[name='dm1']").val()=="")
			{
				$("input[name='num"+bmbm+"']").attr("disabled",true);
				$("input[name='dm1']").val(bmbm);
				//$("input[name='dm1']").attr("disabled",false);
			}else{
				if($("input[name='dm2']").val()=="")
				{
					$("input[name='num"+bmbm+"']").attr("disabled",true);
					$("input[name='dm2']").val(bmbm);
					//$("input[name='dm2']").attr("disabled",false);
				}
			}
		}
		else
		{
			if($("input[name='dm1']").val()=="")
			{
				$("input[name='num"+bmbm+"']").attr("disabled",true);
				$("input[name='dm1']").val(bmbm);
				//$("input[name='dm1']").attr("disabled",false);
			}
		}
	}
}
function ChkSubmit(obj){
    //设定『确定』键为反白
 	var type_min_limit = $("#limit_num").val();
	// document.all.btnSubmit.disabled = true;
	// alert($("#pabc").val());
	//判断正常的下注数
	if ($("#pabc").val() == 1 || $("#pabc").val() == 2) {
		var count = 0;
		$("input[type='checkbox']").each(function(){
			if($(this).attr("checked")==undefined){

			}else{
				count++ ;
			}
		});
		// alert(count);alert(type_min_limit);
	    if(count <type_min_limit){
	        alert("温馨提示:至少选择"+type_min_limit+"个");
	        return false;
	    }
	}


	if ($("#pabc").val() == 3) {
		if ($("#pan1").val() =="" || $("#pan2").val() ==""){
			alert('请选择生肖');
			return false;
		}
	}else if ($("#pabc").val() == 4) {
		if ($("#pan3").val() =="" || $("#pan4").val() ==""){
			alert('请选择尾数');
			return false;
		}

	}else if ($("#pabc").val() == 5) {
		if ($("#pan1").val() =="" || $("#pan3").val() ==""){
			alert('请选择生肖和尾数');
			return false;
		}

	}

	if ($("#rrtype").val() == 1) {
		if ($("#pabc").val() == 2) {
			if ($("#dm1").val() =="" || $("#dm2").val() ==""){
				  alert('请选择胆');
				  return false;
			}

		}
	}else if ($("#rrtype").val() == 2) {
		if ($("#pabc").val() == 2) {
			if ($("#dm1").val() =="" ){
				  alert('请选择胆');
				  return false;
			}
		}
	}

	var checkCount = 0;
	var checknum = obj.elements.length;
	var rtypechk = 0;


	for(i=0; i<obj.rtype.length; i++) {
		if (obj.rtype[i].checked) {
			rtypechk ++;
		}
	}

	if (rtypechk == 0) {
	  alert('请选择类别');
	  return false;
	}
	for(i=0; i<checknum; i++) {
		if (obj.elements[i].checked) {
			if(obj.elements[i].name.indexOf('num') >= 0){ checkCount ++;}
		}
	}


		    document.lt_form.submit();
		     //self.location.reload();

}


</script>


<?
$result=$mysqli->query("Select class3,rate from c_odds_7 where class1='连码' order by ID");
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


 <style type="text/css">
<!--
.STYLE2 {color: #333}
.STYLE3 {color: #333}
body {
/*	margin-left: 10px;
	margin-top: 10px;*/
}
-->
 </style>
 <body oncontextmenu="return false"   onselect="document.selection.empty()" oncopy="document.selection.empty()"
>
<noscript>
<!-- <iframe scr=″*.htm″></iframe> -->
</noscript>

<SCRIPT language=javascript>
<!--
if(self == top) location = '/';
var type_nums = 10;  //预设为 3中2
var type_min = 3;
var cb_num = 1;
var mess1 =  '最少选择';
var mess11 = '个数字';
var mess2 =  '最多选择10个数字';
var mess = mess2;

function select_types(type,limit) {
$("#limit_num").val(limit);

cb_num=1

s1="rrtype"
document.all[s1].value = 1;


s2="dm1"
document.all[s2].value ="" ;

s3="dm2"
document.all[s3].value ="";

	if (type == 1 || type == 2 || type == 6) {
		type_nums = 10;
		type_min = 3;

		for(i=1; i<13; i++) {

			MM_changeProp('pan1'+i,'','disabled','disabled','INPUT/CHECKBOX')
			MM_changeProp('pan2'+i,'','disabled','disabled','INPUT/CHECKBOX')
			MM_changeProp('pan1'+i,'','checked','0','INPUT/CHECKBOX')
			MM_changeProp('pan2'+i,'','checked','0','INPUT/CHECKBOX')
			//MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
		}
		for(i=0; i<10; i++) {

			MM_changeProp('pan3'+i,'','disabled','disabled','INPUT/CHECKBOX')
			MM_changeProp('pan4'+i,'','disabled','disabled','INPUT/CHECKBOX')
			MM_changeProp('pan3'+i,'','checked','0','INPUT/CHECKBOX')
			MM_changeProp('pan4'+i,'','checked','0','INPUT/CHECKBOX')
			//MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
		}
		MM_changeProp('pabc3','','disabled','disabled','INPUT/RADIO')
		MM_changeProp('pabc4','','disabled','disabled','INPUT/RADIO')
		MM_changeProp('pabc5','','disabled','disabled','INPUT/RADIO')

		for(i=1; i<50; i++) {

			MM_changeProp('num'+i,'','disabled','0','INPUT/CHECKBOX')
			MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
		}
		MM_changeProp('pabc','','checked','checked','INPUT/RADIO')

		a2.style.display = "";
		a3.style.display = "none";
		a4.style.display = "none";

	 for(i=1; i<6; i++) {
			if (i==1) {
			var pabc="pabc";
			document.all[pabc].value = 1;
			MM_changeProp('pabc'+i,'','checked','1','INPUT/RADIO')
			}else{
			MM_changeProp('pabc'+i,'','checked','0','INPUT/RADIO')
			}


		}



	} else {
	cb_num=1

	s1="rrtype"
document.all[s1].value = 2;

s2="dm1"
document.all[s2].value ="" ;

s3="dm2"
document.all[s3].value ="";

		type_nums = 10;
		type_min = 2;

		a2.style.display = "";
		a3.style.display = "";
		a4.style.display = "";

		MM_changeProp('pabc3','','disabled','0','INPUT/RADIO')
		MM_changeProp('pabc4','','disabled','0','INPUT/RADIO')
		MM_changeProp('pabc5','','disabled','0','INPUT/RADIO')

		for(i=1; i<50; i++) {

			MM_changeProp('num'+i,'','disabled','0','INPUT/CHECKBOX')
			MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
		}

		for(i=1; i<13; i++) {

			MM_changeProp('pan1'+i,'','disabled','disabled','INPUT/CHECKBOX')
			MM_changeProp('pan2'+i,'','disabled','disabled','INPUT/CHECKBOX')
			MM_changeProp('pan1'+i,'','checked','0','INPUT/CHECKBOX')
			MM_changeProp('pan2'+i,'','checked','0','INPUT/CHECKBOX')
			//MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
		}
		for(i=0; i<10; i++) {

			MM_changeProp('pan3'+i,'','disabled','disabled','INPUT/CHECKBOX')
			MM_changeProp('pan4'+i,'','disabled','disabled','INPUT/CHECKBOX')
			MM_changeProp('pan3'+i,'','checked','0','INPUT/CHECKBOX')
			MM_changeProp('pan4'+i,'','checked','0','INPUT/CHECKBOX')
			//MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
		}



		 for(i=1; i<6; i++) {
			if (i==1) {
			var pabc="pabc";
			document.all[pabc].value = 1;
			MM_changeProp('pabc'+i,'','checked','1','INPUT/RADIO')
			}else{
			MM_changeProp('pabc'+i,'','checked','0','INPUT/RADIO')
			}

		}

	}

// $("#rrtype").val()
}

function select_types1(type,i) {
if(i==1){
	document.getElementById("shengxiao_1").style.display = 'none';
	document.getElementById("shengxiao_2").style.display = 'none';
	document.getElementById("shengxiao_3").style.display = 'none';
	document.getElementById("shengxiao_4").style.display = 'none';
	}else if(i==2){
	document.getElementById("shengxiao_1").style.display = 'block';
	document.getElementById("shengxiao_2").style.display = 'block';
	document.getElementById("shengxiao_3").style.display = 'none';
	document.getElementById("shengxiao_4").style.display = 'none';
	}else if(i==3){
	document.getElementById("shengxiao_1").style.display = 'none';
	document.getElementById("shengxiao_2").style.display = 'none';
	document.getElementById("shengxiao_3").style.display = 'block';
	document.getElementById("shengxiao_4").style.display = 'block';
	}else if(i==4){
	document.getElementById("shengxiao_1").style.display = 'block';
	document.getElementById("shengxiao_2").style.display = 'none';
	document.getElementById("shengxiao_3").style.display = 'block';
	document.getElementById("shengxiao_4").style.display = 'none';
	}
cb_num=1
s2="dm1"
document.all[s2].value ="" ;

s3="dm2"
document.all[s3].value ="";
	if (type == 1 || type == 2  || type == 6) {

	for(i=1; i<50; i++) {

			MM_changeProp('num'+i,'','disabled','0','INPUT/CHECKBOX')
			MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
		}

		for(i=1; i<13; i++) {

			MM_changeProp('pan1'+i,'','disabled','disabled','INPUT/CHECKBOX')
			MM_changeProp('pan2'+i,'','disabled','disabled','INPUT/CHECKBOX')
			//MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
		}
		for(i=0; i<10; i++) {

			MM_changeProp('pan3'+i,'','disabled','disabled','INPUT/CHECKBOX')
			MM_changeProp('pan4'+i,'','disabled','disabled','INPUT/CHECKBOX')
			MM_changeProp('pan3'+i,'','checked','0','INPUT/CHECKBOX')
			MM_changeProp('pan4'+i,'','checked','0','INPUT/CHECKBOX')
			//MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
		}


	var i
		for(i=1; i<6; i++) {
			if (i==type) {

			}else{
			MM_changeProp('pabc'+i,'','checked','0','INPUT/RADIO')
			}


		}

	}

	if (type == 3 ) {


		for(i=1; i<50; i++) {

			MM_changeProp('num'+i,'','disabled','disabled','INPUT/CHECKBOX')
			MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
		}

		for(i=1; i<13; i++) {

			MM_changeProp('pan1'+i,'','disabled','0','INPUT/CHECKBOX');
			MM_changeProp('pan2'+i,'','disabled','0','INPUT/CHECKBOX');

			//MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
		}
		for(i=0; i<10; i++) {

			MM_changeProp('pan3'+i,'','disabled','disabled','INPUT/CHECKBOX')
			MM_changeProp('pan4'+i,'','disabled','disabled','INPUT/CHECKBOX')
			MM_changeProp('pan3'+i,'','checked','0','INPUT/CHECKBOX')
			MM_changeProp('pan4'+i,'','checked','0','INPUT/CHECKBOX')
			//MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
		}

		for(i=1; i<6; i++) {
			if (i==type) {

			}else{
			MM_changeProp('pabc'+i,'','checked','0','INPUT/RADIO')
			}


		}

		a3.style.display = "";
		a4.style.display = "";

	}

	if (type == 4 ) {

		for(i=1; i<50; i++) {

			MM_changeProp('num'+i,'','disabled','disabled','INPUT/CHECKBOX')
			MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
		}

	for(i=1; i<13; i++) {

			MM_changeProp('pan1'+i,'','disabled','disabled','INPUT/CHECKBOX')
			MM_changeProp('pan2'+i,'','disabled','disabled','INPUT/CHECKBOX')

			MM_changeProp('pan1'+i,'','checked','0','INPUT/CHECKBOX')
			MM_changeProp('pan2'+i,'','checked','0','INPUT/CHECKBOX')
			//MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
		}
		for(i=0; i<10; i++) {

			MM_changeProp('pan3'+i,'','disabled','0','INPUT/CHECKBOX')
			MM_changeProp('pan4'+i,'','disabled','0','INPUT/CHECKBOX')
			//MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
		}

		a3.style.display = "";
		a4.style.display = "";
	for(i=1; i<6; i++) {
			if (i==type) {

			}else{
			MM_changeProp('pabc'+i,'','checked','0','INPUT/RADIO')
			}


		}


	}

	if (type == 5 ) {

		for(i=1; i<50; i++) {

			MM_changeProp('num'+i,'','disabled','disabled','INPUT/CHECKBOX')
			MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
		}

	for(i=1; i<13; i++) {

			MM_changeProp('pan1'+i,'','disabled','0','INPUT/CHECKBOX')
			MM_changeProp('pan2'+i,'','disabled','disabled','INPUT/CHECKBOX')

			MM_changeProp('pan1'+i,'','checked','0','INPUT/CHECKBOX')
			MM_changeProp('pan2'+i,'','checked','0','INPUT/CHECKBOX')
			//MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
		}
		for(i=0; i<10; i++) {

			MM_changeProp('pan3'+i,'','disabled','0','INPUT/CHECKBOX')
			MM_changeProp('pan4'+i,'','disabled','disabled','INPUT/CHECKBOX')

			MM_changeProp('pan3'+i,'','checked','0','INPUT/CHECKBOX')
			MM_changeProp('pan4'+i,'','checked','0','INPUT/CHECKBOX')
			//MM_changeProp('num'+i,'','checked','0','INPUT/CHECKBOX');
		}

		a3.style.display = "";
		a4.style.display = "";

	for(i=1; i<6; i++) {
			if (i==type) {

			}else{
			MM_changeProp('pabc'+i,'','checked','0','INPUT/RADIO')
			}


		}


	}



}

function r_pan1(zizi) {

for(i=1; i<13; i++) {
			if (i==zizi) {


			}else{
			MM_changeProp('pan1'+i,'','checked','0','INPUT/RADIO')
			}


		}
var str1="pan1";

var str2="pan2";
if(document.all[str2].value ==zizi)

{
MM_changeProp('pan1'+zizi,'','checked','0','INPUT/RADIO')

document.all[str1].value = "";
alert("对不起!请重新选择两个不一样的！");

document.all.pan4.focus();
return false;
}

}

function r_pan2(zizi,zzz){
for(i=1; i<13; i++) {
			if (i==zizi) {


			}else{
			MM_changeProp('pan2'+i,'','checked','0','INPUT/RADIO')
			}


		}

var str1="pan2";

var str2="pan1";
if(document.all[str2].value ==zizi)

{
MM_changeProp('pan2'+zizi,'','checked','0','INPUT/RADIO')

document.all[str1].value = "";
alert("对不起!请重新选择两个不一样的！");

document.all.pan4.focus();
return false;
}
}


function r_pan3(zizi,zzz){
	// alert(zizi);
for(i=0; i<10; i++) {
			if (i==zizi) {


			}else{
			MM_changeProp('pan3'+i,'','checked','0','INPUT/RADIO')
			}


		}
var str1="pan3";
var str2="pan4";
// alert(document.all[str2].value);
if(document.all[str2].value ==zizi && document.all[str2].value!="")
{
MM_changeProp('pan3'+zizi,'','checked','0','INPUT/RADIO')

 document.all[str1].value = "";
alert("对不起!请重新选择两个不一样的！");

document.all.pan3.focus();
return false;
}
}
function r_pan4(zizi,zzz){



for(i=0; i<13; i++) {
			if (i==zizi) {


			}else{
			MM_changeProp('pan4'+i,'','checked','0','INPUT/RADIO')
			}


		}

var str1="pan4";

var str2="pan3";
if(document.all[str2].value ==zizi)

{
MM_changeProp('pan4'+zizi,'','checked','0','INPUT/RADIO')

document.all[str1].value = "";
alert("对不起!请重新选择两个不一样的！");

document.all.pan4.focus();
return false;
}


}



function ra_select(str1,zz){
        document.all[str1].value = zz;

}



function SubChk(obj) {

if (document.all.rrtype.value == "") {
alert('请选择类别');
	  return false;
}


if (document.all.pabc.value == 3) {
if (document.all.pan1.value =="" || document.all.pan2.value ==""){
	  alert('请选择生肖');
	  return false;
	  }

}

if (document.all.pabc.value == 4) {
if (document.all.pan3.value =="" || document.all.pan4.value ==""){
	  alert('请选择尾数');
	  return false;
	  }

}


if (document.all.rrtype.value == 1) {
if (document.all.pabc.value == 2) {
if (document.all.dm1.value =="" || document.all.dm2.value ==""){
	  alert('请选择胆');
	  return false;
	  }

}
}
if (document.all.rrtype.value == 2) {
if (document.all.pabc.value == 2) {



if (document.all.dm1.value =="" ){
	  alert('请选择胆');
	  return false;
	  }

}
}

	var checkCount = 0;
	var checknum = obj.elements.length;
	var rtypechk = 0;


	for(i=0; i<obj.rtype.length; i++) {
		if (obj.rtype[i].checked) {
			rtypechk ++;
		}
	}



	if (rtypechk == 0) {
	  alert('请选择类别');
	  return false;
	}

	for(i=0; i<checknum; i++) {
		if (obj.elements[i].checked) {
			checkCount ++;
		}
	}


	if (checkCount > (type_nums + 1)) {
		alert(mess2);
		return false;
	}if(checkCount < (type_min+1)){
		alert(mess1+type_min+mess11);
		return false;
	}else{
		return true;
	}
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
//-->
</SCRIPT>
 <style type="text/css">
.game td {border: 1px solid #ccc;padding-left: 1px;line-height:15px;}
.game td input{margin-left: -2px;}
.STYLE1 {color: #333}

body {background-color:#f1f1f1}
 </style>
<TABLE  border="0" cellpadding="2" cellspacing="1" bordercolordark="#f9f9f9" bgcolor="#CCCCCC" width="780" >
  <TBODY>


	<?php include("common_qishu.php"); ?>
<!-- <form  method="post" onSubmit="return SubChk();"  action="liuhecai.php?action=k_lmsave" name="lt_form" target="k_meml_h"  style="height:580px;"> -->
 <form  name="lt_form" id="lt_form" method="get" action="main_left.php" style="height:640px;" target="k_meml" onsubmit="return ChkSubmit(this);">
 <input type="hidden" name="title_3d" value="连码" id="title_3d">
  <input type="hidden" name="leixing" value="连码" id="leixing">
 <input type="hidden" name="fc_type" value="9" id="fc_type">
 <input type="hidden" name="ids" value="<?=$ids ?>" id="ids">
 <input type="hidden" name="action" value="k_lmsave" id="action">
 <input type="hidden" name="class2" value="<?=$ids?>" id="class2">
 <input type="hidden" name="limit_num" value="3" id="limit_num">
<TABLE cellSpacing='1' cellPadding='0' style="width:780px" border=0 class="game_table all_body" style="display:none;" >
                <TBODY>
				   <tr class="tbtitle2">
					<th colspan="10"><div  style="float:left;line-height:30px;text-align:center;width:100%"><?=$_GET['leixing'] ?></div>
					</th>
				  </tr>
                  <tr class="tbtitle">
                    <TD width="16%" align=center><span class="STYLE3">
        <input name="rrtype" type="hidden" id="rrtype" value="">
                      <INPUT name=rtype type=radio onclick="select_types(2,3)"; value="三全中" checked='checked'> 三全中</span></TD> <TD width="16%" align=center><span class="STYLE3">
                    <INPUT onclick="select_types(1,3)"; type=radio value="三中二" name=rtype> 三中二</span></TD> <TD width="16%" align=center><span class="STYLE3">
                    <INPUT onclick="select_types(3,2)"; type=radio value="二全中" name=rtype> 二全中</span></TD> <TD width="16%" align=center><span class="STYLE3">
                    <INPUT onclick="select_types(4,2)"; type=radio value="二中特" name=rtype> 二中特</span></TD> <TD width="16%" align=center><span class="STYLE3">
                    <INPUT onclick="select_types(5,2)"; type=radio value="特串" name=rtype> 特串</span></TD> <TD width="16%" align=center><span class="STYLE3">
                    <INPUT onclick="select_types(6,4)"; type=radio value="四中一" name=rtype> 四中一</span></TD> </TR>
                  <TR  class="Ball_tr_H">
                    <TD width="16%" align="center" class="ball_ff" bgcolor="ffffff">三全中 <FONT color=#0000ff><B><?=round($drop_table[4][1],2)?></B></FONT></TD>
                    <TD width="16%" align="center" class="ball_ff" bgcolor="ffffff">中二 <FONT color=#0000ff><B><?=round($drop_table[5][1],2)?></B></FONT><BR>
                    中三 <FONT
        color=#0000ff><B><?=round($drop_table[6][1],2)?></B></FONT></TD>
                    <TD width="16%" align="center" class="ball_ff" bgcolor="ffffff">二全中 <FONT color=#0000ff><B><?=round($drop_table[0][1],2)?></B></FONT></TD>
                    <TD width="16%" align="center" class="ball_ff" bgcolor="ffffff">中特 <FONT color=#0000ff><B><?=round($drop_table[1][1],2)?></B></FONT><BR>
                    中二 <FONT
        color=#0000ff><B><?=round($drop_table[2][1],2)?></B></FONT></TD>
                    <TD width="16%" align="center" class="ball_ff" bgcolor="ffffff">特串 <FONT
    color=#0000ff><B><?=round($drop_table[3][1],2)?></B></FONT></TD>
                    <TD width="16%" align="center" class="ball_ff" bgcolor="ffffff">四中一 <FONT
    color=#0000ff><B><?=round($drop_table[7][1],2)?></B></FONT></TD>
                  </TR>
                </TBODY>
            </TABLE>

         <TABLE cellSpacing='1' cellPadding='0' style="width:780px" border=0 class="game_table all_body" style="display:none;" >
            <TBODY>


            <tr class="tbtitle">
            <TD width=28 height="28" align="center" class="td_caption_1"><span class="STYLE2">号码</span></TD>
            <TD width=110 align="center" class="td_caption_1"><span class="STYLE2">勾选</span></TD>
            <TD width=28 align="center" class="td_caption_1"><span class="STYLE2">号码</span></TD>
            <TD width=110 align="center" class="td_caption_1"><span class="STYLE2">勾选</span></TD>
                                  <TD width=28 align="center" class="td_caption_1"><span class="STYLE2">号码</span></TD>
                                  <TD width=110 align="center" class="td_caption_1"><span class="STYLE2">勾选</span></TD>
                                  <TD width=28 align="center" class="td_caption_1"><span class="STYLE2">号码</span></TD>
                                  <TD width=110 align="center" class="td_caption_1"><span class="STYLE2">勾选</span></TD>
                                  <TD width=28 align="center" class="td_caption_1"><span class="STYLE2">号码</span></TD>
                                  <TD width=110 align="center" class="td_caption_1"><span class="STYLE2">勾选</span></TD>
        </TR>
        <TR class="tbtitle">
                                  <td align="center"   class="ball_r">01</td>
                                  <TD align='middle' class="ball_ff" bgcolor="ffffff"><INPUT  onclick=SubChkBox(this,1)
                        type=checkbox value='01' name='num1' id='num1'></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_r">11</td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,11)
                        type=checkbox value=11 name=num11></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_r">21</td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,21)
                        type=checkbox value=21 name=num21></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_r">31</td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,31)
                        type=checkbox value=31 name=num31></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_r">41</td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,41)
                        type=checkbox value=41 name=num41></TD>
                                </TR>
            <TR class="tbtitle">
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_r">02</td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT name=num2
                        type=checkbox onclick=SubChkBox(this,2) value=02 ></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_r">12</td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,12)
                        type=checkbox value=12 name=num12></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_r">22</td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,22)
                        type=checkbox value=22 name=num22></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_r">32</td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,32)
                        type=checkbox value=32 name=num32></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_r">42</td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,42)
                        type=checkbox value=42 name=num42></TD>
                                </TR>
            <TR class="tbtitle">
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">03</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,3)
                        type=checkbox value=03 name=num3></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">13</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,13)
                        type=checkbox value=13 name=num13></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">23</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,23)
                        type=checkbox value=23 name=num23></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">33</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,33)
                        type=checkbox value=33 name=num33></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">43</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,43)
                        type=checkbox value=43 name=num43></TD>
                                </TR>
            <TR class="tbtitle">
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">04</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,4)
                        type=checkbox value=04 name=num4></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">14</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,14)
                        type=checkbox value=14 name=num14></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">24</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,24)
                        type=checkbox value=24 name=num24></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">34</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,34)
                        type=checkbox value=34 name=num34></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">44</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,44)
                        type=checkbox value=44 name=num44></TD>
                                </TR>
            <TR class="tbtitle">
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_g"><span class="ball_r">05</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,5)
                        type=checkbox value=05 name=num5></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_g"><span class="ball_r">15</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,15)
                        type=checkbox value=15 name=num15></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_g"><span class="ball_r">25</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,25)
                        type=checkbox value=25 name=num25></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_g"><span class="ball_r">35</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,35)
                        type=checkbox value=35 name=num35></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_g"><span class="ball_r">45</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,45)
                        type=checkbox value=45 name=num45></TD>
                                </TR>
            <TR class="tbtitle">
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_g"><span class="ball_r">06</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,6)
                        type=checkbox value=06 name=num6></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_g"><span class="ball_r">16</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,16)
                        type=checkbox value=16 name=num16></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_g"><span class="ball_r">26</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,26)
                        type=checkbox value=26 name=num26></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_g"><span class="ball_r">36</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,36)
                        type=checkbox value=36 name=num36></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_g"><span class="ball_r">46</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,46)
                        type=checkbox value=46 name=num46></TD>
                                </TR>
            <TR class="tbtitle">
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_r">07</td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,7)
                        type=checkbox value=07 name=num7></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_r">17</td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,17)
                        type=checkbox value=17 name=num17></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_r">27</td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,27)
                        type=checkbox value=27 name=num27></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_r">37</td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,37)
                        type=checkbox value=37 name=num37></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_r">47</td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,47)
                        type=checkbox value=47 name=num47></TD>
                                </TR>
            <TR class="tbtitle">
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_r">08</td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,8)
                        type=checkbox value=08 name=num8></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_r">18</td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,18)
                        type=checkbox value=18 name=num18></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_r">28</td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,28)
                        type=checkbox value=28 name=num28></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_r">38</td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,38)
                        type=checkbox value=38 name=num38></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_r">48</td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,48)
                        type=checkbox value=48 name=num48></TD>
                                </TR>
            <TR class="tbtitle">
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">09</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,9)
                        type=checkbox value=09 name=num9></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">19</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,19)
                        type=checkbox value=19 name=num19></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">29</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,29)
                        type=checkbox value=29 name=num29></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">39</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,39)
                        type=checkbox value=39 name=num39></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">49</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,49)
                        type=checkbox value=49 name=num49></TD>
                                </TR>
            <TR class="tbtitle">
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">10</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,10)
                        type=checkbox value=10 name=num10></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">20</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,20)
                        type=checkbox value=20 name=num20></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">30</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT onclick=SubChkBox(this,30)
                        type=checkbox value=30 name=num30></TD>
                                  <td align="center" bordercolordark="#f9f9f9" bgcolor="#FFFFFF" class="ball_b"><span class="ball_r">40</span></td>
                                  <TD align=middle class="ball_ff" bgcolor="ffffff"><INPUT  onclick=SubChkBox(this,40)
                        type=checkbox value=40 name=num40></TD>
                                  <TD colSpan=2 class="ball_ff" align=middle bgcolor="#FFFFFF">                                  </TD>
                                </TR>
                              </TBODY>
                          </TABLE>

        <DIV id="a2" style="display:block; ">
    <table style="width:780px" border="0" cellpadding="0" cellspacing="1" class="game_table all_body" style="display:none;">
      <tbody>
        <tr class="tbtitle" align="middle">
          <td width="76%" ><input name="pabc1" type="radio" onClick="select_types1(1,1);ra_select('pabc','1');" value="1" checked  />
            正常
            <input name="pabc2" type="radio" onClick="select_types1(2,1);ra_select('pabc','2');" value="2" />
            胆拖
            <input name="pabc3" type="radio" onClick="select_types1(3,2);ra_select('pabc','3');" value="3" disabled="disabled" />
            生肖对碰
            <input name="pabc4" type="radio" onClick="select_types1(4,3);ra_select('pabc','4');" value="4"  disabled="disabled"/>
            尾数对碰
			<input name="pabc5" type="radio" onClick="select_types1(5,4);ra_select('pabc','5');" value="5" disabled="disabled" />
            肖串尾
		<input name="pabc" type="hidden" id="pabc" value="1">
          </td>
                  <td width="9%" nowrap  id="hd1">胆1
                  <input name="dm1" type="text" disabled="disabled" class="input1" id="dm1" size="4"></td>
                  <td width="12%" nowrap  id="hd1">胆2
                  <input name="dm2" type="text"  disabled="disabled"  class="input1" id="dm2" size="4"></td>
                </tr>
              </tbody>
            </table>
        </div>
          <DIV id="a3" style="display:block; ">
            <table style="width:500px;" border="0" cellpadding="0" cellspacing="0" class="game" >
              <tbody>
                <tr class="tbtitle" align="middle" style="display:none;height:21px;" id="shengxiao_1">
                  <td align="center" class="ball_ff"  style="background-color:#ff9;">
                     鼠
                    <input name="pan1<?=Get_sx_nx(1)?>" type="radio" onClick="r_pan1(<?=Get_sx_nx(1)?>);javascript:ra_select('pan1','<?=Get_sx_nx(1)?>')" value="<?=Get_sx_nx(1)?>" />
                  </td>
                  <td align="center" class="ball_ff"  style="background-color:#ff9;">牛
                    <input name="pan1<?=Get_sx_nx(7)?>" type="radio" onClick="r_pan1(<?=Get_sx_nx(7)?>);javascript:ra_select('pan1','<?=Get_sx_nx(7)?>')"  value="<?=Get_sx_nx(7)?>" />
                  </td>
                  <td align="center" class="ball_ff"  style="background-color:#ff9;">虎
                    <input name="pan1<?=Get_sx_nx(2)?>" type="radio" onClick="r_pan1(<?=Get_sx_nx(2)?>);javascript:ra_select('pan1','<?=Get_sx_nx(2)?>')" value="<?=Get_sx_nx(2)?>" />
                  </td>
                  <td align="center" class="ball_ff"  style="background-color:#ff9;">兔
                    <input name="pan1<?=Get_sx_nx(8)?>" type="radio" onClick="r_pan1(<?=Get_sx_nx(8)?>);javascript:ra_select('pan1','<?=Get_sx_nx(8)?>')"   value="<?=Get_sx_nx(8)?>" />
                  </td>
                  <td align="center" class="ball_ff"  style="background-color:#ff9;">龙
                    <input name="pan1<?=Get_sx_nx(3)?>" type="radio"  onclick="r_pan1(<?=Get_sx_nx(3)?>);javascript:ra_select('pan1','<?=Get_sx_nx(3)?>')" value="<?=Get_sx_nx(3)?>" />
                  </td>
                  <td align="center" class="ball_ff"  style="background-color:#ff9;">蛇
                    <input name="pan1<?=Get_sx_nx(9)?>" type="radio"  onclick="r_pan1(<?=Get_sx_nx(9)?>);javascript:ra_select('pan1','<?=Get_sx_nx(9)?>')"  value="<?=Get_sx_nx(9)?>" />
                  </td>
                  <td align="center" class="ball_ff"  style="background-color:#ff9;">马
                    <input name="pan1<?=Get_sx_nx(4)?>" type="radio"  onclick="r_pan1(<?=Get_sx_nx(4)?>);javascript:ra_select('pan1','<?=Get_sx_nx(4)?>')"  value="<?=Get_sx_nx(4)?>" />
                  </td>
                  <td align="center" class="ball_ff"  style="background-color:#ff9;">羊
                    <input name="pan1<?=Get_sx_nx(10)?>" type="radio" onClick="r_pan1(<?=Get_sx_nx(10)?>);javascript:ra_select('pan1','<?=Get_sx_nx(10)?>')"  value="<?=Get_sx_nx(10)?>" />
                  </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">猴
                    <input name="pan1<?=Get_sx_nx(5)?>" type="radio" onClick="r_pan1(<?=Get_sx_nx(5)?>);javascript:ra_select('pan1','<?=Get_sx_nx(5)?>')"  value="<?=Get_sx_nx(5)?>" />
                  </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">鸡
                    <input name="pan1<?=Get_sx_nx(11)?>" type="radio" onClick="r_pan1(<?=Get_sx_nx(11)?>);javascript:ra_select('pan1','<?=Get_sx_nx(11)?>')"  value="<?=Get_sx_nx(11)?>" />
                  </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">狗
                    <input name="pan1<?=Get_sx_nx(6)?>" type="radio" onClick="r_pan1(<?=Get_sx_nx(6)?>);javascript:ra_select('pan1','<?=Get_sx_nx(6)?>')"  value="<?=Get_sx_nx(6)?>" />
                  </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">猪
                  <input name="pan1<?=Get_sx_nx(12)?>" type="radio"  onclick="r_pan1(<?=Get_sx_nx(12)?>);javascript:ra_select('pan1','<?=Get_sx_nx(12)?>')" value="<?=Get_sx_nx(12)?>" />
                      <input name="pan1" type="hidden" value="" id="pan1">
                  </td>
                </tr>
                <tr class="tbtitle" align="middle" style="display:none;height:21px;" id="shengxiao_2">
                  <td align="center" class="ball_ff"  style="background-color:#ff9;"><input name="pan2" type="hidden" value="" id="pan2">
                    鼠
                    <input name="pan2<?=Get_sx_nx(1)?>" type="radio" onClick="r_pan2(<?=Get_sx_nx(1)?>);javascript:ra_select('pan2','<?=Get_sx_nx(1)?>')" value="<?=Get_sx_nx(1)?>" />
                  </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">牛
                    <input name="pan2<?=Get_sx_nx(7)?>" type="radio" onClick="r_pan2(<?=Get_sx_nx(7)?>);javascript:ra_select('pan2','<?=Get_sx_nx(7)?>')"  value="<?=Get_sx_nx(7)?>" />
                  </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">虎
                    <input name="pan2<?=Get_sx_nx(2)?>" type="radio" onClick="r_pan2(<?=Get_sx_nx(2)?>);javascript:ra_select('pan2','<?=Get_sx_nx(2)?>')" value="<?=Get_sx_nx(2)?>" />
                  </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">兔
                    <input name="pan2<?=Get_sx_nx(8)?>" type="radio" onClick="r_pan2(<?=Get_sx_nx(8)?>);javascript:ra_select('pan2','<?=Get_sx_nx(8)?>')"   value="<?=Get_sx_nx(8)?>" />
                  </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">龙
                    <input name="pan2<?=Get_sx_nx(3)?>" type="radio"  onclick="r_pan2(<?=Get_sx_nx(3)?>);javascript:ra_select('pan2','<?=Get_sx_nx(3)?>')" value="<?=Get_sx_nx(3)?>" />
                  </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">蛇
                    <input name="pan2<?=Get_sx_nx(9)?>" type="radio"  onclick="r_pan2(<?=Get_sx_nx(9)?>);javascript:ra_select('pan2','<?=Get_sx_nx(9)?>')"  value="<?=Get_sx_nx(9)?>" />
                  </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">马
                    <input name="pan2<?=Get_sx_nx(4)?>" type="radio"  onclick="r_pan2(<?=Get_sx_nx(4)?>);javascript:ra_select('pan2','<?=Get_sx_nx(4)?>')"  value="<?=Get_sx_nx(4)?>" />
                  </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">羊
                    <input name="pan2<?=Get_sx_nx(10)?>" type="radio" onClick="r_pan2(<?=Get_sx_nx(10)?>);javascript:ra_select('pan2','<?=Get_sx_nx(10)?>')"  value="<?=Get_sx_nx(10)?>" />
                  </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">猴
                    <input name="pan2<?=Get_sx_nx(5)?>" type="radio" onClick="r_pan2(<?=Get_sx_nx(5)?>);javascript:ra_select('pan2','<?=Get_sx_nx(5)?>')"  value="<?=Get_sx_nx(5)?>" />
                  </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">鸡
                    <input name="pan2<?=Get_sx_nx(11)?>" type="radio" onClick="r_pan2(<?=Get_sx_nx(11)?>);javascript:ra_select('pan2','<?=Get_sx_nx(11)?>')"  value="<?=Get_sx_nx(11)?>" />
                  </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">狗
                    <input name="pan2<?=Get_sx_nx(6)?>" type="radio" onClick="r_pan2(<?=Get_sx_nx(6)?>);javascript:ra_select('pan2','<?=Get_sx_nx(6)?>')"  value="<?=Get_sx_nx(6)?>" />
                  </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">猪
                  <input name="pan2<?=Get_sx_nx(12)?>" type="radio"  onclick="r_pan2(<?=Get_sx_nx(12)?>);javascript:ra_select('pan2','<?=Get_sx_nx(12)?>')" value="<?=Get_sx_nx(12)?>" /></td>
                </tr>
              </tbody>
            </table>
        </div>
     <DIV id="a4" style="display:block; ">
            <table style="width:500px;" border="0" cellpadding="0" cellspacing="0" class="game">
              <tbody>
                <tr class="tbtitle" align="middle" style="display:none;height:21px;" id="shengxiao_3">
                  <td align="center" class="ball_ff" style="background-color:#ff9;"><input name="pan3" type="hidden" value="" id="pan3">
                    0尾
                    <input name="pan30" type="radio" onClick="r_pan3(0);javascript:ra_select('pan3','0')"  value="0" />                                      </td>
                  <td align="center" class="ball_ff"  style="background-color:#ff9;">1尾
                  <input name="pan31" type="radio"  onclick="r_pan3(1);javascript:ra_select('pan3','1')"  value="1" />                                      </td>
                  <td align="center" class="ball_ff"  style="background-color:#ff9;">2尾
                  <input name="pan32" type="radio" onClick="r_pan3(2);javascript:ra_select('pan3','2')"  value="2" />                                      </td>
                  <td align="center" class="ball_ff"  style="background-color:#ff9;">3尾
                  <input name="pan33" type="radio" onClick="r_pan3(3);javascript:ra_select('pan3','3')"  value="3" />                                      </td>
                  <td align="center" class="ball_ff"  style="background-color:#ff9;">4尾
                  <input name="pan34" type="radio" onClick="r_pan3(4);javascript:ra_select('pan3','4')"  value="4" />                                      </td>
                  <td align="center" class="ball_ff"  style="background-color:#ff9;">5尾
                  <input name="pan35" type="radio"  onclick="r_pan3(5);javascript:ra_select('pan3','5')" value="5" />                                      </td>
                  <td align="center" class="ball_ff"  style="background-color:#ff9;">6尾
                  <input name="pan36" type="radio"  onclick="r_pan3(6);javascript:ra_select('pan3','6')" value="6" />                                      </td>
                  <td align="center" class="ball_ff"  style="background-color:#ff9;">7尾
                  <input name="pan37" type="radio" onClick="r_pan3(7);javascript:ra_select('pan3','7')"  value="7" />                                      </td>
                  <td align="center" class="ball_ff"  style="background-color:#ff9;">8尾
                  <input name="pan38" type="radio" onClick="r_pan3(8);javascript:ra_select('pan3','8')"  value="8" />                                      </td>
                  <td align="center" class="ball_ff"  style="background-color:#ff9;">9尾
                  <input name="pan39" type="radio" onClick="r_pan3(9);javascript:ra_select('pan3','9')"  value="9" />                                      </td>
                </tr>
                <tr align="middle" style="display:none;height:21px;" id="shengxiao_4" class="tbtitle">
                  <td align="center"class="ball_ff"  style="background-color:#ff9;"><input name="pan4" type="hidden" value="" id="pan4">
                    0尾
                    <input name="pan40" type="radio"  onclick="r_pan4(0);javascript:ra_select('pan4','0')" value="0" />                                      </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">1尾
                  <input name="pan41" type="radio" onClick="r_pan4(1);javascript:ra_select('pan4','1')"   value="1" />                                      </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">2尾
                  <input name="pan42" type="radio"  onclick="r_pan4(2);javascript:ra_select('pan4','2')" value="2" />                                      </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">3尾
                  <input name="pan43" type="radio" onClick="r_pan4(3);javascript:ra_select('pan4','3')"  value="3" />                                      </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">4尾
                  <input name="pan44" type="radio" onClick="r_pan4(4);javascript:ra_select('pan4','4')"  value="4" />                                      </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">5尾
                  <input name="pan45" type="radio" onClick="r_pan4(5);javascript:ra_select('pan4','5')" value="5" />                                      </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">6尾
                  <input name="pan46" type="radio" onClick="r_pan4(6);javascript:ra_select('pan4','6')"  value="6" />                                      </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">7尾
                  <input name="pan47" type="radio"  onclick="r_pan4(7);javascript:ra_select('pan4','7')" value="7" />                                      </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">8尾
                  <input name="pan48" type="radio" onClick="r_pan4(8);javascript:ra_select('pan4','8')"  value="8" />                                      </td>
                  <td align="center"class="ball_ff"  style="background-color:#ff9;">9尾
                  <input name="pan49" type="radio" onClick="r_pan4(9);javascript:ra_select('pan4','9')"  value="9" /></td>
                </tr>
              </tbody>
                                </table>
                            </div> </TD>
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
<table class="game_table all_body" style="display:none;" style="width:780px;border:1px solid #bbafaf;border-top:none" border="0" cellpadding="0" cellspacing="0">
   <tbody>
   <tr class="hset2" align="middle">
   <td width="100" class="tbtitle" bgcolor="ffffff"></td>
   <td width="100" class="tbtitle" bgcolor="ffffff"></td>
    <td width="100" class="tbtitle" bgcolor="ffffff"></td>
   <!-- <td  bgcolor="ffffff" class="tbtitle" width="">下注金额</td> -->
    <!-- <td  bgcolor="ffffff" class="tbtitle"><input type="text" class="inp1" value=""  name="jq" style="width:80px;" id="xiazhu_money"></td> -->
  <td width="" class="tbtitle" bgcolor="ffffff">

    <INPUT type="submit" value="投注"  class="button_a"  name=btnSubmit>&nbsp;&nbsp;
    <input style="margin:0 300px 0 0;" type="reset" onClick="javascript:document.all.allgold.innerHTML =0;" class="button_a"  name="Submit3" value="重设" />
	</td><td  bgcolor="ffffff" class="tbtitle" width="350"></td>
  </FORM>
  </td>
  </tr>
  </tbody>
</table>
<script>
	select_types(2,3);
</script>