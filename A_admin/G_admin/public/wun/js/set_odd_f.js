
function show_line(event){
	var show =  document.getElementById("show_div");
	var txt_cb = $("#show_checkbox").html();
	var txt_tb = $("#show_table").html();
	var tmp = "";
	
	var event=event||window.event;
	show.style.display = "";
	show.style.left = (pointerX()+10)+"px";
	show.style.top = pointerY()+"px";
	
	for(i=0;i < Format.length; i++){
		if(Format[i][2]=="Y") {
			tmp +=txt_cb;
			tmp = tmp.replace(/\*CODE\*/g, Format[i][0]);
			tmp = tmp.replace("*CODENAME*", Format[i][1]);
			tmp = tmp.replace("*CHECK*",((odd_f.indexOf(Format[i][0])==(-1))? "":"checked"));
		}
	}
	txt_tb =txt_tb.replace("*SHOWTABLE*", tmp);
	$("#show_div").html(txt_tb);
}
function chk_Date(){
	var str="";
	var outstr="";
	
	for(i=0;i < Format.length; i++){
	aa=$("input[name='lineData["+Format[i][0]+"]']");
		if($("input[name='lineData["+Format[i][0]+"]']") != null)
		
		if(aa.attr("checked")=="checked"){
			if(str!=""){
				str+=",";
			}
			str+=Format[i][0];
		}
	}
	odd_f=str;
	show_Line_Date();
}
function close_show(){
	var show = document.getElementById("show_div");
	show.style.display="none";
	
}
function show_Line_Date(){
	var str="";
	for(i=0;i < Format.length; i++){
		if(odd_f.indexOf(Format[i][0])!=(-1)){
			if(str!=""){
				str+=",";
			}
			str+=Format[i][1];
		}
	}
	if(str == ""){
		alert(top.str_oddf);
	}else{
		document.getElementById('show_cb').innerHTML ="<font style='color: 960000;'>"+str+"</font>";
		document.getElementById('odd_f_str').value =odd_f;
	}
	close_show();
}

function show_oddf(sta){
	if(sta =="show"){
		document.getElementsByName('oddf_edit')[0].disabled="";
	}else{
		document.getElementsByName('oddf_edit')[0].disabled="true";
	}
	odd_f="H";
	show_Line_Date();
}
function pointerX() { return event.pageX || (event.clientX + (document.documentElement.scrollLeft || document.body.scrollLeft)); }
function pointerY() { return event.pageY || (event.clientY + (document.documentElement.scrollTop || document.body.scrollTop)); }