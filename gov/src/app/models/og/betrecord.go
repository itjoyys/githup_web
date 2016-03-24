package og

import (
	m "app/models"
	"common"
	"utility"
	"fmt"
	"strings"
	"time"
)

//og注单存储
type BetRecord struct {
	Id                 int64     `xorm:"'id'"`
	ProductID          int64     `xorm:"pk 'product_id'"`
	UserName           string    `xorm:"'user_name'"`            //`sql:"user_name"`
	GameRecordID       int64     `xorm:"'game_record_id'"`       //`sql:"game_record_id"`
	OrderNumber        int64     `xorm:"'order_number'"`         //`sql:"order_number"`
	TableID            int64     `xorm:"'table_id'"`             //`sql:"table_id"`
	Stage              int64     `xorm:"'stage'"`                // `sql:"stage"`
	Inning             int64     `xorm:"'inning'"`               //`sql:"inning"`
	GameNameID         int64     `xorm:"'game_name_id'"`         // `sql:"game_name_id"`
	GameBettingKind    int64     `xorm:"'game_betting_kind'"`    //`sql:"game_betting_kind"`
	GameBettingContent string    `xorm:"'game_betting_content'"` // `sql:"game_betting_content"`
	ResultType         int8      `xorm:"'result_type'"`          //`sql:"result_type"`
	BettingAmount      float64   `xorm:"'betting_amount'"`       //`sql:"betting_amount"`
	CompensateRate     float64   `xorm:"'compensate_rate'"`      //`sql:"compensate_rate"`
	WinLoseAmount      float64   `xorm:"'win_lose_amount'"`      //`sql:"win_lose_amount"`
	Balance            float64   `xorm:"'balance'"`              //`sql:"balance"`
	AddTime            string    `xorm:"'add_time'"`             //`sql:"add_time"`
	PlatformID         int8      `xorm:"'platform_id'"`          // `sql:"platform_id"`
	VendorId           int64     `xorm:"'vendor_id'"`            // `sql:"vendor_id"`
	ValidAmount        float64   `xorm:"'valid_amount'"`         // `sql:"vendor_id"`
	Site_id            string    `xorm:"'site_id'" xml:"-"`
	Agent_id           int       `xorm:"'agent_id'" xml:"-"`
	Index_id           string       `xorm:"'index_id'" xml:"-"`
	PkUsername         string    `xorm:"'pkusername'" xml:"-"`
	UpdateTime         time.Time `xorm:"'update_time' created"`
}

var (
	sql_fileds = []string{"product_id", "user_name", "game_record_id", "order_number", "table_id",
		"stage", "inning", "game_name_id", "game_betting_kind", "game_betting_content",
		"result_type", "betting_amount", "compensate_rate", "win_lose_amount", "balance",
		"add_time", "platform_id", "vendor_id", "valid_amount", "site_id", "agent_id", "pkusername", "update_time"}
)

func (a *BetRecord) TableName() string {
	return "og_bet_record"
}

func getBetTableName(datestr string) string {
	return "og_bet_record"
}

func CreateBetRecord(ogbet *BetRecord) error {
	//2014/12/29 15:24:06
	bettime, err := time.Parse("2006/01/02 15:04:05", ogbet.AddTime)
	if err != nil {
		common.Log.Err("Insert MG BetRecord :", err)
		return err
	}
	//m.Orm
	_, err = m.Orm.Table(getBetTableName(bettime.Format("200601"))).Insert(ogbet)
	if err != nil {
		if strings.Index(err.Error(), "for key") > 0 && strings.Index(err.Error(), "Duplicate entry") > 0 {
			//TODO 重复的更新
			m.Orm.Table(getBetTableName(bettime.Format("200601"))).Where("vendor_id = ?", ogbet.VendorId).Update(ogbet)
		} else {
			common.Log.Err("Insert OG BetRecord :", err, ogbet)
		}
	}

	return err
}

func GetLastBetRecorVid_bytime(lasttime time.Time) (int64, error) {
	cr := new(BetRecord)
	has, err := m.Orm.Table("og_bet_record").Where("update_time <= ?", lasttime.Format("2006-01-02 15:04:05")).OrderBy("vendor_id DESC").Get(cr)
	if err != nil {
		common.Log.Err("GetLastBetRecorVid SQL err:", err)
		return 0, err
	}
	if !has {
		return 1422488741612, nil
	}
	return cr.VendorId, nil
}

func GetLastBetRecorVid(siteid string) (int64, error) {
	cr := new(BetRecord)
	has, err := m.Orm.Table("og_bet_record").Where("site_id = ?", siteid).OrderBy("vendor_id DESC").Get(cr)
	if err != nil {
		common.Log.Err("GetLastBetRecorVid SQL err:", err)
		return 0, err
	}
	if !has {
		return 1422488741612, nil
	}
	return cr.VendorId, nil
}

func InsertBatchBetRecord(rows []map[string]interface{}, datestr string) error {
	///创建insert语句
	/*
	ogbet := new(BetRecord)
	for _, val := range rows {
		ogbet = new(BetRecord)
		ogbet.ProductID = val["product_id"]
		ogbet.UserName = val["user_name"]
		ogbet.GameRecordID = val["game_record_id"]
		ogbet.OrderNumber = val["order_number"]
		ogbet.TableID = val["table_id"]
		ogbet.Stage = val["stage"]
		ogbet.Inning = val["inning"]
		ogbet.GameNameID = val["game_name_id"]
		ogbet.GameBettingKind = val["game_betting_kind"]
		ogbet.GameBettingContent = val["game_betting_content"]
		ogbet.ResultType = val["result_type"]
		ogbet.BettingAmount = val["betting_amount"]
		ogbet.CompensateRate = val["compensate_rate"]
		ogbet.WinLoseAmount = val["win_lose_amount"]
		ogbet.Balance = val["balance"]
		ogbet.AddTime = val["add_time"]
		ogbet.PlatformID = val["platform_id"]
		ogbet.VendorId = val["vendor_id"]
		ogbet.ValidAmount = val["valid_amount"]
		ogbet.Site_id = val["site_id"]
		ogbet.Agent_id = val["agent_id"]
		ogbet.PkUsername = val["pkusername"]
		ogbet.UpdateTime = time.Now().Format("2006-01-02 15:04:05")
		CreateBetRecord(ogbet)
	}
	ogbet = nil

	*/
	for _, properties := range rows {
		var keys []string
		var placeholders []string
		var args []interface{}
		for key, val := range properties {
			for _, v := range sql_fileds {
				if v == key {
					keys = append(keys, key)
					placeholders = append(placeholders, "'"+utility.ToStr(val)+"'")
					//args = append(args, val)
					break
				}
			}
		}

		//keys = append(keys, "site_id")
		//placeholders = append(placeholders, "'"+utility.ToStr(properties["site_id"])+"'")
		//args = append(args, properties["site_id"])

		ss := fmt.Sprintf("%v,%v", "`", "`")
		statement := fmt.Sprintf("INSERT INTO %v%v%v (%v%v%v) VALUES (%v)",
			"`",
			getBetTableName(datestr),
			"`",
			"`",
			strings.Join(keys, ss),
			"`",
			strings.Join(placeholders, ", "))
		statement = strings.Replace(statement,"''","' '",-1)
		_, err := m.Orm.Exec(statement)
		if err != nil {
			if strings.Index(err.Error(), "for key") > 0 && strings.Index(err.Error(), "Duplicate entry") > 0 {
				//TODO 重复的更新

			} else {
				common.Log.Err("Insert OG BetRecord :", err, statement, args)
			}
		}
	}

	return nil
}

func GetBetRecords(username, orderid, siteid, agentid, video_type, s_time, e_time string, pagenum, page int64) ([]BetRecord, int64, error) {
	//时间转换为北京时间
	//s_time, _ = common.Changetimezone(s_time, "America/New_York", "Asia/Shanghai") //stime.Format("2006-01-02 15:04:05")
	//e_time, _ = common.Changetimezone(e_time, "America/New_York", "Asia/Shanghai") //etime.Format("2006-01-02 15:04:05")

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

		where += "order_number = ?"
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

			//where = where + "user_name = ?"
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
			where = where + "game_name_id = ?"
			whereparam = append(whereparam, video_type)
		}
		if len(s_time) > 0 || len(e_time) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			if len(s_time) > 0 && len(e_time) > 0 {
				where = where + "add_time BETWEEN ? AND ?"
				whereparam = append(whereparam, s_time)
				whereparam = append(whereparam, e_time)
			} else if len(s_time) > 0 && len(e_time) == 0 {
				where = where + "add_time >= ?"
				whereparam = append(whereparam, s_time)
			} else if len(s_time) == 0 && len(e_time) > 0 {
				where = where + "add_time <= ?"
				whereparam = append(whereparam, e_time)
			}
		}
	}

	betrecord := new(BetRecord)
	total, err := m.Orm.Table("og_bet_record").Where(where, whereparam...).Count(betrecord)
	if err != nil {
		common.Log.Err("OG GetBetRecords Count :", err)
		return nil, 0, err
	}
	if total > 0 {
		tp := total / pagenum
		if total % pagenum != 0 {
			tp = tp + 1
		}
		statid := (page - 1) * pagenum
		var betrecords []BetRecord
		err = m.Orm.Table("og_bet_record").Where(where, whereparam...).Limit(int(pagenum), int(statid)).Desc("product_id").Find(&betrecords)
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
	//时间转换为北京时间
	//s_time, _ = common.Changetimezone(s_time, "America/New_York", "Asia/Shanghai") //stime.Format("2006-01-02 15:04:05")
	//e_time, _ = common.Changetimezone(e_time, "America/New_York", "Asia/Shanghai") //etime.Format("2006-01-02 15:04:05")
	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+12h")
	stime = stime.Add(d)
	etime = etime.Add(d)
	s_time = stime.Format("2006-01-02 15:04:05")
	e_time = etime.Format("2006-01-02 15:04:05")

	whereparam := make([]interface{}, 0)
	where := ""
	allsiteid := common.Cfg.Section("app").Key("MANAGE_SITEID").MustString("manage")
	if allsiteid != siteid {
		where = "site_id = ?"
		whereparam = append(whereparam, siteid)
	}
	if len(orderid) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		where += "order_number = ?"
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
		if len(video_type) > 0 && video_type != "all" {
			if len(where) > 0 {
				where = where + " AND "
			}
			where = where + "game_name_id = ?"
			whereparam = append(whereparam, video_type)
		}
		if len(s_time) > 0 || len(e_time) > 0 {
			if len(where) > 0 {
				where = where + " AND "
			}
			if len(s_time) > 0 && len(e_time) > 0 {
				where = where + "add_time BETWEEN ? AND ?"
				whereparam = append(whereparam, s_time)
				whereparam = append(whereparam, e_time)
			} else if len(s_time) > 0 && len(e_time) == 0 {
				where = where + "add_time >= ?"
				whereparam = append(whereparam, s_time)
			} else if len(s_time) == 0 && len(e_time) > 0 {
				where = where + "add_time <= ?"
				whereparam = append(whereparam, e_time)
			}
		}
	}

	var bb, vb, wb float64
	statement := "SELECT sum(betting_amount) as bb,sum(valid_amount) as vb,sum(win_lose_amount) as wb FROM `og_bet_record` WHERE " + where
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
	/*
		stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
		etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
		d, _ := time.ParseDuration("+12h")
		stime = stime.Add(d)
		etime = etime.Add(d)
		s_time = stime.Format("2006-01-02 15:04:05")
		e_time = etime.Format("2006-01-02 15:04:05")
	*/
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
		where = where + "pkusername in(" + strings.Trim(strings.Repeat("?,", len(users)), ",") + ")"
		for _, v := range users {
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
	statement := "SELECT count(*) as times, sum(betting_amount) as all_bb FROM `og_bet_record` WHERE " + where
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
	where += "result_type in (1,2)" //下面计算有效的
	statement = "SELECT count(*) as vtimes, sum(betting_amount) as bb,sum(valid_amount) as vb,sum(win_lose_amount) as wb FROM `og_bet_record` WHERE " + where
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

	//s_time, _ = common.Changetimezone(s_time, "America/New_York", "Asia/Shanghai") //stime.Format("2006-01-02 15:04:05")
	//e_time, _ = common.Changetimezone(e_time, "America/New_York", "Asia/Shanghai") //etime.Format("2006-01-02 15:04:05")

	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+12h")
	stime = stime.Add(d)
	etime = etime.Add(d)
	s_time = stime.Format("2006-01-02 15:04:05")
	e_time = etime.Format("2006-01-02 15:04:05")

	var bb, wb, vb float64
	var times int

	where := "result_type in (1,2)"
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
			where = where + "add_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "add_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "add_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, sum(betting_amount) as bb,sum(valid_amount) as vb,sum(win_lose_amount) as wb FROM `og_bet_record` WHERE " + where
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

//根据站点获取
func GetAvailableAmountBySiteids(siteid, s_time, e_time string) ([]map[string][]byte, error) {

	//s_time, _ = common.Changetimezone(s_time, "America/New_York", "Asia/Shanghai") //stime.Format("2006-01-02 15:04:05")
	//e_time, _ = common.Changetimezone(e_time, "America/New_York", "Asia/Shanghai") //etime.Format("2006-01-02 15:04:05")
	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+12h")
	stime = stime.Add(d)
	etime = etime.Add(d)
	s_time = stime.Format("2006-01-02 15:04:05")
	e_time = etime.Format("2006-01-02 15:04:05")

	where := "result_type in (1,2)"
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
			where = where + "add_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "add_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "add_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times,site_id, sum(betting_amount) as bb,sum(valid_amount) as vb,sum(win_lose_amount) as wb FROM `og_bet_record` WHERE "
	statement += where + " group by site_id"
	rows1, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}
	return rows1, nil
}

//根据站点获取
func GetAvailableAmountByAgentid(agentid int64, siteid, s_time, e_time string) (int, float64, float64, float64, error) {

	//s_time, _ = common.Changetimezone(s_time, "America/New_York", "Asia/Shanghai") //stime.Format("2006-01-02 15:04:05")
	//e_time, _ = common.Changetimezone(e_time, "America/New_York", "Asia/Shanghai") //etime.Format("2006-01-02 15:04:05")
	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+12h")
	stime = stime.Add(d)
	etime = etime.Add(d)
	s_time = stime.Format("2006-01-02 15:04:05")
	e_time = etime.Format("2006-01-02 15:04:05")

	var bb, wb, vb float64
	var times int

	where := "result_type in (1,2) AND site_id = ?"
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
			where = where + "add_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "add_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "add_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times,  sum(betting_amount) as bb,sum(valid_amount) as vb,sum(win_lose_amount) as wb FROM `og_bet_record` WHERE " + where
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
//notbrokerage bool是否去除免佣的注单的统计
func GetUserAvailableAmountByUser(username, siteid, s_time, e_time string, isdz, notbrokerage bool) ([]map[string][]byte, error) {
	/*
		stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
		etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
		d, _ := time.ParseDuration("+12h")
		stime = stime.Add(d)
		etime = etime.Add(d)
		s_time = stime.Format("2006-01-02 15:04:05")
		e_time = etime.Format("2006-01-02 15:04:05")
	*/
	if isdz {
		return nil, m.NodianziErr
	}
	where := "result_type in (1,2) AND site_id = ?"
	whereparam := make([]interface{}, 0)
	whereparam = append(whereparam, siteid)
	if len(username) > 0 {
		if len(where) > 0 {
			where = where + " AND "
		}
		users := strings.Split(username, "|")
		if len(users) > 0 {
			where = where + "pkusername in(" + strings.Trim(strings.Repeat("?,", len(users)), ",") + ")"
			for _, v := range users {
				whereparam = append(whereparam, v)
			}
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
	statement := "SELECT count(*) as times, pkusername as account_number,sum(betting_amount) as bb,sum(valid_amount) as vb,sum(win_lose_amount) as wb FROM `og_bet_record` WHERE " + where + " group by user_name"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}

	return rows, err
}

//根据站点获取
func GetUserAvailableAmountBySiteid(siteid string, isdz bool, s_time, e_time string) ([]map[string][]byte, error) {

	//s_time, _ = common.Changetimezone(s_time, "America/New_York", "Asia/Shanghai") //stime.Format("2006-01-02 15:04:05")
	//e_time, _ = common.Changetimezone(e_time, "America/New_York", "Asia/Shanghai") //etime.Format("2006-01-02 15:04:05")
	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+12h")
	stime = stime.Add(d)
	etime = etime.Add(d)
	s_time = stime.Format("2006-01-02 15:04:05")
	e_time = etime.Format("2006-01-02 15:04:05")

	if isdz {
		return nil, m.NodianziErr
	}
	where := "result_type in (1,2)"
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
			where = where + "add_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "add_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "add_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, pkusername as account_number,sum(betting_amount) as bb,sum(valid_amount) as vb,sum(win_lose_amount) as wb FROM `og_bet_record` WHERE "
	statement += where + " group by user_name"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}

	return rows, err
}

//根据站点获取
func GetUserAvailableAmountByAgentid(agentid string, isdz bool, siteid, s_time, e_time string) ([]map[string][]byte, error) {

	//s_time, _ = common.Changetimezone(s_time, "America/New_York", "Asia/Shanghai") //stime.Format("2006-01-02 15:04:05")
	//e_time, _ = common.Changetimezone(e_time, "America/New_York", "Asia/Shanghai") //etime.Format("2006-01-02 15:04:05")
	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+12h")
	stime = stime.Add(d)
	etime = etime.Add(d)
	s_time = stime.Format("2006-01-02 15:04:05")
	e_time = etime.Format("2006-01-02 15:04:05")

	if isdz {
		return nil, m.NodianziErr
	}
	where := "result_type in (1,2)"
	whereparam := make([]interface{}, 0)

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
			where = where + "add_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "add_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "add_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, pkusername as account_number,sum(betting_amount) as bb,sum(valid_amount) as vb,sum(win_lose_amount) as wb FROM `og_bet_record` WHERE "
	statement += where + " group by user_name"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}

	return rows, err
}

//根据站点获取
func GetAgentAvailableAmountByAgentid(agentid string, isdz bool, siteid, s_time, e_time string) ([]map[string][]byte, error) {

	//s_time, _ = common.Changetimezone(s_time, "America/New_York", "Asia/Shanghai") //stime.Format("2006-01-02 15:04:05")
	//e_time, _ = common.Changetimezone(e_time, "America/New_York", "Asia/Shanghai") //etime.Format("2006-01-02 15:04:05")
	stime, _ := time.Parse("2006-01-02 15:04:05", s_time)
	etime, _ := time.Parse("2006-01-02 15:04:05", e_time)
	d, _ := time.ParseDuration("+12h")
	stime = stime.Add(d)
	etime = etime.Add(d)
	s_time = stime.Format("2006-01-02 15:04:05")
	e_time = etime.Format("2006-01-02 15:04:05")

	if isdz {
		return nil, m.NodianziErr
	}
	where := "og_bet_record.result_type in (1,2)"
	whereparam := make([]interface{}, 0)

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
			where = where + "add_time BETWEEN ? AND ?"
			whereparam = append(whereparam, s_time)
			whereparam = append(whereparam, e_time)
		} else if len(s_time) > 0 && len(e_time) == 0 {
			where = where + "add_time >= ?"
			whereparam = append(whereparam, s_time)
		} else if len(s_time) == 0 && len(e_time) > 0 {
			where = where + "add_time <= ?"
			whereparam = append(whereparam, e_time)
		}
	}
	statement := "SELECT count(*) as times, agent_id ,sum(betting_amount) as bb,sum(valid_amount) as vb,sum(win_lose_amount) as wb FROM `og_bet_record`  " +
	" WHERE " + where + " group by agent_id"
	rows, err := m.Orm.Query(statement, whereparam...)
	if err != nil {
		return nil, err
	}

	return rows, err
}

//更新注单表中没有代理id和用户名的数据
func OG_UpateBetAgentUsername() error {
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
				m.Orm.Table(new(BetRecord)).Where("user_name = ?", v.GUserName).Update(map[string]interface{}{"agent_id": v.AgentId, "pkusername": v.UserName, "site_id": v.SiteId})
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
func OG_UpateBetAgentUsername() error {
	pagesize := 100
	i := 0
	hasuser := true
	for {
		if !hasuser {
			break
		}
		bets := make([]BetRecord, 0)
		err := m.Orm.Cols("user_name").Limit(pagesize, i*pagesize).Where("agent_id = 0 OR pkusername = ''").GroupBy("user_name").Find(&bets)
		if err != nil {
			return err
		}
		fmt.Println("og---------------",pagesize, i*pagesize)
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
			common.Log.Err("ag GetUserByGname:", err)
		} else {
			for _, v := range users {
				m.Orm.Table(new(BetRecord)).Where("user_name = ?", v.GUserName).Update(map[string]interface{}{"agent_id": v.AgentId, "pkusername": v.UserName, "site_id": v.SiteId})
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
