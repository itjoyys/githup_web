define(["jquery"], function(e) {
	return e.fn.extend({
		tab: function(t) {
			var n = {
				autoPlay: !0,
				current: 0,
				mouseover: !1,
				className: {
					bd: ".tab-bd",
					hd: ".tab-hd",
					item: ".tab-item",
					active: "active"
				},
				speed: 0,
				delay: 400,
				selected: function(e, t) {}
			};
			return this.each(function() {
				function u(t, n) {
					s.removeClass(i.className.active), e(t).addClass(i.className.active);
					var r = s.index(t);
					o.eq(r).show(i.speed).siblings(i.className.bd).hide(), i.selected.call(t, n, o.eq(r), r)
				}
				t && (i = e.extend(n, t));
				var r = e(this),
					i = n,
					s = r.find(i.className.hd).find(i.className.item),
					o = r.find(i.className.bd);
				n.mouseover ? (s.on("mouseenter", function(e) {
					u(this, e)
				}), s.eq(n.current).trigger("mouseenter")) : (s.on("click", function(e) {
					return u(this, e), !1
				}), s.eq(n.current).trigger("click"))
			})
		}
	}), e
});