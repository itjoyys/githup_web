// Copyright (c) 2012-2013 Jason McVetta.  This is Free Software, released
// under the terms of the GPL v3.  See http://www.gnu.org/copyleft/gpl.html for
// details.  Resist intellectual serfdom - the ownership of ideas is akin to
// slavery.

package napping

/*
This module provides a Session object to manage and persist settings across
requests (cookies, auth, proxies).
*/

import (
	"bytes"
	"core/socks"
	"crypto/tls"
	"encoding/json"
	"encoding/xml"
	"io/ioutil"
	_ "log"
	"net"
	"net/http"
	"net/url"
	"strings"
	"time"

	"common"
	"fmt"
)

type Session struct {
	Client *http.Client
	Log    bool // Log request and response

	// Optional
	Userinfo *url.Userinfo

	// Optional defaults - can be overridden in a Request
	Header *http.Header
	Params *Params

	Ipaddr   string
	Timeout  time.Duration
	Datatype string //xml json html
}

//设置header的值,替换原来的值
func (s *Session) SetHeader(key, value string) {
	if s.Header == nil {
		s.Header = &http.Header{}
	}
	if len(s.Header.Get(key)) == 0 {
		s.Header.Add(key, value)
	} else {
		s.Header.Set(key, value)
	}
}

// Send constructs and sends an HTTP request.
func (s *Session) Send(r *Request) (response *Response, err error) {
	r.Method = strings.ToUpper(r.Method)
	//验证url
	u, err := url.Parse(r.Url)
	if err != nil {
		return
	}
	//连接的Params
	p := Params{} //默认的
	if s.Params != nil {
		for k, v := range *s.Params {
			p[k] = v
		}
	}
	if r.Params != nil { //参数带进来的
		for k, v := range *r.Params {
			p[k] = v
		}
	}
	vals := u.Query()
	for k, v := range p {
		vals.Set(k, v)
	}
	u.RawQuery = vals.Encode()
	//
	// Create a Request object;
	header := http.Header{}
	if s.Header != nil {
		for k, _ := range *s.Header {
			v := s.Header.Get(k)
			header.Set(k, v)
		}
	}
	var req *http.Request

	if r.Payload != nil {
		if s.Datatype == "" || s.Datatype == "html" { //使用form提交
			header.Add("Content-Type", "application/x-www-form-urlencoded")
		} else if s.Datatype == "xml" { //默认使用soap
			header.Add("Content-Type", "application/x-www-form-urlencoded")
		} else if s.Datatype == "json" {
			header.Add("Content-Type", "application/x-www-form-urlencoded")
		}
		var str string
		str, err = creactPlayload(s.Datatype, r.Payload)
		if err != nil {
			return
		}
		fmt.Println(str, "--------------------")
		buf := strings.NewReader(str)
		req, err = http.NewRequest(r.Method, u.String(), buf)
		if err != nil {
			return
		}
	} else { // no data to encode
		req, err = http.NewRequest(r.Method, u.String(), nil)
		if err != nil {
			return
		}
	}
	//
	// Merge Session and Request options
	//
	var userinfo *url.Userinfo
	if u.User != nil {
		userinfo = u.User
	}
	if s.Userinfo != nil {
		userinfo = s.Userinfo
	}
	// Prefer Request's user credentials
	if r.Userinfo != nil {
		userinfo = r.Userinfo
	}
	if r.Header != nil {
		for k, v := range *r.Header {
			header.Set(k, v[0]) // Is there always guarnateed to be at least one value for a header?
		}
	}
	/*
		if header.Get("Accept") == "" {
			header.Add("Accept", "application/json") // Default, can be overridden with Opts
		}
	*/
	req.Header = header
	//
	// Set HTTP Basic authentication if userinfo is supplied
	//
	if userinfo != nil {
		pwd, _ := userinfo.Password()
		req.SetBasicAuth(userinfo.Username(), pwd)
	}
	r.timestamp = time.Now()
	var client *http.Client
	if s.Client != nil {
		client = s.Client
	} else {
		client = &http.Client{}
	}
	//设置请求超时时间
	client.Transport = &http.Transport{
		Dial: func(netw, addr string) (net.Conn, error) {
			//本地地址  ipaddr是本地外网IP
			deadline := time.Now().Add(s.Timeout * time.Second)
			if len(s.Ipaddr) > 0 {
				lAddr, err := net.ResolveTCPAddr(netw, s.Ipaddr+":0")
				if err != nil {
					return nil, err
				}
				//被请求的地址
				rAddr, err := net.ResolveTCPAddr(netw, addr)
				if err != nil {
					return nil, err
				}
				conn, err := net.DialTCP(netw, lAddr, rAddr)
				if err != nil {
					return nil, err
				}

				conn.SetDeadline(deadline)

				return conn, nil

			} else {
				conn, err := net.DialTimeout(netw, addr, s.Timeout*time.Second)
				if err != nil {
					return nil, err
				}

				conn.SetDeadline(deadline)

				return conn, nil
			}
			// return nil, nil
		},
		TLSClientConfig: &tls.Config{InsecureSkipVerify: true},
	}

	if len(common.Socks5) > 0 {
		dialSocksProxy := socks.DialSocksProxy(socks.SOCKS5, common.Socks5)
		client.Transport = &http.Transport{
			Dial:            dialSocksProxy,
			TLSClientConfig: &tls.Config{InsecureSkipVerify: true},
		}
	}
	//发送请求
	resp, err := client.Do(req)
	if err != nil {
		common.Log.Err(err)
		return
	}
	defer resp.Body.Close()
	r.status = resp.StatusCode
	r.response = resp
	//
	// Unmarshal
	//
	r.body, err = ioutil.ReadAll(resp.Body)
	if err != nil {
		common.Log.Err(err)
		return
	}
	//解析body
	if string(r.body) != "" {
		if resp.StatusCode < 300 && r.Result != nil {
			if s.Datatype == "xml" { //默认使用soap
				err = xml.Unmarshal(r.body, r.Result)
			} else if s.Datatype == "json" {
				err = json.Unmarshal(r.body, r.Result)
			}
		}
		if resp.StatusCode >= 400 && r.Error != nil {
			//错误只以json返回
			json.Unmarshal(r.body, r.Error) // Should we ignore unmarshall error?
		}
	}
	rsp := Response(*r)
	response = &rsp
	return
}

// Send constructs and sends an HTTP request.
func (s *Session) Send2(r *Request) (response *Response, err error) {
	r.Method = strings.ToUpper(r.Method)
	//验证url
	u, err := url.Parse(r.Url)
	if err != nil {
		return
	}
	//连接的Params
	p := Params{} //默认的
	if s.Params != nil {
		for k, v := range *s.Params {
			p[k] = v
		}
	}
	if r.Params != nil { //参数带进来的
		for k, v := range *r.Params {
			p[k] = v
		}
	}
	vals := u.Query()
	for k, v := range p {
		vals.Set(k, v)
	}
	u.RawQuery = vals.Encode()
	//
	// Create a Request object;
	header := http.Header{}
	if s.Header != nil {
		for k, _ := range *s.Header {
			v := s.Header.Get(k)
			header.Set(k, v)
		}
	}
	var req *http.Request
	var str []byte
	if r.Payload != nil {
		if s.Datatype == "" || s.Datatype == "html" { //使用form提交
			header.Add("Content-Type", "application/x-www-form-urlencoded")
		} else if s.Datatype == "xml" { //默认使用soap
			header.Add("Content-Type", "application/x-www-form-urlencoded")
		} else if s.Datatype == "json" {
			header.Set("Content-Type", "application/json")
		}
		str, err = creactPlayload2(s.Datatype, r.Payload)
		if err != nil {
			return
		}
		buf := bytes.NewBuffer(str)
		req, err = http.NewRequest(r.Method, u.String(), buf)
		if err != nil {
			return
		}
	} else { // no data to encode
		req, err = http.NewRequest(r.Method, u.String(), nil)
		if err != nil {
			return
		}
	}
	//
	// Merge Session and Request options
	//
	var userinfo *url.Userinfo
	if u.User != nil {
		userinfo = u.User
	}
	if s.Userinfo != nil {
		userinfo = s.Userinfo
	}
	// Prefer Request's user credentials
	if r.Userinfo != nil {
		userinfo = r.Userinfo
	}
	if r.Header != nil {
		for k, v := range *r.Header {
			header.Set(k, v[0]) // Is there always guarnateed to be at least one value for a header?
		}
	}
	/*
		if header.Get("Accept") == "" {
			header.Add("Accept", "application/json") // Default, can be overridden with Opts
		}
	*/
	req.Header = header
	//
	// Set HTTP Basic authentication if userinfo is supplied
	//
	if userinfo != nil {
		pwd, _ := userinfo.Password()
		req.SetBasicAuth(userinfo.Username(), pwd)
	}
	r.timestamp = time.Now()
	var client *http.Client
	if s.Client != nil {
		client = s.Client
	} else {
		client = &http.Client{}
	}
	//设置请求超时时间
	client.Transport = &http.Transport{
		Dial: func(netw, addr string) (net.Conn, error) {
			//本地地址  ipaddr是本地外网IP
			deadline := time.Now().Add(s.Timeout * time.Second)
			if len(s.Ipaddr) > 0 {
				lAddr, err := net.ResolveTCPAddr(netw, s.Ipaddr+":0")
				if err != nil {
					return nil, err
				}
				//被请求的地址
				rAddr, err := net.ResolveTCPAddr(netw, addr)
				if err != nil {
					return nil, err
				}
				conn, err := net.DialTCP(netw, lAddr, rAddr)
				if err != nil {
					return nil, err
				}

				conn.SetDeadline(deadline)

				return conn, nil

			} else {
				conn, err := net.DialTimeout(netw, addr, s.Timeout*time.Second)
				if err != nil {
					return nil, err
				}

				conn.SetDeadline(deadline)

				return conn, nil
			}
			// return nil, nil
		},
		TLSClientConfig: &tls.Config{InsecureSkipVerify: true},
	}

	if len(common.Socks5) > 0 {
		dialSocksProxy := socks.DialSocksProxy(socks.SOCKS5, common.Socks5)
		client.Transport = &http.Transport{
			Dial:            dialSocksProxy,
			TLSClientConfig: &tls.Config{InsecureSkipVerify: true},
		}
	}
	//发送请求
	resp, err := client.Do(req)
	if err != nil {
		common.Log.Err(err)
		return
	}
	defer resp.Body.Close()
	r.status = resp.StatusCode
	r.response = resp
	//
	// Unmarshal
	//
	r.body, err = ioutil.ReadAll(resp.Body)
	if err != nil {
		common.Log.Err(err)
		return
	}
	//解析body
	if string(r.body) != "" {
		if resp.StatusCode < 300 && r.Result != nil {
			if s.Datatype == "xml" { //默认使用soap
				err = xml.Unmarshal(r.body, r.Result)
			} else if s.Datatype == "json" {
				err = json.Unmarshal(r.body, r.Result)
			}
		}
		if resp.StatusCode >= 400 && r.Error != nil {
			//错误只以json返回
			json.Unmarshal(r.body, r.Error) // Should we ignore unmarshall error?
		}
	}
	rsp := Response(*r)
	response = &rsp
	return
}

/**
 * 根据datatype创建请求的数据
 * @param  {[type]} dataType string        [description]
 * @return {[type]}          [description]
 */
func creactPlayload(dataType string, payload interface{}) (string, error) {
	body := ""
	if dataType == "" || dataType == "html" { //使用form提交
		b, err := json.Marshal(&payload)
		if err != nil {
			return body, err
		}
		m := map[string]string{}
		err = json.Unmarshal(b, &m)
		if err != nil {
			return body, err
		}
		str := make([]string, 0)
		for k, v := range m {
			str = append(str, k+"="+v)
		}
		body = strings.Join(str, "&")
		//fmt.Println(body)
	} else if dataType == "xml" { //默认使用soap

	} else if dataType == "json" {
		//TODO
		b, err := json.Marshal(&payload)
		if err != nil {
			return body, err
		}
		m := map[string]string{}
		err = json.Unmarshal(b, &m)
		if err != nil {
			return body, err
		}
		str := make([]string, 0)
		for k, v := range m {
			str = append(str, k+"="+v)
		}
		body = strings.Join(str, "&")
	}
	return body, nil
}

/*

 */
/**
 * 根据datatype创建请求的数据
 * @param  {[type]} dataType string        [description]
 * @return {[type]}          [description]
 */
func creactPlayload2(dataType string, payload interface{}) (body []byte, err error) {

	if dataType == "" || dataType == "html" { //使用form提交
		body, err = json.Marshal(&payload)
		if err != nil {
			return body, err
		}
		m := map[string]string{}
		err = json.Unmarshal(body, &m)
		if err != nil {
			return body, err
		}
		str := make([]string, 0)
		for k, v := range m {
			str = append(str, k+"="+v)
		}
		body = []byte(strings.Join(str, "&"))
		//fmt.Println(body)
	} else if dataType == "json" {
		body, err = json.Marshal(&payload)
		if err != nil {
			return body, err
		}
	}
	return body, nil
}

// Get sends a GET request.
func (s *Session) Get(url string, p *Params, result, errMsg interface{}) (*Response, error) {
	r := Request{
		Method: "GET",
		Url:    url,
		Params: p,
		Result: result,
		Error:  errMsg,
	}
	return s.Send(&r)
}

// Post sends a POST request. 以form方式提交不支持文件
func (s *Session) Post(url string, p *Params, payload, result, errMsg interface{}) (*Response, error) {
	r := Request{
		Method:  "POST",
		Url:     url,
		Params:  p,
		Payload: payload,
		Result:  result,
		Error:   errMsg,
	}
	return s.Send(&r)
}

// Post sends a POST request. 以form方式提交不支持文件
func (s *Session) Post2(url string, p *Params, payload, result, errMsg interface{}) (*Response, error) {
	r := Request{
		Method:  "POST",
		Url:     url,
		Params:  p,
		Payload: payload,
		Result:  result,
		Error:   errMsg,
	}
	return s.Send2(&r)
}

// Options sends an OPTIONS request.
func (s *Session) Options(url string, result, errMsg interface{}) (*Response, error) {
	r := Request{
		Method: "OPTIONS",
		Url:    url,
		Result: result,
		Error:  errMsg,
	}
	return s.Send(&r)
}

// Head sends a HEAD request.
func (s *Session) Head(url string, result, errMsg interface{}) (*Response, error) {
	r := Request{
		Method: "HEAD",
		Url:    url,
		Result: result,
		Error:  errMsg,
	}
	return s.Send(&r)
}

// Put sends a PUT request.
func (s *Session) Put(url string, payload, result, errMsg interface{}) (*Response, error) {
	r := Request{
		Method:  "PUT",
		Url:     url,
		Payload: payload,
		Result:  result,
		Error:   errMsg,
	}
	return s.Send(&r)
}

// Put sends a PUT request.
func (s *Session) Put2(url string, payload, p *Params, result, errMsg interface{}) (*Response, error) {
	r := Request{
		Method:  "PUT",
		Url:     url,
		Params:  p,
		Payload: payload,
		Result:  result,
		Error:   errMsg,
	}
	return s.Send2(&r)
}

// Patch sends a PATCH request.
func (s *Session) Patch(url string, payload, result, errMsg interface{}) (*Response, error) {
	r := Request{
		Method:  "PATCH",
		Url:     url,
		Payload: payload,
		Result:  result,
		Error:   errMsg,
	}
	return s.Send(&r)
}

// Delete sends a DELETE request.
func (s *Session) Delete(url string, result, errMsg interface{}) (*Response, error) {
	r := Request{
		Method: "DELETE",
		Url:    url,
		Result: result,
		Error:  errMsg,
	}
	return s.Send(&r)
}
