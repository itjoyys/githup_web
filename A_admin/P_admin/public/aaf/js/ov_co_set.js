document.onkeypress=checkfunc;
function checkfunc(e) {
	switch(event.keyCode){

	}
}
function Chk_acc(){
	rs_form.act.value='Y';
	close_win();
}
function Chk_acc2(){
	rs_form_2.act.value='Y';
	close_win_2();
}
function mousePos(e){ 
	var x,y; 
	var e = e||window.event; 
	return { 
		x:e.clientX+document.body.scrollLeft+document.documentElement.scrollLeft, 
		y:e.clientY+document.body.scrollTop+document.documentElement.scrollTop 
	}; 
}
function show_win(event,vs_str,rtype,sc,so,se,war_set_1,war_set_2,war_set_3,war_set_4,war_set_5,add_count,instart,instrat_2,instart_3,instart_4,instart_5,kind) {
	document.getElementById("r_title").innerHTML = '<font color="#FFFFFF">請輸入'+ vs_str + '退水</font>';
	j1=0;
	j2=0;
	j3=0;
	j4=0;
	j5=0;
	var d=add_count;
	while (rs_form.war_set_1.length){
		document.rs_form.war_set_1.options[0]=null;
	}
	while (rs_form.war_set_2.length){
		document.rs_form.war_set_2.options[0]=null;
	}
	while (rs_form.war_set_3.length){
		document.rs_form.war_set_3.options[0]=null;
	}
	while (rs_form.war_set_4.length){
		document.rs_form.war_set_4.options[0]=null;
	}
	while (rs_form.war_set_5.length){
		document.rs_form.war_set_5.options[0]=null;
	}
	for(var i=0;i<=instart;i+=d){
		document.rs_form.war_set_1.options[j1]=new Option(i,i);
		if(i==war_set_1) document.rs_form.war_set_1.selectedIndex=j1;
		j1++;
	}
	for(var i=0;i<=instrat_2;i+=d){
		document.rs_form.war_set_2.options[j2]=new Option(i,i);
		if(i==war_set_2) document.rs_form.war_set_2.selectedIndex=j2;
		j2++;
	}
	for(var i=0;i<=instart_3;i+=d){
		document.rs_form.war_set_3.options[j3]=new Option(i,i);
		if(i==war_set_3) document.rs_form.war_set_3.selectedIndex=j3;
		j3++;
	}
	for(var i=0;i<=instart_4;i+=d){
		document.rs_form.war_set_4.options[j4]=new Option(i,i);
		if(i==war_set_4) document.rs_form.war_set_4.selectedIndex=j4;
		j4++;
	}
	for(var i=0;i<=instart_5;i+=d){
		document.rs_form.war_set_5.options[j5]=new Option(i,i);
		if(i==war_set_5) document.rs_form.war_set_5.selectedIndex=j5;
		j5++;
	}
	rs_form.kind.value=kind;
	rs_form.rtype.value=rtype;
	rs_form.SC.value=sc;
	rs_form.SO.value=so;
	rs_form.SE.value=se;


	var setX = mousePos(event).x + 355;
	var setY = mousePos(event).y + 210;

	if(setX>document.body.offsetWidth)setX = mousePos(event).x - 355;
	else setX = mousePos(event).x;

	if(setY>document.body.offsetHeight)setY = mousePos(event).y - 210;
	else setY = mousePos(event).y

	document.getElementById("rs_window").style.top = setY + 'px';
	document.getElementById("rs_window").style.left = setX + 'px';
	document.getElementById("rs_window_2").style.display = "none";
	document.getElementById("rs_window").style.display = "";
}
function show_win2(event,vs_str,rtype,sc,so,se,war_set_1,add_count,instart,kind) {
	document.getElementById("r_title_2").innerHTML = '<font color="#FFFFFF">請輸入'+ vs_str + '退水</font>';
	j1=0;
	var d=add_count;
	while (rs_form_2.war_set_1.length){
		document.rs_form_2.war_set_1.options[0]=null;
	}
	for(var i=0;i<=instart;i+=d){
		document.rs_form_2.war_set_1.options[j1]=new Option(i,i);
		if(i==war_set_1) document.rs_form_2.war_set_1.selectedIndex=j1;
		j1++;
	}
	rs_form_2.kind.value=kind;
	rs_form_2.SC_2.value=sc;
	rs_form_2.SO_2.value=so;
	rs_form_2.SE_2.value=se;
	rs_form_2.rtype.value=rtype;

	var setX = mousePos(event).x + 280;
	var setY = mousePos(event).y + 190;
	
	if(setX>document.body.offsetWidth)setX = mousePos(event).x - 280;
	else setX = mousePos(event).x;

	if(setY>document.body.offsetHeight)setY = mousePos(event).y - 190;
	else setY = mousePos(event).y

	document.getElementById("rs_window_2").style.top = setY + 'px';
	document.getElementById("rs_window_2").style.left = setX + 'px';
	document.getElementById("rs_window").style.display = "none";
	document.getElementById("rs_window_2").style.display = "";
}
function close_win() {
	document.getElementById("rs_window").style.display = "none";
}
function close_win_2() {
	document.getElementById("rs_window_2").style.display = "none";
}
function count_so(a){
	switch(a){
		case(1):
		b=eval(document.getElementsByName('SC')[0].value)/2;
		document.getElementsByName('SO')[0].value=b;
		break;
		case(2):
		b=eval(document.getElementsByName('SC_2')[0].value)/2;
		document.getElementsByName('SO_2')[0].value=b;
		break;
	}
}