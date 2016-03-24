package models

/*
import (
	"errors"
	"time"

	"common"
	"utility"
)

//用户
type User struct {
	UserId      int64 `db:"PK" tname:"zeng_user"`
	UserName    string
	Password    string
	NickName    string
	Role        string
	Photo       string
	Email       string
	Description string
	CreateTime  int64
	Status      int8
}

//用户名是否存在
func IsUserExist(name string) (bool, error) {
	user := new(User)
	err := Orm.Where("username = ?", name).Find(user)
	return user.UserId > 0, err
}

/*
//邮箱是否存在
func IsEmailUsed(email string) (bool, error) {
	user := new(User)
	err := Orm.Where("email = ?", email).Find(user)
	return user.UserId > 0, err
}
*/
/*
func CreateUser(user *User) error {
	errmsg := common.NameValidate(user.UserName)
	if errmsg.ErrorId > 0 {
		return errors.New(errmsg.Msg)
	}

	isExist, _ := IsEmailUsed(user.Email)
	if isExist {
		return ErrEmailAlreadyUsed
	}

	user.Password = common.Md5(user.Password)
	user.CreateTime = time.Now().Unix()
	user.Status = 1

	return Orm.Save(user)
}

func UpdateUser(user *User) error {
	errmsg := common.NameValidate(user.UserName)
	if errmsg.ErrorId > 0 {
		return errors.New(errmsg.Msg)
	}
	if len(user.Password) > 0 {
		user.Password = common.Md5(user.Password)
	}
	user.Status = 1

	return Orm.Save(user)
}

func GetUserById(uid int64) (*User, error) {
	user := new(User)
	return user, Orm.Where("userid = ?", uid).Find(user)
}

func DelUserByid(uid int64) {
	Orm.SetTable(table_user).Where("userid = ?", uid).DeleteRow()
}

func UserSignIn(uname, password string) (*User, error) {
	u := new(User)
	err := Orm.Where("username = ? ", uname).Find(u)
	if err != nil {
		return nil, err
	}
	if common.Md5(password) == u.Password {
		return u, nil
	}
	return nil, ErrUnsupportedLoginType
}

func CountUsers() (int64, error) {
	c, err := Orm.
		SetTable(table_user).
		SetPK("userid").
		Where("status = ?", 1).
		Select("count(*) as count").
		FindMap()

	if len(c) > 0 {
		if count, ok := c[0]["count"]; ok {
			intcount, err := utility.StrTo(count).Int64()
			return intcount, err
		}
	}
	return 0, err
}

// GetUsers returns given number of user objects with offset.
func GetUsers(num, offset int) ([]*User, error) {
	users := make([]*User, 0, num)
	err := Orm.Where("status = ?", 1).Limit(num, offset).FindAll(&users)
	return users, err
}
*/
