<?php
include_once "../include/config.php";
include_once "./common/login_check.php";
include_once "./comm_menu.php";

//账号权限判断
if (isset($_SESSION["adminid"])) {
$notice = M('site_notice',$db_config)->where("notice_state = '1' and notice_cate = '6'")->order("notice_date DESC")->find();
if(!isset($_COOKIE['notice']) && !empty($notice)){
  $notice['notice_content'] = preg_replace("/<br \s*\/?\/>/i", "\n", $notice['notice_content']);
  $notice['notice_content'] = str_replace("&lt;br /&gt;", "", $notice['notice_content']);
  $notice['notice_content'] = str_replace("&amp;lt;br /&amp;gt;", "", $notice['notice_content']);

  echo '<script>window.onload=function(){alert("系统公告：'.$notice['notice_content'].'");}</script>';
  setcookie("notice", "111", time() + 3600*3);
}

	$quanxian = trim($_SESSION["quanxian"]);

	foreach ($menu as $key => $val) {
		if ($quanxian == 'sadmin') {
			//表示超管
			$menu_w = substr($key, 0, 1);
			switch ($menu_w) {
				case 'a':
					$account[] = $val;
					break;
				case 'b':
					$note[] = $val;
					break;
				case 'c':
					$report[] = $val;
					break;
				case 'd':
					$result[] = $val;
					break;
				case 'e':
					$cash[] = $val;
					break;
				case 'f':
					$other[] = $val;
					break;
			}
		} elseif (strpos($quanxian, $key) !== false) {
			$menu_w = substr($key, 0, 1);
			switch ($menu_w) {
				case 'a':
					$account[] = $val;
					break;
				case 'b':
					$note[] = $val;
					break;
				case 'c':
					$report[] = $val;
					break;
				case 'd':
					$result[] = $val;
					break;
				case 'e':
					$cash[] = $val;
					break;
				case 'f':
					$other[] = $val;
					break;
			}
		}
	}
}

if (!empty($account)) {
	$accState = " style=\"background-position:0 0\" class=\"menu_hover\"";
    $noteMeD = "style=\"display:none\"";
    $repMeD = "style=\"display:none\"";
    $resMeD = "style=\"display:none\"";
    $casMeD = "style=\"display:none\"";
    $othMeD = "style=\"display:none\"";
}else{
     if (!empty($note)) {
     	$noteState = "style=\"background-position:0 0\" class=\"menu_hover\"";
     	    $repMeD = "style=\"display:none\"";
		    $resMeD = "style=\"display:none\"";
		    $casMeD = "style=\"display:none\"";
		    $othMeD = "style=\"display:none\"";
     }else{
     	if (!empty($report)) {
     		$repState = "style=\"background-position:0 0\" class=\"menu_hover\"";
     		$resMeD = "style=\"display:none\"";
		    $casMeD = "style=\"display:none\"";
		    $othMeD = "style=\"display:none\"";
     	}else{
     		if (!empty($result)) {
     			$rusState = "style=\"background-position:0 0\" class=\"menu_hover\"";
			    $casMeD = "style=\"display:none\"";
			    $othMeD = "style=\"display:none\"";
     		}else{
     			if (!empty($cash)) {
     				$casState = "style=\"background-position:0 0\" class=\"menu_hover\"";
     				$casMeD = "style=\"display:none\"";
     			}else{
     				if (!empty($other)) {
     					$othState = "style=\"background-position:0 0\" class=\"menu_hover\"";

     				}
     			}
     		}
     	}
     }
}



?>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>left</title>
<link  rel="stylesheet"  media="all"  href="./public/<?=$_SESSION['site_id']?>/css/styleCss.css">
<link  rel="stylesheet"  media="all"  href="./public/<?=$_SESSION['site_id']?>/css/reset.css">
<script  src="./public/<?=$_SESSION['site_id']?>/js/jquery-1.7.min.js"  type="text/javascript"></script>
<script  type="text/javascript">
var newscount = "0";
var lxvar = "1";
var langx = "zh-tw";
var toflag=false;
//弹出框公告程序
newscount = 0;	//如果想开启弹出窗，只需注释本行
$(document).ready(function () {
	setInterval("RefTime()",1000);

		// Set the click
		var menuobj=$("#nav_main_box ul li span a");
		var menuSub=$(".nav_second_wrap");
		var oldMenuIndex=0;
		menuobj.click(function () {
			$(this).parents().find("a").removeClass('menu_hover');
			$(this).parents().find("a").attr('style','');
			$(this).addClass('menu_hover');
			$(this).attr('style','background-position:0 0');
			$(menuSub[oldMenuIndex]).hide();
			var index=$(this).parent().index();
			var obj=$(menuSub[index]);
			var click_i = 0;
			 if(index==5){
				click_i = 7;
				toflag = true;
			}
			obj.show();
			if(toflag){
				obj.find("a")[click_i].click();
				$(obj.find("a")[click_i]).css("color","#ff0000");
				oldMenuIndex=index;
			}
			else
				toflag=true;
		});
		menuobj[5].click();

		$(".nav_second_wrap a").click(function(){
			$(this).parent().find("a").css('color','#3B2D1B');
			$(this).css('color','#ff0000');
			});
});

//设置美东时间
var mddate="<?php echo date('Y') . '/' . date('m') . '/' . date('d') . ' ' . date('H') . ':' . date('i') . ':' . date('s');?>";
var dd2=new Date(mddate);
function RefTime()
{

     dd2.setSeconds(dd2.getSeconds()+1);
	var myYears = ( dd2.getYear() < 1900 ) ? ( 1900 + dd2.getYear() ) : dd2.getYear();
	$("#vlock").html('美東時間'+'：'+myYears+'年'+fixNum(dd2.getMonth()+1)+'月'+fixNum(dd2.getDate())+'日 '+time(dd2));
	if(dd2.getSeconds()%30 == 0){
	   $.ajax({
         url: "./user_online.php",
         type: "GET",
         dataType: "json",
         success: function(data){
             $("#user_num").html(data);
         }
     });
	}
}
function time(vtime){
    var s='';
    var d=vtime!=null?new Date(vtime):new Date();
    with(d){
        s=fixNum(getHours())+':'+fixNum(getMinutes())+':'+fixNum(getSeconds())
    }
    return(s);
}
function fixNum(num){
    return parseInt(num)<10?'0'+num:num;
}
</script>
<script>
$(function(){
	$("#ag_select option").click(function(){
		var ag=$(this).val();
		parent.admin_func.location.href = ag;

	})
})
</script>
</head>
<body>
<div  id="wrap_box">
	<div  id="header_wrap">
		<div  id="logo_box">
	    	<img  src="./public/<?=$_SESSION['site_id']?>/images/mlogo.png" style="margin-top:5px;" alt="logo">
		</div>
  <!--
		<div  id="notice_box" style="left:220px;width: 55%;float: left; margin-top: 5px; background: url('../G_admin/public/t/images/notice_pic.png') no-repeat left top; background-color: #FFF; border: 1px solid #EEE; width: 55%; margin-left: 1%; height: 25px; color: #B8B8B8; padding-left: 100px; line-height: 25px; border-radius: 4px;">
    <marquee  style="cursor: pointer;" onMouseOut="this.start();" onMouseOver="this.stop();" id="msgNews" direction="left" scrolldelay="5" scrollamount="2">&nbsp;&nbsp;<?=$notice['notice_content'] ?>&nbsp;&nbsp;</marquee>
		</div>
    -->
		<div  id="nav_sub_box" >
		    <span style="color: rgb(163, 208, 245);">在线会员:<font style="font-size:14px;" id="user_num"></font></span> |
	    	<span  id="vlock"></span> |
	    	<span>登陸帳戶：<?=$_SESSION["login_name_1"]?> &nbsp;|&nbsp;原始帳戶：<?=$_SESSION["login_name"]?> </span> |
		    <a  href="./set_pwd.php"  target="admin_func"  style="">修改密碼</a> |
	     	<a  href="./out.php"  target="_top"  style="">安全退出</a>
		</div>
		<div  id="nav_main_box">
			<ul>
				<li>
				    <?php if (!empty($account)): ?>
					<span>
					<a  href="#" <?=$accState?> >帳號管理</a></span>
					<?php endif ?>
					<?php if (!empty($note)): ?>
					<span><a  href="#" <?=$noteState?>>即時注單</a></span>
					<?php endif ?>
					<?php if (!empty($report)): ?>
					<span><a  href="#" <?=$repState?>>報表查詢</a></span>
					<?php endif ?>
					<?php if (!empty($result)): ?>
					<span><a  href="#" <?=$rusState?>>賽果/赔率</a></span>
					<?php endif ?>
					<?php if (!empty($cash)): ?>
					<span><a  href="#" <?=$casState?>>现金系统</a></span>
					<?php endif ?>
					<?php if (!empty($other)): ?>
					<span><a  href="#" <?=$othState?>>其它</a></span>
					<?php endif ?>
				</li>
			</ul>
		</div>
	</div>

<!-- 账号管理 -->
<?php if (!empty($account)): ?>
<div  class="nav_second_wrap"  style="">
   <?php if (!empty($account)) {
           foreach ($account as $key => $val) {
	?>
    <a href="<?=$val['url']?>" target="admin_func"  style=""><?=$val['name']?></a>
   <?php }}?>

</div>
<?php endif ?>
<?php if (!empty($note)): ?>
<!-- 注单管理 -->
<div  class="nav_second_wrap" <?=$noteMeD?> >
    <?php if (!empty($note)) {
    	foreach ($note as $key => $val) {
	?>
    <a href="<?=$val['url']?>" target="admin_func"  style=""><?=$val['name']?></a>
   <?php }}?>
</div>
<?php endif ?>
<?php if (!empty($report)): ?>
<!-- 报表管理 -->
<div  class="nav_second_wrap"  <?=$repMeD?>>
   <?php if (!empty($report)) {
   	foreach ($report as $key => $val) {
	?>
    <a href="<?=$val['url']?>" target="admin_func"  style=""><?=$val['name']?></a>
   <?php }}?>
</div>
<?php endif ?>
<?php if (!empty($result)): ?>
<!-- 赛果管理 -->
<div  class="nav_second_wrap" <?=$resMeD?>>
    <?php if (!empty($result)) {
    	foreach ($result as $key => $val) {
	?>
    <a href="<?=$val['url']?>" target="admin_func"  style=""><?=$val['name']?></a>
   <?php }}?>

</div>
<?php endif ?>

<?php if (!empty($cash)): ?>
<!-- 财务管理 -->
<div  class="nav_second_wrap" <?=$casMeD?>>
      <?php if (!empty($cash)) {
      	foreach ($cash as $key => $val) {
	?>
    <a href="<?=$val['url']?>" target="admin_func"  style=""><?=$val['name']?></a>
   <?php }}?>
</div>
<?php endif ?>
<?php if (!empty($other)): ?>
<!-- 其它管理 -->
<div  class="nav_second_wrap" <?=$othMeD?>>
     <?php if (!empty($other)) {
     	foreach ($other as $key => $val) {
	?>
    <a href="<?=$val['url']?>" <?php if ($val['type'] == '_'): ?> target="_blank"<?php endif ?> <?php if ($val['type'] != '_'): ?> target="admin_func"<?php endif ?> style=""><?=$val['name']?></a>
   <?php }
   } ?>
</div>
<?php endif ?>
	<diiv  id="con_wrap">

	</div>
</div>


</body>
</html>