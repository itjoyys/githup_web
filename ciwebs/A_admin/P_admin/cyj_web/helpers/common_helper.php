<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

// 常用函数
function p($arr)
{
    print_r('<pre>');
    print_r($arr);
    print_r('<pre>');
}

//获取系统版本
function getOS(){
    $os='';
    $Agent=$_SERVER['HTTP_USER_AGENT'];
    if (eregi('win',$Agent)&&strpos($Agent, '95')){
        $os='Windows 95';
    }elseif(eregi('win 9x',$Agent)&&strpos($Agent, '4.90')){
        $os='Windows ME';
    }elseif(eregi('win',$Agent)&&ereg('98',$Agent)){
        $os='Windows 98';
    }elseif(eregi('win',$Agent)&&eregi('nt 5.0',$Agent)){
        $os='Windows 2000';
    }elseif(eregi('win',$Agent)&&eregi('nt 6.0',$Agent)){
        $os='Windows Vista';
    }elseif(eregi('win',$Agent)&&eregi('nt 6.1',$Agent)){
        $os='Windows 7';
    }elseif(eregi('win',$Agent)&&eregi('nt 5.1',$Agent)){
        $os='Windows XP';
    }elseif(eregi('win',$Agent)&&eregi('nt',$Agent)){
        $os='Windows NT';
    }elseif(eregi('win',$Agent)&&ereg('32',$Agent)){
        $os='Windows 32';
    }elseif(eregi('linux',$Agent)){
        $os='Linux';
    }elseif(eregi('unix',$Agent)){
        $os='Unix';
    }else if(eregi('sun',$Agent)&&eregi('os',$Agent)){
        $os='SunOS';
    }elseif(eregi('ibm',$Agent)&&eregi('os',$Agent)){
        $os='IBM OS/2';
    }elseif(eregi('Mac',$Agent)&&eregi('PC',$Agent)){
        $os='Macintosh';
    }elseif(eregi('PowerPC',$Agent)){
        $os='PowerPC';
    }elseif(eregi('AIX',$Agent)){
        $os='AIX';
    }elseif(eregi('HPUX',$Agent)){
        $os='HPUX';
    }elseif(eregi('NetBSD',$Agent)){
        $os='NetBSD';
    }elseif(eregi('BSD',$Agent)){
        $os='BSD';
    }elseif(ereg('OSF1',$Agent)){
        $os='OSF1';
    }elseif(ereg('IRIX',$Agent)){
        $os='IRIX';
    }elseif(eregi('FreeBSD',$Agent)){
        $os='FreeBSD';
    }elseif($os==''){
        $os='Unknown';
    }
    return $os;
}


/**
 * 信息提示页面
 * @$content 要提示的文字
 * @$continue 即将跳往的页面，返回用“back ”
 * @$status 提示状态，值为0,1或其它，0为错误提示(红色)，1为正常提示（绿色）
 */
function showmessage($content, $continue, $status = 1)
{
    $continue = ($continue == 'back') ? 'history.back()' : 'window.location="' . $continue . '"';
    $waits = ($status == 0) ? 'setTimeout("thisUrl()", 2500)' : 'setTimeout("thisUrl()", 1500)';
    $status = ($status == 0) ? 'color:#FF0000' : 'color:#009900';
    $string = "<div class='box'>\n<h5>提示信息</h5>\n<p class='content'>" . $content . "</p>\n\n</div>";
    $style = "<style>\nbody,h5,p,a{font:12px Verdana,Tahoma;text-align:center;text-decoration:none;margin:0;padding:0;}
    \nh5{color:#555;font-size:14px;height:28px;text-align:center;line-height:28px;font-weight:bold;background:
    #EEE;margin:1px;padding:0 10px;} \n.box{width:480px;border:1px solid #DDD;margin:120px auto;-moz-box-shadow:
    3px 4px 5px #EEE;-webkit-box-shadow:3px 4px 5px #EEE;}\n.content{" . $status . ";font-size:14px;font-weight:bold;
    line-height:24px;padding:30px 10px;}\n.clickUrl{color:#888;margin-bottom:15px;padding:0 10px;}\n</style>";
    $html = "<!DOCTYPE html>\n<html>\n<head>\n<title>提示信息</title>\n<meta http-equiv='Content-Type' content='text/html;
    charset=utf-8'/>\n" . $style . "\n<script type='text/javascript'>\nfunction thisUrl(){" . $continue . "}\n" . $waits . "\n
    </script>\n</head>\n<body>\n" . $string . "\n</body>\n</html>";
    exit($html);
}

//查询时间限制60天 结束日期 起始日期
function about_limit($edate,$sdate){
    $tmp_limit = (strtotime($edate.' 00:00:00') - strtotime($sdate.' 23:59:59'))/3600/24;
    if ($tmp_limit > 61) {
        showmessage('查询时间区间限制为62天','back',0);
    }
}

function ifstatus($status)
{
    switch ($status) {
        case 0:
            $statusinfo = '未结算';
            break;
        case 1:
            $statusinfo = '<span style="color:#FF0000;">赢</span>';
            break;
        case 2:
            $statusinfo = '<span style="color:#00CC00;">输</span>';
            break;
        case 3:
            $statusinfo = '注单无效';
            break;
        case 4:
            $statusinfo = '<span style="color:#FF0000;">赢一半</span>';
            break;
        case 5:
            $statusinfo = '<span style="color:#00CC00;">输一半</span>';
            break;
        case 6:
            $statusinfo = '进球无效';
            break;
        case 7:
            $statusinfo = '红卡取消';
            break;
        case 8:
            $statusinfo = '和局';
            break;
        default:
            ;
            break;
    }
    return $statusinfo;
}

function get_account_level()
{
    return array(
        '4' => '会员',
        '1' => '股东',
        '2' => '总代理',
        '3' => '代理',
        '5' => '推广ID'
    );
}

function array_add($a, $b)
{
    // 根据键名获取两个数组的交集
    $arr = array_intersect_key($a, $b);
    // 遍历第二个数组，如果键名不存在与第一个数组，将数组元素增加到第一个数组
    foreach ($b as $key => $value) {
        if (! array_key_exists($key, $a)) {
            $a[$key] = $value;
        }
    }
    // 计算键名相同的数组元素的和，并且替换原数组中相同键名所对应的元素值
    foreach ($arr as $key => $value) {
        $a[$key] = $a[$key] + $b[$key];
    }
    // 返回相加后的数组
    return $a;
}

// 网站资讯系统 文案类别
function case_type($type)
{
    switch ($type) {
        case '1':
            return '线上入款';
            break;
        case '2':
            return '公司入款';
            break;
        case '3':
            return '关于我们';
            break;
        case '4':
            return '联系我们';
            break;
        case '5':
            return '代理联盟';
            break;
        case '6':
            return '存款帮助';
            break;
        case '7':
            return '取款帮助';
            break;
        case '8':
            return '常见问题';
            break;
        case '9':
            return '会员注册';
            break;
        case '10':
            return '代理注册';
            break;
        case '11':
            return '网站LOGO';
            break;
        case '12':
            return '会员中心LOGO';
            break;
        case '13':
            return '首页轮播图';
            break;
        case '14':
            return '优惠活动';
            break;
        case '15':
            return '首页游戏图';
            break;
        case '16':
            return '左边浮动';
            break;
        case '17':
            return '右边浮动';
            break;
        case '18':
            return '开户协议';
            break;
        case '30':
            return 'waplogo';
        case '31':
            return 'wap轮播';
            break;
    }
}

// 编辑器图片上传处理
function Uedit()
{
    header("Content-Type: text/html; charset=utf-8");
    $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("config.json")), true);
    $action = $_GET['action'];
    switch ($action) {
        case 'config':
            $result = json_encode($CONFIG);
            break;

        /* 上传图片 */
        case 'uploadimage':
        /* 上传涂鸦 */
        case 'uploadscrawl':
        /* 上传视频 */
        case 'uploadvideo':
        /* 上传文件 */
        case 'uploadfile':
            $result = include ("action_upload.php");
            break;
        /* 列出图片 */
        case 'listimage':
            $result = include ("action_list.php");
            break;
        /* 列出文件 */
        case 'listfile':
            $result = include ("action_list.php");
            break;

        /* 抓取远程文件 */
        case 'catchimage':
            $result = include ("action_crawler.php");
            break;
        default:
            $result = json_encode(array(
                'state' => '请求地址出错'
            ));
            break;
    }
    /* 输出结果 */
    if (isset($_GET["callback"])) {
        if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
            echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
        } else {
            echo json_encode(array(
                'state' => 'callback参数不合法'
            ));
        }
    } else {
        echo $result;
    }
}

// 单选框选中
function radio_check($val_a, $val_b)
{
    if (isset($val_a) && isset($val_b)) {
        if ($val_a == $val_b) {
            echo "checked=\"checked\"";
        }
    }
}

// 多选框选中
function check_box($val_a, $val_b)
{
    if (isset($val_a) &&  isset($val_b)) {
        if ($val_a == $val_b) {
            echo "checked=\"true\"";
        }
    }
}

// 多选框选中
function check_box2($val_a, $val_b)
{
    $if = 0;
    if (isset($val_a) &&  isset($val_b)) {
        foreach (explode(',', $val_a) as $s) {
            if ($s == $val_b) {
                $if = 1;
            }
        }
        if ($if == 1) {
            echo "checked=\"true\"";
        }
    }
}

function select_check($field, $val)
{
    if (isset($val)) {
        if ($field == $val) {
            echo "selected=\"selected\"";
        }
    }
}

function get_page_num()
{
    return $pagenum = array(
        '20' => '20条',
        '30' => '30条',
        '50' => '50条',
        '100' => '100条'
    );
}


function get_set_type()
{
    return $type = array(
        'sp' => '体育',
        'fc' => '彩票',
        'video' => '视讯'
    );
}
function get_agent_sort(){
    return array(
        'agent_name' => '股東名稱',
        'agent_user' => '股東帳號',
        'add_date' => '新增日期',
    );
}

function getscale()
{
    return array(
        '1.00' => '全成',
        '0.95' => '9.5成',
        '0.90' => '9成',
        '0.85' => '8.5成',
        '0.80' => '8.0成',
        '0.75' => '7.5成',
        '0.70' => '7.0成',
        '0.65' => '6.5成',
        '0.60' => '6.0成',
        '0.55' => '5.5成',
        '0.50' => '5.0成',
        '0.45' => '4.5成',
        '0.40' => '4.0成',
        '0.35' => '3.5成',
        '0.30' => '3.0成',
        '0.25' => '2.5成',
        '0.20' => '2.0成',
        '0.15' => '1.5成',
        '0.10' => '1.0成',
        '0.05' => '0.5成'
    );
}
function get_pscale($count){
    $row =array();
    for($i='0.00';$i<=$count;$i+=0.05){
        $row["$i"] = ($i*10)."成";
    }
    return $row;
}
function get_mem_sort()
{
    return array(
        'reg_date' => '注册日期',
        'username' => '會員帳號',
        'login_time' => '登入日期',
        'money' => '系统额度',
        'mg_money' => 'MG額度',
        'ag_money' => 'AG額度',
        'og_money' => 'OG額度',
        'lebo_money' => 'LEBO額度',
        'bbin_money' => 'BBIN額度',
        'ct_money' => 'CT額度',
    );
}


function get_agent_type($type){
    switch ($type) {
        case 's_h':
        $retrun = '股東';
        break;
        case 'u_a':
            $retrun = '總代理';
            break;
       case 'a_t':
          $retrun = '代理';
       break;
        default:
            ;
        break;
    }
    return $retrun;
}

function get_agent_type_pid($type){
    switch ($type) {
        case 'u_a':
            $retrun = '股东';
            break;
        case 'a_t':
            $retrun = '总代理';
            break;
        default:
            ;
            break;
    }
    return $retrun;
}

function get_agent_type_key($type){
    switch ($type) {
        case 's_h':
            $retrun = 'u_a';
            break;
        case 'u_a':
            $retrun = 'a_t';
            break;
        case 'a_t':
            $retrun = 'user';
            break;
        default:
            ;
            break;
    }
    return $retrun;
}

//导航
function return_meau()
{
    return array(
        'a' => array(
            'name' => '账号管理',
            'val' => array('1' => '會員管理','2' => '股東管理',
                           '3' => '總代理管理','4' => '代理管理',
                           '5' => '子账号','6' => '會員注册設定',
                           '7'=>'代理申请管理','8'=>'体系查询',
                           '9'=>'层级管理'),
            'url' => array('1' => '../account/member_index',
                           '2' => '../account/agent_index?agent_type=s_h',
                           '3' => '../account/agent_index?agent_type=u_a',
                           '4' => '../account/agent_index?agent_type=a_t',
                           '5' => '../account/sub_account',
                           '6' => '../account/member_reg',
                           '7' => '../account/agent_reg',
                           '8' => '../account/account_search',
                           '9' => '../cash/account_level'),
        ),
        'b' => array(
            'name' => '即时注单',
            'val' => array('2' => '體育注單',
                           '4' => '彩票注單',
                           '5' => '視訊注單'),
            'url' => array(
                           '2' => '../note/bet_record/sp_bet_record',

                           '4' => '../note/bet_record/fc_bet_record',
                           '5' => '../note/bet_record/video_bet_record'),
        ),
        'c' => array(
            'name' => '报表查询',
            'val' => array('1' => '报表明细','2' => '历史報表'),
            'url' => array('1' => '../report/report/index',
                           '2' => '../report/report/index'),
        ),
        'd' => array(
            'name' => '赛果/赔率',
            'val' => array('1' => '体育比赛结果','2' => '彩票开奖结果',
                           '3'=>'赔率设置'),
            'url' => array('1'  => '../result/sp_result/football',
                           '2' => '../result/fc_result',
                           '3' => '../result/odds_set2/index_fc'
                           ),
        ),
        'e' => array(
            'name' => '现金系统',
            'val' => array('01' => '存款與取款','2' => '出入账目汇总',
                           '3' => '出款管理','4' => '入款管理',
                           '5' => '即時稽核查詢','6' => '稽核日志',
                           '9' => '支付設定',
                           '11' => '会员分析系统','12' => '現金系統',
                           '13' => '退傭統計','14' => '會員餘額統計',
                           '15' => '優惠計算'),
            'url' => array('01' => '../cash/catm',
                           '2' => '../cash/cash_count',
                           '3' => '../cash/out_record',
                           '4' => '../cash/bank_record?into_style=1',
                           '5' => '../cash/audit/index',
                           '6' => '../cash/audit/audit_log',
                           '9' => '../cash/payment',
                           '11' => '../cash/member_analysis/analysis_note',
                           '12' => '../cash/cash_system',
                          // '13' => '../../../old/cash/agent_count.php',
                           '13' => '../cash/cash_agent/index',
                           '14' => '../cash/blance',
                           '15' => '../cash/discount/index'),
        ),

        'f' => array(
            'name' => '其它',
            'val' => array('1' => '限額/退水','2' => '网站资讯管理',
                           '3' => '会员消息','4' => '日志管理',
                           '5' => '最新消息','6' => '上级公告',
                           '7' => '红包管理','8' => '域名管理'),
            'url' => array('1' => '../other/system/limit_index?type=sp',
                           '2' => '../site_info/index/index',
                           '3' => '../other/news/member_msg_index',
                           '4' => '../other/log_manage/member_login_log',
                           '5' => '../other/news/index',
                           '6' => '../other/notice',
						   '7' => '../other/red_bag',
                           '8' => '../other/server_domain/site_domain'),
            'type'=>array('2'=>'_'),
        )
    );
}

// 二维数组转一维
function i_array_column($input, $columnKey, $indexKey = null)
{
    if (! function_exists('array_column')) {
        $columnKeyIsNumber = (is_numeric($columnKey)) ? true : false;
        $indexKeyIsNull = (is_null($indexKey)) ? true : false;
        $indexKeyIsNumber = (is_numeric($indexKey)) ? true : false;
        $result = array();
        foreach ((array) $input as $key => $row) {
            if ($columnKeyIsNumber) {
                $tmp = array_slice($row, $columnKey, 1);
                $tmp = (is_array($tmp) && ! empty($tmp)) ? current($tmp) : null;
            } else {
                $tmp = isset($row[$columnKey]) ? $row[$columnKey] : null;
            }
            if (! $indexKeyIsNull) {
                if ($indexKeyIsNumber) {
                    $key = array_slice($row, $indexKey, 1);
                    $key = (is_array($key) && ! empty($key)) ? current($key) : null;
                    $key = is_null($key) ? 0 : $key;
                } else {
                    $key = isset($row[$indexKey]) ? $row[$indexKey] : 0;
                }
            }
            $result[$key] = $tmp;
        }
        return $result;
    } else {
        return array_column($input, $columnKey, $indexKey);
    }
}
