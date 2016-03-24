package models

import (
	//"fmt"
	"time"

	_"errors"
)

type ManualQueue struct {
	ID              int       `xorm:"pk 'id'"`
	Vtype           string    `xorm:"'vtype'"`
	Cj_time         time.Time `xorm:"'cj_time'"`
	Queue_date      time.Time `xorm:"'queue_date'"`
	Queue_starttime time.Time `xorm:"'queue_starttime'"`
	Queue_endtime   time.Time `xorm:"'queue_endtime'"`
	Count           int       `xorm:"'count'"`
	Status          int       `xorm:"'status'"`
}
/*
CREATE TABLE `manual_queue` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`vtype` CHAR(20) NOT NULL DEFAULT '' COMMENT '视讯类型',
	`cj_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '采集的时间',
	`count` INT(11) NOT NULL DEFAULT '0' COMMENT '采集个数',
	`queue_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '采集的提交时间',
	`queue_starttime` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '采集的开始时间',
	`queue_endtime` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '采集结束时间',
	`status` INT(11) NOT NULL DEFAULT '0' COMMENT '采集状态0 等待中，1，采集中 2.完成',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `Index` (`vtype`, `cj_time`)
)
*/
func (t *ManualQueue) TableName() string {
	return "manual_queue"
}

//添加一个采集任务到队列
func AddQueue(gtype string, cjdate time.Time) error {
	mq := new(ManualQueue)
	mq.Status = 0
	mq.Vtype = gtype
	mq.Cj_time = cjdate
	mq.Queue_date = time.Now()

	_, err := Orm.Insert(mq)
	if err != nil {
		return err
	}
	return nil
}

//获取一个任务
func GetQueue(gtype string, queuetime time.Time) (ManualQueue, error) {
	mqs := ManualQueue{}
	_,err := Orm.Where("status = 0 AND vtype = ? AND cj_time = ?", gtype, queuetime.Format("2006-01-02 15:04:05")).Get(&mqs)

	if err != nil {
		return mqs, err
	}
	return mqs, nil
}

func UpdateQueueing(id int) error {
	mq := new(ManualQueue)
	mq.Status = 1
	mq.Queue_starttime = time.Now()
	_, err := Orm.Where("id = ?", id).Update(mq)
	if err != nil {
		return err
	}
	return nil
}

func UpdateQueueCount(id int) error {
	mq := new(ManualQueue)
	mq.Status = 1
	_, err := Orm.Where("id = ?", id).Update(map[string]interface{}{"status": 0, "count": "count + 1"})
	if err != nil {
		return err
	}
	return nil
}

func FinishQueue(id int) error {
	mq := new(ManualQueue)
	mq.Status = 2
	mq.Queue_endtime = time.Now()
	_, err := Orm.Where("id = ?", id).Update(mq)
	//统计采集条数
	if err != nil {
		return err
	}
	return nil
}
