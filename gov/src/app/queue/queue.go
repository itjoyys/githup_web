package queue

import (
	"bytes"
	"common"
	"fmt"
	"io/ioutil"
	"net"
	"runtime"
	"strings"
	"time"

	"app/models"
//mag "app/models/ag"
//mbbin "app/models/bbin"
//mmg "app/models/mg"
)

var (
	dunno = []byte("???")
	centerDot = []byte("·")
	dot = []byte(".")
	slash = []byte("/")
	bbinq     *BbinQueue
	timeag = "00:30:00"
	timemg = "00:50:00"
	timebbin = "01:00:00"
	timelebo = "01:30:00"
)

//验证多ip
func Verify_ips() error {
	multi_ip := common.Cfg.Section("app").Key("LOCAL_IPS").MustString("")
	ips_arr := strings.Split(multi_ip, ",")
	for _, v := range ips_arr {
		lAddr, err := net.ResolveTCPAddr("tcp", v + ":0")
		if err != nil {
			return err
		}
		//被请求的地址
		rAddr, err := net.ResolveTCPAddr("tcp", "8.8.8.8:53")
		if err != nil {
			return err
		}
		_, err = net.DialTCP("tcp", lAddr, rAddr)
		if err != nil {
			return err
		}
	}
	return nil
}

/*
//队列维护

CT:  直接获取，获取玩以后标记，(3分钟扫描一次)

MG:：3小时扫描一次，先获取guid，过5分钟后分页获取结果  （跟新日志写入数据库）

OG: 3分钟扫描一次，根据数据库最大的vid

AG: 根据ftp文件名生成下一次要获取文件的时间，15分钟取一次ftp文件

*/
func StartQueue() {
	ag_conf_init()          //ag初始化
	bbinq = NewQueue(60000) //6000的队列
	timeag = common.Cfg.Section("CHECKYDAYBET").Key("ag").MustString("14:30:00")
	timemg = common.Cfg.Section("CHECKYDAYBET").Key("mg").MustString("15:30:00")
	timebbin = common.Cfg.Section("CHECKYDAYBET").Key("bbin").MustString("14:00:00")
	timelebo = common.Cfg.Section("CHECKYDAYBET").Key("lebo").MustString("15:00:00")
	ticker_og := time.NewTicker(190 * time.Second)
	ticker_ag := time.NewTicker(180 * time.Second)
	ticker_mg := time.NewTicker(150 * time.Second)
	ticker_ct := time.NewTicker(55 * time.Second) //55
	ticker_lebo := time.NewTicker(124 * time.Second)
	ticker_bbin := time.NewTicker(200 * time.Second) //160
	ticker_eg := time.NewTicker(2 * time.Second)
	bbin_queue_timer := time.NewTicker(1 * time.Second)
	ticker_pt := time.NewTicker(240 * time.Second)

	defer func() {
		ticker_og.Stop()
		ticker_ag.Stop()
		ticker_mg.Stop()
		ticker_ct.Stop()
		ticker_lebo.Stop()
		ticker_bbin.Stop()
		ticker_eg.Stop()
		bbin_queue_timer.Stop()
		ticker_pt.Stop()
	}()
	//时间队列
	for {
		select {
		case <-ticker_og.C:
			go og_GetRecords() //获取注单记录
		case <-ticker_ag.C:
			go ag_GetFtpFiles() //获取注单记录
		case <-ticker_mg.C:
			go mg_GetAllSiteBetInfoDetails("") //获取注单记录
		case <-ticker_eg.C:
			go Eg_GetBetRecord()
		case <-ticker_ct.C:
			go ct_GetAllSiteBetRecord() //获取注单记录
		case <-ticker_lebo.C:
			go lebo_GetAllBetRecord()
		case <-ticker_bbin.C:
			go bbin_GetAllBetRecord()
			go BBIN_verify1()
		case <-ticker_pt.C:
			go pt_GetAllBets()
		case timer := <-bbin_queue_timer.C:
			multi_ip := common.Cfg.Section("app").Key("LOCAL_IPS").MustString("")
			ips_arr := strings.Split(multi_ip, ",")
			if len(ips_arr) > 1 {
				for _, v := range ips_arr {
					go bbin_do_queue(bbinq.Pull(), v)
				}
			} else {
				go bbin_do_queue(bbinq.Pull(), "")
			}

			d, _ := time.ParseDuration("-23h")
			yday := timer.Add(d)
		//生成0点时间
			ydata := yday.Format("2006-01-02")
			yday_time, err := time.Parse("2006-01-02 15:04:05", ydata + " 00:00:00")
			if err != nil {
				yday_time = yday
			}
			nowstr := timer.Format("15:04:05")
			if nowstr == timeag {
				ag_auto_collect(yday_time)
			} else if nowstr == timemg {
				mg_auto_collect(yday_time.Format("2006-01-02 15:04:05"))
			} else if nowstr == timebbin {
				bbin_auto_collect(yday_time)
				//查看是否需要新建表
				correcttime := timer.AddDate(0, 0, 2)
				if correcttime.Month().String() != timer.Month().String() {
					err := models.CreateAllBetTableByDate(correcttime.Format("200601"))
					if err != nil {
						//TODO email
					}
				}
				correcttime = timer.AddDate(0, 0, 1)
				if correcttime.Month().String() != timer.Month().String() {
					err := models.CreateAllBetTableByDate(correcttime.Format("200601"))
					if err != nil {
						//TODO email
					}
				}
			} else if nowstr == timelebo {

			}
		}
	}
}

// stack returns a nicely formated stack frame, skipping skip frames
func stack(skip int) []byte {
	buf := new(bytes.Buffer) // the returned data
	// As we loop, we open files and read them. These variables record the currently
	// loaded file.
	var lines [][]byte
	var lastFile string
	for i := skip;; i++ {
		// Skip the expected number of frames
		pc, file, line, ok := runtime.Caller(i)
		if !ok {
			break
		}
		// Print this much at least.  If we can't find the source, it won't show.
		fmt.Fprintf(buf, "%s:%d (0x%x)\n", file, line, pc)
		if file != lastFile {
			data, err := ioutil.ReadFile(file)
			if err != nil {
				continue
			}
			lines = bytes.Split(data, []byte{'\n'})
			lastFile = file
		}
		fmt.Fprintf(buf, "\t%s: %s\n", function(pc), source(lines, line))
	}
	return buf.Bytes()
}

// function returns, if possible, the name of the function containing the PC.
func function(pc uintptr) []byte {
	fn := runtime.FuncForPC(pc)
	if fn == nil {
		return dunno
	}
	name := []byte(fn.Name())
	// The name includes the path name to the package, which is unnecessary
	// since the file name is already included.  Plus, it has center dots.
	// That is, we see
	//	runtime/debug.*T·ptrmethod
	// and want
	//	*T.ptrmethod
	// Also the package path might contains dot (e.g. code.google.com/...),
	// so first eliminate the path prefix
	if lastslash := bytes.LastIndex(name, slash); lastslash >= 0 {
		name = name[lastslash + 1:]
	}
	if period := bytes.Index(name, dot); period >= 0 {
		name = name[period + 1:]
	}
	name = bytes.Replace(name, centerDot, dot, -1)
	return name
}

// source returns a space-trimmed slice of the n'th line.
func source(lines [][]byte, n int) []byte {
	n-- // in stack trace, lines are 1-indexed but our array is 0-indexed
	if n < 0 || n >= len(lines) {
		return dunno
	}
	return bytes.TrimSpace(lines[n])
}
