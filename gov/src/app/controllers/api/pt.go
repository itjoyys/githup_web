package api

import (
	"app/models"
	mpt "app/models/pt"
	"common"
	_ "fmt"
	"middleware"
	"plug-ins/games/pt"
	"strings"
	"time"
	"utility"
)

//添加用户
func PT_CheckOrCreateGameAccout(ctx *middleware.Context) {
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
	if len(cur) == 0 {
		cur = "CNY"
	}
	//是否已经纯在
	if mpt.IsUserExist(username, siteid) {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	//判断用户是否存在，如果不存在则创建,存在换名字
	if len(username) >= 12 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10005}})
		return
	}
	user := mpt.User{}
	user.UserName = username
	user.SiteId = siteid2
	user.Cur = cur
	user.IndexId = index_id
	user.AgentId = agent_id
	username = siteid2 + username
	user.Password = mpt.Password()
	if len(username) > 16 {
		username = common.SubString(username, 0, 15)
	}
	b := false
	ii := 0
	for {
		if b || ii > 10 {
			break
		}
		b = mpt.IsGUserNotExist(username)
		if !b {
			username = CreateUserName(username)
		}
		ii++
	}
	pt_g, err := pt.PtInit()
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
		addresult, err := pt_g.CheckOrCreateGameAccout(username, user.Password, cur)
		if err != nil {
			if addresult == false {
				isexist = "0"
			} else {
				common.Log.Err("PT CheckAndCreateAccount sql error：", err)
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
	err = mpt.CreateUser(&user)
	if err != nil {
		common.Log.Err("PT CheckAndCreateAccount sql error：", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10011}})
}

//获取账户余额
func PT_GetBalance(ctx *middleware.Context) {
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
	userinfo, err := mpt.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("PT GetUserInfo:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	pt_g, err := pt.PtInit()
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}

	balance, err := pt_g.GetBalance(userinfo.GUserName)
	if err != nil {
		common.Log.Info("pt GetBalance", err)
		ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10018}})
		return
	}
	ctx.JSON(200, JsonResult{Result: true, Data: BalanceData{Code: 10017, Balance: balance}})
}

//转账
func PT_TransferCredit(ctx *middleware.Context) {
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
	//credit_str := mv.Get("credit")

	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	billno := time.Now().Format("0102150405.000000")
	//去掉小数点
	billno = strings.Replace(billno, ".", "", 10)
	//fmt.Println(billno)
	//ctype := "IN" //OUT
	pt_g, err := pt.PtInit()
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	gcr := new(models.GameCashRecord)
	gcr.Credit = credit
	gcr.Platform = "pt"
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
	userinfo, err := mpt.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("PT GetUserInfo:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	isok, err := pt_g.TransferCredit(userinfo.GUserName, billno, ctype, credit)
	if err == nil {
		if isok {
			gcr.Status = 1
		} else {
			gcr.Status = 0
		}
	} else {
		common.Log.Err("PT TransferCredit ", err)
	}

	err = models.CreateGameCashRecord(gcr)
	if err != nil {
		common.Log.Err("PT TransferCredit ", err)
	}
	if gcr.Status == 0 {
		gcr = nil
		ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10014}})
		return
	}
	gcr = nil
	ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10013}})
}

//正式线路
func PT_ForwardGame(ctx *middleware.Context) {

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
	gamecode := mv.Get("gameid")
	ipaddress := mv.Get("ipaddress")
	lang := mv.Get("lang")
	producttype := mv.Get("producttype")
	pt_g, err := pt.PtInit()
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	//获取用户
	userinfo, err := mpt.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("PT GetUserInfo:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	html, err := pt_g.ForwardGame(userinfo.GUserName, gamecode, ipaddress, lang, producttype)
	if err != nil {
		ctx.Echo(200, err.Error())
		return
	}
	//system maintenance, AGIN platform closed!
	ctx.Echo(200, html)
}

//试玩线路
func PT_ForwardSwGame(ctx *middleware.Context) {

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
	lang := mv.Get("lang")
	currencycode := mv.Get("currencycode")
	gameid := mv.Get("gameid")
	pt_g, err := pt.PtInit()
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	html, err := pt_g.ForwardSwGame(lang, currencycode, gameid)
	if err != nil {
		ctx.Echo(200, err.Error())
		return
	}
	ctx.Echo(200, html)
}

//删除玩家会话
func PT_KillSession(ctx *middleware.Context) {
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
	username := mv.Get("loginname")

	pt_g, err := pt.PtInit()
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	//获取用户
	userinfo, err := mpt.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("PT GetUserInfo:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	ok, err := pt_g.KillSession(userinfo.GUserName)
	if err != nil {
		if ok {
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
}

//修改用户秘密
//EditAccount(name, password) (bool, error)
func PT_EditAccountPwd(ctx *middleware.Context) {
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
	userinfo, err := mpt.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("PT GetUserInfo ", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	pt_g, err := pt.PtInit()
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	isok, err := pt_g.EditAccount(userinfo.GUserName, password)
	if err != nil {
		common.Log.Err("PT EditAccount:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	//修改用户密码
	if isok {
		ccc, err := mpt.ChengUserPwd(username, siteid2, password)
		if ccc {
			//修改成功
			ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10011}})
			return
		} else {
			common.Log.Err("PT ChengUserPwd:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	} else {
		common.Log.Err("PT EditAccount:", "pwd error")
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}

}

func PT_GetBetRecord(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("PT GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	agent_id := mv.Get("agent_id")
	username := mv.Get("username")
	orderId := mv.Get("orderid")
	//gamekind := mv.Get("video_type") //gamekind
	gametype := mv.Get("game_type")
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")
	//fmt.Println(siteid2 + "==" + agent_id + "==" + username + "==" + orderId + "==" + gamekind + "==" + gametype + "==" + fromtime + "==" + totime + "==")
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
	betrecords, count, err := mpt.GetBetRecords(username, orderId, siteid2, agent_id, gametype, fromtime, totime, pagenum, page)
	if err != nil {
		common.Log.Err("PT GetBetRecords sql error:", err)
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
				brdata.BetMoney, _ = utility.StrTo(v.Bet).Float64()
				brdata.ValidBetMoney, _ = utility.StrTo(v.Bet).Float64()
				brdata.BetType = utility.ToStr(v.GameName, 10)
				brdata.DeskNum = v.GameCode
				brdata.GameId = ""
				brdata.GameResult = v.Win
				brdata.Number = v.GameCode
				brdata.OrderId = v.GameCode
				brdata.OrderNum = v.GameCode
				brdata.OrderTime = v.GameDate
				brdata.Siteid = v.Site_id
				brdata.ResultMoney, _ = utility.StrTo(v.Win).Float64()
				brs.BetMoneyAll = brs.BetMoneyAll + brdata.BetMoney
				brs.ValidBetMoneyAll = brs.ValidBetMoneyAll + brdata.ValidBetMoney
				brs.ResultMoneyAll = brs.ResultMoneyAll + brdata.ResultMoney
				brdata.ResultMoney = brdata.ResultMoney - brdata.BetMoney
				brs.ResultMoneyAll = brs.ResultMoneyAll - brdata.BetMoney
				brdatas = append(brdatas, brdata)
			}

			//brs.ResultMoneyAll = brs.ResultMoneyAll + brs.ValidBetMoneyAll //因为输的派彩为负数，所以要加上本金
			brs.Code = 10021
			brs.BackMoneyAll = 0

			brs.BetMoneyAll_, brs.ValidBetMoneyAll_, brs.BackMoneyAll_, brs.ResultMoneyAll_, err = mpt.GetAllMonery(username, orderId, siteid2, agent_id, gametype, fromtime, totime, pagenum, page)
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

func PT_GetAvailableAmountByUser(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("PT GetUserInfo xml error:", err)
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

	times, vtimes, all_bb, _, vb, wb, err := mpt.GetAvailableAmountByUser(username, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("Pt GetAvailableAmountByUser sql error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10024}})
		return
	}
	abd := AllBetData{}
	abd.BetAll = all_bb
	abd.BetAllCount = times
	abd.BetYC = vb
	abd.BetPC = wb //盈利=
	//if abd.BetPC > 0 {
	abd.BetPC = wb - all_bb
	//}
	abd.BetVTimes = vtimes
	abd.Code = 10023
	ctx.JSON(200, JsonResult{Result: false, Data: abd})
}
