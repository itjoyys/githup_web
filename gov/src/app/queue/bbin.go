package queue

import (
	"app/models"
	mbbin "app/models/bbin"
	"common"

	"plug-ins/games/bbin"
	"time"
	"utility"

	"math"
)

/*
func BBIN() {
	ids, err := models.GetAllSiteIdForBbin()
	if err != nil {
		common.Log.Err("BBIN GetAllSiteIdForBBIN:", err)
		//邮件提示
		return
	}
	date := "2015-07-16"
	starttime := "00:00:00"
	endtime := "23:59:59"

	for _, v := range ids {
		bbin_GetBetRecord(v, date, starttime, endtime)
		//time.Sleep(3 * time.Second)
	}

}

func BBIN_verify(date string) {
	ids, err := models.GetAllSiteIdForBbin()
	if err != nil {
		common.Log.Err("BBIN GetAllSiteIdForBBIN:", err)
		//邮件提示
		return
	}
	//date := "2015-07-15"
	starttime := "00:00:00"
	endtime := "23:59:59"

	for _, v := range ids {
		bbin_GetBetRecord(v, date, starttime, endtime)
		//time.Sleep(10 * time.Second)
		//采集更改的数据
		//bbin_GetBetRecordByModifiedDate3(v, date, date, 0)
		//time.Sleep(3 * time.Second)
	}
}
*/
//验证是否维护	//执行检查是否维护或者异常
var bbin_do_onetime int = 0

func bbin_auto_collect(datetime time.Time) {
	ids, err := models.GetAllSiteIdForBbin()
	if err != nil {
		common.Log.Err("BBIN GetAllSiteIdForBBIN:", err)
		//邮件提示
		return
	}
	date := datetime.Format("2006-01-02")
	starttime := "00:00:00"
	endtime := "23:59:59"
	bbin_do_onetime = 0
	for _, v := range ids {
		bbin_GetBetRecord(v, date, starttime, endtime, 0, false)
	}
}

func BBIN_verify1() {
	ids, err := models.GetAllSiteIdForBbin()
	if err != nil {
		common.Log.Err("BBIN GetAllSiteIdForBBIN:", err)
		//邮件提示
		return
	}

	//最后一次采集的时间
	_, vlasttime := mbbin.GetTimeline()
	//验证采集时间

	var date, starttime, endtime string
	var jintian time.Time

	jintian = time.Now()
	//日期为当天返回
	if jintian.YearDay() == vlasttime.YearDay() {
		return
	}
	//加3个小时
	date = vlasttime.Format("2006-01-02")
	starttime = vlasttime.Format("15:04:05")
	d, _ := time.ParseDuration("+3h") //12分钟前
	jintian = vlasttime.Add(d)
	//判断是否垮天
	if jintian.YearDay() != vlasttime.YearDay() {
		endtime = "23:59:59"
		jintian, _ = time.Parse("2006-01-02 15:04:05", time.Now().Format("2006-01-02")+" 00:00:00")
	} else {
		endtime = jintian.Format("15:04:05")
	}
	//更新时间
	startvtime, _ := time.Parse("2006-01-02 15:04:05", date+" 00:00:00")
	mss, err := models.GetQueue("bbin", startvtime)
	if err != nil {
		//队列不存在
		return
	}
	models.UpdateQueueing(mss.ID)
	for _, v := range ids {
		flag := bbin_GetBetRecord(v, date, starttime, endtime, mss.ID, true)
		if flag {
			return
		}
	}
	mbbin.UpdateModifiedTimeline(jintian)
}

func BBIN_verify_site(site_id string) {
	var ids []string
	if len(site_id) > 0 {
		ids = append(ids, site_id)
	} else {
		var err error
		ids, err = models.GetAllSiteIdForBbin()
		if err != nil {
			common.Log.Err("BBIN GetAllSiteIdForBBIN:", err)
			//邮件提示
			return
		}
	}
	//最后一次采集的时间
	_, vlasttime := mbbin.GetTimeline()
	//验证采集时间
	var date, starttime, endtime string
	var jintian time.Time

	jintian = time.Now()
	//日期为当天返回
	if jintian.YearDay() == vlasttime.YearDay() {
		return
	}
	//加3个小时
	date = vlasttime.Format("2006-01-02")
	starttime = vlasttime.Format("15:04:05")
	d, _ := time.ParseDuration("+3h") //12分钟前
	jintian = vlasttime.Add(d)
	//判断是否垮天
	if jintian.YearDay() != vlasttime.YearDay() {
		endtime = "23:59:59"
		jintian, _ = time.Parse("2006-01-02 15:04:05", time.Now().Format("2006-01-02")+" 00:00:00")
	} else {
		endtime = jintian.Format("15:04:05")
	}
	//更新时间
	startvtime, _ := time.Parse("2006-01-02 15:04:05", date+" 00:00:00")
	mss, err := models.GetQueue("bbin", startvtime)
	if err != nil {
		//队列不存在
		return
	}
	models.UpdateQueueing(mss.ID)
	for _, v := range ids {
		flag := bbin_GetBetRecord(v, date, starttime, endtime, mss.ID, true)
		if flag {
			return
		}
	}
	mbbin.UpdateModifiedTimeline(jintian)
}

func bbin_GetAllBetRecord() {
	ids, err := models.GetAllSiteIdForBbin()
	if err != nil {
		common.Log.Err("BBIN GetAllSiteIdForBBIN:", err)
		//邮件提示
		return
	}

	//最后一次采集的时间
	lasttime, _ := mbbin.GetTimeline()

	//正常采集
	var date, starttime, endtime string
	var jintian time.Time
	jintian = time.Now()
	//如果是同一天，采集12分钟前的
	date = lasttime.Format("2006-01-02")
	starttime = lasttime.Format("15:04:05")
	//endtime = jintian.Format("15:04:05")

	if jintian.YearDay() != lasttime.YearDay() {
		endtime = "23:59:59"
		//jintian = time.Now()
	} else {
		endtime = jintian.Format("15:04:05")
	}

	d, _ := time.ParseDuration("-8m") //8分钟前
	jintian = jintian.Add(d)

	//更新时间
	for _, v := range ids {
		flag := bbin_GetBetRecord(v, date, starttime, endtime, 0, false)
		if flag {
			return
		}
	}
	mbbin.UpdateTimeline(jintian)
}

func bbin_do_queue(pam BbinPramar, ipaddr string) {
	//fmt.Println(pam)
	if len(pam.siteid) == 0 {
		return
	}
	bbin_GetRecordPage(pam.siteid, pam.date, pam.starttime, pam.endtime, pam.typeid,
		pam.gametype, pam.subgamekind, ipaddr, pam.page, pam.queueid, pam.ismanual)
}

/**
ismanual 是否是手动采集
*/
func bbin_GetBetRecord(siteid, date, starttime, endtime string, queueid int, ismanual bool) bool {

	//1：球类、3：视讯、5：机率、12：彩票、15：3D厅
	for i := 0; i < 7; i++ {

		typeid := "1"
		gametype := ""
		subgamekind := ""
		switch i {
		case 0:
			typeid = "1"
		case 1:
			typeid = "3"
		case 2:
			typeid = "5"
			subgamekind = "1"
		case 3:
			typeid = "12"
			gametype = "OTHER"
		case 4:
			typeid = "15"
		case 5:
			gametype = "LT"
			typeid = "12"
		case 6:
			typeid = "5"
			subgamekind = "2"
		}
		//var starttime1, endtime1 string
		//TODO 体育的查询方式
		/*
			if typeid == "12" || typeid == "1" {
				starttime1 = "00:00:00"
				endtime1 = "23:59:59"
			} else {
				starttime1 = starttime
				endtime1 = endtime
			}
		*/
		if bbin_do_onetime == 0 {
			flag := bbin_GetRecordPage(siteid, date, starttime, endtime, typeid, gametype, subgamekind, "", 1, queueid, ismanual)
			if flag {
				return true
			}
			bbin_do_onetime = 1
		} else {
			//其他
			bp := BbinPramar{}
			bp.siteid = siteid
			bp.date = date
			bp.starttime = starttime
			bp.endtime = endtime
			bp.typeid = typeid
			bp.gametype = gametype
			bp.subgamekind = subgamekind
			bp.ismanual = ismanual
			bp.queueid = queueid
			bp.page = 1
			if !bbinq.Push(bp) {
				common.Log.Err("bbin QUEUE IS FULL:")
			}
		}
	}

	//try---catch---
	defer func() {
		if err := recover(); err != nil {
			stack := stack(3)
			common.Log.Errf("Lebo GetCashTrade PANIC: %s\n%s", err, stack)
			//发邮件报告
			return
		}
	}()

	return false
}

func bbin_GetRecordPage(siteid, date, starttime, endtime, typeid, gametype, subgamekind, ipaddr string, page, queueid int, ismanual bool) bool {
	bp := BbinPramar{}
	bbin_g, err := bbin.BbinInit(siteid)
	if err != nil {
		common.Log.Err("Bbin GetBetRecord err:", err)
		//TODO 邮件日志
		return false
	}
	brs, err := bbin_g.BetRecord(ipaddr, date, starttime, endtime, typeid, gametype, subgamekind, page)
	if err != nil {
		common.Log.Err("bbin GetBetRecord err:", err)
		//TODO 邮件日志
		//异常
		return true
	}
	rows := brs.Data
	if len(rows) == 0 {
		return false
	}
	gusernames := []string{}
	gusernames_map := make(map[string]string)
	for _, v := range rows {
		gusernames_map[v.UserName] = v.UserName
	}
	for _, k := range gusernames_map {
		gusernames = append(gusernames, k)
	}
	//过滤不存在的用户
	users, err := mbbin.GetUserByGname(gusernames)
	if err != nil || len(users) == 0 {
		common.Log.Err("bbin GetUserByGname:", gusernames, err)
	} else {
		//添加username和agentid
		for k, v := range rows {
			user, ok := users[v.UserName]
			if ok {
				v.Site_id = user.SiteId
				v.Agent_id = user.AgentId
				v.PkUsername = user.UserName
				rows[k] = v
			} else {
				common.Log.Err("bbin GetUserByGname not exist user name:", v.UserName)
			}
		}
	}
	dbtime, _ := time.Parse("2006-01-02 15:04:05", date+" "+starttime)
	datestr := dbtime.Format("200601")
	idd, _ := utility.StrTo(typeid).Int()
	if len(brs.Data) > 20 {
		count := len(brs.Data)
		maxtimes := math.Ceil(float64(count) / float64(20))
		for i := 0; i < int(maxtimes); i++ {
			if i == int(maxtimes)-1 {
				//fmt.Println(arr[i*20 : count])
				err := mbbin.InsertBatchBetRecord(rows[i*20:count], idd, siteid, datestr)
				if err != nil {
					common.Log.Err("bbin InsertBatchBetRecord sql error :", err, rows[i*20:count])
				}
			} else {
				//fmt.Println(arr[i*20 : (i+1)*20])
				err := mbbin.InsertBatchBetRecord(rows[i*20:(i+1)*20], idd, siteid, datestr)
				if err != nil {
					common.Log.Err("bbin InsertBatchBetRecord sql error :", err, rows[i*20:(i+1)*20])
				}
			}
		}
	} else {
		if len(brs.Data) > 0 {
			mbbin.InsertBatchBetRecord(rows, idd, siteid, datestr)
		}
	}
	str_br_page := utility.ToStr(brs.Pagination.TotalPage)
	br_page, _ := utility.StrTo(str_br_page).Int()
	if br_page > page {
		bp.siteid = siteid
		bp.date = date
		bp.starttime = starttime
		bp.endtime = endtime
		bp.typeid = typeid
		bp.gametype = gametype
		bp.subgamekind = subgamekind
		bp.ismanual = ismanual
		bp.queueid = queueid
		bp.page = page + 1
		if !bbinq.Push(bp) {
			common.Log.Err("bbin QUEUE IS FULL:")
		}
		//time.AfterFunc(12*time.Second, func() { bbin_GetRecordPage(siteid, date, starttime, endtime, typeid, gametype, page+1) })
	} else {
		if endtime == "23:59:59" && ismanual {
			models.FinishQueue(queueid)
		}
	}

	//try---catch---
	defer func() {
		if err := recover(); err != nil {
			stack := stack(3)
			common.Log.Errf("Lebo GetCashTrade PANIC: %s\n%s", err, stack)
			//发邮件报告
			return
		}
	}()

	return false
}

/*
func bbin_insertBatchBetRecord(rows []BbinBetRecord_form_Jsion,typeid,datastr string) error{

	idd, _ := utility.StrTo(typeid).Int()
	if len(brs.Data) > 20 {
		count := len(brs.Data)
		maxtimes := math.Ceil(float64(count) / float64(20))
		for i := 0; i < int(maxtimes); i++ {
			if i == int(maxtimes)-1 {
				//fmt.Println(arr[i*20 : count])
				err := mbbin.InsertBatchBetRecord(rows[i*20:count], idd, siteid)
				if err != nil {
					common.Log.Err("bbin InsertBatchBetRecord sql error :", err, rows[i*20:count])
				}
			} else {
				//fmt.Println(arr[i*20 : (i+1)*20])
				err := mbbin.InsertBatchBetRecord(rows[i*20:(i+1)*20], idd, siteid)
				if err != nil {
					common.Log.Err("bbin InsertBatchBetRecord sql error :", err, rows[i*20:(i+1)*20])
				}
			}
		}
	} else {
		if len(brs.Data) > 0 {
			mbbin.InsertBatchBetRecord(rows, idd, siteid)
		}
	}
	}
*/

//获取更改的 7天以內的数据
func bbin_BetRecordByModifiedDate3() {
	ids, err := models.GetAllSiteIdForBbin()
	if err != nil {
		common.Log.Err("BBIN GetAllSiteIdForBBIN:", err)
		//邮件提示
		return
	}
	//采集7天以内的数据
	k := time.Now()
	d, _ := time.ParseDuration("-12h")
	starttime := k.Add(d * 4)
	//mbbin.UpdateModifiedTimeline(jintian)

	for _, v := range ids {
		//BetRecordByModifiedDate3(date_start, date_end, gamekind, gametype string, page int64)
		bbin_GetBetRecordByModifiedDate3(v, starttime.Format("2006/01/02"), k.Format("2006/01/02"), 1)
		time.Sleep(320 * time.Second)
	}
}

func bbin_GetBetRecordByModifiedDate3(siteid, date_start, date_end string, page int) {
	bbin_g, err := bbin.BbinInit(siteid)
	if err != nil {
		common.Log.Err("Bbin BetRecordByModifiedDate3 err:", err)
		//TODO 邮件日志
		return
	}

	for i := 0; i < 7; i++ {

		typeid := "1"
		gametype := ""
		subgamekind := ""
		switch i {
		case 0:
			typeid = "1"
		case 1:
			typeid = "3"
		case 2:
			typeid = "5"
			subgamekind = "1"
		case 3:
			typeid = "12"
			gametype = "OTHER"
		case 4:
			typeid = "15"
		case 5:
			gametype = "LT"
			typeid = "12"
		case 6:
			typeid = "5"
			subgamekind = "2"
		}

		//1：球类、3：视讯、5：机率、12：彩票、15：3D厅
		brs, err := bbin_g.BetRecordByModifiedDate3(date_start, date_end, typeid, gametype, subgamekind, page)
		if err != nil {
			common.Log.Err("bbin BetRecordByModifiedDate3 err:", err)
			//TODO 邮件日志
			return
		}
		//入库
		//根据参数 获取分页数据
		str_br_page := utility.ToStr(brs.Pagination.TotalPage)
		br_page, _ := utility.StrTo(str_br_page).Int()
		if br_page > page {
			time.AfterFunc(8*time.Second, func() {
				bbin_GetRecordModifiedPage(siteid, date_start, date_end, typeid, gametype, subgamekind, page+1)
			})
		}

		//idd, _ := utility.StrTo(typeid).Int()
		mbbin.UpdateBatchBetRecord(brs.Data)

		time.Sleep(320 * time.Second)
	}
	//try---catch---
	defer func() {
		if err := recover(); err != nil {
			stack := stack(3)
			common.Log.Errf("Lebo GetCashTrade PANIC: %s\n%s", err, stack)
			//发邮件报告
			return
		}
	}()
}

func bbin_GetRecordModifiedPage(siteid, date_start, date_end, typeid, gametype, subgamekind string, page int) {
	bbin_g, err := bbin.BbinInit(siteid)
	if err != nil {
		common.Log.Err("Bbin GetBetRecord err:", err)
		//TODO 邮件日志
		return
	}
	brs, err := bbin_g.BetRecordByModifiedDate3(date_start, date_end, typeid, gametype, subgamekind, page)
	if err != nil {
		common.Log.Err("bbin GetBetRecord err:", err)
		//TODO 邮件日志
		return
	}
	//入库
	//根据参数 获取分页数据
	idd, _ := utility.StrTo(typeid).Int()

	dbtime, _ := time.Parse("2006/01/02", date_start)
	datestr := dbtime.Format("200601")
	mbbin.InsertBatchBetRecord(brs.Data, idd, siteid, datestr)

	str_br_page := utility.ToStr(brs.Pagination.TotalPage)
	br_page, _ := utility.StrTo(str_br_page).Int()
	if br_page > page {
		time.AfterFunc(3*time.Second, func() {
			bbin_GetRecordModifiedPage(siteid, date_start, date_end, typeid, gametype, subgamekind, page+1)
		})
	}
}
