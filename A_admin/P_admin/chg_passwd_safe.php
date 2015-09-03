<?php 
include_once("../include/config.php");
include_once '../include/'.$_SESSION['site_id'].'_private_config.php';
include_once("../lib/class/model.class.php");

function message($value,$url=""){ //默认返回上一页
	header("Content-type: text/html; charset=utf-8");
	
	$js  = "<script type=\"text/javascript\" language=\"javascript\">\r\n";
	$js .= "alert(\"".$value."\");\r\n";
	if($url) $js .= "window.location.href=\"$url\";\r\n";
	else $js .= "window.history.go(-1);\r\n";
	$js .= "</script>\r\n";

	echo $js;
	exit;
}
//变更账号处理
if(!empty($_POST['uid'])){

	if(empty($_POST['username'])||empty($_POST['password'])||empty($_POST['password2'])){
		message("请填写完整表单！");
	}

	if ($_POST['type'] == 'p') {
		$is_name = M('sys_admin',$db_config)->where("login_name='".$_POST['username']."' or login_name_1='".$_POST['username']."'")->find();
	    if($is_name){
	       message("账号已存在!");
	    }
		$data['login_name_1']=$_POST['username'];
	    $data['login_pwd']=md5(md5($_POST['password2']));
		if(M("sys_admin",$db_config)->where("uid = '".$_POST['uid']."'")->update($data)){
		    message("修改成功！","./index.html");
		}else{
			message("修改失败！");
		}
	}elseif ($_POST['type'] == 'g') {
		$data['agent_login_user']=$_POST['username'];
	    $data['agent_pwd']=md5(md5($_POST['password2']));
	   if(M("k_user_agent",$db_config)->where("id = '".$_POST['uid']."'")->update($data)){
		   message("修改成功！","./admin_index.php");
		}else{
			message("修改失败！");
		}
	}	
}

 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html style="height: 100%;"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>變更密碼</title>

<link rel="stylesheet" href="./public/css/reset.css" type="text/css">
<link rel="stylesheet" href="./public/css/mem_order.css" type="text/css">
<script src="./public/js/jquery-1.7.min.js"></script>
</head>
<body id="PWD" marginwidth="0" marginheight="0">
<table width="450" border="0" align="center" cellpadding="1" cellspacing="0" class="pwd_side">
	<tbody><tr class="pwd_bg">
		<td colspan="2">
			<table border="0" cellpadding="1" cellspacing="0">
			  	<tbody><tr>
			  		<td width="400" class="pwd_title">設置登錄帳號</td>
					<td width="100" class="point">設置指引</td>
			  	</tr>
		 	</tbody></table>
	 	</td>
	</tr>
	<tr>
		<td colspan="2" class="pwd_txt">
			請使用閣下容易記得的名字或代號設定<font class="red_txt">「登錄帳號」</font>以供會員端登入使用。
		</td>
	</tr>
	<tr>
		<td colspan="2" class="pwd_txt">
			設置規則 : 必須有2個英文小寫字母和數字(0~9)組合輸入限制(6~15字元)
			<br>例：oicq888 , england228 , tudou668 , soccer2009 , 888yahoo等...簡易的名字或代號，皆可依照您所喜好設置。

		</td>
	</tr>
	<tr>
	<td colspan="2" class="pwd_txt">
	<p align="left">1.密碼需使用字母加上數字,字母开头。</p>
				<p align="left">2.密碼必須至少6個字元長，最多12個字元長。</p>
				</td>
	</tr>
	<form name="ChgPwdForm" method="post" action="" onsubmit="return inputCheck();" id="myform">
	<input type="hidden" name="uid" value="<?=$_SESSION['set_uid']?>">
	
		<tr>		
			<td width="100" align="right" class="pwd_txt">登錄帳號</td>
			<td width="350" class="pwd_txt">
				<input type="TEXT" name="username" id="username" value="" maxlength="15" class="za_text_02">
				
				<font class="red_txt username_info">注意：設置後將無法修改。</font><font class="username_tishi" style="color:#3D3934;"></font>
			</td>
		</tr>
		<tr>
					<td width="100" align="right" class="pwd_txt">登錄密码</td>
					<td width="350" class="pwd_txt">
					<input class="za_text_02" type="password" id="password" name="password" value="" maxlength="15"><font class="red_txt password_new_info"></font><font class="password_new_tishi" style="color:#3D3934;">密码为6-15個字元長</font></td>
		</tr>
		<tr>
				<td width="100" align="right" class="pwd_txt">確認密码</td>
					<td width="350" class="pwd_txt">
					<input class="za_text_02" type="password" id="password2" name="password2" value="" maxlength="15"><font class="red_txt password_new2_info"></font><font class="password_new2_tishi" style="color:#3D3934;"></font></td>

		</tr>
		<tr>
			<td colspan="2" align="center" class="pwd_bg">
			   <input type="hidden" value="<?=$_GET['type']?>" name="type">
				<input type="submit" class="input_btn" value="確認" >&nbsp;
				<input type="reset" class="input_btn" name="cancel" value="重置" >
			</td>
		</tr>
	</form>
</tbody></table>
<table align="center" style="margin-left:auto;margin-right:auto; ">
	<tbody><tr>
		<td class="white">
			<ul><li>如閣下的「登錄帳號」設置完成後<font class="red_txt">須以「登錄帳號」登入會員端，</font><br>原「會員帳號」只供識別身份使用，不可登入。</li></ul>
		</td>
	</tr>
</tbody></table>

<script type="text/javascript">  
 function inputCheck(){
    var userInput = $("input[name=username]");
    var passwdInput = $("input[name=password]");
    var pass2Input = $("input[name=password2]");
    if(userInput.val() == "" || userInput.val() == "帐号"){
        alert('请输入帐号!!');
        userInput.focus();
        return false;
    }else if(passwdInput.val()== "" || passwdInput.val() == "xx@x@x.x"){
        alert('请输入密码!!');
        passwdInput.focus();
        return false;
    }else if(passwdInput.val() != pass2Input.val() ){
        alert('两次密码不相同!!');
        vlcodesInput.focus();
        return false;
    }
    return true;
}

</script>
<script>
    var type = "<?=$_GET['type']?>";
	$(function(){
		var username_safe_1=0;
		var password_new_1=0;
		var password_new2_1=0;

		 //验证用户名		
		$("#username").blur(function(event) {
			var username = $(this).val();
			if (username == '') { return false;}
		  $.get( 
    		"./chg_passwd_safe_ajax.php",
    		{username:username,type:type,active:"u"},
	        function(data) {

	          if(data == 1){
	            $(".username_info").text("该用户名已存在！");
	            $(".username_tishi").text("");
	          }else if(data == 2){            
	            $(".username_info").text("");
	            $(".username_tishi").text("该用户名有效！");
	            username_safe_1=1;
	          }else if(data == 3){    
	            $(".username_info").text("该用户名格式不正确！");
	            $(".username_tishi").text("");	 
	          }else if(data == 4){ 
	            $(".username_info").text("用户名必须填写！");
	            $(".username_tishi").text("");
	          }
	        
	        }
     	 )

		});

		 //验证密码	
		$("#password").blur(function(event) {
			var password_new = $(this).val();
			if (password_new == '') { return false;}
		  $.get( 
    		"./chg_passwd_safe_ajax.php",
    		{password_new:password_new,active:"p"},
	        function(data) {

	          if(data == 1){
	            $(".password_new_info").text("");
	            $(".password_new_tishi").text("该密码有效！");	 
	            password_new_1 =1;
	          }else if(data == 2){            
	            $(".password_new_info").text("该密码格式不正确！");
	            $(".password_new_tishi").text("");	
	          }
	        
	        }
     	 )

		});

		//验证密码2
		$("#password2").blur(function(event) {
			var password_new2 = $(this).val();
			var password_new = $("#password2").val();
		  $.get( 
    		"./chg_passwd_safe_ajax.php",
    		{password_new2:password_new2,active:"p2",password_new:password_new},
	        function(data) {

	          if(data == 1){
	            $(".password_new2_info").text("");
	            $(".password_new2_tishi").text("该确认密码有效！");
	            password_new2_1=1;	 
	          }else if(data == 2){            
	            $(".password_new2_info").text("该确认密码格式不正确！");
	            $(".password_new2_tishi").text("");	
	          }
	        
	        }
     	 )

		});

		$("#myform").submit(function() {
			if(username_safe_1==0 || password_new_1==0 ||password_new2_1==0){
				return false;
			}else{
				$("#myform").submit();
			}
		});
	})
</script>


</body></html>