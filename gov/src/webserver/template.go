package main

import (
	"html/template"
	"math/rand"
	"reflect"
	"strconv"
	"strings"
	"time"

	"app/models"
	. "common"
)

var TemplateFuncs template.FuncMap = map[string]interface{}{
	"formatTime": func(args ...interface{}) string {
		return args[0].(time.Time).Format("Jan _2 15:04")
	},
	"cnFormatTime": func(args ...interface{}) string {
		return time.Unix(args[0].(int64), 0).Format("2006-01-02 15:04")
	},
	"unescaped": func(args ...interface{}) template.HTML {
		return template.HTML(args[0].(string))
	},
	"isnil": func(args ...interface{}) bool {
		return args[0] == nil
	},
	"equal": func(args ...interface{}) bool {
		return args[0] == args[1]
	},
	"equalint8": func(args ...int8) bool {
		return args[0] == args[1]
	},
	"equalint64": func(args ...int64) bool {
		return args[0] == args[1]
	},
	"menusmapindex": func(args ...interface{}) []*models.Menu {
		maps := args[0].(map[int64][]*models.Menu)
		index := args[1].(int)
		if _, ok := maps[int64(index)]; !ok {
			return nil
		}
		return maps[int64(index)]
	},
	//<font color="red">法院</font>
	"highlight": func(args ...string) string {
		if len(args[1]) > 0 {
			newstr := "<font color=\"red\">" + args[1] + "</font>"
			return strings.Replace(args[0], args[1], newstr, -1)
		} else {
			return args[0]
		}
	},
	"typeof": func(args ...interface{}) bool {
		if len(args) == 2 {
			str := reflect.TypeOf(args[0]).Name()
			if str == args[1].(string) {
				return true
			}
		}
		return false
	},
	"getdefaultpic": func() string {
		r := rand.New(rand.NewSource(time.Now().UnixNano()))
		str_id := strconv.Itoa(r.Intn(20))
		return "/images/defaultpic/sj_" + str_id + ".jpg"
	},
	"mold": func(args ...interface{}) bool {
		id := args[0].(int)
		num := args[1].(int)
		return id%num == 0
	},
	"strcat": func(args ...interface{}) string {
		str := args[0].(string)
		num := args[1].(int)
		return SubString(str, 0, num)
	},
	/*
		"global": func(key string) string {
			return siteconfig.Global(key)
		},
	*/
	"bigavatar": func(uid int64) string {
		apath := GetAvatar(uid, 1, "big")
		if len(apath) == 0 {
			apath = "/images/user-img/sex-man.png"
		}
		return apath
	},
	"sex": func(key int64) string {
		if key == 1 {
			return "男"
		}
		return "女"
	},
	"plus": func(args ...int) int {
		var result int
		for _, val := range args {
			result += val
		}
		return result
	},
	"plus64": func(args ...int64) int64 {
		var result int64
		for _, val := range args {
			result += val
		}
		return result
	},
}
