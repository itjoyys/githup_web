<?
// ini_set("display_errors", "On");
// error_reporting(E_ALL);
 set_time_limit(0);

include_once("../../include/config.php");
include_once("../common/login_check.php");
include("../../class/agentCenter.class.php");
include("../../lib/video/Games.class.php");

$agent = M('k_user_agent',$db_config);
//默认显示当天时间


if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $map_n['reg_date'] = array(
        array('>=',$_GET['start_date'].' 00:00:00'),
        array('<=',$_GET['end_date'].' 23:59:59')
        );
}else{
     $start_date = $end_date = date('Y-m-d');
     $map_n['reg_date'] = array(
        array('>=',$start_date.' 00:00:00'),
        array('<=',$end_date.' 23:59:59')
                               );
}


//代理账号检索
if(!empty($_GET['username'])){
   $map_s['agent_user'] = $_GET['username'];
}

 //站点代理商读取
$map_s['site_id'] = SITEID;
$map_s['is_demo'] = 0;//非测试代理
$map_s['agent_type'] = 'a_t';//代理类别
$count=$agent->field("id")->where($map_s)->count();

//分页
$perNumber=isset($_GET['page_num'])?$_GET['page_num']:50; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;

$user = $agent->field("id,agent_user,agent_name,agent_login_user")->where($map_s)->limit($limit)->select();

//$user_c = $agent ->where($map_s)->select();

$CountYX;
$NewC;

$dateY = array($start_date,$end_date);
$User = M('k_user',$db_config);
$games = new Games();

foreach ($user as $key => $val) {
   //代理商旗下新增会员数量

      $map_n['agent_id'] = $val['id'];
      $ucc = $User->where($map_n)->count();
      $user[$key]['newNum'] = $ucc;
      if (empty($ucc)) {
          $user[$key]['newYx'] = 0;
      }else{
          //彩票体育
          $FcSp_YX = array();
          $FcSp_YX = agentCenter::agentUserYX($val['id'],$dateY);
               //视讯
          $s_time = $start_date." 00:00:00";
          $e_time = $end_date." 23:59:59";
          $Vdata = $games->GetAllUserAvailableAmountByAgentid($val['id'], $s_time, $e_time);
          $Vdata = json_decode($Vdata);
          $tmpd = $Vdata->data->data;
          $video_uid_YX = array();
          foreach ($tmpd as $k => $v) {
             $video_uid_YX[$v->username] = $v->username;
          }
          ////////////////////////////////////Game
          $VdataG = $games->GetAllUserAvailableAmountByAgentid($val['id'], $s_time, $e_time,1);
          $VdataG = json_decode($VdataG);
          $tmpdG = $VdataG->data->data;
          $Game_uid_YX = array();
          foreach ($tmpdG as $k => $v) {
             $Game_uid_YX[$v->username] = $v->username;
          }
         
          $userYxAll = $FcSp_YX + $video_uid_YX + $Game_uid_YX;

          $user[$key]['newYx'] = count($userYxAll);
         }
}
$page = $agent->showPage($totalPage,$page);
$title="有效會員列表"; require("../common_html/header.php");?>


<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit();
    }
  }
</script>
<style>
  .fenye{width: 100%;text-align: center;}
  .fenye a{color: gray;}
</style>
<body>
<div id="con_wrap">
  <div class="input_002">有效會員列表</div>
  <div class="con_menu">
    <form method="get" name="action_form" action="" id="myFORM">
    <input type="hidden" name="find" value="f">
    	<input onclick="document.location='active_member_search.php'" class="button_d" value="會員查詢" type="button">
      日期：
      <input class="za_text Wdate" onClick="WdatePicker()" value="<?=$start_date?>" name="start_date">
      ~
      <input class="za_text Wdate" onClick="WdatePicker()" value="<?=$end_date?>" name="end_date">
      代理商帳號：
      <input class="za_text inpuut1" style="min-width:80px;width:80px" name="username" value="<?=$_GET['username']?>" type="text">
      <!--有效人數大於：
      <input  class="za_text inpuut1" style="min-width:50px;width:50px" name="runshu" value="0">-->
      <input class="button_d" value="查詢" type="submit">
 <?=$page?>
    </form>
    
  </div>
</div>
<table style="width:400px" class="m_tab">
  <tbody><tr class="m_title" style="height: 27px;">
    <td class="table_bg">排序</td>
    <td class="table_bg">帳號</td>
    <td class="table_bg">登入帳號</td>
    <td class="table_bg">有效人數</td>
  	<td class="table_bg">新增人數</td>
  </tr>

  <?php if(!empty($user)){
    foreach($user as $key=> $v){
      $all_new_num += $v['newNum'];
  ?>
    <tr class="m_cen">
    <td><?=$key+1; ?></td>
    <td style="text-align: left;">
    	<?=$v['agent_user']?>(<?=$v['agent_name']?>)
      </td>
     <td>
    	<?=$v['agent_login_user']?>
      </td> 
    <td><?php 
    if($v['newYx']){ ?>
    <a href="./agent_report.php?agent_id=<?=$v['id']?>&date_start=<?=$start_date?>&date_end=<?=$end_date?>&cp=2&sp=1"><?=$v['newYx'] ?></a>
   <?php $all_num += $v['newYx'];}else{
      echo "0";
    }  
    ?></td>
	<td>
    <?=$v['newNum']+0?>
  </td>

  </tr>
  <?
  }}?>
   
    <tr class="m_title">
    <td class="table_bg"></td>
    <td class="table_bg" colspan="2" align="right">小計人數</td>
    <td class="table_bg"><?=$all_num+0 ;?></td>
	<td class="table_bg"><?=$all_new_num+0 ;?></td>
  </tr>
<!--   <tr class="m_title">
    <td class="table_bg"></td>
    <td class="table_bg" colspan="2" align="right">總計人數</td>
    <td class="table_bg"><?=$CountYX+0;?></td>
	  <td class="table_bg"><?=$NewC+0?></td>
  </tr> -->
</tbody></table>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>