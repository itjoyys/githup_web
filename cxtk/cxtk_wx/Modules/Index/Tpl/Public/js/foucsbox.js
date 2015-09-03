$(function () {
    var lmk123 = {
        com: function (con) {
            var t, imgArr = [], $lmk = $('#foucsBox'), $imgUl = $lmk.find('ul.imgCon'), $titleDiv = $lmk.find('div.showTitle'), $foucsDiv = $lmk.find('div.foucs'), $rlBtn = $lmk.find('.foucsButton'), $rBtn = $lmk.find('.rBtn'), $lBtn = $lmk.find('.lBtn'), config = {
                len: $imgUl.find('li').length,
                //自动滚动时间，默认两千毫秒（一秒等于一千毫秒）
                timeo: 2000,
                //宽，默认680px
                wid: 230,
                //高，默认380px
                hei: 317
            }, i = 0, autoChange = function () {
                $imgUl.animate({ marginLeft: '-' + i * config.wid + 'px' }, function () {
                    $foucsDiv.find('span:eq(' + i + ')').addClass('f').siblings().removeClass('f');
                    $rBtn.find('img').replaceWith(imgArr[(i === config.len - 1) ? 0 : (i + 1)]);
                    $lBtn.find('img').length !== 0 ? $lBtn.find('img:eq(0)').replaceWith(imgArr[(i === 0) ? (config.len - 1) : (i - 1)]) : $lBtn.append(imgArr[(i === 0) ? (config.len - 1) : (i - 1)]);
                    i += 1;
                    i = i === config.len ? 0 : i;
                });
            };
            $imgUl.find('img').each(function (inde, ele) {
                imgArr[inde] = new Image();
                imgArr[inde].src = $(this).attr('src');
            });
            $imgUl.css('width', config.len * config.wid);
            $foucsDiv.html(function () {
                var i, s = '';
                for (i = 0; i < config.len; i += 1) {
                    s += '<span ' + (i === 0 ? 'class="f"' : '') + '></span>';
                }
                return s;
            });
            $rBtn.find('img').replaceWith(imgArr[(i === config.len - 1) ? 0 : (i + 1)]);
            $lBtn.find('img').length !== 0 ? $lBtn.find('img:eq(0)').replaceWith(imgArr[(i === 0) ? (config.len - 1) : (i - 1)]) : $lBtn.append(imgArr[(i === 0) ? (config.len - 1) : (i - 1)]);
            t = setInterval(autoChange, config.timeo);
            $lmk.mouseenter(function () { clearInterval(t); }).mouseleave(function () { t = setInterval(autoChange, config.timeo); });
            $rlBtn.hover(function () {
                $(this).addClass('btnHover');
            }, function () {
                $(this).removeClass('btnHover');
            }).click(function () {
                i = $foucsDiv.find('span.f').index();
                if ($(this).is('.lBtn')) {
                    i = (i === 0) ? (config.len - 1) : (i - 1);
                } else {
                    i = (i === config.len - 1) ? 0 : (i + 1);
                }
                autoChange();
            });
            $foucsDiv.find('span').click(function () {
                i = $(this).index();
                autoChange();
            });
        }
    };
    //执行开始
    lmk123.com();
});