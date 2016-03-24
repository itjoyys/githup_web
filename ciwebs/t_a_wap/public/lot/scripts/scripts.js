/**
 * created by bang on 2015/4/15.
 * edit by yang on 2015/4/15.
 */
"use strict";
angular.module("PKLottery", ["ionic", "ngCordova", "ngCookies", "components.localStorage", "services.common.auth", "services.common.store", "services.common.utils", "services.common.dialog", "services.common.constants", "services.common.lottery.Delegate", "services.common.language", "app.common.theme", "services.lottery.info", "services.lottery.bet", "services.lottery.account", "services.lottery.cash", "services.lottery.notice", "controllers.index", "controllers.transfer", "controllers.gaoPin","controllers.cqKuaiLeShiFen","controllers.gdKuaiLeShiFen", "controllers.hongKongCai", "controllers.beiJingKuailLeBa", "controllers.lottery", "controllers.notcount", "controllers.count", "controllers.history", "controllers.changLong", "controllers.luZhu", "controllers.error", "controllers.logonfailure", "controllers.sso", "controllers.login", "controllers.limit", "angular.filter", "filters.app", "directives.menu", "directives.lottery", "directives.repeatn", "directives.resetfield", "directives.tips"]).run(["$ionicPlatform", "$state", "$rootScope", "Constants", "$ionicLoading", "$filter", "LangService", "QuickDialog", "AuthService", function (t, a, i, s, e, d, n, l, o) {
    o.init(),
        i.$on("event:auth-loginRequired", function () {
                o.resetCache(),
                    a.go("logonFailure")
            }
        ),
        i.$on("event:sys-maintenance", function () {
                a.go("maintain")
            }
        ),
        i.$on("event:sys-error", function (t, i) {
                a.go("error", {
                    contents: i
                })
            }
        ),
        i.$on("$stateChangeStart", function (t, i, s) {
                l.loading(),
                i.authenticate && (n.success ? s.template = "default" : (t.preventDefault(),
                    n.setDefault().then(function () {
                                a.go(i.name, s)
                        }
                    ))),
                i.authenticate && !o.isLoggedIn()
            }
        ),
        i.$on("$stateChangeSuccess", function () {
                l.hideLoading()
            }
        ),
        i.$on("$stateChangeError", function (t) {
                t.preventDefault(),
                    l.hideLoading()
            }
        ),
        i.CDNURL = s.CDNURL
}
]).config(["$stateProvider", "$urlRouterProvider", "$ionicConfigProvider", function (t, a, i) {
    i.navBar.alignTitle("center"),
        i.views.transition("none"),
        t.state("index", {
            url: "/index",
            controller: "IndexCtrl",
            templateUrl: function (t) {
                return "/templates/" + t.template + "/lot_index.html"
            },
            authenticate: !0
        }).state("login", {
            url: "/login",
            controller: "LoginCtrl",
            templateUrl: "/templates/common/login.html",
            authenticate: !1
        }).state("maintain", {
            url: "/maintain",
            controller: ["$scope", "$stateParams", function (t) {
                t.uri = function () {
                    window.location = a(window.location.host)
                }
                ;
                var a = function (t) {
                    //return document.getElementById("APIURL").value;//"http://wapindexci.com/";
                    return -1 != t.indexOf(".") ? window.location.protocol + "//m." + t.substring(t.indexOf(".") + 1) : window.location.href
                }
            }
            ],
            templateUrl: "/templates/common/maintain.html",
            authenticate: !1
        }).state("error", {
            url: "/error",
            params: {
                contents: ""
            },
            controller: "ErrorCtrl",
            templateUrl: "/templates/common/error.html",
            authenticate: !1
        }).state("logonFailure", {
            url: "/logonFailure",
            templateUrl: "/templates/common/logonFailure.html",
            controller: "LogonFailureCtrl",
            authenticate: !1
        }).state("lottery", {
            url: "/lottery",
            controller: "LotteryCtrl",
            templateUrl: function (t) {
                return "/templates/" + t.template + "/lottery/index.html"
            },
            authenticate: !0
        }).state("notcount", {
            url: "/notcount/:rid",
            controller: "NotCountCtrl",
            templateUrl: function (t) {
                return "/templates/" + t.template + "/account/notcount/list.html"
            },
            authenticate: !0
        }).state("notcountDetail", {
            url: "/notcount/detail/:rid/:lid/:name",
            controller: "NotCountDetailCtrl",
            templateUrl: function (t) {
                return "/templates/" + t.template + "/account/notcount/detail.html"
            },
            authenticate: !0
        }).state("count", {
            url: "/count/:rid",
            controller: "CountByWeekCtrl",
            templateUrl: function (t) {
                return "/templates/" + t.template + "/account/count/week.html"
            },
            authenticate: !0
        }).state("limit", {
            url: "/limit/:rid",
            controller: "LimitCtrl",
            templateUrl: function (t) {
                return "/templates/" + t.template + "/account/limit.html"
            },
            authenticate: !0
        }).state("countByDay", {
            url: "/countByDay/:rid/:day",
            controller: "CountByDayCtrl",
            templateUrl: function (t) {
                return "/templates/" + t.template + "/account/count/day.html"
            },
            authenticate: !0
        }).state("countByDetail", {
            url: "/countByDetail/:rid/:day/:id/:name",
            controller: "CountByDetailCtrl",
            templateUrl: function (t) {
                return "/templates/" + t.template + "/account/count/detail.html"
            },
            authenticate: !0
        }).state("history", {
            url: "/history/:rid",
            controller: "HistoryCtrl",
            templateUrl: function (t) {
                return "/templates/" + t.template + "/account/history.html"
            },
            authenticate: !0
        }).state("changLong", {
            url: "/changLong/:rid",
            controller: "ChangLongCtrl",
            templateUrl: function (t) {
                return "/templates/" + t.template + "/account/changLong.html"
            },
            authenticate: !0
        }).state("luZhu", {
            url: "/luZhu/:rid",
            controller: "LuZhuCtrl",
            templateUrl: function (t) {
                return "/templates/" + t.template + "/account/luZhu.html"
            },
            authenticate: !0
        }).state("transfer", {
            url: "/transfer",
            controller: "TransferCtrl",
            authenticate: !0
        }).state("sso", {
            url: "/sso/:v/:p",
            controller: "SsoCtrl",
            templateUrl: "/templates/common/sso.html",
            authenticate: !1
        }),
        a.otherwise(function (t) {
                var a = t.get("$state");
                a.go("index")
            }
        )
}
]),
    angular.module("components.localStorage", []).factory("$cordovaLocalStorage", ["$cordovaCookieStorage", function (t) {
        var a = {
            key: function (t) {
                return window.localStorage.key(t)
            },
            setItem: function (t, a) {
                return window.localStorage.setItem(t, a)
            },
            getItem: function (t) {
                return window.localStorage.getItem(t)
            },
            removeItem: function (t) {
                return window.localStorage.removeItem(t)
            },
            clear: function () {
                return window.localStorage.clear()
            }
        };
        try {
            return null !== window.localStorage ? (window.localStorage.setItem("testkey", "foo"),
                window.localStorage.removeItem("testkey"),
                a) : t
        } catch (i) {
            return t
        }
    }
    ]).factory("$cordovaCookieStorage", ["$cookies", function (t) {
        return {
            key: function (a) {
                return t[a]
            },
            setItem: function (a, i) {
                t[a] = i
            },
            getItem: function (a) {
                var i = t[a];
                return i
            },
            removeItem: function (a) {
                return delete t[a]
            },
            clear: function () {
                for (var a in t)
                    delete t[key];
                return !0
            }
        }
    }
    ]),
    angular.module("filters.app", ["pascalprecht.translate"]).filter("jsonDate", ["$filter", function (t) {
        return function (a, i) {
            return t("date")(parseInt(a.replace("/Date(", "").replace(")/", "")), i)
        }
    }
    ]).filter("amount", function () {
            function t(t) {
                var a = t >= 0 ? "positive" : "balanced";
                return null != t ? '<span class="' + a + '">' + t.toFixed(2) + "</span>" : '<span class="' + a + '">' + t + "</span>"
            }

            return t.$stateful = !0,
                t
        }
    ).filter("realName", function () {
            function t(t) {
                return "undefined" == typeof t ? "" : "[" + t + "]"
            }

            return t.$stateful = !0,
                t
        }
    ).filter("formas", ["$filter", function (t) {
        function a(a, i, s) {
            var e = t("translate")(i);
            return null != a && (e = e.format(a, s)),
                e
        }

        return String.prototype.format = function (t) {
            var a = this;
            if (arguments.length > 0)
                if (1 == arguments.length && "object" == typeof t) {
                    for (var i in t)
                        if (void 0 != t[i]) {
                            var s = new RegExp("({" + i + "})", "g");
                            a = a.replace(s, t[i])
                        }
                } else
                    for (var e = 0; e < arguments.length; e++)
                        if (void 0 != arguments[e]) {
                            var s = new RegExp("({[" + e + "]})", "g");
                            a = a.replace(s, arguments[e])
                        }
            return a
        }
            ,
            a.$stateful = !0,
            a
    }
    ]).filter("yd", ["$filter", function () {
        function t(t, a, i) {
            var s = ["01", "02", "07", "08", "12", "13", "18", "19", "23", "24", "29", "30", "34", "35", "40", "45", "46"]
                , e = ["03", "04", "09", "10", "14", "15", "20", "25", "26", "31", "36", "37", "41", "42", "47", "48"]
                , d = ["05", "06", "11", "16", "17", "21", "22", "27", "28", "32", "33", "38", "39", "43", "44", "49"];
            Array.prototype.contains = function (t) {
                for (var a = this.length; a--;)
                    if (this[a] === t)
                        return !0;
                return !1
            };
            var n = "";
            return null != t && "" != t && void 0 != t && angular.forEach(t.split(","), function (t, l) {
                    if ("3" == a || "4" == a || "5" == a) {
                        null != i && "" != i && void 0 != i && i == l && (n += "<span class='round-" + a + " transparent ion-android-add'></span>");
                        var o = "0";
                        -1 != t.indexOf(":") ? (o = t.split(":")[0],
                        1 == o.length && (o = "0" + o),
                            t = t.split(":")[1]) : 1 == t.length ? (o = "0" + t,
                            t = "0" + t) : (o = t,
                            t = t),
                            s.contains(o) ? n += "<span class='round-" + a + " red'>" + t + "</span>" : e.contains(o) ? n += "<span class='round-" + a + " blue'>" + t + "</span>" : d.contains(o) && (n += "<span class='round-" + a + " green'>" + t + "</span>")
                    } else
                        "6" == a ? (null != i && "" != i && void 0 != i && i == l && (n += "<span class='round-5 transparent '></span>"),
                            n += "<span class='round-5 transparent'>" + t + "</span>") : n += "2" == a ? "<span class='round-2'>" + t + "</span>" : "7" == a ? "<span class='round-6 data-" + t + "'>" + t + "</span>" : "8" == a ? "<span class='round-8 data-" + t + "'>" + t + "</span>" : "<span class='round'  >" + t + "</span>"
                }
            ),
                n
        }

        return t.$stateful = !0,
            t
    }
    ]);
angular.module("services.common.dialog", []).factory("QuickDialog", ["$ionicLoading", "$ionicPopup", "$filter", function (t, a, i) {
    var s = {
        loading: function (a, i) {
            a || (a = 1e4),
            i || (i = "<ion-spinner></ion-spinner>"),
                t.show({
                    template: i,
                    duration: a,
                    noBackdrop: !0,
                    delay: 100
                })
        },
        hideLoading: function () {
            t.hide()
        },
        tips: function (a) {
            t.show({
                template: a,
                duration: 1500
            })
        },
        timeout: function () {
            var t = i("translate")("Member_TipsOutOfTime");
            this.tips(t)
        },
        alert: function (t, i) {
            a.show({
                title: t,
                buttons: [{
                    text: "OK",
                    onTap: function () {
                        angular.isFunction(i) && i()
                    }
                }]
            })
        },
        alertCancelConfirm: function (t, s, e) {
            a.show({
                title: t,
                buttons: [{
                    text: i("translate")("Common.ButtonCancel"),
                    onTap: function () {
                        angular.isFunction(s) && s()
                    }
                }, {
                    text: i("translate")("LotteryManagent.ButtonSave"),
                    type: "button-positive",
                    onTap: function () {
                        angular.isFunction(e) && e()
                    }
                }]
            })
        }
    };
    return s
}
]),
    angular.module("services.common.auth", ["components.localStorage"]).service("AuthService", ["$http", "$rootScope", "$location", "$q", "$cordovaLocalStorage", "utils", "LangService", "ThemeConfig", "LotteryInfoService", "$state", function (t, a, i, s, e, d, n, l) {
        var o = {
            MemberName: null,
            LanguageNo: "",
            PageStyle: null
        },oo = {
            WebName: "",
        }
            , c = {
            SESSION_TOKEN: "SESSION_TOKEN",
            OA_APP_USER: "OA_APP_USER",
            OA_LANGUAGE: "OA_LANGUAGE",
            BASE_INFO: "BASE_INFO",
            APP_LANGUAGE: "APP_LANGUAGE"
        }
            , v = {
            currentUser: {},
            init: function () {
                var a = this;
                t.defaults.headers.common["X-Access-Token"] = e.getItem(c.SESSION_TOKEN) || "";
                var i = e.getItem(c.OA_APP_USER) || o;
                "string" == typeof i && (i = JSON.parse(i)),
                    a.currentUser = i,
                a.currentUser.PageStyle && l.setTheme(a.currentUser.PageStyle);
                var ii = e.getItem("APP_CONFIG_DATA") || oo;
                "string" == typeof ii && (ii = JSON.parse(ii)),
                angular.element(document.querySelector("#apptitle")).text("" == ii.WebName ? l("translate")("PageTitle.LotteryGame") :ii.WebName);
            },
            siteinfo:function(){
                var a = this;
                var i = e.getItem("APP_CONFIG_DATA") || oo;
                "string" == typeof i && (i = JSON.parse(i)),
               a.siteInfo = i;
            },
            updateUser: function (t, a) {
                var i = this
                    , s = {
                    remove: !1,
                    set: !1
                };
                angular.extend(s, a),
                    angular.extend(i.currentUser, t),
                s.remove === !0 && (e.removeItem(c.OA_APP_USER),
                    i.currentUser = {}),
                s.set === !0 && (e.setItem(c.OA_APP_USER, JSON.stringify(i.currentUser)),
                    e.setItem(c.APP_LANGUAGE, i.currentUser.LanguageNo),
                    l.setTheme(i.currentUser.PageStyle))
            },
            isLoggedIn: function () {
                var t = this
                    , a = !1;
                return t.currentUser.MemberName && (a = !0),
                    a
            },
            sso: function (a) {
                var i = this
                    , n = s.defer()
                    , l = function (a) {
                        if (0 == a.ErrorCode) {
                            var s = a.Data.User
                                , d = a.Data.Token;
                            i.updateUser(s, {
                                set: !0
                            }),
                                t.defaults.headers.common["X-Access-Token"] = d,
                                e.setItem(c.SESSION_TOKEN, d),
                                e.setItem(c.BASE_INFO, JSON.stringify(a.Data.BaseInfo))
                        }
                        n.resolve(a)
                    }
                    , o = function (t) {
                        n.reject(t)
                    }
                    ;
                return d.getResource("/basic/SSO", a).then(l, o),
                    n.promise
            },
            login: function () {
            },
            logout: function () {
                self.resetCache()
            },
            resetCache: function () {
                var a = this;
                e.removeItem(c.OA_APP_USER),
                    e.removeItem(c.SESSION_TOKEN),
                    e.removeItem(c.BASE_INFO),
                    t.defaults.headers.common["X-Access-Token"] = "",
                    a.updateUser(o, {
                        remove: !0
                    })
            }
        };
        return v
    }
    ]),
    angular.module("services.common.store", ["components.localStorage"]).service("StoreService", ["$q", "$cordovaLocalStorage", function (t, a) {
        function i(i, s) {
            var e = this;
            this.deferred = t.defer(),
                this.cacheKey = i;
            var d = a
                , n = function (t) {
                    0 === t.ErrorCode && e.setItem(t.Data),
                        e.deferred.resolve(t)
                }
                , l = function (t) {
                    e.deferred.reject(t)
                }
                ;
            return this.setItem = function (t) {
                d.setItem(this.cacheKey, JSON.stringify(t))
            }
                ,
                this.getItem = function () {
                    var t = d.getItem(this.cacheKey);
                    return t && (t = JSON.parse(t)),
                        t
                }
                ,
            angular.isObject(s) && s.then(n, l),
            angular.isFunction(s) && s().then(n, l),
                this.promise = this.deferred.promise,
                this
        }

        return i
    }
    ]).service("StoreByUriService", ["$q", "StoreService", "$cordovaLocalStorage", "utils", function (t, a, i, s) {
        function e(a, e, d) {
            var n = this;
            this.deferred = t.defer(),
                this.cacheKey = a;
            var l = i
                , o = function (t) {
                    0 === t.ErrorCode && n.setItem(t.Data, d),
                        n.deferred.resolve(t)
                }
                , c = function (t) {
                    n.deferred.reject(t)
                }
                ;
            if (this.setItem = function (t, a) {
                    if (a) {
                        var i = new Date;
                        l.setItem(this.cacheKey + "_TIME", i.getTime() + 1e3 * a)
                    }
                    l.setItem(this.cacheKey, JSON.stringify(t))
                }
                    ,
                    this.getItem = function () {
                        var t = null
                            , a = n.getTime();
                        if (a) {
                            var i = new Date;
                            i.getTime() <= a && (t = l.getItem(this.cacheKey))
                        } else
                            t = l.getItem(this.cacheKey);
                        return t && (t = JSON.parse(t)),
                            t
                    }
                    ,
                    this.clear = function () {
                        l.removeItem(this.cacheKey),
                            l.removeItem(this.cacheKey + "_TIME")
                    }
                    ,
                    this.getTime = function () {
                        var t = l.getItem(this.cacheKey + "_TIME");
                        return t
                    }
                    ,
                    angular.isObject(e))
                if (d) {
                    var v = n.getItem();
                    v ? n.deferred.resolve({
                        ErrorCode: 0,
                        Data: v
                    }) : s.getResource(e.getUri(), e.Params(), e.Options()).then(o, c)
                } else
                    s.getResource(e.getUri(), e.Params(), e.Options()).then(o, c);
            else if (e)
                if (d) {
                    var v = n.getItem();
                    v ? n.deferred.resolve({
                        ErrorCode: 0,
                        Data: v
                    }) : s.getResource(e).then(o, c)
                } else
                    s.getResource(e).then(o, c);
            return this.promise = this.deferred.promise,
                this
        }

        return e
    }
    ]),
    angular.module("services.common.utils", []).factory("utils", ["$q", "$http", "$rootScope", "Constants", "QuickDialog", "$filter", "$state", function (t, a, i, s, e, d, n) {
        function l(l, o, c) {
            var v = t.defer()
                , b = function (t, a) {
                var s = {
                    error: t,
                    status: a
                };
                if (e.hideLoading(),
                    0 != c.isTip) {
                    if (401 === a)
                        return i.$emit("event:auth-loginRequired");
                    if (404 == a)
                        e.tips(d("translate")("Common.LabelTimeOut"));
                    else if (500 === a)
                        e.tips(d("translate")("Common.LabelTimeOut"));
                    else if (0 == a)
                        e.tips(d("translate")("Common.LabelTimeOut"));
                    else if (901 == a)
                        switch (t.ErrorCode) {
                            case 102:
                                return i.$emit("event:sys-maintenance")
                        }
                    else
                        e.tips(d("translate")("Common.LabelTimeOut"))
                }
                v.reject(s)
            }
                , m = {
                timeout: 1e5
            };
            c = angular.extend({}, m, c);
            i.online;
            return a.post(s.API_PATH + l + "?r=" + Math.random(), o, c).success(function (t) {
                angular.isArray(t.Data) && !t.Data.length ;
                angular.isObject(t) && !t.Data;
                if (angular.isObject(t)){
                    e.hideLoading();
                    switch (t.ErrorCode) {
                        case 109:
                            n.go("index");
                        case 116: //维护提示
                           return e.tips('<div class="tipDialog">' + d("translate")("Common.LabelWHLottery") + '</div>');
                     
                    };
                }
                v.resolve(t)


                   /* angular.isArray(t.Data) && !t.Data.length || angular.isObject(t) && !t.Data,
                    109 == t.ErrorCode && n.go("index"),
                        v.resolve(t)*/
                }
            ).error(b),
                v.promise
        }

        function o(t, a, i) {
            return this.getUri = function () {
                return t
            }
                ,
                this.Params = function () {
                    return a
                }
                ,
                this.Options = function () {
                    return i
                }
                ,
                this
        }

        function c(t) {
            if (!t)
                return "";
            try {
                var a = new Date(t);
                switch (a.getDay()) {
                    case 0:
                        return d("translate")("Common.LabelSunday");
                    case 1:
                        return d("translate")("Common.LabelMonday");
                    case 2:
                        return d("translate")("Common.LabelTuesday");
                    case 3:
                        return d("translate")("Common.LabelWednesday");
                    case 4:
                        return d("translate")("Common.LabelThursday");
                    case 5:
                        return d("translate")("Common.LabelFriday");
                    case 6:
                        return d("translate")("Common.LabelSaturday")
                }
            } catch (i) {
                return ""
            }
        }

        function v(t, a) {
            var i = Math.floor(parseInt(t / 3600) / 24)
                , s = parseInt(t / 3600) % 24
                , e = parseInt(t / 60) % 60
                , d = t % 60;
            if (1 == a)
                return i && (s += 24 * i),
                s && (e += 60 * s),
                (10 > e ? "0" + e : e) + ":" + (10 > d ? "0" + d : d);
            if (2 == a) {
                i && (s += 24 * i);
                var n = "";
                return 0 != s && (n = (10 > s ? "0" + s : s) + ":"),
                n + (10 > e ? "0" + e : e) + ":" + (10 > d ? "0" + d : d)
            }
            return {
                days: i,
                hours: 10 > s ? "0" + s : s,
                minutes: 10 > e ? "0" + e : e,
                seconds: 10 > d ? "0" + d : d
            }
        }

        function b() {
            var t = new Date;
            t.setDate(t.getDate() - 6);
            for (var a, i = [], s = 1, e = 0; 7 > e; e++)
                a = t.getFullYear() + "-" + (t.getMonth() + 1) + "-" + t.getDate(),
                    i.push(a),
                    t.setDate(t.getDate() + s);
            return i.reverse()
        }

        function m(t, a) {
            return 1 == t ? null == a ? {
                show: !0,
                index: 0
            } : 0 == a.status ? {
                show: !0,
                index: 1
            } : {
                show: !0,
                index: 0
            } : 2 == t ? 0 == a.length ? {
                show: !0,
                index: 2
            } : {
                show: !1,
                index: 0
            } : {
                show: !0,
                index: 0
            }
        }

        function r(t, a) {
            var i = [];
            return function s(t, a, e) {
                if (0 == e)
                    return i.push(t);
                for (var d = 0, n = a.length; n - e >= d; d++)
                    s(t.concat(a[d]), a.slice(d + 1), e - 1)
            }
            ([], t, a),
                i
        }

        return {
            getResourceParams: o,
            getResource: l,
            getWeek: c,
            secondsFormat: v,
            getDaysBeforeDate: b,
            getCombinationNum: r,
            getTip: m
        }
    }
    ]),
    angular.module("services.common.constants", []).factory("Constants", [function () {
        var t = {
            CDNURL: document.getElementById("CDNURL").value,
            API_PATH: document.getElementById("APIURL").value,//"http://wapindexci.com/api",
            CACHETIMEOUT: 60,
            CSSPATH: {
                _default: "default/main.css"
            }
        };
        return t
    }
    ]),
    angular.module("services.common.lottery.Delegate", ["ionic"]).service("curLotteryDelegate", ionic.DelegateService(["clear", "submit", "init", "destroy"])),
    angular.module("services.common.language", ["pascalprecht.translate"]).config(["$translateProvider", function (t) {
        t.translations("en", LangEN),
            t.translations("zh_cn", LangCN),
            t.translations("zh_tw", LangTW)
    }
    ]).factory("LangService", ["$q", "$translate", "$cordovaLocalStorage", function (t, a, i) {
        var s = {
            APP_LANGUAGE: "APP_LANGUAGE"
        }
            , e = {
            success: !1,
            set: function (e) {
                var d = this
                    , n = t.defer();
                return e && "" != e && a.use(e).then(function (t) {
                        i.setItem(s.APP_LANGUAGE, e),
                            d.success = !0,
                            n.resolve(t)
                    }
                    , function (t) {
                        n.reject(t)
                    }
                ).catch(function () {
                        a.use("zh_cn")
                    }
                ),
                    n.promise
            },
            get: function () {
                return i.getItem(s.APP_LANGUAGE) || "zh_cn"
            },
            setDefault: function () {
                var t = this.get();
                return this.set(t)
            }
        };
        return e
    }
    ]),
    angular.module("app.common.theme", []).factory("ThemeConfig", ["$cordovaLocalStorage", "Constants", function (t, a) {
        var i = {
            getPath: function (t) {
                switch (t) {
                    case "default":
                        return a.CSSPATH._default;
                    default:
                        return a.CSSPATH._default
                }
            },
            setTheme: function (t) {
                var i = this.getPath(t);
                if ("" != i)
                    if (document.querySelector("#appstyle")) {
                        var s = angular.element(document.querySelector("#appstyle"));
                        s.attr("href", a.CDNURL + "styles/" + i)
                    } else {
                        var s = document.createElement("link");
                        s.id = "appstyle",
                            s.rel = "stylesheet",
                            s.href = a.CDNURL + "styles/" + i,
                            s.charset = "utf-8",
                            document.head.appendChild(s)
                    }
            }
        };
        return i
    }
    ]),
    angular.module("services.lottery.bet", []).service("LotteryBetService", ["utils", function (t) {
        var a = {
            bet: function (a) {
                return t.getResource("/lottery_info/MBet", a)
            }
        };
        return a
    }
    ]), /*服务*/
    angular.module("services.lottery.info", []).service("LotteryInfoService", ["utils", "$cordovaLocalStorage", "StoreByUriService", "$q", function (t, a, i, s) {
        var e = {
            APP_LOTTERY_INFO_LOTTERY_LIST: "APP_LOTTERY_INFO_LOTTERY_LIST",
            APP_IN_GAME: "APP_IN_GAME"
        }
            , d = {
            lotteryList: function () {
                var t = a.getItem(e.APP_LOTTERY_INFO_LOTTERY_LIST);
                return "string" == typeof t && (t = JSON.parse(t)),
                    t
            },
            getLotteryList: function () {
                {
                    var a = s.defer()
                        , d = function (t) {
                        a.resolve(t)
                    }
                        , n = function (t) {
                        a.reject(t)
                    }
                        , l = t.getResourceParams("/lottery_info/GetLotteryList");
                    new i(e.APP_LOTTERY_INFO_LOTTERY_LIST, l).promise.then(d, n)
                }
                return a.promise
            },
            getBaseInfo: function (a) {
                return t.getResource("/lottery_info/GetBaseInfo", a, {
                    isTip: 0
                })
            },
            getHFLines: function (a) {
                return t.getResource("/lottery_info/GetHFLines", a, {
                    isTip: 0
                })
            },
            getSFLHCLines: function (a) {
                return t.getResource("/lottery_info/GetSFLHCLines", a, {
                    isTip: 0
                })
            },
            getHKCLines: function (a) {
                return t.getResource("/lottery_info/getHKCLines", a, {
                    isTip: 0
                })
            },
            getLotteyHistory: function (a) {
                return t.getResource("/lottery_info/GetHistory", a)
            },
            getBetLimit: function (a) {
                return t.getResource("/lottery_info/GetLimitByLotteryId", a)
            },
            setInGame: function (t) {
                //设置游戏
                a.setItem(e.APP_IN_GAME, t)
            },
            getInGame: function () {
                var t = a.getItem(e.APP_IN_GAME);
                return t
            }
        };
        return d
    }
    ]),
    angular.module("services.lottery.account", []).service("AccountService", ["utils", function (t) {
        var a = {
            notCount: function (a) {
                return t.getResource("/lottery_info/NotCount", {
                    LotteryId: a
                })
            },
            notCountBetMoney: function () {
                return t.getResource("/lottery_info/NotCountBetMoney")
            },
            getCount: function () {
                return t.getResource("/lottery_info/GetCount")
            },
            getCountByDate: function (a) {
                return t.getResource("/lottery_info/GetCountByDate", a)
            },
            getCountByDateAndLotteryId: function (a) {
                return t.getResource("/lottery_info/GetCountByDateAndLotteryId", a)
            },
            getChangLong: function (a) {
                return t.getResource("/lottery_info/GetChangLong", {
                    LotteryId: a
                })
            },
            getLuZhu: function (a, i) {
                return t.getResource("/lottery_info/GetLuZhu", {
                    LotteryId: a,
                    NumberPosition: i
                })
            },
            getMemberBalance: function (a) {
                return t.getResource("/basic/GetMemberBalance", null, a)
            },
            geMaintBalance: function () {
                return t.getResource("/basic/GeMaintBalance")
            },
            getTodayWinLossWithMember: function (a) {
                return t.getResource("/basic/GetTodayWinLossWithMember", null, a)
            }
        };
        return a
    }
    ]),
    angular.module("services.lottery.cash", []).factory("CashService", ["utils", function (t) {
        var a = {
            gameFundTransfer: function (a) {
                return t.getResource("/basic/GameFundTransfer", a)
            }
        };
        return a
    }
    ]),
    angular.module("services.lottery.notice", []).factory("NoticeService", ["utils", function (t) {
        var a = {
            getNotice: function () {
                return t.getResource("/basic/Notices")
            }
        };
        return a
    }
    ]),/*彩票自定义标签*/
    angular.module("directives.lottery", [])
    .directive("curFuCai3d", ["$controller", "$stateParams", function (t, a) {
       //福彩3d
        return {
            restrict: "E",
            scope: !0,
            templateUrl: "/lottery/index?LotteryId=fc_3d",
            compile: function (a, i) {
                function s(a) {
                    var s = {
                        LotteryId: 2,
                        delegateHandle: i.delegateHandle
                    };
                    t("gaoPinCtrl", {
                        $scope: a,
                        viewOptions: s
                    })
                }

                return {
                    pre: s
                }
            }
        }
    }
    ])
    .directive("curPaiLieSan", ["$controller", "$stateParams", function (t, a) {
       //排列3
        return {
            restrict: "E",
            scope: !0,
            templateUrl: "/lottery/index?LotteryId=pl_3",
            compile: function (a, i) {
                function s(a) {
                    var s = {
                        LotteryId: 3,
                        delegateHandle: i.delegateHandle
                    };
                    t("gaoPinCtrl", {
                        $scope: a,
                        viewOptions: s
                    })
                }

                return {
                    pre: s
                }
            }
        }
    }
    ]).directive("curLiuHeCai", ["curLotteryDelegate", "$stateParams", function (t, a) {
        return {
            restrict: "E",
            scope: !0,
            controller: "HongKongCaiCtrl",
            templateUrl: "/lottery/index?LotteryId=liuhecai",
            compile: function () {
                function a(a, i, s, e) {
                    t._registerInstance(e, s.delegateHandle, e.hasActiveScope)
                }

                return {
                    pre: a
                }
            }
        }
    }
    ])
    .directive("curBeiJingKuailLeBa", ["curLotteryDelegate", "$stateParams", function (t, a) {
       //北京快乐8
        return {
            restrict: "E",
            scope: !0,
            templateUrl: "/lottery/index?LotteryId=bj_8",
            controller: "BeiJingKuailLeBaCtrl",
            compile: function () {
                function a(a, i, s, e) {
                    t._registerInstance(e, s.delegateHandle, e.hasActiveScope)
                }

                return {
                    pre: a
                }
            }
        }
    }
    ])
    .directive("curBeiJingSaiChe", ["$controller", "$stateParams", function (t, a) {
       //北京赛车 pk10
        return {
            restrict: "E",
            scope: !0,
            templateUrl: "/lottery/index?LotteryId=bj_10",
            compile: function (a, i) {
                function s(a) {
                    var s = {
                        LotteryId: 6,
                        delegateHandle: i.delegateHandle
                    };
                    t("gaoPinCtrl", {
                        $scope: a,
                        viewOptions: s
                    })
                }

                return {
                    pre: s
                }
            }
        }
    }
    ])
    .directive("curChongQingShiShiCai", ["$controller", "$stateParams", function (t, a) {
       //重庆时时彩
        return {
            restrict: "E",
            scope: !0,
            templateUrl: "/lottery/index?LotteryId=cq_ssc",
            compile: function (a, i) {
                function s(a) {
                    var s = {
                        LotteryId: 7,
                        delegateHandle: i.delegateHandle
                    };
                    t("gaoPinCtrl", {
                        $scope: a,
                        viewOptions: s
                    })
                }

                return {
                    pre: s
                }
            }
        }
    }
    ])
    .directive("curTianJingShiShiCai", ["$controller", "$stateParams", function (t, a) {
       //天津时时彩
        return {
            restrict: "E",
            scope: !0,
            templateUrl: "/lottery/index?LotteryId=tj_ssc",
            compile: function (a, i) {
                function s(a) {
                    var s = {
                        LotteryId: 8,
                        delegateHandle: i.delegateHandle
                    };
                    t("gaoPinCtrl", {
                        $scope: a,
                        viewOptions: s
                    })
                }

                return {
                    pre: s
                }
            }
        }
    }
    ])
    .directive("curXinJiangShiShiCai", ["$controller", "$stateParams", function (t, a) {
       //新疆时时彩
        return {
            restrict: "E",
            scope: !0,
            templateUrl: "/lottery/index?LotteryId=xj_ssc",
            compile: function (a, i) {
                function s(a) {
                    var s = {
                        LotteryId: 9,
                        delegateHandle: i.delegateHandle
                    };
                    t("gaoPinCtrl", {
                        $scope: a,
                        viewOptions: s
                    })
                }

                return {
                    pre: s
                }
            }
        }
    }
    ])
    .directive("curJiangXiShiShiCai", ["$controller", "$stateParams", function (t, a) {
       //江西时时彩
        return {
            restrict: "E",
            scope: !0,
            templateUrl: "/lottery/index?LotteryId=jx_ssc",
            compile: function (a, i) {
                function s(a) {
                    var s = {
                        LotteryId: 10,
                        delegateHandle: i.delegateHandle
                    };
                    t("gaoPinCtrl", {
                        $scope: a,
                        viewOptions: s
                    })
                }

                return {
                    pre: s
                }
            }
        }
    }
    ])
    .directive("curCqKuaiLeShiFen", ["curLotteryDelegate", "$stateParams", function (t, a) {
        //重庆快乐十分
        return {
            restrict: "E",
            scope: !0,
            controller: "CqKuaiLeShiFenCtrl",
            templateUrl: "/lottery/index?LotteryId=cq_ten",
            compile: function () {
                function a(a, i, s, e) {
                    t._registerInstance(e, s.delegateHandle, e.hasActiveScope)
                }
                return {
                    pre: a
                }
            }
        }
    }
    ])
    .directive("curGdKuaiLeShiFen", ["curLotteryDelegate", "$stateParams", function (t, a) {
        //广东快乐十分
        return {
            restrict: "E",
            scope: !0,
            controller: "GdKuaiLeShiFenCtrl",
            templateUrl: "/lottery/index?LotteryId=gd_ten",
            compile: function () {
                function a(a, i, s, e) {
                    t._registerInstance(e, s.delegateHandle, e.hasActiveScope)
                }
                return {
                    pre: a
                }
            }
        }
    }
    ])
    .directive("curJiangSuKuaiSan", ["$controller", "$stateParams", function (t, a) {
       //江苏快3
        return {
            restrict: "E",
            scope: !0,
            templateUrl: "/lottery/index?LotteryId=js_k3",
            compile: function (a, i) {
                function s(a) {
                    var s = {
                        LotteryId: 13,
                        delegateHandle: i.delegateHandle
                    };
                    t("gaoPinCtrl", {
                        $scope: a,
                        viewOptions: s
                    })
                }

                return {
                    pre: s
                }
            }
        }
    }
    ])
    .directive("curJiLinKuaiSan", ["$controller", "$stateParams", function (t, a) {
       //吉林快3
        return {
            restrict: "E",
            scope: !0,
            templateUrl: "/lottery/index?LotteryId=jl_k3",
            compile: function (a, i) {
                function s(a) {
                    var s = {
                        LotteryId: 14,
                        delegateHandle: i.delegateHandle
                    };
                    t("gaoPinCtrl", {
                        $scope: a,
                        viewOptions: s
                    })
                }

                return {
                    pre: s
                }
            }
        }
    }
    ]),
    angular.module("directives.menu", []).directive("curMenus", function () {
            return {
                restrict: "E",
                transclude: !0,
                scope: !0,
                controller: ["$scope", "$element", "$ionicScrollDelegate", function (t, a, i) {
                    var s = t.panes = [];
                    t.tabUrl = "",
                        t.select = function (a) {
                            var e = 0;
                            t.tabIndex = e,
                                angular.forEach(s, function (i) {
                                        i.selected = !1,
                                        i == a && (t.tabIndex = e),
                                            e++
                                    }
                                ),
                                a.selected = !0,
                                t.tabUrl = a.url;
                            var d = i.$getByHandle("menus").getScrollView()
                                , n = (d.__contentHeight,
                                d.__clientHeight)
                                , l = (d.__maxScrollTop,
                                d.__scrollTop)
                                , o = d.__lastTouchTop - 109
                                , c = o - n / 2;
                            i.$getByHandle("menus").scrollTo(0, l + c, 10)
                        }
                        ,
                        this.addPane = function (a) {
                            0 == s.length && t.select(a),
                                s.push(a)
                        }
                }
                ],
                template: '<div class="cur-menus"><ion-content delegate-handle="menus" ><ul class="nav"><ng-transclude></ng-transclude><li ng-repeat="pane in panes" ng-class="{active:pane.selected}" ><a href="" class="item col" ng-click="select(pane);">{{pane.title}}<span class="smallround"></span></a></li></ul></ion-content><div class="tab-content" onload="menuInit(tabIndex)" ng-include src="tabUrl"></div></div>',
                replace: !0,
                compile: function (t, a) {
                    var i = a.lotteryMenu || "lottery-menu";
                    angular.element(t.children()[0]).addClass(i)
                }
            }
        }
    ).directive("curMenu", function () {
            return {
                require: "^curMenus",
                restrict: "E",
                transclude: !0,
                scope: {
                    title: "@",
                    url: "@"
                },
                link: function (t, a, i, s) {
                    s.addPane(t)
                },
                replace: !0
            }
        }
    ).directive("subNavs", function () {
            return {
                restrict: "E",
                transclude: !0,
                scope: !0,
                controller: ["$scope", "$ionicScrollDelegate", function (t, a) {
                    var i = t.subnavs = [];
                    t.subNavUrl = "",
                        t.choose = function (s) {
                            var e = 0;
                            t.navIndex = e,
                                angular.forEach(i, function (a) {
                                        a.selected = !1,
                                        a == s && (t.navIndex = e),
                                            e++
                                    }
                                ),
                                s.selected = !0,
                                t.subNavUrl = s.url;
                            var d = a.$getByHandle("handle-navs").getScrollView()
                                , n = (d.__contentWidth,
                                d.__clientWidth)
                                , l = (d.__maxScrollLeft,
                                d.__scrollLeft)
                                , o = d.__lastTouchLeft
                                , c = o - n / 2;
                            a.$getByHandle("handle-navs").scrollTo(l + c, 0, 10)
                        }
                        ,
                        this.addSubNav = function (a) {
                            0 == i.length && t.choose(a),
                                i.push(a)
                        }
                }
                ],
                template: '<div class="sub-Navs" style="width: 100%;"><ion-scroll direction="x" delegate-handle="handle-navs" scrollbar-x="false"  ><div class="row"><ng-transclude></ng-transclude><div class="col  sub-col {{itemClass}}" ng-repeat="subnav in subnavs" ng-class="{navActive:subnav.selected}"  ><a href="" class="item"  ng-click="choose(subnav)">{{subnav.title}}</a></div></div></ion-scroll><div class="nav-content" onload="subNavInit(navIndex)" ng-include src="subNavUrl"></div></div>',
                replace: !0,
                compile: function () {
                    function t(t, a, i) {
                        t.itemClass = i.itemClass || "col-50",
                            t.height = i.height || "20"
                    }

                    return {
                        pre: t
                    }
                }
            }
        }
    ).directive("subNav", function () {
            return {
                require: "^subNavs",
                restrict: "E",
                transclude: !0,
                scope: {
                    title: "@",
                    url: "@",
                    panid: "@"
                },
                link: function (t, a, i, s) {
                    s.addSubNav(t)
                },
                replace: !0
            }
        }
    ),
    angular.module("directives.repeatn", []).directive("curRepeatN", ["$parse", function (t) {
        return {
            restrict: "A",
            transclude: "element",
            replace: !0,
            link: function (a, i, s, e, d) {
                a.last = i,
                    a.parentElem = i.parent(),
                    a.elems = [i];
                var n = t(s.curRepeatN);
                a.$watch(function () {
                        return parseInt(s.curRepeatN) || n(a)
                    }
                    , function (t, i) {
                        var s, e, n, l = parseInt(t), o = parseInt(i), c = !isNaN(l) && !isNaN(o);
                        if (isNaN(l) || c && o > l)
                            for (n = c ? l : 0,
                                     a.last = a.elems[n],
                                     e = a.elems.length - 1; e > n; e -= 1)
                                a.elems[e].remove(),
                                    a.elems.pop();
                        else
                            for (e = a.elems.length - 1; l > e; e += 1)
                                s = a.$new(),
                                    s.$index = e,
                                    d(s, function (t) {
                                            a.last.after(t),
                                                a.last = t,
                                                a.elems.push(t)
                                        }
                                    )
                    }
                )
            }
        }
    }
    ]),
    angular.module("directives.resetfield", []).directive("resetField", ["$compile", "$timeout", function (t, a) {
        return {
            require: "ngModel",
            scope: {
                resetPage: "&resetField"
            },
            link: function (i, s, e, d) {
                var n = /text|search|tel|url|email|password/i;
                if ("INPUT" === s[0].nodeName) {
                    if (!n.test(e.type))
                        throw new Error("Invalid input type for resetField: " + e.type)
                } else if ("TEXTAREA" !== s[0].nodeName)
                    throw new Error("resetField is limited to input and textarea elements");
                var l = t('<i ng-show="enabled" ng-click="reset();" class="box txdel icon ion-android-close reset-field-icon"></i>')(i);
                s.addClass("reset-field"),
                    s.after(l),
                    i.reset = function () {
                        d.$setViewValue(null),
                            d.$render(),
                            a(function () {
                                    s[0].focus()
                                }
                                , 0, !1),
                            i.enabled = !1,
                            i.resetPage()
                    }
                    ,
                    s.bind("input", function () {
                        }
                    ).bind("focus", function () {
                            a(function () {
                                    i.enabled = !d.$isEmpty(s.val()),
                                        i.$apply()
                                }
                                , 0, !1)
                        }
                    ).bind("keyup", function () {
                            a(function () {
                                    i.enabled = !d.$isEmpty(s.val()),
                                        i.$apply()
                                }
                                , 0, !1)
                        }
                    ).bind("blur", function () {
                            a(function () {
                                    i.enabled = !1,
                                        i.$apply()
                                }
                                , 0, !1)
                        }
                    )
            }
        }
    }
    ]),
    angular.module("directives.tips", []).directive("curTips", function () {
            return {
                restrict: "AE",
                scope: {
                    tip: "=tip"
                },
                controller: ["$scope", "$element", "$filter", function (t, a, i) {
                    t.tips = {
                        index: 0,
                        text: [i("translate")("Common.LabelGetDataError"), i("translate")("Common.LabelTimeOut"), i("translate")("Common.LabelTempNoData")]
                    },
                        t.$watch("tip", function (a) {
                                a.index && (t.tips.index = a.index)
                            }
                        )
                }
                ],
                template: '<div class="text-center padding ng-hide cur-tips" ng-show="tip.show" >{{ tips.text[tips.index] }}</div>'
            }
        }
    ),/*高频彩数据格式化*/
    angular.module("controllers.gaoPin", []).controller("gaoPinCtrl", ["$scope", "viewOptions", "curLotteryDelegate", "$ionicHistory", "LotteryInfoService", "$filter", "utils", "$interval", "$timeout", "QuickDialog", "LotteryBetService", "$ionicPopup", "$state", "AccountService", function (t, a, i, s, e, d, n, l, o, c, v, b, m, r) {
        function g() {
            t.baseInfo.CloseCount > 0 ? t.baseInfo.CloseTime = n.secondsFormat(t.baseInfo.CloseCount, 1) : (t.baseInfo.CloseTime = d("translate")("Common.TipsClosed"),
                angular.element(document.querySelector("#betbt")).removeAttr("disabled")),
                t.baseInfo.OpenCount > 0 ? (t.baseInfo.OpenTime = n.secondsFormat(t.baseInfo.OpenCount, 1),
                    t.baseInfo.OpenCount--) : (t.baseInfo.OpenTime = d("translate")("Common.LabelOpenResulting"),
                    angular.element(document.querySelector("#betbt")).removeAttr("disabled"));
            var a = angular.element(document.querySelector(".bet-view"))
                , i = angular.element(document.querySelector(".bar-footer .close-pan"));
            if (t.baseInfo.CloseCount <= 0) {
                if (0 == i.length && (i.remove(),
                        t.baseInfo.PreResult)) {
                    a.addClass("opacityBetView");
                    var s = angular.element(document.querySelector(".bar-footer"))
                        , e = angular.element('<div class="close-pan"><div class="tip">' + d("translate")("Common.TipsClosed") + "</div></div>");
                    s.append(e)
                }
                angular.element(document.querySelector("#j-money")).attr({
                    disabled: "disabled"
                }),
                    C.infoUpdate = !1,
                    T(),
                    S(),
                y && (y.close(),
                    y = null ),
                    t.current.count = 0,
                    t.betList.MBetParameters = [],
                    L = null
            } else
                i.remove(),
                    a.removeClass("opacityBetView"),
                    angular.element(document.querySelector("#j-money")).removeAttr("disabled"),
                    x(),
                    t.baseInfo.CloseCount--
        }

        var p = this
            , u = !1
            , h = {
            LotteryId: a.LotteryId
        }
            , C = {
            lineUpdate: !1
        };
        //基本类型定义
        t.baseInfo = {
            CurrentPeriod: null,
            PrePeriodNumber: null,
            CloseCount: 0,
            CloseTime: "00:00",
            OpenCount: 0,
            OpenTime: "00:00",
            PreResult: null
        },
            t.betList = {
                LotteryId: a.LotteryId,
                MBetParameters: []
            };
        var y = null
            , L = null
            , _ = null
            , w = (i._registerInstance(p, a.delegateHandle, function () {
                    return s.isActiveScope(t)
                }
                ),
                    function () {
                        var a = function (t) {
                                401 == t.status || 901 == t.status || u || o(function () {
                                        w()
                                    }
                                    , 2e4)
                            }
                            , i = function (a) {
                                0 === a.ErrorCode && (L = a.Data.Lines,
                                t.baseInfo && (t.baseInfo.CurrentPeriod = a.Data.CurrentPeriod,
                                    t.baseInfo.PrePeriodNumber = a.Data.PrePeriodNumber,
                                    t.baseInfo.CloseCount = a.Data.CloseCount,
                                    t.baseInfo.PreResult = a.Data.PreResult,
                                    t.baseInfo.OpenCount = a.Data.OpenCount),
                                u || o(function () {
                                        w()
                                    }
                                    , 1e4))
                            }
                            ;
                        C.lineUpdate = !1,
                            e.getHFLines(h).then(i, a)
                    }
            )
            , H = function (a) {
                for (var i = 0; i < t.betList.MBetParameters.length; i++)
                    if (t.betList.MBetParameters[i].Id == a)
                        return i;
                return -1
            }
            , f = function (a) {
                var i = H(a);
                -1 != i && (t.betList.MBetParameters.splice(i, 1),
                    t.current.count--,
                    D())
            }
            , Z = function (a) {
                var i = H(a);
                if (-1 == i) {
                    var s = angular.element(document.querySelectorAll("[data-id='" + a + "']"))
                        , e = s.parent().parent().attr("data-title")
                        , d = angular.element(document.querySelectorAll("[data-id='" + a + "'] .bet-content")).text()
                        , n = {
                        Id: a,
                        Lines: L[a],
                        Money: 0,
                        BetContext: d,
                        BetType: 1,
                        Pel: e,
                        Change: !1
                    };
                    t.betList.MBetParameters.push(n),
                        t.current.count++,
                        D()
                }
            }
            , N = function (a, i) {
                for (var s = 0; s < t.betList.MBetParameters.length; s++)
                    t.betList.MBetParameters[s].Money = parseInt(a),
                    1 != i && t.betList.MBetParameters[s].Lines != L[t.betList.MBetParameters[s].Id] && (t.betList.MBetParameters[s].Lines = L[t.betList.MBetParameters[s].Id],
                        t.betList.MBetParameters[s].Change = !0)
            }
            , D = function () {
                var t = angular.element(document.querySelectorAll(".cur-menus .nav .active .smallround"))
                    , a = document.querySelectorAll(".lottery-bet .bet-choose");
                a.length > 0 ? t.addClass("menus-choose") : t.removeClass("menus-choose")
            }
            , S = function () {
                var t = angular.element(document.querySelectorAll(".cur-menus .nav  .smallround"));
                angular.forEach(t, function (t) {
                        var a = angular.element(t);
                        a.removeClass("menus-choose")
                    }
                )
            }
            , B = function (a) {
                for (var i = !1, s = document.querySelectorAll(".tab-content")[0], e = 0; e < t.betList.MBetParameters.length; e++) {
                    var d = angular.element(s.querySelectorAll("[data-id='" + t.betList.MBetParameters[e].Id + "']"));
                    d.length > 0 && (d.addClass("bet-choose"),
                        i = !0)
                }
                var n = angular.element(document.querySelectorAll(".cur-menus .nav .smallround"))
                    , l = 0;
                angular.forEach(n, function (t) {
                        if (a == l) {
                            var s = angular.element(t);
                            i ? s.addClass("menus-choose") : s.removeClass("menus-choose")
                        }
                        l++
                    }
                )
            }
            , K = function () {
                var a = angular.element(document.querySelectorAll(".lottery-bet .bet"));
                angular.forEach(a, function (a) {
                        var i = angular.element(a)
                            , s = angular.element(a).attr("data-id");
                        i.bind("click", function () {
                                t.baseInfo.CloseCount > 0 && null != L && (i.hasClass("bet-choose") ? (i.removeClass("bet-choose"),
                                    f(s)) : (i.addClass("bet-choose"),
                                    Z(s)))
                            }
                        )
                    }
                )
            }
            , T = function () {
                var t = angular.element(document.querySelectorAll(".lottery-bet .tab-content .scroll-content"));
                t.addClass("close");
                var a = angular.element(document.querySelector(".bet-view"));
                a.addClass("opacityBetView");
                var i = angular.element(document.querySelectorAll(".lottery-bet .bet"));
                angular.forEach(i, function (t) {
                        angular.element(t).removeClass("bet-choose");
                        var a = angular.element(t).attr("data-id");
                        angular.element(document.querySelectorAll("[data-id='" + a + "'] .bet-item")).text("-"),
                            angular.element(document.querySelectorAll("[data-id='" + a + "']")).removeClass("ok")
                    }
                )
            }
            ;
        t.menuInit = function (t) {
            K(),
                C.lineUpdate = !1,
                T(),
                B(t)
        }
        ;
        var x = function () {
                if (null != L && !C.lineUpdate) {
                    var t = angular.element(document.querySelectorAll(".lottery-bet .tab-content .scroll-content"));
                    t.removeClass("close");
                    var a = angular.element(document.querySelectorAll(".lottery-bet .bet"));
                    angular.forEach(a, function (t) {
                            var a = angular.element(t).attr("data-id")
                                , i = angular.element(document.querySelectorAll("[data-id='" + a + "'] .bet-item"));
                            i.text(L[a]),
                                angular.element(document.querySelectorAll("[data-id='" + a + "']")).addClass("ok")
                        }
                    ),
                        C.lineUpdate = !0
                }
            }
            , X = function (a, i) {
                var s = {
                    cssClass: "lotter-popup",
                    template: '<div><ion-scroll direction="y" ><div ng-repeat="item in betList.MBetParameters"><span>{{item.Pel}}【{{item.BetContext}}】</span><span>@<span ng-class="{red:item.Change}">{{item.Lines}}</span>X{{item.Money.toFixed(2)}}</span></div></ion-scroll></div>',
                    title: d("translate")("Common.LabelBetListing"),
                    scope: t,
                    buttons: []
                };
                1 == i ? (N(a, 1),
                    s.buttons.push({
                        text: d("translate")("Common.ButtonCancel"),
                        onTap: function () {
                            angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                        }
                    }),
                    s.buttons.push({
                        text: "<b>" + d("translate")("LotteryManagent.ButtonSave") + "</b>",
                        type: "button-positive",
                        onTap: function () {
                            var i = function (i) {
                                    c.hideLoading(),
                                        0 === i.ErrorCode ? (t.current.money = "",
                                            p.clear(),
                                            c.tips('<div class="tipDialog">' + d("translate")("Common.LabelBetSuccess") + "</div>")) : 2 === i.ErrorCode ? (c.tips('<div class="tipDialog">' + i.ErrorMsg + "</div>"),
                                            t.current.money = "",
                                            p.clear()) : 3 === i.ErrorCode || 4 === i.ErrorCode || 5 === i.ErrorCode || 6 === i.ErrorCode ? c.tips('<div class="tipDialog">' + i.ErrorMsg + "<div>") : 9 === i.ErrorCode ? (L = i.Data.Lines,
                                            C.lineUpdate = !1,
                                            x(),
                                            X(a, 2)) : c.tips('<div class="tipDialog">' + d("translate")("Common.LabelBetError") + "</div>"),
                                        angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                                }
                                , s = function () {
                                    angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                                }
                                ;
                            c.loading(1e6, "<ion-spinner></ion-spinner>"),
                                v.bet(t.betList).then(i, s)
                        }
                    })) : (N(a, 2),
                    s.title = d("translate")("Common.LabelPriceChanges"),
                    s.buttons.push({
                        text: d("translate")("Common.LabelBetCancel"),
                        onTap: function () {
                            angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                        }
                    }),
                    s.buttons.push({
                        text: "<b>" + d("translate")("Common.LabelBetContinue") + "</b>",
                        type: "button-positive",
                        onTap: function () {
                            var i = function (i) {
                                    c.hideLoading(),
                                        0 === i.ErrorCode ? (t.current.money = "",
                                            p.clear(),
                                            c.tips('<div class="tipDialog">' + d("translate")("Common.LabelBetSuccess") + "</div>")) : 2 === i.ErrorCode ? (c.tips('<div class="tipDialog">' + i.ErrorMsg + "</div>"),
                                            t.current.money = "",
                                            p.clear()) : 3 === i.ErrorCode || 4 === i.ErrorCode || 5 === i.ErrorCode || 6 === i.ErrorCode ? c.tips('<div class="tipDialog">' + i.ErrorMsg + "<div>") : 9 === i.ErrorCode ? (L = i.Data.Lines,
                                            C.lineUpdate = !1,
                                            x(),
                                            X(a, 2)) : c.tips('<div class="tipDialog">' + d("translate")("Common.LabelBetError") + "</div>"),
                                        angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                                }
                                , s = function () {
                                    angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                                }
                                ;
                            c.loading(1e6, "<ion-spinner ></ion-spinner>"),
                                v.bet(t.betList).then(i, s)
                        }
                    })),
                    t.betList.MBetParameters.length > 0 ? y = b.show(s) : (c.tips('<div class="tipDialog">' + d("translate")("Common.LabelPleaseEnterBets") + "</div>"),
                        angular.element(document.querySelector("#betbt")).removeAttr("disabled"))
            }
            ;
        w(),
            _ = l(g, 1e3);//一秒钟刷新一次开盘时间
        var M = !1;
        t.getBankData = function () {
            function i(i) {
                0 == i.ErrorCode ? (t.bankData = parseFloat(i.Data.Balance).toFixed(2),
                    t.loaded = !0,
                i.Data.Balance < 1 && !M && (M = !0,
                    c.alertCancelConfirm(d("translate")("Common.TipsBalanceNotEnough"), function () {
                        }
                        , function () {
                            m.go("transfer")
                        }
                    ))) : (t.bankData = "0.00",
                    t.loaded = !0)
            }

            t.loaded = !1;
            var s = function () {
                    t.bankData = "0.00",
                        t.loaded = !0,
                        t.notCountMoney = -1
                }
                ;
            r.getMemberBalance().then(i, s)
        }
            ,
            t.getBankData(),
            p.submit = function (i) {
                t.current.count * parseInt(i) > t.current.memberBalance ? (c.alertCancelConfirm(d("translate")("Common.TipsBalanceNotEnough"), function () {
                    }
                    , function () {
                        m.go("transfer")
                    }
                ),
                    angular.element(document.querySelector("#betbt")).removeAttr("disabled")) : X(i, 1)
            }
            ,
            p.destroy = function () {
                u = !0,
                    l.cancel(_),
                    p = null ,
                    t.baseInfo = null ,
                    L = null ,
                    t.betList = null
            }
            ,
            p.clear = function () {
                t.current.count = 0,
                    S();
                var a = angular.element(document.querySelectorAll(".lottery-bet .bet"));
                t.betList.MBetParameters = [],
                    angular.forEach(a, function (t) {
                            angular.element(t).removeClass("bet-choose")
                        }
                    )
            }
    }
    ]),
    angular.module("controllers.hongKongCai", []).controller("HongKongCaiCtrl", ["$scope", "$ionicHistory", "LotteryInfoService", "$timeout", "$interval", "utils", "$filter", "QuickDialog", "LotteryBetService", "$ionicPopup", "$cordovaLocalStorage", "$state", "AuthService", "AccountService", function (t, a, i, s, e, d, n, l, o, c, v, b, m, r) {
        function g() {
            if (t.baseInfo.CloseCount > 0) {
                var a = ""
                    , i = d.secondsFormat(t.baseInfo.CloseCount);
                parseInt(i.days) > 0 ? a = i.days + n("translate")("Common.LabelDay") : "",
                    parseInt(i.hours) > 0 ? a += i.hours + n("translate")("Common.Hour") : "",
                    parseInt(i.minutes) > 0 ? a += i.minutes + n("translate")("Common.LabelMinutes") : "",
                    parseInt(i.seconds) > 0 ? a += i.seconds + n("translate")("Common.LabelSeconds") : "",
                    t.baseInfo.CloseTime = a
            } else
                t.baseInfo.CloseTime = n("translate")("Common.TipsClosed"),
                    angular.element(document.querySelector("#betbt")).removeAttr("disabled");
            var s = angular.element(document.querySelector(".bet-view"))
                , e = angular.element(document.querySelector(".bar-footer .close-pan"));
            if (t.baseInfo.CloseCount <= 0) {
                if (0 == e.length && (e.remove(),
                        t.baseInfo.PreResult)) {
                    s.addClass("opacityBetView");
                    var l = angular.element(document.querySelector(".bar-footer"))
                        , o = angular.element('<div class="close-pan"><div class="tip">' + n("translate")("Common.TipsClosed") + "</div></div>");
                    l.append(o)
                }
                angular.element(document.querySelector("#j-money")).attr({
                    disabled: "disabled"
                }),
                    L.infoUpdate = !1,
                    Q(),
                    M(),
                _ && (_.close(),
                    _ = null ),
                    t.current.count = 0,
                    t.betList.MBetParameters = [],
                    t.isForNumberbetList.BetItems = [],
                    t.lines = []
            } else
                e.remove(),
                    s.removeClass("opacityBetView"),
                    angular.element(document.querySelector("#j-money")).removeAttr("disabled"),
                    W(),
                    t.baseInfo.CloseCount--
        }

        t.sxList = [];
        var p = {
            BASE_INFO: "BASE_INFO"
        }
            , u = v.getItem(p.BASE_INFO) || [];
        if ("string" == typeof u) {
            u = JSON.parse(u);
            var h = [];
            angular.forEach(u.SxList, function (t, a) {
                    h[a] = t.join(",")
                }
            ),
                t.sxList = h
        }
        var C = this
            , y = !1;
        t.lotteryPanId = 0,
            t.isForNumber = !1,
            t.playTypeId = 0,
            t.combNum = 0,
            t.combNumEnd = 0;
        var L = {
            lineUpdate: !1
        }
            , _ = null
            , w = {
            LotteryId: 4,
            Lotterypan: t.lotteryPanId
        };
        t.baseInfo = {
            CurrentPeriod: null,
            PrePeriodNumber: null,
            CloseCount: 0,
            CloseTime: "00:00",
            OpenCount: 0,
            OpenTime: "00:00",
            PreResult: null,
            SxResult: null,
            ShowResult: null
        },
            t.lines = [];
        var H = null;
        t.betList = {
            LotteryId: 4,
            CombNum:1,
            LotteryPan: t.lotteryPanId,
            MBetParameters: []
        },
            t.isForNumberbetList = {
                LotteryId: 4,
                LotteryPan: t.lotteryPanId,
                BetItems: []
            },
            t.subNavInit = function (a) {
                Q(),
                    C.clear(),
                    1 == t.lotteryPanId || 2 == t.lotteryPanId || 3 == t.lotteryPanId || 4 == t.lotteryPanId || 7 == t.lotteryPanId || 8 == t.lotteryPanId || 9 == t.lotteryPanId ? (t.isForNumber = !1,
                        P()) : 6 == t.lotteryPanId ? (0 == a ? (t.combNum = 2,
                        t.combNumEnd = 5) : 1 == a ? (t.combNum = 2,
                        t.combNumEnd = 5) : 2 == a ? (t.combNum = 2,
                        t.combNumEnd = 5) : 3 == a ? (t.combNum = 3,
                        t.combNumEnd = 6) : 4 == a ? (t.combNum = 3,
                        t.combNumEnd = 6) : 5 == a && (t.combNum = 4,
                        t.combNumEnd = 7),
                        t.isForNumber = !0,
                        A())  : 10 == t.lotteryPanId ? (0 == a ? t.combNum = 2 : 1 == a ? t.combNum = 3 : 2 == a ? t.combNum = 4 : 3 == a ? t.combNum = 5 : 4 == a ? t.combNum = 6 : 5 == a ? t.combNum = 7 : 6 == a ? t.combNum = 8 : 7 == a ? t.combNum = 9 : 8 == a ? (t.combNum = 10) : 9 == a && (t.combNum = 11),
                        t.isForNumber = !0,A()) : 11 == t.lotteryPanId ? (0 == a ? (t.combNum = 2,
                        t.combNumEnd = 5) : 1 == a ? (t.combNum = 3,
                        t.combNumEnd = 6) : 2 == a ? (t.combNum = 4,
                        t.combNumEnd = 7) : 3 == a ? (t.combNum = 5,
                        t.combNumEnd = 8) : 4 == a ? (t.combNum = 2,
                        t.combNumEnd = 5) : 5 == a ? (t.combNum = 3,
                        t.combNumEnd = 6): 6 == a && (t.combNum = 4,
                        t.combNumEnd = 7) ,
                        t.isForNumber = !0,
                        A()) : 12 == t.lotteryPanId ? (0 == a ? (t.combNum = 2,
                        t.combNumEnd = 5) : 1 == a ? (t.combNum = 3,
                        t.combNumEnd = 6) : 2 == a ? (t.combNum = 4,
                        t.combNumEnd = 7) : 3 == a ? (t.combNum = 2,
                        t.combNumEnd = 6) : 4 == a ? (t.combNum = 3,
                        t.combNumEnd = 6) : 5 == a && (t.combNum = 4,
                        t.combNumEnd = 7)  ,
                        t.isForNumber = !0,
                        A()) : 13 == t.lotteryPanId ? (0 == a ? (t.combNum = 5,
                        t.combNumEnd = 8) : 1 == a ? (t.combNum = 6,
                        t.combNumEnd = 9) : 2 == a ? (t.combNum = 7,
                        t.combNumEnd = 10) : 3 == a ? (t.combNum = 8,
                        t.combNumEnd = 11) : 4 == a ? (t.combNum = 9,
                        t.combNumEnd = 12) : 5 == a ? (t.combNum = 10,
                        t.combNumEnd = 13) : 6 == a ? (t.combNum = 11,
                        t.combNumEnd = 14) : 7 == a && (t.combNum = 12,
                        t.combNumEnd = 15) ,
                        t.isForNumber = !0,
                        A()) : 18 == t.lotteryPanId ? (0 == a ? t.combNum = 2 : 1 == a ? t.combNum = 2 : 2 == a ? t.combNum = 3 : 3 == a ? t.combNum = 3 : 4 == a ? t.combNum = 4 : 5 == a && (t.combNum = 4),
                        t.combNumEnd = 8,
                        t.isForNumber = !0,
                        A()) : t.isForNumber = !1,
                    t.isForNumberbetList.LotteryPan = t.lotteryPanId,
                    t.betList.LotteryPan = t.lotteryPanId,
                    window.setTimeout(Z(), 1500)
            }
            ,
            t.menuInit = function (a) {
                var i = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13];
                if( a !=4){P()}
                    L.lineUpdate = !1,
                    Q(),
                    C.clear(),
                    I(a),
                    t.isForNumber = !1,
                    t.lotteryPanId = i[a],
                    w.Lotterypan = t.lotteryPanId,
                        5 == t.lotteryPanId ?  (t.combNum = 2,
                            t.combNumEnd = 8,
                            t.isForNumber = !0,
                            A()):"",
                    Z()
            }
        ;
        var f = null
            , Z = function () {
                var a = function (t) {
                        401 == t.status || 901 == t.status || y || (null != f && s.cancel(f),
                            f = s(function () {
                                    Z()
                                }
                                , 2e4))
                    }
                    , e = function (a) {
                        0 === a.ErrorCode && (t.lines[t.lotteryPanId] = a.Data.Lines,
                        t.baseInfo && (t.baseInfo.CurrentPeriod = a.Data.CurrentPeriod,
                            t.baseInfo.PrePeriodNumber = a.Data.PrePeriodNumber,
                            t.baseInfo.CloseCount = a.Data.CloseCount,
                            t.baseInfo.PreResult = a.Data.PreResult,
                            t.baseInfo.SxResult = a.Data.SxResult,
                            t.baseInfo.ShowResult = a.Data.PreResult,
                            t.baseInfo.OpenCount = a.Data.OpenCount),
                        y || (null != f && (s.cancel(f),
                            y = !1),
                            f = s(function () {
                                    Z()
                                }
                                , 1e4)))
                    }
                    ;
                L.lineUpdate = !1,
                    i.getHKCLines(w).then(e, a)
            }
            ;
        t.loop = function (a) {
            t.baseInfo.ShowResult = a != t.baseInfo.SxResult ? t.baseInfo.SxResult : t.baseInfo.PreResult
        }
        ;
        var N = function (a) {
            for (var i = 0; i < t.betList.MBetParameters.length; i++)
                if (t.betList.MBetParameters[i].Id == a)
                    return i;
            return -1
        }
            , D = function (a) {
            for (var i = 0; i < t.isForNumberbetList.BetItems.length; i++)
                if (t.isForNumberbetList.BetItems[i].numberKey == a)
                    return i;
            return -1
        }
            , S = function (a) {
            var i = N(a);
            -1 != i && (t.betList.MBetParameters.splice(i, 1),
                t.current.count--,
                X())
        }
            , B = function (a) {
            var i = D(a);
            -1 != i && t.isForNumberbetList.BetItems.splice(i, 1);
            var s = angular.element(document.querySelectorAll("[data-id='" + a + "']"))
                , e = s.parent().parent().attr("data-title")
                , e1 =s.parent().parent().attr("data-title2")
                , n = s.parent().parent().attr("data-playId"),lines = "";
            if (t.betList.MBetParameters = [],
                    t.current.count = d.getCombinationNum(t.isForNumberbetList.BetItems, t.combNum).length,
                t.current.count > 0) {
                for (var l = "", o = "", c = t.isForNumberbetList.BetItems.length, v = 0; c > v; v++){
                    if(12 == t.lotteryPanId || 11 == t.lotteryPanId){
                        l += t.isForNumberbetList.BetItems[v].BetContext  +":" +t.isForNumberbetList.BetItems[v].Lines+ ",",
                        o += t.isForNumberbetList.BetItems[v].BetContext  + ",",
                        o = o.substr(0, o.length - 1),lines =t.isForNumberbetList.BetItems[v].Lines
                    }else{
                        lines = t.isForNumberbetList.BetItems[v].Lines ,
                            l += t.isForNumberbetList.BetItems[v].BetContext  + ",",
                        o = l
                    }
                }
                l = l.substr(0, l.length - 1)
                if (6 == t.lotteryPanId) {
                    var oo = angular.element(document.querySelector(".betLine")).attr("data-id");
                    lines = t.lines[t.lotteryPanId][oo]
                }else if (5 == t.lotteryPanId) {
                    lines = 1,e1 = "";
                     for (var  c = t.isForNumberbetList.BetItems.length, v = 0; c > v; v++)
                            lines *=t.lines[t.lotteryPanId][t.isForNumberbetList.BetItems[v].numberKey],
                                e1 += t.isForNumberbetList.BetItems[v].Pel1 + ",";
                }
                var b = {
                    DisplayText: o,
                    BetContext: l,
                    IsForNumber: !0,
                    BetType: 5,
                    IsTeMa: 0,
                    Money: 0,
                    Lines: lines,
                    Id: n,
                    Pel: e,
                    Pel1: e1,
                    Change: !1
                };
                if (6 == t.lotteryPanId) {
                    var o = angular.element(document.querySelectorAll(".betLine")), id = "", c = "";
                    angular.forEach(o, function (a) {
                        var s = angular.element(a);
                        id = s.attr("data-id"),
                            c += s.attr("data-txt") + ":" + t.lines[t.lotteryPanId][id] + ","
                    })
                    b.Lines = t.lines[t.lotteryPanId][id],
                        b.Id = id,
                        b.BetType = 1,
                        b.Txt = c,//"二全中:61;二全中:61;",
                        b.IsForNumber = !1
                }
                5 == t.lotteryPanId && (t.current.count =1 ,t.betList.CombNum = 1)
                t.betList.MBetParameters.push(b),
                    X()
            } else
                M()
        }
            , K = function (a) {
            var i = N(a);
            if (-1 == i) {
                var s = angular.element(document.querySelectorAll("[data-id='" + a + "']"))
                    , e = s.parent().parent().attr("data-title")
                    , e1 =s.parent().parent().attr("data-title2")
                    , d = angular.element(document.querySelectorAll("[data-id='" + a + "'] .bet-content")).text()
                    , n = 1;
                4 != t.lotteryPanId && (n = 0);
                var l = {
                    Id: a,
                    Lines: t.lines[t.lotteryPanId][a],
                    Money: 0,
                    BetContext: d,
                    IsForNumber: !1,
                    BetType: 1,
                    IsTeMa: n,
                    Pel: e,
                    Pel1: e1,
                    Change: !1
                };
                20 == t.lotteryPanId && (e = s.parent().parent().attr(a % 2 == 0 ? "data-long" : "data-hu"),
                    l.Pel = d,
                    l.BetContext = e),
                    t.betList.MBetParameters.push(l),
                    t.current.count++,
                    X()
            }
        }
            , T = function (a) {
            var i = angular.element(document.querySelectorAll("[data-id='" + a + "']"))
                , s = i.parent().parent().attr("data-title")
                , s1 =i.parent().parent().attr("data-title2"),s2 =''
                , e = i.parent().parent().attr("data-playId")
                , n = angular.element(document.querySelectorAll("[data-id='" + a + "'] .bet-content")).text()
                , l = {
                numberKey: a,
                Lines: t.lines[t.lotteryPanId][a],
                BetContext: n,
                Pel: s,
                Pel1:s1
            };
            if (6 == t.lotteryPanId) {
                var o = angular.element(document.querySelector(".betLine")).attr("data-id");
                l.Lines = t.lines[t.lotteryPanId][o]
            }
            if (t.betList.MBetParameters = [],
                    t.isForNumberbetList.BetItems.push(l),
                    t.current.count = d.getCombinationNum(t.isForNumberbetList.BetItems, t.combNum).length,
                t.current.count > 0) {
                var c = "",lines = 1
                    , v = "",mm = ""
                    , b = t.isForNumberbetList.BetItems.length;
                if ( 6 == t.lotteryPanId || 5 == t.lotteryPanId ){
                    if (5 == t.lotteryPanId) {
                        for (var m = 0; b > m; m++)
                            c += t.isForNumberbetList.BetItems[m].numberKey + ",",
                                v += t.isForNumberbetList.BetItems[m].BetContext + ",",
                                lines *=t.lines[t.lotteryPanId][t.isForNumberbetList.BetItems[m].numberKey] ,
                                s2 += t.isForNumberbetList.BetItems[m].Pel1 + ",";
                        l.Lines = lines;
                    }else if (6 == t.lotteryPanId) {
                        for (var m = 0; b > m; m++)
                            c += t.isForNumberbetList.BetItems[m].numberKey + ",",
                                v += t.isForNumberbetList.BetItems[m].BetContext + ","
                    }
                }
                else{
                    for (var r = 0; b > r; r++)
                        mm += t.isForNumberbetList.BetItems[r].BetContext+ ":" + t.isForNumberbetList.BetItems[r].Lines + ",",
                            c += t.isForNumberbetList.BetItems[r].BetContext + ",",
                            v += t.isForNumberbetList.BetItems[r].BetContext + ",";
                }
                c = c.substr(0, c.length - 1),
                v = v.substr(0, v.length - 1);
                mm = mm.substr(0, mm.length - 1);
                5 == t.lotteryPanId ? s1 = s2 : "";
                var g = {
                    DisplayText: v,
                    BetContext: c,
                    IsForNumber: !0,
                    BetType: 5,
                    IsTeMa: 0,
                    Money: 0,
                    Lines: l.Lines,
                    Id: e,
                    Pel: s,
                    Pel1: s1,
                    Change: !1
                };
                if(12 == t.lotteryPanId ||11==t.lotteryPanId){
                	 g.BetContext =mm;
                }
                if (6 == t.lotteryPanId) {
                    var o = angular.element(document.querySelectorAll(".betLine")),id="",c="";
                    angular.forEach(o,function(a){
                        var s = angular.element(a);
                        id = s.attr("data-id"),
                        c +=  s.attr("data-txt") + ":" + t.lines[t.lotteryPanId][id] + ","
                    })
                    g.Lines = t.lines[t.lotteryPanId][id],
                        g.Id = id,
                        g.BetType = 1,
                        g.Txt = c,//"二全中:61;二全中:61;",
                        g.IsForNumber = !1
                }
                5 == t.lotteryPanId ? (t.current.count =1 ,t.betList.CombNum = 1):t.betList.CombNum = t.combNum,
                t.betList.MBetParameters.push(g),
                    X()
            } else
                M()
        }
            , x = function (a, i) {
            for (var s = 0; s < t.betList.MBetParameters.length; s++)
                if (t.betList.MBetParameters[s].Money = parseInt(a),
                    1 != i)
                    if (t.isForNumber) {
                        for (var e = t.betList.MBetParameters[s], d = e.BetContext.split(","), n = [], l = 0; l < d.length; l++) {
                            var o = d[l].split("@");
                            o[1] != t.lines[t.lotteryPanId][o[0]] && (o[1] = t.lines[t.lotteryPanId][o[0]]),
                                n.push(o.join("@"))
                        }
                        t.betList.MBetParameters[s].BetContext = n.join(","),
                            t.betList.MBetParameters[s].Change = !0
                    } else
                        t.betList.MBetParameters[s].Lines != t.lines[t.lotteryPanId][t.betList.MBetParameters[s].Id] && (t.betList.MBetParameters[s].Lines = t.lines[t.lotteryPanId][t.betList.MBetParameters[s].Id],
                            t.betList.MBetParameters[s].Change = !0)
        }
            , X = function () {
            var t = angular.element(document.querySelectorAll(".cur-menus .nav .active .smallround"))
                , a = document.querySelectorAll(".lottery-bet .bet-choose");
            a.length > 0 ? t.addClass("menus-choose") : t.removeClass("menus-choose")
        }
            , M = function () {
            var t = angular.element(document.querySelectorAll(".cur-menus .nav  .smallround"));
            angular.forEach(t, function (t) {
                    var a = angular.element(t);
                    a.removeClass("menus-choose")
                }
            )
        }
            , I = function (a) {
            for (var i = !1, s = document.querySelectorAll(".tab-content")[0], e = 0; e < t.betList.MBetParameters.length; e++) {
                var d = angular.element(s.querySelectorAll("[data-id='" + t.betList.MBetParameters[e].Id + "']"));
                d.length > 0 && (d.addClass("bet-choose"),
                    i = !0)
            }
            var n = angular.element(document.querySelectorAll(".cur-menus .nav .smallround"))
                , l = 0;
            angular.forEach(n, function (t) {
                    if (a == l) {
                        var s = angular.element(t);
                        i ? s.addClass("menus-choose") : s.removeClass("menus-choose")
                    }
                    l++
                }
            )
        }
            , P = function () {
            var a = angular.element(document.querySelectorAll(".lottery-bet .bet"));
            angular.forEach(a, function (a) {
                    var i = angular.element(a)
                        , s = angular.element(a).attr("data-id");
                    i.bind("click", function () {

                        t.baseInfo.CloseCount > 0 && null != t.lines[t.lotteryPanId] && (i.hasClass("bet-choose") ? (i.removeClass("bet-choose"),
                                   S(s)) : (i.addClass("bet-choose"),
                                   K(s)))
                        }
                    )
                }
            )
        }
            , A = function () {
            var a = angular.element(document.querySelectorAll(".lottery-bet .bet"));
            angular.forEach(a, function (a) {
                    var i = angular.element(a),ic = i.children()[0].textContent
                        ,qtxt = i.parent().parent().attr("data-title2")
                        , s = angular.element(a).attr("data-id");
                    i.bind("click", function () {
                        if (i.hasClass("bet-choose"))
                            i.removeClass("bet-choose"),
                                B(s);
                        else {
                        //大小 单双 红波 蓝波 绿波
                        if( 5 == t.lotteryPanId){
                            if( t.baseInfo.CloseCount > 0 && null != t.lines[t.lotteryPanId]) {
                                if (i.hasClass("bet-choose"))
                                    i.removeClass("bet-choose"),
                                        B(s);
                                else {
                                    var bets = angular.element(document.querySelectorAll(".lottery-bet div[data-title2='" + qtxt + "'] .bet"));
                                    angular.forEach(bets, function (a) {
                                        var ii = angular.element(a), iic = ii.children()[0].textContent
                                            , ss = angular.element(a).attr("data-id");
                                        if (ic == "大" || ic == "小") {
                                            if (ic === "大" && iic == "小" && ii.hasClass("bet-choose")) {
                                                ii.removeClass("bet-choose"), B(ss)
                                            }
                                            if (ic === "小" && iic == "大" && ii.hasClass("bet-choose")) {
                                                ii.removeClass("bet-choose"), B(ss)
                                            }
                                        } else if (ic == "单" || ic == "双") {
                                            if (ic === "单" && iic == "双" && ii.hasClass("bet-choose")) {
                                                ii.removeClass("bet-choose"), B(ss)
                                            }
                                            if (ic === "双" && iic == "单" && ii.hasClass("bet-choose")) {
                                                ii.removeClass("bet-choose"), B(ss)
                                            }
                                        } else if (ic == "红波" || ic == "绿波" || ic == "蓝波") {
                                            if (ic === "红波") {
                                                if (iic == "绿波" || iic == "蓝波")
                                                    ii.removeClass("bet-choose"), B(ss)
                                            }
                                            if (ic === "绿波") {
                                                if (iic == "红波" || iic == "蓝波")
                                                    ii.removeClass("bet-choose"), B(ss)
                                            }
                                            if (ic === "蓝波") {
                                                if (iic == "绿波" || iic == "红波")
                                                    ii.removeClass("bet-choose"), B(ss)
                                            }
                                        }
                                    })
                                }
                            }
                        }
                            if (t.baseInfo.CloseCount > 0 && null != t.lines[t.lotteryPanId])
                                    if (20 == t.lotteryPanId) {
                                        if (t.isForNumberbetList.BetItems.length >= 10)
                                            return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_10") + "</div>")
                                    }if (5 == t.lotteryPanId) {
                                        if (t.isForNumberbetList.BetItems.length >= 8)
                                            return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_8") + "</div>")
                                    }
                                    else if (11 == t.lotteryPanId) {
                                        if (2 == t.combNum) {
                                            if (t.isForNumberbetList.BetItems.length >= 5)
                                                return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_5") + "</div>")
                                        }else if (3 == t.combNum) {
                                            if (t.isForNumberbetList.BetItems.length >= 6)
                                                return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_6") + "</div>")
                                        }else if (4 == t.combNum) {
                                            if (t.isForNumberbetList.BetItems.length >= 7)
                                                return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_7") + "</div>")
                                        }else {
                                            if (t.isForNumberbetList.BetItems.length >= 8)
                                                return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_8") + "</div>")
                                        }
                                    } else if (12 == t.lotteryPanId) {
                                        if (2 == t.combNum) {
                                            if (t.isForNumberbetList.BetItems.length >= 5)
                                                return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_5") + "</div>")
                                        } else if (3 == t.combNum) {
                                            if (t.isForNumberbetList.BetItems.length >= 6)
                                                return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_6") + "</div>")
                                        } else if (4 == t.combNum) {
                                            if (t.isForNumberbetList.BetItems.length >= 7)
                                                return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_7") + "</div>")
                                        }
                                    } else if (6 == t.lotteryPanId) {
                                        if (2 == t.combNum) {
                                            if (t.isForNumberbetList.BetItems.length >= 5)
                                                return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_5") + "</div>")
                                        } else if (3 == t.combNum) {
                                            if (t.isForNumberbetList.BetItems.length >= 6)
                                                return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_6") + "</div>")
                                        }else if (4 == t.combNum) {
                                            if (t.isForNumberbetList.BetItems.length >= 7)
                                                return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_7") + "</div>")
                                        }
                                    } else if (13 == t.lotteryPanId) {//全不中
                                        if (5 == t.combNum) {
                                            if (t.isForNumberbetList.BetItems.length >= 8)
                                                return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_8") + "</div>")
                                        } else if (6 == t.combNum) {
                                            if (t.isForNumberbetList.BetItems.length >= 9)
                                                return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_9") + "</div>")
                                        } else if (7 == t.combNum) {
                                            if (t.isForNumberbetList.BetItems.length >= 10)
                                                return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_10") + "</div>")
                                        } else if (8 == t.combNum) {
                                            if (t.isForNumberbetList.BetItems.length >= 11)
                                                return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_11") + "</div>")
                                        } else if (9 == t.combNum) {
                                            if (t.isForNumberbetList.BetItems.length >= 12)
                                                return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_12") + "</div>")
                                        } else if (10 == t.combNum) {
                                            if (t.isForNumberbetList.BetItems.length >= 13)
                                                return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_13") + "</div>")
                                        } else if (11 == t.combNum) {
                                            if (t.isForNumberbetList.BetItems.length >= 14)
                                                return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_14") + "</div>")
                                        } else if (12 == t.combNum) {
                                            if (t.isForNumberbetList.BetItems.length >= 15)
                                                return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_15") + "</div>")
                                        }
                                    } else if (10 == t.lotteryPanId) {
                                        if (2 == t.combNum) {
                                            if (2 == t.isForNumberbetList.BetItems.length)
                                                return void l.tips('<div class="tipDialog">' + n("translate")("HongKongCai.LabelAllowChooseThan_2") + "</div>")
                                        }else if (3 == t.combNum) {
                                            if (3 == t.isForNumberbetList.BetItems.length)
                                                return void l.tips('<div class="tipDialog">' + n("translate")("HongKongCai.LabelAllowChooseThan_3") + "</div>")
                                        }else if (4 == t.combNum) {
                                            if (4 == t.isForNumberbetList.BetItems.length)
                                                return void l.tips('<div class="tipDialog">' + n("translate")("HongKongCai.LabelAllowChooseThan_4") + "</div>")
                                        }else if (5 == t.combNum) {
                                            if (5 == t.isForNumberbetList.BetItems.length)
                                                return void l.tips('<div class="tipDialog">' + n("translate")("HongKongCai.LabelAllowChooseThan_5") + "</div>")
                                        }else if (6 == t.combNum) {
                                            if (6 == t.isForNumberbetList.BetItems.length)
                                                return void l.tips('<div class="tipDialog">' + n("translate")("HongKongCai.LabelAllowChooseThan_6") + "</div>")
                                        }else if (7 == t.combNum) {
                                            if (7 == t.isForNumberbetList.BetItems.length)
                                                return void l.tips('<div class="tipDialog">' + n("translate")("HongKongCai.LabelAllowChooseThan_7") + "</div>")
                                        }else if (8 == t.combNum) {
                                            if (8 == t.isForNumberbetList.BetItems.length)
                                                return void l.tips('<div class="tipDialog">' + n("translate")("HongKongCai.LabelAllowChooseThan_8") + "</div>")
                                        }else if (9 == t.combNum) {
                                            if (9 == t.isForNumberbetList.BetItems.length)
                                                return void l.tips('<div class="tipDialog">' + n("translate")("HongKongCai.LabelAllowChooseThan_9") + "</div>")
                                        }else if (10 == t.combNum) {
                                            if (10 == t.isForNumberbetList.BetItems.length)
                                                return void l.tips('<div class="tipDialog">' + n("translate")("HongKongCai.LabelAllowChooseThan_10") + "</div>")
                                        }else if (11 == t.combNum) {
                                            if (11 == t.isForNumberbetList.BetItems.length)
                                                return void l.tips('<div class="tipDialog">' + n("translate")("HongKongCai.LabelAllowChooseThan_11") + "</div>")
                                        }

                                    } else if (18 == t.lotteryPanId && t.isForNumberbetList.BetItems.length >= 8)
                                        return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_8") + "</div>");
                                    i.addClass("bet-choose"),
                                        T(s)
                                }
                        }
                    )
                }
            )
        }
            , Q = function () {
            var t = angular.element(document.querySelectorAll(".lottery-bet .tab-content .scroll-content"));
            t.addClass("close");
            var a = angular.element(document.querySelector(".bet-view"));
            a.addClass("opacityBetView");
            var i = angular.element(document.querySelectorAll(".lottery-bet .bet"));
            angular.element(document.querySelector(".betLine .line")).text("-"),
                angular.forEach(i, function (t) {
                        angular.element(t).removeClass("bet-choose");
                        var a = angular.element(t).attr("data-id");
                        angular.element(document.querySelectorAll("[data-id='" + a + "'] .bet-item")).text("-"),
                            angular.element(document.querySelectorAll("[data-id='" + a + "']")).removeClass("ok")
                    }
                )
        }
            , W = function () {
            if (null != t.lines[t.lotteryPanId] && !L.lineUpdate) {
                if (6 == t.lotteryPanId) {
                    var a = angular.element(document.querySelectorAll(".lottery-bet .tab-content .scroll-content"));
                    a.removeClass("close");
                    var i = angular.element(document.querySelectorAll(".lottery-bet .betLine"));
                    angular.forEach(i, function (a) {
                            var i = angular.element(a).attr("data-id"),txt = angular.element(a).attr("data-txt")
                                , s = angular.element(document.querySelectorAll("[data-id='" + i + "'] .line"));
                            s.text(null != t.lines[t.lotteryPanId][i] ? txt+n("translate")("HongKongCai.LabelOdds") + ":" + t.lines[t.lotteryPanId][i] : txt+n("translate")("HongKongCai.LabelOdds") + "：0"),
                                angular.element(document.querySelectorAll("[data-id='" + i + "']")).addClass("ok"),
                                angular.element(document.querySelectorAll(".col.bet")).addClass("ok")
                        }
                    )
                } else {
                    var a = angular.element(document.querySelectorAll(".lottery-bet .tab-content .scroll-content"));
                    a.removeClass("close");
                    var i = angular.element(document.querySelectorAll(".lottery-bet .bet"));
                    angular.forEach(i, function (a) {
                            var i = angular.element(a).attr("data-id")
                                , s = angular.element(document.querySelectorAll("[data-id='" + i + "'] .bet-item"));
                            s.text(t.lines[t.lotteryPanId][i]),
                                angular.element(document.querySelectorAll("[data-id='" + i + "']")).addClass("ok")
                        }
                    )
                }
                L.lineUpdate = !0
            }
        }
            , E = function (a, i) {
            var s = {}
                , e = "";
            e = t.isForNumber ? "<ion-scroll direction=\"y\"><div ng-repeat=\"item in betList.MBetParameters\"><span>{{item.Pel}}【{{item.DisplayText}}】</span><br /><span>{{'HongKongCai.LabelNumberOfCombinations'|translate}}：{{current.count}}</span><br /><span>{{'HongKongCai.LabelOneBetMoney'|translate}}：</span><span>{{item.Money.toFixed(2)}}</span><br /><span>{{'HongKongCai.LabelTotalAmount'|translate}}：{{(current.count * item.Money).toFixed(2)}}</span></div></ion-scroll>" : '<ion-scroll direction="y"><div ng-repeat="item in betList.MBetParameters"><span>{{item.Pel}}【{{item.BetContext}}】</span><span> @<span ng-class="{red:item.Change}">{{item.Lines}}</span> X</span><span> {{item.Money.toFixed(2)}}</span></div></ion-scroll>',
                s = {
                    cssClass: "lotter-popup",
                    template: e,
                    title: n("translate")("Common.LabelBetListing"),
                    scope: t,
                    buttons: []
                },
                1 == i ? (x(a, 1),
                    s.buttons.push({
                        text: n("translate")("Common.ButtonCancel"),
                        onTap: function () {
                            angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                        }
                    }),
                    s.buttons.push({
                        text: "<b>" + n("translate")("LotteryManagent.ButtonSave") + "</b>",
                        type: "button-positive",
                        onTap: function () {
                            var i = function (i) {
                                    l.hideLoading(),
                                        0 === i.ErrorCode ? (t.current.money = "",
                                            C.clear(),
                                            l.tips('<div class="tipDialog">' + n("translate")("Common.LabelBetSuccess") + "</div>")) : 2 === i.ErrorCode ? (l.tips('<div class="tipDialog">' + i.ErrorAuthServiceMsg + "</div>"),
                                            t.current.money = "",
                                            C.clear()) : 3 === i.ErrorCode || 4 === i.ErrorCode || 5 === i.ErrorCode || 6 === i.ErrorCode ? l.tips('<div class="tipDialog">' + i.ErrorMsg + "<div>") : 9 === i.ErrorCode ? (t.lines[t.lotteryPanId] = i.Data.Lines,
                                            L.lineUpdate = !1,
                                            W(),
                                            E(a, 2)) : l.tips('<div class="tipDialog">' + n("translate")("Common.LabelBetError") + "</div>"),
                                        angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                                }
                                , s = function () {
                                    angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                                }
                                ;
                            l.loading(1e6, "<ion-spinner></ion-spinner>"),
                                o.bet(t.betList).then(i, s)
                        }
                    })) : (x(a, 2),
                    s.title = n("translate")("Common.LabelPriceChanges"),
                    s.buttons.push({
                        text: n("translate")("Common.LabelBetCancel"),
                        onTap: function () {
                            angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                        }
                    }),
                    s.buttons.push({
                        text: "<b>" + n("translate")("Common.LabelBetContinue") + "</b>",
                        type: "button-positive",
                        onTap: function () {
                            var i = function (i) {
                                    l.hideLoading(),
                                        0 === i.ErrorCode ? (t.current.money = "",
                                            C.clear(),
                                            l.tips('<div class="tipDialog">' + n("translate")("Common.LabelBetSuccess") + "</div>")) : 2 === i.ErrorCode ? (l.tips('<div class="tipDialog">' + n("translate")("Common.TipsClosed") + "</div>"),
                                            t.current.money = "",
                                            C.clear()) : 3 === i.ErrorCode || 4 === i.ErrorCode || 5 === i.ErrorCode || 6 === i.ErrorCode ? l.tips('<div class="tipDialog">' + i.ErrorMsg + "<div>") : 9 === i.ErrorCode ? (t.lines[t.lotteryPanId] = i.Data.Lines,
                                            L.lineUpdate = !1,
                                            W(),
                                            E(a, 2)) : l.tips('<div class="tipDialog">' + n("translate")("Common.LabelBetError") + "</div>"),
                                        angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                                }
                                , s = function () {
                                    angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                                }
                                ;
                            l.loading(1e6, "<ion-spinner ></ion-spinner>"),
                                o.bet(t.betList).then(i, s)
                        }
                    })),
                t.betList.MBetParameters.length > 0 ? _ = c.show(s) : (l.tips(5 == t.lotteryPanId || 6 == t.lotteryPanId || 11 == t.lotteryPanId || 12 == t.lotteryPanId || 13 == t.lotteryPanId ? '<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelLeastChooseOption", t.combNumEnd) + "</div>" : 10 == t.lotteryPanId ? '<div class="tipDialog">' +  n("translate")("HongKongCai.LabelAllowChooseThan_"+t.combNum) + "</div>" : '<div class="tipDialog">' + n("translate")("Common.LabelPleaseEnterBets") + "</div>"),
                    angular.element(document.querySelector("#betbt")).removeAttr("disabled"))
        }
            , R = !1;
        t.getBankData = function () {
            function a(a) {
                0 == a.ErrorCode ? (t.bankData = parseFloat(a.Data.Balance).toFixed(2),
                    t.loaded = !0,
                a.Data.Balance < 1 && !R && (R = !0,
                    l.alertCancelConfirm(n("translate")("Common.TipsBalanceNotEnough"), function () {
                        }
                        , function () {
                            b.go("transfer")
                        }
                    ))) : (t.bankData = "0.00",
                    t.loaded = !0)
            }

            t.loaded = !1;
            var i = function () {
                    t.bankData = "0.00",
                        t.loaded = !0,
                        t.notCountMoney = -1
                }
                ;
            r.getMemberBalance().then(a, i)
        }
            ,
            t.getBankData(),
            H = e(g, 1e3),
            C.hasActiveScope = function () {
                return a.isActiveScope(t)
            }
            ,
            C.submit = function (a) {
                /*
                if (t.betList.LotteryPan = t.lotteryPanId,
                    10 == t.lotteryPanId) {
                    var i = ["鼠", "虎", "龙", "马", "猴", "狗"]
                        , s = ["牛", "兔", "蛇", "羊", "鸡", "猪"];
                    if (t.betList.MBetParameters.length > 0) {
                        for (var e = t.betList.MBetParameters[0].BetContext.split(","), d = 0, o = 0, c = 0; 6 > c; c++)
                            -1 != i.indexOf(e[c]) && d++,
                            -1 != s.indexOf(e[c]) && o++;
                        if (0 == d || 0 == o)
                            return void l.tips('<div class="tipDialog">' + n("translate")("HongKongCai.LabelNotChooseSingleOrPairs") + "</div>")
                    }
                }*/
                if(t.betList.LotteryPan = t.lotteryPanId,6 == t.lotteryPanId) {
                     if (t.betList.MBetParameters.length > 0) {
                        //for (var e = t.betList.MBetParameters[0].BetContext.split(","), d = 0, o = 0, c = 0; 6 > c; c++)
                        //    -1 != i.indexOf(e[c]) && d++,
                        //    -1 != s.indexOf(e[c]) && o++;
                        //if (0 == d || 0 == o)
                         //   return void l.tips('<div class="tipDialog">' + n("translate")("HongKongCai.LabelNotChooseSingleOrPairs") + "</div>")
                    }
                }
                t.current.count * parseInt(a) > t.current.memberBalance ? (l.alertCancelConfirm(n("translate")("Common.TipsBalanceNotEnough"), function () {
                    }
                    , function () {
                        b.go("transfer")
                    }
                ),
                    angular.element(document.querySelector("#betbt")).removeAttr("disabled")) : E(a, 1)
            }
            ,
            C.destroy = function () {
                y = !0,
                    e.cancel(H),
                    C = null ,
                    t.baseInfo = null ,
                    t.lines = [],
                    t.betList = null
            }
            ,
            C.clear = function () {
                t.current.count = 0,
                    M();
                var a = angular.element(document.querySelectorAll(".lottery-bet .bet"));
                t.betList.MBetParameters = [],
                t.betList.CombNum = 1,
                    t.isForNumberbetList.BetItems = [],
                    angular.forEach(a, function (t) {
                            angular.element(t).removeClass("bet-choose")
                        }
                    )
            }
    }
    ]),
    angular.module("controllers.beiJingKuailLeBa", []).controller("BeiJingKuailLeBaCtrl", ["$scope", "$ionicHistory", "LotteryInfoService", "$timeout", "$interval", "utils", "$filter", "QuickDialog", "LotteryBetService", "$ionicPopup", "$cordovaLocalStorage", "$state", "AccountService", function (t, a, i, s, e, d, n, l, o, c, v, b, m) {
        function r() {
            t.baseInfo.CloseCount > 0 ? t.baseInfo.CloseTime = d.secondsFormat(t.baseInfo.CloseCount, 1) : (t.baseInfo.CloseTime = n("translate")("Common.TipsClosed"),
                angular.element(document.querySelector("#betbt")).removeAttr("disabled")),
                t.baseInfo.OpenCount > 0 ? (t.baseInfo.OpenTime = d.secondsFormat(t.baseInfo.OpenCount, 1),
                    t.baseInfo.OpenCount--) : (t.baseInfo.OpenTime = n("translate")("Common.LabelOpenResulting"),
                    angular.element(document.querySelector("#betbt")).removeAttr("disabled"));
            var a = angular.element(document.querySelector(".bet-view"))
                , i = angular.element(document.querySelector(".bar-footer .close-pan"));
            if (t.baseInfo.CloseCount <= 0) {
                if (0 == i.length && (i.remove(),
                        t.baseInfo.PreResult)) {
                    a.addClass("opacityBetView");
                    var s = angular.element(document.querySelector(".bar-footer"))
                        , e = angular.element('<div class="close-pan"><div class="tip">' + n("translate")("Common.TipsClosed") + "</div></div>");
                    s.append(e)
                }
                angular.element(document.querySelector("#j-money")).attr({
                    disabled: "disabled"
                }),
                    y.infoUpdate = !1,
                    A(),
                    X(),
                L && (L.close(),
                    L = null ),
                    t.current.count = 0,
                    t.betList.MBetParameters = [],
                    t.isForNumberbetList.BetItems = [],
                    t.lines = []
            } else
                i.remove(),
                    a.removeClass("opacityBetView"),
                    angular.element(document.querySelector("#j-money")).removeAttr("disabled"),
                    Q(),
                    t.baseInfo.CloseCount--
        }

        t.sxList = [];
        var g = {
            BASE_INFO: "BASE_INFO"
        }
            , p = v.getItem(g.BASE_INFO) || [];
        if ("string" == typeof p) {
            p = JSON.parse(p);
            var u = [];
            angular.forEach(p.SxList, function (t, a) {
                    u[a] = t.join(",")
                }
            ),
                t.sxList = u
        }
        var h = this
            , C = !1;
        t.lotteryPanId = 0,
            t.isForNumber = !1,
            t.playTypeId = 0,
            t.combNum = 0,
            t.combNumEnd = 0;
        var y = {
            lineUpdate: !1
        }
            , L = null
            , _ = {
            LotteryId: 5,
            Lotterypan: t.lotteryPanId
        };
        t.baseInfo = {
            CurrentPeriod: null,
            PrePeriodNumber: null,
            CloseCount: 0,
            CloseTime: "00:00",
            OpenCount: 0,
            OpenTime: "00:00",
            PreResult: null,
            SxResult: null,
            ShowResult: null
        },
            t.lines = [];
        var w = null;
        t.betList = {
            LotteryId: 5,
            CombNum:1,
            LotteryPan: t.lotteryPanId,
            MBetParameters: []
        },
            t.isForNumberbetList = {
                LotteryId: 5,
                LotteryPan: t.lotteryPanId,
                BetItems: []
            },
            t.subNavInit = function (a) {
                A(),
                    h.clear(),
                    6 == t.lotteryPanId || 7 == t.lotteryPanId || 8 == t.lotteryPanId ? (t.isForNumber = !1,
                        I()): 1 == t.lotteryPanId ? (t.combNum = 1,
                        t.combNumEnd = 10,
                        t.isForNumber = !0,
                        P()) : 2 == t.lotteryPanId ? (t.combNum = 2,
                        t.combNumEnd = 6,
                        t.isForNumber = !0,
                        P()) : 3 == t.lotteryPanId ? (t.combNum = 3,
                        t.combNumEnd = 6,
                        t.isForNumber = !0,
                        P()) : 4 == t.lotteryPanId ? (t.combNum = 4,
                        t.combNumEnd = 7,
                        t.isForNumber = !0,
                        P()) : 5 == t.lotteryPanId ? (t.combNum = 5,
                        t.combNumEnd = 8,
                        t.isForNumber = !0,
                        P()) : t.isForNumber = !1,
                    t.isForNumberbetList.LotteryPan = t.lotteryPanId,
                    t.betList.LotteryPan = t.lotteryPanId,
                    f()
            }
            ,
            t.menuInit = function (a) {
                var i = [1, 2, 3, 4, 5, 6, 7, 8];
                //I(),
                    y.lineUpdate = !1,
                    A(),
                    h.clear(),
                    M(a),
                    t.isForNumber = !1,
                    t.lotteryPanId = i[a],
                    _.Lotterypan = t.lotteryPanId,
                     6 == t.lotteryPanId || 7 == t.lotteryPanId || 8 == t.lotteryPanId ? (t.isForNumber = !1,
                        I()) : 1 == t.lotteryPanId ? (t.combNum = 1,
                        t.combNumEnd = 10,
                        t.isForNumber = !0,
                        P()) : 2 == t.lotteryPanId ? (t.combNum = 2,
                        t.combNumEnd = 6,
                        t.isForNumber = !0,
                        P()) : 3 == t.lotteryPanId ? (t.combNum = 3,
                        t.combNumEnd = 6,
                        t.isForNumber = !0,
                        P()) : 4 == t.lotteryPanId ? (t.combNum = 4,
                        t.combNumEnd = 6,
                        t.isForNumber = !0,
                        P()) : 5 == t.lotteryPanId ? (t.combNum = 5,
                        t.combNumEnd = 6,
                        t.isForNumber = !0,
                        P()) : t.isForNumber = !1,
                    t.isForNumberbetList.LotteryPan = t.lotteryPanId,
                    t.betList.LotteryPan = t.lotteryPanId,
                    //t.subNavInit(0)
                    //f()
                    window.setTimeout(f(), 1e3)
            }
        ;
        var H = null
            , f = function () {
                var a = function (t) {
                        401 == t.status || 901 == t.status || C || (null != H && s.cancel(H),
                            H = s(function () {
                                    f()
                                }
                                , 2e4))
                    }
                    , e = function (a) {
                        0 === a.ErrorCode && (t.lines[t.lotteryPanId] = a.Data.Lines,
                        t.baseInfo && (t.baseInfo.CurrentPeriod = a.Data.CurrentPeriod,
                            t.baseInfo.PrePeriodNumber = a.Data.PrePeriodNumber,
                            t.baseInfo.CloseCount = a.Data.CloseCount,
                            t.baseInfo.PreResult = a.Data.PreResult,
                            t.baseInfo.SxResult = a.Data.SxResult,
                            t.baseInfo.ShowResult = a.Data.PreResult,
                            t.baseInfo.OpenCount = a.Data.OpenCount),
                        C || (null != H && (s.cancel(H),
                            C = !1),
                            H = s(function () {
                                    f()
                                }
                                , 1e4)))
                    }
                    ;
                y.lineUpdate = !1,
                    i.getHFLines(_).then(e, a)
            }
            ;
        t.loop = function (a) {
            t.baseInfo.ShowResult = a != t.baseInfo.SxResult ? t.baseInfo.SxResult : t.baseInfo.PreResult
        }
        ;
        var Z = function (a) {
            for (var i = 0; i < t.betList.MBetParameters.length; i++)
                if (t.betList.MBetParameters[i].Id == a)
                    return i;
            return -1
        }
            , N = function (a) {
            for (var i = 0; i < t.isForNumberbetList.BetItems.length; i++)
                if (t.isForNumberbetList.BetItems[i].numberKey == a)
                    return i;
            return -1
        }
            , D = function (a) {
            var i = Z(a);
            -1 != i && (t.betList.MBetParameters.splice(i, 1),
                t.current.count--,
                x())
        }
            , S = function (a) {
            var i = N(a);
            -1 != i && t.isForNumberbetList.BetItems.splice(i, 1);
            var s = angular.element(document.querySelectorAll("[data-id='" + a + "']"))
                , e = s.parent().parent().attr("data-title"),lines = ""
                , n = s.parent().parent().attr("data-playId");
            if (t.betList.MBetParameters = [],
                    t.current.count = d.getCombinationNum(t.isForNumberbetList.BetItems, t.combNum).length,
                t.current.count > 0) {
                for (var l = "", o = "", c = t.isForNumberbetList.BetItems.length, v = 0; c > v; v++)
                    l += t.isForNumberbetList.BetItems[v].BetContext  + ",",
                        o += t.isForNumberbetList.BetItems[v].BetContext + ",";
                l = l.substr(0, l.length - 1),
                    o = o.substr(0, o.length - 1);
                    var oo = angular.element(document.querySelector(".betLine")).attr("data-id");
                    lines = t.lines[t.lotteryPanId][oo]
                var b = {
                    DisplayText: o,
                    BetContext: l,
                    IsForNumber: !0,
                    BetType: 5,
                    IsTeMa: 0,
                    Money: 0,
                    Lines: lines,
                    Id: n,
                    Pel: e,
                    Change: !1
                };
                if (1 == t.lotteryPanId || 2 == t.lotteryPanId || 3 == t.lotteryPanId || 4 == t.lotteryPanId || 5 == t.lotteryPanId)  {
                    var o = angular.element(document.querySelectorAll(".betLine")),id="",c="";
                    angular.forEach(o,function(a){
                        var s = angular.element(a);
                        id = s.attr("data-id"),
                            c +=  s.attr("data-txt") + ":" + t.lines[t.lotteryPanId][id] + ","
                    })
                        b.Lines = t.lines[t.lotteryPanId][id],
                        b.Id = id,
                        b.BetType = 1,
                        b.Txt = c,//"二全中:61;二全中:61;",
                        b.IsForNumber = !1,
                        t.betList.CombNum = t.combNum
                }
                t.betList.MBetParameters.push(b),
                    x()
            } else
                X()
        }
            , B = function (a) {
            var i = Z(a);
            if (-1 == i) {
                var s = angular.element(document.querySelectorAll("[data-id='" + a + "']"))
                    , e = s.parent().parent().attr("data-title")
                    , d = angular.element(document.querySelectorAll("[data-id='" + a + "'] .bet-content")).text()
                    , n = 1;
                21 != t.lotteryPanId && (n = 0);
                var l = {
                    Id: a,
                    Lines: t.lines[t.lotteryPanId][a],
                    Money: 0,
                    BetContext: d,
                    IsForNumber: !1,
                    BetType: 1,
                    IsTeMa: n,
                    Pel: e,
                    Change: !1
                };
                if (1 == t.lotteryPanId){
                    var o = angular.element(document.querySelectorAll(".betLine")),id="",c="";
                    angular.forEach(o,function(a){
                        var s = angular.element(a);
                        id = s.attr("data-id"),
                        c +=  s.attr("data-txt") + ":" + t.lines[t.lotteryPanId][id] + ","
                    })
                    l.Lines = t.lines[t.lotteryPanId][id],
                        l.BetType = 1,
                        l.Txt = c,//"二全中:61;二全中:61;",
                        l.IsForNumber = !1,
                        t.betList.CombNum = t.combNum
                }
                t.betList.MBetParameters.push(l),
                    t.current.count++,
                    x()
            }
        }
            , K = function (a) {
            var i = angular.element(document.querySelectorAll("[data-id='" + a + "']"))
                , s = i.parent().parent().attr("data-title")
                , e = i.parent().parent().attr("data-playId")
                , n = angular.element(document.querySelectorAll("[data-id='" + a + "'] .bet-content")).text()
                , l = {
                numberKey: a,
                Lines: t.lines[t.lotteryPanId][a],
                BetContext: n,
                Pel: s
            };
            if (1 == t.lotteryPanId || 2 == t.lotteryPanId || 3 == t.lotteryPanId || 4 == t.lotteryPanId || 5 == t.lotteryPanId) {

                var o = angular.element(document.querySelector(".betLine")).attr("data-id");
                l.Lines = t.lines[t.lotteryPanId][o]
            }
            if (t.betList.MBetParameters = [],
                    t.isForNumberbetList.BetItems.push(l),
                    t.current.count = d.getCombinationNum(t.isForNumberbetList.BetItems, t.combNum).length,
                t.current.count > 0) {
                var c = ""
                    , v = ""
                    , b = t.isForNumberbetList.BetItems.length;
                if (1 == t.lotteryPanId || 2 == t.lotteryPanId || 3 == t.lotteryPanId || 4 == t.lotteryPanId || 5 == t.lotteryPanId)
                    for (var m = 0; b > m; m++)
                        c += t.isForNumberbetList.BetItems[m].numberKey + ",",
                            v += t.isForNumberbetList.BetItems[m].BetContext + ",";
                else
                    for (var r = 0; b > r; r++)
                        c += t.isForNumberbetList.BetItems[r].BetContext + "@" + t.isForNumberbetList.BetItems[r].Lines + ",",
                            v += t.isForNumberbetList.BetItems[r].BetContext + ",";
                c = c.substr(0, c.length - 1),
                    v = v.substr(0, v.length - 1);
                var g = {
                    DisplayText: v,
                    BetContext: c,
                    IsForNumber: !0,
                    BetType: 5,
                    IsTeMa: 0,
                    Money: 0,
                    Lines: 0,
                    Id: e,
                    Pel: s,
                    Change: !1
                };
                if (1 == t.lotteryPanId || 2 == t.lotteryPanId || 3 == t.lotteryPanId || 4 == t.lotteryPanId || 5 == t.lotteryPanId)  {
                    var o = angular.element(document.querySelectorAll(".betLine")),id="",c="";
                    angular.forEach(o,function(a){
                        var s = angular.element(a);
                        id = s.attr("data-id"),
                        c +=  s.attr("data-txt") + ":" + t.lines[t.lotteryPanId][id] + ","
                    })
                    g.Lines = t.lines[t.lotteryPanId][id],
                        g.Id = id,
                        g.BetType = 1,
                        g.Txt = c,//"二全中:61;二全中:61;",
                        g.IsForNumber = !1,
                        t.betList.CombNum = t.combNum
                }
                t.betList.MBetParameters.push(g),
                    x()
            } else
                X()
        }
            , T = function (a, i) {
            for (var s = 0; s < t.betList.MBetParameters.length; s++)
                if (t.betList.MBetParameters[s].Money = parseInt(a),
                    1 != i)
                    if (t.isForNumber) {
                        for (var e = t.betList.MBetParameters[s], d = e.BetContext.split(","), n = [], l = 0; l < d.length; l++) {
                            var o = d[l].split("@");
                            o[1] != t.lines[t.lotteryPanId][o[0]] && (o[1] = t.lines[t.lotteryPanId][o[0]]),
                                n.push(o.join("@"))
                        }
                        t.betList.MBetParameters[s].BetContext = n.join(","),
                            t.betList.MBetParameters[s].Change = !0
                    } else
                        t.betList.MBetParameters[s].Lines != t.lines[t.lotteryPanId][t.betList.MBetParameters[s].Id] && (t.betList.MBetParameters[s].Lines = t.lines[t.lotteryPanId][t.betList.MBetParameters[s].Id],
                            t.betList.MBetParameters[s].Change = !0)
        }
            , x = function () {
            var t = angular.element(document.querySelectorAll(".cur-menus .nav .active .smallround"))
                , a = document.querySelectorAll(".lottery-bet .bet-choose");
            a.length > 0 ? t.addClass("menus-choose") : t.removeClass("menus-choose")
        }
            , X = function () {
            var t = angular.element(document.querySelectorAll(".cur-menus .nav  .smallround"));
            angular.forEach(t, function (t) {
                    var a = angular.element(t);
                    a.removeClass("menus-choose")
                }
            )
        }
            , M = function (a) {
            for (var i = !1, s = document.querySelectorAll(".tab-content")[0], e = 0; e < t.betList.MBetParameters.length; e++) {
                var d = angular.element(s.querySelectorAll("[data-id='" + t.betList.MBetParameters[e].Id + "']"));
                d.length > 0 && (d.addClass("bet-choose"),
                    i = !0)
            }
            var n = angular.element(document.querySelectorAll(".cur-menus .nav .smallround"))
                , l = 0;
            angular.forEach(n, function (t) {
                    if (a == l) {
                        var s = angular.element(t);
                        i ? s.addClass("menus-choose") : s.removeClass("menus-choose")
                    }
                    l++
                }
            )
        }
            , I = function () {
            var a = angular.element(document.querySelectorAll(".lottery-bet .bet"));
            angular.forEach(a, function (a) {
                    var i = angular.element(a)
                        , s = angular.element(a).attr("data-id");
                    i.bind("click", function () {
                            t.baseInfo.CloseCount > 0 && null != t.lines[t.lotteryPanId] && (i.hasClass("bet-choose") ? (i.removeClass("bet-choose"),
                                D(s)) : (i.addClass("bet-choose"),
                                B(s)))
                        }
                    )
                }
            )
        }
            , P = function () {
            var a = angular.element(document.querySelectorAll(".lottery-bet .bet"));
            angular.forEach(a, function (a) {
                    var i = angular.element(a)
                        , s = angular.element(a).attr("data-id");
                    i.bind("click", function () {
                            if (t.baseInfo.CloseCount > 0 && null != t.lines[t.lotteryPanId])
                                if (i.hasClass("bet-choose"))
                                    i.removeClass("bet-choose"),
                                        S(s);
                                else {
                                   if (1 == t.lotteryPanId || 2 == t.lotteryPanId || 3 == t.lotteryPanId || 4 == t.lotteryPanId || 5 == t.lotteryPanId) {
                                       if(t.combNum == 1){
                                           if (t.isForNumberbetList.BetItems.length >= 10)
                                               return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_10") + "</div>")
                                       }else if(t.combNum == 2){
                                           if (t.isForNumberbetList.BetItems.length >= 6)
                                               return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_6") + "</div>")
                                       }else if(t.combNum == 3){
                                           if (t.isForNumberbetList.BetItems.length >= 6)
                                               return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_6") + "</div>")
                                       }else if(t.combNum == 4){
                                           if (t.isForNumberbetList.BetItems.length >= 7)
                                               return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_7") + "</div>")
                                       }else if(t.combNum == 5){
                                           if (t.isForNumberbetList.BetItems.length >= 8)
                                               return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_8") + "</div>")
                                       }
                                    } else if (42 == t.lotteryPanId) {
                                        if (6 == t.isForNumberbetList.BetItems.length)
                                            return void l.tips('<div class="tipDialog">' + n("translate")("HongKongCai.LabelAllowChooseThan_6") + "</div>")
                                    } else if (43 == t.lotteryPanId && t.isForNumberbetList.BetItems.length >= 8)
                                        return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_8") + "</div>");
                                    i.addClass("bet-choose"),
                                        K(s)
                                }
                        }
                    )
                }
            )
        }
            , A = function () {
            var t = angular.element(document.querySelectorAll(".lottery-bet .tab-content .scroll-content"));
            t.addClass("close");
            var a = angular.element(document.querySelector(".bet-view"));
            a.addClass("opacityBetView");
            var i = angular.element(document.querySelectorAll(".lottery-bet .bet"));
            angular.element(document.querySelector(".betLine .line")).text("-"),
                angular.forEach(i, function (t) {
                        angular.element(t).removeClass("bet-choose");
                        var a = angular.element(t).attr("data-id");
                        angular.element(document.querySelectorAll("[data-id='" + a + "'] .bet-item")).text("-"),
                            angular.element(document.querySelectorAll("[data-id='" + a + "']")).removeClass("ok")
                    }
                )
        }
            , Q = function () {
            if (null != t.lines[t.lotteryPanId] && !y.lineUpdate) {
                if (1 == t.lotteryPanId || 2 == t.lotteryPanId || 3 == t.lotteryPanId || 4 == t.lotteryPanId || 5 == t.lotteryPanId) {
                    var a = angular.element(document.querySelectorAll(".lottery-bet .tab-content .scroll-content"));
                    a.removeClass("close");
                    var i = angular.element(document.querySelectorAll(".lottery-bet .betLine"));
                    angular.forEach(i, function (a) {
                            var i = angular.element(a).attr("data-id"),txt = angular.element(a).attr("data-txt")
                                , s = angular.element(document.querySelectorAll("[data-id='" + i + "'] .line"));
                            s.text(null != t.lines[t.lotteryPanId][i] ? txt + ":" + t.lines[t.lotteryPanId][i] : txt +  "：0"),
                                angular.element(document.querySelectorAll("[data-id='" + i + "']")).addClass("ok"),
                                angular.element(document.querySelectorAll(".col.bet")).addClass("ok")
                        }
                    )
                } else {
                    var a = angular.element(document.querySelectorAll(".lottery-bet .tab-content .scroll-content"));
                    a.removeClass("close");
                    var i = angular.element(document.querySelectorAll(".lottery-bet .bet"));
                    angular.forEach(i, function (a) {
                            var i = angular.element(a).attr("data-id")
                                , s = angular.element(document.querySelectorAll("[data-id='" + i + "'] .bet-item"));
                            s.text(t.lines[t.lotteryPanId][i]),
                                angular.element(document.querySelectorAll("[data-id='" + i + "']")).addClass("ok")
                        }
                    )
                }
                y.lineUpdate = !0
            }
        }
            , W = function (a, i) {
            var s = {}
                , e = "";
            e = t.isForNumber ? "<ion-scroll direction=\"y\"><div ng-repeat=\"item in betList.MBetParameters\"><span>{{item.Pel}}【{{item.DisplayText}}】</span><br /><span>{{'HongKongCai.LabelNumberOfCombinations'|translate}}：{{current.count}}</span><br /><span>{{'HongKongCai.LabelOneBetMoney'|translate}}：</span><span>{{item.Money.toFixed(2)}}</span><br /><span>{{'HongKongCai.LabelTotalAmount'|translate}}：{{(current.count * item.Money).toFixed(2)}}</span></div></ion-scroll>" : '<ion-scroll direction="y"><div ng-repeat="item in betList.MBetParameters"><span>{{item.Pel}}【{{item.BetContext}}】</span><span> @<span ng-class="{red:item.Change}">{{item.Lines}}</span> X</span><span > {{item.Money.toFixed(2)}}</span></div></ion-scroll>',
                s = {
                    cssClass: "lotter-popup",
                    template: e,
                    title: n("translate")("Common.LabelBetListing"),
                    scope: t,
                    buttons: []
                },
                1 == i ? (T(a, 1),
                    s.buttons.push({
                        text: n("translate")("Common.ButtonCancel"),
                        onTap: function () {
                            angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                        }
                    }),
                    s.buttons.push({
                        text: "<b>" + n("translate")("LotteryManagent.ButtonSave") + "</b>",
                        type: "button-positive",
                        onTap: function () {
                            var i = function (i) {
                                    l.hideLoading(),
                                        0 === i.ErrorCode ? (t.current.money = "",
                                            h.clear(),
                                            l.tips('<div class="tipDialog">' + n("translate")("Common.LabelBetSuccess") + "</div>")) : 2 === i.ErrorCode ? (l.tips('<div class="tipDialog">' + i.ErrorMsg + "</div>"),
                                            t.current.money = "",
                                            h.clear()) : 3 === i.ErrorCode || 4 === i.ErrorCode || 5 === i.ErrorCode || 6 === i.ErrorCode ? l.tips('<div class="tipDialog">' + i.ErrorMsg + "<div>") : 9 === i.ErrorCode ? (t.lines[t.lotteryPanId] = i.Data.Lines,
                                            y.lineUpdate = !1,
                                            Q(),
                                            W(a, 2)) : l.tips('<div class="tipDialog">' + n("translate")("Common.LabelBetError") + "</div>"),
                                        angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                                }
                                , s = function () {
                                    angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                                }
                                ;
                            l.loading(1e6, "<ion-spinner></ion-spinner>"),
                                o.bet(t.betList).then(i, s)
                        }
                    })) : (T(a, 2),
                    s.title = n("translate")("Common.LabelPriceChanges"),
                    s.buttons.push({
                        text: n("translate")("Common.LabelBetCancel"),
                        onTap: function () {
                            angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                        }
                    }),
                    s.buttons.push({
                        text: "<b>" + n("translate")("Common.LabelBetContinue") + "</b>",
                        type: "button-positive",
                        onTap: function () {
                            var i = function (i) {
                                    l.hideLoading(),
                                        0 === i.ErrorCode ? (t.current.money = "",
                                            h.clear(),
                                            l.tips('<div class="tipDialog">' + n("translate")("Common.LabelBetSuccess") + "</div>")) : 2 === i.ErrorCode ? (l.tips('<div class="tipDialog">' + n("translate")("Common.TipsClosed") + "</div>"),
                                            t.current.money = "",
                                            h.clear()) : 3 === i.ErrorCode || 4 === i.ErrorCode || 5 === i.ErrorCode || 6 === i.ErrorCode ? l.tips('<div class="tipDialog">' + i.ErrorMsg + "<div>") : 9 === i.ErrorCode ? (t.lines[t.lotteryPanId] = i.Data.Lines,
                                            y.lineUpdate = !1,
                                            Q(),
                                            W(a, 2)) : l.tips('<div class="tipDialog">' + n("translate")("Common.LabelBetError") + "</div>"),
                                        angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                                }
                                , s = function () {
                                    angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                                }
                                ;
                            l.loading(1e6, "<ion-spinner ></ion-spinner>"),
                                o.bet(t.betList).then(i, s)
                        }
                    })),
                t.betList.MBetParameters.length > 0 ? L = c.show(s) : (l.tips(1 == t.lotteryPanId || 2 == t.lotteryPanId || 3 == t.lotteryPanId || 4 == t.lotteryPanId || 5 == t.lotteryPanId ? '<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelLeastChooseOption", t.combNumEnd) + "</div>" : 42 == t.lotteryPanId ? '<div class="tipDialog">' + n("translate")("HongKongCai.LabelAllowChooseThan_6") + "</div>" : '<div class="tipDialog">' + n("translate")("Common.LabelPleaseEnterBets") + "</div>"),
                    angular.element(document.querySelector("#betbt")).removeAttr("disabled"))
        }
            , E = !1;
        t.getBankData = function () {
            function a(a) {
                0 == a.ErrorCode ? (t.bankData = parseFloat(a.Data.Balance).toFixed(2),
                    t.loaded = !0,
                a.Data.Balance < 1 && !E && (E = !0,
                    l.alertCancelConfirm(n("translate")("Common.TipsBalanceNotEnough"), function () {
                        }
                        , function () {
                            b.go("transfer")
                        }
                    ))) : (t.bankData = "0.00",
                    t.loaded = !0)
            }

            t.loaded = !1;
            var i = function () {
                    t.bankData = "0.00",
                        t.loaded = !0,
                        t.notCountMoney = -1
                }
                ;
            m.getMemberBalance().then(a, i)
        }
            ,
            t.getBankData(),
            w = e(r, 1e3),
            h.hasActiveScope = function () {
                return a.isActiveScope(t)
            }
            ,
            h.submit = function (a) {
                if (t.betList.LotteryPan = t.lotteryPanId,
                    42 == t.lotteryPanId) {
                    var i = ["鼠", "虎", "龙", "马", "猴", "狗"]
                        , s = ["牛", "兔", "蛇", "羊", "鸡", "猪"];
                    if (t.betList.MBetParameters.length > 0) {
                        for (var e = t.betList.MBetParameters[0].BetContext.split(","), d = 0, o = 0, c = 0; 6 > c; c++)
                            -1 != i.indexOf(e[c]) && d++,
                            -1 != s.indexOf(e[c]) && o++;
                        if (0 == d || 0 == o)
                            return void l.tips('<div class="tipDialog">' + n("translate")("HongKongCai.LabelNotChooseSingleOrPairs") + "</div>")
                    }
                }
                t.current.count * parseInt(a) > t.current.memberBalance ? (l.alertCancelConfirm(n("translate")("Common.TipsBalanceNotEnough"), function () {
                    }
                    , function () {
                        b.go("transfer")
                    }
                ),
                    angular.element(document.querySelector("#betbt")).removeAttr("disabled")) : W(a, 1)
            }
            ,
            h.destroy = function () {
                C = !0,
                    e.cancel(w),
                    h = null ,
                    t.baseInfo = null ,
                    t.lines = [],
                    t.betList = null
            }
            ,
            h.clear = function () {
                t.current.count = 0,
                    X();
                var a = angular.element(document.querySelectorAll(".lottery-bet .bet"));
                t.betList.MBetParameters = [],
                t.betList.CombNum = 1,
                    t.isForNumberbetList.BetItems = [],
                    angular.forEach(a, function (t) {
                            angular.element(t).removeClass("bet-choose")
                        }
                    )
            }
    }
    ]),
    angular.module("controllers.gdKuaiLeShiFen", []).controller("GdKuaiLeShiFenCtrl", ["$scope", "$ionicHistory", "LotteryInfoService", "$timeout", "$interval", "utils", "$filter", "QuickDialog", "LotteryBetService", "$ionicPopup", "$cordovaLocalStorage", "$state", "AccountService", function (t, a, i, s, e, d, n, l, o, c, v, b, m) {
        function r() {
            t.baseInfo.CloseCount > 0 ? t.baseInfo.CloseTime = d.secondsFormat(t.baseInfo.CloseCount, 1) : (t.baseInfo.CloseTime = n("translate")("Common.TipsClosed"),
                angular.element(document.querySelector("#betbt")).removeAttr("disabled")),
                t.baseInfo.OpenCount > 0 ? (t.baseInfo.OpenTime = d.secondsFormat(t.baseInfo.OpenCount, 1),
                    t.baseInfo.OpenCount--) : (t.baseInfo.OpenTime = n("translate")("Common.LabelOpenResulting"),
                    angular.element(document.querySelector("#betbt")).removeAttr("disabled"));
            var a = angular.element(document.querySelector(".bet-view"))
                , i = angular.element(document.querySelector(".bar-footer .close-pan"));
            if (t.baseInfo.CloseCount <= 0) {
                if (0 == i.length && (i.remove(),
                        t.baseInfo.PreResult)) {
                    a.addClass("opacityBetView");
                    var s = angular.element(document.querySelector(".bar-footer"))
                        , e = angular.element('<div class="close-pan"><div class="tip">' + n("translate")("Common.TipsClosed") + "</div></div>");
                    s.append(e)
                }
                angular.element(document.querySelector("#j-money")).attr({
                    disabled: "disabled"
                }),
                    y.infoUpdate = !1,
                    A(),
                    X(),
                L && (L.close(),
                    L = null ),
                    t.current.count = 0,
                    t.betList.MBetParameters = [],
                    t.isForNumberbetList.BetItems = [],
                    t.lines = []
            } else
                i.remove(),
                    a.removeClass("opacityBetView"),
                    angular.element(document.querySelector("#j-money")).removeAttr("disabled"),
                    Q(),
                    t.baseInfo.CloseCount--
        }

        t.sxList = [];
        var g = {
            BASE_INFO: "BASE_INFO"
        }
            , p = v.getItem(g.BASE_INFO) || [];
        if ("string" == typeof p) {
            p = JSON.parse(p);
            var u = [];
            angular.forEach(p.SxList, function (t, a) {
                    u[a] = t.join(",")
                }
            ),
                t.sxList = u
        }
        var h = this
            , C = !1;
        t.lotteryPanId = 0,
            t.isForNumber = !1,
            t.playTypeId = 0,
            t.combNum = 0,
            t.combNumEnd = 0;
        var y = {
            lineUpdate: !1
        }
            , L = null
            , _ = {
            LotteryId: 12,
            Lotterypan: t.lotteryPanId
        };
        t.baseInfo = {
            CurrentPeriod: null,
            PrePeriodNumber: null,
            CloseCount: 0,
            CloseTime: "00:00",
            OpenCount: 0,
            OpenTime: "00:00",
            PreResult: null,
            SxResult: null,
            ShowResult: null
        },
            t.lines = [];
        var w = null;
        t.betList = {
            LotteryId: 12,
            CombNum:1,
            LotteryPan: t.lotteryPanId,
            MBetParameters: []
        },
            t.isForNumberbetList = {
                LotteryId: 12,
                LotteryPan: t.lotteryPanId,
                BetItems: []
            },
            t.subNavInit = function (a) {
                A(),
                    h.clear(),
                    10 == t.lotteryPanId ? (0 == a ? (t.combNum = 2,
                        t.combNumEnd = 6) : 1 == a ? (t.combNum = 2,
                        t.combNumEnd = 6) : 2 == a ? (t.combNum = 3,
                        t.combNumEnd = 6) : 3 == a ? (t.combNum = 4,
                        t.combNumEnd = 6) : 4 == a && (t.combNum = 5,
                        t.combNumEnd = 6),
                        t.isForNumber = !0,
                        P()) : 15 == t.lotteryPanId ? (t.combNum = 4,
                        t.combNumEnd = 5,
                        t.isForNumber = !0,
                        P()) : t.isForNumber = !1,
                    t.isForNumberbetList.LotteryPan = t.lotteryPanId,
                    t.betList.LotteryPan = t.lotteryPanId,
                    f()
            }
            ,
            t.menuInit = function (a) {
                var i = [1, 2, 3, 4, 5, 6, 7, 8,9,10];
                //I(),
                y.lineUpdate = !1,
                    A(),
                    h.clear(),
                    M(a),
                    t.isForNumber = !1,
                    t.lotteryPanId = i[a],
                    _.Lotterypan = t.lotteryPanId,
                    1 == t.lotteryPanId || 2 == t.lotteryPanId || 3 == t.lotteryPanId || 4 == t.lotteryPanId || 5 == t.lotteryPanId || 6 == t.lotteryPanId || 7 == t.lotteryPanId || 8 == t.lotteryPanId || 9 == t.lotteryPanId || 10 == t.lotteryPanId ? (t.isForNumber = !1,
                        I()) : 15 == t.lotteryPanId ? (t.combNum = 4,
                        t.combNumEnd = 5,
                        t.isForNumber = !0,
                        P()) : t.isForNumber = !1,
                    t.isForNumberbetList.LotteryPan = t.lotteryPanId,
                    t.betList.LotteryPan = t.lotteryPanId,
                    //t.subNavInit(0)
                    //f()
                    window.setTimeout(f(), 1e3)
            }
        ;
        var H = null
            , f = function () {
                var a = function (t) {
                        401 == t.status || 901 == t.status || C || (null != H && s.cancel(H),
                            H = s(function () {
                                    f()
                                }
                                , 2e4))
                    }
                    , e = function (a) {
                        0 === a.ErrorCode && (t.lines[t.lotteryPanId] = a.Data.Lines,
                        t.baseInfo && (t.baseInfo.CurrentPeriod = a.Data.CurrentPeriod,
                            t.baseInfo.PrePeriodNumber = a.Data.PrePeriodNumber,
                            t.baseInfo.CloseCount = a.Data.CloseCount,
                            t.baseInfo.PreResult = a.Data.PreResult,
                            t.baseInfo.SxResult = a.Data.SxResult,
                            t.baseInfo.ShowResult = a.Data.PreResult,
                            t.baseInfo.OpenCount = a.Data.OpenCount),
                        C || (null != H && (s.cancel(H),
                            C = !1),
                            H = s(function () {
                                    f()
                                }
                                , 1e4)))
                    }
                    ;
                y.lineUpdate = !1,
                    i.getHFLines(_).then(e, a)
            }
            ;
        t.loop = function (a) {
            t.baseInfo.ShowResult = a != t.baseInfo.SxResult ? t.baseInfo.SxResult : t.baseInfo.PreResult
        }
        ;
        var Z = function (a) {
            for (var i = 0; i < t.betList.MBetParameters.length; i++)
                if (t.betList.MBetParameters[i].Id == a)
                    return i;
            return -1
        }
            , N = function (a) {
            for (var i = 0; i < t.isForNumberbetList.BetItems.length; i++)
                if (t.isForNumberbetList.BetItems[i].numberKey == a)
                    return i;
            return -1
        }
            , D = function (a) {
            var i = Z(a);
            -1 != i && (t.betList.MBetParameters.splice(i, 1),
                t.current.count--,
                x())
        }
            , S = function (a) {
            var i = N(a);
            -1 != i && t.isForNumberbetList.BetItems.splice(i, 1);
            var s = angular.element(document.querySelectorAll("[data-id='" + a + "']"))
                , e = s.parent().parent().attr("data-title"),lines =""
                , n = s.parent().parent().attr("data-playId");
            if (t.betList.MBetParameters = [],
                    t.current.count = d.getCombinationNum(t.isForNumberbetList.BetItems, t.combNum).length,
                t.current.count > 0) {
                for (var l = "", o = "", c = t.isForNumberbetList.BetItems.length, v = 0; c > v; v++)
                    l += t.isForNumberbetList.BetItems[v].BetContext + ",",
                        o += t.isForNumberbetList.BetItems[v].BetContext + ",";
                l = l.substr(0, l.length - 1),
                    o = o.substr(0, o.length - 1);
                var oo = angular.element(document.querySelector(".betLine")).attr("data-id");
                lines = t.lines[t.lotteryPanId][oo]
                var b = {
                    DisplayText: o,
                    BetContext: l,
                    IsForNumber: !0,
                    BetType: 5,
                    IsTeMa: 0,
                    Money: 0,
                    Lines: lines,
                    Id: n,
                    Pel: e,
                    Change: !1
                };

                t.betList.MBetParameters.push(b),
                    x()
            } else
                X()
        }
            , B = function (a) {
            var i = Z(a);
            if (-1 == i) {
                var s = angular.element(document.querySelectorAll("[data-id='" + a + "']"))
                    , e = s.parent().parent().attr("data-title")
                    , d = angular.element(document.querySelectorAll("[data-id='" + a + "'] .bet-content")).text()
                    , n = 1;
                21 != t.lotteryPanId && (n = 0);
                var l = {
                    Id: a,
                    Lines: t.lines[t.lotteryPanId][a],
                    Money: 0,
                    BetContext: d,
                    IsForNumber: !1,
                    BetType: 1,
                    IsTeMa: n,
                    Pel: e,
                    Change: !1
                };
                if (10 == t.lotteryPanId){
                    var o = angular.element(document.querySelectorAll(".betLine")),id="",c="";
                    angular.forEach(o,function(a){
                        var s = angular.element(a);
                        id = s.attr("data-id"),
                            c +=  s.attr("data-txt") + ":" + t.lines[t.lotteryPanId][id] + ","
                    })
                    l.Lines = t.lines[t.lotteryPanId][id],
                        l.BetType = 1,
                        l.Txt = c,//"二全中:61;二全中:61;",
                        l.IsForNumber = !1,
                        t.betList.CombNum = t.combNum
                }
                t.betList.MBetParameters.push(l),
                    t.current.count++,
                    x()
            }
        }
            , K = function (a) {
            var i = angular.element(document.querySelectorAll("[data-id='" + a + "']"))
                , s = i.parent().parent().attr("data-title")
                , e = i.parent().parent().attr("data-playId")
                , n = angular.element(document.querySelectorAll("[data-id='" + a + "'] .bet-content")).text()
                , l = {
                numberKey: a,
                Lines: t.lines[t.lotteryPanId][a],
                BetContext: n,
                Pel: s
            };
            if (10 == t.lotteryPanId) {
                var o = angular.element(document.querySelector(".betLine")).attr("data-id");
                l.Lines = t.lines[t.lotteryPanId][o]
            }
            if (t.betList.MBetParameters = [],
                    t.isForNumberbetList.BetItems.push(l),
                    t.current.count = d.getCombinationNum(t.isForNumberbetList.BetItems, t.combNum).length,
                t.current.count > 0) {
                var c = ""
                    , v = ""
                    , b = t.isForNumberbetList.BetItems.length;
                if (10 == t.lotteryPanId)
                    for (var m = 0; b > m; m++)
                        c += t.isForNumberbetList.BetItems[m].numberKey + ",",
                            v += t.isForNumberbetList.BetItems[m].BetContext + ",";
                else
                    for (var r = 0; b > r; r++)
                        c += t.isForNumberbetList.BetItems[r].BetContext + "@" + t.isForNumberbetList.BetItems[r].Lines + ",",
                            v += t.isForNumberbetList.BetItems[r].BetContext + ",";
                c = c.substr(0, c.length - 1),
                    v = v.substr(0, v.length - 1);
                var g = {
                    DisplayText: v,
                    BetContext: c,
                    IsForNumber: !0,
                    BetType: 5,
                    IsTeMa: 0,
                    Money: 0,
                    Lines: 0,
                    Id: e,
                    Pel: s,
                    Change: !1
                };
                if (10 == t.lotteryPanId)  {
                    var o = angular.element(document.querySelectorAll(".betLine")),id="",c="";
                    angular.forEach(o,function(a){
                        var s = angular.element(a);
                        id = s.attr("data-id"),
                            c +=  s.attr("data-txt") + ":" + t.lines[t.lotteryPanId][id] + ","
                    })
                    g.Lines = t.lines[t.lotteryPanId][id],
                        g.Id = id,
                        g.BetType = 1,
                        g.Txt = c,//"二全中:61;二全中:61;",
                        g.IsForNumber = !1,
                        t.betList.CombNum = t.combNum
                }
                t.betList.MBetParameters.push(g),
                    x()
            } else
                X()
        }
            , T = function (a, i) {
            for (var s = 0; s < t.betList.MBetParameters.length; s++)
                if (t.betList.MBetParameters[s].Money = parseInt(a),
                    1 != i)
                    if (t.isForNumber) {
                        for (var e = t.betList.MBetParameters[s], d = e.BetContext.split(","), n = [], l = 0; l < d.length; l++) {
                            var o = d[l].split("@");
                            o[1] != t.lines[t.lotteryPanId][o[0]] && (o[1] = t.lines[t.lotteryPanId][o[0]]),
                                n.push(o.join("@"))
                        }
                        t.betList.MBetParameters[s].BetContext = n.join(","),
                            t.betList.MBetParameters[s].Change = !0
                    } else
                        t.betList.MBetParameters[s].Lines != t.lines[t.lotteryPanId][t.betList.MBetParameters[s].Id] && (t.betList.MBetParameters[s].Lines = t.lines[t.lotteryPanId][t.betList.MBetParameters[s].Id],
                            t.betList.MBetParameters[s].Change = !0)
        }
            , x = function () {
            var t = angular.element(document.querySelectorAll(".cur-menus .nav .active .smallround"))
                , a = document.querySelectorAll(".lottery-bet .bet-choose");
            a.length > 0 ? t.addClass("menus-choose") : t.removeClass("menus-choose")
        }
            , X = function () {
            var t = angular.element(document.querySelectorAll(".cur-menus .nav  .smallround"));
            angular.forEach(t, function (t) {
                    var a = angular.element(t);
                    a.removeClass("menus-choose")
                }
            )
        }
            , M = function (a) {
            for (var i = !1, s = document.querySelectorAll(".tab-content")[0], e = 0; e < t.betList.MBetParameters.length; e++) {
                var d = angular.element(s.querySelectorAll("[data-id='" + t.betList.MBetParameters[e].Id + "']"));
                d.length > 0 && (d.addClass("bet-choose"),
                    i = !0)
            }
            var n = angular.element(document.querySelectorAll(".cur-menus .nav .smallround"))
                , l = 0;
            angular.forEach(n, function (t) {
                    if (a == l) {
                        var s = angular.element(t);
                        i ? s.addClass("menus-choose") : s.removeClass("menus-choose")
                    }
                    l++
                }
            )
        }
            , I = function () {
            var a = angular.element(document.querySelectorAll(".lottery-bet .bet"));
            angular.forEach(a, function (a) {
                    var i = angular.element(a)
                        , s = angular.element(a).attr("data-id");
                    i.bind("click", function () {
                            t.baseInfo.CloseCount > 0 && null != t.lines[t.lotteryPanId] && (i.hasClass("bet-choose") ? (i.removeClass("bet-choose"),
                                D(s)) : (i.addClass("bet-choose"),
                                B(s)))
                        }
                    )
                }
            )
        }
            , P = function () {
            var a = angular.element(document.querySelectorAll(".lottery-bet .bet"));
            angular.forEach(a, function (a) {
                    var i = angular.element(a)
                        , s = angular.element(a).attr("data-id");
                    i.bind("click", function () {
                            if (t.baseInfo.CloseCount > 0 && null != t.lines[t.lotteryPanId])
                                if (i.hasClass("bet-choose"))
                                    i.removeClass("bet-choose"),
                                        S(s);
                                else {
                                    if (10 == t.lotteryPanId) {
                                        if (t.isForNumberbetList.BetItems.length >= 6)
                                            return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_6") + "</div>")

                                    } else if (42 == t.lotteryPanId) {
                                        if (6 == t.isForNumberbetList.BetItems.length)
                                            return void l.tips('<div class="tipDialog">' + n("translate")("HongKongCai.LabelAllowChooseThan_6") + "</div>")
                                    } else if (43 == t.lotteryPanId && t.isForNumberbetList.BetItems.length >= 8)
                                        return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_8") + "</div>");
                                    i.addClass("bet-choose"),
                                        K(s)
                                }
                        }
                    )
                }
            )
        }
            , A = function () {
            var t = angular.element(document.querySelectorAll(".lottery-bet .tab-content .scroll-content"));
            t.addClass("close");
            var a = angular.element(document.querySelector(".bet-view"));
            a.addClass("opacityBetView");
            var i = angular.element(document.querySelectorAll(".lottery-bet .bet"));
            angular.element(document.querySelector(".betLine .line")).text("-"),
                angular.forEach(i, function (t) {
                        angular.element(t).removeClass("bet-choose");
                        var a = angular.element(t).attr("data-id");
                        angular.element(document.querySelectorAll("[data-id='" + a + "'] .bet-item")).text("-"),
                            angular.element(document.querySelectorAll("[data-id='" + a + "']")).removeClass("ok")
                    }
                )
        }
            , Q = function () {
            if (null != t.lines[t.lotteryPanId] && !y.lineUpdate) {
                if (10 == t.lotteryPanId) {
                    var a = angular.element(document.querySelectorAll(".lottery-bet .tab-content .scroll-content"));
                    a.removeClass("close");
                    var i = angular.element(document.querySelectorAll(".lottery-bet .betLine"));
                    angular.forEach(i, function (a) {
                            var i = angular.element(a).attr("data-id"),txt = angular.element(a).attr("data-txt")
                                , s = angular.element(document.querySelectorAll("[data-id='" + i + "'] .line"));
                            s.text(null != t.lines[t.lotteryPanId][i] ? txt + ":" + t.lines[t.lotteryPanId][i] : txt +  "：0"),
                                angular.element(document.querySelectorAll("[data-id='" + i + "']")).addClass("ok"),
                                angular.element(document.querySelectorAll(".col.bet")).addClass("ok")
                        }
                    )
                } else {
                    var a = angular.element(document.querySelectorAll(".lottery-bet .tab-content .scroll-content"));
                    a.removeClass("close");
                    var i = angular.element(document.querySelectorAll(".lottery-bet .bet"));
                    angular.forEach(i, function (a) {
                            var i = angular.element(a).attr("data-id")
                                , s = angular.element(document.querySelectorAll("[data-id='" + i + "'] .bet-item"));
                            s.text(t.lines[t.lotteryPanId][i]),
                                angular.element(document.querySelectorAll("[data-id='" + i + "']")).addClass("ok")
                        }
                    )
                }
                y.lineUpdate = !0
            }
        }
            , W = function (a, i) {
            var s = {}
                , e = "";
            e = t.isForNumber ? "<ion-scroll direction=\"y\"><div ng-repeat=\"item in betList.MBetParameters\"><span>{{item.Pel}}【{{item.DisplayText}}】</span><br /><span>{{'HongKongCai.LabelNumberOfCombinations'|translate}}：{{current.count}}</span><br /><span>{{'HongKongCai.LabelOneBetMoney'|translate}}：</span><span>{{item.Money.toFixed(2)}}</span><br /><span>{{'HongKongCai.LabelTotalAmount'|translate}}：{{(current.count * item.Money).toFixed(2)}}</span></div></ion-scroll>" : '<ion-scroll direction="y"><div ng-repeat="item in betList.MBetParameters"><span>{{item.Pel}}【{{item.BetContext}}】</span><span> @<span ng-class="{red:item.Change}">{{item.Lines}}</span> X</span><span > {{item.Money.toFixed(2)}}</span></div></ion-scroll>',
                s = {
                    cssClass: "lotter-popup",
                    template: e,
                    title: n("translate")("Common.LabelBetListing"),
                    scope: t,
                    buttons: []
                },
                1 == i ? (T(a, 1),
                    s.buttons.push({
                        text: n("translate")("Common.ButtonCancel"),
                        onTap: function () {
                            angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                        }
                    }),
                    s.buttons.push({
                        text: "<b>" + n("translate")("LotteryManagent.ButtonSave") + "</b>",
                        type: "button-positive",
                        onTap: function () {
                            var i = function (i) {
                                    l.hideLoading(),
                                        0 === i.ErrorCode ? (t.current.money = "",
                                            h.clear(),
                                            l.tips('<div class="tipDialog">' + n("translate")("Common.LabelBetSuccess") + "</div>")) : 2 === i.ErrorCode ? (l.tips('<div class="tipDialog">' + i.ErrorMsg + "</div>"),
                                            t.current.money = "",
                                            h.clear()) : 3 === i.ErrorCode || 4 === i.ErrorCode || 5 === i.ErrorCode || 6 === i.ErrorCode ? l.tips('<div class="tipDialog">' + i.ErrorMsg + "<div>") : 9 === i.ErrorCode ? (t.lines[t.lotteryPanId] = i.Data.Lines,
                                            y.lineUpdate = !1,
                                            Q(),
                                            W(a, 2)) : l.tips('<div class="tipDialog">' + n("translate")("Common.LabelBetError") + "</div>"),
                                        angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                                }
                                , s = function () {
                                    angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                                }
                                ;
                            l.loading(1e6, "<ion-spinner></ion-spinner>"),
                                o.bet(t.betList).then(i, s)
                        }
                    })) : (T(a, 2),
                    s.title = n("translate")("Common.LabelPriceChanges"),
                    s.buttons.push({
                        text: n("translate")("Common.LabelBetCancel"),
                        onTap: function () {
                            angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                        }
                    }),
                    s.buttons.push({
                        text: "<b>" + n("translate")("Common.LabelBetContinue") + "</b>",
                        type: "button-positive",
                        onTap: function () {
                            var i = function (i) {
                                    l.hideLoading(),
                                        0 === i.ErrorCode ? (t.current.money = "",
                                            h.clear(),
                                            l.tips('<div class="tipDialog">' + n("translate")("Common.LabelBetSuccess") + "</div>")) : 2 === i.ErrorCode ? (l.tips('<div class="tipDialog">' + n("translate")("Common.TipsClosed") + "</div>"),
                                            t.current.money = "",
                                            h.clear()) : 3 === i.ErrorCode || 4 === i.ErrorCode || 5 === i.ErrorCode || 6 === i.ErrorCode ? l.tips('<div class="tipDialog">' + i.ErrorMsg + "<div>") : 9 === i.ErrorCode ? (t.lines[t.lotteryPanId] = i.Data.Lines,
                                            y.lineUpdate = !1,
                                            Q(),
                                            W(a, 2)) : l.tips('<div class="tipDialog">' + n("translate")("Common.LabelBetError") + "</div>"),
                                        angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                                }
                                , s = function () {
                                    angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                                }
                                ;
                            l.loading(1e6, "<ion-spinner ></ion-spinner>"),
                                o.bet(t.betList).then(i, s)
                        }
                    })),
                t.betList.MBetParameters.length > 0 ? L = c.show(s) : (l.tips(10 == t.lotteryPanId ? '<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelLeastChooseOption", t.combNumEnd) + "</div>" : 42 == t.lotteryPanId ? '<div class="tipDialog">' + n("translate")("HongKongCai.LabelAllowChooseThan_6") + "</div>" : '<div class="tipDialog">' + n("translate")("Common.LabelPleaseEnterBets") + "</div>"),
                    angular.element(document.querySelector("#betbt")).removeAttr("disabled"))
        }
            , E = !1;
        t.getBankData = function () {
            function a(a) {
                0 == a.ErrorCode ? (t.bankData = parseFloat(a.Data.Balance).toFixed(2),
                    t.loaded = !0,
                a.Data.Balance < 1 && !E && (E = !0,
                    l.alertCancelConfirm(n("translate")("Common.TipsBalanceNotEnough"), function () {
                        }
                        , function () {
                            b.go("transfer")
                        }
                    ))) : (t.bankData = "0.00",
                    t.loaded = !0)
            }

            t.loaded = !1;
            var i = function () {
                    t.bankData = "0.00",
                        t.loaded = !0,
                        t.notCountMoney = -1
                }
                ;
            m.getMemberBalance().then(a, i)
        }
            ,
            t.getBankData(),
            w = e(r, 1e3),
            h.hasActiveScope = function () {
                return a.isActiveScope(t)
            }
            ,
            h.submit = function (a) {
                t.current.count * parseInt(a) > t.current.memberBalance ? (l.alertCancelConfirm(n("translate")("Common.TipsBalanceNotEnough"), function () {
                    }
                    , function () {
                        b.go("transfer")
                    }
                ),
                    angular.element(document.querySelector("#betbt")).removeAttr("disabled")) : W(a, 1)
            }
            ,
            h.destroy = function () {
                C = !0,
                    e.cancel(w),
                    h = null ,
                    t.baseInfo = null ,
                    t.lines = [],
                    t.betList = null
            }
            ,
            h.clear = function () {
                t.current.count = 0,
                    X();
                var a = angular.element(document.querySelectorAll(".lottery-bet .bet"));
                t.betList.MBetParameters = [],
                    t.betList.CombNum = 1,
                    t.isForNumberbetList.BetItems = [],
                    angular.forEach(a, function (t) {
                            angular.element(t).removeClass("bet-choose")
                        }
                    )
            }
    }
    ]),
    angular.module("controllers.cqKuaiLeShiFen", []).controller("CqKuaiLeShiFenCtrl", ["$scope", "$ionicHistory", "LotteryInfoService", "$timeout", "$interval", "utils", "$filter", "QuickDialog", "LotteryBetService", "$ionicPopup", "$cordovaLocalStorage", "$state", "AccountService", function (t, a, i, s, e, d, n, l, o, c, v, b, m) {
    function r() {
        t.baseInfo.CloseCount > 0 ? t.baseInfo.CloseTime = d.secondsFormat(t.baseInfo.CloseCount, 1) : (t.baseInfo.CloseTime = n("translate")("Common.TipsClosed"),
            angular.element(document.querySelector("#betbt")).removeAttr("disabled")),
            t.baseInfo.OpenCount > 0 ? (t.baseInfo.OpenTime = d.secondsFormat(t.baseInfo.OpenCount, 1),
                t.baseInfo.OpenCount--) : (t.baseInfo.OpenTime = n("translate")("Common.LabelOpenResulting"),
                angular.element(document.querySelector("#betbt")).removeAttr("disabled"));
        var a = angular.element(document.querySelector(".bet-view"))
            , i = angular.element(document.querySelector(".bar-footer .close-pan"));
        if (t.baseInfo.CloseCount <= 0) {
            if (0 == i.length && (i.remove(),
                    t.baseInfo.PreResult)) {
                a.addClass("opacityBetView");
                var s = angular.element(document.querySelector(".bar-footer"))
                    , e = angular.element('<div class="close-pan"><div class="tip">' + n("translate")("Common.TipsClosed") + "</div></div>");
                s.append(e)
            }
            angular.element(document.querySelector("#j-money")).attr({
                disabled: "disabled"
            }),
                y.infoUpdate = !1,
                A(),
                X(),
            L && (L.close(),
                L = null ),
                t.current.count = 0,
                t.betList.MBetParameters = [],
                t.isForNumberbetList.BetItems = [],
                t.lines = []
        } else
            i.remove(),
                a.removeClass("opacityBetView"),
                angular.element(document.querySelector("#j-money")).removeAttr("disabled"),
                Q(),
                t.baseInfo.CloseCount--
    }

    t.sxList = [];
    var g = {
        BASE_INFO: "BASE_INFO"
    }
        , p = v.getItem(g.BASE_INFO) || [];
    if ("string" == typeof p) {
        p = JSON.parse(p);
        var u = [];
        angular.forEach(p.SxList, function (t, a) {
                u[a] = t.join(",")
            }
        ),
            t.sxList = u
    }
    var h = this
        , C = !1;
    t.lotteryPanId = 0,
        t.isForNumber = !1,
        t.playTypeId = 0,
        t.combNum = 0,
        t.combNumEnd = 0;
    var y = {
        lineUpdate: !1
    }
        , L = null
        , _ = {
        LotteryId: 11,
        Lotterypan: t.lotteryPanId
    };
    t.baseInfo = {
        CurrentPeriod: null,
        PrePeriodNumber: null,
        CloseCount: 0,
        CloseTime: "00:00",
        OpenCount: 0,
        OpenTime: "00:00",
        PreResult: null,
        SxResult: null,
        ShowResult: null
    },
        t.lines = [];
    var w = null;
    t.betList = {
        LotteryId: 11,
        CombNum:1,
        LotteryPan: t.lotteryPanId,
        MBetParameters: []
    },
        t.isForNumberbetList = {
            LotteryId: 11,
            LotteryPan: t.lotteryPanId,
            BetItems: []
        },
        t.subNavInit = function (a) {
            A(),
                h.clear(),
                10 == t.lotteryPanId ? (0 == a ? (t.combNum = 2,
                    t.combNumEnd = 6) : 1 == a ? (t.combNum = 2,
                    t.combNumEnd = 6) : 2 == a ? (t.combNum = 3,
                    t.combNumEnd = 6) : 3 == a ? (t.combNum = 4,
                    t.combNumEnd = 6) : 4 == a && (t.combNum = 5,
                    t.combNumEnd = 6),
                    t.isForNumber = !0,
                    P()) : 15 == t.lotteryPanId ? (t.combNum = 4,
                    t.combNumEnd = 5,
                    t.isForNumber = !0,
                    P()) : t.isForNumber = !1,
                t.isForNumberbetList.LotteryPan = t.lotteryPanId,
                t.betList.LotteryPan = t.lotteryPanId,
                f()
        }
        ,
        t.menuInit = function (a) {
            var i = [1, 2, 3, 4, 5, 6, 7, 8,9,10];
            //I(),
            y.lineUpdate = !1,
                A(),
                h.clear(),
                M(a),
                t.isForNumber = !1,
                t.lotteryPanId = i[a],
                _.Lotterypan = t.lotteryPanId,
                1 == t.lotteryPanId || 2 == t.lotteryPanId || 3 == t.lotteryPanId || 4 == t.lotteryPanId || 5 == t.lotteryPanId || 6 == t.lotteryPanId || 7 == t.lotteryPanId || 8 == t.lotteryPanId || 9 == t.lotteryPanId || 10 == t.lotteryPanId ? (t.isForNumber = !1,
                    I()) : 15 == t.lotteryPanId ? (t.combNum = 4,
                    t.combNumEnd = 5,
                    t.isForNumber = !0,
                    P()) : t.isForNumber = !1,
                t.isForNumberbetList.LotteryPan = t.lotteryPanId,
                t.betList.LotteryPan = t.lotteryPanId,
                //t.subNavInit(0)
                //f()
                window.setTimeout(f(), 1e3)
        }
    ;
    var H = null
        , f = function () {
            var a = function (t) {
                    401 == t.status || 901 == t.status || C || (null != H && s.cancel(H),
                        H = s(function () {
                                f()
                            }
                            , 2e4))
                }
                , e = function (a) {
                    0 === a.ErrorCode && (t.lines[t.lotteryPanId] = a.Data.Lines,
                    t.baseInfo && (t.baseInfo.CurrentPeriod = a.Data.CurrentPeriod,
                        t.baseInfo.PrePeriodNumber = a.Data.PrePeriodNumber,
                        t.baseInfo.CloseCount = a.Data.CloseCount,
                        t.baseInfo.PreResult = a.Data.PreResult,
                        t.baseInfo.SxResult = a.Data.SxResult,
                        t.baseInfo.ShowResult = a.Data.PreResult,
                        t.baseInfo.OpenCount = a.Data.OpenCount),
                    C || (null != H && (s.cancel(H),
                        C = !1),
                        H = s(function () {
                                f()
                            }
                            , 1e4)))
                }
                ;
            y.lineUpdate = !1,
                i.getHFLines(_).then(e, a)
            //e.getHFLines(h).then(i, a)
        }
        ;
    t.loop = function (a) {
        t.baseInfo.ShowResult = a != t.baseInfo.SxResult ? t.baseInfo.SxResult : t.baseInfo.PreResult
    }
    ;
    var Z = function (a) {
        for (var i = 0; i < t.betList.MBetParameters.length; i++)
            if (t.betList.MBetParameters[i].Id == a)
                return i;
        return -1
    }
        , N = function (a) {
        for (var i = 0; i < t.isForNumberbetList.BetItems.length; i++)
            if (t.isForNumberbetList.BetItems[i].numberKey == a)
                return i;
        return -1
    }
        , D = function (a) {
        var i = Z(a);
        -1 != i && (t.betList.MBetParameters.splice(i, 1),
            t.current.count--,
            x())
    }
        , S = function (a) {
        var i = N(a);
        -1 != i && t.isForNumberbetList.BetItems.splice(i, 1);
        var s = angular.element(document.querySelectorAll("[data-id='" + a + "']"))
            , e = s.parent().parent().attr("data-title"),lines=""
            , n = s.parent().parent().attr("data-playId");
        if (t.betList.MBetParameters = [],
                t.current.count = d.getCombinationNum(t.isForNumberbetList.BetItems, t.combNum).length,
            t.current.count > 0) {
            for (var l = "", o = "", c = t.isForNumberbetList.BetItems.length, v = 0; c > v; v++)
                l += t.isForNumberbetList.BetItems[v].BetContext + ",",
                    lines = t.isForNumberbetList.BetItems[v].Lines ,
                    o += t.isForNumberbetList.BetItems[v].BetContext + ",";
            l = l.substr(0, l.length - 1),
                o = o.substr(0, o.length - 1);
            var oo = angular.element(document.querySelector(".betLine")).attr("data-id");
            oo > 0 ? lines = t.lines[t.lotteryPanId][oo]: "";
            var b = {
                DisplayText: o,
                BetContext: l,
                IsForNumber: !0,
                BetType: 5,
                IsTeMa: 0,
                Money: 0,
                Lines: lines,
                Id: n,
                Pel: e,
                Change: !1
            };

            t.betList.MBetParameters.push(b),
                x()
        } else
            X()
    }
        , B = function (a) {
        var i = Z(a);
        if (-1 == i) {
            var s = angular.element(document.querySelectorAll("[data-id='" + a + "']"))
                , e = s.parent().parent().attr("data-title")
                , d = angular.element(document.querySelectorAll("[data-id='" + a + "'] .bet-content")).text()
                , n = 1;
            21 != t.lotteryPanId && (n = 0);
            var l = {
                Id: a,
                Lines: t.lines[t.lotteryPanId][a],
                Money: 0,
                BetContext: d,
                IsForNumber: !1,
                BetType: 1,
                IsTeMa: n,
                Pel: e,
                Change: !1
            };
            if (10 == t.lotteryPanId){
                var o = angular.element(document.querySelectorAll(".betLine")),id="",c="";
                angular.forEach(o,function(a){
                    var s = angular.element(a);
                    id = s.attr("data-id"),
                        c +=  s.attr("data-txt") + ":" + t.lines[t.lotteryPanId][id] + ","
                })
                l.Lines = t.lines[t.lotteryPanId][id],
                    l.BetType = 1,
                    l.Txt = c,//"二全中:61;二全中:61;",
                    l.IsForNumber = !1,
                    t.betList.CombNum = t.combNum
            }
            t.betList.MBetParameters.push(l),
                t.current.count++,
                x()
        }
    }
        , K = function (a) {
        var i = angular.element(document.querySelectorAll("[data-id='" + a + "']"))
            , s = i.parent().parent().attr("data-title")
            , e = i.parent().parent().attr("data-playId")
            , n = angular.element(document.querySelectorAll("[data-id='" + a + "'] .bet-content")).text()
            , l = {
            numberKey: a,
            Lines: t.lines[t.lotteryPanId][a],
            BetContext: n,
            Pel: s
        };
        if (10 == t.lotteryPanId) {
            var o = angular.element(document.querySelector(".betLine")).attr("data-id");
            l.Lines = t.lines[t.lotteryPanId][o]
        }
        if (t.betList.MBetParameters = [],
                t.isForNumberbetList.BetItems.push(l),
                t.current.count = d.getCombinationNum(t.isForNumberbetList.BetItems, t.combNum).length,
            t.current.count > 0) {
            var c = ""
                , v = ""
                , b = t.isForNumberbetList.BetItems.length;
            if (1 == t.lotteryPanId || 2 == t.lotteryPanId || 3 == t.lotteryPanId || 4 == t.lotteryPanId || 5 == t.lotteryPanId)
                for (var m = 0; b > m; m++)
                    c += t.isForNumberbetList.BetItems[m].numberKey + ",",
                        v += t.isForNumberbetList.BetItems[m].BetContext + ",";
            else
                for (var r = 0; b > r; r++)
                    c += t.isForNumberbetList.BetItems[r].BetContext + "," ,
                        v += t.isForNumberbetList.BetItems[r].BetContext + ",";
            c = c.substr(0, c.length - 1),
                v = v.substr(0, v.length - 1);
            var g = {
                DisplayText: v,
                BetContext: c,
                IsForNumber: !0,
                BetType: 5,
                IsTeMa: 0,
                Money: 0,
                Lines: 0,
                Id: e,
                Pel: s,
                Change: !1
            };
            if (10 == t.lotteryPanId)  {
                var o = angular.element(document.querySelectorAll(".betLine")),id="",c="";
                angular.forEach(o,function(a){
                    var s = angular.element(a);
                    id = s.attr("data-id"),
                        c +=  s.attr("data-txt") + ":" + t.lines[t.lotteryPanId][id] + ","
                })
                    g.Lines = t.lines[t.lotteryPanId][id],
                    g.Id = id,
                    g.BetType = 1,
                    g.Txt = c,//"二全中:61;二全中:61;",
                    g.IsForNumber = !1,
                    t.betList.CombNum = t.combNum
            }
            t.betList.MBetParameters.push(g),
                x()
        } else
            X()
    }
        , T = function (a, i) {
        for (var s = 0; s < t.betList.MBetParameters.length; s++)
            if (t.betList.MBetParameters[s].Money = parseInt(a),
                1 != i)
                if (t.isForNumber) {
                    for (var e = t.betList.MBetParameters[s], d = e.BetContext.split(","), n = [], l = 0; l < d.length; l++) {
                        var o = d[l].split("@");
                        o[1] != t.lines[t.lotteryPanId][o[0]] && (o[1] = t.lines[t.lotteryPanId][o[0]]),
                            n.push(o.join("@"))
                    }
                    t.betList.MBetParameters[s].BetContext = n.join(","),
                        t.betList.MBetParameters[s].Change = !0
                } else
                    t.betList.MBetParameters[s].Lines != t.lines[t.lotteryPanId][t.betList.MBetParameters[s].Id] && (t.betList.MBetParameters[s].Lines = t.lines[t.lotteryPanId][t.betList.MBetParameters[s].Id],
                        t.betList.MBetParameters[s].Change = !0)
    }
        , x = function () {
        var t = angular.element(document.querySelectorAll(".cur-menus .nav .active .smallround"))
            , a = document.querySelectorAll(".lottery-bet .bet-choose");
        a.length > 0 ? t.addClass("menus-choose") : t.removeClass("menus-choose")
    }
        , X = function () {
        var t = angular.element(document.querySelectorAll(".cur-menus .nav  .smallround"));
        angular.forEach(t, function (t) {
                var a = angular.element(t);
                a.removeClass("menus-choose")
            }
        )
    }
        , M = function (a) {
        for (var i = !1, s = document.querySelectorAll(".tab-content")[0], e = 0; e < t.betList.MBetParameters.length; e++) {
            var d = angular.element(s.querySelectorAll("[data-id='" + t.betList.MBetParameters[e].Id + "']"));
            d.length > 0 && (d.addClass("bet-choose"),
                i = !0)
        }
        var n = angular.element(document.querySelectorAll(".cur-menus .nav .smallround"))
            , l = 0;
        angular.forEach(n, function (t) {
                if (a == l) {
                    var s = angular.element(t);
                    i ? s.addClass("menus-choose") : s.removeClass("menus-choose")
                }
                l++
            }
        )
    }
        , I = function () {
        var a = angular.element(document.querySelectorAll(".lottery-bet .bet"));
        angular.forEach(a, function (a) {
                var i = angular.element(a)
                    , s = angular.element(a).attr("data-id");
                i.bind("click", function () {
                        t.baseInfo.CloseCount > 0 && null != t.lines[t.lotteryPanId] && (i.hasClass("bet-choose") ? (i.removeClass("bet-choose"),
                            D(s)) : (i.addClass("bet-choose"),
                            B(s)))
                    }
                )
            }
        )
    }
        , P = function () {
        var a = angular.element(document.querySelectorAll(".lottery-bet .bet"));
        angular.forEach(a, function (a) {
                var i = angular.element(a)
                    , s = angular.element(a).attr("data-id");
                i.bind("click", function () {
                        if (t.baseInfo.CloseCount > 0 && null != t.lines[t.lotteryPanId])
                            if (i.hasClass("bet-choose"))
                                i.removeClass("bet-choose"),
                                    S(s);
                            else {
                                if (10 == t.lotteryPanId) {
                                    if (t.isForNumberbetList.BetItems.length >= 6)
                                        return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_6") + "</div>")
                                } else if (42 == t.lotteryPanId) {
                                    if (6 == t.isForNumberbetList.BetItems.length)
                                        return void l.tips('<div class="tipDialog">' + n("translate")("HongKongCai.LabelAllowChooseThan_6") + "</div>")
                                } else if (43 == t.lotteryPanId && t.isForNumberbetList.BetItems.length >= 8)
                                    return void l.tips('<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelNotAllowMoreThan_8") + "</div>");
                                i.addClass("bet-choose"),
                                    K(s)
                            }
                    }
                )
            }
        )
    }
        , A = function () {
        var t = angular.element(document.querySelectorAll(".lottery-bet .tab-content .scroll-content"));
        t.addClass("close");
        var a = angular.element(document.querySelector(".bet-view"));
        a.addClass("opacityBetView");
        var i = angular.element(document.querySelectorAll(".lottery-bet .bet"));
        angular.element(document.querySelector(".betLine .line")).text("-"),
            angular.forEach(i, function (t) {
                    angular.element(t).removeClass("bet-choose");
                    var a = angular.element(t).attr("data-id");
                    angular.element(document.querySelectorAll("[data-id='" + a + "'] .bet-item")).text("-"),
                        angular.element(document.querySelectorAll("[data-id='" + a + "']")).removeClass("ok")
                }
            )
    }
        , Q = function () {
        if (null != t.lines[t.lotteryPanId] && !y.lineUpdate) {
            if (10 == t.lotteryPanId) {
                var a = angular.element(document.querySelectorAll(".lottery-bet .tab-content .scroll-content"));
                a.removeClass("close");
                var i = angular.element(document.querySelectorAll(".lottery-bet .betLine"));
                angular.forEach(i, function (a) {
                        var i = angular.element(a).attr("data-id"),txt = angular.element(a).attr("data-txt")
                            , s = angular.element(document.querySelectorAll("[data-id='" + i + "'] .line"));
                        s.text(null != t.lines[t.lotteryPanId][i] ? txt + ":" + t.lines[t.lotteryPanId][i] : txt +  "：0"),
                            angular.element(document.querySelectorAll("[data-id='" + i + "']")).addClass("ok"),
                            angular.element(document.querySelectorAll(".col.bet")).addClass("ok")
                    }
                )
            } else {
                var a = angular.element(document.querySelectorAll(".lottery-bet .tab-content .scroll-content"));
                a.removeClass("close");
                var i = angular.element(document.querySelectorAll(".lottery-bet .bet"));
                angular.forEach(i, function (a) {
                        var i = angular.element(a).attr("data-id")
                            , s = angular.element(document.querySelectorAll("[data-id='" + i + "'] .bet-item"));
                        s.text(t.lines[t.lotteryPanId][i]),
                            angular.element(document.querySelectorAll("[data-id='" + i + "']")).addClass("ok")
                    }
                )
            }
            y.lineUpdate = !0
        }
    }
        , W = function (a, i) {
        var s = {}
            , e = "";
        e = t.isForNumber ? "<ion-scroll direction=\"y\"><div ng-repeat=\"item in betList.MBetParameters\"><span>{{item.Pel}}【{{item.DisplayText}}】</span><br /><span>{{'HongKongCai.LabelNumberOfCombinations'|translate}}：{{current.count}}</span><br /><span>{{'HongKongCai.LabelOneBetMoney'|translate}}：</span><span>{{item.Money.toFixed(2)}}</span><br /><span>{{'HongKongCai.LabelTotalAmount'|translate}}：{{(current.count * item.Money).toFixed(2)}}</span></div></ion-scroll>" : '<ion-scroll direction="y"><div ng-repeat="item in betList.MBetParameters"><span>{{item.Pel}}【{{item.BetContext}}】</span><span> @<span ng-class="{red:item.Change}">{{item.Lines}}</span> X</span><span > {{item.Money.toFixed(2)}}</span></div></ion-scroll>',
            s = {
                cssClass: "lotter-popup",
                template: e,
                title: n("translate")("Common.LabelBetListing"),
                scope: t,
                buttons: []
            },
            1 == i ? (T(a, 1),
                s.buttons.push({
                    text: n("translate")("Common.ButtonCancel"),
                    onTap: function () {
                        angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                    }
                }),
                s.buttons.push({
                    text: "<b>" + n("translate")("LotteryManagent.ButtonSave") + "</b>",
                    type: "button-positive",
                    onTap: function () {
                        var i = function (i) {
                                l.hideLoading(),
                                    0 === i.ErrorCode ? (t.current.money = "",
                                        h.clear(),
                                        l.tips('<div class="tipDialog">' + n("translate")("Common.LabelBetSuccess") + "</div>")) : 2 === i.ErrorCode ? (l.tips('<div class="tipDialog">' + i.ErrorMsg + "</div>"),
                                        t.current.money = "",
                                        h.clear()) : 3 === i.ErrorCode || 4 === i.ErrorCode || 5 === i.ErrorCode || 6 === i.ErrorCode ? l.tips('<div class="tipDialog">' + i.ErrorMsg + "<div>") : 9 === i.ErrorCode ? (t.lines[t.lotteryPanId] = i.Data.Lines,
                                        y.lineUpdate = !1,
                                        Q(),
                                        W(a, 2)) : l.tips('<div class="tipDialog">' + n("translate")("Common.LabelBetError") + "</div>"),
                                    angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                            }
                            , s = function () {
                                angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                            }
                            ;
                        l.loading(1e6, "<ion-spinner></ion-spinner>"),
                            o.bet(t.betList).then(i, s)
                    }
                })) : (T(a, 2),
                s.title = n("translate")("Common.LabelPriceChanges"),
                s.buttons.push({
                    text: n("translate")("Common.LabelBetCancel"),
                    onTap: function () {
                        angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                    }
                }),
                s.buttons.push({
                    text: "<b>" + n("translate")("Common.LabelBetContinue") + "</b>",
                    type: "button-positive",
                    onTap: function () {
                        var i = function (i) {
                                l.hideLoading(),
                                    0 === i.ErrorCode ? (t.current.money = "",
                                        h.clear(),
                                        l.tips('<div class="tipDialog">' + n("translate")("Common.LabelBetSuccess") + "</div>")) : 2 === i.ErrorCode ? (l.tips('<div class="tipDialog">' + n("translate")("Common.TipsClosed") + "</div>"),
                                        t.current.money = "",
                                        h.clear()) : 3 === i.ErrorCode || 4 === i.ErrorCode || 5 === i.ErrorCode || 6 === i.ErrorCode ? l.tips('<div class="tipDialog">' + i.ErrorMsg + "<div>") : 9 === i.ErrorCode ? (t.lines[t.lotteryPanId] = i.Data.Lines,
                                        y.lineUpdate = !1,
                                        Q(),
                                        W(a, 2)) : l.tips('<div class="tipDialog">' + n("translate")("Common.LabelBetError") + "</div>"),
                                    angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                            }
                            , s = function () {
                                angular.element(document.querySelector("#betbt")).removeAttr("disabled")
                            }
                            ;
                        l.loading(1e6, "<ion-spinner ></ion-spinner>"),
                            o.bet(t.betList).then(i, s)
                    }
                })),
            t.betList.MBetParameters.length > 0 ? L = c.show(s) : (l.tips(10 == t.lotteryPanId ? '<div class="tipDialog">' + n("formas")(t.combNum, "HongKongCai.LabelLeastChooseOption", t.combNumEnd) + "</div>" : 42 == t.lotteryPanId ? '<div class="tipDialog">' + n("translate")("HongKongCai.LabelAllowChooseThan_6") + "</div>" : '<div class="tipDialog">' + n("translate")("Common.LabelPleaseEnterBets") + "</div>"),
                angular.element(document.querySelector("#betbt")).removeAttr("disabled"))
    }
        , E = !1;
    t.getBankData = function () {
        function a(a) {
            0 == a.ErrorCode ? (t.bankData = parseFloat(a.Data.Balance).toFixed(2),
                t.loaded = !0,
            a.Data.Balance < 1 && !E && (E = !0,
                l.alertCancelConfirm(n("translate")("Common.TipsBalanceNotEnough"), function () {
                    }
                    , function () {
                        b.go("transfer")
                    }
                ))) : (t.bankData = "0.00",
                t.loaded = !0)
        }

        t.loaded = !1;
        var i = function () {
                t.bankData = "0.00",
                    t.loaded = !0,
                    t.notCountMoney = -1
            }
            ;
        m.getMemberBalance().then(a, i)
    }
        ,
        t.getBankData(),
        w = e(r, 1e3),
        h.hasActiveScope = function () {
            return a.isActiveScope(t)
        }
        ,
        h.submit = function (a) {
            t.current.count * parseInt(a) > t.current.memberBalance ? (l.alertCancelConfirm(n("translate")("Common.TipsBalanceNotEnough"), function () {

                }
                , function () {
                    b.go("transfer")
                }
            ),
                angular.element(document.querySelector("#betbt")).removeAttr("disabled")) : W(a, 1)
        }
        ,
        h.destroy = function () {
            C = !0,
                e.cancel(w),
                h = null ,
                t.baseInfo = null ,
                t.lines = [],
                t.betList = null
        }
        ,
        h.clear = function () {
            t.current.count = 0,
                X();
            var a = angular.element(document.querySelectorAll(".lottery-bet .bet"));
            t.betList.MBetParameters = [],
                t.betList.CombNum = 1,
                t.isForNumberbetList.BetItems = [],
                angular.forEach(a, function (t) {
                        angular.element(t).removeClass("bet-choose")
                    }
                )
        }
}
]),
    angular.module("controllers.index", []).controller("IndexCtrl", ["$filter", "$scope", "$ionicPopover", "$state", "AuthService", "AccountService", "NoticeService", "QuickDialog", "LotteryInfoService", "$ionicScrollDelegate", "utils", "$interval", "$timeout", "$cordovaLocalStorage", function (t, a, i, s, e, d, n, l, o, c, v, b, m) {
        a.hasList = !0;
        var goi = function (t) {
            return -1 != t.indexOf(".") ? window.location.protocol + "//m." + t.substring(t.indexOf(".") + 1) : window.location.href
        },
          r = null
            , g = !1
            , p = !1;
        i.fromTemplateUrl("my-popover.html", {
            scope: a
        }).then(function (t) {
                a.popover = t
            }
        ),
            a.openPopover = function (t) {
                u(),
                    a.popover.show(t)
            }
            ,
            a.closePopover = function () {
                a.popover.hide()
            }
            ,
            a.selectMenu = function (t) {
                "count" == t  ? window.location = goi(window.location.host)+"/#/day/1/0" :
                    (a.popover.hide(),
                    s.go(t, {
                        rid: 0
                    }))
            }
            ,
            a.testData = {
                show: !0,
                height: 160
            },
            a.lotteryList = [],
            a.tempLotteryList = [],
            a.bankData = null ,
            a.notices = [],
            a.username = e.currentUser.MemberName,
            a.loaded = !1,
            a.notCountMoney = 0,
            a.CountMoneyState = 0,
        1 == e.currentUser.TestState && (a.testData.height = 125,
            a.testData.show = !1,
            a.username = t("translate")("Common.TestUser"));
        var u = function () {
                a.CountMoneyState = -2;
                var t = function (t) {
                        0 == t.ErrorCode ? (a.CountMoneyState = 1,
                            a.notCountMoney = parseFloat(t.Data.BetMoney).toFixed(2)) : a.CountMoneyState = -1
                    }
                    , i = function () {
                        a.CountMoneyState = -1
                    }
                    ;
                d.notCountBetMoney().then(t, i)
            }
            , h = function () {
                function t(t) {
                    a.tempLotteryList = [],
                    0 == t.ErrorCode && (angular.forEach(t.Data.LotteryList, function (t) {
                            a.tempLotteryList.push({
                                id: t.LotteryID,
                                name: t.LotteryName,
                                state: t.State,
                                openCount: t.OpenCount
                            })
                        }
                    ),
                        a.lotteryList = a.tempLotteryList,
                    g || m(h, 1e4),
                        y(),
                        a.hasList = !1)
                }

                o.getLotteryList().then(t)
            }
            , C = function () {
                function t(t) {
                    0 == t.ErrorCode && angular.forEach(t.Data, function (t) {
                            a.notices.push({
                                content: t.Content
                            })
                        }
                    )
                }

                n.getNotice().then(t)
            }
            ;
        a.getBankData1 = function () {
            function i(i) {
				//t, a, i, s, e, d, n, l, o, c, v, b, m
                0 == i.ErrorCode ? (a.bankData = parseFloat(i.Data.Balance).toFixed(2),
                    a.loaded = !0,
                i.Data.Balance < 1 && !p && (p = !0,
                    l.alertCancelConfirm(t("translate")("Common.TipsBalanceNotEnough"), function () {
                        }
                        , function () {
                            s.go("transfer")
                        }
                    ))) : ((l.alertCancelConfirm(t("translate")(i.ErrorMsg),function(){},function(){s.go("login")})),a.bankData = "请登录",
                    a.loaded = !0)
            }

            a.loaded = !1;
            var e = function () {
                    a.bankData = "0.00",
                        a.loaded = !0,
                        a.notCountMoney = -1
                }
                ;
            d.getMemberBalance().then(i, e)
        },
		        a.getBankData = function () {
            function i(i) {
				//t, a, i, s, e, d, n, l, o, c, v, b, m
                0 == i.ErrorCode ? (a.bankData = parseFloat(i.Data.Balance).toFixed(2),
                    a.loaded = !0,
                i.Data.Balance < 1 && !p && (p = !0,
                    l.alertCancelConfirm(t("translate")("Common.TipsBalanceNotEnough"), function () {
                        }
                        , function () {
                            s.go("transfer")
                        }
                    ))) : (a.bankData = "请登录",
                    a.loaded = !0)
            }

            a.loaded = !1;
            var e = function () {
                    a.bankData = "0.00",
                        a.loaded = !0,
                        a.notCountMoney = -1
                }
                ;
            d.getMemberBalance().then(i, e)
        }
            ,
            a.inGame = function (tt) {
                var wh =false;
                angular.forEach(a.lotteryList, function (a) {
                        if(a.id == tt && a.state == 0){
                            l.tips("<div class='tipDialog'>" + a.name+t("translate")("Common.LabelWHLottery") + "</div>"),wh = true;
                            return;
                        }
                    }
                )
                if(wh) return;
                o.setInGame(tt),
                    s.go("lottery")
            }
        ;
        var y = function () {
                angular.forEach(a.lotteryList, function (i, s) {
                    if(i.state == 0){
                        a.lotteryList[s].openCountTime = t("translate")("Common.LabelWHLottery")
                    }else{
                        1 == i.id ? isNaN(i.openCount) ? a.lotteryList[s].openCountTime = i.openCount : (a.lotteryList[s].openCountTime = v.secondsFormat(i.openCount, 2),
                        a.lotteryList[s].openCount > 0 && a.lotteryList[s].openCount--) : i.openCount > 0 ? (a.lotteryList[s].openCountTime = v.secondsFormat(i.openCount, 2),
                            a.lotteryList[s].openCount--) : a.lotteryList[s].openCountTime = t("translate")("Common.LabelIsTheLottery")
                    }
                    }
                )
            }
            ;
        a.$on("$destroy", function () {
                g = !0,
                    b.cancel(r),
                    a.lotteryList = []
            }
        ),
            a.uri = function () {
                window.location = L(window.location.host)
            }
        ;
        var L = function (t) {
                //return document.getElementById("APIURL").value; //"http://wapindexci.com/";
                return -1 != t.indexOf(".") ? window.location.protocol + "//m." + t.substring(t.indexOf(".") + 1) : window.location.href
            }
            ;
        u(),
            C(),
            a.getBankData(),
            h(),
            r = b(y, 1e3)
    }
    ]),
    angular.module("controllers.transfer", []).controller("TransferCtrl", ["$scope", "$state", "CashService", "$filter", "QuickDialog", "AccountService", "$timeout", "AuthService", function (t, a, i, s, e, d, n, l) {
        var v = function (t) {
            return -1 != t.indexOf(".") ? window.location.protocol + "//m." + t.substring(t.indexOf(".") + 1) : window.location.href
        }
        window.location = v(window.location.host) + "/#/deposit/0";
        return
        function o() {
            t.bankData = null ,
                t.loaded = !1,
                t.amountList = [s("translate")("Common.LabelAll"), "100", "500", "1000", "5000", "10000"],
                t.transerModel.actualMoney = "",
                t.transerModel.moneyIndex = 1,
                t.getBankData = function () {
                    function a(a) {
                        if (0 == a.ErrorCode) {
                            t.bankData = a.Data.Balance.toFixed(2),
                                t.loaded = !0;
                            var i = angular.element(document.querySelectorAll(".item.ng-binding "));
                            angular.forEach(i, function (a) {
                                    var i = angular.element(a);
                                    isNaN(i.text()) || parseInt(t.bankData) < parseInt(i.text()).toFixed(2) && i.attr({
                                        disabled: "disabled"
                                    }).addClass("item-disabled")
                                }
                            )
                        } else {
                            t.bankData = 0,
                                t.loaded = !0;
                            var i = angular.element(document.querySelectorAll(".item.ng-binding "));
                            angular.forEach(i, function (a) {
                                    var i = angular.element(a);
                                    isNaN(i.text()) || parseInt(t.bankData) < parseInt(i.text()).toFixed(2) && i.attr({
                                        disabled: "disabled"
                                    }).addClass("item-disabled")
                                }
                            )
                        }
                    }

                    t.loaded = !1;
                    d.geMaintBalance().then(a)
                }
                ,
                t.getBankData()
        }

        var c = a.params.rid;
        t.backUrl = null ,
            t.backUrl = "0" == c ? "#/index" : "#/lottery",
            t.transerModel = {
                actualMoney: ""
            },
            t.visibleButton = l.currentUser.TestState,
            o(),
            t.parseIntMoney = function (a) {
                a.target.value = parseInt(a.target.value),
                    t.transerModel.moneyIndex = 99,
                    "NaN" == a.target.value ? (a.target.value = "",
                        t.transerModel.actualMoney = "") : a.target.value.length > 8 && (a.target.value = "",
                        t.transerModel.actualMoney = ""),
                    0 == a.target.value.length ? (angular.element(document.querySelector("#but")).attr({
                        disabled: "disabled"
                    }),
                        t.transerModel.moneyClose = !1) : (angular.element(document.querySelector("#but")).removeAttr("disabled"),
                        t.transerModel.moneyClose = !0),
                    angular.element(document.querySelectorAll(".show-color")).removeClass("show-color")
            }
            ,
            t.close = function () {
                angular.element(document.querySelectorAll(".show-color")).removeClass("show-color"),
                    angular.element(document.querySelector("#but")).attr({
                        disabled: "disabled"
                    }),
                    t.transerModel.actualMoney = "",
                    t.transerModel.moneyClose = !1
            }
            ,
            t.chooseMoneyFun = function (a, i) {
                (0 == a || parseInt(t.amountList[a]) <= t.bankData) && (angular.element(document.querySelectorAll(".show-color")).removeClass("show-color"),
                    angular.element(i.srcElement).addClass("show-color"),
                    angular.element(document.querySelector("#but")).removeAttr("disabled"),
                    t.transerModel.actualMoney = parseInt(0 == a ? t.bankData : t.amountList[a]),
                    t.transerModel.moneyClose = !0,
                    t.transerModel.moneyIndex = a)
            }
            ,
            t.transferSubmit = function () {
                if ("" == t.transerModel.actualMoney || 0 == t.transerModel.actualMoney)
                    return void e.tips('<div class="tipDialog">' + s("translate")("Common.SelectOrPlaceHolderRTAMoney") + "</div>");
                if (angular.element(document.querySelector("#but")).attr({
                        disabled: "disabled"
                    }),
                        e.loading(1e6, '<div class="tipDialog">' + s("translate")("Common.IntransferOrPlease") + "</div>"),
                    parseInt(t.bankData) < parseInt(t.transerModel.actualMoney))
                    angular.element(document.querySelector("#but")).removeAttr("disabled"),
                        e.tips('<div class="tipDialog">' + s("translate")("Common.LabelTurnsMoneyInsufficient") + "</div>");
                else {
                    var d = {
                        Amount: t.transerModel.actualMoney
                    };
                    i.gameFundTransfer(d).then(function (i) {
                            e.hideLoading(),
                                0 == i.ErrorCode ? (t.transerModel.actualMoney = "",
                                    t.transerModel.moneyClose = !1,
                                    angular.element(document.querySelectorAll(".show-color")).removeClass("show-color"),
                                    e.tips('<div class="tipDialog">' + s("translate")("Common.OperationSuccessful") + "</div>"),
                                    n(function () {
                                            a.go("0" == c ? "index" : "lottery")
                                        }
                                        , 1500)) : 1 == i.ErrorCode ? (e.tips('<div class="tipDialog">' + s("translate")("Common.Intransfer") + "</div>"),
                                    angular.element(document.querySelector("#but")).removeAttr("disabled")) : 4 == i.ErrorCode ? (e.tips('<div class="tipDialog">' + s("translate")("Common.LabelTransferGreaterLimit") + "</div>"),
                                    angular.element(document.querySelector("#but")).removeAttr("disabled")) : (e.tips('<div class="tipDialog">' + s("translate")("Common.TransferFailed") + "</div>"),
                                    angular.element(document.querySelector("#but")).removeAttr("disabled"))
                        }
                        , function () {
                            e.hideLoading(),
                                e.tips('<div class="tipDialog">' + s("translate")("Common.TransferFailed") + "</div>"),
                                angular.element(document.querySelector("#but")).removeAttr("disabled")
                        }
                    )
                }
            }
            ,
            t.out = function () {
                a.go("index")
            }
            ,
            t.goUrl = function () {
                window.open(v(window.location.host) + "/#/deposit/0")
            }
        ;
    }
    ]), /*投注页面初始化*/
    angular.module("controllers.lottery", []).controller("LotteryCtrl", ["$window", "AuthService", "$scope", "$ionicPopover", "$stateParams", "$filter", "$compile", "LangService", "$ionicScrollDelegate", "$ionicPopup", "curLotteryDelegate", "$state", "QuickDialog", "AccountService", "$rootScope", "$timeout", "LotteryInfoService","AuthService", function (t, a, i, s, e, d, n, l, o, c, v, b, m, r, g, p, u,x) {
        var goi = function (t) {
                return -1 != t.indexOf(".") ? window.location.protocol + "//m." + t.substring(t.indexOf(".") + 1) : window.location.href
            };
        i.current = {
            gameId: 0,
            gameName: null,
            money: null,
            count: 0,
            memberBalance: 0,
            winLoss: 0,
            notCountMoney: 0,
            isDestroy: !1,
            CountMoneyState: 0,
            showCount: !0
        },
        1 == a.currentUser.TestState && (i.current.showCount = !1),
            s.fromTemplateUrl("my-popover.html", {
                scope: i
            }).then(function (t) {
                    i.popover = t
                }
            ),
            i.openPopover = function (t) {
                C(),
                    L(),
                    i.popover.show(t)
            }
            ,
            i.closePopover = function () {
                i.popover.hide()
            }
            ,
            i.selectMenu = function (t) {
				if(!x.isLoggedIn()){
					m.tips('<div class="tipDialog">' + d("translate")("Common.TipsPleaseCount") + "</div>");
					}else{
                "count" == t  ? window.location = goi(window.location.host)+"/#/day/1/0" :(
                i.popover.hide(),
                    b.go(t, {
                        rid: i.current.gameId
                    }))
				}
            }
        ;
        var h = u.lotteryList();
        h ? i.lotteryList = h.LotteryList : i.inGame(0),
            i.showRole = function () {
                i.popover.hide();
                var a = null;
                switch (i.current.gameId) {
                    case 1:
                        a = "hkc";
                        break;
                    case 2:
                        a = "fc_3d";
                        break;
                    case 3:
                        a = "pl_3";
                        break;
                    case 4:
                        a = "lhc";
                        break;
                    case 5:
                        a = "bj_8"
                        break;
                    case 6:
                        a = "bj_10";
                        break;
                    case 7:
                        a = "cq_ssc";
                        break;
                    case 8:
                        a = "tj_ssc";
                        break;
                    case 9:
                        a = "xj_ssc";
                        break;
                    case 10:
                        a = "jx_ssc";
                        break;
                    case 11:
                        a = "cq_ten";
                        break;
                    case 12:
                        a = "gd_ten";
                        break;
                    case 13:
                        a = "js_k3";
                        break;
                    case 14:
                        a = "jl_k3";
                        break;
                }
                null != a && (i.ruleHeight = t.innerHeight < 420 ? 100 : .5 * t.innerHeight,
                    c.show({
                        title: d("formas")(i.current.gameName, "Common.LabelGameRole"),
                        cssClass: "role-poput",
                        templateUrl: "/templates/common/rule/" + l.get() + "/" + a + ".html",
                        scope: i,
                        buttons: [{
                            text: "确定",
                            type: "button-positive"
                        }]
                    }))
            },
            i.$on("$destroy", function () {
                    i.current.isDestroy = !0,
                        v.destroy()
                }
            ),
            i.inGame = function (t) {
                var wh =false;
                angular.forEach(i.lotteryList, function (a) {
                        if(a.LotteryID == t && a.State == 0){
                            m.tips("<div class='tipDialog'>" + a.LotteryName+d("translate")("Common.LabelWHLottery") + "</div>"),wh = true;
                            return;
                        }
                    }
                )
                if(wh) return;
                var a = null;
                switch (t) {
                    case 4://六合彩
                        a = "<cur-liu-he-cai></cur-liu-he-cai>";
                        break;
                    case 2://福彩3D
                        a = "<cur-fu-cai-3d></cur-fu-cai-3d>";
                        break;
                    case 3://排列3
                        a = "<cur-pai-lie-san></cur-pai-lie-san>";
                        break;
                    case 5://北京快乐8
                        a = "<cur-bei-jing-kuail-le-ba></cur-bei-jing-kuail-le-ba>";
                        break;
                    case 6://北京赛车 pk10
                        a = "<cur-bei-jing-sai-che></cur-bei-jing-sai-che>";
                        break;
                    case 7://重庆时时彩
                        a = "<cur-chong-qing-shi-shi-cai></cur-chong-qing-shi-shi-cai>";
                        break;
                    case 8://天津时时彩
                        a = "<cur-tian-jing-shi-shi-cai></cur-tian-jing-shi-shi-cai>";
                        break;
                    case 9://新疆时时彩
                        a = "<cur-xin-jiang-shi-shi-cai></cur-xin-jiang-shi-shi-cai>";
                        break;
                    case 10://江西时时彩
                        a = "<cur-jiang-xi-shi-shi-cai></cur-jiang-xi-shi-shi-cai>";
                        break;
                    case 11://重庆快乐十分
                        a = "<cur-cq-kuai-le-shi-fen></cur-cq-kuai-le-shi-fen>";
                        break;
                    case 12://广东快乐十分
                        a = "<cur-gd-kuai-le-shi-fen></cur-gd-kuai-le-shi-fen>";
                        break;
                    case 13://江苏快3
                        a = "<cur-jiang-su-kuai-san></cur-jiang-su-kuai-san>"
                        break;
                    case 14://吉林快3
                        a = "<cur-ji-lin-kuai-san></cur-ji-lin-kuai-san>";
                        break;
                }
                if (!a)
                    return void b.go("index");
                u.setInGame(t),
                    angular.forEach(i.lotteryList, function (a) {
                            a.LotteryID == t && (i.current.gameId = t,
                                i.current.popoverHeight = 215,//319
                                i.current.gameName = a.LotteryName,
                                i.current.money = null ,
                                i.current.count = 0)
                        }
                    ),
                (1 == t || 10 == t) && (i.current.popoverHeight = 215),
                i.current.showCount || (i.current.popoverHeight = i.current.popoverHeight - 35),
                    v.destroy(),
                    o._instances = [],
                    v._instances = [];
                var s = n(a)(i)
                    , e = angular.element(document.querySelector(".lottery-content"));
                e.empty(),
                    e.append(s)
            };
        var C = function () {
                i.current.CountMoneyState = -2;
                var t = function (t) {
                        0 == t.ErrorCode ? (i.current.CountMoneyState = 0,
                            i.current.notCountMoney = t.Data.BetMoney.toFixed(2)) : i.current.CountMoneyState = -1
                    }
                    , a = function () {
                        i.current.CountMoneyState = -1
                    }
                    ;
                r.notCountBetMoney().then(t, a)
            }
            ;
        i.parseIntMoney = function (t) {
            t.target.value = parseInt(t.target.value),
                "NaN" == t.target.value ? (t.target.value = "",
                    i.current.money = "") : t.target.value.length > 6 && (t.target.value = "",
                    i.current.money = "")
        }
        ;
        var y = function () {
                var t = function (t) {
                        if (401 == t.status || 901 == t.status) {
                            if (401 == t.status)
                                return g.$emit("event:auth-loginRequired");
                            switch (t.error.ErrorCode) {
                                case 102:
                                    return g.$emit("event:sys-maintenance")
                            }
                        } else
                            i.current.isDestroy || p(function () {
                                    y()
                                }
                                , 2e4)
                    }
                    , a = function (t) {
                        if(1 == t.ErrorCode){
                            return g.$emit("event:auth-loginRequired");
                        }
						if(!x.isLoggedIn()){
							i.current.memberBalance = '请登录';
							}
                        0 == t.ErrorCode && (i.current.memberBalance = t.Data.Balance.toFixed(2),
                        i.current.isDestroy || p(function () {
                                y()
                            }
                            , 1e4))
                    }
                    ;
                r.getMemberBalance({
                    isTip: 0
                }).then(a, t)
            }
            ;
        y();
        var L = function () {
                i.current.winLoss = -2;
                var t = function () {
                        i.current.winLoss = -1
                    }
                    , a = function (t) {
                        i.current.winLoss = 0 == t.ErrorCode ? t.Data.WinLoss.toFixed(2) : -1
                    }
                    ;
                r.getTodayWinLossWithMember().then(a, t)
            }
            ;
        i.clickCount = function () {
            i.current.money = "",
                v.clear()
        }
            ,
            i.submit = function (t) {
				if(!x.isLoggedIn()){
						m.alertCancelConfirm(d("translate")("Common.TipsPleaseLogin"),function(){},function(){b.go("login")});
							}else{
                "disabled" == angular.element(document.querySelector("#j-money")).attr("disabled") || ("0" == i.current.money || "" == i.current.money || null == i.current.money || "NaN" == i.current.money ? m.tips('<div class="tipDialog">' + d("translate")("Common.LabelPleaseMoneyBets") + "</div>") : (angular.element(t.target).attr({
                    disabled: "disabled"
                }),v.submit(i.current.money)))
							}
            }
            ,
            i.inGame(parseInt(u.getInGame())),
            i.close = function () {
                i.current.money = "",
                    i.current.moneyClose = !1
            }
    }
    ]),
    angular.module("controllers.notcount", []).controller("NotCountCtrl", ["$scope", "$state", "$ionicHistory", "AccountService", "utils", function (t, a, i, s, e) {
        var d = a.params.rid;
        t.nocountData = [],
            t.ansycLoaded = !1,
            t.completeLoaded = !0,
            t.tip = {
                show: !1,
                index: 0
            },
            t.backUrl = "",
            t.backUrl = 0 == d ? "#/index" : "#/lottery",
            t.getNotCount = function () {
                if (t.completeLoaded) {
                    t.completeLoaded = !1,
                        t.nocountData = [];
                    var a = function (a) {
                            0 == a.ErrorCode ? (t.nocountData = a.Data.ReportList,
                                t.tip.show = !1) : t.tip = e.getTip(),
                                t.ansycLoaded = !0,
                                t.$broadcast("scroll.refreshComplete"),
                                t.completeLoaded = !0
                        }
                        , i = function (a) {
                            t.tip = e.getTip(1, a),
                                t.ansycLoaded = !0,
                                t.$broadcast("scroll.refreshComplete"),
                                t.completeLoaded = !0
                        }
                        ;
                    s.notCount(0).then(a, i)
                }
            }
            ,
            t.detail = function (t, i, s) {
                t > 0 && a.go("notcountDetail", {
                    rid: d,
                    lid: i,
                    name: encodeURIComponent(s)
                })
            }
            ,
            t.getNotCount()
    }
    ]).controller("NotCountDetailCtrl", ["$scope", "$state", "AccountService", "utils", function (t, a, i, s) {
        var e = a.params.rid
            , d = a.params.lid;
        t.rid = e;
        var n = decodeURIComponent(a.params.name);
        t.lotteryName = n,
            t.sum = {},
            t.detailData = [],
            t.ansycLoaded = !1,
            t.completeLoaded = !0,
            t.tip = {
                show: !1,
                index: 0
            },
            t.getNotCount = function () {
                if (t.completeLoaded) {
                    t.completeLoaded = !1,
                        t.detailData = [],
                        t.sum = {};
                    var a = function (a) {
                            0 == a.ErrorCode ? (t.detailData = a.Data.ReportList,
                                t.sum = a.Data.AllSum,
                                t.tip = s.getTip(2, t.detailData)) : t.tip = s.getTip(),
                                t.ansycLoaded = !0,
                                t.$broadcast("scroll.refreshComplete"),
                                t.completeLoaded = !0
                        }
                        , e = function (a) {
                            t.tip = s.getTip(1, a),
                                t.ansycLoaded = !0,
                                t.$broadcast("scroll.refreshComplete"),
                                t.completeLoaded = !0
                        }
                        ;
                    i.notCount(d).then(a, e)
                }
            }
            ,
            t.getNotCount()
    }
    ]),
    angular.module("controllers.count", []).controller("CountByWeekCtrl", ["AuthService", "$scope", "$filter", "$state", "utils", "AccountService", function (t, a, i, s, e, d) {
        if (1 == t.currentUser.TestState)
            return void s.go("index");
        var n = s.params.rid;
        a.ansycLoaded = !1,
            a.weekData = [],
            a.sum = {},
            a.backUrl = null ,
            a.backUrl = "0" == n ? "#/index" : "#/lottery",
            a.completeLoaded = !0,
            a.tip = {
                show: !1,
                index: 0
            },
            a.getCount = function () {
                if (a.completeLoaded) {
                    a.completeLoaded = !1;
                    var t = ""
                        , i = function (i) {
                            a.weekData = [],
                                0 == i.ErrorCode ? (angular.forEach(i.Data.CountList, function (i) {
                                        t = e.getWeek(i.Date),
                                            angular.extend(i, {
                                                WhatDay: t
                                            }),
                                            a.weekData.push(i)
                                    }
                                ),
                                    a.sum = i.Data.AllSum) : a.tip = e.getTip(),
                                a.ansycLoaded = !0,
                                a.$broadcast("scroll.refreshComplete"),
                                a.completeLoaded = !0
                        }
                        , s = function (t) {
                            a.tip = e.getTip(1, t),
                                a.ansycLoaded = !0,
                                a.$broadcast("scroll.refreshComplete"),
                                a.completeLoaded = !0
                        }
                        ;
                    d.getCount().then(i, s)
                }
            }
            ,
            a.goDayRpt = function (t) {
                t.Count > 0 && s.go("countByDay", {
                    rid: n,
                    day: t.Date
                })
            }
            ,
            a.getCount()
    }
    ]).controller("CountByDayCtrl", ["AuthService", "$scope", "$state", "utils", "AccountService", function (t, a, i, s, e) {
        if (1 == t.currentUser.TestState)
            return void i.go("index");
        var d = i.params.rid
            , n = i.params.day;
        a.ansycLoaded = !1,
            a.dateData = [],
            a.day = n,
            a.whatDay = s.getWeek(n),
            a.rid = d,
            a.completeLoaded = !0,
            a.tip = {
                show: !1,
                index: 0
            },
            a.getCountByDate = function () {
                if (a.completeLoaded) {
                    a.completeLoaded = !1;
                    var t = function (t) {
                            if (0 == t.ErrorCode) {
                                var i = t.Data.CountList;
                                a.tip = s.getTip(2, i),
                                    a.dateData = i
                            } else
                                a.tip = s.getTip();
                            a.ansycLoaded = !0,
                                a.$broadcast("scroll.refreshComplete"),
                                a.completeLoaded = !0
                        }
                        , i = function (t) {
                            a.tip = s.getTip(1, t),
                                a.ansycLoaded = !0,
                                a.$broadcast("scroll.refreshComplete"),
                                a.completeLoaded = !0
                        }
                        ;
                    e.getCountByDate({
                        Date: n
                    }).then(t, i)
                }
            }
            ,
            a.goDetailRpt = function (t) {
                t.Count > 0 && i.go("countByDetail", {
                    rid: d,
                    day: n,
                    id: t.LotteryId,
                    name: t.LotteryName
                })
            }
            ,
            a.getCountByDate()
    }
    ]).controller("CountByDetailCtrl", ["$scope", "$state", "utils", "AccountService", "AuthService", "$timeout", function (t, a, i, s, e, d) {
        if (1 == e.currentUser.TestState)
            return void a.go("index");
        var n = a.params.rid
            , l = a.params.day
            , o = a.params.id
            , c = a.params.name
            , v = null
            , b = 10;
        t.moreDataCanBeLoaded = !0,
            t.countLottery = null ,
            t.ansycLoaded = !1,
            t.detailData = [],
            t.whatDay = i.getWeek(l),
            t.rid = n,
            t.name = c,
            t.day = l,
            t.completeLoaded = !0,
            t.tip = {
                show: !1,
                index: 0
            },
            t.getCountByDateAndLotteryId = function () {
                if (t.completeLoaded) {
                    t.completeLoaded = !1;
                    var a = function (a) {
                            if (0 == a.ErrorCode) {
                                var s = a.Data.CountList;
                                t.moreDataCanBeLoaded = s.length < b ? !1 : !0,
                                    t.tip = i.getTip(2, s),
                                    angular.forEach(s, function (a) {
                                            t.detailData.push(a)
                                        }
                                    ),
                                    v = a.Data.SequenceID,
                                    t.ansycLoaded = !0,
                                    t.$broadcast("scroll.refreshComplete"),
                                    t.$broadcast("scroll.infiniteScrollComplete"),
                                    t.completeLoaded = !0
                            } else
                                e()
                        }
                        , e = function (a) {
                            t.moreDataCanBeLoaded = !1,
                                0 == t.detailData.length ? t.tip = i.getTip(1, a) : (t.tip.show = !1,
                                    d(function () {
                                            t.moreDataCanBeLoaded = !0
                                        }
                                        , 1e3)),
                                t.ansycLoaded = !0,
                                t.$broadcast("scroll.refreshComplete"),
                                t.$broadcast("scroll.infiniteScrollComplete"),
                                t.completeLoaded = !0
                        }
                        ;
                    s.getCountByDateAndLotteryId({
                        Date: l,
                        LotteryId: o,
                        PageSize: b,
                        SequenceID: v
                    }).then(a, e)
                }
            }
            ,
            t.getCountByDate = function () {
                var a = function (a) {
                        0 == a.ErrorCode && (t.countLottery = a.Data.AllSum)
                    }
                    ;
                s.getCountByDate({
                    Date: l,
                    LotteryId: o
                }).then(a)
            }
            ,
            t.reflash = function () {
                t.completeLoaded && (t.moreDataCanBeLoaded = !1,
                    t.detailData = [],
                    v = null ,
                    t.getCountByDateAndLotteryId(),
                    t.getCountByDate())
            }
            ,
            t.reflash()
    }
    ]),
    angular.module("controllers.history", []).controller("HistoryCtrl", ["$scope", "$stateParams", "LotteryInfoService", "$filter", "utils", "QuickDialog", "$ionicScrollDelegate", function (t, a, i, s, e, d, n) {
        t.backUrl = null ,
            t.backUrl = "0" == a.rid ? "#/index" : "#/lottery",
            t.model = {
                date: e.getDaysBeforeDate()
            },
            t.lotteryHistoryList = [],
            t.lotteryList = [],
            t.lottery = {},
            t.ansycLoaded = !1,
            t.params = {},
            t.isDate = !0,
            t.Date = null ,
            t.completeLoaded = !0,
            t.tip = {
                show: !1,
                index: 0
            },
            t.check = function () {
                t.completeLoaded || d.tips('<div class="tipDialog">' + s("translate")("Common.LabelLoadWait") + "</div>")
            }
        ;
        var l = function () {
                function s(i) {
                    0 == i.ErrorCode && (0 == a.rid && (a.rid = 4),
                        angular.forEach(i.Data.LotteryList, function (i) {
                                t.lotteryList.push({
                                    id: i.LotteryID,
                                    name: i.LotteryName
                                }),
                                i.LotteryID == a.rid && (t.lottery = {
                                    id: i.LotteryID,
                                    name: i.LotteryName
                                })
                            }
                        ),
                        t.getHistory(t.lottery))
                }

                i.getLotteryList().then(s)
            }
            ;
        t.getHistory = function (a) {
            function s(a) {
                if (n.$getByHandle("list-handle").scrollTop(),
                    0 == a.ErrorCode) {
                    var i = a.Data.HistoryList;
                    t.tip = e.getTip(2, i),
                        t.lotteryHistoryList = {
                            ydType: d,
                            sdj: l,
                            HistoryList: i
                        }
                } else
                    t.tip = e.getTip();
                t.ansycLoaded = !0,
                    t.$broadcast("scroll.refreshComplete"),
                    t.completeLoaded = !0
            }

            if (t.completeLoaded) {
                t.completeLoaded = !1,
                    t.lotteryHistoryList = [];
                var d, l;
                switch (null != a && (t.lottery = a,
                        t.ansycLoaded = !1),
                        t.lottery.id) {
                        case 2:
                            t.Date = null ,
                                t.isDate = !1,
                                t.class_pd = !0,
                                d = "2";
                            break;
                        case 3:
                            t.Date = null ,
                                t.isDate = !1,
                                t.class_pd = !0,
                                d = "2";
                            break;
                        case 4:
                            t.Date = null ,
                                t.isDate = !1,
                                t.class_pd = !0,
                                d = "5",
                                l = "6";
                            break;
                        case 6:
                            t.class_pd = !1,
                            t.isDate = !0,
                            d = "8"
                            break;
                        default:
                            t.class_pd = !1,
                                t.isDate = !0,
                                d = "2"
                    }
                var o = {
                        Date: t.Date,
                        LotteryId: t.lottery.id
                    }
                    , c = function (a) {
                        t.tip = e.getTip(1, a),
                            t.ansycLoaded = !0,
                            t.$broadcast("scroll.refreshComplete"),
                            t.completeLoaded = !0
                    }
                    ;
                i.getLotteyHistory(o).then(s, c)
            }
        }
            ,
            t.onQuery = function () {
                t.Date = angular.element(document.querySelectorAll("#GetDate")).val(),
                    t.getHistory(t.lottery)
            }
            ,
            l()
    }
    ]),
    angular.module("controllers.changLong", []).controller("ChangLongCtrl", ["$scope", "$state", "LotteryInfoService", "AccountService", "utils", "$ionicScrollDelegate", "QuickDialog", "$filter", function (t, a, i, s, e, d, n, l) {
        var o = a.params.rid;
        t.ansycLoaded = !1,
            t.lotteryData = [],
            t.changLongData = [],
            t.lottery = {},
            t.completeLoaded = !0,
            t.tip = {
                show: !1,
                index: 0
            },
            t.backUrl = null ,
            t.backUrl = "0" == o ? "#/index" : "#/lottery",
            t.check = function () {
                t.completeLoaded || n.tips('<div class="tipDialog">' + l("translate")("Common.LabelLoadWait") + "</div>")
            }
        ;
        var c = function () {
                var a = function (a) {
                        0 == a.ErrorCode && angular.forEach(a.Data.LotteryList, function (a) {
                                1 != a.LotteryID && 10 != a.LotteryID && t.lotteryData.push(a),
                                a.LotteryID == o && (t.lottery = a)
                            }
                        )
                    }
                    ;
                i.getLotteryList().then(a)
            }
            ;
        t.getChangLong = function (a) {
            if (t.completeLoaded) {
                t.completeLoaded = !1;
                var i = function (a) {
                        if (d.$getByHandle("list-handle").scrollTop(),
                            0 == a.ErrorCode) {
                            var i = a.Data.ChangLong;
                            t.tip = e.getTip(2, i),
                                t.changLongData = i
                        } else
                            t.tip = e.getTip();
                        t.ansycLoaded = !0,
                            t.$broadcast("scroll.refreshComplete"),
                            t.completeLoaded = !0
                    }
                    , n = function (a) {
                        t.tip = e.getTip(1, a),
                            t.ansycLoaded = !0,
                            t.$broadcast("scroll.refreshComplete"),
                            t.completeLoaded = !0
                    }
                    ;
                s.getChangLong(a).then(i, n)
            }
        }
            ,
            t.getChangLong(o),
            c()
    }
    ]),
    angular.module("controllers.luZhu", []).controller("LuZhuCtrl", ["$scope", "$stateParams", "$filter", "$ionicScrollDelegate", "LotteryInfoService", "AccountService", "$timeout", function (t, a, i, s, e, d, n) {
        var l = a.rid
            , o = [{
            ID: 2,
            URL: "/templates/default/account/luzhu/ballTemp.html"
        }, {
            ID: 3,
            URL: "/templates/default/account/luzhu/ballTemp.html"
        }, {
            ID: 4,
            URL: "/templates/default/account/luzhu/sideTemp.html"
        }, {
            ID: 5,
            URL: "/templates/default/account/luzhu/ballTemp.html"
        }, {
            ID: 6,
            URL: "/templates/default/account/luzhu/sideTemp.html"
        }, {
            ID: 7,
            URL: "/templates/default/account/luzhu/ballTemp.html"
        }, {
            ID: 8,
            URL: "/templates/default/account/luzhu/ballTemp.html"
        }, {
            ID: 9,
            URL: "/templates/default/account/luzhu/ballTemp.html"
        }];
        t.luZhu = {
            lotteryData: [],
            sideData: [],
            ballData: [],
            lottery: {},
            sideSelect: 0,
            ballSelect: 0
        },
            t.ansycLoaded = !1,
            t.ballLoaded = !1,
            t.backUrl = null;
        var c = function () {
                t.backUrl = "0" == l ? "#/index" : "#/lottery",
                4 != l && 6 != l && (t.luZhu.ballSelect = 1),
                    v()
            }
            , v = function () {
                var a = function (a) {
                        0 == a.ErrorCode && (angular.forEach(a.Data.LotteryList, function (a) {
                                1 != a.LotteryID && 10 != a.LotteryID && t.luZhu.lotteryData.push(a),
                                a.LotteryID == l && (t.luZhu.lottery = a)
                            }
                        ),
                            t.getLuZhuTemp(t.luZhu.lottery.LotteryID))
                    }
                    ;
                e.getLotteryList().then(a)
            }
            ;
        t.getLuZhuTemp = function (a) {
            t.ansycLoaded = !1,
                t.ballLoaded = !1,
                t.luZhu.sideData = [],
                t.luZhu.ballData = [],
                t.luZhu.sideSelect = 0,
                t.luZhu.ballSelect = 0,
            4 != a && 6 != a && (t.luZhu.ballSelect = 1),
                angular.forEach(o, function (i) {
                        i.ID == a && (t.temp = i)
                    }
                ),
                t.getLuZhu(a),
                b(".ball-item", document.querySelectorAll(".first-ball"))
        }
            ,
            t.getLuZhu = function (a) {
                var i = 0 == t.luZhu.sideData.length;
                t.luZhu.ballData = [];
                var e = function (a) {
                        0 == a.ErrorCode && angular.forEach(a.Data.LuZhu, function (a) {
                                if (0 != a.p) {
                                    if (a.c = a.c.split(",").slice(0, 20).reverse(),
                                        20 != a.c.length)
                                        for (var s = 0; s < 20 - a.c.length; s++)
                                            a.c.unshift(":"),
                                                s -= 1;
                                    a.c = a.c.toString(),
                                        t.luZhu.ballData.push(a)
                                }
                                if (0 == a.p && i) {
                                    if (a.c = a.c.split(",").slice(0, 20).reverse(),
                                        20 != a.c.length)
                                        for (var s = 0; s < 20 - a.c.length; s++)
                                            a.c.unshift(":"),
                                                s -= 1;
                                    a.c = a.c.toString(),
                                        t.luZhu.sideData.push(a)
                                }
                            }
                        ),
                            t.ansycLoaded = !0,
                            t.ballLoaded = !0
                    }
                    , l = n(function () {
                        s.$getByHandle("contentScroll").scrollTo(1e4, 0),
                            s.$getByHandle("bsScroll").scrollTo(1e4, 0),
                            s.$getByHandle("oeScroll").scrollTo(1e4, 0)
                    }
                    , 500)
                    , o = function () {
                        t.ansycLoaded = !0,
                            t.ballLoaded = !0
                    }
                    ;
                d.getLuZhu(a, t.luZhu.ballSelect).then(e, o).then(l)
            }
            ,
            t.onDrag = function (t) {
                var a = -1 * t.gesture.deltaY;
                s.$getByHandle("mainScroll").scrollBy(0, a, !0)
            }
            ,
            t.typeSelect = function (a, i) {
                t.luZhu.sideSelect = a,
                    b(".side-item", i.target),
                    s.$getByHandle("contentScroll").scrollTo(1e4, 0)
            }
            ,
            t.numSelect = function (a, i, e) {
                t.luZhu.ballSelect = i,
                    t.ballLoaded = !1,
                    b(".ball-item", e.target),
                    t.getLuZhu(a),
                    s.$getByHandle("bsScroll").scrollTo(1e4, 0),
                    s.$getByHandle("oeScroll").scrollTo(1e4, 0)
            }
        ;
        var b = function (t, a) {
                var i = angular.element(document.querySelectorAll(t));
                angular.forEach(i, function (t) {
                        var a = angular.element(t);
                        a.hasClass("selected") && a.removeClass("selected")
                    }
                ),
                a && angular.element(a).addClass("selected")
            }
            ;
        c()
    }
    ]),
    angular.module("controllers.limit", []).controller("LimitCtrl", ["$scope", "$filter", "$state", "$stateParams", "LotteryInfoService", "QuickDialog", "utils", "$ionicScrollDelegate", function (t, a, i, s, e, d, n, l) {
        t.backUrl = null ,
            t.backUrl = "0" == s.rid ? "#/index" : "#/lottery",
            t.betLimitList = [],
            t.lotteryList = [],
            t.lottery = {},
            t.ansycLoaded = !1,
            t.completeLoaded = !0,
            t.tip = {
                show: !1,
                index: 0
            },
            t.check = function () {
                t.completeLoaded || d.tips('<div class="tipDialog">' + a("translate")("Common.LabelLoadWait") + "</div>")
            }
        ;
        var o = function () {
                function a(a) {
                    0 == a.ErrorCode && (0 == s.rid && (s.rid = 4),
                        angular.forEach(a.Data.LotteryList, function (a) {
                                t.lotteryList.push({
                                    id: a.LotteryID,
                                    name: a.LotteryName
                                }),
                                a.LotteryID == s.rid && (t.lottery = {
                                    id: a.LotteryID,
                                    name: a.LotteryName
                                })
                            }
                        ),
                        t.getLimit(t.lottery))
                }

                e.getLotteryList().then(a)
            }
            ;
        t.getLimit = function (i) {
            function s(i) {
                if (l.$getByHandle("list-handle").scrollTop(),
                    0 == i.ErrorCode) {
                    var s = i.Data.LimitList;
                    t.tip = n.getTip(2, s),
                    0 == s.length && d.tips('<div class="tipDialog">' + a("translate")("Common.LabelTempNoData") + "</div>"),
                        t.betLimitList = {
                            LimitList: s
                        }
                } else
                    t.tip = n.getTip();
                t.ansycLoaded = !0,
                    t.$broadcast("scroll.refreshComplete"),
                    t.completeLoaded = !0
            }

            if (t.completeLoaded) {
                t.completeLoaded = !1,
                    t.betLimitList = [],
                null != i && (t.lottery = i,
                    t.ansycLoaded = !1);
                var o = {
                        Date: t.Date,
                        LotteryId: t.lottery.id
                    }
                    , c = function (a) {
                        t.tip = n.getTip(1, a),
                            t.ansycLoaded = !0,
                            t.$broadcast("scroll.refreshComplete"),
                            t.completeLoaded = !0
                    }
                    ;
                e.getBetLimit(o).then(s, c)
            }
        }
            ,
            o()
    }
    ]),
    angular.module("controllers.error", []).controller("ErrorCtrl", ["$scope", "$stateParams", "$filter", "$ionicScrollDelegate", "$timeout", function (t, a) {
        t.err = {
            content: a.contents
        },
            t.uri = function () {
                window.location = i(window.location.host)
            }
        ;
        var i = function (t) {
           // return   document.getElementById("APIURL").value;//"http://wapindexci.com/";
            return -1 != t.indexOf(".") ? window.location.protocol + "//m." + t.substring(t.indexOf(".") + 1) : window.location.href
        }
    }
    ]),
    angular.module("controllers.logonfailure", []).controller("LogonFailureCtrl", ["$scope", "$stateParams", "$filter", "$ionicScrollDelegate", "$timeout", function (t) {
        t.uri = function () {
            window.location = a(window.location.host)+"/#/login"
        }
        ;
        var a = function (t) {
            return -1 != t.indexOf(".") ? window.location.protocol + "//m." + t.substring(t.indexOf(".") + 1) : window.location.href
        }
    }
    ]),
    angular.module("controllers.sso", []).controller("SsoCtrl", ["$scope", "QuickDialog", "$stateParams", "$filter", "AuthService", "$state", "$timeout", "$rootScope",  function (t, a, i, s, e, d, n, l) {
        a.loading(5e4, '<ion-spinner class="enterLoading"></ion-spinner>');
        var b = function (t) {
                if (0 == t.ErrorCode) {
                    d.go("index")
                } else
                    l.$emit("event:sys-error", "进入游戏失败")
            }
            , m = {
                VenderNo: 0,
                DESDATA:0
            }
            , r = function (t) {
                return t ? void l.$emit("event:sys-error", "进入游戏失败[" + t.status + "]") : void l.$emit("event:sys-error", "进入游戏失败")
            }
            ;
        e.sso(m).then(b, r)
    }
    ]),
    angular.module("controllers.login", []).controller("LoginCtrl", ["$scope", function () {
                var a = function (t) {
                    //return document.getElementById("APIURL").value;//"http://wapindexci.com/";
                    return -1 != t.indexOf(".") ? window.location.protocol + "//m." + t.substring(t.indexOf(".") + 1) : window.location.href
                }
			window.location = a(window.location.host)+"/#/login";
    }
    ]);