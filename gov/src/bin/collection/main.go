package main

import (
	"runtime"

	"app/queue"
	mag "app/models/ag"
	mbbin "app/models/bbin"
	mct "app/models/ct"
	mlebo "app/models/lebo"
	mmg "app/models/mg"
	mog "app/models/og"
	mpt "app/models/pt"
	meg "app/models/eg"
)

var _VERSION_ = "local" //test , online

func init() {
	runtime.GOMAXPROCS(runtime.NumCPU())
}

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
	meg.Eg_LoadAllUserInRedis()

	queue.StartQueue()
}
