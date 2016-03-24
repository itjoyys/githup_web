<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lottery_info extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('lottery/lottery_model');
    }

    //投注限额
    function GetLimitByLotteryId(){

        //{LotteryId: 7}
        $postarr =$this->input->file_get();
        $lotteryId = $this->lottery_model->get_lottery_type($postarr['LotteryId']);
        //p($lotteryId);
        $bet_limit = array();
        //限红
        if(empty($bet_limit)){
            $bet_limit = $this->lottery_model->_get_ball_limit($lotteryId);
        }
        // p($bet_limit);exit;
        foreach ($bet_limit as $k => $v) {
            $LimitList[$k]['GroupName']=$v['name'];
            $LimitList[$k]['OneBetMinMoney']=$v['min'];
            $LimitList[$k]['OneBetLimitMoney']=$v['single_note_max'];
            $LimitList[$k]['ItemLimitMoney']=$v['single_field_max'];
            $LimitList[$k]['BackMoneyPercent']=0.00;
        }
        $json=array('ErrorCode'=>0,'Data'=>array('LimitList'=>$LimitList));
//         $json = <<<Eof

// {"ErrorCode":0,
// "Data":
// {"LimitList":[
// {"OneBetMinMoney":2.00,"OneBetLimitMoney":20000.00,"ItemLimitMoney":100000.00,"BackMoneyPercent":0.0000,"GroupName":"冠军"},
// {"OneBetMinMoney":2.00,"OneBetLimitMoney":20000.00,"ItemLimitMoney":100000.00,"BackMoneyPercent":0.0000,"GroupName":"亚军"},
// {"OneBetMinMoney":2.00,"OneBetLimitMoney":20000.00,"ItemLimitMoney":100000.00,"BackMoneyPercent":0.0000,"GroupName":"第三名"},
// {"OneBetMinMoney":2.00,"OneBetLimitMoney":20000.00,"ItemLimitMoney":100000.00,"BackMoneyPercent":0.0000,"GroupName":"第四名"},
// {"OneBetMinMoney":2.00,"OneBetLimitMoney":20000.00,"ItemLimitMoney":100000.00,"BackMoneyPercent":0.0000,"GroupName":"第五名"},
// {"OneBetMinMoney":2.00,"OneBetLimitMoney":20000.00,"ItemLimitMoney":100000.00,"BackMoneyPercent":0.0000,"GroupName":"第六名"},
// {"OneBetMinMoney":2.00,"OneBetLimitMoney":20000.00,"ItemLimitMoney":100000.00,"BackMoneyPercent":0.0000,"GroupName":"第七名"},
// {"OneBetMinMoney":2.00,"OneBetLimitMoney":20000.00,"ItemLimitMoney":100000.00,"BackMoneyPercent":0.0000,"GroupName":"第八名"},
// {"OneBetMinMoney":2.00,"OneBetLimitMoney":20000.00,"ItemLimitMoney":100000.00,"BackMoneyPercent":0.0000,"GroupName":"第九名"},
// {"OneBetMinMoney":2.00,"OneBetLimitMoney":20000.00,"ItemLimitMoney":100000.00,"BackMoneyPercent":0.0000,"GroupName":"第十名"},
// {"OneBetMinMoney":2.00,"OneBetLimitMoney":50000.00,"ItemLimitMoney":500000.00,"BackMoneyPercent":0.0000,"GroupName":"1-10大小"},
// {"OneBetMinMoney":2.00,"OneBetLimitMoney":50000.00,"ItemLimitMoney":500000.00,"BackMoneyPercent":0.0000,"GroupName":"1-10单双"},
// {"OneBetMinMoney":2.00,"OneBetLimitMoney":50000.00,"ItemLimitMoney":500000.00,"BackMoneyPercent":0.0000,"GroupName":"1-5龙虎"},
// {"OneBetMinMoney":2.00,"OneBetLimitMoney":50000.00,"ItemLimitMoney":500000.00,"BackMoneyPercent":0.0000,"GroupName":"冠亚军和大小"},
// {"OneBetMinMoney":2.00,"OneBetLimitMoney":50000.00,"ItemLimitMoney":500000.00,"BackMoneyPercent":0.0000,"GroupName":"冠亚军和单双"},
// {"OneBetMinMoney":2.00,"OneBetLimitMoney":10000.00,"ItemLimitMoney":50000.00,"BackMoneyPercent":0.0000,"GroupName":"冠亚军和"}]}}
// Eof;

            header('Content-Type: application/json;charset=utf-8');
            echo json_encode($json);
        }

    function GetHFLines()
    {
        // {LotteryId: 3}
        // {LotteryId: 4}
        // {LotteryId: 7}
        $postarr =$this->input->file_get();
        $list = $this->lottery_model->get_odds_one($postarr['LotteryId']);
        // p($list);
        $type = $this->lottery_model->get_lottery_type($postarr['LotteryId']);
        $qishu = $this->lottery_model->_dq_qishu($type);
        $fengpan_time = $this->lottery_model->_get_fengpan_time($type);
        $kaijiang = $this->lottery_model->_get_result($type, 1); // 最近开奖结果
        if ($kaijiang[0]) {
            $last_kaijiang = '';
            foreach ($kaijiang[0] as $k => $v) {
                $balls = explode('ball_', $k);
                if (! empty($balls[1])) {
                    $last_kaijiang .= ',' . $v;
                } else {
                    continue;
                }
            }
            $last_kaijiang = trim($last_kaijiang, ',');
        }

        // $SxResult = "2:蛇,42:鼠,9:虎,45:猪,36:虎,28:龙,16:马";
        $json = array(
            'ErrorCode' => 0,
            'Data' => array(
                'Lines' => $list,
                'CurrentPeriod' => $qishu['qishu'],
                "OpenCount" => $fengpan_time['k_t_stro'],
                "PrePeriodNumber" => $kaijiang[0]['qishu'],
                "CloseCount" => $fengpan_time['f_t_stro'],
                "PreResult" => $last_kaijiang
            )
            // 'SxResult' =>$SxResult

        );

        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($json);
    }

    function getHKCLines()
    {
        // {LotteryId: 1, Lotterypan: 4}
        // {LotteryId: 1, Lotterypan: 8}

        // Lotterypan投注类型
        /**
         * 1 ,2,3,4 特码
         */
        $postarr =$this->input->file_get();
        $list = $this->lottery_model->get_odds_one($postarr['LotteryId'],$postarr['Lotterypan']);
        // p($list);
        $type = $this->lottery_model->get_lottery_type($postarr['LotteryId']);
        $qishu = $this->lottery_model->_dq_qishu($type);
        $fengpan_time = $this->lottery_model->_get_fengpan_time($type);
        $kaijiang = $this->lottery_model->_get_result($type, 1); // 最近开奖结果
        if ($kaijiang[0]) {
            $last_kaijiang = '';
            $SxResult = '';
            foreach ($kaijiang[0] as $k => $v) {
                $balls = explode('ball_', $k);
                if (! empty($balls[1])) {
                    $last_kaijiang .= ',' . $v;
                    $SxResult .= ',' . $v . ':' . func_shenxiao($v);
                } else {
                    continue;
                }
            }
            $last_kaijiang = trim($last_kaijiang, ',');
            $SxResult = trim($SxResult, ',');
        }

        // $SxResult = "2:蛇,42:鼠,9:虎,45:猪,36:虎,28:龙,16:马";
        $json = array(
            'ErrorCode' => 0,
            'Data' => array(
                'Lines' => $list,
                'CurrentPeriod' => $qishu['qishu'],
                "OpenCount" => intval($fengpan_time['k_t_stro']),
                "PrePeriodNumber" => $kaijiang[0]['qishu'],
                "CloseCount" => $fengpan_time['f_t_stro'],
                "PreResult" => $last_kaijiang,
                'SxResult' => $SxResult
            )
        );
        // switch ($postarr['LotteryId']) {
        // case 7:
        // # 重庆时时彩
        // $json = <<<Eof
        // {"ErrorCode":0,"Data":{"Lines":{"986":9.850,"987":9.850,"988":9.850,"989":9.850,"990":9.850,"991":9.850,"992":9.850,"993":9.850,"994":9.850,"995":9.850,"996":9.850,"997":9.850,"998":9.850,"999":9.850,"1000":9.850,"1001":9.850,"1002":9.850,"1003":9.850,"1004":9.850,"1005":9.850,"1006":9.850,"1007":9.850,"1008":9.850,"1009":9.850,"1010":9.850,"1011":9.850,"1012":9.850,"1013":9.850,"1014":9.850,"1015":9.850,"1016":9.850,"1017":9.850,"1018":9.850,"1019":9.850,"1020":9.850,"1021":9.850,"1022":9.850,"1023":9.850,"1024":9.850,"1025":9.850,"1026":9.850,"1027":9.850,"1028":9.850,"1029":9.850,"1030":9.850,"1031":9.850,"1032":9.850,"1033":9.850,"1034":9.850,"1035":9.850,"1036":1.982,"1037":1.982,"1038":1.982,"1039":1.982,"1040":1.982,"1041":1.982,"1042":1.982,"1043":1.982,"1044":1.982,"1045":1.982,"1046":1.982,"1047":1.982,"1048":1.982,"1049":1.982,"1050":1.982,"1051":1.982,"1052":1.982,"1053":1.982,"1054":1.982,"1055":1.982,"1057":1.982,"1058":1.982,"1059":1.982,"1060":1.982,"1061":1.982,"1062":1.982,"1063":9.400,"1064":70.000,"1065":14.000,"1066":3.200,"1067":2.300,"1068":2.800,"1069":70.000,"1070":14.000,"1071":3.200,"1072":2.300,"1073":2.800,"1074":70.000,"1075":14.000,"1076":3.200,"1077":2.300,"1078":2.800},"CurrentPeriod":"20151227049","OpenCount":335,"PrePeriodNumber":"20151227048","CloseCount":275,"PreResult":"4,6,2,2,6"}}
        // Eof;
        // break;
        // case 2:
        // # 重庆时时彩
        // $json = <<<Eof
        // {"ErrorCode":0,"Data":{"Lines":{"986":9.850,"987":9.850,"988":9.850,"989":9.850,"990":9.850,"991":9.850,"992":9.850,"993":9.850,"994":9.850,"995":9.850,"996":9.850,"997":9.850,"998":9.850,"999":9.850,"1000":9.850,"1001":9.850,"1002":9.850,"1003":9.850,"1004":9.850,"1005":9.850,"1006":9.850,"1007":9.850,"1008":9.850,"1009":9.850,"1010":9.850,"1011":9.850,"1012":9.850,"1013":9.850,"1014":9.850,"1015":9.850,"1016":9.850,"1017":9.850,"1018":9.850,"1019":9.850,"1020":9.850,"1021":9.850,"1022":9.850,"1023":9.850,"1024":9.850,"1025":9.850,"1026":9.850,"1027":9.850,"1028":9.850,"1029":9.850,"1030":9.850,"1031":9.850,"1032":9.850,"1033":9.850,"1034":9.850,"1035":9.850,"1036":1.982,"1037":1.982,"1038":1.982,"1039":1.982,"1040":1.982,"1041":1.982,"1042":1.982,"1043":1.982,"1044":1.982,"1045":1.982,"1046":1.982,"1047":1.982,"1048":1.982,"1049":1.982,"1050":1.982,"1051":1.982,"1052":1.982,"1053":1.982,"1054":1.982,"1055":1.982,"1057":1.982,"1058":1.982,"1059":1.982,"1060":1.982,"1061":1.982,"1062":1.982,"1063":9.400,"1064":70.000,"1065":14.000,"1066":3.200,"1067":2.300,"1068":2.800,"1069":70.000,"1070":14.000,"1071":3.200,"1072":2.300,"1073":2.800,"1074":70.000,"1075":14.000,"1076":3.200,"1077":2.300,"1078":2.800},"CurrentPeriod":"20151227049","OpenCount":335,"PrePeriodNumber":"20151227048","CloseCount":275,"PreResult":"4,6,2,2,6"}}
        // Eof;
        // break;
        // case 6:
        // //北京pk10
        // $json = <<<Eof
        // {"ErrorCode":0,"Data":{"Lines":{"1079":9.850,"1080":9.850,"1081":9.850,"1082":9.850,"1083":9.850,"1084":9.850,"1085":9.850,"1086":9.850,"1087":9.850,"1088":9.850,"1089":9.850,"1090":9.850,"1091":9.850,"1092":9.850,"1093":9.850,"1094":9.850,"1095":9.850,"1096":9.850,"1097":9.850,"1098":9.850,"1099":9.850,"1100":9.850,"1101":9.850,"1102":9.850,"1103":9.850,"1104":9.850,"1105":9.850,"1106":9.850,"1107":9.850,"1108":9.850,"1109":9.850,"1110":9.850,"1111":9.850,"1112":9.850,"1113":9.850,"1114":9.850,"1115":9.850,"1116":9.850,"1117":9.850,"1118":9.850,"1119":9.850,"1120":9.850,"1121":9.850,"1122":9.850,"1123":9.850,"1124":9.850,"1125":9.850,"1126":9.850,"1127":9.850,"1128":9.850,"1129":9.850,"1130":9.850,"1131":9.850,"1132":9.850,"1133":9.850,"1134":9.850,"1135":9.850,"1136":9.850,"1137":9.850,"1138":9.850,"1139":9.850,"1140":9.850,"1141":9.850,"1142":9.850,"1143":9.850,"1144":9.850,"1145":9.850,"1146":9.850,"1147":9.850,"1148":9.850,"1149":9.850,"1150":9.850,"1151":9.850,"1152":9.850,"1153":9.850,"1154":9.850,"1155":9.850,"1156":9.850,"1157":9.850,"1158":9.850,"1159":9.850,"1160":9.850,"1161":9.850,"1162":9.850,"1163":9.850,"1164":9.850,"1165":9.850,"1166":9.850,"1167":9.850,"1168":9.850,"1169":9.850,"1170":9.850,"1171":9.850,"1172":9.850,"1173":9.850,"1174":9.850,"1175":9.850,"1176":9.850,"1177":9.850,"1178":9.850,"1179":42.300,"1180":42.300,"1181":21.300,"1182":21.300,"1183":14.300,"1184":14.300,"1185":10.300,"1186":10.300,"1187":8.300,"1188":10.300,"1189":10.300,"1190":14.300,"1191":14.300,"1192":21.300,"1193":21.300,"1194":42.300,"1195":42.300,"1254":2.200,"1255":1.770,"1256":1.770,"1257":2.200,"1316":1.982,"1317":1.982,"1318":1.982,"1319":1.982,"1320":1.982,"1321":1.982,"1322":1.982,"1323":1.982,"1324":1.982,"1325":1.982,"1326":1.982,"1327":1.982,"1328":1.982,"1329":1.982,"1330":1.982,"1331":1.982,"1332":1.982,"1333":1.982,"1334":1.982,"1335":1.982,"1336":1.982,"1337":1.982,"1338":1.982,"1339":1.982,"1340":1.982,"1341":1.982,"1342":1.982,"1343":1.982,"1344":1.982,"1345":1.982,"1346":1.982,"1347":1.982,"1348":1.982,"1349":1.982,"1350":1.982,"1351":1.982,"1352":1.982,"1353":1.982,"1354":1.982,"1355":1.982,"1356":1.982,"1357":1.982,"1358":1.982,"1359":1.982,"1360":1.982,"1361":1.982,"1362":1.982,"1363":1.982,"1364":1.982,"1365":1.982},"CurrentPeriod":"530065","OpenCount":110,"PrePeriodNumber":"530064","CloseCount":90,"PreResult":"7,5,2,9,8,3,1,6,10,4"}}
        // Eof;
        // break;
        // case 6:
        // # 重庆时时彩
        // $json = <<<Eof
        // {"ErrorCode":0,"Data":{"Lines":{"986":9.850,"987":9.850,"988":9.850,"989":9.850,"990":9.850,"991":9.850,"992":9.850,"993":9.850,"994":9.850,"995":9.850,"996":9.850,"997":9.850,"998":9.850,"999":9.850,"1000":9.850,"1001":9.850,"1002":9.850,"1003":9.850,"1004":9.850,"1005":9.850,"1006":9.850,"1007":9.850,"1008":9.850,"1009":9.850,"1010":9.850,"1011":9.850,"1012":9.850,"1013":9.850,"1014":9.850,"1015":9.850,"1016":9.850,"1017":9.850,"1018":9.850,"1019":9.850,"1020":9.850,"1021":9.850,"1022":9.850,"1023":9.850,"1024":9.850,"1025":9.850,"1026":9.850,"1027":9.850,"1028":9.850,"1029":9.850,"1030":9.850,"1031":9.850,"1032":9.850,"1033":9.850,"1034":9.850,"1035":9.850,"1036":1.982,"1037":1.982,"1038":1.982,"1039":1.982,"1040":1.982,"1041":1.982,"1042":1.982,"1043":1.982,"1044":1.982,"1045":1.982,"1046":1.982,"1047":1.982,"1048":1.982,"1049":1.982,"1050":1.982,"1051":1.982,"1052":1.982,"1053":1.982,"1054":1.982,"1055":1.982,"1057":1.982,"1058":1.982,"1059":1.982,"1060":1.982,"1061":1.982,"1062":1.982,"1063":9.400,"1064":70.000,"1065":14.000,"1066":3.200,"1067":2.300,"1068":2.800,"1069":70.000,"1070":14.000,"1071":3.200,"1072":2.300,"1073":2.800,"1074":70.000,"1075":14.000,"1076":3.200,"1077":2.300,"1078":2.800},"CurrentPeriod":"20151227049","OpenCount":335,"PrePeriodNumber":"20151227048","CloseCount":275,"PreResult":"4,6,2,2,6"}}
        // Eof;
        // break;
        // case 6:
        // //幸运飞艇
        // $json = <<<Eof
        // Eof;
        // break;
        // case 12:
        // //广东快乐十分
        // $json = <<<Eof
        // {"ErrorCode":0,"Data":{"Lines":{"2":19.800,"3":19.800,"4":19.800,"5":19.800,"6":19.800,"7":19.800,"8":19.800,"9":19.800,"10":19.800,"11":19.800,"12":19.800,"13":19.800,"14":19.800,"15":19.800,"16":19.800,"17":19.800,"18":19.800,"19":19.800,"20":19.800,"21":19.800,"22":19.800,"23":19.800,"24":19.800,"25":19.800,"26":19.800,"27":19.800,"28":19.800,"29":19.800,"30":19.800,"31":19.800,"32":19.800,"33":19.800,"34":19.800,"35":19.800,"36":19.800,"37":19.800,"38":19.800,"39":19.800,"40":19.800,"41":19.800,"42":19.800,"43":19.800,"44":19.800,"45":19.800,"46":19.800,"47":19.800,"48":19.800,"49":19.800,"50":19.800,"51":19.800,"52":19.800,"53":19.800,"54":19.800,"55":19.800,"56":19.800,"57":19.800,"58":19.800,"59":19.800,"60":19.800,"61":19.800,"62":19.800,"63":19.800,"64":19.800,"65":19.800,"66":19.800,"67":19.800,"68":19.800,"69":19.800,"70":19.800,"71":19.800,"72":19.800,"73":19.800,"74":19.800,"75":19.800,"76":19.800,"77":19.800,"78":19.800,"79":19.800,"80":19.800,"81":19.800,"82":1.982,"83":1.982,"84":1.982,"85":1.982,"86":1.982,"87":1.982,"88":1.982,"89":1.982,"90":3.900,"91":3.900,"92":3.900,"93":3.900,"94":2.800,"95":2.800,"96":3.000,"97":1.982,"98":1.982,"99":1.982,"100":1.982,"101":1.982,"102":1.982,"103":1.982,"104":1.982,"105":3.900,"106":3.900,"107":3.900,"108":3.900,"109":2.800,"110":2.800,"111":3.000,"112":1.982,"113":1.982,"114":1.982,"115":1.982,"116":1.982,"117":1.982,"118":1.982,"119":1.982,"120":3.900,"121":3.900,"122":3.900,"123":3.900,"124":2.800,"125":2.800,"126":3.000,"127":1.982,"128":1.982,"129":1.982,"130":1.982,"131":1.982,"132":1.982,"133":1.982,"134":1.982,"135":3.900,"136":3.900,"137":3.900,"138":3.900,"139":2.800,"140":2.800,"141":3.000,"142":1.982,"143":1.982,"144":1.982,"145":1.982,"146":1.982,"147":1.982,"148":1.982,"149":1.982,"150":3.900,"151":3.900,"152":3.900,"153":3.900,"154":2.800,"155":2.800,"156":3.000,"157":1.982,"158":1.982,"159":1.982,"160":1.982,"161":1.982,"162":1.982,"163":1.982,"164":1.982,"165":3.900,"166":3.900,"167":3.900,"168":3.900,"169":2.800,"170":2.800,"171":3.000,"172":1.982,"173":1.982,"174":1.982,"175":1.982,"176":1.982,"177":1.982,"178":1.982,"179":1.982,"180":3.900,"181":3.900,"182":3.900,"183":3.900,"184":2.800,"185":2.800,"186":3.000,"187":1.982,"188":1.982,"189":1.982,"190":1.982,"191":1.982,"192":1.982,"193":1.982,"194":1.982,"195":3.900,"196":3.900,"197":3.900,"198":3.900,"199":2.800,"200":2.800,"201":3.000,"204":1.982,"205":1.982,"206":1.982,"207":1.982,"208":1.982,"209":1.982,"212":6.000,"213":0.000,"214":20.000,"215":18.000,"216":0.000,"217":0.000,"218":60.000,"219":120.000,"897":1.982,"898":1.982,"899":1.982,"900":1.982,"901":1.982,"902":1.982,"903":1.982,"904":1.982,"905":19.800,"906":19.800,"907":19.800,"908":19.800,"909":19.800,"910":19.800,"911":19.800,"912":19.800,"913":19.800,"914":19.800,"915":19.800,"916":19.800,"917":19.800,"918":19.800,"919":19.800,"920":19.800,"921":19.800,"922":19.800,"923":19.800,"924":19.800,"925":19.800,"926":19.800,"927":19.800,"928":19.800,"929":19.800,"930":19.800,"931":19.800,"932":19.800,"933":19.800,"934":19.800,"935":19.800,"936":19.800,"937":19.800,"938":19.800,"939":19.800,"940":19.800,"941":19.800,"942":19.800,"943":19.800,"944":19.800,"945":19.800,"946":19.800,"947":19.800,"948":19.800,"949":19.800,"950":19.800,"951":19.800,"952":19.800,"953":19.800,"954":19.800,"955":19.800,"956":19.800,"957":19.800,"958":19.800,"959":19.800,"960":19.800,"961":19.800,"962":19.800,"963":19.800,"964":19.800,"965":19.800,"966":19.800,"967":19.800,"968":19.800,"969":19.800,"970":19.800,"971":19.800,"972":19.800,"973":19.800,"974":19.800,"975":19.800,"976":19.800,"977":19.800,"978":19.800,"979":19.800,"980":19.800,"981":19.800,"982":19.800,"983":19.800,"984":19.800},"CurrentPeriod":"2016010641","OpenCount":589,"PrePeriodNumber":"2016010639","CloseCount":539,"PreResult":"5,7,9,17,15,4,16,12"}}
        // Eof;
        // break;
        // default:
        // # code...
        // break;
        // }

        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($json);
    }

    function GetLotteryList()
    {
        // ID":"2","GameID":"101","PageStyle":""}
        // 获得每个彩种
        $map = array();
        $list = $this->lottery_model->get_lottery($map);
        $json = array(
            'ErrorCode' => 0,
            'Data' => array(
                'LotteryList' => $list
            )
        );
        // 生成游戏链接
        // $json = <<<Eof
        // {"ErrorCode":0,"Data":{
        // "LotteryList":[
        // {"LotteryID":2,"LotteryName":"福彩3D","OpenCount":"128"},
        // {"LotteryID":3,"LotteryName":"排列3","OpenCount":"248"},
        // {"LotteryID":4,"LotteryName":"北京快乐8","OpenCount":"8"},
        // {"LotteryID":5,"LotteryName":"北京赛车PK拾","OpenCount":"68"},
        // {"LotteryID":6,"LotteryName":"重庆时时彩","OpenCount":"8"},
        // {"LotteryID":7,"LotteryName":"天津时时彩","OpenCount":"188"},
        // {"LotteryID":8,"LotteryName":"新疆时时彩","OpenCount":"8"},
        // {"LotteryID":9,"LotteryName":"江西时时彩","OpenCount":"216"},
        // {"LotteryID":10,"LotteryName":"重庆快乐十分","OpenCount":"68"},
        // {"LotteryID":11,"LotteryName":"广东快乐十分","OpenCount":"68"},
        // {"LotteryID":12,"LotteryName":"江苏快3","OpenCount":"68"},
        // {"LotteryID":13,"LotteryName":"吉林快3","OpenCount":"68"},
        // {"LotteryID":1,"LotteryName":"六合彩","OpenCount":"202208"}
        // ]
        // }
        // }
        // Eof;
        /*
         * {"LotteryID":4,"LotteryName":"北京赛车pk10","OpenCount":"128"},
         * {"LotteryID":6,"LotteryName":"幸运飞艇","OpenCount":"248"},
         * {"LotteryID":3,"LotteryName":"重庆时时彩","OpenCount":"8"},
         * {"LotteryID":7,"LotteryName":"圣地彩","OpenCount":"68"},
         * {"LotteryID":2,"LotteryName":"广东快乐十分","OpenCount":"8"},
         * {"LotteryID":5,"LotteryName":"幸运农场","OpenCount":"188"},
         * {"LotteryID":9,"LotteryName":"广东11选5","OpenCount":"8"},
         * {"LotteryID":8,"LotteryName":"江西时时彩","OpenCount":"216"},
         * {"LotteryID":10,"LotteryName":"十分六合彩","OpenCount":"68"},
         * {"LotteryID":1,"LotteryName":"六合彩","OpenCount":"202208"}
         */

        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($json);
    }

    function GetHistory(){
        $postarr =$this->input->file_get();
        $lotteryId = $this->lottery_model->get_lottery_type($postarr['LotteryId']);
        if(!empty($postarr['Date'])){
        $postarr['Date']  = date('Y-m-d',strtotime($postarr['Date']));
        }
        $date = $postarr['Date']?$postarr['Date']:func_nowtime('Y-m-d');
        if(!empty($date)){
            $map['table'] = $lotteryId."_auto";
            if(!$lotteryId=='liuhecai'&&!$lotteryId=='fc_3d'&&!$lotteryId=='pl_3'){
            $map['where']= "date_format(datetime,'%Y-%m-%d') ='". $date."'";
            }else{
                $map['limit'] = 20;
            }
            $data = $this->lottery_model->get_auto($map);
            $data = func_get_arr_row_all($data,$lotteryId);
            $json = array('ErrorCode'=>0,'Data'=>array('HistoryList'=>$data));
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($json);
        }
    }
    //及时注单
    function NotCount()
    {
        $postarr =$this->input->file_get();
        $uid = $_SESSION['uid'];
        $lotteryId = $postarr['LotteryId'];
        if(empty($lotteryId)){
	        $map['table'] = 'fc_games';
	       	$map['select'] = "fc_games.id as LotteryId ,fc_games.`name` as LotteryName,COUNT(c_bet.did) as Count,sum(c_bet.money) as TotalBetMoney";
	        //$map['select'] = "fc_games.id as LotteryId ,fc_games.`name` as LotteryName,COUNT(c_bet.did) as Count,sum(c_bet.money) as TotalBetMoney";
	        $map['join']['table'] ='c_bet';
	        $map['join']['action'] ='fc_games.name = c_bet.type';
	        $map['where'] = "date_format(c_bet.addtime,'%Y-%m-%d') ='" . func_nowtime('Y-m-d','now') . "' and c_bet.uid=" . $uid;
	        $map['group'] = "c_bet.type";
	        $data = $this->lottery_model->get_table($map);
	        foreach ($data as $k=>$v){
	            $data[$k]['Count'] = (int)$v['Count'];
	            $data[$k]['TotalBetMoney'] = (int)$v['TotalBetMoney'];
	        }
	        $json = array(
	            'ErrorCode' => 0,
	            'Data' => array(
	                'ReportList' => $data
	            )
	        );
        }else{
            $lotteryId = $this->lottery_model->get_lottery_type($postarr['LotteryId'],'name');
            $map['table'] ='c_bet';
            $map['select'] = "qishu as LotteryNo,mingxi_1 as PlayTypeName,mingxi_2 as BetContent,odds as Lines,win as PredictWinMoney,money as TotalBetMoney";
            $map['where'] = "date_format(addtime,'%Y-%m-%d') ='".func_nowtime('Y-m-d','now')."' and uid=".$uid." and type='".$lotteryId."'";
            $map['order'] = 'id desc';
            // $map['limit'] = '20';
            $data = $this->lottery_model->get_table($map);
            foreach ($data as $k=>$v){
                $data[$k]['TotalBetMoney'] = (int)$v['TotalBetMoney'];
                $mun['SumBetMoney'] +=$v['TotalBetMoney'];
            }
            $json = array(
                'ErrorCode' => 0,
                'Data' => array(
                    'ReportList' => $data,
                    'AllSum' =>$mun
                )
            );
        }
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($json);
    }

    //投注记录
   function GetCount(){

/* $json = <<<Eof
{"ErrorCode":0,"Data":{"CountList":[{"Date":"2016-01-13","Count":10,"WinLoseMoney":"0.00"},{"Date":"2016-01-12","Count":0,"WinLoseMoney":"0.00"},{"Date":"2016-01-11","Count":0,"WinLoseMoney":"0.00"},{"Date":"2016-01-10","Count":0,"WinLoseMoney":"0.00"},{"Date":"2016-01-09","Count":0,"WinLoseMoney":"0.00"},{"Date":"2016-01-08","Count":0,"WinLoseMoney":"0.00"},{"Date":"2016-01-07","Count":0,"WinLoseMoney":"0.00"}],"AllSum":{"SumCount":0,"SumWinLoseMoney":"0.00"}}}
Eof;
        header('Content-Type: application/json;charset=utf-8');
        echo $json; */
		   $result = $this->lottery_model->_get_week_recored($_SESSION['uid']);
        $data = array();
        $result['allwin'] = 0;
        $result['allnum'] = 0;
        for($i = 0;$i<7;$i++){
            $result['betwin'.$i] = $result['win'.$i] - $result['bet'.$i] ;//当天输赢
            $result['allnum'] += $result['num'.$i];    //总笔数
            $result['allwin'] += $result['betwin'.$i]; //总输赢
            $data[$i] = array('Date'=>$result['date'.$i],"Count"=>$result['num'.$i],"WinLoseMoney"=>$result['betwin'.$i]);
         }
        $data['AllSum'] = array('SumCount'=>$result['allnum'],"SumWinLoseMoney"=>$result['allwin']);
         $json = array(
            'ErrorCode' => 0,
            'Data' => array(
                'CountList' => $data
            )
        );
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($json);
   }

  function GetCountByDate(){
  /*   $json = <<<Eof
{"ErrorCode":0,"Data":{"CountList":[{"LotteryName":"2016-01-13","Count":10,"WinLoseMoney":"0.00"},{"LotteryName":"2016-01-12","Count":0,"WinLoseMoney":"0.00"},{"LotteryName":"2016-01-11","Count":0,"WinLoseMoney":"0.00"},{"Date":"2016-01-10","Count":0,"WinLoseMoney":"0.00"},{"Date":"2016-01-09","Count":0,"WinLoseMoney":"0.00"},{"Date":"2016-01-08","Count":0,"WinLoseMoney":"0.00"},{"Date":"2016-01-07","Count":0,"WinLoseMoney":"0.00"}],"AllSum":{"SumCount":0,"SumWinLoseMoney":"0.00"}}}
Eof;
        header('Content-Type: application/json;charset=utf-8');
        echo $json; */
	 $postarr =$this->input->file_get();
        $uid = $_SESSION['uid'];
        $date = $postarr['Date'];
        $result = $this->lottery_model->_get_oneday_recored($uid,$date);
        $data = array();
        $data['AllSum']['SumCount'] = 0;
        $data['AllSum']['SumWinLoseMoney'] = 0;
        foreach($result as $k=>$v){
            $money = $v['win'] - $v['bet'];
            $data[$k] = array('LotteryName'=>$v['type'],"Count"=>$v['num'],"WinLoseMoney"=>$money);
            $data['AllSum']['SumCount']+= $v['num'];
            $data['AllSum']['SumWinLoseMoney']+= $money;
        }
         $json = array(
            'ErrorCode' => 0,
            'Data' => array(
                'CountList' => $data
            )
        );
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($json);


  }

 function GetCountByDateAndLotteryId(){
      $json = <<<Eof
{"ErrorCode":0,"Data":{"CountList":[{"LotteryName":"六合彩","Count":10,"WinLoseMoney":"0.00"},{"LotteryName":"2016-01-12","Count":0,"WinLoseMoney":"0.00"},{"LotteryName":"2016-01-11","Count":0,"WinLoseMoney":"0.00"},{"Date":"2016-01-10","Count":0,"WinLoseMoney":"0.00"},{"Date":"2016-01-09","Count":0,"WinLoseMoney":"0.00"},{"Date":"2016-01-08","Count":0,"WinLoseMoney":"0.00"},{"Date":"2016-01-07","Count":0,"WinLoseMoney":"0.00"}],"AllSum":{"SumCount":0,"SumWinLoseMoney":"0.00"}}}
Eof;
        header('Content-Type: application/json;charset=utf-8');
        echo $json;
 }

    function MBet()
    {
        /*
         * {"LotteryId":2,"MBetParameters":[{"Id":"204","Lines":1.982,"Money":34,"BetContext":"总和大","BetType":1,"Change":false},{"Id":"206","Lines":1.982,"Money":34,"BetContext":"总和单","BetType":1,"Change":false},{"Id":"208","Lines":1.982,"Money":34,"BetContext":"总尾大","BetType":1,"Change":false},{"Id":"209","Lines":1.982,"Money":34,"BetContext":"总尾小","BetType":1,"Change":false},{"Id":"207","Lines":1.982,"Money":34,"BetContext":"总和双","BetType":1,"Change":false}]}
         */
        $postarr =$this->input->file_get();
        header('Content-Type: application/json;charset=utf-8');
        $lotteryId = $this->lottery_model->get_lottery_type($postarr['LotteryId'],'type');
        $this->load->model('maintenance_model');
        $lot = $this->maintenance_model->getweihu('lottery')?0:1;
        $type_status = $this->maintenance_model->getweihu($lotteryId)?0:1;
        if($lot == 0 || $type_status == 0){
            //下注维护判断
            $data=array("ErrorCode"=>116);
            echo json_encode($data);exit;
        }
        

        // p($postarr);
        if(!empty($postarr['CombNum'])&&$postarr['CombNum']>1 || $postarr['MBetParameters'][0]['Pel'] == '选一'){
            $arr_temp = explode(',',$postarr['MBetParameters'][0]['BetContext']);
            $zuhe_arr=func_get_zuhe($arr_temp,$postarr['CombNum']);
            //$postarr['MBetParameters'] = ;
            foreach ($zuhe_arr as $k=>$v){
                $data[$k]['BetContext']=$v;
                $data[$k]['Money']=$postarr['MBetParameters'][0]['Money'];
                $data[$k]['Lines']=$postarr['MBetParameters'][0]['Lines'];
                $data[$k]['DisplayText']=$postarr['MBetParameters'][0]['DisplayText'];
                $data[$k]['Pel1']=$postarr['MBetParameters'][0]['Pel1'];
                $data[$k]['Pel']=$postarr['MBetParameters'][0]['Pel'];
                $data[$k]['Txt']=rtrim($postarr['MBetParameters'][0]['Txt'], ",");
            }
            $postarr['MBetParameters'] = $data;
        }
        // p($postarr);exit;
        $data = $this->lottery_model->_addlottery_bet($postarr);
        echo $data;

        // $json = <<<Eof
        // {"ErrorCode":0,"ErrorMsg":"投注成功"}
        // Eof;
        // header('Content-Type: application/json;charset=utf-8');
        // echo $json;
    }

    //获取即时下注总额
    function NotCountBetMoney()
    {
        $map['table'] = 'c_bet';
        $map['select'] = 'sum(money) as money';
        $map['where']['index_id'] = INDEX_ID;
        $map['where']['site_id'] = SITEID;
        $map['where']['addtime >='] = date('Y-m-d').' 00:00:00';
        $map['where']['addtime <='] = date('Y-m-d').' 23:59:59';
        $map['where']['uid'] = $_SESSION['uid'];
        $data = $this->lottery_model->rfind($map);
        $json = array(
            'ErrorCode' => 0,
            'Data' => array(
                'BetMoney' => (int) $data['money']
            )
        );
        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($json);
    }
}
?>