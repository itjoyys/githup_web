package main

import (
	"html/template"
	"path"

	"app/models"
	. "common"
	"core/webcore"
	"middleware"
	"middleware/cache"
	"middleware/i18n"
	"middleware/session"
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

//创建APP
func NewApp() *webcore.Macaron {
	//版本初始化
	m := webcore.New(Log.GetAccessLogger())

	if webcore.Env == webcore.DEV {
		m.Use(webcore.Logger())
	}

	m.Use(middleware.Recovery())
	//开启gzip
	//m.Use(webcore.Gziper())

	//静态文件
	m.Use(webcore.Static(
		StaticRootPath,
		webcore.StaticOptions{
			SkipLogging: true,
		},
	))
	//----------------------------------------------

	m.Use(webcore.Renderer(webcore.RenderOptions{
		Directory:  DefaultTemplatPath,
		Extensions: []string{".tmpl", ".html"},
		Funcs:      []template.FuncMap{TemplateFuncs},
		IndentJSON: webcore.Env != webcore.PROD,
	}))
	m.Use(i18n.I18n(i18n.Options{
		// 存放本地化文件的目录，默认为 "conf/locale"
		Directory: path.Join(ConfRootPath, "locale"),
		Langs:     Langs,
		Names:     Names,
		// 当通过 URL 参数指定语言时是否重定向，默认为 false
		Redirect: true,
	}))
	m.Use(cache.Cacher(cache.Options{
		Adapter:       CacheAdapter,
		AdapterConfig: CacheConn,
		Interval:      CacheInternal,
	}))

	m.Use(session.Sessioner(SessionConfig))

	m.Use(middleware.Contexter())

	return m
}
