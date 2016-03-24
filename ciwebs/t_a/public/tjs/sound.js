
define(["/lottery_type/public/tjs/utils.js"], function(e) {
	function n(e) {
		if (window.document[e]) return window.document[e];
		if (navigator.appName.indexOf("Microsoft Internet") != -1) return document.getElementById(e);
		if (document.embeds && document.embeds[e]) return document.embeds[e]
	}

	function r(e) {
		var t = n("asound");
		t && (t.SetVariable("f", e), t.GotoFrame(1))
	}

	function i() {
		var e = n("asound");
		e && (e.SetVariable("f", "not-exist.mp3"), e.GotoFrame(1))
	}
	window.asplay = r, window.asstop = r;
	var t = ry_lottery_config;
	return {
		play: function() {
			r(e.format(t.assets, "other/open.mp3"))
		}
	}
});