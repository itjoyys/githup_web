package og

import (
	m "app/models"
	"common"

	"fmt"
	"strings"
)

type CashRecord struct {
	Id         int64     `xorm:"'id'"`
	VendorId    int64 `xorm:"pk 'vendor_id'"`
	UserName    string `xorm:"'user_name'"`    //`sql:"user_name"`
	OrderNumber string `xorm:"'order_number'"` //`sql:"order_number"`
	TradeAmount string `xorm:"'trade_amount'"` //`sql:"trade_amount"`
	Balance     string `xorm:"'balance'"`      //`sql:"balance"`
	TradeType   string `xorm:"'trade_type'"`   //`sql:"trade_type"`
	From        string `xorm:"'from'"`         //`sql:"from"`
	To          string `xorm:"'to'"`           //`sql:"to"`
	AddTime     string `xorm:"'user_name'"`    //`sql:"user_name"`
}

func (a *CashRecord) TableName() string {
	return "og_cash_record"
}

func GetLastCashRecordVid(siteid string) (int64, error) {
	cr := new(CashRecord)
	has, err := m.Orm.Cols("vendor_id").Where("site_id = ?",siteid) .OrderBy("vendor_id DESC").Get(cr)
	if err != nil {
		common.Log.Err("GetLastCashRecordVid SQL err:", err)
		return 0, err
	}
	if !has {
		return 1, nil
	}
	return cr.VendorId, nil
}

func CreateCashRecord(ogbet *CashRecord) error {
	_, err := m.Orm.Insert(ogbet)
	return err
}

func InsertBatchCashRecord(rows []map[string]interface{},siteid string) error {
	//创建insert语句
	for _, properties := range rows {
		var keys []string
		var placeholders []string
		var args []interface{}
		for key, val := range properties {
			keys = append(keys, key)
			placeholders = append(placeholders, "?")
			args = append(args, val)
		}
		
		keys = append(keys, "site_id")
		placeholders = append(placeholders, "?")
		args = append(args, siteid)

		ss := fmt.Sprintf("%v,%v", "`", "`")
		statement := fmt.Sprintf("INSERT INTO %v%v%v (%v%v%v) VALUES (%v)",
			"`",
			"og_cash_record",
			"`",
			"`",
			strings.Join(keys, ss),
			"`",
			strings.Join(placeholders, ", "))
		//fmt.Println(statement)
		_, err := m.Orm.Exec(statement, args...)
		if err != nil {
			common.Log.Err("insert og CashRecord :", err, statement, args)
		}
	}

	return nil
}
