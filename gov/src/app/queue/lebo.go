package queue

import (
	"app/models"
	mlebo "app/models/lebo"
	"common"

	"plug-ins/games/lebo"
	"time"
	"utility"
	"fmt"
)

func lebo_GetAllBetRecord() {
	ids, err := models.GetAllSiteIdForLebo()
	if err != nil {
		common.Log.Err("MG GetAllSiteIdForMg:", err)
		//邮件提示
		return
	}
	for _, v := range ids {
		lebo_GetBetRecord(v)
	}
}

func Update_GetAllBetRecord(starttime, endtime string) {
	ids, err := models.GetAllSiteIdForLebo()
	if err != nil {
		common.Log.Err("MG GetAllSiteIdForMg:", err)
		//邮件提示
		return
	}
	for _, v := range ids {
		lebo_GetBetReordByDate(starttime, endtime, v)
	}
}

//param=2011-01-31 19:25:34|2011-01-31 19:25:34
func lebo_GetBetReordByDate(starttime, endtime, siteid string) {
	lebo_g, err := lebo.LeboTechnologyInit(siteid)

	if err != nil {
		common.Log.Err("Lebo GetBetRecord err:", err)
		//TODO 邮件日志
		return
	}
	brs, err := lebo_g.GetBetRecordByDate(starttime, endtime)
	if err != nil {
		common.Log.Err("Lebo GetBetRecord err:", err)
		//TODO 邮件日志
		return
	}
	if len(brs) == 0 {
		return
	}
	var ids []string
	for _, v := range brs {
		v.Site_id = siteid
		timeline, _ := utility.StrTo(v.Betstarttime).Int64()
		tm := time.Unix(timeline, 0) //时间问题
		loc, _ := time.LoadLocation("Asia/Shanghai")
		time2 := tm.In(loc)
		d, _ := time.ParseDuration("-12h") //转换服务器时间为中国时间
		time2 = time2.Add(d)
		v.UpdateTime = time2 //.Format("2006-01-02 03:04:05")
		err = mlebo.CreateBetRecord(&v)
		if err == nil {
			ids = append(ids, v.Gameid)
		}
		fmt.Println(v,err)
	}
	//err = mlebo.InsertBatchBetRecord(&brs)
	/*
			if err != nil {
				common.Log.Err("Lebo InsertBatchBetRecord err:", err)
				//TODO 邮件日志
				return
			}
		err = lebo_g.MarkBetRecord(ids)
		if err != nil {
			common.Log.Err("Lebo MarkBetRecord err:", err)
			//TODO 邮件日志
			return
		}
	*/
	//添加成功后标记
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

func lebo_GetBetRecord(siteid string) {
	lebo_g, err := lebo.LeboTechnologyInit(siteid)
	if err != nil {
		common.Log.Err("Lebo GetBetRecord err:", err)
		//TODO 邮件日志
		return
	}
	brs, err := lebo_g.GetBetRecord()
	if err != nil {
		common.Log.Err("Lebo GetBetRecord err:", err)
		//TODO 邮件日志
		return
	}
	if len(brs) == 0 {
		return
	}
	var ids []string
	for _, v := range brs {
		v.Site_id = siteid
		err = mlebo.CreateBetRecord(&v)
		if err == nil {
			ids = append(ids, v.Gameid)
		}
	}
	//err = mlebo.InsertBatchBetRecord(&brs)
	/*
		if err != nil {
			common.Log.Err("Lebo InsertBatchBetRecord err:", err)
			//TODO 邮件日志
			return
		}*/
	//添加成功后标记
	err = lebo_g.MarkBetRecord(ids)
	if err != nil {
		common.Log.Err("Lebo MarkBetRecord err:", err)
		//TODO 邮件日志
		return
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
