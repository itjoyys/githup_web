package queue

/**
 *
 *CREATE TABLE `ag_xmlfile` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `filenameid` bigint(20) NOT NULL,
  `lostfilenameid` bigint(20) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 *
 *
*/

import (
	"bufio"
	"encoding/xml"
	"io"
	"os"
//"sort"
//"fmt"
	"math"
	"strings"
	"time"

	m "app/models"
	mag "app/models/ag"
	"common"
	"core/goftp"
	"utility"
)

type lastfile struct {
	Name     int64
	FileName string
}

type lastfiles []lastfile

func initlastfiles() lastfiles {
	ms := make(lastfiles, 0)

	return ms
}

func (ms lastfiles) Len() int {
	return len(ms)
}

func (ms lastfiles) Less(i, j int) bool {
	return ms[i].Name > ms[j].Name // 按值排序
}

func (ms lastfiles) Swap(i, j int) {
	ms[i], ms[j] = ms[j], ms[i]
}

var (
	last24files                                    []lastfile
	host, hunterhost, username, password, temppath string
	queueid int
)

func ag_conf_init() {
	hunterhost = common.Cfg.Section("ag").Key("FTP_HUNTER_HOST").MustString("ftp.agingames.com:21")
	host = common.Cfg.Section("ag").Key("FTP_HOST").MustString("br.agingames.com:21")
	username = common.Cfg.Section("ag").Key("FTP_USERNAME").MustString("E59.pkbet")
	password = common.Cfg.Section("ag").Key("FTP_PASSWORD").MustString("E&GZnLR7^ff")
	temppath = common.Cfg.Section("ag").Key("FTP_TEMP_PATH").MustString("/home/sxgames/bin/ag_report")
}

//获取ftp上的文件并解析存入数据库
func ag_GetFtpFiles() {
	//获取前2天踩掉的数据
	if queueid == 0 {
		//验证前两天的数据一次
		startvtime, err := mag.GetTimeline()
		//fmt.Errorf(err,startvtime)
		if err == nil {
			mss, err := m.GetQueue("ag", startvtime)
			if err != nil {
				//队列不存在
				mag.StopTimeline() //执行一次
				return
			} else {
				queueid = mss.ID
			}
			go verifylogfile(startvtime.Format("20060102"))
			mag.StopTimeline() //执行一次
			m.UpdateQueueing(queueid)
			return
		}
	}
	//如时间在00点检查下前一天有没有忘记采集的数据
	//当天的数据全部查询
	nowtime := time.Now()
	nowhour := nowtime.Hour()
	//如果是0-3点 获取前一天的数据
	if nowhour == 0 {
		d, _ := time.ParseDuration("-12h") //转换服务器时间为中国时间
		oldtime := time.Now().Add(d)
		getlogfile(oldtime.Format("20060102"))
		time.Sleep(1 * time.Minute)
		getlogfile(time.Now().Format("20060102"))
	} else {
		getlogfile(time.Now().Format("20060102"))
	}
}

func ag_auto_collect(datetime time.Time) {
	go verifylogfile(datetime.Format("20060102"))
}

func Test_ag(startdate string) {
	verifylogfile(startdate)
}

//验证某一天的數據
func verifylogfile(startdate string) {
	ag_conf_init()
	//SetQueueTime()
	f := goftp.FtpOpe{}
	//获取捕鱼的
	hunterpath := "/HUNTER/" + startdate
	hunterfiles, err := f.FtpWalkDir(hunterhost, username, password, hunterpath)
	if err != nil {
		common.Log.Err("AG ftp HUNTER:", err)
		return
	} else {
		allfile := make(lastfiles, 0)
		for _, v := range hunterfiles {
			//找出12个
			if v.Filetype == 1 {
				//一个一个的获取解析
				file := lastfile{}
				file.FileName = v.Path + "/" + v.Name
				file.Name, err = utility.StrTo(strings.Replace(v.Name, ".xml", "", -1)).Int64()
				if err == nil {
					allfile = append(allfile, file)
				}
			}
		}
		getListxmlFile(allfile, 1, true)
	}

	filepath := "/AGIN/" + startdate
	files, err := f.FtpWalkDir(host, username, password, filepath)
	if err != nil {
		common.Log.Err("AG ftp :", err)
		return
	} else {
		allfiles := make(lastfiles, 0)
		for _, v := range files {
			//找出12个
			if v.Filetype == 1 {
				//一个一个的获取解析
				file := lastfile{}
				file.FileName = v.Path + "/" + v.Name
				file.Name, err = utility.StrTo(strings.Replace(v.Name, ".xml", "", -1)).Int64()
				if err == nil {
					allfiles = append(allfiles, file)
				}
			}
		}
		getListxmlFile(allfiles, 0, true)
	}

	lostAndfound := "/AGIN/lostAndfound/" + startdate //time.Now().Format("20060102")
	lostAndfoundfiles, err := f.FtpWalkDir(host, username, password, lostAndfound)
	if err != nil {
		common.Log.Err("AG ftp :", err)
		return
	} else {
		allfiles := make(lastfiles, 0)
		for _, v := range lostAndfoundfiles {
			//找出12个
			if v.Filetype == 1 {
				//一个一个的获取解析
				file := lastfile{}
				file.FileName = v.Path + "/" + v.Name
				file.Name, err = utility.StrTo(strings.Replace(v.Name, ".xml", "", -1)).Int64()
				if err == nil {
					allfiles = append(allfiles, file)
				}
			}
		}
		getListxmlFile(allfiles, 2, true)
	}
	//获取捕鱼的
	hunterlostAndfound := "/HUNTER/lostAndfound/" + startdate
	hunterlostAndfoundfiles, err := f.FtpWalkDir(hunterhost, username, password, hunterlostAndfound)
	if err != nil {
		common.Log.Err("AG ftp HUNTER:", err)
		return
	} else {
		allfile := make(lastfiles, 0)
		for _, v := range hunterlostAndfoundfiles {
			//找出12个
			if v.Filetype == 1 {
				//一个一个的获取解析
				file := lastfile{}
				file.FileName = v.Path + "/" + v.Name
				file.Name, err = utility.StrTo(strings.Replace(v.Name, ".xml", "", -1)).Int64()
				if err == nil {
					allfile = append(allfile, file)
				}
			}
		}
		getListxmlFile(allfile, 3, true)
	}

	m.FinishQueue(queueid)
	queueid = 0
}

/**
 * 算法问题
 *
 */
//读取最后8个文件 ///time.Now().Format("20060102")
func getlogfile(nowdate string) {
	ag_conf_init()
	filepath := "/AGIN/" + nowdate
	f := goftp.FtpOpe{}
	files, err := f.FtpWalkDir(host, username, password, filepath)
	if err != nil {
		common.Log.Err("AG ftp :", err)
		return
	} else {
		allfile := initlastfiles()
		lastlog, err := mag.GetLastFile(nowdate)
		if err != nil {
			//没有错误保存到数据库
			common.Log.Err("AG ftp GetLastFile:", err)
		}
		for _, v := range files {
			//找出12个
			if v.Filetype == 1 {
				//一个一个的获取解析
				file := lastfile{}
				file.FileName = v.Path + "/" + v.Name
				file.Name, err = utility.StrTo(strings.Replace(v.Name, ".xml", "", -1)).Int64()
				if err == nil {
					if lastlog <= file.Name {
						//后12个确定
						allfile = append(allfile, file)
					}

				}
			}
		}
		common.Log.Err("AG ftp GetFilelist:", allfile)
		getListxmlFile(allfile, 0, false)
	}
	//获取捕鱼的
	hunterpath := "/HUNTER/" + nowdate
	hunterfiles, err := f.FtpWalkDir(hunterhost, username, password, hunterpath)
	if err != nil {
		common.Log.Err("AG ftp HUNTER:", err)
		return
	} else {
		allfile := initlastfiles()
		lastlog, err := mag.GetLastHunterFile(nowdate)
		if err != nil {
			//没有错误保存到数据库
			common.Log.Err("AG ftp HUNTER GetLastFile:", err)
		}
		for _, v := range hunterfiles {
			//找出12个
			if v.Filetype == 1 {
				//一个一个的获取解析
				file := lastfile{}
				file.FileName = v.Path + "/" + v.Name
				file.Name, err = utility.StrTo(strings.Replace(v.Name, ".xml", "", -1)).Int64()
				if err == nil {
					if lastlog <= file.Name {
						//后12个确定
						allfile = append(allfile, file)
					}

				}
			}
		}
		common.Log.Err("AG ftp GetFilelist:", allfile)
		getListxmlFile(allfile, 1, false)
	}
	lostAndfound := "/AGIN/lostAndfound/" + nowdate //time.Now().Format("20060102")
	lostAndfoundfiles, err := f.FtpWalkDir(host, username, password, lostAndfound)
	if err != nil {
		common.Log.Err("AG ftp :", err)
		return
	} else {
		allfile := initlastfiles()
		lastlog, err := mag.GetLastLostFile(nowdate)
		if err != nil {
			common.Log.Err("AG ftp GetLastLostFile:", err)
		}
		for _, v := range lostAndfoundfiles {
			//找出12个
			if v.Filetype == 1 {
				//一个一个的获取解析
				file := lastfile{}
				file.FileName = v.Path + "/" + v.Name
				file.Name, err = utility.StrTo(strings.Replace(v.Name, ".xml", "", -1)).Int64()
				if err == nil {
					if lastlog <= file.Name {
						allfile = append(allfile, file)
					}
				}
			}
		}
		common.Log.Err("AG ftp GetFilelist:", allfile)
		getListxmlFile(allfile, 2, false)
	}
	hunterlostAndfound := "/HUNTER/lostAndfound/" + nowdate
	hunterlostAndfoundfiles, err := f.FtpWalkDir(hunterhost, username, password, hunterlostAndfound)
	if err != nil {
		common.Log.Err("AG ftp :", err)
		return
	} else {
		allfile := initlastfiles()
		lastlog, err := mag.GetLastLostFile(nowdate)
		if err != nil {
			common.Log.Err("AG ftp GetLastLostFile:", err)
		}
		for _, v := range hunterlostAndfoundfiles {
			//找出12个
			if v.Filetype == 1 {
				//一个一个的获取解析
				file := lastfile{}
				file.FileName = v.Path + "/" + v.Name
				file.Name, err = utility.StrTo(strings.Replace(v.Name, ".xml", "", -1)).Int64()
				if err == nil {
					if lastlog <= file.Name {
						allfile = append(allfile, file)
					}
				}
			}
		}
		common.Log.Err("AG ftp GetFilelist:", allfile)
		getListxmlFile(allfile, 3, false)
	}
}

//获取一批XML文件
//filetype, 0 默认文件 1 捕鱼文件 2 丢失 3 捕鱼丢失文件
func getListxmlFile(allfile []lastfile, filetype int, isverify bool) error {
	ag_conf_init()
	str_host := ""
	if filetype == 1 || filetype == 3 {
		str_host = hunterhost
	} else {
		str_host = host
	}
	//下载所有文件
	f1 := goftp.InitFtpBase(str_host, username, password, "/", nil)
	err := f1.Conn()
	if err != nil {
		common.Log.Err("AG ftp :", err)
		return err
	}
	defer f1.Close()
	for _, v := range allfile {
		if v.Name > 0 {
			//err = getOnexmlFile(v.FileName, temppath+v.FileName)
			if isverify {
				err = f1.GetFile(v.FileName, temppath + "/verify" + v.FileName)
				if err != nil {
					common.Log.Err("AG ftp :", err)
					return err
				}
			} else {
				err = f1.GetFile(v.FileName, temppath + v.FileName)
				if err != nil {
					common.Log.Err("AG ftp :", err)
					return err
				}
			}

			if isverify {
				err = PaseOnexmlFile(temppath + "/verify" + v.FileName)
			} else {
				err = PaseOnexmlFile(temppath + v.FileName)
			}
			if err != nil {
				common.Log.Err("AG PaseOnexmlFile ftp :", err)
			}
			if err == nil {
				//没有错误保存到数据库
				if filetype == 0 {
					mag.CreateLog(v.Name, 0, 0)
				} else if filetype == 1 {
					mag.CreateLog(0, 0, v.Name)
				} else if filetype == 2 {
					mag.CreateLog(0, v.Name, 0)
				}
			}
		}
	}

	return nil
}

//获取指定文件,并保存到数据库
func PaseOnexmlFile(dst_path string) error {
	//解析xml
	//ioutil.ReadAll
	inputFile, inputError := os.Open(dst_path) //变量指向os.Open打开的文件时生成的文件句柄
	if inputError != nil {
		common.Log.Err("An error occurred on opening the inputfile", dst_path)
		return inputError
	}
	defer inputFile.Close()

	inputReader := bufio.NewReader(inputFile)
	BetRecords := make([]mag.BetRecord, 0)
	gusernames := []string{}
	gusernames_map := make(map[string]string)
	for {
		inputString, readerError := inputReader.ReadString('\n')
		if readerError == io.EOF {
			break
		}
		//解析这行的数据HSR
		if strings.Index(inputString, `dataType="BR"`) > 0 {
			//下注记录
			jsonMap := mag.BetRecord{}
			err := xml.Unmarshal([]byte(inputString), &jsonMap)
			if err != nil {
				common.Log.Err("ag xml line error :", err, inputString)
			}
			BetRecords = append(BetRecords, jsonMap)
			gusernames_map[jsonMap.PlayerName] = jsonMap.PlayerName
			//err = mag.CreateBetRecord(&jsonMap)
			//if err != nil {
			//	common.Log.Err("ag sql error :", err, inputString)
			//}
		} else if strings.Index(inputString, `dataType="EBR"`) > 0 {
			//电子记录
			jsonMap := mag.BetRecord{}
			err := xml.Unmarshal([]byte(inputString), &jsonMap)
			if err != nil {
				common.Log.Err("ag xml line error :", err, inputString)
			}
			BetRecords = append(BetRecords, jsonMap)
			gusernames_map[jsonMap.PlayerName] = jsonMap.PlayerName
			//err = mag.CreateBetRecord(&jsonMap)
			//if err != nil {
			//	common.Log.Err("ag sql error :", err, inputString)
			//}//
		} else if strings.Index(inputString, `dataType="HBR"`) > 0 {
			//捕鱼记录
			jsonMap := mag.BetRecord{}
			err := xml.Unmarshal([]byte(inputString), &jsonMap)
			if err != nil {
				common.Log.Err("ag xml line error :", err, inputString)
			}
			BetRecords = append(BetRecords, jsonMap)
			gusernames_map[jsonMap.PlayerName] = jsonMap.PlayerName
			//err = mag.CreateBetRecord(&jsonMap)
			//if err != nil {
			//	common.Log.Err("ag sql error :", err, inputString)
			//}//HBR
		} else if strings.Index(inputString, `dataType="TR"`) > 0 {
			//转账记录
			jsonMap := mag.CashRecord{}
			err := xml.Unmarshal([]byte(inputString), &jsonMap)
			if err != nil {
				common.Log.Err("ag xml line error :", err, inputString)
			}
			err = mag.CreateCashRecord(&jsonMap)
			if err != nil {
				common.Log.Err("ag sql error :", err, inputString)
			}
			//dataType="HTR"
		} else if strings.Index(inputString, `dataType="HTR"`) > 0 {
			//转账记录
			jsonMap := mag.CashRecord{}
			err := xml.Unmarshal([]byte(inputString), &jsonMap)
			if err != nil {
				common.Log.Err("ag xml line error :", err, inputString)
			}
			err = mag.CreateCashRecord(&jsonMap)
			if err != nil {
				common.Log.Err("ag sql error :", err, inputString)
			}
		}else if strings.Index(inputString, `dataType="HSR"`) > 0 {
			//转账记录
			jsonMap := mag.CashRecordH{}
			err := xml.Unmarshal([]byte(inputString), &jsonMap)
			if err != nil {
				common.Log.Err("ag xml line error :", err, inputString)
			}
			err = mag.CreateCashRecordH(&jsonMap)
			if err != nil {
				common.Log.Err("ag sql error :", err, inputString)
			}
		} else if strings.Index(inputString, `dataType="GR"`) > 0 {
			//游戏结果
			jsonMap := mag.GameRecord{}
			err := xml.Unmarshal([]byte(inputString), &jsonMap)
			if err != nil {
				common.Log.Err("ag xml line error :", err, inputString)
			}
			err = mag.CreateGameRecord(&jsonMap)
			if err != nil {
				common.Log.Err("ag sql error :", err, inputString)
			}
		}  else {
			common.Log.Err("ag unknown dataType:", inputString)
		}
	}
	for _, k := range gusernames_map {
		gusernames = append(gusernames, k)
	}
	//过滤不存在的用户
	users, err := mag.GetUserByGname(gusernames)
	date_map := make(map[string][]mag.BetRecord)
	if err != nil || len(users) == 0 {
		//common.Log.Err("ag GetUserByGname:", gusernames, err)
	} else {
		//添加username和agentid
		//BetRecords_1 := make([]mag.BetRecord, 0)
		for _, v := range BetRecords {
			user, ok := users[v.PlayerName]
			if ok {
				v.Site_id = user.SiteId
				v.Agent_id = user.AgentId
				v.PkUsername = user.UserName
				//BetRecords_1 = append(BetRecords_1, v)
				//时间
				bettime, _ := time.Parse("2006-01-02 15:04:05", v.BetTime)
				date_map[bettime.Format("200601")] = append(date_map[bettime.Format("200601")], v)
			} else {
				//common.Log.Err("ag GetUserByGname not exist user name:", v.PlayerName)
			}
		}
		//BetRecords = BetRecords_1
	}
	//分时间插入
	for k, v := range date_map {
		err := ag_insertBatchBetRecord(v, k)
		if err != nil {
			return err
		}
	}
	return nil
}

func ag_insertBatchBetRecord(betRecords []mag.BetRecord, datestr string) error {
	//分批插入
	if len(betRecords) > 20 {
		count := len(betRecords)
		maxtimes := math.Ceil(float64(count) / float64(20))
		for i := 0; i < int(maxtimes); i++ {
			if i == int(maxtimes) - 1 {
				//fmt.Println(arr[i*20 : count])
				err := mag.InsertBatchBetRecord(betRecords[i * 20:count], datestr)
				if err != nil {
					common.Log.Err("ag InsertBatchBetRecord sql error :", err, betRecords)
				}
			} else {
				err := mag.InsertBatchBetRecord(betRecords[i * 20:(i + 1) * 20], datestr)
				if err != nil {
					common.Log.Err("ag InsertBatchBetRecord sql error :", err, betRecords)
				}
			}
		}
	} else {
		err := mag.InsertBatchBetRecord(betRecords, datestr)
		if err != nil {
			common.Log.Err("ag InsertBatchBetRecord sql error :", err, betRecords)
			return err
		}

	}
	return nil
}
