<html><head><script>
var rtype = 're';
var odd_f_str = 'H,E';
top.today_gmt = '2015-01-22';
top.showtype = '';
var Format=new Array();
try{
Format[0]=new Array( 'H','香港盘','Y');
Format[1]=new Array( 'M','马来盘','Y');
Format[2]=new Array( 'I','印尼盘','Y');
Format[3]=new Array( 'E','欧洲盘','Y');
}catch(e){}
</script>
<script language="JavaScript">

var ReloadTimeID;
var sel_gtype = parent.sel_gtype;


function onLoadset() {
	obj_layer = document.getElementById('LoadLayer');
	obj_layer.style.display = 'none';
	obj_layer = document.getElementById('controlscroll');
	obj_layer.style.display = 'none';
	top.swShowLoveI = false;
	if (("" + eval("parent." + sel_gtype + "_lname_ary")) == "undefined") eval("parent." + sel_gtype + "_lname_ary='ALL'");
	if (("" + eval("parent." + sel_gtype + "_lid_ary")) == "undefined") eval("parent." + sel_gtype + "_lid_ary='ALL'");
	if (parent.ShowType == "" || rtype == "r") parent.ShowType = 'OU';
	if (rtype == "hr") parent.ShowType = 'OU';
	if (rtype == "re") parent.ShowType = 'RE';
	if (rtype == "pd") parent.ShowType = 'PD';
	if (rtype == "hpd") parent.ShowType = 'HPD';
	if (rtype == "t") parent.ShowType = 'EO';
	if (rtype == "f") parent.ShowType = 'F';
	if (parent.parent.leg_flag == "Y") {
		parent.parent.leg_flag = "N";
		parent.pg = 0;
		reload_var("");
	}
	parent.loading = 'N';
	if (parent.loading_var == 'N') {
		parent.ShowGameList();
		
		
	}
	if (parent.retime_flag == 'Y') {
		
		count_down();
	} else {
		var rt = document.getElementById('refreshTime');
		rt.innerHTML = top.refreshTime;
	}
	document.getElementById("odd_f_window").style.display = "none";
	if (sel_gtype == "FU") {
		if (rtype != "r") {
			if (top.showtype != 'hgft') {
				selgdate(rtype);
			}
		}
	}
}

window.onscroll = scroll;

function scroll() {
	var refresh_right = document.getElementById('refresh_right');
	refresh_right.style.top = document.body.scrollTop + 39;

	
	
	
	
	
	
	
	
	
	
	
}

function reload_var(Level) {
	parent.loading_var = 'Y';
	if (Level == "up") {
		var tmp = "./" + parent.sel_gtype + "_browse/body_var.php";
		if (parent.sel_gtype == "FU") {
			tmp = "./FT_future/body_var.php";
		}
	} else {
		var tmp = "./body_var.php";
	}

	var l_id = eval("parent.parent." + sel_gtype + "_lid_type");
	if (top.showtype == 'hgft' && parent.sel_gtype == "FU") {
		l_id = 3;
	}
	if (parent.rtype == "p3") top.hot_game = "";
	var homepage = tmp + "?uid=" + parent.uid + "&rtype=" + parent.rtype + "&langx=" + parent.langx + "&mtype=" + parent.ltype + "&page_no=" + parent.pg + "&league_id=" + l_id + "&hot_game=" + top.hot_game;
	

	if (parent.sel_gtype == "FU") {
		homepage += "&g_date=" + parent.g_date;
	}
	parent.body_var.location = homepage;
	if (rtype == "r") document.getElementById('more_window').style.display = 'none';
	
}


function count_down() {
	var rt = document.getElementById('refreshTime');
	setTimeout('count_down()', 1000);
	if (parent.retime_flag == 'Y') {
		if (parent.retime <= 0) {
			if (parent.loading_var == 'N') reload_var("");
			return;
		}
		parent.retime--;
		rt.innerHTML = parent.retime;
		
		
		
	}
}


function chg_pg(pg) {
	if (pg == parent.pg) {
		return;
	}
	parent.pg = pg;
	reload_var("");
}

function chg_wtype(wtype) {
	var l_id = eval("parent.parent." + sel_gtype + "_lid_type");
	if (top.swShowLoveI) l_id = 3;
	if (top.showtype == 'hgft' && parent.sel_gtype == "FU") {
		l_id = 3;
	}
	parent.location.href = "index.php?uid=" + top.uid + "&langx=" + top.langx + "&mtype=" + parent.ltype + "&rtype=" + wtype + "&showtype=&league_id=" + l_id;

	
	
}


function chg_league() {
	
	var legview = document.getElementById('legView');
	try {
		legFrame.location.href = "./body_var_lid.php?uid=" + parent.uid + "&rtype=" + parent.rtype + "&langx=" + parent.langx + "&mtype=" + parent.ltype;
	} catch(e) {
		legFrame.src = "./body_var_lid.php?uid=" + parent.uid + "&rtype=" + parent.rtype + "&langx=" + parent.langx + "&mtype=" + parent.ltype;

	}
	legview.style.display = '';
	legview.style.top = document.body.scrollTop + 82; 
	
	
	legview.style.left = document.getElementById('myTable').scrollLeft + 10;

	
}
function setleghi(leghight) {
	var legview = document.getElementById('legFrame');
	
	
	
	
	
	
	
	if ((leghight * 1) > 95) {
		legview.height = leghight;
	} else {

		legview.height = 95;
	}
	
	
	
}
function LegBack() {
	var legview = document.getElementById('legView');
	legview.style.display = 'none';
	reload_var("");
}


function unload() {
	clearInterval(ReloadTimeID);
}
window.onunload = unload;


function selgdate(rtype, cdate) {
	
	var date_opt = "";
	var arrDate = new Array();
	var year = '';
	var nowDate = "";
	if (top.showtype == 'hgft') {
		var tmpdate = DateAry[0].split("-");
		for (i = 0; i < parent.hotgdateArr.length; i++) {
			var tmpd = parent.hotgdateArr[i].split("-");
			if (tmpdate[1] * 1 > tmpd[0] * 1) {
				year = tmpdate[0] * 1 + 1;
			} else {
				year = tmpdate[0];
			}
			arrDate = arraySort1(arrDate, year + '-' + parent.hotgdateArr[i]);
		}
		if (cdate == '') cdate = 'ALL';
		date_opt = '<select id="g_date" name="g_date" onChange="chg_gdate()">';
		date_opt += '<option value="ALL" ' + ((cdate == 'ALL') ? 'selected': '') + '>' + top.alldata + '</option>';
		for (i = 0; i < arrDate.length; i++) {
			nowDate = showdate(arrDate[i]);
			date_opt += '<option value="' + arrDate[i] + '" ' + ((cdate == arrDate[i]) ? 'selected': '') + '>' + nowDate + '</option>';
		}
		date_opt += "</select>";
	} else {
		arrDate = DateAry;
		date_opt = "<select id=\"g_date\" name=\"g_date\" onChange=\"chg_gdate()\">";
		date_opt += "<option value=\"ALL\">" + top.alldata + "</option>";
		if (rtype == "r") {
			date_opt += "<option value=\"1\" >" + top.S_EM + "</option>";
		}
		for (i = 0; i < arrDate.length; i++) {
			nowDate = showdate(arrDate[i]);
			date_opt += "<option value=\"" + arrDate[i] + "\" >" + nowDate + "</option>";
		}
		date_opt += "</select>";
	}

	document.getElementById("show_date_opt").innerHTML = date_opt;
}
function showdate(sdate) {
	var showgdate = sdate.split("-");
	tmpsdate = showgdate[1] + "-" + showgdate[2];
	if (top.langx == "zh-tw" || top.langx == "zh-cn") {
		if ((showgdate[1] * 1) < 10) showgdate[1] = showgdate[1] * 1;
		if ((showgdate[2] * 1) < 10) showgdate[2] = showgdate[2] * 1;
		tmpsdate = showgdate[1] + top.showmonth + showgdate[2] + top.showday;
	}
	return tmpsdate;
}
function arraySort1(array, data) {
	var outarray = new Array();
	var newarray = new Array();
	for (var i = 0; i < array.length; i++) {
		if (array[i] <= data) {
			outarray.push(array[i]);
		} else {
			newarray.push(array[i]);
		}
	}
	outarray.push(data);
	for (var i = 0; i < newarray.length; i++) {
		outarray.push(newarray[i]);
	}
	return outarray;
}


function chg_gdate() {

	var obj_gdate = document.getElementById("g_date");

	parent.g_date = obj_gdate.value;
	parent.pg = 0;
	reload_var("");
}


function GetTD_X(TD_lay, GetTableID) {
	var TBar = document.getElementById(GetTableID);
	var td_x = TD_lay;
	for (var i = 0; i < TBar.rows[0].children.length; i++) {
		if (i == TD_lay) {
			break;
		}
		td_x += TBar.rows[0].children[i].clientWidth;
	}
	return td_x;
}

function GetTD_Y(AryIndex, GetTableID) {
	var TBar = document.getElementById(GetTableID);
	var td_y = parseInt(AryIndex) + 2;

	for (var i = 0; i <= parseInt(AryIndex) + 1; i++) {
		try {
			td_y += TBar.rows[i].clientHeight;
		} catch(E) {
			td_y += TBar.rows[i - 1].clientHeight;
		}
	}
	return td_y;
}


function showPicLove() {
	var gtypeNum = StatisticsGty(top.today_gmt, top.now_gmt, getGtypeShowLoveI());
	try {
		document.getElementById("fav_num").style.display = "none";
		document.getElementById("showNull").style.display = "none";
		document.getElementById("showAll").style.display = "none";
		document.getElementById("showMy").style.display = "none";
		if (gtypeNum != 0) {
			document.getElementById("live_num").innerHTML = gtypeNum;
			document.getElementById("fav_num").style.display = "block";
			if (top.hot_game != "") {
				document.getElementById("showMy").style.display = "block";
			} else {
				if (top.swShowLoveI) {
					document.getElementById("showAll").style.display = "block";
				} else {
					document.getElementById("showMy").style.display = "block";
				}
			}
		} else {
			document.getElementById("showNull").style.display = "block";
			top.swShowLoveI = false;
		}
	} catch(E) {}
}

function showAllGame(gtype) {
	top.swShowLoveI = false;
	
	eval("parent.parent." + parent.sel_gtype + "_lid_type=top." + parent.sel_gtype + "_lid['" + parent.sel_gtype + "_lid_type']");
	reload_var("");
}


function showMyLove(gtype) {
	top.swShowLoveI = true;
	
	if (top.hot_game != "") {
		top.hot_game = "";
		document.getElementById("euro_btn").style.display = '';
		document.getElementById("euro_up").style.display = 'none';
	}
	
	parent.pg = 0;
	eval("parent.parent." + parent.sel_gtype + "_lid_type='3'");
	reload_var("");
}

function StatisticsGty(today, now_gmt, gtype) {
	var out = 0;
	var array = new Array(0, 0, 0);
	var tmp = today.split("-");
	var newtoday = tmp[1] + "-" + tmp[2];
	var Months = tmp[1] * 1;
	tmp = now_gmt.split(":");
	var newgmt = tmp[0] + ":" + tmp[1];
	var tmpgday = new Array(0, 0);
	var bf = false;
	for (var i = 0; i < top.ShowLoveIarray[gtype].length; i++) {
		
		tmpday = top.ShowLoveIarray[gtype][i][1].split("<br>")[0];
		tmpgday = tmpday.split("-");
		tmpgmt = top.ShowLoveIarray[gtype][i][1].split("<br>")[1];
		if(parent.rtype == "re")
			tmpgmt = tmpgmt.substring(0,5);
		tmpgmt = time_12_24(tmpgmt);
		if (++tmpgday[0] < Months) {
			bf = true;
		} else {
			bf = false;
		}
		if (bf) {
			array[2]++;
		} else {
			if (newtoday >= tmpday) {
				if ((newtoday + " " + newgmt) >= (tmpday + " " + tmpgmt)) {
					array[0]++; 
				} else {
					array[1]++; 
				}
			} else if (newtoday < tmpday) {
				array[2]++; 
			}
		}
	}
	if (parent.sel_gtype == "FT" || parent.sel_gtype == "OP" || parent.sel_gtype == "BK" || parent.sel_gtype == "BS" || parent.sel_gtype == "VB" || parent.sel_gtype == "TN") {
		if (parent.rtype == "re") {
			out = array[0];
		} else {
			out = array[1];
		}
	} else if (parent.sel_gtype == "FU" || parent.sel_gtype == "OM" || parent.sel_gtype == "BU" || parent.sel_gtype == "BSFU" || parent.sel_gtype == "VU" || parent.sel_gtype == "TU") {
		out = array[2];
	}

	return out;
}

function time_12_24(stTime) {
	var out = "";
	var shour = stTime.split(":")[0] * 1;
	var smin = stTime.split(":")[1];
	var aop = smin.substr(smin.length - 1, 1);
	if (aop == "p") {
		if ((shour * 1) > 0 && (shour * 1) < 12) shour += 12;
	}
	out = ((shour < 10) ? "0": "") + shour + ":" + smin;
	return out;
}

function addShowLoveI(gid, getDateTime, getLid, team_h, team_c) {
	var getGtype = getGtypeShowLoveI();
	var getnum = top.ShowLoveIarray[getGtype].length;
	var sw = true;
	for (var i = 0; i < top.ShowLoveIarray[getGtype].length; i++) {
		if (top.ShowLoveIarray[getGtype][i][0] == gid && top.ShowLoveIarray[getGtype][i][1] == getDateTime) sw = false;
	}
	if (sw) {
		top.ShowLoveIarray[getGtype] = arraySort(top.ShowLoveIarray[getGtype], new Array(gid, getDateTime, getLid, team_h, team_c));
		chkOKshowLoveI();
	}

	document.getElementById("sp_" + MM_imgId(getDateTime, gid)).innerHTML = "<div class=\"fov_icon_on\" style=\"cursor:hand\" title=\"" + top.str_delShowLoveI + "\" onClick=\"chkDelshowLoveI('" + getDateTime + "','" + gid + "');\"></div>";
}
function arraySort(array, data) {
	var outarray = new Array();
	var newarray = new Array();
	for (var i = 0; i < array.length; i++) {
		if (array[i][1] <= data[1]) {
			outarray.push(array[i]);
		} else {
			newarray.push(array[i]);
		}
	}
	outarray.push(data);
	for (var i = 0; i < newarray.length; i++) {
		outarray.push(newarray[i]);
	}
	return outarray;
}

function getGtypeShowLoveI() {
	var Gtype;
	var getGtype = parent.sel_gtype;
	var getRtype = parent.rtype;
	Gtype = getGtype;
	if (getRtype == "re") {
		Gtype += "RE";
	}
	/*
	if(getGtype =="FU"||getGtype=="FT"){
		Gtype ="FT";
	}else if(getGtype =="OM"||getGtype=="OP"){
		Gtype ="OP";
	}else if(getGtype =="BU"||getGtype=="BK"){
		Gtype ="BK";
	}else if(getGtype =="BSFU"||getGtype=="BS"){
		Gtype ="BS";
	}else if(getGtype =="VU"||getGtype=="VB"){
		Gtype ="VB";
	}else if(getGtype =="TU"||getGtype=="TN"){
		Gtype ="TN";
	}else {
		Gtype ="FT";
	}
	*/

	
	return Gtype;
}
function chkOKshowLoveI() {
	var getGtype = getGtypeShowLoveI();
	var getnum = top.ShowLoveIOKarray[getGtype].length;
	var ibj = "";
	top.ShowLoveIOKarray[getGtype] = "";
	for (var i = 0; i < top.ShowLoveIarray[getGtype].length; i++) {
		tmp = top.ShowLoveIarray[getGtype][i][1].split("<br>")[0];
		top.ShowLoveIOKarray[getGtype] += tmp + top.ShowLoveIarray[getGtype][i][0] + ",";
	}
	showPicLove();
}

function chkDelshowLoveI(getDateTime, gid) {
	var getGtype = getGtypeShowLoveI();
	var tmpdata = getDateTime.split("<br>")[0] + gid;
	var tmpdata1 = "";
	var ary = new Array();
	var tmp = new Array();
	tmp = top.ShowLoveIarray[getGtype];
	top.ShowLoveIarray[getGtype] = new Array();
	for (var i = 0; i < tmp.length; i++) {
		tmpdata1 = tmp[i][1].split("<br>")[0] + tmp[i][0];
		if (tmpdata1 == tmpdata) {
			ary = tmp[i];
			continue;
		}
		top.ShowLoveIarray[getGtype].push(tmp[i]);
	}
	chkOKshowLoveI();
	var gtypeNum = StatisticsGty(top.today_gmt, top.now_gmt, getGtypeShowLoveI());
	if (top.swShowLoveI) {

		var sw = false;
		if (gtypeNum == 0) {
			top.swShowLoveI = false;
			eval("parent.parent." + parent.sel_gtype + "_lid_type=top." + parent.sel_gtype + "_lid['" + parent.sel_gtype + "_lid_type']");
			reload_var("");
		} else {
			parent.ShowGameList();
		}
	} else {
		if (gtypeNum == 0) {
			reload_var("");
		} else {
			document.getElementById("sp_" + MM_imgId(ary[1], ary[0])).innerHTML = "<div id='" + MM_imgId(ary[1], ary[0]) + "' class=\"fov_icon_out\" style=\"cursor:hand;display:none;\" title=\"" + top.str_ShowMyFavorite + "\" onClick=\"addShowLoveI('" + ary[0] + "','" + ary[1] + "','" + ary[2] + "','" + ary[3] + "','" + ary[4] + "'); \"></div>";
		}
	}
}

function chkDelAllShowLoveI() {
	var getGtype = getGtypeShowLoveI();
	top.ShowLoveIarray[getGtype] = new Array();
	top.ShowLoveIOKarray[getGtype] = "";
	if (top.swShowLoveI) {
		top.swShowLoveI = false;
		eval("parent.parent." + parent.sel_gtype + "_lid_type=top." + parent.sel_gtype + "_lid['" + parent.sel_gtype + "_lid_type']");
		parent.pg = 0;
		reload_var("");
	} else {
		parent.ShowGameList();
	}
}

function checkLoveCount(GameArray) {

	var getGtype = getGtypeShowLoveI();
	var tmpdata = "";
	var tmpdata1 = "";
	var ary = new Array();
	var tmp = new Array();
	tmp = top.ShowLoveIarray[getGtype];
	top.ShowLoveIarray[getGtype] = new Array();
	for (s = 0; s < GameArray.length; s++) {
		tmpdata = GameArray[s].datetime.split("<br>")[0] + GameArray[s].gnum_h;
		for (var i = 0; i < tmp.length; i++) {
			tmpdata1 = tmp[i][1].split("<br>")[0] + tmp[i][0];
			if (tmpdata1 == tmpdata) {
				top.ShowLoveIarray[getGtype].push(tmp[i]);
			}
		}
	}
	chkOKshowLoveI();
}

function mouseEnter_pointer(tmp) {
	
	try {
		document.getElementById(tmp.split("_")[1]).style.display = "block";
	} catch(E) {}
}

function mouseOut_pointer(tmp) {
	try {
		document.getElementById(tmp.split("_")[1]).style.display = "none";
	} catch(E) {}
}

function chkLookShowLoveI() {
	top.swShowLoveI = true;
	eval("parent.parent." + parent.sel_gtype + "_lid_type='3'");
	parent.pg = 0;
	reload_var("");
}

function MM_imgId(time, gid) {
	var tmp = time.split("<br>")[0];
	
	return tmp + gid;
}




function ChkOddfDiv() {

	
	var odd_show = "<select id=myoddType onchange=chg_odd_type()>";
	var tmp_check = "";
	for (i = 0; i < Format.length; i++) {
		
		if ((odd_f_str.indexOf(Format[i][0]) != ( - 1)) && Format[i][2] == "Y") {

			if (top.odd_f_type == Format[i][0]) {
				odd_show += "<option value=" + Format[i][0] + tmp_check + " selected>" + Format[i][1] + "</option>";
			} else {
				odd_show += "<option value=" + Format[i][0] + tmp_check + ">" + Format[i][1] + "</option>";
			}
		}
		
		
		
		
	}
	odd_show += "</select>";
	document.getElementById("Ordertype").innerHTML = odd_show;

}


function chg_odd_type() {

	var myOddtype = document.getElementById("myoddType");
	top.odd_f_type = myOddtype.options[myOddtype.selectedIndex].value;
	reload_var("");
}

function show_oddf() {
	for (i = 0; i < Format.length; i++) {
		if (Format[i][0] == top.odd_f_type) {
			document.getElementById("oddftext").innerHTML = Format[i][1];
		}
	}

}

var keep_drop_layers;
var dragapproved = false;
var iex;
var iey;
var tempx;
var tempy;
if (document.all) {
	document.onmouseup = new Function("dragapproved=false;");
}

function initializedragie(drop_layers) {
    return;
    keep_drop_layers = drop_layers;
    iex = event.clientX;
	iey = event.clientY;
	eval("tempx=" + drop_layers + ".style.pixelLeft");
	eval("tempy=" + drop_layers + ".style.pixelTop");
	dragapproved = true;
    document.onmousemove = drag_dropie;
}
function drag_dropie() {
	if (dragapproved == true) {
		eval("document.all." + keep_drop_layers + ".style.pixelLeft=tempx+event.clientX-iex");
		eval("document.all." + keep_drop_layers + ".style.pixelTop=tempy+event.clientY-iey");
		return false
	}
}

function refreshReload() {

	document.getElementById("refresh_right").className = 'refresh_M_on';
	document.getElementById("refresh_btn").className = 'refresh_on';
	document.getElementById("refresh_down").className = 'refresh_M_on';
	reload_var("");
}

function get_timer() {
	return (new Date()).getTime();
} 
function Euro() {

	if (top.hot_game != "") {
		top.hot_game = "";
		top.swShowLoveI = false;
		document.getElementById("euro_btn").style.display = '';
		document.getElementById("euro_up").style.display = 'none';
	} else {
		top.hot_game = "HOT_";
		document.getElementById("euro_btn").style.display = 'none';
		document.getElementById("euro_up").style.display = '';

	}
	parent.pg = 0;
	parent.show_page();
	reload_var("");

}

function Eurover(act) {
	
	if (act.className == "euro_btn") {
		act.className = 'euro_over';
	} else if (act.className == "euro_up") {
		act.className = 'euro_up_over';
	}
}

function Eurout(act) {
	
	if (act.className == "euro_over") {
		act.className = 'euro_btn';
	} else if (act.className == "euro_up_over") {
		act.className = 'euro_up';
	}
}

function getObjAbsolute(obj){
	var abs = new Object();
	
	abs["left"] = obj.offsetLeft;
	abs["top"] = obj.offsetTop;
	
	while (obj = obj.offsetParent) {
		abs["left"] += obj.offsetLeft;
		abs["top"] += obj.offsetTop;
	}
	
	return abs;
}

function show_lego_sort(Obj,event){
	if(document.getElementById("SortTable").style.display=="none"){
		abs=getObjAbsolute(Obj);
		document.getElementById("SortTable").style.top=abs["top"]+20;
		document.getElementById("SortTable").style.left=abs["left"]+2;
		document.getElementById("SortTable").style.display ="";
		document.getElementById("uid").value=top.uid;
		document.getElementById("langx").value=top.langx;
		document.getElementById("SortForm").action="../setSortType.php";
	}else{ 
		document.getElementById("SortTable").style.display ="none";
	}
}

function saveSortType(){
		/*try{
		document.getElementById("SortType").value = document.getElementById("SortSel").value;
		document.getElementById("uid").value=top.uid;
		document.getElementById("langx").value=top.langx;
		document.getElementById("SortForm").action="../setSortType.php";
		if(top.SortType == document.getElementById("SortSel").value ) {
			refreshReload();
			return;
		}
		document.getElementById("SortForm").submit();
		}catch(e){
		
		}*/
}

function gameSort(){
	if(top.SortType=="") top.SortType="T";
	document.getElementById("SortSel").value = top.SortType;
}
</script>



<title>body_football_re</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/cl/tpl/commonFile/style/member/mem_body_olympics.css?=29" type="text/css">

</head>

<body id="Mre" class="bodyset reRE" onload="onLoadset();">
<div id="LoadLayer" style="display: none;">loading...............................................................................</div>
<div id="showtableData" style="display:none;">
  <xmp>

          <table id="game_table"  cellspacing="0" cellpadding="0" class="game">
            <tr>
              <th class="time_re" nowrap>时间</th>
              <th class="team" nowrap>赛事</th>
              <th class="h_1x2" nowrap>独赢</th>
              <th class="h_r" nowrap>全场 - 让球</th>
              <th class="h_ou" nowrap>全场 - 大小</th>
              <th class="h_1x2" nowrap>独赢</th>
              <th class="h_r" nowrap>半场 - 让球</th>
              <th class="h_ou" nowrap>半场 - 大小</th>
            </tr>
            *showDataTR*
          </table>
  </xmp>
</div>
<!--   表格资料     -->
<div id="DataTR" style="display:none;">
	<xmp>
  <!--SHOW LEGUAGE START-->
  <tr *ST* >
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tr><td class="legicon" onClick="parent.showLeg('*LEG*')">
      <span id="*LEG*" name="*LEG*" class="showleg">
      	*LegMark*
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onClick="parent.showLeg('*LEG*')" class="leg_bar">*LEG*</td></tr></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_*ID_STR*" *TR_EVENT* *CLASS*>
    <td rowspan="3" class="b_cen"><table class="rb_box"><tr><td class="rb_time">*DATETIME*</td></tr><tr><td class="rb_score">*SCORE*</td></tr></table></td>
    <td rowspan="2" class="team_name none">
    
            <table border="0" cellspacing="0" cellpadding="0" class="re_team_box">
              <tr>
                <td class="re_team">*TEAM_H*</td>
                <td *REDCARD_H_STYLE*><span class="red_card">*REDCARD_H*</span></td>
              </tr>
            </table>
            
           <table border="0" cellspacing="0" cellpadding="0" class="re_team_box">
              <tr>
                <td class="re_team">*TEAM_C*</td>
                <td *REDCARD_C_STYLE*><span class="red_card">*REDCARD_C*</span></td>
              </tr>
            </table>

      <!--<div class="re_team_box"><span class="re_team">*TEAM_H*</span> <span class="red_card" *REDCARD_H_STYLE*>*REDCARD_H*</span></div>
      <div class="re_team_box"><span class="re_team">*TEAM_C*</span> <span class="red_card" *REDCARD_C_STYLE*>*REDCARD_C*</span></div> -->
      
      </td>
    <td class="b_cen">*RATIO_MH*</td>
    <td class="b_rig"><span class="con">*CON_RH*</span> <span class="ratio">*RATIO_RH*</span></td>
    <td class="b_rig"><span class="con">*CON_OUH*</span> <span class="ratio">*RATIO_OUH*</span></td>
    <td class="b_1st">*RATIO_HMH*</td>
    <td class="b_1stR"><span class="con">*CON_HRH*</span> <span class="ratio">*RATIO_HRH*</span></td>
    <td class="b_1stR"><span class="con">*CON_HOUH*</span> <span class="ratio">*RATIO_HOUH*</span></td>
  </tr>
  <tr id="TR1_*ID_STR*" *TR_EVENT* *CLASS*>
    <td class="b_cen">*RATIO_MC*</td>
    <td class="b_rig"><span class="con">*CON_RC*</span> <span class="ratio">*RATIO_RC*</span></td>
    <td class="b_rig"><span class="con">*CON_OUC*</span> <span class="ratio">*RATIO_OUC*</span></td>
    <td class="b_1st">*RATIO_HMC*</td>
    <td class="b_1stR"><span class="con">*CON_HRC*</span> <span class="ratio">*RATIO_HRC*</span></td>
    <td class="b_1stR"><span class="con">*CON_HOUC*</span> <span class="ratio">*RATIO_HOUC*</span></td>
  </tr>
  <tr id="TR2_*ID_STR*" *TR_EVENT* *CLASS*>
    <td class="drawn_td">*MYLOVE*<!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td>
    <td class="b_cen">*RATIO_MN*</td>
    <td colspan="2" valign="top" class="b_cen">&nbsp;</td>
    <td class="b_1st" >*RATIO_HMN*</td>
    <td colspan="2" valign="top" class="b_1st">&nbsp;</td>
  </tr>
</xmp>
</div>
<!--右方刷新钮--><div id="refresh_right" style="position: absolute; top: 39px; left: 754px;" class="refresh_M_btn" onclick="this.className='refresh_M_on';javascript:refreshReload()"><span>刷新</span></div>
<div id="NoDataTR" style="display:none;">
	<xmp>
	   <td colspan="20" class="no_game">您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td>
	 </xmp>
</div>

<table border="0" cellpadding="0" cellspacing="0" id="myTable">
	<tbody>
		<tr>
			<td>
				<table border="0" cellpadding="0" cellspacing="0" id="box">
					<tbody>
						<tr>
							<td class="top">
								<h1><em>今日足球 : 滚球</em></h1>
							</td>
						</tr>
				  
						<tr>
							<td class="mem">
								<h2>
									<table width="100%" border="0" cellpadding="0" cellspacing="0" id="fav_bar">
										<tbody>
											<tr>
												<td id="page_no">
													<span id="show_date_opt"></span><span id="pg_txt"></span>
													<div id="euro_btn" class="euro_btn" onclick="Euro();" onmouseover="Eurover(this);" onmouseout="Eurout(this);" style="display: none;"><!--奥运--></div>
													<div id="euro_up" class="euro_up" onclick="Euro();" onmouseover="Eurover(this);" onmouseout="Eurout(this);" style="display: none;"><!--所有赛事--></div>
												</td>
												<td id="tool_td">
													<table border="0" cellspacing="0" cellpadding="0" class="tool_box">
														<tbody>
															<tr>
																<td id="fav_btn">
																	<div id="fav_num" title="清空" onclick="chkDelAllShowLoveI();" style="display: none;"><!--我的最爱场数--><span id="live_num">0</span></div>
																	<div id="showNull" title="无资料" class="fav_null" style="display: block;"></div>
																	<div id="showAll" title="所有赛事" onclick="showAllGame('FT');" style="display: none;" class="fav_on"></div>
																	<div id="showMy" title="我的最爱" onclick="showMyLove('FT');" class="fav_out" style="display: none;"></div>                        </td>
																<td class="refresh_btn" id="refresh_btn" onclick="this.className='refresh_on';"><!--秒数更新--><div onclick="javascript:refreshReload()"><font id="refreshTime">39</font></div></td>
																<td class="leg_btn"><div onclick="javascript:chg_league();" id="sel_league">选择联赛 (<span id="str_num">全部</span>)</div></td>
																<td id="SortGame" class="SortGame" name="SortGame">
																	<select id="SortSel" onchange="saveSortType();">
																		 <option value="C">按联盟排序</option>
																		 <option value="T">按时间排序</option>
																	</select>
																</td>
																<td class="OrderType" id="Ordertype"><select id="myoddType" onchange="chg_odd_type()"><option value="H" selected="">香港盘</option><option value="E">欧洲盘</option></select></td>
															</tr>
														</tbody>
													</table>                
												</td>
											</tr>
										</tbody>
									</table>
								</h2>

								<!--     资料显示的layer     -->
								<div id="showtable">
  

          <table id="game_table" cellspacing="0" cellpadding="0" class="game">
            <tbody><tr>
              <th class="time_re" nowrap="">时间</th>
              <th class="team" nowrap="">赛事</th>
              <th class="h_1x2" nowrap="">独赢</th>
              <th class="h_r" nowrap="">全场 - 让球</th>
              <th class="h_ou" nowrap="">全场 - 大小</th>
              <th class="h_1x2" nowrap="">独赢</th>
              <th class="h_r" nowrap="">半场 - 让球</th>
              <th class="h_ou" nowrap="">半场 - 大小11</th>
            </tr>
            
	
  <!--SHOW LEGUAGE START-->
  <tr style="display: ;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('美洲2015锦标赛U20(在牙买加)')">
      <span id="美洲2015锦标赛U20(在牙买加)" name="美洲2015锦标赛U20(在牙买加)" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('美洲2015锦标赛U20(在牙买加)')" class="leg_bar">美洲2015锦标赛U20(在牙买加)</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2240098" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="3" class="b_cen"><table class="rb_box"><tbody><tr><td class="rb_time">下 10'</td></tr><tr><td class="rb_score">1&nbsp;-&nbsp;1</td></tr></tbody></table></td>
    <td rowspan="2" class="team_name none">
    
            <table border="0" cellspacing="0" cellpadding="0" class="re_team_box">
              <tbody><tr>
                <td class="re_team"> 海地 U20  </td>
                <td style="display:none;"><span class="red_card">*REDCARD_H*</span></td>
              </tr>
            </tbody></table>
            
           <table border="0" cellspacing="0" cellpadding="0" class="re_team_box">
              <tbody><tr>
                <td class="re_team"> 墨西哥 U20  </td>
                <td style="display:none;"><span class="red_card">*REDCARD_C*</span></td>
              </tr>
            </tbody></table>

      <!--<div class="re_team_box"><span class="re_team">*TEAM_H*</span> <span class="red_card" *REDCARD_H_STYLE*>*REDCARD_H*</span></div>
      <div class="re_team_box"><span class="re_team">*TEAM_C*</span> <span class="red_card" *REDCARD_C_STYLE*>*REDCARD_C*</span></div> -->
      
      </td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RM','gid=118457361&amp;uid=5bd675adf95a8cb665ba5&amp;odd_f_type=H&amp;type=H&amp;gnum=40098&amp;langx=zh-cn');" title=" 海地 U20  "><font true="">10.20</font></a></td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RE','gid=118457361&amp;uid=5bd675adf95a8cb665ba5&amp;odd_f_type=H&amp;type=H&amp;gnum=40098&amp;strong=C&amp;langx=zh-cn');" title=" 海地 U20  "><font true="">0.890</font></a></span></td>
    <td class="b_rig"><span class="con">大3.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','ROU','gid=118457361&amp;uid=5bd675adf95a8cb665ba5&amp;odd_f_type=H&amp;type=H&amp;gnum=40098&amp;langx=zh-cn');" title="大"><font true="">1.000</font></a></span></td>
    <td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HRM','gid=118457371&amp;uid=5bd675adf95a8cb665ba5&amp;odd_f_type=H&amp;type=H&amp;gnum=40098&amp;langx=zh-cn');" title=" 海地 U20  "><font undefined=""></font></a></td>
    <td class="b_1stR"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HRE','gid=118457371&amp;uid=5bd675adf95a8cb665ba5&amp;odd_f_type=H&amp;type=H&amp;gnum=40098&amp;strong=&amp;langx=zh-cn');" title=" 海地 U20  "><font undefined=""></font></a></span></td>
    <td class="b_1stR"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HROU','gid=118457371&amp;uid=5bd675adf95a8cb665ba5&amp;odd_f_type=H&amp;type=H&amp;gnum=40098&amp;langx=zh-cn');" title="大"><font undefined=""></font></a></span></td>
  </tr>
  <tr id="TR1_01-2240098" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RM','gid=118457361&amp;uid=5bd675adf95a8cb665ba5&amp;odd_f_type=H&amp;type=C&amp;gnum=40097&amp;langx=zh-cn');" title=" 墨西哥 U20  "><font true="">1.56</font></a></td>
    <td class="b_rig"><span class="con">1</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RE','gid=118457361&amp;uid=5bd675adf95a8cb665ba5&amp;odd_f_type=H&amp;type=C&amp;gnum=40097&amp;strong=C&amp;langx=zh-cn');" title=" 墨西哥 U20  "><font true="">0.990</font></a></span></td>
    <td class="b_rig"><span class="con">小3.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','ROU','gid=118457361&amp;uid=5bd675adf95a8cb665ba5&amp;odd_f_type=H&amp;type=C&amp;gnum=40097&amp;langx=zh-cn');" title="小"><font true="">0.860</font></a></span></td>
    <td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HRM','gid=118457371&amp;uid=5bd675adf95a8cb665ba5&amp;odd_f_type=H&amp;type=C&amp;gnum=40097&amp;langx=zh-cn');" title=" 墨西哥 U20  "><font undefined=""></font></a></td>
    <td class="b_1stR"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HRE','gid=118457371&amp;uid=5bd675adf95a8cb665ba5&amp;odd_f_type=H&amp;type=C&amp;gnum=40097&amp;strong=&amp;langx=zh-cn');" title=" 墨西哥 U20  "><font undefined=""></font></a></span></td>
    <td class="b_1stR"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HROU','gid=118457371&amp;uid=5bd675adf95a8cb665ba5&amp;odd_f_type=H&amp;type=C&amp;gnum=40097&amp;langx=zh-cn');" title="小"><font undefined=""></font></a></span></td>
  </tr>
  <tr id="TR2_01-2240098" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td class="drawn_td"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2240098"><div id="01-2240098" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('40098','01-22<br>09:00p','美洲2015锦标赛U20(在牙买加)',' 海地 U20  ',' 墨西哥 U20  '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td>
    <td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','RM','gid=118457361&amp;uid=5bd675adf95a8cb665ba5&amp;odd_f_type=H&amp;type=N&amp;gnum=40097&amp;langx=zh-cn');" title="和"><font true="">2.60</font></a></td>
    <td colspan="2" valign="top" class="b_cen">&nbsp;</td>
    <td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HRM','gid=118457371&amp;uid=5bd675adf95a8cb665ba5&amp;odd_f_type=H&amp;type=N&amp;gnum=40097&amp;langx=zh-cn');" title="和"><font undefined=""></font></a></td>
    <td colspan="2" valign="top" class="b_1st">&nbsp;</td>
  </tr>


          </tbody></table>
  
</div></td>
						</tr>
						<tr>
							<td id="foot"><b>&nbsp;</b></td>
						</tr>
					</tbody>
				</table>
				<!--下方刷新钮--><div id="refresh_down" class="refresh_M_btn" onclick="this.className='refresh_M_on';javascript:refreshReload()"><span>刷新</span></div>
			</td>
		</tr>
	</tbody>
</table>
<div class="more" id="more_window" name="more_window" style="position:absolute; display:none; ">
  <iframe id="showdata" name="showdata" scrolling="no" frameborder="NO" border="0" framespacing="0" noresize="" topmargin="0" leftmargin="0" marginwidth="0" marginheight="0"></iframe>
</div>
<!--选择联赛-->
<div id="legView" style="display:none;" class="legView">
    <div class="leg_head" onmousedown="initializedragie('legView')"></div>
    
<div><iframe id="legFrame" scrolling="no" frameborder="no" border="0" allowtransparency="true"></iframe></div>


    <div class="leg_foot"></div>
</div>
<div id="controlscroll" style="position: absolute; display: none;"><table border="0" cellspacing="0" cellpadding="0" class="loadBox"><tbody><tr><td><!--loading--></td></tr></tbody></table></div>







<div id="odd_f_window" style="display: none; position: absolute;">
<table id="odd_group" width="100" border="0" cellspacing="1" cellpadding="1">
		<tbody><tr>
			<td class="b_hline">盘口</td>
		</tr>
		<tr>
			<td class="b_cen" width="100">
				<span id="show_odd_f"></span></td>
		</tr>
	</tbody></table>
</div>

<!--盘口选择--></body><!-- 盘口选择 --></html>