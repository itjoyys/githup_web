define(["/public/tjs/liuhecai.js"], function(e) {
	var t = e.extend({
		initialize: function(e) {
			var n = this;
			t.superclass.initialize.call(this, {
				hln: !0,
				useCategoryAsName: !0
			}), this.isQuickMode = !0, $(function() {

				n.$ctn = $("#parlay-ctn"), n.$radio = n.$ctn.find("input"), n.$parlayForm = $("#parlay-form"), n._setRadio(), n.listenAmount(), n.$radio.on("change", function() {
					// console.log(_.pluck(n.records, "name"));

					var nnn =  _.pluck(n.records, "name");
					nnn = JSON.stringify(nnn);
					var nnn1 = nnn.split("*").length;
					n.getData()
				})
			})
		},
		_setRadio: function() {
			this._.each(this.$ctn.find("tbody tr"), function(e, t) {
				this._.each($(e).find(":radio"), function(e, n) {
					var r;
					switch (n) {
						case 0:
						case 1:
							r = 0;
							break;
						case 2:
						case 3:
							r = 1;
							break;
						case 4:
						case 5:
						case 6:
							r = 2;
							break;
						default:
					}
					$(e).prop("name", "lm_" + t + "_" + r)
				}, this)
			}, this)
		},
		reset: function() {
			this.$radio.prop("checked", !1), t.superclass.reset.call(this)
		},
		valid: function() {
			var e = this.records.length;
			if (e >= 1) {
				var t = this.records[0].name.split("*").length;
				return t < 2 || t > 8 ? (this.ui.msg(this.utils.format(this.lang.msg.hhh, "2-8")), !1) : this.amount ? !0 : (this.ui.msg(this.lang.msg.emptyAmount), !1)
			}
			return this.ui.msg(this.utils.format(this.lang.msg.hhh, "2-8")), !1
		},
		getData: function() {
			var e = this,
				t = this.amount,
				n;
			this.records = this.$radio.filter(":checked").map(function() {
				var e = $(this),
					n = parseInt(e.attr("data-id"), 10),
					r = parseInt(e.attr("data-oid"), 10),
					i = e.attr("data-odds"),
					s = e.attr("data-key"),
					o = e.closest("td").prevAll(".table-odd").text(),
					u = e.closest("table").find("th").eq(e.closest("td").index()).text();

				return {
					category: o,
					name: u,
					id: n,
					key: s,
					oddsId: r,
					odds: i,
					amount: t
				}
			}).get();
			var r = _.map(this.records, function(e) {
				if (e.oddsId) return n || (n = e.id), {
					Lines: e.odds,
					BetContext: e.key,
					recordsBetContent: e.category + e.name + "@" + e.odds,
					nn:e.category
				}
			}, this);
			if (!r.length) return;
			var i = _.reduce(_.pluck(r, "Lines"), function(e, t) {
				return e * t
			}, 1);
			this.records = [{
				name: _.pluck(r, "recordsBetContent").join("*"),
				odds: i.toFixed(3),
				amount: t.toFixed(2),
				category: e.options.category
			}];

			var s = {
				Id: n,
				mingxi_1: n,
				BetContext: _.pluck(r, "BetContext").join(","),
				gname:_.pluck(r, "nn").join(","),
				Lines: i.toFixed(3),
				BetType: 2,
				IsForNumber: !0,
				IsTeMa: !1,
				Money: t
			};
			this.data = [s]
		}
	});
	return t
});