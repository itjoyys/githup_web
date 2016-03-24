function closed() {
	//parent.$.jBox.close(true);
	//$(window.parent.document).find(".aui_close").click();
	//$(parent).click();
	window.location.reload();
}
function Go(url){
	window.location.href=url;
}
//修改登录密码
function check_submit_login(){
	if($("#oldpass").val().length<=0){
		$("#oldpass_txt").html('请输入您的原登录密码');
		$("#oldpass").select();
		return false;
	}
	if($("#newpass").val().length<6 || $("#newpass").val().length>10){
		$("#newpass_txt").html('新登录密码只能是6-10位');
		$("#newpass").select();
		return false;
	}
	if($("#newpass").val()!=$("#newpass2").val()){
		$("#newpass2_txt").html('两次密码输入不一致');
		$("#newpass2").select();
		return false;
	}
}
//修改登录密码
function check_submit_login(){
	if($("#oldpass").val().length<=0){
		$("#oldpass_txt").html('请输入您的原登录密码');
		$("#oldpass").select();
		return false;
	}
	if($("#newpass").val().length<6 || $("#newpass").val().length>10){
		$("#newpass_txt").html('新登录密码只能是6-10位');
		$("#newpass").select();
		return false;
	}
	if($("#newpass").val()!=$("#newpass2").val()){
		$("#newpass2_txt").html('两次密码输入不一致');
		$("#newpass2").select();
		return false;
	}
}
//修改取款密码
function check_submit_money(){
	if($("#oldmoneypass").val().length<=0){
		$("#oldmoneypass_txt").html('请输入您的原取款密码');
		$("#oldmoneypass").select();
		return false;
	}
	if($("#newmoneypass").val().length<6 || $("#newmoneypass").val().length>10){
		$("#newmoneypass_txt").html('新取款密码只能是6-10位');
		$("#newmoneypass").select();
		return false;
	}
	if($("#newmoneypass").val()!=$("#newmoneypass2").val()){
		$("#newmoneypass2_txt").html('两次密码输入不一致');
		$("#newmoneypass2").select();
		return false;
	}
}
//修改代理密码
function check_submit_dlmoney(){
	if($("#olddlpass").val().length<=0){
		$("#olddlpass_txt").html('请输入您的原代理密码');
		$("#olddlpass").select();
		return false;
	}
	if($("#newdlpass").val().length<6 || $("#newdlpass").val().length>10){
		$("#newdlpass_txt").html('新代理密码只能是6-10位');
		$("#newdlpass").select();
		return false;
	}
	if($("#newdlpass").val()!=$("#newdlpass2").val()){
		$("#newdlpass2_txt").html('两次密码输入不一致');
		$("#newdlpass2").select();
		return false;
	}
}
//设置财务资料
function check_submit_pay(){
	if($("#pay_card").val().length<=0){
		$("#pay_card_txt").html('请输入您的收款银行');
		$("#pay_card").select();
		return false;
	}else{
		$("#pay_card_txt").html('');
	}
	if($("#pay_num").val().length<10){
		$("#pay_num_txt").html('请输入您的银行账号');
		$("#pay_num").select();
		return false;
	}
	else{
		$("#pay_num_txt").html('');
	}
	if($("#pay_address").length<=0){
		$("#pay_address_txt").html('请输入您的开户行地址');
		$("#pay_address").select();
		return false;
	}
	else{
		$("#pay_address_txt").html('');
	}
}