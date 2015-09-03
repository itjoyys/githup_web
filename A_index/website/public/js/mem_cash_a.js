

var LANGX = 'gb', H = '75', ajaxDoing = false, query = {};

// 防被砍
if (top.location.hostname != self.location.hostname) {
    location = '/';
}

$(function () {
    $(".password_adv").passStrength({
        userid: "#r_user_form",
        shortPass_txt: '密码强度：太短',
        badPass_txt: '密码强度：弱',
        goodPass_txt: '密码强度：很好',
        strongPass_txt: '密码强度：强',
        samePassword_txt: '帐号与密码不能相同'
    });

      $(".r_user_form").keyup(function () {
        var zh_pattern = /[a-z0-9]/g;
        if (!zw_pattern.test($(this).val())) {
           $(".r_user_form").val('');
        }
    })

/*表单重置*/
    $('#myFORM').find('select[name^=pwd]').focus(function () {
        //$('.Error_MultiPwd').remove();
    }).end().find('input[name=CANCEL2]').click(function () {
        validator.resetForm();
    });

    /**/
    $.validator.addMethod('ajaxCheckData', function (value, element) {
        var id = element.id;
        if ('undefined' != typeof (query[id]) && query[id].value == value) { return true; }
        var data = {
            'r_user_form': { 'ajax': 'Check_agent_user', 'user': value },
            'yzm_form': { 'ajax': 'CheckCode', 'a_idcode': value },
        };
        query[id] = {'status': false, 'value': value };
        ajaxDoing = true;
        $(element).siblings('.loading_pic').fadeIn();

        $.ajax({
            'url': './index.php?a=agent_ajax_check',
            'type': 'get',
            'data': data[id],
            'cache': false,
            'timeout': 30000,
            'error': function (e,textStatus) {
                if (textStatus == 'timeout') {
                    alert('网路品质不佳!!');
                    $(element).siblings('.loading_pic').fadeOut();
                }
            },
            'success': function (data) {
                if (data == 'block') {
                    location.reload();
                }
                var status = (data == '0') ? false : true;
                //var status = true;
                query[id].status = status;
                showError(id, status);
                ajaxDoing = false;
                $(element).siblings('.loading_pic').fadeOut();
            }
        });
        return true;
    }, '');
  $("#r_user_form").focus(function() {
			var vdefault = this.value;   //获得焦点时，如果值为默认值，则设置为空
			if(vdefault!=""){
			value=$('div').is('.FormErrorC');
               if(value) {
				   $(".FormErrorA").hide();
				   $(".FormErrorC").hide();
				   $("#r_user_form").val("");
            }			
			}
        });
    /**/
    $.validator.addMethod('CheckNameRule', function (value, element) {
        var Ch = /^[\u4e00-\u9fa5]+$/;
        var KRW = /^([\uAC00-\uD7AF])*$/gi;
        var En = /^([a-zA-Z]+)$/;
        // 韓幣特例
        var currency = 'RMB'; // $('#myFORM [name=currency]').val()
        if (currency == 'KRW') {
            return this.optional(element) || (Ch.test(value)) || (En.test(value)) || (KRW.test(value));
        } else {
            return this.optional(element) || (Ch.test(value)) || (En.test(value));
        }
    }, '取款密码!!');

    $.validator.addMethod('CheckChinaName', function (value, element) {
        var Ch = /^[\u4e00-\u9fa5]+$/;
        return  Ch.test(value);
    }, '取款密码!!');

    // $.validator.addMethod('CheckPWD', function (value, element) {
    //     var i = 0;
    //     $('select[name^=safe_pass]').each(function () {
    //         if ($(this).val() == '-') {
    //             $(this).addClass('err');
    //             i++;
    //         } else {
    //             $(this).removeClass('err');
    //         }
    //     });
    //     return ((i == 0) ? true : false);
    // }, '请设定取款密码!!');


    /**/
    $.validator.addMethod('equalToUsername', function (value, element) {
        return (value == $('#r_user_form').val()) ? false : true;
    }, '帐号与密码不能相同');

    $.validator.addMethod('mustchar', function (value, element) {
        return (/[a-z]+/.test(value)) ? true : false;
    }, '帐号必须包括字母');

    /**/
    $.validator.addMethod('CheckPWDStrength', function (value, element) {
        return ($.fn.checkstrength(value) < 34) ? false : true;
    }, '密码强度：弱');

    /**/
    $.validator.addMethod('CheckIdopt', function (value, element) {
        if ($('select[name=idopt]').val() == '')
            $('select[name=idopt]').focus();
        return ($('select[name=idopt]').val() != '') ? true : false;
    }, '请选取身份证或护照选项！');

    /**/
    $.validator.addMethod('CheckrmNum', function (value, element) {
        return value != '请点击' && value;
    }, '验证码请务必输入!!');

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
            'r_user_form': {
                'required': true,
                'minlength': 4,
                'mustchar':true,
                'ajaxCheckData': true
            },
            'zcturename': {
                'required': true,
             
                'ajaxCheckData': true
            },
            'password_form': {
                'required': true,
                'minlength': 6,
                'equalToUsername': true,
                'CheckPWDStrength': true
            },
            'passwd_form': {
                'required': true,
                'equalTo': '#password_form'
            },
            'bank_account_form': { 'required': true }
            ,
            'bank_province_form': { 'required': true }
            ,
            'bank_county_form': { 'required': true }
            ,
            'agree': { 'required': true }
            ,
             'safe_pass1': { 'required': true }
            ,
             'safe_pass2': { 'required': true }
            ,
             'safe_pass3': { 'required': true }
              ,
             'safe_pass4': { 'required': true }
            ,
            'yzm_form': { 'required': true,'ajaxCheckData': true}
        },
        /**/
        'messages': {
            'r_user_form': {
                'required': $._BuildPrompt('r_user_form', '✖ 请输入帐号!!'),
                'mustchar': $._BuildPrompt('r_user_form', '✖ 帐号：账号必须包括英文字母!!', { 'width': 200 }),
                'minlength': $._BuildPrompt('r_user_form', '✖ 帐号：请输入4-11个字元, 仅可输入英文字母以及数字的组合!!', { 'width': 360 }),
                'ajaxCheckData': $._BuildPrompt('r_user_form', '✖ 此帐号已经有人使用了！', { 'width': 160 })
            },
            'password_form': {
                'required': $._BuildPrompt('password_form', '✖ 请输入密码!!'),
                'minlength': $._BuildPrompt('password_form', '✖ 请输入6-12个字元的密码!!', { 'width': 170 }),
                'equalToUsername': $._BuildPrompt('password_form', '✖ 帐号与密码不能相同!!', { 'width': 170 }),
                'CheckPWDStrength': $._BuildPrompt('password_form', '✖ 密码强度：弱')
            },

            'passwd_form': {
                'required': $._BuildPrompt('passwd_form', '✖ 确认密码!!'),
                'equalTo': $._BuildPrompt('passwd_form', '✖ 确认密码错误！请重新输入!!', { 'width': 140 })
            },
            'is_zh_name': {
                'required': $._BuildPrompt('is_zh_name', '✖ 请输入你的中文昵称!!', { 'width': 120 })
            },
            'is_email': {
                'required': $._BuildPrompt('is_email', '✖ 请输入你的邮箱!!', { 'width': 120 })
            },
            'is_payname': {
                'required': $._BuildPrompt('is_payname', '✖ 请输入你的姓名!!', { 'width': 120 })
            },
            'is_qq': {
                'required': $._BuildPrompt('is_qq', '✖ 请输入你的QQ!!', { 'width': 120 })
            },
            'is_en_name': {
                'required': $._BuildPrompt('is_en_name', '✖ 请输入你的英文昵称!!', { 'width': 140 })
            },
            'is_phone': {
                'required': $._BuildPrompt('is_phone', '✖ 请输入你的手机号!!', { 'width': 120 })
            },
            'from_url_form': {
                'required': $._BuildPrompt('from_url_form', '✖ 请输入你推广网址!!', { 'width': 120 })
            },
            'other_method_form': {
                'required': $._BuildPrompt('other_method_form', '✖ 请输入其它方式!!', { 'width': 120 })
            },
            'is_card': {
                'required': $._BuildPrompt('is_card', '✖ 请输入身份证信息!!', { 'width': 120 })
            },
            'safe_pass1': {
                'required': $._BuildPrompt('safe_pass1', '✖ 请设定密码!!', { 'width': 120 })
            },
             'safe_pass2': {
                'required': $._BuildPrompt('safe_pass2', '✖ 请设定密码!!', { 'width': 120 })
            },
             'safe_pass3': {
                'required': $._BuildPrompt('safe_pass3', '✖ 请设定密码!!', { 'width': 120 })
            },
             'safe_pass4': {
                'required': $._BuildPrompt('safe_pass4', '✖ 请设定密码!!', { 'width': 120 })
            },
            'bank_account_form': {
                'required': $._BuildPrompt('bank_account_form', '✖ 请输入银行账号!!', { 'width': 120 })
            },
            'bank_province_form': {
                'required': $._BuildPrompt('bank_province_form', '✖ 请输入银行省份!!', { 'width': 120 })
            },
            'bank_county_form': {
                'required': $._BuildPrompt('bank_county_form', '✖ 请输入银行县市!!', { 'width': 120 })
            },
            'agree': { 'required': $._BuildPrompt('agree', '✖ 请勾选同意条款!!', { 'width': 120, 'top': -60, 'left': -28 }) 
            },
            'yzm_form': { 'required': $._BuildPrompt('yzm_form', '✖ 请输入验证码!!', { 'width': 120 }),
                'ajaxCheckData': $._BuildPrompt('yzm_form', '✖ 验证码错误！', { 'width': 90 }) 
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
    $('#myFORM input[name=is_payname]').rules('add', {
        'CheckChinaName': true,
        'messages': { 'CheckChinaName': $._BuildPrompt('is_payname', '✖ 请输入全中文!!', { 'width': 110 }) }
    });

    /**/
    $('#myFORM input[name=email]').rules('add', {
        'required': false,
        'messages': { 'required': $._BuildPrompt('email', '✖ 请输入有效的邮箱！', { 'positionType': 'bottomLeft', 'width': 130, 'left': -50, 'top': 10 }) }
    });

    /**/
    $('#myFORM input[name=email]').rules('add', {
        'email': false,
        'messages': { 'email': $._BuildPrompt('email', '✖ E-mail格式不正确!!', { 'positionType': 'bottomLeft', 'width': 120, 'left': -50, 'top': 10 }) }
    });

    /**/
    // $('#myFORM').find('select[name^=pwd]').focus(function () {
    //     $('.Error_MultiPwd').remove();
    //     /**/
    // }).end().
    /*$('#CANCEL2').click(function () {
        validator.resetForm();
    });*/
    // 重置表单


    //$('input[name=tel], input[name=rmNum]').keyup(function () {
    $('input[name=tel]').keyup(function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    }).focus(function () {
        $(this).blur(function () { this.value = (/[^0-9]/g.test(this.value)) ? '' : this.value; });
    });

    /**/
    $('input[name=username], input[name=idcode], input[name=agName], input[name=Intr]').keyup(function () {
        this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
    }).focus(function () {
        $(this).blur(function () { this.value = (/[^a-zA-Z0-9]/g.test(this.value)) ? '' : this.value; });
    });

    /**/
    $('input[name=password], input[name=passwd]').keyup(function () {
        this.value = this.value.replace(/[^a-z0-9]/g, '');
    });

    /**/
    $('input[name=email]').keyup(function () {
        this.value = this.value.replace(/[^\x00-\xff]/g, '');
    }).focus(function () {
        $(this).blur(function () { this.value = (/[^\x00-\xff]/g.test(this.value)) ? '' : this.value; });
    });

    $('#r_user_form,#is_payname,#yzm_form').focus(function () {
        $('#r_user_form,#is_payname,#yzm_form').focus(function () {
            showError(this.id, true);
        }).blur(function () {
            var id = this.id;
            if ('undefined' != typeof (query[id]) && !query[id].status && !ajaxDoing && $('.Error_' + id + ':visible').length == 0) {
                showError(id, false);
            }
        });
    });
});

function showError(name, status) {
    var errMes = $('.Error_ajax_' + name);
    if (status) {
        errMes.hide();
    } else {
        if (errMes.length > 0) {
            errMes.show();
            return;
        }
        switch (name) {
            case 'r_user_form':
                $('#r_user_form').after($._BuildPrompt('ajax_r_user_form', '✖ 此帐号已经有人使用了！', { 'width': 160 }));
                break;
            case 'yzm_form':
                $('#yzm_form').after($._BuildPrompt('ajax_yzm_form', '✖ 验证码错误!', { 'width': 90 }));
                break;
        }
    }
}

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
                    arrow.addClass("FormErrorABottom").html('<div style="width:1px;border:none;background: #DDDDDD;"><!-- --></div><div style="width:3px;border:none;background:#DDDDDD;"><!-- --></div><div style="width:1px;border-left:2px solid #DDDDDD;border-right:2px solid #ddd;border-bottom:0 solid #ddd;"><!-- --></div><div style="width:3px;"><!-- --></div><div style="width:5px;"><!-- --></div><div style="width:7px;"><!-- --></div><div style="width:9px;"><!-- --></div><div style="width:11px;"><!-- --></div><div id="sanjiao" style="width:13px;border:none;"><!-- --></div><div style="width:15px;border:none;"><!-- --></div>');
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

