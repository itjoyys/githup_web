

var LANGX = 'gb', H = '75', ajaxDoing = false, query = {};

// 防被砍
if (top.location.hostname != self.location.hostname) {
    location = '/';
}

$(function () {
    $(".password_adv").passStrength({
        userid: "#zcname",
        shortPass_txt: '密码强度：太短',
        badPass_txt: '密码强度：弱',
        goodPass_txt: '密码强度：很好',
        strongPass_txt: '密码强度：强'
    });
/*表单重置*/
    $('#myFORM').find('select[name^=pwd]').focus(function () {
        //$('.Error_MultiPwd').remove();
    }).end().find('input[name=CANCEL2]').click(function () {
        validator.resetForm();
    });
    /**/
    $.validator.addMethod('equalToUsername', function (value, element) {
        return (value == $('#zcname').val()) ? false : true;
    }, '帐号与密码不能相同');

    $.validator.addMethod('mustchar', function (value, element) {
        return (/[a-zA-Z]+/.test(value)) ? true : false;
    }, '帐号必须包括字母');

    /**/
    $.validator.addMethod('CheckPWDStrength', function (value, element) {
        return ($.fn.checkstrength(value) < 34) ? false : true;
    }, '密码强度：弱');


    /**/
    var validator = $("#myFORM").validate({
        /**/
        'onkeyup': false,
        /**/
        'focusCleanup': true,
        /**/
        'focusInvalid': false,
        /**/
        'errorElement': 'span',
        /**/
        'rules': {
            'zcpwd1': {
                'required': true,
                'minlength': 6,
                'CheckPWDStrength': true
            },
            'zcpwd2': {
                'required': true,
                'equalTo': '#zcpwd1'
            },
            'agree': { 'required': true }
        },
        /**/
        'messages': {
            'zcpwd1': {
                'required': $._BuildPrompt('zcpwd1', '✖ 请输入密码!!'),
                'minlength': $._BuildPrompt('zcpwd1', '✖ 请输入6-12个字元的密码!!', { 'width': 170 }),
                'CheckPWDStrength': $._BuildPrompt('zcpwd1', '✖ 密码强度：弱')
            },

            'zcpwd2': {
                'required': $._BuildPrompt('zcpwd2', '✖ 确认密码!!'),
                'equalTo': $._BuildPrompt('zcpwd2', '✖ 确认密码错误！请重新输入!!', { 'width': 140 })
            },
            'agree': { 'required': $._BuildPrompt('agree', '✖ 请勾选同意条款!!', { 'width': 120, 'top': -60, 'left': -28 }) 
            }
        },
        /**/
        'submitHandler': function () {
            if (ajaxDoing) {
                alert('资料验证中!');
                return false;
            }
            for (var i in query) {
                if (!query[i]['status']) {
                    return false;
                }
            }
            if (confirm("是否确定写入?")) {
                $('form input:submit').attr('disabled', 'disabled');
                document.myFORM.submit();
            }
        }
    });

    /**/

    /**/
    $('#myFORM input[name=zcpwd1]').rules('add', {
        'required': true,
        'messages': { 'required': $._BuildPrompt('zcpwd1', '✖ 请输入密码!', { 'width': 110 }) }
    });

    /**/
    $('input[name=zcpwd1], input[name=zcpwd2]').keyup(function () {
        this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
    });
    /**/
    $('#Dialog').bgiframe();
});
$.extend({
    /**/
    '_Dialog': function (Title, Data, Width, Height) {
        $('#Dialog').dialog({
            'title': Title,
            'width': Width,
            'minWidth': Width,
            'height': Height,
            'minHeight': Height,
            'modal': true,
            'bgiframe': true,
            'show': 'blind',
            'hide': 'blind'
        });
        $('#Dialog').html(Data);
    },
    /**/
    '_BuildPrompt': function (Name, PromptText, o) {
        var options = {
            'showArrow': true,
            'positionType': 'topRight',
            'width': 100,
            'top': -28,
            'left': -27,
            'opacity': 0.8,
            'AMarginLeft': 13
        };
        options = $.extend(options, o);

        if (LANGX != 'gb' && LANGX != 'big5')
            options.width += 50;

        /**/
        var prompt = $('<div>');
        prompt.addClass("FormError");
        /**/
        var promptContent = $('<div>').addClass("FormErrorC").css('width', options.width).html(PromptText).appendTo(prompt);
        /**/
        if (options.showArrow) {
            var arrow = $('<div>').addClass("FormErrorA").css('marginLeft', options.AMarginLeft);
            switch (options.positionType) {
                case "bottomLeft":
                case "bottomRight":
                    prompt.find(".FormErrorC").before(arrow);
                    arrow.addClass("FormErrorABottom").html('<div style="width:1px;border:none;background: #DDDDDD;"><!-- --></div><div style="width:3px;border:none;background:#DDDDDD;"><!-- --></div><div style="width:1px;border-left:2px solid #DDDDDD;border-right:2px solid #ddd;border-bottom:0 solid #ddd;"><!-- --></div><div style="width:3px;"><!-- --></div><div style="width:5px;"><!-- --></div><div style="width:7px;"><!-- --></div><div style="width:9px;"><!-- --></div><div style="width:11px;"><!-- --></div><div style="width:13px;border:none;"><!-- --></div><div style="width:15px;border:none;"><!-- --></div>');
                    break;
                case "topLeft":
                case "topRight":
                    arrow.html('<div style="width:15px;border:none;"><!-- --></div><div style="width:13px;border:none;"><!-- --></div><div style="width:11px;"><!-- --></div><div style="width:9px;"><!-- --></div><div style="width:7px;"><!-- --></div><div style="width:5px;"><!-- --></div><div style="width:3px;"><!-- --></div><div style="width:1px;border-left:2px solid #ddd;border-right:2px solid #ddd;border-bottom:0 solid #DDDDDD;"><!-- --></div><div style="width:3px;border:none;background:#DDDDDD;"><!-- --></div><div style="width:1px;border:none;background: #DDDDDD;"><!-- --></div>');
                    prompt.append(arrow);
                    break;
            }
        }
        /**/
        prompt.css({
            "top": options.top,
            "left": options.left,
            "opacity": options.opacity
        });
        return $('<span>').addClass("Error_" + Name).css('position', 'relative').css('vertical-align', 'top').append(prompt.css('position', 'absolute'));
    },
    /**/
    '_CheckIDCard': function (num) {
        var len = num.length, re;
        if (len == 15)
            re = new RegExp(/^(\d{6})()?(\d{2})(\d{2})(\d{2})(\d{3})$/);
        else if (len == 18)
            re = new RegExp(/^(\d{6})()?(\d{4})(\d{2})(\d{2})(\d{3})(\d)$/);
        else {
            return false;
        }
        var a = num.match(re);
        if (a != null) {
            if (len == 15) {
                var D = new Date("19" + a[3] + "/" + a[4] + "/" + a[5]);
                var B = D.getYear() == a[3] && (D.getMonth() + 1) == a[4] && D.getDate() == a[5];
            } else {
                var D = new Date(a[3] + "/" + a[4] + "/" + a[5]);
                var B = D.getFullYear() == a[3] && (D.getMonth() + 1) == a[4] && D.getDate() == a[5];
            }
            if (!B) return false;
        }
        return true;
    },
    /**/
    '_InArray': function (stringToSearch, arrayToSearch) {
        for (s = 0; s < arrayToSearch.length; s++) {
            thisEntry = arrayToSearch[s].toString();
            if (thisEntry == stringToSearch) {
                return true;
            }
        }
        return false;
    }
});

