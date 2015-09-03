function report_bg(){
	document.getElementById(date_num).className="report_c";
}
window.oncontextmenu=function(){
	window.event.returnValue=false;
}
function padZero(num) {
	return ((num <= 9) ? ("0" + num) : num);
}