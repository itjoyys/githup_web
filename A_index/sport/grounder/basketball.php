<html><head><script>
var DateAry = new Array('2015-01-23','2015-01-24','2015-01-25','2015-01-26','2015-01-27','2015-01-28','2015-01-29','2015-01-30','2015-01-31','2015-02-01','2015-02-02');
var rtype = 'all';
var odd_f_str = 'H,E';
var lid_arr=new Array();
top.lid_arr=lid_arr;
top.today_gmt = '2015-01-22';
top.showtype = '';
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
        obj_layer = document.getElementById('LoadLayer');
        obj_layer.style.display = 'none';
    }
    if (parent.retime_flag == 'Y') {
        
        count_down();
    } else {
        var rt = document.getElementById('refreshTime');
        rt.innerHTML = top.refreshTime;
    }
    document.getElementById("odd_f_window").style.display = "none";
    if (sel_gtype == "BU") {

        if (rtype != "pr") {
            if (top.showtype != 'hgft') {
                selgdate(rtype);
            }
        }
        
    }
    if ("" + top.BK_RE_session == "undefined") {
        top.BK_RE_session = "all";
    }
    if (rtype == "r" || rtype == "all" || rtype == "rq4" || rtype == "re") {
        var selRtype = document.getElementById("sel_rtype");
        for (i = 0; i < selRtype.length; i++) {
            if (rtype == "re") {
                if (selRtype.options[i].value == top.BK_RE_session) {
                    selRtype.selectedIndex = i;
                }
            } else {
                if (selRtype.options[i].value == rtype) {
                    selRtype.selectedIndex = i;
                }
            }
        }
    }

}

window.onscroll = scroll;

function scroll() {
    var refresh_right = document.getElementById('refresh_right');
    refresh_right.style.top = document.body.scrollTop + 39;
    
    
    
    
    
    
    
    
}

function setleghi(leghight) {
    var legview = document.getElementById('legFrame');

    if ((leghight * 1) > 95) {
        legview.height = leghight;
    } else {

        legview.height = 95;
    }

}

function reload_var(Level) {
    
    
    
    
    
    
    parent.loading_var = 'Y';
    if (Level == "up") {
        var tmp = "./" + parent.sel_gtype + "_browse/body_var.php";
        if (parent.sel_gtype == "BU") {
            tmp = "./BK_future/body_var.php";
        }
    } else {
        var tmp = "./body_var.php";
    }

    var l_id = eval("parent.parent." + sel_gtype + "_lid_type");
    if (top.showtype == 'hgft' && parent.sel_gtype == "BU") {
        l_id = 3;
    }
    if (parent.rtype == "pr") top.hot_game = "";
    var homepage = tmp + "?uid=" + parent.uid + "&rtype=" + parent.rtype + "&langx=" + parent.langx + "&mtype=" + parent.ltype + "&page_no=" + parent.pg + "&league_id=" + l_id + "&hot_game=" + top.hot_game;
    
    if (parent.sel_gtype == "BU") {

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
    if (top.showtype == 'hgft' && parent.sel_gtype == "BU") {
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
    legview.style.left = 10; 
    
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
        if (rtype == "r" || rtype == "all") {
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
    alert(GetTableID);
    alert(document.getElementById(GetTableID)) 
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
			tmpgmt = tmpgmt.substr(0,5);			
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
        if ((shour * 1) > 0) shour += 12;
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

function chkDelshowLoveI(data2, data) {
    var getGtype = getGtypeShowLoveI();
    var tmpdata = data2.split("<br>")[0] + data;
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
        /*	
		else{
			odd_show+="<option value="+Format[i][0]+tmp_check+">"+Format[i][1]+"</option>";
			}
		*/
        
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

function change_rtype() {
    top.hot_game = "";
    var myOddtype = document.getElementById("sel_rtype");
    rtype = myOddtype.options[myOddtype.selectedIndex].value;
    parent.location.href = "index.php?uid=" + top.uid + "&langx=" + top.langx + "&mtype=" + parent.ltype + "&rtype=" + rtype;

}
function change_RE_session() {
    top.hot_game = "";
    var myOddtype = document.getElementById("sel_rtype");
    rtype = myOddtype.options[myOddtype.selectedIndex].value;
    top.BK_RE_session = rtype;
    reload_var("");
    
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
</script>



<title>body_basketball_all</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/cl/tpl/commonFile/style/member/mem_body_olympics.css?=29" type="text/css">
</head>

<body id="MBU" class="bodyset BUR" onload="onLoadset();">
<div id="LoadLayer" style="display: none;">loading...............................................................................</div>
<div id="showtableData" style="display:none;">
  <xmp>

          <table id="game_table"  cellspacing="0" cellpadding="0" class="game">
            <tr>
              <th class="time">时间</th>
              <th class="team">赛事</th>
              <th class="h_m">独赢盘</th>
              <th class="h_r">让分</th>
              <th class="h_ou">大小</th>
              <th class="h_oe">单/双</th>
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
      <span id="*LEG*" class="showleg">
      	*LegMark*
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onClick="parent.showLeg('*LEG*')" class="leg_bar">*LEG*</td></tr></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_*ID_STR*" *TR_EVENT* *CLASS*>
    <td rowspan="2" class="b_cen">*DATETIME*</td>
    <td rowspan="2" class="team_name">*TEAM_H*<br>
      *TEAM_C*
       *MYLOVE*<!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">*RATIO_MH*</td>
    <td class="b_rig"><span class="con">*CON_RH*</span> <span class="ratio">*RATIO_RH*</span></td>
    <td class="b_rig"><span class="con">*CON_OUH*</span> <span class="ratio">*RATIO_OUH*</span></td>
    <td class="b_cen">*RATIO_EOO*</td>
  </tr>
  <tr id="TR1_*ID_STR*" *TR_EVENT* *CLASS*>
  	<td class="b_cen">*RATIO_MC*</td>
    <td class="b_rig"><span class="con">*CON_RC*</span> <span class="ratio">*RATIO_RC*</span></td>
    <td class="b_rig"><span class="con">*CON_OUC*</span> <span class="ratio">*RATIO_OUC*</span></td>
    <td class="b_cen">*RATIO_EOE*</td>
  </tr>

</xmp>
</div>
<!--右方刷新钮--><div id="refresh_right" style="position: absolute; top: 39px; left: 685px;" class="refresh_M_btn" onclick="this.className='refresh_M_on';javascript:reload_var()"><span>刷新</span></div>
<div id="NoDataTR" style="display:none;">
	<xmp>
	    <td colspan="20" class="no_game">您选择的项目暂时没有赛事。请修改您的选项或迟些再返回。</td>
	 </xmp>
</div>


<table border="0" cellpadding="0" cellspacing="0" id="myTable"><tbody><tr><td>
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
				<h1>
					<em>早餐篮球和美式足球 : </em>&nbsp;
					<select id="sel_rtype" onchange="change_rtype();">
						<option value="all" selected="">所有时节</option>
						<option value="r">单式</option>
						<option value="rq4">单节</option>
					</select>
				<!--span id="hr_info">秒自动更新</span-->
				</h1>
			</td>
		</tr>
		<tr>
        <td class="mem">
			<h2>
				<table width="100%" border="0" cellpadding="0" cellspacing="0" id="fav_bar">
					<tbody>
						<tr>
							<td id="page_no">
								时间:<span id="show_date_opt"><select id="g_date" name="g_date" onchange="chg_gdate()"><option value="ALL">全部</option><option value="1">特早</option><option value="2015-01-23">1月23日</option><option value="2015-01-24">1月24日</option><option value="2015-01-25">1月25日</option><option value="2015-01-26">1月26日</option><option value="2015-01-27">1月27日</option><option value="2015-01-28">1月28日</option><option value="2015-01-29">1月29日</option><option value="2015-01-30">1月30日</option><option value="2015-01-31">1月31日</option><option value="2015-02-01">2月1日</option><option value="2015-02-02">2月2日</option></select></span><span id="pg_txt"></span>              
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
											<td class="refresh_btn" id="refresh_btn" onclick="this.className='refresh_on';"><!--秒数更新--><div onclick="javascript:reload_var()"><font id="refreshTime">167</font></div></td>
											<td class="leg_btn"><div onclick="javascript:chg_league();" id="sel_league">选择联赛 (<span id="str_num">全部</span>)</div></td>
											<td id="SortGame" class="SortGame" name="SortGame">
											<select id="SortSel" onchange="saveSortType();">
													<option value="C">按聯盟排序</option>
													<option value="T">按時間排序</option>
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
              <th class="time">时间</th>
              <th class="team">赛事</th>
              <th class="h_m">独赢盘</th>
              <th class="h_r">让分</th>
              <th class="h_ou">大小</th>
              <th class="h_oe">单/双</th>
            </tr>
            
	
  <!--SHOW LEGUAGE START-->
  <tr style="display: ;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('美国大学篮球')">
      <span id="美国大学篮球" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('美国大学篮球')" class="leg_bar">美国大学篮球</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350332" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>00:01</td>
    <td rowspan="2" class="team_name"> 贡萨格<br>
       圣玛丽学院盖尔
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350332"><div id="01-2350332" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50332','01-23<br>00:01a','美国大学篮球',' 贡萨格',' 圣玛丽学院盖尔'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">15</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115086211&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50332&amp;strong=H&amp;langx=zh-cn');" title=" 贡萨格"><font true="">0.91</font></a></span></td>
    <td class="b_rig"><span class="con">大136.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115086211&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50332&amp;langx=zh-cn');" title="大"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">单&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115086211&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.94</font></a></td>
  </tr>
  <tr id="TR1_01-2350332" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115086211&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50331&amp;strong=H&amp;langx=zh-cn');" title=" 圣玛丽学院盖尔"><font true="">0.91</font></a></span></td>
    <td class="b_rig"><span class="con">小136.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115086211&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50331&amp;langx=zh-cn');" title="小"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">双&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115086211&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.94</font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: none;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('美国大学篮球')">
      <span id="美国大学篮球" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('美国大学篮球')" class="leg_bar">美国大学篮球</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350334" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>00:01</td>
    <td rowspan="2" class="team_name"> 圣塔克拉拉<br>
       玛利曼罗耀拉
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350334"><div id="01-2350334" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50334','01-23<br>00:01a','美国大学篮球',' 圣塔克拉拉',' 玛利曼罗耀拉'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">4.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115086281&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50334&amp;strong=H&amp;langx=zh-cn');" title=" 圣塔克拉拉"><font true="">0.86</font></a></span></td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115086281&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50334&amp;langx=zh-cn');" title="大"><font undefined=""></font></a></span></td>
    <td class="b_cen"><span class="con_oe">单&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115086281&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.94</font></a></td>
  </tr>
  <tr id="TR1_01-2350334" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115086281&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50333&amp;strong=H&amp;langx=zh-cn');" title=" 玛利曼罗耀拉"><font true="">0.96</font></a></span></td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115086281&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50333&amp;langx=zh-cn');" title="小"><font undefined=""></font></a></span></td>
    <td class="b_cen"><span class="con_oe">双&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115086281&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.94</font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: none;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('美国大学篮球')">
      <span id="美国大学篮球" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('美国大学篮球')" class="leg_bar">美国大学篮球</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350336" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>00:01</td>
    <td rowspan="2" class="team_name"> 加州<br>
       亚利桑那州立
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350336"><div id="01-2350336" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50336','01-23<br>00:01a','美国大学篮球',' 加州',' 亚利桑那州立'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">2</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115086351&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50336&amp;strong=H&amp;langx=zh-cn');" title=" 加州"><font true="">0.83</font></a></span></td>
    <td class="b_rig"><span class="con">大127.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115086351&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50336&amp;langx=zh-cn');" title="大"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">单&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115086351&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.94</font></a></td>
  </tr>
  <tr id="TR1_01-2350336" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115086351&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50335&amp;strong=H&amp;langx=zh-cn');" title=" 亚利桑那州立"><font true="">0.99</font></a></span></td>
    <td class="b_rig"><span class="con">小127.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115086351&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50335&amp;langx=zh-cn');" title="小"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">双&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115086351&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.94</font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: none;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('美国大学篮球')">
      <span id="美国大学篮球" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('美国大学篮球')" class="leg_bar">美国大学篮球</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350338" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>00:01</td>
    <td rowspan="2" class="team_name"> 俄勒冈<br>
       南加州大学
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350338"><div id="01-2350338" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50338','01-23<br>00:01a','美国大学篮球',' 俄勒冈',' 南加州大学'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">9.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115086421&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50338&amp;strong=H&amp;langx=zh-cn');" title=" 俄勒冈"><font true="">0.95</font></a></span></td>
    <td class="b_rig"><span class="con">大148.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115086421&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50338&amp;langx=zh-cn');" title="大"><font true="">0.86</font></a></span></td>
    <td class="b_cen"><span class="con_oe">单&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115086421&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.94</font></a></td>
  </tr>
  <tr id="TR1_01-2350338" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115086421&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50337&amp;strong=H&amp;langx=zh-cn');" title=" 南加州大学"><font true="">0.87</font></a></span></td>
    <td class="b_rig"><span class="con">小148.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115086421&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50337&amp;langx=zh-cn');" title="小"><font true="">0.92</font></a></span></td>
    <td class="b_cen"><span class="con_oe">双&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115086421&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.94</font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: none;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('美国大学篮球')">
      <span id="美国大学篮球" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('美国大学篮球')" class="leg_bar">美国大学篮球</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350340" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>01:00</td>
    <td rowspan="2" class="team_name"> 夏威夷<br>
       加州大学戴维斯
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350340"><div id="01-2350340" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50340','01-23<br>01:00a','美国大学篮球',' 夏威夷',' 加州大学戴维斯'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">3.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115086491&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50340&amp;strong=H&amp;langx=zh-cn');" title=" 夏威夷"><font true="">0.87</font></a></span></td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115086491&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50340&amp;langx=zh-cn');" title="大"><font undefined=""></font></a></span></td>
    <td class="b_cen"><span class="con_oe">单&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115086491&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.94</font></a></td>
  </tr>
  <tr id="TR1_01-2350340" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115086491&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50339&amp;strong=H&amp;langx=zh-cn');" title=" 加州大学戴维斯"><font true="">0.95</font></a></span></td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115086491&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50339&amp;langx=zh-cn');" title="小"><font undefined=""></font></a></span></td>
    <td class="b_cen"><span class="con_oe">双&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115086491&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.94</font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: ;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('欧洲篮球联赛')">
      <span id="欧洲篮球联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('欧洲篮球联赛')" class="leg_bar">欧洲篮球联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350024" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>12:30</td>
    <td rowspan="2" class="team_name"> 诺夫哥罗德<br>
       艾菲斯
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350024"><div id="01-2350024" class="fov_icon_out" style="cursor: pointer; display: none;" title="我的最愛" onclick="addShowLoveI('50024','01-23<br>12:30p','欧洲篮球联赛',' 诺夫哥罗德',' 艾菲斯'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115053591&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50024&amp;strong=C&amp;langx=zh-cn');" title=" 诺夫哥罗德"><font true="">0.91</font></a></span></td>
    <td class="b_rig"><span class="con">大146</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115053591&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50024&amp;langx=zh-cn');" title="大"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">单&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115053591&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.94</font></a></td>
  </tr>
  <tr id="TR1_01-2350024" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">3</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115053591&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50023&amp;strong=C&amp;langx=zh-cn');" title=" 艾菲斯"><font true="">0.91</font></a></span></td>
    <td class="b_rig"><span class="con">小146</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115053591&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50023&amp;langx=zh-cn');" title="小"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">双&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115053591&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.94</font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: none;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('欧洲篮球联赛')">
      <span id="欧洲篮球联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('欧洲篮球联赛')" class="leg_bar">欧洲篮球联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350026" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>12:30</td>
    <td rowspan="2" class="team_name"> 诺夫哥罗德<br>
       艾菲斯
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350026"><div id="01-2350026" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50026','01-23<br>12:30p','欧洲篮球联赛',' 诺夫哥罗德',' 艾菲斯'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115053661&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50026&amp;strong=C&amp;langx=zh-cn');" title=" 诺夫哥罗德"><font true="">1.04</font></a></span></td>
    <td class="b_rig"><span class="con">大145</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115053661&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50026&amp;langx=zh-cn');" title="大"><font true="">0.81</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115053661&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>
  <tr id="TR1_01-2350026" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">2</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115053661&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50025&amp;strong=C&amp;langx=zh-cn');" title=" 艾菲斯"><font true="">0.78</font></a></span></td>
    <td class="b_rig"><span class="con">小145</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115053661&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50025&amp;langx=zh-cn');" title="小"><font true="">0.97</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115053661&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: none;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('欧洲篮球联赛')">
      <span id="欧洲篮球联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('欧洲篮球联赛')" class="leg_bar">欧洲篮球联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350028" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>12:30</td>
    <td rowspan="2" class="team_name"> 诺夫哥罗德<br>
       艾菲斯
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350028"><div id="01-2350028" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50028','01-23<br>12:30p','欧洲篮球联赛',' 诺夫哥罗德',' 艾菲斯'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115053731&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50028&amp;strong=C&amp;langx=zh-cn');" title=" 诺夫哥罗德"><font true="">0.77</font></a></span></td>
    <td class="b_rig"><span class="con">大147</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115053731&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50028&amp;langx=zh-cn');" title="大"><font true="">0.97</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115053731&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>
  <tr id="TR1_01-2350028" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">4</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115053731&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50027&amp;strong=C&amp;langx=zh-cn');" title=" 艾菲斯"><font true="">1.05</font></a></span></td>
    <td class="b_rig"><span class="con">小147</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115053731&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50027&amp;langx=zh-cn');" title="小"><font true="">0.81</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115053731&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: ;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('芬兰篮球联赛')">
      <span id="芬兰篮球联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('芬兰篮球联赛')" class="leg_bar">芬兰篮球联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350228" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>12:30</td>
    <td rowspan="2" class="team_name"> KTP篮球会<br>
       萨罗维尔帕斯
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350228"><div id="01-2350228" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50228','01-23<br>12:30p','芬兰篮球联赛',' KTP篮球会',' 萨罗维尔帕斯'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">16</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115075221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50228&amp;strong=H&amp;langx=zh-cn');" title=" KTP篮球会"><font true="">0.83</font></a></span></td>
    <td class="b_rig"><span class="con">大156</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115075221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50228&amp;langx=zh-cn');" title="大"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">单&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115075221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.94</font></a></td>
  </tr>
  <tr id="TR1_01-2350228" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115075221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50227&amp;strong=H&amp;langx=zh-cn');" title=" 萨罗维尔帕斯"><font true="">0.99</font></a></span></td>
    <td class="b_rig"><span class="con">小156</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115075221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50227&amp;langx=zh-cn');" title="小"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">双&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115075221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.94</font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: none;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('芬兰篮球联赛')">
      <span id="芬兰篮球联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('芬兰篮球联赛')" class="leg_bar">芬兰篮球联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350230" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>12:30</td>
    <td rowspan="2" class="team_name"> 科沃特<br>
       洛马亚野牛
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350230"><div id="01-2350230" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50230','01-23<br>12:30p','芬兰篮球联赛',' 科沃特',' 洛马亚野牛'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">2.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115075291&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50230&amp;strong=H&amp;langx=zh-cn');" title=" 科沃特"><font true="">0.91</font></a></span></td>
    <td class="b_rig"><span class="con">大160</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115075291&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50230&amp;langx=zh-cn');" title="大"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">单&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115075291&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.94</font></a></td>
  </tr>
  <tr id="TR1_01-2350230" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115075291&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50229&amp;strong=H&amp;langx=zh-cn');" title=" 洛马亚野牛"><font true="">0.91</font></a></span></td>
    <td class="b_rig"><span class="con">小160</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115075291&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50229&amp;langx=zh-cn');" title="小"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">双&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115075291&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.94</font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: ;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('捷克篮球甲级联赛')">
      <span id="捷克篮球甲级联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('捷克篮球甲级联赛')" class="leg_bar">捷克篮球甲级联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350214" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>13:00</td>
    <td rowspan="2" class="team_name"> CEZ宁布尔克<br>
       斯维塔维
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350214"><div id="01-2350214" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50214','01-23<br>01:00p','捷克篮球甲级联赛',' CEZ宁布尔克',' 斯维塔维'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">27</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115074731&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50214&amp;strong=H&amp;langx=zh-cn');" title=" CEZ宁布尔克"><font true="">0.86</font></a></span></td>
    <td class="b_rig"><span class="con">大159</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115074731&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50214&amp;langx=zh-cn');" title="大"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">单&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115074731&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.94</font></a></td>
  </tr>
  <tr id="TR1_01-2350214" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115074731&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50213&amp;strong=H&amp;langx=zh-cn');" title=" 斯维塔维"><font true="">0.96</font></a></span></td>
    <td class="b_rig"><span class="con">小159</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115074731&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50213&amp;langx=zh-cn');" title="小"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">双&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115074731&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.94</font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: ;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('欧洲篮球联赛')">
      <span id="欧洲篮球联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('欧洲篮球联赛')" class="leg_bar">欧洲篮球联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350030" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>13:45</td>
    <td rowspan="2" class="team_name"> 萨拉基利斯<br>
       特拉维夫马卡比
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350030"><div id="01-2350030" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50030','01-23<br>01:45p','欧洲篮球联赛',' 萨拉基利斯',' 特拉维夫马卡比'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115053801&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50030&amp;strong=C&amp;langx=zh-cn');" title=" 萨拉基利斯"><font true="">0.93</font></a></span></td>
    <td class="b_rig"><span class="con">大149</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115053801&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50030&amp;langx=zh-cn');" title="大"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">单&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115053801&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.94</font></a></td>
  </tr>
  <tr id="TR1_01-2350030" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">3.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115053801&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50029&amp;strong=C&amp;langx=zh-cn');" title=" 特拉维夫马卡比"><font true="">0.89</font></a></span></td>
    <td class="b_rig"><span class="con">小149</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115053801&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50029&amp;langx=zh-cn');" title="小"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">双&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115053801&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.94</font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: none;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('欧洲篮球联赛')">
      <span id="欧洲篮球联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('欧洲篮球联赛')" class="leg_bar">欧洲篮球联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350032" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>13:45</td>
    <td rowspan="2" class="team_name"> 萨拉基利斯<br>
       特拉维夫马卡比
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350032"><div id="01-2350032" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50032','01-23<br>01:45p','欧洲篮球联赛',' 萨拉基利斯',' 特拉维夫马卡比'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115053871&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50032&amp;strong=C&amp;langx=zh-cn');" title=" 萨拉基利斯"><font true="">1.05</font></a></span></td>
    <td class="b_rig"><span class="con">大148</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115053871&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50032&amp;langx=zh-cn');" title="大"><font true="">0.81</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115053871&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>
  <tr id="TR1_01-2350032" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">2.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115053871&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50031&amp;strong=C&amp;langx=zh-cn');" title=" 特拉维夫马卡比"><font true="">0.77</font></a></span></td>
    <td class="b_rig"><span class="con">小148</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115053871&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50031&amp;langx=zh-cn');" title="小"><font true="">0.97</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115053871&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: none;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('欧洲篮球联赛')">
      <span id="欧洲篮球联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('欧洲篮球联赛')" class="leg_bar">欧洲篮球联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350034" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>13:45</td>
    <td rowspan="2" class="team_name"> 萨拉基利斯<br>
       特拉维夫马卡比
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350034"><div id="01-2350034" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50034','01-23<br>01:45p','欧洲篮球联赛',' 萨拉基利斯',' 特拉维夫马卡比'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115053941&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50034&amp;strong=C&amp;langx=zh-cn');" title=" 萨拉基利斯"><font true="">0.80</font></a></span></td>
    <td class="b_rig"><span class="con">大150</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115053941&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50034&amp;langx=zh-cn');" title="大"><font true="">0.97</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115053941&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>
  <tr id="TR1_01-2350034" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">4.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115053941&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50033&amp;strong=C&amp;langx=zh-cn');" title=" 特拉维夫马卡比"><font true="">1.02</font></a></span></td>
    <td class="b_rig"><span class="con">小150</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115053941&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50033&amp;langx=zh-cn');" title="小"><font true="">0.81</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115053941&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: ;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('波兰篮球联赛')">
      <span id="波兰篮球联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('波兰篮球联赛')" class="leg_bar">波兰篮球联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350298" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>14:00</td>
    <td rowspan="2" class="team_name"> 斯拉斯克<br>
       特里弗索波特
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350298"><div id="01-2350298" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50298','01-23<br>02:00p','波兰篮球联赛',' 斯拉斯克',' 特里弗索波特'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">6.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115077671&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50298&amp;strong=H&amp;langx=zh-cn');" title=" 斯拉斯克"><font true="">0.91</font></a></span></td>
    <td class="b_rig"><span class="con">大164</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115077671&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50298&amp;langx=zh-cn');" title="大"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">单&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115077671&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.94</font></a></td>
  </tr>
  <tr id="TR1_01-2350298" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115077671&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50297&amp;strong=H&amp;langx=zh-cn');" title=" 特里弗索波特"><font true="">0.91</font></a></span></td>
    <td class="b_rig"><span class="con">小164</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115077671&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50297&amp;langx=zh-cn');" title="小"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">双&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115077671&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.94</font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: ;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('德国篮球联赛')">
      <span id="德国篮球联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('德国篮球联赛')" class="leg_bar">德国篮球联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350260" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>15:00</td>
    <td rowspan="2" class="team_name"> 哈根凤凰<br>
       乌尔姆兰蒂奥帕姆
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350260"><div id="01-2350260" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50260','01-23<br>03:00p','德国篮球联赛',' 哈根凤凰',' 乌尔姆兰蒂奥帕姆'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115076341&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50260&amp;strong=C&amp;langx=zh-cn');" title=" 哈根凤凰"><font true="">0.95</font></a></span></td>
    <td class="b_rig"><span class="con">大175.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115076341&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50260&amp;langx=zh-cn');" title="大"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">单&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115076341&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.94</font></a></td>
  </tr>
  <tr id="TR1_01-2350260" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">2</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115076341&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50259&amp;strong=C&amp;langx=zh-cn');" title=" 乌尔姆兰蒂奥帕姆"><font true="">0.87</font></a></span></td>
    <td class="b_rig"><span class="con">小175.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115076341&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50259&amp;langx=zh-cn');" title="小"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">双&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115076341&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.94</font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: none;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('德国篮球联赛')">
      <span id="德国篮球联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('德国篮球联赛')" class="leg_bar">德国篮球联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350262" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>15:00</td>
    <td rowspan="2" class="team_name"> 哈根凤凰<br>
       乌尔姆兰蒂奥帕姆
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350262"><div id="01-2350262" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50262','01-23<br>03:00p','德国篮球联赛',' 哈根凤凰',' 乌尔姆兰蒂奥帕姆'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115076411&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50262&amp;strong=C&amp;langx=zh-cn');" title=" 哈根凤凰"><font true="">1.06</font></a></span></td>
    <td class="b_rig"><span class="con">大174.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115076411&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50262&amp;langx=zh-cn');" title="大"><font true="">0.78</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115076411&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>
  <tr id="TR1_01-2350262" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">1</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115076411&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50261&amp;strong=C&amp;langx=zh-cn');" title=" 乌尔姆兰蒂奥帕姆"><font true="">0.76</font></a></span></td>
    <td class="b_rig"><span class="con">小174.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115076411&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50261&amp;langx=zh-cn');" title="小"><font true="">1.00</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115076411&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: none;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('德国篮球联赛')">
      <span id="德国篮球联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('德国篮球联赛')" class="leg_bar">德国篮球联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350264" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>15:00</td>
    <td rowspan="2" class="team_name"> 哈根凤凰<br>
       乌尔姆兰蒂奥帕姆
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350264"><div id="01-2350264" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50264','01-23<br>03:00p','德国篮球联赛',' 哈根凤凰',' 乌尔姆兰蒂奥帕姆'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115076481&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50264&amp;strong=C&amp;langx=zh-cn');" title=" 哈根凤凰"><font true="">0.83</font></a></span></td>
    <td class="b_rig"><span class="con">大176.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115076481&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50264&amp;langx=zh-cn');" title="大"><font true="">1.00</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115076481&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>
  <tr id="TR1_01-2350264" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">3</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115076481&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50263&amp;strong=C&amp;langx=zh-cn');" title=" 乌尔姆兰蒂奥帕姆"><font true="">0.99</font></a></span></td>
    <td class="b_rig"><span class="con">小176.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115076481&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50263&amp;langx=zh-cn');" title="小"><font true="">0.78</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115076481&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: ;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('欧洲篮球联赛')">
      <span id="欧洲篮球联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('欧洲篮球联赛')" class="leg_bar">欧洲篮球联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350036" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>15:30</td>
    <td rowspan="2" class="team_name"> 奥林比亚高斯<br>
       沙斯基贝斯克尼亚
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350036"><div id="01-2350036" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50036','01-23<br>03:30p','欧洲篮球联赛',' 奥林比亚高斯',' 沙斯基贝斯克尼亚'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">11</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115054011&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50036&amp;strong=H&amp;langx=zh-cn');" title=" 奥林比亚高斯"><font true="">0.89</font></a></span></td>
    <td class="b_rig"><span class="con">大153</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115054011&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50036&amp;langx=zh-cn');" title="大"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">单&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115054011&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.94</font></a></td>
  </tr>
  <tr id="TR1_01-2350036" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115054011&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50035&amp;strong=H&amp;langx=zh-cn');" title=" 沙斯基贝斯克尼亚"><font true="">0.93</font></a></span></td>
    <td class="b_rig"><span class="con">小153</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115054011&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50035&amp;langx=zh-cn');" title="小"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">双&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115054011&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.94</font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: none;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('欧洲篮球联赛')">
      <span id="欧洲篮球联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('欧洲篮球联赛')" class="leg_bar">欧洲篮球联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350038" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>15:30</td>
    <td rowspan="2" class="team_name"> 奥林比亚高斯<br>
       沙斯基贝斯克尼亚
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350038"><div id="01-2350038" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50038','01-23<br>03:30p','欧洲篮球联赛',' 奥林比亚高斯',' 沙斯基贝斯克尼亚'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">12</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115054081&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50038&amp;strong=H&amp;langx=zh-cn');" title=" 奥林比亚高斯"><font true="">1.01</font></a></span></td>
    <td class="b_rig"><span class="con">大152</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115054081&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50038&amp;langx=zh-cn');" title="大"><font true="">0.81</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115054081&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>
  <tr id="TR1_01-2350038" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115054081&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50037&amp;strong=H&amp;langx=zh-cn');" title=" 沙斯基贝斯克尼亚"><font true="">0.81</font></a></span></td>
    <td class="b_rig"><span class="con">小152</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115054081&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50037&amp;langx=zh-cn');" title="小"><font true="">0.97</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115054081&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: none;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('欧洲篮球联赛')">
      <span id="欧洲篮球联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('欧洲篮球联赛')" class="leg_bar">欧洲篮球联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350040" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>15:30</td>
    <td rowspan="2" class="team_name"> 奥林比亚高斯<br>
       沙斯基贝斯克尼亚
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350040"><div id="01-2350040" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50040','01-23<br>03:30p','欧洲篮球联赛',' 奥林比亚高斯',' 沙斯基贝斯克尼亚'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">10</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115054151&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50040&amp;strong=H&amp;langx=zh-cn');" title=" 奥林比亚高斯"><font true="">0.76</font></a></span></td>
    <td class="b_rig"><span class="con">大154</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115054151&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50040&amp;langx=zh-cn');" title="大"><font true="">0.97</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115054151&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>
  <tr id="TR1_01-2350040" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115054151&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50039&amp;strong=H&amp;langx=zh-cn');" title=" 沙斯基贝斯克尼亚"><font true="">1.06</font></a></span></td>
    <td class="b_rig"><span class="con">小154</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115054151&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50039&amp;langx=zh-cn');" title="小"><font true="">0.81</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115054151&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: ;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('法国篮球甲级联赛')">
      <span id="法国篮球甲级联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('法国篮球甲级联赛')" class="leg_bar">法国篮球甲级联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350244" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>15:30</td>
    <td rowspan="2" class="team_name"> 巴黎勒瓦卢瓦<br>
       第戎
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350244"><div id="01-2350244" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50244','01-23<br>03:30p','法国篮球甲级联赛',' 巴黎勒瓦卢瓦',' 第戎'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">3.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115075781&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50244&amp;strong=H&amp;langx=zh-cn');" title=" 巴黎勒瓦卢瓦"><font true="">0.95</font></a></span></td>
    <td class="b_rig"><span class="con">大151</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115075781&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50244&amp;langx=zh-cn');" title="大"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">单&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115075781&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.94</font></a></td>
  </tr>
  <tr id="TR1_01-2350244" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115075781&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50243&amp;strong=H&amp;langx=zh-cn');" title=" 第戎"><font true="">0.87</font></a></span></td>
    <td class="b_rig"><span class="con">小151</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115075781&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50243&amp;langx=zh-cn');" title="小"><font true="">0.89</font></a></span></td>
    <td class="b_cen"><span class="con_oe">双&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115075781&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.94</font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: none;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('法国篮球甲级联赛')">
      <span id="法国篮球甲级联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('法国篮球甲级联赛')" class="leg_bar">法国篮球甲级联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350246" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>15:30</td>
    <td rowspan="2" class="team_name"> 巴黎勒瓦卢瓦<br>
       第戎
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350246"><div id="01-2350246" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50246','01-23<br>03:30p','法国篮球甲级联赛',' 巴黎勒瓦卢瓦',' 第戎'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">4.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115075851&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50246&amp;strong=H&amp;langx=zh-cn');" title=" 巴黎勒瓦卢瓦"><font true="">1.07</font></a></span></td>
    <td class="b_rig"><span class="con">大150</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115075851&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50246&amp;langx=zh-cn');" title="大"><font true="">0.81</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115075851&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>
  <tr id="TR1_01-2350246" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115075851&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50245&amp;strong=H&amp;langx=zh-cn');" title=" 第戎"><font true="">0.75</font></a></span></td>
    <td class="b_rig"><span class="con">小150</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115075851&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50245&amp;langx=zh-cn');" title="小"><font true="">0.97</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115075851&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: none;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('法国篮球甲级联赛')">
      <span id="法国篮球甲级联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('法国篮球甲级联赛')" class="leg_bar">法国篮球甲级联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350248" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>15:30</td>
    <td rowspan="2" class="team_name"> 巴黎勒瓦卢瓦<br>
       第戎
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350248"><div id="01-2350248" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50248','01-23<br>03:30p','法国篮球甲级联赛',' 巴黎勒瓦卢瓦',' 第戎'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">2.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115075921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50248&amp;strong=H&amp;langx=zh-cn');" title=" 巴黎勒瓦卢瓦"><font true="">0.75</font></a></span></td>
    <td class="b_rig"><span class="con">大152</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115075921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50248&amp;langx=zh-cn');" title="大"><font true="">0.97</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115075921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>
  <tr id="TR1_01-2350248" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115075921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50247&amp;strong=H&amp;langx=zh-cn');" title=" 第戎"><font true="">1.07</font></a></span></td>
    <td class="b_rig"><span class="con">小152</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115075921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50247&amp;langx=zh-cn');" title="小"><font true="">0.81</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115075921&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: ;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('欧洲篮球联赛')">
      <span id="欧洲篮球联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('欧洲篮球联赛')" class="leg_bar">欧洲篮球联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350042" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>15:45</td>
    <td rowspan="2" class="team_name"> 巴塞罗那<br>
       贝尔格莱德红星
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350042"><div id="01-2350042" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50042','01-23<br>03:45p','欧洲篮球联赛',' 巴塞罗那',' 贝尔格莱德红星'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">10.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115054221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50042&amp;strong=H&amp;langx=zh-cn');" title=" 巴塞罗那"><font true="">0.91</font></a></span></td>
    <td class="b_rig"><span class="con">大150</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115054221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50042&amp;langx=zh-cn');" title="大"><font true="">0.91</font></a></span></td>
    <td class="b_cen"><span class="con_oe">单&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115054221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title="单"><font true="">1.94</font></a></td>
  </tr>
  <tr id="TR1_01-2350042" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115054221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50041&amp;strong=H&amp;langx=zh-cn');" title=" 贝尔格莱德红星"><font true="">0.91</font></a></span></td>
    <td class="b_rig"><span class="con">小150</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115054221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50041&amp;langx=zh-cn');" title="小"><font true="">0.87</font></a></span></td>
    <td class="b_cen"><span class="con_oe">双&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115054221&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title="双"><font true="">1.94</font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: none;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('欧洲篮球联赛')">
      <span id="欧洲篮球联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('欧洲篮球联赛')" class="leg_bar">欧洲篮球联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350044" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>15:45</td>
    <td rowspan="2" class="team_name"> 巴塞罗那<br>
       贝尔格莱德红星
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350044"><div id="01-2350044" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50044','01-23<br>03:45p','欧洲篮球联赛',' 巴塞罗那',' 贝尔格莱德红星'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">11.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115054291&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50044&amp;strong=H&amp;langx=zh-cn');" title=" 巴塞罗那"><font true="">1.04</font></a></span></td>
    <td class="b_rig"><span class="con">大149</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115054291&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50044&amp;langx=zh-cn');" title="大"><font true="">0.83</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115054291&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>
  <tr id="TR1_01-2350044" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115054291&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50043&amp;strong=H&amp;langx=zh-cn');" title=" 贝尔格莱德红星"><font true="">0.78</font></a></span></td>
    <td class="b_rig"><span class="con">小149</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115054291&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50043&amp;langx=zh-cn');" title="小"><font true="">0.95</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115054291&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: none;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('欧洲篮球联赛')">
      <span id="欧洲篮球联赛" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('欧洲篮球联赛')" class="leg_bar">欧洲篮球联赛</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_01-2350046" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">01-23<br>15:45</td>
    <td rowspan="2" class="team_name"> 巴塞罗那<br>
       贝尔格莱德红星
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_01-2350046"><div id="01-2350046" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('50046','01-23<br>03:45p','欧洲篮球联赛',' 巴塞罗那',' 贝尔格莱德红星'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">9.5</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115054361&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50046&amp;strong=H&amp;langx=zh-cn');" title=" 巴塞罗那"><font true="">0.78</font></a></span></td>
    <td class="b_rig"><span class="con">大151</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115054361&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=50046&amp;langx=zh-cn');" title="大"><font true="">1.01</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115054361&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>
  <tr id="TR1_01-2350046" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115054361&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50045&amp;strong=H&amp;langx=zh-cn');" title=" 贝尔格莱德红星"><font true="">1.04</font></a></span></td>
    <td class="b_rig"><span class="con">小151</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115054361&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=50045&amp;langx=zh-cn');" title="小"><font true="">0.77</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115054361&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>



	
  <!--SHOW LEGUAGE START-->
  <tr style="display: ;">
    <td colspan="8" class="b_hline">
    	<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="parent.showLeg('美国职业美式足球超级碗')">
      <span id="美国职业美式足球超级碗" class="showleg">
      	<span id="LegOpen"></span>
       <!--展开联盟-符号--><!--span id="LegOpen"></span-->
       <!--收合联盟-符号--><!--div id="LegClose"></div-->
      </span>
        </td><td onclick="parent.showLeg('美国职业美式足球超级碗')" class="leg_bar">美国职业美式足球超级碗</td></tr></tbody></table>
      </td>
  </tr>
  <!--SHOW LEGUAGE END-->
  <tr id="TR_02-0170006" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
    <td rowspan="2" class="b_cen">02-01<br>19:30</td>
    <td rowspan="2" class="team_name"> 西雅图海鹰 <font color="#005aff">[中]</font><br>
       新英格兰爱国者
       <table border="0" cellpadding="0" cellspacing="0" class="fav_tab"><tbody><tr><td class="hot_star"><span id="sp_02-0170006"><div id="02-0170006" class="fov_icon_out" style="cursor:hand;display:none;" title="我的最愛" onclick="addShowLoveI('70006','02-01<br>07:30p','美国职业美式足球超级碗',' 西雅图海鹰 [中]',' 新英格兰爱国者'); "></div></span></td></tr></tbody></table><!--星星符号--><!--div class="fov_icon_on"></div--><!--星星符号-灰色--><!--div class="fov_icon_out"></div-->
      </td>
    <td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con"></span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115074591&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=70006&amp;strong=C&amp;langx=zh-cn');" title=" 西雅图海鹰 [中]"><font true="">0.97</font></a></span></td>
    <td class="b_rig"><span class="con">大48</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115074591&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=H&amp;gnum=70006&amp;langx=zh-cn');" title="大"><font true="">1.00</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115074591&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=ODD&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>
  <tr id="TR1_02-0170006" onmouseover="mouseEnter_pointer(this.id);" onmouseout="mouseOut_pointer(this.id);" style="display: ;">
  	<td class="b_cen">&nbsp;</td>
    <td class="b_rig"><span class="con">1</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','R','gid=115074591&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=70005&amp;strong=C&amp;langx=zh-cn');" title=" 新英格兰爱国者"><font true="">0.91</font></a></span></td>
    <td class="b_rig"><span class="con">小48</span> <span class="ratio"><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','OU','gid=115074591&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;type=C&amp;gnum=70005&amp;langx=zh-cn');" title="小"><font true="">0.86</font></a></span></td>
    <td class="b_cen"><span class="con_oe">&nbsp;</span><a href="javascript://" onclick="parent.parent.mem_order.betOrder('BK','EO','gid=115074591&amp;uid=3eebeebc0f10dafc9989a&amp;odd_f_type=H&amp;rtype=EVEN&amp;langx=zh-cn');" title=""><font undefined=""></font></a></td>
  </tr>



          </tbody></table>
  
</div>
		</td>
	</tr>
	<tr>
		<td id="foot"><b>&nbsp;</b></td>
	</tr>
    </tbody>
</table>

	
<!--下方刷新钮--><div id="refresh_down" class="refresh_M_btn" onclick="this.className='refresh_M_on';javascript:reload_var()"><span>刷新</span></div>
	

</td></tr></tbody></table>
<div id="SortTable" class="SortTable" name="SortTable" style="position:absolute; display:none;">
	<form id="SortForm" name="SortForm" method="POST" target="saveOrderFrame">
		<input type="hidden" id="SortType" name="SortType" value="">
		<input type="hidden" id="uid" name="uid" value="">
		<input type="hidden" id="langx" name="langx" value="">
	  <table bgcolor="#FFFFFF">	
	    <tbody><tr>
	    	<td id="SortTypeC" name="SortTypeC" onclick="changeSortType(this,'C');">
					Sort By Compition
				</td>
			</tr>
	    <tr>
	    	<td id="SortTypeT" name="SortTypeT" onclick="changeSortType(this,'T');">
					Sort By Time
				</td>
			</tr>
		</tbody></table>
	</form>
</div>
<div class="more" id="more_window" name="more_window" style="position:absolute; display:none; ">
  <iframe id="showdata" name="showdata" scrolling="no" frameborder="NO" border="0" framespacing="0" noresize="" topmargin="0" leftmargin="0" marginwidth="0" marginheight="0"></iframe>
</div>
<!--选择联赛-->
<div id="legView" style="display:none;" class="legView">
    <div class="leg_head" onmousedown="initializedragie('legView')"></div>
    
<div><iframe id="legFrame" frameborder="no" border="0" allowtransparency="true"></iframe></div>


    <div class="leg_foot"></div>
</div>
<div><iframe id="saveOrderFrame" name="saveOrderFrame" frameborder="no" border="0" allowtransparency="true"></iframe></div>
<div id="controlscroll" style="position: absolute; top: 1px; display: none;"><table border="0" cellspacing="0" cellpadding="0" class="loadBox"><tbody><tr><td><!--loading--></td></tr></tbody></table></div>


<!--<div id="copyright">
    版权所有 皇冠 建议您以 IE 5.0 800 X 600 以上高彩模式浏览本站&nbsp;&nbsp;<a id=download title="下载" href="http://www.microsoft.com/taiwan/products/ie/" target="_blank">立刻下载IE</a>
</div>--> 
 
<!-- ------------------------------ 盘口选择 ------------------------------ -->

<div id="odd_f_window" style="display: none; position: absolute;">
  <table id="odd_group" width="100" border="0" cellspacing="1" cellpadding="1">
    <tbody>
      <tr>
        <td class="b_hline">盘口</td>
      </tr>
      <tr>
        <td class="b_cen" width="100"><span id="show_odd_f"></span></td>
      </tr>
    </tbody>
  </table>
</div>
 
<!-- ------------------------------ 盘口选择 ------------------------------ -->



<!-- 盘口选择 -->

<div id="odd_f_window" style="display: none;position:absolute">
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