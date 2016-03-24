package bbin

import (
	m "app/models"
	"common"

//"fmt"
	"log"
	"strings"
	"time"
	"utility"
)

//具体的数据
type BbinBetRecord_form_Jsion struct {
	Id              int64       `xorm:"'id'" json:"-"`
	UserName        string      `xorm:"'username'"`
	WagersID        string      `xorm:"pk 'wagers_id'"`
	WagersDate      string      `xorm:"'wagers_date'"`
	GameType        string      `xorm:"'gametype'"`
	Result          string      `xorm:"'result'"`
	BetAmount       string      `xorm:"'betamount'"`
	Payoff_         interface{} `xorm:"-" json:"Payoff"`
	Payoff          string      `xorm:"'payoff'" json:"-"`
	Currency        string      `xorm:"'currency'"`
	Commissionable_ interface{} `xorm:"-" json:"Commissionable"`
	Commissionable  string      `xorm:"'commissionable'" json:"-"`

							       //视讯游戏
	SerialID        string `xorm:"'serial_id'"`
	RoundNo         string `xorm:"'round_no'"`
	GameCode        string `xorm:"'game_code'"`
	ResultType      string `xorm:"'result_type'"`
	Card            string `xorm:"'card'"`
	ExchangeRate    string `xorm:"'exchange_rate'"`

							       //机率游戏
							       //彩票游戏
							       //三D厅
	Commission      string `xorm:"'commission'"`
	IsPaid          string `xorm:"'is_paid'"`
							       //注单变更时间
	UPTIME          string `xorm:"'uptime'"`
	OrderDate       string `xorm:"'order_date'"`
	ModifiedDate    string `xorm:"'modified_date'"`

							       //附加数据
	Gamekind        int       `xorm:"'gamekind'" json:"-"` //大类型
	Site_id         string    `xorm:"'site_id'" json:"-"`
	Agent_id        int       `xorm:"'agent_id'" json:"-"`
	Index_id        string    `xorm:"'index_id'" json:"-"`
	PkUsername      string    `xorm:"'pkusername'" json:"-"`
	UpdateTime      time.Time `xorm:"'update_time' created" json:"-"`
}

//具体的数据
type BbinBetRecord struct {
	Id             int64  `xorm:"'id'"`
	UserName       string `xorm:"'username'"`
	WagersID       string `xorm:"pk 'wagers_id'"`
	WagersDate     string `xorm:"'wagers_date'"`
	GameType       string `xorm:"'gametype'"`
	Result         string `xorm:"'result'"`
	BetAmount      string `xorm:"'betamount'"`
	Payoff         string `xorm:"'payoff'"`
	Currency       string `xorm:"'currency'"`
	Commissionable string `xorm:"'commissionable'"`

							      //视讯游戏
	SerialID       string `xorm:"'serial_id'"`
	RoundNo        string `xorm:"'round_no'"`
	GameCode       string `xorm:"'game_code'"`
	ResultType     string `xorm:"'result_type'"`
	Card           string `xorm:"'card'"`
	ExchangeRate   string `xorm:"'exchange_rate'"`

							      //机率游戏
							      //彩票游戏
							      //三D厅
	Commission     string `xorm:"'commission'"`
	IsPaid         string `xorm:"'is_paid'"`
							      //注单变更时间
	UPTIME         string `xorm:"'uptime'"`
	OrderDate      string `xorm:"'order_date'"`
	ModifiedDate   string `xorm:"'modified_date'"`

							      //附加数据
	Gamekind       int       `xorm:"'gamekind'" json:"-"` //大类型
	Site_id        string    `xorm:"'site_id'" json:"-"`
	Agent_id       int       `xorm:"'agent_id'" json:"-"`
	Index_id       string    `xorm:"'index_id'" json:"-"`
	PkUsername     string    `xorm:"'pkusername'" json:"-"`
	UpdateTime     time.Time `xorm:"'update_time' created" json:"-"`
}

/**
1：球类、3：视讯、5：机率、12：彩票、//15：3D厅
**/
func (a *BbinBetRecord) TableName() string {
	return "bbin_bet_record"
}

func getBetTableName(datestr string) string {
	return "bbin_bet_record"
}

func (a *BbinBetRecord_form_Jsion) TableName() string {
	return "bbin_bet_record"
}

func CreateBetRecord(row *BbinBetRecord) error {
	bettime, err := time.Parse("2006-01-02 15:04:05", row.WagersDate)
	if err != nil {
		common.Log.Err("Insert bbin BetRecord :", err)
		return err
	}
	//m.Orm.Table(getBetTableName(bettime.Format("200601")))
	//_, err = m.Orm.Insert(ogbet)
	if len(row.UPTIME) == 0 {
		row.UPTIME = "0000-00-00 00:00:00"
	}
	if len(row.OrderDate) == 0 {
		row.OrderDate = "0000-00-00 00:00:00"
	}
	if len(row.ModifiedDate) == 0 {
		row.ModifiedDate = "0000-00-00 00:00:00"
	}
	sqlstr := "INSERT INTO `" + getBetTableName(bettime.Format("200601")) +
	"` (`wagers_id`, `username`, `wagers_date`, `gametype`, `result`, `betamount`, `payoff`, `currency`, `commissionable`, `serial_id`, `round_no`, `game_code`, `result_type`, `card`, `exchange_rate`, `commission`, `is_paid`, `uptime`, `order_date`, `modified_date`, `gamekind`, `site_id`, `agent_id`, `index_id`, `pkusername`, `update_time`) " +
	"VALUES ( '" + row.WagersID + "', '" + row.UserName + "','" + row.WagersDate + "', '" + row.GameType + "', '" + row.Result + "', '" + utility.ToStr(row.BetAmount) + "', '" + utility.ToStr(row.Payoff) + "', '" + row.Currency + "', '" + utility.ToStr(row.Commissionable) + "', '" + row.Site_id + "', '" + row.RoundNo + "', '" +
	row.GameCode + "', '" + row.ResultType + "', '" + row.Card + "', '" + row.ExchangeRate + "', '" + row.Commission + "', '" + row.IsPaid + "', '" + row.UPTIME + "', '" + row.OrderDate + "', '" + row.ModifiedDate + "', '" + utility.ToStr(row.Gamekind) + "', '" + row.Site_id + "', '" + utility.ToStr(row.Agent_id) + "', '" + row.Index_id + "','" + row.PkUsername + "','" + time.Now().Format("2006-01-02 15:04:05") + "')"
	sqlstr = sqlstr +
	"ON DUPLICATE KEY UPDATE  `wagers_date`=VALUES(`wagers_date`), `gametype`=VALUES(`gametype`), `result`=VALUES(`result`), `betamount`=VALUES(`betamount`), `payoff`=VALUES(`payoff`),  `commissionable`=VALUES(`commissionable`), `serial_id`=VALUES(`serial_id`), `round_no`=VALUES(`round_no`), `game_code`=VALUES(`game_code`), `result_type`=VALUES(`result_type`), `card`=VALUES(`card`), `exchange_rate`=VALUES(`exchange_rate`), `commission`=VALUES(`commission`), `is_paid`=VALUES(`is_paid`), `uptime`=VALUES(`uptime`), `order_date`=VALUES(`order_date`), `modified_date`=VALUES(`modified_date`)"
	sqlstr = strings.Replace(sqlstr, "''", "' '", -1)
	_, err = m.Orm.Exec(sqlstr)

	return err
}

func InsertBatchBetRecord(rows []BbinBetRecord_form_Jsion, gamekind int, site_id, datestr string) error {
	sqlstr_head := "INSERT INTO `" + getBetTableName(datestr) +
	"` (`wagers_id`, `username`, `wagers_date`, `gametype`, `result`, `betamount`, `payoff`, `currency`, `commissionable`, `serial_id`, `round_no`, `game_code`, `result_type`, `card`, `exchange_rate`, `commission`, `is_paid`, `uptime`, `order_date`, `modified_date`, `gamekind`, `site_id`, `agent_id`, `index_id`, `pkusername`, `update_time`) " +
	"VALUES "

	sqlstr_foot :=
	"ON DUPLICATE KEY UPDATE  `wagers_date`=VALUES(`wagers_date`), `gametype`=VALUES(`gametype`), `result`=VALUES(`result`), `betamount`=VALUES(`betamount`), `payoff`=VALUES(`payoff`),  `commissionable`=VALUES(`commissionable`), `serial_id`=VALUES(`serial_id`), `round_no`=VALUES(`round_no`), `game_code`=VALUES(`game_code`), `result_type`=VALUES(`result_type`), `card`=VALUES(`card`), `exchange_rate`=VALUES(`exchange_rate`), `commission`=VALUES(`commission`), `is_paid`=VALUES(`is_paid`), `uptime`=VALUES(`uptime`), `order_date`=VALUES(`order_date`), `modified_date`=VALUES(`modified_date`)"

	sql_values := make([]string, 0)
	for _, row := range rows {
		row.Payoff = utility.ToStr(row.Payoff_)
		row.Gamekind = gamekind
		row.Site_id = site_id
		if gamekind == 12 {
			//如果result 为 W L 这修改有效投注为投注金额、
			if row.Result == "W" || row.Result == "L" {
				row.Commissionable = row.BetAmount
			}
		} else {
			row.Commissionable = utility.ToStr(row.Commissionable_)
		}
		if len(strings.Trim(row.ExchangeRate, " ")) == 0 {
			row.ExchangeRate = "0"
		}

		row.UpdateTime = time.Now()
		if len(row.UPTIME) == 0 {
			row.UPTIME = "0000-00-00 00:00:00"
		}
		if len(row.OrderDate) == 0 {
			row.OrderDate = "0000-00-00 00:00:00"
		}
		if len(row.ModifiedDate) == 0 {
			row.ModifiedDate = "0000-00-00 00:00:00"
		}

		sql_values = append(sql_values, "( '" + row.WagersID + "', '" + row.UserName + "','" + row.WagersDate + "', '" + row.GameType + "', '" + row.Result + "', '" + utility.ToStr(row.BetAmount) + "', '" + utility.ToStr(row.Payoff) + "', '" + row.Currency + "', '" + utility.ToStr(row.Commissionable) + "', '" + row.Site_id + "', '" + row.RoundNo + "', '" +
		row.GameCode + "', '" + row.ResultType + "', '" + row.Card + "', '" + row.ExchangeRate + "', '" + row.Commission + "', '" + row.IsPaid + "', '" + row.UPTIME + "', '" + row.OrderDate + "', '" + row.ModifiedDate + "', '" + utility.ToStr(row.Gamekind) + "', '" + row.Site_id + "', '" + utility.ToStr(row.Agent_id) + "', '" + row.Index_id + "','" + row.PkUsername + "','" + time.Now().Format("2006-01-02 15:04:05") + "')")
	}
	//组合sql
	sqlstr := sqlstr_head + strings.Join(sql_values, ",") + sqlstr_foot
	sqlstr = strings.Replace(sqlstr, "''", "' '", -1)
	_, err := m.Orm.Exec(sqlstr)
	if err != nil {
		common.Log.Err("Insert bbin BetRecord :", err, sql_values)
	}

	//try---catch---
	defer func() {
		if err := recover(); err != nil {
			common.Log.Errf("Lebo bbin PANIC: %s\n%s", err, rows)
			//发邮件报告
			return
		}
	}()
	return nil
}

func UpdateBatchBetRecord(rows []BbinBetRecord_form_Jsion) error {
	for _, row := range rows {
		//row.Gamekind = gamekind
		//row.Site_id = site_id;
		bettime, err := time.Parse("2006-01-02 15:04:05", row.WagersDate)
		if err != nil {
			common.Log.Err("Insert bbin BetRecord :", err)
			return err
		}
		_, err = m.Orm.Table(getBetTableName(bettime.Format("200601"))).Where("wagers_id = ?", row.WagersID).Update(&row)
		if err != nil {
			common.Log.Err("Update bbin BetRecord :", err, row)
		}
	}
	////TODO添加失败更新
	return nil
}

//gamekind 游戏大类型，game_type 小类型
func GetBetRecords(username, orderid, siteid, agentid, gamekind, game_type, s_time, e_time string, pagenum, page int64) ([]BbinBetRecord, int64, error) {
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
		where += "wagers_id = ?"
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
			//where = where + "username = ?"
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
		//彩票 ，足球
		if len(gamekind) > 0 && gamekind != "all" {
			if len(where) > 0 {
				where = where + " AND "
			}
			if gamekind == "5" {
				where = where + "gamekind in (5,15)"
				//whereparam = append(whereparam, gamekind)
			} else {
				where = where + "gamekind = ?"
				whereparam = append(whereparam, gamekind)
			}
		}
		if len(game_type) > 0 && game_type != "all" {
			if len(where) > 0 {
				where = where + " AND "
			}
			where = where + "gametype = ?"
			whereparam = append(whereparam, game_type)
		}
		if len(s_time) > 0 || len(e_time) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			if len(s_time) > 0 && len(e_time) > 0 {
				where = where + "wagers_date BETWEEN ? AND ?"
				whereparam = append(whereparam, s_time)
				whereparam = append(whereparam, e_time)
			} else if len(s_time) > 0 && len(e_time) == 0 {
				where = where + "wagers_date >= ?"
				whereparam = append(whereparam, s_time)
			} else if len(s_time) == 0 && len(e_time) > 0 {
				where = where + "wagers_dates <= ?"
				whereparam = append(whereparam, e_time)
			}
		}
	}

	betrecord := new(BbinBetRecord)
	total, err := m.Orm.Table("bbin_bet_record").Where(where, whereparam...).Count(betrecord)
	if err != nil {
		common.Log.Err("bbin GetBetRecords Count :", err)
		return nil, 0, err
	}
	if total > 0 {
		tp := total / pagenum
		if total % pagenum != 0 {
			tp = tp + 1
		}
		statid := (page - 1) * pagenum
		var betrecords []BbinBetRecord
		err = m.Orm.Table("bbin_bet_record").Where(where, whereparam...).Limit(int(pagenum), int(statid)).Desc("wagers_date").Find(&betrecords)
		if err != nil {
			common.Log.Err("bbin GetBetRecords Count :", err)
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
func GetAllMonery(username, orderid, siteid, agentid, gamekind, gametype, s_time, e_time string, pagenum, page int64) (float64, float64, float64, float64, error) {
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
		where += "wagers_id = ?"
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
		//彩票 ，足球
		if len(gamekind) > 0 && gamekind != "all" {
			if len(where) > 0 {
				where = where + " AND "
			}
			if gamekind == "5" {
				where = where + "gamekind in (5,15)"
				//whereparam = append(whereparam, gamekind)
			} else {
				where = where + "gamekind = ?"
				whereparam = append(whereparam, gamekind)
			}
		}
		if len(gametype) > 0 && gametype != "all" {
			if len(where) > 0 {
				where = where + " AND "
			}
			where = where + "gametype = ?"
			whereparam = append(whereparam, gametype)
		}
		if len(s_time) > 0 || len(e_time) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			if len(s_time) > 0 && len(e_time) > 0 {
				where = where + "wagers_date BETWEEN ? AND ?"
				whereparam = append(whereparam, s_time)
				whereparam = append(whereparam, e_time)
			} else if len(s_time) > 0 && len(e_time) == 0 {
				where = where + "wagers_date >= ?"
				whereparam = append(whereparam, s_time)
			} else if len(s_time) == 0 && len(e_time) > 0 {
				where = where + "wagers_dates <= ?"
				whereparam = append(whereparam, e_time)
			}
		}
	}

	var bb, vb, wb float64
	statement := "SELECT sum(betamount) as bb,sum(Commissionable) as vb,sum(payoff) as wb FROM `bbin_bet_record` WHERE " + where
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

	var bb, wb, vb, all_bb float64
	var times, vtimes int

	whereparam := make([]interface{}, 0)
	where := "site_id = ?"
	whereparam = append(whereparam, siteid)
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
	statement := "SELECT count(*) as times, sum(betamount) as all_bb FROM `bbin_bet_record` WHERE " + where
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
	where += "result_type <> '-1' AND result <> 'D' AND result <> '-1'" //下面计算有效的
	statement = "SELECT count(*) as vtimes, sum(betamount) as bb,sum(commissionable) as vb,sum(payoff) as wb FROM `bbin_bet_record` WHERE " + where
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

	where := "result_type <> '-1' AND result <> 'D' AND result <> '-1'"
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
			where = where + "wagers_date BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "wagers_date >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "wagers_date <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, sum(betamount) as bb,sum(commissionable) as vb,sum(payoff) as wb FROM `bbin_bet_record` WHERE " + where
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

func GetAvailableAmountBySiteids(siteid, s_time, e_time string, isdz bool) ([]map[string][]byte, error) {

	where := "result_type <> '-1' AND result <> 'D' AND result <> '-1'"
	whereparam := make([]interface{}, 0)
	if isdz {
		where = where + " AND gamekind in (5,15)"
		//whereparam = append(whereparam, gamekind)
	} else {
		where = where + " AND gamekind not in (5,15)"
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
			where = where + "wagers_date BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "wagers_date >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "wagers_date <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times,site_id, sum(betamount) as bb,sum(commissionable) as vb,sum(payoff) as wb FROM `bbin_bet_record` WHERE "
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

	where := "result_type <> '-1' AND result <> 'D' AND result <> '-1' AND site_id = ?"
	whereparam := make([]interface{}, 0)
	whereparam = append(whereparam, siteid)
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
			where = where + "wagers_date BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "wagers_date >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "wagers_date <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, sum(betamount) as bb,sum(commissionable) as vb,sum(payoff) as wb FROM `bbin_bet_record` WHERE " + where
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
//notbrokerage bool 是否去除免佣的注单的统计
func GetUserAvailableAmountByUser(username, siteid, s_time, e_time, gametype string, notbrokerage bool) ([]map[string][]byte, error) {

	where := "result_type <> '-1' AND result <> 'D' AND result <> '-1' AND site_id = ?"
	whereparam := make([]interface{}, 0)
	whereparam = append(whereparam, siteid)
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
	} //游戏类型
	if len(gametype) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		if gametype == "5" {
			where = where + "gamekind in (5,15)"
			//whereparam = append(whereparam, gamekind)
		} else if gametype == "99" {
			where = where + "gamekind not in (5,15)"
		} else {
			where = where + "gamekind = ?"
			whereparam = append(whereparam, gametype)
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
	statement := "SELECT count(*) as times, pkusername as account_number,sum(betamount) as bb,sum(commissionable) as vb,sum(payoff) as wb FROM `bbin_bet_record` WHERE " + where + " group by username"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}
	return rows, err
}

//根据站点获取
func GetUserAvailableAmountBySiteid(siteid, gametype string, s_time, e_time string) ([]map[string][]byte, error) {
	where := "result_type <> '-1' AND result <> 'D' AND result <> '-1'"
	whereparam := make([]interface{}, 0)
	if len(siteid) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		where = where + "site_id = ?"
		whereparam = append(whereparam, siteid)
	} else {
		return nil, m.ParamErr
	} //游戏类型
	if len(gametype) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		if gametype == "5" {
			where = where + "gamekind in (5,15)"
			//whereparam = append(whereparam, gamekind)
		} else if gametype == "99" {
			where = where + "gamekind not in (5,15)"
		} else {
			where = where + "gamekind = ?"
			whereparam = append(whereparam, gametype)
		}
	}
	if len(s_time) > 0 || len(e_time) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		if len(s_time) > 0 && len(e_time) > 0 {
			where = where + "wagers_date BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "wagers_date >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "wagers_date <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, pkusername as account_number,sum(betamount) as bb,sum(commissionable) as vb,sum(payoff) as wb FROM `bbin_bet_record` WHERE "
	statement += where + " group by username"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}

	return rows, err
}

//根据d代理id获取
func GetAgentAvailableAmountByAgentid(agentid, gametype string, siteid, s_time, e_time string) ([]map[string][]byte, error) {
	where := "result_type <> '-1' AND result <> 'D' AND result <> '-1'"
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
	//游戏类型
	if len(gametype) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		if gametype == "5" {
			where = where + "gamekind in (5,15)"
			//whereparam = append(whereparam, gamekind)
		} else if gametype == "99" {
			where = where + "gamekind not in (5,15)"
		} else {
			where = where + "gamekind = ?"
			whereparam = append(whereparam, gametype)
		}
	}
	if len(s_time) > 0 || len(e_time) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		if len(s_time) > 0 && len(e_time) > 0 {
			where = where + "wagers_date BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "wagers_date >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "wagers_date <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, agent_id ,sum(betamount) as bb," +
	"sum(commissionable) as vb,sum(payoff) as wb FROM `bbin_bet_record` " +
	" WHERE " + where + " group by `agent_id`"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}

	return rows, err
}

//根据站点获取
func GetUserAvailableAmountByAgentid(agentid, gametype string, siteid, s_time, e_time string) ([]map[string][]byte, error) {

	where := "result_type <> '-1' AND result <> 'D' AND result <> '-1' AND site_id = ?"
	whereparam := make([]interface{}, 0)
	whereparam = append(whereparam, siteid)

	if len(agentid) > 0 && len(siteid) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		ids := strings.Split(agentid, "|")
		where = where + "agent_id in(" +
		strings.Trim(strings.Repeat("?,", len(ids)), ",") + ") "
		for _, v := range ids {
			whereparam = append(whereparam, v)
		}
	} else {
		return nil, m.ParamErr
	}
	//游戏类型
	if len(gametype) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		if gametype == "5" {
			where = where + "gamekind in (5,15)"
			//whereparam = append(whereparam, gamekind)
		} else if gametype == "99" {
			where = where + "gamekind not in (5,15)"
		} else {
			where = where + "gamekind = ?"
			whereparam = append(whereparam, gametype)
		}
	}
	if len(s_time) > 0 || len(e_time) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		if len(s_time) > 0 && len(e_time) > 0 {
			where = where + "wagers_date BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "wagers_date >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "wagers_date <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, pkusername as account_number,sum(betamount) as bb,sum(commissionable) as vb,sum(payoff) as wb FROM `bbin_bet_record` WHERE "
	statement += where + " group by username"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}

	return rows, err
}

//更新注单表中没有代理id和用户名的数据
func Bbin_UpateBetAgentUsername() error {
	pagesize := 300
	i := 0
	hasuser := true
	for {
		if !hasuser {
			break
		}
		users := make([]User, 0)
		err := m.Orm.Limit(pagesize, i * pagesize).Find(&users)
		if err != nil {
			return err
		}
		count := len(users)
		//guser := make([]string, 0)
		if count > 0 {
			for _, v := range users {
				log.Println("bbin----", pagesize, i * pagesize, v)
				_, err := m.Orm.Table(new(BbinBetRecord)).Where("username = ?", v.GUserName).Update(map[string]interface{}{"agent_id": v.AgentId, "pkusername": v.UserName, "site_id": v.SiteId})
				if err != nil {
					log.Println("bbin----", err, m.Orm.Ping())
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
func Bbin_UpateBetAgentUsername() error {
	pagesize := 100
	i := 0
	hasuser := true
	for {
		if !hasuser {
			break
		}
		bets := make([]BbinBetRecord, 0)
		err := m.Orm.Cols("username").Limit(pagesize, i*pagesize).Where("agent_id = 0 OR pkusername = ''").GroupBy("username").Find(&bets)
		if err != nil {
			return err
		}
		fmt.Println("bbin---------------")
		fmt.Println(pagesize, i*pagesize)
		fmt.Println("bbin---------------")
		count := len(bets)
		guser := make([]string, 0)
		if count > 0 {
			//更新注单，以用户名
			for _, v := range bets {
				guser = append(guser, v.UserName)
			}
		}
		users, err := GetUserByGname(guser)
		if err != nil {
			common.Log.Err("bbin GetUserByGname:", err)
		} else {
			for _, v := range users {
				m.Orm.Table(new(BbinBetRecord)).Where("username = ?", v.GUserName).Update(map[string]interface{}{"agent_id": v.AgentId, "pkusername": v.UserName, "site_id": v.SiteId})
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
