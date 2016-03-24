package mg

import (
	"bytes"
	"encoding/xml"
	"errors"
	"io/ioutil"
	"net"
	"net/http"
	_ "strconv"
	"strings"
	"time"

	"common"
	"core/socks"

	m "app/models"
	"utility"
)

const (
	mg_soap_head = `<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">`
	mg_soap_foot = `</soap:Envelope>`
	timeout      = 30
)

//TODO 改用reids
//var MgSessions map[string]*MgHeader

var statusError = errors.New(" StatusCode is not 200")

//func init() {
//	MgSessions = make(map[string]*MgHeader)
//}

//把head写入redis
func setheader(site string, header *MgHeader) error {
	ip, err := getlocalip()
	if err != nil {
		return err
	}
	header_str := ""
	header_str = header.SessionGUID + "###" + header.ErrorCode + "###" + header.IPAddress
	if header.IsExtendSession {
		header_str = header_str + "###1"
	} else {
		header_str = header_str + "###0"
	}
	header_str = header_str + "###" + utility.ToStr(header.Expired)
	common.Log.Info("SOAP setheader:", "mg:header:"+site+ip, header_str)
	sc := m.Redisclient.Set("mg:header:"+site+ip, header_str)
	m.Redisclient.Expire("mg:header:"+site+ip, 50*60*time.Second) //50分钟失效
	if sc.Err() != nil {
		return sc.Err()
	}

	return nil
}

//获取head从redis
func getheader(site string) (*MgHeader, error) {
	//获取本机ip
	ip, err := getlocalip()
	if err != nil {
		return nil, err
	}
	common.Log.Info("SOAP getheader:", "mg:header:" + site + ip)
	header_str, err := m.Redisclient.Get("mg:header:" + site + ip).Result()

	if err != nil {
		return nil, err
	}

	head := strings.Split(header_str, "###")

	mgh := new(MgHeader)
	mgh.SessionGUID = head[0]
	mgh.ErrorCode = head[1]
	mgh.IPAddress = head[2]
	if head[3] == "1" {
		mgh.IsExtendSession = true
	} else {
		mgh.IsExtendSession = false
	}

	mgh.Expired, _ = utility.StrTo(head[4]).Int64()

	return mgh, nil
}

func getlocalip() (string, error) {
	conn, err := net.Dial("udp", "google.com:80")
	common.Log.Info("SOAP IP:", conn.LocalAddr().String())
	if err != nil {
		return "", err
	}
	defer conn.Close()
	return strings.Split(conn.LocalAddr().String(), ":")[0], nil
}

/*
<AgentSession xmlns="https://entservices.totalegame.net">
      <SessionGUID>guid</SessionGUID>
      <ErrorCode>int</ErrorCode>
      <IPAddress>string</IPAddress>
      <IsExtendSession>boolean</IsExtendSession>
    </AgentSession>
*/
type SoapHeader struct {
	XMLName xml.Name `xml:"soap:Header"`
	Header  interface{}
}
type SoapBody struct {
	XMLName xml.Name `xml:"soap:Body"`
	Body    interface{}
}

type MgHeader struct {
	XMLName         xml.Name `xml:"https://entservices.totalegame.net AgentSession"`
	SessionGUID     string   `xml:"SessionGUID"`
	ErrorCode       string   `xml:"ErrorCode"`
	IPAddress       string   `xml:"IPAddress"`
	IsExtendSession bool     `xml:"IsExtendSession"`
	Expired         int64    `xml:"-"`
}

/*
func GetToken() (token string, spam string) {
	int_spam := time.Now().Unix()
	spam = strconv.FormatInt(int_spam, 10)
	str := APPID + "$" + APPKEY + "$" + spam
	token = Md5(str)
	return
}
*/
func GetAuth(wsdlurl string, soapbody interface{}) ([]byte, error) {
	buffer := &bytes.Buffer{}
	requestEnvelope := SoapBody{}
	requestEnvelope.Body = soapbody

	encoder := xml.NewEncoder(buffer)
	err := encoder.Encode(requestEnvelope)
	if err != nil {
		return []byte{}, err
	}
	buffer = bytes.NewBufferString(mg_soap_head + buffer.String() + mg_soap_foot)
	var client *http.Client
	if len(common.Socks5) > 0 {
		dialSocksProxy := socks.DialSocksProxy(socks.SOCKS5, common.Socks5)
		tr := &http.Transport{Dial: dialSocksProxy}
		client = &http.Client{Transport: tr}
	} else {
		client = &http.Client{}
		//设置请求超时时间
		client.Transport = &http.Transport{
			Dial: func(netw, addr string) (net.Conn, error) {
				deadline := time.Now().Add(timeout * time.Second)
				c, err := net.DialTimeout(netw, addr, timeout*time.Second)
				if err != nil {
					return nil, err
				}
				c.SetDeadline(deadline)
				return c, nil
			},
		}
	}
	req, err := http.NewRequest("POST", wsdlurl, buffer)
	if err != nil {
		return []byte{}, err
	}
	//common.Log.Info("SOAP DOPOSR:", req.Header, req.Body)
	//soap头
	req.Header.Set("SOAPAction", "https://entservices.totalegame.net/IsAuthenticate")
	req.Header.Set("Content-Type", "text/xml")
	req.Header.Set("charset", "utf-8")

	resp, err := client.Do(req)
	if err == nil {
		defer resp.Body.Close()
	}

	if err != nil {
		return []byte{}, err
	}
	body, err := ioutil.ReadAll(resp.Body)
	if err != nil {
		return []byte{}, err
	}
	if resp.StatusCode != 200 {
		common.Log.Info("SOAP DOPOSR:", resp.Header, string(body))
		return []byte{}, errors.New(string(body))
	}
	return body, nil
}

func SoapPost(wsdlurl, action string, header *MgHeader, soapbody interface{}) ([]byte, error) {
	if header == nil {
		return []byte{}, ErrHeaderIsNil
	}
	buffer := &bytes.Buffer{}
	requestEnvelope := SoapBody{}
	requestEnvelope.Body = soapbody
	encoder := xml.NewEncoder(buffer)
	err := encoder.Encode(requestEnvelope)
	if err != nil {
		return []byte{}, err
	}
	soapbody_string := buffer.String()
	//头
	soapHeader := SoapHeader{}
	soapHeader.Header = *header
	buffer = &bytes.Buffer{}
	encoder = xml.NewEncoder(buffer)
	err = encoder.Encode(soapHeader)
	if err != nil {
		return []byte{}, err
	}
	soapheader_string := buffer.String()
	buffer = bytes.NewBufferString(mg_soap_head + soapheader_string + soapbody_string + mg_soap_foot)

	//common.Log.Info("MG SoapPost:", buffer.String())
	var client *http.Client
	if len(common.Socks5) > 0 {
		dialSocksProxy := socks.DialSocksProxy(socks.SOCKS5, common.Socks5)
		tr := &http.Transport{Dial: dialSocksProxy}
		client = &http.Client{Transport: tr}
	} else {
		client = &http.Client{}
	}
	req, err := http.NewRequest("POST", wsdlurl, buffer)
	if err != nil {
		return []byte{}, err
	}
	common.Log.Info("SOAP DOPOSR:", buffer.String())
	//soap头
	req.Header.Set("SOAPAction", action)
	req.Header.Set("Content-Type", "text/xml")
	req.Header.Set("charset", "utf-8")
	//设置请求超时时间
	client.Transport = &http.Transport{
		Dial: func(netw, addr string) (net.Conn, error) {
			deadline := time.Now().Add(timeout * time.Second)
			c, err := net.DialTimeout(netw, addr, timeout*time.Second)
			if err != nil {
				return nil, err
			}
			c.SetDeadline(deadline)
			return c, nil
		},
	}
	resp, err := client.Do(req)
	if err == nil {
		defer resp.Body.Close()
	}

	if err != nil {
		return []byte{}, err
	}
	body, err := ioutil.ReadAll(resp.Body)
	if err != nil {
		return []byte{}, err
	}
	if resp.StatusCode != 200 {
		common.Log.Info("SOAP DOPOSR:", resp.Header, string(body))
		return []byte{}, errors.New(" StatusCode is not 200")
	}
	return body, nil
}

/*
func Dosoap(action string, param interface{}) ([]byte, error) {
	buffer := &bytes.Buffer{}
	requestEnvelope := CreateSoapEnvelope()

	tempparam := GetOrderListRequest{}
	//强制类型转换
	if reflect.TypeOf(param).Name() == reflect.TypeOf(tempparam).Name() {
		tempparam = param.(GetOrderListRequest)
	} else {
		return []byte{}, errors.New("is not a GetOrderList param")
	}
	//
	err := checkOrderListParam(&tempparam)
	if err != nil {
		return []byte{}, err
	}
	Log.Err(tempparam)
	requestEnvelope.Body.Body = tempparam

	encoder := xml.NewEncoder(buffer)
	err = encoder.Encode(requestEnvelope)
	if err != nil {
		return []byte{}, err
	}

	client := http.Client{}
	req, err := http.NewRequest("POST", WSDL_URL, buffer)
	if err != nil {
		return []byte{}, err
	}
	Log.Err(req)
	//soap头
	req.Header.Set("SOAPAction", "")
	req.Header.Set("Content-Type", "text/xml")
	req.Header.Set("charset", "utf-8")

	resp, err := client.Do(req)
	defer resp.Body.Close()
	if err != nil {
		return []byte{}, err
	}
	if resp.StatusCode != 200 {
		return []byte{}, errors.New(" StatusCode is not 200")
	}
	body, err := ioutil.ReadAll(resp.Body)
	if err != nil {
		return []byte{}, err
	}
	Log.Err(string(body))

	/*
		bodyElement, err := DecodeResponseBody(bytes.NewReader(body))
		if err != nil {
			return UploadPaperBackData{}, err
		}
		jsonMap := UploadPaperBackData{}
		err = xml.Unmarshal([]byte(bodyElement.UploadPaperResult), &jsonMap)
		if err != nil {
			return jsonMap, err
		}

	return body, nil
}
*/

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
*/
/*
func DecodeGetOrderListResponse(body io.Reader) (*GetOrderListResponse, error) {
	decoder := xml.NewDecoder(body)
	nextElementIsBody := false
	for {
		token, err := decoder.Token()
		//fmt.Println("\n\n", token, err)
		if err == io.EOF {
			break
		} else if err != nil {
			return nil, err
		}

		switch startElement := token.(type) {
		case xml.StartElement:
			if nextElementIsBody {
				responseBody := GetOrderListResponse{}
				err = decoder.DecodeElement(&responseBody, &startElement)
				if err != nil {
					return nil, err
				}
				return &responseBody, nil
			}
			if startElement.Name.Space == "http://schemas.xmlsoap.org/soap/envelope/" && startElement.Name.Local == "Body" {
				nextElementIsBody = true
			}
		}
	}
	return nil, errors.New("Did not find SOAP body element")
}
*/
