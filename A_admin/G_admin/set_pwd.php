<?php
include_once "../include/config.php";
include("./common/login_check.php");

if($_GET["action"]=="save"){
   $data['login_pwd'] = md5(md5($_POST['password']));
   $upState = M('sys_admin',$db_config)->where("uid = '".$_SESSION["adminid"]."'")->update($data);
   include_once("../class/admin.php");
  if($upState){
     
     admin::insert_log($uid,$_SESSION['login_name'],"修改了自己的密码"); 
     session_destroy();
     echo '<script language="javascript">alert("修改密码成功");top.location.href="/index.html"; </script>';
  }else{
     admin::insert_log($uid,$_SESSION['login_name'],"修改自己的密码失败"); 
     session_destroy();
     echo '<script language="javascript">alert("修改密码失败");top.location.href="/index.html"; </script>';
  }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>设置密码</title>
<link  rel="stylesheet"  media="all"  href="./public/<?=$_SESSION['site_id']?>/css/styleCss.css">
<link  rel="stylesheet"  media="all"  href="./public/<?=$_SESSION['site_id']?>/css/reset.css">
<link  rel="stylesheet"  href="./public/<?=$_SESSION['site_id']?>/css/main.css"  type="text/css">
<script  src="./public/js/jquery-1.7.min.js"  type="text/javascript"></script>
<body>
<script language="javascript">
function check_sub(){
  var p1 = document.getElementById("password").value;
  var p2 = document.getElementById("password2").value;
  if(p1.length < 1){
    alert('请输入密码');
    document.getElementById("password").focus();
    return false;
  }
 
  if(p1 != p2){
    alert("两次密码输入不一致");
    document.getElementById("password2").select();
    return false;
  } 
  
  return true;
}
 </script>
 <style type="text/css">
     .content {
  float: left;
  width: 100%;
}

 </style>
<div  id="con_wrap">
  <div  class="input_002">管理員管理--修改</div>
  <div  class="con_menu">
</div>
</div>
<div  class="content">
  <form onSubmit="return check_sub();" action="set_pwd.php?action=save" method="post" name="form1">
  <table  width="780"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab_ed">
    <tbody><tr  class="m_title_edit">
      <td  colspan="2">基本資料設定</td>
    </tr>
    <tr  class="m_bc_ed">
      <td  class="m_mem_ed" width="120"> 帳號：</td>
      <td><?=$_SESSION["login_name"]?></td>
    </tr>
    <tr  class="m_bc_ed">
      <td  class="m_mem_ed">密碼：</td>
      <td>
        <input  type="PASSWORD" id="password" type="password" name="password"  value="" size="12"  maxlength="12"  class="za_text" >
        密碼必須至少6個字元長，最多12個字元長，并只能有數字(0-9)，及英文大小寫字母
      </td>
    </tr>
    <tr  class="m_bc_ed">
      <td  class="m_mem_ed">確認密碼：</td>
      <td>
        <input  id="password2" type="password"  name="password2"  size="12"  value="" maxlength="12"  class="za_text" >
      </td>
    </tr>
    <tr  class="m_bc_ed"  align="center">
      <td  colspan="2"  class="m_bc_td">
        <input  name="s" type="submit" id="s" value="確定"  class="za_button">
        &nbsp;&nbsp;&nbsp;
       <input  type="button"  id="FormsButton2"  name="FormsButton2"  value="取消"   class="za_button">
      </td>
    </tr>
  </tbody></table>
  </form>

</div>
<!-- 公共尾部 -->
<?php require("./common_html/footer.php"); ?>