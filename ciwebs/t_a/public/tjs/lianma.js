define(["/public/tjs/liuhecai.js"], function(e) {
    var t = e.extend({
        initialize: function(e) {

            var n = this
              , r = _.extend({
                ballTpl: "",
                lotteryId: "liuhecai",
                panType: 20,
                sx: null ,
                useCategoryAsName: !0
            }, e);
            t.superclass.initialize.call(this, r),
            this.isQuickMode = !0,
            this._betType = 1,
            this.$betContainer = $(),
            this.$betTable = $("#j-bet-tb"),
            this.danNumber = null ,
            this.tuoNumber = null ;
// console.log(this);
if(this.options.category == '三全中' || this.options.category == '三中二'){
                min = 3;
            }else if(this.options.category == '四中一' || this.options.category == '四全中'){
                min = 4;
            }else{
                min = 2;
            }
            // var i = this._getGroup();
             var i = min;
            this._region = {
                min: i,
                max: i+3,
                group: i
            };
            this._limiMsg = this.utils.format(this.lang.msg.limit, this._region.min + "-" + this._region.max),
            this.listenAmount(),
            $(function() {
                n._init()
            }
            )
        },
        _getGroup: function() {
            // console.log(this);
            var e = 0
              , t = 21;
            if(this.options.category == '二中特' || this.options.category == '二全中' || this.options.category == '特串' ){
                t = 19;
            }else if(this.options.category == '四中一' || this.options.category == '四全中'){
                t = 20;
            }else{
                t=21;
            }
            switch (t) {
            case 17:
            case 18:
            case 19:
                e = 2;
                break;
            case 20:
                e = 4;
                break;
            case 21:
                e = 3
            }
            return e
        },
        _init: function() {
            function t(t, n) {
                var r = !1;
                return e.$betContainer.find(".input").not(n).each(function() {
                    if ($(this).val() == t)
                        return r = !0,
                        !1
                }
                ),
                r
            }
            var e = this;
            e.$checkbox = e.$doc.find(":checkbox");
            this.$betRadios = $("#j-cpd-tb").find(":radio");
            this.$betCondition = $("#j-bet-tb").find("tr.hide");
            e.listenAmount(),
            e.$checkbox.on("change", function() {
                if (e._betType == 2) {

                    var t = e.$checkbox.filter(":disabled").length
                      , n = e.$betContainer.find("input")
                      , r = false
                      , i = $(this).data("number") + "";
                    if(e._region.group == 3 || e._region.group == 4)
                        r = true
                    t == 0 && (e.danNumber = i,
                    n.eq(0).val(e.danNumber),
                    $(this).prop("disabled", !0));
                    r && t == 1 && (e.tuoNumber = i,
                    n.eq(1).val(e.tuoNumber),
                    $(this).prop("disabled", !0))

                }
                if (e.records.length < e._region.max){
                    $(this).parent('td').parent('tr').toggleClass("table-current");
                    e.getData();
                }else {
                    $(this).parent('td').parent('tr').removeClass("table-current");
                    if (!!this.checked || e.records.length != e._region.max)
                        e.ui.msg(e._limiMsg),
                        $(this).prop("checked", !1);
                    e.getData()
                }

            }
            ),
            this.$betRadios.on("change", function() {
                e._getBetType($(this))
            }
            ),
            this.$betRadios.eq(0).prop("checked", !0).trigger("change"),
            this.$doc.on("blur", ".j-left-num,.j-right-num", function() {
                var n = $(this).val();
                n && t(n, $(this)) && (e.ui.msg(e.lang.msg.sameNumbers),
                $(this).val(""))
            }
            )
        },
        _getBetType: function(e) {
            var t = parseInt(e.data("type"), 10), n = e.data("process"), r;
            if (!n)
                return;
            this.processor = n;
            if (!t)
                return;
            r = t - 1,
            this._betType = t,
            this.$betContainer = this.$betCondition.eq(r),
            this.$betCondition.hide(),
            this.$betContainer.show(),
            this.reset(),
            this._betType != 1 && this._betType != 2 && this.$checkbox.prop("disabled", !0)
        },
        _getInfoByElement: function(e) {
            var t = e.attr("data-id")
              , n = parseInt(e.attr("data-oid"), 10)
              , r = e.attr("data-odds")
              , i = $.trim(e.closest("td").prevAll(".table-odd").text())
              , s = parseFloat(e.attr("data-sub-odds"))
              , o = e.attr("data-key")
              , u = this.options.category;
// console.log(e);
            return {

                category: u,
                name: i,
                id: t,

                key: o,
                oddsId: n,
                odds: r,
                subOdds: s,
                amount: this.amount
            }
        },
        getRecordsHtml: function() {
            function u() {
                return _.pluck(i.records, "name")
            }
            var e, t, n, r, i = this, s = $("#tpl-confirm-group").html(), o = null ;
            this._betType == 1 && (e = [u().join(",")],
            t = this.utils.combination(this.records, this._region.min),
            n = t.length),
            this._betType == 2 && (o = {},
            o.t = _.without(u(), this.danNumber, this.tuoNumber).join(","),

            (this._region.group == 3 || this._region.group == 4) ? (n = this.records.length - 2,
            o.d = this.danNumber + "," + this.tuoNumber) : (n = this.records.length - 1,
            o.d = this.danNumber)),

            this._betType != 1 && this._betType != 2 && (n = _.reduce(this.records, function(e, t) {
                return e * t.length
            }
            , 1),
            r = _.groupBy(_.map(this.records, function(e) {
                return _.pluck(e, "name").join(",")
            }
            ), function(e, t) {
                return t % 2 == 0 ? "left" : "right"
            }
            )),
            this;

            var a = {
                category: this.options.category,
                dan: o,
                name: e,
                desc: r,
                group: n,
                single: this.amount.toFixed(2),
                total: (n * this.amount).toFixed(2)
            };
            return this.tpl.to_html(s, a)
        },
        reset: function() {
            this.$checkbox.prop({
                checked: !1,
                disabled: !1
            }),
            this.danNumber = null ,
            this.tuoNumber = null ,
            this.$betTable.find(":checked").prop("checked", !1),
            $(".wp-20").find('tr').removeClass('table-current'),
            this.$betTable.find(".input").val(""),
            this._betType != 1 && this._betType != 2 && (this.$checkbox.prop("disabled", !0),
            this._renderIframe()),
            $(".j-reset-html").html(""),
            t.superclass.reset.call(this)
        },
        _defaultValidator: function() {
            var e = this.records.length;
            return e < this._region.min || e > this._region.max ? (this.ui.msg(this._limiMsg),
            !1) : !0
        },
        _commonValidator: function(e, t) {
            var n = this.records
              , r = n[0]
              , i = n[1];
            return r,
            i,
            n,
            !r || !i || !r.length || !i.length ? (this.ui.msg(e),
            !1) : _.isEqual(r, i) ? (this.ui.msg(t),
            !1) : !0
        },
        _dtValidator: function() {
            return this._defaultValidator()
        },
        _sxValidator: function() {
            return this._commonValidator(this.options.lang.tipsDuiPengSX, this.options.lang.tipsDuiPengSxSame)
        },
        _wsValidator: function() {
            return this._commonValidator(this.options.lang.tipsDuiPengWeiShu, this.options.lang.tipsDuiPengWeiShuSame)
        },
        _swValidator: function() {
            return this._commonValidator(this.options.lang.tipsDuiPengSxWeiShu, this.options.lang.tipsDuiPengSxWeiShuSame)
        },
        _ryValidator: function() {
            return this._commonValidator(this.options.lang.tipsDuiPengAnyNum, this.options.lang.tipsDuiPengAnyNumSame)
        },
        valid: function() {
            var e = this["_" + this.processor + "Validator"], t;
            // console.log(e);
            return e && (t = e.call(this)),
            t,
            t ? this.amount ? !0 : (this.ui.msg(this.lang.msg.emptyAmount),
            !1) : !1
        },
        getData: function() {
            var min_h = 0;
            if(this.options.category == '三全中' || this.options.category == '三中二'){
                min_h = 3;
            }else if(this.options.category == '四中一' || this.options.category == '四全中' ){
                min_h = 4;
            }else{
                min_h = 2;
            }
            var ia = function(v){
                return toString.apply(v) === '[object Object]';
            }
            var e, t = {
                Id: null ,
                BetContext: null
            }, n = this["_" + this.processor + "Processor"];
            n && (t = n.call(this));
            var odd = ia(this.records[0])?this.records[0].odds:( this.records.length >0 && ia(this.records[0][0]))?this.records[0][0].odds:0;
            // alert(odd);
            e = $.extend(!0, {
                min:min_h,
                Id: null ,
                BetContext: "",
                Lines: odd,
                mingxi_1:this.options.lotteryPan,
                BetType: this.options.BetType,
                gname:this.options.category,
                IsForNumber: !0,
                IsTeMa: !1,
                Money: this.amount
            }, t),
            this.data = [e],
            this.data,
            t
        },
        _getOddsByItem: function(e) {
            //console.log(e);
            if (e) {
                var t = e.name;
                // console.log(e.subOdds);
                return   t
            }
        },
        _defaultProcessor: function() {
            var e = this, t = this._region.group, n = this.options, r = this.$betContainer, i, s, o, u, a;
            return n.BetType = 5,
            this.records = this.$checkbox.filter(":checked").map(function() {
                return e._getInfoByElement($(this))
            }
            ).get(),
            s = this.utils.combination(this.records, t),
            i = _.pluck(this.records, "name"),
            r.find("td").html(this.tpl.to_html($("#tpl-cpd").html(), {
                total: i.length,
                items: i.join(" "),
                group: s.length
            })),
            a = _.map(this.records, function(t) {
                return t.name
                // return o || (o = t.id),
                // {
                //     BetContext: e._getOddsByItem(t)
                // }
            }
            ),
            u = a.join(","),
            {
                Id: o,
                BetContext: u
            }
        },
        _dtProcessor: function() {
            this.options.BetType = 3;
            var r;
            var e = this, t, n = this._region.group;

            if(n == 3 || n == 4){
                r=2;
            }else{
                r=1;
            }
            var i;
            this.records = this.$checkbox.filter(":checked").map(function() {
                return e._getInfoByElement($(this))
            }
            ).get();
            var s = _.pluck(this.records, "name");
            this.$betContainer.find("td div").html(this.tpl.to_html($("#tpl-cpd").html(), {
                total: s.length,
                items: s.join(" "),
                group: this.records.length - r

            })),
            (n == 3 || n == 4) ? t = _.filter(this.records, function(t) {
                return t.name == e.danNumber || t.name == e.tuoNumber
            }
            ) : t = [_.find(this.records, {
                name: e.danNumber
            })],
            t,
            {
                name: e.danNumber
            },
            e;

            var o = _.map(this.records, function(t) {
                if (t.name == e.danNumber)
                    return;
                if ((n == 3 && t.name == e.tuoNumber) || (n == 4 && t.name == e.tuoNumber))
                    return;
                return t.name
                //e._getOddsByItem(t)
            }
            );

            t = _.map(t, function(t) {

                return t.name
            }
            );

            var u = t.join(",") + "&" + _.compact(o).join(",");
            return {
                Id: i,
                BetContext: u
            }
        },
        _commonProcessor: function(e, t) {
            function c(e) {
                return o.$betContainer.find(e).map(function() {
                    var e = parseInt("0" + $.trim($(this).val()), 10);
                    if (e > 0)
                        return e
                }
                )
            }
            function h(e) {
                var t = _.map(e, function(e) {
                    return '[data-number="' + e + '"]'
                }
                ).join(",");
                return o.$checkbox.filter(t).map(function() {
                    return o._getInfoByElement($(this))
                }
                ).get()
            }
            var n, r, i, s, o = this, u, a, f, l;
            this.records.length = 0;
            if (t)
                r = c(".j-left-num"),
                n = c(".j-right-num"),
                a = h(r),
                f = h(n);
            else {
                i = this.$betContainer.find(":checked"),
                r = $.trim(i.eq(0).val()),
                n = $.trim(i.eq(1).val());
                if (!r || !n)
                    return null ;
                r = e[r],
                n = e[n],
                o.processor == "sw" && _.each(r, function(e) {
                    _.contains(n, e) && (n = _.without(n, e))
                }
                ),
                a = h(r),
                f = h(n)
            }
            return this.records.push(a),
            this.records.push(f),
            l = _.map(this.records, function(e) {
                return _.map(e, function(e) {
                    return u || (u = e.id),
                    o._getOddsByItem(e)
                }
                ).join(",")
            }
            ),
            s = _.first(l) + "&" + _.last(l),
            {
                Id: u,
                BetContext: s
            }
        },
        _sxProcessor: function() {
            return this.options.BetType = 6,
            this._commonProcessor(this.options.sx)
        },
        _wsProcessor: function() {
            return this.options.BetType = 6,
            this._commonProcessor(this.options.ws)
        },
        _swProcessor: function() {
            this.options.BetType = 6;
            var e = $.extend({}, this.options.sx, this.options.ws);
            return this._commonProcessor(e)
        },
        _ryProcessor: function() {
            this.options.BetType = 6;
            var e = $.extend({}, this.options.sx, this.options.ws);
            return this._commonProcessor(e, !0)
        }
    });
    return t
}
);
