<?php

include_once("../../include/config.php");
include_once("../common/login_check.php");
/****
  发布信息处理
   showtype显示类型 traditional繁体文
    simplify简体
    english英文
***/
//p($_POST);exit;

//用户层级查询
//echo SITEID;exit;
$k_user_level = M('k_user_level', $db_config);
$user_cj = $k_user_level->field("id,level_des")->where("site_id='".SITEID."'")->select();


if(!empty($_POST['news_msg_add']) && $_POST['news_msg_add']=="OK"){

	foreach ($_POST['gametype'] as $k => $v) {
		$data['game_type'].=$_POST['gametype'][$k].',';
	}

	if($_POST['lxxz'] == 1){	//指定用户时
		$data['level_power'] = '-1';
	}elseif ($_POST['lxxz'] == 2){	//层级选择时
		foreach ($_POST['levellx'] as $k => $v) {
			$data['level_power'].=$_POST['levellx'][$k].',';
		}
	}elseif ($_POST['lxxz'] == 3){	//全体用户时
		$data['level_power'] = '-2';
	}


	//过滤掉最后一个,

	$data['game_type']=rtrim($data['game_type'],',');
	$data['level_power']=rtrim($data['level_power'],',');
	$data['show_type']=$_POST['showtype'];	//显示类型
	$data['chn_traditiona']=$_POST['traditional'];//繁体
	$data['chn_simplified']=$_POST['simplify'];//简体
	$data['english']=$_POST['english'];//英文
	$data['state']=$_POST['state'];//状态
	$data['is_delete']=$_POST['is_delete'];//停用
	$data['add_time']=$_POST['time'];//时间
	$data['name']=$_POST['login_name'];//发布者
	$data['zduser'] = $_POST['zduser']; //指定发送人
	if(!empty($_POST['id'])){
		$k_message=M('k_message',$db_config);
		$rows=$k_message->where("id='".$_POST['id']."'")->update($data);
		if(!empty($rows)){
			admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$_SESSION['login_name']."修改了最新消息,  id=".$_POST['id']);
			message("修改成功！","./new_msg.php?show=1");
		}
	}else{
		$sql="insert into k_message (game_type,show_type,level_power,chn_traditiona,chn_simplified,english,state,is_delete,add_time,name,site_id,zduser) values ('".$data['game_type']."' , '".$data['show_type']."', '".$data['level_power']."', '".$data['chn_traditiona']."', '".$data['chn_simplified']."', '".$data['english']."','".$data['state']."', '".$data['is_delete']."', '".$data['add_time']."', '".$data['name']."','".SITEID."','".$data['zduser']."')";

		$query	=	$mysqlt->query($sql);
		$result =	$mysqlt->insert_id;
		admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$_SESSION['login_name']."添加了最新消息,  id=".$result);
		message("添加成功！","./new_msg.php?show=1");
	}
}

if($_GET['active']=='u'){
	$k_message=M('k_message',$db_config);
	$id=$_GET['id'];
	$info=$k_message->where("id='$id' and site_id='".SITEID."'")->find();
}

?>
<body>
<?php require("../common_html/header.php");?>
<script type="text/javascript">

function valid()
{
	var gametype = "input[name='gametype[]']";
	var lxxz = $("input[name='lxxz']:checked").val();
	var levellx = "input[name='levellx[]']";
	var showtype = $("input[name='showtype']:checked").val();
	var state = $("input[name='state']:checked").val();
	var traditional = $('#traditional').val();
	var simplify = $('#simplify').val();
	var english = $('#english').val();
	var flag = true;
	if(showtype == 1){
 		if(judge(levellx) == 0 && lxxz == 2)
		{
			alert("請對公告適用的層級進行設置!");
			flag = false;
		}
	}else{
		if(state == 2){
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
		}else if(state == 1){
			if(judge(levellx) == 0)
			{
				alert("請對公告適用的層級進行設置!");
				flag = false;
			}
		}
	}

	if(traditional == '' || simplify == '' || english == ''){
		alert("請填寫完整的公告內容!");
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

function noti(){
	var showtype = $("input[name='showtype']:checked").val();
	var state = $("input[name='state']:checked").val();
	if(showtype == 1){
	 	$("#cengt").hide();
	 	$("#allt").hide();
	 	$("#gamet").hide();
	 	$("#user_cengt").show();
	}else if(showtype == 2){
	 	$("#cengt").show();
	 	$("#allt").show();
	 	$("#gamet").hide();
	 	$("#user_cengt").hide();

	 	if(state == 1){
		 	$("#cengt").show();
		 	$("#allt").show();
		 	$("#gamet").hide();
		 }else if(state == 2){
		 	$("#cengt").show();
		 	$("#allt").show();
		 	$("#gamet").show();
		 }
	}
}

$(function(){
	noti();
	$("input[name='showtype']").change(function(){
		noti();
	})
	$("input[name='state']").change(function(){
		 state = $("input[name='state']:checked").val();
		 if(state == 1){
		 	$("#cengt").hide();
		 	$("#allt").show();
		 	$("#gamet").hide();
		 }else if(state == 2){
		 	$("#cengt").show();
		 	$("#allt").show();
		 	$("#gamet").show();
		 }
	})
})

//层级隐藏显示
function listdiv(v,k,u){

	if(v.checked && k!='all'){
		var div = document.getElementById(k);
		var div1 = document.getElementById(u);
		div.style.display = "";
		div1.style.display = "none";
	}else{
		document.getElementById('cengt').style.display = "none";
		document.getElementById('zd_user').style.display = "none";
	}
}
</script>
<div id="con_wrap">
  <div class="input_002">公告管理</div>
  <div class="con_menu">
<a href="javascript:window.history.go(-1)">返回上一頁</a>
  </div>
</div>
<div class="content">
<form method="post" name="myFORM" id="myFORM" action="" onsubmit="return valid();">
<table style="width:800px" border="0" cellspacing="0" cellpadding="0" class="m_tab">
	<tbody><tr class="m_title_over_co">
		<td width="30%" height="25">設置項</td>
		<td width="70%">設置內容</td>
	</tr>
	<tr>
		<td align="center" class="table_bg1">顯示類型</td>
		<td align="left" class="table_bg1">
			<input type="radio" name="showtype" <?php if($info['show_type']==1 || $info['show_type']==''){echo 'checked="checked"'; }?>  value="1">&nbsp;彈出&nbsp;&nbsp;&nbsp;
			<input type="radio" name="showtype" <?php if($info['show_type']==2){echo 'checked="checked"'; }?>   value="2">&nbsp;跑馬燈&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<tr id="allt" style="display:none">
		<td align="center" class="table_bg1">顯示位置</td>
		<td align="left" class="table_bg1">
			<input type="radio" name="state" <?php if($info['state']==1 || $info['state']==''){echo 'checked="checked"'; }?>  value="1">&nbsp;全站顯示&nbsp;&nbsp;&nbsp;
			<input type="radio" name="state" <?php if($info['state']==2){echo 'checked="checked"'; }?>   value="2">&nbsp;指定頁面顯示&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<tr id="gamet" style="display:none">
		<td align="center" class="table_bg1">遊戲類型</td>
		<td align="left" class="table_bg1">
			<input type="checkbox" name="gametype[]" <?php if(strpos($info['game_type'],'1')===false){}else{ echo 'checked="checked"';}?> value="1">&nbsp;體育&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="gametype[]" <?php if(strpos($info['game_type'],'2')===false){}else{ echo 'checked="checked"';}?> value="2">&nbsp;彩票&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="gametype[]" <?php if(strpos($info['game_type'],'3')===false){}else{ echo 'checked="checked"';}?> value="3">&nbsp;視訊&nbsp;&nbsp;&nbsp;<font style="color:red;">視訊包含電子遊戲</font>
		</td>
	</tr>
	<tr id="user_cengt">
		<td align="center" class="table_bg1">用户类型选择</td>
		<td align="left" class="table_bg1">

			<!--<input type="checkbox" name="levellx[]" <?php if(strpos($info['level_power'],'3')===false){}else{ echo 'checked="checked"';}?> value="3">&nbsp;股東&nbsp;&nbsp;&nbsp;-->
			<input type="radio" name="lxxz" value="1" onclick="listdiv(this,'zd_user','cengt')">&nbsp;指定用户&nbsp;&nbsp;&nbsp;
			<input type="radio" name="lxxz" value="2" onclick="listdiv(this,'cengt','zd_user')">&nbsp;层级选择&nbsp;&nbsp;&nbsp;
			<input type="radio" name="lxxz" value="3" onclick="listdiv(this,'all')">&nbsp;全部用户&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<tr id="cengt">
		<td align="center" class="table_bg1">層級</td>
		<td align="left" class="table_bg1">

			<!--<input type="checkbox" name="levellx[]" <?php if(strpos($info['level_power'],'3')===false){}else{ echo 'checked="checked"';}?> value="3">&nbsp;股東&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="levellx[]" <?php if(strpos($info['level_power'],'4')===false){}else{ echo 'checked="checked"';}?> value="4">&nbsp;總代理&nbsp;&nbsp;&nbsp;-->
			<input type="checkbox" name="levellx[]" <?php if(strpos($info['level_power'],'0')===false){}else{ echo 'checked="checked"';}?> value="0">&nbsp;代理&nbsp;&nbsp;&nbsp;
			<?php foreach ($user_cj as $key => $value) { ?>
			<input type="checkbox" name="levellx[]" <?php if(strpos($info['level_power'], $value['id'] )===false){}else{ echo 'checked="checked"';}?> value="<?php echo $value['id'] ?>">&nbsp;<?php echo $value['level_des'] ?>&nbsp;&nbsp;&nbsp;
			<?php } ?>
		</td>
	</tr>
	<tr id="zd_user" style="display: none;">
		<td align="center" class="table_bg1">指定用户</td>
		<td align="left" class="table_bg1"><textarea name="zduser" id="zduser" style="height:70px" cols="60" rows="3"><?=$info['zduser']?></textarea></td>
	</tr>
	<tr>
		<td align="center" class="table_bg1">繁體中文</td>
		<td align="left" class="table_bg1"><textarea name="traditional" id="traditional" style="height:70px" cols="60" rows="3"><?=htmlspecialchars_decode($info['chn_traditiona'])?></textarea></td>
	</tr>
	<tr>
		<td align="center" class="table_bg1">简体中文</td>
		<td align="left" class="table_bg1"><textarea name="simplify" id="simplify" style="height:70px" cols="60" rows="3"><?=htmlspecialchars_decode($info['chn_simplified'])?></textarea></td>
	</tr>
	<tr>
		<td align="center" class="table_bg1">English</td>
		<td align="left" class="table_bg1"><textarea name="english" id="english" style="height:70px" cols="60" rows="3"><?=htmlspecialchars_decode($info['english'])?></textarea></td>
	</tr>
	<tr>
		<td align="center" class="table_bg1">啟用狀態</td>
		<td align="left" class="table_bg1">
			<input type="radio" name="is_delete" <?php if($info['is_delete']==0){echo 'checked="checked"'; }?> checked="checked" value="0">&nbsp;啟用&nbsp;&nbsp;&nbsp;
			<input type="radio" name="is_delete" <?php if($info['is_delete']==2){echo 'checked="checked"'; }?> value="2">&nbsp;停用&nbsp;&nbsp;&nbsp;
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
	<input type="hidden" value="<?=$_GET['id']?>" name="id">
</tbody></table>
</form>
</div>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="clear:both">
		<tbody><tr>
			<td align="center" height="50"></td>
		</tr>
	</tbody></table>


<script src="./new_msg_add_files/yhinput.js" type="text/javascript"></script></body></html>