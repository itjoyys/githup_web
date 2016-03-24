angular.module("PKMain", ["angular.filter", "ionic", "ngCordova", "ngCookies", "components.localStorage", "components.md5", "components.curActionIcon", "app.config", "app.theme", "app.language", "services.common.utils", "services.common.store", "services.common.constants", "services.common.auth", "services.common.dialog", "services.common.popover", "services.common.goback", "services.common.country", "services.register", "services.notice", "services.account", "services.report", "services.cash", "services.bank", "controllers.idx", "controllers.account", "controllers.notice", "controllers.report", "controllers.cash", "controllers.register", "controllers.bank", "filters.app", "directives.tabs", "directives.actionIcon", "directives.resetfield", "directives.lock"]).run(["$ionicPlatform", "$state", "$rootScope", "$filter", "$templateCache", "$ionicHistory", "AppConfig", "ThemeConfig", "AppLanguage", "AuthService", "QuickDialog", "BackService", function(e, t, a, o, n, r, i, s, l, m, c, u) {
    e.ready(function() {}
    ),
    m.init(),
    i.clearConfigCache(),
    l.clearLoaded(),
    a.$on("event:auth-loginRequired", function() {
        m.resetCache(),
        t.go("login")
    }
    ),
    a.$on("event:sys-maintenance", function(e, a, o) {
        t.go("maintain", {
            start: a,
            end: o
        })
    }
    ),
    a.$on("$stateChangeStart", function(e, a, n, r) {
        c.loading();
        var u = function(e) {
            0 != e.ErrorCode ? t.go("error") : (s.setDefault(),
            l.setDefault().then(function(e) {
                i.setAppTitle(e),
                s.loadTemple(function() {
                    t.go(a.name, n)
                }
                )
            }
            ))
        }
          , d = function() {
            t.go("error")
        }
        ;
        "error" != a.name && "maintain" != a.name ? i.getData() ? n.template = i.getTemplate() : (e.preventDefault(),
        i.getGlobal().then(u, d)) : 0 == l.getLoaded() && (e.preventDefault(),
        l.setDefault().then(function() {
            t.go(a.name, n)
        }
        )),
        a.authenticate && !m.isLoggedIn() ? (t.go("login", n),
        e.preventDefault()) : a.testDeny && m.isLoggedIn() && 2 == m.currentUser.TestState && (e.preventDefault(),
        c.tips(o("translate")("Member_TipsTrialUserLock"), null , function() {
            "" == r.name && t.go("index")
        }
        ))
    }
    ),
    a.$on("$stateChangeSuccess", function() {
        c.hideLoading()
    }
    ),
    a.$on("$stateChangeError", function(e) {
        e.preventDefault(),
        c.hideLoading()
    }
    ),
    a.pageRefresh = function() {
        t.reload(),
        a.$broadcast("scroll.refreshComplete")
    }
    ,
    a.historyBack = function() {
        u.goBack()
    }
    ,
    a.ionicBack = function() {
        r.backView() ? r.goBack() : t.go("index")
    }
    ,
    a.CDNURL = i.CDNURL
}
]).config(["$stateProvider", "$urlRouterProvider", "$ionicConfigProvider","$sceDelegateProvider", function(e, t, a,d) {
    a.tabs.position("bottom"),
    a.navBar.alignTitle("center"),
    a.views.transition("none"),
    d.resourceUrlWhitelist([
     'self']),
    e.state("index", {
        url: "/index",
        controller: "IndexCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/index.html"
        },
        authenticate: !1
    }).state("login", {
        url: "/login",
        controller: "LoginCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/account/login.html"
        },
        authenticate: !1
    }).state("appgame", {
        url: "/appgame/:name",
        controller: "AppInfoCtrl",
        templateUrl: "/templates/common/appinfo.html",
        //templateUrl: "/voide/bbin",
        authenticate: !1
    }).state("reg", {
        url: "/reg/:code",
        controller: "RegCtrl",
        templateUrl: function(e) {
            var step = 2;
            if(parseInt(e.code) == 1) step = 1 ;
            return "/templates/" + e.template + "/register/register"+step+".html"
        },
        authenticate: !1
    }).state("shiwanreg", {
        url: "/shiwanreg/",
        controller: "ShiWanReg",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/register/shiwanregister.html"
        },
        authenticate: !1
    }).state("fgtpwd", {
        url: "/fgtpwd",
        controller: "FgtPwdCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/account/fgt-pwd.html"
        },
        authenticate: !1
    }).state("center", {
        url: "/center",
        controller: "CenterCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/account/center.html"
        },
        authenticate: !0
    }).state("info", {
        url: "/info",
        controller: "InfoCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/account/info.html"
        },
        authenticate: !0,
        testDeny: !0
    }).state("mdfpwd", {
        url: "/mdfpwd",
        controller: "MdfPwdCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/account/mdf-pwd.html"
        },
        authenticate: !0,
        testDeny: !0
    }).state("mdfinfo", {
        url: "/mdfinfo/:type/:data",
        controller: "MdfInfoCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/account/mdf-info.html"
        },
        authenticate: !0,
        testDeny: !0
    }).state("notice", {
        url: "/notice",
        controller: "NoticeCtrl",
        params: {
            type: 0
        },
        templateUrl: function(e) {
            return "/templates/" + e.template + "/notice/notice-list.html"
        },
        authenticate: !0
    }).state("egame", {
        url: "/egame",
        controller: "EGameCtrl",
        params: {
            type: 0
        },
        templateUrl: function(e) {
            return "/templates/" + e.template + "/egame.html"
        },
        authenticate: !0
    }).state("noticeDetail", {
        url: "/notice/detail",
        controller: "NoticeDetailCtrl",
        params: {
            id: 0,
            Content: "",
            StartDateTime: ""
        },
        templateUrl: function(e) {
            return "/templates/" + e.template + "/notice/notice-detail.html"
        },
        authenticate: !0
    }).state("internal-mail-detail", {
        url: "/internal-mail/detail",
        controller: "InternalMailDetailCtrl",
        params: {
            MessID: 0,
            sendType: 0
        },
        templateUrl: function(e) {
            return "/templates/" + e.template + "/notice/internal-mail-detail.html"
        },
        authenticate: !0,
        testDeny: !0
    }).state("noticeadd", {
        url: "/noticeadd",
        controller: "NoticeAddCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/notice/add.html"
        },
        authenticate: !0,
        testDeny: !0
    }).state("deposit", {
        url: "/deposit/:type",
        controller: "DepositCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/cash/deposit.html"
        },
        authenticate: !0,
        testDeny: !0
    }).state("withdraw", {
        url: "/withdraw",
        controller: "WithdrawCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/cash/withdraw.html"
        },
        authenticate: !0,
        testDeny: !0
    }).state("trans", {
        url: "/trans",
        controller: "TransCtrl",
        params: {
            type: 0
        },
        templateUrl: function(e) {
            return "/templates/" + e.template + "/cash/trans.html"
        },
        authenticate: !0,
        testDeny: !0
    }).state("transfer", {
        url: "/transfer",
        controller: "TransferCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/cash/transfer.html"
        },
        authenticate: !0,
        testDeny: !0
    }).state("quickTransfer", {
        url: "/quickTransfer",
        controller: "QuickTransferCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/cash/quickTransfer.html"
        },
        authenticate: !0
    }).state("bank", {
        url: "/bank",
        controller: "BankCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/bank/list.html"
        },
        authenticate: !0,
        testDeny: !0
    }).state("mdfbank", {
        url: "/mdfbank/:id",
        controller: "MdfBankCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/bank/mdf.html"
        },
        authenticate: !0,
        testDeny: !0
    }).state("week", {
        url: "/week",
        controller: "WeekCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/report/week.html"
        },
        authenticate: !0
    }).state("day", {
        url: "/day/:b/:id",
        controller: "DayCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/report/day.html"
        },
        authenticate: !0
    }).state("rptgame", {
        url: "/rptgame",
        controller: "RptGameCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/report/game.html"
        },
        authenticate: !0,
        testDeny: !0
    }).state("rptdetail", {
        url: "/rptdetail",
        controller: "RptDetailCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/report/detail.html"
        },
        authenticate: !0
    }).state("rptlist", {
        url: "/rptlist",
        controller: "RptBetListCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/report/betlist.html"
        },
        authenticate: !0
    }).state("ext", {
        url: "/p/:code",
        controller: "ExtCtrl",
        templateUrl: function(e) {
            return "/templates/" + e.template + "/ext.html"
        },
        authenticate: !1,
        testDeny: !1
    }).state("try", {
        url: "/try/:gameClassId/:gameId",
        controller: "TryGameCtrl",
        templateUrl: "/templates/common/inGame.html",
        authenticate: !1,
        testDeny: !1
    }).state("maintain", {
        url: "/maintain/:start/:end",
        controller: "MaintainCtrl",
        templateUrl: "/templates/common/maintain.html",
        authenticate: !1
    }).state("ingame", {
        url: "/ingame/:gameClassId/:gameId",
        templateUrl: "/templates/common/inGame.html",
        controller: "InGameCtrl",
        authenticate: !1
    }).state("inegame", {
        url: "/inegame/:gameId/:gameType",
        controller: "IneGameCtrl",
        templateUrl: "/templates/common/ineGame.html",
        authenticate: !1,
        testDeny: !1
    }).state("inappgame", {
        url: "/inappgame/:dascode",
        templateUrl: "/templates/common/inGame.html",
        controller: "InGameAppCtrl",
        authenticate: !1
    }).state("pay", {
        url: "/pay",
        templateUrl: "/templates/common/thirdpay.html",
        controller: "ThirdPayRedirectCtrl",
        authenticate: !1,
        testDeny: !0
    }).state("error", {
        url: "/error",
        controller: "ErrorCtrl",
        templateUrl: "/templates/common/error.html",
        authenticate: !1
    }),
    t.otherwise(function(e) {
        var t = e.get("$state");
        t.go("index")
    }
    )
}
]),
angular.module("components.localStorage", []).factory("$cordovaLocalStorage", ["$cordovaCookieStorage", function(e) {
    var t = {
        key: function(e) {
            return window.localStorage.key(e)
        },
        setItem: function(e, t) {
            return window.localStorage.setItem(e, t)
        },
        getItem: function(e) {
            return window.localStorage.getItem(e)
        },
        removeItem: function(e) {
            return window.localStorage.removeItem(e)
        },
        clear: function() {
            return window.localStorage.clear()
        }
    };
    try {
        return null  !== window.localStorage ? (window.localStorage.setItem("testkey", "foo"),
        window.localStorage.removeItem("testkey"),
        t) : e
    } catch (a) {
        return e
    }
}
]).factory("$cordovaCookieStorage", ["$cookies", function(e) {
    return {
        key: function(t) {
            return e[t]
        },
        setItem: function(t, a) {
            e[t] = a
        },
        getItem: function(t) {
            var a = e[t];
            return a
        },
        removeItem: function(t) {
            return delete e[t]
        },
        clear: function() {
            for (var t in e)
                delete e[key];
            return !0
        }
    }
}
]),
/**
 * @license HTTP Auth Interceptor Module for AngularJS
 * (c) 2012 Witold Szczerba
 * License: MIT
 *
 * This file was altered from the original version on line 62 to 65
 */
function() {
    "use strict";
    angular.module("components.http-auth-interceptor", ["http-auth-interceptor-buffer"]).factory("AuthInterceptor", ["$rootScope", "httpBuffer", function(e, t) {
        return {
            loginConfirmed: function(a, o) {
                var n = o || function(e) {
                    return e
                }
                ;
                e.$broadcast("event:auth-loginConfirmed", a),
                t.retryAll(n)
            },
            loginCancelled: function(a, o) {
                t.rejectAll(o),
                e.$broadcast("event:auth-loginCancelled", a)
            }
        }
    }
    ]).config(["$httpProvider", function(e) {
        var t = ["$rootScope", "$q", "httpBuffer", function(e, t, a) {
            function o(e) {
                return e
            }
            function n(o) {
                if (401 === o.status && !o.config.ignoreAuthModule) {
                    var n = t.defer();
                    return a.append(o.config, n),
                    e.$broadcast("event:auth-loginRequired", o),
                    n.promise
                }
                if (0 === o.status) {
                    var n = t.defer();
                    return e.$broadcast("event:app-networkRequired", o),
                    n.promise
                }
                return t.reject(o)
            }
            return function(e) {
                return e.then(o, n)
            }
        }
        ];
        e.interceptors.push(t)
    }
    ]),
    angular.module("http-auth-interceptor-buffer", []).factory("httpBuffer", ["$injector", function(e) {
        function t(t, o) {
            function n(e) {
                o.resolve(e)
            }
            function r(e) {
                o.reject(e)
            }
            a = a || e.get("$http"),
            a(t).then(n, r)
        }
        var a, o = [];
        return {
            append: function(e, t) {
                o.push({
                    config: e,
                    deferred: t
                })
            },
            rejectAll: function(e) {
                if (e)
                    for (var t = 0; t < o.length; ++t)
                        o[t].deferred.reject(e);
                o = []
            },
            retryAll: function(e) {
                for (var a = 0; a < o.length; ++a)
                    t(e(o[a].config), o[a].deferred);
                o = []
            }
        }
    }
    ])
}
(),
angular.module("components.md5", []).factory("md5", function() {
    return !function(e) {
        function t(e) {
            for (var t, a, o = t = "", n = a = 0, r = 0, i = e.length; i > r; r++) {
                var s = e.charCodeAt(r);
                128 > s ? a++ : (t = 2048 > s ? String.fromCharCode(s >> 6 | 192, 63 & s | 128) : String.fromCharCode(s >> 12 | 224, s >> 6 & 63 | 128, 63 & s | 128),
                a > n && (o += e.slice(n, a)),
                o += t,
                n = a = r + 1)
            }
            return a > n && (o += e.slice(n, i)),
            o
        }
        function a(e) {
            var t, a;
            if (e += "",
            _ = !1,
            g = f = e.length,
            f > 63) {
                for (o(e.substring(0, 64)),
                i(u),
                _ = !0,
                t = 128; f >= t; t += 64)
                    o(e.substring(t - 64, t)),
                    s(u);
                e = e.substring(t - 64),
                f = e.length
            }
            for (c[0] = 0,
            c[1] = 0,
            c[2] = 0,
            c[3] = 0,
            c[4] = 0,
            c[5] = 0,
            c[6] = 0,
            c[7] = 0,
            c[8] = 0,
            c[9] = 0,
            c[10] = 0,
            c[11] = 0,
            c[12] = 0,
            c[13] = 0,
            c[14] = 0,
            c[15] = 0,
            t = 0; f > t; t++)
                a = 3 & t,
                0 === a ? c[t >> 2] = e.charCodeAt(t) : c[t >> 2] |= e.charCodeAt(t) << p[a];
            return c[t >> 2] |= d[3 & t],
            t > 55 ? (_ ? s(c) : (i(c),
            _ = !0),
            s([0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, g << 3, 0])) : (c[14] = g << 3,
            void (_ ? s(c) : i(c)))
        }
        function o(e) {
            for (var t = 16; t--; ) {
                var a = t << 2;
                u[t] = e.charCodeAt(a) + (e.charCodeAt(a + 1) << 8) + (e.charCodeAt(a + 2) << 16) + (e.charCodeAt(a + 3) << 24)
            }
        }
        function n(e, o, n) {
            a(o ? e : t(e));
            var r = C[0];
            return m[1] = b[15 & r],
            m[0] = b[15 & (r >>= 4)],
            m[3] = b[15 & (r >>= 4)],
            m[2] = b[15 & (r >>= 4)],
            m[5] = b[15 & (r >>= 4)],
            m[4] = b[15 & (r >>= 4)],
            m[7] = b[15 & (r >>= 4)],
            m[6] = b[15 & (r >>= 4)],
            r = C[1],
            m[9] = b[15 & r],
            m[8] = b[15 & (r >>= 4)],
            m[11] = b[15 & (r >>= 4)],
            m[10] = b[15 & (r >>= 4)],
            m[13] = b[15 & (r >>= 4)],
            m[12] = b[15 & (r >>= 4)],
            m[15] = b[15 & (r >>= 4)],
            m[14] = b[15 & (r >>= 4)],
            r = C[2],
            m[17] = b[15 & r],
            m[16] = b[15 & (r >>= 4)],
            m[19] = b[15 & (r >>= 4)],
            m[18] = b[15 & (r >>= 4)],
            m[21] = b[15 & (r >>= 4)],
            m[20] = b[15 & (r >>= 4)],
            m[23] = b[15 & (r >>= 4)],
            m[22] = b[15 & (r >>= 4)],
            r = C[3],
            m[25] = b[15 & r],
            m[24] = b[15 & (r >>= 4)],
            m[27] = b[15 & (r >>= 4)],
            m[26] = b[15 & (r >>= 4)],
            m[29] = b[15 & (r >>= 4)],
            m[28] = b[15 & (r >>= 4)],
            m[31] = b[15 & (r >>= 4)],
            m[30] = b[15 & (r >>= 4)],
            n ? m : m.join("")
        }
        function r(e, t, a, o, n, r, i) {
            return t += e + o + i,
            (t << n | t >>> r) + a << 0
        }
        function i(e) {
            l(0, 0, 0, 0, e),
            C[0] = h[0] + 1732584193 << 0,
            C[1] = h[1] - 271733879 << 0,
            C[2] = h[2] - 1732584194 << 0,
            C[3] = h[3] + 271733878 << 0
        }
        function s(e) {
            l(C[0], C[1], C[2], C[3], e),
            C[0] = h[0] + C[0] << 0,
            C[1] = h[1] + C[1] << 0,
            C[2] = h[2] + C[2] << 0,
            C[3] = h[3] + C[3] << 0
        }
        function l(e, t, a, o, n) {
            var i, s;
            _ ? (e = r((a ^ o) & t ^ o, e, t, n[0], 7, 25, -680876936),
            o = r((t ^ a) & e ^ a, o, e, n[1], 12, 20, -389564586),
            a = r((e ^ t) & o ^ t, a, o, n[2], 17, 15, 606105819),
            t = r((o ^ e) & a ^ e, t, a, n[3], 22, 10, -1044525330)) : (e = n[0] - 680876937,
            e = (e << 7 | e >>> 25) - 271733879 << 0,
            o = n[1] - 117830708 + (2004318071 & e ^ -1732584194),
            o = (o << 12 | o >>> 20) + e << 0,
            a = n[2] - 1126478375 + ((-271733879 ^ e) & o ^ -271733879),
            a = (a << 17 | a >>> 15) + o << 0,
            t = n[3] - 1316259209 + ((o ^ e) & a ^ e),
            t = (t << 22 | t >>> 10) + a << 0),
            e = r((a ^ o) & t ^ o, e, t, n[4], 7, 25, -176418897),
            o = r((t ^ a) & e ^ a, o, e, n[5], 12, 20, 1200080426),
            a = r((e ^ t) & o ^ t, a, o, n[6], 17, 15, -1473231341),
            t = r((o ^ e) & a ^ e, t, a, n[7], 22, 10, -45705983),
            e = r((a ^ o) & t ^ o, e, t, n[8], 7, 25, 1770035416),
            o = r((t ^ a) & e ^ a, o, e, n[9], 12, 20, -1958414417),
            a = r((e ^ t) & o ^ t, a, o, n[10], 17, 15, -42063),
            t = r((o ^ e) & a ^ e, t, a, n[11], 22, 10, -1990404162),
            e = r((a ^ o) & t ^ o, e, t, n[12], 7, 25, 1804603682),
            o = r((t ^ a) & e ^ a, o, e, n[13], 12, 20, -40341101),
            a = r((e ^ t) & o ^ t, a, o, n[14], 17, 15, -1502002290),
            t = r((o ^ e) & a ^ e, t, a, n[15], 22, 10, 1236535329),
            e = r((t ^ a) & o ^ a, e, t, n[1], 5, 27, -165796510),
            o = r((e ^ t) & a ^ t, o, e, n[6], 9, 23, -1069501632),
            a = r((o ^ e) & t ^ e, a, o, n[11], 14, 18, 643717713),
            t = r((a ^ o) & e ^ o, t, a, n[0], 20, 12, -373897302),
            e = r((t ^ a) & o ^ a, e, t, n[5], 5, 27, -701558691),
            o = r((e ^ t) & a ^ t, o, e, n[10], 9, 23, 38016083),
            a = r((o ^ e) & t ^ e, a, o, n[15], 14, 18, -660478335),
            t = r((a ^ o) & e ^ o, t, a, n[4], 20, 12, -405537848),
            e = r((t ^ a) & o ^ a, e, t, n[9], 5, 27, 568446438),
            o = r((e ^ t) & a ^ t, o, e, n[14], 9, 23, -1019803690),
            a = r((o ^ e) & t ^ e, a, o, n[3], 14, 18, -187363961),
            t = r((a ^ o) & e ^ o, t, a, n[8], 20, 12, 1163531501),
            e = r((t ^ a) & o ^ a, e, t, n[13], 5, 27, -1444681467),
            o = r((e ^ t) & a ^ t, o, e, n[2], 9, 23, -51403784),
            a = r((o ^ e) & t ^ e, a, o, n[7], 14, 18, 1735328473),
            t = r((a ^ o) & e ^ o, t, a, n[12], 20, 12, -1926607734),
            i = t ^ a,
            e = r(i ^ o, e, t, n[5], 4, 28, -378558),
            o = r(i ^ e, o, e, n[8], 11, 21, -2022574463),
            s = o ^ e,
            a = r(s ^ t, a, o, n[11], 16, 16, 1839030562),
            t = r(s ^ a, t, a, n[14], 23, 9, -35309556),
            i = t ^ a,
            e = r(i ^ o, e, t, n[1], 4, 28, -1530992060),
            o = r(i ^ e, o, e, n[4], 11, 21, 1272893353),
            s = o ^ e,
            a = r(s ^ t, a, o, n[7], 16, 16, -155497632),
            t = r(s ^ a, t, a, n[10], 23, 9, -1094730640),
            i = t ^ a,
            e = r(i ^ o, e, t, n[13], 4, 28, 681279174),
            o = r(i ^ e, o, e, n[0], 11, 21, -358537222),
            s = o ^ e,
            a = r(s ^ t, a, o, n[3], 16, 16, -722521979),
            t = r(s ^ a, t, a, n[6], 23, 9, 76029189),
            i = t ^ a,
            e = r(i ^ o, e, t, n[9], 4, 28, -640364487),
            o = r(i ^ e, o, e, n[12], 11, 21, -421815835),
            s = o ^ e,
            a = r(s ^ t, a, o, n[15], 16, 16, 530742520),
            t = r(s ^ a, t, a, n[2], 23, 9, -995338651),
            e = r(a ^ (t | ~o), e, t, n[0], 6, 26, -198630844),
            o = r(t ^ (e | ~a), o, e, n[7], 10, 22, 1126891415),
            a = r(e ^ (o | ~t), a, o, n[14], 15, 17, -1416354905),
            t = r(o ^ (a | ~e), t, a, n[5], 21, 11, -57434055),
            e = r(a ^ (t | ~o), e, t, n[12], 6, 26, 1700485571),
            o = r(t ^ (e | ~a), o, e, n[3], 10, 22, -1894986606),
            a = r(e ^ (o | ~t), a, o, n[10], 15, 17, -1051523),
            t = r(o ^ (a | ~e), t, a, n[1], 21, 11, -2054922799),
            e = r(a ^ (t | ~o), e, t, n[8], 6, 26, 1873313359),
            o = r(t ^ (e | ~a), o, e, n[15], 10, 22, -30611744),
            a = r(e ^ (o | ~t), a, o, n[6], 15, 17, -1560198380),
            t = r(o ^ (a | ~e), t, a, n[13], 21, 11, 1309151649),
            e = r(a ^ (t | ~o), e, t, n[4], 6, 26, -145523070),
            o = r(t ^ (e | ~a), o, e, n[11], 10, 22, -1120210379),
            a = r(e ^ (o | ~t), a, o, n[2], 15, 17, 718787259),
            t = r(o ^ (a | ~e), t, a, n[9], 21, 11, -343485551),
            h[0] = e,
            h[1] = t,
            h[2] = a,
            h[3] = o
        }
        var m = []
          , c = []
          , u = []
          , d = []
          , b = "0123456789abcdef".split("")
          , p = []
          , C = []
          , _ = !1
          , g = 0
          , f = 0
          , h = [];
        if (e.Int32Array)
            c = new Int32Array(16),
            u = new Int32Array(16),
            d = new Int32Array(4),
            p = new Int32Array(4),
            C = new Int32Array(4),
            h = new Int32Array(4);
        else {
            var L;
            for (L = 0; 16 > L; L++)
                c[L] = u[L] = 0;
            for (L = 0; 4 > L; L++)
                d[L] = p[L] = C[L] = h[L] = 0
        }
        d[0] = 128,
        d[1] = 32768,
        d[2] = 8388608,
        d[3] = -2147483648,
        p[0] = 0,
        p[1] = 8,
        p[2] = 16,
        p[3] = 24,
        e.md5 = e.md5 || n
    }
    ("undefined" == typeof global ? window : global),
    md5
}
),
angular.module("components.curActionIcon", []).factory("$curActionIcon", ["$rootScope", "$compile", "$animate", "$timeout", "$ionicTemplateLoader", "$ionicPlatform", "$ionicBody", function(e, t, a, o, n, r, i) {
    function s(n) {
        var s = e.$new(!0);
        angular.extend(s, {
            cancel: angular.noop,
            buttonClicked: angular.noop,
            $deregisterBackButton: angular.noop,
            buttons: [],
            cancelOnStateChange: !0
        }, n || {});
        var l = s.element = t('<cur-action-icon ng-class="cssClass" buttons="buttons"></cur-action-icon>')(s)
          , m = angular.element(l[0].querySelector(".action-sheet-wrapper"))
          , c = s.cancelOnStateChange ? e.$on("$stateChangeSuccess", function() {
            s.cancel()
        }
        ) : angular.noop;
        return s.removeSheet = function(e) {
            s.removed || (s.removed = !0,
            m.removeClass("action-sheet-up"),
            o(function() {
                i.removeClass("action-sheet-open")
            }
            , 400),
            s.$deregisterBackButton(),
            c(),
            a.removeClass(l, "active").then(function() {
                s.$destroy(),
                l.remove(),
                s.cancel.$scope = m = null ,
                (e || angular.noop)()
            }
            ))
        }
        ,
        s.showSheet = function(e) {
            s.removed || (i.append(l).addClass("action-sheet-open"),
            a.addClass(l, "active").then(function() {
                s.removed || (e || angular.noop)()
            }
            ),
            o(function() {
                s.removed || m.addClass("action-sheet-up")
            }
            , 20, !1))
        }
        ,
        s.$deregisterBackButton = r.registerBackButtonAction(function() {
            o(s.cancel)
        }
        , 300),
        s.cancel = function() {
            s.removeSheet(n.cancel)
        }
        ,
        s.buttonClicked = function(e) {
            n.buttonClicked(e, n.buttons[e]) === !0 && s.removeSheet()
        }
        ,
        s.destructiveButtonClicked = function() {
            n.destructiveButtonClicked() === !0 && s.removeSheet()
        }
        ,
        s.showSheet(),
        s.cancel.$scope = s,
        s.cancel
    }
    return {
        show: s
    }
}
]),
angular.module("services.common.auth", ["components.localStorage"]).service("AuthService", ["$http", "$rootScope", "$location", "$q", "$cordovaLocalStorage", "utils", "Constants", function(e, t, a, o, n, r, i) {
    var s = {
        UserName: null ,
        NickName: "",
        UserWallet: ""
    }
      , l = {
        SESSION_TOKEN: "SESSION_TOKEN",
        OA_APP_USER: "OA_APP_USER"
    }
      , m = {
        currentUser: {},
        init: function() {
            var t = this;
            e.defaults.headers.common["X-Access-Token"] = n.getItem(l.SESSION_TOKEN) || "",
            e.defaults.headers.common["X-Access-Host"] = i.DEBUGMODE ? i.DOMAIN : a.host();
            var o = n.getItem(l.OA_APP_USER) || s;
            "string" == typeof o && (o = JSON.parse(o)),
            t.currentUser = o
        },
        updateUser: function(e, t) {
            var a = this
              , o = {
                remove: !1,
                set: !1
            };
            angular.extend(o, t),
            angular.extend(a.currentUser, e),
            o.remove === !0 && (n.removeItem(l.OA_APP_USER),
            a.currentUser = {}),
            o.set === !0 && n.setItem(l.OA_APP_USER, JSON.stringify(a.currentUser))
        },
        isLoggedIn: function() {
            var e = this
              , t = !1;
            return e.currentUser.UserName && (t = !0),
            t
        },
        login: function(t) {
            var a = this
              , i = o.defer()
              , s = function(o) {
                if (0 == o.ErrorCode) {
                    var r = o.Data
                      , s = o.Data.Token;
                    angular.extend(r, {
                        UserName: t.MemberName
                    }),
                    a.updateUser(r, {
                        set: !0
                    }),
                    e.defaults.headers.common["X-Access-Token"] = s,
                    n.setItem(l.SESSION_TOKEN, s)
                }
                i.resolve(o)
            }
              , m = function(e) {
                i.reject(e)
            }
            ;
            return r.getResource("/members/Login", t).then(s, m),
            i.promise
        },
        loginAndTransfer: function(t) {
            var a = this
              , i = o.defer()
              , s = function(o) {
                if (0 == o.ErrorCode) {
                    var r = o.Data
                      , s = o.Data.Token;
                    angular.extend(r, {
                        UserName: t.MemberName
                    }),
                    a.updateUser(r, {
                        set: !0
                    }),
                    e.defaults.headers.common["X-Access-Token"] = s,
                    n.setItem(l.SESSION_TOKEN, s)
                }
                i.resolve(o)
            }
              , m = function(e) {
                i.reject(e)
            }
            ;
            return r.getResource("/members/LoginAndTransfer", t).then(s, m),
            i.promise
        },
        checkToken: function() {
            var e = this
              , t = o.defer()
              , a = function(a) {
                108 == a.ErrorCode && e.resetCache(),
                t.resolve(a)
            }
              , n = function(e) {
                t.reject(e)
            }
            ;
            return r.getResource("/members/CheckToken", "").then(a, n),
            t.promise
        },
        logout: function() {
            var e = this;
            r.getResource("/members/Logout", ""),
            e.resetCache()
        },
        resetCache: function() {
            var t = this;
            n.removeItem(l.OA_APP_USER),
            n.removeItem(l.SESSION_TOKEN),
            e.defaults.headers.common["X-Access-Token"] = "",
            t.updateUser(s, {
                remove: !0
            })
        }
    };
    return m
}
]),
angular.module("services.common.popover", ["ionic"]).service("PopoverService", ["$ionicPopover", "$q", function(e) {
    function t(t) {
        if (angular.isObject(t)) {
            var a = angular.extend({
                data: null ,
                template: null ,
                scope: null ,
                width: 100,
                height: 0,
                label: "label",
                selected: null ,
                options: {},
                onSelect: function() {}
            }, t)
              , o = this
              , n = "";
            angular.forEach(a.data, function(e) {
                var t = "";
                a.selected === e.label && (t = "selected"),
                n += '<a class="item ' + t + ' " href="" >' + e[a.label] + "</a>"
            }
            ),
            0 == a.height ? (a.height = a.data.length ? 38 * a.data.length + 10 : 200,
            a.height > 200 && (a.height = 200)) : a.height = 200;
            var r = '<ion-popover-view style="width: ' + a.width + "px;height:" + a.height + 'px;"><ion-content><div class="list">' + n + "</div></ion-content></ion-popover-view>";
            a.template = a.template || r;
            var i = a.scope;
            a.options.scope = i,
            this.popover = e.fromTemplate(a.template, a.options);
            var s = this.popover.$el.find("a");
            return s.on("click", function(e) {
                s.removeClass("selected"),
                angular.element(this).addClass("selected");
                var t = ""
                  , n = angular.element(this).children().length > 0;
                t = n ? angular.element(this).children().attr("data_id") : angular.element(e.target).text();
                var r = ""
                  , i = _.find(a.data, function(e) {
                    return r = n ? angular.element(e[a.label]).attr("data_id") : e[a.label],
                    r === t
                }
                );
                a.onSelect(t, i, e, o),
                o.popover.hide()
            }
            ),
            i.$on("$destroy", function() {
                o.popover.remove()
            }
            ),
            i.$on("$ionicView.beforeLeave", function() {
                o.popover.hide()
            }
            ),
            i.$on("popover.hidden", function() {}
            ),
            i.$on("popover.removed", function() {}
            ),
            this.popover
        }
    }
    return t
}
]),
angular.module("services.common.store", ["components.localStorage"]).service("StoreService", ["$q", "$cordovaLocalStorage", function(e, t) {
    function a(a, o) {
        var n = this;
        this.deferred = e.defer(),
        this.cacheKey = a;
        var r = t
          , i = function(e) {
            0 === e.ErrorCode && n.setItem(e.Data),
            n.deferred.resolve(e)
        }
          , s = function(e) {
            n.deferred.reject(e)
        }
        ;
        return this.setItem = function(e) {
            r.setItem(this.cacheKey, JSON.stringify(e))
        }
        ,
        this.getItem = function() {
            var e = r.getItem(this.cacheKey);
            return e && (e = JSON.parse(e)),
            e
        }
        ,
        angular.isObject(o) && o.then(i, s),
        angular.isFunction(o) && o().then(i, s),
        this.promise = this.deferred.promise,
        this
    }
    return a
}
]).service("StoreByUriService", ["$q", "StoreService", "$cordovaLocalStorage", "utils", function(e, t, a, o) {
    function n(t, n, r) {
        var i = this;
        this.deferred = e.defer(),
        this.cacheKey = t;
        var s = a
          , l = function(e) {
            0 === e.ErrorCode && i.setItem(e.Data, r),
            i.deferred.resolve(e)
        }
          , m = function(e) {
            i.deferred.reject(e)
        }
        ;
        if (this.setItem = function(e, t) {
            if (t) {
                var a = new Date;
                s.setItem(this.cacheKey + "_TIME", a.getTime() + 1e3 * t)
            }
            s.setItem(this.cacheKey, JSON.stringify(e))
        }
        ,
        this.getItem = function() {
            var e = null
              , t = i.getTime();
            if (t) {
                var a = new Date;
                a.getTime() <= t && (e = s.getItem(this.cacheKey))
            } else
                e = s.getItem(this.cacheKey);
            return e && (e = JSON.parse(e)),
            e
        }
        ,
        this.clear = function() {
            s.removeItem(this.cacheKey),
            s.removeItem(this.cacheKey + "_TIME")
        }
        ,
        this.getTime = function() {
            var e = s.getItem(this.cacheKey + "_TIME");
            return e
        }
        ,
        angular.isObject(n))
            if (r) {
                var c = i.getItem();
                c ? i.deferred.resolve({
                    ErrorCode: 0,
                    Data: c
                }) : o.getResource(n.getUri(), n.Params(), n.Options()).then(l, m)
            } else
                o.getResource(n.getUri(), n.Params(), n.Options()).then(l, m);
        else if (n)
            if (r) {
                var c = i.getItem();
                c ? i.deferred.resolve({
                    ErrorCode: 0,
                    Data: c
                }) : o.getResource(n).then(l, m)
            } else
                o.getResource(n).then(l, m);
        return this.promise = this.deferred.promise,
        this
    }
    return n
}
]),
//登录验证
angular.module("services.common.utils", []).factory("utils", ["$q", "$http", "$filter", "$rootScope", "Constants", "QuickDialog", function(e, t, a, o, n, r) {
    function i(i, s, l) {
        var m = e.defer()
          , c = function(e, t) {
            if (r.hideLoading(),
            401 === t) {
                if (111 != e.ErrorCode)
                    return o.$emit("event:auth-loginRequired");
                r.alert(a("translate")("Member_TipsLoginOther"), function() {
                    return o.$emit("event:auth-loginRequired")
                }
                )
            } else if (0 != l.isTip)
                if (404 === t)
                    r.tips(a("translate")("Common_TipsGetDataTimeOut"));
                else if (500 === t)
                    r.tips(a("translate")("Common_TipsGetDataTimeOut"));
                else if (0 === t)
                    r.tips(a("translate")("Common_TipsGetDataTimeOut"));
                else if (901 === t)
                    switch (e.ErrorCode) {
                    case 102:
                        var n = ""
                          , i = "";
                        return angular.isDefined(e.Data) && (n = e.Data.CloseTime || "",
                        i = e.Data.OpenTime || ""),
                        o.$emit("event:sys-maintenance", n, i)
                    }
                else if (angular.isObject(e))
                    switch (e.ErrorCode) {
                    case 108:
                        return o.$emit("event:auth-loginRequired")
                    }
                else
                    r.tips(a("translate")("Common_TipsGetDataTimeOut"));
            m.reject(e)
        }
          , u = {
            timeout: 120e3
        };
        l = angular.extend({}, u, l);
        o.online;
        return t.post(n.API_PATH + i + "?r=" + parseInt(1e4 * Math.random()), s, l).success(function(e) {
            angular.isArray(e.Data) && !e.Data.length ;
            angular.isObject(e) && !e.Data;
            if (angular.isObject(e)){
                r.hideLoading();
                switch (e.ErrorCode) {
                    case 111:
                        return o.$emit("event:auth-loginRequired");
                    case 112:
                        return o.$emit("event:auth-loginRequired");
                    case 113:
                        return o.$emit("event:auth-loginRequired");
                    case 114:
                        return o.$emit("event:auth-loginRequired");
                    case 115:
                        return o.$emit("event:auth-loginRequired");
                };
            }
            m.resolve(e)
        }
        ).error(c),
        m.promise
    }
    function s(e, t, a) {
        return this.getUri = function() {
            return e
        }
        ,
        this.Params = function() {
            return t
        }
        ,
        this.Options = function() {
            return a
        }
        ,
        this
    }
    function l(e) {
        if (!e)
            return "";
        try {
            var t = new Date(e);
            switch (t.getDay()) {
            case 0:
                return a("translate")("Common_TipsSunday");
            case 1:
                return a("translate")("Common_TipsMonday");
            case 2:
                return a("translate")("Common_TipsTuesday");
            case 3:
                return a("translate")("Common_TipsWednesday");
            case 4:
                return a("translate")("Common_TipsThursday");
            case 5:
                return a("translate")("Common_TipsFriday");
            case 6:
                return a("translate")("Common_TipsSaturday")
            }
        } catch (o) {
            return ""
        }
    }
    function m() {
        var e = navigator.userAgent.toLocaleLowerCase()
          , t = String(navigator.platform).indexOf("linux") > -1
          , a = "android" == e.match(/android/i)
          , o = "windows mobile" == e.match(/windows mobile/i)
          , n = "ipad" == e.match(/ipad/i)
          , r = "iphone os" == e.match(/iphone os/i)
          , i = "windows nt" == e.match(/windows nt/i);
        return t ? "linux" : n || r ? "ios" : o ? "wp" : i ? "pc" : a ? "android" : void 0
    }
    function c(e) {
        var t = navigator.userAgent.toLocaleLowerCase();
        switch (e) {
        case "uc":
            return t.indexOf("ucbrowser") > -1;
        case "qq":
            return t.indexOf("mqqbrowser") > -1;
        case "chrome":
            return t.indexOf("crios") > -1;
        default:
            return !1
        }
    }
    function u(e) {
        var t = m();
        "android" == t ? window.open(e) : window.open(e, "_blank", "resizable=yes")
    }
    function d(e) {
        if (!(e.length > 4))
            return e;
        try {
            return e.replace(/\s/g,'').replace(/(\d{4})(?=\d)/g,"$1 ");
        } catch (t) {
            return e
        }
    }
    function b(e, t, a) {
        if (t) {
            a && parseFloat(a) || (a = 1576800);
            var o = new Date;
            o.setTime(o.getTime() + 60 * a * 1e3),
            document.cookie = e + "=" + escape(t) + ";expires=" + o.toGMTString()
        } else
            for (var n = document.cookie.split("; "), r = 0; r < n.length; r++) {
                var i = n[r].split("=");
                if (i[0] == e)
                    return unescape(i[1])
            }
    }
    function p(e, t) {
        var a = new Date;
        if ("encode" == t) {
            var o = a.getFullYear()
              , n = a.getMonth() < 10 ? "0" + a.getMonth() : a.getMonth()
              , r = a.getDate() < 10 ? "0" + a.getDate() : a.getDate();
            return o + "" + n + r + e
        }
        return e.substr(8)
    }
    return {
        getResourceParams: s,
        getResource: i,
        getWeek: l,
        getBrowser: m,
        checkBrowser: c,
        openWindow: u,
        splitCardNO: d,
        cookie: b,
        simpleCrypto: p
    }
}
]),
angular.module("app.language", ["pascalprecht.translate"]).config(["$translateProvider", function(e) {
    e.translations("en", LangEN),
    e.translations("zh_cn", LangCN),
    e.translations("zh_tw", LangTW)
}
]).factory("AppLanguage", ["$q", "$translate", "$cordovaLocalStorage", "AppConfig", function(e, t, a, o) {
    var n = {
        APP_LANGUAGE: "APP_LANGUAGE",
        APP_LANGUAGE_FILE: "APP_LANGUAGE_FILE"
    }
      , r = {
        set: function(o) {
            var r = e.defer()
              , i = this;
            return o && "" != o && t.use(o).then(function(e) {
                a.setItem(n.APP_LANGUAGE, o),
                i.setLoaded(),
                r.resolve(e)
            }
            , function(e) {
                r.reject(e)
            }
            ).catch(function() {
                t.use("zh_cn")
            }
            ),
            r.promise
        },
        get: function() {
            return a.getItem(n.APP_LANGUAGE) || o.getDefaultLang() || "zh_cn"
        },
        setDefault: function() {
            var e = this.get();
            return this.set(e)
        },
        setLoaded: function() {
            a.setItem(n.APP_LANGUAGE_FILE, !0)
        },
        getLoaded: function() {
            return a.getItem(n.APP_LANGUAGE_FILE) || !1
        },
        clearLoaded: function() {
            a.removeItem(n.APP_LANGUAGE_FILE)
        }
    };
    return r
}
]),
angular.module("services.common.constants", []).factory("Constants", [function() {
    var e = {
        API_PATH: document.getElementById("APIURL").value, //"http://wapindexci.com/api",
        DEBUGMODE: !1,
        CACHETIMEOUT: 60,
        DOMAIN: "127.0.0.1",
        CSSPATH: {
            _default: "default/default.css",
            _white: "white/white.css"
        }
    };
    return e
}
]),
angular.module("services.common.dialog", []).factory("QuickDialog", ["$ionicLoading", "$ionicPopup", "$filter", "$timeout", "$rootScope", function(e, t, a, o, n) {
    var r = {
        loading: function(t, a) {
            t || (t = 1e4),
            a || (a = '<ion-spinner class="loading-page"></ion-spinner>'),
            e.show({
                template: a,
                duration: t,
                noBackdrop: !0,
                delay: 100
            })
        },
        hideLoading: function() {
            e.show({
                template: " ",
                duration: 1,
                noBackdrop: !0,
                delay: 0
            })
        },
        tips: function(t, a, n) {
            null  == a && (a = 1500);
            e.show({
                template: "<div class='tips'>" + t + "</div>",
                duration: a
            });
            angular.isFunction(n) && o(function() {
                n()
            }
            , a)
        },
        timeout: function() {
            var e = a("translate")("Member_TipsOutOfTime");
            this.tips(e)
        },
        alert: function(e, o) {
            n.alertFlag || (n.alertFlag = !0,
            t.show({
                title: e,
                buttons: [{
                    text: a("translate")("Common_ButtonSure"),
                    type: "button-positive",
                    onTap: function() {
                        n.alertFlag = !1,
                        angular.isFunction(o) && o()
                    }
                }]
            }))
        },
        loadAndDisable: function(e, t, a) {
            e && angular.element(document.querySelector("#" + e)).attr({
                disabled: "disabled"
            }),
            null  == a ? this.loading(t, a) : this.tips(a, t)
        },
        hideLoadAndDisable: function(e, t) {
            e && angular.element(document.querySelector("#" + e)).removeAttr("disabled"),
            t && this.hideLoading()
        }
    };
    return r
}
]),
angular.module("services.common.goback", []).factory("BackService", ["$rootScope", "$state", "$cordovaLocalStorage", function(e, t, a) {
    var o = {
        APP_FROMTYPE: "APP_FROMTYPE"
    }
      , n = {
        setType: function(e) {
            a.setItem(o.APP_FROMTYPE, e)
        },
        getType: function() {
            return a.getItem(o.APP_FROMTYPE) || "index"
        },
        goBack: function() {
            var e = this.getType();
            switch (e) {
            case "index":
                t.go("index");
                break;
            case "center":
                t.go("center");
                break;
            default:
                t.go("index")
            }
        }
    };
    return n
}
]),
angular.module("services.common.country", ["ionic"]).service("ChooseService", ["$filter", "$ionicPopup", "$timeout", function(e, t, a) {
    function o(o) {
        if (angular.isObject(o)) {
            var n = angular.extend({
                data: null ,
                selected: null ,
                scope: null ,
                onSelect: function() {},
                onTap: function() {}
            }, o)
              , r = function(e) {
                a(function() {
                    angular.element(document.querySelectorAll("#" + e))[0].focus()
                }
                , 100)
            }
              , i = ""
              , s = "";
            angular.forEach(n.data, function(e) {
                s = "",
                e.CountryAreaCode == n.selected && (s = "selected"),
                i += '   <div class="item">     <a class="button button-full button-light ' + s + '" data-value="' + e.CountryAreaCode + '">' + e.CountryName + "</a>   </div>"
            }
            );
            var l = '<div class="list">' + i + '   <div class="item">     <div class="row">       <div class="col-33">         <a class="button button-full button-light button-border-right" data-value="">' + e("translate")("Member_LabelOther") + '</a>       </div>       <div class="col">         <input id="txt_countrycode" type="tel" />       </div>     </div>   </div></div>'
              , m = t.show({
                cssClass: "country-choose",
                template: l,
                buttons: [{
                    text: e("translate")("Common_ButtonSure"),
                    type: "button-full button-light",
                    onTap: function(e) {
                        n.onTap(e)
                    }
                }]
            });
            return a(function() {
                angular.element(document.querySelector("#txt_countrycode")).val(n.selected);
                var e = angular.element(document.querySelector(".country-choose")).find("a");
                e.on("click", function(t) {
                    var a = angular.element(this).attr("data-value");
                    e.removeClass("selected"),
                    angular.element(this).addClass("selected"),
                    "" == a && (angular.element(document.querySelector("#txt_countrycode")).parent().addClass("selected"),
                    r("txt_countrycode")),
                    angular.element(document.querySelector("#txt_countrycode")).val(a),
                    n.onSelect(t, a)
                }
                );
                var t = angular.element(document.querySelector("#txt_countrycode"));
                t.on("keyup", function(e) {
                    var t = angular.element(this).val();
                    n.onSelect(e, t)
                }
                )
            }
            , 500),
            n.scope.$on("$destroy", function() {
                m && m.close()
            }
            ),
            m
        }
    }
    return o
}
]),
angular.module("filters.app", ["pascalprecht.translate"]).filter("amount", function() {
    function e(e) {
        var t = e >= 0 ? "positive" : "balanced";
        return null  != e ? '<span class="' + t + '">' + e.toFixed(2) + "</span>" : '<span class="' + t + '">' + e + "</span>"
    }
    return e.$stateful = !0,
    e
}
).filter("realName", function() {
    function e(e) {
        return "undefined" == typeof e ? "" : "[" + e + "]"
    }
    return e.$stateful = !0,
    e
}
).filter("formats", ["$filter", function(e) {
    function t(t, a) {
        var o = e("translate")(t);
        return o && "" != o && (o = o.format(a)),
        o
    }
    return String.prototype.format = function() {
        var e = arguments[0];
        return this.replace(/\{(\d+)\}/g, function(t, a) {
            return e[a]
        }
        )
    }
    ,
    t.$stateful = !0,
    t
}
]),
angular.module("app.config", []).service("AppConfig", ["$http", "$q", "StoreService", "StoreByUriService", "$location", "Constants", "utils","$cordovaLocalStorage", function(e, t, a, o, n, r, i,al) {
    var s = n.host();
    r.DEBUGMODE && (s = r.DOMAIN);
    var l = {
        APP_LANGUAGE: "APP_LANGUAGE",
        APP_CONFIG_DATA: "APP_CONFIG_DATA",
        APP_PLAYIMG_DATA: "APP_PLAYIMG_DATA"
    }
      , m = {
        CDNURL: document.getElementById("CDNURL").value,
        getGlobal: function() {
            var e = i.getResourceParams("/basic/GetSiteBasicConfig", {
                domain: s
            })
              , t = new o(l.APP_CONFIG_DATA,e);
            return t.promise
        },
        getData: function() {
            var e = new o(l.APP_CONFIG_DATA).getItem();
            return e
        },
        getMainTheme: function() {
            var e = this.getData();
            return e.Theme
        },
        getTemplate: function() {
            var e = this.getData();
            return e.Template
        },
        getDefaultLang: function() {
            var e = this.getData();
            return e ? e.Lang.split(",")[0].replace("-", "_") : "zh_cn"
        },
        getLangs: function() {
            var e = this.getData();
            return e.Lang.split(",")
        },
        getLogo: function() {
            var e = this.getData();
            return e.LogoUrl
        },
        getIcon: function() {
            var e = this.getData();
            return e.DesktopIconUrl
        },
        getSiteNo: function() {
            var e = this.getData();
            return e.SiteNo
        },
        getRegStep: function() {
            var e = this.getData();
            return e.RegStep
        },
        getTopbarBgColor: function() {
            var e = this.getData();
            return e.TopbarBgColor
        },
        getReturnMode: function() {
            var e = this.getData();
            return e.ReturnMode && "2" == e.ReturnMode ? !0 : !1
        },
        getTryPlaySet: function() {
            var e = this.getData();
            return e && e.TryPlaySet ? e.TryPlaySet : !1
        },
        getSiteName: function(e) {
            var t = ""
              , a = this.getData();
            if (a) {
                var o = this.getData().SiteName;
                if (o) {
                    o = '{"' + o.replace(/:/g, '":"').replace(/,/g, '","') + '"}';
                    var n = JSON.parse(o);
                    angular.forEach(n, function(a, o) {
                        o == e && (t = a)
                    }
                    , t)
                }
            }
            return t
        },
        setAppTitle: function(e) {
            var t = this.getSiteName(e),c = this.getTopbarBgColor(e);
            var dd = angular.element(document.querySelector("#topbarbg"));
            var t = this.getSiteName(e),c = this.getTopbarBgColor(e);
            "" != t && (angular.element(document.querySelector("#appTitle")).text(t),
            angular.element(document.querySelector(".bar-header.bar-energized")).attr("style", "background:"+c),
            angular.element(document.querySelector("#appTouchIcon")).attr("href", this.getIcon()))
        },
        getOpenGameList: function() {
            var e = this.getData();
            return e.GameClassList
        },
        getComputerUrl: function() {
            var e = ""
                , t = n.protocol()
                , a = n.host()
                , o = n.port()
                , r = /([0-9]{1,3}\.{1}){3}[0-9]{1,3}/;
            if (!r.test(a)) {
                var i = a.split(".")
                    , s = "80" == o || "" == o ? "" : ":" + o;
                e = t + "://www." + i[i.length - 2] + "." + i[i.length - 1] + s + "/?type=PC&intr="+ this.getIntr()
            }
            return e
        },
        getServiceUrl: function() {
            var e = this.getData();
            return e.OnLineServiceLink
        },
        getIntr: function() {
            var e = this.getData();
            return e.Intr
        },
        getPlayImages: function(e) {
            var t = this.getSiteNo()
              , a = i.getResourceParams("/basic/GetPlayImages", {
                siteNo: t,
                LanguageNo: e
            }, {
                isTip: 0
            });
            return new o(l.APP_PLAYIMG_DATA + e,a,r.CACHETIMEOUT).promise
        },
        getGameState: function() {
            var e = this.getSiteNo();
            return i.getResource("/basic/GetGameState", {
                SiteNo: e
            })
        },
        getEGames: function() {
            var e = this.getSiteNo();
            return i.getResource("/basic/GetEGames", {
                SiteNo: e
            })
        },
        getMoreEGames: function(t) {
            var a = this.getSiteNo(),
           ln = al.getItem(l.APP_LANGUAGE) || this.getDefaultLang() || "zh_cn";
            return angular.extend(t, {
                SiteNo: a,
                LanguageNo: ln
            }), i.getResource("/basic/GetEGames", t)
        },
        getCountryData: function(e) {
            var t = this.getSiteNo();
            return i.getResource("/basic/GetSiteCountrys", {
                SiteNo: t,
                LanguageNo: e
            })
        },
        getCurrencyData: function(e, t) {
            var a = this.getSiteNo();
            return i.getResource("/basic/GetSiteCurrencys", {
                SiteNo: a,
                LanguageNo: e,
                CountryNo: t
            })
        },
        getProvinceData: function() {
            return i.getResource("/basic/GetProvinces")
        },
        getCityData: function(e) {
            return i.getResource("/basic/GetCitys", {
                ProvinceName: e
            })
        },
        getSystemMaintenance: function() {
            return i.getResource("/basic/GetSystemMaintenance")
        },
        clearConfigCache: function() {
            var e = new o(l.APP_CONFIG_DATA);
            e.clear()
        },
        clearPlayImagesCache: function() {
            var e = new o(l.APP_PLAYIMG_DATA);
            e.clear()
        }
    };
    return m
}
]),
angular.module("app.theme", []).factory("ThemeConfig", ["$cordovaLocalStorage", "$q", "$http", "$templateCache", "Constants", "AppConfig", function(e, t, a, o, n, r) {
    var i = {
        getPath: function(e) {
            switch (e) {
            case "default":
                return n.CSSPATH._default;
            case "white":
                return n.CSSPATH._white;
            default:
                return n.CSSPATH._default
            }
        },
        load: function(e) {
            var t = this.getPath(e);
            if ("" !== t)
                if (document.querySelector("#appstyle")) {
                    var a = angular.element(document.querySelector("#appstyle"));
                    a.attr("href", r.CDNURL + "styles/" + t)
                } else {
                    var a = document.createElement("link");
                    a.id = "appstyle",
                    a.rel = "stylesheet",
                    a.href = r.CDNURL + "styles/" + t + "?r=" + parseInt(1e4 * Math.random()),
                    a.charset = "utf-8",
                    document.head.appendChild(a)
                }
        },
        loadTemple: function(e) {
            return void e();
            /*
            if (n.DEBUGMODE)
                return void e();
            var t = document.getElementsByTagName("head")[0]
              , a = document.createElement("script");
            a.setAttribute("type", "text/javascript"),
            a.setAttribute("src", r.CDNURL + "scripts/" + r.getMainTheme() + ".js?r=" + parseInt(1e4 * Math.random())),
            t.appendChild(a),
            a.onload = a.onreadystatechange = function() {
                this.readyState && "loaded" != this.readyState && "complete" != this.readyState || (template(o),
                e()),
                a.onload = a.onreadystatechange = null
            }*/
        },
        setDefault: function() {
            this.load(r.getMainTheme())
        }
    };
    return i
}
]),
angular.module("services.register", []).factory("RegService", ["$cordovaLocalStorage", "utils", "AppConfig", "Constants", function(e, t, a) {
    var o = a.getSiteNo()
      , n = {
        APP_PROMO_CODE: "APP_PROMO_CODE"
    }
      , r = {
        verifyRegisterMember: function(e) {
            return angular.extend(e, {
                SiteNo: o
            }),
            t.getResource("/members/VerifyRegisterMember", e)
        },
        registerMember: function(e) {
            return angular.extend(e, {
                SiteNo: o
            }),
            t.getResource("/members/RegisterMember", e)
        },
        setPromoCodeCache: function(t) {
            e.setItem(n.APP_PROMO_CODE, t)
        },
        getPromoCodeCache: function(t) {
            var t = e.getItem(n.APP_PROMO_CODE) || "";
            return t.length > 0 && (t = t.substring(1, t.length)),
            t
        },
        removePromoCodeCache: function() {
            e.removeItem(n.APP_PROMO_CODE)
        }
    };
    return r
}
]),
angular.module("services.notice", []).factory("NoticeService", ["utils", "AppConfig", "AppLanguage", "StoreByUriService", "Constants", function(e, t, a, o, n) {
    var r = {
        APP_NOTICE_DATA: "APP_NOTICE_DATA"
    }
      , i = t.getSiteNo()
      , s = {
        getNotice: function(t) {
            var s = a.get();
            angular.extend(t, {
                SiteNo: i,
                LanguageNo: s
            });
            var l = e.getResourceParams("/basic/GetNewNotices", t, {
                isTip: 0
            });
            return new o(r.APP_NOTICE_DATA + s,l,n.CACHETIMEOUT).promise
        },
        clearNoticeCache: function() {
            return new o(r.APP_NOTICE_DATA).clear()
        },
        getMoreNotice: function(t) {
            var o = a.get();
            return angular.extend(t, {
                SiteNo: i,
                LanguageNo: o
            }),
            e.getResource("/members/GetMoreNotices", t)
        },
        setReadNotices: function(t) {
            return e.getResource("/members/SetReadNotices", t, {
                isTip: 0
            })
        },
        getMessageCount: function(t) {
            return e.getResource("/members/GetMessageCount", t, {
                isTip: 0
            })
        },
        getInternalMessageTypeList: function(t) {
            return e.getResource("/members/GetInternalMessageTypeList", t, {
                isTip: 0
            })
        },
        getInternalMessageList: function(t) {
            return e.getResource("/members/GetInternalMessageList", t)
        },
        addInternalMessage: function(t) {
            return e.getResource("/members/AddInternalMessage", t, {
                isTip: 0
            })
        },
        getInternalMessage: function(t) {
            return e.getResource("/members/GetInternalMessage", t)
        },
        deleteMessage: function(t) {
            return e.getResource("/members/DeleteMessage", t)
        }
    };
    return s
}
]),
angular.module("services.account", []).factory("AccountService", ["$cordovaLocalStorage", "AppConfig", "utils", "StoreByUriService", "Constants", function(e, t, a) {
    var o = t.getSiteNo()
      , n = {
        APP_MEMBERGAMESTATE_DATA: "APP_MEMBERGAMESTATE_DATA",
        LOGIN_USERNAME: "LOGIN_USERNAME"
    }
      , r = {
        fgtPwd: function(e) {
            return angular.extend(e, {
                siteNo: o
            }),
            a.getResource("/members/ForgetPassword", e)
        },
        resetPwd: function(e) {
            return a.getResource("/members/ForgetPasswordChangePassword", e)
        },
        mdfPwd: function(e) {
            return a.getResource("/members/ChangeMemberPwd", e)
        },
        getMember: function(e) {
            return a.getResource("/members/GetMember", e)
        },
        getMember2: function(e) {
            return a.getResource("/members/GetMember2", e)
        },
        mdfInfo: function(e) {
            return a.getResource("/members/UpdateMember", e)
        },
        getMemberGameState: function(e) {
            return a.getResource("/members/GetMemberGameState", e)
        },
        getMemberGameInfo: function(e) {
            return a.getResource("/members/GetMemberGameInfo", e)
        },
        setUserNameCache: function(t) {
            e.setItem(n.LOGIN_USERNAME, t)
        },
        getUserNameFromCache: function() {
            return e.getItem(n.LOGIN_USERNAME) || ""
        },
        GetInGameRedirect: function(e) {
            return a.getResource("/members/GetInGameRedirect", e)
        },
        getTestMember: function(e) {
            return a.getResource("/members/GetTestMember", e)
        },
        getTestMemberName: function() {
            return a.getResource("/members/getTestMemberName", {
                SiteNo: o
            })
        }
    };
    return r
}
]),
angular.module("services.report", []).factory("ReportService", ["utils", "$cordovaLocalStorage", function(e, t) {
    var a = {
        APP_REPORT_PARAMS: "APP_REPORT_PARAMS"
    }
      , o = {
        getWeekReport: function() {
            return e.getResource("/reports/GetAccountByWeek", "")
        },
        getDayReport: function(t) {
            return e.getResource("/reports/GetAccountByGameClass", t)
        },
        getDayTime: function() {
            return e.getResource("/reports/getDayTime", {})
        },
        getGameReport: function(t) {
            return e.getResource("/reports/GetAccountByGame", t)
        },
        getDetailReport: function(t) {
            return e.getResource("/reports/GetAccountByGameDetail", t)
        },
        getAccountByGameDetailItems: function(t) {
            return e.getResource("/reports/GetAccountByGameDetailItems", t)
        },
        getParamsCache: function() {
            var e = t.getItem(a.APP_REPORT_PARAMS);
            return e && "string" == typeof e ? JSON.parse(e) : {}
        },
        setParamsCache: function(e) {
            var o = this.getParamsCache();
            angular.extend(o, e),
            t.setItem(a.APP_REPORT_PARAMS, JSON.stringify(o))
        }
    };
    return o
}
]),
angular.module("services.cash", []).factory("CashService", ["$cordovaLocalStorage", "utils", "StoreByUriService", "Constants", "AppConfig", "AppLanguage", function(e, t, a, o, n, r) {
    var i = {
        APP_PAY_PARAMS: "APP_PAY_PARAMS"
    }
      , s = {
        getDWReport: function(e) {
            return t.getResource("/reports/GetDepositReport", e)
        },
         getWReport: function(e) {
            return t.getResource("/reports/GetWithdrawReport", e)
        },
        getTransReport: function(e) {
            return t.getResource("/reports/GetTransferLogs", e)
        },
        getGiveReport: function(e) {
            return t.getResource("/reports/GetGiveLogs", e)
        },
        getWallets: function(e) {
            return t.getResource("/members/GetWallets", e, {
                isTip: 0
            })
        },
        fundTransfer: function(e) {
            return t.getResource("/members/FundTransfer", e)
        },
        getActivityDeposits: function(e) {
            return t.getResource("/members/GetActivityDeposits", e, {
                isTip: 0
            })
        },
        getDepositsBanks: function(e) {
            return t.getResource("/members/GetDepositsBanks", e)
        },
        getDepositLimit: function(e) {
            return t.getResource("/members/GetDepositLimit", e)
        },
        GetOrderNum: function(e) {
            return t.getResource("/members/GetOrderNum", e)
        },
        addDeposit: function(e) {
            return t.getResource("/members/AddDeposit", e, {
                isTip: 0
            })
        },
        addWithdraw: function(e) {
            return t.getResource("/members/AddWithdraw", e)
        },
        getWithdrawLimit: function(e) {
            return t.getResource("/members/GetWithdrawLimit", e)
        },
        getThirdPartyBanks: function() {
            return t.getResource("/deposit_withdraw/GetThirdPartyBanks", {}, {
                isTip: 0
            })
        },
        getThirdCardPartyBanks: function() {
            return t.getResource("/deposit_withdraw/GetThirdPartyCardBanks", {}, {
                isTip: 0
            })
        },
        addThirdPartyDeposit: function(e) {
            return t.getResource("/deposit_withdraw/AddThirdPartyDeposit", e)
        },
        setPayParams: function(t) {
            e.setItem(i.APP_PAY_PARAMS, JSON.stringify(t))
        },
        getPayParams: function() {
            return JSON.parse(e.getItem(i.APP_PAY_PARAMS) || {})
        },
        clearPayParams: function() {
            e.removeItem(i.APP_PAY_PARAMS)
        },
        getDepositType: function() {
            return t.getResource("/basic/GetDepositTypeList", {
                SiteNo: n.getSiteNo(),
                LanguageNo: r.get()
            })
        }
    };
    return s
}
]),
angular.module("services.bank", []).factory("BankService", ["utils", function(e) {
    var t = {
        getBankData: function() {
            return e.getResource("/basic/GetSiteBanks")
        },
        getMemberBankInfo: function(t) {
            return e.getResource("/members/GetMemberBankInfo", t)
        },
        getMemberBankList: function(t) {
            return e.getResource("/members/GetMemberBankList", t)
        },
        saveMemberBank: function(t) {
            return e.getResource("/members/SaveMemberBank", t)
        },
        removeMemberBank: function(t) {
            return e.getResource("/members/RemoveMemberBank", t)
        }
    };
    return t
}
]),
angular.module("directives.tabs", []).directive("curTabs", function() {
    return {
        restrict: "E",
        transclude: !0,
        scope: {
            tabIndex: "@",
            change: "&onChange"
        },
        controller: ["$scope", "$element", function(e) {
            var t = e.panes = [];
            e.select = function(a) {
                var o = 0;
                angular.forEach(t, function(t) {
                    t.selected = !1,
                    t == a && (e.tabIndex = o),
                    o++
                }
                ),
                a.selected = !0
            }
            ,
            this.addPane = function(a) {
                0 == t.length && e.select(a),
                t.push(a),
                t.length - 1 == e.tabIndex && e.select(a)
            }
        }
        ],
        compile: function(e, t) {
            t.$set("class", t["class"] || "tab-bable", !0)
        },
        template: '<div><ul class="tab-nav"><li ng-repeat="pane in panes" class="{{pane.tabscale}}" ng-class="{active:pane.selected}"><a href="" ng-click="select(pane);change({index:tabIndex});">{{pane.title}}</a></li></ul><div class="tab-content" ng-transclude></div></div>',
        replace: !0
    }
}
).directive("curTab", function() {
    return {
        require: "^curTabs",
        restrict: "E",
        transclude: !0,
        scope: {
            title: "@",
            tabscale: "@"
        },
        link: function(e, t, a, o) {
            o.addPane(e)
        },
        template: '<div class="tab-pane" ng-class="{active: selected}" ng-transclude></div>',
        replace: !0
    }
}
),
angular.module("directives.actionIcon", []).directive("curActionIcon", ["$document", "$filter", function(e, t) {
    return {
        restrict: "E",
        scope: !0,
        replace: !0,
        link: function(t, a) {
            var o = function(e) {
                27 == e.which && (t.cancel(),
                t.$apply())
            }
              , n = function(e) {
                e.target == a[0] && (t.cancel(),
                t.$apply())
            }
            ;
            t.$on("$destroy", function() {
                a.remove(),
                e.unbind("keyup", o)
            }
            ),
            e.bind("keyup", o),
            a.bind("click", n)
        },
        template: '<div class="action-sheet-backdrop cur-action-icon"><div class="action-sheet-wrapper"><div class="action-sheet" ><div class="action-sheet-title">' + t("translate")("Common_TipsSharedTo") + '<span>(ps:请先手动复制地址栏地址,然后在点击下列图标)</span></div><div class="action-sheet-group"><div class="row row-wrap"><div class="col col-25" ng-repeat="b in buttons"><img ng-src="{{b.icon}}" ng-click="buttonClicked($index)" />{{b.text}}</div></div></div><div class="action-sheet-cancel" ng-if="cancelText"><button class="button button-light" ng-click="cancel()" ng-bind-html="cancelText"></button></div></div></div></div>'
    }
}
]),
angular.module("directives.resetfield", []).directive("resetField", ["$compile", "$timeout", "utils", function(e, t, a) {
    return {
        require: "ngModel",
        scope: {
            resetPage: "&resetField"
        },
        link: function(o, n, r, i) {
            var s = /text|search|tel|url|email|password|number/i;
            if ("INPUT" === n[0].nodeName) {
                if (!s.test(r.type))
                    throw new Error("Invalid input type for resetField: " + r.type)
            } else if ("TEXTAREA" !== n[0].nodeName)
                throw new Error("resetField is limited to input and textarea elements");
            var l = e('<i ng-show="enabled" ng-click="reset();" class="icon ion-android-close reset-field-icon"></i>')(o);
            n.addClass("reset-field"),
            n.after(l),
            o.reset = function() {
                i.$setViewValue(null ),
                i.$render();
                var e = a.checkBrowser("uc");
                e || t(function() {
                    n[0].focus()
                }
                , 0, !1),
                o.enabled = !1,
                o.resetPage()
            }
            ,
            n.bind("input", function() {}
            ).bind("focus", function() {
                t(function() {
                    o.enabled = !i.$isEmpty(n.val()),
                    o.$apply()
                }
                , 0, !1)
            }
            ).bind("keyup", function() {
                t(function() {
                    o.enabled = !i.$isEmpty(n.val()),
                    o.$apply()
                }
                , 0, !1)
            }
            ).bind("blur", function() {
                t(function() {
                    o.enabled = !1,
                    o.$apply()
                }
                , 0, !1)
            }
            )
        }
    }
}
]),
angular.module("directives.lock", []).directive("curLock", function() {
    return {
        restrict: "AE",
        template: '<div class="m-lock"><img ng-src="{{CDNURL+\'images/lock.png\'}}"><h4>{{"Common_TipsUserLock"|translate}}</h4><p>{{"Common_TipsCallCustomerService"|translate}}</p></div>'
    }
}
),
angular.module("controllers.idx", []).controller("IndexCtrl", ["$scope", "$rootScope", "$filter", "$curActionIcon", "$ionicLoading", "$ionicSlideBoxDelegate", "$window", "$state", "$timeout", "$cordovaLocalStorage", "$ionicScrollDelegate", "$ionicPopup","utils", "PopoverService", "AuthService", "NoticeService", "AppConfig", "AppLanguage", "Constants", "QuickDialog", "BackService", "AccountService", function(e, t, a, o, n, r, i, s, l, m, c, z,u, d, b, p, C, _, g, f, h, L) {
    function M() {
        2 == e.imgs.length && (w += 1,
        w > 1 && (w = 0),
        r.$getByHandle("slideLess") && (r.$getByHandle("slideLess").slide(w),
        T = l(M, y)))
    }
    e.islogin = b.isLoggedIn(),
    e.currentUser = b.currentUser,
    e.username = e.currentUser.UserName || "",
    e.logo = C.getLogo(),
    e.regstep = C.getRegStep(),
    e.tryOpen = C.getTryPlaySet(),
    e.currentLanguage = _.get(),
    e.notices = [],
    e.imgs = [],
    e.openGameList = [],
    e.stateGameList = [],
    e.msgCount = 0,
    e.siteName = C.getSiteName(e.currentLanguage) || "",
    e.computerLink = C.getComputerUrl(),
    e.serviceLink = C.getServiceUrl(),
    e.addedHome = !1,
    e.desktopIcon = C.getIcon(),
    e.langs = [],
    e.isAndroid = "android" == u.getBrowser(),
    e.navCount = 0,
    e.gameRepairArr = [];
    var T, y = 3e3, w = 0, D = C.getLangs();
    if (e.goMessage = function() {
        s.go("notice", {
            type: 0
        })
    }
    ,
    e.updateSlider = function() {
        r.update()
    }
    ,
    h.setType("index"),
    e.islogin) {
        e.openGameList = e.currentUser.GameClassList;
        b.checkToken().then(function(t) {
            108 == t.ErrorCode && (e.islogin = !1,
            e.currentUser = {},
            e.username = "")
        }
        );
        e.navCount = 1;
        var v = 6 - e.openGameList.length - 1;
        2 == e.currentUser.TestState && (e.navCount++,
        v -= 1);
        for (var P = 0; v > P; P++)
            e.gameRepairArr.push(P)
    } else {
        e.openGameList = C.getOpenGameList();
        var v = 6 - e.openGameList.length - 1;
        e.navCount = 2,
        e.tryOpen && (e.navCount++,
        v -= 1),
        D.length > 1 && e.navCount++;
        for (var P = 0; v > P; P++)
            e.gameRepairArr.push(P)
    }
    for (var P = 0; P < D.length; P++)
        e.langs.push({
            value: D[P],
            text: "<img src='" + C.CDNURL + "images/" + D[P] + ".png' data_id='" + D[P] + "'  style='width:30px;' />"
        });
    e.popover = new d({
        data: e.langs,
        label: "text",
        width: "60",
        height: "auto",
        scope: e,
        onSelect: function(t, a) {
            a.value && a.value != e.currentLanguage && e.changeLang(a.value)
        }
    });
    var A = function() {
        var i = angular.element(document.querySelectorAll(".bar-header.bar-energized")).addClass("topbarbg");
            var i = angular.element(document.querySelector(".bar-header.bar-energized"));
            angular.forEach(i, function (t) {
                    var a = angular.element(t);
                    a.attr("style", "background:"+C.getTopbarBgColor());
                }
            );

        var t = function(t) {
            0 == t.ErrorCode && (e.imgs = [],
            angular.forEach(t.Data.PlayImageList, function(t) {
                if (t.ActivityPlayImage) {
                    var a = {};
                    a.src = t.ActivityPlayImage,
                    e.imgs.push(a)
                }
            }
            ),
            T = l(M, y))
        }
        ;
        e.imgs = [],
        C.getPlayImages(e.currentLanguage).then(t)
    }
      , S = function() {
        var t = function(t) {
            0 == t.ErrorCode && (e.stateGameList = t.Data)
        }
        ;
        C.getGameState().then(t)
    }
      , I = function() {
        var t = function(t) {
            0 == t.ErrorCode && (e.notices = [],
            angular.forEach(t.Data, function(t) {
                e.notices.push({
                    content: t.Content
                })
            }
            ))
        }
        ;
        p.getNotice({
            Total: 3,
            NoticeType: 1,
            LanguageNo: e.currentLanguage
        }).then(t)
    }
      , k = function() {
        var t = function(t) {
            0 == t.ErrorCode && (e.msgCount = t.Data.NoticeCount + t.Data.MessageCount,
            e.msgCount > 99 && (e.msgCount = 99),
            e.goMessage = function() {
                t.Data.NoticeCount > 0 ? s.go("notice", {
                    type: 0
                }) : t.Data.MessageCount > 0 ? s.go("notice", {
                    type: 1
                }) : s.go("notice", {
                    type: 0
                })
            }
            )
        }
        ;
        p.getMessageCount().then(t)
    }
    ;
    A(),
    S(),
    I(),
    e.islogin && 2 != e.currentUser.TestState && k(),
    e.inGame = function(t, o) {
     //   if (b.isLoggedIn()) {
            window.location = "/lot/#/sso/" + t + "/" + o
      //  } else
       //     s.go("login"),
        //    event.preventDefault()
    }
    ,
    e.service = function() {
        i.open(C.getServiceUrl())
    }
    ,
    e.changeLang = function(t) {
        f.loading(),
        _.set(t).then(function() {
            e.currentLanguage = _.get(),
            s.reload()
        }
        , function() {
            f.hideLoading()
        }
        )
    }
    ,
    e.playImgDrag = function(e) {
        var t = -1 * e.gesture.deltaY;
        c.$getByHandle("mainScroll").scrollBy(0, t, !0)
    }
    ;
    var B = function() {
        if (navigator.userAgent.toLocaleLowerCase().indexOf("iphone os") > -1 && !u.checkBrowser("chrome") && !u.checkBrowser("uc") && !u.checkBrowser("qq")) {
            var t = u.cookie("sah")
              , a = m.getItem("APP_ADDHOME");
            t || a || (e.addedHome = !0)
        }
    }
    ;
    e.closeAddHome = function() {
        u.cookie("sah", 1),
        m.setItem("APP_ADDHOME", 1),
        e.addedHome = !1
    }
    ,
    B(),
    e.getTestMember = function() {
        f.loading();
        var e = {
            SiteNo: C.getSiteNo(),
            LanguageNo: _.get(),
            MemberName: "",
            Pwd: ""
        }
          , t = u.cookie("tester");
        if (t) {
            var o = t.split("|");
            e = angular.extend(e, {
                MemberName: o[0],
                Pwd: u.simpleCrypto(o[1])
            }),
            b.login(e).then(function(e) {
                f.hideLoading(),
                0 == e.ErrorCode ? f.tips(a("translate")("Member_TipsLoginSuccess"), null , function() {
                    s.reload()
                }
                ) : (u.cookie("tester", "-1", -60),
                f.timeout())
            }
            )
        } else {
            var n = function(t) {
                0 == t.ErrorCode ? (u.cookie("tester", t.Data.MemberName + "|" + u.simpleCrypto(t.Data.Pwd, "encode"), 30),
                e = angular.extend(e, {
                    MemberName: t.Data.MemberName,
                    Pwd: t.Data.Pwd
                }),
                b.login(e).then(function(e) {
                    f.hideLoading(),
                    0 == e.ErrorCode ? f.tips(a("translate")("Member_TipsLoginSuccess"), null , function() {
                        s.reload()
                    }
                    ) : (u.cookie("tester", "-1", -60),
                    f.timeout())
                }
                )) : 3 == t.ErrorCode ? (f.hideLoading(),
                f.tips(a("translate")("Member_TipsTryUseUp"))) : (f.hideLoading(),
                f.tips(a("translate")("Member_TipsTryGetFail")))
            }
            ;
            L.getTestMember().then(n)
        }
    }
    ,
    e.$on("$destroy", function() {
        e.popover.remove(),
        l.cancel(T)
    }
    ),
    e.logout = function() {
        z.confirm({
            title: a("translate")("Common_TipsConfirmOutLogin"),
            cancelText: a("translate")("Common_ButtonCancel"),
            cancelType: "button-light",
            okText: a("translate")("Common_ButtonSure")
        }).then(function(e) {
            e && (b.logout(),
                s.reload())
        })
    }
}
]).controller("EGameCtrl", ["$scope", "$filter", "$state", "$stateParams", "$ionicPopup", "$ionicListDelegate", "$timeout", "AppConfig", "QuickDialog", "Constants", "AuthService","utils", "AccountService", function(e, t, a, o, n, r, i, s, l, m, c,uu,aa) {
// C.getEGames().then(t)
    var u = 180
        , d = ""
        , p = 0;
        e.egames = [],
        e.moreEgameCanBeLoaded = !1,
        e.egamesCount = 0,
        e.ansycEgameLoaded = !0,
        e.refreshData = !1,
        e.currentUser = c.currentUser,
        e.ineGame = function(o, n) { //进入游戏
            a.go("inegame", {
                gameId: o,
                gameType: n
            })
        }
    ;/*
    var C = function() {
            var t = function(t) {
                    0 == t.ErrorCode && (e.mailCount = t.Data.MessageCount,
                        e.noticeCount = t.Data.NoticeCount)
                }
                ;
            s.getMessageCount().then(t)
        }
        ;
    C();*/
    var _ = function() {
            function t() {
                e.ansycEgameLoaded = !0,
                    e.moreEgameCanBeLoaded = !1,
                e.egames.length > 0 && e.egames.length % u == 0 && i(function () {
                        e.moreEgameCanBeLoaded = !0
                    }
                    , 2e3),
                    e.$broadcast("scroll.infiniteScrollComplete"),
                    e.$broadcast("scroll.refreshComplete"),
                    i(function () {
                            e.refreshData = !1
                        }
                        , 500)
            }

            var a = function (t) {
                    if (0 == t.ErrorCode) {
                        p = t.Data.CurrentPage,
                            e.moreEgameCanBeLoaded = t.Data.List.length < u ? !1 : !0;
                        var a = "";
                        angular.forEach(t.Data.List, function (t) {
                                    e.egames.push(t)
                            }
                        )
                    } else
                        e.moreEgameCanBeLoaded = !1,
                            l.timeout();
                    e.ansycEgameLoaded = !0,
                        e.$broadcast("scroll.infiniteScrollComplete"),
                        e.$broadcast("scroll.refreshComplete"),
                        i(function () {
                                e.refreshData = !1
                            }
                            , 500)
                }
                ;
            //getMoreEgames
            s.getMoreEGames({
                PageSize: u,
                CurrentPage: p+1
            }).then(a, t)
        }
        ;
        e.ansycEgameLoaded = !1, _();
        e.loadMoreEgame = function() {
            _()
        }
        ,
        e.egameRefresh = function() {
            !e.refreshData && e.ansycEgameLoaded && (e.refreshData = !0,
                d = "",
                e.egames = [],
                _()/*,C()*/)
        }/*
            , e.ineGame = function(tt, oo) {
            aa.GetInGameRedirect({
                GameId: tt,
                GameType: oo,
                GameClassID:61
            }).then(function(i) {
                    l.hideLoading(),
                        0 == i.ErrorCode ? (i.Data.Redirect += '&lobbyURL='+ encodeURIComponent(v(document.getElementById("APIURL").value) + '/#/egame') + '&bankingURL=' + encodeURIComponent(v(document.getElementById("APIURL").value) + '/#/transfer'),uu.openWindow(i.Data.Redirect)) : l.tips(t("translate")("Common_TipsInGameFail"))
                }
                , function() {
                    l.hideLoading(),
                       l.tips(t("translate")("Common_TipsInGameFail"))
                }
            );
            var v = function (t) {
               t =   t.substring(0, t.lastIndexOf("/"))
                return -1 != t.indexOf(".") ? window.location.protocol + "//m." + t.substring(t.indexOf(".") + 1) : window.location.href
            }
            }*/

}
]).controller("InGameCtrl", ["$scope", "QuickDialog", "$filter", "AccountService", "$stateParams", function(e, t, a, o, n) {
    var v = function (t) {
        t =   t.substring(0, t.lastIndexOf("/"))
        return -1 != t.indexOf(".") ? window.location.protocol + "//m." + t.substring(t.indexOf(".") + 1) : window.location.href
    }
    t.loading(3e4),
    o.GetInGameRedirect({
        GameClassID: n.gameClassId,
        GameID: n.gameId,
        PageStyle: ""
    }).then(function(e) {
        t.hideLoading(),
            0 == i.ErrorCode ? (i.Data.Redirect += '&lobbyURL='+ encodeURIComponent(v(document.getElementById("APIURL").value) + '/#/egame') + '&bankingURL=' + encodeURIComponent(v(document.getElementById("APIURL").value) + '/#/transfer'),window.location =i.Data.Redirect) : t.tips(a("translate")("Common_TipsInGameFail"))
    }
    , function() {
        t.hideLoading(),
        t.tips(a("translate")("Common_TipsInGameFail"))
    }
    )
}
]).controller("AppInfoCtrl", ["$scope", "QuickDialog", "$filter", "AccountService","utils", "$stateParams", function(e, t, a, o,u, n) {
    e.egameURL = "",
        e.appTitle = "";
        t.loading(3e4),
        o.GetInGameRedirect({
            GameClassID: "bbin" == n.name ?(e.appTitle = a("translate")("Common_AppBbin_Course") , 5) : ("lebo" == n.name)  ? (e.appTitle = a("translate")("Common_AppLebo_Course"),4) : 0,
            GameID: 0 ,
            PageStyle: ""
        }).then(function(i) {
                t.hideLoading(),
                    0 == i.ErrorCode ?e.egameURL = i.Data.Redirect : t.tips(a("translate")("Common_TipsInGameFail")) && s.go("index")
            }
            , function() {
                t.hideLoading(),
                    t.tips(a("translate")("Common_TipsInGameFail"))
            }
        )
}
]).controller("IneGameCtrl", ["$scope", "QuickDialog", "$filter", "AccountService","utils", "$stateParams", function(e, t, a, o,u, n) {
    var v = function (t) {
        t =   t.substring(0, t.lastIndexOf("/"))
        return -1 != t.indexOf(".") ? window.location.protocol + "//m." + t.substring(t.indexOf(".") + 1) : window.location.href
    }
    e.egameURL = "",
    t.loading(3e4),
        o.GetInGameRedirect({
            GameId: n.gameId,
            GameType: n.gameType,
            GameClassID:61
        }).then(function(i) {
                t.hideLoading(),
                i.Data.Redirect += '&lobbyURL='+ encodeURIComponent(v(document.getElementById("APIURL").value) + '/#/egame') + '&bankingURL=' + encodeURIComponent(v(document.getElementById("APIURL").value) + '/#/transfer'),
                0 == i.ErrorCode ? window.location = i.Data.Redirect : t.tips(a("translate")("Common_TipsInGameFail")) && s.go("egame")
            }
            , function() {
                t.hideLoading(),
                    t.tips(a("translate")("Common_TipsInGameFail"))
            }
        );
}
]).controller("InGameAppCtrl", ["$scope", "QuickDialog", "$filter", "AccountService", "$stateParams","$state", function(e, t, a, o, n,s) {
    t.loading(3e4),
    o.GetInGameRedirect({
        DAScode: n.dascode,
    }).then(function(e) {
        t.hideLoading()
        0 == e.ErrorCode ? window.location = e.Data.Redirect : t.tips(a("translate")("Common_TipsInGameFail"))
    }
    , function() {
        t.hideLoading(),
        t.tips(a("translate")("Common_TipsInGameFail"))
    }
    )
}
]).controller("ExtCtrl", ["$scope", "$state", "$filter", "utils", "AuthService", "RegService", "QuickDialog", "AppConfig", "AppLanguage", "AccountService", function(e, t, a, o, n, r, i, s, l, m) {
    var c = t.params.code;
    c && c.length > 0 && r.setPromoCodeCache(c),
    e.getTestMember = function() {
        i.loading();
        var e = {
            SiteNo: s.getSiteNo(),
            LanguageNo: l.get(),
            MemberName: "",
            Pwd: ""
        }
          , r = o.cookie("tester");
        if (r) {
            var c = r.split("|");
            e = angular.extend(e, {
                MemberName: c[0],
                Pwd: o.simpleCrypto(c[1])
            }),
            n.login(e).then(function(e) {
                i.hideLoading(),
                0 == e.ErrorCode ? (i.tips(a("translate")("Member_TipsLoginSuccess")),
                t.go("index")) : (o.cookie("tester", "-1", -60),
                i.timeout())
            }
            )
        } else {
            var u = function(r) {
                0 == r.ErrorCode ? (o.cookie("tester", r.Data.MemberName + "|" + o.simpleCrypto(r.Data.Pwd, "encode"), 30),
                e = angular.extend(e, {
                    MemberName: r.Data.MemberName,
                    Pwd: r.Data.Pwd
                }),
                n.login(e).then(function(e) {
                    i.hideLoading(),
                    0 == e.ErrorCode ? (i.tips(a("translate")("Member_TipsLoginSuccess")),
                    t.go("index")) : (o.cookie("tester", "-1", -60),
                    i.timeout())
                }
                )) : 3 == r.ErrorCode ? (i.hideLoading(),
                i.tips(a("translate")("Member_TipsTryUseUp"))) : (i.hideLoading(),
                i.tips(a("translate")("Member_TipsTryGetFail")))
            }
            ;
            m.getTestMember().then(u)
        }
    }
    ,
    e.inGame = function(e, t) {
        e > 0 && (t = t || "",
            window.location = "#/ingame/" + e + "/" + t)
    }
}
]).controller("TryGameCtrl", ["$scope", "$stateParams", "$filter", "utils", "AuthService", "QuickDialog", "AccountService", "AppConfig", "AppLanguage", function(e, t, a, o, n, r, i, s, l) {
    var m = t.gameClassId
      , c = t.gameId
      , u = {
        SiteNo: s.getSiteNo(),
        LanguageNo: l.get(),
        MemberName: "",
        Pwd: "",
        GameId: m
    };
    r.loading(3e4);
    var d = function(e) {
        0 == e.ErrorCode ? (o.cookie("tester", e.Data.MemberName + "|" + o.simpleCrypto(e.Data.Pwd, "encode"), 30),
        u = angular.extend(u, {
            MemberName: e.Data.MemberName,
            Pwd: e.Data.Pwd
        }),
        o.cookie("transfer", !0, 60),
        n.loginAndTransfer(u).then(p, C)) : 3 == e.ErrorCode ? (r.hideLoading(),
        r.tips(a("translate")("Member_TipsTryUseUp"))) : (r.hideLoading(),
        r.tips(a("translate")("Member_TipsTryGetFail")))
    }
      , b = function() {
        r.hideLoading(),
        r.tips(a("translate")("Member_TipsTryGetFail"))
    }
      , p = function(e) {
        0 == e.ErrorCode ? i.GetInGameRedirect({
            GameClassID: m,
            GameID: c,
            PageStyle: ""
        }).then(_, g) : (o.cookie("tester", "-1", -60),
        r.hideLoading(),
        r.timeout())
    }
      , C = function() {
        r.hideLoading(),
        r.timeout()
    }
      , _ = function(e) {
        r.hideLoading(),
        0 == e.ErrorCode ? window.location = e.Data.Redirect : r.tips(a("translate")("Common_TipsInGameFail"))
    }
      , g = function() {
        r.hideLoading(),
        r.tips(a("translate")("Common_TipsInGameFail"))
    }
      , f = function() {
        var e = n.isLoggedIn();
        if (e)
            i.GetInGameRedirect({
                GameClassID: m,
                GameID: c,
                PageStyle: ""
            }).then(_, g);
        else {
            var t = o.cookie("tester");
            if (t) {
                var a = t.split("|");
                u = angular.extend(u, {
                    MemberName: a[0],
                    Pwd: o.simpleCrypto(a[1])
                });
                var r = o.cookie("transfer");
                r ? n.login(u).then(p, C) : (o.cookie("transfer", !0, 60),
                n.loginAndTransfer(u).then(p, C))
            } else
                i.getTestMember().then(d, b)
        }
    }
    ;
    f()
}
]).controller("MaintainCtrl", ["$scope", "$state", "$stateParams", "AppConfig", function(e, t, a, o) {
    e.start = a.start,
    e.end = a.end;
    var n = function(a) {
        0 == a.ErrorCode ? t.go("index") : 102 == a.ErrorCode && a.Data && (e.start = a.Data.CloseTime,
        e.end = a.Data.OpenTime)
    }
    ;
    o.getSystemMaintenance().then(n)
}
]),
angular.module("controllers.account", []).controller("LoginCtrl", ["$scope", "$state", "$filter", "$timeout", "utils", "md5", "AuthService", "AppConfig", "AppLanguage", "QuickDialog", "Constants", "AccountService", function(e, t, a, o, n, r, i, s, l, m, c, u) {
    e.loginData = {
        SiteNo: s.getSiteNo(),
        LanguageNo: l.get(),
        MemberName: u.getUserNameFromCache()
    }, e.regstep = s.getRegStep(),
    e.getTestMember = function() {
        m.loading();
        var o = n.cookie("tester");
        if (o) {
            var r = o.split("|")
              , s = {};
            angular.extend(s, e.loginData),
            angular.extend(s, {
                MemberName: r[0],
                Pwd: n.simpleCrypto(r[1])
            }),
            i.login(s).then(function(e) {
                m.hideLoading(),
                0 == e.ErrorCode ? (m.tips(a("translate")("Member_TipsLoginSuccess")),
                t.go("index")) : (n.cookie("tester", "-1", -60),
                m.timeout())
            }
            )
        } else {
            var l = function(o) {
                if (0 == o.ErrorCode) {
                    n.cookie("tester", o.Data.MemberName + "|" + n.simpleCrypto(o.Data.Pwd, "encode"), 30);
                    var r = {};
                    angular.extend(r, e.loginData),
                    angular.extend(r, {
                        MemberName: o.Data.MemberName,
                        Pwd: o.Data.Pwd
                    }),
                    i.login(r).then(function(e) {
                        m.hideLoading(),
                        0 == e.ErrorCode ? (m.tips(a("translate")("Member_TipsLoginSuccess")),
                        t.go("index")) : (n.cookie("tester", "-1", -60),
                        m.timeout())
                    }
                    )
                } else
                    3 == o.ErrorCode ? (m.hideLoading(),
                    m.tips(a("translate")("Member_TipsTryUseUp"))) : (m.hideLoading(),
                    m.tips(a("translate")("Member_TipsTryGetFail")))
            }
            ;
            u.getTestMember().then(l)
        }
    }
    ,
    e.login = function() {
        if (!e.loginData.MemberName || "" == e.loginData.MemberName)
            return void m.tips(a("translate")("Member_TipsInputAccount"));
        if (!e.loginData.Pwd || "" == e.loginData.Pwd)
            return void m.tips(a("translate")("Member_TipsInputPassword"));
        var o = /^[a-zA-Z_0-9]{3,20}$/;
        if (!o.test(e.loginData.MemberName))
            return void m.tips(a("translate")("Common_TipsUserNameFormatWrong"));
        if (!o.test(e.loginData.Pwd))
            return void m.tips(a("translate")("Common_TipsPasswordFormatWrong"));
        m.loadAndDisable("btnLogin", 15e3);
        var n = function(o) {
            m.hideLoadAndDisable("btnLogin", !0),
            0 == o.ErrorCode ? (u.setUserNameCache(e.loginData.MemberName),
            e.loginData.MemberName = "",
            e.loginData.Pwd = "",
            t.go("index")) : m.tips(1 == o.ErrorCode ? a("translate")("Common_TipsAccountIsNotExists") : 2 == o.ErrorCode ? a("translate")("Member_TipsLoginError") : 3 == o.ErrorCode ? a("translate")("Common_TipsAccountStopped") : 4 == o.ErrorCode ? a("translate")("Common_TipsLoginFail") : 101 == o.ErrorCode ? a("translate")("Common_TipsExecuteFail") : 107 == o.ErrorCode ? a("translate")("Common_TipsParameterError") : a("translate")("Common_TipsExecuteFail"))
        }
          , r = function() {
            m.hideLoadAndDisable("btnLogin")
        }
        ;
        i.login(e.loginData).then(n, r)
    }
}
]).controller("FgtPwdCtrl", ["$scope", "$state", "$filter", "$ionicSlideBoxDelegate", "$timeout", "$ionicScrollDelegate", "utils", "AccountService", "Constants", "QuickDialog", "AppConfig", "AppLanguage", "ChooseService", function(e, t, a, o, n, r, i, s, l, m, c, u, d) {
    angular.element(document).ready(function() {
        e.ucFlag = i.checkBrowser("uc");
        var t = "android" == i.getBrowser();
        if (t && angular.element(document.querySelectorAll(".scroll")).removeClass("scroll"),
        e.ucFlag) {
            var a, o = angular.element(document.querySelectorAll(".uc-set-scroll"));
            o.on("click", function() {
                var e = angular.element(this).parent().prop("offsetTop");
                a = r.getScrollPosition(),
                r.scrollTo(0, e - 50)
            }
            ),
            o.on("blur", function() {
                r.scrollTo(a.left, a.top)
            }
            )
        }
    }
    ),
    e.fgtPwdData = {
        CountryAreaCode: "86"
    },
    e.resetPwdData = {},
    e.countryData = [];
    {
        var b, p = Date.now(), C = "";
        u.get()
    }
    e.CaptchaSrc = l.API_PATH + "/IPage/Captcha?key=" + p,
    e.disableSwipe = function() {
        o.slide(0),
        o.enableSlide(!1)
    }
    ,
    e.goStep2 = function(t) {
        m.loadAndDisable("btnFgtPwd1");
        var n = function(t) {
            m.hideLoadAndDisable("btnFgtPwd1", !0),
            e.refreshCaptcha(),
            0 == t.ErrorCode ? (C = t.Data.ForgetPwdKey,
            o.slide(1)) : m.tips(1 == t.ErrorCode ? a("translate")("Common_TipsAccountIsNotRight") : 2 == t.ErrorCode ? a("translate")("Common_TipsPhoneIsNotRight") : 3 == t.ErrorCode ? a("translate")("Common_TipsDrawPasswordIsNotRight") : 4 == t.ErrorCode ? a("translate")("Common_TipsValidateCodeIsOverdue") : 5 == t.ErrorCode ? a("translate")("Common_TipsValidateCodeIsError") : 101 == t.ErrorCode ? a("translate")("Common_TipsPhoneIsNotRight") : a("translate")("Common_TipsEditPwdFail"))
        }
          , r = function() {
            m.hideLoadAndDisable("btnFgtPwd1")
        }
        ;
        angular.extend(t, {
            CaptChaKey: p,
            Phone: t.CountryAreaCode + " " + t.PhoneNum
        }),
        s.fgtPwd(t).then(n, r)
    }
    ,
    e.resetPwd = function(e) {
        m.loadAndDisable("btnFgtPwd2");
        var o = function(e) {
            m.hideLoadAndDisable("btnFgtPwd2", !0),
            0 == e.ErrorCode ? m.tips(a("translate")("Common_TipsEditPwdSuccess"), 3e3, function() {
                t.go("login")
            }
            ) : m.tips(1 == e.ErrorCode ? a("translate")("Common_TipsEditPasswordKeyIsOverdue") : 2 == e.ErrorCode ? a("translate")("Common_TipsAccountIsNotExists") : 101 == e.ErrorCode ? a("translate")("Common_TipsEditPwdFail") : a("translate")("Common_TipsEditPwdFail"))
        }
          , n = function() {
            m.hideLoadAndDisable("btnFgtPwd2")
        }
        ;
        angular.extend(e, {
            ChangePwdKey: C
        }),
        s.resetPwd(e).then(o, n)
    }
    ,
    e.refreshCaptcha = function() {
        p = Date.now(),
        e.CaptchaSrc = l.API_PATH + "/IPage/Captcha?key=" + p
    }
    ;
    var _ = function() {
        function t(t) {
            0 == t.ErrorCode && (e.countryData = t.Data)
        }
        c.getCountryData().then(t)
    }
    ;
    _(),
    e.countryChoose = function() {
        b = new d({
            data: e.countryData,
            selected: e.fgtPwdData.CountryAreaCode,
            scope: e,
            onSelect: function(t, a) {
                e.fgtPwdData.CountryAreaCode = a
            },
            onTap: function(t) {
                "" != e.fgtPwdData.CountryAreaCode && /^\d{0,5}$/.test(e.fgtPwdData.CountryAreaCode) || (m.tips(a("translate")("Common_LabelInputAreaNumber")),
                t.preventDefault())
            }
        })
    }
}
]).controller("CenterCtrl", ["$scope", "$state", "$window", "$timeout", "$filter", "AuthService", "utils", "$curActionIcon", "$ionicPopup", "AppConfig", "CashService", "AccountService", "Constants", "QuickDialog", "NoticeService", "BackService", function(e, tt, a, o, n, r, i, s, l, m, c, u, d, b, p, C) {
    e.logout = function() {
        l.confirm({
            title: n("translate")("Common_TipsConfirmOutLogin"),
            cancelText: n("translate")("Common_ButtonCancel"),
            cancelType: "button-light",
            okText: n("translate")("Common_ButtonSure")
        }).then(function(e) {
            e && (r.logout(),
            tt.go("index"))
        }
        )
    }
    , e.regstep = m.getRegStep(),
    e.currentUser = r.currentUser,
    e.wallet = "",
    e.loaded = !1,
    e.memberData = {},
    e.msgCount = 0,
    e.showLevel = m.getReturnMode(),
    e.serviceLink = m.getServiceUrl(),
    e.goMessage = function() {
        tt.go("notice", {
            type: 0
        })
    }
    ,
    C.setType("center"),
    e.getMember = function() {
        function t(t) {
            0 == t.ErrorCode && (e.memberData = t.Data)
        }
        u.getMember().then(t)
    }
    ,
    e.getWallet = function() {
        function t(t) {
            0 == t.ErrorCode && (e.wallet = parseFloat(t.WalletBalanceList[0].Balance).toFixed(2),
            e.loaded = !0) || 1 == t.ErrorCode && (r.logout(),tt.go("index"))
        }
        function a() {
            e.wallet = parseFloat(0).toFixed(2),
            e.loaded = !0
        }
        e.loaded = !1,
        c.getWallets({
            GameClassIDs: 0
        }).then(t, a)
    }
    ,
    e.getWallet(),
    e.getMember(),
    e.getGreeting = function() {
        var e = (new Date).getHours();
        return n("translate")(e >= 6 && 11 > e ? "Common_GoodMorning" : e >= 11 && 14 > e ? "Common_GoodAfternoon1" : e >= 14 && 18 > e ? "Common_GoodAfternoon2" : "Common_GoodNight")
    }
    ,
    e.share = function() {
        var e = (i.getBrowser(),
        s.show({
            buttons: [{
                icon: m.CDNURL + "images/icon-wechat.png",
                text: n("translate")("Common_TipsWeixinFriend")
            }, {
                icon: m.CDNURL + "images/icon-social.png",
                text: n("translate")("Common_TipsWeixinFriends")
            }, {
                icon: m.CDNURL + "images/icon-qq.png",
                text: n("translate")("Common_TipsPhoneQQ")
            }, {
                icon: m.CDNURL + "images/icon-note.png",
                text: n("translate")("Common_TipsSmallLetter")
            }],
            cancelText: n("translate")("Common_ButtonCancel"),
            buttonClicked: function(t) {
                return e(t),
                !1
            }
        }),
        function(e) {
            var t = [{
                "in": ["weixin://"],
                download: "itms-apps://itunes.apple.com/cn/app/wei-xin/id414478124?mt=8",
                soft: n("translate")("Common_TipsWeixin")
            }, {
                "in": ["weixin://"],
                download: "itms-apps://itunes.apple.com/cn/app/wei-xin/id414478124?mt=8",
                soft: n("translate")("Common_TipsWeixin")
            }, {
                "in": ["qapp://", "mqq://"],
                download: "itms-apps://itunes.apple.com/cn/app/qq-2011/id444934666?mt=8",
                soft: "QQ"
            }, {
                "in": ["sms:"],
                download: ""
            }]
              , a = []
              , o = 1e3
              , r = !0;
            setTimeout(function() {
                for (var e = 0; e < a.length; e++)
                    document.body.removeChild(a[e])
            }
            , 2e3);
            for (var i = Date.now(), s = 0; s < t[e].in.length; s++) {
                var l = document.createElement("iframe");
                l.setAttribute("src", t[e].in[s]),
                l.setAttribute("style", "display:none"),
                document.body.appendChild(l),
                a.push(l)
            }
            setTimeout(function() {
                var e = Date.now();
                (!i || o + 100 > e - i) && (r = !1)
            }
            , o)
        }
        )
    }
    ;
    var _ = function() {
        var a = function(a) {
            0 == a.ErrorCode && (e.msgCount = a.Data.NoticeCount + a.Data.MessageCount,
            e.msgCount > 99 && (e.msgCount = 99),
            e.goMessage = function() {
                a.Data.NoticeCount > 0 ? tt.go("notice", {
                    type: 0
                }) : a.Data.MessageCount > 0 ? tt.go("notice", {
                    type: 1
                }) : tt.go("notice", {
                    type: 0
                })
            }
            )
        }
        ;
        2 != e.currentUser.TestState && p.getMessageCount().then(a);
        r.checkToken().then(function(t) {
            108 == t.ErrorCode && (e.islogin = !1,
                e.currentUser = {},
                e.username = "")
        })
    }
    ;
    _(),
    e.goInfo = function() {
        2 == e.currentUser.TestState ? (g(),
        e.denyInfo = !0,
        o(function() {
            e.denyInfo = !1
        }
        , 5e3)) : tt.go("info")
    }
    ,
    e.goMdfPwd = function() {
        2 == e.currentUser.TestState ? (g(),
        e.denyMdfPwd = !0,
        o(function() {
            e.denyMdfPwd = !1
        }
        , 5e3)) : tt.go("mdfpwd")
    }
    ,
    e.goBank = function() {
        2 == e.currentUser.TestState ? (g(),
        e.denyBank = !0,
        o(function() {
            e.denyBank = !1
        }
        , 5e3)) : tt.go("bank")
    }
    ,
    e.goTransfer = function() {
        2 == e.currentUser.TestState ? (g(),
            e.denyTransfe = !0,
            o(function() {
                e.denyTransfe = !1
            }, 5e3)) : tt.go("transfer")
        }
    ,
    e.goDeposit = function() {
        2 == e.currentUser.TestState ? (g(),
        e.denyDeposit = !0,
        o(function() {
            e.denyDeposit = !1
        }
        , 5e3)) : tt.go("deposit", {
            type: 0
        })
    }
    ,
    e.goWeek = function() {
       /* 2 == e.currentUser.TestState ? (g(),
        e.denyWeek = !0,
        o(function() {
            e.denyWeek = !1
        }
        , 5e3)) : */
        tt.go("week")
    }
    ;
    var g = function() {
        e.denyInfo = !1,
        e.denyMdfPwd = !1,
        e.denyDeposit = !1,
        e.denyBank = !1,
        e.denyWeek = !1
        e.denyTransfe = !1
    }
}
]).controller("InfoCtrl", ["$scope", "$timeout", "$state", "AppConfig", "AuthService", "AccountService", "Constants", "QuickDialog", function(e, t, a, o, n, r, i, s) {
    e.memberData = {},
    e.ansycLoaded = !1,
    e.currentUser = n.currentUser,
    e.showLevel = o.getReturnMode();
    var l = function() {
        var t = function(t) {
            0 == t.ErrorCode ? e.memberData = t.Data : s.timeout(),
            e.ansycLoaded = !0
        }
        ;
        r.getMember().then(t)
    }
    ;
    l(),
    e.goEdit = function(e) {
        a.go("mdfinfo", {
            type: 2,
            data: encodeURIComponent(e)
        })
    }
}
]).controller("ShiWanReg", ["$scope", "$timeout", "$state", "$filter", "AppConfig", "AuthService", "AccountService", "Constants", "QuickDialog","utils", "AppLanguage", "RegService", function(e, t, a, f, o, n, r, i, s, q, l, z) {
    e.memberData = {
            SiteNo: o.getSiteNo(),
            LanguageNo: l.get(),
            MemberName: "",
            Pwd: ""
        }
    var l = function() {
        var t = function(t) {
            0 == t.ErrorCode ? e.memberData.MemberName = t.Data.MemberName : s.timeout()
        }
        ;
        r.getTestMemberName().then(t)
    }
    ;
    l(),
    e.checkPwdLevel = function(t) {
        e.pwdLevel = 0,
        t && "" != t && t.length >= 6 && t.length <= 20 && (/\d/.test(t) && e.pwdLevel++,
        /[a-z]+/.test(t) && e.pwdLevel++,
        /[A-Z]+/.test(t) && e.pwdLevel++,
        /[_]+/.test(t) && e.pwdLevel++)
    },
    e.getTestMember = function(memberData) {
        s.loading();
        var z = q.cookie("tester");
        /*if (z) {
            var c = z.split("|");
            e.memberData = angular.extend(e.memberData, {
                MemberName: c[0],
                Pwd: q.simpleCrypto(c[1])
            }),
            n.login(e.memberData).then(function(e) {
                s.hideLoading(),
                0 == e.ErrorCode ? (s.tips(f("translate")("Member_TipsLoginSuccess")),
                a.go("index")) : (q.cookie("tester", "-1", -60),
                s.timeout())
            }
            )
        } else {*/
            var u = function(r) {
                0 == r.ErrorCode ? (q.cookie("tester", r.Data.MemberName + "|" + q.simpleCrypto(r.Data.Pwd, "encode"), 30),
                e.memberData = angular.extend(e.memberData, {
                    MemberName: r.Data.MemberName,
                    Pwd: r.Data.Pwd
                }),
                n.login(e.memberData).then(function(e) {
                    s.hideLoading(),
                    0 == e.ErrorCode ? (s.tips(f("translate")("Member_TipsLoginSuccess")),
                    a.go("index")) : (q.cookie("tester", "-1", -60),
                    s.timeout())
                }
                )) : 3 == r.ErrorCode ? (s.hideLoading(),
                s.tips(f("translate")("Member_TipsTryUseUp"))) : (s.hideLoading(),
                s.tips(f("translate")("Member_TipsTryGetFail")))
            }
            ;
            r.getTestMember(e.memberData).then(u)
        /*}*/
    }

}
]).controller("InfoCtrl", ["$scope", "$timeout", "$state", "AppConfig", "AuthService", "AccountService", "Constants", "QuickDialog", function(e, t, a, o, n, r, i, s) {
    e.memberData = {},
        e.ansycLoaded = !1,
        e.currentUser = n.currentUser,
        e.showLevel = o.getReturnMode();
    var l = function() {
            var t = function(t) {
                    0 == t.ErrorCode ? e.memberData = t.Data : s.timeout(),
                        e.ansycLoaded = !0
                }
                ;
            r.getMember().then(t)
        }
        ;
    l(),
        e.goEdit = function(e) {
            a.go("mdfinfo", {
                type: 2,
                data: encodeURIComponent(e)
            })
        }
}
]).controller("ErrorCtrl", [ "$state", function(t) {
    t.go("index")
}//
]).controller("MdfInfoCtrl", ["$scope", "$state", "$filter", "AccountService", "Constants", "QuickDialog", function(e, t, a, o, n, r) {
    e.mdfInfoData = {},
    e.type = t.params.type,
    1 == e.type ? e.mdfInfoData.UpdateNickName = t.params.data : e.mdfInfoData.UpdateEMail = decodeURIComponent(t.params.data),
    e.mdfNickName = function(e) {
        function n(e) {
            r.hideLoading(),
            0 == e.ErrorCode ? r.tips(a("translate")("Common_ModifyNickSuccess"), 1500, function() {
                t.go("info")
            }
            ) : r.tips(a("translate")("Common_TipsModifyNickFail"))
        }
        return e.UpdateNickName && e.UpdateNickName.length > 20 ? void r.tips(a("translate")("Member_TipsNickNameTooLong")) : (r.loading(),
        void o.mdfInfo(e).then(n))
    }
    ,
    e.mdfEmail = function(e) {
        function n(e) {
            0 == e.ErrorCode ? r.tips(a("translate")("Common_ModifySuccess"), 1500, function() {
                t.go("info")
            }
            ) : r.tips(1 == e.ErrorCode ? a("translate")("Common_TipsEmailBeenRegister") : a("translate")("Common_TipsModifyFail"))
        }
        var i = /^([-\w\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return !i.test(e.UpdateEMail) || e.UpdateEMail.length > 25 ? void r.tips(a("translate")("Common_TipsEmailError")) : (r.loading(),
        void o.mdfInfo(e).then(n))
    }
}
]).controller("MdfPwdCtrl", ["$scope", "$state", "$filter", "$ionicScrollDelegate", "utils", "AccountService", "Constants", "AuthService", "QuickDialog", function(e, t, a, o, n, r, i, s, l) {
    angular.element(document).ready(function() {
        e.ucFlag = n.checkBrowser("uc");
        var t = "android" == n.getBrowser();
        if (t && angular.element(document.querySelectorAll(".scroll")).removeClass("scroll"),
        e.ucFlag) {
            var a, r = angular.element(document.querySelectorAll(".uc-set-scroll"));
            r.on("click", function() {
                var e = angular.element(this).parent().prop("offsetTop");
                a = o.getScrollPosition(),
                o.scrollTo(0, e - 50)
            }
            ),
            r.on("blur", function() {
                o.scrollTo(a.left, a.top)
            }
            )
        }
    }
    ),
    e.mdfLoginPwd = function(e) {
        function o(e) {
            l.hideLoading();
            var o = e.ErrorCode;
            switch (o) {
            case 0:
                l.tips(a("translate")("Common_TipsModifyPwdSuccess"), 1500, function() {
                    t.go("center")
                }
                );
                break;
            case 108:
                l.tips(a("translate")("Common_TipsLoginStateBeenLose"), 1500, function() {
                    t.go("login")
                }
                );
                break;
            case 2:
                l.tips(a("translate")("Common_TipsOldPwdError"));
                break;
            case 3:
                l.tips(a("translate")("Common_TipsNewOldPwdNotTheSame"));
                break;
            case 4:
                l.tips(a("translate")("Common_ValidPasswordSame"));
                break;
            default:
                l.tips(a("translate")("Common_TipsEditPwdFail"))
            }
        }
        if (l.loading(),
        angular.isDefined(e)) {
            if (e.NewLoginPwd == s.currentUser.UserName)
                return void l.tips(a("translate")("Common_ValidPasswordSame"));
            var n = {
                OldLoginPwd: e.OldLoginPwd || "",
                NewLoginPwd: e.NewLoginPwd || ""
            };
            r.mdfPwd(n).then(o)
        }
    }
    ,
        e.parseNum = function (a) {
            a.target.value = parseInt(a.target.value);
                "NaN" == a.target.value ? (a.target.value = "",
                    e.mdfWithPwdData.OldWithdrawPwd = "") : a.target.value.length > 4 && (a.target.value = "",
                    e.mdfWithPwdData.OldWithdrawPwd  = "")
        },
    e.mdfWithPwd = function(e) {
        function o(e) {
            l.hideLoading();
            var o = e.ErrorCode;
            switch (o) {
            case 0:
                l.tips(a("translate")("Common_TipsModifyPwdSuccess"), 1500, function() {
                    t.go("center")
                }
                );
                break;
            case 108:
                l.tips(a("translate")("Common_TipsLoginStateBeenLose"), 1500, function() {
                    t.go("login")
                }
                );
                break;
            case 2:
                l.tips(a("translate")("Common_TipsOldPwdError"));
                break;
            case 3:
                l.tips(a("translate")("Common_TipsNewOldPwdNotTheSame"));
                break;
            default:
                l.tips(a("translate")("Common_TipsEditPwdFail"))
            }
        }
        if (l.loading(),
        angular.isDefined(e)) {
            var n = {
                OldWithdrawPwd: e.OldWithdrawPwd || "",
                NewWithDrawPwd: e.NewWithDrawPwd || ""
            };
            r.mdfPwd(n).then(o)
        }
    }
}
]),
angular.module("controllers.notice", []).controller("NoticeCtrl", ["$scope", "$filter", "$state", "$stateParams", "$ionicPopup", "$ionicListDelegate", "$timeout", "NoticeService", "QuickDialog", "Constants", "AuthService", function(e, t, a, o, n, r, i, s, l, m, c) {
    var u = 10
      , d = ""
      , b = ""
      , p = 1;
    e.type = o.type,
    e.notices = [],
    e.mails = [],
    e.showNotice = !0,
    e.showMail = !0,
    e.moreNoticeCanBeLoaded = !1,
    e.moreMailCanBeLoaded = !1,
    e.mailCount = 0,
    e.noticeCount = 0,
    e.ansycNoticeLoaded = !0,
    e.ansycMailLoaded = !0,
    e.refreshData = !1,
    e.currentUser = c.currentUser,
    e.showDetail = function(o, n, r) {
        1 == o ? (e.showdata = t("filter")(e.notices, {
            ID: parseInt(n)
        }, !0)[0],
        a.go("noticeDetail", {
            id: n,
            Content: e.showdata.Content,
            StartDateTime: e.showdata.StartDateTime
        })) : a.go("internal-mail-detail", {
            MessID: n,
            sendType: r
        })
    }
    ;
    var C = function() {
        var t = function(t) {
            0 == t.ErrorCode && (e.mailCount = t.Data.MessageCount,
            e.noticeCount = t.Data.NoticeCount)
        }
        ;
        s.getMessageCount().then(t)
    }
    ;
    C();
    var _ = function() {
        function t() {
            e.ansycNoticeLoaded = !0,
            e.moreNoticeCanBeLoaded = !1,
            e.notices.length > 0 && e.notices.length % u == 0 && i(function() {
                e.moreNoticeCanBeLoaded = !0
            }
            , 2e3),
            e.$broadcast("scroll.infiniteScrollComplete"),
            e.$broadcast("scroll.refreshComplete"),
            i(function() {
                e.refreshData = !1
            }
            , 500)
        }
        var a = function(t) {
            if (0 == t.ErrorCode) {
                d = t.Data.SearchData,
                e.moreNoticeCanBeLoaded = t.Data.List.length < u ? !1 : !0;
                var a = "";
                angular.forEach(t.Data.List, function(t) {
                    a = t.Content,
                    angular.extend(t, {
                        ShortContent: a
                    }),
                    e.notices.push(t)
                }
                )
            } else
                e.moreNoticeCanBeLoaded = !1,
                l.timeout();
            e.ansycNoticeLoaded = !0,
            e.$broadcast("scroll.infiniteScrollComplete"),
            e.$broadcast("scroll.refreshComplete"),
            i(function() {
                e.refreshData = !1
            }
            , 500)
        }
        ;
        s.getMoreNotice({
            PageSize: u,
            SearchData: d
        }).then(a, t)
    }
      , g = function() {
        function t() {
            e.ansycMailLoaded = !0,
            e.moreMailCanBeLoaded = !1,
            e.mails.length > 0 && e.mails.length % u == 0 && i(function() {
                e.moreMailCanBeLoaded = !0
            }
            , 2e3),
            e.$broadcast("scroll.infiniteScrollComplete"),
            e.$broadcast("scroll.refreshComplete"),
            i(function() {
                e.refreshData = !1
            }
            , 500)
        }
        e.mails.length > 0 && e.mails.length % u == 0 && (p = parseInt(e.mails.length / u) + 1);
        var a = function(t) {
            if (0 == t.ErrorCode) {
                b = t.Data.SearchTime,
                e.moreMailCanBeLoaded = t.Data.List.length < u ? !1 : !0;
                var a = "";
                angular.forEach(t.Data.List, function(t) {
                    a = t.SendContent,
                    angular.extend(t, {
                        ShortContent: a
                    }),
                    e.mails.push(t)
                }
                )
            } else
                e.moreMailCanBeLoaded = !1,
                l.timeout();
            e.ansycMailLoaded = !0,
            e.$broadcast("scroll.infiniteScrollComplete"),
            e.$broadcast("scroll.refreshComplete"),
            i(function() {
                e.refreshData = !1
            }
            , 500)
        }
        ;
        s.getInternalMessageList({
            PageSize: u,
            SearchTime: b,
            CurrentPage: p
        }).then(a, t)
    }
    ;
    0 == e.type ? (e.moreMailCanBeLoaded = !1,
    e.ansycNoticeLoaded = !1,
    _()) : (e.moreNoticeCanBeLoaded = !1,
    e.ansycMailLoaded = !1,
    g()),
    e.loadMoreNotice = function() {
        _()
    }
    ,
    e.loadMoreMail = function() {
        g()
    }
    ,
    e.noticeRefresh = function() {
        !e.refreshData && e.ansycNoticeLoaded && (e.refreshData = !0,
        d = "",
        e.notices = [],
        _(),
        C())
    }
    ,
    e.mailRefresh = function() {
        !e.refreshData && e.ansycMailLoaded && (e.refreshData = !0,
        b = "",
        p = 1,
        e.mails = [],
        g(),
        C())
    }
    ,
    e.onChange = function(t) {
        1 == t && "" == b && e.ansycMailLoaded ? (e.ansycMailLoaded = !1,
        e.moreMailCanBeLoaded = !0,
        g()) : 0 == t && "" == d && e.ansycNoticeLoaded && (e.ansycNoticeLoaded = !1,
        e.moreNoticeCanBeLoaded = !0,
        _())
    }
    ,
    e.delMail = function(a, o) {
        n.confirm({
            title: t("translate")("Common_TipsConfirmDeleteInternalMail"),
            cancelText: t("translate")("Common_ButtonCancel"),
            cancelType: "button-light",
            okText: t("translate")("Common_ButtonSure")
        }).then(function(n) {
            if (n) {
                var i = function(o) {
                    if (0 == o.ErrorCode) {
                        var n = -1;
                        angular.forEach(e.mails, function(e, t) {
                            e.MessID == a && (n = t)
                        }
                        ),
                        n >= 0 && e.mails.splice(n, 1),
                        l.tips(t("translate")("Common_TipsSuccessDeleteInternalMail"), 1e3)
                    } else
                        l.tips(t("translate")("Common_TipsFailDeleteInternalMail"), 1e3)
                }
                ;
                s.deleteMessage({
                    MessID: a,
                    SendType: o
                }).then(i)
            } else
                r.closeOptionButtons()
        }
        )
    }
}
]).controller("NoticeDetailCtrl", ["$scope", "$filter", "$state", "$stateParams", "$ionicPopup", "NoticeService", "QuickDialog", "Constants", function(e, t, a, o, n, r) {
    var i = o.id
      , s = o.Content
      , l = o.StartDateTime;
    0 === i && a.go("notice"),
    e.id = i,
    e.Content = s,
    e.StartDateTime = l,
    r.setReadNotices({
        NoticeID: i
    }).then(function() {}
    ),
    e.returnNotice = function() {
        a.go("notice", {
            type: 0
        })
    }
}
]).controller("InternalMailDetailCtrl", ["$scope", "$filter", "$state", "$stateParams", "$ionicPopup", "NoticeService", "QuickDialog", "Constants", function(e, t, a, o, n, r) {
    e.type = o.type,
    e.mails = [];
    var i = o.MessID;
    0 === i && a.go("notice", {
        type: 1
    });
    var s = o.sendType
      , l = function(t, a) {
        var o = function(t) {
            0 == t.ErrorCode && (e.showdata = t)
        }
        ;
        r.getInternalMessage({
            MessID: t,
            SendType: a
        }).then(o)
    }
    ;
    l(i, s),
    e.returnNotice = function() {
        a.go("notice", {
            type: 1
        })
    }
}
]).controller("NoticeAddCtrl", ["$scope", "$state", "$filter", "$timeout", "$ionicPopup", "NoticeService", "Constants", "QuickDialog", function(e, t, a, o, n, r, i, s) {
    e.typeData = [],
    e.mailData = {
        TypeID: "",
        Content: ""
    };
    var l = function() {
        var t = function(t) {
            0 == t.ErrorCode && (e.typeData = t.Data)
        }
        ;
        r.getInternalMessageTypeList().then(t)
    }
    ;
    l(),
    e.addMail = function(t) {
        s.loading();
        var o = function(t) {
            if (s.hideLoading(),
            0 == t.ErrorCode) {
                var o = a("translate")("Common_TipsSuccessSendInternalMail")
                  , n = 1500;
                s.tips(o, n, function() {
                    e.goInternalMail()
                }
                )
            } else
                s.alert(a("translate")("Common_TipsTimeoutGoMailListConfirm"), function() {
                    e.goInternalMail()
                }
                )
        }
          , n = function() {
            s.alert(a("translate")("Common_TipsTimeoutGoMailListConfirm"), function() {
                e.goInternalMail()
            }
            )
        }
        ;
        r.addInternalMessage(t).then(o, n)
    }
    ,
    e.goInternalMail = function() {
        t.go("notice", {
            type: 1
        })
    }
}
]),
angular.module("controllers.report", []).controller("WeekCtrl", ["$scope", "$state", "$timeout", "utils", "ReportService", "Constants", "QuickDialog", function(e, t, a, o, n, r, i) {
    e.weekData = [],
    e.allData = {},
    e.ansycLoaded = !1,
    e.refreshLoaded = !0;
    var s = function() {
        var t = function(t) {
            e.weekData = [],
            e.allData = {},
            0 == t.ErrorCode ? angular.forEach(t.Data, function(t) {
                "ALL" != t.AccountDate ? (angular.extend(t, {
                    WhatDay: o.getWeek(t.AccountDate)
                }),
                e.weekData.push(t)) : e.allData = t
            }
            ) : i.timeout(),
            e.ansycLoaded = !0,
            e.refreshLoaded = !0,
            e.$broadcast("scroll.refreshComplete")
        }
          , a = function() {
            e.ansycLoaded = !0,
            e.refreshLoaded = !0,
            e.$broadcast("scroll.refreshComplete")
        }
        ;
        n.getWeekReport().then(t, a)
    }
    ;
    s(),
    e.gotoDay = function(e) {
        n.setParamsCache({
            day: e
        }),
        t.go("day/0/0")
    }
    ,
    e.weekRefresh = function() {
        e.refreshLoaded && (e.refreshLoaded = !1,
        s())
    }
}
]).controller("DayCtrl", ["$scope", "$state", "$timeout", "utils", "Constants", "ReportService", "QuickDialog", function(e, t, a, o, n, r, i) {
    var d = "today",back = t.params.b; //gameid = e.params.id;
    e.ansycLoaded = !1,
    e.refreshLoaded = !0,
    e.dayData = [];
    1 == back  ? e.backUrl="/lot/#/index" :( 2 == back ) ? e.backUrl="/#/center" : e.backUrl="/"
    var m = function(l) {
        var t = function(t) {
            e.dayData = [],
            0 == t.ErrorCode ? e.dayData = t.Data : i.timeout(),
            e.ansycLoaded = !0,
            e.refreshLoaded = !0,
            e.$broadcast("scroll.refreshComplete")
        }
          , a = function() {
            e.ansycLoaded = !0,
            e.refreshLoaded = !0,
            e.$broadcast("scroll.refreshComplete")
        }
        ;
        r.getDayReport({
            DateTime: l
        }).then(t, a)
    }
    ;
    m(),
    e.getreport = function(q){
        angular.element(document.querySelector(".today" )).removeClass("active"),
            angular.element(document.querySelector(".yesterday")).removeClass("active"),
            angular.element(document.querySelector(".theweeky")).removeClass("active"),
            angular.element(document.querySelector(".lastweek")).removeClass("active"),
        angular.element(document.querySelector("." + q)).addClass("active"),
            d =q,
        e.ansycLoaded && (e.ansycLoaded = !1,
        m(q));
    }
    e.dayRefresh = function() {
        e.refreshLoaded && (e.refreshLoaded = !1,
        m())
    }, e.gotoRptGame = function(e, a) {
        r.setParamsCache({
            day: d,
            gid: e,
            name: encodeURI(a)
        }),
            t.go("rptgame")
    }

}
]).controller("RptGameCtrl", ["$scope", "$state", "$timeout", "utils", "ReportService", "Constants", "QuickDialog", function(e, t, a, o, n, r, i) {
    var s = n.getParamsCache();
    if (!s || !s.day)
        return void t.go("week");
    var l = s.day
      , m = s.gid
      , c = decodeURI(s.name);
    e.ansycLoaded = !1,
    e.refreshLoaded = !0,
    e.day = l,
    e.GameName = c,
    e.whatDay = o.getWeek(l),
    e.gameData = [];
    var u = function() {
        var t = function(t) {
            e.gameData = [],
            0 == t.ErrorCode ? e.gameData = t.Data : i.timeout(),
            e.ansycLoaded = !0,
            e.refreshLoaded = !0,
            e.$broadcast("scroll.refreshComplete")
        }
          , a = function() {
            e.ansycLoaded = !0,
            e.refreshLoaded = !0,
            e.$broadcast("scroll.refreshComplete")
        }
        ;
        n.getGameReport({
            DateTime: l,
            GameClassID: m
        }).then(t, a)
    }
    ;
    u(),
    e.rptGameRefresh = function() {
        e.refreshLoaded && (e.refreshLoaded = !1,
        u())
    }
    ,
    e.gotoRptDetail = function(e, a) {
        n.setParamsCache({
            day: l,
            gid: m,
            id: e,
            name: encodeURI(c),
            subname: encodeURI(a)
        }),
        t.go("rptdetail")
    }
}
]).controller("RptDetailCtrl", ["$scope", "$state", "$timeout", "utils", "ReportService", "Constants", "QuickDialog", function(e, t, a, o, n, r, i) {
    var s = n.getParamsCache();
    if (!s || !s.day)
        return void t.go("week");
    var l = s.day
      , m = s.gid
      , c = s.id
      , u = decodeURI(s.name)
      , d = decodeURI(s.subname)
      , b = 10
      , p = 1;
    e.ansycLoaded = !1,
    e.refreshLoaded = !0,
    e.moreDataCanBeLoaded = !0,
    e.day = l,
    e.whatDay = o.getWeek(l),
    e.gid = m,
    e.GameName = u,
    e.SubGameName = d,
    e.detailData = [];
    var C = function() {
        function t(t) {
            0 == t.ErrorCode ? (t.Data.Result.length < b && (e.moreDataCanBeLoaded = !1),
            angular.forEach(t.Data.Result, function(t) {
                e.detailData.push(t)
            }
            )) : (e.moreDataCanBeLoaded = !1,
            i.timeout()),
            e.ansycLoaded = !0,
            e.refreshLoaded = !0,
            e.$broadcast("scroll.refreshComplete"),
            e.$broadcast("scroll.infiniteScrollComplete")
        }
        function o() {
            i.hideLoading(),
            e.moreDataCanBeLoaded = !1,
            e.refreshLoaded = !0,
            e.$broadcast("scroll.refreshComplete"),
            e.$broadcast("scroll.infiniteScrollComplete"),
            e.detailData.length > 0 && e.detailData.length % b == 0 && a(function() {
                e.moreMailCanBeLoaded = !0
            }
            , 2e3)
        }
        e.detailData.length > 0 && e.detailData.length % b == 0 && (p = parseInt(e.detailData.length / b) + 1),
        n.getDetailReport({
            DateTime: l,
            GameClassID: m,
            GameID: c,
            PageSize: b,
            CurrentPage: p
        }).then(t, o)
    }
    ;
    C(),
    e.loadMoreData = function() {
        C()
    }
    ,
    e.rptDetailRefresh = function() {
        e.refreshLoaded && (e.detailData = [],
        p = 1,
        e.refreshLoaded = !1,
        e.moreDataCanBeLoaded = !0,
        C())
    }
    ,
    e.gotoBetList = function(e) {
        n.setParamsCache({
            day: l,
            gid: m,
            id: c,
            name: encodeURI(u),
            subname: encodeURI(d),
            betno: e
        }),
        t.go("rptlist")
    }
}
]).controller("RptBetListCtrl", ["$scope", "$state", "utils", "ReportService", "Constants", "QuickDialog", function(e, t, a, o, n, r) {
    var i = o.getParamsCache();
    if (!i || !i.day)
        return void t.go("week");
    var s = i.day
      , l = i.gid
      , m = i.id
      , c = decodeURI(i.name)
      , u = decodeURI(i.subname)
      , d = i.betno;
    e.ansycLoaded = !1,
    e.refreshLoaded = !0,
    e.day = s,
    e.whatDay = a.getWeek(s),
    e.GameName = c,
    e.SubGameName = u,
    e.betListData = [];
    var b = function() {
        function t(t) {
            e.betListData = [],
            0 == t.ErrorCode ? e.betListData = t.Data : r.timeout(),
            e.ansycLoaded = !0,
            e.refreshLoaded = !0,
            e.$broadcast("scroll.refreshComplete")
        }
        var a = function() {
            e.ansycLoaded = !0,
            e.refreshLoaded = !0,
            e.$broadcast("scroll.refreshComplete")
        }
        ;
        o.getAccountByGameDetailItems({
            GameClassID: l,
            GameID: m,
            BetNo: d
        }).then(t, a)
    }
    ;
    b(),
    e.betListRefresh = function() {
        e.refreshLoaded && (e.refreshLoaded = !1,
        b())
    }
}
]),
angular.module("controllers.cash", []).controller("TransferCtrl", ["$scope", "$filter", "$ionicScrollDelegate", "utils", "CashService", "QuickDialog", "Constants", "$ionicLoading", "$ionicPopup", "$state", "AuthService", "AccountService", function(e, t, a, o, n, r, i, s, l, m, c, u) {
    function d() {
        e.transerModel.walletType = 99,
        e.transerModel.moneyIndex = 99,
        e.transerModel.rollInWallet = 99,
        e.transerModel.actualMoney = null ,
        e.transerModel.haveMoney = 0,
        e.transerModel.classLoading = 1,
        g = [{
            GameClassID: 0,
            State: 1,
            GameClassName: t("translate")("Common_LabelMyWallet"),
            OpenState: 1,
            IsOnline: 0,
            OpenState2: 0,
            LoadingState: 0,
            walletBalance: 0
        }],
        angular.forEach(_, function(e) {
            e.walletBalance = 0,
            e.LoadingState = 0,
            e.State = 1,
            e.IsOnline = 0,
            e.OpenState2 = 0,
            e.OpenState = 1;
            if(e.GameClassID != 1 && e.GameClassID != 2){
                this.push(e)
            }
        }
        , g),
        e.amountList = [t("translate")("Common_LabelAll"), "100", "500", "1000", "5000", "10000"],
        e.walletList = g,
        b()
    }
    function b() {
        angular.forEach(e.walletList, function(e) {
            C(e.GameClassID)
        }
        ),
        u.getMemberGameState().then(function(t) {
            0 == t.ErrorCode ? (e.transerModel.classLoading = 0,
            angular.forEach(t.Data, function(t) {
                var a = p(t.GameClassID);
                a > -1 && (e.walletList[a].State = t.State,
                2 == e.currentUser.TestState && t.TestUserWalletModel > 0 && (e.walletList[a].State = 0),
                1 == e.currentUser.TestState && t.TestUserWalletModel > 1 && (e.walletList[a].State = 0))
            }
            )) : e.transerModel.classLoading = 2
        }
        , function() {
            e.transerModel.classLoading = 2
        }
        )
    }
    function p(t) {
        for (var a = 0; a < e.walletList.length; a++)
            if (e.walletList[a].GameClassID == t)
                return a;
        return -1
    }
    function pp(t) {
        for (var a = 0; a < e.walletList.length; a++)
        if( t == 0){
            e.walletList[a].OpenState2 = 0
        }else
            if(e.walletList[a].GameClassID == 0) {
                e.walletList[a].OpenState2 = 0
            }else {
                e.walletList[a].OpenState2 = 1
            }
    }
    function C(t) {
        var a = p(t);
        a > -1 && (e.walletList[a].LoadingState = 1,
        n.getWallets({
            GameClassIDs: t
        }).then(function(t) {
            if (0 == t.ErrorCode) {
                var o = t.WalletBalanceList;
                o.length > 0 ? (0 == e.walletList[a].State && e.walletList[a].GameClassID == e.transerModel.walletType && (e.transerModel.walletType = 99),
                -1 == o[0].Balance ? (e.walletList[a].walletBalance = parseFloat(0).toFixed(2),
                e.walletList[a].LoadingState = 2) : o[0].Balance == o[0].RateBalance ? (e.walletList[a].walletBalance = parseFloat(o[0].Balance).toFixed(2),
                e.walletList[a].LoadingState = 0) : (e.walletList[a].walletBalance = parseFloat(o[0].Balance).toFixed(2) + "(" + parseFloat(o[0].RateBalance).toFixed(2) + ")",
                e.walletList[a].LoadingState = 0)) : (e.walletList[a].walletBalance = parseFloat(0).toFixed(2),
                e.walletList[a].LoadingState = 0)
            }
        }
        , function() {
            e.walletList[a].LoadingState = 2
        }
        ))
    }
    var _ = c.currentUser.GameClassList;
    e.currentUser = c.currentUser,
    e.transerModel = {
        actualMoney: null ,
        haveMoney: 0,
        walletType: 99,
        moneyIndex: 99,
        rollInWallet: 99,
        classLoading: 1
    },
    e.walletList = [];
    var g = [];
    d(),
    e.chooseWalletFun = function(t) {
        //如果是非0
        pp(t);
        var a = p(t)
          , o = e.walletList[a];
        0 != o.State && e.transerModel.rollInWallet != o.GameClassID && 2 != o.LoadingState && 1 != o.IsOnline && 0 == e.transerModel.classLoading && o.walletBalance > 0 && (e.transerModel.walletType == t ? (e.transerModel.walletType = 99,
        e.transerModel.actualMoney = "") : e.transerModel.walletType = t,
        e.transerModel.haveMoney = parseInt(e.walletList[a].walletBalance),
        e.transerModel.moneyIndex = 99)
    }
    ,
    e.rollInFun = function(t) {
        var a = p(t)
          , o = e.walletList[a];
        0 != o.State && e.transerModel.walletType != o.GameClassID && 2 != o.LoadingState && 1 != o.IsOnline && 0 == e.transerModel.classLoading && (e.transerModel.rollInWallet = e.transerModel.rollInWallet == t ? 99 : t)
    }
    ,
    e.chooseMoneyFun = function(t) {
        0 == t ? (e.transerModel.actualMoney = parseInt(e.transerModel.haveMoney),
        e.transerModel.moneyIndex = t) : parseInt(e.amountList[t]) <= parseInt(e.transerModel.haveMoney) && (e.transerModel.actualMoney = parseInt(e.amountList[t]),
        e.transerModel.moneyIndex = t)
    }
    ,
    e.refresh = function() {
        b()
    }
    ,
    e.parseIntMoney = function(t) {
        t.target.value = parseInt(t.target.value),
        e.transerModel.moneyIndex = 99,
        "NaN" == t.target.value ? (t.target.value = "",
        e.transerModel.actualMoney = "") : e.transerModel.actualMoney = t.target.value
    }
    ,
    e.clearSelect = function() {
        e.transerModel.moneyIndex = 99
    }
    ,
    e.transferSubmit = function() {
        if (99 == e.transerModel.walletType)
            return void r.tips(t("translate")("Member_TipsPleaseChooseOutWallet"));
        if (99 == e.transerModel.rollInWallet)
            return void r.tips(t("translate")("Member_TipsPleaseChooseInWallet"));
        if (!e.transerModel.actualMoney || "" == e.transerModel.actualMoney || 0 == e.transerModel.actualMoney)
            return void r.tips(t("translate")("Member_TipsPleaseChooseOrPutInPoint"));
        if (!angular.isNumber(parseInt(e.transerModel.actualMoney)))
            return void r.tips(t("translate")("Member_TipsTranPointMustBeInt"));
        var o = p(e.transerModel.walletType)
          , i = e.walletList[o];
        if (parseFloat(i.walletBalance) < parseFloat(e.transerModel.actualMoney))
            r.tips(t("translate")("Member_TipsOutWalletPointNotEnough"));
        else {
            r.loadAndDisable("btnTransfer", 1e6, t("translate")("Member_TipsPleaseWaitInDoing"));
            var s = 1
              , c = {
                FromGameClassID: e.transerModel.walletType,
                ToGameClassID: e.transerModel.rollInWallet,
                Amount: e.transerModel.actualMoney,
                Type: s
            };
            n.fundTransfer(c).then(function(o) {
                r.hideLoadAndDisable("btnTransfer", !0),
                0 == o.ErrorCode ? (e.transerModel.walletType = 99,
                e.transerModel.moneyIndex = 99,
                e.transerModel.rollInWallet = 99,
                e.transerModel.actualMoney = "",
                e.transerModel.haveMoney = 0,
                e.transerModel.classLoading = 1,
                2 != e.currentUser.TestState ? l.confirm({
                    title: t("translate")("Member_LabelTransferSuccess"),
                    okText: t("translate")("Member_LabelViewRecord"),
                    cancelText: t("translate")("Member_LabelTransContinue"),
                    cancelType: "button-light"
                }).then(function(e) {
                    return e ? void m.go("trans", {
                        type: 1
                    }) : void a.$getByHandle("transferScroll").scrollTo(0, 0)
                }
                ) : (a.$getByHandle("transferScroll").scrollTo(0, 0),
                r.tips(t("translate")("Member_LabelTransferSuccess")))) : r.tips(o.ErrorMsg),
                b()
            }
            , function() {
                r.hideLoadAndDisable("btnTransfer")
            }
            )
        }
    }
    ,
    e.setScroll = function(t) {
        var n = o.checkBrowser("uc")
          , r = o.checkBrowser("qq");
        if (e.showBlank = n || r,
        e.showBlank) {
            var i = angular.element(t.target).parent().prop("offsetTop");
            a.$getByHandle("transferScroll").scrollTo(0, i - 50)
        }
    }
}
]).controller("QuickTransferCtrl", ["$scope", "$filter", "CashService", "QuickDialog", "$ionicLoading", "$ionicPopup", "Constants", "AuthService", "AccountService", "$state", function(e, t, a, o, n, r, i, s, l, m) {
    function c() {
        e.classLoading = 1,
        angular.forEach(e.walletList, function(e) {
            b(e.GameClassID)
        }
        ),
        l.getMemberGameState().then(function(t) {
            0 == t.ErrorCode ? (e.classLoading = 0,
            angular.forEach(t.Data, function(t) {
                var a = d(t.GameClassID);
                a > -1 && (e.walletList[a].State = t.State)
            }
            )) : e.classLoading = 2
        }
        , function() {
            e.classLoading = 2
        }
        )
    }
    function u() {
        p = [{
            GameClassID: 0,
            State: 1,
            GameClassName: t("translate")("Common_LabelMyWallet"),
            OpenState: 1,
            IsOnline: 0,
            LoadingState: 0,
            walletBalance: 0,
            TransferLoading: -1
        }],
        angular.forEach(C, function(t) {
            t.walletBalance = 0,
            t.LoadingState = 0,
            t.State = 1,
            t.IsOnline = 0,
            t.OpenState = 1,
            t.TransferLoading = -1;
            if(t.GameClassID == 1 || t.GameClassID == 2){
                return
            }
            2 == e.currentUser.TestState ? 0 == t.TestUserWalletModel && this.push(t) : 1 == e.currentUser.TestState ? (0 == t.TestUserWalletModel || 1 == t.TestUserWalletModel) && this.push(t) : this.push(t)
        }
        , p),
        e.walletList = p,
        c()
    }
    function d(t) {
        for (var a = -1, o = 0; o < e.walletList.length; o++)
            e.walletList[o].GameClassID == t && (a = o);
        return a
    }
    function b(t) {
        var o = d(t);
        o > -1 && (e.walletList[o].LoadingState = 1,
        2 != e.walletList[o].State && a.getWallets({
            GameClassIDs: t
        }).then(function(t) {
            if (0 == t.ErrorCode) {
                var a = t.WalletBalanceList;
                a.length > 0 ? -1 == a[0].Balance ? (e.walletList[o].walletBalance = 0,
                e.walletList[o].LoadingState = 2) : a[0].Balance == a[0].RateBalance ? (e.walletList[o].walletBalance = parseFloat(a[0].Balance).toFixed(2),
                e.walletList[o].LoadingState = 0,
                0 == a[0].Balance && 2 == e.walletList[o].TransferLoading && (e.walletList[o].TransferLoading = 0)) : (e.walletList[o].walletBalance = parseFloat(a[0].Balance).toFixed(2) + "(" + parseFloat(a[0].RateBalance).toFixed(2) + ")",
                e.walletList[o].LoadingState = 0,
                0 == a[0].Balance && 2 == e.walletList[o].TransferLoading && (e.walletList[o].TransferLoading = 0)) : (e.walletList[o].walletBalance = parseFloat(0).toFixed(2),
                e.walletList[o].LoadingState = 0)
            }
        }
        , function() {
            e.walletList[o].LoadingState = 2
        }
        ))
    }
    e.walletList = [],
    e.classLoading = 1,
    e.watchCount = 0,
    e.currentUser = s.currentUser;
    var p = []
      , C = s.currentUser.GameClassList;
    e.showError = function(a) {
        var n = d(a)
          , r = e.walletList[n];
        r.error && o.alert(t("translate")("Member_TipsTranFailCause") + r.error, function() {}
        )
    }
    ,
    e.$watch("watchCount", function(a) {
        if (a == e.walletList.length) {
            o.hideLoading();
            for (var n = 0; n < e.walletList.length; n++)
                if (0 == e.walletList[n].OpenState || 1 == e.walletList[n].IsOnline || 2 == e.walletList[n].LoadingState || 2 == e.walletList[n].TransferLoading)
                    return void c();
            o.tips(t("translate")("Member_LabelTransferSuccess"), null , function() {
                m.go("transfer")
            }
            )
        }
    }
    ),
    u(),
    e.allIn = function(n) {
        e.watchCount = 0;
        var i = d(n)
          , s = e.walletList[i];
        0 == s.State ? o.tips(t("translate")("Member_LabelTransGameUpholding")) : 2 == s.State ? o.tips(t("translate")("Member_TipsTrialUserNoService")) : 0 == s.OpenState ? o.tips(t("translate")("Member_LabelTransferFail")) : 1 == s.IsOnline ? o.tips(t("translate")("Member_LabelTransGamingOfElectron")) : 0 != s.LoadingState || 0 != e.classLoading || r.confirm({
            title: t("translate")("Member_TipsTranInConfirm") + s.GameClassName + "?",
            okText: t("translate")("Common_ButtonSure"),
            cancelType: "button-light",
            cancelText: t("translate")("Common_ButtonCancel")
        }).then(function(r) {
            r && (o.tips(t("translate")("Member_TipsPleaseWaitInDoing"), 1e6),
            angular.forEach(e.walletList, function(o) {
                var r = d(o.GameClassID);
                if (o.GameClassID != n && 0 != o.OpenState) {
                    var i = {
                        FromGameClassID: o.GameClassID,
                        ToGameClassID: n,
                        Type: 2
                    };
                    e.walletList[r].TransferLoading = 1,
                    a.fundTransfer(i).then(function(a) {
                        e.watchCount++,
                        0 == a.ErrorCode ? (e.walletList[r].TransferLoading = 0,
                        e.walletList[r].LoadingState = 0) : (e.walletList[r].TransferLoading = 2,
                        e.walletList[r].error = t("translate")(1 == a.ErrorCode ? "Member_LabelTransBing" : 4 == a.ErrorCode ? "Member_LabelGamingDoNotTrans" : 5 == a.ErrorCode ? "Member_LabelTransOutPointThanSurplusPoint" : 6 == a.ErrorCode ? "Member_LabelTransGameUpholding" : "Member_LabelTransferFail"))
                    }
                    , function() {
                        e.watchCount++,
                        e.walletList[r].error = t("translate")("Member_TipsConnectTimeOut"),
                        e.walletList[r].TransferLoading = 2
                    }
                    )
                } else
                    e.walletList[r].TransferLoading = -1,
                    e.watchCount++
            }
            ))
        }
        )
    }
}
]).controller("DepositCtrl", ["$scope", "$filter", "$state", "$timeout", "$ionicScrollDelegate", "utils", "Constants", "CashService", "QuickDialog", "AuthService", "BankService", function(e, t, a, o, n, r, i, s, l, m, n) {
    angular.element(document).ready(function() {
        e.ucFlag = r.checkBrowser("uc");
        var t = "android" == r.getBrowser();
        if (t && angular.element(document.querySelectorAll(".scroll")).removeClass("scroll"),
        e.ucFlag) {
            var a, o = angular.element(document.querySelectorAll(".uc-set-scroll"));
            o.on("click", function() {
                var e = angular.element(this).parent().prop("offsetTop");
                a = n.getScrollPosition(),
                n.scrollTo(0, e - 20)
            }
            ),
            o.on("blur", function() {
                n.scrollTo(a.left, a.top)
            }
            )
        }
    }
    );
    for (var c = parseInt(1e6 * Math.random()).toString(); c.length < 6; )
        c = "0" + c;
    var u = 1;
    e.limitData = {},
    e.activityData = [],
    e.activityThirdData = [],
    e.activity = {},
    e.activityThird = {},
    e.bankData = [],
    e.bankData1 = [],
    e.bankThirdData = [],
    e.bank = {},
    e.bankThird = {},
    e.depositData = {
        DepositType: 0,
        DepositAccountTypeID: 0,
        BankAccountID: 0,
        DepositMoney: null ,
        ActivityID: 0,
        OrderNum: 0,
        Remark: null,
        BankID: 0
    },
    e.depositThirdData = {
        BankAccountID: 0,
        DepositMoney: null ,
        ActivityID: 0,
        Platform: "",
        BankCode: ""
    },
    e.depositThirdCardData = {
        BankAccountID: 0,
        DepositMoney: null ,
        ActivityID: 0,
        Platform: "",
        BankCode: "",
        pa7_cardAmt:"",
        pa8_cardNo:"",
        pa9_cardPwd:""
    },
    e.currentUser = m.currentUser,
    e.loaded = !1,
    e.depositTypeData = [],
    1 == a.params.type && l.alert(t("translate")("Common_TipsTransEndGoodLuck"));
    var d = function() {
        var t = function(t) {
            0 == t.ErrorCode && (e.limitData = t.Data)
        }
        ;
        s.getDepositLimit().then(t)
    }
    ;
    d();
    var GetOrderNum = function() {
        var t = function(t) {
            0 == t.ErrorCode && (e.OrderNum = t.Data.OrderNum)
        }
        ;
        s.GetOrderNum().then(t)
    }
    ;
    var b = function() {
        var t = function(t) {
            0 == t.ErrorCode ? (e.bankThirdData = t.Data.BankList,
            angular.element(document.querySelector("#slt-bankcode")).removeAttr("disabled"),
            e.bankThird = t.Data.BankAccount,
            e.depositThirdData.Platform = t.Data.BankAccount.Platform,
            e.depositThirdData.BankAccountID = t.Data.BankAccount.ID) : 1 == t.ErrorCode && (e.notOpenOLPay = !0)
        }
        ;
        s.getThirdPartyBanks().then(t)
    }
    ;
    var card = function() {
        var t = function(t) {
            0 == t.ErrorCode ? (e.bankCardThirdData = t.Data.BankList,
            angular.element(document.querySelector("#slt-bankCardcode")).removeAttr("disabled"),
            e.bankCradThird = t.Data.BankAccount,
            e.depositThirdCardData.Platform = t.Data.BankAccount.Platform,
            e.depositThirdCardData.BankAccountID = t.Data.BankAccount.ID) : 1 == t.ErrorCode && (e.notOpenCardOLPay = !0)
        }
        ;
        s.getThirdCardPartyBanks().then(t)
    }
    ;
    var q = function() {
        var t = function(t) {
            0 == t.ErrorCode && (e.bankData1 = t.Data)
        }
        n.getBankData().then(t)
    }

    e.getActivityDeposits = function(t) {
        e.activity = {};
        var a;
        if (1 == u ? (t.target.value = t.target.value.replace(/[^0-9.]+/g, ""),
        "NaN" == t.target.value && (t.target.value = ""),
        /^\d+\.?\d{0,2}$/.test(t.target.value) || (t.target.value = parseFloat(t.target.value).toFixed(2)),
        a = parseFloat(t.target.value),
        e.depositThirdData.DepositMoney = a) : (t.target.value = parseInt(t.target.value),
        "NaN" == t.target.value && (t.target.value = ""),
        a = t.target.value,
        e.depositThirdData.DepositMoney = a),
        a && angular.isNumber(parseInt(a)) && parseInt(a) > 0) {
        }
    }

    e.getActivityCardDeposits = function(t) {
        e.activityCard = {};
        var a;
        if (1 == u ? (t.target.value = t.target.value.replace(/[^0-9.]+/g, ""),
        "NaN" == t.target.value && (t.target.value = ""),
        /^\d+\.?\d{0,2}$/.test(t.target.value) || (t.target.value = parseFloat(t.target.value).toFixed(2)),
        a = parseFloat(t.target.value),
        e.depositThirdCardData.DepositMoney = a) : (t.target.value = parseInt(t.target.value),
        "NaN" == t.target.value && (t.target.value = ""),
        a = t.target.value,
        e.depositThirdCardData.DepositMoney = a),
        a && angular.isNumber(parseInt(a)) && parseInt(a) > 0) {
        }
    }
    e.getActivityCardpa7_cardAmt = function(t) {
        e.activityCard = {};
        var a;
        if (1 == u ? (t.target.value = t.target.value.replace(/[^0-9.]+/g, ""),
        "NaN" == t.target.value && (t.target.value = ""),
        /^\d+\.?\d{0,2}$/.test(t.target.value) || (t.target.value = parseFloat(t.target.value).toFixed(2)),
        a = parseFloat(t.target.value),
        e.depositThirdCardData.pa7_cardAmt = a) : (t.target.value = parseInt(t.target.value),
        "NaN" == t.target.value && (t.target.value = ""),
        a = t.target.value,
        e.depositThirdCardData.pa7_cardAmt = a),
        a && angular.isNumber(parseInt(a)) && parseInt(a) > 0) {
        }
    }
    e.getActivityCardpa8_cardNo = function(t) {
        e.activityCard = {};
        var a;
        if (1 == u ? (t.target.value = t.target.value.replace(/[^0-9.]+/g, ""),
        "NaN" == t.target.value && (t.target.value = ""),
        /^\d+\.?\d{0,2}$/.test(t.target.value) || (t.target.value = parseFloat(t.target.value).toFixed(2)),
        a = parseFloat(t.target.value),
        e.depositThirdCardData.pa8_cardNo = a) : (t.target.value = parseInt(t.target.value),
        "NaN" == t.target.value && (t.target.value = ""),
        a = t.target.value,
        e.depositThirdCardData.pa8_cardNo = a),
        a && angular.isNumber(parseInt(a)) && parseInt(a) > 0) {
        }
    }
    e.getActivityCardpa9_cardPwd = function(t) {
        e.activityCard = {};
        var a;
        if (1 == u ? (t.target.value = t.target.value.replace(/[^0-9.]+/g, ""),
        "NaN" == t.target.value && (t.target.value = ""),
        /^\d+\.?\d{0,2}$/.test(t.target.value) || (t.target.value = parseFloat(t.target.value).toFixed(2)),
        a = parseFloat(t.target.value),
        e.depositThirdCardData.pa9_cardPwd = a) : (t.target.value = parseInt(t.target.value),
        "NaN" == t.target.value && (t.target.value = ""),
        a = t.target.value,
        e.depositThirdCardData.pa9_cardPwd = a),
        a && angular.isNumber(parseInt(a)) && parseInt(a) > 0) {
        }
    }
    ;
    var p = function() {
        var t = function(t) {
            0 == t.ErrorCode && (e.bankData = t.Data)
        }
        ;
        s.getDepositsBanks().then(t)
    }
      , C = function() {
        var t = function(t) {
            0 == t.ErrorCode && (e.depositTypeData = t.Data)
        }
        ;
        s.getDepositType().then(t)
    }
    ;
    e.onChange = function(t) {
        if(t == 1){
             b();
             0 == e.bankData.length && p();
        }else if(t == 2){
            card();
        }else{
            0 == e.depositTypeData.length && C();
            0 == e.bankData1.length && q();
        }
    }
    ,
    1 == u && (0 == e.bankData.length && p(),
    0 == e.depositTypeData.length && C(),
    0 == e.bankData1.length && q()),
    e.activityChange = function(a) {
        e.activity = {},
        a > 0 && (1 == u ? e.activity = t("filter")(e.activityData, {
            ActivityID: parseInt(a)
        }, !0)[0] : e.activityThird = t("filter")(e.activityThirdData, {
            ActivityID: parseInt(a)
        }, !0)[0])
    }
    ,
    e.bankChange = function(a) {
        e.bank = {},
        a > 0 && (e.bank = t("filter")(e.bankData, {
            BankAccountID: parseInt(a)
        }, !0)[0],
        e.bank.BankAccount = r.splitCardNO(e.bank.BankAccount))
        GetOrderNum();
    }
    ,
    e.addDeposit = function(o) {
        if (o.BankID <= 0)
            return void l.tips(t("translate")("Member_LabelChooseDepositBank"));
        if (!o.DepositMoney || "" == o.DepositMoney)
            return void l.tips(t("translate")("Common_LabelPleaseInputMoney"));
        if (!angular.isNumber(parseFloat(o.DepositMoney)))
            return void l.tips(t("translate")("Common_LabelDepositPointOnlyNum"));
        if (parseFloat(o.DepositMoney) < parseFloat(e.limitData.MinAccountDepositMoney) || parseFloat(o.DepositMoney) > parseFloat(e.limitData.MaxAccountDepositMoney))
            return void l.tips(t("translate")("Common_LabelDepositPointOverLimit"));
        if (!o.DepositName || "" == o.DepositName)
            return void l.tips(t("translate")("Member_LabelInputCunKuanMinZi"));
        if (o.BankAccountID <= 0)
            return void l.tips(t("translate")("Member_LabelChooseDepositBankAccount"));
        if (o.DepositAccountTypeID <= 0){
            return void l.tips(t("translate")("Member_TipsPleaseChooseInWithdrawType"));
        }else if (o.DepositAccountTypeID != 1 && o.DepositAccountTypeID != 2 && o.DepositAccountTypeID != 3 && o.DepositAccountTypeID != 4 && o.DepositAccountTypeID != 8){
            if(!o.Remark || "" == o.Remark){
                return void l.tips(t("translate")("Member_TipsRemarkBank"));
            }else if(o.Remark && o.Remark.length > 50){
                return void l.tips(t("translate")("Member_TipsRemarkTooLong"));
            }
        }

        l.loadAndDisable("btnDeposit");
        o.OrderNum =  e.OrderNum;
        var n = function(e) {
            if (l.hideLoadAndDisable("btnDeposit", !0),
            0 == e.ErrorCode)
                l.alert(t("translate")("Member_TipsOrderBeenSubmit"), function() {
                    a.go("center")
                }
                );
            else
                switch (e.ErrorCode) {
                case 1:
                    l.alert(t("translate")("Common_TipsYourAccountBeenStopped"));
                    break;
                case 2:
                    l.alert(t("translate")("Common_TipsYourAccountBeenLocked"));
                    break;
                case 3:
                    l.alert(t("translate")("Common_LabelDepositPointOverLimit"));
                    break;
                case 4:
                    l.alert(t("translate")("Common_TipsParameterError"), function() {
                        a.go("center")
                    })
                    break;
                case 5:
                    l.alert(t("translate")("Common_TipsTimeoutGoTranConfirm"), function() {
                        a.go("trans")
                    })
                    break;
                }

        }
        ;
        s.addDeposit(o).then(n)
    }
    ,
    e.addThirdDeposit = function(n) {
         return n.DepositMoney && "" != n.DepositMoney ? angular.isNumber(parseInt(n.DepositMoney)) ? parseInt(n.DepositMoney) < parseInt(e.limitData.MinThirdPartyDepositMoney) || parseInt(n.DepositMoney) > parseInt(e.limitData.MaxThirdPartyDepositMoney) ? void l.tips(t("translate")("Common_LabelDepositPointOverLimit")) :
          ("" == n.BankCode && (3 != parseInt(n.Platform) && 12 != parseInt(n.Platform))) ? void l.tips(t("translate")("Member_TipsPleaseChooseBank")) :
           (l.loadAndDisable("btnThirdDeposit"),
        s.setPayParams(n),
        r.openWindow("#/pay"),
        void o(function() {
            l.hideLoadAndDisable("btnThirdDeposit", !0)
            a.go("trans")
        }
        , 2e3)) : void l.tips(t("translate")("Common_LabelDepositPointOnlyNum")) : void l.tips(t("translate")("Common_LabelPleaseInputMoney"))
    }
    ,
     e.addThirdCardDeposit = function(n) {
         return n.DepositMoney && "" != n.DepositMoney ? angular.isNumber(parseInt(n.DepositMoney)) ? parseInt(n.DepositMoney) < parseInt(e.limitData.MinThirdPartyDepositMoney) || parseInt(n.DepositMoney) > parseInt(e.limitData.MaxThirdPartyDepositMoney) ? void l.tips(t("translate")("Common_LabelDepositPointOverLimit")) :
          "" == n.pa7_cardAmt && 14 != parseInt(n.Platform) ? void l.tips(t("translate")("Common_Labelinputpa7_cardAmt")) :
          "" == n.pa8_cardNo && 14 != parseInt(n.Platform) ? void l.tips(t("translate")("Common_Labelinputpa8_cardNo")) :
          "" == n.pa9_cardPwd && 14 != parseInt(n.Platform) ? void l.tips(t("translate")("Common_Labelinputpa9_cardPwd")) :
          "" == n.BankCode ? void l.tips(t("translate")("Member_LabelChoosePayType")) :
           (l.loadAndDisable("btnThirdCardDeposit"),
        s.setPayParams(n),
        r.openWindow("#/pay"),
        void o(function() {
            l.hideLoadAndDisable("btnThirdCardDeposit", !0)
            a.go("trans")
        }
        , 2e3)) : void l.tips(t("translate")("Common_LabelDepositPointOnlyNum")) : void l.tips(t("translate")("Common_LabelPleaseInputMoney"))
    }
    ,
    e.getWallet = function() {
        function t(t) {
            0 == t.ErrorCode && (e.wallet = parseFloat(t.WalletBalanceList[0].Balance).toFixed(2),
            e.loaded = !0)
        }
        function a() {
            e.wallet = parseFloat(0).toFixed(2),
            e.loaded = !0
        }
        e.loaded = !1,
        s.getWallets({
            GameClassIDs: 0
        }).then(t, a)
    }
    ,
    e.getWallet()
}
]).controller("ThirdPayRedirectCtrl", ["$filter" ,"CashService", "QuickDialog", function(l,e, t) {
    t.loading(3e5);
    var a = e.getPayParams();
    if (a && a.DepositMoney) {
        var o = function(a) {
            t.hideLoading(),
            0 == a.ErrorCode ? (e.clearPayParams(),window.location = a.Data.PayUrl) :
            7 == a.ErrorCode ? (e.clearPayParams(),document.write(a.Data.PayUrl)) :
            t.timeout()
        }
        ;
        e.addThirdPartyDeposit(a).then(o)
    }
}
]).controller("WithdrawCtrl", ["$scope", "$stateParams", "$ionicLoading", "$ionicHistory", "$filter", "$timeout", "$ionicScrollDelegate", "utils", "AccountService", "BankService", "QuickDialog", "Constants", "CashService", "AuthService", function(e, t, a, o, n, r, i, s, l, m, c, u, d, b) {
    angular.element(document).ready(function() {
        e.ucFlag = s.checkBrowser("uc");
        var t = "android" == s.getBrowser();
        t && angular.element(document.querySelectorAll(".scroll")).removeClass("scroll"),
        e.ucFlag && angular.element(document.querySelectorAll(".scroll-content")).css({
            position: "fixed"
        })
    }
    );
    var p = function() {
        if (e.ucFlag) {
            var t, a = angular.element(document.querySelectorAll(".uc-set-scroll"));
            a.on("click", function() {
                angular.element(this).parent().prop("offsetTop");
                t = i.getScrollPosition(),
                i.scrollTo(0, 50)
            }
            ),
            a.on("blur", function() {
                i.scrollTo(t.left, t.top)
            }
            )
        }
    }
    ;
    e.withdrawData = {
        applyMoney: "",
        memberBankID: 0,
        withdrawPwd: ""
    },
    e.ansycLoaded = !1,
    e.currentUser = b.currentUser;
    var C = ["/templates/" + t.template + "/cash/withdraw-exist.html", "/templates/" + t.template + "/cash/withdraw-add.html", "/templates/" + t.template + "/cash/withdraw-banks.html", "/templates/" + t.template + "/cash/withdraw-success.html", "/templates/" + t.template + "/cash/withdraw-limit.html"];
    e.saveWithdraw = function() {
        if (e.withdrawData.applyMoney)
            if (e.withdrawData.applyMoney > e.wallet)
                c.tips(n("translate")("Member_TipsBalanceNotEnough"));
            else if (3 == e.withdrawLimit.LimitType)
                c.tips(n("translate")("Member_TipsXiMaNotEnough"));
            else if (e.withdrawData.applyMoney < e.withdrawLimit.LimitData.MinWithdrawalMoney || e.withdrawData.applyMoney > e.withdrawLimit.LimitData.MaxWithdrawalMoney)
                c.tips(n("translate")("Member_TipsWithdrawlAmountLimit"));
            else if (!e.withdrawData.memberBankID || e.withdrawData.memberBankID <= 0)
                c.tips(n("translate")("Member_TipsPleaseChooseInBank"));
            else if (e.OutAudit==undefined){
                c.tips(n("translate")("Member_TipsPleaseWaitAudit"));
            }
            else if (e.withdrawData.withdrawPwd) {
                c.loadAndDisable("btnWithdraw");
                var t = function(t) {
                    c.hideLoadAndDisable("btnWithdraw", !0),
                    0 == t.ErrorCode ? (
                        e.wallet = parseFloat(t.Data.Balance).toFixed(2),
                        e.withdrawMoney = parseFloat(e.withdrawData.applyMoney).toFixed(2),
                        e.OutAudit = parseFloat(e.OutAudit).toFixed(2),
                        e.PayName = t.Data.PayName,
                        e.OrderNum = t.Data.OrderNum,
                        e.url = C[3]
                    )
                     : c.tips(
                        1 == t.ErrorCode ? n("translate")("Member_TipsWithdrawPwdError") :
                        2 == t.ErrorCode ? n("translate")("Common_TipsParameterError") :
                        4 == t.ErrorCode ? n("translate")("Member_TipsWithdralFail") :
                        10 == t.ErrorCode ? n("translate")("Member_TipsTrialUserNoService") :
                        11 == t.ErrorCode ? n("translate")("Member_LabelDespositDescription") : n("translate")("Member_TipsWithdralFail"))
                }
                  , a = function() {
                    c.hideLoadAndDisable("btnWithdraw")
                }
                ;
                d.addWithdraw(e.withdrawData).then(t, a)
            } else
                c.tips(n("translate")("Member_TipsPleaseInputWithdrawlPwd"));
        else
            c.tips(n("translate")("Common_LabelWithdrawPointMin0"))
    }
    ,
    e.getWallet = function() {
        function t(t) {
            0 == t.ErrorCode && (e.wallet = parseFloat(t.WalletBalanceList[0].Balance).toFixed(2),
            e.loaded = !0)
        }
        function a() {
            e.wallet = parseFloat(0).toFixed(2),
            e.loaded = !0
        }
        e.loaded = !1,
        d.getWallets({
            GameClassIDs: 0
        }).then(t, a)
    }
    ,
    e.getWallet(),
    e.getMemberBankList = function() {
        function t(t) {
            0 == t.ErrorCode ? t.Data.length > 0 ? (e.bankData = t.Data,
            e.getWithdrawLimit()) : (e.ansycLoaded = !0,
            e.url = C[2]) : c.timeout()
        }
        m.getMemberBankList().then(t)
    }
    ,
    e.getMemberBankList(),
    e.parseIntMoney = function(t) {
        t.target.value = parseInt(t.target.value),
        "NaN" == t.target.value ? (t.target.value = "",
        e.withdrawData.applyMoney = "") : e.withdrawData.applyMoney = parseInt(t.target.value)
    }
    ,
    e.getWithdrawLimit = function() {
        function t(t) {
            if (0 == t.ErrorCode) {
                if (t.Data)
                    switch (e.withdrawLimit = t.Data,
                    e.withdrawLimit.LimitType) {
                    case 1:
                        e.url = C[2];
                        break;
                    case 2:
                        e.url = C[0];
                        break;
                    case 3:
                        e.url = C[4];
                        break;
                    default:
                        e.url = C[1],
                        _(),
                        r(function() {
                            p()
                        }
                        , 1e3)
                    }
            } else
                c.timeout();
            e.ansycLoaded = !0
        }
        d.getWithdrawLimit().then(t)
    }
    ,
    e.getMemberAccount = function() {
        for (var t = 0; t < e.bankData.length; t++)
            e.bankData[t].MemberBankID == e.withdrawData.memberBankID && (e.memberAcccnout = e.bankData[t].BankName + " " + e.bankData[t].BankAccount)
    }
    ,
    e.confirm = function() {
        e.url = C[0]

    }
    ;
    var _ = function() {
        function t(t) {
            0 == t.ErrorCode && (e.OutAudit = parseFloat(t.Data.OutAudit).toFixed(2))
        }
        l.getMember2().then(t)
    }
}
]).controller("TransCtrl", ["$scope", "$timeout", "$filter", "$state", "$stateParams", "CashService", "QuickDialog", "Constants", "AuthService", function(e, t, a, o, n, r, i, s, l) {
    var m, c, u, d = 10, b = 1, p = "", C = n.type || 0;
    e.type = C,
    e.moreDataCanBeLoaded = !1,
    e.loaded = !1,
    e.ansycLoaded = !1,
    e.currentUser = l.currentUser,
    e.dwData = [],
    e.transData = [],
    e.giveData = [];
    var _ = function() {
        function o(t) {
            0 == t.ErrorCode ? (p = t.Data.SearchTime,
            e.moreDataCanBeLoaded = t.Data.List.length >= d ? !0 : !1,
            angular.forEach(t.Data.List, function(t) {
                m = new Date(Date.parse(t.ApplyDateTime.replace(/-/g, "/"))),
                c = a("date")(m, "yyyy-MM-dd"),
                u = a("date")(m, "HH:mm"),
                e.dwData.push(angular.extend(t, {
                    ApplyShortDate: c,
                    ApplyShortTime: u
                }))
            }
            )) : i.timeout(),
            e.ansycLoaded = !0,
            e.$broadcast("scroll.infiniteScrollComplete"),
            e.$broadcast("scroll.refreshComplete")
        }
        function n() {
            e.ansycLoaded = !0,
            e.moreDataCanBeLoaded = !1,
            e.dwData.length > 0 && e.dwData.length % d == 0 && t(function() {
                e.moreDataCanBeLoaded = !0
            }
            , 2e3),
            e.$broadcast("scroll.infiniteScrollComplete"),
            e.$broadcast("scroll.refreshComplete")
        }
        e.dwData.length > 0 && e.dwData.length % d == 0 && (b = parseInt(e.dwData.length / d) + 1),
        r.getDWReport({
            PageSize: d,
            CurrentPage: b,
            SearchTime: p
        }).then(o, n)
    }
     w = function() {
        function o(t) {
            0 == t.ErrorCode ? (p = t.Data.SearchTime,
            e.moreDataCanBeLoaded = t.Data.List.length >= d ? !0 : !1,
            angular.forEach(t.Data.List, function(t) {
                m = new Date(Date.parse(t.ApplyDateTime.replace(/-/g, "/"))),
                c = a("date")(m, "yyyy-MM-dd"),
                u = a("date")(m, "HH:mm"),
                e.dwData.push(angular.extend(t, {
                    ApplyShortDate: c,
                    ApplyShortTime: u
                }))
            }
            )) : i.timeout(),
            e.ansycLoaded = !0,
            e.$broadcast("scroll.infiniteScrollComplete"),
            e.$broadcast("scroll.refreshComplete")
        }
        function n() {
            e.ansycLoaded = !0,
            e.moreDataCanBeLoaded = !1,
            e.dwData.length > 0 && e.dwData.length % d == 0 && t(function() {
                e.moreDataCanBeLoaded = !0
            }
            , 2e3),
            e.$broadcast("scroll.infiniteScrollComplete"),
            e.$broadcast("scroll.refreshComplete")
        }
        e.dwData.length > 0 && e.dwData.length % d == 0 && (b = parseInt(e.dwData.length / d) + 1),
        r.getWReport({
            PageSize: d,
            CurrentPage: b,
            SearchTime: p
        }).then(o, n)
    }
      , g = function() {
        function o(t) {
            0 == t.ErrorCode ? (p = t.Data.SearchTime,
            e.moreDataCanBeLoaded = t.Data.List.length >= d ? !0 : !1,
            angular.forEach(t.Data.List, function(t) {
                m = new Date(Date.parse(t.TransferDateTime.replace(/-/g, "/"))),
                c = a("date")(m, "yyyy-MM-dd"),
                u = a("date")(m, "HH:mm"),
                e.transData.push(angular.extend(t, {
                    TransferShortDate: c,
                    TransferShortTime: u
                }))
            }
            )) : i.timeout(),
            e.ansycLoaded = !0,
            e.$broadcast("scroll.infiniteScrollComplete"),
            e.$broadcast("scroll.refreshComplete")
        }
        function n() {
            e.ansycLoaded = !0,
            e.moreDataCanBeLoaded = !1,
            e.transData.length > 0 && e.transData.length % d == 0 && t(function() {
                e.moreDataCanBeLoaded = !0
            }
            , 2e3),
            e.$broadcast("scroll.infiniteScrollComplete"),
            e.$broadcast("scroll.refreshComplete")
        }
        e.transData.length > 0 && e.transData.length % d == 0 && (b = parseInt(e.transData.length / d) + 1),
        r.getTransReport({
            PageSize: d,
            CurrentPage: b,
            SearchTime: p
        }).then(o, n)
    }
      , f = function() {
        function o(t) {
            0 == t.ErrorCode ? (p = t.Data.SearchTime,
            e.moreDataCanBeLoaded = t.Data.List.length >= d ? !0 : !1,
            angular.forEach(t.Data.List, function(t) {
                m = new Date(Date.parse(t.GiveDateTime.replace(/-/g, "/"))),
                c = a("date")(m, "yyyy-MM-dd"),
                u = a("date")(m, "HH:mm"),
                e.giveData.push(angular.extend(t, {
                    GiveShortDate: c,
                    GiveShortTime: u
                }))
            }
            )) : i.timeout(),
            e.ansycLoaded = !0,
            e.$broadcast("scroll.infiniteScrollComplete"),
            e.$broadcast("scroll.refreshComplete")
        }
        function n() {
            e.ansycLoaded = !0,
            e.moreDataCanBeLoaded = !1,
            e.giveData.length > 0 && e.giveData.length % d == 0 && t(function() {
                e.moreDataCanBeLoaded = !0
            }
            , 2e3),
            e.$broadcast("scroll.infiniteScrollComplete"),
            e.$broadcast("scroll.refreshComplete")
        }
        e.giveData.length > 0 && e.giveData.length % d == 0 && (b = parseInt(e.giveData.length / d) + 1),
        r.getGiveReport({
            PageSize: d,
            CurrentPage: b,
            SearchTime: p
        }).then(o, n)
    }
    ;
    e.loadMoreData = function() {
        switch (C) {
        case 2:
            _();
            break;
        case 1:
            g();
            break;
        case 0:
            w()
        }
    }
    ,
    e.refresh = function() {
        e.dwData = [],
        e.transData = [],
        e.giveData = [],
        p = "",
        b = 1,
        e.ansycLoaded = !1,
        e.moreDataCanBeLoaded = !1,
        e.loadMoreData()
    }
    ,
    e.onChange = function(t) {
        C = t,
        e.type = C,
        e.refresh()
    }
    ,
    e.getWallet = function() {
        function t(t) {
            0 == t.ErrorCode && (e.wallet = parseFloat(t.WalletBalanceList[0].Balance).toFixed(2),
            e.loaded = !0)
        }
        function a() {
            e.wallet = parseFloat(0).toFixed(2),
            e.loaded = !0
        }
        e.loaded = !1,
        r.getWallets({
            GameClassIDs: 0
        }).then(t, a)
    }
    ,
    e.getWallet(),
    e.refresh(),
    e.showReason = function(e, t, a) {
        2 == t && a && "" != a && i.alert(a)
    }
    ,
    e.showRemark = function(e) {
        e && "" != e && i.alert(e)
    }
    ,
    e.getStateText = function(e, t) {
        var o = "";
        if (2 == e)
            switch (t) {
            case 0:
                o = a("translate")("Member_LabelWaitWithdraw");
                break;
            case 1:
                o = a("translate")("Member_LabelWithdrawSuccess");
                break;
            case 2:
            case 3:
                o = a("translate")("Member_LabelWithdrawFail")
                break;
            case 4:
                o = a("translate")("Member_LabelDoItPleaseWait");
                break;
            }
        else if (1 == e)
            switch (t) {
            case 1:
                o = a("translate")("Member_LabelDepositSuccess");
                break;
            case 2:
                o = a("translate")("Member_LabelDepositFail");
                break;
            case 3:
                o = a("translate")("Member_LabelWaitDeposit");
                break;
            case 4:
                o = a("translate")("Member_LabelDepositing")
            }
        return o
    },
    e.getStateText1 = function(e, t) {
        var o = "";
        if (1 == e) //公司入款
            switch (t) {
            case 0:
                o = a("translate")("Member_LabelWaitDeposit"); //等待入款
                break;
            case 1:
                o = a("translate")("Member_LabelDepositSuccess");
                break;
            case 2:
                o = a("translate")("Member_LabelNODeposit");
                break;
            }
        else if (2 == e)   //線上入款
            switch (t) {
            case 0:
            o = a("translate")("Member_LabelDepositweiOl");
                break;
            case 1:
                o = a("translate")("Member_LabelDepositSuccess");
                break;
            case 2:
                o = a("translate")("Member_LabelDepositNoOl");
                break;
            }
        return o
    }
}
]),
angular.module("controllers.register", []).controller("RegCtrl", ["$scope", "$state", "$ionicSlideBoxDelegate", "$filter", "$ionicScrollDelegate", "$location", "$timeout", "$ionicPopup", "utils", "AuthService", "RegService", "AccountService", "QuickDialog", "Constants", "AppConfig", "AppLanguage", "ChooseService", function(e, t, a, o, n, r, i, s, l, m, c, u, d, b, p, C, _) {
    angular.element(document).ready(function() {
        e.ucFlag = l.checkBrowser("uc");
        var t = "android" == l.getBrowser();
        t && angular.element(document.querySelectorAll(".scroll")).removeClass("scroll"),
        e.ucFlag && angular.element(document.querySelectorAll(".view-container")).css({
            position: "fixed"
        })
    }
    ),
    e.regData = {
        agreement: !0
    },
    e.countryData = [],
    e.currencyData = [],
    e.memberNameExist = !1,
    e.realNameExist = !1,
    e.pwdLevel = 0,
    e.siteName = p.getSiteName(C.get());
    var g, f = C.get(), h = "";
    try {
        h = document.referrer;
        var L = r.host();
        h.toLowerCase().indexOf(L.toLowerCase()) > -1 && (h = "")
    } catch (M) {
        h = ""
    }
    var T = t.params.code;
    T && T.length > 0 && c.setPromoCodeCache(T),
    3 == T.split("-").length && 1 == T.split("-")[2] && t.go("index"),
    e.disableSwipe = function() {
        e.slide = 0,
        a.slide(0),
        a.enableSlide(!1)
    }
    ,
    e.agreement = function() {
        s.show({
            title: o("translate")("Common_LabelAccountAgreement"),
            cssClass: "agreement",
            templateUrl: "/templates/" + t.params.template + "/register/agreement.html",
            scope: e,
            buttons: [{
                text: o("translate")("Common_ButtonSure"),
                type: "button-positive"
            }]
        })
    }
    ;
    var y = function() {
        function t(t) {
            b.DEBUGMODE,
            0 == t.ErrorCode ? (e.countryData = t.Data,
            1 == e.countryData.length && (e.regData.CountryNo = e.countryData[0].CountryNo,
            e.regData.CountryName = e.countryData[0].CountryName,
            e.getCurrencyData(e.countryData[0].CountryNo))) : d.timeout()
        }
        p.getCountryData(f).then(t)
    }
    ;
    e.getCurrencyData = function(t) {
        function a(t) {
            b.DEBUGMODE,
            0 == t.ErrorCode ? (e.currencyData = t.Data,
            e.regData.CurrencyNo = "",
            1 == e.currencyData.length && (e.regData.CurrencyNo = e.currencyData[0].CurrencyNo,
            e.regData.CurrencyName = e.currencyData[0].CurrencyName)) : d.timeout()
        }
        angular.isUndefined(t) || "" == t ? (e.currencyData = [],
        e.regData.CurrencyNo = "") : p.getCurrencyData(f, t).then(a)
    }
    ,
    y(),
    e.checkMemberName = function(t) {
        function a(t) {
            0 == t.ErrorCode && 1 == t.Data.MemberNameExist && (e.memberNameExist = !0),
            e.EmailCS = t.Data.EmailCS == 0 ? true:false,
            e.PassPortCS = t.Data.PassPortCS == 0 ? true:false,
            e.QQCS = t.Data.QQCS == 0 ? true:false,
            e.MobileCS = t.Data.MobileCS == 0 ? true:false,
            e.EmailMT = t.Data.EmailMT == 1 ? true:false,
            e.PassPortMT = t.Data.PassPortMT == 1 ? true:false,
            e.QQMT = t.Data.QQMT == 1 ? true:false,
            e.MobileMT = t.Data.MobileMT == 1 ? true:false
        }
        e.memberNameExist = !1;
        angular.isDefined(t) && "" != t && (0 == t.toLowerCase().indexOf("TEST") ? e.memberNameExist = !0 : c.verifyRegisterMember({
            MemberName: t
        }).then(a))
    },
    e.parseNum = function (a) {
        a.target.value = parseInt(a.target.value);
        "NaN" == a.target.value ? (a.target.value = "",
            e.regData.WithdrawPwd = "") : a.target.value.length > 4 && (a.target.value = "",
            e.regData.WithdrawPwd  = "")
    },
    e.checkRealName = function(t) {
        function a(t) {
            b.DEBUGMODE,
            0 == t.ErrorCode && 1 == t.Data.RealNameExist && (e.realNameExist = !0)
        }
        e.realNameExist = !1,
        angular.isDefined(t) && "" != t && c.verifyRegisterMember({
            RealName: t
        }).then(a)
    }
    ,
    e.checkEmail = function(t) {
        function a(t) {
            b.DEBUGMODE,
            0 == t.ErrorCode && 1 == t.Data.EMailExist && (e.emailExist = !0)
        }
        e.emailExist = !1,
        angular.isDefined(t) && "" != t && c.verifyRegisterMember({
            EMail: t
        }).then(a)
    }
    ,
    e.checkPhone = function(t, a) {
        function o(t) {
            b.DEBUGMODE,
            0 == t.ErrorCode && 1 == t.Data.PhoneExist && (e.phoneExist = !0)
        }
        e.phoneExist = !1,
        angular.isDefined(t) && "" != t && angular.isDefined(a) && "" != a && c.verifyRegisterMember({
            Phone: t + " " + a
        }).then(o)
    }
    ,
    e.checkQQ = function(t) {
        function a(t) {
            b.DEBUGMODE,
            0 == t.ErrorCode && 1 == t.Data.QQExist && (e.QQExist = !0)
        }
        e.QQExist = !1,
        angular.isDefined(t) && "" != t && c.verifyRegisterMember({
            QQ: t
        }).then(a)
    }
    ,
    e.checkPwdLevel = function(t) {
        e.pwdLevel = 0,
        t && "" != t && t.length >= 6 && t.length <= 20 && (/\d/.test(t) && e.pwdLevel++,
        /[a-z]+/.test(t) && e.pwdLevel++,
        /[A-Z]+/.test(t) && e.pwdLevel++,
        /[_]+/.test(t) && e.pwdLevel++)
    }
    ,
    e.memberNameKeydown = function() {
        e.memberNameExist && (e.memberNameExist = !1)
    }
    ,
    e.realNameKeydown = function() {
        e.realNameExist && (e.realNameExist = !1)
    }
    ;
    var w = function(t) {
        function r(r) {
            if (d.hideLoadAndDisable("btnRegister", !0),
            0 == r.ErrorCode) {
                var i = {
                    SiteNo: p.getSiteNo(),
                    LanguageNo: f,
                    MemberName: t.MemberName,
                    Pwd: t.Pwd
                };
                e.slide = 2,
                a.slide(2),
                n.scrollTo(0, 0),
                c.removePromoCodeCache(),
                m.login(i).then(function() {
                    u.setUserNameCache(t.MemberName)
                }
                )
            } else {
                1 == r.ErrorCode ? d.alert(o("translate")("Common_TipsEmailIsExist"), e.goStep1()) :
                2 == r.ErrorCode ? d.alert(o("translate")("Common_TipsPhoneIsExist"), e.goStep1()) :
                3 == r.ErrorCode ? d.alert(o("translate")("Common_TipsAccountIsExists"), e.goStep1()) :
                4 == r.ErrorCode ? d.alert(o("translate")("Common_TipsAccountFormatIsError"), e.goStep1()) :
                5 == r.ErrorCode ? d.alert(o("translate")("Common_TipsPasswordFormatIsError"), e.goStep1()) :
                6 == r.ErrorCode ? d.alert(o("translate")("Common_TipsDrawPasswordFormatIsError"), e.goStep1()) :
                7 == r.ErrorCode ? d.alert(o("translate")("Common_LabelRealNameBeen"), e.goStep1()) :
                8 == r.ErrorCode ? d.alert(o("translate")("Common_ValidPasswordSame"), e.goStep1()) :
                10 == r.ErrorCode ? d.alert(o("translate")("Common_LabelQQBeen"), e.goStep1()) :
                11 == r.ErrorCode ? d.alert(o("translate")("Common_TipsPwdDifferent"), e.goStep1()) :
                12 == r.ErrorCode ? d.alert(o("translate")("Common_TipsTryEmailPatterError"), e.goStep1()) :
                13 == r.ErrorCode ? d.alert(o("translate")("Common_TipsTryQQPatterError"), e.goStep1()) :
                14 == r.ErrorCode ? d.alert(o("translate")("Common_TipsTryNAMEPatterError"), e.goStep1()) :
                15 == r.ErrorCode ? d.alert(o("translate")("Common_VaildDifferentdrawPwd"), e.goStep1()) :
                101 == r.ErrorCode ? d.alert(o("translate")("Common_TipsRegisterError"), e.goStep1()) :
                d.alert(o("translate")("Common_TipsRegisterError"), e.goStep1())
            }
        }
        var i = c.getPromoCodeCache()
          , s = ""
          , l = "";
        i.length > 0 && (i.indexOf("-") > -1 ? (s = i.split("-")[0],
        l = i.split("-")[1]) : s = i),
        angular.extend(t, {
            PromoCode: s,
            FromCode: l,
            FromUrl: h
        });
        var b = function() {
            d.hideLoadAndDisable("btnRegister")
        }
        ;
        c.registerMember(t).then(r, b)
    }
    ;
    e.goStep2 = function(t) {
        e.memberNameExist || t.MemberName == t.Pwd || (e.slide = 1,
        a.slide(1),
        n.scrollTo(0, 0),
        e.regData.CountryAreaCode && "" != e.regData.CountryAreaCode || (e.regData.CountryAreaCode = o("filter")(e.countryData, {
            CountryNo: t.CountryNo
        }, !0)[0].CountryAreaCode))
    }
    ,
    e.goStep1 = function() {
        e.slide = 0,
        a.slide(0),
        n.scrollTo(0, 0)
    }
    ,
    e.goStep3 = function(t) {
        if (0 == t.MemberName.toLowerCase().indexOf("TEST"))
            return e.memberNameExist = !0,
            void e.goStep1();
        e.goStep2(t);
        if (t.Pwd != t.WithdrawPwd) {
            d.loadAndDisable("btnRegister");
            var a = function(a) {
                0 == a.ErrorCode && (1 == a.Data.MemberNameExist && (e.memberNameExist = !0),
                1 == a.Data.RealNameExist && (e.realNameExist = !0),
                1 == a.Data.EMailExist && (e.emailExist = !0),
                1 == a.Data.PhoneExist && (e.phoneExist = !0),
                e.memberNameExist || e.realNameExist || e.emailExist || e.phoneExist ? e.memberNameExist ? (d.hideLoadAndDisable("btnRegister", !0),
                e.goStep1()) : d.hideLoadAndDisable("btnRegister", !0) : w(t))
            }
              , o = function() {
                d.hideLoadAndDisable("btnRegister")
            }
              , n = {
                RealName: t.RealName,
                MemberName: t.MemberName,
            };
            c.verifyRegisterMember(n).then(a, o)
        }
    }
    ,
    e.countryChoose = function() {
        g = new _({
            data: e.countryData,
            selected: e.regData.CountryAreaCode,
            scope: e,
            onSelect: function(t, a) {
                e.regData.CountryAreaCode = a
            },
            onTap: function(t) {
                "" != e.regData.CountryAreaCode && /^\d{0,5}$/.test(e.regData.CountryAreaCode) || (d.tips(o("translate")("Common_LabelInputAreaNumber")),
                t.preventDefault()),
                e.checkPhone(e.regData.CountryAreaCode, e.regData.PhoneNum)
            }
        })
    }
}
]),
angular.module("controllers.bank", []).controller("BankCtrl", ["$scope", "$state", "$ionicPopup", "$filter", "BankService", "QuickDialog", "Constants", function(e, t, a, o, n, r, i) {
    function s() {
        i.DEBUGMODE,
        r.timeout()
    }
    function l(t) {
        0 == t.ErrorCode ? t.Data.length > 0 ? e.bankData = t.Data : e.showAdd = !0 : r.timeout()
    }
    function m(t) {
        if (r.hideLoading(),
        0 == t.ErrorCode) {
            var a = -1;
            angular.forEach(e.bankData, function(e, t) {
                e.MemberBankID == c && (a = t)
            }
            ),
            a >= 0 && (e.bankData.splice(a, 1),
            0 == e.bankData.length && (e.showAdd = !0));
            var n = o("translate")("Common_TipsSuccessDeleteBank")
              , i = 2e3;
            r.tips(n, i)
        } else {
            var n = o("translate")("Common_TipsFailDeleteBank")
              , i = 2e3;
            r.tips(n, i)
        }
    }
    e.bankData = [],
    e.showAdd = !1;
    var c = 0;
    n.getMemberBankList().then(l),
    e.addBank = function() {
        e.bankData.length >= 2 ? r.tips(o("translate")("Member_TipsMaxAddBank"), 2e3) : t.go("mdfbank", {
            id: 0
        })
    }
    ,
    e.mdfBank = function(e) {
        t.go("mdfbank", {
            id: e
        })
    }
    ,
    e.delBank = function(e) {
        a.confirm({
            title: o("translate")("Member_TipsConfirmDeleteBankAccount"),
            cancelText: o("translate")("Common_ButtonCancel"),
            cancelType: "button-light",
            okText: o("translate")("Common_ButtonSure")
        }).then(function(t) {
            t && (c = e,
            r.loading(),
            n.removeMemberBank({
                MemberBankID: c
            }).then(m, s))
        }
        )
    }
}
]).controller("MdfBankCtrl", ["$rootScope", "$scope", "$state", "$ionicHistory", "$timeout", "$filter", "$ionicScrollDelegate", "utils", "AuthService", "AppConfig", "BankService", "Constants", "QuickDialog", function(e, t, a, o, n, r, i, s, l, m, c, u, d) {
    angular.element(document).ready(function() {
        t.ucFlag = s.checkBrowser("uc");
        var e = "android" == s.getBrowser();
        if (e && angular.element(document.querySelectorAll(".scroll")).removeClass("scroll"),
        t.ucFlag) {
            var a, o = angular.element(document.querySelectorAll(".uc-set-scroll"));
            o.on("click", function() {
                var e = angular.element(this).parent().prop("offsetTop");
                a = i.getScrollPosition(),
                i.scrollTo(0, e - 20)
            }
            ),
            o.on("blur", function() {
                i.scrollTo(a.left, a.top)
            }
            )
        }
    }
    ),
    t.ansycLoaded = !1,
    t.bankData = [],
    t.provinceData = [],
    t.cityData = [],
    t.realName = l.currentUser.RealName || "",
    t.qkpwd    = l.currentUser.qkpwd || "",
    t.showProvince = "china" == angular.lowercase(l.currentUser.CountryNo),
    t.mdfBankData = {};
    var b = a.params.id
      , p = function() {
        function e(e) {
            0 == e.ErrorCode && (t.bankData = e.Data),
            C()
        }
        c.getBankData().then(e)
    }
      , C = function() {
        function e(e) {
            u.DEBUGMODE,
            0 == e.ErrorCode && (t.provinceData = e.Data,
            _())
        }
        m.getProvinceData().then(e)
    }
      , _ = function() {
        function e(e) {
            t.ansycLoaded = !0,
            0 == e.ErrorCode && (t.mdfBankData = e.Data,
            t.getCityData(t.mdfBankData.BankProvince, t.mdfBankData.BankCity))
        }
        b > 0 ? c.getMemberBankInfo({
            MemberBankID: b
        }).then(e) : t.ansycLoaded = !0
    }
    ;
    t.getCityData = function(e, a) {
        function o(e) {
            u.DEBUGMODE,
            0 == e.ErrorCode && (t.cityData = e.Data,
            t.mdfBankData.BankCity = a ? a : "")
        }
        e && "" != e && m.getCityData(e).then(o)
    }
    ,
    t.mdfBank = function(t) {
        function a(t) {
            if (d.hideLoading(),
            0 == t.ErrorCode) {
                var a = r("translate")("Common_TipsSuccessAddBank");
                b > 0 && (a = r("translate")("Common_TipsSuccessEditBank"));
                var o = 1500;
                d.tips(a, o, function() {
                    e.ionicBack()
                }
                )
            } else if (1 == t.ErrorCode) {
                var a = r("translate")("Member_TipsAddTwoBank")
                  , o = 1500;
                d.tips(a, o)
            } else {
                var a = r("translate")("Common_ButtonSaveFail")
                  , o = 1500;
                d.tips(a, o)
            }
        }
        d.loading(),
        c.saveMemberBank(t).then(a)
    }
    , t.parseNum = function (a) {
        var re = /^[1-9]+[0-9]*]*$/;
        !re.test(a.target.value) ? (a.target.value = "",
                e.mdfBankData.BankAccount = "") : ""
        },
    p()
}
]);
/*
angular.module("MaYaOfficial").run(["$templateCache", function(e) {
    e.put("/templates/common/error.html", '<ion-view cache-view="false"><ion-nav-bar class="bar bar-header bar-light"><ion-nav-title>{{\'Common_TitleNetWorkError\' | translate}}</ion-nav-title><ion-nav-buttons side="left"><a class="button button-icon icon ion-ios-arrow-back" ng-href="#/index"></a></ion-nav-buttons></ion-nav-bar><ion-content class="has-header" scroll="false"><div class="m-error"><img ng-src="{{CDNURL+\'images/network-error.png\'}}"><h4>{{\'Common_TipsNoServe\' | translate}}</h4></div></ion-content></ion-view>'),
    e.put("/templates/common/inGame.html", '<ion-view hide-nav-bar="true" class="ingame-blank"><ion-content scroll="false"></ion-content></ion-view>'),
    e.put("/templates/common/maintain.html", '<ion-view cache-view="false"><ion-nav-bar class="bar bar-header bar-light"><ion-nav-title>{{\'Common_TitleSysMaintain\' | translate}}</ion-nav-title><ion-nav-buttons side="left"><a class="button button-icon icon ion-ios-arrow-back" ng-href="#/index"></a></ion-nav-buttons></ion-nav-bar><ion-content class="has-header"><div class="m-maintain"><img ng-src="{{CDNURL+\'images/maintain.png\'}}"><h4>{{\'Common_TipsSystemMaintain\' | translate}}</h4><div ng-if="start.length>0&&end.length>0"><p>--- {{\'Common_TipsSystemMaintainTime\' | translate}} ---</p><p class="time"><span>{{start}}</span><span>{{\'Member_LabelTo\' | translate}}</span><span>{{end}}</span></p></div></div></ion-content></ion-view>'),
    e.put("/templates/common/thirdpay.html", '<ion-view hide-nav-bar="true"><ion-content scroll="false"></ion-content></ion-view>')
}
]);*/
