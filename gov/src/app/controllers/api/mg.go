package api

import (
	"strings"
	"text/template"
	"time"

	"app/models"
	mmg "app/models/mg"
	"bytes"
	"common"
	"middleware"
	"plug-ins/games/mg"
	"utility"
)

const (
	//https://redirect.CONTDELIVERY.COM/Casino/Default.aspx?applicationid=1023&sext1=[UserName]&sext2=[Password]&csid=16113&serverid=16113&gameid=DragonDance&ul=en&theme=igamingA4&usertype=0&variant=instantplay
	mgdz_url   = "https://redirect.CONTDELIVERY.COM/Casino/Default.aspx?applicationid=1023&sext1={{.Name}}&sext2={{.Password}}&csid=16113&serverid=16113&gameid={{.GameId}}&ul={{.Lang}}&theme=igamingA4&usertype=0&variant=instantplay"
	mgdz_post  = "https://redirect.CONTDELIVERY.COM/Casino/Default.aspx"
	//https://livegames.gameassists.co.uk/ETILandingPage/?CasinoID=16113&LoginName={{.Name}}&Password={{.Password}}ClientID=4&UL=zh-cn&VideoQuality=AutoSD&ClientType=1&ModuleID=70004&UserType=0&ProductID=2&ActiveCurrency=Credits&CDNselection=1&altProxy=TNG
	mgsx_url   = "https://livegames.gameassists.co.uk/ETILandingPage/?CasinoID=16113&LoginName={{.Name}}&Password={{.Password}}&ClientID=4&UL=zh&VideoQuality=AutoSD&ClientType=1&ModuleID=70004&UserType=0&ProductID=2&ActiveCurrency=Credits&CDNselection=1&altProxy=TNG"
	//"https://livegames.gameassists.co.uk/ETILandingPage/?CasinoID=15062&LoginName={{.Name}}&Password={{.Password}}&ClientID=4&UL=zh-cn&VideoQuality=auto6&BetProfileID=DesignStyleA&CustomLDParam=MultiTableMode^^1||LobbyMode^^C&StartingTab=%20SPCasinoholdem&ClientType=1&ModuleID=70004&UserType=0&ProductID=2&ActiveCurrency=Credits"
	mgh5dz_url = "https://mobile2206.gameassists.co.uk/MobileWebServices_40/casino/game/launch/iGamingA4HTML5/{{.GameId}}/{{.Lang}}/?username={{.Name}}&password={{.Password}}&currencyFormat=%23%2C%23%23%23.%23%23"
	//https://mobile2206.gameassists.co.uk/Lobby/en/iGamingA4Html5
	//"https://mobile2205.gameassists.co.uk/MobileWebServices_40/casino/game/launch/iGamingA3HTML5/{{.GameId}}/{{.Lang}}/?username={{.Name}}&password={{.Password}}&currencyFormat=%23%2C%23%23%23.%23%23"
)

//添加用户
func MG_CheckOrCreateGameAccout(ctx *middleware.Context) {
	//获取参数
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("Mg DecParams ：", err)
	}
	siteid2 := mv.Get("siteid")
	username := mv.Get("username")
	agent_id, _ := utility.StrTo(mv.Get("agent_id")).Int()
	cur := mv.Get("cur")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	index_id := mv.Get("index_id")
	if len(index_id) == 0 {
		index_id = "a"
	}
	//是否已经纯在
	if mmg.IsUserExist(username, siteid) {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	//判断用户是否存在，如果不存在则创建,存在换名字
	if len(username) >= 12 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10005}})
		return
	}
	user := mmg.User{}
	user.UserName = username
	user.SiteId = siteid2
	user.Cur = cur
	user.IndexId = index_id
	user.AgentId = agent_id
	username = siteid2 + username
	if len(username) > 16 {
		username = common.SubString(username, 0, 15)
	}
	b := false
	ii := 0
	for {
		if b || ii > 10 {
			break
		}
		b = mmg.IsGUserNotExist(username)
		if !b {
			username = CreateUserName(username)
		}
		ii++
	}
	mg_g, err := mg.MGameInit(siteid2)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	//原则用户是否存在
	isexist := ""
	ii = 0
	for {
		if isexist == "1" || ii > 10 {
			break
		}
		addresult, err := mg_g.AddAccount(username, mmg.Password, user.UserName, "pk"+siteid2)
		if err != nil {
			if addresult != nil && (addresult.ErrorCode == 115 || addresult.ErrorCode == 66) {
				isexist = "0"
			} else {
				common.Log.Err("Mg CheckAndCreateAccount sql error：", err)
				ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10012}})
				return
			}
		} else {
			isexist = "1"
		}
		if isexist != "0" {
			if isexist != "1" {
				ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10012}})
				return
			}
		}
		if isexist != "1" {
			username = CreateUserName(username)
		}
		ii++
	}
	if isexist != "1" {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10012}})
		return
	}
	user.GUserName = username
	err = mmg.CreateUser(&user)
	if err != nil {
		common.Log.Err("mg CheckAndCreateAccount sql error：", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10011}})
}

//获取余额
func MG_GetBalance(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	siteid2 := mv.Get("siteid")
	username := mv.Get("username")

	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	//获取用户
	userinfo, err := mmg.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("MG GetUserInfo:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}

	mg_g, err := mg.MGameInit(siteid2)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	balance, err := mg_g.GetAccountBalance(userinfo.GUserName)
	if err != nil {
		common.Log.Info("MG GetBalance", err)
		ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10018}})
		return
	}
	ctx.JSON(200, JsonResult{Result: true, Data: BalanceData{Code: 10017, Balance: balance[0].Balance}})
}

func MG_TransferCredit(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	siteid2 := mv.Get("siteid")
	username := mv.Get("username")
	ctype := mv.Get("type")
	credit, err := utility.StrTo(mv.Get("credit")).Float64()
	if err != nil || credit == 0 {
		ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10019}})
		return
	}

	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	billno := time.Now().Format("0102150405.000000")
	//去掉小数点
	billno = strings.Replace(billno, ".", "", 10)
	mg_g, err := mg.MGameInit(siteid2)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	gcr := new(models.GameCashRecord)
	gcr.Platform = "mg"
	gcr.Info = ""
	if strings.ToUpper(ctype) == "IN" {
		gcr.CashType = 1
	} else if strings.ToUpper(ctype) == "OUT" {
		gcr.CashType = 2
	} else {
		gcr = nil
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	gcr.SiteID = siteid2
	gcr.UserName = username
	gcr.TradeID = billno
	//获取用户密码
	userinfo, err := mmg.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("mg GetUserInfo sql error：", err)
			gcr = nil
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	gcr.Credit = credit
	gcr.Info = "mooney:" + mv.Get("credit") + "  "
	if gcr.CashType == 1 {
		deposit, err := mg_g.Deposit(userinfo.GUserName, credit, 8)
		if err == nil {
			//本地添加一条转账记录
			gcr.Status = 1
			gcr.Credit = deposit.TransactionAmount
			gcr.TransactionId = deposit.TransactionId
		} else {
			common.Log.Err("mg Deposit:", err)
			gcr.Info += err.Error()
			gcr.Status = 0
		}
	} else if gcr.CashType == 2 {
		withdrawal, err := mg_g.Withdrawal(userinfo.GUserName, credit)
		if err == nil {
			//本地添加一条转账记录
			gcr.Status = 1
			gcr.Credit = withdrawal.TransactionAmount
			gcr.TransactionId = withdrawal.TransactionId
		} else {
			common.Log.Err("mg Withdrawal:", err)
			gcr.Info += err.Error()
			gcr.Status = 0
		}
	}
	err = models.CreateGameCashRecord(gcr)
	if err != nil {
		common.Log.Err("OG TransferCredit ", err)
	}
	if gcr.Status == 0 {
		gcr = nil
		ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10014}})
		return
	}
	gcr = nil
	ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10013}})
}

//修改用户秘密
//EditAccount(name, password) (bool, error)
func MG_EditAccountPwd(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	siteid2 := mv.Get("siteid")
	username := mv.Get("username")
	password := mv.Get("password")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	//获取用户是否存在
	userinfo, err := mmg.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("MG GetUserInfo ", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	mg_g, err := mg.MGameInit(siteid2)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	isok, err := mg_g.EditAccount(userinfo.GUserName, password)
	if err != nil {
		common.Log.Err("MG EditAccount:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	//修改用户密码
	if isok {
		ccc, err := mmg.ChengUserPwd(username, siteid2, password)
		if ccc {
			//修改成功
			ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10011}})
			return
		} else {
			common.Log.Err("MG ChengUserPwd:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	} else {
		common.Log.Err("MG EditAccount:", "pwd error")
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}

}

//GetPlaycheckUrl(name, password, lang string) (string, error)
func MG_GetPlaycheckUrl(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	siteid2 := mv.Get("siteid")
	username := mv.Get("username")
	lang := mv.Get("lang")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	//获取用户
	userinfo, err := mmg.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("MG GetUserInfo ", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	mg_g, err := mg.MGameInit(siteid2)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	url, err := mg_g.GetPlaycheckUrl(userinfo.GUserName, userinfo.Password, lang)
	if err != nil {
		common.Log.Err("MG GetPlaycheckUrl:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	ctx.Echo(200, url)
}

func MG_ForwardGame(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	siteid2 := mv.Get("siteid")
	username := mv.Get("username")
	//gametype := mv.Get("gametype")
	//lang := mv.Get("lang")
	//cur := mv.Get("cur")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	//获取用户
	userinfo, err := mmg.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("MG GetUserInfo ", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	t, err := template.New("mgsx_url").Parse(mgsx_url)
	data := struct {
		Name     string
		Password string
	}{Name: userinfo.GUserName, Password: userinfo.Password}

	buf := bytes.NewBufferString("")
	err = t.Execute(buf, &data)
	if err != nil {
		common.Log.Err("MG Execute template:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	ctx.Echo(200, buf.String())
}

func MG_ForwardDz(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	siteid2 := mv.Get("siteid")
	username := mv.Get("username")
	gameid := mv.Get("gameid")
	gametype := mv.Get("gametype")
	//lang := mv.Get("lang")
	//cur := mv.Get("cur")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	//获取用户
	userinfo, err := mmg.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("MG GetUserInfo ", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	url := ""
	ln := ""
	if gametype == "11" {
		url = mgh5dz_url
		ln = "zh-cn"
	} else {
		url = mgdz_url
		ln = "zh"
	}
	t, err := template.New("mgdz_url").Parse(url)
	data := struct {
		Name     string
		Password string
		GameId   string
		Lang     string
	}{Name: userinfo.GUserName, Password: userinfo.Password, GameId: gameid, Lang: ln}

	buf := bytes.NewBufferString("")
	err = t.Execute(buf, &data)
	if err != nil {
		common.Log.Err("MG Execute template:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}

	ctx.Echo(200, buf.String())
}

func MG_ForwardDzPost(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	siteid2 := mv.Get("siteid")
	username := mv.Get("username")
	gameid := mv.Get("gameid")
	//gametype := mv.Get("gametype")
	//lang := mv.Get("lang")
	//cur := mv.Get("cur")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	//获取用户
	userinfo, err := mmg.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("MG GetUserInfo ", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	/*
		t, err := template.New("mgdz_url").Parse(mgdz_url)
		data := struct {
			Name     string
			Password string
			GameId   string
			Lang     string
		}{Name: userinfo.GUserName, Password: userinfo.Password, GameId: gameid, Lang: "zh"}

		buf := bytes.NewBufferString("")
		err = t.Execute(buf, &data)
		if err != nil {
			common.Log.Err("MG Execute template:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}*/
	html := `<form style="display: none;" name="form1" action="` + mgdz_post + `" method="post">
    <input type="text" name="applicationid" value='1023'/>
    <input type="text" name="sext1" value='` + userinfo.GUserName + `'/> 
    <input type="text" name="sext2" value='` + userinfo.Password + `'/> 
    <input type="text" name="csid" value='16113'/>
    <input type="text" name="serverid" value='16113'/>
    <input type="text" name="gameid" value='` + gameid + `'/>
    <input type="text" name="ul" value='zh'/><input type="text" name="theme" value='iGamingA4'/>
    <input type="text" name="variant" value='instantplay'/>
    </form><script language="javascript">document.form1.submit()</script>`

	ctx.Echo(200, html)
}

//siteid=t/\\/username=/\\/orderid=/\\/video_type=/\\/game_type=/\\/
//s_time=2015-06-02 00:00:00/\\/e_time=2015-06-02 23:59:59/\\/
//agent_id=89|91/\\/page=1/\\/page_num=20
func MG_GetBetRecord(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("mg GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	agent_id := mv.Get("agent_id")
	username := mv.Get("username")
	orderId := mv.Get("orderid")
	videotype := mv.Get("video_type")
	gametype := mv.Get("game_type")
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")
	pagenum, err := utility.StrTo(mv.Get("page_num")).Int64() //当前页
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	page, err := utility.StrTo(mv.Get("page")).Int64() //当前页
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}

	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	/*
		if len(username) > 0 {
			//userinfo, err := mmg.GetUserInfo(username, siteid2)
			//获取用户
			allsiteid := common.Cfg.Section("app").Key("MANAGE_SITEID").MustString("manage")
			if siteid2 != allsiteid {
				userinfo, err := mmg.GetUserInfo(username, siteid2)
				//获取用户
				if err != nil {
					if err == models.ErrNotExist {

						ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
						return

					} else {
						common.Log.Err("mbbin GetUserInfo sql error:", err)
						ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
						return
					}
				}
				username = userinfo.GUserName
			} else {
				username, err = mmg.GetGUserNames(username)
				if err != nil {
					common.Log.Err("mbbin GetUserInfo sql error:", err)
					ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
					return
				}
				username = strings.Trim(username, "|")
			}
		}*/
	betrecords, count, err := mmg.GetBetRecords(username, orderId, siteid2, agent_id, videotype, gametype, fromtime, totime, pagenum, page)
	if err != nil {
		common.Log.Err("AG GetBetRecords sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	} else {
		if count > 0 {
			brdatas := make([]BetRecordsRow, 0)
			brs := BetRecordsData{}
			for _, v := range betrecords {
				brdata := BetRecordsRow{}
				brdata.Account = v.PkUsername
				brdata.GAccount = v.AccountNumber
				brdata.BackMoney = 0.00
				brdata.BetMoney = v.Income
				brdata.ValidBetMoney = v.Income
				brdata.BetType = v.GameType
				brdata.DeskNum = ""
				brdata.GameId = utility.ToStr(v.ModuleId, 10)
				brdata.GameResult = ""
				brdata.Number = ""
				brdata.OrderId = v.BetNo
				brdata.OrderNum = ""
				brdata.OrderTime = v.UpdateTime.Format("2006-01-02 15:04:05")
				brdata.ResultMoney = v.Payout - v.Income
				brdata.Siteid = v.Site_id
				brs.BetMoneyAll = brs.BetMoneyAll + brdata.BetMoney
				brs.ValidBetMoneyAll = brs.BetMoneyAll
				brs.ResultMoneyAll = brs.ResultMoneyAll + brdata.ResultMoney

				brdatas = append(brdatas, brdata)
			}
			brs.Code = 10021
			brs.BackMoneyAll = 0
			brs.BetMoneyAll_, brs.BackMoneyAll_, brs.ResultMoneyAll_, _ = mmg.GetAllMonery(username, orderId, siteid2, agent_id, videotype, gametype, fromtime, totime, pagenum, page)
			brs.ValidBetMoneyAll_ = brs.BetMoneyAll_
			brs.BetRecordsRows = brdatas
			brs.Nums = count
			brs.Page = pagenum
			brs.ThisPage = page

			ctx.JSON(200, JsonResult{Result: true, Data: brs})
			return
		} else {
			ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10022}})
			return
		}
	}
}

func MG_GetAvailableAmountByUser(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("AG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	username := mv.Get("username")
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")
	isdz := (mv.Get("dz") == "1")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	times, bb, wb, err := mmg.GetAvailableAmountByUser(username, siteid2, fromtime, totime, isdz)
	if err != nil {
		common.Log.Err("AG GetAvailableAmountByUser sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	abd := AvailableBetData{}
	abd.BetYC = bb
	abd.BetAll = bb
	abd.BetPC = wb - bb
	abd.BetTimes = times
	abd.Code = 10023
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func MG_GetAvailableAmountBySiteid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("AG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")
	isdz := (mv.Get("dz") == "1")

	if len(siteid2) == 0 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	times, bb, wb, err := mmg.GetAvailableAmountBySiteid(siteid2, fromtime, totime, isdz)
	if err != nil {
		common.Log.Err("AG GetAvailableAmountBySiteid sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	abd := AvailableBetData{}
	abd.BetYC = bb
	abd.BetPC = wb - bb
	abd.BetAll = bb
	abd.BetTimes = times
	abd.Code = 10023
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func MG_GetAvailableAmountByAgentid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("AG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	agentid, err := utility.StrTo(mv.Get("agentid")).Int64()
	if err != nil {
		common.Log.Err("AG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")
	isdz := (mv.Get("dz") == "1")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	times, bb, wb, err := mmg.GetAvailableAmountByAgentid(agentid, siteid2, fromtime, totime, isdz)
	if err != nil {
		common.Log.Err("MG GetAvailableAmountByAgentid sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	abd := AvailableBetData{}
	abd.BetYC = bb
	abd.BetPC = wb - bb
	abd.BetAll = bb
	abd.BetTimes = times
	abd.Code = 10023
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func MG_GetUserAvailableAmountByUser(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("AG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	username := mv.Get("username")
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")
	isdz := (mv.Get("dz") == "1")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	wb, err := mmg.GetUserAvailableAmountByUser(username, siteid2, fromtime, totime, isdz)
	if err != nil {
		common.Log.Err("AG GetAvailableAmountByUser sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	users := make([]UserAvailableBetData, 0)
	for _, v := range wb {
		userd := UserAvailableBetData{}
		userd.UserName = string(v["pkusername"])
		userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
		userd.BetAll = userd.BetYC
		userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
		userd.BetPC = userd.BetPC - userd.BetYC
		userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
		users = append(users, userd)
	}
	abd := UserAvailableBetDatas{}
	abd.Code = 10023
	abd.UserAvailableBetData = users
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func MG_GetUserAvailableAmountBySiteid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("MG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")
	isdz := (mv.Get("dz") == "1")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	wb, err := mmg.GetUserAvailableAmountBySiteid(siteid2, isdz, fromtime, totime)
	if err != nil {
		common.Log.Err("MG GetAvailableAmountBySiteid sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	users := make([]UserAvailableBetData, 0)
	for _, v := range wb {
		userd := UserAvailableBetData{}
		userd.UserName = string(v["pkusername"])
		userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
		userd.BetAll = userd.BetYC
		userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
		userd.BetPC = userd.BetPC - userd.BetYC
		userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
		users = append(users, userd)
	}
	abd := UserAvailableBetDatas{}
	abd.Code = 10023
	abd.UserAvailableBetData = users
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func MG_GetUserAvailableAmountByAgentid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("MG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	agentid := mv.Get("agentid")
	if err != nil {
		common.Log.Err("LEBO GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")
	isdz := (mv.Get("dz") == "1")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	wb, err := mmg.GetUserAvailableAmountByAgentid(agentid, isdz, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("MG GetAvailableAmountByAgentid sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	users := make([]UserAvailableBetData, 0)
	for _, v := range wb {
		userd := UserAvailableBetData{}
		userd.UserName = string(v["pkusername"])
		userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
		userd.BetAll = userd.BetYC
		userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
		userd.BetPC = userd.BetPC - userd.BetYC
		userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
		users = append(users, userd)
	}
	abd := UserAvailableBetDatas{}
	abd.Code = 10023
	abd.UserAvailableBetData = users
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func MG_GetAgentAvailableAmountByAgentid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("MG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	agentid := mv.Get("agentid")
	if err != nil {
		common.Log.Err("LEBO GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")
	isdz := (mv.Get("dz") == "1")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	wb, err := mmg.GetAgentAvailableAmountByAgentid(agentid, isdz, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("MG GetAgentAvailableAmountByAgentid sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	users := make([]AgentAvailableBetData, 0)
	for _, v := range wb {
		userd := AgentAvailableBetData{}
		userd.AgentId = string(v["agent_id"])
		userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
		userd.BetAll = userd.BetYC
		userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
		userd.BetPC = userd.BetPC - userd.BetYC
		userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
		users = append(users, userd)
	}
	abd := AgentAvailableBetDatas{}
	abd.Code = 10023
	abd.AgentAvailableBetData = users
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func MG_ChangeAgentPwd(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("MG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	mgpwd := mv.Get("mgpwd")
	lebopwd := mv.Get("lebopwd")
	if len(mgpwd) > 0 {
		err = models.UpdateAgentPwd(1, siteid2, mgpwd)
	}
	if len(lebopwd) > 0 {
		err = models.UpdateAgentPwd(2, siteid2, lebopwd)
	}
	if err != nil {
		common.Log.Err("MG ChangeAgentPwd error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10026}})
}

//获取网站会员的MG后台，用户名和密码
func MG_GetSiteUserAndPwd(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("MG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	sites, err := models.GetSiteById(siteid2)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}

	mg_siteinfo := struct {
		Code           int    `json:"Code"`
		LoginName      string `json:"loginname"`
		Password       string `json:"password"`
		Mg_url         string `json:"url"`
		Lebo_LoginName string `json:"lebo_loginname"`
		Lebo_Password  string `json:"lebo_password"`
		Lebo_url       string `json:"lebo_url"`
	}{Code: 10025,
		LoginName:      sites.Mg_AgentName,
		Password:       sites.Mg_AgentPWD,
		Mg_url:         "https://www.totalegame.net/login_ch.php",
		Lebo_LoginName: sites.Lebo_AgentName,
		Lebo_Password:  sites.Lebo_AgentPWD,
		Lebo_url:       "https://longtou.jf-game.net/"}
	ctx.JSON(200, JsonResult{Result: true, Data: mg_siteinfo})
}

//游戏记录
func MG_APICasinoProfitByGameTypeReport(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("MG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	if err != nil {
		common.Log.Err("MG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	username := mv.Get("username") //TODO逗号隔开
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")
	groupingtype := mv.Get("groupingtype")
	currencyRateType := mv.Get("currencyRateType")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	mg_g, err := mg.MGameInit(siteid2)
	report, err := mg_g.APICasinoProfitByGameTypeReport(username, fromtime, totime, groupingtype, currencyRateType, 200)
	if err != nil {
		common.Log.Err("MG Execute template:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	if report.Status == "Pending" {
		time.AfterFunc(1*time.Minute, func() {
			//mg_g := mg.MGameInit()
			re, err := mg_g.GetReportResult(report.Id, 1)
			if err != nil {
				common.Log.Err("MG GetReportResult:", err)
			}
			common.Log.Err("MG GetReportResult:", re)
		})
	} else if report.Status == "Complete" {
		//处理
		common.Log.Err("MG GetReportResult:", report)
	} else {
		//返回false
	}
	if err != nil {
		common.Log.Err("MG APICasinoProfitByGameTypeReport err:", err)
	}
	ctx.Echo(200, "OK")
}

////////////////
//会员转账记录
func MG_CreditTransferByAccount(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("MG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	if err != nil {
		common.Log.Err("LEBO GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	username := mv.Get("username")
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")

	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	mg_g, err := mg.MGameInit(siteid2)
	report, err := mg_g.CreditTransferByAccount(username, fromtime, totime, 200)
	if err != nil {
		common.Log.Err("MG Execute template:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	if report.Status == "Pending" {
		time.AfterFunc(1*time.Minute, func() {
			//mg_g := mg.MGameInit()
			re, err := mg_g.GetReportResult(report.Id, 1)
			if err != nil {
				common.Log.Err("MG GetReportResult:", re)
			}
			common.Log.Err("MG GetReportResult:", report)
		})
	} else if report.Status == "Complete" {
		//处理
		common.Log.Err("MG GetReportResult:", report)
	} else {
		//返回false
	}
	if err != nil {
		common.Log.Err("MG APIGetBetInfoDetailsByAccount err:", err)
	}
	ctx.Echo(200, "OK")
}

//会员下注明细
func MG_APIGetBetInfoDetailsByAccount(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("MG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	if err != nil {
		common.Log.Err("MG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	username := mv.Get("username")
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")

	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	mg_g, err := mg.MGameInit(siteid2)
	report, err := mg_g.APIGetBetInfoDetailsByAccount(username, fromtime, totime, 200)
	if err != nil {
		common.Log.Err("MG Execute template:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	if report.Status == "Pending" {
		time.AfterFunc(1*time.Minute, func() {
			//mg_g := mg.MGameInit()
			re, err := mg_g.GetReportResult(report.Id, 1)
			if err != nil {
				common.Log.Err("MG GetReportResult:", err)
			}
			common.Log.Err("MG GetReportResult:", re.CurrentPageData.DiffgrDiffgram.NewDataSet) //输出
		})
	} else if report.Status == "Complete" {
		//处理
		common.Log.Err("MG GetReportResult:", report)
	} else {
		//返回false
	}
	if err != nil {
		common.Log.Err("MG APIGetBetInfoDetailsByAccount err:", err)
	}
	ctx.Echo(200, "OK")
}
