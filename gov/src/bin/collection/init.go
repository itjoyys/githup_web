package main

import (
	"app/models"
	. "common"
	"core/webcore"

	_ "middleware/session/mysql"
)

//程序初始化
func GlobalInit(ver string) {
	if ver == "online" {
		webcore.Env = webcore.PROD
	} else {
		webcore.Env = webcore.DEV
	}
	GetConf()          //加载配置文件
	InitLog()          //初始化日志系统
	models.NewEngine() //初始化数据库连接
}
