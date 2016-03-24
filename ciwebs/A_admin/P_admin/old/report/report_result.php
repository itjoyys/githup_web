<?php
set_time_limit(0);
include_once ("../../../include/config.php");
include_once ("../common/login_check.php");
include_once ("../../class/Level.class.php");
include_once ("../../../lib/video/Games.class.php");
$is_res = $_GET['is_res']; // 是否有结果
$date_start = $_GET["date_start"] = $_GET["date_start"] . " 00:00:00";
$date_end = $_GET["date_end"] = $_GET["date_end"] . " 23:59:59";

//时间限制判断，是否在40天之内
time_check($date_start,$date_end);

$games = $_GET['games'];

if(!is_array($_GET['game'])){
    $_GET['game'] = explode(',',$_GET['game']);
}
$get = $_GET;
// 层级数组
$level_a = array(
    '0' => array(
        'name' => '會員'
    ),
    '1' => array(
        'name' => '代理商'
    ),
    '2' => array(
        'name' => '代理商所得'
    )
);

if ($_GET['atype'] == 's_h') {
    // 股东报表
    $atype = 'u_a';
    $table_title = '股東';
    $u_table_title = '公司';
    $level_a[3]['name'] = '總代理';
    $level_a[4]['name'] = '股東';
    $level_a[5]['name'] = '公司';
} elseif ($_GET['atype'] == 'u_a') {
    // 总代报表
    $a_map = " and agent_type <> 's_h' and pid=".$get['uid'];
    $atype = 'a_t';
    $table_title = '總代理';
    $u_table_title = '股東';
    $level_a[3]['name'] = '總代理';
    $level_a[4]['name'] = '股東';
} elseif ($_GET['atype'] == 'a_t') {
    // echo 2;exit;
    // 代理商报表
    $atype = 'user';

    $a_map = " and agent_type <> 'u_a' and pid=".$get['uid'];
    $table_title = '代理';
    $u_table_title = '總代理';
    $level_a[3]['name'] = '總代理';
} elseif ($_GET['atype'] == 'user') {
     $a_map = " and agent_type <> 'u_a' and id=".$get['uid'];
    $table_title = '会员';
    $u_table_title = '代理';
}

function getr_title($db_config)
{
    if (! empty($_GET['uid'])) {
        $uname = M('k_user_agent', $db_config)->where("id='" . $_GET['uid'] . "' and site_id='" . $_SESSION['site_id'] . "'")->find();
    }
    if ($_GET['atype'] == 's_h') {
        $r_title = '公司';
    } elseif ($_GET['atype'] == 'u_a') {
        $r_title = $uname['agent_user'] . '股東';
    } elseif ($_GET['atype'] == 'a_t') {
        $r_title = $uname['agent_user'] . '總代';
    } elseif ($_GET['atype'] == 'user') {
        $r_title = $uname['agent_user'] . '代理';
    }

    return $r_title;
}

$u = M('k_user', $db_config);
// 获取每个股东旗下所有会员
$array = array();
$list = getagentall($a_map, $db_config, ',', $get);

foreach ($list as $k=>$v){
    foreach ($v as $k1=>$v1){
        if(!strstr($k1,'total')){
        $array[$k1]['id'] = $v1['id']?$v['id']:$v['uid'];
        $array[$k1]['user'][0]['agent_user'] = $v1['user'][0]['agent_user']?$v1['user'][0]['agent_user']:$v1['user'][0]['username'];
        $array[$k1]['user'][0]['agent_name'] = $v1['user'][0]['agent_name']?$v1['user'][0]['agent_name']:$v1['user'][0]['pay_name'];
        $array[$k1]['a_t'] = $v1['a_t'];
        $array[$k1]['u_a'] = $v1['u_a'];
        $array[$k1]['s_h'] = $v1['s_h'];
        $array[$k1]['agent_sh']['s_hdljs'] += $v1['agent_sh']['s_hdljs'];
        $array[$k1]['agent_sh']['s_hdljg'] += $v1['agent_sh']['s_hdljg'];
        $array[$k1]['agent_sh']['s_hzdjs'] += $v1['agent_sh']['s_hzdjs'];
        $array[$k1]['agent_sh']['s_hgdjs'] += $v1['agent_sh']['s_hgdjs'] ;
        $array[$k1]['agent_sh']['count'] += $v1['agent_sh']['count']+$v1['agent_sh']['num'];
        $array[$k1]['agent_sh']['bet_money'] += $v1['agent_sh']['bet_money']+$v1['agent_sh']['BetAll'];
        $array[$k1]['agent_sh']['bet_money_YX'] += $v1['agent_sh']['bet_money_YX']+$v1['agent_sh']['BetYC'];
        $array[$k1]['agent_sh']['payout'] += $v1['agent_sh']['payout']+$v1['agent_sh']['BetPC'] + $v1['agent_sh']['BetYC'];
        }
    }
}

foreach ($array as $k=>$v){
    $array['total_s_hdljs'] += $array[$k]['agent_sh']['s_hdljs'];
    $array['total_s_hdljg'] += $array[$k]['agent_sh']['s_hdljg'];
    $array['total_s_hzdjs'] += $array[$k]['agent_sh']['s_hzdjs'];
    $array['total_s_hgdjs'] +=  $array[$k]['agent_sh']['s_hgdjs'] ;
    $array['total_count'] += $array[$k]['agent_sh']['count'];
    $array['total_bet_money'] += $array[$k]['agent_sh']['bet_money'];
    $array['total_bet_money_YX'] += $array[$k]['agent_sh']['bet_money_YX'];
    $array['total_payout'] +=  $array[$k]['agent_sh']['payout'] ;
}

$list['total'] = $array;
function get_report_url($id,$gtype,$atype,$db_config){
    if ($_GET['atype'] == 'user') {
        if($gtype=='zq'){
            $php = 'list.php';
        }elseif($gtype=='cp'){
            $php = 'fc.php';
        }else{
            $php = 'video.php';
        }
        $gameinfo = gameinfo($gtype);
        $username = getuserinfo($id,$db_config);
        $id = $username[0]['username'];
        $report_url = "../note/".$php."?username=".$id."&date_start=".substr($_GET["date_start"],0,10)."&date_end=".substr($_GET["date_end"],0,10)."&gtype=".$gameinfo['name'];
    }else{
        $g = implode(',',$_GET['game']);
        $report_url = './report_result.php?uid='.$id.'&is_res='.$_GET["is_res"].'&date_start='.substr($_GET["date_start"],0,10).'&date_end='.substr($_GET["date_end"],0,10).'&game='.$g.'&gtype='.$gtype.'&atype='.$atype;
    }
    return $report_url;
}

function getagentall($a_map, $db_config, $Symbol, $get)
{
    $agent = M('k_user_agent', $db_config)->field('id,pid')
        ->where("is_demo = '0' and site_id = '" . $_SESSION['site_id'] . "'" . $a_map)
        ->select();
    if (! empty($agent)) {
        foreach ($agent as $key => $val) {
            $agentid .= $val['id'] . $Symbol;
        }
        $agentid = trim($agentid, $Symbol);
        $data_a['pid']   = array('in',"(".$agentid.")");
        if($get['atype']=='u_a'){
            $agentu_a = M('k_user_agent', $db_config)->field('id,pid')
            ->where($data_a)
            ->select();
            $agentid='';
            foreach ($agentu_a as $key => $val) {
                $agentid .= $val['id'] . $Symbol;
            }
            $agentid = trim($agentid, $Symbol);
        }
    }
    $list = getmoneyall($agentid, $get, $db_config);
    return $list;
}

function getmoneyall($agentid, $get, $db_config)
{
    $list['total'] = '';
    foreach ($get['game'] as $k => $v) {
        $list[$v] = getmoneyone($v, $agentid, $get, $db_config);
    }
    return $list;
}

function getmoneyone($game, $agentid, $get, $db_config)
{
    switch ($game) {
        case 'zq':
            return getzq($agentid, $db_config, $get);
            break;
        case 'cp':
            return getcp($agentid, $db_config, $get);
            break;
        default:
            return getshixun($game, $agentid, $db_config, $get);
            break;
    }
}

function getzq($agentid, $db_config, $get)
{
    // 条件初始化
    $data_a = $data_b = $data_c = $data_d = array();
    if ($get['atype']=='user'){
        $key = 'uid';
    }else{
        $key = 'agent_id';
    }
    // 体育总笔数和总投注
    $data_a['agent_id'] = array(
        'in',
        "(" . $agentid . ")"
    );;
    $data_a['update_time'] = array(
        array(
            '>=',
            $get['date_start']
        ),
        array(
            '<=',
            $get['date_end']
        )
    );
    if ($get['is_res'] == 1) {
        $data_a['is_jiesuan'] = 1;
        $data_a['status'] = array(
            'in',
            "(1,2,4,5)"
        );
    }
    $sp_bett = M('k_bet', $db_config)->field("count(bid) as sp_count,sum(bet_money) as sp_bet_money,agent_id,uid")
        ->where($data_a)
        ->group($key)
        ->select();
    // 体育总有效金额和总派彩
    $data_b['agent_id'] = array(
        'in',
        "(" . $agentid . ")"
    );
    $data_b['update_time'] = array(
        array(
            '>=',
            $get['date_start']
        ),
        array(
            '<=',
            $get['date_end']
        )
    );
    $data_b['status'] = array(
        'in',
        "(1,2,4,5)"
    );
    $sp_bet = M('k_bet', $db_config)->field("sum(bet_money) as sp_bet_money_YX,sum(win) as payout_sp,agent_id,uid")
        ->where($data_b)
        ->group($key)
        ->select();

    foreach ($sp_bett as $k => $v) {
        $agent_sh[$v[$key]]['uid'] = $v['uid'];
        $agent_sh[$v[$key]]['agent_id'] = $v['agent_id'];
        $agent_sh[$v[$key]]['count'] = $v['sp_count'];
        $agent_sh[$v[$key]]['bet_money'] = $v['sp_bet_money'];
        $agent_sh[$v[$key]]['bet_money_YX'] = $sp_bet[$k]['sp_bet_money_YX'];
        $agent_sh[$v[$key]]['payout'] = $sp_bet[$k]['payout_sp'];
    }

    // 体育串关总笔数和总投注
    $data_c['agent_id'] = array(
        'in',
        "(" . $agentid . ")"
    );
    $data_c['update_time'] = array(
        array(
            '>=',
            $get['date_start']
        ),
        array(
            '<=',
            $get['date_end']
        )
    );
    if ($get['is_res'] == 1) {
        $data_c['is_jiesuan'] = 1;
        $data_c['status'] = array(
            'in',
            "(1,2)"
        );
    }
    $bett = M('k_bet_cg_group', $db_config)->field("count(gid) as spc_count,sum(bet_money) as spc_bet_money,agent_id,uid")
        ->where($data_c)
        ->group('agent_id')
        ->select();

    // 体育串关总有效金额和总派彩
    $data_d['agent_id'] = array(
        'in',
        "(" . $agentid . ")"
    );
    ;
    $data_d['update_time'] = array(
        array(
            '>=',
            $get['date_start']
        ),
        array(
            '<=',
            $get['date_end']
        )
    );
    $data_d['status'] = array(
        'in',
        "(1,2)"
    );
    $bet = M('k_bet_cg_group', $db_config)->field("sum(bet_money) as spc_bet_money_YX,sum(win) as payout_spc,agent_id,uid")
        ->where($data_d)
        ->group($key)
        ->select();
    foreach ($bett as $k => $v) {
        $agent_sh[$v[$key]]['uid'] = $v['uid'];
        $agent_sh[$v[$key]]['agent_id'] = $v['agent_id'];
        $agent_sh[$v[$key]]['ccount'] = $v['spc_count'];
        $agent_sh[$v[$key]]['cbet_money'] = $v['spc_bet_money'];
        $agent_sh[$v[$key]]['cbet_money_YX'] = $bet[$k]['spc_bet_money_YX'];
        $agent_sh[$v[$key]]['cpayout'] = $bet[$k]['payout_spc'];
    }
    foreach ($agent_sh as $k => $v) {
        $a_t = getagentinfo($v['agent_id'], $db_config);
        $u_a = getagentinfo($a_t[0]['pid'], $db_config);
        $s_h  = getagentinfo($u_a[0]['pid'], $db_config);
        if ($get['atype'] == 'a_t') {
            $pid['pid'] = $a_t[0]['id'];
            $agentinfo[$pid['pid']]['user'] = getagentinfo($pid['pid'], $db_config);
        } elseif ($get['atype'] == 'u_a') {
            $pid['pid'] =$u_a[0]['id'];
            $agentinfo[$pid['pid']]['user'] = getagentinfo($pid['pid'], $db_config);
        } elseif ($get['atype'] == 's_h') {
            $pid['pid'] =$s_h[0]['id'];
            $agentinfo[$pid['pid']]['user'] = getagentinfo($pid['pid'], $db_config);
        }elseif ($get['atype']=='user'){
            $v['agentid'] = $v['uid'];
            $agentinfo[$v['uid']]['user'] = getuserinfo($v['agentid'], $db_config);
            $agentinfo[$v['uid']]['user'][0]['pay_name']="线上注册会员";
            $pid['pid'] =$v['uid'];
        }
        $agentinfo[$pid['pid']]['a_t'] = $a_t;
        $agentinfo[$pid['pid']]['u_a'] = $u_a;
        $agentinfo[$pid['pid']]['s_h'] = $s_h;
        foreach ($agentinfo[$pid['pid']]['a_t'] as $k3=>$v3){
            $agentinfo[$pid['pid']]['agent_sh']['s_hdljs']+=(1-$v3['lottery_scale'])* (($v['bet_money_YX'] + $v['cbet_money_YX']) - ($v['payout'] + $v['cpayout']));
            $agentinfo[$pid['pid']]['agent_sh']['s_hdljg'] += $v3['lottery_scale'] * ($v['bet_money_YX'] - ($v['payout'] + $v['cpayout']));
            $agentinfo['total_s_hdljs']+=(1-$v3['lottery_scale'])* (($v['bet_money_YX'] + $v['cbet_money_YX']) - ($v['payout'] + $v['cpayout']));
            $agentinfo['total_s_hdljg']+=$v3['lottery_scale'] * ($v['bet_money_YX'] - ($v['payout'] + $v['cpayout']));
        }
        foreach ($agentinfo[$pid['pid']]['u_a'] as $k3=>$v3){
            $agentinfo[$pid['pid']]['agent_sh']['s_hzdjs']+=(1-$v3['lottery_scale'])* (($v['bet_money_YX'] + $v['cbet_money_YX']) - ($v['payout'] + $v['cpayout']));
            $agentinfo['total_s_hzdjs']+=(1-$v3['lottery_scale'])* (($v['bet_money_YX'] + $v['cbet_money_YX']) - ($v['payout'] + $v['cpayout']));
        }
        foreach ($agentinfo[$pid['pid']]['s_h'] as $k3=>$v3){
            $agentinfo[$pid['pid']]['agent_sh']['s_hgdjs']+=(1-$v3['lottery_scale'])* (($v['bet_money_YX'] + $v['cbet_money_YX']) - ($v['payout'] + $v['cpayout']));
            $agentinfo['total_s_hgdjs']+=(1-$v3['lottery_scale'])* (($v['bet_money_YX'] + $v['cbet_money_YX']) - ($v['payout'] + $v['cpayout']));
        }
        $agentinfo[$pid['pid']]['agent_sh']['count']+=($v['count'] + $v['ccount']);
        $agentinfo[$pid['pid']]['agent_sh']['bet_money'] += ($v['bet_money'] + $v['cbet_money']);
        $agentinfo[$pid['pid']]['agent_sh']['bet_money_YX'] += ($v['bet_money_YX'] + $v['cbet_money_YX']);
        $agentinfo[$pid['pid']]['agent_sh']['payout'] += ($v['payout'] + $v['cpayout']);
        $agentinfo['total_count'] += ($v['count'] + $v['ccount']);
        $agentinfo['total_bet_money'] += ($v['bet_money'] + $v['cbet_money']);
        $agentinfo['total_bet_money_YX'] += ($v['bet_money_YX'] + $v['cbet_money_YX']);
        $agentinfo['total_payout'] += ($v['payout'] + $v['cpayout']);
    }
    return $agentinfo;
}

function getcp($agentid, $db_config, $get)
{
    // 条件初始化
    $data_a = $data_b = array();
    if ($get['atype']=='user'){
        $key = 'uid';
    }else{
        $key = 'agent_id';
    }
    // 彩票总笔数和总投注
    $data_a['agent_id'] = array(
        'in',
        "(" . $agentid . ")"
    );
    $data_a['update_time'] = array(
        array(
            '>=',
            $get['date_start']
        ),
        array(
            '<=',
            $get['date_end']
        )
    );
    if ($get['is_res'] == 1) {
        $data_a['js'] = 1;
        $data_a['status'] = array('!=',"0");
    }
    $bett = M('c_bet', $db_config)->field("count(id) as cp_count,sum(money) as c_bet_money,agent_id,uid")
        ->group($key)
        ->where($data_a)
        ->select();

    // 彩票总有效金额
    $data_b['agent_id'] = array(
        'in',
        "(" . $agentid . ")"
    );
    $data_b['status'] = array('in',"(1,2,3)");
    $data_b['update_time'] = array(
        array(
            '>=',
            $get['date_start']
        ),
        array(
            '<=',
            $get['date_end']
        )
    );
    $bet = M('c_bet', $db_config)->field("sum(money) as c_bet_money_YX,sum(win) as payout_fc,agent_id,uid")
        ->where($data_b)
        ->group($key)
        ->select();
    foreach ($bett as $k => $v) {
        $agent_sh[$v[$key]]['uid'] = $v['uid'];
        $agent_sh[$v[$key]]['agent_id'] = $v['agent_id'];
        $agent_sh[$v[$key]]['count'] = $v['cp_count'];
        $agent_sh[$v[$key]]['bet_money'] = $v['c_bet_money'];
        $agent_sh[$v[$key]]['bet_money_YX'] = $bet[$k]['c_bet_money_YX'];
        $agent_sh[$v[$key]]['payout'] = $bet[$k]['payout_fc'];

    }
    foreach ($agent_sh as $k => $v) {

        $a_t = getagentinfo($v['agent_id'], $db_config);
        $u_a = getagentinfo($a_t[0]['pid'], $db_config);
        $s_h  = getagentinfo($u_a[0]['pid'], $db_config);
        if ($get['atype'] == 'a_t') {
            $pid['pid'] = $a_t[0]['id'];
            $agentinfo[$pid['pid']]['user'] = getagentinfo($pid['pid'], $db_config);
        } elseif ($get['atype'] == 'u_a') {
          $pid['pid'] =$u_a[0]['id'];
          $agentinfo[$pid['pid']]['user'] = getagentinfo($pid['pid'], $db_config);
        } elseif ($get['atype'] == 's_h') {
           $pid['pid'] =$s_h[0]['id'];
           $agentinfo[$pid['pid']]['user'] = getagentinfo($pid['pid'], $db_config);
        }elseif ($get['atype']=='user'){
            $v['agentid'] = $v['uid'];
            $agentinfo[$v['uid']]['user'] = getuserinfo($v['agentid'], $db_config);
            $agentinfo[$v['uid']]['user'][0]['pay_name']="线上注册会员";
            $pid['pid'] =$v['uid'];
        }

        $agentinfo[$pid['pid']]['a_t'] = $a_t;
        $agentinfo[$pid['pid']]['u_a'] = $u_a;
        $agentinfo[$pid['pid']]['s_h'] = $s_h;
        foreach ($agentinfo[$pid['pid']]['a_t'] as $k3=>$v3){
          $agentinfo[$pid['pid']]['agent_sh']['s_hdljs']+=(1-$v3['lottery_scale'])* ($v['bet_money_YX'] - $v['payout']);
          $agentinfo[$pid['pid']]['agent_sh']['s_hdljg'] += $v3['lottery_scale'] * ($v['bet_money_YX'] - $v['payout']);
          $agentinfo['total_s_hdljs']+=(1-$v3['lottery_scale'])* ($v['bet_money_YX'] - $v['payout']);
          $agentinfo['total_s_hdljg']+=$v3['lottery_scale'] * ($v['bet_money_YX'] - $v['payout']);
        }
        foreach ($agentinfo[$pid['pid']]['u_a'] as $k3=>$v3){
            $agentinfo[$pid['pid']]['agent_sh']['s_hzdjs']+=(1-$v3['lottery_scale'])* ($v['bet_money_YX'] - $v['payout']);
            $agentinfo['total_s_hzdjs']+=(1-$v3['lottery_scale'])* ($v['bet_money_YX'] - $v['payout']);
        }
        foreach ($agentinfo[$pid['pid']]['s_h'] as $k3=>$v3){
            $agentinfo[$pid['pid']]['agent_sh']['s_hgdjs']+=(1-$v3['lottery_scale'])* ($v['bet_money_YX'] - $v['payout']);
            $agentinfo['total_s_hgdjs']+=(1-$v3['lottery_scale'])* ($v['bet_money_YX'] - $v['payout']);
        }
        $agentinfo[$pid['pid']]['agent_sh']['count'] += $v['count'];
        $agentinfo[$pid['pid']]['agent_sh']['bet_money'] += $v['bet_money'];
        $agentinfo[$pid['pid']]['agent_sh']['bet_money_YX'] += $v['bet_money_YX'];
        $agentinfo[$pid['pid']]['agent_sh']['payout'] += $v['payout'];
        $agentinfo['total_count'] += $v['count'];
        $agentinfo['total_bet_money'] += $v['bet_money'];
        $agentinfo['total_bet_money_YX'] += $v['bet_money_YX'];
        $agentinfo['total_payout'] += $v['payout'];
    }
    return $agentinfo;
}

// function getpid($agent_id,$db_config){
// $s_h = M('k_user_agent',$db_config)
// ->field('pid,')
// ->where("id=$agent_id")
// ->find();
// return $s_h['pid'];
// }
function getagentinfo($agent_id, $db_config)
{
    $s_h = M('k_user_agent', $db_config)->field('id,agent_user,agent_name,video_scale,sports_scale,lottery_scale,agent_type,pid')
        ->where("id=$agent_id")
        ->select();
    return $s_h;
}

function getuserinfo($username,$db_config){
    $s_h = M('k_user', $db_config)->field('uid,username,pay_name,agent_id')
    ->where("username='$username' or uid='$username'")
    ->select();
    return $s_h;
}
function gameinfo($game){
    switch ($game) {
        case 'mgdz':
            $gameinfo['name'] = 'mg';
            $gameinfo['type'] = '1';
            break;
        case 'bbsx':
            $gameinfo['name'] = 'bbin';
            $gameinfo['type'] = '3';
            break;
        case 'bbdz':
            $gameinfo['name'] = 'bbin';
            $gameinfo['type'] = '5';
            break;
        case 'bbty':
            $gameinfo['name'] = 'bbin';
            $gameinfo['type'] = '1';
            break;
        case 'bbcp':
            $gameinfo['name'] = 'bbin';
            $gameinfo['type'] = '12';
            break;
        default:
            $gameinfo['name'] = $game;
            $gameinfo['type'] = 0;
            break;
    }
    return $gameinfo;
}
function getshixun($game, $agentid, $db_config, $get)
{
   $gameinfo = gameinfo($game);
    $games = new Games();
    $agentid = str_replace(',', '|', $agentid);
    if($get['atype']=='user'){
        $shixunBet = $games->GetUserAvailableAmountByAgentid($gameinfo['name'], $agentid, $get['date_start'], $get['date_end'], $gameinfo['type']);
    }else{
        $shixunBet = $games->GetAgentAvailableAmountByAgentid($gameinfo['name'], $agentid, $get['date_start'], $get['date_end'], $gameinfo['type']);
    }
    if ($shixunBet) {
        $shixunBet = json_decode($shixunBet);
        foreach ($shixunBet->data->data as $k => $v) {
            $a_t = getagentinfo($v->agentid, $db_config);
            $u_a = getagentinfo($a_t[0]['pid'], $db_config);
            $s_h  = getagentinfo($u_a[0]['pid'], $db_config);
            if ($get['atype'] == 'a_t') {
                $pid['pid'] = $a_t[0]['id'];
                $agentinfo[$pid['pid']]['user'] = getagentinfo($pid['pid'], $db_config);
            } elseif ($get['atype'] == 'u_a') {
                $pid['pid'] =$u_a[0]['id'];
                $agentinfo[$pid['pid']]['user'] = getagentinfo($pid['pid'], $db_config);
            } elseif ($get['atype'] == 's_h') {
                $pid['pid'] =$s_h[0]['id'];
                $agentinfo[$pid['pid']]['user'] = getagentinfo($pid['pid'], $db_config);
            }elseif ($get['atype']=='user'){
                $v->agentid = $v->username;
                $agentinfo[$v->agentid]['user'] = getuserinfo($v->agentid, $db_config);
                $agentinfo[$v->agentid]['user']['pay_name']="线上注册会员";
                $pid['pid'] =$v->agentid;
            }
            $agentinfo[$pid['pid']]['a_t'] = $a_t;
            $agentinfo[$pid['pid']]['u_a'] = $u_a;
            $agentinfo[$pid['pid']]['s_h'] = $s_h;



            foreach ($agentinfo[$pid['pid']]['a_t'] as $k3=>$v3){
                $agentinfo[$pid['pid']]['agent_sh']['s_hdljs']+=(1-$v3['lottery_scale'])* (0-$v->BetPC);
                $agentinfo[$pid['pid']]['agent_sh']['s_hdljg'] += $v3['lottery_scale'] * (0-$v->BetPC);
                $agentinfo['total_s_hdljs']+=(1-$v3['lottery_scale'])* (0-$v->BetPC);
                $agentinfo['total_s_hdljg']+=$v3['lottery_scale'] * (0-$v->BetPC);
            }
            foreach ($agentinfo[$pid['pid']]['u_a'] as $k3=>$v3){
                $agentinfo[$pid['pid']]['agent_sh']['s_hzdjs']+=(1-$v3['lottery_scale'])* (0-$v->BetPC);
                $agentinfo['total_s_hzdjs']+=(1-$v3['lottery_scale'])* (0-$v->BetPC);
            }
            foreach ($agentinfo[$pid['pid']]['s_h'] as $k3=>$v3){
                $agentinfo[$pid['pid']]['agent_sh']['s_hgdjs']+=(1-$v3['lottery_scale'])* (0-$v->BetPC);
                $agentinfo['total_s_hgdjs']+=(1-$v3['lottery_scale'])* (0-$v->BetPC);
            }

            $agentinfo[$pid['pid']]['agent_sh']['num'] += $v->BetBS;
            $agentinfo[$pid['pid']]['agent_sh']['BetAll'] += $v->BetAll;
            $agentinfo[$pid['pid']]['agent_sh']['BetYC'] += $v->BetYC;
            $agentinfo[$pid['pid']]['agent_sh']['BetPC'] += $v->BetPC;
            $agentinfo['total_count'] += $v->BetBS;
            $agentinfo['total_BetAll'] += $v->BetAll;
            $agentinfo['total_BetYC'] += $v->BetYC;
            $agentinfo['total_BetPC'] += $v->BetPC;
        }
        return $agentinfo;
    }
}

function getshixunname($game)
{
    switch ($game) {
        case 'zq':
            $name = "體育";
            break;
        case 'cp':
            $name = "彩票";
            break;
        case 'mg':
            $name = "MG视讯";
            break;
        case 'mgdz':
            $name = "MG机率";
            break;
        case 'bbsx':
            $name = "BBIN视讯";
            break;
        case 'bbdz':
            $name = "BBIN电子";
            break;
        case 'bbty':
            $name = "BBIN体育";
            break;
        case 'bbty':
            $name = "BBIN球类";
            break;
        case 'bbcp':
            $name = "BBIN彩票";
            break;
        case 'ag':
            $name = "AG视讯";
            break;
        case 'og':
            $name = "OG视讯";
            break;
        case 'lebo':
            $name = "LEBO视讯";
            break;
        case 'ct':
            $name = "CT视讯";
            break;
        default:
             $name = "總報表";
            break;
    }
    return $name;
}

?>
<?php $title="报表明细"; require("../common_html/header.php");?>
<body>
	<style type="text/css">
table.m_tab th span {
	margin-right: 8px;
}
</style>
	<script type="text/javascript">
$(document).ready(function(){
	  $("td").each(function () {
          if (parseFloat($(this).text())<0) {
        	  $(this).css("color","red");
          }
      })
	});

$(document).ready(function(){
	$("#m_tab1").tablesorter({
		sortList:false,
		headers:{	//有特别指定按digit的列是可以保证正负数值正确排序，改动时请注意列的对应
			4:{
				sorter:'digit'
			},
			5:{
				sorter:'digit'
			},
			6:{
				sorter:'digit'
			},
			7:{
				sorter:'digit'
			},
			8:{
				sorter:'digit'
			},
			9:{
				sorter:'digit'
			},
			10:{
				sorter:'digit'
			},
			11:{
				sorter:'digit'
			},
			12:{
				sorter:'digit'
			},
			13:{
				sorter:'digit'
			}
		}
	});
	$("#m_tab2").tablesorter({
		sortList:false,
		headers:{	//有特别指定按digit的列数是可以保证正负数值正确排序，links是针对正则的排序，改动时请注意列的对应
			2:{
				sorter:'links'
			},
			4:{
				sorter:'digit'
			},
			5:{
				sorter:'digit'
			},
			6:{
				sorter:'digit'
			},
			7:{
				sorter:'digit'
			},
			8:{
				sorter:'digit'
			},
			9:{
				sorter:'digit'
			},
			10:{
				sorter:'digit'
			},
			11:{
				sorter:'digit'
			},
			12:{
				sorter:'digit'
			},
			13:{
				sorter:'digit'
			}
		}
	});
	$("#m_tab3").tablesorter({
		sortList:false,
		headers:{	//有特别指定按digit的列数是可以保证正负数值正确排序，links是针对正则的排序，改动时请注意列的对应
			2:{
				sorter:'links'
			},
			4:{
				sorter:'digit'
			},
			5:{
				sorter:'digit'
			},
			6:{
				sorter:'digit'
			},
			7:{
				sorter:'digit'
			},
			8:{
				sorter:'digit'
			},
			9:{
				sorter:'digit'
			},
			10:{
				sorter:'digit'
			},
			11:{
				sorter:'digit'
			},
			12:{
				sorter:'digit'
			},
			13:{
				sorter:'digit'
			}
		}
	});
	$("#m_tab4").tablesorter({
		sortList:false,
		headers:{
			2:{
				sorter:'links'
			},
			4:{
				sorter:'links'
			},
			5:{
				sorter:'digit'
			},
			6:{
				sorter:'digit'
			},
			7:{
				sorter:'digit'
			},
			8:{
				sorter:'digit'
			},
			9:{
				sorter:'digit'
			},
			10:{
				sorter:'digit'
			},
			11:{
				sorter:'digit'
			},
			12:{
				sorter:'digit'
			},
			13:{
				sorter:'digit'
			}
		}
	});
	//$("th[id^=target]").css("background-color","#527A98");
} );
$.tablesorter.addParser({
    // set a unique id
    id: 'links',
    is: function(s)
    {
        // return false so this parser is not auto detected
        return false;
    },
    format: function(s)
    {
        // format your data for normalization
        return s.replace(new RegExp(/<.*?>/),"");
    },
    // set type, either numeric or text
    type: 'numeric'
});
</script>
	<div id="con_wrap">
		<div class="input_002"><?=getr_title($db_config);?> - 報表查詢</div>
		<div class="con_menu">
			<a href="javascript:history.go(-1);">返回上一頁</a>
		</div>
	</div>


<?php foreach ($list as $k=>$v){?>
<div class="content" id="content_report">
		<table border="0" cellpadding="0" cellspacing="0" id="m_tab1"
			class="m_tab tablesorter">
			<thead>
				<tr class="m_title">
					<td colspan="14"
						style="text-align: left; background: #FFFFFF; color: #333131">
						<div style="float: left"><?=getshixunname($k); ?> 日期：<?=$date_start?>~ <?=$date_end?> 投注類型：全部 投注方式：全部</div>
						<div style="float: right">
							"<font color="#ff0000">紅</font>"色代表业主亏损，"<font color="#000">黑</font>"色代表业主盈利
						</div>
					</td>
				</tr>
				<tr class="m_title">
					<th width="150" rowspan="2" nowrap="nowrap"
						style="font-size: 14px;" class="header"><span><?=$table_title?>名稱</span></th>
					<th width="80" rowspan="2" class="header" style="font-size: 14px;"><span>總筆數</span></th>
					<th id="target1" width="80" rowspan="2" style="font-size: 12px;"
						class="header"><span>總下注金額</span></th>
					<th width="80" rowspan="2" style="font-size: 12px;" class="header"><span>總有效金額</span></th>
					<th width="80" rowspan="2" style="font-size: 12px;" class="header"><span>總派彩</span></th>
					<th width="80" rowspan="2" style="font-size: 12px;" class="header"><span><?=$u_table_title?>占成</span></th>
					<th width="80" rowspan="2" style="font-size: 12px;" class="header"><span><?=$u_table_title?>退水</span></th>
					<th width="80" rowspan="2" style="font-size: 12px;" class="header"><span><?=$u_table_title?>損益</span></th>
					<td colspan="<?=count($level_a)?>"
						style="text-align: center; font-size: 12px; background-color: #fecee4; color: #122858"><span>總各層交收</span></td>
				</tr>
				<tr class="m_title">
	    <?php

    foreach ($level_a as $key => $val) {
        ?>
          <th class="header"><span><?=$val['name']?></span></th>
        <?php }?>
        <th style="display: none" class="header"><span>後台</span></th>
				</tr>
			</thead>
			<tbody>

   <?php ?>
   <?php foreach ($v as $k1=>$val){?>
	<tr class="m_rig" align="left">

	<?if(is_array($val)){?>
	<td align="center"><?=$val['user'][0]['agent_user']?> <?=$val['user'][0]['username']?>(<?=$val['user'][0]['agent_name']?><?=$val['user'][0]['pay_name']?>)</td>
	<?if($k=='cp'||$k=='zq'||$k=='total'){?>

					<td><?=$val['agent_sh']['count']+0?></td>
					<td align="right"><?php if($k=='total'){ ?><?=number_format($val['agent_sh']['bet_money']+0,4)?><?php }else{ ?><a href="<?=get_report_url($k1,$k,$atype,$db_config)?>" class="a_001"><?=number_format($val['agent_sh']['bet_money']+0,4)?></a><?php } ?></td>
					<td><?=number_format($val['agent_sh']['bet_money_YX']+0,4)?></td>
					<td><?=number_format($val['agent_sh']['payout']+0,4)?></td>
					<td><?=number_format($val['agent_sh']['bet_money_YX']-$val['agent_sh']['payout']+0,4)?></td>
					<td>0.00</td>
					<td><?=number_format($val['agent_sh']['bet_money_YX']-$val['agent_sh']['payout']+0,4)?></td>
					<td><?=number_format($val['agent_sh']['bet_money_YX']-$val['agent_sh']['payout']+0,4)?></td>





					<td><?=number_format($val['agent_sh']['s_hdljs'],4)?></td>
					<td><?=number_format($val['agent_sh']['s_hdljg'],4)?></td>
					<td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($val['agent_sh']['s_hzdjs'],4)?></td>
					<td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($val['agent_sh']['s_hgdjs'],4)?></td>
					<td <?php if($_GET['atype']=='u_a'||$_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>


					<?php }else{ ?>

					<td><?=$val['agent_sh']['num']+0?></td>
					<td><?php if($k=='total'){ ?><?=number_format($val['agent_sh']['BetAll']+0,4)?><?php }else{ ?><a href="<?=get_report_url($k1,$k,$atype,$db_config)?>" class="a_001"><?=number_format($val['agent_sh']['BetAll']+0,4)?></a><?php } ?></td>
					<td><?=number_format($val['agent_sh']['BetYC']+0,4)?></td>
					<td><?=number_format($val['agent_sh']['BetPC'] + $val['agent_sh']['BetYC'],4)?></td>

					<td><?=number_format(0-$val['agent_sh']['BetPC'],4)?></td>
					<td>0.00</td>
					<td><?=number_format(0-$val['agent_sh']['BetPC'],4)?></td>
					<td><?=number_format(0-$val['agent_sh']['BetPC'],4)?></td>


					<td><?=number_format($val['agent_sh']['s_hdljs'],4)?></td>
					<td><?=number_format($val['agent_sh']['s_hdljg'],4)?></td>
					<td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($val['agent_sh']['s_hzdjs'],4)?></td>
					<td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($val['agent_sh']['s_hgdjs'],4)?></td>
					<td <?php if($_GET['atype']=='u_a'||$_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
					<?php } ?>
				</tr>
   <?php
        }
    }
    ?>

	</tbody>
			<tfoot>

	<?php if($v['total_count'] > 0){?>
				<?if($k=='cp'||$k=='zq'||$k=='total'){?>
	<tr class="m_rig">
					<td align="center">總計</td>
					<td><?=$v['total_count']?></td>
					<td><?=number_format($v['total_bet_money'],4)?></td>
					<td><?=number_format($v['total_bet_money_YX'],4)?></td>
					<td><?=number_format($v['total_payout'],4)?></td>
					<td><?=number_format($v['total_bet_money_YX']-$v['total_payout'],4)?></td>
					<td>0.00</td>
					<td><?=number_format($v['total_bet_money_YX']-$v['total_payout'],4)?></td>
					<td><?=number_format($v['total_bet_money_YX']-$v['total_payout'],4)?></td>

                    <td><?=number_format($v['total_s_hdljs'],4)?></td>
					<td><?=number_format($v['total_s_hdljg'],4)?></td>
					<td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($v['total_s_hzdjs'],4)?></td>
					<td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($v['total_s_hgdjs'],4)?></td>
					<td <?php if($_GET['atype']=='u_a'||$_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
				</tr>

	<?php }else{?>
		       <tr class="m_rig">
					<td align="center">總計</td>
					<td><?=$v['total_count']?></td>
					<td><?=number_format($v['total_BetAll'],4)?></td>
					<td><?=number_format($v['total_BetYC'],4)?></td>
					<td><?=number_format($v['total_BetPC'] + $v['total_BetYC'],4)?></td>
					<td><?=number_format(0-$v['total_BetPC'],4)?></td>
					<td>0.00</td>
					<td><?=number_format(0-$v['total_BetPC'],4)?></td>
					<td><?=number_format(0-$v['total_BetPC'],4)?></td>
					 <td><?=number_format($v['total_s_hdljs'],4)?></td>
					<td><?=number_format($v['total_s_hdljg'],4)?></td>
					<td <?php if($_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($v['total_s_hzdjs'],4)?></td>
					<td <?php if($_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>><?=number_format($v['total_s_hgdjs'],4)?></td>
					<td <?php if($_GET['atype']=='u_a'||$_GET['atype']=='a_t'||$_GET['atype']=='user'){echo 'style="display:none"';}?>>-</td>
				</tr>

	 <?php }?>
	 	<?php }else{?>
	 <tr class="m_rig">
					<td colspan="14" align="center">暂无数据</td>
				</tr>
	 <?php }?>
	</tfoot>
		</table>
		<br>
	</div>
<?php } ?>
<?php require("../common_html/footer.php"); ?>