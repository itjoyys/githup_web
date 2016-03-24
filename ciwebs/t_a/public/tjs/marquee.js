define(["jquery"], function(e) {
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
});