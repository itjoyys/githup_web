package api

import (
	_ "fmt"
	"strings"
	"time"

	"app/models"
	mag "app/models/ag"
	mbbin "app/models/bbin"
	mct "app/models/ct"
	mlebo "app/models/lebo"
	mmg "app/models/mg"
	mog "app/models/og"
	mpt "app/models/pt"
	"common"
	"core/napping"
	"encoding/json"
	"middleware"
	"net/url"
	"plug-ins/games/ag"
	"plug-ins/games/bbin"
	"plug-ins/games/ct"
	"plug-ins/games/lebo"
	"plug-ins/games/mg"
	"plug-ins/games/og"
	"plug-ins/games/pt"
	"utility"
)

//添加用户
func CheckAndCreateAccount(ctx *middleware.Context) {
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
	//是否已经纯在
	if mog.IsUserExist(username, siteid) {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	index_id := mv.Get("index_id")
	if len(index_id) == 0 {
		index_id = "a"
	}
	//判断用户是否存在，如果不存在则创建,存在换名字
	if len(username) >= 12 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10005}})
		return
	}
	user := mog.User{}
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
		b = mog.IsGUserNotExist(username)
		if !b {
			username = CreateUserName(username)
		}
		ii++
	}
	og_g, err := og.OrientalGameInit(siteid2)
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
		isexist, _ = og_g.CheckAndCreateAccount(username, mog.Password, cur, "", "", "")
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
	err = mog.CreateUser(&user)
	if err != nil {
		common.Log.Info("og CheckAndCreateAccount sql error：", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10011}})
}

//获取站点的设置
func GetSiteByIdForApi(ctx *middleware.Context) {
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

	sites, err := models.GetSiteByIdForApi(siteid2)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	ctx.JSON(200, JsonResult{Result: true, Data: sites})
}

//通过api修改站点配置
func UpdateSiteByApi(ctx *middleware.Context) {
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

	//sites, err := models.GetSiteByIdForApi(siteid2)
	//update
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10028}})
		return
	}
	//ctx.JSON(200, JsonResult{Result: true, Data: sites})
	ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10027}})
}

//获取账户余额
func GetBalance(ctx *middleware.Context) {
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
	userinfo, err := mog.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("OG GetUserInfo:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	og_g, err := og.OrientalGameInit(siteid2)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}

	balance, err := og_g.GetBalance(userinfo.GUserName, userinfo.Password)
	if err != nil {
		common.Log.Info("og GetBalance", err)
		ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10018}})
		return
	}
	ctx.JSON(200, JsonResult{Result: true, Data: BalanceData{Code: 10017, Balance: balance}})
}

//获取所有类型的注单以agentid分组
func GetAllUserAvailableAmountByAgentid(ctx *middleware.Context) {
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
	ids := strings.Split(agentid, "|")
	if len(ids) == 0 {
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
	//og
	mapuser := make(map[string]UserAvailableBetData)
	wb_og, err := mog.GetUserAvailableAmountByAgentid(agentid, isdz, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("OG GetUserAvailableAmountByAgentid sql error:", agentid, err)
	}
	if len(wb_og) > 0 {
		for _, v := range wb_og {
			userd := UserAvailableBetData{}
			userd.UserName = string(v["account_number"])
			userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
			userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
			userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
			mapuser[userd.UserName] = userd
		}
	}
	wb_lebo, err := mlebo.GetUserAvailableAmountByAgentid(agentid, isdz, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("LEBO GetUserAvailableAmountByAgentid sql error:", agentid, err)
	}
	if len(wb_lebo) > 0 {
		for _, v := range wb_lebo {
			userd := UserAvailableBetData{}
			userd.UserName = string(v["account_number"])
			userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
			userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
			userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
			if tempu, ok := mapuser[userd.UserName]; ok {
				userd.BetYC += tempu.BetYC
				userd.BetPC += tempu.BetPC
				userd.BetTimes += tempu.BetTimes
			}
			mapuser[userd.UserName] = userd
		}
	}
	gamekind := ""
	if isdz {
		gamekind = "100000" //bbin的电子不用分开
	}
	wb_bbin, err := mbbin.GetUserAvailableAmountByAgentid(agentid, gamekind, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("BBIN GetUserAvailableAmountByAgentid sql error:", agentid, err)
	}
	if len(wb_bbin) > 0 {
		for _, v := range wb_bbin {
			userd := UserAvailableBetData{}
			userd.UserName = string(v["account_number"])
			userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
			userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
			userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
			if tempu, ok := mapuser[userd.UserName]; ok {
				userd.BetYC += tempu.BetYC
				userd.BetPC += tempu.BetPC
				userd.BetTimes += tempu.BetTimes
			}
			mapuser[userd.UserName] = userd
		}
	}
	wb_ct, err := mct.GetUserAvailableAmountByAgentid(agentid, isdz, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("CT GetUserAvailableAmountByAgentid sql error:", agentid, err)
	}
	if len(wb_ct) > 0 {
		for _, v := range wb_ct {
			userd := UserAvailableBetData{}
			userd.UserName = string(v["account_number"])
			userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
			userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
			userd.BetPC = userd.BetPC - userd.BetYC
			userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
			if tempu, ok := mapuser[userd.UserName]; ok {
				userd.BetYC += tempu.BetYC
				userd.BetPC += tempu.BetPC
				userd.BetTimes += tempu.BetTimes
			}
			mapuser[userd.UserName] = userd
		}
	}
	wb_ag, err := mag.GetUserAvailableAmountByAgentid(agentid, isdz, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("AG GetUserAvailableAmountByAgentid sql error:", agentid, err)
	}
	if len(wb_ag) > 0 {
		for _, v := range wb_ag {
			userd := UserAvailableBetData{}
			userd.UserName = string(v["account_number"])
			userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
			userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
			userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
			if tempu, ok := mapuser[userd.UserName]; ok {
				userd.BetYC += tempu.BetYC
				userd.BetPC += tempu.BetPC
				userd.BetTimes += tempu.BetTimes
			}
			mapuser[userd.UserName] = userd
		}
	}
	wb_mg, err := mmg.GetUserAvailableAmountByAgentid(agentid, isdz, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("MG GetUserAvailableAmountByAgentid sql error:", agentid, err)
	}
	if len(wb_mg) > 0 {
		for _, v := range wb_mg {
			userd := UserAvailableBetData{}
			userd.UserName = string(v["pkusername"])
			userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
			userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
			userd.BetPC = userd.BetPC - userd.BetYC
			userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
			if tempu, ok := mapuser[userd.UserName]; ok {
				userd.BetYC += tempu.BetYC
				userd.BetPC += tempu.BetPC
				userd.BetTimes += tempu.BetTimes
			}
			mapuser[userd.UserName] = userd
		}
	}
	users := make([]UserAvailableBetData, 0)
	for _, v := range mapuser {
		users = append(users, v)
	}
	abd := UserAvailableBetDatas{}
	abd.Code = 10023
	abd.UserAvailableBetData = users
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func UpdateAllBalance(ctx *middleware.Context) {
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
	backurl := mv.Get("backurl")
	backurl, err = url.QueryUnescape(backurl)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	go _getAllBalancesSingle(siteid2, username, backurl)
	//common.Log.Info(" start _getAllBalancesSingle :", siteid2, username, backurl)
	ctx.Echo(200, "OK")
}

//单线程跑
func _getAllBalancesSingle(siteid2, username, callbackurl string) {
	allb := AllBalanceData{}
	allb.AG_Status = false
	allb.OG_Status = false
	allb.MG_Status = false
	allb.CT_Status = false
	allb.Lebo_Status = false
	allb.Bbin_Status = false
	allb.Pt_Status = false
	//og
	oguserinfo, err := mog.GetUserInfo(username, siteid2)
	if err == nil {
		og_g, err := og.OrientalGameInit(siteid2)
		if err != nil {
			common.Log.Info("_getAllBalancesSingle og:", err)
		}
		ogbalance, err := og_g.GetBalance(oguserinfo.GUserName, oguserinfo.Password)
		if err != nil {
			common.Log.Info("_getAllBalancesSingle og:", err)
		} else {
			allb.OG_Balance = ogbalance
			allb.OG_Status = true
		}
	}
	//mg
	mguserinfo, err := mmg.GetUserInfo(username, siteid2)
	if err == nil {
		mg_g, err := mg.MGameInit(siteid2)
		if err != nil {
			common.Log.Info("_getAllBalancesSingle mg:", err)
		}
		mgbalance, err := mg_g.GetAccountBalance(mguserinfo.GUserName)
		if err != nil {
			common.Log.Info("_getAllBalancesSingle mg:", err)
		} else {
			allb.MG_Balance = mgbalance[0].Balance
			allb.MG_Status = true
		}
	}
	//ct
	ctuserinfo, err := mct.GetUserInfo(username, siteid2)
	if err == nil {
		ct_g, err := ct.CrownTechnologyInit(siteid2)
		if err != nil {
			common.Log.Info("_getAllBalancesSingle ct:", err)
		}
		ctuserinfo, err := ct_g.Getuserinfo(ctuserinfo.GUserName)
		if err != nil {
			common.Log.Info("_getAllBalancesSingle ct:", err)
		} else {
			allb.CT_Balance = ctuserinfo.Balance
			allb.CT_Status = true
		}
	}
	//ag
	aguserinfo, err := mag.GetUserInfo(username, siteid2)
	if err == nil {
		ag_g := ag.AsiaGamingInit()
		agbalance, err := ag_g.GetBalance(aguserinfo.GUserName, aguserinfo.Password, "1", "")
		if err != nil {
			common.Log.Info("_getAllBalancesSingle ag:", err)
		} else {
			allb.AG_Balance = agbalance
			allb.AG_Status = true
		}
	}
	//lebo
	mlebouserinfo, err := mlebo.GetUserInfo(username, siteid2)
	if err == nil {
		lebo_g, err := lebo.LeboTechnologyInit(siteid2)
		if err != nil {
			common.Log.Info("_getAllBalancesSingle lebo:", err)
		}
		lebouserinfo, err := lebo_g.Getuserinfo(mlebouserinfo.GUserName)
		if err != nil {
			common.Log.Info("_getAllBalancesSingle lebo:", err)
		} else {
			allb.Lebo_Balance = lebouserinfo.Balance
			allb.Lebo_Status = true
		}
	}
	//bbin
	mbbinuserinfo, err := mbbin.GetUserInfo(username, siteid2)
	if err == nil {
		bbin_g, err := bbin.BbinInit(siteid2)
		if err != nil {
			common.Log.Err("_getAllBalancesSingle bbin:", err)
		}
		bbinuserinfo, err := bbin_g.CheckUsrBalance(mbbinuserinfo.GUserName, 0, 0)
		if err != nil {
			common.Log.Err("_getAllBalancesSingle bbin:", err)
		} else {
			allb.Bbin_Balance = bbinuserinfo.TotalBalance
			allb.Bbin_Status = true
		}
	}
	//pt
	mptuserinfo, err := mpt.GetUserInfo(username, siteid2)
	if err == nil {
		pt_g, err := pt.PtInit()
		if err != nil {
			common.Log.Info("_getAllBalancesSingle lebo:", err)
		}
		ptuserinfo, err := pt_g.GetBalance(mptuserinfo.GUserName)
		if err != nil {
			common.Log.Info("_getAllBalancesSingle lebo:", err)
		} else {
			allb.Pt_Balance = ptuserinfo
			allb.Pt_Status = true
		}
	}

	params := make([]string, 1)
	result, err := json.Marshal(allb)
	if err != nil {
		common.Log.Err("_getAllBalancesSingle bbin:", err)
		return
	}
	params[0] = "data=" + string(result)
	//获取站点
	site, err := models.GetSiteById(siteid2)
	if err != nil {
		common.Log.Err("_getAllBalancesSinglebbin siteid=:", siteid2, err)
		return
	}
	q_params, err := utility.DesEncrypt([]byte(strings.Join(params, "/\\\\/")), []byte(site.SiteDesKey))
	if err != nil {
		common.Log.Err("_getAllBalancesSingle :", err)
		return
	}
	key := common.Md5(string(q_params) + site.SiteMd5Key)
	//回复请求
	p := napping.Params{"param": string(q_params), "key": key, "siteid": siteid2, "username": username}
	s := napping.Session{Timeout: 30, Datatype: "html"}
	res := ""
	resp, err := s.Post(callbackurl, &p, nil, &res, nil)
	if err != nil {
		if resp != nil {
			common.Log.Err("_getAllBalancesSingle method Connection err ", resp.RawText())
		}
		common.Log.Err("_getAllBalancesSingle method Connection err ", err)
		return
	}
	if resp.RawText() == "yes" {

	}
}

//获取所有视讯的余额
func GetAllBalance(ctx *middleware.Context) {
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

	allb := AllBalanceData{}
	allb.AG_Status = false
	allb.OG_Status = false
	allb.MG_Status = false
	allb.CT_Status = false
	allb.Lebo_Status = false
	allb.Bbin_Status = false
	allb.Pt_Status = false

	//加入线程
	og_ch := make(chan float64)
	mg_ch := make(chan float64)
	ct_ch := make(chan float64)
	ag_ch := make(chan float64)
	lebo_ch := make(chan float64)
	bbin_ch := make(chan float64)
	pt_ch := make(chan float64)
	defer func() {
		close(og_ch)
		close(mg_ch)
		close(ct_ch)
		close(ag_ch)
		close(lebo_ch)
		close(bbin_ch)
		close(pt_ch)
	}()
	go func() {
		defer func() {
			if r := recover(); r != nil {
				common.Log.Err("GetAllBalance err:", r)
			}
		}()
		//获取用户
		oguserinfo, err := mog.GetUserInfo(username, siteid2)
		if err == nil {
			og_g, err := og.OrientalGameInit(siteid2)
			if err != nil {
				common.Log.Info("GetAllBalance og:", err)
				og_ch <- -1
				return
			}
			ogbalance, err := og_g.GetBalance(oguserinfo.GUserName, oguserinfo.Password)
			if err != nil {
				common.Log.Info("GetAllBalance og:", err)
				og_ch <- -1
			} else {
				og_ch <- ogbalance
			}
		} else {
			og_ch <- -1
		}
	}()
	go func() {
		defer func() {
			if r := recover(); r != nil {
				common.Log.Err("GetAllBalance err:", r)
			}
		}()
		mguserinfo, err := mmg.GetUserInfo(username, siteid2)
		if err == nil {
			mg_g, err := mg.MGameInit(siteid2)
			if err != nil {
				mg_ch <- -1
				return
			}
			mgbalance, err := mg_g.GetAccountBalance(mguserinfo.GUserName)
			if err != nil {
				common.Log.Info("GetAllBalance mg:", err)
				mg_ch <- -1
			} else {
				mg_ch <- mgbalance[0].Balance
			}
		} else {
			mg_ch <- -1
		}
	}()
	go func() {
		defer func() {
			if r := recover(); r != nil {
				common.Log.Err("GetAllBalance err:", r)
			}
		}()
		ctuserinfo, err := mct.GetUserInfo(username, siteid2)
		if err == nil {
			ct_g, err := ct.CrownTechnologyInit(siteid2)
			if err != nil {
				common.Log.Info("GetAllBalance ct:", err)
				ct_ch <- -1
				return
			}
			ctuserinfo, err := ct_g.Getuserinfo(ctuserinfo.GUserName)
			if err != nil {
				common.Log.Info("GetAllBalance ct:", err)
				ct_ch <- -1
			} else {
				ct_ch <- ctuserinfo.Balance
			}
		} else {
			ct_ch <- -1
		}
	}()
	go func() {
		defer func() {
			if r := recover(); r != nil {
				common.Log.Err("GetAllBalance err:", r)
			}
		}()
		aguserinfo, err := mag.GetUserInfo(username, siteid2)
		if err == nil {
			ag_g := ag.AsiaGamingInit()
			agbalance, err := ag_g.GetBalance(aguserinfo.GUserName, aguserinfo.Password, "1", "")
			if err != nil {
				common.Log.Info("GetAllBalance ag:", err)
				ag_ch <- -1
			} else {
				ag_ch <- agbalance
			}
		} else {
			ag_ch <- -1
		}
	}()
	go func() {
		defer func() {
			if r := recover(); r != nil {
				common.Log.Err("GetAllBalance err:", r)
			}
		}()
		mlebouserinfo, err := mlebo.GetUserInfo(username, siteid2)
		if err == nil {
			lebo_g, err := lebo.LeboTechnologyInit(siteid2)
			if err != nil {
				lebo_ch <- -1
				return
			}
			lebouserinfo, err := lebo_g.Getuserinfo(mlebouserinfo.GUserName)
			if err != nil {
				common.Log.Info("GetAllBalance lebo:", err)
				lebo_ch <- -1
			} else {
				lebo_ch <- lebouserinfo.Balance
			}
		} else {
			lebo_ch <- -1
		}
	}()
	go func() {
		defer func() {
			if r := recover(); r != nil {
				common.Log.Err("GetAllBalance err:", r)
			}
		}()
		mbbinuserinfo, err := mbbin.GetUserInfo(username, siteid2)
		if err == nil {
			bbin_g, err := bbin.BbinInit(siteid2)
			if err != nil {
				bbin_ch <- -1
				return
			}
			bbinuserinfo, err := bbin_g.CheckUsrBalance(mbbinuserinfo.GUserName, 0, 0)
			if err != nil {
				common.Log.Info("GetAllBalance bbin:", err)
				bbin_ch <- -1
			} else {
				bbin_ch <- bbinuserinfo.TotalBalance
			}
		} else {
			bbin_ch <- -1
		}
	}()
	go func() {
		defer func() {
			if r := recover(); r != nil {
				common.Log.Err("GetAllBalance err:", r)
			}
		}()
		mptuserinfo, err := mpt.GetUserInfo(username, siteid2)
		if err == nil {
			pt_g, err := pt.PtInit()
			if err != nil {
				pt_ch <- -1
				return
			}
			ptuserinfo, err := pt_g.GetBalance(mptuserinfo.GUserName)
			if err != nil {
				common.Log.Info("GetAllBalance pt:", err)
				pt_ch <- -1
			} else {
				pt_ch <- ptuserinfo
			}
		} else {
			pt_ch <- -1
		}
	}()
	i := 0
	for {
		select {
		case <-time.After(time.Second * 7):
			i = 10
		case og_b := <-og_ch:
			if og_b >= 0 {
				allb.OG_Status = true
				allb.OG_Balance = og_b
			}
			i++
		case mg_b := <-mg_ch:
			if mg_b >= 0 {
				allb.MG_Balance = mg_b
				allb.MG_Status = true
			}
			i++
		case ct_b := <-ct_ch:
			if ct_b >= 0 {
				allb.CT_Balance = ct_b
				allb.CT_Status = true
			}
			i++
		case ag_b := <-ag_ch:
			if ag_b >= 0 {
				allb.AG_Status = true
				allb.AG_Balance = ag_b
			}
			i++
		case lebo_b := <-lebo_ch:
			if lebo_b >= 0 {
				allb.Lebo_Balance = lebo_b
				allb.Lebo_Status = true
			}
			i++
		case bbin_b := <-bbin_ch:
			if bbin_b >= 0 {
				allb.Bbin_Balance = bbin_b
				allb.Bbin_Status = true
			}
			i++
		case pt_b := <-pt_ch:
			if pt_b >= 0 {
				allb.Pt_Balance = pt_b
				allb.Pt_Status = true
			}
			i++
		}
		if i >= 7 {
			break
		}
	}
	allb.Code = 10017
	ctx.JSON(200, JsonResult{Result: true, Data: allb})
}

//转账
func TransferCredit(ctx *middleware.Context) {
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
	credit_str := mv.Get("credit")

	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	billno := time.Now().Format("0102150405.000000")
	//去掉小数点
	billno = strings.Replace(billno, ".", "", 10)
	//fmt.Println(billno)
	//ctype := "IN" //OUT
	og_g, err := og.OrientalGameInit(siteid2)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	gcr := new(models.GameCashRecord)
	gcr.Credit = credit
	gcr.Platform = "og"
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
	userinfo, err := mog.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("OG GetUserInfo:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}
	isok, err := og_g.TransferCredit(userinfo.GUserName, userinfo.Password, billno, ctype, credit_str)
	if err != nil {
		gcr.Info += err.Error()
		common.Log.Err("OG PrepareTransferCredit:", err)
		gcr.Status = 0
		//如果失败
		isok, err = og_g.ConfirmTransferCredit(userinfo.GUserName, userinfo.Password, billno, ctype, credit_str)
		if isok && err == nil {
			//本地添加一条转账记录
			gcr.Status = 1
		} else {
			gcr.Info += err.Error()
			common.Log.Err("OG ConfirmTransferCredit:", err)
			gcr.Status = 0
		}
	} else {
		gcr.Status = 1
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

//进入游戏
func TransferGame(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	siteid2 := mv.Get("siteid")
	username := mv.Get("username")
	limit := mv.Get("limit")
	//limitype := mv.Get("limitype")
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
	userinfo, err := mog.GetUserInfo(username, siteid2)
	if err != nil {
		if err == models.ErrNotExist {
			//TODO 不存在的用户创建
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10006}})
			return
		} else {
			common.Log.Err("OG GetUserInfo:", err)
			ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
			return
		}
	}

	og_g, err := og.OrientalGameInit(siteid2)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}
	//设置限红
	limits := strings.Split(limit, "|")
	if len(limits) == 3 {
		if len(limits[0]) > 0 {
			og_g.UpdateLimit(userinfo.GUserName, limits[0], "1")
		}
		if len(limits[1]) > 0 {
			og_g.UpdateLimit(userinfo.GUserName, limits[1], "2")
		}
		if len(limits[2]) > 0 {
			og_g.UpdateLimit(userinfo.GUserName, limits[2], "3")
		}
	}

	html, err := og_g.TransferGame(userinfo.GUserName, userinfo.Password, siteconfig.SiteDomain)
	if err != nil {
		ctx.Echo(200, err.Error())
		return
	}
	ctx.Echo(200, html)
}

/*
func GetGameResult(ctx *middleware.Context) {

	og_g,err := og.OrientalGameInit(siteid2)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}

	tableid := 1
	stage := 1
	inning := 1
	ascordesc := 1
	currentpage := 1
	pagesize := 200
	datestart := "2014-05-08"
	dateend := "2015-05-22"

	html, err := og_g.GetGameResult(tableid, stage, inning, ascordesc, currentpage, pagesize, datestart, dateend)
	oggr := make([]map[string]interface{}, 0)
	for _, v := range html.GameRecord {
		gr := make(map[string]interface{})
		for _, v1 := range v.Properties {
			key := common.SnakeCasedName(v1.Name)
			gr[key] = v1.Value
		}
		oggr = append(oggr, gr)
	}
	err = mog.InsertBatchGameRecord(oggr)
	if err != nil {
		common.Log.Err("OG InsertBatchGameRecord mysql error", err)
	}
	ctx.Echo(200, "OK")
}
/*
func GetCashRecord(ctx *middleware.Context) {
	og_g,err := og.OrientalGameInit(siteid2)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
		return
	}

	html, err := og_g.GetCashTrade("1")
	ogcashs := make([]map[string]interface{}, 0)
	for _, v := range html.CashRecord {
		bet := make(map[string]interface{})
		for _, v1 := range v.Properties {
			key := common.SnakeCasedName(v1.Name)
			bet[key] = v1.Value
		}
		ogcashs = append(ogcashs, bet)
	}
	err = mog.InsertBatchCashRecord(ogcashs)
	if err != nil {
		common.Log.Err("OG InsertBatchGameRecord mysql error", err)
	}
	ctx.Echo(200, "OK")
}
*/
func OG_GetBetRecord(ctx *middleware.Context) {
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
			//userinfo, err := mog.GetUserInfo(username, siteid2)
			//获取用户
			allsiteid := common.Cfg.Section("app").Key("MANAGE_SITEID").MustString("manage")
			if siteid2 != allsiteid {
				userinfo, err := mog.GetUserInfo(username, siteid2)
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
				username, err = mog.GetGUserNames(username)
				if err != nil {
					common.Log.Err("mbbin GetUserInfo sql error:", err)
					ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10004}})
					return
				}
				username = strings.Trim(username, "|")
			}
		}
	*/
	betrecords, count, err := mog.GetBetRecords(username, orderId, siteid2, agent_id, videotype, fromtime, totime, pagenum, page)
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
				brdata.BetMoney = v.BettingAmount
				brdata.ValidBetMoney = v.ValidAmount
				brdata.BetType = utility.ToStr(v.GameNameID, 10)
				brdata.DeskNum = utility.ToStr(v.TableID, 10)
				brdata.GameId = utility.ToStr(v.GameNameID, 10)
				brdata.GameResult = utility.ToStr(v.ResultType, 10)
				brdata.Number = ""
				brdata.OrderId = utility.ToStr(v.OrderNumber, 10)
				brdata.OrderNum = utility.ToStr(v.OrderNumber, 10)
				brdata.OrderTime = v.AddTime
				brdata.ResultMoney = v.WinLoseAmount
				brdata.Siteid = v.Site_id
				brs.BetMoneyAll = brs.BetMoneyAll + brdata.BetMoney
				brs.ValidBetMoneyAll = brs.ValidBetMoneyAll + brdata.ValidBetMoney
				brs.ResultMoneyAll = brs.ResultMoneyAll + brdata.ResultMoney
				brdatas = append(brdatas, brdata)
			}

			//brs.ResultMoneyAll = brs.ResultMoneyAll + brs.ValidBetMoneyAll //因为输的派彩为负数，所以要加上本金
			brs.Code = 10021
			brs.BackMoneyAll = 0

			brs.BetMoneyAll_, brs.ValidBetMoneyAll_, brs.BackMoneyAll_, brs.ResultMoneyAll_, _ = mog.GetAllMonery(username, orderId, siteid2, agent_id, videotype, fromtime, totime, pagenum, page)

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

func OG_GetAvailableAmountByUser(ctx *middleware.Context) {
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

	times, vtimes, all_bb, _, vb, wb, err := mog.GetAvailableAmountByUser(username, siteid2, fromtime, totime)
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

func OG_GetAvailableAmountBySiteid(ctx *middleware.Context) {
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

	times, bb, vb, wb, err := mog.GetAvailableAmountBySiteid(siteid2, fromtime, totime)
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

func OG_GetAvailableAmountByAgentid(ctx *middleware.Context) {
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

	times, bb, vb, wb, err := mog.GetAvailableAmountByAgentid(agentid, siteid2, fromtime, totime)
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

func OG_GetUserAvailableAmountByUser(ctx *middleware.Context) {
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

	wb, err := mog.GetUserAvailableAmountByUser(username, siteid2, fromtime, totime, isdz, notbrokerage)
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

func OG_GetUserAvailableAmountBySiteid(ctx *middleware.Context) {
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
	isdz := (mv.Get("dz") == "1")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	wb, err := mog.GetUserAvailableAmountBySiteid(siteid2, isdz, fromtime, totime)
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

func OG_GetUserAvailableAmountByAgentid(ctx *middleware.Context) {
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
	isdz := (mv.Get("dz") == "1")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	wb, err := mog.GetUserAvailableAmountByAgentid(agentid, isdz, siteid2, fromtime, totime)
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

func OG_GetAgentAvailableAmountByAgentid(ctx *middleware.Context) {
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
	isdz := (mv.Get("dz") == "1")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}

	wb, err := mog.GetAgentAvailableAmountByAgentid(agentid, isdz, siteid2, fromtime, totime)
	if err != nil {
		common.Log.Err("OG GetAvailableAmountByAgentid sql error:", err)
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
