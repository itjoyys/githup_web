package aba

import (
	"common"
	"core/napping"
	"errors"
	"time"
	"utility"
)

type Aba struct {
	Partner   string //接入商ID
	Sign      string //签名
	MethodUrl string //地址
	LoginUrl  string
	Prefix    string
	//Site_id   string
}

/**
 *  http://pk.ssst2.com:90/
 * 从ini文件读取数据
 * @param  {[type]} ) (website      string, keyb string, uppername string [description]
 * @return {[type]}   [description]
 */
func get_auth_from_ini() (partner, sign, url, lgoinurl, prefix string) {
	partner = common.Cfg.Section("aba").Key("Partner").MustString("MP100001")
	sign = common.Cfg.Section("aba").Key("Sign").MustString("6dVCfoBSBkSZkCWJigtctnLa0CN7nI3I")
	url = common.Cfg.Section("aba").Key("MethodUrl").MustString("http://61.219.187.243:10281")
	lgoinurl = common.Cfg.Section("aba").Key("LoginUrl").MustString("http://JC.ssst2.com")
	prefix = common.Cfg.Section("aba").Key("Prefix").MustString("JA")
	return
}

func AbaInit(siteid string) (*Aba, error) {
	aba := new(Aba)

	//aba.Site_id = siteid
	//获取配置文件
	aba.Partner, aba.Sign, aba.MethodUrl, aba.LoginUrl, aba.Prefix = get_auth_from_ini()
	/*
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
	*/

	return aba, nil
}

//获取登录session
//login/chk
func (b *Aba) LoginChk(account, upsw string, tierule, ratio int) (string, error) {
	//排序
	time3 := time.Now()
	loc, _ := time.LoadLocation("Asia/Shanghai")
	time2 := time3.In(loc)
	time_str := utility.ToStr(time2.Unix())
	keystr := "account=" + account + "&partner=" + b.Partner + "&ratio=" + utility.ToStr(ratio) + "&t=" + time_str + "&tierule=" + utility.ToStr(tierule)

	sign := common.Md5(keystr + b.Sign)

	p := napping.Params{"account": account, "partner": b.Partner, "sign": sign,
		"tierule": utility.ToStr(tierule), "ratio": utility.ToStr(ratio), "t": time_str}

	s := napping.Session{Timeout: 30, Datatype: "json"}
	res := LoginchkResult{}
	resp, err := s.Post(b.MethodUrl+"/login/chk", nil, &p, &res, nil)
	if err != nil {
		if resp != nil {
			common.Log.Err("aba LoginChk method Connection err", resp.RawText())
		}
		common.Log.Err("aba LoginChk method Connection err", err)
		return "", err
	}
	common.Log.Err("aba LoginChk method Connection err", resp.RawText(), err)
	if res.Code == "SUCCESS" {
		return res.Session, nil
	} else {
		return "", errors.New(res.Code)
	}
}

///user/balance
func (b *Aba) Getbalance(account, session string) (float64, float64, error) {
	//account partner sign session
	p := napping.Params{"account": account, "session": session}
	s := napping.Session{Timeout: 30, Datatype: "json"}
	res := BalanceResult{}
	resp, err := s.Get(b.MethodUrl+"/user/balance", &p, &res, nil)
	if err != nil {
		common.Log.Err("aba Getbalance method Connection err", resp.RawText(), err)
		return 0, 0, err
	}
	if res.Code == "SUCCESS" {
		return res.Balance, res.FreezeBalance, nil
	} else {
		return 0, 0, errors.New(res.Code)
	}
}

func (b *Aba) Getloginurl(account, session string) string {
	//account partner sign session
	str := b.LoginUrl + "/?a=" + b.Prefix + account + "&s=" + session
	return str
}
