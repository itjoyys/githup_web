$.fn.subTabs = function(options) {
    var conf = {
        "inDelay": 400,
        "outDelay": 600,
        "showTime": 300,
        "notOver": 1
    };
    $.extend(conf, options);
    return this.each(function() {
        var _o = $(this);
        var tClass = _o.attr("class").split(" ")[0];
        var sub = $("div[class=" + tClass + "]");
        var targetWid = _o.width();
        var posX = _o.position().left;
        var moveVal = (posX - (sub.width() - targetWid) / 2) - _o.parent().position().left;
        var tout, tin;
        $(this).find("a").removeAttr("title");
        sub.find("a").removeAttr("title");

        if (moveVal < 0 && conf.notOver == 1) {
            moveVal = 0
        }

        if (conf.left != undefined) {
            moveVal = parseInt(conf.left) - parseInt(sub.width() / 2)
        }
        if (conf.posTop) {
            sub.css("top", conf.posTop)
        }
        sub.css("left", moveVal);
        sub.hide();

        $("." + tClass).hover(function() {
            clearTimeout(tout);
            tin = setTimeout(function() {
                inTab()
            },
            conf.inDelay)
        },
        function() {
            clearTimeout(tin);
            tout = setTimeout(function() {
                outTab()
            },
            conf.outDelay)
        });
        _o.bind("click",function() {
            if (sub.is(":visible")) {
                return false
            } else {
                clickTab()
            }
        });
        function clearTab() {
            sub.parent().find("div").hide()
        }
        function inTab() {
            sub.stop(true, true).fadeIn(conf.showTime)
        }
        function outTab() {
            sub.stop(true, true).fadeOut(conf.showTime)
        }
        function clickTab() {
            clearTab();
            sub.stop(true, true).fadeIn(conf.showTime)
        }
        function position() {
            var m = parseInt(conf.left) - parseInt(sub.width() / 2);
            sub.css("left", m + "px")
        }
    })
};