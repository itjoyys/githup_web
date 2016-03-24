package common

import (
	"errors"
	"math"
)

type Page struct {
	//Startid int64	//当前页的第一个id
	Pagenum      int64 //当前页数
	Pagecount    int64 //共多少页
	Count        int64 //共多少条
	Prepagecount int   //每页显示多少条

	Pagenums []int64 //显示的页数{默认5个}
}

//page int64, count int64, prepagecount int
func (p *Page) GetStartId() (int64, error) {
	if p.Pagenum < 1 {
		p.Pagenum = 1
	}
	var startid int64 = 0
	if p.Count > int64(p.Prepagecount) {
		p.Pagecount = int64(math.Ceil(float64(p.Count) / float64(p.Prepagecount)))
		if p.Pagenum > p.Pagecount {
			return 0, errors.New("error page count")
			//startid = (p.Pagecount - 1) * int64(p.Prepagecount)
		} else {
			startid = (p.Pagenum - 1) * int64(p.Prepagecount)
		}
	}
	//确认显示的页数
	//获得上一页和主页的链接
	//下一页,未页的链接
	//获得数字链接
	var lens int64 = 7
	var j int64 = 0
	if p.Pagenum >= lens {
		j = p.Pagenum - 5
		lens = p.Pagenum + 5
		if lens > p.Pagecount {
			lens = p.Pagecount
		}
	} else {
		j = 1
		if lens > p.Pagecount {
			lens = p.Pagecount
		}
	}
	for {
		p.Pagenums = append(p.Pagenums, j)
		j++
		if j > lens {
			break
		}
	}

	return startid, nil
}
