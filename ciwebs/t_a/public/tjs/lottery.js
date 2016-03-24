//     Underscore.js 1.7.0
//     http://underscorejs.org
//     (c) 2009-2014 Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
//     Underscore may be freely distributed under the MIT license.

/*! artDialog v6.0.2 | https://github.com/aui/artDialog */

/*!
 * mustache.js - Logic-less {{mustache}} templates with JavaScript
 * http://github.com/janl/mustache.js
 */

/*!
 * jQuery Cookie Plugin v1.4.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */
(function() {
	var e = this,
		t = e._,
		n = Array.prototype,
		r = Object.prototype,
		i = Function.prototype,
		s = n.push,
		o = n.slice,
		u = n.concat,
		a = r.toString,
		f = r.hasOwnProperty,
		l = Array.isArray,
		c = Object.keys,
		h = i.bind,
		p = function(e) {
			if (e instanceof p) return e;
			if (!(this instanceof p)) return new p(e);
			this._wrapped = e
		};
	typeof exports != "undefined" ? (typeof module != "undefined" && module.exports && (exports = module.exports = p), exports._ = p) : e._ = p, p.VERSION = "1.7.0";
	var d = function(e, t, n) {
		if (t === void 0) return e;
		switch (n == null ? 3 : n) {
			case 1:
				return function(n) {
					return e.call(t, n)
				};
			case 2:
				return function(n, r) {
					return e.call(t, n, r)
				};
			case 3:
				return function(n, r, i) {
					return e.call(t, n, r, i)
				};
			case 4:
				return function(n, r, i, s) {
					return e.call(t, n, r, i, s)
				}
		}
		return function() {
			return e.apply(t, arguments)
		}
	};

	p.iteratee = function(e, t, n) {
		return e == null ? p.identity : p.isFunction(e) ? d(e, t, n) : p.isObject(e) ? p.matches(e) : p.property(e)
	}, p.each = p.forEach = function(e, t, n) {
		if (e == null) return e;
		t = d(t, n);
		var r, i = e.length;
		if (i === +i)
			for (r = 0; r < i; r++) t(e[r], r, e);
		else {
			var s = p.keys(e);
			for (r = 0, i = s.length; r < i; r++) t(e[s[r]], s[r], e)
		}
		return e
	}, p.map = p.collect = function(e, t, n) {
		if (e == null) return [];
		t = p.iteratee(t, n);
		var r = e.length !== +e.length && p.keys(e),
			i = (r || e).length,
			s = Array(i),
			o;
		for (var u = 0; u < i; u++) o = r ? r[u] : u, s[u] = t(e[o], o, e);
		return s
	};
	var v = "Reduce of empty array with no initial value";
	p.reduce = p.foldl = p.inject = function(e, t, n, r) {
		e == null && (e = []), t = d(t, r, 4);
		var i = e.length !== +e.length && p.keys(e),
			s = (i || e).length,
			o = 0,
			u;
		if (arguments.length < 3) {
			if (!s) throw new TypeError(v);
			n = e[i ? i[o++] : o++]
		}
		for (; o < s; o++) u = i ? i[o] : o, n = t(n, e[u], u, e);
		return n
	}, p.reduceRight = p.foldr = function(e, t, n, r) {
		e == null && (e = []), t = d(t, r, 4);
		var i = e.length !== +e.length && p.keys(e),
			s = (i || e).length,
			o;
		if (arguments.length < 3) {
			if (!s) throw new TypeError(v);
			n = e[i ? i[--s] : --s]
		}
		while (s--) o = i ? i[s] : s, n = t(n, e[o], o, e);
		return n
	}, p.find = p.detect = function(e, t, n) {
		var r;
		return t = p.iteratee(t, n), p.some(e, function(e, n, i) {
			if (t(e, n, i)) return r = e, !0
		}), r
	}, p.filter = p.select = function(e, t, n) {
		var r = [];
		return e == null ? r : (t = p.iteratee(t, n), p.each(e, function(e, n, i) {
			t(e, n, i) && r.push(e)
		}), r)
	}, p.reject = function(e, t, n) {
		return p.filter(e, p.negate(p.iteratee(t)), n)
	}, p.every = p.all = function(e, t, n) {
		if (e == null) return !0;
		t = p.iteratee(t, n);
		var r = e.length !== +e.length && p.keys(e),
			i = (r || e).length,
			s, o;
		for (s = 0; s < i; s++) {
			o = r ? r[s] : s;
			if (!t(e[o], o, e)) return !1
		}
		return !0
	}, p.some = p.any = function(e, t, n) {
		if (e == null) return !1;
		t = p.iteratee(t, n);
		var r = e.length !== +e.length && p.keys(e),
			i = (r || e).length,
			s, o;
		for (s = 0; s < i; s++) {
			o = r ? r[s] : s;
			if (t(e[o], o, e)) return !0
		}
		return !1
	}, p.contains = p.include = function(e, t) {
		return e == null ? !1 : (e.length !== +e.length && (e = p.values(e)), p.indexOf(e, t) >= 0)
	}, p.invoke = function(e, t) {
		var n = o.call(arguments, 2),
			r = p.isFunction(t);
		return p.map(e, function(e) {
			return (r ? t : e[t]).apply(e, n)
		})
	}, p.pluck = function(e, t) {
		return p.map(e, p.property(t))
	}, p.where = function(e, t) {
		return p.filter(e, p.matches(t))
	}, p.findWhere = function(e, t) {
		return p.find(e, p.matches(t))
	}, p.max = function(e, t, n) {
		var r = -Infinity,
			i = -Infinity,
			s, o;
		if (t == null && e != null) {
			e = e.length === +e.length ? e : p.values(e);
			for (var u = 0, a = e.length; u < a; u++) s = e[u], s > r && (r = s)
		} else t = p.iteratee(t, n), p.each(e, function(e, n, s) {
			o = t(e, n, s);
			if (o > i || o === -Infinity && r === -Infinity) r = e, i = o
		});
		return r
	}, p.min = function(e, t, n) {
		var r = Infinity,
			i = Infinity,
			s, o;
		if (t == null && e != null) {
			e = e.length === +e.length ? e : p.values(e);
			for (var u = 0, a = e.length; u < a; u++) s = e[u], s < r && (r = s)
		} else t = p.iteratee(t, n), p.each(e, function(e, n, s) {
			o = t(e, n, s);
			if (o < i || o === Infinity && r === Infinity) r = e, i = o
		});
		return r
	}, p.shuffle = function(e) {
		var t = e && e.length === +e.length ? e : p.values(e),
			n = t.length,
			r = Array(n);
		for (var i = 0, s; i < n; i++) s = p.random(0, i), s !== i && (r[i] = r[s]), r[s] = t[i];
		return r
	}, p.sample = function(e, t, n) {
		return t == null || n ? (e.length !== +e.length && (e = p.values(e)), e[p.random(e.length - 1)]) : p.shuffle(e).slice(0, Math.max(0, t))
	}, p.sortBy = function(e, t, n) {
		return t = p.iteratee(t, n), p.pluck(p.map(e, function(e, n, r) {
			return {
				value: e,
				index: n,
				criteria: t(e, n, r)
			}
		}).sort(function(e, t) {
			var n = e.criteria,
				r = t.criteria;
			if (n !== r) {
				if (n > r || n === void 0) return 1;
				if (n < r || r === void 0) return -1
			}
			return e.index - t.index
		}), "value")
	};
	var m = function(e) {
		return function(t, n, r) {
			var i = {};
			return n = p.iteratee(n, r), p.each(t, function(r, s) {
				var o = n(r, s, t);
				e(i, r, o)
			}), i
		}
	};
	p.groupBy = m(function(e, t, n) {
		p.has(e, n) ? e[n].push(t) : e[n] = [t]
	}), p.indexBy = m(function(e, t, n) {
		e[n] = t
	}), p.countBy = m(function(e, t, n) {
		p.has(e, n) ? e[n]++ : e[n] = 1
	}), p.sortedIndex = function(e, t, n, r) {
		n = p.iteratee(n, r, 1);
		var i = n(t),
			s = 0,
			o = e.length;
		while (s < o) {
			var u = s + o >>> 1;
			n(e[u]) < i ? s = u + 1 : o = u
		}
		return s
	}, p.toArray = function(e) {
		return e ? p.isArray(e) ? o.call(e) : e.length === +e.length ? p.map(e, p.identity) : p.values(e) : []
	}, p.size = function(e) {
		return e == null ? 0 : e.length === +e.length ? e.length : p.keys(e).length
	}, p.partition = function(e, t, n) {
		t = p.iteratee(t, n);
		var r = [],
			i = [];
		return p.each(e, function(e, n, s) {
			(t(e, n, s) ? r : i).push(e)
		}), [r, i]
	}, p.first = p.head = p.take = function(e, t, n) {
		return e == null ? void 0 : t == null || n ? e[0] : t < 0 ? [] : o.call(e, 0, t)
	}, p.initial = function(e, t, n) {
		return o.call(e, 0, Math.max(0, e.length - (t == null || n ? 1 : t)))
	}, p.last = function(e, t, n) {
		return e == null ? void 0 : t == null || n ? e[e.length - 1] : o.call(e, Math.max(e.length - t, 0))
	}, p.rest = p.tail = p.drop = function(e, t, n) {
		return o.call(e, t == null || n ? 1 : t)
	}, p.compact = function(e) {
		return p.filter(e, p.identity)
	};
	var g = function(e, t, n, r) {
		if (t && p.every(e, p.isArray)) return u.apply(r, e);
		for (var i = 0, o = e.length; i < o; i++) {
			var a = e[i];
			!p.isArray(a) && !p.isArguments(a) ? n || r.push(a) : t ? s.apply(r, a) : g(a, t, n, r)
		}
		return r
	};
	p.flatten = function(e, t) {
		return g(e, t, !1, [])
	}, p.without = function(e) {
		return p.difference(e, o.call(arguments, 1))
	}, p.uniq = p.unique = function(e, t, n, r) {
		if (e == null) return [];
		p.isBoolean(t) || (r = n, n = t, t = !1), n != null && (n = p.iteratee(n, r));
		var i = [],
			s = [];
		for (var o = 0, u = e.length; o < u; o++) {
			var a = e[o];
			if (t)(!o || s !== a) && i.push(a), s = a;
			else if (n) {
				var f = n(a, o, e);
				p.indexOf(s, f) < 0 && (s.push(f), i.push(a))
			} else p.indexOf(i, a) < 0 && i.push(a)
		}
		return i
	}, p.union = function() {
		return p.uniq(g(arguments, !0, !0, []))
	}, p.intersection = function(e) {
		if (e == null) return [];
		var t = [],
			n = arguments.length;
		for (var r = 0, i = e.length; r < i; r++) {
			var s = e[r];
			if (p.contains(t, s)) continue;
			for (var o = 1; o < n; o++)
				if (!p.contains(arguments[o], s)) break;
			o === n && t.push(s)
		}
		return t
	}, p.difference = function(e) {
		var t = g(o.call(arguments, 1), !0, !0, []);
		return p.filter(e, function(e) {
			return !p.contains(t, e)
		})
	}, p.zip = function(e) {
		if (e == null) return [];
		var t = p.max(arguments, "length").length,
			n = Array(t);
		for (var r = 0; r < t; r++) n[r] = p.pluck(arguments, r);
		return n
	}, p.object = function(e, t) {
		if (e == null) return {};
		var n = {};
		for (var r = 0, i = e.length; r < i; r++) t ? n[e[r]] = t[r] : n[e[r][0]] = e[r][1];
		return n
	}, p.indexOf = function(e, t, n) {
		if (e == null) return -1;
		var r = 0,
			i = e.length;
		if (n) {
			if (typeof n != "number") return r = p.sortedIndex(e, t), e[r] === t ? r : -1;
			r = n < 0 ? Math.max(0, i + n) : n
		}
		for (; r < i; r++)
			if (e[r] === t) return r;
		return -1
	}, p.lastIndexOf = function(e, t, n) {
		if (e == null) return -1;
		var r = e.length;
		typeof n == "number" && (r = n < 0 ? r + n + 1 : Math.min(r, n + 1));
		while (--r >= 0)
			if (e[r] === t) return r;
		return -1
	}, p.range = function(e, t, n) {
		arguments.length <= 1 && (t = e || 0, e = 0), n = n || 1;
		var r = Math.max(Math.ceil((t - e) / n), 0),
			i = Array(r);
		for (var s = 0; s < r; s++, e += n) i[s] = e;
		return i
	};

	var y = function() {};
	p.bind = function(e, t) {
		var n, r;
		if (h && e.bind === h) return h.apply(e, o.call(arguments, 1));
		if (!p.isFunction(e)) throw new TypeError("Bind must be called on a function");
		return n = o.call(arguments, 2), r = function() {
			if (this instanceof r) {
				y.prototype = e.prototype;
				var i = new y;
				y.prototype = null;
				var s = e.apply(i, n.concat(o.call(arguments)));
				return p.isObject(s) ? s : i
			}
			return e.apply(t, n.concat(o.call(arguments)))
		}, r
	}, p.partial = function(e) {
		var t = o.call(arguments, 1);
		return function() {
			var n = 0,
				r = t.slice();
			for (var i = 0, s = r.length; i < s; i++) r[i] === p && (r[i] = arguments[n++]);
			while (n < arguments.length) r.push(arguments[n++]);
			return e.apply(this, r)
		}
	}, p.bindAll = function(e) {
		var t, n = arguments.length,
			r;
		if (n <= 1) throw new Error("bindAll must be passed function names");
		for (t = 1; t < n; t++) r = arguments[t], e[r] = p.bind(e[r], e);
		return e
	}, p.memoize = function(e, t) {
		var n = function(r) {
			var i = n.cache,
				s = t ? t.apply(this, arguments) : r;
			return p.has(i, s) || (i[s] = e.apply(this, arguments)), i[s]
		};
		return n.cache = {}, n
	}, p.delay = function(e, t) {
		var n = o.call(arguments, 2);
		return setTimeout(function() {
			return e.apply(null, n)
		}, t)
	}, p.defer = function(e) {
		return p.delay.apply(p, [e, 1].concat(o.call(arguments, 1)))
	}, p.throttle = function(e, t, n) {
		var r, i, s, o = null,
			u = 0;
		n || (n = {});
		var a = function() {
			u = n.leading === !1 ? 0 : p.now(), o = null, s = e.apply(r, i), o || (r = i = null)
		};
		return function() {
			var f = p.now();
			!u && n.leading === !1 && (u = f);
			var l = t - (f - u);
			return r = this, i = arguments, l <= 0 || l > t ? (clearTimeout(o), o = null, u = f, s = e.apply(r, i), o || (r = i = null)) : !o && n.trailing !== !1 && (o = setTimeout(a, l)), s
		}
	}, p.debounce = function(e, t, n) {
		var r, i, s, o, u, a = function() {
			var f = p.now() - o;
			f < t && f > 0 ? r = setTimeout(a, t - f) : (r = null, n || (u = e.apply(s, i), r || (s = i = null)))
		};
		return function() {
			s = this, i = arguments, o = p.now();
			var f = n && !r;
			return r || (r = setTimeout(a, t)), f && (u = e.apply(s, i), s = i = null), u
		}
	}, p.wrap = function(e, t) {
		return p.partial(t, e)
	}, p.negate = function(e) {
		return function() {
			return !e.apply(this, arguments)
		}
	}, p.compose = function() {
		var e = arguments,
			t = e.length - 1;
		return function() {
			var n = t,
				r = e[t].apply(this, arguments);
			while (n--) r = e[n].call(this, r);
			return r
		}
	}, p.after = function(e, t) {
		return function() {
			if (--e < 1) return t.apply(this, arguments)
		}
	}, p.before = function(e, t) {
		var n;
		return function() {
			return --e > 0 ? n = t.apply(this, arguments) : t = null, n
		}
	}, p.once = p.partial(p.before, 2), p.keys = function(e) {
		if (!p.isObject(e)) return [];
		if (c) return c(e);
		var t = [];
		for (var n in e) p.has(e, n) && t.push(n);
		return t
	}, p.values = function(e) {
		var t = p.keys(e),
			n = t.length,
			r = Array(n);
		for (var i = 0; i < n; i++) r[i] = e[t[i]];
		return r
	}, p.pairs = function(e) {
		var t = p.keys(e),
			n = t.length,
			r = Array(n);
		for (var i = 0; i < n; i++) r[i] = [t[i], e[t[i]]];
		return r
	}, p.invert = function(e) {
		var t = {},
			n = p.keys(e);
		for (var r = 0, i = n.length; r < i; r++) t[e[n[r]]] = n[r];
		return t
	}, p.functions = p.methods = function(e) {
		var t = [];
		for (var n in e) p.isFunction(e[n]) && t.push(n);
		return t.sort()
	}, p.extend = function(e) {
		if (!p.isObject(e)) return e;
		var t, n;
		for (var r = 1, i = arguments.length; r < i; r++) {
			t = arguments[r];
			for (n in t) f.call(t, n) && (e[n] = t[n])
		}
		return e
	}, p.pick = function(e, t, n) {
		var r = {},
			i;
		if (e == null) return r;
		if (p.isFunction(t)) {
			t = d(t, n);
			for (i in e) {
				var s = e[i];
				t(s, i, e) && (r[i] = s)
			}
		} else {
			var a = u.apply([], o.call(arguments, 1));
			e = new Object(e);
			for (var f = 0, l = a.length; f < l; f++) i = a[f], i in e && (r[i] = e[i])
		}
		return r
	}, p.omit = function(e, t, n) {
		if (p.isFunction(t)) t = p.negate(t);
		else {
			var r = p.map(u.apply([], o.call(arguments, 1)), String);
			t = function(e, t) {
				return !p.contains(r, t)
			}
		}
		return p.pick(e, t, n)
	}, p.defaults = function(e) {
		if (!p.isObject(e)) return e;
		for (var t = 1, n = arguments.length; t < n; t++) {
			var r = arguments[t];
			for (var i in r) e[i] === void 0 && (e[i] = r[i])
		}
		return e
	}, p.clone = function(e) {
		return p.isObject(e) ? p.isArray(e) ? e.slice() : p.extend({}, e) : e
	}, p.tap = function(e, t) {
		return t(e), e
	};
	var b = function(e, t, n, r) {
		if (e === t) return e !== 0 || 1 / e === 1 / t;
		if (e == null || t == null) return e === t;
		e instanceof p && (e = e._wrapped), t instanceof p && (t = t._wrapped);
		var i = a.call(e);
		if (i !== a.call(t)) return !1;
		switch (i) {
			case "[object RegExp]":
			case "[object String]":
				return "" + e == "" + t;
			case "[object Number]":
				if (+e !== +e) return +t !== +t;
				return +e === 0 ? 1 / +e === 1 / t : +e === +t;
			case "[object Date]":
			case "[object Boolean]":
				return +e === +t
		}
		if (typeof e != "object" || typeof t != "object") return !1;
		var s = n.length;
		while (s--)
			if (n[s] === e) return r[s] === t;
		var o = e.constructor,
			u = t.constructor;
		if (o !== u && "constructor" in e && "constructor" in t && !(p.isFunction(o) && o instanceof o && p.isFunction(u) && u instanceof u)) return !1;
		n.push(e), r.push(t);
		var f, l;
		if (i === "[object Array]") {
			f = e.length, l = f === t.length;
			if (l)
				while (f--)
					if (!(l = b(e[f], t[f], n, r))) break
		} else {
			var c = p.keys(e),
				h;
			f = c.length, l = p.keys(t).length === f;
			if (l)
				while (f--) {
					h = c[f];
					if (!(l = p.has(t, h) && b(e[h], t[h], n, r))) break
				}
		}
		return n.pop(), r.pop(), l
	};
	p.isEqual = function(e, t) {
		return b(e, t, [], [])
	}, p.isEmpty = function(e) {
		if (e == null) return !0;
		if (p.isArray(e) || p.isString(e) || p.isArguments(e)) return e.length === 0;
		for (var t in e)
			if (p.has(e, t)) return !1;
		return !0
	}, p.isElement = function(e) {
		return !!e && e.nodeType === 1
	}, p.isArray = l || function(e) {
		return a.call(e) === "[object Array]"
	}, p.isObject = function(e) {
		var t = typeof e;
		return t === "function" || t === "object" && !!e
	}, p.each(["Arguments", "Function", "String", "Number", "Date", "RegExp"], function(e) {
		p["is" + e] = function(t) {
			return a.call(t) === "[object " + e + "]"
		}
	}), p.isArguments(arguments) || (p.isArguments = function(e) {
		return p.has(e, "callee")
	}), typeof /./ != "function" && (p.isFunction = function(e) {
		return typeof e == "function" || !1
	}), p.isFinite = function(e) {
		return isFinite(e) && !isNaN(parseFloat(e))
	}, p.isNaN = function(e) {
		return p.isNumber(e) && e !== +e
	}, p.isBoolean = function(e) {
		return e === !0 || e === !1 || a.call(e) === "[object Boolean]"
	}, p.isNull = function(e) {
		return e === null
	}, p.isUndefined = function(e) {
		return e === void 0
	}, p.has = function(e, t) {
		return e != null && f.call(e, t)
	}, p.noConflict = function() {
		return e._ = t, this
	}, p.identity = function(e) {
		return e
	}, p.constant = function(e) {
		return function() {
			return e
		}
	}, p.noop = function() {}, p.property = function(e) {
		return function(t) {
			return t[e]
		}
	}, p.matches = function(e) {
		var t = p.pairs(e),
			n = t.length;
		return function(e) {
			if (e == null) return !n;
			e = new Object(e);
			for (var r = 0; r < n; r++) {
				var i = t[r],
					s = i[0];
				if (i[1] !== e[s] || !(s in e)) return !1
			}
			return !0
		}
	}, p.times = function(e, t, n) {
		var r = Array(Math.max(0, e));
		t = d(t, n, 1);
		for (var i = 0; i < e; i++) r[i] = t(i);
		return r
	}, p.random = function(e, t) {
		return t == null && (t = e, e = 0), e + Math.floor(Math.random() * (t - e + 1))
	}, p.now = Date.now || function() {
		return (new Date).getTime()
	};
	var w = {
			"&": "&amp;",
			"<": "&lt;",
			">": "&gt;",
			'"': "&quot;",
			"'": "&#x27;",
			"`": "&#x60;"
		},
		E = p.invert(w),
		S = function(e) {
			var t = function(t) {
					return e[t]
				},
				n = "(?:" + p.keys(e).join("|") + ")",
				r = RegExp(n),
				i = RegExp(n, "g");
			return function(e) {
				return e = e == null ? "" : "" + e, r.test(e) ? e.replace(i, t) : e
			}
		};
	p.escape = S(w), p.unescape = S(E), p.result = function(e, t) {
		if (e == null) return void 0;
		var n = e[t];
		return p.isFunction(n) ? e[t]() : n
	};
	var x = 0;
	p.uniqueId = function(e) {
		var t = ++x + "";
		return e ? e + t : t
	}, p.templateSettings = {
		evaluate: /<%([\s\S]+?)%>/g,
		interpolate: /<%=([\s\S]+?)%>/g,
		escape: /<%-([\s\S]+?)%>/g
	};
	var T = /(.)^/,
		N = {
			"'": "'",
			"\\": "\\",
			"\r": "r",
			"\n": "n",
			"\u2028": "u2028",
			"\u2029": "u2029"
		},
		C = /\\|'|\r|\n|\u2028|\u2029/g,
		k = function(e) {
			return "\\" + N[e]
		};
	p.template = function(e, t, n) {
		!t && n && (t = n), t = p.defaults({}, t, p.templateSettings);
		var r = RegExp([(t.escape || T).source, (t.interpolate || T).source, (t.evaluate || T).source].join("|") + "|$", "g"),
			i = 0,
			s = "__p+='";
		e.replace(r, function(t, n, r, o, u) {
			return s += e.slice(i, u).replace(C, k), i = u + t.length, n ? s += "'+\n((__t=(" + n + "))==null?'':_.escape(__t))+\n'" : r ? s += "'+\n((__t=(" + r + "))==null?'':__t)+\n'" : o && (s += "';\n" + o + "\n__p+='"), t
		}), s += "';\n", t.variable || (s = "with(obj||{}){\n" + s + "}\n"), s = "var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};\n" + s + "return __p;\n";
		try {
			var o = new Function(t.variable || "obj", "_", s)
		} catch (u) {
			throw u.source = s, u
		}
		var a = function(e) {
				return o.call(this, e, p)
			},
			f = t.variable || "obj";
		return a.source = "function(" + f + "){\n" + s + "}", a
	}, p.chain = function(e) {
		var t = p(e);
		return t._chain = !0, t
	};
	var L = function(e) {
		return this._chain ? p(e).chain() : e
	};
	p.mixin = function(e) {
		p.each(p.functions(e), function(t) {
			var n = p[t] = e[t];
			p.prototype[t] = function() {
				var e = [this._wrapped];
				return s.apply(e, arguments), L.call(this, n.apply(p, e))
			}
		})
	}, p.mixin(p), p.each(["pop", "push", "reverse", "shift", "sort", "splice", "unshift"], function(e) {
		var t = n[e];
		p.prototype[e] = function() {
			var n = this._wrapped;
			return t.apply(n, arguments), (e === "shift" || e === "splice") && n.length === 0 && delete n[0], L.call(this, n)
		}
	}), p.each(["concat", "join", "slice"], function(e) {
		var t = n[e];
		p.prototype[e] = function() {
			return L.call(this, t.apply(this._wrapped, arguments))
		}
	}), p.prototype.value = function() {
		return this._wrapped
	}, typeof define == "function" && define.amd && define("underscore", [], function() {
		return p
	})
}).call(this), define("dialog", ["jquery"], function(e) {
		function u(t, n, r, i) {
			n = n || 3e3, e.type(n) === "string" && (r = n, n = 3e3);
			var o = s({
					id: "d-msg",
					skin: "ui-msg " + r,
					content: t
				}),
				u = setTimeout(function() {
					o.remove()
				}, n);
			return o.show(), o
		}
		var t = function(e) {
				function i() {
					this.destroyed = !1, this.__popup = e("<div />").attr({
						tabindex: "-1"
					}).css({
						display: "none",
						position: "absolute",
						outline: 0
					}).html(this.innerHTML).appendTo("body"), this.__backdrop = e("<div />"), this.node = this.__popup[0], this.backdrop = this.__backdrop[0], t++
				}
				var t = 0,
					n = !("minWidth" in e("html")[0].style),
					r = !n;
				return e.extend(i.prototype, {
					node: null,
					backdrop: null,
					fixed: !1,
					destroyed: !0,
					open: !1,
					returnValue: "",
					autofocus: !0,
					align: "bottom left",
					backdropBackground: "#fff",
					backdropOpacity: 0,
					innerHTML: "",
					className: "ui-popup",
					show: function(t) {
						if (this.destroyed) return this;
						var r = this,
							i = this.__popup;
						return this.__activeElement = this.__getActive(), this.open = !0, this.follow = t || this.follow, this.__ready || (i.addClass(this.className), this.modal && this.__lock(), i.html() || i.html(this.innerHTML), n || e(window).on("resize", this.__onresize = function() {
							r.reset()
						}), this.__ready = !0), i.addClass(this.className + "-show").attr("role", this.modal ? "alertdialog" : "dialog").css("position", this.fixed ? "fixed" : "absolute").show(), this.__backdrop.show(), this.reset().focus(), this.__dispatchEvent("show"), this
					},
					showModal: function() {
						return this.modal = !0, this.show.apply(this, arguments)
					},
					close: function(e) {
						return !this.destroyed && this.open && (e !== undefined && (this.returnValue = e), this.__popup.hide().removeClass(this.className + "-show"), this.__backdrop.hide(), this.open = !1, this.blur(), this.__dispatchEvent("close")), this
					},
					remove: function() {
						if (this.destroyed) return this;
						this.__dispatchEvent("beforeremove"), i.current === this && (i.current = null), this.__unlock(), this.__popup.remove(), this.__backdrop.remove(), n || e(window).off("resize", this.__onresize), this.__dispatchEvent("remove");
						for (var t in this) delete this[t];
						return this
					},
					reset: function() {
						var e = this.follow;
						return e ? this.__follow(e) : this.__center(), this.__dispatchEvent("reset"), this
					},
					focus: function() {
						var t = this.node,
							n = i.current;
						n && n !== this && n.blur(!1);
						if (!e.contains(t, this.__getActive())) {
							var r = this.__popup.find("[autofocus]")[0];
							!this._autofocus && r ? this._autofocus = !0 : r = t, this.__focus(r)
						}
						return i.current = this, this.__popup.addClass(this.className + "-focus"), this.__zIndex(), this.__dispatchEvent("focus"), this
					},
					blur: function() {
						var e = this.__activeElement,
							t = arguments[0];
						return t !== !1 && this.__focus(e), this._autofocus = !1, this.__popup.removeClass(this.className + "-focus"), this.__dispatchEvent("blur"), this
					},
					addEventListener: function(e, t) {
						return this.__getEventListener(e).push(t), this
					},
					removeEventListener: function(e, t) {
						var n = this.__getEventListener(e);
						for (var r = 0; r < n.length; r++) t === n[r] && n.splice(r--, 1);
						return this
					},
					__getEventListener: function(e) {
						var t = this.__listener;
						return t || (t = this.__listener = {}), t[e] || (t[e] = []), t[e]
					},
					__dispatchEvent: function(e) {
						var t = this.__getEventListener(e);
						this["on" + e] && this["on" + e]();
						for (var n = 0; n < t.length; n++) t[n].call(this)
					},
					__focus: function(e) {
						try {
							this.autofocus && !/^iframe$/i.test(e.nodeName) && e.focus()
						} catch (t) {}
					},
					__getActive: function() {
						try {
							var e = document.activeElement,
								t = e.contentDocument,
								n = t && t.activeElement || e;
							return n
						} catch (r) {}
					},
					__zIndex: function() {
						var e = i.zIndex++;
						this.__popup.css("zIndex", e), this.__backdrop.css("zIndex", e - 1), this.zIndex = e
					},
					__center: function() {
						var t = this.__popup,
							n = e(window),
							r = e(document),
							i = this.fixed,
							s = i ? 0 : r.scrollLeft(),
							o = i ? 0 : r.scrollTop(),
							u = n.width(),
							a = n.height(),
							f = t.width(),
							l = t.height(),
							c = (u - f) / 2 + s,
							h = (a - l) * 382 / 1e3 + o,
							p = t[0].style;
						p.left = Math.max(parseInt(c), s) + "px", p.top = Math.max(parseInt(h), o) + "px"
					},
					__follow: function(t) {
						var n = t.parentNode && e(t),
							r = this.__popup;
						this.__followSkin && r.removeClass(this.__followSkin);
						if (n) {
							var i = n.offset();
							if (i.left * i.top < 0) return this.__center()
						}
						var s = this,
							o = this.fixed,
							u = e(window),
							a = e(document),
							f = u.width(),
							l = u.height(),
							c = a.scrollLeft(),
							h = a.scrollTop(),
							p = r.width(),
							d = r.height(),
							v = n ? n.outerWidth() : 0,
							m = n ? n.outerHeight() : 0,
							g = this.__offset(t),
							y = g.left,
							b = g.top,
							w = o ? y - c : y,
							E = o ? b - h : b,
							S = o ? 0 : c,
							x = o ? 0 : h,
							T = S + f - p,
							N = x + l - d,
							C = {},
							k = this.align.split(" "),
							L = this.className + "-",
							A = {
								top: "bottom",
								bottom: "top",
								left: "right",
								right: "left"
							},
							O = {
								top: "top",
								bottom: "top",
								left: "left",
								right: "left"
							},
							M = [{
								top: E - d,
								bottom: E + m,
								left: w - p,
								right: w + v
							}, {
								top: E,
								bottom: E - d + m,
								left: w,
								right: w - p + v
							}],
							_ = {
								left: w + v / 2 - p / 2,
								top: E + m / 2 - d / 2
							},
							D = {
								left: [S, T],
								top: [x, N]
							};
						e.each(k, function(e, t) {
							M[e][t] > D[O[t]][1] && (t = k[e] = A[t]), M[e][t] < D[O[t]][0] && (k[e] = A[t])
						}), k[1] || (O[k[1]] = O[k[0]] === "left" ? "top" : "left", M[1][k[1]] = _[O[k[1]]]), L += k.join("-") + " " + this.className + "-follow", s.__followSkin = L, n && r.addClass(L), C[O[k[0]]] = parseInt(M[0][k[0]]), C[O[k[1]]] = parseInt(M[1][k[1]]), r.css(C)
					},
					__offset: function(t) {
						var n = t.parentNode,
							r = n ? e(t).offset() : {
								left: t.pageX,
								top: t.pageY
							};
						t = n ? t : t.target;
						var i = t.ownerDocument,
							s = i.defaultView || i.parentWindow;
						if (s == window) return r;
						var o = s.frameElement,
							u = e(i),
							a = u.scrollLeft(),
							f = u.scrollTop(),
							l = e(o).offset(),
							c = l.left,
							h = l.top;
						return {
							left: r.left + c - a,
							top: r.top + h - f
						}
					},
					__lock: function() {
						var t = this,
							n = this.__popup,
							s = this.__backdrop,
							o = {
								position: "fixed",
								left: 0,
								top: 0,
								width: "100%",
								height: "100%",
								overflow: "hidden",
								userSelect: "none",
								opacity: 0,
								background: this.backdropBackground
							};
						n.addClass(this.className + "-modal"), i.zIndex = i.zIndex + 2, this.__zIndex(), r || e.extend(o, {
							position: "absolute",
							width: e(window).width() + "px",
							height: e(document).height() + "px"
						}), s.css(o).animate({
							opacity: this.backdropOpacity
						}, 150).insertAfter(n).attr({
							tabindex: "0"
						}).on("focus", function() {
							t.focus()
						})
					},
					__unlock: function() {
						this.modal && (this.__popup.removeClass(this.className + "-modal"), this.__backdrop.remove(), delete this.modal)
					}
				}), i.zIndex = 1024, i.current = null, i
			}(e),
			n = function(e) {
				return {
					content: '<span class="ui-dialog-loading">Loading..</span>',
					title: "",
					statusbar: "",
					button: null,
					ok: null,
					cancel: null,
					okValue: "ok",
					cancelValue: "cancel",
					cancelDisplay: !0,
					width: "",
					height: "",
					padding: "",
					skin: "",
					quickClose: !1,
					cssUri: "./index.css",
					innerHTML: '<div i="dialog" class="ui-dialog"><div class="ui-dialog-arrow-a"></div><div class="ui-dialog-arrow-b"></div><table class="ui-dialog-grid"><tr><td i="header" class="ui-dialog-header"><button i="close" class="ui-dialog-close">&#215;</button><div i="title" class="ui-dialog-title"></div></td></tr><tr><td i="body" class="ui-dialog-body"><div i="content" class="ui-dialog-content"></div></td></tr><tr><td i="footer" class="ui-dialog-footer"><div i="statusbar" class="ui-dialog-statusbar"></div><div i="button" class="ui-dialog-button"></div></td></tr></table></div>'
				}
			}(e),
			r = function(e) {
				var r = 0,
					i = new Date - 0,
					s = !("minWidth" in e("html")[0].style),
					o = "createTouch" in document && !("onmousemove" in document) || /(iPhone|iPad|iPod)/i.test(navigator.userAgent),
					u = !s && !o,
					a = function(t, n, s) {
						var f = t = t || {};
						if (typeof t == "string" || t.nodeType === 1) t = {
							content: t,
							fixed: !o
						};
						t = e.extend(!0, {}, a.defaults, t), t._ = f;
						var l = t.id = t.id || i + r,
							c = a.get(l);
						return c ? c.focus() : (u || (t.fixed = !1), t.quickClose && (t.modal = !0, f.backdropOpacity || (t.backdropOpacity = 0)), e.isArray(t.button) || (t.button = []), s !== undefined && (t.cancel = s), t.cancel && t.button.push({
							id: "cancel",
							value: t.cancelValue,
							callback: t.cancel,
							display: t.cancelDisplay
						}), n !== undefined && (t.ok = n), t.ok && t.button.push({
							id: "ok",
							value: t.okValue,
							callback: t.ok,
							autofocus: !0
						}), a.list[l] = new a.create(t))
					},
					f = function() {};
				f.prototype = t.prototype;
				var l = a.prototype = new f;
				return a.create = function(n) {
					var i = this;
					e.extend(this, new t);
					var s = e(this.node).html(n.innerHTML);
					return this.options = n, this._popup = s, e.each(n, function(e, t) {
						typeof i[e] == "function" ? i[e](t) : i[e] = t
					}), n.zIndex && (t.zIndex = n.zIndex), s.attr({
						"aria-labelledby": this._$("title").attr("id", "title:" + this.id).attr("id"),
						"aria-describedby": this._$("content").attr("id", "content:" + this.id).attr("id")
					}), this._$("close").css("display", this.cancel === !1 ? "none" : "").attr("title", this.cancelValue).on("click", function(e) {
						i._trigger("cancel"), e.preventDefault()
					}), this._$("dialog").addClass(this.skin), this._$("body").css("padding", this.padding), s.on("click", "[data-id]", function(t) {
						var n = e(this);
						n.attr("disabled") || i._trigger(n.data("id")), t.preventDefault()
					}), n.quickClose && e(this.backdrop).on("onmousedown" in document ? "mousedown" : "click", function() {
						return i._trigger("cancel"), !1
					}), this._esc = function(e) {
						var n = e.target,
							r = n.nodeName,
							s = /^input|textarea$/i,
							o = t.current === i,
							u = e.keyCode;
						if (!o || s.test(r) && n.type !== "button") return;
						u === 27 && i._trigger("cancel")
					}, e(document).on("keydown", this._esc), this.addEventListener("remove", function() {
						e(document).off("keydown", this._esc), delete a.list[this.id]
					}), r++, a.oncreate(this), this
				}, a.create.prototype = l, e.extend(l, {
					content: function(e) {
						return this._$("content").empty("")[typeof e == "object" ? "append" : "html"](e), this.reset()
					},
					title: function(e) {
						return this._$("title").text(e), this._$("header")[e ? "show" : "hide"](), this
					},
					width: function(e) {
						return this._$("content").css("width", e), this.reset()
					},
					height: function(e) {
						return this._$("content").css("height", e), this.reset()
					},
					button: function(t) {
						t = t || [];
						var n = this,
							r = "",
							i = 0;
						return this.callbacks = {}, typeof t == "string" ? r = t : e.each(t, function(e, t) {
							t.id = t.id || t.value, n.callbacks[t.id] = t.callback;
							var s = "";
							t.display === !1 ? s = ' style="display:none"' : i++, r += '<button type="button" data-id="' + t.id + '"' + s + (t.disabled ? " disabled" : "") + (t.autofocus ? ' autofocus class="ui-dialog-autofocus"' : "") + ">" + t.value + "</button>"
						}), this._$("footer")[i ? "show" : "hide"](), this._$("button").html(r), this
					},
					statusbar: function(e) {
						return this._$("statusbar").html(e)[e ? "show" : "hide"](), this
					},
					_$: function(e) {
						return this._popup.find("[i=" + e + "]")
					},
					_trigger: function(e) {
						var t = this.callbacks[e];
						return typeof t != "function" || t.call(this) !== !1 ? this.close().remove() : this
					}
				}), a.oncreate = e.noop, a.getCurrent = function() {
					return t.current
				}, a.get = function(e) {
					return e === undefined ? a.list : a.list[e]
				}, a.list = {}, a.defaults = n, a
			}(e),
			i = function(e) {
				var t = e(window),
					n = e(document),
					r = "createTouch" in document,
					i = document.documentElement,
					s = !("minWidth" in i.style),
					o = !s && "onlosecapture" in i,
					u = "setCapture" in i,
					a = {
						start: r ? "touchstart" : "mousedown",
						over: r ? "touchmove" : "mousemove",
						end: r ? "touchend" : "mouseup"
					},
					f = r ? function(e) {
						return e.touches || (e = e.originalEvent.touches.item(0)), e
					} : function(e) {
						return e
					},
					l = function() {
						this.start = e.proxy(this.start, this), this.over = e.proxy(this.over, this), this.end = e.proxy(this.end, this), this.onstart = this.onover = this.onend = e.noop
					};
				return l.types = a, l.prototype = {
					start: function(e) {
						return e = this.startFix(e), n.on(a.over, this.over).on(a.end, this.end), this.onstart(e), !1
					},
					over: function(e) {
						return e = this.overFix(e), this.onover(e), !1
					},
					end: function(e) {
						return e = this.endFix(e), n.off(a.over, this.over).off(a.end, this.end), this.onend(e), !1
					},
					startFix: function(r) {
						return r = f(r), this.target = e(r.target), this.selectstart = function() {
							return !1
						}, n.on("selectstart", this.selectstart).on("dblclick", this.end), o ? this.target.on("losecapture", this.end) : t.on("blur", this.end), u && this.target[0].setCapture(), r
					},
					overFix: function(e) {
						return e = f(e), e
					},
					endFix: function(e) {
						return e = f(e), n.off("selectstart", this.selectstart).off("dblclick", this.end), o ? this.target.off("losecapture", this.end) : t.off("blur", this.end), u && this.target[0].releaseCapture(), e
					}
				}, l.create = function(r, i) {
					var s = e(r),
						o = new l,
						u = l.types.start,
						a = function() {},
						f = r.className.replace(/^\s|\s.*/g, "") + "-drag-start",
						c, h, p, d, v = {
							onstart: a,
							onover: a,
							onend: a,
							off: function() {
								s.off(u, o.start)
							}
						};
					return o.onstart = function(e) {
						var i = s.css("position") === "fixed",
							o = n.scrollLeft(),
							u = n.scrollTop(),
							a = s.width(),
							l = s.height();
						c = 0, h = 0, p = i ? t.width() - a + c : n.width() - a, d = i ? t.height() - l + h : n.height() - l;
						var m = s.offset(),
							g = this.startLeft = i ? m.left - o : m.left,
							y = this.startTop = i ? m.top - u : m.top;
						this.clientX = e.clientX, this.clientY = e.clientY, s.addClass(f), v.onstart.call(r, e, g, y)
					}, o.onover = function(e) {
						var t = e.clientX - this.clientX + this.startLeft,
							n = e.clientY - this.clientY + this.startTop,
							i = s[0].style;
						t = Math.max(c, Math.min(p, t)), n = Math.max(h, Math.min(d, n)), i.left = t + "px", i.top = n + "px", v.onover.call(r, e, t, n)
					}, o.onend = function(e) {
						var t = s.position(),
							n = t.left,
							i = t.top;
						s.removeClass(f), v.onend.call(r, e, n, i)
					}, o.off = function() {
						s.off(u, o.start)
					}, i ? o.start(i) : s.on(u, o.start), v
				}, l
			}(e);
		r.oncreate = function(t) {
			var n = t.options,
				r = n._,
				s = n.url,
				o = n.oniframeload,
				u;
			s && (this.padding = n.padding = 0, u = e("<iframe />"), u.attr({
				src: s,
				name: t.id,
				width: "100%",
				height: "100%",
				allowtransparency: "yes",
				frameborder: "no",
				scrolling: "no"
			}).on("load", function() {
				var e;
				try {
					e = u[0].contentWindow.frameElement
				} catch (r) {}
				e && (n.width || t.width(u.contents().width()), n.height || t.height(u.contents().height())), o && o.call(t)
			}), t.addEventListener("beforeremove", function() {
				u.attr("src", "about:blank").remove()
			}, !1), t.content(u[0]), t.iframeNode = u[0]);
			if (!(r instanceof Object)) {
				var a = function() {
					t.close().remove()
				};
				for (var f = 0; f < frames.length; f++) try {
					if (r instanceof frames[f].Object) {
						e(frames[f]).one("unload", a);
						break
					}
				} catch (l) {}
			}
			e(t.node).on(i.types.start, "[i=title]", function(e) {
				t.follow || (t.focus(), i.create(t.node, e))
			})
		}, r.get = function(e) {
			if (e && e.frameElement) {
				var t = e.frameElement,
					n = r.list,
					i;
				for (var s in n) {
					i = n[s];
					if (i.node.getElementsByTagName("iframe")[0] === t) return i
				}
			} else if (e) return r.list[e]
		}, window.ry_dialog = r;
		var s = r,
			o = "d-loading-bar";
		return {
			dialog: s,
			msg: function(e, t) {
				u(e, t, "msg-info")
			},
			success: function(e, t) {
				u(e, t, "msg-success")
			},
			error: function(e, t) {
				u(e, t, "msg-error")
			},
			loading: {
				show: function(e) {
					var t = s({
						skin: "ui-loading",
						id: o,
						content: e
					});
					return t.showModal(), t
				},
				hide: function() {
					s.get(o).remove()
				}
			}
		}
	}), define("/public/tjs/class.js", [], function() {
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
	}), define("/public/tjs/utils.js", ["jquery", "dialog"], function(e, t) {
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
	}),
	function(e, t) {
		if (typeof exports == "object" && exports) t(exports);
		else {
			var n = {};
			t(n), typeof define == "function" && define.amd ? define("mustache", n) : e.Mustache = n
		}
	}(this, function(e) {
		function n(e, n) {
			return t.call(e, n)
		}

		function i(e) {
			return !n(r, e)
		}

		function u(e) {
			return typeof e == "function"
		}

		function a(e) {
			return e.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&")
		}

		function l(e) {
			return String(e).replace(/[&<>"'\/]/g, function(e) {
				return f[e]
			})
		}

		function c(e) {
			if (!o(e) || e.length !== 2) throw new Error("Invalid tags: " + e);
			return [new RegExp(a(e[0]) + "\\s*"), new RegExp("\\s*" + a(e[1]))]
		}

		function g(t, n) {
			function E() {
				if (l && !g)
					while (f.length) delete u[f.pop()];
				else f = [];
				l = !1, g = !1
			}
			n = n || e.tags, t = t || "", typeof n == "string" && (n = n.split(p));
			var r = c(n),
				s = new w(t),
				o = [],
				u = [],
				f = [],
				l = !1,
				g = !1,
				S, x, T, N, C, k;
			while (!s.eos()) {
				S = s.pos, T = s.scanUntil(r[0]);
				if (T)
					for (var L = 0, A = T.length; L < A; ++L) N = T.charAt(L), i(N) ? f.push(u.length) : g = !0, u.push(["text", N, S, S + 1]), S += 1, N === "\n" && E();
				if (!s.scan(r[0])) break;
				l = !0, x = s.scan(m) || "name", s.scan(h), x === "=" ? (T = s.scanUntil(d), s.scan(d), s.scanUntil(r[1])) : x === "{" ? (T = s.scanUntil(new RegExp("\\s*" + a("}" + n[1]))), s.scan(v), s.scanUntil(r[1]), x = "&") : T = s.scanUntil(r[1]);
				if (!s.scan(r[1])) throw new Error("Unclosed tag at " + s.pos);
				C = [x, T, S, s.pos], u.push(C);
				if (x === "#" || x === "^") o.push(C);
				else if (x === "/") {
					k = o.pop();
					if (!k) throw new Error('Unopened section "' + T + '" at ' + S);
					if (k[1] !== T) throw new Error('Unclosed section "' + k[1] + '" at ' + S)
				} else x === "name" || x === "{" || x === "&" ? g = !0 : x === "=" && (r = c(n = T.split(p)))
			}
			k = o.pop();
			if (k) throw new Error('Unclosed section "' + k[1] + '" at ' + s.pos);
			return b(y(u))
		}

		function y(e) {
			var t = [],
				n, r;
			for (var i = 0, s = e.length; i < s; ++i) n = e[i], n && (n[0] === "text" && r && r[0] === "text" ? (r[1] += n[1], r[3] = n[3]) : (t.push(n), r = n));
			return t
		}

		function b(e) {
			var t = [],
				n = t,
				r = [],
				i, s;
			for (var o = 0, u = e.length; o < u; ++o) {
				i = e[o];
				switch (i[0]) {
					case "#":
					case "^":
						n.push(i), r.push(i), n = i[4] = [];
						break;
					case "/":
						s = r.pop(), s[5] = i[2], n = r.length > 0 ? r[r.length - 1][4] : t;
						break;
					default:
						n.push(i)
				}
			}
			return t
		}

		function w(e) {
			this.string = e, this.tail = e, this.pos = 0
		}

		function E(e, t) {
			this.view = e == null ? {} : e, this.cache = {
				".": this.view
			}, this.parent = t
		}

		function S() {
			this.cache = {}
		}
		var t = RegExp.prototype.test,
			r = /\S/,
			s = Object.prototype.toString,
			o = Array.isArray || function(e) {
				return s.call(e) === "[object Array]"
			},
			f = {
				"&": "&amp;",
				"<": "&lt;",
				">": "&gt;",
				'"': "&quot;",
				"'": "&#39;",
				"/": "&#x2F;"
			},
			h = /\s*/,
			p = /\s+/,
			d = /\s*=/,
			v = /\s*\}/,
			m = /#|\^|\/|>|\{|&|=|!/;
		w.prototype.eos = function() {
			return this.tail === ""
		}, w.prototype.scan = function(e) {
			var t = this.tail.match(e);
			if (t && t.index === 0) {
				var n = t[0];
				return this.tail = this.tail.substring(n.length), this.pos += n.length, n
			}
			return ""
		}, w.prototype.scanUntil = function(e) {
			var t = this.tail.search(e),
				n;
			switch (t) {
				case -1:
					n = this.tail, this.tail = "";
					break;
				case 0:
					n = "";
					break;
				default:
					n = this.tail.substring(0, t), this.tail = this.tail.substring(t)
			}
			return this.pos += n.length, n
		}, E.prototype.push = function(e) {
			return new E(e, this)
		}, E.prototype.lookup = function(e) {
			var t;
			if (e in this.cache) t = this.cache[e];
			else {
				var n = this;
				while (n) {
					if (e.indexOf(".") > 0) {
						t = n.view;
						var r = e.split("."),
							i = 0;
						while (t != null && i < r.length) t = t[r[i++]]
					} else t = n.view[e];
					if (t != null) break;
					n = n.parent
				}
				this.cache[e] = t
			}
			return u(t) && (t = t.call(this.view)), t
		}, S.prototype.clearCache = function() {
			this.cache = {}
		}, S.prototype.parse = function(e, t) {
			var n = this.cache,
				r = n[e];
			return r == null && (r = n[e] = g(e, t)), r
		}, S.prototype.render = function(e, t, n) {
			var r = this.parse(e),
				i = t instanceof E ? t : new E(t);
			return this.renderTokens(r, i, n, e)
		}, S.prototype.renderTokens = function(t, n, r, i) {
			function f(e) {
				return a.render(e, n, r)
			}
			var s = "",
				a = this,
				l, c;
			for (var h = 0, p = t.length; h < p; ++h) {
				l = t[h];
				switch (l[0]) {
					case "#":
						c = n.lookup(l[1]);
						if (!c) continue;
						if (o(c))
							for (var d = 0, v = c.length; d < v; ++d) s += this.renderTokens(l[4], n.push(c[d]), r, i);
						else if (typeof c == "object" || typeof c == "string") s += this.renderTokens(l[4], n.push(c), r, i);
						else if (u(c)) {
							if (typeof i != "string") throw new Error("Cannot use higher-order sections without the original template");
							c = c.call(n.view, i.slice(l[3], l[5]), f), c != null && (s += c)
						} else s += this.renderTokens(l[4], n, r, i);
						break;
					case "^":
						c = n.lookup(l[1]);
						if (!c || o(c) && c.length === 0) s += this.renderTokens(l[4], n, r, i);
						break;
					case ">":
						if (!r) continue;
						c = u(r) ? r(l[1]) : r[l[1]], c != null && (s += this.renderTokens(this.parse(c), n, r, c));
						break;
					case "&":
						c = n.lookup(l[1]), c != null && (s += c);
						break;
					case "name":
						c = n.lookup(l[1]), c != null && (s += e.escape(c));
						break;
					case "text":
						s += l[1]
				}
			}
			return s
		}, e.name = "mustache.js", e.version = "0.8.1", e.tags = ["{{", "}}"];
		var x = new S;
		e.clearCache = function() {
			return x.clearCache()
		}, e.parse = function(e, t) {
			return x.parse(e, t)
		}, e.render = function(e, t, n) {
			return x.render(e, t, n)
		}, e.to_html = function(t, n, r, i) {
			var s = e.render(t, n, r);
			if (!u(i)) return s;
			i(s)
		}, e.escape = l, e.Scanner = w, e.Context = E, e.Writer = S
	}), define("/public/tjs/countdown.js", ["jquery"], function(e) {
		function t(e, t) {
			var n = this;
			this.timerId = null, this._timer = e, this.timer = e, this.update = function(e) {}, this.done = function() {}, this.start = function() {
				return n.timerId != null && clearInterval(n.timerId), n.timerId = setInterval(function() {
					--n.timer, n.timer >= 0 ? n.update.call(n, n.timer) : (n.done.call(n), n.stop())
				}, 1e3), n
			}, this.stop = function() {
				return clearInterval(n.timerId), n.timerId = null, n
			}, this.restart = function() {
				n.start(), n.timer = n._timer
			}
		}
		return t
	}), define("/public/tjs/sound.js", ["/public/tjs/utils.js"], function(e) {
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
				r(e.format(t.assets, "open.mp3"))
			}
		}
	}), define("/public/tjs/lang.js", [], function() {
		var e = ry_lottery_config,
			t = e.locale,
			n = {
				dialog: {
					ok: "确定",
					cancel: "取消",
					close: "关闭"
				},
				msg: {
					empty: "请选择一个玩法",
					limit: "请选择{0}个号码",
					limit1: "请选择{0}个选项",
					hhh:"请选择2-8组玩法，若只要单一下注请前往正特投注！",
					maxGroup: "最多只能选择{0}组",
					minGroup: "最少选择{0}组",
					emptyAmount: "请输入下注金额",
					minNumbers: "最少选择{0}个号码",
					issue: "期",
					closedGate: "已封盘",
					errorNumber: "号码信息有误",
					sameNumbers: "不能包含相同的号码",
					notice: "公告"
				},
				date: {
					days: "天",
					hours: "时",
					minutes: "分",
					seconds: "秒"
				},
				bet: {
					success: "下注成功",
					chaochu: "金额超出限制",
					unknownError: "未知错误",
					linesUpdated: "赔率发生变化",
					loading: "正在保存中...",
					orders: "下注清单"
				}
			},
			r = {
				dialog: {
					ok: "確定",
					cancel: "取消",
					close: "關閉"
				},
				msg: {
					empty: "請選擇壹個玩法",
					limit: "只能選擇{0}個號碼",
					maxGroup: "最多只能選擇{0}組",
					minGroup: "最少選擇{0}組",
					emptyAmount: "請輸入下註金額",
					minNumbers: "最少選擇{0}個號碼",
					issue: "期",
					closedGate: "已封盤",
					notice: "公告",
					errorNumber: "號碼信息有誤",
					sameNumbers: "不能包含相同的號碼"
				},
				date: {
					days: "天",
					hours: "小時",
					minutes: "分鐘",
					seconds: "秒"
				},
				bet: {
					success: "下註成功",
					chaochu: "金额超出限制",
					unknownError: "未知錯誤",
					linesUpdated: "賠率發生變化",
					loading: "正在保存中...",
					orders: "下註清單"
				}
			},
			i = {
				dialog: {
					ok: "Ok",
					cancel: "Cancel",
					close: "Close"
				},
				msg: {
					empty: "Please choose for betting",
					limit: "Can only choose {0} numbers",
					maxGroup: "Can only choose {0} groups",
					minGroup: "Select at least {0} groups",
					emptyAmount: "Please enter betting amount",
					notice: "Notice",
					minNumbers: "Select at least {0} numbers",
					issue: "Issue",
					closedGate: "Closed",
					errorNumber: "Number info error",
					sameNumbers: "Can not include same numbers"
				},
				date: {
					days: "Days",
					hours: "Hours",
					minutes: "Minutes",
					seconds: "Seconds"
				},
				bet: {
					success: "Success !",
					unknownError: "Unknown error !",
					linesUpdated: "Lines updated ",
					loading: "Loading...",
					orders: "Order list"
				}
			},
			s = {
				zh_cn: n,
				zh_tw: r,
				en: i
			};
		return s[t]
	}), define("/public/tjs/tabs.js", ["jquery"], function(e) {
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
	}), define("/public/tjs/marquee.js", ["jquery"], function(e) {

		return function(e) {
			e.fn.marquee = function(t) {
				function s(e, t, n) {
					var r = n.behavior,
						i = n.width,
						s = n.dir,
						o = 0;
					return r == "alternate" ? o = e == 1 ? t[n.widthAxis] - i * 2 : i : r == "slide" ? e == -1 ? o = s == -1 ? t[n.widthAxis] : i : o = s == -1 ? t[n.widthAxis] - i * 2 : 0 : o = e == -1 ? t[n.widthAxis] : 0, o
				}

				function o() {
					var t = r.length,
						i = null,
						u = null,
						a = {},
						f = [],
						l = !1;
					while (t--) i = r[t], u = e(i), a = u.data("marqueeState"), u.data("paused") !== !0 ? (i[a.axis] += a.scrollamount * a.dir, l = a.dir == -1 ? i[a.axis] <= s(a.dir * -1, i, a) : i[a.axis] >= s(a.dir * -1, i, a), a.behavior == "scroll" && a.last == i[a.axis] || a.behavior == "alternate" && l && a.last != -1 || a.behavior == "slide" && l && a.last != -1 ? (a.behavior == "alternate" && (a.dir *= -1), a.last = -1, u.trigger("stop"), a.loops--, a.loops === 0 ? (a.behavior != "slide" ? i[a.axis] = s(a.dir, i, a) : i[a.axis] = s(a.dir * -1, i, a), u.trigger("end")) : (f.push(i), u.trigger("start"), i[a.axis] = s(a.dir, i, a))) : f.push(i), a.last = i[a.axis], u.data("marqueeState", a)) : f.push(i);
					r = f, r.length && setTimeout(o, n.speed)
				}
				var n = {
					kclass: "",
					speed: 30
				};
				e.extend(n, t);
				var r = [],
					i = this.length;
				return this.each(function(t) {
					var u = e(this),
						a = u.attr("width") || u.width(),
						f = u.attr("height") || u.height(),
						l = u.after("<div " + (n.kclass ? 'class="' + n.kclass + '" ' : "") + 'style=" width: ' + a + "px; height: " + f + 'px; overflow: hidden;"><div style="float: left;">' + u.html() + "</div></div>").next(),
						c = l.get(0),
						h = 0,
						p = (u.attr("direction") || "left").toLowerCase(),
						d = {
							dir: /down|right/.test(p) ? -1 : 1,
							axis: /left|right/.test(p) ? "scrollLeft" : "scrollTop",
							widthAxis: /left|right/.test(p) ? "scrollWidth" : "scrollHeight",
							last: -1,
							loops: u.attr("loop") || -1,
							scrollamount: u.attr("scrollamount") || this.scrollAmount || 2,
							behavior: (u.attr("behavior") || "scroll").toLowerCase(),
							width: /left|right/.test(p) ? a : f
						};
					u.attr("loop") == -1 && d.behavior == "slide" && (d.loops = 1), u.remove(), /left|right/.test(p) ? l.find("> div").css({
						padding: "0 " + a + "px",
						"white-space": "nowrap"
					}) : l.find("> div").css({
						padding: f + "px 0"
					}), l.bind("stop", function() {
						l.data("paused", !0)
					}).bind("pause", function() {
						l.data("paused", !0)
					}).bind("start", function() {
						l.data("paused", !1)
					}).bind("unpause", function() {
						l.data("paused", !1)
					}).data("marqueeState", d), r.push(c), c[d.axis] = s(d.dir, c, d), l.trigger("start"), t + 1 == i && o()
				}), e(r)
			}
		}(e), e
	}),
	function(e) {
		typeof define == "function" && define.amd ? define("cookie", ["jquery"], e) : typeof exports == "object" ? e(require("jquery")) : e(jQuery)
	}(function(e) {
		function n(e) {
			return u.raw ? e : encodeURIComponent(e)
		}

		function r(e) {
			return u.raw ? e : decodeURIComponent(e)
		}

		function i(e) {
			return n(u.json ? JSON.stringify(e) : String(e))
		}

		function s(e) {
			e.indexOf('"') === 0 && (e = e.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, "\\"));
			try {
				return e = decodeURIComponent(e.replace(t, " ")), u.json ? JSON.parse(e) : e
			} catch (n) {}
		}

		function o(t, n) {
			var r = u.raw ? t : s(t);
			return e.isFunction(n) ? n(r) : r
		}
		var t = /\+/g,
			u = e.cookie = function(t, s, a) {
				if (s !== undefined && !e.isFunction(s)) {
					a = e.extend({}, u.defaults, a);
					if (typeof a.expires == "number") {
						var f = a.expires,
							l = a.expires = new Date;
						l.setTime(+l + f * 864e5)
					}
					return document.cookie = [n(t), "=", i(s), a.expires ? "; expires=" + a.expires.toUTCString() : "", a.path ? "; path=" + a.path : "", a.domain ? "; domain=" + a.domain : "", a.secure ? "; secure" : ""].join("")
				}
				var c = t ? undefined : {},
					h = document.cookie ? document.cookie.split("; ") : [];
				for (var p = 0, d = h.length; p < d; p++) {
					var v = h[p].split("="),
						m = r(v.shift()),
						g = v.join("=");
					if (t && t === m) {
						c = o(g, s);
						break
					}!t && (g = o(g)) !== undefined && (c[m] = g)
				}
				return c
			};
		u.defaults = {}, e.removeCookie = function(t, n) {
			return e.cookie(t) === undefined ? !1 : (e.cookie(t, "", e.extend({}, n, {
				expires: -1
			})), !e.cookie(t))
		}
	}), define("/public/tjs/lottery.js", ["jquery", "underscore", "dialog", "/public/tjs/class.js", "/public/tjs/utils.js", "mustache", "/public/tjs/countdown.js", "/public/tjs/sound.js", "/public/tjs/lang.js", "/public/tjs/tabs.js", "/public/tjs/marquee.js", "cookie"], function(e, t, n, r, i, s, o, u, a) {
		var f, l = null,
			c = 20;
		return e.cookie.json = !0, t.templateSettings = {
			interpolate: /\{\{(.+?)\}\}/g
		}, f = r.create({
			$: e,
			utils: i,
			_: t,
			ui: n,
			tpl: s,
			CountDown: o,
			cfg: ry_lottery_config,
			lang: a,
			initialize: function(t) {
				var r = this;
				n.dialog.defaults = e.extend(!0, n.dialog.defaults, {
					okValue: a.dialog.ok,
					cancelValue: a.dialog.cancel
				}), this.options = e.extend(!0, {
					hln: !1,
					filter: !0,
					lotteryId: 'liuhecai',
					lotteryPan: 0,
					BetType: 1,
					IsTeMa: !1,
					IsForNumber: !1,
					category: "",
					cookieKey: "hfc_QuickMode"
				}, t), this.lotteryId = this.options.lotteryId, this.isOpen = !0, this.isQuickMode = !0, this.setQuickMode(), this.refreshDuration = 10, this.amount = 0, this.data = [], this.records = [], this.betInfo = null, this.$doc = e(document), this.$play = e("#play"), this.PrePeriodNumber = null;
				var i = this.options.filter;
				if (i) {
					var s = e.type(i) === "string" ? i : ".input";
					this.filter(s)
				}
				e(function() {
					r.$doc.off("click", ".bet-button").on("click", ".bet-button", function(t) {
						t.preventDefault();
						var n = e(this).data("action");
						r[n] && r[n]()
					}), r.marquee(), r.openNavDialog(),r.soundOnOff()
				})
			},
	        soundOnOff: function() {
	            var t = this,
	            n = e.cookie("soundSwitch");
	            n != null && (t.isSoundOn = parseInt(n) ? !0 : !1, n || e(".sound-on").addClass("sound-stop")),
	            e(".sound-on").click(function() {
	                e(this).toggleClass("sound-stop"),
	                t.isSoundOn = !t.isSoundOn,
	                e.cookie("soundSwitch", t.isSoundOn ? 1 : 0, {
	                    expires: 365,
	                    path: "/"
	                })
	            })
	        },
			getNotice: function() {

				return i.ajax({
					url: "/index.php/lottery/lottery/GetNoticeList",
					success: function(t) {
						var r = t.Obj;
						if (r.length > 0)
							for (var i = 0; i < r.length; i++) {
								var s = e.cookie("lotteryNoticeIdList");
								if (s != null && e.inArray(r[i].ID.toString(), s.split(",")) > -1) continue;
								n.dialog({
									id: i + 1,
									title: a.msg.notice,
									width: 400,
									height: 100,
									content: r[i].Content,
									ok: function() {
										var t = this.id - 1,
											n = e.cookie("lotteryNoticeIdList");
										n != null ? n += "," + r[t].ID : n = r[t].ID.toString(), e.cookie("lotteryNoticeIdList", n, {
											path: "/",
											expires: 365
										})
									}
								}).showModal()
							}
					}

				}), e.get("/index.php/lottery/lottery/syspars")
			},
			onAuthFail: function() {},
			refresh: function() {
				var e = this;
				this.getBetInfo().done(function(t) {
					if(t.Success){
						e.afterRefresh(t.Obj);
					}else{
						// e.ui.msg(t.Msg);
					}
					// t.Success ? e.afterRefresh(t.Obj) : e.ui.msg(t.Msg)
				}).error(function(t) {
					c > 0 && setTimeout(function() {
						e.refresh(), c--
					}, e.refreshDuration * 1e3)
				})
			},
			setQuickMode: function(t) {
				var n = this.options.cookieKey;
				t != undefined && e.cookie(n, t, {
					expires: 365,
					path: "/"
				}), this.isQuickMode = e.cookie(n) == undefined ? !0 : e.cookie(n)
			},
			afterRefresh: function(e) {},
			getPreBall: function(e) {},
			reset: function() {
				this.$doc.find(".input").val(""), this.data.length = 0, this.records.length = 0, this.amount = 0, this.removeConfirmDialog()
			},
			valid: function() {
				return this.data.length ? this.isQuickMode && !this.amount ? (n.msg(a.msg.emptyAmount), !1) : !0 : (n.msg(a.msg.empty), !1)
			},
			parlay: function() {
				var e = this;
				this.getData();
				if (!this.valid()) return;
				this.openConfirmDialog(function() {
					e.bet(e.lotteryId, e.data).done(function(t) {
						e.handlerResult(t)
					})
				})
			},
			beforeConfirm: function() {
				return !0
			},
			openConfirmDialog: function(e) {
				var t = this,
					r = "d-parlay-confirm";
				this.$confirmDialog = n.dialog({
					id: r,
					title: a.bet.orders,
					content: t.getRecordsHtml(),
					ok: function() {
						var n = this;
						if (!t.beforeConfirm()) return !1;
						t.beforeBet(n), e && e.call(n)
					},
					cancel: function() {}
				}).showModal()
			},
			removeConfirmDialog: function() {
				this.$confirmDialog && this.$confirmDialog.remove(), this.$confirmDialog = null
			},
			beforeBet: function() {},
			open: function() {
				this.isOpen = !0, this.$play.find("input,button").prop("disabled", !1), this.$play.removeClass("bet-closed")
			},
			close: function() {
				this.isOpen = !1, this.$play.find("input,button").prop("disabled", !0), this.$play.find(".input").val(""), this.removeConfirmDialog();
				var e = this.$play.addClass("bet-closed").find(".odds-text");
				e.find("[data-id]").length ? e.find("[data-id]").text("-") : e.text("-"), this.$play.find(".table-current").removeClass("table-current")
			},
			bet: function(e, t) {

				return i.ajax({
					url: "/index.php/lottery/lottery/bet?",
					data: JSON.stringify({
						lotteryId: e,
						betParameters: t
					})
				}, a.bet.loading)
			},
			getData: function() {
				var t = this;
				this.records = this.$doc.find(".input").map(function(n, r) {
					var i = e(this),
						s = parseInt(i.attr("data-id"), 10),
						o, u, a, f = i.attr("data-odds");
					u = e.trim(i.parent("td").prevAll(".table-odd").eq(0).text()), o = parseInt(e.trim(i.val()), 10), a = t.options.category;
					if (s && o && !isNaN(o)) return {
						category: a,
						name: u,
						id: s,
						odds: f,
						amount: o.toFixed(2)
					}
				}).get(), this.getPostData()
			},
			getPostData: function() {
				this.data = t.map(this.records, function(e, t) {
// console.log(e);
// console.log(e);
					return {
						id: e.id,
						gname:e.category,
						BetContext: e.name,
						Lines: e.odds,
						BetType: this.options.BetType,
						Money: e.amount,
						IsTeMa: this.options.IsTeMa,
						IsForNumber: this.options.IsForNumber,
						mingxi_1:this.options.lotteryPan
					}
				}, this)

			},
			getRecordsHtml: function() {
				var n = e("#j-confirm-tpl").html(),
					r, i = this.records.length,
					o = 0;
				this.isQuickMode ? o = this.amount * i : t.each(this.records, function(e) {
					o += parseFloat(e.amount)
				});
				var u = {
					col: i > 6 ? "two-col" : "",
					currency: this.cfg.currency,
					total: o,
					sum: i,
					items: this.records
				};
				return r = s.render(n, u), r
			},
			afterGetBet: function(e) {},

			getBetInfo: function() {
				var e = this;


				return i.ajax({
					url: "/index.php/lottery/lottery/get_json",
					// url: "./tests",
					data: JSON.stringify({

						lotteryId: this.lotteryId,

						numberPostion: e.NumberPostion || ""
					}),
					success: function(t) {
						if(t.errId == 1024){
							// alert(t.msg);
							ShowDiv('MyDiv','fade');
						}else{
							e.afterGetBet(t);
						var r;
						t.Success ? (e.betInfo = t.Obj, e.updateAmount(t), r = e.betInfo.PrePeriodNumber, e.PrePeriodNumber != null && e.PrePeriodNumber != r && e.isSoundOn && u.play(), e.PrePeriodNumber = r) : n.error(t.Msg), t.ExtendObj && !t.ExtendObj.IsLogin && e.onAuthFail()
						}

					},
					timeout: 3e3
				})
			},
			playSound: u.play,
			onLinesUpdated: function(e) {
				n.dialog({
					title: a.bet.linesUpdated,
					content: e,
					ok: function() {}
				}).showModal()
			},
			afterBet: function(e) {
				t.delay(function() {
					location.reload()
				}, 2e3)
			},
			listenAmount: function() {
				var t = this;
				this.$doc.on("change keyup blur", ".fb", function() {
					var n = e(this).val();
					n && t.isQuickMode ? t.amount = parseInt(n, 10) : (t.amount = 0, e(this).val(""))
				})
			},
			handlerResult: function(o) {
				function h() {
					var n;
					t.isArray(u.records) ? n = t.find(u.records, {
						id: c
					}) : n = u.records, !n && u.options.useCategoryAsName && (n = u.records.length ? u.records[0] : {}, e.isArray(n) && n.length ? n = n[0] : n.category = u.options.category);
					if (c == 800 || c == 801 || c == 2955 || c == 2956) n.name = "";
					u.options.useCategoryAsName && (n.name = "");
					if (!n) return a.msg.errorNumber;
					var r = s.to_html("{{category}}{{name}}", {
						category: n.category,
						name: n.name ? "[" + n.name + "]" : ""
					});
					return l = i.format(l, r), l
				}
				var u = this;
				if (o) {
					var f = o.result,
						l = o.msg,
						c = o.errId;
					if (f === 9) {
						this.onLinesUpdated(h());
						return
					}
					switch (f) {
						case 1:
							l = a.bet.success, this.afterBet(l);
							break;
						case 4:
						case 5:
						case 6:
						case 10:
							h();
							break;
						default:
					}
					f.isSoundOn && u.play(p),
					n.msg(l, 2500)
				} else f.isSoundOn && u.play("fail"),
	            n.error(a.bet.unknownError)
			},
			filter: function(t) {
				function i(e) {
					r.length && (r.val(e), n.amount = e)
				}
				var n = this,
					r = e(".sync-bet");
				this.$doc.on("keyup blur", t, function() {
					var t = e(this),
						r = e.trim(t.val()),
						s = parseInt(r, 10),
						o = 99999999,
						u = t.data("max");
					u && (u = parseInt(u, 10), o = u), !isNaN(s) && s > 0 && s <= o ? (t.val(s), n.isQuickMode && i(s)) : (t.val(""), i(""))
				})
			},
			updateAmount: function(t) {
				function i(t, n) {
					e("#j-balance").text(r + t), e("#j-orders").text(r + n)
				}

				var n = this,
					r = this.cfg.currency;
				t ? (t = t.Obj, i(t.Balance, t.NotCountSum)) : e.post("/index.php/lottery/lottery/GetBalance", {
					lotteryId: n.lotteryId
				}, function(t) {
					var r = t.Obj;
					// alert(t.Obj.ChangLong[0]);
					t.Success && (t = t.Obj, i(t.Balance, t.NotCountSum), n.lotteryId == 10 && e("#win-lose").text(t.WinLoss));
					if (n.lotteryId == 'liuhecai' || n.lotteryId == 10) n.lotteryId == 10 && e("#current-issue").text(r.CurrentPeriod + n.lang.msg.issue), n.getPreBall(r)
				})
			},
			marquee: function() {
				function i(e) {
					var t = n.dialog({
						id: "d-notice-info",
						title: a.msg.notice,
						width: "500px",
						content: "xxx"
					});
					t.showModal()
				}
				var t, r = "";
				this.getNotice().done(function(n) {

					var r = "";
					n && n.Success && (n = n.Obj, n.notice && n.notice.length && (t = n, r = s.render('{{#notice}}<span class="j-show-more-notice" data-id="{{ID}}">{{Content}}</span>{{/notice}}', n), e("#marquee").before('<i class="icon notify-icon"></i>').html(r).marquee({
						kclass: "marquee",
						speed: 50
					}).mouseover(function() {
						e(this).trigger("stop")
					}).mouseout(function() {
						e(this).trigger("start")
					})))
				}), this.$doc.on("click", ".j-show-more-notice", function(t) {
					t.preventDefault();
					var n = e(this).attr("data-id")
				})
			},
			openNavDialog: function() {
				this.$doc.on("click", ".odfi", function(t) {
					t.preventDefault();
					var r = e(this).data("href"),
						i = this.title,
						s = e(this).data("height"),
						o = n.dialog({
							id: "d-account-info",
							title: i,
							width: "820px",
							height: s || 350,
							content: '<iframe src="' + r + '" frameborder="0" width="100%" height="100%" style="position:relative"></iframe>'
						});
					o.showModal()
				})
			},
			_renderIframe: function() {
				var e = this,
					t = document,
					n = document.getElementById("myifr");
				n && (n.src = e.cfg.officalDomain + "/lottery/lottery/setiframe?height=" + t.body.clientHeight)
			}
		}), f
	});

function writeObj(obj){
 var description = "";
 for(var i in obj){
  var property=obj[i];
  description+=i+" = "+property+"\n";
 }

}