<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");


$data['count_bet']   = $_POST['count_bet'];           //有效总投注
$data['fc_discount'] = $_POST['fc_discount'];          //彩票优惠
$data['sp_discount'] = $_POST['sp_discount'];	      //彩票优惠
$data['mg_discount'] = $_POST['mg_discount'];    //MG视讯优惠
$data['mgdz_discount']=$_POST['mgdz_discount']; 
$data['ct_discount'] = $_POST['ct_discount'];     //CT视讯优惠
$data['bbin_discount']=$_POST['bbin_discount'];  //BB视讯优惠
$data['bbdz_discount']=$_POST['bbdz_discount'];  //BB视讯优惠
$data['lebo_discount']=$_POST['lebo_discount'];   //LEBO优惠
$data['ag_discount']=$_POST['ag_discount'];    //AG视讯优惠
$data['og_discount']=$_POST['og_discount'];    //OG视讯优惠
$data['max_discount']=$_POST['max_discount'];      //优惠上限
$data['index_id']=empty($_POST['index_id'])?0:$_POST['index_id'];//前台ID

//修改按钮跳转提交
if(!empty($_GET['id'])){
	$edit=M('k_user_discount_set',$db_config)->where("id=".$_GET['id'])->find();
}

//本页面修改
if(!empty($_POST['id'])){
	if(M('k_user_discount_set',$db_config)->where("id=".$_POST['id'])->update($data)){
         $do_log = $_SESSION['login_name'].'修改了返點優惠設定'.$_POST['id'];
         admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
		message('修改成功','./discount_set.php');
	}else{
		$do_log = $_SESSION['login_name'].'修改返點優惠設定失败'.$_POST['id'];
         admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
		message('修改失败','./discount_set.php');
	}
}elseif(empty($_POST['id']) && !empty($_POST['count_bet'])){
	$data['site_id']=SITEID;
	$addId = M('k_user_discount_set',$db_config)->add($data);
	if($addId){
		 $do_log = $_SESSION['login_name'].'添加了返點優惠設定'.$addId;
         admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
		message('添加成功','./discount_set.php');
	}else{
		 $do_log = $_SESSION['login_name'].'添加返點優惠設定失败';
         admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
		message('添加失败','./discount_set.php');
	}	
}

?>



<?php require("../common_html/header.php");?>
<body>
<div id="con_wrap">
  <div class="input_002">返點優惠設定</div>
  <div class="con_menu">
	  <a  href="discount_index.php">優惠統計</a>
      <a  href="discount_search.php">優惠查詢</a>
	  <a href="javascript:window.history.go(-1);">返回上一頁</a>
  </div>
</div>
<div class="content">
<form method="post" name="myFORM" id="vform" action="">
<input type="hidden" name="id" value="<?=$edit['id']?>">
<table style="width:500px" class="m_tab">        
	<tbody><tr class="m_title">
		<td colspan="2" align="center">返點優惠設定</td>
	</tr>
		<tr>
		<td width="30%" height="25" align="center" class="table_bg1">前台ID</td>
		<td>
			<div style="float:left"><input type="text" name="index_id" class="za_text" value="<?=$edit['index_id']?>">前台ID,请默认输入0</div>
		</td>
	</tr>
	<tr>
		<td width="30%" height="25" align="center" class="table_bg1">有效總投注</td>
		<td>
			<div style="float:left"><input type="text" name="count_bet" class="za_text" onkeydown="return Yh_Text.CheckNumber()" nullmsg="请输入投注金额" datatype="s" value="<?=$edit['count_bet']?>">以上</div>
			<div class="Validform_checktip"></div>
		</td>
	</tr>
	<tr>
		<td width="30%" height="25" align="center" class="table_bg1">彩票優惠</td>
		<td>
			<div style="float:left"><input type="text" name="fc_discount" class="za_text" onkeydown="return Yh_Text.CheckNumber()" nullmsg="请输入彩票优惠额度" datatype="s" value="<?=$edit['fc_discount']?>">%</div>
			<div class="Validform_checktip"></div>
		</td>
	</tr>
	<tr>
	<td width="30%" height="25" align="center" class="table_bg1">体育優惠</td>
		<td>			
			<div style="float:left"><input type="text" name="sp_discount" class="za_text" onkeydown="return Yh_Text.CheckNumber()" nullmsg="请输入体育优惠额度" datatype="s" value="<?=$edit['sp_discount']?>">%</div>
			<div class="Validform_checktip"></div>
		</td>
	</tr>
    <tr><td width="30%" height="25" align="center" class="table_bg1">MG視訊優惠</td>
		<td>			
			<div style="float:left"><input type="text" name="mg_discount" class="za_text" onkeydown="return Yh_Text.CheckNumber()" nullmsg="请输入MG視訊優惠额度" datatype="s" value="<?=$edit['mg_discount']?>">%</div>
			<div class="Validform_checktip"></div>
		</td>
	</tr>
	   <tr><td width="30%" height="25" align="center" class="table_bg1">MG电子優惠</td>
		<td>			
			<div style="float:left"><input type="text" name="mgdz_discount" class="za_text" onkeydown="return Yh_Text.CheckNumber()" nullmsg="请输入MG电子優惠额度" datatype="s" value="<?=$edit['mgdz_discount']?>">%</div>
			<div class="Validform_checktip"></div>
		</td>
	</tr>
    <tr><td width="30%" height="25" align="center" class="table_bg1">AG視訊優惠</td>
		<td>			
			<div style="float:left"><input type="text" name="ag_discount" class="za_text" onkeydown="return Yh_Text.CheckNumber()" nullmsg="请输入AG視訊優惠额度" datatype="s" value="<?=$edit['ag_discount']?>">%</div>
			<div class="Validform_checktip"></div>
		</td>
	</tr>
    <tr><td width="30%" height="25" align="center" class="table_bg1">BBIN視訊優惠</td>
		<td>			
			<div style="float:left"><input type="text" name="bbin_discount" class="za_text" onkeydown="return Yh_Text.CheckNumber()" nullmsg="请输入BB視訊優惠额度" datatype="s" value="<?=$edit['bbin_discount']?> ">%</div>
			<div class="Validform_checktip"></div>
		</td>
	</tr>
	   <tr><td width="30%" height="25" align="center" class="table_bg1">BBIN电子優惠</td>
		<td>			
			<div style="float:left"><input type="text" name="bbdz_discount" class="za_text" onkeydown="return Yh_Text.CheckNumber()" nullmsg="请输入BB視訊優惠额度" datatype="s" value="<?=$edit['bbdz_discount']?> ">%</div>
			<div class="Validform_checktip"></div>
		</td>
	</tr>
   <tr><td width="30%" height="25" align="center" class="table_bg1">LEBO視訊優惠</td>
		<td>			
			<div style="float:left"><input type="text" name="lebo_discount" class="za_text" onkeydown="return Yh_Text.CheckNumber()" nullmsg="请输入LEBO視訊優惠额度" datatype="s" value="<?=$edit['lebo_discount']?>">%</div>
			<div class="Validform_checktip"></div>
		</td>
	</tr>
    <tr><td width="30%" height="25" align="center" class="table_bg1">OG視訊優惠</td>
		<td>			
			<div style="float:left"><input type="text" name="og_discount" class="za_text" onkeydown="return Yh_Text.CheckNumber()" nullmsg="请输入OG視訊優惠额度" datatype="s" value="<?=$edit['og_discount']?>">%</div>
			<div class="Validform_checktip"></div>
		</td>
	</tr>

    <tr><td width="30%" height="25" align="center" class="table_bg1">CT視訊優惠</td>
		<td>			
			<div style="float:left"><input type="text" name="ct_discount" class="za_text" onkeydown="return Yh_Text.CheckNumber()" nullmsg="请输入CT視訊優惠额度" datatype="s" value="<?=$edit['ct_discount']?>">%</div>
			<div class="Validform_checktip"></div>
		</td>
	</tr>
	<tr>
		<td width="30%" height="25" align="center" class="table_bg1">優惠上限</td>
		<td>			
		    <div style="float:left"><input type="text" name="max_discount" class="za_text" onkeydown="return Yh_Text.CheckNumber()" nullmsg="请输入优惠上限" datatype="s" value="<?=$edit['max_discount']?>"></div>
			<div class="Validform_checktip"></div>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="table_bg1">
			<input type="submit" name="submit" value="確定" class="za_button">&nbsp;&nbsp;
			<input type="reset" value="重置" class="za_button">
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
<?php require("../common_html/footer.php");?>