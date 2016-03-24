//common method object
var f_com = {};

/**
 * 設定 cookie
 * @param {String} theName  cookie 名稱
 * @param {String} theValue cookie 值
 * @param {[type]} theDay   [description]
 */
f_com.setCookie = function(theName, theValue, theDay) {
    if((theName != "") && (theValue != "")) {
        var expDay = "Web,01 Jan 2020 18:56:35 GMT";
        var setDay;
        if(theDay != null) {
            theDay = eval(theDay);
            setDay = new Date();
            setDay.setTime(setDay.getTime() + (theDay * 1000 * 60 * 60 * 24));
            expDay = setDay.toGMTString();
        }
        document.cookie = theName + "=" + escape(theValue) + ";path=/;expires=" + expDay + ";";
        return true;
    }
    return false;
};

/**
 * 刪除 cookie
 * @param  {String} theName 刪除的 cookie 名稱
 * @return {Boolean}
 */
f_com.delCookie = function(theName) {
    document.cookie = theName + "=;expires=Thu,01-Jan-70 00:00:01 GMT";
    return true;
};

/**
 * 取得 cookie
 * @param  {String} theName 要取值的 cookie 名稱
 * @return {Boolean}
 */
f_com.getCookie = function(theName) {
    var theCookie, start, end;
    theName += "=";
    theCookie = document.cookie + ";";
    start = theCookie.indexOf(theName);
    if(start != -1) {
        end = theCookie.indexOf(";", start);
        return unescape(theCookie.substring(start + theName.length, end));
    }
    return false;
};

/**
 * 取得使用者作業系統
 * @return {String} 回傳使用者的作業系統
 */
f_com.checkOs = function() {
    var os_type = "",
        windows = (navigator.userAgent.indexOf("Windows", 0) != -1) ? 1 : 0,
        mac = (navigator.userAgent.indexOf("Mac", 0) != -1) ? 1 : 0,
        linux = (navigator.userAgent.indexOf("Linux", 0) != -1) ? 1 : 0,
        unix = (navigator.userAgent.indexOf("X11", 0) != -1) ? 1 : 0;

    if (windows) {
        os_type = "windows";
    } else if (mac) {
        os_type = "mac";
    } else if (linux) {
        os_type = "lunix";
    } else if (unix) {
        os_type = "unix";
    }
    return os_type;
}

//電子遊藝載入unity物件
$(window).load(function() {
    var pageSite = f_com.getCookie('page_site');

    if(pageSite && (pageSite.toLowerCase() == 'game' || pageSite.toLowerCase() =='game3dfight')) {
        var unityObjectUrl = "/cl/js/game/UnityObject.js";
        var scriptTag = document.createElement("script");
        scriptTag.type = "text/javascript";
        scriptTag.src = unityObjectUrl;
        document.getElementsByTagName('head')[0].appendChild(scriptTag);
    }
});

/**
 * bm window.open 遊戲方法
 * @param  {String} url 開啟的網址
 * @param  {String} n   開啟的視窗名稱
 * @param  {Object} o   開啟的視窗設定值
 * @return {null}
 */
f_com.bm = function(url, n, o) {
    var conf = {
        width: '1024',
        height: '768',
        scrollbars: 'yes',
        resizable: 'no',
        status: 'no',
        location: 'yes',
        toolbar: 'no',
        menubar: 'no'
    };
    var _tmp = [];

    if(o == undefined) o = {};

    // 特例:如為電子遊藝-玉蒲團的搶先看 則設定寬高
    if (/(PriorityWatch)/.test(url)) {
        o = {
            'width': '1000',
            'height': '700',
            'scrollbars': 'no'
        };
    }

    //3D廳下載unity特例
    //if(/games\/(6|22|18)\?/ig.test(url) && !/platform=flash/ig.test(url)) {
    //    if(!document.getElementById('unityPlayer')) {
    //        $("body").append('<div style="position:relative;width:0px;height:0px;overflow:hidden;"><div id="unityPlayer"></div>');
    //    }
    //    var pageLang = f_com.getCookie("lang");
    //    var lang = (($.inArray(pageLang, ['zh-tw', 'zh-cn', 'en']) != -1) ? pageLang: 'zh-cn');
    //    var agent = (f_com.checkOs() == 'mac' ? f_com.checkOs() : 'windows');
    //
    //    if (typeof unityObject != "undefined") {
    //    	unityObject.embedUnity("unityPlayer", "/cl/tpl/commonFile/swf/TestUnity.unity3d", '0', '0', null, null, function(result){
    //            if (!result.success) {
    //                url = 'http://portal.dddgaming.com/docs/help/unity/' + agent + '/' + lang + '/default_' + lang + '.html';
    //            }
    //        });
    //    }
    //    openWindow();
    //}

    // 特例:如為真人娛樂-BB3D開啟遊戲 則以location的方式連結
    if (/(LiveBB3D|LiveBBK8|LiveBBRB|LiveBBBO)/.test(url)) {
        self.location = url;
    } else {
        openWindow();
    }

    //儲存 "最近瀏覽"
    if(o.browse != undefined && n == 'gameOpen'){
        BrowseRecords(o.browse.name,o.browse.gametype);
    }

    function openWindow() {
        for (var k in conf) {
            _tmp.push(k + '=' + ((o[k] == undefined) ? conf[k] : o[k]));
        }
        window.open(url, n, _tmp.join(',')).focus();
    }

    function BrowseRecords(_name,_gametype) {
        var BrowseName = _name + "BrowseRecords";
        //取得儲存 "最近瀏覽"
        var BrowseRecords = f_com.localStorage("get", BrowseName);

        if (!BrowseRecords) { //沒有 最近瀏覽
            f_com.localStorage("save", BrowseName, _gametype);
        }else if (BrowseRecords.match(_gametype) == null){
            BrowseRecords = BrowseRecords.split(",");
            BrowseRecords.push(_gametype);
            f_com.localStorage("save", BrowseName, BrowseRecords);
        }
    }
};




/**
 * 頁籤切換
 * @return {null}
 */
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

$.fn.idTabs = function() {
    var s = {
        start: 0,
        change: false,
        click: null,
        selected: ".selected",
        event: "!click"
    };

    for(var i = 0; i < arguments.length; ++i) {
        var a = arguments[i];
        switch(a.constructor) {
            case Object:
                break;
            case Boolean:
                s.change = a;
                break;
            case Number:
                s.start = a;
                break;
            case Function:
                s.click = a;
                break;
            case String:
                if(a.charAt(0) =='.') s.selected = a;
                else if(a.charAt(0) =='!') s.event = a;
                else s.start = a;
                break;
        }
    }

    return this.each(function() {
        if(s.selected.charAt(0) == '.') s.selected = s.selected.substr(1);
        if(s.event.charAt(0) == '!') s.event = s.event.substr(1);
        if(s.start==null) s.start = -1; //no tab selected
        tabs = this;
        var showId = function() {
            if($(this).is('.' + s.selected)) return s.change;
            var id = "#" + this.href.split("#")[1];
            var aList = [];//save tabs
            var idList = [];//save possible elements
            $("a", tabs).each(function() {
                if(this.href.match(/#/)) {
                    aList.push(this);
                    idList.push("#" + this.href.split("#")[1]);
                }
            });
            if(s.click && !s.click.apply(this,[id,idList,tabs,s])) return s.change;
            //Clear tabs, and hide all
            for(i in aList) $(aList[i]).removeClass(s.selected);
            for(i in idList) $(idList[i]).hide();
            //Select clicked tab and show content
            $(this).addClass(s.selected);
            $(id).show();
            return s.change; //Option for changing url
        };
        //Bind idTabs
        var list = $("a[href*='#']",tabs).unbind(s.event,showId).bind(s.event,showId);
        list.each(function() {
            $("#" + this.href.split('#')[1]).hide();
        });

        //Select default tab
        var test = false;
        if((test = list.filter('.' + s.selected)).length); //Select tab with selected class
        else if(typeof s.start == "number" && (test = list.eq(s.start)).length); //Select num tab
        else if(typeof s.start == "string" //Select tab linking to id
            &&(test = list.filter("[href*='#" + s.start + "']")).length);
        if(test) {
            test.removeClass(s.selected);
            test.trigger(s.event);
        } //Select tab

        return s; //return current settings (be creative)
    });
};

$.winBox = function(data, option) {
    var winbox = $('#windowBox');
    if(winbox.length == 0){
        $('body').after("<div id=\"windowBox\"></div>");
        winbox = $('#windowBox');
    }
    winbox.empty();
    var setting = {
        modal: true,
        resizable: false,
        width: 700,
        height: 'auto',
        minHeight: 'auto',
        buttons: {
            "CLOSE":function() {
                $(this).dialog('close');
            }
        }
    };
    if(option != undefined) {
        for(var k in option) {
            setting[k] = option[k];
        }
    }
    winbox.dialog(setting);
    if(data == undefined || data=="") {
        winbox.html('loading...');
        return;
    }
    if(winbox.dialog("isOpen") === true) {
        winbox.html(data);
        return;
    }
    winbox.dialog("open").html(data);

};

/**
 * 會員訊息
 */
function MemberMsg() {
    try {
        $.winBox("", {width:550, modal:false});
        $.ajax({
            type: 'POST',
            url: '?module=MemberMsg&method=getMsg',
            error: function(msg) {
                alert('error');
            },
            success: function(data) {
                $.winBox(data,{width: 550});
            }
        });
    }catch(e) {
        alert('error');
    }
}

/**
 * 安全上網
 * @param {String} theURL 連結網址
 * @param {Object} event  事件物件
 */
function MagicWindow(theURL, event) {
    var top = event.screenY;
    var left = event.screenX;
    var features = "top=" + top + ",left=" + left + ",scrollbars=yes,resizable=yes,width=250,height=120";
    window.open(theURL, '_blank', features);
}

/**
 * BB瀏覽器
 * @param {String} theURL 連結網址
 * @param {Object} event  事件物件
 */
function BrowserWindow(theURL, event) {
     var top = event.screenY;
     var left = event.screenX;
     var features = "top=" + top + ",left=" + left + ",scrollbars=yes,resizable=yes,width=800,height=600";
     window.open(theURL, '_blank', features);
}

/**
 * 歷史訊息
 */
function HotNewsHistory() {
     var features = 'height=600,width=800,top=0, left=0,scrollbars=yes,resizable=yes';
     window.open('/cl/?module=MFunction&method=ShowHotNewsHistory', 'HotNewsHistory', features);
}

/**
 * 彩金說明
 */
JackPotRule = function() {
    window.open('?module=MAdvertis&method=JackPotRule', 'popup','width=658,height=280,scrollbars=no,resizable=no, toolbar=no,directories=no,location=no,menubar=no, status=no,left=0,top=0');
};

//取得賽事數量
var _TmpGameQuantity = {};
function GetGameQuantity(color){
   $.getJSON('/cl/?module=MCountGameNums&method=CountGames',{ "noCache": Math.random()}, function(data){
       var RB_NUM = (data.RB.ALL_RB) ? data.RB.ALL_RB.ALL_RB : 0;
       for(var i in _TmpGameQuantity){
            $('#'+i).empty();
        }
        _TmpGameQuantity = {};
        for(var k in data){
            for(var k2 in data[k]){
                for(var k3 in data[k][k2]){
                    //判斷單式
                    if(/_S$/.test(k3)){
                        $('#'+k3+'_MENU').html(" "+data[k][k2][k3]+" ").css('color',color);
                        _TmpGameQuantity[k3+'_MENU'] = data[k][k2][k3];
                    }
                    $('#'+k+'_'+k3+'_GAME').html(" "+data[k][k2][k3]+" ").css('color',color);
                    _TmpGameQuantity[k+'_'+k3+'_GAME'] = data[k][k2][k3];
                }
            }
        }
        //顯示目前滾球數量
        if(data.RB.ALL_RB.ALL_RB >0){
            $("#RB_NUM").html(RB_NUM);
        }
   });
}

/**
 * 取得自訂賽程數量
 * @param {String} color 文字顏色
 */
function GetFavorQuantity(color) {
    $.getJSON('/cl/?module=MCountGameNums&method=CountFavoGames', {"noCache": Math.random()}, function(data) {
        var type = ['FT', 'BK', 'FB', 'IH', 'BS', 'TB', 'TN', 'F1'];
        for(var i = 0 ; i < type.length; i++) {
            if(data.Favo[type[i]] == undefined) {
                $('#' + type[i] + '_S_FAVORGAME').hide();
            }
        }
        for(var k in data.Favo) {
            for(var key in data.Favo[k]) {
                if(data.Favo[k][key] > 0) {
                    $('#' + key + '_FAVORGAME').show();
                    $('#' + key + '_FAVOR').html(" " + data.Favo[k][key] + " ").css('color',color);
                } else {
                    $('#' + key + '_FAVORGAME').show();
                }
            }
        }
   });
}

/**
 * 頁籤
 * @param  {String} div 頁籤元素
 * @return {null}
 */
$.fn.stab = function(div) {
    var area = this;
    var ul = $(this).children('li');

    ul.click(function() {
        var i = ul.index(this) || 0;

        ul.children('a').removeClass('stab');
        $(div).hide();
        $(div).get(i).style.display = '';
        $(this).children('a').addClass('stab');
    });
};

/**
 * 文字閃爍
 * @param id   jquery selecor
 * @param arr  ['#FFFFFF','#FF0000']
 * @param s    milliseconds
 */
function toggleColor(id, arr, s) {
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


/**
 * 遊戲規則說明另開
 * @param  {String} GAME_TYPE 遊戲代碼
 * @return {null}
 */
function gameRule(GAME_TYPE) {
    window.open('/cl/?module=MRule&method=ruleDescrip&args=' + GAME_TYPE, 'AllRule', 'width=1024,height=640,status=no,scrollbars=yes');
}

/*會員中心換頁*/
f_com.__options = {
    type: "GET",
    dataType: "html",
    module: "MACenterView",
    method: "memberdata",
    timeout: 30000,
    blockUI: true,
    blockId: "#page-container",
    blockStyle: "white",
    maskColor: "#000000"
}

/**
 * 設定會員中心物件參數
 * @param {String} key   物件的 key 值
 * @param {*} value      設定的值
 */
f_com.SetOptions = function(key, value) {
    f_com.__options[key] = value;
};

/**
 * 開啟會員中心頁面
 * @param {String} mo module
 * @param {String} me method
 */
f_com.MGetPager = function(mo, me) {
    window.open("/cl/?module=" + mo + "&other=" + me, "MACENTER", "top=50,left=50,width=1020,height=610,status=no,scrollbars=yes,resizable=no").focus();
};

/**
 * 會員中心 ajax 換頁
 * @param {Object} options   會員中心換頁設定參數
 * @param {Object} otherData 開啟頁面參數
 */
f_com.MChgPager = function(options, otherData) {

    var conf = $.extend(f_com.__options, options);

    var data = {"module": conf.module, "method": conf.method};
    if(otherData) {
        $.extend(data, otherData);
    }
    if(conf.blockUI) {
        $(conf.blockId).block({
            message: "<div id='MBlockImg'></div>",
            centerY: 0,
            css: {
                "background-color": "transparent",
                top: "300px",
                width: '0px',
                border: "none",
                cursor: "default"
            },
            overlayCSS: {
                cursor: "default",
                backgroundColor: conf.maskColor
            }
        });
    }
    $.ajax({
        type: conf.type,
        url: 'index.php',
        data: data,
        cache: false,
        dataType: conf.dataType,
        timeout: conf.timeout,
        error: function(data) {
            alert(JsBook.S_MSG_TRAN_BUSY);
        },
        success: function(data) {
            $('#MACenter-content').html(data);
        },
        complete: function() {
            if(conf.blockUI) {
                $(conf.blockId).unblock();
            }
        }
    });
}

/**
 * QQ連結 : 傳入QQ帳號
 * @param {String} acc QQ帳號
 */
f_com.QQOnlineService = function(acc) {
    window.open('http://wpa.qq.com/msgrd?v=3&uin=' + acc + '&site=qq&menu=yes', 'QQOnlineService', 'width=1024,height=800,status=no,scrollbars=no');
};

/**
 *localStorage
 * @param string _type (get ,sava ,clear ,clearall)
 * @param string _name
 * @param string _value
 * 不支援localStorage 使用cookie
 */
f_com.localStorage = function (_type, _name, _value) {
    if(_name == undefined) _name = "";
    if(_value == undefined) _value = "";
    if(window.localStorage) {
        switch (_type) {
           //取得
           case "get":
               return window.localStorage[_name];
               break;
           //記錄
           case "save":
               window.localStorage[_name] = _value;
               break;
           //刪除指定key
           case "clear":
               window.localStorage.removeItem(_name);
               break;
           //刪除全部localStorage
           case "clearall":
               window.localStorage.clear();
               break;
        }
    } else { //浏览暂不支持localStorage
        switch (_type) {
           //取得
           case "get":
               return f_com.getCookie(_name);
               break;
           //記錄
           case "save":
               f_com.setCookie(_name, _value);
               break;
           case "clear":
               f_com.delCookie(_name);
               break;
        }
    }
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

/**
 * 開啟bbin資訊中心
 * @param {String} mo module
 * @param {String} me method
 */
f_com.BBinGetPager = function(mo) {
    window.open("/cl/?module=" + mo, "bb", "top=50,left=50,width=1000,height=600,status=no,scrollbars=no,resizable=yes").focus();
};

/*bbin資訊中心-開啟外部連結*/
f_com.OpenLink = function(urllink) {
    window.open(urllink , 'outPage', "top=50,left=50,width=1000,height=610,status=no,scrollbars=yes,resizable=yes").focus();
};

/*開啟bbin資訊中心-指定頁面*/
f_com.BBinEnterPager = function(mo,me,ty) {
    //視窗寬度ub連覽器判斷
    if (!ty){
        window.open("/cl/?module=" + mo +"&PageItem=" + me , "BBinPage", "top=50,left=50,width=1000,height=600,status=no,scrollbars=no,resizable=yes").focus();
    }else{
        window.open("/cl/?module=" + mo +"&PageItem=" + me+"&type=" + ty , "BBinPage", "top=50,left=50,width=1000,height=600,status=no,scrollbars=no,resizable=yes").focus();
    }
};
/*開啟bbin資訊中心-指定頁面並無左側選單*/
f_com.BBinOnlyPager = function(mo,me,ty)  {
    if (!ty){
        window.open("/cl/?module=" + mo +"&OnlyPage=Y&PageItem=" + me , "BBinPage", "top=50,left=50,width=770,height=700,status=no,scrollbars=no,resizable=yes").focus();
        return;
    }

    window.open("/cl/?module=" + mo +"&PageItem=" + me+"&OnlyPage=Y&type=" + ty , "BBinPage", "top=50,left=50,width=770,height=700,status=no,scrollbars=no,resizable=yes").focus();
}
/*event活動開啟*/
f_com.OpenEventPage = function(_event) {
    event_url = location.protocol + '//' +location.hostname;
    event_url = event_url.replace(/(:\/\/www\.|:\/\/ball-m\.|:\/\/www-dev\.|:\/\/)/, "://event.");
    window.open( event_url + '/' + _event ).focus();
};

/*ckeditor 用天生贏家連結*/
f_com.OpenBornWinner = function() {
    event_url = location.protocol + '//' +location.hostname + '/ipl/events/esballBornWinner/bornwinner.php';
    window.open( event_url ).focus();
};
