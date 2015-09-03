<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-29 04:13:07
         compiled from "/home/wwwuser/public_html/t/website/templates/index.html" */ ?>
<?php /*%%SmartyHeaderCode:1924747085590fe1383f5b5-65765611%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e4e3fc78f3046c6cce4e68a2ef81dd419e8c8e93' => 
    array (
      0 => '/home/wwwuser/public_html/t/website/templates/index.html',
      1 => 1435131388,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1924747085590fe1383f5b5-65765611',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'intr' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5590fe13849b63_92328774',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5590fe13849b63_92328774')) {function content_5590fe13849b63_92328774($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
    <style>
        html{width: 100%;height: 100%;}
        body{width: 100%;height: 100%;margin: 0;}
    </style>
</head>

<frameset rows="*,0,0,0" frameborder="NO" border="0" framespacing="0">
    <frame name="mem_index" id="mem_index" src="./index.php?a=<?php if ($_smarty_tpl->tpl_vars['intr']->value) {?>zhuce<?php } else { ?>N_index<?php }?>">
    <frame src="" noresize="" scrolling="NO" name="act">
<frame src="./index.php?a=upupFlash" noresize="" scrolling="NO" name="upupMsg">
<frame src="./index.php?a=refresh" noresize="" scrolling="NO" name="refresh">
</frameset>
<noframes> <body> <!--[if lte IE 6]> <div id="ie6-warning">您正在使用 Internet Explorer 6，在本页面的显示效果可能有差异。建议您升级到 <a href="http://www.microsoft.com/china/windows/internet-explorer/" target="_blank">Internet Explorer 8</a> 或以下浏览器： <a href="http://www.mozillaonline.com/">Firefox</a> / <a href="http://www.google.com/chrome/?hl=zh-CN">Chrome</a> / <a href="http://www.apple.com.cn/safari/">Safari</a> / <a href="http://www.operachina.com/">Opera</a> </div> <?php echo '<script'; ?>
 language="javascript" type="text/javascript"> function position_fixed(el, eltop, elleft){ // check if this is IE6 if(!window.XMLHttpRequest) window.onscroll = function(){ el.style.top = (document.documentElement.scrollTop + eltop)+"px"; el.style.left = (document.documentElement.scrollLeft + elleft)+"px"; } else el.style.position = "fixed"; } position_fixed(document.getElementById("ie6-warning"),0, 0); <?php echo '</script'; ?>
> <![endif]--> </body> </noframes>
</html><?php }} ?>
