<?php /* Smarty version Smarty-3.1.21-dev, created on 2016-01-14 03:48:17
         compiled from "D:\php\web_20156\index_ci\t_wap\views\\lottery\liuhecai\228.html" */ ?>
<?php /*%%SmartyHeaderCode:49105694a2b68a0a71-72960619%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8cbadd063e25f01e0d46820af5b8f4a4adce0247' => 
    array (
      0 => 'D:\\php\\web_20156\\index_ci\\t_wap\\views\\\\lottery\\liuhecai\\228.html',
      1 => 1452757199,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '49105694a2b68a0a71-72960619',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5694a2b690a205_03670259',
  'variables' => 
  array (
    'gamename' => 0,
    'list' => 0,
    'v' => 0,
    'k' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5694a2b690a205_03670259')) {function content_5694a2b690a205_03670259($_smarty_tpl) {?><ion-content class="tp40">
<div data-title="<?php echo $_smarty_tpl->tpl_vars['gamename']->value;?>
" data-title2="<?php echo $_GET['gamename2'];?>
"
	class="text-left">
	<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
	<div class="row">
		<div class="col bet ban-bo" data-id="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">
			<div class="bet-content"><?php echo $_smarty_tpl->tpl_vars['v']->value['input_name'];?>
</div>
			<div class="bet-item"></div>
			<div class="bet-qiu">
				<div ng-bind-html="'<?php echo banbo_arr($_smarty_tpl->tpl_vars['k']->value);?>
'|yd:'3'"></div>
			</div>
		</div>
	</div>
	<?php } ?>
</div>
</ion-content><?php }} ?>
