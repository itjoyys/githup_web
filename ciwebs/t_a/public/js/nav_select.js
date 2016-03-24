$.fn.subTabs = function(options) {

    var conf = {
        "inDelay": 400,
        "outDelay": 400,
        "showTime": 300,
        "notOver": 1, //防止超出版面
        "animate": "fade",
        "alignLeft": false //子選單靠左
    };

    $.extend(conf, options);

    return this.each(function() {
        var _o = $(this);
        var tClass = _o.attr("class").split(' ')[0];
        var sub = $("div[class=" + tClass + ']');
        var targetWid = _o.width();
        var posX = _o.position().left;
        if(conf.alignLeft) {
        	var moveVal = posX - _o.parent().position().left;
        } else {
        var moveVal = (posX - (sub.width() - targetWid) / 2) - _o.parent().position().left - 8;
        }
		
        var tout, tin


        sub.css("left", moveVal);
        sub.hide();

        $("." + tClass).hover(function() {
            clearTimeout(tout);
            tin = setTimeout(function() {
                inTab();
            }, conf.inDelay);
        }, function() {
            clearTimeout(tin);
            tout = setTimeout(function() {
                outTab();
            }, conf.outDelay);
        });

        _o.bind("click", function() {
            if (sub.is(":visible")) {
                return true;
            } else {
                clickTab();
            }
        });

        function clearTab() {
            sub.parent().find("div").hide();
        }

        function inTab() {
        	if(conf.animate == "slide") {
        		sub.stop(true, true).slideDown(conf.showTime);
        	} else {
                sub.stop(true, true).fadeIn(conf.showTime);
        	}
            //position();
        }

        function outTab() {
        	if(conf.animate == "slide") {
        		sub.stop(true, true).slideUp(conf.showTime);
        	} else {
                sub.stop(true, true).fadeOut(conf.showTime);
        	}
        }
		function position() {
            var m = parseInt(conf.left) - parseInt(sub.width() / 2);
            sub.css("left", m + "px");
        }

    });
};
 $(function() {
    $('#mainnav').find("li.game-ball").subTabs({
        "animate"  : "slide",
        "showTime" : 200,
        "inDelay"  : 200,
        "notOver"  : 0,
        "outDelay" : 200,
        "posTop"   : 1
    });
});
