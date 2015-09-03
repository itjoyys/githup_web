<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-22 03:43:25
         compiled from "D:\WWW\jpk\web_20156\A_index\website\templates\sports.html" */ ?>
<?php /*%%SmartyHeaderCode:229365585c32320a742-25718838%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '771cc083e2501a72b414439d3171d3386214668a' => 
    array (
      0 => 'D:\\WWW\\jpk\\web_20156\\A_index\\website\\templates\\sports.html',
      1 => 1434915727,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '229365585c32320a742-25718838',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5585c32332b887_69641275',
  'variables' => 
  array (
    'header' => 0,
    'notice_html' => 0,
    'bottom' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5585c32332b887_69641275')) {function content_5585c32332b887_69641275($_smarty_tpl) {?><?php echo $_smarty_tpl->tpl_vars['header']->value;?>

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
