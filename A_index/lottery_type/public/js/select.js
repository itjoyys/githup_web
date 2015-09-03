function bodyLoad(){top.onloadTag="Y";close_bet();scroll();showOrder()}
window.onresize=scroll;
function selfReload(){window.location.href=window.location}
$(document).ready(function(){$.myPlugin.rollBar("#main")});
$.myPlugin={rollBar:function(B){
	var D=$(B);
	var A=D.offset();
	var C=D.parent().width();
	$(window).scroll(function(){
		var E=$(window).scrollTop();
		if(E>=A.top){D.css({"position":"fixed","top":"0","width":C+"px"});
		if($.browser.msie){isIE=$.browser.version;switch(isIE){case"6.0":D.css({"position":"absolute","top":E});break}}}else{D.css({"position":"static","width":"auto"})}})}};
function showOrder(){
	document.getElementById("order_button").className="ord_on";
	document.getElementById("record_button").className="record_btn";
	var B=document.getElementById("bet_div");
	var C=document.getElementById("rec5_div");
	B.style.display="";
	C.style.display="none";
	document.getElementById("pls_bet").style.display="none";
	document.getElementById("info_div").style.display="";
	try{
		var D=document.getElementById("recRoad");
	D.style.display=""}catch(A){}top.open_Rec=""}
	function scroll(){
		var B=document.getElementById("info_div");
		var A=(B.style.height.replace("px","")*1);
		var C=document.getElementById("rec_frame");
		B.style.top=document.body.scrollHeight-A-15}
		function close_bet(D){
			if(D=="r"){selfReload()}
				document.getElementById("pls_bet").style.display="none";
			var B=document.getElementById("bet_order_frame");try{B.height=100}catch(A){};
			bet_order_frame.document.close();
			bet_order_frame.document.writeln('<html><link href="css/mem_order_sel.css" rel="stylesheet" type="text/css">');bet_order_frame.document.writeln("<body id=\"OHIS\" style='height:100px'>");
			bet_order_frame.document.writeln($("#pls_bet").html());bet_order_frame.document.writeln("</body></html>");B.height=bet_order_frame.document.body.offsetHeight;document.getElementById("info_div").style.display="";try{var F=document.getElementById("recRoad");F.style.display=""}catch(A){}top.scripts=new Array();top.keepGold="";top.keepGold_PR="";try{parent.body.orderRemoveALL()}catch(C){}top.open_bet=""}
			function showRec(){
	
				var B=document.getElementById("rec_frame");
				rec_frame.document.close();
				rec_frame.location.replace("/app/ssc/result8.php?uid="+top.uid+"&langx="+top.langx+"&lx="+top.lx);
				document.getElementById("order_button").className="ord_btn";document.getElementById("record_button").className="record_on";var C=document.getElementById("bet_div");var E=document.getElementById("rec5_div");
				var D=document.getElementById("info_div");
				try{var F=document.getElementById("recRoad");F.style.display=""}catch(A){}
				C.style.display="none";D.style.display="";E.style.display="";rec5_div.focus();E.style.display="";
				try{if(tenrec_id==""){top.open_Rec=""}else{top.open_Rec="Y"}}catch(A){}}function betOrder(A){bet_order_frame.document.close();bet_order_frame.height=0;document.getElementById("order_button").className="ord_on";document.getElementById("record_button").className="record_btn";document.getElementById("pls_bet").style.display="none";document.getElementById("rec5_div").style.display="none";document.getElementById("bet_div").style.display="";bet_order_frame.location.replace(A);document.getElementById("info_div").style.display="none";
				try{var C=document.getElementById("recRoad");C.style.display="none"}catch(B){}top.open_bet="Y";top.open_Rec=""}function onloadSet(C,A,D){if(A<100){A=100}document.getElementById(D).width=216;document.getElementById(D).height=A;document.getElementById("pls_bet").style.display="none";if(D=="rec_frame"){try{if(tenrec_id!=""){top.open_Rec="Y";document.getElementById("info_div").style.display="none";try{var E=document.getElementById("recRoad");E.style.display="none"}catch(B){}}else{top.open_Rec=""}}catch(B){}}};