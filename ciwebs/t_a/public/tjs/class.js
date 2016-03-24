define([], function() {
	function e(t) {
		if (!(this instanceof e) && a(t)) return n(t)
	}

	function t(t) {
		var n, r;
		for (n in t) r = t[n], e.Mutators.hasOwnProperty(n) ? e.Mutators[n].call(this, r) : this.prototype[n] = r
	}

	function n(n) {
		return n.extend = e.extend, n.implement = t, n
	}

	function r() {}

	function s(e, t, n) {
		for (var r in t)
			if (t.hasOwnProperty(r)) {
				if (n && f(n, r) === -1) continue;
				r !== "prototype" && (e[r] = t[r])
			}
	}
	e.create = function(r, i) {
		function o() {
			r.apply(this, arguments), this.constructor === o && this.initialize && this.initialize.apply(this, arguments)
		}
		return a(r) || (i = r, r = null), i || (i = {}), r || (r = i.Extends || e), i.Extends = r, r !== e && s(o, r, r.StaticsWhiteList), t.call(o, i), n(o)
	}, e.extend = function(t) {
		return t || (t = {}), t.Extends = this, e.create(t)
	}, e.Mutators = {
		Extends: function(e) {
			var t = this.prototype,
				n = i(e.prototype);
			s(n, t), n.constructor = this, this.prototype = n, this.superclass = e.prototype
		},
		Implements: function(e) {
			u(e) || (e = [e]);
			var t = this.prototype,
				n;
			while (n = e.shift()) s(t, n.prototype || n)
		},
		Statics: function(e) {
			s(this, e)
		}
	};
	var i = Object.__proto__ ? function(e) {
			return {
				__proto__: e
			}
		} : function(e) {
			return r.prototype = e, new r
		},
		o = Object.prototype.toString,
		u = Array.isArray || function(e) {
			return o.call(e) === "[object Array]"
		},
		a = function(e) {
			return o.call(e) === "[object Function]"
		},
		f = Array.prototype.indexOf ? function(e, t) {
			return e.indexOf(t)
		} : function(e, t) {
			for (var n = 0, r = e.length; n < r; n++)
				if (e[n] === t) return n;
			return -1
		};
	return e
});