package queue

import (
	"encoding/xml"
	"time"

	"app/models"
	mmg "app/models/mg"
	"common"
	"fmt"

	"plug-ins/games/mg"
	//"utility"
)

func MG_Queue(datetime string) {
	mg_GetAllSiteBetInfoDetails(datetime)
}

func mg_GetAllSiteBetInfoDetails(datetime string) {
	ids, err := models.GetAllSiteIdForMg()
	if err != nil {
		common.Log.Err("MG GetAllSiteIdForMg:", err)
		//邮件提示
		return
	}
	startvtime, err := mmg.GetTimeline()
	if err == nil {
		mss, err := models.GetQueue("mg", startvtime)
		if err != nil {
			//队列不存在
			return
		}
		for _, v := range ids {
			mg_GetBetInfoDetails(v, startvtime.Format("2006-01-02 15:04:05"), mss.ID)
		}
		models.UpdateQueueing(mss.ID)
		mmg.StopTimeline() //执行一次
	} else {
		//正常执行
		for _, v := range ids {
			mg_GetBetInfoDetails(v, datetime, 0)
		}
	}
}

//自动采集
func mg_auto_collect(datetime string) {
	ids, err := models.GetAllSiteIdForMg()
	if err != nil {
		common.Log.Err("MG GetAllSiteIdForMg:", err)
		//邮件提示
		return
	}
	if err == nil {
		//正常执行
		for _, v := range ids {
			mg_GetBetInfoDetails(v, datetime, 0)
		}
	}
}

//GMT 服务器时区GMT-4  //1-2
//MG:：3分钟扫描一次，先获取guid，过1.5分钟后分页获取结果  （跟新日志写入数据库）
func mg_GetBetInfoDetails(siteid, datetime string, manualid int) {
	mg_g, err := mg.MGameInit(siteid)
	if err != nil {
		common.Log.Err("MG GetBetInfoDetails:", err)
		//邮件提示
		return
	}

	//当天的数据全部查询
	nowtime := time.Now()
	if len(datetime) > 0 {
		temptime, err := time.Parse("2006-01-02 15:04:05", datetime)
		if err == nil {
			nowtime = temptime
		}
	}
	d, _ := time.ParseDuration("+4h") //转换服务器时间为中国时间
	nowtime = nowtime.Add(d)
	nowhour := nowtime.Hour()
	var fromdate, todate string
	//如果是0-3点 获取前一天的数据
	if nowhour <= 1 {
		//3点以后，获取当天数据
		d, _ := time.ParseDuration("-48h") //转换服务器时间为中国时间
		oldtime := nowtime.Add(d)
		fromdate = fmt.Sprintf("%d-%02d-%02dT00:00:00", oldtime.Year(), oldtime.Month(), oldtime.Day())
		todate = fmt.Sprintf("%d-%02d-%02dT23:59:59.997", nowtime.Year(), nowtime.Month(), nowtime.Day())
	} else {
		d, _ := time.ParseDuration("-28h") //转换服务器时间为中国时间
		oldtime := nowtime.Add(d)
		//3点以后，获取当天数据
		fromdate = fmt.Sprintf("%d-%02d-%02dT00:00:00", oldtime.Year(), oldtime.Month(), oldtime.Day())
		todate = fmt.Sprintf("%d-%02d-%02dT23:59:59.997", nowtime.Year(), nowtime.Month(), nowtime.Day())
	}
	//common.Log.Info(time.Now(), fromdate, todate)
	guid, err := mg_g.GetBetInfoDetails(fromdate, todate, 100)
	if err != nil {
		common.Log.Err("MG GetBetInfoDetails:", err)
		//邮件提示
		return
	}
	if len(guid) == 0 {
		return
	}
	//5分钟后执行
	time.AfterFunc(90*time.Second, func() { mg_GetAllPagesRecord(siteid, guid, 1, manualid) })
	mg_g = nil

}

func mg_GetAllPagesRecord(siteid, guid string, pagenum, manualid int) {
	mg_g, err := mg.MGameInit(siteid)
	if err != nil {
		common.Log.Err("MG GetBetInfoDetails:", err)
		//邮件提示
		return
	}
	if len(guid) == 0 {
		return
	}
	result, err := mg_g.GetReportResult(guid, pagenum)
	if err != nil {
		common.Log.Err("MG GetBetInfoDetails xml:", err)
		//邮件提示
		return
	}
	if result.Status == "Complete" {
		//入数据库
		data := mg.NewDataSet{}
		err = xml.Unmarshal([]byte(result.CurrentPageData.DiffgrDiffgram.NewDataSet), &data)
		if err != nil {
			common.Log.Err("MG GetBetInfoDetails xml:", err,result)
			//邮件提示
			return
		}
		for k, v := range data.BetRecord {
			betno := fmt.Sprintf("%v", v)
			//common.Log.Err("BetRecord :", betno)
			data.BetRecord[k].BetNo = common.Md5(betno)
		}
		err = mmg.InsertBatchBetRecord(data.BetRecord, siteid)
		if err != nil {
			common.Log.Err("MG GetBetInfoDetails xml:", err)
			//邮件提示
			return
		}
		//获取下一页
		if result.Paging.TotalPage > pagenum {
			mg_GetAllPagesRecord(siteid, guid, pagenum+1, manualid)
		} else {
			if manualid > 0 {
				models.FinishQueue(manualid)
			}
		}
	} else if result.Status == "Pending" {
		time.AfterFunc(90*time.Second, func() { mg_GetAllPagesRecord(siteid, guid, 1, manualid) })
	} else {
		common.Log.Err("MG GetBetInfoDetails network error:", result.CurrentPageData.DiffgrDiffgram.NewDataSet)
	}

	mg_g = nil
}
