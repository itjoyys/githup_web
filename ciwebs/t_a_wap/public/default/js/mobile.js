/**
 * Created by camille on 2015/7/1.
 */
(function(window){
    "ues strict";

    var mobileVer ={
        //滾動狀態
        scrollCur: true,

        mobile: {
            ChatClose      : ["click", "#js-wechat-close"],
            Ios9Tabs       : ["click touchstart", "#js-mobile-nav .nav-btn"]
        },

        mobile_functions: {
            'ChatClose' : function() {
                var wechatAlert = $('#js-wechat');
                wechatAlert.hide();
            },

            'Ios9Tabs' : function() {
                var $mobileWrap = $('#js-' + $(this).data('target')),
                    $mobileWrapSiblings = $mobileWrap.siblings();

                $mobileWrap
                    .show()
                    .siblings().removeClass('pt-page-delay300 pt-page-scaleUp').end()
                    .addClass('pt-page-delay300 pt-page-scaleUp');

                $mobileWrapSiblings
                    .hide()
                    .siblings().removeClass('pt-page-scaleDownUp').end()
                    .addClass('pt-page-scaleDownUp');

                //滾動狀態，預設false
                mobileVer.scrollCur = false;

                //導覽列current
                $(this)
                    .siblings().removeClass('current').end()
                    .addClass('current');

            }
        },

        init: function() {
            var self = this;

            for (var key in self.mobile) {
                $(document).on(self.mobile[key][0], self.mobile[key][1], self.mobile_functions[key]);
            }

            $(function() {
                self.mobileFunction();
                //self.scrollFunction();
            });
        },

        mobileFunction: function(){
            var $window = $(window),
                _widthMedia = $window.width(),
                _heightMedia = $window.height(),
                $scrollWrapCount = $('.scroll-wrap').size(),
                ua = window.navigator.userAgent.toLowerCase(),
                wechatAlert = $('#js-wechat');

            //算出有幾個廣宣，並給總寬度
            $('.mobile-scroller').css({
                'width'  : _widthMedia*$scrollWrapCount + 'px',
                'height' : _heightMedia + 'px'
            });

            //給這些容器塞入裝置寬高
            $('.scroll-bg, .mobile-viewport').css({
                'width'  : _widthMedia + 'px',
                'height' : _heightMedia + 'px'
            });

            //給這些容器塞入裝置寬
            $('#js-mobile-explain, #js-mobile-nav').css({
                'width'  : _widthMedia + 'px'
            });

            //頁簽，外層寬度，並置中
            $('.mobile-dot-wrap').css({
                'width'  : 15*$scrollWrapCount + 'px',
                'margin-left' : '-' + (15*$scrollWrapCount)/2 + 'px'
            });

            //頁簽(iPad以上使用)，外層寬度，並置中
            if (_widthMedia >= 768) {
                $('.mobile-dot-wrap').css({
                    'width'  : 30*$scrollWrapCount + 'px',
                    'margin-left' : '-' + (30*$scrollWrapCount)/2 + 'px'
                });
                return;
            }

            //判斷微信
            if (ua.match(/MicroMessenger/i) == 'micromessenger') {
                wechatAlert.show();
                return;
            }
        },

        scrollFunction: function(){
            //iscroll 套件
            var myScroll;

            myScroll = new IScroll('.mobile-viewport', {
                scrollX: true,
                scrollY: true,
                momentum: false,
                snap: true,
                snapSpeed: 400,
                keyBindings: true,
                indicators: {
                    el: document.getElementById('js-mobile-dot-wrap'),
                    resize: false
                },
                click: true
            });
        }

    };

    mobileVer.init()
})(window);
