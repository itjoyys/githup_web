package lebo

import (
	m "app/models"
	"common"

	//"fmt"
	"encoding/xml"
	"strings"
	"time"
	"utility"
)

type LeboBetRecord struct {
	XMLName        xml.Name  `xorm:"-" xml:"row"`
	Id             int64     `xorm:"'id'" xml:"-"`
	Gameid         string    `xorm:"pk 'game_id'" xml:"gameid"`
	Betstarttime   string    `xorm:"'betstart_time'" xml:"betstarttime"`
	Member         string    `xorm:"'member'" xml:"member"`
	IsRevocation   string    `xorm:"'is_revocation'" xml:"IsRevocation"`
	GameType       string    `xorm:"'game_type'" xml:"GameType"`
	TableID        string    `xorm:"'table_id'" xml:"TableID"`
	BetAmount      float64   `xorm:"'betamount'" xml:"BetAmount"`
	PayOut         float64   `xorm:"'payout'" xml:"PayOut"`
	ValidBetAmount float64   `xorm:"'valid_betamount'" xml:"ValidBetAmount"`
	BetDetail      string    `xorm:"'bet_detail'" xml:"BetDetail"`
	GameResult     string    `xorm:"'game_result'" xml:"GameResult"`
	Site_id        string    `xorm:"'site_id'" json:"-"`
	Agent_id       int       `xorm:"'agent_id'" xml:"-"`
	Index_id       string    `xorm:"'index_id'" xml:"-"`
	PkUsername     string    `xorm:"'pkusername'" xml:"-"`
	UpdateTime     time.Time `xorm:"'update_time' created" xml:"-"`
}

/*
<?xml version="1.0"?>
<response>
<code>0</code>
<text>Success</text>
<result>
<row id="1">
<gameid>118537095</gameid>
<betstarttime>1432806714</betstarttime>
<member>tgzerophp</member>
<IsRevocation>0</IsRevocation>
<GameType>1</GameType>
<TableID>2</TableID>
<BetAmount>20.0000</BetAmount>
<PayOut>-20.0000</PayOut>
<ValidBetAmount>20.0000</ValidBetAmount>
<BetDetail>1,20#</BetDetail>
<GameResult>9;3^40,33,0;56,53,0</GameResult>
</row>
</result>
</response>
*/
func (a *LeboBetRecord) TableName() string {
	return "lebo_bet_record"
}

func getBetTableName(datestr string) string {
	return "lebo_bet_record"
}

func CreateBetRecord(ogbet *LeboBetRecord) error {
	//1441174254
	users, err := GetUserByGname([]string{ogbet.Member})
	if err != nil || len(users) == 0 {
		common.Log.Err("lebo GetUserByGname:", err, ogbet.Member)
	} else {
		user, ok := users[ogbet.Member]
		if ok {
			ogbet.Site_id = user.SiteId
			ogbet.Agent_id = user.AgentId
			ogbet.PkUsername = user.UserName
		} else {
			common.Log.Err("lebo GetUserByGname no exist user:", ogbet.Member)
		}
	}
	timeline, _ := utility.StrTo(ogbet.Betstarttime).Int64()
	tm := time.Unix(timeline, 0) //时间问题
	loc, _ := time.LoadLocation("Asia/Shanghai")
	time2 := tm.In(loc)
	d, _ := time.ParseDuration("-12h") //转换服务器时间为中国时间
	time2 = time2.Add(d)
	ogbet.Betstarttime = time2.Format("2006-01-02 15:04:05")
	//m.Orm.Table(getBetTableName(tm.Format("200601")))
	//_, err = m.Orm.Table(getBetTableName(tm.Format("200601"))).Insert(ogbet)
	sqlstr := "INSERT INTO `"+getBetTableName(tm.Format("200601"))+"` (`game_id`, `betstart_time`, `member`, `is_revocation`, `game_type`, `table_id`, `betamount`, `payout`, `valid_betamount`, `update_time`, `bet_detail`, `game_result`, `site_id`, `agent_id`, `index_id`, `pkusername`) "+
	"VALUES ( '"+ogbet.Gameid+"', '"+ogbet.Betstarttime+"','"+ogbet.Member+"', '"+ogbet.IsRevocation+"', '"+ogbet.GameType+"', '"+ogbet.TableID+"', '"+utility.ToStr(ogbet.BetAmount)+"', '"+utility.ToStr(ogbet.PayOut)+"', '"+utility.ToStr(ogbet.ValidBetAmount)+"', '"+
	time.Now().Format("2006-01-02 15:04:05")+"', '"+ogbet.BetDetail+"', '"+ogbet.GameResult+"', '"+ogbet.Site_id+"','"+utility.ToStr(ogbet.Agent_id)+"','"+ogbet.Index_id+"', '"+ogbet.PkUsername+"')"
	sqlstr = strings.Replace(sqlstr,"''","' '",-1)
	_, err = m.Orm.Exec(sqlstr)
	if err != nil {
		if strings.Index(err.Error(), "for key") > 0 && strings.Index(err.Error(), "Duplicate entry") > 0 {
			//重复主键更新
			_, err = m.Orm.Table(getBetTableName(tm.Format("200601"))).Where("game_id = ?",ogbet.Gameid).Update(ogbet)
			if err != nil {
				common.Log.Err("Insert lebo BetRecord :", err)
			}
		} else {
			common.Log.Err("Insert lebo BetRecord :", err)
			//for key 'PRIMARY'
		}
	}
	return err
}

func GetBetRecords(username, orderid, siteid, agentid, video_type, s_time, e_time string, pagenum, page int64) ([]LeboBetRecord, int64, error) {
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
		where += "game_id = ?"
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

			//where = where + "member = ?"
			//whereparam = append(whereparam, username)
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
				where = where + "betstart_time BETWEEN ? AND ?"
				whereparam = append(whereparam, s_time)
				whereparam = append(whereparam, e_time)
			} else if len(s_time) > 0 && len(e_time) == 0 {
				where = where + "betstart_time >= ?"
				whereparam = append(whereparam, s_time)
			} else if len(s_time) == 0 && len(e_time) > 0 {
				where = where + "betstart_time <= ?"
				whereparam = append(whereparam, e_time)
			}
		}
	}

	betrecord := new(LeboBetRecord)
	total, err := m.Orm.Table("lebo_bet_record").Where(where, whereparam...).Count(betrecord)
	if err != nil {
		common.Log.Err("lebo GetBetRecords Count :", err)
		return nil, 0, err
	}
	if total > 0 {
		tp := total / pagenum
		if total%pagenum != 0 {
			tp = tp + 1
		}
		statid := (page - 1) * pagenum
		var betrecords []LeboBetRecord
		err = m.Orm.Table("lebo_bet_record").Where(where, whereparam...).Limit(int(pagenum), int(statid)).Desc("game_id").Find(&betrecords)
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
		where += "game_id = ?"
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
				where = where + "betstart_time BETWEEN ? AND ?"
				whereparam = append(whereparam, s_time)
				whereparam = append(whereparam, e_time)
			} else if len(s_time) > 0 && len(e_time) == 0 {
				where = where + "betstart_time >= ?"
				whereparam = append(whereparam, s_time)
			} else if len(s_time) == 0 && len(e_time) > 0 {
				where = where + "betstart_time <= ?"
				whereparam = append(whereparam, e_time)
			}
		}
	}

	var bb, vb, wb float64
	statement := "SELECT sum(valid_betamount) as vb,sum(betamount) as bb,sum(payout) as wb FROM `lebo_bet_record` WHERE " + where
	rows1, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return 0, 0, 0, 0, err
	}
	if len(rows1) > 0 {
		bb, _ = utility.StrTo(rows1[0]["bb"]).Float64()
		vb, _ = utility.StrTo(rows1[0]["vb"]).Float64()
		wb, _ = utility.StrTo(rows1[0]["wb"]).Float64()
	}

	return bb, vb, 0.00, wb, nil
}

//siteid,, agentid int64
func GetAvailableAmountByUser(username, siteid, s_time, e_time string) (int, int, float64, float64, float64, float64, error) {
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
	statement := "SELECT count(*) as times,sum(betamount) as all_bb FROM `lebo_bet_record` WHERE " + where
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
	where += "is_revocation=0" //只要有效
	statement = "SELECT count(*) as vtimes, sum(valid_betamount) as vb,sum(betamount) as bb,sum(payout) as wb FROM `lebo_bet_record` WHERE " + where
	rows1, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return 0, 0, 0, 0, 0, 0, err
	}
	if len(rows1) > 0 {
		bb, _ = utility.StrTo(rows1[0]["bb"]).Float64()
		vb, _ = utility.StrTo(rows1[0]["vb"]).Float64()
		vtimes, _ = utility.StrTo(rows1[0]["vtimes"]).Int()
		wb, _ = utility.StrTo(rows1[0]["wb"]).Float64()
	}
	return times, vtimes, all_bb, bb, vb, wb, err
}

//根据站点获取
func GetAvailableAmountBySiteid(siteid, s_time, e_time string) (int, float64, float64, float64, error) {
	var bb, wb, vb float64
	var times int
	where := "is_revocation=0"
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
	statement := "SELECT count(*) as times, sum(valid_betamount) as vb,sum(betamount) as bb,sum(payout) as wb FROM `lebo_bet_record` WHERE " + where
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
	where := "is_revocation=0"
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
	statement := "SELECT count(*) as times,site_id, sum(valid_betamount) as vb,sum(betamount) as bb,sum(payout) as wb FROM `lebo_bet_record` WHERE "
	statement += where + " group by site_id"
	rows1, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}
	return rows1, nil
}

//根据站点获取
func GetAvailableAmountByAgentid(agentid int64, siteid, s_time, e_time string) (int, float64, float64, float64, error) {
	var bb, wb, vb float64
	var times int
	where := "is_revocation=0"
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
	statement := "SELECT count(*) as times, sum(valid_betamount) as vb,sum(betamount) as bb,sum(payout) as wb FROM `lebo_bet_record` WHERE " + where
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
func GetUserAvailableAmountByUser(username, siteid, s_time, e_time string, isdz, notbrokerage bool) ([]map[string][]byte, error) {
	if isdz {
		return nil, m.NodianziErr
	}
	where := "is_revocation=0"
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
	statement := "SELECT count(*) as times, pkusername as account_number,sum(betamount) as bb,sum(valid_betamount) as vb ,sum(payout) as wb FROM `lebo_bet_record` WHERE " + where + " group by member"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}
	return rows, err
}

//根据站点获取
func GetUserAvailableAmountBySiteid(siteid string, isdz bool, s_time, e_time string) ([]map[string][]byte, error) {
	if isdz {
		return nil, m.NodianziErr
	}
	where := "is_revocation = 0"
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
	statement := "SELECT count(*) as times,pkusername as account_number,sum(betamount) as bb,sum(valid_betamount) as vb ,sum(payout) as wb FROM `lebo_bet_record` WHERE "
	statement += where + " group by member"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}
	return rows, err
}

//根据站点获取
func GetUserAvailableAmountByAgentid(agentid string, isdz bool, siteid, s_time, e_time string) ([]map[string][]byte, error) {
	if isdz {
		return nil, m.NodianziErr
	}
	where := "is_revocation=0"
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
	statement := "SELECT count(*) as times,pkusername as account_number,sum(betamount) as bb,sum(valid_betamount) as vb,sum(payout) as wb FROM `lebo_bet_record` WHERE "
	statement += where + " group by member"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}

	return rows, err
}

//根据站点获取
func GetAgentAvailableAmountByAgentid(agentid string, isdz bool, siteid, s_time, e_time string) ([]map[string][]byte, error) {
	if isdz {
		return nil, m.NodianziErr
	}
	where := "is_revocation=0"
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
	statement := "SELECT count(*) as times,agent_id ,sum(betamount) as bb,sum(valid_betamount) as vb,sum(payout) as wb FROM `lebo_bet_record`  " +
		" WHERE " + where + " group by agent_id"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}

	return rows, err
}

//更新注单表中没有代理id和用户名的数据
func Lebo_UpateBetAgentUsername() error {
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
				m.Orm.Table(new(LeboBetRecord)).Where("member = ?", v.GUserName).Update(map[string]interface{}{"agent_id": v.AgentId, "pkusername": v.UserName, "site_id": v.SiteId})
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
func Lebo_UpateBetAgentUsername() error {
	pagesize := 100
	i := 0
	hasuser := true
	for {
		if !hasuser {
			break
		}
		bets := make([]LeboBetRecord, 0)
		err := m.Orm.Cols("member").Limit(pagesize, i*pagesize).Where("agent_id = 0 OR pkusername = ''").GroupBy("member").Find(&bets)
		if err != nil {
			return err
		}
		fmt.Println("lebo---------------",pagesize, i*pagesize)
		count := len(bets)
		guser := make([]string, 0)
		if count > 0 {
			//更新注单，以用户名
			for _, v := range bets {
				guser = append(guser, v.Member)
			}
		}
		users, err := GetUserByGname(guser)
		if err != nil {
			common.Log.Err("lebo GetUserByGname:", err)
		} else {
			for _, v := range users {
				m.Orm.Table(new(LeboBetRecord)).Where("member = ?", v.GUserName).Update(map[string]interface{}{"agent_id": v.AgentId, "pkusername": v.UserName, "site_id": v.SiteId})
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
