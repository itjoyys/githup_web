package main

import (
	"runtime"

	h "app/controllers"
	"app/controllers/api"
	"app/queue"
	"common"
)

var _VERSION_ = "local" //test , online

func init() {
	runtime.GOMAXPROCS(runtime.NumCPU())
}

//启动说有分站
func main() {
	GlobalInit(_VERSION_)

	//lebo_game()

	//获取个大视讯的投注和财务记录
	//等待。。
	//queue.StartQueue()
	queue.MG_Queue("2013-08-13 05:00:00")
	
	var main chan(int)
	
	<- main

}

func lebo_game() {
	m := NewApp()

	m.NotFound(h.NotFoundJson)

	m.Group("", func() {
		m.Get("/createaccount", api.LEBO_CheckOrCreateGameAccout) //注册端口
		m.Get("/getbalance", api.LEBO_GetBalance)                 //获取余额
		m.Get("/transfercredit", api.LEBO_TransferCredit)         //转账端口
		m.Get("/forwardgame", api.LEBO_ForwardGame)               //进入游戏页面

		m.Get("/getbetrecords", api.LEBO_GetBetRecord)
		m.Get("/getavailableamountbyuser", api.LEBO_GetAvailableAmountByUser)
		m.Get("/getavailableamountbysiteid", api.LEBO_GetAvailableAmountBySiteid)
		m.Get("/getavailableamountbyagentid", api.LEBO_GetAvailableAmountByAgentid)

		m.Get("/getuseravailableamountbyuser", api.LEBO_GetUserAvailableAmountByUser)
	}, h.AuthIP())

	m.Run(common.Cfg.Section("subdomain").Key("LEBO_PORT").MustInt(3005))
}
