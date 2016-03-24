package bbin

import (
	"app/models"
	//"app/models/bbin"
	"common"
	"core/napping"
	"utility"

	"errors"
	"net/url"
	"strings"
	"time"

	//"fmt"
)

/**
 * bbin api类
 */
type Bbin struct {
	Website    string     //网站名称
	KeyB       [10]string //验证码(需全小写)
	Uppername  string     //上层帐号
	Lang       string
	MethodUrl  string
	MethodUrl1 string
	Site_id    string
}

/*
1‧CreateMember → KeyB : avtoReb4
2‧Login → KeyB : kfRpUEo36H
3‧Logout → KeyB : xnRTK
4‧CheckUsrBalance → KeyB : 7xy194M7
5‧Transfer → KeyB : 1944vm
6‧CheckTransfer → KeyB : 52EM2
7‧TransferRecord → KeyB : 52EM2
8‧BetRecord → KeyB : oabm0M
9‧BetRecordByModifiedDate3 → KeyB : oabm0M
10‧GetJPHistory→ KeyB : oabm0M
*/

var (
	ErrNotExistSiteid = errors.New("not exist site id")
)

func get_auth_from_ini() (website, domain, domain1 string, keyb [10]string) {
	website = common.Cfg.Section("bbin").Key("website").MustString("fashion")
	domain = common.Cfg.Section("bbin").Key("domain").MustString("888.pk-bbin.com")
	domain1 = common.Cfg.Section("bbin").Key("domain1").MustString("linkapi.pk-bbin.com")
	keyb[0] = common.Cfg.Section("bbin").Key("keyb1").MustString("avtoReb4")
	keyb[1] = common.Cfg.Section("bbin").Key("keyb2").MustString("kfRpUEo36H")
	keyb[2] = common.Cfg.Section("bbin").Key("keyb3").MustString("xnRTK")
	keyb[3] = common.Cfg.Section("bbin").Key("keyb4").MustString("7xy194M7")
	keyb[4] = common.Cfg.Section("bbin").Key("keyb5").MustString("1944vm")
	keyb[5] = common.Cfg.Section("bbin").Key("keyb6").MustString("52EM2")
	keyb[6] = common.Cfg.Section("bbin").Key("keyb7").MustString("52EM2")
	keyb[7] = common.Cfg.Section("bbin").Key("keyb8").MustString("oabm0M")
	keyb[8] = common.Cfg.Section("bbin").Key("keyb9").MustString("oabm0M")
	keyb[9] = common.Cfg.Section("bbin").Key("keyb10").MustString("oabm0M")
	return
}

func BbinInit(siteid string) (*Bbin, error) {
	bbin := new(Bbin)
	domain := ""
	domain1 := ""
	//获取配置文件
	bbin.Website, domain, domain1, bbin.KeyB = get_auth_from_ini()
	bbin.MethodUrl = "http://" + domain + "/app/WebService/JSON/display.php/"
	bbin.MethodUrl1 = "http://" + domain1 + "/app/WebService/JSON/display.php/"
	//fmt.Println(bbin.KeyB)
	site, err := models.GetSiteById(siteid)
	if err != nil {
		common.Log.Err("bbin BbinInit sql err:", err)
		return nil, ErrNotExistSiteid
	}
	//获取配置文件
	bbin.Uppername = site.Bbin_AgentName
	bbin.Site_id = siteid
	if len(bbin.Uppername) == 0 {
		return nil, nil
	}

	return bbin, nil
}

/**
 * 这里的str代表的api中KeyB前面所需要的字符串
 */
func get_authkey(str string, keyb string, leftnum int, rightnum int) string {
	head_str := string(utility.RandomCreateBytes(leftnum))

	md5_str := common.Md5(str + keyb + time.Now().Format("20060102"))

	foot_str := string(utility.RandomCreateBytes(rightnum))

	return strings.ToLower(head_str + md5_str + foot_str)
}

/**
 * API呼叫方式
JSON (http://您的网址/app/WebService/JSON/display.php/API名称?GET参数)
XML (http://您的网址/app/WebService/XML/display.php/API名称?GET参数)
POST：
表单(form)post
action-http://您的网址/app/WebService/JSONorXML/display.php/API名称
input-各参数
*/

/**
  key=A+B+C(验证码组合方式)
  A= 无意义字串长度5码
  B=MD5(website+ username + KeyB + YYYYMMDD)
  C=无意义字串长度7码
  YYYYMMDD为美东时间(GMT-4)(20140528)
  password 6-12个字
*/
//新增会员
func (b *Bbin) CreateMember(username string, password string) (string, error) {
	//生成key
	key_str := get_authkey(b.Website+username, b.KeyB[0], 1, 5)
	//生成链接
	p := napping.Params{
		"website":   b.Website,
		"username":  username,
		"uppername": b.Uppername,
		"password":  password,
		"key":       strings.ToLower(key_str),
	}
	s := napping.Session{Timeout: 30, Datatype: "json"}
	jsonresult := CommonResult{}
	resp, err := s.Get(b.MethodUrl1+"CreateMember", &p, &jsonresult, nil)
	if err != nil {
		common.Log.Err("BBIN CreateMember :", err)
		if resp != nil {
			common.Log.Err("BBIN CreateMember :", resp.RawText())
		}
		return "00", err
	}

	//common.Log.Info("BBIN CreateMember return json :", resp.RawText())

	if jsonresult.Data.Code == "21001" {
		return "21001", err
	}

	if jsonresult.Data.Code == "21100" {
		return "21100", nil
	}

	return "00", errors.New(jsonresult.Data.Message)
}

//会员登入
func (b *Bbin) Login(username, password, lang, page_site string) string {
	//生成key
	key_str := get_authkey(b.Website+username, b.KeyB[1], 9, 4)
	//生成链接
	u, _ := url.Parse(b.MethodUrl + "Login")
	q := u.Query()
	q.Set("website", b.Website)
	q.Set("username", username)
	q.Set("uppername", b.Uppername)
	q.Set("password", password)
	q.Set("lang", lang)
	q.Set("page_site", page_site)
	q.Set("key", strings.ToLower(key_str))
	u.RawQuery = q.Encode()
	//res, err := http.Get(u.String())

	return u.String()
}

//会员登出
func (b *Bbin) Logout(username string) error {
	//生成key
	key_str := get_authkey(b.Website+username, b.KeyB[2], 8, 6)
	//生成链接
	p := napping.Params{
		"website":  b.Website,
		"username": username,
		"key":      strings.ToLower(key_str),
	}
	s := napping.Session{Timeout: 30, Datatype: "json"}
	jsonresult := CommonResult{}
	resp, err := s.Get(b.MethodUrl+"Logout", &p, &jsonresult, nil)
	if err != nil {
		common.Log.Err("BBIN Logout :", err)
		return err
	}

	common.Log.Info("BBIN Logout return json :", resp)

	if jsonresult.Data.Code == "22001" {
		return nil
	}

	return errors.New(jsonresult.Data.Message)
}

//查询会员额度
func (b *Bbin) CheckUsrBalance(username string, page, pagelimit int64) (*CheckUsrBalanceData, error) {
	//生成key
	key_str := get_authkey(b.Website+username, b.KeyB[3], 8, 7)
	//生成链接
	p := napping.Params{
		"website":   b.Website,
		"username":  username,
		"uppername": b.Uppername,
		//"page":      utility.ToStr(page),
		//"pagelimit": utility.ToStr(pagelimit),
		"key": strings.ToLower(key_str),
	}
	s := napping.Session{Timeout: 30, Datatype: "json"}
	jsonresult := CheckUsrBalanceResult{}
	resp, err := s.Get(b.MethodUrl1+"CheckUsrBalance", &p, &jsonresult, nil)
	if err != nil {
		common.Log.Err("BBIN CheckUsrBalance :", err)
		if resp != nil {
			common.Log.Err("BBIN CheckUsrBalance :", resp.RawText())
		}
		return nil, err
	}

	//common.Log.Info("BBIN CheckUsrBalance return json :", resp.RawText())

	if jsonresult.Result {
		return &jsonresult.Data[0], nil
	}

	return nil, errors.New("unknow error")
}

//转帐
func (b *Bbin) Transfer(username, action string, remitno string, remit float64) error {
	//生成key
	key_str := get_authkey(b.Website+username+remitno, b.KeyB[4], 6, 9)
	//生成链接
	p := napping.Params{
		"website":   b.Website,
		"username":  username,
		"uppername": b.Uppername,
		"remitno":   remitno,
		"action":    action,
		"remit":     utility.ToStr(remit),
		"key":       strings.ToLower(key_str),
	}
	s := napping.Session{Timeout: 30, Datatype: "json"}
	jsonresult := CommonResult{}
	resp, err := s.Get(b.MethodUrl1+"Transfer", &p, &jsonresult, nil)
	if err != nil {
		common.Log.Err("BBIN Transfer :", err)
		if resp != nil {
			common.Log.Err("BBIN CheckTransfer :", resp.RawText())
		}
		return err
	}

	//common.Log.Info("BBIN Transfer return json :", resp.RawText())

	if jsonresult.Data.Code == "11100" {
		return nil
	}

	return errors.New(jsonresult.Data.Message)
}

//查询会员存提是否成功
func (b *Bbin) CheckTransfer(transid string) int {
	//生成key
	key_str := get_authkey(b.Website, b.KeyB[5], 5, 6)
	//生成链接
	p := napping.Params{
		"website": b.Website,
		"transid": transid,
		"key":     key_str,
	}
	s := napping.Session{Timeout: 30, Datatype: "json"}
	jsonresult := CheckTransferResult{}
	resp, err := s.Get(b.MethodUrl1+"CheckTransfer", &p, &jsonresult, nil)
	if err != nil {
		common.Log.Err("BBIN CheckTransfer :", err)
		if resp != nil {
			common.Log.Err("BBIN CheckTransfer :", resp.RawText())
		}
		return 0
	}

	//common.Log.Info("BBIN CheckTransfer return json :", resp.RawText())

	return jsonresult.Data.Status
}

//查询会员存提记录
func (b *Bbin) TransferRecord(date_start, date_end string, page int64) (*TransferRecordData, error) {
	//生成key
	key_str := get_authkey(b.Website, b.KeyB[6], 5, 6)
	p := napping.Params{
		"website":    b.Website,
		"uppername":  b.Uppername,
		"date_start": date_start,
		"date_end":   date_end,
		"page":       utility.ToStr(page),
		"pagelimit":  "500",
		"key":        key_str,
	}
	s := napping.Session{Timeout: 30, Datatype: "json"}
	jsonresult := TransferRecordResult{}
	resp, err := s.Get(b.MethodUrl1+"TransferRecord", &p, &jsonresult, nil)
	if err != nil {
		common.Log.Err("BBIN TransferRecord :", err)
		if resp != nil {
			common.Log.Err("BBIN CheckTransfer :", resp.RawText())
		}
		return nil, err
	}

	//common.Log.Info("BBIN TransferRecord return json :", resp.RawText())
	if jsonresult.Result {
		return &jsonresult.Data, nil
	}

	return nil, errors.New("unknow error")
}

//下注记录
func (b *Bbin) BetRecord(ipaddr, rounddate, starttime, endtime, gamekind, gametype, subgamekind string, page int) (*BetRecordResult, error) {
	//生成key
	key_str := get_authkey(b.Website, b.KeyB[7], 6, 8)
	p := napping.Params{}
	if gamekind == "12" {
		p = napping.Params{
			"website":     b.Website,
			"uppername":   b.Uppername,
			"rounddate":   rounddate,
			"starttime":   starttime,
			"endtime":     endtime,
			"gamekind":    gamekind,
			"gametype":    gametype,
			"subgamekind": "1",
			"page":        utility.ToStr(page),
			"pagelimit":   "500",
			"key":         key_str,
		}
	} else {
		p = napping.Params{
			"website":     b.Website,
			"uppername":   b.Uppername,
			"rounddate":   rounddate,
			"starttime":   starttime,
			"endtime":     endtime,
			"gamekind":    gamekind,
			"subgamekind": subgamekind,
			"page":        utility.ToStr(page),
			"pagelimit":   "500",
			"key":         key_str,
		}
	}
	/*
		BetRecord : subgamekind=1
		{網址}/app/WebService/JSON/display.php/BetRecord?
		website={$website}&
		username=&
		uppername={$uppername}&
		rounddate=2015-08-31&
		starttime=&
		endtime=&
		gamekind=5&
		subgamekind=1&
		gametype=&
		page=&
		pagelimit=&
		key={$key}
	*/
	common.Log.Err("BBIN BetRecord :", p)
	s := napping.Session{Timeout: 30, Ipaddr: ipaddr, Datatype: "json"}
	jsonresult := BetRecordResult{}
	resp, err := s.Get(b.MethodUrl1+"BetRecord", &p, &jsonresult, nil)
	if err != nil {
		common.Log.Err("BBIN BetRecord :", err)
		if resp != nil {
			common.Log.Err("BBIN BetRecord :", resp.RawText())
		}
		return nil, err
	}

	//common.Log.Info("BBIN BetRecord return json :", resp.RawText())

	if jsonresult.Result {
		return &jsonresult, nil
	}

	return nil, errors.New("unknow error")
}

//下注记录(注单变更时间)(不分体系、限定5分钟)
func (b *Bbin) BetRecordByModifiedDate3(date_start, date_end, gamekind, gametype, subgamekind string, page int) (*BetRecordResult, error) {
	//生成key
	key_str := get_authkey(b.Website, b.KeyB[8], 6, 8)

	p := napping.Params{}
	if gamekind == "12" {
		p = napping.Params{
			"website":     b.Website,
			"start_date":  date_start,
			"end_date":    date_start,
			"starttime":   "00:00:00",
			"endtime":     "23:59:59",
			"gamekind":    gamekind,
			"gametype":    gametype,
			"subgamekind": "1",
			"page":        utility.ToStr(page),
			"key":         key_str,
		}
	} else {
		p = napping.Params{
			"website":     b.Website,
			"start_date":  date_start,
			"end_date":    date_start,
			"starttime":   "00:00:00",
			"endtime":     "23:59:59",
			"gamekind":    gamekind,
			"subgamekind": subgamekind,
			"page":        utility.ToStr(page),
			"key":         key_str,
		}
	}
	//common.Log.Err("BBIN BetRecordByModifiedDate3 :", p)
	s := napping.Session{Timeout: 30, Datatype: "json"}
	jsonresult := BetRecordResult{}
	resp, err := s.Get(b.MethodUrl1+"BetRecordByModifiedDate3", &p, &jsonresult, nil)
	if err != nil {
		common.Log.Err("BBIN BetRecordByModifiedDate3 :", err)
		if resp != nil {
			common.Log.Err("BBIN BetRecordByModifiedDate3 :", resp.RawText())
		}
		return nil, err
	}

	//common.Log.Info("BBIN BetRecordByModifiedDate3 return json :", resp.RawText())

	if jsonresult.Result {
		return &jsonresult, nil
	}

	return nil, errors.New("unknow error")
}

//JP开奖历史纪录
func (b *Bbin) GetJPHistory(date_start, date_end string, page int64) (*GetJPHistoryData, error) {
	//生成key
	key_str := get_authkey(b.Website, b.KeyB[9], 6, 8)

	p := napping.Params{
		"website":    b.Website,
		"uppername":  b.Uppername,
		"start_date": date_start,
		"end_date":   date_end,
		"starttime":  "00:00:00",
		"endtime":    "23:59:59",
		"page":       utility.ToStr(page),
		"pagelimit":  "500",
		"key":        key_str,
	}
	s := napping.Session{Timeout: 30, Datatype: "json"}
	jsonresult := GetJPHistoryResult{}
	resp, err := s.Get(b.MethodUrl1+"GetJPHistory", &p, &jsonresult, nil)
	if err != nil {
		common.Log.Err("BBIN GetJPHistory :", err)
		if resp != nil {
			common.Log.Err("BBIN BetRecord :", resp.RawText())
		}
		return nil, err
	}

	common.Log.Info("BBIN GetJPHistory return json :", resp.RawText())

	if jsonresult.Result {
		return &jsonresult.Data, nil
	}

	return nil, errors.New("unknow error")
}
