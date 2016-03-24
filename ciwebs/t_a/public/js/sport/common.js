var matchurl = 0; //足球单式
var time_bet = 10; //倒计时10秒
var match_time_id=time_id = 0;

$(document).ready(function() {
    Footballnews();
    getuserinfo()
    refresh(0, 1);
    $('.menu li').click(function() {
        sps = $(this).attr('id');
        if(sps == 'sps'){
            return false;//如果是赛果就不改变样式
        }
        qx_bet();
        $('.menu li').addClass('menuover_1');
        $(this).removeClass('menuover_1');
        $(this).addClass('menuover');
        $('.sportmenu').find('ul:first').find('li').removeClass('c2');
        $('.sportmenu').find('ul:first').find('li:first').addClass('c2');
        $('.sportmenu_').hide();
        $('.sportmenu_first').show();
    })
    $('.sportmenu').find('ul:first').find('li').click(function() {
        qx_bet();
        $('.sportmenu').find('ul:first').find('li').removeClass('c2')
        $(this).addClass('c2')
        d = $(this).attr('data') 
        $('.sportmenu_').hide();
        $('#' + d).show();

    })
    $('.sportmenu_ li').click(function() {
        qx_bet();
        $('.sportmenu_ li').removeClass('c2')
        $(this).addClass('c2')
    })
    $(window).bind("resize", auto_wh);
    auto_wh();
    matchnum();
});
function matchnum(){
    $.post('/index.php/sports/main/matchnum',{},function(d){
        sitestatus(d)
        $.each(d,function(i,v){
            $('#'+i).html(v)
        })
        setTimeout('matchnum()',6000*2);
    },'json')
}
function log(k) {
    console.log(k)
}

function getElemPos(obj) {
    var pos = {
        "top": 0,
        "left": 0
    };
    if (obj.offsetParent) {
        while (obj.offsetParent) {
            pos.top += obj.offsetTop;
            pos.left += obj.offsetLeft;
            obj = obj.offsetParent;
        }
    } else if (obj.x) {
        pos.left += obj.x;
    } else if (obj.x) {
        pos.top += obj.y;
    }
    return {
        x: pos.left,
        y: pos.top
    };
}

//设置美东时间

function RefTime() {
    dd2.setSeconds(dd2.getSeconds() + 1);
    var myYears = (dd2.getYear() < 1900) ? (1900 + dd2.getYear()) : dd2.getYear();
    document.getElementById("vlock").innerHTML = "美东时间：" + myYears + "-" + fixNum(dd2.getMonth() + 1) + "-" + fixNum(dd2.getDate()) + " " + time(dd2);
}

function time(vtime) {
    var s = '';
    var d = vtime != null ? new Date(vtime) : new Date();
    with(d) {
        s = fixNum(getHours()) + ':' + fixNum(getMinutes()) + ':' + fixNum(getSeconds())
    }
    return (s);
}

function fixNum(num) {
    return parseInt(num) < 10 ? '0' + num : num;
}

function sport_menu(obj) {
    $('.sportmenu').hide();
    $('.sportmenu .sportmenu_:first ,.sport_' + obj).show();
    if (obj == 'today')
        refresh(0, 1);
    else if (obj == 'morning')
        refresh(311, 1);
    else if (obj == 'grounder')
        refresh(5, 1);
    else if (obj == 'amidithion')
        refresh(411, 0);
}


function formatNumber(num, exponent) {
    if (num != 0) {
        num = parseFloat(num).toFixed(exponent);
        if (num < 0)
            num = "<font color='#006633'>" + num + "</font>";
        return num;
    } else {
        return '';
    }
}

window.onscroll = function() {
    auto_wh()
}
$(function() {
    $("#selectAll").click(function() { //全选  
        $("#legname_data_load :checkbox").attr("checked", true);
    });

    $("#unSelect").click(function() { //全不选  
        $("#legname_data_load :checkbox").attr("checked", false);
    });

    $("#reverse").click(function() { //反选  
        $("#legname_data_load :checkbox").each(function() {
            $(this).attr("checked", !$(this).attr("checked"));
        });
    });
});

function auto_wh() {

    h = $(window).height();
    w = $(window).width();
    scrollTop = $(window).scrollTop();
    header_h = $('.header').height();
    $(".left").height(h - header_h - 1)
    $("#right").height(h - header_h - 1)
    $("#right").width(w - 230)
    $("#right_sport_main").height(h - header_h - 75 + scrollTop)
    $("#right_sport_main").css({
        'z-index': 11000
    })
}

function showOrder() {
    $('#order_button').removeClass('ord_btn');
    $('#order_button').addClass('ord_on');
    $('#record_button').removeClass('record_on');
    $('#record_button').addClass('record_btn');
    $('#bet_login_out,#bethistory').stop();
    $('#bet_login_out').fadeIn(500);
    $('#bethistory').hide();
}

function showRec() {
    $('#order_button').removeClass('ord_on');
    $('#order_button').addClass('ord_btn');
    $('#record_button').removeClass('record_btn');
    $('#record_button').addClass('record_on');
    $('#bet_login_out,#bethistory').stop();
    $('#bethistory').fadeIn(500);
    $('#bet_login_out').hide();
}

function checkUid() {

    if ($('#userName').html() != '你好，请先登录！') {
        return true;
    } else return false;
}
function sitestatus(d){
    if(d.sitestatus){
        window.top.frames.location.href=d.sitestatus;
        return false;
    }
}
var getuserinfo_time_id = null;

function getuserinfo_auto() {

    clearTimeout(getuserinfo_time_id);
    getuserinfo();
    getuserinfo_time_id = setTimeout('getuserinfo_auto()', 15000);
}
getuserinfo_auto();

function getuserinfo() {
    token = $('#token').val();
    uid = $('#uid').val();
    $.post('/index.php/sports/user/getuserinfo', {
        token: token,
        uid: uid
    }, function(d) {
        sitestatus(d)
        if (d.login == 1) {
            $('#userName').html(d.username);
            $('#user_money').html(d.money);
            $('#token').val(d.token);
        } else {
            $('#user_money').html('0.00');
            $('#userName').html('你好，请先登录！');
        }
    }, 'json')
}

function loading(obj) {
    //  $(obj).html("<div class='loading'>数据加载中...</div>");
    $("#load_alpha").show()
    header_h = $('.header').height();
    var x = $('#right_sport_main').position().top;
    var y = $('#right_sport_main').position().left;
    log(x + '-' + y);
    $("#load_alpha").css({
        top: x,
        left: y,
        width: $("#right").width(),
        height: $("#right").height() - 60
    })
}

function refresh(i, p, s) {
    refreshmatch(120,120);
    $('#legselect').show();
    if (s) dbs = null;
    i >= 0 ? matchurl = i : matchurl = 0;
    if (matchurl == 0)
        FootballToday(p);
    else if (matchurl == 1)
        BasketballToday(p);
    else if (matchurl == 4)
        BasketballGG(p);
    else if (matchurl == 11)
        FootballGG(p);
    else if (matchurl == 2)
        FootballZRQ(p);
    else if (matchurl == 3)
        FootballBD(p);
    else if (matchurl == 5){
        $('#legselect').hide();
        FootballPlaying(p);
        refreshmatch(10,30);
    }
    else if (matchurl == 6){
         $('#legselect').hide();
        BasketballPlaying(p);
    }
       
    else if (matchurl == 7)
        VolleyballToday(p); 
    else if (matchurl == 8)
        TennisToday(p); 
    else if (matchurl == 9)
        BaseballToday(p); 
    else if (matchurl == 311)
        FootballMorning(p);
    else if (matchurl == 312)
        FootballMBD(p);
    else if (matchurl == 313)
        FootballMZRQ(p);
    else if (matchurl == 314)
        FootballMGG(p);
    else if (matchurl == 321)
        BasketballMorning(p);
    else if (matchurl == 322)
        BasketballMGG(p);
    else if (matchurl == 331)
        VolleyballMorning(p); 
    else if (matchurl == 341)
        TennisMorning(p); 
    else if (matchurl == 411)
        FBRresults(p);
    else if (matchurl == 421)
        BKRresults(p);
    else if (matchurl == 431)
        VBRresults(p);
    else if (matchurl == 441)
        TNRresults(p);
    else if (matchurl == 441)
        TNRresults(p);
    else if (matchurl == 451)
        BBRresults(p);

}
isnumber = function(e) {
    if ($.browser.msie) {
        if (((event.keyCode > 47) && (event.keyCode < 58)) ||
            (event.keyCode == 8)) {
            return true;
        } else {
            return false;
        }
    } else {
        if (((e.which > 47) && (e.which < 58)) ||
            (e.which == 8)) {
            return true;
        } else {
            return false;
        }
    }
}

function qx_bet() {
    showOrder();
    $('#bet_login_out').html('<div style="padding-top:50px;min-height:100px;">点击赔率便可将<br>选项加到交易单裡</div>')
}

function check_bet() {
    $("#checkOrder").removeAttr('checked');
    btn=$('#qr_btn').css('display');
    plwin = $("#plwin").val();
    bet_money = $("#bet_money").val();
    minmoney = parseFloat($("#minmoney").html());
    maxmoney = parseFloat($("#maxmoney").html());
    if (bet_money < minmoney || bet_money > maxmoney) {
        alert('投注金额不符合限定金额\n当前投注：' + bet_money + '\n最少：' + minmoney + '\n最高：' + maxmoney)
        $('#bet_money').focus();
    }else if(btn=='none'){
        alert('注单正在提交中，请勿重复提交！')
    } else {
        if (confirm('是否确认下注？') && btn!='none') {
            $('#qr_btn').addClass('none')
            dsorcg = $('#dsorcg').val();
            postdata = $('#order_bet_form').serialize();
            $.post('/index.php/sports/bet/post_bet?dsorcg=' + dsorcg + '&uid=' + uid + '&token=' + token, postdata, function(d) {
               if(d.sitestatus)sitestatus(d)
               else{


                    if (d.status == '0' && d.data) {
                        alert(d.msg)

                        postdata.split('&').forEach(function(param) {
                            param = param.split('=');
                            var name = param[0],
                                val = param[1];
                            if (name == 'Sport_Type%5B%5D') {
                                html = betOrderView(d.data, val);

                            }
                        })


                        $('#bet_login_out').html(html)
                        $('#bet_money').focus();

                        check_win(bet_money);
                        orderReload();
                    } else {
                        alert(d.msg)
                        if (d.status == 2) $('#bet_login_out').html('')
                        else $('#qr_btn').css({'display':'block'});

                    }
                }
            }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
                var headers = XMLHttpRequest.getAllResponseHeaders();
                var timeout = '';
                if (XMLHttpRequest.getResponseHeader("Connection") == "close")
                    timeout = " :加载超时,服务器连接中断!";
                error = "数据加载异常" + timeout + "\r";
                error += headers;
                error += '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown;

                alert(error)
            });
        } else {

        }
    }
}

function check_win(bet_money) {
    plwin_obj = $(".plwin");
    dsorcg = $('#dsorcg').val();
    plwin = 1;
    $.each(plwin_obj, function(i, v) {
        plwin = plwin * parseFloat($(this).val());
    })
    if(dsorcg!=1)plwin-=1;
    isnum = /^\d+(\.\d+)?$/;
    if (isnum.test(bet_money) == false || bet_money == '')
        bet_money = $("#bet_money").val();
    else
        $("#bet_money").val(bet_money);
    bet_money = $("#winmoney").html((plwin * bet_money).toFixed(2));
}

function closecgbet(matchid) {
    bet_num = $('.order_div_bet').length;
    if (bet_num > 1)
        $('.bet_' + matchid).parent().remove();
    else
        qx_bet();
}

//window.clearTimeout(time_id);

//clearTimeout(time_id);
//http://xinhome.com/index.php/sports/main/?token=1a30ade1b90457c9243c8f70e27f941e&uid=17687
function refreshmatch(time_match,time_match_){
    clearTimeout(match_time_id);
    obj=$("#tableref");
    
    if(time_match<10)
        obj.html(' 0'+time_match+'');
    else
        obj.html(' '+time_match+'');
    
    match_time_id=setTimeout(function(){
        if(time_match<=1){
        time_match=time_match_;
        refresh(matchurl,$('#legpage').val())
        }
        refreshmatch(time_match-1,time_match_)
    },1000);
}
function orderReload() {
    clearTimeout(time_id);
    var check = $("#checkOrder").attr('checked');
    if (check == 'checked') {
        Refresh();
    } else {
        $("#bet_time").html("10");
    }
}

function Refresh() {
    time_bet = time_bet - 1;
    if (time_bet == 0) {
        dsorcg = $('#dsorcg').val();
        if (dsorcg == 1) {
            obj = $('.order_div_bet')
            matchid = obj.attr('data');
            bet_money = $('#bet_money').val();
            Match_Type = obj.find("input[name='Match_Type[]']").val();
            Sport_Type = obj.find("input[name='Sport_Type[]']").val();
            betOrder(Sport_Type, Match_Type, matchid, bet_money);
        }

    }
    if (time_bet < 0) {
        time_bet = 10;
        $("#bet_time").html(time_bet);
    } else {
        $("#bet_time").html(time_bet);
    }
    time_id = setTimeout("orderReload()", 1000);
}

function betOrderView(d, sport_type) {
    var PKINFO = info = '';
    var betpl = 0;
    var html_btn = '';
    var html_bet = '';
    var html_bet_re = '';
    var dsorcg = $('#dsorcg').val();
    Match_ShowType = '';
    oddpk = $('#arepk').val();
    PKINFO = d.data.pk[0][0];
    if (d.data.pk[2] == 'QCDY' || d.data.pk[2] == 'SBDY') {
        if (d.data.pk[2] == 'QCDY') {
            if (d.data.pk[0][1] == 'Z') {
                info = d.data.data[0].Match_Master;
            } else if (d.data.pk[0][1] == 'K') {
                info = d.data.data[0].Match_Guest;
            } else {
                info = d.data.pk[0][1];
            }
        } else {
            if (d.data.pk[0][1] == 'Z') {
                info = d.data.data[0].Match_Master;
            } else if (d.data.pk[0][1] == 'K') {
                info = d.data.data[0].Match_Guest;
            } else {
                info = d.data.pk[0][1];
            }
        }
    } else if (d.data.pk[2] == 'QCRQ' || d.data.pk[2] == 'SBRQ') {
        if (d.data.pk[2] == 'QCRQ') {
            if (d.data.data[0]['Match_ShowType'] == 'H') {
                PKINFO = '主让';
                Match_ShowType = 'H';
            } else {
                PKINFO = '客让';
                Match_ShowType = 'C';
            }
            PKINFO += ' <span class="c1 b">' + d.data.data[0]['Match_RGG'] + '</span>';
            betpl = d.data.data[0]['Match_RGG'];
        } else {
            if (d.data.data[0]['Match_Hr_ShowType'] == 'H') {
                PKINFO = '主让';
                Match_ShowType = 'H';
            } else {
                PKINFO = '客让';
                Match_ShowType = 'C';
            }
            PKINFO += ' <span class="c1 b">' + d.data.data[0]['Match_BRpk'] + '</span>';
            betpl = d.data.data[0]['Match_BRpk'];
        }
        if (d.data.pk[0][1] == 'Z')
            info = d.data.data[0].Match_Master;
        else if (d.data.pk[0][1] == 'K')
            info = d.data.data[0].Match_Guest;
        else
            info = d.data.pk[0][1];
    } else if (d.data.pk[2] == 'QCDX' || d.data.pk[2] == 'SBDX') {
        if (d.data.pk[2] == 'QCDX') {
            info = d.data.pk[0][1] + ' ' + d.data.data[0]['Match_DxGG'];
            betpl = d.data.data[0]['Match_DxGG'];
        } else {
            info = d.data.pk[0][1] + ' ' + d.data.data[0]['Match_Bdxpk'];
            betpl = d.data.data[0]['Match_Bdxpk'];
        }
    } else /*if (d.data.pk[2] == 'QCDS' || d.data.pk[2] == 'SBDS' || d.data.pk[2] == 'BD')*/ {
        info = d.data.pk[0][1];
    }
    var bet_num = $('.order_div_bet').length;
    info = (d.data.betinfo).split('@')[0]+" @ <span class='b c1'>" + formatNumber(d.data.pl, 2) + "</span>";
    var html = '<div class="order_div"><form name="order_bet_name" id="order_bet_form" >' +
        '<div class="tiTimer xs" onclick=orderReload()><span id="bet_time">10</span><input id="checkOrder" type="checkbox" value="10" checked=""></div>' +
        '<div class="clear"></div>' +
        '<div class="order_div_bet_all">';
    html_bet += '<div><div class="order_div_bet bet_' + d.data.data[0].Match_ID + '" data=' + d.data.data[0].Match_ID + '>';
    html_bet += '<div class="order_div_leg_name"><span class="bet_close fl_r" onclick="closecgbet(' + d.data.data[0].Match_ID + ')"></span>' + d.data.data[0].Match_Name + '</div>';
    html_bet += '<div class="order_div_pk">' + PKINFO + '</div>';
    html_bet += '<div class="order_div_Mster_Guest">' + d.data.data[0].Match_Master + ' Vs ' + d.data.data[0].Match_Guest + '</div>';
    html_bet += '<div class="order_div_info ' + (bet_num < 1 ? '' : 'bg2') + '">' + info + '</div>';
    html_bet += '<input name="Match_ID[]" type="hidden"  value="' + d.data.data[0].Match_ID + '">';
    html_bet += '<input name="Match_ShowType[]" type="hidden"  value="' + Match_ShowType + '">';
    html_bet += '<input name="Match_Type[]" type="hidden"  value="' + d.data.pk[1] + '">';
    html_bet += '<input name="Sport_Type[]" type="hidden"  value="' + sport_type + '">';
    html_bet += '<input name="Bet_PK[]" type="hidden"  value="' + betpl + '">';
    html_bet += '<input name="Bet_PL[]" type="hidden"  value="' + d.data.pl + '">';
    html_bet += '<input name="Odd_PK[]" type="hidden"  value="' + oddpk + '">';
    html_bet += '<input name="Win_PL[]" type="hidden" class="plwin" value="' + d.data.plwin + '">';
    html_bet += '</div></div>';

    if (dsorcg != 1 && bet_num > 0) {
        $('.order_div_info').removeClass('bg2');
        var cgcheck = false;
        $.each($('.order_div_bet'), function(i, v) {
            matchid = $(this).attr('data');
            if (matchid == d.data.data[0].Match_ID) {
                cgcheck = true;
                // html_bet=html_bet.replace('order_div_info','order_div_info bg2');
                $('.bet_' + matchid).parent().html(html_bet);
                //html_bet+=$('.order_div_bet').html();
            } else {
                MG = $(this).find('.order_div_Mster_Guest').html();
                if (MG == d.data.data[0].Match_Master + ' Vs ' + d.data.data[0].Match_Guest) {
                    $('.bet_' + matchid).parent().html(html_bet);
                    cgcheck = true;
                }
            }
        })
        if (cgcheck == false)
            html_bet = ($('.order_div_bet_all').html() + html_bet);
        else
            html_bet = ($('.order_div_bet_all').html());
    } else {


    }
    html_bet += '</div>'; /*<div class="order_div_bet">*/

    html_btn += '<div class="order_div_money"> <span class="fl_l">交易金额：</span>';
    html_btn += '<span class="fl_l"><input type="text" name="bet_money" id="bet_money" maxlength=7 onkeyup="return check_win(event);" onkeydown="if(event.keyCode==13)return check_bet();" oncontextmenu="return false" oncopy="return false" oncut="return false" autocomplete="off" onpaste="return false" onkeypress="return isnumber(event);"></span>';
    html_btn += '<input type="hidden" id="plwin" value="' + d.data.plwin + '">';
    html_btn += '<input type="hidden" name="uid" id="uid" value="' + uid + '">';
    html_btn += '<div class="clear"></div>';
    html_btn += '可赢金额：<span id="winmoney">0</span><Br> 单注最低：<span id="minmoney">' + d.data.pk['xe'][2] + '</span><br>单注最高：<span id="maxmoney">' + d.data.pk['xe'][1] + '</span>';
    html_btn += '<div class="clear"></div>';
    html_btn += '<div class="qx_btn fl_l xs" onclick="qx_bet()">取消</div><div id="qr_btn" class="qr_btn fl_l xs" onclick="check_bet()">确认交易</div>';
    html_btn += '<div class="clear"></div>';
    html_btn += '</div>';
    html += html_bet + html_btn;
    html += '<div class="clear"></div></form></div>';

    return html;
}

function betOrder(sport_type /*赛事类型*/ , pk /*盘口*/ , matchid /*matchid*/ , bet_money) {
    if (parseFloat(bet_money) < 0 || !bet_money)
        bet_money = 0;
    if (checkUid() != true) {
        return false;
    }
    showOrder()
    var oddpk = $('#arepk').val();
    var dsorcg = $('#dsorcg').val();
    var token = $('#token').val()
    var uid = $('#uid').val()
    $.post('/index.php/sports/bet/makebetshow', {
        uid: uid,
        token: token,
        sport_type: sport_type,
        pk: pk,
        matchid: matchid,
        oddpk: oddpk,
        dsorcg: dsorcg
    }, function(d) {
        if (d.login != '1') {
            qx_bet();
            if(d.sitestatus){sitestatus(d)}
            else alert('您已经退出，请登录账号！');
        } else {
            if (d.status == 1) {
                html = betOrderView(d, sport_type);
                if (dsorcg == 1)
                    $('#bet_login_out').html(html)
                else {
                    $('#bet_login_out').html(html)
                }
                $('#bet_money').focus();

                check_win(bet_money);
                orderReload();
            } else
                $('#bet_login_out').html(d.msg)
        }


    }, 'json')
}

function pkchoose() {
    $('#tableref').click();
    var oddpk = $('#arepk').val();
}

function pages(p, thispage) {
    page = '';

    for (var i = 1; i <= p; i++) {
        s = '';
        if (i == thispage) {
            s = "selected";
        }
        page += "<option " + s + " value=" + i + ">第" + i + "页</option>";
    }
    $('#legpage').html(page);
}

function showLeg(id) {

    $('#TR_' + id + ',#TR1_' + id + ',#TR2_' + id).toggle();
}

function showLegList() {
    header_h = $('.header').height();
    var x = $('#right_sport_main').position().top;
    var y = $('#right_sport_main').position().left;
    log(x + '-' + y);
    $("#legname_data").css({
        top: x + 1,
        left: y + 5,
        width: $("#data").width() - 22,
        'max-height': $("#right_sport_main").height() - 50

    })
    $('#legname_data').fadeToggle(500);
}

function getlegname(d) {
    leg = '';
    $.each(d, function(i, v) {
        leg += '<div><input type="checkbox" name="legname" value="' + v + '"> ' + v + '</div>'
    })
    $('#legname_data_load').html(leg)
}
var dbs = '';
var oddpk = 'H'; //默认香港盘
function BasketballPlaying(p) {
    $('#dsorcg').val(1)
    $('#tableref').addClass('tablerefon');
    oddpk = $('#arepk').val();
    if (!oddpk)
        oddpk = 'H';
    var d_ = leg_ = '';
    var Match_Name = null;
    var thispage = $('#legpage').val();
    leg = $('#legname_data_load input');
    if (leg.length > 0) {
        $.each(leg, function(i, v) {
            if ($(this).attr('checked')) {
                leg_ += $(this).val() + '|'
            }
        })
    }
    if (p)
        thispage = p;
    var table = ''
    var html = table + '<table cellpadding="0" cellspacing="0" border="0" class="game"><tr>' +
        '<th nowrap="" class="time">时间</th>' +
        '<th nowrap="" class="team">赛事</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">全场 - 让球</th>' +
        '<th nowrap="" class="h_ou">全场 - 大小</th>' +
        '<th nowrap="" class="h_oe">单双</th>' +
        '</tr>';
    $.post('/index.php/sports/Match/BasketballPlaying/' + "?t=" + Math.random(), {
        p: thispage,
        oddpk: oddpk,
        leg: leg_
    }, function(d) {
        sitestatus(d)
        if (d.db) {
            pages(d.page, thispage)

            if (!dbs)
                dbs = d.db;
            else {
                if (dbs.length < d.db.length)
                    dbs = d.db;
            }


            $.each(d.db, function(i, v) {

               
                if (v.Match_Name != Match_Name) {
                    html += '<tr onclick="showLeg(' + v.Match_ID + ')" class="leg_bar">' +
                        '<td colspan="6" class="b_hline">' + v.Match_Name + '</td>' +
                        '</tr>';
                    Match_ID = v.Match_ID;
                }
                Match_Name = v.Match_Name;
                html += '<tr id="TR_' + Match_ID + '">' + 
                    //时间
                    ' <td rowspan="2" class="b_cen">' + v["Match_Date"] + '<br>' + v["Match_NowScore"] + '</td>' +
                    //主客队
                    ' <td rowspan="2" class="team_name" >' + v.Match_Master + '<br>' + v.Match_Guest + '</td>' +
                    //独赢主
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_BzM"] != v["Match_BzM"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'BKP\',\'Match_BzM\',' + v.Match_ID + ')" title="' + v.Match_Master + '" >' + formatNumber(v.Match_BzM, 2) + '</a></td>' +
                    //让球主让
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_Ho"] != v["Match_Ho"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "H" && v["Match_Ho"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BKP\',\'Match_Ho\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Ho"], 2) + '</a></span></td>' +
                    //大
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_DxDpl"] != v["Match_DxDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxXpl"] == "0" ? "" : '大' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BKP\',\'Match_DxDpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_DxDpl"], 2) + '</a></span></td>' +
                    //单
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_DsDpl"] != v["Match_DsDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsDpl"] == "0" ? "" : "单") + '<a href="javascript:;" onclick="betOrder(\'BKP\',\'Match_DsDpl\',' + v.Match_ID + ')" title="单">' + formatNumber(v["Match_DsDpl"], 2) + '</a></td>' +
                    '</tr>' +
                    '<tr id="TR1_' + Match_ID + '" >' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzG"] != v["Match_BzG"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'BKP\',\'Match_BzG\',' + v.Match_ID + ')" title="' + v.Match_Guest + '" ><font>' + formatNumber(v.Match_BzG, 2) + '</font></a></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_Ao"] != v["Match_Ao"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "C" && v["Match_Ao"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BKP\',\'Match_Ao\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v["Match_Ao"], 2) + '</a></span></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_DxXpl"] != v["Match_DxXpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxGG1"] == "O" || v["Match_DxXpl"] == "0" ? "" : '小' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BKP\',\'Match_DxXpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_DxXpl, 2) + '</a></span></td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_DsSpl"] != v["Match_DsSpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsSpl"] == "0" ? "" : "双") + '<a href="javascript:;" onclick="betOrder(\'BKP\',\'Match_DsSpl\',' + v.Match_ID + ')" title="双">' + formatNumber(v.Match_DsSpl, 2) + '</a></td>' +
                    '</tr> ';

            })
            dbs = d.db;
            html += '</table>';
            if (d.legname) {
                if (!leg_) getlegname(d.legname);
            }

        } else {
            html += '<tr><Td colspan=9 class="b_cen">无赛事数据</td></tr>';
        }

        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
        $('#data').html(html)
        $('#tablename').html('篮球滚球');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}

function FootballPlaying(p) {
    $('#dsorcg').val(1)
    $('#tableref').addClass('tablerefon');
    oddpk = $('#arepk').val();
    if (!oddpk)
        oddpk = 'H';
    var d_ = leg_ = '';
    var Match_Name = null;
    var thispage = $('#legpage').val();
    leg = $('#legname_data_load input');
    if (leg.length > 0) {
        $.each(leg, function(i, v) {
            if ($(this).attr('checked')) {
                leg_ += $(this).val() + '|'
            }
        })
    }
    if (p)
        thispage = p;
    var table = '';
    var html = table + '<table cellpadding="0" cellspacing="0" border="0" class="game"><tr>' +
        '<th nowrap="" class="time">时间</th>' +
        '<th nowrap="" class="team">赛事</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">全场 - 让球</th>' +
        '<th nowrap="" class="h_ou">全场 - 大小</th>' +
        '<th nowrap="" class="h_oe">单双</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">半场 - 让球</th>' +
        '<th nowrap="" class="h_ou">半场 - 大小</th>' +
        '</tr>';
    $.post('/index.php/sports/Match/FootballPlaying/' + "?t=" + Math.random(), {
        p: thispage,
        oddpk: oddpk,
        leg: leg_
    }, function(d) {
        sitestatus(d)
        if (d.db) {
            pages(d.page, thispage)

            if (!dbs)
                dbs = d.db;
            else {
                if (dbs.length < d.db.length)
                    dbs = d.db;
            }

            $.each(d.db, function(i, v) {

                if (v.Match_Name != Match_Name) {
                    html += '<tr onclick="showLeg(' + v.Match_ID + ')" class="leg_bar">' +
                        '<td colspan="9" class="b_hline">' + v.Match_Name + '</td>' +
                        '</tr>';
                    Match_ID = v.Match_ID;
                }
                Match_Name = v.Match_Name;
                html += '<tr id="TR_' + Match_ID + '">' +
                    //时间
                    ' <td rowspan="3" class="b_cen">' + v.Match_Date + '<Br>' + v.Match_NowScore + '</td>' +
                    //主客队
                    ' <td rowspan="2" class="team_name" ><div class="fl_l">' + v.Match_Master + '</div> <div class="redcard' + v.Match_HRedCard + '"></div><br><div class="fl_l">' + v.Match_Guest + '</div> <div class="redcard' + v.Match_GRedCard + '"></div></td>' +
                    //独赢主
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_BzM"] != v["Match_BzM"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FTP\',\'Match_BzM\',' + v.Match_ID + ')" title="' + v.Match_Master + '" >' + formatNumber(v.Match_BzM, 2) + '</a></td>' +
                    //让球主让
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_Ho"] != v["Match_Ho"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "H" && v["Match_Ho"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FTP\',\'Match_Ho\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Ho"], 2) + '</a></span></td>' +
                    //大
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_DxDpl"] != v["Match_DxDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxXpl"] == "0" ? "" : '大' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FTP\',\'Match_DxDpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_DxDpl"], 2) + '</a></span></td>' +
                    //单
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_DsDpl"] != v["Match_DsDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsDpl"] == "0" ? "" : "单") + '<a href="javascript:;" onclick="betOrder(\'FT\',\'Match_DsDpl\',' + v.Match_ID + ')" title="单">' + formatNumber(v["Match_DsDpl"], 2) + '</a></td>' +
                    //半场独赢主
                    ' <td class="b_1st" style=" ' + (dbs[i]["Match_Bmdy"] != v["Match_Bmdy"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FTP\',\'Match_Bmdy\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Bmdy"], 2) + '</a></td>' +
                    //半场让球主
                    ' <td class="b_1stR" style=" ' + (dbs[i]["Match_BHo"] != v["Match_BHo"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_Hr_ShowType"] == "H" && v["Match_BHo"] != 0) ? v.Match_BRpk : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FTP\',\'Match_BHo\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_BHo"], 2) + '</a></span></td>' +
                    //半场 大
                    ' <td class="b_1stR" style=" ' + (dbs[i]["Match_Bdpl"] != v["Match_Bdpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_Bdxpk1"]) ? "大" + v["Match_Bdxpk1"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FTP\',\'Match_Bdpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_Bdpl"], 2) + '</a></span></td>' +
                    '</tr>' +
                    '<tr id="TR1_' + Match_ID + '" >' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzG"] != v["Match_BzG"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FTP\',\'Match_BzG\',' + v.Match_ID + ')" title="' + v.Match_Guest + '" ><font>' + formatNumber(v.Match_BzG, 2) + '</font></a></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_Ao"] != v["Match_Ao"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "C" && v["Match_Ao"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FTP\',\'Match_Ao\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v["Match_Ao"], 2) + '</a></span></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_DxXpl"] != v["Match_DxXpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxGG1"] == "O" || v["Match_DxXpl"] == "0" ? "" : '小' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FTP\',\'Match_DxXpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_DxXpl, 2) + '</a></span></td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_DsSpl"] != v["Match_DsSpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsSpl"] == "0" ? "" : "双") + '<a href="javascript:;" onclick="betOrder(\'FTP\',\'Match_DsSpl\',' + v.Match_ID + ')" title="双">' + formatNumber(v.Match_DsSpl, 2) + '</a></td>' +
                    '  <td class="b_1st" style=" ' + (dbs[i]["Match_Bgdy"] != v["Match_Bgdy"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FTP\',\'Match_Bgdy\',' + v.Match_ID + ')" title="' + v.Match_Guest + '"><font>' + formatNumber(v.Match_Bgdy, 2) + '</font></a></td>' +
                    '  <td class="b_1stR" style=" ' + (dbs[i]["Match_BAo"] != v["Match_BAo"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_Hr_ShowType"] == "C" && v["Match_BAo"] != 0) ? v.Match_BRpk : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FTP\',\'Match_BAo\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v.Match_BAo, 2) + '</a></span></td>' +
                    '  <td class="b_1stR" style=" ' + (dbs[i]["Match_Bxpl"] != v["Match_Bxpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_Bdxpk1"]) ? "小" + v["Match_Bdxpk1"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FTP\',\'Match_Bxpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_Bxpl, 2) + '</a></span></td>' +
                    '</tr>' +
                    '<tr id="TR2_' + Match_ID + '" >' +
                    '  <td class="team_name" id="TR_11-1350180_1">和局</td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzH"] != v["Match_BzH"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FTP\',\'Match_BzH\',' + v.Match_ID + ')" title="和"><font>' + formatNumber(v.Match_BzH, 2) + '</font></a></td>' +
                    '  <td colspan="3" valign="top" class="b_cen"></td>' +
                    '  <td class="b_1st" style=" ' + (dbs[i]["Match_Bhdy"] != v["Match_Bhdy"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FTP\',\'Match_Bhdy\',' + v.Match_ID + ')" title="和">' + formatNumber(v.Match_Bhdy, 2) + '</a></td>' +
                    '  <td colspan="2" valign="top" class="b_1st">&nbsp;</td>' +
                    '</tr>';

            })
            dbs = d.db;
            html += '</table>';

            //$('#load_alpha').hide();
        } else {
            html += '<tr><Td colspan=9 class="b_cen">无赛事数据</td></tr>';
        }
        if (d.legname) {
            if (!leg_) getlegname(d.legname);
        }
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');

        $('#data').html(html)
        $('#tablename').html('足球滚球');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}

function FootballToday(p) {
    $('#dsorcg').val(1)
    $('#tableref').addClass('tablerefon');
    oddpk = $('#arepk').val();
    if (!oddpk)
        oddpk = 'H';
    d_ = leg_ = '';
    var Match_Name = null;
    var thispage = $('#legpage').val();
    leg = $('#legname_data_load input');
    if (leg.length > 0 && $('#tablename').html() == '足球单式') {
        $.each(leg, function(i, v) {
            if ($(this).attr('checked')) {
                leg_ += $(this).val() + '|'
            }
        })
    }
    if (p)
        thispage = p;
    var table = '';
    var html = table + '<table cellpadding="0" cellspacing="0" border="0" class="game"><tr>' +
        '<th nowrap="" class="time">时间</th>' +
        '<th nowrap="" class="team">赛事</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">全场 - 让球</th>' +
        '<th nowrap="" class="h_ou">全场 - 大小</th>' +
        '<th nowrap="" class="h_oe">单双</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">半场 - 让球</th>' +
        '<th nowrap="" class="h_ou">半场 - 大小</th>' +
        '</tr>';
    $.post('/index.php/sports/Match/FootballToday/' + "?t=" + Math.random(), {
        p: thispage,
        oddpk: oddpk,
        leg: leg_
    }, function(d) {
        sitestatus(d)
        if (d.db) {
            pages(d.page, thispage)

            if (!dbs)
                dbs = d.db;
            else {
                if (dbs.length < d.db.length)
                    dbs = d.db;
            }

            $.each(d.db, function(i, v) {

                if (v.Match_Name != Match_Name) {
                    html += '<tr onclick="showLeg(' + v.Match_ID + ')" class="leg_bar">' +
                        '<td colspan="9" class="b_hline">' + v.Match_Name + '</td>' +
                        '</tr>';
                    Match_ID = v.Match_ID;
                }
                Match_Name = v.Match_Name;

                html += '<tr id="TR_' + Match_ID + '">' +
                    //时间
                    ' <td rowspan="3" class="b_cen">' + v.Match_Date + '</td>' +
                    //主客队
                    ' <td rowspan="2" class="team_name" >' + v.Match_Master + '<br>' + v.Match_Guest + '</td>' +
                    //独赢主
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_BzM"] != v["Match_BzM"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BzM\',' + v.Match_ID + ')" title="' + v.Match_Master + '" >' + formatNumber(v.Match_BzM, 2) + '</a></td>' +
                    //让球主让
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_Ho"] != v["Match_Ho"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "H" && v["Match_Ho"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Ho\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Ho"], 2) + '</a></span></td>' +
                    //大
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_DxDpl"] != v["Match_DxDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxXpl"] == "0" ? "" : '大' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_DxDpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_DxDpl"], 2) + '</a></span></td>' +
                    //单
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_DsDpl"] != v["Match_DsDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsDpl"] == "0" ? "" : "单") + '<a href="javascript:;" onclick="betOrder(\'FT\',\'Match_DsDpl\',' + v.Match_ID + ')" title="单">' + formatNumber(v["Match_DsDpl"], 2) + '</a></td>' +
                    //半场独赢主
                    ' <td class="b_1st" style=" ' + (dbs[i]["Match_Bmdy"] != v["Match_Bmdy"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bmdy\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Bmdy"], 2) + '</a></td>' +
                    //半场让球主
                    ' <td class="b_1stR" style=" ' + (dbs[i]["Match_BHo"] != v["Match_BHo"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_Hr_ShowType"] == "H" && v["Match_BHo"] != 0) ? v.Match_BRpk : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BHo\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_BHo"], 2) + '</a></span></td>' +
                    //半场 大
                    ' <td class="b_1stR" style=" ' + (dbs[i]["Match_Bdpl"] != v["Match_Bdpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_Bdxpk1"]) ? "大" + v["Match_Bdxpk1"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bdpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_Bdpl"], 2) + '</a></span></td>' +
                    '</tr>' +
                    '<tr id="TR1_' + Match_ID + '" >' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzG"] != v["Match_BzG"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BzG\',' + v.Match_ID + ')" title="' + v.Match_Guest + '" ><font>' + formatNumber(v.Match_BzG, 2) + '</font></a></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_Ao"] != v["Match_Ao"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "C" && v["Match_Ao"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Ao\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v["Match_Ao"], 2) + '</a></span></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_DxXpl"] != v["Match_DxXpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxGG1"] == "O" || v["Match_DxXpl"] == "0" ? "" : '小' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_DxXpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_DxXpl, 2) + '</a></span></td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_DsSpl"] != v["Match_DsSpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsSpl"] == "0" ? "" : "双") + '<a href="javascript:;" onclick="betOrder(\'FT\',\'Match_DsSpl\',' + v.Match_ID + ')" title="双">' + formatNumber(v.Match_DsSpl, 2) + '</a></td>' +
                    '  <td class="b_1st" style=" ' + (dbs[i]["Match_Bgdy"] != v["Match_Bgdy"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bgdy\',' + v.Match_ID + ')" title="' + v.Match_Guest + '"><font>' + formatNumber(v.Match_Bgdy, 2) + '</font></a></td>' +
                    '  <td class="b_1stR" style=" ' + (dbs[i]["Match_BAo"] != v["Match_BAo"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_Hr_ShowType"] == "C" && v["Match_BAo"] != 0) ? v.Match_BRpk : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BAo\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v.Match_BAo, 2) + '</a></span></td>' +
                    '  <td class="b_1stR" style=" ' + (dbs[i]["Match_Bxpl"] != v["Match_Bxpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_Bdxpk1"]) ? "小" + v["Match_Bdxpk1"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bxpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_Bxpl, 2) + '</a></span></td>' +
                    '</tr>' +
                    '<tr id="TR2_' + Match_ID + '" >' +
                    '  <td class="team_name" id="TR_11-1350180_1">和局</td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzH"] != v["Match_BzH"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BzH\',' + v.Match_ID + ')" title="和"><font>' + formatNumber(v.Match_BzH, 2) + '</font></a></td>' +
                    '  <td colspan="3" valign="top" class="b_cen"></td>' +
                    '  <td class="b_1st" style=" ' + (dbs[i]["Match_Bhdy"] != v["Match_Bhdy"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bhdy\',' + v.Match_ID + ')" title="和">' + formatNumber(v.Match_Bhdy, 2) + '</a></td>' +
                    '  <td colspan="2" valign="top" class="b_1st">&nbsp;</td>' +
                    '</tr>';

            })
            dbs = d.db;
            html += '</table>';

            //$('#load_alpha').hide();
        } else {
            html += '<tr><Td colspan=9 class="b_cen">无赛事数据</td></tr>';
        }
        if (d.legname) {
            if (!leg_) getlegname(d.legname);
        }
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');

        $('#data').html(html)
        $('#tablename').html('足球单式');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}

function FootballGG(p) {
    $('#dsorcg').val(2)
    $('#tableref').addClass('tablerefon');
    oddpk = $('#arepk').val();
    if (!oddpk)
        oddpk = 'H';
     d_ = leg_ = '';
    var Match_Name = null;
    var thispage = $('#legpage').val();
    leg = $('#legname_data_load input');
    if (leg.length > 0 && $('#tablename').html() == '足球单式过关') {
        $.each(leg, function(i, v) {
            if ($(this).attr('checked')) {
                leg_ += $(this).val() + '|'
            }
        })
    }
    var thispage = $('#legpage').val();
    if (p)
        thispage = p;
    var table = ''
    var html = table + '<table cellpadding="0" cellspacing="0" border="0" class="game"><tr>' +
        '<th nowrap="" class="time">时间</th>' +
        '<th nowrap="" class="team">赛事</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">全场 - 让球</th>' +
        '<th nowrap="" class="h_ou">全场 - 大小</th>' +
        '<th nowrap="" class="h_oe">单双</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">半场 - 让球</th>' +
        '<th nowrap="" class="h_ou">半场 - 大小</th>' +
        '</tr>';
    $.post('/index.php/sports/Match/FootballGG/' + "?t=" + Math.random(), {
        p: thispage,
        oddpk: oddpk,leg:leg_
    }, function(d) {
        sitestatus(d)
        if (d.db) {
            pages(d.page, thispage)

            if (!dbs)
                dbs = d.db;
            else {
                if (dbs.length < d.db.length)
                    dbs = d.db;
            }

            $.each(d.db, function(i, v) {

                if (v.Match_Name != Match_Name) {
                    html += '<tr style="display: ;">' +
                        '<td colspan="9" class="b_hline">' +
                        '<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="showLeg(\'' + v.Match_Name + '\')">' +
                        ' <span id="' + v.Match_Name + '" name="' + v.Match_Name + '" class="showleg">' +
                        ' <span id="LegOpen"></span>' +
                        ' </span>' +
                        '</td><td onclick="showLeg(\'' + v.Match_Name + '\')" class="leg_bar">' + v.Match_Name + '</td></tr></tbody></table>' +
                        '</td>' +
                        '</tr>';
                }
                Match_Name = v.Match_Name;
                html += '<tr id="TR_' + v.Match_ID + '">' +
                    //时间
                    ' <td rowspan="3" class="b_cen">' + v.Match_Date + '</td>' +
                    //主客队
                    ' <td rowspan="2" class="team_name" >' + v.Match_Master + '<br>' + v.Match_Guest + '</td>' +
                    //独赢主
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_BzM"] != v["Match_BzM"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BzM\',' + v.Match_ID + ')" title="' + v.Match_Master + '" >' + formatNumber(v.Match_BzM, 2) + '</a></td>' +
                    //让球主让
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_Ho"] != v["Match_Ho"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "H" && v["Match_Ho"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Ho\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Ho"], 2) + '</a></span></td>' +
                    //大
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_DxDpl"] != v["Match_DxDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxXpl"] == "0" ? "" : '大' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_DxDpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_DxDpl"], 2) + '</a></span></td>' +
                    //单
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_DsDpl"] != v["Match_DsDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsDpl"] == "0" ? "" : "单") + '<a href="javascript:;" onclick="betOrder(\'FT\',\'Match_DsDpl\',' + v.Match_ID + ')" title="单">' + formatNumber(v["Match_DsDpl"], 2) + '</a></td>' +
                    //半场独赢主
                    ' <td class="b_1st" style=" ' + (dbs[i]["Match_Bmdy"] != v["Match_Bmdy"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bmdy\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Bmdy"], 2) + '</a></td>' +
                    //半场让球主
                    ' <td class="b_1stR" style=" ' + (dbs[i]["Match_BHo"] != v["Match_BHo"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_Hr_ShowType"] == "H" && v["Match_BHo"] != 0) ? v.Match_BRpk : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BHo\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_BHo"], 2) + '</a></span></td>' +
                    //半场 大
                    ' <td class="b_1stR" style=" ' + (dbs[i]["Match_Bdpl"] != v["Match_Bdpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_Bdxpk1"]) ? "大" + v["Match_Bdxpk1"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bdpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_Bdpl"], 2) + '</a></span></td>' +
                    '</tr>' +
                    '<tr id="TR1_' + v.Match_ID + '" >' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzG"] != v["Match_BzG"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BzG\',' + v.Match_ID + ')" title="' + v.Match_Guest + '" ><font>' + formatNumber(v.Match_BzG, 2) + '</font></a></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_Ao"] != v["Match_Ao"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "C" && v["Match_Ao"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Ao\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v["Match_Ao"], 2) + '</a></span></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_DxXpl"] != v["Match_DxXpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxGG1"] == "O" || v["Match_DxXpl"] == "0" ? "" : '小' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_DxXpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_DxXpl, 2) + '</a></span></td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_DsSpl"] != v["Match_DsSpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsSpl"] == "0" ? "" : "双") + '<a href="javascript:;" onclick="betOrder(\'FT\',\'Match_DsSpl\',' + v.Match_ID + ')" title="双">' + formatNumber(v.Match_DsSpl, 2) + '</a></td>' +
                    '  <td class="b_1st" style=" ' + (dbs[i]["Match_Bgdy"] != v["Match_Bgdy"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bgdy\',' + v.Match_ID + ')" title="' + v.Match_Guest + '"><font>' + formatNumber(v.Match_Bgdy, 2) + '</font></a></td>' +
                    '  <td class="b_1stR" style=" ' + (dbs[i]["Match_BAo"] != v["Match_BAo"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((dbs[i]["Match_Hr_ShowType"] == "C" && dbs[i]["Match_BAo"] != 0) ? v.Match_BRpk : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BAo\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v.Match_BAo, 2) + '</a></span></td>' +
                    '  <td class="b_1stR" style=" ' + (dbs[i]["Match_Bxpl"] != v["Match_Bxpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_Bdxpk1"]) ? "小" + v["Match_Bdxpk1"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bxpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_Bxpl, 2) + '</a></span></td>' +
                    '</tr>' +
                    '<tr id="TR2_' + v.Match_ID + '" >' +
                    '  <td class="team_name" id="TR_11-1350180_1">和局</td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzH"] != v["Match_BzH"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BzH\',' + v.Match_ID + ')" title="和"><font>' + formatNumber(v.Match_BzH, 2) + '</font></a></td>' +
                    '  <td colspan="3" valign="top" class="b_cen"></td>' +
                    '  <td class="b_1st" style=" ' + (dbs[i]["Match_Bhdy"] != v["Match_Bhdy"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bhdy\',' + v.Match_ID + ')" title="和">' + formatNumber(v.Match_Bhdy, 2) + '</a></td>' +
                    '  <td colspan="2" valign="top" class="b_1st">&nbsp;</td>' +
                    '</tr>';

            })
            dbs = d.db;
            html += '</table>';

        } else {
            html += '<tr><Td colspan=9 class="b_cen">无赛事数据</td></tr>';
        }
        if (d.legname) {
            if (!leg_) getlegname(d.legname);
        }
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
        $('#data').html(html)
        $('#tablename').html('足球单式过关');
        // $('#load_alpha').hide();
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}

function BasketballToday(p) {
    $('#dsorcg').val(1)
    $('#tableref').addClass('tablerefon');
    oddpk = $('#arepk').val();
    if (!oddpk)
        oddpk = 'H';
     d_ = leg_ = '';
    var Match_Name = null;
    var thispage = $('#legpage').val();
    leg = $('#legname_data_load input');
    if (leg.length > 0 && $('#tablename').html() == '篮球单式') {
        $.each(leg, function(i, v) {
            if ($(this).attr('checked')) {
                leg_ += $(this).val() + '|'
            }
        })
    }
    var thispage = $('#legpage').val();
    if (p)
        thispage = p;
    var table = ''
    var html = table + '<table cellpadding="0" cellspacing="0" border="0" class="game"><tr>' +
        '<th nowrap="" class="time">时间</th>' +
        '<th nowrap="" class="team">赛事</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">全场 - 让球</th>' +
        '<th nowrap="" class="h_ou">全场 - 大小</th>' +
        '<th nowrap="" class="h_oe">单双</th>' +
        '</tr>';
    $.post('/index.php/sports/Match/BasketballToday/' + "?t=" + Math.random(), {
        p: thispage,
        oddpk: oddpk,leg:leg_
    }, function(d) {
        sitestatus(d)
        if (d.db) {
            pages(d.page, thispage)

            if (!dbs)
                dbs = d.db;
            else {
                if (dbs.length < d.db.length)
                    dbs = d.db;
            }


            $.each(d.db, function(i, v) {
                 if (v.Match_Name != Match_Name) {
                    html += '<tr onclick="showLeg(' + v.Match_ID + ')" class="leg_bar">' +
                        '<td colspan="6" class="b_hline">' + v.Match_Name + '</td>' +
                        '</tr>';
                    Match_ID = v.Match_ID;
                }
                Match_Name = v.Match_Name;  
                html += '<tr id="TR_' + Match_ID + '">' +
                    //时间
                    ' <td rowspan="2" class="b_cen">' + v["Match_Date"] + '</td>' +
                    //主客队
                    ' <td rowspan="2" class="team_name" >' + v.Match_Master + '<br>' + v.Match_Guest + '</td>' +
                    //独赢主
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_BzM"] != v["Match_BzM"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_BzM\',' + v.Match_ID + ')" title="' + v.Match_Master + '" >' + formatNumber(v.Match_BzM, 2) + '</a></td>' +
                    //让球主让
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_Ho"] != v["Match_Ho"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "H" && v["Match_Ho"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_Ho\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Ho"], 2) + '</a></span></td>' +
                    //大
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_DxDpl"] != v["Match_DxDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxXpl"] == "0" ? "" : '大' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_DxDpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_DxDpl"], 2) + '</a></span></td>' +
                    //单
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_DsDpl"] != v["Match_DsDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsDpl"] == "0" ? "" : "单") + '<a href="javascript:;" onclick="betOrder(\'BK\',\'Match_DsDpl\',' + v.Match_ID + ')" title="单">' + formatNumber(v["Match_DsDpl"], 2) + '</a></td>' +
                    '</tr>' +
                    '<tr id="TR1_' + Match_ID + '" >' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzG"] != v["Match_BzG"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BzG\',' + v.Match_ID + ')" title="' + v.Match_Guest + '" ><font>' + formatNumber(v.Match_BzG, 2) + '</font></a></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_Ao"] != v["Match_Ao"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "C" && v["Match_Ao"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_Ao\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v["Match_Ao"], 2) + '</a></span></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_DxXpl"] != v["Match_DxXpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxGG1"] == "O" || v["Match_DxXpl"] == "0" ? "" : '小' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_DxXpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_DxXpl, 2) + '</a></span></td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_DsSpl"] != v["Match_DsSpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsSpl"] == "0" ? "" : "双") + '<a href="javascript:;" onclick="betOrder(\'BK\',\'Match_DsSpl\',' + v.Match_ID + ')" title="双">' + formatNumber(v.Match_DsSpl, 2) + '</a></td>' +
                    '</tr> ';

            })
            dbs = d.db;
            html += '</table>';

        } else {
            html += '<tr><Td colspan=9 class="b_cen">无赛事数据</td></tr>';
        }
        if (d.legname) {
            if (!leg_) getlegname(d.legname);
        }
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
        $('#data').html(html)
        $('#tablename').html('篮球单式');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}

function BasketballGG(p) {
    $('#dsorcg').val(2)
    $('#tableref').addClass('tablerefon');
    oddpk = $('#arepk').val();
    if (!oddpk)
        oddpk = 'H';
    var d_ = null;
    var Match_Name = null;
    var thispage = $('#legpage').val();
    if (p)
        thispage = p;
    var table = ''
    var html = table + '<table cellpadding="0" cellspacing="0" border="0" class="game"><tr>' +
        '<th nowrap="" class="time">时间</th>' +
        '<th nowrap="" class="team">赛事</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">全场 - 让球</th>' +
        '<th nowrap="" class="h_ou">全场 - 大小</th>' +
        '<th nowrap="" class="h_oe">单双</th>' +
        '</tr>';
    $.post('/index.php/sports/Match/BasketballGG/' + "?t=" + Math.random(), {
        p: thispage,
        oddpk: oddpk
    }, function(d) {
        sitestatus(d)
        if (d.db) {
            pages(d.page, thispage)

            if (!dbs)
                dbs = d.db;
            else {
                if (dbs.length < d.db.length)
                    dbs = d.db;
            }

            $.each(d.db, function(i, v) {

                if (v.Match_Name != Match_Name) {
                    html += '<tr style="display: ;">' +
                        '<td colspan="6" class="b_hline">' +
                        '<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="showLeg(\'' + v.Match_Name + '\')">' +
                        ' <span id="' + v.Match_Name + '" name="' + v.Match_Name + '" class="showleg">' +
                        ' <span id="LegOpen"></span>' +
                        ' </span>' +
                        '</td><td onclick="showLeg(\'' + v.Match_Name + '\')" class="leg_bar">' + v.Match_Name + '</td></tr></tbody></table>' +
                        '</td>' +
                        '</tr>';
                }
                Match_Name = v.Match_Name;
                html += '<tr id="TR_' + v.Match_ID + '">' +
                    //时间
                    ' <td rowspan="2" class="b_cen">' + v["Match_Date"] + '</td>' +
                    //主客队
                    ' <td rowspan="2" class="team_name" >' + v.Match_Master + '<br>' + v.Match_Guest + '</td>' +
                    //独赢主
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_BzM"] != v["Match_BzM"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_BzM\',' + v.Match_ID + ')" title="' + v.Match_Master + '" >' + formatNumber(v.Match_BzM, 2) + '</a></td>' +
                    //让球主让
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_Ho"] != v["Match_Ho"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "H" && v["Match_Ho"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_Ho\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Ho"], 2) + '</a></span></td>' +
                    //大
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_DxDpl"] != v["Match_DxDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxXpl"] == "0" ? "" : '大' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_DxDpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_DxDpl"], 2) + '</a></span></td>' +
                    //单
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_DsDpl"] != v["Match_DsDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsDpl"] == "0" ? "" : "单") + '<a href="javascript:;" onclick="betOrder(\'BK\',\'Match_DsDpl\',' + v.Match_ID + ')" title="单">' + formatNumber(v["Match_DsDpl"], 2) + '</a></td>' +
                    '</tr>' +
                    '<tr id="TR1_' + v.Match_ID + '" >' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzG"] != v["Match_BzG"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BzG\',' + v.Match_ID + ')" title="' + v.Match_Guest + '" ><font>' + formatNumber(v.Match_BzG, 2) + '</font></a></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_Ao"] != v["Match_Ao"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "C" && v["Match_Ao"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_Ao\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v["Match_Ao"], 2) + '</a></span></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_DxXpl"] != v["Match_DxXpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxGG1"] == "O" || v["Match_DxXpl"] == "0" ? "" : '小' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_DxXpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_DxXpl, 2) + '</a></span></td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_DsSpl"] != v["Match_DsSpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsSpl"] == "0" ? "" : "双") + '<a href="javascript:;" onclick="betOrder(\'BK\',\'Match_DsSpl\',' + v.Match_ID + ')" title="双">' + formatNumber(v.Match_DsSpl, 2) + '</a></td>' +
                    '</tr> ';

            })
            dbs = d.db;
            html += '</table>';

        } else {
            html += '<tr><Td colspan=9 class="b_cen">无赛事数据</td></tr>';
        }
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
        $('#data').html(html)
        $('#tablename').html('篮球单式过关');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}
/*排球*/
function VolleyballToday(p) {
    $('#dsorcg').val(1)
    $('#tableref').addClass('tablerefon');
    oddpk = $('#arepk').val();
    if (!oddpk)
        oddpk = 'H';
     d_ = leg_ = '';
    var Match_Name = null;
    var thispage = $('#legpage').val();
    leg = $('#legname_data_load input');
    if (leg.length > 0 && $('#tablename').html() == '排球单式') {
        $.each(leg, function(i, v) {
            if ($(this).attr('checked')) {
                leg_ += $(this).val() + '|'
            }
        })
    }
    var thispage = $('#legpage').val();
    if (p)
        thispage = p;
    var table = ''
    var html = table + '<table cellpadding="0" cellspacing="0" border="0" class="game"><tr>' +
        '<th nowrap="" class="time">时间</th>' +
        '<th nowrap="" class="team">赛事</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">全场 - 让球</th>' +
        '<th nowrap="" class="h_ou">全场 - 大小</th>' +
        '<th nowrap="" class="h_oe">单双</th>' +
        '</tr>';
    $.post('/index.php/sports/Match/VolleyballToday/' + "?t=" + Math.random(), {
        p: thispage,
        oddpk: oddpk,leg:leg_
    }, function(d) {
        sitestatus(d)
        if (d.db) {
            pages(d.page, thispage)

            if (!dbs)
                dbs = d.db;
            else {
                if (dbs.length < d.db.length)
                    dbs = d.db;
            }


            $.each(d.db, function(i, v) {

                if (v.Match_Name != Match_Name) {
                    html += '<tr style="display: ;">' +
                        '<td colspan="6" class="b_hline">' +
                        '<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="showLeg(\'' + v.Match_Name + '\')">' +
                        ' <span id="' + v.Match_Name + '" name="' + v.Match_Name + '" class="showleg">' +
                        ' <span id="LegOpen"></span>' +
                        ' </span>' +
                        '</td><td onclick="showLeg(\'' + v.Match_Name + '\')" class="leg_bar">' + v.Match_Name + '</td></tr></tbody></table>' +
                        '</td>' +
                        '</tr>';
                }
                Match_Name = v.Match_Name;
                html += '<tr id="TR_' + v.Match_ID + '">' +
                    //时间
                    ' <td rowspan="2" class="b_cen">' + v["Match_Date"] + '</td>' +
                    //主客队
                    ' <td rowspan="2" class="team_name" >' + v.Match_Master + '<br>' + v.Match_Guest + '</td>' +
                    //独赢主
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_BzM"] != v["Match_BzM"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'VB\',\'Match_BzM\',' + v.Match_ID + ')" title="' + v.Match_Master + '" >' + formatNumber(v.Match_BzM, 2) + '</a></td>' +
                    //让球主让
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_Ho"] != v["Match_Ho"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "H" && v["Match_Ho"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'VB\',\'Match_Ho\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Ho"], 2) + '</a></span></td>' +
                    //大
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_DxDpl"] != v["Match_DxDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxXpl"] == "0" ? "" : '大' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'VB\',\'Match_DxDpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_DxDpl"], 2) + '</a></span></td>' +
                    //单
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_DsDpl"] != v["Match_DsDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsDpl"] == "0" ? "" : "单") + '<a href="javascript:;" onclick="betOrder(\'VB\',\'Match_DsDpl\',' + v.Match_ID + ')" title="单">' + formatNumber(v["Match_DsDpl"], 2) + '</a></td>' +
                    '</tr>' +
                    '<tr id="TR1_' + v.Match_ID + '" >' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzG"] != v["Match_BzG"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'VB\',\'Match_BzG\',' + v.Match_ID + ')" title="' + v.Match_Guest + '" ><font>' + formatNumber(v.Match_BzG, 2) + '</font></a></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_Ao"] != v["Match_Ao"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "C" && v["Match_Ao"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'VB\',\'Match_Ao\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v["Match_Ao"], 2) + '</a></span></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_DxXpl"] != v["Match_DxXpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxGG1"] == "O" || v["Match_DxXpl"] == "0" ? "" : '小' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'VB\',\'Match_DxXpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_DxXpl, 2) + '</a></span></td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_DsSpl"] != v["Match_DsSpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsSpl"] == "0" ? "" : "双") + '<a href="javascript:;" onclick="betOrder(\'VB\',\'Match_DsSpl\',' + v.Match_ID + ')" title="双">' + formatNumber(v.Match_DsSpl, 2) + '</a></td>' +
                    '</tr> ';

            })
            dbs = d.db;
            html += '</table>';

        } else {
            html += '<tr><Td colspan=9 class="b_cen">无赛事数据</td></tr>';
        }
        if (d.legname) {
            if (!leg_) getlegname(d.legname);
        }
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
        $('#data').html(html)
        $('#tablename').html('排球单式');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}
/*排球*/
function TennisToday(p) {
    $('#dsorcg').val(1)
    $('#tableref').addClass('tablerefon');
    oddpk = $('#arepk').val();
    if (!oddpk)
        oddpk = 'H';
     d_ = leg_ = '';
    var Match_Name = null;
    var thispage = $('#legpage').val();
    leg = $('#legname_data_load input');
    if (leg.length > 0 && $('#tablename').html() == '网球单式') {
        $.each(leg, function(i, v) {
            if ($(this).attr('checked')) {
                leg_ += $(this).val() + '|'
            }
        })
    }
    var thispage = $('#legpage').val();
    if (p)
        thispage = p;
    var table = ''
    var html = table + '<table cellpadding="0" cellspacing="0" border="0" class="game"><tr>' +
        '<th nowrap="" class="time">时间</th>' +
        '<th nowrap="" class="team">赛事</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">全场 - 让球</th>' +
        '<th nowrap="" class="h_ou">全场 - 大小</th>' +
        '<th nowrap="" class="h_oe">单双</th>' +
        '</tr>';
    $.post('/index.php/sports/Match/TennisToday/' + "?t=" + Math.random(), {
        p: thispage,
        oddpk: oddpk,leg:leg_
    }, function(d) {
        sitestatus(d)
        if (d.db) {
            pages(d.page, thispage)

            if (!dbs)
                dbs = d.db;
            else {
                if (dbs.length < d.db.length)
                    dbs = d.db;
            }


            $.each(d.db, function(i, v) {

                if (v.Match_Name != Match_Name) {
                    html += '<tr style="display: ;">' +
                        '<td colspan="6" class="b_hline">' +
                        '<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="showLeg(\'' + v.Match_Name + '\')">' +
                        ' <span id="' + v.Match_Name + '" name="' + v.Match_Name + '" class="showleg">' +
                        ' <span id="LegOpen"></span>' +
                        ' </span>' +
                        '</td><td onclick="showLeg(\'' + v.Match_Name + '\')" class="leg_bar">' + v.Match_Name + '</td></tr></tbody></table>' +
                        '</td>' +
                        '</tr>';
                }
                Match_Name = v.Match_Name;
                html += '<tr id="TR_' + v.Match_ID + '">' +
                    //时间
                    ' <td rowspan="2" class="b_cen">' + v["Match_Date"] + '</td>' +
                    //主客队
                    ' <td rowspan="2" class="team_name" >' + v.Match_Master + '<br>' + v.Match_Guest + '</td>' +
                    //独赢主
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_BzM"] != v["Match_BzM"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'TN\',\'Match_BzM\',' + v.Match_ID + ')" title="' + v.Match_Master + '" >' + formatNumber(v.Match_BzM, 2) + '</a></td>' +
                    //让球主让
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_Ho"] != v["Match_Ho"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "H" && v["Match_Ho"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'TN\',\'Match_Ho\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Ho"], 2) + '</a></span></td>' +
                    //大
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_DxDpl"] != v["Match_DxDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxXpl"] == "0" ? "" : '大' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'TN\',\'Match_DxDpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_DxDpl"], 2) + '</a></span></td>' +
                    //单
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_DsDpl"] != v["Match_DsDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsDpl"] == "0" ? "" : "单") + '<a href="javascript:;" onclick="betOrder(\'TN\',\'Match_DsDpl\',' + v.Match_ID + ')" title="单">' + formatNumber(v["Match_DsDpl"], 2) + '</a></td>' +
                    '</tr>' +
                    '<tr id="TR1_' + v.Match_ID + '" >' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzG"] != v["Match_BzG"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'TN\',\'Match_BzG\',' + v.Match_ID + ')" title="' + v.Match_Guest + '" ><font>' + formatNumber(v.Match_BzG, 2) + '</font></a></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_Ao"] != v["Match_Ao"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "C" && v["Match_Ao"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'TN\',\'Match_Ao\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v["Match_Ao"], 2) + '</a></span></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_DxXpl"] != v["Match_DxXpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxGG1"] == "O" || v["Match_DxXpl"] == "0" ? "" : '小' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'TN\',\'Match_DxXpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_DxXpl, 2) + '</a></span></td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_DsSpl"] != v["Match_DsSpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsSpl"] == "0" ? "" : "双") + '<a href="javascript:;" onclick="betOrder(\'TN\',\'Match_DsSpl\',' + v.Match_ID + ')" title="双">' + formatNumber(v.Match_DsSpl, 2) + '</a></td>' +
                    '</tr> ';

            })
            dbs = d.db;
            html += '</table>';

        } else {
            html += '<tr><Td colspan=9 class="b_cen">无赛事数据</td></tr>';
        }
        if (d.legname) {
            if (!leg_) getlegname(d.legname);
        }
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
        $('#data').html(html)
        $('#tablename').html('网球单式');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}
function BaseballToday(p) {
    $('#dsorcg').val(1)
    $('#tableref').addClass('tablerefon');
    oddpk = $('#arepk').val();
    if (!oddpk)
        oddpk = 'H';
     d_ = leg_ = '';
    var Match_Name = null;
    var thispage = $('#legpage').val();
    leg = $('#legname_data_load input');
    if (leg.length > 0 && $('#tablename').html() == '棒球单式') {
        $.each(leg, function(i, v) {
            if ($(this).attr('checked')) {
                leg_ += $(this).val() + '|'
            }
        })
    }
    var thispage = $('#legpage').val();
    if (p)
        thispage = p;
    var table = ''
    var html = table + '<table cellpadding="0" cellspacing="0" border="0" class="game"><tr>' +
        '<th nowrap="" class="time">时间</th>' +
        '<th nowrap="" class="team">赛事</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">全场 - 让球</th>' +
        '<th nowrap="" class="h_ou">全场 - 大小</th>' +
        '<th nowrap="" class="h_oe">单双</th>' +
        '</tr>';
    $.post('/index.php/sports/Match/BaseballToday/' + "?t=" + Math.random(), {
        p: thispage,
        oddpk: oddpk,leg:leg_
    }, function(d) {
        sitestatus(d)
        if (d.db) {
            pages(d.page, thispage)

            if (!dbs)
                dbs = d.db;
            else {
                if (dbs.length < d.db.length)
                    dbs = d.db;
            }


            $.each(d.db, function(i, v) {
                 if (v.Match_Name != Match_Name) {
                    html += '<tr onclick="showLeg(' + v.Match_ID + ')" class="leg_bar">' +
                        '<td colspan="6" class="b_hline">' + v.Match_Name + '</td>' +
                        '</tr>';
                    Match_ID = v.Match_ID;
                }
                Match_Name = v.Match_Name;  
                html += '<tr id="TR_' + Match_ID + '">' +
                    //时间
                    ' <td rowspan="2" class="b_cen">' + v["Match_Date"] + '</td>' +
                    //主客队
                    ' <td rowspan="2" class="team_name" >' + v.Match_Master + '<br>' + v.Match_Guest + '</td>' +
                    //独赢主
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_BzM"] != v["Match_BzM"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_BzM\',' + v.Match_ID + ')" title="' + v.Match_Master + '" >' + formatNumber(v.Match_BzM, 2) + '</a></td>' +
                    //让球主让
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_Ho"] != v["Match_Ho"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "H" && v["Match_Ho"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BB\',\'Match_Ho\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Ho"], 2) + '</a></span></td>' +
                    //大
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_DxDpl"] != v["Match_DxDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxXpl"] == "0" ? "" : '大' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BB\',\'Match_DxDpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_DxDpl"], 2) + '</a></span></td>' +
                    //单
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_DsDpl"] != v["Match_DsDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsDpl"] == "0" ? "" : "单") + '<a href="javascript:;" onclick="betOrder(\'BB\',\'Match_DsDpl\',' + v.Match_ID + ')" title="单">' + formatNumber(v["Match_DsDpl"], 2) + '</a></td>' +
                    '</tr>' +
                    '<tr id="TR1_' + Match_ID + '" >' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzG"] != v["Match_BzG"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BzG\',' + v.Match_ID + ')" title="' + v.Match_Guest + '" ><font>' + formatNumber(v.Match_BzG, 2) + '</font></a></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_Ao"] != v["Match_Ao"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "C" && v["Match_Ao"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BB\',\'Match_Ao\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v["Match_Ao"], 2) + '</a></span></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_DxXpl"] != v["Match_DxXpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxGG1"] == "O" || v["Match_DxXpl"] == "0" ? "" : '小' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BB\',\'Match_DxXpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_DxXpl, 2) + '</a></span></td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_DsSpl"] != v["Match_DsSpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsSpl"] == "0" ? "" : "双") + '<a href="javascript:;" onclick="betOrder(\'BB\',\'Match_DsSpl\',' + v.Match_ID + ')" title="双">' + formatNumber(v.Match_DsSpl, 2) + '</a></td>' +
                    '</tr> ';

            })
            dbs = d.db;
            html += '</table>';

        } else {
            html += '<tr><Td colspan=9 class="b_cen">无赛事数据</td></tr>';
        }
        if (d.legname) {
            if (!leg_) getlegname(d.legname);
        }
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
        $('#data').html(html)
        $('#tablename').html('棒球单式');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}

/*交易状况*/
function getbet() {
    getbetdata(1);
    easyDialog.open({
        container: 'showbet',
        fixed: true,
        drag: true,
        follow: 'transaction',
        followX : -107,
        followY : 24
    });
}

function getbetdata(p) {

    dsorcg = $('#bettype').val()
    status = $('#status').val()
    if (p) betpage = p
    else betpage = $('#betpage').val()
    $.post('/index.php/sports/main/bet', {
        betpage: betpage,
        status: status,
        dsorcg: dsorcg,
        uid: $('#uid').val(),
        token: $('#token').val()
    }, function(d) {
        sitestatus(d)
        data = '';
        page = number = bg = bg_ = '';
        for (var i = 1; i <= d.page; i++) {
            page += '<option ' + (i == betpage ? 'selected' : '') + '>' + i + '</option>'
        };
        cgi=0;
        $('#betpage').html(page)
        data += '<table class="tablebet">'
        data += '<tr><th>联赛</th><th>信息</th><th>下注日期</th><th>下注金额</th><th>交易金额</th><th>可赢</th><th>派彩</th><th>结果</th></tr>'
        $.each(d.d, function(i, v) {
            if (number == v.number) {
                //bg = 'bg2';
                //bg_ = bg;
            } else {
                cgi++;
               // bg = 'bg5';
               // bg_ = bg;
            }
            if(cgi%2==0) {bg = 'bg5';cgi=0;}
            else{bg = '';}
            if (dsorcg == 1) status = v.status;
            else status = v.status2;
            if (status == 0) status = '未结算';
            else if (status == 1) status = '<span style="color:#FF0000;">赢</span>';
            else if (status == 2) status = '<span style="color:#00CC00;">输</span>';
            else if (status == 8) status = '和局';
            else if (status == 3) status = '注单无效';
            else if (status == 4) status = '<span style="color:#FF0000;">赢一半</span>';
            else if (status == 5) status = '<span style="color:#00CC00;">输一半</span>';
            else if (status == 6) status = '进球无效';
            else if (status == 7) status = '红卡取消';
            data += '<tr onmouseover="this.className=\'bg4\'" onmouseout="this.className=\'bg3\'">';
            if (number == v.number) number_ = '';
            else number_ = v.number ; 
            if(v.bet_point<0)xz_money=(v.bet_win-v.bet_money).toFixed(2);
            else xz_money=v.bet_money
            data += '<td class="'+bg+'">' + v.match_name + '<br>' + v.master_guest + '<br>'+number_+'</td>';
            data += '<td class="'+bg+'">' + v.ball_sort + '<br>' + v.bet_info + '</td>';
            data += '<td class="'+bg+'">' + v.bet_time + '</td>';
            data += '<td class="'+bg+'">' + xz_money + '</td>';
            data += '<td class="'+bg+'">' + v.bet_money + '</td>';
            data += '<td class="'+bg+'">' + v.bet_win + '</td>';
            data += '<td class="'+bg+'">' + v.win + '</td>';
            data += '<td class="'+bg+'">' + status + '</td>';
            data += '</tr>';
            number = v.number;
        })
        data += '</table>'
        $("#showbetdata").html(data)
    }, 'json')

}
/**
 * 選擇多盤口時 轉換成該選擇賠率
 * @param odd_type  選擇盤口
 * @param iorH      主賠率
 * @param iorC      客賠率
 * @param show      顯示位數
 * @return      回傳陣列 0-->H  ,1-->C
 */
function get_other_ioratio(odd_type, iorH, iorC, showior) {
    var out = new Array();
    if (iorH != "" || iorC != "") {
        out = chg_ior(odd_type, iorH, iorC, showior);
    } else {
        out[0] = iorH;
        out[1] = iorC;
    }
    return out;
}
/**
 * 轉換賠率
 * @param odd_f
 * @param H_ratio
 * @param C_ratio
 * @param showior
 * @return
 */

function chg_ior(odd_f, iorH, iorC, showior) {
    //console.log("1. "+odd_f+"<>"+iorH+"<>"+iorC+"<>"+showior);
    iorH = Math.floor((iorH * 1000) + 0.001) / 1000;
    iorC = Math.floor((iorC * 1000) + 0.001) / 1000;

    var ior = new Array();
    if (iorH < 11)
        iorH *= 1000;
    if (iorC < 11)
        iorC *= 1000;
    iorH = parseFloat(iorH);
    iorC = parseFloat(iorC);
    switch (odd_f) {
        case "H": //香港變盤(輸水盤)
            ior = get_HK_ior(iorH, iorC);
            break;
        case "M": //馬來盤
            ior = get_MA_ior(iorH, iorC);
            break;
        case "I": //印尼盤
            ior = get_IND_ior(iorH, iorC);
            break;
        case "E": //歐洲盤
            ior = get_EU_ior(iorH, iorC);
            break;
        default: //香港盤
            ior[0] = iorH;
            ior[1] = iorC;
    }
    ior[0] /= 1000;
    ior[1] /= 1000;

    ior[0] = printf(Decimal_point(ior[0], showior), iorpoints);
    ior[1] = printf(Decimal_point(ior[1], showior), iorpoints);
    //alert("odd_f="+odd_f+",iorH="+iorH+",iorC="+iorC+",ouH="+ior[0]+",ouC="+ior[1]);
    return ior;
}

/**
 * 換算成輸水盤賠率
 * @param H_ratio
 * @param C_ratio
 * @return
 */

function get_HK_ior(H_ratio, C_ratio) {
    var out_ior = new Array();
    var line, lowRatio, nowRatio, highRatio;
    var nowType = "";
    if (H_ratio <= 1000 && C_ratio <= 1000) {
        out_ior[0] = H_ratio;
        out_ior[1] = C_ratio;
        return out_ior;
    }
    line = 2000 - (H_ratio + C_ratio);

    if (H_ratio > C_ratio) {
        lowRatio = C_ratio;
        nowType = "C";
    } else {
        lowRatio = H_ratio;
        nowType = "H";
    }
    if (((2000 - line) - lowRatio) > 1000) {
        //對盤馬來盤
        nowRatio = (lowRatio + line) * (-1);
    } else {
        //對盤香港盤
        nowRatio = (2000 - line) - lowRatio;
    }

    if (nowRatio < 0) {
        highRatio = Math.floor(Math.abs(1000 / nowRatio) * 1000);
    } else {
        highRatio = (2000 - line - nowRatio);
    }
    if (nowType == "H") {
        out_ior[0] = lowRatio;
        out_ior[1] = highRatio;
    } else {
        out_ior[0] = highRatio;
        out_ior[1] = lowRatio;
    }
    return out_ior;
}
/**
 * 換算成馬來盤賠率
 * @param H_ratio
 * @param C_ratio
 * @return
 */

function get_MA_ior(H_ratio, C_ratio) {
    var out_ior = new Array();
    var line, lowRatio, highRatio;
    var nowType = "";
    if ((H_ratio <= 1000 && C_ratio <= 1000)) {
        out_ior[0] = H_ratio;
        out_ior[1] = C_ratio;
        return out_ior;
    }
    line = 2000 - (H_ratio + C_ratio);
    if (H_ratio > C_ratio) {
        lowRatio = C_ratio;
        nowType = "C";
    } else {
        lowRatio = H_ratio;
        nowType = "H";
    }
    highRatio = (lowRatio + line) * (-1);
    if (nowType == "H") {
        out_ior[0] = lowRatio;
        out_ior[1] = highRatio;
    } else {
        out_ior[0] = highRatio;
        out_ior[1] = lowRatio;
    }
    return out_ior;
}
/**
 * 換算成印尼盤賠率
 * @param H_ratio
 * @param C_ratio
 * @return
 */

function get_IND_ior(H_ratio, C_ratio) {
    var out_ior = new Array();
    out_ior = get_HK_ior(H_ratio, C_ratio);
    H_ratio = out_ior[0];
    C_ratio = out_ior[1];
    H_ratio /= 1000;
    C_ratio /= 1000;
    if (H_ratio < 1) {
        H_ratio = (-1) / H_ratio;
    }
    if (C_ratio < 1) {
        C_ratio = (-1) / C_ratio;
    }
    out_ior[0] = H_ratio * 1000;
    out_ior[1] = C_ratio * 1000;
    return out_ior;
}
/**
 * 換算成歐洲盤賠率
 * @param H_ratio
 * @param C_ratio
 * @return
 */

function get_EU_ior(H_ratio, C_ratio) {
    var out_ior = new Array();
    out_ior = get_HK_ior(H_ratio, C_ratio);
    H_ratio = out_ior[0];
    C_ratio = out_ior[1];
    out_ior[0] = H_ratio + 1000;
    out_ior[1] = C_ratio + 1000;
    return out_ior;
}
/*
 去正負號做小數第幾位捨去
 進來的值是小數值
 */

function Decimal_point(tmpior, show) {
    var sign = "";
    sign = ((tmpior < 0) ? "Y" : "N");
    tmpior = (Math.floor(Math.abs(tmpior) * show + 1 / show)) / show;
    return (tmpior * ((sign == "Y") ? -1 : 1));
}


/*
 公用 FUNC
 */
function printf(vals, points) { //小數點位數
    vals = "" + vals;
    var cmd = new Array();
    cmd = vals.split(".");
    if (cmd.length > 1) {
        for (ii = 0; ii < (points - cmd[1].length); ii++)
            vals = vals + "0";
    } else {
        vals = vals + ".";
        for (ii = 0; ii < points; ii++)
            vals = vals + "0";
    }
    return vals;
}

/*足球总入球*/
function FootballZRQ(p) {
    $('#dsorcg').val(1)
    $('#tableref').addClass('tablerefon');
    oddpk = $('#arepk').val();
    if (!oddpk)
        oddpk = 'H';

    d_ = leg_ = '';
    var Match_Name = null;
    var thispage = $('#legpage').val();
    leg = $('#legname_data_load input');
    if (leg.length > 0 && $('#tablename').html() == '足球总入球') {
        $.each(leg, function(i, v) {
            if ($(this).attr('checked')) {
                leg_ += $(this).val() + '|'
            }
        })
    }
    $('#tablename').html('足球总入球');
    var thispage = $('#legpage').val();
    if (p)
        thispage = p;
    var table = ''
    var html = table + '<table cellpadding="0" cellspacing="0" border="0" class="game"><tr>' +
        '<th nowrap="" class="time">时间</th>' +
        '<th nowrap="" class="team">主客队</th>' +
        '<th nowrap="" class="h_1x2">0~1</th>' +
        '<th nowrap="" class="h_r">2~3</th>' +
        '<th nowrap="" class="h_ou">4~6</th>' +
        '<th nowrap="" class="h_oe">7或以上</th>' +
        '</tr>';
    $.post('/index.php/sports/Match/FootballZRQ/' + "?t=" + Math.random(), {
        p: thispage,
        oddpk: oddpk,leg:leg_
    }, function(d) {
        sitestatus(d)
        if (d.db) {
            pages(d.page, thispage)

            if (!dbs)
                dbs = d.db;
            else {
                if (dbs.length < d.db.length)
                    dbs = d.db;
            }

            $.each(d.db, function(i, v) {

                if (v.Match_Name != Match_Name) {
                    html += '<tr style="display: ;">' +
                        '<td colspan="6" class="b_hline">' +
                        '<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="showLeg(\'' + v.Match_Name + '\')">' +
                        ' <span id="' + v.Match_Name + '" name="' + v.Match_Name + '" class="showleg">' +
                        ' <span id="LegOpen"></span>' +
                        ' </span>' +
                        '</td><td onclick="showLeg(\'' + v.Match_Name + '\')" class="leg_bar">' + v.Match_Name + '</td></tr></tbody></table>' +
                        '</td>' +
                        '</tr>';
                }
                Match_Name = v.Match_Name;
                html += '<tr>' +
                    '<td class="b_cen">' + v.Match_Date + '</td>    ' +
                    '<td class="b_cen">' + v.Match_Master + '<br>' + v.Match_Guest + '</td> ' +
                    '<td class="b_cen"><a href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Total01Pl\',' + v.Match_ID + ')"  title="0~1"  >' + v.Match_Total01Pl + '</a></td>' +
                    '<td class="b_cen"><a href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Total23Pl\',' + v.Match_ID + ')"  title="2~3"  >' + v.Match_Total23Pl + '</a></td>' +
                    '<td class="b_cen"><a href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Total46Pl\',' + v.Match_ID + ')"  title="4~6"  >' + v.Match_Total46Pl + '</a></td>' +
                    '<td class="b_cen"><a href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Total7upPl\',' + v.Match_ID + ')" title="7以上">' + v.Match_Total7upPl + '</a></td>' +
                    '</tr>';
            })
            dbs = d.db;
            if (d.legname) {
                if (!leg_) getlegname(d.legname);
            }
           
                //$('#load_alpha').hide();
        }
        else {
            html += '<tr><Td colspan=18 class="b_cen">无赛事数据</td></tr>';
        }
        html += '</table>';
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
        $('#data').html(html)
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}
/*足球波胆*/
function FootballBD(p) {

    $('#dsorcg').val(1)
    $('#tableref').addClass('tablerefon');
    oddpk = $('#arepk').val();
    if (!oddpk)
        oddpk = 'H';
    d_ = leg_ = '';
    var Match_Name = null;
    var thispage = $('#legpage').val();
    leg = $('#legname_data_load input');
    if (leg.length > 0 && $('#tablename').html() == '足球波胆') {
        $.each(leg, function(i, v) {
            if ($(this).attr('checked')) {
                leg_ += $(this).val() + '|'
            }
        })
    }
    $('#tablename').html('足球波胆');
    if (p)
        thispage = p;
    var table = ''
    var html = table + '<table cellpadding="0" cellspacing="0" border="0" class="game"><tr>' +
        '<th class="time">时间</th>' +
        '<th style="width:120px;" class="time">主客队伍</th>' +
        '<th class="h_1x2">1:0</th>' +
        '<th class="h_1x2">2:0</th>' +
        '<th class="h_1x2">2:1</th>' +
        '<th class="h_1x2">3:0</th>' +
        '<th class="h_1x2">3:1</th>' +
        '<th class="h_1x2">3:2</th>' +
        '<th class="h_1x2">4:0</th>' +
        '<th class="h_1x2">4:1</th>' +
        '<th class="h_1x2">4:2</th>' +
        '<th class="h_1x2">4:3</th>' +
        '<th class="h_1x2">0:0</th>' +
        '<th class="h_1x2">1:1</th>' +
        '<th class="h_1x2">2:2</th>' +
        '<th class="h_1x2">3:3</th>' +
        '<th class="h_1x2">4:4</th>' +
        '<th class="h_1x2">其他</th>' +
        '</tr>';
    $.post('/index.php/sports/Match/FootballBD/' + "?t=" + Math.random(), {
        p: thispage,
        oddpk: oddpk,
        leg: leg_
    }, function(d) {
        sitestatus(d)
        if (d.db) {
            pages(d.page, thispage)

            if (!dbs)
                dbs = d.db;
            else {
                if (dbs.length < d.db.length)
                    dbs = d.db;
            }

            $.each(d.db, function(i, v) {

                if (v.Match_Name != Match_Name) {
                    html += '<tr style="display: ;">' +
                        '<td colspan="18" class="b_hline">' +
                        '<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="showLeg(\'' + v.Match_Name + '\')">' +
                        ' <span id="' + v.Match_Name + '" name="' + v.Match_Name + '" class="showleg">' +
                        ' <span id="LegOpen"></span>' +
                        ' </span>' +
                        '</td><td onclick="showLeg(\'' + v.Match_Name + '\')" class="leg_bar">' + v.Match_Name + '</td></tr></tbody></table>' +
                        '</td>' +
                        '</tr>';
                }
                Match_Name = v.Match_Name;
                html += '<tr id="TR_01-1">' +
                    '<td rowspan="2" class="b_cen">' + v.Match_Date + '</td>' +
                    '<td width="13%" rowspan="2" class="b_cen">' + v.Match_Master + '<br>' + v.Match_Guest + '</td>' +
                    '<td width="5%" class="b_cen"><a title="1:0" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd10\',' + v.Match_ID + ')"  >' + v.Match_Bd10 + '</a></td>' +
                    '<td width="5%" class="b_cen"><a title="2:0" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd20\',' + v.Match_ID + ')">' + v.Match_Bd20 + '</a></td>' +
                    '<td width="5%" class="b_cen"><a title="2:1" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd21\',' + v.Match_ID + ')">' + v.Match_Bd21 + '</a></td>' +
                    '<td width="5%" class="b_cen"><a title="3:0" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd30\',' + v.Match_ID + ')">' + v.Match_Bd30 + '</a></td>' +
                    '<td width="5%" class="b_cen"><a title="3:1" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd31\',' + v.Match_ID + ')">' + v.Match_Bd31 + '</a></td>' +
                    '<td width="5%" class="b_cen"><a title="3:2" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd32\',' + v.Match_ID + ')">' + v.Match_Bd32 + '</a></td>' +
                    '<td width="5%" class="b_cen"><a title="4:0" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd40\',' + v.Match_ID + ')">' + v.Match_Bd40 + '</a></td>' +
                    '<td width="5%" class="b_cen"><a title="4:1" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd41\',' + v.Match_ID + ')">' + v.Match_Bd41 + '</a></td>' +
                    '<td width="5%" class="b_cen"><a title="4:2" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd42\',' + v.Match_ID + ')">' + v.Match_Bd42 + '</a></td>' +
                    '<td width="5%" class="b_cen"><a title="4:3" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd43\',' + v.Match_ID + ')">' + v.Match_Bd43 + '</a></td>' +
                    '<td width="5%" rowspan="2" class="b_cen"><a  title="0:0" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd00\',' + v.Match_ID + ')">' + v.Match_Bd00 + '</a></td>' +
                    '<td width="5%" rowspan="2" class="b_cen"><a  title="1:1" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd10\',' + v.Match_ID + ')">' + v.Match_Bd10 + '</a></td>   ' +
                    '<td width="5%" rowspan="2" class="b_cen"><a  title="2:2" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd22\',' + v.Match_ID + ')">' + v.Match_Bd22 + '</a></td>' +
                    '<td width="5%" rowspan="2" class="b_cen"><a  title="3:3" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd33\',' + v.Match_ID + ')">' + v.Match_Bd33 + '</a></td>' +
                    '<td width="5%" rowspan="2" class="b_cen"><a  title="4:4" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd44\',' + v.Match_ID + ')">' + v.Match_Bd44 + '</a></td>' +
                    '<td width="5%" rowspan="2" class="b_cen"><a  title="其它比分" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bdup5\',' + v.Match_ID + ')">' + v.Match_Bdup5 + '</a></td>' +
                    '</tr>' +
                    '<tr id="TR_01-1">' +
                    '<td class="b_cen"><a style=""  title="0:1" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg10\',' + v.Match_ID + ')">' + v.Match_Bdg10 + '</a></td>' +
                    '<td class="b_cen"><a style=""  title="0:2" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg20\',' + v.Match_ID + ')">' + v.Match_Bdg20 + '</a></td>' +
                    '<td class="b_cen"><a style=""  title="1:2" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg21\',' + v.Match_ID + ')">' + v.Match_Bdg21 + '</a></td>' +
                    '<td class="b_cen"><a style=""  title="0:3" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg30\',' + v.Match_ID + ')">' + v.Match_Bdg30 + '</a></td>' +
                    '<td class="b_cen"><a style=""  title="1:3" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg31\',' + v.Match_ID + ')">' + v.Match_Bdg31 + '</a></td>' +
                    '<td class="b_cen"><a style=""  title="2:3" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg32\',' + v.Match_ID + ')">' + v.Match_Bdg32 + '</a></td>' +
                    '<td class="b_cen"><a style=""  title="0:4" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg40\',' + v.Match_ID + ')">' + v.Match_Bdg40 + '</a></td>' +
                    '<td class="b_cen"><a style=""  title="1:4" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg41\',' + v.Match_ID + ')">' + v.Match_Bdg41 + '</a></td>' +
                    '<td class="b_cen"><a style=""  title="2:4" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg42\',' + v.Match_ID + ')">' + v.Match_Bdg42 + '</a></td>' +
                    '<td class="b_cen"><a style=""  title="3:4" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg43\',' + v.Match_ID + ')">' + v.Match_Bdg43 + '</a></td>' +
                    '</tr>';


            })
            if (d.legname) {
                if (!leg_) getlegname(d.legname);
            }
            dbs = d.db;
            
            // $('#load_alpha').hide();
        } else {
            html += '<tr><Td colspan=18 class="b_cen">无赛事数据</td></tr>';
        }
        html += '</table>';
            $('#tableref').removeClass('tablerefon');
            $('#tableref').addClass('tableref');



            $('#data').html(html)

    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}

function Footballnews() {
    $.post('/index.php/sports/Match/Fbnews/' + "?t=" + Math.random(), function(d) {
        sitestatus(d)
        if (d) {

            $('#real_msg').html(d);
        }
    }, 'json')

}
/*早餐赛事足球独赢*/
function FootballMorning(p) {
    $('#dsorcg').val(1)
    $('#tableref').addClass('tablerefon');
    oddpk = $('#arepk').val();
    if (!oddpk)
        oddpk = 'H';
    d_ = leg_ = '';
    var Match_Name = null;
    var thispage = $('#legpage').val();
    leg = $('#legname_data_load input');
    if (leg.length > 0 && $('#tablename').html() == '足球单式') {
        $.each(leg, function(i, v) {
            if ($(this).attr('checked')) {
                leg_ += $(this).val() + '|'
            }
        })
    }
    if (p)
        thispage = p;
    var table = '';
    var html = table + '<table cellpadding="0" cellspacing="0" border="0" class="game"><tr>' +
        '<th nowrap="" class="time">时间</th>' +
        '<th nowrap="" class="team">赛事</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">全场 - 让球</th>' +
        '<th nowrap="" class="h_ou">全场 - 大小</th>' +
        '<th nowrap="" class="h_oe">单双</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">半场 - 让球</th>' +
        '<th nowrap="" class="h_ou">半场 - 大小</th>' +
        '</tr>';
    $.post('/index.php/sports/Match/FootballMorning/' + "?t=" + Math.random(), {
        p: thispage,
        oddpk: oddpk,
        leg: leg_
    }, function(d) {
        sitestatus(d)
        if (d.db) {
            pages(d.page, thispage)

            if (!dbs)
                dbs = d.db;
            else {
                if (dbs.length < d.db.length)
                    dbs = d.db;
            }

            $.each(d.db, function(i, v) {

                if (v.Match_Name != Match_Name) {
                    html += '<tr onclick="showLeg(' + v.Match_ID + ')" class="leg_bar">' +
                        '<td colspan="9" class="b_hline">' + v.Match_Name + '</td>' +
                        '</tr>';
                    Match_ID = v.Match_ID;
                }
                Match_Name = v.Match_Name;

                html += '<tr id="TR_' + Match_ID + '">' +
                    //时间
                    ' <td rowspan="3" class="b_cen">' + v.Match_Date + '</td>' +
                    //主客队
                    ' <td rowspan="2" class="team_name" >' + v.Match_Master + '<br>' + v.Match_Guest + '</td>' +
                    //独赢主
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_BzM"] != v["Match_BzM"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BzM\',' + v.Match_ID + ')" title="' + v.Match_Master + '" >' + formatNumber(v.Match_BzM, 2) + '</a></td>' +
                    //让球主让
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_Ho"] != v["Match_Ho"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "H" && v["Match_Ho"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Ho\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Ho"], 2) + '</a></span></td>' +
                    //大
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_DxDpl"] != v["Match_DxDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxXpl"] == "0" ? "" : '大' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_DxDpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_DxDpl"], 2) + '</a></span></td>' +
                    //单
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_DsDpl"] != v["Match_DsDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsDpl"] == "0" ? "" : "单") + '<a href="javascript:;" onclick="betOrder(\'FT\',\'Match_DsDpl\',' + v.Match_ID + ')" title="单">' + formatNumber(v["Match_DsDpl"], 2) + '</a></td>' +
                    //半场独赢主
                    ' <td class="b_1st" style=" ' + (dbs[i]["Match_Bmdy"] != v["Match_Bmdy"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bmdy\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Bmdy"], 2) + '</a></td>' +
                    //半场让球主
                    ' <td class="b_1stR" style=" ' + (dbs[i]["Match_BHo"] != v["Match_BHo"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_Hr_ShowType"] == "H" && v["Match_BHo"] != 0) ? v.Match_BRpk : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BHo\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_BHo"], 2) + '</a></span></td>' +
                    //半场 大
                    ' <td class="b_1stR" style=" ' + (dbs[i]["Match_Bdpl"] != v["Match_Bdpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_Bdxpk1"]) ? "大" + v["Match_Bdxpk1"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bdpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_Bdpl"], 2) + '</a></span></td>' +
                    '</tr>' +
                    '<tr id="TR1_' + Match_ID + '" >' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzG"] != v["Match_BzG"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BzG\',' + v.Match_ID + ')" title="' + v.Match_Guest + '" ><font>' + formatNumber(v.Match_BzG, 2) + '</font></a></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_Ao"] != v["Match_Ao"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "C" && v["Match_Ao"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Ao\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v["Match_Ao"], 2) + '</a></span></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_DxXpl"] != v["Match_DxXpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxGG1"] == "O" || v["Match_DxXpl"] == "0" ? "" : '小' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_DxXpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_DxXpl, 2) + '</a></span></td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_DsSpl"] != v["Match_DsSpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsSpl"] == "0" ? "" : "双") + '<a href="javascript:;" onclick="betOrder(\'FT\',\'Match_DsSpl\',' + v.Match_ID + ')" title="双">' + formatNumber(v.Match_DsSpl, 2) + '</a></td>' +
                    '  <td class="b_1st" style=" ' + (dbs[i]["Match_Bgdy"] != v["Match_Bgdy"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bgdy\',' + v.Match_ID + ')" title="' + v.Match_Guest + '"><font>' + formatNumber(v.Match_Bgdy, 2) + '</font></a></td>' +
                    '  <td class="b_1stR" style=" ' + (dbs[i]["Match_BAo"] != v["Match_BAo"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_Hr_ShowType"] == "C" && v["Match_BAo"] != 0) ? v.Match_BRpk : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BAo\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v.Match_BAo, 2) + '</a></span></td>' +
                    '  <td class="b_1stR" style=" ' + (dbs[i]["Match_Bxpl"] != v["Match_Bxpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_Bdxpk1"]) ? "小" + v["Match_Bdxpk1"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bxpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_Bxpl, 2) + '</a></span></td>' +
                    '</tr>' +
                    '<tr id="TR2_' + Match_ID + '" >' +
                    '  <td class="team_name" id="TR_11-1350180_1">和局</td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzH"] != v["Match_BzH"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BzH\',' + v.Match_ID + ')" title="和"><font>' + formatNumber(v.Match_BzH, 2) + '</font></a></td>' +
                    '  <td colspan="3" valign="top" class="b_cen"></td>' +
                    '  <td class="b_1st" style=" ' + (dbs[i]["Match_Bhdy"] != v["Match_Bhdy"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bhdy\',' + v.Match_ID + ')" title="和">' + formatNumber(v.Match_Bhdy, 2) + '</a></td>' +
                    '  <td colspan="2" valign="top" class="b_1st">&nbsp;</td>' +
                    '</tr>';

            })
            dbs = d.db;
            html += '</table>';

            //$('#load_alpha').hide();
        } else {
            html += '<tr><Td colspan=9 class="b_cen">无赛事数据</td></tr>';
        }
        if (d.legname) {
            if (!leg_) getlegname(d.legname);
        }
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');

        $('#data').html(html)
        $('#tablename').html('足球单式');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}

/*早餐足球波胆*/
function FootballMBD(p) {
    $('#dsorcg').val(1)
    $('#tableref').addClass('tablerefon');
    oddpk = $('#arepk').val();
    if (!oddpk)
        oddpk = 'H';
    d_ = leg_ = '';
    var Match_Name = null;
    var thispage = $('#legpage').val();
    leg = $('#legname_data_load input');
    if (leg.length > 0 && $('#tablename').html() == '足球波胆') {
        $.each(leg, function(i, v) {
            if ($(this).attr('checked')) {
                leg_ += $(this).val() + '|'
            }
        })
    }
    $('#tablename').html('足球波胆');
    if (p)
        thispage = p;
    var table = ''
    var html = table + '<table cellpadding="0" cellspacing="0" border="0" class="game"><tr>' +
        '<th class="time">时间</th>' +
        '<th style="width:120px;" class="time">主客队伍</th>' +
        '<th class="h_1x2">1:0</th>' +
        '<th class="h_1x2">2:0</th>' +
        '<th class="h_1x2">2:1</th>' +
        '<th class="h_1x2">3:0</th>' +
        '<th class="h_1x2">3:1</th>' +
        '<th class="h_1x2">3:2</th>' +
        '<th class="h_1x2">4:0</th>' +
        '<th class="h_1x2">4:1</th>' +
        '<th class="h_1x2">4:2</th>' +
        '<th class="h_1x2">4:3</th>' +
        '<th class="h_1x2">0:0</th>' +
        '<th class="h_1x2">1:1</th>' +
        '<th class="h_1x2">2:2</th>' +
        '<th class="h_1x2">3:3</th>' +
        '<th class="h_1x2">4:4</th>' +
        '<th class="h_1x2">其他</th>' +
        '</tr>';
    $.post('/index.php/sports/Match/FootballMBD/' + "?t=" + Math.random(), {
        p: thispage,
        oddpk: oddpk,
        leg: leg_
    }, function(d) {
        sitestatus(d)
        if (d.db) {
            pages(d.page, thispage)

            if (!dbs)
                dbs = d.db;
            else {
                if (dbs.length < d.db.length)
                    dbs = d.db;
            }

            $.each(d.db, function(i, v) {

                if (v.Match_Name != Match_Name) {
                    html += '<tr style="display: ;">' +
                        '<td colspan="18" class="b_hline">' +
                        '<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="showLeg(\'' + v.Match_Name + '\')">' +
                        ' <span id="' + v.Match_Name + '" name="' + v.Match_Name + '" class="showleg">' +
                        ' <span id="LegOpen"></span>' +
                        ' </span>' +
                        '</td><td onclick="showLeg(\'' + v.Match_Name + '\')" class="leg_bar">' + v.Match_Name + '</td></tr></tbody></table>' +
                        '</td>' +
                        '</tr>';
                }
                Match_Name = v.Match_Name;
                html += '<tr id="TR_01-1">' +
                    '<td rowspan="2" class="b_cen">' + v.Match_Date + '</td>' +
                    '<td width="13%" rowspan="2" class="b_cen">' + v.Match_Master + '<br>' + v.Match_Guest + '</td>' +
                    '<td width="5%" class="b_cen"><a title="1:0" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd10\',' + v.Match_ID + ')"  >' + v.Match_Bd10 + '</a></td>' +
                    '<td width="5%" class="b_cen"><a title="2:0" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd20\',' + v.Match_ID + ')">' + v.Match_Bd20 + '</a></td>' +
                    '<td width="5%" class="b_cen"><a title="2:1" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd21\',' + v.Match_ID + ')">' + v.Match_Bd21 + '</a></td>' +
                    '<td width="5%" class="b_cen"><a title="3:0" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd30\',' + v.Match_ID + ')">' + v.Match_Bd30 + '</a></td>' +
                    '<td width="5%" class="b_cen"><a title="3:1" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd31\',' + v.Match_ID + ')">' + v.Match_Bd31 + '</a></td>' +
                    '<td width="5%" class="b_cen"><a title="3:2" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd32\',' + v.Match_ID + ')">' + v.Match_Bd32 + '</a></td>' +
                    '<td width="5%" class="b_cen"><a title="4:0" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd40\',' + v.Match_ID + ')">' + v.Match_Bd40 + '</a></td>' +
                    '<td width="5%" class="b_cen"><a title="4:1" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd41\',' + v.Match_ID + ')">' + v.Match_Bd41 + '</a></td>' +
                    '<td width="5%" class="b_cen"><a title="4:2" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd42\',' + v.Match_ID + ')">' + v.Match_Bd42 + '</a></td>' +
                    '<td width="5%" class="b_cen"><a title="4:3" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd43\',' + v.Match_ID + ')">' + v.Match_Bd43 + '</a></td>' +
                    '<td width="5%" rowspan="2" class="b_cen"><a  title="0:0" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd00\',' + v.Match_ID + ')">' + v.Match_Bd00 + '</a></td>' +
                    '<td width="5%" rowspan="2" class="b_cen"><a  title="1:1" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd10\',' + v.Match_ID + ')">' + v.Match_Bd10 + '</a></td>   ' +
                    '<td width="5%" rowspan="2" class="b_cen"><a  title="2:2" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd22\',' + v.Match_ID + ')">' + v.Match_Bd22 + '</a></td>' +
                    '<td width="5%" rowspan="2" class="b_cen"><a  title="3:3" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd33\',' + v.Match_ID + ')">' + v.Match_Bd33 + '</a></td>' +
                    '<td width="5%" rowspan="2" class="b_cen"><a  title="4:4" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bd44\',' + v.Match_ID + ')">' + v.Match_Bd44 + '</a></td>' +
                    '<td width="5%" rowspan="2" class="b_cen"><a  title="其它比分" href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Bdup5\',' + v.Match_ID + ')">' + v.Match_Bdup5 + '</a></td>' +
                    '</tr>' +
                    '<tr id="TR_01-1">' +
                    '<td class="b_cen"><a style=""  title="0:1" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg10\',' + v.Match_ID + ')">' + v.Match_Bdg10 + '</a></td>' +
                    '<td class="b_cen"><a style=""  title="0:2" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg20\',' + v.Match_ID + ')">' + v.Match_Bdg20 + '</a></td>' +
                    '<td class="b_cen"><a style=""  title="1:2" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg21\',' + v.Match_ID + ')">' + v.Match_Bdg21 + '</a></td>' +
                    '<td class="b_cen"><a style=""  title="0:3" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg30\',' + v.Match_ID + ')">' + v.Match_Bdg30 + '</a></td>' +
                    '<td class="b_cen"><a style=""  title="1:3" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg31\',' + v.Match_ID + ')">' + v.Match_Bdg31 + '</a></td>' +
                    '<td class="b_cen"><a style=""  title="2:3" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg32\',' + v.Match_ID + ')">' + v.Match_Bdg32 + '</a></td>' +
                    '<td class="b_cen"><a style=""  title="0:4" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg40\',' + v.Match_ID + ')">' + v.Match_Bdg40 + '</a></td>' +
                    '<td class="b_cen"><a style=""  title="1:4" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg41\',' + v.Match_ID + ')">' + v.Match_Bdg41 + '</a></td>' +
                    '<td class="b_cen"><a style=""  title="2:4" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg42\',' + v.Match_ID + ')">' + v.Match_Bdg42 + '</a></td>' +
                    '<td class="b_cen"><a style=""  title="3:4" href="javascript:void(0)" onclick="betOrder(\'FT\',\'Match_Bdg43\',' + v.Match_ID + ')">' + v.Match_Bdg43 + '</a></td>' +
                    '</tr>';


            })
            if (d.legname) {
                if (!leg_) getlegname(d.legname);
            }
            dbs = d.db;
            
            // $('#load_alpha').hide();
        } else {
            html += '<tr><Td colspan=18 class="b_cen">无赛事数据</td></tr>';
        }
        html += '</table>';
            $('#tableref').removeClass('tablerefon');
            $('#tableref').addClass('tableref');



            $('#data').html(html)

    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}

/*早餐-足球总入球*/
function FootballMZRQ(p) {
    $('#dsorcg').val(1)
    $('#tableref').addClass('tablerefon');
    oddpk = $('#arepk').val();
    if (!oddpk)
        oddpk = 'H';

    d_ = leg_ = '';
    var Match_Name = null;
    var thispage = $('#legpage').val();
    leg = $('#legname_data_load input');
    if (leg.length > 0 && $('#tablename').html() == '足球总入球') {
        $.each(leg, function(i, v) {
            if ($(this).attr('checked')) {
                leg_ += $(this).val() + '|'
            }
        })
    }
    $('#tablename').html('足球总入球');
    var thispage = $('#legpage').val();
    if (p)
        thispage = p;
    var table = ''
    var html = table + '<table cellpadding="0" cellspacing="0" border="0" class="game"><tr>' +
        '<th nowrap="" class="time">时间</th>' +
        '<th nowrap="" class="team">主客队</th>' +
        '<th nowrap="" class="h_1x2">0~1</th>' +
        '<th nowrap="" class="h_r">2~3</th>' +
        '<th nowrap="" class="h_ou">4~6</th>' +
        '<th nowrap="" class="h_oe">7或以上</th>' +
        '</tr>';
    $.post('/index.php/sports/Match/FootballMZRQ/' + "?t=" + Math.random(), {
        p: thispage,
        oddpk: oddpk,leg:leg_
    }, function(d) {
        sitestatus(d)
        if (d.db) {
            pages(d.page, thispage)

            if (!dbs)
                dbs = d.db;
            else {
                if (dbs.length < d.db.length)
                    dbs = d.db;
            }

            $.each(d.db, function(i, v) {

                if (v.Match_Name != Match_Name) {
                    html += '<tr style="display: ;">' +
                        '<td colspan="6" class="b_hline">' +
                        '<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="showLeg(\'' + v.Match_Name + '\')">' +
                        ' <span id="' + v.Match_Name + '" name="' + v.Match_Name + '" class="showleg">' +
                        ' <span id="LegOpen"></span>' +
                        ' </span>' +
                        '</td><td onclick="showLeg(\'' + v.Match_Name + '\')" class="leg_bar">' + v.Match_Name + '</td></tr></tbody></table>' +
                        '</td>' +
                        '</tr>';
                }
                Match_Name = v.Match_Name;
                html += '<tr>' +
                    '<td class="b_cen">' + v.Match_Date + '</td>    ' +
                    '<td class="b_cen">' + v.Match_Master + '<br>' + v.Match_Guest + '</td> ' +
                    '<td class="b_cen"><a href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Total01Pl\',' + v.Match_ID + ')"  title="0~1"  >' + v.Match_Total01Pl + '</a></td>' +
                    '<td class="b_cen"><a href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Total23Pl\',' + v.Match_ID + ')"  title="2~3"  >' + v.Match_Total23Pl + '</a></td>' +
                    '<td class="b_cen"><a href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Total46Pl\',' + v.Match_ID + ')"  title="4~6"  >' + v.Match_Total46Pl + '</a></td>' +
                    '<td class="b_cen"><a href="javascript:void(0)"  onclick="betOrder(\'FT\',\'Match_Total7upPl\',' + v.Match_ID + ')" title="7以上">' + v.Match_Total7upPl + '</a></td>' +
                    '</tr>';
            })
            dbs = d.db;
            if (d.legname) {
                if (!leg_) getlegname(d.legname);
            }
           
                //$('#load_alpha').hide();
        }
        else {
            html += '<tr><Td colspan=18 class="b_cen">无赛事数据</td></tr>';
        }
        html += '</table>';
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
        $('#data').html(html)
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}

/*早餐-足球综合过关*/
function FootballMGG(p) {
    $('#dsorcg').val(2)
    $('#tableref').addClass('tablerefon');
    oddpk = $('#arepk').val();
    if (!oddpk)
        oddpk = 'H';
     d_ = leg_ = '';
    var Match_Name = null;
    var thispage = $('#legpage').val();
    leg = $('#legname_data_load input');
    if (leg.length > 0 && $('#tablename').html() == '足球单式过关') {
        $.each(leg, function(i, v) {
            if ($(this).attr('checked')) {
                leg_ += $(this).val() + '|'
            }
        })
    }
    var thispage = $('#legpage').val();
    if (p)
        thispage = p;
    var table = ''
    var html = table + '<table cellpadding="0" cellspacing="0" border="0" class="game"><tr>' +
        '<th nowrap="" class="time">时间</th>' +
        '<th nowrap="" class="team">赛事</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">全场 - 让球</th>' +
        '<th nowrap="" class="h_ou">全场 - 大小</th>' +
        '<th nowrap="" class="h_oe">单双</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">半场 - 让球</th>' +
        '<th nowrap="" class="h_ou">半场 - 大小</th>' +
        '</tr>';
    $.post('/index.php/sports/Match/FootballMGG/' + "?t=" + Math.random(), {
        p: thispage,
        oddpk: oddpk,leg:leg_
    }, function(d) {
        sitestatus(d)
        if (d.db) {
            pages(d.page, thispage)

            if (!dbs)
                dbs = d.db;
            else {
                if (dbs.length < d.db.length)
                    dbs = d.db;
            }

            $.each(d.db, function(i, v) {

                if (v.Match_Name != Match_Name) {
                    html += '<tr style="display: ;">' +
                        '<td colspan="9" class="b_hline">' +
                        '<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="showLeg(\'' + v.Match_Name + '\')">' +
                        ' <span id="' + v.Match_Name + '" name="' + v.Match_Name + '" class="showleg">' +
                        ' <span id="LegOpen"></span>' +
                        ' </span>' +
                        '</td><td onclick="showLeg(\'' + v.Match_Name + '\')" class="leg_bar">' + v.Match_Name + '</td></tr></tbody></table>' +
                        '</td>' +
                        '</tr>';
                }
                Match_Name = v.Match_Name;
                html += '<tr id="TR_' + v.Match_ID + '">' +
                    //时间
                    ' <td rowspan="3" class="b_cen">' + v.Match_Date + '</td>' +
                    //主客队
                    ' <td rowspan="2" class="team_name" >' + v.Match_Master + '<br>' + v.Match_Guest + '</td>' +
                    //独赢主
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_BzM"] != v["Match_BzM"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BzM\',' + v.Match_ID + ')" title="' + v.Match_Master + '" >' + formatNumber(v.Match_BzM, 2) + '</a></td>' +
                    //让球主让
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_Ho"] != v["Match_Ho"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "H" && v["Match_Ho"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Ho\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Ho"], 2) + '</a></span></td>' +
                    //大
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_DxDpl"] != v["Match_DxDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxXpl"] == "0" ? "" : '大' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_DxDpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_DxDpl"], 2) + '</a></span></td>' +
                    //单
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_DsDpl"] != v["Match_DsDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsDpl"] == "0" ? "" : "单") + '<a href="javascript:;" onclick="betOrder(\'FT\',\'Match_DsDpl\',' + v.Match_ID + ')" title="单">' + formatNumber(v["Match_DsDpl"], 2) + '</a></td>' +
                    //半场独赢主
                    ' <td class="b_1st" style=" ' + (dbs[i]["Match_Bmdy"] != v["Match_Bmdy"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bmdy\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Bmdy"], 2) + '</a></td>' +
                    //半场让球主
                    ' <td class="b_1stR" style=" ' + (dbs[i]["Match_BHo"] != v["Match_BHo"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_Hr_ShowType"] == "H" && v["Match_BHo"] != 0) ? v.Match_BRpk : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BHo\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_BHo"], 2) + '</a></span></td>' +
                    //半场 大
                    ' <td class="b_1stR" style=" ' + (dbs[i]["Match_Bdpl"] != v["Match_Bdpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_Bdxpk1"]) ? "大" + v["Match_Bdxpk1"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bdpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_Bdpl"], 2) + '</a></span></td>' +
                    '</tr>' +
                    '<tr id="TR1_' + v.Match_ID + '" >' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzG"] != v["Match_BzG"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BzG\',' + v.Match_ID + ')" title="' + v.Match_Guest + '" ><font>' + formatNumber(v.Match_BzG, 2) + '</font></a></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_Ao"] != v["Match_Ao"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "C" && v["Match_Ao"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Ao\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v["Match_Ao"], 2) + '</a></span></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_DxXpl"] != v["Match_DxXpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxGG1"] == "O" || v["Match_DxXpl"] == "0" ? "" : '小' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_DxXpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_DxXpl, 2) + '</a></span></td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_DsSpl"] != v["Match_DsSpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsSpl"] == "0" ? "" : "双") + '<a href="javascript:;" onclick="betOrder(\'FT\',\'Match_DsSpl\',' + v.Match_ID + ')" title="双">' + formatNumber(v.Match_DsSpl, 2) + '</a></td>' +
                    '  <td class="b_1st" style=" ' + (dbs[i]["Match_Bgdy"] != v["Match_Bgdy"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bgdy\',' + v.Match_ID + ')" title="' + v.Match_Guest + '"><font>' + formatNumber(v.Match_Bgdy, 2) + '</font></a></td>' +
                    '  <td class="b_1stR" style=" ' + (dbs[i]["Match_BAo"] != v["Match_BAo"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((dbs[i]["Match_Hr_ShowType"] == "C" && dbs[i]["Match_BAo"] != 0) ? v.Match_BRpk : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BAo\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v.Match_BAo, 2) + '</a></span></td>' +
                    '  <td class="b_1stR" style=" ' + (dbs[i]["Match_Bxpl"] != v["Match_Bxpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_Bdxpk1"]) ? "小" + v["Match_Bdxpk1"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bxpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_Bxpl, 2) + '</a></span></td>' +
                    '</tr>' +
                    '<tr id="TR2_' + v.Match_ID + '" >' +
                    '  <td class="team_name" id="TR_11-1350180_1">和局</td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzH"] != v["Match_BzH"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BzH\',' + v.Match_ID + ')" title="和"><font>' + formatNumber(v.Match_BzH, 2) + '</font></a></td>' +
                    '  <td colspan="3" valign="top" class="b_cen"></td>' +
                    '  <td class="b_1st" style=" ' + (dbs[i]["Match_Bhdy"] != v["Match_Bhdy"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_Bhdy\',' + v.Match_ID + ')" title="和">' + formatNumber(v.Match_Bhdy, 2) + '</a></td>' +
                    '  <td colspan="2" valign="top" class="b_1st">&nbsp;</td>' +
                    '</tr>';

            })
            dbs = d.db;
            html += '</table>';

        } else {
            html += '<tr><Td colspan=9 class="b_cen">无赛事数据</td></tr>';
        }
        if (d.legname) {
            if (!leg_) getlegname(d.legname);
        }
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
        $('#data').html(html)
        $('#tablename').html('足球单式过关');
        // $('#load_alpha').hide();
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}
/*早餐-篮球，独赢等*/
function BasketballMorning(p) {
    $('#dsorcg').val(1)
    $('#tableref').addClass('tablerefon');
    oddpk = $('#arepk').val();
    if (!oddpk)
        oddpk = 'H';
     d_ = leg_ = '';
    var Match_Name = null;
    var thispage = $('#legpage').val();
    leg = $('#legname_data_load input');
    if (leg.length > 0 && $('#tablename').html() == '篮球单式') {
        $.each(leg, function(i, v) {
            if ($(this).attr('checked')) {
                leg_ += $(this).val() + '|'
            }
        })
    }
    var thispage = $('#legpage').val();
    if (p)
        thispage = p;
    var table = ''
    var html = table + '<table cellpadding="0" cellspacing="0" border="0" class="game"><tr>' +
        '<th nowrap="" class="time">时间</th>' +
        '<th nowrap="" class="team">赛事</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">全场 - 让球</th>' +
        '<th nowrap="" class="h_ou">全场 - 大小</th>' +
        '<th nowrap="" class="h_oe">单双</th>' +
        '</tr>';
    $.post('/index.php/sports/Match/BasketballMorning/' + "?t=" + Math.random(), {
        p: thispage,
        oddpk: oddpk,leg:leg_
    }, function(d) {
        sitestatus(d)
        if (d.db) {
            pages(d.page, thispage)

            if (!dbs)
                dbs = d.db;
            else {
                if (dbs.length < d.db.length)
                    dbs = d.db;
            }


            $.each(d.db, function(i, v) {

                if (v.Match_Name != Match_Name) {
                    html += '<tr style="display: ;">' +
                        '<td colspan="6" class="b_hline">' +
                        '<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="showLeg(\'' + v.Match_Name + '\')">' +
                        ' <span id="' + v.Match_Name + '" name="' + v.Match_Name + '" class="showleg">' +
                        ' <span id="LegOpen"></span>' +
                        ' </span>' +
                        '</td><td onclick="showLeg(\'' + v.Match_Name + '\')" class="leg_bar">' + v.Match_Name + '</td></tr></tbody></table>' +
                        '</td>' +
                        '</tr>';
                }
                Match_Name = v.Match_Name;
                html += '<tr id="TR_' + v.Match_ID + '">' +
                    //时间
                    ' <td rowspan="2" class="b_cen">' + v["Match_Date"] + '</td>' +
                    //主客队
                    ' <td rowspan="2" class="team_name" >' + v.Match_Master + '<br>' + v.Match_Guest + '</td>' +
                    //独赢主
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_BzM"] != v["Match_BzM"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_BzM\',' + v.Match_ID + ')" title="' + v.Match_Master + '" >' + formatNumber(v.Match_BzM, 2) + '</a></td>' +
                    //让球主让
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_Ho"] != v["Match_Ho"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "H" && v["Match_Ho"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_Ho\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Ho"], 2) + '</a></span></td>' +
                    //大
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_DxDpl"] != v["Match_DxDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxXpl"] == "0" ? "" : '大' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_DxDpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_DxDpl"], 2) + '</a></span></td>' +
                    //单
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_DsDpl"] != v["Match_DsDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsDpl"] == "0" ? "" : "单") + '<a href="javascript:;" onclick="betOrder(\'BK\',\'Match_DsDpl\',' + v.Match_ID + ')" title="单">' + formatNumber(v["Match_DsDpl"], 2) + '</a></td>' +
                    '</tr>' +
                    '<tr id="TR1_' + v.Match_ID + '" >' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzG"] != v["Match_BzG"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BzG\',' + v.Match_ID + ')" title="' + v.Match_Guest + '" ><font>' + formatNumber(v.Match_BzG, 2) + '</font></a></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_Ao"] != v["Match_Ao"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "C" && v["Match_Ao"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_Ao\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v["Match_Ao"], 2) + '</a></span></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_DxXpl"] != v["Match_DxXpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxGG1"] == "O" || v["Match_DxXpl"] == "0" ? "" : '小' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_DxXpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_DxXpl, 2) + '</a></span></td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_DsSpl"] != v["Match_DsSpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsSpl"] == "0" ? "" : "双") + '<a href="javascript:;" onclick="betOrder(\'BK\',\'Match_DsSpl\',' + v.Match_ID + ')" title="双">' + formatNumber(v.Match_DsSpl, 2) + '</a></td>' +
                    '</tr> ';

            })
            dbs = d.db;
            html += '</table>';

        } else {
            html += '<tr><Td colspan=9 class="b_cen">无赛事数据</td></tr>';
        }
        if (d.legname) {
            if (!leg_) getlegname(d.legname);
        }
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
        $('#data').html(html)
        $('#tablename').html('篮球单式');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}

/*早餐-篮球综合过关*/
function BasketballMGG(p) {
    $('#dsorcg').val(2)
    $('#tableref').addClass('tablerefon');
    oddpk = $('#arepk').val();
    if (!oddpk)
        oddpk = 'H';
    var d_ = null;
    var Match_Name = null;
    var thispage = $('#legpage').val();
    if (p)
        thispage = p;
    var table = ''
    var html = table + '<table cellpadding="0" cellspacing="0" border="0" class="game"><tr>' +
        '<th nowrap="" class="time">时间</th>' +
        '<th nowrap="" class="team">赛事</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">全场 - 让球</th>' +
        '<th nowrap="" class="h_ou">全场 - 大小</th>' +
        '<th nowrap="" class="h_oe">单双</th>' +
        '</tr>';
    $.post('/index.php/sports/Match/BasketballMGG/' + "?t=" + Math.random(), {
        p: thispage,
        oddpk: oddpk
    }, function(d) {
        sitestatus(d)
        if (d.db) {
            pages(d.page, thispage)

            if (!dbs)
                dbs = d.db;
            else {
                if (dbs.length < d.db.length)
                    dbs = d.db;
            }

            $.each(d.db, function(i, v) {

                if (v.Match_Name != Match_Name) {
                    html += '<tr style="display: ;">' +
                        '<td colspan="6" class="b_hline">' +
                        '<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="showLeg(\'' + v.Match_Name + '\')">' +
                        ' <span id="' + v.Match_Name + '" name="' + v.Match_Name + '" class="showleg">' +
                        ' <span id="LegOpen"></span>' +
                        ' </span>' +
                        '</td><td onclick="showLeg(\'' + v.Match_Name + '\')" class="leg_bar">' + v.Match_Name + '</td></tr></tbody></table>' +
                        '</td>' +
                        '</tr>';
                }
                Match_Name = v.Match_Name;
                html += '<tr id="TR_' + v.Match_ID + '">' +
                    //时间
                    ' <td rowspan="2" class="b_cen">' + v["Match_Date"] + '</td>' +
                    //主客队
                    ' <td rowspan="2" class="team_name" >' + v.Match_Master + '<br>' + v.Match_Guest + '</td>' +
                    //独赢主
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_BzM"] != v["Match_BzM"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_BzM\',' + v.Match_ID + ')" title="' + v.Match_Master + '" >' + formatNumber(v.Match_BzM, 2) + '</a></td>' +
                    //让球主让
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_Ho"] != v["Match_Ho"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "H" && v["Match_Ho"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_Ho\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Ho"], 2) + '</a></span></td>' +
                    //大
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_DxDpl"] != v["Match_DxDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxXpl"] == "0" ? "" : '大' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_DxDpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_DxDpl"], 2) + '</a></span></td>' +
                    //单
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_DsDpl"] != v["Match_DsDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsDpl"] == "0" ? "" : "单") + '<a href="javascript:;" onclick="betOrder(\'BK\',\'Match_DsDpl\',' + v.Match_ID + ')" title="单">' + formatNumber(v["Match_DsDpl"], 2) + '</a></td>' +
                    '</tr>' +
                    '<tr id="TR1_' + v.Match_ID + '" >' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzG"] != v["Match_BzG"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'FT\',\'Match_BzG\',' + v.Match_ID + ')" title="' + v.Match_Guest + '" ><font>' + formatNumber(v.Match_BzG, 2) + '</font></a></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_Ao"] != v["Match_Ao"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "C" && v["Match_Ao"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_Ao\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v["Match_Ao"], 2) + '</a></span></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_DxXpl"] != v["Match_DxXpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxGG1"] == "O" || v["Match_DxXpl"] == "0" ? "" : '小' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'BK\',\'Match_DxXpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_DxXpl, 2) + '</a></span></td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_DsSpl"] != v["Match_DsSpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsSpl"] == "0" ? "" : "双") + '<a href="javascript:;" onclick="betOrder(\'BK\',\'Match_DsSpl\',' + v.Match_ID + ')" title="双">' + formatNumber(v.Match_DsSpl, 2) + '</a></td>' +
                    '</tr> ';

            })
            dbs = d.db;
            html += '</table>';

        } else {
            html += '<tr><Td colspan=9 class="b_cen">无赛事数据</td></tr>';
        }
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
        $('#data').html(html)
        $('#tablename').html('篮球单式过关');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}

/*早餐-排球*/
function VolleyballMorning(p) {
    $('#dsorcg').val(1)
    $('#tableref').addClass('tablerefon');
    oddpk = $('#arepk').val();
    if (!oddpk)
        oddpk = 'H';
     d_ = leg_ = '';
    var Match_Name = null;
    var thispage = $('#legpage').val();
    leg = $('#legname_data_load input');
    if (leg.length > 0 && $('#tablename').html() == '排球单式') {
        $.each(leg, function(i, v) {
            if ($(this).attr('checked')) {
                leg_ += $(this).val() + '|'
            }
        })
    }
    var thispage = $('#legpage').val();
    if (p)
        thispage = p;
    var table = ''
    var html = table + '<table cellpadding="0" cellspacing="0" border="0" class="game"><tr>' +
        '<th nowrap="" class="time">时间</th>' +
        '<th nowrap="" class="team">赛事</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">全场 - 让球</th>' +
        '<th nowrap="" class="h_ou">全场 - 大小</th>' +
        '<th nowrap="" class="h_oe">单双</th>' +
        '</tr>';
    $.post('/index.php/sports/Match/VolleyballMorning/' + "?t=" + Math.random(), {
        p: thispage,
        oddpk: oddpk,leg:leg_
    }, function(d) {
        sitestatus(d)
        if (d.db) {
            pages(d.page, thispage)

            if (!dbs)
                dbs = d.db;
            else {
                if (dbs.length < d.db.length)
                    dbs = d.db;
            }


            $.each(d.db, function(i, v) {

                if (v.Match_Name != Match_Name) {
                    html += '<tr style="display: ;">' +
                        '<td colspan="6" class="b_hline">' +
                        '<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="showLeg(\'' + v.Match_Name + '\')">' +
                        ' <span id="' + v.Match_Name + '" name="' + v.Match_Name + '" class="showleg">' +
                        ' <span id="LegOpen"></span>' +
                        ' </span>' +
                        '</td><td onclick="showLeg(\'' + v.Match_Name + '\')" class="leg_bar">' + v.Match_Name + '</td></tr></tbody></table>' +
                        '</td>' +
                        '</tr>';
                }
                Match_Name = v.Match_Name;
                html += '<tr id="TR_' + v.Match_ID + '">' +
                    //时间
                    ' <td rowspan="2" class="b_cen">' + v["Match_Date"] + '</td>' +
                    //主客队
                    ' <td rowspan="2" class="team_name" >' + v.Match_Master + '<br>' + v.Match_Guest + '</td>' +
                    //独赢主
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_BzM"] != v["Match_BzM"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'VB\',\'Match_BzM\',' + v.Match_ID + ')" title="' + v.Match_Master + '" >' + formatNumber(v.Match_BzM, 2) + '</a></td>' +
                    //让球主让
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_Ho"] != v["Match_Ho"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "H" && v["Match_Ho"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'VB\',\'Match_Ho\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Ho"], 2) + '</a></span></td>' +
                    //大
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_DxDpl"] != v["Match_DxDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxXpl"] == "0" ? "" : '大' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'VB\',\'Match_DxDpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_DxDpl"], 2) + '</a></span></td>' +
                    //单
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_DsDpl"] != v["Match_DsDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsDpl"] == "0" ? "" : "单") + '<a href="javascript:;" onclick="betOrder(\'VB\',\'Match_DsDpl\',' + v.Match_ID + ')" title="单">' + formatNumber(v["Match_DsDpl"], 2) + '</a></td>' +
                    '</tr>' +
                    '<tr id="TR1_' + v.Match_ID + '" >' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzG"] != v["Match_BzG"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'VB\',\'Match_BzG\',' + v.Match_ID + ')" title="' + v.Match_Guest + '" ><font>' + formatNumber(v.Match_BzG, 2) + '</font></a></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_Ao"] != v["Match_Ao"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "C" && v["Match_Ao"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'VB\',\'Match_Ao\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v["Match_Ao"], 2) + '</a></span></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_DxXpl"] != v["Match_DxXpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxGG1"] == "O" || v["Match_DxXpl"] == "0" ? "" : '小' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'VB\',\'Match_DxXpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_DxXpl, 2) + '</a></span></td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_DsSpl"] != v["Match_DsSpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsSpl"] == "0" ? "" : "双") + '<a href="javascript:;" onclick="betOrder(\'VB\',\'Match_DsSpl\',' + v.Match_ID + ')" title="双">' + formatNumber(v.Match_DsSpl, 2) + '</a></td>' +
                    '</tr> ';

            })
            dbs = d.db;
            html += '</table>';

        } else {
            html += '<tr><Td colspan=9 class="b_cen">无赛事数据</td></tr>';
        }
        if (d.legname) {
            if (!leg_) getlegname(d.legname);
        }
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
        $('#data').html(html)
        $('#tablename').html('排球单式');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}

/*早餐-排球*/
function TennisMorning(p) {
    $('#dsorcg').val(1)
    $('#tableref').addClass('tablerefon');
    oddpk = $('#arepk').val();
    if (!oddpk)
        oddpk = 'H';
     d_ = leg_ = '';
    var Match_Name = null;
    var thispage = $('#legpage').val();
    leg = $('#legname_data_load input');
    if (leg.length > 0 && $('#tablename').html() == '网球单式') {
        $.each(leg, function(i, v) {
            if ($(this).attr('checked')) {
                leg_ += $(this).val() + '|'
            }
        })
    }
    var thispage = $('#legpage').val();
    if (p)
        thispage = p;
    var table = ''
    var html = table + '<table cellpadding="0" cellspacing="0" border="0" class="game"><tr>' +
        '<th nowrap="" class="time">时间</th>' +
        '<th nowrap="" class="team">赛事</th>' +
        '<th nowrap="" class="h_1x2">独赢</th>' +
        '<th nowrap="" class="h_r">全场 - 让球</th>' +
        '<th nowrap="" class="h_ou">全场 - 大小</th>' +
        '<th nowrap="" class="h_oe">单双</th>' +
        '</tr>';
    $.post('/index.php/sports/Match/TennisMorning/' + "?t=" + Math.random(), {
        p: thispage,
        oddpk: oddpk,leg:leg_
    }, function(d) {
        sitestatus(d)
        if (d.db) {
            pages(d.page, thispage)

            if (!dbs)
                dbs = d.db;
            else {
                if (dbs.length < d.db.length)
                    dbs = d.db;
            }


            $.each(d.db, function(i, v) {

                if (v.Match_Name != Match_Name) {
                    html += '<tr style="display: ;">' +
                        '<td colspan="6" class="b_hline">' +
                        '<table border="0" cellpadding="0" cellspacing="0"><tbody><tr><td class="legicon" onclick="showLeg(\'' + v.Match_Name + '\')">' +
                        ' <span id="' + v.Match_Name + '" name="' + v.Match_Name + '" class="showleg">' +
                        ' <span id="LegOpen"></span>' +
                        ' </span>' +
                        '</td><td onclick="showLeg(\'' + v.Match_Name + '\')" class="leg_bar">' + v.Match_Name + '</td></tr></tbody></table>' +
                        '</td>' +
                        '</tr>';
                }
                Match_Name = v.Match_Name;
                html += '<tr id="TR_' + v.Match_ID + '">' +
                    //时间
                    ' <td rowspan="2" class="b_cen">' + v["Match_Date"] + '</td>' +
                    //主客队
                    ' <td rowspan="2" class="team_name" >' + v.Match_Master + '<br>' + v.Match_Guest + '</td>' +
                    //独赢主
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_BzM"] != v["Match_BzM"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'VB\',\'Match_BzM\',' + v.Match_ID + ')" title="' + v.Match_Master + '" >' + formatNumber(v.Match_BzM, 2) + '</a></td>' +
                    //让球主让
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_Ho"] != v["Match_Ho"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "H" && v["Match_Ho"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'VB\',\'Match_Ho\',' + v.Match_ID + ')" title="' + v.Match_Master + '">' + formatNumber(v["Match_Ho"], 2) + '</a></span></td>' +
                    //大
                    ' <td class="b_rig" style=" ' + (dbs[i]["Match_DxDpl"] != v["Match_DxDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxXpl"] == "0" ? "" : '大' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'VB\',\'Match_DxDpl\',' + v.Match_ID + ')" title="大">' + formatNumber(v["Match_DxDpl"], 2) + '</a></span></td>' +
                    //单
                    ' <td class="b_cen" style=" ' + (dbs[i]["Match_DsDpl"] != v["Match_DsDpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsDpl"] == "0" ? "" : "单") + '<a href="javascript:;" onclick="betOrder(\'VB\',\'Match_DsDpl\',' + v.Match_ID + ')" title="单">' + formatNumber(v["Match_DsDpl"], 2) + '</a></td>' +
                    '</tr>' +
                    '<tr id="TR1_' + v.Match_ID + '" >' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_BzG"] != v["Match_BzG"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><a href="javascript:;" onclick="betOrder(\'VB\',\'Match_BzG\',' + v.Match_ID + ')" title="' + v.Match_Guest + '" ><font>' + formatNumber(v.Match_BzG, 2) + '</font></a></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_Ao"] != v["Match_Ao"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + ((v["Match_ShowType"] == "C" && v["Match_Ao"] != "0") ? v["Match_RGG"] : "") + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'VB\',\'Match_Ao\',' + v.Match_ID + ')" title="' + v.Match_Guest + '">' + formatNumber(v["Match_Ao"], 2) + '</a></span></td>' +
                    '  <td class="b_rig" style=" ' + (dbs[i]["Match_DxXpl"] != v["Match_DxXpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '"><span class="con">' + (v["Match_DxGG1"] == "O" || v["Match_DxXpl"] == "0" ? "" : '小' + v["Match_DxGG1"]) + '</span> <span class="ratio"><a href="javascript:;" onclick="betOrder(\'VB\',\'Match_DxXpl\',' + v.Match_ID + ')" title="小">' + formatNumber(v.Match_DxXpl, 2) + '</a></span></td>' +
                    '  <td class="b_cen" style=" ' + (dbs[i]["Match_DsSpl"] != v["Match_DsSpl"] && dbs[i]["Match_ID"] == v["Match_ID"] ? "background:#FFFF00" : "") + '">' + (v["Match_DsSpl"] == "0" ? "" : "双") + '<a href="javascript:;" onclick="betOrder(\'VB\',\'Match_DsSpl\',' + v.Match_ID + ')" title="双">' + formatNumber(v.Match_DsSpl, 2) + '</a></td>' +
                    '</tr> ';

            })
            dbs = d.db;
            html += '</table>';

        } else {
            html += '<tr><Td colspan=9 class="b_cen">无赛事数据</td></tr>';
        }
        if (d.legname) {
            if (!leg_) getlegname(d.legname);
        }
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
        $('#data').html(html)
        $('#tablename').html('网球单式');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}

/*足球-赛果*/
function FBRresults(t) {
    var t=0;//t=getRresultsDate(t,1);
     
    var table = ''
    var html = table + '<table id="game_table"  cellspacing="0" cellpadding="0" class="game">'+
            '<tr>'+
                '<th  class="time">时间</th>'+
                '<th  class="time">主客队伍</th>'+
                '<th  class="h_1x2">上半场比分</th>'+
                '<th class="h_1x2">全场比分</th>'+
            '</tr>';
          
         
    $.post('/index.php/sports/Match/FBRresults/' + "?t=" + Math.random(), {
        time: t
    }, function(d) {
        var Match_Name;
        if (d) {
            //pages(d.page, thispage)
            $.each(d.db, function(i, v) {
                var s1,x1,mcname;
                
                if(v.TG_Inball<0){
                    s1='<td colspan=2 class="b_cen"  id="">赛事无效</td>'
                    x1=s1;
                }else{
                    s1='<td id="2158360_MH" class="b_cen">'+ v.MB_Inball_HR+'</td>' +
                            '<td id="2158360_PRH" class="b_cen">'+ v.MB_Inball+'</td>' ;
                    x1='<td id="2158360_MH" class="b_cen">'+ v.TG_Inball_HR+'</td>'+
                            '<td id="2158360_PRH" class="b_cen">'+ v.TG_Inball+'</td>';
                }
                if(Match_Name == v.Match_Name){
                    mcname='';
                }else{
                    
                    mcname='<tr>' +
                        '<td  colspan="4"  class="b_hline">' +
                           ' <table  border="0"  cellpadding="0"  cellspacing="0"  width="100%"><tbody>' +
                                '<tr>' +
                                    '<td  class="legicon" >' +
                                        '<span  id="'+ v.Match_Name+'"  class="showleg"><span  id="LegOpen"></span> </span>' +
                                    '</td>' +
                                    '<td  class="leg_bar" >'+ v.Match_Name+' </td>' +
                                '</tr>' +
                                '</tbody></table>' +
                        '</td>' +
                        '</tr>' ;
                }
                Match_Name = v.Match_Name;
                html += mcname+
                        '<tr *class*="" id="">' +
                            '<td width="26%" class="b_cen" rowspan="2">'+ v.Match_MatchTime+'</td>' +
                            '<td width="26%" class="b_cen" rowspan="2"> '+ v.Match_Master+' <br>' +
                            v.Match_Guest+' </td>' +
                            s1+
                        '</tr>'+
                        '<tr *class*="" id="">'+
                            x1+
                        '</tr>';

            })
            
            html += '</table>';

        } else {
            html += '<tr><Td colspan=4 class="b_cen">无赛事数据</td></tr>';
            html += '</table>';
        }
        
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
        $('#data').html(html)
        $('#tablename').html('足球赛果');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}

/*篮球-赛果*/
function BKRresults(t) {
    var t=0;//t=getRresultsDate(t,2);
        
    var table = ''
    var html = table + '<table id="game_table"  cellspacing="0" cellpadding="0" class="game">'+
            '<tr>'+
                '<th  class="time">时间</th>'+
                '<th  class="time">主客队伍</th>'+
                '<th  class="h_1x2">1</th>'+
                '<th class="h_1x2">2</th>'+
                '<th  class="h_1x2">3</th>'+
                '<th class="h_1x2">4</th>'+
                '<th  class="h_1x2">上半</th>'+
                '<th class="h_1x2">下半</th>'+
                '<th  class="h_1x2">加时</th>'+
                '<th class="h_1x2">全场</th>'+
            '</tr>';
          
         
    $.post('/index.php/sports/Match/BKRresults/' + "?t=" + Math.random(), {
        time: t
    }, function(d) {
        var Match_Name;
        if (d) {
            //pages(d.page, thispage)
            $.each(d.db, function(i, v) {
                //将空的数据赋值为长度为0的字符串；
                for (nv in v){
                    if(v[nv]===null){
                        v[nv]='';
                    }
                }
                var s1,x1,mcname;
                if(Match_Name == v.Match_Name){
                    mcname='';
                }else{
                    
                    mcname='<tr>' +
                        '<td  colspan="10"  class="b_hline">' +
                           ' <table  border="0"  cellpadding="0"  cellspacing="0"  width="100%"><tbody>' +
                                '<tr>' +
                                    '<td  class="legicon" >' +
                                        '<span  id="'+ v.Match_Name+'"  class="showleg"><span  id="LegOpen"></span> </span>' +
                                    '</td>' +
                                    '<td  class="leg_bar" >'+ v.Match_Name+' </td>' +
                                '</tr>' +
                                '</tbody></table>' +
                        '</td>' +
                        '</tr>' ;
                }
                Match_Name = v.Match_Name;
                html += mcname+
                        '<tr *class*="" id="">' +
                        '<td width="26%" class="b_cen" rowspan="2">'+ v.Match_Date+'<br />'+v.Match_Time+'</td>' +
                        '<td width="26%" class="b_cen" rowspan="2"> '+ v.Match_Master+' <br>' +
                        v.Match_Guest+' </td>' +
                        '<td  class="b_cen"  id="118389861_MH">'+v.MB_Inball_1st+'</td>'+
                        '<td  class="b_cen"  id="118389861_PRH">'+v.MB_Inball_2st+'</td>'+
                        '<td  class="b_cen"  id="118389861_MH">'+v.MB_Inball_3st+'</td>'+
                        '<td  class="b_cen"  id="118389861_PRH">'+v.MB_Inball_4st+'</td>'+
                        '<td  class="b_cen"  id="118389861_MH">'+v.MB_Inball_HR+'</td>'+
                        '<td  class="b_cen"  id="118389861_PRH">'+v.MB_Inball_ER+'</td>'+
                        '<td  class="b_cen"  id="118389861_MH">'+v.MB_Inball_Add+'</td>'+
                        '<td  class="b_cen"  id="118389861_PRH">'+v.MB_Inball+'</td>'+
                        '</tr>'+
                        '<tr *class*="" id="">'+
                        '<td  class="b_cen"  >'+v.TG_Inball_1st+'</td>'+
                        '<td  class="b_cen"  >'+v.TG_Inball_2st+'</td>'+
                        '<td  class="b_cen"  >'+v.TG_Inball_3st+'</td>'+
                        '<td  class="b_cen"  >'+v.TG_Inball_4st+'</td>'+
                        '<td  class="b_cen"  >'+v.TG_Inball_HR+'</td>'+
                        '<td  class="b_cen"  >'+v.TG_Inball_ER+'</td>'+
                        '<td  class="b_cen"  >'+v.TG_Inball_Add+'</td>'+
                        '<td  class="b_cen"  >'+v.TG_Inball+'</td>'+
                        '</tr>';

            })
            
            html += '</table>';

        } else {
            html += '<tr><Td colspan=10 class="b_cen">无赛事数据</td></tr>';
            html += '</table>';
        }
        
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
        $('#data').html(html)
        $('#tablename').html('篮球结果');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}
/*网球-赛果*/
function TNRresults(t) {
    var t=0;//t=getRresultsDate(t,3);
        
    var table = ''
    var html = table + '<table id="game_table"  cellspacing="0" cellpadding="0" class="game">'+
            '<tr>'+
                '<th  class="time">时间</th>'+
                '<th  class="time">主客队伍</th>'+
                '<th  class="h_1x2">完赛(局)</th>'+
                '<th class="h_1x2">完赛(盘)</th>'+
            '</tr>';
          
         
    $.post('/index.php/sports/Match/TNRresults/' + "?t=" + Math.random(), {
        time: t
    }, function(d) {
        var Match_Name;
        if (d) {
            //pages(d.page, thispage)
            $.each(d.db, function(i, v) {
                var s1,x1,mcname;
                if(v.MB_Inball<0){
                    s1='<td  class="b_cen"  id="">赛事无效</td><td  class="b_cen"  id="">赛事无效</td>';
                }else{
                    s1='<td id="2158360_MH" class="b_cen">'+ v.MB_Inball+'</td>' +
                       '<td id="2158360_PRH" class="b_cen">'+ v.MB_Inball+'</td>' ;
                }
                 if(v.TG_Inball<0){
                    x1='<td  class="b_cen"  id="">赛事无效</td><td  class="b_cen"  id="">赛事无效</td>';
                }else{
                    x1='<td id="2158360_MH" class="b_cen">'+ v.TG_Inball+'</td>' +
                       '<td id="2158360_PRH" class="b_cen">'+ v.TG_Inball+'</td>' ;
                }
                if(Match_Name == v.Match_Name){
                    mcname='';
                }else{
                    
                    mcname='<tr>' +
                        '<td  colspan="4"  class="b_hline">' +
                           ' <table  border="0"  cellpadding="0"  cellspacing="0"  width="100%"><tbody>' +
                                '<tr>' +
                                    '<td  class="legicon" >' +
                                        '<span  id="'+ v.Match_Name+'"  class="showleg"><span  id="LegOpen"></span> </span>' +
                                    '</td>' +
                                    '<td  class="leg_bar" >'+ v.Match_Name+' </td>' +
                                '</tr>' +
                                '</tbody></table>' +
                        '</td>' +
                        '</tr>' ;
                }
                Match_Name = v.Match_Name;
                html += mcname+
                        '<tr  id="">' +
                            '<td width="26%" class="b_cen" rowspan="2">'+ v.Match_Date+'<br>'+v.Match_Time+'</td>' +
                            '<td width="26%" class="b_cen" rowspan="2"> '+ v.Match_Master+' <br>' +v.Match_Guest+' </td>' +
                            s1+
                        '</tr>'+
                        '<tr  id="">'+
                            x1+
                        '</tr>';

            })
            
            html += '</table>';

        } else {
            html += '<tr><Td colspan=4 class="b_cen">无赛事数据</td></tr>';
            html += '</table>';
        }
        
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
        $('#data').html(html)
        $('#tablename').html('网球赛果');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}

/*排球-赛果*/
function VBRresults(t) {
    var t=0;//t=getRresultsDate(t,4);
        
    var table = ''
    var html = table + '<table id="game_table"  cellspacing="0" cellpadding="0" class="game">'+
            '<tr>'+
                '<th  class="time">时间</th>'+
                '<th  class="time">主客队伍</th>'+
                '<th  class="h_1x2">完赛(局)</th>'+
                '<th class="h_1x2">完赛(盘)</th>'+
            '</tr>';
          
         
    $.post('/index.php/sports/Match/VBRresults/' + "?t=" + Math.random(), {
        time: t
    }, function(d) {
        var Match_Name;
        if (d) {
            //pages(d.page, thispage)
            $.each(d.db, function(i, v) {
                var s1,x1,mcname;
                if(v.MB_Inball<0){
                    s1='<td  class="b_cen"  id="">赛事无效</td><td  class="b_cen"  id="">赛事无效</td>';
                }else{
                    s1='<td id="2158360_MH" class="b_cen">'+ v.MB_Inball+'</td>' +
                       '<td id="2158360_PRH" class="b_cen">'+ v.MB_Inball+'</td>' ;
                }
                 if(v.TG_Inball<0){
                    x1='<td  class="b_cen"  id="">赛事无效</td><td  class="b_cen"  id="">赛事无效</td>';
                }else{
                    x1='<td id="2158360_MH" class="b_cen">'+ v.TG_Inball+'</td>' +
                       '<td id="2158360_PRH" class="b_cen">'+ v.TG_Inball+'</td>' ;
                }
                if(Match_Name == v.Match_Name){
                    mcname='';
                }else{
                    
                    mcname='<tr>' +
                        '<td  colspan="4"  class="b_hline">' +
                           ' <table  border="0"  cellpadding="0"  cellspacing="0"  width="100%"><tbody>' +
                                '<tr>' +
                                    '<td  class="legicon" >' +
                                        '<span  id="'+ v.Match_Name+'"  class="showleg"><span  id="LegOpen"></span> </span>' +
                                    '</td>' +
                                    '<td  class="leg_bar" >'+ v.Match_Name+' </td>' +
                                '</tr>' +
                                '</tbody></table>' +
                        '</td>' +
                        '</tr>' ;
                }
                Match_Name = v.Match_Name;
                html += mcname+
                        '<tr  id="">' +
                            '<td width="26%" class="b_cen" rowspan="2">'+ v.Match_Date+'<br>'+v.Match_Time+'</td>' +
                            '<td width="26%" class="b_cen" rowspan="2"> '+ v.Match_Master+' <br>' +v.Match_Guest+' </td>' +
                            s1+
                        '</tr>'+
                        '<tr  id="">'+
                            x1+
                        '</tr>';

            })
            
            html += '</table>';

        } else {
            html += '<tr><td colspan=4 class="b_cen">无赛事数据</td></tr>';
            html += '</table>';
        }
        
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
        $('#data').html(html)
        $('#tablename').html('排球赛果');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}

/*棒球-赛果*/
function BBRresults(t) {
    var t=0;//t=getRresultsDate(t,5);
        
    var table = ''
    var html = table + '<table id="game_table"  cellspacing="0" cellpadding="0" class="game">'+
            '<tr>'+
                '<th  class="time">时间</th>'+
                '<th  class="time">主客队伍</th>'+
                '<th  class="h_1x2">半场</th>'+
                '<th class="h_1x2">全场</th>'+
            '</tr>';
          
         
    $.post('/index.php/sports/Match/BBRresults/' + "?t=" + Math.random(), {
        time: t
    }, function(d) {
        var Match_Name;
        if (d) {
            //pages(d.page, thispage)
            $.each(d.db, function(i, v) {
                var s1,x1,mcname;
                if(v.MB_Inball<0){
                    s1='<td  class="b_cen"  id="">赛事无效</td><td  class="b_cen"  id="">赛事无效</td>';
                }else{
                    s1='<td id="2158360_MH" class="b_cen">'+ v.MB_Inball_HR+'</td>' +
                       '<td id="2158360_PRH" class="b_cen">'+ v.MB_Inball+'</td>' ;
                }
                 if(v.TG_Inball<0){
                    x1='<td  class="b_cen"  id="">赛事无效</td><td  class="b_cen"  id="">赛事无效</td>';
                }else{
                    x1='<td id="2158360_MH" class="b_cen">'+ v.TG_Inball_HR+'</td>' +
                       '<td id="2158360_PRH" class="b_cen">'+ v.TG_Inball+'</td>' ;
                }
                if(Match_Name == v.Match_Name){
                    mcname='';
                }else{
                    
                    mcname='<tr>' +
                        '<td  colspan="4"  class="b_hline">' +
                           ' <table  border="0"  cellpadding="0"  cellspacing="0"  width="100%"><tbody>' +
                                '<tr>' +
                                    '<td  class="legicon" >' +
                                        '<span  id="'+ v.Match_Name+'"  class="showleg"><span  id="LegOpen"></span> </span>' +
                                    '</td>' +
                                    '<td  class="leg_bar" >'+ v.Match_Name+' </td>' +
                                '</tr>' +
                                '</tbody></table>' +
                        '</td>' +
                        '</tr>' ;
                }
                Match_Name = v.Match_Name;
                html += mcname+
                        '<tr  id="">' +
                            '<td width="26%" class="b_cen" rowspan="2">'+ v.Match_Date+'<br>'+v.Match_Time+'</td>' +
                            '<td width="26%" class="b_cen" rowspan="2"> '+ v.Match_Master+' <br>' +v.Match_Guest+' </td>' +
                            s1+
                        '</tr>'+
                        '<tr  id="">'+
                            x1+
                        '</tr>';

            })
            
            html += '</table>';

        } else {
            html += '<tr><Td colspan=4 class="b_cen">无赛事数据</td></tr>';
            html += '</table>';
        }
        
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
        $('#data').html(html)
        $('#tablename').html('棒球赛果');
    }, 'json').error(function(XMLHttpRequest, textStatus, errorThrown) {
        var headers = XMLHttpRequest.getAllResponseHeaders();
        var timeout = '';
        if (XMLHttpRequest.getResponseHeader("Connection") == "close")
            timeout = " :加载超时,服务器连接中断!";
        html += "<table  class='game'><tr><td colspan='12'  class='b_1st'>数据加载错误" + timeout + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + headers + "</td></tr>";
        html += "<tr><td colspan='7' class='b_1st'>" + '返回状态：' + XMLHttpRequest.status + ' =>' + textStatus + '： ' + errorThrown + "</td></tr>";
        html += "</table>";
        $('#data').html(html);
        $('#tableref').removeClass('tablerefon');
        $('#tableref').addClass('tableref');
    })

}
/*获取比赛日期*/
function getRresultsDate(t,type){
    var slct='选择时间：<select id="RresultsDate" onchange="setRresultsDate('+type+')">'+
            '<option value="0">今天</option>'+
            '<option value="1">昨天</option>'+
            '<option value="2">前天</option>'+
            '<option value="3">3天前</option>'+
            '<option value="4">4天前</option>'+
            '<option value="5">5天前</option>'+
            '<option value="6">6天前</option>'+
            '<option value="7">7天前</option>'+
            '</select><div id="data"></div>';
    $("#box_mid").html(slct);
    if (t!==null){
        t=parseInt(t)==='NaN'?0:parseInt(t);
        $("#RresultsDate").val(t);
    }
    return t;
    
}
/*更改比赛日期*/
function setRresultsDate(type){
    t=$('#RresultsDate').val();
    if(type==1){
      FBRresults(t);
    }
    if(type==2){
       BKRresults(t);
    }
    if(type==3){
       TNRresults(t);
    }
    if(type==4){
       VBRresults(t); 
    }
    if(type==5){
       BBRresults(t);
    }
    
}

/*更多消息*/
function getnews() {
    getnewsdata();
    easyDialog.open({
        container: 'morenews',
        fixed: true,
        drag: true,
        follow: 'transaction',
        followX : -107,
        followY : 24
    });
    $('#easyDialogBox').show()
    $('#easyDialogBox').css({width:'60%'})
    
}
/*获取消息*/
function getnewsdata() {
    $.ajax({
        type: "POST",
        data:{type:2}, 
        dataType: "json",
        url: '/index.php/sports/Match/Fbnews/' + "?t=" + Math.random(),
//        beforeSend: function () {
//            var pro=document.getElementsByTagName("progress")[0];
//            var sp=document.getElementById("sp");
//            var t=setInterval(function  () {
//              if(pro.value==98){
//                clearInterval(t);
//                sp.innerHTML='加载中...'+pro.value+"%";
//              }else{
//                pro.value+=1;
//                sp.innerHTML='加载中...'+pro.value+"%";
//              }
//            },100)
//        },
        success: function (data) {
           if (data) {
                var html='<table><tr>' +
                    '<td>序号</td>' +
                    '<td>时间</td>' +
                    '<td>内容</td>' +
                    '</tr>' ;
                $.each(data, function(i, v) {
                    html +='<tr>' +
                    '<td>'+(i+1)+'</td>' +
                    '<td>'+v.notice_date+'</td>' +
                    '<td>'+v.notice_title+':'+v.notice_content+'</td>' +
                    '</tr>' ;
                })
                html +='</table>';
                $('#news_content').html(html);
            }
        }
    })
}
//帮助显示隐藏
function OnMouseOverEvent() {
    document.getElementById("informaction").style.display = "block";
}
function OnMouseOutEvent() {
    document.getElementById("informaction").style.display = "none";
}
//帮助下面打开新窗口方法
function winOpen(url,width,height,left,top,name)
{
        var temp = "menubar=no,toolbar=no,directories=no,scrollbars=yes,resizable=no";
        if (width) {
        temp += ',width=' + width;
        } else {
        width = 1024;
        }
        if (height) {
        temp += ',height=' + height;
        } else {
        height = 600;
        }
        if (left) {
        temp += ',left=' + left;
        } else {
        temp += ',left='
        + Math.round((window.screen.width - parseInt(width)) / 2);
        }
        if (top) {
        temp += ',top=' + top;
        } else {
        temp += ',top='
        + Math.round((window.screen.height - parseInt(height)) / 2);
        } 
        if(typeof(name)=="undefined"){
                name="";
        }
        if(name=="game")
        {
                //alert(temp);
                var obj=window.open (url,name,temp);
                obj.moveTo(0,0);
                obj.resizeTo(window.screen.availWidth,window.screen.availHeight);   
                //window.setTimeout("obj.document.location=url",3000);
        }
        else{
                window.open (url,name,temp);
        }
}