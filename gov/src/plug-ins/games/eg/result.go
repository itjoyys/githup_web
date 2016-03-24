package eg

//{”resultCode”:1}
type ComResult struct {
	ResultCode int `json:"resultCode"`
}

type BetRecordResult struct {
	ResultCode int         `json:"resultCode"`
	BetList    []BetRecord `json:"betList,,omitempty"`
}

/*
{"id":1,
	"orderNo":"0d95484e-dd7c-43ce-b88a-fabbae1a0167",
	"betTime":"2015-11-04 17:10:55",
	"userNo":"1001",
	"agentNo":"a1002",
	"betNo":"12,10,8,4,9,13,14,5,7,12,14,11,14,3,12",
	"betNo2":"",
	"betMoney":0.01,
	"winMoney":0,
	"resultMoney":-0.01,
	"gameId":5,
	"restNum":163108.91,
	"odds":"",
	"ip":"110.84.2.19"}*/
type BetRecord struct {
	Id          int64   `json:"id"`
	OrderNo     string  `json:"orderNo"`
	BetTime     string  `json:"betTime"`
	UserNo      string  `json:"userNo"`
	AgentNo     string  `json:"agentNo"`
	BetNo       string  `json:"betNo"`
	BetNo2      string  `json:"betNo2"`
	BetMoney    float64  `json:"betMoney"`
	SureMoney   float64  `json:"sureMoney"`
	WinMoney    float64 `json:"winMoney"`
	ResultMoney float64 `json:"resultMoney"`
	GameId      int64   `json:"gameId"`
	RestNum     float64 `json:"restNum"`
	Odds        string `json:"odds"`
	Ip          string  `json:"ip"`
}
