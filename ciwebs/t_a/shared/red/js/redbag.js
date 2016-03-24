red_site_domain='http://'+window.location.host;

function red_bag_html_(red_site_domain,pic){
    if(pic == '1'){
        pic = '';
    }
    red_bag_html='<link href="'+red_site_domain+'/shared/red'+pic+'/css/red_pc.css" rel="stylesheet" type="text/css">';
    red_bag_html+="<div id=\"wrapper_redbag\">";
    red_bag_html+="            <div class=\"box\" style=\"position: absolute; width: 100%; height: 100%; display:none\">";
    red_bag_html+="                <div class=\"demo\">";
    red_bag_html+="                    <a href=\"javascript:;\" class=\"flipInX\"><img src=\""+red_site_domain+"/shared/red"+pic+"/images/rt-ad.gif\"></a>";
    red_bag_html+="                </div>";
    red_bag_html+="";
    red_bag_html+="                <div id=\"dialogBg\"></div>";
    red_bag_html+="                <div id=\"dialog\" class=\"animated\">";
    red_bag_html+="                    <div class=\"red_bg\">";
    red_bag_html+="                        <div class=\"dialogTop\">";
    red_bag_html+="                            <a class=\"claseDialogBtn\"></a>";
    red_bag_html+="                        </div>";
    red_bag_html+="                        <div id=\"djs_redbag\" class=\"djs\"><strong></strong></div>";
    red_bag_html+="                        <div class=\"red_but \"><span id=\"red_sp\"><img src=\""+red_site_domain+"/shared/red"+pic+"/images/q_red.gif\" ></span></div>";
    red_bag_html+="                        <div class=\"red_ping hide\"><span id=\"red_sp\">派奖中...请稍后</span></div>";
    red_bag_html+="                        <div class=\"ren_box\" id=\"ren_box\">";
    red_bag_html+="                            <p id=\"qd\" class=\"red_p hide\"><span>恭喜抢到</span><span id=\"redmoney\">0</span><span>元</span></p>";
    red_bag_html+="                            <p id=\"late\" class=\"red_p hide\"><span>Sorry，来晚一步！</span></p>";
    red_bag_html+="                            <p id=\"is_ip\" class=\"red_p hide\"><span>该IP已领取此红包！<br><br>敬请期待，下一轮！</span></p>";
    red_bag_html+="                            <p id=\"cengji\" class=\"red_p hide\"><span>Sorry，权限不够！<br><br>请咨询在线客服！</span></p>";
    red_bag_html+="                            <p id=\"qg\" class=\"red_p hide\"><span>您已经抢过了!<br/>请明天再来!</span></p>";
    red_bag_html+="                            <p id=\"needlogin\" class=\"red_p hide\">您没有登录，<br/>请登录后再抢!</p>";
    red_bag_html+="                            <p id=\"shiwan\" class=\"red_p hide\">试玩用户无法参与!<br/>请注册真实账户!</p>";
    red_bag_html+="                            <span class=\"red_a\" href=\"javascript:;\">查看记录</span>";
    red_bag_html+="                        </div>";
    red_bag_html+="                        <div class=\"jilu\">";
    red_bag_html+="                            <div class=\"jl_text str1 str_wrap\">";
    red_bag_html+="                                ";
    red_bag_html+="                            </div>";
    red_bag_html+="                        </div>";
    red_bag_html+="                    </div>";
    red_bag_html+="                </div>";
    red_bag_html+="            </div>";
    red_bag_html+="</div>";
    red_bag_html+='<script type="text/javascript" src="'+red_site_domain+'/shared/red'+pic+'/js/jquery.liMarquee.js"></scritp>';
    return red_bag_html;
}
$('body').append("<div id='hdddddddddd'></div>"); 
//$('body').append('<link href="'+red_site_domain+'/shared/red/css/red_pc.css" rel="stylesheet" type="text/css">');
(function ($) {
    $.extend({
        timer: function (action,time) {
            var _timer;
            if ($.isFunction(action)) {
                (function () {
                    _timer = setInterval(function () {
                        action();
                    }, time);
                })();
            }
        }
    });
})(jQuery);

//redbag
(function($) {
    //红包js
    var iii = 0;
    $.redbag = {
        gameslist:{},
        isopen:false,
        intDiff:0,
        initbox:function(){
            var _this = this;
            if(_this.gameslist[0].opentime > 0){
                _this.intDiff = _this.gameslist[0].opencount;
                //_this.dtimer(gameslist[0].opencount,1);
            }else{
                _this.intDiff = _this.gameslist[0].closecount;
               //_this.dtimer(gameslist[0].closecount,2);
                if(!_this.isopen){
                    //抢红包事件
                    $("#red_sp").click(function(){
                        $(".red_but").hide();
                         $(".red_ping").show();
                        $.get(red_site_domain+"/index.php/games/red/snatch", {"rid":_this.gameslist[0].id},
                            function(data, status){
                            if("success" === status ){
                                $(".red_a").show();
                               if(data.Code == 0){
                                 $(".red_p").addClass("hide");
                                 $("#qd").removeClass("hide");
                                 $("#qd #redmoney").html(parseFloat(data.red.money).toFixed(2))
                               }else if(data.Code == 3){
                                 $("#qd").addClass("hide");
                                 $("#qg").removeClass("hide");//needlogin
                                 $(".red_a").hide();
                               }else if(data.Code == 4){
                                 $("#needlogin").removeClass("hide");
                                 $(".red_a").hide();
                               }else if(data.Code == 20){
                                $(".red_p").addClass("hide");
                                $('#shiwan').removeClass("hide");
                                $(".red_a").hide();
                               }else if(data.Code == 21){
                                $(".red_p").addClass("hide");
                                $('#cengji').removeClass("hide");
                                $(".red_a").hide();
                               }else if(data.Code == 22){
                                $(".red_p").addClass("hide");
                                $('#is_ip').removeClass("hide");
                                $(".red_a").hide();
                               }else{
                                $("#late").removeClass("hide");
                                $(".red_a").hide();
                               }
                                $(".red_ping").hide();
                                $(".red_but").show();
                            }
                            $("#ren_box").addClass("show");
                        });
                    });
                    $(".red_a").click(function(){
                        //查看记录
                        $.get(red_site_domain+"/index.php/games/red/snatch_info", {"rid":_this.gameslist[0].id},
                            function(data, status){
                            if("success" === status ){
                               if(data.Code == 0 && data.List.length > 0){
                               var  txt = "";
                                data.List.forEach(function(e){  
                                    txt += "<p><span>"+ e.username +": </span><span>"+ parseFloat(e.money).toFixed(2)+"元</span></p>";
                                });
                                $(".jilu .jl_text").children().html(txt)
                               }else{
                                $(".jilu .jl_text").children().html("<p><span>暂无记录</span><span></span></p>");
                               }
                            }
                        });

                        $("#ren_box").removeClass("show");
                        $(".jilu").addClass("show_2");
                    });
                }
                _this.isopen = true;
            }
            
        },
        refresh:function(){
            var _this = this;
                //红包活动
            $.get(red_site_domain+"/index.php/games/red", {},
                function(data, status){
                if("success" === status ){
                    if(0 ==data.Code && data.List.length > 0){
                        _this.gameslist = data.List;
                        var newpic = _this.gameslist[0].pic;
                        if(iii==0) {
                            $('#hdddddddddd').html(red_bag_html_(red_site_domain,newpic));
                            clickfunction();
                            document.cookie="pic="+newpic;
                            iii++;
                        }
                        var cookiepic = getCookie('pic');
                        if(cookiepic != newpic){
                            $('#hdddddddddd').html(red_bag_html_(red_site_domain,newpic));
                            clickfunction();
                            document.cookie="pic="+newpic;
                        }
                        $("#wrapper_redbag .box").show();
                        _this.initbox();
                    }else{
                        $("#wrapper_redbag .box").hide();
                    }
                }
            });
        },
        dtimer:function(){
            var _this = this;
            $.timer(function(){
                var day=0,  hour=0,  minute=0, second=0,type=1;//时间默认值 
                if(_this.gameslist.length >0){
                    if(_this.gameslist[0].opentime > 0){
                        type = 1;
                    }else{
                        type = 2;
                    } 
                }else{
                    return;
                }                    
                if(_this.intDiff > 0){  
                    day = Math.floor(_this.intDiff / (60 * 60 * 24));  
                    hour = Math.floor(_this.intDiff / (60 * 60)) - (day * 24);  
                    minute = Math.floor(_this.intDiff / 60) - (day * 24 * 60) - (hour * 60);  
                    second = Math.floor(_this.intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);  
                }  
                if (minute <= 9) minute = '0' + minute;  
                if (second <= 9) second = '0' + second;
               txt =  type==1 ? "离活动开始还差:":"离活动结束还差:";
                $("#djs_redbag").children().html(txt+hour+'时'+minute+'分'+second+'秒');
                _this.intDiff--;
            }, 1000); 
        }
    }
})(jQuery); 

function getCookie(name)
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg))
        return unescape(arr[2]);
    else
        return null;
}

var w,h,className;
function getSrceenWH(){
    w = $(window).width();
    h = $(window).height();
    $('#dialogBg').width(w).height(h);
}

window.onresize = function(){  
    getSrceenWH();
}  
$(window).resize();  

function clickfunction(){
    getSrceenWH();
    //显示弹框
    $('.box a').click(function(){
        className = $(this).attr('class');
        $('#dialogBg').fadeIn(300);
        $('#dialog').removeAttr('class').addClass('animated '+className+'').fadeIn();
    });
    
    //关闭弹窗
    $('.claseDialogBtn').click(function(){
        $('#dialogBg').fadeOut(300,function(){
            $('#dialog').addClass('bounceOutUp').fadeOut();
        });
    });

    $(".claseDialogBtn").click(function(){
        $("#ren_box").removeClass("show");
        $(".jilu").removeClass("show_2");
    });
}
$(document).ready(function(){
    $.redbag.refresh();
    $.redbag.dtimer();
})
 var get_redbag = function(){
        $.redbag.refresh();
}
$.timer(get_redbag, 10000);