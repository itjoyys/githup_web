<?php 
	include_once("../../include/config.php");
	include_once("../../common/login_check.php");
	include_once("../../class/user.php");

	$userinfo=user::getinfo($_SESSION["uid"]);
    $uid=$userinfo['uid'];
	if(!empty($_POST['OK'])){
		$oldpw=$_POST["oldPW1"].$_POST["oldPW2"].$_POST["oldPW3"].$_POST["oldPW4"];
		$newpw=$_POST["newPW1"].$_POST["newPW2"].$_POST["newPW3"].$_POST["newPW4"];
		if(strlen($newpw) != 4){
			exit("<script>alert('密码格式不正确！');history.go(-1);</script>");
		}
		if($userinfo['qk_pwd']!=$oldpw){
			exit("<script>alert('旧取款密码不正确！');history.go(-1);</script>");
		}else{
			$data['qk_pwd'] = $newpw;
            $d=M("k_user",$db_config);
			$data1 = $d->where("uid = '".$uid."' and site_id='".SITEID."'")->update($data);
     
			if($data1){
				echo "<script>alert('修改取款密码成功');window.close();</script>";
			}else{
				echo "<script>alert('修改取款密码失败！');history.go(-1);</script>";
			}
		}
	}
 ?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title> 修改密码 </title>
		<link href="./css/jquery-ui.css" type="text/css" rel="stylesheet">
		<link type="text/css" href="./css/standard.css" rel="stylesheet">
		<link rel="stylesheet" href="./css/template.css" type="text/css">
		<link rel="stylesheet" href="./css/easydialog.css" type="text/css">
		<link rel="stylesheet" href="./css/bank.css" type="text/css">
	</head>
	<body id="chgPasswd" oncontextmenu="window.event.returnValue=false">
	    <div id="memAccTable">
		    <form name="chgFORM" method="post" action="">
		        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
		            <thead>
		                <tr>
		                    <td class="chgPasswdTitle" colspan="2">请输入密码</td>
		                </tr>
		            </thead>
		            <tbody>
		                <tr>
		                    <td class="tipDwText">旧密码</td>
		                    <td>
		                        <select name="oldPW1">
		                            <option label="-" value="-">-</option>
									<option label="0" value="0">0</option>
									<option label="1" value="1">1</option>
									<option label="2" value="2">2</option>
									<option label="3" value="3">3</option>
									<option label="4" value="4">4</option>
									<option label="5" value="5">5</option>
									<option label="6" value="6">6</option>
									<option label="7" value="7">7</option>
									<option label="8" value="8">8</option>
									<option label="9" value="9">9</option>

		                        </select>
		                        <select name="oldPW2">
		                            <option label="-" value="-">-</option>
									<option label="0" value="0">0</option>
									<option label="1" value="1">1</option>
									<option label="2" value="2">2</option>
									<option label="3" value="3">3</option>
									<option label="4" value="4">4</option>
									<option label="5" value="5">5</option>
									<option label="6" value="6">6</option>
									<option label="7" value="7">7</option>
									<option label="8" value="8">8</option>
									<option label="9" value="9">9</option>

		                        </select>
		                        <select name="oldPW3">
		                            <option label="-" value="-">-</option>
									<option label="0" value="0">0</option>
									<option label="1" value="1">1</option>
									<option label="2" value="2">2</option>
									<option label="3" value="3">3</option>
									<option label="4" value="4">4</option>
									<option label="5" value="5">5</option>
									<option label="6" value="6">6</option>
									<option label="7" value="7">7</option>
									<option label="8" value="8">8</option>
									<option label="9" value="9">9</option>

		                        </select>
		                        <select name="oldPW4">
		                            <option label="-" value="-">-</option>
									<option label="0" value="0">0</option>
									<option label="1" value="1">1</option>
									<option label="2" value="2">2</option>
									<option label="3" value="3">3</option>
									<option label="4" value="4">4</option>
									<option label="5" value="5">5</option>
									<option label="6" value="6">6</option>
									<option label="7" value="7">7</option>
									<option label="8" value="8">8</option>
									<option label="9" value="9">9</option>

		                        </select>
		                    </td>
		                </tr>
		                <tr>
		                    <td class="tipDwText">新密码</td>
		                    <td>
		                        <select name="newPW1">
		                            <option label="-" value="">-</option>
									<option label="0" value="0">0</option>
									<option label="1" value="1">1</option>
									<option label="2" value="2">2</option>
									<option label="3" value="3">3</option>
									<option label="4" value="4">4</option>
									<option label="5" value="5">5</option>
									<option label="6" value="6">6</option>
									<option label="7" value="7">7</option>
									<option label="8" value="8">8</option>
									<option label="9" value="9">9</option>

		                        </select>
		                        <select name="newPW2">
		                            <option label="-" value="">-</option>
									<option label="0" value="0">0</option>
									<option label="1" value="1">1</option>
									<option label="2" value="2">2</option>
									<option label="3" value="3">3</option>
									<option label="4" value="4">4</option>
									<option label="5" value="5">5</option>
									<option label="6" value="6">6</option>
									<option label="7" value="7">7</option>
									<option label="8" value="8">8</option>
									<option label="9" value="9">9</option>
		                        </select>
		                        <select name="newPW3">
		                            <option label="-" value="">-</option>
									<option label="0" value="0">0</option>
									<option label="1" value="1">1</option>
									<option label="2" value="2">2</option>
									<option label="3" value="3">3</option>
									<option label="4" value="4">4</option>
									<option label="5" value="5">5</option>
									<option label="6" value="6">6</option>
									<option label="7" value="7">7</option>
									<option label="8" value="8">8</option>
									<option label="9" value="9">9</option>
		                        </select>
		                        <select name="newPW4">
		                            <option label="-" value="">-</option>
									<option label="0" value="0">0</option>
									<option label="1" value="1">1</option>
									<option label="2" value="2">2</option>
									<option label="3" value="3">3</option>
									<option label="4" value="4">4</option>
									<option label="5" value="5">5</option>
									<option label="6" value="6">6</option>
									<option label="7" value="7">7</option>
									<option label="8" value="8">8</option>
									<option label="9" value="9">9</option>
		                        </select>
		                    </td>
		                </tr>
		            </tbody>
		            <tfoot>
		                <tr>
		                    <td colspan="2">
		                        <input type="submit" name="OK" class="btn_001" value="确认">
		                        <input type="button" name="cancel" class="btn_001" value="取消" onclick="window.close();">
		                        <input type="hidden" name="action" value="1">
		                        <input type="hidden" name="uid" value="p4c3d455z1o5rrkkz52vbbmz3cr1rz0142">
		                    </td>
		                </tr>
		            </tfoot>
		        </table>
		    </form>
	    </div>
	</body>
</html>
<style>
/*--- 按钮样式 ---*/
.btn_001{
	cursor: pointer;
	margin: 0 1px 0 0;
	width: 85px;
	height: 26px;
	border: none;
	padding-top: 2px;
	color: #FFF;
	font-weight: bold;
	background: #3D3D3D url(/cl/tpl/baoying/images/member/order_btn.gif) no-repeat 0 -80px;
}

</style>