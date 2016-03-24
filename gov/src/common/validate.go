package common

import (
	"regexp"
	"strings"
)

var (
	//禁止使用的名字
	illegalEquals = []string{"debug", "raw", "install", "api", "avatar", "user",
		"org", "help", "stars", "issues", "pulls", "commits", "repo", "template", "admin", "new"}
	illegalSuffixs = []string{".git", ".keys"}

	MsgIllegalEmail = "E-mail is illegal"
	MsgIllegalName  = "User name is illegal "
)

//错误类型
type ErrorMsg struct {
	ErrorId int8   `json:"errorid"`
	Msg     string `json:"msg"`
}

func ImageUplaodFileValidate(con_type string) string {
	expandedName := ""
	switch con_type {
	case "image/pjpeg", "image/jpeg":
		expandedName = ".jpg"
	case "image/png", "image/x-png":
		expandedName = ".png"
	case "image/gif":
		expandedName = ".gif"
	case "image/bmp":
		expandedName = ".bmp"
	}
	return expandedName
}

// 验证用户名
func NameValidate(name string) *ErrorMsg {
	msg := new(ErrorMsg)
	name = strings.ToLower(name)
	for _, char := range illegalEquals {
		if name == char {
			msg.ErrorId = 1
			msg.Msg = MsgIllegalName
			return msg
		}
	}
	for _, char := range illegalSuffixs {
		if strings.HasSuffix(name, char) {
			msg.ErrorId = 1
			msg.Msg = MsgIllegalName
			return msg
		}
	}
	return msg
}

// 验证字符串长度
func LenValidate(name string, lens int, errmsg string) *ErrorMsg {
	msg := new(ErrorMsg)
	if len([]rune(name)) < lens {
		msg.ErrorId = 1
		msg.Msg = errmsg
		return msg
	}
	return msg
}

// 验证邮箱
func EmailValidate(email string) *ErrorMsg {
	msg := new(ErrorMsg)
	re := regexp.MustCompile(".+@.+\\..+")
	matched := re.Match([]byte(email))
	if !matched {
		msg.ErrorId = 1
		msg.Msg = MsgIllegalEmail
	}
	return msg
}
