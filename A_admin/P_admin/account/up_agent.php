<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");

include("../../class/user.php");
/**

  s_h表示大股东  u_a表示总代理  a_t表示代理  

*/

$u = M('k_user_agent',$db_config);

if (!empty($_GET['uid'])) {
  $user_data = $u->field('agent_user')->where("id = '".$_GET['uid']."' and site_id='".SITEID."'")->find();
  switch ($_GET['action']) {
   case 'stop':
    //停用会员
       $data['is_delete'] = 1;
       if($u->where("id = '".$_GET['uid']."'")->update($data)){
         admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],"停用了总代理".$user_data['agent_user']."的账号");
          message('操作成功','up_agent.php');
       }
    break;
  case 'pause':
    //会员暂停
       $data['is_delete'] = 2;
       if($u->where("id = '".$_GET['uid']."'")->update($data)){
        admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],"暂停了总代理".$user_data['agent_user']."的账号");
          message('操作成功','up_agent.php');
       }
    
    break;
   case 'using':
    //启用会员
       $data['is_delete'] = 0;
       if($u->where("id = '".$_GET['uid']."'")->update($data)){
        admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],"启用了总代理".$user_data['agent_user']."的账号");
          message('操作成功','up_agent.php');
       }
    break;
  }
}
//查询不是代理的所有数据组合数组
$map['agent_type'] != 'a_t';
$map['site_id'] = SITEID;
$map['is_demo'] = '0';

//股东选择
if(!empty($_GET['super_agents_id'])){
	$map['pid'] = $_GET['super_agents_id'];
	$s_h = $u->where("id =".$_GET['super_agents_id']." and site_id='".SITEID."'")->select();
}




//状态选择
if($_GET['enable']=='Y'){
	$map['is_delete'] = 0;
}elseif($_GET['enable']=='N'){
	$map['is_delete'] = 1;
}
//排序
if(!empty($_GET['sort'])){
	$order = $_GET['sort'];
}else{
	$order = 'id';
}
//升降
if(!empty($_GET['orderby'])) {
   $order = $order.' '.$_GET['orderby'];
}else{
   $order = $order.' DESC';
}
//账号查询
if(!empty($_GET['uname'])){
    $str = '%'.$_GET['uname'].'%';
    $map['agent_user'] = array('like',$str);
}



$allagent = $u->where($map)->order($order)->select();

if(!empty($_GET['super_agents_id']) && is_array($allagent) && is_array($s_h)){
	$allagent=array_merge($allagent,$s_h);
}

$agent = agent_level($allagent,'u_a');  //拼接数组


$count=count($agent); //获得记录总数

//分页
$perNumber=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
   if($totalPage<$page){
      $page=1;
    }

$agent=array_slice($agent,$startCount,$perNumber);  //取当前页的数据


//查询股东
$con['site_id'] = SITEID;
$con['agent_type'] = 's_h';
$con['is_demo'] = '0';
$agent_up = $u->where($con)->order('id DESC')->select();
$page = $u->showPage($totalPage,$page);
?>
<?php $title='總代理管理'; require("../common_html/header.php");?>
<body> 
<script  language="javascript">
<!--
$(document).ready(function(){
   $("#super_agents_id").val('<?=$_GET['super_agents_id']?>');
   $("#enable").val('<?=$_GET['enable']?>');
   $("#sort").val('<?=$_GET['sort']?>');
   $("#orderby").val('<?=$_GET['orderby']?>');
   $("#uname").val('<?=$_GET['uname']?>');
});

function condel()
{
  if(confirm("您確認刪除此登錄帳號?"))
  {
    document.location.href='';
  }
  else
  {
    return false;
  }
}
-->
</script> 
<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
</script>
<script  language="javascript"  src="../public/js/agents.js"></script> 
<script  src="../public/js/report_func.js"  type="text/javascript"></script>
<div  id="con_wrap">
   <div  class="input_002">總代理管理</div>
   <div  class="con_menu">
    <form  name="myFORM"  id="myFORM"  action="#"  method="get">
      股東选择：
      <select  id="super_agents_id"  name="super_agents_id" onchange="document.getElementById('myFORM').submit()"  class="za_select">
        <option  value="">全部</option>
        <?php if (!empty($agent_up)) {
           foreach ($agent_up as $key => $val) {
   ?>
        <option  value="<?=$val['id']?>"><?=$val['agent_user']?></option>
        <?
          }  
        }
        ?>
      </select>
      <select  id="enable"  name="enable"  onchange="document.getElementById('myFORM').submit()" class="za_select">
        <option  value="">全部</option>
        <option  value="Y">啟用</option>
        <option  value="N">停用</option>
      </select>
      排序：
      <select  id="sort"  name="sort" onchange="document.getElementById('myFORM').submit()"  class="za_select">
        <option  value="agent_name">代理商名稱</option>
        <option  value="agent_user">代理商帳號</option>
        <option  value="add_date">新增日期</option>
      </select>
      <select  id="orderby"  name="orderby" onchange="document.getElementById('myFORM').submit()"  class="za_select">
        <option  value="asc">升冪(由小到大)</option>
        <option  value="desc">降冪(由大到小)</option>
      </select>
     
      帳號：
      <input  type="text"  name="uname"  value="" id="uname"  class="za_text"  style="min-width:80px;width:80px;"  size="15">
      <input  type="submit"  name="buname"  value="搜索"   class="za_button">
      <input  type="button"  name="append"  value="新增" onclick="document.location='agent_add.php?atype=s_h'"  class="za_button">
      每页记录数：
  <select name="page_num" id="page_num" onchange="document.getElementById('myFORM').submit()" class="za_select">
    <option value="20" <?=select_check(20,$perNumber)?>>20条</option>
    <option value="30" <?=select_check(30,$perNumber)?>>30条</option>
    <option value="50" <?=select_check(50,$perNumber)?>>50条</option>
    <option value="100" <?=select_check(100,$perNumber)?>>100条</option>
  </select>
  &nbsp;<?=$page?>

      </form>
  </div>
  </div>
  <div  class="content">
  <table  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab">
    <tbody><tr  class="m_title_over_co">
	  <td>ID</td>
      <td>總代理名稱</td>
      <td>總代理帳號</td>
      <td>登入帳號</td>
      <td>所屬股東</td>
      <td>會員數</td>
      <td>体育占成</td>
      <td>彩票占成</td>
      <td>视讯占成</td>
      <td>新增日期</td>
      <td>狀況</td>
      <td>功能</td>
    </tr>
    
   <?php
if (!empty($agent)) {
  
   foreach ($agent as $key => $rows) {

	   $agent_num=M('k_user_agent',$db_config)->where('pid='.$rows['id'])->count();
	   $user_num=M('k_user_agent',$db_config)->field('k_user.agent_id')->join('left join k_user on k_user_agent.id=k_user.agent_id')->where("k_user_agent.pid= '".$rows['id']."' and k_user.site_id = '".SITEID."' and k_user.shiwan = 0")->count();
?>
    <tr  class="m_cen">
    <td><?=$rows["id"]?></td>
      <td><?=$rows["agent_name"]?></td>
      <td><a href="agent.php?super_agents_id=<?=$rows["id"]?>"> <?=$rows["uid"]?><?=$rows["agent_user"]?></a></td>
      <td><?=$rows["agent_login_user"]?></td>
      <td><a href="up_agent.php?super_agents_id=<?=$rows["pid"]?>"><?=$rows["up_agent"]?></a> </td>
      <td><font color="#CC0000">不限制</font>&nbsp;&nbsp;/
          <font  color="#FF0000"><?=$agent_num?></font> /  <font  color="#FF0000"><?=$user_num?></font>
      </td>
      <td><?=$rows["sports_scale"]*100?>%</td>
      <td><?=$rows["lottery_scale"]*100?>%</td>
      <td><?=$rows["video_scale"]*100?>%</td>
      <td  nowrap="nowrap"><?=$rows["add_date"]?></td>
      <td><?=isstop($rows["is_delete"])?></td>
      <td  align="center" nowrap="nowrap">
              <?php
          $duid = $rows['id'];
         if ($rows['is_delete'] == 1) {
             echo "<a onclick=\"return confirm('确定启用该总代理使用')\" href=\"up_agent.php?action=using&uid=$duid\">启用</a>";
         }elseif($rows['is_delete'] == 2){
            echo "<a onclick=\"return confirm('确定恢复该总代理使用')\" href=\"up_agent.php?action=using&uid=$duid\">恢复</a>";
         }else{
             echo "<a onclick=\"return confirm('确定停止该总代理使用')\" href=\"up_agent.php?action=stop&uid=$duid\">停用</a>".' / '."<a onclick=\"return confirm('确定暫停该总代理使用')\" href=\"up_agent.php?action=pause&uid=$duid\">暫停</a>";
         }
       ?>/
       <a  href="agent_edit.php?atype=s_h&aid=<?=$rows['id']?>">修改</a> /
       <a  href="agent_set.php?aid=<?=$rows['id']?>">設定</a>  / 
       <a  href="../betRecord/bet_record.php?gid=<?=$rows["id"]?>">下注</a>
    </td>
    </tr>
    <?php
      
   }
}else{?>
<tr class="m_rig" style="display:;">
        <td height="70" align="center" colspan="12"><font color="#3B2D1B">暫無數據。</font></td>
      </tr>

<?php }?>

  
  </tbody>
</table>
</div>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>
<?php
  function isstop($gameType)
  {
    $arr  = array(
      "1"=>"<font style='color:#FF00FF';>停用</font>",
      "2"=>"<font style='color:#FF00FF';>暫停</font>",
      "0"=>"啟用"
      );
    return $arr[$gameType];
  }
?>
</body>
</html>