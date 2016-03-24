package ag

import (
	m "app/models"
	"common"

	"fmt"
	"strings"
)

//row dataType="HTR"
//ID="H_56826ce93d9308aa47411cf1"
//agentCode="" transferId=""
//tradeNo="56826ce93d9308aa47411cf1"
//platformType="HUNTER"
//playerName="aaewanglin888"
//transferType="3"
//transferAmount="-1.0599999999999419"
//previousAmount="1.0599999999999419"
//currentAmount="0" currency="CNY"
//exchangeRate="1" IP="" flag="0"
//creationTime="2015-12-29 07:22:18"
//remark="0" gameCode="" />
//<row dataType="TR" ID="2BC7D906C6AA7038E050C770796171C3" agentCode="391001001001001" transferId="0214235309207020"
// tradeNo="160214890437974" platformType="AGIN" playerName="nunmiday98" transferType="IN" transferAmount="909" previousAmount="0.45"
// currentAmount="909.45" currency="CNY" exchangeRate="1" IP="0" flag="0" creationTime="2016-02-14 23:53:09" gameCode="" />
//<row dataType="HTR" ID="H_56de69e2321e4acb04f84755" agentCode="" transferId="" tradeNo="56de69e2321e4acb04f84755"
// platformType="HUNTER" playerName="aadpingping" transferType="3" transferAmount="35" previousAmount="0.79" currentAmount="35.79"
// currency="CNY" exchangeRate="1" IP="" flag="0" creationTime="2016-03-08 01:57:54" remark="0" gameCode="" />

//sceneId type
type CashRecord struct {
	Aid            int64  `xorm:"'aid'" xml:"-"`
	ID             string `xorm:"pk 'id'" xml:"ID,attr"`
	AgentCode      int64 `xorm:"'agent_code'" xml:"agentCode,attr"`
	TransferId     string `xorm:"'transfer_id'" xml:"transferId,attr"`
	TradeNo        string `xorm:"'trade_no'" xml:"tradeNo,attr"`
	PlatformType   string `xorm:"'platform_type'" xml:"platformType,attr"`
	PlayerName     string `xorm:"'player_name'" xml:"playerName,attr"`
	TransferType   string `xorm:"'transfer_type'" xml:"transferType,attr"`
	TransferAmount string `xorm:"'transfer_amount'" xml:"transferAmount,attr"`
	PreviousAmount string `xorm:"'previous_amount'" xml:"previousAmount,attr"`
	CurrentAmount  string `xorm:"'current_amount'" xml:"currentAmount,attr"`
	Currency       string `xorm:"'currency'" xml:"currency,attr"`
	ExchangeRate   string `xorm:"'exchange_rate'" xml:"exchangeRate,attr"`
	IP             string `xorm:"'ip'" xml:"IP,attr"`
	Flag           string `xorm:"'flag'" xml:"flag,attr"`
	Remark         string `xorm:"'remark'" xml:"remark,attr,omitempty"`
	CreationTime   string `xorm:"'creation_time'" xml:"creationTime,attr"`
}

func (a *CashRecord) TableName() string {
	return "ag_cash_record"
}

func CreateCashRecord(ogbet *CashRecord) error {
	_, err := m.Orm.Insert(ogbet)
	if err != nil {
		m.Orm.Where("id = ?", ogbet.ID).Update(ogbet)
	}
	return err
}


//<row dataType="HSR" ID="H_56de583f321e4acb04f4cea8" tradeNo="56de583f321e4acb04f4cea8" platformType="HUNTER"
// sceneId="51457411807" playerName="aadpingping" type="7" SceneStartTime="2016-03-08 00:42:40" SceneEndTime="2016-03-08 00:42:40"
// Roomid="10-105" Roombet="10" Cost="0" Earn="0" Jackpotcomm="0" transferAmount="22.72" previousAmount="25" currentAmount="47.72"
// currency="CNY" exchangeRate="1" IP="" flag="0" creationTime="2016-03-08 00:42:40" gameCode="" />
type CashRecordH struct {
	Aid            int64  `xorm:"'aid'" xml:"-"`
	ID             string `xorm:"pk 'id'" xml:"ID,attr"`
	TradeNo        string `xorm:"'trade_no'" xml:"tradeNo,attr"`
	PlatformType   string `xorm:"'platform_type'" xml:"platformType,attr"`
	SceneId        string `xorm:"'scene_id'" xml:"sceneId,attr"`
	PlayerName     string `xorm:"'player_name'" xml:"playerName,attr"`
	TransferType   string `xorm:"'transfer_type'" xml:"type,attr"`
	SceneStartTime string `xorm:"'scene_starttime'" xml:"SceneStartTime,attr"`
	SceneEndTime   string `xorm:"'scene_endtime'" xml:"SceneEndTime,attr"`
	Roomid         string `xorm:"'room_id'" xml:"Roomid,attr"`
	Roombet        string `xorm:"'room_bet'" xml:"Roombet,attr"`
	Cost           string `xorm:"'cost'" xml:"Cost,attr"`
	Earn           string `xorm:"'earn'" xml:"Earn,attr"`
	Jackpotcomm    string `xorm:"'jackpotcomm'" xml:"Jackpotcomm,attr"`
	TransferAmount string `xorm:"'transfer_amount'" xml:"transferAmount,attr"`
	PreviousAmount string `xorm:"'previous_amount'" xml:"previousAmount,attr"`
	CurrentAmount  string `xorm:"'current_amount'" xml:"currentAmount,attr"`
	Currency       string `xorm:"'currency'" xml:"currency,attr"`
	ExchangeRate   string `xorm:"'exchange_rate'" xml:"exchangeRate,attr"`
	IP             string `xorm:"'ip'" xml:"IP,attr"`
	Flag           string `xorm:"'flag'" xml:"flag,attr"`
	GameCode       string `xorm:"'game_code'" xml:"gameCode,attr"`
	CreationTime   string `xorm:"'creation_time'" xml:"creationTime,attr"`
}

func (a *CashRecordH) TableName() string {
	return "ag_cash_recordh"
}

func CreateCashRecordH(ogbet *CashRecordH) error {
	_, err := m.Orm.Insert(ogbet)
	if err != nil {
		_, err := m.Orm.Where("id = ?", ogbet.ID).Update(ogbet)
		return err
	}
	return err
}

func InsertBatchCashRecord(rows []map[string]interface{}) error {
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
			"ag_cash_record",
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
