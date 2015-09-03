var ReloadTimeID;
function onLoad(rtype){
	parent.loading = "N";
	if (rtype == "t") {
		parent.ShowType = "EO";
	} else {
		parent.ShowType = rtype.toUpperCase();
		if (rtype == "all" || rtype == "r4") parent.ShowType = "OU";
	}
	document.getElementById("ltype").value = parent.ltype;
	document.getElementById("retime").value = parent.retime;
	var obj_retime = document.getElementById("retime").value;
	var homepage = "./real_wagers_var.php?uid="+parent.uid+"&rtype="+rtype+"&page_no=0";
	homepage += Chk_gdate();	//=== 早餐需多帶日期
	parent.body_var.location = homepage;
	parent.pg = 0;
	if (document.getElementById("line_window") != null) document.getElementById('line_window').style.visibility='hidden';
	if(obj_retime != -1)
	ReloadTimeID = setInterval("parent.body_var.location.reload()",obj_retime * 1000);
}

function onUnload(){
	if (ReloadTimeID) clearInterval(ReloadTimeID);
	if (parent.GameTimerID) clearInterval(parent.GameTimerID);
	parent.loading = "Y";
	parent.ShowType = "";
	parent.pg = 0;
	parent.sel_league = "";
	if (document.getElementById("line_window") != null) document.getElementById('line_window').style.visibility='hidden';
}

function chg_retime(){
	if (document.getElementById("line_window") != null) document.getElementById('line_window').style.visibility='hidden';
	parent.retime = document.getElementById("retime").value;
	TimeValue = parent.retime;
	if(ReloadTimeID) clearInterval(ReloadTimeID);
	if(TimeValue != -1){
		parent.body_var.location.reload();
		ReloadTimeID = setInterval("parent.body_var.location.reload()",TimeValue*1000);
	}
}

function chg_ltype(){
	if (document.getElementById("line_window") != null) document.getElementById('line_window').style.visibility='hidden';
	var obj_ltype = document.getElementById("ltype");
	var gdate = Chk_gdate();
	var league = Chk_league();
	parent.body_var.location = "./real_wagers_var.php?uid="+parent.uid+"&rtype="+parent.stype_var+"&ltype="+obj_ltype.value+"&set_account="+parent.set_account+"&page_no="+parent.pg+league+gdate;
}

function chg_page(page_type){
	if (document.getElementById("line_window") != null) document.getElementById('line_window').style.visibility='hidden';
	var gdate = Chk_gdate();
	var homepage = "real_wagers.php?uid="+parent.uid+"&rtype="+page_type+gdate;
	self.location = homepage;
}

function chg_pg(pg){
	if (document.getElementById("line_window") != null) document.getElementById('line_window').style.visibility='hidden';
	if (pg == parent.pg)return;
	var gdate = Chk_gdate();
	parent.pg=pg;
	parent.loading_var = 'Y';
	parent.body_var.location = "./real_wagers_var.php?uid="+parent.uid+"&rtype="+parent.stype_var+"&ltype="+parent.ltype+"&set_account="+parent.set_account+"&page_no="+pg+gdate;
}

function chg_account(set_account){
	if (document.getElementById("line_window") != null) document.getElementById('line_window').style.visibility='hidden';
	var gdate = Chk_gdate();
	var league = Chk_league();
	parent.body_var.location = "./real_wagers_var.php?uid="+parent.uid+"&rtype="+parent.stype_var+"&ltype="+parent.ltype+"&set_account="+set_account+"&page_no="+parent.pg+league+gdate;
}

function chg_gdate(value){
	if (document.getElementById("line_window") != null) document.getElementById('line_window').style.visibility='hidden';
	parent.pg = 0;
	parent.sel_league = "";
	parent.body_var.location = "./real_wagers_var.php?uid="+parent.uid+"&rtype="+parent.stype_var+"&ltype="+parent.ltype+"&set_account="+parent.set_account+"&page_no="+parent.pg+"&gdate="+value;
}

function chg_league(value){
	if (document.getElementById("line_window") != null) document.getElementById('line_window').style.visibility='hidden';
	var gdate = Chk_gdate();
	parent.ShowGameList();
	parent.body_var.location = "./real_wagers_var.php?uid="+parent.uid+"&rtype="+parent.stype_var+"&ltype="+parent.ltype+"&set_account="+parent.set_account+"&league_id="+value+gdate;
	parent.pg = 0;
}

//====== 判斷是否有日期選項
function Chk_gdate() {
	var url_gdate = "";
	if (document.getElementById("gdate") != null) {
		if (parent.gdate == "") {
			parent.gdate = document.getElementById("gdate").value;
		} else {
			document.getElementById("gdate").value = parent.gdate;
		}
		url_gdate = "&gdate="+document.getElementById("gdate").value;
	}
	return url_gdate;
}

//====== 判斷是否有聯盟選項
function Chk_league() {
	var url_league = "";
	if (document.getElementById("sel_lid") != null) {
		document.getElementById("sel_lid").value = parent.sel_league;
		url_league = "&league_id="+document.getElementById("sel_lid").value;
	}
	return url_league;
}
function mousePos(e){ 
	var x,y; 
	var e = e||window.event; 
	return { 
		x:e.clientX+document.body.scrollLeft+document.documentElement.scrollLeft, 
		y:e.clientY+document.body.scrollTop+document.documentElement.scrollTop 
	}; 
}
//====== 已開賽 more
function show_detail(event,gid){

	document.getElementById("line_window").style.position = "absolute";
	var setX=mousePos(event).x+document.getElementById("line_window").offsetWidth;
	if(setX>document.body.offsetWidth)setX=mousePos(event).x-document.getElementById("line_window").offsetWidth;
	else setX=mousePos(event).x;
	document.getElementById("line_window").style.top = mousePos(event).y + "px";
	document.getElementById("line_window").style.left = setX+"px";　
	document.getElementById("line_form").gid.value = gid;
	if (document.getElementById("ovcid") != null)	{
		if(parent.set_account == "1") {
			document.getElementById("line_form").ovcid.value = parent.ovcid;
		} else {
			document.getElementById("line_form").ovcid.value = "";
		}
	} else {
		if (document.getElementById("cid") != null)	document.getElementById("line_form").cid.value = parent.cid;
		if (document.getElementById("sid") != null)	document.getElementById("line_form").sid.value = parent.sid;
		if (document.getElementById("aid") != null)	document.getElementById("line_form").aid.value = parent.aid;
		document.getElementById("line_form").set_acc.value = parent.set_account;
	}
	document.getElementById("line_window").style.visibility = "visible";
	document.getElementById("line_form").submit();
}

/**
* more 目前只有 FT、BS、OP 有, 變數名稱也都就 top.divFT
*/
function show_one(){
	show_table = document.getElementById("gdiv_table");
	parent.ShowData_PL_DETAIL(show_table,top.divFT);
}

//====== 單式賽事控制 more
function show_ou_detail(event,gid){
  
	//alert(document.body.scrollTop+event.clientY+20)
	document.getElementById('line_window').style.position='absolute';
	document.getElementById('line_window').style.top=mousePos(event).y + "px";
	document.getElementById('line_window').style.left=mousePos(event).x + "px";
	document.getElementById("line_form").gid.value=gid;
	document.getElementById('line_window').style.visibility='visible';
	document.getElementById("line_form").submit();
}

function show_ou_one(){
	show_table = document.getElementById("gdiv_ou_table");
	parent.ShowData_OU_DETAIL(show_table,top.divFT);
}

//====== 上半場賽事控制 more
function show_hou_detail(event,gid){

	document.getElementById('line_window').style.position='absolute';
	document.getElementById("line_window").style.top = mousePos(event).y + "px";
	document.getElementById("line_window").style.left = mousePos(event).x + "px";　
	document.getElementById("line_form").gid.value=gid;
	document.getElementById('line_window').style.visibility='visible';
	document.getElementById("line_form").submit();
}

function show_hou_one(){
	show_table = document.getElementById("gdiv_hou_table");
	parent.ShowData_HOU_DETAIL(show_table,top.divFT);
}

//====== 滾球賽事控制 more
function show_rou_detail(event,gid){

	document.getElementById('line_window').style.position='absolute';
	document.getElementById("line_window").style.top = mousePos(event).y + "px";
	document.getElementById("line_window").style.left = mousePos(event).x + "px";
	document.getElementById("line_form").gid.value=gid;
	document.getElementById('line_window').style.visibility='visible';
	document.getElementById("line_form").submit();
}

function show_rou_one(){
	show_table = document.getElementById("gdiv_rou_table");
	parent.ShowData_ROU_DETAIL(show_table,top.divFT);
}

function CheckKey(event){
	var event = event||window.event; 
	if(event.keyCode == 13) return false;
	if((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode > 95 || event.keyCode < 106)){
		alert(top.str_checkkey);
		return false;
	}
}
