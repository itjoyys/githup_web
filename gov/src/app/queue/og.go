package queue

import (
	"app/models"
	mog "app/models/og"
	"common"

	"math"
	"plug-ins/games/og"
	"time"
	"utility"
)

func og_GetRecords() {
	ids, err := models.GetAllSiteIdForOg()

	if err != nil {
		common.Log.Err("og GetAllSiteIdForMg:", err)
		//邮件提示
		return
	}
	//加入队列
	for _, v := range ids {
		//账务
		og_GetCashRecord(v)
		//注单记录
		og_GetBettingRecord(v,0)
	}
}

func Og_GetRecords(vid int64) {
	ids, err := models.GetAllSiteIdForOg()

	if err != nil {
		common.Log.Err("og GetAllSiteIdForMg:", err)
		//邮件提示
		return
	}
	for _, v := range ids {
		//注单记录
		og_GetBettingRecord(v,vid)
	}
}

func og_GetCashRecord(siteid string) {
	og_g, err := og.OrientalGameInit(siteid)
	if err != nil {
		common.Log.Err("OG GetCashTrade:", err)
		//发邮件报告
		return
	}
	//og_g.AgentName = "og013dai" //只能用这个采集
	vid, err := mog.GetLastCashRecordVid(siteid)
	if err != nil {
		//发邮件报告
		return
	}

	html, err := og_g.GetCashTrade(utility.ToStr(vid, 10))
	if err != nil {
		common.Log.Err("OG GetCashTrade:", err)
		//发邮件报告
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

	ogcashs := make([]map[string]interface{}, 0)
	for _, v := range html.CashRecord {
		bet := make(map[string]interface{})
		for _, v1 := range v.Properties {
			key := common.SnakeCasedName(v1.Name)
			bet[key] = v1.Value
		}
		ogcashs = append(ogcashs, bet)
	}
	err = mog.InsertBatchCashRecord(ogcashs, siteid)
	if err != nil {
		common.Log.Err("OG InsertBatchGameRecord mysql error", err)
	}
	og_g = nil
}

func og_GetBettingRecord(siteid string, vid int64) (lastvid int64){
	lastvid = 0
	og_g, err := og.OrientalGameInit(siteid)
	if err != nil {
		common.Log.Err("OG GetCashTrade:", err)
		//发邮件报告
		return
	}
	//og_g.AgentName = "og013dai" //只能用这个采集
	if vid == 0 {
		vid, err = mog.GetLastBetRecorVid(siteid)
		if err != nil {
			//发邮件报告
			return
		}
	}

	html, err := og_g.GetBettingRecord(utility.ToStr(vid, 10))
	if err != nil {
		common.Log.Err("OG GetCashTrade:", err)
		//发邮件报告
		return
	}
	//try---catch---
	defer func() {
		if err := recover(); err != nil {
			stack := stack(3)
			common.Log.Errf("OG GetBettingRecord PANIC: %s\n%s", err, stack)
			//发邮件报告
			return
		}
	}()
	if len(html.BetRecord) == 0 {
		return
	}
	ogbets := make([]map[string]interface{}, 0)
	gusernames := []string{}
	gusernames_map := make(map[string]string)
	for _, v := range html.BetRecord {
		bet := make(map[string]interface{})
		for _, v1 := range v.Properties {
			key := common.SnakeCasedName(v1.Name)
			bet[key] = v1.Value
			if key == "user_name" {
				gusernames_map[v1.Value] = v1.Value
			}
		}
		//fmt.Println(bet)
		ogbets = append(ogbets, bet)
	}
	for _, k := range gusernames_map {
		gusernames = append(gusernames, k)
	}
	users, err := mog.GetUserByGname(gusernames)
	date_map := make(map[string][]map[string]interface{})
	if err != nil {
		common.Log.Err("og GetUserByGname:", err)
	} else {
		//添加username和agentid
		for _, v := range ogbets {
			user := users[v["user_name"].(string)]
			v["site_id"] = user.SiteId
			v["agent_id"] = user.AgentId
			v["pkusername"] = user.UserName
			//ogbets[k] = v
			bettime, _ := time.Parse("2006/1/2 15:04:05", v["add_time"].(string))
			date_map[bettime.Format("200601")] = append(date_map[bettime.Format("200601")], v)
		}
	}
	//分时间插入
	for k, v := range date_map {
		og_insertBatchBetRecord(v, k)
	}
	og_g = nil
	return
}

func og_insertBatchBetRecord(betRecords []map[string]interface{}, datestr string) error {
	//分批插入
	if len(betRecords) > 20 {
		count := len(betRecords)
		maxtimes := math.Ceil(float64(count) / float64(20))
		for i := 0; i < int(maxtimes); i++ {
			if i == int(maxtimes)-1 {
				//fmt.Println(arr[i*20 : count])
				err := mog.InsertBatchBetRecord(betRecords[i*20:count], datestr)
				if err != nil {
					common.Log.Err("og InsertBatchBetRecord sql error :", err, betRecords)
				}
			} else {
				//fmt.Println(arr[i*20 : (i+1)*20])
				err := mog.InsertBatchBetRecord(betRecords[i*20:(i+1)*20], datestr)
				if err != nil {
					common.Log.Err("og InsertBatchBetRecord sql error :", err, betRecords)
				}
			}
		}
	} else {
		err := mog.InsertBatchBetRecord(betRecords, datestr)
		if err != nil {
			common.Log.Err("ag InsertBatchBetRecord sql error :", err, betRecords)
			return err
		}

	}
	return nil
}
