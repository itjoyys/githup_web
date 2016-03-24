package bbin

import (
	m "app/models"
	"fmt"
	"time"

	"common"
)

type Timeline struct {
	ID                int       `xorm:"pk 'id'"`
	LastRecordTme     time.Time `xorm:"'lastrecordtime'"`    // lastrecordtime
	LastModifiedDate3 time.Time `xorm:"'lastmodifieddate3'"` //lastmodifieddate3
	//Site_id           string    `xorm:"'site_id'"`           //lastmodifieddate3
}

func (t *Timeline) TableName() string {
	return "bbin_timeline"
}

//获取时间
func GetTimeline() (time.Time, time.Time) {

	tl := new(Timeline)
	_, err := m.Orm.Where("id = ?", 1).Get(tl)
	if err != nil {
		//昨天
		k := time.Now()
		d, _ := time.ParseDuration("-24h")
		fromdate := fmt.Sprintf("%d-%02d-%02dT00:00:00", k.Add(d).Year(), k.Add(d).Month(), k.Add(d).Day())
		time3, _ := time.Parse("2006-01-02T15:04:05", fromdate)
		return time3, time3
	}

	//return tl.LastRecordTme.Format("2006-01-02T15:04:05"), tl.LastModifiedDate3.Format("2006-01-02T15:04:05")
	return tl.LastRecordTme, tl.LastModifiedDate3
}

func UpdateTimeline(lasttime time.Time) {

	sql := "update bbin_timeline set lastrecordtime = ? where id = 1"

	_, err := m.Orm.Exec(sql, lasttime.Format("2006-01-02 15:04:05"))
	if err != nil {
		common.Log.Err("Update MG BetRecord :", err, lasttime)
	}
}

func UpdateModifiedTimeline(lasttime time.Time) {
	sql := "update bbin_timeline set lastmodifieddate3 = ? where id = 1"

	_, err := m.Orm.Exec(sql, lasttime.Format("2006-01-02 15:04:05"))
	if err != nil {
		common.Log.Err("Update MG BetRecord :", err, lasttime)
	}
}
