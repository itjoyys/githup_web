<?php //ini_set("display_errors","on");
ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(0);

include_once("../../include/config.php");
include_once("../common/login_check.php");

include("../../class/Level.class.php");
include("../../class/GetCpName.php");
$_DBC['private']['pass']=$_DBC['private']['pwd'];
$_DBC['public']['pass']=$_DBC['public']['pwd'];
$CpType= new CaiPaioType();

/*$d= $CpType->Cp;
print_r($d);
exit;*/
$sql = 1;
$cp_name=$_REQUEST['cp_name'];
$money=intval($_REQUEST['money']);
$username=$_REQUEST['username'];
$status=$_REQUEST['status'];
$statustype=$_REQUEST['statustype'];
$date_start=$_REQUEST['date_start'];
$date_end=$_REQUEST['date_end'];
$action=$_REQUEST['action'];
$cp_type=$_REQUEST['cp_type'];
$Order=$_REQUEST['Order'];
$p=intval($_REQUEST['p']);
$LastId=$_REQUEST['LastId'];
$qs=$_REQUEST['qs'];
$m1=$_REQUEST['m1'];
$m2=$_REQUEST['m2'];
$m3=$_REQUEST['m3'];
$p=intval($_REQUEST['p']);
$nums=intval($_REQUEST['nums']);
if($action=='getlist'){

    $where=" site_id='".SITEID."' ";
    //$testagent = M('k_user_agent', $db_config)->where("agent_type = 'a_t' and is_demo = 1 and site_id = '".SITEID."'")->getField('id');
    $where.="  and agent_id='".$_SESSION['agent_id']."'";
    $wherewin='';
    if($cp_type){
        $CpIdName= $CpType->GetId($cp_type);
        $CpName=$CpIdName['CpName'];
        $where.=" and `type`='$CpName'";
    }
    if($username) $where.=" and username ='$username'";
    if($date_start && $date_end && empty($Order)) $where.=" and addtime between '$date_start 00:00:00' and '$date_end 23:59:59'";
    if($Order) $where.=" and did='$Order'";
    if($status==0) {
        $where.=" and status=0";
        $wherewin=" and js in(1)";
    }
    elseif($status==1) {
        if($statustype==1) $where.=" and status in(1)";
        elseif($statustype==2) $where.=" and status in(2)";
        elseif($statustype==3) $where.=" and status in(3)";
        else $where.=" and status in(1,2,3)";
    }
    elseif($status==2) $where.="and status=4 and js=0";
    else {
        $wherewin=" and js=1";
    }
    if($money>0) $where.=" and money>=$money";
    if($nums<=0)$nums=20;
    if($p<=0) $p=1;
    $p_=($p-1)*$nums;
    //if($LastId) $where.=" and id<$LastId";
    $limit="$p_,$nums";
    //  $limit="0,$nums";
//echo $where;
    $allagent=M('k_user_agent',$_DBC['private'])->where("is_delete=0 and site_id='".SITEID."'")->select();
    $DataList=M("c_bet",$_DBC['private'])->where($where)->order('id desc')->limit($limit)->select();
    $Data['num'] =M("c_bet",$_DBC['private'])->where($where)->order('addtime desc')->count();
    if($status==-1 || $status==0){
        $SumMoney =M("c_bet",$_DBC['private'])->field("sum(`money`) as AllMoney")->where($where)->select();
        // echo $where.$wherewin;
        $SumWin   =M("c_bet",$_DBC['private'])->field("sum(`win`) as AllWin")->where($where.$wherewin)->select();
        $Data['AllMoney'] =round($SumMoney[0]['AllMoney'],2);
        $Data['AllWin'] =round($SumWin[0]['AllWin'],2);
    }
    else{
        $SumMoney =M("c_bet",$_DBC['private'])->field("sum(`money`) as AllMoney ,sum(`win`) as AllWin")->where($where)->select();
        $Data['AllMoney'] =round($SumMoney[0]['AllMoney'],2);
        $Data['AllWin'] =round($SumMoney[0]['AllWin'],2);
    }


    if(is_array($DataList)){
        //查询所有代理，总代，股东信息并整合为一个数组
        foreach($DataList as $k=>$v){
            $userdata=M('k_user',$_DBC['private'])->field('agent_id,username,money')->where("uid='".$v['uid']."'")->select();
            $arr=Level::getParents($allagent,$userdata[0]['agent_id']);
            //print_r($arr);
            $Data['data'][$k]=array_merge($v,$arr);
            if($v['js']==0) $Data['data'][$k]['win']=0;
        }
    }

    $page=ceil($Data['num']/$nums);
    for($i=1;$i<=$page;$i++){
        if($p==$i)$Data['page'].="<option selected>$i</option>";
        else $Data['page'].="<option>$i</option>";
    }
    exit(json_encode($Data));
}

