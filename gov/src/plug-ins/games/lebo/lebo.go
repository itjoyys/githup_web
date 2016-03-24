package lebo

import (
	"common"
	"core/napping"
	"errors"
	"strings"
	"utility"

	"app/models"
	mlebo "app/models/lebo"
)

/*
host = http://api.855group.com/api/ctapi/
agent = XC000000
key = 64ffe0d12e9c24beced9c15ee7764dea

host = http://api.855group.com/api/ctapi/
agent = XC000000
key = 64ffe0d12e9c24beced9c15ee7764dea



/////
gather005pkylc.jugaming.com


*/
type LeboTechnology struct {
	SiteId    string
	AgentName string
	Userkey   string
	Userurl   string
	Beturl    string
}

var (
	ErrNotExistSiteid = errors.New("not exist site id")
	ErrEmptyUsername  = errors.New("username is empty")
)

/**
 * 从ini文件读取数据
 * @param  {[type]} ) (website      string, keyb string, uppername string [description]
 * @return {[type]}   [description]
 */
func get_auth_from_ini() (userkey, userurl, beturl string) {
	userurl = common.Cfg.Section("lebo").Key("UserUrl").MustString("https://login005pkylc.lebogame.com/AServer/server.php")
	userkey = common.Cfg.Section("lebo").Key("Userkey").MustString("Eewke32834@") //SecurityKey:Eewke32834@
	beturl = common.Cfg.Section("lebo").Key("BetUrl").MustString("https://gather005pkylc.jugaming.com/AServer/server.php")
	return
}

//https://apiag.jugaming.com/AServer/server.php?method=UserLogin&
//agent=c1zon000&username=test02&param=zh-cn|test02|RMB|2dbc76907ecf951e8881298f449d6be8

func LeboTechnologyInit(siteid string) (*LeboTechnology, error) {
	ct := new(LeboTechnology)
	ct.SiteId = siteid
	site, err := models.GetSiteById(siteid)
	if err != nil {
		common.Log.Err("MG MGameInit sql err:", err)
		return nil, ErrNotExistSiteid
	}
	//获取配置文件
	ct.Userkey, ct.Userurl, ct.Beturl = get_auth_from_ini()
	ct.AgentName = site.Lebo_AgentName
	//bbin.Key =
	return ct, nil
}

//http://<host>/ctapi?action=<action>&agent=<agent>&username=<username>&param=<param>
//获取会员登录URL[核心接口]
func (ct *LeboTechnology) Getloginurl(username, lang, cur, limit string, maxwin float64) (string, error) {
	//Lang|Currency|MaxWin|Limit|Token
	if !checkUsername(username) {
		return "", errors.New("username no right")
	}
	if len(lang) == 0 {
		lang = "zh-cn"
	}
	if len(cur) == 0 {
		cur = "RMB"
	}
	//Md5(“SecurityKey+Currency+Agent+UserName+Lang+MaxWin”)
	token := common.Md5(ct.Userkey + cur + ct.AgentName + username + lang)
	//common.Log.Info("Getloginurl Userkey:", ct.Userkey+cur+ct.AgentName+username+lang)
	param := lang + "|" + username + "|" + cur + "|" + token
	//common.Log.Info("Getloginurl param", param)
	p := napping.Params{"method": "UserLogin", "agent": ct.AgentName, "username": username, "param": param}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := ResultData{}
	resp, err := s.Get(ct.Userurl, &p, &res, nil)
	/*
		<?xml version="1.0"?>
		<response><code>0</code>
		<text>Success</text>
		<result>http://pk-lebo.org/Lebo/game.php?uid=932248adf6147445008fb&amp;target=BigHall.swf&amp;sid=-1&amp;apiSign=1&amp;langx=zh-cn</result>
		</response>
	*/
	if err != nil {
		if resp != nil {
			common.Log.Info("Getloginurl", resp.RawText())
		}
		common.Log.Err("CT Getloginurl method Connection err ", err)
		return "", err
	}
	//common.Log.Info("Getloginurl", resp.RawText())
	if res.Code == 0 {
		return res.Result, nil
	} else if res.Code == 1 {
		return "", errors.New("username locked")
	} else {
		return "", getcommon(res.Code)
	}
}

//获取供应商会员信息
func (ct *LeboTechnology) Getuserinfo(username string) (*Userinfo, error) {
	p := napping.Params{"method": "UserDetail", "agent": ct.AgentName, "username": username, "param": ""}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := UserinfoResponse{}
	_, err := s.Get(ct.Userurl, &p, &res, nil)
	if err != nil {
		common.Log.Err("Lebo Getloginurl method Connection err ", err)
		return nil, err
	}
	//common.Log.Info("Getloginurl", resp.RawText())
	if res.Code == 0 {
		return &res.Result, nil
	} else if res.Code == 1 {
		return nil, errors.New("username locked")
	} else {
		return nil, getcommon(res.Code)
	}
}

//会员存款[核心接口]
func (ct *LeboTechnology) TransferCreditIN(username string, amount float64) (*CachResult, error) {
	if !checkUsername(username) {
		return nil, errors.New("username no right")
	}
	token := common.Md5(ct.Userkey + utility.ToStr(amount, 2, 64) + ct.AgentName + username)
	param := utility.ToStr(amount, 2, 64) + "|" + token

	p := napping.Params{"method": "Deposit", "agent": ct.AgentName, "username": username, "param": param}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := CachResultResponse{}
	_, err := s.Get(ct.Userurl, &p, &res, nil)
	if err != nil {
		common.Log.Err("Lebo TransferCreditIN method Connection err ", err)
		return nil, err
	}
	//common.Log.Info("TransferCreditIN", resp.RawText())
	if res.Code == 0 {
		return &res.Result, nil
	} else if res.Code == 1 {
		return nil, errors.New("User not exist")
	} else if res.Code == 2 {
		return nil, errors.New("Duplicated serial")
	} else if res.Code == 3 {
		return nil, errors.New("Currency error")
	} else if res.Code == 4 {
		return nil, errors.New("Deposit error")
	} else {
		return nil, getcommon(res.Code)
	}
}

//会员取款[核心接口]
func (ct *LeboTechnology) TransferCreditOUT(username string, amount float64) (*CachResult, error) {
	if !checkUsername(username) {
		return nil, errors.New("username no right")
	}

	token := common.Md5(ct.Userkey + utility.ToStr(amount, 2, 64) + ct.AgentName + username)
	param := utility.ToStr(amount, 2, 64) + "|" + token

	p := napping.Params{"method": "WithDrawal", "agent": ct.AgentName, "username": username, "param": param}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := CachResultResponse{}
	_, err := s.Get(ct.Userurl, &p, &res, nil)
	if err != nil {
		common.Log.Err("Lebo TransferCreditOUT method Connection err ", err)
		return nil, err
	}
	//common.Log.Info("TransferCreditOUT", resp.RawText())
	if res.Code == 0 {
		return &res.Result, nil
	} else if res.Code == 1 {
		return nil, errors.New("User not exist")
	} else if res.Code == 2 {
		return nil, errors.New("Duplicated serial")
	} else if res.Code == 3 {
		return nil, errors.New("Currency error")
	} else if res.Code == 4 {
		return nil, errors.New("Deposit error")
	} else {
		return nil, getcommon(res.Code)
	}
}

//会员存取款状态查询[核心接口]
//在线会员列表
//注销在线会员
//会员状态更新
//按时间查询已结算和已撤销注单[此接口仅作查询之用]
//按时间查询未结算注单[此接口仅作查询之用]
//获取结算/撤销注单[与2.2.12配对使用][取注单请使用此接口][核心接口]
func (ct *LeboTechnology) GetBetRecord() ([]mlebo.LeboBetRecord, error) {
	p := napping.Params{"method": "GetBetData", "agent": ct.AgentName, "username": "", "param": ""}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := BetRecordResultData{}
	resp, err := s.Get(ct.Beturl, &p, &res, nil)
	if err != nil {
		common.Log.Err("Lebo GetBetRecord method Connection err ", err)
		if resp !=nil{
			common.Log.Err("Lebo GetBetRecord method Connection body ", resp.RawText())
		}
		return nil, err
	}
	common.Log.Info("GetBetRecord", resp.RawText())
	if res.Code == 0 {
		return res.Result.BetRecords, nil
	} else if res.Code == 1 {
		return nil, errors.New("username locked")
	} else {
		return nil, getcommon(res.Code)
	}
}

//根据日期区间获取注单
//param=2011-01-31 19:25:34|2011-01-31 19:25:34
func (ct *LeboTechnology) GetBetRecordByDate(starttime, endtime string) ([]mlebo.LeboBetRecord, error) {
	p := napping.Params{"method": "BetRecord", "agent": ct.AgentName, "username": "", "param": starttime + "|" + endtime}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := BetRecordResultData{}
	resp, err := s.Get(ct.Beturl, &p, &res, nil)
	if err != nil {
		common.Log.Err("Lebo GetBetRecordByDate method Connection err ", err)
		return nil, err
	}
	common.Log.Info("GetBetRecordByDate",starttime, endtime, resp.RawText())
	if res.Code == 0 {
		return res.Result.BetRecords, nil
	} else if res.Code == 1 {
		return nil, errors.New("username locked")
	} else {
		return nil, getcommon(res.Code)
	}
}

//标记结算/撤销注单[与2.2.11配对使用][核心接口]
func (ct *LeboTechnology) MarkBetRecord(ids []string) error {
	if len(ids) == 0 {
		return errors.New("ids is empty")
	}
	param := strings.Join(ids, "|")
	p := napping.Params{"method": "MarkBetData", "agent": ct.AgentName, "username": "", "param": param}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := BetRecordResultData{}
	_, err := s.Get(ct.Beturl, &p, &res, nil)
	if err != nil {
		common.Log.Err("Lebo Getloginurl method Connection err ", err)
		return err
	}
	//common.Log.Info("GetBetRecord", resp.RawText())
	if res.Code == 0 {
		return nil
	} else if res.Code == 1 {
		return errors.New("MarkBetRecord Fail")
	} else {
		return getcommon(res.Code)
	}
	return nil
}

//按时间查询已结算和已撤销注单数目
//按时间查询未结算注单数目
//按注单号查询未结算/已结算/已撤销注单

func getcommon(code int) error {
	var err error
	if code == -1 {
		err = errors.New("System maintenance")
	} else if code == -2 {
		err = errors.New("Invalid action")
	} else if code == -3 {
		err = errors.New("Invalid arguments")
	} else if code == -4 {
		err = errors.New("IP address not allow")
	} else if code == -5 {
		err = errors.New("Invalid username")
	} else if code == -6 {
		err = errors.New("System error")
	} else {
		err = errors.New("unknown error")
	}

	return err
}

func money(balance string) (float64, error) {
	balance = strings.Trim(balance, " ")

	return utility.StrTo(balance).Float64()
}

func checkUsername(username string) bool {
	return true
}
