<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-07-26 00:10:19
         compiled from "D:\WWW\web_20156\A_index\website\templates\upupFlash.html" */ ?>
<?php /*%%SmartyHeaderCode:784655b3b4ebc9cca9-42184805%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '17c1bd2b90f534910e3ee0c4a5bada5ae88cfbe1' => 
    array (
      0 => 'D:\\WWW\\web_20156\\A_index\\website\\templates\\upupFlash.html',
      1 => 1437759046,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '784655b3b4ebc9cca9-42184805',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_55b3b4ebcd37a1_00483941',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b3b4ebcd37a1_00483941')) {function content_55b3b4ebcd37a1_00483941($_smarty_tpl) {?><html>
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
