package ag

import (
	//"fmt"

	//"bytes"
	//"crypto/sha256"
	//"crypto/tls"
	//"io"
	//"os"
	"strings"
	"time"

	//"bufio"
	"common"
	//"core/goftp"
	"core/napping"
	"errors"
	"utility"
)

type AsiaGaming struct {
	Cagent      string
	MD5_key     string
	DES_key     string
	MethodUrl   string
	GcMethodUrl string
}

/**
 *
 * 从ini文件读取数据
 * @param  {[type]} ) (website      string, keyb string, uppername string [description]
 * @return {[type]}   [description]
 */
func get_auth_from_ini() (cagent, url, gcurl, md5key, deskey string) {
	cagent = common.Cfg.Section("ag").Key("Cagent").MustString("E59_AGIN")
	md5key = common.Cfg.Section("ag").Key("MD5_key").MustString("w5tb3y76u5")
	deskey = common.Cfg.Section("ag").Key("DES_key").MustString("g5i34yb6")
	url = common.Cfg.Section("ag").Key("MethodUrl").MustString("http://gci.pkgamings.com:81")
	gcurl = common.Cfg.Section("ag").Key("GcMethodUrl").MustString("http://gi.pkgamings.com:81")
	return
}

func AsiaGamingInit() *AsiaGaming {
	ag := new(AsiaGaming)
	//获取配置文件
	ag.Cagent, ag.MethodUrl, ag.GcMethodUrl, ag.MD5_key, ag.DES_key = get_auth_from_ini()
	return ag
}

//检测并创建游戏账号(CheckOrCreateGameAccout)
func (ag *AsiaGaming) CheckOrCreateGameAccout(loginname, password, oddtype, actype, cur string) (bool, error) {
	//doBusiness.do
	/*“cagent=XXXXXXXXX/\\\\/loginname=XXXXXX/\\\\/method=l
	  g/\\\\/actype=0/\\\\/password=XXXXXXXX/\\\\/oddtype=XXX/\\\\/cur=XXX”*/
	if !checkUsername(loginname) {
		return false, errors.New("username no right")
	}
	if !checkPassword(password) {
		return false, errors.New("password no right")
	}
	if len(oddtype) == 0 {
		oddtype = "A"
	}
	if len(actype) == 0 {
		actype = "0"
	}
	if len(cur) == 0 {
		cur = "CNY"
	}
	params := make([]string, 7)
	params[0] = "cagent=" + ag.Cagent
	params[1] = "loginname=" + loginname
	params[2] = "method=lg"
	params[3] = "actype=" + actype
	params[4] = "password=" + password
	params[5] = "oddtype=" + oddtype
	params[6] = "cur=" + cur

	q_params, err := utility.DesEncrypt([]byte(strings.Join(params, "/\\\\/")), []byte(ag.DES_key))
	if err != nil {
		return false, err
	}
	key := common.Md5(string(q_params) + ag.MD5_key)
	//common.Log.Info("CheckOrCreateGameAccout :", strings.Join(params, "/\\\\/"), "  ", string(q_params), " ", key)
	p := napping.Params{"params": string(q_params), "key": key}
	s := napping.Session{Timeout: 40, Datatype: "xml"}
	s.SetHeader("User-Agent", "WEB_LIB_GI_"+ag.Cagent)
	res := ResultData{}
	_, err = s.Get(ag.GcMethodUrl+"/doBusiness.do", &p, &res, nil)
	if err != nil {
		common.Log.Err("AG CheckOrCreateGameAccout method Connection err ", err)
		return false, err
	}
	//common.Log.Info("CheckOrCreateGameAccout", resp.RawText())
	////<?xml version="1.0" encoding="utf-8"?><result info="error" msg="Ip Is Not Allowed!"/>
	if res.Info == "0" {
		return true, nil
	} else if res.Info == "account_add_fail" {
		return false, nil
	} else {
		err = errors.New(res.Msg)
	}
	return false, err
}

//查询余额(GetBalance)
func (ag *AsiaGaming) GetBalance(loginname, password, actype, cur string) (float64, error) {
	if !checkUsername(loginname) {
		return 0, errors.New("username no right")
	}
	if !checkPassword(password) {
		return 0, errors.New("password no right")
	}
	if len(actype) == 0 {
		actype = "0"
	}
	if len(cur) == 0 {
		cur = "CNY"
	}
	/*
		“cagent=XXXXXXXX/\\\\/loginname=XXXXXXX/\\\\/method=gb/\\\\/actype=0/\\\\/password=XXXXXXXX/\\\\/cur=XXX”
	*/
	params := make([]string, 6)
	params[0] = "cagent=" + ag.Cagent
	params[1] = "loginname=" + loginname
	params[2] = "method=gb"
	params[3] = "actype=" + actype
	params[4] = "password=" + password
	params[5] = "cur=" + cur

	q_params, err := utility.DesEncrypt([]byte(strings.Join(params, "/\\\\/")), []byte(ag.DES_key))
	if err != nil {
		return 0, err
	}
	key := common.Md5(string(q_params) + ag.MD5_key)
	//common.Log.Info("GetBalance :", string(q_params), " ", key)
	p := napping.Params{"params": string(q_params), "key": key}
	s := napping.Session{Timeout: 40, Datatype: "xml"}
	s.SetHeader("User-Agent", "WEB_LIB_GI_"+ag.Cagent)
	res := ResultData{}
	_, err = s.Get(ag.GcMethodUrl+"/doBusiness.do", &p, &res, nil)
	if err != nil {
		common.Log.Err("OG GetBalance method Connection err ", err)
		return 0, err
	}
	//common.Log.Info("GetBalance", resp.RawText())
	if len(res.Msg) > 0 {
		return 0, errors.New(res.Msg)
	}

	return money(res.Info)
}

/*
params=des.encrypt(“cagent=XXXXXXX/\\\\/method=tc/\\\\/loginname=XXXXXXX
/\\\\/billno=XXXXXXXXXXXXXXXXXXXX/\\\\/type=IN/\\\\/credit=000.00/\\\\/actype=0/\\\\/password=XXXXXXXX/\\\\/cur=XXX”);
*/
//预备转账(PrepareTransferCredit)
func (ag *AsiaGaming) PrepareTransferCredit(loginname, password, billno string, credit float64, mtype, actype, cur string) (bool, error) {
	//spalyers.com
	if !checkUsername(loginname) {
		return false, errors.New("username no right")
	}
	if !checkPassword(password) {
		return false, errors.New("password no right")
	}
	if len(billno) < 16 {
		return false, errors.New("billno muset min 16 bit")
	}
	if mtype != "IN" && mtype != "OUT" {
		return false, errors.New("type must is 'IN' or 'OUT'")
	}
	if credit < 0 {
		return false, errors.New("credit must more than 0.00")
	}
	if len(actype) == 0 {
		actype = "1"
	}
	if len(cur) == 0 {
		cur = "CNY"
	}

	params := make([]string, 9)
	params[0] = "cagent=" + ag.Cagent
	params[1] = "loginname=" + loginname
	params[2] = "method=tc"
	params[3] = "actype=" + actype
	params[4] = "password=" + password
	params[5] = "cur=" + cur
	params[6] = "type=" + mtype
	params[7] = "billno=" + billno
	params[8] = "credit=" + utility.ToStr(credit, 2, 64)

	q_params, err := utility.DesEncrypt([]byte(strings.Join(params, "/\\\\/")), []byte(ag.DES_key))
	if err != nil {
		return false, err
	}
	//common.Log.Info(strings.Join(params, "/\\\\/"))
	key := common.Md5(string(q_params) + ag.MD5_key)
	p := napping.Params{"params": string(q_params), "key": key}
	s := napping.Session{Timeout: 40, Datatype: "xml"}
	s.SetHeader("User-Agent", "WEB_LIB_GI_"+ag.Cagent)
	res := ResultData{}
	_, err = s.Get(ag.GcMethodUrl+"/doBusiness.do", &p, &res, nil)
	if err != nil {
		common.Log.Err("AG PrepareTransferCredit method Connection err ", err)
		return false, err
	}
	//common.Log.Info("AG PrepareTransferCredit:", resp.RawText())
	if res.Info == "0" {
		return true, nil
	} else {
		err = errors.New(res.Msg)
	}
	return false, err
}

/*
params=des.encrypt(“cagent=XXXXXX/\\\\/loginname=XXXXXX/\\\\/method=tcc/\\
\\/billno=XXXXXXXXXXXXXXXXX/\\\\/type=IN/\\\\/credit=000.00/\\\\/actype=0/\\\\/fl
ag=1/\\\\/password=XXXXXXXX/\\\\/cur=XXX”);
*/
//转账确认(TransferCreditConfirm)
func (ag *AsiaGaming) TransferCreditConfirm(loginname, password, billno string, credit float64, mtype, actype, cur string, flag bool) (bool, error) {
	if !checkUsername(loginname) {
		return false, errors.New("username no right")
	}
	if !checkPassword(password) {
		return false, errors.New("password no right")
	}
	if len(billno) < 16 {
		return false, errors.New("billno muset min 16 bit")
	}
	if mtype != "IN" && mtype != "OUT" {
		return false, errors.New("type must is 'IN' or 'OUT'")
	}
	if credit < 0 {
		return false, errors.New("credit must more than 0.00")
	}
	if len(actype) == 0 {
		actype = "1"
	}
	if len(cur) == 0 {
		cur = "CNY"
	}

	params := make([]string, 10)
	params[0] = "cagent=" + ag.Cagent
	params[1] = "loginname=" + loginname
	params[2] = "method=tcc"
	params[3] = "actype=" + actype
	params[4] = "password=" + password
	params[5] = "cur=" + cur
	params[6] = "type=" + mtype
	params[7] = "billno=" + billno
	params[8] = "credit=" + utility.ToStr(credit, 2, 64)
	if flag {
		params[9] = "flag=1"
	} else {
		params[9] = "flag=0"
	}

	q_params, err := utility.DesEncrypt([]byte(strings.Join(params, "/\\\\/")), []byte(ag.DES_key))
	if err != nil {
		return false, err
	}
	//common.Log.Info(strings.Join(params, "/\\\\/"))
	key := common.Md5(string(q_params) + ag.MD5_key)
	p := napping.Params{"params": string(q_params), "key": key}
	s := napping.Session{Timeout: 40, Datatype: "xml"}
	s.SetHeader("User-Agent", "WEB_LIB_GI_"+ag.Cagent)
	res := ResultData{}
	_, err = s.Get(ag.GcMethodUrl+"/doBusiness.do", &p, &res, nil)
	if err != nil {
		common.Log.Err("AG PrepareTransferCredit method Connection err ", err)
		return false, err
	}
	//common.Log.Info("AG PrepareTransferCredit:", resp.RawText())

	if res.Info == "0" {
		return true, nil
	} else {
		err = errors.New(res.Msg)
	}
	return false, err
}

/*
params=des.encrypt(cagent=XXXXXXXX/\\\\/billno=XXXXXXX/\\\\/method=qos/\\\\/actype=0/\\\\/cur=XXX”);
*/
//查询订单状态(QueryOrderStatus)
func (ag *AsiaGaming) QueryOrderStatus() {

}

/*
 params=des.encrypt(“cagent=81288128/\\\\/loginname=vtest1/\\\\/actype=0
 /\\\\/password=XXXXXX/\\\\/dm=www.xxx.com/\\\\/sid=XXXXXXXX1023456789098/\\\\/lang=1/\\\\/gameType=1/\\\\/oddtype=XXX/\\\\/cur=XXX”);
*/
//跳转到游戏页面，进入游戏(forwardGame)
func (ag *AsiaGaming) ForwardGame(loginname, password, dm, gameType, actype, lang, oddtype, cur string) (string, error) {
	///forwardGame.do
	if !checkUsername(loginname) {
		return "", errors.New("username no right")
	}
	if !checkPassword(password) {
		return "", errors.New("password no right")
	}
	if len(lang) == 0 {
		lang = "1"
	}
	if len(actype) == 0 {
		actype = "1"
	}
	if len(cur) == 0 {
		cur = "CNY"
	}
	if len(oddtype) == 0 {
		oddtype = "A"
	}
	billno := time.Now().Format("0102150405.000000")
	//去掉小数点
	billno = strings.Replace(billno, ".", "", 10)

	params := make([]string, 10)
	params[0] = "cagent=" + ag.Cagent
	params[1] = "loginname=" + loginname
	params[2] = "dm=" + dm
	params[3] = "actype=" + actype
	params[4] = "password=" + password
	params[5] = "cur=" + cur
	params[6] = "sid=" + ag.Cagent + billno
	params[7] = "lang=" + lang
	params[8] = "gameType=" + gameType
	params[9] = "oddtype=" + oddtype

	q_params, err := utility.DesEncrypt([]byte(strings.Join(params, "/\\\\/")), []byte(ag.DES_key))
	if err != nil {
		return "", err
	}
	key := common.Md5(string(q_params) + ag.MD5_key)
	url := ag.MethodUrl + "/forwardGame.do?params=" + string(q_params) + "&key=" + key
	//common.Log.Info("AG ForwardGame:", url)
	/*
		sHtml := "<form id='orientalgamesubmit' name='orientalgamesubmit' action='" + url + "' method='post'>"
		//submit按钮控件请不要含有name属性
		sHtml = sHtml + "<input style='display: none;' type='submit' value='submit'></form>"
		sHtml = sHtml + "<script>document.forms['orientalgamesubmit'].submit();</script>"
	*/
	return url, nil
}

//跳转到游戏页面，进入游戏(ForwardGameDz) 电子游戏
func (ag *AsiaGaming) ForwardGameDz(loginname, password, dm, gameType, actype, lang, oddtype, cur string) (string, error) {
	///forwardGame.do
	if !checkUsername(loginname) {
		return "", errors.New("username no right")
	}
	if !checkPassword(password) {
		return "", errors.New("password no right")
	}
	if len(lang) == 0 {
		lang = "1"
	}
	if len(actype) == 0 {
		actype = "1"
	}
	if len(cur) == 0 {
		cur = "CNY"
	}
	if len(oddtype) == 0 {
		oddtype = "A"
	}
	billno := time.Now().Format("0102150405.000000")
	//去掉小数点
	billno = strings.Replace(billno, ".", "", 10)

	params := make([]string, 10)
	params[0] = "cagent=" + ag.Cagent
	params[1] = "loginname=" + loginname
	params[2] = "dm=" + dm
	params[3] = "actype=" + actype
	params[4] = "password=" + password
	params[5] = "cur=" + cur
	params[6] = "sid=" + ag.Cagent + billno
	params[7] = "lang=" + lang
	params[8] = "gameType=" + gameType
	params[9] = "oddtype=" + oddtype

	q_params, err := utility.DesEncrypt([]byte(strings.Join(params, "/\\\\/")), []byte(ag.DES_key))
	if err != nil {
		return "", err
	}
	key := common.Md5(string(q_params) + ag.MD5_key)
	url := ag.MethodUrl + "/forwardGame.do?params=" + string(q_params) + "&key=" + key
	return url, nil
}

//ftp获取报告
/*
func Getlogfile() error {

	src_path := "/AGIN/20150521/201505210540.xml"
	dst_path := "./ag_report/20150521/201505210540.xml"

	f1 := goftp.InitFtpBase("ftp.agingames.com:21", "E59.pkbet", "E&GZnLR7^ff", "/", nil)
	err := f1.Conn()
	if err != nil {
		common.Log.Err("AG ftp :", err)
		return err
	}

	defer f1.Close()

	err = f1.GetFile(src_path, dst_path)
	if err != nil {
		common.Log.Err("AG ftp :", err)
		return err
	}
	//解析xml
	//ioutil.ReadAll
	inputFile, inputError := os.Open(dst_path) //变量指向os.Open打开的文件时生成的文件句柄
	if inputError != nil {
		fmt.Printf("An error occurred on opening the inputfile\n")
		return inputError
	}
	defer inputFile.Close()

	inputReader := bufio.NewReader(inputFile)
	for {
		inputString, readerError := inputReader.ReadString('\n')
		if readerError == io.EOF {
			break
		}
		//解析这行的数据

		if strings.Index(inputString, `dataType="BR"`) > 0 { //下注记录

		} else if strings.Index(inputString, `dataType="EBR"`) > 0 { //电子记录

		} else if strings.Index(inputString, `dataType="TR"`) > 0 { //转账记录

		} else if strings.Index(inputString, `dataType="GR"`) > 0 { //游戏结果

		}
	}

	/*
		f := goftp.FtpOpe{}
		js, err := f.FtpWalkDir("ftp.agingames.com:21", "E59.pkbet", "E&GZnLR7^ff", "/AGIN")

		fmt.Println(js)
		if err != nil {
			common.Log.Err("AG ftp :", err)

		}
	*
	return nil
}
*/
/*
//产生
func Getbillno(isbbin bool) {
	billno := time.Now().Format("0102150405.000000")
}
*/

func money(balance string) (float64, error) {
	balance = strings.Trim(balance, " ")

	return utility.StrTo(balance).Float64()
}

func checkUsername(username string) bool {
	return true
}

func checkPassword(password string) bool {
	return true
}
