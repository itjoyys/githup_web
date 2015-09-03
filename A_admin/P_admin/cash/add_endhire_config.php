<?
include_once("../../include/config.php");
include_once("../common/login_check.php");
include("../../class/user.php");

if($_POST){
		$date['self_profit']=$_POST['self_profit'];
		$date['effective_user']=$_POST['effective_user'];
		$date['sport_slay_rate']=$_POST['sport_slay_rate'];
		$date['lottery_slay_rate']=$_POST['lottery_slay_rate'];
		$date['video_slay_rate']=$_POST['video_slay_rate'];
		$date['evideo_slay_rate']=$_POST['evideo_slay_rate'];
		$date['self_effective_bet']=$_POST['self_effective_bet'];
		$date['sport_water_rate']=$_POST['sport_water_rate'];
		$date['lottery_water_rate']=$_POST['lottery_water_rate'];
		$date['video_water_rate']=$_POST['video_water_rate'];
		$date['evideo_water_rate']=$_POST['evideo_water_rate'];
	if($_POST['id']){
	    //编辑
		if(M('k_hire_config',$db_config)->where("id='".$_POST['id']."'")->update($date)){
			$do_log = $_SESSION['login_name'].'修改了退佣统计，代理退佣设定'; 
			admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
			message('修改成功','endhire_list.php');
		}else{
			message('修改失败！','add_endhire_config?id='.$_POST['id']);exit;
		}
	}else{
		//添加
		$date['site_id']=SITEID;
		if(M('k_hire_config',$db_config)->add($date)){
			$do_log = $_SESSION['login_name'].'添加了退佣统计，代理退佣设定'; 
			admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
			message('添加成功','endhire_list.php');
		}else{
			message('添加失败！','add_endhire_config.php');exit;
		}
	
	}
	
	//print_r($_POST);exit;
}
if($_GET['id']){
	$hire=M('k_hire_config',$db_config)->where("id='".$_GET['id']."'")->find();
}
?>

<?php require("../common_html/header.php");?>
<body>
<div id="con_wrap">
<div class="input_002">代理退傭設定</div>
<div class="con_menu">
		<a href="agent_count.php">退佣统计</a>
    <a href="agent_search.php" >退佣查询</a>
    <a href="end_hire.php">期數管理</a>
	<a href="fee_list.php">手續費設定</a>
	<a href="endhire_list.php" style="color:red">代理退傭設定</a>
&nbsp;&nbsp;<a href="javascript:history.go(-1)">返回到列表</a>
</div>
</div>
<div class="content" style="width:500px;">
	<form name="addForm" action="#" method="post" class="vform">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab" style="border:0px;">
	<input type="hidden" name='id' value="<?=$hire['id']?>">
		<tbody><tr class="m_title_over_co">
			<td colspan="2">新增代理退傭設定</td>
		</tr>
		<tr>
			<td class="table_bg1" align="right" width="120px">自身盈利金額：</td>
			<td style="text-align:left;">
				<div style="float:left"><input type="text" name="self_profit" value="<?=$hire['self_profit']?>" datatype="s" nullmsg="請填寫盈利金額" class="za_text">&nbsp;以上</div>
				<div class="Validform_checktip" style="float:left"></div>
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="right">有效會員：</td>
			<td style="text-align:left;">
				<div style="float:left"><input type="text" name="effective_user" value="<?=$hire['effective_user']?>" datatype="s" nullmsg="請填寫有效會員数" class="za_text">&nbsp;以上</div>
				<div class="Validform_checktip" style="float:left"></div>
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="right">體育退傭比例：</td>
			<td style="text-align:left;">
				<div style="float:left"><input type="text" name="sport_slay_rate" value="<?=$hire['sport_slay_rate']?>" datatype="s" nullmsg="請設定退傭比例" class="za_text">&nbsp;%&nbsp;&nbsp;&nbsp;</div>
				<div class="Validform_checktip" style="float:left"></div>
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="right">彩票退傭比例：</td>
			<td style="text-align:left;">
				<div style="float:left"><input type="text" name="lottery_slay_rate" value="<?=$hire['lottery_slay_rate']?>" datatype="s" nullmsg="請設定退傭比例" class="za_text">&nbsp;%&nbsp;&nbsp;&nbsp;</div>
				<div class="Validform_checktip" style="float:left"></div>
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="right">視訊退傭比例：</td>
			<td style="text-align:left;">
				<div style="float:left"><input type="text" name="video_slay_rate" value="<?=$hire['video_slay_rate']?>" datatype="s" nullmsg="請設定退傭比例" class="za_text">&nbsp;%&nbsp;&nbsp;&nbsp;</div>
				<div class="Validform_checktip" style="float:left"></div>
			</td>
		</tr>	
        <tr>
			<td class="table_bg1" align="right">電子退傭比例：</td>
			<td style="text-align:left;">
				<div style="float:left"><input type="text" name="evideo_slay_rate" value="<?=$hire['evideo_slay_rate']?>" datatype="s" nullmsg="請設定退傭比例" class="za_text">&nbsp;%&nbsp;&nbsp;&nbsp;</div>
				<div class="Validform_checktip" style="float:left"></div>
			</td>
		</tr>	
		<tr>
			<td class="table_bg1" align="right">自身有效投注：</td>
			<td style="text-align:left;">
				<div style="float:left"><input type="text" name="self_effective_bet" value="<?=$hire['self_effective_bet']?>" datatype="s" nullmsg="請輸入投注金額" class="za_text">&nbsp;以上</div>
				<div class="Validform_checktip" style="float:left"></div>
			</td>
		</tr>			
		<tr>
			<td class="table_bg1" align="right">體育退水比例：</td>
			<td style="text-align:left;">
				<div style="float:left"><input type="text" name="sport_water_rate" value="<?=$hire['sport_water_rate']?>" datatype="s" nullmsg="請設定退水比例" class="za_text">&nbsp;%&nbsp;&nbsp;&nbsp;</div>
				<div class="Validform_checktip" style="float:left"></div>
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="right">彩票退水比例：</td>
			<td style="text-align:left;">
				<div style="float:left"><input type="text" name="lottery_water_rate" value="<?=$hire['lottery_water_rate']?>" datatype="s" nullmsg="請設定退水比例" class="za_text">&nbsp;%&nbsp;&nbsp;&nbsp;</div>
				<div class="Validform_checktip" style="float:left"></div>
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="right">視訊退水比例：</td>
			<td style="text-align:left;">
				<div style="float:left"><input type="text" name="video_water_rate" value="<?=$hire['video_water_rate']?>" datatype="s" nullmsg="請設定退水比例" class="za_text">&nbsp;%&nbsp;&nbsp;&nbsp;</div>
				<div class="Validform_checktip" style="float:left"></div>
			</td>
		</tr>	
        <tr>
			<td class="table_bg1" align="right">電子退水比例：</td>
			<td style="text-align:left;">
				<div style="float:left"><input type="text" name="evideo_water_rate" value="<?=$hire['evideo_water_rate']?>" datatype="s" nullmsg="請設定退水比例" class="za_text">&nbsp;%&nbsp;&nbsp;&nbsp;</div>
				<div class="Validform_checktip" style="float:left"></div>
			</td>
		</tr>							
		<tr>
			<td colspan="2" class="table_bg1" align="center">
				<input type="submit" name="subbtn" class="button_a" value=" 確 定 ">&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="reset" name="cls" value=" 重 置 " class="button_a">
			</td>
		</tr>			
	</tbody></table>
	</form>
</div>
<script>

$(".vform").Validform({
	tiptype:2,
	showAllError:true
});
</script>
</body></html>