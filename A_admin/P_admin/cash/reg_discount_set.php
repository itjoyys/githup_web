<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");

$map['discount_money']=$_POST['num'];//优惠金额
if ($_POST['num'] > 200) {
	message("优惠金额不能大于200");
}
$map['discount_bet']=$_POST['reg'];//打码量
$m['site_id'] = SITEID;
$data=M('k_user_apply',$db_config)->where($m)->find();
if(!empty($_POST['id'])){
	if(M('k_user_apply',$db_config)->where("id=".$_POST['id'])->update($map)){
		$log = "修改申請會員優惠設定";
		admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$log); 	
		message('设定成功');
	}
}elseif(isset($_POST['num'])){
	$map['site_id']=SITEID;
	if(M('k_user_apply',$db_config)->add($map)){
		$log = "修改申請會員優惠設定";
		admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$log); 	
	    message('设定成功');
	}	
} 

?>

<?php require("../common_html/header.php");?>
<body>
<div id="con_wrap">
  <div class="input_002">申請會員優惠設定</div>
  <div class="con_menu">
	  <a  href="discount_index.php">優惠統計</a>
      <a  href="discount_search.php">優惠查詢</a>
	  <a href="javascript:void(0);" onclick="javascript:history.go(-1);" style="cursor:pointer ;">返回上一頁</a> 
	  
  </div>
</div>
<div class="content">
<form method="post" name="myFORM" id="vform" action="">
<input type="hidden" name="id" value="<?php echo $data['id'];?>">
<table style="width:500px" class="m_tab">        
	<tbody><tr class="m_title">
		<td colspan="2" align="center">申請會員優惠設定</td>
	</tr>
	<tr>
		<td width="30%" height="25" align="center" class="table_bg1">申請會員贈送優惠</td>
		<td>
			<div style="float:left"><input type="text" name="num" class="za_text" style="width:80px" onkeydown="return Yh_Text.CheckNumber()" nullmsg="请输入金额" datatype="s" value="<?=$data['discount_money']?>"> </div><div class="Validform_checktip"></div><br><div>&nbsp;<font color="red">* 為0時加入會員不贈送優惠 範圍：0~200元</font></div>
		</td>
	</tr>
    <tr>
		<td width="30%" height="25" align="center" class="table_bg1">優惠打碼</td>
		<td>
			<div style="float:left"><input type="text" name="reg" class="za_text" style="width:50px!important" onkeydown="return Yh_Text.CheckNumber()" nullmsg="请输入數字" datatype="s" value="<?=$data['discount_bet']?>">&nbsp;倍</div><div style="float:left" class="Validform_checktip"></div><div>
		</div></td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="table_bg1">
			<input type="submit" name="submit" value="確定" class="za_button">&nbsp;&nbsp;
			<!-- <input type="reset" value="重置" class="za_button"> -->
		</td>	
	</tr>	
	
</tbody></table> 
</form>
</div>
<script type="text/javascript">

$(function(){
	$("#vform").Validform({
		tiptype:2
	});
});

</script>
	<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>