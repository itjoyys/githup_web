package mg

import (
	m "app/models"
	"common"
	"utility"

	"encoding/xml"
	"strings"
	"time"
	//"fmt"
)

//mg注单存储
/*
type MgBetRecord struct {
	XMLName       xml.Name  `xorm:"-" xml:"Table"`
	BetNo         string    `xorm:"pk 'bet_no'" xml:"-"`
	AccountNumber string    `xorm:"'account_number'" xml:"AccountNumber"`
	Income        float64   `xorm:"'income'" xml:"Income"`
	Payout        float64   `xorm:"'payout'" xml:"Payout"`
	WinAmount     float64   `xorm:"'win_amount'" xml:"WinAmount"`
	LoseAmount    float64   `xorm:"'lose_amount'" xml:"LoseAmount"`
	Date          string    `xorm:"'date'" xml:"Date"`
	GameType      string    `xorm:"'game_type'" xml:"GameType"`
	NetCash       float64   `xorm:"'net_cash'" xml:"NetCash"`
	NetWin        float64   `xorm:"'net_win'" xml:"NetWin"`
	ModuleId      int       `xorm:"'module_id'" xml:"ModuleId"`
	ClientId      string    `xorm:"'client_id'" xml:"ClientId"`
	UpdateTime    time.Time `xorm:"'update_time' created"`
}
*/
//新结构
type MgBetRecord struct {
	XMLName       xml.Name `xorm:"-" xml:"Table"`
	Id            int64    `xorm:"'id'" xml:"-"`
	BetNo         string   `xorm:"pk 'bet_no'" xml:"-"`
	AccountNumber string   `xorm:"'account_number'" xml:"AccountNumber"`
	Income        float64  `xorm:"'income'" xml:"-"`
	Payout        float64  `xorm:"'payout'" xml:"-"`
	WinAmount     float64  `xorm:"'win_amount'" xml:"-"`
	LoseAmount    float64  `xorm:"'lose_amount'" xml:"-"`

	All_Income     float64 `xorm:"'all_income'" xml:"Income"`
	All_Payout     float64 `xorm:"'all_payout'" xml:"Payout"`
	All_WinAmount  float64 `xorm:"'all_win_amount'" xml:"WinAmount"`
	All_LoseAmount float64 `xorm:"'all_lose_amount'" xml:"LoseAmount"`

	Date     string  `xorm:"'date'" xml:"Date"`
	GameType string  `xorm:"'game_type'" xml:"GameType"`
	NetCash  float64 `xorm:"'net_cash'" xml:"NetCash"`
	NetWin   float64 `xorm:"'net_win'" xml:"NetWin"`

	All_NetCash float64 `xorm:"'all_net_cash'" xml:"-"`
	All_NetWin  float64 `xorm:"'all_net_win'" xml:"-"`

	ModuleId   int       `xorm:"'module_id'" xml:"ModuleId"`
	ClientId   string    `xorm:"'client_id'" xml:"ClientId"`
	Site_id    string    `xorm:"'site_id'" json:"-"`
	Agent_id   int       `xorm:"'agent_id'" xml:"-"`
	Index_id   string       `xorm:"'index_id'" xml:"-"`
	PkUsername string    `xorm:"'pkusername'" xml:"-"`
	UpdateTime time.Time `xorm:"'update_time' created"`
}

func (a *MgBetRecord) TableName() string {
	return "mg_bet_record"
}

func getBetTableName(datestr string) string {
	return "mg_bet_record"
}

func GetLastDatabyDate(account_number, datetime, client_id string, module_id int) (*MgBetRecord, error) {
	mbr := new(MgBetRecord)
	datetime = strings.Replace(datetime, "T", " ", -1)
	//common.Log.Err("GetLastDatabyDate MG BetRecord :", account_number, datetime, module_id, client_id)
	_, err := m.Orm.Table("mg_bet_record").Where("account_number = ? AND `date`=? AND module_id=? AND client_id=?",
		account_number, datetime, module_id, client_id).OrderBy("update_time DESC").Get(mbr)

	return mbr, err
}

func CreateBetRecord(row *MgBetRecord) error {
	//时间转换 2015-07-08T00:00:00 如果是同一天，而且数据不一样
	//测试数据库插入
	bettime, err := time.Parse("2006-01-02T15:04:05", row.Date)
	if err != nil {
		common.Log.Err("Insert MG BetRecord :", err)
		return err
	}
	//common.Log.Err("GetLastDatabyDate MG BetRecord error:", row.Date, bettime, bettime.Format("200601"))
	//查询当天是否已经有数据了
	lastbet, err := GetLastDatabyDate(row.AccountNumber, row.Date, row.ClientId, row.ModuleId)
	if err != nil {
		common.Log.Err("GetLastDatabyDate MG BetRecord error:", err)
	}
	//common.Log.Err("GetLastDatabyDate MG BetRecord :", lastbet.BetNo, lastbet.AccountNumber)
	if len(lastbet.AccountNumber) > 0 && lastbet.ModuleId > 0 {
		//如果没有变化返回
		if lastbet.All_Income == row.All_Income &&
			lastbet.All_LoseAmount == row.All_LoseAmount &&
			lastbet.All_NetCash == row.All_NetCash &&
			lastbet.All_NetWin == row.All_NetWin &&
			lastbet.All_Payout == row.All_Payout &&
			lastbet.All_WinAmount == row.All_WinAmount {
			return nil
		} else {
			//有变化，存差值
			row.Income = row.All_Income - lastbet.All_Income
			row.LoseAmount = row.All_LoseAmount - lastbet.All_LoseAmount
			row.NetCash = row.All_NetCash - lastbet.All_NetCash
			row.NetWin = row.All_NetWin - lastbet.All_NetWin
			row.Payout = row.All_Payout - lastbet.All_Payout
			row.WinAmount = row.All_WinAmount - lastbet.All_WinAmount
		}
	} else {
		//无数据，保存
		row.Income = row.All_Income
		row.LoseAmount = row.All_LoseAmount
		row.NetCash = row.All_NetCash
		row.NetWin = row.All_NetWin
		row.Payout = row.All_Payout
		row.WinAmount = row.All_WinAmount
	}
	//插入数据
	//m.Orm
	sqlstr := "INSERT INTO `"+getBetTableName(bettime.Format("200601"))+"` (`bet_no`, `account_number`, `income`, `payout`, `win_amount`, `lose_amount`, `all_income`, `all_payout`, `all_win_amount`, `all_lose_amount`, `all_net_cash`, `all_net_win`, `date`, `game_type`, `net_cash`, `net_win`, `module_id`, `client_id`, `site_id`, `agent_id`, `index_id`, `pkusername`, `update_time`) "+
	"VALUES ( '"+row.BetNo+"', '"+row.AccountNumber+"','"+utility.ToStr(row.Income)+"', '"+utility.ToStr(row.Payout)+"', '"+utility.ToStr(row.WinAmount)+"', '"+utility.ToStr(row.LoseAmount)+"', '"+utility.ToStr(row.All_Income)+"', '"+utility.ToStr(row.All_Payout)+"', '"+utility.ToStr(row.All_WinAmount)+"', '"+utility.ToStr(row.All_LoseAmount)+"', '"+utility.ToStr(row.All_NetCash)+"', '"+utility.ToStr(row.All_NetWin)+"', '"+row.Date+"', '"+
	row.GameType+"', '"+utility.ToStr(row.NetCash)+"', '"+utility.ToStr(row.NetWin)+"', '"+utility.ToStr(row.ModuleId)+"', '"+row.ClientId+"', '"+row.Site_id+"','"+utility.ToStr(row.Agent_id)+"','"+row.Index_id+"', '"+row.PkUsername+"', '"+time.Now().Format("2006-01-02 15:04:05")+"')"
	sqlstr = strings.Replace(sqlstr,"''","' '",-1)
	_, err = m.Orm.Exec(sqlstr)
	if err != nil {
		if strings.Index(err.Error(), "for key") > 0 && strings.Index(err.Error(), "Duplicate entry") > 0 {
			//TODO 重复的更新

		} else {
			common.Log.Err("Insert MG BetRecord :", err, row)
		}
	}

	return err
}

func InsertBatchBetRecord(rows []MgBetRecord, site_id string) error {
	//m.Orm.Table(getBetTableName())

	for _, row := range rows {
		row.Site_id = site_id
		//获取id
		users, err := GetUserByGname([]string{row.AccountNumber})
		if err != nil {
			common.Log.Err("mg GetUserByGname:", err)
		} else {
			user, ok := users[row.AccountNumber]
			if ok {
				row.Site_id = user.SiteId
				row.Agent_id = user.AgentId
				row.PkUsername = user.UserName
			}
		}

		CreateBetRecord(&row)
	}

	return nil
}

func GetBetRecords(username, orderid, siteid, agentid, video_type, game_type, s_time, e_time string, pagenum, page int64) ([]MgBetRecord, int64, error) {
	where := ""
	whereparam := make([]interface{}, 0)
	allsiteid := common.Cfg.Section("app").Key("MANAGE_SITEID").MustString("manage")
	if allsiteid != siteid {
		where = "site_id = ?"
		whereparam = append(whereparam, siteid)
	}
	if len(orderid) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		where += "bet_no = ?"
		whereparam = append(whereparam, orderid)
	} else {
		if len(username) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}

			names := strings.Split(username, "|")
			where = where + "pkusername in (" + strings.Trim(strings.Repeat("?,", len(names)), ",") + ")"
			for _, v := range names {
				whereparam = append(whereparam, v)
			}
			//where = where + "account_number = ?"
			//whereparam = append(whereparam, username)
		} else if len(agentid) > 0 && len(siteid) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			ids := strings.Split(agentid, "|")
			where = where + "agent_id in(" +
				strings.Trim(strings.Repeat("?,", len(ids)), ",") + ")"
			for _, v := range ids {
				whereparam = append(whereparam, v)
			}
		}
		if len(video_type) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			where = where + "module_id in (25,28,29,30,32)"
			if video_type != "all" {
				where = where + " AND game_type = ?"
				whereparam = append(whereparam, video_type)
			}
		} else if len(game_type) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			where = where + "module_id not in (25,28,29,30,32)"
			if game_type != "all" {
				where = where + " AND game_type = ?"
				whereparam = append(whereparam, game_type)
			}
		}
		if len(s_time) > 0 || len(e_time) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			if len(s_time) > 0 && len(e_time) > 0 {
				where = where + "`date` BETWEEN ? AND ?"
				whereparam = append(whereparam, s_time)
				whereparam = append(whereparam, e_time)
			} else if len(s_time) > 0 && len(e_time) == 0 {
				where = where + "`date` >= ?"
				whereparam = append(whereparam, s_time)
			} else if len(s_time) == 0 && len(e_time) > 0 {
				where = where + "`date` <= ?"
				whereparam = append(whereparam, e_time)
			}
		}
	}

	betrecord := new(MgBetRecord)
	total, err := m.Orm.Table("mg_bet_record").Where(where, whereparam...).Count(betrecord)
	if err != nil {
		common.Log.Err("MG GetBetRecords Count :", err)
		return nil, 0, err
	}
	if total > 0 {
		tp := total / pagenum
		if total%pagenum != 0 {
			tp = tp + 1
		}
		statid := (page - 1) * pagenum
		var betrecords []MgBetRecord
		err = m.Orm.Table("mg_bet_record").Where(where, whereparam...).Limit(int(pagenum), int(statid)).Desc("update_time").Find(&betrecords)
		if err != nil {
			return nil, 0, err
		} else {
			return betrecords, total, nil
		}
	}

	return nil, 0, nil
}

/**
//获取总额度
$data['BetMoneyAll']='100.00';//当页有效投注金额 总计
$data['BackMoneyAll']='200.00';//当页退水  总计
$data['ResultMoneyAll']='300.00';//总计結果(派彩金额)  总计
BetMoneyAll,BackMoneyAll,ResultMoneyAll
**/
func GetAllMonery(username, orderid, siteid, agentid, video_type, game_type, s_time, e_time string, pagenum, page int64) (float64, float64, float64, error) {
	where := ""
	whereparam := make([]interface{}, 0)
	allsiteid := common.Cfg.Section("app").Key("MANAGE_SITEID").MustString("manage")
	if allsiteid != siteid {
		where = "site_id = ?"
		whereparam = append(whereparam, siteid)
	}
	if len(orderid) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		where += "bet_no = ?"
		whereparam = append(whereparam, orderid)
	} else {
		if len(username) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			names := strings.Split(username, "|")
			where = where + "pkusername in (" + strings.Trim(strings.Repeat("?,", len(names)), ",") + ")"
			for _, v := range names {
				whereparam = append(whereparam, v)
			}
		} else if len(agentid) > 0 && len(siteid) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			ids := strings.Split(agentid, "|")
			where = where + "agent_id in(" +
				strings.Trim(strings.Repeat("?,", len(ids)), ",") + ") AND site_id = ?"
			for _, v := range ids {
				whereparam = append(whereparam, v)
			}
			whereparam = append(whereparam, siteid)
		}
		if len(video_type) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			where = where + "module_id in (28,29,30,32)"
			if video_type != "all" {
				where = where + " AND game_type = ?"
				whereparam = append(whereparam, video_type)
			}
		} else if len(game_type) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			where = where + "module_id not in (28,29,30,32)"
			if game_type != "all" {
				where = where + " AND game_type = ?"
				whereparam = append(whereparam, game_type)
			}
		}
		if len(s_time) > 0 || len(e_time) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			if len(s_time) > 0 && len(e_time) > 0 {
				where = where + "date BETWEEN ? AND ?"
				whereparam = append(whereparam, s_time)
				whereparam = append(whereparam, e_time)
			} else if len(s_time) > 0 && len(e_time) == 0 {
				where = where + "date >= ?"
				whereparam = append(whereparam, s_time)
			} else if len(s_time) == 0 && len(e_time) > 0 {
				where = where + "date <= ?"
				whereparam = append(whereparam, e_time)
			}
		}
	}

	var bb, wb float64
	statement := "SELECT sum(income) as bb,sum(payout) as wb FROM `mg_bet_record` WHERE " + where
	rows1, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return 0, 0.00, 0, err
	}
	if len(rows1) > 0 {
		bb, _ = utility.StrTo(rows1[0]["bb"]).Float64()
		wb, _ = utility.StrTo(rows1[0]["wb"]).Float64()
	}

	return bb, 0.00, wb - bb, err
}

//siteid,, agentid int64
func GetAvailableAmountByUser(username, siteid, s_time, e_time string, isdz bool) (int, float64, float64, error) {
	var bb, wb float64
	var times int
	where := ""
	whereparam := make([]interface{}, 0)
	if !isdz {
		where = "module_id in (28,29,30,32)"
	} else {
		where = "module_id not in (28,29,30,32)"
	}
	if len(username) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}

		users := strings.Split(username, "|")
		where = where + "pkusername in(" + strings.Trim(strings.Repeat("?,", len(users)), ",") + ") AND site_id = ?"
		for _, v := range users {
			whereparam = append(whereparam, v)
		}
		whereparam = append(whereparam, siteid)
	}
	if len(s_time) > 0 || len(e_time) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		if len(s_time) > 0 && len(e_time) > 0 {
			where = where + "update_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "update_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "update_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, sum(income) as bb FROM `mg_bet_record` WHERE " + where
	rows1, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return 0, 0, 0, err
	}
	if len(rows1) > 0 {
		bb, _ = utility.StrTo(rows1[0]["bb"]).Float64()
		times, _ = utility.StrTo(rows1[0]["times"]).Int()
	}

	statement = "SELECT  sum(payout) as wb FROM `mg_bet_record` WHERE " + where
	rows2, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return 0, 0, 0, err
	}
	if len(rows2) > 0 {
		wb, _ = utility.StrTo(rows2[0]["wb"]).Float64()
	}
	return times, bb, wb, err
}

//根据站点获取
//去掉电子
func GetAvailableAmountBySiteid(siteid, s_time, e_time string, isdz bool) (int, float64, float64, error) {
	var bb, wb float64
	var times int
	where := ""
	whereparam := make([]interface{}, 0)
	if !isdz {
		where = "module_id in (28,29,30,32)"
	} else {
		where = "module_id not in (28,29,30,32)"
	}
	if len(siteid) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		ids := strings.Split(siteid, "|")
		where = where + "site_id in(" +
			strings.Trim(strings.Repeat("?,", len(ids)), ",") + ")"
		for _, v := range ids {
			whereparam = append(whereparam, v)
		}
	}
	if len(s_time) > 0 || len(e_time) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		if len(s_time) > 0 && len(e_time) > 0 {
			where = where + "date BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "date >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "date <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, sum(income) as bb FROM `mg_bet_record` WHERE " + where
	rows1, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return 0, 0, 0, err
	}
	if len(rows1) > 0 {
		bb, _ = utility.StrTo(rows1[0]["bb"]).Float64()
		times, _ = utility.StrTo(rows1[0]["times"]).Int()
	}

	statement = "SELECT  sum(payout) as wb FROM `mg_bet_record` WHERE " + where
	rows2, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return 0, 0, 0, err
	}
	if len(rows2) > 0 {
		wb, _ = utility.StrTo(rows2[0]["wb"]).Float64()
	}
	return times, bb, wb, err
}

func GetAvailableAmountBySiteids(siteid, s_time, e_time string, isdz bool) (map[string]map[string][]byte, error) {

	where := ""
	whereparam := make([]interface{}, 0)
	if !isdz {
		where = "module_id in (28,29,30,32)"
	} else {
		where = "module_id not in (28,29,30,32)"
	}
	if len(siteid) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		ids := strings.Split(siteid, "|")
		where = where + "site_id in(" +
			strings.Trim(strings.Repeat("?,", len(ids)), ",") + ")"
		for _, v := range ids {
			whereparam = append(whereparam, v)
		}
	}
	if len(s_time) > 0 || len(e_time) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		if len(s_time) > 0 && len(e_time) > 0 {
			where = where + "date BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "date >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "date <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times,site_id, sum(income) as bb FROM `mg_bet_record` WHERE "
	statement += where + " group by site_id"
	rows1, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}
	rows3 := make(map[string]map[string][]byte)
	for _, v := range rows1 {
		rows3[string(v["site_id"])] = v
	}
	statement = "SELECT  sum(payout) as wb,site_id FROM `mg_bet_record` WHERE "
	statement += where + " group by site_id"
	rows2, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}
	for _, v := range rows2 {
		rows3[string(v["site_id"])]["wb"] = v["wb"]
	}
	return rows3, nil
}

//根据站点获取
func GetAvailableAmountByAgentid(agentid int64, siteid, s_time, e_time string, isdz bool) (int, float64, float64, error) {
	var bb, wb float64
	var times int
	where := ""
	whereparam := make([]interface{}, 0)
	if !isdz {
		where = "module_id in (28,29,30,32)"
	} else {
		where = "module_id not in (28,29,30,32)"
	}
	if agentid > 0 && len(siteid) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		where = where + "agent_id = ? AND site_id = ?"
		whereparam = append(whereparam, agentid)
		whereparam = append(whereparam, siteid)
	} else {
		return 0, 0, 0, m.ParamErr
	}
	if len(s_time) > 0 || len(e_time) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		if len(s_time) > 0 && len(e_time) > 0 {
			where = where + "date BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "date >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "date <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, sum(income) as bb FROM `mg_bet_record` WHERE " + where
	rows1, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return 0, 0, 0, err
	}
	if len(rows1) > 0 {
		bb, _ = utility.StrTo(rows1[0]["bb"]).Float64()
		times, _ = utility.StrTo(rows1[0]["times"]).Int()
	}

	statement = "SELECT  sum(payout) as wb FROM `mg_bet_record` WHERE " + where
	rows2, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return 0, 0, 0, err
	}
	if len(rows2) > 0 {
		wb, _ = utility.StrTo(rows2[0]["wb"]).Float64()
	}
	return times, bb, wb, err
}

//siteid,, agentid int64 获取用户的注单统计按时间，
func GetUserAvailableAmountByUser(username, siteid, s_time, e_time string, isdz bool) ([]map[string][]byte, error) {
	where := ""
	whereparam := make([]interface{}, 0)
	if !isdz {
		where = "module_id in (28,29,30,32)"
	} else {
		where = "module_id not in (28,29,30,32)"
	}
	if len(username) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		users := strings.Split(username, "|")
		where = where + "pkusername in(" +
			strings.Trim(strings.Repeat("?,", len(users)), ",") + ") AND site_id = ?"
		for _, v := range users {
			//userlist = append(userlist, v)
			whereparam = append(whereparam, v)
		}
		whereparam = append(whereparam, siteid)
	}
	if len(s_time) > 0 || len(e_time) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		if len(s_time) > 0 && len(e_time) > 0 {
			where = where + "update_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "update_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "update_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, pkusername,sum(income) as bb,sum(payout) as wb FROM `mg_bet_record` WHERE " + where + " group by account_number"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}

	return rows, err
}

//根据站点获取
func GetUserAvailableAmountBySiteid(siteid string, isdz bool, s_time, e_time string) ([]map[string][]byte, error) {
	where := ""
	whereparam := make([]interface{}, 0)
	if !isdz {
		where = "module_id in (28,29,30,32)"
	} else {
		where = "module_id not in (28,29,30,32)"
	}
	if len(siteid) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		where = where + "site_id = ?"
		whereparam = append(whereparam, siteid)
	} else {
		return nil, m.ParamErr
	}
	if len(s_time) > 0 || len(e_time) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		if len(s_time) > 0 && len(e_time) > 0 {
			where = where + "date BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "date >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "date <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, pkusername,sum(income) as bb,sum(payout) as wb FROM `mg_bet_record` WHERE "
	statement += where + " group by account_number"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}

	return rows, err
}

//根据站点获取
func GetUserAvailableAmountByAgentid(agentid string, isdz bool, siteid, s_time, e_time string) ([]map[string][]byte, error) {
	where := ""
	whereparam := make([]interface{}, 0)
	if !isdz {
		where = "module_id in (28,29,30,32)"
	} else {
		where = "module_id not in (28,29,30,32)"
	}
	if len(agentid) > 0 && len(siteid) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		ids := strings.Split(agentid, "|")
		where = where + "agent_id in(" +
			strings.Trim(strings.Repeat("?,", len(ids)), ",") + ") AND site_id = ?"
		for _, v := range ids {
			whereparam = append(whereparam, v)
		}
		whereparam = append(whereparam, siteid)
	} else {
		return nil, m.ParamErr
	}
	if len(s_time) > 0 || len(e_time) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		if len(s_time) > 0 && len(e_time) > 0 {
			where = where + "date BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "date >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "date <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, pkusername ,sum(income) as bb,sum(payout) as wb FROM `mg_bet_record` WHERE "
	statement += where + " group by account_number"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}

	return rows, err
}

//根据站点获取
func GetAgentAvailableAmountByAgentid(agentid string, isdz bool, siteid, s_time, e_time string) ([]map[string][]byte, error) {
	where := ""
	whereparam := make([]interface{}, 0)
	if !isdz {
		where = "module_id in (28,29,30,32)"
	} else {
		where = "module_id not in (28,29,30,32)"
	}
	if len(agentid) > 0 && len(siteid) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		ids := strings.Split(agentid, "|")
		where = where + " agent_id in(" +
			strings.Trim(strings.Repeat("?,", len(ids)), ",") + ") AND site_id = ?"
		for _, v := range ids {
			whereparam = append(whereparam, v)
		}
		whereparam = append(whereparam, siteid)
	} else {
		return nil, m.ParamErr
	}
	if len(s_time) > 0 || len(e_time) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		if len(s_time) > 0 && len(e_time) > 0 {
			where = where + "date BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "date >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "date <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, agent_id ,sum(income) as bb,sum(payout) as wb FROM `mg_bet_record` " +
		" WHERE " + where + " group by agent_id"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}

	return rows, err
}

//更新注单表中没有代理id和用户名的数据
func MG_UpateBetAgentUsername() error {
	pagesize := 300
	i := 0
	hasuser := true
	for {
		if !hasuser {
			break
		}
		users := make([]User, 0)
		err := m.Orm.Limit(pagesize, i*pagesize).Find(&users)
		if err != nil {
			return err
		}
		count := len(users)
		//guser := make([]string, 0)
		if count > 0 {
			for _, v := range users {
				m.Orm.Table(new(MgBetRecord)).Where("account_number = ?", v.GUserName).Update(map[string]interface{}{"agent_id": v.AgentId, "pkusername": v.UserName, "site_id": v.SiteId})
			}
		}
		if count == pagesize {
			hasuser = true
		} else {
			hasuser = false
		}
		i++
	}
	return nil
}

/*
func MG_UpateBetAgentUsername() error {
	pagesize := 100
	i := 0
	hasuser := true
	for {
		if !hasuser {
			break
		}
		bets := make([]MgBetRecord, 0)
		err := m.Orm.Cols("account_number").Limit(pagesize, i*pagesize).Desc("date").Where("agent_id = 0 OR pkusername = ''").GroupBy("account_number").Find(&bets)
		if err != nil {
			return err
		}
		fmt.Println("mg---------------",pagesize, i*pagesize)
		count := len(bets)
		guser := make([]string, 0)
		if count > 0 {
			//更新注单，以用户名
			for _, v := range bets {
				guser = append(guser, v.AccountNumber)
			}
		}
		users, err := GetUserByGname(guser)
		if err != nil {
			common.Log.Err("mg GetUserByGname:", err)
		} else {
			for _, v := range users {
				m.Orm.Table(new(MgBetRecord)).Where("account_number = ?", v.GUserName).Update(map[string]interface{}{"agent_id": v.AgentId, "pkusername": v.UserName,"site_id":v.SiteId})
				//.Exec("UPDATE `og_bet_record` SET `agent_id`=?, `pkusername`=? WHERE  `username`=?", v.AgentId,v.UserName,v.GUserName)
			}
		}
		if count == pagesize {
			hasuser = true
		} else {
			hasuser = false
		}
		i++
	}
	return nil
}
*/
