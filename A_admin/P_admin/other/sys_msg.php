<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");

	
if(@$_GET["action"]=="add"){
	include_once("../../class/user.php");
	
	$msg_title	=	trim($_POST["msg_title"]);
	$msg_info	=	trim($_POST["msg_info"]);
	$msg_from	=	trim($_POST["msg_from"]);
	if($_POST["type"] || $_POST['group']){
		$sql		=	'';
		if($_POST["type"] == "login"){ //所有在线会员
			$sql	=	"select uid from k_user_login where `is_login`>0";
		}elseif($_POST["type"] == "all"){ //所有会员
			$sql	=	"select uid from k_user";
		}
		if($sql){
			$query	=	$mysqlt->query($sql);
			while($rows = $query->fetch_array()){
				user::msg_add($rows["uid"],$msg_from,$msg_title,$msg_info);
			}
		}
		if($_POST['group']){
			$sql	=	"select uid from k_user where gid='".$_POST['group']."'";
			$query	=	$mysqlt->query($sql);
			while($rows = $query->fetch_array()){
				user::msg_add($rows["uid"],$msg_from,$msg_title,$msg_info);
			}
		}
		message('发送成功',$_SERVER['PHP_SELF']);
	}else{
		$arr_un = explode(',',rtrim(trim($_POST["username"]),','));
		$un		= '';
		foreach($arr_un as $k=>$v){
			$un	.= "'".$v."',";
		}
		if($un != ''){
			$un		=	rtrim($un,',');
			$sql	=	"select uid from k_user where username in ($un)";
			$query	=	$mysqlt->query($sql);
			while($rows = $query->fetch_array()){
				user::msg_add($rows['uid'],$msg_from,$msg_title,$msg_info);
			}
			message('发送成功',$_SERVER['PHP_SELF']);
		}
		message('没有这个用户名',$_SERVER['PHP_SELF']);
	}
}
?>

<?php require("../common_html/header.php");?>
<script language="javascript" src="../../js/jquery.js"></script>
<script>
function check(){
	if($("#msg_title").val()==""){
		alert("请输入短消息标题");
		$("#msg_title").select();
		return false;
	}
	if($("#msg_info").val()==""){
		alert("请输入短消息标题");
		$("#msg_info").select();
		return false;
	}
	var len = $(":radio:checked").length; 
	if($("#group").val()=="0" && len==0 && $("#username").val()==""){
		alert("请输入会员名称");
		$("#username").select();
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">
	 window.onload=function(){
    document.getElementById("other").onchange=function(){
      var val=this.value;
      if(val==1){
        window.location.href="./set_site.php";
      }else if(val==2){
        window.location.href="./dqxz.php";
      }else if(val==3){
        window.location.href="./add.php?1=1";
      }else{
        window.location.href="./ssgl.php";
      }
    }
  }
</script>
<body>
<div  id="con_wrap">
  <div  class="input_002">站内消息</div>
  <div  class="con_menu"> 
       <select  id="other"  name="other" style="margin-top:4px;" class="za_select">
        <option  value="1" >系统设置</option>
        <option  value="2" >地区限制</option>
        <option  value="3">公告管理</option>
        <option  value="5">申诉管理</option>
      </select>
  </div>
</div>
<table  width="780"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed" >
  <tr class="m_title_edit">
        <td colspan="2">给网站会员发布短消息</td>
   </tr>
  <tr class="m_bc_ed">
    <td height="24">
    <form name="form1"   method="post" action="<?=$_SERVER['PHP_SELF']?>?action=add" onsubmit="return check();">
    <table width="100%" border="1" bgcolor="#FFFFFF" bordercolor="#96B697" cellspacing="0" cellpadding="0" style="border-collapse: collapse; color: #225d9c;" >
  <tr class="m_bc_ed">
     <td class="m_co_ed">消息标题：</td>
     <td align="left"><input name="msg_title" id="msg_title" type="text" style="width:600px;" value="<?=@$_GET['title']?>"/></td>
  </tr>
  <tr class="m_bc_ed">
    <td class="m_co_ed">消息内容：</td>
    <td  align="left"><textarea name="msg_info" id="msg_info" rows="5" style="width:600px;"></textarea></td>
  </tr>
  <tr class="m_bc_ed">
    <td class="m_co_ed">用户名：</td>
    <td align="left">
      <textarea name="username" rows="2" id="username" style="width:600px;"><?=@$_GET["username"]?></textarea>
      多个会员用 , 隔开
      <br/>
          <input type="radio" name="type" value="login" />
          在线会员&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="radio" name="type" value="all" />
          所有会员&nbsp;&nbsp;&nbsp;&nbsp;
		  会员组：<select name="group" id="group">
            <option value="0">请选择会员组</option>
<?php
$sql	=	"SELECT id,name FROM k_group order by id asc";
$query	=	$mysqlt->query($sql);
while($rows = $query->fetch_array()){
?>
            <option value="<?=$rows["id"]?>"><?=$rows["name"]?></option>
<?php
}
?>
          </select>
        </td>
  </tr>
  <tr class="m_bc_ed">
    <td class="m_co_ed">发布者：</td>
    <td width="88%" align="left"><input type="text" name="msg_from"  value="<?=$web_site['web_name']?>" /></td>
    </tr>
  <tr class="m_bc_ed">
    <td colspan="2" align="center">&nbsp;</td>
    </tr>
  <tr class="m_bc_ed">
    <td align="center">&nbsp;</td>
    <td align="left"><input name="submit" type="submit" value="发布"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="msg_list.php?1=1">查看短消息记录</a></td>
  </tr>
</table>  
    </form>
</td>
  </tr>
</table>
</body>
</html>