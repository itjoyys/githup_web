define(["/public/tjs/tabs.js"], function(e) {
	function t() {
		this.init()
	}
	return t.prototype.init = function() {
		var t = this;
		e(function() {
			var t = e('[data-wdiget="tab"]'),
				n = t.data("current");
			t.tab({
				current: n - 1,
				className: {
					hd: ".tab-colored-hd",
					bd: ".tab-colored-bd",
					item: ".tab-colored-item",
					active: "tab-colored-active"
				},
				selected: function() {}
			})
		})
	}, t
});