package og

import (
	"app/models"
	"common"
	"core/napping"
	"errors"
	"strings"
	"utility"

	"encoding/xml"
)

/*
public $agent = "og013dai";
public $UserKey = "jka#bn@013%daiu098";
public $testUrl = "http://cashapi.pkgamings.com/cashapi/DoBusiness.aspx";
public $betUrl = "http://cashapi.pkgamings.com/cashapi/GetData.aspx";


$this->agent = 'dailishang';
$this->UserKey = '1111'; //
$this->testUrl = 'http://cashapi.ss838.com/cashapi/DoBusiness.aspx';
$this->betUrl = "http://cashapi.ss838.com/cashapi/GetData.aspx";
*/

var (
	ErrNotExistSiteid = errors.New("not exist site id")
)

type OrientalGame struct {
	AgentName  string
	Userkey    string
	MethodUrl  string
	GetDataUrl string
	SiteId     string
}

/**
 *
 * 从ini文件读取数据
 * @param  {[type]} ) (website      string, keyb string, uppername string [description]
 * @return {[type]}   [description]
 */
func get_auth_from_ini() (url, userkey, dataurl string) {
	//agentname = common.Cfg.Section("og").Key("AgentName").MustString("og013dai")
	url = common.Cfg.Section("og").Key("MethodUrl").MustString("http://cashapi.pkgamings.com/cashapi/DoBusiness.aspx")
	dataurl = common.Cfg.Section("og").Key("GetDataUrl").MustString("http://cashapi.pkgamings.com/cashapi/GetData.aspx")
	userkey = common.Cfg.Section("og").Key("Userkey").MustString("jka#bn@013%daiu098")
	return
}

func OrientalGameInit(siteid string) (*OrientalGame, error) {
	og := new(OrientalGame)

	og.SiteId = siteid
	//获取配置文件
	og.MethodUrl, og.Userkey, og.GetDataUrl = get_auth_from_ini()

	site, err := models.GetSiteById(siteid)
	if err != nil {
		common.Log.Err("OG MGameInit sql err:", err)
		return nil, ErrNotExistSiteid
	}
	//获取配置文件
	og.AgentName = site.Og_AgentName
	if len(og.AgentName) == 0 {
		return nil, nil
	}

	return og, nil
}

//客户是否存在
func (og *OrientalGame) CheckAccountIsExist(username string) (bool, error) {
	if !checkUsername(username) {
		return false, errors.New("username no right")
	}
	params := utility.Base64Encode("agent=" + og.AgentName + "$username=" + username + "$method=caie")
	key := common.Md5(params + og.Userkey)
	p := napping.Params{"params": params, "key": key}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := ResultData{}
	_, err := s.Get(og.MethodUrl, &p, &res, nil)
	if err != nil {
		common.Log.Err("OG CheckAccountIsExist method Connection err ", err)
		return false, err
	}
	//common.Log.Info("CheckAccountIsExist", resp.RawText())
	if res.Value == "1" {
		return true, nil
	} else if res.Value == "0" {
		err = errors.New("The customer not exist")
	} else if res.Value == "10" {
		err = errors.New("The agent not exist")
	} else if res.Value == "key_error" {
		err = errors.New("The key value is error")
	} else if res.Value == "network_error" {
		err = errors.New("Network has some problem,lost information")
	} else {
		err = errors.New("unknown error")
	}
	return false, err
}

//(检测并创建游戏账号)
func (og *OrientalGame) CheckAndCreateAccount(username, password, moneysort, limit, limitvideo, limitroulette string) (string, error) {
	if !checkUsername(username) {
		return "", errors.New("username no right")
	}
	if !checkPassword(password) {
		return "", errors.New("password no right")
	}
	if len(limit) == 0 {
		limit = "1,1,1,1,1,1,1,1,1,1,1,1,1,1"
	}
	if len(moneysort) == 0 {
		moneysort = "RMB"
	}
	if len(limitvideo) == 0 {
		limitvideo = "3"
	}
	if len(limitroulette) == 0 {
		limitroulette = "5"
	}
	params := utility.Base64Encode("agent=" + og.AgentName + "$username=" + username + "$password=" + password +
		"$moneysort=" + moneysort + "$limit=" + limit + "$limitvideo=" + limitvideo +
		"$limitroulette=" + limitroulette + "$method=caca")
	key := common.Md5(params + og.Userkey)
	p := napping.Params{"params": params, "key": key}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := ResultData{}
	_, err := s.Get(og.MethodUrl, &p, &res, nil)
	if err != nil {
		common.Log.Err("OG CheckAndCreateAccount method Connection err ", err)
		return "", err
	}
	//common.Log.Info("CheckAndCreateAccount", resp.RawText())
	if res.Value == "1" {
		return "1", nil
	} else if res.Value == "0" {
		return "0", nil
	} else if res.Value == "2" {
		err = errors.New("the password no right")
	} else if res.Value == "10" {
		err = errors.New("The agent not exist")
	} else if res.Value == "key_error" {
		err = errors.New("The key value is error")
	} else if res.Value == "network_error" {
		err = errors.New("Network has some problem,lost information")
	} else {
		err = errors.New("unknown error")
	}

	return "", err
}

//更新限红
func (og *OrientalGame) UpdateLimit(username, limit, limittype string) (bool, error) {

	//agent=XXXXXXXX$username =vtest1$limit=xxx$limittype=xxx$method=ul
	params := utility.Base64Encode("agent=" + og.AgentName + "$username=" + username+ "$limit=" + 
		limit+ "$limittype=" + limittype + "$method=ul")
	key := common.Md5(params + og.Userkey)
	p := napping.Params{"params": params, "key": key}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := ResultData{}
	resp, err := s.Get(og.MethodUrl, &p, &res, nil)
	if err != nil {
		common.Log.Err("OG UpdateLimit method Connection err ", err)
		return false, err
	}
	//common.Log.Info("CheckAndCreateAccount", resp.RawText())
	if res.Value == "1" {
		return true, nil
	} else {
		err = errors.New("unknown error")
		common.Log.Err("OG UpdateLimit method txt ", resp.RawText())
	}

	return false, err
}

//GetBalance（查询余额）
func (og *OrientalGame) GetBalance(username, password string) (float64, error) {
	if !checkUsername(username) {
		return 0, errors.New("username no right")
	}
	if !checkPassword(password) {
		return 0, errors.New("password no right")
	}
	params := utility.Base64Encode("agent=" + og.AgentName + "$username=" + username + "$password=" + password + "$method=gb")
	key := common.Md5(params + og.Userkey)
	p := napping.Params{"params": params, "key": key}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := ResultData{}
	resp, err := s.Get(og.MethodUrl, &p, &res, nil)
	if err != nil {
		common.Log.Err("OG GetBalance method Connection err ", err)
		return 0, err
	}
	var balance float64
	balance, err = money(res.Value)
	if err == nil {
		return balance, nil
	}
	if res.Value == "Account_no_exist" {
		err = errors.New("The username which you want to query credit is not exist")
	} else if res.Value == "10" {
		err = errors.New("The agent not exist")
	} else if res.Value == "key_error" {
		err = errors.New("The key value is error")
	} else if res.Value == "network_error" {
		err = errors.New("Network has some problem,lost information")
	} else {
		common.Log.Info("GetBalance", resp.RawText())
		err = errors.New("unknown error")
	}

	return 0, err
}

//PrepareTransferCredit (预备转账)
func (og *OrientalGame) TransferCredit(username, password, billno, ctype, credit string) (bool, error) {
	if !checkUsername(username) {
		return false, errors.New("username no right")
	}
	if !checkPassword(password) {
		return false, errors.New("password no right")
	}
	params := utility.Base64Encode("agent=" + og.AgentName + "$username=" + username +
		"$password=" + password + "$billno=" + billno + "$type=" + ctype + "$credit=" + credit + "$method=ptc")
	key := common.Md5(params + og.Userkey)
	p := napping.Params{"params": params, "key": key}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := ResultData{}
	_, err := s.Get(og.MethodUrl, &p, &res, nil)
	if err != nil {
		common.Log.Err("OG TransferCredit method Connection err ", err)
		return false, err
	}
	//common.Log.Info("PrepareTransferCredit", resp.RawText())
	if res.Value == "1" {
		return true, nil
	} else if res.Value == "no_enough_credit" {
		err = errors.New("Not enough money to withdraw")
	} else if res.Value == "account_no_exist" {
		err = errors.New("The username which you want to query credit is not exist")
	} else if res.Value == "10" {
		err = errors.New("The agent not exist")
	} else if res.Value == "key_error" {
		err = errors.New("The key value is error")
	} else if res.Value == "network_error" {
		err = errors.New("Network has some problem,lost information")
	} else {
		err = errors.New("unknown error")
	}
	return false, err
}

//ConfirmTransferCredit (确认转账) .
func (og *OrientalGame) ConfirmTransferCredit(username, password, billno, ctype, credit string) (bool, error) {
	if !checkUsername(username) {
		return false, errors.New("username no right")
	}
	if !checkPassword(password) {
		return false, errors.New("password no right")
	}
	params := utility.Base64Encode("agent=" + og.AgentName + "$username=" + username +
		"$password=" + password + "$billno=" + billno + "$type=" + ctype + "$credit=" + credit + "$method=ctc")
	key := common.Md5(params + og.Userkey)
	p := napping.Params{"params": params, "key": key}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := ResultData{}
	_, err := s.Get(og.MethodUrl, &p, &res, nil)
	if err != nil {
		common.Log.Err("OG ConfirmTransferCredit method Connection err ", err)
		return false, err
	}
	//common.Log.Info("ConfirmTransferCredit", resp.RawText())
	if res.Value == "1" {
		return true, nil
	} else if res.Value == "0" {
		err = errors.New("invalid transfer Credit,not allowed to transfer")
	} else if res.Value == "account_no_exist" {
		err = errors.New("The username which you want to query credit is not exist")
	} else if res.Value == "10" {
		err = errors.New("The agent not exist")
	} else if res.Value == "key_error" {
		err = errors.New("The key value is error")
	} else if res.Value == "network_error" {
		err = errors.New("Network has some problem,lost information")
	} else {
		err = errors.New("unknown error")
	}
	return false, err
}

//TransferGame (进入游戏)
func (og *OrientalGame) TransferGame(username, password, domain string) (string, error) {
	if !checkUsername(username) {
		return "", errors.New("username no right")
	}
	if !checkPassword(password) {
		return "", errors.New("password no right")
	}
	params := utility.Base64Encode("agent=" + og.AgentName + "$username=" + username + "$password=" + password +
		"$domain=" + domain + "$gametype=1$gamekind=0$dataid=1$platformname=Oriental$method=tg")
	key := common.Md5(params + og.Userkey)
	url := og.MethodUrl + "?params=" + params + "&key=" + key

	sHtml := "<form id='orientalgamesubmit' name='orientalgamesubmit' action='" + url + "' method='post'>"
	//submit按钮控件请不要含有name属性
	sHtml = sHtml + "<input style='display: none;' type='submit' value='submit'></form>"
	sHtml = sHtml + "<script>document.forms['orientalgamesubmit'].submit();</script>"
	return sHtml, nil
}

//GetReport (获取报表数据)
func (og *OrientalGame) GetReport(username, password, datestart, dateend string) (string, error) {
	if !checkUsername(username) {
		return "", errors.New("username no right")
	}
	if !checkPassword(password) {
		return "", errors.New("password no right")
	}
	if len(datestart) == 0 {
		return "", errors.New("datestart no empty")
	}
	if len(dateend) == 0 {
		return "", errors.New("dateend no empty")
	}
	/*
		agent=XXXXXXXX$username=vtest1$password=XXXXXXXX$datestart=XXXX-XX-XX$dateend=XXXX-XX-XX$method=gr
		'agent=' . $this->agent . '$username=' . $username . '$password=' . $password . '$datestart=' . $datestart . '$dateend=' . $dateend . '$method=gr'
	*/
	params := utility.Base64Encode("agent=" + og.AgentName + "$username=" + username + "$password=" + password +
		"$datestart=" + datestart + "$dateend=" + dateend + "$method=gr")
	key := common.Md5(params + og.Userkey)
	p := napping.Params{"params": params, "key": key}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := ResultData{}
	_, err := s.Get(og.GetDataUrl, &p, &res, nil)
	if err != nil {
		common.Log.Err("OG GetReport method Connection err ", err)
		return "", err
	}
	//common.Log.Info("GetReport", resp.RawText())

	if res.Value == "1" {
		return "", nil
	} else if res.Value == "0" {
		err = errors.New("invalid transfer Credit,not allowed to transfer")
	} else if res.Value == "account_no_exist" {
		err = errors.New("The username which you want to query credit is not exist")
	} else if res.Value == "10" {
		err = errors.New("The agent not exist")
	} else if res.Value == "key_error" {
		err = errors.New("The key value is error")
	} else if res.Value == "network_error" {
		err = errors.New("Network has some problem,lost information")
	} else {
		err = errors.New("unknown error")
	}
	return "", err
}

//GetCashTrade (转帐记录)
func (og *OrientalGame) GetCashTrade(vendorid string) (*CashRecordData, error) {

	//agent=XXXXXXXX$vendorid=XXXX $method=gct
	params := utility.Base64Encode("agent=" + og.AgentName + "$vendorid=" + vendorid + "$method=gct")
	key := common.Md5(params + og.Userkey)
	p := napping.Params{"params": params, "key": key}
	s := napping.Session{Timeout: 30, Datatype: "xml"}

	res := &CashRecordData{}
	_, err := s.Get(og.GetDataUrl, &p, &res, nil)
	if err != nil {
		common.Log.Err("OG GetCashTrade method Connection err ", err)
		return res, err
	}
	//common.Log.Info("GetCashTrade", resp.RawText())

	if len(res.CashRecord) > 0 {
		return res, err
	} else {
		return res, errors.New("unknown error")
	}
}

//GetBettingRecord(游戏记录)
func (og *OrientalGame) GetBettingRecord(vendorid string) (*BetRecordData, error) {
	params := utility.Base64Encode("agent=" + og.AgentName + "$vendorid=" + vendorid + "$method=gbrbv")
	//common.Log.Info("GetBettingRecord", "agent="+og.AgentName+"$vendorid="+vendorid+"$method=gbrbv")
	key := common.Md5(params + og.Userkey)
	p := napping.Params{"params": params, "key": key}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := &BetRecordData{}
	resp, err := s.Get(og.GetDataUrl, &p, &res, nil)
	if err != nil {
		common.Log.Err("OG GetBettingRecord method Connection err ", err)
		if resp != nil {
			common.Log.Info("GetBettingRecord", resp.RawText())
		}
		return res, err
	}
    //common.Log.Info("GetBettingRecord", resp.RawText())
	if len(res.BetRecord) > 0 {
		return res, err
	} else {
		return res, errors.New("unknown error")
	}
}

func (og *OrientalGame) GetBettingRecord_test(vendorid string) (BetRecordData, error) {
	res := BetRecordData{}
	err := xml.Unmarshal([]byte(betrecord_str), &res)
	return res, err
}

//GetGameResult(游戏结果记录)
func (og *OrientalGame) GetGameResult(tableid, stage, inning, ascordesc, currentpage, pagesize int, datestart, dateend string) (*GameRecordData, error) {
	//“agent=XXXXXXXX$tableid=X$stage=X$inning=X$ datestart=XXXXXX-XX$dateend=XXXX-XX-XX$ascordesc =X$currentpage =X$pagesize =X$method=ggr”
	//
	params := utility.Base64Encode("agent=" + og.AgentName + "$tableid=" + utility.ToStr(tableid, 10) +
		"$stage=" + utility.ToStr(stage, 10) + "$inning=" + utility.ToStr(inning, 10) + "$datestart=" + datestart + "$dateend=" + dateend +
		"$ascordesc=" + utility.ToStr(ascordesc, 10) + "$currentpage=" + utility.ToStr(currentpage, 10) + "$pagesize=" + utility.ToStr(pagesize, 10) + "$method=ggr")
	key := common.Md5(params + og.Userkey)
	p := napping.Params{"params": params, "key": key}
	s := napping.Session{Timeout: 30, Datatype: "xml"}
	res := &GameRecordData{}
	_, err := s.Get(og.GetDataUrl, &p, &res, nil)
	if err != nil {
		common.Log.Err("OG GetGameResult method Connection err ", err)
		return res, err
	}
	//common.Log.Info("GetGameResult", resp.RawText())

	if len(res.GameRecord) > 0 {
		return res, err
	} else {
		return res, errors.New("unknown error")
	}
}

//UpdateAccount (帐户密码更改)
//UpdateLimit (帐户限红及游戏权限更改)
func money(balance string) (float64, error) {
	balance = strings.Trim(balance, " ")
	arr := strings.Split(balance, ".")
	if len(arr) == 2 {
		return utility.StrTo(balance).Float64()
	}
	return 0, errors.New("balance is not number")
}

func checkUsername(username string) bool {
	return true
}

func checkPassword(password string) bool {
	return true
}
