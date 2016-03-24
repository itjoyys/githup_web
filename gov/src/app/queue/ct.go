package queue

import (
	"app/models"
	mct "app/models/ct"
	"common"

	"plug-ins/games/ct"
	"utility"
)

func ct_GetAllSiteBetRecord() {
	ids, err := models.GetAllSiteIdForCt()
	if err != nil {
		common.Log.Err("MG GetAllSiteIdForMg:", err)
		//邮件提示
		return
	}
	for _, v := range ids {
		ct_GetBetRecord(v)
	}
}

func CTUpdate_GetAllBetRecord(starttime, endtime string) {
	ids, err := models.GetAllSiteIdForCt()
	if err != nil {
		common.Log.Err("MG GetAllSiteIdForMg:", err)
		//邮件提示
		return
	}
	for _, v := range ids {
		ct_GetBetReordByDate(starttime, endtime, v)
	}
}

func ct_GetBetRecord(siteid string) {
	ct_g, err := ct.CrownTechnologyInit(siteid)
	if err != nil {
		common.Log.Err("CT GetBetInfoDetails:", err)
		//邮件提示
		return
	}
	brs, err := ct_g.GetBetRecord()
	if err != nil {
		common.Log.Err("CT GetBetRecord err:", err)
		//TODO 邮件日志
		return
	}
	if len(brs) == 0 {
		return
	}
	//err = mct.InsertBatchBetRecord(&brs)
	for _, v := range brs {
		v.Site_id = siteid
		users, err := mct.GetUserByGname([]string{v.Member_id})
		if err != nil || len(users) == 0 {
			common.Log.Err("ct GetUserByGname:", err, v.Member_id)
		} else {
			user, ok := users[v.Member_id]
			if ok {
				v.Site_id = user.SiteId
				v.Agent_id = user.AgentId
				v.PkUsername = user.UserName
			} else {
				common.Log.Err("ct GetUserByGname no exist user:", v.Member_id)
			}
		}
		mct.CreateBetRecord(&v)
	}
	if err != nil {
		common.Log.Err("CT InsertBatchBetRecord err:", err)
		//TODO 邮件日志
		return
	}
	var ids []string
	for _, v := range brs {
		ids = append(ids, utility.ToStr(v.Transaction_id, 10))
	}
	//添加成功后标记
	err = ct_g.MarkBetRecord(ids)
	if err != nil {
		common.Log.Err("CT MarkBetRecord err:", err)
		//TODO 邮件日志
		return
	}

	//try---catch---
	defer func() {
		if err := recover(); err != nil {
			stack := stack(3)
			common.Log.Errf("OG GetCashTrade PANIC: %s\n%s", err, stack)
			//发邮件报告
			return
		}
	}()

}

func ct_GetBetReordByDate(starttime, endtime, siteid string) {
	ct_g, err := ct.CrownTechnologyInit(siteid)
	if err != nil {
		common.Log.Err("CT GetBetInfoDetails:", err)
		//邮件提示
		return
	}
	brs, err := ct_g.GetBetRecordbyDate(starttime, endtime)
	if err != nil {
		common.Log.Err("CT GetBetRecord err:", err)
		//TODO 邮件日志
		return
	}
	if len(brs) == 0 {
		return
	}
	//err = mct.InsertBatchBetRecord(&brs)
	for _, v := range brs {
		v.Site_id = siteid
		users, err := mct.GetUserByGname([]string{v.Member_id})
		if err != nil || len(users) == 0 {
			common.Log.Err("ct GetUserByGname:", err, v.Member_id)
		} else {
			user, ok := users[v.Member_id]
			if ok {
				v.Site_id = user.SiteId
				v.Agent_id = user.AgentId
				v.PkUsername = user.UserName
			} else {
				common.Log.Err("ct GetUserByGname no exist user:", v.Member_id)
			}
		}
		mct.CreateBetRecord(&v)
	}
	if err != nil {
		common.Log.Err("CT InsertBatchBetRecord err:", err)
		//TODO 邮件日志
		return
	}

	//try---catch---
	defer func() {
		if err := recover(); err != nil {
			stack := stack(3)
			common.Log.Errf("OG GetCashTrade PANIC: %s\n%s", err, stack)
			//发邮件报告
			return
		}
	}()

}
