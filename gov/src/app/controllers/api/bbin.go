package api

import (
	_ "fmt"
	"strings"
	"time"

	"app/models"
	mbbin "app/models/bbin"
	"common"
	"middleware"
	"plug-ins/games/bbin"
	"utility"
)

//添加用户
func CreateMember(ctx *middleware.Context) {
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
	index_id := mv.Get("index_id")
	if len(index_id) == 0 {
		index_id = "a"
	}
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	//是否已经纯在
	if mbbin.IsUserExist(username, siteid) {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	//判断用户是否存在，如果不存在则创建,存在换名字
	if len(username) >= 12 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10005}})
		return
	}
	user := mbbin.User{}
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
		b = mbbin.IsGUserNotExist(username)
		if !b {
			username = CreateUserName(username)
		}
		ii++
	}
	bbin_g, err := bbin.BbinInit(siteid2)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	//原则用户是否存在
	isexist := ""
	ii = 0
	for {
		if isexist == "21100" || ii > 10 {
			break
		}
		isexist, _ = bbin_g.CreateMember(username, mbbin.Password)
		if isexist != "21100" {
			if isexist != "21001" {
				ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10012}})
				return
			}
		}
		if isexist != "21100" {
			username = CreateUserName(username)
		}
		ii++
	}
	if isexist != "21100" {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10012}})
		return
	}
	user.GUserName = username
	err = mbbin.CreateUser(&user)
	if err != nil {
		common.Log.Info("bbin CheckAndCreateAccount sql error：", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10011}})
}

//获取账户余额
func Bbin_GetBalance(ctx *middleware.Context) {
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
	userinfo, err := mbbin.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("bbin GetUserInfo:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	bbin_g, err := bbin.BbinInit(siteid2)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}

	balance, err := bbin_g.CheckUsrBalance(userinfo.GUserName, 0, 0)
	if err != nil {
		common.Log.Info("bbin GetBalance", err)
		ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10018}})
		return
	}

	ctx.JSON(200, JsonResult{Result: true, Data: BalanceData{Code: 10017, Balance: balance.TotalBalance}})
}

//转账
func Bbin_TransferCredit(ctx *middleware.Context) {
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
	//fmt.Println(billno)
	//ctype := "IN" //OUT
	bbin_g, err := bbin.BbinInit(siteid2)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	gcr := new(models.GameCashRecord)
	gcr.Credit = credit
	gcr.Platform = "bbin"
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
	userinfo, err := mbbin.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("bbin GetUserInfo:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	//action string, remitno int64, remit float64
	err = bbin_g.Transfer(userinfo.GUserName, ctype, billno, credit)
	if err != nil {
		gcr.Info += err.Error()
		common.Log.Err("bbin Transfer:", err)
		gcr.Status = 0
	} else {
		gcr.Status = 1
	}

	err = models.CreateGameCashRecord(gcr)
	if err != nil {
		gcr.Info += err.Error()
		common.Log.Err("bbin TransferCredit ", err)
	}
	if gcr.Status == 0 {
		gcr = nil
		ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10014}})
		return
	}
	gcr = nil
	ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10013}})
}

//进入游戏
func Bbin_TransferGame(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	siteid2 := mv.Get("siteid")
	username := mv.Get("username")
	gametype := mv.Get("gametype")
	lang := mv.Get("lang")
	//cur := mv.Get("cur")

	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	//获取用户
	userinfo, err := mbbin.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("bbin GetUserInfo:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}

	bbin_g, err := bbin.BbinInit(siteid2)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	html := bbin_g.Login(userinfo.GUserName, userinfo.Password, lang, gametype)

	ctx.Echo(200, html)
}

func Bbin_GetBetRecord(ctx *middleware.Context) {
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
	gamekind := mv.Get("video_type") //gamekind
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
	betrecords, count, err := mbbin.GetBetRecords(username, orderId, siteid2, agent_id, gamekind, gametype, fromtime, totime, pagenum, page)
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
				brdata.GAccount = v.UserName
				brdata.BackMoney = 0.00
				brdata.BetMoney, _ = utility.StrTo(v.BetAmount).Float64()
				brdata.ValidBetMoney, _ = utility.StrTo(v.Commissionable).Float64()
				brdata.BetType = utility.ToStr(v.Gamekind, 10)
				brdata.DeskNum = v.GameCode
				brdata.GameId = ""
				brdata.GameResult = v.Result
				brdata.Number = v.WagersID
				brdata.OrderId = v.WagersID
				brdata.OrderNum = v.SerialID
				brdata.OrderTime = v.WagersDate
				brdata.Siteid = v.Site_id
				brdata.ResultMoney, _ = utility.StrTo(v.Payoff).Float64()
				brs.BetMoneyAll = brs.BetMoneyAll + brdata.BetMoney
				brs.ValidBetMoneyAll = brs.ValidBetMoneyAll + brdata.ValidBetMoney
				brs.ResultMoneyAll = brs.ResultMoneyAll + brdata.ResultMoney
				brdatas = append(brdatas, brdata)
			}

			//brs.ResultMoneyAll = brs.ResultMoneyAll + brs.ValidBetMoneyAll //因为输的派彩为负数，所以要加上本金
			brs.Code = 10021
			brs.BackMoneyAll = 0

			brs.BetMoneyAll_, brs.ValidBetMoneyAll_, brs.BackMoneyAll_, brs.ResultMoneyAll_, err = mbbin.GetAllMonery(username, orderId, siteid2, agent_id, gamekind, gametype, fromtime, totime, pagenum, page)
			if err != nil {
				common.Log.Err("bbin GetBetRecords all money sql error:", err)
			}
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

func Bbin_GetAvailableAmountByUser(ctx *middleware.Context) {
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

	times, vtimes, all_bb, _, vb, wb, err := mbbin.GetAvailableAmountByUser(username, siteid2, fromtime, totime)
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

func Bbin_GetAvailableAmountBySiteid(ctx *middleware.Context) {
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

	if len(siteid2) == 0 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	times, bb, vb, wb, err := mbbin.GetAvailableAmountBySiteid(siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("AG GetAvailableAmountBySiteid sql error:", err)
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

func Bbin_GetAvailableAmountByAgentid(ctx *middleware.Context) {
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

	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	times, bb, vb, wb, err := mbbin.GetAvailableAmountByAgentid(agentid, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("AG GetAvailableAmountByAgentid sql error:", err)
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

func Bbin_GetUserAvailableAmountByUser(ctx *middleware.Context) {
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
	isdz := mv.Get("dz")
	notbrokerage := (mv.Get("notbk") == "1")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	wb, err := mbbin.GetUserAvailableAmountByUser(username, siteid2, fromtime, totime, isdz, notbrokerage)
	if err != nil {
		common.Log.Err("AG GetAvailableAmountByUser sql error:", err)
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

func Bbin_GetUserAvailableAmountBySiteid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("OG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")
	isdz := mv.Get("dz")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	wb, err := mbbin.GetUserAvailableAmountBySiteid(siteid2, isdz, fromtime, totime)
	if err != nil {
		common.Log.Err("OG GetAvailableAmountBySiteid sql error:", err)
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

func Bbin_GetUserAvailableAmountByAgentid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("OG GetUserInfo xml error:", err)
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
	isdz := mv.Get("dz")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	wb, err := mbbin.GetUserAvailableAmountByAgentid(agentid, isdz, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("OG GetAvailableAmountByAgentid sql error:", err)
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

func Bbin_GetAgentAvailableAmountByAgentid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("OG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	agentid := mv.Get("agentid")
	if err != nil {
		common.Log.Err("bbin GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")
	isdz := mv.Get("dz")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	wb, err := mbbin.GetAgentAvailableAmountByAgentid(agentid, isdz, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("OG GetAgentAvailableAmountByAgentid sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	users := make([]AgentAvailableBetData, 0)
	for _, v := range wb {
		userd := AgentAvailableBetData{}
		userd.AgentId = string(v["agent_id"])
		userd.BetYC, _ = utility.StrTo(v["vb"]).Float64()
		userd.BetAll, _ = utility.StrTo(v["bb"]).Float64()
		userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
		userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
		users = append(users, userd)
	}
	abd := AgentAvailableBetDatas{}
	abd.Code = 10023
	abd.AgentAvailableBetData = users
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

//修改用户秘密
//EditAccount(name, password) (bool, error)
func BBIN_EditAccountPwd(ctx *middleware.Context) {
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
	userinfo, err := mbbin.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("BBIN GetUserInfo ", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	//修改用户密码
	ccc, err := mbbin.ChengUserPwd(userinfo.UserName, siteid2, password)
	if ccc {
		//修改成功
		ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10011}})
		return
	} else {
		common.Log.Err("BBIN ChengUserPwd:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
}
