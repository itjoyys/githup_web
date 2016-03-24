package ct

import (
	m "app/models"
	"common"

	"encoding/xml"
	"strings"
	"time"
	"utility"
	//"fmt"
)

type CtBetRecord struct {
	XMLName               xml.Name  `xorm:"-" xml:"row"`
	Id                    int64     `xorm:"'id'" xml:"-"`
	Transaction_id        int64     `xorm:"pk 'transaction_id'" xml:"transaction_id"`
	Transaction_date_time string    `xorm:"'transaction_date_time'" xml:"transaction_date_time"`
	Closed_time           string    `xorm:"'closed_time'" xml:"closed_time"`
	Member_id             string    `xorm:"'member_id'" xml:"member_id"`
	Member_type           string    `xorm:"'member_type'" xml:"member_type"`
	Currency              string    `xorm:"'currency'" xml:"currency"`
	Balance_start         float64   `xorm:"'balance_start'" xml:"balance_start"`
	Balance_end           float64   `xorm:"'balance_end'" xml:"balance_end"`
	IsRevocation          string    `xorm:"'is_revocation'" xml:"IsRevocation"`
	GameType              string    `xorm:"'game_type'" xml:"GameType"`
	TableID               string    `xorm:"'table_id'" xml:"TableID"`
	ShoeID                string    `xorm:"'shoe_id'" xml:"ShoeID"`
	PlayID                string    `xorm:"'play_id'" xml:"PlayID"`
	BetPoints             float64   `xorm:"'betpoint'" xml:"BetPoints"`
	BetPointDetail        string    `xorm:"'betpoint_detail'" xml:"BetPointDetail"`
	BetResult             string    `xorm:"'betresult'" xml:"BetResult"`
	BetResultDetail       string    `xorm:"'betresult_detail'" xml:"BetResultDetail"`
	WinOrLoss             float64   `xorm:"'win_or_loss'" xml:"WinOrLoss"`
	Betip                 string    `xorm:"'betip'" xml:"betip"`
	Paramid               string    `xorm:"'paramid'" xml:"paramid"`
	Availablebet          float64   `xorm:"'availablebet'" xml:"availablebet"`
	Site_id               string    `xorm:"'site_id'" json:"-"`
	Agent_id              int       `xorm:"'agent_id'" xml:"-"`
	Index_id	      string    `xorm:"'index_id'" json:"-"`
	PkUsername            string    `xorm:"'pkusername'" xml:"-"`
	UpdateTime            time.Time `xorm:"'update_time' created" xml:"-"`
}

func (a *CtBetRecord) TableName() string {
	return "ct_bet_record"
}

func getBetTableName(datestr string) string {
	return "ct_bet_record"
}

func CreateBetRecord(ogbet *CtBetRecord) error {
	bettime, err := time.Parse("2006-01-02 15:04:05", ogbet.Transaction_date_time)
	if err != nil {
		common.Log.Err("Insert Bbin BetRecord :", err)
		return err
	}
	sqlstr := "INSERT INTO `"+getBetTableName(bettime.Format("200601"))+
	"` (`transaction_id`, `transaction_date_time`, `closed_time`, `member_id`, `member_type`, `currency`, `balance_start`, `balance_end`, `is_revocation`, `game_type`, `table_id`, `shoe_id`, `play_id`, `betpoint`, `betpoint_detail`, `betresult`, `betresult_detail`, `win_or_loss`, `betip`, `paramid`, `site_id`, `agent_id`, `index_id`, `pkusername`, `availablebet`, `update_time`) "+
	"VALUES ( '"+utility.ToStr(ogbet.Transaction_id)+"', '"+ogbet.Transaction_date_time+"','"+ogbet.Closed_time+"', '"+ogbet.Member_id+"', '"+ogbet.Member_type+"', '"+ogbet.Currency+"', '"+utility.ToStr(ogbet.Balance_start)+"', '"+utility.ToStr(ogbet.Balance_end)+"', '"+ogbet.IsRevocation+"', '"+ogbet.GameType+"', '"+ogbet.TableID+"', '"+
	ogbet.ShoeID+"', '"+ogbet.PlayID+"', '"+utility.ToStr(ogbet.BetPoints)+"', '"+ ogbet.BetPointDetail+"', '"+ogbet.BetResult+"', '"+ogbet.BetResultDetail+"', '"+utility.ToStr(ogbet.WinOrLoss)+"', '"+ogbet.Paramid+"', '"+ogbet.Betip+"', '"+ogbet.Site_id+"', '"+utility.ToStr(ogbet.Agent_id)+"', '"+ogbet.Index_id+"','"+ogbet.PkUsername+"','"+utility.ToStr(ogbet.Availablebet)+"', '"+ time.Now().Format("2006-01-02 15:04:05")+"')"
	sqlstr = strings.Replace(sqlstr,"''","' '",-1)
	_, err = m.Orm.Exec(sqlstr)
	//_, err = m.Orm.Table(getBetTableName(bettime.Format("200601"))).Insert(ogbet)
	//TODO添加失败更新
	return err
}

func GetBetRecords(username, orderid, siteid, agentid, video_type, s_time, e_time string, pagenum, page int64) ([]CtBetRecord, int64, error) {
	//s_time ,_=  common.Changetimezone(s_time,"America/New_York", "Asia/Phnom_Penh")//stime.Format("2006-01-02 15:04:05")
	//e_time  ,_= common.Changetimezone(e_time,"America/New_York", "Asia/Phnom_Penh")//etime.Format("2006-01-02 15:04:05")
	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+11h")
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
		where += "transaction_id = ?"
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
			//where = where + "member_id = ?"
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
		if len(video_type) > 0 && video_type != "all" {
			if len(where) > 0 {
				where = where + " AND "
			}
			where = where + "game_type = ?"
			whereparam = append(whereparam, video_type)
		}
		if len(s_time) > 0 || len(e_time) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			if len(s_time) > 0 && len(e_time) > 0 {
				where = where + "transaction_date_time BETWEEN ? AND ?"
				whereparam = append(whereparam, s_time)
				whereparam = append(whereparam, e_time)
			} else if len(s_time) > 0 && len(e_time) == 0 {
				where = where + "transaction_date_time >= ?"
				whereparam = append(whereparam, s_time)
			} else if len(s_time) == 0 && len(e_time) > 0 {
				where = where + "transaction_date_time <= ?"
				whereparam = append(whereparam, e_time)
			}
		}
	}

	betrecord := new(CtBetRecord)
	total, err := m.Orm.Table("ct_bet_record").Where(where, whereparam...).Count(betrecord)
	if err != nil {
		common.Log.Err("CT GetBetRecords Count :", err)
		return nil, 0, err
	}
	if total > 0 {
		tp := total / pagenum
		if total%pagenum != 0 {
			tp = tp + 1
		}
		statid := (page - 1) * pagenum
		var betrecords []CtBetRecord
		err = m.Orm.Table("ct_bet_record").Where(where, whereparam...).Limit(int(pagenum), int(statid)).Desc("transaction_id").Find(&betrecords)
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
func GetAllMonery(username, orderid, siteid, agentid, video_type, s_time, e_time string, pagenum, page int64) (float64, float64, float64, float64, error) {
	//s_time ,_=  common.Changetimezone(s_time,"America/New_York", "Asia/Phnom_Penh")//stime.Format("2006-01-02 15:04:05")
	//e_time  ,_= common.Changetimezone(e_time,"America/New_York", "Asia/Phnom_Penh")//etime.Format("2006-01-02 15:04:05")
	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+11h")
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
		where += "transaction_id = ?"
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
				strings.Trim(strings.Repeat("?,", len(ids)), ",") + ")"
			for _, v := range ids {
				whereparam = append(whereparam, v)
			}
		}
		if len(video_type) > 0 && video_type != "all" {
			if len(where) > 0 {
				where = where + " AND "
			}
			where = where + "game_type = ?"
			whereparam = append(whereparam, video_type)
		}
		if len(s_time) > 0 || len(e_time) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			if len(s_time) > 0 && len(e_time) > 0 {
				where = where + "transaction_date_time BETWEEN ? AND ?"
				whereparam = append(whereparam, s_time)
				whereparam = append(whereparam, e_time)
			} else if len(s_time) > 0 && len(e_time) == 0 {
				where = where + "transaction_date_time >= ?"
				whereparam = append(whereparam, s_time)
			} else if len(s_time) == 0 && len(e_time) > 0 {
				where = where + "transaction_date_time <= ?"
				whereparam = append(whereparam, e_time)
			}
		}
	}

	var bb, vb, wb float64
	//所有
	statement := "SELECT sum(betpoint) as bb, sum(availablebet) as vb FROM `ct_bet_record` WHERE " + where
	rows1, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return 0, 0, 0, 0, err
	}
	if len(rows1) > 0 {
		bb, _ = utility.StrTo(rows1[0]["bb"]).Float64()
		vb, _ = utility.StrTo(rows1[0]["vb"]).Float64()
		//wb, _ = utility.StrTo(rows1[0]["wb"]).Float64()
	}

	statement = "SELECT sum(betpoint) as bb,sum(win_or_loss) as wb FROM `ct_bet_record` WHERE " + where + " AND win_or_loss > 0"
	rows2, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return 0, 0, 0, 0, err
	}
	if len(rows2) > 0 {
		//bb1, _ = utility.StrTo(rows2[0]["bb"]).Float64()
		wb, _ = utility.StrTo(rows2[0]["wb"]).Float64()
	}

	return bb, vb, 0.00, wb - bb, nil
}

func InsertBatchBetRecord(rows *[]CtBetRecord, datestr string) error {
	m.Orm.Table(getBetTableName(datestr))
	_, err := m.Orm.Table(getBetTableName(datestr)).Insert(rows)
	//TODO添加失败更新
	return err
}

//siteid,, agentid int64
func GetAvailableAmountByUser(username, siteid, s_time, e_time string) (int, int, float64, float64, float64, float64, error) {
	/*
		stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
		etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
		d, _ := time.ParseDuration("+11h")
		stime = stime.Add(d)
		etime = etime.Add(d)
		s_time = stime.Format("2006-01-02 15:04:05")
		e_time = etime.Format("2006-01-02 15:04:05")
	*/
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
	statement := "SELECT count(*) as times,sum(betpoint) as all_bb FROM `ct_bet_record` WHERE " + where
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return 0, 0, 0, 0, 0, 0, err
	}
	if len(rows) > 0 {
		all_bb, _ = utility.StrTo(rows[0]["all_bb"]).Float64()
		times, _ = utility.StrTo(rows[0]["times"]).Int()
	}
	if len(where) > 0 {
		where = where + " AND "
	}
	where += "is_revocation=1"
	statement = "SELECT count(*) as vtimes,sum(betpoint) as bb, sum(availablebet) as vb, sum(win_or_loss) as wb FROM `ct_bet_record` WHERE " + where
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

//根据站点获取
func GetAvailableAmountBySiteid(siteid, s_time, e_time string) (int, float64, float64, float64, error) {
	//s_time ,_=  common.Changetimezone(s_time,"America/New_York", "Asia/Phnom_Penh")//stime.Format("2006-01-02 15:04:05")
	//e_time  ,_= common.Changetimezone(e_time,"America/New_York", "Asia/Phnom_Penh")//etime.Format("2006-01-02 15:04:05")
	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+11h")
	stime = stime.Add(d)
	etime = etime.Add(d)
	s_time = stime.Format("2006-01-02 15:04:05")
	e_time = etime.Format("2006-01-02 15:04:05")
	var bb, wb, vb float64
	var times int
	where := "is_revocation=1"
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
			where = where + "transaction_date_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "transaction_date_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "transaction_date_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times,sum(betpoint) as bb, sum(availablebet) as vb,sum(win_or_loss) as wb FROM `ct_bet_record` WHERE " + where
	rows1, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return 0, 0, 0, 0, err
	}
	if len(rows1) > 0 {
		bb, _ = utility.StrTo(rows1[0]["bb"]).Float64()
		vb, _ = utility.StrTo(rows1[0]["vb"]).Float64()
		times, _ = utility.StrTo(rows1[0]["times"]).Int()
		wb, _ = utility.StrTo(rows1[0]["wb"]).Float64()
	}

	return times, bb, vb, wb, err
}

func GetAvailableAmountBySiteids(siteid, s_time, e_time string) ([]map[string][]byte, error) {
	//s_time ,_=  common.Changetimezone(s_time,"America/New_York", "Asia/Phnom_Penh")//stime.Format("2006-01-02 15:04:05")
	//e_time  ,_= common.Changetimezone(e_time,"America/New_York", "Asia/Phnom_Penh")//etime.Format("2006-01-02 15:04:05")
	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+11h")
	stime = stime.Add(d)
	etime = etime.Add(d)
	s_time = stime.Format("2006-01-02 15:04:05")
	e_time = etime.Format("2006-01-02 15:04:05")
	where := "is_revocation=1"
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
			where = where + "transaction_date_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "transaction_date_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "transaction_date_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times,site_id,sum(betpoint) as bb, sum(availablebet) as vb,sum(win_or_loss) as wb FROM `ct_bet_record` WHERE "
	statement += where + " group by site_id"
	rows1, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}
	return rows1, nil
}

//根据站点获取
func GetAvailableAmountByAgentid(agentid int64, siteid, s_time, e_time string) (int, float64, float64, float64, error) {
	//s_time ,_=  common.Changetimezone(s_time,"America/New_York", "Asia/Phnom_Penh")//stime.Format("2006-01-02 15:04:05")
	//e_time  ,_= common.Changetimezone(e_time,"America/New_York", "Asia/Phnom_Penh")//etime.Format("2006-01-02 15:04:05")
	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+11h")
	stime = stime.Add(d)
	etime = etime.Add(d)
	s_time = stime.Format("2006-01-02 15:04:05")
	e_time = etime.Format("2006-01-02 15:04:05")

	var bb, wb, vb float64
	var times int
	where := "is_revocation=1"
	whereparam := make([]interface{}, 0)
	if agentid > 0 && len(siteid) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		where = where + "agent_id = ? AND site_id = ?"
		whereparam = append(whereparam, agentid)
		whereparam = append(whereparam, siteid)
	} else {
		return 0, 0, 0, 0, m.ParamErr
	}
	if len(s_time) > 0 || len(e_time) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		if len(s_time) > 0 && len(e_time) > 0 {
			where = where + "transaction_date_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "transaction_date_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "transaction_date_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times,sum(betpoint) as bb, sum(availablebet) as vb,sum(win_or_loss) as wb FROM `ct_bet_record` WHERE " + where
	rows1, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return 0, 0, 0, 0, err
	}
	if len(rows1) > 0 {
		bb, _ = utility.StrTo(rows1[0]["bb"]).Float64()
		vb, _ = utility.StrTo(rows1[0]["vb"]).Float64()
		times, _ = utility.StrTo(rows1[0]["times"]).Int()
		wb, _ = utility.StrTo(rows1[0]["wb"]).Float64()
	}

	return times, bb, vb, wb, err
}

//siteid,, agentid int64
func GetUserAvailableAmountByUser(username, siteid, s_time, e_time string, isdz bool) ([]map[string][]byte, error) {
	/*
		stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
		etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
		d, _ := time.ParseDuration("+11h")
		stime = stime.Add(d)
		etime = etime.Add(d)
		s_time = stime.Format("2006-01-02 15:04:05")
		e_time = etime.Format("2006-01-02 15:04:05")
	*/
	if isdz {
		return nil, m.NodianziErr
	}
	where := "is_revocation=1"
	whereparam := make([]interface{}, 0)
	if len(username) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		users := strings.Split(username, "|")
		where = where + "pkusername in(" +
			strings.Trim(strings.Repeat("?,", len(users)), ",") + ") AND site_id = ?"
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

	statement := "SELECT count(*) as times, pkusername as account_number,sum(betpoint) as bb,sum(availablebet) as vb ,sum(win_or_loss) as wb FROM `ct_bet_record` WHERE " + where + " group by member_id"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}
	return rows, err
}

//根据站点获取
func GetUserAvailableAmountBySiteid(siteid string, isdz bool, s_time, e_time string) ([]map[string][]byte, error) {

	//s_time, _ = common.Changetimezone(s_time, "America/New_York", "Asia/Phnom_Penh") //stime.Format("2006-01-02 15:04:05")
	//e_time, _ = common.Changetimezone(e_time, "America/New_York", "Asia/Phnom_Penh") //etime.Format("2006-01-02 15:04:05")
	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+11h")
	stime = stime.Add(d)
	etime = etime.Add(d)
	s_time = stime.Format("2006-01-02 15:04:05")
	e_time = etime.Format("2006-01-02 15:04:05")
	if isdz {
		return nil, m.NodianziErr
	}
	where := "is_revocation=1"
	whereparam := make([]interface{}, 0)
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
			where = where + "transaction_date_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "transaction_date_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "transaction_date_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times,pkusername as account_number,sum(betpoint) as bb,sum(availablebet) as vb ,sum(win_or_loss) as wb FROM `ct_bet_record` WHERE "
	statement += where + " group by member_id HAVING sum(availablebet)>0"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}

	return rows, err
}

//根据站点获取
func GetUserAvailableAmountByAgentid(agentid string, isdz bool, siteid, s_time, e_time string) ([]map[string][]byte, error) {

	//s_time, _ = common.Changetimezone(s_time, "America/New_York", "Asia/Phnom_Penh") //stime.Format("2006-01-02 15:04:05")
	//e_time, _ = common.Changetimezone(e_time, "America/New_York", "Asia/Phnom_Penh") //etime.Format("2006-01-02 15:04:05")
	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+11h")
	stime = stime.Add(d)
	etime = etime.Add(d)
	s_time = stime.Format("2006-01-02 15:04:05")
	e_time = etime.Format("2006-01-02 15:04:05")
	if isdz {
		return nil, m.NodianziErr
	}
	where := "is_revocation=1"
	whereparam := make([]interface{}, 0)
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
			where = where + "transaction_date_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "transaction_date_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "transaction_date_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, pkusername as account_number,sum(betpoint) as bb,sum(availablebet) as vb,sum(win_or_loss) as wb FROM `ct_bet_record` WHERE "
	statement += where + " group by member_id HAVING sum(availablebet)>0 "
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}

	return rows, err
}

//根据站点获取
func GetAgentAvailableAmountByAgentid(agentid string, isdz bool, siteid, s_time, e_time string) ([]map[string][]byte, error) {

	//s_time, _ = common.Changetimezone(s_time, "America/New_York", "Asia/Phnom_Penh") //stime.Format("2006-01-02 15:04:05")
	//e_time, _ = common.Changetimezone(e_time, "America/New_York", "Asia/Phnom_Penh") //etime.Format("2006-01-02 15:04:05")
	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+11h")
	stime = stime.Add(d)
	etime = etime.Add(d)
	s_time = stime.Format("2006-01-02 15:04:05")
	e_time = etime.Format("2006-01-02 15:04:05")
	if isdz {
		return nil, m.NodianziErr
	}
	where := "is_revocation=1"
	whereparam := make([]interface{}, 0)
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
			where = where + "transaction_date_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "transaction_date_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "transaction_date_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, agent_id as agent_id,sum(betpoint) as bb,sum(availablebet) as vb,sum(win_or_loss) as wb FROM `ct_bet_record` " +
		" WHERE " + where + " group by agent_id HAVING sum(availablebet) > 0 "
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}

	return rows, err
}

//更新注单表中没有代理id和用户名的数据
func CT_UpateBetAgentUsername() error {
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
				m.Orm.Table(new(CtBetRecord)).Where("member_id = ?", v.GUserName).Update(map[string]interface{}{"agent_id": v.AgentId, "pkusername": v.UserName, "site_id": v.SiteId})
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
func CT_UpateBetAgentUsername() error {
	pagesize := 100
	i := 0
	hasuser := true
	for {
		if !hasuser {
			break
		}
		bets := make([]CtBetRecord, 0)
		err := m.Orm.Cols("member_id").Limit(pagesize, i*pagesize).Where("agent_id = 0 OR pkusername = ''").GroupBy("member_id").Find(&bets)
		if err != nil {
			return err
		}
		count := len(bets)
		guser := make([]string, 0)
		if count > 0 {
			//更新注单，以用户名
			for _, v := range bets {
				guser = append(guser, v.Member_id)
			}
		}
		users, err := GetUserByGname(guser)
		if err != nil {
			common.Log.Err("ct GetUserByGname:", err)
		} else {
			for _, v := range users {
				m.Orm.Table(new(CtBetRecord)).Where("member_id = ?", v.GUserName).Update(map[string]interface{}{"agent_id": v.AgentId, "pkusername": v.UserName,"site_id":v.SiteId})
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
