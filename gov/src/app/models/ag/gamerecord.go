package ag

import (
	m "app/models"
	"common"

	"fmt"
	"strings"
)

type GameRecord struct {
	Id           int64     `xorm:"'id'" xml:"-"`
	Gmcode       string `xorm:"pk 'gmcode'" xml:"gmcode,attr"`
	Tablecode    string `xorm:"'tablecode'" xml:"tablecode,attr"`
	Begintime    string `xorm:"'begintime'" xml:"begintime,attr"`
	Closetime    string `xorm:"'closetime'" xml:"closetime,attr"`
	Dealer       string `xorm:"'dealer'" xml:"dealer,attr"`
	Shoecode     string `xorm:"'shoecode'" xml:"shoecode,attr"`
	Flag         string `xorm:"'flag'" xml:"flag,attr"`
	Bankerpoint  string `xorm:"'bankerpoint'" xml:"bankerpoint,attr"`
	Playerpoint  string `xorm:"'playerpoint'" xml:"playerpoint,attr"`
	Cardnum      string `xorm:"'cardnum'" xml:"cardnum,attr"`
	Pair         string `xorm:"'pair'" xml:"pair,attr"`
	Gametype     string `xorm:"'gametype'" xml:"gametype,attr"`
	Dragonpoint  string `xorm:"'dragonpoint'" xml:"dragonpoint,attr"`
	Tigerpoint   string `xorm:"'tigerpoint'" xml:"tigerpoint,attr"`
	Cardlist     string `xorm:"'cardlist'" xml:"cardlist,attr"`
	Vid          string `xorm:"'vid'" xml:"vid,attr"`
	Platformtype string `xorm:"'platformtype'" xml:"platformtype,attr"`
}

func (a *GameRecord) TableName() string {
	return "ag_game_record"
}

func CreateGameRecord(ogbet *GameRecord) error {
	_, err := m.Orm.Insert(ogbet)
	if err != nil {
		_, err = m.Orm.Id(ogbet.Gmcode).Update(ogbet)
	}
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
		_, err := m.Orm.Exec(statement, args...)
		if err != nil {
			common.Log.Err("Insert OG GameRecord :", err, statement, args)
		}
	}

	return nil
}
