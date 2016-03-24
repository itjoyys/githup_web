package ag

import (
	m "app/models"
	"common"


	"log"
	"strings"
	"time"
	"utility"
)

type BetRecord struct {
	Id                int64     `xorm:"'id'" xml:"-"`
	BillNo            string    `xorm:"pk 'bill_no'" xml:"billNo,attr"` // `xml:"billNo,attr"`
	DataType          string    `xorm:"'data_type'" xml:"dataType,attr"`
	PlayerName        string    `xorm:"'player_name'" xml:"playerName,attr"`
	AgentCode         string    `xorm:"'agent_code'" xml:"agentCode,attr"`
	GameCode          string    `xorm:"'game_code'" xml:"gameCode,attr"`
	NetAmount         float64   `xorm:"'netamount'" xml:"netAmount,attr"`
	BetTime           string    `xorm:"'bet_time'" xml:"betTime,attr"`
	GameType          string    `xorm:"'game_type'" xml:"gameType,attr"`
	BetAmount         float64   `xorm:"'bet_amount'" xml:"betAmount,attr"`
	ValidBetAmount    float64   `xorm:"'valid_betamount'" xml:"validBetAmount,attr"`
	Flag              string    `xorm:"'flag'" xml:"flag,attr"`
	PlayType          string    `xorm:"'play_type'" xml:"playType,attr"`
	Currency          string    `xorm:"'currency'" xml:"currency,attr"`
	TableCode         string    `xorm:"'table_code'" xml:"tableCode,attr"`
	LoginIP           string    `xorm:"'login_ip'" xml:"loginIP,attr"`
	RecalcuTime       string    `xorm:"'recalcu_time'" xml:"recalcuTime,attr"`
	PlatformId        string    `xorm:"'platform_id'" xml:"platformId,attr"`
	PlatformType      string    `xorm:"'platform_type'" xml:"platformType,attr"`
	Stringex          string    `xorm:"'stringex'" xml:"stringex,attr"`
	Remark            string    `xorm:"'remark'" xml:"remark,attr"`
	Round             string    `xorm:"'round'" xml:"round,attr"`
	Result            string    `xorm:"'result'" xml:"result,attr"`
	BeforeCredit      float64   `xorm:"'before_credit'" xml:"beforeCredit,attr"`
	Odds              float64   `xorm:"'odds'" xml:"odds,attr"`
	Scene             string    `xorm:"'scene'" xml:"Scene,attr"`
	JackpotSettlement float64    `xorm:"'jackpot_settlement'" xml:"JackpotSettlement,attr"`
	DeviceType        string    `xorm:"'device_type'" xml:"deviceType,attr"`
	Site_id           string    `xorm:"'site_id'" xml:"-"`
	Agent_id          int       `xorm:"'agent_id'" xml:"-"`
	Index_id           string    `xorm:"'index_id'" xml:"-"`
	PkUsername        string    `xorm:"'pkusername'" xml:"-"`
	UpdateTime        time.Time `xorm:"'update_time' created" xml:"-"`
}

/*
<row dataType="HBR" billNo="568160423d9308aa473da4f0"
playerName="aaewanglin888" agentCode="" gameCode="" netAmount="-10" betTime="2015-12-28 12:16:03" gameType="HUNTER" betAmount="10" validBetAmount="10" flag="1" playType="2147494471:10:1" currency="CNY" tableCode="8" loginIP="" recalcuTime="" platformType="HUNTER" remark="null" round="" result="Hunter Miss[ type=19,number=19,cost=5000]" beforeCredit="2145"
 odds="1" Scene="81451319189" JackpotSettlement="0.08" />
*/

func (a *BetRecord) TableName() string {
	return "ag_bet_record"
}

func getBetTableName(datestr string) string {
	return "ag_bet_record"
}

func CreateBetRecord(ogbet *BetRecord) error {
	//2014-07-21 03:12:00
	bettime, err := time.Parse("2006-01-02 15:04:05", ogbet.BetTime)
	if err != nil {
		common.Log.Err("Insert AG BetRecord :", err)
		return err
	}
	if len(ogbet.RecalcuTime) == 0{
		ogbet.RecalcuTime = "0000-00-00 00:00:00"
	}
	sqlstr := "INSERT INTO `"+getBetTableName(bettime.Format("200601"))+
	"` (`bill_no`, `data_type`, `player_name`, `agent_code`, `game_code`, `netamount`, `bet_time`, `game_type`, `bet_amount`, `valid_betamount`, `flag`, `play_type`, `currency`, `table_code`, `login_ip`, `recalcu_time`, `platform_id`, `platform_type`, `stringex`, `remark`, `round`, `result`, `before_credit`, `odds`, `scene`, `jackpot_settlement`, `device_type`, `site_id`, `agent_id`, `index_id`, `pkusername`)  "+
	"VALUES ( '"+ogbet.BillNo+"', '"+ogbet.DataType+"','"+ogbet.PlayerName+"', '"+ogbet.AgentCode+"', '"+ogbet.GameCode+"', '"+utility.ToStr(ogbet.NetAmount)+"', '"+ogbet.BetTime+"', '"+ogbet.GameType+"', '"+utility.ToStr(ogbet.BetAmount)+"', '"+utility.ToStr(ogbet.ValidBetAmount)+"', '"+ogbet.Flag+"', '"+
	ogbet.PlayType+"', '"+ogbet.Currency+"', '"+ogbet.TableCode+"', '"+ ogbet.LoginIP+"', '"+ogbet.RecalcuTime+"', '"+ogbet.PlatformId+"', '"+ogbet.PlatformType+"', '"+ogbet.Stringex+"', '"+ogbet.Remark+"', '"+ogbet.Round+"', '"+ogbet.Result+"', '"+utility.ToStr(ogbet.BeforeCredit)+"', '"+utility.ToStr(ogbet.Odds)+"', '"+ogbet.Scene+"', '"+utility.ToStr(ogbet.JackpotSettlement)+"', '"+ogbet.DeviceType+"', '"+ogbet.Site_id+"', '"+utility.ToStr(ogbet.Agent_id)+"', '"+ogbet.Index_id+"','"+ogbet.PkUsername +"')"
	sqlstr = strings.Replace(sqlstr,"''","' '",-1)
	_, err = m.Orm.Query(sqlstr)
	if err != nil {
		if strings.Index(err.Error(), "for key") > 0 && strings.Index(err.Error(), "Duplicate entry") > 0 {
			//重复主键更新
			_, err = m.Orm.Id(ogbet.BillNo).Update(ogbet)
			if err != nil {
				common.Log.Err("Insert AG BetRecord :", err)
			}
		} else {
			common.Log.Err("Insert AG BetRecord :", err)
			//for key 'PRIMARY'
		}
	}

	return err
}

func InsertBatchBetRecord(agrows []BetRecord, datestr string) error {
	strsql_head := "INSERT INTO `"+getBetTableName(datestr)+
	"` (`bill_no`, `data_type`, `player_name`, `agent_code`, `game_code`, `netamount`, `bet_time`, `game_type`, `bet_amount`, `valid_betamount`, `flag`, `play_type`, `currency`, `table_code`, `login_ip`, `recalcu_time`,`platform_id`, `platform_type`, `stringex`, `remark`, `round`, `result`, `before_credit`, `odds`, `scene`, `jackpot_settlement`, `device_type`, `site_id`, `agent_id`, `index_id`, `pkusername`)  "+
	"VALUES "
	strsql_foot := "ON DUPLICATE KEY UPDATE  `agent_code`=VALUES(`agent_code`), `game_code`=VALUES(`game_code`), `netamount`=VALUES(`netamount`), `bet_time`=VALUES(`bet_time`), `game_type`=VALUES(`game_type`),  `bet_amount`=VALUES(`bet_amount`), `valid_betamount`=VALUES(`valid_betamount`), `flag`=VALUES(`flag`), `play_type`=VALUES(`play_type`), `table_code`=VALUES(`table_code`), `recalcu_time`=VALUES(`recalcu_time`),`platform_id`=VALUES(`platform_id`), `platform_type`=VALUES(`platform_type`), `stringex`=VALUES(`stringex`), `remark`=VALUES(`remark`), `round`=VALUES(`round`), `result`=VALUES(`result`), `before_credit`=VALUES(`before_credit`), `odds`=VALUES(`odds`), `scene`=VALUES(`scene`), `jackpot_settlement`=VALUES(`jackpot_settlement`)"
	sql_values := make([]string, 0)
	for _, val := range agrows {
		if len(val.RecalcuTime) == 0{
			val.RecalcuTime = "0000-00-00 00:00:00"
		}
		sql_values = append(sql_values,"( '"+val.BillNo+"', '"+val.DataType+"','"+val.PlayerName+"', '"+val.AgentCode+"', '"+val.GameCode+"', '"+utility.ToStr(val.NetAmount)+"', '"+val.BetTime+"', '"+val.GameType+"', '"+utility.ToStr(val.BetAmount)+"', '"+utility.ToStr(val.ValidBetAmount)+"', '"+val.Flag+"', '"+
		val.PlayType+"', '"+val.Currency+"', '"+val.TableCode+"', '"+ val.LoginIP+"', '"+val.RecalcuTime+"', '"+val.PlatformId+"', '"+val.PlatformType+"', '"+val.Stringex+"', '"+val.Remark+"', '"+val.Round+"', '"+utility.MysqlFilter(val.Result) +"', '"+utility.ToStr(val.BeforeCredit)+"', '"+utility.ToStr(val.Odds)+"', '"+val.Scene+"', '"+utility.ToStr(val.JackpotSettlement)+"', '"+val.DeviceType+"', '"+val.Site_id+"', '"+utility.ToStr(val.Agent_id)+"', '"+val.Index_id+"','"+val.PkUsername +"')")
	}
	sqlstr := strsql_head + strings.Join(sql_values, ",") + strsql_foot
	sqlstr = strings.Replace(sqlstr,"''","' '",-1)
	_, err := m.Orm.Query(sqlstr)
	if err != nil {
		common.Log.Err("Insert AG BetRecord :", err,sql_values)
	}
	return nil
}

func GetBetRecords(username, orderid, siteid, agentid, video_type, game_type, s_time, e_time string, pagenum, page int64) ([]BetRecord, int64, error) {
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
		where += "bill_no = ?"
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
		} else if len(agentid) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			ids := strings.Split(agentid, "|")
			where = where + "agent_id in(" + strings.Trim(strings.Repeat("?,", len(ids)), ",") +
				")"
			for _, v := range ids {
				whereparam = append(whereparam, v)
			}
		}
		if len(video_type) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			where = where + "data_type= 'BR'"
			if video_type != "all" {
				where = where + " AND game_type = ? "
				whereparam = append(whereparam, video_type)
			}
		} else if len(game_type) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			where = where + "data_type = 'EBR' AND data_type = 'HBR' "
			if game_type != "all" {
				where = where + " AND game_type = ? "
				whereparam = append(whereparam, game_type)
			}
		}
		if len(s_time) > 0 || len(e_time) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			if len(s_time) > 0 && len(e_time) > 0 {
				where = where + "bet_time BETWEEN ? AND ?"
				whereparam = append(whereparam, s_time)
				whereparam = append(whereparam, e_time)
			} else if len(s_time) > 0 && len(e_time) == 0 {
				where = where + "bet_time >= ?"
				whereparam = append(whereparam, s_time)
			} else if len(s_time) == 0 && len(e_time) > 0 {
				where = where + "bet_time <= ?"
				whereparam = append(whereparam, e_time)
			}
		}
	}
	betrecord := new(BetRecord)
	total, err := m.Orm.Table("ag_bet_record").Where(where, whereparam...).Count(betrecord)

	if err != nil {
		common.Log.Err("GetBetRecords Count :", err)
		return nil, 0, err
	}
	if total > 0 {
		tp := total / pagenum
		if total%pagenum != 0 {
			tp = tp + 1
		}
		statid := (page - 1) * pagenum
		var betrecords []BetRecord
		err = m.Orm.Table("ag_bet_record").Where(where, whereparam...).Limit(int(pagenum), int(statid)).Desc("bet_time").Find(&betrecords)
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
func GetAllMonery(username, orderid, siteid, agentid, video_type, game_type, s_time, e_time string, pagenum, page int64) (float64, float64, float64, float64, error) {
	where := ""
	whereparam := make([]interface{}, 0)
	allsiteid := common.Cfg.Section("app").Key("MANAGE_SITEID").MustString("manage")
	if allsiteid != siteid {
		where = " site_id = ?"
		whereparam = append(whereparam, siteid)
	}
	if len(orderid) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		where += "bill_no = ?"
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
		if len(video_type) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			where = where + "data_type= 'BR'"
			if video_type != "all" {
				where = where + " AND game_type = ? "
				whereparam = append(whereparam, video_type)
			}
		} else if len(game_type) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			where = where + "data_type = 'EBR' AND data_type = 'HBR'"
			if game_type != "all" {
				where = where + " AND game_type = ? "
				whereparam = append(whereparam, game_type)
			}
		}
		if len(s_time) > 0 || len(e_time) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			if len(s_time) > 0 && len(e_time) > 0 {
				where = where + "bet_time BETWEEN ? AND ?"
				whereparam = append(whereparam, s_time)
				whereparam = append(whereparam, e_time)
			} else if len(s_time) > 0 && len(e_time) == 0 {
				where = where + "bet_time >= ?"
				whereparam = append(whereparam, s_time)
			} else if len(s_time) == 0 && len(e_time) > 0 {
				where = where + "bet_time <= ?"
				whereparam = append(whereparam, e_time)
			}
		}
	}

	var bb, vb, wb float64
	statement := "SELECT sum(bet_amount) as bb, sum(valid_betamount) as vb,sum(netamount) as wb  FROM `ag_bet_record` WHERE " + where
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
func GetAvailableAmountByUser(username, siteid string, isdz bool, s_time, e_time string) (int, int, float64, float64, float64, float64, error) {
	var bb, wb, vb, all_bb float64
	var times, vtimes int
	where := ""
	whereparam := make([]interface{}, 0)
	/*
		if !isdz {
			whereparam = append(whereparam, "BR")
		} else {
			whereparam = append(whereparam, "EBR")
		}
	*/
	if len(username) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		users := strings.Split(username, "|")
		//users := "'" + strings.Replace(username, "|", `','`, -1) + "'"
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
	statement := "SELECT count(*) as times,sum(bet_amount) as all_bb FROM `ag_bet_record` WHERE " + where
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
	where += "flag=1"
	statement = "SELECT count(*) as vtimes,sum(bet_amount) as bb, sum(valid_betamount) as vb, sum(netamount) as wb FROM `ag_bet_record` WHERE " + where
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
func GetAvailableAmountBySiteid(siteid string, isdz bool, s_time, e_time string) (int, float64, float64, float64, error) {
	var bb, wb, vb float64
	var times int
	where := "flag=1"
	whereparam := make([]interface{}, 0)
	/*
		if !isdz {
			whereparam = append(whereparam, "BR")
		} else {
			whereparam = append(whereparam, "EBR")
		}*/
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
			where = where + "bet_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "bet_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "bet_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times,sum(bet_amount) as bb, sum(valid_betamount) as vb,sum(netamount) as wb FROM `ag_bet_record` WHERE " + where
	rows1, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return 0, 0, 0, 0, err
	}
	if len(rows1) > 0 {
		bb, _ = utility.StrTo(rows1[0]["bb"]).Float64()
		times, _ = utility.StrTo(rows1[0]["times"]).Int()
		wb, _ = utility.StrTo(rows1[0]["wb"]).Float64()
		vb, _ = utility.StrTo(rows1[0]["vb"]).Float64()
	}

	return times, bb, vb, wb, err
}

func GetAvailableAmountBySiteids(siteid string, s_time, e_time string) ([]map[string][]byte, error) {

	where := "flag=1"
	whereparam := make([]interface{}, 0)
	/*
		if !isdz {
			whereparam = append(whereparam, "BR")
		} else {
			whereparam = append(whereparam, "EBR")
		}*/
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
			where = where + "bet_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "bet_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "bet_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times,sum(bet_amount) as bb,site_id, sum(valid_betamount) as vb,sum(netamount) as wb FROM `ag_bet_record` WHERE "
	statement += where + " group by site_id"
	rows1, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}
	return rows1, nil
}

//根据站点获取
func GetAvailableAmountByAgentid(agentid int64, isdz bool, siteid, s_time, e_time string) (int, float64, float64, float64, error) {
	var bb, wb, vb float64
	var times int
	where := "flag=1"
	whereparam := make([]interface{}, 0)
	/*
		if !isdz {
			whereparam = append(whereparam, "BR")
		} else {
			whereparam = append(whereparam, "EBR")
		}*/
	if agentid > 0 && len(siteid) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		where = where + "agent_id = ?"
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
			where = where + "bet_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "bet_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "bet_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times,sum(bet_amount) as bb, sum(valid_betamount) as vb,sum(netamount) as wb FROM `ag_bet_record` WHERE " + where
	rows1, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return 0, 0, 0, 0, err
	}
	if len(rows1) > 0 {
		bb, _ = utility.StrTo(rows1[0]["bb"]).Float64()
		times, _ = utility.StrTo(rows1[0]["times"]).Int()
		wb, _ = utility.StrTo(rows1[0]["wb"]).Float64()
		vb, _ = utility.StrTo(rows1[0]["vb"]).Float64()
	}

	return times, bb, vb, wb, err
}

//siteid,, agentid int64
func GetUserAvailableAmountByUser(username, siteid string, isdz bool, s_time, e_time string, notbrokerage bool) ([]map[string][]byte, error) {
	if isdz {
		return nil, m.NodianziErr
	}
	where := "flag=1"
	whereparam := make([]interface{}, 0)
	/*
		if !isdz {
			whereparam = append(whereparam, "BR")
		} else {
			whereparam = append(whereparam, "EBR")
		}*/
	if len(username) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		users := strings.Split(username, "|")
		for _, v := range users {
			whereparam = append(whereparam, v)
		}
		where = where + "pkusername in(" +
			strings.Trim(strings.Repeat("?,", len(users)), ",") + ") AND site_id = ?"
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
	statement := "SELECT count(*) as times, pkusername as account_number,sum(bet_amount) as bb, sum(valid_betamount) as vb,sum(netamount) as wb FROM `ag_bet_record` WHERE " + where + " group by player_name"
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
	where := "flag=1" // AND data_type=?"
	whereparam := make([]interface{}, 0)
	/*if !isdz {
		whereparam = append(whereparam, "BR")
	} else {
		whereparam = append(whereparam, "EBR")
	}*/
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
			where = where + "bet_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "bet_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "bet_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}

	statement := "SELECT count(*) as times, pkusername as account_number,sum(bet_amount) as bb, sum(valid_betamount) as vb,sum(netamount) as wb FROM `ag_bet_record` WHERE "
	statement += where + " group by player_name"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}
	/*
		userlist := make([]interface{}, 0)
		userkv := make(map[string][]byte)
		for _, v := range rows {
			userlist = append(userlist, v["account_number"])
		}

		userlist = append(userlist, siteid)
		userrows, err := m.Orm.Query("select username,g_username FROM ag_user WHERE g_username in("+
			strings.Trim(strings.Repeat("?,", len(rows)), ",")+") AND site_id = ?", userlist...)
		if err == nil && len(userrows) > 0 {
			sqlw := ""
			for _, v := range userrows {
				sqlw += "?,"
				userkv[string(v["g_username"])] = v["username"]
			}
		}
		for k, v := range rows {
			rows[k]["account_number"] = userkv[string(v["account_number"])]
		}
	*/
	return rows, err
}

//根据站点获取
func GetUserAvailableAmountByAgentid(agentid string, isdz bool, siteid, s_time, e_time string) ([]map[string][]byte, error) {
	if isdz {
		return nil, m.NodianziErr
	}
	where := "flag=1"
	whereparam := make([]interface{}, 0)
	/*
		if !isdz {
			whereparam = append(whereparam, "BR")
		} else {
			whereparam = append(whereparam, "EBR")
		}*/
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
			where = where + "bet_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "bet_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "bet_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, pkusername as account_number,sum(bet_amount) as bb,sum(valid_betamount) as vb,sum(netamount) as wb FROM `ag_bet_record` WHERE "
	statement += where + " group by player_name"
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
	where := "flag=1"
	whereparam := make([]interface{}, 0)
	/*
		if !isdz {
			whereparam = append(whereparam, "BR")
		} else {
			whereparam = append(whereparam, "EBR")
		}*/
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
			where = where + "bet_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "bet_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "bet_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, agent_id,sum(bet_amount) as bb,sum(valid_betamount) as vb,sum(netamount) as wb FROM `ag_bet_record`  WHERE " +
		where + " group by ag_bet_record.agent_id"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}

	return rows, err
}

//更新注单表中没有代理id和用户名的数据

func AG_UpateBetAgentUsername() error {
	pagesize := 100
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
				log.Println("ag----", pagesize, i*pagesize, v)
				_, err := m.Orm.Table(new(BetRecord)).Where("player_name = ?", v.GUserName).Update(map[string]interface{}{"agent_id": v.AgentId, "pkusername": v.UserName, "site_id": v.SiteId})
				//m.Orm.Ping()
				if err != nil {
					log.Println("ag----", err, m.Orm.Ping())
				}
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
func AG_UpateBetAgentUsername() error {
	bets := make([]BetRecord, 0)
	pagesize := 100
	i := 0
	hasuser := true
	for {
		if !hasuser {
			break
		}
		err := m.Orm.Cols("player_name").Limit(pagesize, i*pagesize).Where("agent_id = 0 OR pkusername = ''").GroupBy("player_name").Find(&bets)
		if err != nil {
			return err
		}
		count := len(bets)
		guser := make([]string, 0)
		if count > 0 {
			//更新注单，以用户名
			for _, v := range bets {
				guser = append(guser, v.PlayerName)
			}
		}
		users, err := GetUserByGname(guser)
		if err != nil {
			common.Log.Err("ag GetUserByGname:", err)
		} else {
			for _, v := range users {
				m.Orm.Table(new(BetRecord)).Where("player_name = ?", v.GUserName).Update(map[string]interface{}{"agent_id": v.AgentId, "pkusername": v.UserName})
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
