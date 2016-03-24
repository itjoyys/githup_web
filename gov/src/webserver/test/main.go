package main

import (
	"time"
	"fmt"
)

func main(){

	loc, ee := time.LoadLocation("GMT")
	fmt.Println(ee)
	//time2, _ := time.ParseInLocation("2006-01-02 15:04",time.Now().Format("2006-01-02 15:04"), loc)
	time2 := time.Now().In(loc)
	d, _ := time.ParseDuration("-4h") //转换服务器时间为中国时间
	time2 = time2.Add(d)
	fmt.Println(time.Now().Format("2006-01-02 15:04"))
	fmt.Println(time2.Format("2006-01-02 15:04"))

}
