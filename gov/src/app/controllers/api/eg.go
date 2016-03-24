package api

import (
	_ "fmt"
	"strings"
//"time"

	"app/models"
	mdz "app/models/eg"
	"common"
	"middleware"
	"plug-ins/games/eg"
	"utility"
)

//添加用户
func Eg_CreateMember(ctx *middleware.Context) {
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
	shiwan := mv.Get("sw")
	if len(index_id) == 0 {
		index_id = "a"
	}
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	//是否已经纯在
	if mdz.IsUserExist(username, siteid) {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	//判断用户是否存在，如果不存在则创建,存在换名字
	if len(username) >= 12 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10005}})
		return
	}
	user := mdz.User{}
	user.UserName = username
	user.SiteId = siteid2
	user.Cur = cur
	user.IndexId = index_id
	user.AgentId = agent_id
	user.Password = mdz.Password()
	username = siteid2 + "_" + mv.Get("agent_id") + "_" + username
	if len(username) > 50 {
		username = common.SubString(username, 0, 49)
	}
	b := false
	ii := 0
	for {
		if b || ii > 10 {
			break
		}
		b = mdz.IsGUserNotExist(username)
		if !b {
			username = CreateUserName(username)
		}
		ii++
	}
	dz_g, err := eg.LocalDzInit(siteid2, shiwan)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	//原则用户是否存在
	isexist := -3
	ii = 0
	for {
		if isexist == 1 || ii > 10 {
			break
		}
		isexist, _ = dz_g.CreateMember(username, user.Password)
		if isexist != 1 {
			if isexist != -3 {
				ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10012}})
				return
			}
		} //重名
		if isexist == -3 {
			username = CreateUserName(username)
		}
		ii++
	}
	if isexist != 1 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10012}})
		return
	}
	user.GUserName = username
	err = mdz.CreateUser(&user)
	if err != nil {
		common.Log.Info("dz CheckAndCreateAccount sql error：", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10011}})
}

//进入游戏
func Eg_TransferGame(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	siteid2 := mv.Get("siteid")
	username := mv.Get("username")
	gameid := mv.Get("gameid")
	shiwan := mv.Get("sw")
	//cur := mv.Get("cur")

	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	//获取用户
	userinfo, err := mdz.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("dz GetUserInfo:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	dz_g, err := eg.LocalDzInit(siteid2, shiwan)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	html := dz_g.Login(userinfo.GUserName, userinfo.Password, gameid)

	ctx.Echo(200, html)
}


func Eg_GetBetRecord(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("EG GetUserInfo xml error:", err)
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
	betrecords, count, err := mdz.GetBetRecords(username, orderId, siteid2, agent_id, videotype, fromtime, totime, pagenum, page)
	if err != nil {
		common.Log.Err("EG GetBetRecords sql error:", err)
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

			brs.BetMoneyAll_, brs.ValidBetMoneyAll_, brs.BackMoneyAll_, brs.ResultMoneyAll_, _ = mdz.GetAllMonery(username, orderId, siteid2, agent_id, videotype, fromtime, totime, pagenum, page)
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

func Eg_GetAvailableAmountByUser(ctx *middleware.Context) {
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

	times, vtimes, all_bb, _, vb, wb, err := mdz.GetAvailableAmountByUser(username, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("EG GetAvailableAmountByUser sql error:", err)
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

func Eg_GetAvailableAmountBySiteid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("EG GetUserInfo xml error:", err)
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

	times, bb, vb, wb, err := mdz.GetAvailableAmountBySiteid(siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("EG GetAvailableAmountBySiteid sql error:", err)
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

func Eg_GetAvailableAmountByAgentid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("EG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	agentid, err := utility.StrTo(mv.Get("agentid")).Int64()
	if err != nil {
		common.Log.Err("EG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")

	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	times, bb, vb, wb, err := mdz.GetAvailableAmountByAgentid(agentid, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("EG GetAvailableAmountByAgentid sql error:", err)
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

func Eg_GetUserAvailableAmountByUser(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("EG GetUserInfo xml error:", err)
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

	wb, err := mdz.GetUserAvailableAmountByUser(username, siteid2, fromtime, totime, isdz, notbrokerage)
	if err != nil {
		common.Log.Err("EG GetAvailableAmountByUser sql error:", err)
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

func Eg_GetUserAvailableAmountBySiteid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("EG GetUserInfo xml error:", err)
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

	wb, err := mdz.GetUserAvailableAmountBySiteid(siteid2, isdz, fromtime, totime)
	if err != nil {
		common.Log.Err("EG GetAvailableAmountBySiteid sql error:", err)
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

func Eg_GetUserAvailableAmountByAgentid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("EG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	agentid := mv.Get("agentid")
	if err != nil {
		common.Log.Err("EG GetUserInfo xml error:", err)
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

	wb, err := mdz.GetUserAvailableAmountByAgentid(agentid, isdz, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("EG GetAvailableAmountByAgentid sql error:", err)
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

func Eg_GetAgentAvailableAmountByAgentid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("EG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	agentid := mv.Get("agentid")
	if err != nil {
		common.Log.Err("EG GetUserInfo xml error:", err)
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

	wb, err := mdz.GetAgentAvailableAmountByAgentid(agentid, isdz, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("EG GetAvailableAmountByAgentid sql error:", err)
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
