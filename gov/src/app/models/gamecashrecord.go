package models

import (
	"time"
)

/**
 * 本地的现金交易记录表
 */
type GameCashRecord struct {
	TradeID       string    `xorm:"pk 'trade_id'"` //`db:"PK" sql:"trade_id" tname:"game_cash_record"`
	TransactionId string    `xorm:"'transaction_id'"`
	SiteID        string    `xorm:"'site_id'"`     //`sql:"site_id"`
	UserName      string    `xorm:"'username'"`    //`sql:"username"`
	Platform      string    `xorm:"'platform'"`    //`sql:"platform"`
	CashType      int       `xorm:"'cash_type'"`   //`sql:"cash_type"`
	Credit        float64   `xorm:"'credit'"`      //`sql:"credit"`
	CreateTime    time.Time `xorm:"'create_time'"` //`sql:"create_time"`
	UpdateTime    time.Time `xorm:"'update_time'"` //`sql:"update_time"`
	Status        int       `xorm:"'status'"`      // `sql:"status"` //0转账失败，转账成功
	Info          string    `xorm:"'info'"`
}

func (a *GameCashRecord) TableName() string {
	return "game_cash_record"
}

//插入
func CreateGameCashRecord(cashrecord *GameCashRecord) error {

	cashrecord.CreateTime = time.Now()
	cashrecord.UpdateTime = time.Now()
	//cashrecord.Status = 0
	_, err := Orm.Insert(cashrecord)
	return err
}

//更新
func UpdateGameCashRecord(cashrecord *GameCashRecord) error {
	cashrecord.UpdateTime = time.Now()

	_, err := Orm.Id(cashrecord.TradeID).Update(cashrecord)

	return err
}
