package mg

import (
	"app/models"
	"common"
	"errors"
	"strings"
	"time"
	_ "utility"

	"encoding/xml"
)

type MGame struct {
	SiteId    string
	AgentName string
	Userkey   string
	MethodUrl string
}

var (
	ErrHeaderIsNil    = errors.New("soap header is nil")
	ErrNotExistSiteid = errors.New("not exist site id")
	ErrEmptyUsername  = errors.New("username is empty")
)

/*
总代登录名: zongdai172150密码: Pk7890oK
代理登录名: daili172152密码: Pk7890dloK
这是MG的账号密码
mg使用的时区是GTM  我们使用的是GTM-4
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <AgentSession xmlns="https://entservices.totalegame.net">
      <SessionGUID>guid</SessionGUID>
      <ErrorCode>int</ErrorCode>
      <IPAddress>string</IPAddress>
      <IsExtendSession>boolean</IsExtendSession>
    </AgentSession>
  </soap:Header>
  <soap:Body>
    <IsAccountAvailable xmlns="https://entservices.totalegame.net">
      <accountNumber>string</accountNumber>
    </IsAccountAvailable>
  </soap:Body>
</soap:Envelope>
*/

/**
 *
 * 从ini文件读取数据
 * @param  {[type]} ) (website      string, keyb string, uppername string [description]
 * @return {[type]}   [description]
 */
func get_auth_from_ini() (url string) {
	url = common.Cfg.Section("Mg").Key("MethodUrl").MustString("https://entservices.totalegame.net?wsdl")
	return
}

func MGameInit(siteid string) (*MGame, error) {
	mg := new(MGame)
	mg.SiteId = siteid
	mg.MethodUrl = get_auth_from_ini()

	site, err := models.GetSiteById(siteid)
	if err != nil {
		common.Log.Err("MG MGameInit sql err:", err)
		return nil, ErrNotExistSiteid
	}
	//获取配置文件
	mg.AgentName = site.Mg_AgentName
	mg.Userkey = site.Mg_AgentPWD
	if len(mg.AgentName) == 0 || len(mg.Userkey) == 0 {
		return nil, nil
	}
	//bbin.Key =
	return mg, nil
}

/*
<soap:Envelope
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xmlns:xsd="http://www.w3.org/2001/XMLSchema"
xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
<Envelope
xmlns="http://schemas.xmlsoap.org/soap/envelope/"
xmlns:envelope="http://schemas.xmlsoap.org/soap/envelope/"
envelope:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
<Body xmlns="http://schemas.xmlsoap.org/soap/envelope/">
<IsAuthenticate xmlns="https://entservices.totalegame.net">
<loginName>Jugame5888</loginName><pinCode>58450928</pinCode>
</IsAuthenticate>
</Body>
</Envelope>
*/

/**
 soapRequest := `<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" `
	for key, namespace := range namespaces {
		soapRequest += "xmlns:" + key + "=\"" + namespace
	}
	soapRequest += `"><soapenv:Header/><soapenv:Body>` + "\n"
	soapRequest += buffer.String()
	soapRequest += `</soapenv:Body></soapenv:Envelope>`
*/

//IsAuthenticate 获取sessionid
/**
 * 是否强制跟新sesson
 */
func (mg *MGame) isAuthenticate() error {

	params := struct {
		XMLName   xml.Name `xml:"https://entservices.totalegame.net IsAuthenticate"`
		LoginName string   `xml:"loginName"`
		PinCode   string   `xml:"pinCode"`
	}{LoginName: mg.AgentName, PinCode: mg.Userkey}

	body, err := GetAuth(mg.MethodUrl, params)
	if err != nil {
		return err
	}
	common.Log.Err(string(body))

	//body := []byte(`<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><IsAuthenticateResponse xmlns="https://entservices.totalegame.net"><IsAuthenticateResult><SessionGUID>a56ae9f0-89c5-4dbe-b9bd-e9017eab4cee</SessionGUID><ErrorCode>0</ErrorCode><IPAddress>210.56.56.107</IPAddress><IsExtendSession>true</IsExtendSession><IsSucceed>true</IsSucceed></IsAuthenticateResult></IsAuthenticateResponse></soap:Body></soap:Envelope>`)
	jsonMap := ResultData{}

	err = xml.Unmarshal(body, &jsonMap)
	if err != nil {
		return err
	}
	data := AuthenticateResponseBodyData{}
	err = xml.Unmarshal([]byte(jsonMap.Body), &data)
	if err != nil {
		return err
	}
	session := new(MgHeader)
	session.ErrorCode = data.AuthenticateResponse.AuthenticateResult.ErrorCode
	session.Expired = time.Now().Unix()
	session.IPAddress = data.AuthenticateResponse.AuthenticateResult.IPAddress
	session.IsExtendSession = data.AuthenticateResponse.AuthenticateResult.IsExtendSession
	session.SessionGUID = data.AuthenticateResponse.AuthenticateResult.SessionGUID
	setheader(mg.SiteId, session)
	//MgSessions[mg.SiteId] = session

	return nil
}

/**
<soap:Body>
<GetCurrenciesForAddAccountResponse xmlns="https://entservices.totalegame.net">
<GetCurrenciesForAddAccountResult>
<Currency>
<IsDefaultProduct>true</IsDefaultProduct>
<ProductType>Casino</ProductType>
<CurrencyId>8</CurrencyId>
<IsoName>Chinese Yuan</IsoName>
<IsoCode>CNY</IsoCode>
</Currency>
</GetCurrenciesForAddAccountResult>
</GetCurrenciesForAddAccountResponse>
</soap:Body>
*/
func (mg *MGame) GetCurrenciesForAddAccount() error {
	sess, err := getheader(mg.SiteId) //MgSessions[mg.SiteId]
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return err
		}
	}
	params := struct {
		XMLName xml.Name `xml:"https://entservices.totalegame.net GetCurrenciesForAddAccount"`
	}{}
	//action string, header SoapHeader, soapbody interface{}
	_, err = SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/GetCurrenciesForAddAccount", sess, params)
	if err != nil {
		return err
	}
	//common.Log.Err(string(body))
	return nil
}

/*
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><GetBettingProfileListResponse xmlns="https://entservices.totalegame.net">
<GetBettingProfileListResult>
<Profile>
<Id>563</Id>
<Name>CNY5-1 LB 10/500 100/5000 1000/50000  LR 1/2 5/4 10/5 BJ 10/5000 100/10000</Name>
<Description>CNY5-1 LB 10/500 100/5000 1000/50000  LR 1/2 5/4 10/5 BJ 10/5000 100/10000</Description>
</Profile>
<Profile>
<Id>564</Id>
<Name>CNY5-2 LB 100/10000 1000/100000 10000/200000  LR 10/2 50/4 100/5 BJ 200/20000 500/50000 </Name>
<Description>CNY5-2 LB 100/10000 1000/100000 10000/200000  LR 10/2 50/4 100/5 BJ 200/20000 500/50000</Description>
</Profile>
</GetBettingProfileListResult>
</GetBettingProfileListResponse>
</soap:Body>
</soap:Envelope>]
*/
//获取游戏列表
func (mg *MGame) GetBettingProfileList() error {
	sess, err := getheader(mg.SiteId) //MgSessions[mg.SiteId]
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return err
		}
	}
	params := struct {
		XMLName xml.Name `xml:"https://entservices.totalegame.net GetBettingProfileList"`
	}{}
	//action string, header SoapHeader, soapbody interface{}
	_, err = SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/GetBettingProfileList", sess, params)
	if err != nil {
		return err
	}
	//common.Log.Err(string(body))

	return nil
}

/*
	  <accountNumber>string</accountNumber>
      <password>string</password>
      <firstName>string</firstName>
      <lastName>string</lastName>
      <currency>int</currency>
      <mobileNumber>string</mobileNumber>
      <isSendGame>boolean</isSendGame>
      <email>string</email>
      <BettingProfileId>int</BettingProfileId>
      <moblieGameLanguageId>int</moblieGameLanguageId>
      <isProgressive>boolean</isProgressive>


      <?xml version="1.0" encoding="utf-8"?>
      <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
      <soap:Body>
      <AddAccountResponse xmlns="https://entservices.totalegame.net">
      <AddAccountResult>
      <ErrorMessage />
      <IsSucceed>true</IsSucceed>
      <ErrorCode>0</ErrorCode>
      <ErrorId />
      <CustomerId>6666614</CustomerId>
      <LockAccountStatus>Open</LockAccountStatus>
      <SuspendAccountStatus>Open</SuspendAccountStatus>
      <CasinoId>2635</CasinoId>
      <AccountNumber>usdfsdfsdfiiue</AccountNumber>
      <PinCode>888988984</PinCode>
      <FirstName>usdfsd</FirstName>
      <LastName>fsdfiiue</LastName>
      <MobileNumber />
      <ProfileId>563</ProfileId>
      <IsProgressive>false</IsProgressive>
      </AddAccountResult></AddAccountResponse>
      </soap:Body></soap:Envelope>]

*/
func (mg *MGame) AddAccount(name, password, firstname, lastname string) (*AddAccountResult, error) {
	sess, err := getheader(mg.SiteId) //MgSessions[mg.SiteId]
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return nil,err
		}
	}
	//BettingProfileId使用默认值
	params := struct {
		XMLName              xml.Name `xml:"https://entservices.totalegame.net AddAccount"`
		AccountNumber        string   `xml:"accountNumber"`
		Password             string   `xml:"password"`
		FirstName            string   `xml:"firstName"`
		LastName             string   `xml:"lastName"`
		Currency             int      `xml:"currency"`
		MobileNumber         string   `xml:"mobileNumber"`
		IsSendGame           bool     `xml:"isSendGame"`
		Email                string   `xml:"email"`
		BettingProfileId     int      `xml:"BettingProfileId"`
		MoblieGameLanguageId int      `xml:"moblieGameLanguageId"`
		IsProgressive        bool     `xml:"isProgressive"`
	}{AccountNumber: name, Password: password, FirstName: firstname, LastName: lastname, Currency: 8, IsSendGame: false, BettingProfileId: 0}
	//action string, header SoapHeader, soapbody interface{}
	body, err := SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/AddAccount", sess, params)
	if err != nil {
		mg.isAuthenticate()
		return nil, err
	}
	//common.Log.Info(string(body))
	if strings.Index(string(body), "session has expired") > 0 {
		mg.isAuthenticate() //重新获取session
	}
	jsonMap := ResultData{}

	err = xml.Unmarshal(body, &jsonMap)
	if err != nil {
		return nil, err
	}
	data := AddAccountResponseBodyData{}
	err = xml.Unmarshal([]byte(jsonMap.Body), &data)
	if err != nil {
		return nil, err
	}
	if data.AddAccountResponse.AddAccountResult.ErrorCode == 0 {
		return &data.AddAccountResponse.AddAccountResult, nil
	} else {
		return &data.AddAccountResponse.AddAccountResult, errors.New(data.AddAccountResponse.AddAccountResult.ErrorMessage)
	}
}

//EditAccount
/*
<EditAccountResponse xmlns="https://entservices.totalegame.net">
      <EditAccountResult>
        <CustomerId>int</CustomerId>
        <LockAccountStatus>Unknown or Open or Lock or PendingToDelete or Deleted or CHANetWinBalanceLock or Suspended or LockedByDatacash or NetworkLock or PendingToNetworkLock or NetworkSuspend or PendingToNetworkSuspend or PendingToNetworkOpen</LockAccountStatus>
        <SuspendAccountStatus>Unknown or Open or Suspended</SuspendAccountStatus>
        <CasinoId>int</CasinoId>
        <AccountNumber>string</AccountNumber>
        <PinCode>string</PinCode>
        <FirstName>string</FirstName>
        <LastName>string</LastName>
        <NickName>string</NickName>
        <MobileNumber>string</MobileNumber>
        <ProfileId>int</ProfileId>
        <EMail>string</EMail>
        <IsProgressive>boolean</IsProgressive>
        <RngBettingProfileId>int</RngBettingProfileId>
      </EditAccountResult>
    </EditAccountResponse>
*/
func (mg *MGame) EditAccount(name, password string) (bool, error) {
	sess, err := getheader(mg.SiteId) //MgSessions[mg.SiteId]
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return false,err
		}
	}
	params := struct {
		XMLName          xml.Name `xml:"https://entservices.totalegame.net EditAccount"`
		AccountNumber    string   `xml:"accountNumber"`
		Password         string   `xml:"password"`
		FirstName        string   `xml:"firstName"`
		LastName         string   `xml:"lastName"`
		MobileNumber     string   `xml:"mobileNumber"`
		BettingProfileId int64    `xml:"bettingProfileId"`
		EMail            string   `xml:"eMail"`
	}{AccountNumber: name, Password: password, FirstName: "", LastName: "", MobileNumber: "", BettingProfileId: 0, EMail: ""}
	body, err := SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/EditAccount", sess, params)
	if err != nil {
		return false, err
	}
	common.Log.Err(string(body))

	jsonMap := ResultData{}

	err = xml.Unmarshal(body, &jsonMap)
	if err != nil {
		return false, err
	}
	data := EditAccountResponseBodyData{}
	err = xml.Unmarshal([]byte(jsonMap.Body), &data)
	if err != nil {
		return false, err
	}
	if data.EditAccountResponse.EditAccountResult.AccountNumber == name {
		return true, nil
	}

	return false, nil

}

/*
<accountNumber>string</accountNumber>
<password>string</password>
<playCheckType>Station or Player</playCheckType>
<language>string</language>

获取用户登录日志
*/
func (mg *MGame) GetPlaycheckUrl(name, password, lang string) (string, error) {
	sess, err := getheader(mg.SiteId) //MgSessions[mg.SiteId]
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return "",err
		}
	}
	params := struct {
		XMLName       xml.Name `xml:"https://entservices.totalegame.net GetPlaycheckUrl"`
		AccountNumber string   `xml:"accountNumber"`
		Password      string   `xml:"password"`
		PlayCheckType string   `xml:"playCheckType"`
		Language      string   `xml:"language"`
	}{AccountNumber: name, Password: password, PlayCheckType: "Player", Language: lang}
	body, err := SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/GetPlaycheckUrl", sess, params)
	if err != nil {
		return "", err
	}
	//common.Log.Err(string(body))

	jsonMap := ResultData{}

	err = xml.Unmarshal(body, &jsonMap)
	if err != nil {
		return "", err
	}
	data := GetPlaycheckUrlResponseBodyData{}
	err = xml.Unmarshal([]byte(jsonMap.Body), &data)
	if err != nil {
		return "", err
	}
	return data.PlaycheckUrlResponse.PlaycheckUrlResult.PlaycheckUrl, nil
}

/*
<GetCurrenciesForDepositResponse xmlns="https://entservices.totalegame.net">
<GetCurrenciesForDepositResult>
<Currency>
<IsDefaultProduct>true</IsDefaultProduct>
<ProductType>Casino</ProductType>
<CurrencyId>8</CurrencyId>
<IsoName>Chinese Yuan</IsoName>
<IsoCode>CNY</IsoCode>
</Currency>
</GetCurrenciesForDepositResult>
</GetCurrenciesForDepositResponse>
*/
func (mg *MGame) GetCurrenciesForDeposit() error {
	sess, err := getheader(mg.SiteId) //MgSessions[mg.SiteId]
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return err
		}
	}
	params := struct {
		XMLName xml.Name `xml:"https://entservices.totalegame.net GetCurrenciesForDeposit"`
	}{}
	_, err = SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/GetCurrenciesForDeposit",sess, params)
	if err != nil {
		return err
	}
	//common.Log.Err(string(body))
	/*
		jsonMap := ResultData{}
		err = xml.Unmarshal(body, &jsonMap)
		if err != nil {
			return "", err
		}
		data := GetPlaycheckUrlResponseBodyData{}
		err = xml.Unmarshal([]byte(jsonMap.Body), &data)
		if err != nil {
			return "", err
		}
	*/
	return nil
}

/**
  <GetAccountDetailsResponse xmlns="https://entservices.totalegame.net">
    <GetAccountDetailsResult>
      <AccountNumber>string</AccountNumber>
      <FirstName>string</FirstName>
      <LastName>string</LastName>
      <NickName>string</NickName>
      <PokerAlias>string</PokerAlias>
      <MobileNumber>string</MobileNumber>
      <RelatedProduct>All or Casino or Progressive or Poker or Sports or Count</RelatedProduct>
      <AccountStatus>Unknown or Open or Lock or PendingToDelete or Deleted or CHANetWinBalanceLock or Suspended or LockedByDatacash or NetworkLock or PendingToNetworkLock or NetworkSuspend or PendingToNetworkSuspend or PendingToNetworkOpen</AccountStatus>
      <SuspendAccountStatus>Unknown or Open or Suspended</SuspendAccountStatus>
      <LastEditDate>dateTime</LastEditDate>
      <EMail>string</EMail>
      <CreditBalance>decimal</CreditBalance>
      <Balance>decimal</Balance>
      <ProfileId>int</ProfileId>
    </GetAccountDetailsResult>
  </GetAccountDetailsResponse>
*/
func (mg *MGame) GetAccountDetails(username string) (*GetAccountDetailsResult, error) {
	sess, err := getheader(mg.SiteId) //MgSessions[mg.SiteId]
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return nil,err
		}
	}
	params := struct {
		XMLName       xml.Name `xml:"https://entservices.totalegame.net GetAccountDetails"`
		AccountNumber string   `xml:"accountNumber"`
	}{AccountNumber: username}
	body, err := SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/GetAccountDetails", sess, params)
	if err != nil {
		return nil, err
	}
	//common.Log.Err(string(body))
	if strings.Index(string(body), "session has expired") > 0 {
		mg.isAuthenticate() //重新获取session
	}
	jsonMap := ResultData{}
	err = xml.Unmarshal(body, &jsonMap)
	if err != nil {
		return nil, err
	}

	data := GetAccountDetailsResultBodyData{}
	err = xml.Unmarshal([]byte(jsonMap.Body), &data)
	if err != nil {
		return nil, err
	}
	//LockAccount
	return &data.GetAccountDetailsResponse.GetAccountDetailsResult, nil
}

type strAccount struct {
	Namestring string `xml:"string"`
}

//LockAccount
func (mg *MGame) LockAccount(usernames string, islock bool) (bool, error) {
	sess, err := getheader(mg.SiteId) //MgSessions[mg.SiteId]
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return false, err
		}
	}
	block := ""
	strnames := make([]strAccount, 0)
	arr_name := strings.Split(usernames, "|")
	if len(arr_name) == 0 {
		return false, ErrEmptyUsername
	}
	for _, v := range arr_name {
		strnames = append(strnames, strAccount{Namestring: v})
	}
	//True,False
	if islock {
		block = "True"
	} else {
		block = "False"
	}
	params := struct {
		XMLName     xml.Name     `xml:"https://entservices.totalegame.net LockAccount"`
		StrAccounts []strAccount `xml:"strAccounts"`
		BLock       string       `xml:"bLock"`
	}{StrAccounts: strnames, BLock: block}
	body, err := SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/LockAccount", sess, params)
	if err != nil {
		return false, err
	}
	//common.Log.Err(string(body))
	if strings.Index(string(body), "session has expired") > 0 {
		mg.isAuthenticate() //重新获取session
	}
	jsonMap := ResultData{}
	err = xml.Unmarshal(body, &jsonMap)
	if err != nil {
		return false, err
	}
	//LockAccount
	return true, nil
}

/**
<GetAccountBalanceResponse xmlns="https://entservices.totalegame.net">
<GetAccountBalanceResult>
<BalanceResult>
<IsSucceed>true</IsSucceed>
<ErrorCode>0</ErrorCode>
<TransactionAmount>0</TransactionAmount>
<TransactionCreditAmount>0</TransactionCreditAmount>
<TransactionId>0</TransactionId>
<AccountNumber>usdfsdfsdfiiue</AccountNumber>
<CreditBalance>0</CreditBalance>
<Balance>0</Balance>
</BalanceResult>
</GetAccountBalanceResult>
</GetAccountBalanceResponse>


<GetAccountBalanceResult>
<BalanceResult>
<IsSucceed>true</IsSucceed>
<ErrorCode>0</ErrorCode>
<TransactionAmount>0</TransactionAmount>
<TransactionCreditAmount>0</TransactionCreditAmount>
<TransactionId>0</TransactionId>
<AccountNumber>usdfsdfsdfiiue</AccountNumber>
<CreditBalance>21</CreditBalance>
<Balance>21</Balance>
</BalanceResult>
</GetAccountBalanceResult>
</GetAccountBalanceResponse>
*/
func (mg *MGame) GetAccountBalance(username string) ([]BalanceResult, error) {
	sess, err := getheader(mg.SiteId) //MgSessions[mg.SiteId]
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return nil,err
		}
	}
	params := struct {
		XMLName                 xml.Name `xml:"https://entservices.totalegame.net GetAccountBalance"`
		DelimitedAccountNumbers string   `xml:"delimitedAccountNumbers"`
	}{DelimitedAccountNumbers: username}
	body, err := SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/GetAccountBalance", sess, params)
	if err != nil {
		return nil, err
	}
	//common.Log.Err(string(body))
	if strings.Index(string(body), "session has expired") > 0 {
		mg.isAuthenticate() //重新获取session
	}
	jsonMap := ResultData{}
	err = xml.Unmarshal(body, &jsonMap)
	if err != nil {
		return nil, err
	}
	data := GetAccountBalanceResponseBodyData{}
	err = xml.Unmarshal([]byte(jsonMap.Body), &data)
	if err != nil {
		return nil, err
	}

	if data.GetAccountBalanceResponse.GetAccountBalanceResult.BalanceResults[0].ErrorCode == 0 &&
		data.GetAccountBalanceResponse.GetAccountBalanceResult.BalanceResults[0].CreditBalance >= 0 {
		return data.GetAccountBalanceResponse.GetAccountBalanceResult.BalanceResults, nil
	} else {
		return data.GetAccountBalanceResponse.GetAccountBalanceResult.BalanceResults, errors.New("Mg GetAccountBalance error")
	}
}

/*
<DepositResponse xmlns="https://entservices.totalegame.net">
<DepositResult>
<ErrorMessage/>
<IsSucceed>true</IsSucceed>
<ErrorCode>0</ErrorCode>
<ErrorId>SUCCEED</ErrorId>
<TransactionAmount>100</TransactionAmount>
<TransactionCreditAmount>100</TransactionCreditAmount>
<TransactionId>120412766</TransactionId>
<AccountNumber>usdfsdfsdfiiue</AccountNumber>
<CreditBalance>100</CreditBalance>
<Balance>100</Balance>
</DepositResult>
</DepositResponse>
*/
func (mg *MGame) Deposit(username string, amount float64, curid int) (*DepositResult, error) {
	sess, err := getheader(mg.SiteId) //MgSessions[mg.SiteId]
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return nil,err
		}
	}
	params := struct {
		XMLName       xml.Name `xml:"https://entservices.totalegame.net Deposit"`
		AccountNumber string   `xml:"accountNumber"`
		Amount        float64  `xml:"amount"`
		Currency      int      `xml:"currency"`
	}{AccountNumber: username, Amount: amount, Currency: curid}
	body, err := SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/Deposit",sess, params)
	if err != nil {
		common.Log.Err(string(body), err)
		return nil, err
	}
	//common.Log.Info(string(body))
	if strings.Index(string(body), "session has expired") > 0 {
		mg.isAuthenticate() //重新获取session
	}
	jsonMap := ResultData{}
	err = xml.Unmarshal(body, &jsonMap)
	if err != nil {
		return nil, err
	}
	data := DepositResponseBodyData{}
	err = xml.Unmarshal([]byte(jsonMap.Body), &data)
	if err != nil {
		return nil, err
	}

	if data.DepositResponse.DepositResult.ErrorCode == 0 {
		return &data.DepositResponse.DepositResult, nil
	} else {
		return &data.DepositResponse.DepositResult, errors.New(data.DepositResponse.DepositResult.ErrorMessage)
	}
}

/**
<WithdrawalResponse xmlns="https://entservices.totalegame.net">
      <WithdrawalResult>
        <TransactionAmount>decimal</TransactionAmount>
        <TransactionCreditAmount>decimal</TransactionCreditAmount>
        <TransactionId>int</TransactionId>
        <AccountNumber>string</AccountNumber>
        <CreditBalance>decimal</CreditBalance>
        <Balance>decimal</Balance>
      </WithdrawalResult>
    </WithdrawalResponse>
**/
func (mg *MGame) Withdrawal(username string, amount float64) (*WithdrawalResult, error) {
	sess, err := getheader(mg.SiteId) //MgSessions[mg.SiteId]
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return nil, err
		}
	}
	params := struct {
		XMLName       xml.Name `xml:"https://entservices.totalegame.net Withdrawal"`
		AccountNumber string   `xml:"accountNumber"`
		Amount        float64  `xml:"amount"`
	}{AccountNumber: username, Amount: amount}
	body, err := SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/Withdrawal", sess, params)
	if err != nil {
		common.Log.Err(string(body), err)
		return nil, err
	}
	//common.Log.Info(string(body))
	if strings.Index(string(body), "session has expired") > 0 {
		mg.isAuthenticate() //重新获取session
	}
	jsonMap := ResultData{}
	err = xml.Unmarshal(body, &jsonMap)
	if err != nil {
		return nil, err
	}
	data := WithdrawalResponseBodyData{}
	err = xml.Unmarshal([]byte(jsonMap.Body), &data)
	if err != nil {
		return nil, err
	}

	if data.WithdrawalResponse.WithdrawalResult.TransactionAmount > 0 {
		return &data.WithdrawalResponse.WithdrawalResult, nil
	} else {
		return &data.WithdrawalResponse.WithdrawalResult, errors.New(data.WithdrawalResponse.WithdrawalResult.ErrorMessage)
	}
}

/**
<dateFrom>dateTime</dateFrom> //YYYY-MM-DDThh:mm:ss
<dateTo>dateTime</dateTo>2006-01-02T15:04:05
<maxNumRowsPage>int</maxNumRowsPage>
获取游戏报表记录
*/
func (mg *MGame) GetBetInfoDetails(datefrom, dateto string, maxmunpage int) (string, error) {
	sess, err := getheader(mg.SiteId) //MgSessions[mg.SiteId]
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return "",err
		}
	}
	params := struct {
		XMLName  xml.Name `xml:"https://entservices.totalegame.net GetBetInfoDetails"`
		DateFrom string   `xml:"dateFrom"`
		DateTo   string   `xml:"dateTo"`
		MaxPage  int      `xml:"maxNumRowsPage"`
	}{DateFrom: datefrom, DateTo: dateto, MaxPage: maxmunpage}
	body, err := SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/GetBetInfoDetails",sess, params)
	if err != nil {
		//Expired
		if(strings.Index(strings.ToLower(err.Error()),"expired") > 0){
			mg.isAuthenticate() //重新获取session
		}
		return "", err
	}
	//common.Log.Err(string(body))
	if strings.Index(string(body), "session has expired") > 0 {
		mg.isAuthenticate() //重新获取session
	}
	jsonMap := ResultData{}
	err = xml.Unmarshal(body, &jsonMap)
	if err != nil {
		return "", err
	}
	data := GetBetInfoDetailsResponseBodyData{}
	err = xml.Unmarshal([]byte(jsonMap.Body), &data)
	if err != nil {
		return "", err
	}

	return data.BetInfoDetailsResponse.BetInfoDetailsResult.Guid, nil
}

//获取游戏投资记录
func (mg *MGame) GameplayDetailedReport(datefrom string, maxmunpage int) (string, error) {
	sess, err := getheader(mg.SiteId) //MgSessions[mg.SiteId]
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return "", err
		}
	}
	params := struct {
		XMLName  xml.Name `xml:"https://entservices.totalegame.net GameplayDetailedReport"`
		DateFrom string   `xml:"dateFrom"`
		MaxPage  int      `xml:"maxNumRowsPage"`
	}{DateFrom: datefrom, MaxPage: maxmunpage}
	body, err := SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/GameplayDetailedReport", sess, params)
	if err != nil {
		return "", err
	}
	//common.Log.Err(string(body))
	if strings.Index(string(body), "session has expired") > 0 {
		mg.isAuthenticate() //重新获取session
	}
	jsonMap := ResultData{}
	err = xml.Unmarshal(body, &jsonMap)
	if err != nil {
		return "", err
	}
	data := GameplayDetailedReportResponseBodyData{}
	err = xml.Unmarshal([]byte(jsonMap.Body), &data)
	if err != nil {
		return "", err
	}

	return data.GameplayDetailedReport.GameplayDetailedReportResult.Guid, nil
}

/**
<GetReportResult xmlns="https://entservices.totalegame.net">
<id>guid</id>
<nPage>int</nPage>
</GetReportResult>
*/
func (mg *MGame) GetReportResult(guid string, page int) (*GetReportResultStatus, error) {
	sess, err := getheader(mg.SiteId) //MgSessions[mg.SiteId]
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return nil, err
		}
	}
	params := struct {
		XMLName xml.Name `xml:"https://entservices.totalegame.net GetReportResult"`
		Guid    string   `xml:"id"`
		Page    int      `xml:"nPage"`
	}{Guid: guid, Page: page}
	body, err := SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/GetReportResult", sess, params)
	if err != nil {
		return nil, err
	}
	//common.Log.Info(string(body))
	if strings.Index(string(body), "session has expired") > 0 {
		mg.isAuthenticate() //重新获取session
	}
	jsonMap := GetReportResultDataStatus{}
	err = xml.Unmarshal(body, &jsonMap)
	if err != nil {
		return nil, err
	}

	return &jsonMap.GetReportResultResponseBodyDataStatus.GetReportResultResponseStatus.GetReportResultStatus, nil
}

/**
//获取用户的注单记录GetBetInfoDetailsByAccount
<reportName>string</reportName>
     <parameters>
       <item>
         <ParameterName>string</ParameterName>
         <ParameterValue>string</ParameterValue>
       </item>
       <item>
         <ParameterName>string</ParameterName>
         <ParameterValue>string</ParameterValue>
       </item>
     </parameters>
     <maxNumRowsPage>int</maxNumRowsPage>
*/
func (mg *MGame) GetBetInfoDetailsByAccount(username, fromDate, toDate string, maxNumRowsPage int) error {
	sess, err := getheader(mg.SiteId) //MgSessions[mg.SiteId]
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return err
		}
	}
	items := make([]ParametersItem, 3)
	items[0] = ParametersItem{ParameterName: "FromDate", ParameterValue: fromDate}
	items[1] = ParametersItem{ParameterName: "ToDate", ParameterValue: toDate}
	items[2] = ParametersItem{ParameterName: "AccountNumber", ParameterValue: username}
	params := struct {
		XMLName    xml.Name         `xml:"https://entservices.totalegame.net GetReportByName"`
		ReportName string           `xml:"reportName"`
		Parameters []ParametersItem `xml:"parameters"`
		MaxPage    int              `xml:"maxNumRowsPage"`
	}{ReportName: "GetBetInfoDetailsByAccount", Parameters: items, MaxPage: maxNumRowsPage}
	_, err = SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/GetReportByName", sess, params)
	if err != nil {
		return err
	}

	//common.Log.Info(string(body))
	/*
		jsonMap := ResultData{}
		err = xml.Unmarshal(body, &jsonMap)
		if err != nil {
			return "", err
		}
		data := GetPlaycheckUrlResponseBodyData{}
		err = xml.Unmarshal([]byte(jsonMap.Body), &data)
		if err != nil {
			return "", err
		}
	*/
	return nil
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
usernames  “Account001, Account002, Account003”------
*/
func (mg *MGame) APIGetBetInfoDetailsByAccount(usernames, fromDate, toDate string, maxNumRowsPage int) (*GetReportByNameResult, error) {
	sess, err := getheader(mg.SiteId) //MgSessions[mg.SiteId]
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return nil,err
		}
	}
	items := make([]ParametersItem, 3)
	items[0] = ParametersItem{ParameterName: "FromDate", ParameterValue: fromDate}
	items[1] = ParametersItem{ParameterName: "ToDate", ParameterValue: toDate}
	items[2] = ParametersItem{ParameterName: "AccountList_XML", ParameterValue: usernames}
	params := struct {
		XMLName    xml.Name   `xml:"https://entservices.totalegame.net GetReportByName"`
		ReportName string     `xml:"reportName"`
		Parameters Parameters `xml:"parameters"`
		MaxPage    int        `xml:"maxNumRowsPage"`
	}{ReportName: "APIGetBetInfoDetailsByAccount", Parameters: Parameters{Items: items}, MaxPage: maxNumRowsPage}
	body, err := SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/GetReportByName", sess, params)
	if err != nil {
		common.Log.Err(err)
		return nil, err
	}
	if strings.Index(string(body), "session has expired") > 0 {
		mg.isAuthenticate() //重新获取session
	}
	jsonMap := ResultData{}
	err = xml.Unmarshal(body, &jsonMap)
	if err != nil {
		common.Log.Err(err)
		return nil, err
	}

	data := GetReportByNameResponseBodyDataStatus{}
	err = xml.Unmarshal([]byte(jsonMap.Body), &data)
	if err != nil {
		common.Log.Err(err)
		return nil, err
	}
	if data.GetReportByNameResponse.GetReportByNameResult.Status == "Pending" {
		//加入队列
		return &data.GetReportByNameResponse.GetReportByNameResult, nil
	} else if data.GetReportByNameResponse.GetReportByNameResult.Status == "Complete" {
		//继续解析
		return &data.GetReportByNameResponse.GetReportByNameResult, nil
	} else {
		//Error
		return &data.GetReportByNameResponse.GetReportByNameResult, nil
	}
}

/**
参数
[0] "FromDate" "2011-07-01T00:00:00"
[1] "ToDate" "2011-07-20T23:59:59.997"
[2] "CurrencyRateTypeId" "1" {1-Daily, 2-Monthly}
[3] "AccountList_XML” “Accoint001, Account002, Account003” (comma-delimited Account Numbers)
[4] "GroupingType" "Gametype" {AccountNo, Gametype}
*/
//TODO游戏记录明显
func (mg *MGame) APICasinoProfitByGameTypeReport(usernames, fromDate, toDate, groupingtype, currencyRateType string, maxNumRowsPage int) (*GetReportByNameResult, error) {
	sess, err := getheader(mg.SiteId) //MgSessions[mg.SiteId]
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return nil,err
		}
	}
	items := make([]ParametersItem, 3)
	items[0] = ParametersItem{ParameterName: "FromDate", ParameterValue: fromDate}
	items[1] = ParametersItem{ParameterName: "ToDate", ParameterValue: toDate}
	if currencyRateType == "Daily" {
		items[2] = ParametersItem{ParameterName: "CurrencyRateTypeId", ParameterValue: "1"}
	} else {
		items[2] = ParametersItem{ParameterName: "CurrencyRateTypeId", ParameterValue: "2"}
	}
	items[3] = ParametersItem{ParameterName: "AccountList_XML", ParameterValue: usernames}
	items[4] = ParametersItem{ParameterName: "GroupingType", ParameterValue: groupingtype}
	params := struct {
		XMLName    xml.Name   `xml:"https://entservices.totalegame.net GetReportByName"`
		ReportName string     `xml:"reportName"`
		Parameters Parameters `xml:"parameters"`
		MaxPage    int        `xml:"maxNumRowsPage"`
	}{ReportName: "CreditTransferByAccount", Parameters: Parameters{Items: items}, MaxPage: maxNumRowsPage}
	body, err := SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/GetReportByName", sess, params)
	if err != nil {
		return nil, err
	}
	//common.Log.Err(string(body))
	if strings.Index(string(body), "session has expired") > 0 {
		mg.isAuthenticate() //重新获取session
	}
	jsonMap := ResultData{}
	err = xml.Unmarshal(body, &jsonMap)
	if err != nil {
		common.Log.Err(err)
		return nil, err
	}
	data := GetReportByNameResponseBodyDataStatus{}
	err = xml.Unmarshal([]byte(jsonMap.Body), &data)
	if err != nil {
		common.Log.Err(err)
		return nil, err
	}
	if data.GetReportByNameResponse.GetReportByNameResult.Status == "Pending" {
		//加入队列
		return &data.GetReportByNameResponse.GetReportByNameResult, nil
	} else if data.GetReportByNameResponse.GetReportByNameResult.Status == "Complete" {
		//继续解析
		return &data.GetReportByNameResponse.GetReportByNameResult, nil
	} else {
		//Error
		return &data.GetReportByNameResponse.GetReportByNameResult, nil
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
usernames 为空的时候返回所有
*/
func (mg *MGame) APIPushBackByAccountReport(usernames, fromDate, toDate string, maxNumRowsPage int) (*GetReportByNameResult, error) {
	sess, err := getheader(mg.SiteId) //
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return nil,err
		}
	}
	items := make([]ParametersItem, 3)
	items[0] = ParametersItem{ParameterName: "FromDate", ParameterValue: fromDate}
	items[1] = ParametersItem{ParameterName: "ToDate", ParameterValue: toDate}
	items[2] = ParametersItem{ParameterName: "AccountList_XML", ParameterValue: usernames}
	params := struct {
		XMLName    xml.Name   `xml:"https://entservices.totalegame.net GetReportByName"`
		ReportName string     `xml:"reportName"`
		Parameters Parameters `xml:"parameters"`
		MaxPage    int        `xml:"maxNumRowsPage"`
	}{ReportName: "APIPushBackByAccountReport", Parameters: Parameters{Items: items}, MaxPage: maxNumRowsPage}
	body, err := SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/GetReportByName", sess, params)
	if err != nil {
		return nil, err
	}
	//common.Log.Err(string(body))
	if strings.Index(string(body), "session has expired") > 0 {
		mg.isAuthenticate() //重新获取session
	}
	jsonMap := ResultData{}
	err = xml.Unmarshal(body, &jsonMap)
	if err != nil {
		common.Log.Err(err)
		return nil, err
	}

	data := GetReportByNameResponseBodyDataStatus{}
	err = xml.Unmarshal([]byte(jsonMap.Body), &data)
	if err != nil {
		common.Log.Err(err)
		return nil, err
	}
	if data.GetReportByNameResponse.GetReportByNameResult.Status == "Pending" {
		//加入队列
		return &data.GetReportByNameResponse.GetReportByNameResult, nil
	} else if data.GetReportByNameResponse.GetReportByNameResult.Status == "Complete" {
		//继续解析
		return &data.GetReportByNameResponse.GetReportByNameResult, nil
	} else {
		//Error
		return &data.GetReportByNameResponse.GetReportByNameResult, nil
	}
}

//会员转账明显
func (mg *MGame) CreditTransferByAccount(usernames, fromDate, toDate string, maxNumRowsPage int) (*GetReportByNameResult, error) {
	sess, err := getheader(mg.SiteId) //MgSessions[mg.SiteId]
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return nil,err
		}
	}
	items := make([]ParametersItem, 3)
	items[0] = ParametersItem{ParameterName: "FromDate", ParameterValue: fromDate}
	items[1] = ParametersItem{ParameterName: "ToDate", ParameterValue: toDate}
	items[2] = ParametersItem{ParameterName: "AccountList_XML", ParameterValue: usernames}
	params := struct {
		XMLName    xml.Name   `xml:"https://entservices.totalegame.net GetReportByName"`
		ReportName string     `xml:"reportName"`
		Parameters Parameters `xml:"parameters"`
		MaxPage    int        `xml:"maxNumRowsPage"`
	}{ReportName: "CreditTransferByAccount", Parameters: Parameters{Items: items}, MaxPage: maxNumRowsPage}
	body, err := SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/GetReportByName", sess, params)
	if err != nil {
		return nil, err
	}
	//common.Log.Err(string(body))
	if strings.Index(string(body), "session has expired") > 0 {
		mg.isAuthenticate() //重新获取session
	}
	jsonMap := ResultData{}
	err = xml.Unmarshal(body, &jsonMap)
	if err != nil {
		common.Log.Err(err)
		return nil, err
	}

	data := GetReportByNameResponseBodyDataStatus{}
	err = xml.Unmarshal([]byte(jsonMap.Body), &data)
	if err != nil {
		common.Log.Err(err)
		return nil, err
	}
	if data.GetReportByNameResponse.GetReportByNameResult.Status == "Pending" {
		//加入队列
		return &data.GetReportByNameResponse.GetReportByNameResult, nil
	} else if data.GetReportByNameResponse.GetReportByNameResult.Status == "Complete" {
		//继续解析
		return &data.GetReportByNameResponse.GetReportByNameResult, nil
	} else {
		//Error
		return &data.GetReportByNameResponse.GetReportByNameResult, nil
	}
}

//////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
//以下只返回guid，需要使用GetReportResult才能返回结果
//////////////////////////////////////////////////////////////////////////////////
/*
<GetReportByNameResponse xmlns="https://entservices.totalegame.net">
<GetReportByNameResult>
<IsSucceed>true</IsSucceed>
<ErrorCode>0</ErrorCode>
<SourceStatus>Normal</SourceStatus>
<ErrorValue>SUCCESS</ErrorValue>
<Status>Pending</Status>
<Id>a155d57f-c566-4690-a714-65e69641d836</Id>
</GetReportByNameResult>
</GetReportByNameResponse>
*/
func (mg *MGame) PlayerGamePlayReport(username, fromDate, toDate string, maxNumRowsPage int) (string, error) {
	sess, err := getheader(mg.SiteId) //MgSessions[mg.SiteId]
	if err != nil {
		err = mg.isAuthenticate() //获取session
		if err != nil {
			return "",err
		}
	}
	items := make([]ParametersItem, 5)
	items[0] = ParametersItem{ParameterName: "FromDate", ParameterValue: fromDate}
	items[1] = ParametersItem{ParameterName: "ToDate", ParameterValue: toDate}
	items[2] = ParametersItem{ParameterName: "PlayerGrossWinSummaryLevel", ParameterValue: "Detailed"}
	items[3] = ParametersItem{ParameterName: "TimeZoneType", ParameterValue: "own"}
	items[4] = ParametersItem{ParameterName: "EntityList_XML", ParameterValue: username}
	params := struct {
		XMLName    xml.Name   `xml:"https://entservices.totalegame.net GetReportByName"`
		ReportName string     `xml:"reportName"`
		Parameters Parameters `xml:"parameters"`
		MaxPage    int        `xml:"maxNumRowsPage"`
	}{ReportName: "PlayerGamePlayReport", Parameters: Parameters{Items: items}, MaxPage: maxNumRowsPage}
	body, err := SoapPost(mg.MethodUrl, "https://entservices.totalegame.net/GetReportByName", sess, params)
	if err != nil {
		return "", err
	}

	//common.Log.Err(string(body))
	if strings.Index(string(body), "session has expired") > 0 {
		mg.isAuthenticate() //重新获取session
	}
	jsonMap := ResultData{}
	err = xml.Unmarshal(body, &jsonMap)
	if err != nil {
		return "", err
	}
	data := PlayerGamePlayReportBodyData{}
	err = xml.Unmarshal([]byte(jsonMap.Body), &data)
	if err != nil {
		return "", err
	}

	return data.PlayerGamePlayReportResponse.PlayerGamePlayReportResult.Guid, nil
}
