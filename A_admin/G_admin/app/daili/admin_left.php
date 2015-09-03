
<?php
include_once "../../../include/config.php";
include_once "../../../include/login_config.php";
include_once "../../../lib/class/model.class.php";
include_once("../../comm_menu.php");

$uid=$_SESSION['adminid'];
//账号权限判断
if(isset($_SESSION["adminid"])){
  $notice = M('site_notice',$db_config)->where("notice_state = '1' and notice_cate = '6'")->order("notice_date DESC")->find();
if(!isset($_COOKIE['notice']) && !empty($notice)){
  $notice['notice_content'] = preg_replace("/<br \s*\/?\/>/i", "\n", $notice['notice_content']);
  $notice['notice_content'] = str_replace("&lt;br /&gt;", "", $notice['notice_content']);
  $notice['notice_content'] = str_replace("&amp;lt;br /&amp;gt;", "", $notice['notice_content']);

  echo '<script>window.onload=function(){alert("系统公告：'.$notice['notice_content'].'");}</script>';
  setcookie("notice", "111", time() + 3600*3);
}
	$quanxian=trim($_SESSION["quanxian"]);
    foreach ($menu as $key => $val) {
    	if ($quanxian == 'agadmin') {

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
		}elseif (strpos($quanxian,$key) !== false) {
    		$menu_w = substr($key,0,1);
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
 <html><head>
<title>left</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<link rel="stylesheet" media="all" href="../../public/<?=$_SESSION['site_id']?>/css/styleCss.css">
<link rel="stylesheet" media="all" href="../../public/<?=$_SESSION['site_id']?>/css/reset.css">
<script src="../../public/<?=$_SESSION['site_id']?>/js/jquery-1.7.2.min.js" type="text/javascript"></script>

<script type="text/javascript">
var newscount = "0";
var lxvar = "5";
var langx = "zh-tw";
var toflag=false;
//弹出框公告程序
var dd2=new Date("2015/01/28 22:51:44");
newscount = 0;	//如果想开启弹出窗，只需注释本行
$(document).ready(function () {

	/*$.get('/app/ssc/data/msg_unread.php?uid=<?=$uid ?>',function(msg){
		if(Number(msg)>0)$("#msg").html('('+msg+')');
		//alert(json);
	});*/
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
			obj.show();
			if(toflag){
				obj.find("a")[0].click();
				$(obj.find("a")[0]).css("color","#ff0000");
				oldMenuIndex=index;
			}
			else
				toflag=true;
		});
		menuobj[0].click();

		$(".nav_second_wrap a").click(function(){
			$(this).parent().find("a").css('color','#3B2D1B');
			$(this).css('color','#ff0000');
			});
});

//设置美东时间
var mddate="<?php echo date('Y').'/'.date('m').'/'.date('d').' '.date('H').':'.date('i').':'.date('s');;?>";
var dd2=new Date(mddate);
function RefTime()
{
	dd2.setSeconds(dd2.getSeconds()+1);
	var myYears = ( dd2.getYear() < 1900 ) ? ( 1900 + dd2.getYear() ) : dd2.getYear();
	$("#vlock").html('美東時間'+'：'+myYears+'年'+fixNum(dd2.getMonth()+1)+'月'+fixNum(dd2.getDate())+'日 '+time(dd2));
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

</head><body>
<div id="wrap_box">
<!-- header -->
	<div id="header_wrap">
		<div id="logo_box">
		<?php if($_SESSION['site_id'] == 'hun'||$_SESSION['site_id'] == 'jun'){
			if($_SESSION['index_id'] == 'a'){ ?>
				<img src="../../public/<?=$_SESSION['site_id']?>/images/mlogoa.png" alt="logo" height="80">
			<?php }elseif($_SESSION['index_id'] == 'b'){?>
			<img src="../../public/<?=$_SESSION['site_id']?>/images/mlogob.png" alt="logo" height="80">
			<?php }elseif($_SESSION['index_id'] == 'c'){?>
			<img src="../../public/<?=$_SESSION['site_id']?>/images/mlogoc.png" alt="logo" height="80">
			<?php }elseif($_SESSION['index_id'] == 'd'){?>
			<img src="../../public/<?=$_SESSION['site_id']?>/images/mlogod.png" alt="logo" height="80">
			<?php }elseif($_SESSION['index_id'] == 'e'){?>
			<img src="../../public/<?=$_SESSION['site_id']?>/images/mlogoe.png" alt="logo" height="80">
			<?php }elseif($_SESSION['index_id'] == 'f'){?>
			<img src="../../public/<?=$_SESSION['site_id']?>/images/mlogof.png" alt="logo" height="80">
			<?php }elseif($_SESSION['index_id'] == 'g'){?>
			<img src="../../public/<?=$_SESSION['site_id']?>/images/mlogog.png" alt="logo" height="80">
			<?php }elseif($_SESSION['index_id'] == 'h'){?>
			<img src="../../public/<?=$_SESSION['site_id']?>/images/mlogoh.png" alt="logo" height="80">
			<?php }?>
		<?php }else{?>
		<img src="../../public/<?=$_SESSION['site_id']?>/images/mlogo.png" alt="logo" height="80">
		<?php }?>
		</div>

	<!-- 	<div id="notice_box" style="left:220px">
		<marquee style="cursor: pointer;" onMouseOut="this.start();" onMouseOver="this.stop();" id="msgNews" direction="left" scrolldelay="5" scrollamount="2">&nbsp;&nbsp;<?=$notice['notice_content'] ?>&nbsp;&nbsp;</marquee>
		</div> -->

		<div id="nav_sub_box"><span>原始帳戶：<?=$_SESSION["login_name"]?></span> |
		<span>登陸帳戶：<?=$_SESSION["login_name_1"]?></span> |<a href="../../set_pwd.php" target="admin_func" style="">修改密碼</a> |	<a href="../../out.php" target="_top" style="">安全退出</a>
		</div>
		<div id="vlock"></div>
		<div id="nav_main_box" style="margin-top: 40px;">
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
	<div class="nav_second_wrap" style="">
		<?php foreach ($account as $key => $val) {?>
		<a href="<?=$val['url']?>?uid=<?=$uid ?>" target="admin_func"  style=""><?=$val['name']?></a>
		<?php }?>
	</div>

<!-- 注单管理 -->
<div class="nav_second_wrap" style="display:none">
 <?php foreach ($note as $key => $val) {
   ?>
    <a href="<?=$val['url']?>" target="admin_func"  style=""><?=$val['name']?></a>
   <?php }?>
</div>
<!-- 报表管理 -->
<div class="nav_second_wrap" style="display:none">
	<?php foreach ($report as $key => $val) {?>
    <a href="<?=$val['url']?>" target="admin_func"  style=""><?=$val['name']?></a>
   <?php }?>
</div>
<!-- 赛果管理 -->
<div class="nav_second_wrap" style="display:none">
	<?php foreach ($result as $key => $val) {?>
	<a href="<?=$val['url']?>" target="admin_func"  style=""><?=$val['name']?></a>
	<?php }?>
</div>
<!-- 财务管理 -->
<div class="nav_second_wrap" style="display:none">
	<?php foreach ($cash as $key => $val) {?>
	<a href="<?=$val['url']?>" target="admin_func"  style=""><?=$val['name']?></a>
	<?php }?>
</div>
<!-- 其它管理 -->
<div class="nav_second_wrap" style="display:none">
<?php foreach ($other as $key => $val) {?>
<a href="<?=$val['url']?>" target="admin_func"  style=""><?=$val['name']?></a>
<?php }?>
</div>

<!-- nav_second_wrap end -->

<!-- con_wrap -->
	<div id="con_wrap">

	</div>
<!-- con_wrap end -->

</div>

</body></html>