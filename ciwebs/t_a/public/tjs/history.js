define(["jquery", "/public/tjs/datepicker.js", "/public/tjs/datepicker-zh-CN.js"], function(e) {
	function t(t) {
		this.options = t, t.lotteryId == 1 && e(".j-datepicker").hide(), this.init()
	}
	return t.prototype.init = function() {
		var t = this;
		e(function() {
			function u(e) {
				var r = o.find('a[data-id="' + e + '"]');
				if (r.length) {
					var u = r.text();
					o.find(".current").removeClass("current"), r.addClass("current"), e == 1 ? n.hide() : n.show(), s.val(e), i.text(u), t.getHistory()
				}
			}
			var n = e(".j-datepicker");
			n.datepicker({
				changeMonth: !0,
				maxDate: 0,
				onSelect: function() {
					t.getHistory()
				}
			}), e("#q").click(function(e) {
				e.preventDefault(), t.getHistory()
			});
			var r = e("#q-id"),
				i = r.find(".q-val"),
				s = e("#q-hidden-val"),
				o = r.find(".select-options");
			r.hover(function() {
				o.stop(!0, !0).fadeIn(300)
			}, function() {
				o.fadeOut(300)
			}).on("click", "a", function(t) {
				t.preventDefault(), o.fadeOut(300), u(e(this).data("id"))
			}), u(t.options.lotteryId)
		})
	}, t.prototype.getHistory = function() {
		var t = e("#q-form").serialize(),
			n = e("#loading"),
			r = e("#history"),
			i = e("#hid-empty-data").val();
			l = e("#q-hidden-val").val();
			if(l=='liuhecai'||l=='fc_3d'||l=='pl_3'){
				e("#date").hide();
			}
		n.show(), r.load("/index.php/lottery/History/index?v=" + (new Date).getTime() + " #history>table", t, function(t) {
			n.hide();
			var s = e(t).find(".table-bordered tbody > tr");
			s.length || r.html(i)
		})
	}, t
});