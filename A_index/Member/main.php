<?php 
if($_GET['url']==1){//线上存款
	$str='./cash/set_money.php';
}elseif ($_GET['url']==2) {//线上取款
	$str='./cash/get_money.php';
}elseif ($_GET['url']==3) {//额度转换
	$str='./cash/zr_money.php';
}elseif ($_GET['url']==4) {//会员中心
	$str='./account/userinfo.php';
}elseif ($_GET['url']==5) {//投注记录
	$str='./trading_log/record_ds.php';
}elseif ($_GET['url']==6) {//投注报表
	$str='./bet/count.php';
}elseif ($_GET['url']==7) {//代理申请
	$str='./agent/daili_shenqing.php';
}elseif ($_GET['url']==8) {//未读信息
	$str='./notice/sms.php';
}elseif ($_GET['url']==9) {//未读信息
	$str='./notice/lottery_news.php';
}
?>
<!doctype html>
<html lang="en" style="height:426px;">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<frameset cols="*,220,780,*" rows="" frameborder="NO" border="0" framespacing="0"> 
	<frame id="k_mem11" scrolling="no" name="k_meml11" src="./gray.php" frameborder="0"></frame>

    <frame id="k_mem" scrolling="no" name="k_meml" src="./main_left.php?url=<?=$_GET['url']?>" frameborder="0"></frame>

    <frame id="k_memr"  scrolling="no" name="k_memr" src="<?=$str?>" frameborder="0"></frame>

	<frame id="k_mem22" scrolling="no" name="k_meml22" src="./gray.php" frameborder="0"></frame>

</frameset>


</html>

