function SubChk(){
	var Numflag = 0;
	var Letterflag = 0;
	//var pwdo = document.getElementById('password_old').value;
	var users = document.getElementById('username_safe').value;
	var pwd = document.getElementById('password_new').value;
	var repwd = document.getElementById('password_re').value;
	var user = document.getElementById('username').value;
	if(users == ""){
		document.getElementById('username_safe').focus();
		alert(top.str_input_longin_id);
		return false;
	}
	if(pwd == ""){
		document.getElementById('password_new').focus();
		alert(top.str_input_pwd);
		return false;
	}
	if(repwd == ""){
		document.getElementById('password_re').focus();
		alert(top.str_input_repwd);
		return false;
	}
	if (users.length < 6 || users.length > 12) {
		alert(top.str_longin_limit1);
		return false;
	}
	if (pwd!= repwd) {
		document.getElementById('password_re').focus();
		alert(top.str_err_pwd);
		return false;
	}
	
	for (idx = 0; idx < users.length; idx++) {
		//====== 密碼只可使用字母(不分大小寫)與數字
		if(!((users.charAt(idx)>= "a" && users.charAt(idx) <= "z") || (users.charAt(idx)>= 'A' && users.charAt(idx) <= 'Z') || (users.charAt(idx)>= '0' && users.charAt(idx) <= '9'))){
			alert(top.str_longin_limit1);
			return false;
		}
		if ((users.charAt(idx)>= "a" && users.charAt(idx) <= "z") || (users.charAt(idx)>= 'A' && users.charAt(idx) <= 'Z')){
			Letterflag = 1;
		}
		if ((users.charAt(idx)>= '0' && users.charAt(idx) <= '9')){
			Numflag = 1;
		}
	}
	//====== 密碼需使用字母加上數字
	if (Numflag == 0 || Letterflag == 0) {
		alert(top.str_longin_limit2);
		return false;
	}
	ChgPwdForm.submit();
}

function ChkMem(){
	var D=document.getElementById('username_safe').value;
	var uid =document.getElementById('uid').value;
	document.getElementById('getData').src='mem_chk.php?uid='+uid+'&langx='+langx+'&username='+D;
}