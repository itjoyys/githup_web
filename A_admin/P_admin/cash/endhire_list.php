<?
include_once("../../include/config.php");
include_once("../common/login_check.php");
include("../../class/user.php");



if($_GET['id']){
	$data['is_delete'] = 1;
	$result=M('k_hire_config',$db_config)->where("id='".$_GET['id']."'")->update($data);
	if($result){
		$do_log = $_SESSION['login_name'].'删除了退佣统计，代理退佣设定'; 
		admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
		message('删除成功！','endhire_list.php');
	}else{
		message('删除失败');
	}
}
if(isset($_POST['validCust'])){
	$data['valid_money'] = $_POST['validCust'];
	$result=M('k_hire_config',$db_config)->where("site_id = '".SITEID."'")->update($data);
	if($result){
		$do_log = $_SESSION['login_name'].'修改了退佣统计，代理退佣设定'; 
		admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
		message('设定成功！','endhire_list.php');
	}
}
//列表读取
	$hire=M('k_hire_config',$db_config)->where("site_id='".SITEID."' and is_delete ='0'")->select();
?>

<?php $title="代理退傭設定"; require("../common_html/header.php");?>
<body>
<script type="text/javascript">

$(function(){
	$("div.con_menu a:nth-child(5)").attr("style","color:red");	
});

</script>
<script type="text/javascript">

$(function(){
	$(".vform").Validform({
		tiptype:2,
		tipSweep:false
	});
})

</script>
<div id="con_wrap">
<div class="input_002">代理退傭設定</div>
<div class="con_menu">
	<a href="agent_count.php">退佣统计</a>
    <a href="agent_search.php">退佣查询</a>
    <a href="end_hire.php">期數管理</a>
	<a href="fee_list.php">手續費設定</a>
	<a href="endhire_list.php" style="color:red">代理退傭設定</a>

&nbsp;&nbsp;<button name="add" onclick="location.href='add_endhire_config.php'" class="button_c">新增設定</button>
</div>
</div>
<div class="content">
	<table width="1024" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab">
		<tbody>
		<tr class="m_title_over_co">
			<td rowspan="2">自身盈利金額</td>
			<td rowspan="2">有效會員</td>
			<td colspan="4">退傭比例</td>
			<td rowspan="2">自身有效投注</td>
			<td colspan="4">退水比例</td>
			<td rowspan="2">功能</td>
		</tr>
		<tr class="m_title_over_co">
			<td>體育 (%)</td>
			<td>彩票 (%)</td>
			<td>視訊 (%)</td>
            <td>電子 (%)</td>
			<td>體育 (%)</td>
			<td>彩票 (%)</td>
			<td>視訊 (%)</td>
            <td>電子 (%)</td>			
		</tr>
		<?foreach($hire as $v){?>
		<tr class="m_cen">
			<td><?=$v['self_profit']?></td>
			<td><?=$v['effective_user']?></td>
			<td><?=$v['sport_slay_rate']?></td>
			<td><?=$v['lottery_slay_rate']?></td>
			<td><?=$v['video_slay_rate']?></td>
            <td><?=$v['evideo_slay_rate']?></td>
			<td><?=$v['self_effective_bet']?></td>
			<td><?=$v['sport_water_rate']?></td>
			<td><?=$v['lottery_water_rate']?></td>
			<td><?=$v['video_water_rate']?></td>	
            <td><?=$v['evideo_water_rate']?></td>		
			<td>
				<button class="button_d" name="update" onclick="window.location='add_endhire_config.php?id=<?=$v['id']?>'">修改</button>
				<button class="button_d" onclick="if(confirm('是否要刪除這條記錄?')){window.location='endhire_list.php?id=<?=$v['id']?>'}else{return false;}">刪除</button>
			</td>
		</tr>
		<?}?>
</tbody></table>
</div>
<div class="content" style="width:500px;">
	<form action="" id="addForm" name="addForm" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab">
		<tbody><tr class="m_title_over_co">
			<td>有效會員投注金額</td>
		</tr>
		<tr class="m_cen">
			<td onclick="$('#editnum').show();$('#num').hide();">
			<div id="num"><?php if($hire[0]['valid_money']){echo $hire[0]['valid_money'];}else{echo 0;}?></div>
			<div id="editnum" style="display:none;">
				<input type="text" name="validCust" class="za_text" value="<?php if($hire[0]['valid_money']){echo $hire[0]['valid_money'];}?>">&nbsp;&nbsp;&nbsp;
				<input type="submit" name="subbtn" value=" 設 定 " class="button_a">
			</div>
			</td>
		</tr>
	</tbody></table>
	</form>
</div>



<script src="./endhire_list_files/yhinput.js" type="text/javascript"></script></body></html>