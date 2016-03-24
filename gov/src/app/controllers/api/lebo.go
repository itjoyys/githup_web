package api

import (
	"strings"
	"time"

	"app/models"
	mlebo "app/models/lebo"
	"common"
	"middleware"
	"plug-ins/games/lebo"
	"utility"
)

//添加用户
func LEBO_CheckOrCreateGameAccout(ctx *middleware.Context) {
	//获取参数
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
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
	if mlebo.IsUserExist(username, siteid) {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	//判断用户是否存在，如果不存在则创建,存在换名字
	if len(username) >= 12 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10005}})
		return
	}
	user := mlebo.User{}
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
		b = mlebo.IsGUserNotExist(username)
		if !b {
			username = CreateUserName(username)
		}
		ii++
	}
	lebo_g, err := lebo.LeboTechnologyInit(siteid2)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	//原则用户是否存在
	login_url, err := lebo_g.Getloginurl(username, "", cur, "", 0)
	if err != nil {
		common.Log.Err("CT CheckAndCreateAccount xml error：", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10012}})
		return
	}

	if len(login_url) > 0 {
		user.GUserName = username
		err = mlebo.CreateUser(&user)
		if err != nil {
			common.Log.Info("og CheckAndCreateAccount sql error：", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
		ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10011}})
		return
	}

	ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
}

//添加用户
func LEBO_GetBalance(ctx *middleware.Context) {

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
	userinfo, err := mlebo.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("CT GetUserInfo sql err:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}

	lebo_g, err := lebo.LeboTechnologyInit(siteid2)
	ctuserinfo, err := lebo_g.Getuserinfo(userinfo.GUserName)
	if err != nil {
		common.Log.Info("CT GetBalance", err)
		ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10018}})
		return
	}
	ctx.JSON(200, JsonResult{Result: true, Data: BalanceData{Code: 10017, Balance: ctuserinfo.Balance}})
}

func LEBO_TransferCredit(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
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

	gcr := new(models.GameCashRecord)
	gcr.Credit = credit
	gcr.Platform = "lebo"
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
	userinfo, err := mlebo.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("LEBO GetUserInfo sql err:", err)
			gcr = nil
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	lebo_g, err := lebo.LeboTechnologyInit(siteid2)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	if gcr.CashType == 1 {
		bc, err := lebo_g.TransferCreditIN(userinfo.GUserName, credit)
		if err == nil && bc.Amount > 0 {
			//本地添加一条转账记录
			gcr.Status = 1
			gcr.TransactionId = bc.Ordernum
		} else {
			gcr.Info += err.Error()
			gcr.Status = 0
		}
	} else if gcr.CashType == 2 {
		bc, err := lebo_g.TransferCreditOUT(userinfo.GUserName, credit)
		if err == nil && bc.Amount > 0 {
			//本地添加一条转账记录
			gcr.Status = 1
			gcr.TransactionId = bc.Ordernum
		} else {
			gcr.Info += err.Error()
			gcr.Status = 0
		}
	}
	err = models.CreateGameCashRecord(gcr)
	if err != nil {
		common.Log.Err("LEBO TransferCredit ", err)
	}
	if gcr.Status == 0 {
		gcr = nil
		ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10014}})
		return
	}
	gcr = nil
	ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10013}})
}

func LEBO_ForwardGame(ctx *middleware.Context) {
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
	userinfo, err := mlebo.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("CT GetUserInfo sql err:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	lebo_g, err := lebo.LeboTechnologyInit(siteid2)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	login_url, err := lebo_g.Getloginurl(userinfo.GUserName, "", userinfo.Cur, "", 0)
	ctx.Echo(200, login_url)
}

func LEBO_GetBetRecord(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("LEBO GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	agent_id := mv.Get("agent_id")
	username := mv.Get("username")
	orderId := mv.Get("orderid")
	videotype := mv.Get("video_type")
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
		//userinfo, err := mlebo.GetUserInfo(username, siteid2)
		allsiteid := common.Cfg.Section("app").Key("MANAGE_SITEID").MustString("manage")
		if siteid2 != allsiteid {
			userinfo, err := mlebo.GetUserInfo(username, siteid2)
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
			username, err = mlebo.GetGUserNames(username)
			if err != nil {
				common.Log.Err("mbbin GetUserInfo sql error:", err)
				ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
				return
			}
			username = strings.Trim(username, "|")
		}
	}*/
	betrecords, count, err := mlebo.GetBetRecords(username, orderId, siteid2, agent_id, videotype, fromtime, totime, pagenum, page)
	if err != nil {
		common.Log.Err("lebo GetBetRecords sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	} else {
		if count > 0 {
			brdatas := make([]BetRecordsRow, 0)
			brs := BetRecordsData{}
			for _, v := range betrecords {
				brdata := BetRecordsRow{}
				brdata.Account = v.PkUsername
				brdata.GAccount = v.Member
				brdata.BackMoney = 0.00
				brdata.BetMoney = v.BetAmount
				brdata.ValidBetMoney = v.ValidBetAmount
				brdata.BetType = v.GameType
				brdata.DeskNum = v.TableID
				brdata.GameId = v.GameType
				brdata.GameResult = v.GameResult
				brdata.Number = ""
				brdata.OrderId = v.Gameid
				brdata.OrderNum = ""
				brdata.OrderTime = v.Betstarttime
				brdata.ResultMoney = v.PayOut
				brdata.Siteid = v.Site_id
				brs.BetMoneyAll = brs.BetMoneyAll + brdata.BetMoney
				brs.ValidBetMoneyAll = brs.ValidBetMoneyAll + brdata.ValidBetMoney

				brs.ResultMoneyAll = brs.ResultMoneyAll + brdata.ResultMoney

				brdatas = append(brdatas, brdata)
			}
			//brs.ResultMoneyAll = brs.ResultMoneyAll + brs.ValidBetMoneyAll //因为输的派彩为负数，所以要加上本金
			brs.Code = 10021
			brs.BackMoneyAll = 0

			brs.BetMoneyAll_, brs.ValidBetMoneyAll_, brs.BackMoneyAll_, brs.ResultMoneyAll_, _ = mlebo.GetAllMonery(username, orderId, siteid2, agent_id, videotype, fromtime, totime, pagenum, page)
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

func LEBO_GetAvailableAmountByUser(ctx *middleware.Context) {
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

	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	times, vtimes, all_bb, _, vb, wb, err := mlebo.GetAvailableAmountByUser(username, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("LEBO GetAvailableAmountByUser sql error:", err)
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

func LEBO_GetAvailableAmountBySiteid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("LEBO GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")

	if len(siteid2) == 0 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	times, bb, vb, wb, err := mlebo.GetAvailableAmountBySiteid(siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("LEBO GetAvailableAmountBySiteid sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	abd := AvailableBetData{}
	abd.BetAll = bb
	abd.BetYC = vb
	abd.BetPC = wb
	abd.BetTimes = times
	abd.Code = 10023
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func LEBO_GetAvailableAmountByAgentid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("LEBO GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	agentid, err := utility.StrTo(mv.Get("agentid")).Int64()
	if err != nil {
		common.Log.Err("LEBO GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")

	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	times, bb, vb, wb, err := mlebo.GetAvailableAmountByAgentid(agentid, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("LEBO GetAvailableAmountByAgentid sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	abd := AvailableBetData{}
	abd.BetAll = bb
	abd.BetYC = vb
	abd.BetPC = wb
	abd.BetTimes = times
	abd.Code = 10023
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func LEBO_GetUserAvailableAmountByUser(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("LEBO GetUserInfo xml error:", err)
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

	wb, err := mlebo.GetUserAvailableAmountByUser(username, siteid2, fromtime, totime, isdz, notbrokerage)
	if err != nil {
		common.Log.Err("LEBO GetAvailableAmountByUser sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	users := make([]UserAvailableBetData, 0)
	for _, v := range wb {
		userd := UserAvailableBetData{}
		userd.UserName = string(v["account_number"])
		userd.BetYC, _ = utility.StrTo(v["vb"]).Float64()
		userd.BetAll, _ = utility.StrTo(v["bb"]).Float64()
		userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
		userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
		users = append(users, userd)
	}
	abd := UserAvailableBetDatas{}
	abd.Code = 10023
	abd.UserAvailableBetData = users
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func LEBO_GetUserAvailableAmountBySiteid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("LEBO GetUserInfo xml error:", err)
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

	wb, err := mlebo.GetUserAvailableAmountBySiteid(siteid2, isdz, fromtime, totime)
	if err != nil {
		common.Log.Err("LEBO GetAvailableAmountBySiteid sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	users := make([]UserAvailableBetData, 0)
	for _, v := range wb {
		userd := UserAvailableBetData{}
		userd.UserName = string(v["account_number"])
		userd.BetYC, _ = utility.StrTo(v["vb"]).Float64()
		userd.BetAll, _ = utility.StrTo(v["bb"]).Float64()
		userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
		userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
		users = append(users, userd)
	}
	abd := UserAvailableBetDatas{}
	abd.Code = 10023
	abd.UserAvailableBetData = users
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func LEBO_GetUserAvailableAmountByAgentid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("LEBO GetUserInfo xml error:", err)
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

	wb, err := mlebo.GetUserAvailableAmountByAgentid(agentid, isdz, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("LEBO GetAvailableAmountByAgentid sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	users := make([]UserAvailableBetData, 0)
	for _, v := range wb {
		userd := UserAvailableBetData{}
		userd.UserName = string(v["account_number"])
		userd.BetYC, _ = utility.StrTo(v["vb"]).Float64()
		userd.BetAll, _ = utility.StrTo(v["bb"]).Float64()
		userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
		userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
		users = append(users, userd)
	}
	abd := UserAvailableBetDatas{}
	abd.Code = 10023
	abd.UserAvailableBetData = users
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func LEBO_GetAgentAvailableAmountByAgentid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("LEBO GetUserInfo xml error:", err)
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

	wb, err := mlebo.GetAgentAvailableAmountByAgentid(agentid, isdz, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("LEBO GetAvailableAmountByAgentid sql error:", err)
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
