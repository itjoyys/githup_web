var pluginEffect = {};
(function ($) {
    'use strict';
    $.fn.slideshow = function (options) {
        var $this = this,
            d, settings, main;

        $.ajaxSetup({cache: true });

        d = {
            uni            : 1,
            animationType  : 'slideh',
            animationTime  : 1.5,
            animationOption: {
                row : 10,
                col : 6
            },
            isNav          : true,
            isControl      : true,
            navPosition    : 'left',
            isNumber       : true,
            isArrow        : true,
            isText         : true,
            textX          : 50,
            textY          : 10,
            textTime       : 2,
            random         : true,
            isAutoPlay     : true,
            hoverPause     : true,
            isLink         : true,
            isRunLine      : false,
            runLinePos     : 'bottom',
            fullScreen     : 0,
            fullScreenIEType: 'slideh'
        };

        settings = $.extend(d, options);

        var oldIe = /msie\s[1-8][^\d]/i.test(navigator.userAgent);
        if(oldIe && (settings.animationType === 'clip' || settings.animationType === 'align' || settings.animationType === 'falls' || settings.animationType === 'through')){
            settings.animationType = settings.fullScreenIEType;
        }

        main = {
            settings: settings,
            param : {
                slideUl: $this.find('ul'),
                slide: $this.find('.js-ele-slideshow-scroll'),
                total: $this.find('li').length,
                mainWidth: $this.width(),
                mainHeight: $this.height(),
                nowAt: 1,
                clickLock: false,
                oldIe: /msie\s[1-8][^\d]/i.test(navigator.userAgent),
                fullScreen: settings.fullScreen
            },
            init: function () {
                var _self = this;

                //若無資料則不做任何事情
                if(_self.param.total === 0) {return;}

                if (settings.isNav) {_self.setNav();}
                if (settings.isText) {_self.setText();}
                if (settings.isArrow) {_self.setArrow();}
                if (settings.isLink) {_self.setLink();}
                if (settings.isAutoPlay) {
                    _self.setAutoPlay();
                    if (settings.isRunLine && _self.param.total > 1) {_self.SetRunLine();}
                }

                //繼承特效function
                _self.initial = pluginEffect[settings.animationType].initial;
                _self.move = pluginEffect[settings.animationType].move;

                _self.moveTl = new TimelineMax({
                    onComplete: pluginEffect[settings.animationType].reSet,
                    onCompleteParams: [_self]
                });

                //初始化
                _self.initial();
                if (settings.isText) {_self.textEvent(0);}

                $(window).on('resize', function(e) {
                    e.preventDefault();
                    main.setControlPos();
                });
            },
            run: function (index, type) {
                main.clickLock = true;
                main.move(index, type);
                if (settings.isAutoPlay && settings.isRunLine) {main.runLineMove(main.autoPlayTime[index]/1000);}
                if (settings.isText) {main.textEvent(index);}
            },
            //開啟導覽列
            setNav: function () {
                var $control = $this.find('.js-ele-slideshow-control-wrap'),
                    _nav = [],
                    html = '',
                    i, active;

                for (i = 0; i < main.param.total; i+=1) {
                    active = (i === 0) ? 'class="active"' : '';
                    _nav[i] = (settings.isNumber) ?
                        '<a href="###"' + active + '>' + (i + 1) + '</a>' :
                        '<a href="###"' + active + '></a>';
                }

                html = '<div id="js-ele-slideshow-nav' + settings.uni + '" class="ele-slideshow-nav">' + _nav.join('') + '</div>';

                $control
                    .append(html)
                    .find('#js-ele-slideshow-nav' + settings.uni)
                    .on('click', 'a', function () {
                        var $a = $(this),
                            index = $a.index();

                        if (main.clickLock || index === (main.param.nowAt - 1)) {return;}
                        $a.addClass('active').siblings().removeClass('active');

                        main.param.nowAt = index + 1;
                        //重置runline
                        main.runLineTl = new TimelineMax();
                        main.run(index, 'isBtn');
                    });

                main.setControlPos();
            },
            //開始 / 停止
            setControl: function () {
                var $control,
                    html = '<div id="js-ele-slideshow-control' + settings.uni + '" class="ele-slideshow-control">'
                         + '<a href="###" id="js-ele-slideshow-start' + settings.uni + '" class="ele-slideshow-start"></a>'
                         + '<a href="###" id="js-ele-slideshow-stop' + settings.uni + '" class="ele-slideshow-stop">'
                         + '<div class="ele-slideshow-stop-left"></div>' + '<div class="ele-slideshow-stop-right"></div>'
                         + '</a>'
                         + '</div>';

                $this.find('.js-ele-slideshow-control-wrap').prepend(html);

                $control = $('#js-ele-slideshow-control' + settings.uni);

                if (settings.isAutoPlay) {
                    $control.find('#js-ele-slideshow-start' + settings.uni).hide();
                } else {
                    $control.find('#js-ele-slideshow-stop' + settings.uni).hide();
                }

                $control
                    .on('click', 'a', function (event) {
                        event.preventDefault();

                        var $a = $(this);
                        $a.hide().siblings().css('display', 'inline-block');

                        if (event.target.id === 'js-ele-slideshow-stop' + settings.uni) {
                            clearTimeout(main.autoPlay);
                            settings.isAutoPlay = false;
                        } else {
                            settings.isAutoPlay = true;
                            main.autoPlayEvent();
                        }
                    });

                main.setControlPos();
            },
            setAutoPlay: function () {
                var time = [],
                    i;

                //取得每張圖播放的時間
                for (i = 0; i <= main.param.total - 1; i+=1) {
                    time.push(main.param.slideUl.find('li').eq(i).data('autoplaytime'));
                }

                main.autoPlayTime = time;
                main.autoPlayEvent();

                //控制選項
                if (settings.isControl) {main.setControl();}

                if (!settings.hoverPause) {return;}
                //hover Pause
                $this.hover(function () {
                    clearTimeout(main.autoPlay);
                }, function () {
                    main.autoPlayEvent();
                });
            },
            autoPlayEvent: function () {
                if (!settings.isAutoPlay) {return;}

                var _timer;

                clearTimeout(main.autoPlay);

                _timer = function () {
                    main.goNext();
                    main.autoPlayEvent();
                };

                main.autoPlay = window.setTimeout(_timer, main.autoPlayTime[main.param.nowAt - 1]);
            },
            setText: function () {
                var $wrap = $this.find('.js-ele-slideshow-text-wrap'),
                    $text = $wrap.find('img'),
                    i, $t;

                $wrap.css({
                    'left': settings.textX,
                    'top': settings.textY
                });

                for (i = 0; i < main.param.total; i+=1) {
                    $t = $text.eq(i);

                    $t.data({
                        'left': $t.css('left'),
                        'top': $t.css('top')
                    });
                }

                main.textTl = new TimelineMax();
            },
            textEvent: function (index) {
                var $text = $this.find('.js-ele-slideshow-text-wrap').find('img'),
                    $textCurrent = $text.eq(index),
                    i,$t;

                main.textTl.clear();

                //隨機或是固定
                if (settings.random) {
                    for (i = 0; i < main.param.total; i+=1) {
                        if (i !== index) {
                            $t = $text.eq(i);
                            main.textTl.to($t, settings.textTime, {
                                'opacity': 0,
                                'left': $t.data('left'),
                                'top': $t.data('top')
                            }, 0);
                        }
                    }
                } else {
                     main.textTl.to($text, settings.textTime, {
                        'opacity': 0,
                        'left': $text.data('left'),
                        'top': $text.data('top')
                    }, 0);
                }

                main.textTl.to($textCurrent, settings.textTime, {
                    'opacity': 1,
                    'left': 0,
                    'top': 0
                }, 1);
            },
            setArrow: function () {
                var html, $left ,$right;

                html = '<a href="###" id="js-ele-slideshow-arrow-left' + settings.uni + '" '
                     + 'class="ele-slideshow-arrow ele-slideshow-arrow-left"><i></i></a>'
                     + '<a href="###" id="js-ele-slideshow-arrow-right' + settings.uni + '" '
                     + 'class="ele-slideshow-arrow ele-slideshow-arrow-right"><i></i>'
                     + '</a>';

                $this
                    .append('<div class="ele-slideshow-arrow-wrap">' + html + '</div>')
                    .on('click', '#js-ele-slideshow-arrow-left' + settings.uni, function () {
                        main.goNext(true);
                    })
                    .on('click', '#js-ele-slideshow-arrow-right' + settings.uni, function () {
                        main.goNext();
                    });

                $left = $('#js-ele-slideshow-arrow-left' + settings.uni);
                $right = $('#js-ele-slideshow-arrow-right' + settings.uni);
                $left.add($right).css('top',(main.param.mainHeight - $left.height()) / 2 + 'px');
            },
            goNext: function (reverse) {
                if (main.clickLock) {return;}

                if (!reverse) {
                    if (main.param.nowAt + 1 > main.param.total) {main.param.nowAt = 0; }
                    main.run(main.param.nowAt % main.param.total, 'isArrow');
                    main.setBtnCurrent(main.param.nowAt);
                    main.param.nowAt += 1;
                } else {
                    main.param.nowAt -= 1;
                    if (main.param.nowAt === 0) {main.param.nowAt = main.param.total; }
                    main.setBtnCurrent(main.param.nowAt - 1);
                    main.run(main.param.nowAt - 1, 'isArrow');
                }
            },
            setBtnCurrent: function (index) {
                $this
                    .find('.ele-slideshow-nav')
                    .find('a')
                    .eq(index)
                    .addClass('active')
                    .siblings()
                    .removeClass('active');
            },
            setControlPos: function () {
                var $control = $this.find('.js-ele-slideshow-control-wrap');
                switch (settings.navPosition) {
                case 'center':
                    $control.css(
                        'left', ($this.width() - $control.width()) / 2
                    );
                    break;
                case 'right':
                    $control.css('right', '10px');
                    break;
                case 'left':
                    $control.css('left', '10px');
                    break;
                default:
                    $control.css('left', '10px');
                }
            },
            setLink : function(){
                main.param.slide.on('click', function(e) {
                    e.preventDefault();
                    var _link = main.param.slideUl.find('li').eq(main.param.nowAt - 1).find('a')
                    if(_link.length == 0) return;
                    if (_link.attr('href') === '###') {
                        _link.trigger('click');
                        return;
                    }
                    if(_link.attr('target') != '_blank') {
                        // TPL需整個替換連結
                        if($('html').hasClass('isTpl')){
                            self.parent.location = _link.attr('href');
                        }
                        location.href = _link.attr('href');
                        return;
                    }

                    window.open(_link.attr('href'), '_blank');
                });
            },
            SetRunLine : function(){
                var $line,_pos;

                $this.append('<div id="js-runline' + settings.uni +'" class="run-line"></div>');
                $line = $('#js-runline' + settings.uni);

                _pos = (settings.runLinePos === 'top') ? 'top' : 'bottom';

                $line.css(_pos,0);

                main.runLineTl = new TimelineMax();

                main.runLineMove(main.autoPlayTime[0]/1000);
            },
            runLineMove : function(sec){
                var $line = $('#js-runline' + settings.uni);

                main.runLineTl
                    .set($line,{
                        opacity : 1
                    })
                    .fromTo($line,sec,{
                        width: 0
                    },{
                        width: main.param.mainWidth,
                        ease : Cubic.easeOut
                    });
            }
        };

        // public method
        $.slideshow = {
            next: function(){
                main.goNext();
            },
            prev: function(){
                main.goNext(true);
            },
            start: function(){
                main.autoPlayEvent();
            },
            stop: function(){
                clearTimeout(main.autoPlay);
            }
        };

        $.getScript('/cl/js/slideshow/plugin_' + settings.animationType + '.js', function () {
            $(function() {
                main.init();
            });
        });
    };
}(jQuery));