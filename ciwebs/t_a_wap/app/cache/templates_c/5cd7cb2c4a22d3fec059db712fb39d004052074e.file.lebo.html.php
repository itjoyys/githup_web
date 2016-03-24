<?php /* Smarty version Smarty-3.1.21-dev, created on 2016-01-14 23:29:37
         compiled from "/home/wwwuser/public_html/index_ci/t_wap/views/lebo.html" */ ?>
<?php /*%%SmartyHeaderCode:666909578569867a19391d6-40012265%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5cd7cb2c4a22d3fec059db712fb39d004052074e' => 
    array (
      0 => '/home/wwwuser/public_html/index_ci/t_wap/views/lebo.html',
      1 => 1452763689,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '666909578569867a19391d6-40012265',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_569867a197eb92_18601804',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_569867a197eb92_18601804')) {function content_569867a197eb92_18601804($_smarty_tpl) {?><!-- saved from url=(0020)https://leboapp.com/ -->
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>LeBo Game</title>
		<link rel="shortcut icon" href="/public/default/images/favicon.ico">
		<style type="text/css">
			#banner_top {
				width: 98%;
				height: 100px;
				background-image: url("/public/default/images/bg_duplicate.png");
				text-align: center;
				padding-top: 25px;
			}
		</style>

		<?php echo '<script'; ?>
 type="text/javascript">
			window.onload = function() {
				if (isWechat()) {
					document.getElementById("browser").style.display = 'none';
					document.getElementById("wechat").style.display = '';
				} else {
					document.getElementById("browser").style.display = '';
					if (browser.versions.mobile) {
						document.getElementById("horizontal").style.width = '100%';
						document.getElementById("vertical").style.display = '';
						document.getElementById("horizontal").style.display = 'none';
					} else {
						document.getElementById("horizontal").style.width = '70%';
						document.getElementById("vertical").style.display = 'none';
						document.getElementById("horizontal").style.display = '';
					}
				}
			}

			function isWechat() {
				var ua = window.navigator.userAgent.toLowerCase();
				if (ua.match(/MicroMessenger/i) == 'micromessenger') {
					return true;
				} else {
					return false;
				}
			}
			var browser = {
					versions: function() {
						var u = navigator.userAgent,
							app = navigator.appVersion;
						return { //移动终端浏览器版本信息 
							trident: u.indexOf('Trident') > -1, //IE内核 
							presto: u.indexOf('Presto') > -1, //opera内核 
							webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核 
							gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核 
							mobile: !!u.match(/AppleWebKit.*Mobile.*/), // || !!u.match(/AppleWebKit/), //是否为移动终端 
							ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端 
							android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器 
							iPhone: u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1, //是否为iPhone或者QQHD浏览器 
							iPad: u.indexOf('iPad') > -1, //是否iPad 
							webApp: u.indexOf('Safari') == -1
								//是否web应该程序，没有头部与底部 
						};
					}(),
					language: (navigator.browserLanguage || navigator.language).toLowerCase()
				}
				// 	document.writeln(navigator.userAgent); 
				// 	document.writeln("语言版本: "+browser.language); 
				// 	document.writeln(" 是否为移动终端: "+browser.versions.mobile); 
				// 	document.writeln(" ios终端: "+browser.versions.ios); 
				// 	document.writeln(" android终端: "+browser.versions.android); 
				// 	document.writeln(" 是否为iPhone: "+browser.versions.iPhone); 
				// 	document.writeln(" 是否iPad: "+browser.versions.iPad); 
				// 	document.writeln(navigator.userAgent);
			function orientationChange() {
				switch (window.orientation) {
					case 0:
						document.getElementById("vertical").style.display = '';
						document.getElementById("horizontal").style.display = 'none';
						break;
					case 180:
						document.getElementById("vertical").style.display = '';
						document.getElementById("horizontal").style.display = 'none';
						break;
					case -90:
						document.getElementById("vertical").style.display = 'none';
						document.getElementById("horizontal").style.display = '';
						break;
					case 90:
						document.getElementById("vertical").style.display = 'none';
						document.getElementById("horizontal").style.display = '';
						break;
				}
			}
			window.addEventListener("onorientationchange" in window ? "orientationchange" : "resize", orientationChange, false);
		<?php echo '</script'; ?>
>

	</head>

	<body>
		<div id="wechat" style="display: none; width: 100%">
			<img alt="" src="/public/default/images/wechat_help_sc.png" style="width: 100%">
		</div>
		<div id="browser">
			<div id="horizontal" style="margin-left: auto; margin-right: auto; width: 100%; height: 170%; display: none; background-image: url(/public/default/images/horizontal_ios.jpg); background-size: 100%; background-repeat: no-repeat;">
				<a href="itms-services://?action=download-manifest&url=https://www.leboapp.com/lebogaming_ios.plist">
					<img alt="" src="/public/default/images/install_btn.png" style="margin-top: 34%;margin-left: 72%;width:20%"></a>
				<br>
				<br>
				<br>
			</div>
			<div id="vertical" style="width: 100%; height: 120%; text-align: center; padding-top: 3%; background-image: url(/public/default/images/vertical.jpg); background-size: 100%; background-repeat: no-repeat;">
				<span style="color: white;font-size: xx-large;font-family: 黑体;">LEBO游戏苹果手机客户端</span>
				<span style="color: #02245b;font-size: xx-large;font-family: 黑体;display: inherit;padding-top: 10%;padding-bottom:45%;padding-left: 38%;text-align: left;">
			<br>
			·&nbsp;跨越地域随时显示<br>
			·&nbsp;支援iPhone型号: iPhone 4或以上<br>
			·&nbsp;支援系统版本: iOS 6.0或以上
		</span>

				<a href="itms-services://?action=download-manifest&url=https://www.leboapp.com/lebogaming_ios.plist">
					<br>
					<br>
					<br><img alt="" src="/public/default/images/install_btn.png" style="margin-top: 16%;width: 60%;">
				</a>
				<br>
				<span style="color: #ab9984;font-size: xx-large;font-family: 黑体;display: inherit;">更新日期：&nbsp;2015年11月12日</span>
				<span style="color: #ab9984;font-size: 36;font-family: 黑体;text-align: left;padding-left: 5%;width: 85%;">
			<br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;注册登入LEBO手机客户端便可以享受更高层次的博娱游戏。不管何时，不管何地，只要一机在手，便可随地玩游。此为LEBO唯一官方认证，版权所有。
		</span>
			</div>
		</div>

	</body>

</html><?php }} ?>
