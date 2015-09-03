<!--
document.onkeypress=checkfunc;
function checkfunc(e) {
	switch(event.keyCode){ }
}
function Chk_acc(){
	rs_form.act.value='Y';
	close_win();
}
function mousePos(e){ 
	var x,y; 
	var e = e||window.event; 
	return { 
		x:e.clientX+document.body.scrollLeft+document.documentElement.scrollLeft, 
		y:e.clientY+document.body.scrollTop+document.documentElement.scrollTop 
	}; 
}
function show_win(event,vs_str,rtype,sc,so,se,war_set,add_count,instart,kind) {
	document.getElementById("r_title").innerHTML = '<font color="#FFFFFF">請輸入' + vs_str + '退水</font>';
	j1=0;
	var d=add_count;
	while (rs_form.war_set.length){
		document.rs_form.war_set.options[0]=null;
	}
	for(var i=0;i<=instart;i+=d){
		document.rs_form.war_set.options[j1]=new Option(i,i);
		if(i==war_set) document.rs_form.war_set.selectedIndex=j1;
		j1++;
	}
	rs_form.kind.value=kind;
	rs_form.rtype.value=rtype;
	rs_form.SC.value=sc;
	rs_form.SO.value=so;
	rs_form.SE.value=se;
	
	var setX = mousePos(event).x + 375;
	var setY = mousePos(event).y + 210;

	if(setX>document.body.offsetWidth)setX = (mousePos(event).x - 375);
	else setX = mousePos(event).x;

	if(setY>document.body.offsetHeight)setY = mousePos(event).y - 210;
	else setY = mousePos(event).y

	document.getElementById("rs_window").style.top = setY + 'px';
	document.getElementById("rs_window").style.left = setX + 'px';
	document.getElementById("rs_window").style.display="";
	Chg_Sc_Mcy();
	Chg_So_Mcy();
}
function roundBy(num,num2) {
	return(Math.floor((num)*num2)/num2);
}
function close_win() {
	document.getElementById("rs_window").style.display = "none";
}

function Chg_Sc_Mcy(){
	ratio=eval(document.getElementsByName('ratio')[0].value);
	tmp_sc=ratio*eval(document.getElementsByName('SC')[0].value);
	document.getElementById('mcy_sc').innerHTML=tmp_sc;
}

function Chg_So_Mcy(){
	ratio=eval(document.getElementsByName('ratio')[0].value);
	tmp_so=ratio*eval(document.getElementsByName('SO')[0].value);
	document.getElementById('mcy_so').innerHTML=tmp_so;
}

function count_so(){
	b=eval(document.getElementsByName('SC')[0].value)/2;
	document.getElementsByName('SO')[0].value=b;
}
//-->