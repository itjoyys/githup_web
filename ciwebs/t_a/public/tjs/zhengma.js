define(["/public/tjs/liuhecai.js"],
function(e) {
    var t = e.extend({
        initialize: function(e) {
            var n = this;
            t.superclass.initialize.call(this, e)
        },
        getData: function() {
            var e = this;
            this.records = this.$doc.find(".input").map(function() {
                var e = $(this),
                t,
                n,
                r,
                i,
                s;
                n = parseInt($.trim(e.val()), 10);
                if (n && !isNaN(n)) {
                    t = parseInt(e.attr("data-id"), 10),
                    r = $.trim(e.parent("td").prevAll(".table-odd").eq(0).text());
                    var o = e.parent("td").index();
                    return o = o == 2 ? o - 1 : o - o / 2,
                    i = e.closest("table").find("th").eq(o).text(),
                    s = e.attr("data-odds"),
                    {
                        category: i,
                        name: r,
                        id: t,
                        odds: s,
                        amount: n.toFixed(2)
                    }
                }
            }).get(),
            this.data = _.map(this.records,
            function(t, n) {
                // console.log(e);
                // console.log(t);
                return {
                    id: t.id,
                    BetContext: t.name,
                    Lines: t.odds,
                    BetType: e.options.BetType,
                    Money: t.amount,
                    IsTeMa: e.options.IsTeMa,
                    IsForNumber: e.options.IsForNumber,
                    gname:t.category,
                    mingxi_1:e.options.lotteryPan
                }
            })
        }
    });
    return t
});