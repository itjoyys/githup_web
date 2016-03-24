package aba

import (
	m "app/models"
	"common"
	"time"

	"fmt"
	"strings"
	"utility"
)

const (
	username_fix = ""
	//Password     = "jd822f242123"
)

type User struct {
	UserName    string    `xorm:"pk 'username'"` // `db:"PK" sql:"username" tname:"og_user"` //16位
	GUserName   string    `xorm:"unique 'g_username'"`
	Sessionid   string    `xorm:"'sessionid'"`
	Sessiontime time.Time `xorm:"'sessiontime'"`
	Password    string    `xorm:"unique 'password'"`
	Balance     float64   `xorm:"'balance'"`
	AgentId     int       `xorm:"'agent_id'"`
	SiteId      string    `xorm:"pk 'site_id'"`
	IndexId     string    `xorm:"'index_id'"`
	Cur         string    `xorm:"'cur'"`
	CreateTime  time.Time `xorm:"pk 'createtime'"`
	Status      int       `xorm:"pk 'status'"`
}

func (a *User) TableName() string {
	return "aba_user"
}

func Password() string {
	return string(utility.RandomCreateBytes(8))
}

//bbin用户名只可以使用小写
//保存用户
func CreateUser(user *User) error {
	user.CreateTime = time.Now()
	user.Balance = 0.00
	user.Status = 1
	user.GUserName = strings.ToLower(user.GUserName)
	_, err := m.Orm.InsertOne(user)
	if err == nil {
		user_v := fmt.Sprintf("%v'%v'%v'%v'%v'%v'%v'%v",
			user.UserName, user.AgentId, user.Password, user.GUserName, user.SiteId, user.Balance, user.Cur, user.CreateTime)
		sc := m.Redisclient.MSet("user:aba:"+user.GUserName, user_v)
		if sc.Err() != nil {
			common.Log.Err("Redisclient MSet:", sc.Err(), user)
			m.Redis_user_error[1] = 1
		}
	}
	return err
}

//获取多个
func GetUserByGname(gnames []string) (map[string]User, error) {
	musers := make(map[string]User)
	if len(gnames) == 0 {
		return musers, nil
	}
	var mgname []string
	for _, v := range gnames {
		mgname = append(mgname, "user:aba:"+strings.ToLower(v))
	}
	users := make([]User, 0)
	rc := m.Redisclient.MGet(mgname...)
	if len(rc.Val()) > 0 {
		for _, v := range rc.Val() {
			if v == nil {
				users = make([]User, 0)
				break
			}
			user := parseValue(v.(string))
			if len(user.UserName) == 0 {
				users = make([]User, 0)
				break
			}
			users = append(users, user)
		}
	}
	if len(users) == 0 {
		err := m.Orm.In("g_username", gnames).Find(&users)
		if err != nil {
			return musers, err
		}
	}
	if len(users) > 0 {
		for _, v := range users {
			v.GUserName = strings.ToLower(v.GUserName)
			musers[v.GUserName] = v
		}
	}
	return musers, nil
}

func GetUserInfo(username, siteid string) (*User, error) {
	user := new(User)
	has, err := m.Orm.Where("username = ? AND site_id = ?", username, siteid).Get(user)
	if err != nil {
		return user, err
	}
	if !has {
		return user, m.ErrNotExist
	}
	user_v := fmt.Sprintf("%v'%v'%v'%v'%v'%v'%v'%v", user.UserName, user.AgentId, user.Password, strings.ToLower(user.GUserName), user.SiteId, user.Balance, user.Cur, user.CreateTime)
	m.Redisclient.MSet("user:aba:"+strings.ToLower(user.GUserName), user_v)
	return user, nil
}

func GetGUserNames(username string) (string, error) {
	users := make([]User, 0)
	err := m.Orm.Where("username = ?", username).Find(&users)
	if err != nil {
		return "", err
	}
	guser := ""
	for _, v := range users {
		guser += strings.ToLower(v.GUserName) + "|"
	}
	return guser, nil
}

func IsUserExist(username, siteid string) bool {
	user := new(User)
	has, err := m.Orm.Where("username = ? AND site_id = ?", username, siteid).Get(user)
	if err != nil {
		return false
	}
	return has
}

func IsGUserNotExist(gname string) bool {
	user := new(User)
	has, err := m.Orm.Where("g_username =?", gname).Get(user)
	if err != nil {
		return false
	}
	return !has
}

//加载用户到redis中
func DZ_LoadAllUserInRedis() error {
	pagesize := 300
	i := 0
	hasuser := true
	for {
		if !hasuser {
			break
		}
		users := make([]User, 0)
		err := m.Orm.Limit(pagesize, i*pagesize).Where("status = 1").OrderBy("createtime").Find(&users)

		if err != nil {
			return err
		}
		count := len(users)
		guser := make([]string, 0)
		if count > 0 {
			for _, v := range users {
				guser = append(guser, "user:aba:"+strings.ToLower(v.GUserName))
				user_v := fmt.Sprintf("%v'%v'%v'%v'%v'%v'%v'%v", v.UserName, v.AgentId, v.Password, strings.ToLower(v.GUserName), v.SiteId, v.Balance, v.Cur, v.CreateTime)
				guser = append(guser, user_v)
			}
			sc := m.Redisclient.MSet(guser...)
			if sc.Err() != nil {
				common.Log.Err("Redisclient MSet:", sc.Err(), i)
				m.Redis_user_error[1] = 1
				return err
			}
		}
		if count == pagesize {
			hasuser = true
		} else {
			hasuser = false
		}
		i++
	}
	return nil
}

func parseValue(str string) User {
	temp_a := strings.Split(str, "'")
	user := User{}
	if len(temp_a) == 8 {
		user.UserName = temp_a[0]
		user.AgentId, _ = utility.StrTo(temp_a[1]).Int()
		user.Password = temp_a[2]
		user.GUserName = temp_a[3]
		user.SiteId = temp_a[4]
		user.Balance, _ = utility.StrTo(temp_a[5]).Float64()
		user.Cur = temp_a[6]
		user.CreateTime, _ = time.Parse("2006-01-02 15:04:05 -0700 MST", temp_a[7])
	}
	return user
}
