<?
include_once("../../include/config.php");
include_once("../common/login_check.php");
include("../../class/user.php");

if(!empty($_POST)){
	$data['qsname']=$_POST['qsname'];
	$data['start_date']=$_POST['start_date'];
	$data['end_date']=$_POST['end_date'];
	$data['is_xianshi']=intval($_POST['is_xianshi']);
	$data['state']= intval($_POST['state']);

	//修改
	if($_POST['id']){
		$model = M('k_qishu',$db_config);
		$row = $model->where("id='".$_POST['id']."'")->update($data);
		if(intval($row) > 0){
			$do_log = $_SESSION['login_name'].'修改了退佣统计，期数，期数为'.$_POST['qsname'];
			admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
			message('修改成功','end_hire.php');
		}else if(intval($row) == -1){
			message('修改的期数重名！','add_qs.php?id='.$_POST['id']);
		}else{
			message('修改失败！','add_qs.php?id='.$_POST['id']);
		}
	}else{
		$data['site_id'] = SITEID;
		$model = M('k_qishu',$db_config);
		//判断是否纯在
		$row = $model->where("site_id = '".SITEID."' AND qsname ='".$data['qsname']."'")->find();
		if(count($row) > 0 ){
			message('期数重复！','add_qs.php');exit();
		}
		$rr = $model->add($data);
		if($rr){
			$do_log = $_SESSION['login_name'].'添加了退佣统计，期数为'.$_POST['qsname'];
			admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log);
			message('添加成功','end_hire.php');
		}else{
			message('添加失败！','add_qs.php');
		}


	}
}
//读取
if($_GET['id']){
	$qishu=M('k_qishu',$db_config)->where("id='".$_GET['id']."'")->find();
}
?>

<?php require "../common_html/header.php";?>

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
    <a href="end_hire.php">期數管理</a>
	<a href="fee_list.php">手續費設定</a>
	<a href="endhire_list.php">代理退傭設定</a>

</div>
</div>
<div class="content" style="width:500px;">
	<form name="addForm" action="add_qs.php" method="post" id="myform" class="vform">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab" style="border:0px;">
		<tbody><tr class="m_title_over_co">
			<td colspan="2">新增期數</td>
		</tr>
		<tr><input type='hidden' name='id' value="<?=$qishu['id']?>">
			<td class="table_bg1" align="right">期數名稱：</td>
			<td>
				<div style="float:left"><input type="text" class="za_text" id="qsname" name="qsname" value="<?if($qishu['qsname']!=''){echo $qishu['qsname'];}?>"></div>
				<div class="Validform_checktip" style="float:left" id="qsnames"></div>
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="right">是否显示：</td>
			<td>
				<div style="float:left"><input type="radio" class="" id="" <?php if ($qishu['is_xianshi'] == 0) {echo "checked=''checked";}
?> name="is_xianshi" value="0">是<input type="radio" class="" id="" <?php if ($qishu['is_xianshi'] == 1) {echo "checked=''checked";}
?> name="is_xianshi" value="1">否</div>
				<div class="Validform_checktip" style="float:left" id="qsnames"></div>
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="right">当前状态：</td>
			<td>
				<div style="float:left"><input type="radio" class="" id="" <?php if ($qishu['state'] == 0) {echo "checked=''checked";}
?> name="state" value="0">正常<input type="radio" class="" id="" <?php if ($qishu['state'] == 1) {echo "checked=''checked";}
?> name="state" value="1">停止</div>
				<div class="Validform_checktip" style="float:left" id="qsnames"></div>
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="right">開始日期：</td>
			<td>
				<div style="float:left"><input type="text" name="start_date" id="start_date" value="<?php if($qishu['start_date']!=''){echo $qishu['start_date'];}else{ echo date('Y-m-d');}?>" class="za_text Wdate"  onClick="WdatePicker()"  readonly="readonly"></div>
				<div class="Validform_checktip" style="float:left" id="start_dates"></div>
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="right">結束日期：</td>
			<td style="text-align:left;border-right-style:none;">
				<div style="float:left"><input type="text" name="end_date" value="<?php if($qishu['end_date']!=''){echo $qishu['end_date'];}else{echo date('Y-m-d');}?>" id="end_date" class="za_text Wdate"  onClick="WdatePicker()" readonly="readonly"></div>
				<div class="Validform_checktip" style="float:left"  id="end_dates"></div>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="button" name="subbtn" class="button_a" onclick="check();" value=" 確 定 ">&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="reset" name="cls" value="重 置 " class="button_a">
			</td>
		</tr>
	</tbody></table>
	</form>
</div>
<script type="text/javascript">
	function check(){
		var qsname=$("#qsname").val();
		if(qsname==''){
			$("#qsnames").html("期数名称不能为空！");
			return false;
		}else{
			$("#qsnames").html("");
		}
		//alert(qsname);
		var start_date=$("#start_date").val();
		if(start_date==''){
			$("#start_dates").html("期数名称不能为空！");
			return false;
		}else{
			$("#start_dates").html("");
		}

		var end_date=$("#end_date").val();
		if(end_date==''){
			$("#end_dates").html("期数名称不能为空！");
			return false;
		}else{
			$("#end_dates").html("");
		}

		document.getElementById("myform").submit();
	}
</script>

</body></html>