<?php /* Smarty version Smarty-3.1.21-dev, created on 2016-01-14 23:26:08
         compiled from "/home/wwwuser/public_html/index_ci/t_wap/views/bbin.html" */ ?>
<?php /*%%SmartyHeaderCode:104404793569866d0663d08-79222013%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ae9a11837373c96d21647e503d7e1c258b069478' => 
    array (
      0 => '/home/wwwuser/public_html/index_ci/t_wap/views/bbin.html',
      1 => 1452763689,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '104404793569866d0663d08-79222013',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_569866d06a3d81_51437140',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_569866d06a3d81_51437140')) {function content_569866d06a3d81_51437140($_smarty_tpl) {?><!DOCTYPE html>
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
			<div class="mobile-viewport" style="width: 381px; height: 667px;">
				<div class="mobile-scroller" style="width: 381px; height: 667px;">
					<!--第一個廣宣-start-->
					<div class="scroll-wrap scroll-1">
						<div class="scroll-wrap-bg">
							<div id="js-mobile-app" class="scroll-bg mobile-app" style="width: 381px; height: 667px;">
								<div class="title">
									<img src="/public/default/images/title.png">
								</div>

								<div class="sub-title-wrap">
									<div class="sub-top">
										整合所有游戏
									</div>

									<div class="sub-bottom">
										随心所欲 随时随地
									</div>
								</div>

								<button id="js-scroll-download" type="button"></button>
							</div>

							<!-- ios9訊息說明 -->
						</div>

						<!--ios9頁籤-->
					</div>
					<!--第一個廣宣-end-->
				</div>

				<!--20150610需求，切換廣宣功能-->
				<!--<div id="js-mobile-dot-wrap" class="mobile-dot-wrap">-->
				<!--<div class="mobile-dot"></div>-->
				<!--</div>-->
			</div>
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
