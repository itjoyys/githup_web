package bbin

import "app/models/bbin"

//{"result":false,"data":{"Code":"44900","Message":"IP is not accepted."}}
//一般返回json格式
type CommonResult struct {
	Result bool       `json:"result"`
	Data   CommonData `json:"data"`
}

//常规数据
//适用于 CreateMember,Logout,Transfer,
type CommonData struct {
	Code    string `json:"Code"`
	Message string `json:"Message"`
}

//CheckUsrBalance方法的的返回数据
/*{
	"result":"回传结果(true or false)",
	"data":{
		"LoginName":"帐号",
		"Currency":"币别",
		"Balance":"额度",
		"TotalBalance":"总额度"
		},
	"pagination":{
		"Page":"页数",
		"PageLimit":"每页数量",
		"TotalNumber":"总数",
		"TotalPage":"总页数"
		}
}*/
type CheckUsrBalanceResult struct {
	Result     bool                  `json:"result"`
	Data       []CheckUsrBalanceData `json:"data"`
	Pagination Pagination            `json:"pagination"`
}

type CheckUsrBalanceData struct {
	LoginName    string  `json:"LoginName"`
	Currency     string  `json:"Currency"`
	Balance      float64 `json:"Balance"`
	TotalBalance float64 `json:"TotalBalance"`
}

type Pagination struct {
	Page        interface{} `json:"Page"`
	PageLimit   interface{} `json:"PageLimit"`
	TotalNumber interface{} `json:"TotalNumber"`
	TotalPage   interface{} `json:"TotalPage"`
}

//"Page":0,"PageLimit":500,"TotalNumber":"0","TotalPage":0}
//{"result":true,"data":[],"pagination":{"Page":"1","PageLimit":"500","TotalNumber":0,"TotalPage":0}}

//查询会员存提是否成功
/*{
"result":"回传结果(true or false)",
"data":{"TransID":"转帐序号","TransType":"转帐型态(入/出)","Status":"状态"}
}*/
type CheckTransferResult struct {
	Result bool              `json:"result"`
	Data   CheckTransferData `json:"data"`
}

type CheckTransferData struct {
	TransID   string `json:"TransID"`
	TransType string `json:"TransType"`
	Status    int    `json:"Status"`
}

/*{
"result":"回传结果(true or false)",
"data":{
"UserName":"帐号",
"CreateTime":"建立时间",
"TransType":"转帐型态(入/出)",
"Amount":"转帐金额",
"Balance":"额度",
"Currency":"币别",
"TransID":"转帐序号"
},
"pagination":{
"Page":"页数",
"PageLimit":"每页数量",
"TotalNumber":"总数",
"TotalPage":"总页数"}
}*/
//查询会员存提记录
type TransferRecordResult struct {
	Result     bool               `json:"result"`
	Data       TransferRecordData `json:"data"`
	Pagination Pagination         `json:"pagination"`
}

type TransferRecordData struct {
	UserName   string  `json:"UserName"`
	CreateTime string  `json:"CreateTime"`
	TransType  string  `json:"TransType"`
	Amount     float64 `json:"Amount"`
	Balance    float64 `json:"Balance"`
	Currency   string  `json:"Currency"`
	TransID    string  `json:"TransID"`
}

//查询下注记录
/*{
"result":"回传结果(true or false)",
"data":{
"UserName":"帐号",
"WagersID":"注单号码",
"WagersDate":"下注时间",
"GameType":"游戏种类",
"Result":"开牌结果",
"BetAmount":"下注金额",
"Payoff":"派彩金额",
"Currency":"币别",
"Commissionable":"会员有效投注额"
},
"pagination":{"Page":"页数","PageLimit":"每页数量","TotalNumber":"总数","TotalPage":"总页数"
}*/
//游戏下注记录 - 下注记录(注单变更时间 方法BetRecord，BetRecordByModifiedDate3使用
type BetRecordResult struct {
	Result     bool                            `json:"result"`
	Data       []bbin.BbinBetRecord_form_Jsion `json:"data"`
	Pagination Pagination                      `json:"pagination"`
}

type GetJPHistoryResult struct {
	Result     bool             `json:"result"`
	Data       GetJPHistoryData `json:"data"`
	Pagination Pagination       `json:"pagination"`
}

type GetJPHistoryData struct {
	WagersID   string  `json:"WagersID"`
	JPTypeID   string  `json:"JPTypeID"`
	UserName   string  `json:"UserName"`
	WagersDate string  `json:"WagersDate"`
	JPAmount   float64 `json:"JPAmount"`
}
