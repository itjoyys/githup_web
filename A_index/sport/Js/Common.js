function go(url){
	location.href=url;
}

var time=121;  		//120秒自动刷新

$(document).ready(function(){
	Refresh(); //自动刷新
});

function Refresh(){
	time=time-1;
	if(time<1){
		time=121;
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
	if(num > 0){
		return parseFloat(num).toFixed(exponent);
	}else{
		return '';
	}
}  

function shuaxin(league){
	time=121
	$("#top_f5").val(document.documentElement.scrollTop);
	var page = document.getElementById('aaaaa').innerHTML;
	loaded(league,page);
}

function NumPage(thispage){
	g_num		=	0;
	var league	=	document.getElementById('league').value;
	document.getElementById('aaaaa').innerHTML = thispage;
	loaded(league,thispage,'p');
}

function check_one(lsm){
	document.getElementById("league").value	=	lsm;
	loaded(lsm);
}

function set_num(num,fid,zid){
	var fsum	=	window.parent.leftFrame.document.getElementById(fid).innerHTML.match(/([0-9]{1,})/ig);
	var zsum	=	window.parent.leftFrame.document.getElementById(zid).innerHTML.match(/([0-9]{1,})/ig);
	fsum		=	fsum*1;
	zsum		=	zsum*1;
	fsum		=	fsum-zsum+num;
	window.parent.leftFrame.document.getElementById(fid).innerHTML	=	fsum+"";
	window.parent.leftFrame.document.getElementById(zid).innerHTML	=	num+"";
}

function _attachEvent(obj, evt, func, eventobj) {
	eventobj = !eventobj ? obj : eventobj;
	if(obj.addEventListener){
		obj.addEventListener(evt, func, false);
	}else if(eventobj.attachEvent){
		obj.attachEvent('on' + evt, func);
	}
}

function killerrors() {
	return true;
}
window.onerror = killerrors;