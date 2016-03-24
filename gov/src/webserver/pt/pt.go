package main

import (
	"runtime"

	h "app/controllers"
	"app/controllers/api"
	"app/queue"
	"common"

	mpt "app/models/pt"
)

var _VERSION_ = "local" //test , online

func init() {
	runtime.GOMAXPROCS(runtime.NumCPU())
}

//启动说有分站
func main() {
	GlobalInit(_VERSION_)
	go og_game()
	//go pt_game()
	mpt.PT_LoadAllUserInRedis()
	queue.Pt_GetAllBets()

	var main chan (int)

	<-main

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
		m.Get("/forwardptdz", api.PT_ForwardGame)               //进入游戏页面
		m.Get("/forwardptswdz", api.PT_ForwardSwGame)           //试玩线路
		m.Get("/editaccountpwd", api.PT_EditAccountPwd)         //修改密码
		m.Get("/getbetrecords", api.PT_GetBetRecord)            //获取注单记录
		m.Get("/getavailableamountbyuser", api.PT_GetAvailableAmountByUser)
		m.Get("/getallavailableamountbysites", api.GetAllAvailableAmountBySiteid)
	}, h.AuthIP())

	m.Run(common.Cfg.Section("subdomain").Key("PT_PORT").MustInt(3010))
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
