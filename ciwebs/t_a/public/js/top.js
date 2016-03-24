/***********************************js跳转页面******************************/
function open_pager(type,mo,me){
    if(type.charAt(0) == "-") {
        if (me == '') {
            //top.mem_index.location.href = mo;
            window.location.href = mo;
        }else{
            window.location.href = mo + '?type='+me;
        }
    }else if(type.charAt(0) == "i"){
        window.location.href = '../webcenter/iword?type='+mo;
    }
}
//open
function getPager(type,mo,me){
   if(type.charAt(0) == "-") {
        if (me == '') {
            //top.mem_index.location.href = mo;
            window.location.href = mo;
        }else{
            window.location.href = mo + '?metype='+me;
        }
    }else if(type.charAt(0) == "i"){
        window.location.href = '../webcenter/iword?type='+mo;
    }else if(type.charAt('_')){
        if(type == '_bank') {
            mo = 'http://'+mo;
            window.open(mo, null);
        } else if(type == '_self') {
            location.href = mo;
        }
    }
}


//电子游戏
function opengeme(url){
    var type = url.split("=");
    if (url == '/video/rule.html') {
        url = '/index.php/video/rule';
    }else if (type.length == 2) {//兼容老版本
        url = '/index.php/video/login?g_type='+ type[1];
    }else{
        url = '/index.php/video/login?g_type='+ url;
    }

    newWin=window.open(url,'','width=900,height=600,fullscreen=1,scrollbars=0,location=no');
    window.opener=null;//出掉关闭时候的提示窗口
    window.open('','_self'); //ie7
    window.close();
}



/***********************************在线客服******************************/
function OnlineService(url){
    newWin=window.open(url,'','width=900,height=600,top=0,left=0,status=no,toolbar=no,scrollbars=yes,resizable=no,personalbar=no');
    window.opener=null;//出掉关闭时候的提示窗口
    window.open('','_self'); //ie7
    window.close();
}


/***********************************会员中心******************************/
function openmember(id){
    var url = '/index.php/member/index?url='+id;
    window.open(url,'帮助','height=630,width=1020,top=80,left=200,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no');
}

//前台会员中心打开
function openHelp(url) {
    id = url.split("=");//兼容老版本
    openmember(id[1]);
}


/***********************************验证码******************************/
//验证码
function getKey(url) {
    $("#vPic").attr("src", "/yzm.php?type="+Math.random()+(new Date).getTime());
    $("#vPic").show();
}

function getYzm(url) {
    $("#vImg").attr("src", "/yzm.php?type="+Math.random()+(new Date).getTime());
    $("#vImg").show();
}




/***********************************弹出历史消息******************************/
function notice_data() {
    window.open('/index.php/index/notice_data', "History", "width=816,height=500,top=0,left=0,status=no,toolbar=no,scrollbars=yes,resizable=no,personalbar=no");
}




/***********************************代理联盟table选项卡******************************/
$.fn.mtab2 = function(posType) {
    var area = this, bgTop = '', bgBottom = '';
    var posType = (typeof posType !== 'undefined'? posType: 'l');
    switch(posType) {
        case 'c':
            bgTop = 'top center';
            bgBottom = 'bottom center';
            break;
        case 'r':
            bgTop = 'top right';
            bgBottom = 'bottom right';
            break;
        default:
            bgTop = 'top left';
            bgBottom = 'bottom left'
    }
    $.each(area.find('li[id^=#]'), function(i) {
        if(i != 0) {
            area.find(this.id)[0].style.display = 'none';
        }
    });
    area.find('li[id^=#]').click(function() {
        var self = this;
        $.each(area.find('li[id^=#]'), function(i) {
            if(self.id != this.id) {
                area.find(this.id)[0].style.display = 'none';
                $(this)[0].style.backgroundPosition = bgTop;
                $(this).removeClass('mtab');
            } else {
                area.find(this.id)[0].style.display = 'block';
                $(this)[0].style.backgroundPosition = bgBottom;
                $(this).addClass('mtab');
            }
        });
    });
};



/***********************************显示美东时间******************************/
//美东时间
function RefTime(){
    dd2.setSeconds(dd2.getSeconds()+1);
    var myYears = ( dd2.getYear() < 1900 ) ? ( 1900 + dd2.getYear() ) : dd2.getYear();
    $("#vlock").html('美東時間'+'：'+myYears+'年'+fixNum(dd2.getMonth()+1)+'月'+fixNum(dd2.getDate())+'日 '+time(dd2));
}

function time(vtime){
    var s='';
    var d=vtime!=null?new Date(vtime):new Date();
    with(d){
        s=fixNum(getHours())+':'+fixNum(getMinutes())+':'+fixNum(getSeconds())
    }
    return(s);
}

function fixNum(num){
    return parseInt(num)<10?'0'+num:num;
}


/***********************************日历******************************/
function _getYear(d){
    var yr=d.getYear();
    if(yr<1000) yr+=1900;
    return yr;
}

function tick(){
    function initArray(){
        for(i=0;i<initArray.arguments.length;i++) this[i]=initArray.arguments[i];
    }
    var isnDays=new initArray("星期日","星期一","星期二","星期三","星期四","星期五","星期六","星期日");
    var today=new Date();
    var hrs=today.getHours();
    var _min=today.getMinutes();
    var sec=today.getSeconds();
    var clckh=""+((hrs>12)?hrs-12:hrs);
    var clckm=((_min<10)?"0":"")+_min;clcks=((sec<10)?"0":"")+sec;
    var clck=(hrs>=12)?"下午":"上午";

    //document.getElementById("t_2_1").innerHTML = _getYear(today)+"/"+(today.getMonth()+1)+"/"+today.getDate()+"&nbsp;"+clckh+":"+clckm+":"+clcks+"&nbsp;"+clck+"&nbsp;"+isnDays[today.getDay()];
    document.getElementById("t_2_1").innerHTML = _getYear(today)+"/"+(today.getMonth()+1)+"/"+today.getDate()+"&nbsp;"+clckh+":"+clckm+":"+clcks;

    window.setTimeout("tick()", 100);
}

//var disnum=0;
//function urlOnclick(url,i){
//  window.open(url,"mainFrame");
//  if(i >= 0){
//      disnum = i;
//
//  }
//}


function urlparent(url){
    window.open(url,"newFrame");
}

function topMouseEvent(mi,ty,i){
    if(ty == "o" && i != disnum){
        mi.className = "homemenua";
    }else if(ty == "t" && i != disnum){
        mi.className = "alink";
    }
}

//登陆验证
function aLeftForm1Sub(){
    var un  =   $("#username").val();
    if(un == "" || un == "帐户"){
        $("#username").focus();
        return false;
    }
    var pw  =   $("#password").val();
    if(pw == "" || pw == "******"){
        $("#password").focus();
        return false;
    }
    var rmNum   =   $("#rmNum").val();
    if(rmNum == "" || rmNum == '验证码'){
        $("#rmNum").focus();
        return false;
    }
    $("#submit").attr("disabled",true); //按钮失效
    $.post("/index.php/webcenter/Login/login_do",{r:Math.random(),action:"login",username:un,password:pw,vlcodes:rmNum},function(data){
    if(data == '5'){
        alert("验证码错误，请重新输入");
        $("#rmNum").select();
    }else if(data == 4){
        alert("账号密码不匹配,请重新输入!");
        $("#rmNum").val('');
        $("#password").val('');
        $("#username").select();
    }else if(data == 3){
        alert("账号不存在!");
        $("#rmNum").val('');
        $("#password").val('');
        $("#username").select();
    }else if(data == 2){
        alert("对不起，账户已暂停使用,请联系在线客服！");
    }else if(data == 1){
        window.location.href= '/index.php/webcenter/Login/login_info';
        return;
    }
    $("#submit").attr("disabled",false); //按钮有效
});
}


/***********************************会员登录******************************/
function mem_login(){
    var uname = $("#username").val();
    if(uname == "" || uname == "帐户"){
        $("#username").focus();
        return false;
    }
    var pwd = $("#password").val();
    if(pwd == "" || pwd == "******"){
        $("#password").focus();
        return false;
    }
    var rmNum = $("#rmNum").val();
    if(rmNum == ""){
        $("#rmNum").focus();
        return false;
    }

    $("#submit").attr("disabled",true); //按钮失效
    $.post("../webcenter/Login/login_do",{r:Math.random(),action:"login",username:uname,password:pwd,vlcodes:rmNum},function(data){
        if(data == '5'){
            alert("验证码错误，请重新输入");
            $("#rmNum").select();
        }else if(data == 4){
            alert("账号密码不匹配,请重新输入!");
            $("#rmNum").val('');
            $("#password").val('');
            $("#username").select();
        }else if(data == 3){
            alert("账号不存在!");
            $("#rmNum").val('');
            $("#password").val('');
            $("#username").select();
        }else if(data == 2){
            alert("对不起，账户已暂停使用,请联系在线客服！");
        }else if(data == 1){
            window.location.href='../webcenter/Login/login_info';
            return;
        }
        $("#submit").attr("disabled",false); //按钮有效
    });
}



/***********************************memberUrl******************************/
function memberUrl(url) {
    art.dialog.open(url,{width:960,height:500});
}

function get_dled(){
    $.getJSON("getDLED.php?callback=?",function(json){
        $("#dled").html("("+json.dled+")");
    });
}

function navfocu(i){
    var as = document.getElementById("top_3").getElementsByTagName("a");
    for(var s=0; s<as.length; s++){
        if(s == (i-1)){
            as[s].className = "nav"+i+"_f";
        }else{
            as[s].className = "nav"+(s+1);
        }
    }
}


/***********************************收藏及设为首页******************************/
//用法
//onclick="AddFavorite(window.location, document.title)"
//onclick="SetHome(this, top.location)"

/**
 *加入收藏
 */
function AddFavorite(sURL, sTitle){
    try
    {
        window.external.addFavorite(sURL, sTitle);
    }
    catch (e)
    {
        try
        {
            window.sidebar.addPanel(sTitle, sURL, "");
        }
        catch (e)
        {
            alert("加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
}

/**
 *设为首页
 */
function SetHome(obj, vrl) {
    try {
        obj.style.behavior = 'url(#default#homepage)';
        obj.setHomePage(vrl);
    }
    catch (e) {
        if (window.netscape) {
            try {
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
            }
            catch (e) {
                alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车\n然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
            }
            var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
            prefs.setCharPref('browser.startup.homepage', vrl);
        } else {
            alert("您的浏览器不支持，请按照下面步骤操作：1.打开浏览器设置。2.点击设置网页。3.输入：" + vrl + "点击确定。");
        }
    }
}

/***********************************A标签文字闪烁-无需调用******************************/
function toggleColor(id, arr, s){
    var self = this;
    self._i = 0;
    self._timer = null;

    self.run = function() {
        if(arr[self._i]) {
            $(id).css('color', arr[self._i]);
        }
        self._i == 0 ? self._i++ : self._i = 0;
        self._timer = setTimeout(function() {
            self.run(id, arr, s);
        }, s);
    }
    self.run();
}

//讀取文案連結  data-color
$(function() {
    $('a.js-article-color').each(function() {
        var color_arr = $(this).data('color');

        if ('undefined' ==  typeof color_arr) return;

        color_arr = color_arr.split('|');

        // 確認顏色數量  2=>閃爍   1=>單一色  0=>跳過
        if(color_arr.length == 2) {
            new toggleColor(this, [color_arr[0], color_arr[1]], 500 );
        }else if(color_arr.length == 1 && color_arr[0] != ''){
            $(this).css('color', color_arr[0]);
        }
    });
});


//输入框验证
$(document).ready(function() {
    $('#username').focus(function() {
        if($(this).val() == '帐号'){
            $(this).val('');
        }
    }).blur(function() {
        if($(this).val() == ''){
            $(this).val('帐号');
        }
    });

    $('#password').focus(function() {
        if($(this).val() == '******'){
            $(this).val('');
        }
    }).blur(function() {
        if($(this).val() == ''){
            $(this).val('******');
        }
    });
    $('#rmNum').focus(function() {
        if($(this).val() == '验证码'){
            $(this).val('');
        }
        getYzm('');
    }).blur(function() {
        if($(this).val() == ''){
            $(this).val('验证码');
        }
    });


});


/***********************************回到顶部按钮******************************/
if ('undefined' != typeof($)) {
    $(function(){
        var btnNum = $('#ele-float-top').children().length,
            wrap = $('#ele-float-top-wrap'),
            wrapHeight = (btnNum - 1) * (40 + 2),
            gotop = $('#ele-float-top-up'),
            speedSet = 300,
            thebox = $('.ele-float-box-wrap'),
            boxwrap = '';

        wrap.height(wrapHeight);
        if(wrap.height() == wrapHeight){
            $('#ele-float-top').show();
        }

        $('.ele-float-top-code').hover(function(){
            $(this).children(thebox).stop(true, true).fadeIn(speedSet);
        },function(){
            $(this).children(thebox).stop(true, true).fadeOut(speedSet);
        });

        $("#ele-float-top-up").click(function(){
            $('html,body').animate( {scrollTop:0}, 1000, 'easeOutExpo' );
        });
        $(window).scroll(function() {
            if(navigator.userAgent.indexOf("MSIE") != -1) {
                var fadeSec = 200;
            }else{
                var fadeSec = 300;
            }
            if ( $(this).scrollTop() > 300){
                $('#ele-float-top-up').fadeIn(fadeSec);
            } else {
                $('#ele-float-top-up').stop().fadeOut(fadeSec);
            }

        });
    });
}



/********************************** IE低版本支持placeholder属性 *****************************/
var JPlaceHolder = {
    //检测
    _check : function(){
        return 'placeholder' in document.createElement('input');
    },
    //初始化
    init : function(){
        if(!this._check()){
            this.fix();
        }
    },
    //修复
    fix : function(){
        jQuery(':input[placeholder]').each(function(index, element) {

            var self = $(this), txt = self.attr('placeholder');
            self.wrap($('<div></div>').css({position:'relative', zoom:'1', border:'none', background:'none', padding:'none', margin:'none'}));
            var pos = self.position(), h = self.outerHeight(true), paddingleft = self.css('padding-left');

            var holder = $('<span></span>').text(txt).css({position:'absolute', left:pos.left, top:pos.top, height:h, lienHeight:h, paddingLeft:paddingleft, color:'#aaa'}).appendTo(self.parent());
            self.focusin(function(e) {
                holder.hide();
            }).focusout(function(e) {
                if(!self.val()){
                    holder.show();
                }
            });
            holder.click(function(e) {
                holder.hide();
                self.focus();
            });
        });
    }
};
//执行
$(function(){
    JPlaceHolder.init();
});