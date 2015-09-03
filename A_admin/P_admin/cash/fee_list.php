<?
include_once("../../include/config.php");
include_once("../common/login_check.php");

include("../../class/user.php");

//删除手续费设定
if($_GET['id']){
	$data['is_delete'] = 1;
	$result=M('k_fee_set',$db_config)->where("id='".$_GET['id']."'")->update($data);
	if(!$result){
		message('删除失败！','fee_list.php');
	}else{
		message('删除成功！');
	}
}
//读取手续费设定
$fee=M('k_fee_set',$db_config)->where("site_id='".SITEID."' and is_delete = '0'")->order('id desc')->select();
?>

<?php $title="手續費設定"; require("../common_html/header.php");?>
<body>
<div id="con_wrap">
<div class="input_002">手續費設定</div>
<div class="con_menu">
	<a href="agent_count.php">退佣统计</a>
    <a href="agent_search.php">退佣查询</a>
    <a href="end_hire.php">期數管理</a>
	<a href="fee_list.php" style="color:red">手續費設定</a>
	<a href="endhire_list.php">代理退傭設定</a>&nbsp;&nbsp;<button name="add" onclick="location.href='fee_add.php'" class="button_c">新增設定</button>
</div>
</div>
<div class="content">
	<table width="1024" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab">
		<tbody><tr class="m_title_over_co">
			<td>入款手續費</td>
			<td>入款手續費上限</td>
			<td>出款手續費</td>
			<td>出款手續費上限</td>
			<td>生效日期</td>
			<td>功能</td>
		</tr>
		<?php foreach ($fee as $v){?>
		<tr class="m_cen">
			<td><?=$v['in_fee']?>%</td>
			<td><?=$v['in_max_fee']?></td>
			<td><?=$v['out_fee']?>%</td>
			<td><?=$v['out_max_fee']?></td>
			<td><?=$v['valid_date']?></td>
			<td>
				<a href="fee_add.php?id=<?=$v['id']?>"> <button class="button_d" name="update">修改</button></a>
				<button class="button_d" name="delbtn" onclick="if(confirm('確定要刪除這條記錄嗎?')){document.location.href='fee_list.php?id=<?=$v['id']?>';}else{return false;}">刪除</button>
			</td>
		</tr>
		<?php }?>
	</tbody></table>
</div>
</body></html>