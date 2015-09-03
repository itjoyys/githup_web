<?php
include_once("../../include/config.php");
include_once("../common/login_check.php"); 

$map_cp = " site_id = '".SITEID."' ";
//时间检索
if(!empty($_GET['date_start']) && !empty($_GET['end_date'])){
  $start_date = $_GET['date_start'];
  $end_date = $_GET['end_date'];
	
}else{
  $start_date = $end_date = date('Y-m-d');
}
$tmp_sdate = $start_date.' 00:00:00';
$tmp_edate = $end_date.' 23:59:59';
$map_cp .= " and login_time >= '".$tmp_sdate."' and login_time <= '".$tmp_edate."' ";


//账号检索
if(!empty($_GET['username'])){
	$user='%'.$_GET['username'].'%';
	$map_cp .= " and username like '".$user."' ";
}
//ip检索
if(!empty($_GET['ip'])){
  $ip_str = '%'.$_GET['ip'].'%';
  $map_cp .= " and ip like '".$ip_str."' ";
}
//登录状态
if(!empty($_GET['state'])){
   if ($_GET['state'] == '2') {
      $map_cp .= " and state = '0' ";
   }else{
      $map_cp .= " and state = '1' ";
   }
}

$u = M('sys_admin_login',$db_config);
//总数
$count = $u->where($map_cp)->count();


$perNumber=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
if($totalPage<$page){
  $page = 1;
}

$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;

$user_login=$u->where($map_cp)->order("id DESC")
          ->limit($limit)-> select();

$page = $u->showPage($totalPage,$page);

?>
<?php $title="管理员登陆日志"; require("../common_html/header.php");?>
<body>
<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myform').submit()
    }
  }
</script>

 <script language="javascript">

// //下拉框选中
// $(document).ready(function(){
//    $("#username").val('<?=$_GET['username']?>');
//    $("#date_start").val('<?=$_GET['date_start']?>');
//    $("#date_end").val('<?=$_GET['date_end']?>');
//     $("#ip").val('<?=$_GET['ip']?>');
// })
// </script>
<div id="con_wrap">
  <div class="input_002">管理登錄日志</div>
  <div class="con_menu">
    <form method="get" name="action_form" id="myform">
      <a href="member_login_log.php">会员登陆</a>
      <a href="sys_login_log.php" style="color: rgb(255, 0, 0);">管理登陆</a>
      日期：
      <input class="za_text Wdate" style="min-width:80px;width:80px;" value="<?=$start_date?>" id="date_start" name="date_start" onClick="WdatePicker()">
      ~
      <input class="za_text Wdate" style="min-width:80px;width:80px;" value="<?=$end_date?>" id="date_end" onClick="WdatePicker()" name="end_date">
      帳號：
      <input class="za_text" style="width:80px;min-width:80px;" id="username" name="username" value="<?=$_GET['username']?>">
   IP：
      <input class="za_text" style="width:80px;min-width:100px;" name="ip" id="ip" value="<?=$_GET['ip']?>">      
      <input class="button_d" value="查詢" type="submit">
   
	 登录是否成功：
	<select name="state" id="is_success" class="za_select" onchange="document.getElementById('myform').submit()">
		    <option value="0" <?=select_check(0,$_GET['state'])?>>全部</option>
        <option value="1" <?=select_check(1,$_GET['state'])?>>成功</option>
        <option value="2" <?=select_check(2,$_GET['state'])?>>失败</option>
	</select>
              每页记录数：
  <select name="page_num" id="page_num" onchange="document.getElementById('myform').submit()" class="za_select">
    <option value="20" <?=select_check(20,$_GET['page_num'])?>>20条</option>
    <option value="30" <?=select_check(30,$_GET['page_num'])?>>30条</option>
    <option value="50" <?=select_check(50,$_GET['page_num'])?>>50条</option>
    <option value="100" <?=select_check(100,$_GET['page_num'])?>>100条</option>
  </select>
  &nbsp;<?=$page?>
    </form>
  </div>
</div>
<table width="99%" class="m_tab">
  <tbody><tr class="m_title">
   <td class="table_bg">www</td>
    <td class="table_bg">帐号</td>
    <td class="table_bg">讯息</td>
    <td class="table_bg">登入时间</td>
  
    <td class="table_bg">IP位置</td>
  </tr>
  
  <?php if(!empty($user_login)){
    foreach($user_login as $row){?>
	<tr class="m_cen">
		<td><?=$row['www']?></td>
		<td><?=$row['username']?></td>
		<td><?=loginState($row['state'],$row['www'])?></td>
		<td><?=$row['login_time']?></td>
    
		<td><?=$row['ip'].'('.$row['ip_address'].')';?></td>
	  </tr>
  <?}} ?>
    </tbody></table>
    <?php 
	if(empty($user_login)){
?>
	<div style="float:left;text-align:center;width:100%; margin:10px 0 0 0; font-size:13px;">暂无消息</div>	
<?php } ?>
<?php 
function loginState($state,$url){
   switch ($state) {
     case '1':
       return '登入成功('.$url.')';
       break;
     
     default:
        return '登入失败('.$url.')';
       break;
   }
}

?>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>