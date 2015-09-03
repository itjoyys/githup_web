<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../../class/user.php");
include_once "../comm_menu.php";

// var_dump($_GET);离线
//会员总数
$todayReg =M('k_user',$db_config);
$map = 'site_id ='."'".SITEID."'".'and shiwan = 0 and reg_date like "'.date('Y-m-d').'%"';
$todayReg_count = $todayReg->field('uid')->where($map)->count();

//查询代理
$agent =M('k_user_agent',$db_config);
$con['site_id'] = SITEID;
$con['agent_type'] = 'a_t';
$con['is_demo'] = '0';
$agent_up = $agent->where($con)->order('id DESC')->select();
//在线会员获取
$redis_key = 'ulg'.CLUSTER_ID.'_'.$_SESSION['site_id'].'*';
$on_user = $redis->keys($redis_key);
if (!empty($on_user)) {
    foreach ($on_user as $key => $val) {
        $new_user_on[] = str_replace('ulg'.CLUSTER_ID.'_'.$_SESSION['site_id'],'',$val);
    }
    unset($on_user);
}else{
  $new_user_on = 0;
}



//会员相关处理

if (!empty($_GET['uid'])) {
  $username=M('k_user',$db_config)->field('username')->where('uid="'.$_GET[uid].'"')->find();
  switch ($_GET['action']) {
    case 'pause':
    //会员暂停
       $data['is_delete'] = 2;
       if($todayReg->where("uid = '".$_GET['uid']."'")->update($data)){
        $data1['is_login'] = 0;
        $stop = M("k_user_login",$db_config)->where("uid = '".$_GET['uid']."'")->update($data1);
        //if($stop){
          admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],"暂停了会员".$username['username']);
          message('操作成功','member_index.php');
        //}
       }

    break;
   case 'stop':
    //停用会员
       $data['is_delete'] = 1;
       if($todayReg->where("uid = '".$_GET['uid']."'")->update($data)){

          $data1['is_login'] = 0;
          $stop = M("k_user_login",$db_config)->where("uid = '".$_GET['uid']."'")->update($data1);
          //if($stop){
             admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],"停用了会员".$username['username']);
            message('操作成功','member_index.php');
          //}
       }

    break;
   case 'using':
    //启用会员
       $data['is_delete'] = 0;
       if($todayReg->where("uid = '".$_GET['uid']."'")->update($data)){
        admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],"启用了会员".$username['username']);
          message('操作成功','member_index.php');
       }

    break;
   case 'recovery':
    //恢复
       $data['is_delete'] = 0;
       if($todayReg->where("uid = '".$_GET['uid']."'")->update($data)){
        admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],"恢复了会员".$username['username']);
          message('操作成功','member_index.php');
       }

    break;
    case 'renew':
        //恢复
        require ("../video/getallbalance.php");
         message('操作成功','member_index.php');

        break;


}

}

$sql  = "select u.uid from k_user u left join k_user_agent ua on u.agent_id = ua.id where u.site_id='".SITEID."' and u.shiwan = 0";
//}

//查询当前选中代理
if(!empty($_GET["agent_id"])){
    $sql .=" and u.agent_id=".$_GET["agent_id"];
}


//启用代理
if(isset($_GET["mem_enable"]) && strlen($_GET["mem_enable"]) > 0){
	$sql .=" and u.is_delete=".$_GET["mem_enable"];
}

 //所属代理商链接
if($_GET["agent_user"] != ''){
  $sql.="  and ua.agent_user='".$_GET["agent_user"]."'";
}

//会员排序种类
if (isset($_GET['mem_sort'])) {
   switch($_GET['mem_sort']){
	  case 'username':
	  $desc =" order by u.".$_GET['mem_sort'];
	  break;
	  case 'reg_date':
	  $desc =" order by u.".$_GET['mem_sort'];
	  break;
	  case 'login_time':
	  $desc =" order by u.".$_GET['mem_sort'];
	  break;
	  case 'money':
	  $desc =" order by u.".$_GET['mem_sort'];
	  break;
	  case 'mg_money':
	  $desc =" order by u.".$_GET['mem_sort'];
	  break;
	  case 'ag_money':
	  $desc =" order by u.".$_GET['mem_sort'];
    break;
    case 'lebo_money':
    $desc =" order by u.".$_GET['mem_sort'];
    break;
    case 'bbin_money':
    $desc =" order by u.".$_GET['mem_sort'];
    break;
    case 'og_money':
    $desc =" order by u.".$_GET['mem_sort'];
    break;
    case 'ct_money':
    $desc =" order by u.".$_GET['mem_sort'];
    break;
	default:
    $desc =" order by u.reg_date";
	  break;
   }
}else{
   $desc .= ' order by u.uid ';
}
//会员排序方式
if (!isset($_GET['mem_order'])) {
   $desc .= ' DESC';
}else{
   $desc .= ' '.$_GET['mem_order'];
}


//查询
if(!empty($_GET["search_name"])){
    switch ($_GET['search_type']) {
      case '0':
        $sql .= " and u.username like '%".$_GET['search_name']."%'";
        break;
      case '1':
        $sql .= " and u.pay_name like '%".$_GET['search_name']."%'";
        break;
      case '2':
        $sql .= " and u.mobile like '%".$_GET['search_name']."%'";
        break;
      case '3':
        $sql .= " and u.pay_num like '".$_GET['search_name']."%'";
        break;
      case '4':
        $sql .= " and u.reg_ip like '".$_GET['search_name']."%'";
        break;
      case '5':
        $sql .= " and u.login_ip like '".$_GET['search_name']."%'";
        break;
    }
}
//时间区间
if (!empty($_GET['start_date']) && !empty($_GET['end_date']))   {
   $start_date = $_GET['start_date'];
   $end_date = $_GET['end_date'];
   $sql .= " and u.reg_date<='".$_GET['end_date']." 23:59:59' and u.reg_date>='".$_GET['start_date']." 00:00:00'";
}

if ($_GET["mem_status"] > 0){
   if (!empty($new_user_on)) {
       $on_user_str = implode(',',$new_user_on);
       $sql .=" and u.uid in ($on_user_str) ";
   }else{
       $sql .=" and u.uid = 0 ";
   }

}

$query    = $mysqlt->query($sql.$desc);
$sum    = $mysqlt->affected_rows;
if (empty($_GET['start_date']) && empty($_GET['end_date'])) {
   $sum_title = '总会员数:'.$sum;
}else{
   $sum_title = '此日期内会员数:'.$sum;
}
$pagenum=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$CurrentPage=isset($_GET['page'])?$_GET['page']:1;

 $totalPage=ceil($sum/$pagenum); //计算出总页数
if($totalPage<$CurrentPage){
  $CurrentPage=1;
}
$uid    = '';
$i      = 1; //记录 uid 数
// $start    = ($thisPage-1)*20+1;
// $end    = $thisPage*20;
$start  = ($CurrentPage-1)*$pagenum+1;
$end  = $CurrentPage*$pagenum;
while($row = $query->fetch_array()){
  //会员状态
  if($i >= $start && $i <= $end){
    $uid .= $row['uid'].',';
  }
  if($i > $end) break;
    $i++;
}


?>
<?php $title='會員管理'; require("../common_html/header.php");?>
<body>
<script>
         //下拉选项
$(document).ready(function(){

   $("#mem_agent_select").val('<?=$_GET['agent_id']?>');
   $("#mem_type_select").val('<?=$_GET['mem_enable']?>');
  // $("#mem_type_select").val('<?=$_GET['mem_type']?>');
   $("#status_select").val('<?=$_GET['mem_status']?>');
   $("#sort_select").val('<?=$_GET['mem_sort']?>');
   $("#order_select").val('<?=$_GET['mem_order']?>');
   $("#search_mem").val('<?=$_GET['search_mem']?>');
   $("#utype").val('<?=$_GET['search_type']?>');
   $("#mem_agent").val('<?=$_GET['mem_agent']?>');
   });

</script>
<div  id="con_wrap">
<div  class="input_002" >會員管理(今日注册:<?=$todayReg_count?> <?=$sum_title?>)</div>
<div  class="con_menu"  style="height: 55px;margin-bottom:5px;width:870px;overflow:hidden;height:58px">
<form  name="myFORM"  id="myFORM"  action="<?=$_SERVER["REQUEST_URI"]?>"  method="GET">
代理账号：
	<select  name="agent_id" onchange="document.getElementById('myFORM').submit()" id="mem_agent_select"   class="za_select">
        <option  value="">全部</option>
        <?php if (!empty($agent_up)) {
           foreach ($agent_up as $key => $val) {
		?>
			<option  value="<?=$val['id']?>"<?=select_check($val['id'],$_GET['agent_id'])?>><?=$val['agent_user']?>(<?=$val['agent_name']?>)</option>
        <?
          }
        }
        ?>
    </select>
    <select  name="mem_enable"  onchange="document.getElementById('myFORM').submit()" id="mem_type_select"   class="za_select">
      <option  value="">全部</option>
      <option  value="0">启用</option>
      <option  value="1">停用</option>
      <option  value="2">暫停</option>
    </select>

         状态:
     <select name="mem_status" onchange="document.getElementById('myFORM').submit()" id="status_select" class="za_select">
          <option  value="0">全部</option>
          <option  value="1">在线</option>
     </select>
           排序:
     <select  id="sort_select"  name="mem_sort" onchange="document.getElementById('myFORM').submit()"  class="za_select">
          <option  value="reg_date">注册日期</option>
          <option  value="username">會員帳號</option>
		  <option  value="login_time">登入日期</option>
		  <option  value="money">系统额度</option>
		  <option  value="mg_money">MG額度</option>
		  <option  value="ag_money">AG額度</option>
      <option  value="og_money">OG額度</option>
      <option  value="lebo_money">LEBO額度</option>
      <option  value="bbin_money">BBIN額度</option>
      <option  value="ct_money">CT額度</option>
     </select>
              <select  id="order_select"  name="mem_order" onchange="document.getElementById('myFORM').submit()"  class="za_select">
                <option  value="desc">由大到小</option>
                <option  value="asc">由小到大</option>

              </select>
               每页记录数：
  <select name="page_num" id="page_num" onchange="document.getElementById('myFORM').submit()" class="za_select">
    <option value="20" <?=select_check(20,$pagenum)?>>20条</option>
    <option value="30" <?=select_check(30,$pagenum)?>>30条</option>
    <option value="50" <?=select_check(50,$pagenum)?>>50条</option>
    <option value="100" <?=select_check(100,$pagenum)?>>100条</option>
  </select>
  &nbsp;頁數：
 <select id="page" name="page" class="za_select">
  <?php

    for($i=1;$i<=$totalPage;$i++){
      if($i==$CurrentPage){
        echo  '<option value="'.$i.'" selected>'.$i.'</option>';
      }else{
        echo  '<option value="'.$i.'">'.$i.'</option>';
      }
    }
   ?>
  </select> <?php echo  $totalPage ;?> 頁&nbsp;
              注册日期：
              <input class="za_text Wdate" onClick="WdatePicker()" value="<?=$start_date?>" size="10" name="start_date"> - <input  type="text"  name="end_date" id="end_date" readonly  value="<?=$end_date?>" class="za_text Wdate" onClick="WdatePicker()">
              <select  name="search_type" id="utype"  class="za_select">
              <option  value="0">帳號</option>
              <option  value="4">注册IP</option>
              <option  value="5">登陆IP</option>
              <option  value="1">姓名</option>
              <option  value="2">手机</option>
              <option  value="3">银行卡</option>
            </select>
              <input  type="text"  name="search_name"  value="<?=$_GET['search_name']?>"  class="za_text"  style="min-width:80px;width:80px;">
              <input  type="submit" value="搜索"   class="za_button">
         <!--      <input  type="button"  name="append"  value="新增"   class="za_button" onclick="document.location='member_add.php'">  -->


</form>
<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
</script>
</div>
</div>
<div  class="content" style="overflow">
  <table  style="table-layout: fixed;width:100%" border="0"  cellspacing="0"  cellpadding="0"  class="m_tab">
    <tbody><tr  class="m_title_over_co">
      <td>状态</td>
      <td>真實姓名</td>
      <td style="width:95px;">登入帳號</td>
      <td>系統額度</td>
      <td>LEBO額度</td>
      <td>BBIN額度</td>
      <td>AG額度</td>
      <td>OG額度</td>
      <td>MG額度</td>
      <td>CT額度</td>
       <td>盤口</td>
      <td style="width:180px;">所屬代理商</td>
      <td style="width:130px;">新增日期</td>
      <td>狀況</td>
      <td style="width: 240px;">功能</td>
    </tr>
      <?php
if($uid){
  $uid  = rtrim($uid,',');
  $sql  = "select u.uid,u.agent_id,u.username,u.money,u.login_ip,u.is_delete,g.agent_user,g.id,g.agent_name,u.pay_name,u.mobile,u.login_time,u.og_money,u.mg_money,u.ag_money,u.lebo_money,u.bbin_money,u.ag_money,u.mg_money,u.ct_money,u.reg_date from k_user u left join k_user_agent g on u.agent_id=g.id where u.shiwan = '0' and u.uid in ($uid) ".$desc;

// echo $sql;
  $query  = $mysqlt->query($sql);
  while($rows = $query->fetch_array()){
      $over = "#EBEBEB";
      $out  = "#ffffff";
      $color  = "#FFFFFF";
      if($rows["money"] < 0){
        $color = $over = $out = "#FF9999";
      }
      //在线判断
      if(!empty($new_user_on)){
         if (in_array($rows['uid'],$new_user_on)) {
             $is_state = "<span style=\"color:#FF00FF;\">在線</span>";
          }else{
             $is_state = "<span style=\"color:#999999;\">離線</span>";
          }
      }else{
         $is_state = "<span style=\"color:#999999;\">離線</span>";
      }
        ?>
    <tr  class="m_cen">
      <td><a  style="color:red"  title="登陸時間:<?=$rows['login_time']?> 登陸IP:<?=$rows['login_ip']?>"><?=$is_state?></a></td>
      <td><?=$rows["pay_name"]?></td>
      <td><?=$rows["username"]?></td>
      <td  style="text-align:left"  nowrap=""><?=$rows["money"]?> </td>
      <td  style="text-align:left"  nowrap=""><?=$rows["lebo_money"]?></td>
      <td  style="text-align:left"  nowrap=""><?=$rows["bbin_money"]?></td>
      <td  style="text-align:left"  nowrap=""><?=$rows["ag_money"]?></td>
      <td  style="text-align:left"  nowrap=""><?=$rows["og_money"]?></td>
      <td  style="text-align:left"  nowrap=""><?=$rows["mg_money"]?></td>
      <td  style="text-align:left"  nowrap=""><?=$rows["ct_money"]?></td>
      <td>D</td>
      <td ><a style="width:180px;display:block;overflow:hidden;word-break:keep-all;white-space:nowrap;text-overflow:ellipsis;" href="member_index.php?agent_id=<?=$rows["id"]?>"><?=$rows["agent_user"]?>(<?=$rows["agent_name"]?>)</a></td>
      <td><?=$rows["reg_date"]?></td>
      <td><?php if ($rows['is_delete'] == 1) {
           echo "<span style=\"color:#FF00FF;\">停用</span>";
         }elseif ($rows['is_delete'] == 2) {
           echo "<span style=\"color:#FF00FF;\">暫停</span>";
         }else{
           echo "<span style=\"color:##1E20CA;\">正常</span>";
         } ?></td>
      <td  align="center">
       <?php
          $duid = $rows['uid'];
         if ($rows['is_delete'] == 1) {
            echo "<a onclick=\"return confirm('确定启用该会员使用')\" href=\"member_index.php?action=using&uid=$duid\">启用</a>";
         }elseif($rows['is_delete'] == 2){
            echo "<a onclick=\"return confirm('确定恢复该会员使用')\" href=\"member_index.php?action=using&uid=$duid\">恢复</a>";
         }else{
             echo "<a onclick=\"return confirm('确定停止该会员使用')\" href=\"member_index.php?action=stop&uid=$duid\">停用</a>".' / '."<a onclick=\"return confirm('确定暫停该会员使用')\" href=\"member_index.php?action=pause&uid=$duid\">暫停</a>";
         }
       ?>
       /
        <a  href="./member_edit.php?uid=<?=$rows["uid"]?>">修改</a>/
        <a  href="./user_set.php?aid=<?=$rows['agent_id']?>&uid=<?=$rows["uid"]?>">設定</a>/
        <a  href="../betRecord/bet_record.php?uid=<?=$rows["uid"]?>">下注</a>/
        <?php
        if (isset($_SESSION["adminid"])) {
              $quanxian = trim($_SESSION["quanxian"]);
              if ($quanxian == 'sadmin' || strpos($quanxian, 'g') !== false ){
                echo '<a  href="./member_data.php?uid='.$rows["uid"].'">資料</a>/';
              }
            }
        ?>
        <a  href="../cash/cash_system.php?uid=<?=$rows["uid"]?>&username=<?=$rows["username"]?>">現金</a>/
        <a  onclick="return confirm('确定更新用户')" href="./member_index.php?action=renew&uid=<?=$rows["uid"]?>&user_name=<?=$rows["username"]?>">更新</a>
      </td>
    </tr>
            <?php
      }
}else{ ?>
  <tr class="m_rig" style="display:;">
        <td height="70" align="center" colspan="16"><font color="#3B2D1B">暫無數據。</font></td>
      </tr>
<?php }?>
  </tbody></table>
</div>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>