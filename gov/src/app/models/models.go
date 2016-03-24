package models

import (
	"errors"
	_ "github.com/go-sql-driver/mysql"

	"common"
	"core/redis"
	"core/webcore"
	"core/xorm"
	"core/xorm/core"
	"fmt"
	"strings"
	_ "time"
)

var (
	Orm         *xorm.Engine
	Redisclient *redis.Client
	//0 ag 1 bbin 2 ct 3 lebo 4 mg 5 og
	Redis_user_error [6]int
)

type DataTableJson struct {
	Echo                string      `json:"sEcho"`
	TotalRecords        int64       `json:"iTotalRecords"`
	TotalDisplayRecords int64       `json:"iTotalDisplayRecords"`
	Data                interface{} `json:"aaData"`
}

var (
	ErrNotExist = errors.New("not exist")
	ParamErr    = errors.New("param err")
	NodianziErr = errors.New("no have dianzi err")
)

//创建bet库

//数据库引擎
func NewEngine() {
	username := common.Cfg.Section("db").Key("USERNAME").MustString("root")
	password := common.Cfg.Section("db").Key("PASSWORD").MustString("123")
	dbName := common.Cfg.Section("db").Key("DB_NAME").MustString("lunwensl_go")
	dbHost := common.Cfg.Section("db").Key("DB_HOST").MustString("localhost:3306")
	var err error
	Orm, err = xorm.NewEngine("mysql", username+":"+password+"@tcp("+dbHost+")/"+dbName+"?charset=utf8&interpolateParams=true")
	if err != nil {
		panic(err)
	}
	if webcore.Env == webcore.DEV {
		Orm.ShowSQL = true
	}
	Orm.SetMapper(core.GonicMapper{})
	//开启redis
	redis_host := common.Cfg.Section("redis").Key("ADDR").MustString(":6379")
	redis_password := common.Cfg.Section("redis").Key("PASSWORD").MustString("")
	if len(redis_password) > 0 {
		Redisclient = redis.NewTCPClient(&redis.Options{
			Addr:     redis_host,
			Password: redis_password,
			DB:       1,
		})
	} else {
		Redisclient = redis.NewTCPClient(&redis.Options{
			Addr: redis_host,
			DB:   1,
		})
	}
	err = Redisclient.Ping().Err()
	if err != nil {
		panic(err)
	}
	//client.FlushAll()//删除所有
	//Orm = sqlengine.New(db)
	//sqlengine.OnDebug = true
}

///分表使用
type tablename struct {
	Name string `xorm:"'table_name'"`
}

func GetBetTableNames(pre string) ([]string, error) {
	names := make([]string, 0)
	sxgames := common.Cfg.Section("db").Key("DB_NAME").MustString("sxgames")
	session := Orm.Sql("select table_name from information_schema.tables "+
		"where table_name like ? AND table_schema=? and table_type='base table'", pre+"_%", sxgames)
	tables := make([]tablename, 0)
	err := session.Find(&tables)
	if err != nil {
		return []string{}, err
	}
	for _, v := range tables {
		names = append(names, v.Name)
	}
	return names, nil
}

//根据日期创建表
func CreateAllBetTableByDate(datestr string) error {
	//当前日期
	//datestr = time.Now().Format("200601")
	bettables := []string{"ag", "bbin", "ct", "lebo", "mg", "og"}
	for _, v := range bettables {
		err := CreateBetTableByDate(datestr, v)
		if err != nil {
			return err
		}
	}
	return nil
}

func CreateBetTableByDate(datestr, gtype string) error {
	var sql_str, prefix string
	if gtype == "ag" {
		prefix = "ag_bet_record"
		sql_str = ag_bet_record_create
	} else if gtype == "bbin" {
		prefix = "bbin_bet_record"
		sql_str = bbin_bet_record_create
	} else if gtype == "ct" {
		prefix = "ct_bet_record"
		sql_str = ct_bet_record_create
	} else if gtype == "lebo" {
		prefix = "lebo_bet_record"
		sql_str = lebo_bet_record_create
	} else if gtype == "mg" {
		prefix = "mg_bet_record"
		sql_str = mg_bet_record_create
	} else if gtype == "og" {
		prefix = "og_bet_record"
		sql_str = og_bet_record_create
	}

	isexist, _ := Orm.IsTableExist(prefix + "_" + datestr)
	if !isexist {
		_, err := Orm.Exec("CREATE TABLE " + prefix + "_" + datestr + sql_str)
		if err != nil {
			common.Log.Err("CREATE TABLE "+prefix+"_"+datestr, err)
			return err
		}
		names, err := GetBetTableNames(prefix)
		if err != nil {
			common.Log.Err("CREATE TABLE "+prefix+"_"+datestr, err)
			return err
		}
		statement := fmt.Sprintf("ALTER TABLE %v%v%v UNION=(%v%v%v)",
			"`", prefix, "`", "`", strings.Join(names, "`,`"), "`")
		_, err = Orm.Exec(statement)
		if err != nil {
			common.Log.Err("ALTER TABLE "+prefix+"error:", err)
			return err
		}
		//ALTER TABLE `ag_bet_record` UNION=(`ag_bet_record_201508`,`ag_bet_record_201509`,`ag_bet_record_201507`);
	}
	return nil
}
