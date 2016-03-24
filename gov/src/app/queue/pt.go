package queue

import (
	"app/models"
	mpt "app/models/pt"
	"common"
	"time"

	"fmt"
	"plug-ins/games/pt"
)

func Pt_GetAllBets() {
	pt_GetAllBets()
}

func pt_GetAllBets() {

	//PT时间为北京时间
	nowtime := time.Now()
	loc, _ := time.LoadLocation("Asia/Shanghai")
	time2 := nowtime.In(loc)
	time3 := nowtime.In(loc)
	d, _ := time.ParseDuration("-30m") //读取最近8分钟的
	time2 = time2.Add(d)
	starttime := time2.Format("2006-01-02 15:04:05")
	endtime := time3.Format("2006-01-02 15:04:05")
	pt_GetAllSiteBetInfoDetails(starttime, endtime)
}

func PTGetAllBets() {
	pt_GetAllBets()

}

func pt_GetAllSiteBetInfoDetails(starttime, endtime string) {
	startvtime, err := mpt.GetTimeline()
	if err == nil {
		mss, err := models.GetQueue("pt", startvtime)
		if err != nil {
			//队列不存在
			return
		}
		starttime = fmt.Sprintf("%d-%02d-%02d 00:00:00", startvtime.Year(), startvtime.Month(), startvtime.Day())
		endtime = fmt.Sprintf("%d-%02d-%02d 23:59:59", startvtime.Year(), startvtime.Month(), startvtime.Day())
		pt_GetAllBetRecord(starttime, endtime)
		models.UpdateQueueing(mss.ID)
		mpt.StopTimeline() //执行一次
		models.FinishQueue(mss.ID)
	} else {
		//正常执行
		pt_GetAllBetRecord(starttime, endtime)
	}
}

//自动采集
func pt_GetAllBetRecord(starttime, endtime string) {
	page := 1
	pt_GetBetRecord(starttime, endtime, page)
}

func pt_GetBetRecord(starttime, endtime string, page int) {
	pt_g, err := pt.PtInit()
	if err != nil {
		common.Log.Err("Pt pt_GetBetRecord:", err)
		//邮件提示
		return
	}
	brs, err := pt_g.GetBetRecord(starttime, endtime, page)
	if err != nil {
		common.Log.Err("PT GetBetRecord err:", err)
		//TODO 邮件日志
		return
	}
	if len(brs.Result) == 0 {
		return
	}
	for _, v := range brs.Result {
		users, err := mpt.GetUserByGname([]string{v.PlayerName})
		if err != nil || len(users) == 0 {
			//common.Log.Err("pt GetUserByGname:", err, v.PlayerName)
		} else {
			user, ok := users[v.PlayerName]
			if ok {
				v.Site_id = user.SiteId
				v.Agent_id = user.AgentId
				v.PkUsername = user.UserName
				v.Index_id = user.IndexId
			} else {
				common.Log.Err("pt GetUserByGname no exist user:", v.PlayerName)
			}
		}
		mpt.CreateBetRecord(&v)
	}
	if err != nil {
		common.Log.Err("pt InsertBatchBetRecord err:", err)
		//TODO 邮件日志
		return
	}
	if brs.Pagination.TotalPage > page {
		time.AfterFunc(3*time.Second, func() {
			pt_GetBetRecord(starttime, endtime, page+1)
		})
	}

}
