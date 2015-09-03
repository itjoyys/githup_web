<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-07-03 01:07:21
         compiled from "/home/wwwuser/public_html/A_index/website/templates/notice_data.html" */ ?>
<?php /*%%SmartyHeaderCode:6675023105585d558912942-66987779%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0428587e356632f7ddc5e64483863d21239afaef' => 
    array (
      0 => '/home/wwwuser/public_html/A_index/website/templates/notice_data.html',
      1 => 1435671225,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6675023105585d558912942-66987779',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5585d55895f479_37815301',
  'variables' => 
  array (
    'list' => 0,
    'con' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5585d55895f479_37815301')) {function content_5585d55895f479_37815301($_smarty_tpl) {?><?php  $_config = new Smarty_Internal_Config("public.conf", $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars("public", 'local'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="<?php echo $_smarty_tpl->getConfigVariable('css');?>
/standard.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $_smarty_tpl->getConfigVariable('css');?>
/HotNewsHistory.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        #title { background: url(<?php echo $_smarty_tpl->getConfigVariable('images');?>
/history/title.jpg) left top no-repeat; }
        #top { background: url(<?php echo $_smarty_tpl->getConfigVariable('images');?>
/history/bg_t.jpg) left top no-repeat; width: 720px; height: 58px; }
        #container { background: url(<?php echo $_smarty_tpl->getConfigVariable('images');?>
/history/bg_c.jpg) left top repeat-y; margin: 0 auto; padding: 0 37px; width: 646px; }
        #footer { background: url(<?php echo $_smarty_tpl->getConfigVariable('images');?>
/history/bg_f.jpg) left top no-repeat; padding-top: 20px; width: 720px; height: 35px; }
    </style>
</head>
<body>
<div id="main-wrap">
    <div id="top">
        <div id="title"></div>
    </div>
    <div id="container">
        <div class="content">
            <div class="date"><span class="content-title">日期</span></div>
            <div class="msg"><span class="content-title">内容</span></div>
            <div class="clear"></div>
        </div>
        <div class="line"></div>
		<?php  $_smarty_tpl->tpl_vars['con'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['con']->_loop = false;
 $_smarty_tpl->tpl_vars['cid'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['con']->key => $_smarty_tpl->tpl_vars['con']->value) {
$_smarty_tpl->tpl_vars['con']->_loop = true;
 $_smarty_tpl->tpl_vars['cid']->value = $_smarty_tpl->tpl_vars['con']->key;
?>
        <div class="content">
            <div class="date"><span class="inner"><?php echo $_smarty_tpl->tpl_vars['con']->value['add_time'];?>
</span></div>
            <div class="msg"><p class="inner-title"><?php echo $_smarty_tpl->tpl_vars['con']->value['chn_simplified'];?>
</p></div>
			<div class="clear"></div>
        </div>
        <div class="line"></div>
       <?php } ?>
    </div>
    <div id="footer">
    </div>
</div>
</body>
</html><?php }} ?>
