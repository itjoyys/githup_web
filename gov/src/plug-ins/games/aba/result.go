package aba

import ()

type LoginchkResult struct {
	Code    string `json:"code"`
	Session string `json:"session",omitempty`
}

type BalanceResult struct {
	Code          string  `json:"code"`
	Balance       float64 `json:"balance",omitempty`
	FreezeBalance float64 `json:"freezeBalance",omitempty` //会员本厅冻结余额
}

/*
Account	帐号	string
BetNo	注单编号	string
BetTime	下注时间	long
GameName	游戏名称	string
GameProcess	游戏类型	int
TableNo	台号	string
Xue	靴号	int
Pu	铺号	int
Result	游戏结果	int	转化结果值，详见附录游戏结果类型定义
BetScore	下注	decimal
WinScore	输赢	decimal
RealBetScore	真实洗码量	decimal	注:会员在百家乐庄或闲都下注，洗码量为0
card0	0 号位	bigint	共6组长整型，以下数值对应数组索引,关于牌算法请参见<附录>;
百家乐(庄: 2，闲：3)
龙虎(虎: 2, 龙: 3)
牛牛(庄家:0,1号位:1,2号位:2,3号位:3,4号位:4,5号位:5)
card1	1号位	bigint
card2	2号位	bigint
card3	3号位	bigint
card4	4号位	bigint
card5	5号位	bigint
bet	下注明细	decimal(12,2)	查看说明

result	结果	string(50)
*/
type AbaBetRecord struct {
	Account      string
	BetNo        string  //注单
	BetTime      int64   //下注时间
	GameName     string  //游戏名称
	GameProcess  int     //游戏类型
	TableNo      string  //台号
	Xue          int     //靴号
	Pu           int     //铺号
	Result       int     //游戏结果 转化结果值，详见附录游戏结果类型定义
	BetScore     float64 //下注
	WinScore     float64 //输赢
	RealBetScore float64 //真实洗码量 注:会员在百家乐庄或闲都下注，洗码量为0
	card0        int64   //0 号位 共6组长整型，以下数值对应数组索引,关于牌算法请参见<附录>; 百家乐(庄: 2，闲：3) 龙虎(虎: 2, 龙: 3) 牛牛(庄家:0,1号位:1,2号位:2,3号位:3,4号位:4,5号位:5)
	card1        int64   //1号位
	card2        int64   //2号位
	card3        int64   //3号位
	card4        int64   //4号位
	card5        int64   //5号位
	bet          float64 //下注明细 查看说明
	result       string  //结果
}
