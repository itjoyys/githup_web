jQuery.validator.addMethod("nosymbol",
    function(A, B) {
        return this.optional(B) || !/[~`!@#\$%\^&\*\(\)_\-\+=\\\|\[\]\{\}:;"'<>\?,\.\/\s]+/.test(A)
    },
    "no symbol please");
jQuery.validator.addMethod("specialchar",
    function(A, B) {
        return this.optional(B) || !/[~`!@#\$%\^&\*\(\)_\-\+=\\\|\[\]\{\}:;"'<>\?,\.\/]+/.test(A)
    },
    "no symbol please");

function setBank(C) {
    var bankObj = {};
    var E = $("input:radio[name=bank_id]:checked").val() || null;
    if (null == E) {
        alert(TXT.MENU_COMP_S01);
        return false;
    }

    document.getElementById("bank_card").value = E;
    var D = document.getElementById("b" + E).innerHTML;
    document.getElementById("BankInfo").innerHTML = D;
    bankObj.bank_id = E;
    for (var D = document.getElementsByTagName("td"), A = 0; A < D.length; A++) {
        var B = D[A].id,
            F = B.indexOf("-");
        if (B.substring(0, F) == E) {
            "1" == C ? document.getElementById(B).style.background = "#efb2b2": document.getElementById(B).style.background = ""
        }
    }
    "1" == C ? ($tabs.tabs({
            disabled: false
        },
        {
            selected: 1
        }), $tabs.tabs({
        disabled: [0, 2, 3]
    })) : ($tabs.tabs({
            disabled: false
        },
        {
            selected: 0
        }), $tabs.tabs({
        disabled: [1, 2, 3]
    }))
}
function setDeposit(A) {
    var bankObj={};
    var C = $("input:radio[name=deposit_id]:checked").val() || null;
    if (null == C) {
        alert(TXT.MENU_COMP_S02);
        return false;
    }
    var B = document.getElementById(C).innerHTML;
    document.getElementById("DepositInfo").innerHTML = B;
    document.getElementById("bid").value = C;
    C = C.substring(C.indexOf("-") + 1);
    bankObj.deposit_id = C;
    "1" == A ? ($tabs.tabs({
            disabled: false
        },
        {
            selected: 2
        }), $tabs.tabs({
        disabled: [0, 1, 3]
    })) : ($tabs.tabs({
            disabled: false
        },
        {
            selected: 1
        }), $tabs.tabs({
        disabled: [0, 2, 3]
    }))
}
function explain(A, B) {
    window.open(A + "/app/member/pay_online2/pay_explain.php?uid=" + B, "pay_explain", "width=700,height=450,resizable=1, scrollbars=1")
}
var payData = {};
function checkInfo(C, E, D, A, B) {
    try {
        $("#dialog").dialog("close")
    } catch(e) {}
    payData = {};
    false == $("#deposit_way").valid() ? document.getElementById("deposit_way2").innerHTML = TXT.MENU_COMP_S03: document.getElementById("deposit_way2").innerHTML = "";
    if (false == $("#deposit").valid()) {
        alert(TXT.MENU_COMP_S04);
        return false;
    }
    payData.s_amount = $("#deposit_amount").val();
    payData.s_name = $("#deposit_name").val();
    payData.s_way = $("input:radio[name=deposit_way]:checked").val();
    if ("" == payData.s_amount || "" == $("#datepicker").val() || "" == payData.s_name || "" == payData.s_way) {
        alert(TXT.MENU_COMP_S05);
        return false;
    }
    var F = payData.s_amount.indexOf(".");
    if (0 < F && 2 < payData.s_amount.substring(F + 1).length) {
        alert(TXT.MENU_COMP_S06);
        return false;
    }
    if (false == !isNaN(payData.s_amount)) {
        alert(S_ONLY_NUMBER);
        return false;
    }
    if (payData.s_amount > parseFloat(A)) {
        alert(TXT.MENU_COMP_S07);
        return false;
    }
    if (payData.s_amount < parseFloat(B)) {
        alert(TXT.MENU_COMP_S29);
        return false;
    }
    "Y" == E && payData.s_amount >= D ? (E = {
        bgiframe: true,
        closeOnEscape: false,
        modal: true,
        resizable: false,
        width: 500,
        height: "auto",
        buttons: {}
    },
        E.buttons[TXT.S_OK] = function() {
            if (null == $("input:radio[name=abandon_sp_radio]:checked").val()) {
                alert(TXT.MENU_COMP_S08);
                return false;
            }
            try {
                $(this).dialog("close")
            } catch(G) {}
            clearTimeout(CloseT);
            saveInfo(C)
        },
        E.buttons[TXT.S_CLOSE] = function() {
            $(this).dialog("close")
        },
        $("#dialog").dialog(E)) : (clearTimeout(CloseT), saveInfo(C))
}
function saveInfo(A) {
    payData.uidrand = uidrand;
    payData.s_order_num = $("#order_num").val();
    payData.s_datetime = $("#datepicker").val() + " " + $("select[name=Time_Hour]").val() + ":" + $("select[name=Time_Minute]").val();
    payData.abandon_sp = $("#abandon_sp").val();
    payData.deposit_way = $("#deposit_way").val();
    payData.bank_id = $("input:radio[name=bank_id]:checked").val();
    var B = $("input:radio[name=deposit_id]:checked").val();
    payData.deposit_id = B;
    payData.id_number = $("#id_number").val();
    payData.tel = $("#tel").val();
    payData.transfer_code = $("#transfer_code").val();
    payData.atm_code = $("#atm_code").val();
    payData.bank_loc1 = $("#bank_location1").val();
    payData.bank_loc2 = $("#bank_location2").val();
    payData.bank_loc4 = $("#bank_location4").val();
    payData.transaction_id = $("#transaction_id").val();
    payData.card_number = $("#card_number").val();
    payData.note = $("#deposit textarea[name=note]").val();
    switch (payData.s_way) {
        case "0":
            payData.deposit_way = TXT.MENU_COMP_S09;
            break;
        case "1":
            payData.deposit_way = TXT.PAY_EX_COM17;
            break;
        case "2":
            payData.deposit_way = TXT.MENU_COMP_S10;
            break;
        case "3":
            payData.deposit_way = TXT.MENU_COMP_S11;
            break;
        case "4":
            payData.deposit_way = TXT.S_VOICE_TRANSFER;
            break;
        case "5":
            payData.deposit_way = TXT.S_CHEQUE_DEPOSIT;
            break;
        case "6":
            payData.deposit_way = TXT.S_CREDIT_CARD;
        case "7":
            payData.deposit_way = TXT.MENU_COMP_S30;
            break;
        case "8":
            payData.deposit_way = TXT.MENU_COMP_S31;
            break;
    }
    B = "";
    1 == payData.s_way || 2 == payData.s_way || 3 == payData.s_way ? ("" != $("#bank_location1").val() && (B += $("#bank_location1").val() + TXT.S_BANK_PROVINCE), "" != $("#bank_location2").val() && (B += $("#bank_location2").val() + $("#bank_location3").val()), "" != $("#bank_location4").val() && (B += $("#bank_location4").val())) : B = "";
    payData.bank_location = B;
    if (null != payData.s_order_num) {
        document.getElementById("s_order_num").innerHTML = payData.s_order_num
    }
    document.getElementById("s_amount").innerHTML = payData.s_amount;
    document.getElementById("s_datetime").innerHTML = payData.s_datetime;
    document.getElementById("s_name").innerHTML = payData.s_name;
    document.getElementById("s_way").value = payData.s_way;
    document.getElementById("s_deposit_way").innerHTML = payData.deposit_way;
    document.getElementById("s_bank_location").innerHTML = payData.bank_location;
    B = {
        modal: true,
        closeOnEscape: false,
        width: 500,
        buttons: {}
    };
    B.buttons[TXT.S_SUBMIT] = function() {
        $(".ui-button").attr("disabled", true);
        confirm(TXT.MENU_COMP_S12) ? $.ajax({
            url: "pay_menu_company2.php?uid=" + A,
            type: "POST",
            data: payData,
            dataType: "json",
            success: function(C) {
                switch (C.DepositMSG) {
                    case "1":
                        alert(TXT.MENU_COMP_S13);
                        break;
                    case "2":
                        if (C.DepositMIN) {
                            TXT.MENU_COMP_S27 = TXT.MENU_COMP_S27.replace("%s", C.DepositMIN)
                        }
                        alert(TXT.MENU_COMP_S27);
                        break;
                    case "3":
                        alert(TXT.MENU_COMP_S15);
                        break;
                    case "4":
                        alert(TXT.MENU_COMP_S16);
                        break;
                    case "5":
                        alert(TXT.MENU_COMP_S17);
                        break;
                    case "6":
                        alert(TXT.MENU_COMP_S18);
                        break;
                    case "7":
                        alert(TXT.S_SELECT_BANK);
                        break;
                    case "8":
                        alert(TXT.MENU_COMP_S19);
                        break;
                    case "9":
                        AutoClose();
                        break;
                    case "10":
                        alert(TXT.MENU_COMP_S20);
                        location.reload();
                        break;
                    case "11":
                        alert(TXT.S_TRAN_BUSY + C.ErrorCode)
                }
                if ("1" == C.DepositMSG || "2" == C.DepositMSG) {
                    $("#checkInfo").dialog("close"),
                        $("#d_deposit_bank").html(C.BANKINFO),
                        $("#d_amount").html($("#deposit_amount").val()),
                        $("#d_datetime").html($("#s_datetime").html()),
                        $("#d_bank").html(C.BANK),
                        $("#d_payee").html($("#s_name").html()),
                        $("#d_way").html(C.WAY),
                        $("#d_bank_location").html($("#s_bank_location").html()),
                        $("#d_order_num").html($("#order_num").val()),
                        $("#d_id_number").html($("#id_number").val()),
                        $("#d_tel").html($("#tel").val()),
                        $("#d_trans_code").html($("#transfer_code").val()),
                        $("#d_atm_code").html($("#atm_code").val()),
                        $("#d_trans_sn").html($("#transaction_id").val()),
                        $("#d_payer_card_no").html($("#card_number").val()),
                        $("#d_note").html($("#deposit textarea[name=note]").val()),
                        $tabs.tabs({
                                disabled: [0, 1, 2]
                            },
                            {
                                selected: 3
                            }),
                        $tabs.tabs({
                            disabled: [0, 1, 2]
                        })
                }
                $(".ui-button").removeAttr("disabled")
            },
            error: function(data) {}
        }) : $(this).dialog("close")
    };
    B.buttons[TXT.S_CLOSE] = function() {
        $(this).dialog("close")
    };
    $("#checkInfo").dialog(B)
}
function copyToClipboard(C) {
    if (window.clipboardData) {
        window.clipboardData.clearData(),
            window.clipboardData.setData("Text", C)
    } else {
        if ( - 1 != navigator.userAgent.indexOf("Opera")) {
            window.location = C
        } else {
            if (window.netscape) {
                try {
                    netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect")
                } catch(E) {
                    alert("\u88ab\u700f\u89bd\u5668\u62d2\u7d55\uff01\n\u8acb\u5728\u700f\u89bd\u5668\u5730\u5740\u6b04\u8f38\u5165'about:config'\u4e26\u56de\u8eca\n\u7136\u5f8c\u5c07'signed.applets.codebase_principal_support'\u8a2d\u7f6e\u70ba'true'")
                }
                var D = Components.classes["@mozilla.org/widget/clipboard;1"].createInstance(Components.interfaces.nsIClipboard);
                if (D) {
                    var A = Components.classes["@mozilla.org/widget/transferable;1"].createInstance(Components.interfaces.nsITransferable);
                    if (A) {
                        A.addDataFlavor("text/unicode");
                        var B = {},
                            B = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
                        B.data = C;
                        A.setTransferData("text/unicode", B, 2 * C.length);
                        if (!D) {
                            return false
                        }
                        D.setData(A, null, Components.interfaces.nsIClipboard.kGlobalClipboard)
                    }
                }
            }
        }
    }
}
function CopyCode(A) {
    if ($.browser.msie) {
        switch (A) {
            case 0:
                copyToClipboard($("#DepositInfo .bank").html());
                alert(TXT.MENU_COMP_S23);
                break;
            case 1:
                copyToClipboard($("#DepositInfo .payee").html());
                alert(TXT.MENU_COMP_S22);
                break;
            case 2:
                copyToClipboard($("#DepositInfo .depositbank").html());
                alert(TXT.MENU_COMP_S21);
                break;
            case 3:
                copyToClipboard($("#DepositInfo .account").html());
                alert(TXT.MENU_COMP_S24);
                break;
            case 4:
                copyToClipboard($("#OrderNum").html()),
                    alert(TXT.MENU_COMP_S25)
        }
    } else {
        alert(TXT.MENU_COMP_S26)
    }
}
function AutoClose() {
    alert(AlertText);
    self.opener = null;
    self.close()
};