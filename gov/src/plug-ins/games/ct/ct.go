package ct

import (
	"app/models"
	"common"
	"core/napping"
	"errors"
	"strings"
	"utility"

	mct "app/models/ct"
)

/*
host = http://api.855group.com/api/ctapi/
agent = XC000000
key = 64ffe0d12e9c24beced9c15ee7764dea

host = http://api.855group.com/api/ctapi/
agent = XC000000
key = 64ffe0d12e9c24beced9c15ee7764dea
*/

var (
	ErrNotExistSiteid = errors.New("not exist site id")
)

type CrownTechnology struct {
	AgentName string
	Userkey   string
	MethodUrl string
	SiteId    string
}

/**
 *
 * 从ini文件读取数据
 * @param  {[type]} ) (website      string, keyb string, uppername string [description]
 * @return {[type]}   [description]
 */
func get_auth_from_ini() (userkey, url string) {
	//agentname = common.Cfg.Section("ct").Key("AgentName").MustString("XC000000")
	userkey = common.Cfg.Section("ct").Key("Userkey").MustString("64ffe0d12e9c24beced9c15ee7764dea")
	url = common.Cfg.Section("ct").Key("MethodUrl").MustString("http://api.855group.com/api/ctapi")
	return
}

func CrownTechnologyInit(siteid string) (*CrownTechnology, error) {
	ct := new(CrownTechnology)

	ct.SiteId = siteid
	//获取配置文件
	ct.Userkey, ct.MethodUrl = get_auth_from_ini()

	site, err := models.GetSiteById(siteid)
	if err != nil {
		common.Log.Err("CT MGameInit sql err:", err)
		return nil, ErrNotExistSiteid
	}
	//获取配置文件
	ct.AgentName = site.Ct_AgentName
	if len(ct.AgentName) == 0 {
		return nil, nil
	}

	return ct, nil
}

//http://<host>/ctapi?action=<action>&agent=<agent>&username=<username>&param=<param>
//获取会员登录URL[核心接口]
func (ct *CrownTechnology) Getloginurl(username, lang, cur, limit string, maxwin float64) (string, error) {
	//Lang|Currency|MaxWin|Limit|Token
	if !checkUsername(username) {
		return "", errors.New("username no right")
	}
	if len(lang) == 0 {
		lang = "cn"
	}
	if len(cur) == 0 {
		cur = "RMB"
	}
	//Md5(“SecurityKey+Currency+Agent+UserName+Lang+MaxWin”)
	token := common.Md5(ct.Userkey + cur + ct.AgentName + username + lang + utility.ToStr(maxwin, 2, 64))
	//common.Log.Info("Getloginurl Userkey:", ct.Userkey+cur+ct.AgentName+username+lang+utility.ToStr(maxwin, 2, 64))
	param := lang + "|" + cur + "|" + utility.ToStr(maxwin, 2, 64) + "|" + limit + "|" + token
	//common.Log.Info("Getloginurl param", param)
	p := napping.Params{"action": "1", "agent": ct.AgentName, "username": username, "param": param}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := ResultData{}
	_, err := s.Get(ct.MethodUrl, &p, &res, nil)
	if err != nil {
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
func (ct *CrownTechnology) Getuserinfo(username string) (*Userinfo, error) {
	p := napping.Params{"action": "2", "agent": ct.AgentName, "username": username, "param": ""}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := UserinfoResponse{}
	_, err := s.Get(ct.MethodUrl, &p, &res, nil)
	if err != nil {
		common.Log.Err("CT Getloginurl method Connection err ", err)
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
func (ct *CrownTechnology) TransferCreditIN(username, serial, cur string, amount float64) (float64, error) {
	if !checkUsername(username) {
		return 0, errors.New("username no right")
	}
	if len(serial) == 0 {
		return 0, errors.New("serial net empty")
	}
	if len(cur) == 0 {
		cur = "RMB"
	}

	token := common.Md5(ct.Userkey + utility.ToStr(amount, 2, 64) + ct.AgentName + username + serial)
	param := serial + "|" + utility.ToStr(amount, 2, 64) + "|" + cur + "|" + token

	p := napping.Params{"action": "3", "agent": ct.AgentName, "username": username, "param": param}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := ResultData{}
	_, err := s.Get(ct.MethodUrl, &p, &res, nil)
	if err != nil {
		common.Log.Err("CT TransferCreditIN method Connection err ", err)
		return 0, err
	}
	//common.Log.Info("TransferCreditIN", resp.RawText())
	if res.Code == 0 {
		return money(res.Result)
	} else if res.Code == 1 {
		return 0, errors.New("User not exist")
	} else if res.Code == 2 {
		return 0, errors.New("Duplicated serial")
	} else if res.Code == 3 {
		return 0, errors.New("Currency error")
	} else if res.Code == 4 {
		return 0, errors.New("Deposit error")
	} else {
		return 0, getcommon(res.Code)
	}
}

//会员取款[核心接口]
func (ct *CrownTechnology) TransferCreditOUT(username, serial, cur string, amount float64) (float64, error) {
	if !checkUsername(username) {
		return 0, errors.New("username no right")
	}
	if len(serial) == 0 {
		return 0, errors.New("serial net empty")
	}
	if len(cur) == 0 {
		cur = "RMB"
	}

	token := common.Md5(ct.Userkey + utility.ToStr(amount, 2, 64) + ct.AgentName + username + serial)
	param := serial + "|" + utility.ToStr(amount, 2, 64) + "|" + cur + "|" + token

	p := napping.Params{"action": "4", "agent": ct.AgentName, "username": username, "param": param}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := ResultData{}
	_, err := s.Get(ct.MethodUrl, &p, &res, nil)
	if err != nil {
		common.Log.Err("CT TransferCreditOUT method Connection err ", err)
		return 0, err
	}
	//common.Log.Info("TransferCreditOUT", resp.RawText())
	if res.Code == 0 {
		return money(res.Result)
	} else if res.Code == 1 {
		return 0, errors.New("User not exist")
	} else if res.Code == 2 {
		return 0, errors.New("Duplicated serial")
	} else if res.Code == 3 {
		return 0, errors.New("Currency error")
	} else if res.Code == 4 {
		return 0, errors.New("Deposit error")
	} else {
		return 0, getcommon(res.Code)
	}
}

//会员存取款状态查询[核心接口]
//在线会员列表
//注销在线会员
//会员状态更新
//按时间查询已结算和已撤销注单[此接口仅作查询之用]
//按时间查询未结算注单[此接口仅作查询之用]
//获取结算/撤销注单[与2.2.12配对使用][取注单请使用此接口][核心接口]
func (ct *CrownTechnology) GetBetRecord() ([]mct.CtBetRecord, error) {

	p := napping.Params{"action": "11", "agent": ct.AgentName, "username": "", "param": ""}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := BetRecordResultData{}
	resp, err := s.Get(ct.MethodUrl, &p, &res, nil)
	if err != nil {
		if resp != nil {
			common.Log.Info("GetBetRecord", resp.RawText())
		}
		common.Log.Err("CT Getloginurl method Connection err ", err)
		return nil, err
	}

	if res.Code == 0 {
		return res.Result.BetRecords, nil
	} else if res.Code == 1 {
		return nil, errors.New("username locked")
	} else {
		return nil, getcommon(res.Code)
	}
}

func (ct *CrownTechnology) GetBetRecordbyDate(starttime, endtime string) ([]mct.CtBetRecord, error) {

	p := napping.Params{"action": "9", "agent": ct.AgentName, "username": "", "param": starttime + "|" + endtime}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := BetRecordResultData{}
	resp, err := s.Get(ct.MethodUrl, &p, &res, nil)
	common.Log.Info("GetBetRecordbyDate", resp.RawText())
	if err != nil {
		common.Log.Err("CT GetBetRecordbyDate method Connection err ", err)
		return nil, err
	}

	if res.Code == 0 {
		return res.Result.BetRecords, nil
	} else if res.Code == 1 {
		return nil, errors.New("username locked")
	} else {
		return nil, getcommon(res.Code)
	}
}

//标记结算/撤销注单[与2.2.11配对使用][核心接口]
func (ct *CrownTechnology) MarkBetRecord(ids []string) error {
	if len(ids) == 0 {
		return errors.New("ids is empty")
	}
	param := strings.Join(ids, "|")
	p := napping.Params{"action": "12", "agent": ct.AgentName, "username": "", "param": param}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := BetRecordResultData{}
	resp, err := s.Get(ct.MethodUrl, &p, &res, nil)
	common.Log.Info("GetBetRecord", resp.RawText())
	if err != nil {
		common.Log.Err("CT Getloginurl method Connection err ", err)
		return err
	}

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
