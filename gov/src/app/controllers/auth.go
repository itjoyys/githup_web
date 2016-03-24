package controllers

import (
	"strings"

	"app/models"
	"common"
	"core/webcore"
	//"fmt"
	"middleware"
)

//ip是否可以登录
func AuthIP() webcore.Handler {
	return func(ctx *middleware.Context) {
		//是否有权限访问接口
		if _manage_auth(ctx) {
			return
		}
		//User-Agent
		useragent := ctx.Req.UserAgent()
		if 0 == strings.Index(useragent, "Game_Go_") {
			//存在并且在最前面
			siteid := strings.Replace(useragent, "Game_Go_", "", -1)
			ip := ctx.RemoteAddr()
			//fmt.Println(siteid, "  ", ip)
			//common.Log.Info(siteid,"  ",ip)
			siteconfig, err := models.IsAllowRequest(ip, siteid)
			//fmt.Println(siteconfig, err)
			if err == nil {
				//判断key是否正确
				params := ctx.Query("params")
				params = strings.Replace(params, " ", "+", -1)
				key := common.Md5(params + siteconfig.SiteMd5Key)
				//fmt.Println(key, " ------------ ", ctx.Query("key"))
				//common.Log.Info(key,"  ",ctx.Query("key"))
				if key == ctx.Query("key") {
					return
				}
			}
		}
		//无权限访问
		ctx.JSON(200, not_allow_json)
	}
}

func _manage_auth(ctx *middleware.Context) bool {
	//是否有权限访问接口
	//User-Agent
	useragent := ctx.Req.UserAgent()
	if 0 == strings.Index(useragent, "Game_Go_") {
		//存在并且在最前面
		siteid := strings.Replace(useragent, "Game_Go_", "", -1)
		ip := ctx.RemoteAddr()
		//common.Log.Info(siteid,"  ",ip)
		allsiteid := common.Cfg.Section("app").Key("MANAGE_SITEID").MustString("manage")
		md5key := common.Cfg.Section("app").Key("MANAGE_MD5KEY").MustString("123")
		manageips := common.Cfg.Section("app").Key("MANAGE_IP").MustString("123")
		ips := strings.Split(manageips, ",")
		flag := false
		for _, v := range ips {
			if v == ip {
				flag = true
			}
		}
		if siteid == allsiteid && flag {
			//判断key是否正确
			params := ctx.Query("params")
			params = strings.Replace(params, " ", "+", -1)
			key := common.Md5(params + md5key)
			//common.Log.Info(key,"  ",ctx.Query("key"))
			if key == ctx.Query("key") {
				return true
			}
		}
	}
	return false
}

//总后台函数验证
func AuthManage() webcore.Handler {
	return func(ctx *middleware.Context) {
		//是否有权限访问接口
		//User-Agent
		useragent := ctx.Req.UserAgent()
		if 0 == strings.Index(useragent, "Game_Go_") {
			//存在并且在最前面
			siteid := strings.Replace(useragent, "Game_Go_", "", -1)
			ip := ctx.RemoteAddr()
			allsiteid := common.Cfg.Section("app").Key("MANAGE_SITEID").MustString("manage")
			md5key := common.Cfg.Section("app").Key("MANAGE_MD5KEY").MustString("123")
			manageips := common.Cfg.Section("app").Key("MANAGE_IP").MustString("123")
			ips := strings.Split(manageips, ",")
			flag := false
			for _, v := range ips {
				if v == ip {
					flag = true
				}
			}
			if siteid == allsiteid && flag {
				//判断key是否正确
				params := ctx.Query("params")
				params = strings.Replace(params, " ", "+", -1)
				key := common.Md5(params + md5key)
				//common.Log.Info(key,"  ",ctx.Query("key"))
				if key == ctx.Query("key") {
					return
				}
			}
		}
		//无权限访问
		ctx.JSON(200, not_allow_json)
	}
}
