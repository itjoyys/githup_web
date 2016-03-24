package middleware

import (
	"fmt"
	"io"
	"net/http"
	"strings"
	"time"

	//"app/models" //后台登陆使用
	. "common"
	"core/webcore"
	"middleware/cache"
	"middleware/i18n"
	"middleware/session"
)

// Context represents context of a request.
type Context struct {
	*webcore.Context               //继承
	Cache            cache.Cache   //缓存
	Session          session.Store //回话

	//User     *models.Admin
	//IsSigned bool
	Errors   []string
}

func (self *Context) HasError() bool {
	return (len(self.Errors) > 0)
}

func (self *Context) AddError(err string) {
	self.Errors = append(self.Errors, err)
	self.Data["Errors"] = self.Errors
}

// HTML calls Context.HTML and converts template name to string.
func (ctx *Context) HTML(status int, name string) {
	ctx.Context.HTML(status, string(name))
}

// HTML calls Context.HTML and converts template name to string.
func (ctx *Context) HTMLData(status int, name string, data interface{}) {
	ctx.HTMLSet(status, "DEFAULT", name, data)
}

// RenderWithErr used for page has form validation but need to prompt error to users.
//设置错误提示
func (ctx *Context) RenderWithErr(msg string, tpl string, form interface{}) {
	/*
		if form != nil {
			auth.AssignForm(form, ctx.Data)
		}
	*/
	ctx.Data["HasError"] = msg
	ctx.HTML(200, tpl)
}

// Handle handles and logs error by given status.
func (ctx *Context) Handle(status int, title string, err error) {
	if err != nil {
		Log.Errf("%s: %v", title, err)
		if webcore.Env != webcore.PROD {
			ctx.Data["ErrorMsg"] = err
		}
	}

	switch status {
	case 404:
		ctx.Data["Title"] = "Page Not Found"
	case 500:
		ctx.Data["Title"] = "Internal Server Error"
	}
	ctx.HTML(status, string(fmt.Sprintf("status/%d", status)))
}

//?
func (ctx *Context) ServeContent(name string, r io.ReadSeeker, params ...interface{}) {
	modtime := time.Now()
	for _, p := range params {
		switch v := p.(type) {
		case time.Time:
			modtime = v
		}
	}
	ctx.Resp.Header().Set("Content-Description", "File Transfer")
	ctx.Resp.Header().Set("Content-Type", "application/octet-stream")
	ctx.Resp.Header().Set("Content-Disposition", "attachment; filename="+name)
	ctx.Resp.Header().Set("Content-Transfer-Encoding", "binary")
	ctx.Resp.Header().Set("Expires", "0")
	ctx.Resp.Header().Set("Cache-Control", "must-revalidate")
	ctx.Resp.Header().Set("Pragma", "public")
	http.ServeContent(ctx.Resp, ctx.Req.Request, name, modtime, r)
}

// Contexter initializes a classic context for a request.
func Contexter() webcore.Handler {
	return func(c *webcore.Context, l i18n.Locale, cache cache.Cache, sess session.Store) {
		ctx := &Context{
			Context: c,
			Cache:   cache,
			Session: sess,
		}
		/*
			// url 绝对url路径
			link := AppSubUrl + ctx.Req.RequestURI
			i := strings.Index(link, "?")
			if i > -1 {
				link = link[:i]
			}
			ctx.Data["Link"] = link
		*/
		//统计时间
		ctx.Data["PageStartTime"] = time.Now()
		/*
		var err error
		// Get user from session if logined.
		if uid, ok := ctx.Session.Get("adminid").(int64); ok {
			ctx.User, err = models.GetAdminById(uid)
			if err != nil {
				Log.Err("ctx get user err : ", err)
				ctx.User = nil
			}
		}

		//用户信息处理
		if ctx.User != nil {
			ctx.IsSigned = true
			ctx.Data["IsSigned"] = ctx.IsSigned
			ctx.Data["SignedUser"] = ctx.User
			ctx.Data["SignedUserName"] = ctx.User.Username
		} else {
			ctx.Data["SignedUserName"] = ""
		}
		*/
		// 过滤大附件
		if ctx.Req.Method == "POST" && strings.Contains(ctx.Req.Header.Get("Content-Type"), "multipart/form-data") {
			if err := ctx.Req.ParseMultipartForm(AttachmentMaxSize << 20); err != nil && !strings.Contains(err.Error(), "EOF") { // 32MB max size
				ctx.Handle(500, "ParseMultipartForm", err)
				return
			}
		}

		c.Map(ctx)
	}
}
