<?php
include_once("../../include/config.php");
include_once("../../common/login_check.php");

$rows2= M('k_user',$db_config)->field('level_id')->where("uid = '$_SESSION[uid]'")->select();
if($_GET['action']=='d'){
	
	foreach ($_GET['msg_id'] as $k => $v) {
		$sql = "update k_user_msg set is_delete = '1' where  msg_id='".$v."'";
		$q1=$mysqlt->query($sql);
	}
	echo '<script>alert("删除成功!")</script>';
	echo '<script>self.location.href="sms.php";</script>';exit;
}

if($_GET["act"]=="delall" || $_GET["act"]=="del"){
	$msg_id = $_GET["id"];
	if($msg_id<0){
		$mysqlt->query("update k_user_msg set is_delete = '1' where  uid=".$_SESSION["uid"]);
	}else{	
		$sql = "update k_user_msg set is_delete = '1' where  msg_id='".$msg_id."'";
		
		$mysqlt->query($sql);

	}
	echo '<script>alert("删除成功!")</script>';
	echo '<script>self.location.href="sms.php";</script>';exit;
}



if($_POST['type']=='look'){
	$msg_id=$_POST['uid'];
	$sql = "update k_user_msg set islook = '1' where  msg_id='".$msg_id."'";
	if($mysqlt->query($sql)){
		echo 1; exit;
	}
}

$id=M('k_user_msg',$db_config)->where("(uid='".$_SESSION["uid"]."' or (uid = '' and (level_id = '".$rows2['level_id']."' or level_id = '-1')))   and is_delete = '0' and site_id = '".SITEID."'")->field('msg_id')->order('islook asc,msg_id desc')->select();
if($id){
	foreach ($id as $v) {
		$bid .=	$v['msg_id'].',';
	}
	$id		=	rtrim($bid,',');
}

$count=M('k_user_msg',$db_config)->where("msg_id in($id)")->count();
//分页
$perNumber=isset($_GET['page_num'])?$_GET['page_num']:10; //每页显示的记录数
$totalPage=ceil($count/$perNumber); //计算出总页数
$page=isset($_GET['page'])?$_GET['page']:1;
if($totalPage<$page){
  $page = 1;
}

$startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
$limit=$startCount.",".$perNumber;
$data = array();

$data = M('k_user_msg',$db_config)->where("msg_id in($id)")->field("islook,msg_title,msg_time,msg_info,msg_id")->order('islook asc,msg_id desc')->limit($limit)->select();
?>

<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Document</title>
		<link rel="stylesheet" href="../public/css/index_main.css" />
		<link rel="stylesheet" href="../public/css/standard.css" />
		<script src="../public/js/jquery-1.8.3.min.js"></script>
	</head>
	<body  style="BACKGROUND: url(../public/images/content_bg.jpg) repeat-y left top;" >
		<div id="MAMain" style="width:767px">
			<div id="MACenter-content">
				<div id="MACenterContent">
					<div id="MNav">
						<span class="mbtn">个人信息</span>
						<div class="navSeparate"></div>
					</div>
					<div id="MMainData" style="overflow-y:scroll; height:370px">
						<h2 class="MSubTitle">个人信息</h2>
						<form id='myFORM' method="get">
							<span style="height:17px;line-height:20px;float:right;">&nbsp; <?=$totalPage?> 頁&nbsp;</span>
							<select id="page" name="page" class="za_select" style="float:right;">
								<?php  
								for($i=1;$i<=$totalPage;$i++){
									if($i==$page){
										echo  '<option value="'.$i.'" selected>'.$i.'</option>';
									}else{
										echo  '<option value="'.$i.'">'.$i.'</option>';
									}  
								} 
								?>
							</select>
						</form>
						<form name="form" id='form' method="get">
							<input type="hidden" name="action" value="d"/>
							<table class="MMain" border="1">
								<thead>
									<tr>
										<!--<th style="width:30px"><input type="checkbox" id="checkall" onclick="ckall()"/></th>-->
										<th align=middle>状态</th>
										<th align=middle>标题</th>
										<th align=middle>发布时间</th>
										<!--<th align=middle>操作</th>-->
									</tr>
								</thead>
								<tbody>
									<?php if($data){?>
									<?php foreach ($data as $k => $v) {
									?>
									<tr>
										<!--<td><input type="checkbox" class="check" name="msg_id[]" value="<?=$v["msg_id"]?>"/></td>-->
										<td align=middle class="islook<?=$v["msg_id"]?>"><?=$v["islook"] ? '<FONT color=#ff0000>已读</FONT>' : '<b>未读</b>'?></td>
										<td align=middle><A  target="k_memr"   href="" class="toggle"><?= strlen(trim($v["msg_title"])) ? $v["msg_title"] : '无标题信息' ?></A></td>
										<td align=middle><?=date("Y-m-d",strtotime($v["msg_time"]))?></td>
										<!--<td align=middle><a class="button_d" href="sms.php?id=<?=$v["msg_id"]?>&act=del" onclick="return confirm('确定删除?')">删除</a></td>-->
										<input type="hidden" value="<?=$v["msg_id"]?>"/>
									</tr>
									<tr style="display:none;height:150px;">
										<td colspan="5"><?=$v["msg_info"]?></td>
									</tr>
									<?php } }else{?>
									<tr align="center">
										<td colspan=3>暂时没有个人消息 </td>
									</tr>
									<?php }?>
									<!--
									<TR>
									<Td colSpan=5 align=middle>
									<DIV class=Pagination>  
									<input  class="button_d" type="submit" onclick="return confirm('确定删除选中的信息?') " value='删除勾选' />
									</DIV></Td></TR>
									-->
								</tbody>
							</table>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

<script>
	//全选。全取消
	function ckall(){
		if($('#checkall').is(':checked')){
			$('.check').attr('checked',true);
		}else{
			$('.check').attr('checked',false);
		}
	}
</script>

<script>
	$(function(){
		$(".toggle").toggle(
			function(){
				$(this).parents("tr").next("tr").slideDown();
				var uid=$(this).parents("tr").find("input[type='hidden']").val();
				$.ajax({
					type: "POST",
					url: "sms.php",
					data: "type=look&uid="+uid,
					success: function(msg){
						if(msg==1){
							$('.islook'+uid).html('<FONT color=#ff0000>已读</FONT>');
						}
					}
				});
			},
			function(){
			$(this).parents("tr").next("tr").slideUp();
			}
		);

		window.onload=function(){
		document.getElementById("page").onchange=function(){
		document.getElementById('myFORM').submit()
		}
		}
	})
</script>

<style>
.button_d{background:-moz-linear-gradient(center top , white, #ededed) repeat scroll 0 0 rgba(0, 0, 0, 0);border:1px solid #b7b7b7;color:#3d3934;border-radius:0.5em;box-shadow:0 1px 2px rgba(97, 97, 97, 0.2);font:12px/100% Arial,Helvetica,sans-serif;padding:0.45em 0.5em 0.5em;display:inline-block;cursor: pointer;}
</style>