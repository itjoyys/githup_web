package main

import (
	"runtime"

	h "app/controllers"
	"app/controllers/api"
	"app/queue"
	"common"

	mag "app/models/ag"
	mbbin "app/models/bbin"
	mct "app/models/ct"
	mlebo "app/models/lebo"
	mmg "app/models/mg"
	mog "app/models/og"
	mpt "app/models/pt"
)

var _VERSION_ = "local" //test , online

func init() {
	runtime.GOMAXPROCS(runtime.NumCPU())
}

func og_game() {
	m := NewApp()

	m.NotFound(h.NotFoundJson)

	m.Group("", func() {
		m.Get("/createaccount", api.CheckAndCreateAccount) //注册端口
		m.Get("/getbalance", api.GetBalance)               //获取余额

		m.Get("/getallbalance", api.GetAllBalance)       //获取所有余额
		m.Get("/updateallbalance", api.UpdateAllBalance) //回调更新所有余额

		m.Get("/getalluseravailableamountbyagentid", api.GetAllUserAvailableAmountByAgentid)

		m.Get("/transfercredit", api.TransferCredit) //转账端口
		m.Get("/forwardgame", api.TransferGame)      //进入游戏页面

		m.Get("/getbetrecords", api.OG_GetBetRecord)
		m.Get("/getavailableamountbyuser", api.OG_GetAvailableAmountByUser)
		m.Get("/getavailableamountbysiteid", api.OG_GetAvailableAmountBySiteid)
		m.Get("/getavailableamountbyagentid", api.OG_GetAvailableAmountByAgentid)

		m.Get("/getuseravailableamountbyuser", api.OG_GetUserAvailableAmountByUser)
		m.Get("/getuseravailableamountbysiteid", api.OG_GetUserAvailableAmountBySiteid)
		m.Get("/getuseravailableamountbyagentid", api.OG_GetUserAvailableAmountByAgentid)

		m.Get("/getagentavailableamountbyagentid", api.OG_GetAgentAvailableAmountByAgentid)

		m.Get("/getuserallavailableamount", api.GetUserAllAvailableAmountByUser)
		m.Get("/getuserallavailableamountbyagentid", api.GetUserAllAvailableAmountByAgentid)

		m.Get("/getallavailableamountbysites", api.GetAllAvailableAmountBySiteid)
		//m.Get("/recollection", api.ReCollection) //手动采集历史记录

	}, h.AuthIP())

	//debug使用-------------------------------------
	m.Any("/debug1--1--/pprof", h.DebugIndex)
	m.Any("/debug1--1--/pprof/*", h.DebugIndex)
	m.Any("/debug1--1--/pprof/profile", h.DebugProfile)
	//----------------------------------------------
	m.Run(common.Cfg.Section("subdomain").Key("OG_PORT").MustInt(3001))
}

func ag_game() {
	m := NewApp()

	m.NotFound(h.NotFoundJson)

	m.Group("", func() {
		m.Get("/createaccount", api.AG_CheckOrCreateGameAccout) //注册端口
		m.Get("/getbalance", api.AG_GetBalance)                 //获取余额
		m.Get("/transfercredit", api.AG_TransferCredit)         //转账端口
		m.Get("/forwardgame", api.AG_ForwardGame)               //进入游戏页面
		m.Get("/updateallbalance", api.UpdateAllBalance)        //回调更新所有余额
		m.Get("/forwarddz", api.AG_ForwardDz)                   //电子游戏

		m.Get("/getbetrecords", api.AG_GetBetRecord)
		m.Get("/getavailableamountbyuser", api.AG_GetAvailableAmountByUser)
		m.Get("/getavailableamountbysiteid", api.AG_GetAvailableAmountBySiteid)
		m.Get("/getavailableamountbyagentid", api.AG_GetAvailableAmountByAgentid)

		m.Get("/getuseravailableamountbyuser", api.AG_GetUserAvailableAmountByUser)
		m.Get("/getuseravailableamountbysiteid", api.AG_GetUserAvailableAmountBySiteid)
		m.Get("/getuseravailableamountbyagentid", api.AG_GetUserAvailableAmountByAgentid)

		m.Get("/getagentavailableamountbyagentid", api.AG_GetAgentAvailableAmountByAgentid)
		m.Get("/getuserallavailableamount", api.GetUserAllAvailableAmountByUser)

		m.Get("/getuserallavailableamountbyagentid", api.GetUserAllAvailableAmountByAgentid)

		m.Get("/getallavailableamountbysites", api.GetAllAvailableAmountBySiteid)
		//m.Get("/recollection", api.ReCollection) //手动采集历史记录

	}, h.AuthIP())

	m.Run(common.Cfg.Section("subdomain").Key("AG_PORT").MustInt(3002))
}

func ct_game() {
	m := NewApp()

	m.NotFound(h.NotFoundJson)

	m.Group("", func() {
		m.Get("/createaccount", api.CT_CheckOrCreateGameAccout) //注册端口
		m.Get("/getbalance", api.CT_GetBalance)                 //获取余额
		m.Get("/transfercredit", api.CT_TransferCredit)         //转账端口
		m.Get("/forwardgame", api.CT_ForwardGame)               //进入游戏页面

		m.Get("/updateallbalance", api.UpdateAllBalance) //回调更新所有余额

		m.Get("/getbetrecords", api.CT_GetBetRecord)
		m.Get("/getavailableamountbyuser", api.CT_GetAvailableAmountByUser)
		m.Get("/getavailableamountbysiteid", api.CT_GetAvailableAmountBySiteid)
		m.Get("/getavailableamountbyagentid", api.CT_GetAvailableAmountByAgentid)

		m.Get("/getuseravailableamountbyuser", api.CT_GetUserAvailableAmountByUser)
		m.Get("/getuseravailableamountbysiteid", api.CT_GetUserAvailableAmountBySiteid)
		m.Get("/getuseravailableamountbyagentid", api.CT_GetUserAvailableAmountByAgentid)

		m.Get("/getagentavailableamountbyagentid", api.CT_GetAgentAvailableAmountByAgentid)

		m.Get("/getuserallavailableamount", api.GetUserAllAvailableAmountByUser)

		m.Get("/getuserallavailableamountbyagentid", api.GetUserAllAvailableAmountByAgentid)

		m.Get("/getallavailableamountbysites", api.GetAllAvailableAmountBySiteid)

		//m.Get("/recollection", api.ReCollection) //手动采集历史记录
	}, h.AuthIP())

	m.Run(common.Cfg.Section("subdomain").Key("CT_PORT").MustInt(3003))
}

func mg_game() {
	m := NewApp()

	m.NotFound(h.NotFoundJson)

	m.Group("", func() {
		m.Get("/createaccount", api.MG_CheckOrCreateGameAccout) //注册端口
		m.Get("/getbalance", api.MG_GetBalance)                 //获取余额
		m.Get("/transfercredit", api.MG_TransferCredit)         //转账端口
		m.Get("/forwardgame", api.MG_ForwardGame)               //进入游戏页面
		m.Get("/forwarddz", api.MG_ForwardDz)                   //电子游戏
		m.Get("/forwarddzpost", api.MG_ForwardDzPost)           //电子游戏

		m.Get("/updateallbalance", api.UpdateAllBalance) //回调更新所有余额

		m.Get("/getbetrecords", api.MG_GetBetRecord)
		m.Get("/getavailableamountbyuser", api.MG_GetAvailableAmountByUser)
		m.Get("/getavailableamountbysiteid", api.MG_GetAvailableAmountBySiteid)
		m.Get("/getavailableamountbyagentid", api.MG_GetAvailableAmountByAgentid)
		m.Get("/getplaycheckurl", api.MG_GetPlaycheckUrl) //获取用户注单页面

		m.Get("/getuseravailableamountbyuser", api.MG_GetUserAvailableAmountByUser)
		m.Get("/getuseravailableamountbysiteid", api.MG_GetUserAvailableAmountBySiteid)
		m.Get("/getuseravailableamountbyagentid", api.MG_GetUserAvailableAmountByAgentid)

		m.Get("/getagentavailableamountbyagentid", api.MG_GetAgentAvailableAmountByAgentid)

		//mg管理相关
		m.Get("/getagentinfo", api.MG_GetSiteUserAndPwd)
		m.Get("/changeagentpwd", api.MG_ChangeAgentPwd)
		m.Get("/editaccountpwd", api.MG_EditAccountPwd)

		m.Get("/casinoprofitbygametypereport", api.MG_APICasinoProfitByGameTypeReport)
		m.Get("/credittransferbyaccount", api.MG_CreditTransferByAccount)
		m.Get("/getbetinfodetailsbyaccount", api.MG_APIGetBetInfoDetailsByAccount)

		m.Get("/getuserallavailableamount", api.GetUserAllAvailableAmountByUser)

		m.Get("/getuserallavailableamountbyagentid", api.GetUserAllAvailableAmountByAgentid)

		m.Get("/getallavailableamountbysites", api.GetAllAvailableAmountBySiteid)
		//m.Get("/recollection", api.ReCollection) //手动采集历史记录
	}, h.AuthIP())

	m.Run(common.Cfg.Section("subdomain").Key("MG_PORT").MustInt(3004))
}

func lebo_game() {
	m := NewApp()

	m.NotFound(h.NotFoundJson)

	m.Group("", func() {
		m.Get("/createaccount", api.LEBO_CheckOrCreateGameAccout) //注册端口
		m.Get("/getbalance", api.LEBO_GetBalance)                 //获取余额
		m.Get("/transfercredit", api.LEBO_TransferCredit)         //转账端口
		m.Get("/forwardgame", api.LEBO_ForwardGame)               //进入游戏页面

		m.Get("/updateallbalance", api.UpdateAllBalance) //回调更新所有余额

		m.Get("/getbetrecords", api.LEBO_GetBetRecord)
		m.Get("/getavailableamountbyuser", api.LEBO_GetAvailableAmountByUser)
		m.Get("/getavailableamountbysiteid", api.LEBO_GetAvailableAmountBySiteid)
		m.Get("/getavailableamountbyagentid", api.LEBO_GetAvailableAmountByAgentid)

		m.Get("/getuseravailableamountbyuser", api.LEBO_GetUserAvailableAmountByUser)
		m.Get("/getuseravailableamountbysiteid", api.LEBO_GetUserAvailableAmountBySiteid)
		m.Get("/getuseravailableamountbyagentid", api.LEBO_GetUserAvailableAmountByAgentid)

		m.Get("/getagentavailableamountbyagentid", api.LEBO_GetAgentAvailableAmountByAgentid)

		//mg管理相关
		m.Get("/getagentinfo", api.MG_GetSiteUserAndPwd)
		m.Get("/changeagentpwd", api.MG_ChangeAgentPwd)

		m.Get("/getuserallavailableamount", api.GetUserAllAvailableAmountByUser)
		m.Get("/getuserallavailableamountbyagentid", api.GetUserAllAvailableAmountByAgentid)

		m.Get("/getallavailableamountbysites", api.GetAllAvailableAmountBySiteid)

		//m.Get("/recollection", api.ReCollection) //手动采集历史记录
	}, h.AuthIP())

	m.Run(common.Cfg.Section("subdomain").Key("LEBO_PORT").MustInt(3005))
}

func bbin_game() {
	m := NewApp()

	m.NotFound(h.NotFoundJson)

	m.Group("", func() {
		m.Get("/createaccount", api.CreateMember) //注册端口
		m.Get("/getbalance", api.Bbin_GetBalance) //获取余额

		m.Get("/transfercredit", api.Bbin_TransferCredit) //转账端口
		m.Get("/forwardgame", api.Bbin_TransferGame)      //进入游戏页面

		m.Get("/updateallbalance", api.UpdateAllBalance) //回调更新所有余额

		m.Get("/getbetrecords", api.Bbin_GetBetRecord)
		m.Get("/getavailableamountbyuser", api.Bbin_GetAvailableAmountByUser)
		m.Get("/getavailableamountbysiteid", api.Bbin_GetAvailableAmountBySiteid)
		m.Get("/getavailableamountbyagentid", api.Bbin_GetAvailableAmountByAgentid)

		m.Get("/getuseravailableamountbyuser", api.Bbin_GetUserAvailableAmountByUser)
		m.Get("/getuseravailableamountbysiteid", api.Bbin_GetUserAvailableAmountBySiteid)
		m.Get("/getuseravailableamountbyagentid", api.Bbin_GetUserAvailableAmountByAgentid)

		m.Get("/getagentavailableamountbyagentid", api.Bbin_GetAgentAvailableAmountByAgentid)

		m.Get("/getuserallavailableamount", api.GetUserAllAvailableAmountByUser)
		m.Get("/getuserallavailableamountbyagentid", api.GetUserAllAvailableAmountByAgentid)

		m.Get("/getallavailableamountbysites", api.GetAllAvailableAmountBySiteid)
		//m.Get("/recollection", api.ReCollection) //手动采集历史记录

	}, h.AuthIP())

	m.Run(common.Cfg.Section("subdomain").Key("BBIN_PORT").MustInt(3006))
}

func pt_game() {
	m := NewApp()

	m.NotFound(h.NotFoundJson)

	m.Group("", func() {
		m.Get("/getallbalance", api.GetAllBalance)              //获取所有余额
		m.Get("/killsession", api.PT_KillSession)               //删除玩家会话
		m.Get("/createaccount", api.PT_CheckOrCreateGameAccout) //注册端口
		m.Get("/getbalance", api.PT_GetBalance)                 //获取余额
		m.Get("/transfercredit", api.PT_TransferCredit)         //转账端口
		m.Get("/forwarddz", api.PT_ForwardGame)                 //进入游戏页面
		m.Get("/forwardptswdz", api.PT_ForwardSwGame)           //试玩线路
		m.Get("/editaccountpwd", api.PT_EditAccountPwd)         //修改密码
		m.Get("/getbetrecords", api.PT_GetBetRecord)            //获取注单记录
		m.Get("/getavailableamountbyuser", api.PT_GetAvailableAmountByUser)
		m.Get("/getallavailableamountbysites", api.GetAllAvailableAmountBySiteid)
	}, h.AuthIP())

	m.Run(common.Cfg.Section("subdomain").Key("PT_PORT").MustInt(3009))
}

func dz_game() {
	m := NewApp()

	m.NotFound(h.NotFoundJson)

	m.Group("", func() {
		m.Get("/createaccount", api.Dz_CreateMember) //注册端口
		//m.Get("/getbalance", api.LEBO_GetBalance)                 //获取余额
		//m.Get("/transfercredit", api.LEBO_TransferCredit)         //转账端口
		m.Get("/forwarddz", api.Dz_TransferGame) //进入游戏页面

		//m.Get("/getbetrecords", api.LEBO_GetBetRecord)
		//m.Get("/getavailableamountbyuser", api.LEBO_GetAvailableAmountByUser)
		//m.Get("/getavailableamountbysiteid", api.LEBO_GetAvailableAmountBySiteid)
		//m.Get("/getavailableamountbyagentid", api.LEBO_GetAvailableAmountByAgentid)

		//m.Get("/getuseravailableamountbyuser", api.LEBO_GetUserAvailableAmountByUser)
	}, h.AuthIP())

	m.Run(common.Cfg.Section("subdomain").Key("PKDZ_PORT").MustInt(3007))
}

func sx_game() {
	m := NewApp()

	m.NotFound(h.NotFoundJson)

	m.Group("", func() {
		m.Get("/createaccount", api.Aba_CreateMember) //注册端口
		m.Get("/forwardgame", api.Aba_TransferGame)   //进入游戏页面

	}, h.AuthIP())

	m.Run(common.Cfg.Section("subdomain").Key("PKSX_PORT").MustInt(3008))
}

//启动说有分站
func main() {
	GlobalInit(_VERSION_)

	if err := queue.Verify_ips(); err != nil {
		panic(err)
	}

	//加载redis缓存
	mag.AG_LoadAllUserInRedis()
	mog.OG_LoadAllUserInRedis()
	mct.CT_LoadAllUserInRedis()
	mlebo.Lebo_LoadAllUserInRedis()
	mmg.MG_LoadAllUserInRedis()
	mbbin.BBIN_LoadAllUserInRedis()
	mpt.PT_LoadAllUserInRedis()

	go og_game()
	go ag_game()
	go ct_game()
	go mg_game()
	go lebo_game()
	go bbin_game()
	go pt_game()
	go dz_game()
	go sx_game()

	//command := make(chan int)
	//<-command
	//获取各大视讯的投注和财务记录
	//等待。。
	queue.StartQueue()
}
