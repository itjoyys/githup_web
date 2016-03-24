define(["jquery", "dialog"], function(e, t) {
	function r(e, t) {
		var n;
		if (e == null || e == "--" || e.length == 0) return "--";
		e.substring(0, 5) == "/Date" ? (e = e.replace("/Date(", "").replace(")/", ""), e.indexOf("+") > 0 ? e = e.substring(0, e.indexOf("+")) : e.indexOf("-") > 0 && (e = e.substring(0, e.indexOf("-"))), n = new Date(parseInt(e, 10))) : n = new Date(e);
		var r = n.getMonth() + 1 < 10 ? "0" + (n.getMonth() + 1) : n.getMonth() + 1,
			i = n.getDate() < 10 ? "0" + n.getDate() : n.getDate(),
			s = n.getHours() + t - 8;
		s = s < 10 ? "0" + s : s;
		var o = n.getMinutes() < 10 ? "0" + n.getMinutes() : n.getMinutes(),
			u = n.getSeconds() < 10 ? "0" + n.getSeconds() : n.getSeconds();
		return n.getFullYear() + "-" + r + "-" + i + " " + s + ":" + o + ":" + u
	}
	var n = {};
	return n.format = function(e) {
		var t = Array.prototype.slice.call(arguments, 1);
		return e.replace(/{(\d+)}/g, function(e, n) {
			return typeof t[n] != "undefined" ? t[n] : e
		})
	}, n.dateFormat = function(e, t) {
		return r(e, parseInt(t))
	}, n.padleft = function(e, t) {
		t = (t || 8) - e.toString().length;
		var n = "";
		for (var r = 0; r < t; r++) n += "0";
		return n + e
	}, n.combination = function(e, t) {
		var n = [];
		return function r(e, t, i) {
			if (i == 0) return n.push(e);
			for (var s = 0, o = t.length; s <= o - i; s++) r(e.concat(t[s]), t.slice(s + 1), i - 1)
		}([], e, t), n
	}, n.ajax = function(n, r) {
		var i = {
				beforeSend: function() {
					t.loading.show(r)
				},
				complete: function() {
					t.loading.hide()
				}
			},
			s = {
				type: "POST",
				contentType: "application/json",
				error: function(e) {
					e.status == 888 && (t.error(e.responseText), setTimeout(function() {
						location.href = e.getResponseHeader("Location")
					}, 2500))
				}
			};
		r && (s = e.extend(!0, s, i));
		var o = e.extend(!0, s, n);
		return e.ajax(o)
	}, n.secondsFormat = function(e, t) {
		var n = Math.floor(parseInt(e / 3600) / 24),
			r = parseInt(e / 3600) % 24,
			i = parseInt(e / 60) % 60,
			s = e % 60;
		return t ? (r && (i += r * 60), (i < 10 ? "0" + i : i) + ":" + (s < 10 ? "0" + s : s)) : {
			days: n,
			hours: r < 10 ? "0" + r : r,
			minutes: i < 10 ? "0" + i : i,
			seconds: s < 10 ? "0" + s : s
		}
	}, n
});