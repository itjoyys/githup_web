<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-29 04:13:33
         compiled from "/home/wwwuser/public_html/t/website/templates/sports.html" */ ?>
<?php /*%%SmartyHeaderCode:8585147275590fe2d683ca7-15108081%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cd8052c73492283439dfa8c5dc1d650b8eca2711' => 
    array (
      0 => '/home/wwwuser/public_html/t/website/templates/sports.html',
      1 => 1434035166,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8585147275590fe2d683ca7-15108081',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'header' => 0,
    'notice_html' => 0,
    'bottom' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5590fe2d6c61f0_13140214',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5590fe2d6c61f0_13140214')) {function content_5590fe2d6c61f0_13140214($_smarty_tpl) {?><?php echo $_smarty_tpl->tpl_vars['header']->value;?>

<?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
<!--banner-->
<div class="PageBnner2"></div>
<!--banner end-->
<?php echo $_smarty_tpl->tpl_vars['notice_html']->value;?>

<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->getConfigVariable('css');?>
/video.css?v=1.0"/>
<div class="CenterMain">
    <div  style="margin: -60px auto 20px; width: 1100px;">
        <iframe src="sport/sport_main.php" frameborder="no" style="width: 1100px; height: 620px;" scrolling="no"></iframe>

    </div>

  
</div>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->getConfigVariable('js');?>
/video.js"><?php echo '</script'; ?>
>

<!--footer-->
<?php echo $_smarty_tpl->tpl_vars['bottom']->value;?>

<?php }} ?>
