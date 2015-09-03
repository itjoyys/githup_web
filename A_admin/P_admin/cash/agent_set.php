<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");


/**
*0-1 代表需要  且必填
*0-0 代表需要  不必填
**/

$data=M('k_user_agent_config',$db_config)->where("site_id = '".SITEID."'")->find();
if(empty($data)){
	$info['site_id']=SITEID;
	$k_user_agent_config=M('k_user_agent_config',$db_config)->add($info);
	if($k_user_agent_config){
		$data=M('k_user_agent_config',$db_config)->where("site_id = '".SITEID."'")->find();
	}
}
if($_POST){
	$_POST['is_zh_name_true'] = empty($_POST['is_zh_name_true']) ? '0' : $_POST['is_zh_name_true'];
	$_POST['is_email_true'] = empty($_POST['is_email_true']) ? '0' : $_POST['is_email_true'];
	$_POST['is_en_name_true'] = empty($_POST['is_en_name_true']) ? '0' : $_POST['is_en_name_true'];
	$_POST['is_card_true'] = empty($_POST['is_card_true']) ? '0' : $_POST['is_card_true'];
	$_POST['is_qq_true'] = empty($_POST['is_qq_true']) ? '0' : $_POST['is_qq_true'];
	$_POST['is_payname_true'] = empty($_POST['is_payname_true']) ? '0' : $_POST['is_payname_true'];
	$_POST['is_phone_true'] = empty($_POST['is_phone_true']) ? '0' : $_POST['is_phone_true'];
	$_POST['from_url_form_true'] = empty($_POST['from_url_form_true']) ? '0' : $_POST['from_url_form_true'];
	$_POST['other_method_form_true'] = empty($_POST['other_method_form_true']) ? '0' : $_POST['other_method_form_true'];

	$map['is_daili'] = $_POST['is_on'];
	$map['from_url_form'] = $_POST['from_url_form'].'-'.$_POST['from_url_form_true'];
	$map['other_method_form'] = $_POST['other_method_form'].'-'.$_POST['other_method_form_true'];
	$map['is_zh_name'] = $_POST['is_zh_name'].'-'.$_POST['is_zh_name_true'];
	$map['is_email'] = $_POST['is_email'].'-'.$_POST['is_email_true'];
	$map['is_en_name'] = $_POST['is_en_name'].'-'.$_POST['is_en_name_true'];
	$map['is_card'] = $_POST['is_card'].'-'.$_POST['is_card_true'];
	$map['is_qq'] = $_POST['is_qq'].'-'.$_POST['is_qq_true'];
	$map['is_payname'] = $_POST['is_payname'].'-'.$_POST['is_payname_true'];
	$map['is_phone'] = $_POST['is_phone'].'-'.$_POST['is_phone_true'];
	$map['site_id'] = SITEID;

	if(!empty($_POST['id'])){
		if(M('k_user_agent_config',$db_config)->where("id=".$_POST['id'])->update($map)){
			message('修改成功','agent_set.php');
		}
	}else if(isset($_POST['is_on'])){
		if(M('k_user_agent_config',$db_config)->add($map)){
		 	message('设定成功','agent_set.php');
		}
	}
}

?>

<?php require("../common_html/header.php");?>
<body>
<div id="con_wrap">
  <div class="input_002">會員端新增代理設定</div>
  <div class="con_menu">
  <a href="agent_examine.php">代理申請管理</a>
  </div>
</div>

<div class="content" style="width:650px;clear: both;">
<form action="" method="post">
	<input type="hidden" name="id" value="<?=$data['id'];?>">

	<table width="99%" class="m_tab">
		<tbody><tr class="m_title">
			<td height="27" class="table_bg" colspan="2" align="middle">會員端新增代理設定</td>
		</tr>

		<tr>
			<td class="table_bg1" align="center">是否啟用會員端新增代理</td>
			<td>
				<input value="1" <?php radio_check(1,$data['is_daili'])?>type="radio" name="is_on">啟用&nbsp;&nbsp;
				<input value="0" <?php radio_check(0,$data['is_daili'])?> type="radio" name="is_on">停用&nbsp;&nbsp;
			</td>
		</tr>
	</tbody></table>
	<table width="99%" class="m_tab">
		<tbody><tr class="m_title">
			<td height="27" class="table_bg" colspan="2" align="middle">會員端新增代理商 驗證規則</td>
		</tr>

		<script>
			//隐藏是否必填
			function is_check(name){
				var c='#'+name;
				$(c).css('display','none');
			}

			//显示是否必填
			function is_check2(name){
				var c='#'+name;
				$(c).css('display','');
			}
		</script>

		<tr>
			<td class="table_bg1" align="center">中文昵稱</td>
			<td>
				<input value="0" <?php if($data['is_zh_name'] == '0-1' || $data['is_zh_name'] == '0-0'){ echo "checked='checked'"; } ?> type="radio" name="is_zh_name" onclick="is_check2(this.name)">需&nbsp;&nbsp;
				<input value="1" <?php if($data['is_zh_name'] == '1-0' || $data['is_zh_name'] == '1-1'){ echo "checked='checked'"; } ?> type="radio" name="is_zh_name" onclick="is_check(this.name)">無需&nbsp;&nbsp;
				<span id="is_zh_name" style="<?php if($data['is_zh_name'] == '1-0' || $data['email'] == '1-1'){ echo 'display:none'; } ?>" >
					<input value="1" <?php if($data['is_zh_name'] == '0-1'){ echo "checked='checked'"; } ?> type="checkbox" name="is_zh_name_true">是否必填&nbsp;&nbsp;
				</span>
			</td>
		</tr>

		<tr>
			<td class="table_bg1" align="center">電子信箱</td>
			<td>
				<input value="0" <?php if($data['is_email'] == '0-1' || $data['is_email'] == '0-0'){ echo "checked='checked'"; } ?> type="radio" name="is_email" onclick="is_check2(this.name)">需&nbsp;&nbsp;
				<input value="1" <?php if($data['is_email'] == '1-0' || $data['is_email'] == '1-1'){ echo "checked='checked'"; } ?> type="radio" name="is_email" onclick="is_check(this.name)">無需&nbsp;&nbsp;
				<span id="is_email" style="<?php if($data['is_email'] == '1-0' || $data['email'] == '1-1'){ echo 'display:none'; } ?>" >
					<input value="1" <?php if($data['is_email'] == '0-1'){ echo "checked='checked'"; } ?> type="checkbox" name="is_email_true">是否必填&nbsp;&nbsp;
				</span>
			</td>
		</tr>

		<tr>
			<td class="table_bg1" align="center">英文昵稱</td>
			<td>
				<input value="0" <?php if($data['is_en_name'] == '0-1' || $data['is_en_name'] == '0-0'){ echo "checked='checked'"; } ?> type="radio" name="is_en_name" onclick="is_check2(this.name)">需&nbsp;&nbsp;
				<input value="1" <?php if($data['is_en_name'] == '1-0' || $data['is_en_name'] == '1-1'){ echo "checked='checked'"; } ?> type="radio" name="is_en_name" onclick="is_check(this.name)">無需&nbsp;&nbsp;
				<span id="is_en_name" style="<?php if($data['is_en_name'] == '1-0' || $data['email'] == '1-1'){ echo 'display:none'; } ?>" >
					<input value="1" <?php if($data['is_en_name'] == '0-1'){ echo "checked='checked'"; } ?> type="checkbox" name="is_en_name_true">是否必填&nbsp;&nbsp;
				</span>
			</td>
		</tr>

		<tr>
			<td class="table_bg1" align="center">證件</td>
			<td>
				<input value="0" <?php if($data['is_card'] == '0-1' || $data['is_card'] == '0-0'){ echo "checked='checked'"; } ?> type="radio" name="is_card" onclick="is_check2(this.name)">需&nbsp;&nbsp;
				<input value="1" <?php if($data['is_card'] == '1-0' || $data['is_card'] == '1-1'){ echo "checked='checked'"; } ?> type="radio" name="is_card" onclick="is_check(this.name)">無需&nbsp;&nbsp;
				<span id="is_card" style="<?php if($data['is_card'] == '1-0' || $data['email'] == '1-1'){ echo 'display:none'; } ?>" >
					<input value="1" <?php if($data['is_card'] == '0-1'){ echo "checked='checked'"; } ?> type="checkbox" name="is_card_true">是否必填&nbsp;&nbsp;
				</span>
			</td>
		</tr>

		<tr>
			<td class="table_bg1" align="center">QQ</td>
			<td>
				<input value="0" <?php if($data['is_qq'] == '0-1' || $data['is_qq'] == '0-0'){ echo "checked='checked'"; } ?> type="radio" name="is_qq" onclick="is_check2(this.name)">需&nbsp;&nbsp;
				<input value="1" <?php if($data['is_qq'] == '1-0' || $data['is_qq'] == '1-1'){ echo "checked='checked'"; } ?> type="radio" name="is_qq" onclick="is_check(this.name)">無需&nbsp;&nbsp;
				<span id="is_qq" style="<?php if($data['is_qq'] == '1-0' || $data['email'] == '1-1'){ echo 'display:none'; } ?>" >
					<input value="1" <?php if($data['is_qq'] == '0-1'){ echo "checked='checked'"; } ?> type="checkbox" name="is_qq_true">是否必填&nbsp;&nbsp;
				</span>
			</td>
		</tr>

		<tr>
			<td class="table_bg1" align="center">真實姓名</td>
			<td>
				<input value="0" <?php if($data['is_payname'] == '0-1' || $data['is_payname'] == '0-0'){ echo "checked='checked'"; } ?> type="radio" name="is_payname" onclick="is_check2(this.name)">需&nbsp;&nbsp;
				<input value="1" <?php if($data['is_payname'] == '1-0' || $data['is_payname'] == '1-1'){ echo "checked='checked'"; } ?> type="radio" name="is_payname" onclick="is_check(this.name)">無需&nbsp;&nbsp;
				<span id="is_payname" style="<?php if($data['is_payname'] == '1-0' || $data['email'] == '1-1'){ echo 'display:none'; } ?>" >
					<input value="1" <?php if($data['is_payname'] == '0-1'){ echo "checked='checked'"; } ?> type="checkbox" name="is_payname_true">是否必填&nbsp;&nbsp;
				</span>
			</td>
		</tr>

		<tr>
			<td class="table_bg1" align="center">手機號</td>
			<td>
				<input value="0" <?php if($data['is_phone'] == '0-1' || $data['is_phone'] == '0-0'){ echo "checked='checked'"; } ?> type="radio" name="is_phone" onclick="is_check2(this.name)">需&nbsp;&nbsp;
				<input value="1" <?php if($data['is_phone'] == '1-0' || $data['is_phone'] == '1-1'){ echo "checked='checked'"; } ?> type="radio" name="is_phone" onclick="is_check(this.name)">無需&nbsp;&nbsp;
				<span id="is_phone" style="<?php if($data['is_phone'] == '1-0' || $data['email'] == '1-1'){ echo 'display:none'; } ?>" >
					<input value="1" <?php if($data['is_phone'] == '0-1'){ echo "checked='checked'"; } ?> type="checkbox" name="is_phone_true">是否必填&nbsp;&nbsp;
				</span>
			</td>
		</tr>
			<tr>
			<td class="table_bg1" align="center">推广网址</td>
			<td>
				<input value="0" <?php if($data['from_url_form'] == '0-1' || $data['from_url_form'] == '0-0'){ echo "checked='checked'"; } ?> type="radio" name="from_url_form" onclick="is_check2(this.name)">需&nbsp;&nbsp;
				<input value="1" <?php if($data['from_url_form'] == '1-0' || $data['from_url_form'] == '1-1'){ echo "checked='checked'"; } ?> type="radio" name="from_url_form" onclick="is_check(this.name)">無需&nbsp;&nbsp;
				<span id="from_url_form" style="<?php if($data['from_url_form'] == '1-0' || $data['email'] == '1-1'){ echo 'display:none'; } ?>" >
					<input value="1" <?php if($data['from_url_form'] == '0-1'){ echo "checked='checked'"; } ?> type="checkbox" name="from_url_form_true">是否必填&nbsp;&nbsp;
				</span>
			</td>
		</tr>

			<tr>
			<td class="table_bg1" align="center">其它方式</td>
			<td>
				<input value="0" <?php if($data['other_method_form'] == '0-1' || $data['other_method_form'] == '0-0'){ echo "checked='checked'"; } ?> type="radio" name="other_method_form" onclick="is_check2(this.name)">需&nbsp;&nbsp;
				<input value="1" <?php if($data['other_method_form'] == '1-0' || $data['other_method_form'] == '1-1'){ echo "checked='checked'"; } ?> type="radio" name="other_method_form" onclick="is_check(this.name)">無需&nbsp;&nbsp;
				<span id="other_method_form" style="<?php if($data['other_method_form'] == '1-0' || $data['email'] == '1-1'){ echo 'display:none'; } ?>" >
					<input value="1" <?php if($data['other_method_form'] == '0-1'){ echo "checked='checked'"; } ?> type="checkbox" name="other_method_form_true">是否必填&nbsp;&nbsp;
				</span>
			</td>
		</tr>

		<tr>
			<td height="27" class="table_bg" colspan="2" align="middle">
			<input class="button_d" value="確定" type="submit">
			</td>
		</tr>
	</tbody></table>
</form>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="clear:both">
		<tbody><tr>
			<td align="center" height="50"></td>
		</tr>
	</tbody></table>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>