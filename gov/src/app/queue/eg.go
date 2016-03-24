package queue

import (
//"plug-ins/games/eg"
	"common"
	"app/models/eg"
	"core/xorm"
	"core/xorm/core"
	"utility"
)

var egdb    *xorm.Engine

func initdb() error {
	source := common.Cfg.Section("localdz").Key("DBSRC").MustString("")
	var err error
	egdb, err = xorm.NewEngine("mysql", source)
	//username+":"+password+"@tcp("+dbHost+")/"+dbName+"?charset=utf8&interpolateParams=true")
	if err != nil {
		common.Log.Err("init egdberror ", err)
		return err
	}
	egdb.SetMapper(core.GonicMapper{})
	egdb.ShowSQL = true
	return nil
}



//从数据库获取
func Eg_GetBetRecord() {
	if egdb == nil {
		err := initdb()
		if err != nil {
			return
		}
	}
	if err := egdb.Ping(); err != nil {
		err = initdb()
		if err != nil {
			return
		}
	}
	//查询数据
	lastvid, err := eg.GetLastBetRecorVid()
	if err != nil {
		common.Log.Err("get eg record error :", err)
		return
	}
	rds := make([]eg.EG_record, 0)
	err = egdb.Where("id > " + utility.ToStr(lastvid)).Limit(1000, 0).Find(&rds)
	if err != nil {
		common.Log.Err("get eg record error :", err)
		return
	}
	for _, v := range rds {
		eg.CreateBetRecord(&v)
	}
}
