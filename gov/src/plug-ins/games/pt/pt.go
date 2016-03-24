package pt

import (
	"common"
	"core/napping"
	"errors"
	//"time"
	//mpt "app/models/pt"
	//"fmt"
	"strings"
	"utility"
)

type Ptgame struct {
	MethodUrl    string //API地址
	merchantname string
	merchantcode string
	ipaddress    string
	lang         string
	gamecode     string
}

/**
 *  http://pk.ssst2.com:90/
 * 从ini文件读取数据
 * @param  {[type]} ) (website      string, keyb string, uppername string [description]
 * @return {[type]}   [description]
 */
func get_auth_from_ini() (MethodUrl, merchantname, merchantcode string) {
	MethodUrl = common.Cfg.Section("pt").Key("MethodUrl").MustString("https://ws-keryx2.imapi.net")
	merchantname = common.Cfg.Section("pt").Key("merchantname").MustString("tkprod")
	merchantcode = common.Cfg.Section("pt").Key("merchantcode").MustString("Q9SG6a2pcvmGBxIBIk4EfdKUdzFBnTd9")
	return
}

func PtInit() (*Ptgame, error) {
	Pt := new(Ptgame)

	//获取配置文件
	Pt.MethodUrl, Pt.merchantname, Pt.merchantcode = get_auth_from_ini()
	return Pt, nil
}

//获取登录session
///player/createplayer
func (Pt *Ptgame) CheckOrCreateGameAccout(username, Password, cur string) (bool, error) {
	if len(username) < 0 {
		return false, common.Log.Err("pt CheckOrCreateGameAccout method Connection err", "username nil")

	}
	if len(Password) < 0 {
		return false, common.Log.Err("pt CheckOrCreateGameAccout method Connection err", "Password nil")
	}
	if len(cur) < 0 {
		return false, common.Log.Err("pt CheckOrCreateGameAccout method Connection err", "currency nil")
	}
	p := napping.Params{"membercode": username, "password": Password, "currency": cur}

	s := napping.Session{Timeout: 30, Datatype: "json"}
	s.SetHeader("merchantname", Pt.merchantname)
	s.SetHeader("merchantcode", Pt.merchantcode)
	//fmt.Println(Pt.MethodUrl + "/player/createplayer" + s.Header.Get("merchantname") + s.Header.Get("merchantcode"))
	//fmt.Println(p)
	res := LoginchkResult{}
	resp, err := s.Post2(Pt.MethodUrl+"/player/createplayer", nil, &p, &res, nil)
	if err != nil {
		if resp != nil {
			common.Log.Err("pt CheckOrCreateGameAccout method Connection err", resp.RawText())
		}
		common.Log.Err("pt CheckOrCreateGameAccout method Connection err", err)
		return false, err
	}
	common.Log.Err("pt CheckOrCreateGameAccout method Connection err", resp.RawText(), err)
	if res.Code == "0" {
		return true, nil
	} else {
		return false, errors.New(res.Code)
	}
}

//GetBalance（查询余额）
func (Pt *Ptgame) GetBalance(username string) (float64, error) {
	if !checkUsername(username) {
		return 0, errors.New("username no right")
	}
	p := napping.Params{}
	s := napping.Session{Timeout: 30, Datatype: "json"}
	s.SetHeader("merchantname", Pt.merchantname)
	s.SetHeader("merchantcode", Pt.merchantcode)
	res := BalanceResult{}
	resp, err := s.Get(Pt.MethodUrl+"/account/getbalance/membercode/"+username+"/producttype/0", &p, &res, nil)
	if err != nil {
		if resp != nil {
			common.Log.Err("pt GetBalance method Connection err", resp.RawText())
		}
		common.Log.Err("PT GetBalance method Connection err ", err)
		return 0, err
	}
	if res.Code == "0" {
		return res.Balance, nil
	} else {
		common.Log.Err("PT GetBalance method Connection err ", resp.RawText())
		return 0, err
	}
	return 0, err
}

//PrepareTransferCredit (预备转账)
func (Pt *Ptgame) TransferCredit(username, billno, ctype string, credit float64) (bool, error) {
	if !checkUsername(username) {
		return false, errors.New("username no right")
	}
	credit_str := ""
	if strings.ToUpper(ctype) == "OUT" {
		credit = 0 - credit
		credit_str = utility.ToStr(credit)
	}
	if strings.ToUpper(ctype) == "IN" {
		credit = 0 + credit
		credit_str = utility.ToStr(credit)
	}
	p := napping.Params{"membercode": username, "amount": credit_str, "externaltransactionid": billno, "producttype": "0"}
	s := napping.Session{Timeout: 30, Datatype: "json"}
	s.SetHeader("merchantname", Pt.merchantname)
	s.SetHeader("merchantcode", Pt.merchantcode)
	res := CreditResult{}
	resp, err := s.Post2(Pt.MethodUrl+"/chip/createtransaction", nil, &p, &res, nil)
	if err != nil {
		if resp != nil {
			common.Log.Err("PT TransferCredit method Connection err ", resp.RawText())
		}
		common.Log.Err("PT TransferCredit method Connection err ", err)
		return false, err
	}
	common.Log.Err("PT TransferCredit method Connection err ", resp.RawText(), err)
	if res.Code == "0" {
		return true, nil
	} else {
		return false, err
	}
	return false, err
}

//跳转到游戏页面，进入游戏(forwardGame)
func (Pt *Ptgame) ForwardGame(username, gamecode, ipaddress, lang, producttype string) (string, error) {
	if !checkUsername(username) {
		return "", errors.New("username no right")
	}
	if len(lang) == 0 {
		lang = "EN"
	}
	if len(ipaddress) == 0 {
		return "", errors.New("ipaddress no right")
	}
	if len(producttype) == 0 {
		producttype = "0"
	}

	p := napping.Params{"membercode": username, "gamecode": gamecode, "langauge": lang, "ipaddress": ipaddress, "producttype": producttype}
	s := napping.Session{Timeout: 30, Datatype: "json"}
	s.SetHeader("merchantname", Pt.merchantname)
	s.SetHeader("merchantcode", Pt.merchantcode)
	res := GameResult{}
	resp, err := s.Post2(Pt.MethodUrl+"/game/launchgame", nil, &p, &res, nil)

	if err != nil {
		if resp != nil {
			common.Log.Err("PT ForwardGame method Connection err ", resp.RawText())
		}
		common.Log.Err("PT ForwardGame method Connection err ", err)
		return "", err
	}
	common.Log.Err("PT ForwardGame method Connection err ", resp.RawText(), err)
	if res.Code == "0" {
		url := " http://cache.download.banner.longsnake88.com/casinoclient.html?game=" + gamecode + "&language=" + lang
		return url, nil
	} else {
		return "", err
	}
	return "", err
}

//跳转到游戏页面，进入游戏(ForwardSwGame)
func (Pt *Ptgame) ForwardSwGame(lang, currencycode, gameid string) (string, error) {
	if len(lang) == 0 {
		lang = "EN"
	}
	if len(currencycode) == 0 {
		currencycode = "0"
	}
	url := "http://cache.download.banner.longsnake88.com/casinoclient.html?language=" + lang + "&game=" + gameid + "&mode=offline&affiliates=1&currency=" + currencycode
	return url, nil
}

//获取注单
func (Pt *Ptgame) GetBetRecord(starttime, endtime string, page int) (*PtBetRecord, error) {
	if len(starttime) == 0 {
		return nil, errors.New("starttime no right")
	}
	if len(endtime) == 0 {
		return nil, errors.New("endtime no right")
	}

	p := napping.Params{"startdate": starttime, "enddate": endtime, "page": utility.ToStr(page), "producttype": "0", "currency": "CNY"}
	s := napping.Session{Timeout: 30, Datatype: "json"}
	s.SetHeader("merchantname", Pt.merchantname)
	s.SetHeader("merchantcode", Pt.merchantcode)
	//common.Log.Err("pt GetBetRecord data", p, s)
	res := PtBetRecord{}
	resp, err := s.Get(Pt.MethodUrl+"/report/getbetlog", &p, &res, nil)
	if err != nil {
		if resp != nil {
			common.Log.Err("pt GetBetRecord data", resp.RawText())
		}
		common.Log.Err("PT GetBetRecord method data", err)
		return nil, err
	}
	//common.Log.Err("pt GetBetRecord data", resp.RawText())
	if res.Code == "0" {
		return &res, nil
	} else {
		common.Log.Err("PT GetBetRecord method Connection err ", resp.RawText())
		return nil, err
	}
	return nil, err
}

func (Pt *Ptgame) EditAccount(username, password string) (bool, error) {
	if !checkUsername(username) {
		return false, errors.New("username no right")
	}
	if !checkPassword(password) {
		return false, errors.New("password no right")
	}
	p := napping.Params{"membercode": username, "password": password}
	s := napping.Session{Timeout: 30, Datatype: "json"}
	s.SetHeader("merchantname", Pt.merchantname)
	s.SetHeader("merchantcode", Pt.merchantcode)
	res := LoginchkResult{}
	resp, err := s.Put2(Pt.MethodUrl+"/player/resetpassword", &p, nil, &res, nil)
	if err != nil {
		if resp != nil {
			common.Log.Err("PT EditAccount method Connection err ", resp.RawText())
		}
		common.Log.Err("PT EditAccount method Connection err ", err)
		return false, err
	}
	if res.Code == "0" {
		return true, nil
	} else {
		common.Log.Err("PT EditAccount method Connection err ", resp.RawText(), err)
		return false, err
	}
	return false, err

}

//删除玩家会话
func (Pt *Ptgame) KillSession(username string) (bool, error) {
	if !checkUsername(username) {
		return false, errors.New("username no right")
	}
	p := napping.Params{"membercode": username, "producttype": "0"}
	s := napping.Session{Timeout: 30, Datatype: "json"}
	s.SetHeader("merchantname", Pt.merchantname)
	s.SetHeader("merchantcode", Pt.merchantcode)
	res := LoginchkResult{}
	resp, err := s.Put2(Pt.MethodUrl+"/player/killsession", &p, nil, &res, nil)
	if err != nil {
		if resp != nil {
			common.Log.Err("PT EditAccount method Connection err ", resp.RawText())
		}
		common.Log.Err("PT EditAccount method Connection err ", err)
		return false, err
	}
	if res.Code == "0" {
		return true, nil
	} else {
		common.Log.Err("PT KillSession method Connection err ", resp.RawText(), err)
		return false, err
	}
	return false, err

}

func checkUsername(username string) bool {
	return true
}

func checkPassword(password string) bool {
	return true
}
