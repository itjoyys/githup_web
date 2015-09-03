<?php 
	include_once("../../include/config.php");
	include_once("../common/member_config.php");
	include_once("../../common/login_check.php");
	include_once("../../common/function.php");
	include_once("../../class/user.php");

//版权查询
$copy_right = user::copy_right(SITEID,INDEX_ID);

	//您的银行账号已添加
	if($_SESSION['shiwan']==2){
		exit("<script language=javascript>alert('试玩账号不能修改银行卡资料，请使用正式账号！');history.go(-1);</script>");
	}


	$uid     = $_SESSION['uid'];
	$userinfo=user::getinfo($_SESSION["uid"]);
	if($_POST["action"]=="add_card"){
		$user = M('k_user',$db_config);
		if(!$_POST["pay_card"] || !$_POST["pay_num"] || !$_POST["province"] || !$_POST["action"]=="add_card" || !$_POST["city"] ){
			echo "<script>alert('请填写完整表单资料！');history.go(-1);</script>";
		}else{
			if(preg_match("/([`~!@#$%^&*()_+<>?:\"{},\/;'[\]·~！#￥%……&*（）——+《》？：“{}，。\、；’‘【\】])/",$_POST["pay_num"])){

				message('银行卡号格式错误！','set_card.php');
			}
			$result = M('k_user',$db_config)->field('pay_num')->where(" pay_num= '$_POST[pay_num]' and site_id = '".SITEID."'")->find();
			if($result['pay_num']){
				message('该银行卡已经绑定到其他会员！','set_card.php');
			}

			$data["pay_address"]=	$_POST["province"]."-".$_POST["city"];
			$data["pay_card"] = bank_type($_POST["pay_card"]);
			$data['pay_num'] = $_POST['pay_num'];

			if(user::update_paycard($uid,$_POST["pay_card"],$_POST["pay_num"],$data["pay_address"],$userinfo["pay_name"],$userinfo["username"])){
				echo "<script>alert('资料修改成功');window.location.href='./show.php'</script>";
			}else{
				echo "<script>alert('资料修改失败');</script>";
			}
		}
	}
?>
<html>
	<head>
		<title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link href="./css/jquery-ui.css" type="text/css" rel="stylesheet">
		<link type="text/css" href="./css/standard.css" rel="stylesheet">
		<link rel="stylesheet" href="./css/template.css" type="text/css">
		<link rel="stylesheet" href="./css/easydialog.css" type="text/css">
		<link rel="stylesheet" href="./css/bank.css" type="text/css">
		<script src="../public/js/jquery-1.8.3.min.js"></script>
		<script src="../public/js/PCASClass.js"></script>
	</head>
	<body id="bank_body">
		<div id="bank_header">
			<div id="bank_logo"></div>
		</div>
		<div id="bank_content">
			<div class="main_nr">
				<form method="post" name="withdrawal_form" action="" id="withdrawal_form" onsubmit="return checksubmit();">
					<h3 id="pay_title " class="star">为保护您的资金安全﹐请确实填写您的出款银行资料﹐以免有心人士窃取﹐谢谢!</h3>
					<table width="90%" border="0" cellspacing="0" cellpadding="0" class="tab_bank">
						<tbody>
							<tr>
								<td height="25" align="center" class="m_bc_ed" width="30%">真实姓名 : </td>
								<td colspan="2"><?=$userinfo['pay_name']?></td>
							</tr>
							<tr>
								<td height="25" align="center" class="m_bc_ed">取款密码 : </td>
								<td colspan="2"><?=$userinfo['qk_pwd']?></td>
							</tr>			
							<tr>
								<th height="25" align="center" class="m_bc_ed" width="30%"><span class="star">*</span>出款银行:</th>
								<td>
									<select name="pay_card" class="txt4_3" style="width:131px">
										<option value="">請選擇取款銀行</option>
										<option value="1">中國銀行</option>
										<option value="2">中國工商銀行</option>
										<option value="3">中國建設銀行</option>
										<option value="4">中國招商銀行</option>
										<option value="5">中國民生銀行</option>
										<option value="7">中國交通銀行</option>
										<option value="8">中國郵政銀行</option>
										<option value="9">中國农业銀行</option>
										<option value="10">華夏銀行</option>
										<option value="11">浦發銀行</option>
										<option value="12">廣州銀行</option>
										<option value="13">北京銀行</option>
										<option value="14">平安銀行</option>
										<option value="15">杭州銀行</option>
										<option value="16">溫州銀行</option>
										<option value="17">中國光大銀行</option>
										<option value="18">中信銀行</option>
										<option value="19">浙商銀行</option>
										<option value="20">漢口銀行</option>
										<option value="21">上海銀行</option>
										<option value="22">廣發銀行</option>
										<option value="23">农村信用社</option>
										<option value="24">深圳发展银行</option>
										<option value="25">渤海银行</option>
										<option value="26">东莞银行</option>
										<option value="27">宁波银行</option>
										<option value="28">东亚银行</option>
										<option value="29">晋商银行</option>
										<option value="30">南京银行</option>
										<option value="31">广州农商银行</option>
										<option value="32">上海农商银行</option>
										<option value="33">珠海农村信用合作联社</option>
										<option value="34">顺德农商银行</option>
										<option value="35">尧都区农村信用联社</option>
										<option value="36">浙江稠州商业银行</option>
										<option value="37">北京农商银行</option>
										<option value="38">重庆银行</option>
										<option value="39">广西农村信用社</option>
										<option value="40">江苏银行</option>
										<option value="41">吉林银行</option>
										<option value="42">成都银行</option>
										<option value="50">兴业银行</option>
										<option value="100">支付宝</option>
									</select>
								</td>
								<td width="400"></td>
							</tr>
							<tr>
								<th height="25" align="center" class="m_bc_ed"><span class="star">*</span>省份:</th>
								<td><select name="province" style="width:131px" id="province"></select></td>
								<td><div class="Validform_checktip">请选择您所在省份！</div></td>
							</tr>
							<tr>
								<th height="25" align="center" class="m_bc_ed"><span class="star">*</span>城市:</th>
								<td><select name="city" style="width:131px" id="city"></select></td>
								<td><div class="Validform_checktip">请选择您所在城市！</div></td>
							</tr>	
							<tr>
								<th height="25" align="center" class="m_bc_ed"><span class="star">*</span>银行帐号:</th>		
								<td><input class="txt4_3" datatype="n" nullmsg="银行帐号不能为空！" errormsg="银行帐号只能为数字" name="pay_num" id="pay_num" onkeyup="value=this.value.replace(/\D+/g,'')"  onkeydown="" value="" style="width:131px"></td>
								<td><div class="Validform_checktip">请输入您的银行帐号！</div></td>
							</tr>
							<input type="hidden" name="action" value="add_card" />
							<tr align="center">
								<td colspan="3" class="table_bg1">
									<input value="确定" type="submit" id="btnOk" class="btn_001">&nbsp;&nbsp;&nbsp;
									<input type="reset" value="重置" id="btnReset" class="btn_001"> &nbsp;&nbsp;&nbsp;
								</td>
							</tr>
						</tbody>
					</table> 
				</form>
			</div>
			<div id="note">
				<h1>备注：</h1>
				<p>1.标记有&nbsp;<span class="star">*</span>&nbsp;者为必填项目。</p>
				<p>2.手机与取款密码为取款金额时的凭证,请会员务必填写详细资料。</p>
			</div>
		</div>
		<div id="bank_footer" style="text-align: center;">
			<p>Copyright © <?=$copy_right['copy_right']?> Reserved</p>
		</div>
	</body>
</html>

<script language="JavaScript" type="text/JavaScript">
	$(function(){
		//验证非法字符(@)
		var oldval;
		$("input").keydown(function(event) {
			oldval = $(this).val();
		});
		$("input").keyup(function(event) {
			var newval  = $(this).val();   
			var reg = /([`~!@#$%^&*()_+<>?:"{},\/;'[\]])/ig; 
			var reg1 = /([\·\~\！\#\@\￥\%\…\…\&\*\（\）\—\—\+\《\》\？\：\“\{\}\，\。\、\；\’\‘\【\】])/ig;
			$(this).val(newval.replace(reg, "")); 
			$(this).val(newval.replace(reg, ""));   
		});
	}) 

	function checksubmit(){
		if($("select[name='pay_card']").val()=="" || $("#province").val()=="" || $("#city").val()=="" || $("#pay_num").val()==""){
			alert("请填写完整表单资料！");
			return false;
		}


		var account = $("#pay_num").val(); 
		var reg = /^([0-9]{16,19})$/g; // 以19位数字开头，以19位数字结尾 
		if( !reg.test(account)) 
		{ 
			alert("格式错误，银行卡号为16-19位纯数字！");
			return false;
		} 
	} 
	new PCAS("province","city");
</script>

<style type="text/css">
	H3{text-indent: 2em;}
	body{font-size:12px}
	.m_bc_ed{text-align:right;height:30px;line-height:30px}
	.tab_bank{font-size:12px}
	.star{margin-left:10px}
	.btn_001{cursor: pointer;margin: 0 1px 0 0;width: 85px;height: 26px;border: none;padding-top: 2px;color: #FFF;font-weight: bold;background: #3D3D3D url(./images/order_btn.gif) no-repeat 0 -80px;}
	select,input{height:20px;line-height:20px;}
</style>