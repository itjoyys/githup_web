<?php /* Smarty version Smarty-3.1.21-dev, created on 2016-01-21 18:37:16
         compiled from "D:\WWW\web_20156\index_ci\t_wap\views\\lottery\liuhecai\list.html" */ ?>
<?php /*%%SmartyHeaderCode:3211356a0b4dc4b7377-18960269%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6cf754f871d77e6f1e0773465ec74e31120bd25a' => 
    array (
      0 => 'D:\\WWW\\web_20156\\index_ci\\t_wap\\views\\\\lottery\\liuhecai\\list.html',
      1 => 1452602061,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3211356a0b4dc4b7377-18960269',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'config' => 0,
    'v' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_56a0b4dc561978_59414657',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56a0b4dc561978_59414657')) {function content_56a0b4dc561978_59414657($_smarty_tpl) {?><ion-content class="bet-view" scroll="false">
<div>
	<sub-navs item-class="col-<?php if (count($_smarty_tpl->tpl_vars['config']->value)==1) {?>100<?php } elseif (count($_smarty_tpl->tpl_vars['config']->value)==2) {?>50<?php } else { ?>31<?php }?>">
	<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['config']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
		<sub-nav title="<?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
"
		url="/lottery/liuhecai?gameid=<?php echo $_GET['gameid'];?>
&LotteryId=<?php echo $_GET['LotteryId'];?>
&gamename2=<?php echo $_smarty_tpl->tpl_vars['v']->value['name'];?>
"></sub-nav>
	<?php } ?>
</div>
</ion-content><?php }} ?>
