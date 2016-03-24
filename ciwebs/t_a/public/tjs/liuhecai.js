define(["/public/tjs/lottery.js"],
function(e) {
    "use strict";
    var t, n = null;
    return t = e.extend({
        initialize: function(e) {
            var n = this,
            r = _.extend({
                hln: !0,
                cookieKey: "hkc_QuickMode"
            },
            e);
            t.superclass.initialize.call(this, r),
            this.refreshTimer = new this.CountDown(this.refreshDuration),
            $(function() {
                n.options.lotteryPan = $("#h-nav-top").val(),
                n.options.panType = $("#h-nav-sub").val(),
                n.ballTpl = $("#tpl-prev-balls").html(),
                n.refresh(),
                n.highlightNav(),
                n._renderIframe()
            })
        },
        closeTimer: function(e,login) {
            function s(e) {
                var n, s;
                s = r.utils.secondsFormat(e),
                _.map(i,
                function(e, t) {
                    var n = s[t];
                    n > 0 ? s[t] = s[t] + e: s[t] = null
                }),
                n = r.tpl.to_html("{{days}}{{hours}}{{minutes}}{{seconds}}", s),
                t.text(n)
            }

            var t = $("#close-timer"),
            r = this,
            i = r.lang.date;
//            console.log(s);
//            if(!login.IsLogin){
//         	   r.close(),
//         	  this.ui.msg('请登录');
//            }else{
            n != null && n.stop(),
            e ? (n = new r.CountDown(e), n.update = function(e) {
                s(e)
            },
            n.done = function() {
                r.close(),
                t.text(r.lang.msg.closedGate)
            },
            n.start(), r.isOpen || r.open()) : (r.close(), t.text(r.lang.msg.closedGate))
           // }
        },
        afterBet: function() {
            this.reset(),
            this.refresh()
        },
        _updateLines: function(e) {
            if (!this.isOpen) return;
            this.$doc.find("[data-id]").each(function() {
                var t = $(this),
                n = t.attr("data-id"),
                r = t.attr("data-oid"),
                i = t.attr("data-odds");
                r && (n = r);
                if (n) {
                    var s = e[n];
                    s <= 0 ? 0 : s;
                    if (i) {
                        t.attr("data-odds", s);
                        if (s != null && s != "" && s.indexOf("/") != -1) {
                            var o = s.split("/");
                            t.attr("data-sub-odds", o[1])
                        }
                    } else t.text(s)
                }
            })
        },
        highlightNav: function() {

            var e = {
                top: $("#h-nav-top").val(),
                sub: $("#h-nav-sub").val()
            },
            t = $("#play-tab").find("a"),
            n = $("#sub-tabs").find("a"),
            r = "current";
            e.top !== "1" ? t.removeClass(r).filter("#nav-" + e.top).addClass(r) : t.eq(0).addClass(r),
            n.length ? (this.options.category = $.trim(n.filter("." + r).text())) : this.options.category = $.trim(t.filter("." + r).text())

        },
        afterRefresh: function(e) {
            var t = this,
            n = e.CloseCountdown;
            t.closeTimer(n <= 0 ? 0 : n,e),
            this.refreshTimer.update = function(e) {},
            this.refreshTimer.done = function() {
                t.refresh()
            },
            this.refreshTimer.restart(),
            this._updateLines(e.Lines)
        },
        getPreBall: function(e) {
            var t = this.ballTpl,
            n = this.tpl.to_html(t, {
                balls: e.PreResult
            });
            n && $("#prev-bs").html(n),
            $("#prev-bs").children().last().before('<i class="icon plus-icon"></i>'),
            $("#prev-issue").text(e.LotterNo)
        },
        getBetInfo: function() {

            var e = this,

            t = {
                lotteryPan: e.options.lotteryPan,
                panType: e.options.panType
            };
            return this.utils.ajax({
                url: "/index.php/lottery/lottery/liuhecaijson",
                data: JSON.stringify(t),
                success: function(t) {
                    if(t.Success){
                       e.updateAmount();
                    }else{
                       ShowDiv('MyDiv','fade');
                    }
                    // t.Success ? e.updateAmount() : e.ui.error(t.Msg),
                    t.ExtendObj && !t.ExtendObj.IsLogin && e.onAuthFail()
                }
            })
        }
    }),
    t
});