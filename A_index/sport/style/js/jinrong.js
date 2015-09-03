function setbet(match_id,tid){
	if($(parent.topFrame.document).find("#username").html().length<=3){ //没有登录
	   alert("登录后才能进行此操作");
	   return ;
	}
	 
	$.post("../ajaxleft/jinrong.php",{match_id:match_id,tid:tid,rand:Math.random()},function (data){  parent.leftFrame.bet(data); });
}
