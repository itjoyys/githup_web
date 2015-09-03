<!--
function CheckDEL(str){
	var enable_s = document.getElementsByName('enable')[0].value;
	var page = document.getElementsByName('page')[0].value;
	if(confirm("是否確定刪除該總代理?")) document.location=str+"&enable_s="+enable_s+"&page="+page;
}

function CheckSTOP(str,chk){
	var enable_s = document.getElementsByName('enable')[0].value;
	var page = document.getElementsByName('page')[0].value;
	if(chk=='Y'){
		if(confirm("是否確定啟用該總代理?")) document.location=str+"&enable_s="+enable_s+"&page="+page;
	}
	if(chk=='N'){
		if(confirm("是否確定停用該總代理?")) document.location=str+"&enable_s="+enable_s+"&page="+page;
	}
	if(chk=='S'){
		if(confirm("是否確定暫停該總代理?")) document.location=str+"&enable_s="+enable_s+"&page="+page;
	}
}
-->