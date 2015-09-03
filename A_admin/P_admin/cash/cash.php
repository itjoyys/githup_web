<?php
 include_once("../../include/config.php");
 include_once("../common/login_check.php");
//判断是否有新出款
 $new_out_state = '';
$new_out_state = M('k_user_bank_out_record',$db_config)->field("id")
				   ->where("out_status = '0' or out_status='4' and site_id = '".SITEID."'")
				   ->find();
if (!empty($new_out_state)) {
	$new_out_state = 1;
}else{
	$new_out_state = 0;
}

//判断是否有新入款
$new_down_state = '';
$new_down_state = M('k_user_bank_in_record',$db_config)->field("id")
				   ->where("make_sure = '0' and site_id = '".SITEID."' and into_style = '1'")
				   ->find();
if (!empty($new_down_state)) {
	$new_down_state = 1;
}else{
	$new_down_state = 0;
}


//判断是否有新入款
$new_on_state = '';
$new_on_state = M('k_user_bank_in_record',$db_config)->field("id")
				   ->where("make_sure = '0' and site_id = '".SITEID."' and into_style = '2'")
				   ->find();
if (!empty($new_on_state)) {
	$new_on_state = 1;
}else{
	$new_on_state = 0;
}


if(!empty($_GET['type'])){
	if($_GET['type'] == 'out'){
		$cbout  =	'checked="checked"';
	}elseif($_GET['type'] == 'down'){
		$cbbank  =	'checked="checked"';
	}elseif($_GET['type'] == 'on'){
		$cbonline  =	'checked="checked"';
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>出入款監控</title>
<link  rel="stylesheet"  href="../public/css/main.css"  type="text/css">
<link  rel="stylesheet"  href="../public/css/styleCss.css"  type="text/css">
<link  rel="stylesheet"  href="../public/css/style.css"  type="text/css">
<script  src="../public/js/jquery-1.7.min.js"></script>
<script  src="../public/js/validform.js"></script>
<script  src="../public/js/jquery.tablesorter.min.js"></script>
<script  src="../public/js/myfunction.js"></script>
<script  type="text/javascript" src="../public/js/WdatePicker.js"></script>
</head>
<title>出入款監控</title>
<body>
<script src="../public/js/swfobject.js" type="text/javascript"></script>
<script language="JavaScript" src="../public/js/easydialog.min.js"></script>
<script type="text/javascript">
var uid="";
var sound=2;
var OutTimeObj=null;
var BankTimeObj=null;
var OnlineTimeObj=null;
var PlayOut=false;
var PlayBank=false;
var PlayOnline=false;
var OutNum=0;
var BankNum=0;
var OnlineNum=0;

$(document).ready(function(){
	// GetOut();
	// GetBank();
	// GetOnline();
	// RefreshOut();
	// RefreshBank();
	// RefreshOnline();

	$(".button_a_b").click(function() {
		$(".payBox_1").css('display', 'none');
	});

		if($("#cbout").attr("checked")=="checked")
		{
			$("#outPanel").show();
			GetOut();
			RefreshOut();
			// var issound = '';
			// var issound = <?=$new_out_state?>;
			// if(issound==1){
			//     embed1();
			// }
		}
		else
		{
			if(OutTimeObj!=null) window.clearTimeout(OutTimeObj);
			$("#outPanel").hide();
		}

$("#cbout").bind("click",function(b){
		if($("#cbout").attr("checked")=="checked")
		{
			$("#outPanel").show();
			GetOut();
			RefreshOut();

		}
		else
		{
			if(OutTimeObj!=null) window.clearTimeout(OutTimeObj);
			$("#outPanel").hide();
		}
	});
	$("#cbbank").bind("click",function(b){
		if($(this).attr("checked")=="checked")
		{
			$("#bankPanel").show();
			GetBank();
			RefreshBank();

		}
		else
		{
			if(BankTimeObj!=null) window.clearTimeout(BankTimeObj);
			$("#bankPanel").hide();
		}
	});

		if($("#cbbank").attr("checked")=="checked")
		{
			$("#bankPanel").show();
			GetBank();
			RefreshBank();
			// var issound = '';
			// var issound = <?=$new_down_state?>;
			// if(issound==1){
			//      embed2();
			// }
		}
		else
		{
			if(BankTimeObj!=null) window.clearTimeout(BankTimeObj);
			$("#bankPanel").hide();
		}

	$("#cbonline").bind("click",function(b){
		if($(this).attr("checked")=="checked")
		{
			$("#onlinePanel").show();
			GetOnline();
			RefreshOnline();

		}
		else
		{
			if(OnlineTimeObj!=null) window.clearTimeout(OnlineTimeObj);
			$("#onlinePanel").hide();
		}
	});

		if($("#cbonline").attr("checked")=="checked")
		{
			$("#onlinePanel").show();
			GetOnline();
			RefreshOnline();
			// var issound = '';
			// var issound = <?=$new_on_state?>;
			// if(issound==1){
			//     embed3();
			// }
		}
		else
		{
			if(OnlineTimeObj!=null) window.clearTimeout(OnlineTimeObj);
			$("#onlinePanel").hide();
		}

	window.setInterval(function(){
		if(PlayOut)
		{
			play("chukuan");
			PlayOut=false;
		}
		else if(PlayBank)
		{
			play("gsrk");
			PlayBank=false;
		}
		else if(PlayOnline)
		{
			play("xsrk");
			PlayOnline=false;
		}
	},3000);
});
function embed1(){


		$('body').append('<embed class="embed1" src="../public/sound/chukuan.swf" autostart="true" width=0 height=0 loop="false">');

}
function embed2(){

		$('body').append('<embed class = "embed2" src="../public/sound/gsrk.swf" autostart="true" width=0 height=0 loop="false">');

}
function embed3(){

		//$('body').append('<embed class = "embed3" src="../public/sound/xsrk.swf" autostart="true" width=0 height=0 loop="false">');

}
function ReLoad()
{
	Res();

	if($("#cbout").attr("checked")=="checked"){
		GetOut();


	}

	if($("#cbbank").attr("checked")=="checked"){
		GetBank();

	}

	if($("#cbonline").attr("checked")=="checked"){
		GetOnline();

	}

}

/*自动更新取消超过30分钟的线上支付订单*/
function cash_online(){
	$.ajax({
		type: "get",
		url: "cash_online.php",
		data: {act:"cash_delete"},
		success: function(r){
			}
		});
}
function Res()
{
	if(OutTimeObj!=null) window.clearTimeout(OutTimeObj);
	if(BankTimeObj!=null) window.clearTimeout(BankTimeObj);
	if(OnlineTimeObj!=null) window.clearTimeout(OnlineTimeObj);
	if($("#reload").val()!="-1")
	{
		$("#outtime").text($("#reload").val());
		$("#banktime").text($("#reload").val());
		$("#onlinetime").text($("#reload").val());
		if($("#cbout").attr("checked")=="checked") OutTimeObj=window.setTimeout("RefreshOut()",1000);
		if($("#cbbank").attr("checked")=="checked") BankTimeObj=window.setTimeout("RefreshBank()",1000);
		if($("#cbonline").attr("checked")=="checked") OnlineTimeObj=window.setTimeout("RefreshOnline()",1000);
	}
}
function RefreshOut()
{
	var t=parseInt($("#outtime").text());
	if(t<=0)
	{
		GetOut();

		$("#outtime").text($("#reload").val());
	}
	else
		$("#outtime").text(t-1);
	OutTimeObj=window.setTimeout("RefreshOut()",1000);
}
function RefreshBank()
{
	var t=parseInt($("#banktime").text());
	if(t<=0)
	{
		GetBank();

		$("#banktime").text($("#reload").val());
	}
	else
		$("#banktime").text(t-1);
	BankTimeObj=window.setTimeout("RefreshBank()",1000);
}
function RefreshOnline()
{
	var t=parseInt($("#onlinetime").text());
	if(t<=0)
	{
		GetOnline();

		$("#onlinetime").text($("#reload").val());
	}
	else
		$("#onlinetime").text(t-1);
	OnlineTimeObj=window.setTimeout("RefreshOnline()",1000);
}
function play(swf)
{
	var el = document.getElementById("sound");
	var flashvars={};
	flashvars.isAutoPlay = true;
	var params = {};
	params.play= 1;
	params.wmode = 'Transparent';
	var attributes = {};
	function callbackFn(e) {
		if(e.success)
		{
			try{
				thisMovie("sound").asSoundControl(is_need_notice);
			}catch(e){}
		}
	}
	swfobject.embedSWF("/"+swf+".swf", el, 1, 1, 10,"expressInstall.swf",flashvars,params,attributes,callbackFn);
}

var timeout=0;

function ajaxout(r){
	if(r.error=="ajaxerror"){
		 alert("账户异常，请重新登录");
		 window.location.href='../index.html';
		}
}
//出款
function GetOut()
{
	// var issound = <?=$new_out_state?>;
	// if(issound==1){
	// 	embed1();
	// }
	$.getJSON("cash_op.php?act=out1",null,function(r){
		   ajaxout(r);
			var old=$("#outList").html();
			$("#outList").empty();
			if(r['status'] == 0){
					$('.embed1').remove();
					}else{

			$.each(r.info,function(index,rs)
			{

				var item='<tr  class="m_cen">'+
					'<td>'+rs.level_des+'</td>'+
					'<td>'+rs.agent_user+'</td>'+
					'<td style="display:none" class="out_uids">'+rs.uid+'</td>'+
				    // '<td><a href="#" onclick="show_currency('+rs.uid+',\''+rs.username+'\')">'+rs.username+'</a></td>'+
				    '<td class="username_tan">'+rs.username+'</td>'+
					'<td>'+(rs.outward_style==1?"是":"否")+'</td>'+
					'<td>'+rs.outward_num+'</td>'+
					'<td>'+rs.charge+'</td>'+
					'<td>'+rs.favourable_num+'</td>'+
					'<td>'+rs.expenese_num+'</td>'+
					// '<td style="cursor:pointer" onclick="show_div(event,\''+rs.id+'\',\'bankinfo\')"><font color="#0000FF">'+rs.outward_money+'</font></td>'+
					'<td style="color:#C03130;cursor: pointer;" onclick="show_bank_msg(this);">'+rs.outward_money+'</td>'+
					'<td>'+rs.balance+'</td>'+
					'<td>'+(rs.favourable_out==1?"是":"否")+'</td>'+
					'<td>'+rs.out_time+'</td>'+
					'<td>'+(rs.out_status==4?'<button name="confirmbtn" class="button_d">正在出款</button>':'<button name="confirmbtn" class="button_d" onclick="OutBtn(this,4,'+rs.id+')">预备出款</button>')+

					'<button name="confirmbtn" class="button_d" onclick="OutBtn(this,1,'+rs.id+','+rs.uid+')">确定</button>'+
					'<button name="cancelbtn" class="button_d" onclick="OutBtn(this,2,'+rs.id+','+rs.id+','+rs.uid+')">取消</button>'+
					 '<button name="refusebtn" class="button_d" onclick="OutBtn(this,3,'+rs.id+','+rs.uid+')">拒绝</button>'+
					'</td>'+
					'<td>'+rs.admin_user+'</td>'+
					'</tr>';
					$("#outList").append(item);

			});
			embed1();
			}
			if($("#outList").html()!="")
			{
				if(old!=$("#outList").html())
				{
					PlayOut=true;
					OutNum=0;
				}
				else
				{
					OutNum++;
					if(OutNum<sound) PlayOut=true;
				}
			}
		});

}
//公司入款
function GetBank()
{
	// var issound = '';
	// var issound = <?=$new_out_state?>;
 //    if(issound==1){
	//    setTimeout('embed2()',1000);
	// }
	$.ajax({
		type: "get",
		url: "cash_op.php",
		dataType: "json",
		data: {act:"bank"},
		success: function(r){
			ajaxout(r);
			var old=$("#bankList").html();
			$("#bankList").empty();
			if(r['status'] == 0){
				$('.embed2').remove();
			}else{
			$.each(r.info,function(index,rs)
			{
				var bank="";
				if(rs.bank_name!="") bank+="銀行："+rs.bank_name+"<br>";
				if(rs.in_name!="") bank+="存款人："+rs.in_name+"<br>";
				if(rs.in_date!="") bank+="存款時間："+rs.in_date+"<br>";
				bank+="方式："+rs.bank_fs+"<br>";
				if(rs.in_type == '2' || rs.in_type == '3' || rs.in_type == '4') bank+="網點："+rs.in_atm_address+"<br>";

				var item='<tr  class="m_cen">'+
					'<td>'+rs.level_des+'</td>'+
					'<td>'+rs.order_num+'</td>'+
					'<td>'+rs.agent_user+'</td>'+
					'<td>'+rs.username+'</td>'+
					'<td style="text-align:left">'+bank+'</td>'+
					'<td style="text-align:left">存入金額：'+rs.deposit_num+'<br>存款優惠：'+rs.favourable_num+'<br>其他優惠：'+rs.other_num+'<br>存入總金額：'+rs.deposit_money+'</td>'+
					'<td style="text-align:left">銀行帳號：'+rs.card_ID+'<br>銀行：'+rs.card_address+'<br>卡主姓名：'+rs.card_userName+'</td>'+
					'<td>'+(rs.is_firsttime==1?"是":"否")+'</td>'+
					'<td>'+rs.log_time+'</td>'+
					'<td><button name="confirmbtn" class="button_d" onclick="BankBtn(this,1,'+rs.order_num+','+rs.id+','+rs.uid+')">确定</button>'+
					'<button name="cancelbtn" class="button_d" onclick="BankBtn(this,2,'+rs.order_num+','+rs.id+','+rs.uid+')">取消</button>'+
					'</td>'+
					'</tr>';

				$("#bankList").append(item);

			});//each

			if($("#bankList").html()!=""){
				if(old!=$("#bankList").html()){
					PlayBank=true;
					BankNum=0;
				}else{
					BankNum++;
					if(BankNum<sound) PlayBank=true;
				}
			}
			setTimeout("embed2()",3000);
		}
		}
		// ,
		 // error: function(status, errorThrown){
   //                    alert("状态码：" + status.status)
   //                    alert("错误原因：" + errorThrown);
   //      }
		});

}
//点击金额显示银行信息
function show_bank_msg(obj){

	//出款金额弹窗
		$(".payBox_1").css('display', 'block');
		$(".b_1_1").text('');
		$(".b_2_1").text('');
		$(".b_3_1").text('');
		$(".b_4_1").text('');
		$(".b_5_1").text('');
		$(".b_6_1").text('');
		$(".b_7_1").text('');
		$(".b_8_1").text('');
		var test_id_1 = $(obj).siblings(".out_uids").text();
		var username_id_1=$(obj).siblings(".username_tan").text();
		$.ajax({
			type: "post",
			url: "./out_record_ajax.php",
			dataType: "json",
			data: {test_id:test_id_1,action:'bank'},
			success: function(msg){
				$(".b_1_1").text(username_id_1);
				$(".b_2_1").text(msg.pay_name);
				$(".b_3_1").text(msg.pay_card);
				$(".b_4_1").text(msg.pay_num);
				$(".b_5_1").text(msg.city[0]);
				$(".b_6_1").text(msg.city[1]);
				$(".b_7_1").text(msg.mobile);
				$(".b_8_1").text(msg.about);

			}
		});

}


function GetOnline()
{
	// var issound = '';
	// var issound = <?=$new_out_state?>;
	// 	if(issound==1){
	// 		setTimeout('embed3()',3500);
	// 		timeout = 0;

	// 	}
	cash_online();
	$.getJSON("cash_op.php?act=online",null,function(r){
		ajaxout(r);
			var old=$("#onlineList").html();
			$("#onlineList").empty();
			if(r['status'] == 0){
					$('.embed3').remove();
					}else{
			$.each(r.info,function(index,rs)
			{
				var item='<tr  class="m_cen">'+
					'<td>'+rs.level_des+'</td>'+
					'<td>'+rs.order_num+'</td>'+
					'<td>'+rs.agent_user+'</td>'+
					'<td>'+rs.username+'</td>'+
					'<td style="text-align:left">存入金額：'+rs.deposit_num+'<br>存款優惠：'+rs.favourable_num+'<br>其他優惠：'+rs.other_num+'<br>存入總金額：'+rs.deposit_money+'</td>'+
					'<td>'+(rs.is_first==1?"是":"否")+'</td>'+
					'<td>'+rs.in_date+'</td>'+
					/*'<td><button name="confirmbtn" class="button_d" onclick="OnlineBtn(this,1,'+rs.id+')">确定</button>'+
					'<button name="cancelbtn" class="button_d" onclick="OnlineBtn(this,2,'+rs.id+')">取消</button>'+*/
					'<td>'+
					'未支付'+
					'</td>'+
					'</tr>';
				$("#onlineList").append(item);
			});
			if($("#onlineList").html()!="")
			{
				if(old!=$("#onlineList").html())
				{
					PlayOnline=true;
					OnlineNum=0;
				}
				else
				{
					OnlineNum++;
					if(OnlineNum<sound) PlayOnline=true;
				}
			}
			setTimeout("embed3()",6000);
		}
		});

}
function OutBtn(b,flag,id,uid)
{
	b.disabled=true;
	switch(flag)
	{
		case 1: //確認
			if(confirm('要將狀態改為已確定的狀態嗎?'))
			{
				$.ajax({
				type: "get",
				url: "out_record.php",
				dataType: "json",
				data: {btn:"sc",op:"cop",oc:'od',id:id,status:flag,uid:uid},
				success: function(r){
					ajaxout(r);
					     if(r=="2"){
					  		 alert("修改失败！");
					  		GetOut();
						  }else{
						 	 alert("修改成功！");
						 	 GetOut();
					 	 }
					}
				  });
			}
			else
				b.disabled=false;
			break;
		case 2: //取消
			if(confirm('要將狀態改為已取消的狀態嗎?'))
			{
				$.ajax({
				type: "get",
				url: "out_record.php",
				dataType: "json",
				data: {btn:"s3",op:'cop',id:id,status:flag,uid:uid},
				success: function(r){
					ajaxout(r);
					     if(r=="2"){
					  		 alert("修改失败！");
					  		GetOut();
						  }else{
						 	 alert("修改成功！");
						 	 GetOut();
					 	 }
					}
				  });
			}
			else
				b.disabled=false;
			break;
		case 3: //拒绝
			if(confirm('要將狀態改為已拒絕的狀態嗎?'))
			{
				$.ajax({
				type: "get",
				url: "out_record.php",
				dataType: "json",
				data: {btn:"s2",op:'cop',oc:'od',id:id,status:flag},
				success: function(r){
					ajaxout(r);
					     if(r=="2"){
					  		 alert("修改失败！");
					  		GetOut();
						  }else{
						 	 alert("修改成功！");
						 	 GetOut();
					 	 }
					}
				  });
			}
			else
				b.disabled=false;
			break;
		case 4: //预备出款
			if(confirm('要將狀態改為预备出款的狀態嗎?'))
			{
				$.ajax({
				type: "get",
				url: "out_record.php",
				dataType: "json",
				data: {btn:"s1",op:'cop',id:id,status:flag},
				success: function(r){
					ajaxout(r);
					     if(r=="2"){
					  		 alert("预备出款失败！");
					  		GetOut();
						  }else{
						 	 alert("预备出款成功！");
						 	 GetOut();
					 	 }
					}
				  });
			}
			else
				b.disabled=false;
			break;
	}
}

function BankBtn(b,flag,ordernum,id,uid)//this,1,id
{
	b.disabled=true;
	switch(flag)
	{
		case 1: //確認
			if(confirm('要將狀態改為已確定的狀態嗎?'))
			{
				$.ajax({
				type: "get",
				url: "./bank_record_do.php",
				dataType: "json",
				data: {btn:"s1",op:"cop",id:id,order_num:ordernum,uid:uid},
				success: function(r){
					ajaxout(r);
					  if(r=="2"){
					  	 alert("修改失败！");
					      GetBank();
					  }else{
						  alert("确定入款成功！");
						  GetBank();
					  }
					}
				  });
			}
			else
				b.disabled=false;
			break;
		case 2: //取消
			if(confirm('要將狀態改為已取消的狀態嗎?'))
			{
				$.ajax({
				type: "get",
				url: "./bank_record_do.php",
				dataType: "json",
				data: {btn:"s0",op:"cop",id:id,order_num:ordernum,uid:uid},
				success: function(r){
					ajaxout(r);
					  if(r=="2"){
					  	alert("修改失败！");
					  	GetBank();
					  }else{
						  alert("修改成功！");
						  GetBank();
					  }
					}
				  });
			}
			else
				b.disabled=false;
			break;
	}
}
//线上入款处理
function OnlineBtn(b,flag,id)
{
	b.disabled=false;
	switch(flag)
	{
		case 1: //確認
			if(confirm('要將狀態改為已確定的狀態嗎?'))
			{
				$.ajax({
				type: "get",
				url: "cash_op.php",
				dataType: "json",
				data: {act:"online",op:1,id:id,status:flag},
				success: function(r){
					ajaxout(r);
					if(r=="2"){
						alert("修改失败！");
					  	GetOnline();
					  }else{
						  alert("修改成功！");
						  GetOnline();
					  }


					}
				  });
			}
			else
				b.disabled=false;
			break;
		case 2: //取消
			if(confirm('要將狀態改為已取消的狀態嗎?'))
			{
				$.ajax({
				type: "get",
				url: "cash_op.php",
				dataType: "json",
				data: {act:"online",op:1,id:id,status:flag},
				success: function(r){
					ajaxout(r);
					if(r=="2"){
						alert("修改失败！");
					  	GetOnline();
					  }else{
						  alert("修改成功！");
						  GetOnline();
					  }

					}
				  });
			}
			else
				b.disabled=false;
			break;
	}
}
function show_config(id,complex,yh){
		$("#level_id").val(id);
		$("#complex").val(complex);
		$("#yh").val(yh);
		if(yh==0) $("#yh_panel").hide();
		easyDialog.open({
			  container : 'currency_box'
			});
}
function show_pre(id,complex){
	$("#order_id").val(id);
	$("#pre_amount").val(complex);
	easyDialog.open({
		  container : 'pre_box'
		});
}
function sxfsave(b)
{
	b.disabled=true;
	if(confirm('确认修改数据？'))
	{
		$.post("cash_op.php",{act:"savepre",aid:$("#order_id").val(),pre_amount:$("#pre_amount").val(),op:1},function(r){
			if(r=="")
			{
				GetOut();
				easyDialog.close();
			}
			else
				alert(r);
		});
	}
	b.disabled=false;
}
function xzfsave(b)
{
	b.disabled=true;
	if(confirm('确认修改数据？'))
	{
		$.post("cash_op.php",{act:"savexzf",aid:$("#level_id").val(),complex:$("#complex").val(),yh:$("#yh").val(),op:1},function(r){
			if(r=="")
			{
				GetOut();
				easyDialog.close();
			}
			else
				alert(r);
		});
	}
	b.disabled=false;
}
function show_div(event,divid,iden)//event,\''+rs.id+'\',\'bankinfo\'
{alert("!");
	var event = event ? event : window.event;
	var top = left = 0;
	var div = document.getElementById(iden);
	alert("!");
	$("#bankinfo").hide();
	$("#memoinfo").hide();
	if(iden == 'bankinfo')
	{
		alert("!");
		top = document.body.scrollTop + event.clientY - 10;
		left = document.body.scrollLeft + event.clientX + 30;
		$.getJSON("cash_op.php?uid="+uid+"&act=user",{id:divid},function(rs)
		{
			ajaxout(rs);
			$("#bankinfo_1").text(rs.user_account);
			$("#bankinfo_2").text(rs.user_alias);
			$("#bankinfo_3").text(rs.bank_name);
			$("#bankinfo_4").text(rs.bank_account);
			$("#bankinfo_5").text(rs.bank_province);
			$("#bankinfo_6").text(rs.bank_city);
			$("#bankinfo_7").text(rs.user_phone);
			$("#bankinfo_8").text(rs.bank_memo);
			div.style.top = top + "px";
			div.style.left = left + "px";
			div.style.display = "block";
		});
	}
	else if(iden == "memoinfo")
	{
		top = document.body.scrollTop + event.clientY + 15;
		div.style.top = top + "px";
		div.style.left = left + "px";
		div.style.display = "block";
	}

}
function show_currency(test_id,username){//level_id,username
	$.ajax({
		type: "post",
		url: "out_record_ajax.php",
		dataType: "json",
		data: {test_id:test_id,action:'user'},
		success: function(msg){
			ajaxout(msg);
		   if(msg.count_in == null){
					msg.count_in =0;
				}
				if(msg.count_out == null){
					msg.count_out =0;
				}
				$("#a_1").text(test_id);
				$("#a_2_1").text(msg.in_all_money);
				$("#a_2_2").text(msg.count_in+"笔");
				$("#a_3_1").text(msg.out_all_money);
				$("#a_3_2").text(msg.count_out+"笔");
				$("#a_4_1").text(msg.owen_money);
				$("#a_5_1").text(msg.data1.cash_num);
				$("#a_5_2").text(type_lei(msg.data1.cash_type));
				$("#a_6_1").text(msg.data2.cash_num);
				$("#a_6_2").text(type_lei(msg.data2.cash_type));
				$("#a_7_1").text(msg.data3.cash_num);
				$("#a_7_2").text(type_lei(msg.data3.cash_type));
		}
	});
	$("#username").html(username);
	$(".payBox").css('display', 'block');
}

	$(".button_a_b").click(function() {
		$("#payBox").css('display', 'none');
	});





</script>

 <!-- 用户名弹窗 -->
<div class="payBox" id="payBox" style="margin: -216px 0px 0px -150px; padding: 0px; border: none; z-index: 10000; position: fixed; top: 50%; left: 50%; display: none;"><div id="currency_box" style="display: block; margin: 0px;" class="con_menu">

  <input name="level_id_1" id="level_id_1" value="" type="hidden">
  <table class="m_tab" style="width:250px;margin:0;">
    <tbody><tr class="m_title">
      <td colspan="3" height="27" class="table_bg" align="left">
      <span id="a_1"></span>
      <span style="float:right;"><a style="color:white;" href="javascript:void(0)" title="关闭窗口" class="button_a_b">×</a></span>
      </td>
    </tr>
    <tr class="m_title_a">
      <td>入款总额</td>
      <td width="90" id="a_2_1"></td>
      <td width="90" id="a_2_2"></td>
    </tr>

      <tr>

      <td height="0" style="text-align:center;">出款总额</td>
      <td  id="a_3_1" style="text-align:center;"></td>
      <td style="text-align:center;" id="a_3_2"></td>
    </tr>
  		<tr class="m_title_a">
  			<td width="70">盈利情况</td>
  			<td colspan="2" id="a_4_1"></td>

  		</tr>
  		<tr class="m_title">
  			<td colspan="3">最近3笔入款金额</td>
  		</tr>

  		<tr class="m_title_a">
  			<td>入款一</td>
  			<td id="a_5_1"></td>
  			<td id="a_5_2"></td>
  		</tr>
  		<tr class="m_title_a">
  			<td>入款二</td>
  			<td id="a_6_1"></td>
  			<td id="a_6_2"></td>
  		</tr>
  		<tr class="m_title_a">
  			<td>入款三</td>
  			<td id="a_7_1"></td>
  			<td id="a_7_2"></td>
  		</tr>

        <tr>
      <td colspan="3" align="center">
        <input type="button" value="关闭" class="button_a close_x button_a_b">
      </td>
    </tr>
  </tbody></table>

</div></div>
<div style="position:absolute;width:300px;height:300px;display:none;" id="bankinfo">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab">
		<tbody><tr class="m_title_over_co">
			<td colspan="2">會員&nbsp;<span id="bankinfo_1"></span>&nbsp;的銀行帳戶資料</td>
		</tr>
		<tr>
			<td width="40%" class="table_bg1">會員姓名</td>
			<td width="60%" id="bankinfo_2"></td>
		</tr>
		<tr>
			<td class="table_bg1">銀行名稱</td>
			<td id="bankinfo_3"></td>
		</tr>
		<tr>
			<td class="table_bg1">銀行帳號</td>
			<td id="bankinfo_4"></td>
		</tr>
		<tr>
			<td class="table_bg1">省份</td>
			<td id="bankinfo_5"></td>
		</tr>
		<tr>
			<td class="table_bg1">城市</td>
			<td id="bankinfo_6"></td>
		</tr>
		<tr>
			<td class="table_bg1">聯繫電話</td>
			<td id="bankinfo_7"></td>
		</tr>
		<tr>
			<td class="table_bg1">備注</td>
			<td id="bankinfo_8"></td>
		</tr>
		<tr>
			<td colspan="2" class="table_bg1" align="center">
				<button name="closebtn" onclick="$(&#39;#bankinfo&#39;).hide();">關閉</button>
			</td>
		</tr>
	</tbody></table>
</div>
<div id="currency_box" style="display:none;" class="con_menu">
<form method="post" id="xzf_form" name="xzf_form">
	<input name="level_id" id="level_id" value="0" type="hidden">
	<input name="savexzf" id="savexzf" value="true" type="hidden">
    <table class="m_tab" style="width:300px;margin:0;">
		<tbody><tr class="m_title">
			<td colspan="2" height="27" class="table_bg" align="left">
			<span id="title">修改出款</span>
			<span style="float:right;"><a style="color:white;" href="javascript:void(0)" title="关闭窗口" onclick="easyDialog.close();">×</a></span>
			</td>
		</tr>
		<tr class="m_title">
			<td>行政費</td>
			<td style="text-align:left"><input name="complex" id="complex" value=""></td>
		</tr>
		<tr class="m_title" id="yh_panel">
			<td>優惠扣除</td>
			<td style="text-align:left"><select name="yh" id="yh">
            		<option value="0">否</option>
                    <option value="1">是</option>
            	</select>
            </td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="button" value="提交" id="xzfBtn" name="xzfBtn" onclick="xzfsave(this)" class="button_a">
				<input type="reset" value="关闭" onclick="easyDialog.close();" class="button_a">
			</td>
		</tr>
	</tbody></table>
</form>
</div>

<div id="pre_box" style="display:none;" class="con_menu">
<form method="post" id="search_form" name="search_form" onsubmit="$(&#39;#sxfBtn&#39;).attr(&#39;disabled&#39;,true)">
	<input name="order_id" id="order_id" value="0" type="hidden">
	<input name="savepre" id="savepre" value="true" type="hidden">
    <table class="m_tab" style="width:300px;margin:0;">
		<tbody><tr class="m_title">
			<td colspan="2" height="27" class="table_bg" align="left">
			<span id="title">修改出款</span>
			<span style="float:right;"><a style="color:white;" href="javascript:void(0)" title="关闭窗口" onclick="easyDialog.close();">×</a></span>
			</td>
		</tr>
		<tr class="m_title">
			<td>手续费費</td>
			<td style="text-align:left"><input name="pre_amount" id="pre_amount" value=""></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="button" id="sxfBtn" name="sxfBtn" value="提交" onclick="sxfsave(this)" class="button_a">
				<input type="reset" value="关闭" onclick="easyDialog.close();" class="button_a">
			</td>
		</tr>
	</tbody></table>
</form>
</div>

<div id="con_wrap">
<div class="input_002">出入款監控</div>
<div class="con_menu" style="width:90%;height:70px;">
<form name="queryform" action="" method="get">
	<input type="hidden" name="uid" value="fdf15055e699b262e54976b31e41">
	&nbsp;
	重新整理:
	<select name="reload" id="reload" onchange="Res()">
		<option value="-1">不更新</option>
        <option value="30">30秒</option>
		<option value="60" selected="selected">60秒</option>
		<option value="120">120秒</option>
        <option value="180">180秒</option>
	</select>
    &nbsp;
    <input type="button" value="立即更新" name="refBtn" onclick="ReLoad()">
    &nbsp;&nbsp;&nbsp;&nbsp;
    項目：
    <input type="checkbox" id="cbout" value="1" <?=$cbout ?>>出款&nbsp;
    <input type="checkbox" id="cbbank" value="1" <?=$cbbank ?>>公司入款&nbsp;
    <input type="checkbox" id="cbonline" value="1" <?=$cbonline ?>>在線入款&nbsp;</form>
</div>
</div>
<div class="content">
	<div style="padding-left:10px;color:red;padding-bottom:10px">注意：由於有時網絡不順，要是操作後服務器還未返回，請不要連續重複操作，可以點擊"立即更新"按鈕同步最新數據</div>
	<div id="outPanel" style="display: none;">
	&nbsp;&nbsp;<font color="red" id="outtime">16</font> 秒後刷新數據
	<table style="width:1250px" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab">
		<tbody><tr class="m_title_over_co">
			<td colspan="15"><b>未处理的出款申請</b></td>
		</tr>
        <tr class="m_title_over_co">
			<td>層級</td>
			<td>代理</td>
			<td>會員帳號</td>
            <td>出款狀況</td>
			<td>提出額度</td>
			<td>手續費</td>
			<td>優惠金額</td>
			<td>行政費</td>
			<td>出款金額</td>
            <td>账户余额</td>
			<td>優惠扣除</td>
            <td>日期</td>
            <td>操作</td>
            <td>操作者</td>
		</tr>
        </tbody><tbody id="outList"></tbody>
	</table>
    </div>
    <br>
    <div id="bankPanel" style="display: none;">
    &nbsp;&nbsp;<font color="red" id="banktime">16</font> 秒後刷新數據
    <table style="width:1250px" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab">
		<tbody><tr class="m_title_over_co">
			<td colspan="10"><b>未处理的公司入款</b></td>
		</tr>
        <tr class="m_title_over_co">
        	<td>層級</td>
			<td>訂單號</td>
			<td>代理</td>
			<td>會員帳號</td>
            <td>會員銀行</td>
			<td>存入金額</td>
			<td>存入銀行帳戶</td>
			<td>是否首存</td>
			<td>日期</td>
            <td>操作</td>
		</tr>
        </tbody><tbody id="bankList">

        </tbody>
	</table>
    </div>
    <br>
    <div id="onlinePanel" style="display: none;">
    &nbsp;&nbsp;<font color="red" id="onlinetime">17</font> 秒後刷新數據
    <table style="width:1250px" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab">
		<tbody><tr class="m_title_over_co">
			<td colspan="8"><b>未处理的線上入款</b></td>
		</tr>
        <tr class="m_title_over_co">
        	<td>層級</td>
			<td>訂單號</td>
			<td>代理</td>
			<td>會員帳號</td>
            <td>存入金額</td>
			<td>是否首存</td>
			<td>日期</td>
            <td>状态</td>
		</tr>
        </tbody><tbody id="onlineList"></tbody>
	</table>
    </div>
</div>

<!-- 出款金额弹窗 -->
<div class="payBox_1" style="margin: -216px 0px 0px -150px; padding: 0px; border: none; z-index: 10000; position: fixed; top:50%; left: 50%; display: none;"><div id="currency_box" style="display: block; margin: 0px;text-align:center;" class="con_menu">

  <input name="level_id_1" id="level_id_1" value="" type="hidden">
  <table class="m_tab" style="width:250px;margin:0;">
    <tbody><tr class="m_title">
      <td colspan="2" height="27" class="table_bg" align="left">
      <span id="title">會員 <span class="b_1_1"></span> 的銀行帳戶資料</span>
      <span style="float:right;"><a style="color:white;" href="javascript:void(0)" title="关闭窗口" class="button_a_b">×</a></span>
      </td>
    </tr>
    <tr class="m_title_a">
      <td>會員姓名</td>
      <td width="90" class="b_2_1">
</td>
    </tr>
      <tr>

      <td height="0" style="text-align:center;">銀行名稱</td>
      <td id="RMB_1" style="text-align:center;" class="b_3_1"></td>

    </tr>
  		<tr class="m_title_a">
  			<td width="70">銀行帳號</td>
  			<td  class="b_4_1"></td>

  		</tr>
  		<tr class="m_title_a">
  			<td width="70">省份</td>
  			<td  class="b_5_1"></td>
  		</tr>

  		<tr class="m_title_a">
  			<td>城市</td>
  			<td  class="b_6_1"></td>

  		</tr>
  		<tr class="m_title_a">
  			<td>聯繫電話</td>
  			<td  class="b_7_1"></td>

  		</tr>
  		<tr class="m_title_a">
  			<td>備注</td>
  			<td  class="b_8_1"></td>

  		</tr>

        <tr>
      <td colspan="3" align="center">
        <input type="button" value="关闭" class="button_a close_x button_a_b">
      </td>
    </tr>
  </tbody></table>

</div></div>


<!--
<object width="1" height="1" id="sound" type="application/x-shockwave-flash" data="../public/sound/swf/xsrk.swf" style="visibility: visible;"><param name="play" value="1"><param name="wmode" value="Transparent"><param name="flashvars" value="isAutoPlay=true"></object>-->

</body></html>