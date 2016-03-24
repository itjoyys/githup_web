package models

type Menu struct {
	MenuId   int64 `db:"PK" tname:"game_menu"`
	Name     string
	Enname   string
	Pid      int64 //父id
	Url      string
	Icon     string
	Data     string
	SortRank int64
	Display  int8
}

func (a *Menu) TableName() string {
	return "game_menu"
}

//获取sidebar的类容
func GetLeftMenuHtml() (string, error) {
	menus := make([]*Menu, 0)
	err := Orm.Where("display = ?", 1).Find(&menus)
	if err != nil {
		return "", err
	}
	menu_list := make(map[int64][]*Menu)
	for _, menu := range menus {
		if _, ok := menu_list[menu.Pid]; !ok {
			menu_list[menu.Pid] = make([]*Menu, 0)
		}
		menu_list[menu.Pid] = append(menu_list[menu.Pid], menu)
	}
	html := ""

	for _, menu := range menu_list[0] {
		html = html + getItemMenus(menu, menus, menu_list)
	}

	return html, nil
}

//菜单递归
func getItemMenus(self *Menu, menus []*Menu, menu_list map[int64][]*Menu) string {
	//没有儿子
	if _, ok := menu_list[self.MenuId]; !ok {
		return "<li><a href=\"" + self.Url + "\">" + self.Name + "</a></li>"
	} else {
		html := ""
		if self.Pid == 0 {
			html = `<li class=""><a href="javascript:;"><i class="icon-` + self.Icon + `"></i><span class="title">` + self.Name +
				`</span><span class="arrow "></span></a><ul class="sub-menu">`
		} else {
			html = `<li class=""><a href="javascript:;">` + self.Name + `</span><span class="arrow "></span></a><ul class="sub-menu">`
		}

		for _, menu := range menu_list[self.MenuId] {
			//有儿子
			html = html + getItemMenus(menu, menus, menu_list)
		}
		return html + "</ul></li>"
	}
}

//获取所有菜单
func GetAllMenus() (map[int64][]*Menu, error) {
	menus := make([]*Menu, 0)
	err := Orm.Where("display = ?", 1).Find(&menus)
	if err != nil {
		return nil, err
	}

	//初始化菜单Map
	menu_list := make(map[int64][]*Menu)
	for _, menu := range menus {
		if _, ok := menu_list[menu.Pid]; !ok {
			menu_list[menu.Pid] = make([]*Menu, 0)
		}
		menu_list[menu.Pid] = append(menu_list[menu.Pid], menu)
	}

	return menu_list, nil
}
