package models

import (
	"fmt"
)

type Site struct {
	SiteID         string `xorm:"pk 'site_id'" json:"site_id"`
	SiteName       string `xorm:"'site_name'" json:"site_name"`
	SiteDomain     string `xorm:"'site_domain'" json:"site_domain"`
	SiteIp         string `xorm:"'site_ip'" json:"site_ip"`
	SiteDesKey     string `xorm:"'site_des_key'" json:"site_des_key"`
	SiteMd5Key     string `xorm:"'site_md5_key'" json:"site_md5_key"`
	SiteModules    string `xorm:"'site_modules'" json:"site_modules"`
	Mg_AgentName   string `xorm:"'mg_agentname'" json:"mg_agentname"`
	Mg_AgentPWD    string `xorm:"'mg_agentpwd'" json:"mg_agentpwd"`
	Lebo_AgentName string `xorm:"'lebo_agentname'" json:"lebo_agentname"`
	Lebo_AgentPWD  string `xorm:"'lebo_agentpwd'" json:"lebo_agentpwd"`
	Ct_AgentName   string `xorm:"'ct_agentname'" json:"ct_agentname"`
	Ct_AgentPWD    string `xorm:"'ct_agentpwd'" json:"ct_agentpwd"`
	Og_AgentName   string `xorm:"'og_agentname'" json:"og_agentname"`
	Og_AgentPWD    string `xorm:"'og_agentpwd'" json:"og_agentpwd"`
	Bbin_AgentName string `xorm:"'bbin_agentname'" json:"bbin_agentname"`
	Bbin_AgentPWD  string `xorm:"'bbin_agentpwd'" json:"bbin_agentpwd"`
	Eg_Agentname string `xorm:"'eg_agentname'" json:"eg_agentname"`
	CreateTime     string `xorm:"'create_time'" json:"-"`
}

func (site *Site) TableName() string {
	return "game_sites"
}

//根据id返回网站的配置文件
func GetSiteById(siteid string) (*Site, error) {
	site := new(Site)
	_, err := Orm.Where("status = 1 AND site_id = ?", siteid).Get(site)
	return site, err
}

//根据id返回网站的配置文件
func GetSiteByIdForApi(siteid string) (*Site, error) {
	site := new(Site)
	_, err := Orm.Where("site_id = ?", siteid).Get(site)
	return site, err
}

//后去所有站点的id给买个采集队列使用
func GetAllSiteIdForMg() ([]string, error) {
	var sites []Site
	err := Orm.Cols("site_id").Where("status = 1 AND FIND_IN_SET('mg',site_modules)").Find(&sites)
	if err != nil {
		return nil, err
	}
	sids := make([]string, 0)
	for _, v := range sites {
		sids = append(sids, v.SiteID)
	}
	return sids, err
}

func GetAllSiteIdForLebo() ([]string, error) {
	var sites []Site
	err := Orm.Cols("site_id").Where("status = 1 AND FIND_IN_SET('lebo',site_modules)").Find(&sites)
	if err != nil {
		return nil, err
	}
	sids := make([]string, 0)
	for _, v := range sites {
		sids = append(sids, v.SiteID)
	}
	return sids, err
}

//后去所有站点的id给买个采集队列使用
func GetAllSiteIdForOg() ([]string, error) {
	var sites []Site
	err := Orm.Cols("site_id").Where("status = 1 AND FIND_IN_SET('og',site_modules)").Find(&sites)
	if err != nil {
		return nil, err
	}
	sids := make([]string, 0)
	for _, v := range sites {
		sids = append(sids, v.SiteID)
	}
	return sids, err
}

func GetAllSiteIdForCt() ([]string, error) {
	var sites []Site
	err := Orm.Cols("site_id").Where("status = 1 AND FIND_IN_SET('ct',site_modules)").Find(&sites)
	if err != nil {
		return nil, err
	}
	sids := make([]string, 0)
	for _, v := range sites {
		sids = append(sids, v.SiteID)
	}
	return sids, err
}

func GetAllSiteIdForBbin() ([]string, error) {
	var sites []Site
	err := Orm.Cols("site_id").Where("status = 1 AND FIND_IN_SET('bbin',site_modules)").Find(&sites)
	if err != nil {
		return nil, err
	}
	sids := make([]string, 0)
	for _, v := range sites {
		sids = append(sids, v.SiteID)
	}
	return sids, err
}

/**
* 1 mg，2 lebo
 */
func UpdateAgentPwd(vtype int, siteid, pwd string) error {
	site := new(Site)
	if vtype == 1 {
		site.Mg_AgentPWD = pwd
		_, err := Orm.Id(siteid).Update(site)
		if err != nil {
			return err
		}
	} else if vtype == 2 {
		site.Lebo_AgentPWD = pwd
		_, err := Orm.Id(siteid).Update(site)
		if err != nil {
			return err
		}
	}

	return nil
}

//判断ip和useragent是否允许访问
func IsAllowRequest(ip, siteid string) (*Site, error) {
	site := new(Site)
	has, err := Orm.Where("status = 1 AND FIND_IN_SET(?,site_ip) AND site_id=? ", ip, siteid).Get(site)
	fmt.Println(site)
	if err != nil {
		return nil, err
	}
	if has {
		return site, nil
	} else {
		return nil, ErrNotExist
	}
}
