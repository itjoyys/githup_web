
define(["jquery", "/public/tjs/lottery.js"], function(e, t) {
	var n, r = "button-current",
		i = "table-current";

	return n = t.extend({
		initialize: function(t) {

			var r = this,
				i = _.extend({
					hln: !0,
					lotteryId: 'bj_10'
				}, t);
			n.superclass.initialize.call(this, i), this.isOpen = !0, this._np = 0, this.refreshTimer = new this.CountDown(this.refreshDuration), this.cache = {
				lz: null,
				ranking: null
			}, e(function() {
				r.setLiveUrl(i.lotteryId), r.ballTpl = e("#tpl-prev-balls").html(), r.$selectedCount = e("#j-selected-count"), r._events()
			})
		},
		close: function() {
			n.superclass.close.call(this), this.$play.find(".input").val(""), this._setSelectedCount(0), this.isLianMa && (this.$ctn.find(":checked").prop("checked", !1), this._LianMaConfig && this._LianMaConfig.balls && (this._LianMaConfig.balls.length = 0))
		},
		open: function(e) {
			if (this.isLianMa)
				if (e) {
					var t = this.$play.find(":checkbox");
					t._luzCurrentTabgth == t.filter(":disabled").length ? n.superclass.open.call(this) : this.$play.find("input:not(:checkbox),button").prop("disabled", !1)
				} else n.superclass.open.call(this);
			else n.superclass.open.call(this);
			!e && this.refresh()
		},
		getCategory: function(e) {
			var t = e.closest(".j-betting");
			//console.log(t);
			return this.$ctn.find(".h-category").val() || t.find("th").eq(0).text() || t.closest("tr").prevAll(".thead").eq(0).find("th").text()
		},
		reset: function() {
			n.superclass.reset.call(this), this._setSelectedCount(0), this.$ctn.find("." + i).removeClass(i), this.$ctn.find(".input").val(""), this.isLianMa ? (e(".button-secondary-group,.selected-ball,.removable").hide(), e("#j-normal-form").show(), this._resetLianMa(), this.$ctn.find("." + i).removeClass(i)) : e(".button-secondary-group,.selected-ball,.removable").show()
		},
		getData: function() {
			var t = this,
				n, r;
			if (this.isLianMa) {
				this._LianMaConfig.lines === "0" && (this._LianMaConfig.lines = e(".j-current-odds").find("span").text()), t.$doc.find(".lm-total").text(0);
				return
			}
			r = this.isQuickMode ? this.$ctn.find(".j-betting tr." + i) : this.$ctn.find(".j-betting .fb"), this.records = r.map(function(n, r) {
				var i = e(this),
					s, o, u, a;
				t.isQuickMode ? a = t.amount : (i = e(this).closest("tr"), a = parseInt(e.trim(i.find("input").val()), 10));
				if (t.isQuickMode || a) return u = t.getCategory(i), a > 0 && (a = a.toFixed(2)), {
					category: u?u:i.attr('data-type'),
					id: parseInt(i.attr("data-id"), 10),
					name: i.find(".table-odd").text(),
					odds: i.attr("data-odds"),
					amount: a
				}
			}).get(), this.isQuickMode && t._setSelectedCount(), this.getPostData()
		},
		afterBet: function() {
			this.reset(), this.isLianMa && this._resetLianMa(), this.refresh()
		},
		afterGetBet: function(e) {
			if (this.isLianMa) {
				var t = this.$play.find(":radio");
				t.filter(":checked").length || t.eq(0).prop("checked", !0).trigger("change")
			}
		},
		beforeConfirm: function() {
			var e = this;
			return this.isLianMa && !e.amount ? (e.ui.msg(e.lang.msg.emptyAmount), !1) : !0
		},
		valid: function() {
			var e = this;
			if (this.isLianMa) {
				var t = this._LianMaConfig;
				return t.id ? t.balls.length < t.min ? (e.ui.msg(e.utils.format(e.lang.msg.minNumbers, t.min)), !1) : !0 : (e.ui.msg(e.lang.msg.empty), !1)
			}
			return n.superclass.valid.call(e)
		},
		afterRefresh: function(e) {
			var t = this;

			this._updateRanking(e.ChangLong), this._updateSummary(e), this._updateLuZhu(), this._updateHitBalls(e.ZongchuYilou), this._renderBall(e), this.isOpen && this._updateLines(e.Lines), this.refreshTimer.update = function(e) {}, this.refreshTimer.done = function() {
				t.refresh()
			}, t.refreshTimer.restart()
		},
		getRecordsHtml: function() {
			var t, r, i, s = this;
			return this.isLianMa ? (i = e("#j-lm-tpl").html(), t = s._LianMaConfig, t.group = s.utils.combination(t.balls, t.min).length, t.total = 0, t.money = 0, r = s._.template(i)(t), t.total == 0 && e(r).find(".lm-total").text(0), r) : n.superclass.getRecordsHtml.call(this)
		},
		beforeBet: function(e) {

			var t, n = this;

			this.isLianMa && (t = n._LianMaConfig, n.data = [{

				BetContext: t.balls.join(","),
				min:t.min,
				max:t.max,
				gname:t.title,
				Money: n.amount,
				Lines: t.lines,
				Id: t.id,
				BetType: 5
			}])

		},
		setLiveUrl: function(t) {
			var n = e(".live"),
				r = "http://www.133918.com/";
			switch (t) {
				case 2:
					r += "gdkl10/shipin/";
					break;
				case 3:
					r += "shishicai/shipin/";
					break;
				case 4:
					r += "pk10/shipin/";
					break;
				case 5:
					r += "xync/shipin/";
					break;
				case 6:
					r += "xyft/shipin";
					break;
				case 7:
					r += "sdc/";
					break;
				default:
			}
			n.attr("href", r)
		},
		_setSelectedCount: function(e) {
			e == undefined && (e = this.records.length), this.$selectedCount.find(".j-selected-count").text(e)
		},
		_afterTabChange: function(e) {
			this._luzCurrentTab = 0
		},
		_tabs: function() {
			var t = this,
				n = e("#play-tab");
			n.on("click", "li", function(n) {
				n.preventDefault();
				var r = e(this),
					i = e(r.data("target"));
				t.isLianMa = r.data("type") === "lm", t.disabledHits = r.data("hits") === "yes", t.disabledTrends = r.data("trends") === "yes";
				if (i.length) {
					t.$ctn = i;
					var s = t.isQuickMode ? 0 : 1;
					t.$doc.find(".button-secondary-group button").eq(s).trigger("click"), r.addClass("active").siblings("li").removeClass("active"), t.NumberPostion = r.data("np"), t._np = r.index(), i.show().siblings().hide(), t._afterTabChange.call(t, r), t.refresh(), t.isLianMa && t._LianMa()
				}
			}), n.find("li").eq(t._np).trigger("click")
		},
		_events: function() {
			var t = this;

			this.$doc.on("click", ".button-secondary-group button", function(n) {
				n.preventDefault();
				var i = e(this),
					s = e(this).data("mode");

				i.addClass(r).siblings().removeClass(r), t.reset();
				if (!s) return;
				t.setQuickMode(s === "quick"), t._toggleElements()
			}), this.$doc.on("click", ".j-betting td", function(n) {
				n.preventDefault();
				//console.log(t);
				var r = e(this).parent("tr");
				if (t.isQuickMode) {
					if(t.isOpen==true){
					var s = r.find("td"),
						o = e.trim(r.find(".table-odd").text());
					if (!o) return;
					s.toggleClass(i), r.toggleClass(i), t.getData()
					}
				} else r.find(".input").focus()
			}), this._tabs()
		},
		_toggleElements: function() {
			var e = this.$ctn.find(".j-odds"),
				t = this.$doc.find(".quick-form"),
				n = this.$doc.find(".normal-form"),
				r = "is-quick-mode",
				i = "is-normal-mode";
			this.isQuickMode ? (t.show(), n.hide(), e.hide(), this.$selectedCount.show(), this.$play.removeClass(i).addClass(r)) : (t.hide(), e.show(), n.show(), this.$play.removeClass(r).addClass(i), this.$selectedCount.hide())
		},
		_updateLines: function(t) {
			var n = this;
			for(var i in t){
				ii = (t[i].lastIndexOf(",") > 0 ? t[i].lastIndexOf(","):t[i].length),
				t[i] = t[i].substring(0, ii);
		  }
			if (this.isLianMa) {
				e('label[for^="j"]').each(function() {
					var r = e(this),
						i = r.attr("for"),
						s = t[i],
						o = n.$ctn.find("#" + i);
					s ? (o.prop("disabled", !1), e(this).find("span").text(s)) : o.prop("disabled", !0)
				});
				return
			}
			this.$play.find("tr[data-id]").each(function() {
				var n = e(this),
					r = "j" + n.attr("data-id"),
					i = t[r];
				i = i > 0 ? i : 0, n.attr({
					"data-odds": i
				}).find(".odds-text").text(i)
			})
		},
		_updateRanking: function(t) {
			var n, r, i = _.map(t, function(e, t) {
				var n = "";
				return t % 2 == 0 && (n = "table-odd"), {
					name: e[0],
					issue: e[1],
					odds: n
				}
			});
			if (_.isEqual(i, this.cache.ranking)) return;
			this.cache.ranking = i, n = '{{#items}}<tr><td class="{{odds}} tal">{{name}}</td><td class="{{odds}} td-issue number">{{issue}}' + this.lang.msg.issue + "</td></tr>{{/items}}", r = this.tpl.to_html(n, {
				items: i
			}), e("#changelong").find("tbody").html(r)
		},
		_updateSummary: function(t) {

			var n = this,
				r = t.CloseCount;
			//console.log(t);
//			if(!t.IsLogin){
//				n.close(),this.ui.msg('请登录');// e("#close-timer").text("未登录");
//			}else{
			r > 0 && n.open(!0), n.closeTimer != null && n.closeTimer.stop(), n.closeTimer = new this.CountDown(r),
			n.closeTimer.update = function(t) {
	            function s(t,timer) {
	                var r, s,i = n.lang.date;
	                s = n.utils.secondsFormat(t),        
	                _.map(i,
	                function(t, timer) {
	                    var n = s[timer];
	                   // s[timer] = s[timer] + t
	                    n > 0 ? s[timer] = s[timer] + t: s[timer] = null
	                }),
	                r = n.tpl.to_html("{{days}}{{hours}}{{minutes}}{{seconds}}", s),	            
	                timer.text(r)
	            }
				s(t,e("#close-timer"));
				//e("#close-timer").text(n.utils.secondsFormat(t));
				
			},
			n.closeTimer.done = function() {
				n.close(),e("#close-timer").text(n.lang.msg.closedGate);
			}, n.closeTimer.start(), n.awarTimer != null && n.awarTimer.stop(), n.awarTimer = new this.CountDown(Math.abs(t.OpenCount) + 5), n.awarTimer.update = function(t) {
				//e("#award-timer").text(n.utils.secondsFormat(t, !0));
	            function s(t,timer) {
	                var r, s,i = n.lang.date;
	                s = n.utils.secondsFormat(t),
	          
	                _.map(i,
	                function(t, timer) {
	                    var n = s[timer];
	                   // s[timer] = s[timer] + t
	                    n > 0 ? s[timer] = s[timer] + t: s[timer] = null
	                }),
	                r = n.tpl.to_html("{{days}}{{hours}}{{minutes}}{{seconds}}", s),
		            
	                timer.text(r)
	            }
				s(t,e("#award-timer"));
			}, n.awarTimer.done = function() {
				n.open()
			}, n.awarTimer.start(), e("#current-issue").text(t.CurrentPeriod + n.lang.msg.issue), e("#win-lose").text(t.WinLoss), e("#prev-issue").text(t.PrePeriodNumber)
			
			},
		_updateHitBalls: function(t) {
			var n = this,
				r = e("#trends"),
				i = e("#tpl-hit-miss").html();
			r.show();
			if (n.disabledHits) {
				r.hide();
				return
			}
			t.hit = t.hit["n" + n.NumberPostion], r.html(this.tpl.to_html(i, t))
		},
		_updateLuZhu: function(t) {

			var n = e("#luzhu"),
				r = e("#tpl-luzhu").html(),
				i = this,
				s = i._luzCurrentTab || 0;
			n.show();

			var o = t || _.where(this.betInfo.LuZhu, {
				p: i.NumberPostion
			});

			if (!o.length || i.disabledTrends) {
				n.hide();
				return
			}
			if (_.isEqual(o, this.cache.lz)) return;
			this.cache.lz = o;
			var u = _.map(o, function(e) {
					var t = _.map(e.c.split(","), function(e) {
							var t = e.split(":"),
								n = t[0],
								r = t[1],
								i = r > 1 ? _.times(r, function() {
									return n
								}) : [n];
							return {
								item: i
							}
						}),
						n = 30 - t.length;
					return n > 0 && _.times(n, function() {
						t.push({
							item: []
						})
					}), {
						hd: e.n,
						bd: {
							items: t.reverse()
						}
					}
				}),
				a = {
					hd: _.pluck(u, "hd"),
					bd: _.pluck(u, "bd")
				};

			n.html(this.tpl.to_html(r, a)).tab({
				mouseover: !0,
				current: s,
				selected: function(e, t, n) {
					i._luzCurrentTab = n, i._renderIframe()
				}
			})
		},
		_renderBall: function(t) {
			var n = this.ballTpl;
			t = t.PreResult.split(",");
			var r = this.tpl.to_html(n, {
				balls: t
			});
			r && e("#prev-bs").html(r)
		},
		_resetLianMa: function() {
			this._genderedLianMaBalls(!0).filter(":checked").prop("checked", !1).trigger("change"), this.$ctn.find(":radio:checked").prop("checked", !1), this.amount = 0
		},
		_genderedLianMaBalls: function(t) {
			var n = this.$ctn.find(":checkbox");
			return t ? n : n.filter(":checked").map(function() {
				return e(this).attr("id").replace("b-", "")
			}).get()
		},
		_LianMa: function() {
			var t = this;
			this._LianMaConfig = {
				max: 6,
				total: 0,
				min: 2,
				money: 0,
				lines: 0,
				title: "",
				balls: [],
				_balls: [],
				id: 0,
				group: 0
			};
			var n = this._LianMaConfig,
				r = "j-current-odds";
			t.$ctn.off("change", 'input[name="lm"]').on("change", 'input[name="lm"]', function() {
				$("input[type='checkbox']").each(function(index, el) {
					$(this).attr('checked', false);
					$(this).attr('disabled', false);
					$(this).parent("td").parent("tr").removeClass(i);
				});
				//console.log(t);
				var i = e(this),
				s = i.data("min"),
				m=0,
				o = e('label[for="' + this.id + '"]');
				if(t.lotteryId=='bj_8'){
				    switch (i.data("min")) {
			        case 1:
			        	m=10;
			        break;
			        case 2:
			        	m=6;
			        break;
			        case 3:
			        	m=6;
			        break;
			        case 4:
			        	m=7;
			        break;
			        case 5:
			        	m=8;
			        break;
			        default:
			            ;
			        break;
			    }
				}else{
					m = i.data("min")+3
				}

//					if(s>1){
//						m=6;
//					}
					// if(s==2){
					// 	m = 6;
					// }else if(s==3){
					// 	m = 7;
					// }else if(s==4){
					// 	m = 8;
					// }else if(s==5){
					// 	m = 8;
					// }

				e("." + r).removeClass(r), o.addClass(r);
				var u = o.find("span").text();
				n.title = o.find("b").text(), n.id = i.val(), u && (n.lines = u), n.min = s,n.max = m, t.$ctn.find(":checkbox:checked:first").trigger("change")
			}), t.$ctn.off("change", ":checkbox").on("change", ":checkbox", function() {

				//t.ui.msg(t.lang.msg.limit);
			//	alert(e(this).lang.msg.emptyAmount);
				//console.log(t.lang.msg.limit);
				var langtext = "只允许选择"+n.max+"个号码";
				t._genderedLianMaBalls().length > n.max ?(t.ui.msg(langtext), $(this).prop("checked", !1)) : e(this).parent('td').parent('tr').toggleClass(i), n.balls = t._genderedLianMaBalls(), n._balls = [{
					id: parseInt(n.id, 10),
					category: n.title
				}], t.records = {
					category: n.title,
					id: n.id
				}
			}), t.$doc.off("keyup blur", ".single-bet").on("keyup blur", ".single-bet", function() {
				var r = e(this),
					i = r.val();
				i ? (n.money = (i * n.group).toFixed(2), n.total = n.money, t.amount = i) : t.amount = 0, t.$doc.find(".lm-total").text(n.total)
			}), t.$doc.off("click", ".j-highlights-tb tr").on({
				mouseenter: function() {
					//e(this).addClass(i)
				},
				mouseleave: function() {
					//e(this).removeClass(i)
				},
				click: function(t) {
					var n, r;
					n = e(this).find("input");
					if (n.prop("disabled")) return !1;
					e(t.target).is(n) || (t.preventDefault(), r = n.prop("checked"), n.prop("checked", !r).trigger("change"))
				}
			}, ".j-highlights-tb tr")
		}
	}), n
});