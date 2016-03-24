<?php /* Smarty version Smarty-3.1.21-dev, created on 2016-01-26 17:52:58
         compiled from "E:\work\web_20156\index_ci\t_wap\views\index.html" */ ?>
<?php /*%%SmartyHeaderCode:1023856a741fa613bd6-44470992%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '44d1786f31ba524c65d121a86e9d71638fc1a4c3' => 
    array (
      0 => 'E:\\work\\web_20156\\index_ci\\t_wap\\views\\index.html',
      1 => 1453629789,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1023856a741fa613bd6-44470992',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'OLD_URL' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_56a741fa6717e7_95547421',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56a741fa6717e7_95547421')) {function content_56a741fa6717e7_95547421($_smarty_tpl) {?><!DOCTYPE html>
<html ng-app="MaYaOfficial">
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
<meta name="format-detection" content="telephone=no">
<link id="appTouchIcon" href="#" sizes="114x114" rel="apple-touch-icon-precomposed">
<title id="appTitle"></title>
<link rel="stylesheet" href="public/official/styles/common/common.css?v=23db2edf1">
<?php echo '<script'; ?>
 type="text/javascript">
    try{
      if(self != top && top.location.host == self.location.host){
        top.location.href = self.location.href;
      }
    } catch (e) {

    }
<?php echo '</script'; ?>
>
</head>
<body>
<input type="hidden" id="CDNURL" name="CDNURL" value="public/official/" />
<input type="hidden" id="APIURL" name="APIURL" value="<?php echo $_smarty_tpl->tpl_vars['OLD_URL']->value;?>
/api"/>
<ion-nav-view>
</ion-nav-view>
<?php echo '<script'; ?>
 src="public/default/js/vendor.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="public/official/scripts/lang.js?v=fbbf677a1"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="public/official/scripts/scripts.js?v=d97658691"><?php echo '</script'; ?>
>
</body>
</html><?php }} ?>
