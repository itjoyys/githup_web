package api

import (
	_ "fmt"

	"strings"
	"time"

	"app/models"
	mag "app/models/ag"
	"common"
	"middleware"
	"plug-ins/games/ag"
	"utility"
)

//添加用户
func AG_CheckOrCreateGameAccout(ctx *middleware.Context) {
	//获取参数
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
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
	if mag.IsUserExist(username, siteid) {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	//判断用户是否存在，如果不存在则创建,存在换名字
	if len(username) >= 12 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10005}})
		return
	}
	user := mag.User{}
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
		b = mag.IsGUserNotExist(username)
		if !b {
			username = CreateUserName(username)
		}
		ii++
	}
	ag_g := ag.AsiaGamingInit()
	//原则用户是否存在
	isexist := "0"
	ii = 0
	for {
		if isexist == "1" || ii > 10 {
			break
		}
		has, err := ag_g.CheckOrCreateGameAccout(username, mag.Password, "", "1", "")
		if has == true && err == nil {
			isexist = "1"
		} else if has == false && err == nil {
			isexist = "0"
		} else {
			isexist = "9"
			common.Log.Err("AG CheckOrCreateGameAccout:", err)
			break
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
	err = mag.CreateUser(&user)
	if err != nil {
		common.Log.Info("AG CheckAndCreateAccount sql error：", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10011}})

	/*
		isexist, err := ag_g.CheckOrCreateGameAccout(username, password, "", "", "")

		common.Log.Err(isexist, err)

		ctx.Echo(200, "OK")
	*/
}

//添加用户
func AG_GetBalance(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	username := mv.Get("username")

	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	//获取用户
	userinfo, err := mag.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("AG GetUserInfo:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	ag_g := ag.AsiaGamingInit()

	balance, err := ag_g.GetBalance(userinfo.GUserName, userinfo.Password, "1", "")
	if err != nil {
		common.Log.Info("AG GetBalance", err)
		ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10018}})
		return
	}
	ctx.JSON(200, JsonResult{Result: true, Data: BalanceData{Code: 10017, Balance: balance}})
}

func AG_TransferCredit(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	username := mv.Get("username")
	ctype := mv.Get("type")
	credit, err := utility.StrTo(mv.Get("credit")).Float64()
	if err != nil {
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
	//fmt.Println(billno)
	//ctype := "IN" //OUT
	ag_g := ag.AsiaGamingInit()
	gcr := new(models.GameCashRecord)
	gcr.Credit = credit
	gcr.Platform = "ag"
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
	gcr.Info = ""
	//获取用户密码
	userinfo, err := mag.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("AG GetUserInfo:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	isok, err := ag_g.PrepareTransferCredit(userinfo.GUserName, userinfo.Password, billno, credit, ctype, "1", "")
	//PrepareTransferCredit(userinfo.GUserName, userinfo.Password, billno, ctype, credit_str)
	if err != nil {
		common.Log.Err("AG PrepareTransferCredit:", err)
		gcr.Info += err.Error()
		gcr.Status = 0
	}
	isok, err = ag_g.TransferCreditConfirm(userinfo.GUserName, userinfo.Password, billno, credit, ctype, "1", "", isok)
	if isok && err == nil {
		//本地添加一条转账记录
		gcr.Status = 1
	} else {
		gcr.Info += err.Error()
		common.Log.Err("AG ConfirmTransferCredit:", err)
		gcr.Status = 0
	}
	err = models.CreateGameCashRecord(gcr)
	if err != nil {
		gcr.Info += err.Error()
		common.Log.Err("AG TransferCredit ", err)
	}
	if gcr.Status == 0 {
		gcr = nil
		ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10014}})
		return
	}
	gcr = nil
	ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10013}})
}

func AG_ForwardGame(ctx *middleware.Context) {

	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	username := mv.Get("username")
	limit := mv.Get("limit")
	//11 HTML5 大厅
	gametype := mv.Get("gametype")
	if gametype != "11" {
		gametype = "0"
	}
	//lang := mv.Get("lang")
	//cur := mv.Get("cur")

	siteconfig, err := models.GetSiteById(siteid2)
	if err != nil {
		common.Log.Err("OG GetSiteById:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	//获取用户
	userinfo, err := mag.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("AG GetUserInfo:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}

	ag_g := ag.AsiaGamingInit()
	html, err := ag_g.ForwardGame(userinfo.GUserName, userinfo.Password, siteconfig.SiteDomain, gametype, "", "", limit, "")
	if err != nil {
		ctx.Echo(200, err.Error())
		return
	}
	//system maintenance, AGIN platform closed!
	ctx.Echo(200, html)
}

func AG_ForwardDz(ctx *middleware.Context) {
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
	siteconfig, err := models.GetSiteById(siteid2)
	if err != nil {
		common.Log.Err("OG GetSiteById:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	//获取用户
	userinfo, err := mag.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("AG GetUserInfo ", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	ag_g := ag.AsiaGamingInit()
	html, err := ag_g.ForwardGameDz(userinfo.GUserName, userinfo.Password, siteconfig.SiteDomain, gameid, "", "", "", "")
	if err != nil {
		ctx.Echo(200, err.Error())
		return
	}
	//system maintenance, AGIN platform closed!
	ctx.Echo(200, html)
}

/**
'OrderId='+OrderId+'&
Company='+Company+'&
VideoType='+VideoType+'&
S_Time='+s_time+'&
E_Time='+e_time+'&
UserName='+username+'&
Page_Num='+page_num+'&
Page='+Page;
*/
func AG_GetBetRecord(ctx *middleware.Context) {
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
	agent_id := mv.Get("agent_id")
	username := mv.Get("username")
	orderId := mv.Get("orderid")
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")
	videotype := mv.Get("video_type")
	gametype := mv.Get("game_type")
	/*
		SeachVideoGameSelect 大分类
		空为视讯和电子  video视讯 game 电子
		----
		VideoType 视讯分类 值为空时 为所有
		GameType 电子分类
	*/

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
			allsiteid := common.Cfg.Section("app").Key("MANAGE_SITEID").MustString("manage")
			if siteid2 != allsiteid {
				userinfo, err := mag.GetUserInfo(username, siteid2)
				//获取用户
				if err != nil {
					if err == models.ErrNotExist {

						ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
						return

					} else {
						common.Log.Err("AG GetUserInfo sql error:", err)
						ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
						return
					}
				}
				username = userinfo.GUserName
			} else {
				username, err = mag.GetGUserNames(username)
				if err != nil {
					common.Log.Err("AG GetUserInfo sql error:", err)
					ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
					return
				}
				username = strings.Trim(username, "|")
			}
		}
	*/
	betrecords, count, err := mag.GetBetRecords(username, orderId, siteid2, agent_id, videotype, gametype, fromtime, totime, pagenum, page)
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
				brdata.GAccount = v.PlayerName
				brdata.BackMoney = 0.00
				brdata.BetMoney = v.BetAmount
				brdata.ValidBetMoney = v.ValidBetAmount
				brdata.BetType = v.GameType
				brdata.DeskNum = v.TableCode
				brdata.GameId = v.GameType
				brdata.GameResult = v.Result
				brdata.Number = ""
				brdata.OrderId = v.BillNo
				brdata.OrderNum = v.GameCode
				brdata.OrderTime = v.BetTime
				brdata.ResultMoney = v.NetAmount
				brdata.Siteid = v.Site_id
				brs.BetMoneyAll = brs.BetMoneyAll + brdata.BetMoney
				brs.ValidBetMoneyAll = brs.ValidBetMoneyAll + brdata.ValidBetMoney
				brs.ResultMoneyAll += brdata.ResultMoney
				brdatas = append(brdatas, brdata)
			}
			//brs.ResultMoneyAll = brs.ResultMoneyAll + brs.ValidBetMoneyAll //因为输的派彩为负数，所以要加上本金
			brs.Code = 10021
			brs.BackMoneyAll = 0

			brs.BetMoneyAll_, brs.ValidBetMoneyAll_, brs.BackMoneyAll_, brs.ResultMoneyAll_, _ = mag.GetAllMonery(username, orderId, siteid2, agent_id, videotype, gametype, fromtime, totime, pagenum, page)

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

func AG_GetAvailableAmountByUser(ctx *middleware.Context) {
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

	times, vtimes, all_bb, _, vb, wb, err := mag.GetAvailableAmountByUser(username, siteid2, isdz, fromtime, totime)
	if err != nil {
		common.Log.Err("AG GetAvailableAmountByUser sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	abd := AllBetData{}
	abd.BetAll = all_bb
	abd.BetAllCount = times
	abd.BetYC = vb
	abd.BetPC = wb
	abd.BetVTimes = vtimes
	abd.Code = 10023
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func AG_GetAvailableAmountBySiteid(ctx *middleware.Context) {
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

	times, bb, vb, wb, err := mag.GetAvailableAmountBySiteid(siteid2, isdz, fromtime, totime)
	if err != nil {
		common.Log.Err("AG GetAvailableAmountBySiteid sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	abd := AvailableBetData{}
	abd.BetYC = vb
	abd.BetAll = bb
	abd.BetPC = wb
	abd.BetTimes = times
	abd.Code = 10023
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func AG_GetAvailableAmountByAgentid(ctx *middleware.Context) {
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

	times, bb, vb, wb, err := mag.GetAvailableAmountByAgentid(agentid, isdz, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("AG GetAvailableAmountByAgentid sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	abd := AvailableBetData{}
	abd.BetYC = vb
	abd.BetAll = bb
	abd.BetPC = wb
	abd.BetTimes = times
	abd.Code = 10023
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func AG_GetUserAvailableAmountByUser(ctx *middleware.Context) {
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
	notbrokerage := (mv.Get("notbk") == "1")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	wb, err := mag.GetUserAvailableAmountByUser(username, siteid2, isdz, fromtime, totime, notbrokerage)
	if err != nil {
		common.Log.Err("AG GetAvailableAmountByUser sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	users := make([]UserAvailableBetData, 0)
	for _, v := range wb {
		userd := UserAvailableBetData{}
		userd.UserName = string(v["account_number"])
		userd.BetAll, _ = utility.StrTo(v["bb"]).Float64()
		userd.BetYC, _ = utility.StrTo(v["vb"]).Float64()
		userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
		userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
		users = append(users, userd)
	}
	abd := UserAvailableBetDatas{}
	abd.Code = 10023
	abd.UserAvailableBetData = users
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func AG_GetUserAvailableAmountBySiteid(ctx *middleware.Context) {
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
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	wb, err := mag.GetUserAvailableAmountBySiteid(siteid2, isdz, fromtime, totime)
	if err != nil {
		common.Log.Err("AG GetAvailableAmountBySiteid sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	users := make([]UserAvailableBetData, 0)
	for _, v := range wb {
		userd := UserAvailableBetData{}
		userd.UserName = string(v["account_number"])
		userd.BetAll, _ = utility.StrTo(v["bb"]).Float64()
		userd.BetYC, _ = utility.StrTo(v["vb"]).Float64()
		userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
		userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
		users = append(users, userd)
	}
	abd := UserAvailableBetDatas{}
	abd.Code = 10023
	abd.UserAvailableBetData = users
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func AG_GetUserAvailableAmountByAgentid(ctx *middleware.Context) {
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
	agentid := mv.Get("agentid")
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

	wb, err := mag.GetUserAvailableAmountByAgentid(agentid, isdz, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("AG GetAvailableAmountByAgentid sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	users := make([]UserAvailableBetData, 0)
	for _, v := range wb {
		userd := UserAvailableBetData{}
		userd.UserName = string(v["account_number"])
		userd.BetAll, _ = utility.StrTo(v["bb"]).Float64()
		userd.BetYC, _ = utility.StrTo(v["vb"]).Float64()
		userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
		userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
		users = append(users, userd)
	}
	abd := UserAvailableBetDatas{}
	abd.Code = 10023
	abd.UserAvailableBetData = users
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func AG_GetAgentAvailableAmountByAgentid(ctx *middleware.Context) {
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
	agentid := mv.Get("agentid")
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

	wb, err := mag.GetAgentAvailableAmountByAgentid(agentid, isdz, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("AG GetAvailableAmountByAgentid sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	users := make([]AgentAvailableBetData, 0)
	for _, v := range wb {
		userd := AgentAvailableBetData{}
		userd.AgentId = string(v["agent_id"])
		userd.BetAll, _ = utility.StrTo(v["bb"]).Float64()
		userd.BetYC, _ = utility.StrTo(v["vb"]).Float64()
		userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
		userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
		users = append(users, userd)
	}
	abd := AgentAvailableBetDatas{}
	abd.Code = 10023
	abd.AgentAvailableBetData = users
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}
