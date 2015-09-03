<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");


/**
0-1 代表需要  且必填
0-0 代表需要  不必填

**/


$data=M('k_user_reg_config',$db_config)->where("site_id = '".SITEID."'")->find();
if(empty($data)){
	$info['site_id']=SITEID;
	$k_user_reg_config=M('k_user_reg_config',$db_config)->add($info);
	if($k_user_reg_config){
		$data=M('k_user_reg_config',$db_config)->where("site_id = '".SITEID."'")->find();
	}
}
if ($_POST) {
	$_POST['is_email']        =   !empty($_POST['is_email'])?$_POST['is_email']:'0';
	$_POST['is_passport']     =   !empty($_POST['is_passport'])?$_POST['is_passport']:'0';
	$_POST['is_qq']           =   !empty($_POST['is_qq'])?$_POST['is_qq']:'0';
	$_POST['is_mobile']       =   !empty($_POST['is_mobile'])?$_POST['is_mobile']:'0';
	$map['site_id']           =   SITEID;
	$map['is_work']           =   $_POST['is_work'];	
	$map['username_chn']      =   $_POST['username_chn'];
	$map['username_eng']      =   $_POST['username_eng'];
	$map['true_name']         =   $_POST['true_name'];


	$map['email']             =   $_POST['email'].'-'.$_POST['is_email'];
	$map['passport']          =   $_POST['passport'].'-'.$_POST['is_passport'];
	$map['qq']                =   $_POST['qq'].'-'.$_POST['is_qq'];
	$map['mobile']            =   $_POST['mobile'].'-'.$_POST['is_mobile'];
}

if ($_POST) {
	if(!empty($_POST['id'])){	
	if(M('k_user_reg_config',$db_config)->where("id=".$_POST['id'])->update($map)){
		admin::insert_log($_SESSION["adminid"],$_SESSION['login_name'],"修改了会员注册设定"); 
		message('修改成功','member_reg_set.php');
	}
}else{
	if(M('k_user_reg_config',$db_config)->where("id=".$_POST['id'])->update($map)){
	  message('设定成功','member_reg_set.php');
	}	
}
}

?>

<?php $title="會員注册設定"; require("../common_html/header.php");?>
<style type="text/css">
	.m_tab{
		width: 780px;
	}
</style>
<body>
<div id="con_wrap">
  <div class="input_002">會員注册設定</div>
</div>

<div class="content">
<form action="" method="post">
	<input type="hidden" name="id" value="<?=$data['id']?>">

	<table width="780" class="m_tab">
		<tbody><tr class="m_title">
			<td height="27" class="table_bg" colspan="2" align="middle">會員注册設定</td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">是否啟用會員注册</td>
			<td>
				<input value="1" <?php if($data['is_work'] == 1){ echo "checked='checked'" ;}?>type="radio" name="is_work">啟用&nbsp;&nbsp;
				<input value="0" <?php if($data['is_work'] == 0){ echo "checked" ;}?> type="radio" name="is_work">停用&nbsp;&nbsp;
			</td>
		</tr>
		<script>
		//隐藏是否必填
		function is_check(name){
			var c='#is_'+name;
			$(c).css('display','none');
		}

		//显示是否必填
		function is_check2(name){
			var c='#is_'+name;
			$(c).css('display','');
		}


		</script>
		<tr>
			<td class="table_bg1" align="center">邮箱</td>
			<td>
				<input value="0" <?php if($data['email'] == '0-1'||$data['email'] == '0-0'){ echo "checked='checked'" ;}?> type="radio" name="email" onclick="is_check2(this.name)">需&nbsp;&nbsp;
				<input value="1" <?php if($data['email'] == '1-0'||$data['email'] == '1-1'){ echo "checked='checked'" ;}?>  type="radio" name="email" onclick="is_check(this.name)">無需&nbsp;&nbsp;
				<span id="is_email" style="<?php if($data['email'] == '1-0'||$data['email']=='1-1'){ echo "display:none" ;}?>" >
				<input value="1" <?php if($data['email'] == '0-1'){ echo "checked='checked'" ;}?>  type="checkbox" name="is_email">是否必填&nbsp;&nbsp;
				</span>
			</td>
		</tr>

		<tr>
			<td class="table_bg1" align="center">證件</td>
			<td>
				<input value="0" type="radio" <?php if($data['passport'] == '0-1'||$data['passport'] == '0-0'){ echo "checked='checked'" ;}?> name="passport" onclick="is_check2(this.name)">需&nbsp;&nbsp;
				<input value="1" <?php if($data['passport'] == '1-0'||$data['passport'] == '1-1'){ echo "checked='checked'" ;}?> type="radio" name="passport" onclick="is_check(this.name)">無需&nbsp;&nbsp;
				<span id="is_passport" style="<?php if($data['passport'] == '1-0'||$data['passport']=='1-1'){ echo "display:none" ;}?>" >
				<input value="1" <?php if($data['passport'] == '0-1'){ echo "checked='checked'" ;}?>  type="checkbox" name="is_passport">是否必填&nbsp;&nbsp;
				</span>
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="center">QQ</td>
			<td>
				<input value="0" <?php if($data['qq'] == '0-1'||$data['qq'] == '0-0'){ echo "checked='checked'" ;}?> type="radio" name="qq" onclick="is_check2(this.name)" onclick="is_check2(this.name)">需&nbsp;&nbsp;
				<input value="1" <?php if($data['qq'] == '1-0'||$data['qq'] == '1-1'){ echo "checked='checked'" ;}?> type="radio" name="qq" onclick="is_check(this.name)">無需&nbsp;&nbsp;
				<span id="is_qq" style="<?php if($data['qq'] == '1-0'||$data['qq']=='1-1'){ echo "display:none" ;}?>" >
				<input value="1" <?php if($data['qq'] == '0-1'){ echo "checked='checked'" ;}?>  type="checkbox" name="is_qq">是否必填&nbsp;&nbsp;
				</span>
			</td>
		</tr>

		<tr>
			<td class="table_bg1" align="center">手機號</td>
			<td>
				<input  type="radio" name="mobile" value="0" <?php if($data['mobile'] == '0-1'||$data['mobile'] == '0-0'){ echo "checked='checked'" ;}?>  onclick="is_check2(this.name)">需 &nbsp;&nbsp;
				<input value="1" type="radio"<?php if($data['mobile'] == '1-0'||$data['mobile'] == '1-1'){ echo "checked='checked'" ;}?> name="mobile" onclick="is_check(this.name)">無需&nbsp;&nbsp;
				<span id="is_mobile" style="<?php if($data['mobile'] == '1-0'||$data['mobile']=='1-1'){ echo "display:none" ;}?>" >
				<input value="1" <?php if($data['mobile'] == '0-1'){ echo "checked='checked'" ;}?>  type="checkbox" name="is_mobile">是否必填&nbsp;&nbsp;
				</span>
			</td>
		</tr>
			<tr>
			<td height="27" class="table_bg" colspan="2" align="middle">
			<input class="button_d" value="確定" type="submit" name="edit">
			</td>
		</tr>


	</tbody></table>
</form>
</div>
<?php require("../common_html/footer.php");?>