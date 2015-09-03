//window.onload = function (){
	/*
	try{
  	uid=opener.top.uid;
	}catch(E){
  	alert(top.mem_logut);
  	window.close();
}
	if (""+select_date=="undefined") select_date="";
	showclass(select_date);
	*/
	/*
	if(){
	document.getElementById("today").className="scr_out";
	document.getElementById("yesterday").className="scr_out";
	document.getElementById("before").className="scr_ou";
	}
	*/
	
	
/*	
	var myOddtype=document.getElementById("select2");
	for (i = 0; i < myOddtype.length; i++) {
		if(myOddtype.options[i].value==select_date){
			myOddtype.selectedIndex=i;
		}
	}	
		
		var pge_opt="";
		var str_sel="";	
		pge_opt = "<select id=\"select\" name=\"select\" onChange=\"chg_pge()\">";
		for (i = 0; i < t_page; i++) {
			str_sel="";
			if(page_no == i){
				str_sel="selected";
			}
			pge_opt+= "<option value=\""+i+"\" "+str_sel+">"+(i+1)+"</option>";
		}
		pge_opt+= "</select>";
			document.getElementById("t_pge").innerHTML = pge_opt;
	
*/	
//}
$(document).ready(function(){
	$('.b_rig').hover(
	  function () {
			$(this).toggleClass('color_bg3');
	  },
	  function () {
			$(this).toggleClass('color_bg3');
	  }

	);
});
function reload_var(){
//	location.reload();
	var str = document.getElementById ("findField").value;
	uid=opener.top.uid;
	langx=top.langx;
	self.location.href="scroll_history.php?uid="+uid+"&langx="+langx+"&select_date="+select_date+"&fField="+str;
}
function showclass(tmp_date){
	if(tmp_date==-4){
		document.getElementById("all").className="scr_on";
		document.getElementById("today").className="scr_out";
		document.getElementById("yesterday").className="scr_out";
		document.getElementById("before").className="scr_out";
	}	
	if(tmp_date==0){
		document.getElementById("all").className="scr_out";
		document.getElementById("today").className="scr_on";
		document.getElementById("yesterday").className="scr_out";
		document.getElementById("before").className="scr_out";
	}
	if(tmp_date==-1){
		document.getElementById("all").className="scr_out";
		document.getElementById("today").className="scr_out";
		document.getElementById("yesterday").className="scr_on";
		document.getElementById("before").className="scr_out";
		
	}
	if(tmp_date==-2){
		document.getElementById("all").className="scr_out";
		document.getElementById("today").className="scr_out";
		document.getElementById("yesterday").className="scr_out";
		document.getElementById("before").className="scr_on";
		
	}	
	if(tmp_date==""){
		document.getElementById("all").className="scr_out";
		document.getElementById("today").className="scr_out";
		document.getElementById("yesterday").className="scr_out";
		document.getElementById("before").className="scr_out";	
	}	
}
function chg_date(tmp_date){
	var str = document.getElementById ("findField").value;
	showclass(tmp_date);
	top.tmp_date = tmp_date;
	//var myOddtype=document.getElementById("select2");
	//var tmp_date=myOddtype.options[myOddtype.selectedIndex].value;
	//self.location.href="scroll_history.php?uid="+top.uid+"&langx="+top.langx+"&select_date="+tmp_date;
	try{
  	uid=opener.top.uid;
	}catch(E){
  	alert(top.mem_logut);
  	window.close();
}
	self.location.href="scroll_history.php?uid="+uid+"&langx="+langx+"&select_date="+tmp_date+"&fField="+str;
}

function chg_pge(){
	var myOddtype=document.getElementById("select");
	var page_no=myOddtype.value;
	//self.location.href="scroll_history.php?uid="+top.uid+"&langx="+top.langx+"&select_date="+select_date+"&page_no="+page_no;
	try{
  	uid=opener.top.uid;
	}catch(E){
  	alert(top.mem_logut);
 	 window.close();
}
	self.location.href="scroll_history.php?uid="+uid+"&langx="+langx+"&select_date="+select_date+"&page_no="+page_no;
	
}
function overbars(obj,color){
	obj.className=color;
}
function outbars(obj,color){
	obj.className=color;
}

//穓碝ゅ
 function FindNext () {
  	try{
  	uid=opener.top.uid;
	}catch(E){
  	alert(top.mem_logut);
  	window.close();
	}
 var str = document.getElementById ("findField").value;
   if (str == "") {
	   // alert ("Please enter some text to search!");
	   //alert(top.message031);
		//return;
	self.location.href="scroll_history.php?uid="+uid+"&langx="+langx+"&select_date="+select_date;
	return;
	}
 self.location.href="scroll_history.php?uid="+uid+"&langx="+langx+"&fField="+str+"&find=true"+"&select_date="+select_date;

}

function init_scroll(){
	try{
  	uid=opener.top.uid;
	}catch(E){
  	alert(top.mem_logut);
  	window.close();
	}
	if (""+select_date=="undefined") select_date="";
	showclass(select_date);
	
	document.getElementById ("findField").value=fField;
	x=document.getElementById ("findField");
	x.focus();
	if(x.setSelectionRange){
		x.setSelectionRange(x.value.length, x.value.length);
	}else{ 
		o=x.createTextRange(); 
		o.moveStart('character',x.value.length); 
		o.collapse(true); 
		o.select();
	}
}
function checkKey(e){
	var findbutton = document.getElementById('findbutton');
	if(e ==13){  // Enter
	 findbutton.focus();	 
	}
}