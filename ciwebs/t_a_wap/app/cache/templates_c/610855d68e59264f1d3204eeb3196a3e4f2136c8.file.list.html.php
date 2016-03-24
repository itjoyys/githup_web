<?php /* Smarty version Smarty-3.1.21-dev, created on 2016-01-14 23:38:19
         compiled from "/home/wwwuser/public_html/index_ci/t_wap/views//lottery/liuhecai/list.html" */ ?>
<?php /*%%SmartyHeaderCode:1183644765569869ab378861-87582191%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '610855d68e59264f1d3204eeb3196a3e4f2136c8' => 
    array (
      0 => '/home/wwwuser/public_html/index_ci/t_wap/views//lottery/liuhecai/list.html',
      1 => 1452588901,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1183644765569869ab378861-87582191',
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
  'unifunc' => 'content_569869ab3f0898_01709072',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_569869ab3f0898_01709072')) {function content_569869ab3f0898_01709072($_smarty_tpl) {?><ion-content class="bet-view" scroll="false">
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
