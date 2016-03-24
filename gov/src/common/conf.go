//网站的基本配置
package common

import (
	"log"
	"os"
	"os/exec"
	"path"
	"path/filepath"
	"strings"

	"core/ini"
	"core/webcore"
	"middleware/session"
)

var (
	// App settings.
	AppVer         string
	AppName        string
	AppUrl         string
	CookiePath     string
	StaticRootPath string
	Socks5         string

	Cfg          *ini.File
	ConfRootPath string
	// Log settings.
	LogRootPath string

	// Cache settings.
	CacheAdapter  string
	CacheInternal int
	CacheConn     string

	DefaultTemplatPath string
	AttachmentMaxSize  int64
	AttachmentPath     string
	ImagesPath         string
	ImagesUrl          string

	AvatarUploadPath string
	AvatarUrl        string

	// Session settings.
	SessionConfig session.Options
	//csrf
	SecretKey string
	//国际化
	Langs, Names []string
)

func ExecPath() (string, error) {
	file, err := exec.LookPath(os.Args[0])
	if err != nil {
		return "", err
	}
	p, err := filepath.Abs(file)
	if err != nil {
		return "", err
	}
	return p, nil
}

// WorkDir returns absolute path of work directory.
func WorkDir() (string, error) {
	execPath, err := ExecPath()
	return path.Dir(strings.Replace(execPath, "\\", "/", -1)), err
}

func GetConf() {
	workDir, err := WorkDir()
	if err != nil {
		log.Fatal(4, "Fail to get work directory: %v", err)
	}
	//log.Fatal(4, "Fail to get work directory: %v", workDir)
	if webcore.Env == webcore.DEV {
		//TODO 方便调试
		ConfRootPath = path.Join(workDir, "../a_static/conf")

		Cfg, err = ini.Load(path.Join(ConfRootPath, "localapp.ini"))
		if err != nil {
			log.Fatal(4, "Fail to parse 'conf/localapp.ini': %v", err)
		}
		//TODO 方便调试
		DefaultTemplatPath = Cfg.Section("app").Key("DEFAULT_TEMPLAT_PATH").MustString(path.Join(workDir, "../src/templates"))
		StaticRootPath = Cfg.Section("app").Key("STATIC_ROOT_PATH").MustString(path.Join(workDir, "../a_static/public")) //.MustString(workDir)
		AttachmentPath = Cfg.Section("app").Key("ATTACHMENT_PATH").MustString(path.Join(workDir, "../a_static/public/uploads/att/"))
		ImagesPath = Cfg.Section("app").Key("IMAGES_PATH").MustString(path.Join(workDir, "../a_static/public/uploads/allimg/"))
		ImagesUrl = Cfg.Section("app").Key("IMAGES_URL").MustString(path.Join(workDir, "/uploads/allimg"))
	} else {
		//更目录
		ConfRootPath = path.Join(workDir, "conf")

		Cfg, err = ini.Load(path.Join(ConfRootPath, "app.ini"))
		if err != nil {
			log.Fatal(4, "Fail to parse 'conf/app.ini': %v", err)
		}
		DefaultTemplatPath = Cfg.Section("app").Key("DEFAULT_TEMPLAT_PATH").MustString(path.Join(workDir, "templates"))
		StaticRootPath = Cfg.Section("app").Key("STATIC_ROOT_PATH").MustString(path.Join(workDir, "public"))
		AttachmentPath = Cfg.Section("app").Key("ATTACHMENT_PATH").MustString(path.Join(workDir, "public/uploads/att/"))
		ImagesPath = Cfg.Section("app").Key("IMAGES_PATH").MustString(path.Join(workDir, "public/uploads/allimg/"))
		ImagesUrl = Cfg.Section("app").Key("IMAGES_URL").MustString(path.Join(workDir, "/uploads/uploadfile"))
	}
	Socks5 =  Cfg.Section("app").Key("SOCKS5").MustString("")
	//头像目录
	AvatarUploadPath = Cfg.Section("app").Key("AVATAR_UPLOAD_PATH").MustString(path.Join(workDir, "avatars"))
	AvatarUrl = Cfg.Section("app").Key("AVATAR_URL").MustString("/avatar")
	//根目录
	LogRootPath = Cfg.Section("log").Key("ROOT_PATH").MustString(path.Join(workDir, "log"))

	CookiePath = Cfg.Section("app").Key("COOKIE_PATH").MustString("/")

	Langs = Cfg.Section("i18n").Key("LANGS").Strings(",")
	Names = Cfg.Section("i18n").Key("NAMES").Strings(",")

	sec := Cfg.Section("security")
	SecretKey = sec.Key("SECRET_KEY").String()

	newCacheService(workDir)
	newSessionService(workDir)
	//调试
	if webcore.Env == webcore.DEV {
		log.Println("StaticRootPath:"+StaticRootPath, "\nAvatarUploadPath:"+AvatarUploadPath)
		log.Println("workDir:"+workDir, "\nLogRootPath:"+LogRootPath, "\nConfRootPath:"+ConfRootPath)
	}
}

func newCacheService(workDir string) {
	CacheAdapter = Cfg.Section("cache").Key("ADAPTER").In("memory", []string{"memory", "redis", "memcache", "file"})

	switch CacheAdapter {
	case "memory":
		CacheInternal = Cfg.Section("cache").Key("INTERVAL").MustInt(60)
	case "redis", "memcache":
		CacheConn = strings.Trim(Cfg.Section("cache").Key("HOST").String(), "\" ")
	case "file":
		CacheConn = Cfg.Section("cache").Key("CACHE_PATH").MustString(path.Join(workDir, "cache"))
	default:
		log.Fatal(4, "Unknown cache adapter: %s", CacheAdapter)
	}

}

func newSessionService(workDir string) {
	SessionConfig.Provider = Cfg.Section("session").Key("PROVIDER").In("memory",
		[]string{"memory", "file", "redis", "mysql"})
	providerConfig := strings.Trim(Cfg.Section("session").Key("PROVIDER_CONFIG").String(), "\" ")
	//TODO 测试
	if SessionConfig.Provider == "file" {
		SessionConfig.ProviderConfig = path.Join(workDir, providerConfig)
	} else {
		SessionConfig.ProviderConfig = providerConfig
	}
	//存储Session的cookie
	SessionConfig.CookieName = Cfg.Section("session").Key("COOKIE_NAME").MustString("goblog_v1")
	SessionConfig.CookiePath = CookiePath
	SessionConfig.Secure = Cfg.Section("session").Key("COOKIE_SECURE").MustBool()
	SessionConfig.CookieLifeTime = Cfg.Section("session").Key("COOKIE_LIFE_TIME").MustInt(86400)
	if len(MainHost) > 0 {
		SessionConfig.Domain = MainHost
	}

	SessionConfig.Gclifetime = Cfg.Section("session").Key("GC_INTERVAL_TIME").MustInt64(86400)
	SessionConfig.Maxlifetime = Cfg.Section("session").Key("SESSION_LIFE_TIME").MustInt64(86400)
}
