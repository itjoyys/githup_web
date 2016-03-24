package api

import (
	"net/url"
	"strings"

	"app/models"
	mag "app/models/ag"
	mbbin "app/models/bbin"
	mct "app/models/ct"
	meg "app/models/eg"
	mlebo "app/models/lebo"
	mmg "app/models/mg"
	mog "app/models/og"
	mpt "app/models/pt"
	"common"
	"middleware"
	"time"
	"utility"
)

type JsonResult struct {
	Result bool        `json:"result"`
	Data   interface{} `json:"data"`
}

type JsonData struct {
	Code int `json:"Code"`
}

type BalanceData struct {
	Code    int     `json:"Code"`
	Balance float64 `json:"balance"`
}

type AvailableBetData struct {
	Code     int     `json:"Code"`
	BetAll   float64 `json:"BetAll"`
	BetTimes int     `json:"BetBS"`
	BetYC    float64 `json:"BetYC"`
	BetPC    float64 `json:"BetPC"`
}

type AllBetData struct {
	Code        int     `json:"Code"`
	BetAll      float64 `json:"BetAll"`      //总投注
	BetAllCount int     `json:"BetAllCount"` //总投注次数
	BetVTimes   int     `json:"BetBS"`       //有效投注次数
	BetYC       float64 `json:"BetYC"`       //总有效投注
	BetPC       float64 `json:"BetPC"`       //总盈利
}

type UserAvailableBetDatas struct {
	Code                 int                    `json:"Code"`
	UserAvailableBetData []UserAvailableBetData `json:"data"`
}

type UserAllAvailableBetDatas struct {
	Code                 int                               `json:"Code"`
	UserAvailableBetData map[string][]UserAvailableBetData `json:"data"`
}

type UserAvailableBetData struct {
	UserName  string  `json:"username"`
	BetTimes  int64   `json:"BetBS"`  //有效投注次数
	BetWTimes int64   `json:"BetWBS"` //赢的投注次数
	BetAll    float64 `json:"BetAll"` //总投注
	BetYC     float64 `json:"BetYC"`  //总有效投注
	BetPC     float64 `json:"BetPC"`  //总盈利
}

type AgentAvailableBetDatas struct {
	Code                  int                     `json:"Code"`
	AgentAvailableBetData []AgentAvailableBetData `json:"data"`
}

type AgentAvailableBetData struct {
	AgentId   string  `json:"agentid"`
	BetTimes  int64   `json:"BetBS"`  //有效投注次数
	BetWTimes int64   `json:"BetWBS"` //赢的投注次数
	BetAll    float64 `json:"BetAll"` //总投注
	BetYC     float64 `json:"BetYC"`  //总有效投注
	BetPC     float64 `json:"BetPC"`  //总盈利
}

type SiteAvailableBetDatas struct {
	Code                 int                               `json:"Code"`
	SiteAvailableBetData map[string][]SiteAvailableBetData `json:"data"`
}

//站点的
type SiteAvailableBetData struct {
	Siteid    string  `json:"siteid"`
	BetTimes  int64   `json:"BetBS"`  //有效投注次数
	BetWTimes int64   `json:"BetWBS"` //赢的投注次数
	BetAll    float64 `json:"BetAll"` //总投注
	BetYC     float64 `json:"BetYC"`  //总有效投注
	BetPC     float64 `json:"BetPC"`  //总盈利
}

/*
for($i=0;$i<20;$i++){

}

//<!--数据输出-->
$data['data']=$d;
$data['BetMoneyAll']='100.00';//当页统计
$data['BackMoneyAll']='200.00';
$data['ResultMoneyAll']='300.00';
$data['BetMoneyAll_']='1100.00';//总计
$data['BackMoneyAll_']='1200.00';
$data['ResultMoneyAll_']='1300.00';
$data['Page']=10;//总页数
$data['ThisPage']=2;//当前页
$data['Nums']=230;//总数据条数
$data['Error']='';//异常提示
$data=json_encode($data);
*/

type BetRecordsData struct {
	Code              int             `json:"Code"`
	BetRecordsRows    []BetRecordsRow `json:"data"`
	BetMoneyAll       float64         `json:"BetMoneyAll"`
	ValidBetMoneyAll  float64         `json:"ValidBetMoneyAll"`
	BackMoneyAll      float64         `json:"BackMoneyAll"`
	ResultMoneyAll    float64         `json:"ResultMoneyAll"`
	BetMoneyAll_      float64         `json:"BetMoneyAll_"`
	ValidBetMoneyAll_ float64         `json:"ValidBetMoneyAll_"`
	BackMoneyAll_     float64         `json:"BackMoneyAll_"`
	ResultMoneyAll_   float64         `json:"ResultMoneyAll_"`
	Page              int64           `json:"Page"`
	ThisPage          int64           `json:"ThisPage"`
	Nums              int64           `json:"Nums"`
}

/*
 $d[$i]['OrderTime']=date('Y-m-d H:i:s',(time())-$i*5);//時間
    $d[$i]['OrderNum']=rand(100000000,999999999);//局號
    $d[$i]['OrderId']=rand(1000,8000);//PK注单号
    $d[$i]['DeskNum']=rand(1000,8000);//桌號
    $d[$i]['GameId']='GameId';//遊戲ID
    $d[$i]['Account']='Account';//下注帳號
    $d[$i]['Number']='Number';//場次
    $d[$i]['GameResult']='GameResult';//場次
    $d[$i]['BetType']='BetType';//視訊類別
    $d[$i]['BetMoney']='100.00';//有效投注
    $d[$i]['BackMoney']='0.00';//退水
    $d[$i]['ResultMoney']='150.00';//結果
*/
type BetRecordsRow struct {
	OrderTime     string  `json:"OrderTime"`
	OrderNum      string  `json:"OrderNum"`
	OrderId       string  `json:"OrderId"`
	DeskNum       string  `json:"DeskNum"`
	GameId        string  `json:"GameId"`
	Account       string  `json:"Account"`
	GAccount      string  `json:"GAccount"`
	Number        string  `json:"Number"`
	GameResult    string  `json:"GameResult"`
	BetType       string  `json:"BetType"`
	BetMoney      float64 `json:"BetMoney"`      //投注额
	ValidBetMoney float64 `json:"ValidBetMoney"` //有效投注额
	BackMoney     float64 `json:"BackMoney"`
	ResultMoney   float64 `json:"ResultMoney"`
	Siteid        string  `json:"Siteid"`
}

type AllBalanceData struct {
	Code         int     `json:"Code"`
	OG_Balance   float64 `json:"ogbalance"`
	OG_Status    bool    `json:"ogstatus"`
	AG_Balance   float64 `json:"agbalance"`
	AG_Status    bool    `json:"agstatus"`
	MG_Balance   float64 `json:"mgbalance"`
	MG_Status    bool    `json:"mgstatus"`
	CT_Balance   float64 `json:"ctbalance"`
	CT_Status    bool    `json:"ctstatus"`
	Bbin_Balance float64 `json:"bbinbalance"`
	Bbin_Status  bool    `json:"bbinstatus"`
	Lebo_Balance float64 `json:"lebobalance"`
	Lebo_Status  bool    `json:"lebostatus"`
	Pt_Balance   float64 `json:"ptbalance"`
	Pt_Status    bool    `json:"ptstatus"`
}

func DecParams(siteid, params string) (url.Values, error) {
	if len(params) == 0 {
		return nil, nil
	}
	allsiteid := common.Cfg.Section("app").Key("MANAGE_SITEID").MustString("manage")
	desKey := common.Cfg.Section("app").Key("MANAGE_DESKEY").MustString("12345678")
	if siteid != allsiteid {
		siteconfig, err := models.GetSiteById(siteid)
		if err != nil {
			return nil, err
		}
		desKey = siteconfig.SiteDesKey
	}
	param, err := utility.DesDecrypt([]byte(params), []byte(desKey))
	if err != nil {
		return nil, err
	}
	//common.Log.Info("DecParams", string(param))
	param_arr := strings.Replace(string(param), "/\\\\/", "&", -1)

	return url.ParseQuery(param_arr)
}

//生成一个用户名
func CreateUserName(username string) string {
	username = strings.ToLower(username)
	billno := time.Now().Format("0405.000")
	billno = strings.Replace(billno, ".", "", -1)
	if len(username) >= 7 {
		username = common.SubstrByByte(username, 7)
	}
	return username + billno
}

//获取手动采集日志
func GetCollectionStatus(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("OG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	type_sx := mv.Get("type")
	fromtime := mv.Get("s_time") //采集的启始时间

	//og 根据时间查询最后一个id
	//ag  设置要采集的时间
	//bbin 设置要采集的时间
	//mg 设置要采集的时间
	stime, err := time.Parse("2006-01-02 15:04:05", fromtime)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10004}})
	}
	if type_sx == "og" {
		//TODO 刷新数据
	} else if type_sx == "ag" {
		mag.UpdateTimeline(stime)
	} else if type_sx == "mg" {
		mmg.UpdateTimeline(stime)
	} else if type_sx == "bbin" {
		mbbin.UpdateTimeline(stime)
	}

	ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 20002}})
}

//获取用户所有的注单统计 只支持单用户
func GetUserAllAvailableAmountByUser(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("AG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	username := mv.Get("username")
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")
	types := mv.Get("types")
	notbrokerage := (mv.Get("notbk") == "1")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	//并行查询
	//ag binn ct lebo mg og mgdz
	//"og","ag","mg","ct","bbin","lebo",'mgdz','bbdz','eg','pt'
	types_arr := strings.Split(types, "|")
	//isdz := (mv.Get("dz") == "1")
	mapuser := make(map[string][]UserAvailableBetData)
	for _, v := range types_arr {
		if v == "og" {
			wb_og, err := mog.GetUserAvailableAmountByUser(username, siteid2, fromtime, totime, false, notbrokerage)
			if err != nil {
				common.Log.Err("OG GetUserAvailableAmountByUser sql error:", err)
			}
			if len(wb_og) > 0 {
				mapuser["og"] = make([]UserAvailableBetData, 0)
				for _, v := range wb_og {
					userd := UserAvailableBetData{}
					userd.UserName = string(v["account_number"])
					userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["og"] = append(mapuser["og"], userd)
				}
			}
		} else if v == "ag" {
			wb_ag, err := mag.GetUserAvailableAmountByUser(username, siteid2, false, fromtime, totime, notbrokerage)
			if err != nil {
				common.Log.Err("AG GetUserAvailableAmountByUser sql error:", err)
			}
			if len(wb_ag) > 0 {
				mapuser["ag"] = make([]UserAvailableBetData, 0)
				for _, v := range wb_ag {
					userd := UserAvailableBetData{}
					userd.UserName = string(v["account_number"])
					userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["ag"] = append(mapuser["ag"], userd)
				}
			}
		} else if v == "mg" {
			wb_mg, err := mmg.GetUserAvailableAmountByUser(username, siteid2, fromtime, totime, false)
			if err != nil {
				common.Log.Err("MG GetUserAvailableAmountByUser sql error:", err)
			}
			if len(wb_mg) > 0 {
				mapuser["mg"] = make([]UserAvailableBetData, 0)
				for _, v := range wb_mg {
					userd := UserAvailableBetData{}
					userd.UserName = string(v["pkusername"])
					userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetPC = userd.BetPC - userd.BetYC
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["mg"] = append(mapuser["mg"], userd)
				}
			}
		} else if v == "mgdz" {
			wb_mg, err := mmg.GetUserAvailableAmountByUser(username, siteid2, fromtime, totime, true)
			if err != nil {
				common.Log.Err("MG GetUserAvailableAmountByUser sql error:", err)
			}
			if len(wb_mg) > 0 {
				mapuser["mgdz"] = make([]UserAvailableBetData, 0)
				for _, v := range wb_mg {
					userd := UserAvailableBetData{}
					userd.UserName = string(v["pkusername"])
					userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetPC = userd.BetPC - userd.BetYC
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					//mapuser["mgdz"] = userd
					mapuser["mgdz"] = append(mapuser["mgdz"], userd)
				}
			}
		} else if v == "ct" {
			wb_ct, err := mct.GetUserAvailableAmountByUser(username, siteid2, fromtime, totime, false)
			if err != nil {
				common.Log.Err("CT GetUserAvailableAmountByUser sql error:", err)
			}
			if len(wb_ct) > 0 {
				mapuser["ct"] = make([]UserAvailableBetData, 0)
				for _, v := range wb_ct {
					userd := UserAvailableBetData{}
					userd.UserName = string(v["account_number"])
					userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetPC = userd.BetPC - userd.BetYC
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["ct"] = append(mapuser["ct"], userd)
				}
			}
		} else if v == "lebo" {
			wb_lebo, err := mlebo.GetUserAvailableAmountByUser(username, siteid2, fromtime, totime, false, notbrokerage)
			if err != nil {
				common.Log.Err("LEBO GetUserAvailableAmountByUser sql error:", err)
			}
			if len(wb_lebo) > 0 {
				mapuser["lebo"] = make([]UserAvailableBetData, 0)
				for _, v := range wb_lebo {
					userd := UserAvailableBetData{}
					userd.UserName = string(v["account_number"])
					userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					//mapuser["lebo"] = userd
					mapuser["lebo"] = append(mapuser["lebo"], userd)
				}
			}
		} else if v == "eg" {
			wb_lebo, err := meg.GetUserAvailableAmountByUser(username, siteid2, fromtime, totime, false, notbrokerage)
			if err != nil {
				common.Log.Err("eg GetUserAvailableAmountByUser sql error:", err)
			}
			if len(wb_lebo) > 0 {
				mapuser["eg"] = make([]UserAvailableBetData, 0)
				for _, v := range wb_lebo {
					userd := UserAvailableBetData{}
					userd.UserName = string(v["account_number"])
					userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					//mapuser["lebo"] = userd
					mapuser["eg"] = append(mapuser["eg"], userd)
				}
			}
		} else if v == "bbin" {
			wb_bbin, err := mbbin.GetUserAvailableAmountByUser(username, siteid2, fromtime, totime, "", notbrokerage)
			if err != nil {
				common.Log.Err("BBIN GetUserAvailableAmountByUser sql error:", err)
			}
			if len(wb_bbin) > 0 {
				mapuser["bbin"] = make([]UserAvailableBetData, 0)
				for _, v := range wb_bbin {
					userd := UserAvailableBetData{}
					userd.UserName = string(v["account_number"])
					userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["bbin"] = append(mapuser["bbin"], userd)
				}
			}
		} else if v == "pt" {
			wb_pt, err := mpt.GetUserAvailableAmountByUser(username, siteid2, fromtime, totime)
			if err != nil {
				common.Log.Err("PT GetUserAvailableAmountByUser sql error:", siteid2, err)
			}
			if len(wb_pt) > 0 {
				mapuser["pt"] = make([]UserAvailableBetData, 0)
				for _, v := range wb_pt {
					userd := UserAvailableBetData{}
					userd.UserName = string(v["pkusername"])
					userd.BetYC, _ = utility.StrTo(v["vb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetAll, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["pt"] = append(mapuser["pt"], userd)
				}
			}
		}
	}

	abd := UserAllAvailableBetDatas{}
	abd.Code = 10023
	abd.UserAvailableBetData = mapuser
	ctx.JSON(200, JsonResult{Result: true, Data: abd})

}

//获取用户所有的注单统计 只支持单用户
func GetUserAllAvailableAmountByAgentid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("OG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteid2 := mv.Get("siteid")
	agentid := mv.Get("agentid")
	ids := strings.Split(agentid, "|")
	if len(ids) == 0 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")
	if siteid != siteid2 {
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	types := mv.Get("types")
	//ag binn ct lebo mg og mgdz
	//"og","ag","mg","ct","bbin","lebo",'mgdz','bbdz'
	types_arr := strings.Split(types, "|")
	mapuser := make(map[string][]UserAvailableBetData)
	for _, v := range types_arr {
		if v == "og" {
			wb_og, err := mog.GetUserAvailableAmountByAgentid(agentid, false, siteid2, fromtime, totime)
			if err != nil {
				common.Log.Err("OG GetUserAvailableAmountByAgentid sql error:", agentid, err)
			}
			if len(wb_og) > 0 {
				mapuser["og"] = make([]UserAvailableBetData, 0)
				for _, v := range wb_og {
					userd := UserAvailableBetData{}
					userd.UserName = string(v["account_number"])
					userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["og"] = append(mapuser["og"], userd)
				}
			}
		} else if v == "lebo" {
			wb_lebo, err := mlebo.GetUserAvailableAmountByAgentid(agentid, false, siteid2, fromtime, totime)
			if err != nil {
				common.Log.Err("LEBO GetUserAvailableAmountByAgentid sql error:", agentid, err)
			}
			if len(wb_lebo) > 0 {
				mapuser["lebo"] = make([]UserAvailableBetData, 0)
				for _, v := range wb_lebo {
					userd := UserAvailableBetData{}
					userd.UserName = string(v["account_number"])
					userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["lebo"] = append(mapuser["lebo"], userd)
				}
			}
		} else if v == "eg" {
			wb_lebo, err := meg.GetUserAvailableAmountByAgentid(agentid, false, siteid2, fromtime, totime)
			if err != nil {
				common.Log.Err("LEBO GetUserAvailableAmountByAgentid sql error:", agentid, err)
			}
			if len(wb_lebo) > 0 {
				mapuser["eg"] = make([]UserAvailableBetData, 0)
				for _, v := range wb_lebo {
					userd := UserAvailableBetData{}
					userd.UserName = string(v["account_number"])
					userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["eg"] = append(mapuser["eg"], userd)
				}
			}
		} else if v == "bbin" {
			//bbin的电子不用分开
			wb_bbin, err := mbbin.GetUserAvailableAmountByAgentid(agentid, "", siteid2, fromtime, totime)
			if err != nil {
				common.Log.Err("BBIN GetUserAvailableAmountByAgentid sql error:", agentid, err)
			}
			if len(wb_bbin) > 0 {
				mapuser["bbin"] = make([]UserAvailableBetData, 0)
				for _, v := range wb_bbin {
					userd := UserAvailableBetData{}
					userd.UserName = string(v["account_number"])
					userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["bbin"] = append(mapuser["bbin"], userd)
				}
			}
		} else if v == "ct" {
			wb_ct, err := mct.GetUserAvailableAmountByAgentid(agentid, false, siteid2, fromtime, totime)
			if err != nil {
				common.Log.Err("CT GetUserAvailableAmountByAgentid sql error:", agentid, err)
			}
			if len(wb_ct) > 0 {
				mapuser["ct"] = make([]UserAvailableBetData, 0)
				for _, v := range wb_ct {
					userd := UserAvailableBetData{}
					userd.UserName = string(v["account_number"])
					userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetPC = userd.BetPC - userd.BetYC
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["ct"] = append(mapuser["ct"], userd)
				}
			}
		} else if v == "ag" {
			wb_ag, err := mag.GetUserAvailableAmountByAgentid(agentid, false, siteid2, fromtime, totime)
			if err != nil {
				common.Log.Err("AG GetUserAvailableAmountByAgentid sql error:", agentid, err)
			}
			if len(wb_ag) > 0 {
				mapuser["ag"] = make([]UserAvailableBetData, 0)
				for _, v := range wb_ag {
					userd := UserAvailableBetData{}
					userd.UserName = string(v["account_number"])
					userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["ag"] = append(mapuser["ag"], userd)
				}
			}
		} else if v == "mg" {
			wb_mg, err := mmg.GetUserAvailableAmountByAgentid(agentid, false, siteid2, fromtime, totime)
			if err != nil {
				common.Log.Err("MG GetUserAvailableAmountByAgentid sql error:", agentid, err)
			}
			if len(wb_mg) > 0 {
				mapuser["mg"] = make([]UserAvailableBetData, 0)
				for _, v := range wb_mg {
					userd := UserAvailableBetData{}
					userd.UserName = string(v["pkusername"])
					userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetPC = userd.BetPC - userd.BetYC
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["mg"] = append(mapuser["mg"], userd)
				}
			}
		} else if v == "mgdz" {
			wb_mg, err := mmg.GetUserAvailableAmountByAgentid(agentid, true, siteid2, fromtime, totime)
			if err != nil {
				common.Log.Err("MG GetUserAvailableAmountByAgentid sql error:", agentid, err)
			}
			if len(wb_mg) > 0 {
				mapuser["mgdz"] = make([]UserAvailableBetData, 0)
				for _, v := range wb_mg {
					userd := UserAvailableBetData{}
					userd.UserName = string(v["pkusername"])
					userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetPC = userd.BetPC - userd.BetYC
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["mgdz"] = append(mapuser["mgdz"], userd)
				}
			}
		}
	}

	abd := UserAllAvailableBetDatas{}
	abd.Code = 10023
	abd.UserAvailableBetData = mapuser
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

//获取站点的报表
func GetAllAvailableAmountBySiteid(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)

	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("OG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	siteids := mv.Get("siteid")
	ids := strings.Split(siteids, "|")
	if len(ids) == 0 {
		common.Log.Err("OG GetUserInfo xml error:", "123")
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	fromtime := mv.Get("s_time")
	totime := mv.Get("e_time")

	types := mv.Get("types")
	//ag binn ct lebo mg og mgdz
	//"og","ag","mg","ct","bbin","lebo",'mgdz','bbdz'
	types_arr := strings.Split(types, "|")

	mapuser := make(map[string][]SiteAvailableBetData)
	for _, v := range types_arr {
		if v == "og" {
			wb_og, err := mog.GetAvailableAmountBySiteids(siteids, fromtime, totime)
			if err != nil {
				common.Log.Err("OG GetAvailableAmountBySiteids sql error:", siteids, err)
			}
			if len(wb_og) > 0 {
				mapuser["og"] = make([]SiteAvailableBetData, 0)
				for _, v := range wb_og {
					userd := SiteAvailableBetData{}
					userd.Siteid = string(v["site_id"])
					userd.BetYC, _ = utility.StrTo(v["vb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetAll, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["og"] = append(mapuser["og"], userd)
				}
			}
		} else if v == "lebo" {
			wb_lebo, err := mlebo.GetAvailableAmountBySiteids(siteids, fromtime, totime)
			if err != nil {
				common.Log.Err("LEBO GetAvailableAmountBySiteids sql error:", siteids, err)
			}
			if len(wb_lebo) > 0 {
				mapuser["lebo"] = make([]SiteAvailableBetData, 0)
				for _, v := range wb_lebo {
					userd := SiteAvailableBetData{}
					userd.Siteid = string(v["site_id"])
					userd.BetYC, _ = utility.StrTo(v["vb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetAll, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["lebo"] = append(mapuser["lebo"], userd)
				}
			}
		} else if v == "eg" {
			wb_lebo, err := meg.GetAvailableAmountBySiteids(siteids, fromtime, totime)
			if err != nil {
				common.Log.Err("eg GetAvailableAmountBySiteids sql error:", siteids, err)
			}
			if len(wb_lebo) > 0 {
				mapuser["eg"] = make([]SiteAvailableBetData, 0)
				for _, v := range wb_lebo {
					userd := SiteAvailableBetData{}
					userd.Siteid = string(v["site_id"])
					userd.BetYC, _ = utility.StrTo(v["vb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetAll, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["eg"] = append(mapuser["eg"], userd)
				}
			}
		} else if v == "bbin" {
			//bbin的电子不用分开
			wb_bbin, err := mbbin.GetAvailableAmountBySiteids(siteids, fromtime, totime, false)
			if err != nil {
				common.Log.Err("BBIN GetAvailableAmountBySiteids sql error:", siteids, err)
			}
			if len(wb_bbin) > 0 {
				mapuser["bbin"] = make([]SiteAvailableBetData, 0)
				for _, v := range wb_bbin {
					userd := SiteAvailableBetData{}
					userd.Siteid = string(v["site_id"])
					userd.BetYC, _ = utility.StrTo(v["vb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetAll, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["bbin"] = append(mapuser["bbin"], userd)
				}
			}
		} else if v == "bbdz" {
			//bbin的电子不用分开
			wb_bbin, err := mbbin.GetAvailableAmountBySiteids(siteids, fromtime, totime, true)
			if err != nil {
				common.Log.Err("BBIN GetAvailableAmountBySiteids sql error:", siteids, err)
			}
			if len(wb_bbin) > 0 {
				mapuser["bbdz"] = make([]SiteAvailableBetData, 0)
				for _, v := range wb_bbin {
					userd := SiteAvailableBetData{}
					userd.Siteid = string(v["site_id"])
					userd.BetYC, _ = utility.StrTo(v["vb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetAll, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["bbdz"] = append(mapuser["bbdz"], userd)
				}
			}
		} else if v == "ct" {
			wb_ct, err := mct.GetAvailableAmountBySiteids(siteids, fromtime, totime)
			if err != nil {
				common.Log.Err("CT GetAvailableAmountBySiteids sql error:", siteids, err)
			}
			if len(wb_ct) > 0 {
				mapuser["ct"] = make([]SiteAvailableBetData, 0)
				for _, v := range wb_ct {
					userd := SiteAvailableBetData{}
					userd.Siteid = string(v["site_id"])
					userd.BetYC, _ = utility.StrTo(v["vb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetAll, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC = userd.BetPC - userd.BetAll
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["ct"] = append(mapuser["ct"], userd)
				}
			}
		} else if v == "ag" {
			wb_ag, err := mag.GetAvailableAmountBySiteids(siteids, fromtime, totime)
			if err != nil {
				common.Log.Err("AG GetAvailableAmountBySiteids sql error:", siteids, err)
			}
			if len(wb_ag) > 0 {
				mapuser["ag"] = make([]SiteAvailableBetData, 0)
				for _, v := range wb_ag {
					userd := SiteAvailableBetData{}
					userd.Siteid = string(v["site_id"])
					userd.BetYC, _ = utility.StrTo(v["vb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetAll, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["ag"] = append(mapuser["ag"], userd)
				}
			}
		} else if v == "mg" {
			wb_mg, err := mmg.GetAvailableAmountBySiteids(siteids, fromtime, totime, false)
			if err != nil {
				common.Log.Err("MG GetAvailableAmountBySiteids sql error:", siteids, err)
			}
			if len(wb_mg) > 0 {
				mapuser["mg"] = make([]SiteAvailableBetData, 0)
				for _, v := range wb_mg {
					userd := SiteAvailableBetData{}
					userd.Siteid = string(v["site_id"])
					userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetAll, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC = userd.BetPC - userd.BetYC
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["mg"] = append(mapuser["mg"], userd)
				}
			}
		} else if v == "mgdz" {
			wb_mg, err := mmg.GetAvailableAmountBySiteids(siteids, fromtime, totime, true)
			if err != nil {
				common.Log.Err("MG GetAvailableAmountBySiteids sql error:", siteids, err)
			}
			if len(wb_mg) > 0 {
				mapuser["mgdz"] = make([]SiteAvailableBetData, 0)
				for _, v := range wb_mg {
					userd := SiteAvailableBetData{}
					userd.Siteid = string(v["site_id"])
					userd.BetYC, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetAll, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetPC = userd.BetPC - userd.BetYC
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["mgdz"] = append(mapuser["mgdz"], userd)
				}
			}
		} else if v == "pt" {
			wb_pt, err := mpt.GetAvailableAmountBySiteids(siteids, fromtime, totime)
			if err != nil {
				common.Log.Err("PT GetAvailableAmountBySiteids sql error:", siteids, err)
			}
			if len(wb_pt) > 0 {
				mapuser["pt"] = make([]SiteAvailableBetData, 0)
				for _, v := range wb_pt {
					userd := SiteAvailableBetData{}
					userd.Siteid = string(v["site_id"])
					userd.BetYC, _ = utility.StrTo(v["vb"]).Float64()
					userd.BetPC, _ = utility.StrTo(v["wb"]).Float64()
					userd.BetAll, _ = utility.StrTo(v["bb"]).Float64()
					userd.BetTimes, _ = utility.StrTo(v["times"]).Int64()
					mapuser["pt"] = append(mapuser["pt"], userd)
				}
			}
		}
	}

	abd := SiteAvailableBetDatas{}
	abd.Code = 10023
	abd.SiteAvailableBetData = mapuser
	ctx.JSON(200, JsonResult{Result: true, Data: abd})
}

func ReCollection(ctx *middleware.Context) {
	params := ctx.Query("params")
	params = strings.Replace(params, " ", "+", -1) //base64bug
	//解码
	siteid := strings.Replace(ctx.Req.UserAgent(), "Game_Go_", "", -1)
	mv, err := DecParams(siteid, params)
	if err != nil {
		common.Log.Err("OG GetUserInfo xml error:", err)
		ctx.JSON(200, JsonResult{Result: false, Data: JsonData{Code: 10001}})
		return
	}
	type_sx := mv.Get("type")
	fromtime := mv.Get("s_time") //采集的启始时间
	//og 根据时间查询最后一个id
	//ag  设置要采集的时间
	//bbin 设置要采集的时间
	//mg 设置要采集的时间
	stime, err := time.Parse("2006-01-02 15:04:05", fromtime)
	if err != nil {
		ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 10004}})
	}
	if type_sx == "og" {
		//vid ,err := mog.GetLastBetRecorVid_bytime(stime)
	} else if type_sx == "ag" {
		mag.UpdateTimeline(stime)
	} else if type_sx == "mg" {
		mmg.UpdateTimeline(stime)
	} else if type_sx == "bbin" {
		mbbin.UpdateTimeline(stime)
	}

	ctx.JSON(200, JsonResult{Result: true, Data: JsonData{Code: 20001}})
}
