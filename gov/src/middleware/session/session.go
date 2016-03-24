// Copyright 2013 Beego Authors
// Copyright 2014 Unknwon
//
// Licensed under the Apache License, Version 2.0 (the "License"): you may
// not use this file except in compliance with the License. You may obtain
// a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
// WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
// License for the specific language governing permissions and limitations
// under the License.

// Package session a middleware that provides the session management of Macaron.
package session

// NOTE: last sync 000033e on Nov 4, 2014.

import (
	"encoding/hex"
	"fmt"
	"net/http"
	"time"

	"core/webcore"
)

const _VERSION = "0.1.6"

func Version() string {
	return _VERSION
}

// RawStore is the interface that operates the session data.
type RawStore interface {
	// Set sets value to given key in session.
	Set(interface{}, interface{}) error
	// Get gets value by given key in session.
	Get(interface{}) interface{}
	// Delete deletes a key from session.
	Delete(interface{}) error
	// ID returns current session ID.
	ID() string
	// Release releases session resource and save data to provider.
	Release() error
	// Flush deletes all session data.
	Flush() error
}

// Store is the interface that contains all data for one session process with specific ID.
type Store interface {
	RawStore
	// Read returns raw session store by session ID.
	Read(string) (RawStore, error)
	// Destory deletes a session.
	Destory(*webcore.Context) error
	// RegenerateId regenerates a session store from old session ID to new one.
	RegenerateId(ctx *webcore.Context) (RawStore, error)
	//更新登录状态
	SetLoginId(ctx *webcore.Context, islogin bool) error
	// Count counts and returns number of sessions.
	Count() int
	// GC calls GC to clean expired sessions.
	GC()
}

type store struct {
	RawStore
	*Manager
}

var _ Store = &store{}

// Options represents a struct for specifying configuration options for the session middleware.
type Options struct {
	// Name of provider. Default is "memory".
	Provider string
	// Provider configuration, it's corresponding to provider.
	ProviderConfig string
	// Cookie name to save session ID. Default is "MacaronSession".
	CookieName string
	// Cookie path to store. Default is "/".
	CookiePath string
	// GC interval time in seconds. Default is 3600.
	Gclifetime int64
	// Max life time in seconds. Default is whatever GC interval time is.
	Maxlifetime int64
	// Use HTTPS only. Default is false.
	Secure bool
	// Cookie life time. Default is 0.
	CookieLifeTime int
	// Cookie domain name. Default is empty.
	Domain string
	// Session ID length. Default is 16.
	IDLength int
	// Configuration section name. Default is "session".
	Section string
}

func prepareOptions(options []Options) Options {
	var opt Options
	if len(options) > 0 {
		opt = options[0]
	}
	if len(opt.Section) == 0 {
		opt.Section = "session"
	}
	sec := webcore.Config().Section(opt.Section)

	if len(opt.Provider) == 0 {
		opt.Provider = sec.Key("PROVIDER").MustString("memory")
	}
	if len(opt.ProviderConfig) == 0 {
		opt.ProviderConfig = sec.Key("PROVIDER_CONFIG").MustString("data/sessions")
	}
	if len(opt.CookieName) == 0 {
		opt.CookieName = sec.Key("COOKIE_NAME").MustString("session")
	}
	if len(opt.CookiePath) == 0 {
		opt.CookiePath = sec.Key("COOKIE_PATH").MustString("/")
	}
	if opt.Gclifetime == 0 {
		opt.Gclifetime = sec.Key("GC_INTERVAL_TIME").MustInt64(3600)
	}
	//session存活时间
	if opt.Maxlifetime == 0 {
		opt.Maxlifetime = sec.Key("MAX_LIFE_TIME").MustInt64(opt.Gclifetime)
	}
	if !opt.Secure {
		opt.Secure = sec.Key("SECURE").MustBool()
	}
	if opt.CookieLifeTime == 0 {
		opt.CookieLifeTime = sec.Key("COOKIE_LIFE_TIME").MustInt()
	}
	if len(opt.Domain) == 0 {
		opt.Domain = sec.Key("DOMAIN").String()
	}
	if opt.IDLength == 0 {
		opt.IDLength = sec.Key("ID_LENGTH").MustInt(32)
	}

	return opt
}

// Sessioner is a middleware that maps a session.SessionStore service into the Macaron handler chain.
// An single variadic session.Options struct can be optionally provided to configure.
func Sessioner(options ...Options) webcore.Handler {
	opt := prepareOptions(options)
	manager, err := NewManager(opt.Provider, opt)
	if err != nil {
		panic(err)
	}
	//回收过期对色session
	go manager.startGC()

	return func(ctx *webcore.Context) {
		sess, err := manager.Start(ctx)
		if err != nil {
			panic("session(start): " + err.Error())
		}
		s := store{
			RawStore: sess,
			Manager:  manager,
		}

		ctx.MapTo(s, (*Store)(nil))

		ctx.Next()

		if err = sess.Release(); err != nil {
			panic("session(release): " + err.Error())
		}
	}
}

// Provider is the interface that provides session manipulations.
type Provider interface {
	// Init initializes session provider.
	Init(gclifetime int64, config string) error
	// Read returns raw session store by session ID.
	Read(sid string) (RawStore, error)
	// Exist returns true if session with given ID exists.
	Exist(sid string) bool
	// Destory deletes a session by session ID.
	Destory(sid string) error
	// Regenerate regenerates a session store from old session ID to new one.
	Regenerate(oldsid, sid string) (RawStore, error)
	//跟新登录状态
	SetLogin(sid string, islogin bool) error
	// Count counts and returns number of sessions.
	Count() int
	// GC calls GC to clean expired sessions.
	GC()
}

var providers = make(map[string]Provider)

// Register registers a provider.
func Register(name string, provider Provider) {
	if provider == nil {
		panic("session: cannot register provider with nil value")
	}
	if _, dup := providers[name]; dup {
		panic(fmt.Errorf("session: cannot register provider '%s' twice", name))
	}
	providers[name] = provider
}

// Manager represents a struct that contains session provider and its configuration.
type Manager struct {
	provider Provider
	opt      Options
}

// NewManager creates and returns a new session manager by given provider name and configuration.
// It panics when given provider isn't registered.
func NewManager(name string, opt Options) (*Manager, error) {
	p, ok := providers[name]
	if !ok {
		return nil, fmt.Errorf("session: unknown provider '%s'(forgotten import?)", name)
	}
	return &Manager{p, opt}, p.Init(opt.Maxlifetime, opt.ProviderConfig)
}

// sessionId generates a new session ID with rand string, unix nano time, remote addr by hash function.
func (m *Manager) sessionId() string {
	return hex.EncodeToString(generateRandomKey(m.opt.IDLength / 2))
}

// Start starts a session by generating new one
// or retrieve existence one by reading session ID from HTTP request if it's valid.
func (m *Manager) Start(ctx *webcore.Context) (RawStore, error) {
	sid := ctx.GetCookie(m.opt.CookieName)
	//如果有session
	if len(sid) > 0 && m.provider.Exist(sid) {
		return m.provider.Read(sid)
		rs, err := m.provider.Read(sid)
		if err == nil {
			return rs, nil
		} else {
			m.provider.Destory(sid)
		}
	}
	//没有,创建一个新session
	sid2 := m.sessionId()
	sess, err := m.provider.Read(sid2)
	if err != nil {
		return nil, err
	}

	cookie := &http.Cookie{
		Name:     m.opt.CookieName,
		Value:    sid2,
		Path:     m.opt.CookiePath,
		HttpOnly: true,
		Secure:   m.opt.Secure,
		Domain:   m.opt.Domain,
	}
	if m.opt.CookieLifeTime >= 0 {
		cookie.MaxAge = m.opt.CookieLifeTime
	}
	//都添加cookie
	http.SetCookie(ctx.Resp, cookie)
	ctx.Req.AddCookie(cookie)
	return sess, nil
}

// Read returns raw session store by session ID.
func (m *Manager) Read(sid string) (RawStore, error) {
	return m.provider.Read(sid)
}

// Destory deletes a session by given ID.
func (m *Manager) Destory(ctx *webcore.Context) error {
	sid := ctx.GetCookie(m.opt.CookieName)
	if len(sid) == 0 {
		return nil
	}

	if err := m.provider.Destory(sid); err != nil {
		return err
	}
	cookie := &http.Cookie{
		Name:     m.opt.CookieName,
		Path:     m.opt.CookiePath,
		HttpOnly: true,
		Expires:  time.Now(),
		MaxAge:   -1,
	}
	http.SetCookie(ctx.Resp, cookie)
	return nil
}

func (m *Manager) SetLoginId(ctx *webcore.Context, islogin bool) error {
	sid := ctx.GetCookie(m.opt.CookieName)
	if len(sid) == 0 {
		return fmt.Errorf(" sid '%s' is not exists", sid)
	}
	return m.provider.SetLogin(sid, islogin)
}

// RegenerateId regenerates a session store from old session ID to new one.
func (m *Manager) RegenerateId(ctx *webcore.Context) (sess RawStore, err error) {
	sid := m.sessionId()
	oldsid := ctx.GetCookie(m.opt.CookieName)
	sess, err = m.provider.Regenerate(oldsid, sid)
	if err != nil {
		return nil, err
	}
	ck := &http.Cookie{
		Name:     m.opt.CookieName,
		Value:    sid,
		Path:     m.opt.CookiePath,
		HttpOnly: true,
		Secure:   m.opt.Secure,
		Domain:   m.opt.Domain,
	}
	if m.opt.CookieLifeTime >= 0 {
		ck.MaxAge = m.opt.CookieLifeTime
	}
	http.SetCookie(ctx.Resp, ck)
	ctx.Req.AddCookie(ck)
	return sess, nil
}

// Count counts and returns number of sessions.
func (m *Manager) Count() int {
	return m.provider.Count()
}

// GC starts GC job in a certain period.
func (m *Manager) GC() {
	m.provider.GC()
}

// startGC starts GC job in a certain period.
func (m *Manager) startGC() {
	m.GC()
	time.AfterFunc(time.Duration(m.opt.Gclifetime)*time.Second, func() { m.startGC() })
}

// SetSecure indicates whether to set cookie with HTTPS or not.
func (m *Manager) SetSecure(secure bool) {
	m.opt.Secure = secure
}
