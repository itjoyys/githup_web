<?php 
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../../class/user.php");
include_once("../../class/audit.class.php");

//即时稽核  上次取款时间或者存款时间 到当前时间的统计
//每次存款都要对余额稽核
//取款稽核
//優惠稽核 两次优惠退水的时间
//常態稽核
//
//优惠退水  在线存款 公司入款 存入取出 
//查询会员
if ($_POST['check'] == 't') {
	if (empty($_POST['username'])) {
    	message('请输入会员账号');
	}else{
		$u = M('k_user',$db_config);
		$map['site_id'] = SITEID;
		$map['username'] = $_POST['username'];
		$u_uid = $u->where($map)->getField('uid');
		if (empty($u_uid)) {
			  message('会员不存在');
		}else{
        $audit = A($u_uid);
        $audit_data = $audit->admin_show();
        $total = $audit_data['count_dis'] + $audit_data['count_xz'] + $audit_data['out_fee'];
        $total = sprintf("%.2f", $total); 
    }     
}
}
?>

<?php $title="即时稽核查询"; require("../common_html/header.php");?>
<body>
<div id="con_wrap">
  <div class="input_002">即時稽核查詢</div>
  <div class="con_menu">
  			<form method="post" name="action_form">
            <input type="hidden" name="check" value="t">
			帳號： 
			<input class="za_text" size="10" name="username" value="<?=$_POST['username']?>">&nbsp;
			<input class="button_d" value="查詢" type="submit"></form>
	</div>
</div>

<div class="content">
<div style="line-height:25px;font-weight:bold">
<span style="color:#0202FE">優惠稽核：<br></span>
自出款後第一次存款開始之後：<br>
總有效投注：<?=$audit_data['betAll']?>&nbsp;&nbsp;(所有視訊有效投注)
<div style="width: 100%;">
    <?=$audit_data['content']?>
</div>

<br>
<div style="margin-bottom: 5px;">
<?php if (!empty($audit_data['count_xz'])): ?>
    <span style="color:#ff0000">未達常態性稽核！&nbsp;需扣除行政費：<?=$audit_data['count_xz']?>元<br></span>
<?php endif ?>

<?php if (!empty($audit_data['count_dis'])): ?>
   <span style="color:#ff0000">未達優惠稽核！&nbsp;需扣除優惠：<?=sprintf("%.2f",$audit_data['count_dis'])?>元<br></span>
<?php endif ?>
<?php if (!empty($audit_data['out_fee'])): ?>
	<span style="color:#ff0000">需扣除手續費:<?=$audit_data['out_fee']?>元<br></span>
<?php endif ?>

<span style="color:#fff;background-Color:#900;padding:2px">優惠稽核 + 常態性稽核 + 手续费 共需扣除：<?=$total?>元</span><br><br>
<?php if (!empty($u_uid)) {
?>
此次出款時間為：<?=date('Y-m-d H:i:s')?>
<?php }?>
</div>
</div>
</div>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>