package mg

import (
	"encoding/xml"

	mmg "app/models/mg"
)

type Parameters struct {
	XMLName xml.Name         `xml:"parameters"`
	Items   []ParametersItem `xml:"item"`
}

type ParametersItem struct {
	XMLName        xml.Name `xml:"item"`
	ParameterName  string   `xml:"ParameterName"`
	ParameterValue string   `xml:"ParameterValue"`
}

/*
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <IsAuthenticateResponse xmlns="https://entservices.totalegame.net">
      <IsAuthenticateResult>
        <ErrorMessage>string</ErrorMessage>
        <IsSucceed>boolean</IsSucceed>
        <ErrorId>string</ErrorId>
      </IsAuthenticateResult>
    </IsAuthenticateResponse>
  </soap:Body>
</soap:Envelope>

<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
<soap:Body>
<IsAuthenticateResponse xmlns="https://entservices.totalegame.net">
<IsAuthenticateResult>
<SessionGUID>a56ae9f0-89c5-4dbe-b9bd-e9017eab4cee</SessionGUID>
<ErrorCode>0</ErrorCode>
<IPAddress>210.56.56.107</IPAddress>
<IsExtendSession>true</IsExtendSession>
<IsSucceed>true</IsSucceed>
</IsAuthenticateResult>
</IsAuthenticateResponse>
</soap:Body></soap:Envelope>`)
*/

type ResultData struct {
	XMLName xml.Name `xml:"Envelope"`
	Body    string   `xml:",innerxml"`
}

type AuthenticateResponseBodyData struct {
	XMLName              xml.Name               `xml:"Body"`
	AuthenticateResponse IsAuthenticateResponse `xml:"IsAuthenticateResponse"`
}

type IsAuthenticateResponse struct {
	XMLName            xml.Name             `xml:"IsAuthenticateResponse"`
	AuthenticateResult IsAuthenticateResult `xml:"IsAuthenticateResult"`
}

type IsAuthenticateResult struct {
	XMLName         xml.Name `xml:"IsAuthenticateResult"`
	SessionGUID     string   `xml:"SessionGUID"`
	ErrorCode       string   `xml:"ErrorCode"`
	IPAddress       string   `xml:"IPAddress"`
	IsExtendSession bool     `xml:"IsExtendSession"`
	IsSucceed       bool     `xml:"IsSucceed"`
}

/*
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
*/
type AddAccountResponseBodyData struct {
	XMLName            xml.Name           `xml:"Body"`
	AddAccountResponse AddAccountResponse `xml:"AddAccountResponse"`
}

type AddAccountResponse struct {
	XMLName          xml.Name         `xml:"AddAccountResponse"`
	AddAccountResult AddAccountResult `xml:"AddAccountResult"`
}
type AddAccountResult struct {
	XMLName              xml.Name `xml:"AddAccountResult"`
	ErrorMessage         string   `xml:"ErrorMessage"`
	IsSucceed            bool     `xml:"IsSucceed"`
	ErrorCode            int      `xml:"ErrorCode"`
	ErrorId              string   `xml:"ErrorId"`
	CustomerId           string   `xml:"CustomerId"`
	LockAccountStatus    string   `xml:"LockAccountStatus"`
	SuspendAccountStatus string   `xml:"SuspendAccountStatus"`
	CasinoId             int      `xml:"CasinoId"`
	AccountNumber        string   `xml:"AccountNumber"`
	PinCode              string   `xml:"PinCode"`
	FirstName            string   `xml:"FirstName"`
	LastName             string   `xml:"LastName"`
	MobileNumber         string   `xml:"MobileNumber"`
	ProfileId            int      `xml:"ProfileId"`
	IsProgressive        bool     `xml:"IsProgressive"`
}

type EditAccountResponseBodyData struct {
	XMLName             xml.Name            `xml:"Body"`
	EditAccountResponse EditAccountResponse `xml:"EditAccountResponse"`
}

type EditAccountResponse struct {
	XMLName           xml.Name          `xml:"EditAccountResponse"`
	EditAccountResult EditAccountResult `xml:"EditAccountResult"`
}
type EditAccountResult struct {
	XMLName      xml.Name `xml:"EditAccountResult"`
	CustomerId           string `xml:"CustomerId"`
	LockAccountStatus    string `xml:"LockAccountStatus"`
	SuspendAccountStatus string `xml:"SuspendAccountStatus"`
	CasinoId             int    `xml:"CasinoId"`
	AccountNumber        string `xml:"AccountNumber"`
	PinCode              string `xml:"PinCode"`
	FirstName            string `xml:"FirstName"`
	LastName             string `xml:"LastName"`
	NickName             string `xml:"NickName"`
	MobileNumber         string `xml:"MobileNumber"`
	ProfileId            int    `xml:"ProfileId"`
	EMail                string `xml:"EMail"`
	IsProgressive        bool   `xml:"IsProgressive"`
	RngBettingProfileId  int    `xml:"RngBettingProfileId"`
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
type GetAccountDetailsResultBodyData struct {
	XMLName                   xml.Name                  `xml:"Body"`
	GetAccountDetailsResponse GetAccountDetailsResponse `xml:"GetAccountDetailsResponse"`
}
type GetAccountDetailsResponse struct {
	XMLName                 xml.Name                `xml:"GetAccountDetailsResponse"`
	GetAccountDetailsResult GetAccountDetailsResult `xml:"GetAccountDetailsResult"`
}
type GetAccountDetailsResult struct {
	XMLName              xml.Name `xml:"GetAccountDetailsResult"`
	AccountNumber        string   `xml:"AccountNumber"`
	FirstName            string   `xml:"AccountNumber"`
	LastName             string   `xml:"AccountNumber"`
	NickName             string   `xml:"AccountNumber"`
	PokerAlias           string   `xml:"AccountNumber"`
	MobileNumber         string   `xml:"AccountNumber"`
	RelatedProduct       string   `xml:"AccountNumber"`
	AccountStatus        string   `xml:"AccountNumber"`
	SuspendAccountStatus string   `xml:"AccountNumber"`
	LastEditDate         string   `xml:"AccountNumber"`
	EMail                string   `xml:"AccountNumber"`
	CreditBalance        float64  `xml:"AccountNumber"`
	Balance              float64  `xml:"AccountNumber"`
	ProfileId            int64    `xml:"AccountNumber"`
}

/*
<GetAccountBalanceResponse xmlns="https://entservices.totalegame.net">
            <GetAccountBalanceResult>
                <BalanceResult>
                    <IsSucceed>true</IsSucceed>
                    <ErrorCode>0</ErrorCode>
                    <TransactionAmount>0</TransactionAmount>
                    <TransactionCreditAmount>0</TransactionCreditAmount>
                    <TransactionId>0</TransactionId>
                    <AccountNumber>twwwwww10</AccountNumber>
                    <CreditBalance>40</CreditBalance>
                    <Balance>40</Balance>
                </BalanceResult>
            </GetAccountBalanceResult>
        </GetAccountBalanceResponse>
*/
type GetAccountBalanceResponseBodyData struct {
	XMLName                   xml.Name                  `xml:"Body"`
	GetAccountBalanceResponse GetAccountBalanceResponse `xml:"GetAccountBalanceResponse"`
}

type GetAccountBalanceResponse struct {
	XMLName                 xml.Name                `xml:"GetAccountBalanceResponse"`
	GetAccountBalanceResult GetAccountBalanceResult `xml:"GetAccountBalanceResult"`
}
type GetAccountBalanceResult struct {
	XMLName        xml.Name        `xml:"GetAccountBalanceResult"`
	BalanceResults []BalanceResult `xml:"BalanceResult"`
}
type BalanceResult struct {
	XMLName                 xml.Name `xml:"BalanceResult"`
	IsSucceed               bool     `xml:"IsSucceed"`
	ErrorCode               int      `xml:"ErrorCode"`
	TransactionAmount       float64  `xml:"TransactionAmount"`
	TransactionCreditAmount float64  `xml:"TransactionCreditAmount"`
	TransactionId           string   `xml:"TransactionId"`
	AccountNumber           string   `xml:"AccountNumber"`
	CreditBalance           float64  `xml:"CreditBalance"`
	Balance                 float64  `xml:"Balance"`
}

/*
<DepositResponse xmlns="https://entservices.totalegame.net">
            <DepositResult>
                <ErrorMessage />
                <IsSucceed>true</IsSucceed>
                <ErrorCode>0</ErrorCode>
                <ErrorId>SUCCEED</ErrorId>
                <TransactionAmount>20</TransactionAmount>
                <TransactionCreditAmount>20</TransactionCreditAmount>
                <TransactionId>121267292</TransactionId>
                <AccountNumber>twwwwww10</AccountNumber>
                <CreditBalance>138.5</CreditBalance>
                <Balance>138.5</Balance>
            </DepositResult>
        </DepositResponse>
*/
type DepositResponseBodyData struct {
	XMLName         xml.Name        `xml:"Body"`
	DepositResponse DepositResponse `xml:"DepositResponse"`
}

type DepositResponse struct {
	XMLName       xml.Name      `xml:"DepositResponse"`
	DepositResult DepositResult `xml:"DepositResult"`
}

type DepositResult struct {
	XMLName                 xml.Name `xml:"DepositResult"`
	ErrorMessage            string   `xml:"ErrorMessage"`
	IsSucceed               bool     `xml:"IsSucceed"`
	ErrorCode               int      `xml:"ErrorCode"`
	ErrorId                 string   `xml:"ErrorId"`
	TransactionAmount       float64  `xml:"TransactionAmount"`
	TransactionCreditAmount float64  `xml:"TransactionCreditAmount"`
	TransactionId           string   `xml:"TransactionId"`
	AccountNumber           string   `xml:"AccountNumber"`
	CreditBalance           float64  `xml:"CreditBalance"`
	Balance                 float64  `xml:"Balance"`
}

/*
<WithdrawalResponse xmlns="https://entservices.totalegame.net">
<WithdrawalResult>
<ErrorMessage/>
<IsSucceed>true</IsSucceed>
<ErrorCode>0</ErrorCode>
<ErrorId>SUCCEED</ErrorId>
<TransactionAmount>20</TransactionAmount>
<TransactionCreditAmount>20</TransactionCreditAmount>
<TransactionId>121308887</TransactionId>
<AccountNumber>twwwwww10</AccountNumber>
<CreditBalance>198.5</CreditBalance>
<Balance>198.5</Balance>
</WithdrawalResult>
</WithdrawalResponse>
    取款返回
*/
type WithdrawalResponseBodyData struct {
	XMLName            xml.Name           `xml:"Body"`
	WithdrawalResponse WithdrawalResponse `xml:"WithdrawalResponse"`
}

type WithdrawalResponse struct {
	XMLName          xml.Name         `xml:"WithdrawalResponse"`
	WithdrawalResult WithdrawalResult `xml:"WithdrawalResult"`
}

type WithdrawalResult struct {
	XMLName                 xml.Name `xml:"WithdrawalResult"`
	ErrorMessage            string   `xml:"ErrorMessage"`
	IsSucceed               bool     `xml:"IsSucceed"`
	ErrorCode               int      `xml:"ErrorCode"`
	ErrorId                 string   `xml:"ErrorId"`
	TransactionAmount       float64  `xml:"TransactionAmount"`
	TransactionCreditAmount float64  `xml:"TransactionCreditAmount"`
	TransactionId           string   `xml:"TransactionId"`
	AccountNumber           string   `xml:"AccountNumber"`
	CreditBalance           float64  `xml:"CreditBalance"`
	Balance                 float64  `xml:"Balance"`
}

/*
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetPlaycheckUrlResponse xmlns="https://entservices.totalegame.net">
      <GetPlaycheckUrlResult>string</GetPlaycheckUrlResult>
    </GetPlaycheckUrlResponse>
  </soap:Body>
</soap:Envelope>
*/

type GetPlaycheckUrlResponseBodyData struct {
	XMLName              xml.Name                `xml:"Body"`
	PlaycheckUrlResponse GetPlaycheckUrlResponse `xml:"GetPlaycheckUrlResponse"`
}

type GetPlaycheckUrlResponse struct {
	XMLName            xml.Name              `xml:"GetPlaycheckUrlResponse"`
	PlaycheckUrlResult GetPlaycheckUrlResult `xml:"GetPlaycheckUrlResult"`
}
type GetPlaycheckUrlResult struct {
	XMLName      xml.Name `xml:"GetPlaycheckUrlResult"`
	PlaycheckUrl string   `xml:",innerxml"`
}

/*总报表
<GetBetInfoDetailsResponse xmlns="https://entservices.totalegame.net">
     <GetBetInfoDetailsResult>guid</GetBetInfoDetailsResult>
   </GetBetInfoDetailsResponse>
*/
type GetBetInfoDetailsResponseBodyData struct {
	XMLName                xml.Name                  `xml:"Body"`
	BetInfoDetailsResponse GetBetInfoDetailsResponse `xml:"GetBetInfoDetailsResponse"`
}

type GetBetInfoDetailsResponse struct {
	XMLName              xml.Name                `xml:"GetBetInfoDetailsResponse"`
	BetInfoDetailsResult GetBetInfoDetailsResult `xml:"GetBetInfoDetailsResult"`
}
type GetBetInfoDetailsResult struct {
	XMLName xml.Name `xml:"GetBetInfoDetailsResult"`
	Guid    string   `xml:",innerxml"`
}

//注单记录
/*
<GameplayDetailedReportResponse xmlns="https://entservices.totalegame.net">
      <GameplayDetailedReportResult>guid</GameplayDetailedReportResult>
    </GameplayDetailedReportResponse>
*/
type GameplayDetailedReportResponseBodyData struct {
	XMLName                xml.Name                       `xml:"Body"`
	GameplayDetailedReport GameplayDetailedReportResponse `xml:"GameplayDetailedReportResponse"`
}

type GameplayDetailedReportResponse struct {
	XMLName                      xml.Name                     `xml:"GameplayDetailedReportResponse"`
	GameplayDetailedReportResult GameplayDetailedReportResult `xml:"GameplayDetailedReportResult"`
}
type GameplayDetailedReportResult struct {
	XMLName xml.Name `xml:"GameplayDetailedReportResult"`
	Guid    string   `xml:",innerxml"`
}

/**
<GetReportByNameResponse xmlns="https://entservices.totalegame.net">
<GetReportByNameResult>
<IsSucceed>true</IsSucceed>
<ErrorCode>0</ErrorCode>
<SourceStatus>Normal</SourceStatus>
<ErrorValue>SUCCESS</ErrorValue>
<Status>Pending</Status> //是否成功 Error, Pending, Complete
<Id>a155d57f-c566-4690-a714-65e69641d836</Id>
</GetReportByNameResult>
</GetReportByNameResponse>
PlayerGamePlayReport
*/
type PlayerGamePlayReportBodyData struct {
	XMLName                      xml.Name                     `xml:"Body"`
	PlayerGamePlayReportResponse PlayerGamePlayReportResponse `xml:"GetReportByNameResponse"`
}

type PlayerGamePlayReportResponse struct {
	XMLName                    xml.Name                   `xml:"GetReportByNameResponse"`
	PlayerGamePlayReportResult PlayerGamePlayReportResult `xml:"GetReportByNameResult"`
}
type PlayerGamePlayReportResult struct {
	XMLName xml.Name `xml:"GetReportByNameResult"`
	Guid    string   `xml:"Id"`
}

/**
视频
https://etiloader3.valueactive.eu/ETILoader/default.aspx?CasinoID=2635&LoginName=player3230827&Password=MTbdGBG&ClientID=4&UL=zh&VideoQuality=auto4&StartingTab=default&ClientType=1&ModuleID=70004&UserType=0&ProductID=2&BetProfileID=0&ActiveCurrency=Credits&CustomLDParam=NULL&=Login

电子
https://igaminga.gameassists.co.uk/aurora/?theme=igamingA&ul=zh&gameid=oceans&sEXT1=player3230827&sEXT2=MTbdGBG&usertype=0
*/

/*
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    <soap:Body>
        <GetReportByNameResponse xmlns="https://entservices.totalegame.net">
            <GetReportByNameResult>
                <IsSucceed>true</IsSucceed>
                <ErrorCode>0</ErrorCode>
                <SourceStatus>Normal</SourceStatus>
                <ErrorValue>SUCCESS</ErrorValue>
                <Status>Pending</Status> Error, Pending, Complete
                <Id>fc9571ad-5b23-4132-b190-db0ecd3fb5d6</Id>
            </GetReportByNameResult>
        </GetReportByNameResponse>
    </soap:Body>
</soap:Envelope>
//获取返回的状态
*/
type GetReportByNameResponseBodyDataStatus struct {
	XMLName                 xml.Name                `xml:"Body"`
	GetReportByNameResponse GetReportByNameResponse `xml:"GetReportByNameResponse"`
}

type GetReportByNameResponse struct {
	XMLName               xml.Name              `xml:"GetReportByNameResponse"`
	GetReportByNameResult GetReportByNameResult `xml:"GetReportByNameResult"`
}

type GetReportByNameResult struct {
	XMLName         xml.Name `xml:"GetReportByNameResult"`
	IsSucceed       bool     `xml:"IsSucceed"`
	ErrorCode       int      `xml:"ErrorCode"`
	ErrorValue      string   `xml:"ErrorValue"`
	Status          string   `xml:"Status"`
	Id              string   `xml:"Id"`
	ReportName      string   `xml:"ReportName"`
	Paging          string   `xml:"Paging"`
	CurrentPageData []string `xml:"CurrentPageData"`
}

////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////
type Paging struct {
	XMLName           xml.Name `xml:"Paging"`
	RowsPerPage       int      `xml:"RowsPerPage"`
	PageNumber        int      `xml:"PageNumber"`
	TotalPage         int      `xml:"TotalPage"`
	TotalRow          int      `xml:"TotalRow"`
	RowsInCurrentPage int      `xml:"RowsInCurrentPage"`
}

/////////////////////GetReportResult的返回
type GetReportResultDataStatus struct { //GetReportResultResponse
	XMLName                               xml.Name                              `xml:"Envelope"`
	GetReportResultResponseBodyDataStatus GetReportResultResponseBodyDataStatus `xml:"Body"`
}

type GetReportResultResponseBodyDataStatus struct {
	XMLName                       xml.Name                      `xml:"Body"`
	GetReportResultResponseStatus GetReportResultResponseStatus `xml:"GetReportResultResponse"`
}

type GetReportResultResponseStatus struct {
	XMLName               xml.Name              `xml:"GetReportResultResponse"`
	GetReportResultStatus GetReportResultStatus `xml:"GetReportResultResult"`
}

type GetReportResultStatus struct {
	XMLName         xml.Name        `xml:"GetReportResultResult"`
	IsSucceed       bool            `xml:"IsSucceed"`
	ErrorCode       string          `xml:"ErrorCode"`
	SourceStatus    string          `xml:"SourceStatus"`
	ErrorValue      string          `xml:"ErrorValue"`
	ReportName      string          `xml:"ReportName"`
	Paging          Paging          `xml:"Paging"`
	CurrentPageData CurrentPageData `xml:"CurrentPageData"`
	Status          string          `xml:"Status"`
	Id              string          `xml:"Id"`
}

type CurrentPageData struct {
	XMLName        xml.Name       `xml:"CurrentPageData"`
	DiffgrDiffgram DiffgrDiffgram `xml:"diffgram"`
}

type DiffgrDiffgram struct {
	XMLName    xml.Name `xml:"diffgram"`
	NewDataSet string   `xml:",innerxml"`
}

type NewDataSet struct {
	BetRecord []mmg.MgBetRecord `xml:"Table"`
}

/////////////////
