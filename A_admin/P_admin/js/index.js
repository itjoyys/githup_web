// JavaScript Document
function check(){
	if($("#LoginName").val()	==	""){
		$("#LoginName").focus();
		return false;
	}
	if($("#LoginPassword").val()	==	""){
		$("#LoginPassword").focus();
		return false;
	}
	if($("#CheckCode").val()	==	""){
		$("#CheckCode").focus();
		return false;
	}else if($("#CheckCode").val().length()	!=	4){
		$("#CheckCode").select();
		return false;
	}
	
	return true;
}

$(function(){
	$("#yzm").click(function(){
		$("#yzm").attr("src","../yzm.php?"+Math.random());
	})	  
})