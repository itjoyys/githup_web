package main

import (
	"runtime"

	"app/models"
	"app/queue"
	"common"
	//"core/napping"
	"core/webcore"

	//"encoding/xml"
	//"core/goftp"
	"flag"
	"fmt"
	"time"
	//"log"
	//"strings"
	//"utility"

	//mag "app/models/ag"
	//mbbin "app/models/bbin"
	//mct "app/models/ct"
	//mlebo "app/models/lebo"
	//mmg "app/models/mg"
	//mog "app/models/og"
)

var _VERSION_ = "local" //test , online

func init() {
	runtime.GOMAXPROCS(runtime.NumCPU())
}

var (
	host, username, password, temppath string
)

//批量获取ogbet

//启动说有分站
func main() {

	//vid := flag.Int64("vid", 1422507122698, "start vid ")
	start_time_str := flag.String("stime", "2015-11-02 00:00:00", "start vid ")
	end_time_str := flag.String("etime", "2015-11-03 00:00:00", "start vid ")
	//site_id := flag.String("siteid", "t", "siteid ")
	// -stime="2015-12-15 00:00:00" -etime="2015-12-17 03:00:00"
	//BBIN_verify_site

	flag.Parse()

	if _VERSION_ == "online" {
		webcore.Env = webcore.PROD
	} else {
		webcore.Env = webcore.DEV
	}
	common.GetConf()   //加载配置文件
	common.InitLog()   //初始化日志系统
	models.NewEngine() //初始化数据库连接

	//queue.Test_ag("20151228")
	/*
		f := goftp.FtpOpe{20151228}
		ag_conf_init()
		hunterpath := "/HUNTER/20151229"
		hunterfiles, err := f.FtpWalkDir(host, username, password, hunterpath)
		fmt.Println(hunterfiles, err)

		hunterpath = "/HUNTER/20151228"
		hunterfiles, err = f.FtpWalkDir(host, username, password, hunterpath)
		fmt.Println(hunterfiles, err)

		hunterpath = "/HUNTER/20151225"
		hunterfiles, err = f.FtpWalkDir(host, username, password, hunterpath)
		fmt.Println(hunterfiles, err)
	*/
	//return
	/*
		if *vid > 0 {
			queue.Og_GetRecords(*vid)
		} else {
			fmt.Println("start vid must not !!",*vid)
		}*/
	//2011-01-31 19:25:34|2011-01-31 19:25:34

	startime, _ := time.Parse("2006-01-02 15:04:05", *start_time_str) //,"2015-11-02 00:00:00"
	endtime, _ := time.Parse("2006-01-02 15:04:05", *end_time_str)    //"2015-12-06 00:00:00"
	d, _ := time.ParseDuration("+1h")                                 //转换服务器时间为中国时间
	for {
		if startime.Unix() >= endtime.Unix() {
			break
		}
		startime_end := startime.Add(d)
		fmt.Println(startime.Format("2006-01-02 15:04:05"), startime_end.Format("2006-01-02 15:04:05"))
		queue.CTUpdate_GetAllBetRecord(startime.Format("2006-01-02 15:04:05"), startime_end.Format("2006-01-02 15:04:05"))
		startime = startime_end
		time.Sleep(15 * time.Second)
	}

	/*
			d, _ := time.ParseDuration("+3h")                                 //转换服务器时间为中国时间
			for {
				if startime.Unix() >= endtime.Unix() {
					break
				}
				startime_end := startime.Add(d)
				fmt.Println(startime.Format("2006-01-02 15:04:05"), startime_end.Format("2006-01-02 15:04:05"))
				queue.Update_GetAllBetRecord(startime.Format("2006-01-02 15:04:05"), startime_end.Format("2006-01-02 15:04:05"))
				startime = startime_end
				time.Sleep(15 * time.Second)
			}

		bbinq := queue.NewQueue(60000) //6000的队列
		BBIN_verify_site
		bbin_queue_timer := time.NewTicker(2 * time.Second)
		ticker_bbin := time.NewTicker(240 * time.Second) //160
		defer func() {
			bbin_queue_timer.Stop()
			//ticker_bbin.Stop()
		}()

		for {
			select {
			//case <-ticker_bbin.C:
			//	go bbin_GetAllBetRecord()
			//	go BBIN_verify1()
			case timer := <-bbin_queue_timer.C:
				multi_ip := common.Cfg.Section("app").Key("LOCAL_IPS").MustString("")
				ips_arr := strings.Split(multi_ip, ",")
				if len(ips_arr) > 1 {
					for _, v := range ips_arr {
						go bbin_do_queue(bbinq.Pull(), v)
					}
				} else {
					go bbin_do_queue(bbinq.Pull(), "")
				}
			}

		}

	*/
}

func ag_conf_init() {
	host = common.Cfg.Section("ag").Key("FTP_HOST").MustString("br.agingames.com:21")
	username = common.Cfg.Section("ag").Key("FTP_USERNAME").MustString("E59.pkbet")
	password = common.Cfg.Section("ag").Key("FTP_PASSWORD").MustString("E&GZnLR7^ff")
	temppath = common.Cfg.Section("ag").Key("FTP_TEMP_PATH").MustString("/home/sxgames/bin/ag_report")
}
