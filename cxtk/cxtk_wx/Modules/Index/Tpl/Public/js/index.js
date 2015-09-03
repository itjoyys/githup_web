$(document).ready(function() {
	$("body").css("width", "320px");
	$("body").css("margin", "0 auto")
});
var tX = [];
var tY = [];
var x;
var y;
var isSlide = true;
var clientX, clientY;

function touchstart(a) {
	x = a.touches[0].pageX;
	y = a.touches[0].pageY;
	clientX = a.touches[0].clientX;
	clientY = a.touches[0].clientY;
	tX.push(x);
	tY.push(y);
	var b = $("#func").css("margin-left");
	y = parseInt(b.split("px").shift())
}

function touchmove(c) {
	isSlide = true;
	var d = c.touches;
	var e = d[0];
	tX.push(e.pageX);
	tY.push(e.pageY);
	if (tX != undefined && tX.length > 1) {
		var b = Math.abs(e.clientX - clientX);
		if (tY != undefined && tY.length > 1) {
			var a = Math.abs(e.clientY - clientY);
			if (b > a) {
				if (y == -298) {
					c.preventDefault();
					$("#func").css("margin-left", (e.pageX - 298 - x) + "px")
				} else {
					c.preventDefault();
					$("#func").css("margin-left", (e.pageX - x) + "px")
				}
			} else {
				isSlide = false
			}
		}
	}
}

function touchend(g) {
	if (isSlide) {
		if (tX != undefined && tX.length > 1) {
			var b = parseInt(tX[0], 10);
			var f = parseInt(tX[tX.length - 1], 10);
			var d = Math.abs(b - f);
			if (tY != undefined && tY.length > 1) {
				var a = parseInt(tY[0], 10);
				var e = parseInt(tY[tY.length - 1], 10);
				var c = Math.abs(a - e);
				if (f > b) {
					$("#func").animate({
						"margin-left": "0px"
					}, 200)
				} else {
					$("#func").animate({
						"margin-left": "-298px"
					}, 200)
				}
			}
			tX = [];
			tY = []
		}
	} else {
		var h = parseInt($("#func").css("margin-left").replace("px", ""));
		if (h < -149) {
			$("#func").animate({
				"margin-left": "-298px"
			}, 200)
		} else {
			$("#func").animate({
				"margin-left": "0px"
			}, 200)
		}
		tX = [];
		tY = []
	}
}
var startPosX;
var startPosY;
var powerA;
var powerB;
var isend = false;
var cpage = 1;

function tStart(a) {
	startPosX = a.touches[0].pageX;
	startPosY = a.touches[0].pageY
}

function tMove(a) {
	var d = $("#slider").css("margin-left").replace("px", "");
	var b = Math.abs(Math.ceil(parseInt(d) / 71)) + 5;
	var j = $("#slider img");
	for (var e = 0; e < b; e++) {
		if (j.length > e && $(j[e]).attr("imgdata")) {
			$(j[e]).attr("src", $(j[e]).attr("imgdata"));
			$(j[e]).removeAttr("imgdata")
		}
	}
	if (Math.abs(a.touches[0].pageY - startPosY) < Math.abs(a.touches[0].pageX - startPosX)) {
		a.preventDefault()
	}
	var f = a.touches;
	var h = parseInt($("#slider").css("width").replace("px", ""));
	if (a.touches.length == 1) {
		if (f[0].pageX > startPosX) {
			var g = f[0].pageX - startPosX;
			var c = parseInt($("#slider").css("margin-left").replace("px", ""));
			$("#slider").css("margin-left", c + g + "px")
		} else {
			var g = f[0].pageX - startPosX;
			var c = parseInt($("#slider").css("margin-left").replace("px", ""));
			$("#slider").css("margin-left", c + g + "px")
		}
		startPosX = f[0].pageX
	}
	if (f.length > 0) {
		powerA = powerB;
		powerB = f[f.length - 1].pageX
	}
}

function tEnd(c) {
	var d = parseInt($("#slider").css("margin-left").replace("px", ""));
	var b = parseInt($("#slider").css("width").replace("px", ""));
	if (powerA && powerB && powerA > 0 && powerB > 0) {
		var a = Math.abs(powerA - powerB);
		if (a > 0) {
			$("#slider").animate({
				"margin-left": (powerA > powerB ? d - a : d + a) + "px"
			}, 200)
		}
	}
	if (d > 0) {
		setTimeout(function() {
			$("#slider").animate({
				"margin-left": "0px"
			}, 200)
		}, 200)
	}
	if (Math.abs(d) > (b - 320)) {
		if (!isend) {
			cpage += 1;
			jQuery.post("/index/getWare.json", {
				page: cpage
			}, function(h) {
				if (h && h.crazyList && h.crazyList.length > 0) {
					var f;
					var g;
					for (var e = 0; e < h.crazyList.length; e++) {
						g = h.crazyList[e];
						f = '<li class="new-tbl-cell"><a href="/product/' + g.wareId + '.html",$sid)"><img src="' + g.imageurl + '" width="70" height="70"><span>&yen;' + g.jdPrice + "</span></a></li>";
						$("#slider").append(f)
					}
				} else {
					isend = true;
					setTimeout(function() {
						$("#slider").animate({
							"margin-left": -((b - 320 + 20)) + "px"
						}, 200)
					}, 200)
				}
			}, "json")
		} else {
			setTimeout(function() {
				$("#slider").animate({
					"margin-left": -((b - 320 + 20)) + "px"
				}, 200)
			}, 200)
		}
	}
	powerA = 0;
	powerB = 0
};