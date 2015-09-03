<?
include_once("../../include/config.php");
include_once("../common/login_check.php");
include("../../class/user.php");

//删除
if($_GET['id']){
	$data['is_delete'] = 1;
	$result=M('k_qishu',$db_config)->where("id='".$_GET['id']."'")->update($data);
	if(!$result){
		$do_log = $_SESSION['login_name'].'删除了退佣统计，期数，期数序号为'.$_GET['id']; 
		admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
		message('删除失败！','end_hire.php');
	}else{
		message('删除成功！');
	}
}
//读取期数
$qishu=M('k_qishu',$db_config)->where("site_id='".SITEID."' and is_delete = '0'")->order('id desc')->select();
?>

<?php $title="期数管理"; require("../common_html/header.php");?>
<body>

<script type="text/javascript">

$(function(){
	$("div.con_menu a:nth-child(3)").attr("style","color:red");	
});

</script>
<div id="con_wrap">
<div class="input_002">期数管理</div>
<div class="con_menu">
	<a href="agent_count.php">退佣统计</a>
    <a href="agent_search.php">退佣查询</a>
    <a href="end_hire.php" style="color:red">期數管理</a>
	<a href="fee_list.php">手續費設定</a>
	<a href="endhire_list.php">代理退傭設定</a>
&nbsp;&nbsp;<button name="add" onclick="location.href='add_qs.php'" class="button_c">新增期數</button>
</div>
</div>
<div class="content">
	<table width="1024" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab">
		<tbody><tr class="m_title_over_co">
			<td>序號</td>
			<td>期數名字</td>
			<td>起始日期</td>
			<td>結束日期</td>
			<td>當前狀態</td>
			<td>是否显示</td>
			<td>功能</td>
		</tr>
	<?php if(!empty($qishu)){
		foreach($qishu as $v){?>
		<tr class="m_cen">
			<td><?=$v['id']?></td>
			<td><?=$v['qsname']?></td>
			<td><?=$v['start_date']?></td>
			<td><?=$v['end_date']?></td>
			<td><?if($v['state']==0){echo '正常';}else{echo '停止';}?></td>
			<td><?if($v['is_xianshi']==0){echo '显示';}else{echo '隐藏';}?></td>
			<td>					
				<a href="add_qs.php?id=<?=$v['id']?>"> <button class="button_d" name="update">修改</button></a>
				<button class="button_d" name="delbtn" onclick="if(confirm('確定要刪除這條記錄嗎?')){document.location.href='end_hire.php?id=<?=$v['id']?>';}else{return false;}">刪除</button>
			</td>
		</tr>
	<?} }?>				
	</tbody></table>
</div>
</body>
</html>