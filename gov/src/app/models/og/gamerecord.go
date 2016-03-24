package og

import (
	m "app/models"
	 "common"

	"fmt"
	"strings"
)

type GameRecord struct {
	ProductID       string `xorm:"pk 'product_id'"`
	TableID         string `xorm:"'table_id'"`         //`sql:"table_id"`
	Stage           string `xorm:"'stage'"`            //`sql:"stage"`
	Inning          string `xorm:"'inning'"`           //`sql:"inning"`
	GameResult      string `xorm:"'game_result'"`      //`sql:"game_result"`
	GameInformation string `xorm:"'game_information'"` //`sql:"game_information"`
	GameNameID      string `xorm:"'game_name_id'"`     //`sql:"game_name_id"`
	AddTime         string `xorm:"'add_time'"`         //`sql:"add_time"`
}

func (a *GameRecord) TableName() string {
	return "og_game_record"
}

func CreateGameRecord(ogbet *GameRecord) error {
	_, err := m.Orm.Insert(ogbet)
	return err
}

func InsertBatchGameRecord(rows []map[string]interface{}) error {
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

		ss := fmt.Sprintf("%v,%v", "`", "`")
		statement := fmt.Sprintf("INSERT INTO %v%v%v (%v%v%v) VALUES (%v)",
			"`",
			"og_game_record",
			"`",
			"`",
			strings.Join(keys, ss),
			"`",
			strings.Join(placeholders, ", "))
		//fmt.Println(statement)
		_, err := m.Orm.Exec(statement,args...)
		if err != nil {
			common.Log.Err("Insert OG GameRecord :", err,statement,args)
		}
	}

	return nil
}
