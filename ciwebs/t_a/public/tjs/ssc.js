define(["jquery", "/public/tjs/index.js"], function(e, t) {
	var n = t.extend({
		initialize: function(t) {
			var r = this,
				i = _.extend({
					lotteryId: 'cq_ssc'
				}, t);
			n.superclass.initialize.call(this, i), this.isAll = this._np == 0, e(function() {
				r.ballTpl = e("#tpl-prev-balls").html()
			})
		},
		_afterTabChange: function() {
			this.isAll = this._np == 0, this._luzCurrentTab = 0, this.isAll && (this.NumberPostion = "")
		},
		_updateHitBalls: function(t) {
			var r = this,
				i = this.betInfo,
				s = i.LuZhu,
				o, u, a, f, l = _.range(10),
				c = e("#trends");
			r.options.lotteryId == 9 && (l = _.range(1, 12));
			if (this.isAll) {
				o = _.pluck(s.slice(0, 5), "n"), f = i.ZongchuYilou, f && (f = f.hit);
				var h = _.map(f, function(e) {
					return {
						item: e,
						number: l
					}
				});
				a = e("#tpl-trends").html(), u = {
					hd: o,
					bd: h
				};
				if (_.isEqual(u, r.cache.cqAll)) {
					e("#luzhu").show(), c.show(), r._updateLuZhu(r.cache.cqTrends);
					return
				}
				r.cache.cqAll = u;
				var p = this.tpl.to_html(a, u);
				c.html(p).tab({
					mouseover: !0,
					selected: function() {
						var t = e(this).index() + 1,
							n = _.where(s, {
								p: t
							}),
							i = _.where(s, {
								p: 0
							}),
							o = _.flatten([n, i]);
						r.cache.cqTrends = o, r._updateLuZhu(o)
					}
				}).fadeIn("fast", function() {
					r._renderIframe()
				})
			} else e("#trends").hide(), n.superclass._updateLuZhu.call(this)
		}
	});
	return n
});