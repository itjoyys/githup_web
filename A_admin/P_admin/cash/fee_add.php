<?php 
include_once("../../include/config.php");
include_once("../common/login_check.php");
include("../../class/user.php");


if($_POST){
	$data['in_fee']=$_POST['infeeper'];
	$data['in_max_fee']=$_POST['maxinfee'];
	$data['out_fee']=$_POST['outfeeper'];
	$data['out_max_fee']=$_POST['maxoutfee'];
	$data['valid_date']=$_POST['validdate'];
	
	//修改
	if($_POST['id']){
		if(M('k_fee_set',$db_config)->where("id='".$_POST['id']."'")->update($data)){
			$do_log = $_SESSION['login_name'].'修改了退佣统计，手续费设定'; 
			admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
			message('修改成功','fee_list.php');
		}else{
			message('修改失败！','fee_add.php?id='.$_POST['id']);
		}
	}
	//添加
	else{
		$data['site_id']=SITEID;
		if(M('k_fee_set',$db_config)->add($data)){
			$do_log = $_SESSION['login_name'].'添加了退佣统计，手续费设定'; 
			admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
			message('添加成功','fee_list.php');
		}else{
			message('添加失败！','fee_add.php');
		}
	
	}
}
//读取
if($_GET['id']){
	$fee=M('k_fee_set',$db_config)->where("id='".$_GET['id']."'")->find();
}
?>

<?php require("../common_html/header.php");?>
<body>
<script type="text/javascript">

$(function(){
	$("div.con_menu a:nth-child(4)").attr("style","color:red");	
});

</script>
<div id="con_wrap">
<div class="input_002">手續費設定</div>
<div class="con_menu">
	<a href="agent_count.php">退佣统计</a>
    <a href="agent_search.php">退佣查询</a>
    <a href="end_hire.php">期數管理</a>
	<a href="fee_list.php" style="color:red">手續費設定</a>
	<a href="endhire_list.php">代理退傭設定</a>&nbsp;&nbsp;<a href="javascript:history.go(-1)">返回到列表</a>
</div>
</div>
<div class="content" style="width:500px;">
	<form name="addForm" action="" method="post" class="vform">
	<input type='hidden' name='id' value="<?php echo $fee['id'];?>">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab" style="border:0px;">
		<tbody><tr class="m_title_over_co">
			<td colspan="2">增加手續費設定</td>
		</tr>
		<tr>
			<td class="table_bg1" align="right">入款手續費：</td>
			<td style="text-align:left;">
				<div style="float:left"><input type="text" name="infeeper" value="<?if($fee['in_fee']!=''){echo $fee['in_fee'];}?>" datatype="*" nullmsg="请输入入款手续费" errormsg="入款手续费只能填写数字" class="za_text" onkeydown="return Yh_Text.CheckNumber()">&nbsp;%</div>
				<div class="Validform_checktip" style="float:left"></div>
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="right">入款手續費上限：</td>
			<td style="text-align:left;">
				<div style="float:left"><input type="text" name="maxinfee" value="<?if($fee['in_max_fee']!=''){echo $fee['in_max_fee'];}?>" datatype="n" nullmsg="请输入入款手续费上限" errormsg="入款手续费上限只能填写数字" class="za_text" onkeydown="return Yh_Text.CheckNumber2()"></div>
				<div class="Validform_checktip" style="float:left"></div>
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="right">出款手續費：</td>
			<td style="text-align:left;">
				<div style="float:left"><input type="text" name="outfeeper" value="<?if($fee['out_fee']!=''){echo $fee['out_fee'];}?>" datatype="*" nullmsg="请输入出款手续费" errormsg="出款手续费只能填写数字" class="za_text" onkeydown="return Yh_Text.CheckNumber()">&nbsp;%</div>
				<div class="Validform_checktip" style="float:left"></div>
			</td>
		</tr>
		<tr>
			<td class="table_bg1" align="right">出款手續費上限：</td>
			<td style="text-align:left;">
				<div style="float:left"><input type="text" name="maxoutfee" value="<?if($fee['out_max_fee']!=''){echo $fee['out_max_fee'];}?>" datatype="n" nullmsg="请输入出款手续费上限" errormsg="出款手续费上限只能填写数字" onkeydown="return Yh_Text.CheckNumber2()" class="za_text"></div>
				<div class="Validform_checktip" style="float:left"></div>
			</td>
		</tr>
		<tr> 
			<td class="table_bg1" align="right">生效日期：</td>
			<td style="text-align:left;">
				<div style="float:left"><input type="text" name="validdate" value="<?if($fee['valid_date']!=''){echo $fee['valid_date'];}?>" class="za_text Wdate" onclick="WdatePicker()" readonly="readonly"></div>
				
			
				
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
<script type="text/javascript">

 $(function(){
	 $(".vform").Validform({
		 tiptype:2,
		 tipSweep:false
	 });
 })

</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="clear:both">
		<tbody><tr>
			<td align="center" height="50">4.4E-5</td>
		</tr>
	</tbody></table>


<script src="./fee_add_files/yhinput.js" type="text/javascript"></script></body></html>