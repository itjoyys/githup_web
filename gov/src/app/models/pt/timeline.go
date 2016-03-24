package pt

import (
	m "app/models"
	//	"fmt"
	"time"

	"common"
	"errors"
)

type Timeline struct {
	ID        int       `xorm:"pk 'id'"`
	Startdate time.Time `xorm:"'startdate'"` // lastrecordtime
	Status    int       `xorm:"'status'"`
}

func (t *Timeline) TableName() string {
	return "pt_timeline"
}

//获取时间
func GetTimeline() (time.Time, error) {

	tl := new(Timeline)
	has, err := m.Orm.Where("id = ? AND status = ?", 1, 1).Get(tl)
	if err != nil {
		return time.Now(), errors.New("SQL ERROE")
	}
	if !has {
		return time.Now(), errors.New("NO HAS")
	}

	return tl.Startdate, nil
}

func StopTimeline() {
	sql := "update pt_timeline set startdate = ?,status=0 where id = 1"

	_, err := m.Orm.Exec(sql, time.Now().Format("2006-01-02 15:04:05"))
	if err != nil {
		common.Log.Err("Update PT BetRecord :", err)
	}
}

func UpdateTimeline(lasttime time.Time) {

	sql := "update pt_timeline set startdate = ? ,status=1 where id = 1"

	_, err := m.Orm.Exec(sql, lasttime.Format("2006-01-02 15:04:05"))
	if err != nil {
		common.Log.Err("Update PT BetRecord :", err, lasttime)
	}
}
