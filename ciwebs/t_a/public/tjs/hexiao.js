define(["/public/tjs/liuhecai.js"],
function(e) {
    var t = 2,
    n = e.extend({
        initialize: function(e) {
            var t = this;
            n.superclass.initialize.call(this, {
                hln: !0,
                useCategoryAsName: !0
            }),
            this.isQuickMode = !0,
            this.options.lang = e.lang,
            this.options.panType =e.panType,
            t.$checkbox = t.$doc.find(":checkbox"),
            t.listenAmount();
            var r = {
                poultry: ["2", "7", "8", "10", "11", "12"],
                wild: ["1", "3", "4", "5", "6", "9"]
            };
            this.animals = r,
            $(function() {
                r.$wild = _.map(r.wild,
                function(e) {
                    return "#j-" + e
                }).join(","),
                r.$poultry = _.map(r.poultry,
                function(e) {
                    return "#j-" + e
                }).join(","),
                t.$doc.on("click", ".j-animals",
                function(e) {

                    e.preventDefault();
                    var n, i = $(this).data("wild");
                    t.$checkbox.prop("checked", !1),
                    i ? n = t.$checkbox.filter(r.$wild) : n = t.$checkbox.filter(r.$poultry),
                    n.prop("checked", !0)
                }),
                t.$doc.on("change", ":checkbox",
                function() {
                	var min = parseInt(t.options.panType);
                	var max = min;
                	var langtext = "只允许选择"+min+"个生肖";
                    var e = t.records[0];
                    if(min == 6){
                        var hh_num = 0;
                        $("input[type='checkbox']").each(function() {
                            if($(this).attr("checked")==undefined){
                            }else{
                                hh_num ++;
                            }
                        });
                        if(hh_num > 6){
                            t.ui.msg(langtext), !1, $(this).prop("checked", !1), t.getData()
                        }
                    }else{
                        if(e){
                        var r = t.records[0].name.split(",").length;
                        }else{
                            var r = 1;
                        }
                        t.getData(),
                         r <min ||r>max (t.ui.msg(langtext), !1, $(this).prop("checked", !1), t.getData())
                    }



                   // t.records.length > 2 && (t.ui.msg(this.options.lang.tipsMustChooseSixSX), $(this).prop("checked", !1), t.getData())
                })
            })
        },
        valid: function() {
        	var min = parseInt(this.options.panType);
        	var max = min;
        	max = max>12?12:max;
        	var langtext = "只允许选择"+min+"个生肖";
            function e(e, t) {
                if (e === t) return ! 0;
                if (e == null || t == null) return ! 1;
                if (e.length != t.length) return ! 1;
                for (var n = 0; n < e.length; ++n) if (e[n] !== t[n]) return ! 1;
                return ! 0
            }
            var t = this.records[0].num,
            n = this.records.length;
            t = t.split(",");
            if (n >= 1) {
                var r = this.records[0].name.split(",").length;
                return r <min ||r>max ? (this.ui.msg(langtext), !1) : this.amount ? !0 : (this.ui.msg(this.lang.msg.emptyAmount), !1)
            }
            return this.ui.msg(langtext),
            !1
        },
        getData: function() {
            // console.log(this);
            var e = this.amount,
            t = $(".j-odds"),
            n = parseFloat(t.text()),
            r = t.data("id"),
            i = "",
            s = "",
            hh = this.options.lotteryPan,
            o = this.options.category;
            this.records = this.$checkbox.filter(":checked").map(function() {
                var t = $(this),
                i = t.attr("data-num"),
                s = t.attr("data-text"),
                u = $.trim(t.parent().parent("td").prevAll(".table-odd").eq(0).text());
                return {
                    category: o,
                    num: i,


                    name: s,
                    lanName: u,
                    id: r,
                    odds: n,
                    amount: e.toFixed(2)
                }
            }).get(),
            i = _.pluck(this.records, "name").join(","),
            lanNames = _.pluck(this.records, "lanName").join(","),
            s = _.pluck(this.records, "num").join(","),
            this.records = [{
                category: o,
                id: r,
                name: lanNames,
                odds: n,
                num: s,
                amount: e.toFixed(2)
            }];
            var u = {
                Id: r,
                mingxi_1:hh,
                gname:o,
                BetContext: i,
                Lines: n,
                BetType: 1,
                IsForNumber: !1,
                IsTeMa: !1,
                Money: e
            };
            this.data = [u]
        },
        reset: function() {
            this.$checkbox.prop("checked", !1),
            $(".fb").val("")
        }
    });
    return n
});