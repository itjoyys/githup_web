<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
/**

  s_h表示大股东  u_a表示总代理  a_t表示代理  

*/
$allagent=M('k_user_agent',$db_config)->where("is_delete=0 and site_id='".SITEID."' and is_demo = '0'")->select();
$agent = M('k_user_agent',$db_config);

if (!empty($_GET['uid'])) {
  $user_data = $agent->field('agent_user')->where("id = '".$_GET['uid']."'")->find();

  switch ($_GET['action']) {
   case 'stop':
    //停用会员
       $data['is_delete'] = 1;
       if($agent->where("id = '".$_GET['uid']."'")->update($data)){
          admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],"停用了股东".$user_data['agent_user']."的账号");
          message('操作成功','shareholder_index.php');
       }
    
    break;
         case 'pause':
    //会员暂停
       $data['is_delete'] = 2;
       if($agent->where("id = '".$_GET['uid']."'")->update($data)){
          admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],"暂停了股东".$user_data['agent_user']."的账号");
          message('操作成功','shareholder_index.php');
       }
    
    break;
   case 'using':
    //启用会员
       $data['is_delete'] = 0;
       if($agent->where("id = '".$_GET['uid']."'")->update($data)){
          admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],"启用了股东".$user_data['agent_user']."的账号");
          message('操作成功','shareholder_index.php');
       }
    break;
  }
}

//条件拼接
$map['agent_type'] = 's_h';
$map['site_id'] = SITEID;
$map['is_demo'] = '0';
//账号查询
if (!empty($_GET['agent_user'])) {
    $str = '%'.$_GET['agent_user'].'%';
    $map['agent_user'] = array('like',$str);
}

//启用关闭
if ($_GET['is_delete'] == 'Y') {
   $map['is_delete'] = 0;
}elseif ($_GET['is_delete'] == 'N'){
   $map['is_delete'] = 1;
}

//排序
if (!empty($_GET['sort'])) {
   $order = $_GET['sort'];
}else{
   $order = 'id';
}
//升降
if (!empty($_GET['order'])) {
   $order = $order.' '.$_GET['order'];
}else{
   $order = $order.' DESC';
}

$count = $agent->field("id")->where($map)->order($order)->count();
//获得记录总数
//分页
$perNumber=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
   if($totalPage<$page){
      $page=1;
    }
$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;

$page = $agent->showPage($totalPage,$page);
$agent = $agent->where($map)->order($order)->limit($limit)->select();


foreach ($agent as $key => $value) {
    // p($agent);//股东
    $agent_data1=M('k_user_agent',$db_config)->where('pid='.$value['id'])->select();//zongdai
    $agent[$key]['agents_num']=count($agent_data1);
    if(!empty($agent_data1)){
        foreach ($agent_data1 as $k1 => $v1) {
            $agent_data2=M('k_user_agent',$db_config)->where('pid='.$v1['id'])->select();//daili
            $agent[$key]['agent_num']+=count($agent_data2); 
            if(!empty($agent_data2)){
                foreach ($agent_data2 as $k2 => $v2) {
                    $user_data_3=M('k_user',$db_config)->field("uid")->where("agent_id=$v2[id] and shiwan = 0")->select();//huiyuan
                    $agent[$key]['user_num']+=count($user_data_3);
                    
                }
            }else{
                $agent[$key]['user_num']=0;
            }
        }
    }else{
        $agent[$key]['agent_num']=0;
    }
}


?>
<?php $title='股東管理'; require("../common_html/header.php");?>
<script language="javascript">
function go(value){
	if(value != "") location.href=value;
	else return false;
}
//下拉框选中
$(document).ready(function(){
   $("#is_delete").val('<?=$_GET['is_delete']?>');
   $("#sort").val('<?=$_GET['sort']?>');
   $("#order").val('<?=$_GET['order']?>');
   $("#agent_user").val('<?=$_GET['agent_user']?>');
})
</script>
<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
</script>
</HEAD>
<body>
<div id="con_wrap">
  <div class="input_002">股東管理</div>
  <div class="con_menu">
    <form name="myFORM" id="myFORM" action="<?=$_SERVER["REQUEST_URI"]?>" method="get">
      狀態：
      <select id="is_delete" name="is_delete" onchange="document.getElementById('myFORM').submit()" class="za_select">
        <option value="">全部</option>
        <option value="Y">啟用</option>
        <option value="N">停用</option>
      </select>
      排序：
      <select id="sort" name="sort" onchange="document.getElementById('myFORM').submit()" class="za_select">
        <option value="agent_name">股東名稱</option>
        <option value="agent_user">股東帳號</option>
        <option value="add_date">新增日期</option>
      </select>
      <select id="order" name="order" onchange="document.getElementById('myFORM').submit()" class="za_select">
        <option value="ASC">升冪(由小到大)</option>
        <option value="DESC">降冪(由大到小)</option>
      </select>
      帳號：
      <input type="text" name="agent_user" id="agent_user" value="" class="za_text" style="min-width:80px;width:80px;" size="15">
      <input type="submit" name="buname" value="搜索" class="za_button">
      <input type="button" name="append" onclick="document.location='shareholder_add.php'" value="新增" class="za_button">
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
    
<table width="100%"  bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" class="m_tab" width="100%" >
  <tr class="m_title_over_co">
	  <td>ID</td>
      <td>股東名稱</td>
      <td>股東帳號</td>
      <td>登陆帳號</td>
      <td>會員數</td>
      <td>体育占成</td>
      <td>彩票占成</td>
      <td>视讯占成</td>
      <td>新增日期</td>
      <td>狀況</td>
      <td>功能</td>
    </tr>     
<?php
$agent_num_1=0;
$agent_num=0;
$user_num_huiyuan=0;
if(!empty($agent)){
   
foreach ($agent as $key => $rows) {

?>
      <tr align="center" class="m_cen" onMouseOver="this.style.backgroundColor='#EBEBEB'" onMouseOut="this.style.backgroundColor='#ffffff'">
        <td height="20" align="center"  ><?=$rows["id"]?></td>
        <td><?=$rows["agent_name"]?></td>
		<td><a href="up_agent.php?super_agents_id=<?=$rows["id"]?>"><?=$rows["agent_user"]?></a> </td>
    <td><?=$rows["agent_login_user"]?></td>
		<td><font color="#CC0000">不限制</font>&nbsp;&nbsp;/
          <font  color="#FF0000"><?=!empty($rows['agents_num'])?$rows['agents_num']:0?></font>/ <font  color="#FF0000"><?=!empty($rows['agent_num'])?$rows['agent_num']:0?></font> /  <font  color="#FF0000"><?=!empty($rows['user_num'])?$rows['user_num']:0?></font></td>
         
	    <td><?=$rows["sports_scale"]*100?>%</td>
	    <td><?=$rows["lottery_scale"]*100?>%</td>
		<td><?=$rows["video_scale"]*100?>%</td>
		<td><?=$rows["add_date"]?></td>
		<td><?=isstop($rows["is_delete"])?></td>
		<td>
       <?php
          $duid = $rows['id'];
         if ($rows['is_delete'] == 1) {
             echo "<a onclick=\"return confirm('确定启用该股东使用')\" href=\"shareholder_index.php?action=using&uid=$duid\">启用</a>";
         }elseif($rows['is_delete'] == 2){
            echo "<a onclick=\"return confirm('确定恢复该股东使用')\" href=\"shareholder_index.php?action=using&uid=$duid\">恢复</a>";
         }else{
             echo "<a onclick=\"return confirm('确定停止该股东使用')\" href=\"shareholder_index.php?action=stop&uid=$duid\">停用</a>".' / '."<a onclick=\"return confirm('确定暫停该股东使用')\" href=\"shareholder_index.php?action=pause&uid=$duid\">暫停</a>";
         }
       ?>
     /<a href="shareholder_edit.php?uid=<?=$rows["id"]?>">修改</a>/ 
        <a href="agent_set.php?aid=<?=$rows["id"]?>">設定</a>/
        <a href="../betRecord/bet_record.php?gid=<?=$rows["id"]?>">下注</a>
        </td>        
        </tr>
<?php
	}
}else{?>
<tr class="m_rig" style="display:;">
        <td height="70" align="center" colspan="12"><font color="#3B2D1B">暫無數據。</font></td>
      </tr>
<?php }
?>		
    </table>
<?php require("../common_html/footer.php");?>
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