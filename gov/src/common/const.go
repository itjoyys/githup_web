package common

import (
	"core/webcore"
)

var (
	MainHost = ""
)

func InitConst() {
	if webcore.Env == webcore.DEV {
		MainHost = ""
	} else if webcore.Env == webcore.PROD {
		MainHost = "zengnotes.com"
	}
}
