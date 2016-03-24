define(["/public/tjs/liuhecai.js"], function(e) {
	function r(e) {
		var t = 0;
		switch (e) {
			case "9":
			case "10":
			case "30":
			case "31":
				t = 2;
				break;
			case "11":
			case "12":
			case "32":
			case "33":
				t = 3;
				break;
			case "13":
			case "14":
			case "34":
			case "35":
				t = 4
		}
		return t
	}
	var t = 2,
		n = e.extend({
			initialize: function(e) {
				var i = this;
				n.superclass.initialize.call(this, {
					hln: !0,
					useCategoryAsName: !0
				}), this.isQuickMode = !0, i.$checkbox = i.$doc.find(":checkbox"), i.$radio = i.$doc.find(":radio"), i.listenAmount(), $(function() {
					i.$doc.on("change", ":checkbox", function() {
						var e = i.records.length + 1;
						e <= 8 ? i.getData() : (this.checked && ($(this).prop("checked", !1), i.ui.msg(i.utils.format(i.lang.msg.limit, t + "-8"))), i.getData())
					}), i.$radio.on("change", function() {
						var e = $(this),
							n = e.attr("panType"),
							s = e.attr("lotteryPan"),
							o = e.closest("td").index(),
							u = e.closest(".table").find("thead th").eq(o),
							a = e.parent("label").text();
						i.options.category = u.text() + a, t = r(n), i.options.panType = n, $(".j-content").load("/hkc/partialLianWeiContent?lotteryPan=" + s + "&panType=" + n + "&v=" + (new Date).getTime(), function(e) {
							i.$checkbox = i.$doc.find(":checkbox"), i.amount = 0, i.$play.find(".fb").val(""), i.refresh()
						})
					}), i.isOpen && i.$radio.eq(0).trigger("change")
				})
			},
			valid: function() {
				var e = this.records.length;
				return e < t || e > 8 ? (this.ui.msg(this.utils.format(this.lang.msg.limit, t + "-8")), !1) : this.amount ? !0 : (this.ui.msg(this.lang.msg.emptyAmount), !1)
			},
			reset: function() {
				this.$checkbox.prop("checked", !1), n.superclass.reset.call(this)
			},
			getData: function() {
				var e = this.amount,
					t, n = this.options.category;
				this.records = this.$checkbox.filter(":checked").map(function() {
					var t = $(this),
						r = t.attr("data-id"),
						i = parseInt(t.attr("data-oid"), 10),
						s = parseFloat(t.attr("data-odds")),
						o = t.attr("data-key"),
						u = $.trim(t.parent("td").prevAll(".table-odd").eq(0).text());
					if (u) return {
						category: n,
						name: u,
						id: r,
						oddsId: i,
						key: o,
						odds: s,
						amount: e.toFixed(2)
					}
				}).get();
				var r = _.map(this.records, function(e) {
					if (e.oddsId) return t || (t = e.id), {
						Lines: e.odds,
						BetContext: e.key + "@" + e.odds
					}
				}, this);
				if (!r.length) return;
				console.log(this);
				var i = {
					Id: t,
					gname:this.options.category,
          mingxi_1:this.options.lotteryPan,
          min:this.options.panType,
					BetContext: _.pluck(r, "BetContext").join(","),
					Lines: 0,
					BetType: 5,
					IsForNumber: !0,
					IsTeMa: !1,
					Money: e
				};
				this.data = [i]
			},
			getRecordsHtml: function() {
				var e = this.records,
					n = this.utils.combination(e, t);
				n = _.map(n, function(e) {
					var t = _.first(e),
						n = _.pluck(e, "name").join("，"),
						r = _.min(_.pluck(e, "odds"));
					return {
						category: t.category,
						name: n,
						id: t.id,
						odds: r,
						amount: t.amount
					}
				});
				var r = $("#j-confirm-tpl").html(),
					i, s = n.length,
					o = 0;
				this.isQuickMode ? o = this.amount * s : _.each(n, function(e) {
					o += e.amount
				});
				var u = {
					col: s > 6 ? "two-col" : "",
					currency: this.cfg.currency,
					total: o,
					sum: s,
					items: n
				};
				return i = this.tpl.render(r, u), i
			}
		});
	return n
});