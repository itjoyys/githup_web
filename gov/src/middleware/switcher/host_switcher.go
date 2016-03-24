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

// Package switcher is a helper module that provides host switch functionality for Macaron.
package switcher

import (
	"net/http"
	_ "strings"

	"core/webcore"
	com "utility"
)

// HostSwitcher represents a global multi-site support layer.
type HostSwitcher struct {
	switches map[string]*webcore.Macaron
	list     []string
}

// NewHostSwitcher initalizes and returns a new host switcher.
// You have to use this function to get a new host switcher.
func NewHostSwitcher() *HostSwitcher {
	return &HostSwitcher{
		switches: make(map[string]*webcore.Macaron),
	}
}

// Set adds a new switch to host switcher.
func (hs *HostSwitcher) Set(host string, m *webcore.Macaron) {
	hs.switches[host] = m
	hs.list = append(hs.list, host)
}

// Remove removes a switch from host switcher.
func (hs *HostSwitcher) Remove(host string) {
	delete(hs.switches, host)
	idx := -1
	for i, name := range hs.list {
		if name == host {
			idx = i
			break
		}
	}
	if idx >= 0 {
		hs.list = append(hs.list[:idx], hs.list[idx+1:]...)
	}
}

// ServeHTTP is the HTTP Entry point for a Host Switcher instance.
func (hs *HostSwitcher) ServeHTTP(resp http.ResponseWriter, req *http.Request) {
	if h := hs.switches[req.Host]; h != nil {
		h.ServeHTTP(resp, req)
	} else {
		http.Error(resp, "Not Found", http.StatusNotFound)
	}
}

// RunOnAddr runs server in given address and port.
func (hs *HostSwitcher) RunOnAddr(addr string) {
	/*
		if webcore.Env == webcore.DEV {
			infos := strings.Split(addr, ":")
			port := com.StrTo(infos[1]).MustInt()
			for _, host := range hs.list[:len(hs.list)-1] {
				go hs.switches[host].Run(infos[0], port)
				port++
			}
			hs.switches[hs.list[len(hs.list)-1]].Run(infos[0], port)
			return
		}
	*/
	http.ListenAndServe(addr, hs)
}

// GetDefaultListenAddr returns default server listen address of Macaron.
func GetDefaultListenAddr() string {
	host, port := webcore.GetDefaultListenInfo()
	return host + ":" + com.ToStr(port)
}

// Run the http server. Listening on os.GetEnv("PORT") or 4000 by default.
func (hs *HostSwitcher) Run() {
	hs.RunOnAddr(GetDefaultListenAddr())
}
