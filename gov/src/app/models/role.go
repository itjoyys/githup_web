package models

type Role struct {
	RoleId     int64 `db:"PK" tname:"game_role"`
	Rolename   string
	Desc       string
	Data       string
	Createtime int64
	Status     int8
}
