<?php
	include_once("../../include/config.php");
	include_once("../../common/login_check.php");
	include_once("../../common/function.php");
	include_once("../../class/user.php");
//版权查询
$copy_right = user::copy_right(SITEID,INDEX_ID);
	$uid     = $_SESSION['uid'];

	$userinfo=user::getinfo($_SESSION["uid"]);
	if($_GET['into']=='true'){
		$data["pay_address"]=	$_POST["province"]."-".$_POST["city"];
		$data["pay_card"] = $_POST["pay_card"];
		$data['pay_num'] = $_POST['pay_num'];
		if(preg_match("/([`~!@#$%^&*()_+<>?:\"{},\/;'[\]·~！#￥%……&*（）——+《》？：“{}，。\、；’‘【\】])/",$_POST["pay_num"])){
			
			message('银行卡号格式错误！','hk_money.php');
		}
		$result = M('k_user',$db_config)->field('pay_num')->where(" pay_num= '$_POST[pay_num]' and site_id = '".SITEID."'")->find();
		if($result['pay_num']){
			message('该银行卡已经绑定到其他会员！','hk_money.php');
		}
		if (M('k_user',$db_config)->where("uid='".$uid."'")->update($data)) {
			echo '<script>alert("资料修改成功!")</script>';
			echo '<script>top.location.href="bank.php";</script>';exit;
			
			//message('添加成功','bank.php');  
		}else{
			message('对不起，由于网络堵塞原因。\\n您提交的汇款信息失败，请您重新提交。','hk_money.php');
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Document</title>
		<script src="../public/js/jquery-1.8.3.min.js"></script>
		<script src="../public/js/PCASClass.js"></script>
		<link rel="stylesheet" href="../public/css/bank.css" type="text/css">
		<link rel="stylesheet" href="../public/css/index_main.css" />
		<link rel="stylesheet" href="../public/css/standard.css" />
	</head>
	<div id="bank_header">
		<div id="bank_logo"></div>
	</div>
	<body style="">
		<div id="MAMain" style="width:100%">
			<div id="MACenter-content" style="width:800px;margin:0 auto;">
				<div id="MACenterContent">
					<div id="bank_content" style="width:600px; height:400px;">
						<div class="main_nr">
							<FORM id="form1" method="post" name="form1" action="?into=true">
								<h3 id="pay_title " class="star">为保护您的资金安全﹐请确实填写您的出款银行资料﹐以免有心人士窃取﹐谢谢!</h3>
								<table width="90%" border="0" cellspacing="0" cellpadding="0" class="tab_bank">
									<tbody>
										<tr>
											<td height="25" align="center" class="m_bc_ed" width="30%">真实姓名 : </td>
											<td colspan="2"><?=$userinfo["pay_name"]?></td>
										</tr>
										<tr>
											<td height="25" align="center" class="m_bc_ed" width="30%">取款密码 : </td>
											<td colspan="2"><?=$userinfo["qk_pwd"]?></td>
										</tr>
										<tr>
											<td height="25" align="center" class="m_bc_ed">出款银行： </td>
											<td>
												<SELECT id="IntoBank" name="pay_card" class="txt4_3" style="width:131px"> 
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
												</SELECT>
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
											<td><select name="city" style="width:131px" id="city"></td>
											<td><div class="Validform_checktip">请选择您所在城市！</div></td>
										</tr>
										<tr>
											<td height="25" align="center" class="m_bc_ed"><span class="star">*</span>银行账号： </td>
											<td colspan="2"><INPUT id="pay_num" class="txt4_3" onkeyup="value=this.value.replace(/\D+/g,'')" type="text" name="pay_num" ></td>
										</tr>
										<tr align="center">
											<td colspan="3" class="table_bg1">
											<input value=提交信息 onclick="SubInfo()" type="button" name=SubTran id="SubTran" class="btn_001">
											&nbsp;&nbsp;&nbsp;
											<input type="reset" value="重置" class="btn_001">
											&nbsp;&nbsp;&nbsp; </td>
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
				</div>
			</div>
			<div id="bank_footer" style="text-align: center;background:none;">
				<p>Copyright © <?=$copy_right['copy_right']?> Reserved</p>
			</div>
		</div>
	</body>
</html>

	<script>
		new PCAS("province","city");
	</script>
	<style type="text/css">
		H3{text-indent: 2em;}
		body{font-size:12px}
		.m_bc_ed{text-align:right;height:30px;line-height:30px}
		.tab_bank{font-size:12px}
		.star{margin-left:10px}
		.btn_001{cursor: pointer;margin: 0 1px 0 0;width: 85px;height: 26px;border: none;padding-top: 2px;color: #FFF;font-weight: bold;background: #3D3D3D url(../public/images/order_btn.gif) no-repeat 0 -80px;}
		select,input{height:20px;line-height:20px;}
	</style>

	<SCRIPT language=javascript type=text/javascript>
	function SubInfo(){
		var IntoBank=$('#IntoBank').val();
		if(IntoBank==''){
			alert('请选择出款银行');
			$('IntoBank').focus();
			return false;
		}
		var account = $("#pay_num").val(); 
		var reg = /^([0-9]{16,19})$/g; // 以19位数字开头，以19位数字结尾 
		if(!reg.test(account)) 
		{ 
			alert("格式错误，银行卡号为16-19位纯数字！");
			return false;  
		} 
		if($('#province').val()==''){
			alert('请选择省份！');
			return false;
		}
		if($('#city').val()==''){
			alert('请选择城市！');
			return false;
		}
		$('#form1').submit(); 
	}

	//是否是中文
	function isChinese(str){
		return /[\u4E00-\u9FA0]/.test(str);
	}
	</SCRIPT>