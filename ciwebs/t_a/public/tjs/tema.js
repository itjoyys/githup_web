define(["/public/tjs/liuhecai.js"],
function(e) {
    "use strict";
    var t, n = "table-current";
    return t = e.extend({
        initialize: function(e) {
            var n = this,
            r = _.extend({
                quick: {
                    sx: null,
                    banbo: null,
                    dxds: null
                }
            },
            e);
            t.superclass.initialize.call(this, r),
            this.setQuickMode(!1),
            $(function() {
                n._init()
            })
        },
        _getElementsByIds: function(e, t) {
            var r = this;
            if ( !! e && !e.length) return;
            var i = _.map(e,
            function(e) {
                return e = e < 10 ? "0" + e: e,
                ".el" + e
            }).join(","),
            s = this.$allElements.filter(i),
            o = this.$amount.val();
            t ? (this.$allElements.filter("." + n).removeClass(n).find("input").val(""), s.addClass(n)) : s.eq(0).hasClass(n) && !t ? s.removeClass(n).find("input").val("") : s.addClass(n),
            this._setSelectedAmount(o)
        },
        _setSelectedAmount: function(e) {
            if (!e) return;
            this.$allElements.filter("." + n).find("input").val(e)
        },
        _getDxds: function() {
            var e = _.range(1, 50),
            t = _.groupBy(e,
            function(e) {
                return e > 24 ? "da": "xiao"
            }),
            n = _.groupBy(e,
            function(e) {
                return e % 2 == 0 ? "shuang": "dan"
            });
            this.options.quick.dxds = _.defaults({},
            t, n)
        },
        reset: function() {
            t.superclass.reset.call(this),
            this.$qctn.find("input").prop("checked", !1).end(),
            this.$play.find("." + n).removeClass(n)
        },
        _init: function() {
            var e, t, r, i = this.options.quick,
            s = this;
            this.$qctn = $("#hkc-quick-ctn"),
            this.$numCtn = $("#j-number-ctn"),
            this.$amount = $("#hkc-quick-amount"),
            this.$allElements = this.$doc.find("tr[data-id]");
            if (!i) return;
            e = i.sx,
            t = i.banbo,
            this._getDxds(),
            r = i.dxds,
            this.$doc.on("change", ".j-hkc-qb",
            function() {
                var e = $(this).val(),
                r = t[e];
                $("td.table-current").removeClass(n),
                s._getElementsByIds(r, !0)
            }),
            this.$doc.on("click", ".j-hkc-sx",
            function() {
                var t, i = $(this),
                o = i.data("sx"),
                u = i.data("key"),
                a = i.parent(),
                f;
                $(".j-hkc-qb:checked").prop("checked", !1).trigger("change"),
                a.toggleClass(n),
                o ? (_.each($("#tb-sx td"),
                function(t) {
                    $(t).hasClass(n) && (f = _.union(f, e[$(t).find(".j-hkc-sx").data("key")]))
                }), $("#tb-dxds").find("." + n).removeClass(n), s._getElementsByIds(f, !0)) : (t = a.hasClass(n), a.siblings("." + n).removeClass(n), $("#tb-sx").find("." + n).removeClass(n), f = r[u], s._getElementsByIds(f, t))
            }),
            this.$amount.on("change blur",
            function() {
                s._setSelectedAmount($(this).val())
            }),
            this.$allElements.on("click",
            function(e) {
                var t = $(this),
                r = t.hasClass(n);
               // if(t.isOpen==true){
                $(e.target).is(".input") ? r && t.removeClass(n) : r ? t.removeClass(n) : t.addClass(n).find("input").val(s.$amount.val())
                //}
                })
        }
    }),
    t
});