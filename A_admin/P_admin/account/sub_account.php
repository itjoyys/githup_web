<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");

$u=M('sys_admin',$db_config);
$strMap="0,2";
$map['is_delete'] = array('in',"($strMap)");
$map['site_id'] = SITEID;
$map['type'] = 0;
if(!empty($_GET['search_name'])){
	if($_GET['search_type'] == 0){
		$str = '%'.$_GET['search_name'].'%';
		$map['login_name'] = array('like',$str);
	}elseif($_GET['search_type'] == 1){
		$str = '%'.$_GET['search_name'].'%';
		$map['about'] = array('like',$str);
	}	
}
if(isset($_GET['mem_status'])){
  $map['is_login']=$_GET['mem_status'];
}
$sum= $u->where($map)->count();//计算符合条件的数目

//在线账号获取
$redis_ckey = 'alg'.CLUSTER_ID.'_'.$_SESSION['site_id'].'*';
$on_user = $redis->keys($redis_ckey);
if (!empty($on_user)) {
    foreach ($on_user as $key => $val) {
        $new_user_on[] = str_replace('alg'.CLUSTER_ID.'_'.$_SESSION['site_id'],'',$val);
    }
    unset($on_user);
}else{
  $new_user_on = 0;
}

//分页
$perNumber=50; //每页显示的记录数
$totalPage=ceil($sum/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
   if($totalPage<$page){
      $page=1;
    }
$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;


$admin= $u->where($map)->limit($limit)->select();
$page = $u->showPage($totalPage,$page);
 // p($admin);


//子账号暂停 删除 启用操作
switch (@$_GET['action']) {
  case 'pa':
    //暂停
      $dataP = array();
      $dataP['is_delete'] = 2;
      $stateP = M('sys_admin',$db_config)
              ->where("uid = '".$_GET['uid']."'")
              ->update($dataP);
      admin::insert_log($_SESSION["adminid"], $_SESSION['login_name'], "暂停了后台管理员 " . $_GET['sname']);
      admin::make_offline($_GET['uid']);
      message('暂停成功', 'sub_account.php');
    break;
  case 'us':
    //启用
     $dataP = array();
     $dataP['is_delete'] = 0;
     $dataP['error_num'] = 0;
     $stateP = M('sys_admin',$db_config)
              ->where("uid = '".$_GET['uid']."'")
              ->update($dataP);
     admin::insert_log($_SESSION["adminid"], $_SESSION['login_name'], "启用了后台管理员 " . $_GET['sname']);
     admin::make_offline($_GET['uid']);
     message('启用成功', 'sub_account.php');
    break;
  case 'de':
    //删除
     $dataP = array();
     $dataP['is_delete'] = 1;
     $stateP = M('sys_admin',$db_config)
              ->where("uid = '".$_GET['uid']."'")
              ->update($dataP);
      admin::insert_log($_SESSION["adminid"], $_SESSION['login_name'], "删除了后台管理员 " . $_GET['sname']);
      admin::make_offline($_GET['uid']);
      message('删除成功', 'sub_account.php');
    break;
}

?>
<?php $title='子账号'; require("../common_html/header.php");?>
<body> 
<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
  $(document).ready(function(){
   $("#search_name").val('<?=$_GET['search_name']?>');
   $("#utype").val('<?=$_GET['search_type']?>');
   $("#page").val('<?=$_GET['page']?>');
});
</script>
<style type="text/css">
  #page{font:12px/16px arial}
  #page span{float:left;margin:0px 3px;}
  #page a{float:left;margin:0 3px;border:1px solid #ddd;padding:3px 7px; text-decoration:none;color:#666}
  #page a.now_page,#page a:hover{color:#fff;background:#05c}
</style>
<div  id="con_wrap">
  <div  class="input_002">子帳號管理</div>
  <div  class="con_menu"  style="width:80% ; padding-top:3px;">
  <form  name="myFORM"  id="myFORM"  action="<?=$_SERVER["REQUEST_URI"]?>"  method="GET">
  	<select  name="search_type"  id="utype"  class="za_select">
		<option  value="0">帳號</option>
		<option  value="1">名称</option>
	</select>	
	<input  type="text"  name="search_name" id="search_name" value=""  class="za_text"  style="min-width:80px;width:80px;">
	<input  type="submit" value="搜索"   class="za_button">
	<input  type="button"  name="append"  value="新增"   class="za_button" onclick="document.location='sub_account_add.php?action=add'"> 
  &nbsp;<?=$page?>&nbsp;
  </form>

  </div>
</div>
<div  class="content">
  <table  width="1024"  border="0"  cellspacing="0"  cellpadding="0"  bgcolor="#0E75B0"  class="m_tab">
    <tbody><tr  class="m_title_over_co"  bgcolor="#429CCD">
      <td>状态</td>
      <td>名稱</td>
      <td>帳號</td>
      <td>登入帳號</td>
      <td>最后登录时间</td>
      <td>最后登录IP</td>
      <td>新增日期</td>
      <td>登陆错误次数</td>
      <td>当前状态</td>
      <td>功能</td>
    </tr>
<?php
  if (!empty($admin)) {
    foreach ($admin as $key => $rows) {
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
      <!-- <td><a  title=""  href="">離線</a></td> -->
        <td><?=$is_state?></td>
      <td><?=$rows["about"]?></td>
      <td><?=$rows["login_name"]?></td>
      <td><?=$rows["login_name_1"]?></td>
      <td><?=date('Y-m-d H:i:s',$rows["updatetime"])?></td>
       <td><?=$rows["login_ip"]?></td>
      <td><?=$rows["add_date"]?></td>
      <td><?=$rows["error_num"]?></td>
      <td><?php if($rows["is_delete"]==2){echo '<span style="color:#FF0000">已暂停</span>';}elseif($rows["is_delete"]==0){echo '<span style="color:#006600">已启用</span>';}?></td>
      <td  align="center">
      <?php if($rows['is_delete'] == "2"){
            echo "<a onclick=\"return confirm('确定启用该会员使用');\" href=\"sub_account.php?action=us&sname=".$rows['login_name']."&uid=".$rows["uid"]."\">启用</a> / ";
          
         } ?>
      
      <a  href="sub_account_add.php?action=u&uid=<?=$rows["uid"]?>">修改</a> / 
      <?php if($rows['is_delete'] == "0"){
            echo "<a onclick=\"return confirm('确定暂停该子账号使用');\" href=\"sub_account.php?action=pa&sname=".$rows['login_name']."&uid=".$rows["uid"]."\">暂停</a> / ";
         } ?>
      
 <a  href="sub_account.php?action=de&sname=<?=$rows['login_name']?>&uid=<?=$rows["uid"]?>"  onclick="return confirm('确定删除该子账号?');">刪除</a>
      </td>
    </tr>
    <?php
    }
  }else{?>
  <tr class="m_rig" style="display:;">
        <td height="70" align="center" colspan="10"><font color="#3B2D1B">暫無數據。</font></td>
      </tr>
  <?php }?>
  </tbody>
  </table>
</div>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>
</body>
</html>