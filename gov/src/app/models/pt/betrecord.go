package pt

import (
	m "app/models"
	"common"

	"strings"
	"time"
	"utility"
)

type PtBetRecord struct {
	Id             int       `xorm:"'id'" json:"-"`
	PlayerName     string    `xorm:"pk 'PlayerName'" json:"PlayerName"`
	WindowCode     string    `xorm:"'WindowCode'" json:"WindowCode"`
	GameId         string    `xorm:"'GameId'" json:"GameId"`
	GameCode       string    `xorm:"'GameCode'" json:"GameCode"`
	GameType       string    `xorm:"'GameType'" json:"GameType"`
	GameName       string    `xorm:"'GameName'" json:"GameName"`
	SessionId      string    `xorm:"'SessionId'" json:"SessionId"`
	Bet            string    `xorm:"'Bet'" json:"Bet"`
	Win            string    `xorm:"'Win'" json:"Win"`
	ProgressiveBet string    `xorm:"'ProgressiveBet'" json:"ProgressiveBet"`
	ProgressiveWin string    `xorm:"'ProgressiveWin'" json:"ProgressiveWin"`
	Balance        string    `xorm:"'Balance'" json:"Balance"`
	CurrentBet     string    `xorm:"'CurrentBet'" json:"CurrentBet"`
	GameDate       string    `xorm:"'GameDate'" json:"GameDate"`
	LiveNetwork    string    `xorm:"'LiveNetwork'" json:"LiveNetwork"`
	RNum           string    `xorm:"'RNum'" json:"RNum"`
	Site_id        string    `xorm:"'site_id'" json:"-"`
	Index_id       string    `xorm:"'index_id'" json:"-"`
	Agent_id       int       `xorm:"'agent_id'" json:"-"`
	PkUsername     string    `xorm:"'pkusername'" json:"-"`
	Add_time       time.Time `xorm:"'add_time' created" json:"-"`
}

func (a *PtBetRecord) TableName() string {
	return "pt_bet_record"
}

func getBetTableName(datestr string) string {
	return "pt_bet_record"
}

func CreateBetRecord(ptbet *PtBetRecord) error {
	//1441174254
	/*intbet, _ := utility.StrTo(ptbet.Bet).Float64()
	intwin, _ := utility.StrTo(ptbet.Win).Float64()
	if intbet == 0 && intwin == 0 {
		return nil
	}*/
	ptbet.PlayerName = strings.ToLower(ptbet.PlayerName)
	users, err := GetUserByGname([]string{strings.ToLower(ptbet.PlayerName)})
	if err != nil || len(users) == 0 {
		//common.Log.Err("pt GetUserByGname:", err, ptbet.PlayerName)
	} else {
		user, ok := users[ptbet.PlayerName]
		if ok {
			ptbet.Site_id = user.SiteId
			ptbet.Agent_id = user.AgentId
			ptbet.PkUsername = user.UserName
			ptbet.Index_id = user.IndexId

			bettime, err := time.Parse("2006-01-02 15:04:05", ptbet.GameDate)
			sqlstr := "INSERT INTO `" + getBetTableName(bettime.Format("200601")) + "` (`GameCode`, `PlayerName`, `pkusername`, `site_id`, `agent_id`, `index_id`, `GameId`, `GameType`, `GameName`, `SessionId`, `Bet`, `Win`, `ProgressiveBet`, `ProgressiveWin`, `Balance`, `CurrentBet`, `GameDate`, `LiveNetwork`, `RNum`, `add_time`, `WindowCode`) " +
				"VALUES ( '" + ptbet.GameCode + "', '" + ptbet.PlayerName + "','" + ptbet.PkUsername + "', '" + ptbet.Site_id + "', '" + utility.ToStr(ptbet.Agent_id) + "', '" + ptbet.Index_id + "', '" + ptbet.GameId + "', '" + utility.MysqlFilter(ptbet.GameType) + "', '" + utility.MysqlFilter(ptbet.GameName) + "', '" + ptbet.SessionId + "', '" + ptbet.Bet + "', '" + ptbet.Win + "', '" + ptbet.ProgressiveBet + "', '" +
				ptbet.ProgressiveWin + "', '" + ptbet.Balance + "', '" + ptbet.CurrentBet + "', '" + ptbet.GameDate + "', '" + ptbet.LiveNetwork + "', '" + ptbet.RNum + "', '" + time.Now().Format("2006-01-02 15:04:05") + "', '" + ptbet.WindowCode + "')"
			sqlstr = strings.Replace(sqlstr, "''", "' '", -1)
			_, err = m.Orm.Exec(sqlstr)
			if err != nil {
				if strings.Index(err.Error(), "for key") > 0 && strings.Index(err.Error(), "Duplicate entry") > 0 {
					//重复主键更新
					_, err = m.Orm.Table(getBetTableName(bettime.Format("200601"))).Where("`GameCode` = ?", ptbet.GameCode).Update(ptbet)
					if err != nil {
						common.Log.Err("Insert pt BetRecord :", err, sqlstr)
					}
				} else {
					common.Log.Err("Insert pt BetRecord :", err, sqlstr)
					//for key 'PRIMARY'
				}
			}
		} else {
			common.Log.Err("pt GetUserByGname no exist user:", ptbet.PlayerName)
		}
	}

	return err
}

//会员中心获取注单信息
func GetBetRecords(username, orderid, siteid, agentid, game_type, s_time, e_time string, pagenum, page int64) ([]PtBetRecord, int64, error) {
	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+12h")
	stime = stime.Add(d)
	etime = etime.Add(d)
	s_time = stime.Format("2006-01-02 15:04:05")
	e_time = etime.Format("2006-01-02 15:04:05")
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
		where += "GameCode = ?"
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
				strings.Trim(strings.Repeat("?,", len(ids)), ",") + ") "
			for _, v := range ids {
				whereparam = append(whereparam, v)
			}
		}

		if len(s_time) > 0 || len(e_time) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			if len(s_time) > 0 && len(e_time) > 0 {
				where = where + "GameDate BETWEEN ? AND ?"
				whereparam = append(whereparam, s_time)
				whereparam = append(whereparam, e_time)
			} else if len(s_time) > 0 && len(e_time) == 0 {
				where = where + "GameDate >= ?"
				whereparam = append(whereparam, s_time)
			} else if len(s_time) == 0 && len(e_time) > 0 {
				where = where + "GameDate <= ?"
				whereparam = append(whereparam, e_time)
			}
		}
	}
	where = where + " AND (Bet > '0.00' or Win > '0.00')"
	betrecord := new(PtBetRecord)
	total, err := m.Orm.Table("pt_bet_record").Where(where, whereparam...).Count(betrecord)
	if err != nil {
		common.Log.Err("pt GetBetRecords Count :", err)
		return nil, 0, err
	}
	if total > 0 {
		tp := total / pagenum
		if total%pagenum != 0 {
			tp = tp + 1
		}
		statid := (page - 1) * pagenum
		var betrecords []PtBetRecord
		err = m.Orm.Table("pt_bet_record").Where(where, whereparam...).Limit(int(pagenum), int(statid)).Desc("GameDate").Find(&betrecords)
		if err != nil {
			common.Log.Err("pt GetBetRecords Count :", betrecords)
			return nil, 0, err
		} else {
			return betrecords, total, nil
		}
	}

	return nil, 0, nil
}

func GetAllMonery(username, orderid, siteid, agentid, gametype, s_time, e_time string, pagenum, page int64) (float64, float64, float64, float64, error) {
	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+12h")
	stime = stime.Add(d)
	etime = etime.Add(d)
	s_time = stime.Format("2006-01-02 15:04:05")
	e_time = etime.Format("2006-01-02 15:04:05")
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
		where += "GameCode = ?"
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
				strings.Trim(strings.Repeat("?,", len(ids)), ",") + ") "
			for _, v := range ids {
				whereparam = append(whereparam, v)
			}
		}

		if len(s_time) > 0 || len(e_time) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			if len(s_time) > 0 && len(e_time) > 0 {
				where = where + "GameDate BETWEEN ? AND ?"
				whereparam = append(whereparam, s_time)
				whereparam = append(whereparam, e_time)
			} else if len(s_time) > 0 && len(e_time) == 0 {
				where = where + "GameDate >= ?"
				whereparam = append(whereparam, s_time)
			} else if len(s_time) == 0 && len(e_time) > 0 {
				where = where + "GameDate <= ?"
				whereparam = append(whereparam, e_time)
			}
		}
	}

	var bb, vb, wb float64
	where = where + " AND (Bet > '0.00' or Win > '0.00')"
	statement := "SELECT sum(Bet) as bb,sum(Bet) as vb,sum(Win-Bet) as wb FROM `pt_bet_record` WHERE " + where
	rows1, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return 0, 0, 0, 0, err
	}
	if len(rows1) > 0 {
		bb, _ = utility.StrTo(rows1[0]["bb"]).Float64()
		vb, _ = utility.StrTo(rows1[0]["vb"]).Float64()
		wb, _ = utility.StrTo(rows1[0]["wb"]).Float64()
	}

	return bb, vb, 0.00, wb, err
}

//siteid,, agentid int64
func GetAvailableAmountByUser(username, siteid, s_time, e_time string) (int, int, float64, float64, float64, float64, error) {
	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+12h")
	stime = stime.Add(d)
	etime = etime.Add(d)
	s_time = stime.Format("2006-01-02 15:04:05")
	e_time = etime.Format("2006-01-02 15:04:05")
	var bb, wb, vb, all_bb float64
	var times, vtimes int
	where := ""
	whereparam := make([]interface{}, 0)
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
			where = where + "GameDate BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "GameDate >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "GameDate <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	where = where + " AND (Bet > '0.00' or Win > '0.00')"
	statement := "SELECT count(*) as times,sum(Bet) as all_bb FROM `pt_bet_record` WHERE " + where
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return 0, 0, 0, 0, 0, 0, err
	}
	if len(rows) > 0 {
		all_bb, _ = utility.StrTo(rows[0]["all_bb"]).Float64()
		times, _ = utility.StrTo(rows[0]["times"]).Int()
	}
	statement = "SELECT count(*) as vtimes,sum(Bet) as bb, sum(Bet) as vb, sum(Win) as wb FROM `pt_bet_record` WHERE " + where
	rows1, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return 0, 0, 0, 0, 0, 0, err
	}
	if len(rows1) > 0 {
		bb, _ = utility.StrTo(rows1[0]["bb"]).Float64()
		vtimes, _ = utility.StrTo(rows1[0]["vtimes"]).Int()
		vb, _ = utility.StrTo(rows1[0]["vb"]).Float64()
		wb, _ = utility.StrTo(rows1[0]["wb"]).Float64()
	}
	return times, vtimes, all_bb, bb, vb, wb, err
}

func GetAvailableAmountBySiteids(siteid, s_time, e_time string) ([]map[string][]byte, error) {
	//s_time ,_=  common.Changetimezone(s_time,"America/New_York", "Asia/Phnom_Penh")//stime.Format("2006-01-02 15:04:05")
	//e_time  ,_= common.Changetimezone(e_time,"America/New_York", "Asia/Phnom_Penh")//etime.Format("2006-01-02 15:04:05")
	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+12h")
	stime = stime.Add(d)
	etime = etime.Add(d)
	s_time = stime.Format("2006-01-02 15:04:05")
	e_time = etime.Format("2006-01-02 15:04:05")
	where := "(Bet > '0.00' or Win > '0.00')"
	whereparam := make([]interface{}, 0)
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
			where = where + "GameDate BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "GameDate >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "GameDate <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times,site_id,sum(Bet) as bb, sum(Bet) as vb,sum(Win-Bet) as wb ,pkusername FROM `pt_bet_record` WHERE "
	statement += where + " group by site_id"
	rows1, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}
	return rows1, nil
}

//siteid,, agentid int64
//notbrokerage bool 是否去除免佣的注单的统计
func GetUserAvailableAmountByUser(username, siteid, s_time, e_time string) ([]map[string][]byte, error) {

	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+12h")
	stime = stime.Add(d)
	etime = etime.Add(d)
	s_time = stime.Format("2006-01-02 15:04:05")
	e_time = etime.Format("2006-01-02 15:04:05")
	where := "(Bet > '0.00' or Win > '0.00')"
	whereparam := make([]interface{}, 0)
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
	if len(username) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		users := strings.Split(username, "|")
		for _, v := range users {
			whereparam = append(whereparam, v)
		}
		where = where + "pkusername in(" +
			strings.Trim(strings.Repeat("?,", len(users)), ",") + ")"
	}
	if len(s_time) > 0 || len(e_time) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		if len(s_time) > 0 && len(e_time) > 0 {
			where = where + "GameDate BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "GameDate >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "GameDate <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times,site_id,sum(Bet) as bb, sum(Bet) as vb,sum(Win-Bet) as wb ,pkusername FROM `pt_bet_record` WHERE " + where + " group by pkusername"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}
	return rows, err
}
