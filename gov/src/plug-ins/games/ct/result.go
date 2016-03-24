package ct

import (
	"encoding/xml"
	
	mct "app/models/ct"
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
<transaction_id>354030</transaction_id> --- long (注单号，指客户下注某笔记录在所有记录中的id号，如之前
的记录是空此时客户test_1下了一个注单那么交易id=1，然后test_2 接着下了一笔那么test_2的id是2)
<transaction_date_time>2011-07-25 14:19:09</transaction_date_time> --- String (yyyy-MM-dd HH:mm:ss)(下注
时间)
<closed_time>2011-07-25 14:20:35</closed_time>---String(yyyy-MM-dd HH:mm:ss)(结算或撤消时间，特别说明：
此字段对已结算或已撤消的注单有意义；对于未结算注单，是没有意义的，此时值为1970-01-01 00:00:00)
<member_id>smt99test23</member_id> --- String (客户账号名)
<member_type>CASH</member_type> --- String (客户类型，现金或信用额)
<currency>THB</currency> --- String (币别类型)
<balance_start>10000.0</balance_start> --- double (下注之前的余额)
<balance_end>2000.0</balance_end> --- double (下注之后的余额)
<IsRevocation>1</IsRevocation> ---- int (是否计算,0:表示没计算，1:表示已经计算2，:表示结果撤销即此局作废)
<GameType>1</GameType> --- int(表示是哪种游戏 见附件)
<TableID>1</TableID> ---- int(表示是哪桌的游戏 见附件)
<ShoeID>1247</ShoeID> ---long(牌类游戏中,每洗一次牌 就是一靴,代表靴号)
<PlayID>11</PlayID> ----long(牌类游戏中指的是每一靴牌中的第几局;非牌类游戏指第几局)
<BetPoints>8000.0</BetPoints> ---double(指下注金额)
<BetPointDetail>0#8000#0#0#0#0#0#</BetPointDetail> ---String(指详细下注的信息,此注单下了哪些注 见附件)
<BetResult>1#0#0#2#^45#26#0#20#18#1#</BetResult> --String(此局结果 见附件)
<BetResultDetail>0.0#0.0#0.0^8000.0#0.0#0.0^0.0#0.0#0.0^0.0#0.0#0.0^0.0#0.0#0.0^0.0#0.0#0.0^0.0#0.0#0.0^</BetResultDetail> --String (计算结果明细,具体格式见计算的文档)
<WinOrLoss>0.0</WinOrLoss> -- Double(此局的输赢金额 注不是实际输赢还要计算,见计算说明)
<betip>60.50.82.208</betip> -- String(客户下注时所在IP)
<paramid>57</paramid> ---long (外部参数，无意义)
<availablebet>7800.0</availablebet>--double(实际有效下注,即可以计算结果的下注，此你下注了8000但是有效的
只有7800)
</row>
</result>
//返回结果,注单信息

<?xml version="1.0"?>
<response>
    <code>0</code>
    <text>Success</text>
    <result>
        <row id="1">
            <transaction_id>10782518</transaction_id>
            <transaction_date_time>2015-05-19 18:10:40</transaction_date_time>
            <closed_time>2015-05-19 18:11:21</closed_time>
            <member_id>t@wwwwww10</member_id>
            <member_type>CASH</member_type>
            <currency>RMB</currency>
            <balance_start>40.0</balance_start>
            <balance_end>70.0</balance_end>
            <IsRevocation>1</IsRevocation>
            <GameType>1</GameType>
            <TableID>3</TableID>
            <ShoeID>28563</ShoeID>
            <PlayID>1</PlayID>
            <BetPoints>30.0</BetPoints>
            <BetPointDetail>0#30#0#0#0#0#0#</BetPointDetail>
            <BetResult>2#1#0#1#^12#25#0#45#16#0#</BetResult>
            <BetResultDetail>0.0#0.0#0.0^30.0#30.0#30.0^0.0#0.0#0.0^0.0#0.0#0.0^0.0#0.0#0.0^0.0#0.0#0.0^0.0#0.0#0.0^</BetResultDetail>
            <WinOrLoss>60.0</WinOrLoss>
            <betip>111.67.101.134</betip>
            <paramid></paramid>
            <availablebet>30.0</availablebet>
        </row>
        <row id="2">
            <transaction_id>10782588</transaction_id>
            <transaction_date_time>2015-05-19 18:13:05</transaction_date_time>
            <closed_time>2015-05-19 18:13:57</closed_time>
            <member_id>t@wwwwww10</member_id>
            <member_type>CASH</member_type>
            <currency>RMB</currency>
            <balance_start>70.0</balance_start>
            <balance_end>98.5</balance_end>
            <IsRevocation>1</IsRevocation>
            <GameType>1</GameType>
            <TableID>3</TableID>
            <ShoeID>28563</ShoeID>
            <PlayID>3</PlayID>
            <BetPoints>30.0</BetPoints>
            <BetPointDetail>30#0#0#0#0#0#0#</BetPointDetail>
            <BetResult>1#0#0#1#^52#21#0#33#32#0#</BetResult>
            <BetResultDetail>30.0#30.0#28.5^0.0#0.0#0.0^0.0#0.0#0.0^0.0#0.0#0.0^0.0#0.0#0.0^0.0#0.0#0.0^0.0#0.0#0.0^</BetResultDetail>
            <WinOrLoss>58.5</WinOrLoss>
            <betip>111.67.101.134</betip>
            <paramid></paramid>
            <availablebet>30.0</availablebet>
        </row>
    </result>
</response>
*/

type BetRecordResultData struct {
	XMLName xml.Name        `xml:"response"`
	Code    int             `xml:"code"`
	Text    string          `xml:"text"`
	Result  BetRecordResult `xml:"result"`
}
type BetRecordResult struct {
	XMLName    xml.Name    `xml:"result"`
	BetRecords []mct.CtBetRecord `xml:"row"`
}

