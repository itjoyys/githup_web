<?php require("../common_html/header.php");
include_once("../../include/config.php");
include_once("../common/login_check.php");

if(!empty($_GET['active']) && $_GET['active'] == 'd'){
	$sql="UPDATE k_message SET is_delete = '1' WHERE id = '".$_GET['id']."'" ;
	$mysqlt->query($sql);
	admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$_SESSION['login_name']."删除了最新消息,  id=".$_GET['id']);
	message("删除成功！","./new_msg.php?show=1");
}
if(!empty($_GET['active']) && $_GET['active'] == 'pause'){

	$sql="UPDATE k_message SET is_delete = '2' WHERE id = '".$_GET['id']."'";

  	$mysqlt->query($sql);
  	admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$_SESSION['login_name']."停用了最新消息,  id=".$_GET['id']);
	message("停用成功！","./new_msg.php?show=1");
}
if(!empty($_GET['active']) && $_GET['active'] == 's'){
	$sql="UPDATE k_message SET is_delete = '0' WHERE id = '".$_GET['id']."'";
	$mysqlt->query($sql);
	admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$_SESSION['login_name']."启用了最新消息,  id=".$_GET['id']);
	message("启用成功！","./new_msg.php?show=1");
}

  if(!empty($_POST['message_id'])){

	$map['game_type']=implode($_POST['gametype'],',');
	$map['show_type']=$_POST['showtype'];
	$map['level_power']=implode($_POST['levellx'],',');
	$map['chn_traditiona']=$_POST['traditional'];
	$map['chn_simplified']=$_POST['simplify'];
	$map['english']=$_POST['english'];
	$map['state']=$_POST['status'];
	if(M('k_message',$db_config)->where('id='.$_POST['message_id'])->update($map)){
		admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$_SESSION['login_name']."修改了最新消息,  id=".$_POST['message_id']);

		message("修改成功！","./new_msg.php?show=1");
		exit;
	}
}

?>
<body>


<script type="text/javascript">

function valid()
{
	var gametype = "input[name='gametype[]']";
	var levellx = "input[name='levellx[]']";
	var flag = true;
	if(judge(gametype) == 0)
	{
		alert("請對遊戲類型進行選擇!");
		flag = false;
	}
	if(judge(levellx) == 0)
	{
		alert("請對公告適用的層級進行設置!");
		flag = false;
	}
	return flag;
}
function judge(str)
{
	var flag = 0;
	$(str).each(function(){
		if($(this).attr('checked'))
		{
			flag += 1;
		}
	});
	return flag;
}

</script>
<script>
	$(function(){
		//复选框默认值
		var val = $('#game_type_h').val();

		if(val.indexOf("1") != -1){
			$('#gametype1').prop('checked', 'checked');
		}
		if(val.indexOf("2") != -1){
			$('#gametype2').prop('checked', 'checked');
		}
		if(val.indexOf("3") != -1){
			$('#gametype3').prop('checked', 'checked');
		}

		var show_type_val = $('#show_type_h').val();

		if(show_type_val==1){
			$('#show_type_1').prop('checked', 'checked');
		}else{
			$('#show_type_2').prop('checked', 'checked');
		}

		var level_power_val = $('#level_power_h').val();

		if(level_power_val.indexOf("3") != -1){
			$('#level_power_1').prop('checked', 'checked');
		}
		 if(level_power_val.indexOf("4") != -1){
			$('#level_power_2').prop('checked', 'checked');
		}
		 if(level_power_val.indexOf("5") != -1){
			$('#level_power_3').prop('checked', 'checked');
		}
		 if(level_power_val.indexOf("6") != -1){
			$('#level_power_4').prop('checked', 'checked');
		}


		var state_val = $('#state_h').val();

		if(state_val==0){
			$('#state_1').prop('checked', 'checked');
		}else{
			$('#state_2').prop('checked', 'checked');
		}

	})
</script>
<div id="con_wrap">
  <div class="input_002">公告管理</div>
  <div class="con_menu">
<a href="javascript:window.history.go(-1)">返回上一頁</a>
  </div>
</div>
<div class="content">
<form method="post" name="myFORM" id="myFORM" action="" onsubmit="return valid();">
	<input type="hidden" name="message_id" value="<?=$_GET['id'] ?>">
<table style="width:800px" border="0" cellspacing="0" cellpadding="0" class="m_tab">
	<tbody><tr class="m_title_over_co">
		<td width="30%" height="25">設置項</td>
		<td width="70%">設置內容</td>
	</tr>
	<?php
	if(!empty($_GET['id'])&& $_GET['active'] == 'update'){
	$sql="SELECT * FROM k_message WHERE id = '".$_GET['id']."'";

		$query	=	$mysqlt->query($sql);
		while($rows = $query->fetch_array()){

		 ?>
	<tr>
	<input type="hidden" id="game_type_h" value="<?=$rows['game_type'] ?>">
	<input type="hidden" id="show_type_h" value="<?=$rows['show_type'] ?>">
	<input type="hidden" id="level_power_h" value="<?=$rows['level_power'] ?>">
	<input type="hidden" id="state_h" value="<?=$rows['state'] ?>">

		<td align="center" class="table_bg1">遊戲類型</td>
		<td align="left" class="table_bg1">
			<input type="checkbox" name="gametype[]" value="1" id="gametype1">&nbsp;體育&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="gametype[]" value="2" id="gametype2">&nbsp;彩票&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="gametype[]" value="3" id="gametype3">&nbsp;視訊&nbsp;&nbsp;&nbsp;
		</td>
	</tr>

	<tr>
		<td align="center" class="table_bg1">顯示類型</td>
		<td align="left" class="table_bg1">
			<input type="radio" name="showtype" value="1" id="show_type_1">&nbsp;彈出&nbsp;&nbsp;&nbsp;
			<input type="radio" name="showtype" id="show_type_2" value="2">&nbsp;跑馬燈&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<tr>
		<td align="center" class="table_bg1">層級</td>
		<td align="left" class="table_bg1">

			<input type="checkbox" name="levellx[]" value="3" id="level_power_1">&nbsp;股東&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="levellx[]" value="4" id="level_power_2">&nbsp;總代理&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="levellx[]" value="5" id="level_power_3">&nbsp;代理&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="levellx[]" value="6" id="level_power_4">&nbsp;會員&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<tr>
		<td align="center" class="table_bg1">繁體中文</td>
		<td align="left" class="table_bg1"><textarea name="traditional" style="height:70px" cols="60" rows="3" ><?=$rows['chn_traditiona'] ?></textarea></td>
	</tr>
	<tr>
		<td align="center" class="table_bg1">简体中文</td>
		<td align="left" class="table_bg1"><textarea name="simplify" style="height:70px" cols="60" rows="3"><?=$rows['chn_simplified'] ?></textarea></td>
	</tr>
	<tr>
		<td align="center" class="table_bg1">English</td>
		<td align="left" class="table_bg1"><textarea name="english" style="height:70px" cols="60" rows="3" ><?=$rows['english'] ?></textarea></td>
	</tr>
	<tr>
		<td align="center" class="table_bg1">啟用狀態</td>
		<td align="left" class="table_bg1">
			<input type="radio" name="status"  value="0" id="state_1">&nbsp;啟用&nbsp;&nbsp;&nbsp;
			<input type="radio" name="status" value="1" id="state_2">&nbsp;停用&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<tr>
		<td align="center" colspan="2" class="table_bg1">
			<input type="submit" value="確定" class="za_button">
			&nbsp;&nbsp;&nbsp;
			<input type="reset" value="重置" class="za_button">
		</td>
	</tr>
	<input type="hidden" value="<?php echo date("Y-m-d H:i:s") ?>" name="time">
	<input type="hidden" value="<?=$_SESSION['login_name'] ?>" name="login_name">
	<input type="hidden" value="OK" name="news_msg_add">
</tbody></table>

</form>
</div>
<?php } } ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="clear:both">
		<tbody><tr>
			<td align="center" height="50"></td>
		</tr>
	</tbody></table>


<script src="./new_msg_add_files/yhinput.js" type="text/javascript"></script></body></html>