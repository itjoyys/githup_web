package ag

import (
	m "app/models"
	//	"fmt"
	"time"

	//"common"
	"errors"
)

type Xmlfile struct {
	ID               int64     `xorm:"pk 'id'"`
	Filenameid       int64     `xorm:"index 'filenameid'"`
	Lostfilenameid   int64     `xorm:"index 'lostfilenameid'"`
	Hunterfilenameid int64     `xorm:"index 'hunterfilenameid'"`
	CreateTime       time.Time `xorm:"'create_time'"` // lastrecordtime
}

func (t *Xmlfile) TableName() string {
	return "ag_xmlfile"
}

//获取最后文件
func GetLastFile(nowdate string) (int64, error) {

	xf := new(Xmlfile)
	has, err := m.Orm.Where("filenameid like ?", nowdate+"%").Limit(1, 4).Desc("filenameid").Get(xf)
	if err != nil {
		return 0, err
	}
	if !has {
		return 0, errors.New("NO HAS")
	}

	return xf.Filenameid, nil
}

//获取最后文件
func GetLastHunterFile(nowdate string) (int64, error) {

	xf := new(Xmlfile)
	has, err := m.Orm.Where("hunterfilenameid like ?", nowdate+"%").Limit(1, 4).Desc("hunterfilenameid").Get(xf)
	if err != nil {
		return 0, err
	}
	if !has {
		return 0, errors.New("NO HAS")
	}

	return xf.Filenameid, nil
}

//获取最后文件
func GetLastLostFile(nowdate string) (int64, error) {

	xf := new(Xmlfile)
	has, err := m.Orm.Where("filenameid like ?", nowdate+"%").Limit(1, 1).Desc("lostfilenameid").Get(xf)
	if err != nil {
		return 0, err
	}
	if !has {
		return 0, errors.New("NO HAS")
	}

	return xf.Lostfilenameid, nil
}

func CreateLog(id int64, lostid int64, hunterid int64) error {
	uxf := new(Xmlfile)
	uxf.Filenameid = id
	uxf.Lostfilenameid = lostid
	uxf.Hunterfilenameid = hunterid
	uxf.CreateTime = time.Now()

	_, err := m.Orm.InsertOne(uxf)

	return err
}
