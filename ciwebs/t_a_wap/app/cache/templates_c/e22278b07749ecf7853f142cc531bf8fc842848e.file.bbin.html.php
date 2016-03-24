<?php /* Smarty version Smarty-3.1.21-dev, created on 2016-01-23 15:53:08
         compiled from "D:\WWW\web_20156\index_ci\t_wap\views\bbin.html" */ ?>
<?php /*%%SmartyHeaderCode:16456a33164db6694-34160896%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e22278b07749ecf7853f142cc531bf8fc842848e' => 
    array (
      0 => 'D:\\WWW\\web_20156\\index_ci\\t_wap\\views\\bbin.html',
      1 => 1453531441,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16456a33164db6694-34160896',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_56a33164dfa779_15129903',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56a33164dfa779_15129903')) {function content_56a33164dfa779_15129903($_smarty_tpl) {?><!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<title>手机下注说明</title>
		<link href="/public/default/css/standard.css" rel="stylesheet">
		<link href="/public/default/css/mobile.css" rel="stylesheet">
	</head>

	<body>
		<div class="mobile-main">
			<div class="mobile-viewport" >
				<div class="mobile-scroller">
					<!--第一個廣宣-start-->
					<div class="scroll-wrap scroll-1">
					    <div class="scroll-wrap-bg">
						 <div id="js-mobile-app" class="scroll-bg mobile-app">
								<div>
								<span class="title"><img src="/public/default/images/title.png"></span>

								<p class="sub-title-wrap">
									<span class="sub-top" style="display: block;">整合所有游戏</span>

									<span class="sub-bottom" style="display: block;">随心所欲 随时随地</span>
								</p>
								</div>

							</div>
						<div class="anzhuang">
									<span class="zhinan">安装指南</span>
									<span class="bz_1">步骤一：</span>
									<span class="bz_1_1">1.登录电脑版或WAP页面，完成注册获取手机客户端账号及设置密码。</span>
									<span class="bz_1_1">2.点击下方链接下载手机APP。</span>
                                    
									<span class="bz_2">步骤二：</span>
									<span class="bz_2_2">ios系统手机首次开启应用程式将会弹出询问视窗。</span>
									<span class="sp_img"><img src="/public/default/images/IMG_0222.PNG" ></span>
									<span class="bz_2_2">只需在，设置-通用-设备管理-应用,点击信任即可进入客户端。</span>
									<span class="sp_img"><img src="/public/default/images/IMG_0223.PNG"></span>
									<span class="bz_2_2">安卓系统手机需要安全设置。</span>
									<span class="bz_2_2">设定-安全-未知来源</span>
									<span class="sp_img"><img src="/public/default/images/anzhuo.png" ></span>
									<span class="bz_3">步骤三：</span>
									<span class="bz_3_3">进入App,登录账户</span>
									<span class="sp_img"><img src="/public/default/images/login.jpg"></span>
									<span class="bz_3">点击下面按钮下载BBIN APP</span>
									<button id="js-scroll-download" type="button"></button>

									
								</div>
						
					    
					</div>
				</div>
					<!--第一個廣宣-end-->
			</div>

				<!--20150610需求，切換廣宣功能-->
				<!--<div id="js-mobile-dot-wrap" class="mobile-dot-wrap">-->
				<!--<div class="mobile-dot"></div>-->
				<!--</div>-->
		</div>
		

		<!--微信提示-->
		<div id="js-wechat" class="mobile-wechat">
			<div class="mobile-wechat-wrap">
				<div id="js-wechat-close" class="mobile-wechat-close"></div>
			</div>
		</div>

		<!--禁止橫向-->
		<div class="landscape"></div>

		<?php echo '<script'; ?>
 src="/public/default/js/jquery-1.9.1.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/public/default/js/iscroll.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/public/default/js/mobile.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
>
			$(function() {
				$('#js-scroll-download').on('click', function() {
					//scroll-1廣宣，下載按鈕狀態
					$(this).addClass('active');
					//scroll-1廣宣，提示
					var check = confirm("确定安装此 APP");
					if (check === true) {
						location.href = 'itms-services://?action=download-manifest&amp;url=https://app.bbimgs.com/ipl/app/flash/publicbmw/DesktopInstall/app/mobile/cocos/ios/BBVegas_1028.plist';
						return;
					}
				});
			});
		<?php echo '</script'; ?>
>

	</body>

</html>
<?php }} ?>
