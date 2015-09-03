<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-07-01 23:40:29
         compiled from "/home/wwwuser/public_html/A_index/website/templates/sports.html" */ ?>
<?php /*%%SmartyHeaderCode:14070714055798efa6d5ab7-64660711%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2d4e401a72e19c3ececf029b1a27dfd086e52372' => 
    array (
      0 => '/home/wwwuser/public_html/A_index/website/templates/sports.html',
      1 => 1435671225,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14070714055798efa6d5ab7-64660711',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_55798efa739465_53394890',
  'variables' => 
  array (
    'header' => 0,
    'notice_html' => 0,
    'bottom' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55798efa739465_53394890')) {function content_55798efa739465_53394890($_smarty_tpl) {?><?php echo $_smarty_tpl->tpl_vars['header']->value;?>

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
