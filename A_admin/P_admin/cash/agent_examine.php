<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include("../../class/setCenter.class.php");

//读取代理审核管理
$u = M('k_user_agent',$db_config);

//代理商状态
if(isset($_GET['enable'])){
	switch ($_GET['enable']){
		case 'yes':
			$map['is_delete'] = 0;
			break;
		case 'no':
			$map['is_delete'] = 5;
			break;
		default:
			break;	
	}
}
//账号查询
if(!empty($_GET['uname'])&&$_GET['type']=='account'){
  if(!empty($_GET['uname'])){
  $str = '%'.$_GET['uname'].'%';
  $map['agent_login_user']=array('like',$str);
  }
}

//姓名查询
if(!empty($_GET['uname'])&&$_GET['type']=='realname'){
  if(!empty($_GET['uname'])){
  $str = '%'.$_GET['uname'].'%';
  $map['agent_name']=array('like',$str);
  }
}
//电话查询
if(!empty($_GET['uname'])&&$_GET['type']=='mobile'){
  if(!empty($_GET['uname'])){
  $str = '%'.$_GET['uname'].'%';
  $map['mobile']=array('like',$str);
  }
}
//email查询
if(!empty($_GET['uname'])&&$_GET['type']=='email'){
  if(!empty($_GET['uname'])){
  $str = '%'.$_GET['uname'].'%';
  $map['email']=array('like',$str);
  }
}
//QQ查询
if(!empty($_GET['uname'])&&$_GET['type']=='qq'){
  if(!empty($_GET['uname'])){
  $str = '%'.$_GET['uname'].'%';
  $map['qq']=array('like',$str);
  }
}
//申请理由查询
if(!empty($_GET['uname'])&&$_GET['type']=='grounds'){
  if(!empty($_GET['uname'])){
  $str = '%'.$_GET['uname'].'%';
  $map['grounds']=array('like',$str);
  }
}
$map['is_apply'] = 1;
$map['site_id'] = SITEID;

$count=$u->field("id")->where($map)->count();//获得记录总数


//分页
$perNumber=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;

//查询表信息
$agent=$u->where($map)->limit($limit)->order("id DESC,is_delete DESC")->select();
$page = $u->showPage($totalPage,$page);
//删除代理申请
if ($_GET['action'] == 'del' && !empty($_GET['uid'])) {
   $data['is_delete'] = 1;
   $u->where("id = '".$_GET['uid']."'")->update($data);
   message("删除成功！","agent_examine.php");
}
				   
if ($_GET['action'] == 'sure' && !empty($_GET['uid'])) {
    $data['is_delete'] = 0;
    $Sys = M('sys_admin',$db_config);
    $info=$u->where("id = '".$_GET['uid']."'")->find();
    $a=M('web_config',$db_config);
    $config=$a->where("site_id='".SITEID."'")->find();
        if ($aid=M('k_user_agent',$db_config)->update($data)) {
            $state = setCenter::agent_setAdd($info['id'],$info['pid']);
            if (empty($state)) {
                $do_log = "设定参数错误,";
            }

            $dataS['login_name'] = SITEID.$info['agent_login_user'];
            $dataS['login_name_1'] = $info['agent_login_user'];
            $dataS['quanxian']='agadmin';//添加代理时 默认给权限
            $dataS['admin_url'] = $config['agent_url'];
            $dataS['agent_id'] = $info['id'];
            $dataS['type'] = 1;
            $dataS['about'] = $info['agent_login_user'];
            $dataS['login_pwd'] = $info['agent_pwd'];
            $dataS['site_id'] = SITEID;
            $dataS['add_date'] =date('Y-m-d H:i:s');//添加时间

            $Sys->add($dataS);
            $do_log = '添加了代理账号:'.SITEID.$info['agent_user'];
            admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
            message('添加代理成功','../account/agent.php');
    }else{
        message('已经存在该账号,请使用其它账号','agent_examine.php');
    }
}
?>
<?php $title="代理申请管理"; require("../common_html/header.php");?>
<body> 
<script  language="javascript">
<!--
$(document).ready(function(){
  $("#enable").val('<?=$_GET['enable'];?>');
  $("#type").val('<?=$_GET['type'];?>');
  $("#uname").val('<?=$_GET['uname'];?>');
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
   <div  class="input_002">代理商审核管理</div>
   <div  class="con_menu">
    <form  name="myFORM"  id="myFORM"  action="#"  method="get">
        <a href="agent_set.php">会员新增代理设定</a>
  状态：
      <select  id="enable"  name="enable" onchange="document.getElementById('myFORM').submit()"  class="za_select">
   
        <option  value="all">全部</option>
        <option  value="yes">已添加</option>
        <option  value="no">未处理</option>
      </select>
      搜索内容：
  <select name="type" id="type">
  <option value="">请选择</option>
    <option value="realname">姓名</option>
    <option value="account">帐号</option>
    <option value="mobile">电话</option>
    <option value="email">email</option>
    <option value="qq">QQ</option>
    <option value="grounds">申请理由</option>
    </select>
      <input  type="text"  name="uname"  value=""  class="za_text"  style="min-width:80px;width:80px;"  size="15"  maxlength="15" id="uname">
      <input  type="submit"  name="buname"  value="搜索"   class="za_button">
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
    <tbody>
      <tr class="m_title_over_co">
     
      <td >中文昵称</td>
      <td >账号</td>
      <td >电话</td>
      <td>email/msn_qq</td>
      <td >姓名</td>
      <td >状态</td>
      <td >申请时间</td>
      <td class="table_bg">功能</td>
      <td class="table_bg">推广网址</td>
      <td class="table_bg">申请理由</td>
    </tr>
   <?php
   if (!empty($agent)) {
    
       foreach ($agent as $key => $rows) {
?>
    <tr  class="m_cen">
    
      <td><?=$rows["agent_name"]?></td>
        <td><?=$rows["agent_login_user"]?></td>
      <td><?=$rows['mobile']?></td>
       <td><?=$rows["email"]?>&nbsp;/&nbsp;<?=$rows["qq"]?></td>
      <td><?=$rows["realname"]?></td>
      <td ><?php if ($rows["is_delete"]==0) {
           echo "<font>已添加</font>";
      }elseif ($rows['is_delete'] == 1) {
           echo "<font style='color:#f00;'>停用</font>";
      }elseif ($rows['is_delete'] == 5) {
           echo "<font style='color:#f00;'>未处理</font>";
      }
      ?></td>
      <td ><?=$rows["add_date"]?></td>
      <td>
<?php 
  if($rows["is_delete"] == 5   ){

 ?>
            <a onClick="return confirm('通过该用户成为代理的请求？');" href="../account/agent_add.php?appid=<?=$rows["id"]?>&r_user=<?=$rows['agent_login_user']?>&r_name=<?=$rows['agent_name']?>&atype=u_a&app_pwd=<?=$rows['agent_pwd']?>">新增账号</a> &nbsp;/ &nbsp;
<?php  } ?>
     
       <a href="../account/agents_data.php?uid=<?=$rows["id"]?>">详细内容</a>
    <!--   <a onClick="return confirm('删除该用户成为代理的请求？');" href="agent_examine.php?uid=<?=$rows["id"]?>&action=del">新增帐号</a> &nbsp;/&nbsp;  -->
<?php 
  if($rows["is_delete"] == 5){

 ?>
&nbsp;/ &nbsp;
      <a style="color:#EC1717;" onClick="return confirm('删除该用户成为代理的请求？');" href="agent_examine.php?uid=<?=$rows["id"]?>&action=del">删除</a>
<?php  } ?>
      </td>
       <td ><?=$rows["from_url"]?></td>
        <td ><?=@htmlspecialchars($rows["grounds"])?></td>
    </tr>
    <?php
  }
}
?>

  </tbody>
</table>
</div>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>