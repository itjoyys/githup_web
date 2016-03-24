package models

import (
	"errors"
	"time"

	"common"
	//"utility"
)

var (
	ErrEmailAlreadyUsed     = errors.New("E-mail already used")
	ErrEmailNotActivated    = errors.New("E-mail address has not been activated")
	ErrUserNameIllegal      = errors.New("User name contains illegal characters")
	ErrUnsupportedLoginType = errors.New("Login source is unknown")

	table_admin = "game_admin"
)

//`xorm:"varchar(25) notnull unique 'usr_name'"`

type Admin struct {
	AdminId       int64 `db:"PK" tname:"game_admin"`
	Username      string
	Password      string
	RoleId        int64
	Role          *Role `sql:"-"`
	Lastloginip   string
	Lastlogintime int64
	Email         string
	Realname      string
	Lang          string
	Status        int8
	Createtime    int64
}

func (a *Admin) TableName() string {
	return "game_admin"
}

func GetAdminsByRole(rid int64, num, offset int) ([]*Admin, error) {
	admins := make([]*Admin, 0)
	err := Orm.Where("status = ? AND roleid = ?", 1, rid).Limit(num, offset).Find(&admins)
	//err := Orm.Where("status = ? AND roleid = ?", 1, rid).Limit(num, offset).FindAll(&admins)
	return admins, err
}

//用户名是否存在
func IsAdminExist(name string) (bool, error) {
	user := new(Admin)
	return Orm.Where("username = ?", name).Get(user)
}

//邮箱是否存在
func IsEmailUsed(email string) (bool, error) {
	user := new(Admin)
	return Orm.Where("email = ?", email).Get(user)
}

func CreateAdmin(admin *Admin) error {
	errmsg := common.NameValidate(admin.Username)
	if errmsg.ErrorId > 0 {
		return errors.New(errmsg.Msg)
	}

	isExist, _ := IsEmailUsed(admin.Email)
	if isExist {
		return ErrEmailAlreadyUsed
	}

	admin.Password = common.Md5(admin.Password)
	admin.Createtime = time.Now().Unix()
	admin.Status = 1

	_, err := Orm.Insert(admin)
	return err
}

func UpdateAdmin(user *Admin) error {
	errmsg := common.NameValidate(user.Username)
	if errmsg.ErrorId > 0 {
		return errors.New(errmsg.Msg)
	}
	if len(user.Password) > 0 {
		user.Password = common.Md5(user.Password)
	}
	user.Status = 1
_, err := Orm.Id(user.AdminId).Update(user)
	return err
}

func GetAdminById(uid int64) (*Admin, error) {
	user := new(Admin)
	_,err := Orm.Where("adminid = ?", uid).Get(user)
	return user,err
}

func DelAdminByid(uid int64) {
	user := new(Admin)
	//affected, err := 
	
	Orm.Id(uid).Delete(user)
}

func AdminSignIn(uname, password string) (*Admin, error) {
	u := new(Admin)
	err := Orm.Where("username = ? ", uname).Find(u)
	if err != nil {
		return nil, err
	}
	if common.Md5(password) == u.Password {
		return u, nil
	}
	return nil, ErrUnsupportedLoginType
}

func CountAdmins() (int64, error) {
	
	user := new(Admin)
    return Orm.Where("status = ?", 1).Count(user)
}

// GetUsers returns given number of user objects with offset.
func GetAdmins(num, offset int) ([]*Admin, error) {
	admins := make([]*Admin, 0)
	err := Orm.Where("status = ?", 1).Limit(num, offset).Find(&admins)
	return admins, err
}
