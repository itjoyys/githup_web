<html><head><script>
var DateAry = new Array('2015-01-23','2015-01-24','2015-01-25','2015-01-26','2015-01-27','2015-01-28','2015-01-29','2015-01-30','2015-01-31','2015-02-01','2015-02-02');
var rtype = 'r';
var odd_f_str = 'H,E';
var lid_arr=new Array();

top.lid_arr=lid_arr;
top.today_gmt = '2015-01-22';
top.showtype = 'future';
var Format=new Array();
try{Format[0]=new Array( 'H','香港盘','Y');
Format[1]=new Array( 'M','马来盘','Y');
Format[2]=new Array( 'I','印尼盘','Y');
Format[3]=new Array( 'E','欧洲盘','Y');}catch(e){}
</script>
<script>
top.guest_name = "欢迎光临";
top.guest_tip = "您还未登录，请先登录！";
top.running_reeor = "执行错误,代码";
top.str_input_opwd = "请输入旧密码!!";
top.str_input_pwd = "密码请务必输入!!";
top.str_input_repwd = "确认密码请务必输入!!";
top.str_err_pwd = "密码确认错误,请重新输入!!";
top.str_pwd_limit = "密码必须是6-12个字元组成。";
top.str_pwd_limit2 = "密码必须由数字和字母组成。";
top.str_pwd_NoChg = "您的新密码必须和现用密码不一样。";
top.str_pwd_NowErr="您的现用密码不正确，请重试。";
top.str_input_longin_id = "登录帐号请务必输入!!";
top.str_longin_limit1 = "登录帐号最少必须有2个英文大小写字母和数字(0~9)组合输入限制(6~12字元)";
top.str_longin_limit2 = "您的登录帐号需使用字母加上数字!!";
top.str_o="单";
top.str_e="双";
top.str_checknum="验证码错误,请重新输入";
top.str_irish_kiss="和";
top.dPrivate="私域";
top.dPublic="公有";
top.grep="群组";
top.grepIP="群组IP";
top.IP_list="IP列表";
top.Group="组别";
top.choice="请选择";
top.account="请输入帐号!!";
top.password="请输入密码!!";
top.S_EM="特早";
top.alldata="全部";
top.webset="资讯网";
top.str_renew="更新";
top.outright="冠军";
top.financial="金融";
top.str_HCN = new Array("主","客","无");

//====== Live TV
top.str_FT = "足球";
top.str_BK = "篮球";
top.str_TN = "网球";
top.str_VB = "排球";
top.str_BS = "棒球";
top.str_OP = "其他";

top.str_order_FT = "足球";
top.str_order_BK = "篮球 / 美式足球 / 橄榄球";
top.str_order_TN = "网球";
top.str_order_VB = "排球 / 羽毛球 / 兵乓球";
top.str_order_BS = "棒球";
top.str_order_OP = "其他";


top.str_fs_FT = "足球 : ";
top.str_fs_BK = "篮球和美式足球 : ";
top.str_fs_TN = "网球 : ";
top.str_fs_VB = "排球 : ";
top.str_fs_BS = "棒球 : ";
top.str_fs_OP = "其他体育 : ";

top.str_game_list = "所有球类";
top.str_date_list = "所有日期";
top.str_second = "秒";
top.str_demo = "样本播放";
top.str_alone = "独立";
top.str_back = "返回";
top.str_RB = "LIVE";

top.str_ShowMyFavorite="我的最爱";
top.str_ShowAllGame="全部赛事";
top.str_delShowLoveI="移出";

top.str_ShowMyFavorite="我的最愛";
top.str_ShowAllGame="全部賽事";
top.str_delShowLoveI="移出";
top.str_SortType="按時間排序";
top.str_SortTypeC="按联盟排序";
top.str_SortTypeT="按時間排序";

top.strRtypeSP = new Array();
top.strRtypeSP["PGF"]="最先进球";
top.strRtypeSP["OSF"]="最先越位";
top.strRtypeSP["STF"]="最先替补球员";
top.strRtypeSP["CNF"]="第一颗角球";
top.strRtypeSP["CDF"]="第一张卡";
top.strRtypeSP["RCF"]="会进球";
top.strRtypeSP["YCF"]="第一张黄卡";
top.strRtypeSP["GAF"]="有失球";
top.strRtypeSP["PGL"]="最后进球";
top.strRtypeSP["OSL"]="最后越位";
top.strRtypeSP["STL"]="最后替补球员";
top.strRtypeSP["CNL"]="最后一颗角球";
top.strRtypeSP["CDL"]="最后一张卡";
top.strRtypeSP["RCL"]="不会进球";
top.strRtypeSP["YCL"]="最后一张黄卡";
top.strRtypeSP["GAL"]="没有失球";
top.strRtypeSP["PG"]="最先/最后进球球队";
top.strRtypeSP["OS"]="最先/最后越位球队";
top.strRtypeSP["ST"]="最先/最后替补球员球队";
top.strRtypeSP["CN"]="第一颗/最后一颗角球";
top.strRtypeSP["CD"]="第一张/最后一张卡";
top.strRtypeSP["RC"]="会进球/不会进球";
top.strRtypeSP["YC"]="第一张/最后一张黄卡";
top.strRtypeSP["GA"]="有失球/没有失球";


top.strOver="大";
top.strUnder="小";
top.strOdd="单";
top.strEven="双";


//下注警语
top.message001="请输入下注金额。";
top.message002="只能输入数字!!";
top.message003="最低投注额是";
top.message004="对不起,本场有下注金额最高: ";
top.message005=" 元限制!!";
top.message006="最高投注额设在";
top.message007="总下注金额已超过单场限额。";
top.message008="本场累计下注共: ";
top.message009="\n总下注金额已超过单场限额";
top.message010="下注金额不可大於信用额度。";
top.message011="可赢金额：";
top.message012="<br>确定进行下注吗?";
top.message013="确定进行下注吗?<br>";
top.message014='未输入下注金额!!!';
top.message015="下注金额只能输入数字";
top.message016="\n\n确定进行下注吗?";
top.message017="串1";
top.message018="队联碰";
top.message019="您必须选择至少";
top.message020="个队伍,否则不能下注!!";
top.message021="不接受";
top.message022="串过关投注!!";
top.message023="请输入欲下注金额!!";
top.message024="已超过某场次之过关注单限额!!";
top.message025="下注金额不可大於信用额度。";
top.message026="请选择下注队伍!!";
top.message027="单式投注请至单式下注页面下注!!";
top.message028="仅接受";
top.message029="串投注!!";
top.message030="确定要进行交易吗？";
top.message031="请输入要搜寻的文字";
top.message032="找不到相符项目";
top.message033="你的瀏览器不支援";
top.message034 = "下注金额不可大于单注限额!!";
top.message035 = "下注金额不可小于最低下注金额!!";
top.message036 = "每注最高派为";





top.page="页";
top.refreshTime="刷新";
top.showmonth="月";
top.showday="日";

top.str_RB ="滚球";
top.Half1st="上半滚球";
top.Half2nd="下半滚球";

top.mem_logut="您的帐号已登出";
top.retime1H="上";
top.retime2H="下";

top.str_otb_close="赛事已关闭。";
//奥运用
top.no_oly="您选择的项目暂时没有赛事。请查看冠军玩法。";

//会员详细 conf
top.conf_R="让球,大小,单双";
top.conf_RE="滚球让球,滚球大小,滚球单双";
top.conf_RE_BK="滚球让球,滚球大小";
top.conf_M="独赢,滚球独赢";
top.conf_DT="其他";
top.conf_RDT="滚球其他";
top.conf_FS="冠军";
top.str_more="更多玩法";
//new type
top.str_all_bets="全部玩法";
top.str_TV_RB = "视频转播可使用";
top.str_TV_FT = "视频转播将在滚球时提供";
top.addtoMyMarket="加到\"我的盘口\"";

top.str_result = new Object();
top.str_result["No"] = "无";
top.str_result["Y"] = "是";
top.str_result["N"] = "否";
top.str_result["F&#8203;&#8203;G_S"] = "射门";
top.str_result["F&#8203;&#8203;G_H"] = "头球";
top.str_result["F&#8203;&#8203;G_N"] = "无进球";
top.str_result["F&#8203;&#8203;G_P"] = "点球";
top.str_result["F&#8203;&#8203;G_F"] = "任意球";
top.str_result["F&#8203;&#8203;G_O"] = "乌龙球";

top.str_result["T3G_1"] = "第26分钟或之前";
top.str_result["T3G_2"] = "第27分钟或之后";
top.str_result["T3G_N"] = "无进球";

top.str_result["T1G_N"] = "无进球";
top.str_result["T1G_1"] = "0 - 14:59";
top.str_result["T1G_2"] = "15 - 29:59";
top.str_result["T1G_3"] = "30 – 半场";
top.str_result["T1G_4"] = "45 – 59:59";
top.str_result["T1G_5"] = "60 – 74:59";
top.str_result["T1G_6"] = "75 – 全场";

top.statu = new Array();
top.statu["HT"] = "Half Time";
top.statu["1H"] = "1st Half";
top.statu["2H"] = "2nd Half";;
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



<title>body_football_r</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/cl/tpl/commonFile/style/member/mem_body_olympics.css?=29" type="text/css">


</head>
<body id="MFU" class="bodyset FUR" onload="onLoadset();">
<div id="LoadLayer" style="display: none;">loading...............................................................................</div>
<div id="showtableData" style="display:none;">
  <xmp>
   
          <table id="game_table"  cellspacing="0" cellpadding="0" class="game">
            <tr>
              <th nowrap class="time">时间</th>
              <th nowrap class="team">赛事</th>
              <th nowrap class="h_1x2">独赢</th>
              <th nowrap class="h_r">全场 - 让球</th>
              <th nowrap  class="h_ou">全场 - 大小</th>
              <th nowrap class="h_oe">单双</th>
              <th nowrap class="h_1x2">独赢</th>
              <th nowrap class="h_r">半场 - 让球</th>
              <th nowrap class="h_ou">半场 - 大小</th>
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
    <td colspan="9" class="b_hline">
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
  <tr id="TR_*ID_STR*"  *CLASS*>
  <!--<tr id="TR_*ID_STR*" *TR_EVENT* *CLASS*>-->
    <td rowspan="3" class="b_cen">*DATETIME*</td>
    <td rowspan="2" class="team_name none" id="TR_*ID_STR*_1" *TR_EVENT*>*TEAM_H*<br>
      *TEAM_C*</td>
    <td class="b_cen">*RATIO_MH*</td>
    <td class="b_rig"><span class="con">*CON_RH*</span> <span class="ratio">*RATIO_RH*</span></td>
    <td class="b_rig"><span class="con">*CON_OUH*</span> <span class="ratio">*RATIO_OUH*</span></td>
    <td class="b_cen">*RATIO_EOO*</td>
    <td class="b_1st">*RATIO_HMH*</td>
    <td class="b_1stR"><span class="con">*CON_HRH*</span> <span class="ratio">*RATIO_HRH*</span></td>
    <td class="b_1stR"><span class="con">*CON_HOUH*</span> <span class="ratio">*RATIO_HOUH*</span></td>
  </tr>
  <tr id="TR1_*ID_STR*" *CLASS*>
  <!--<tr id="TR1_*ID_STR*" *TR_EVENT* *CLASS*>-->
    <td class="b_cen">*RATIO_MC*</td>
    <td class="b_rig"><span class="con">*CON_RC*</span> <span class="ratio">*RATIO_RC*</span></td>
    <td class="b_rig"><span class="con">*CON_OUC*</span> <span class="ratio">*RATIO_OUC*</span></td>
    <td class="b_cen">*RATIO_EOE*</td>
    <td class="b_1st">*RATIO_HMC*</td>
    <td class="b_1stR"><span class="con">*CON_HRC*</span> <span class="ratio">*RATIO_HRC*</span></td>
    <td class="b_1stR"><span class="con">*CON_HOUC*</span> <span class="ratio">*RATIO_HOUC*</span></td>
  </tr>
  <tr id="TR2_*ID_STR*"  *CLASS*>
  <!--<tr id="TR2_*ID_STR*" *TR_EVENT* *CLASS*>-->
    <td class="drawn_td" id="TR_*ID_STR*_1" *TR_EVENT*>*MYLOVE*<!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td>
    <td class="b_cen">*RATIO_MN*</td>
    <td colspan="3" valign="top" class="b_cen"><span class="more_txt">*MORE*</span></td>
    <td class="b_1st" >*RATIO_HMN*</td>
    <td colspan="3" valign="top" class="b_1st">&nbsp;</td>
  </tr>
</xmp>
</div>
<!--右方刷新钮--><div id="refresh_right" style="position: absolute; top: 139px; left: 820px;" class="refresh_M_btn" onclick="this.className='refresh_M_on';javascript:refreshReload('',true)"><span>刷新</span></div>
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
				  <!--tr>
					<td id="ad">
						<span id="real_msg"></span>
					<p><a href="javascript://" onClick="javascript: window.open('../scroll_history.php?uid=18e75f07m12280781l111337718&langx=zh-cn','','menubar=no,status=yes,scrollbars=yes,top=150,left=200,toolbar=no,width=510,height=500')">历史讯息</a></p>
					</td>
					</tr-->
					<tbody>
						<tr>
							<td class="top">
								<h1><em>早餐足球</em></h1>
							</td>
						</tr>
						<tr>
							<td class="mem">
								<h2>
									<table width="100%" border="0" cellpadding="0" cellspacing="0" id="fav_bar">
										<tbody>						
											<tr>
												<td id="page_no">
													<div id="euro_btn" class="euro_btn" onclick="Euro();" onmouseover="Eurover(this);" onmouseout="Eurout(this);" style="display: none;"><!--奥运--></div>
													<div id="euro_up" class="euro_up" onclick="Euro();" onmouseover="Eurover(this);" onmouseout="Eurout(this);" style="display: none;"><!--所有赛事--></div>
													<span id="pg_txt">1 / 15 页&nbsp;&nbsp; <select onchange="chg_pg(this.options[this.selectedIndex].value)"><option value="0" selected="">1</option><option value="1">2</option><option value="2">3</option><option value="3">4</option><option value="4">5</option><option value="5">6</option><option value="6">7</option><option value="7">8</option><option value="8">9</option><option value="9">10</option><option value="10">11</option><option value="11">12</option><option value="12">13</option><option value="13">14</option><option value="14">15</option></select></span>&nbsp;&nbsp; 时间:	<span id="show_date_opt"><select id="g_date" name="g_date" onchange="chg_gdate()"><option value="ALL">全部</option><option value="1">特早</option><option value="2015-01-23">1月23日</option><option value="2015-01-24">1月24日</option><option value="2015-01-25">1月25日</option><option value="2015-01-26">1月26日</option><option value="2015-01-27">1月27日</option><option value="2015-01-28">1月28日</option><option value="2015-01-29">1月29日</option><option value="2015-01-30">1月30日</option><option value="2015-01-31">1月31日</option><option value="2015-02-01">2月1日</option><option value="2015-02-02">2月2日</option></select></span>
												</td>
												<td id="tool_td">
													<table border="0" cellspacing="0" cellpadding="0" class="tool_box">
														<tbody>
															<tr>
																<td id="fav_btn">
																	<div id="fav_num" title="清空" onclick="chkDelAllShowLoveI();" style="display: none;"><!--我的最爱场数--><span id="live_num"></span></div>
																	<div id="showNull" title="无资料" class="fav_null" style="display: block;"></div>
																	<div id="showAll" title="所有赛事" onclick="showAllGame('FT');" style="display: none;" class="fav_on"></div>
																	<div id="showMy" title="我的最爱" onclick="showMyLove('FT');" class="fav_out" style="display: none;"></div>
																</td>
																<td class="refresh_btn" id="refresh_btn" onclick="this.className='refresh_on';"><!--秒数更新--><div onclick="javascript:refreshReload('',true)"><font id="refreshTime">刷新</font></div></td>
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
              <th nowrap="" class="time">时间</th>
              <th nowrap="" class="team">赛事</th>
              <th nowrap="" class="h_1x2">独赢</th>
              <th nowrap="" class="h_r">全场 - 让球</th>
              <th nowrap="" class="h_ou">全场 - 大小</th>
              <th nowrap="" class="h_oe">单双</th>
              <th nowrap="" class="h_1x2">独赢</th>
              <th nowrap="" class="h_r">半场 - 让球</th>
              <th nowrap="" class="h_ou">半场 - 大小</th>
            </tr>
            
	
  <!--SHOW LEGUAGE START--><tr style="display: ;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('亚洲杯2015(在澳洲)')"><span id="亚洲杯2015(在澳洲)" name="亚洲杯2015(在澳洲)" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('亚洲杯2015(在澳洲)')" class="leg_bar">亚洲杯2015(在澳洲)</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350280" style="display: ;"><!--<tr id="TR_01-2350280" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>02:30<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350280_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 伊朗 <br>
       伊拉克 </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118462561&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50280&amp;langx=zh-cn');" title=" 伊朗 "><font true="">1.75</font></a></td><td class="b_rig"><span class="con">0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462561&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50280&amp;strong=H&amp;langx=zh-cn');" title=" 伊朗 "><font true="">1.01</font></a></span></td><td class="b_rig"><span class="con">大1.5 / 2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462561&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50280&amp;langx=zh-cn');" title="大"><font true="">1.05</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462561&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">2.06</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118462571&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50280&amp;langx=zh-cn');" title=" 伊朗 "><font true="">2.61</font></a></td><td class="b_1stR"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118462571&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50280&amp;strong=H&amp;langx=zh-cn');" title=" 伊朗 "><font true="">1.01</font></a></span></td><td class="b_1stR"><span class="con">大0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118462571&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50280&amp;langx=zh-cn');" title="大"><font true="">1.21</font></a></span></td></tr><tr id="TR1_01-2350280" style="display: ;"><!--<tr id="TR1_01-2350280" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118462561&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50279&amp;langx=zh-cn');" title=" 伊拉克 "><font true="">4.60</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462561&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50279&amp;strong=H&amp;langx=zh-cn');" title=" 伊拉克 "><font true="">0.89</font></a></span></td><td class="b_rig"><span class="con">小1.5 / 2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462561&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50279&amp;langx=zh-cn');" title="小"><font true="">0.83</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462561&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.84</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118462571&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50279&amp;langx=zh-cn');" title=" 伊拉克 "><font true="">5.00</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118462571&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50279&amp;strong=H&amp;langx=zh-cn');" title=" 伊拉克 "><font true="">0.89</font></a></span></td><td class="b_1stR"><span class="con">小0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118462571&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50279&amp;langx=zh-cn');" title="小"><font true="">0.70</font></a></span></td></tr><tr id="TR2_01-2350280" style="display: ;"><!--<tr id="TR2_01-2350280" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350280_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350280"><div id="01-2350280" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50280','01-23<br>02:30a<br><font color=red>滚球</font>','亚洲杯2015(在澳洲)',' 伊朗 ',' 伊拉克 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118462561&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50279&amp;langx=zh-cn');" title="和"><font true="">3.18</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118462571&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50279&amp;langx=zh-cn');" title="和"><font true="">1.85</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('亚洲杯2015(在澳洲)')"><span id="亚洲杯2015(在澳洲)" name="亚洲杯2015(在澳洲)" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('亚洲杯2015(在澳洲)')" class="leg_bar">亚洲杯2015(在澳洲)</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350282" style="display: ;"><!--<tr id="TR_01-2350282" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>02:30<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350282_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 伊朗 <br>
       伊拉克 </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con">0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462581&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50282&amp;strong=H&amp;langx=zh-cn');" title=" 伊朗 "><font true="">0.74</font></a></span></td><td class="b_rig"><span class="con">大1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462581&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50282&amp;langx=zh-cn');" title="大"><font true="">0.81</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462581&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con">0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118462591&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50282&amp;strong=H&amp;langx=zh-cn');" title=" 伊朗 "><font true="">1.63</font></a></span></td><td class="b_1stR"><span class="con">大0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118462591&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50282&amp;langx=zh-cn');" title="大"><font true="">0.79</font></a></span></td></tr><tr id="TR1_01-2350282" style="display: ;"><!--<tr id="TR1_01-2350282" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462581&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50281&amp;strong=H&amp;langx=zh-cn');" title=" 伊拉克 "><font true="">1.19</font></a></span></td><td class="b_rig"><span class="con">小1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462581&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50281&amp;langx=zh-cn');" title="小"><font true="">1.07</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462581&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118462591&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50281&amp;strong=H&amp;langx=zh-cn');" title=" 伊拉克 "><font true="">0.51</font></a></span></td><td class="b_1stR"><span class="con">小0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118462591&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50281&amp;langx=zh-cn');" title="小"><font true="">1.09</font></a></span></td></tr><tr id="TR2_01-2350282" style="display: ;"><!--<tr id="TR2_01-2350282" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350282_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350282"><div id="01-2350282" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50282','01-23<br>02:30a<br><font color=red>滚球</font>','亚洲杯2015(在澳洲)',' 伊朗 ',' 伊拉克 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('亚洲杯2015(在澳洲)')"><span id="亚洲杯2015(在澳洲)" name="亚洲杯2015(在澳洲)" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('亚洲杯2015(在澳洲)')" class="leg_bar">亚洲杯2015(在澳洲)</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350284" style="display: ;"><!--<tr id="TR_01-2350284" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>02:30<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350284_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 伊朗 <br>
       伊拉克 </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con">1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462601&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50284&amp;strong=H&amp;langx=zh-cn');" title=" 伊朗 "><font true="">1.51</font></a></span></td><td class="b_rig"><span class="con">大2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462601&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50284&amp;langx=zh-cn');" title="大"><font true="">1.49</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462601&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50284&amp;strong=&amp;langx=zh-cn');" title=" 伊朗 "><font undefined=""></font></a></span></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50284&amp;langx=zh-cn');" title="大"><font undefined=""></font></a></span></td></tr><tr id="TR1_01-2350284" style="display: ;"><!--<tr id="TR1_01-2350284" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462601&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50283&amp;strong=H&amp;langx=zh-cn');" title=" 伊拉克 "><font true="">0.56</font></a></span></td><td class="b_rig"><span class="con">小2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462601&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50283&amp;langx=zh-cn');" title="小"><font true="">0.55</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462601&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50283&amp;strong=&amp;langx=zh-cn');" title=" 伊拉克 "><font undefined=""></font></a></span></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50283&amp;langx=zh-cn');" title="小"><font undefined=""></font></a></span></td></tr><tr id="TR2_01-2350284" style="display: ;"><!--<tr id="TR2_01-2350284" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350284_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350284"><div id="01-2350284" class="fov_icon_out" style="cursor: pointer; display: block;" title="我的最愛" onclick="addShowLoveI('50284','01-23<br>02:30a<br><font color=red>滚球</font>','亚洲杯2015(在澳洲)',' 伊朗 ',' 伊拉克 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('亚洲杯2015(在澳洲)')"><span id="亚洲杯2015(在澳洲)" name="亚洲杯2015(在澳洲)" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('亚洲杯2015(在澳洲)')" class="leg_bar">亚洲杯2015(在澳洲)</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350286" style="display: ;"><!--<tr id="TR_01-2350286" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>02:30</td><td rowspan="2" class="team_name none" id="TR_01-2350286_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 伊朗 <br>
       伊拉克 </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462621&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50286&amp;strong=H&amp;langx=zh-cn');" title=" 伊朗 "><font true="">0.47</font></a></span></td><td class="b_rig"><span class="con">大1 / 1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462621&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50286&amp;langx=zh-cn');" title="大"><font true="">0.48</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462621&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50286&amp;strong=&amp;langx=zh-cn');" title=" 伊朗 "><font undefined=""></font></a></span></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50286&amp;langx=zh-cn');" title="大"><font undefined=""></font></a></span></td></tr><tr id="TR1_01-2350286" style="display: ;"><!--<tr id="TR1_01-2350286" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462621&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50285&amp;strong=H&amp;langx=zh-cn');" title=" 伊拉克 "><font true="">1.75</font></a></span></td><td class="b_rig"><span class="con">小1 / 1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462621&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50285&amp;langx=zh-cn');" title="小"><font true="">1.66</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462621&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50285&amp;strong=&amp;langx=zh-cn');" title=" 伊拉克 "><font undefined=""></font></a></span></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50285&amp;langx=zh-cn');" title="小"><font undefined=""></font></a></span></td></tr><tr id="TR2_01-2350286" style="display: ;"><!--<tr id="TR2_01-2350286" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350286_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350286"><div id="01-2350286" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50286','01-23<br>02:30a','亚洲杯2015(在澳洲)',' 伊朗 ',' 伊拉克 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: ;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('亚洲杯2015(在澳洲)特别投注')"><span id="亚洲杯2015(在澳洲)特别投注" name="亚洲杯2015(在澳洲)特别投注" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('亚洲杯2015(在澳洲)特别投注')" class="leg_bar">亚洲杯2015(在澳洲)特别投注</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350296" style="display: ;"><!--<tr id="TR_01-2350296" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>02:30</td><td rowspan="2" class="team_name none" id="TR_01-2350296_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 主场 -星期五-2场赛事  <br>
       客场 -星期五-2场赛事  </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118462721&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50296&amp;langx=zh-cn');" title=" 主场 -星期五-2场赛事  "><font true="">1.10</font></a></td><td class="b_rig"><span class="con">2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462721&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50296&amp;strong=H&amp;langx=zh-cn');" title=" 主场 -星期五-2场赛事  "><font true="">1.05</font></a></span></td><td class="b_rig"><span class="con">大4.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462721&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50296&amp;langx=zh-cn');" title="大"><font true="">1.17</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462721&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.95</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118462731&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50296&amp;langx=zh-cn');" title=" 主场 -星期五-2场赛事  "><font true="">1.45</font></a></td><td class="b_1stR"><span class="con">1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118462731&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50296&amp;strong=H&amp;langx=zh-cn');" title=" 主场 -星期五-2场赛事  "><font true="">1.03</font></a></span></td><td class="b_1stR"><span class="con">大1.5 / 2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118462731&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50296&amp;langx=zh-cn');" title="大"><font true="">0.91</font></a></span></td></tr><tr id="TR1_01-2350296" style="display: ;"><!--<tr id="TR1_01-2350296" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118462721&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50295&amp;langx=zh-cn');" title=" 客场 -星期五-2场赛事  "><font true="">9.00</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462721&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50295&amp;strong=H&amp;langx=zh-cn');" title=" 客场 -星期五-2场赛事  "><font true="">0.85</font></a></span></td><td class="b_rig"><span class="con">小4.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462721&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50295&amp;langx=zh-cn');" title="小"><font true="">0.73</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462721&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.95</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118462731&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50295&amp;langx=zh-cn');" title=" 客场 -星期五-2场赛事  "><font true="">6.00</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118462731&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50295&amp;strong=H&amp;langx=zh-cn');" title=" 客场 -星期五-2场赛事  "><font true="">0.85</font></a></span></td><td class="b_1stR"><span class="con">小1.5 / 2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118462731&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50295&amp;langx=zh-cn');" title="小"><font true="">0.97</font></a></span></td></tr><tr id="TR2_01-2350296" style="display: ;"><!--<tr id="TR2_01-2350296" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350296_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350296"><div id="01-2350296" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50296','01-23<br>02:30a','亚洲杯2015(在澳洲)特别投注',' 主场 -星期五-2场赛事  ',' 客场 -星期五-2场赛事  '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118462721&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50295&amp;langx=zh-cn');" title="和"><font true="">7.50</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118462731&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50295&amp;langx=zh-cn');" title="和"><font true="">3.40</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('亚洲杯2015(在澳洲)特别投注')"><span id="亚洲杯2015(在澳洲)特别投注" name="亚洲杯2015(在澳洲)特别投注" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('亚洲杯2015(在澳洲)特别投注')" class="leg_bar">亚洲杯2015(在澳洲)特别投注</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350298" style="display: ;"><!--<tr id="TR_01-2350298" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>02:30</td><td rowspan="2" class="team_name none" id="TR_01-2350298_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 伊朗 -会晋级  <br>
       伊拉克 -会晋级  </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con">0</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462741&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50298&amp;strong=H&amp;langx=zh-cn');" title=" 伊朗 -会晋级  "><font true="">0.28</font></a></span></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462741&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50298&amp;langx=zh-cn');" title="大"><font undefined=""></font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462741&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50298&amp;strong=&amp;langx=zh-cn');" title=" 伊朗 -会晋级  "><font undefined=""></font></a></span></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50298&amp;langx=zh-cn');" title="大"><font undefined=""></font></a></span></td></tr><tr id="TR1_01-2350298" style="display: ;"><!--<tr id="TR1_01-2350298" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462741&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50297&amp;strong=H&amp;langx=zh-cn');" title=" 伊拉克 -会晋级  "><font true="">2.63</font></a></span></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462741&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50297&amp;langx=zh-cn');" title="小"><font undefined=""></font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462741&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50297&amp;strong=&amp;langx=zh-cn');" title=" 伊拉克 -会晋级  "><font undefined=""></font></a></span></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50297&amp;langx=zh-cn');" title="小"><font undefined=""></font></a></span></td></tr><tr id="TR2_01-2350298" style="display: ;"><!--<tr id="TR2_01-2350298" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350298_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350298"><div id="01-2350298" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50298','01-23<br>02:30a','亚洲杯2015(在澳洲)特别投注',' 伊朗 -会晋级  ',' 伊拉克 -会晋级  '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('亚洲杯2015(在澳洲)特别投注')"><span id="亚洲杯2015(在澳洲)特别投注" name="亚洲杯2015(在澳洲)特别投注" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('亚洲杯2015(在澳洲)特别投注')" class="leg_bar">亚洲杯2015(在澳洲)特别投注</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350300" style="display: ;"><!--<tr id="TR_01-2350300" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>02:30<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350300_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 伊朗 -角球数  <br>
       伊拉克 -角球数  </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462761&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50300&amp;strong=H&amp;langx=zh-cn');" title=" 伊朗 -角球数  "><font undefined=""></font></a></span></td><td class="b_rig"><span class="con">大9.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462761&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50300&amp;langx=zh-cn');" title="大"><font true="">1.01</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462761&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.93</font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118462771&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50300&amp;strong=H&amp;langx=zh-cn');" title=" 伊朗 -角球数  "><font undefined=""></font></a></span></td><td class="b_1stR"><span class="con">大4 / 4.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118462771&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50300&amp;langx=zh-cn');" title="大"><font true="">0.89</font></a></span></td></tr><tr id="TR1_01-2350300" style="display: ;"><!--<tr id="TR1_01-2350300" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462761&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50299&amp;strong=H&amp;langx=zh-cn');" title=" 伊拉克 -角球数  "><font undefined=""></font></a></span></td><td class="b_rig"><span class="con">小9.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462761&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50299&amp;langx=zh-cn');" title="小"><font true="">0.81</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462761&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.93</font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118462771&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50299&amp;strong=H&amp;langx=zh-cn');" title=" 伊拉克 -角球数  "><font undefined=""></font></a></span></td><td class="b_1stR"><span class="con">小4 / 4.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118462771&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50299&amp;langx=zh-cn');" title="小"><font true="">0.93</font></a></span></td></tr><tr id="TR2_01-2350300" style="display: ;"><!--<tr id="TR2_01-2350300" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350300_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350300"><div id="01-2350300" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50300','01-23<br>02:30a<br><font color=red>滚球</font>','亚洲杯2015(在澳洲)特别投注',' 伊朗 -角球数  ',' 伊拉克 -角球数  '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: ;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('亚洲杯2015(在澳洲)')"><span id="亚洲杯2015(在澳洲)" name="亚洲杯2015(在澳洲)" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('亚洲杯2015(在澳洲)')" class="leg_bar">亚洲杯2015(在澳洲)</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350288" style="display: ;"><!--<tr id="TR_01-2350288" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>05:30<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350288_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 日本 <br>
       阿联酋 </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118462641&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50288&amp;langx=zh-cn');" title=" 日本 "><font true="">1.35</font></a></td><td class="b_rig"><span class="con">1 / 1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462641&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50288&amp;strong=H&amp;langx=zh-cn');" title=" 日本 "><font true="">0.88</font></a></span></td><td class="b_rig"><span class="con">大2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462641&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50288&amp;langx=zh-cn');" title="大"><font true="">0.83</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462641&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">2.00</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118462651&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50288&amp;langx=zh-cn');" title=" 日本 "><font true="">1.91</font></a></td><td class="b_1stR"><span class="con">0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118462651&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50288&amp;strong=H&amp;langx=zh-cn');" title=" 日本 "><font true="">0.89</font></a></span></td><td class="b_1stR"><span class="con">大1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118462651&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50288&amp;langx=zh-cn');" title="大"><font true="">1.08</font></a></span></td></tr><tr id="TR1_01-2350288" style="display: ;"><!--<tr id="TR1_01-2350288" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118462641&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50287&amp;langx=zh-cn');" title=" 阿联酋 "><font true="">7.20</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462641&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50287&amp;strong=H&amp;langx=zh-cn');" title=" 阿联酋 "><font true="">1.02</font></a></span></td><td class="b_rig"><span class="con">小2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462641&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50287&amp;langx=zh-cn');" title="小"><font true="">1.05</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462641&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.90</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118462651&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50287&amp;langx=zh-cn');" title=" 阿联酋 "><font true="">5.85</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118462651&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50287&amp;strong=H&amp;langx=zh-cn');" title=" 阿联酋 "><font true="">1.01</font></a></span></td><td class="b_1stR"><span class="con">小1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118462651&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50287&amp;langx=zh-cn');" title="小"><font true="">0.80</font></a></span></td></tr><tr id="TR2_01-2350288" style="display: ;"><!--<tr id="TR2_01-2350288" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350288_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350288"><div id="01-2350288" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50288','01-23<br>05:30a<br><font color=red>滚球</font>','亚洲杯2015(在澳洲)',' 日本 ',' 阿联酋 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118462641&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50287&amp;langx=zh-cn');" title="和"><font true="">4.50</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118462651&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50287&amp;langx=zh-cn');" title="和"><font true="">2.35</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('亚洲杯2015(在澳洲)')"><span id="亚洲杯2015(在澳洲)" name="亚洲杯2015(在澳洲)" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('亚洲杯2015(在澳洲)')" class="leg_bar">亚洲杯2015(在澳洲)</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350290" style="display: ;"><!--<tr id="TR_01-2350290" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>05:30<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350290_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 日本 <br>
       阿联酋 </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con">1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462661&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50290&amp;strong=H&amp;langx=zh-cn');" title=" 日本 "><font true="">1.14</font></a></span></td><td class="b_rig"><span class="con">大2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462661&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50290&amp;langx=zh-cn');" title="大"><font true="">1.09</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462661&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con">0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118462671&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50290&amp;strong=H&amp;langx=zh-cn');" title=" 日本 "><font true="">1.31</font></a></span></td><td class="b_1stR"><span class="con">大0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118462671&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50290&amp;langx=zh-cn');" title="大"><font true="">0.64</font></a></span></td></tr><tr id="TR1_01-2350290" style="display: ;"><!--<tr id="TR1_01-2350290" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462661&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50289&amp;strong=H&amp;langx=zh-cn');" title=" 阿联酋 "><font true="">0.77</font></a></span></td><td class="b_rig"><span class="con">小2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462661&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50289&amp;langx=zh-cn');" title="小"><font true="">0.79</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462661&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118462671&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50289&amp;strong=H&amp;langx=zh-cn');" title=" 阿联酋 "><font true="">0.66</font></a></span></td><td class="b_1stR"><span class="con">小0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118462671&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50289&amp;langx=zh-cn');" title="小"><font true="">1.31</font></a></span></td></tr><tr id="TR2_01-2350290" style="display: ;"><!--<tr id="TR2_01-2350290" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350290_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350290"><div id="01-2350290" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50290','01-23<br>05:30a<br><font color=red>滚球</font>','亚洲杯2015(在澳洲)',' 日本 ',' 阿联酋 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('亚洲杯2015(在澳洲)')"><span id="亚洲杯2015(在澳洲)" name="亚洲杯2015(在澳洲)" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('亚洲杯2015(在澳洲)')" class="leg_bar">亚洲杯2015(在澳洲)</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350292" style="display: ;"><!--<tr id="TR_01-2350292" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>05:30<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350292_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 日本 <br>
       阿联酋 </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con">1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462681&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50292&amp;strong=H&amp;langx=zh-cn');" title=" 日本 "><font true="">0.60</font></a></span></td><td class="b_rig"><span class="con">大2.5 / 3</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462681&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50292&amp;langx=zh-cn');" title="大"><font true="">1.36</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462681&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50292&amp;strong=&amp;langx=zh-cn');" title=" 日本 "><font undefined=""></font></a></span></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50292&amp;langx=zh-cn');" title="大"><font undefined=""></font></a></span></td></tr><tr id="TR1_01-2350292" style="display: ;"><!--<tr id="TR1_01-2350292" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462681&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50291&amp;strong=H&amp;langx=zh-cn');" title=" 阿联酋 "><font true="">1.42</font></a></span></td><td class="b_rig"><span class="con">小2.5 / 3</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462681&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50291&amp;langx=zh-cn');" title="小"><font true="">0.61</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462681&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50291&amp;strong=&amp;langx=zh-cn');" title=" 阿联酋 "><font undefined=""></font></a></span></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50291&amp;langx=zh-cn');" title="小"><font undefined=""></font></a></span></td></tr><tr id="TR2_01-2350292" style="display: ;"><!--<tr id="TR2_01-2350292" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350292_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350292"><div id="01-2350292" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50292','01-23<br>05:30a<br><font color=red>滚球</font>','亚洲杯2015(在澳洲)',' 日本 ',' 阿联酋 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('亚洲杯2015(在澳洲)')"><span id="亚洲杯2015(在澳洲)" name="亚洲杯2015(在澳洲)" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('亚洲杯2015(在澳洲)')" class="leg_bar">亚洲杯2015(在澳洲)</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350294" style="display: ;"><!--<tr id="TR_01-2350294" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>05:30</td><td rowspan="2" class="team_name none" id="TR_01-2350294_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 日本 <br>
       阿联酋 </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con">1.5 / 2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462701&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50294&amp;strong=H&amp;langx=zh-cn');" title=" 日本 "><font true="">1.47</font></a></span></td><td class="b_rig"><span class="con">大2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462701&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50294&amp;langx=zh-cn');" title="大"><font true="">0.55</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462701&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50294&amp;strong=&amp;langx=zh-cn');" title=" 日本 "><font undefined=""></font></a></span></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50294&amp;langx=zh-cn');" title="大"><font undefined=""></font></a></span></td></tr><tr id="TR1_01-2350294" style="display: ;"><!--<tr id="TR1_01-2350294" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462701&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50293&amp;strong=H&amp;langx=zh-cn');" title=" 阿联酋 "><font true="">0.58</font></a></span></td><td class="b_rig"><span class="con">小2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462701&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50293&amp;langx=zh-cn');" title="小"><font true="">1.49</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462701&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50293&amp;strong=&amp;langx=zh-cn');" title=" 阿联酋 "><font undefined=""></font></a></span></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50293&amp;langx=zh-cn');" title="小"><font undefined=""></font></a></span></td></tr><tr id="TR2_01-2350294" style="display: ;"><!--<tr id="TR2_01-2350294" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350294_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350294"><div id="01-2350294" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50294','01-23<br>05:30a','亚洲杯2015(在澳洲)',' 日本 ',' 阿联酋 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: ;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('亚洲杯2015(在澳洲)特别投注')"><span id="亚洲杯2015(在澳洲)特别投注" name="亚洲杯2015(在澳洲)特别投注" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('亚洲杯2015(在澳洲)特别投注')" class="leg_bar">亚洲杯2015(在澳洲)特别投注</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350302" style="display: ;"><!--<tr id="TR_01-2350302" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>05:30</td><td rowspan="2" class="team_name none" id="TR_01-2350302_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 日本 -会晋级  <br>
       阿联酋 -会晋级  </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con">0</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462781&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50302&amp;strong=H&amp;langx=zh-cn');" title=" 日本 -会晋级  "><font true="">0.08</font></a></span></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462781&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50302&amp;langx=zh-cn');" title="大"><font undefined=""></font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462781&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50302&amp;strong=&amp;langx=zh-cn');" title=" 日本 -会晋级  "><font undefined=""></font></a></span></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50302&amp;langx=zh-cn');" title="大"><font undefined=""></font></a></span></td></tr><tr id="TR1_01-2350302" style="display: ;"><!--<tr id="TR1_01-2350302" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462781&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50301&amp;strong=H&amp;langx=zh-cn');" title=" 阿联酋 -会晋级  "><font true="">5.55</font></a></span></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462781&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50301&amp;langx=zh-cn');" title="小"><font undefined=""></font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462781&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50301&amp;strong=&amp;langx=zh-cn');" title=" 阿联酋 -会晋级  "><font undefined=""></font></a></span></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50301&amp;langx=zh-cn');" title="小"><font undefined=""></font></a></span></td></tr><tr id="TR2_01-2350302" style="display: ;"><!--<tr id="TR2_01-2350302" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350302_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350302"><div id="01-2350302" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50302','01-23<br>05:30a','亚洲杯2015(在澳洲)特别投注',' 日本 -会晋级  ',' 阿联酋 -会晋级  '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('亚洲杯2015(在澳洲)特别投注')"><span id="亚洲杯2015(在澳洲)特别投注" name="亚洲杯2015(在澳洲)特别投注" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('亚洲杯2015(在澳洲)特别投注')" class="leg_bar">亚洲杯2015(在澳洲)特别投注</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350304" style="display: ;"><!--<tr id="TR_01-2350304" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>05:30</td><td rowspan="2" class="team_name none" id="TR_01-2350304_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 日本 -角球数  <br>
       阿联酋 -角球数  </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462801&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50304&amp;strong=H&amp;langx=zh-cn');" title=" 日本 -角球数  "><font undefined=""></font></a></span></td><td class="b_rig"><span class="con">大10</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462801&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50304&amp;langx=zh-cn');" title="大"><font true="">1.12</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462801&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.93</font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118462811&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50304&amp;strong=H&amp;langx=zh-cn');" title=" 日本 -角球数  "><font undefined=""></font></a></span></td><td class="b_1stR"><span class="con">大4.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118462811&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50304&amp;langx=zh-cn');" title="大"><font true="">0.96</font></a></span></td></tr><tr id="TR1_01-2350304" style="display: ;"><!--<tr id="TR1_01-2350304" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118462801&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50303&amp;strong=H&amp;langx=zh-cn');" title=" 阿联酋 -角球数  "><font undefined=""></font></a></span></td><td class="b_rig"><span class="con">小10</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118462801&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50303&amp;langx=zh-cn');" title="小"><font true="">0.71</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118462801&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.93</font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118462811&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50303&amp;strong=H&amp;langx=zh-cn');" title=" 阿联酋 -角球数  "><font undefined=""></font></a></span></td><td class="b_1stR"><span class="con">小4.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118462811&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50303&amp;langx=zh-cn');" title="小"><font true="">0.86</font></a></span></td></tr><tr id="TR2_01-2350304" style="display: ;"><!--<tr id="TR2_01-2350304" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350304_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350304"><div id="01-2350304" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50304','01-23<br>05:30a','亚洲杯2015(在澳洲)特别投注',' 日本 -角球数  ',' 阿联酋 -角球数  '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: ;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('非洲国家杯2015(在赤道几内亚)')"><span id="非洲国家杯2015(在赤道几内亚)" name="非洲国家杯2015(在赤道几内亚)" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('非洲国家杯2015(在赤道几内亚)')" class="leg_bar">非洲国家杯2015(在赤道几内亚)</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350118" style="display: ;"><!--<tr id="TR_01-2350118" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>12:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350118_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 阿尔及利亚 <br>
       加纳 </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118448221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50118&amp;langx=zh-cn');" title=" 阿尔及利亚 "><font true="">2.25</font></a></td><td class="b_rig"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118448221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50118&amp;strong=H&amp;langx=zh-cn');" title=" 阿尔及利亚 "><font true="">0.97</font></a></span></td><td class="b_rig"><span class="con">大2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118448221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50118&amp;langx=zh-cn');" title="大"><font true="">1.09</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118448221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">2.05</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118448231&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50118&amp;langx=zh-cn');" title=" 阿尔及利亚 "><font true="">2.80</font></a></td><td class="b_1stR"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118448231&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50118&amp;strong=H&amp;langx=zh-cn');" title=" 阿尔及利亚 "><font true="">1.40</font></a></span></td><td class="b_1stR"><span class="con">大0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118448231&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50118&amp;langx=zh-cn');" title="大"><font true="">0.80</font></a></span></td></tr><tr id="TR1_01-2350118" style="display: ;"><!--<tr id="TR1_01-2350118" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118448221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50117&amp;langx=zh-cn');" title=" 加纳 "><font true="">3.10</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118448221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50117&amp;strong=H&amp;langx=zh-cn');" title=" 加纳 "><font true="">0.93</font></a></span></td><td class="b_rig"><span class="con">小2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118448221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50117&amp;langx=zh-cn');" title="小"><font true="">0.79</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118448221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.85</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118448231&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50117&amp;langx=zh-cn');" title=" 加纳 "><font true="">4.20</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118448231&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50117&amp;strong=H&amp;langx=zh-cn');" title=" 加纳 "><font true="">0.61</font></a></span></td><td class="b_1stR"><span class="con">小0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118448231&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50117&amp;langx=zh-cn');" title="小"><font true="">1.08</font></a></span></td></tr><tr id="TR2_01-2350118" style="display: ;"><!--<tr id="TR2_01-2350118" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350118_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350118"><div id="01-2350118" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50118','01-23<br>12:00p<br><font color=red>滚球</font>','非洲国家杯2015(在赤道几内亚)',' 阿尔及利亚 ',' 加纳 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118448221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50117&amp;langx=zh-cn');" title="和"><font true="">3.00</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118448231&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50117&amp;langx=zh-cn');" title="和"><font true="">1.90</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('非洲国家杯2015(在赤道几内亚)')"><span id="非洲国家杯2015(在赤道几内亚)" name="非洲国家杯2015(在赤道几内亚)" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('非洲国家杯2015(在赤道几内亚)')" class="leg_bar">非洲国家杯2015(在赤道几内亚)</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350120" style="display: ;"><!--<tr id="TR_01-2350120" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>12:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350120_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 阿尔及利亚 <br>
       加纳 </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con">0</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118448241&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50120&amp;strong=H&amp;langx=zh-cn');" title=" 阿尔及利亚 "><font true="">0.63</font></a></span></td><td class="b_rig"><span class="con">大2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118448241&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50120&amp;langx=zh-cn');" title="大"><font true="">0.76</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118448241&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con">0</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118448251&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50120&amp;strong=H&amp;langx=zh-cn');" title=" 阿尔及利亚 "><font true="">0.66</font></a></span></td><td class="b_1stR"><span class="con">大0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118448251&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50120&amp;langx=zh-cn');" title="大"><font true="">0.60</font></a></span></td></tr><tr id="TR1_01-2350120" style="display: ;"><!--<tr id="TR1_01-2350120" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118448241&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50119&amp;strong=H&amp;langx=zh-cn');" title=" 加纳 "><font true="">1.36</font></a></span></td><td class="b_rig"><span class="con">小2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118448241&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50119&amp;langx=zh-cn');" title="小"><font true="">1.13</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118448241&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118448251&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50119&amp;strong=H&amp;langx=zh-cn');" title=" 加纳 "><font true="">1.31</font></a></span></td><td class="b_1stR"><span class="con">小0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118448251&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50119&amp;langx=zh-cn');" title="小"><font true="">1.38</font></a></span></td></tr><tr id="TR2_01-2350120" style="display: ;"><!--<tr id="TR2_01-2350120" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350120_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350120"><div id="01-2350120" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50120','01-23<br>12:00p<br><font color=red>滚球</font>','非洲国家杯2015(在赤道几内亚)',' 阿尔及利亚 ',' 加纳 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: ;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('土耳其超级联赛')"><span id="土耳其超级联赛" name="土耳其超级联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('土耳其超级联赛')" class="leg_bar">土耳其超级联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350004" style="display: ;"><!--<tr id="TR_01-2350004" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>13:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350004_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 干亚士邦 <br>
       柏萨士邦 </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118376081&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50004&amp;langx=zh-cn');" title=" 干亚士邦 "><font true="">2.70</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118376081&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50004&amp;strong=C&amp;langx=zh-cn');" title=" 干亚士邦 "><font true="">1.06</font></a></span></td><td class="b_rig"><span class="con">大2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118376081&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50004&amp;langx=zh-cn');" title="大"><font true="">1.06</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118376081&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">2.03</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118376091&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50004&amp;langx=zh-cn');" title=" 干亚士邦 "><font true="">3.40</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118376091&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50004&amp;strong=C&amp;langx=zh-cn');" title=" 干亚士邦 "><font true="">1.04</font></a></span></td><td class="b_1stR"><span class="con">大1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118376091&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50004&amp;langx=zh-cn');" title="大"><font true="">1.06</font></a></span></td></tr><tr id="TR1_01-2350004" style="display: ;"><!--<tr id="TR1_01-2350004" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118376081&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50003&amp;langx=zh-cn');" title=" 柏萨士邦 "><font true="">2.40</font></a></td><td class="b_rig"><span class="con">0</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118376081&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50003&amp;strong=C&amp;langx=zh-cn');" title=" 柏萨士邦 "><font true="">0.86</font></a></span></td><td class="b_rig"><span class="con">小2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118376081&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50003&amp;langx=zh-cn');" title="小"><font true="">0.83</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118376081&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.87</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118376091&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50003&amp;langx=zh-cn');" title=" 柏萨士邦 "><font true="">3.05</font></a></td><td class="b_1stR"><span class="con">0</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118376091&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50003&amp;strong=C&amp;langx=zh-cn');" title=" 柏萨士邦 "><font true="">0.86</font></a></span></td><td class="b_1stR"><span class="con">小1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118376091&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50003&amp;langx=zh-cn');" title="小"><font true="">0.83</font></a></span></td></tr><tr id="TR2_01-2350004" style="display: ;"><!--<tr id="TR2_01-2350004" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350004_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350004"><div id="01-2350004" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50004','01-23<br>01:00p<br><font color=red>滚球</font>','土耳其超级联赛',' 干亚士邦 ',' 柏萨士邦 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118376081&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50003&amp;langx=zh-cn');" title="和"><font true="">3.20</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118376091&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50003&amp;langx=zh-cn');" title="和"><font true="">2.00</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('土耳其超级联赛')"><span id="土耳其超级联赛" name="土耳其超级联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('土耳其超级联赛')" class="leg_bar">土耳其超级联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350006" style="display: ;"><!--<tr id="TR_01-2350006" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>13:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350006_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 干亚士邦 <br>
       柏萨士邦 </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118376101&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50006&amp;strong=C&amp;langx=zh-cn');" title=" 干亚士邦 "><font true="">0.73</font></a></span></td><td class="b_rig"><span class="con">大2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118376101&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50006&amp;langx=zh-cn');" title="大"><font true="">0.82</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118376101&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118376111&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50006&amp;strong=C&amp;langx=zh-cn');" title=" 干亚士邦 "><font true="">0.53</font></a></span></td><td class="b_1stR"><span class="con">大0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118376111&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50006&amp;langx=zh-cn');" title="大"><font true="">0.62</font></a></span></td></tr><tr id="TR1_01-2350006" style="display: ;"><!--<tr id="TR1_01-2350006" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118376101&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50005&amp;strong=C&amp;langx=zh-cn');" title=" 柏萨士邦 "><font true="">1.23</font></a></span></td><td class="b_rig"><span class="con">小2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118376101&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50005&amp;langx=zh-cn');" title="小"><font true="">1.07</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118376101&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118376111&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50005&amp;strong=C&amp;langx=zh-cn');" title=" 柏萨士邦 "><font true="">1.58</font></a></span></td><td class="b_1stR"><span class="con">小0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118376111&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50005&amp;langx=zh-cn');" title="小"><font true="">1.36</font></a></span></td></tr><tr id="TR2_01-2350006" style="display: ;"><!--<tr id="TR2_01-2350006" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350006_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350006"><div id="01-2350006" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50006','01-23<br>01:00p<br><font color=red>滚球</font>','土耳其超级联赛',' 干亚士邦 ',' 柏萨士邦 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: ;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('土耳其超级联赛特别投注')"><span id="土耳其超级联赛特别投注" name="土耳其超级联赛特别投注" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('土耳其超级联赛特别投注')" class="leg_bar">土耳其超级联赛特别投注</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350012" style="display: ;"><!--<tr id="TR_01-2350012" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>13:00</td><td rowspan="2" class="team_name none" id="TR_01-2350012_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 主场 -星期五-2场赛事  <br>
       客场 -星期五-2场赛事  </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118376161&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50012&amp;langx=zh-cn');" title=" 主场 -星期五-2场赛事  "><font true="">1.63</font></a></td><td class="b_rig"><span class="con">0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118376161&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50012&amp;strong=H&amp;langx=zh-cn');" title=" 主场 -星期五-2场赛事  "><font true="">0.94</font></a></span></td><td class="b_rig"><span class="con">大5.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118376161&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50012&amp;langx=zh-cn');" title="大"><font true="">0.99</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118376161&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.97</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118376171&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50012&amp;langx=zh-cn');" title=" 主场 -星期五-2场赛事  "><font true="">1.95</font></a></td><td class="b_1stR"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118376171&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50012&amp;strong=H&amp;langx=zh-cn');" title=" 主场 -星期五-2场赛事  "><font true="">0.87</font></a></span></td><td class="b_1stR"><span class="con">大2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118376171&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50012&amp;langx=zh-cn');" title="大"><font true="">0.96</font></a></span></td></tr><tr id="TR1_01-2350012" style="display: ;"><!--<tr id="TR1_01-2350012" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118376161&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50011&amp;langx=zh-cn');" title=" 客场 -星期五-2场赛事  "><font true="">3.00</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118376161&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50011&amp;strong=H&amp;langx=zh-cn');" title=" 客场 -星期五-2场赛事  "><font true="">0.96</font></a></span></td><td class="b_rig"><span class="con">小5.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118376161&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50011&amp;langx=zh-cn');" title="小"><font true="">0.89</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118376161&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.93</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118376171&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50011&amp;langx=zh-cn');" title=" 客场 -星期五-2场赛事  "><font true="">3.20</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118376171&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50011&amp;strong=H&amp;langx=zh-cn');" title=" 客场 -星期五-2场赛事  "><font true="">1.01</font></a></span></td><td class="b_1stR"><span class="con">小2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118376171&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50011&amp;langx=zh-cn');" title="小"><font true="">0.92</font></a></span></td></tr><tr id="TR2_01-2350012" style="display: ;"><!--<tr id="TR2_01-2350012" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350012_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350012"><div id="01-2350012" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50012','01-23<br>01:00p','土耳其超级联赛特别投注',' 主场 -星期五-2场赛事  ',' 客场 -星期五-2场赛事  '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118376161&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50011&amp;langx=zh-cn');" title="和"><font true="">4.90</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118376171&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50011&amp;langx=zh-cn');" title="和"><font true="">3.05</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: ;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('土耳其超级联赛')"><span id="土耳其超级联赛" name="土耳其超级联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('土耳其超级联赛')" class="leg_bar">土耳其超级联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350008" style="display: ;"><!--<tr id="TR_01-2350008" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>14:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350008_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 特拉布宗 <br>
       希维斯堡 </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118376121&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50008&amp;langx=zh-cn');" title=" 特拉布宗 "><font true="">1.60</font></a></td><td class="b_rig"><span class="con">1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118376121&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50008&amp;strong=H&amp;langx=zh-cn');" title=" 特拉布宗 "><font true="">1.09</font></a></span></td><td class="b_rig"><span class="con">大3</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118376121&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50008&amp;langx=zh-cn');" title="大"><font true="">1.06</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118376121&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">2.00</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118376131&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50008&amp;langx=zh-cn');" title=" 特拉布宗 "><font true="">2.16</font></a></td><td class="b_1stR"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118376131&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50008&amp;strong=H&amp;langx=zh-cn');" title=" 特拉布宗 "><font true="">0.80</font></a></span></td><td class="b_1stR"><span class="con">大1 / 1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118376131&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50008&amp;langx=zh-cn');" title="大"><font true="">1.12</font></a></span></td></tr><tr id="TR1_01-2350008" style="display: ;"><!--<tr id="TR1_01-2350008" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118376121&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50007&amp;langx=zh-cn');" title=" 希维斯堡 "><font true="">4.70</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118376121&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50007&amp;strong=H&amp;langx=zh-cn');" title=" 希维斯堡 "><font true="">0.83</font></a></span></td><td class="b_rig"><span class="con">小3</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118376121&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50007&amp;langx=zh-cn');" title="小"><font true="">0.83</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118376121&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.90</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118376131&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50007&amp;langx=zh-cn');" title=" 希维斯堡 "><font true="">4.60</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118376131&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50007&amp;strong=H&amp;langx=zh-cn');" title=" 希维斯堡 "><font true="">1.11</font></a></span></td><td class="b_1stR"><span class="con">小1 / 1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118376131&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50007&amp;langx=zh-cn');" title="小"><font true="">0.78</font></a></span></td></tr><tr id="TR2_01-2350008" style="display: ;"><!--<tr id="TR2_01-2350008" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350008_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350008"><div id="01-2350008" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50008','01-23<br>02:00p<br><font color=red>滚球</font>','土耳其超级联赛',' 特拉布宗 ',' 希维斯堡 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118376121&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50007&amp;langx=zh-cn');" title="和"><font true="">3.80</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118376131&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50007&amp;langx=zh-cn');" title="和"><font true="">2.30</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('土耳其超级联赛')"><span id="土耳其超级联赛" name="土耳其超级联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('土耳其超级联赛')" class="leg_bar">土耳其超级联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350010" style="display: ;"><!--<tr id="TR_01-2350010" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>14:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350010_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 特拉布宗 <br>
       希维斯堡 </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con">0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118376141&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50010&amp;strong=H&amp;langx=zh-cn');" title=" 特拉布宗 "><font true="">0.81</font></a></span></td><td class="b_rig"><span class="con">大2.5 / 3</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118376141&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50010&amp;langx=zh-cn');" title="大"><font true="">0.79</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118376141&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con">0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118376151&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50010&amp;strong=H&amp;langx=zh-cn');" title=" 特拉布宗 "><font true="">1.17</font></a></span></td><td class="b_1stR"><span class="con">大1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118376151&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50010&amp;langx=zh-cn');" title="大"><font true="">0.71</font></a></span></td></tr><tr id="TR1_01-2350010" style="display: ;"><!--<tr id="TR1_01-2350010" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118376141&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50009&amp;strong=H&amp;langx=zh-cn');" title=" 希维斯堡 "><font true="">1.12</font></a></span></td><td class="b_rig"><span class="con">小2.5 / 3</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118376141&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50009&amp;langx=zh-cn');" title="小"><font true="">1.11</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118376141&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118376151&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50009&amp;strong=H&amp;langx=zh-cn');" title=" 希维斯堡 "><font true="">0.75</font></a></span></td><td class="b_1stR"><span class="con">小1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118376151&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50009&amp;langx=zh-cn');" title="小"><font true="">1.21</font></a></span></td></tr><tr id="TR2_01-2350010" style="display: ;"><!--<tr id="TR2_01-2350010" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350010_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350010"><div id="01-2350010" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50010','01-23<br>02:00p<br><font color=red>滚球</font>','土耳其超级联赛',' 特拉布宗 ',' 希维斯堡 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: ;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('法国杯')"><span id="法国杯" name="法国杯" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('法国杯')" class="leg_bar">法国杯</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350038" style="display: ;"><!--<tr id="TR_01-2350038" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>14:30<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350038_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 红星圣欧恩 <br>
       康索拉特 </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118401761&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50038&amp;langx=zh-cn');" title=" 红星圣欧恩 "><font true="">1.72</font></a></td><td class="b_rig"><span class="con">0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118401761&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50038&amp;strong=H&amp;langx=zh-cn');" title=" 红星圣欧恩 "><font true="">0.98</font></a></span></td><td class="b_rig"><span class="con">大2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118401761&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50038&amp;langx=zh-cn');" title="大"><font true="">1.02</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118401761&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">2.03</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118401771&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50038&amp;langx=zh-cn');" title=" 红星圣欧恩 "><font true="">2.42</font></a></td><td class="b_1stR"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118401771&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50038&amp;strong=H&amp;langx=zh-cn');" title=" 红星圣欧恩 "><font true="">0.97</font></a></span></td><td class="b_1stR"><span class="con">大0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118401771&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50038&amp;langx=zh-cn');" title="大"><font true="">0.79</font></a></span></td></tr><tr id="TR1_01-2350038" style="display: ;"><!--<tr id="TR1_01-2350038" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118401761&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50037&amp;langx=zh-cn');" title=" 康索拉特 "><font true="">4.30</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118401761&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50037&amp;strong=H&amp;langx=zh-cn');" title=" 康索拉特 "><font true="">0.92</font></a></span></td><td class="b_rig"><span class="con">小2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118401761&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50037&amp;langx=zh-cn');" title="小"><font true="">0.86</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118401761&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.87</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118401771&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50037&amp;langx=zh-cn');" title=" 康索拉特 "><font true="">4.30</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118401771&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50037&amp;strong=H&amp;langx=zh-cn');" title=" 康索拉特 "><font true="">0.93</font></a></span></td><td class="b_1stR"><span class="con">小0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118401771&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50037&amp;langx=zh-cn');" title="小"><font true="">1.09</font></a></span></td></tr><tr id="TR2_01-2350038" style="display: ;"><!--<tr id="TR2_01-2350038" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350038_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350038"><div id="01-2350038" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50038','01-23<br>02:30p<br><font color=red>滚球</font>','法国杯',' 红星圣欧恩 ',' 康索拉特 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118401761&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50037&amp;langx=zh-cn');" title="和"><font true="">3.50</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118401771&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50037&amp;langx=zh-cn');" title="和"><font true="">2.10</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('法国杯')"><span id="法国杯" name="法国杯" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('法国杯')" class="leg_bar">法国杯</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350040" style="display: ;"><!--<tr id="TR_01-2350040" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>14:30<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350040_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 红星圣欧恩 <br>
       康索拉特 </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con">0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118401781&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50040&amp;strong=H&amp;langx=zh-cn');" title=" 红星圣欧恩 "><font true="">0.73</font></a></span></td><td class="b_rig"><span class="con">大2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118401781&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50040&amp;langx=zh-cn');" title="大"><font true="">0.74</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118401781&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con">0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118401791&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50040&amp;strong=H&amp;langx=zh-cn');" title=" 红星圣欧恩 "><font true="">1.44</font></a></span></td><td class="b_1stR"><span class="con">大1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118401791&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50040&amp;langx=zh-cn');" title="大"><font true="">1.28</font></a></span></td></tr><tr id="TR1_01-2350040" style="display: ;"><!--<tr id="TR1_01-2350040" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118401781&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50039&amp;strong=H&amp;langx=zh-cn');" title=" 康索拉特 "><font true="">1.20</font></a></span></td><td class="b_rig"><span class="con">小2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118401781&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50039&amp;langx=zh-cn');" title="小"><font true="">1.16</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118401781&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118401791&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50039&amp;strong=H&amp;langx=zh-cn');" title=" 康索拉特 "><font true="">0.59</font></a></span></td><td class="b_1stR"><span class="con">小1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118401791&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50039&amp;langx=zh-cn');" title="小"><font true="">0.66</font></a></span></td></tr><tr id="TR2_01-2350040" style="display: ;"><!--<tr id="TR2_01-2350040" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350040_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350040"><div id="01-2350040" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50040','01-23<br>02:30p<br><font color=red>滚球</font>','法国杯',' 红星圣欧恩 ',' 康索拉特 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: ;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('意大利职业联赛')"><span id="意大利职业联赛" name="意大利职业联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('意大利职业联赛')" class="leg_bar">意大利职业联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350208" style="display: ;"><!--<tr id="TR_01-2350208" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>14:30<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350208_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 拉梅兹亚 <br>
       伊斯基亚 </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118458921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50208&amp;langx=zh-cn');" title=" 拉梅兹亚 "><font true="">1.90</font></a></td><td class="b_rig"><span class="con">0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118458921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50208&amp;strong=H&amp;langx=zh-cn');" title=" 拉梅兹亚 "><font true="">0.91</font></a></span></td><td class="b_rig"><span class="con">大2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118458921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50208&amp;langx=zh-cn');" title="大"><font true="">0.98</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118458921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.99</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118458931&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50208&amp;langx=zh-cn');" title=" 拉梅兹亚 "><font true="">2.50</font></a></td><td class="b_1stR"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118458931&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50208&amp;strong=H&amp;langx=zh-cn');" title=" 拉梅兹亚 "><font true="">1.07</font></a></span></td><td class="b_1stR"><span class="con">大0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118458931&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50208&amp;langx=zh-cn');" title="大"><font true="">0.78</font></a></span></td></tr><tr id="TR1_01-2350208" style="display: ;"><!--<tr id="TR1_01-2350208" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118458921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50207&amp;langx=zh-cn');" title=" 伊斯基亚 "><font true="">3.45</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118458921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50207&amp;strong=H&amp;langx=zh-cn');" title=" 伊斯基亚 "><font true="">0.97</font></a></span></td><td class="b_rig"><span class="con">小2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118458921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50207&amp;langx=zh-cn');" title="小"><font true="">0.88</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118458921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.89</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118458931&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50207&amp;langx=zh-cn');" title=" 伊斯基亚 "><font true="">4.50</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118458931&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50207&amp;strong=H&amp;langx=zh-cn');" title=" 伊斯基亚 "><font true="">0.81</font></a></span></td><td class="b_1stR"><span class="con">小0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118458931&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50207&amp;langx=zh-cn');" title="小"><font true="">1.08</font></a></span></td></tr><tr id="TR2_01-2350208" style="display: ;"><!--<tr id="TR2_01-2350208" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350208_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350208"><div id="01-2350208" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50208','01-23<br>02:30p<br><font color=red>滚球</font>','意大利职业联赛',' 拉梅兹亚 ',' 伊斯基亚 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118458921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50207&amp;langx=zh-cn');" title="和"><font true="">3.30</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118458931&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50207&amp;langx=zh-cn');" title="和"><font true="">2.00</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: ;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('荷兰甲组联赛')"><span id="荷兰甲组联赛" name="荷兰甲组联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('荷兰甲组联赛')" class="leg_bar">荷兰甲组联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350028" style="display: ;"><!--<tr id="TR_01-2350028" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>15:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350028_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 川迪 <br>
       荷华高斯 </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118395901&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50028&amp;langx=zh-cn');" title=" 川迪 "><font true="">1.35</font></a></td><td class="b_rig"><span class="con">1 / 1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118395901&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50028&amp;strong=H&amp;langx=zh-cn');" title=" 川迪 "><font true="">0.93</font></a></span></td><td class="b_rig"><span class="con">大3 / 3.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118395901&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50028&amp;langx=zh-cn');" title="大"><font true="">0.96</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118395901&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.99</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118395911&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50028&amp;langx=zh-cn');" title=" 川迪 "><font true="">1.82</font></a></td><td class="b_1stR"><span class="con">0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118395911&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50028&amp;strong=H&amp;langx=zh-cn');" title=" 川迪 "><font true="">0.89</font></a></span></td><td class="b_1stR"><span class="con">大1 / 1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118395911&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50028&amp;langx=zh-cn');" title="大"><font true="">0.86</font></a></span></td></tr><tr id="TR1_01-2350028" style="display: ;"><!--<tr id="TR1_01-2350028" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118395901&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50027&amp;langx=zh-cn');" title=" 荷华高斯 "><font true="">6.50</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118395901&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50027&amp;strong=H&amp;langx=zh-cn');" title=" 荷华高斯 "><font true="">0.99</font></a></span></td><td class="b_rig"><span class="con">小3 / 3.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118395901&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50027&amp;langx=zh-cn');" title="小"><font true="">0.94</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118395901&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.91</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118395911&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50027&amp;langx=zh-cn');" title=" 荷华高斯 "><font true="">6.35</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118395911&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50027&amp;strong=H&amp;langx=zh-cn');" title=" 荷华高斯 "><font true="">1.01</font></a></span></td><td class="b_1stR"><span class="con">小1 / 1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118395911&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50027&amp;langx=zh-cn');" title="小"><font true="">1.04</font></a></span></td></tr><tr id="TR2_01-2350028" style="display: ;"><!--<tr id="TR2_01-2350028" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350028_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350028"><div id="01-2350028" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50028','01-23<br>03:00p<br><font color=red>滚球</font>','荷兰甲组联赛',' 川迪 ',' 荷华高斯 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118395901&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50027&amp;langx=zh-cn');" title="和"><font true="">4.80</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118395911&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50027&amp;langx=zh-cn');" title="和"><font true="">2.40</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('荷兰甲组联赛')"><span id="荷兰甲组联赛" name="荷兰甲组联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('荷兰甲组联赛')" class="leg_bar">荷兰甲组联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350030" style="display: ;"><!--<tr id="TR_01-2350030" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>15:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350030_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 川迪 <br>
       荷华高斯 </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con">1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118395921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50030&amp;strong=H&amp;langx=zh-cn');" title=" 川迪 "><font true="">1.14</font></a></span></td><td class="b_rig"><span class="con">大3.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118395921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50030&amp;langx=zh-cn');" title="大"><font true="">1.19</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118395921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con">0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118395931&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50030&amp;strong=H&amp;langx=zh-cn');" title=" 川迪 "><font true="">1.31</font></a></span></td><td class="b_1stR"><span class="con">大1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118395931&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50030&amp;langx=zh-cn');" title="大"><font true="">1.23</font></a></span></td></tr><tr id="TR1_01-2350030" style="display: ;"><!--<tr id="TR1_01-2350030" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118395921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50029&amp;strong=H&amp;langx=zh-cn');" title=" 荷华高斯 "><font true="">0.79</font></a></span></td><td class="b_rig"><span class="con">小3.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118395921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50029&amp;langx=zh-cn');" title="小"><font true="">0.74</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118395921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118395931&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50029&amp;strong=H&amp;langx=zh-cn');" title=" 荷华高斯 "><font true="">0.66</font></a></span></td><td class="b_1stR"><span class="con">小1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118395931&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50029&amp;langx=zh-cn');" title="小"><font true="">0.71</font></a></span></td></tr><tr id="TR2_01-2350030" style="display: ;"><!--<tr id="TR2_01-2350030" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350030_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350030"><div id="01-2350030" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50030','01-23<br>03:00p<br><font color=red>滚球</font>','荷兰甲组联赛',' 川迪 ',' 荷华高斯 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('荷兰甲组联赛')"><span id="荷兰甲组联赛" name="荷兰甲组联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('荷兰甲组联赛')" class="leg_bar">荷兰甲组联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350032" style="display: ;"><!--<tr id="TR_01-2350032" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>15:00</td><td rowspan="2" class="team_name none" id="TR_01-2350032_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 川迪 <br>
       荷华高斯 </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con">1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118395941&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50032&amp;strong=H&amp;langx=zh-cn');" title=" 川迪 "><font true="">0.66</font></a></span></td><td class="b_rig"><span class="con">大3</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118395941&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50032&amp;langx=zh-cn');" title="大"><font true="">0.73</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118395941&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50032&amp;strong=&amp;langx=zh-cn');" title=" 川迪 "><font undefined=""></font></a></span></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50032&amp;langx=zh-cn');" title="大"><font undefined=""></font></a></span></td></tr><tr id="TR1_01-2350032" style="display: ;"><!--<tr id="TR1_01-2350032" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118395941&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50031&amp;strong=H&amp;langx=zh-cn');" title=" 荷华高斯 "><font true="">1.35</font></a></span></td><td class="b_rig"><span class="con">小3</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118395941&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50031&amp;langx=zh-cn');" title="小"><font true="">1.20</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118395941&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50031&amp;strong=&amp;langx=zh-cn');" title=" 荷华高斯 "><font undefined=""></font></a></span></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50031&amp;langx=zh-cn');" title="小"><font undefined=""></font></a></span></td></tr><tr id="TR2_01-2350032" style="display: ;"><!--<tr id="TR2_01-2350032" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350032_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350032"><div id="01-2350032" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50032','01-23<br>03:00p','荷兰甲组联赛',' 川迪 ',' 荷华高斯 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: ;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('荷兰乙组联赛')"><span id="荷兰乙组联赛" name="荷兰乙组联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('荷兰乙组联赛')" class="leg_bar">荷兰乙组联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350076" style="display: ;"><!--<tr id="TR_01-2350076" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>15:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350076_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> RKC华域克 <br>
       NEC尼美根 </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118439061&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50076&amp;langx=zh-cn');" title=" RKC华域克 "><font true="">6.06</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118439061&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50076&amp;strong=C&amp;langx=zh-cn');" title=" RKC华域克 "><font true="">0.88</font></a></span></td><td class="b_rig"><span class="con">大3</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118439061&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50076&amp;langx=zh-cn');" title="大"><font true="">0.89</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118439061&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.97</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118439071&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50076&amp;langx=zh-cn');" title=" RKC华域克 "><font true="">4.65</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118439071&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50076&amp;strong=C&amp;langx=zh-cn');" title=" RKC华域克 "><font true="">0.87</font></a></span></td><td class="b_1stR"><span class="con">大1 / 1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118439071&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50076&amp;langx=zh-cn');" title="大"><font true="">0.91</font></a></span></td></tr><tr id="TR1_01-2350076" style="display: ;"><!--<tr id="TR1_01-2350076" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118439061&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50075&amp;langx=zh-cn');" title=" NEC尼美根 "><font true="">1.40</font></a></td><td class="b_rig"><span class="con">1 / 1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118439061&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50075&amp;strong=C&amp;langx=zh-cn');" title=" NEC尼美根 "><font true="">1.04</font></a></span></td><td class="b_rig"><span class="con">小3</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118439061&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50075&amp;langx=zh-cn');" title="小"><font true="">0.99</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118439061&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.93</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118439071&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50075&amp;langx=zh-cn');" title=" NEC尼美根 "><font true="">2.00</font></a></td><td class="b_1stR"><span class="con">0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118439071&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50075&amp;strong=C&amp;langx=zh-cn');" title=" NEC尼美根 "><font true="">1.03</font></a></span></td><td class="b_1stR"><span class="con">小1 / 1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118439071&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50075&amp;langx=zh-cn');" title="小"><font true="">0.97</font></a></span></td></tr><tr id="TR2_01-2350076" style="display: ;"><!--<tr id="TR2_01-2350076" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350076_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350076"><div id="01-2350076" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50076','01-23<br>03:00p<br><font color=red>滚球</font>','荷兰乙组联赛',' RKC华域克 ',' NEC尼美根 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118439061&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50075&amp;langx=zh-cn');" title="和"><font true="">4.50</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118439071&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50075&amp;langx=zh-cn');" title="和"><font true="">2.45</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('荷兰乙组联赛')"><span id="荷兰乙组联赛" name="荷兰乙组联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('荷兰乙组联赛')" class="leg_bar">荷兰乙组联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350080" style="display: ;"><!--<tr id="TR_01-2350080" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>15:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350080_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 希蒙 <br>
       埃曼 </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118439101&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50080&amp;langx=zh-cn');" title=" 希蒙 "><font true="">3.10</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118439101&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50080&amp;strong=C&amp;langx=zh-cn');" title=" 希蒙 "><font true="">0.88</font></a></span></td><td class="b_rig"><span class="con">大3 / 3.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118439101&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50080&amp;langx=zh-cn');" title="大"><font true="">0.99</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118439101&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">2.00</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118439111&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50080&amp;langx=zh-cn');" title=" 希蒙 "><font true="">4.00</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118439111&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50080&amp;strong=C&amp;langx=zh-cn');" title=" 希蒙 "><font true="">0.81</font></a></span></td><td class="b_1stR"><span class="con">大1 / 1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118439111&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50080&amp;langx=zh-cn');" title="大"><font true="">0.92</font></a></span></td></tr><tr id="TR1_01-2350080" style="display: ;"><!--<tr id="TR1_01-2350080" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118439101&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50079&amp;langx=zh-cn');" title=" 埃曼 "><font true="">2.01</font></a></td><td class="b_rig"><span class="con">0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118439101&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50079&amp;strong=C&amp;langx=zh-cn');" title=" 埃曼 "><font true="">1.04</font></a></span></td><td class="b_rig"><span class="con">小3 / 3.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118439101&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50079&amp;langx=zh-cn');" title="小"><font true="">0.89</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118439101&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.90</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118439111&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50079&amp;langx=zh-cn');" title=" 埃曼 "><font true="">2.45</font></a></td><td class="b_1stR"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118439111&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50079&amp;strong=C&amp;langx=zh-cn');" title=" 埃曼 "><font true="">1.09</font></a></span></td><td class="b_1stR"><span class="con">小1 / 1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118439111&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50079&amp;langx=zh-cn');" title="小"><font true="">0.96</font></a></span></td></tr><tr id="TR2_01-2350080" style="display: ;"><!--<tr id="TR2_01-2350080" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350080_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350080"><div id="01-2350080" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50080','01-23<br>03:00p<br><font color=red>滚球</font>','荷兰乙组联赛',' 希蒙 ',' 埃曼 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118439101&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50079&amp;langx=zh-cn');" title="和"><font true="">3.60</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118439111&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50079&amp;langx=zh-cn');" title="和"><font true="">2.15</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('荷兰乙组联赛')"><span id="荷兰乙组联赛" name="荷兰乙组联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('荷兰乙组联赛')" class="leg_bar">荷兰乙组联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350084" style="display: ;"><!--<tr id="TR_01-2350084" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>15:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350084_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 艾切尔斯 <br>
       恩浩云 </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118439141&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50084&amp;langx=zh-cn');" title=" 艾切尔斯 "><font true="">5.60</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118439141&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50084&amp;strong=C&amp;langx=zh-cn');" title=" 艾切尔斯 "><font true="">1.12</font></a></span></td><td class="b_rig"><span class="con">大3</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118439141&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50084&amp;langx=zh-cn');" title="大"><font true="">0.86</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118439141&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.97</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118439151&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50084&amp;langx=zh-cn');" title=" 艾切尔斯 "><font true="">4.50</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118439151&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50084&amp;strong=C&amp;langx=zh-cn');" title=" 艾切尔斯 "><font true="">0.81</font></a></span></td><td class="b_1stR"><span class="con">大1 / 1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118439151&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50084&amp;langx=zh-cn');" title="大"><font true="">0.93</font></a></span></td></tr><tr id="TR1_01-2350084" style="display: ;"><!--<tr id="TR1_01-2350084" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118439141&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50083&amp;langx=zh-cn');" title=" 恩浩云 "><font true="">1.45</font></a></td><td class="b_rig"><span class="con">1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118439141&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50083&amp;strong=C&amp;langx=zh-cn');" title=" 恩浩云 "><font true="">0.81</font></a></span></td><td class="b_rig"><span class="con">小3</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118439141&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50083&amp;langx=zh-cn');" title="小"><font true="">1.02</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118439141&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.93</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118439151&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50083&amp;langx=zh-cn');" title=" 恩浩云 "><font true="">2.08</font></a></td><td class="b_1stR"><span class="con">0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118439151&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50083&amp;strong=C&amp;langx=zh-cn');" title=" 恩浩云 "><font true="">1.09</font></a></span></td><td class="b_1stR"><span class="con">小1 / 1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118439151&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50083&amp;langx=zh-cn');" title="小"><font true="">0.95</font></a></span></td></tr><tr id="TR2_01-2350084" style="display: ;"><!--<tr id="TR2_01-2350084" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350084_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350084"><div id="01-2350084" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50084','01-23<br>03:00p<br><font color=red>滚球</font>','荷兰乙组联赛',' 艾切尔斯 ',' 恩浩云 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118439141&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50083&amp;langx=zh-cn');" title="和"><font true="">4.30</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118439151&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50083&amp;langx=zh-cn');" title="和"><font true="">2.40</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('荷兰乙组联赛')"><span id="荷兰乙组联赛" name="荷兰乙组联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('荷兰乙组联赛')" class="leg_bar">荷兰乙组联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350088" style="display: ;"><!--<tr id="TR_01-2350088" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>15:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350088_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> FC奥斯 <br>
       阿梅尔城 </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118439181&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50088&amp;langx=zh-cn');" title=" FC奥斯 "><font true="">2.30</font></a></td><td class="b_rig"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118439181&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50088&amp;strong=H&amp;langx=zh-cn');" title=" FC奥斯 "><font true="">1.06</font></a></span></td><td class="b_rig"><span class="con">大3 / 3.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118439181&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50088&amp;langx=zh-cn');" title="大"><font true="">0.99</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118439181&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.99</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118439191&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50088&amp;langx=zh-cn');" title=" FC奥斯 "><font true="">2.85</font></a></td><td class="b_1stR"><span class="con">0</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118439191&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50088&amp;strong=H&amp;langx=zh-cn');" title=" FC奥斯 "><font true="">0.79</font></a></span></td><td class="b_1stR"><span class="con">大1 / 1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118439191&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50088&amp;langx=zh-cn');" title="大"><font true="">0.81</font></a></span></td></tr><tr id="TR1_01-2350088" style="display: ;"><!--<tr id="TR1_01-2350088" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118439181&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50087&amp;langx=zh-cn');" title=" 阿梅尔城 "><font true="">2.65</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118439181&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50087&amp;strong=H&amp;langx=zh-cn');" title=" 阿梅尔城 "><font true="">0.86</font></a></span></td><td class="b_rig"><span class="con">小3 / 3.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118439181&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50087&amp;langx=zh-cn');" title="小"><font true="">0.89</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118439181&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.91</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118439191&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50087&amp;langx=zh-cn');" title=" 阿梅尔城 "><font true="">3.25</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118439191&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50087&amp;strong=H&amp;langx=zh-cn');" title=" 阿梅尔城 "><font true="">1.12</font></a></span></td><td class="b_1stR"><span class="con">小1 / 1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118439191&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50087&amp;langx=zh-cn');" title="小"><font true="">1.07</font></a></span></td></tr><tr id="TR2_01-2350088" style="display: ;"><!--<tr id="TR2_01-2350088" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350088_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350088"><div id="01-2350088" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50088','01-23<br>03:00p<br><font color=red>滚球</font>','荷兰乙组联赛',' FC奥斯 ',' 阿梅尔城 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118439181&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50087&amp;langx=zh-cn');" title="和"><font true="">3.50</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118439191&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50087&amp;langx=zh-cn');" title="和"><font true="">2.15</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('荷兰乙组联赛')"><span id="荷兰乙组联赛" name="荷兰乙组联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('荷兰乙组联赛')" class="leg_bar">荷兰乙组联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350092" style="display: ;"><!--<tr id="TR_01-2350092" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>15:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350092_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 丹博斯治 <br>
       华伦丹 </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118439221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50092&amp;langx=zh-cn');" title=" 丹博斯治 "><font true="">2.55</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118439221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50092&amp;strong=C&amp;langx=zh-cn');" title=" 丹博斯治 "><font true="">1.01</font></a></span></td><td class="b_rig"><span class="con">大3</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118439221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50092&amp;langx=zh-cn');" title="大"><font true="">0.98</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118439221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">2.00</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118439231&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50092&amp;langx=zh-cn');" title=" 丹博斯治 "><font true="">3.20</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118439231&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50092&amp;strong=C&amp;langx=zh-cn');" title=" 丹博斯治 "><font true="">1.03</font></a></span></td><td class="b_1stR"><span class="con">大1 / 1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118439231&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50092&amp;langx=zh-cn');" title="大"><font true="">1.02</font></a></span></td></tr><tr id="TR1_01-2350092" style="display: ;"><!--<tr id="TR1_01-2350092" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118439221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50091&amp;langx=zh-cn');" title=" 华伦丹 "><font true="">2.40</font></a></td><td class="b_rig"><span class="con">0</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118439221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50091&amp;strong=C&amp;langx=zh-cn');" title=" 华伦丹 "><font true="">0.91</font></a></span></td><td class="b_rig"><span class="con">小3</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118439221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50091&amp;langx=zh-cn');" title="小"><font true="">0.90</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118439221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.90</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118439231&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50091&amp;langx=zh-cn');" title=" 华伦丹 "><font true="">3.00</font></a></td><td class="b_1stR"><span class="con">0</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118439231&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50091&amp;strong=C&amp;langx=zh-cn');" title=" 华伦丹 "><font true="">0.87</font></a></span></td><td class="b_1stR"><span class="con">小1 / 1.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118439231&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50091&amp;langx=zh-cn');" title="小"><font true="">0.86</font></a></span></td></tr><tr id="TR2_01-2350092" style="display: ;"><!--<tr id="TR2_01-2350092" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350092_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350092"><div id="01-2350092" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50092','01-23<br>03:00p<br><font color=red>滚球</font>','荷兰乙组联赛',' 丹博斯治 ',' 华伦丹 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118439221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50091&amp;langx=zh-cn');" title="和"><font true="">3.40</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118439231&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50091&amp;langx=zh-cn');" title="和"><font true="">2.10</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: ;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('法国乙组联赛')"><span id="法国乙组联赛" name="法国乙组联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('法国乙组联赛')" class="leg_bar">法国乙组联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350044" style="display: ;"><!--<tr id="TR_01-2350044" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>15:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350044_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 阿些斯奥 <br>
       GFC阿些斯奥 </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118434881&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50044&amp;langx=zh-cn');" title=" 阿些斯奥 "><font true="">2.45</font></a></td><td class="b_rig"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118434881&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50044&amp;strong=H&amp;langx=zh-cn');" title=" 阿些斯奥 "><font true="">1.08</font></a></span></td><td class="b_rig"><span class="con">大2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118434881&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50044&amp;langx=zh-cn');" title="大"><font true="">0.91</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118434881&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">2.04</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118434891&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50044&amp;langx=zh-cn');" title=" 阿些斯奥 "><font true="">2.95</font></a></td><td class="b_1stR"><span class="con">0</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118434891&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50044&amp;strong=H&amp;langx=zh-cn');" title=" 阿些斯奥 "><font true="">0.74</font></a></span></td><td class="b_1stR"><span class="con">大0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118434891&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50044&amp;langx=zh-cn');" title="大"><font true="">0.86</font></a></span></td></tr><tr id="TR1_01-2350044" style="display: ;"><!--<tr id="TR1_01-2350044" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118434881&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50043&amp;langx=zh-cn');" title=" GFC阿些斯奥 "><font true="">2.90</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118434881&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50043&amp;strong=H&amp;langx=zh-cn');" title=" GFC阿些斯奥 "><font true="">0.84</font></a></span></td><td class="b_rig"><span class="con">小2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118434881&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50043&amp;langx=zh-cn');" title="小"><font true="">0.97</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118434881&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.86</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118434891&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50043&amp;langx=zh-cn');" title=" GFC阿些斯奥 "><font true="">3.90</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118434891&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50043&amp;strong=H&amp;langx=zh-cn');" title=" GFC阿些斯奥 "><font true="">1.19</font></a></span></td><td class="b_1stR"><span class="con">小0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118434891&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50043&amp;langx=zh-cn');" title="小"><font true="">1.02</font></a></span></td></tr><tr id="TR2_01-2350044" style="display: ;"><!--<tr id="TR2_01-2350044" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350044_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350044"><div id="01-2350044" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50044','01-23<br>03:00p<br><font color=red>滚球</font>','法国乙组联赛',' 阿些斯奥 ',' GFC阿些斯奥 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118434881&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50043&amp;langx=zh-cn');" title="和"><font true="">3.05</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118434891&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50043&amp;langx=zh-cn');" title="和"><font true="">1.90</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('法国乙组联赛')"><span id="法国乙组联赛" name="法国乙组联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('法国乙组联赛')" class="leg_bar">法国乙组联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350046" style="display: ;"><!--<tr id="TR_01-2350046" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>15:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350046_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 阿些斯奥 <br>
       GFC阿些斯奥 </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con">0</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118434901&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50046&amp;strong=H&amp;langx=zh-cn');" title=" 阿些斯奥 "><font true="">0.69</font></a></span></td><td class="b_rig"><span class="con">大2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118434901&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50046&amp;langx=zh-cn');" title="大"><font true="">1.17</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118434901&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118434911&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50046&amp;strong=H&amp;langx=zh-cn');" title=" 阿些斯奥 "><font true="">1.44</font></a></span></td><td class="b_1stR"><span class="con">大0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118434911&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50046&amp;langx=zh-cn');" title="大"><font true="">0.59</font></a></span></td></tr><tr id="TR1_01-2350046" style="display: ;"><!--<tr id="TR1_01-2350046" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118434901&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50045&amp;strong=H&amp;langx=zh-cn');" title=" GFC阿些斯奥 "><font true="">1.29</font></a></span></td><td class="b_rig"><span class="con">小2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118434901&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50045&amp;langx=zh-cn');" title="小"><font true="">0.73</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118434901&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118434911&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50045&amp;strong=H&amp;langx=zh-cn');" title=" GFC阿些斯奥 "><font true="">0.59</font></a></span></td><td class="b_1stR"><span class="con">小0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118434911&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50045&amp;langx=zh-cn');" title="小"><font true="">1.40</font></a></span></td></tr><tr id="TR2_01-2350046" style="display: ;"><!--<tr id="TR2_01-2350046" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350046_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350046"><div id="01-2350046" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50046','01-23<br>03:00p<br><font color=red>滚球</font>','法国乙组联赛',' 阿些斯奥 ',' GFC阿些斯奥 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('法国乙组联赛')"><span id="法国乙组联赛" name="法国乙组联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('法国乙组联赛')" class="leg_bar">法国乙组联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350048" style="display: ;"><!--<tr id="TR_01-2350048" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>15:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350048_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 阿莱斯亚维农 <br>
       南锡 </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118434921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50048&amp;langx=zh-cn');" title=" 阿莱斯亚维农 "><font true="">3.00</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118434921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50048&amp;strong=C&amp;langx=zh-cn');" title=" 阿莱斯亚维农 "><font true="">0.89</font></a></span></td><td class="b_rig"><span class="con">大2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118434921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50048&amp;langx=zh-cn');" title="大"><font true="">0.81</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118434921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">2.04</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118434931&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50048&amp;langx=zh-cn');" title=" 阿莱斯亚维农 "><font true="">3.90</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118434931&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50048&amp;strong=C&amp;langx=zh-cn');" title=" 阿莱斯亚维农 "><font true="">1.22</font></a></span></td><td class="b_1stR"><span class="con">大0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118434931&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50048&amp;langx=zh-cn');" title="大"><font true="">0.84</font></a></span></td></tr><tr id="TR1_01-2350048" style="display: ;"><!--<tr id="TR1_01-2350048" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118434921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50047&amp;langx=zh-cn');" title=" 南锡 "><font true="">2.35</font></a></td><td class="b_rig"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118434921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50047&amp;strong=C&amp;langx=zh-cn');" title=" 南锡 "><font true="">1.03</font></a></span></td><td class="b_rig"><span class="con">小2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118434921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50047&amp;langx=zh-cn');" title="小"><font true="">1.07</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118434921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.86</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118434931&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50047&amp;langx=zh-cn');" title=" 南锡 "><font true="">2.95</font></a></td><td class="b_1stR"><span class="con">0</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118434931&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50047&amp;strong=C&amp;langx=zh-cn');" title=" 南锡 "><font true="">0.71</font></a></span></td><td class="b_1stR"><span class="con">小0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118434931&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50047&amp;langx=zh-cn');" title="小"><font true="">1.04</font></a></span></td></tr><tr id="TR2_01-2350048" style="display: ;"><!--<tr id="TR2_01-2350048" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350048_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350048"><div id="01-2350048" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50048','01-23<br>03:00p<br><font color=red>滚球</font>','法国乙组联赛',' 阿莱斯亚维农 ',' 南锡 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118434921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50047&amp;langx=zh-cn');" title="和"><font true="">3.10</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118434931&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50047&amp;langx=zh-cn');" title="和"><font true="">1.90</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('法国乙组联赛')"><span id="法国乙组联赛" name="法国乙组联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('法国乙组联赛')" class="leg_bar">法国乙组联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350050" style="display: ;"><!--<tr id="TR_01-2350050" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>15:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350050_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 阿莱斯亚维农 <br>
       南锡 </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118434941&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50050&amp;strong=C&amp;langx=zh-cn');" title=" 阿莱斯亚维农 "><font true="">1.40</font></a></span></td><td class="b_rig"><span class="con">大2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118434941&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50050&amp;langx=zh-cn');" title="大"><font true="">1.13</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118434941&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118434951&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50050&amp;strong=C&amp;langx=zh-cn');" title=" 阿莱斯亚维农 "><font true="">0.56</font></a></span></td><td class="b_1stR"><span class="con">大1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118434951&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50050&amp;langx=zh-cn');" title="大"><font true="">1.40</font></a></span></td></tr><tr id="TR1_01-2350050" style="display: ;"><!--<tr id="TR1_01-2350050" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con">0</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118434941&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50049&amp;strong=C&amp;langx=zh-cn');" title=" 南锡 "><font true="">0.63</font></a></span></td><td class="b_rig"><span class="con">小2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118434941&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50049&amp;langx=zh-cn');" title="小"><font true="">0.76</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118434941&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118434951&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50049&amp;strong=C&amp;langx=zh-cn');" title=" 南锡 "><font true="">1.51</font></a></span></td><td class="b_1stR"><span class="con">小1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118434951&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50049&amp;langx=zh-cn');" title="小"><font true="">0.59</font></a></span></td></tr><tr id="TR2_01-2350050" style="display: ;"><!--<tr id="TR2_01-2350050" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350050_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350050"><div id="01-2350050" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50050','01-23<br>03:00p<br><font color=red>滚球</font>','法国乙组联赛',' 阿莱斯亚维农 ',' 南锡 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('法国乙组联赛')"><span id="法国乙组联赛" name="法国乙组联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('法国乙组联赛')" class="leg_bar">法国乙组联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350052" style="display: ;"><!--<tr id="TR_01-2350052" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>15:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350052_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 贾特尔 <br>
       拉华尔 </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118434961&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50052&amp;langx=zh-cn');" title=" 贾特尔 "><font true="">2.21</font></a></td><td class="b_rig"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118434961&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50052&amp;strong=H&amp;langx=zh-cn');" title=" 贾特尔 "><font true="">0.91</font></a></span></td><td class="b_rig"><span class="con">大2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118434961&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50052&amp;langx=zh-cn');" title="大"><font true="">1.01</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118434961&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">2.03</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118434971&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50052&amp;langx=zh-cn');" title=" 贾特尔 "><font true="">2.70</font></a></td><td class="b_1stR"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118434971&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50052&amp;strong=H&amp;langx=zh-cn');" title=" 贾特尔 "><font true="">1.28</font></a></span></td><td class="b_1stR"><span class="con">大0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118434971&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50052&amp;langx=zh-cn');" title="大"><font true="">0.75</font></a></span></td></tr><tr id="TR1_01-2350052" style="display: ;"><!--<tr id="TR1_01-2350052" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118434961&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50051&amp;langx=zh-cn');" title=" 拉华尔 "><font true="">3.30</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118434961&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50051&amp;strong=H&amp;langx=zh-cn');" title=" 拉华尔 "><font true="">1.01</font></a></span></td><td class="b_rig"><span class="con">小2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118434961&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50051&amp;langx=zh-cn');" title="小"><font true="">0.86</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118434961&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.87</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118434971&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50051&amp;langx=zh-cn');" title=" 拉华尔 "><font true="">4.45</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118434971&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50051&amp;strong=H&amp;langx=zh-cn');" title=" 拉华尔 "><font true="">0.68</font></a></span></td><td class="b_1stR"><span class="con">小0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118434971&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50051&amp;langx=zh-cn');" title="小"><font true="">1.14</font></a></span></td></tr><tr id="TR2_01-2350052" style="display: ;"><!--<tr id="TR2_01-2350052" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350052_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350052"><div id="01-2350052" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50052','01-23<br>03:00p<br><font color=red>滚球</font>','法国乙组联赛',' 贾特尔 ',' 拉华尔 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118434961&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50051&amp;langx=zh-cn');" title="和"><font true="">3.10</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118434971&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50051&amp;langx=zh-cn');" title="和"><font true="">1.90</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('法国乙组联赛')"><span id="法国乙组联赛" name="法国乙组联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('法国乙组联赛')" class="leg_bar">法国乙组联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350054" style="display: ;"><!--<tr id="TR_01-2350054" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>15:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350054_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 贾特尔 <br>
       拉华尔 </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con">0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118434981&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50054&amp;strong=H&amp;langx=zh-cn');" title=" 贾特尔 "><font true="">1.23</font></a></span></td><td class="b_rig"><span class="con">大2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118434981&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50054&amp;langx=zh-cn');" title="大"><font true="">0.69</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118434981&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con">0</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118434991&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50054&amp;strong=H&amp;langx=zh-cn');" title=" 贾特尔 "><font true="">0.62</font></a></span></td><td class="b_1stR"><span class="con">大1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118434991&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50054&amp;langx=zh-cn');" title="大"><font true="">1.29</font></a></span></td></tr><tr id="TR1_01-2350054" style="display: ;"><!--<tr id="TR1_01-2350054" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118434981&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50053&amp;strong=H&amp;langx=zh-cn');" title=" 拉华尔 "><font true="">0.73</font></a></span></td><td class="b_rig"><span class="con">小2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118434981&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50053&amp;langx=zh-cn');" title="小"><font true="">1.23</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118434981&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118434991&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50053&amp;strong=H&amp;langx=zh-cn');" title=" 拉华尔 "><font true="">1.38</font></a></span></td><td class="b_1stR"><span class="con">小1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118434991&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50053&amp;langx=zh-cn');" title="小"><font true="">0.65</font></a></span></td></tr><tr id="TR2_01-2350054" style="display: ;"><!--<tr id="TR2_01-2350054" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350054_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350054"><div id="01-2350054" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50054','01-23<br>03:00p<br><font color=red>滚球</font>','法国乙组联赛',' 贾特尔 ',' 拉华尔 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('法国乙组联赛')"><span id="法国乙组联赛" name="法国乙组联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('法国乙组联赛')" class="leg_bar">法国乙组联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350056" style="display: ;"><!--<tr id="TR_01-2350056" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>15:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350056_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 迪卓恩 <br>
       奈梅斯 </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118435001&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50056&amp;langx=zh-cn');" title=" 迪卓恩 "><font true="">1.60</font></a></td><td class="b_rig"><span class="con">0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118435001&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50056&amp;strong=H&amp;langx=zh-cn');" title=" 迪卓恩 "><font true="">0.85</font></a></span></td><td class="b_rig"><span class="con">大2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118435001&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50056&amp;langx=zh-cn');" title="大"><font true="">1.01</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118435001&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">2.02</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118435011&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50056&amp;langx=zh-cn');" title=" 迪卓恩 "><font true="">2.33</font></a></td><td class="b_1stR"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118435011&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50056&amp;strong=H&amp;langx=zh-cn');" title=" 迪卓恩 "><font true="">0.80</font></a></span></td><td class="b_1stR"><span class="con">大0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118435011&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50056&amp;langx=zh-cn');" title="大"><font true="">0.79</font></a></span></td></tr><tr id="TR1_01-2350056" style="display: ;"><!--<tr id="TR1_01-2350056" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118435001&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50055&amp;langx=zh-cn');" title=" 奈梅斯 "><font true="">5.20</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118435001&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50055&amp;strong=H&amp;langx=zh-cn');" title=" 奈梅斯 "><font true="">1.07</font></a></span></td><td class="b_rig"><span class="con">小2 / 2.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118435001&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50055&amp;langx=zh-cn');" title="小"><font true="">0.86</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118435001&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.88</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118435011&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50055&amp;langx=zh-cn');" title=" 奈梅斯 "><font true="">5.00</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118435011&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50055&amp;strong=H&amp;langx=zh-cn');" title=" 奈梅斯 "><font true="">1.11</font></a></span></td><td class="b_1stR"><span class="con">小0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118435011&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50055&amp;langx=zh-cn');" title="小"><font true="">1.09</font></a></span></td></tr><tr id="TR2_01-2350056" style="display: ;"><!--<tr id="TR2_01-2350056" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350056_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350056"><div id="01-2350056" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50056','01-23<br>03:00p<br><font color=red>滚球</font>','法国乙组联赛',' 迪卓恩 ',' 奈梅斯 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118435001&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50055&amp;langx=zh-cn');" title="和"><font true="">3.50</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118435011&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50055&amp;langx=zh-cn');" title="和"><font true="">2.05</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('法国乙组联赛')"><span id="法国乙组联赛" name="法国乙组联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('法国乙组联赛')" class="leg_bar">法国乙组联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350058" style="display: ;"><!--<tr id="TR_01-2350058" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>15:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350058_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 迪卓恩 <br>
       奈梅斯 </td><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con">1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118435021&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50058&amp;strong=H&amp;langx=zh-cn');" title=" 迪卓恩 "><font true="">1.21</font></a></span></td><td class="b_rig"><span class="con">大2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118435021&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50058&amp;langx=zh-cn');" title="大"><font true="">0.73</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118435021&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con">0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118435031&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50058&amp;strong=H&amp;langx=zh-cn');" title=" 迪卓恩 "><font true="">1.38</font></a></span></td><td class="b_1stR"><span class="con">大1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118435031&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50058&amp;langx=zh-cn');" title="大"><font true="">1.29</font></a></span></td></tr><tr id="TR1_01-2350058" style="display: ;"><!--<tr id="TR1_01-2350058" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen">&nbsp;</td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118435021&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50057&amp;strong=H&amp;langx=zh-cn');" title=" 奈梅斯 "><font true="">0.74</font></a></span></td><td class="b_rig"><span class="con">小2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118435021&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50057&amp;langx=zh-cn');" title="小"><font true="">1.17</font></a></span></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118435021&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font undefined=""></font></a></td><td class="b_1st">&nbsp;</td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118435031&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50057&amp;strong=H&amp;langx=zh-cn');" title=" 奈梅斯 "><font true="">0.62</font></a></span></td><td class="b_1stR"><span class="con">小1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118435031&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50057&amp;langx=zh-cn');" title="小"><font true="">0.65</font></a></span></td></tr><tr id="TR2_01-2350058" style="display: ;"><!--<tr id="TR2_01-2350058" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350058_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350058"><div id="01-2350058" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50058','01-23<br>03:00p<br><font color=red>滚球</font>','法国乙组联赛',' 迪卓恩 ',' 奈梅斯 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen">&nbsp;</td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st">&nbsp;</td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


	
  <!--SHOW LEGUAGE START--><tr style="display: none;"><td colspan="9" class="b_hline"><table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('法国乙组联赛')"><span id="法国乙组联赛" name="法国乙组联赛" class="showleg"><span id="LegOpen"></span><!--展开联盟-符号--><!--span id="LegOpen"></span--><!--收合联盟-符号--><!--div id="LegClose"></div--></span></td><td onclick="parent.showLeg('法国乙组联赛')" class="leg_bar">法国乙组联赛</td></tr></tbody></table></td></tr><!--SHOW LEGUAGE END--><tr id="TR_01-2350060" style="display: ;"><!--<tr id="TR_01-2350060" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td rowspan="3" class="b_cen">01-23<br>15:00<br><font color="red">滚球</font></td><td rowspan="2" class="team_name none" id="TR_01-2350060_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"> 勒哈费尔 <br>
       尼欧特 </td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118435041&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50060&amp;langx=zh-cn');" title=" 勒哈费尔 "><font true="">2.29</font></a></td><td class="b_rig"><span class="con">0 / 0.5</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118435041&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50060&amp;strong=H&amp;langx=zh-cn');" title=" 勒哈费尔 "><font true="">0.99</font></a></span></td><td class="b_rig"><span class="con">大2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118435041&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50060&amp;langx=zh-cn');" title="大"><font true="">0.86</font></a></span></td><td class="b_cen">单<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118435041&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">2.04</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118435051&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50060&amp;langx=zh-cn');" title=" 勒哈费尔 "><font true="">2.90</font></a></td><td class="b_1stR"><span class="con">0</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118435051&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50060&amp;strong=H&amp;langx=zh-cn');" title=" 勒哈费尔 "><font true="">0.67</font></a></span></td><td class="b_1stR"><span class="con">大0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118435051&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50060&amp;langx=zh-cn');" title="大"><font true="">0.88</font></a></span></td></tr><tr id="TR1_01-2350060" style="display: ;"><!--<tr id="TR1_01-2350060" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118435041&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50059&amp;langx=zh-cn');" title=" 尼欧特 "><font true="">2.95</font></a></td><td class="b_rig"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','R','gid=118435041&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50059&amp;strong=H&amp;langx=zh-cn');" title=" 尼欧特 "><font true="">0.93</font></a></span></td><td class="b_rig"><span class="con">小2</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','OU','gid=118435041&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50059&amp;langx=zh-cn');" title="小"><font true="">1.02</font></a></span></td><td class="b_cen">双<a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','EO','gid=118435041&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.86</font></a></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118435051&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50059&amp;langx=zh-cn');" title=" 尼欧特 "><font true="">3.80</font></a></td><td class="b_1stR"><span class="con"></span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HR','gid=118435051&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50059&amp;strong=H&amp;langx=zh-cn');" title=" 尼欧特 "><font true="">1.29</font></a></span></td><td class="b_1stR"><span class="con">小0.5 / 1</span><span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HOU','gid=118435051&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50059&amp;langx=zh-cn');" title="小"><font true="">1.00</font></a></span></td></tr><tr id="TR2_01-2350060" style="display: ;"><!--<tr id="TR2_01-2350060" onMouseOver='mouseEnter_pointer(this.id);' onMouseOut='mouseOut_pointer(this.id);' style='display: ;'>--><td class="drawn_td" id="TR_01-2350060_1" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);"><table width="99%" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td align="left">和局</td><td class="hot_td"><span id="sp_01-2350060"><div id="01-2350060" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50060','01-23<br>03:00p<br><font color=red>滚球</font>','法国乙组联赛',' 勒哈费尔 ',' 尼欧特 '); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div--></td><td class="b_cen"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','M','gid=118435041&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50059&amp;langx=zh-cn');" title="和"><font true="">3.05</font></a></td><td colspan="3" valign="top" class="b_cen"><span class="more_txt"></span></td><td class="b_1st"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('FT','HM','gid=118435051&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=N&amp;gnum=50059&amp;langx=zh-cn');" title="和"><font true="">1.95</font></a></td><td colspan="3" valign="top" class="b_1st">&nbsp;</td></tr>


          </tbody></table>
  
</div>

							</td>
						</tr>
						<tr>
							<td id="foot"><b>&nbsp;</b></td>
						</tr>
					</tbody>
				</table>
	
	
				<!--下方刷新钮--><div id="refresh_down" class="refresh_M_btn" onclick="this.className='refresh_M_on';javascript:refreshReload('',true)"><span>刷新</span></div>


			</td>
		</tr>
	</tbody>
</table>
	
<div id="SortTable" class="SortTable" name="SortTable" style="position:absolute; display:none;">
	<form id="SortForm" name="SortForm" method="POST" target="saveOrderFrame">
		<input type="hidden" id="SortType" name="SortType" value="">
		<input type="hidden" id="uid" name="uid" value="">
		<input type="hidden" id="langx" name="langx" value="">
		<table>	
			<tbody>
				<tr>
					<td id="SortTypeC" name="SortTypeC" onclick="changeSortType(this,'C');">
							Sort By Compition
						</td>
					</tr>
				<tr>
				<td id="SortTypeT" name="SortTypeT" onclick="changeSortType(this,'T');">
						Sort By Time
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>	
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





<!-- ------------------------------ 盘口选择 ------------------------------ -->

<!-- 盘口选择 -->

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

<!--盘口选择--></body></html>