package ag

import (
	"encoding/xml"
)

var (
	gameType     = make(map[string]string)
	flag         = make(map[string]string)
	playType     = make(map[int]string)
	transferType = make(map[string]string)
)

func init() {
	//游戏类型
	gameType["BAC"] = "百家乐"
	gameType["CBAC"] = "包桌百家乐"
	gameType["LINK"] = "连环百家乐"
	gameType["DT"] = "龙虎"
	gameType["SHB"] = "骰宝"
	gameType["ROU"] = "轮盘"
	gameType["FT"] = "番攤"
	gameType["SL1"] = "巴西世界杯"
	gameType["PK_J"] = "视频扑克(杰克高手)"
	gameType["SL2"] = "疯狂水果店"
	gameType["SL3"] = "3D 水族馆"
	gameType["SL4"] = "极速赛车"
	gameType["PKBJ"] = "新视频扑克(杰克高手)"
	gameType["FRU"] = "水果拉霸"

	//结算状态
	flag["0"] = "未结算"
	flag["1"] = "已结算"
	flag["-1"] = "重置试玩额度"
	flag["-2"] = "注单被篡改"
	flag["-8"] = "取消指定局注单"
	flag["-9"] = "取消注单"

	//游戏玩法

	//转账类别
	transferType["IN"] = "转入额度"
	transferType["OUT"] = "转出额度"
	transferType["RECALC"] = "重新派彩"
	transferType["GBED"] = "代理修改額度"
	transferType["RECKON"] = "派彩"
	transferType["RECALC_ERR"] = "重新派彩时扣款失败"
	transferType["CHANGE_CUS_BALANCE"] = "修改玩家账户额度"
	transferType["CHANGE_CUS_CREDIT"] = "修改玩家信用额度"
	transferType["RESET_CUS_CREDIT"] = "重置玩家信用额度"
	transferType["DONATEFEE"] = "玩家小费"
	transferType["CANCEL_BET"] = "系统取消下注"
	transferType["CANCEL_BET_ERR"] = "系统取消下注失败"
}

//<?xml version="1.0" encoding="utf-8"?><result info="error" msg="Ip Is Not Allowed!"/>
type ResultData struct {
	XMLName xml.Name `xml:"result"`
	Info    string   `xml:"info,attr"`
	Msg     string   `xml:"msg,attr"`
}

/*
billNo:注单流水号(满足一约束条件)
playerName:玩家账号
agentCod:代理商编号
gameCode:游戏局号
netAmount:玩家输赢额
betTime:投注时间
gameType:游戏类型
betAmount:投注金额
validBetAmount:有效
flag:结算状态
playType:游戏玩法
currency:货币类型
tableCode:桌子编号
loginIP:玩家 IP
recalcuTime:注单重新派彩时间
platformId:平台编号(通常為空)
platformType:平台類型
stringex:產品附註(通常為空)
remark: 輪盤遊戲 - 額外資訊
round: VIP – 包桌; VIPB – 旁注;其他 – 非包桌百家樂

<row dataType="BR" billNo="14072103192544" playerName="ah389"
agentCode="031001001001001" gameCode="GA0061472104J" netAmount="-50"
betTime="2014-07-21 03:11:40" gameType="BAC" betAmount="50"
validBetAmount="50" flag="1" playType="2" currency="CNY" tableCode="H33"
loginIP="223.11.21.174" recalcuTime="2014-07-21 03:12:00" platformType="AGIN"
remark="" round="AGQ" result="" />

<row dataType="EBR" billNo="14072200789639" playerName="de0515"
agentCode="031001001001001" gameCode="" netAmount="-50"
betTime="2014-07-22 00:30:52" gameType="SL1" betAmount="50"
validBetAmount="50" flag="1" playType="null" currency="CNY" tableCode="null"
loginIP="117.28.171.213" recalcuTime="2014-07-22 00:30:52" platformType="AGIN"
remark="null" round="SLOT" slottype="1"
result="10,6,8,9,11,1,101,5,201,9,11,9,201,6,4" mainbillno="14072200789639" />

<row dataType="BR" 投注详情
billNo="" billNo: 注单流水号(满足平台的
唯一约束条件)
playerName="" playerName: 玩家账号
agentCode="" agentCode: 代理商编号
gameCode="" gameCode: 游戏局号
netAmount="" netAmount: 玩家输赢额度
betTime="" betTime: 投注时间
gameType="" gameType: 游戏类型
betAmount="" betAmount: 投注金额
validBetAmount="" validBetAmount: 有效投注额度
flag="" flag: 结算状态
playType="" playType: 游戏玩法
currency="" currency: 货币类型
页 5 / 24 AsiaGamings.com
tableCode="" tableCode: 桌子编号
loginIP="" loginIP: 玩家 IP
recalcuTime="" recalcuTime: 注单重新派彩时间
platformId="" platformId: 平台编号(通常為空)
platformType="AGIN" platformType: 平台类型
stringex=”” stringex :產品附註(通常為空)
remark=”” remark: 额外信息
round=”DSP” round: 平台内的游戏厅类型
DSP – AG 国际厅; AGQ – AG
旗舰厅; VIP – VIP 包桌厅; SLOT
– 电子游戏; LED – 竞咪厅
result="" result: 结果
beforeCredit="195" beforeCredit: 玩家下注前的剩余
额度
deviceType="0"/> deviceType: 设备类型
"0"电脑; "1"手机

*/
type BetRecord struct {
	BillNo         string `xml:"billNo,attr"`
	DataType       string `xml:"dataType,attr"`
	PlayerName     string `xml:"playerName,attr"`
	AgentCod       string `xml:"agentCod,attr"`
	GameCode       string `xml:"gameCode,attr"`
	NetAmount      string `xml:"netAmount,attr"`
	BetTime        string `xml:"betTime,attr"`
	GameType       string `xml:"gameType,attr"`
	BetAmount      string `xml:"betAmount,attr"`
	ValidBetAmount string `xml:"validBetAmount,attr"`
	Flag           string `xml:"flag,attr"`
	PlayType       string `xml:"playType,attr"`
	Currency       string `xml:"currency,attr"`
	TableCode      string `xml:"tableCode,attr"`
	LoginIP        string `xml:"loginIP,attr"`
	RecalcuTime    string `xml:"recalcuTime,attr"`
	PlatformId     string `xml:"platformId,attr"`
	platformType   string `xml:"netAmount,attr"`
	Stringex       string `xml:"stringex,attr"`
	Remark         string `xml:"remark,attr"`
	Round          string `xml:"round,attr"`
	Result         string `xml:"result,attr"`
	BeforeCredit   string `xml:"beforeCredit,attr"`
	BeviceType     string `xml:"deviceType,attr"`
}

/**
 ID: 项目编号
agentCode: 代理商编号
transferId: 转账编号
tradeNo: 交易编号
platformId: 平台编号(通常為空)
platformType: 平台類型
playerName: 玩家账户
transferType: 转账类别
transferAmount: 转账额度
previousAmount: 转账前额度
currentAmount: 当前额度
currency: 货币
exchangeRate: 汇率
IP: 玩家 IP
flag: 转账状态
creationTime: 纪录时间

<row dataType="TR" ID="FEAFD2E18675449FE040C770796124DE"
agentCode="031001001001001" transferId="" tradeNo="140721416292334"
platformType="AGIN" playerName="fh2055" transferType="BET" transferAmount="-50" previousAmount="305.5" currentAmount="255.5"
currency="CNY" exchangeRate="1" IP="0" flag="0" creationTime="2014-07-21
03:12:00" gameCode="GA0071472104T" />
*/
type CashRecord struct {
	ID             string `xml:"ID,attr"`
	AgentCode      string `xml:"agentCode,attr"`
	TransferId     string `xml:"transferId,attr"`
	TradeNo        string `xml:"tradeNo,attr"`
	PlatformId     string `xml:"platformId,attr"`
	PlatformType   string `xml:"platformType,attr"`
	PlayerName     string `xml:"playerName,attr"`
	TransferType   string `xml:"transferType,attr"`
	TransferAmount string `xml:"transferAmount,attr"`
	PreviousAmount string `xml:"previousAmount,attr"`
	CurrentAmount  string `xml:"currentAmount,attr"`
	Currency       string `xml:"currency,attr"`
	ExchangeRate   string `xml:"exchangeRate,attr"`
	IP             string `xml:"IP,attr"`
	Flag           string `xml:"flag,attr"`
	CreationTime   string `xml:"creationTime,attr"`
}

/*
<row
gmcode=””  遊戲局號
tablecode=”” 台號
begintime=”” 局開始時間
closetime=”” 局結束時間
dealer=”” 荷官
shoecode=”” 牌靴
flag=”” 狀態 (1/null – 已確認, 0 - 未結算)
bankerpoint=”” 庄家點
playerpoint=”” 閒家點
cardnum=”” 牌數目
pair=”” 對子數
gametype=”” 遊戲類型
dragonpoint=”” 龍點數
tigerpoint=”” 虎點數
cardlist=”” 發牌資料
vid=””  視頻 ID
platformtype=”” 遊戲平台
/>
<row dataType="GR" gmcode="GA0031472103N" tablecode=""
begintime="2014-07-21 03:11:25" closetime="2014-07-21 03:12:05" dealer="Darmy"
shoecode="17477" flag="1" bankerPoint="0" playerPoint="7" cardnum="5" pair="0"
gametype="BAC" dragonpoint="0" tigerpoint="0" cardlist="S.13,H.12,S.11;D.13,D.7"
vid="A003" platformtype="AGIN" />
*/
type GameRecord struct {
	gmcode       string `xml:"gmcode,attr"`
	tablecode    string `xml:"tablecode,attr"`
	begintime    string `xml:"begintime,attr"`
	closetime    string `xml:"closetime,attr"`
	dealer       string `xml:"dealer,attr"`
	shoecode     string `xml:"shoecode,attr"`
	flag         string `xml:"flag,attr"`
	bankerpoint  string `xml:"bankerpoint,attr"`
	playerpoint  string `xml:"playerpoint,attr"`
	cardnum      string `xml:"cardnum,attr"`
	pair         string `xml:"pair,attr"`
	gametype     string `xml:"gametype,attr"`
	dragonpoint  string `xml:"dragonpoint,attr"`
	tigerpoint   string `xml:"tigerpoint,attr"`
	cardlist     string `xml:"cardlist,attr"`
	vid          string `xml:"vid,attr"`
	platformtype string `xml:"platformtype,attr"`
}
