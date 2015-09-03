
<?php 
include_once("../../comm_menu.php");
$uid=$_SESSION['adminid'];
//账号权限判断
if(isset($_SESSION["adminid"])){
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
	if(newscount > 0)
	{
		$.get('admin_left.php',function(data){
			setTimeout(function(){//parent.document.getElementById("admin_func").contentWindow
				top.openGg("/app/ssc/admin/main.php?action=lastnews&uid=<?=$uid ?>&langx=" + langx + "&lx=" + lxvar);
			}, 5000);			
		});
	}
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
		<img src="../../public/<?=$_SESSION['site_id']?>/images/mlogo.png" alt="logo" height="80">
		</div>
		
	<!-- 	<div id="notice_box" style="left:220px">
		<marquee style="cursor: pointer;" onMouseOut="this.start();" onMouseOver="this.stop();" id="msgNews" direction="left" scrolldelay="5" scrollamount="2">&nbsp;&nbsp;尊貴的客戶們, 體育項目維護將由本星期五提前至星期四(北京時間01月29日 - 14:00至19:15) 該時間暫時停止服務以進行系統維護, 如有不便之處, 敬請見諒.&nbsp;&nbsp;</marquee>
		</div>
		 -->
		<div id="nav_sub_box">
		<span>登陸帳戶：<?=$_SESSION["login_name"]?></span> |<a href="../../set_pwd.php" target="admin_func" style="">修改密碼</a> |	<a href="../../out.php" target="_top" style="">安全退出</a>
		</div>
		<div id="vlock"></div>
		<div id="nav_main_box">
			<ul>
				<li>
					<span><a href="#" style="background-position:0 0" class="menu_hover">帳號管理</a></span>
<span><a href="#" style="">即時注單</a></span>
<span><a href="#" style="">報表查詢</a></span>
<span><a href="#" style="">賽果/規則</a></span>
<span><a href="#" style="">現金系統</a></span>
<span><a href="#" style="">其他<span id="msg"></span></a></span>

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