<?php
	include_once("../../include/config.php");
	include_once("../../common/login_check.php");
	include_once("../../class/user.php");
	include_once("../../common/function.php");

	$userinfo=user::getinfo($_SESSION["uid"]);
	if($userinfo['change_pwd']==0){
		message('系统管理员已禁止您自行修改登录密码。\n 请联系系统管理员！');
	}
		
	//登录密码
	if($_POST["oldpass"] && $_POST["newpass2"]  && $_GET["action"]=="pass" )
	{
		//验证
		if(!preg_match("/^[A-Za-z0-9]{6,12}$/",$_POST["oldpass"]))
		{  
			message("登录密码格式错误!");  
		}
		if(!preg_match("/^[A-Za-z0-9]{6,12}$/",$_POST["newpass2"]))
		{  
			message("登录密码格式错误!");  
		}
		$result=user::update_pwd($_SESSION["uid"],$_POST["oldpass"],$_POST["newpass2"],'password');
		if($result['status'])
		{
			echo "<script>alert('登陆密码修改成功');</script>";
			session_destroy();
			echo "<script>top.location.href='/';</script>";
		}else
		{
			echo "<script>alert('".$result['msg']."');</script>";
		}
	}
	
	//取款密码
	if($_POST["oldmoneypass"] && $_POST["newmoneypass2"]  && $_GET["action"]=="moneypass" )
	{
		//验证
		if(!preg_match("/^[0-9]{4}$/",$_POST["oldmoneypass"]))
		{  
			message("取款密码格式错误!");  
		}
		if(!preg_match("/^[0-9]{4}$/",$_POST["newmoneypass2"]))
		{  
			message("取款密码格式错误!");  
		}
		if($_SESSION['shiwan']==2)
		{
			exit("<script language=javascript>alert('试玩账号没有取款密码，请使用正式账号！');history.go(-1);</script>");
		}

		$data['qk_pwd']=$_POST["newmoneypass2"];
		$result=M('k_user',$db_config)->where("uid='$_SESSION[uid]' and qk_pwd='$_POST[oldmoneypass]'")->find();
		if($result)
		{
			$row=M('k_user',$db_config)->where("uid='$_SESSION[uid]' and qk_pwd='$_POST[oldmoneypass]'")->update($data);
			if($row)
			{
				echo "<script>alert('取款密码修改成功');</script>";
			}
			else
			{
				echo "<script>alert('原取款密码不能与修改后的取款密码一致');</script>";
			}
		}
		else
		{
			echo "<script>alert('原取款密码错误');</script>";
		}
	}

	/*
if($_POST["oldmoneypass"] && $_POST["newmoneypass2"]  && $_GET["action"]=="dlpass" ){

		if($_SESSION['shiwan']==2){
			exit("<script language=javascript>alert('试玩账号不能修改代理密码，请使用正式账号！');history.go(-1);</script>");
		}	
		//验证
		if(!preg_match("/^[A-Za-z0-9]{6,15}$/",$_POST["oldmoneypass"])){  
	          message("代理密码格式错误!");  
	    }
	    if(!preg_match("/^[A-Za-z0-9]{6,15}$/",$_POST["newmoneypass2"])){  
	          message("代理密码格式错误!");  
	    }
		if(user::update_pwd($_SESSION["uid"],$_POST["oldmoneypass"],$_POST["newmoneypass2"],'dl_pwd')){
			echo "<script>alert('代理密码修改成功');</script>";
		}else{
			echo "<script>alert('代理密码修改失败');</script>";
		}
}		

*/
?>

  <html lang="en">
    <head>
    	<meta charset="UTF-8">
    	<title>Document</title>
		<link rel="stylesheet" href="../public/css/bank.css" type="text/css">
		<style type="text/css">
			H3{text-indent: 2em;}
			body{font-size:12px}
			.m_bc_ed{text-align:right;height:30px;line-height:30px}
			.tab_bank{font-size:12px}
			.star{margin-left:10px}
			.btn_001{cursor: pointer;margin: 0 1px 0 0;width: 85px;height: 26px;border: none;padding-top: 2px;color: #FFF;font-weight: bold;background: #3D3D3D url(../public/images/order_btn.gif) no-repeat 0 -80px;}
			select,input{height:20px;line-height:20px;}
		</style>
		<link rel="stylesheet" href="../public/css/index_main.css" />
		<link rel="stylesheet" href="../public/css/standard.css" />
		<script src="../public/js/jquery-1.8.3.min.js"></script>
    </head>
	<body style="BACKGROUND: url(../public/images/content_bg.jpg) repeat-y left top;" >
		<div id="MAMain" style="width:767px">
			<div id="MACenter-content">
				<div id="MACenterContent">
					<div id="MNav">
						<span class="mbtn">基本资讯</span>
						<div class="navSeparate"></div>
						<a target="k_memr" class="mbtn" href="sport.php">投注资讯</a>
					</div>
					<div id="bank_content" style="width:600px;margin-top:20px;">
						<div class="main_nr">
							<FORM  target="k_memr" onSubmit="return check_submit1();" method=post name=form1 action="?action=pass">
								<h3 id="pay_title " class="star">修改登录密码:</h3>
								<table width="90%" border="0" cellspacing="0" cellpadding="10" class="tab_bank">
									<tbody>
									<TR>
										<TD width=100 align=right class="m_bc_ed">原登录密码：</TD>
										<TD class=hong width=150 align=left><INPUT id="oldpass" class=input_150 maxLength=12 type=password name=oldpass></TD>
										<TD id="oldpass_txt" class=hong align=left>&nbsp;</TD>
									</TR>
									<TR>
										<TD align=right class="m_bc_ed">新登录密码：</TD>
										<TD class=hong align=left><INPUT id="newpass" class=input_150 maxLength=12 type=password name=newpass></TD>
										<TD id="newpass_txt" class=hong align=left>* <SPAN class=lan>请输入6-12位新密码</SPAN></TD>
									</TR>
									<TR>
										<TD align=right class="m_bc_ed">确认新登录密码：</TD>
										<TD class=hong align=left><INPUT id="newpass2" class=input_150 maxLength=12 type=password name=newpass2></TD>
										<TD id="newpass2_txt" class=hong align=left>* <SPAN class=lan>重复输入一次新密码</SPAN></TD>
									</TR>
									<tr align="left">
										<td></td>
										<td colspan="2">
										<input id="submit" value="确认修改" type="submit" name="submit" class="btn_001">
										&nbsp;&nbsp;&nbsp;
										<input type="reset" value="重置" class="btn_001">
										&nbsp;&nbsp;&nbsp; </td>
									</tr>
									</tbody>
								</table>
							</form>
						</div>
					</div>

					<div id="bank_content" style="width:600px;margin-top:20px;">
						<div class="main_nr">
							<!-- onSubmit="return check_submit_money();" -->
							<FORM  target="k_memr"  method=post name=form1 action="?action=moneypass" onSubmit="return check_submit2();">
								<h3 id="pay_title " class="star">修改取款密码:</h3>
								<table width="90%" border="0" cellspacing="0" cellpadding="10" class="tab_bank">
									<tbody>
										<TR>
											<TD width=100 align=right class="m_bc_ed">原取款密码：</TD>
											<TD class=hong width=150 align=left><INPUT id="oldmoneypass" class=input_150 maxLength=4 type=password name=oldmoneypass></TD>
											<TD id="oldmoneypass_txt" class=hong align=left>&nbsp;</TD>
										</TR>
										<TR>
										<TR>
											<TD align=right class="m_bc_ed">新取款密码：</TD>
											<TD class=hong align=left><INPUT id="newmoneypass" class=input_150 maxLength=4 type=password name=newmoneypass></TD>
											<TD id="newmoneypass_txt" class=hong align=left>* <SPAN class=lan>请输入4位数字新密码</SPAN></TD>
										</TR>
										<TR>
											<TD align=right class="m_bc_ed">确认取款密码：</TD>
											<TD class=hong align=left><INPUT id="newmoneypass2" class=input_150 maxLength=4 type=password name=newmoneypass2></TD>
											<TD id="newmoneypass2_txt" class=hong align=left>* <SPAN class=lan>重复输入一次新密码</SPAN></TD>
										</TR>

										<tr align="left">
											<td></td>
											<td colspan="2">
											<input id="submit" value=确认修改 type=submit name=submit class="btn_001">
											&nbsp;&nbsp;&nbsp;
											<input type="reset" value="重置" class="btn_001">
											&nbsp;&nbsp;&nbsp; </td>
										</tr>
									</tbody>
								</table>
							</form>
						</div>
					</div>

					<?php
					$display = 'none';
					if($_COOKIE['is_daili']) $display = '';
					?>		  
					<!--<div id="bank_content" style="width:600px;margin-top:20px;display:<?=$display?>;">
						<div class="main_nr" style="display:<?=$display?>;">
							<FORM  target="k_memr" onSubmit="return check_submit3();" method=post name=form1 action="?action=dlpass">
								<h3 id="pay_title " class="star" style="display:<?=$display?>;">修改代理密码:</h3>
								<table width="90%" border="0" cellspacing="0" cellpadding="10" class="tab_bank">
									<tbody>
										<TR style="display:<?=$display?>;">
										<TD align=right  width=100 class="m_bc_ed">原代理密码：</TD>
											<TD class=hong align=left width=150><INPUT id="olddlpass" class=input_150 maxLength=10 type=password name=oldmoneypass></TD>
											<TD id="olddlpass_txt" class=hong align=left>&nbsp;</TD>
										</TR>
										<TR style="display:<?=$display?>;">
											<TD align=right class="m_bc_ed">新代理密码：</TD>
											<TD class=hong align=left><INPUT id="newdlpass" class=input_150 maxLength=10 type=password name=newmoneypass></TD>
											<TD id=newdlpass_txt class=hong align=left>* <SPAN class=lan>请输入6-15位新密码</SPAN></TD>
										</TR>
										<TR style="display:<?=$display?>;">
											<TD align=right class="m_bc_ed">确认代理密码：</TD>
											<TD class=hong align=left><INPUT id="newdlpass2" class=input_150 maxLength=10 type=password name=newmoneypass2></TD>
											<TD id="newdlpass2_txt" class=hong align=left>* <SPAN class=lan>重复输入一次新密码</SPAN></TD>
										</TR>
										<tr align="left" style="display:<?=$display?>;">
											<td></td>
											<td colspan="2">
											<input id="submit" value="确认修改" type=submit name=submit class="btn_001">
											&nbsp;&nbsp;&nbsp;
											<input type="reset" value="重置" class="btn_001">
											&nbsp;&nbsp;&nbsp; </td>
										</tr>
									</tbody>
								</table>
							</form>
						</div>
					</div>-->
				</div>
			</div>
		</div>
	</body>
</html>
	<script>
		$(function(){
			//验证非法字符(@)
			var oldval;
			$("input").keydown(function(event) {
				oldval = $(this).val();
			});
			$("input").keyup(function(event) {
				newval  = $(this).val();   
				var reg = /([`~!@#$%^&*()_+<>?:"{},\/;'[\]])/ig; 
				var reg1 = /([·~！#@￥%……&*（）——+《》？：“{}，。\、；’‘【\】])/ig;
				$(this).val(newval.replace(reg, "")); 
				$(this).val(newval.replace(reg, ""));   
			});
		})

		function check_submit1(){
			var oldpass=$("#oldpass").val();
			var newpass=$("#newpass").val();
			var newpass2=$("#newpass2").val(); 
			if(oldpass && newpass && newpass2){
				if(oldpass.length<6 || oldpass.length>15){
					alert("原登录密码格式不正确！");return false;
				}
				if(newpass.length<6 || newpass.length>15){
					alert("新登录密码格式不正确！");return false;
				}
				if(newpass2.length<6 || newpass2.length>15){
					alert("确认新登录密码格式不正确！");return false;
				}else if(newpass!=newpass2){
					alert("确认登录密码不正确！");return false;
				}
			}else{
				alert("请填写完整表单！");return false;
			}		
		}
		function check_submit2(){
			var oldmoneypass=$("#oldmoneypass").val();
			var newmoneypass=$("#newmoneypass").val();
			var newmoneypass2=$("#newmoneypass2").val(); 
			if(oldmoneypass && newmoneypass && newmoneypass2){
				if(oldmoneypass.length!=4||!(/^[0-9]*$/g.test(oldmoneypass))){
					alert("原取款密码格式不正确2！");return false;
				}
				if(newmoneypass.length!=4||!(/^[0-9]*$/g.test(newmoneypass))){
					alert("新取款密码格式不正确3！");return false;
				}
				if(newmoneypass2.length!=4||!(/^[0-9]*$/g.test(newmoneypass2))){
					alert("确认新取款密码格式不正确！");return false;
				}else if(newmoneypass!=newmoneypass2){
					alert("确认取款密码不正确！");return false;
				}
			}else{
				alert("请填写完整表单！");return false;
			}	
		}
		function check_submit3(){
			var olddlpass=$("#olddlpass").val();
			var newdlpass=$("#newdlpass").val();
			var newdlpass2=$("#newdlpass2").val(); 
			if(olddlpass && newdlpass && newdlpass2){
				if(olddlpass.length<6 || olddlpass.length>15){
					alert("原代理密码格式不正确！");return false;
				}
				if(newdlpass.length<6 || newdlpass.length>15){
					alert("新代理密码格式不正确！");return false;
				}
				if(newdlpass2.length<6 || newdlpass.length>15){
					alert("确认新代理密码格式不正确！");return false;
				}else if(newdlpass!=newdlpass2){
					alert("确认代理密码不正确！");return false;
				}
			}else{
				alert("请填写完整表单！");return false;
			}	
		}
	</script>
