function SubChk(){
	if(document.getElementsByName('passwd')[0].value!='' && document.getElementsByName('passwd')[0].value.length < 6 || document.getElementsByName('passwd')[0].value.length > 12)
	{ document.getElementsByName('passwd')[0].focus(); alert(top.str_pwd_limit); return false; }
	if(document.getElementsByName('passwd')[0].value!='' && document.getElementsByName('repasswd')[0].value=='')
	{ document.getElementsByName('repasswd')[0].focus(); alert(top.str_input_repwd); return false; }
	if(document.getElementsByName('passwd')[0].value != document.getElementsByName('repasswd')[0].value)
	{ document.getElementsByName('passwd')[0].focus(); alert(top.str_err_pwd); return false; }
	if(document.getElementsByName('alias')[0].value=='')
	{ document.getElementsByName('alias')[0].focus(); alert("總代理名稱請務必輸入!!"); return false; }
	if(document.getElementsByName('maxcredit')[0].value=='' || document.getElementsByName('maxcredit')[0].value<='0')
	{ document.getElementsByName('maxcredit')[0].focus(); alert("總信用額度請務必輸入!!"); return false; }
	if(!confirm("是否確定寫入總代理?")) {
		return false;
	}
}

function SubChk2(){
	var dfwinloss_s;
	dfwinloss_s=(document.getElementsByName('dfwinloss_s')[0].value);
	if(dfwinloss_s!="-"){
		if(!confirm("預設的成數將在 "+dfday+" 後生效!!確認預設嗎?")) {
			return false;
		}
	}
	//if((dfwinloss_s=="-")){
	//alert("預設成數不可有 [ - ] 號");
	//document.getElementsByName('dfwinloss_s')[0].focus();
	//return false;
	//}
}