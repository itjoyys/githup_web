<!--
function CheckDEL(str){
	var enable_s = document.all.enable.value;
	var page = document.all.page.value;
	if(confirm("是否確定刪除該代理商?")) document.location=str+"&enable_s="+enable_s+"&page="+page;
}

function CheckSTOP(str,chk){
	var enable_s = document.all.enable.value;
	var page = document.all.page.value;
	if(chk=='Y'){
		if(confirm("是否確定啟用該代理商?")) document.location=str+"&enable_s="+enable_s+"&page="+page;
	}
	if(chk=='N'){
		if(confirm("是否確定停用該代理商?")) document.location=str+"&enable_s="+enable_s+"&page="+page;
	}
	if(chk=='S'){
		if(confirm("是否確定暫停該代理商?")) document.location=str+"&enable_s="+enable_s+"&page="+page;
	}
}
-->