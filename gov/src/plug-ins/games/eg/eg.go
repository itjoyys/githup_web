package eg

import (
	"common"
	"core/napping"
	"time"
//"utility"
	"app/models"
	"errors"
)

var (
	ErrNotExistSiteid = errors.New("not exist site id")
)

//自动的电子
type LocalDz struct {
	Uppername   string //接入商ID
	SwUppername string //接入商ID
	Sign        string //签名
	ApiUrl      string //地址
	SwUrl       string
	IsSw        bool
	Site_id     string
}

func get_auth_from_ini() (uppername, sign, url, swurl string) {
	uppername = common.Cfg.Section("localdz").Key("Uppername").MustString("a1002")
	sign = common.Cfg.Section("localdz").Key("Sign").MustString("79E0C78FF1CAE38BD395FED0B13AC333")
	url = common.Cfg.Section("localdz").Key("ApiUrl").MustString("http://114.119.41.33/Game")
	swurl = common.Cfg.Section("localdz").Key("SwUrl").MustString("http://114.119.41.33/Game")
	return
}

func LocalDzInit(siteid, sw string) (*LocalDz, error) {
	dz := new(LocalDz)
	//获取配置文件
	dz.SwUppername, dz.Sign, dz.ApiUrl, dz.SwUrl = get_auth_from_ini()
	dz.IsSw = false
	if sw == "1" {
		dz.IsSw = true
	}
	site, err := models.GetSiteById(siteid)
	if err != nil {
		common.Log.Err("bbin BbinInit sql err:", err)
		return nil, ErrNotExistSiteid
	}
	//获取配置文件
	dz.Uppername = site.Eg_Agentname
	dz.Site_id = siteid
	if len(dz.Uppername) == 0 && len(dz.SwUppername) == 0{
		return nil, nil
	}
	return dz, nil
}

//创建用户
func (dz *LocalDz) CreateMember(username, pwd string) (int, error) {
	sign := common.Md5(username + pwd + dz.Sign + time.Now().Format("2006-01-02"))
	common.Log.Err(username, pwd, dz.Sign, time.Now().Format("2006-01-02"), sign)
	//p := napping.Params{"username": username, "uppername": dz.Uppername, "password": pwd, "sign": sign}
	url := dz.ApiUrl
	upname := dz.Uppername
	if dz.IsSw {
		url = dz.SwUrl
		upname = dz.SwUppername
	}
	p := napping.Params{"param": `{"username":"` + username + `","uppername":"` +
	upname + `","password":"` + pwd + `","sign":"` + sign + `"}`}
	s := napping.Session{Timeout: 30, Datatype: "json"}
	res := ComResult{}

	resp, err := s.Get(url + "/createMember.action", &p, &res, nil)
	if err != nil {
		common.Log.Err("dz CreateMember method Connection err", err)
		if resp != nil {
			common.Log.Err("dz CreateMember method Connection err body", resp.RawText())
		}
		return -99, err
	}
	common.Log.Err("dz CreateMember method Connection err body", resp.RawText())

	return res.ResultCode, nil
}

//http://114.119.41.33/Game/login.action
//登录
func (dz *LocalDz) Login(username, pwd, gameid string) string {
	sign := common.Md5(username + pwd + dz.Sign + time.Now().Format("2006-01-02"))
	/*
		u, _ := url.Parse(b.ApiUrl + "/login.action")
		q := u.Query()
		q.Set("username", username)
		q.Set("uppername", dz.Uppername)
		q.Set("password", pwd)
		q.Set("sign", dz.Sign)
		u.RawQuery = q.Encode()
	*/
	url := dz.ApiUrl
	upname := dz.Uppername
	if dz.IsSw {
		url = dz.SwUrl
		upname = dz.SwUppername
	}
	param := `{"username":"` + username + `","uppername":"` +
	upname + `","password":"` + pwd + `","gameId":"` + gameid + `","sign":"` + sign + `"}`
	html := `<html>
<head><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>eg电子</title></head><body><form name="form1" style="display: none;" action="` + url + `/login" method="post">
    <input type="text" name="param" value='` + param + `'/></form><script language="javascript">document.form1.submit()</script></body></html>`
	////
	return html
}
/*
func (dz *LocalDz) GetBetRecords(uppernames string, id int64) (*[]BetRecord, error) {
	//Md5(id+uppername+appKey+”yyyy-MM-dd”)

	sign := common.Md5(utility.ToStr(id) + uppernames + dz.Sign + time.Now().Format("2006-01-02"))
	p := napping.Params{"param": `{"id":"` + utility.ToStr(id) + `","uppername":"` +
	uppernames + `","sign":"` + sign + `"}`}
	s := napping.Session{Timeout: 30, Datatype: "json"}
	res := BetRecordResult{}
	url := dz.ApiUrl
	if dz.IsSw {
		url = dz.SwUrl
	}
	resp, err := s.Get(url + "/querybet", &p, &res, nil)
	if err != nil {
		common.Log.Err("dz GetBetRecords method Connection err", err)
		if resp != nil {
			common.Log.Err("dz GetBetRecords method Connection err body", resp.RawText())
		}
		return nil, err
	}
	common.Log.Err("dz GetBetRecords method Connection err body", resp.RawText())
	common.Log.Err("dz GetBetRecords method Connection err BetList", res.BetList)
	return &res.BetList, nil
}
*/