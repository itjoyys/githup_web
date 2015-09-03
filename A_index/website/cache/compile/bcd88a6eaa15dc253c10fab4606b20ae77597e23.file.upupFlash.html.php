<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-29 04:13:09
         compiled from "/home/wwwuser/public_html/t/website/templates/upupFlash.html" */ ?>
<?php /*%%SmartyHeaderCode:17603036415590fe15e47023-52118635%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bcd88a6eaa15dc253c10fab4606b20ae77597e23' => 
    array (
      0 => '/home/wwwuser/public_html/t/website/templates/upupFlash.html',
      1 => 1200347104,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17603036415590fe15e47023-52118635',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5590fe15e875f2_07756601',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5590fe15e875f2_07756601')) {function content_5590fe15e875f2_07756601($_smarty_tpl) {?><html>
<head>
<meta charset="UTF-8">
<?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
<title>welcome</title>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/jquery-1.8.3.min.js"> <?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/jquery.cookie.js"> <?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/swfobject.js"> <?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/float.js"> <?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/upup.js"> <?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>

var langx; 
var _UNDER = {};
var liveconf = "7,1,5,6,8,9";

function checkJS() {// flash 載入後，呼叫此用以確認JS 初始化完成
	return jsReady;
}
function pageInit() {// 載入html頁面後，設定jsReady = true，讓flash 可以確認JS 狀態
	jsReady = true;
}
<?php echo '</script'; ?>
>
</head>
<body onload="pageInit();">
</body><?php }} ?>
