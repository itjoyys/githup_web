if(self==top){
	top.location='/index.php';
}

function go(url){
	location.href=url;
}

var time=21;  		//120秒自动刷新

$(document).ready(function(){
	Refresh(); //自动刷新
});

function Refresh(){
	time=time-1;
	if(time<1){
		time=21;
		$("#top_f5").val(document.documentElement.scrollTop);
		var league = document.getElementById('league').value
		var page = document.getElementById('aaaaa').innerHTML;
		loaded(league,page);
	}else{
		$("#sx_f5").val("刷新"+time);
	}
	setTimeout("Refresh()",1000);
}

function formatNumber(num,exponent){
	return parseFloat(num).toFixed(exponent);
}  

function shuaxin(league){
	time=21
	$("#top_f5").val(document.documentElement.scrollTop);
	var page = document.getElementById('aaaaa').innerHTML;
	loaded(league,page);
}

function NumPage(thispage){
	var league = document.getElementById('league').value;
	document.getElementById('aaaaa').innerHTML = thispage;
	loaded(league,thispage,'p');
}

function check_one(lsm){
	document.getElementById("league").value	=	lsm;
	loaded(lsm);
}


function killerrors() {
	return true;
}
window.onerror = killerrors; 