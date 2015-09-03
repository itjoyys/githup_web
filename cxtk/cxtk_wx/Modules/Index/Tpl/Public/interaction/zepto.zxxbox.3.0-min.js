(function($) {
	//给页面装载CSS样式
	var LG = 'linear-gradient(top, #fafafa, #eee)', CSS = '<style type="text/css">' +
		'#zxxBlank{position:absolute;z-index:2000;left:0;top:0;width:100%;height:0;background:black;}' +
		'.wrap_out{padding:5px;background:#eee;box-shadow:0 0 6px rgba(0,0,0,.5);position:absolute;z-index:2000;left:-9999px;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;}' +
		'.wrap_in{background:#fafafa;border:1px solid #ccc;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;}' +
		'.wrap_bar{border-bottom:1px solid #ddd;background:#f0f0f0;background:-moz-'+ LG +';background:-o-'+ LG +';background:-webkit-'+ LG +';background:'+ LG +';}' +
		'.wrap_title{line-height:30px;padding-left:10px;margin:0;font-weight:bold;font-size:14px;}' +
		'.wrap_close{position:relative;}' +
		'.wrap_close a{width:20px;height:20px;text-align:center;margin-top:-25px;color:#34538b;font:bold 1em/20px Tahoma;text-decoration:none;cursor:pointer;position:absolute;right:6px;}' +
		'.wrap_close a:hover{text-decoration:none;color:#f30;}' +
		'.wrap_body{background:white;}' +
		'.wrap_remind{padding:20px 20px;color:#333;min-width:200px;}' +
		'.wrap_remind p{margin:10px 0 0;}' +
		'.submit_btn, .cancel_btn{width:100px;}' +
		'.submit_btn{}' +
		'.submit_btn:hover{}' +
		'.cancel_btn{}' +
	'</style>';
	$("head").append(CSS);	  
	
	var WRAP = '<div id="zxxBlank" onselectstart="return false;"></div>' + 
		'<div class="wrap_out" id="wrapOut">' +
			'<div class="wrap_in" id="wrapIn">' +
				'<div id="wrapBar" class="wrap_bar" onselectstart="return false;">' +
					'<h4 id="wrapTitle" class="wrap_title"></h4>' +
					'<div class="wrap_close"><a href="javasctipt:" id="wrapClose" title="关闭"></a></div>' +	
				'</div>' +
				'<div class="wrap_body" id="wrapBody"></div>' +
			'</div>' +
		'</div>';
	
	$.fn.zxxbox = function(options) {	
		options = options || {};
		var s = $.extend({}, zxxboxDefault, options);
		return this.each(function() {		
			var node = this.nodeName.toLowerCase();
			if (node === "a" && s.ajaxTagA) {
				$(this).click(function() {
					var href = $.trim($(this).attr("href"));
					if (href && href.indexOf("javascript:") != 0) {
						if (href.indexOf('#') === 0) {
							$.zxxbox($(href), options);
						} else {
							//加载图片
							$.zxxbox.loading();
							var myImg = new Image(), element;
							myImg.onload = function() {
								var w = myImg.width, h = myImg.height;
								if (w > 0) {
									var element = $('<img src="'+ href +'" width="'+ w +'" height="'+ h +'" />');
									options.protect = false;
									$.zxxbox(element, options);
								}
							};
							myImg.onerror = function() {
								//显示加载图片失败
								$.zxxbox.ajax(href, {}, options);
							};
							myImg.src = href;
						}
					}	
					return false;
				});
			} else {
				$.zxxbox($(this), options);	
			}
		});				
	};
	
	$.zxxbox = function(elements, options) {
		if (!elements) {
			return;	
		}

		var s = $.extend({}, zxxboxDefault, options || {});

		//弹框的显示
		var eleOut = $("#wrapOut"), eleBlank = $("#zxxBlank");
					
		if (eleOut.size()) {
			eleOut.show();
			eleBlank[s.bg? "show": "hide"]();
		} else {
			$("body").append(WRAP);	
		}
		
		if (typeof(elements) === "object") {
			elements.show();
		} else {
			elements = $(elements);
		}
		//一些元素对象
		$.o = {
			s: s,
			ele: elements,
			bg: eleBlank.size()? eleBlank: $("#zxxBlank"), 
			out: eleOut.size()? eleOut: $("#wrapOut"), 
			tit: $("#wrapTitle"),
			bar: $("#wrapBar"), 
			clo: $("#wrapClose"),
			bd: $("#wrapBody")
		};
		
		// 标题以及关闭内容
		$.o.tit.html(s.title);
		$.o.clo.html(s.shut);
		
		//装载元素
		$.o.bd.empty().append(elements);

		if ($.isFunction(s.onshow)) {
			s.onshow();
		}
		//尺寸
		$.zxxbox.setSize();
		//定位
		$.zxxbox.setPosition();

		if (s.fix) {
			$.zxxbox.setFixed();
		}
		if (s.drag) {
			$.zxxbox.drag();	
		} else {
			$(window).resize(function() {
				$.zxxbox.setPosition();					  
			});	
		}
		if (!s.bar) {
			$.zxxbox.barHide();	
		} else {
			$.zxxbox.barShow();	
		}
		if (!s.bg) {
			$.zxxbox.bgHide();
		} else {
			$.zxxbox.bgShow();
		}
		if (!s.btnclose) {
			$.zxxbox.closeBtnHide();	
		} else {
			$.o.clo.click(function() {
				$.zxxbox.hide();	
				return false;
			});
		}
		if (s.bgclose) {
			$.zxxbox.bgClickable();	
		}
		if (s.delay > 0) {
			setTimeout($.zxxbox.hide, s.delay);	
		}
	};
	$.extend($.zxxbox, {
		setSize: function() {
			if (!$.o.bd.size() || !$.o.ele.size() || !$.o.bd.size()) {
				return;	
			}
			//主体内容的尺寸
			$.o.out.css({
				"width": $.o.s.width,
				"height:": $.o.s.height
			});
						
			return $.o.out;
		},
		setPosition: function(flag) {
			flag = flag || false;
			if (!$.o.bg.size() || !$.o.ele.size() || !$.o.out.size()) {
				return;	
			}
			var w = $(window).width(), h = $(window).height(), st = $(window).scrollTop(), ph = $("body").height();
			if (ph < h) {
				ph = h;	
			}
			$.o.bg.width(w).height(ph).css("opacity", $.o.s.opacity);
			//主体内容的位置
			//获取当前主体元素的尺寸
			var xh = $.o.out.height(), xw = $.o.out.width();
			var t = st + (h - xh)/2, l = (w - xw)/2;
			
			if ($.o.s.fix && window.XMLHttpRequest) {
				t = (h - xh)/2;
			}
			if (flag === true) {
				$.o.out.animate({
					top: t,
					left: l
				});
			} else {
				$.o.out.css({
					top: t,
					left: l,
					zIndex: $.o.s.index
				});
			}
			return $.o.out;
		},
		//定位
		setFixed: function() {
			if (!$.o.out || !$.o.out.size()) {
				return;	
			}
			if (window.XMLHttpRequest) {
				$.zxxbox.setPosition().css({
					position: "fixed"			
				});
			} else {
				$(window).scroll(function() {
					$.zxxbox.setPosition();						  
				});
			}
			return $.o.out;
		},
		//背景可点击
		bgClickable: function() {
			if ($.o.bg && $.o.bg.size()) {
				$.o.bg.click(function() {
					$.zxxbox.hide();
				});
			}
		},
		//背景隐藏
		bgHide: function() {
			if ($.o.bg && $.o.bg.size()) {
				$.o.bg.hide();
			}
		},
		//背景层显示
		bgShow: function() {
			if ($.o.bg && $.o.bg.size()) {
				//$.o.bg.show();
				$.o.bg.css('display', 'block');
			} else {
				$('<div id="zxxBlank"></div>').prependTo("body");	
			}
		},
		//标题栏隐藏
		barHide: function() {
			if ($.o.bar && $.o.bar.size()) {
				$.o.bar.hide();
			}
		},
		//标题栏显示
		barShow: function() {
			if ($.o.bar && $.o.bar.size()) {
				$.o.bar.show();
			}
		},
		//关闭按钮隐藏
		closeBtnHide: function() {
			if ($.o.clo && $.o.clo.size()) {
				$.o.clo.hide();
			}
		},
		//弹框隐藏
		hide: function() {
			if ($.o.ele && $.o.out.size() && $.o.out.css("display") !== "none") {
				$.o.out.fadeOut("fast", function() {
					if ($.o.s.protect && (!$.o.ele.hasClass("wrap_remind") || $.o.ele.attr("id"))) {
						$.o.ele.hide().appendTo($("body"));
					}
					$(this).remove();
					if ($.isFunction($.o.s.onclose)) {
						$.o.s.onclose();
					}
				});
				if ($.o.bg.size()) {
					$.o.bg.fadeOut("fast", function() {
						$(this).remove();								
					});
				}
			}
			return false;
		},
		//拖拽
		drag: function() {
			if (!$.o.out.size() || !$.o.bar.size()) {
				$(document).unbind("mouseover").unbind("mouseup");
				return;
			}
			var bar = $.o.bar, out = $.o.out;
			var drag = false;
			var currentX = 0, currentY = 0, posX = out.css("left"), posY = out.css("top");
			bar.mousedown(function(e) {
				drag = true;
				currentX = e.pageX;
				currentY = e.pageY;							 
			}).css("cursor", "move");	
			$(document).mousemove(function(e) {
				if (drag) {
					var nowX = e.pageX, nowY = e.pageY;
					var disX = nowX - currentX, disY = nowY - currentY;
					out.css("left", parseInt(posX) + disX).css("top", parseInt(posY) + disY);
				}					   
			});
			$(document).mouseup(function() {
				drag = false;
				posX = out.css("left");
				posY = out.css("top");
			});
		},
		//预载
		loading: function() {
			var element = $('<div class="wrap_remind">加载中...</div>');
			$.zxxbox(element, { bar: false });
		},
		//ask询问方法
		ask: function(message, sureCall, cancelCall, options) {
			var element = $('<div class="wrap_remind">'+message+'<p><button id="zxxSureBtn" class="btn btn-default submit_btn">确认</button>&nbsp;&nbsp;<button id="zxxCancelBtn" class="cancel_btn">取消</button></p></div>');
			$.zxxbox(element, options);
			//回调方法
			$("#zxxSureBtn").click(function() {
				if ($.isFunction(sureCall)) {
					sureCall.call(this);
				}						
			});
			$("#zxxCancelBtn").click(function() {
				if (cancelCall && $.isFunction(cancelCall)) {
					cancelCall.call(this);
				}
				$.zxxbox.hide();						  
			});	
		},
		//remind提醒方法
		remind: function(message, callback, options) {
			var element = $('<div class="wrap_remind">'+message+'</div>');
			if(options.button!=false) element.append('<p><button id="zxxSubmitBtn" class="btn btn-default submit_btn">确认</button</p>');
			$.zxxbox(element, options);
			$("#zxxSubmitBtn").click(function() {
				//回调方法
				if (callback && $.isFunction(callback)) {
					callback.call(this);	
				}
				$.zxxbox.hide();							  
			});
				
		},		
		//uri Ajax方法
		ajax: function(uri, params, options) {
			if (uri) {
				$.zxxbox.loading();
				options = options || {};
				options.protect = false;
				$.ajax({
					url: uri,
					data: params || {},
					success: function(html, other) {
						$.zxxbox(html, options);
					},
					error: function() {
						$.zxxbox.remind("加载出了点问题。");	
					}
				});	
			}
		}
	});
	var zxxboxDefault = {
		title: "对话框",
		shut: "×",
		index: 2000,
		opacity: 0.5,
		
		width: "auto",
		height: "auto",
		
		bar: true, //是否显示标题栏
		bg: true, //是否显示半透明背景
		btnclose:true, //是否显示关闭按钮
		
		fix: false, //是否弹出框固定在页面上
		bgclose: false, //是否点击半透明背景隐藏弹出框
		drag: false, //是否可拖拽
		
		ajaxTagA: true, //是否a标签默认Ajax操作
		
		protect: "auto", //保护装载的内容
		
		onshow: $.noop, //弹窗显示后触发事件
		onclose: $.noop, //弹窗关闭后触发事件
		
		delay: 0 //弹窗打开后关闭的时间, 0和负值不触发
	};
})(Zepto);