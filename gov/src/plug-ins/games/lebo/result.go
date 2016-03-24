package lebo

import (
	"encoding/xml"

	mlebo "app/models/lebo"
)

/*
// 响应规范
<?xml version="1.0"?>
<response>
<code></code>
<text></text>
<result></result>
</response>
*/
type ResultData struct {
	XMLName xml.Name `xml:"response"`
	Code    int      `xml:"code"`
	Text    string   `xml:"text"`
	Result  string   `xml:"result"`
}

/**
<amount> 36.58</ amount>
<ordernum>2014012501241246</ordernum>
<?xml version="1.0"?>
<response>
<code>0</code>
<text>Success</text>
<result><amount>20</amount><ordernum>2015053004082605</ordernum></result>
</response>
*/
type CachResultResponse struct {
	XMLName xml.Name `xml:"response"`
	Code    int      `xml:"code"`
	Text    string   `xml:"text"`
	Result  CachResult   `xml:"result"`	
}

type CachResult struct {
	XMLName xml.Name `xml:"result"`
	Amount    float64  `xml:"amount"`
	Ordernum    string   `xml:"ordernum"`
}

/*
<online_status>Online</online_status>
<member_status>Normal</member_status>
<balance>96.85</balance>

<?xml version="1.0"?>
<response>
<code>0</code>
<text>User exist</text>
<result>
<username>t@wwwwww10</username>
<online_status>Offline</online_status>
<member_status>Normal</member_status>
<balance>0</balance>
</result>
</response>
*/
type UserinfoResponse struct {
	XMLName xml.Name `xml:"response"`
	Code    int      `xml:"code"`
	Text    string   `xml:"text"`
	Result  Userinfo `xml:"result"`
}

type Userinfo struct {
	XMLName      xml.Name `xml:"result"`
	Username     string   `xml:"username"`
	OnlineStatus string   `xml:"online_status"`
	MemberStarus string   `xml:"member_status"`
	Balance      float64  `xml:"balance"`
}

/*
<code>0</code>
<text>Success</text>
<result>
<row id=”1”>
<gameid>354030</gameid> --- long (注单号，指客户下注某笔记录在所有记录中的id号，如之前的记录是空此时客户test_1
下了一个注单那么交易id=1，然后test_2 接着下了一笔那么test_2的id是2)
<betstarttime>1362268822</betstarttime> --- String (时间戳)
<member>test01</member> --- String (客户账号名)
<IsRevocation>0</IsRevocation> ---- int (是否计算, 0:表示已经计算1，:表示结果撤销即此局作废)
<GameType>1</GameType> --- int(表示是哪种游戏 见附件)
<TableID>1</TableID> ---- int(表示是哪桌的游戏 见附件)
<BetAmount>8000.0</BetAmount> ---double(指下注金额)
<PayOut>0.0</PayOut> -- Double(此局的输赢金额)
<ValidBetAmount>78000.0</ValidBetAmount>--double(实际有效下注)
<BetResult></BetResult> --String(此局结果 见附件)
<BetPointDetail>0#8000#0#0#0#0#0#</BetPointDetail> ---String(指详细下注的信息,此注单下了哪些注 见附件)
</row>
//返回结果,注单信息
*/
type BetRecordResultData struct {
	XMLName xml.Name        `xml:"response"`
	Code    int             `xml:"code"`
	Text    string          `xml:"text"`
	Result  BetRecordResult `xml:"result"`
}
type BetRecordResult struct {
	XMLName    xml.Name              `xml:"result"`
	BetRecords []mlebo.LeboBetRecord `xml:"row"`
}
