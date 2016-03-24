define(["/public/tjs/liuhecai.js"], function(e) {
	var t;
	return t = e.extend({
		initialize: function(e) {
			var n = this,
				r = _.extend({
					ballTpl: "",
					lotteryId: 'liuhecai',
					panType: 22,
					useCategoryAsName: !0
				}, e);
			t.superclass.initialize.call(this, r), this.isQuickMode = !0, this.$checkbox = n.$doc.find(":checkbox"), this._limit = parseInt(r.panType === "0" ? "22" : r.panType, 10), this._region = {
				min: this._limit,
				max: this._limit + 3,
				group: 5
			}, this._records = [], this.isGroup = !1, this._limiMsg = this.utils.format(this.lang.msg.limit, this._region.min + "-" + this._region.max), this.listenAmount(), this._init()
		},
		_init: function() {
			var e = this;
			this.$groupHolder = $("#j-cpd-holder"), this.$checkbox.on("change", function() {
				var t = $(this),
					n;
				n = e._getInfoByElement($(this));
				if (parseInt(n.name) > 50 || parseInt(n.name) < 0) e.ui.msg(e.options.lang.Tips), $(this).prop("checked", !1);
				this.checked && (_.map(e.records, function(r) {
					if (r.name == n.name) {
						e.ui.msg(e.options.lang.Tips), t.prop("checked", !1);
						return
					}
				}), _.map(e._records, function(r) {
					if (r.name == n.name) {
						e.ui.msg(e.options.lang.Tips), t.prop("checked", !1), e._records = _.without(e._records, _.find(e._records, n));
						return
					}
				}));
				if (e.isGroup) {
					n = e._getInfoByElement($(this));
					if (!this.checked) e._records = _.without(e._records, _.find(e._records, n));
					else {
						if (e.records.length >= e._region.group) {
							e.ui.msg(e.utils.format(e.lang.msg.maxGroup, e._region.group)), $(this).prop("checked", !1);
							return
						}
						e._records.push(n), e._records.length == e._region.min && (e.records.push(_.clone(e._records)), e._records.length = 0, e.$checkbox.filter(":checked").prop("checked", !1))
					}
					e._setGroupInfo();
					return
				}
				if (e.records.length < e._region.max) e.getData();
				else {
					if (!!this.checked || e.records.length != e._region.max) e.ui.msg(e._limiMsg), $(this).prop("checked", !1);
					e.getData()
				}
			}), this.$doc.on("click", ".button-group .button", function(t) {
				t.preventDefault();
				var n = $(this),
					r = n.data("type"),
					i = "button-light";
				e.reset(), n.removeClass(i).siblings().addClass(i), e.isGroup = !!r, e.isGroup ? e._clearGroupHtml() : e.$groupHolder.hide()
			})
		},
		_clearGroupHtml: function() {
			return this.$groupHolder.show().find("td").empty()
		},
		_getInfoByElement: function(e) {
			var t = parseInt(e.attr("data-id"), 10),
				n = parseInt(e.attr("data-oid"), 10),
				r = parseFloat(e.attr("data-odds")),
				i = $.trim(e.closest("td").prevAll(".table-odd").text()),
				s = e.attr("data-key"),
				o = this.options.category;
			return {
				category: o,
				name: i,
				id: t,
				key: s,
				oddsId: n,
				odds: r,
				amount: this.amount
			}
		},
		_setGroupInfo: function() {
			var e = this,
				t = '{{#.}}【<em class="text-blue">{{name}}</em>】 {{/.}}',
				n = _.map(this.records, function(e) {
					return {
						name: _.pluck(e, "name").join(",")
					}
				}),
				r = this.tpl.to_html(t, n);
			n.length && ($("#j-cpd-holder").find("td").html(r), e._renderIframe())
		},
		reset: function() {
			this.$checkbox.prop("checked", !1), this._clearGroupHtml(), this._records.length = 0, t.superclass.reset.call(this)
		},
		valid: function() {
			var e = this.records.length;
			if (this.isGroup) {
				if (!e) return this.ui.msg(this.utils.format(this.lang.msg.minGroup, 1)), !1
			} else if (e < this._region.min || e > this._region.max) return this.ui.msg(this._limiMsg), !1;
			return this.amount ? !0 : (this.ui.msg(this.lang.msg.emptyAmount), !1)
		},
		getData: function() {
			// console.log(this);
			var e, t, n, r, i = this,
				s = this.options,
				o = _.clone(this.records);
			this.isGroup || (this.records = this.$checkbox.filter(":checked").map(function() {
				return i._getInfoByElement($(this))
			}).get()), this.isGroup ? (s.BetType = 4, r = _.map(o, function(e) {
				return n || (n = e[0].id), {
					BetContext:  _.pluck(e, "key").join(",") + "@" + _.min(_.pluck(e, "odds"))
				}
			}), e = _.pluck(r, "BetContext").join("&"), _.uniq(i.records)) : (s.BetType = 5, e = _.pluck(_.map(this.records, function(e) {
				return n || (n = e.id), {
					BetContext: e.key + "@" + e.odds
				}
			}), "BetContext").join(",")), t = {
				Id: n,
				gname:this.options.category,
        mingxi_1:this.options.lotteryPan,
        min:this._region.min,
				BetContext: e,
				Lines: 0,
				buzhong: 'fushi',
				BetType: i.options.BetType,
				IsForNumber: !0,
				IsTeMa: !1,
				Money: this.amount
			}, this.data = [t]
		},
		getRecordsHtml: function() {
			var e, t, n, r = $("#tpl-confirm-bz").html();
			this.isGroup ? (t = this.records, e = _.map(t, function(e) {
				return _.pluck(e, "name")
			})) : (e = [_.pluck(this.records, "name").join(",")], t = this.utils.combination(this.records, this._region.min)), n = t.length;
			var i = {
				category: this.options.category,
				name: e,
				group: n,
				single: this.amount,
				total: n * this.amount
			};
			return this.tpl.to_html(r, i)
		}
	}), t
});