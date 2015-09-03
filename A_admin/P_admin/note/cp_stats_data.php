<?
/*
 广东快乐十分   1
重庆时时彩      2
北京PK拾         3
重庆快乐十分     4
福彩3D           5
排列三            6
六合彩            7
北京快乐8        8*/
header("Content-type: text/html; charset=utf-8");
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once('../../include/public_config.php');
include_once("../../lib/class/model.class.php");
include('../../include/function_cpqs.php');
$_DBC['private']['pass']=$_DBC['private']['pwd'];
$_DBC['public']['pass']=$_DBC['public']['pwd'];
function retun_sort($i){
    switch ($i){
        case '1';
            $str= '一';
            break;
        case '2';
            $str=  '二';
            break;
        case '3';
            $str=  '三';
            break;
        case '4';
            $str=  '四';
            break;
        case '5';
            $str=  '五';
            break;
        case '6';
            $str=  '六';
            break;
        case '7';
            $str=  '七';
            break;
        case '8';
            $str=  '八';
            break;
        case '9';
            $str=  '九';
            break;
        case '10';
            $str=  '十';
            break;

    }
    return $str;
}
$cp_name=$_REQUEST['cp_name'];
$action=$_REQUEST['action'];
$cp_type=$_REQUEST['cp_type'];
$qs=$_REQUEST['qs'];
$m1=$_REQUEST['m1'];
$m2=$_REQUEST['m2'];
$m3=$_REQUEST['m3'];$p=intval($_REQUEST['p']);
if($action=='getlist'){
    $where=" and site_id='".SITEID."' ";
    $agent_demo = M('k_user_agent', $_DBC['private'])->where("agent_type = 'a_t' and is_demo = 1 and site_id = '".SITEID."'")->getField('id');
    $where.=" and agent_id!=$agent_demo";
    if($m1) $where.=" and mingxi_1='$m1'";
    if(isset($m2)) $where.=" and mingxi_2='$m2'";
    if($m3 && ($cp_name=='北京赛车PK拾' || $cp_name=='六合彩')) $where.=" and mingxi_3='$m3'";

    // elseif($m2)
    $nums=20;
    if($p<=0) $p=1;
    $p_=($p-1)*$nums;
    $limit="$p_,$nums";
    $Data['data']=M("c_bet",$_DBC['private'])->where(" `type`='$cp_name' and qishu='$qs' $where")->order('addtime desc')->limit($limit)->select();
    $Data['num']=M("c_bet",$_DBC['private'])->where(" `type`='$cp_name' and qishu='$qs' $where")->order('addtime desc')->count();
    $page=ceil($Data['num']/$nums);
    for($i=1;$i<=$page;$i++){
        if($p==$i)$Data['page'].="<option selected>$i</option>";
        else $Data['page'].="<option>$i</option>";
    }
    exit(json_encode($Data));
}
if($action=='get_cp_qs'){
    $db_table='c_auto_';
    switch ($cp_type){
        case '1'://广东快乐十分
            $cp_name='广东快乐十分';
            $db_table.=$cp_type;
            break;
        case '2':
            $cp_name='重庆时时彩';
            $db_table.=$cp_type;
            break;
        case '10':
            $cp_name='天津时时彩';
            $db_table.=$cp_type;
            break;
        case '11':
            $cp_name='江西时时彩';
            $db_table.=$cp_type;
            break;
        case '12':
            $cp_name='新疆时时彩';
            $db_table.=$cp_type;
            break;
        case '3'://北京PK拾
            $cp_name='北京PK拾';
            $db_table.=$cp_type;
            break;
        case '4'://重庆快乐十分
            $cp_name='重庆快乐十分';
            $db_table.=$cp_type;
            break;
        case '5'://福彩3D
            $cp_name='福彩3D';
            $db_table.=$cp_type;
             break;
        case '6'://排列三
            $cp_name='排列三';
            $db_table.=$cp_type;
            break;
        case '7':
            $cp_name='六合彩';
            $db_table.=$cp_type;
            break;
        case '13':
            $cp_name='江苏快3';
            $db_table.=$cp_type;
            break;
        case '14':
            $cp_name='吉林快3';
            $db_table.=$cp_type;
            break;
        default://北京快乐8
            $cp_name='北京快乐8';
            $cp_type=8;
            $db_table.=$cp_type;
            break;
    }
     $select='' ;

    $d=M('c_bet',$_DBC['private'])->field("qishu")->where("`type`='$cp_name' and site_id='".SITEID."'")->group("qishu")->order('id desc')->limit('0,100')->select();
    if($d){
        foreach($d as $k=>$r){
            $r=$r['qishu'];
            $select.= "<option value='$r'>$r</option>";
        }
    }
    else  $select.= "1";
    echo $select;
    exit;
}


if($action=='cp_type_show' && $qs && $cp_type){
    $testagent = M('k_user_agent', $_DBC['private'])->where("agent_type = 'a_t' and is_demo = 1 and site_id = '".SITEID."'")->getField('id');

    if($cp_type==1 or $cp_type==4){
        include_once('cp_gdklsf.php');
    }elseif($cp_type==2 || $cp_type==10 || $cp_type==11 || $cp_type==12){//时时彩
        /*CQ频率：(10分钟) 10:00 - 22:00 频率：(5分钟)  22:00 - 01:55 全天：120期*/
        /*江西 84*/
        include_once('cp_ssc.php');//20150514 - 120
    }elseif($cp_type==3){
        include_once('cp_bjpks.php');
    }elseif($cp_type==8){
        include_once('cp_bjkl8.php');
    }elseif($cp_type==7){
        include_once('cp_lhc.php');//
    }elseif($cp_type==5){
        include_once('cp_fc3d.php');//+1
    }elseif($cp_type==6){
        include_once('cp_pl3.php');//+1
    }elseif($cp_type==13 || $cp_type==14){
        include_once('cp_kuai3.php');//+1
    }
}

?>


